<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group Database
 * @covers DeleteRevision
 */
class DeleteRevisionTest extends MediaWikiTestCase {

	protected $tablesUsed = [
		'archive',
		'page',
		'revision',
		'revision_comment_temp',
		'comment',
	];

	private function getRevisionData( Revision $rev ) {
		return [
			'title' => $rev->getTitle()->getPrefixedText(),
			'content' => $rev->getContent( Revision::RAW )->serialize(),
			'comment' => $rev->getComment( Revision::RAW ),
			'user' => $rev->getUserText( Revision::RAW ),
			'timestamp' => $rev->getTimestamp(),
			'isminor' => $rev->isMinor(),
			'rev_id' => $rev->getId(),
			'text_id' => $rev->getTextId(),
			'visibility' => $rev->getVisibility(),
			'size' => $rev->getSize(),
			'page_id' => $rev->getPage(),
			'parent_id' => (int)$rev->getParentId(),
			'sha1' => $rev->getSha1(),
			'model' => $rev->getContentModel(),
			'format' => $rev->getContentFormat(),
		];
	}

	/**
	 * @dataProvider provideDeletion
	 * @param int $wstage
	 * @param int $rstage
	 */
	public function testDeletion( $wstage, $rstage ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', $wstage );

		$res = $this->insertPage( __METHOD__ );
		$rev = Revision::newFromPageId( $res['id'] );
		$id = $rev->getId();
		$expect = $this->getRevisionData( $rev );

		$del = TestingAccessWrapper::newFromObject( new DeleteRevision() );
		$del->mQuiet = true;
		$del->mArgs = [ $id ];
		$del->execute();

		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', $rstage );

		$row = $this->db->selectRow(
			'archive',
			Revision::selectArchiveFields(),
			[ 'ar_rev_id' => $id ],
			__METHOD__
		);
		$rev = Revision::newFromArchiveRow( $row );
		$actual = $this->getRevisionData( $rev );

		$this->assertEquals( $expect, $actual );
	}

	public static function provideDeletion() {
		return [
			[ MIGRATION_OLD, MIGRATION_OLD ],
			[ MIGRATION_OLD, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_OLD, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_OLD ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_BOTH, MIGRATION_NEW ],
			[ MIGRATION_WRITE_NEW, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_WRITE_NEW, MIGRATION_WRITE_NEW ],
			[ MIGRATION_WRITE_NEW, MIGRATION_NEW ],
			[ MIGRATION_NEW, MIGRATION_WRITE_BOTH ],
			[ MIGRATION_NEW, MIGRATION_WRITE_NEW ],
			[ MIGRATION_NEW, MIGRATION_NEW ],
		];
	}

}
