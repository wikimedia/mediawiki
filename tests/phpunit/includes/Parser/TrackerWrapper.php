<?php

namespace MediaWiki\Tests\Parser;

/**
 * Test wrapper to be able to keep track of calls to TrackingParserCache during execution.
 * Using an object wrapper allows to pass it to the various instances without worrying
 * about copies during argument passing.
 */
class TrackerWrapper {
	/** This array contains data set by TrackingParserCache during tests */
	public array $calls = [];
}
