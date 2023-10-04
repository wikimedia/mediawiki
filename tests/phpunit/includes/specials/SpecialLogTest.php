<?php
/**
 * @license GPL-2.0-or-later
 * @author Legoktm
 */

use MediaWiki\Request\FauxRequest;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialLog;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialLog
 */
class SpecialLogTest extends SpecialPageTestBase {

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		$services = $this->getServiceContainer();
		return new SpecialLog(
			$services->getLinkBatchFactory(),
			$services->getDBLoadBalancerFactory(),
			$services->getActorNormalization(),
			$services->getUserIdentityLookup(),
			$services->getUserNameUtils()
		);
	}

	/**
	 * Verify that no exception was thrown for an invalid date
	 * @see T201411
	 */
	public function testInvalidDate() {
		[ $html, ] = $this->executeSpecialPage(
			'',
			// There is no 13th month
			new FauxRequest( [ 'wpdate' => '2018-13-01' ] ),
			'qqx'
		);
		$this->assertStringContainsString( '(log-summary)', $html );
	}

	public function testSuppressionLog() {
		// Have "BadGuy" create a revision
		$user = ( new TestUser( 'BadGuy' ) )->getUser();
		$title = $this->insertPage( 'Foo', 'Bar', null, $user )['title'];
		$revId = $title->getLatestRevID();

		// Hide our revision's comment
		$list = RevisionDeleter::createList( 'revision', RequestContext::getMain(), $title, [ $revId ] );
		$status = $list->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_RESTRICTED => 1,
				RevisionRecord::DELETED_COMMENT => 1
			],
			'comment' => 'SpecialLogTest'
		] );
		$this->assertStatusGood( $status );

		// Allow everyone to read the suppression log
		$this->mergeMwGlobalArrayValue(
			'wgGroupPermissions', [
				'*' => [
					'suppressionlog' => true
				]
			]
		);

		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest( [ 'offender' => 'BadGuy' ] ),
			'qqx'
		);
		$this->assertStringNotContainsString( '(logempty)', $html );
		$this->assertStringContainsString( '(logentry-suppress-revision', $html );

		// Full suppression log
		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest(),
			'qqx'
		);
		$this->assertStringNotContainsString( '(logempty)', $html );
		$this->assertStringContainsString( '(logentry-suppress-revision', $html );

		// Suppression log for unknown user should be empty
		[ $html, ] = $this->executeSpecialPage(
			'suppress',
			new FauxRequest( [ 'offender' => 'GoodGuy' ] ),
			'qqx'
		);
		$this->assertStringContainsString( '(logempty)', $html );
		$this->assertStringNotContainsString( '(logentry-suppress-revision', $html );
	}

}
