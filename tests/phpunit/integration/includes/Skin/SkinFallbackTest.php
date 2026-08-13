<?php

use MediaWiki\Skin\SkinFallback;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Skin\SkinFallback
 */
class SkinFallbackTest extends MediaWikiIntegrationTestCase {

	/** @var string */
	private $styleDirectory;

	protected function setUp(): void {
		parent::setUp();
		$this->styleDirectory = $this->getNewTempDirectory();
		// skins/MinervaNeue registers under a key that differs from its
		// directory name.
		mkdir( "$this->styleDirectory/MinervaNeue", 0777, true );
		file_put_contents( "$this->styleDirectory/MinervaNeue/skin.json", json_encode( [
			'ValidSkinNames' => [ 'minerva' => [ 'class' => 'SkinMinerva' ] ],
		] ) );
		// A skin whose directory name and key agree.
		mkdir( "$this->styleDirectory/MonoBook", 0777, true );
		file_put_contents( "$this->styleDirectory/MonoBook/skin.json", json_encode( [
			'ValidSkinNames' => [ 'monobook' => 'MonoBook' ],
		] ) );
		// A multi-key skin: the key order in the file carries no meaning.
		mkdir( "$this->styleDirectory/Vector", 0777, true );
		file_put_contents( "$this->styleDirectory/Vector/skin.json", json_encode( [
			'ValidSkinNames' => [
				'vector-2022' => [ 'class' => 'SkinVector22' ],
				'vector' => 'Vector',
			],
		] ) );
		// A fork that declares an already-enabled key but is never loaded.
		mkdir( "$this->styleDirectory/MonoBookFork", 0777, true );
		file_put_contents( "$this->styleDirectory/MonoBookFork/skin.json", json_encode( [
			'ValidSkinNames' => [ 'monobook' => 'MonoBookFork' ],
		] ) );
		// A legacy skin with no skin.json to map keys from.
		mkdir( "$this->styleDirectory/Legacy", 0777, true );
		file_put_contents( "$this->styleDirectory/Legacy/Legacy.php", "<?php\n" );
	}

	public static function provideEnabledKeyForSkinDir() {
		return [
			'directory name differs from key' => [
				'MinervaNeue', true, [ 'minerva', 'monobook' ], 'minerva'
			],
			'directory name matches key' => [
				'MonoBook', true, [ 'minerva', 'monobook' ], 'monobook'
			],
			'multi-key skin picks the enabled key, not the first listed' => [
				'Vector', true, [ 'minerva', 'vector' ], 'vector'
			],
			'legacy skin without skin.json' => [
				'Legacy', true, [ 'minerva', 'monobook' ], null
			],
			'directory not among loaded components' => [
				'NoSuchSkin', true, [ 'minerva', 'monobook' ], null
			],
			'skin present but not loaded' => [
				'MonoBook', false, [ 'minerva', 'monobook' ], null
			],
			'fork declaring an enabled key but not loaded' => [
				'MonoBookFork', true, [ 'minerva', 'monobook' ], null
			],
		];
	}

	/**
	 * @dataProvider provideEnabledKeyForSkinDir
	 */
	public function testEnabledKeyForSkinDir( $skinDir, $loaded, $enabledKeys, $expected ) {
		$loadedComponentPaths = $loaded ? [
			"$this->styleDirectory/MinervaNeue/skin.json",
			"$this->styleDirectory/MonoBook/skin.json",
			"$this->styleDirectory/Vector/skin.json",
			"$this->styleDirectory/Legacy/skin.json",
		] : [];
		$skinFallback = TestingAccessWrapper::newFromClass( SkinFallback::class );
		$this->assertSame(
			$expected,
			$skinFallback->enabledKeyForSkinDir(
				$this->styleDirectory, $skinDir, $loadedComponentPaths, $enabledKeys
			)
		);
	}
}
