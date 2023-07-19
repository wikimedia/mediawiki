<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader as RL;
use Wikimedia\Minify\CSSMin;
use Wikimedia\TestingAccessWrapper;

/**
 * Checks for making sure registered resources are sensible.
 *
 * @author Antoine Musso
 * @author Niklas Laxström
 * @author Santhosh Thottingal
 * @copyright © 2012, Antoine Musso
 * @copyright © 2012, Niklas Laxström
 * @copyright © 2012, Santhosh Thottingal
 *
 * @coversNothing
 * @group Database
 */
class ResourcesTest extends MediaWikiIntegrationTestCase {

	public function testStyleMedia() {
		foreach ( self::provideMediaStylesheets() as [ $moduleName, $media, $filename, $css ] ) {
			$cssText = CSSMin::minify( $css->cssText );

			$this->assertStringNotContainsString(
				'@media',
				$cssText,
				'Stylesheets should not both specify "media" and contain @media'
			);
		}
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
		$illegalDeps = [ 'startup' ];
		// Can't depend on modules in the `noscript` group, find all such module names
		// to add to $illegalDeps. See T291735
		/** @var RL\Module $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			if ( $module->getGroup() === 'noscript' ) {
				$illegalDeps[] = $moduleName;
			}
		}

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

		/** @var RL\Module $module */
		foreach ( $data['modules'] as $moduleName => $module ) {
			foreach ( $module->getDependencies( $data['context'] ) as $dep ) {
				if ( !isset( $data['modules'][$dep] ) ) {
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
		$lang = MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );

		/** @var RL\Module $module */
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
	 * Verify that dependencies of all modules are actually registered in the same client context.
	 *
	 * Example:
	 * - A depends on B. A has targets: mobile, desktop. B has targets: desktop. Therefore the
	 *   dependency is sometimes unregistered: it's impossible to load module A on mobile.
	 */
	public function testUnsatisfiableDependencies() {
		$data = self::getAllModules();

		/** @var RL\Module $module */
		$incompatibleTargetNames = [];
		$targetsErrMsg = '';
		foreach ( $data['modules'] as $moduleName => $module ) {
			$depNames = $module->getDependencies( $data['context'] );
			$moduleTargets = $module->getTargets();

			foreach ( $depNames as $depName ) {
				$dep = $data['modules'][$depName] ?? null;
				if ( !$dep ) {
					// Missing dependencies reported by testMissingDependencies
					continue;
				}
				if ( $moduleTargets === [ 'test' ] ) {
					// Target filter does not apply under tests, which may include
					// both mobile-only and desktop-only dependencies.
					continue;
				}
				$targets = $dep->getTargets();
				foreach ( $moduleTargets as $moduleTarget ) {
					if ( !in_array( $moduleTarget, $targets ) ) {
						$incompatibleTargetNames[] = $moduleName;
						$targetsErrMsg .= "* The module '$moduleName' must not have target '$moduleTarget' "
								. "because its dependency '$depName' does not have it\n";
					}
				}
			}
		}
		$this->assertEquals( [], $incompatibleTargetNames, $targetsErrMsg );
	}

	/**
	 * Verify that dependencies of all modules are actually registered in the same client context.
	 *
	 * Example:
	 * - A depends on B. A has targets: mobile, desktop. B has targets: desktop. Therefore the
	 *   dependency is sometimes unregistered: it's impossible to load module A on mobile.
	 * - A depends on B. B has requiresES6=true but A does not. In some browsers, B will be
	 *   unregistered at startup and thus impossible to satisfy as dependency.
	 */
	public function testRedundantTargets() {
		$targetsBad = [];
		$data = self::getAllModules();

		// This makes sure that new modules are not added in a way that goes against
		// the current plan to dismantle the targets system.
		// Modules should only be removed from the list, not added.
		$knownExceptions = [];
		foreach ( $data['modules'] as $moduleName => $module ) {
			$definedTargets = $module->getTargets();
			if (
				!in_array( $moduleName, $knownExceptions ) &&
				!str_starts_with( $moduleName, 'test.' ) &&
				(
					!in_array( 'desktop', $definedTargets ) ||
					!in_array( 'mobile', $definedTargets )
				)
			) {
				$targetsBad[] = $moduleName;
			}
		}
		$this->assertEquals( [], $targetsBad,
			'All modules should load on both mobile and desktop target. '
			. 'The following modules have redundant targets definitions:' . implode( ' ', $targetsBad )
		);
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
			'context' => new RL\Context( $rl, new FauxRequest() )
		];
	}

	/**
	 * Get all stylesheet files from modules that are an instance of
	 * RL\FileModule (or one of its subclasses).
	 */
	public static function provideMediaStylesheets() {
		$data = self::getAllModules();
		$context = $data['context'];

		foreach ( $data['modules'] as $moduleName => $module ) {
			if ( !$module instanceof RL\FileModule ) {
				continue;
			}

			$moduleProxy = TestingAccessWrapper::newFromObject( $module );

			$styleFiles = $moduleProxy->getStyleFiles( $context );

			foreach ( $styleFiles as $media => $files ) {
				if ( $media && $media !== 'all' ) {
					foreach ( $files as $file ) {
						yield [
							$moduleName,
							$media,
							$file,
							// XXX: Wrapped in an object to keep it out of PHPUnit output
							(object)[
								'cssText' => $moduleProxy->readStyleFile( $file, $context )
							],
						];
					}
				}
			}
		}
	}

	/**
	 * Check all resource files from RL\FileModule modules.
	 */
	public function testResourceFiles() {
		$data = self::getAllModules();

		// See also RL\FileModule::__construct
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
			if ( !$module instanceof RL\FileModule ) {
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

			foreach ( $files as $key => $file ) {
				$fileInfo = $moduleProxy->expandFileInfo( $data['context'], $file, "files[$key]" );
				if ( !isset( $fileInfo['filePath'] ) ) {
					continue;
				}
				$relativePath = $fileInfo['filePath']->getPath();
				$localPath = $fileInfo['filePath']->getLocalPath();
				$this->assertFileExists(
					$localPath,
					"File '$relativePath' referenced by '$moduleName' must exist."
				);
			}

			// To populate missingLocalFileRefs. Not sure how sensible this is inside this test...
			$moduleProxy->readStyleFiles(
				$module->getStyleFiles( $data['context'] ),
				$data['context']
			);

			$missingLocalFileRefs = $moduleProxy->missingLocalFileRefs;

			foreach ( $missingLocalFileRefs as $file ) {
				$this->assertFileExists(
					$file,
					"File '$file' referenced by '$moduleName' must exist."
				);
			}
		}
	}

	/**
	 * Check all image files from RL\ImageModule modules.
	 */
	public function testImageFiles() {
		$data = self::getAllModules();

		foreach ( $data['modules'] as $moduleName => $module ) {
			if ( !$module instanceof RL\ImageModule ) {
				continue;
			}

			$imagesFiles = $module->getImages( $data['context'] );
			foreach ( $imagesFiles as $file ) {
				$relativePath = $file->getName();
				$this->assertFileExists(
					$file->getPath( $data['context'] ),
					"File '$relativePath' referenced by '$moduleName' must exist."
				);
			}
		}
	}

	public static function provideRespond() {
		$rl = MediaWikiServices::getInstance()->getResourceLoader();
		foreach ( $rl->getModuleNames() as $moduleName ) {
			yield $moduleName => [ $moduleName ];
		}
	}

	/**
	 * @dataProvider provideRespond
	 * @param string $moduleName
	 */
	public function testRespond( $moduleName ) {
		$rl = $this->getServiceContainer()->getResourceLoader();
		$module = $rl->getModule( $moduleName );
		if ( $module->getGroup() === RL\Module::GROUP_PRIVATE ) {
			// Private modules cannot be served from load.php
			$this->assertTrue( true );
			return;
		}
		// Test only general (scripts) or only=styles responses.
		$only = $module->getType() === RL\Module::LOAD_STYLES ? 'styles' : null;
		$context = new RL\Context(
			$rl,
			new FauxRequest( [ 'modules' => $moduleName, 'only' => $only ] )
		);
		ob_start();
		$rl->respond( $context );
		ob_end_clean();
		$this->assertSame( [], $rl->getErrors() );
	}
}
