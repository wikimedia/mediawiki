<?php
/**
 * Migrate archive.ar_text and ar_flags to modern storage
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that migrates archive.ar_text and ar_flags to text storage
 *
 * @ingroup Maintenance
 * @since 1.31
 */
class MigrateArchiveText extends LoggedUpdateMaintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription(
			'Migrates content from pre-1.5 ar_text and ar_flags columns to text storage'
		);
		$this->addOption(
			'replace-missing',
			"For rows with missing or unloadable data, throw away whatever is there and\n"
			. "mark them as \"error\" in the database."
		);
	}

	/**
	 * Sets whether a run of this maintenance script has the force parameter set
	 * @param bool $forced
	 */
	public function setForce( $forced = true ) {
		$this->mOptions['force'] = $forced;
	}

	protected function getUpdateKey() {
		return __CLASS__;
	}

	protected function doDBUpdates() {
		$replaceMissing = $this->hasOption( 'replace-missing' );
		$defaultExternalStore = $this->getConfig()->get( 'DefaultExternalStore' );
		$blobStore = MediaWikiServices::getInstance()
			->getBlobStoreFactory()
			->newSqlBlobStore();
		$batchSize = $this->getBatchSize();

		$dbr = $this->getDB( DB_REPLICA, [ 'vslow' ] );
		$dbw = $this->getDB( DB_MASTER );
		if ( !$dbr->fieldExists( 'archive', 'ar_text', __METHOD__ ) ||
			!$dbw->fieldExists( 'archive', 'ar_text', __METHOD__ )
		) {
			$this->output( "No ar_text field, so nothing to migrate.\n" );
			return true;
		}

		$this->output( "Migrating ar_text to modern storage...\n" );
		$last = 0;
		$count = 0;
		$errors = 0;
		while ( true ) {
			$res = $dbr->select(
				'archive',
				[ 'ar_id', 'ar_text', 'ar_flags' ],
				[
					'ar_text_id' => null,
					"ar_id > $last",
				],
				__METHOD__,
				[ 'LIMIT' => $batchSize, 'ORDER BY' => [ 'ar_id' ] ]
			);
			$numRows = $res->numRows();

			foreach ( $res as $row ) {
				$last = $row->ar_id;

				// Recompress the text (and store in external storage, if
				// applicable) if it's not already in external storage.
				$arFlags = explode( ',', $row->ar_flags );
				if ( !in_array( 'external', $arFlags, true ) ) {
					$data = $blobStore->decompressData( $row->ar_text, $arFlags );
					if ( $data !== false ) {
						$flags = $blobStore->compressData( $data );

						if ( $defaultExternalStore ) {
							$data = ExternalStore::insertToDefault( $data );
							if ( $flags ) {
								$flags .= ',';
							}
							$flags .= 'external';
						}
					} elseif ( $replaceMissing ) {
						$this->error( "Replacing missing data for row ar_id=$row->ar_id" );
						$data = 'Missing data in migrateArchiveText.php on ' . date( 'c' );
						$flags = 'error';
					} else {
						$this->error( "No data for row ar_id=$row->ar_id" );
						$errors++;
						continue;
					}
				} else {
					$flags = $row->ar_flags;
					$data = $row->ar_text;
				}

				$this->beginTransaction( $dbw, __METHOD__ );
				$dbw->insert(
					'text',
					[ 'old_text' => $data, 'old_flags' => $flags ],
					__METHOD__
				);
				$id = $dbw->insertId();
				$dbw->update(
					'archive',
					[ 'ar_text_id' => $id, 'ar_text' => '', 'ar_flags' => '' ],
					[ 'ar_id' => $row->ar_id, 'ar_text_id' => null ],
					__METHOD__
				);
				$count += $dbw->affectedRows();
				$this->commitTransaction( $dbw, __METHOD__ );
			}

			if ( $numRows < $batchSize ) {
				// We must have reached the end
				break;
			}

			$this->output( "... $last\n" );
			// $this->commitTransaction() already waited for replication; no need to re-wait here
		}

		$this->output( "Completed ar_text migration, $count rows updated, $errors missing data.\n" );
		if ( $errors ) {
			$this->output( "Run with --replace-missing to overwrite missing data with an error message.\n" );
		}

		return $errors === 0;
	}
}

$maintClass = MigrateArchiveText::class;
require_once RUN_MAINTENANCE_IF_MAIN;
