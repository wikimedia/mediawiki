<?php

/**
 * MediaWikiErrorsConnectToDatabasePageTestCase
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


require_once ( __DIR__ . '/MediaWikiInstallationCommonFunction.php' );

/**
 * Test Case ID   : 09 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Invalid/ blank values for fields in 'Connect to database' page.
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiErrorsConnectToDatabasePageTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Verify warning messages for the 'Connet to database' page
    public function testErrorsConnectToDatabasePage() {

        parent::navigateConnetToDatabasePage();

        // Verify warning mesage for invalid database host
        $this->type( "mysql_wgDBserver", INVALID_DB_HOST );
        parent::clickContinueButton();
        $this->assertEquals( "DB connection error: php_network_getaddresses: getaddrinfo failed: No such host is known. (".INVALID_DB_HOST.").",
                $this->getText( LINK_DIV."div[2]/div[2]/p[1]" ));
        $this->assertEquals( "Check the host, username and password below and try again.",
                $this->getText( LINK_DIV."div[2]/div[2]/p[2]" ));
        // Verify warning message for the blank database host
        $this->type( "mysql_wgDBserver", "" );
        parent::clickContinueButton();
        $this->assertEquals( "MySQL 4.0.14 or later is required, you have .",
                $this->getText( LINK_DIV."div[2]/div[2]" ));

        // Valid Database Host
        $this->type( "mysql_wgDBserver", VALID_DB_HOST );

        // Verify warning message for the invalid database name
        $this->type( "mysql_wgDBname", INVALID_DB_NAME );
        parent::clickContinueButton();
        $this->assertEquals( "Invalid database name \"".INVALID_DB_NAME."\". Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).",
                $this->getText( LINK_DIV."div[2]/div[2]/p" ));

        // Verify warning message for the blank database name
        $this->type( "mysql_wgDBname", "");
        parent::clickContinueButton();
        $this->assertEquals( "You must enter a value for \"Database name\"",
                $this->getText( LINK_DIV."div[2]/div[2]" ));

        // valid Database name
        $this->type( "mysql_wgDBname", VALID_DB_NAME);

        // Verify warning message for the invalid databaase prefix
        $this->type( "mysql_wgDBprefix", INVALID_DB_PREFIX );
        parent::clickContinueButton();
        $this->assertEquals( "Invalid database prefix \"".INVALID_DB_PREFIX."\". Use only ASCII letters (a-z, A-Z), numbers (0-9) and underscores (_).",
                $this->getText( LINK_DIV."div[2]/div[2]" ));

        // Valid Database prefix
        $this->type( "mysql_wgDBprefix", VALID_DB_PREFIX );

        // Verify warning message for the invalid database user name
        $this->type( "mysql__InstallUser", INVALID_DB_USER_NAME );
        parent::clickContinueButton();
        $this->assertEquals( "DB connection error: Access denied for user '".INVALID_DB_USER_NAME."'@'localhost' (using password: NO) (localhost).",
                $this->getText( LINK_DIV."div[2]/div[2]/p[1]" ));
        $this->assertEquals( "Check the host, username and password below and try again.",
                $this->getText( LINK_DIV."div[2]/div[2]/p[2]"));

        // Verify warning message for the blank database user name
        $this->type( "mysql__InstallUser", "" );
        parent::clickContinueButton();
        $this->assertEquals( "DB connection error: Access denied for user 'SYSTEM'@'localhost' (using password: NO) (localhost).",
                $this->getText( LINK_DIV."div[2]/div[2]/p[1]" ));
        $this->assertEquals( "Check the host, username and password below and try again.",
                $this->getText( LINK_DIV."div[2]/div[2]/p[2]" ));

        // Valid Database username
        $this->type( "mysql__InstallUser",  VALID_DB_USER_NAME );

        // Verify warning message for the invalid password
        $this->type( "mysql__InstallPassword", INVALID_DB_PASSWORD );
        parent::clickContinueButton();

        $this->assertEquals( "DB connection error: Access denied for user 'root'@'localhost' (using password: YES) (localhost).",
                $this->getText( LINK_DIV."div[2]/div[2]/p[1]" ));
        $this->assertEquals( "Check the host, username and password below and try again.",
                $this->getText( LINK_DIV."div[2]/div[2]/p[2]" ));

        // Verify warning message for the invalid username and password
        $this->type( "mysql__InstallUser", INVALID_DB_USER_NAME );
        $this->type( "mysql__InstallPassword", INVALID_DB_PASSWORD );
        parent::clickContinueButton();
        $this->assertEquals( "DB connection error: Access denied for user '".INVALID_DB_USER_NAME."'@'localhost' (using password: YES) (localhost).",
                $this->getText( LINK_DIV."div[2]/div[2]/p[1]" ));
        $this->assertEquals( "Check the host, username and password below and try again.",
                $this->getText( LINK_DIV."div[2]/div[2]/p[2]" ));

        // Valid username and valid password
        $this->type( "mysql__InstallUser", VALID_DB_USER_NAME );
        $this->type( "mysql__InstallPassword", "" );
        parent::clickContinueButton();

        // successfully completes the 'Connect to database' page
        $this->assertEquals( "Database settings",
                $this->getText( LINK_DIV."h2" ));
    }
}
