<?php

namespace MediaWiki\Api\Validator;

use ApiBase;
use ApiMain;
use ApiMessage;
use ApiTestCase;
use ApiUsageException;
use FauxRequest;
use MediaWiki\MediaWikiServices;
use Message;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\Api\Validator\ApiParamValidator
 * @group API
 * @group medium
 */
class ApiParamValidatorTest extends ApiTestCase {

	private function getValidator( FauxRequest $request ) : array {
		$context = $this->apiContext->newTestContext( $request, $this->getTestUser()->getUser() );
		$main = new ApiMain( $context );
		return [
			new ApiParamValidator( $main, MediaWikiServices::getInstance()->getObjectFactory() ),
			$main
		];
	}

	public function testKnwonTypes() : void {
		[ $validator ] = $this->getValidator( new FauxRequest( [] ) );
		$this->assertSame(
			[
				'boolean', 'enum', 'integer', 'limit', 'namespace', 'NULL', 'password', 'string', 'submodule',
				'tags', 'text', 'timestamp', 'user', 'upload',
			],
			$validator->knownTypes()
		);
	}

	/**
	 * @dataProvider provideNormalizeSettings
	 * @param array|mixed $settings
	 * @param array $expect
	 */
	public function testNormalizeSettings( $settings, array $expect ) : void {
		[ $validator ] = $this->getValidator( new FauxRequest( [] ) );
		$this->assertEquals( $expect, $validator->normalizeSettings( $settings ) );
	}

