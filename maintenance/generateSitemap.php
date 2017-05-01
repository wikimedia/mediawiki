<?php
/**
 * Creates a sitemap for the site.
 *
 * Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank <jeluf@gmx.de> and
 * Brion Vibber <brion@pobox.com>
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that generates a sitemap for the site.
 *
 * @ingroup Maintenance
 */
class GenerateSitemap extends Maintenance {
	const GS_MAIN = -2;
	const GS_TALK = -1;

	/**
	 * The maximum amount of urls in a sitemap file
	 *
	 * @link http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
	 *
	 * @var int
	 */
	public $url_limit;

	/**
	 * The maximum size of a sitemap file
	 *
	 * @link http://www.sitemaps.org/faq.php#faq_sitemap_size
	 *
	 * @var int
	 */
	public $size_limit;

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
	 * The number of entries to save in each sitemap file
	 *
	 * @var array
	 */
	public $limit = [];

	/**
	 * Key => value entries of namespaces and their priorities
	 *
	 * @var array
	 */
	public $priorities = [];

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
	 * @var object
	 */
	public $dbr;

	/**
	 * A resource pointing to the sitemap index file
	 *
	 * @var resource
	 */
	public $findex;

	/**
	 * A resource pointing to a sitemap file
	 *
	 * @var resource
	 */
	public $file;

	/**
	 * Identifier to use in filenames, default $wgDBname
	 *
	 * @var string
	 */
	private $identifier;

