<?php
/**
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
 */

use MediaWiki\Composer\LockFileChecker;
use Wikimedia\Composer\ComposerJson;
use Wikimedia\Composer\ComposerLock;

/**
 * @covers \MediaWiki\Composer\LockFileChecker
 */
class LockFileCheckerTest extends MediaWikiIntegrationTestCase {

	private const FIXTURE_DIRECTORY = MW_INSTALL_PATH . '/tests/phpunit/data/LockFileChecker';

	public function testOk() {
		$json = new ComposerJson( self::FIXTURE_DIRECTORY . '/composer-testcase1.json' );
		$lock = new ComposerLock( self::FIXTURE_DIRECTORY . '/composer-testcase1.lock' );
		$checker = new LockFileChecker( $json, $lock );
		$errors = $checker->check();
		$this->assertNull( $errors );
	}

	public function testOutdated() {
		$json = new ComposerJson( self::FIXTURE_DIRECTORY . '/composer-testcase2.json' );
		$lock = new ComposerLock( self::FIXTURE_DIRECTORY . '/composer-testcase2.lock' );
		$checker = new LockFileChecker( $json, $lock );
		$errors = $checker->check();
		$this->assertArrayEquals( [
			'wikimedia/relpath: 2.9.9 installed, 3.0.0 required.',
		], $errors );
	}

	public function testNotInstalled() {
		$json = new ComposerJson( self::FIXTURE_DIRECTORY . '/composer-testcase3.json' );
		$lock = new ComposerLock( self::FIXTURE_DIRECTORY . '/composer-testcase3.lock' );
		$checker = new LockFileChecker( $json, $lock );
		$errors = $checker->check();
		$this->assertArrayEquals( [
			'wikimedia/relpath: 2.9.9 installed, 3.0.0 required.',
			'wikimedia/at-ease: not installed, 2.1.0 required.',
		], $errors );
	}
}
