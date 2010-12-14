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

class MovePageTestCase extends SeleniumTestCase {

    // Verify move(rename) wiki page
    public function testMovePage() {

        $newPage = "mypage99";
        $displayName = "Mypage99";

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );
        $this->click( "link=".$displayName );
        $this->waitForPageToLoad( "60000" );
        $this->type( "wpTextbox1", $newPage." text" );
        $this->click( "wpSave" );
        $this->waitForPageToLoad( "60000" );

        // Verify link 'Move' available
        $this->assertTrue($this->isElementPresent( "link=Move" ));

        $this->click( "link=Move" );
        $this->waitForPageToLoad( "30000" );

        // Verify correct page name displayed under 'Move Page' field
        $this->assertEquals($displayName,
                $this->getText("//table[@id='mw-movepage-table']/tbody/tr[1]/td[2]/strong/a"));
        $movePageName = $this->getText( "//table[@id='mw-movepage-table']/tbody/tr[1]/td[2]/strong/a" );

        // Verify 'To new title' field has current page name as the default name
        $newTitle =  $this->getValue( "wpNewTitle" );
        $correct = strstr( $movePageName , $newTitle  );
        $this->assertEquals( $correct, true );

        $this->type( "wpNewTitle", $displayName );
        $this->click( "wpMove" );
        $this->waitForPageToLoad( "30000" );

        // Verify warning message for the same source and destination titles
        $this->assertEquals( "Source and destination titles are the same; cannot move a page over itself.",
                $this->getText("//div[@id='bodyContent']/p[4]/strong" ));

        // Verify warning message for the blank title
        $this->type( "wpNewTitle", "" );
        $this->click( "wpMove" );
        $this->waitForPageToLoad( "30000" );

        // Verify warning message for the blank title
        $this->assertEquals( "The requested page title was invalid, empty, or an incorrectly linked inter-language or inter-wiki title. It may contain one or more characters which cannot be used in titles.",
                $this->getText( "//div[@id='bodyContent']/p[4]/strong" ));

        //  Verify warning messages for the invalid titles
        $this->type( "wpNewTitle", "# < > [ ] | { }" );
        $this->click( "wpMove" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "The requested page title was invalid, empty, or an incorrectly linked inter-language or inter-wiki title. It may contain one or more characters which cannot be used in titles.",
                $this->getText( "//div[@id='bodyContent']/p[4]/strong" ));

        $this->type( "wpNewTitle", $displayName."move" );
        $this->click( "wpMove" );
        $this->waitForPageToLoad( "30000" );

        // Verify move success message displayed correctly
        $this->assertEquals( "\"".$displayName."\" has been moved to \"".$displayName."move"."\"",
                $this->getText( "//div[@id='bodyContent']/p[1]/b" ));

        $this->type( "searchInput", $newPage."move" );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );

        // Verify search using new page name
        $this->assertEquals( $displayName."move", $this->getText( "firstHeading" ));

        $this->type( "searchInput", $newPage );
        $this->click( "searchGoButton" );
        $this->waitForPageToLoad( "30000" );

        // Verify search using old page name
        $redirectPageName = $this->getText( "//*[@id='contentSub']" );
        $this->assertEquals( "(Redirected from ".$displayName.")" , $redirectPageName );

        // newpage delete
        $this->deletePage( $newPage."move" );
        $this->deletePage( $newPage );
    }
}

