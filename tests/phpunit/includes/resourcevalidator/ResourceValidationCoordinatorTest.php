<?php

class ResourceValidationCoordinatorTest extends PHPUnit_Framework_TestCase
{
	private $resourceValidationCoordinator;
	private $resourceLoader;

	protected function setUp() {
		parent::setUp();
		$this->resourceLoader = new ResourceLoader();
		$this->resourceValidationCoordinator = new ResourceValidationCoordinator( $this->resourceLoader );
		$this->resourceValidator = $this->getMock( 'ResourceValidator' );
	}

	public function test_validateAllResources_should_return_a_success_by_default() {
		$success = ResourceValidationResult::success();
		$this->resourceValidator->expects( $this->any() )
			->method( 'validateResourceLoaderModule' )
			->with( $this->anything() )
			->will( $this->returnValue( $success ) );
		$result = $this->resourceValidationCoordinator->validateAllResources( $this->resourceValidator );
		$this->assertEquals( $success, $result );
	}

	public function test_validateAllResources_should_return_a_failure_when_a_module_is_invalid() {
		$resourceLoaderModule = $this->getMock( 'ResourceLoaderModule' );
		$this->resourceLoader->register( 'resourceLoaderModule', $resourceLoaderModule );
		$failure = ResourceValidationResult::failure();
		$this->resourceValidator->expects( $this->any() )
			->method( 'validateResourceLoaderModule' )
			->with( $this->anything() )
			->will( $this->returnValue( $failure ) );
		$result = $this->resourceValidationCoordinator->validateAllResources( $this->resourceValidator );
		$this->assertEquals( $failure, $result );
	}
}
