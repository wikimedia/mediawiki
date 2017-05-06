<?php

use MediaWiki\Interwiki\InterwikiLookup;
use MediaWiki\MediaWikiServices;
use MediaWiki\Site\SiteInfoLookup;

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script for generating site info files from legacy settings.
 * See docs/siteinfo.txt for more information.
 *
 * @since 1.30
 *
 * @license GNU GPL v2+
 * @author Daniel Kinzler
 */
class BuildSiteInfo extends Maintenance {

	public function __construct() {
		$this->addDescription(
			"Builds site info files from legacy Interwiki and Site settings.\n"
			. "See docs/siteinfo.txt for more information."
		);

		$this->addArg( 'file', 'The output file for the file info. The file name must end in ".json" or ".php".', true );

		$this->addOption( 'interwiki-id-file', 'File to write interwiki prefixes to. The file name must end in ".json" or ".php".', false, true );
		$this->addOption( 'navigation-id-file', 'File to write navigation prefixes to. The file name must end in ".json" or ".php".', false, true );

		parent::__construct();
	}

	private function getFileFormat( $file ) {
		if ( preg_match( '/\.(php|json)$/', $file, $m ) ) {
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
				$array = json_decode( $data, true );
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
				$data = "<?php\nreturn $data;\n";
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
					$this->warn( "Conflicting data: $name -> $key: skipping {$data[$key]}, keeping $value" );
				}
			} else {
				$data[$key] = $value;
			}
		}

		return $data;
	}

	private function saveData( $file, array $newData ) {
		if ( file_exists( $file ) ) {
			$this->log( "Merging site info into $file" );
			$data = $this->readFile( $file );
			$data = $this->mergeData( "File $file", $data, $newData );
		} else {
			$this->log( "Creating new site info file $file" );
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
			$interwikiIdFile = $this->getOption( 'interwiki-id-file', $file );
			$navigationIdFile = $this->getOption( 'navigation-id-file', $file );

			$siteLookup = MediaWikiServices::getInstance()->getSiteLookup();
			$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
			$raw = $this->collectRawInfo( $interwikiLookup, $siteLookup );

			$this->log( "Building site info..." );
			$sites = $this->buildSiteInfo( $raw );
			$this->log( "Writing site info to $file..." );
			$this->saveData( $file, [ 'sites' => $sites ] );

			$this->log( "Building alias IDs..." );
			$aliasIds = $this->extractLocalIds( $raw, '_aliasIds_' );

			$this->log( "Writing alias IDs to $file..." );
			$array = [ 'ids' => [ SiteInfoLookup::ALIAS_ID => $aliasIds ] ];
			$this->saveData( $file, $array );

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
				$array = [ 'ids' => [ SiteInfoLookup::INTERLANGUAGE_ID => $navigationIds ] ];
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

		foreach ( $interwikiLookup->getAllPrefixes() as $interwikiRow ) {
			$wikiId = $this->getIdForInterwiki( $interwikiRow );
			$raw[$wikiId] = $this->buildInfoFromInterwiki( $interwikiRow );
		}

		/* @var Site $site */
		foreach ( $sites as $wikiId => $site ) {
			$wikiId = $site->getGlobalId();
			$info = $this->buildInfoFromSite( $site );

			if ( isset( $raw[$wikiId] ) ) {
				$raw[$wikiId] = $this->mergeData( "Site $wikiId", $raw[$wikiId], $info );
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
		list( $baseUrl, $linkPath )  = $this->splitUrl( $site->getLinkPath() );

		$info = [
			SiteInfoLookup::SITE_FAMILY => $site->getGroup(),
			SiteInfoLookup::SITE_LANGUAGE => $site->getLanguageCode(),
			SiteInfoLookup::SITE_LINK_PATH => $linkPath,
			SiteInfoLookup::SITE_BASE_URL => $baseUrl,
			SiteInfoLookup::SITE_IS_FORWARDABLE => $site->shouldForward(),
			SiteInfoLookup::SITE_NAME => $site->getDomain(),
		];

		if ( $site->getType() === Site::TYPE_MEDIAWIKI ) {
			$info[ SiteInfoLookup::SITE_TYPE ] = SiteInfoLookup::TYPE_MEDIAWIKI;
		}

		if ( $site instanceof MediaWikiSite ) {
			/* @var MediaWikiSite $site */
			$scriptPath = preg_replace( '!\$1!', '', $site->getRelativeFilePath() );
			$info[ SiteInfoLookup::SITE_SCRIPT_PATH ] = $scriptPath;
			$info[ SiteInfoLookup::SITE_LINK_PATH ] = $site->getRelativePagePath();
		}

		$info['_interwikiIds_'] = $site->getInterwikiIds();
		$info['_navigationIds_'] = $site->getNavigationIds();

		return array_filter( $info );
	}

	/**
	 * @param array $interwikiRow
	 *
	 * @return array
	 */
	private function buildInfoFromInterwiki( array $interwikiRow ) {
		global $wgExtraInterlanguageLinkPrefixes;

		$prefix = $interwikiRow['iw_prefix'];
		$interwiki = new Interwiki(
			$prefix,
			isset( $interwikiRow['iw_url'] ) ? $interwikiRow['iw_url'] : '',
			isset( $interwikiRow['iw_api'] ) ? $interwikiRow['iw_api'] : '',
			isset( $interwikiRow['iw_wikiid'] ) ? $interwikiRow['iw_wikiid'] : '',
			isset( $interwikiRow['iw_local'] ) ? $interwikiRow['iw_local'] : 0,
			isset( $interwikiRow['iw_trans'] ) ? $interwikiRow['iw_trans'] : 0
		);

		$info = [
			SiteInfoLookup::SITE_IS_FORWARDABLE => $interwiki->isLocal(),
			SiteInfoLookup::SITE_IS_TRANSCLUDABLE => $interwiki->isTranscludable(),
			SiteInfoLookup::SITE_NAME => $interwiki->getName(), // i18n applies!
			SiteInfoLookup::SITE_DESCRIPTION => $interwiki->getDescription(), // i18n applies!
			SiteInfoLookup::SITE_DB_NAME => $interwiki->getWikiID(),
			SiteInfoLookup::SITE_DESCRIPTION => $interwiki->getDescription(),
		];

		if ( $interwiki->isLocal() ) {
			$info[ SiteInfoLookup::SITE_DB_NAME ] = $interwiki->getWikiID();
		}

		list( $baseUrl, $articlePath )  = $this->splitUrl( $interwiki->getUrl() );
		$info[ SiteInfoLookup::SITE_BASE_URL ] = $baseUrl;
		$info[ SiteInfoLookup::SITE_LINK_PATH ] = $articlePath;

		$scriptPath = preg_replace( '![^/]*.php$!', '', $interwiki->getAPI() );
		// TODO: add Wikimedia specific magic defaults

		if ( $scriptPath ) {
			if ( substr_compare( $scriptPath, $baseUrl, 0, strlen( $baseUrl ) ) === 0 ) {
				$scriptPath = substr( $scriptPath, strlen( $baseUrl ) );
			}

			$info[ SiteInfoLookup::SITE_SCRIPT_PATH ] = $scriptPath;
			$info[ SiteInfoLookup::SITE_TYPE ] = SiteInfoLookup::TYPE_MEDIAWIKI;
		}

		$info[ '_interwikiIds_' ] = [ $prefix ];

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
		if ( preg_match( '!^((\w+:)?//([\w.-]+)(:\d+)?)(/.*)$!', $url, $m ) ) {
			return [ $m[1], $m[5] ];
		} else {
			$this->warn( 'Failed to split URL: ' . $url );
			return [ '', $url ];
		}
	}

	/**
	 * @param array $interwikiRow
	 *
	 * @return string
	 */
	private function getIdForInterwiki( array $interwikiRow ) {
		if ( isset( $interwikiRow['iw_wikiid'] ) && $interwikiRow['iw_wikiid'] !== '' ) {
			return $interwikiRow['iw_wikiid'];
		}

		if ( isset( $interwikiRow['iw_url'] ) && $interwikiRow['iw_url'] !== '' ) {
			return $this->getIdForHost( parse_url( $interwikiRow['iw_url'], PHP_URL_HOST ) );
		}

		return $interwikiRow['iw_preifx'];
	}


	/**
	 * @param array[] $raw
	 *
	 * @return array[]
	 */
	private function buildSiteInfo( array $raw ) {
		$siteInfo = [];

		foreach ( $raw as $wikiId => $info ) {
			unset( $info['_aliasIds_'] );
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

	private function getIdForHost( $host ) {
		$siteId = $host;

		if ( $host === 'wikimediafoundation.org' ) {
			return 'foundationwiki';
		}

		if ( $host === 'www.wikidata.org' ) {
			return 'wikidatawiki';
		}

		if ( $host === 'www.mediawiki.org' ) {
			return 'mediawikiwiki';
		}

		if ( $host === 'www.wikia.com' ) {
			return 'wikia';
		}

		if ( preg_match( '!^(bugzilla|phabricator|gerrit)\.wikimedia\.org$!', $host, $m ) ) {
			return $m[1];
		}

		if ( preg_match( '!^(\w+)\.(wikipedia|wikimedia)\.org$!', $host, $m ) ) {
			return $m[1] . 'wiki';
		}

		if ( preg_match( '!^(\w+)\.(wikis|wikia|wikispaces)\.com!', $host, $m ) ) {
			return $m[1];
		}

		if ( preg_match( '!^(\w+)\.(wiktionary|wiki(quote|source|news|books|versity|species|voyage))\.org$!', $host, $m ) ) {
			return $m[1] . $m[2];
		}

		$siteId = preg_replace( '!^(www|wiki|wikis)\.!', '', $siteId );
		$siteId = preg_replace( '!\.\w+$!', '', $siteId );
		$siteId = preg_replace( '![^\w]!', '_', $siteId );

		return $siteId;
	}

}

$maintClass = 'BuildSiteInfo';
require_once RUN_MAINTENANCE_IF_MAIN;
