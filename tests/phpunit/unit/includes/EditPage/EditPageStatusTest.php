<?php

namespace MediaWiki\Tests\Unit\EditPage;

use Exception;
use LogicException;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\EditPageStatus;
use MediaWiki\EditPage\IEditObject;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\EditPage\EditPageStatus
 */
class EditPageStatusTest extends MediaWikiUnitTestCase {

	public function testSetAndGetFailedConstraint() {
		$status = new EditPageStatus();

		$this->assertNull( $status->getFailedConstraint() );

		$mockConstraint = $this->createMock( IEditConstraint::class );
		$status->setFailedConstraint( $mockConstraint );

		$this->assertSame( $mockConstraint, $status->getFailedConstraint() );
	}

	public function testSetValue() {
		$status = new EditPageStatus();

		$status->setValue( IEditObject::AS_SUCCESS_NEW_ARTICLE );
		$this->assertSame( IEditObject::AS_SUCCESS_NEW_ARTICLE, $status->getValue() );

		$status->setValue( IEditObject::AS_SPAM_ERROR );
		$this->assertSame( IEditObject::AS_SPAM_ERROR, $status->getValue() );
	}

	public function testErrorFunction() {
		$status = new EditPageStatus();
		$exceptionClass = new class extends Exception {
		};
		$status->setErrorFunction( static fn () => throw new $exceptionClass() );
		$this->expectException( get_class( $exceptionClass ) );
		$status->throwError();
	}

	public function testErrorFunctionNotSet() {
		$status = new EditPageStatus();

		$this->expectException( LogicException::class );
		$status->throwError();
	}

	public function testErrorFunctionDoesNotThrow() {
		$status = new EditPageStatus();
		$status->setErrorFunction( static fn () => null );

		$this->expectException( LogicException::class );
		$status->throwError();
	}

}
