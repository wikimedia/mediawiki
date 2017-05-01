<?php
/**
 * Unit tests for HTMLAutoCompleteSelectField
 *
 * @covers HTMLAutoCompleteSelectField
 */
class HtmlAutoCompleteSelectFieldTest extends MediaWikiTestCase {

	public $options = [
		'Bulgaria'     => 'BGR',
		'Burkina Faso' => 'BFA',
		'Burundi'      => 'BDI',
	];

	/**
	 * Verify that attempting to instantiate an HTMLAutoCompleteSelectField
	 * without providing any autocomplete options causes an exception to be
	 * thrown.
	 *
	 * @expectedException        MWException
	 * @expectedExceptionMessage called without any autocompletions
	 */
	function testMissingAutocompletions() {
		new HTMLAutoCompleteSelectField( [ 'fieldname' => 'Test' ] );
	}

	/**
	 * Verify that the autocomplete options are correctly encoded as
	 * the 'data-autocomplete' attribute of the field.
	 *
	 * @covers HTMLAutoCompleteSelectField::getAttributes
	 */
	function testGetAttributes() {
		$field = new HTMLAutoCompleteSelectField( [
			'fieldname'    => 'Test',
			'autocomplete' => $this->options,
		] );

		$attributes = $field->getAttributes( [] );
		$this->assertEquals( array_keys( $this->options ),
			FormatJson::decode( $attributes['data-autocomplete'] ),
			"The 'data-autocomplete' attribute encodes autocomplete option keys as a JSON array."
		);
	}

	/**
	 * Test that the optional select dropdown is included or excluded based on
	 * the presence or absence of the 'options' parameter.
	 */
	function testOptionalSelectElement() {
		$params = [
			'fieldname'    => 'Test',
			'autocomplete' => $this->options,
			'options'      => $this->options,
		];

		$field = new HTMLAutoCompleteSelectField( $params );
		$html = $field->getInputHTML( false );
		$this->assertRegExp( '/select/', $html,
			"When the 'options' parameter is set, the HTML includes a <select>" );

		unset( $params['options'] );
		$field = new HTMLAutoCompleteSelectField( $params );
		$html = $field->getInputHTML( false );
		$this->assertNotRegExp( '/select/', $html,
			"When the 'options' parameter is not set, the HTML does not include a <select>" );
	}
}
