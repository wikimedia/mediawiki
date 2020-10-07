<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers \MediaWiki\Rest\Validator\JsonBodyValidator
 */
class JsonBodyValidatorTest extends \MediaWikiUnitTestCase {

	public function provideValidateBody() {
		yield 'empty object' => [
			[],
			$request = new RequestData( [
				'bodyContents' => json_encode( (object)[] ),
			] ),
			[]
		];

		yield 'extra data' => [
			[],
			$request = new RequestData( [
				'bodyContents' => json_encode( (object)[
					'kittens' => 'cute',
					'number' => 5,
				] ),
			] ),
			[
				'kittens' => 'cute',
				'number' => 5
			]
		];

		yield 'missing optional' => [
			[
				'number' => [
					ParamValidator::PARAM_TYPE => 'integer',
					ParamValidator::PARAM_REQUIRED => false,
				]
			],
			$request = new RequestData( [
				'bodyContents' => json_encode( (object)[
					'kittens' => 'cute',
				] ),
			] ),
			[
				'kittens' => 'cute',
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
			$request = new RequestData( [
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

	public function provideValidateBody_failure() {
		yield 'empty body' => [
			[],
			$request = new RequestData( [
				'bodyContents' => '',
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-json-body-parse-error' ), 400 ),
		];

		yield 'bad syntax' => [
			[],
			$request = new RequestData( [
				'bodyContents' => '.....',
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-json-body-parse-error' ), 400 ),
		];

		yield 'not an object' => [
			[],
			$request = new RequestData( [
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
			$request = new RequestData( [
				'bodyContents' => json_encode( (object)[
					'kittens' => 'cute',
				] ),
			] ),
			new LocalizedHttpException( new MessageValue( 'rest-missing-body-field' ), 400 ),
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
