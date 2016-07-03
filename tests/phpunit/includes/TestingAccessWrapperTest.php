<?php

class TestingAccessWrapperTest extends MediaWikiTestCase {
	protected $raw;
	protected $wrapped;
	protected $wrappedStatic;

	function setUp() {
		parent::setUp();

		require_once __DIR__ . '/../data/helpers/WellProtectedClass.php';
		$this->raw = new WellProtectedClass();
		$this->wrapped = TestingAccessWrapper::newFromObject( $this->raw );
		$this->wrappedStatic = TestingAccessWrapper::newFromClass( 'WellProtectedClass' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	function testConstructorException() {
		TestingAccessWrapper::newFromObject( 'WellProtectedClass' );
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	function testStaticConstructorException() {
		TestingAccessWrapper::newFromClass( new WellProtectedClass() );
	}

	function testGetProperty() {
		$this->assertSame( 1, $this->wrapped->property );
		$this->assertSame( 42, $this->wrapped->privateProperty );
		$this->assertSame( 9000, $this->wrapped->privateParentProperty );
		$this->assertSame( 'sp', $this->wrapped->staticProperty );
		$this->assertSame( 'spp', $this->wrapped->staticPrivateProperty );
		$this->assertSame( 'sp', $this->wrappedStatic->staticProperty );
		$this->assertSame( 'spp', $this->wrappedStatic->staticPrivateProperty );
	}

	/**
	 * @expectedException DomainException
	 */
	function testGetException() {
		$this->wrappedStatic->property;
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

		$this->wrapped->staticProperty = 'x';
		$this->assertSame( 'x', $this->wrapped->staticProperty );
		$this->assertSame( 'x', $this->wrappedStatic->staticProperty );

		$this->wrapped->staticPrivateProperty = 'y';
		$this->assertSame( 'y', $this->wrapped->staticPrivateProperty );
		$this->assertSame( 'y', $this->wrappedStatic->staticPrivateProperty );

		$this->wrappedStatic->staticProperty = 'X';
		$this->assertSame( 'X', $this->wrapped->staticProperty );
		$this->assertSame( 'X', $this->wrappedStatic->staticProperty );

		$this->wrappedStatic->staticPrivateProperty = 'Y';
		$this->assertSame( 'Y', $this->wrapped->staticPrivateProperty );
		$this->assertSame( 'Y', $this->wrappedStatic->staticPrivateProperty );

		// don't rely on PHPUnit to restore static properties
		$this->wrapped->staticProperty = 'sp';
		$this->wrapped->staticPrivateProperty = 'spp';
	}

	/**
	 * @expectedException DomainException
	 */
	function testSetException() {
		$this->wrappedStatic->property = 1;
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

		$this->assertSame( 'sm', $this->wrapped->staticMethod() );
		$this->assertSame( 'spm', $this->wrapped->staticPrivateMethod() );
		$this->assertSame( 'sm', $this->wrappedStatic->staticMethod() );
		$this->assertSame( 'spm', $this->wrappedStatic->staticPrivateMethod() );
	}

	function testCallMethodTwoArgs() {
		$this->assertSame( 'two', $this->wrapped->whatSecondArg( 'one', 'two' ) );
	}

	/**
	 * @expectedException DomainException
	 */
	function testCallMethodException() {
		$this->wrappedStatic->incrementPropertyValue();
	}

}
