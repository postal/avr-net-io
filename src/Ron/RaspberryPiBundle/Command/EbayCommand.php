<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 12.11.13
 * Time: 20:00
 */

namespace Ron\RaspberryPiBundle\Command;


use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\DomCrawler\Crawler;

class EbayCommand extends ContainerAwareCommand
{
    protected $url = 'http://kleinanzeigen.ebay.de/anzeigen/s-suchanfrage.html?keywords=hochbett+paidi&categoryId=&locationStr=Berlin&locationId=3331&radius=50&sortingField=SORTING_DATE&adType=&posterType=&pageNum=1&action=find&maxPrice=&minPrice=';

    public $filename = '/home/ron/last_search_result.txt';
    protected $mail = 'siedler1@freenet.de';

    protected function configure()
    {
        $this
            ->setName('ebay:search')
            ->setDescription('Ebay Kleinanzeigen');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        #$memcache = new \Memcache();
        #$memcache->addServer('localhost', 11211);

        #if(false === $memcache->get('content')) {
        $client = new Client();
        $crawler = $client->request('GET', $this->url);
        #   $content = $crawler->html();
        #  $memcache->set('content', serialize($content));
        #}else {
        #    $content = unserialize($memcache->get('content'));
        #}

        $i = 1;
        $content = "";
        foreach ($crawler->filterXPath('//table[@id="srchrslt-adtable"]/child::tr') as $rowNode) {
            /** @var $rowNode \DOMElement */

            $lineText = array();
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
            $location = str_replace('<br>', ' ', ($crawler->filter('h3.c-h-adtble-lctn')->html()));
            $title = $crawler->filter('a.ad-title')->text();
            $link = $crawler->filter('.c-td-adtble-dscr a.ad-title')->attr('href');
            $date = $crawler->filter('td.c-td-adtble-time')->text();
            $content .= $i . '. ' . $title . '|' . $description . '|' . $price . '|' . $location;

            $emailContent[] = array(
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'location' => $location,
                'link' => $link,
                'date' => $date,
            );

            $output->writeln($i . '. ' . $title . '|' . $description . '|' . $price . '|' . $location);
            $i++;
        }
        $lastContent = file_get_contents($this->filename);


        if ($lastContent != base64_encode($content)) {
            $output->writeln('Angebote haben sich geändert.');
            file_put_contents($this->filename, base64_encode($content));
            $message = $this->getMessage($emailContent);
            $mailer = $this->getContainer()->get('mailer');
            $mailer->send($message);

            $output->writeln('Eine E-Mail wurde versandt.');
            $spool = $mailer->getTransport()->getSpool();
            $transport = $this->getContainer()->get('swiftmailer.transport.real');

            $spool->flushQueue($transport);
        } else {
            $output->writeln('Die Angebote haben sich nicht geändert.');
        }
    }

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

    protected function getBody($data)
    {
        $templating = $this->getContainer()->get('templating');
        $body = $templating->render('RonRaspberryPiBundle:Ebay:ebay_mail.html.twig', array('data' => $data));
        return $body;
    }
} 