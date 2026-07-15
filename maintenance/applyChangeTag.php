<?php

/**
 * Applies an existing change tag to revisions and/or log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

namespace MediaWiki\Maintenance;

use MediaWiki\ChangeTags\ChangeTagsStore;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Applies an existing change tag to revisions and/or log entries.
 *
 * Unlike the tag action API, this can apply software-defined and restricted
 * ('mw-private-') tags.
 *
 * @ingroup Maintenance
 */
class ApplyChangeTag extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Applies an existing change tag to revisions and/or log entries.' );

		$this->addOption( 'tag', 'Tag to apply. Must already be defined.', true, true );
		$this->addOption( 'revisions', 'A list of revision IDs to tag, separated by comma.', false, true );
		$this->addOption( 'logs', 'A list of log entry IDs to tag, separated by comma.', false, true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$changeTagsStore = $this->getServiceContainer()->getChangeTagsStore();

		$tag = $this->getOption( 'tag' );
		if ( !in_array( $tag, $changeTagsStore->listDefinedTags(), true ) ) {
			$this->fatalError( "Tag '$tag' is not defined; define it before applying it." );
		}

		$revIds = $this->parseIntList( $this->getOption( 'revisions', '' ) );
		$logIds = $this->parseIntList( $this->getOption( 'logs', '' ) );
		if ( !$revIds && !$logIds ) {
			$this->fatalError( 'Specify at least one of --revisions or --logs.' );
		}

		$applied = 0;
		$applied += $this->tagTargets( $changeTagsStore, $tag, $revIds, 'revision' );
		$applied += $this->tagTargets( $changeTagsStore, $tag, $logIds, 'logging' );

		$this->output( "Applied '$tag' to $applied target(s).\n" );
	}

	/**
	 * Tag the IDs that exist, reporting any that don't so a mistyped ID is not
	 * mistaken for the tag being hidden from the current user.
	 *
	 * @param ChangeTagsStore $changeTagsStore
	 * @param string $tag
	 * @param int[] $ids
	 * @param string $table Table the IDs belong to: 'revision' or 'logging'
	 * @return int Number of targets newly tagged
	 */
	private function tagTargets( ChangeTagsStore $changeTagsStore, string $tag, array $ids, string $table ): int {
		if ( !$ids ) {
			return 0;
		}

		$isLog = $table === 'logging';
		$idField = $isLog ? 'log_id' : 'rev_id';
		$existing = array_map(
			'intval',
			$this->getReplicaDB()->newSelectQueryBuilder()
				->select( $idField )
				->from( $table )
				->where( [ $idField => $ids ] )
				->caller( __METHOD__ )
				->fetchFieldValues()
		);

		$missing = array_diff( $ids, $existing );
		if ( $missing ) {
			$this->output( "Skipping $table IDs that do not exist: " . implode( ', ', $missing ) . "\n" );
		}

		$applied = 0;
		foreach ( array_chunk( $existing, $this->getBatchSize() ) as $batch ) {
			$this->beginTransactionRound( __METHOD__ );
			foreach ( $batch as $id ) {
				if ( $changeTagsStore->addTags( $tag, null, $isLog ? null : $id, $isLog ? $id : null ) ) {
					$applied++;
				}
			}
			$this->commitTransactionRound( __METHOD__ );
		}
		return $applied;
	}
}

// @codeCoverageIgnoreStart
$maintClass = ApplyChangeTag::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
