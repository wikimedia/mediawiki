<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\JsonLocalizer;
use MediaWikiUnitTestCase;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\JsonLocalizer
 */
class JsonLocalizerTest extends MediaWikiUnitTestCase {
	use RestTestTrait;

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
					'description' => '<message key="foo"></message>',
				]
			],
			'pair override' => [
				[
					'x-i18n-description' => 'foo',
					'description' => 'untranslated',
				],
				[
					'description' => '<message key="foo"></message>',
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
					'description' => '<message key="foo"></message>',
					'properties' => [
						[
							'my-property-name' => [
								'type' => 'integer',
								'description' => '<message key="bar"></message>'
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
					'title' => '<message key="foo"></message>',
				]
			],
			'translatable description and title' => [
				[
					'x-i18n-title' => 'foo',
					'x-i18n-description' => 'bar',
				],
				[
					'title' => '<message key="foo"></message>',
					'description' => '<message key="bar"></message>',
				]
			],
		];
	}

	/**
	 * @dataProvider provideLocalizeJsonObjects
	 */
	public function testLocalizeJson( $inputObj, $expectedObj ) {
		$util = new JsonLocalizer( $this->getDummyTextFormatter( true ) );
		$adjustedObj = $util->localizeJson( $inputObj );
		$this->assertEquals( $expectedObj, $adjustedObj );
	}

	public static function provideGetFormattedMessage() {
		return [
			'message key' => [
				'foo',
				'<message key="foo"></message>'
			],
			'message value' => [
				new MessageValue( 'foo' ),
				'<message key="foo"></message>'
			]
		];
	}

	/**
	 * @dataProvider provideGetFormattedMessage
	 */
	public function testGetFormattedMessage( $message, $expectedString ) {
		$util = new JsonLocalizer( $this->getDummyTextFormatter( true ) );
		$ret = $util->getFormattedMessage( $message );
		$this->assertEquals( $expectedString, $ret );
	}
}
