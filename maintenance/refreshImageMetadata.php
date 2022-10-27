<?php
/**
 * Refresh image metadata fields. See also rebuildImages.php
 *
 * Usage: php refreshImageMetadata.php
 *
 * Copyright © 2011 Brian Wolff
 * https://www.mediawiki.org/
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
 * @author Brian Wolff
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Maintenance script to refresh image metadata fields.
 *
 * @ingroup Maintenance
 */
class RefreshImageMetadata extends Maintenance {

	/**
	 * @var IMaintainableDatabase
	 */
	protected $dbw;

	public function __construct() {
		parent::__construct();

		$this->addDescription( 'Script to update image metadata records' );
		$this->setBatchSize( 200 );

		$this->addOption(
			'force',
			'Reload metadata from file even if the metadata looks ok',
			false,
			false,
			'f'
		);
		$this->addOption(
			'broken-only',
			'Only fix really broken records, leave old but still compatible records alone.'
		);
		$this->addOption(
			'convert-to-json',
			'Fix records with an out of date serialization format.'
		);
		$this->addOption(
			'split',
			'Enable splitting out large metadata items to the text table. Implies --convert-to-json.'
		);
		$this->addOption(
			'verbose',
			'Output extra information about each upgraded/non-upgraded file.',
			false,
			false,
			'v'
		);
		$this->addOption( 'start', 'Name of file to start with', false, true );
		$this->addOption( 'end', 'Name of file to end with', false, true );

		$this->addOption(
			'mediatype',
			'Only refresh files with this media type, e.g. BITMAP, UNKNOWN etc.',
			false,
			true
		);
		$this->addOption(
			'mime',
			"Only refresh files with this MIME type. Can accept wild-card 'image/*'. "
				. "Potentially inefficient unless 'mediatype' is also specified",
			false,
			true
		);
		$this->addOption(
			'metadata-contains',
			'(Inefficient!) Only refresh files where the img_metadata field '
				. 'contains this string. Can be used if its known a specific '
				. 'property was being extracted incorrectly.',
			false,
			true
		);
		$this->addOption(
			'sleep',
			'Time to sleep between each batch (in seconds). Default: 0',
			false,
			true
		);
		$this->addOption( 'oldimage', 'Run and refresh on oldimage table.' );
	}

	public function execute() {
		$force = $this->hasOption( 'force' );
		$brokenOnly = $this->hasOption( 'broken-only' );
		$verbose = $this->hasOption( 'verbose' );
		$start = $this->getOption( 'start', false );
		$split = $this->hasOption( 'split' );
		$sleep = (int)$this->getOption( 'sleep', 0 );
		$reserialize = $this->hasOption( 'convert-to-json' );
		$oldimage = $this->hasOption( 'oldimage' );
		if ( $oldimage ) {
			$fieldPrefix = 'oi_';
			$fileQuery = OldLocalFile::getQueryInfo();
		} else {
			$fieldPrefix = 'img_';
			$fileQuery = LocalFile::getQueryInfo();
		}

		$upgraded = 0;
		$leftAlone = 0;
		$error = 0;

		$dbw = $this->getDB( DB_PRIMARY );
		$batchSize = $this->getBatchSize();
		if ( $batchSize <= 0 ) {
			$this->fatalError( "Batch size is too low...", 12 );
		}

		$repo = $this->newLocalRepo( $force, $brokenOnly, $reserialize, $split );
		$conds = $this->getConditions( $dbw, $fieldPrefix );

		// For the WHERE img_name > 'foo' condition that comes after doing a batch
		$conds2 = [];
		if ( $start !== false ) {
			$conds2[] = $fieldPrefix . 'name >= ' . $dbw->addQuotes( $start );
		}

		$options = [
			'LIMIT' => $batchSize,
			'ORDER BY' => $fieldPrefix . 'name ASC',
		];

		do {
			$res = $dbw->select(
				$fileQuery['tables'],
				$fileQuery['fields'],
				array_merge( $conds, $conds2 ),
				__METHOD__,
				$options,
				$fileQuery['joins']
			);

			$nameField = $fieldPrefix . 'name';
			if ( $res->numRows() > 0 ) {
				$row1 = $res->current();
				$this->output( "Processing next {$res->numRows()} row(s) starting with {$row1->$nameField}.\n" );
				$res->rewind();
			}

			foreach ( $res as $row ) {
				try {
					// LocalFile will upgrade immediately here if obsolete
					$file = $repo->newFileFromRow( $row );
					$file->maybeUpgradeRow();
					if ( $file->getUpgraded() ) {
						// File was upgraded.
						$upgraded++;
						$this->output( "Refreshed File:{$row->$nameField}.\n" );
					} else {
						$leftAlone++;
						if ( $force ) {
							$file->upgradeRow();
							if ( $verbose ) {
								$this->output( "Forcibly refreshed File:{$row->$nameField}.\n" );
							}
						} else {
							if ( $verbose ) {
								$this->output( "Skipping File:{$row->$nameField}.\n" );
							}
						}
					}
				} catch ( Exception $e ) {
					$this->output( "{$row->$nameField} failed. {$e->getMessage()}\n" );
				}
			}
			if ( $res->numRows() > 0 ) {
				// @phan-suppress-next-line PhanPossiblyUndeclaredVariable rows contains at least one item
				$conds2 = [ $fieldPrefix . 'name > ' . $dbw->addQuotes( $row->$nameField ) ];
			}
			$this->waitForReplication();
			if ( $sleep ) {
				sleep( $sleep );
			}
		} while ( $res->numRows() === $batchSize );

		$total = $upgraded + $leftAlone;
		if ( $force ) {
			$this->output( "\nFinished refreshing file metadata for $total files. "
				. "$upgraded needed to be refreshed, $leftAlone did not need to "
				. "be but were refreshed anyways, and $error refreshes were suspicious.\n" );
		} else {
			$this->output( "\nFinished refreshing file metadata for $total files. "
				. "$upgraded were refreshed, $leftAlone were already up to date, "
				. "and $error refreshes were suspicious.\n" );
		}
	}

