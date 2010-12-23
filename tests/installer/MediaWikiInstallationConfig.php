<?php

/**
 * MediaWikiInstallationConfig
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


/*
 * MediaWikiInstallerTestSuite.php can be run one time successfully
 * with current value of the 'DB_NAME_PREFIX'.
 * If you wish to run the suite more than one time, you need to change
 * the value of the 'DB_NAME_PREFIX'.
*/
define('DB_NAME_PREFIX', "database_name" );
define('DIRECTORY_NAME', "mediawiki" );
define ('PORT', "8080" );
define( 'HOST_NAME', "localhost" );

/*
 *  Use the followings to run the test suite in different browsers.
 *  Firefox : *firefox
 *  IE :  *iexplore
 *  Google chrome : *googlechrome
 *  Opera :  *opera
*/
define ( 'TEST_BROWSER', "*firefox" );
