<?php

/**
 * MediaWikiUserInterfaceTestCase
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

/**
 * Test Case ID   : 18 - 27 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : UI of MediaWiki initial/ Language/ Welcome to MediaWiki!/ Connect to database/
 * Database settings/ Name/ Options/ Install/ Complete/ Restart Inslation pages
 * Version        : MediaWiki 1.18alpha
*/


class MediaWikiUserInterfaceTestCase extends MediaWikiInstallationCommonFunction {
    
    function setUp() {
        parent::setUp();
    }
    
    
    public function testInitialPageUI() {
        
        parent::navigateInitialpage();
        
        // MediaWiki logo available
        $this->assertTrue( $this->isElementPresent( "//img[@alt='The MediaWiki logo']" ));
        
        // 'MediaWiki 1.18alpha' text available
        $this->assertEquals( "MediaWiki 1.18alpha", $this->getText( "//h1" ));
        
        // 'LocalSettings.php not found.' text available
        $this->assertEquals( "LocalSettings.php not found.", $this->getText( "//p[1]" ));
        
        // 'Please set up the wiki first' text available
        $this->assertEquals( "Please set up the wiki first.", $this->getText( "//p[2]" ));
        
        // 'set up the wiki' link available
        $this->assertTrue($this->isElementPresent( "link=set up the wiki" ));
    }
    
    
    public function testlanguagePageUI() {
        
        parent::navigateLanguagePage();
        
        // Verify 'Language' heading
        $this->assertEquals( "Language", $this->getText( LINK_DIV."h2" ));
        
        // 'Your language' label available
        $this->assertEquals( "Your language:",
                $this->getText( LINK_FORM."div[1]/div[1]/label" ));
        
        // 'Your language' dropdown available
        $this->assertTrue( $this->isElementPresent( "UserLang" ));
        
        // 'Wiki language' label available
        $this->assertEquals( "Wiki language:",
                $this->getText( LINK_FORM."div[2]/div[1]/label" ));
        
        // 'Wiki language' dropdown available
        $this->assertTrue($this->isElementPresent( "ContLang" ));
    }
    
    
    public function testWelcometoMediaWikiUI() {
        
        parent::navigateWelcometoMediaWikiPage();
        
        // Verify 'Welcome to MediaWiki!' heading
        $this->assertEquals( "Welcome to MediaWiki!",
                $this->getText( LINK_DIV."h2" ));
        
        // Verify environment ok text displayed.
        $this->assertEquals( "The environment has been checked.You can install MediaWiki.",
                $this->getText( LINK_DIV."div[6]/span" ));
    }
    
    
    public function testConnectToDatabaseUI() {
        
        parent::navigateConnetToDatabasePage();
        
        //  'MYSQL radio button available
        $this->assertEquals( "MySQL",
                $this->getText( LINK_FORM."div[2]/div[2]/ul/li[1]/label" ));
        $this->assertTrue( $this->isElementPresent( LINK_FORM."div[2]/div[2]/ul/li[1]" ));
        
        // 'SQLite' radio button available
        $this->assertTrue( $this->isElementPresent( LINK_FORM."div[2]/div[2]/ul/li[2]" ));
        $this->assertEquals( "SQLite", $this->getText( LINK_FORM."div[2]/div[2]/ul/li[2]/label "));
        
        // 'Database host' label available
        $this->assertEquals( "Database host:", $this->getText( "//div[@id='DB_wrapper_mysql']/div/div[1]/label" ));
        
        // 'Database host' text box default to 'localhost'
        $this->assertEquals( "localhost", $this->getValue( "mysql_wgDBserver" ));
        
        // 'Identify this wiki' section available
        $this->assertTrue( $this->isElementPresent( "//div[@id='DB_wrapper_mysql']/fieldset[1]/legend" ));
        
        // 'Identify this wiki' label available
        $this->assertEquals( "Identify this wiki", $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[1]/legend" ));
        
        // 'Database name' lable available
        $this->assertEquals( "Database name:",
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[1]/div[1]/div[1]/label" ));
        
        // Verify 'Database name:' text box is default to 'my_wiki'
        $this->assertEquals( "my_wiki", $this->getValue( "mysql_wgDBname" ));
        
        // Verify 'Database table prefix:' label available
        $this->assertEquals( "Database table prefix:",
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[1]/div[2]/div[1]/label" ));
        
        // 'User account for installation' section available
        $this->assertTrue( $this->isElementPresent( "//div[@id='DB_wrapper_mysql']/fieldset[2]/legend" ));
        
        // 'User account for installation' label available
        $this->assertEquals( "User account for installation", $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[2]/legend" ));
        
        // 'Database username' label available
        $this->assertEquals( "Database username:",
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[1]/div[1]/label" ));
        
        // 'Database username' text box defaults to 'root'
        $this->assertEquals("root", $this->getValue( "mysql__InstallUser" ));
        
        // 'Database password' label available
        $this->assertEquals( "Database password:",
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[2]/div[1]/label" ));
    }
    
    
    
    public function testDatabaseSettingsUI() {
        
        $databaseName = DB_NAME_PREFIX."_db_settings_UI";
        parent::navigateDatabaseSettingsPage( $databaseName );
        
        // 'Database settings' text available.
        $this->assertEquals( "Database settings", $this->getText( LINK_DIV."h2" ));
        
        // 'Database account for web access' section available
        $this->assertTrue( $this->isElementPresent( LINK_FORM."fieldset" ));
        
        // 'Database account for web access' label available
        $this->assertEquals( "Database account for web access", $this->getText( LINK_FORM."fieldset/legend" ));
        
        // 'Use the same account as for installation' check box available
        $this->assertEquals( "Use the same account as for installation", $this->getText( LINK_FORM."fieldset/div[1]/label" ));
        
        // 'Use the same account as for installation' check box is selected by default
        $this->assertEquals( "on", $this->getValue( "mysql__SameAccount" ));
        
        // 'Use the same account as for installation' check box deselected
        $this->click( "mysql__SameAccount" );
        
        // verify 'Use the same account as for installation' check box is not selected
        $this->assertEquals( "off", $this->getValue( "mysql__SameAccount" ));
        
        // 'Database username' label available
        $this->assertEquals( "Database username:", $this->getText( "//div[@id='dbOtherAccount']/div[1]/div[1]/label" ));
        
        // 'Database username' text box is default to the 'wikiuser'
        $this->assertEquals( "wikiuser", $this->getValue( "mysql_wgDBuser" ));
        
        // 'Database password' label available
        $this->assertEquals( "Database password:", $this->getText( "//div[@id='dbOtherAccount']/div[2]/div[1]/label" ));
        
        // 'Create the account if it does not already exist' label available
        $this->assertEquals( "Create the account if it does not already exist", $this->getText( "//div[@id='dbOtherAccount']/div[4]/label" ));
        
        // 'Create the account if it does not already exist' check box is not selected by default
        $this->assertEquals( "off" , $this->getValue( "mysql__CreateDBAccount" ));
        
        //  'Create the account if it does not already exist' check box selected
        $this->click( "mysql__CreateDBAccount" );
        
        // Verify  'Create the account if it does not already exist' check box is selected
        $this->assertEquals( "on" , $this->getValue( "mysql__CreateDBAccount" ));
        $this->click( "mysql__SameAccount" );
        $this->assertEquals( "on", $this->getValue( "mysql__SameAccount" ));
        
        // 'Storage engine' label available
        $this->assertEquals( "Storage engine:",
                $this->getText( LINK_FORM."div[1]/div[1]/label"));
        
        // 'InnoDB' label available
        $this->assertEquals( "InnoDB",
                $this->getText( LINK_FORM."div[1]/div[2]/ul/li[1]/label" ));
        
        // 'InnoDB' radio button available
        $this->assertTrue( $this->isElementPresent( "mysql__MysqlEngine_InnoDB" ));
        
        // 'MyISAM' label available
        $this->assertEquals( "MyISAM", $this->getText( LINK_FORM."div[1]/div[2]/ul/li[2]/label" ));
        
        // 'MyISAM' radio button available
        $this->assertTrue($this->isElementPresent( "mysql__MysqlEngine_MyISAM" ));
        
        // 'Database character set' label available
        $this->assertEquals( "Database character set:",
                $this->getText( LINK_FORM."div[3]/div[1]/label" ));
        
        // 'Binary' radio button available
        $this->assertTrue( $this->isElementPresent( "mysql__MysqlCharset_binary" ));
        
        // 'Binary' radio button available
        $this->assertEquals( "Binary", $this->getText( LINK_FORM."div[3]/div[2]/ul/li[1]/label" ));
        
        // 'UTF-8' radio button available
        $this->assertTrue( $this->isElementPresent( "mysql__MysqlCharset_utf8" ));
        
        // 'UTF-8' label available
        $this->assertEquals( "UTF-8", $this->getText( LINK_FORM."div[3]/div[2]/ul/li[2]/label" ));
        
        // 'Binary' radio button is selected
        $this->assertEquals( "on", $this->getValue( "mysql__MysqlCharset_binary" ));
    }
    
    
    
    public function testNamePageUI() {
        
        $databaseName = DB_NAME_PREFIX."_name_UI";
        parent::navigateNamePage($databaseName);
        
        // 'Name of wiki' text box available
        $this->assertEquals( "Name of wiki:",
                $this->getText( LINK_FORM."div[1]/div[1]/label" ));
        
        $this->assertTrue( $this->isElementPresent( "config_wgSitename" ));
        
        // 'Project namespace' label available
        $this->assertEquals( "Project namespace:",
                $this->getText( LINK_FORM."div[2]/div[1]/label" ));
        
        // 'Same as the wiki name' radio button available
        $this->assertTrue( $this->isElementPresent( "config__NamespaceType_site-name" ));
        
        // 'Project' radio button available
        $this->assertTrue( $this->isElementPresent( "config__NamespaceType_generic" ));
        
        // 'Project' radio button available
        $this->assertTrue( $this->isElementPresent( "config__NamespaceType_other" ));
        
        // 'Same as the wiki name' label available
        $this->assertEquals( "Same as the wiki name:",
                $this->getText( LINK_FORM."div[2]/div[2]/ul/li[1]/label" ));
        
        // 'Project' label available
        $this->assertEquals("Project",
                $this->getText( LINK_FORM."div[2]/div[2]/ul/li[2]/label" ));
        
        // 'Project' label available
        $this->assertEquals( "Other (specify)",
                $this->getText( LINK_FORM."div[2]/div[2]/ul/li[3]/label" ));
        
        //  'Same as the wiki name' radio button selected by default
        $this->assertEquals( "on", $this->getValue( "config__NamespaceType_site-name" ));
        
        // 'Administrator account' section available
        $this->assertTrue( $this->isElementPresent( LINK_FORM."fieldset" ));
        
        // 'Administrator account' label available
        $this->assertEquals( "Administrator account",
                $this->getText( LINK_FORM."fieldset/legend" ));
        
        // 'Your Name' label available
        $this->assertEquals( "Your name:",
                $this->getText( LINK_FORM."fieldset/div[1]/div[1]/label" ));
        
        // 'Your Name' text box available
        $this->assertTrue( $this->isElementPresent( "config__AdminName" ));
        
        // 'Password' label available
        $this->assertEquals( "Password:",
                $this->getText( LINK_FORM."fieldset/div[2]/div[1]/label" ));
        
        // 'Password' text box available
        $this->assertTrue( $this->isElementPresent( "config__AdminPassword" ));
        
        // 'Password again' label available
        $this->assertEquals( "Password again:",
                $this->getText( LINK_FORM."fieldset/div[3]/div[1]/label" ));
        
        // 'Password again' text box available
        $this->assertTrue( $this->isElementPresent( "config__AdminPassword2" ));
        
        // 'Email address' label avaialble
        $this->assertEquals( "E-mail address:",
                $this->getText( LINK_FORM."fieldset/div[4]/div[1]/label" ));
        
        // 'Email address' text box available
        $this->assertTrue( $this->isElementPresent( "config__AdminEmail" ));
        
        // Message displayed
        $this->assertEquals( "You are almost done! You can now skip the remaining configuration and install the wiki right now.",
                $this->getText( LINK_FORM."/div[4]/div[2]/p" ));
        
        // 'Ask me more questions.' radio button available
        $this->assertTrue( $this->isElementPresent( "config__SkipOptional_continue" ));
        
        // 'Ask me more questions.' label available
        $this->assertEquals( "Ask me more questions.",
                $this->getText( LINK_FORM."div[5]/div[2]/ul/li[1]/label" ));
        
        // 'I'm bored already, just install the wiki' radio button is avaiable
        $this->assertTrue( $this->isElementPresent( "config__SkipOptional_skip" ));
        
        // 'I'm bored already, just install the wiki' label available
        $this->assertEquals( "I'm bored already, just install the wiki.",
                $this->getText( LINK_FORM."div[5]/div[2]/ul/li[2]/label" ));
        
        //  'Ask me more questions.' radio button is default selected
        $this->assertEquals( "on", $this->getValue( "config__SkipOptional_continue" ));
    }
    
    
    
    public function testOptionPageUI() {
        
        $databaseName = DB_NAME_PREFIX."_options_UI";
        parent::navigateOptionsPage($databaseName);
        
        // 'Options' label available
        $this->assertEquals( "Options", $this->getText( LINK_DIV."h2"));
        
        // 'Return e-mail address' label available
        $this->assertEquals( "Return e-mail address:", $this->getText( "//div[@id='emailwrapper']/div[1]/div[1]/label" ));
        
        //    'Return e-mail address' text box available
        $this->assertTrue( $this->isElementPresent( "config_wgPasswordSender" ));
        
        // Text 'apache@localhost' is default value of the 'Return e-mail address' text box
        $this->assertEquals( "apache@localhost", $this->getValue( "config_wgPasswordSender" ));
        
        // 'Logo URL' label available
        $this->assertEquals( "Logo URL:", $this->getText( LINK_FORM."fieldset[2]/div[3]/div[1]/label" ));
        
        // 'Logo URL' text box available
        $this->assertTrue( $this->isElementPresent( "config_wgLogo" ));
        
        // Correct path available in the 'Logo URL' text box
        $this->assertEquals( "/wiki/skins/common/images/wiki.png", $this->getValue( "config_wgLogo" ));
        
        // 'Enable file uploads' radio button available
        $this->assertTrue( $this->isElementPresent( "config_wgEnableUploads" ));
        
        // 'Enable file uploads' label available
        $this->assertEquals( "Enable file uploads",
                $this->getText( LINK_FORM."fieldset[2]/div[1]/label" ));
        
        // 'Enable file uploads' check box is not selected
        $this->assertEquals( "off", $this->getValue( "config_wgEnableUploads" ));
        
        $this->click( "config_wgEnableUploads" );
        
        // 'Directory for deleted files' label available
        $this->assertEquals( "Directory for deleted files:",
                $this->getText( "//div[@id='uploadwrapper']/div/div[1]/label" ));
        
        // 'Directory for deleted files' text box available
        $this->assertTrue( $this->isElementPresent( "config_wgDeletedDirectory" ));
        
        // Correct path available in the 'Directory for deleted files' text box
        $this->assertEquals( "C:\\wamp\\www\\".DIRECTORY_NAME."/images/deleted",
                $this->getValue( "config_wgDeletedDirectory" ));
    }
    
    
    
    public function testInstallPageUI() {
        
        $databaseName = DB_NAME_PREFIX."_install_UI";
        parent::navigateInstallPage( $databaseName );
        
        // Verify installation done messages display
        $this->assertEquals( "Setting up database... done",
                $this->getText( LINK_FORM."ul/li[1]" ));
        $this->assertEquals( "Creating tables... done",
                $this->getText( LINK_FORM."ul/li[2]" ));
        $this->assertEquals( "Creating database user... done",
                $this->getText( LINK_FORM."ul/li[3]" ));
        $this->assertEquals( "Populating default interwiki table... done",
                $this->getText( LINK_FORM."ul/li[4]" ));
        $this->assertEquals( "Generating secret key... done",
                $this->getText( LINK_FORM."ul/li[5]" ));
        $this->assertEquals( "Generating default upgrade key... done",
                $this->getText( LINK_FORM."ul/li[6]" ));
        $this->assertEquals( "Creating administrator user account... done",
                $this->getText( LINK_FORM."ul/li[7]" ));
        $this->assertEquals( "Creating main page with default content... done",
                $this->getText( LINK_FORM."ul/li[8]" ));
    }
    
    
    
    public function testCompletePageUI() {
        
        $databaseName = DB_NAME_PREFIX."_complete_UI";
        parent::navigateCompletePage( $databaseName );
        
        // 'Congratulations!' text display
        $this->assertEquals("Congratulations!",
                $this->getText( LINK_FORM."div[1]/div[2]/p[1]/b"));
        // 'LocalSettings.php' generated message display
        $this->assertEquals( "The installer has generated a LocalSettings.php file. It contains all your configuration.",
                $this->getText( LINK_FORM."div[1]/div[2]/p[2]" ));
        
        // 'Download LocalSettings.php'' link available
        $this->assertTrue( $this->isElementPresent( "link=Download LocalSettings.php" ));
        
        // 'enter your wiki' link available
        $this->assertTrue($this->isElementPresent("link=Folder/index.php enter your wiki"));
    }
    
    
    
    public function testRestartInstallation() {
        
        parent::navigateConnetToDatabasePage();
        $this->click( "link=Restart installation" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        
        // Restart installation' label should be available.
        $this->assertEquals( "Restart installation", $this->getText( LINK_DIV."h2" ));
        
        //'Do you want to clear all saved data that you have entered and restart the installation process?' label available
        $this->assertEquals( "Do you want to clear all saved data that you have entered and restart the installation process?",
                $this->getText( "//*[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]" ));
        // 'Back' button available
        $this->assertTrue($this->isElementPresent( "submit-back" ));
        
        // 'Restart' button available
        $this->assertTrue($this->isElementPresent( "submit-restart" ));
    }
    
    
    
    public function testMediaWikiLogoAvailability() {
        
        $databaseName = DB_NAME_PREFIX."_mediawiki_logo";
        parent::navigateInitialpage();
        parent::mediaWikiLogoPresentInitialScreen();
        $this->click( "link=set up the wiki" );
        $this->waitForPageToLoad( PAGE_LOAD_TIME );
        
        // 'Language' page
        parent::mediaWikiLogoPresent();
        parent::clickContinueButton();
        
        // 'Welcome to MediaWiki' page
        parent::mediaWikiLogoPresent();
        parent::clickContinueButton();
        
        // 'Connet to database' page
        parent::mediaWikiLogoPresent();
        $this->type("mysql_wgDBname", $databaseName );
        parent::clickContinueButton();
        
        // 'Database setting' page
        parent::mediaWikiLogoPresent();
        parent::clickContinueButton();
        
        // 'Name' page
        parent::mediaWikiLogoPresent();
        parent::completeNamePage();
        parent::clickContinueButton();
        
        // 'Options' page
        parent::mediaWikiLogoPresent();
        parent::clickContinueButton();
        
        // 'Install' page
        parent::mediaWikiLogoPresent();
    }
    
    
    public function testRightFramework() {
        
        parent::navigateLanguagePage();
        // Verfy right framework texts display
        $this->assertEquals( "Language",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[1]" ));
        $this->assertEquals( "Existing wiki",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[2]" ));
        $this->assertEquals( "Welcome to MediaWiki!",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[3]" ));
        $this->assertEquals( "Connect to database",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[4]" ));
        $this->assertEquals( "Upgrade existing installation",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[5]" ));
        $this->assertEquals( "Database settings",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[6]" ));
        $this->assertEquals( "Name",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[7]" ));
        $this->assertEquals( "Options",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[8]" ));
        $this->assertEquals( "Install",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[9]" ));
        $this->assertEquals( "Complete!",
                $this->getText( LINK_RIGHT_FRAMEWORK."li[10]/span" ));
    } 
}
