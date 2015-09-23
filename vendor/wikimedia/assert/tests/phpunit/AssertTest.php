<?php

namespace Wikimedia\Assert\Test;

use LogicException;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Wikimedia\Assert\Assert;
use Wikimedia\Assert\AssertionException;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Assert\ParameterElementTypeException;
use Wikimedia\Assert\ParameterTypeException;

/**
 * @covers Wikimedia\Assert\Assert
 *
 * @license MIT
 * @author Daniel Kinzler
 * @copyright Wikimedia Deutschland e.V.
 */

class AssertTest extends PHPUnit_Framework_TestCase {

	public function testPrecondition_pass() {
		Assert::precondition( true, 'test' );
	}

	public function testPrecondition_fail() {
		$this->setExpectedException( 'Wikimedia\Assert\PreconditionException' );
		Assert::precondition( false, 'test' );
	}

	public function testParameter_pass() {
		Assert::parameter( true, 'foo', 'test' );
	}

	public function testParameter_fail() {
		try {
			Assert::parameter( false, 'test', 'testing' );
		} catch ( ParameterAssertionException $ex ) {
			$this->assertEquals( 'test', $ex->getParameterName() );
		}
	}

	public function validParameterTypeProvider() {
		return array(
			'simple' => array( 'string', 'hello' ),
			'class' => array( 'RuntimeException', new RuntimeException() ),
			'multi' => array( 'string|array|Closure', function() {} ),
			'null' => array( 'integer|null', null ),
			'callable' => array( 'null|callable', 'time' ),
		);
	}

	/**
	 * @dataProvider validParameterTypeProvider
	 */
	public function testParameterType_pass( $type, $value ) {
		Assert::parameterType( $type, $value, 'test' );
	}

	public function invalidParameterTypeProvider() {
		return array(
			'simple' => array( 'string', 5 ),
			'class' => array( 'RuntimeException', new LogicException() ),
			'multi' => array( 'string|integer|Closure', array() ),
			'null' => array( 'integer|string', null ),
			'callable' => array( 'null|callable', array() ),
		);
	}

	/**
	 * @dataProvider invalidParameterTypeProvider
	 */
	public function testParameterType_fail( $type, $value ) {
		try {
			Assert::parameterType( $type, $value, 'test' );
			$this->fail( 'Expected ParameterTypeException' );
		} catch ( ParameterTypeException $ex ) {
			$this->assertEquals( $type, $ex->getParameterType() );
			$this->assertEquals( 'test', $ex->getParameterName() );
		}
	}

	public function testParameterType_catch() {
		try {
			Assert::parameterType( 'string', 17, 'test' );
			$this->fail( 'Expected exception' );
		} catch ( AssertionException $ex ) {
			// ok
		}
	}

	public function validParameterElementTypeProvider() {
		return array(
			'empty' => array( 'string', array() ),
			'simple' => array( 'string', array( 'hello', 'world' ) ),
			'class' => array( 'RuntimeException', array( new RuntimeException() ) ),
			'multi' => array( 'string|array|Closure', array( array(), function() {} ) ),
			'null' => array( 'integer|null', array( null, 3, null ) ),
		);
	}

	/**
	 * @dataProvider validParameterElementTypeProvider
	 */
	public function testParameterElementType_pass( $type, $value ) {
		Assert::parameterElementType( $type, $value, 'test' );
	}

	public function invalidParameterElementTypeProvider() {
		return array(
			'simple' => array( 'string', array( 'hello', 5 ) ),
			'class' => array( 'RuntimeException', array( new LogicException() ) ),
			'multi' => array( 'string|array|Closure', array( array(), function() {}, 5 ) ),
			'null' => array( 'integer|string', array( null, 3, null ) ),
		);
	}

	/**
	 * @dataProvider invalidParameterElementTypeProvider
	 */
	public function testParameterElementType_fail( $type, $value ) {
		try {
			Assert::parameterElementType( $type, $value, 'test' );
			$this->fail( 'Expected ParameterElementTypeException' );
		} catch ( ParameterElementTypeException $ex ) {
			$this->assertEquals( $type, $ex->getElementType() );
			$this->assertEquals( 'test', $ex->getParameterName() );
		}
	}

	public function testParameterElementType_bad() {
		$this->setExpectedException( 'Wikimedia\Assert\ParameterTypeException' );
		Assert::parameterElementType( 'string', 'foo', 'test' );
	}

	public function testInvariant_pass() {
		Assert::invariant( true, 'test' );
	}

	public function testInvariant_fail() {
		$this->setExpectedException( 'Wikimedia\Assert\InvariantException' );
		Assert::invariant( false, 'test' );
	}

	public function testPostcondition_pass() {
		Assert::postcondition( true, 'test' );
	}

	public function testPostcondition_fail() {
		$this->setExpectedException( 'Wikimedia\Assert\PostconditionException' );
		Assert::postcondition( false, 'test' );
	}

}
