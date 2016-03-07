<?php

/**
 * @covers CategoryMembershipChange
 *
 * @group Database
 *
 * @author Addshore
 */
class CategoryMembershipChangeTest extends MediaWikiLangTestCase {

	/**
	 * @var array|Title[]|User[]
	 */
	private static $lastNotifyArgs;

	/**
	 * @var int
	 */
	private static $notifyCallCounter = 0;

	/**
	 * @var RecentChange
	 */
	private static $mockRecentChange;

	/**
	 * @var Revision
	 */
	private static $testPageRev = null;

	/**
	 * @var Revision
	 */
	private static $testPagePrevRev = null;

	public static function newForCategorizationCallback() {
		self::$lastNotifyArgs = func_get_args();
		self::$notifyCallCounter += 1;
		return self::$mockRecentChange;
	}

	public function setUp() {
		parent::setUp();
		self::$notifyCallCounter = 0;
		self::$mockRecentChange = self::getMock( 'RecentChange' );
	}

	public function addDBDataOnce() {
		// create two revisions
		$this->insertPage( 'UTPage' );
		$info = $this->insertPage( 'UTPage' );
		$title = $info['title'];

		$page = WikiPage::factory( $title );
		self::$testPageRev = $page->getRevision();
		self::$testPagePrevRev = $page->getRevision()->getPrevious();
	}

	private function newChange( Revision $revision = null ) {
		$change = new CategoryMembershipChange( Title::newFromText( 'UTPage' ), $revision );
		$change->overrideNewForCategorizationCallback(
			'CategoryMembershipChangeTest::newForCategorizationCallback'
		);

		return $change;
	}

	public function testChangeAddedNoRev() {
		$change = $this->newChange();
		$change->triggerCategoryAddedNotification( Title::newFromText( 'CategoryName', NS_CATEGORY ) );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'MediaWiki automatic change', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] added to category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( 0, self::$lastNotifyArgs[6] );
		$this->assertEquals( self::$testPagePrevRev->getTimestamp(), self::$lastNotifyArgs[7] );
		$this->assertEquals( 1, self::$lastNotifyArgs[8] );
		$this->assertEquals( null, self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeRemovedNoRev() {
		$change = $this->newChange();
		$change->triggerCategoryRemovedNotification( Title::newFromText( 'CategoryName', NS_CATEGORY ) );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'MediaWiki automatic change', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] removed from category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( 0, self::$lastNotifyArgs[6] );
		$this->assertEquals( self::$testPagePrevRev->getTimestamp(), self::$lastNotifyArgs[7] );
		$this->assertEquals( 1, self::$lastNotifyArgs[8] );
		$this->assertEquals( null, self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeAddedWithRev() {
		$revision = Revision::newFromId( Title::newFromText( 'UTPage' )->getLatestRevID() );
		$change = $this->newChange( $revision );
		$change->triggerCategoryAddedNotification( Title::newFromText( 'CategoryName', NS_CATEGORY ) );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'UTSysop', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] added to category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( self::$testPageRev->getParentId(), self::$lastNotifyArgs[5] );
		$this->assertEquals( $revision->getId(), self::$lastNotifyArgs[6] );
		$this->assertEquals( self::$testPageRev->getTimestamp(), self::$lastNotifyArgs[7] );
		$this->assertEquals( 0, self::$lastNotifyArgs[8] );
		$this->assertEquals( '127.0.0.1', self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeRemovedWithRev() {
		$revision = Revision::newFromId( Title::newFromText( 'UTPage' )->getLatestRevID() );
		$change = $this->newChange( $revision );
		$change->triggerCategoryRemovedNotification( Title::newFromText( 'CategoryName', NS_CATEGORY ) );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'UTSysop', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] removed from category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( self::$testPageRev->getParentId(), self::$lastNotifyArgs[5] );
		$this->assertEquals( $revision->getId(), self::$lastNotifyArgs[6] );
		$this->assertEquals( self::$testPageRev->getTimestamp(), self::$lastNotifyArgs[7] );
		$this->assertEquals( 0, self::$lastNotifyArgs[8] );
		$this->assertEquals( '127.0.0.1', self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

}
