<?php
/**
 * @section LICENSE
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

/**
 * Simple event dispatcher.
 *
 * Event listeners register to receive notifications for specific named events
 * using EventDispatcher::listen. Registered callbacks are executed in
 * registration order when EventDispatcher::fire is called to generate an
 * event. Events are passed as arrays including the topic of the event and
 * an arbirtary payload.
 *
 * @since 1.26
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 */
class EventDispatcher {

	/**
	 * @var array $instances
	 */
	private static $instances = array();

	/**
	 * @var array $listeners
	 */
	protected $listeners = array();

	public function __construct() {
	}

	/**
	 * Register a callback to receive notifications of new events.
	 *
	 * When an event is recieved for the given topic, the callback will be
	 * called with a single array argument representing the event. The array
	 * will contain two elements: "topic" and "data".
	 *
	 * @param string $topic Event topic
	 * @param callable $callback Callback to recieve events
	 */
	public function listen( $topic, $callback ) {
		if ( !isset( $this->listeners[$topic] ) ) {
			$this->listeners[$topic] = array();
		}
		$this->listeners[$topic][] = $callback;
	}

	/**
	 * Are any listeners registered for the topic?
	 *
	 * @param string $topic Event topic
	 * @return bool
	 */
	public function hasListeners( $topic ) {
		return isset( $this->listeners[$topic] );
	}

	/**
	 * Send an event to all listeners on the topic.
	 *
	 * @param string $topic Event topic
	 * @param mixed $data Event data
	 */
	public function fire( $topic, $data = null ) {
		if ( !$this->hasListeners( $topic ) ) {
			return;
		}

		$event = array( 'topic' => $topic, 'data' => $data );
		foreach ( $this->listeners[$topic] as $callback ) {
			call_user_func( $callback, $event );
		}
	}

	/**
	 * Get a named dispatcher.
	 *
	 * @param string $name
	 * @return EventDispatcher
	 */
	public static function getNamedInstance( $name ) {
		if ( !isset( self::$instances[$name] ) ) {
			self::$instances[$name] = new self;
		}
		return self::$instances[$name];
	}
}
