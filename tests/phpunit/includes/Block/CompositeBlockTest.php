<?php

namespace MediaWiki\Tests\Block;

use MediaWiki\Block\AnonIpBlockTarget;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MainConfigNames;
use MediaWikiLangTestCase;

/**
 * @group Database
 * @group Blocking
 * @covers \MediaWiki\Block\CompositeBlock
 * @covers \MediaWiki\Block\AbstractBlock
 */
class CompositeBlockTest extends MediaWikiLangTestCase {
	private function getPartialBlocks() {
		$sysopUser = $this->getTestSysop()->getUser();

		$blockStore = $this->getServiceContainer()->getDatabaseBlockStore();
		$userBlock = $blockStore->insertBlockWithParams( [
			'targetUser' => $this->getTestUser()->getUser(),
			'by' => $sysopUser,
			'sitewide' => false,
		] );
		$ipBlock = $blockStore->insertBlockWithParams( [
			'address' => '127.0.0.1',
			'by' => $sysopUser,
			'sitewide' => false,
		] );

		return [
			'user' => $userBlock,
			'ip' => $ipBlock,
		];
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

	public function testCreateFromBlocks() {
		$block1 = new SystemBlock( [
			'target' => new AnonIpBlockTarget( '127.0.0.1' ),
			'systemBlock' => 'test1',
		] );
		$block2 = new SystemBlock( [
			'target' => new AnonIpBlockTarget( '127.0.0.1' ),
			'systemBlock' => 'test2',
		] );
		$block3 = new SystemBlock( [
			'target' => new AnonIpBlockTarget( '127.0.0.1' ),
			'systemBlock' => 'test3',
		] );

		$compositeBlock = CompositeBlock::createFromBlocks( $block1, $block2 );
		$this->assertInstanceOf( CompositeBlock::class, $compositeBlock );
		$this->assertCount( 2, $compositeBlock->getOriginalBlocks() );
		[ $actualBlock1, $actualBlock2 ] = $compositeBlock->getOriginalBlocks();
		$this->assertSame( $block1->getSystemBlockType(), $actualBlock1->getSystemBlockType() );
		$this->assertSame( $block2->getSystemBlockType(), $actualBlock2->getSystemBlockType() );
		$this->assertSame( 'blockedtext-composite-reason',
			$compositeBlock->getReasonComment()->message->getKey() );
		$this->assertSame( '127.0.0.1', $compositeBlock->getTargetName() );

		$compositeBlock2 = CompositeBlock::createFromBlocks( $compositeBlock, $block3 );
		$this->assertCount( 3, $compositeBlock2->getOriginalBlocks() );
		[ $actualBlock1, $actualBlock2, $actualBlock3 ] = $compositeBlock2->getOriginalBlocks();
		$this->assertSame( $block1->getSystemBlockType(), $actualBlock1->getSystemBlockType() );
		$this->assertSame( $block2->getSystemBlockType(), $actualBlock2->getSystemBlockType() );
		$this->assertSame( $block3->getSystemBlockType(), $actualBlock3->getSystemBlockType() );
	}

	protected function getBlockRestrictionStore(): BlockRestrictionStore {
		return $this->getServiceContainer()->getBlockRestrictionStore();
	}
}
