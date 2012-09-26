<?php
/**
 * New version of the difference engine
 *
 * Copyright © 2008 Guy Van den Broeck <guy@guyvdb.eu>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup DifferenceEngine
 */

/**
 * This diff implementation is mainly lifted from the LCS algorithm of the Eclipse project which
 * in turn is based on Myers' "An O(ND) difference algorithm and its variations"
 * (http://citeseer.ist.psu.edu/myers86ond.html) with range compression (see Wu et al.'s
 * "An O(NP) Sequence Comparison Algorithm").
 *
 * This implementation supports an upper bound on the execution time.
 *
 * Complexity: O((M + N)D) worst case time, O(M + N + D^2) expected time, O(M + N) space
 *
 * @author Guy Van den Broeck
 * @ingroup DifferenceEngine
 */
class WikiDiff3 {

	// Input variables
	private $from;
	private $to;
	private $m;
	private $n;

	private $tooLong;
	private $powLimit;

	// State variables
	private $maxDifferences;
	private $lcsLengthCorrectedForHeuristic = false;

	// Output variables
	public $length;
	public $removed;
	public $added;
	public $heuristicUsed;

	function __construct( $tooLong = 2000000, $powLimit = 1.45 ) {
		$this->tooLong = $tooLong;
		$this->powLimit = $powLimit;
	}

	public function diff( /*array*/ $from, /*array*/ $to ) {
		// remember initial lengths
		$m = count( $from );
		$n = count( $to );

		$this->heuristicUsed = false;

		// output
		$removed = $m > 0 ? array_fill( 0, $m, true ) : array();
		$added = $n > 0 ? array_fill( 0, $n, true ) : array();

		// reduce the complexity for the next step (intentionally done twice)
		// remove common tokens at the start
		$i = 0;
		while ( $i < $m && $i < $n && $from[$i] === $to[$i] ) {
			$removed[$i] = $added[$i] = false;
			unset( $from[$i], $to[$i] );
			++$i;
		}

		// remove common tokens at the end
		$j = 1;
		while ( $i + $j <= $m && $i + $j <= $n && $from[$m - $j] === $to[$n - $j] ) {
			$removed[$m - $j] = $added[$n - $j] = false;
			unset( $from[$m - $j], $to[$n - $j] );
			++$j;
		}

		$this->from = $newFromIndex = $this->to = $newToIndex = array();

		// remove tokens not in both sequences
		$shared = array();
		foreach ( $from as $key ) {
			$shared[$key] = false;
		}

		foreach ( $to as $index => &$el ) {
			if ( array_key_exists( $el, $shared ) ) {
				// keep it
				$this->to[] = $el;
				$shared[$el] = true;
				$newToIndex[] = $index;
			}
		}
		foreach ( $from as $index => &$el ) {
			if ( $shared[$el] ) {
				// keep it
				$this->from[] = $el;
				$newFromIndex[] = $index;
			}
		}

		unset( $shared, $from, $to );

		$this->m = count( $this->from );
		$this->n = count( $this->to );

		$this->removed = $this->m > 0 ? array_fill( 0, $this->m, true ) : array();
		$this->added = $this->n > 0 ? array_fill( 0, $this->n, true ) : array();

		if ( $this->m == 0 || $this->n == 0 ) {
			$this->length = 0;
		} else {
			$this->maxDifferences = ceil( ( $this->m + $this->n ) / 2.0 );
			if ( $this->m * $this->n > $this->tooLong ) {
				// limit complexity to D^POW_LIMIT for long sequences
				$this->maxDifferences = floor( pow( $this->maxDifferences, $this->powLimit - 1.0 ) );
				wfDebug( "Limiting max number of differences to $this->maxDifferences\n" );
			}

			/*
			 * The common prefixes and suffixes are always part of some LCS, include
			 * them now to reduce our search space
			 */
			$max = min( $this->m, $this->n );
			for ( $forwardBound = 0; $forwardBound < $max
					&& $this->from[$forwardBound] === $this->to[$forwardBound];
					++$forwardBound ) {
				$this->removed[$forwardBound] = $this->added[$forwardBound] = false;
			}

			$backBoundL1 = $this->m - 1;
			$backBoundL2 = $this->n - 1;

			while ( $backBoundL1 >= $forwardBound && $backBoundL2 >= $forwardBound
					&& $this->from[$backBoundL1] === $this->to[$backBoundL2] ) {
				$this->removed[$backBoundL1--] = $this->added[$backBoundL2--] = false;
			}

			$temp = array_fill( 0, $this->m + $this->n + 1, 0 );
			$V = array( $temp, $temp );
			$snake = array( 0, 0, 0 );

			$this->length = $forwardBound + $this->m - $backBoundL1 - 1
				+ $this->lcs_rec( $forwardBound, $backBoundL1,
				$forwardBound, $backBoundL2, $V, $snake );
		}

		$this->m = $m;
		$this->n = $n;

		$this->length += $i + $j - 1;

		foreach ( $this->removed as $key => &$removed_elem ) {
			if ( !$removed_elem ) {
				$removed[$newFromIndex[$key]] = false;
			}
		}
		foreach ( $this->added as $key => &$added_elem ) {
			if ( !$added_elem ) {
				$added[$newToIndex[$key]] = false;
			}
		}
		$this->removed = $removed;
		$this->added = $added;
	}

