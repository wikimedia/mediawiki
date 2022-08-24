<?php

use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\MainConfigNames;

/**
 * @coversDefaultClass FileBackendGroup
 */
class FileBackendGroupIntegrationTest extends MediaWikiIntegrationTestCase {
	use FileBackendGroupTestTrait;

	private static function getWikiID() {
		return WikiMap::getCurrentWikiId();
	}

	private function getLockManagerGroupFactory( $domain ): LockManagerGroupFactory {
		return $this->getServiceContainer()->getLockManagerGroupFactory();
	}

	private function newObj( array $options = [] ): FileBackendGroup {
		$globals = [
			MainConfigNames::DirectoryMode,
			MainConfigNames::FileBackends,
			MainConfigNames::ForeignFileRepos,
			MainConfigNames::LocalFileRepo,
		];
		foreach ( $globals as $global ) {
			$this->overrideConfigValue(
				$global, $options[$global] ?? self::getDefaultOptions()[$global] );
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

		$services = $this->getServiceContainer();

		$obj = $services->getFileBackendGroup();

		foreach ( $serviceMembers as $key => $name ) {
			$this->$key = $services->getService( $name );
			if ( $key === 'srvCache' && $this->$key instanceof EmptyBagOStuff ) {
				// ServiceWiring will have created its own HashBagOStuff that we don't have a
				// reference to. Set null instead.
				$this->srvCache = null;
			}
		}

		return $obj;
	}
}
