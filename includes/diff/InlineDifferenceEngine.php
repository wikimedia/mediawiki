<?php
/**
 * InlineDifferenceEngine.php
 */

/**
 * Extends the basic DifferenceEngine from core to enable inline difference view
 * using only one column instead of two column diff system.
 */
class InlineDifferenceEngine extends DifferenceEngine {
	/** @var string */
	public $engineName = 'inline';

	/**
	 * Checks whether the given Revision was deleted
	 * @todo FIXME: Upstream to DifferenceEngine - refactor showDiffPage
	 *
	 * @return boolean
	 */
	public function isDeletedDiff() {
		return $this->mNewRev && $this->mNewRev->isDeleted( Revision::DELETED_TEXT );
	}

	/**
	 * Checks whether the given Revision was deleted or if it is delete
	 * restricted.
	 * FIXME: Upstream to DifferenceEngine - refactor showDiffPage
	 *
	 * @return boolean
	 */
	public function isSuppressedDiff() {
		return $this->isDeletedDiff() &&
			$this->mNewRev->isDeleted( Revision::DELETED_RESTRICTED );
	}

	/**
	 * Checks whether the current user has permission to view the old
	 * and current revisions.
	 * @todo FIXME: Upstream to DifferenceEngine - refactor showDiffPage
	 *
	 * @return boolean
	 */
	public function isUserAllowedToSee() {
		$user = $this->getUser();
		$allowed = $this->mNewRev->userCan( Revision::DELETED_TEXT, $user );
		if ( $this->mOldRev &&
			!$this->mOldRev->userCan( Revision::DELETED_TEXT, $user )
		) {
			$allowed = false;
		}
		return $allowed;
	}

	/**
	 * Checks whether the diff should be hidden from the current user
	 * This is based on whether the user is allowed to see it and whether
	 * the flag unhide is set to allow viewing deleted revisions.
	 * @todo FIXME: Upstream to DifferenceEngine - refactor showDiffPage
	 *
	 * @return boolean
	 */
	public function isHiddenFromUser() {
		if ( $this->isDeletedDiff() && ( !$this->unhide || !$this->isUserAllowedToSee() ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Returns warning messages in situations where a revision cannot be viewed by a user
	 * explaining to them why.
	 * Returns empty string when the revision can be viewed.
	 *
	 * @return string
	 */
	public function getWarningMessageText() {
		$msg = '';
		if ( $this->isHiddenFromUser() ) {
			$allowed = $this->isUserAllowedToSee();
			$suppressed = $this->isSuppressedDiff();

			if ( !$allowed ) {
				$msg = $suppressed ? 'rev-suppressed-no-diff' : 'rev-deleted-no-diff';
				$msg = wfMessage( $msg )->parse();
			} else {
				# Give explanation and add a link to view the diff...
				$query = $this->getRequest()->appendQueryValue( 'unhide', '1', true );
				$link = $this->getTitle()->getFullURL( $query );
				$msg = $suppressed ? 'rev-suppressed-unhide-diff' : 'rev-deleted-unhide-diff';
				$msg = wfMessage( $msg, $link )->parse();
			}
		}
		return $msg;
	}

	/**
	 * Creates an inline diff
	 * @param Content $otext Old content
	 * @param Content $ntext New content
	 *
	 * @return string
	 */
	function generateTextDiffBody( $otext, $ntext ) {
		global $wgContLang;

		# First try wikidiff2
		if ( function_exists( 'wikidiff2_inline_diff' ) ) {
			$text = wikidiff2_inline_diff( $otext, $ntext, 2 );
			$text .= $this->debug( 'wikidiff2-inline' );

			return $text;
		}

		# Else slow native PHP diff
		$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
		$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
		$diffs = new Diff( $ota, $nta );
		$formatter = new InlineDiffFormatter();
		$difftext = $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) );

		return $difftext;
	}

	/**
	 * Reimplements getDiffBodyCacheKey from DifferenceEngine
	 * Returns the cache key for diff body text or content.
	 *
	 * @throws Exception when no mOldid and mNewid is set
	 * @see DifferenceEngine:getDiffBodyCacheKey
	 * @return string
	 */
	protected function getDiffBodyCacheKey() {
		if ( !$this->mOldid || !$this->mNewid ) {
			throw new Exception( 'mOldid and mNewid must be set to get diff cache key.' );
		}

		return wfMemcKey( 'diff', 'inline', MW_DIFF_VERSION,
			'oldid', $this->mOldid, 'newid', $this->mNewid );
	}

	/**
	 * Create a getter function for the patrol link in Mobile Diff.
	 * FIXME: This shouldn't be needed, but markPatrolledLink is protected in DifferenceEngine
	 * @return String
	 */
	public function getPatrolledLink() {
		return $this->markPatrolledLink();
	}
}
