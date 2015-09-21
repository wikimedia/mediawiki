<?php

/**
 * @group Database
 */
class RecentChangeTest extends MediaWikiTestCase {
	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;
	protected $context;

	public function setUp() {
		parent::setUp();

		$this->title = Title::newFromText( 'SomeTitle' );
		$this->target = Title::newFromText( 'TestTarget' );
		$this->user = User::newFromName( 'UserName' );

		$this->user_comment = '<User comment about action>';
		$this->context = RequestContext::newExtraneousContext( $this->title );
	}

	/**
	 * The testIrcMsgForAction* tests are supposed to cover the hacky
	 * LogFormatter::getIRCActionText / bug 34508
	 *
	 * Third parties bots listen to those messages. They are clever enough
	 * to fetch the i18n messages from the wiki and then analyze the IRC feed
	 * to reverse engineer the $1, $2 messages.
	 * One thing bots can not detect is when MediaWiki change the meaning of
	 * a message like what happened when we deployed 1.19. $1 became the user
	 * performing the action which broke basically all bots around.
	 *
	 * Should cover the following log actions (which are most commonly used by bots):
	 * - block/block
	 * - block/unblock
	 * - block/reblock
	 * - delete/delete
	 * - delete/restore
	 * - newusers/create
	 * - newusers/create2
	 * - newusers/autocreate
	 * - move/move
	 * - move/move_redir
	 * - protect/protect
	 * - protect/modifyprotect
	 * - protect/unprotect
	 * - protect/move_prot
	 * - upload/upload
	 * - merge/merge
	 * - import/upload
	 * - import/interwiki
	 *
	 * As well as the following Auto Edit Summaries:
	 * - blank
	 * - replace
	 * - rollback
	 * - undo
	 */

	/**
	 * @covers RecentChange::parseParams
	 */
	public function testParseParams() {
		$params = array(
			'root' => array(
				'A' => 1,
				'B' => 'two'
			)
		);

		$this->assertParseParams(
			$params,
			'a:1:{s:4:"root";a:2:{s:1:"A";i:1;s:1:"B";s:3:"two";}}'
		);

		$this->assertParseParams(
			null,
			null
		);

		$this->assertParseParams(
			null,
			serialize( false )
		);

		$this->assertParseParams(
			null,
			'not-an-array'
		);
	}

	/**
	 * @param array $expectedParseParams
	 * @param string|null $rawRcParams
	 */
	protected function assertParseParams( $expectedParseParams, $rawRcParams ) {
		$rc = new RecentChange;
		$rc->setAttribs( array( 'rc_params' => $rawRcParams ) );

		$actualParseParams = $rc->parseParams();

		$this->assertEquals( $expectedParseParams, $actualParseParams );
	}

	public function provideIsInRCLifespan() {
		return array(
			array( 60, time() - 30, 0, true ),
			array( 30, time() - 60, 0, false ),
			array( 60, time() - 30, 60, true ),
			array( 30, time() - 60, 60, true ),
		);
	}

	/**
	 * @covers RecentChange::isInRCLifespan
	 * @dataProvider provideIsInRCLifespan
	 */
	public function testIsInRCLifespan( $maxAge, $timestamp, $tollerance, $expected ) {
		$this->setMwGlobals( 'wgRCMaxAge', $maxAge );
		$this->assertEquals( $expected, RecentChange::isInRCLifespan( $timestamp, $tollerance ) );
	}

}
