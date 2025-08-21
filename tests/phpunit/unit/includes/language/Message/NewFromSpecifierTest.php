<?php

namespace MediaWiki\Tests\Unit\Message;

use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Message\Message::newFromSpecifier
 * @covers \Wikimedia\Message\MessageValue::newFromSpecifier
 */
class NewFromSpecifierTest extends MediaWikiUnitTestCase {

	/** @dataProvider provideConversions */
	public function testConvertMessage( Message $m, MessageValue $mv ) {
		$this->assertEquals( $mv, MessageValue::newFromSpecifier( $m ) );
	}

	/** @dataProvider provideConversions */
	public function testConvertMessageValue( Message $m, MessageValue $mv ) {
		$this->assertEquals( $m, Message::newFromSpecifier( $mv ) );
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

		$u = new UserIdentityValue( 1, 'Username' );
		yield 'Stringable params' => [
			new Message( 'foobar', [ $u ] ),
			new MessageValue( 'foobar', [ $u ] ),
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
				Message::listParam( [ 1, 2, 3 ], ListType::COMMA ),
				Message::listParam( [ 4, 5, 6 ], ListType::SEMICOLON ),
				Message::listParam( [ 7, 8, 9 ], ListType::PIPE ),
				Message::listParam( [ 10, 11, 12 ], ListType::AND )
			),
			MessageValue::new( 'foobar' )
				->commaListParams( [ 1, 2, 3 ] )
				->semicolonListParams( [ 4, 5, 6 ] )
				->pipeListParams( [ 7, 8, 9 ] )
				->textListParams( [ 10, 11, 12 ] ),
		];
	}

	public static function provideConversions_RawMessage() {
		yield 'No params' => [
			new RawMessage( 'Foo Bar' ),
			new MessageValue( 'rawmessage', [ 'Foo Bar' ] ),
		];

		yield 'Single param' => [
			new RawMessage( '$1', [ 'Foo Bar' ] ),
			new MessageValue( 'rawmessage', [ 'Foo Bar' ] ),
		];

		yield 'Multiple params' => [
			new RawMessage( '$1 $2', [ 'Foo', 'Bar' ] ),
			new MessageValue( 'rawmessage', [ 'Foo Bar' ] ),
		];
	}

	/** @dataProvider provideConversions_RawMessage */
	public function testConvertMessage_RawMessage( RawMessage $m, MessageValue $mv ) {
		// Tests for unidirectional conversion from RawMessage.
		// The result doesn't roundtrip, but it at least renders the same output.
		// Avoid service container access in the multiple param case
		$ctx = $this->createMock( IContextSource::class );
		$ctx->method( 'getLanguage' )->willReturn( $this->createMock( Language::class ) );
		$m->setContext( $ctx );
		$this->assertEquals( $mv, MessageValue::newFromSpecifier( $m ) );
	}

}
