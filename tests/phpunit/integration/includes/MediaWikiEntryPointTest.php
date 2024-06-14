<?php
namespace MediaWiki\Tests;

use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\MediaWikiEntryPoint
 */
class MediaWikiEntryPointTest extends MediaWikiIntegrationTestCase {

	private function newEntryPoint(): MediaWikiEntryPoint {
		$context = new RequestContext();
		$context->setRequest( new FauxRequest() );
		$environment = new MockEnvironment();
		$services = $this->createMock( MediaWikiServices::class );

		$mw = $this->getMockBuilder( MediaWikiEntryPoint::class )
			->onlyMethods( [ 'execute' ] )
			->setConstructorArgs( [ $context, $environment, $services ] )
			->getMockForAbstractClass();

		return $mw;
	}

	/**
	 * Assert that we can create, flush, and access output buffers.
	 *
	 * Note that can't test the behaviour for non-removable buffers,
	 * because then we are stuck with a non-removable buffer,
	 * and PHPUnit will choke on it.
	 *
	 * We also can't test that content gets send to the client by calling
	 * flush(), since we can't mock that function, and we can't capture
	 * its output.
	 */
	public function testOutputBuffer() {
		$oldLevel = ob_get_level();

		$mw = TestingAccessWrapper::newFromObject( $this->newEntryPoint() );
		$mw->enableOutputCapture();

		$mw->startOutputBuffer();
		$this->assertSame( 1, $mw->getOutputBufferLevel(), 'getOutputBufferLevel' );

		print 'Testing test';
		$this->assertSame( 12, $mw->getOutputBufferLength(), 'getOutputBufferLength' );

		$mw->flushOutputBuffer();

		$this->assertSame( $oldLevel, ob_get_level(), 'ob_get_level' );
		$this->assertSame( 'Testing test', $mw->drainOutputBuffer() );
	}

	/**
	 * Check that flushOutputBuffer() will not try to send output
	 * when in post-send mode.
	 */
	public function testFlushOutputBuffers_sent() {
		$mw = TestingAccessWrapper::newFromObject( $this->newEntryPoint() );
		$mw->enableOutputCapture();

		ob_start( null, 0, PHP_OUTPUT_HANDLER_STDFLAGS & ~PHP_OUTPUT_HANDLER_FLUSHABLE );
		print 'Testing test';

		$mw->enterPostSendMode();

		$this->expectPHPError(
			E_USER_NOTICE,
			static function () use ( $mw ) {
				$mw->flushOutputBuffer();
			}
		);

		$this->assertSame( '', $mw->drainOutputBuffer() );
	}

}
