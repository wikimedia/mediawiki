<?php

/**
 * MediaWikiErrorsNamepageTestCase
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
 * Test Case ID   : 10 (http://www.mediawiki.org/wiki/New_installer/Test_plan)
 * Test Case Name : Invalid/ blank values for fields in 'Name' page.
 * Version        : MediaWiki 1.18alpha
*/

require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiInstallationCommonFunction.php');

class MediaWikiErrorsNamepageTestCase extends MediaWikiInstallationCommonFunction {

    function setUp() {
        parent::setUp();
    }

    // Verify warning message for the 'Name' page
    public function testErrorsNamePage() {

        $databaseName  = DB_NAME_PREFIX."_error_name";

        parent::navigateNamePage( $databaseName );

        // Verify warning message for all blank fields
        parent::clickContinueButton();
        $this->assertEquals( "Enter a site name.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));
        $this->assertEquals( "Enter an administrator username.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[3]/div[2]" ));
        $this->assertEquals( "Enter a password for the administrator account.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[4]/div[2]" ));

        // Verify warning message for the blank 'Site name'
        $this->type( "config__AdminName", VALID_YOUR_NAME );
        $this->type( "config__AdminPassword", VALID_PASSWORD );
        $this->type( "config__AdminPassword2", VALID_PASSWORD_AGAIN );
        parent::clickContinueButton();
        $this->assertEquals( "Enter a site name.",
                $this->getText(" //div[@id='bodyContent']/div/div/div[2]/div[2]" ));

        // Input valid 'Site name'
        $this->type( "config_wgSitename", VALID_WIKI_NAME );


        // Verify warning message for the invalid "Project namespace'
        $this->click( "config__NamespaceType_other" );
        $this->type( "config_wgMetaNamespace", INVALID_NAMESPACE );
        parent::clickContinueButton();
        $this->assertEquals( "The specified namespace \"\" is invalid. Specify a different project namespace.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));


        // Verify warning message for the blank 'Project namespace'
        $this->type( "config_wgSitename",  VALID_WIKI_NAME );
        $this->click( "config__NamespaceType_other" );
        $this->type( "config_wgMetaNamespace" , "" );
        parent::clickContinueButton();
        $this->assertEquals( "The specified namespace \"\" is invalid. Specify a different project namespace.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));


        // Valid 'Project namespace'
        $this->click( "config__NamespaceType_other" );
        $this->type( "config_wgMetaNamespace", VALID_NAMESPACE );
        parent::clickContinueButton();


        // Valid 'Site name'
        $this->click( "config__NamespaceType_site-name" );
        $this->type( "config_wgSitename", VALID_WIKI_NAME );


        // Verify warning message for blank 'Your name'
        $this->type( "config__AdminName", " " );
        parent::clickContinueButton();
        $this->assertEquals( "Enter an administrator username.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));

        $this->type( "config_wgSitename", VALID_WIKI_NAME );
        // Verify warning message for blank 'Password'
        $this->type( "config__AdminName", VALID_YOUR_NAME );
        $this->type( "config__AdminPassword", " " );
        parent::clickContinueButton();
        $this->assertEquals( "Enter a password for the administrator account.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));


        // Verify warning message for the blank 'Password again'
        $this->type( "config_wgSitename", VALID_WIKI_NAME );
        $this->type( "config__AdminPassword", VALID_PASSWORD );
        $this->type( "config__AdminPassword2", " " );
        parent::clickContinueButton();
        $this->assertEquals( "The two passwords you entered do not match.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));


        // Verify warning message for the different'Password' and 'Password again'
        $this->type( "config_wgSitename", VALID_WIKI_NAME );
        $this->type( "config__AdminPassword", VALID_PASSWORD );
        $this->type( "config__AdminPassword2", INVALID_PASSWORD_AGAIN );
        parent::clickContinueButton();
        $this->assertEquals( "The two passwords you entered do not match.",
                $this->getText( "//div[@id='bodyContent']/div/div/div[2]/div[2]" ));
    }
}
