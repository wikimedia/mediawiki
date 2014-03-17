<?php
/**
 * Checks LESS files in known resources for errors
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
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';
require_once 'PHPUnit/Autoload.php';

/**
 * @ingroup Maintenance
 */
class CheckLess extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Checks LESS files for errors by running the LessTestSuite PHPUnit test suite';
	}

	public function execute() {
		global $IP;

		// NOTE (phuedx, 2014-03-26) wgAutoloadClasses isn't set up
		// by either of the dependencies at the top of the file, so
		// require it here.
		require_once __DIR__ . '/../tests/TestsAutoLoader.php';

		$textUICommand = new PHPUnit_TextUI_Command();
		$argv = array(
			"$IP/tests/phpunit/phpunit.php",
			"$IP/tests/phpunit/suites/LessTestSuite.php"
		);
		$textUICommand->run( $argv );
	}
}

$maintClass = 'CheckLess';
require_once RUN_MAINTENANCE_IF_MAIN;