	/**
	 * @param IDatabase $dbw
	 * @param string $fieldPrefix like img_ or oi_
	 * @return array
	 */
	private function getConditions( $dbw, $fieldPrefix ) {
		$conds = [];

		$end = $this->getOption( 'end', false );
		$mime = $this->getOption( 'mime', false );
		$mediatype = $this->getOption( 'mediatype', false );
		$like = $this->getOption( 'metadata-contains', false );

		if ( $end !== false ) {
			$conds[] = $fieldPrefix . 'name <= ' . $dbw->addQuotes( $end );
		}
		if ( $mime !== false ) {
			[ $major, $minor ] = File::splitMime( $mime );
			$conds[$fieldPrefix . 'major_mime'] = $major;
			if ( $minor !== '*' ) {
				$conds[$fieldPrefix . 'minor_mime'] = $minor;
			}
		}
		if ( $mediatype !== false ) {
			$conds[$fieldPrefix . 'media_type'] = $mediatype;
		}
		if ( $like ) {
			$conds[] = $fieldPrefix . 'metadata ' . $dbw->buildLike( $dbw->anyString(), $like, $dbw->anyString() );
		}

		return $conds;
	}

	/**
	 * @param bool $force
	 * @param bool $brokenOnly
	 * @param bool $reserialize
	 * @param bool $split
	 *
	 * @return LocalRepo
	 */
	private function newLocalRepo( $force, $brokenOnly, $reserialize, $split ): LocalRepo {
		if ( $brokenOnly && $force ) {
			$this->fatalError( 'Cannot use --broken-only and --force together. ', 2 );
		}
		$reserialize = $reserialize || $split;
		if ( $brokenOnly && $reserialize ) {
			$this->fatalError( 'Cannot use --broken-only with --convert-to-json or --split. ',
				2 );
		}

		$overrides = [
			'updateCompatibleMetadata' => !$brokenOnly,
		];
		if ( $reserialize ) {
			$overrides['reserializeMetadata'] = true;
			$overrides['useJsonMetadata'] = true;
		}
		if ( $split ) {
			$overrides['useSplitMetadata'] = true;
		}

		return MediaWikiServices::getInstance()->getRepoGroup()
			->newCustomLocalRepo( $overrides );
	}
}

$maintClass = RefreshImageMetadata::class;
require_once RUN_MAINTENANCE_IF_MAIN;
