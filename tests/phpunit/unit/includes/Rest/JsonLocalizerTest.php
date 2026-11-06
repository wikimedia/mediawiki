<?php

namespace MediaWiki\Tests\Rest;

use MediaWiki\Rest\JsonLocalizer;
use MediaWikiUnitTestCase;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * @covers \MediaWiki\Rest\JsonLocalizer
 */
class JsonLocalizerTest extends MediaWikiUnitTestCase {
	use RestTestTrait;

	public static function provideLocalizeJsonObjects() {
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
			'pair override, unprefixed key first' => [
				[
					'description' => 'untranslated',
					'x-i18n-description' => 'foo',
				],
				[
					'description' => '<message key="foo"></message>',
				]
			],
			'pair override, nested' => [
				[
					'properties' => [
						'my-property-name' => [
							'x-i18n-description' => 'foo',
							'description' => 'untranslated',
						],
					],
				],
				[
					'properties' => [
						'my-property-name' => [
							'description' => '<message key="foo"></message>',
						],
					],
				]
			],
			'MessageValue value' => [
				[
					'description' => new MessageValue( 'foo' ),
				],
				[
					'description' => '<message key="foo"></message>',
				]
			],
			'MessageSpecifier implementation other than MessageValue' => [
				[
					'description' => new class implements MessageSpecifier {
						public function getKey(): string {
							return 'foo';
						}

						public function getParams(): array {
							return [];
						}
					},
				],
				[
					// The dummy formatter only dumps MessageValue instances and returns the
					// key for any other MessageSpecifier. Either way, it was passed through
					// the formatter instead of being left in place as an object.
					'description' => 'foo',
				]
			],
			'nested MessageValue values' => [
				[
					'properties' => [
						'my-property-name' => [
							'type' => 'integer',
							'example' => new MessageValue( 'foo' ),
						],
					],
					'required' => [ 'a', 'b' ],
				],
				[
					'properties' => [
						'my-property-name' => [
							'type' => 'integer',
							'example' => '<message key="foo"></message>',
						],
					],
					'required' => [ 'a', 'b' ],
				]
			],
			'MessageValue in a list' => [
				[
					'examples' => [ new MessageValue( 'foo' ), 'plain', new MessageValue( 'bar' ) ],
				],
				[
					'examples' => [
						'<message key="foo"></message>',
						'plain',
						'<message key="bar"></message>',
					],
				]
			],
			'MessageValue with parameters' => [
				[
					'description' => new MessageValue( 'foo', [ 'x' ] ),
				],
				[
					'description' => '<message key="foo"><text>x</text></message>',
				]
			],
			'MessageValue next to translatable pair' => [
				[
					'x-i18n-title' => 'foo',
					'description' => new MessageValue( 'bar' ),
				],
				[
					'title' => '<message key="foo"></message>',
					'description' => '<message key="bar"></message>',
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

	public function testLocalizeValueIsDeprecated() {
		$this->expectDeprecationAndContinue( '/localizeValue/' );

		$util = new JsonLocalizer( $this->getDummyTextFormatter( true ) );
		$this->assertSame(
			'<message key="foo"></message>',
			$util->localizeValue( [ 'description' => new MessageValue( 'foo' ) ], 'description' )
		);
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
