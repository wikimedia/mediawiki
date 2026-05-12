<?php

namespace MediaWiki\Tests\Unit\PageEdit;

use Exception;
use LogicException;
use MediaWiki\EditPage\Constraint\EditConstraint;
use MediaWiki\EditPage\IEditObject;
use MediaWiki\PageEdit\PageEditStatus;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\PageEdit\PageEditStatus
 */
class PageEditStatusTest extends MediaWikiUnitTestCase {

	public function testSetAndGetFailedConstraint() {
		$status = new PageEditStatus();

		$this->assertNull( $status->getFailedConstraint() );

		$mockConstraint = $this->createMock( EditConstraint::class );
		$status->setFailedConstraint( $mockConstraint );

		$this->assertSame( $mockConstraint, $status->getFailedConstraint() );
	}

	public function testSetValue() {
		$status = new PageEditStatus();

		$status->setValue( IEditObject::AS_SUCCESS_NEW_ARTICLE );
		$this->assertSame( IEditObject::AS_SUCCESS_NEW_ARTICLE, $status->getValue() );

		$status->setValue( IEditObject::AS_SPAM_ERROR );
		$this->assertSame( IEditObject::AS_SPAM_ERROR, $status->getValue() );
	}

	public function testErrorFunction() {
		$status = new PageEditStatus();
		$exceptionClass = new class extends Exception {
		};
		$status->setErrorFunction( static fn () => throw new $exceptionClass() );
		$this->expectException( get_class( $exceptionClass ) );
		$status->throwError();
	}

	public function testErrorFunctionNotSet() {
		$status = new PageEditStatus();

		$this->expectException( LogicException::class );
		$status->throwError();
	}

	public function testErrorFunctionDoesNotThrow() {
		$status = new PageEditStatus();
		$status->setErrorFunction( static fn () => null );

		$this->expectException( LogicException::class );
		$status->throwError();
	}

}
