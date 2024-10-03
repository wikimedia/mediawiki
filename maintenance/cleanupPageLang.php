<?php
/**
 * Clean up deprecated language codes in page_lang
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
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/TableCleanup.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Language\LanguageCode;

/**
 * Maintenance script to clean up deprecated language codes in page_lang
 *
 * @ingroup Maintenance
 */
class CleanupPageLang extends TableCleanup {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Script to clean up deprecated language codes in page_lang' );
		$this->setBatchSize( 1000 );
	}

	/**
	 * @param stdClass $row
	 */
	protected function processRow( $row ) {
		$oldPageLang = $row->page_lang;
		if ( $oldPageLang === null ) {
			// Page has no page language
			$this->progress( 0 );
			return;
		}

		$newPageLang = LanguageCode::replaceDeprecatedCodes( $oldPageLang );
		if ( $newPageLang === $oldPageLang ) {
			// Page language is unchanged
			$this->progress( 0 );
			return;
		}

		$this->updatePageLang( $row, $oldPageLang, $newPageLang );
		$this->progress( 1 );
	}

	/**
	 * @param stdClass $row
	 * @param string $oldPageLang
	 * @param string $newPageLang
	 */
	private function updatePageLang( $row, $oldPageLang, $newPageLang ) {
		if ( $this->dryrun ) {
			$this->output( "DRY RUN: would update page_lang on $row->page_id from $oldPageLang to $newPageLang.\n" );
		} else {
			$this->output( "Update page_lang on $row->page_id from $oldPageLang to $newPageLang.\n" );
			$this->getPrimaryDB()
				->newUpdateQueryBuilder()
				->update( 'page' )
				->set( [ 'page_lang' => $newPageLang ] )
				->where( [ 'page_id' => $row->page_id ] )
				->caller( __METHOD__ )->execute();
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = CleanupPageLang::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
