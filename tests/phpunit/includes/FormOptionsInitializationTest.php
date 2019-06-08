<?php

use Wikimedia\TestingAccessWrapper;

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
		$this->object = TestingAccessWrapper::newFromObject( new FormOptions() );
	}

	/**
	 * @covers FormOptions::add
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
			$this->object->options
		);
	}

	/**
	 * @covers FormOptions::add
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
			$this->object->options
		);
	}
}
