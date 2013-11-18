<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 12.11.13
 * Time: 20:00
 */

namespace Ron\RaspberryPiBundle\Command;


use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\CssSelector\Node\ElementNode;
use Symfony\Component\DomCrawler\Crawler;

class EbayCommand extends Command
{
    protected $url = 'http://kleinanzeigen.ebay.de/anzeigen/s-suchanfrage.html?keywords=hochbett+paidi&categoryId=&locationStr=Berlin&locationId=3331&radius=50&sortingField=SORTING_DATE&adType=&posterType=&pageNum=1&action=find&maxPrice=&minPrice=';

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

        #echo $content;
        #$crawler = new Crawler();
        #$crawler->addHtmlContent($content);
        # echo $crawler->html();exit;
        foreach ($crawler->filterXPath('//table[@id="srchrslt-adtable"]/child::tr') as $rowNode) {
            /** @var $rowNode \DOMElement */

            $lineText = array();
            $crawler = new Crawler();
            $crawler->addNode($rowNode);
            #echo  $description = $crawler->filterXPath('//td[@class="c-td-adtble-time"]')->html();
            var_dump($crawler->filter('.c-td-adtble-dscr.h-td-first p'));
            if ($description = $crawler->filter('.c-td-adtble-dscr.h-td-first p')) {
                echo $description->text();
            }
            echo $pice = $crawler->filter('.price')->html();
            echo $location = str_replace('<br>', ' ', ($crawler->filter('h3.c-h-adtble-lctn')->html()));
            echo $title = $crawler->filter('a.ad-title')->html();
            #echo  $description = $crawler->filterXPath('//td[@class="c-td-adtble-dscr "]')->text();
            #  $crawler->first();
            /** @var $td \DOMElement */
            # foreach($rowNode->childNodes as $td){
            #  #      echo "huhu";
            #     $lineText[] = $td->textContent;
            # }
            #   var_dump($lineText);
            #      $output->writeln(implode(' | ', $lineText));

        }
        #$output->writeln($crawler->filterXPath('//table[id="srchrslt-adtable"]')->html());
    }
} 