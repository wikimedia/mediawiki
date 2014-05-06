<?php

/**
 * Class IPSet: match addresses against a fixed whitelist
 * of CIDR ranges and/or individual IPs.
 *
 * Usage:
 *   # At startup, calculate the optimized data structure for the set:
 *   $ipset = new IPSet( $wgSquidServersNoPurge );
 *   # runtime check (returns bool):
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
 *     'next' => array (
 *         0 => 1,
 *         1 => array (
 *             0 => 0,
 *             1 => 1,
 *         ),
 *     ),
 * );
 *
 * (multi-byte compression nodes were attempted as well, but were
 * a net loss in my test scenarios due to additional match complexity)
 *
 * XXX still missing/TODO:
 *   Input validation on set config
 *   Input validation on match()
 *   inet_ntop() error-checking?
 *   unit tests?
 *   (has been manually tested in current form against current/proposed wmf prod cfg)
 *
 *********
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

class IPSet {
	# the initial arrays indicates terminal match-fail for all addresses
	private $v4_root = array ( 0, 0 );
	private $v6_root = array ( 0, 0 );

	private function add_cidr( $cidr ) {
		# v4 or v6 check
		if(strpos($cidr, ':') === false) {
			$node =& $this->v4_root;
			$def_mask = "32";
		}
		else {
			$node =& $this->v6_root;
			$def_mask = "128";
		}

		# Default to all-1's mask if no netmask in the input
		if(strpos($cidr, '/') === false) {
			$net = $cidr;
			$mask = $def_mask;
		}
		else {
			list($net, $mask) = explode('/', $cidr);
		}

		# special-case: zero mask overwrites the whole tree with a pair of terminal match-true's
		if($mask == 0) {
			$node = array(1, 1);
			return;
		}

		# convert "$net" to an array of integer bytes, length 4 or 16:
		$raw = inet_pton($net);
		$raw_ord = array_map("ord", str_split($raw));
		$curbit = 0;
		while(1) {
			# figure out whether our next branch is $node[0] or $node[1] based
			#  on the raw IP data and the current bit number
			$mask_shift = 7 - ($curbit & 7);
			$node =& $node[($raw_ord[$curbit++ >> 3] & (1 << $mask_shift)) >> $mask_shift];
			if($node == 1) {
				# already added a larger supernet, no need to go deeper
				return;
			}
			else {
				if($curbit == $mask) {
					# this may wipe out deeper subnets from earlier
					$node = 1;
					return;
				}
				else if($node == 0) {
					# create new subarray to go deeper
					$node = array( 0, 0 );
				}
			}
		}
	}

	public function match( $ip ) {
		$raw = inet_pton($ip);
		$raw_ord = array_map("ord", str_split($raw));
		if(count($raw_ord) == 4) {
			$node =& $this->v4_root;
		}
		else {
			$node =& $this->v6_root;
		}
		$curbit = 0;
		while(1) {
			if(isSet($node{'comp'})) {
				# compressed node, matches 1 whole byte on a byte boundary
				if($raw_ord[$curbit >> 3] != $node{'comp'}) {
					return false;
				}
				$curbit += 8;
				$node =& $node{'next'};
			}
			else {
				# uncompressed node, walk in the correct direction for the current bit-value
				$mask_shift = 7 - ($curbit & 7);
				$node =& $node[($raw_ord[$curbit++ >> 3] & (1 << $mask_shift)) >> $mask_shift];
			}

			if($node == 1) {
				return true;
			}
			else if($node == 0) {
				return false;
			}
		}
	}

	# This recursively merges adjacent nets into larger supernets, e.g.:
	#      8.0.0.0/8 + 9.0.0.0/8 -> 8.0.0.0/7
	private static function rec_optimize( &$node ) {
		if(is_array($node[0]) && IPSet::rec_optimize($node[0])) {
			$node[0] = 1;
		}
		if(is_array($node[1]) && IPSet::rec_optimize($node[1])) {
			$node[1] = 1;
		}
		if($node[0] == 1 && $node[1] == 1) {
			return 1;
		}
		return 0;
	}

	# This is a very simplistic compression scheme: if we go through a whole
	# byte of address starting at a byte boundary with no real branching
	# other than immediate false-vs-(node|true), compress that subtree down to a single
	# byte-matching node.
	# The $max_comp_start check elides recursing the final 7 levels of depth (family-dependent)
	private static function rec_compress( &$node, $cur_bitdepth, $max_comp_start ) {
		if(!($cur_bitdepth & 7)) { # byte boundary, check for depth-8 single path(s)
			$any_success = false;
			while(1) {
				$byte = 0;
				$cnode =& $node;
				$success = true;
				for($i = 0; $i < 8; $i++) {
					if($cnode[0] == 0 && ($cnode[1] == 1 || is_array($cnode[1]))) { # 1-bit
						$byte |= (1 << (7 - $i));
						$cnode =& $cnode[1];
					}
					else if($cnode[1] == 0 && ($cnode[0] == 1 || is_array($cnode[0]))) { # 0-bit
						$cnode =& $cnode[0];
					}
					else { # partial-byte branching, give up
						$success = false;
						break;
					}
				}
				if($success) {
					$any_success = true;
					$node = array(
						'comp' => $byte,
						'next' => &$cnode,
					);
					$node =& $node{'next'};
					$cur_bitdepth += 8;
				}
				else {
					break;
				}
			}
			if($any_success) {
				$cur_bitdepth--; # for inc below
			}
		}

		$cur_bitdepth++;
		if($cur_bitdepth <= $max_comp_start) {
			if(is_array($node[0])) {
				IPSet::rec_compress($node[0], $cur_bitdepth, $max_comp_start);
			}
			if(is_array($node[1])) {
				IPSet::rec_compress($node[1], $cur_bitdepth, $max_comp_start);
			}
		}
	}

	public function IPSet( $cfg ) {
		foreach ($cfg as $cidr) {
			$this->add_cidr($cidr);
		}
		IPSet::rec_optimize($this->v4_root);
		IPSet::rec_compress($this->v4_root, 0, 24);
		IPSet::rec_optimize($this->v6_root);
		IPSet::rec_compress($this->v6_root, 0, 120);
	}
}
