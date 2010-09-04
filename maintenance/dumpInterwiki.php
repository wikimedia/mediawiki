<?php
/**
 * Build constant slightly compact database of interwiki prefixes
 * Wikimedia specific!
 *
 * @file
 * @todo document
 * @ingroup Maintenance
 * @ingroup Wikimedia
 */

/**
 * @todo document
 * @ingroup Maintenance
 */
class Site {
	var $suffix, $lateral, $url;

	function __construct( $s, $l, $u ) {
		$this->suffix = $s;
		$this->lateral = $l;
		$this->url = $u;
	}

	function getURL( $lang ) {
		$xlang = str_replace( '_', '-', $lang );
		return "http://$xlang.{$this->url}/wiki/\$1";
	}
}

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class DumpInterwiki extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Build constant slightly compact database of interwiki prefixes.";
		$this->addOption( 'langlist', 'File with one language code per line', false, true );
		$this->addOption( 'dblist', 'File with one db per line', false, true );
		$this->addOption( 'specialdbs', "File with one 'special' db per line", false, true );
		$this->addOption( 'o', 'Cdb output file', false, true );
	}

	function execute() {
		# List of language prefixes likely to be found in multi-language sites
		$this->langlist = array_map( "trim", file( $this->getOption( 'langlist', "/home/wikipedia/common/langlist" ) ) );

		# List of all database names
		$this->dblist = array_map( "trim", file( $this->getOption( 'dblist', "/home/wikipedia/common/all.dblist" ) ) );

		# Special-case databases
		$this->specials = array_flip(	array_map( "trim", file( $this->getOption( 'specialdbs', "/home/wikipedia/common/special.dblist" ) ) ) );

		if ( $this->hasOption( 'o' ) ) {
			$this->dbFile = CdbWriter::open( $this->getOption( 'o' ) ) ;
		} else {
			$this->dbFile = false;
		}

		$this->getRebuildInterwikiDump();
	}

	function getRebuildInterwikiDump() {
		global $wgContLang;

		# Multi-language sites
		# db suffix => db suffix, iw prefix, hostname
		$sites = array(
			'wiki' => new Site( 'wiki', 'w', 'wikipedia.org' ),
			'wiktionary' => new Site( 'wiktionary', 'wikt', 'wiktionary.org' ),
			'wikiquote' => new Site( 'wikiquote', 'q', 'wikiquote.org' ),
			'wikibooks' => new Site( 'wikibooks', 'b', 'wikibooks.org' ),
			'wikinews' => new Site( 'wikinews', 'n', 'wikinews.org' ),
			'wikisource' => new Site( 'wikisource', 's', 'wikisource.org' ),
			'wikimedia' => new Site( 'wikimedia', 'chapter', 'wikimedia.org' ),
			'wikiversity' => new Site( 'wikiversity', 'v', 'wikiversity.org' ),
		);

		# Extra interwiki links that can't be in the intermap for some reason
		$extraLinks = array(
			array( 'm', 'http://meta.wikimedia.org/wiki/$1', 1 ),
			array( 'meta', 'http://meta.wikimedia.org/wiki/$1', 1 ),
			array( 'sep11', 'http://sep11.wikipedia.org/wiki/$1', 1 ),
		);

		# Language aliases, usually configured as redirects to the real wiki in apache
		# Interlanguage links are made directly to the real wiki
		# Something horrible happens if you forget to list an alias here, I can't
		#   remember what
		$this->languageAliases = array(
			'zh-cn' => 'zh',
			'zh-tw' => 'zh',
			'dk' => 'da',
			'nb' => 'no',
		);

		# Special case prefix rewrites, for the benefit of Swedish which uses s:t
		# as an abbreviation for saint
		$this->prefixRewrites = array(
			'svwiki' => array( 's' => 'src' ),
		);

		# Construct a list of reserved prefixes
		$reserved = array();
		foreach ( $this->langlist as $lang ) {
			$reserved[$lang] = 1;
		}
		foreach ( $this->languageAliases as $alias => $lang ) {
			$reserved[$alias] = 1;
		}
		foreach ( $sites as $site ) {
			$reserved[$site->lateral] = 1;
		}

		# Extract the intermap from meta
		$intermap = Http::get( 'http://meta.wikimedia.org/w/index.php?title=Interwiki_map&action=raw', 30 );
		$lines = array_map( 'trim', explode( "\n", trim( $intermap ) ) );

		if ( !$lines || count( $lines ) < 2 ) {
			$this->error( "m:Interwiki_map not found", true );
		}

		# Global iterwiki map
		foreach ( $lines as $line ) {
			if ( preg_match( '/^\|\s*(.*?)\s*\|\|\s*(.*?)\s*$/', $line, $matches ) ) {
				$prefix = $wgContLang->lc( $matches[1] );
				$prefix = str_replace( ' ', '_', $prefix );
				$prefix = strtolower( $matches[1] );
				$url = $matches[2];
				if ( preg_match( '/(wikipedia|wiktionary|wikisource|wikiquote|wikibooks|wikimedia)\.org/', $url ) ) {
					$local = 1;
				} else {
					$local = 0;
				}

				if ( empty( $reserved[$prefix] ) ) {
					$imap  = array( "iw_prefix" => $prefix, "iw_url" => $url, "iw_local" => $local );
					$this->makeLink ( $imap, "__global" );
				}
			}
		}

		# Exclude Wikipedia for Wikipedia
		$this->makeLink ( array ( 'iw_prefix' => 'wikipedia', 'is_url' => null ), "_wiki" );
		      
		# Multilanguage sites
		foreach ( $sites as $site ) {
			$this->makeLanguageLinks ( $site, "_" . $site->suffix );
		}


		foreach ( $dblist as $db ) {
			if ( isset( $this->specials[$db] ) ) {
				# Special wiki
				# Has interwiki links and interlanguage links to wikipedia

				$this->makeLink( array( 'iw_prefix' => $db, 'iw_url' => "wiki" ), "__sites" );
				# Links to multilanguage sites
				foreach ( $sites as $targetSite ) {
					$this->makeLink( array( 'iw_prefix' => $targetSite->lateral,
						'iw_url' => $targetSite->getURL( 'en' ),
						'iw_local' => 1 ), $db );
				}
			} else {
				# Find out which site this DB belongs to
				$site = false;
				foreach ( $sites as $candidateSite ) {
					$suffix = $candidateSite->suffix;
					if ( preg_match( "/(.*)$suffix$/", $db, $matches ) ) {
						$site = $candidateSite;
						break;
					}
				}
				$this->makeLink( array( 'iw_prefix' => $db, 'iw_url' => $site->suffix ), "__sites" );
				if ( !$site ) {
					$this->error( "Invalid database $db\n" );
					continue;
				}
				$lang = $matches[1];

				# Lateral links
				foreach ( $sites as $targetSite ) {
					if ( $targetSite->suffix != $site->suffix ) {
						$this->makeLink( array( 'iw_prefix' => $targetSite->lateral,
							'iw_url' => $targetSite->getURL( $lang ),
							'iw_local' => 1 ), $db );
					}
				}

				if ( $site->suffix == "wiki" ) {
					$this->makeLink( array( 'iw_prefix' => 'w',
						'iw_url' => "http://en.wikipedia.org/wiki/$1",
						'iw_local' => 1 ), $db );
				}

			}
		}
		foreach ( $extraLinks as $link ) {
			$this->makeLink( $link, "__global" );
		}
	}

	# ------------------------------------------------------------------------------------------

	# Executes part of an INSERT statement, corresponding to all interlanguage links to a particular site
	function makeLanguageLinks( &$site, $source ) {
		# Actual languages with their own databases
		foreach ( $this->langlist as $targetLang ) {
			$this->makeLink( array( $targetLang, $site->getURL( $targetLang ), 1 ), $source );
		}

		# Language aliases
		foreach ( $this->languageAliases as $alias => $lang ) {
			$this->makeLink( array( $alias, $site->getURL( $lang ), 1 ), $source );
		}
	}

	function makeLink( $entry, $source ) {
		if ( isset( $this->prefixRewrites[$source] ) && isset( $this->prefixRewrites[$source][$entry[0]] ) )
			$entry[0] = $this->prefixRewrites[$source][$entry[0]];

		if ( !array_key_exists( "iw_prefix", $entry ) ) {
			$entry = array( "iw_prefix" => $entry[0], "iw_url" => $entry[1], "iw_local" => $entry[2] );
		}
		if ( array_key_exists( $source, $this->prefixRewrites ) &&
				array_key_exists( $entry['iw_prefix'], $this->prefixRewrites[$source] ) ) {
			$entry['iw_prefix'] = $this->prefixRewrites[$source][$entry['iw_prefix']];
		}

		if ( $this->dbFile ) {
			$this->dbFile->set( "{$source}:{$entry['iw_prefix']}", trim( "{$entry['iw_local']} {$entry['iw_url']}" ) );
		} else {
			$this->output( "{$source}:{$entry['iw_prefix']} {$entry['iw_url']} {$entry['iw_local']}\n" );
		}
	}
}

$maintClass = "DumpInterwiki";
require_once( DO_MAINTENANCE );

