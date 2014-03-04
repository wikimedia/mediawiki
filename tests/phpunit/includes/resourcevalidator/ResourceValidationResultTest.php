<?php

class ResourceValidationResultTest extends PHPUnit_Framework_TestCase
{
	public function test_a_success_is_a_success() {
		$success = ResourceValidationResult::success();
		$this->assertTrue( $success->isSuccess() );
	}

	public function test_a_failure_is_a_failure() {
		$failure = ResourceValidationResult::failure();
		$this->assertFalse( $failure->isSuccess() );
	}

	public function test_a_success_doesnt_have_an_error() {
		$success = ResourceValidationResult::success();
		$this->assertEmpty( $success->getErrors() );
	}

	public function test_a_success_merged_with_a_failure_is_a_failure() {
		$success = ResourceValidationResult::success();
		$failure = $success->merge( ResourceValidationResult::failure() );
		$this->assertFalse( $failure->isSuccess() );
	}

	public function test_a_success_merged_with_a_success_is_a_success() {
		$success = ResourceValidationResult::success();
		$success2 = ResourceValidationResult::success();
		$success3 = $success->merge( $success2 );
		$this->assertTrue( $success3->isSuccess() );
	}

	public function test_a_success_merged_with_a_failure_with_errors_is_a_failure_with_errors() {
		$errors = array(
			new Exception('Error #1'),
		);
		$success = ResourceValidationResult::success();
		$failure = ResourceValidationResult::failure( $errors );
		$failure2 = $success->merge( $failure );
		$this->assertEquals( $errors, $failure2->getErrors() );
	}

	public function test_a_failure_with_errors_merged_with_a_failure_with_errors_is_a_failure_with_merged_errors() {
		$failure = ResourceValidationResult::failure( array(
			new Exception('Error #1'),
		) );
		$failure2 = ResourceValidationResult::failure( array(
			new Exception('Error #2'),
		) );
		$failure3 = $failure->merge( $failure2 );
		$expectedErrors = array(
			new Exception('Error #1'),
			new Exception('Error #2'),
		);
		$this->assertEquals( $expectedErrors, $failure3->getErrors() );
	}
}
