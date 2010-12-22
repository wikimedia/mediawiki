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
define('DB_NAME_PREFIX', "database_name_d" );

// Directory name  should be change
define('DIRECTORY_NAME', "installertesting");


// Port should be change
define ('PORT', "8080");

// Common variables
define('PAGE_LOAD_TIME', "80000" );


// 'MySQL' database type help field hint
define('MYSQL_DATABASE_HOST_HELP', "If your database server is on different server, enter the host name or IP address here. \nIf you are using shared web hosting, your hosting provider should give you the correct host name in their documentation. \nIf you are installing on a Windows server and using MySQL, using \"localhost\" may not work for the server name. If it does not, try \"127.0.0.1\" for the local IP address.");
define('MYSQL_DATABASE_NAME_HELP', "Choose a name that identifies your wiki. It should not contain spaces or hyphens. \nIf you are using shared web hosting, your hosting provider will either give you a specific database name to use or let you create databases via a control panel.");
define('MYSQL_DATABASE_TABLE_PREFIX_HELP', "Choose a name that identifies your wiki. It should not contain spaces or hyphens.");
define('MYSQL_DATBASE_USERNAME_HELP', "Enter the username that will be used to connect to the database during the installation process. This is not the username of the MediaWiki account; this is the username for your database.");
define('MYSQL_DATABASE_PASSWORD_HELP', "Enter the password that will be used to connect to the database during the installation process. This is not the password for the MediaWiki account; this is the password for your database.");

// 'SQLite' database type help field hint
define('SQLITE_DATA_DIRECTORY_HELP', "SQLite stores all data in a single file. \nThe directory you provide must be writable by the webserver during installation. \nIt should not be accessible via the web, this is why we're not putting it where your PHP files are. \nThe installer will write a .htaccess file along with it, but if that fails someone can gain access to your raw database. That includes raw user data (e-mail addresses, hashed passwords) as well as deleted revisions and other restricted data on the wiki. \nConsider putting the database somewhere else altogether, for example in /var/lib/mediawiki/yourwiki.");
define('SQLITE_DATABASE_NAME_HELP', "Choose a name that identifies your wiki. Do not use spaces or hyphens. This will be used for the SQLite data file name.");

// 'Database settings' page hel0p field hint
define('SEARCH_ENGINE_HELP', "InnoDB is almost always the best option, since it has good concurrency support. \nMyISAM may be faster in single-user or read-only installations. MyISAM databases tend to get corrupted more often than InnoDB databases.");
define('DATABASE_CHARACTER_SET_HELP', "In binary mode, MediaWiki stores UTF-8 text to the database in binary fields. This is more efficient than MySQL's UTF-8 mode, and allows you to use the full range of Unicode characters. \nIn UTF-8 mode, MySQL will know what character set your data is in, and can present and convert it appropriately, but it will not let you store characters above the Basic Multilingual Plane.");

// 'Name' page help field hint
define('NAME_OF_WIKI_HELP', "This will appear in the title bar of the browser and in various other places.");
define('PROJECT_NAMESPACE_HELP', "Following Wikipedia's example, many wikis keep their policy pages separate from their content pages, in a \"project namespace\". All page titles in this namespace start with a certain prefix, which you can specify here. Traditionally, this prefix is derived from the name of the wiki, but it cannot contain punctuation characters such as \"#\" or \":\".");
define('USER_NAME_HELP', "Enter your preferred username here, for example \"Joe Bloggs\". This is the name you will use to log in to the wiki.");
define('EMAIL_ADDRESS_HELP', "Enter an e-mail address here to allow you to receive e-mail from other users on the wiki, reset your password, and be notified of changes to pages on your watchlist.");
define('SUBSCRIBE_MAILING_LIST_HELP', "This is a low-volume mailing list used for release announcements, including important security announcements. You should subscribe to it and update your MediaWiki installation when new versions come out.");


// 'Name' page input values
define( 'NAME_OF_WIKI', "Site Name");
define( 'ADMIN_USER_NAME', "My Name" );
define( 'ADMIN_PASSWORD', "12345" );
define ( 'ADMIN_RETYPE_PASSWORD', "12345" );
define ( 'ADMIN_EMAIL_ADDRESS', "nadeesha@calcey.com" );


// 'Name' page input values for warning messages
define( 'VALID_WIKI_NAME', "MyWiki" );
define( 'VALID_YOUR_NAME', "Nadeesha Weerasinghe" );
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
define ( 'VALID_DB_NAME', "my_wiki1");
define( 'INVALID_DB_PREFIX', "database prefix" );
define( 'VALID_DB_PREFIX', "database_prefix");
define( 'INVALID_DB_USER_NAME', "roots" );
define( 'VALID_DB_USER_NAME', "root");
define( 'INVALID_DB_PASSWORD', "12345" );


