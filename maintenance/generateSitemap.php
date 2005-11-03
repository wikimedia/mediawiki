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
 * @link https://www.google.com/webmasters/sitemaps/docs/en/about.html
 * @link http://www.google.com/schemas/sitemap/0.84/sitemap.xsd
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$optionsWithArgs = array( 'host' );
/* */
require_once 'commandLine.inc';

if ( ! isset( $options['host'] ) ) {
	echo "Usage: php generateSitemap.php --host=hostname\n";
	exit(1);
} else {
	$_SERVER['HOSTNAME'] = $options['host'];
}

$gs = new GenerateSitemap( $options['host'] );
$gs->main();

class GenerateSitemap {
	var $host;
	var $cutoff = 9000;
	var $priorities = array(
		// Custom main namespaces
		-2			=> '0.5',
		// Custom talk namesspaces
		-1			=> '0.1',	
		NS_MAIN			=> '1.0',
		NS_TALK			=> '0.1',
		NS_USER			=> '0.5',
		NS_USER_TALK		=> '0.1',
		NS_PROJECT		=> '0.5',
		NS_PROJECT_TALK		=> '0.5',
		NS_IMAGE		=> '0.5',
		NS_IMAGE_TALK		=> '0.1',
		NS_MEDIAWIKI		=> '0.0',
		NS_MEDIAWIKI_TALK	=> '0.0',
		NS_TEMPLATE		=> '0.0',
		NS_TEMPLATE_TALK	=> '0.0',
		NS_HELP			=> '0.5',
		NS_HELP_TALK		=> '0.1',
		NS_CATEGORY		=> '0.5',
		NS_CATEGORY_TALK	=> '0.1',
	);
	var $namespaces = array();
	var $dbr;
	var $file, $findex;
	var $stderr;
	
	function GenerateSitemap( $host ) {
		global $wgDBname;

		$this->stderr = fopen( 'php://stderr', 'wt' );
		
		$this->host = $host;
		$this->dbr =& wfGetDB( DB_SLAVE );
		$this->generateNamespaces();
		$this->findex = fopen( "sitemap-index-$wgDBname.xml", 'wb' );
	}

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

	function priority( $namespace ) {
		return isset( $this->priorities[$namespace] ) ? $this->priorities[$namespace] : $this->guessPriority( $namespace );
	}

	function guessPriority( $namespace ) {
		return Namespace::isTalk( $namespace ) ? $this->priorities[-1] : $this->priorities[-2];
	}

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

	function main() {
		global $wgDBname;

		fwrite( $this->findex, $this->openIndex() );
		
		foreach ( $this->namespaces as $namespace ) {
			$res = $this->getPageRes( $namespace );
			$this->file = false;
			$i = $smcount = 0;
			
			while ( $row = $this->dbr->fetchObject( $res ) ) {
				if ( $i % $this->cutoff == 0 ) {
					if ( $this->file !== false ) {
						gzwrite( $this->file, $this->closeFile() );
						gzclose( $this->file );
					}
					++$smcount;
					$filename = "sitemap-$wgDBname-NS$namespace-$smcount.xml.gz";
					$this->file = gzopen( $filename, 'wb' );
					$this->debug( $namespace );
					gzwrite( $this->file, $this->openFile() );
					fwrite( $this->findex, $this->indexEntry( $filename ) );
					$this->debug( "\t$filename" );
				}
				++$i;
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$date = $this->ISO8601( $row->page_touched );
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

	function xmlHead() {
		return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}

	function xmlSchema() {
		return 'http://www.google.com/schemas/sitemap/0.84';
	}

	function openIndex() {
		return $this->xmlHead() . '<sitemapindex xmlns="' . $this->xmlSchema() . '">' . "\n";
	}

	function indexEntry( $filename ) {
		global $wgServer;
		
		return
			"\t<sitemap>\n" .
			"\t\t<loc>$wgServer/$filename</log>\n" .
			"\t</sitemap>\n";
	}

	function closeIndex() {
		return "</sitemapindex>\n";
	}

	function openFile() {
		return $this->xmlHead() . '<urlset xmlns="' . $this->xmlSchema() . '">' . "\n";
	}
	
	function fileEntry( $url, $date, $priority ) {
		return
			"\t<url>\n" .
			"\t\t<loc>$url</loc>\n" .
			"\t\t<lastmod>$date</lastmod>\n" .
			"\t\t<priority>$priority</priority>\n" .
			"\t</url>\n";
	}

	function closeFile() {
		return "</urlset>\n";
	}
	
	function ISO8601( $timestamp ) {
		return substr( wfTimestamp( TS_DB, $timestamp ), 0, 4 + 1 + 2 + 1 + 2 );
	}

	function debug( $str ) {
		fwrite( $this->stderr, "$str\n" );
	}
}

?>
