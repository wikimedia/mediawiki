<?php

/**
 * Selenium server manager
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


/*
 * Test Case ID   : 03 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Install mediawiki on a already installed Mediawiki.]
 * Version        : MediaWiki 1.18alpha
*/

class MediaWikiOnAlreadyInstalledTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Install Mediawiki using 'MySQL' database type.
    public function testInstallOnAlreadyInstalled() {

        $databaseName = DB_NAME_PREFIX."_already_installed";
        parent::navigateInstallPage( $databaseName );

        // 'Options' page
        parent::clickBackButton();

        // Install page
        parent::clickContinueButton();

        // 'Install' page should display after the 'Option' page
        $this->assertEquals( "Install", $this->getText( LINK_DIV."h2" ));

        // Verify warning text displayed
        $this->assertEquals( "Warning: You seem to have already installed MediaWiki and are trying to install it again. Please proceed to the next page.",
                $this->getText( LINK_FORM."div[1]/div[2]" ));

        // Complete page
        parent::clickContinueButton();
        parent::completePageSuccessfull();
        $this->chooseCancelOnNextConfirmation();
        parent::restartInstallation();
    }
}
