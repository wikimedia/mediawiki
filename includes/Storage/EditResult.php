<?php
/**
 * Object for storing information about the effects of an edit.
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

/**
 * Object for storing information about the effects of an edit.
 *
 * This object should be constructed by an EditResultBuilder with relevant information filled in
 * during the process of saving the revision by the PageUpdater. You can use it to extract
 * information about whether the edit was a revert and which edits were reverted.
 *
 * @since 1.35
 */
class EditResult {

	// revert methods
	public const REVERT_UNDO = 1;
	public const REVERT_ROLLBACK = 2;
	public const REVERT_MANUAL = 3;

	/** @var bool */
	private $isNew;

	/** @var bool|int */
	private $originalRevisionId;

	/** @var int|null */
	private $revertMethod;

	/** @var int|null */
	private $newestRevertedRevId;

	/** @var int|null */
	private $oldestRevertedRevId;

	/** @var bool */
	private $isExactRevert;

	/** @var bool */
	private $isNullEdit;

	/** @var string[] */
	private $revertTags;

	/**
	 * EditResult constructor.
	 *
	 * @param bool $isNew
	 * @param bool|int $originalRevisionId
	 * @param int|null $revertMethod
	 * @param int|null $oldestReverted
	 * @param int|null $newestReverted
	 * @param bool $isExactRevert
	 * @param bool $isNullEdit
	 * @param string[] $revertTags
	 *
	 * @internal Use EditResultBuilder for constructing EditResults.
	 */
	public function __construct(
		bool $isNew,
		$originalRevisionId,
		?int $revertMethod,
		?int $oldestReverted,
		?int $newestReverted,
		bool $isExactRevert,
		bool $isNullEdit,
		array $revertTags
	) {
		$this->isNew = $isNew;
		$this->originalRevisionId = $originalRevisionId;
		$this->revertMethod = $revertMethod;
		$this->oldestRevertedRevId = $oldestReverted;
		$this->newestRevertedRevId = $newestReverted;
		$this->isExactRevert = $isExactRevert;
		$this->isNullEdit = $isNullEdit;
		$this->revertTags = $revertTags;
	}

	/**
	 * Returns the ID of the most recent revision that was reverted by this edit.
	 * The same as getOldestRevertedRevisionId if only a single revision was
	 * reverted. Returns null if the edit was not a revert.
	 *
	 * @see EditResult::isRevert() for information on how a revert is defined
	 *
	 * @return int|null
	 */
	public function getNewestRevertedRevisionId() : ?int {
		return $this->newestRevertedRevId;
	}

	/**
	 * Returns the ID of the oldest revision that was reverted by this edit.
	 * The same as getOldestRevertedRevisionId if only a single revision was
	 * reverted. Returns null if the edit was not a revert.
	 *
	 * @see EditResult::isRevert() for information on how a revert is defined
	 *
	 * @return int|null
	 */
	public function getOldestRevertedRevisionId() : ?int {
		return $this->oldestRevertedRevId;
	}

	/**
	 * If the edit was an undo, returns the oldest revision that was undone.
	 * Method kept for compatibility reasons.
	 *
	 * @return int
	 */
	public function getUndidRevId() : int {
		if ( $this->getRevertMethod() !== self::REVERT_UNDO ) {
			return 0;
		}
		return $this->getOldestRevertedRevisionId() ?? 0;
	}

	/**
	 * Returns the ID of an earlier revision that is being repeated or restored.
	 *
	 * The original revision's content should match the new revision exactly.
	 *
	 * @return bool|int The original revision id, or false if no earlier revision is known to be
	 * repeated or restored.
	 * The old PageUpdater::getOriginalRevisionId() returned false in such cases. This value would
	 * be then passed on to extensions through hooks, so it may be wise to keep compatibility with
	 * the old behavior.
	 */
	public function getOriginalRevisionId() {
		return $this->originalRevisionId;
	}

	/**
	 * Whether the edit created a new page
	 *
	 * @return bool
	 */
	public function isNew() : bool {
		return $this->isNew;
	}

	/**
	 * Whether the edit was a revert, not necessarily exact.
	 *
	 * An edit is considered a revert if it either:
	 * - Restores the page to an exact previous state (rollbacks, manual reverts and some undos).
	 *   E.g. for edits A B C D, edits C and D are reverted.
	 * - Undoes some edits made previously, but automatic conflict resolution is done and
	 *   possibly additional changes are made by the reverting user (undo).
	 *   E.g. for edits A B C D, edits B and C are reverted.
	 *
	 * To check whether the edit was an exact revert, please use the isExactRevert() method.
	 * The getRevertMethod() will provide additional information about which kind of revert
	 * was made.
	 *
	 * @return bool
	 */
	public function isRevert() : bool {
		return !$this->isNew() && $this->getOldestRevertedRevisionId();
	}

	/**
	 * Returns the revert method that was used to perform the edit, if any changes were reverted.
	 * Returns null if the edit was not a revert.
	 *
	 * Possible values: REVERT_UNDO, REVERT_ROLLBACK, REVERT_MANUAL
	 *
	 * @see EditResult::isRevert()
	 *
	 * @return int|null
	 */
	public function getRevertMethod() : ?int {
		return $this->revertMethod;
	}

	/**
	 * Whether the edit was an exact revert,
	 * i.e. the contents of the revert revision and restored revision match
	 *
	 * @return bool
	 */
	public function isExactRevert() : bool {
		return $this->isExactRevert;
	}

	/**
	 * An edit is a null edit if the original revision is equal to the parent revision,
	 * i.e. no changes were made.
	 *
	 * @return bool
	 */
	public function isNullEdit() : bool {
		return $this->isNullEdit;
	}

	/**
	 * Returns an array of revert-related tags that were applied automatically to this edit.
	 *
	 * @return string[]
	 */
	public function getRevertTags() : array {
		return $this->revertTags;
	}
}
