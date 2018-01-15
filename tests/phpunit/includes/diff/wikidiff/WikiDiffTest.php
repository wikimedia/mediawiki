<?php

/**
 * @author Andrew Kostka < Andrew.Kostka@wikimedia.de >
 *
 * @group Diff
 */
class WikiDiffTest extends MediaWikiTestCase {

	/**
	 * @var DifferenceEngine
	 */
	private $diffEngine;

	protected function setUp() {
		parent::setUp();
		$this->diffEngine = new DifferenceEngine();
	}

	private function getTestCases() {
		return [
			[1, "Honeyguide"],
			[2, "Rogers"]
		];
	}

	public function testDiffOutput() {
		foreach ($this->getTestCases() as $case) {
			$fileA = file_get_contents(__DIR__ . "/files/" . $case[0] . "/a.txt");
			$fileB = file_get_contents(__DIR__ . "/files/" . $case[0] . "/b.txt");
			$fileRes = file_get_contents(__DIR__ . "/files/" . $case[0] . "/res.txt");

			$diff = $this->diffEngine->generateTextDiffBody($fileA, $fileB);
			$this->assertEquals($fileRes, $diff, $case[1]);
		}
	}
}