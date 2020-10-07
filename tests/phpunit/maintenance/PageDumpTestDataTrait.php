<?php

namespace MediaWiki\Tests\Maintenance;

use Exception;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MWException;
use RequestContext;
use RevisionDeleter;
use Title;
use Wikimedia\Rdbms\IDatabase;
use WikiPage;
use WikitextContent;

/**
 * Trait for creating a know set of test pages in the database,
 * corresponding to the XML data files in tests/phpunit/data/dumps,
 * to be used used with DumpAsserter::assertDOM().
 */
trait PageDumpTestDataTrait {

	// We'll add several pages, revision and texts. The following variables hold the
	// corresponding ids.

	/** @var int */
	private $pageId1, $pageId2, $pageId3, $pageId4, $pageId5;

	/** @var Title */
	private $pageTitle1, $pageTitle2, $pageTitle3, $pageTitle4, $pageTitle5;

	private static $numOfPages = 4;
	private static $numOfRevs = 8;

	/** @var RevisionRecord */
	private $rev1_1;
	/** @var RevisionRecord */
	private $rev2_1, $rev2_2;
	/** @var RevisionRecord */
	private $rev2_3, $rev2_4;
	/** @var RevisionRecord */
	private $rev3_1, $rev3_2;
	/** @var RevisionRecord */
	private $rev4_1;
	/** @var RevisionRecord */
	private $rev5_1;

	private $namespace, $talk_namespace;

