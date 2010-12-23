<?php

/**
 * Selenium server manager
 *
 * @file
 * @ingroup Testing
 * Copyright (C) 2010 Dan Nessett <dnessett@yahoo.com>
 * http://citizendium.org/
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

class MyContributionsTestCase extends SeleniumTestCase {

    // Verify user contributions
    public function testRecentChangesAvailability() {

        $newPage = $this->createNewTestPage( "MyContributionsTest" );
        
        // Verify My contributions Link available
        $this->assertTrue($this->isElementPresent( "link=Contributions" ));

        
        $this->click( "link=Contributions" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify recent page adding available on My Contributions list
        $this->assertEquals( $newPage, $this->getText( "link=".$newPage ));

        $this->type( INPUT_SEARCH_BOX, $newPage );
        $this->click( BUTTON_SEARCH );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        
        $this->click( LINK_EDIT );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->type( TEXT_EDITOR, $newPage . " text changed" );
        $this->click( BUTTON_SAVE );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->click( "link=Contributions" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify recent page changes available on My Contributions
        $this->assertTrue( $this->isTextPresent( $newPage ) );
    }
}

