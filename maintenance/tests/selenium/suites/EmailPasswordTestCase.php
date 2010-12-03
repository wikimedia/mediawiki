<?php

/**
 * Selenium server manager
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

class EmailPasswordTestCase extends SeleniumTestCase {

    // change user name for each and every test (with in 24 hours)
    private $userName = "test1";

    public function testEmailPasswordButton() {

        $this->click( "link=Log out" );
        $this->waitForPageToLoad( "30000" );

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        // click Log in / create account link to open Log in / create account' page
        $this->click( "link=Log in / create account" );
        $this->waitForPageToLoad( "30000" );
        $this->assertTrue($this->isElementPresent( "wpMailmypassword" ));
    }

    // Verify Email password functionality
    public function testEmailPasswordMessages() {

        $this->click( "link=Log out" );
        $this->waitForPageToLoad( "30000" );

        $this->open( $this->getUrl().'/index.php?title=Main_Page' );

        // click Log in / create account link to open Log in / create account' page
        $this->click( "link=Log in / create account" );
        $this->waitForPageToLoad( "30000" );

        $this->type( "wpName1", "" );
        $this->click( "wpMailmypassword" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n You have not specified a valid user name.",
                $this->getText("//div[@id='bodyContent']/div[4]"));

        $this->type( "wpName1", $this->userName );
        $this->click( "wpMailmypassword" );
        $this->waitForPageToLoad( "30000" );

        //  Can not run on localhost
        $this->assertEquals( "A new password has been sent to the e-mail address registered for ".ucfirst($this->userName).". Please log in again after you receive it.",
                $this->getText("//div[@id='bodyContent']/div[4]" ));

        $this->type( "wpName1", $this->userName );
        $this->click( "wpMailmypassword" );
        $this->waitForPageToLoad( "30000" );
        $this->assertEquals( "Login error\n A password reminder has already been sent, within the last 24 hours. To prevent abuse, only one password reminder will be sent per 24 hours.",
                $this->getText( "//div[@id='bodyContent']/div[4]" ));
    }
}
?>
