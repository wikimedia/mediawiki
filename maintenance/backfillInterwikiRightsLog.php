<?php
/**
 * @license GPL-2.0-or-later
 *
 * @file
 * @ingroup Maintenance
 */

declare( strict_types = 1 );

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to copy interwiki rights changes from log on the remote wiki to the current wiki
 *
 * @ingroup Maintenance
 */
class BackfillInterwikiRightsLog extends Maintenance {
	private string $interwikiDelimiter;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Backfill interwiki rights log from the specified wiki' );
		$this->addArg( 'before', 'Only interwiki rights logs before this timestamp will be processed' );
		$this->addOption( 'remote-wiki', 'The wiki to read logs from', true, true );
		$this->addOption( 'dry-run', 'Perform a dry run, copy nothing' );
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$dryRun = $this->hasOption( 'dry-run' );
		$sourceWiki = $this->getOption( 'remote-wiki' );
		$cutoffTimestamp = ConvertibleTimestamp::convert( TS::MW, $this->getArg( 0 ) );

		$currentWiki = WikiMap::getCurrentWikiId();
		if ( $sourceWiki === $currentWiki ) {
			$this->output( "Source wiki must be different from the current wiki.\n" );
			return;
		}

		$sourceDb = $this->getReplicaDB( $sourceWiki );
		$this->interwikiDelimiter = $this->getConfig()->get( MainConfigNames::UserrightsInterwikiDelimiter );
		$titlePattern = new LikeValue( $sourceDb->anyString(), $this->interwikiDelimiter . $currentWiki );

		if ( $dryRun ) {
			$this->output( "DRY RUN: No changes will be made\n" );
		}

		$minTimestamp = $sourceDb->newSelectQueryBuilder()
			->select( 'log_timestamp' )
			->from( 'logging' )
			->where( [
				'log_type' => 'rights',
				'log_action' => 'rights',
			] )
			->orderBy( 'log_timestamp', SelectQueryBuilder::SORT_ASC )
			->caller( __METHOD__ )
			->fetchField();

		if ( $minTimestamp === false ) {
			$this->output( "No source data found, exiting\n" );
			return;
		}

		$lastLogId = 0;
		$lastTimestamp = $minTimestamp;
		$count = 0;
		$skipped = 0;
		$minInsertedId = null;
		$maxInsertedId = null;
		while ( true ) {
			$rows = DatabaseLogEntry::newSelectQueryBuilder( $sourceDb )
				->where( [
					'log_type' => 'rights',
					'log_action' => 'rights',
					$sourceDb->expr( 'log_title', IExpression::LIKE, $titlePattern ),
					$sourceDb->expr( 'log_timestamp', '<', $sourceDb->timestamp( $cutoffTimestamp ) ),
				] )
				->where(
					$sourceDb->buildComparison( '>', [
						'log_timestamp' => $sourceDb->timestamp( $lastTimestamp ),
						'log_id' => $lastLogId,
					] )
				)
				->orderBy( [ 'log_timestamp', 'log_id' ], SelectQueryBuilder::SORT_ASC )
				->limit( $this->getBatchSize() )
				->caller( __METHOD__ )
				->fetchResultSet();

			if ( $rows->numRows() === 0 ) {
				break;
			}
			$this->output( "Processing batch of {$rows->numRows()} log entries...\n" );

			$this->beginTransactionRound( __METHOD__ );

			$originalEntries = [];
			$targetUserNames = [];
			foreach ( $rows as $row ) {
				$entry = DatabaseLogEntry::newFromRow( $row );
				$originalEntries[] = $entry;
				$targetUserNames[] = $this->getTargetUserName( $entry );
			}

			$renames = $this->getRenames( $targetUserNames );

			$logsToInsert = [];
			// For deduplication query
			$timestampsPresent = [];
			foreach ( $originalEntries as $originalEntry ) {
				$lastLogId = $originalEntry->getId();
				$lastTimestamp = $originalEntry->getTimestamp();

				$targetName = $this->getTargetUserName( $originalEntry );
				$targetNewName = $this->getUpToDateUserName( $targetName, $originalEntry->getTimestamp(), $renames );
				if ( $targetNewName !== $targetName ) {
					$this->output( "Renaming $targetName to $targetNewName in entry $lastLogId\n" );
				}
				$targetName = $targetNewName;
				$localTarget = Title::newFromText( $targetName, $originalEntry->getTarget()->getNamespace() );

				$params = $originalEntry->getParameters();
				if ( $originalEntry->isLegacy() ) {
					// We must ensure that the inserted log entry is in the current form, so that we don't create
					// a yet another params schema
					$legacyParams = $originalEntry->getParameters();
					if ( count( $legacyParams ) > 1 ) {
						$oldGroups = array_map( 'trim', explode( ',', $legacyParams[0] ) );
						$newGroups = array_map( 'trim', explode( ',', $legacyParams[1] ) );
						$params = [
							'4::oldgroups' => $oldGroups,
							'5::newgroups' => $newGroups,
						];
					}
				}

				$performerName = $originalEntry->getPerformerIdentity()->getName();
				$performer = UserIdentityValue::newExternal( $sourceWiki, $performerName );

				$logEntry = new ManualLogEntry( 'rights', 'rights' );
				$logEntry->setTimestamp( $originalEntry->getTimestamp() );
				$logEntry->setPerformer( $performer );
				$logEntry->setTarget( $localTarget );
				$logEntry->setComment( $originalEntry->getComment() );
				$logEntry->setParameters( $params );
				$logEntry->setDeleted( $originalEntry->getDeleted() );
				$logsToInsert[] = $logEntry;
				$timestampsPresent[] = $logEntry->getTimestamp();
			}

			$existingRows = DatabaseLogEntry::newSelectQueryBuilder( $this->getReplicaDB() )
				->where( [
					'log_type' => 'rights',
					'log_action' => 'rights',
					'log_timestamp' => array_map(
						$this->getReplicaDB()->timestamp( ... ),
						$timestampsPresent
					),
				] )
				->caller( __METHOD__ )
				->fetchResultSet();

			// keyed by timestamp => array of target users
			$existingChanges = [];
			foreach ( $existingRows as $row ) {
				$entry = DatabaseLogEntry::newFromRow( $row );
				$existingChanges[ $entry->getTimestamp() ][] = $entry->getTarget()->getText();
			}

			foreach ( $logsToInsert as $logEntry ) {
				// If the target user's rights were already changed at the same timestamp, skip so that we don't
				// duplicate entries. This leaves room to false positives, where the user's rights are changed by
				// different users at the same time. It's unlikely and we accept this risk here
				if (
					isset( $existingChanges[ $logEntry->getTimestamp() ] )
					&& in_array( $logEntry->getTarget()->getText(), $existingChanges[ $logEntry->getTimestamp() ] )
				) {
					$skipped++;
					continue;
				}

				if ( !$dryRun ) {
					$id = $logEntry->insert();

					if ( $minInsertedId === null ) {
						$minInsertedId = $id;
					}
					$maxInsertedId = $id;
				}
				$count++;
			}

			$this->commitTransactionRound( __METHOD__ );
		}

