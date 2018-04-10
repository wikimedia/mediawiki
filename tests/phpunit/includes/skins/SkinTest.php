<?php

class TestSkin extends Skin {
	public function outputPage( OutputPage $out = null ) {}
	public function setupSkinUserCss( OutputPage $out ) {}
}

class TestSkinStyleModules extends TestSkin {
	public function getDefaultModules() {
		$modules = parent::getDefaultModules();
		$modules['styles']['foo'] = 'bar';
		return $modules;
	}
}

class SkinTest extends MediaWikiTestCase {

	/**
	 * @covers Skin::getDefaultModules
	 */
	public function testGetDefaultModules() {
		$skin = new TestSkin();
		$modules = $skin->getDefaultModules();
		$skinStyle = new TestSkinStyleModules();
		$modulesStyle = $skinStyle->getDefaultModules();
		$this->assertTrue( isset( $modules['core'] ), 'core key is set by default' );
		$this->assertTrue( isset( $modules['styles'] ), 'style key is set by default' );
		$this->assertTrue( isset( $modulesStyle['styles']['foo'] ), 'foo style key is set by override' );
	}
}
