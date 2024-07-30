<?php

namespace MediaWiki\Tests\Maintenance;

use IDBAccessObject;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Title\Title;
use RollbackEdits;

/**
 * @covers \RollbackEdits
 * @group Database
 * @author Dreamy Jazz
 */
class RollbackEditsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return RollbackEdits::class;
	}

	public function testExecuteWhenUserHasNoPagesToRollback() {
		$this->maintenance->setOption( 'user', $this->getTestUser()->getUserIdentity()->getName() );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/No suitable titles to be rolled back./" );
	}

	public function testExecuteWhenAllTitlesInvalid() {
		$this->maintenance->setOption( 'titles', '::|~~~~' );
		$this->maintenance->setOption( 'user', $this->getTestUser()->getUserIdentity()->getName() );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/No suitable titles to be rolled back./" );
	}

	public function testExecuteForAllNonExistingTitles() {
		$this->maintenance->setOption( 'titles', 'Non-existing-test-page' );
		$this->maintenance->setOption( 'user', $this->getTestUser()->getUserIdentity()->getName() );
		$this->maintenance->execute();
		$this->expectOutputRegex( "/Processing Non-existing-test-page...Failed!/" );
	}

	/**
	 * @param int $count
	 * @return Title[]
	 */
	private function getExistingTestPages( int $count ): array {
		$returnArray = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$returnArray[] = $this->getExistingTestPage()->getTitle();
		}
		return $returnArray;
	}

	/**
	 * @param Title[] $titlesToBeRolledBack
	 * @param Authority $authority
	 * @param array $options
	 * @return void
	 */
	private function commonExecuteForSuccess( array $titlesToBeRolledBack, Authority $authority, array $options ) {
		$expectedOutputRegex = '/';
		foreach ( $titlesToBeRolledBack as $title ) {
			$this->editPage( $title, 'testing-12345', '', NS_MAIN, $authority );
			$expectedOutputRegex .= 'Processing ' . preg_quote( $title->getPrefixedText() ) . "\.\.\.Done!\n";
		}
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->setOption( 'user', $authority->getUser()->getName() );
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex . '/' );
		foreach ( $titlesToBeRolledBack as $title ) {
			// Assert that the content of the first revision and the latest revision are the same after the rollback.
			$latestRevision = $this->getServiceContainer()->getRevisionLookup()
				->getRevisionById( $title->getLatestRevID( IDBAccessObject::READ_LATEST ) );
			$firstRevision = $this->getServiceContainer()->getRevisionLookup()
				->getFirstRevision( $title );
			$this->assertSame(
				$firstRevision->getContent( SlotRecord::MAIN )->getWikitextForTransclusion(),
				$latestRevision->getContent( SlotRecord::MAIN )->getWikitextForTransclusion()
			);
			// Assert the last revision is marked as a rollback
			$this->assertContains( 'mw-rollback', $this->getServiceContainer()->getChangeTagsStore()->getTags( $this->getDb(), null, $latestRevision->getId() ) );
		}
	}

	public function testExecuteForUser() {
		$testUser = $this->getTestUser()->getAuthority();
		$titles = $this->getExistingTestPages( 2 );
		$this->commonExecuteForSuccess( $titles, $testUser, [] );
	}

	public function testExecuteForUserWithTitlesSet() {
		$testUser = $this->getTestUser()->getAuthority();
		$titles = $this->getExistingTestPages( 3 );
		$titleNotToBeRolledBack = array_pop( $titles );
		$this->editPage( $titleNotToBeRolledBack, 'testing-12345', '', NS_MAIN, $testUser );
		// Limit the rollbacks to only the specified pages.
		$this->commonExecuteForSuccess(
			$titles, $testUser, [ 'titles' => $titles[0]->getPrefixedText() . '|' . $titles[1]->getPrefixedText() ]
		);
		// Assert that the $titleNotToBeRolledBack was not rollbacked.
		$latestRevision = $this->getServiceContainer()->getRevisionLookup()
			->getRevisionById( $titleNotToBeRolledBack->getLatestRevID() );
		$firstRevision = $this->getServiceContainer()->getRevisionLookup()
			->getFirstRevision( $titleNotToBeRolledBack );
		$this->assertNotSame(
			$firstRevision->getContent( SlotRecord::MAIN )->getWikitextForTransclusion(),
			$latestRevision->getContent( SlotRecord::MAIN )->getWikitextForTransclusion()
		);
	}
}
