<?php

namespace MediaWiki\Tests\Export;

use FactoryArgTestTrait;
use MediaWiki\Export\WikiExporterFactory;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use WikiExporter;
use XmlDumpWriter;

/**
 * @covers \MediaWiki\Export\WikiExporterFactory
 */
class WikiExporterFactoryTest extends MediaWikiIntegrationTestCase {
	use FactoryArgTestTrait;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue(
			MainConfigNames::XmlDumpSchemaVersion,
			XmlDumpWriter::$supportedSchemas[0]
		);
	}

	protected static function getFactoryClass() {
		return WikiExporterFactory::class;
	}

	protected static function getInstanceClass() {
		return WikiExporter::class;
	}

	protected static function getExtraClassArgCount() {
		return 4;
	}

	protected function getFactoryMethodName() {
		return 'getWikiExporter';
	}

	protected function getOverriddenMockValueForParam( $param ) {
		if ( $param->getName() === 'text' ) {
			return [ WikiExporter::TEXT ];
		}
		return [];
	}
}
