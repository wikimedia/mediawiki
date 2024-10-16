<?php
/**
 * Prints the birthdate and age of a wiki install.
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
 * @author Derick Alangi
 * @author Daniel Kinzler
 * @since 1.39
 */

use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Utils\MWTimestamp;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * @ingroup Maintenance
 */
class WikiBirthday extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( "Print this wiki's birthday and age" );
	}

	/**
	 * @param string $wikiCreatedAt The date the wiki was created
	 *
	 * @return DateInterval|false The wiki age
	 */
	private function computeAge( string $wikiCreatedAt ) {
		return date_diff(
			date_create( MWTimestamp::now() ),
			date_create( $wikiCreatedAt )
		);
	}

	public function execute() {
		$dbr = $this->getReplicaDB();

		$revId = $dbr->newSelectQueryBuilder()
			->table( 'revision' )
			->field( 'MIN(rev_id)' )
			->caller( __METHOD__ )
			->fetchField();

		$archiveRevId = $dbr->newSelectQueryBuilder()
			->table( 'archive' )
			->field( 'MIN(ar_rev_id)' )
			->caller( __METHOD__ )
			->fetchField();

		if ( $archiveRevId && ( $archiveRevId < $revId || !$revId ) ) {
			$timestamp = $dbr->newSelectQueryBuilder()
				->table( 'archive' )
				->field( 'ar_timestamp' )
				->where( [ 'ar_rev_id' => (int)$archiveRevId ] )
				->caller( __METHOD__ )
				->fetchField();
		} else {
			$timestamp = $dbr->newSelectQueryBuilder()
				->table( 'revision' )
				->field( 'rev_timestamp' )
				->where( [ 'rev_id' => (int)$revId ] )
				->caller( __METHOD__ )
				->fetchField();
		}

		$birthDay = $this->getServiceContainer()->getContentLanguage()
			->getHumanTimestamp( MWTimestamp::getInstance( $timestamp ) );

		$text = "Wiki was created on: " . $birthDay . " <age: " .
			$this->computeAge( $timestamp )->format(
				"%y yr(s), %m month(s), %d day(s)"
			) . " old>.";

		$this->output( $text . "\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = WikiBirthday::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
