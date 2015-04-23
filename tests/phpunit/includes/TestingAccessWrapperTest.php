<?php

class TestingAccessWrapperTest extends MediaWikiTestCase {
	protected $raw;
	protected $wrapped;

	function setUp() {
		parent::setUp();

		require_once __DIR__ . '/../data/helpers/WellProtectedClass.php';
		$this->raw = new WellProtectedClass();
		$this->wrapped = TestingAccessWrapper::newFromObject( $this->raw );
	}

	function testGetProperty() {
		$this->assertSame( 1, $this->wrapped->property );
	}

	function testSetProperty() {
		$this->wrapped->property = 10;
		$this->assertSame( 10, $this->wrapped->property );
		$this->assertSame( 10, $this->raw->getProperty() );
	}

	function testCallMethod() {
		$this->wrapped->incrementPropertyValue();
		$this->assertSame( 2, $this->wrapped->property );
		$this->assertSame( 2, $this->raw->getProperty() );
	}

	function testCallMethodTwoArgs() {
		$this->assertSame( 'two', $this->wrapped->whatSecondArg( 'one', 'two' ) );
	}
}
