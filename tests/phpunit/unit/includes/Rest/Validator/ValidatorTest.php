<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\HttpException;
use MediaWiki\Rest\RequestData;
use MediaWiki\Rest\Validator\BodyValidator;
use MediaWiki\Rest\Validator\JsonBodyValidator;
use MediaWiki\Rest\Validator\NullBodyValidator;
use MediaWiki\Rest\Validator\Validator;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers \MediaWiki\Rest\Validator\Validator
 */
class ValidatorTest extends \MediaWikiUnitTestCase {
	use DummyServicesTrait;
	use MockAuthorityTrait;

	public function provideValidateBody() {
		$bodyData = [
			'kittens' => 'cute',
			'number' => 5,
		];

		$emptyBodyValidator = new NullBodyValidator();
		$nonEmptyBodyValidator = new JsonBodyValidator( [
			'kittens' => [
				'rest-param-source' => 'body',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'number' => [
				'rest-param-source' => 'body',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
			]
		] );

		$emptyBodyParams = [ 'bodyContents' => '' ];
		$nonEmptyBodyParams = [
			'bodyContents' => json_encode( (object)$bodyData ),
			'headers' => [
				'Content-Type' => 'application/json'
			]
		];

		// Validator::validateBody() normalizes method for case and leading/trailing whitespace.
		// Use various permutations herein to confirm that normalization is happy.
		yield 'GET request with empty body' => [
			$emptyBodyValidator,
			new RequestData( $emptyBodyParams + [ 'method' => 'GET' ] ),
			null
		];

		yield 'GET request with non-empty body' => [
			$emptyBodyValidator,
			new RequestData( $nonEmptyBodyParams + [ 'method' => 'get' ] ),
			null
		];

		yield 'HEAD request with empty body' => [
			$emptyBodyValidator,
			new RequestData( $emptyBodyParams + [ 'method' => 'HEAD' ] ),
			null
		];

		yield 'HEAD request with non-empty body' => [
			$emptyBodyValidator,
			new RequestData( $nonEmptyBodyParams + [ 'method' => 'Head' ] ),
			null

		];
		yield 'DELETE request with empty body' => [
			$emptyBodyValidator,
			new RequestData( $emptyBodyParams + [ 'method' => 'DELETE' ] ),
			null
		];

		yield 'DELETE request with non-empty body' => [
			$nonEmptyBodyValidator,
			new RequestData( $nonEmptyBodyParams + [ 'method' => 'DELETE ' ] ),
			$bodyData
		];

		yield 'POST request with empty body' => [
			$nonEmptyBodyValidator,
			new RequestData( $emptyBodyParams + [ 'method' => 'POST' ] ),
			HttpException::class
		];

		yield 'POST request with non-empty body' => [
			$nonEmptyBodyValidator,
			new RequestData( $nonEmptyBodyParams + [ 'method' => ' POST' ] ),
			$bodyData
		];

		yield 'PUT request with empty body' => [
			$nonEmptyBodyValidator,
			new RequestData( $emptyBodyParams + [ 'method' => 'PUT' ] ),
			HttpException::class
		];

		yield 'PUT request with non-empty body' => [
			$nonEmptyBodyValidator,
			new RequestData( $nonEmptyBodyParams + [ 'method' => ' put ' ] ),
			$bodyData
		];
	}

	/**
	 * If $expected is a string, it must be the name of the expected exception class.
	 * Otherwise, it must match the returned body.
	 *
	 * @dataProvider provideValidateBody
	 */
	public function testValidateBody( BodyValidator $bodyValidator, RequestData $requestData, $expected ) {
		$objectFactory = $this->getDummyObjectFactory();

		/** @var Handler|MockObject $handler */
		$handler = $this->getMockBuilder( Handler::class )
			->onlyMethods( [ 'getBodyValidator' , 'execute' ] )
			->getMock();
		$handler->method( 'getBodyValidator' )->willReturn( $bodyValidator );

		$validator = new Validator( $objectFactory, $requestData, $this->mockAnonNullAuthority() );

		if ( is_string( $expected ) ) {
			$this->expectException( $expected );
		}

		$actual = $validator->validateBody( $requestData, $handler );
		$this->assertEquals( $expected, $actual );
	}
}
