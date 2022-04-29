<?php

namespace MediaWiki\Settings;

use WebRequest;

/**
 * Utility for loading site-specific settings in a multi-tenancy ("wiki farm" or "wiki family")
 * environment. See <https://www.mediawiki.org/wiki/Manual:Wiki_family>.
 *
 * This class is designed to be used before the initialization of MediaWiki is complete.
 *
 * @unstable
 */
class WikiFarmSettingsLoader {

	/** @var SettingsBuilder */
	private $settingsBuilder;

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
	 * the WikiFarmSiteDetector callback, which defaults to
	 * SettingsBuilder::detectWikiFarmSite(). The file extension is
	 * given by WikiFarmSettingsExtension and defaults to "yaml".
	 *
	 * If no file matching the detected site name is found, this
	 * method tries to load a settings file called "default"
	 * (with the appropriate file extension).
	 *
	 * @unstable
	 */
	public function loadWikiFarmSettings() {
		$config = $this->settingsBuilder->getConfig();

		$farmDir = $config->get( 'WikiFarmSettingsDirectory' );
		$farmExt = $config->get( 'WikiFarmSettingsExtension' );
		$siteDetector = $config->get( 'WikiFarmSiteDetector' );

		if ( !$farmDir ) {
			return;
		}

		if ( !$siteDetector ) {
			$siteDetector = [ $this, 'detectWikiFarmSite' ];
		}

		if ( defined( 'MW_WIKI_NAME' ) ) {
			// MW_WIKI_NAME is used to control the target wiki when running CLI scripts.
			// Maintenance.php sets it to the value of the --wiki option.
			$site = MW_WIKI_NAME;
		} else {
			$site = $siteDetector();
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
	 * Default detection algorithm for deciding which wiki to load settings for
	 * in a multi-tenant (wiki-farm) environment. This will be used if
	 * WikiFarmSettingsDirectory is set, but WikiFarmSiteDetector is not set.
	 *
	 * In order to determine the requested wiki site, this method method looks
	 * at $_SERVER['WIKI_NAME'] and $_SERVER['HTTP_HOST'], among other things.
	 * $_SERVER['WIKI_NAME'] would come from a CGI environment variable,
	 * e.g. from setEnv in an Apache vhost configuration.
	 *
	 * @return string
	 */
	private function detectWikiFarmSite(): string {
		if ( isset( $_SERVER['WIKI_NAME'] ) ) {
			return $_SERVER['WIKI_NAME'];
		}

		$config = $this->settingsBuilder->getConfig();
		$assumeProxiesUseDefaultProtocolPorts =
			$config->get( 'AssumeProxiesUseDefaultProtocolPorts' );

		$server = WebRequest::detectServer( $assumeProxiesUseDefaultProtocolPorts );

		// normalize the wiki name.
		$server = preg_replace( '@^\w+://|:\d+@', '', $server );
		$wiki = strtolower( strtr( $server, '.', '_' ) );

		return $wiki;
	}

}
