<?php
/**
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
 */

use MediaWiki\MainConfigNames;

require_once __DIR__ . '/Maintenance.php';

/**
 * Run automatically with update.php
 *
 * - Changes "rfc" URL to use tools.ietf.org domain
 * - Adds "pmid" interwiki
 *
 * @since 1.28
 */
class AddRFCandPMIDInterwiki extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Add RFC and PMID to the interwiki database table' );
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function updateSkippedMessage() {
		return 'RFC and PMID already added to interwiki database table.';
	}

	protected function doDBUpdates() {
		$interwikiCache = $this->getConfig()->get( MainConfigNames::InterwikiCache );
		// Using something other than the database,
		if ( $interwikiCache !== false ) {
			return true;
		}
		$dbw = $this->getDB( DB_PRIMARY );

		$rfc = $dbw->newSelectQueryBuilder()
			->select( 'iw_url' )
			->from( 'interwiki' )
			->where( [ 'iw_prefix' => 'rfc' ] )
			->caller( __METHOD__ )
			->fetchField();

		// Old pre-1.28 default value, or not set at all
		if ( $rfc === false || $rfc === 'http://www.rfc-editor.org/rfc/rfc$1.txt' ) {
			$dbw->newReplaceQueryBuilder()
				->replaceInto( 'interwiki' )
				->uniqueIndexFields( [ 'iw_prefix' ] )
				->rows( [
					'iw_prefix' => 'rfc',
					'iw_url' => 'https://tools.ietf.org/html/rfc$1',
					'iw_api' => '',
					'iw_wikiid' => '',
					'iw_local' => 0,
				] )
				->caller( __METHOD__ )->execute();
		}

		$dbw->insert(
			'interwiki',
			[
				'iw_prefix' => 'pmid',
				'iw_url' => 'https://www.ncbi.nlm.nih.gov/pubmed/$1?dopt=Abstract',
				'iw_api' => '',
				'iw_wikiid' => '',
				'iw_local' => 0,
			],
			__METHOD__,
			// If there's already a pmid interwiki link, don't
			// overwrite it
			[ 'IGNORE' ]
		);

		return true;
	}
}

$maintClass = AddRFCandPMIDInterwiki::class;
require_once RUN_MAINTENANCE_IF_MAIN;
