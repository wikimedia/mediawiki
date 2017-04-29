<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Site\SiteInfoLookup;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script for generating site info files from legacy settings.
 *
 * @since 1.30
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class NBuildSiteInfo extends Maintenance {

	public function __construct() {
		$this->addDescription( 'Builds site info files from legacy Interwiki and Site settings.' );

		$this->addArg( 'file', 'The output file for the file info. The file name must end in ".json" or ".php".', true );

		$this->addOption( 'interwiki-id-file', 'File to write interwiki prefixes to. The file name must end in ".json" or ".php".', true );
		$this->addOption( 'navigation-id-file', 'File to write navigation prefixes to. The file name must end in ".json" or ".php".', true );

		parent::__construct();
	}

	private function getFileFormat( $file ) {
		if ( preg_match( '\.(php|json)$', $file, $m ) ) {
			return $m[1];
		}

		throw new MWException( 'File name must end in ".json" or ".php": ' . $file );
	}

	private function readFile( $file ) {
		$format = $this->getFileFormat( $file );
		switch ( $format ) {
			case 'php':
				$array = include $file;
				break;
			case 'json':
				$data = file_get_contents( $file );
				$array = json_decode( $data );
				break;
			default:
				throw new InvalidArgumentException( 'Unknown data format ' . $format );
		}

		if ( !is_array( $array ) ) {
			throw new MWException( 'Failed to read ' . $file );
		}

		return $array;
	}

	private function writeFile( $file, array $array ) {
		$format = $this->getFileFormat( $file );
		switch ( $format ) {
			case 'php':
				$data = var_export( $array, true );
				break;
			case 'json':
				$data = json_encode( $array, JSON_PRETTY_PRINT );
				break;
			default:
				throw new InvalidArgumentException( 'Unknown data format ' . $format );
		}

		if ( !is_string( $data ) ) {
			throw new MWException( 'Failed to encode data for ' . $file );
		}

		$ok = file_put_contents( $file, $data );

		if ( !$ok ) {
			throw new MWException( 'Failed to write ' . $file );
		}
	}

	private function mergeData( $name, array $data, array $newData ) {
		// indexed arrays just get concatenated
		if ( isset( $data[0] ) ) {
			return array_merge( $data, $newData );
		}

		foreach ( $newData as $key => $value ) {
			if ( isset( $data[$key] ) ) {
				if ( is_array( $value ) && is_array( $data[$key] ) ) {
					$data[$key] = $this->mergeData( "$name -> $key", $data[$key], $value );
				} elseif ( $data[$key] !== $value ) {
					$this->warn( "Conflicting data: $name -> $key: {$data[$key]} !== $value" );
				}
			} else {
				$data[$key] = $value;
			}
		}

		return $data;
	}

	private function saveData( $file, array $newData ) {
		if ( file_exists( $file ) ) {
			$data = $this->readFile( $file );
			$data = $this->mergeData( $file, $data, $newData );
		} else {
			$data = $newData;
		}

		$this->writeFile( $file, $data );
	}

	/**
	 * Do the import.
	 */
	public function execute() {
		try {
			$file = $this->getArg( 0 );
			$interwikiIdFile = $this->getOption( 'interwiki-id-file' );
			$navigationIdFile = $this->getOption( 'navigation-id-file' );

			$siteLookup = MediaWikiServices::getInstance()->getSiteLookup();
			$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
			$raw = $this->collectRawInfo( $interwikiLookup, $siteLookup );

			$this->log( "Building site info..." );
			$sites = $this->buildSiteInfo( $raw );
			$this->log( "Writing site info to $file..." );
			$this->saveData( $file, [ 'sites' => $sites ] );

			$this->log( "Building alias IDs..." );
			$aliasIds = $this->extractLocalIds( $raw, '_aliasIds_' );

			if ( empty( $aliasIds ) ) {
				$this->log( "Writing alias IDs to $file..." );
				$array = [ 'ids' => [ SiteInfoLookup::ALIAS_ID => $aliasIds ] ];
				$this->saveData( $file, $array );
			} else {
				$this->log( "No aliases found." );
			}

			if ( $interwikiIdFile ) {
				$this->log( "Building interwiki IDs..." );
				$interwikiIds = $this->extractLocalIds( $raw, '_interwikiIds_' );

				$this->log( "Writing interwiki IDs to $interwikiIdFile..." );
				$array = [ 'ids' => [ SiteInfoLookup::INTERWIKI_ID => $interwikiIds ] ];
				$this->saveData( $interwikiIdFile, $array );
			}

			if ( $navigationIdFile ) {
				$this->log( "Building navigation IDs..." );
				$navigationIds = $this->extractLocalIds( $raw, '_navigationIds_' );

				$this->log( "Writing navigation IDs to $navigationIdFile..." );
				$array = [ 'ids' => [ SiteInfoLookup::NAVIGATION_ID => $navigationIds ] ];
				$this->saveData( $navigationIdFile, $array );
			}

			$this->log( "Done." );
		}
		catch ( Exception $ex ) {
			$this->reportException( $ex );
		}
	}

	/**
	 * Outputs a message via the output() method.
	 *
	 * @param Exception $ex
	 */
	public function reportException( Exception $ex ) {
		$msg = $ex->getMessage();
		$this->output( "ERROR: $msg\n" );
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
	private function warn( $msg ) {
		$this->output( "WARNING: $msg\n" );
	}

	/**
	 * @param InterwikiLookup $interwikiLookup
	 * @param SiteLookup $siteLookup
	 *
	 * @return array[]
	 */
	private function collectRawInfo( InterwikiLookup $interwikiLookup, SiteLookup $siteLookup ) {
		$raw = [];

		$sites = $siteLookup->getSites();

		/* @var Site $site */
		foreach ( $sites as $wikiId => $site ) {
			$wikiId = $site->getGlobalId();
			$raw[$wikiId] = $this->buildInfoFromSite( $site );
		}

		foreach ( $interwikiLookup->getAllPrefixes() as $prefix ) {
			$interwiki = $interwikiLookup->fetch( $prefix );
			$wikiId = $this->getIdForInterwiki( $interwiki );
			$info = $this->buildInfoFromInterwiki( $prefix, $interwiki );

			if ( isset( $raw[$wikiId] ) ) {
				$raw[$wikiId] = $this->mergeData( $wikiId, $raw[$wikiId], $info );
			} else {
				$raw[$wikiId] = $info;
			}
		}

		return $raw;
	}

	/**
	 * @param Site $site
	 *
	 * @return array
	 */
	private function buildInfoFromSite( Site $site ) {
		list( $linkPath, $baseUrl )  = $this->splitUrl( $site->getLinkPath() );

		$info = [
			SiteInfoLookup::SITE_FAMILY => $site->getGroup(),
			SiteInfoLookup::SITE_LANGUAGE => $site->getLanguageCode(),
			SiteInfoLookup::SITE_ARTICLE_PATH => $linkPath,
			SiteInfoLookup::SITE_BASE_URL => $baseUrl,
			SiteInfoLookup::SITE_IS_FORWARDABLE => $site->shouldForward(),
			SiteInfoLookup::SITE_NAME => $site->getDomain(),
		];

		if ( $site->getType() === Site::TYPE_MEDIAWIKI ) {
			$info[ SiteInfoLookup::SITE_TYPE ] = SiteInfoLookup::TYPE_MEDIAWIKI;
		}

		if ( $site instanceof MediaWikiSite ) {
			/* @var MediaWikiSite $site */
			$info[ SiteInfoLookup::SITE_SCRIPT_PATH ] = $site->getRelativeFilePath();
			$info[ SiteInfoLookup::SITE_ARTICLE_PATH ] = $site->getRelativePagePath();
		}

		$info['_interwikiIds_'] = $site->getInterwikiIds();
		$info['_navigationIds_'] = $site->getNavigationIds();

		return array_filter( $info );
	}

	/**
	 * @param string $prefix
	 * @param Interwiki $interwiki
	 *
	 * @return array
	 */
	private function buildInfoFromInterwiki( $prefix, Interwiki $interwiki ) {
		global $wgExtraInterlanguageLinkPrefixes;

		$info = [
			SiteInfoLookup::SITE_IS_FORWARDABLE => $interwiki->isLocal(),
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => $interwiki->isTranscludable(),
			SiteInfoLookup::SITE_NAME => $interwiki->getName(), // i18n applies!
			SiteInfoLookup::SITE_DESCRIPTION => $interwiki->getDescription(), // i18n applies!
			SiteInfoLookup::SITE_DB_NAME => $interwiki->getWikiID(),
			SiteInfoLookup::SITE_DESCRIPTION => $interwiki->getDescription(),
		];

		list( $articlePath, $baseUrl )  = $this->splitUrl( $interwiki->getUrl() );
		$info[ SiteInfoLookup::SITE_BASE_URL ] = $baseUrl;
		$info[ SiteInfoLookup::SITE_ARTICLE_PATH ] = $articlePath;

		$scriptPath = preg_replace( '![^/]*.php$!', '', $interwiki->getAPI() );
		// TODO: add Wikimedia specific magic defaults

		if ( $scriptPath ) {
			$info[ SiteInfoLookup::SITE_SCRIPT_PATH ] = $scriptPath;
			$info[ SiteInfoLookup::SITE_TYPE ] = SiteInfoLookup::TYPE_MEDIAWIKI;
		}

		$info[ '_interwikiIds_' ] = [ $interwiki->getName() ];

		// NOTE: conditional copied from Parser::replaceInternalLinks2
		if ( Language::fetchLanguageName( $prefix, null, 'mw' ) ||
			in_array( $prefix, $wgExtraInterlanguageLinkPrefixes )
		) {
			$info[ '_navigationIds_' ] = [ $prefix ];
		}

		$info = array_filter( $info );
		return $info;
	}

	/**
	 * @param string $url
	 *
	 * @return array
	 */
	private function splitUrl( $url ) {
		$path = parse_url( $url, PHP_URL_PATH );
		$base = substr( $url, -strlen( $path ) ); // let's hope there is no fragment
		return [ $base, $path ];
	}

	private function getIdForInterwiki( Interwiki $interwiki ) {
		if ( $interwiki->getWikiID() ) {
			return $interwiki->getWikiID();
		}

		$wikiId = parse_url( $interwiki->getURL(), PHP_URL_HOST );
		// TODO: add special magic for Wikimedia sites

		return $wikiId;
	}


	/**
	 * @param array[] $raw
	 *
	 * @return array[]
	 */
	private function buildSiteInfo( array $raw ) {
		$siteInfo = [];

		foreach ( $raw as $wikiId => $info ) {
			unset( $info['_interwikiIds_'] );
			unset( $info['_navigationIds_'] );
			$siteInfo[$wikiId] = $info;
		}

		return $siteInfo;
	}

	/**
	 * @param array[] $raw
	 *
	 * @return array[]
	 */
	private function extractLocalIds( array $raw, $field ) {
		$localIds = [];

		foreach ( $raw as $wikiId => $info ) {
			if ( !isset( $info[$field] ) ) {
				continue;
			}
			foreach ( $info[$field] as $localId ) {
				$localIds[$localId] = $wikiId;
			}
		}

		return $localIds;
	}

}

$maintClass = 'ImportSites';
require_once RUN_MAINTENANCE_IF_MAIN;
