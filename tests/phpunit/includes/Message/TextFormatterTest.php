<?php

namespace MediaWiki\Tests\Message;

use MediaWiki\Message\Converter;
use MediaWiki\Message\TextFormatter;
use MediaWikiIntegrationTestCase;
use Message;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \MediaWiki\Message\TextFormatter
 * @covers \Wikimedia\Message\MessageValue
 * @covers \Wikimedia\Message\ListParam
 * @covers \Wikimedia\Message\ScalarParam
 * @covers \Wikimedia\Message\MessageParam
 */
class TextFormatterTest extends MediaWikiIntegrationTestCase {
	private function createTextFormatter( $langCode,
		$includeWikitext = false,
		$format = Message::FORMAT_TEXT
	) {
		$converter = $this->getMockBuilder( Converter::class )
			->setMethods( [ 'createMessage' ] )
			->getMock();
		$converter->method( 'createMessage' )
			->willReturnCallback( function ( $key ) use ( $includeWikitext ) {
				$message = $this->getMockBuilder( Message::class )
					->setConstructorArgs( [ $key ] )
					->setMethods( [ 'fetchMessage' ] )
					->getMock();

				$message->method( 'fetchMessage' )
					->willReturnCallback( function () use ( $message, $includeWikitext ) {
						/** @var Message $message */
						$result = "{$message->getKey()} $1 $2";
						if ( $includeWikitext ) {
							$result .= " {{SITENAME}}";
						}
						return $result;
					} );

				return $message;
			} );

		return new TextFormatter( $langCode, $converter, $format );
	}

	public function testGetLangCode() {
		$formatter = new TextFormatter( 'fr', new Converter );
		$this->assertSame( 'fr', $formatter->getLangCode() );
	}

	public function testFormatBitrate() {
		$formatter = $this->createTextFormatter( 'en' );
		$mv = ( new MessageValue( 'test' ) )->bitrateParams( 100, 200 );
		$result = $formatter->format( $mv );
		$this->assertSame( 'test 100 bps 200 bps', $result );
	}

	public function testFormatShortDuration() {
		$formatter = $this->createTextFormatter( 'en' );
		$mv = ( new MessageValue( 'test' ) )->shortDurationParams( 100, 200 );
		$result = $formatter->format( $mv );
		$this->assertSame( 'test 1 min 40 s 3 min 20 s', $result );
	}

	public function testFormatList() {
		$formatter = $this->createTextFormatter( 'en' );
		$mv = ( new MessageValue( 'test' ) )->commaListParams( [
			'a',
			new ScalarParam( ParamType::BITRATE, 100 ),
		] );
		$result = $formatter->format( $mv );
		$this->assertSame( 'test a, 100 bps $2', $result );
	}

	public function testFormatMessage() {
		$formatter = $this->createTextFormatter( 'en' );
		$mv = ( new MessageValue( 'test' ) )
			->params( new MessageValue( 'test2', [ 'a', 'b' ] ) )
			->commaListParams( [
				'x',
				new ScalarParam( ParamType::BITRATE, 100 ),
				new MessageValue( 'test3', [ 'c', new MessageValue( 'test4', [ 'd', 'e' ] ) ] )
			] );
		$result = $formatter->format( $mv );
		$this->assertSame( 'test test2 a b x, 100 bps, test3 c test4 d e', $result );
	}

	public function testFormatMessageFormatsWikitext() {
		global $wgSitename;
		$formatter = $this->createTextFormatter( 'en', true );
		$mv = MessageValue::new( 'test' )
			->plaintextParams( '1', '2' );
		$this->assertSame( "test 1 2 $wgSitename", $formatter->format( $mv ) );
	}

	public function testFormatMessageNotWikitext() {
		$formatter = $this->createTextFormatter( 'en', true, Message::FORMAT_PLAIN );
		$mv = MessageValue::new( 'test' )
			->plaintextParams( '1', '2' );
		$this->assertSame( "test 1 2 {{SITENAME}}", $formatter->format( $mv ) );
	}
}
