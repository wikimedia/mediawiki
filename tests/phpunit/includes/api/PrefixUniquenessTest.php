<?php

/**
 * Checks that all API query modules, core and extensions, have unique prefixes.
 *
 * @group API
 */
class PrefixUniquenessTest extends MediaWikiTestCase {

	public function testPrefixes() {
		$main = new ApiMain( new FauxRequest() );
		$query = new ApiQuery( $main, 'foo', 'bar' );
		$moduleManager = $query->getModuleManager();

		$modules = $moduleManager->getNames();
		$prefixes = array();

		foreach ( $modules as $name ) {
			$module = $moduleManager->getModule( $name );
			$class = get_class( $module );

			$prefix = $module->getModulePrefix();
			if ( $prefix !== '' && isset( $prefixes[$prefix] ) ) {
				$this->fail( "Module prefix '{$prefix}' is shared between {$class} and {$prefixes[$prefix]}" );
			}
			$prefixes[$module->getModulePrefix()] = $class;
		}
		$this->assertTrue( true ); // dummy call to make this test non-incomplete
	}
}
