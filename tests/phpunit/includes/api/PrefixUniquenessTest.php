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
		$modules = $query->getModuleManager()->getNamesWithClasses();
		$prefixes = array();

		foreach ( $modules as $name => $class ) {
			$module = new $class( $main, $name );
			$prefix = $module->getModulePrefix();
			if ( isset( $prefixes[$prefix] ) ) {
				$this->fail( "Module prefix '{$prefix}' is shared between {$class} and {$prefixes[$prefix]}" );
			}
			$prefixes[$module->getModulePrefix()] = $class;
		}
		$this->assertTrue( true ); // dummy call to make this test non-incomplete
	}
}
