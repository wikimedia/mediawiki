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


class MyWatchListTestCase extends SeleniumTestCase {

    // Verify user watchlist
    public function testMyWatchlist() {

        $pageName = $this->createNewTestPage( "MyWatchListTest", true );
        // Verify link 'My Watchlist' available
        $this->assertTrue( $this->isElementPresent( "link=Watchlist" ) );

        $this->click( "link=Watchlist" );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );

        // Verify newly added page to the watchlist is available
        $this->assertEquals( $pageName, $this->getText( "link=".$pageName ));

        $this->click( "link=".$pageName );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->click( LINK_EDIT );
        $this->waitForPageToLoad( WIKI_TEST_WAIT_TIME );
        $this->click( "wpWatchthis" );
        $this->click( BUTTON_SAVE );
        $this->assertFalse( $this->isElementPresent( "link=".$pageName ) );
        //todo watch using the dropdown menu
    }
}

