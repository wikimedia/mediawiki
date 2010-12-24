<?php

/**
 * MediaWikiButtonsAvailabilityTestCase
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


require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiInstallationCommonFunction.php');

/*
 * Test Case ID   : 30 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name :'Back' and 'Continue' button availability
 * Version        : MediaWiki 1.18alpha
*/


class MediaWikiButtonsAvailabilityTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }


    // Verify only 'Continue' button available on 'Language' page
    public function testOnlyContinueButtonAvailability() {

        parent::navigateLanguagePage();

        // Verify only 'Continue' button avaialble
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));

        // 'Back' button is not avaialble
        $this->assertElementNotPresent( "submit-back" );
    }


    // Verify 'Continue' and 'Back' buttons availability
    public function testBothButtonsAvailability() {

        // Verify buttons availability on 'Welcome to MediaWiki' page
        parent::navigateWelcometoMediaWikiPage();
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
        parent::restartInstallation();

        // Verify buttons availability on 'Connect to Database' page
        parent::navigateConnetToDatabasePage();
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
        parent::restartInstallation();

        // Verify buttons availability on 'Database settings' page
        $databaseName = DB_NAME_PREFIX."_db_settings";
        parent::navigateDatabaseSettingsPage( $databaseName );
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
        parent::restartInstallation();

        // Verify buttons availability on 'Name' page
        $databaseName = DB_NAME_PREFIX."_name";
        parent::navigateNamePage( $databaseName );
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
        parent::restartInstallation();

        // Verify buttons availability on 'Options' page
        $databaseName = DB_NAME_PREFIX."_options";
        parent::navigateOptionsPage( $databaseName );
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
        parent::restartInstallation();

        // Verify buttons availability on 'Install' page
        $databaseName = DB_NAME_PREFIX."_install";
        parent::navigateInstallPage($databaseName);
        $this->assertTrue( $this->isElementPresent( "submit-back" ));
        $this->assertTrue( $this->isElementPresent( "submit-continue" ));
    }


    // Verify only 'Back' button available on 'Complete' page
    public function testOnlyBackButtonAvailability() {

        // Verify only 'Back' button available
        $databaseName = DB_NAME_PREFIX."_back";
        parent::navigateCompletePage( $databaseName );

        // Only 'Back' button available
        $this->assertTrue( $this->isElementPresent( "submit-back" ));

        // 'Continue' button is not available
        $this->assertElementNotPresent( "submit-continue" );
        parent::restartInstallation();
    }
}

