<?php

/**
 * A parser used during article insertion which does nothing, to avoid
 * unnecessary log noise and other interference with debugging.
 */
class ParserTestMockParser {
	public function preSaveTransform( $text, Title $title, User $user,
		ParserOptions $options, $clearState = true
	) {
		return $text;
	}

	public function parse(
		$text, Title $title, ParserOptions $options,
		$linestart = true, $clearState = true, $revid = null
	) {
		return new ParserOutput;
	}

	public function getOutput() {
		return new ParserOutput;
	}
}
