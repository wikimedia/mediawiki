<?php

/**
 * @covers HTMLCheckMatrix
 */
class HTMLCheckMatrixTest extends MediaWikiTestCase {
	private static $defaultOptions = [
		'rows' => [ 'r1', 'r2' ],
		'columns' => [ 'c1', 'c2' ],
		'fieldname' => 'test',
	];

	public function testPlainInstantiation() {
		try {
			new HTMLCheckMatrix( [] );
		} catch ( MWException $e ) {
			$this->assertInstanceOf( HTMLFormFieldRequiredOptionsException::class, $e );
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
		$field = new HTMLCheckMatrix( self::$defaultOptions + [
			'validation-callback' => function () use ( &$called ) {
				$called = true;

				return false;
			},
		] );
		$this->assertEquals( false, $this->validate( $field, [] ) );
		$this->assertTrue( $called );
	}

	public function testValidateRequiresArrayInput() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertEquals( false, $this->validate( $field, null ) );
		$this->assertEquals( false, $this->validate( $field, true ) );
		$this->assertEquals( false, $this->validate( $field, 'abc' ) );
		$this->assertEquals( false, $this->validate( $field, new stdClass ) );
		$this->assertEquals( true, $this->validate( $field, [] ) );
	}

	public function testValidateAllowsOnlyKnownTags() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertInstanceOf( Message::class, $this->validate( $field, [ 'foo' ] ) );
	}

	public function testValidateAcceptsPartialTagList() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertTrue( $this->validate( $field, [] ) );
		$this->assertTrue( $this->validate( $field, [ 'c1-r1' ] ) );
		$this->assertTrue( $this->validate( $field, [ 'c1-r1', 'c1-r2', 'c2-r1', 'c2-r2' ] ) );
	}

	/**
	 * This form object actually has no visibility into what happens later on, but essentially
	 * if the data submitted by the user passes validate the following is run:
	 * foreach ( $field->filterDataForSubmit( $data ) as $k => $v ) {
	 *     $user->setOption( $k, $v );
	 * }
	 */
	public function testValuesForcedOnRemainOn() {
		$field = new HTMLCheckMatrix( self::$defaultOptions + [
				'force-options-on' => [ 'c2-r1' ],
			] );
		$expected = [
			'c1-r1' => false,
			'c1-r2' => false,
			'c2-r1' => true,
			'c2-r2' => false,
		];
		$this->assertEquals( $expected, $field->filterDataForSubmit( [] ) );
	}

	public function testValuesForcedOffRemainOff() {
		$field = new HTMLCheckMatrix( self::$defaultOptions + [
				'force-options-off' => [ 'c1-r2', 'c2-r2' ],
			] );
		$expected = [
			'c1-r1' => true,
			'c1-r2' => false,
			'c2-r1' => true,
			'c2-r2' => false,
		];
		// array_keys on the result simulates submitting all fields checked
		$this->assertEquals( $expected, $field->filterDataForSubmit( array_keys( $expected ) ) );
	}

	protected function validate( HTMLFormField $field, $submitted ) {
		return $field->validate(
			$submitted,
			[ self::$defaultOptions['fieldname'] => $submitted ]
		);
	}

}
