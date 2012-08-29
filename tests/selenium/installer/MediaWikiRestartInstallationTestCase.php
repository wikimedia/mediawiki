<?php

/**
 * MediaWikiRestartInstallationTestCase
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



require_once (__DIR__.'/'.'MediaWikiInstallationCommonFunction.php');

/**
 * Test Case ID   : 11, 12 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install mediawiki on a already installed Mediawiki.
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiRestartInstallationTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Verify restarting the installation
    public function testSuccessRestartInstallation() {

        $dbNameBeforeRestart  = DB_NAME_PREFIX."_db_before";
        parent::navigateDatabaseSettingsPage( $dbNameBeforeRestart );

        // Verify 'Restart installation' link available
        $this->assertTrue($this->isElementPresent( "link=Restart installation" ));

        // Click 'Restart installation'
        $this->click( "link=Restart installation ");
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Restart Installation' page displayed
        $this->assertEquals( "Restart installation", $this->getText( LINK_DIV."h2"));

        // Restart warning message displayed
        $this->assertTrue($this->isTextPresent( "exact:Do you want to clear all saved data that you have entered and restart the installation process?" ));

        // Click on the 'Yes, restart' button
        $this->click( "submit-restart" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Navigate to the initial installation page(Language).
        $this->assertEquals(  "Language", $this->getText( LINK_DIV."h2" ));

        // 'Welcome to MediaWiki!' page
        parent::clickContinueButton();

        // 'Connect to database' page
        parent::clickContinueButton();

        // saved data should be deleted
        $dbNameAfterRestart = $this->getValue("mysql_wgDBname");
        $this->assertNotEquals($dbNameBeforeRestart, $dbNameAfterRestart);
    }


    // Verify cancelling restart
    public function testCancelRestartInstallation() {

        $dbNameBeforeRestart  = DB_NAME_PREFIX."_cancel_restart";

        parent::navigateDatabaseSettingsPage( $dbNameBeforeRestart);
        // Verify 'Restart installation' link available
        $this->assertTrue($this->isElementPresent( "link=Restart installation" ));

        $this->click( "link=Restart installation" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Restart Installation' page displayed
        $this->assertEquals( "Restart installation", $this->getText( LINK_DIV."h2" ));

        // Restart warning message displayed
        $this->assertTrue( $this->isTextPresent( "Do you want to clear all saved data that you have entered and restart the installation process?"));

        // Click on the 'Back' button
        parent::clickBackButton();

        // Navigates to the previous page
        $this->assertEquals( "Database settings", $this->getText( LINK_DIV."h2" ));

        // 'Connect to database' page
        parent::clickBackButton();

        // Saved data remain on the page.
        $dbNameAfterRestart = $this->getValue( "mysql_wgDBname" );
        $this->assertEquals( $dbNameBeforeRestart, $dbNameAfterRestart );
    }
}
