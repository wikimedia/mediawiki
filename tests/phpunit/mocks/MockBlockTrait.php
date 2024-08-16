<?php
namespace MediaWiki\Tests\Unit;

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockManager;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * Creates and apply user blocks
 * @stable to use since 1.42
 */
trait MockBlockTrait {

	/**
	 * @param array $options Options as supported by AbstractBlock and
	 *        DatabaseBlock. In addition, the 'target' field may contain a
	 *        UserIdentity identifying the blocked user.
	 *
	 * @return AbstractBlock
	 */
	private function makeMockBlock( $options = [] ): AbstractBlock {
		if ( isset( $options['target'] ) ) {
			/** @var UserIdentity $user */
			$user = $options['target'];

			if ( !isset( $options['address'] ) ) {
				$options['address'] = $user->getName();
			}
			if ( !isset( $options['wiki'] ) ) {
				$options['wiki'] = $user->getWikiId();
			}
			unset( $options['target'] );
		}

		if ( !isset( $options['by'] ) ) {
			$options['by'] = UserIdentityValue::newRegistered( 45622, 'Blocker' );
		}

		$block = new DatabaseBlock( $options );
		return $block;
	}

	/**
	 * @param UserIdentity|AbstractBlock|array $block
	 *        The Block to apply, or the user to block.
	 *        If the block is given as an array,
	 *        it supports the fields specified by the constructors
	 *        of AbstractBlock and DatabaseBlock.
	 * @param UserIdentity|string|null $match The user to apply the
	 *        block for. Will be determined automatically if not given.
	 *
	 * @return BlockManager
	 */
	private function makeMockBlockManager( $block, $match = null ) {
		if ( is_array( $block ) ) {
			if ( !isset( $block['target'] ) && $match !== null ) {
				$target = $match instanceof UserIdentity ?
					new UserIdentityValue( 0, $match ) :
					$match;

				$block['target'] = $target;
			}

			$block = $this->makeMockBlock( $block );
		}

		if ( $block instanceof UserIdentity ) {
			$target = $block;
			$block = $this->makeMockBlock( [ 'target' => $target ] );
		}

		$match ??= $block->getTargetUserIdentity();

		$blockManager = $this->getMockBuilder( BlockManager::class )
			->disableOriginalConstructor()
			->onlyMethods( [
				'getUserBlock', 'getBlock', 'getCreateAccountBlock', 'getIpBlock', 'clearUserCache'
			] )
			->getMock();

		$callback = static function ( $user )
			use ( $match, $block )
		{
			if ( is_string( $user ) && $user === $match->getName() ) {
				return $block;
			} elseif ( $user->equals( $match ) ) {
				return $block;
			}

			return null;
		};

		$blockManager->method( 'getUserBlock' )
			->willReturnCallback( $callback );

		$blockManager->method( 'getBlock' )
			->willReturnCallback( $callback );

		$blockManager->method( 'getCreateAccountBlock' )
			->willReturnCallback( $callback );

		$blockManager->method( 'getIpBlock' )
			->willReturnCallback( $callback );

		return $blockManager;
	}

	/**
	 * @param UserIdentity|AbstractBlock|BlockManager|array $block
	 *        The BlockManager to install, the Block to apply, or
	 *        the user to block. If the block is given as an array,
	 *        it supports the fields specified by the constructors
	 *        of AbstractBlock and DatabaseBlock.
	 * @param UserIdentity|string|null $match The user to apply the
	 *        block for. Will be determined automatically if not given.
	 *
	 * @return BlockManager
	 */
	private function installMockBlockManager( $block, $match = null ): BlockManager {
		if ( $block instanceof BlockManager ) {
			$blockManager = $block;
		} else {
			$blockManager = $this->makeMockBlockManager( $block, $match );
		}

		$this->setService( 'BlockManager', $blockManager );
		return $blockManager;
	}

}
