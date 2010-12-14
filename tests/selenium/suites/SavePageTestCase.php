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

class SavePageTestCase extends SeleniumTestCase {

    // Verify adding a new page
    public function testSavePage() {
        $wikiText = "Adding this page to test the Save button functionality";
        $newPage = "Test Save Page";

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->getNewPage($newPage);
        $this->type("wpTextbox1", $wikiText);

        // verify 'Save' button available
        $this->assertTrue($this->isElementPresent( "wpSave" ));
        $this->click( "wpSave" );

        // Verify saved page available
        $source = $this->gettext( "firstHeading" );
        $correct = strstr( $source, "Test Save Page" );

        // Verify Saved page name displayed correctly
        $this->assertEquals( $correct, true );

        // Verify page content saved succesfully
        $contentOfSavedPage = $this->getText( "//*[@id='content']" );
        $this->assertContains( $wikiText, $contentOfSavedPage  );
        $this->deletePage( $newPage );
    }
}
