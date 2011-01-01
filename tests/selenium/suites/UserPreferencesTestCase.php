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

class UserPreferencesTestCase extends SeleniumTestCase {

    // Verify user information
    public function testUserInfoDisplay() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify correct username displayed in User Preferences
        $this->assertEquals( $this->getText( "//li[@id='pt-userpage']/a" ),
                $this->getText( "//table[@id='mw-htmlform-info']/tbody/tr[1]/td[2]" ));

        // Verify existing Signature Displayed correctly
        $this->assertEquals( $this->selenium->getUser(),
                $this->getTable( "mw-htmlform-signature.0.1" ) );
    }

    // Verify change password
    public function testChangePassword() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->click( SeleniumTestConstants::LINK_START."Change password" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->type( "wpPassword", "12345" );
        $this->type( "wpNewPassword", "54321" );
        $this->type( "wpRetype", "54321" );
        $this->click( "//input[@value='Change password']" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->assertEquals( "Preferences", $this->getText( "firstHeading" ));

        $this->click( SeleniumTestConstants::LINK_START."Change password" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->type( "wpPassword", "54321" );
        $this->type( "wpNewPassword", "12345" );
        $this->type( "wpRetype", "12345" );
        $this->click( "//input[@value='Change password']" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
        $this->assertEquals( "Preferences", $this->getText( "firstHeading" ));

        $this->click( SeleniumTestConstants::LINK_START."Change password" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->type( "wpPassword", "54321" );
        $this->type( "wpNewPassword", "12345" );
        $this->type( "wpRetype", "12345" );
        $this->click( "//input[@value='Change password']" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
    }

    // Verify successful preferences save
    public function testSuccessfullSave() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->type( "mw-input-realname", "Test User" );
        $this->click( "prefcontrol" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify  "Your preferences have been saved." message
        $this->assertEquals( "Your preferences have been saved.",
                $this->getText( "//div[@id='bodyContent']/div[4]/strong/p" ));
        $this->type( "mw-input-realname", "" );
        $this->click( "prefcontrol" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

    }

    // Verify change signature
    public function testChangeSignature() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->type( "mw-input-nickname", "TestSignature" );
        $this->click( "prefcontrol" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify change user signature
        $this->assertEquals( "TestSignature", $this->getText( SeleniumTestConstants::LINK_START."TestSignature" ));
        $this->type( "mw-input-nickname", "Test" );
        $this->click( "prefcontrol" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
    }

    // Verify change date format
    public function testChangeDateFormatTimeZone() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );

        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
        $this->click( SeleniumTestConstants::LINK_START."Date and time" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        $this->click( "mw-input-date-dmy" );
        $this->select( "mw-input-timecorrection", "label=Asia/Colombo" );
        $this->click( "prefcontrol" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify Date format and time zome saved
        $this->assertEquals( "Your preferences have been saved.",
                $this->getText( "//div[@id='bodyContent']/div[4]/strong/p" ));
    }

    // Verify restoring all default settings
    public function testSetAllDefault() {

        $this->open( $this->getUrl() .
                '/index.php?title=Main_Page&action=edit' );
        $this->click( SeleniumTestConstants::LINK_START."My preferences" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify restoring all default settings
        $this->assertEquals( "Restore all default settings",
                $this->getText( SeleniumTestConstants::LINK_START."Restore all default settings" ));

        $this->click("//*[@id='preferences']/div/a");
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify 'This can not be undone' warning message displayed
        $this->assertTrue($this->isElementPresent("//input[@value='Restore all default settings']"));

        // Verify 'Restore all default settings' button available
        $this->assertEquals("You can use this page to reset your preferences to the site defaults. This cannot be undone.",
                $this->getText("//div[@id='bodyContent']/p"));

        $this->click("//input[@value='Restore all default settings']");
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME  );

        // Verify preferences saved successfully
        $this->assertEquals("Your preferences have been saved.",
                $this->getText("//div[@id='bodyContent']/div[4]/strong/p"));
    }
}

