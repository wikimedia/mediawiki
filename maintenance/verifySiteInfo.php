<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\Interwiki\SiteInfoInterwikiLookup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Site\FileSiteInfoLookup;
use MediaWiki\Site\SiteInfoLookup;
use MediaWiki\Site\SiteInfoSiteLookup;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script for comparing information loaded from SiteInfo files
 * with the wiki's current configuration reflected by the InterwikiLookup
 * and SiteLookup services.
 *
 * See docs/siteinfo.txt for more information.
 *
 * @since 1.30
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class VerifySiteInfo extends Maintenance {

	/**
	 * @var InterwikiLookup
	 */
	private $interwikiLookup;

	/**
	 * @var SiteLookup
	 */
	private $siteLookup;

	/**
	 * @var int
	 */
	private $failures = 0;

	public function __construct() {
		$this->addDescription(
			"Compares script for comparing information loaded from SiteInfo files\n"
			. "with the wiki's current configuration reflected by the InterwikiLookup\n"
			. "and SiteLookup services"
		);

		$this->addArg( 'file(s)', 'FileInfo files to verify. The file names must end in ".json" or ".php".', true );

		parent::__construct();
	}

	/**
	 * @return SiteLookup
	 */
	public function getSiteLookup() {
		if ( !$this->siteLookup ) {
			$this->siteLookup = MediaWikiServices::getInstance()->getSiteLookup();
		}

		return $this->siteLookup;
	}

	/**
	 * @param SiteLookup $siteLookup
	 */
	public function setSiteLookup( $siteLookup ) {
		$this->siteLookup = $siteLookup;
	}

	/**
	 * @return InterwikiLookup
	 */
	public function getInterwikiLookup() {
		if ( !$this->interwikiLookup ) {
			$this->interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
		}

		return $this->interwikiLookup;
	}

	/**
	 * @param InterwikiLookup $interwikiLookup
	 */
	public function setInterwikiLookup( $interwikiLookup ) {
		$this->interwikiLookup = $interwikiLookup;
	}

	/**
	 * Do the import.
	 */
	public function execute() {
		$files = $this->mArgs;
		$siteInfoLookup = $this->loadSiteInfoLookup( $files );

		$targetInterwikiLookup = new SiteInfoInterwikiLookup( $siteInfoLookup );
		$targetSiteLookup = new SiteInfoSiteLookup( $siteInfoLookup );

		$currentInterwikiLookup = $this->getInterwikiLookup();
		$currentSiteLookup = $this->getSiteLookup();

		$this->verifyInterwikiLookup( $targetInterwikiLookup, $currentInterwikiLookup );
		$this->verifySiteLookup( $targetSiteLookup, $currentSiteLookup );

		if ( $this->failures > 0 ) {
			$this->error( "Verification failed on {$this->failures} counts.", 2 );
		}
	}

	/**
	 * @param string $msg
	 */
	private function log( $msg ) {
		$this->output( "$msg\n" );
	}

	/**
	 * @param string $msg
	 */
	private function fail( $msg ) {
		$this->output( "FAILURE: $msg\n" );
		$this->failures++;
	}

	/**
	 * @param string[] $files
	 *
	 * @return FileSiteInfoLookup
	 */
	private function loadSiteInfoLookup( array $files ) {
		$lookup = new FileSiteInfoLookup( wfWikiID(), $files );

		return $lookup;
	}

	private function verifyInterwikiLookup(
		InterwikiLookup $targetInterwikiLookup,
		InterwikiLookup $currentInterwikiLookup
	) {
		$currentRows = $this->keyByField( $currentInterwikiLookup->getAllPrefixes(), 'iw_prefix' );
		$targetRows = $this->keyByField( $targetInterwikiLookup->getAllPrefixes(), 'iw_prefix' );

		foreach ( $currentRows as $prefix => $current ) {
			$this->log( "Verifying interwiki for prefix $prefix" );
			if ( !$this->verifyKey( "Interwiki for prefix $prefix", $targetRows, $prefix ) ) {
				continue;
			}

			$target = $targetRows[$prefix];

			foreach ( array_keys( $current ) as $field ) {
				$this->verifyField( "Interwiki for prefix $prefix", $field, $target, $current );
			}
		}
	}

	private function verifySiteLookup(
		SiteLookup $targetSiteLookup,
		SiteLookup $currentSiteLookup
	) {
		$targetSiteLookup->getSites();
		$currentSites = $this->sitesToArray( $currentSiteLookup->getSites() );
		$targetSites = $this->sitesToArray( $targetSiteLookup->getSites() );

		foreach ( $currentSites as $siteId => $current ) {
			$this->log( "Verifying site $siteId" );
			if ( !$this->verifyKey( "Site $siteId", $targetSites, $siteId ) ) {
				continue;
			}

			$target = $targetSites[$siteId];

			foreach ( array_keys( $current ) as $field ) {
				$this->verifyField( "Site $siteId", $field, $target, $current );
			}
		}
	}

	/**
	 * @param SiteList $sites
	 *
	 * @return array[]
	 */
	private function sitesToArray( SiteList $sites ) {
		$array = [];

		/** @var Site $site */
		foreach ( $sites as $site ) {
			$id = $site->getGlobalId();
			$array[$id] = [
				'globalid' => $site->getGlobalId(),
				'type' => $site->getType(),
				'group' => $site->getGroup(),
				'source' => $site->getSource(),
				'language' => $site->getLanguageCode(),
				'aliases' => $site->getLocalIds(),
				'config' => $site->getExtraConfig(),
				'data' => $site->getExtraData(),
				'forward' => $site->shouldForward()
			];
		}

		return $array;
	}

	/**
	 * @param array[] $listOfRows
	 * @param string $field
	 *
	 * @return array[]
	 */
	private function keyByField( array $listOfRows, $field ) {
		$result = [];

		foreach ( $listOfRows as $row ) {
			$key = $row[$field];
			$result[$key] = $row;
		}

		return $result;
	}

	/**
	 * @param string $name
	 * @param array $array
	 * @param string $key
	 *
	 * @return bool
	 */
	private function verifyKey( $name, array $array, $key ) {
		if ( !isset( $array[$key] ) ) {
			$this->fail( "$name: target has no entry for `$key``!" );
			return false;
		}

		return true;
	}

	/**
	 * @param string $name
	 * @param string $field
	 * @param array $target
	 * @param array $current
	 *
	 * @return bool
	 */
	private function verifyField( $name, $field, array $target, array $current ) {
		if ( !isset( $current[$field] ) || $current[$field] === null || $current[$field] === '' ) {
			return true;
		}

		if ( !isset( $target[$field] ) ) {
			$this->fail( "$name: [$field] is not set!" );
			return false;
		}

		if ( is_array( $target[$field] ) && is_array( $current[$field] ) ) {
			foreach( array_keys( $current[$field] ) as $f ) {
				return $this->verifyField( "$name [$field]", $f, $target[$field], $current[$field] );
			}
		}

		if ( $target[$field] != $current[$field] ) {
			$targetValue = trim( var_export( $target[$field], true ) );
			$currentValue = trim( var_export( $current[$field], true ) );
			$this->fail(
				"$name: Values of [$field] differ: "
				. "target has $targetValue, current is $currentValue"
			);
			return false;
		}

		return true;
	}

}

$maintClass = 'VerifySiteInfo';
require_once RUN_MAINTENANCE_IF_MAIN;
