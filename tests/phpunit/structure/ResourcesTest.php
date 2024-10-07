<?php

use JsonSchema\Validator;
use MediaWiki\MainConfigNames;
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
	 * does not find all such variations.
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

	public function testSchema() {
		$data = include __DIR__ . '/../../../resources/Resources.php';
		$schemaPath = __DIR__ . '/../../../docs/extension.schema.v2.json';

		// Replace inline functions with fake callables
		array_walk_recursive( $data, static function ( &$item, $key ) {
			if ( $item instanceof Closure ) {
				$item = 'Test::test';
			}
		} );
		// Convert PHP associative arrays to stdClass objects recursively
		$data = json_decode( json_encode( $data ) );

		$validator = new Validator;
		$validator->validate( $data, (object)[ '$ref' => 'file://' . $schemaPath . '#/properties/ResourceModules' ] );

		$this->assertEquals(
			[],
			$validator->getErrors(),
			'Found errors when validating Resources.php against the ResourceModules schema: ' .
				json_encode( $validator->getErrors(), JSON_PRETTY_PRINT )
		);
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
		$this->overrideConfigValues( [
			MainConfigNames::Logo => false,
			MainConfigNames::Logos => [],
		] );

		$data = self::getAllModules();

		// See also RL\FileModule::__construct
		$filePathProps = [
			// Lists of file paths
			'lists' => [
				'scripts',
				'debugScripts',
				'styles',
				'packageFiles',
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
				if ( $list === null ) {
					continue;
				}
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
		$services = MediaWikiServices::getInstance();
		$rl = $services->getResourceLoader();
		$skinFactory = $services->getSkinFactory();
		foreach ( array_keys( $skinFactory->getInstalledSkins() ) as $skin ) {
			foreach ( $rl->getModuleNames() as $moduleName ) {
				yield [ $moduleName, $skin ];
			}
		}
	}

	/**
	 * @dataProvider provideRespond
	 * @param string $moduleName
	 * @param string $skin
	 */
	public function testRespond( $moduleName, $skin ) {
		$rl = $this->getServiceContainer()->getResourceLoader();
		$module = $rl->getModule( $moduleName );
		if ( $module->shouldSkipStructureTest() ) {
			// Private modules cannot be served from load.php
			$this->assertTrue( true );
			return;
		}
		// Test only general (scripts) or only=styles responses.
		$only = $module->getType() === RL\Module::LOAD_STYLES ? 'styles' : null;
		$context = new RL\Context(
			$rl,
			new FauxRequest( [ 'modules' => $moduleName, 'only' => $only, 'skin' => $skin ] )
		);
		ob_start();
		$rl->respond( $context );
		ob_end_clean();
		$this->assertSame( [], $rl->getErrors() );
	}
}
