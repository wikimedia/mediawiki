<?php
/**
 * Copyright 2014, 2015 Brandon Black <blblack@gmail.com>
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
 * @author Brandon Black <blblack@gmail.com>
 */
namespace IPSet;

/**
 * Matches IP addresses against a set of CIDR specifications
 *
 * Usage:
 *
 *     // At startup, calculate the optimized data structure for the set:
 *     $ipset = new IPSet( array(
 *         '208.80.154.0/26',
 *         '2620:0:861:1::/64',
 *         '10.64.0.0/22',
 *     ) );
 *
 *     // Runtime check against cached set (returns bool):
 *     $allowme = $ipset->match( $ip );
 *
 * In rough benchmarking, this takes about 80% more time than
 * in_array() checks on a short (a couple hundred at most) array
 * of addresses.  It's fast either way at those levels, though,
 * and IPSet would scale better than in_array if the array were
 * much larger.
 *
 * For mixed-family CIDR sets, however, this code gives well over
 * 100x speedup vs iterating IP::isInRange() over an array
 * of CIDR specs.
 *
 * The basic implementation is two separate binary trees
 * (IPv4 and IPv6) as nested php arrays with keys named 0 and 1.
 * The values false and true are terminal match-fail and match-success,
 * otherwise the value is a deeper node in the tree.
 *
 * A simple depth-compression scheme is also implemented: whole-byte
 * tree compression at whole-byte boundaries only, where no branching
 * occurs during that whole byte of depth.  A compressed node has
 * keys 'comp' (the byte to compare) and 'next' (the next node to
 * recurse into if 'comp' matched successfully).
 *
 * For example, given these inputs:
 *
 *     25.0.0.0/9
 *     25.192.0.0/10
 *
 * The v4 tree would look like:
 *
 *     root4 => array(
 *         'comp' => 25,
 *         'next' => array(
 *             0 => true,
 *             1 => array(
 *                 0 => false,
 *                 1 => true,
 *             ),
 *         ),
 *     );
 *
 * (multi-byte compression nodes were attempted as well, but were
 * a net loss in my test scenarios due to additional match complexity)
 */
class IPSet {
	/** @var array $root4 The root of the IPv4 matching tree */
	private $root4 = array( false, false );

	/** @var array $root6 The root of the IPv6 matching tree */
	private $root6 = array( false, false );

	/**
	 * Instantiate the object from an array of CIDR specs
	 *
	 * Invalid input network/mask values in $cfg will result in issuing
	 * E_WARNING and/or E_USER_WARNING and the bad values being ignored.
	 *
	 * @param array $cfg Array of IPv[46] CIDR specs as strings
	 */
	public function __construct( array $cfg ) {
		foreach ( $cfg as $cidr ) {
			$this->addCidr( $cidr );
		}

		self::recOptimize( $this->root4 );
		self::recCompress( $this->root4, 0, 24 );
		self::recOptimize( $this->root6 );
		self::recCompress( $this->root6, 0, 120 );
	}

	/**
	 * Add a single CIDR spec to the internal matching trees
	 *
	 * @param string $cidr String CIDR spec, IPv[46], optional /mask (def all-1's)
	 */
	private function addCidr( $cidr ) {
		// v4 or v6 check
		if ( strpos( $cidr, ':' ) === false ) {
			$node =& $this->root4;
			$defMask = '32';
		} else {
			$node =& $this->root6;
			$defMask = '128';
		}

		// Default to all-1's mask if no netmask in the input
		if ( strpos( $cidr, '/' ) === false ) {
			$net = $cidr;
			$mask = $defMask;
		} else {
			list( $net, $mask ) = explode( '/', $cidr, 2 );
			if ( !ctype_digit( $mask ) || intval( $mask ) > $defMask ) {
				trigger_error( "IPSet: Bad mask '$mask' from '$cidr', ignored", E_USER_WARNING );
				return;
			}
		}
		$mask = intval( $mask ); // explicit integer convert, checked above

		// convert $net to an array of integer bytes, length 4 or 16:
		$raw = inet_pton( $net );
		if ( $raw === false ) {
			return; // inet_pton() sends an E_WARNING for us
		}
		$rawOrd = array_map( 'ord', str_split( $raw ) );

		// special-case: zero mask overwrites the whole tree with a pair of terminal successes
		if ( $mask == 0 ) {
			$node = array( true, true );
			return;
		}

		// iterate the bits of the address while walking the tree structure for inserts
		$curBit = 0;
		while ( 1 ) {
			$maskShift = 7 - ( $curBit & 7 );
			$node =& $node[( $rawOrd[$curBit >> 3] & ( 1 << $maskShift ) ) >> $maskShift];
			++$curBit;
			if ( $node === true ) {
				// already added a larger supernet, no need to go deeper
				return;
			} elseif ( $curBit == $mask ) {
				// this may wipe out deeper subnets from earlier
				$node = true;
				return;
			} elseif ( $node === false ) {
				// create new subarray to go deeper
				$node = array( false, false );
			}
		}
	}

