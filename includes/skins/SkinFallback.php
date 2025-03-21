<?php
/**
 * Skin file for the fallback skin.
 *
 * @since 1.24
 * @file
 */

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;

/**
 * SkinTemplate class for the fallback skin
 */
class SkinFallback extends SkinMustache {
	/** @inheritDoc */
	public $skinname = 'fallback';

	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$out->disableClientCache();
	}

	/**
	 * @return array
	 */
	private function findInstalledSkins() {
		$config = $this->getConfig();
		$styleDirectory = $config->get( MainConfigNames::StyleDirectory );
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
		$installedSkins = $this->findInstalledSkins();
		$skinFactory = MediaWikiServices::getInstance()->getSkinFactory();
		$enabledSkins = $skinFactory->getInstalledSkins();
		$enabledSkins = array_change_key_case( $enabledSkins, CASE_LOWER );

		if ( $installedSkins ) {
			$skinsInstalledText = [];
			$skinsInstalledSnippet = [];

			foreach ( $installedSkins as $skinKey ) {
				$normalizedKey = strtolower( $skinKey );
				$isEnabled = array_key_exists( $normalizedKey, $enabledSkins );
				if ( $isEnabled ) {
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

/** @deprecated class alias since 1.44 */
class_alias( SkinFallback::class, 'SkinFallback' );
