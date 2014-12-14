<?php
/**
 * Copyright 2010 Wikimedia Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * 		http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed
 * under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 *
 * @file
 * @version 0.1 -- 2010-09-11
 * @author Ori Livneh <ori@wikimedia.org>
 * @copyright Copyright 2014 Wikimedia Foundation
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Buffered, line-oriented UDP writer.
 *
 * Buffers writes until the buffer size exceeds the maximum UDP buffer
 * size or until the stream is flushed.
 *
 * @par Example:
 * @code
 * $fp = fopen('udp://10.64.0.21:1440', 'w');
 * fwrite($fp, $data);
 * @endcode
 */
class UdpStreamWriter {

	private $socket;
	private $host;
	private $port;
	private $buffer;
	private $bufferLength;

	public function stream_open( $path, $mode, $options, &$openedPath ) {
		$url = parse_url( $path );
		if ( empty( $url['host'] ) || empty( $url['port'] ) ) {
			if ( $options & STREAM_REPORT_ERRORS ) {
				trigger_error( __METHOD__ . "could not parse '$path' for host and port", E_USER_WARNING );
			}
			return false;
		}

		$this->host = $url['host'];
		$this->port = $url['port'];
		$domain = strpos( $this->host, '[' ) === 0 ? AF_INET6 : AF_INET;
		$this->socket = socket_create( $domain, SOCK_DGRAM, SOL_UDP );

		if ( $mode !== 'w' ) {
			if ( $options & STREAM_REPORT_ERRORS ) {
				trigger_error( __METHOD__ . "invalid mode '$mode'; only 'w' is supported", E_USER_WARNING );
			}
			return false;
		}

		if ( $options & STREAM_USE_PATH ) {
			$openedPath = "{$this->host}:{$this->port}";
		}

		$this->buffer = '';
		$this->bufferLength = 0;
		return true;
	}

	public function stream_write( $data ) {
		if ( substr( $data, -1 ) != "\n" ) {
			$data .= "\n";
		}

		$length = strlen( $data );

		if ( $length + $this->bufferLength > 65506 ) {
			socket_sendto( $this->socket, $this->buffer, $this->bufferLength, 0, $this->host, $this->port );
			$this->buffer = '';
			$this->bufferLength = 0;
		}

		$this->buffer .= $data;
		$this->bufferLength += $length;

		return $length;
	}

	public function stream_close() {
		socket_close( $this->socket );
	}

	public function stream_flush() {
		$bytesWritten = 0;

		if ( $this->bufferLength ) {
			$bytesWritten += socket_sendto( $this->socket, $this->buffer, $this->bufferLength, MSG_EOR, $this->host, $this->port );
			$this->buffer = '';
			$this->bufferLength = 0;
		}

		return $bytesWritten === $this->bufferLength;
	}
}

stream_wrapper_register( 'udp', 'UdpStreamWriter' );
