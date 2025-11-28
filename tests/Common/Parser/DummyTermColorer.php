<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Common\Parser;

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
/** @deprecated class alias since 1.46 */
class_alias( DummyTermColorer::class, 'MediaWiki\\Tests\\DummyTermColorer' );
