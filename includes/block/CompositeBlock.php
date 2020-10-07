<?php
/**
 * Class for blocks composed from multiple blocks.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Block;

use Title;

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

		$this->setHideName( $this->propHasValue( 'mHideName', true ) );
		$this->isSitewide( $this->propHasValue( 'isSitewide', true ) );
		$this->isEmailBlocked( $this->propHasValue( 'mBlockEmail', true ) );
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
	 * @inheritDoc
	 */
	public function getExpiry() {
		$maxExpiry = null;
		foreach ( $this->originalBlocks as $block ) {
			$expiry = $block->getExpiry();
			if ( $maxExpiry === null || $expiry === '' || $expiry > $maxExpiry ) {
				$maxExpiry = $expiry;
			}
		}
		return $maxExpiry;
	}

	/**
	 * @inheritDoc
	 */
	public function getIdentifier() {
		$identifier = [];
		foreach ( $this->originalBlocks as $block ) {
			$identifier[] = $block->getIdentifier();
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
	public function appliesToUsertalk( Title $usertalk = null ) {
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
	public function getBy() {
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function getByName() {
		return '';
	}
}
