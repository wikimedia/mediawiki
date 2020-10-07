<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\FileBackend\FSFile\TempFSFileFactory;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use Wikimedia\ObjectFactory;

/**
 * @coversDefaultClass FileBackendGroup
 */
class FileBackendGroupTest extends MediaWikiUnitTestCase {
	use FileBackendGroupTestTrait;

	protected function setUp() : void {
		parent::setUp();
		// This config var is not yet dependency-injected.
		// FileBackendGroup has a default option 'profiler', that holds a closure
		// that calls Profiler::instance(), which doesn't use service wiring yet.
		// Without this, the variable would be undefined there.
		// TODO: Remove this once Profiler uses service wiring and is injected
		// into FileBackendGroup.
		$GLOBALS['wgProfiler'] = [];
	}

	private static function getWikiID() {
		return 'mywiki';
	}

	private function getNoOpMock( $class ) {
		$mock = $this->createMock( $class );
		$mock->expects( $this->never() )->method( $this->anything() );
		return $mock;
	}

	private function getLocalServerCache() : BagOStuff {
		if ( !$this->srvCache ) {
			$this->srvCache = new EmptyBagOStuff;
		}
		return $this->srvCache;
	}

	private function getWANObjectCache() : WANObjectCache {
		if ( !$this->wanCache ) {
			$this->wanCache = $this->getNoOpMock( WANObjectCache::class );
		}
		return $this->wanCache;
	}

	/**
	 * @param string $domain Expected argument that LockManagerGroupFactory::getLockManagerGroup
	 *   will receive
	 */
	private function getLockManagerGroupFactory( $domain = 'mywiki' ) : LockManagerGroupFactory {
		if ( !$this->lmgFactory ) {
			$mockLmg = $this->createMock( LockManagerGroup::class );
			$mockLmg->method( 'get' )->with( 'fsLockManager' )->willReturn( 'string lock manager' );
			$mockLmg->expects( $this->never() )->method( $this->anythingBut( 'get' ) );

			$this->lmgFactory = $this->createMock( LockManagerGroupFactory::class );
			$this->lmgFactory->method( 'getLockManagerGroup' )->with( $domain )
				->willReturn( $mockLmg );
			$this->lmgFactory->expects( $this->never() )
				->method( $this->anythingBut( 'getLockManagerGroup' ) );
		}
		return $this->lmgFactory;
	}

	private function getTempFSFileFactory() : TempFSFileFactory {
		if ( !$this->tmpFileFactory ) {
			$this->tmpFileFactory = $this->getNoOpMock( TempFSFileFactory::class );
		}
		return $this->tmpFileFactory;
	}

	/**
	 * @param array $options Dictionary to use as a source for ServiceOptions before defaults, plus
	 *   the following options are available to override other arguments:
	 *     * 'configuredROMode'
	 *     * 'lmgFactory'
	 *     * 'mimeAnalyzer'
	 *     * 'tmpFileFactory'
	 */
	private function newObj( array $options = [] ) : FileBackendGroup {
		return new FileBackendGroup(
			new ServiceOptions(
				FileBackendGroup::CONSTRUCTOR_OPTIONS, $options, self::getDefaultOptions() ),
			$options['configuredROMode'] ?? new ConfiguredReadOnlyMode( false ),
			$this->getLocalServerCache(),
			$this->getWANObjectCache(),
			$options['mimeAnalyzer'] ?? $this->getNoOpMock( MimeAnalyzer::class ),
			$options['lmgFactory'] ?? $this->getLockManagerGroupFactory(),
			$options['tmpFileFactory'] ?? $this->getTempFSFileFactory(),
			new ObjectFactory( $this->getNoOpMock( Psr\Container\ContainerInterface::class ) )
		);
	}

}
