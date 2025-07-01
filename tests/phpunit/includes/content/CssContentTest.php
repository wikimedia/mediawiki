<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\Content;

use MediaWiki\Content\Content;
use MediaWiki\Content\CssContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\MainConfigNames;

/**
 * @group ContentHandler
 * @group Database
 *        ^--- needed, because we do need the database to test link updates
 * @covers \MediaWiki\Content\CssContent
 */
class CssContentTest extends TextContentTest {
	use ContentSerializationTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue(
			MainConfigNames::TextModelsToParse,
			[
				CONTENT_MODEL_CSS,
			]
		);
	}

	public function newContent( $text ) {
		return new CssContent( $text );
	}

	// XXX: currently, preSaveTransform is applied to styles. this may change or become optional.
	public static function providePreSaveTransform() {
		return [
			[ 'hello this is ~~~',
				"hello this is [[Special:Contributions/127.0.0.1|127.0.0.1]]",
			],
			[ 'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
				'hello \'\'this\'\' is <nowiki>~~~</nowiki>',
			],
			[ " Foo \n ",
				" Foo",
			],
		];
	}

	public function testGetModel() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getModel() );
	}

	public function testGetContentHandler() {
		$content = $this->newContent( 'hello world.' );

		$this->assertEquals( CONTENT_MODEL_CSS, $content->getContentHandler()->getModelID() );
	}

	/**
	 * Redirects aren't supported
	 */
	public static function provideUpdateRedirect() {
		return [
			[
				'#REDIRECT [[Someplace]]',
				'#REDIRECT [[Someplace]]',
			],
		];
	}

	/**
	 * @dataProvider provideGetRedirectTarget
	 */
	public function testGetRedirectTarget( $title, $text ) {
		$this->overrideConfigValues( [
			MainConfigNames::Server => '//example.org',
			MainConfigNames::ScriptPath => '/w',
			MainConfigNames::Script => '/w/index.php',
		] );
		$content = new CssContent( $text );
		$target = $content->getRedirectTarget();
		$this->assertEquals( $title, $target ? $target->getPrefixedText() : null );
	}

	/**
	 * Keep this in sync with CssContentHandlerTest::provideMakeRedirectContent()
	 */
	public static function provideGetRedirectTarget() {
		return [
			[ 'MediaWiki:MonoBook.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=MediaWiki:MonoBook.css&action=raw&ctype=text/css);" ],
			[ 'User:FooBar/common.css', "/* #REDIRECT */@import url(//example.org/w/index.php?title=User:FooBar/common.css&action=raw&ctype=text/css);" ],
			[
				'User:😂/unicode.css',
				'/* #REDIRECT */@import url(//example.org/w/index.php?title=User:%F0%9F%98%82/unicode.css&action=raw&ctype=text/css);'
			],
			# No #REDIRECT comment
			[ null, "@import url(//example.org/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
			# Wrong domain
			[ null, "/* #REDIRECT */@import url(//example.com/w/index.php?title=Gadget:FooBaz.css&action=raw&ctype=text/css);" ],
		];
		// phpcs:enable
	}

	public static function provideEquals() {
		return [
			[ new CssContent( 'hallo' ), null, false ],
			[ new CssContent( 'hallo' ), new CssContent( 'hallo' ), true ],
			[ new CssContent( 'hallo' ), new WikitextContent( 'hallo' ), false ],
			[ new CssContent( 'hallo' ), new CssContent( 'HALLO' ), false ],
		];
	}

	/**
	 * @dataProvider provideEquals
	 */
	public function testEquals( Content $a, ?Content $b = null, $equal = false ) {
		$this->assertEquals( $equal, $a->equals( $b ) );
	}

	public static function getClassToTest(): string {
		return CssContent::class;
	}

	public static function getTestInstancesAndAssertions(): array {
		$redirects = self::provideGetRedirectTarget();
		[ $redirectTitle, $redirectBlob ] = $redirects[0];
		return [
			'basic' => [
				'instance' => new CssContent( '/* hello */' ),
				'assertions' => static function ( $testCase, $obj ) {
					$testCase->assertInstanceof( CssContent::class, $obj );
					$testCase->assertSame( '/* hello */', $obj->getText() );
					$testCase->assertNull( $obj->getRedirectTarget() );
				},
			],
			'redirect' => [
				'instance' => new CssContent( $redirectBlob ),
				'assertions' => static function ( $testCase, $obj ) use ( $redirectTitle, $redirectBlob ) {
					$testCase->overrideConfigValues( [
						MainConfigNames::Server => '//example.org',
						MainConfigNames::ScriptPath => '/w',
						MainConfigNames::Script => '/w/index.php',
					] );
					$testCase->assertInstanceof( CssContent::class, $obj );
					$testCase->assertSame( $redirectBlob, $obj->getText() );
					$testCase->assertSame( $redirectTitle, $obj->getRedirectTarget()->getPrefixedText() );
				},
			],
		];
	}
}
