<?php

/**
 * MediaWikiInstallerTestSuite
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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/TestSuite.php';

require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiUserInterfaceTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiButtonsAvailabilityTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiHelpFieldHintTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiRightFrameworkLinksTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiRestartInstallationTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiErrorsConnectToDatabasePageTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiErrorsNamepageTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiMySQLDataBaseTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiMySQLiteDataBaseTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiUpgradeExistingDatabaseTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiDifferntDatabasePrefixTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiDifferentDatabaseAccountTestCase.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'MediaWikiOnAlreadyInstalledTestCase.php');




$suite = new PHPUnit_Framework_TestSuite('ArrayTest');
$result = new PHPUnit_Framework_TestResult;

$suite->run($result);
