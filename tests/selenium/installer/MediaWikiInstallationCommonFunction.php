<?php
/**
 * MediaWikiInstallationCommonFunction
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

require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
require_once ( dirname( __FILE__ ) . '/MediaWikiInstallationConfig.php' );
require_once ( dirname(__FILE__) . '/MediaWikiInstallationMessage.php' );
require_once ( dirname(__FILE__) . '/MediaWikiInstallationVariables.php');


class MediaWikiInstallationCommonFunction extends PHPUnit_Extensions_SeleniumTestCase {

    function setUp() {
        $this->setBrowser( TEST_BROWSER );
        $this->setBrowserUrl("http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/");
    }


    public function navigateInitialpage() {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/" );
    }


    // Navigate to the 'Language' page
    public function navigateLanguagePage() {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );
    }


    // Navigate to the 'Welcome to MediaWiki' page
    public function navigateWelcometoMediaWikiPage() {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );
        $this->click( "submit-continue ");
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate yo 'Connect to Database' page
    public function navigateConnetToDatabasePage() {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click("submit-continue");
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate to the 'Database Settings' page
    public function navigateDatabaseSettingsPage( $databaseName ) {

        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click("submit-continue");
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click("submit-continue");
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        $this->type("mysql_wgDBname", $databaseName );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate to the 'Name' page
    public function navigateNamePage( $databaseName ) {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        $this->type( "mysql_wgDBname",  $databaseName );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Database settings
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate 'Options' page
    public function navigateOptionsPage( $databaseName ) {

        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        $this->type( "mysql_wgDBname",  $databaseName );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Database settings
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Name
        $this->type( "config_wgSitename", NAME_OF_WIKI );
        $this->type( "config__AdminName", ADMIN_USER_NAME);
        $this->type( "config__AdminPassword", ADMIN_PASSWORD );
        $this->type( "config__AdminPassword2", ADMIN_RETYPE_PASSWORD );
        $this->type( "config__AdminEmail", ADMIN_EMAIL_ADDRESS );

        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate 'Install' page
    public function navigateInstallPage( $databaseName ) {

        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        $this->type( "mysql_wgDBname",  $databaseName );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Database settings
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Name
        $this->type( "config_wgSitename", NAME_OF_WIKI );
        $this->type( "config__AdminName", ADMIN_USER_NAME);
        $this->type( "config__AdminPassword", ADMIN_PASSWORD );
        $this->type( "config__AdminPassword2", ADMIN_RETYPE_PASSWORD );
        $this->type( "config__AdminEmail", ADMIN_EMAIL_ADDRESS );

        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Options page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Navigate to 'Complete' page
    public function navigateCompletePage( $databaseName ) {
        $this->open( "http://".HOST_NAME.":".PORT."/".DIRECTORY_NAME."/config/index.php" );

        // 'Welcome to MediaWiki!' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // 'Connect to Database' page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        $this->type( "mysql_wgDBname",  $databaseName );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Database settings
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Name
        $this->type( "config_wgSitename", NAME_OF_WIKI );
        $this->type( "config__AdminName", ADMIN_USER_NAME);
        $this->type( "config__AdminPassword", ADMIN_PASSWORD );
        $this->type( "config__AdminPassword2", ADMIN_RETYPE_PASSWORD );
        $this->type( "config__AdminEmail", ADMIN_EMAIL_ADDRESS );

        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Options page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );

        // Install page
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->chooseCancelOnNextConfirmation();
    }


    // Complete the Name page fields
    public function completeNamePage() {
        $this->type( "config_wgSitename", NAME_OF_WIKI );
        $this->type( "config__AdminName", ADMIN_USER_NAME);
        $this->type( "config__AdminPassword", ADMIN_PASSWORD );
        $this->type( "config__AdminPassword2", ADMIN_RETYPE_PASSWORD );
        $this->type( "config__AdminEmail", ADMIN_EMAIL_ADDRESS );
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME);
    }


    // Clicking on the 'Continue' button in any MediaWiki page
    public function clickContinueButton() {
        $this->click( "submit-continue" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Clicking on the 'Back' button in any MediaWiki page
    public function clickBackButton() {
        $this->click( "submit-back" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Restarting the installation
    public function restartInstallation() {
        $this->click( "link=Restart installation" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        $this->click( "submit-restart" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
    }


    // Verify 'MediaWiki' logo available in the initial screen
    public function mediaWikiLogoPresentInitialScreen() {
        $this->assertTrue( $this->isElementPresent( "//img[@alt='The MediaWiki logo']" ));
    }


    // Verify 'MediaWiki' logo available
    public function mediaWikiLogoPresent() {
        $this->assertTrue( $this->isElementPresent( "//div[@id='p-logo']/a" ));
    }


    public function completePageSuccessfull() {
        $this->assertEquals( "Complete!",
                $this->getText( "//div[@id='bodyContent']/div/div/h2" ));

        // 'Congratulations!' text should be available in the 'Complete!' page.
        $this->assertEquals( "Congratulations!",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]/p[1]/b" ));
    }
}
