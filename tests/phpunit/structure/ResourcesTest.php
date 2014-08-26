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
	 * @dataProvider provideMediaStylesheets
	 */
	public function testStyleMedia( $moduleName, $media, $filename, $css ) {
		$cssText = CSSMin::minify( $css->cssText );

		$this->assertTrue(
			strpos( $cssText, '@media' ) === false,
			'Stylesheets should not both specify "media" and contain @media'
		);
	}

	/**
	 * Verify that nothing explicitly depends on the 'jquery' and 'mediawiki' modules.
	 * They are always loaded, depending on them is unsupported and leads to unexpected behaviour.
	 */
	public function testIllegalDependencies() {
		$data = self::getAllModules();
		$illegalDeps = array( 'jquery', 'mediawiki' );

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			foreach ( $illegalDeps as $illegalDep ) {
				$this->assertNotContains(
					$illegalDep,
					$module->getDependencies(),
					"Module '$moduleName' must not depend on '$illegalDep'"
				);
			}
		}
	}

	/**
	 * Verify that all modules specified as dependencies of other modules actually exist.
	 */
	public function testMissingDependencies() {
		$data = self::getAllModules();
		$validDeps = array_keys( $data['modules'] );

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			foreach ( $module->getDependencies() as $dep ) {
				$this->assertContains(
					$dep,
					$validDeps,
					"The module '$dep' required by '$moduleName' must exist"
				);
			}
		}
	}

	/**
	 * Verify that all dependencies of all modules are always satisfiable with the 'targets' defined
	 * for the involved modules.
	 *
	 * Example: A depends on B. A has targets: mobile, desktop. B has targets: desktop. Therefore the
	 * dependency is sometimes unsatisfiable: it's impossible to load module A on mobile.
	 */
	public function testUnsatisfiableDependencies() {
		$data = self::getAllModules();
		$validDeps = array_keys( $data['modules'] );

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			$moduleTargets = $module->getTargets();
			foreach ( $module->getDependencies() as $dep ) {
				$targets = $data['modules'][$dep]->getTargets();
				foreach ( $moduleTargets as $moduleTarget ) {
					$this->assertContains(
						$moduleTarget,
						$targets,
						"The module '$moduleName' must not have target '$moduleTarget' "
							. "because its dependency '$dep' does not have it"
					);
				}
			}
		}
	}

	/**
	 * Get all registered modules from ResouceLoader.
	 * @return array
	 */
	protected static function getAllModules() {
		global $wgEnableJavaScriptTest;

		// Test existance of test suite files as well
		// (can't use setUp or setMwGlobals because providers are static)
		$org_wgEnableJavaScriptTest = $wgEnableJavaScriptTest;
		$wgEnableJavaScriptTest = true;

		// Initialize ResourceLoader
		$rl = new ResourceLoader();

		$modules = array();

		foreach ( $rl->getModuleNames() as $moduleName ) {
			$modules[$moduleName] = $rl->getModule( $moduleName );
		}

		// Restore settings
		$wgEnableJavaScriptTest = $org_wgEnableJavaScriptTest;

		return array(
			'modules' => $modules,
			'resourceloader' => $rl,
			'context' => new ResourceLoaderContext( $rl, new FauxRequest() )
		);
	}

	/**
	 * Get all stylesheet files from modules that are an instance of
	 * ResourceLoaderFileModule (or one of its subclasses).
	 */
	public static function provideMediaStylesheets() {
		$data = self::getAllModules();
		$cases = array();

		foreach ( $data['modules'] as $moduleName => $module ) {
			if ( !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$reflectedModule = new ReflectionObject( $module );

			$getStyleFiles = $reflectedModule->getMethod( 'getStyleFiles' );
			$getStyleFiles->setAccessible( true );

			$readStyleFile = $reflectedModule->getMethod( 'readStyleFile' );
			$readStyleFile->setAccessible( true );

			$styleFiles = $getStyleFiles->invoke( $module, $data['context'] );

			$flip = $module->getFlip( $data['context'] );

			foreach ( $styleFiles as $media => $files ) {
				if ( $media && $media !== 'all' ) {
					foreach ( $files as $file ) {
						$cases[] = array(
							$moduleName,
							$media,
							$file,
							// XXX: Wrapped in an object to keep it out of PHPUnit output
							(object)array( 'cssText' => $readStyleFile->invoke( $module, $file, $flip ) ),
						);
					}
				}
			}
		}

		return $cases;
	}

	/**
	 * Get all resource files from modules that are an instance of
	 * ResourceLoaderFileModule (or one of its subclasses).
	 *
	 * Since the raw data is stored in protected properties, we have to
	 * overrride this through ReflectionObject methods.
	 */
	public static function provideResourceFiles() {
		$data = self::getAllModules();
		$cases = array();

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

		foreach ( $data['modules'] as $moduleName => $module ) {
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
					$moduleName,
					( $file instanceof ResourceLoaderFilePath ? $file->getPath() : $file ),
				);
			}
		}

		return $cases;
	}
}
