<?php
/**
 * Builder class for the EditResult object.
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
 *
 * @author Ostrzyciel
 */

namespace MediaWiki\Storage;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\RevisionStoreRecord;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Builder class for the EditResult object.
 *
 * @internal Only for use by PageUpdater
 * @since 1.35
 */
class EditResultBuilder {

	public const CONSTRUCTOR_OPTIONS = [
		'ManualRevertSearchRadius',
	];

	/**
	 * A mapping from EditResult's revert methods to relevant change tags.
	 * For use by getRevertTags()
	 */
	private const REVERT_METHOD_TO_CHANGE_TAG = [
		EditResult::REVERT_UNDO => 'mw-undo',
		EditResult::REVERT_ROLLBACK => 'mw-rollback',
		EditResult::REVERT_MANUAL => 'mw-manual-revert'
	];

	/** @var RevisionRecord|null */
	private $revisionRecord = null;

	/** @var bool */
	private $isNew = false;

	/** @var bool|int */
	private $originalRevisionId = false;

	/** @var RevisionRecord|null */
	private $originalRevision = null;

	/** @var int|null */
	private $revertMethod = null;

	/** @var int|null */
	private $newestRevertedRevId = null;

	/** @var int|null */
	private $oldestRevertedRevId = null;

	/** @var RevisionStore */
	private $revisionStore;

	/** @var string[] */
	private $softwareTags;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var ServiceOptions */
	private $options;

	/**
	 * EditResultBuilder constructor.
	 *
	 * @param RevisionStore $revisionStore
	 * @param string[] $softwareTags Array of currently enabled software change tags. Can be
	 *        obtained from ChangeTags::getSoftwareTags()
	 * @param ILoadBalancer $loadBalancer
	 * @param ServiceOptions $options Options for this instance.
	 */
	public function __construct(
		RevisionStore $revisionStore,
		array $softwareTags,
		ILoadBalancer $loadBalancer,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );

