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


class AddNewPageTestCase extends SeleniumTestCase {

    // Verify adding a new page
    public function testAddNewPage() {
        $newPage = "new";
        $displayName = "New";
        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "600000" );

        // Verify 'Search results' text available
        $source = $this->gettext( "firstHeading" );
        $correct = strstr( $source, "Search results" );
        $this->assertEquals( $correct, true);

        // Verify  'Create the page "<page name>" on this wiki' text available
        $source = $this->gettext( "//div[@id='bodyContent']/div[4]/p/b" );
        $correct = strstr ( $source, "Create the page \"New\" on this wiki!" );
        $this->assertEquals( $correct, true );

        $this->click( "link=".$displayName );
        $this->waitForPageToLoad( "600000" );

        $this->assertTrue($this->isElementPresent( "link=Create" ));
        $this->type( "wpTextbox1", "add new test page" );
        $this->click( "wpSave" );

        // Verify new page added
        $source = $this->gettext( "firstHeading" );
        $correct = strstr ( $source, $displayName );
        $this->assertEquals( $correct, true );
    }
}
