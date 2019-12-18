<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@member.fsf.org>
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
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/../includes/Benchmarker.php';

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
		$this->titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
		$this->titleParser = MediaWikiServices::getInstance()->getTitleParser();
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

	protected function constructTitleValue() {
		return new TitleValue( NS_CATEGORY, $this->dbKey );
	}

	protected function constructTitle() {
		return Title::makeTitle( NS_CATEGORY, $this->dbKey );
	}

	protected function constructTitleSafe() {
		return Title::makeTitleSafe( NS_CATEGORY, $this->dbKey );
	}

	protected function getPrefixedTextTitleValue() {
		// This is really showing TitleFormatter aka MediaWikiTitleCodec perf
		return $this->titleFormatter->getPrefixedText( $this->titleValue );
	}

	protected function getPrefixedTextTitle() {
		return $this->title->getPrefixedText();
	}

	protected function parseTitleValue() {
		// This is really showing TitleParser aka MediaWikiTitleCodec perf
		$this->titleParser->parseTitle( 'Category:' . $this->dbKey, NS_MAIN );
	}

	protected function parseTitle() {
		Title::newFromText( 'Category:' . $this->dbKey );
	}
}

$maintClass = BenchmarkTitleValue::class;
require_once RUN_MAINTENANCE_IF_MAIN;
