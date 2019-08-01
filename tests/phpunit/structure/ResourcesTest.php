<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * Sanity checks for making sure registered resources are sane.
 *
 * @author Antoine Musso
 * @author Niklas Laxström
 * @author Santhosh Thottingal
 * @copyright © 2012, Antoine Musso
 * @copyright © 2012, Niklas Laxström
 * @copyright © 2012, Santhosh Thottingal
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
	 * Verify that all modules specified as dependencies of other modules actually
	 * exist and are not illegal.
	 *
	 * @todo Modules can dynamically choose dependencies based on context. This method
	 * does not find all such variations. The same applies to testUnsatisfiableDependencies().
	 */
	public function testValidDependencies() {
		$data = self::getAllModules();
		$knownDeps = array_keys( $data['modules'] );
		$illegalDeps = [ 'startup' ];

		// Avoid an assert for each module to keep the test fast.
		// Instead, perform a single assertion against everything at once.
		// When all is good, actual/expected are both empty arrays.
		// When we find issues, add the violations to 'actual' and add an empty
		// key to 'expected'. These keys in expected are because the PHPUnit diff
		// (as of 6.5) only goes one level deep.
		$actualUnknown = [];
		$expectedUnknown = [];
		$actualIllegal = [];
		$expectedIllegal = [];

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			foreach ( $module->getDependencies( $data['context'] ) as $dep ) {
				if ( !in_array( $dep, $knownDeps, true ) ) {
					$actualUnknown[$moduleName][] = $dep;
					$expectedUnknown[$moduleName] = [];
				}
				if ( in_array( $dep, $illegalDeps, true ) ) {
					$actualIllegal[$moduleName][] = $dep;
					$expectedIllegal[$moduleName] = [];
				}
			}
		}
		$this->assertEquals( $expectedUnknown, $actualUnknown, 'Dependencies that do not exist' );
		$this->assertEquals( $expectedIllegal, $actualIllegal, 'Dependencies that are not legal' );
	}

	/**
	 * Verify that all specified messages actually exist.
	 */
	public function testMissingMessages() {
		$data = self::getAllModules();
		$lang = Language::factory( 'en' );

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			foreach ( $module->getMessages() as $msgKey ) {
				$this->assertTrue(
					wfMessage( $msgKey )->useDatabase( false )->inLanguage( $lang )->exists(),
					"Message '$msgKey' required by '$moduleName' must exist"
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

		/** @var ResourceLoaderModule $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			$moduleTargets = $module->getTargets();
			foreach ( $module->getDependencies( $data['context'] ) as $dep ) {
				if ( !isset( $data['modules'][$dep] ) ) {
					// Missing dependencies reported by testMissingDependencies
					continue;
				}
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
	 * CSSMin::getLocalFileReferences should ignore url(...) expressions
	 * that have been commented out.
	 */
	public function testCommentedLocalFileReferences() {
		$basepath = __DIR__ . '/../data/css/';
		$css = file_get_contents( $basepath . 'comments.css' );
		$files = CSSMin::getLocalFileReferences( $css, $basepath );
		$expected = [ $basepath . 'not-commented.gif' ];
		$this->assertSame(
			$expected,
			$files,
			'Url(...) expression in comment should be omitted.'
		);
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

		// Get main ResourceLoader
		$rl = MediaWikiServices::getInstance()->getResourceLoader();

		$modules = [];

		foreach ( $rl->getModuleNames() as $moduleName ) {
			$modules[$moduleName] = $rl->getModule( $moduleName );
		}

		// Restore settings
		$wgEnableJavaScriptTest = $org_wgEnableJavaScriptTest;

		return [
			'modules' => $modules,
			'resourceloader' => $rl,
			'context' => new ResourceLoaderContext( $rl, new FauxRequest() )
		];
	}

	/**
	 * Get all stylesheet files from modules that are an instance of
	 * ResourceLoaderFileModule (or one of its subclasses).
	 */
	public static function provideMediaStylesheets() {
		$data = self::getAllModules();
		$cases = [];

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
						$cases[] = [
							$moduleName,
							$media,
							$file,
							// XXX: Wrapped in an object to keep it out of PHPUnit output
							(object)[
								'cssText' => $readStyleFile->invoke(
									$module,
									$file,
									$flip,
									$data['context']
								)
							],
						];
					}
				}
			}
		}

		return $cases;
	}

	/**
	 * Get all resource files from modules that are an instance of
	 * ResourceLoaderFileModule (or one of its subclasses).
	 */
	public static function provideResourceFiles() {
		$data = self::getAllModules();
		$cases = [];

		// See also ResourceLoaderFileModule::__construct
		$filePathProps = [
			// Lists of file paths
			'lists' => [
				'scripts',
				'debugScripts',
				'styles',
			],

			// Collated lists of file paths
			'nested-lists' => [
				'languageScripts',
				'skinScripts',
				'skinStyles',
			],
		];

		foreach ( $data['modules'] as $moduleName => $module ) {
			if ( !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$moduleProxy = TestingAccessWrapper::newFromObject( $module );

			$files = [];

			foreach ( $filePathProps['lists'] as $propName ) {
				$list = $moduleProxy->$propName;
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
				$lists = $moduleProxy->$propName;
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

			// Populate cases
			foreach ( $files as $file ) {
				$cases[] = [
					$moduleProxy->getLocalPath( $file ),
					$moduleName,
					( $file instanceof ResourceLoaderFilePath ? $file->getPath() : $file ),
				];
			}

			// To populate missingLocalFileRefs. Not sure how sane this is inside this test...
			$moduleProxy->readStyleFiles(
				$module->getStyleFiles( $data['context'] ),
				$module->getFlip( $data['context'] ),
				$data['context']
			);

			$missingLocalFileRefs = $moduleProxy->missingLocalFileRefs;

			foreach ( $missingLocalFileRefs as $file ) {
				$cases[] = [
					$file,
					$moduleName,
					$file,
				];
			}
		}

		return $cases;
	}
}
