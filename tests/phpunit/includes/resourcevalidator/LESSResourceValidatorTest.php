<?php

class LESSResourceValidatorTest extends PHPUnit_Framework_TestCase {
	private $lessc;
	private $resource;
	private $anotherResource;
	private $resources;
	private $resourceLoaderModule;
	private $styleFiles;

	protected function setUp() {
		parent::setUp();
		$this->lessc = $this->getMock( 'lessc' );
		$this->lessResourceValidator = new LESSResourceValidator( $this->lessc );
		$this->resource = '/path/to/resource.less';
		$this->resources = array(
			$this->resource,
			'/path/to/another/resource.less',
		);
		$this->resourceLoaderModule = $this->getMockBuilder( 'ResourceLoaderFileModule' )
			->disableOriginalConstructor()
			->getMock();
	}

	public function test_it_should_implement_the_ResourceValidator_interface() {
		$this->assertTrue( $this->lessResourceValidator instanceof ResourceValidator );
	}

	public function test_validateResource_should_call_compileFile() {
		$this->lessc->expects( $this->once() )
			->method( 'compileFile' )
			->with( $this->resource );
		$this->lessResourceValidator->validateResource( $this->resource );
	}

	public function test_validateResource_should_return_a_success_when_the_resource_is_valid() {
		$this->whenTheResourceIsValid( $this->resource );
		$success = ResourceValidationResult::success();
		$this->assertEquals( $success, $this->lessResourceValidator->validateResource( $this->resource ) );
	}

	public function test_validateResource_should_return_a_failure_when_the_resource_is_invalid() {
		$this->whenTheResourceIsInvalid( $this->resource );
		// XXX (phuedx, 2014-02-03) Indirectly asserts that validation
		// errors are associated with the files that are invalid.
		$failure = ResourceValidationResult::failure( array(
			$this->resource => new Exception( 'Error' ),
		) );
		$this->assertEquals( $failure, $this->lessResourceValidator->validateResource( $this->resource ) );
	}

	public function test_validateResources_should_validate_all_of_the_resources() {
		foreach ( $this->resources as $i => $resource ) {
			$this->lessc->expects( $this->at( $i ) )
				->method( 'compileFile' )
				->with( $resource );
		}
		$this->lessResourceValidator->validateResources( $this->resources );
	}

	public function test_validateResources_should_return_a_success_when_all_of_the_resources_are_valid() {
		foreach ( $this->resources as $resource ) {
			$this->whenTheResourceIsValid( $resource );
		}
		$success = ResourceValidationResult::success();
		$this->assertEquals( $success, $this->lessResourceValidator->validateResources( $this->resources ) );
	}

	public function test_validateResources_should_return_a_failure_when_one_of_the_resources_is_invalid() {
		$this->whenTheResourceIsInvalid( $this->resources[1] );
		$failure = $this->lessResourceValidator->validateResources( $this->resources );
		$this->assertFalse( $failure->isSuccess() );
	}

	public function test_validateResources_should_return_a_failure_with_validation_errors_for_all_invalid_resources() {
		foreach ( $this->resources as $i => $resource ) {
			$this->whenTheResourceIsInvalid( $resource, $i );
		}
		$failure = $this->lessResourceValidator->validateResources( $this->resources );
		$errors = $failure->getErrors();
		$expectedError = new Exception( 'Error' );
		foreach ( $this->resources as $resource ) {
			$this->assertEquals( $expectedError, $errors[ $resource ] );
		}
	}

	public function test_accept_should_return_false_when_the_resource_loader_module_doesnt_have_any_styles() {
		$this->assertFalse( $this->lessResourceValidator->accept( $this->resourceLoaderModule ) );
	}

	public function test_accept_should_call_getAllStyleFilesByLang() {
		$this->resourceLoaderModule->expects( $this->once() )
			->method( 'getAllStyleFilesByLang' )
			->with( 'less' );
		$this->lessResourceValidator->accept( $this->resourceLoaderModule );
	}

	public function test_accept_should_return_false_when_the_resource_loader_module_isnt_a_ResourceLoaderFileModule() {
		$resourceLoaderModule = $this->getMock( 'ResourceLoaderModule' );
		$this->assertFalse( $this->lessResourceValidator->accept( $resourceLoaderModule ) );
	}

	public function test_accept_should_return_true_when_the_resource_loader_module_has_a_less_stylesheet() {
		$this->resourceLoaderModule->expects( $this->once() )
			->method( 'getAllStyleFilesByLang' )
			->will( $this->returnValue( $this->resources ) );
		$this->assertTrue( $this->lessResourceValidator->accept( $this->resourceLoaderModule ) );
	}

	public function test_validateResourceLoaderModule_should_return_false_when_the_resource_loader_module_isnt_a_ResourceLoaderFileModule() {
		$resourceLoaderModule = $this->getMock( 'ResourceLoaderModule' );
		$result = $this->lessResourceValidator->validateResourceLoaderModule( $resourceLoaderModule );
		$this->assertFalse( $result );
	}

	public function test_validateResourceLoaderModule_should_call_getAllStyleFilesByLang() {
		$this->resourceLoaderModule->expects( $this->once() )
			->method( 'getAllStyleFilesByLang' )
			->with( 'less' );
		$this->lessResourceValidator->validateResourceLoaderModule( $this->resourceLoaderModule );
	}

	public function test_validateResourceLoaderModule_should_return_true_when_the_resource_loader_module_less_stylesheets_are_valid() {
		$this->resourceLoaderModule->expects( $this->once() )
			->method( 'getAllStyleFilesByLang' )
			->will( $this->returnValue( $this->resources ) );
		foreach ( $this->resources as $i => $file ) {
			$this->whenTheResourceIsValid( $file, $i );
		}
		$success = ResourceValidationResult::success();
		$result = $this->lessResourceValidator->validateResourceLoaderModule( $this->resourceLoaderModule );
		$this->assertEquals( $success, $result );
	}

	private function whenTheResourceIsValid( $resource, $at = null ) {
	}

	private function whenTheResourceIsInvalid( $resource, $at = null ) {
		$exception = new Exception( 'Error' );
		$invocationMatcher = $at === null ? $this->any() : $this->at( $at );
		$this->lessc->expects( $invocationMatcher )
			->method( 'compileFile' )
			->with( $resource )
			->will( $this->throwException( $exception ) );
	}
}
