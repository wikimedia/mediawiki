<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class TestSuiteBuilder {

	private static function sortByTimeDescending( TestDescriptor $a, TestDescriptor $b ): int {
		if ( $a->getDuration() === $b->getDuration() ) {
			return 0;
		}
		return ( $a->getDuration() > $b->getDuration() ? -1 : 1 );
	}

	private static function smallestGroup( array $suites ): int {
		$min = 10000;
		$minIndex = 0;
		$groups = count( $suites );
		for ( $i = 0; $i < $groups; $i++ ) {
			if ( $suites[$i]["time"] < $min ) {
				$min = $suites[$i]["time"];
				$minIndex = $i;
			}
		}
		return $minIndex;
	}

	public function buildSuites( array $testDescriptors, int $groups ): array {
		$suites = array_fill( 0, $groups, [ "list" => [], "time" => 0 ] );
		$roundRobin = 0;
		usort( $testDescriptors, [ self::class, "sortByTimeDescending" ] );
		foreach ( $testDescriptors as $testDescriptor ) {
			if ( !$testDescriptor->getFilename() ) {
				// We didn't resolve a matching file for this test, so we skip it
				// from the suite here. This only happens for "known" missing test
				// classes (see PhpUnitXmlManager::EXPECTED_MISSING_CLASSES) - in
				// all other cases a missing test file will throw an exception during
				// suite building.
				continue;
			}
			if ( $testDescriptor->getDuration() === 0 ) {
				// If no explicit timing information is available for a test, we just
				// drop it round-robin into the next bucket.
				$nextSuite = $roundRobin;
				$roundRobin = ( $roundRobin + 1 ) % $groups;
			} else {
				// If we have information about the test duration, we try and balance
				// out the tests suites by having an even amount of time spent on
				// each suite.
				$nextSuite = self::smallestGroup( $suites );
			}
			$suites[$nextSuite]["list"][] = $testDescriptor->getFilename();
			$suites[$nextSuite]["time"] += $testDescriptor->getDuration();
		}
		return $suites;
	}
}
