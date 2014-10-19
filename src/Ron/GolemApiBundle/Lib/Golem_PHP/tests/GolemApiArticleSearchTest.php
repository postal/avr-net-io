<?php
/**
 * Copyright (c) 2009, Kla�&Ihlenfeld Verlag GmbH
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *    * Redistributions of source code must retain the above copyright notice,
 *      this list of conditions and the following disclaimer.
 *    * Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *    * Neither the name of the Kla�&Ihlenfeld Verlag GmbH nor the names of its
 *      contributors may be used to endorse or promote products derived from
 *      this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * $Id: GolemApiArticleSearchTest.php 1852 2009-04-28 16:15:14Z am $
 *
 * PHP version 5
 *
 * @category  WebInterfaceTest
 * @package   Golem.de_Test
 * @author    Alexander Merz <am@golem.de>
 * @copyright 2009 Kla�&Ihlenfeld Verlag GmbH
 * @license   http://api.golem.de/LICENCE.txt BSD Licence
 * @link      http://api.golem.de/
 */

require_once 'PHPUnit/Framework.php';

require_once '../Golem/Request.php';
require_once '../Golem/Api/Article/Search.php';

class GolemApiArticleSearchTest extends PHPUnit_Framework_TestCase
{

    const API_KEY = 'YOUR_DEV_KEY'; // insert your developer key here

    protected $request = null;
    
    protected $itemsPerPage = 10;

    protected $startIndex = 1;
    
    protected $query = 'test';
    
    public function setUp() {
      
        $this->request = new Golem_Api_Article_Search(self::API_KEY);
        
        $this->request->setQuery($this->query);
        $this->request->setItemsPerPage($this->itemsPerPage);
        $this->request->setStartIndex($this->startIndex);
        
        $this->request->fetch();

    }
    
    public function testArticleCount() {
      
        $articles = $this->request->getArticles();

        $this->assertEquals($this->itemsPerPage, count($articles));

        $this->assertEquals($this->itemsPerPage, $this->request->getTotalResults());

    }

    public function testSettings() {

        $this->assertEquals($this->itemsPerPage, $this->request->getItemsPerPage());

        $this->assertEquals($this->startIndex, $this->request->getStartIndex());


    }
    
    public function testArticleData()
    {

        $articles = $this->request->getArticles();

        foreach($articles as $article) {

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT, 
                              $article['articleid']);
            $this->assertEquals(1, $article['articleid']);
            
            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING, 
                              $article['headline']);
            $this->assertEquals('Lorem ipsum dolor sit amet consectetuer '.
                                'sadipscing elitr sed',
                                $article['headline']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $article['abstracttext']);
            $this->assertEquals('Lorem ipsum dolor sit amet, consectetuer '.
                                'sadipscing elitr, sed diam nonumy eirmod '.
                                'tempor invidunt ut labore et dolore magna'.
                                ' aliquyam erat, sed diam voluptua. At ver'.
                                'o eos et accusam et justo duo dolores et '.
                                'ea rebum. Stet clita kasd gubergren, no s'.
                                'ea takimata sanctus est Lorem ipsum dolor'.
                                ' sit amet.',
                                $article['abstracttext']);

            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_INT,
                              $article['date']);
            $this->assertEquals(1234567890, $article['date']);


            $this->assertType(PHPUnit_Framework_Constraint_isType::TYPE_STRING,
                              $article['url']);
            $this->assertEquals('http://api.golem.de/', $article['url']);
            


        }

    }

}
?>