	function diff_range( $from_lines, $to_lines ) {
		// Diff and store locally
		$this->diff( $from_lines, $to_lines );
		unset( $from_lines, $to_lines );

		$ranges = array();
		$xi = $yi = 0;
		while ( $xi < $this->m || $yi < $this->n ) {
			// Matching "snake".
			while ( $xi < $this->m && $yi < $this->n
					&& !$this->removed[$xi]
					&& !$this->added[$yi] ) {
				++$xi;
				++$yi;
			}
			// Find deletes & adds.
			$xstart = $xi;
			while ( $xi < $this->m && $this->removed[$xi] ) {
				++$xi;
			}

			$ystart = $yi;
			while ( $yi < $this->n && $this->added[$yi] ) {
				++$yi;
			}

			if ( $xi > $xstart || $yi > $ystart ) {
				$ranges[] = new RangeDifference( $xstart, $xi,
								$ystart, $yi );
			}
		}
		return $ranges;
	}

	private function lcs_rec( $bottoml1, $topl1, $bottoml2, $topl2, &$V, &$snake ) {
		// check that both sequences are non-empty
		if ( $bottoml1 > $topl1 || $bottoml2 > $topl2 ) {
			return 0;
		}

		$d = $this->find_middle_snake( $bottoml1, $topl1, $bottoml2,
							$topl2, $V, $snake );

		// need to store these so we don't lose them when they're
		// overwritten by the recursion
		$len = $snake[2];
		$startx = $snake[0];
		$starty = $snake[1];

		// the middle snake is part of the LCS, store it
		for ( $i = 0; $i < $len; ++$i ) {
			$this->removed[$startx + $i] = $this->added[$starty + $i] = false;
		}

		if ( $d > 1 ) {
			return $len
			+ $this->lcs_rec( $bottoml1, $startx - 1, $bottoml2,
							$starty - 1, $V, $snake )
			+ $this->lcs_rec( $startx + $len, $topl1, $starty + $len,
							$topl2, $V, $snake );
		} elseif ( $d == 1 ) {
			/*
			 * In this case the sequences differ by exactly 1 line. We have
			 * already saved all the lines after the difference in the for loop
			 * above, now we need to save all the lines before the difference.
			 */
			$max = min( $startx - $bottoml1, $starty - $bottoml2 );
			for ( $i = 0; $i < $max; ++$i ) {
				$this->removed[$bottoml1 + $i] =
					$this->added[$bottoml2 + $i] = false;
			}
			return $max + $len;
		}
		return $len;
	}

