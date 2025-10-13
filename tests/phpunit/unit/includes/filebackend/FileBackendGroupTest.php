<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\FileBackend\FileBackendGroup;
use MediaWiki\FileBackend\LockManager\LockManagerGroupFactory;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\Mime\MimeAnalyzer;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @covers \MediaWiki\FileBackend\FileBackendGroup
 */
class FileBackendGroupTest extends MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use FileBackendGroupTestTrait;

	protected function setUp(): void {
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

	private function getLocalServerCache(): BagOStuff {
		if ( !$this->srvCache ) {
			$this->srvCache = new EmptyBagOStuff;
		}
		return $this->srvCache;
	}

	/**
	 * @param string $domain Expected argument that LockManagerGroupFactory::getLockManagerGroup
	 *   will receive
	 * @return LockManagerGroupFactory
	 */
	private function getLockManagerGroupFactory( $domain = 'mywiki' ): LockManagerGroupFactory {
		if ( !$this->lmgFactory ) {
			$mockLmg = $this->createNoOpMock( LockManagerGroup::class, [ 'get' ] );
			$mockLmg->method( 'get' )->with( 'fsLockManager' )->willReturn( 'string lock manager' );

			$this->lmgFactory = $this->createNoOpMock( LockManagerGroupFactory::class,
				[ 'getLockManagerGroup' ] );
			$this->lmgFactory->method( 'getLockManagerGroup' )->with( $domain )
				->willReturn( $mockLmg );
		}
		return $this->lmgFactory;
	}

	private function getTempFSFileFactory(): TempFSFileFactory {
		if ( !$this->tmpFileFactory ) {
			$this->tmpFileFactory = $this->createNoOpMock( TempFSFileFactory::class );
		}
		return $this->tmpFileFactory;
	}

	/**
	 * @param array $options Dictionary to use as a source for ServiceOptions before defaults, plus
	 *   the following options are available to override other arguments:
	 *     * 'readOnlyMode'
	 *     * 'lmgFactory'
	 *     * 'mimeAnalyzer'
	 *     * 'tmpFileFactory'
	 * @return FileBackendGroup
	 */
	private function newObj( array $options = [] ): FileBackendGroup {
		return new FileBackendGroup(
			new ServiceOptions(
				FileBackendGroup::CONSTRUCTOR_OPTIONS, $options, self::getDefaultOptions() ),
			$this->getDummyReadOnlyMode( $options['readOnlyMode'] ?? false ),
			$this->getLocalServerCache(),
			WANObjectCache::newEmpty(),
			$options['mimeAnalyzer'] ?? $this->createNoOpMock( MimeAnalyzer::class ),
			$options['lmgFactory'] ?? $this->getLockManagerGroupFactory(),
			$options['tmpFileFactory'] ?? $this->getTempFSFileFactory(),
			$this->getDummyObjectFactory()
		);
	}

}
