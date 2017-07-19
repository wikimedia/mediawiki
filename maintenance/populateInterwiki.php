<?php

/**
 * Maintenance script that populates the interwiki table with list of sites from
 * a source wiki, such as English Wikipedia. (the default source)
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
 * @author Katie Filbert < aude.wiki@gmail.com >
 */

require_once __DIR__ . '/Maintenance.php';

class PopulateInterwiki extends Maintenance {

	/**
	 * @var string
	 */
	private $source;

	public function __construct() {
		parent::__construct();

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
	}

	public function execute() {
		$force = $this->hasOption( 'force' );
		$this->source = $this->getOption( 'source', 'https://en.wikipedia.org/w/api.php' );

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

			if ( !$row ) {
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

			Interwiki::invalidateCache( $prefix );
		}

		$this->output( "Interwiki links are populated.\n" );

		return true;
	}

}

$maintClass = PopulateInterwiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
