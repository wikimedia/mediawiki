<?php
/**
 * @license GPL-2.0-or-later
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
