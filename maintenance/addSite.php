<?php

use MediaWiki\MediaWikiServices;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script for adding a site definition into the sites table.
 *
 * @since 1.29
 *
 * @license GNU GPL v2+
 * @author Florian Schmidt
 */
class AddSite extends Maintenance {

	public function __construct() {
		$this->addDescription( 'Add a site definition into the sites table.' );

		$this->addArg( 'globalid', 'The global id of the site to add, e.g. "wikipedia".', true );
		$this->addArg( 'group', 'In which group this site should be sorted in.', true );
		$this->addOption( 'language', 'The language code of the site, e.g. "de".' );
		$this->addOption( 'interwiki-id', 'The interwiki ID of the site.' );
		$this->addOption( 'navigation-id', 'The navigation ID of the site.' );
		$this->addOption( 'pagepath', 'The URL to pages of this site, e.g.' .
			' https://example.com/wiki/\$1.' );
		$this->addOption( 'filepath', 'The URL to files of this site, e.g. https://example
		.com/w/\$1.' );

		parent::__construct();
	}

	/**
	 * Imports the site described by the parameters (see self::__construct()) passed to this
	 * maintenance sccript into the sites table of MediaWiki.
	 * @return bool
	 */
	public function execute() {
		$siteStore = MediaWikiServices::getInstance()->getSiteStore();
		$siteStore->reset();

		$globalId = $this->getArg( 0 );
		$group = $this->getArg( 1 );
		$language = $this->getOption( 'language' );
		$interwikiId = $this->getOption( 'interwiki-id' );
		$navigationId = $this->getOption( 'navigation-id' );
		$pagepath = $this->getOption( 'pagepath' );
		$filepath = $this->getOption( 'filepath' );

		if ( !is_string( $globalId ) || !is_string( $group ) ) {
			echo "Arguments globalid and group need to be strings.\n";
			return false;
		}

		if ( $siteStore->getSite( $globalId ) !== null ) {
			echo "Site with global id $globalId already exists.\n";
			return false;
		}

		$site = new MediaWikiSite();
		$site->setGlobalId( $globalId );
		$site->setGroup( $group );
		if ( $language !== null ) {
			$site->setLanguageCode( $language );
		}
		if ( $interwikiId !== null ) {
			$site->addInterwikiId( $interwikiId );
		}
		if ( $navigationId !== null ) {
			$site->addNavigationId( $navigationId );
		}
		if ( $pagepath !== null ) {
			$site->setPagePath( $pagepath );
		}
		if ( $filepath !== null ) {
			$site->setFilePath( $filepath );
		}

		$siteStore->saveSites( [ $site ] );

		if ( method_exists( $siteStore, 'reset' ) ) {
			$siteStore->reset();
		}

		echo "Done.\n";
	}
}

$maintClass = AddSite::class;
require_once RUN_MAINTENANCE_IF_MAIN;
