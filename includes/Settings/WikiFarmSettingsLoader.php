<?php

namespace MediaWiki\Settings;

use MediaWiki\MainConfigNames;

/**
 * Utility for loading site-specific settings in a multi-tenancy ("wiki farm" or "wiki family")
 * environment. See <https://www.mediawiki.org/wiki/Manual:Wiki_family>.
 *
 * This class is designed to be used before the initialization of MediaWiki is complete.
 *
 * @unstable
 */
class WikiFarmSettingsLoader {

	private SettingsBuilder $settingsBuilder;

	/**
	 * @param SettingsBuilder $settingsBuilder
	 */
	public function __construct( SettingsBuilder $settingsBuilder ) {
		$this->settingsBuilder = $settingsBuilder;
	}

	/**
	 * Loads any site-specific settings in a multi-tenant (wiki-farm)
	 * environment. The settings file is expected to be found in the
	 * directory identified by the WikiFarmSettingsDirectory config
	 * variable. If WikiFarmSettingsDirectory is not set, wiki-farm
	 * mode is disabled, and no site-specific settings will be loaded.
	 *
	 * The name of the site-specific settings file is determined using
	 * the MW_WIKI_NAME environment variable. The file extension is
	 * given by WikiFarmSettingsExtension and defaults to "yaml".
	 *
	 * @unstable
	 */
	public function loadWikiFarmSettings() {
		$config = $this->settingsBuilder->getConfig();

		$farmDir = $config->get( MainConfigNames::WikiFarmSettingsDirectory );
		$farmExt = $config->get( MainConfigNames::WikiFarmSettingsExtension );

		if ( !$farmDir ) {
			return;
		}

		$site = null;
		$wikiName = $this->getWikiNameConstant();
		if ( $wikiName !== null ) {
			// The MW_WIKI_NAME constant is used to control the target wiki when running CLI scripts.
			// Maintenance.php sets it to the value of the --wiki option.
			$site = $wikiName;
		} elseif ( isset( $_SERVER['MW_WIKI_NAME'] ) ) {
			// The MW_WIKI_NAME environment variable is used to set the target wiki
			// via web server configuration, e.g. using Apache's SetEnv directive.
			// For maintenance scripts, it may be set as an environment variable,
			// or by using the --wiki option.
			$site = $_SERVER['MW_WIKI_NAME'];
		} elseif ( isset( $_SERVER['WIKI_NAME'] ) ) {
			// In 1.38, experimental support for wiki farms was added using the
			// "WIKI_NAME" server variable. This has been changed to "MW_WIKI_NAME"
			// in 1.39.
			$site = $_SERVER['WIKI_NAME'];

			// NOTE: We can't use wfDeprecatedMsg here, MediaWiki hasn't been initialized yet.
			trigger_error(
				'The WIKI_NAME server variable has been deprecated since 1.39, ' .
					'use MW_WIKI_NAME instead.'
			);
		}

		if ( !$site ) {
			return;
		}

		$path = "$farmDir/$site.$farmExt";
		if ( $this->settingsBuilder->fileExists( $path ) ) {
			$this->settingsBuilder->loadFile( $path );
		}
	}

	/**
	 * Access MW_WIKI_NAME in a way that can be overridden by tests
	 *
	 * @return string|null
	 */
	protected function getWikiNameConstant() {
		return defined( 'MW_WIKI_NAME' ) ? MW_WIKI_NAME : null;
	}

}
