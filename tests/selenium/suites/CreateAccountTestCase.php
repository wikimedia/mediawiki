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

Class CreateAccountTestCase extends SeleniumTestCase {

    // Change these values before run the test
    private $userName = "yourname4000";
    private $password = "yourpass4000";

    // Verify 'Log in/create account' link existance in Main page.
    public function testMainPageLink() {

        $this->click( "link=Log out" );
        $this->waitForPageToLoad( "30000" );

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );
        $this->assertTrue($this->isElementPresent( "link=Log in / create account" ));
    }

    // Verify 'Create an account' link existance in 'Log in / create account' Page.
    public function testCreateAccountPageLink() {

        $this->click( "link=Log out" );
        $this->waitForPageToLoad( "30000" );

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        // click Log in / create account link to open Log in / create account' page
        $this->click( "link=Log in / create account" );
        $this->waitForPageToLoad( "30000" );
        $this->assertTrue($this->isElementPresent( "link=Create an account" ));
    }

    // Verify Create account
    public function testCreateAccount() {

        $this->click( "link=Log out" );
        $this->waitForPageToLoad( "30000" );

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        $this->click( "link=Log in / create account" );
        $this->waitForPageToLoad( "30000" );

        $this->click( "link=Create an account" );
        $this->waitForPageToLoad( "30000" );

        // Verify for blank user name
        $this->type( "wpName2", "" );
        $this->click( "wpCreateaccount" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n You have not specified a valid user name.",
                $this->getText( "//div[@id='bodyContent']/div[4]" ));

        // Verify for invalid user name
        $this->type( "wpName2", "@" );
        $this->click("wpCreateaccount" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n You have not specified a valid user name.",
                $this->getText( "//div[@id='bodyContent']/div[4]" ));

        // start of test for blank password
        $this->type( "wpName2", $this->userName);
        $this->type( "wpPassword2", "" );
        $this->click( "wpCreateaccount" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n Passwords must be at least 1 character.",
                $this->getText("//div[@id='bodyContent']/div[4]" ));

        $this->type( "wpName2", $this->userName );
        $this->type( "wpPassword2", $this->password );
        $this->click( "wpCreateaccount" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n The passwords you entered do not match.",
                $this->getText( "//div[@id='bodyContent']/div[4]" ));

        $this->type( "wpName2", $this->userName );
        $this->type( "wpPassword2", $this->password );
        $this->type( "wpRetype", $this->password );
        $this->click( "wpCreateaccount" );
        $this->waitForPageToLoad( "30000 ");

        // Verify successful account creation for valid combination of 'Username', 'Password', 'Retype password'
        $this->assertEquals( "Welcome, ".ucfirst( $this->userName )."!",
                $this->getText( "Welcome,_".ucfirst( $this->userName )."!" ));
    }
}

