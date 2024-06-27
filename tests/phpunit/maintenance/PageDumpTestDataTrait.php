<?php

namespace MediaWiki\Tests\Maintenance;

use Exception;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use RevisionDeleter;
use RuntimeException;
use Wikimedia\Rdbms\IDatabase;
use WikitextContent;

/**
 * Trait for creating a know set of test pages in the database,
 * corresponding to the XML data files in tests/phpunit/data/dumps,
 * to be used used with DumpAsserter::assertDOM().
 */
trait PageDumpTestDataTrait {

	// We'll add several pages, revision and texts. The following variables hold the
	// corresponding ids.

	// phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.WrongStyle
	private int $pageId1;
	private int $pageId2;
	private int $pageId3;
	private int $pageId4;
	private int $pageId5;

	private Title $pageTitle1;
	private Title $pageTitle2;
	private Title $pageTitle3;
	private Title $pageTitle4;
	private Title $pageTitle5;

	private static $numOfPages = 4;
	private static $numOfRevs = 8;

	private RevisionRecord $rev1_1;
	private RevisionRecord $rev2_1;
	private RevisionRecord $rev2_2;
	private RevisionRecord $rev2_3;
	private RevisionRecord $rev2_4;
	private RevisionRecord $rev3_1;
	private RevisionRecord $rev3_2;
	private RevisionRecord $rev4_1;
	private RevisionRecord $rev5_1;

	private int $namespace;
	private int $talk_namespace;

	protected function addTestPages( User $sysopUser ) {
		// be sure, titles created here using english namespace names
		$this->setContentLang( 'en' );

		try {
			$this->namespace = $this->getDefaultWikitextNS();
			$this->talk_namespace = NS_TALK;

			if ( $this->namespace === $this->talk_namespace ) {
				// @todo work around this.
				throw new RuntimeException( "The default wikitext namespace is the talk namespace. "
					. " We can't currently deal with that." );
			}

			$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
			$this->pageTitle1 = Title::makeTitle( $this->namespace, 'BackupDumperTestP1' );
			$page = $wikiPageFactory->newFromTitle( $this->pageTitle1 );
			$this->rev1_1 = $this->addMultiSlotRevision(
				$page,
				[
					SlotRecord::MAIN => new WikitextContent( 'BackupDumperTestP1Text1' ),
					'aux' => new WikitextContent( 'BackupDumperTestP1Text1/aux' ),
				],
				"BackupDumperTestP1Summary1"
			);
			$this->pageId1 = $page->getId();

			$this->pageTitle2 = Title::makeTitle( $this->namespace, 'BackupDumperTestP2' );
			$page = $wikiPageFactory->newFromTitle( $this->pageTitle2 );
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

			$context = new DerivativeContext( RequestContext::getMain() );
			$context->setUser( $sysopUser );

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
			$this->rev2_2 = $this->getServiceContainer()->getRevisionLookup()->getRevisionById(
				$this->rev2_2->getId()
			);

			$this->pageTitle3 = Title::makeTitle( $this->namespace, 'BackupDumperTestP3' );
			$page = $wikiPageFactory->newFromTitle( $this->pageTitle3 );
			[ , , $this->rev3_1 ] = $this->addRevision( $page,
				"BackupDumperTestP3Text1", "BackupDumperTestP2Summary1" );
			[ , , $this->rev3_2 ] = $this->addRevision( $page,
				"BackupDumperTestP3Text2", "BackupDumperTestP2Summary2" );
			$this->pageId3 = $page->getId();
			$this->getServiceContainer()->getDeletePageFactory()
				->newDeletePage( $page, $context->getAuthority() )
				->deleteUnsafe( "Testing" );

			$this->pageTitle4 = Title::makeTitle( $this->talk_namespace, 'BackupDumperTestP1' );
			$page = $wikiPageFactory->newFromTitle( $this->pageTitle4 );
			[ , , $this->rev4_1 ] = $this->addRevision( $page,
				"Talk about BackupDumperTestP1 Text1",
				"Talk BackupDumperTestP1 Summary1" );
			$this->pageId4 = $page->getId();

			$this->pageTitle5 = Title::makeTitle( NS_MAIN, 'BackupDumperTestP5' );
			$page = $wikiPageFactory->newFromTitle( $this->pageTitle5 );
			[ , , $this->rev5_1 ] = $this->addRevision( $page,
				"BackupDumperTestP5 Text1",
				"BackupDumperTestP5 Summary1" );
			$this->pageId5 = $page->getId();

			$this->rev5_1 = $this->corruptRevisionData( $this->getDb(), $this->rev5_1 );
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
			[ $this->pageId1 + 1, $this->pageId1 + 2, $this->pageId1 + 3 ],
			[ $this->pageId2, $this->pageId3, $this->pageId4 ],
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
		$db->newUpdateQueryBuilder()
			->update( 'content' )
			->set( [ 'content_address' => 'tt:0' ] )
			->where( [ 'content_id' => $revision->getSlot( SlotRecord::MAIN )->getContentId() ] )
			->execute();

		$revision = $this->getServiceContainer()->getRevisionLookup()->getRevisionById(
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
		$title = $this->getServiceContainer()->getTitleFormatter()->getPrefixedText(
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
		$config = $this->getServiceContainer()->getMainConfig();

		$asserter->setVarMapping( 'mw_version', MW_VERSION );
		$asserter->setVarMapping( 'schema_version', $asserter->getSchemaVersion() );

		$asserter->setVarMapping( 'site_name', $config->get( MainConfigNames::Sitename ) );
		$asserter->setVarMapping(
			'project_namespace',
			$this->getServiceContainer()->getTitleFormatter()->getNamespaceName(
				NS_PROJECT,
				'Dummy'
			)
		);
		$asserter->setVarMapping( 'site_db', $config->get( MainConfigNames::DBname ) );
		$asserter->setVarMapping(
			'site_case',
			$config->get( MainConfigNames::CapitalLinks ) ? 'first-letter' : 'case-sensitive'
		);
		$asserter->setVarMapping(
			'site_base',
			Title::newMainPage()->getCanonicalURL()
		);
		$asserter->setVarMapping(
			'site_language',
			$this->getServiceContainer()->getContentLanguage()->getHtmlCode()
		);
	}
}