	/**
	 * Constructor
	 */
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
	}

	/**
	 * Execute
	 */
	public function execute() {
		$this->setNamespacePriorities();
		$this->url_limit = 50000;
		$this->size_limit = pow( 2, 20 ) * 10;

		# Create directory if needed
		$fspath = $this->getOption( 'fspath', getcwd() );
		if ( !wfMkdirParents( $fspath, null, __METHOD__ ) ) {
			$this->error( "Can not create directory $fspath.", 1 );
		}

		$this->fspath = realpath( $fspath ) . DIRECTORY_SEPARATOR;
		$this->urlpath = $this->getOption( 'urlpath', "" );
		if ( $this->urlpath !== "" && substr( $this->urlpath, -1 ) !== '/' ) {
			$this->urlpath .= '/';
		}
		$this->identifier = $this->getOption( 'identifier', wfWikiID() );
		$this->compress = $this->getOption( 'compress', 'yes' ) !== 'no';
		$this->skipRedirects = $this->getOption( 'skip-redirects', false ) !== false;
		$this->dbr = $this->getDB( DB_REPLICA );
		$this->generateNamespaces();
		$this->timestamp = wfTimestamp( TS_ISO_8601, wfTimestampNow() );
		$this->findex = fopen( "{$this->fspath}sitemap-index-{$this->identifier}.xml", 'wb' );
		$this->main();
	}

	private function setNamespacePriorities() {
		global $wgSitemapNamespacesPriorities;

		// Custom main namespaces
		$this->priorities[self::GS_MAIN] = '0.5';
		// Custom talk namesspaces
		$this->priorities[self::GS_TALK] = '0.1';
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

		// Custom priorities
		if ( $wgSitemapNamespacesPriorities !== false ) {
			/**
			 * @var $wgSitemapNamespacesPriorities array
			 */
			foreach ( $wgSitemapNamespacesPriorities as $namespace => $priority ) {
				$float = floatval( $priority );
				if ( $float > 1.0 ) {
					$priority = '1.0';
				} elseif ( $float < 0.0 ) {
					$priority = '0.0';
				}
				$this->priorities[$namespace] = $priority;
			}
		}
	}

	/**
	 * Generate a one-dimensional array of existing namespaces
	 */
	function generateNamespaces() {
		// Only generate for specific namespaces if $wgSitemapNamespaces is an array.
		global $wgSitemapNamespaces;
		if ( is_array( $wgSitemapNamespaces ) ) {
			$this->namespaces = $wgSitemapNamespaces;

			return;
		}

		$res = $this->dbr->select( 'page',
			[ 'page_namespace' ],
			[],
			__METHOD__,
			[
				'GROUP BY' => 'page_namespace',
				'ORDER BY' => 'page_namespace',
			]
		);

		foreach ( $res as $row ) {
			$this->namespaces[] = $row->page_namespace;
		}
	}

	/**
	 * Get the priority of a given namespace
	 *
	 * @param int $namespace The namespace to get the priority for
	 * @return string
	 */
	function priority( $namespace ) {
		return isset( $this->priorities[$namespace] )
			? $this->priorities[$namespace]
			: $this->guessPriority( $namespace );
	}

	/**
	 * If the namespace isn't listed on the priority list return the
	 * default priority for the namespace, varies depending on whether it's
	 * a talkpage or not.
	 *
	 * @param int $namespace The namespace to get the priority for
	 * @return string
	 */
	function guessPriority( $namespace ) {
		return MWNamespace::isSubject( $namespace )
			? $this->priorities[self::GS_MAIN]
			: $this->priorities[self::GS_TALK];
	}

	/**
	 * Return a database resolution of all the pages in a given namespace
	 *
	 * @param int $namespace Limit the query to this namespace
	 * @return Resource
	 */
	function getPageRes( $namespace ) {
		return $this->dbr->select( 'page',
			[
				'page_namespace',
				'page_title',
				'page_touched',
				'page_is_redirect'
			],
			[ 'page_namespace' => $namespace ],
			__METHOD__
		);
	}

	/**
	 * Main loop
	 */
	public function main() {
		global $wgContLang;

		fwrite( $this->findex, $this->openIndex() );

		foreach ( $this->namespaces as $namespace ) {
			$res = $this->getPageRes( $namespace );
			$this->file = false;
			$this->generateLimit( $namespace );
			$length = $this->limit[0];
			$i = $smcount = 0;

			$fns = $wgContLang->getFormattedNsText( $namespace );
			$this->output( "$namespace ($fns)\n" );
			$skippedRedirects = 0; // Number of redirects skipped for that namespace
			foreach ( $res as $row ) {
				if ( $this->skipRedirects && $row->page_is_redirect ) {
					$skippedRedirects++;
					continue;
				}

				if ( $i++ === 0
					|| $i === $this->url_limit + 1
					|| $length + $this->limit[1] + $this->limit[2] > $this->size_limit
				) {
					if ( $this->file !== false ) {
						$this->write( $this->file, $this->closeFile() );
						$this->close( $this->file );
					}
					$filename = $this->sitemapFilename( $namespace, $smcount++ );
					$this->file = $this->open( $this->fspath . $filename, 'wb' );
					$this->write( $this->file, $this->openFile() );
					fwrite( $this->findex, $this->indexEntry( $filename ) );
					$this->output( "\t$this->fspath$filename\n" );
					$length = $this->limit[0];
					$i = 1;
				}
				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				$date = wfTimestamp( TS_ISO_8601, $row->page_touched );
				$entry = $this->fileEntry( $title->getCanonicalURL(), $date, $this->priority( $namespace ) );
				$length += strlen( $entry );
				$this->write( $this->file, $entry );
				// generate pages for language variants
				if ( $wgContLang->hasVariants() ) {
					$variants = $wgContLang->getVariants();
					foreach ( $variants as $vCode ) {
						if ( $vCode == $wgContLang->getCode() ) {
							continue; // we don't want default variant
						}
						$entry = $this->fileEntry(
							$title->getCanonicalURL( '', $vCode ),
							$date,
							$this->priority( $namespace )
						);
						$length += strlen( $entry );
						$this->write( $this->file, $entry );
					}
				}
			}

			if ( $this->skipRedirects && $skippedRedirects > 0 ) {
				$this->output( "  skipped $skippedRedirects redirect(s)\n" );
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
	 * @param string $file
	 * @param string $flags
	 * @return resource
	 */
	function open( $file, $flags ) {
		$resource = $this->compress ? gzopen( $file, $flags ) : fopen( $file, $flags );
		if ( $resource === false ) {
			throw new MWException( __METHOD__
				. " error opening file $file with flags $flags. Check permissions?" );
		}

		return $resource;
	}

	/**
	 * gzwrite() / fwrite() wrapper
	 *
	 * @param resource $handle
	 * @param string $str
	 */
	function write( &$handle, $str ) {
		if ( $handle === true || $handle === false ) {
			throw new MWException( __METHOD__ . " was passed a boolean as a file handle.\n" );
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
	 * @param resource $handle
	 */
	function close( &$handle ) {
		if ( $this->compress ) {
			gzclose( $handle );
		} else {
			fclose( $handle );
		}
	}

	/**
	 * Get a sitemap filename
	 *
	 * @param int $namespace The namespace
	 * @param int $count The count
	 * @return string
	 */
	function sitemapFilename( $namespace, $count ) {
		$ext = $this->compress ? '.gz' : '';

		return "sitemap-{$this->identifier}-NS_$namespace-$count.xml$ext";
	}

	/**
	 * Return the XML required to open an XML file
	 *
	 * @return string
	 */
	function xmlHead() {
		return '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	}

	/**
	 * Return the XML schema being used
	 *
	 * @return string
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
	 * @param string $filename The filename of the sitemap file
	 * @return string
	 */
	function indexEntry( $filename ) {
		return
			"\t<sitemap>\n" .
			"\t\t<loc>{$this->urlpath}$filename</loc>\n" .
			"\t\t<lastmod>{$this->timestamp}</lastmod>\n" .
			"\t</sitemap>\n";
	}

	/**
	 * Return the XML required to close a sitemap index file
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
	 * @param string $url An RFC 2396 compliant URL
	 * @param string $date A ISO 8601 date
	 * @param string $priority A priority indicator, 0.0 - 1.0 inclusive with a 0.1 stepsize
	 * @return string
	 */
	function fileEntry( $url, $date, $priority ) {
		return
			"\t<url>\n" .
			// T36666: $url may contain bad characters such as ampersands.
			"\t\t<loc>" . htmlspecialchars( $url ) . "</loc>\n" .
			"\t\t<lastmod>$date</lastmod>\n" .
			"\t\t<priority>$priority</priority>\n" .
			"\t</url>\n";
	}

	/**
	 * Return the XML required to close sitemap file
	 *
	 * @return string
	 */
	function closeFile() {
		return "</urlset>\n";
	}

	/**
	 * Populate $this->limit
	 *
	 * @param int $namespace
	 */
	function generateLimit( $namespace ) {
		// T19961: make a title with the longest possible URL in this namespace
		$title = Title::makeTitle( $namespace, str_repeat( "\xf0\xa8\xae\x81", 63 ) . "\xe5\x96\x83" );

		$this->limit = [
			strlen( $this->openFile() ),
			strlen( $this->fileEntry(
				$title->getCanonicalURL(),
				wfTimestamp( TS_ISO_8601, wfTimestamp() ),
				$this->priority( $namespace )
			) ),
			strlen( $this->closeFile() )
		];
	}
}

$maintClass = "GenerateSitemap";
require_once RUN_MAINTENANCE_IF_MAIN;
