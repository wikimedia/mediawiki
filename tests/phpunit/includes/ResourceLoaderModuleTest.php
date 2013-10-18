<?php

class ResourceLoaderModuleTest extends MediaWikiTestCase {

	public function testDefinitionSummary() {
		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$hash = json_encode( $module->getDefinitionSummary() );

		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'mediawiki', 'jquery' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary() ),
			'Order of dependencies is insignificant'
		);

		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'foo.js', 'bar.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'world', 'hello' ),
		) );

		$this->assertEquals(
			$hash,
			json_encode( $module->getDefinitionSummary() ),
			'Order of messages is insignificant'
		);

		$module = new ResourceLoaderFileModule( array(
			'scripts' => array( 'bar.js', 'foo.js' ),
			'dependencies' => array( 'jquery', 'mediawiki' ),
			'messages' => array( 'hello', 'world' ),
		) );

		$this->assertNotEquals(
			$hash,
			json_encode( $module->getDefinitionSummary() ),
			'Order of scripts is significant'
		);
	}
}
