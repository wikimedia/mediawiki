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
 *
 * @author Ostrzyciel
 */

namespace MediaWiki\Storage;

use ChangeTags;
use DeferrableUpdate;
use FormatJson;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Adds the mw-reverted tag to reverted edits after a revert is made.
 *
 * This class is used by RevertedTagUpdateJob to perform the actual update.
 *
 * @since 1.36
 */
class RevertedTagUpdate implements DeferrableUpdate {

	/**
	 * @internal
	 */
	public const CONSTRUCTOR_OPTIONS = [ 'RevertedTagMaxDepth' ];

	/** @var RevisionStore */
	private $revisionStore;

	/** @var LoggerInterface */
	private $logger;

	/** @var string[] */
	private $softwareTags;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ServiceOptions */
	private $options;

	/** @var int */
	private $revertId;

	/** @var EditResult */
	private $editResult;

	/** @var RevisionRecord|null */
	private $revertRevision;

	/** @var RevisionRecord|null */
	private $newestRevertedRevision;

	/** @var RevisionRecord|null */
	private $oldestRevertedRevision;

	/**
	 * @param RevisionStore $revisionStore
	 * @param LoggerInterface $logger
	 * @param string[] $softwareTags Array of currently enabled software change tags. Can be
	 *        obtained from ChangeTags::getSoftwareTags()
	 * @param ILoadBalancer $loadBalancer
	 * @param ServiceOptions $serviceOptions
	 * @param int $revertId ID of the revert
	 * @param EditResult $editResult EditResult object of this revert
	 */
	public function __construct(
		RevisionStore $revisionStore,
		LoggerInterface $logger,
		array $softwareTags,
		ILoadBalancer $loadBalancer,
		ServiceOptions $serviceOptions,
		int $revertId,
		EditResult $editResult
	) {
		$serviceOptions->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->revisionStore = $revisionStore;
		$this->logger = $logger;
		$this->softwareTags = $softwareTags;
		$this->loadBalancer = $loadBalancer;
		$this->options = $serviceOptions;
		$this->revertId = $revertId;
		$this->editResult = $editResult;
	}

	/**
	 * Marks reverted edits with `mw-reverted` tag.
	 */
	public function doUpdate() {
		// Do extensive checks, as the update may be carried out even months after the edit
		if ( !$this->shouldExecute() ) {
			return;
		}

		// Skip some of the DB code and just tag it if only one edit was reverted
		if ( $this->handleSingleRevertedEdit() ) {
			return;
		}

		$maxDepth = $this->options->get( 'RevertedTagMaxDepth' );
		$extraParams = $this->getTagExtraParams();
		$revertedRevisionIds = $this->revisionStore->getRevisionIdsBetween(
			$this->getOldestRevertedRevision()->getPageId(),
			$this->getOldestRevertedRevision(),
			$this->getNewestRevertedRevision(),
			$maxDepth,
			RevisionStore::INCLUDE_BOTH
		);

		if ( count( $revertedRevisionIds ) > $maxDepth ) {
			// This revert exceeds the depth limit
			$this->logger->notice(
				'The revert is deeper than $wgRevertedTagMaxDepth. Skipping...',
				$extraParams
			);
			return;
		}

		$revertedRevision = null;
		foreach ( $revertedRevisionIds as $revId ) {
			$previousRevision = $revertedRevision;

			$revertedRevision = $this->revisionStore->getRevisionById( $revId );
			if ( $revertedRevision === null ) {
				// Shouldn't happen, but necessary for static analysis
				continue;
			}

			if ( $previousRevision === null ) {
				$previousRevision = $this->revisionStore->getPreviousRevision(
					$revertedRevision
				);
			}
			if ( $previousRevision !== null &&
				$revertedRevision->hasSameContent( $previousRevision )
			) {
				// This is a null revision (e.g. a page move or protection record)
				// See: T265312
				continue;
			}

			$this->markAsReverted(
				$revId,
				$extraParams
			);
		}
	}

	/**
	 * Performs checks to determine whether the update should execute.
	 *
	 * @return bool
	 */
	private function shouldExecute() : bool {
		$maxDepth = $this->options->get( 'RevertedTagMaxDepth' );
		if ( !in_array( ChangeTags::TAG_REVERTED, $this->softwareTags ) || $maxDepth <= 0 ) {
			return false;
		}

		$extraParams = $this->getTagExtraParams();
		if ( !$this->editResult->isRevert() ||
			$this->editResult->getOldestRevertedRevisionId() === null ||
			$this->editResult->getNewestRevertedRevisionId() === null
		) {
			$this->logger->error( 'Invalid EditResult specified.', $extraParams );
			return false;
		}

		if ( !$this->getOldestRevertedRevision() || !$this->getNewestRevertedRevision() ) {
			$this->logger->error(
				'Could not find the newest or oldest reverted revision in the database.',
				$extraParams
			);
			return false;
		}
		if ( !$this->getRevertRevision() ) {
			$this->logger->error(
				'Could not find the revert revision in the database.',
				$extraParams
			);
			return false;
		}

		if ( $this->getNewestRevertedRevision()->getPageId() !==
			$this->getOldestRevertedRevision()->getPageId()
			||
			$this->getOldestRevertedRevision()->getPageId() !==
			$this->getRevertRevision()->getPageId()
		) {
			$this->logger->error(
				'The revert and reverted revisions belong to different pages.',
				$extraParams
			);
			return false;
		}

		if ( $this->getRevertRevision()->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			// The revert's text is marked as deleted, which probably means the update
			// shouldn't be executed.
			$this->logger->notice(
				'The revert\'s text had been marked as deleted before the update was ' .
				'executed. Skipping...',
				$extraParams
			);
			return false;
		}

		$changeTagsOnRevert = $this->getChangeTags( $this->revertId );
		if ( in_array( ChangeTags::TAG_REVERTED, $changeTagsOnRevert ) ) {
			// This is already marked as reverted, which means the update was delayed
			// until the edit is approved. Apparently, the edit was not approved, as
			// it was reverted, so the update should not be performed.
			$this->logger->notice(
				'The revert had been reverted before the update was executed. Skipping...',
				$extraParams
			);
			return false;
		}

		return true;
	}

