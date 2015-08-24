<?php
/**
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
 * Manage a stack of multiple user-defined error handlers.
 *
 * PHP's set_error_handler() only allows installing one custom error handler at
 * a time. That handler can either take complete control of the error handling
 * process by returning true to indicate that the error was handled, or allow
 * PHP to continue to process the error using the default handler. This class
 * extends that native facility to allow multiple user-defined error handlers
 * to be called in an ordered chain before finally choosing whether or not to
 * fall back to the default handler.
 *
 * Each handler follows the signature and behavior of a traditional
 * set_error_handler() handler. If the handler returns true processing of the
 * chain will stop at that point and the default handler will not be called.
 *
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 * @since 1.26
 */
class ErrorHandlerStack {

	/**
	 * @var ErrorHandlerStack $instance
	 */
	private static $instance;

	/**
	 * Stack of error handlers.
	 *
	 * @param array $stack
	 */
	protected $stack;

	/**
	 * External construction not allowed.
	 * @see getStack()
	 */
	private function __construct() {
		$this->stack = array();
		set_error_handler( array( $this, 'handleError' ) );
	}

	/**
	 * @return ErrorHandlerStack
	 */
	public static function getStack() {
		if ( static::$instance === null ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Add a new handler to the top (first) of the handler stack.
	 *
	 * Unlike set_error_handler(), this method does not allow the caller to
	 * specify an error level mask to restrict the errors that are passed to
	 * the callback. If this functionality is needed, the easiest thing to do
	 * is to check the $errno passed to the callback and return false
	 * immediately if the handler should ignore the event.
	 *
	 * @param Callable $callable
	 * @return int Depth of handler stack
	 */
	public function push( $callable ) {
		return array_unshift( $this->stack, $callable );
	}

	/**
	 * Add a new handler to the top (first) of the handler stack and get
	 * a ScopedCallback that will pop() the stack when it goes out of scope.
	 *
	 * @param Callable $callable
	 * @return ScopedCallback
	 */
	public function pushScoped( $callable ) {
		$this->push( $callable );
		return new ScopedCallback( array( $this, 'pop' ) );
	}

	/**
	 * Remove the top (first) handler from the handler stack.
	 *
	 * @return Callable
	 */
	public function pop() {
		return array_shift( $this->stack );
	}

	/**
	 * @param int $errno Error level raised
	 * @param string $errstr Error message
	 * @param string $file File that error was raised in
	 * @param int $line Line number error was raised at
	 * @param array $context Active symbol table point of error
	 * @param array $trace Backtrace at point of error (HHVM only)
	 * @return bool True to halt error handling
	 */
	public function handleError(
		$errno, $errstr, $file = null, $line = null,
		$context = null, $trace = null
	) {
		$args = func_get_args();
		$ret = false;
		foreach( $this->stack as $handler ) {
			$ret = call_user_func_array( $handler, $args );
			if ( $ret ) {
				break;
			}
		}
		return $ret;
	}
}
