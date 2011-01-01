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

class PreviewPageTestCase extends SeleniumTestCase {

    // Verify adding a new page
    public function testPreviewPage() {
        $wikiText = "Adding this page to test the \n Preview button functionality";
        $newPage =  "Test Preview Page";
        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->getNewPage( $newPage );
        $this->type( SeleniumTestConstants::TEXT_EDITOR, $wikiText."" );
        $this->assertTrue($this->isElementPresent( "//*[@id='wpPreview']" ));

        $this->click( "wpPreview" );

        // Verify saved page available
        $source = $this->gettext( "firstHeading" );
        $correct = strstr( $source, "Test Preview Page" );
        $this->assertEquals( $correct, true);

        // Verify page content previewed succesfully
        $contentOfPreviewPage = $this->getText( "//*[@id='content']" );
        $this->assertContains( $wikiText, $contentOfPreviewPage  );
    }
}
