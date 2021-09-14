<?php

namespace Wikimedia\ParamValidator;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers Wikimedia\ParamValidator\TypeDef
 */
class TypeDefTest extends \PHPUnit\Framework\TestCase {

	public function testMisc() {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();

		$this->assertSame( [ 'foobar' ], $typeDef->normalizeSettings( [ 'foobar' ] ) );
		$ret = [ 'issues' => [], 'allowedKeys' => [], 'messages' => [] ];
		$this->assertSame( $ret, $typeDef->checkSettings( 'foobar', [], [], $ret ) );
		$this->assertNull( $typeDef->getEnumValues( 'foobar', [], [] ) );
		$this->assertSame( '123', $typeDef->stringifyValue( 'foobar', 123, [], [] ) );
	}

	public function testGetValue() {
		$options = [ (object)[] ];

		$callbacks = $this->getMockBuilder( Callbacks::class )->getMockForAbstractClass();
		$callbacks->expects( $this->once() )->method( 'getValue' )
			->with(
				$this->identicalTo( 'foobar' ),
				$this->identicalTo( null ),
				$this->identicalTo( $options )
			)
			->willReturn( 'zyx' );

		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->getMockForAbstractClass();

		$this->assertSame(
			'zyx',
			$typeDef->getValue( 'foobar', [ ParamValidator::PARAM_DEFAULT => 'foo' ], $options )
		);
	}

	public function testGetParamInfo() {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();

		$this->assertSame( [], $typeDef->getParamInfo( 'foobar', [], [] ) );
	}

	public function testGetHelpInfo() {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();

		$this->assertSame( [], $typeDef->getHelpInfo( 'foobar', [], [] ) );
	}

	/** @dataProvider provideFailureMessage */
	public function testFailureMessage( $expect, $code, array $data = null, $suffix = null ) {
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ new SimpleCallbacks( [] ) ] )
			->getMockForAbstractClass();
		$ret = TestingAccessWrapper::newFromObject( $typeDef )->failureMessage( $code, $data, $suffix );

		$this->assertInstanceOf( DataMessageValue::class, $ret );
		$this->assertSame( $expect, $ret->dump() );
	}

	public static function provideFailureMessage() {
		return [
			'Basic' => [
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'<datamessage key="paramvalidator-foobar" code="foobar"></datamessage>',
				'foobar',
			],
			'With data' => [
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'<datamessage key="paramvalidator-foobar" code="foobar"><data>{"x":123}</data></datamessage>',
				'foobar', [ 'x' => 123 ]
			],
			'With suffix' => [
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'<datamessage key="paramvalidator-foobar-baz" code="foobar"><data>[]</data></datamessage>',
				'foobar', [], 'baz'
			],
		];
	}

	/** @dataProvider provideFailure */
	public function testFailure_fatal(
		$expect, $failure, $name, $value, array $settings, array $options
	) {
		$callbacks = new SimpleCallbacks( [] );
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->getMockForAbstractClass();

		try {
			TestingAccessWrapper::newFromObject( $typeDef )
				->failure( $failure, $name, $value, $settings, $options );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ValidationException $ex ) {
			$this->assertSame( $expect, $ex->getFailureMessage()->dump() );
			$this->assertSame( $name, $ex->getParamName() );
			$this->assertSame( (string)$value, $ex->getParamValue() );
			$this->assertSame( $settings, $ex->getSettings() );
		}
		$this->assertSame( [], $callbacks->getRecordedConditions() );
	}

	/** @dataProvider provideFailure */
	public function testFailure_nonfatal(
		$expect, $failure, $name, $value, array $settings, array $options
	) {
		$callbacks = new SimpleCallbacks( [] );
		$typeDef = $this->getMockBuilder( TypeDef::class )
			->setConstructorArgs( [ $callbacks ] )
			->getMockForAbstractClass();

		TestingAccessWrapper::newFromObject( $typeDef )
			->failure( $failure, $name, $value, $settings, $options, false );

		$conds = $callbacks->getRecordedConditions();
		$this->assertCount( 1, $conds );
		$conds[0]['message'] = $conds[0]['message']->dump();
		$this->assertSame( [
			'message' => $expect,
			'name' => $name,
			'value' => (string)$value,
			'settings' => $settings,
		], $conds[0] );
	}

	public static function provideFailure() {
		return [
			'Basic' => [
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'<datamessage key="paramvalidator-foobar" code="foobar"><params><plaintext>test</plaintext><plaintext>1234</plaintext></params></datamessage>',
				'foobar', 'test', 1234, [], []
			],
			'DataMessageValue' => [
				// phpcs:ignore Generic.Files.LineLength.TooLong
				'<datamessage key="XXX-msg" code="foobar"><params><plaintext>test</plaintext><plaintext>XXX</plaintext><text>a</text><text>b</text><plaintext>pt</plaintext></params><data>{"data":"!!!"}</data></datamessage>',
				DataMessageValue::new( 'XXX-msg', [ 'a', 'b' ], 'foobar', [ 'data' => '!!!' ] )
					->plaintextParams( 'pt' ),
				'test', 'XXX', [], []
			],
		];
	}

}
