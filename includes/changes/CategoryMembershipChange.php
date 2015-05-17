<?php

/**
 * Helper class for category membership changes
 */
class CategoryMembershipChange {

	const CATEGORY_ADDITION = 1;
	const CATEGORY_REMOVAL = -1;

	/** @var string Current timestamp, set during CategoryMembershipChange::__construct() */
	private $timestamp;

	/** @var Title Title instance of the categorized page */
	private $pageTitle;

	/** @var WikiPage WikiPage instance of the categorized page */
	private $page;

	/** @var Revision Latest Revision instance of the categorized page */
	private $revision;

	/**
	 * @var int
	 * Number of pages this WikiPage is embedded by; set by CategoryMembershipChange::setRecursive()
	 */
	private $numTemplateLinks = 0;

	/**
	 * @var User User
	 * instance of the user that created CategoryMembershipChange::$revision
	 */
	private $user;

	/**
	 * @var null|RecentChange
	 * RecentChange that is referred to in CategoryMembershipChange::$revision
	 */
	private $correspondingRC;

	/**
	 * @param Title $pageTitle Title instance of the categorized page
	 * @param Revision $revision Latest Revision instance of the categorized page
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle, Revision $revision = null ) {
		$this->pageTitle = $pageTitle;
		$this->page = WikiPage::factory( $pageTitle );
		$this->timestamp = wfTimestampNow();

		# if no revision is given, the change was probably triggered by parser functions
		if ( $this->revision ) {
			$this->revision = $revision;
			$this->correspondingRC = $this->revision->getRecentChange();
			$this->user = $this->getRevisionUser();
		} else {
			$this->user = User::newFromId( 0 );
		}
	}

	/**
	 * Determines the number of template links for recursive link updates
	 */
	public function setRecursive() {
		$this->numTemplateLinks = $this->pageTitle->getBacklinkCache()->getNumLinks( 'templatelinks' );
	}

	/**
	 * Create a recentchanges entry for category additions
	 * @param string $categoryName
	 */
	public function pageAddedToCategory( $categoryName ) {
		$this->createRecentChangesEntry( $categoryName, self::CATEGORY_ADDITION );
	}

	/**
	 * Create a recentchanges entry for category removals
	 * @param string $categoryName
	 */
	public function pageRemovedFromCategory( $categoryName ) {
		$this->createRecentChangesEntry( $categoryName, self::CATEGORY_REMOVAL );
	}

	/**
	 * Create a recentchanges entry using RecentChange::notifyCategorization()
	 * @param string $categoryName
	 * @param int $type
	 */
	private function createRecentChangesEntry( $categoryName, $type ) {
		$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );
		if ( !$categoryTitle ) {
			return;
		}

		$previousRevTimestamp = $this->getPreviousRevisionTimestamp();
		$unpatrolled = $this->revision ? $this->revision->isUnpatrolled() : 0;

		RecentChange::notifyCategorization(
			$this->timestamp,
			$categoryTitle,
			$this->user,
			$this->getChangeMessage( $type, array(
				'prefixedUrl' => $this->page->getTitle()->getPrefixedURL(),
				'numTemplateLinks' => $this->numTemplateLinks
			) ),
			$this->pageTitle,
			$this->correspondingRC && $this->correspondingRC->getAttribute( 'rc_last_oldid' ) ?: 0,
			$this->revision ? $this->revision->getId() : 0,
			$previousRevTimestamp,
			$this->correspondingRC && $this->correspondingRC->getAttribute( 'rc_bot' ) ?: 0,
			$this->correspondingRC && $this->correspondingRC->getAttribute( 'rc_ip' ) ?: '',
			$unpatrolled ? 0 : 1,
			$this->revision ? $this->revision->getVisibility() & Revision::SUPPRESSED_USER : 0
		);
	}

	/**
	 * Get the user who created the revision. may be an anonymous IP
	 * @return User
	 */
	private function getRevisionUser() {
		$userId = $this->revision->getUser( Revision::RAW );
		if ( $userId === 0 ) {
			return User::newFromName( $this->revision->getUserText( Revision::RAW ), false );
		} else {
			return User::newFromId( $userId );
		}
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
	 * @param array $params
	 * - prefixedUrl: result of Title::->getPrefixedURL()
	 * - numTemplateLinks
	 * @return string
	 */
	private function getChangeMessage( $type, array $params ) {
		$msgKey = 'recentchanges-';

		switch ( $type ) {
			case self::CATEGORY_ADDITION:
				$msgKey .= 'page-added-to-category';
				break;
			case self::CATEGORY_REMOVAL:
				$msgKey .= 'page-removed-from-category';
				break;
		}

		if ( intval( $params['numTemplateLinks'] ) > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $params )->text();
	}

	/**
	 * Returns the timestamp of the page's previous revision or null if the latest revision
	 * does not refer to a parent revision
	 * @return null|string
	 */
	private function getPreviousRevisionTimestamp() {
		$latestRev = Revision::newFromId( $this->pageTitle->getLatestRevID() );
		$previousRev = Revision::newFromId( $latestRev->getParentId() );

		return $previousRev ? $previousRev->getTimestamp() : null;
	}

}