	/**
	 * Match an IP address against the set
	 *
	 * If $ip is unparseable, inet_pton may issue an E_WARNING to that effect
	 *
	 * @param string $ip string IPv[46] address
	 * @return bool True is match success, false is match failure
	 */
	public function match( $ip ) {
		$raw = inet_pton( $ip );
		if ( $raw === false ) {
			return false; // inet_pton() sends an E_WARNING for us
		}

		$rawOrd = array_map( 'ord', str_split( $raw ) );
		if ( count( $rawOrd ) == 4 ) {
			$node =& $this->root4;
		} else {
			$node =& $this->root6;
		}

		$curBit = 0;
		while ( 1 ) {
			if ( isset( $node['comp'] ) ) {
				// compressed node, matches 1 whole byte on a byte boundary
				if ( $rawOrd[$curBit >> 3] != $node['comp'] ) {
					return false;
				}
				$curBit += 8;
				$node =& $node['next'];
			} else {
				// uncompressed node, walk in the correct direction for the current bit-value
				$maskShift = 7 - ( $curBit & 7 );
				$node =& $node[( $rawOrd[$curBit >> 3] & ( 1 << $maskShift ) ) >> $maskShift];
				++$curBit;
			}

			if ( $node === true || $node === false ) {
				return $node;
			}
		}
	}

	/**
	 * Recursively merges adjacent nets into larger supernets
	 *
	 * @param array &$node Tree node to optimize, by-reference
	 *
	 *  e.g.: 8.0.0.0/8 + 9.0.0.0/8 -> 8.0.0.0/7
	 */
	private static function recOptimize( &$node ) {
		if ( $node[0] !== false && $node[0] !== true && self::recOptimize( $node[0] ) ) {
			$node[0] = true;
		}
		if ( $node[1] !== false && $node[1] !== true && self::recOptimize( $node[1] ) ) {
			$node[1] = true;
		}
		if ( $node[0] === true && $node[1] === true ) {
			return true;
		}
		return false;
	}

	/**
	 * Recursively compresses a tree
	 *
	 * @param array &$node Tree node to compress, by-reference
	 * @param integer $curBit current depth in the tree
	 * @param integer $maxCompStart maximum depth at which compression can start, family-specific
	 *
	 * This is a very simplistic compression scheme: if we go through a whole
	 * byte of address starting at a byte boundary with no real branching
	 * other than immediate false-vs-(node|true), compress that subtree down to a single
	 * byte-matching node.
	 * The $maxCompStart check elides recursing the final 7 levels of depth (family-dependent)
	 */
	private static function recCompress( &$node, $curBit, $maxCompStart ) {
		if ( !( $curBit & 7 ) ) { // byte boundary, check for depth-8 single path(s)
			$byte = 0;
			$cnode =& $node;
			$i = 8;
			while ( $i-- ) {
				if ( $cnode[0] === false ) {
					$byte |= 1 << $i;
					$cnode =& $cnode[1];
				} elseif ( $cnode[1] === false ) {
					$cnode =& $cnode[0];
				} else {
					// partial-byte branching, give up
					break;
				}
			}
			if ( $i == -1 ) { // means we did not exit the while() via break
				$node = array(
					'comp' => $byte,
					'next' => &$cnode,
				);
				$curBit += 8;
				if ( $cnode !== true ) {
					self::recCompress( $cnode, $curBit, $maxCompStart );
				}
				return;
			}
		}

		++$curBit;
		if ( $curBit <= $maxCompStart ) {
			if ( $node[0] !== false && $node[0] !== true ) {
				self::recCompress( $node[0], $curBit, $maxCompStart );
			}
			if ( $node[1] !== false && $node[1] !== true ) {
				self::recCompress( $node[1], $curBit, $maxCompStart );
			}
		}
	}
}