		$this->revisionStore = $revisionStore;
		$this->softwareTags = $softwareTags;
		$this->loadBalancer = $loadBalancer;
		$this->options = $options;
	}

	/**
	 * Builds the EditResult object.
	 *
	 * @return EditResult
	 */
	public function buildEditResult() : EditResult {
		if ( $this->revisionRecord === null ) {
			throw new PageUpdateException(
				'Revision was not set prior to building an EditResult'
			);
		}

		// do a last-minute check if this was a manual revert
		$this->detectManualRevert();

		return new EditResult(
			$this->isNew,
			$this->originalRevisionId,
			$this->revertMethod,
			$this->oldestRevertedRevId,
			$this->newestRevertedRevId,
			$this->isExactRevert(),
			$this->isNullEdit(),
			$this->getRevertTags()
		);
	}

	/**
	 * Set the revision associated with this edit.
	 * Should only be called by PageUpdater when saving an edit.
	 *
	 * @param RevisionRecord $revisionRecord
	 */
	public function setRevisionRecord( RevisionRecord $revisionRecord ) {
		$this->revisionRecord = $revisionRecord;
	}

	/**
	 * Set whether the edit created a new page.
	 * Should only be called by PageUpdater when saving an edit.
	 *
	 * @param bool $isNew
	 */
	public function setIsNew( bool $isNew ) {
		$this->isNew = $isNew;
	}

	/**
	 * Marks this edit as a revert and applies relevant information.
	 * Will do nothing if $oldestRevertedRevId is 0.
	 *
	 * @param int $revertMethod The method used to make the revert:
	 * REVERT_UNDO, REVERT_ROLLBACK or REVERT_MANUAL
	 * @param int $oldestRevertedRevId The ID of the oldest revision that was reverted.
	 * @param int $newestRevertedRevId The ID of the newest revision that was reverted. This
	 *        parameter is optional, default value is $oldestRevertedRevId
	 */
	public function markAsRevert(
		int $revertMethod,
		int $oldestRevertedRevId,
		int $newestRevertedRevId = 0
	) {
		if ( $oldestRevertedRevId === 0 ) {
			return;
		}
		if ( $newestRevertedRevId === 0 ) {
			$newestRevertedRevId = $oldestRevertedRevId;
		}

		$this->revertMethod = $revertMethod;
		$this->oldestRevertedRevId = $oldestRevertedRevId;
		$this->newestRevertedRevId = $newestRevertedRevId;
	}

	/**
	 * Sets the ID of an earlier revision that is being repeated or restored.
	 *
	 * @param int|bool $originalRevId
	 */
	public function setOriginalRevisionId( $originalRevId ) {
		$this->originalRevisionId = $originalRevId;
	}

	/**
	 * If this edit was not already marked as a revert using EditResultBuilder::markAsRevert(),
	 * tries to establish whether this was a manual revert, i.e. someone restored the page to
	 * an exact previous state manually.
	 *
	 * If successful, mutates the builder accordingly.
	 */
	private function detectManualRevert() {
		$searchRadius = $this->options->get( 'ManualRevertSearchRadius' );
		if ( !$searchRadius ||
			// we already marked this as a revert
			$this->revertMethod !== null ||
			// it's a null edit, nothing was reverted
			$this->isNullEdit() ||
			// we wouldn't be able to figure out what was the newest reverted edit
			// this also discards new pages
			!$this->revisionRecord->getParentId()
		) {
			return;
		}

		$revertedToRev = $this->findIdenticalRevision( $searchRadius );
		if ( !$revertedToRev ) {
			return;
		}
		$oldestReverted = $this->revisionStore->getNextRevision(
			$revertedToRev,
			RevisionStore::READ_LATEST
		);
		if ( !$oldestReverted ) {
			return;
		}

		$this->setOriginalRevisionId( $revertedToRev->getId() );
		$this->markAsRevert(
			EditResult::REVERT_MANUAL,
			$oldestReverted->getId(),
			$this->revisionRecord->getParentId()
		);
	}

	/**
	 * Tries to find an identical revision to $this->revisionRecord in $searchRadius most
	 * recent revisions of this page. The comparison is based on SHA1s of these revisions.
	 *
	 * @param int $searchRadius How many recent revisions should be checked
	 *
	 * @return RevisionStoreRecord|null
	 */
	private function findIdenticalRevision( int $searchRadius ) : ?RevisionStoreRecord {
		// We use master just in case we encounter replication lag.
		// This is mostly for cases where a revert is applied rapidly after someone saves
		// the previous edit.
		$db = $this->loadBalancer->getConnection( DB_MASTER );
		$revQuery = $this->revisionStore->getQueryInfo();
		$subquery = $db->buildSelectSubquery(
			$revQuery['tables'],
			$revQuery['fields'],
			[ 'rev_page' => $this->revisionRecord->getPageId() ],
			__METHOD__,
			[
				'ORDER BY' => [
					'rev_timestamp DESC',
					// for cases where there are multiple revs with same timestamp
					'rev_id DESC'
				],
				'LIMIT' => $searchRadius,
				// skip the most recent edit, we can't revert to it anyway
				'OFFSET' => 1
			],
			$revQuery['joins']
		);

		// selectRow effectively uses LIMIT 1 clause, returning only the first result
		$revisionRow = $db->selectRow(
			[ 'recent_revs' => $subquery ],
			'*',
			[ 'rev_sha1' => $this->revisionRecord->getSha1() ],
			__METHOD__
		);

		return $revisionRow ?
			$this->revisionStore->newRevisionFromRow( $revisionRow )
			: null;
	}

	/**
	 * Returns the revision that is being repeated or restored.
	 * Returns null if not set for this edit.
	 *
	 * @param int $flags Access flags, e.g. RevisionStore::READ_LATEST
	 *
	 * @return RevisionRecord|null
	 */
	private function getOriginalRevision(
		int $flags = RevisionStore::READ_NORMAL
	) : ?RevisionRecord {
		if ( $this->originalRevision ) {
			return $this->originalRevision;
		}
		if ( $this->originalRevisionId === false ) {
			return null;
		}

		$this->originalRevision = $this->revisionStore->getRevisionById(
			$this->originalRevisionId,
			$flags
		);
		return $this->originalRevision;
	}

	/**
	 * Whether the edit was an exact revert, i.e. the contents of the revert
	 * revision and restored revision match
	 *
	 * @return bool
	 */
	private function isExactRevert() : bool {
		if ( $this->isNew || $this->oldestRevertedRevId === null ) {
			return false;
		}

		if ( $this->getOriginalRevision() === null ) {
			// we can't find the original revision for some reason, better return false
			return false;
		}

		return $this->revisionRecord->hasSameContent( $this->getOriginalRevision() );
	}

	/**
	 * An edit is a null edit if the original revision is equal to the parent revision.
	 *
	 * @return bool
	 */
	private function isNullEdit() : bool {
		if ( $this->isNew ) {
			return false;
		}

		return $this->getOriginalRevision() &&
			$this->originalRevisionId === $this->revisionRecord->getParentId();
	}

	/**
	 * Returns an array of revert-related tags that will be applied automatically to this edit.
	 *
	 * @return string[]
	 */
	private function getRevertTags() : array {
		if ( isset( self::REVERT_METHOD_TO_CHANGE_TAG[$this->revertMethod] ) ) {
			$revertTag = self::REVERT_METHOD_TO_CHANGE_TAG[$this->revertMethod];
			if ( in_array( $revertTag, $this->softwareTags ) ) {
				return [ $revertTag ];
			}
		}
		return [];
	}
}
