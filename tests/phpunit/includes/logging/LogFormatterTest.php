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
		$this->title = Title::newMainPage();

		$this->context = new RequestContext();
		$this->context->setUser( $this->user );
		$this->context->setTitle( $this->title );
		$this->context->setLanguage( $wgLang );
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
}
