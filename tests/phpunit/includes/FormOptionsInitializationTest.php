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
 * Dummy class to makes FormOptions::$options public.
 * Used by FormOptionsInitializationTest which need to verify the $options
 * array is correctly set through the FormOptions::add() function.
 */
class FormOptionsExposed extends FormOptions {
	public function getOptions() {
		return $this->options;
	}
}

/**
 * Test class for FormOptions initialization
 * Ensure the FormOptions::add() does what we want it to do.
 *
 * Copyright © 2011, Antoine Musso
 *
 * @author Antoine Musso
 */
class FormOptionsInitializationTest extends MediaWikiTestCase {
	/**
	 * @var FormOptions
	 */
	protected $object;

	/**
	 * A new fresh and empty FormOptions object to test initialization
	 * with.
	 */
	protected function setUp() {
		parent::setUp();
		$this->object = new FormOptionsExposed();
	}

	/**
	 * @covers FormOptionsExposed::add
	 */
	public function testAddStringOption() {
		$this->object->add( 'foo', 'string value' );
		$this->assertEquals(
			[
				'foo' => [
					'default' => 'string value',
					'consumed' => false,
					'type' => FormOptions::STRING,
					'value' => null,
				]
			],
			$this->object->getOptions()
		);
	}

	/**
	 * @covers FormOptionsExposed::add
	 */
	public function testAddIntegers() {
		$this->object->add( 'one', 1 );
		$this->object->add( 'negone', -1 );
		$this->assertEquals(
			[
				'negone' => [
					'default' => -1,
					'value' => null,
					'consumed' => false,
					'type' => FormOptions::INT,
				],
				'one' => [
					'default' => 1,
					'value' => null,
					'consumed' => false,
					'type' => FormOptions::INT,
				]
			],
			$this->object->getOptions()
		);
	}
}
