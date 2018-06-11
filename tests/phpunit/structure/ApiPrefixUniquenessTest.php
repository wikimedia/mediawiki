<?php

/**
 * Checks that all API query modules, core and extensions, have unique prefixes.
 *
 * @group API
 * @coversNothing
 */
class ApiPrefixUniquenessTest extends MediaWikiTestCase {

	public function testPrefixes() {
		$main = new ApiMain( new FauxRequest() );
		$query = new ApiQuery( $main, 'foo' );
		$moduleManager = $query->getModuleManager();

		$modules = $moduleManager->getNames();
		$prefixes = [];

		foreach ( $modules as $name ) {
			$module = $moduleManager->getModule( $name );
			$class = get_class( $module );

			$prefix = $module->getModulePrefix();
			if ( $prefix !== '' && isset( $prefixes[$prefix] ) /* HACK: T196962 */ && $prefix !== 'wbeu' ) {
				$this->fail( "Module prefix '{$prefix}' is shared between {$class} and {$prefixes[$prefix]}" );
			}
			$prefixes[$module->getModulePrefix()] = $class;
		}
		$this->assertTrue( true ); // dummy call to make this test non-incomplete
	}
}
