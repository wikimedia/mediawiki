<?php
/**
 * @section LICENSE
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

/**
 * XXX this should be set elsewhere and/or be done more intelligently
 * Our tree-optimizer/compressor functions can recurse up to ~128 levels
 * in the worst case on certain IPv6 inputs, but xdebug defaults to
 * a limit of 100, and that's *including* all the outer-scope functions
 * before we enter our local recursion here.
 */
ini_set( 'xdebug.max_nesting_level', 200 );

/**
 * Matches IP addresses against a set of CIDR specifications
 *
 * Usage:
 *   // At startup, calculate the optimized data structure for the set:
 *   $ipset = new IPSet( $wgSquidServersNoPurge );
 *   // runtime check against cached set (returns bool):
 *   $allowme = $ipset->match( $ip );
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
 * The values 0 and 1 are terminal match-fail and match-success,
 * otherwise the value is a deeper node in the tree.
 *
 * A simple depth-compression scheme is also implemented: whole-byte
 * tree compression at whole-byte boundaries only, where no branching
 * occurs during that whole byte of depth.  A compressed node has
 * keys 'comp' (the byte to compare) and 'next' (the next node to
 * recurse into if 'comp' matched successfully).
 *
 * For example, given these inputs:
 * 25.0.0.0/9
 * 25.192.0.0/10
 *
 * The v4 tree would look like:
 * v4_root => array(
 *     'comp' => 25,
 *     'next' => array(
 *         0 => 1,
 *         1 => array(
 *             0 => 0,
 *             1 => 1,
 *         ),
 *     ),
 * );
 *
 * (multi-byte compression nodes were attempted as well, but were
 * a net loss in my test scenarios due to additional match complexity)
 *
 * @since 1.23
 */
class IPSet {
	/** @var array $v4_root: the root of the IPv4 matching tree */
	private $v4_root = array( 0, 0 );

	/** @var array $v6_root: the root of the IPv6 matching tree */
	private $v6_root = array( 0, 0 );

	/**
	 * __construct() instantiate the object from an array of CIDR specs
	 *
	 * @param array $cfg array of IPv[46] CIDR specs as strings
	 * @return IPSet new IPSet object
	 */
	public function __construct( $cfg ) {
		foreach ( $cfg as $cidr ) {
			$this->addCidr( $cidr );
		}
		IPSet::recOptimize( $this->v4_root );
		IPSet::recCompress( $this->v4_root, 0, 24 );
		IPSet::recOptimize( $this->v6_root );
		IPSet::recCompress( $this->v6_root, 0, 120 );
	}

	/**
	 * Add a single CIDR spec to the internal matching trees
	 *
	 * @param string $cidr string CIDR spec, IPv[46], optional /mask (def all-1's)
	 */
	private function addCidr( $cidr ) {
		// v4 or v6 check
		if ( strpos( $cidr, ':' ) === false ) {
			$node =& $this->v4_root;
			$def_mask = "32";
		} else {
			$node =& $this->v6_root;
			$def_mask = "128";
		}

		// Default to all-1's mask if no netmask in the input
		if ( strpos( $cidr, '/' ) === false ) {
			$net = $cidr;
			$mask = $def_mask;
		} else {
			list( $net, $mask ) = explode( '/', $cidr, 2 );
			if ( !ctype_digit( $mask ) || intval( $mask ) > $def_mask ) {
				trigger_error( "IPSet: Bad mask '$mask' from '$cidr', ignored", E_USER_WARNING );
				return;
			}
		}
		$mask = intval( $mask ); // explicit integer convert, checked above

		// convert "$net" to an array of integer bytes, length 4 or 16:
		$raw = inet_pton( $net );
		if ( $raw === false ) {
			return; // inet_pton() sends an E_WARNING for us
		}
		$raw_ord = array_map( "ord", str_split( $raw ) );

		// special-case: zero mask overwrites the whole tree with a pair of terminal match-true's
		if ( $mask == 0 ) {
			$node = array( 1, 1 );
			return;
		}

		$curbit = 0;
		while ( 1 ) {
			# figure out whether our next branch is $node[0] or $node[1] based
			#  on the raw IP data and the current bit number
			$mask_shift = 7 - ( $curbit & 7 );
			$node =& $node[( $raw_ord[$curbit++ >> 3] & ( 1 << $mask_shift ) ) >> $mask_shift];
			if ( $node === 1 ) {
				// already added a larger supernet, no need to go deeper
				return;
			} else {
				if ( $curbit == $mask ) {
					// this may wipe out deeper subnets from earlier
					$node = 1;
					return;
				}
				elseif ( $node === 0 ) {
					// create new subarray to go deeper
					$node = array( 0, 0 );
				}
			}
		}
	}

