<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Stats\Emitters;

/**
 * Emitter Interface
 *
 * Renderers run the contents of StatsCache through a Formatter to
 * produce wire-formatted metrics.
 *
 * @author Cole White
 * @since 1.41
 */
interface EmitterInterface {
	/**
	 * Runs metrics and their samples from the cache through the formatter and sends them along.
	 *
	 * @return void
	 */
	public function send(): void;
}
