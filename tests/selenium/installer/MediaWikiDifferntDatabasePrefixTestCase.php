<?php

/**
 * MediaWikiDifferntDatabasePrefixTestCase
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
 * Test Case ID   : 02 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install MediaWiki with the same database and the different
 *                  database prefixes(Share one database between multiple wikis).
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiDifferntDatabasePrefixTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Install Mediawiki using 'MySQL' database type.
    public function testDifferentDatabasePrefix() {

        $databaseName = DB_NAME_PREFIX."_db_prefix";
        parent::navigateInstallPage( $databaseName );

        // To 'Options' page
        parent::clickBackButton();

        // To 'Name' page
        parent::clickBackButton();

        // To 'Database settings' page
        parent::clickBackButton();

        // To 'Connect to database' page
        parent::clickBackButton();

        // From 'Connect to database' page without database prefix
        parent::clickContinueButton();

        // Verify upgrade existing message
        $this->assertEquals( "Upgrade existing installation",
                $this->getText( LINK_DIV."h2" ));

        // To 'Connect to database' page
        parent::clickBackButton();

        // Input the database prefix
        $this->type( "mysql_wgDBprefix", DATABASE_PREFIX );

        // From 'Connect to database' page with database prefix
        parent::clickContinueButton();

        // To 'Complete' page
        parent::clickContinueButton();
        parent::completeNamePage();
        parent::clickContinueButton();

        // Verify already installed warning message
        $this->assertEquals( "Install",
                $this->getText( LINK_DIV."h2" ));
        $this->assertEquals( "Warning: You seem to have already installed MediaWiki and are trying to install it again. Please proceed to the next page.",
                $this->getText( LINK_FORM."div[1]" ));

        parent::clickContinueButton();
        parent::completePageSuccessfull();
        $this->chooseCancelOnNextConfirmation();
        parent::restartInstallation();
    }
}
