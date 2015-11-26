<?php

/**
 * @group Database
 */
class LogFormatterTest extends MediaWikiLangTestCase {

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var RequestContext
	 */
	protected $context;

	/**
	 * @var Title
	 */
	protected $target;

	/**
	 * @var string
	 */
	protected $user_comment;

	protected function setUp() {
		parent::setUp();

		global $wgLang;

		$this->setMwGlobals( array(
			'wgLogTypes' => array( 'phpunit' ),
			'wgLogActionsHandlers' => array( 'phpunit/test' => 'LogFormatter',
				'phpunit/param' => 'LogFormatter' ),
			'wgUser' => User::newFromName( 'Testuser' ),
			'wgExtensionMessagesFiles' => array( 'LogTests' => __DIR__ . '/LogTests.i18n.php' ),
		) );

		Language::getLocalisationCache()->recache( $wgLang->getCode() );

		$this->user = User::newFromName( 'Testuser' );
		$this->title = Title::newFromText( 'SomeTitle' );
		$this->target = Title::newFromText( 'TestTarget' );

		$this->context = new RequestContext();
		$this->context->setUser( $this->user );
		$this->context->setTitle( $this->title );
		$this->context->setLanguage( $wgLang );

		$this->user_comment = '<User comment about action>';
	}

	protected function tearDown() {
		parent::tearDown();

		global $wgLang;
		Language::getLocalisationCache()->recache( $wgLang->getCode() );
	}

