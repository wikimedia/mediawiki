<?php
/**
 * Testing logger
 *
 * Copyright (C) 2015 Brad Jorsch <bjorsch@wikimedia.org>
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
 * @author Brad Jorsch <bjorsch@wikimedia.org>
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
	private $buffer = [];
	private $filter = null;

	/**
	 * @param bool $collect Whether to collect logs
	 * @param callable $filter Filter logs before collecting/printing. Signature is
	 *  string|null function ( string $message, string $level );
	 */
	public function __construct( $collect = false, $filter = null ) {
		$this->collect = $collect;
		$this->filter = $filter;
	}

	/**
	 * Set the "collect" flag
	 * @param bool $collect
	 */
	public function setCollect( $collect ) {
		$this->collect = $collect;
	}

	/**
	 * Return the collected logs
	 * @return array Array of array( string $level, string $message )
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
			$message = call_user_func( $this->filter, $message, $level );
			if ( $message === null ) {
				return;
			}
		}

		if ( $this->collect ) {
			$this->buffer[] = [ $level, $message ];
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
