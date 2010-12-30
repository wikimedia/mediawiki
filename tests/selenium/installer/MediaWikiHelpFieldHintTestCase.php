<?php

/**
 * MediaWikiHelpFieldHintTestCase
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

/*
 * Test Case ID   : 29 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Help field hint availability for the fields.
 * Version        : MediaWiki 1.18alpha
*/

require_once ( dirname( __FILE__ ) . '/MediaWikiInstallationCommonFunction.php' );

class MediaWikiHelpFieldHintTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }


    // Verify help field availability for the fields
    public function testMySQLConnectToDatabaseFieldHint() {

        parent::navigateConnetToDatabasePage();

        // Verify help field for 'Database host'
        $this->click( "//div[@id='DB_wrapper_mysql']/div/div[1]/div/span[1]" );
        $this->assertEquals( MYSQL_DATABASE_HOST_HELP,
                $this->getText( "//div[@id='DB_wrapper_mysql']/div/div[1]/div/span[2]" ) );

        // Verify help field for 'Database name'
        $this->click( "//div[@id='DB_wrapper_mysql']/fieldset[1]/div[1]/div[1]/div/span[1]" );
        $this->assertEquals( MYSQL_DATABASE_NAME_HELP,
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[1]/div[1]/div[1]/div/span[2]" ));


        // Verify help field for 'Database table prefix'
        $this->click("//div[@id='DB_wrapper_mysql']/fieldset[1]/div[2]/div[1]/div/span[1]" );
        $this->assertEquals(MYSQL_DATABASE_TABLE_PREFIX_HELP,
                $this->getText("//div[@id='DB_wrapper_mysql']/fieldset[1]/div[1]/div[1]/div/span[2]/p[1]" ));

        // Verify help field for 'Database username'
        $this->click( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[1]/div[1]/div/span[1]" );
        $this->assertEquals( MYSQL_DATBASE_USERNAME_HELP,
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[1]/div[1]/div/span[2]" ));

        // Verify help field for 'Database password'
        $this->click( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[2]/div[1]/div/span[1]" );
        $this->assertEquals( MYSQL_DATABASE_PASSWORD_HELP,
                $this->getText( "//div[@id='DB_wrapper_mysql']/fieldset[2]/div[2]/div[1]/div/span[2]/p" ));
    }


    public function testSQLiteConnectToDatabaseFieldHint() {

        parent::navigateConnetToDatabasePage();
        $this->click( "DBType_sqlite" );

        //  Verify help field for 'SQLite data directory'
        $this->click( "//div[@id='DB_wrapper_sqlite']/div[1]/div[1]/div/span[1]" );
        $this->assertEquals( SQLITE_DATA_DIRECTORY_HELP,
                $this->getText( "//div[@id='DB_wrapper_sqlite']/div[1]/div[1]/div/span[2]" ));

        // Verify help field for 'Database name'
        $this->click( "//div[@id='DB_wrapper_sqlite']/div[2]/div[1]/div/span[1]" );
        $this->assertEquals( SQLITE_DATABASE_NAME_HELP , $this->getText( "//div[@id='DB_wrapper_sqlite']/div[2]/div[1]/div/span[2]/p" ));
    }


    public function testDatabaseSettingsFieldHint() {

        $databaseName = DB_NAME_PREFIX."_db_field";
        parent::navigateDatabaseSettingsPage($databaseName);

        // Verify help field for 'Search engine'
        $this->click( LINK_FORM."div[2]/span[1]" );
        $this->assertEquals( SEARCH_ENGINE_HELP,
                $this->getText( LINK_FORM."div[2]/span[2]" ));

        // Verify help field for 'Database character set'
        $this->click( LINK_FORM."div[4]/span[1]" );
        $this->assertEquals( DATABASE_CHARACTER_SET_HELP,
                $this->getText( LINK_FORM."div[4]/span[2]"));
        parent::restartInstallation();
    }


    public function testNameFieldHint() {

        $databaseName = DB_NAME_PREFIX."_name_field";
        parent::navigateNamePage( $databaseName );

        // Verify help field for 'Name of Wiki'
        $this->click( LINK_FORM."div[1]/div[1]/div/span[1]" );
        $this->assertEquals( NAME_OF_WIKI_HELP,
                $this->getText( LINK_FORM."div[1]/div[1]/div/span[2]/p" ));

        // Verify help field for 'Project namespace'
        $this->click( LINK_FORM."div[2]/div[1]/div/span[1]" );
        $this->assertEquals( PROJECT_NAMESPACE_HELP,
                $this->getText( LINK_FORM."div[2]/div[1]/div/span[2]/p"));

        // Verify help field for 'Your Name'
        $this->click( LINK_FORM."fieldset/div[1]/div[1]/div/span[1]" );
        $this->assertEquals( USER_NAME_HELP,
                $this->getText( LINK_FORM."fieldset/div[1]/div[1]/div/span[2]/p" ));

        // Verify help field for 'E mail address'
        $this->click( LINK_FORM."fieldset/div[4]/div[1]/div/span[1]" );
        $this->assertEquals( EMAIL_ADDRESS_HELP,
                $this->getText( LINK_FORM."fieldset/div[4]/div[1]/div/span[2]/p" ));

        // Verify help field for 'Subscribe to the release announcements mailing list'
        $this->click( LINK_FORM."fieldset/div[5]/div/span[1]" );
        $this->assertEquals( SUBSCRIBE_MAILING_LIST_HELP,
                $this->getText( LINK_FORM."fieldset/div[5]/div/span[2]/p" ));
        parent::restartInstallation();
    }
}

