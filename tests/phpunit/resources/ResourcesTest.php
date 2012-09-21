<?php
/**
 * Sanity checks for making sure registered resources are sane.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2012, Niklas Laxström
 * @copyright Copyright © 2012, Antoine Musso
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class ResourcesTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideResourceFiles
	 */
	public function testFileExistence( $filename, $module, $resource ) {
		$this->assertFileExists( $filename,
			"Resource '$resource' referenced by ResourceLoader module '$module' must be found on local disk."
		);
	}


	/**
	 * This ask the Resouce Loader for any registered files registered via
	 * ResourceLoaderFileModule or one of its descendant. Other modules
	 * providing files will need to be implemented there.
	 *
	 * The list of cases is very simple, consisting of:
	 *  - a filepath on local disk
	 *  - the resource module name
	 *  - the name of the resource in the module
	 *
	 * 	Will only provide files for modules descending from
	 * 	ResourceLoaderFileModule.
	 *
	 *  Since some of that class methods are protected, we have to make them
	 *  accessible explicitly which add a bit of complexity.
	 */
	public function provideResourceFiles() {
		# Array we will return:
		$cases = array();

		# Initialize the resource loader:
		$rl = new ResourceLoader();
		$RLContext = ResourceLoaderContext::newDummyContext();

		foreach ( $rl->getModuleNames() as $moduleName ) {

			$module = $rl->getModule( $moduleName );
			if ( ! $module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$obj = new ReflectionObject( $module );

			$method = $obj->getMethod( 'getScriptFiles' );
			$method->setAccessible( true );
			$moduleScripts = $method->invoke( $module, $RLContext );

			$method = $obj->getMethod( 'getStyleFiles' );
			$method->setAccessible( true );
			$styles = $method->invoke( $module, $RLContext );
			# Styles uses: medias => files
			$moduleStyles = array();
			foreach ( $styles as $media => $perMediaFiles ) {
				$moduleStyles = array_merge( $moduleStyles , $perMediaFiles );
			}

			# All declared resources for this module
			$res = array_merge( $moduleScripts, $moduleStyles );

			# make the module resolve the paths as local paths
			$getLocalPath = $obj->getMethod( 'getLocalPath' );
			$getLocalPath->setAccessible( true );

			foreach ( $res as $resourceName ) {
				$cases[] = array(
					$getLocalPath->invoke( $module, $resourceName ),
					$module->getName(),
					$resourceName,
				);
			}

			$property = $obj->getProperty( 'languageScripts' );
			$property->setAccessible( true );
			$languages = $property->getValue( $module ) ;
			foreach ( $languages as  $language ) {
				foreach ( $language as $script ) {
					$cases[] = array(
						$getLocalPath->invoke( $module, $script ),
						$module->getName(),
						$script
					);
				}
			}

		}

		return $cases;
	}

}
