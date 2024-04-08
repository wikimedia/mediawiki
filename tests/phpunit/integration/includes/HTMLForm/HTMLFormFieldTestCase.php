<?php

namespace MediaWiki\Tests\Integration\HTMLForm;

use HTMLForm;
use InvalidArgumentException;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWikiIntegrationTestCase;

abstract class HTMLFormFieldTestCase extends MediaWikiIntegrationTestCase {
	protected $className = null;

	public function constructField( array $params ): HTMLFormField {
		if ( $this->className === null ) {
			throw new InvalidArgumentException(
				'HTMLFormFieldTestCase subclass ' . __CLASS__ . ' must override $className or constructField()'
			);
		}
		return new $this->className( $params + [
			'parent' => $this->createMock( HTMLForm::class ),
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
		$this->assertHTMLEquals( $expected, $field->getInputHtml( $value ) );
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
		$this->assertHTMLEquals( $expected, $field->getInputOOUI( $value ) );
	}

	public static function provideInputCodex() {
		return [];
	}

	/**
	 * @dataProvider provideInputCodex
	 */
	public function testGetInputCodex( $params, $value, $hasError, $expected ) {
		$field = $this->constructField( $params );
		$this->assertHTMLEquals(
			// assertHTMLEquals is pretty basic; augment it a little bit by stripping newlines and
			// tabs from $expected. This allows $expected to be expressed as a heredoc string
			str_replace( [ "\n", "\t" ], '', $expected ),
			$field->getInputCodex( $value, $hasError )
		);
	}

}
