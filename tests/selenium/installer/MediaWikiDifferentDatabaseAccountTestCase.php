<?php

/**
 * MediaWikiDifferentDatabaseAccountTestCase
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


require_once ( dirname( __FILE__ ) . '/MediaWikiInstallationCommonFunction.php' );

/**
 * Test Case ID   : 04 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install MediaWiki with different Database accounts for web access.
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiDifferentDatabaseAccountTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }


    // Install Mediawiki using 'MySQL' database type.
    public function testDifferentDatabaseAccount() {

        $databaseName = DB_NAME_PREFIX."_dif_accounts";

        // Navigate to the 'Database settings' page
        parent::navigateDatabaseSettingsPage( $databaseName );

        // Click on the 'Use the same account as for installation' check box
        $this->click( "mysql__SameAccount" );

        // Change the 'Database username'
        $this->type( "mysql_wgDBuser", DB_WEB_USER );

        // Enter 'Database password:'
        $this->type( "mysql_wgDBpassword", DB_WEB_USER_PASSWORD );

        // Select 'Create the account if it does not already exist' check box
        $this->click( "mysql__CreateDBAccount" );
        parent::clickContinueButton();

        // 'Name' page
        parent::completeNamePage();

        // 'Options' page
        parent::clickContinueButton();

        // 'Install' page
        $this->assertEquals("Creating database user... done",
                $this->getText( LINK_FORM."ul/li[3]"));
        parent::clickContinueButton();

        // 'Complete' page
        parent::completePageSuccessfull();
        $this->chooseCancelOnNextConfirmation();
    }
}
