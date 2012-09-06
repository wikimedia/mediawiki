<?php
class ApiGeneratorTest extends MediaWikiTestCase {

	/**
	 * Helper to easily get an ApiQuery object instance
	 */
	function getApiQuery() {
		// Initialize an ApiQuery object to play with
		$main = new ApiMain( new FauxRequest() );
		return new ApiQuery( $main, 'foo', 'bar' );
	}

	/**
	 * Test whether all registered query modules which are subclasses of
	 * ApiQueryGeneratorBase are listed as being a generator. Registration is
	 * done:
	 *  - for core: add it to ApiQuery::$mQueryGenerators
	 *  - for extension: by adding to $wgAPIGeneratorModules
	 *
	 * @dataProvider provideApiquerygeneratorbaseChilds
	 */
	public function testApiquerygeneatorbaseModulesListedAsGenerators(
		$moduleName, $moduleClass
	) {
		$generators = $this->getApiQuery()->getGenerators();
		$this->assertArrayHasKey( $moduleName, $generators,
	  		"API module '$moduleName' of class '$moduleClass' (an ApiQueryGeneratorBase subclass) must be listed in ApiQuery::\$mQueryGenerators or added to \$wgAPIGeneratorModules."
		);
	}

	/**
	 * Returns API modules which are subclassing ApiQueryGeneratorBase.
	 * Case format is:
	 * 	(moduleName, moduleClass)
	 */
	public function provideApiquerygeneratorbaseChilds() {
		$cases = array();
		$modules = $this->getApiQuery()->getModules();
		foreach( $modules as $moduleName => $moduleClass ) {
			if( !is_subclass_of( $moduleClass, 'ApiQueryGeneratorBase' ) ) {
				continue;
			}
			$cases[] = array( $moduleName, $moduleClass );
		}
		return $cases;
	}

	/**
	 * @dataProvider provideListedApiqueryGenerators
	 */
	public function testGeneratorsAreApiquerygeneratorbaseSubclasses(
		$generatorName, $generatorClass
	) {
		$modules = $this->getApiQuery()->getModules();
		$this->assertArrayHasKey( $generatorName, $modules,
			"Class '$generatorClass' of generator '$generatorName' must be a subclass of 'ApiQueryGeneratorBase'. Listed either in ApiQuery::\$mQueryGenerators or in \$wgAPIGeneratorModules."
		);

	}

	/**
	 * Return ApiQuery generators, either listed in ApiQuery or registered
	 * via wgAPIGeneratorModules.
	 * Case format is:
	 *  (moduleName, $moduleClass).
	 */
	public function provideListedApiqueryGenerators() {
		$cases = array();
		$generators = $this->getApiQuery()->getGenerators();
		foreach( $generators as $generatorName => $generatorClass ) {
			$cases[] = array( $generatorName, $generatorClass );
		}
		return $cases;
	}

}
