<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialRecentChanges;
use MediaWiki\Tests\SpecialPage\AbstractChangesListSpecialPageTestCase;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use Wikimedia\TestingAccessWrapper;

/**
 * Test class for SpecialRecentchanges class
 *
 * @group Database
 *
 * @covers \MediaWiki\Specials\SpecialRecentChanges
 * @covers \MediaWiki\SpecialPage\ChangesListSpecialPage
 */
class SpecialRecentChangesTest extends AbstractChangesListSpecialPageTestCase {
	use MockAuthorityTrait;
	use TempUserTestTrait;

	protected function getPage(): SpecialRecentChanges {
		return new SpecialRecentChanges(
			$this->getServiceContainer()->getWatchedItemStore(),
			$this->getServiceContainer()->getMessageParser(),
			$this->getServiceContainer()->getUserOptionsLookup(),
			$this->getServiceContainer()->getUserIdentityUtils(),
			$this->getServiceContainer()->getTempUserConfig(),
			$this->getServiceContainer()->getRecentChangeFactory(),
			$this->getServiceContainer()->getChangesListQueryFactory(),
		);
	}

	/**
	 * @return TestingAccessWrapper
	 */
	protected function getPageAccessWrapper() {
		return TestingAccessWrapper::newFromObject( $this->getPage() );
	}

	// Below providers should only be for features specific to
	// RecentChanges.  Otherwise, it should go in ChangesListSpecialPageTest

	public static function provideParseParameters() {
		return [
			[ 'limit=123', [ 'limit' => '123' ] ],

			[ '234', [ 'limit' => '234' ] ],

			[ 'days=3', [ 'days' => '3' ] ],

			[ 'days=0.25', [ 'days' => '0.25' ] ],

			[ 'namespace=5', [ 'namespace' => '5' ] ],

			[ 'namespace=5|3', [ 'namespace' => '5|3' ] ],

			[ 'tagfilter=foo', [ 'tagfilter' => 'foo' ] ],

			[ 'tagfilter=foo;bar', [ 'tagfilter' => 'foo;bar' ] ],
		];
	}

	public static function validateOptionsProvider() {
		return [
			[
				// hidebots=1 is default for Special:RecentChanges
				[ 'hideanons' => 1, 'hideliu' => 1 ],
				true,
				[ 'hideliu' => 1 ],
				false,
			],
		];
	}

	public function testAddWatchlistJoins() {
		// Edit a test page so that it shows up in RC.
		$testPage = $this->getExistingTestPage( 'Test page' );
		$this->editPage( $testPage, 'Test content', '' );

		// Set up RC.
		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		// Confirm that the test page is in RC.
		$rc1 = $this->getPage();
		$rc1->setContext( $context );
		$rc1->execute( null );
		$this->assertStringContainsString( 'Test page', $rc1->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc1->getOutput()->getHTML() );

		// Watch the page, and check that it's now watched in RC.
		$watchedItemStore = $this->getServiceContainer()->getWatchedItemStore();
		$watchedItemStore->addWatch( $context->getUser(), $testPage );
		$rc2 = $this->getPage();
		$rc2->setContext( $context );
		$rc2->execute( null );
		$this->assertStringContainsString( 'Test page', $rc2->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-watched', $rc2->getOutput()->getHTML() );

		// Force a past expiry date on the watchlist item.
		$db = $this->getDb();
		$watchedItemId = $db->newSelectQueryBuilder()
			->select( 'wl_id' )
			->from( 'watchlist' )
			->where( [ 'wl_namespace' => $testPage->getNamespace(), 'wl_title' => $testPage->getDBkey() ] )
			->caller( __METHOD__ )->fetchField();
		$db->newUpdateQueryBuilder()
			->update( 'watchlist_expiry' )
			->set( [ 'we_expiry' => $db->timestamp( '20200101000000' ) ] )
			->where( [ 'we_item' => $watchedItemId ] )
			->caller( __METHOD__ )->execute();

		// Check that the page is still in RC, but that it's no longer watched.
		$rc3 = $this->getPage();
		$rc3->setContext( $context );
		$rc3->execute( null );
		$this->assertStringContainsString( 'Test page', $rc3->getOutput()->getHTML() );
		$this->assertStringContainsString( 'mw-changeslist-line-not-watched', $rc3->getOutput()->getHTML() );
	}

	public function testExperienceLevelFilter() {
		$this->disableAutoCreateTempUser();

		// Edit a test page so that it shows up in RC.
		$testPage = $this->getExistingTestPage( 'Experience page' );
		$this->editPage( $testPage, 'Registered content',
			'registered summary', NS_MAIN, $this->getTestUser()->getUser() );
		$this->editPage( $testPage, 'Anon content',
			'anon summary', NS_MAIN, $this->mockAnonUltimateAuthority() );

		// Set up RC.
		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		// Confirm that the test page is in RC.
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			new FauxRequest()
		);
		$this->assertStringContainsString( 'Experience page', $html );

		// newcomer
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'newcomer' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'registered summary', $html );

		// anon
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'unregistered' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'anon summary', $html );
		$this->assertStringNotContainsString( 'registered summary', $html );

		// registered
		$req = new FauxRequest();
		$req->setVal( 'userExpLevel', 'registered' );
		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			$req
		);
		$this->assertStringContainsString( 'registered summary', $html );
		$this->assertStringNotContainsString( 'anon summary', $html );
	}

	public function testRegistrationFiltersDoShow() {
		$this->disableAutoCreateTempUser();

		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			new FauxRequest()
		);
		$this->assertStringContainsString( 'rcshowhideliu', $html );
		$this->assertStringContainsString( 'rcshowhideanons', $html );
	}

	public function testRegistrationFiltersDoNotShowWhenRegistrationIsRequiredToEdit() {
		$this->overrideConfigValue(
			MainConfigNames::GroupPermissions,
			[ '*' => [ 'edit' => false ] ]
		);
		$this->disableAutoCreateTempUser();

		$context = new RequestContext;
		$context->setTitle( Title::newFromText( __METHOD__ ) );
		$context->setUser( $this->getTestUser()->getUser() );
		$context->setRequest( new FauxRequest );

		[ $html ] = ( new SpecialPageExecutor() )->executeSpecialPage(
			$this->getPage(),
			'',
			new FauxRequest()
		);
		$this->assertStringNotContainsString( 'rcshowhideliu', $html );
		$this->assertStringNotContainsString( 'rcshowhideanons', $html );
	}
}
