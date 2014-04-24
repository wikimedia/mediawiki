<?php

/**
 * Unit tests for the HTMLCheckMatrix
 * @covers HTMLCheckMatrix
 */
class HtmlCheckMatrixTest extends MediaWikiTestCase {
	static private $defaultOptions = array(
		'rows' => array( 'r1', 'r2' ),
		'columns' => array( 'c1', 'c2' ),
		'fieldname' => 'test',
	);

	public function testPlainInstantiation() {
		try {
			new HTMLCheckMatrix( array() );
		} catch ( MWException $e ) {
			$this->assertInstanceOf( 'HTMLFormFieldRequiredOptionsException', $e );
			return;
		}

		$this->fail( 'Expected MWException indicating missing parameters but none was thrown.' );
	}

	public function testInstantiationWithMinimumRequiredParameters() {
		new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertTrue( true ); // form instantiation must throw exception on failure
	}

	public function testValidateCallsUserDefinedValidationCallback() {
		$called = false;
		$field = new HTMLCheckMatrix( self::$defaultOptions + array(
			'validation-callback' => function () use ( &$called ) {
				$called = true;

				return false;
			},
		) );
		$this->assertEquals( false, $this->validate( $field, array() ) );
		$this->assertTrue( $called );
	}

	public function testValidateRequiresArrayInput() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertEquals( false, $this->validate( $field, null ) );
		$this->assertEquals( false, $this->validate( $field, true ) );
		$this->assertEquals( false, $this->validate( $field, 'abc' ) );
		$this->assertEquals( false, $this->validate( $field, new stdClass ) );
		$this->assertEquals( true, $this->validate( $field, array() ) );
	}

	public function testValidateAllowsOnlyKnownTags() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertInternalType( 'string', $this->validate( $field, array( 'foo' ) ) );
	}

	public function testValidateAcceptsPartialTagList() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertTrue( $this->validate( $field, array() ) );
		$this->assertTrue( $this->validate( $field, array( 'c1-r1' ) ) );
		$this->assertTrue( $this->validate( $field, array( 'c1-r1', 'c1-r2', 'c2-r1', 'c2-r2' ) ) );
	}

	/**
	 * This form object actually has no visibility into what happens later on, but essentially
	 * if the data submitted by the user passes validate the following is run:
	 * foreach ( $field->filterDataForSubmit( $data ) as $k => $v ) {
	 *     $user->setOption( $k, $v );
	 * }
	 */
	public function testValuesForcedOnRemainOn() {
		$field = new HTMLCheckMatrix( self::$defaultOptions + array(
				'force-options-on' => array( 'c2-r1' ),
			) );
		$expected = array(
			'c1-r1' => false,
			'c1-r2' => false,
			'c2-r1' => true,
			'c2-r2' => false,
		);
		$this->assertEquals( $expected, $field->filterDataForSubmit( array() ) );
	}

	public function testValuesForcedOffRemainOff() {
		$field = new HTMLCheckMatrix( self::$defaultOptions + array(
				'force-options-off' => array( 'c1-r2', 'c2-r2' ),
			) );
		$expected = array(
			'c1-r1' => true,
			'c1-r2' => false,
			'c2-r1' => true,
			'c2-r2' => false,
		);
		// array_keys on the result simulates submitting all fields checked
		$this->assertEquals( $expected, $field->filterDataForSubmit( array_keys( $expected ) ) );
	}

	protected function validate( HTMLFormField $field, $submitted ) {
		return $field->validate(
			$submitted,
			array( self::$defaultOptions['fieldname'] => $submitted )
		);
	}

}
