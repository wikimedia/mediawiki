<?php

namespace MediaWiki\Tests\Page;

use MediaWiki\Page\RedirectStore;
use MediaWiki\Page\WikiPage;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\Page\RedirectStore
 * @group Database
 */
class RedirectStoreTest extends MediaWikiIntegrationTestCase {

	private function newRedirectStore(): RedirectStore {
		return $this->getServiceContainer()->getRedirectStore();
	}

	/**
	 * Creates a page and returns it as a WikiPage loaded from the database.
	 */
	private function createPage( Title $title, string $text ): WikiPage {
		$this->editPage( $title, $text );
		return $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
	}

	public static function provideUpdateRedirectTarget() {
		yield [ '#REDIRECT [[Foo]]', true, null, true, true, [] ];
		yield [ '#REDIRECT [[Foo]]', true, 'Foo', true, true, [ [ NS_MAIN, 'Foo' ] ] ];
		yield [ 'SomeText', false, null, false, true, [] ];
		yield [ 'SomeText', false, 'Foo', false, true, [ [ NS_MAIN, 'Foo' ] ] ];
	}

	/**
	 * @dataProvider provideUpdateRedirectTarget
	 *
	 * @param string $initialText
	 * @param bool $initialRedirectState
	 * @param string|null $redirectTitle
	 * @param bool|null $lastRevIsRedirect
	 * @param bool $expectedSuccess
	 * @param array $expectedRows
	 */
	public function testUpdateRedirectTarget(
		$initialText,
		$initialRedirectState,
		$redirectTitle,
		$lastRevIsRedirect,
		$expectedSuccess,
		$expectedRows
	) {
		static $pageCounter = 0;
		$pageCounter++;

		$page = $this->createPage( Title::newFromText( __METHOD__ . $pageCounter ), $initialText );
		$this->assertSame( $initialRedirectState, $page->isRedirect() );

		$redirectTitle = is_string( $redirectTitle )
			? Title::newFromText( $redirectTitle )
			: $redirectTitle;

		$success = $this->newRedirectStore()
			->updateRedirectTarget( $page, $redirectTitle, $lastRevIsRedirect );
		$this->assertSame( $expectedSuccess, $success, 'Success assertion' );
		/**
		 * updateRedirectTarget explicitly updates the redirect table (and not the page table).
		 * Most of core checks the page table for redirect status, so we have to be ugly and
		 * assert a select from the table here.
		 */
		$this->assertRedirectTableCountForPageId( $page->getId(), $expectedRows );
	}

	private function assertRedirectTableCountForPageId( $pageId, $expectedRows ) {
		$this->newSelectQueryBuilder()
			->select( [ 'rd_namespace', 'rd_title' ] )
			->from( 'redirect' )
			->where( [ 'rd_from' => $pageId ] )
			->assertResultSet( $expectedRows );
	}

	public function testUpdateRedirectTarget_insertsRedirectEntry() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );

		$targetTitle = Title::newFromText( 'SomeTarget#Frag' );
		$reflectedTitle = TestingAccessWrapper::newFromObject( $targetTitle );
		$reflectedTitle->mInterwiki = 'eninter';
		$this->newRedirectStore()->updateRedirectTarget( $page, $targetTitle );

		$this->newSelectQueryBuilder()
			->select( [ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ] )
			->from( 'redirect' )
			->where( [ 'rd_from' => $page->getId() ] )
			->assertResultSet( [ [
				strval( $page->getId() ),
				strval( $targetTitle->getNamespace() ),
				strval( $targetTitle->getDBkey() ),
				strval( $targetTitle->getFragment() ),
				strval( $targetTitle->getInterwiki() ),
			] ] );
	}

	public function testUpdateRedirectTarget_T278367() {
		$page = $this->createPage( Title::newFromText( __METHOD__ ), 'A' );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );

		$targetTitle = Title::newFromText( '#Frag' );
		$ok = $this->newRedirectStore()->updateRedirectTarget( $page, $targetTitle );

		$this->assertFalse( $ok );
		$this->assertRedirectTableCountForPageId( $page->getId(), [] );
	}
}
