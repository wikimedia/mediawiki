<?php

class ApiGeneratorTest extends MediaWikiTestCase {

	/**
	 * Tests whether all modules listed as a Generator in ApiQuery
	 * are actually a generator.
	 * Tests user additions to $wgAPIGeneratorModules aswell.
	 */
	public function testIfGenerator() {
		$main = new ApiMain( new FauxRequest() );
		$query = new ApiQuery( $main, 'foo', 'bar' );
		foreach( $query->getGenerators() as $moduleClass ) {
			if ( !is_subclass_of( $moduleClass, 'ApiQueryGeneratorBase' ) ) {
				$this->fail( "'{$moduleClass}' is not a subclass of ApiQueryGeneratorBase" );
			}
		}
		$this->assertTrue( true );
	}

	/**
	 * Tests whether all registered query modules which are subclasses of ApiQueryGeneratorBase
	 * are listed as being a generator.
	 */
	public function testForMissingGenerator() {
		$main = new ApiMain( new FauxRequest() );
		$query = new ApiQuery( $main, 'foo', 'bar' );
		$generators = $query->getGenerators();
		$modules = $query->getModules();
		foreach ( $modules as $moduleName => $moduleClass ) {
			if ( is_subclass_of( $moduleClass, 'ApiQueryGeneratorBase' ) && !isset( $generators[$moduleName] ) ) {
				$this->fail( "'{$moduleClass}' is a subclass of ApiQueryGeneratorBase, but isn't listed as being a generator" );
			}
		}
		$this->assertTrue( true );
	}
}
