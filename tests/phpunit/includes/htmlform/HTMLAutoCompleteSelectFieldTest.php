<?php
/**
 * @covers HTMLAutoCompleteSelectField
 */
class HTMLAutoCompleteSelectFieldTest extends MediaWikiIntegrationTestCase {

	public $options = [
		'Bulgaria'     => 'BGR',
		'Burkina Faso' => 'BFA',
		'Burundi'      => 'BDI',
	];

	/**
	 * Verify that attempting to instantiate an HTMLAutoCompleteSelectField
	 * without providing any autocomplete options causes an exception to be
	 * thrown.
	 */
	public function testMissingAutocompletions() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( "called without any autocompletions" );
		new HTMLAutoCompleteSelectField( [ 'fieldname' => 'Test' ] );
	}

	/**
	 * Verify that the autocomplete options are correctly encoded as
	 * the 'data-autocomplete' attribute of the field.
	 *
	 * @covers HTMLAutoCompleteSelectField::getAttributes
	 */
	public function testGetAttributes() {
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
	public function testOptionalSelectElement() {
		$params = [
			'fieldname'         => 'Test',
			'autocomplete-data' => $this->options,
			'options'           => $this->options,
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
