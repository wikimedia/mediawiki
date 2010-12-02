<?php


class MediaWikiCommonFunc{

        // Getting existing page
        public function getExistingPage(){
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type("searchInput", "new" );
                $this->click("searchGoButton");
                $this->waitForPageToLoad("30000");
        }

        // Creating new page
        public function getNewPage($pageName){

                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
                $this->type("searchInput", $pageName );
                $this->click("searchGoButton");
                $this->waitForPageToLoad("30000");
                $this->click("link=".$pageName);
                $this->waitForPageToLoad("600000");
        }

        // Deleting the given page
        public function deletePage($pageName){
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
                $this->type( "searchInput", $pageName );
                $this->click( "searchGoButton");
                $this->waitForPageToLoad( "30000" );
                $this->click( "link=Delete" );
                $this->waitForPageToLoad( "30000" );
                $this->click( "wpConfirmB" );
                $this->waitForPageToLoad( "30000" );
        }

       // Loading the mediawiki editor
        public function loadWikiEditor(){
                $this->open( $this->getUrl() .
			'/index.php?title=Main_Page&action=edit' );
        }

        // Clear the content of the mediawiki editor
        public function clearWikiEditor(){
                $this->type("wpTextbox1", "");
        }

        // Click on the 'Show preview' button of the mediawiki editor
        public function clickShowPreviewBtn(){
                $this->click("wpPreview");
        }

        // Click on the 'Save Page' button of the mediawiki editor
        public function clickSavePageBtn(){
                $this->click("wpSave");
        }

        // Click on the 'Edit' link
        public function clickEditLink(){
                $this->click("link=Edit");
                $this->waitForPageToLoad("30000");
        }


        
}
