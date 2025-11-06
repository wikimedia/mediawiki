<?php
namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\User\UserIdentity;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @covers \MediaWiki\Specials\SpecialBlockList
 * @group Database
 */
class SpecialBlockListTest extends SpecialPageTestBase {
	use TempUserTestTrait;

	private const TEST_BLOCKED_IP = '127.0.0.1';

	private static string $blockedTempUserName;
	private static string $blockedUserName;

	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'BlockList' );
	}

	private function makeTestBlock( UserIdentity $target ): void {
		$status = $this->getServiceContainer()
			->getBlockUserFactory()
			->newBlockUser(
				$target,
				$this->getTestSysop()->getAuthority(),
				'infinity'
			)
			->placeBlock();

		$this->assertStatusGood( $status, "Failed to place block for {$target->getName()}" );
	}

	public function addDBDataOnce() {
		$this->enableAutoCreateTempUser();

		$req = new FauxRequest();
		$tempUser = $this->getServiceContainer()
			->getTempUserCreator()
			->create( null, $req )
			->getUser();

		$namedUser = $this->getTestUser()->getUser();

		self::$blockedTempUserName = $tempUser->getName();
		self::$blockedUserName = $namedUser->getName();

		$this->makeTestBlock( $tempUser );

		$this->makeTestBlock( $this->getTestUser()->getUser() );

		$this->makeTestBlock(
			$this->getServiceContainer()->getUserFactory()->newAnonymous( self::TEST_BLOCKED_IP )
		);
	}

	/**
	 * @dataProvider provideTargetTypes
	 */
	public function testShouldAllowFilteringBlocksByTargetType(
		?string $targetType,
		callable $expectedBlockTargetNamesProvider,
		bool $tempAccountsKnown = true
	): void {
		$this->disableAutoCreateTempUser( [
			'known' => $tempAccountsKnown,
		] );

		$queryParams = $targetType ? [ 'wpOptions' => [ $targetType ] ] : [];
		$req = new FauxRequest( $queryParams );

		[ $html ] = $this->executeSpecialPage( '', $req );

		$doc = DOMUtils::parseHTML( $html );
		$targetUserNames = [];
		foreach ( DOMCompat::querySelectorAll( $doc, '.TablePager_col_target > .mw-userlink' ) as $targetUser ) {
			$targetUserNames[] = $targetUser->textContent;
		}

		$this->assertSame(
			$expectedBlockTargetNamesProvider(),
			$targetUserNames
		);
	}

	public static function provideTargetTypes(): iterable {
		yield 'no target type, temp accounts known' => [
			null,
			static fn () => [ self::TEST_BLOCKED_IP, self::$blockedUserName, self::$blockedTempUserName ]
		];
		yield 'named user blocks excluded, temp accounts known' => [
			'userblocks',
			static fn () => [ self::TEST_BLOCKED_IP, self::$blockedTempUserName ]
		];
		yield 'IP blocks excluded, temp accounts known' => [
			'addressblocks',
			static fn () => [ self::$blockedUserName, self::$blockedTempUserName ]
		];
		yield 'temp user blocks excluded, temp accounts known' => [
			'tempuserblocks',
			static fn () => [ self::TEST_BLOCKED_IP, self::$blockedUserName ]
		];

		yield 'no target type, temp accounts not known' => [
			null,
			static fn () => [ self::TEST_BLOCKED_IP, self::$blockedUserName, self::$blockedTempUserName ],
			false
		];
		yield 'user blocks excluded, temp accounts not known' => [
			'userblocks',
			static fn () => [ self::TEST_BLOCKED_IP ],
			false
		];
		yield 'IP blocks excluded, temp accounts not known' => [
			'addressblocks',
			static fn () => [ self::$blockedUserName, self::$blockedTempUserName ],
			false
		];
	}

	public function testShouldAdaptFilterOptionsWhenTemporaryAccountsAreKnown(): void {
		$this->disableAutoCreateTempUser( [
			'known' => true,
		] );

		[ $html ] = $this->executeSpecialPage();

		$doc = DOMUtils::parseHTML( $html );

		$accountBlocksFilter = DOMCompat::querySelector( $doc, 'input[name="wpOptions[]"][value="userblocks"]' );
		$accountBlocksLabel = DOMCompat::querySelector(
			$doc, "label[for=\"{$accountBlocksFilter->getAttribute( 'id' )}\"]"
		);

		$this->assertSame(
			'(blocklist-nameduserblocks)',
			$accountBlocksLabel->textContent
		);
		$this->assertNotNull(
			DOMCompat::querySelector( $doc, 'input[name="wpOptions[]"][value="tempuserblocks"]' ),
			'Temporary accounts filter checkbox should be present'
		);
	}

	public function testShouldNotShowTemporaryAccountsFilterCheckboxWhenTemporaryAccountsAreNotKnown(): void {
		$this->disableAutoCreateTempUser();

		[ $html ] = $this->executeSpecialPage();

		$doc = DOMUtils::parseHTML( $html );

		$accountBlocksFilter = DOMCompat::querySelector( $doc, 'input[name="wpOptions[]"][value="userblocks"]' );
		$accountBlocksLabel = DOMCompat::querySelector(
			$doc, "label[for=\"{$accountBlocksFilter->getAttribute( 'id' )}\"]"
		);

		$this->assertSame(
			'(blocklist-userblocks)',
			$accountBlocksLabel->textContent
		);
		$this->assertNull(
			DOMCompat::querySelector( $doc, 'input[name="wpOptions[]"][value="tempuserblocks"]' ),
			'Temporary accounts filter checkbox should not be present'
		);
	}
}
