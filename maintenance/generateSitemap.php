<?php
/**
 * Creates a sitemap for the site.
 *
 * Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank <jeluf@gmx.de> and
 * Brooke Vibber <bvibber@wikimedia.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 * @see http://www.sitemaps.org/
 * @see http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\SitemapGenerator;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IDatabase;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that generates a sitemap for the site.
 *
 * @ingroup Maintenance
 */
class GenerateSitemap extends Maintenance {
	/**
	 * The maximum amount of urls in a sitemap file
	 *
	 * @link http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
	 *
	 * @var int
	 */
	public $url_limit;

	/**
	 * The path to prepend to the filename
	 *
	 * @var string
	 */
	public $fspath;

	/**
	 * The URL path to prepend to filenames in the index;
	 * should resolve to the same directory as $fspath.
	 *
	 * @var string
	 */
	public $urlpath;

	/**
	 * Whether or not to use compression
	 *
	 * @var bool
	 */
	public $compress;

	/**
	 * Whether or not to include redirection pages
	 *
	 * @var bool
	 */
	public $skipRedirects;

	/**
	 * A one-dimensional array of namespaces in the wiki
	 *
	 * @var array
	 */
	public $namespaces = [];

	/**
	 * When this sitemap batch was generated
	 *
	 * @var string
	 */
	public $timestamp;

	/**
	 * A database replica DB object
	 *
	 * @var IDatabase
	 */
	public $dbr;

	/**
	 * A resource pointing to the sitemap index file
	 *
	 * @var resource
	 */
	public $indexFile;

	/**
	 * A resource pointing to a sitemap file
	 *
	 * @var resource|false
	 */
	public $file;

