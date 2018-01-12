<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use HashBagOStuff;
use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStoreFactory;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionTitleLookup;
use MediaWiki\Storage\SingleContentRevisionFactory;
use MediaWiki\Storage\SqlBlobStore;
use MediaWikiTestCase;
use Revision;
use TestUserRegistry;
use Title;
use WANObjectCache;
use WikiPage;
use WikitextContent;

class SingleContentRevisionFactoryTest extends MediaWikiTestCase {

	public function provideNewRevisionFromRow_legacyEncoding_applied() {
		yield 'windows-1252, old_flags is empty' => [
			'windows-1252',
			'en',
			[
				'old_flags' => '',
				'old_text' => "S\xF6me Content",
			],
			'Söme Content'
		];

		yield 'windows-1252, old_flags is null' => [
			'windows-1252',
			'en',
			[
				'old_flags' => null,
				'old_text' => "S\xF6me Content",
			],
			'Söme Content'
		];
	}

	/**
	 * @dataProvider provideNewRevisionFromRow_legacyEncoding_applied
	 *
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_legacyEncoding_applied( $encoding, $locale, $row, $text ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( $encoding, Language::factory( $locale ) );

		$factory = new SingleContentRevisionFactory(
			wfGetLB(),
			$blobStore,
			new RevisionTitleLookup( wfGetLB() )
		);

		$record = $factory->newRevisionFromRow(
			$this->makeRow( $row ),
			0,
			Title::newFromText( __METHOD__ . '-UTPage' )
		);

		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_legacyEncoding_ignored() {
		$row = [
			'old_flags' => 'utf-8',
			'old_text' => 'Söme Content',
		];

		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );

		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( 'windows-1252', Language::factory( 'en' ) );

		$factory = new SingleContentRevisionFactory(
			wfGetLB(),
			$blobStore,
			new RevisionTitleLookup( wfGetLB() )
		);

		$record = $factory->newRevisionFromRow(
			$this->makeRow( $row ),
			0,
			Title::newFromText( __METHOD__ . '-UTPage' )
		);
		$this->assertSame( 'Söme Content', $record->getContent( 'main' )->serialize() );
	}

	private function makeRow( array $array ) {
		$row = $array + [
				'rev_id' => 7,
				'rev_page' => 5,
				'rev_text_id' => 11,
				'rev_timestamp' => '20110101000000',
				'rev_user_text' => 'Tester',
				'rev_user' => 17,
				'rev_minor_edit' => 0,
				'rev_deleted' => 0,
				'rev_len' => 100,
				'rev_parent_id' => 0,
				'rev_sha1' => 'deadbeef',
				'rev_comment_text' => 'Testing',
				'rev_comment_data' => '{}',
				'rev_comment_cid' => 111,
				'rev_content_format' => CONTENT_FORMAT_TEXT,
				'rev_content_model' => CONTENT_MODEL_TEXT,
				'page_namespace' => 0,
				'page_title' => 'TEST',
				'page_id' => 5,
				'page_latest' => 7,
				'page_is_redirect' => 0,
				'page_len' => 100,
				'user_name' => 'Tester',
				'old_is' => 13,
				'old_text' => 'Hello World',
				'old_flags' => 'utf-8',
			];

		return (object)$row;
	}

	public function provideNewNullRevision() {
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment1' ),
			true,
		];
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment2', [ 'a' => 1 ] ),
			false,
		];
	}

	/**
	 * @dataProvider provideNewNullRevision
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newNullRevision
	 */
	public function testNewNullRevision( Title $title, $comment, $minor ) {
		$factory = MediaWikiServices::getInstance()->getNullRevisionFactory();
		$user = TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser();
		$record = $factory->newNullRevision(
			wfGetDB( DB_MASTER ),
			$title,
			$comment,
			$minor,
			$user
		);

		$this->assertEquals( $title->getNamespace(), $record->getPageAsLinkTarget()->getNamespace() );
		$this->assertEquals( $title->getDBkey(), $record->getPageAsLinkTarget()->getDBkey() );
		$this->assertEquals( $comment, $record->getComment() );
		$this->assertEquals( $minor, $record->isMinor() );
		$this->assertEquals( $user->getName(), $record->getUser()->getName() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newNullRevision
	 */
	public function testNewNullRevision_nonExistingTitle() {
		$factory = MediaWikiServices::getInstance()->getNullRevisionFactory();
		$record = $factory->newNullRevision(
			wfGetDB( DB_MASTER ),
			Title::newFromText( __METHOD__ . '.iDontExist!' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment' ),
			false,
			TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser()
		);
		$this->assertNull( $record );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_anonEdit() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'a-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__. 'a'
		)->value['revision'];

		$factory = MediaWikiServices::getInstance()->getRevisionFactory();
		$record = $factory->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_anonEdit_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$this->overrideMwServices();
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'a-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__. 'a'
		)->value['revision'];

		$factory = MediaWikiServices::getInstance()->getRevisionFactory();
		$record = $factory->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromRow_1_29
	 */
	public function testNewRevisionFromRow_userEdit() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$text = __METHOD__ . 'b-ä';
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( $text ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision'];

		$factory = MediaWikiServices::getInstance()->getRevisionFactory();
		$record = $factory->newRevisionFromRow(
			$this->revisionToRow( $rev ),
			[],
			$page->getTitle()
		);
		$this->assertRevisionRecordMatchesRevision( $rev, $record );
		$this->assertSame( $text, $rev->getContent()->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromArchiveRow
	 */
	public function testNewRevisionFromArchiveRow() {
		$factory = MediaWikiServices::getInstance()->getRevisionFactory();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var Revision $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			        ->value['revision'];
		$page->doDeleteArticle( __METHOD__ );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $factory->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$record = $factory->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordMatchesRevision( $orig, $record );
		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newRevisionFromArchiveRow
	 */
	public function testNewRevisionFromArchiveRow_legacyEncoding() {
		$this->setMwGlobals( 'wgLegacyEncoding', 'windows-1252' );
		$this->overrideMwServices();
		$factory = MediaWikiServices::getInstance()->getRevisionFactory();
		$title = Title::newFromText( __METHOD__ );
		$text = __METHOD__ . '-bä';
		$page = WikiPage::factory( $title );
		/** @var Revision $orig */
		$orig = $page->doEditContent( new WikitextContent( $text ), __METHOD__ )
			        ->value['revision'];
		$page->doDeleteArticle( __METHOD__ );

		$db = wfGetDB( DB_MASTER );
		$arQuery = $factory->getArchiveQueryInfo();
		$res = $db->select(
			$arQuery['tables'], $arQuery['fields'], [ 'ar_rev_id' => $orig->getId() ],
			__METHOD__, [], $arQuery['joins']
		);
		$this->assertTrue( is_object( $res ), 'query failed' );

		$row = $res->fetchObject();
		$res->free();
		$record = $factory->newRevisionFromArchiveRow( $row );

		$this->assertRevisionRecordMatchesRevision( $orig, $record );
		$this->assertSame( $text, $record->getContent( 'main' )->serialize() );
	}

	private function assertRevisionRecordMatchesRevision(
		Revision $rev,
		RevisionRecord $record
	) {
		$this->assertSame( $rev->getId(), $record->getId() );
		$this->assertSame( $rev->getPage(), $record->getPageId() );
		$this->assertSame( $rev->getTimestamp(), $record->getTimestamp() );
		$this->assertSame( $rev->getUserText(), $record->getUser()->getName() );
		$this->assertSame( $rev->getUser(), $record->getUser()->getId() );
		$this->assertSame( $rev->isMinor(), $record->isMinor() );
		$this->assertSame( $rev->getVisibility(), $record->getVisibility() );
		$this->assertSame( $rev->getSize(), $record->getSize() );
		/**
		 * @note As of MW 1.31, the database schema allows the parent ID to be
		 * NULL to indicate that it is unknown.
		 */
		$expectedParent = $rev->getParentId();
		if ( $expectedParent === null ) {
			$expectedParent = 0;
		}
		$this->assertSame( $expectedParent, $record->getParentId() );
		$this->assertSame( $rev->getSha1(), $record->getSha1() );
		$this->assertSame( $rev->getComment(), $record->getComment()->text );
		$this->assertSame( $rev->getContentFormat(), $record->getContent( 'main' )->getDefaultFormat() );
		$this->assertSame( $rev->getContentModel(), $record->getContent( 'main' )->getModel() );
		$this->assertLinkTargetsEqual( $rev->getTitle(), $record->getPageAsLinkTarget() );
	}

	private function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

	private function revisionToRow( Revision $rev ) {
		$page = WikiPage::factory( $rev->getTitle() );

		return (object)[
			'rev_id' => (string)$rev->getId(),
			'rev_page' => (string)$rev->getPage(),
			'rev_text_id' => (string)$rev->getTextId(),
			'rev_timestamp' => (string)$rev->getTimestamp(),
			'rev_user_text' => (string)$rev->getUserText(),
			'rev_user' => (string)$rev->getUser(),
			'rev_minor_edit' => $rev->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$rev->getVisibility(),
			'rev_len' => (string)$rev->getSize(),
			'rev_parent_id' => (string)$rev->getParentId(),
			'rev_sha1' => (string)$rev->getSha1(),
			'rev_comment_text' => $rev->getComment(),
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_content_format' => $rev->getContentFormat(),
			'rev_content_model' => $rev->getContentModel(),
			'page_namespace' => (string)$page->getTitle()->getNamespace(),
			'page_title' => $page->getTitle()->getDBkey(),
			'page_id' => (string)$page->getId(),
			'page_latest' => (string)$page->getLatest(),
			'page_is_redirect' => $page->isRedirect() ? '1' : '0',
			'page_len' => (string)$page->getContent()->getSize(),
			'user_name' => (string)$rev->getUserText(),
		];
	}

	public function provideNewMutableRevisionFromArray() {
		yield 'Basic array, with page & id' => [
			[
				'id' => 2,
				'page' => 1,
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
		yield 'Basic array, content object' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content' => new WikitextContent( 'Some Content' ),
			]
		];
		yield 'Basic array, serialized text' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'text' => ( new WikitextContent( 'Söme Content' ) )->serialize(),
			]
		];
		yield 'Basic array, serialized text, utf-8 flags' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'text' => ( new WikitextContent( 'Söme Content' ) )->serialize(),
				'flags' => 'utf-8',
			]
		];
		yield 'Basic array, with title' => [
			[
				'title' => Title::newFromText( 'SomeText' ),
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
		yield 'Basic array, no user field' => [
			[
				'id' => 2,
				'page' => 1,
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.3',
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray( array $array ) {
		$factory = MediaWikiServices::getInstance()->getRevisionFactory();

		$result = $factory->newMutableRevisionFromArray( $array );

		if ( isset( $array['id'] ) ) {
			$this->assertSame( $array['id'], $result->getId() );
		}
		if ( isset( $array['page'] ) ) {
			$this->assertSame( $array['page'], $result->getPageId() );
		}
		$this->assertSame( $array['timestamp'], $result->getTimestamp() );
		$this->assertSame( $array['user_text'], $result->getUser()->getName() );
		if ( isset( $array['user'] ) ) {
			$this->assertSame( $array['user'], $result->getUser()->getId() );
		}
		$this->assertSame( (bool)$array['minor_edit'], $result->isMinor() );
		$this->assertSame( $array['deleted'], $result->getVisibility() );
		$this->assertSame( $array['len'], $result->getSize() );
		$this->assertSame( $array['parent_id'], $result->getParentId() );
		$this->assertSame( $array['sha1'], $result->getSha1() );
		$this->assertSame( $array['comment'], $result->getComment()->text );
		if ( isset( $array['content'] ) ) {
			$this->assertTrue(
				$result->getSlot( 'main' )->getContent()->equals( $array['content'] )
			);
		} elseif ( isset( $array['text'] ) ) {
			$this->assertSame( $array['text'], $result->getSlot( 'main' )->getContent()->serialize() );
		} else {
			$this->assertSame(
				$array['content_format'],
				$result->getSlot( 'main' )->getContent()->getDefaultFormat()
			);
			$this->assertSame( $array['content_model'], $result->getSlot( 'main' )->getModel() );
		}
	}

	/**
	 * @dataProvider provideNewMutableRevisionFromArray
	 * @covers \MediaWiki\Storage\SingleContentRevisionFactory::newMutableRevisionFromArray
	 */
	public function testNewMutableRevisionFromArray_legacyEncoding( array $array ) {
		$cache = new WANObjectCache( [ 'cache' => new HashBagOStuff() ] );
		$blobStore = new SqlBlobStore( wfGetLB(), $cache );
		$blobStore->setLegacyEncoding( 'windows-1252', Language::factory( 'en' ) );

		$factory = $this->getMockBuilder( BlobStoreFactory::class )
			->setMethods( [ 'newBlobStore', 'newSqlBlobStore' ] )
			->disableOriginalConstructor()
			->getMock();
		$factory->expects( $this->any() )
			->method( 'newBlobStore' )
			->willReturn( $blobStore );
		$factory->expects( $this->any() )
			->method( 'newSqlBlobStore' )
			->willReturn( $blobStore );

		$this->setService( 'BlobStoreFactory', $factory );

		$this->testNewMutableRevisionFromArray( $array );
	}

}