	/**
	 * Match an IP address against the set
	 *
	 * @param string $ip string IPv[46] address
	 * @return boolean true is match success, false is match failure
	 */
	public function match( $ip ) {
		$raw = inet_pton( $ip );
		if ( $raw === false ) {
			return false; // inet_pton() sends an E_WARNING for us
		}

		$raw_ord = array_map( "ord", str_split( $raw ) );
		if ( count( $raw_ord ) == 4 ) {
			$node =& $this->v4_root;
		} else {
			$node =& $this->v6_root;
		}
		$curbit = 0;
		while ( 1 ) {
			if ( isset( $node { 'comp' } ) ) {
				// compressed node, matches 1 whole byte on a byte boundary
				if ( $raw_ord[$curbit >> 3] != $node { 'comp' } ) {
					return false;
				}
				$curbit += 8;
				$node =& $node { 'next' } ;
			} else {
				// uncompressed node, walk in the correct direction for the current bit-value
				$mask_shift = 7 - ( $curbit & 7 );
				$node =& $node[( $raw_ord[$curbit++ >> 3] & ( 1 << $mask_shift ) ) >> $mask_shift];
			}

			if ( $node === 1 ) {
				return true;
			}
			elseif ( $node === 0 ) {
				return false;
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
		if ( is_array( $node[0] ) && IPSet::recOptimize( $node[0] ) ) {
			$node[0] = 1;
		}
		if ( is_array( $node[1] ) && IPSet::recOptimize( $node[1] ) ) {
			$node[1] = 1;
		}
		if ( $node[0] === 1 && $node[1] === 1 ) {
			return 1;
		}
		return 0;
	}

	/**
	 * Recursively compresses a tree
	 *
	 * @param array &$node Tree node to compress, by-reference
	 * @param integer $cur_bitdepth current depth in the tree
	 * @param integer $max_comp_start maximum depth at which compression can start, family-specific
	 *
	 * This is a very simplistic compression scheme: if we go through a whole
	 * byte of address starting at a byte boundary with no real branching
	 * other than immediate false-vs-(node|true), compress that subtree down to a single
	 * byte-matching node.
	 * The $max_comp_start check elides recursing the final 7 levels of depth (family-dependent)
	 */
	private static function recCompress( &$node, $cur_bitdepth, $max_comp_start ) {
		if ( !( $cur_bitdepth & 7 ) ) { # byte boundary, check for depth-8 single path(s)
			$byte = 0;
			$cnode =& $node;
			$success = true;
			for ( $i = 0; $i < 8; $i++ ) {
				if ( $cnode[0] === 0 && $cnode[1] !== 0 ) { // 1-bit
					$byte |= ( 1 << ( 7 - $i ) );
					$cnode =& $cnode[1];
				} elseif ( $cnode[1] === 0 && $cnode[0] !== 1 ) { // 0-bit
					$cnode =& $cnode[0];
				} else { // partial-byte branching, give up
					$success = false;
					break;
				}
			}
			if ( $success ) {
				$node = array(
					'comp' => $byte,
					'next' => &$cnode,
				);
				$cur_bitdepth += 8;
				IPSet::recCompress( $node { 'next' } , $cur_bitdepth, $max_comp_start );
				return;
			}
		}

		$cur_bitdepth++;
		if ( $cur_bitdepth <= $max_comp_start ) {
			if ( is_array( $node[0] ) ) {
				IPSet::recCompress( $node[0], $cur_bitdepth, $max_comp_start );
			}
			if ( is_array( $node[1] ) ) {
				IPSet::recCompress( $node[1], $cur_bitdepth, $max_comp_start );
			}
		}
	}
}
