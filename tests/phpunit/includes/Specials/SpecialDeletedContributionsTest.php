<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialDeletedContributions;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @group Database
 * @covers \MediaWiki\Specials\SpecialDeletedContributions
 */
class SpecialDeletedContributionsTest extends SpecialPageTestBase {

	use TempUserTestTrait;

	private static User $sysop;
	private static User $userNameWithSpaces;

	protected function newSpecialPage(): SpecialDeletedContributions {
		$services = $this->getServiceContainer();

		return new SpecialDeletedContributions(
			$services->getPermissionManager(),
			$services->getConnectionProvider(),
			$services->getRevisionStore(),
			$services->getNamespaceInfo(),
			$services->getUserNameUtils(),
			$services->getUserNamePrefixSearch(),
			$services->getUserOptionsLookup(),
			$services->getCommentFormatter(),
			$services->getLinkBatchFactory(),
			$services->getUserFactory(),
			$services->getUserIdentityLookup(),
			$services->getDatabaseBlockStore(),
			$services->getUserGroupAssignmentService(),
			$services->getTempUserConfig()
		);
	}

	public function testExecuteNoTarget() {
		[ $html ] = $this->executeSpecialPage(
			'',
			null,
			null,
			self::$sysop,
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteInvalidTarget() {
		[ $html ] = $this->executeSpecialPage(
			'#InvalidUserName',
			null,
			null,
			self::$sysop,
		);
		$this->assertStringNotContainsString( 'mw-pager-body', $html );
	}

	/** @dataProvider provideExecuteNoResultsForIPTarget */
	public function testExecuteNoResultsForIPTarget( $temporaryAccountsEnabled, $expectedPageTitleMessageKey ) {
		if ( $temporaryAccountsEnabled ) {
			$this->enableAutoCreateTempUser();
		} else {
			$this->disableAutoCreateTempUser();
		}
		[ $html ] = $this->executeSpecialPage(
			'127.0.0.1',
			null,
			null,
			self::$sysop,
			true
		);
		$specialPageDocument = DOMUtils::parseHTML( $html );
		$contentHtml = DOMCompat::querySelector( $specialPageDocument, '#content' )->nodeValue;
		$this->assertStringNotContainsString( 'mw-pager-body', $contentHtml );
		$this->assertStringContainsString( "($expectedPageTitleMessageKey: 127.0.0.1", $contentHtml );
	}

	public static function provideExecuteNoResultsForIPTarget() {
		return [
			'Temporary accounts not enabled' => [ false, 'deletedcontributions-title' ],
			'Temporary accounts enabled' => [
				true, 'deletedcontributions-title-for-ip-when-temporary-accounts-enabled',
			],
		];
	}

	public function testExecuteUserNameWithEscapedSpaces() {
		$par = strtr( self::$userNameWithSpaces->getName(), ' ', '_' );
		[ $html ] = $this->executeSpecialPage(
			$par,
			null,
			null,
			self::$sysop,
		);
		$this->assertStringContainsString( 'mw-pager-body', $html );
	}

	public function testExecuteNamespaceFilter() {
		[ $html ] = $this->executeSpecialPage(
			self::$sysop->getName(),
			new FauxRequest( [
				'namespace' => NS_TALK,
			] ),
			null,
			self::$sysop,
		);
		$this->assertStringContainsString( 'mw-pager-body', $html );
	}

	public function addDBDataOnce() {
		self::$sysop = $this->getTestSysop()->getUser();
		self::$userNameWithSpaces = $this->getMutableTestUser( [], 'Test User' )->getUser();

		$title = Title::makeTitle( NS_TALK, 'DeletedContribsPagerTest' );

		// Make some edits
		$this->editPage( $title, '', '', NS_MAIN, self::$sysop );
		$this->editPage( $title, 'test', '', NS_MAIN, self::$userNameWithSpaces );
		$status = $this->editPage( $title, 'Test content.', '', NS_MAIN, self::$sysop );

		// Delete the page where the edits were made
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->deletePage( $page );
	}
}
