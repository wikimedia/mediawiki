<?php
define( 'GS_MAIN', -2 );
define( 'GS_TALK', -1 );
/**
 * Creates a sitemap for the site
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
 * @ingroup Maintenance
 *
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @copyright Copyright © 2005, Jens Frank <jeluf@gmx.de>
 * @copyright Copyright © 2005, Brion Vibber <brion@pobox.com>
 *
 * @see http://www.sitemaps.org/
 * @see http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class GenerateSitemap extends Maintenance {
	/**
	 * The maximum amount of urls in a sitemap file
	 *
	 * @link http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
	 *
	 * @var int
	 */
	var $url_limit;

	/**
	 * The maximum size of a sitemap file
	 *
	 * @link http://www.sitemaps.org/faq.php#faq_sitemap_size
	 *
	 * @var int
	 */
	var $size_limit;

	/**
	 * The path to prepend to the filename
	 *
	 * @var string
	 */
	var $fspath;

	/**
	 * The path to append to the domain name
	 *
	 * @var string
	 */
	var $path;

	/**
	 * Whether or not to use compression
	 *
	 * @var bool
	 */
	var $compress;

	/**
	 * The number of entries to save in each sitemap file
	 *
	 * @var array
	 */
	var $limit = array();

	/**
	 * Key => value entries of namespaces and their priorities
	 *
	 * @var array
	 */
	var $priorities = array();

	/**
	 * A one-dimensional array of namespaces in the wiki
	 *
	 * @var array
	 */
	var $namespaces = array();

	/**
	 * When this sitemap batch was generated
	 *
	 * @var string
	 */
	var $timestamp;

	/**
	 * A database slave object
	 *
	 * @var object
	 */
	var $dbr;

	/**
	 * A resource pointing to the sitemap index file
	 *
	 * @var resource
	 */
	var $findex;


	/**
	 * A resource pointing to a sitemap file
	 *
	 * @var resource
	 */
	var $file;

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Creates a sitemap for the site";
		$this->addOption( 'fspath', 'The file system path to save to, e.g. /tmp/sitemap' .
									"\n\t\tdefaults to current directory", false, true );
		$this->addOption( 'server', "The protocol and server name to use in URLs, e.g.\n" .
									"\t\thttp://en.wikipedia.org. This is sometimes necessary because\n" .
									"\t\tserver name detection may fail in command line scripts.", false, true );
		$this->addOption( 'compress', 'Compress the sitemap files, can take value yes|no, default yes' );
	}

	/**
	 * Execute
	 */
	public function execute() {
		global $wgScriptPath;
		$this->setNamespacePriorities();
		$this->url_limit = 50000;
		$this->size_limit = pow( 2, 20 ) * 10;
		$this->fspath = self::init_path( $this->getOption( 'fspath', getcwd() ) );
		$this->compress = $this->getOption( 'compress', 'yes' ) !== 'no';
		$this->dbr = wfGetDB( DB_SLAVE );
		$this->generateNamespaces();
		$this->timestamp = wfTimestamp( TS_ISO_8601, wfTimestampNow() );
		$this->findex = fopen( "{$this->fspath}sitemap-index-" . wfWikiID() . ".xml", 'wb' );
		$this->main();
	}

	private function setNamespacePriorities() {
		// Custom main namespaces
		$this->priorities[GS_MAIN] = '0.5';
		// Custom talk namesspaces
		$this->priorities[GS_TALK] = '0.1';
		// MediaWiki standard namespaces
		$this->priorities[NS_MAIN] = '1.0';
		$this->priorities[NS_TALK] = '0.1';
		$this->priorities[NS_USER] = '0.5';
		$this->priorities[NS_USER_TALK] = '0.1';
		$this->priorities[NS_PROJECT] = '0.5';
		$this->priorities[NS_PROJECT_TALK] = '0.1';
		$this->priorities[NS_FILE] = '0.5';
		$this->priorities[NS_FILE_TALK] = '0.1';
		$this->priorities[NS_MEDIAWIKI] = '0.0';
		$this->priorities[NS_MEDIAWIKI_TALK] = '0.1';
		$this->priorities[NS_TEMPLATE] = '0.0';
		$this->priorities[NS_TEMPLATE_TALK] = '0.1';
		$this->priorities[NS_HELP] = '0.5';
		$this->priorities[NS_HELP_TALK] = '0.1';
		$this->priorities[NS_CATEGORY] = '0.5';
		$this->priorities[NS_CATEGORY_TALK] = '0.1';
	}

	/**
	 * Create directory if it does not exist and return pathname with a trailing slash
	 */
	private static function init_path( $fspath ) {
		if( !isset( $fspath ) ) {
			return null;
		}
		# Create directory if needed
		if( $fspath && !is_dir( $fspath ) ) {
			wfMkdirParents( $fspath ) or die("Can not create directory $fspath.\n");
		}

		return realpath( $fspath ). DIRECTORY_SEPARATOR ;
	}

	/**
	 * Generate a one-dimensional array of existing namespaces
	 */
	function generateNamespaces() {
		// Only generate for specific namespaces if $wgSitemapNamespaces is an array.
		global $wgSitemapNamespaces;
		if( is_array( $wgSitemapNamespaces ) ) {
			$this->namespaces = $wgSitemapNamespaces;
			return;
		}

		$res = $this->dbr->select( 'page',
			array( 'page_namespace' ),
			array(),
			__METHOD__,
			array(
				'GROUP BY' => 'page_namespace',
				'ORDER BY' => 'page_namespace',
			)
		);

		foreach ( $res as $row )
			$this->namespaces[] = $row->page_namespace;
	}

	/**
	 * Get the priority of a given namespace
	 *
	 * @param int $namespace The namespace to get the priority for
	 +
	 * @return string
	 */

	function priority( $namespace ) {
		return isset( $this->priorities[$namespace] ) ? $this->priorities[$namespace] : $this->guessPriority( $namespace );
	}

	/**
	 * If the namespace isn't listed on the priority list return the
	 * default priority for the namespace, varies depending on whether it's
	 * a talkpage or not.
	 *
	 * @param int $namespace The namespace to get the priority for
	 *
	 * @return string
	 */
	function guessPriority( $namespace ) {
		return MWNamespace::isMain( $namespace ) ? $this->priorities[GS_MAIN] : $this->priorities[GS_TALK];
	}

	/**
	 * Return a database resolution of all the pages in a given namespace
	 *
	 * @param int $namespace Limit the query to this namespace
	 *
	 * @return resource
	 */
	function getPageRes( $namespace ) {
		return $this->dbr->select( 'page',
			array(
				'page_namespace',
				'page_title',
				'page_touched',
			),
			array( 'page_namespace' => $namespace ),
			__METHOD__
		);
	}

	/**
	 * Main loop
	 *
	 * @access public
	 */
	function main() {
		global $wgContLang;

		fwrite( $this->findex, $this->openIndex() );

		foreach ( $this->namespaces as $namespace ) {
			$res = $this->getPageRes( $namespace );
			$this->file = false;
			$this->generateLimit( $namespace );
			$length = $this->limit[0];
			$i = $smcount = 0;

			$fns = $wgContLang->getFormattedNsText( $namespace );
			$this->output( "$namespace ($fns)" );
			foreach ( $res as $row ) {
				if ( $i++ === 0 || $i === $this->url_limit + 1 || $length + $this->limit[1] + $this->limit[2] > $this->size_limit ) {
					if ( $this->file !== false ) {
						$this->write( $this->file, $this->closeFile() );
						$this->close( $this->file );
					}
					$filename = $this->sitemapFilename( $namespace, $smcount++ );
					$this->file = $this->open( $this->fspath . $filename, 'wb' );
					$this->write( $this->file, $this->openFile() );
					fwrite( $this->findex, $this->indexEntry( $filename ) );
					$this->output( "\t$this->fspath$filename" );
					$length = $this->limit[0];
					$i = 1;
				}
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$date = wfTimestamp( TS_ISO_8601, $row->page_touched );
				$entry = $this->fileEntry( $title->getFullURL(), $date, $this->priority( $namespace ) );
				$length += strlen( $entry );
				$this->write( $this->file, $entry );
				// generate pages for language variants
				if($wgContLang->hasVariants()){
					$variants = $wgContLang->getVariants();
					foreach($variants as $vCode){
						if($vCode==$wgContLang->getCode()) continue; // we don't want default variant
						$entry = $this->fileEntry( $title->getFullURL('',$vCode), $date, $this->priority( $namespace ) );
						$length += strlen( $entry );
						$this->write( $this->file, $entry );
					}
				}
			}
			if ( $this->file ) {
				$this->write( $this->file, $this->closeFile() );
				$this->close( $this->file );
			}
		}
		fwrite( $this->findex, $this->closeIndex() );
		fclose( $this->findex );
	}

	/**
	 * gzopen() / fopen() wrapper
	 *
	 * @return resource
	 */
	function open( $file, $flags ) {
		return $this->compress ? gzopen( $file, $flags ) : fopen( $file, $flags );
	}

	/**
	 * gzwrite() / fwrite() wrapper
	 */
	function write( &$handle, $str ) {
		if ( $this->compress )
			gzwrite( $handle, $str );
		else
			fwrite( $handle, $str );
	}

	/**
	 * gzclose() / fclose() wrapper
	 */
	function close( &$handle ) {
		if ( $this->compress )
			gzclose( $handle );
		else
			fclose( $handle );
	}

	/**
	 * Get a sitemap filename
	 *
	 * @static
	 *
	 * @param int $namespace The namespace
	 * @param int $count The count
	 *
	 * @return string
	 */
	function sitemapFilename( $namespace, $count ) {
		$ext = $this->compress ? '.gz' : '';
		return "sitemap-".wfWikiID()."-NS_$namespace-$count.xml$ext";
	}

	/**
	 * Return the XML required to open an XML file
	 *
	 * @static
	 *
	 * @return string
	 */
	function xmlHead() {
		return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}

	/**
	 * Return the XML schema being used
	 *
	 * @static
	 *
	 * @returns string
	 */
	function xmlSchema() {
		return 'http://www.sitemaps.org/schemas/sitemap/0.9';
	}

	/**
	 * Return the XML required to open a sitemap index file
	 *
	 * @return string
	 */
	function openIndex() {
		return $this->xmlHead() . '<sitemapindex xmlns="' . $this->xmlSchema() . '">' . "\n";
	}

	/**
	 * Return the XML for a single sitemap indexfile entry
	 *
	 * @static
	 *
	 * @param string $filename The filename of the sitemap file
	 *
	 * @return string
	 */
	function indexEntry( $filename ) {
		return
			"\t<sitemap>\n" .
			"\t\t<loc>$filename</loc>\n" .
			"\t\t<lastmod>{$this->timestamp}</lastmod>\n" .
			"\t</sitemap>\n";
	}

	/**
	 * Return the XML required to close a sitemap index file
	 *
	 * @static
	 *
	 * @return string
	 */
	function closeIndex() {
		return "</sitemapindex>\n";
	}

	/**
	 * Return the XML required to open a sitemap file
	 *
	 * @return string
	 */
	function openFile() {
		return $this->xmlHead() . '<urlset xmlns="' . $this->xmlSchema() . '">' . "\n";
	}

	/**
	 * Return the XML for a single sitemap entry
	 *
	 * @static
	 *
	 * @param string $url An RFC 2396 compliant URL
	 * @param string $date A ISO 8601 date
	 * @param string $priority A priority indicator, 0.0 - 1.0 inclusive with a 0.1 stepsize
	 *
	 * @return string
	 */
	function fileEntry( $url, $date, $priority ) {
		return
			"\t<url>\n" .
			"\t\t<loc>$url</loc>\n" .
			"\t\t<lastmod>$date</lastmod>\n" .
			"\t\t<priority>$priority</priority>\n" .
			"\t</url>\n";
	}

	/**
	 * Return the XML required to close sitemap file
	 *
	 * @static
	 * @return string
	 */
	function closeFile() {
		return "</urlset>\n";
	}

	/**
	 * Populate $this->limit
	 */
	function generateLimit( $namespace ) {
		$title = Title::makeTitle( $namespace, str_repeat( "\xf0\xa8\xae\x81", 63 ) . "\xe5\x96\x83" );

		$this->limit = array(
			strlen( $this->openFile() ),
			strlen( $this->fileEntry( $title->getFullUrl(), wfTimestamp( TS_ISO_8601, wfTimestamp() ), $this->priority( $namespace ) ) ),
			strlen( $this->closeFile() )
		);
	}
}

$maintClass = "GenerateSitemap";
require_once( DO_MAINTENANCE );
