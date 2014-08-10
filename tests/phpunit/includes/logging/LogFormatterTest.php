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
}
