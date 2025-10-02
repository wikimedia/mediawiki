<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Stats\Emitters;

/**
 * Metrics Null Emitter Implementation
 *
 * Emitter for null-formatted metrics.
 *
 * @author Cole White
 * @since 1.41
 */
class NullEmitter implements EmitterInterface {
	public function send(): void {
	}
}
