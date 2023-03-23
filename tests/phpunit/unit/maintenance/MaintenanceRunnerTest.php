<?php

use MediaWiki\Extension\FakeExtension\Maintenance\FakeScript;
use MediaWiki\Maintenance\MaintenanceRunner;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Maintenance\MaintenanceRunner
 */
class MaintenanceRunnerTest extends TestCase {

	private $oldWorkDir;

	/**
	 * @before
	 */
	public function workDirSetUp() {
		// Needed for testing the resolution of pathes relative to CWD
		$this->oldWorkDir = getcwd();
		chdir( MW_INSTALL_PATH );
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
			=> [ 'version', Version::class ];

		yield 'name with suffix'
			=> [ 'edit.php', EditCLI::class ];

		yield 'name with path but no suffix'
			=> [ 'storage/dumpRev', DumpRev::class ];

		yield 'name with path and suffix'
			=> [ 'storage/moveToExternal.php', MoveToExternal::class ];

		yield 'relative path with "./"'
			=> [ './maintenance/storage/orphanStats.php', OrphanStats::class ];

		$dir = basename( MW_INSTALL_PATH );
		yield 'relative path with "../"'
			=> [ "../$dir/maintenance/cleanupImages.php", CleanupImages::class ];

		yield 'absolute path'
			=> [ MW_INSTALL_PATH . '/maintenance/blockUsers.php', BlockUsers::class ];

		yield 'class name'
			=> [ 'Version', Version::class ];

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

			protected function fatalError( $msg, $exitCode = 1 ) {
				$this->error( $msg );
				throw new UnexpectedValueException( $msg );
			}

			protected function error( string $msg ) {
				echo $msg;
			}

			protected function getExtensionInfo( string $extName ): ?array {
				return [
					'namespace' => "MediaWiki\\Extension\\$extName",
					'path' => __DIR__ . '/FakeExtension/extension.json',
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
			=> [ 'findOrphanedFiles', FindOrphanedFiles::class ];

		yield 'name with suffix'
			=> [ 'getText.php', GetTextMaint::class ];

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