	private function find_middle_snake( $bottoml1, $topl1, $bottoml2, $topl2, &$V, &$snake ) {
		$from = &$this->from;
		$to = &$this->to;
		$V0 = &$V[0];
		$V1 = &$V[1];
		$snake0 = &$snake[0];
		$snake1 = &$snake[1];
		$snake2 = &$snake[2];
		$bottoml1_min_1 = $bottoml1 -1;
		$bottoml2_min_1 = $bottoml2 -1;
		$N = $topl1 - $bottoml1_min_1;
		$M = $topl2 - $bottoml2_min_1;
		$delta = $N - $M;
		$maxabsx = $N + $bottoml1;
		$maxabsy = $M + $bottoml2;
		$limit = min( $this->maxDifferences, ceil( ( $N + $M ) / 2 ) );

		// value_to_add_forward: a 0 or 1 that we add to the start
		// offset to make it odd/even
		if ( ( $M & 1 ) == 1 ) {
			$value_to_add_forward = 1;
		} else {
			$value_to_add_forward = 0;
		}

		if ( ( $N & 1 ) == 1 ) {
			$value_to_add_backward = 1;
		} else {
			$value_to_add_backward = 0;
		}

		$start_forward = -$M;
		$end_forward = $N;
		$start_backward = -$N;
		$end_backward = $M;

		$limit_min_1 = $limit - 1;
		$limit_plus_1 = $limit + 1;

		$V0[$limit_plus_1] = 0;
		$V1[$limit_min_1] = $N;
		$limit = min( $this->maxDifferences, ceil( ( $N + $M ) / 2 ) );

		if ( ( $delta & 1 ) == 1 ) {
			for ( $d = 0; $d <= $limit; ++$d ) {
				$start_diag = max( $value_to_add_forward + $start_forward, -$d );
				$end_diag = min( $end_forward, $d );
				$value_to_add_forward = 1 - $value_to_add_forward;

				// compute forward furthest reaching paths
				for ( $k = $start_diag; $k <= $end_diag; $k += 2 ) {
					if ( $k == -$d || ( $k < $d
							&& $V0[$limit_min_1 + $k] < $V0[$limit_plus_1 + $k] ) ) {
						$x = $V0[$limit_plus_1 + $k];
					} else {
						$x = $V0[$limit_min_1 + $k] + 1;
					}

					$absx = $snake0 = $x + $bottoml1;
					$absy = $snake1 = $x - $k + $bottoml2;

					while ( $absx < $maxabsx && $absy < $maxabsy && $from[$absx] === $to[$absy] ) {
						++$absx;
						++$absy;
					}
					$x = $absx -$bottoml1;

					$snake2 = $absx -$snake0;
					$V0[$limit + $k] = $x;
					if ( $k >= $delta - $d + 1 && $k <= $delta + $d - 1
							&& $x >= $V1[$limit + $k - $delta] ) {
						return 2 * $d - 1;
					}

					// check to see if we can cut down the diagonal range
					if ( $x >= $N && $end_forward > $k - 1 ) {
						$end_forward = $k - 1;
					} elseif ( $absy - $bottoml2 >= $M ) {
						$start_forward = $k + 1;
						$value_to_add_forward = 0;
					}
				}

				$start_diag = max( $value_to_add_backward + $start_backward, -$d );
				$end_diag = min( $end_backward, $d );
				$value_to_add_backward = 1 - $value_to_add_backward;

				// compute backward furthest reaching paths
				for ( $k = $start_diag; $k <= $end_diag; $k += 2 ) {
					if ( $k == $d
					|| ( $k != -$d && $V1[$limit_min_1 + $k] < $V1[$limit_plus_1 + $k] ) ) {
						$x = $V1[$limit_min_1 + $k];
					} else {
						$x = $V1[$limit_plus_1 + $k] - 1;
					}

					$y = $x - $k - $delta;

					$snake2 = 0;
					while ( $x > 0 && $y > 0
					&& $from[$x + $bottoml1_min_1] === $to[$y + $bottoml2_min_1] ) {
						--$x;
						--$y;
						++$snake2;
					}
					$V1[$limit + $k] = $x;

					// check to see if we can cut down our diagonal range
					if ( $x <= 0 ) {
						$start_backward = $k + 1;
						$value_to_add_backward = 0;
					} elseif ( $y <= 0 && $end_backward > $k - 1 ) {
						$end_backward = $k - 1;
					}
				}
			}
		} else {
			for ( $d = 0; $d <= $limit; ++$d ) {
				$start_diag = max( $value_to_add_forward + $start_forward, -$d );
				$end_diag = min( $end_forward, $d );
				$value_to_add_forward = 1 - $value_to_add_forward;

				// compute forward furthest reaching paths
				for ( $k = $start_diag; $k <= $end_diag; $k += 2 ) {
					if ( $k == -$d
					|| ( $k < $d && $V0[$limit_min_1 + $k] < $V0[$limit_plus_1 + $k] ) ) {
						$x = $V0[$limit_plus_1 + $k];
					} else {
						$x = $V0[$limit_min_1 + $k] + 1;
					}

					$absx = $snake0 = $x + $bottoml1;
					$absy = $snake1 = $x - $k + $bottoml2;

					while ( $absx < $maxabsx && $absy < $maxabsy && $from[$absx] === $to[$absy] ) {
						++$absx;
						++$absy;
					}
					$x = $absx -$bottoml1;
					$snake2 = $absx -$snake0;
					$V0[$limit + $k] = $x;

					// check to see if we can cut down the diagonal range
					if ( $x >= $N && $end_forward > $k - 1 ) {
						$end_forward = $k - 1;
					} elseif ( $absy -$bottoml2 >= $M ) {
						$start_forward = $k + 1;
						$value_to_add_forward = 0;
					}
				}

				$start_diag = max( $value_to_add_backward + $start_backward, -$d );
				$end_diag = min( $end_backward, $d );
				$value_to_add_backward = 1 - $value_to_add_backward;

				// compute backward furthest reaching paths
				for ( $k = $start_diag; $k <= $end_diag; $k += 2 ) {
					if ( $k == $d
					|| ( $k != -$d && $V1[$limit_min_1 + $k] < $V1[$limit_plus_1 + $k] ) ) {
						$x = $V1[$limit_min_1 + $k];
					} else {
						$x = $V1[$limit_plus_1 + $k] - 1;
					}

					$y = $x - $k - $delta;

					$snake2 = 0;
					while ( $x > 0 && $y > 0
							&& $from[$x + $bottoml1_min_1] === $to[$y + $bottoml2_min_1] ) {
						--$x;
						--$y;
						++$snake2;
					}
					$V1[$limit + $k] = $x;

					if ( $k >= -$delta - $d && $k <= $d - $delta
							&& $x <= $V0[$limit + $k + $delta] ) {
						$snake0 = $bottoml1 + $x;
						$snake1 = $bottoml2 + $y;
						return 2 * $d;
					}

					// check to see if we can cut down our diagonal range
					if ( $x <= 0 ) {
						$start_backward = $k + 1;
						$value_to_add_backward = 0;
					} elseif ( $y <= 0 && $end_backward > $k - 1 ) {
						$end_backward = $k - 1;
					}
				}
			}
		}
		/*
		 * computing the true LCS is too expensive, instead find the diagonal
		 * with the most progress and pretend a midle snake of length 0 occurs
		 * there.
		 */

		$most_progress = self::findMostProgress( $M, $N, $limit, $V );

		$snake0 = $bottoml1 + $most_progress[0];
		$snake1 = $bottoml2 + $most_progress[1];
		$snake2 = 0;
		wfDebug( "Computing the LCS is too expensive. Using a heuristic.\n" );
		$this->heuristicUsed = true;
		return 5; /*
		* HACK: since we didn't really finish the LCS computation
		* we don't really know the length of the SES. We don't do
		* anything with the result anyway, unless it's <=1. We know
		* for a fact SES > 1 so 5 is as good a number as any to
		* return here
		*/
	}

