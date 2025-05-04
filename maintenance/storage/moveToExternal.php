<?php
/**
 * Move text from the text table to external storage
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
 * @ingroup Maintenance ExternalStorage
 */

use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Maintenance\UndoLog;
use MediaWiki\Storage\SqlBlobStore;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\LikeValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class MoveToExternal extends Maintenance {
	/** @var ResolveStubs */
	private $resolveStubs;
	/** @var int */
	private $reportingInterval;
	/** @var int */
	private $minID;
	/** @var int */
	private $maxID;
	/** @var string */
	private $esType;
	/** @var string */
	private $esLocation;
	/** @var int */
	private $threshold;
	/** @var bool */
	private $gzip;
	/** @var bool */
	private $skipResolve;
	/** @var string|null */
	private $legacyEncoding;
	/** @var bool */
	private $dryRun;
	/** @var UndoLog */
	private $undoLog;

	public function __construct() {
		parent::__construct();

		$this->setBatchSize( 1000 );

		$this->addOption( 'start', 'start old_id', false, true, 's' );
		$this->addOption( 'end', 'end old_id', false, true, 'e' );
		$this->addOption( 'threshold', 'minimum size in bytes', false, true );
		$this->addOption( 'reporting-interval',
			'show a message after this many revisions', false, true );
		$this->addOption( 'undo', 'filename for undo SQL', false, true );

		$this->addOption( 'skip-gzip', 'Don\'t compress individual revisions' );
		$this->addOption( 'skip-resolve',
			'Don\'t replace HistoryBlobStub objects with direct external store pointers' );
		$this->addOption( 'iconv', 'Resolve legacy character encoding' );
		$this->addOption( 'dry-run', 'Don\'t modify any rows' );

		$this->addArg( 'type', 'The external store type, e.g. "DB" or "mwstore"' );
		$this->addArg( 'location', 'e.g. "cluster12" or "global-swift"' );
	}

	/** @inheritDoc */
	public function execute() {
		$this->resolveStubs = new ResolveStubs;
		$this->esType = $this->getArg( 0 ); // e.g. "DB" or "mwstore"
		$this->esLocation = $this->getArg( 1 ); // e.g. "cluster12" or "global-swift"
		$dbw = $this->getPrimaryDB();

		$maxID = $this->getOption( 'end' ) ?? $dbw->newSelectQueryBuilder()
			->select( 'MAX(old_id)' )
			->from( 'text' )
			->caller( __METHOD__ )->fetchField();
		$this->maxID = (int)$maxID;
		$this->minID = (int)$this->getOption( 'start', 1 );

		$this->reportingInterval = $this->getOption( 'reporting-interval', 100 );
		$this->threshold = (int)$this->getOption( 'threshold', 0 );

		if ( $this->getOption( 'skip-gzip' ) ) {
			$this->gzip = false;
		} elseif ( !function_exists( 'gzdeflate' ) ) {
			$this->fatalError( "gzdeflate() not found. " .
				"Please run with --skip-gzip if you don't want to compress revisions." );
		} else {
			$this->gzip = true;
		}

		$this->skipResolve = $this->getOption( 'skip-resolve' );

		if ( $this->getOption( 'iconv' ) ) {
			$legacyEncoding = $this->getConfig()->get( MainConfigNames::LegacyEncoding );
			if ( $legacyEncoding ) {
				$this->legacyEncoding = $legacyEncoding;
			} else {
				$this->output( "iconv requested but the wiki has no legacy encoding\n" );
			}
		}
		$this->dryRun = $this->getOption( 'dry-run', false );

		$undo = $this->getOption( 'undo' );
		try {
			$this->undoLog = new UndoLog( $undo, $dbw );
		} catch ( RuntimeException $e ) {
			$this->fatalError( "Unable to open undo log" );
		}
		$this->resolveStubs->setUndoLog( $this->undoLog );

		return $this->doMoveToExternal();
	}

	private function doMoveToExternal(): bool {
		$success = true;
		$dbr = $this->getReplicaDB();

		$count = $this->maxID - $this->minID + 1;
		$blockSize = $this->getBatchSize();
		$numBlocks = ceil( $count / $blockSize );
		print "Moving text rows from {$this->minID} to {$this->maxID} to external storage\n";

		$esFactory = $this->getServiceContainer()->getExternalStoreFactory();
		$extStore = $esFactory->getStore( $this->esType );
		$numMoved = 0;
		$stubIDs = [];

		for ( $block = 0; $block < $numBlocks; $block++ ) {
			$blockStart = $block * $blockSize + $this->minID;
			$blockEnd = $blockStart + $blockSize - 1;

			if ( $this->reportingInterval && !( $block % $this->reportingInterval ) ) {
				$this->output( "oldid=$blockStart, moved=$numMoved\n" );
				$this->waitForReplication();
			}

			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_flags', 'old_text' ] )
				->from( 'text' )
				->where( $this->getConditions( $blockStart, $blockEnd, $dbr ) )
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				$text = $row->old_text;
				$id = $row->old_id;
				$flags = SqlBlobStore::explodeFlags( $row->old_flags );
				[ $text, $flags ] = $this->resolveText( $text, $flags );

				if ( $text === false ) {
					$success = false;
				}

				if ( in_array( 'error', $flags ) ) {
					continue;
				} elseif ( in_array( 'object', $flags ) ) {
					$obj = unserialize( $text );
					if ( $obj instanceof HistoryBlobStub ) {
						// Handle later, after CGZ resolution
						if ( !$this->skipResolve ) {
							$stubIDs[] = $id;
						}
						continue;
					} elseif ( $obj instanceof HistoryBlobCurStub ) {
						// Copy cur text to ES
						$newText = $obj->getText();
						if ( $newText === false ) {
							print "Warning: Could not fetch revision blob {$id}: {$text}\n";
							$success = false;
							continue;
						}

						[ $text, $flags ] = $this->resolveLegacyEncoding( $newText, [] );

						if ( $text === false ) {
							print "Warning: Could not decode legacy-encoded gzip\'ed revision blob {$id}: {$newText}\n";
							$success = false;
							continue;
						}

						[ $text, $flags ] = $this->compress( $text, $flags );
					} elseif ( $obj instanceof ConcatenatedGzipHistoryBlob ) {
						// Store as is
					} else {
						$className = get_class( $obj );
						print "Warning: old_id=$id unrecognised object class \"$className\"\n";
						$success = false;
						continue;
					}
				} elseif ( strlen( $text ) < $this->threshold ) {
					// Don't move small revisions
					continue;
				} else {
					[ $text, $flags ] = $this->resolveLegacyEncoding( $text, $flags );
					[ $newText, $flags ] = $this->compress( $text, $flags );
					if ( $newText === false ) {
						print "Warning: Could not compress revision blob {$id}: {$text}\n";
						$success = false;
						continue;
					}
					$text = $newText;
				}
				$flags[] = 'external';
				$flagsString = implode( ',', $flags );

				if ( $this->dryRun ) {
					$this->output( "Move $id => $flagsString " .
						addcslashes( substr( $text, 0, 30 ), "\0..\x1f\x7f..\xff" ) .
						"\n"
					);
					continue;
				}

				$url = $extStore->store( $this->esLocation, $text );
				if ( !$url ) {
					$this->fatalError( "Error writing to external storage" );
				}
				$moved = $this->undoLog->update(
					'text',
					[ 'old_flags' => $flagsString, 'old_text' => $url ],
					(array)$row,
					__METHOD__
				);
				if ( $moved ) {
					$numMoved++;
				} else {
					print "Update of old_id $id failed, affected zero rows\n";
					$success = false;
				}
			}
		}

		if ( count( $stubIDs ) ) {
			$this->resolveStubs( $stubIDs );
		}

		return $success;
	}

	private function compress( string $text, array $flags ): array {
		if ( $this->gzip && !in_array( 'gzip', $flags ) ) {
			$flags[] = 'gzip';
			$text = gzdeflate( $text );
		}
		return [ $text, $flags ];
	}

	private function resolveLegacyEncoding( string $text, array $flags ): array {
		if ( $this->legacyEncoding !== null
			&& !in_array( 'utf-8', $flags )
			&& !in_array( 'utf8', $flags )
		) {
			// First decompress the entry so we don't try to convert a binary gzip to utf-8
			if ( in_array( 'gzip', $flags ) ) {
				if ( !$this->gzip ) {
					return [ $text, $flags ];
				}
				$flags = array_diff( $flags, [ 'gzip' ] );
				$newText = gzinflate( $text );
				if ( $newText === false ) {
					return [ false, $flags ];
				}
				$text = $newText;
			}
			AtEase::suppressWarnings();
			$newText = iconv( $this->legacyEncoding, 'UTF-8//IGNORE', $text );
			AtEase::restoreWarnings();
			if ( $newText === false ) {
				return [ false, $flags ];
			}
			$text = $newText;
			$flags[] = 'utf-8';
		}
		return [ $text, $flags ];
	}

	private function resolveStubs( array $stubIDs ) {
		if ( $this->dryRun ) {
			print "Note: resolving stubs in dry run mode is expected to fail, " .
				"because the main blobs have not been moved to external storage.\n";
		}

		$dbr = $this->getReplicaDB();
		$this->output( "Resolving " . count( $stubIDs ) . " stubs\n" );
		$numResolved = 0;
		$numTotal = 0;
		foreach ( array_chunk( $stubIDs, $this->getBatchSize() ) as $stubBatch ) {
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_flags', 'old_text' ] )
				->from( 'text' )
				->where( [ 'old_id' => $stubBatch ] )
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				$numResolved += $this->resolveStubs->resolveStub( $row, $this->dryRun ) ? 1 : 0;
				$numTotal++;
				if ( $this->reportingInterval
					&& $numTotal % $this->reportingInterval == 0
				) {
					$this->output( "$numTotal stubs processed\n" );
					$this->waitForReplication();
				}
			}
		}
		$this->output( "$numResolved of $numTotal stubs resolved\n" );
	}

	protected function getConditions( int $blockStart, int $blockEnd, IReadableDatabase $dbr ): array {
		return [
			$dbr->expr( 'old_id', '>=', $blockStart ),
			$dbr->expr( 'old_id', '>=', $blockEnd ),
			$dbr->expr( 'old_flags', IExpression::NOT_LIKE,
				new LikeValue( $dbr->anyString(), 'external', $dbr->anyString() ) ),
		];
	}

	protected function resolveText( string $text, array $flags ): array {
		return [ $text, $flags ];
	}
}

// @codeCoverageIgnoreStart
$maintClass = MoveToExternal::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
