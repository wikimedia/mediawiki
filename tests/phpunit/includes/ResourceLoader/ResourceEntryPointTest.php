<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\ResourceEntryPoint;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Tests\MockEnvironment;
use Psr\Log\NullLogger;
use Wikimedia\DependencyStore\KeyValueDependencyStore;

/**
 * @covers \MediaWiki\ResourceLoader\ResourceLoader
 * @group Database
 */
class ResourceEntryPointTest extends \MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowExceptionDetails, true );
		$this->setService( 'ResourceLoader', [ $this, 'newResourceLoader' ] );
	}

	public function newResourceLoader() {
		$rl = new ResourceLoader(
			$this->getServiceContainer()->getMainConfig(),
			new NullLogger(),
			new KeyValueDependencyStore( new \HashBagOStuff() ),
			[
				'loadScript' => '/w/load.php',
				'maxageVersioned' => null,
				'maxageUnversioned' => null,
			]
		);

		$rl->register( include MW_INSTALL_PATH . '/resources/Resources.php' );
		return $rl;
	}

	/**
	 * @param MockEnvironment $env
	 *
	 * @return ResourceEntryPoint
	 */
	private function getEntryPoint( MockEnvironment $env ): ResourceEntryPoint {
		$entryPoint = new ResourceEntryPoint(
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);

		return $entryPoint;
	}

	public function testResource() {
		$uri = '/w/load.php';

		$request = new FauxRequest( [ 'modules' => 'site' ] );
		$request->setRequestURL( $uri );

		$env = new MockEnvironment( $request );
		$entryPoint = $this->getEntryPoint( $env );

		$entryPoint->enableOutputCapture();
		$entryPoint->run();

		// NOTE: MediaWikiEntryPoint::doPostOutputShutdown flushes all output buffers
		$content = $entryPoint->getCapturedOutput();

		$this->assertStringContainsString( 'mw.loader.impl', $content );
		$this->assertStringContainsString( 'site@', $content );

		// TODO: Assert headers. We'll have to make ResourceLoader use WebResponse
		//       instead of calling the global header() function.
	}

}
