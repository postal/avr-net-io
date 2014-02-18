<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 12.11.13
 * Time: 20:00
 */

namespace Ron\RaspberryPiBundle\Command;


use Goutte\Client;
use Ron\RaspberryPiBundle\Lib\EbayKleinanzeige;
use Ron\RaspberryPiBundle\Lib\EbayKleinanzeigenClient;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class EbayCommand
 * @package Ron\RaspberryPiBundle\Command
 */
class EbayCommand extends ContainerAwareCommand
{
    /**
     * @var string
     */
    protected $url = 'http://kleinanzeigen.ebay.de/anzeigen/s-suchanfrage.html?keywords=hochbett+paidi&categoryId=&locationStr=Berlin&locationId=3331&radius=50&sortingField=SORTING_DATE&adType=&posterType=&pageNum=1&action=find&maxPrice=&minPrice=';

    /**
     * @var string
     */
    public $filename = 'last_search_result.txt';
    /**
     * @var string
     */
    protected $mail = 'siedler1@freenet.de';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('ebay:search')
            ->setDescription('Ebay Kleinanzeigen');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->check();
        } catch (\Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        $ebaySearch = new EbayKleinanzeigenClient();
        $ebaySearch
            ->setKeywords('hochbett paidi')
            ->setLocationStr('Berlin')
            ->setRadius('50')
            ->setSortingField(EbayKleinanzeigenClient::SORT_BY_DATE);

        $client = new Client();
        $output->writeln($ebaySearch->getUrl());
        /**
         * $crawler Symfony\Component\DomCrawler\Crawler
         */
        $crawler = $client->request('GET', $ebaySearch->getUrl());

        $i = 1;
        $content = "";
        $emailData = array();
        $kleinanzeigeCollection = array();
        foreach ($crawler->filterXPath('//table[@id="srchrslt-adtable"]/child::tr') as $rowNode) {
            /** @var $rowNode \DOMElement */

            $crawler = new Crawler();
            $crawler->addNode($rowNode);

            $description = $crawler->filter('.c-td-adtble-dscr p');
            if (0 == $description->count()) {
                $description = '';
            } else {
                $description = $description->text();
            }

            $price = $crawler->filter('.price');
            if (0 == $price->count()) {
                $price = '';
            } else {
                $price = $price->text();
            }

            $locationStr = str_replace('<br>', ' ', ($crawler->filter('h3.c-h-adtble-lctn')->html()));
            $title = $crawler->filter('a.ad-title')->text();
            $imageLink = $crawler->filter('tr.c-tr-adtble td.c-td-adtble-img a.c-ad-imgbx-listitem')->attr('data-imgsrc');
            $link = $crawler->filter('.c-td-adtble-dscr a.ad-title')->attr('href');
            $id = $this->getIdFromLink($link);
            $date = $crawler->filter('td.c-td-adtble-time')->text();
            $content .= "\n" . $i . '. ' . $title . '|' . $description . '|' . $price . '|' . $locationStr;

            $emailData['anzeigen'][] = array(
                'id' => $id,
                'title' => $title,
                'imgLink' => $imageLink,
                'description' => $description,
                'price' => $price,
                'location' => $locationStr,
                'link' => $link,
                'date' => $date,
            );

            $kleinanzeige = new EbayKleinanzeige();
            $kleinanzeige->setId($id);
            $kleinanzeige->setTitle($title);
            $kleinanzeige->setDescription($description);
            $kleinanzeige->setPrice($price);
            $kleinanzeige->setLocationStr($locationStr);
            $kleinanzeige->setLink($link);
            $kleinanzeige->setDate($date);

            $kleinanzeigeCollection[] = $kleinanzeige;
            $i++;
        };

        $content = $this->createTempFileContent($kleinanzeigeCollection);

        if ($this->getLastContent() != $content) {
            $output->writeln('Angebote haben sich geändert.');

            if ($this->getTempFilename()) {
                file_put_contents($this->getTempFilename(), base64_encode($content));
            }

            $emailData['url'] = $ebaySearch->getUrl();
            $message = $this->getMessage($emailData);
            $mailer = $this->getContainer()->get('mailer');
            $mailer->send($message);

            $output->writeln('Eine E-Mail wurde versand.');
            $spool = $mailer->getTransport()->getSpool();
            $transport = $this->getContainer()->get('swiftmailer.transport.real');

            $spool->flushQueue($transport);
        } else {
            $output->writeln('Die Angebote haben sich nicht geändert.');
        }
    }

    /**
     * build the email message
     *
     * @param $data
     * @return \Swift_Message
     */
    private function getMessage($data)
    {
        $message = new \Swift_Message();
        $message->setSubject('Neues Angebot');
        $message->setContentType('text/html');
        $message->setTo($this->mail);
        $message->setBody($this->getBody($data));
        $message->addFrom('keller.servebeer@gmail.com');
        return $message;
    }

    /**
     * Builds the email body
     *
     * @param $data
     * @return mixed
     */
    protected function getBody($data)
    {
        $templating = $this->getContainer()->get('templating');
        $body = $templating->render('RonRaspberryPiBundle:Ebay:ebay_mail.html.twig', array('data' => $data));

        return $body;
    }

    /**
     * @throws \Exception
     */
    private function check()
    {
        $tmpDir = $this->getContainer()->getParameter('ebay_temp_dir');

        if (!is_dir($tmpDir) || !is_writable($tmpDir)) {
            throw new \Exception('Dir (' . $tmpDir . ') not exist or not writable.');
        }
    }

    /**
     * load last content file
     * @return string
     */
    private function getLastContent()
    {
        $lastContent = '';
        $path = $this->getTempFilename();

        if (file_exists($path) && is_readable($path)) {
            $lastContent = file_get_contents($path);
        }

        return $lastContent;
    }

    /**
     * return temp filename
     * @return string
     */
    protected function getTempFilename()
    {
        $dirName = $this->getContainer()->getParameter('ebay_temp_dir');
        $path = $dirName .'/'. $this->filename;

        return $path;
    }

    /**
     * @param $kleinanzeigeCollection
     * @return array
     */
    private function createTempFileContent($kleinanzeigeCollection)
    {
        $content = array();
        foreach ($kleinanzeigeCollection as $kleinanzeige) {
            $content[] = serialize($kleinanzeige);
        }

        return implode("\n", $content);
    }

    /**
     * @param $link
     * @return string
     */
    private function getIdFromLink($link)
    {
        $linkParts = explode('/', $link);
        $idPart = end($linkParts);
        $pos = strpos($idPart, '?');
        $id = substr($idPart, 0, $pos);

        return $id;
    }
} 