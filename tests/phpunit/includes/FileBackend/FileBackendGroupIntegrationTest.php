<?php

use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\ObjectCache\EmptyBagOStuff;

/**
 * @covers \MediaWiki\FileBackend\FileBackendGroup
 */
class FileBackendGroupIntegrationTest extends MediaWikiIntegrationTestCase {
	use FileBackendGroupTestTrait;
	use DummyServicesTrait;

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
			'readOnlyMode' => 'ReadOnlyMode',
			'srvCache' => 'LocalServerObjectCache',
			'mimeAnalyzer' => 'MimeAnalyzer',
			'lmgFactory' => 'LockManagerGroupFactory',
			'tmpFileFactory' => 'TempFSFileFactory',
		];

		foreach ( $serviceMembers as $key => $name ) {
			if ( isset( $options[$key] ) ) {
				if ( $key === 'readOnlyMode' ) {
					$this->setService( $name, $this->getDummyReadOnlyMode( $options[$key] ) );
				} else {
					$this->setService( $name, $options[$key] );
				}

			}
		}

		$this->assertSame( [],
			array_diff( array_keys( $options ), $globals, array_keys( $serviceMembers ) ) );

		$services = $this->getServiceContainer();

		$obj = $services->getFileBackendGroup();

		foreach ( $serviceMembers as $key => $name ) {
			if ( $key === 'readOnlyMode' || $key === 'mimeAnalyzer' ) {
				continue;
			}
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
