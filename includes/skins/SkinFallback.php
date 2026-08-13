<?php
/**
 * Skin file for the fallback skin.
 *
 * @since 1.24
 * @file
 */

use MediaWiki\Html\Html;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Registration\ExtensionRegistry;

/**
 * SkinTemplate class for the fallback skin
 */
class SkinFallback extends SkinMustache {
	/** @inheritDoc */
	public $skinname = 'fallback';

	/**
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$out->disableClientCache();
	}

	/**
	 * @param string $styleDirectory Path to the skins/ directory
	 * @return string[]
	 */
	private function findInstalledSkins( string $styleDirectory ) {
		// Get all subdirectories which might contains skins
		$possibleSkins = scandir( $styleDirectory );
		$possibleSkins = array_filter( $possibleSkins, static function ( $maybeDir ) use ( $styleDirectory ) {
			return $maybeDir !== '.' && $maybeDir !== '..' && is_dir( "$styleDirectory/$maybeDir" );
		} );

		// Filter out skins that aren't installed
		$possibleSkins = array_filter( $possibleSkins, static function ( $skinDir ) use ( $styleDirectory ) {
			return is_file( "$styleDirectory/$skinDir/skin.json" )
				|| is_file( "$styleDirectory/$skinDir/$skinDir.php" );
		} );

		return $possibleSkins;
	}

	/**
	 * Inform the user why they are seeing this skin.
	 *
	 * @return string
	 */
	private function buildHelpfulInformationMessage() {
		$config = $this->getConfig();
		$defaultSkin = $config->get( MainConfigNames::DefaultSkin );
		$styleDirectory = $config->get( MainConfigNames::StyleDirectory );
		$installedSkins = $this->findInstalledSkins( $styleDirectory );
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$enabledSkins = $skinFactory->getInstalledSkins();
		$enabledSkins = array_change_key_case( $enabledSkins, CASE_LOWER );
		$loadedComponentPaths = array_column(
			ExtensionRegistry::getInstance()->getAllThings(), 'path'
		);

		if ( $installedSkins ) {
			$skinsInstalledText = [];
			$skinsInstalledSnippet = [];

			foreach ( $installedSkins as $skinKey ) {
				$normalizedKey = strtolower( $skinKey );
				if ( !array_key_exists( $normalizedKey, $enabledSkins ) ) {
					// The directory name is not a valid skin key, but the
					// skin may be loaded under another key. Report it under
					// the key that works instead of suggesting wfLoadSkin()
					// for an already-loaded skin.
					$normalizedKey = self::enabledKeyForSkinDir(
						$styleDirectory, $skinKey, $loadedComponentPaths, array_keys( $enabledSkins )
					) ?? $normalizedKey;
				}
				if ( array_key_exists( $normalizedKey, $enabledSkins ) ) {
					$skinsInstalledText[] = $this->msg( 'default-skin-not-found-row-enabled' )
						->params( $normalizedKey, $skinKey )->plain();
				} else {
					$skinsInstalledText[] = $this->msg( 'default-skin-not-found-row-disabled' )
						->params( $normalizedKey, $skinKey )->plain();
					$skinsInstalledSnippet[] = $this->getSnippetForSkin( $skinKey );
				}
			}

			return $this->msg( 'default-skin-not-found' )->params(
				$defaultSkin,
				implode( "\n", $skinsInstalledText ),
				implode( "\n", $skinsInstalledSnippet ) )->numParams(
					count( $skinsInstalledText ),
					count( $skinsInstalledSnippet )
			)->parseAsBlock();
		} else {
			return $this->msg( 'default-skin-not-found-no-skins' )->params(
				$defaultSkin
			)->parseAsBlock();
		}
	}

	/**
	 * Get the appropriate LocalSettings.php snippet to enable the given skin
	 *
	 * @param string $skin
	 * @return string
	 */
	private static function getSnippetForSkin( $skin ) {
		global $IP;
		if ( file_exists( "$IP/skins/$skin/skin.json" ) ) {
			return "wfLoadSkin( '$skin' );";
		} else {
			return "require_once \"\$IP/skins/$skin/$skin.php\";";
		}
	}

	/**
	 * Map an installed skin directory to the key it is loaded under.
	 *
	 * A skin's directory name is not necessarily a valid skin key:
	 * MinervaNeue's skin.json registers the key 'minerva', so with
	 * $wgDefaultSkin = 'minervaneue' the skin is loaded yet the directory
	 * name matches no enabled key. Only directories whose skin.json is among
	 * the loaded components are mapped, and only keys that are enabled are
	 * returned; a directory that is present but not loaded keeps the plain
	 * disabled row and its wfLoadSkin() snippet.
	 *
	 * @param string $styleDirectory Path to the skins/ directory
	 * @param string $skinDir Directory name of an installed skin
	 * @param string[] $loadedComponentPaths Absolute paths to the JSON files of loaded components
	 * @param string[] $enabledSkinKeys Lowercased keys of enabled skins
	 * @return string|null Lowercased enabled key, or null if the directory is not loaded under an enabled key
	 */
	private static function enabledKeyForSkinDir(
		string $styleDirectory, string $skinDir, array $loadedComponentPaths, array $enabledSkinKeys
	): ?string {
		$jsonFile = "$styleDirectory/$skinDir/skin.json";
		if ( !in_array( $jsonFile, $loadedComponentPaths, true ) ) {
			return null;
		}
		$contents = is_file( $jsonFile ) ? file_get_contents( $jsonFile ) : false;
		$info = $contents === false ? null : FormatJson::decode( $contents, true );
		if ( !is_array( $info ) || !is_array( $info['ValidSkinNames'] ?? null ) ) {
			return null;
		}
		foreach ( array_keys( $info['ValidSkinNames'] ) as $key ) {
			$key = strtolower( (string)$key );
			if ( in_array( $key, $enabledSkinKeys, true ) ) {
				return $key;
			}
		}
		return null;
	}

	/**
	 * Adds an `html-fallback-warning` template to inform system administrators
	 * that their mediawiki skin is incorrectly setup.
	 * It's recommended that skin developers do not add further to date here
	 *  and instead work on improving SkinMustache::getTemplateData where necessary
	 *  to improve flexibility of the data for all skin developers.
	 * @inheritDoc
	 * @return array
	 */
	public function getTemplateData() {
		$config = $this->getConfig();
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$data = parent::getTemplateData();
		// If the default skin isn't configured correctly, append a warning to the
		// subtitle to alert a sysadmin.
		if ( !isset(
			$skinFactory->getInstalledSkins()[$config->get( MainConfigNames::DefaultSkin )]
		) ) {
			$data['html-fallback-warning'] = Html::warningBox( $this->buildHelpfulInformationMessage() );
		}
		return $data;
	}
}
