<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use MediaWiki\Deferred\DeferredUpdates;
use Monolog\Handler\BufferHandler as BaseBufferHandler;

/**
 * Helper class for the index.php entry point.
 *
 * Updates \Monolog\Handler\BufferHandler to use DeferredUpdates rather
 * than register_shutdown_function. On supported platforms this will
 * use register_postsend_function or fastcgi_finish_request() to delay
 * until after the request has shutdown and we are no longer delaying
 * the web request.
 *
 * TODO: shutdown is later than postsend. Is this class still useful?
 *
 * @since 1.26
 * @ingroup Debug
 */
class BufferHandler extends BaseBufferHandler {
	/**
	 * @inheritDoc
	 */
	public function handle( array $record ): bool {
		if ( !$this->initialized ) {
			DeferredUpdates::addCallableUpdate( $this->close( ... ) );
			$this->initialized = true;
		}
		return parent::handle( $record );
	}
}
