<?php

namespace MediaWiki\Tests\Import;

use FactoryArgTestTrait;
use MediaWikiUnitTestCase;
use WikiImporter;
use WikiImporterFactory;

/**
 * @covers WikiImporterFactory
 */
class WikiImporterFactoryTest extends MediaWikiUnitTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return WikiImporterFactory::class;
	}

	protected static function getInstanceClass() {
		return WikiImporter::class;
	}

	protected static function getExtraClassArgCount() {
		return 1;
	}

	protected function getFactoryMethodName() {
		return 'getWikiImporter';
	}
}
