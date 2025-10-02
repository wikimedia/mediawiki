<?php
/**
 * Clean up deprecated language codes in page_lang
 *
 * @license GPL-2.0-or-later
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
