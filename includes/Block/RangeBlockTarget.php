<?php

namespace MediaWiki\Block;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use StatusValue;
use Wikimedia\IPUtils;

/**
 * A block target for an IP address range
 *
 * @since 1.44
 */
class RangeBlockTarget extends BlockTarget implements BlockTargetWithIp {
	private string $cidr;

	/**
	 * @var array The minimum prefix lengths indexed by protocol (IPv4 or IPv6)
	 */
	private array $limits;

	/**
	 * @param string $cidr The range specification in CIDR notation
	 * @param array $limits The minimum prefix lengths indexed by protocol (IPv4 or IPv6)
	 * @param string|false $wikiId The wiki ID
	 */
	public function __construct( string $cidr, array $limits, string|false $wikiId = WikiAwareEntity::LOCAL ) {
		parent::__construct( $wikiId );
		$this->cidr = $cidr;
		$this->limits = $limits;
	}

	public function toString(): string {
		return $this->cidr;
	}

	public function getType(): int {
		return Block::TYPE_RANGE;
	}

	public function getLogPage(): PageReference {
		return new PageReferenceValue( NS_USER, $this->cidr, $this->wikiId );
	}

	/** @inheritDoc */
	public function getSpecificity() {
		// This is the number of bits that are allowed to vary in the block, give
		// or take some floating point errors
		[ $ip, $bits ] = explode( '/', $this->cidr, 2 );
		$max = IPUtils::isIPv6( $ip ) ? 128 : 32;
		$size = $max - (int)$bits;

		// Rank a range block covering a single IP equally with a single-IP block
		return 2 + ( $size / $max );
	}

	public function validateForCreation(): StatusValue {
		$status = StatusValue::newGood();
		[ $ip, $prefixLength ] = explode( '/', $this->cidr, 2 );

		if ( IPUtils::isIPv4( $ip ) ) {
			$minLength = $this->limits['IPv4'];
			$totalLength = 32;
		} elseif ( IPUtils::isIPv6( $ip ) ) {
			$minLength = $this->limits['IPv6'];
			$totalLength = 128;
		} else {
			// The factory should not have called the constructor with an invalid range
			throw new \RuntimeException( 'invalid IP range' );
		}

		if ( $minLength == $totalLength ) {
			// Range block effectively disabled
			$status->fatal( 'range_block_disabled' );
		} elseif ( $prefixLength > $totalLength ) {
			// Such a range cannot exist
			$status->fatal( 'ip_range_invalid' );
		} elseif ( $prefixLength < $minLength ) {
			$status->fatal( 'ip_range_toolarge', $minLength );
		}

		return $status;
	}

	/** @inheritDoc */
	public function toHexRange() {
		$range = IPUtils::parseRange( $this->cidr );
		if ( count( $range ) !== 2 || !is_string( $range[0] ) || !is_string( $range[1] ) ) {
			throw new \RuntimeException(
				'Failed to parse range: constructor caller should have validated it' );
		}
		return $range;
	}

	/**
	 * Get the start of the range in hexadecimal form.
	 *
	 * @return string
	 */
	public function getHexRangeStart(): string {
		return $this->toHexRange()[0];
	}

	/**
	 * Get the end of the range in hexadecimal form.
	 *
	 * @return string
	 */
	public function getHexRangeEnd(): string {
		return $this->toHexRange()[1];
	}

	/** @inheritDoc */
	protected function getLegacyUnion() {
		return $this->cidr;
	}

}
