<?php
/**
 * Testing logger
 *
 * Copyright (C) 2015 Wikimedia Foundation and contributors
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

use Psr\Log\LogLevel;

/**
 * A logger that may be configured to either buffer logs or to print them to
 * the output where PHPUnit will complain about them.
 *
 * @since 1.27
 */
class TestLogger extends \Psr\Log\AbstractLogger {
	private $collect = false;
	private $collectContext = false;
	private $buffer = [];
	private $filter = null;

	/**
	 * @param bool $collect Whether to collect logs. @see setCollect()
	 * @param callable|null $filter Filter logs before collecting/printing. Signature is
	 *  string|null function ( string $message, string $level, array $context );
	 * @param bool $collectContext Whether to keep the context passed to log
	 *             (since 1.29, @see setCollectContext()).
	 */
	public function __construct( $collect = false, $filter = null, $collectContext = false ) {
		$this->collect = $collect;
		$this->collectContext = $collectContext;
		$this->filter = $filter;
	}

	/**
	 * Set the "collect" flag
	 * @param bool $collect
	 * @return TestLogger $this
	 */
	public function setCollect( $collect ) {
		$this->collect = $collect;
		return $this;
	}

	/**
	 * Set the collectContext flag
	 *
	 * @param bool $collectContext
	 * @since 1.29
	 * @return TestLogger $this
	 */
	public function setCollectContext( $collectContext ) {
		$this->collectContext = $collectContext;
		return $this;
	}

	/**
	 * Return the collected logs
	 * @return array Array of array( string $level, string $message ), or
	 *   array( string $level, string $message, array $context ) if $collectContext was true.
	 */
	public function getBuffer() {
		return $this->buffer;
	}

	/**
	 * Clear the collected log buffer
	 */
	public function clearBuffer() {
		$this->buffer = [];
	}

	public function log( $level, $message, array $context = [] ) {
		$message = trim( $message );

		if ( $this->filter ) {
			$message = call_user_func( $this->filter, $message, $level, $context );
			if ( $message === null ) {
				return;
			}
		}

		if ( $this->collect ) {
			if ( $this->collectContext ) {
				$this->buffer[] = [ $level, $message, $context ];
			} else {
				$this->buffer[] = [ $level, $message ];
			}
		} else {
			switch ( $level ) {
				case LogLevel::DEBUG:
				case LogLevel::INFO:
				case LogLevel::NOTICE:
					trigger_error( "LOG[$level]: $message", E_USER_NOTICE );
					break;

				case LogLevel::WARNING:
					trigger_error( "LOG[$level]: $message", E_USER_WARNING );
					break;

				case LogLevel::ERROR:
				case LogLevel::CRITICAL:
				case LogLevel::ALERT:
				case LogLevel::EMERGENCY:
					trigger_error( "LOG[$level]: $message", E_USER_ERROR );
					break;
			}
		}
	}
}
