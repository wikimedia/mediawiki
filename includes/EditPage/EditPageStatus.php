<?php

namespace MediaWiki\EditPage;

use Closure;
use LogicException;
use MediaWiki\EditPage\Constraint\EditConstraint;
use StatusValue;
use Throwable;

/**
 * Status returned by edit constraints and other page editing checks.
 *
 * @since 1.46
 * @internal
 *
 * @extends StatusValue<int>
 */
class EditPageStatus extends StatusValue {

	private ?Closure $errorFunction = null;
	private ?EditConstraint $failedConstraint = null;

	/**
	 * @param Closure|null $errorFunction A function that can be called to produce an error.
	 */
	public function setErrorFunction( ?Closure $errorFunction ): self {
		$this->errorFunction = $errorFunction;
		return $this;
	}

	/**
	 * @return ?EditConstraint The constraint that failed, or null if no constraint failed.
	 */
	public function getFailedConstraint(): ?EditConstraint {
		return $this->failedConstraint;
	}

	/**
	 * @param ?EditConstraint $failedConstraint The constraint that failed and produced this status, or null if no
	 * constraint failed.
	 */
	public function setFailedConstraint( ?EditConstraint $failedConstraint ): self {
		$this->failedConstraint = $failedConstraint;
		return $this;
	}

	/**
	 * Sets the value of the status.
	 * @param int $value One of the constants from {@see IEditObject}
	 */
	public function setValue( int $value ): self {
		$this->value = $value;
		return $this;
	}

	/**
	 * @throws Throwable If an error function is set.
	 * @throws LogicException If no error function is set or the error function did not throw an error.
	 */
	public function throwError(): never {
		if ( $this->errorFunction !== null ) {
			( $this->errorFunction )();
		} else {
			throw new LogicException( __METHOD__ . ' called, but no error function was set!' );
		}
		throw new LogicException( __METHOD__ . ' called, but error function did not throw an error!' );
	}

}
