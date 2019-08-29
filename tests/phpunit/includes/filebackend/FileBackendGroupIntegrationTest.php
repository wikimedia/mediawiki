<?php

use MediaWiki\MediaWikiServices;

/**
 * @coversDefaultClass FileBackendGroup
 * @covers ::singleton
 * @covers ::destroySingleton
 */
class FileBackendGroupIntegrationTest extends MediaWikiIntegrationTestCase {
	use FileBackendGroupTestTrait;

	private static function getWikiID() {
		return wfWikiID();
	}

	private function getLockManagerGroupFactory() {
		return MediaWikiServices::getInstance()->getLockManagerGroupFactory();
	}

	private function newObj( array $options = [] ) : FileBackendGroup {
		$globals = [ 'DirectoryMode', 'FileBackends', 'ForeignFileRepos', 'LocalFileRepo' ];
		foreach ( $globals as $global ) {
			$this->setMwGlobals(
				"wg$global", $options[$global] ?? self::getDefaultOptions()[$global] );
		}

		$serviceMembers = [
			'configuredROMode' => 'ConfiguredReadOnlyMode',
			'srvCache' => 'LocalServerObjectCache',
			'wanCache' => 'MainWANObjectCache',
			'mimeAnalyzer' => 'MimeAnalyzer',
			'lmgFactory' => 'LockManagerGroupFactory',
			'tmpFileFactory' => 'TempFSFileFactory',
		];

		foreach ( $serviceMembers as $key => $name ) {
			if ( isset( $options[$key] ) ) {
				$this->setService( $name, $options[$key] );
			}
		}

		$this->assertEmpty(
			array_diff( array_keys( $options ), $globals, array_keys( $serviceMembers ) ) );

		$this->resetServices();
		FileBackendGroup::destroySingleton();

		$services = MediaWikiServices::getInstance();

		foreach ( $serviceMembers as $key => $name ) {
			if ( $key === 'srvCache' ) {
				$this->$key = ObjectCache::getLocalServerInstance( 'hash' );
			} else {
				$this->$key = $services->getService( $name );
			}
		}

		return FileBackendGroup::singleton();
	}
}
