<?php
/**
 * Helper class for the index.php entry point.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use DeferredUpdates;
use Monolog\Handler\BufferHandler as BaseBufferHandler;

/**
 * Updates \Monolog\Handler\BufferHandler to use DeferredUpdates rather
 * than register_shutdown_function. On supported platforms this will
 * use register_postsend_function or fastcgi_finish_request() to delay
 * until after the request has shutdown and we are no longer delaying
 * the web request.
 */
class BufferHandler extends BaseBufferHandler {
	/**
	 * {@inheritDoc}
	 */
	public function handle( array $record ) {
		if ( !$this->initialized ) {
			DeferredUpdates::addCallableUpdate( [ $this, 'close' ] );
			$this->initialized = true;
		}
		return parent::handle( $record );
	}
}