	public function provideNormalizeSettings() : array {
		return [
			'Basic test' => [
				[],
				[
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
					IntegerDef::PARAM_IGNORE_RANGE => true,
					ParamValidator::PARAM_TYPE => 'NULL',
				],
			],
			'Explicit ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES' => [
				[
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => false,
				],
				[
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => false,
					IntegerDef::PARAM_IGNORE_RANGE => true,
					ParamValidator::PARAM_TYPE => 'NULL',
				],
			],
			'Explicit IntegerDef::PARAM_IGNORE_RANGE' => [
				[
					IntegerDef::PARAM_IGNORE_RANGE => false,
				],
				[
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
					IntegerDef::PARAM_IGNORE_RANGE => false,
					ParamValidator::PARAM_TYPE => 'NULL',
				],
			],
			'Handle ApiBase::PARAM_RANGE_ENFORCE' => [
				[
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				[
					ApiBase::PARAM_RANGE_ENFORCE => true,
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
					IntegerDef::PARAM_IGNORE_RANGE => false,
					ParamValidator::PARAM_TYPE => 'NULL',
				],
			],
			'Handle EnumDef::PARAM_DEPRECATED_VALUES, null' => [
				[
					EnumDef::PARAM_DEPRECATED_VALUES => [
						'null' => null,
						'true' => true,
						'string' => 'some-message',
						'array' => [ 'some-message', 'with', 'params' ],
						'Message' => ApiMessage::create(
							[ 'api-message', 'with', 'params' ], 'somecode', [ 'some-data' ]
						),
						'MessageValue' => MessageValue::new( 'message-value', [ 'with', 'params' ] ),
						'DataMessageValue' => DataMessageValue::new(
							'data-message-value', [ 'with', 'params' ], 'somecode', [ 'some-data' ]
						),
					],
				],
				[
					ParamValidator::PARAM_IGNORE_UNRECOGNIZED_VALUES => true,
					IntegerDef::PARAM_IGNORE_RANGE => true,
					EnumDef::PARAM_DEPRECATED_VALUES => [
						'null' => null,
						'true' => true,
						'string' => DataMessageValue::new( 'some-message', [], 'bogus', [ '💩' => 'back-compat' ] ),
						'array' => DataMessageValue::new(
							'some-message', [ 'with', 'params' ], 'bogus', [ '💩' => 'back-compat' ]
						),
						'Message' => DataMessageValue::new(
							'api-message', [ 'with', 'params' ], 'bogus', [ '💩' => 'back-compat' ]
						),
						'MessageValue' => MessageValue::new( 'message-value', [ 'with', 'params' ] ),
						'DataMessageValue' => DataMessageValue::new(
							'data-message-value', [ 'with', 'params' ], 'somecode', [ 'some-data' ]
						),
					],
					ParamValidator::PARAM_TYPE => 'NULL',
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetValue
	 * @param string|null $data Request value
	 * @param mixed $settings Settings
	 * @param mixed $expect Expected value, or an expected ApiUsageException
	 */
	public function testGetValue( ?string $data, $settings, $expect ) : void {
		[ $validator, $main ] = $this->getValidator( new FauxRequest( [ 'aptest' => $data ] ) );
		$module = $main->getModuleFromPath( 'query+allpages' );

		if ( $expect instanceof ApiUsageException ) {
			try {
				$validator->getValue( $module, 'test', $settings, [] );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ApiUsageException $e ) {
				$this->assertSame( $module->getModulePath(), $e->getModulePath() );
				$this->assertEquals( $expect->getStatusValue(), $e->getStatusValue() );
			}
		} else {
			$this->assertEquals( $expect, $validator->getValue( $module, 'test', $settings, [] ) );
		}
	}

	public function provideGetValue() : array {
		return [
			'Basic test' => [
				'1234',
				[
					ParamValidator::PARAM_TYPE => 'integer',
				],
				1234
			],
			'Test for default' => [
				null,
				1234,
				1234
			],
			'Test no value' => [
				null,
				[
					ParamValidator::PARAM_TYPE => 'integer',
				],
				null,
			],
			'Test boolean (false)' => [
				null,
				false,
				null,
			],
			'Test boolean (true)' => [
				'',
				false,
				true,
			],
			'Validation failure' => [
				'xyz',
				[
					ParamValidator::PARAM_TYPE => 'integer',
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badinteger',
					Message::plaintextParam( 'aptest' ),
					Message::plaintextParam( 'xyz' ),
				], 'badinteger' ),
			],
		];
	}

	/**
	 * @dataProvider provideValidateValue
	 * @param mixed $value Value to validate
	 * @param mixed $settings Settings
	 * @param mixed $value Value to validate
	 * @param mixed $expect Expected value, or an expected ApiUsageException
	 */
	public function testValidateValue( $value, $settings, $expect ) : void {
		[ $validator, $main ] = $this->getValidator( new FauxRequest() );
		$module = $main->getModuleFromPath( 'query+allpages' );

		if ( $expect instanceof ApiUsageException ) {
			try {
				$validator->validateValue( $module, 'test', $value, $settings, [] );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ApiUsageException $e ) {
				$this->assertSame( $module->getModulePath(), $e->getModulePath() );
				$this->assertEquals( $expect->getStatusValue(), $e->getStatusValue() );
			}
		} else {
			$this->assertEquals(
				$expect,
				$validator->validateValue( $module, 'test', $value, $settings, [] )
			);
		}
	}

	public function provideValidateValue() : array {
		return [
			'Basic test' => [
				1234,
				[
					ParamValidator::PARAM_TYPE => 'integer',
				],
				1234
			],
			'Validation failure' => [
				1234,
				[
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_IGNORE_RANGE => false,
					IntegerDef::PARAM_MAX => 10,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-outofrange-max',
					Message::plaintextParam( 'aptest' ),
					Message::plaintextParam( 1234 ),
					Message::numParam( '' ),
					Message::numParam( 10 ),
				], 'outofrange', [ 'min' => null, 'curmax' => 10, 'max' => 10, 'highmax' => 10 ] ),
			],
		];
	}

	public function testGetParamInfo() {
		[ $validator, $main ] = $this->getValidator( new FauxRequest() );
		$module = $main->getModuleFromPath( 'query+allpages' );
		$dummy = (object)[];

		$settings = [
			'foo' => (object)[],
		];
		$options = [
			'bar' => (object)[],
		];

		$mock = $this->getMockBuilder( ParamValidator::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getParamInfo' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'getParamInfo' )
		   ->with(
				$this->identicalTo( 'aptest' ),
				$this->identicalTo( $settings ),
				$this->identicalTo( $options + [ 'module' => $module ] )
		   )
		   ->willReturn( [ $dummy ] );

		TestingAccessWrapper::newFromObject( $validator )->paramValidator = $mock;
		$this->assertSame( [ $dummy ], $validator->getParamInfo( $module, 'test', $settings, $options ) );
	}

	public function testGetHelpInfo() {
		[ $validator, $main ] = $this->getValidator( new FauxRequest() );
		$module = $main->getModuleFromPath( 'query+allpages' );

		$settings = [
			'foo' => (object)[],
		];
		$options = [
			'bar' => (object)[],
		];

		$mock = $this->getMockBuilder( ParamValidator::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getHelpInfo' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'getHelpInfo' )
		   ->with(
				$this->identicalTo( 'aptest' ),
				$this->identicalTo( $settings ),
				$this->identicalTo( $options + [ 'module' => $module ] )
		   )
		   ->willReturn( [
			   'mv1' => MessageValue::new( 'parentheses', [ 'foobar' ] ),
			   'mv2' => MessageValue::new( 'paramvalidator-help-continue' ),
		   ] );

		TestingAccessWrapper::newFromObject( $validator )->paramValidator = $mock;
		$ret = $validator->getHelpInfo( $module, 'test', $settings, $options );
		$this->assertArrayHasKey( 'mv1', $ret );
		$this->assertInstanceOf( Message::class, $ret['mv1'] );
		$this->assertEquals( '(parentheses: foobar)', $ret['mv1']->inLanguage( 'qqx' )->plain() );
		$this->assertArrayHasKey( 'mv2', $ret );
		$this->assertInstanceOf( Message::class, $ret['mv2'] );
		$this->assertEquals(
			[ 'api-help-param-continue', 'paramvalidator-help-continue' ],
			$ret['mv2']->getKeysToTry()
		);
		$this->assertCount( 2, $ret );
	}
}
