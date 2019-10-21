<?php

use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\SystemBlock;
use MediaWiki\MediaWikiServices;

/**
 * @group Database
 * @group Blocking
 * @coversDefaultClass \MediaWiki\Block\CompositeBlock
 */
class CompositeBlockTest extends MediaWikiLangTestCase {
	private function getPartialBlocks() {
		$sysopId = $this->getTestSysop()->getUser()->getId();

		$userBlock = new DatabaseBlock( [
			'address' => $this->getTestUser()->getUser(),
			'by' => $sysopId,
			'sitewide' => false,
		] );
		$ipBlock = new DatabaseBlock( [
			'address' => '127.0.0.1',
			'by' => $sysopId,
			'sitewide' => false,
		] );

		$userBlock->insert();
		$ipBlock->insert();

		return [
			'user' => $userBlock,
			'ip' => $ipBlock,
		];
	}

	private function deleteBlocks( $blocks ) {
		foreach ( $blocks as $block ) {
			$block->delete();
		}
	}

	/**
	 * @covers ::__construct
	 * @dataProvider provideTestStrictestParametersApplied
	 */
	public function testStrictestParametersApplied( $blocks, $expected ) {
		$this->setMwGlobals( [
			'wgBlockDisablesLogin' => false,
			'wgBlockAllowsUTEdit' => true,
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

	/**
	 * @covers ::appliesToTitle
	 */
	public function testBlockAppliesToTitle() {
		$this->setMwGlobals( [
			'wgBlockDisablesLogin' => false,
		] );

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

	/**
	 * @covers ::appliesToUsertalk
	 * @covers ::appliesToPage
	 * @covers ::appliesToNamespace
	 */
	public function testBlockAppliesToUsertalk() {
		$this->setMwGlobals( [
			'wgBlockAllowsUTEdit' => true,
			'wgBlockDisablesLogin' => false,
		] );

		$blocks = $this->getPartialBlocks();

		$block = new CompositeBlock( [
			'originalBlocks' => $blocks,
		] );

		$title = $blocks[ 'user' ]->getTarget()->getTalkPage();
		$page = $this->getExistingTestPage( 'User talk:' . $title->getText() );

		$this->getBlockRestrictionStore()->insert( [
			new PageRestriction( $blocks[ 'user' ]->getId(), $page->getId() ),
			new NamespaceRestriction( $blocks[ 'ip' ]->getId(), NS_USER ),
		] );

		$this->assertTrue( $block->appliesToUsertalk( $blocks[ 'user' ]->getTarget()->getTalkPage() ) );

		$this->deleteBlocks( $blocks );
	}

	/**
	 * @covers ::appliesToRight
	 * @dataProvider provideTestBlockAppliesToRight
	 */
	public function testBlockAppliesToRight( $applies, $expected ) {
		$this->setMwGlobals( [
			'wgBlockDisablesLogin' => false,
		] );

		$block = new CompositeBlock( [
			'originalBlocks' => [
				$this->getMockBlockForTestAppliesToRight( $applies[ 0 ] ),
				$this->getMockBlockForTestAppliesToRight( $applies[ 1 ] ),
			],
		] );

		$this->assertSame( $block->appliesToRight( 'right' ), $expected );
	}

	private function getMockBlockForTestAppliesToRight( $applies ) {
		$mockBlock = $this->getMockBuilder( DatabaseBlock::class )
			->setMethods( [ 'appliesToRight' ] )
			->getMock();
		$mockBlock->method( 'appliesToRight' )
			->willReturn( $applies );
		return $mockBlock;
	}

	public function provideTestBlockAppliesToRight() {
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

	/**
	 * @covers ::getPermissionsError
	 * @dataProvider provideGetPermissionsError
	 */
	public function testGetPermissionsError( $ids, $expectedIdsMsg ) {
		// Some block options
		$timestamp = time();
		$target = '1.2.3.4';
		$byText = 'MediaWiki default';
		$formattedByText = "\u{202A}{$byText}\u{202C}";
		$reason = '';
		$expiry = 'infinite';

		$block = $this->getMockBuilder( CompositeBlock::class )
			->setMethods( [ 'getIds', 'getBlockErrorParams' ] )
			->getMock();
		$block->method( 'getIds' )
			->willReturn( $ids );
		$block->method( 'getBlockErrorParams' )
			->willReturn( [
				$formattedByText,
				$reason,
				$target,
				$formattedByText,
				null,
				$timestamp,
				$target,
				$expiry,
			] );

		$this->assertSame( [
			'blockedtext-composite',
			$formattedByText,
			$reason,
			$target,
			$formattedByText,
			$expectedIdsMsg,
			$timestamp,
			$target,
			$expiry,
		], $block->getPermissionsError( RequestContext::getMain() ) );
	}

	public static function provideGetPermissionsError() {
		return [
			'All original blocks are system blocks' => [
				[],
				'Your IP address appears in multiple blacklists',
			],
			'One original block is a database block' => [
				[ 100 ],
				'Relevant block IDs: #100 (your IP address may also be blacklisted)',
			],
			'Several original blocks are database blocks' => [
				[ 100, 101, 102 ],
				'Relevant block IDs: #100, #101, #102 (your IP address may also be blacklisted)',
			],
		];
	}

	/**
	 * Get an instance of BlockRestrictionStore
	 *
	 * @return BlockRestrictionStore
	 */
	protected function getBlockRestrictionStore() : BlockRestrictionStore {
		return MediaWikiServices::getInstance()->getBlockRestrictionStore();
	}
}
