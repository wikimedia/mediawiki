<?php
/**
 * Helper class for category membership changes
 */
class CategoryMembershipChange extends RecentChange {

	const CATEGORY_ADDITION = 1;
	const CATEGORY_ADDITION_BUNDLED = 2;
	const CATEGORY_REMOVAL = -1;
	const CATEGORY_REMOVAL_BUNDLED = -2;

	/** @var Title */
	private $pageTitle;

	/** @var WikiPage */
	private $page;

	/** @var Revision */
	private $revision;

	/** @var int */
	private $affectedPageCount = 0;

	/**
	 * @param Title $pageTitle
	 * @throws MWException
	 */
	public function __construct( Title $pageTitle ) {
		$this->pageTitle = $pageTitle;
		$this->page = WikiPage::factory( $pageTitle );
		$this->page->loadPageData();
		$this->revision = $this->page->mLatest ? $this->page->getRevision() : null;
	}

	/**
	 * Determine the number of template links
	 */
	public function determineNumTemplateLinks() {
		$this->affectedPageCount = $this->pageTitle->getBacklinkCache()->getNumLinks( 'templatelinks' );
	}

	/**
	 * Create a recentchanges entry for category additions
	 * @param string $categoryName
	 */
	public function pageAddedToCategory( $categoryName ) {
		$type = $this->affectedPageCount > 0 ? self::CATEGORY_ADDITION_BUNDLED : self::CATEGORY_ADDITION;
		$this->createRecentChangesEntry( $categoryName, $type );
	}

	/**
	 * Create a recentchanges entry for category removals
	 * @param string $categoryName
	 */
	public function pageRemovedFromCategory( $categoryName ) {
		$type = $this->affectedPageCount > 0 ? self::CATEGORY_REMOVAL_BUNDLED : self::CATEGORY_REMOVAL;
		$this->createRecentChangesEntry( $categoryName, $type );
	}

	/**
	 * Create a recentchanges entry using RecentChange::notifyCategorization()
	 * @param string $categoryName
	 * @param int $type
	 * @return RecentChange
	 */
	private function createRecentChangesEntry( $categoryName, $type ) {
		$categoryTitle = Title::newFromText( $categoryName, NS_CATEGORY );

		$timestamp = wfTimestampNow();
		$revID = $this->revision->getId();
		$rc = $this->revision->getRecentChange();
		$unpatrolled = $this->revision->isUnpatrolled();
		$user = User::newFromId( $this->revision->getUser() );

		RecentChange::notifyCategorization(
			$timestamp,
			$categoryTitle,
			$this->revision->isMinor(),
			$user,
			$this->getChangeMessage( $type, array(
				$this->page->getTitle()->getPrefixedURL(),
				$this->affectedPageCount
			) ),
			$this->pageTitle,
			$rc ? $rc->getAttribute( 'rc_last_oldid' ) : 0,
			$revID,
			$this->revision ? $this->revision->getTimestamp() : 0,
			$rc ? $rc->getAttribute( 'rc_bot' ) : 0,
			'', # IP?!
			$unpatrolled ? 0 : 1,
			array( 'articleId' => array( $this->pageTitle->getArticleID() ) )
		);

		return $rc;
	}

	/**
	 * Returns the change message according to the type of category membership change
	 * @param $type
	 * @param $params
	 * @return string
	 */
	private function getChangeMessage( $type, $params ) {
		switch ( $type ) {
			case self::CATEGORY_ADDITION:
				return wfMessage( 'recentchanges-page-added-to-category', $params )->text();
			case self::CATEGORY_ADDITION_BUNDLED:
				return wfMessage( 'recentchanges-page-added-to-category-bundled', $params )->text();
			case self::CATEGORY_REMOVAL:
				return wfMessage( 'recentchanges-page-removed-from-category', $params )->text();
			case self::CATEGORY_REMOVAL_BUNDLED:
				return wfMessage( 'recentchanges-page-removed-from-category-bundled', $params )->text();
		}
	}

}
