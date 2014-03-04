<?php

class LESSResourceValidator implements ResourceValidator {
	protected $lessCompiler;

	public function __construct( lessc $lessCompiler ) {
		$this->lessCompiler = $lessCompiler;
	}

	public function validateResource( $resource ) {
		try {
			$this->lessCompiler->compileFile( $resource );
		} catch ( Exception $e ) {
			return ResourceValidationResult::failure( array( $resource => $e ) );
		}

		return ResourceValidationResult::success();
	}

	public function validateResources( $resources ) {
		$result = ResourceValidationResult::success();

		foreach ( (array)$resources as $resource ) {
			$result = $result->merge( $this->validateResource( $resource ) );
		}

		return $result;
	}

	public function accept( ResourceLoaderModule $resourceLoaderModule ) {
		if ( !( $resourceLoaderModule instanceof ResourceLoaderFileModule ) ) {
			return false;
		}
		return (bool) $resourceLoaderModule->getAllStyleFilesByLang( 'less' );
	}

	public function validateResourceLoaderModule( ResourceLoaderModule $resourceLoaderModule ) {
		if ( !( $resourceLoaderModule instanceof ResourceLoaderFileModule ) ) {
			return false;
		}
		$styleFiles = $resourceLoaderModule->getAllStyleFilesByLang( 'less' );
		return $this->validateResources( $styleFiles );
	}
}
