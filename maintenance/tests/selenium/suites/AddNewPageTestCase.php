<?php
/* 
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are dosumented as part of SimpleSeleniumTestSuite.php
 */
class AddNewPageTestCase extends SeleniumTestCase{
    
        // Verify adding a new page
        public function testAddNewPage() {
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );   
                $this->type("searchInput", "new");  
                $this->click("searchGoButton");  
                $this->waitForPageToLoad("600000");

                // Verify 'Search results' text available
                $source = $this->gettext( "firstHeading" );
                $correct = strstr( $source, "Search results");
                try{
                    $this->assertEquals( $correct, true);
                    echo "\n Pass : Text'Search results' displayed \n";
                    
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : Text' Search results' displayed  \n" ;
                }

                // Verify  'Create the page "<page name>" on this wiki' text available
                $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p/b" );
                $correct = strstr ( $source, "Create the page \"New\" on this wiki!" );
                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : Text 'Create the page \"New\" on this wiki!' displayed \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : Text 'Create the page \"New\" on this wiki!' displayed \n";
                }
 
               $this->click("link=New");
               $this->waitForPageToLoad("600000");

               // Verify 'Create' tab available
               try{
                   $this->assertTrue($this->isElementPresent("link=Create"));
                   echo "\n Pass : 'Create' tab displayed \n";
               } catch (PHPUnit_Framework_AssertionFailedError $e) {
                   echo "\n Fail : 'Create' tab displayed \n";
               }

               $this->type("wpTextbox1", "add new test page");
               $this->click("wpSave");

                // Verify new page added
                $source = $this->gettext( "firstHeading" );
                $correct = strstr ( $source, "New" );
                try{
                    $this->assertEquals( $correct, true);
                    echo "\n Pass : New page added \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : New page added \n";
                }
	} 
}
