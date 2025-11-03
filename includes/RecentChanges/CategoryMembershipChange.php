<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use MediaWiki\Cache\BacklinkCache;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDBAccessObject;

/**
 * Helper class for category membership changes
 *
 * @since 1.27
 * @ingroup RecentChanges
 * @author Kai Nissen
 * @author Addshore
 */
class CategoryMembershipChange {

	private const CATEGORY_ADDITION = 1;
	private const CATEGORY_REMOVAL = -1;

	/**
	 * @var string Timestamp of the revision associated with this category membership change
	 */
	private $timestamp;

	/**
	 * @var Title Title instance of the categorized page
	 */
	private $pageTitle;

	/**
	 * @var RevisionRecord Latest revision of the categorized page
	 */
	private RevisionRecord $revision;

	/** @var bool Whether this was caused by an import */
	private $forImport;

	/**
	 * @var int
	 * Number of pages this WikiPage is embedded by
	 * Set by CategoryMembershipChange::checkTemplateLinks()
	 */
	private $numTemplateLinks = 0;

	private BacklinkCache $backlinkCache;
	private RecentChangeFactory $recentChangeFactory;

	/**
	 * @param Title $pageTitle Title instance of the categorized page
	 * @param BacklinkCache $backlinkCache
	 * @param RevisionRecord $revision Latest revision of the categorized page.
	 * @param RecentChangeFactory $recentChangeFactory
	 * @param bool $forImport Whether this was caused by an import
	 */
	public function __construct(
		Title $pageTitle,
		BacklinkCache $backlinkCache,
		RevisionRecord $revision,
		RecentChangeFactory $recentChangeFactory,
		bool $forImport
	) {
		$this->pageTitle = $pageTitle;
		$this->revision = $revision;
		$this->recentChangeFactory = $recentChangeFactory;

		// Use the current timestamp for creating the RC entry when dealing with imported revisions,
		// since their timestamp may be significantly older than the current time.
		// This ensures the resulting RC entry won't be immediately reaped by probabilistic RC purging if
		// the imported revision is older than $wgRCMaxAge (T377392).
		$this->timestamp = $forImport ? wfTimestampNow() : $revision->getTimestamp();

		$this->backlinkCache = $backlinkCache;
		$this->forImport = $forImport;
	}

	/**
	 * Determines the number of template links for recursive link updates
	 */
	public function checkTemplateLinks() {
		$this->numTemplateLinks = $this->backlinkCache->getNumLinks( 'templatelinks' );
	}

	/**
	 * Create a recentchanges entry for category additions
	 */
	public function triggerCategoryAddedNotification( PageIdentity $categoryPage ) {
		$this->createRecentChangesEntry( $categoryPage, self::CATEGORY_ADDITION );
	}

	/**
	 * Create a recentchanges entry for category removals
	 */
	public function triggerCategoryRemovedNotification( PageIdentity $categoryPage ) {
		$this->createRecentChangesEntry( $categoryPage, self::CATEGORY_REMOVAL );
	}

	/**
	 * Create a recentchanges entry using RecentChange::notifyCategorization()
	 *
	 * @param PageIdentity $categoryPage
	 * @param int $type
	 */
	private function createRecentChangesEntry( PageIdentity $categoryPage, $type ) {
		$this->notifyCategorization(
			$this->timestamp,
			$categoryPage,
			$this->getUser(),
			$this->getChangeMessageText(
				$type,
				$this->pageTitle->getPrefixedText(),
				$this->numTemplateLinks
			),
			$this->pageTitle,
			$this->revision,
			$this->forImport,
			$type === self::CATEGORY_ADDITION
		);
	}

	/**
	 * @param string $timestamp Timestamp of the recent change to occur in TS_MW format
	 * @param PageIdentity $categoryPage Page of the category a page is being added to or removed from
	 * @param UserIdentity|null $user User object of the user that made the change
	 * @param string $comment Change summary
	 * @param PageIdentity $page Page that is being added or removed
	 * @param RevisionRecord $revision
	 * @param bool $forImport Whether the associated revision was imported
	 * @param bool $added true, if the category was added, false for removed
	 */
	private function notifyCategorization(
		$timestamp,
		PageIdentity $categoryPage,
		?UserIdentity $user,
		$comment,
		PageIdentity $page,
		RevisionRecord $revision,
		bool $forImport,
		$added
	) {
		$deleted = $revision->getVisibility() & RevisionRecord::SUPPRESSED_USER;
		$newRevId = $revision->getId();

		/**
		 * T109700 - Default bot flag to true when there is no corresponding RC entry
		 * This means all changes caused by parser functions & Lua on reparse are marked as bot
		 * Also in the case no RC entry could be found due to replica DB lag
		 */
		$bot = 1;
		$lastRevId = 0;
		$ip = '';

		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();
		$correspondingRc = $revisionStore->getRecentChange( $revision ) ??
			$revisionStore->getRecentChange( $revision, IDBAccessObject::READ_LATEST );
		if ( $correspondingRc !== null ) {
			$bot = $correspondingRc->getAttribute( 'rc_bot' ) ?: 0;
			$ip = $correspondingRc->getAttribute( 'rc_ip' ) ?: '';
			$lastRevId = $correspondingRc->getAttribute( 'rc_last_oldid' ) ?: 0;
		}

		$rc = $this->recentChangeFactory->createCategorizationRecentChange(
			$timestamp,
			$categoryPage,
			$user,
			$comment,
			$page,
			$lastRevId,
			$newRevId,
			$bot,
			$ip,
			$deleted,
			$added,
			$forImport
		);
		$this->recentChangeFactory->insertRecentChange( $rc );
	}

	/**
	 * Get the user associated with this change, or `null` if there is no valid author
	 * associated with this change.
	 *
	 * @return UserIdentity|null
	 */
	private function getUser(): ?UserIdentity {
		return $this->revision->getUser( RevisionRecord::RAW );
	}

	/**
	 * Returns the change message according to the type of category membership change
	 *
	 * The message keys created in this method may be one of:
	 * - recentchanges-page-added-to-category
	 * - recentchanges-page-added-to-category-bundled
	 * - recentchanges-page-removed-from-category
	 * - recentchanges-page-removed-from-category-bundled
	 *
	 * @param int $type may be CategoryMembershipChange::CATEGORY_ADDITION
	 * or CategoryMembershipChange::CATEGORY_REMOVAL
	 * @param string $prefixedText result of Title::->getPrefixedText()
	 * @param int $numTemplateLinks
	 *
	 * @return string
	 */
	private function getChangeMessageText( $type, $prefixedText, $numTemplateLinks ) {
		$array = [
			self::CATEGORY_ADDITION => 'recentchanges-page-added-to-category',
			self::CATEGORY_REMOVAL => 'recentchanges-page-removed-from-category',
		];

		$msgKey = $array[$type];

		if ( intval( $numTemplateLinks ) > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $prefixedText )->inContentLanguage()->text();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( CategoryMembershipChange::class, 'CategoryMembershipChange' );
