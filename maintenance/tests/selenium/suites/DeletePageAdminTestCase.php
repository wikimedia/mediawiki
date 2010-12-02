<?php
/* 
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are dosumented as part of SimpleSeleniumTestSuite.php
 */
class DeletePageAdminTestCase extends SeleniumTestCase{
    
        // Verify adding a new page
        public function testDeletePage() {
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );   
                $this->click("link=Log out");
                $this->waitForPageToLoad( "30000" );
                $this->click( "link=Log in / create account" );
                $this->waitForPageToLoad( "30000" );
                $this->type( "wpName1", "nadeesha" );
                $this->type( "wpPassword1", "12345" );
                $this->click( "wpLoginAttempt" );
                $this->waitForPageToLoad( "30000" );
                $this->type( "searchInput", "new" );
                $this->click( "searchGoButton");
                $this->waitForPageToLoad( "30000" );
  
                // Verify  'Delete' link displayed
                $source = $this->gettext( "link=Delete" );
                $correct = strstr ( $source, "Delete" );
                try{
                    $this->assertEquals( $correct, true);
                    echo "\n Pass : 'Delete' link displayed \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : 'Delete' link displayed \n";
                }
                $this->click( "link=Delete" );
                $this->waitForPageToLoad( "30000" );

                // Verify 'Delete' button available
                $this->assertTrue($this->isElementPresent( "wpConfirmB" ));

                try{
                    $this->assertTrue($this->isElementPresent( "wpConfirmB" ));
                    echo "\n Pass : 'Delete' button available \n";
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Fail : 'Delete' button available \n";
                }
                $this->click( "wpConfirmB" );
                $this->waitForPageToLoad( "30000" );
                
                 // Verify  'Action complete' text displayed
                $source = $this->gettext( "firstHeading" );
                $correct = strstr ( $source, "Action complete" );
                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : 'Action complete' text displayed \n";
                }catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Fail : 'Action complete' text displayed \n";
                }

                // Verify  '<Page Name> has been deleted. See deletion log for a record of recent deletions.' text displayed
                $source = $this->gettext( "//div[@id='bodyContent']/p[1]" );
                $correct = strstr ( $source, "\"New\" has been deleted. See deletion log for a record of recent deletions." );
                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : Page deleted successfully \n";
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Fail : Page deleted successfully \n";
                }
	}  
}
