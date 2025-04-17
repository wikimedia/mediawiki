<?php

namespace MediaWiki\Tests\Maintenance;

use FixInconsistentRedirects;
use MediaWiki\Title\Title;

/**
 * @covers \FixInconsistentRedirects
 * @covers \RefreshLinks
 * @group Database
 * @author Dreamy Jazz
 */
class FixInconsistentRedirectsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return FixInconsistentRedirects::class;
	}

	public function testExecuteWhenNoPages() {
		$this->maintenance->execute();
		$this->expectOutputRegex( '/Done, updated 0 of 0 rows/' );
	}

	public function testExecuteWhenRedirectExistsThatIsNotBroken() {
		$this->editPage(
			Title::newFromText( 'Test' ),
			'#REDIRECT [[Test2]]'
		);
		$this->getExistingTestPage( 'Test2' );

		$this->maintenance->execute();
		$this->expectOutputRegex( '/Done, updated 0 of 1 rows/' );
	}

	public function testExecuteWhenRedirectExistsThatHasNoRedirectTableRow() {
		$fromTitle = Title::newFromText( 'Test' );
		$toTitle = Title::newFromText( 'Test2#testing' );
		$this->editPage( $fromTitle, '#REDIRECT [[Test2#testing]]' );
		$this->getExistingTestPage( $toTitle );

		// Truncate the 'redirect' table to test when the row does not exist.
		$this->truncateTable( 'redirect' );

		$this->maintenance->execute();
		$this->expectOutputRegex( '/Done, updated 1 of 1 rows/' );

		// Check that the redirect table row now exists and is valid.
		$this->newSelectQueryBuilder()
			->select( [ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ] )
			->from( 'redirect' )
			->caller( __METHOD__ )
			->assertRowValue( [
				$fromTitle->getId(), $fromTitle->getNamespace(), $toTitle->getDBkey(),
				$toTitle->getFragment(), $toTitle->getInterwiki(),
			] );
	}

	public function testExecuteWhenRedirectExistsThatHasPartlyBrokenRedirectTableRow() {
		$fromTitle = Title::newFromText( 'Test' );
		$toTitle = Title::newFromText( 'Test2#testing' );
		$this->editPage( $fromTitle, '#REDIRECT [[Test2#testing]]' );
		$this->getExistingTestPage( $toTitle );

		// Make the rd_fragment value be invalid but leave other data as valid for the redirect.
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'redirect' )
			->set( [ 'rd_fragment' => null ] )
			->where( [ 'rd_from' => $fromTitle->getId() ] )
			->caller( __METHOD__ )
			->execute();

		$this->maintenance->execute();
		$this->expectOutputRegex( '/Done, updated 1 of 1 rows/' );

		// Check that the redirect table row exists and is now valid.
		$this->newSelectQueryBuilder()
			->select( [ 'rd_from', 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ] )
			->from( 'redirect' )
			->caller( __METHOD__ )
			->assertRowValue( [
				$fromTitle->getId(), $fromTitle->getNamespace(), $toTitle->getDBkey(),
				$toTitle->getFragment(), $toTitle->getInterwiki(),
			] );
	}
}
