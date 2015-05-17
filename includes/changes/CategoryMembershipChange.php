<?php

/**
 * Helper class for category membership changes
 */
class CategoryMembershipChange {

	const CATEGORY_ADDITION = 1;
	const CATEGORY_REMOVAL = -1;

	/** @var string */
	private $timestamp;

	/** @var Title */
	private $pageTitle;

	/** @var WikiPage */
	private $page;

	/** @var Revision */
	private $revision;

	/** @var int */
	private $numTemplateLinks = 0;

	/** @var User */
	private $user;

	/** @var null|RecentChange */
	private $correspondingRC;

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
		if ( !$categoryTitle || !$this->correspondingRC || !$this->user instanceof User ) {
			return;
		}

		$previousRevTimestamp = $this->getPreviousRevisionTimestamp();
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
			$previousRevTimestamp,
			$this->correspondingRC->getAttribute( 'rc_bot' ) ?: 0,
			$this->correspondingRC->getAttribute( 'rc_ip' ) ?: '',
			$unpatrolled ? 0 : 1,
			$this->revision->getVisibility() & Revision::SUPPRESSED_USER
		);
	}

	/**
	 * Get the user who created the revision. may be an anonymous IP
	 * @return User
	 */
	private function getRevisionUser() {
		$user = $this->revision->getUser( Revision::RAW );
		if ( $user === 0 ) {
			$user = User::newFromName( $this->revision->getUserText( Revision::RAW ), false );
		}
		return $user;
	}

	/**
	 * Returns the change message according to the type of category membership change
	 * @param int $type
	 * @param array $params
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

		if ( $this->numTemplateLinks > 0 ) {
			$msgKey .= '-bundled';
		}

		return wfMessage( $msgKey, $params )->text();
	}

	/**
	 * Returns the timestamp of the page's previous revision
	 * @return string
	 */
	private function getPreviousRevisionTimestamp() {
		$latestRev = Revision::newFromId( $this->pageTitle->getLatestRevID() );
		$previousRev = Revision::newFromId( $latestRev->getParentId() );

		return ( $previousRev ) ? $previousRev->getTimestamp() : null;
	}

}
