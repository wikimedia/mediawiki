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

use Monolog\Handler\HandlerInterface;
use Monolog\Formatter\FormatterInterface;

/**
 * Wrapper for another HandlerInterface that will only handle a percentage of
 * records offered to it.
 *
 * When HandlerInterface::handle() is called for a given record, it will be
 * handled or ignored with a one in N chance based on the sample factor given
 * for the handler.
 *
 * Usage with MWLoggerMonologSpi:
 * @code
 * $wgMWLoggerDefaultSpi = array(
 *   'class' => 'MWLoggerMonologSpi',
 *   'args' => array( array(
 *     'handlers' => array(
 *       'some-handler' => array( ... ),
 *       'sampled-some-handler' => array(
 *         'class' => 'MWLoggerMonologSamplingHandler',
 *         'args' => array(
 *           function() {
 *             return MWLoggerFactory::getProvider()->getHandler( 'some-handler');
 *           },
 *           2, // emit logs with a 1:2 chance
 *         ),
 *       ),
 *     ),
 *   ) ),
 * );
 * @endcode
 *
 * A sampled event stream can be useful for logging high frequency events in
 * a production environment where you only need an idea of what is happening
 * and are not concerned with capturing every occurence. Since the decision to
 * handle or not handle a particular event is determined randomly, the
 * resulting sampled log is not guaranteed to contain 1/N of the events that
 * occurred in the application but based on [[Law of large numbers]] it will
 * tend to be close to this ratio with a large number of attempts.
 *
 * @since 1.25
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
class MWLoggerMonologSamplingHandler implements HandlerInterface {

	/**
	 * @var HandlerInterface $delegate
	 */
	protected $delegate;

	/**
	 * @var int $factor
	 */
	protected $factor;

	/**
	 * @param HandlerInterface $handler Wrapped handler
	 * @param int $factor Sample factor
	 */
	public function __construct( HandlerInterface $handler, $factor ) {
		$this->delegate = $handler;
		$this->factor = $factor;
	}

	public function isHandling( array $record ) {
		return $this->delegate->isHandling( $record );
	}

	public function handle( array $record ) {
		if ( $this->isHandling( $record )
			&& mt_rand( 1, $this->factor ) === 1
		) {
			return $this->delegate->handle( $record );
		}
		return false;
	}

	public function handleBatch( array $records ) {
		foreach ( $records as $record ) {
			$this->handle( $record );
		}
	}

	public function pushProcessor( $callback ) {
		$this->delegate->pushProcessor( $callback );
		return $this;
	}

	public function popProcessor() {
		return $this->delegate->popProcessor();
	}

	public function setFormatter( FormatterInterface $formatter ) {
		$this->delegate->setFormatter( $formatter );
		return $this;
	}

	public function getFormatter() {
		return $this->delegate->getFormatter();
	}

}
