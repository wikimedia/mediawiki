<?php

namespace MediaWiki\Block;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use StatusValue;
use Wikimedia\IPUtils;

/**
 * A block target for a single IP address with an associated user page
 *
 * @since 1.44
 */
class AnonIpBlockTarget extends BlockTarget implements BlockTargetWithUserPage, BlockTargetWithIp {
	private string $addr;

	/**
	 * @param string $addr
	 * @param string|false $wikiId
	 */
	public function __construct( string $addr, $wikiId = WikiAwareEntity::LOCAL ) {
		parent::__construct( $wikiId );
		$this->addr = $addr;
	}

	public function toString(): string {
		return $this->addr;
	}

	public function getType(): int {
		return Block::TYPE_IP;
	}

	/** @inheritDoc */
	public function getSpecificity() {
		return 2;
	}

	public function getLogPage(): PageReference {
		return $this->getUserPage();
	}

	public function getUserPage(): PageReference {
		return new PageReferenceValue( NS_USER, $this->addr, $this->wikiId );
	}

	public function getUserIdentity(): UserIdentity {
		return new UserIdentityValue( 0, $this->addr, $this->wikiId );
	}

	public function validateForCreation(): StatusValue {
		return StatusValue::newGood();
	}

	/**
	 * Get the IP address in hexadecimal form
	 *
	 * @return string
	 */
	public function toHex(): string {
		return IPUtils::toHex( $this->addr );
	}

	/**
	 * Get the IP address as a hex "range" tuple, with the start and end equal
	 *
	 * @return string[]
	 */
	public function toHexRange(): array {
		$hex = $this->toHex();
		return [ $hex, $hex ];
	}

	/** @inheritDoc */
	protected function getLegacyUnion() {
		return $this->getUserIdentity();
	}
}
