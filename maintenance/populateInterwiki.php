<?php

$basePath = getenv( 'MW_INSTALL_PATH' ) !== false ? getenv( 'MW_INSTALL_PATH' ) : __DIR__ . '/..';

require_once $basePath . '/maintenance/Maintenance.php';

/**
 * Maintenance script that populates the interwiki table with list of sites from
 * a source wiki, such as English Wikipedia. (the default source)
 *
 * @since 1.27
 *
 * @license GPL-2.0+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class PopulateInterwiki extends Maintenance {

	/**
	 * @var string
	 */
	private $source;

	/**
	 * @var BagOStuff
	 */
	private $cache;

	public function __construct() {
		$this->addDescription( <<<TEXT
This script will populate the interwiki table, pulling in interwiki links that are used on Wikipedia
or another MediaWiki wiki.

When the script has finished, it will make a note of this in the database, and will not run again
without the --force option.

--source parameter is the url for the source wiki api, such as "https://en.wikipedia.org/w/api.php"
(the default) from which the script fetches the interwiki data and uses here to populate
the interwiki database table.
TEXT
		);

		$this->addOption( 'source', 'Source wiki for interwiki table, such as '
			. 'https://en.wikipedia.org/w/api.php (the default)', false, true );
		$this->addOption( 'force', 'Run regardless of whether the database says it has '
			. 'been run already.' );

		parent::__construct();
	}

	public function execute() {
		$force = $this->getOption( 'force', false );
		$this->source = $this->getOption( 'source', 'https://en.wikipedia.org/w/api.php' );

		$this->cache = wfGetMainCache();

		$data = $this->fetchLinks();

		if ( $data === false ) {
			$this->error( "Error during fetching data." );
		} else {
			$this->doPopulate( $data, $force );
		}
	}

	/**
	 * @return array[]|bool The 'interwikimap' sub-array or false on failure.
	 */
	protected function fetchLinks() {
		$url = wfArrayToCgi( [
			'action' => 'query',
			'meta' => 'siteinfo',
			'siprop' => 'interwikimap',
			'sifilteriw' => 'local',
			'format' => 'json'
		] );

		if ( !empty( $this->source ) ) {
			$url = rtrim( $this->source, '?' ) . '?' . $url;
		}

		$json = Http::get( $url );
		$data = json_decode( $json, true );

		if ( is_array( $data ) ) {
			return $data['query']['interwikimap'];
		} else {
			return false;
		}
	}

	/**
	 * @param array[] $data
	 * @param bool $force
	 *
	 * @return bool
	 */
	protected function doPopulate( array $data, $force ) {
		$dbw = wfGetDB( DB_MASTER );

		if ( !$force ) {
			$row = $dbw->selectRow(
				'updatelog',
				'1',
				[ 'ul_key' => 'populate interwiki' ],
				__METHOD__
			);

			if ( $row ) {
				$this->output( "Interwiki table already populated.  Use php " .
					"maintenance/populateInterwiki.php\n--force from the command line " .
					"to override.\n" );
				return true;
			}
		}

		foreach ( $data as $d ) {
			$prefix = $d['prefix'];

			$row = $dbw->selectRow(
				'interwiki',
				'1',
				[ 'iw_prefix' => $prefix ],
				__METHOD__
			);

			if ( ! $row ) {
				$dbw->insert(
					'interwiki',
					[
						'iw_prefix' => $prefix,
						'iw_url' => $d['url'],
						'iw_local' => 1
					],
					__METHOD__,
					'IGNORE'
				);
			}

			$this->clearCacheEntry( $prefix );
		}

		$this->output( "Interwiki links are populated.\n" );

		return true;
	}

	/**
	 * @param string $prefix
	 */
	private function clearCacheEntry( $prefix ) {
		$key = wfMemcKey( 'interwiki', $prefix );
		$this->cache->delete( $key );
	}

}

$maintClass = PopulateInterwiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
