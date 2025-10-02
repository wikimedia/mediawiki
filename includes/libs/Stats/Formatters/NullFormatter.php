<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

declare( strict_types=1 );

namespace Wikimedia\Stats\Formatters;

/**
 * Null Formatter Implementation
 *
 * For passing to unconfigurable Renderers.
 *
 * @author Cole White
 * @since 1.41
 */
class NullFormatter implements FormatterInterface {
	/** @inheritDoc */
	public function getFormattedSamples( string $prefix, $metric ): array {
		return [];
	}
}