	/**
	 * Identifier to use in filenames, default $wgDBname
	 *
	 * @var string
	 */
	private $identifier;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Creates a sitemap for the site' );
		$this->addOption(
			'fspath',
			'The file system path to save to, e.g. /tmp/sitemap; defaults to current directory',
			false,
			true
		);
		$this->addOption(
			'urlpath',
			'The URL path corresponding to --fspath, prepended to filenames in the index; '
				. 'defaults to an empty string',
			false,
			true
		);
		$this->addOption(
			'compress',
			'Compress the sitemap files, can take value yes|no, default yes',
			false,
			true
		);
		$this->addOption( 'skip-redirects', 'Do not include redirecting articles in the sitemap' );
		$this->addOption(
			'identifier',
			'What site identifier to use for the wiki, defaults to $wgDBname',
			false,
			true
		);
		$this->addOption(
			'namespaces',
			'Only include pages in these namespaces in the sitemap, ' .
			'defaults to the value of wgSitemapNamespaces if not defined.',
			false, true, false, true
		);
		$this->addOption(
			'limit',
			'Maximum number of URLs per sitemap file. Default 50,000.',
			false,
			true
		);
	}

	/**
	 * Execute
	 */
	public function execute() {
		$this->url_limit = $this->getOption( 'limit', 50_000 );

		# Create directory if needed
		$fspath = $this->getOption( 'fspath', getcwd() );
		if ( !wfMkdirParents( $fspath, null, __METHOD__ ) ) {
			$this->fatalError( "Can not create directory $fspath." );
		}

		$dbDomain = WikiMap::getCurrentWikiDbDomain()->getId();
		$this->fspath = realpath( $fspath ) . DIRECTORY_SEPARATOR;
		$this->urlpath = $this->getOption( 'urlpath', "" );
		if ( $this->urlpath !== "" && substr( $this->urlpath, -1 ) !== '/' ) {
			$this->urlpath .= '/';
		}
		$this->identifier = $this->getOption( 'identifier', $dbDomain );
		$this->compress = $this->getOption( 'compress', 'yes' ) !== 'no';
		$this->skipRedirects = $this->hasOption( 'skip-redirects' );
		$this->dbr = $this->getReplicaDB();
		$this->timestamp = wfTimestamp( TS_ISO_8601, wfTimestampNow() );
		$encIdentifier = rawurlencode( $this->identifier );
		$indexPath = "{$this->fspath}sitemap-index-{$encIdentifier}.xml";
		$this->indexFile = fopen( "{$this->fspath}sitemap-index-{$encIdentifier}.xml", 'wb' );
		$this->main();
		$this->output( "Wrote index: $indexPath\n" );
	}

	/**
	 * Generate a one-dimensional array of existing namespaces
	 * @return array|null
	 */
	private function getNamespaces() {
		// Use the namespaces passed in via command line arguments if they are set.
		return $this->getOption( 'namespaces' )
			?? $this->getConfig()->get( MainConfigNames::SitemapNamespaces )
			?: null;
	}

	/**
	 * Main loop
	 */
	public function main() {
		$services = $this->getServiceContainer();
		$contLang = $services->getContentLanguage();
		$serverUrl = $services->getUrlUtils()->getServer( PROTO_CANONICAL ) ?? '';

		fwrite( $this->indexFile, $this->openIndex() );

		$generator = new SitemapGenerator(
			$contLang,
			$services->getLanguageConverterFactory(),
			$services->getGenderCache()
		);
		$generator->skipRedirects( $this->skipRedirects )
			->namespaces( $this->getNamespaces() )
			->limit( $this->url_limit );

		$sitemapId = 0;
		do {
			$filename = $this->sitemapFilename( $sitemapId++ );
			$filePath = $this->fspath . $filename;
			$file = $this->open( $filePath, 'wb' );
			$xml = $generator->getXml( $this->dbr );
			$this->write( $file, $xml );
			$this->close( $file );
			fwrite( $this->indexFile, $this->indexEntry( $filename, $serverUrl ) );
			$this->output( "Wrote sitemap: $filePath\n" );
		} while ( $generator->nextBatch() );

		fwrite( $this->indexFile, $this->closeIndex() );
		fclose( $this->indexFile );
	}

	/**
	 * gzopen() / fopen() wrapper
	 *
	 * @param string $file
	 * @param string $flags
	 * @return resource
	 */
	private function open( $file, $flags ) {
		$resource = $this->compress ? gzopen( $file, $flags ) : fopen( $file, $flags );
		if ( $resource === false ) {
			throw new RuntimeException( __METHOD__
				. " error opening file $file with flags $flags. Check permissions?" );
		}

		return $resource;
	}

	/**
	 * gzwrite() / fwrite() wrapper
	 *
	 * @param resource &$handle
	 * @param string $str
	 */
	private function write( &$handle, $str ) {
		if ( $handle === true || $handle === false ) {
			throw new InvalidArgumentException( __METHOD__ . " was passed a boolean as a file handle.\n" );
		}
		if ( $this->compress ) {
			gzwrite( $handle, $str );
		} else {
			fwrite( $handle, $str );
		}
	}

	/**
	 * gzclose() / fclose() wrapper
	 *
	 * @param resource &$handle
	 */
	private function close( &$handle ) {
		if ( $this->compress ) {
			gzclose( $handle );
		} else {
			fclose( $handle );
		}
	}

	/**
	 * Get a sitemap filename
	 *
	 * @param int $count
	 * @return string
	 */
	private function sitemapFilename( $count ) {
		$ext = $this->compress ? '.gz' : '';

		return "sitemap-{$this->identifier}-$count.xml$ext";
	}

	/**
	 * Return the XML required to open an XML file
	 *
	 * @return string
	 */
	private function xmlHead() {
		return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}

	/**
	 * Return the XML schema being used
	 *
	 * @return string
	 */
	private function xmlSchema() {
		return 'http://www.sitemaps.org/schemas/sitemap/0.9';
	}

	/**
	 * Return the XML required to open a sitemap index file
	 *
	 * @return string
	 */
	private function openIndex() {
		return $this->xmlHead() . '<sitemapindex xmlns="' . $this->xmlSchema() . '">' . "\n";
	}

	/**
	 * Return the XML for a single sitemap indexfile entry
	 *
	 * @param string $filename The filename of the sitemap file
	 * @param string $serverUrl Current server url
	 * @return string
	 */
	private function indexEntry( $filename, $serverUrl ) {
		return "\t<sitemap>\n" .
			"\t\t<loc>" . $serverUrl .
				( substr( $this->urlpath, 0, 1 ) === "/" ? "" : "/" ) .
				"{$this->urlpath}$filename</loc>\n" .
			"\t\t<lastmod>{$this->timestamp}</lastmod>\n" .
			"\t</sitemap>\n";
	}

	/**
	 * Return the XML required to close a sitemap index file
	 *
	 * @return string
	 */
	private function closeIndex() {
		return "</sitemapindex>\n";
	}
}

// @codeCoverageIgnoreStart
$maintClass = GenerateSitemap::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
