<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers \MediaWiki\Rest\Validator\JsonBodyValidator
 */
class JsonBodyValidatorTest extends \MediaWikiUnitTestCase {

	public static function provideValidateBody() {
		yield 'empty object' => [
			[],
			new RequestData( [
				'bodyContents' => json_encode( (object)[] ),
			] ),
			[]
		];

		yield 'missing optional' => [
			[
				'number' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => false,
				]
			],
			new RequestData( [
				'bodyContents' => json_encode( (object)[] ),
			] ),
			[
				'number' => null,
			]
		];

		yield 'apply default' => [
			[
				'number' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => false,
					ParamValidator::PARAM_DEFAULT => 10,
				]
			],
			new RequestData( [
				'bodyContents' => json_encode( (object)[] ),
			] ),
			[
				'number' => 10,
			]
		];
	}

	/**
	 * @dataProvider provideValidateBody
	 */
	public function testValidateBody( $settings, RequestData $requestData, $expected ) {
		$validator = new JsonBodyValidator( $settings );
		$actual = $validator->validateBody( $requestData );
		$this->assertArrayEquals( $expected, $actual, false, true );
	}

	public static function provideValidateBody_failure() {
		yield 'empty body' => [
			[],
			new RequestData( [
				'bodyContents' => '',
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-json-body-parse-error' ), 400 ),
		];

		yield 'bad syntax' => [
			[],
			new RequestData( [
				'bodyContents' => '.....',
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-json-body-parse-error' ), 400 ),
		];

		yield 'not an object' => [
			[],
			new RequestData( [
				'bodyContents' => json_encode( 'evil' ),
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-bad-json-body' ), 400 ),
		];

		yield 'missing optional' => [
			[
				'number' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => true,
				]
			],
			new RequestData( [
				'bodyContents' => json_encode( (object)[
					'kittens' => 'cute',
				] ),
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-missing-body-field' ), 400 ),
		];

		yield 'extraneous parameters' => [
			[
				'foo' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => false,
				]
			],
			new RequestData( [
				'bodyContents' => json_encode( (object)[
					'f00' => 123,
					'bar' => 456,
				] ),
			] ),
			new LocalizedHttpException(
				new MessageValue(
					'rest-extraneous-body-fields',
					[ new ListParam( ListType::COMMA, [ 'f00', 'bar' ] ) ]
				),
				400
			),
		];
	}

	/**
	 * @dataProvider provideValidateBody_failure
	 */
	public function testValidateBody_failure( $settings, RequestData $requestData, $expected ) {
		$validator = new JsonBodyValidator( $settings );

		$this->expectExceptionObject( $expected );
		$validator->validateBody( $requestData );
	}
}
