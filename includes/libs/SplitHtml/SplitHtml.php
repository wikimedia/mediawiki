<?php

namespace Wikimedia\SplitHtml;
use RemexHtml\Tokenizer\Tokenizer;

class SplitHtml {
	public static function splitAtCharOffset( $text, $offset ) {
		$handler = new SplitHandler( $offset );
		$tokenizer = new Tokenizer( $handler, $text, [
			'ignoreErrors' => true,
			'ignoreNulls' => true,
			'skipPreprocess' => true,
		] );
		$tokenizer->execute();
		return $handler->getParts();
	}
}
