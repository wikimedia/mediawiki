<?php

namespace MediaWiki\Tests\Maintenance;

use GrepPages;
use MediaWiki\WikiMap\WikiMap;

/**
 * @covers GrepPages
 * @group Database
 * @author Dreamy Jazz
 */
class GrepPagesTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return GrepPages::class;
	}

	private function getTestPageWithContent( $title, $content ) {
		$testPage = $this->getExistingTestPage( $title );
		$this->editPage( $testPage, $content );
	}

	public function addDBDataOnce() {
		// Create a variety of pages for the test
		$this->getTestPageWithContent( 'TestPage1', 'testingabcdef' );
		$this->getTestPageWithContent( 'TestPage2', 'abcdef' );
		$this->getTestPageWithContent( 'Talk:TestPage1', 'talk-testing' );
		$this->getTestPageWithContent(
			'Template:TestPage',
			"template-test\n== Test ==\nTest section"
		);
	}

	/** @dataProvider provideExecute */
	public function testExecute( $regex, $options, $expectedOutputString ) {
		$this->maintenance->setArg( 0, $regex );
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->maintenance->execute();
		$this->expectOutputString( $expectedOutputString );
	}

	public static function provideExecute() {
		return [
			'Regex defined to be all pages with --pages-with-matches defined' => [
				'/.*/', [ 'pages-with-matches' => 1 ],
				"TestPage1\nTestPage2\nTalk:TestPage1\nTemplate:TestPage\n",
			],
			'Regex defined as no pages' => [ '/^$/', [], '' ],
			'Regex defined as pages containing the word test' => [
				'test', [],
				"TestPage1:1:testingabcdef\nTalk:TestPage1:1:talk-testing\nTemplate:TestPage:1:template-test\n",
			],
			'--prefix for template namespace and --pages-with-matches' => [
				'', [ 'prefix' => [ 'Template:' ], 'pages-with-matches' => 1 ],
				"Template:TestPage\n",
			],
			'--prefix for template namespace' => [
				'', [ 'prefix' => [ 'Template:' ] ],
				"Template:TestPage:1:template-test\nTemplate:TestPage:2:== Test ==\nTemplate:TestPage:3:Test section\n",
			],
			'--prefix without namespace and --pages-with-matches' => [
				'', [ 'prefix' => [ 'TestPage' ], 'pages-with-matches' => 1 ], "TestPage1\nTestPage2\n",
			],
			'--prefix specified twice and --pages-with-matches' => [
				'', [ 'prefix' => [ 'TestPage', 'Talk:TestPage' ], 'pages-with-matches' => 1 ],
				"TestPage1\nTestPage2\nTalk:TestPage1\n",
			],
		];
	}

	public function testExecuteForShowWiki() {
		$wikiName = WikiMap::getCurrentWikiId();
		$this->testExecute(
			'test', [ 'show-wiki' => 1 ],
			$wikiName . "\tTestPage1:1:testingabcdef\n" .
			$wikiName . "\tTalk:TestPage1:1:talk-testing\n" .
			$wikiName . "\tTemplate:TestPage:1:template-test\n",
		);
	}

	public function testExecuteForShowWikiAndPageWithMatches() {
		$wikiName = WikiMap::getCurrentWikiId();
		$this->testExecute(
			'test', [ 'show-wiki' => 1, 'pages-with-matches' => 1 ],
			$wikiName . "\tTestPage1\n" .
			$wikiName . "\tTalk:TestPage1\n" .
			$wikiName . "\tTemplate:TestPage\n",
		);
	}
}
