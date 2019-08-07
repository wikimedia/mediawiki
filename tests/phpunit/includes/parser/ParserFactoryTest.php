<?php

/**
 * @covers ParserFactory
 */
class ParserFactoryTest extends MediaWikiIntegrationTestCase {
	use FactoryArgTestTrait;

	protected static function getFactoryClass() {
		return ParserFactory::class;
	}

	protected static function getInstanceClass() {
		return Parser::class;
	}

	protected static function getFactoryMethodName() {
		return 'create';
	}

	protected static function getExtraClassArgCount() {
		// The parser factory itself is passed to the parser
		return 1;
	}

	protected function getOverriddenMockValueForParam( ReflectionParameter $param ) {
		if ( $param->getPosition() === 0 ) {
			return [ $this->createMock( MediaWiki\Config\ServiceOptions::class ) ];
		}
		return [];
	}
}
