<?php
/**
 * Helper class for category membership changes
 */
class CategoryMembershipChange {

	const CATEGORY_ADDITION = 1;
	const CATEGORY_REMOVAL = -1;

	/** @var Title */
	private $pageTitle;

	/** @var WikiPage */
	private $page;

	/** @var Revision */
	private $revision;

	/** @var int */
	private $numTemplateLinks = 0;

	/**
	 * @param Title $pageTitle
	 * @param Revision $revision
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle, Revision $revision ) {
		$this->pageTitle = $pageTitle;
		$this->revision = $revision;
		$this->page = WikiPage::factory( $pageTitle );
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

		$timestamp = wfTimestampNow();
		$latestRev = Revision::newFromId( $this->pageTitle->getLatestRevID() );
		$latestTimestamp = $latestRev->getTimestamp();
		$revID = $this->revision->getId();
		$rc = $this->revision->getRecentChange();
		$unpatrolled = $this->revision->isUnpatrolled();
		$user = $this->getRevisionUser();

		if ( !$user instanceof User ) {
			return;
		}

		RecentChange::notifyCategorization(
			$timestamp,
			$categoryTitle,
			$user,
			$this->getChangeMessage( $type, array(
				$this->page->getTitle()->getPrefixedURL(),
				$this->numTemplateLinks
			) ),
			$this->pageTitle,
			$rc->getAttribute( 'rc_last_oldid' ) ?: 0,
			$revID,
			$latestTimestamp,
			$rc->getAttribute( 'rc_bot' ) ?: 0,
			$rc->getAttribute( 'rc_ip' ) ?: '',
			$unpatrolled ? 0 : 1,
			$this->revision->getVisibility()
		);
	}

	/**
	 * Get the user who created the revision. may be an anonymous IP 
	 * @return User
	 */
	private function getRevisionUser() {
		$user = $this->revision->getUser( Revision::RAW );
		if( !$user ) {
			$user = User::newFromName( $this->revision->getUserText( Revision::RAW ), false );
		}
		return $user;
	}

	/**
	 * Returns the change message according to the type of category membership change
	 * @param $type
	 * @param $params
	 * @return string
	 */
	private function getChangeMessage( $type, $params ) {
		$msgKey = "recentchanges-";

		switch ( $type ) {
			case self::CATEGORY_ADDITION:
				$msgKey .= 'page-added-to-category';
				break;
			case self::CATEGORY_REMOVAL:
				$msgKey .= 'page-removed-from-category';
				break;
		}

		if ( $this->numTemplateLinks > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $params )->text();
	}

}