	protected function addTestPages() {
		// be sure, titles created here using english namespace names
		$this->setContentLang( 'en' );

		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'ip_changes';
		$this->tablesUsed[] = 'text';

		try {
			$this->namespace = $this->getDefaultWikitextNS();
			$this->talk_namespace = NS_TALK;

			if ( $this->namespace === $this->talk_namespace ) {
				// @todo work around this.
				throw new MWException( "The default wikitext namespace is the talk namespace. "
					. " We can't currently deal with that." );
			}

			$this->pageTitle1 = Title::newFromText( 'BackupDumperTestP1', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle1 );
			$this->rev1_1 = $this->addMultiSlotRevision(
				$page,
				[
					'main' => new WikitextContent( 'BackupDumperTestP1Text1' ),
					'aux' => new WikitextContent( 'BackupDumperTestP1Text1/aux' ),
				],
				"BackupDumperTestP1Summary1"
			);
			$this->pageId1 = $page->getId();

			$this->pageTitle2 = Title::newFromText( 'BackupDumperTestP2', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle2 );
			[ , , $this->rev2_1 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text1", "BackupDumperTestP2Summary1" );
			[ , , $this->rev2_2 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text2", "BackupDumperTestP2Summary2" );
			[ , , $this->rev2_3 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text3", "BackupDumperTestP2Summary3" );
			[ , , $this->rev2_4 ] = $this->addRevision( $page,
				"BackupDumperTestP2Text4 some additional Text  ",
				"BackupDumperTestP2Summary4 extra " );
			$this->pageId2 = $page->getId();

			$context = RequestContext::getMain();
			$revDel = RevisionDeleter::createList(
				'revision',
				$context,
				$this->pageTitle2,
				[ $this->rev2_2->getId() ]
			);
			$revDel->setVisibility( [
				'value' => [ RevisionRecord::DELETED_TEXT => 1 ],
				'comment' => 'testing!'
			] );

			// re-load from database, with correct deletion status
			$this->rev2_2 = MediaWikiServices::getInstance()->getRevisionLookup()->getRevisionById(
				$this->rev2_2->getId()
			);

			$this->pageTitle3 = Title::newFromText( 'BackupDumperTestP3', $this->namespace );
			$page = WikiPage::factory( $this->pageTitle3 );
			[ , , $this->rev3_1 ] = $this->addRevision( $page,
				"BackupDumperTestP3Text1", "BackupDumperTestP2Summary1" );
			[ , , $this->rev3_2 ] = $this->addRevision( $page,
				"BackupDumperTestP3Text2", "BackupDumperTestP2Summary2" );
			$this->pageId3 = $page->getId();
			$page->doDeleteArticleReal( "Testing ;)", $context->getUser() );

			$this->pageTitle4 = Title::newFromText( 'BackupDumperTestP1', $this->talk_namespace );
			$page = WikiPage::factory( $this->pageTitle4 );
			[ , , $this->rev4_1 ] = $this->addRevision( $page,
				"Talk about BackupDumperTestP1 Text1",
				"Talk BackupDumperTestP1 Summary1" );
			$this->pageId4 = $page->getId();

			$this->pageTitle5 = Title::newFromText( 'BackupDumperTestP5' );
			$page = WikiPage::factory( $this->pageTitle5 );
			[ , , $this->rev5_1 ] = $this->addRevision( $page,
				"BackupDumperTestP5 Text1",
				"BackupDumperTestP5 Summary1" );
			$this->pageId5 = $page->getId();

			$this->rev5_1 = $this->corruptRevisionData( $this->db, $this->rev5_1 );
		} catch ( Exception $e ) {
			// We'd love to pass $e directly. However, ... see
			// documentation of exceptionFromAddDBData in
			// DumpTestCase
			$this->exceptionFromAddDBData = $e;
		}

		// Since we will restrict dumping by page ranges (to allow
		// working tests, even if the db gets prepopulated by a base
		// class), we have to assert, that the page id are consecutively
		// increasing
		$this->assertEquals(
			[ $this->pageId2, $this->pageId3, $this->pageId4 ],
			[ $this->pageId1 + 1, $this->pageId1 + 2, $this->pageId1 + 3 ],
			"Page ids increasing without holes" );
	}

	/**
	 * @param string $name
	 * @param string $schemaVersion
	 *
	 * @return string path of the dump file
	 */
	protected function getDumpTemplatePath( $name, $schemaVersion ) {
		return __DIR__ . "/../data/dumps/$name.$schemaVersion.xml";
	}

	/**
	 * Corrupt the information about the given revision in the database.
	 *
	 * @param IDatabase $db
	 * @param RevisionRecord $revision
	 *
	 * @return RevisionRecord
	 */
	protected function corruptRevisionData( IDatabase $db, RevisionRecord $revision ) {
		global $wgMultiContentRevisionSchemaMigrationStage;

		if ( ( $wgMultiContentRevisionSchemaMigrationStage & SCHEMA_COMPAT_WRITE_OLD ) ) {
			$db->update(
				'revision',
				[ 'rev_text_id' => 0 ],
				[ 'rev_id' => $revision->getId() ]
			);
		}

		if ( ( $wgMultiContentRevisionSchemaMigrationStage & SCHEMA_COMPAT_WRITE_NEW ) ) {
			$db->update(
				'content',
				[ 'content_address' => 'tt:0' ],
				[ 'content_id' => $revision->getSlot( \MediaWiki\Revision\SlotRecord::MAIN )->getContentId() ]
			);
		}

		$revision = MediaWikiServices::getInstance()->getRevisionLookup()->getRevisionById(
			$revision->getId()
		);
		return $revision;
	}

	/**
	 * Register variables for use with DumpAsserter::assertDOM().
	 *
	 * @param string $prefix
	 * @param RevisionRecord $rev
	 * @param DumpAsserter $asserter
	 */
	protected function setRevisionVarMappings( $prefix, RevisionRecord $rev, DumpAsserter $asserter ) {
		$ts = wfTimestamp( TS_ISO_8601, $rev->getTimestamp() );
		$title = MediaWikiServices::getInstance()->getTitleFormatter()->getPrefixedText(
			$rev->getPageAsLinkTarget()
		);

		$asserter->setVarMapping( $prefix . 'pageid', $rev->getPageId() );
		$asserter->setVarMapping( $prefix . 'title', $title );
		$asserter->setVarMapping( $prefix . 'namespace', $rev->getPageAsLinkTarget()->getNamespace() );

		$asserter->setVarMapping( $prefix . 'id', $rev->getId() );
		$asserter->setVarMapping( $prefix . 'parentid', $rev->getParentId() );
		$asserter->setVarMapping( $prefix . 'timestamp', $ts );
		$asserter->setVarMapping( $prefix . 'sha1', $rev->getSha1() );
		$asserter->setVarMapping( $prefix . 'username', $rev->getUser()->getName() );
		$asserter->setVarMapping( $prefix . 'userid', $rev->getUser()->getId() );
		$asserter->setVarMapping( $prefix . 'comment', $rev->getComment()->text );

		foreach ( $rev->getSlotRoles() as $role ) {
			$slot = $rev->getSlot( $role );

			$asserter->setVarMapping( $prefix . $role . '_textid', $this->getSlotTextId( $slot ) );
			$asserter->setVarMapping( $prefix . $role . '_location', $slot->getAddress() );
			$asserter->setVarMapping( $prefix . $role . '_text', $this->getSlotText( $slot ) );
			$asserter->setVarMapping( $prefix . $role . '_size', $slot->getSize() );
			$asserter->setVarMapping( $prefix . $role . '_sha1', $slot->getSha1() );
			$asserter->setVarMapping( $prefix . $role . '_origin', $slot->getOrigin() );
			$asserter->setVarMapping( $prefix . $role . '_model', $slot->getModel() );
			$asserter->setVarMapping( $prefix . $role . '_format', $this->getSlotFormat( $slot ) );
			$asserter->setVarMapping( $prefix . $role . '_role', $slot->getRole() );
		}
	}

	/**
	 * Register variables for use with DumpAsserter::assertDOM().
	 *
	 * @param DumpAsserter $asserter
	 */
	protected function setCurrentRevisionsVarMappings( DumpAsserter $asserter ) {
		$this->setRevisionVarMappings( 'rev1_1_', $this->rev1_1, $asserter );
		$this->setRevisionVarMappings( 'rev2_4_', $this->rev2_4, $asserter );
		// skip page 3, since it's deleted
		$this->setRevisionVarMappings( 'rev4_1_', $this->rev4_1, $asserter );
		$this->setRevisionVarMappings( 'rev5_1_', $this->rev5_1, $asserter );
	}

	/**
	 * Register variables for use with DumpAsserter::assertDOM().
	 *
	 * @param DumpAsserter $asserter
	 */
	protected function setAllRevisionsVarMappings( DumpAsserter $asserter ) {
		$this->setRevisionVarMappings( 'rev1_1_', $this->rev1_1, $asserter );
		$this->setRevisionVarMappings( 'rev2_1_', $this->rev2_1, $asserter );
		$this->setRevisionVarMappings( 'rev2_2_', $this->rev2_2, $asserter );
		$this->setRevisionVarMappings( 'rev2_3_', $this->rev2_3, $asserter );
		$this->setRevisionVarMappings( 'rev2_4_', $this->rev2_4, $asserter );
		// skip page 3, since it's deleted
		$this->setRevisionVarMappings( 'rev4_1_', $this->rev4_1, $asserter );
		$this->setRevisionVarMappings( 'rev5_1_', $this->rev5_1, $asserter );
	}

	private function setSiteVarMappings( DumpAsserter $asserter ) {
		global $wgSitename, $wgDBname, $wgVersion, $wgCapitalLinks;

		$asserter->setVarMapping( 'mw_version', $wgVersion );
		$asserter->setVarMapping( 'schema_version', $asserter->getSchemaVersion() );

		$asserter->setVarMapping( 'site_name', $wgSitename );
		$asserter->setVarMapping(
			'project_namespace',
			MediaWikiServices::getInstance()->getTitleFormatter()->getNamespaceName(
				NS_PROJECT,
				'Dummy'
			)
		);
		$asserter->setVarMapping( 'site_db', $wgDBname );
		$asserter->setVarMapping(
			'site_case',
			$wgCapitalLinks ? 'first-letter' : 'case-sensitive'
		);
		$asserter->setVarMapping(
			'site_base',
			Title::newMainPage()->getCanonicalURL()
		);
		$asserter->setVarMapping(
			'site_language',
			MediaWikiServices::getInstance()->getContentLanguage()->getHtmlCode()
		);
	}
}
