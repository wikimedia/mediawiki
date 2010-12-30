<?php

/**
 * MediaWikiRightFrameworkLinksTestCase
 *
 * @file
 * @ingroup Maintenance
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
 * @addtogroup Maintenance
 *
 */


require_once (dirname(__FILE__).'/'.'MediaWikiInstallationCommonFunction.php');

/*
 * Test Case ID   : 14, 15, 16, 17 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : User selects 'Read me' link.
 *                  User selects 'Release notes' link.
 *                  User selects 'Copying' link.
 *                  User selects 'Upgrading' link.
 * Version        : MediaWiki 1.18alpha
*/


class MediaWikiRightFrameworkLinksTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    public function testLinksAvailability() {

        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // Verify 'Read me' link availability
        $this->assertTrue($this->isElementPresent( "link=Read me" ));

        // Verify 'Release notes' link availability
        $this->assertTrue($this->isElementPresent( "link=Release notes" ));

        //  Verify 'Copying' link availability
        $this->assertTrue($this->isElementPresent( "link=Copying" ));
    }

    public function testPageNavigation() {

        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // Navigate to the 'Read me' page
        $this->click( "link=Read me" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->assertEquals( "Read me", $this->getText( LINK_DIV."h2[1]" ));
        $this->assertTrue($this->isElementPresent( "submit-back" ));
        parent::clickBackButton();

        // Navigate to the 'Release notes' page
        $this->click( "link=Release notes" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME);
        $this->assertEquals( "Release notes", $this->getText( LINK_DIV."h2[1]" ));
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        parent::clickBackButton();

        // Navigate to the 'Copying' page
        $this->click( "link=Copying" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->assertEquals( "Copying", $this->getText( LINK_DIV."h2[1]" ));
        $this->assertTrue($this->isElementPresent( "submit-back" ));
        parent::clickBackButton();

        // Navigate to the 'Upgrading' page
        $this->click( "link=Upgrading" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->assertEquals( "Upgrading", $this->getText( LINK_DIV."h2[1]" ));
    }
}
