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

        $newPage = "mypage999";
        $displayName = "Mypage999";

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );

        $this->type( "searchInput", $newPage);
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "60000" );
        $this->click( "link=".$displayName );
        $this->waitForPageToLoad( "600000" );
        $this->type( "wpTextbox1", $newPage." text" );
        $this->click( "wpSave" );
        $this->waitForPageToLoad( "60000" );

        // Verify My contributions Link available
        $this->assertTrue($this->isElementPresent( "link=My contributions" ));

        $this->click( "link=My contributions" );
        $this->waitForPageToLoad( "30000" );

        // Verify recent page adding available on My Contributions list
        $this->assertEquals( $displayName, $this->getText( "link=".$displayName ));

        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );

        $this->click( "link=Edit" );
        $this->waitForPageToLoad( "30000" );
        $this->type( "wpTextbox1", $newPage." text changed" );
        $this->click( "wpSave" );
        $this->waitForPageToLoad( "30000" );
        $this->click( "link=My contributions" );
        $this->waitForPageToLoad( "30000" );

        // Verify recent page changes available on My Contributions
        $this->assertTrue($this->isTextPresent($displayName." ‎ (top)"));
        $this->deletePage($newPage);
    }
}

