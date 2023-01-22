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
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "called without any autocompletions" );

		$htmlForm = $this->createMock( HTMLForm::class );
		new HTMLAutoCompleteSelectField( [ 'fieldname' => 'Test', 'parent' => $htmlForm ] );
	}

	/**
	 * Test that the optional select dropdown is included or excluded based on
	 * the presence or absence of the 'options' parameter.
	 */
	public function testOptionalSelectElement() {
		$htmlForm = $this->createMock( HTMLForm::class );
		$htmlForm->method( 'msg' )->willReturnCallback( 'wfMessage' );

		$params = [
			'fieldname'         => 'Test',
			'autocomplete-data' => $this->options,
			'options'           => $this->options,
			'parent'            => $htmlForm
		];

		$field = new HTMLAutoCompleteSelectField( $params );
		$html = $field->getInputHTML( false );
		$this->assertMatchesRegularExpression( '/select/', $html,
			"When the 'options' parameter is set, the HTML includes a <select>" );

		unset( $params['options'] );
		$field = new HTMLAutoCompleteSelectField( $params );
		$html = $field->getInputHTML( false );
		$this->assertDoesNotMatchRegularExpression( '/select/', $html,
			"When the 'options' parameter is not set, the HTML does not include a <select>" );
	}
}
