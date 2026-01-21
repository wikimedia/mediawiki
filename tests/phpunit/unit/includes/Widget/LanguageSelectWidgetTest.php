<?php
declare( strict_types=1 );

/**
 * @license GPL-2.0-or-later
 * @author Language and Product Localization Team (LPL)
 * @file
 */

namespace MediaWiki\Tests\Widget;

use MediaWiki\Widget\LanguageSelectWidget;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Widget\LanguageSelectWidget
 */
class LanguageSelectWidgetTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
			'name' => 'test-language',
			'value' => 'en',
		];
		$widget = new LanguageSelectWidget( $config );
		$this->assertInstanceOf( LanguageSelectWidget::class, $widget );
	}

	public function testToStringBasic() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( '<select', $html );
		$this->assertStringContainsString( 'mw-widgets-languageSelectWidget', $html );
		$this->assertStringContainsString( 'mw-widgets-languageSelectWidget-select', $html );
		$this->assertStringContainsString( '</select>', $html );
		$this->assertStringContainsString( 'data-mw-languages', $html );
	}

	public function testToStringWithName() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'name' => 'test-language',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'name="test-language"', $html );
	}

	public function testToStringWithId() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'id' => 'test-language-id',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'id="test-language-id"', $html );
	}

	public function testToStringWithValue() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
			'value' => 'de',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		// MediaWiki's Html::element outputs boolean attributes as selected="" (empty string)
		$this->assertStringContainsString( '<option value="de" selected="">', $html );
		$this->assertStringNotContainsString( '<option value="en" selected="">', $html );
	}

	public function testToStringWithDisabled() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'disabled' => true,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'disabled', $html );
	}

	public function testToStringWithRequired() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'required' => true,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'required', $html );
	}

	public function testToStringWithMultiple() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
			'multiple' => true,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'multiple', $html );
	}

	public function testToStringWithCssClass() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'cssclass' => 'custom-class',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'custom-class', $html );
		$this->assertStringContainsString( 'mw-widgets-languageSelectWidget', $html );
	}

	public function testToStringWithNullLanguages() {
		$config = [
			'languages' => null,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'data-mw-languages="null"', $html );
		// Should not contain any option elements when languages is null
		$this->assertStringNotContainsString( '<option', $html );
	}

	public function testToStringWithEmptyLanguages() {
		$config = [];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'data-mw-languages="null"', $html );
	}

	public function testToStringGeneratesOptions() {
		$config = [
			'languages' => [
				'en' => 'English',
				'de' => 'German',
				'fr' => 'French',
			],
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( '<option value="en">', $html );
		$this->assertStringContainsString( '<option value="de">', $html );
		$this->assertStringContainsString( '<option value="fr">', $html );
		$this->assertStringContainsString( 'en - English', $html );
		$this->assertStringContainsString( 'de - German', $html );
		$this->assertStringContainsString( 'fr - French', $html );
	}

	public function testToStringWithAllAttributes() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
			'name' => 'test-language',
			'id' => 'test-id',
			'value' => 'en',
			'disabled' => true,
			'required' => true,
			'multiple' => true,
			'cssclass' => 'custom-class',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringContainsString( 'name="test-language"', $html );
		$this->assertStringContainsString( 'id="test-id"', $html );
		$this->assertStringContainsString( 'disabled', $html );
		$this->assertStringContainsString( 'required', $html );
		$this->assertStringContainsString( 'multiple', $html );
		$this->assertStringContainsString( 'custom-class', $html );
		// MediaWiki's Html::element outputs boolean attributes as selected="" (empty string)
		$this->assertStringContainsString( '<option value="en" selected="">', $html );
	}

	public function testToStringWithNullName() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'name' => null,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringNotContainsString( 'name=', $html );
	}

	public function testToStringWithNullId() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'id' => null,
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		$this->assertStringNotContainsString( 'id=', $html );
	}

	public function testMagicToString() {
		$config = [
			'languages' => [ 'en' => 'English' ],
		];
		$widget = new LanguageSelectWidget( $config );
		$html = (string)$widget;

		$this->assertStringContainsString( '<select', $html );
		$this->assertStringContainsString( '</select>', $html );
		$this->assertEquals( $widget->toString(), $html );
	}

	public function testToStringLanguagesJsonEncoded() {
		$config = [
			'languages' => [ 'en' => 'English', 'de' => 'German' ],
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		// Extract the data-mw-languages attribute value (HTML-encoded JSON)
		preg_match( '/data-mw-languages="([^"]+)"/', $html, $matches );
		$this->assertNotEmpty( $matches[1] );
		// Decode HTML entities before JSON decoding
		$decodedJson = html_entity_decode( $matches[1], ENT_QUOTES | ENT_HTML5 );
		$decoded = json_decode( $decodedJson, true );
		$this->assertIsArray( $decoded );
		$this->assertEquals( [ 'en' => 'English', 'de' => 'German' ], $decoded );
	}

	public function testToStringEmptyCssClass() {
		$config = [
			'languages' => [ 'en' => 'English' ],
			'cssclass' => '',
		];
		$widget = new LanguageSelectWidget( $config );
		$html = $widget->toString();

		// Should not have double spaces or empty class attribute
		$this->assertStringNotContainsString( '  ', $html );
		$this->assertStringContainsString( 'mw-widgets-languageSelectWidget', $html );
	}
}
