<?php

/**
 * @group ResourceLoader
 */
class ResourceLoaderOOUIImageModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderOOUIImageModule::loadFromDefinition
	 */
	public function testNonDefaultSkin() {
		$module = new ResourceLoaderOOUIImageModule( [
			'class' => ResourceLoaderOOUIImageModule::class,
			'name' => 'icons',
			'rootPath' => 'tests/phpunit/data/resourceloader/oouiimagemodule',
		] );

		// Pretend that 'fakemonobook' is a real skin using the Apex theme
		SkinFactory::getDefaultInstance()->register(
			'fakemonobook',
			'FakeMonoBook',
			function () {
			}
		);
		$r = new ReflectionMethod( ExtensionRegistry::class, 'exportExtractedData' );
		$r->setAccessible( true );
		$r->invoke( ExtensionRegistry::getInstance(), [
			'globals' => [],
			'defines' => [],
			'callbacks' => [],
			'credits' => [],
			'autoloaderPaths' => [],
			'attributes' => [
				'SkinOOUIThemes' => [
					'fakemonobook' => 'Apex',
				],
			],
		] );

		$styles = $module->getStyles( $this->getResourceLoaderContext( [ 'skin' => 'fakemonobook' ] ) );
		$this->assertRegExp(
			'/stu-apex/',
			$styles['all'],
			'Generated styles use the non-default image (embed)'
		);
		$this->assertRegExp(
			'/fakemonobook/',
			$styles['all'],
			'Generated styles use the non-default image (link)'
		);

		$styles = $module->getStyles( $this->getResourceLoaderContext() );
		$this->assertRegExp(
			'/stu-wikimediaui/',
			$styles['all'],
			'Generated styles use the default image (embed)'
		);
		$this->assertRegExp(
			'/vector/',
			$styles['all'],
			'Generated styles use the default image (link)'
		);
	}

}
