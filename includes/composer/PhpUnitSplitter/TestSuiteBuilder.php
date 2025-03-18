<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class TestSuiteBuilder {

	private static function sortByNameAscending( TestDescriptor $a, TestDescriptor $b ): int {
		return $a->getFilename() <=> $b->getFilename();
	}

	/**
	 * Try to build balanced groups (split_groups) of tests. We have a couple of
	 * objectives here:
	 *   - the groups should contain a stable ordering of tests so that we reduce the amount
	 *     of random test failures due to test re-ordering
	 *   - the groups should reduce the number of interacting extensions where possible. This
	 *     is achieved with the alphabetical sort on filename - tests of the same extension will
	 *     be grouped together
	 *   - the groups should have a similar test execution time
	 *
	 * Information about test duration may be completely absent (if no test cache information is
	 * supplied), or partially absent (if the test has not been seen before). We attempt to create
	 * similar-duration split-groups using the information we have available, and if anything goes
	 * wrong we fall back to just creating split-groups with the same number of tests in them.
	 *
	 * @param array $testDescriptors the list of tests that we want to sort into split_groups
	 * @param int $groups the number of split_groups we are targetting
	 * @param ?int $chunkSize optionally override the size of the 'chunks' into which tests
	 *             are grouped. If not supplied, the chunk size will depend on the total number
	 *             of tests.
	 * @return array a structured array of the resulting split_groups
	 */
	public function buildSuites( array $testDescriptors, int $groups, ?int $chunkSize = null ): array {
		$suites = array_fill( 0, $groups, [ "list" => [], "time" => 0 ] );
		// If there are no test descriptors, we just return empty suites
		if ( $testDescriptors === [] ) {
			return $suites;
		}

		// Sort the tests by name so that we run tests of the same extension together and in a predictable order
		usort( $testDescriptors, [ self::class, "sortByNameAscending" ] );

		$descriptorCount = count( $testDescriptors );
		if ( $chunkSize === null ) {
			// The algorithm is CPU intensive - make sure we run with at most 200 'chunks' of tests to group
			$chunkSize = intval( ceil( $descriptorCount / 200 ) );
		}

		// Skip over any leading zero-time tests, and add them back to the first group at the end
		// Without this adjustment, the dynamic-sizing algorithm can end up with a zero-size split-group
		// which would cause PHPUnit to error.
		$startIndex = 0;
		while ( $startIndex < $descriptorCount && $testDescriptors[$startIndex]->getDuration() == 0 ) {
			$startIndex++;
		}

		if ( $startIndex === 0 ) {
			$testDescriptorsWithoutLeadingZeros = $testDescriptors;
			$leadingZeros = [];
		} elseif ( $startIndex < $descriptorCount ) {
			$leadingZeros = array_map(
				static fn ( $testDescriptor ) => $testDescriptor->getFilename(),
				array_slice( $testDescriptors, 0, $startIndex )
			);
			$testDescriptorsWithoutLeadingZeros = array_slice( $testDescriptors, $startIndex );
		} else {
			// if we never encounter a test with duration information, fall back to splitting
			// tests into split-groups with the same number of test classes.
			return $this->buildSuitesNoDurationInformation( $testDescriptors, $groups );
		}

		try {
			$this->buildSuitesWithDurationInformationWithoutLeadingEmptyTests(
				$testDescriptorsWithoutLeadingZeros, $suites, $groups, $chunkSize
			);
		} catch ( SuiteSplittingException $se ) {
			return $this->buildSuitesNoDurationInformation( $testDescriptors, $groups );
		}

		// Add any zero-length tests that we sliced away before splitting back to the first group
		if ( $leadingZeros !== [] ) {
			$suites[0]["list"] = array_merge( $leadingZeros, $suites[0]["list"] );
		}

		return $suites;
	}

	/**
	 * @throws SuiteSplittingException
	 */
	private function buildSuitesWithDurationInformationWithoutLeadingEmptyTests(
		array $testDescriptorsWithoutLeadingZeros,
		array &$suites,
		int $numGroups,
		int $chunkSize
	): void {
		$n = count( $testDescriptorsWithoutLeadingZeros );
		if ( $n == 0 ) {
			return;
		}

		$chunks = $this->createChunksOfTests( $n, $testDescriptorsWithoutLeadingZeros, $chunkSize );

		$numChunks = count( $chunks );
		$durations = array_column( $chunks, "time" );

		// Build an array of cumulative test duration (or 'prefix sum') - sum(0..i){x.duration}
		$prefixSum = $this->calculatePrefixSum( $numChunks, $durations );

		// Generate backtracking table
		$backtrack = $this->generateBacktrackingTable( $numChunks, $numGroups, $prefixSum );

		$this->constructSplitGroups( $numGroups, $numChunks, $chunks, $backtrack, $suites );
	}

	/**
	 * Called as a fallback for the case where not duration information is available.
	 * Simply split the tests into $groups equally-sized split-groups.
	 *
	 * @param TestDescriptor[] $testDescriptors
	 * @param int $groups
	 * @return array
	 */
	private function buildSuitesNoDurationInformation( array $testDescriptors, int $groups ): array {
		$suites = array_fill( 0, $groups, [ "list" => [], "time" => 0 ] );
		$testCount = count( $testDescriptors );
		$splitGroupSize = ceil( $testCount / $groups );
		$bucketIndex = 0;
		$testIndex = 0;
		for ( $currentGroup = 0; $currentGroup < $groups, $testIndex < $testCount; $testIndex++, $bucketIndex++ ) {
			if ( $bucketIndex >= $splitGroupSize ) {
				$bucketIndex = 0;
				$currentGroup++;
				if ( $currentGroup === $groups ) {
					break;
				}
			}
			$suites[$currentGroup]["list"][] = $testDescriptors[$testIndex]->getFilename();
			$suites[$currentGroup]["time"] += $testDescriptors[$testIndex]->getDuration();
		}

		return $suites;
	}

	private function createChunksOfTests( int $n, array $testDescriptors, int $chunkSize ): array {
		$chunks = [];
		for ( $i = 0; $i < $n; $i += $chunkSize ) {
			$chunk = array_slice( $testDescriptors, $i, min( $chunkSize, $n - $i ) );
			$chunks[] = [
				"list" => $chunk,
				"time" => array_reduce( $chunk, static fn ( $sum, $test ) => $sum + $test->getDuration(), 0 )
			];
		}
		return $chunks;
	}

	private function calculatePrefixSum( int $numChunks, array $durations ): array {
		$prefixSum = array_fill( 0, $numChunks + 1, 0 );
		for ( $i = 1; $i <= $numChunks; $i++ ) {
			$prefixSum[$i] = $prefixSum[$i - 1] + $durations[$i - 1];
		}
		return $prefixSum;
	}

	/**
	 * Construct the split groups from the backtracking table.
	 * @throws SuiteSplittingException
	 */
	private function constructSplitGroups(
		int $numGroups,
		int $numChunks,
		array $chunks,
		array $backtrack,
		array &$suites
	) {
		for ( $splitGroupId = $numGroups, $i = $numChunks; $splitGroupId > 0; --$splitGroupId ) {
			$startIndex = $backtrack[$i][$splitGroupId];
			if ( $startIndex === -1 ) {
				throw new SuiteSplittingException( "Invalid backtracking index building group " . $splitGroupId );
			}
			$suites[$splitGroupId - 1]["list"] = array_merge(
				...array_map( static fn ( $chunk ) => array_map(
						static fn ( $test ) => $test->getFilename(),
						$chunk["list"]
					),
					array_slice( $chunks, $startIndex, $i - $startIndex )
				)
			);
			$suites[$splitGroupId - 1]["time"] = array_reduce(
				array_slice( $chunks, $startIndex, $i - $startIndex ),
				static fn ( $acc, $chunk ) => $acc + $chunk["time"],
				0
			);
			$i = $startIndex;
		}
	}

	/**
	 * Find the distribution of group split points that minimises the duration of the largest split_group
	 * and thereby minimises the duration of the CI job.
	 */
	private function generateBacktrackingTable( int $numChunks, int $numGroups, array $prefixSum ): array {
		// $minimumLargestBucket[x][y] is the minimum possible largest split_group duration when splitting
		// the first x chunks into y groups
		$minimumLargestBucket = array_fill( 0, $numChunks + 1, array_fill( 0, $numGroups + 1, PHP_INT_MAX ) );
		// The backtracking table keeps track of the end point of the last group of the optimal distribution
		$backtrack = array_fill( 0, $numChunks + 1, array_fill( 0, $numGroups + 1, -1 ) );

		$minimumLargestBucket[0][0] = 0;

		// We work inductively, starting with distributing 1 chunk into 1 split_group
		// and progressively distributing more tests until all chunks are in a split_group
		for ( $i = 1; $i <= $numChunks; $i++ ) {
			// For $i chunks, split them into up to $numGroups groups by identifying the optimal split points
			for ( $j = 1; $j <= min( $numGroups, $i ); $j++ ) {
				// For each split group $j find a split point $k, somewhere before the current chunk
				for ( $k = 0; $k < $i; $k++ ) {
					// the difference of the prefix sums is the combined duration of chunks $k through $i
					$currentSplitGroupDuration = $prefixSum[$i] - $prefixSum[$k];
					// Compare the duration of the proposed split group with the minimum duration we found so far
					// for splitting $k tests into $j-1 groups.
					$newBestCaseMinimumLargestBucket = max(
						$minimumLargestBucket[$k][$j - 1], $currentSplitGroupDuration
					);
					// If our current duration is smaller, we know that we can split $k tests into $j groups without
					// increasing the minimum duration. If it is greater, we know that putting a split point at $k would
					// make the minimum duration of any group at least $currentSplitGroupDuration.
					if ( $newBestCaseMinimumLargestBucket < $minimumLargestBucket[$i][$j] ) {
						// If the new duration is less than the existing minimum for splitting $i tests into $j groups,
						// we update the minimum duration.
						$minimumLargestBucket[$i][$j] = $newBestCaseMinimumLargestBucket;
						// Set the backtrack reference so that we know where the split point was for this minimal value.
						$backtrack[$i][$j] = $k;
					}
				}
			}
		}
		return $backtrack;
	}

}
