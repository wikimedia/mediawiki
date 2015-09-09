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
		$this->assertSame( 42, $this->wrapped->privateProperty );
		$this->assertSame( 9000, $this->wrapped->privateParentProperty );
	}

	function testSetProperty() {
		$this->wrapped->property = 10;
		$this->assertSame( 10, $this->wrapped->property );
		$this->assertSame( 10, $this->raw->getProperty() );

		$this->wrapped->privateProperty = 11;
		$this->assertSame( 11, $this->wrapped->privateProperty );
		$this->assertSame( 11, $this->raw->getPrivateProperty() );

		$this->wrapped->privateParentProperty = 12;
		$this->assertSame( 12, $this->wrapped->privateParentProperty );
		$this->assertSame( 12, $this->raw->getPrivateParentProperty() );
	}

	function testCallMethod() {
		$this->wrapped->incrementPropertyValue();
		$this->assertSame( 2, $this->wrapped->property );
		$this->assertSame( 2, $this->raw->getProperty() );

		$this->wrapped->incrementPrivatePropertyValue();
		$this->assertSame( 43, $this->wrapped->privateProperty );
		$this->assertSame( 43, $this->raw->getPrivateProperty() );

		$this->wrapped->incrementPrivateParentPropertyValue();
		$this->assertSame( 9001, $this->wrapped->privateParentProperty );
		$this->assertSame( 9001, $this->raw->getPrivateParentProperty() );
	}

	function testCallMethodTwoArgs() {
		$this->assertSame( 'two', $this->wrapped->whatSecondArg( 'one', 'two' ) );
	}
}
