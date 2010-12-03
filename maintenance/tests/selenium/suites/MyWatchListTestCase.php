<?php

/**
 * Selenium server manager
 *
 * @file
 * @ingroup Maintenance
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
 * @addtogroup Maintenance
 *
 */


class MyWatchListTestCase extends SeleniumTestCase {

    // Verify user watchlist
    public function testMyWatchlist() {

        $newPage = "mypage";
        $displayName = "Mypage";
        $wikiText = "watch page";

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );
        $this->click( "link=".$displayName );
        $this->waitForPageToLoad( "600000" );

        $this->click( "wpWatchthis" );
        $this->type( "wpTextbox1",$wikiText );
        $this->click( "wpSave" );
        $this->waitForPageToLoad( "30000" );

        // Verify link 'My Watchlist' available
        $this->assertTrue( $this->isElementPresent( "link=My watchlist" ) );

        $this->click( "link=My watchlist" );
        $this->waitForPageToLoad( "30000" );

        // Verify newly added page to the watchlist is available
        $watchList = $this->getText( "//*[@id='bodyContent']" );
        $this->assertContains( $displayName, $watchList );

        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );
        $this->click("link=Edit");
        $this->waitForPageToLoad( "30000" );
        $this->click( "wpWatchthis" );
        $this->click( "wpSave" );
        $this->deletePage( $newPage );
    }
}
?>
