<?php
/**
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

use Wikimedia\IPUtils;

/**
 * An IPv4 address is made of 4 bytes from x00 to xFF which is d0 to d255
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IP_BYTE instead
 */
define( 'RE_IP_BYTE', IPUtils::RE_IP_BYTE );
/**
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IP_ADD instead
 */
define( 'RE_IP_ADD', IPUtils::RE_IP_ADD );
/**
 * An IPv4 range is an IP address and a prefix (d1 to d32)
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IP_PREFIX instead
 */
define( 'RE_IP_PREFIX', IPUtils::RE_IP_PREFIX );
/**
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IP_RANGE instead
 */
define( 'RE_IP_RANGE', IPUtils::RE_IP_RANGE );
/**
 * An IPv6 address is made up of 8 words (each x0000 to xFFFF).
 * However, the "::" abbreviation can be used on consecutive x0000 words.
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_WORD instead
 */
define( 'RE_IPV6_WORD', IPUtils::RE_IPV6_WORD );
/**
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_PREFIX instead
 */
define( 'RE_IPV6_PREFIX', IPUtils::RE_IPV6_PREFIX );
/**
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_ADD instead
 */
define( 'RE_IPV6_ADD', IPUtils::RE_IPV6_ADD );
/**
 * An IPv6 range is an IP address and a prefix (d1 to d128)
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_RANGE instead
 */
define( 'RE_IPV6_RANGE', IPUtils::RE_IPV6_RANGE );
/**
 * For IPv6 canonicalization (NOT for strict validation; these are quite lax!)
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_GAP instead
 */
define( 'RE_IPV6_GAP', IPUtils::RE_IPV6_GAP );
/**
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IPV6_V4_PREFIX instead
 */
define( 'RE_IPV6_V4_PREFIX', IPUtils::RE_IPV6_V4_PREFIX );
/**
 * This might be useful for regexps used elsewhere, matches any IPv4 or IPv6 address or network
 * @deprecated since 1.35 use Wikimedia\IPUtils::RE_IP_ADDRESS_STRING instead
 */
define( 'IP_ADDRESS_STRING', IPUtils::RE_IP_ADDRESS_STRING );

/**
 * Pre-librarized class name for IPUtils
 *
 * @deprecated since 1.35
 */
class IP extends IPUtils {
	// Direct wrapper.
}
