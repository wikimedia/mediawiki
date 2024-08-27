<?php
/**
 * Convert history stubs that point to an external row to direct external
 * pointers.
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

use MediaWiki\Maintenance\UndoLog;
use MediaWiki\Storage\SqlBlobStore;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class ResolveStubs extends Maintenance {
	/** @var UndoLog|null */
	private $undoLog;

	public function __construct() {
		parent::__construct();
		$this->setBatchSize( 1000 );
		$this->addOption( 'dry-run', 'Don\'t update any rows' );
		$this->addOption( 'undo', 'Undo log location', false, true );
	}

	/**
	 * Convert history stubs that point to an external row to direct
	 * external pointers
	 */
	public function execute() {
		$dbw = $this->getPrimaryDB();
		$dbr = $this->getReplicaDB();
		$maxID = $dbr->newSelectQueryBuilder()
			->select( 'MAX(old_id)' )
			->from( 'text' )
			->caller( __METHOD__ )->fetchField();
		$blockSize = $this->getBatchSize();
		$dryRun = $this->getOption( 'dry-run' );
		$this->setUndoLog( new UndoLog( $this->getOption( 'undo' ), $dbw ) );

		$numBlocks = intval( $maxID / $blockSize ) + 1;
		$numResolved = 0;
		$numTotal = 0;

		for ( $b = 0; $b < $numBlocks; $b++ ) {
			$this->waitForReplication();

			$this->output( sprintf( "%5.2f%%\n", $b / $numBlocks * 100 ) );
			$start = $blockSize * $b + 1;
			$end = $blockSize * ( $b + 1 );

			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_text', 'old_flags' ] )
				->from( 'text' )
				->where(
					"old_id>=$start AND old_id<=$end " .
					"AND old_flags LIKE '%object%' AND old_flags NOT LIKE '%external%' " .
					// LOWER() doesn't work on binary text, need to convert
					'AND LOWER(CONVERT(LEFT(old_text,22) USING latin1)) = \'o:15:"historyblobstub"\''
				)
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				$numResolved += $this->resolveStub( $row, $dryRun ) ? 1 : 0;
				$numTotal++;
			}
		}
		$this->output( "100%\n" );
		$this->output( "$numResolved of $numTotal stubs resolved\n" );
	}

	/**
	 * @param UndoLog $undoLog
	 */
	public function setUndoLog( UndoLog $undoLog ) {
		$this->undoLog = $undoLog;
	}

	/**
	 * Resolve a history stub.
	 *
	 * This is called by MoveToExternal
	 *
	 * @param stdClass $row The existing text row
	 * @param bool $dryRun
	 * @return bool
	 */
	public function resolveStub( $row, $dryRun ) {
		$id = $row->old_id;
		$stub = unserialize( $row->old_text );
		$flags = SqlBlobStore::explodeFlags( $row->old_flags );

		$dbr = $this->getReplicaDB();

		if ( !( $stub instanceof HistoryBlobStub ) ) {
			print "Error at old_id $id: found object of class " . get_class( $stub ) .
				", expecting HistoryBlobStub\n";
			return false;
		}

		$mainId = $stub->getLocation();
		if ( !$mainId ) {
			print "Error at old_id $id: falsey location\n";
			return false;
		}

		# Get the main text row
		$mainTextRow = $dbr->newSelectQueryBuilder()
			->select( [ 'old_text', 'old_flags' ] )
			->from( 'text' )
			->where( [ 'old_id' => $mainId ] )
			->caller( __METHOD__ )->fetchRow();

		if ( !$mainTextRow ) {
			print "Error at old_id $id: can't find main text row old_id $mainId\n";
			return false;
		}

		$mainFlags = SqlBlobStore::explodeFlags( $mainTextRow->old_flags );
		$mainText = $mainTextRow->old_text;

		if ( !in_array( 'external', $mainFlags ) ) {
			print "Error at old_id $id: target $mainId is not external\n";
			return false;
		}
		if ( preg_match( '!^DB://([^/]*)/([^/]*)/[0-9a-f]{32}$!', $mainText ) ) {
			print "Error at old_id $id: target $mainId is a CGZ pointer\n";
			return false;
		}
		if ( preg_match( '!^DB://([^/]*)/([^/]*)/[0-9]{1,6}$!', $mainText ) ) {
			print "Error at old_id $id: target $mainId is a DHB pointer\n";
			return false;
		}
		if ( !preg_match( '!^DB://([^/]*)/([^/]*)$!', $mainText ) ) {
			print "Error at old_id $id: target $mainId has unrecognised text\n";
			return false;
		}

		# Preserve the legacy encoding flag, but switch from object to external
		if ( in_array( 'utf-8', $flags ) ) {
			$newFlags = 'utf-8,external';
		} else {
			$newFlags = 'external';
		}
		$newText = $mainText . '/' . $stub->getHash();

		# Update the row
		if ( $dryRun ) {
			$this->output( "Resolve $id => $newFlags $newText\n" );
		} else {
			$updated = $this->undoLog->update(
				'text',
				[
					'old_flags' => $newFlags,
					'old_text' => $newText
				],
				(array)$row,
				__METHOD__
			);
			if ( !$updated ) {
				$this->output( "Updated of old_id $id failed to match\n" );
				return false;
			}
		}
		return true;
	}
}

// @codeCoverageIgnoreStart
$maintClass = ResolveStubs::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
