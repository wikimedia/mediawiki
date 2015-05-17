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
		$this->timestamp = wfTimestampNow();
		$this->correspondingRC = $this->revision->getRecentChange();
		$this->user = $this->getRevisionUser();

		if ( !$this->correspondingRC ) {
			throw new MWException(
				__CLASS__ .
				' could not be instantiated; revision does not refer to a RecentChange' );
		}
		if ( !$this->user instanceof User ) {
			throw new MWException(
				__CLASS__ .
				' could not be instantiated; revision does not refer to a user' );
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

		$latestRev = Revision::newFromId( $this->pageTitle->getLatestRevID() );
		$latestTimestamp = $latestRev->getTimestamp();
		$unpatrolled = $this->revision->isUnpatrolled();

		RecentChange::notifyCategorization(
			$this->timestamp,
			$categoryTitle,
			$this->user,
			$this->getChangeMessage( $type, array(
				$this->page->getTitle()->getPrefixedURL(),
				$this->numTemplateLinks
			) ),
			$this->pageTitle,
			$this->correspondingRC->getAttribute( 'rc_last_oldid' ) ?: 0,
			$this->revision->getId(),
			$latestTimestamp,
			$this->correspondingRC->getAttribute( 'rc_bot' ) ?: 0,
			$this->correspondingRC->getAttribute( 'rc_ip' ) ?: '',
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
		if ( !$user ) {
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