		$this->output( "Skipped $skipped log entries.\n" );
		if ( $dryRun ) {
			$this->output( "Would insert $count log entries.\n" );
		} else {
			LoggerFactory::getInstance( 'logentry' )->info(
				'Backfilled {count} interwiki rights log entries from {sourceWiki}.',
				[
					'count' => $count,
					'sourceWiki' => $sourceWiki,
					'minInsertedId' => $minInsertedId,
					'maxInsertedId' => $maxInsertedId,
				]
			);

			$minInsertedId ??= '(null)';
			$maxInsertedId ??= '(null)';
			$this->output( "Inserted $count log entries, with ids between $minInsertedId and $maxInsertedId.\n" );
		}
	}

	private function getTargetUserName( LogEntry $logEntry ): string {
		$originalTargetText = $logEntry->getTarget()->getText();
		return explode( $this->interwikiDelimiter, $originalTargetText )[0];
	}

	private function getUpToDateUserName( string $originalName, string $timestamp, array $renames ): string {
		while ( array_key_exists( $originalName, $renames ) ) {
			$renameFound = false;
			foreach ( $renames[$originalName] as $renameTimestamp => $newName ) {
				if ( $renameTimestamp > $timestamp ) {
					$originalName = $newName;
					$timestamp = $renameTimestamp;
					$renameFound = true;
					break;
				}
			}
			if ( !$renameFound ) {
				break;
			}
		}
		return $originalName;
	}

	/**
	 * Search for renames affecting the specified users
	 * @param list<string> $originalUserNames The users to resolve the renames for
	 * @return array<string,list<array{0:string,1:string}>> For each original name, a list of new names, keyed by
	 *   and ordered by the rename timestamp
	 */
	private function getRenames( array $originalUserNames ): array {
		$renames = [];
		$dbr = $this->getReplicaDB();

		// Convert usernames to the title form (with underscores). Use space form only in output
		$originalUserNames = array_map( static fn ( $name ) => strtr( $name, ' ', '_' ), $originalUserNames );

		while ( $originalUserNames ) {
			$originalUserNames = array_unique( $originalUserNames );
			$batch = array_splice( $originalUserNames, 0, 100 );
			$renameLogs = DatabaseLogEntry::newSelectQueryBuilder( $dbr )
				->where( [
					'log_namespace' => NS_USER,
					'log_title' => $batch,
					'log_type' => 'renameuser',
				] )
				->orderBy( 'log_timestamp' )
				->caller( __METHOD__ )
				->fetchResultSet();

			foreach ( $renameLogs as $renameLog ) {
				$log = DatabaseLogEntry::newFromRow( $renameLog );

				$oldName = $log->getTarget()->getDBkey();
				$timestamp = $log->getTimestamp();
				$params = $log->getParameters();
				$newName = strtr( $params['5::newuser'] ?? $params[0] ?? '', ' ', '_' );

				if ( $newName === '' ) {
					// Invalid log entry, ignore
					continue;
				}

				$renames[$oldName][$timestamp] = strtr( $newName, '_', ' ' );
				if ( !array_key_exists( $newName, $renames ) ) {
					// Follow up on the next renames affecting the same user
					$originalUserNames[] = $newName;
				}
			}
		}

		return $renames;
	}
}

// @codeCoverageIgnoreStart
$maintClass = BackfillInterwikiRightsLog::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