	/**
	 * Handles the case where only one edit was reverted.
	 * Returns true if the update was handled by this method, false otherwise.
	 *
	 * This is a much simpler case requiring less DB queries than when dealing with multiple
	 * reverted edits.
	 *
	 * @return bool
	 */
	private function handleSingleRevertedEdit() : bool {
		if ( $this->editResult->getOldestRevertedRevisionId() !==
			$this->editResult->getNewestRevertedRevisionId()
		) {
			return false;
		}

		$revertedRevision = $this->getOldestRevertedRevision();
		if ( $revertedRevision === null ||
			$revertedRevision->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			return true;
		}

		$previousRevision = $this->revisionStore->getPreviousRevision(
			$revertedRevision
		);
		if ( $previousRevision !== null &&
			$revertedRevision->hasSameContent( $previousRevision )
		) {
			// Ignore the very rare case of a null edit. This should not occur unless
			// someone does something weird with the page's history before the update
			// is executed.
			return true;
		}

		$this->markAsReverted(
			$this->editResult->getOldestRevertedRevisionId(),
			$this->getTagExtraParams()
		);
		return true;
	}

	/**
	 * Protected function calling static ChangeTags class to allow for unit testing of this
	 * deferrable update.
	 *
	 * This class is not stable for extending, this is just to make the class testable.
	 *
	 * ChangeTags should be passed by dependency injection when that becomes possible.
	 * See: T245964
	 *
	 * @param int $revisionId ID of the revision to mark as reverted
	 * @param array $extraParams Params to put in the ct_params field of table 'change_tag'
	 */
	protected function markAsReverted( int $revisionId, array $extraParams ) {
		ChangeTags::addTags(
			[ ChangeTags::TAG_REVERTED ],
			null,
			$revisionId,
			null,
			FormatJson::encode( $extraParams )
		);
	}

	/**
	 * Protected function calling static ChangeTags class to allow for unit testing of this
	 * deferrable update.
	 *
	 * This class is not stable for extending, this is just to make the class testable.
	 *
	 * ChangeTags should be passed by dependency injection when that becomes possible.
	 * See: T245964
	 *
	 * @param int $revisionId
	 *
	 * @return string[]
	 */
	protected function getChangeTags( int $revisionId ) {
		return ChangeTags::getTags(
			$this->loadBalancer->getConnection( DB_REPLICA ),
			null,
			$revisionId
		);
	}

	/**
	 * Returns additional data to be saved in ct_params field of table 'change_tag'.
	 *
	 * Effectively a superset of what EditResult::jsonSerialize() returns.
	 *
	 * @return array
	 */
	private function getTagExtraParams() : array {
		return array_merge(
			[ 'revertId' => $this->revertId ],
			$this->editResult->jsonSerialize()
		);
	}

	/**
	 * Returns the revision that performed the revert.
	 *
	 * @return RevisionRecord|null
	 */
	private function getRevertRevision() : ?RevisionRecord {
		if ( !isset( $this->revertRevision ) ) {
			$this->revertRevision = $this->revisionStore->getRevisionById(
				$this->revertId
			);
		}
		return $this->revertRevision;
	}

	/**
	 * Returns the newest revision record that was reverted.
	 *
	 * @return RevisionRecord|null
	 */
	private function getNewestRevertedRevision() : ?RevisionRecord {
		if ( !isset( $this->newestRevertedRevision ) ) {
			$this->newestRevertedRevision = $this->revisionStore->getRevisionById(
				$this->editResult->getNewestRevertedRevisionId()
			);
		}
		return $this->newestRevertedRevision;
	}

	/**
	 * Returns the oldest revision record that was reverted.
	 *
	 * @return RevisionRecord|null
	 */
	private function getOldestRevertedRevision() : ?RevisionRecord {
		if ( !isset( $this->oldestRevertedRevision ) ) {
			$this->oldestRevertedRevision = $this->revisionStore->getRevisionById(
				$this->editResult->getOldestRevertedRevisionId()
			);
		}
		return $this->oldestRevertedRevision;
	}
}
