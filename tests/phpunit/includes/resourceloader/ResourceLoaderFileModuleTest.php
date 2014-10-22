<?php

class ResourceLoaderFileModuleTest extends ResourceLoaderTestCase {

	protected function setUp() {
		parent::setUp();

		// The return value of the closure shouldn't matter since this test should
		// never call it
		SkinFactory::getDefaultInstance()->register(
			'fakeskin',
			'FakeSkin',
			function () {
			}
		);
	}

	/**
	 * @covers ResourceLoaderFileModule::getAllSkinStyleFiles
	 */
	public function testGetAllSkinStyleFiles() {
		$baseParams = array(
			'scripts' => array(
				'foo.js',
				'bar.js',
			),
			'styles' => array(
				'foo.css',
				'bar.css' => array( 'media' => 'print' ),
				'screen.less' => array( 'media' => 'screen' ),
				'screen-query.css' => array( 'media' => 'screen and (min-width: 400px)' ),
			),
			'skinStyles' => array(
				'default' => 'quux-fallback.less',
				'fakeskin' => array(
					'baz-vector.css',
					'quux-vector.less',
				),
			),
			'messages' => array(
				'hello',
				'world',
			),
		);

		$module = new ResourceLoaderFileModule( $baseParams );

		$this->assertEquals(
			array(
				'foo.css',
				'baz-vector.css',
				'quux-vector.less',
				'quux-fallback.less',
				'bar.css',
				'screen.less',
				'screen-query.css',
			),
			array_map( 'basename', $module->getAllStyleFiles() )
		);
	}
}
