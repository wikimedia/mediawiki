<?php

/**
 * MediaWikiMySQLiteataBaseTestCase
 *
 * @file
 * @ingroup Maintenance
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
 * @addtogroup Maintenance
 *
 */


require_once 'MediaWikiInstallationCommonFunction.php';

/*
 * Test Case ID   : 06 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install Mediawiki using 'MySQL' database type successfully
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiMySQLiteDataBaseTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Verify  MediaWiki installation using 'MySQL' database type
    public function testMySQLDatabaseSuccess() {

        $databaseName = DB_NAME_PREFIX."_sqlite_db";

        parent::navigateConnetToDatabasePage();
        $this->click( "DBType_sqlite" );

        // Select 'SQLite' database type
        $this->assertEquals( "SQLite settings", $this->getText( "//div[@id='DB_wrapper_sqlite']/h3" ));
        
        // Change database name
        $defaultDbName = $this->getText( "sqlite_wgDBname" );
        $this->type( "sqlite_wgDBname", " ");
        $this->type( "sqlite_wgDBname", $databaseName );
        $this->assertNotEquals( $defaultDbName, $databaseName );

        // 'Database settings' page
        parent::clickContinueButton();

        // 'Name' page
        parent::clickContinueButton();
        parent::completeNamePage();

        // 'Options page
        parent::clickContinueButton();

        // 'Install' page
        parent::clickContinueButton();

        // 'Complete' page
        $this->assertEquals( "Complete!",
                $this->getText("//div[@id='bodyContent']/div/div/h2" ));

        // 'Congratulations!' text should be available in the 'Complete!' page.
        $this->assertEquals( "Congratulations!",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/form/div[1]/div[2]/b" ));
        parent::restartInstallation();
    }
}
