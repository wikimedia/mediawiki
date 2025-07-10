<?php

declare( strict_types=1 ); // This helps PhpStorm notice more problems, but has no effect on Phan.

use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Status\Status;

// phpcs:disable

/*
 * The `@template` annotations are kind of a new thing in our codebase, and support for them in Phan
 * is a bit dodgy. This file is meant to be both a test of their behavior and an example that you
 * can open in your favorite IDE and see how it can autocomplete the methods with these type hints
 * (or how it can't).
 */

die( 'This file should never be loaded' );

class Puppy {
	function bark() {
		echo "woof";
	}
}

class StatusTypeHintsTest {

	function testStatusTypeAny() {
		$s = Status::newGood();
		$s->setResult( true, new Puppy() );
		$puppy = $s->getValue();
		// Phan doesn't know the type of $puppy, so no warnings (but also no autocompletion in your IDE)
		$puppy->bark();
		$puppy->meow();
	}

	function testStatusTypeOverride() {
		/** @var Status<Puppy> $s */
		$s = new Status();
		'@phan-var Status<Puppy> $s';
		$s->setResult( true, new Puppy() );
		$puppy = $s->getValue();
		// The explicit override makes it work
		$puppy->bark();
		$puppy->meow(); // @phan-suppress-current-line PhanUndeclaredMethod
	}

	function testStatusTypeNever() {
		// StatusValue subclasses with the type parameter set to 'never' can't have a value.
		// Trying to access the value causes errors.
		$s = new PermissionStatus();
		$value = $s->getValue(); // @phan-suppress-current-line PhanUseReturnValueOfNever
		$value2 = $s->value; // This should emit an error too, but it doesn't.
	}

	/**
	 * Showcase Phan's issue detection for templated types. Your IDE may understand them too.
	 *
	 * @param Status $status
	 * @param Status<mixed> $mixedStatus
	 * @param Status<float> $floatStatus
	 * @param Status<Puppy> $puppyStatus
	 */
	function testStatusTypes(
		Status $status,
		Status $mixedStatus,
		Status $floatStatus,
		Status $puppyStatus,
	) {
		// As the type of the value is completely unknown, these calls are not flagged as issues.
		$unknown = $status->getValue();
		$this->acceptMixed( $unknown );
		$this->acceptStatusMixed( $status );
		$this->acceptFloat( $unknown );
		$this->acceptStatusFloat( $status );
		$this->acceptPuppy( $unknown );
		$this->acceptStatusPuppy( $status );

		// As the type of the value is known to be possibly anything, these calls should be flagged…
		$mixed = $mixedStatus->getValue();
		$this->acceptMixed( $mixed );
		$this->acceptStatusMixed( $mixedStatus );
		$this->acceptFloat( $mixed ); // https://github.com/phan/phan/issues/3984
		$this->acceptStatusFloat( $mixedStatus ); // https://github.com/phan/phan/issues/3984
		$this->acceptPuppy( $mixed ); // https://github.com/phan/phan/issues/3984
		$this->acceptStatusPuppy( $mixedStatus ); // https://github.com/phan/phan/issues/3984

		// As the type of the value is known more precisely, incorrect calls are flagged.
		$float = $floatStatus->getValue();
		$this->acceptMixed( $float );
		$this->acceptStatusMixed( $floatStatus );
		$this->acceptFloat( $float );
		$this->acceptStatusFloat( $floatStatus );
		$this->acceptPuppy( $float ); // @phan-suppress-current-line PhanTypeMismatchArgument
		$this->acceptStatusPuppy( $floatStatus ); // @phan-suppress-current-line PhanTypeMismatchArgument

		$puppy = $puppyStatus->getValue();
		$this->acceptMixed( $puppy );
		$this->acceptStatusMixed( $puppyStatus );
		$this->acceptFloat( $puppy ); // @phan-suppress-current-line PhanTypeMismatchArgument
		$this->acceptStatusFloat( $puppyStatus ); // @phan-suppress-current-line PhanTypeMismatchArgument
		$this->acceptPuppy( $puppy );
		$this->acceptStatusPuppy( $puppyStatus );

		// Phan knows which methods are valid (your IDE may also autocomplete them).
		$puppy->bark();
		$puppy->meow(); // @phan-suppress-current-line PhanUndeclaredMethod
	}

	function acceptMixed( mixed $mixed ) {}
	function acceptFloat( float $float ) {}
	function acceptPuppy( Puppy $puppy ) {}

	/** @param Status<mixed> $mixed */
	function acceptStatusMixed( Status $mixed ) {}
	/** @param Status<float> $float */
	function acceptStatusFloat( Status $float ) {}
	/** @param Status<Puppy> $puppy */
	function acceptStatusPuppy( Status $puppy ) {}
}
