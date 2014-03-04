<?php

class ResourceValidationCoordinator {
	public function __construct( ResourceLoader $resourceLoader ) {
		$this->resourceLoader = $resourceLoader;
	}

	public function validateAllResources( ResourceValidator $resourceValidator ) {
		$result = ResourceValidationResult::success();

		foreach ( $this->resourceLoader->getModuleNames() as $moduleName ) {
			$module = $this->resourceLoader->getModule( $moduleName );
			$result = $result->merge(
				$resourceValidator->validateResourceLoaderModule( $module )
			);
		}

		return $result;
	}
}
