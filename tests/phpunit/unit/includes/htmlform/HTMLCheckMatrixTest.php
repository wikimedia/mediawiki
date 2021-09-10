<?php

/**
 * @covers HTMLCheckMatrix
 */
class HTMLCheckMatrixTest extends MediaWikiUnitTestCase {
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
			'validation-callback' => static function () use ( &$called ) {
				$called = true;

				return false;
			},
		] );
		$this->assertFalse( $this->validate( $field, [] ) );
		$this->assertTrue( $called );
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate( $expected, $submitted ) {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertSame( $expected, $this->validate( $field, $submitted ) );
	}

	public function provideValidate() {
		// $expected, $submitted
		yield [ false, null ];
		yield [ false, true ];
		yield [ false, 'abc' ];
		yield [ false, (object)[] ];
		yield [ true, [] ];
		yield [ true, [ 'c1-r1' ] ];
		yield [ true, [ 'c1-r1', 'c1-r2', 'c2-r1', 'c2-r2' ] ];
	}

	public function testValidateAllowsOnlyKnownTags() {
		$field = new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertInstanceOf( Message::class, $this->validate( $field, [ 'foo' ] ) );
	}

	/**
	 * This form object actually has no visibility into what happens later on, but essentially
	 * if the data submitted by the user passes validate the following is run:
	 * foreach ( $field->filterDataForSubmit( $data ) as $k => $v ) {
	 *     $userOptionsManager->setOption( $user, $k, $v );
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
