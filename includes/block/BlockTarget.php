<?php

namespace MediaWiki\Block;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\DAO\WikiAwareEntityTrait;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use StatusValue;
use Stringable;

/**
 * Base class for block targets
 *
 * @since 1.44
 */
abstract class BlockTarget implements WikiAwareEntity, Stringable {
	use WikiAwareEntityTrait;

	protected string|false $wikiId;

	/**
	 * @param string|false $wikiId UserIdentity and Block extend WikiAwareEntity
	 *   and so we must ask for a wiki ID as well, to forward it through, even
	 *   though we don't use it.
	 */
	protected function __construct( string|false $wikiId ) {
		$this->wikiId = $wikiId;
	}

	/** @inheritDoc */
	public function getWikiId() {
		return $this->wikiId;
	}

	public function __toString() {
		return $this->toString();
	}

	/**
	 * Compare this object with another one
	 *
	 * @param BlockTarget|null $other
	 * @return bool
	 */
	public function equals( ?BlockTarget $other ) {
		return $other !== null
			&& static::class === get_class( $other )
			&& $this->toString() === $other->toString();
	}

	/**
	 * Get the username, the IP address, range, or autoblock ID prefixed with
	 * a "#". Such a string will round-trip through BlockTarget::newFromString(),
	 * giving back the same target.
	 *
	 * @return string
	 */
	abstract public function toString(): string;

	/**
	 * Get one of the Block::TYPE_xxx constants associated with this target
	 * @return int
	 */
	abstract public function getType(): int;

	/**
	 * Get the title to be used when logging an action on this block. For an
	 * autoblock, the title is technically invalid, with a hash character in
	 * the DB key. For a range block, the title is valid but is not a user
	 * page for a specific user.
	 *
	 * See also getUserPage(), which exists only for subclasses which relate to
	 * a specific user with a talk page.
	 *
	 * @return PageReference
	 */
	abstract public function getLogPage(): PageReference;

	/**
	 * Get the score of this block for purposes of choosing a more specific
	 * block, where lower is more specific.
	 *
	 *  - 1: user block
	 *  - 2: single IP block
	 *  - 2-3: range block scaled according to the size of the range
	 *
	 * @return float|int
	 */
	abstract public function getSpecificity();

	/**
	 * Get the target and type tuple conventionally returned by
	 * BlockUtils::parseBlockTarget()
	 *
	 * @return array
	 */
	public function getLegacyTuple(): array {
		return [ $this->getLegacyUnion(), $this->getType() ];
	}

	/**
	 * Check the target data against more stringent requirements imposed when
	 * a block is created from user input. This is in addition to the loose
	 * validation done by BlockTargetFactory::newFromString().
	 *
	 * @return StatusValue
	 */
	abstract public function validateForCreation(): StatusValue;

	/**
	 * Get the first part of the legacy tuple.
	 *
	 * @return UserIdentity|string
	 */
	abstract protected function getLegacyUnion();
}
