<?php

use MediaWiki\Interwiki\InterwikiLookup;
use Mediawiki\MediaWikiServices;
use MediaWiki\User\UserFactory;

/**
 * @covers CreditsAction
 * @group Action
 * @group Database
 * @author Ammar Abdulhamid <ammarpad@yahoo.com>
 */
class CreditsActionTest extends MediaWikiTestCase {

	/**
	 * @var CreditsAction
	 */
	private $creditsAction;

	/**
	 * @var RequestContext
	 */
	private $context;

	/**
	 * @var WikiPage
	 */
	private $page;

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @var Language
	 */
	private $language;

	protected function setUp() : void {
		parent::setUp();

		$this->setMwGlobals( [ 'wgHiddenPrefs' => [ 'realname' ] ] );
		$this->page = $this->getWikiPage();
		$this->context = RequestContext::getMain();
		$this->context->setLanguage( 'qqx' );
		$this->context->setTitle( $this->page->getTitle() );

		$this->language = $this->context->getLanguage();
		$article = Article::newFromWikiPage( $this->page, $this->context );
		$this->creditsAction = new CreditsAction( $article, $this->context );
		$this->linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
	}

	protected function tearDown() : void {
		$this->page = null;
		$this->creditsAction = null;

		parent::tearDown();
	}

	/**
	 * @covers CreditsAction::getName
	 */
	public function testGetName() {
		$this->assertSame( 'credits', $this->creditsAction->getName() );
	}

	/**
	 * @covers CreditsAction::onView
	 */
	public function testOnViewOnNonExistingPage() {
		$page = $this->getNonExistingTestPage();
		$article = Article::newFromWikiPage( $page, $this->context );
		$this->creditsAction = new CreditsAction( $article, $this->context );

		$expected = '<div id="mw-credits">(nocredits)</div>';
		$this->assertSame( $expected, $this->creditsAction->onView() );
	}

	/**
	 * @covers CreditsAction::onView
	 * @covers CreditsAction::getCredits
	 */
	public function testOnViewOnExistingPage() {
		$expected = '<div id="mw-credits">' . $this->getLastModifiedByLine() . ' </div>';
		$this->assertSame( $expected, $this->creditsAction->onView() );
	}

	/**
	 * @covers CreditsAction::getCredits
	 * @covers CreditsAction::onView
	 */
	public function testGetCredits() {
		$expected = $this->getLastModifiedByLine() . ' ' . $this->getTestContributorsLine();
		$this->assertSame( $expected, $this->creditsAction->getCredits( -1 ) );
	}

	/**
	 * @return string HTML
	 */
	private function getLastModifiedByLine() {
		$date = $this->language->date( $this->page->getTimestamp(), true );
		$time =	$this->language->time( $this->page->getTimestamp(), true );
		$user = User::newFromId( $this->page->getUser() );
		$name = $user->getName();
		$user_link = $this->linkRenderer->makeLink( $user->getUserPage(), $name );

		return "(lastmodifiedatby: $date, $time, (siteuser: $user_link, $name), $name)";
	}

	/**
	 * @return string HTML
	 */
	private function getTestContributorsLine() {
		$userFactory = new UserFactory( MediaWikiServices::getInstance()->getUserNameUtils() );

		// Do some editing with different users.
		// At least one local user, one anonymous user and one fake foreign user.
		$title = $this->page->getTitle()->getText();
		$user1 = $this->getTestUser()->getUser();

		// Pseudo InterWiki user
		$user2 = ( function () use ( $userFactory ) {
			$user = $userFactory->newAnonymous();
			$user->setName( 'fr>Ammarpad' );
			return $user;
		} )();

		$user3 = $userFactory->newAnonymous( '127.0.0.1' );
		$user4 = $this->page->getCreator();

		$this->editPage( $title, 'Test content', '', NS_MAIN, $user1 );
		$this->editPage( $title, 'Another test content', '', NS_MAIN, $user2 );
		$this->editPage( $title, 'Yet another test content', '', NS_MAIN, $user3 );
		$this->editPage( $title, 'And yet another test content', '', NS_MAIN, $user4 );

		$this->setInterwikiLookup( 'fr' );

		$all = [];
		$contributors = [ $user1, $user2, $user3, $user4 ];
		foreach ( $contributors as $user ) {
			if ( $user->getName() == 'UTSysop' ) {
				// Remove last author
				continue;
			}

			if ( ExternalUserNames::isExternal( $user ) ) {
				$page = ExternalUserNames::getUserLinkTitle( $user );
			} elseif ( $user->isAnon() ) {
				$page = SpecialPage::getTitleFor( 'Contributions', $user->getName() );
			} else {
				$page = $user->getUserPage();
			}

			$pagelink = $this->linkRenderer->makeLink( $page, $user->getName() );
			$all[] = $pagelink;
		}

		// Get pagelink with prefixing string
		foreach ( $all as $pagelink ) {
			if ( strpos( $pagelink, 'Ammarpad' ) ) {
				$link1 = '(interwikiusers: ' . $this->language->listToText( [ $pagelink ] ) . ', 1)';
			} elseif ( strpos( $pagelink, '127.0.0.1' ) ) {
				$link2 = '(anonusers: ' . $this->language->listToText( [ $pagelink ] ) . ', 1)';
			} else {
				$link3 = '(siteusers: ' . $this->language->listToText( [ $pagelink ] ) . ', 1)';
			}

		}

		// Rearrange to match real order
		$links = [];
		foreach ( [ $link1, $link3, $link2 ] as $link ) {
			$links[] = $link;
			$users = $this->language->listToText( $links );
		}

		return "(othercontribs: $users, " . count( $links ) . ')';
	}

	private function setInterwikiLookup( $prefix ) {
		$iwLookupMock = $this->getMockBuilder( InterwikiLookup::class )
			->getMock();
		$iwLookupMock->method( 'isValidInterwiki' )
			->with( $prefix )
			->willReturn( true );
		$this->setService( 'InterwikiLookup', $iwLookupMock );

		return $iwLookupMock;
	}

	private function getWikiPage() {
		$title = Title::newFromText( __CLASS__ );
		$page = $this->getMockBuilder( WikiPage::class )
			->setMethods( [ 'getTitle', 'getTimestamp' ] )
			->setConstructorArgs( [ $title ] )
			->getMock();

		$page->method( 'getTitle' )->willReturn( $title );
		$page->method( 'getTimestamp' )->willReturn( '20200101000000' );

		// Page creation
		$user = $this->getTestSysop()->getUser();
		$this->editPage( $title->getText(), 'Put some test content', '', NS_MAIN, $user );

		return $page;
	}
}