	private static function findMostProgress( $M, $N, $limit, $V ) {
		$delta = $N - $M;

		if ( ( $M & 1 ) == ( $limit & 1 ) ) {
			$forward_start_diag = max( -$M, -$limit );
		} else {
			$forward_start_diag = max( 1 - $M, -$limit );
		}

		$forward_end_diag = min( $N, $limit );

		if ( ( $N & 1 ) == ( $limit & 1 ) ) {
			$backward_start_diag = max( -$N, -$limit );
		} else {
			$backward_start_diag = max( 1 - $N, -$limit );
		}

		$backward_end_diag = -min( $M, $limit );

		$temp = array( 0, 0, 0 );

		$max_progress = array_fill( 0, ceil( max( $forward_end_diag - $forward_start_diag,
				$backward_end_diag - $backward_start_diag ) / 2 ), $temp );
		$num_progress = 0; // the 1st entry is current, it is initialized
		// with 0s

		// first search the forward diagonals
		for ( $k = $forward_start_diag; $k <= $forward_end_diag; $k += 2 ) {
			$x = $V[0][$limit + $k];
			$y = $x - $k;
			if ( $x > $N || $y > $M ) {
				continue;
			}

			$progress = $x + $y;
			if ( $progress > $max_progress[0][2] ) {
				$num_progress = 0;
				$max_progress[0][0] = $x;
				$max_progress[0][1] = $y;
				$max_progress[0][2] = $progress;
			} elseif ( $progress == $max_progress[0][2] ) {
				++$num_progress;
				$max_progress[$num_progress][0] = $x;
				$max_progress[$num_progress][1] = $y;
				$max_progress[$num_progress][2] = $progress;
			}
		}

		$max_progress_forward = true; // initially the maximum
		// progress is in the forward
		// direction

		// now search the backward diagonals
		for ( $k = $backward_start_diag; $k <= $backward_end_diag; $k += 2 ) {
			$x = $V[1][$limit + $k];
			$y = $x - $k - $delta;
			if ( $x < 0 || $y < 0 ) {
				continue;
			}

			$progress = $N - $x + $M - $y;
			if ( $progress > $max_progress[0][2] ) {
				$num_progress = 0;
				$max_progress_forward = false;
				$max_progress[0][0] = $x;
				$max_progress[0][1] = $y;
				$max_progress[0][2] = $progress;
			} elseif ( $progress == $max_progress[0][2] && !$max_progress_forward ) {
				++$num_progress;
				$max_progress[$num_progress][0] = $x;
				$max_progress[$num_progress][1] = $y;
				$max_progress[$num_progress][2] = $progress;
			}
		}

		// return the middle diagonal with maximal progress.
		return $max_progress[(int)floor( $num_progress / 2 )];
	}

	/**
	 * @return mixed
	 */
	public function getLcsLength() {
		if ( $this->heuristicUsed && !$this->lcsLengthCorrectedForHeuristic ) {
			$this->lcsLengthCorrectedForHeuristic = true;
			$this->length = $this->m -array_sum( $this->added );
		}
		return $this->length;
	}

}

/**
 * Alternative representation of a set of changes, by the index
 * ranges that are changed.
 *
 * @ingroup DifferenceEngine
 */
class RangeDifference {

	public $leftstart;
	public $leftend;
	public $leftlength;

	public $rightstart;
	public $rightend;
	public $rightlength;

	function __construct( $leftstart, $leftend, $rightstart, $rightend ) {
		$this->leftstart = $leftstart;
		$this->leftend = $leftend;
		$this->leftlength = $leftend - $leftstart;
		$this->rightstart = $rightstart;
		$this->rightend = $rightend;
		$this->rightlength = $rightend - $rightstart;
	}
}
