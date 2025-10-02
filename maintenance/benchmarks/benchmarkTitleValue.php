<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

use MediaWiki\Maintenance\Benchmarker;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleParser;
use MediaWiki\Title\TitleValue;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that benchmarks TitleValue vs Title.
 *
 * @ingroup Benchmark
 */
class BenchmarkTitleValue extends Benchmarker {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;
	/**
	 * @var TitleParser
	 */
	private $titleParser;

	/**
	 * @var string
	 */
	private $dbKey = 'FooBar';
	/**
	 * @var TitleValue
	 */
	private $titleValue;
	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var string
	 */
	private $toParse;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark TitleValue vs Title.' );
	}

	public function execute() {
		$this->titleFormatter = $this->getServiceContainer()->getTitleFormatter();
		$this->titleParser = $this->getServiceContainer()->getTitleParser();
		$this->titleValue = $this->constructTitleValue();
		$this->title = $this->constructTitle();
		$this->toParse = 'Category:FooBar';
		$this->bench( [
			[
				'function' => [ $this, 'constructTitleValue' ],
			],
			[
				'function' => [ $this, 'constructTitle' ],
			],
			[
				'function' => [ $this, 'constructTitleSafe' ],
			],
			[
				'function' => [ $this, 'getPrefixedTextTitleValue' ],
			],
			[
				'function' => [ $this, 'getPrefixedTextTitle' ],
			],
			'parseTitleValue cached' => [
				'function' => [ $this, 'parseTitleValue' ],
				'setup' => [ $this, 'randomize' ],
			],
			'parseTitle cached' => [
				'function' => [ $this, 'parseTitle' ],
				'setup' => [ $this, 'randomize' ],
			],
			'parseTitleValue no cache' => [
				'function' => [ $this, 'parseTitleValue' ],
				'setupEach' => [ $this, 'randomize' ],
			],
			'parseTitle no cache' => [
				'function' => [ $this, 'parseTitle' ],
				'setupEach' => [ $this, 'randomize' ],
			],
		] );
	}

	/**
	 * Use a different dbKey each time to avoid influence of Title caches
	 */
	protected function randomize() {
		$this->dbKey = ucfirst( wfRandomString( 10 ) );
	}

	protected function constructTitleValue(): TitleValue {
		return new TitleValue( NS_CATEGORY, $this->dbKey );
	}

	protected function constructTitle(): Title {
		return Title::makeTitle( NS_CATEGORY, $this->dbKey );
	}

	protected function constructTitleSafe(): Title {
		return Title::makeTitleSafe( NS_CATEGORY, $this->dbKey );
	}

	protected function getPrefixedTextTitleValue(): string {
		// This is really showing TitleFormatter perf
		return $this->titleFormatter->getPrefixedText( $this->titleValue );
	}

	protected function getPrefixedTextTitle(): string {
		return $this->title->getPrefixedText();
	}

	protected function parseTitleValue() {
		// This is really showing TitleParser perf
		$this->titleParser->parseTitle( 'Category:' . $this->dbKey, NS_MAIN );
	}

	protected function parseTitle() {
		Title::newFromText( 'Category:' . $this->dbKey );
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkTitleValue::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
