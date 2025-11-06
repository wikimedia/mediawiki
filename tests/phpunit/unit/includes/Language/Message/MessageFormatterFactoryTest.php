<?php

namespace MediaWiki\Tests\Unit\Message;

use MediaWiki\Message\Message;
use MediaWiki\Message\MessageFormatterFactory;
use MediaWikiCoversValidator;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Message\MessageFormatterFactory
 */
class MessageFormatterFactoryTest extends MediaWikiUnitTestCase {
	use MediaWikiCoversValidator;

	public static function provideGetTextFormatter() {
		yield [ 'en', null ];
		yield [ 'en', Message::FORMAT_TEXT ];
		yield [ 'ru', Message::FORMAT_PLAIN ];
	}

	/**
	 * @covers \MediaWiki\Message\MessageFormatterFactory::getTextFormatter
	 * @dataProvider provideGetTextFormatter
	 * @param string $lang
	 * @param string|null $format
	 */
	public function testGetTextFormatter( string $lang, ?string $format = null ) {
		if ( $format ) {
			$factory = new MessageFormatterFactory( $format );
		} else {
			$factory = new MessageFormatterFactory();
		}
		$formatter = $factory->getTextFormatter( $lang );
		$this->assertSame( $lang, $formatter->getLangCode() );
	}
}
