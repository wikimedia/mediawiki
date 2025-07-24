<?php

namespace Wikimedia\Tests\Message;

use MediaWiki\Json\JsonCodec;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\ParamType;
use Wikimedia\Message\ScalarParam;

/**
 * @covers \Wikimedia\Message\MessageValue
 */
class MessageValueTest extends MediaWikiUnitTestCase {
	use MessageSerializationTestTrait;

	public static function getClassToTest(): string {
		return MessageValue::class;
	}

	public static function provideConstruct() {
		return [
			'empty' => [
				[ 'key', [] ],
				'<message key="key"></message>',
			],
			'withText' => [
				[ 'key', [ 'a' ] ],
				'<message key="key"><text>a</text></message>'
			],
			'withScalarParam' => [
				[ 'key', [ new ScalarParam( ParamType::BITRATE, 100 ) ] ],
				'<message key="key"><bitrate>100</bitrate></message>'
			],
		];
	}

	/** @dataProvider provideConstruct */
	public function testSerialize( $args, $_ ) {
		[ $key, $input ] = $args;
		$codec = new JsonCodec;
		$obj = new MessageValue( $key, $input );

		$serialized = $codec->serialize( $obj );
		$newObj = $codec->deserialize( $serialized );

		$this->assertTrue( $obj->isSameAs( $newObj ), $newObj->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testConstruct( $args, $expected ) {
		[ $key, $input ] = $args;
		$mv = new MessageValue( $key, $input );
		$this->assertSame( $expected, $mv->dump() );
	}

	/** @dataProvider provideConstruct */
	public function testNew( $args, $expected ) {
		[ $key, $input ] = $args;
		$mv = MessageValue::new( $key, $input );
		$this->assertSame( $expected, $mv->dump() );
	}

	public function testGetKey() {
		$mv = new MessageValue( 'key' );
		$this->assertSame( 'key', $mv->getKey() );
	}

	public function testGetParams() {
		$mv = new MessageValue( 'key', [ 'a', 'b' ] );
		$this->assertEquals( [
			new ScalarParam( ParamType::TEXT, 'a' ),
			new ScalarParam( ParamType::TEXT, 'b' ),
		], $mv->getParams() );
	}

	public function testParams() {
		$mv = new MessageValue( 'key' );
		$mv->params( 1, 'x' );
		$mv2 = $mv->params( new ScalarParam( ParamType::BITRATE, 100 ) );
		$this->assertSame(
			'<message key="key"><text>1</text><text>x</text><bitrate>100</bitrate></message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testTextParamsOfType() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->textParamsOfType( ParamType::BITRATE, 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<bitrate>1</bitrate><bitrate>2</bitrate>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testListParamsOfType() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->listParamsOfType( ListType::COMMA, [ 'a' ], [ 'b', 'c' ] );
		$this->assertSame( '<message key="key">' .
			'<list listType="comma"><text>a</text></list>' .
			'<list listType="comma"><text>b</text><text>c</text></list>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testTextParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->textParams( 'a', 'b', new MessageValue( 'key2' ) );
		$this->assertSame( '<message key="key">' .
			'<text>a</text>' .
			'<text>b</text>' .
			'<text><message key="key2"></message></text>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testNumParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->numParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<num>1</num>' .
			'<num>2</num>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testLongDurationParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->longDurationParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<duration>1</duration>' .
			'<duration>2</duration>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testShortDurationParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->shortDurationParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<period>1</period>' .
			'<period>2</period>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testExpiryParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->expiryParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<expiry>1</expiry>' .
			'<expiry>2</expiry>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testDateTimeParams() {
		$mv = new MessageValue( 'key' );
		$ts = '1617158201';
		$mv2 = $mv->dateTimeParams( $ts );
		$this->assertSame( '<message key="key">' .
			"<datetime>$ts</datetime>" .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testDateParams() {
		$mv = new MessageValue( 'key' );
		$ts = '1617158201';
		$mv2 = $mv->dateParams( $ts );
		$this->assertSame( '<message key="key">' .
			"<date>$ts</date>" .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testTimeParams() {
		$mv = new MessageValue( 'key' );
		$ts = '1617158201';
		$mv2 = $mv->timeParams( $ts );
		$this->assertSame( '<message key="key">' .
			"<time>$ts</time>" .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testUserGroupParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->userGroupParams( 'bot' );
		$this->assertSame( '<message key="key">' .
			"<group>bot</group>" .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testSizeParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->sizeParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<size>1</size>' .
			'<size>2</size>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testBitrateParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->bitrateParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<bitrate>1</bitrate>' .
			'<bitrate>2</bitrate>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testRawParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->rawParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<raw>1</raw>' .
			'<raw>2</raw>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testPlaintextParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->plaintextParams( 1, 2 );
		$this->assertSame( '<message key="key">' .
			'<plaintext>1</plaintext>' .
			'<plaintext>2</plaintext>' .
			'</message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testCommaListParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->commaListParams( [ 'a', 'b' ] );
		$this->assertSame( '<message key="key">' .
			'<list listType="comma">' .
			'<text>a</text><text>b</text>' .
			'</list></message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testSemicolonListParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->semicolonListParams( [ 'a', 'b' ] );
		$this->assertSame( '<message key="key">' .
			'<list listType="semicolon">' .
			'<text>a</text><text>b</text>' .
			'</list></message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testPipeListParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->pipeListParams( [ 'a', 'b' ] );
		$this->assertSame( '<message key="key">' .
			'<list listType="pipe">' .
			'<text>a</text><text>b</text>' .
			'</list></message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}

	public function testTextListParams() {
		$mv = new MessageValue( 'key' );
		$mv2 = $mv->textListParams( [ 'a', 'b' ] );
		$this->assertSame( '<message key="key">' .
			'<list listType="text">' .
			'<text>a</text><text>b</text>' .
			'</list></message>',
			$mv->dump() );
		$this->assertSame( $mv, $mv2 );
	}
}
