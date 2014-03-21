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
 * Log handler that replicates the behavior of MediaWiki's wfErrorLog()
 * logging service. Log output can be directed to a local file, a PHP stream,
 * or a udp2log server.
 *
 * For udp2log output, the stream specification must have the form:
 * "udp://HOST:PORT[/PREFIX]"
 * where:
 * - HOST: IPv4, IPv6 or hostname
 * - PORT: server port
 * - PREFIX: optional (but recommended) prefix telling udp2log how to route
 * the log event
 *
 * When not targeting a udp2log stream this class will act as a drop-in
 * replacement for Monolog's StreamHandler.
 *
 * @since 1.24
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2013 Bryan Davis and Wikimedia Foundation.
 */
class MwLogHandler extends \Monolog\Handler\AbstractProcessingHandler {

	/**
	 * Log sink descriptor
	 * @var string $uri
	 */
	protected $uri;

	/**
	 * Log sink
	 * @var resource $sink
	 */
	protected $sink;

	/**
	 * @var string $error
	 */
	protected $error;

	/**
	 * @var string $host
	 */
	protected $host;

	/**
	 * @var int $port
	 */
	protected $port;

	/**
	 * @var string $prefix
	 */
	protected $prefix;


	/**
	 * @param string $stream Stream URI
	 * @param int $level Minimum logging level that will trigger handler
	 * @param bool $bubble Can handled meesages bubble up the handler stack?
	 */
	public function __construct(
		$stream, $level = \Monolog\Logger::DEBUG, $bubble = true
	) {
		parent::__construct( $level, $bubble );
		$this->uri = $stream;
	}


	/**
	 * Open the log sink described by our stream URI.
	 */
	protected function openSink() {
		if ( !$this->uri ) {
			throw new LogicException(
				'Missing stream uri, the stream can not be opened.' );
		}
		$this->error = null;
		set_error_handler( array( $this, 'errorTrap' ) );

		if ( substr( $this->uri, 0, 4 ) == 'udp:' ) {
			$parsed = parse_url( $this->uri );
			if ( !isset( $parsed['host'] ) ) {
				throw new UnexpectedValueException( sprintf(
					'Udp transport "%s" must specify a host', $this->uri
				) );
			}
			if ( !isset( $parsed['port'] ) ) {
				throw new UnexpectedValueException( sprintf(
					'Udp transport "%s" must specify a port', $this->uri
				) );
			}

			$this->host = $parsed['host'];
			$this->port = $parsed['port'];
			$this->prefix = '';

			if ( isset( $parsed['path'] ) ) {
				$this->prefix = ltrim( $parsed['path'], '/' );
			}

			if ( filter_var( $this->host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
				$domain = AF_INET6;

			} else {
				$domain = AF_INET;
			}

			$this->sink = socket_create( $domain, SOCK_DGRAM, SOL_UDP );

		} else {
			$this->sink = fopen( $this->uri, 'a' );
		}
		restore_error_handler();

		if ( !is_resource( $this->sink ) ) {
			$this->sink = null;
			throw new UnexpectedValueException( sprintf(
				'The stream or file "%s" could not be opened: %s',
				$this->uri, $this->error
			) );
		}
	}


	/**
	 * Custom error handler.
	 * @param int $code Error number
	 * @param string $msg Error message
	 */
	protected function errorTrap( $code, $msg ) {
		$this->error = $msg;
	}


	/**
	 * Should we use UDP to send messages to the sink?
	 * @return bool
	 */
	protected function useUdp() {
		return $this->host !== null;
	}


	protected function write( array $record ) {
		if ( $this->sink === null ) {
			$this->openSink();
		}

		$text = (string) $record['formatted'];
		if ( $this->useUdp() ) {

			// Clean it up for the multiplexer
			if ( $this->prefix !== '' ) {
				$text = preg_replace( '/^/m', "{$this->prefix} ", $text );

				// Limit to 64KB
				if ( strlen( $text ) > 65506 ) {
					$text = substr( $text, 0, 65506 );
				}

				if ( substr( $text, -1 ) != "\n" ) {
					$text .= "\n";
				}

			} elseif ( strlen( $text ) > 65507 ) {
				$text = substr( $text, 0, 65507 );
			}

			socket_sendto(
				$this->sink, $text, strlen( $text ), 0, $this->host, $this->port );

		} else {
			fwrite( $this->sink, $text );
		}
	}


	public function close() {
		if ( is_resource( $this->sink ) ) {
			if ( $this->useUdp() ) {
				socket_close( $this->sink );

			} else {
				fclose( $this->sink );
			}
		}
		$this->sink = null;
	}

}
