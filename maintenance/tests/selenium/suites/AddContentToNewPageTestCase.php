<?php
/* 
 * This test case is part of the SimpleSeleniumTestSuite.
 * Configuration for these tests are dosumented as part of SimpleSeleniumTestSuite.php
 */
class AddContentToNewPageTestCase extends SeleniumTestCase{

        // Add bold text and verify output
        public function testAddBoldText(){
                $blnElementPresent = False;
                $blnTextPresent = False;
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "//*[@id='mw-editbutton-bold']" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );
                
                try{
                    $this->assertTrue($this->isElementPresent("//div[@id='wikiPreview']/p/b"));
                    $blnElementPresent = True;
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    $blnElementPresent = False;
                }

                try{
                    $this->assertTrue($this->isTextPresent( "Bold text" ));
                    $blnTextPresent = True;

                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    $blnTextPresent = False;
                }

                 // Verify bold text displayed on mediawiki preview
                if (( $blnElementPresent = True ) && ( $blnTextPresent = True )){
                    echo "\n Pass : Bold text displayed in the preview \n";
                }
                else{
                     echo "\n Fail : Bold text displayed in the preview \n";
                }
        }
        
        // Add italic text and verify output
        public function testAddItalicText(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "//*[@id='mw-editbutton-italic']" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify italic text displayed on mediawiki preview
                try{
                    $this->assertTrue($this->isElementPresent("//div[@id='wikiPreview']/p/i"));
                    $blnElementPresent = True;
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    $blnElementPresent = False;
                }

                try{
                    $this->assertTrue($this->isTextPresent( "Italic text" ));
                    $blnTextPresent = True;

                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    $blnTextPresent = False;
                }

                 // Verify italic text displayed on mediawiki preview
                if (( $blnElementPresent = True ) && ( $blnTextPresent = True )){
                    echo "\n Pass : Italic text displayed in the preview \n";
                }
                else{
                     echo "\n Fail : Italic text displayed in the preview \n";
                }

        }

        // Add internal link for a new page and verify output in the preview
        public function testAddInternalLinkNewPage(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "//*[@id='mw-editbutton-link']" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify internal link displayed on mediawiki preview
                $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
                $correct = strstr( $source, "Link title" );
                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : Internal link displayed in the preview \n";

                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Pass : Internal link displayed in the preview \n";
                }

                $this->click( "link=Link title" );
                $this->waitForPageToLoad( "600000" );

                // Verify internal link open as a new page - editing mode
                $source = $this->getText( "firstHeading" );
                $correct = strstr( $source, "Editing Link title" );
                try{
                    $this->assertEquals( $correct, true );
                     echo "\n Pass : Internal link opened as a new page in the editing mode \n";

                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Fail : Internal link opened as a new page in the editing mode \n";
                }
          }

        // Add external link and verify output in the preview
        public function testAddExternalLink(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "//*[@id='mw-editbutton-extlink']" );
                $this->type( "wpTextbox1", "[http://www.google.com Google]" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify external links displayed on mediawiki preview
                $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
                $correct = strstr( $source, "Google" );

                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : External link displayed in the preview \n";

                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    echo "\n Fail : External link displayed in thw preview \n";
                }

                $this->click( "link=Google" );
                $this->waitForPageToLoad( "600000" );

                // Verify external link opens
                $source = $this->getTitle();
                $correct = strstr( $source, "Google" );
                try{
                    $this->assertEquals( $correct, true);
                    echo "\n Pass : External link opened \n";

                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : External link opened \n";
                }

        }

        // Add level 2 headline and verify output in the preview
        public function testAddLevel2HeadLine(){
                $blnElementPresent = False;
                $blnTextPresent = False;
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "mw-editbutton-headline" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                try {
                    $this->assertTrue($this->isElementPresent("//div[@id='wikiPreview']/h2"));
                    $blnElementPresent = True;
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                     $blnElementPresent = False;
                }

                try{
                    $source = $this->getText( "//*[@id='Headline_text']" );
                    $correct = strstr( $source, "Headline text" );
                    $this->assertEquals( $correct, true );
                    $blnTextPresent = True;
                } catch ( PHPUnit_Framework_AssertionFailedError $e ){
                    $blnTextPresent = False;
                }

                if(($blnElementPresent = True) && ($blnTextPresent = True)){
                     echo "\n Pass : Headline text displayed in the preview \n";
                }
                else{
                    echo "\n Fail : Headline text displayed in the preview \n";
                }
         }

        // Add text with ignore wiki format and verify output the preview
        public function testAddNoWikiFormat(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "//*[@id='mw-editbutton-nowiki']" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify ignore wiki format text displayed on mediawiki preview
                $source = $this->getText( "//div[@id='wikiPreview']/p" );
                $correct = strstr( $source, "Insert non-formatted text here" );
                try{
                    $this->assertEquals( $correct, true );
                    echo "\n Pass : Text available without wiki formats in the preview \n ";

                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : Text available without wiki formats in the preview\n ";
                }               
        }

        // Add signature and verify output in the preview
        public function testAddUserSignature(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "mw-editbutton-signature" );
                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify signature displayed on mediawiki preview
                $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
                $username = $this->getText( "//*[@id='pt-userpage']/a" );
                $correct = strstr( $source, $username );

                try{
                    $this->assertEquals( $correct, true);
                    echo "\n Pass : Correct name displayed in the preview \n ";

                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : Correct name displayed in the preview \n ";
                }
        }

        // Add horizontal line and verify output in the preview
        public function testHorizontalLine(){
                $this->getExistingPage();
                $this->clickEditLink();
                $this->loadWikiEditor();
                $this->clearWikiEditor();
                $this->click( "mw-editbutton-hr" );

                $this->clickShowPreviewBtn();
                $this->waitForPageToLoad( "600000" );

                // Verify horizontal line displayed on mediawiki preview
                try{
                     $this->assertTrue( $this->isElementPresent( "//div[@id='wikiPreview']/hr" ));
                     echo "\n Pass: Horizontal line displayed in the preview \n";
                } catch (PHPUnit_Framework_AssertionFailedError $e){
                    echo "\n Fail : Horizontal line displayed in the preview \n ";
                }
        }

 
 }
