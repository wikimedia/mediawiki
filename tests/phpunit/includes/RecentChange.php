<?php
class RecentChangeTest extends MediaWikiTestCase {
	protected $title;
	protected $target;
	protected $user;
	protected $user_comment;

	function __construct() {
		parent::__construct();

		$this->title  = Title::newFromText( 'SomeTitle' );
		$this->target = Title::newFromText( 'TestTarget' );
		$this->user   = User::newFromName( 'UserName' );

		$this->user_comment = '<User comment about action>';
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
	 * Need to cover the various switches cases:
	 *   move: move, move_redir, move-noredirect, move_redir-noredirect
	 *   delete: delete, restore
	 *   patrol: patrol
	 *   newusers: newusers, create, create2, autocreate
	 *   upload: upload, overwrite
	 *   suppress
	 *   and default case
	 */

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionMove() {
		$move_params = array(
			'4::target'  => $this->target->getPrefixedText(),
			'5::noredir' => 0,
		);

		# move/move
		$this->assertIRCComment(
			"moved [[SomeTitle]] to [[TestTarget]]: {$this->user_comment}"
			, 'move', 'move'
			, $move_params
		);

		# move/move_redir
		$this->assertIRCComment(
			"moved [[SomeTitle]] to [[TestTarget]] over redirect: {$this->user_comment}"
			, 'move', 'move_redir'
			, $move_params
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionDelete() {
		# delete/delete
		$this->assertIRCComment(
			"deleted &quot;[[SomeTitle]]&quot;: {$this->user_comment}"
			, 'delete', 'delete'
			, array()
		);

		# delete/restore
		$this->assertIRCComment(
			"restored &quot;[[SomeTitle]]&quot;: {$this->user_comment}"
			, 'delete', 'restore'
			, array()
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionPatrol() {
		# patrol/patrol
		$this->assertIRCComment(
			"marked revision 777 of [[SomeTitle]] patrolled : {$this->user_comment}"
			, 'patrol', 'patrol'
			, array(
				'4::curid'  => '777',
				'5::previd' => '666',
				'6::auto'   => 0,

			)
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionNewusers() {
		$this->assertIRCComment(
			"New user account: {$this->user_comment}"
			, 'newusers', 'newusers'
			, array()
		);
		$this->assertIRCComment(
			"New user account: {$this->user_comment}"
			, 'newusers', 'create'
			, array()
		);
		$this->assertIRCComment(
			"created new account SomeTitle: {$this->user_comment}"
			, 'newusers', 'create2'
			, array()
		);
		$this->assertIRCComment(
			"Account created automatically: {$this->user_comment}"
			, 'newusers', 'autocreate'
			, array()
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionUpload() {
		# upload/upload
		$this->assertIRCComment(
			"uploaded &quot;[[SomeTitle]]&quot;: {$this->user_comment}"
			, 'upload', 'upload'
			, array()
		);

		# upload/overwrite
		$this->assertIRCComment(
			"uploaded a new version of &quot;[[SomeTitle]]&quot;: {$this->user_comment}"
			, 'upload', 'overwrite'
			, array()
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionText
	 */
	function testIrcMsgForActionSuppress() {
		$this->assertIRCComment(
			"{$this->user_comment}"
			, 'suppress', ''
			, array()
		);
	}

	/**
	 * @param $expected String Expected IRC text without colors codes
	 * @param $type String Log type (move, delete, suppress, patrol ...)
	 * @param $action String A log type action
	 * @param $msg String (optional) A message for PHPUnit :-)
	 */
	function assertIRCComment( $expected, $type, $action, $params, $msg = '' ) {

		$logEntry = new ManualLogEntry( $type, $action );
		$logEntry->setPerformer( $this->user         );
		$logEntry->setTarget   ( $this->title        );
		$logEntry->setComment  ( $this->user_comment );
		$logEntry->setParameters( $params );

		$formatter = LogFormatter::newFromEntry( $logEntry );
		$formatter->setContext( RequestContext::newExtraneousContext( $this->title ) );

		$this->assertEquals( $expected,
			$formatter->getIRCActionComment( ),
			$msg
		);
	}

}
