<?php

/**
 * MediaWikiInstallationConfig
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


// Common variables
define('PAGE_LOAD_TIME', "80000" );

// Common links
define( 'LINK_FORM', "//div[@id='bodyContent']/div/div/div[2]/form/" );
define( 'LINK_RIGHT_FRAMEWORK', "//div[@id='bodyContent']/div/div/div[1]/ul[1]/");

// 'Name' page input values
define( 'NAME_OF_WIKI', "Site Name" );
define( 'ADMIN_USER_NAME', "My Name" );
define( 'ADMIN_PASSWORD', "12345" );
define ( 'ADMIN_RETYPE_PASSWORD', "12345" );
define ( 'ADMIN_EMAIL_ADDRESS', "admin@example.com" );


// 'Name' page input values for warning messages
define( 'VALID_WIKI_NAME', "MyWiki" );
define( 'VALID_YOUR_NAME', "FirstName LastName" );
define( 'VALID_PASSWORD', "12345" );
define( 'VALID_PASSWORD_AGAIN', "12345"  );
define( 'INVALID_PASSWORD_AGAIN', "123" );
define( 'VALID_NAMESPACE', "Mynamespace"  );
define( 'INVALID_NAMESPACE', "##..##" );


// 'Database settings' page input values
define( 'DB_WEB_USER', "different" );
define('DB_WEB_USER_PASSWORD', "12345" );


// 'Connet to database' page input values
define( 'DATABASE_PREFIX',"databaseprefix" );


// 'Connet to database' page input values for warning messages
define( 'VALID_DB_HOST', "localhost" );
define( 'INVALID_DB_HOST', "local" );
define( 'INVALID_DB_NAME', "my-wiki" );
define( 'VALID_DB_NAME', "my_wiki1");
define( 'INVALID_DB_PREFIX', "database prefix" );
define( 'VALID_DB_PREFIX', "database_prefix");
define( 'INVALID_DB_USER_NAME', "roots" );
define( 'VALID_DB_USER_NAME', "root");
define( 'INVALID_DB_PASSWORD', "12345" );


