<?php
/**
 * Creates a Google sitemap for the site
 *
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @copyright Copyright © 2005, Jens Frank <jeluf@gmx.de>
 * @copyright Copyright © 2005, Brion Vibber <brion@pobox.com>
 *
 * @link http://www.google.com/webmasters/sitemaps/docs/en/about.html
 * @link http://www.google.com/schemas/sitemap/0.84/sitemap.xsd
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( isset( $argv[1] ) )
	$_SERVER['SERVER_NAME'] = $argv[1];

$optionsWithArgs = array( 'path' );
/* */
require_once 'commandLine.inc';

define( 'GS_MAIN', -2 );
define( 'GS_TALK', -1 );

$gs = new GenerateSitemap( @$options['path'] );
$gs->main();

class GenerateSitemap {
	/**
	 * The number of entries to save in each sitemap file
	 *
	 * @var int
	 */
	var $limit;

	/**
	 * Key => value entries of namespaces and their priorities
	 *
	 * @var array
	 */
	var $priorities = array(
		// Custom main namespaces
		GS_MAIN			=> '0.5',
		// Custom talk namesspaces
		GS_TALK			=> '0.1',
		// MediaWiki standard namespaces
		NS_MAIN			=> '1.0',
		NS_TALK			=> '0.1',
		NS_USER			=> '0.5',
		NS_USER_TALK		=> '0.1',
		NS_PROJECT		=> '0.5',
		NS_PROJECT_TALK		=> '0.1',
		NS_IMAGE		=> '0.5',
		NS_IMAGE_TALK		=> '0.1',
		NS_MEDIAWIKI		=> '0.0',
		NS_MEDIAWIKI_TALK	=> '0.1',
		NS_TEMPLATE		=> '0.0',
		NS_TEMPLATE_TALK	=> '0.1',
		NS_HELP			=> '0.5',
		NS_HELP_TALK		=> '0.1',
		NS_CATEGORY		=> '0.5',
		NS_CATEGORY_TALK	=> '0.1',
	);

	/**
	 * A one-dimensional array of namespaces in the wiki
	 *
	 * @var array
	 */
	var $namespaces = array();

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
	 * A resource pointing to php://stderr
	 *
	 * @var resource
	 */
	var $stderr;

	/**
	 * Constructor
	 *
	 * @param string $path The path to prepend to the filenames, used to
	 *                     save them somewhere else than in the root directory
	 */
	function GenerateSitemap( $path ) {
		global $wgDBname;

		$this->path = isset( $path ) ? $path : '';
		$this->stderr = fopen( 'php://stderr', 'wt' );
		
		$this->dbr =& wfGetDB( DB_SLAVE );
		$this->generateNamespaces();
		$this->generateLimit( NS_MAIN );
		$this->findex = fopen( "{$this->path}sitemap-index-$wgDBname.xml", 'wb' );
	}

	/**
	 * Generate a one-dimensional array of existing namespaces
	 */
	function generateNamespaces() {
		$fname = 'GenerateSitemap::generateNamespaces';
		
		$res = $this->dbr->select( 'page',
			array( 'page_namespace' ),
			array(),
			$fname,
			array(
				'GROUP BY' => 'page_namespace',
				'ORDER BY' => 'page_namespace',
			)
		);

		while ( $row = $this->dbr->fetchObject( $res ) )
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
		return Namespace::isMain( $namespace ) ? $this->priorities[GS_MAIN] : $this->priorities[GS_TALK];
	}

	/**
	 * Return a database resolution of all the pages in a given namespace
	 *
	 * @param int $namespace Limit the query to this namespace
	 *
	 * @return resource
	 */
	function getPageRes( $namespace ) {
		$fname = 'GenerateSitemap::getPageRes';

		return $this->dbr->select( 'page',
			array( 
				'page_namespace',
				'page_title',
				'page_is_redirect',
				'page_touched',
			),
			array( 'page_namespace' => $namespace ),
			$fname
		);
	}

	/**
	 * Main loop
	 *
	 * @access public
	 */
	function main() {
		global $wgDBname;

		fwrite( $this->findex, $this->openIndex() );
		
		foreach ( $this->namespaces as $namespace ) {
			$res = $this->getPageRes( $namespace );
			$this->file = false;
			$i = $smcount = 0;
			
			$this->debug( $namespace );
			while ( $row = $this->dbr->fetchObject( $res ) ) {
				if ( $i % $this->limit === 0 ) {
					if ( $this->file !== false ) {
						gzwrite( $this->file, $this->closeFile() );
						gzclose( $this->file );
					}
					$this->generateLimit( $namespace );
					$filename = "sitemap-$wgDBname-NS_$namespace-$smcount.xml.gz";
					++$smcount;
					$this->file = gzopen( $this->path . $filename, 'wb' );
					gzwrite( $this->file, $this->openFile() );
					fwrite( $this->findex, $this->indexEntry( $filename ) );
					$this->debug( "\t$filename" );
				}
				++$i;
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$date = wfTimestamp( TS_ISO_8601, $row->page_touched );
				gzwrite( $this->file, $this->fileEntry( $title->getFullURL(), $date, $this->priority( $namespace ) ) );
			}
			if ( $this->file ) {
				gzwrite( $this->file, $this->closeFile() );
				gzclose( $this->file );
			}
		}
		fwrite( $this->findex, $this->closeIndex() );
		fclose( $this->findex );
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
		return 'http://www.google.com/schemas/sitemap/0.84';
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
		global $wgServer, $wgScriptPath;
		
		return
			"\t<sitemap>\n" .
			"\t\t<loc>$wgServer$wgScriptPath/$filename</log>\n" .
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
	 * @param string $url An RFC 2396 compilant URL
	 * @param string $date A ISO 8601 date
	 * @param string $priority A priority indicator, 0.0 - 1.0 inclusive with a 0.1 stepsize
	 *
	 r
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
	 * Write a string to stderr followed by a UNIX newline
	 */
	function debug( $str ) {
		fwrite( $this->stderr, "$str\n" );
	}

	/**
	 * According to the sitemap specification each sitemap must contain no
	 * more than 50,000 urls and no more than 2^20 bytes (10MB), this
	 * function calculates how many urls we can have in each file assuming
	 * that we have the worst case of 63 four byte characters and 1 three
	 * byte character in the title (63*4+1*3 = 255)
	 */
	function generateLimit( $namespace ) {
		$title = Title::makeTitle( $namespace, str_repeat( "\xf0\xa8\xae\x81", 63 ) . "\xe5\x96\x83" );
		
		$olen = strlen( $this->openFile() );
		$elen = strlen( $this->fileEntry( $title->getFullUrl(), wfTimestamp( TS_ISO_8601, wfTimestamp() ), '1.0' ) );
		$clen = strlen( $this->closeFile() );

		for ( $i = 1, $etot = $elen; ( $olen + $clen + $etot + $elen ) <= pow( 2, 20 ); ++$i )
			$etot += $elen;
		
		$this->limit = $i;
	}
}

?>
