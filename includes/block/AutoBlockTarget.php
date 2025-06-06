<?php

namespace MediaWiki\Block;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use StatusValue;

/**
 * A block target of the form #1234 where the number is the block ID. For user
 * input or display when the IP address needs to be hidden.
 *
 * @since 1.44
 */
class AutoBlockTarget extends BlockTarget {
	private int $id;

	/**
	 * @param int $id The block ID
	 * @param string|false $wikiId
	 */
	public function __construct( int $id, string|false $wikiId = WikiAwareEntity::LOCAL ) {
		parent::__construct( $wikiId );
		$this->id = $id;
	}

	public function toString(): string {
		return '#' . $this->id;
	}

	public function getType(): int {
		return Block::TYPE_AUTO;
	}

	public function getLogPage(): PageReference {
		return new PageReferenceValue( NS_USER, $this->toString(), $this->wikiId );
	}

	/** @inheritDoc */
	public function getSpecificity() {
		return 2;
	}

	public function validateForCreation(): StatusValue {
		// Autoblocks are never valid for creation
		return StatusValue::newFatal( 'badipaddress' );
	}

	/**
	 * Get the block ID
	 *
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/** @inheritDoc */
	protected function getLegacyUnion() {
		return (string)$this->id;
	}
}
