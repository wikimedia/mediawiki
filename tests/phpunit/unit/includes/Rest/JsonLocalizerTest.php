<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\JsonLocalizer;
use MediaWiki\Rest\ResponseFactory;
use MediaWikiUnitTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\JsonLocalizer
 */
class JsonLocalizerTest extends MediaWikiUnitTestCase {
	private function createMockResponseFactory(): ResponseFactory {
		$responseFactory = $this->createNoOpMock( ResponseFactory::class, [ 'getFormattedMessage' ] );
		$responseFactory->method( 'getFormattedMessage' )->willReturn( 'translated' );
		return $responseFactory;
	}

	public static function providelocalizeJsonObjects() {
		return [
			'empty object' => [
				[],
				[]
			],
			'no matching pair' => [
				[
					'properties' => [
						[ 'my-property-name' => [ 'type' => 'integer' ] ],
					],
				],
				[
					'properties' => [
						[ 'my-property-name' => [ 'type' => 'integer' ] ],
					],
				]
			],
			'nontranslatable pair' => [
				[
					'description' => 'foo',
				],
				[
					'description' => 'foo',
				]
			],
			'translatable pair' => [
				[
					'x-i18n-description' => 'foo',
				],
				[
					'description' => 'translated',
				]
			],
			'pair override' => [
				[
					'x-i18n-description' => 'foo',
					'description' => 'bar',
				],
				[
					'description' => 'translated',
				]
			],
			'nested translatable pair' => [
				[
					'x-i18n-description' => 'foo',
					'properties' => [
						[
							'my-property-name' => [
								'type' => 'integer',
								'x-i18n-description' => 'bar'
							]
						],
					],
				],
				[
					'description' => 'translated',
					'properties' => [
						[
							'my-property-name' => [
								'type' => 'integer',
								'description' => 'translated'
							]
						],
					],
				]
			],
			'translatable title, no description' => [
				[
					'x-i18n-title' => 'foo',
				],
				[
					'title' => 'translated',
				]
			],
			'translatable description and title' => [
				[
					'x-i18n-title' => 'foo',
					'x-i18n-description' => 'bar',
				],
				[
					'title' => 'translated',
					'description' => 'translated',
				]
			],
		];
	}

	/**
	 * @dataProvider provideLocalizeJsonObjects
	 */
	public function testLocalizeJson( $inputObj, $expectedObj ) {
		$util = new JsonLocalizer( $this->createMockResponseFactory() );
		$adjustedObj = $util->localizeJson( $inputObj );
		$this->assertEquals( $expectedObj, $adjustedObj );
	}

	public static function provideGetFormattedMessage() {
		return [
			'message key' => [
				'foo',
				'translated'
			],
			'message value' => [
				new MessageValue( 'foo' ),
				'translated'
			]
		];
	}

	/**
	 * @dataProvider provideGetFormattedMessage
	 */
	public function testGetFormattedMessage( $message, $expectedString ) {
		$util = new JsonLocalizer( $this->createMockResponseFactory() );
		$ret = $util->getFormattedMessage( $message );
		$this->assertEquals( $expectedString, $ret );
	}
}
