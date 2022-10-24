<?php
/**
 * Class for blocks composed from multiple blocks.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use InvalidArgumentException;
use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;

/**
 * Multiple Block class.
 *
 * Multiple blocks exist to enforce restrictions from more than one block, if several
 * blocks apply to a user/IP. Multiple blocks are created temporarily on enforcement.
 *
 * @since 1.34
 */
class CompositeBlock extends AbstractBlock {
	/** @var AbstractBlock[] */
	private $originalBlocks;

	/**
	 * Helper method for merging multiple blocks into a composite block.
	 * @param AbstractBlock ...$blocks
	 * @return self
	 */
	public static function createFromBlocks( AbstractBlock ...$blocks ): self {
		$originalBlocks = [];
		foreach ( $blocks as $block ) {
			if ( $block instanceof self ) {
				$originalBlocks = array_merge( $originalBlocks, $block->getOriginalBlocks() );
			} else {
				$originalBlocks[] = $block;
			}
		}
		if ( !$originalBlocks ) {
			throw new InvalidArgumentException( 'No blocks given' );
		}
		return new self( [
			'target' => $originalBlocks[0]->target,
			'reason' => new Message( 'blockedtext-composite-reason' ),
			'originalBlocks' => $originalBlocks,
		] );
	}

	/**
	 * Create a new block with specified parameters on a user, IP or IP range.
	 *
	 * @param array $options Parameters of the block, with options supported by
	 *  `AbstractBlock::__construct`, and also:
	 *  - originalBlocks: (Block[]) Blocks that this block is composed from
	 */
	public function __construct( array $options = [] ) {
		parent::__construct( $options );

		$defaults = [
			'originalBlocks' => [],
		];

		$options += $defaults;

		$this->originalBlocks = $options[ 'originalBlocks' ];

		$this->setHideName( $this->propHasValue( 'hideName', true ) );
		$this->isHardblock( $this->propHasValue( 'isHardblock', true ) );
		$this->isSitewide( $this->propHasValue( 'isSitewide', true ) );
		$this->isEmailBlocked( $this->propHasValue( 'blockEmail', true ) );
		$this->isCreateAccountBlocked( $this->propHasValue( 'blockCreateAccount', true ) );
		$this->isUsertalkEditAllowed( !$this->propHasValue( 'allowUsertalk', false ) );
	}

	/**
	 * Determine whether any original blocks have a particular property set to a
	 * particular value.
	 *
	 * @param string $prop
	 * @param mixed $value
	 * @return bool At least one block has the property set to the value
	 */
	private function propHasValue( $prop, $value ) {
		foreach ( $this->originalBlocks as $block ) {
			if ( $block->$prop == $value ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Determine whether any original blocks have a particular method returning a
	 * particular value.
	 *
	 * @param string $method
	 * @param mixed $value
	 * @param mixed ...$params
	 * @return bool At least one block has the method returning the value
	 */
	private function methodReturnsValue( $method, $value, ...$params ) {
		foreach ( $this->originalBlocks as $block ) {
			if ( $block->$method( ...$params ) == $value ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the original blocks from which this block is composed
	 *
	 * @since 1.34
	 * @return AbstractBlock[]
	 */
	public function getOriginalBlocks() {
		return $this->originalBlocks;
	}

	/**
	 * Create a clone of the object with the original blocks array set to
	 * something else.
	 *
	 * @since 1.42
	 * @param AbstractBlock[] $blocks
	 * @return self
	 */
	public function withOriginalBlocks( array $blocks ) {
		$clone = clone $this;
		$clone->originalBlocks = $blocks;
		return $clone;
	}

	public function toArray(): array {
		return $this->originalBlocks;
	}

	/**
	 * @inheritDoc
	 */
	public function getTimestamp(): string {
		$minStart = null;
		foreach ( $this->originalBlocks as $block ) {
			$startTime = $block->getTimestamp();
			if ( $minStart === null || $startTime === '' || $startTime < $minStart ) {
				$minStart = $startTime;
			}
		}
		return $minStart ?? '';
	}

	/**
	 * @inheritDoc
	 */
	public function getExpiry(): string {
		$maxExpiry = null;
		foreach ( $this->originalBlocks as $block ) {
			$expiry = $block->getExpiry();
			if ( $maxExpiry === null || $expiry === '' || $expiry > $maxExpiry ) {
				$maxExpiry = $expiry;
			}
		}
		return $maxExpiry ?? '';
	}

	/**
	 * @inheritDoc
	 */
	public function getIdentifier( $wikiId = self::LOCAL ) {
		$identifier = [];
		foreach ( $this->originalBlocks as $block ) {
			$identifier[] = $block->getIdentifier( $wikiId );
		}
		return $identifier;
	}

	/**
	 * @inheritDoc
	 *
	 * Determines whether the CompositeBlock applies to a right by checking
	 * whether the original blocks apply to that right. Each block can report
	 * true (applies), false (does not apply) or null (unsure). Then:
	 * - If any original blocks apply, this block applies
	 * - If no original blocks apply but any are unsure, this block is unsure
	 * - If all blocks do not apply, this block does not apply
	 */
	public function appliesToRight( $right ) {
		$isUnsure = false;

		foreach ( $this->originalBlocks as $block ) {
			$appliesToRight = $block->appliesToRight( $right );

			if ( $appliesToRight ) {
				return true;
			} elseif ( $appliesToRight === null ) {
				$isUnsure = true;
			}
		}

		return $isUnsure ? null : false;
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToUsertalk( ?Title $usertalk = null ): bool {
		return $this->methodReturnsValue( __FUNCTION__, true, $usertalk );
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToTitle( Title $title ) {
		return $this->methodReturnsValue( __FUNCTION__, true, $title );
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToNamespace( $ns ) {
		return $this->methodReturnsValue( __FUNCTION__, true, $ns );
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToPage( $pageId ) {
		return $this->methodReturnsValue( __FUNCTION__, true, $pageId );
	}

	/**
	 * @inheritDoc
	 */
	public function appliesToPasswordReset() {
		return $this->methodReturnsValue( __FUNCTION__, true );
	}

	/**
	 * @inheritDoc
	 */
	public function getBy( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getByName() {
		return '';
	}

	/**
	 * @inheritDoc
	 */
	public function getBlocker(): ?UserIdentity {
		return null;
	}
}
