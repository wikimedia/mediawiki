<?php
/* 
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are dosumented as part of SimpleSeleniumTestSuite.php
 */
class PageSearchTestCase extends SeleniumTestCase{

             // Verify the functionality of the 'Go' button
             public function testPageSearchBtnGo(){

                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type( "searchInput", "calcey qa" );
                $this->click( "searchGoButton" );
                $this->waitForPageToLoad( "600000" );

                // Verify  no page matched with the entered search text
                $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p/b" );
                $correct = strstr ( $source, "Create the page \"Calcey qa\" on this wiki!" );

                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : No page matched with the entered search text using the 'Go' button \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Pass : No page matched with the entered search text using the 'Go' button \n";
                }

               $this->click( "link=Calcey qa" );
               $this->waitForPageToLoad( "600000" );

               $this->type( "wpTextbox1", "Calcey QA team" );
               $this->click( "wpSave" );
               $this->waitForPageToLoad( "600000" );

            }

            // Verify the functionality of the 'Search' button
            public function testPageSearchBtnSearch(){

                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type( "searchInput", "Calcey web" );
                $this->click( "mw-searchButton" );
                $this->waitForPageToLoad( "30000" );

                 // Verify  no page is available as the search text
                $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p[2]/b" );
                $correct = strstr ( $source, "Create the page \"Calcey web\" on this wiki!" );

                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : No page matched with the entered search text using the 'Search' button \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Pass : No page matched with the entered search text using the 'Search' button \n";
                }

               $this->click( "link=Calcey web" );
               $this->waitForPageToLoad( "600000" );

               $this->type( "wpTextbox1", "Calcey web team" );
               $this->click( "wpSave" );
               $this->waitForPageToLoad( "600000" );


               // Verify saved page is opened  when the exact page name is given
               $this->type( "searchInput", "Calcey web" );
               $this->click( "mw-searchButton" );
               $this->waitForPageToLoad( "30000" );

               $source = $this->getText( "//*[@id='bodyContent']/div[4]/p/b" );
               $correct = strstr( $source, "There is a page named \"Calcey web\" on this wiki." );

               try{
                   $this->assertEquals( $correct, true );
                   echo "\n Pass : Exact page matched with the entered search text using 'Search' button \n";
               } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                   echo "\n Fail : Exact page matched with the entered search text using 'Search' button \n";
               }


               // Verify resutls available when partial page name is entered as the search text

               $this->type( "searchInput", "Calcey" );
               $this->click( "mw-searchButton" );
               $this->waitForPageToLoad( "30000" );

               //  Verify text avaialble in the search result under the page titles
               if($this->isElementPresent( "Page_title_matches" )){
                   try{
                       $textPageTitle = $this->getText( "//*[@id='bodyContent']/div[4]/ul[1]/li[1]/div[1]/a" );
                       $this->assertContains( 'Calcey', $textPageTitle );
                       echo "\n Pass : Results displayed under 'Page title matches' heading for the partial title match match using the 'Search' button\n";
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                       echo "\n Fail : Results displayed under 'Page title matches' heading for the partial title match match using the 'Search' button \n";
                }
               }
               else{
                    echo "\n Pass : No results avaialble for the 'Page title matches' heading for the partial title match using the 'Search' button \n";
               }

                //  Verify text avaialble in the search result under the page text
                if($this->isElementPresent( "Page_text_matches" )){
                    try{
                        $textPageText = $this->getText( "//*[@id='bodyContent']/div[4]/ul[2]/li[2]/div[2]/span" );
                        $this->assertContains( 'Calcey', $textPageText );
                        
                        echo "\n Pass : Results displayed under 'Page text matches' heading for the partial text match using the 'Search' button \n";

                    } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                        echo "\n Fail : Results displayed under 'Page text matches' heading for the partial text match using the 'Search' button \n";
                    }
                }
                else{
                    echo "\n Pass : No results avaialble for the 'Page text matches' heading for the partial title match using the 'Search' button\n";
                }
               $this->deletePage("Calcey QA");
               $this->deletePage("Calcey web");
               
         }


        
}
