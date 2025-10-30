<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests;

/**
 * A colour-less terminal, drop-in replacement for {@link AnsiTermColorer}.
 *
 * @ingroup Testing
 */
class DummyTermColorer {
	public function color( string|int $color ): string {
		return '';
	}

	public function reset(): string {
		return '';
	}
}
