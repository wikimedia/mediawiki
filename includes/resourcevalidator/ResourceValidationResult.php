<?php

class ResourceValidationResult
{
	protected $isSuccess;

	public static function success() {
		return new static( true );
	}

	public static function failure( $errors = array() ) {
		return new static( false, $errors );
	}

	protected function __construct( $isSuccess, $errors = array() ) {
		$this->isSuccess = $isSuccess;
		$this->errors = $errors;
	}

	public function isSuccess() {
		return $this->isSuccess;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function merge( ResourceValidationResult $resourceValidationResult ) {
		$isSuccess = $this->isSuccess && $resourceValidationResult->isSuccess;
		$errors = array_merge( $this->errors, $resourceValidationResult->errors );

		return new static( $isSuccess, $errors );
	}
}
