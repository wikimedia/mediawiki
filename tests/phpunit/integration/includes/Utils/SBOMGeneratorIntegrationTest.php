<?php

namespace MediaWiki\Tests\Integration\Utils;

use MediaWiki\Context\RequestContext;
use MediaWiki\Utils\SBOMGenerator;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Utils\SBOMGenerator
 * @group Database
 */
class SBOMGeneratorIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return SBOMGenerator
	 */
	private function getSBOMGenerator() {
		return TestingAccessWrapper::newFromObject(
			$this->getServiceContainer()->getService( 'SBOMGenerator' )
		);
	}

	public function testGetMediaWikiComponent() {
		$generator = $this->getSBOMGenerator();

		$component = $generator->getMediaWikiComponent()[0];
		$this->assertEquals( 'application', $component['type'] );
		$this->assertEquals( 'MediaWiki', $component['name'] );
		$this->assertIsArray( $component['components'] );
		$this->assertIsString( $component['version'] );
	}

	public function testGenerateCdxSBOM() {
		$generator = $this->getSBOMGenerator();

		$sbom = $generator->generateCdxSBOM( RequestContext::getMain() );
		$this->assertEquals( 'http://cyclonedx.org/schema/bom-1.6.schema.json', $sbom['$schema'] );
		$this->assertEquals( 'CycloneDX', $sbom['bomFormat'] );
		$this->assertSame( '1.6', $sbom['specVersion'] );
		$this->assertIsString( $sbom['serialNumber'] );
		$this->assertSame( 1, $sbom['version'] );
		$this->assertIsString( $sbom['metadata']['timestamp'] );
		$this->assertIsArray( $sbom['components'] );
	}

}
