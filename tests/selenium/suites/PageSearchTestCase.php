<?php

/**
 * Selenium server manager
 *
 * @file
 * @ingroup Testing
 * Copyright (C) 2010 Nadeesha Weerasinghe <nadeesha@calcey.com>
 * http://www.calcey.com/ 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @addtogroup Testing
 *
 */

class PageSearchTestCase extends SeleniumTestCase {

    // Verify the functionality of the 'Go' button
    public function testPageSearchBtnGo() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->type( INPUT_SEARCH_BOX, "calcey qa" );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify  no page matched with the entered search text
        $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p/b" );
        $correct = strstr ( $source, "Create the page \"Calcey qa\" on this wiki!" );
        $this->assertEquals( $correct, true );

        $this->click( "link=Calcey qa" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        $this->type( TEXT_EDITOR , "Calcey QA team" );
        $this->click( "wpSave" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

    }

    // Verify the functionality of the 'Search' button
    public function testPageSearchBtnSearch() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->type( INPUT_SEARCH_BOX, "Calcey web" );
        $this->click( BUTTON_SEARCH );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify  no page is available as the search text
        $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p[2]/b" );
        $correct = strstr ( $source, "Create the page \"Calcey web\" on this wiki!" );
        $this->assertEquals( $correct, true );

        $this->click( "link=Calcey web" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        $this->type( TEXT_EDITOR, "Calcey web team" );
        $this->click( BUTTON_SAVE );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify saved page is opened  when the exact page name is given
        $this->type( INPUT_SEARCH_BOX, "Calcey web" );
        $this->click( BUTTON_SEARCH );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify exact page matched with the entered search text using 'Search' button
        $source = $this->getText( "//*[@id='bodyContent']/div[4]/p/b" );
        $correct = strstr( $source, "There is a page named \"Calcey web\" on this wiki." );
        $this->assertEquals( $correct, true );

        // Verify resutls available when partial page name is entered as the search text
        $this->type( INPUT_SEARCH_BOX, "Calcey" );
        $this->click( BUTTON_SEARCH );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        //  Verify text avaialble in the search result under the page titles
        if($this->isElementPresent( "Page_title_matches" )) {
            $textPageTitle = $this->getText( "//*[@id='bodyContent']/div[4]/ul[1]/li[1]/div[1]/a" );
            $this->assertContains( 'Calcey', $textPageTitle );
        }

        //  Verify text avaialble in the search result under the page text
        if($this->isElementPresent( "Page_text_matches" )) {
            $textPageText = $this->getText( "//*[@id='bodyContent']/div[4]/ul[2]/li[2]/div[2]/span" );
            $this->assertContains( 'Calcey', $textPageText );
        }
        $this->deletePage("Calcey QA");
        $this->deletePage("Calcey web");
    }
}
