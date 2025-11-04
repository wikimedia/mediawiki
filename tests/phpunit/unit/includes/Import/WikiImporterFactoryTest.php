<?php

namespace MediaWiki\Tests\Import;

use FactoryArgTestTrait;
use ImportSource;
use MediaWikiUnitTestCase;
use ReflectionParameter;
use WikiImporter;
use WikiImporterFactory;

/**
 * @covers \WikiImporterFactory
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
		return 2;
	}

	protected function getFactoryMethodName() {
		return 'getWikiImporter';
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getType()->getName() !== ImportSource::class ) {
			return [];
		}

		$importSource = $this->createMock( ImportSource::class );
		$importSource->method( 'atEnd' )->willReturn( true );
		return [ $importSource ];
	}
}
