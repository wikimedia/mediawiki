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

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( array(
			'wgLogTypes' => array( 'phpunit' ),
			'wgLogActionsHandlers' => array( 'phpunit/test' => 'LogFormatter',
				'phpunit/param' => 'LogFormatter' ),
		) );

		$this->user = User::newFromName( 'Testuser' );
		$this->title = Title::newMainPage();
	}

	public function newLogEntry( $action, $params ) {
		$logEntry = new ManualLogEntry( 'phpunit', $action );
		$logEntry->setPerformer( $this->user );
		$logEntry->setTarget( $this->title );
		$logEntry->setComment( 'A very good reason' );

		$logEntry->setParameters( $params );

		return $logEntry;
	}

	public function testNormalLogParams() {
		$entry = $this->newLogEntry( 'test', array() );
		$formatter = LogFormatter::newFromEntry( $entry );

		$formatter->setShowUserToolLinks( false );
		$paramsWithoutTools = $formatter->getMessageParameters();
		unset( $formatter->parsedParameters );

		$formatter->setShowUserToolLinks( true );
		$paramsWithTools = $formatter->getMessageParameters();

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
	 * @dataProvider provideParamsWithType
	 */
	public function testLogParamsType( $params, $expected ) {
		$entry = $this->newLogEntry( 'param', $params );
		$formatter = LogFormatter::newFromEntry( $entry );

		$logParam = $formatter->getActionText();

		$this->assertEquals( $expected, $logParam );
	}

	public static function provideParamsWithType() {
		global $wgLang;

		$user = User::newFromName( 'Testuser' );
		$title = Title::newMainPage();

		$userLink = Linker::userLink(
			$user->getId(),
			$user->getName()
		);

		$userTools = Linker::userToolLinksRedContribs(
			$user->getId(),
			$user->getName(),
			$user->getEditCount()
		);

		return array(
			array(
				array( '4:raw:raw' => Linker::link( $title, null, array(), array() ) ),
				Linker::link( $title, null, array(), array() )
			),
			array(
				array( '4:msg:msg' => 'log-description-phpunit' ),
				wfMessage( 'log-description-phpunit' )->text()
			),
			array(
				array( '4:msg-content:msgContent' => 'log-description-phpunit' ),
				wfMessage( 'log-description-phpunit' )->inContentLanguage()->text()
			),
			array(
				array( '4:number:number' => 123456789 ),
				$wgLang->formatNum( 123456789 )
			),
			array(
				array( '4:user-link:userLink' => $user->getName() ),
				$userLink
			),
			array(
				array( '4:user-link-tools:userLinkTools' => $user->getName() ),
				$userLink . $userTools
			),
			array(
				array( '4:title-link:titleLink' => $title->getText() ),
				Linker::link( $title, null, array(), array() )
			),
			array(
				array( '4:title-link-params:titleLinkParams' => $title->getText() . '|action=edit' ),
				Linker::link( $title, null, array(), array( 'action' => 'edit' ) )
			),
			array(
				array( '4:plain:plain' => 'Some plain text' ),
				'Some plain text'
			),
		);
	}

	public function testLogComment() {
		$entry = $this->newLogEntry( 'test', array() );
		$formatter = LogFormatter::newFromEntry( $entry );

		$comment = ltrim( Linker::commentBlock( $entry->getComment() ) );

		$this->assertEquals( $comment, $formatter->getComment() );
	}
}
