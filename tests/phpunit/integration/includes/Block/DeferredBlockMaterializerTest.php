<?php

namespace MediaWiki\Tests\Integration\Block;

use MediaWiki\Block\Block;
use MediaWiki\Block\BlockUser;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DeferredBlockMaterializer;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Context\RequestContext;
use MediaWiki\Logging\LogEntryBase;
use MediaWiki\MainConfigNames;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWikiIntegrationTestCase;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @group Blocking
 * @group Database
 * @covers \MediaWiki\Block\DeferredBlockMaterializer
 * @covers \MediaWiki\Permissions\PermissionManager
 * @covers \MediaWiki\Permissions\UserAuthority
 */
class DeferredBlockMaterializerTest extends MediaWikiIntegrationTestCase {

	private User $target;
	private User $sysop;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::EnableMultiBlocks, true );
		$this->setGroupPermissions( 'sysop', 'deferredblock', true );
		$this->target = $this->getTestUser()->getUser();
		$this->sysop = $this->getTestUser( [ 'sysop', 'suppress' ] )->getUser();
		RequestContext::getMain()->setUser( $this->target );
	}

	private function placeDeferredBlock(
		string $expiry = 'infinity',
		array $options = [],
		array $restrictions = [],
		?string $target = null,
		string|bool $conflictMode = BlockUser::CONFLICT_FAIL
	): DatabaseBlock {
		$status = $this->getServiceContainer()->getBlockUserFactory()->newBlockUser(
			$target ?? $this->target,
			$this->sysop,
			$expiry,
			'test deferred block',
			[ 'isDeferred' => true ] + $options,
			$restrictions
		)->placeBlock( $conflictMode );
		$this->assertStatusGood( $status );
		return $status->getValue();
	}

	/**
	 * Attempt to edit the page as the user, and return the block that prevents it
	 */
	private function attemptEdit( string $page = 'Test page', ?User $user = null ): ?Block {
		$status = PermissionStatus::newEmpty();
		( $user ?? $this->target )->authorizeWrite( 'edit', Title::newFromText( $page ), $status );
		return $status->getBlock();
	}

	private function getMaterializer(): DeferredBlockMaterializer {
		return $this->getServiceContainer()->getDeferredBlockMaterializer();
	}

	private function isDeferred( DatabaseBlock $block ): bool {
		return $this->getServiceContainer()->getDatabaseBlockStore()
			->newFromID( $block->getId(), true, false, true )
			->isDeferred();
	}

	private function getLogRows( string $logType ): array {
		return iterator_to_array( $this->newSelectQueryBuilder()
			->select( [ 'log_id', 'log_action', 'log_params', 'actor_name' ] )
			->from( 'logging' )
			->join( 'actor', null, 'log_actor = actor_id' )
			->where( [ 'log_type' => $logType ] )
			->orderBy( 'log_id' )
			->caller( __METHOD__ )
			->fetchResultSet() );
	}

	public function testGetDeferredBlocks(): void {
		$userBlock = $this->placeDeferredBlock();

		$materializer = $this->getMaterializer();
		$this->assertSame(
			[ $userBlock->getId() ],
			array_map(
				static fn ( DatabaseBlock $block ) => $block->getId(),
				$materializer->getDeferredBlocks( $this->target )
			)
		);
	}

	public function testMaterialize(): void {
		ConvertibleTimestamp::setFakeTime( '20260101000000' );
		$block = $this->placeDeferredBlock( '1 week' );

		ConvertibleTimestamp::setFakeTime( '20260103000000' );
		$this->assertTrue( $this->getMaterializer()->materialize( $block ) );
		$this->assertFalse( $this->isDeferred( $block ) );
		$this->assertSame( '20260103000000', $block->getTimestamp() );
		$this->assertNotNull( $this->getServiceContainer()->getPermissionManager()->getApplicableBlock(
			'edit', $this->target, PermissionManager::RIGOR_FULL, Title::newFromText( 'Test page' ), null
		) );

		$logRows = $this->getLogRows( 'block' );
		$this->assertCount( 1, $logRows );
		$this->assertSame( 'block', $logRows[0]->log_action );
		// Logged as placed by the blocking sysop, not the target
		$this->assertSame( $this->sysop->getName(), $logRows[0]->actor_name );
		$params = LogEntryBase::extractParams( $logRows[0]->log_params );
		// The expiry is logged as an absolute time, one week after the block was placed
		$this->assertSame( wfTimestamp( TS::ISO_8601, '20260108000000' ), $params['5::duration'] );
		$this->assertSame( '', $params['6::flags'] );
		$this->assertSame( $block->getId(), $params['blockId'] );
	}

	public function testNotMaterializedWhenReadOnly(): void {
		$block = $this->placeDeferredBlock();
		$this->overrideConfigValue( MainConfigNames::ReadOnly, 'Testing' );

		$this->assertFalse( $this->getMaterializer()->materialize( $block ) );
		$this->assertTrue( $this->isDeferred( $block ) );
	}

	public function testNotMaterializedForIP(): void {
		$block = $this->placeDeferredBlock( 'infinity', [], [], '127.0.0.5' );

		$this->assertFalse( $this->getMaterializer()->materialize( $block ) );
		$this->assertTrue( $this->isDeferred( $block ) );
	}

	public function testLogDoesNotRevealOtherDeferredBlocks(): void {
		$page = $this->getExistingTestPage( 'Blocked page' );
		$partialBlock = $this->placeDeferredBlock( 'infinity', [ 'isPartial' => true ], [
			new PageRestriction( 0, $page->getId() )
		] );
		$sitewideBlock = $this->placeDeferredBlock( 'infinity', [], [], null, BlockUser::CONFLICT_NEW );
		$placementRows = $this->getLogRows( 'deferredblock' );
		$this->assertSame(
			2,
			LogEntryBase::extractParams( end( $placementRows )->log_params )['finalTargetCount']
		);

		// The partial block is still deferred, so the log entry must not count it
		$this->assertTrue( $this->getMaterializer()->materialize( $sitewideBlock ) );
		$logRows = $this->getLogRows( 'block' );
		$this->assertCount( 1, $logRows );
		$this->assertArrayNotHasKey(
			'finalTargetCount',
			LogEntryBase::extractParams( $logRows[0]->log_params )
		);

		// When the partial block materializes, both blocks are enforced
		$this->assertTrue( $this->getMaterializer()->materialize( $partialBlock ) );
		$logRows = $this->getLogRows( 'block' );
		$this->assertCount( 2, $logRows );
		$this->assertSame(
			2,
			LogEntryBase::extractParams( $logRows[1]->log_params )['finalTargetCount']
		);
	}

	public function testHiddenBlockMaterializesInSuppressionLog(): void {
		$block = $this->placeDeferredBlock( 'infinity', [ 'isHideUser' => true ] );
		$userFactory = $this->getServiceContainer()->getUserFactory();
		$this->assertFalse( $userFactory->newFromId( $this->target->getId() )->isHidden() );

		$this->assertTrue( $this->getMaterializer()->materialize( $block ) );
		$this->assertFalse( $this->isDeferred( $block ) );
		$this->assertTrue( $userFactory->newFromId( $this->target->getId() )->isHidden() );

		$this->assertSame( [], $this->getLogRows( 'block' ) );
		$suppressLogRows = $this->getLogRows( 'suppress' );
		// One entry for the placement and one for the materialization
		$this->assertCount( 2, $suppressLogRows );
		$params = LogEntryBase::extractParams( $suppressLogRows[1]->log_params );
		$this->assertSame( 'infinity', $params['5::duration'] );
		$this->assertSame( 'hiddenname', $params['6::flags'] );
	}

	public function testMaterializeOnlyOnce(): void {
		$block = $this->placeDeferredBlock();
		$materializer = $this->getMaterializer();

		$deferredBlocks = $materializer->getDeferredBlocks( $this->target );
		$this->assertCount( 1, $deferredBlocks );
		$staleCopy = clone $deferredBlocks[0];

		$this->assertTrue( $materializer->materialize( $deferredBlocks[0] ) );
		$this->assertFalse( $materializer->materialize( $staleCopy ) );
		$this->assertSame( [], $materializer->getDeferredBlocks( $this->target ) );
		$this->assertCount( 1, $this->getLogRows( 'block' ) );
		$this->assertFalse( $this->isDeferred( $block ) );
	}

	public function testNotMaterializedByPermissionChecks(): void {
		$block = $this->placeDeferredBlock();
		$title = Title::newFromText( 'Test page' );
		$permissionManager = $this->getServiceContainer()->getPermissionManager();

		$this->assertNull( $permissionManager->getApplicableBlock(
			'edit', $this->target, PermissionManager::RIGOR_SECURE, $title, null
		) );
		$this->assertStatusGood( $permissionManager->getPermissionStatus( 'edit', $this->target, $title ) );
		$this->assertTrue( $this->target->definitelyCan( 'edit', $title ) );
		$this->assertTrue( $this->target->isDefinitelyAllowed( 'edit' ) );
		$this->assertTrue( $this->target->authorizeRead( 'edit', $title ) );

		$this->assertTrue( $this->isDeferred( $block ) );
		$this->assertSame( [], $this->getLogRows( 'block' ) );
	}

	public function testMaterializedOnAuthorizeWrite(): void {
		$block = $this->placeDeferredBlock();

		$applicableBlock = $this->attemptEdit();
		$this->assertNotNull( $applicableBlock );
		$this->assertSame( $block->getId(), $applicableBlock->getId() );
		$this->assertFalse( $this->isDeferred( $block ) );
		$this->assertCount( 1, $this->getLogRows( 'block' ) );

		// Attempting again doesn't log again
		$this->attemptEdit();
		$this->assertCount( 1, $this->getLogRows( 'block' ) );
	}

	public function testMaterializedOnAuthorizeAction(): void {
		$block = $this->placeDeferredBlock();

		$this->assertFalse( $this->target->authorizeAction( 'upload' ) );
		$this->assertFalse( $this->isDeferred( $block ) );
	}

	public function testNotMaterializedForOtherUser(): void {
		$block = $this->placeDeferredBlock();
		RequestContext::getMain()->setUser( $this->sysop );

		$this->assertNull( $this->attemptEdit() );
		$this->assertTrue( $this->isDeferred( $block ) );
	}

	public static function provideRequestMethods(): array {
		return [
			'GET' => [ false ],
			'POST' => [ true ],
		];
	}

	/** @dataProvider provideRequestMethods */
	public function testMaterializedForRequestMethod( bool $wasPosted ): void {
		RequestContext::getMain()->setRequest( new FauxRequest( [], $wasPosted ) );
		$block = $this->placeDeferredBlock();

		$this->assertNotNull( $this->attemptEdit() );
		$this->assertFalse( $this->isDeferred( $block ) );
	}

	public function testNotMaterializedForActionItDoesNotBlock(): void {
		$page = $this->getExistingTestPage( 'Blocked page' );
		$block = $this->placeDeferredBlock( 'infinity', [ 'isPartial' => true ], [
			new PageRestriction( 0, $page->getId() )
		] );

		$this->assertNull( $this->attemptEdit( 'Other page' ) );
		$this->assertTrue( $this->isDeferred( $block ) );

		$this->assertNotNull( $this->attemptEdit( 'Blocked page' ) );
		$this->assertFalse( $this->isDeferred( $block ) );
	}
}
