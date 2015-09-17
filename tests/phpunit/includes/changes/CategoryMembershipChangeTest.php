<?php

/**
 * @covers CategoryMembershipChange
 *
 * @group Database
 *
 * @author Adam Shorland
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

	public static function notifyCategorization() {
		self::$lastNotifyArgs = func_get_args();
		self::$notifyCallCounter += 1;
	}

	public function setUp() {
		parent::setUp();
		self::$notifyCallCounter = 0;
	}

	private function newChange( Revision $revision = null ) {
		$change = new CategoryMembershipChange( Title::newFromText( 'UTPage' ), $revision );
		$change->overrideNotifyCategorizationCallback( 'CategoryMembershipChangeTest::notifyCategorization' );

		return $change;
	}

	public function testChangeAddedNoRev() {
		$change = $this->newChange();
		$change->triggerCategoryAddedNotification( 'CategoryName' );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'MediaWiki automatic change', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] added to category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( 0, self::$lastNotifyArgs[6] );
		$this->assertEquals( null, self::$lastNotifyArgs[7] );
		$this->assertEquals( 1, self::$lastNotifyArgs[8] );
		$this->assertEquals( null, self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeRemovedNoRev() {
		$change = $this->newChange();
		$change->triggerCategoryRemovedNotification( 'CategoryName' );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'MediaWiki automatic change', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] removed from category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( 0, self::$lastNotifyArgs[6] );
		$this->assertEquals( null, self::$lastNotifyArgs[7] );
		$this->assertEquals( 1, self::$lastNotifyArgs[8] );
		$this->assertEquals( null, self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeAddedWithRev() {
		$revision = Revision::newFromId( Title::newFromText( 'UTPage' )->getLatestRevID() );
		$change = $this->newChange( $revision );
		$change->triggerCategoryAddedNotification( 'CategoryName' );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'UTSysop', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] added to category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( $revision->getId(), self::$lastNotifyArgs[6] );
		$this->assertEquals( null, self::$lastNotifyArgs[7] );
		$this->assertEquals( 0, self::$lastNotifyArgs[8] );
		$this->assertEquals( '127.0.0.1', self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

	public function testChangeRemovedWithRev() {
		$revision = Revision::newFromId( Title::newFromText( 'UTPage' )->getLatestRevID() );
		$change = $this->newChange( $revision );
		$change->triggerCategoryRemovedNotification( 'CategoryName' );

		$this->assertEquals( 1, self::$notifyCallCounter );

		$this->assertTrue( strlen( self::$lastNotifyArgs[0] ) === 14 );
		$this->assertEquals( 'Category:CategoryName', self::$lastNotifyArgs[1]->getPrefixedText() );
		$this->assertEquals( 'UTSysop', self::$lastNotifyArgs[2]->getName() );
		$this->assertEquals( '[[:UTPage]] removed from category', self::$lastNotifyArgs[3] );
		$this->assertEquals( 'UTPage', self::$lastNotifyArgs[4]->getPrefixedText() );
		$this->assertEquals( 0, self::$lastNotifyArgs[5] );
		$this->assertEquals( $revision->getId(), self::$lastNotifyArgs[6] );
		$this->assertEquals( null, self::$lastNotifyArgs[7] );
		$this->assertEquals( 0, self::$lastNotifyArgs[8] );
		$this->assertEquals( '127.0.0.1', self::$lastNotifyArgs[9] );
		$this->assertEquals( 0, self::$lastNotifyArgs[10] );
	}

}
