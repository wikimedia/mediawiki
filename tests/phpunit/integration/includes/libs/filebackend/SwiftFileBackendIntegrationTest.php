<?php

use MediaWiki\MainConfigNames;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\SwiftFileBackend;

/**
 * @group FileBackend
 * @covers \Wikimedia\FileBackend\SwiftFileBackend
 */
class SwiftFileBackendIntegrationTest extends FileBackendIntegrationTestBase {
	/** @var SwiftFileBackend|null */
	private static $backendToUse;

	private function getBackendConfig(): ?array {
		$backends = $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::FileBackends );
		foreach ( $backends as $conf ) {
			if ( $conf['class'] === SwiftFileBackend::class ) {
				return $conf;
			}
		}
		return null;
	}

	protected function getBackend() {
		if ( !self::$backendToUse ) {
			$conf = $this->getBackendConfig();
			if ( $conf === null ) {
				$this->markTestSkipped( 'Configure a Swift file backend in $wgFileBackends to enable this test' );
			}
			$conf['name'] = 'localtesting'; // swap name
			$conf['shardViaHashLevels'] = [ // test sharding
				'unittest-cont1' => [ 'levels' => 1, 'base' => 16, 'repeat' => 1 ]
			];
			$lockManagerGroup = $this->getServiceContainer()
				->getLockManagerGroupFactory()->getLockManagerGroup();
			$conf['lockManager'] = $lockManagerGroup->get( $conf['lockManager'] );
			$conf['domainId'] = WikiMap::getCurrentWikiId();
			self::$backendToUse = new SwiftFileBackend( $conf );
		}
		return self::$backendToUse;
	}
}
