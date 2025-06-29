<?php

namespace MediaWiki\Tests\Unit\Page;

use Generator;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWikiUnitTestCase;
use Psr\Log\NullLogger;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @coversDefaultClass \MediaWiki\Page\UndeletePage
 */
class UndeletePageTest extends MediaWikiUnitTestCase {
	/**
	 * @param ProperPageIdentity|null $page
	 * @param WikiPageFactory|null $wpFactory
	 * @param NamespaceInfo|null $namespaceInfo
	 * @param ArchivedRevisionLookup|null $archivedRevisionLookup
	 * @return UndeletePage
	 */
	private function getUndeletePage(
		?ProperPageIdentity $page = null,
		?WikiPageFactory $wpFactory = null,
		?NamespaceInfo $namespaceInfo = null,
		?ArchivedRevisionLookup $archivedRevisionLookup = null
	): UndeletePage {
		return new UndeletePage(
			$this->createHookContainer(),
			$this->createMock( JobQueueGroup::class ),
			$this->createMock( IConnectionProvider::class ),
			$this->createMock( ReadOnlyMode::class ),
			$this->createMock( RepoGroup::class ),
			new NullLogger(),
			$this->createMock( RevisionStore::class ),
			$wpFactory ?? $this->createMock( WikiPageFactory::class ),
			$this->createMock( PageUpdaterFactory::class ),
			$this->createMock( IContentHandlerFactory::class ),
			$archivedRevisionLookup ?? $this->createMock( ArchivedRevisionLookup::class ),
			$namespaceInfo ?? $this->createMock( NamespaceInfo::class ),
			$this->createMock( ITextFormatter::class ),
			$page ?? $this->createMock( ProperPageIdentity::class ),
			$this->createMock( Authority::class )
		);
	}

	/**
	 * @covers ::canProbablyUndeleteAssociatedTalk
	 * @dataProvider provideAssociatedTalk
	 */
	public function testCanProbablyUndeleteAssociatedTalk(
		ProperPageIdentity $page,
		bool $talkExists,
		bool $hasDeletedRevs,
		?string $expectedMsg
	): void {
		$wpFactory = $this->createMock( WikiPageFactory::class );
		$wpFactory->method( 'newFromTitle' )->willReturnCallback( function ( $t ) {
			$title = Title::castFromPageReference( $t );
			$wikiPage = $this->createMock( WikiPage::class );
			$wikiPage->method( 'getTitle' )->willReturn( $title );
			return $wikiPage;
		} );
		$wpFactory->method( 'newFromLinkTarget' )->willReturnCallback(
			function ( LinkTarget $t ) use ( $talkExists ) {
				$existingTalk = $this->createMock( WikiPage::class );
				$existingTalk->method( 'exists' )->willReturn( $talkExists );
				return $existingTalk;
			}
		);
		$nsInfo = new NamespaceInfo( $this->createMock( ServiceOptions::class ), $this->createHookContainer(), [], [] );
		$archivedRevisionLookup = $this->createMock( ArchivedRevisionLookup::class );
		$archivedRevisionLookup->method( 'hasArchivedRevisions' )->willReturn( $hasDeletedRevs );

		$res = $this->getUndeletePage( $page, $wpFactory, $nsInfo, $archivedRevisionLookup )
			->canProbablyUndeleteAssociatedTalk();
		if ( $expectedMsg === null ) {
			$this->assertStatusGood( $res );
		} else {
			$this->assertStatusError( $expectedMsg, $res );
		}
	}

	public static function provideAssociatedTalk(): Generator {
		$talkPage = PageIdentityValue::localIdentity( 42, NS_TALK, 'Test talk page' );
		yield 'Talk page' => [
			$talkPage,
			false,
			false,
			'undelete-error-associated-alreadytalk'
		];

		$nonTalkPage = PageIdentityValue::localIdentity( 44, NS_MAIN, 'Test article' );

		yield 'Article whose talk page exists and does not have deleted revisions' => [
			$nonTalkPage,
			true,
			false,
			'undelete-error-associated-notdeleted'
		];

		yield 'Article whose talk page does not exist and does not have deleted revisions' => [
			$nonTalkPage,
			false,
			false,
			'undelete-error-associated-notdeleted'
		];

		yield 'Article whose talk page exists and has deleted revisions' =>
			[ $nonTalkPage, true, true, null ];

		yield 'Article whose talk page does not exist and has deleted revisions' =>
			[ $nonTalkPage, false, true, null ];
	}
}
