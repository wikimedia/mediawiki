<?php

/**
 * MediaWikiInstallerTestSuite
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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'MediaWikiUserInterfaceTestCase.php';
require_once 'MediaWikiButtonsAvailabilityTestCase.php';
require_once 'MediaWikiHelpFieldHintTestCase.php';
require_once 'MediaWikiRightFrameworkLinksTestCase.php';
require_once 'MediaWikiRestartInstallationTestCase.php';
require_once 'MediaWikiErrorsConnectToDatabasePageTestCase.php';
require_once 'MediaWikiErrorsNamepageTestCase.php';
require_once 'MediaWikiMySQLDataBaseTestCase.php';
require_once 'MediaWikiMySQLiteDataBaseTestCase.php';
require_once 'MediaWikiUpgradeExistingDatabaseTestCase.php';
require_once 'MediaWikiDifferntDatabasePrefixTestCase.php';
require_once 'MediaWikiDifferentDatabaseAccountTestCase.php';

$suite = new PHPUnit_Framework_TestSuite('ArrayTest');
$result = new PHPUnit_Framework_TestResult;

$suite->run($result);
