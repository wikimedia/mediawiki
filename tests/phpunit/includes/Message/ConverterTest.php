<?php

namespace MediaWiki\Tests\Message;

use InvalidArgumentException;
use MediaWiki\Message\Converter;
use MediaWikiIntegrationTestCase;
use Message;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Message\Converter
 */
class ConverterTest extends MediaWikiIntegrationTestCase {

	public function testCreateMessage() {
		$converter = new Converter();
		$m = $converter->createMessage( 'foobar' );
		$this->assertInstanceOf( Message::class, $m );
		$this->assertSame( 'foobar', $m->getKey() );
		$this->assertSame( [], $m->getParams() );
	}

	/** @dataProvider provideConversions */
	public function testConvertMessage( Message $m, MessageValue $mv ) {
		$converter = new Converter();
		$this->assertEquals( $mv, $converter->convertMessage( $m ) );
	}

	/** @dataProvider provideConversions */
	public function testConvertMessageValue( Message $m, MessageValue $mv ) {
		$converter = new Converter();
		$this->assertEquals( $m, $converter->convertMessageValue( $mv ) );
	}

	public static function provideConversions() {
		yield 'Basic message, no params' => [
			new Message( 'foobar' ),
			new MessageValue( 'foobar' ),
		];

		yield 'Scalar text params' => [
			new Message( 'foobar', [ 'one', 2, 3 ] ),
			new MessageValue( 'foobar', [ 'one', 2, 3 ] ),
		];

		yield 'Message(Value) as param' => [
			new Message( 'foo', [ new Message( 'bar', [ new Message( 'baz' ) ] ) ] ),
			new MessageValue( 'foo', [ new MessageValue( 'bar', [ new MessageValue( 'baz' ) ] ) ] ),
		];

		yield 'numParams' => [
			Message::newFromKey( 'foobar' )->numParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->numParams( 1, 2, 3 ),
		];

		yield 'longDurationParams' => [
			Message::newFromKey( 'foobar' )->durationParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->longDurationParams( 1, 2, 3 ),
		];

		yield 'shortDurationParams' => [
			Message::newFromKey( 'foobar' )->timeperiodParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->shortDurationParams( 1, 2, 3 ),
		];

		yield 'expiryParams' => [
			Message::newFromKey( 'foobar' )->expiryParams( 1, 2, 'infinity' ),
			MessageValue::new( 'foobar' )->expiryParams( 1, 2, 'infinity' ),
		];

		yield 'sizeParams' => [
			Message::newFromKey( 'foobar' )->sizeParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->sizeParams( 1, 2, 3 ),
		];

		yield 'bitrateParams' => [
			Message::newFromKey( 'foobar' )->bitrateParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->bitrateParams( 1, 2, 3 ),
		];

		yield 'rawParams' => [
			Message::newFromKey( 'foobar' )->rawParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->rawParams( 1, 2, 3 ),
		];

		yield 'plaintextParams' => [
			Message::newFromKey( 'foobar' )->plaintextParams( 1, 2, 3 ),
			MessageValue::new( 'foobar' )->plaintextParams( 1, 2, 3 ),
		];

		yield 'listParams' => [
			Message::newFromKey( 'foobar',
				Message::listParam( [ 1, 2, 3 ], 'comma' ),
				Message::listParam( [ 4, 5, 6 ], 'semicolon' ),
				Message::listParam( [ 7, 8, 9 ], 'pipe' ),
				Message::listParam( [ 10, 11, 12 ], 'text' )
			),
			MessageValue::new( 'foobar' )
				->commaListParams( [ 1, 2, 3 ] )
				->semicolonListParams( [ 4, 5, 6 ] )
				->pipeListParams( [ 7, 8, 9 ] )
				->textListParams( [ 10, 11, 12 ] ),
		];
	}

	public function testConvertMessage_invalidParam() {
		$m = Message::newFromKey( 'foobar', [ 'foo' => 'bar' ] );
		$converter = new Converter();
		$this->expectException( InvalidArgumentException::class );
		$converter->convertMessage( $m );
	}

}
