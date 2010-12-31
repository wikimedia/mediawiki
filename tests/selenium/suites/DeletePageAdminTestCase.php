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


class DeletePageAdminTestCase extends SeleniumTestCase {

    // Verify adding a new page
    public function testDeletePage() {

       
        $newPage = "new";
        $displayName = "New";

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->click( LINK_START.$displayName );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->type( TEXT_EDITOR, $newPage." text" );
        $this->click( BUTTON_SAVE );

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( LINK_START."Log out" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->click( LINK_START."Log in / create account" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        $this->type( "wpName1", $this->selenium->getUser() );
        $this->type( "wpPassword1", $this->selenium->getPass() );
        $this->click( "wpLoginAttempt" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->type( "searchInput", "new" );
        $this->click( "searchGoButton");
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify  'Delete' link displayed
        $source = $this->gettext( LINK_START."Delete" );
        $correct = strstr ( $source, "Delete" );
        $this->assertEquals($correct, true );

        $this->click( LINK_START."Delete" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify 'Delete' button available
        $this->assertTrue($this->isElementPresent( "wpConfirmB" ));

        $this->click( "wpConfirmB" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify  'Action complete' text displayed
        $source = $this->gettext( "firstHeading" );
        $correct = strstr ( $source, "Action complete" );
        $this->assertEquals( $correct, true );

        // Verify  '<Page Name> has been deleted. See deletion log for a record of recent deletions.' text displayed
        $source = $this->gettext( "//div[@id='bodyContent']/p[1]" );
        $correct = strstr ( $source, "\"New\" has been deleted. See deletion log for a record of recent deletions." );
        $this->assertEquals( $correct, true );
    }
}
