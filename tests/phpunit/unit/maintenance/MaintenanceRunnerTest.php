<?php

use MediaWiki\Extension\FakeExtension\Maintenance\FakeScript;
use MediaWiki\Maintenance\MaintenanceRunner;
use MediaWiki\Maintenance\Version;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Maintenance\MaintenanceRunner
 */
class MaintenanceRunnerTest extends TestCase {
	use MediaWikiCoversValidator;

	public const FIXTURE_DIRECTORY = MW_INSTALL_PATH . '/tests/phpunit/data/MaintenanceRunner';

	/** @var string */
	private $oldWorkDir;

	/**
	 * @before
	 */
	public function workDirSetUp() {
		// Needed for testing the resolution of paths relative to CWD
		$this->oldWorkDir = getcwd();
		chdir( self::FIXTURE_DIRECTORY );
	}

	/**
	 * @after
	 */
	public function workDirTearDown() {
		chdir( $this->oldWorkDir );
	}

	public static function provideFindScriptClass() {
		// NOTE: We must use a different class for each test case,
		//       otherwise we may trigger a "cannot re-declare class" error.

		yield 'plain name'
			=> [ 'fakeScript', EditCLI::class ];

		yield 'name with suffix'
			=> [ 'fakeScript.php', EditCLI::class ];

		yield 'name with path but no suffix'
			=> [ 'storage/fakeScript', DumpRev::class ];

		yield 'name with path and suffix'
			=> [ 'storage/fakeScript.php', DumpRev::class ];

		yield 'relative path with "./"'
			=> [ './maintenance/storage/fakeScript.php', DumpRev::class ];

		$dir = basename( self::FIXTURE_DIRECTORY );
		yield 'relative path with "../"'
			=> [ "../$dir/maintenance/fakeScriptThatReturnsName.php", CleanupImages::class ];

		yield 'absolute path'
			=> [ self::FIXTURE_DIRECTORY . '/maintenance/fakeScript.php', EditCLI::class ];

		yield 'class name'
			=> [ 'MediaWiki\Maintenance\Version', Version::class ];

		yield 'extension script path, using prefix'
			=> [ 'FakeExtension:fakeScript.php', FakeScript::class ];

		// NOTE: assumes the class has been loaded by the previous test case!
		yield 'extension class, using prefix'
			=> [ 'FakeExtension:FakeScript', FakeScript::class ];

		yield 'extension class'
			=> [ 'MediaWiki\Extension\FakeExtension\Maintenance\FakeScript', FakeScript::class ];

		yield 'extension class, using dots'
			=> [ 'MediaWiki.Extension.FakeExtension.Maintenance.FakeScript', FakeScript::class ];
	}

	private function newRunner(): MaintenanceRunner {
		return new class () extends MaintenanceRunner {
			public function getScriptClass(): string {
				return parent::getScriptClass();
			}

			public function findScriptClass( string $script ): string {
				return parent::findScriptClass( $script );
			}

			public function preloadScriptFile( string $script ): void {
				parent::preloadScriptFile( $script );
			}

			protected function fatalError( $msg, $exitCode = 1 ): never {
				$this->error( $msg );
				throw new UnexpectedValueException( $msg );
			}

			protected function error( string $msg ) {
				echo $msg;
			}

			protected function getMwInstallPath(): string {
				// Fake the install path as the current directory, so that the fake maintenance scripts are found
				// when providing a php file name in the tests.
				return MaintenanceRunnerTest::FIXTURE_DIRECTORY;
			}

			protected function getExtensionInfo( string $extName ): ?array {
				return [
					'namespace' => "MediaWiki\\Extension\\$extName",
					'path' => MaintenanceRunnerTest::FIXTURE_DIRECTORY . '/FakeExtension/extension.json',
				];
			}
		};
	}

	/**
	 * @dataProvider provideFindScriptClass
	 */
	public function testFindScriptClass( $script, $expected ) {
		$runner = $this->newRunner();

		$class = $runner->findScriptClass( $script );

		if ( str_starts_with( $class, '\\' ) ) {
			$class = substr( $class, 1 );
		}
		$this->assertSame( $expected, $class );
	}

	public static function providePreloadScriptFile() {
		// NOTE: We must use a different class for each test case,
		//       otherwise we may trigger a "cannot re-declare class" error.

		yield 'plain name'
			=> [ 'fakeScript', EditCLI::class ];

		yield 'name with suffix'
			=> [ 'fakeScriptThatReturnsName.php', CleanupImages::class ];

		yield 'class name'
			=> [ 'FindOrphanedFiles', FindOrphanedFiles::class ];
	}

	/**
	 * @dataProvider providePreloadScriptFile
	 */
	public function testPreloadScriptFile( $script, $expected ) {
		$runner = $this->newRunner();

		$runner->preloadScriptFile( $script );
		$class = $runner->getScriptClass();

		if ( str_starts_with( $class, '\\' ) ) {
			$class = substr( $class, 1 );
		}
		$this->assertSame( $expected, $class );
	}

}
