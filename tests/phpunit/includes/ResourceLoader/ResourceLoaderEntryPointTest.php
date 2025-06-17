<?php

namespace MediaWiki\Tests\ResourceLoader;

use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\ResourceLoader\ResourceLoaderEntryPoint;
use MediaWiki\Tests\MockEnvironment;
use MediaWikiIntegrationTestCase;

/**
 * @group ResourceLoader
 * @group Database
 * @covers \MediaWiki\ResourceLoader\ResourceLoader
 */
class ResourceLoaderEntryPointTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::ShowExceptionDetails, true );
		$this->setService( 'ResourceLoader', $this->newResourceLoader( ... ) );
	}

	public function newResourceLoader() {
		// See also ResourceLoaderTestCase::getResourceLoaderContext
		return new ResourceLoader(
			$this->getServiceContainer()->getMainConfig(),
			null,
			null,
			[
				'loadScript' => '/w/load.php',
			]
		);
	}

	private function getEntryPoint( FauxRequest $request ) {
		$env = new MockEnvironment( $request );
		return new ResourceLoaderEntryPoint(
			$env->makeFauxContext(),
			$env,
			$this->getServiceContainer()
		);
	}

	/**
	 * Further tested in StartUpModuleTest and ResourceLoaderTest
	 */
	public function testExecute() {
		// Simulate a real request, such as the one from RL\ClientHtml::getHeadHtml
		$request = new FauxRequest( [ 'modules' => 'startup', 'only' => 'scripts', 'raw' => '1' ] );
		$request->setRequestURL( '/w/load.php' );
		$entryPoint = $this->getEntryPoint( $request );

		$entryPoint->enableOutputCapture();
		$entryPoint->run();
		$content = $entryPoint->getCapturedOutput();

		$this->assertStringContainsString( 'function isCompatible', $content );
		$this->assertStringContainsString( 'mw.loader.addSource(', $content );

		// TODO: Assert headers once ResourceLoader doesn't call header()
		// directly (maybe a library version of WebResponse).
	}

}
