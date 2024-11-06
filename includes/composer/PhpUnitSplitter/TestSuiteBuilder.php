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
	 * Try to build balanced groups (split_groups / buckets) of tests. We have a couple of
	 * objectives here:
	 *   - the groups should contain a stable ordering of tests so that we reduce the amount
	 *     of random test failures due to test re-ordering
	 *   - the groups should reduce the number of interacting extensions where possible. This
	 *     is achieved with the alphabetical sort on filename - tests of the same extension will
	 *     be grouped together
	 *   - the groups should have a similar test execution time
	 *
	 * Information about test duration may be completely absent (if no test cache information is
	 * supplied), or partially absent (if the test has not been seen before). Since we neither
	 * want to ignore the duration information nor rely on it, we compromise by filling the buckets
	 * until we have reached a maximum by test count *or* by duration. This has the consequence
	 * that tests with a duration of zero will be treated somewhat like tests with an average
	 * duration.
	 *
	 * @param array $testDescriptors the list of tests that we want to sort into split_groups
	 * @param int $groups the number of split_groups we are targetting
	 * @return array a structured array of the resulting split_groups
	 */
	public function buildSuites( array $testDescriptors, int $groups ): array {
		$suites = array_fill( 0, $groups, [ "list" => [], "time" => 0 ] );

		// Sort the tests alphabetically so that tests in the same extension (folder) stay
		// together in the same split_group
		usort( $testDescriptors, [ self::class, "sortByNameAscending" ] );

		// Count the total number of tests (with valid filenames) and set the max number
		// of tests per bucket
		$testCount = array_reduce(
			$testDescriptors,
			static fn ( $acc, $descriptor ) => ( $descriptor->getFilename() ? $acc + 1 : $acc ),
			0
		);
		$bucketTestCount = ceil( $testCount / $groups );

		// Count the total duration of tests (with duration information) and set the max
		// duration per bucket
		$totalDuration = array_reduce(
			$testDescriptors,
			static fn ( $acc, $descriptor ) => $acc + $descriptor->getDuration(),
			0
		);
		$maxBucketDuration = ceil( $totalDuration / $groups );

		// Counters for current bucket and cumulative counters for total progress
		$currentTestIndex = 0;
		$currentBucketDuration = 0;
		$currentBucketIndex = 0;
		$cumulativeTestCount = 0;
		$cumulativeDuration = 0;
		foreach ( $testDescriptors as $testDescriptor ) {
			if ( !$testDescriptor->getFilename() ) {
				// We didn't resolve a matching file for this test, so we skip it
				// from the suite here. This only happens for "known" missing test
				// classes (see PhpUnitXmlManager::EXPECTED_MISSING_CLASSES) - in
				// all other cases a missing test file will throw an exception during
				// suite building.
				continue;
			}
			$suites[$currentBucketIndex]["list"][] = $testDescriptor->getFilename();
			$suites[$currentBucketIndex]["time"] += $testDescriptor->getDuration();
			$currentTestIndex += 1;
			$cumulativeTestCount += 1;
			$currentBucketDuration += $testDescriptor->getDuration();
			$cumulativeDuration += $testDescriptor->getDuration();

			// Advance to the next bucket if we either have reached the limit in number of tests or the
			// limit in test duration
			if ( $currentTestIndex >= $bucketTestCount || $currentBucketDuration > $maxBucketDuration ) {
				// Don't advance past the last bucket. If we reached the last bucket, just dump
				// everything in there.
				if ( $currentBucketIndex < $groups - 1 ) {
					$currentBucketIndex++;
				}
				$currentTestIndex = 0;
				$currentBucketDuration = 0;

				// Rebalance the bucket targets - $remainingBuckets will be at least 1
				$remainingBuckets = $groups - $currentBucketIndex;
				$bucketTestCount = ceil( ( $testCount - $cumulativeTestCount ) / $remainingBuckets );
				$maxBucketDuration = ceil( ( $totalDuration - $cumulativeDuration ) / $remainingBuckets );
			}
		}
		return $suites;
	}
}
