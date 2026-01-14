<?php

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Config\Config;
use MediaWiki\MainConfigNames;
use Wikimedia\IPUtils;

/**
 * Handle ranges for contributions special pages and APIs.
 *
 * @since 1.44
 */
trait ContributionsRangeTrait {
	/**
	 * Check whether the given IP is a range and within the contributions CIDR limit.
	 *
	 * @param string $target
	 * @param Config $config
	 * @return bool
	 */
	protected function isQueryableRange( string $target, Config $config ): bool {
		if ( !IPUtils::isValidRange( $target ) ) {
			return false;
		}

		// IPs in the format `X.X.X.X - Y.Y.Y.Y` are considered valid by IPUtils
		// but don't have a CIDR we can get from the IP reliably.
		// Contributions pages don't accept this as a valid input, so just return false.
		$ipParts = explode( '/', $target, 2 );
		if ( count( $ipParts ) < 2 ) {
			return false;
		}
		[ $ip, $range ] = $ipParts;

		$CIDRLimit = $this->getQueryableRangeLimit( $config );
		return (
			( IPUtils::isIPv4( $ip ) && $range >= $CIDRLimit['IPv4'] ) ||
			( IPUtils::isIPv6( $ip ) && $range >= $CIDRLimit['IPv6'] )
		);
	}

	/**
	 * Check whether the given target is either a valid IP address or a valid range within the
	 * contributions CIDR limit.
	 *
	 * @param string $target
	 * @param Config $config
	 * @return bool
	 */
	protected function isValidIPOrQueryableRange( string $target, Config $config ): bool {
		return IPUtils::isValid( $target ) ||
			$this->isQueryableRange( $target, $config );
	}

	/**
	 * @param Config $config
	 * @return int[]
	 */
	protected function getQueryableRangeLimit( Config $config ): array {
		return $config->get( MainConfigNames::RangeContributionsCIDRLimit );
	}
}
