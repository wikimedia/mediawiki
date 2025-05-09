<?php

namespace MediaWiki\Tests\Integration\HTMLForm;

use InvalidArgumentException;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Language\RawMessage;
use MediaWikiIntegrationTestCase;

abstract class HTMLFormFieldTestCase extends MediaWikiIntegrationTestCase {
	/** @var string|null */
	protected $className = null;

	/**
	 * Augment assertEquals a little bit by stripping newlines and tabs from $expected.
	 * This allows $expected to be expressed as a heredoc string.
	 *
	 * @param string $expected HTML
	 * @param string $actual HTML
	 * @param string $msg Optional message
	 */
	private function assertHTMLEqualStrippingWhitespace( $expected, $actual, $msg = '' ) {
		$this->assertEquals(
			str_replace( [ "\n", "\t" ], '', $expected ),
			str_replace( [ "\n", "\t" ], '', $actual ),
			$msg
		);
	}

	public function constructField( array $params ): HTMLFormField {
		if ( $this->className === null ) {
			throw new InvalidArgumentException(
				'HTMLFormFieldTestCase subclass ' . __CLASS__ . ' must override $className or constructField()'
			);
		}

		$form = $this->createMock( HTMLForm::class );
		$form->method( 'getLanguage' )
			->willReturnCallback(
				fn () => $this->getServiceContainer()->getLanguageFactory()->getLanguage( 'qqx' )
			);
		$form->method( 'msg' )
			->willReturnCallback( static fn ( ...$args ) => new RawMessage( ...$args ) );

		return new $this->className( $params + [
			'parent' => $form,
			'name' => 'testfield'
		] );
	}

	public static function provideInputHtml() {
		return [];
	}

	/**
	 * @dataProvider provideInputHtml
	 */
	public function testGetInputHtml( $params, $value, $expected ) {
		$field = $this->constructField( $params );
		$this->assertHTMLEqualStrippingWhitespace( $expected, $field->getInputHtml( $value ) );
	}

	public static function provideInputOOUI() {
		return [];
	}

	/**
	 * @dataProvider provideInputOOUI
	 */
	public function testGetInputOOUI( $params, $value, $expected ) {
		\OOUI\Theme::setSingleton( new \OOUI\BlankTheme() );

		$field = $this->constructField( $params );
		$this->assertHTMLEqualStrippingWhitespace( $expected, $field->getInputOOUI( $value ) );
	}

	public static function provideInputCodex() {
		return [];
	}

	/**
	 * @dataProvider provideInputCodex
	 */
	public function testGetInputCodex( $params, $value, $hasError, $expected ) {
		$field = $this->constructField( $params );
		$this->assertHTMLEqualStrippingWhitespace( $expected, $field->getInputCodex( $value, $hasError ) );
	}

}