	public function newLogEntry( $action, $params ) {
		$logEntry = new ManualLogEntry( 'phpunit', $action );
		$logEntry->setPerformer( $this->user );
		$logEntry->setTarget( $this->title );
		$logEntry->setComment( 'A very good reason' );

		$logEntry->setParameters( $params );

		return $logEntry;
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 */
	public function testNormalLogParams() {
		$entry = $this->newLogEntry( 'test', array() );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$formatter->setShowUserToolLinks( false );
		$paramsWithoutTools = $formatter->getMessageParametersForTesting();
		unset( $formatter->parsedParameters );

		$formatter->setShowUserToolLinks( true );
		$paramsWithTools = $formatter->getMessageParametersForTesting();

		$userLink = Linker::userLink(
			$this->user->getId(),
			$this->user->getName()
		);

		$userTools = Linker::userToolLinksRedContribs(
			$this->user->getId(),
			$this->user->getName(),
			$this->user->getEditCount()
		);

		$titleLink = Linker::link( $this->title, null, array(), array() );

		// $paramsWithoutTools and $paramsWithTools should be only different
		// in index 0
		$this->assertEquals( $paramsWithoutTools[1], $paramsWithTools[1] );
		$this->assertEquals( $paramsWithoutTools[2], $paramsWithTools[2] );

		$this->assertEquals( $userLink, $paramsWithoutTools[0]['raw'] );
		$this->assertEquals( $userLink . $userTools, $paramsWithTools[0]['raw'] );

		$this->assertEquals( $this->user->getName(), $paramsWithoutTools[1] );

		$this->assertEquals( $titleLink, $paramsWithoutTools[2]['raw'] );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeRaw() {
		$params = array( '4:raw:raw' => Linker::link( $this->title, null, array(), array() ) );
		$expected = Linker::link( $this->title, null, array(), array() );

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeMsg() {
		$params = array( '4:msg:msg' => 'log-description-phpunit' );
		$expected = wfMessage( 'log-description-phpunit' )->text();

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeMsgContent() {
		$params = array( '4:msg-content:msgContent' => 'log-description-phpunit' );
		$expected = wfMessage( 'log-description-phpunit' )->inContentLanguage()->text();

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeNumber() {
		global $wgLang;

		$params = array( '4:number:number' => 123456789 );
		$expected = $wgLang->formatNum( 123456789 );

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeUserLink() {
		$params = array( '4:user-link:userLink' => $this->user->getName() );
		$expected = Linker::userLink(
			$this->user->getId(),
			$this->user->getName()
		);

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypeTitleLink() {
		$params = array( '4:title-link:titleLink' => $this->title->getText() );
		$expected = Linker::link( $this->title, null, array(), array() );

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getActionText
	 */
	public function testLogParamsTypePlain() {
		$params = array( '4:plain:plain' => 'Some plain text' );
		$expected = 'Some plain text';

		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	/**
	 * @covers LogFormatter::newFromEntry
	 * @covers LogFormatter::getComment
	 */
	public function testLogComment() {
		$entry = $this->newLogEntry( 'test', array() );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		$comment = ltrim( Linker::commentBlock( $entry->getComment() ) );

		$this->assertEquals( $comment, $formatter->getComment() );
	}

	/**
	 * @dataProvider provideApiParamFormatting
	 * @covers LogFormatter::formatParametersForApi
	 * @covers LogFormatter::formatParameterValueForApi
	 */
	public function testApiParamFormatting( $key, $value, $expected ) {
		$entry = $this->newLogEntry( 'param', array( $key => $value ) );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->context );

		ApiResult::setIndexedTagName( $expected, 'param' );
		ApiResult::setArrayType( $expected, 'assoc' );

		$this->assertEquals( $expected, $formatter->formatParametersForApi() );
	}

	public static function provideApiParamFormatting() {
		return array(
			array( 0, 'value', array( 'value' ) ),
			array( 'named', 'value', array( 'named' => 'value' ) ),
			array( '::key', 'value', array( 'key' => 'value' ) ),
			array( '4::key', 'value', array( 'key' => 'value' ) ),
			array( '4:raw:key', 'value', array( 'key' => 'value' ) ),
			array( '4:plain:key', 'value', array( 'key' => 'value' ) ),
			array( '4:bool:key', '1', array( 'key' => true ) ),
			array( '4:bool:key', '0', array( 'key' => false ) ),
			array( '4:number:key', '123', array( 'key' => 123 ) ),
			array( '4:number:key', '123.5', array( 'key' => 123.5 ) ),
			array( '4:array:key', array(), array( 'key' => array( ApiResult::META_TYPE => 'array' ) ) ),
			array( '4:assoc:key', array(), array( 'key' => array( ApiResult::META_TYPE => 'assoc' ) ) ),
			array( '4:kvp:key', array(), array( 'key' => array( ApiResult::META_TYPE => 'kvp' ) ) ),
			array( '4:timestamp:key', '20150102030405', array( 'key' => '2015-01-02T03:04:05Z' ) ),
			array( '4:msg:key', 'parentheses', array(
				'key_key' => 'parentheses',
				'key_text' => wfMessage( 'parentheses' )->text(),
			) ),
			array( '4:msg-content:key', 'parentheses', array(
				'key_key' => 'parentheses',
				'key_text' => wfMessage( 'parentheses' )->inContentLanguage()->text(),
			) ),
			array( '4:title:key', 'project:foo', array(
				'key_ns' => NS_PROJECT,
				'key_title' => Title::newFromText( 'project:foo' )->getFullText(),
			) ),
			array( '4:title-link:key', 'project:foo', array(
				'key_ns' => NS_PROJECT,
				'key_title' => Title::newFromText( 'project:foo' )->getFullText(),
			) ),
			array( '4:user:key', 'foo', array( 'key' => 'Foo' ) ),
			array( '4:user-link:key', 'foo', array( 'key' => 'Foo' ) ),
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeBlock() {
		$sep = $this->context->msg( 'colon-separator' )->text();

		# block/block
		$this->assertIRCComment(
			$this->context->msg( 'blocklogentry', 'SomeTitle', 'duration', '(flags)' )->plain()
			. $sep . $this->user_comment,
			'block', 'block',
			array(
				'5::duration' => 'duration',
				'6::flags' => 'flags',
			),
			$this->user_comment
		);
		# block/block - legacy
		$this->assertIRCComment(
			$this->context->msg( 'blocklogentry', 'SomeTitle', 'duration', '(flags)' )->plain()
			. $sep . $this->user_comment,
			'block', 'block',
			array(
				'duration',
				'flags',
			),
			$this->user_comment,
			'',
			true
		);
		# block/unblock
		$this->assertIRCComment(
			$this->context->msg( 'unblocklogentry', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'block', 'unblock',
			array(),
			$this->user_comment
		);
		# block/reblock
		$this->assertIRCComment(
			$this->context->msg( 'reblock-logentry', 'SomeTitle', 'duration', '(flags)' )->plain()
			. $sep . $this->user_comment,
			'block', 'reblock',
			array(
				'5::duration' => 'duration',
				'6::flags' => 'flags',
			),
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeDelete() {
		$sep = $this->context->msg( 'colon-separator' )->text();

		# delete/delete
		$this->assertIRCComment(
			$this->context->msg( 'deletedarticle', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'delete', 'delete',
			array(),
			$this->user_comment
		);

		# delete/restore
		$this->assertIRCComment(
			$this->context->msg( 'undeletedarticle', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'delete', 'restore',
			array(),
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeNewusers() {
		$this->assertIRCComment(
			'New user account',
			'newusers', 'newusers',
			array()
		);
		$this->assertIRCComment(
			'New user account',
			'newusers', 'create',
			array()
		);
		$this->assertIRCComment(
			'created new account SomeTitle',
			'newusers', 'create2',
			array()
		);
		$this->assertIRCComment(
			'Account created automatically',
			'newusers', 'autocreate',
			array()
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeMove() {
		$move_params = array(
			'4::target' => $this->target->getPrefixedText(),
			'5::noredir' => 0,
		);
		$sep = $this->context->msg( 'colon-separator' )->text();

		# move/move
		$this->assertIRCComment(
			$this->context->msg( '1movedto2', 'SomeTitle', 'TestTarget' )
				->plain() . $sep . $this->user_comment,
			'move', 'move',
			$move_params,
			$this->user_comment
		);

		# move/move_redir
		$this->assertIRCComment(
			$this->context->msg( '1movedto2_redir', 'SomeTitle', 'TestTarget' )
				->plain() . $sep . $this->user_comment,
			'move', 'move_redir',
			$move_params,
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypePatrol() {
		# patrol/patrol
		$this->assertIRCComment(
			$this->context->msg( 'patrol-log-line', 'revision 777', '[[SomeTitle]]', '' )->plain(),
			'patrol', 'patrol',
			array(
				'4::curid' => '777',
				'5::previd' => '666',
				'6::auto' => 0,
			)
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeProtect() {
		$protectParams = array(
			'[edit=sysop] (indefinite) ‎[move=sysop] (indefinite)'
		);
		$sep = $this->context->msg( 'colon-separator' )->text();

		# protect/protect
		$this->assertIRCComment(
			$this->context->msg( 'protectedarticle', 'SomeTitle ' . $protectParams[0] )
				->plain() . $sep . $this->user_comment,
			'protect', 'protect',
			$protectParams,
			$this->user_comment
		);

		# protect/unprotect
		$this->assertIRCComment(
			$this->context->msg( 'unprotectedarticle', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'protect', 'unprotect',
			array(),
			$this->user_comment
		);

		# protect/modify
		$this->assertIRCComment(
			$this->context->msg( 'modifiedarticleprotection', 'SomeTitle ' . $protectParams[0] )
				->plain() . $sep . $this->user_comment,
			'protect', 'modify',
			$protectParams,
			$this->user_comment
		);

		# protect/move_prot
		$this->assertIRCComment(
			$this->context->msg( 'movedarticleprotection', 'SomeTitle', 'OldTitle' )
				->plain() . $sep . $this->user_comment,
			'protect', 'move_prot',
			array(
				'4::oldtitle' => 'OldTitle'
			),
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeUpload() {
		$sep = $this->context->msg( 'colon-separator' )->text();

		# upload/upload
		$this->assertIRCComment(
			$this->context->msg( 'uploadedimage', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'upload', 'upload',
			array(),
			$this->user_comment
		);

		# upload/overwrite
		$this->assertIRCComment(
			$this->context->msg( 'overwroteimage', 'SomeTitle' )->plain() . $sep . $this->user_comment,
			'upload', 'overwrite',
			array(),
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeMerge() {
		$sep = $this->context->msg( 'colon-separator' )->text();

		# merge/merge
		$this->assertIRCComment(
			$this->context->msg( 'pagemerge-logentry', 'SomeTitle', 'Dest', 'timestamp' )->plain()
			. $sep . $this->user_comment,
			'merge', 'merge',
			array(
				'4::dest' => 'Dest',
				'5::mergepoint' => 'timestamp',
			),
			$this->user_comment
		);
	}

	/**
	 * @covers LogFormatter::getIRCActionComment
	 * @covers LogFormatter::getIRCActionText
	 */
	public function testIrcMsgForLogTypeImport() {
		$sep = $this->context->msg( 'colon-separator' )->text();

		# import/upload
		$msg = $this->context->msg( 'import-logentry-upload', 'SomeTitle' )->plain() .
			$sep .
			$this->user_comment;
		$this->assertIRCComment(
			$msg,
			'import', 'upload',
			array(),
			$this->user_comment
		);

		# import/interwiki
		$msg = $this->context->msg( 'import-logentry-interwiki', 'SomeTitle' )->plain() .
			$sep .
			$this->user_comment;
		$this->assertIRCComment(
			$msg,
			'import', 'interwiki',
			array(),
			$this->user_comment
		);
	}

	/**
	 * @param string $expected Expected IRC text without colors codes
	 * @param string $type Log type (move, delete, suppress, patrol ...)
	 * @param string $action A log type action
	 * @param array $params
	 * @param string $comment (optional) A comment for the log action
	 * @param string $msg (optional) A message for PHPUnit :-)
	 */
	protected function assertIRCComment( $expected, $type, $action, $params,
		$comment = null, $msg = '', $legacy = false
	) {
		$logEntry = new ManualLogEntry( $type, $action );
		$logEntry->setPerformer( $this->user );
		$logEntry->setTarget( $this->title );
		if ( $comment !== null ) {
			$logEntry->setComment( $comment );
		}
		$logEntry->setParameters( $params );
		$logEntry->setLegacy( $legacy );

		$formatter = LogFormatter::newFromEntry( $logEntry );
		$formatter->setContext( $this->context );

		// Apply the same transformation as done in IRCColourfulRCFeedFormatter::getLine for rc_comment
		$ircRcComment = IRCColourfulRCFeedFormatter::cleanupForIRC( $formatter->getIRCActionComment() );

		$this->assertEquals(
			$expected,
			$ircRcComment,
			$msg
		);
	}

}
