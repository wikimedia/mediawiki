<?php
/**
 * Abstract block restriction.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block\Restriction;

abstract class AbstractRestriction implements Restriction {

	/**
	 * String constant identifying the type of restriction. Expected to be overridden in subclasses
	 * with a non-empty string value.
	 */
	public const TYPE = '';

	/**
	 * Numeric type identifier. Expected to be overridden in subclasses with a non-zero integer
	 * number. Must not exceed 127 to fit into a TINYINT database field.
	 */
	public const TYPE_ID = 0;

	/**
	 * @var int
	 */
	protected $blockId;

	/**
	 * @var int
	 */
	protected $value;

	/**
	 * Create Restriction.
	 *
	 * @since 1.33
	 * @param int $blockId
	 * @param int $value
	 */
	public function __construct( $blockId, $value ) {
		$this->blockId = (int)$blockId;
		$this->value = (int)$value;
	}

	/**
	 * @inheritDoc
	 */
	public static function getType() {
		return static::TYPE;
	}

	/**
	 * @inheritDoc
	 */
	public static function getTypeId() {
		return static::TYPE_ID;
	}

	/**
	 * @inheritDoc
	 */
	public function getBlockId() {
		return $this->blockId;
	}

	/**
	 * @inheritDoc
	 */
	public function setBlockId( $blockId ) {
		$this->blockId = (int)$blockId;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @inheritDoc
	 */
	public static function newFromRow( \stdClass $row ) {
		// @phan-suppress-next-line PhanTypeInstantiateAbstractStatic
		return new static( $row->ir_ipb_id, $row->ir_value );
	}

	/**
	 * @inheritDoc
	 */
	public function toRow() {
		return [
			'ir_ipb_id' => $this->getBlockId(),
			'ir_type' => $this->getTypeId(),
			'ir_value' => $this->getValue(),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function equals( Restriction $other ) {
		return $this->getHash() === $other->getHash();
	}

	/**
	 * @inheritDoc
	 */
	public function getHash() {
		return $this->getType() . '-' . $this->getValue();
	}
}
