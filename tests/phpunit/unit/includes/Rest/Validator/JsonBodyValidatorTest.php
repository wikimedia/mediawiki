<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Rest\Validator\Validator;
use MediaWikiUnitTestCase;
use Wikimedia\Message\ListParam;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers \MediaWiki\Rest\Validator\JsonBodyValidator
 */
class JsonBodyValidatorTest extends MediaWikiUnitTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->filterDeprecated( '/JsonBodyValidator/' );
	}

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
	public function testValidateBody( array $settings, RequestData $requestData, array $expected ) {
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

	public function testOpenAPISpec() {
		$settings = [
			'first' => [
				ParamValidator::PARAM_TYPE => 'string',
				Validator::PARAM_SOURCE => 'path',
			],
			'second' => [
				ParamValidator::PARAM_TYPE => 'float',
				Validator::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'third' => [
				ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c' ],
				Validator::PARAM_SOURCE => 'body',
				Validator::PARAM_DESCRIPTION => 'just a test',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'fourth' => [
				ParamValidator::PARAM_TYPE => 'timestamp',
			],
		];
		$expected = [
			'properties' => [
				'first' => [
					'type' => 'string',
					'description' => 'first parameter',
				],
				'second' => [
					'type' => 'number',
					'format' => 'float',
					'description' => 'second parameter',
				],
				'third' => [
					'type' => 'string',
					'enum' => [ 'a', 'b', 'c' ],
					'description' => 'just a test',
				],
				'fourth' => [
					'type' => 'string',
					'format' => 'mw-timestamp',
					'description' => 'fourth parameter',
				],
			],
			'required' => [
				'first', 'third'
			]
		];

		$validator = new JsonBodyValidator( $settings );
		$spec = $validator->getOpenAPISpec();
		$this->assertArrayEquals( $expected, $spec, false, true );
	}

	public function testOpenAPISpec_empty() {
		$validator = new JsonBodyValidator( [] );
		$this->assertSame( [], $validator->getOpenAPISpec() );
	}

}
