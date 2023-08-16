<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MainConfigNames;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\CompositeBlock
 */
class CompositeBlockTest extends MediaWikiLangTestCase {
	private function getPartialBlocks() {
		$sysopUser = $this->getTestSysop()->getUser();

		$userBlock = new DatabaseBlock( [
			'address' => $this->getTestUser()->getUser(),
			'by' => $sysopUser,
			'sitewide' => false,
		] );
		$ipBlock = new DatabaseBlock( [
			'address' => '127.0.0.1',
			'by' => $sysopUser,
			'sitewide' => false,
		] );

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$blockStore->insertBlock( $userBlock );
		$blockStore->insertBlock( $ipBlock );

		return [
			'user' => $userBlock,
			'ip' => $ipBlock,
		];
	}

	private function deleteBlocks( $blocks ) {
		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		foreach ( $blocks as $block ) {
			$blockStore->deleteBlock( $block );
		}
	}

	/**
	 * @dataProvider provideTestStrictestParametersApplied
	 */
	public function testStrictestParametersApplied( $blocks, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::BlockDisablesLogin => false,
			MainConfigNames::BlockAllowsUTEdit => true,
		] );

		$block = new CompositeBlock( [
			'originalBlocks' => $blocks,
		] );

		$this->assertSame( $expected[ 'hideName' ], $block->getHideName() );
		$this->assertSame( $expected[ 'sitewide' ], $block->isSitewide() );
		$this->assertSame( $expected[ 'blockEmail' ], $block->isEmailBlocked() );
		$this->assertSame( $expected[ 'allowUsertalk' ], $block->isUsertalkEditAllowed() );
	}

	public static function provideTestStrictestParametersApplied() {
		return [
			'Sitewide block and partial block' => [
				[
					new DatabaseBlock( [
						'sitewide' => false,
						'blockEmail' => true,
						'allowUsertalk' => true,
					] ),
					new DatabaseBlock( [
						'sitewide' => true,
						'blockEmail' => false,
						'allowUsertalk' => false,
					] ),
				],
				[
					'hideName' => false,
					'sitewide' => true,
					'blockEmail' => true,
					'allowUsertalk' => false,
				],
			],
			'Partial block and system block' => [
				[
					new DatabaseBlock( [
						'sitewide' => false,
						'blockEmail' => true,
						'allowUsertalk' => false,
					] ),
					new SystemBlock( [
						'systemBlock' => 'proxy',
					] ),
				],
				[
					'hideName' => false,
					'sitewide' => true,
					'blockEmail' => true,
					'allowUsertalk' => false,
				],
			],
			'System block and user name hiding block' => [
				[
					new DatabaseBlock( [
						'hideName' => true,
						'sitewide' => true,
						'blockEmail' => true,
						'allowUsertalk' => false,
					] ),
					new SystemBlock( [
						'systemBlock' => 'proxy',
					] ),
				],
				[
					'hideName' => true,
					'sitewide' => true,
					'blockEmail' => true,
					'allowUsertalk' => false,
				],
			],
			'Two lenient partial blocks' => [
				[
					new DatabaseBlock( [
						'sitewide' => false,
						'blockEmail' => false,
						'allowUsertalk' => true,
					] ),
					new DatabaseBlock( [
						'sitewide' => false,
						'blockEmail' => false,
						'allowUsertalk' => true,
					] ),
				],
				[
					'hideName' => false,
					'sitewide' => false,
					'blockEmail' => false,
					'allowUsertalk' => true,
				],
			],
		];
	}

	public function testBlockAppliesToTitle() {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );

		$blocks = $this->getPartialBlocks();

		$block = new CompositeBlock( [
			'originalBlocks' => $blocks,
		] );

		$pageFoo = $this->getExistingTestPage( 'Foo' );
		$pageBar = $this->getExistingTestPage( 'User:Bar' );

		$this->getBlockRestrictionStore()->insert( [
			new PageRestriction( $blocks[ 'user' ]->getId(), $pageFoo->getId() ),
			new NamespaceRestriction( $blocks[ 'ip' ]->getId(), NS_USER ),
		] );

		$this->assertTrue( $block->appliesToTitle( $pageFoo->getTitle() ) );
		$this->assertTrue( $block->appliesToTitle( $pageBar->getTitle() ) );

		$this->deleteBlocks( $blocks );
	}

	public function testBlockAppliesToUsertalk() {
		$this->overrideConfigValues( [
			MainConfigNames::BlockAllowsUTEdit => true,
			MainConfigNames::BlockDisablesLogin => false,
		] );

		$blocks = $this->getPartialBlocks();

		$block = new CompositeBlock( [
			'originalBlocks' => $blocks,
		] );

		$userFactory = $this->getServiceContainer()->getUserFactory();
		$targetIdentity = $userFactory->newFromUserIdentity( $blocks[ 'user' ]->getTargetUserIdentity() );
		$title = $targetIdentity->getTalkPage();
		$page = $this->getExistingTestPage( 'User talk:' . $title->getText() );

		$this->getBlockRestrictionStore()->insert( [
			new PageRestriction( $blocks[ 'user' ]->getId(), $page->getId() ),
			new NamespaceRestriction( $blocks[ 'ip' ]->getId(), NS_USER ),
		] );

		$this->assertTrue( $block->appliesToUsertalk( $title ) );

		$this->deleteBlocks( $blocks );
	}

	/**
	 * @dataProvider provideTestBlockAppliesToRight
	 */
	public function testBlockAppliesToRight( $applies, $expected ) {
		$this->overrideConfigValue( MainConfigNames::BlockDisablesLogin, false );

		$block = new CompositeBlock( [
			'originalBlocks' => [
				$this->getMockBlockForTestAppliesToRight( $applies[ 0 ] ),
				$this->getMockBlockForTestAppliesToRight( $applies[ 1 ] ),
			],
		] );

		$this->assertSame( $expected, $block->appliesToRight( 'right' ) );
	}

	private function getMockBlockForTestAppliesToRight( $applies ) {
		$mockBlock = $this->getMockBuilder( DatabaseBlock::class )
			->onlyMethods( [ 'appliesToRight' ] )
			->getMock();
		$mockBlock->method( 'appliesToRight' )
			->willReturn( $applies );
		return $mockBlock;
	}

	public static function provideTestBlockAppliesToRight() {
		return [
			'Block does not apply if no original blocks apply' => [
				[ false, false ],
				false,
			],
			'Block applies if any original block applies (second block doesn\'t apply)' => [
				[ true, false ],
				true,
			],
			'Block applies if any original block applies (second block unsure)' => [
				[ true, null ],
				true,
			],
			'Block is unsure if all original blocks are unsure' => [
				[ null, null ],
				null,
			],
			'Block is unsure if any original block is unsure, and no others apply' => [
				[ null, false ],
				null,
			],
		];
	}

	public function testTimestamp() {
		$timestamp = 20000101000000;

		$firstBlock = $this->createMock( DatabaseBlock::class );
		$firstBlock->method( 'getTimestamp' )
			->willReturn( (string)$timestamp );

		$secondBlock = $this->createMock( DatabaseBlock::class );
		$secondBlock->method( 'getTimestamp' )
			->willReturn( (string)( $timestamp + 10 ) );

		$thirdBlock = $this->createMock( DatabaseBlock::class );
		$thirdBlock->method( 'getTimestamp' )
			->willReturn( (string)( $timestamp + 100 ) );

		$block = new CompositeBlock( [
			'originalBlocks' => [ $thirdBlock, $firstBlock, $secondBlock ],
		] );
		$this->assertSame( (string)$timestamp, $block->getTimestamp() );
	}

	/**
	 * Get an instance of BlockRestrictionStore
	 *
	 * @return BlockRestrictionStore
	 */
	protected function getBlockRestrictionStore(): BlockRestrictionStore {
		return $this->getServiceContainer()->getBlockRestrictionStore();
	}
}
