<?php
/**
 * Sanity checks for making sure registered resources are sane.
 *
 * @file
 * @author Antoine Musso
 * @author Niklas Laxström
 * @author Santhosh Thottingal
 * @author Timo Tijhof
 * @copyright © 2012, Antoine Musso
 * @copyright © 2012, Niklas Laxström
 * @copyright © 2012, Santhosh Thottingal
 * @copyright © 2012, Timo Tijhof
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class ResourcesTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideResourceFiles
	 */
	public function testFileExistence( $filename, $module, $resource ) {
		$this->assertFileExists( $filename,
			"File '$resource' referenced by '$module' must exist."
		);
	}

	/**
	 * This ask the ResouceLoader for all registered files from modules
	 * created by ResourceLoaderFileModule (or one of its descendants).
	 *
	 *
	 * Since the raw data is stored in protected properties, we have to
	 * overrride this through ReflectionObject methods.
	 */
	public static function provideResourceFiles() {
		global $wgEnableJavaScriptTest;

		// Test existance of test suite files as well
		// (can't use setUp or setMwGlobals because providers are static)
		$live_wgEnableJavaScriptTest = $wgEnableJavaScriptTest;
		$wgEnableJavaScriptTest = true;

		// Array with arguments for the test function
		$cases = array();

		// Initialize ResourceLoader
		$rl = new ResourceLoader();

		// See also ResourceLoaderFileModule::__construct
		$filePathProps = array(
			// Lists of file paths
			'lists' => array(
				'scripts',
				'debugScripts',
				'loaderScripts',
				'styles',
			),

			// Collated lists of file paths
			'nested-lists' => array(
				'languageScripts',
				'skinScripts',
				'skinStyles',
			),
		);

		foreach ( $rl->getModuleNames() as $moduleName ) {
			$module = $rl->getModule( $moduleName );
			if ( !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$reflectedModule = new ReflectionObject( $module );

			$files = array();

			foreach ( $filePathProps['lists'] as $propName ) {
				$property = $reflectedModule->getProperty( $propName );
				$property->setAccessible( true );
				$list = $property->getValue( $module );
				foreach ( $list as $key => $value ) {
					// 'scripts' are numeral arrays.
					// 'styles' can be numeral or associative.
					// In case of associative the key is the file path
					// and the value is the 'media' attribute.
					if ( is_int( $key ) ) {
						$files[] = $value;
					} else {
						$files[] = $key;
					}
				}
			}

			foreach ( $filePathProps['nested-lists'] as $propName ) {
				$property = $reflectedModule->getProperty( $propName );
				$property->setAccessible( true );
				$lists = $property->getValue( $module );
				foreach ( $lists as $list ) {
					foreach ( $list as $key => $value ) {
						// We need the same filter as for 'lists',
						// due to 'skinStyles'.
						if ( is_int( $key ) ) {
							$files[] = $value;
						} else {
							$files[] = $key;
						}
					}
				}
			}

			// Get method for resolving the paths to full paths
			$method = $reflectedModule->getMethod( 'getLocalPath' );
			$method->setAccessible( true );

			// Populate cases
			foreach ( $files as $file ) {
				$cases[] = array(
					$method->invoke( $module, $file ),
					$module->getName(),
					$file,
				);
			}
		}

		// Restore settings
		$wgEnableJavaScriptTest = $live_wgEnableJavaScriptTest;

		return $cases;
	}
}
