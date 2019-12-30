<?php

use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;

/**
 * A parser used during article insertion which does nothing, to avoid
 * unnecessary log noise and other interference with debugging.
 */
class ParserTestMockParser extends Parser {

	public function __construct() {
	}

	public function preSaveTransform( $text, PageReference $page, UserIdentity $user,
		ParserOptions $options, $clearState = true
	) {
		return $text;
	}

	public function parse(
		$text, PageReference $page, ParserOptions $options,
		$linestart = true, $clearState = true, $revid = null
	) {
		return new ParserOutput;
	}

	public function getOutput() {
		return new ParserOutput;
	}
}
