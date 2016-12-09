<?php
/**
 * This file host two test case classes for the MediaWiki FormOptions class:
 *  - FormOptionsInitializationTest : tests initialization of the class.
 *  - FormOptionsTest : tests methods an on instance
 *
 * The split let us take advantage of setting up a fixture for the methods
 * tests.
 */

/**
 * Test class for FormOptions methods.
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 */
class FormOptionsTest extends MediaWikiTestCase {
	/**
	 * @var FormOptions
	 */
	protected $object;

	/**
	 * Instanciates a FormOptions object to play with.
	 * FormOptions::add() is tested by the class FormOptionsInitializationTest
	 * so we assume the function is well tested already an use it to create
	 * the fixture.
	 */
	protected function setUp() {
		parent::setUp();
		$this->object = new FormOptions;
		$this->object->add( 'string1', 'string one' );
		$this->object->add( 'string2', 'string two' );
		$this->object->add( 'integer', 0 );
		$this->object->add( 'float', 0.0 );
		$this->object->add( 'intnull', 0, FormOptions::INTNULL );
	}

	/** Helpers for testGuessType() */
	/* @{ */
	private function assertGuessBoolean( $data ) {
		$this->guess( FormOptions::BOOL, $data );
	}
	private function assertGuessInt( $data ) {
		$this->guess( FormOptions::INT, $data );
	}
	private function assertGuessFloat( $data ) {
		$this->guess( FormOptions::FLOAT, $data );
	}
	private function assertGuessString( $data ) {
		$this->guess( FormOptions::STRING, $data );
	}
	private function assertGuessArray( $data ) {
		$this->guess( FormOptions::ARR, $data );
	}

	/** Generic helper */
	private function guess( $expected, $data ) {
		$this->assertEquals(
			$expected,
			FormOptions::guessType( $data )
		);
	}
	/* @} */

	/**
	 * Reuse helpers above assertGuessBoolean assertGuessInt assertGuessString
	 * @covers FormOptions::guessType
	 */
	public function testGuessTypeDetection() {
		$this->assertGuessBoolean( true );
		$this->assertGuessBoolean( false );

		$this->assertGuessInt( 0 );
		$this->assertGuessInt( -5 );
		$this->assertGuessInt( 5 );
		$this->assertGuessInt( 0x0F );

		$this->assertGuessFloat( 0.0 );
		$this->assertGuessFloat( 1.5 );
		$this->assertGuessFloat( 1e3 );

		$this->assertGuessString( 'true' );
		$this->assertGuessString( 'false' );
		$this->assertGuessString( '5' );
		$this->assertGuessString( '0' );
		$this->assertGuessString( '1.5' );

		$this->assertGuessArray( [ 'foo' ] );
	}

	/**
	 * @expectedException MWException
	 * @covers FormOptions::guessType
	 */
	public function testGuessTypeOnNullThrowException() {
		$this->object->guessType( null );
	}
}
