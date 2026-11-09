<?php

namespace MediaWiki\Tests\Unit\Message;

use MediaWiki\Message\Message;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWikiCoversValidator;
use MediaWikiUnitTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Message\MessageFormatterFactory
 */
class MessageFormatterFactoryTest extends MediaWikiUnitTestCase {
	use MediaWikiCoversValidator;

	public static function provideGetTextFormatter() {
		yield [ 'en', null, null ];
		yield [ 'en', Message::FORMAT_TEXT, false ];
		yield [ 'ru', Message::FORMAT_PLAIN, true ];
	}

	/**
	 * @covers \MediaWiki\Message\MessageFormatterFactory::getTextFormatter
	 * @dataProvider provideGetTextFormatter
	 * @param string $lang
	 * @param string|null $format
	 */
	public function testGetTextFormatter( string $lang, ?string $format, ?bool $useDatabase ) {
		if ( $format ) {
			$factory = new MessageFormatterFactory( $format );
		} else {
			$factory = new MessageFormatterFactory();
		}

		if ( $useDatabase !== null ) {
			$formatter = $factory->getTextFormatter( $lang, $useDatabase );
		} else {
			$formatter = $factory->getTextFormatter( $lang );
		}

		$this->assertSame( $lang, $formatter->getLangCode() );

		// SMELL: This is currently testing implementation rather
		// than contract.
		// Would be nice if we could have the formatter produce a mock
		// message that we could use to check if all parameters get looped
		// through properly.
		$access = TestingAccessWrapper::newFromObject( $formatter );
		$this->assertSame( $useDatabase ?? true, $access->useDatabase, 'useDatabase' );
		$this->assertSame( $format ?? Message::FORMAT_TEXT, $access->format, 'format' );
	}
}
