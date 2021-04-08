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

namespace MediaWiki\Logger\Monolog;

use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

/**
 * Log handler that will append the record's channel to a fixed 'application
 * prefix' given at construction time.
 *
 * The use case for this handler is to deprecate udp2log with a localhost
 * syslog endpoint. The application name is then used to reconstruct the
 * message's channel.
 *
 * @since 1.32
 * @copyright © 2019 Wikimedia Foundation and contributors
 */
class MwlogHandler extends SyslogUdpHandler {

	/**
	 * @var string
	 */
	private $appprefix;

	/**
	 * @var string
	 */
	private $hostname;

	/**
	 * @param string $appprefix Application prefix to use, channel will be appended.
	 * @param string $host Syslog host
	 * @param int $port Syslog port
	 * @param int $facility Syslog message facility
	 * @param int $level The minimum logging level at which this handler
	 *   will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble up
	 *   the stack or not
	 */
	public function __construct(
		$appprefix,
		$host,
		$port = 514,
		$facility = LOG_USER,
		$level = Logger::DEBUG,
		$bubble = true
	) {
		parent::__construct( $host, $port, $facility, $level, $bubble );
		$this->appprefix = $appprefix;
		$this->hostname = php_uname( 'n' );
	}

	protected function syslogHeader( $severity, $app ) {
		$pri = $severity + $this->facility;

		// Goofy date format courtesy of RFC 3164 :(
		// RFC 3164 actually specifies that the day of month should be space
		// padded rather than unpadded but this seems to work with rsyslog and
		// Logstash.
		$timestamp = date( 'M j H:i:s' );

		return "<{$pri}>{$timestamp} {$this->hostname} {$app}: ";
	}

	private function splitMessageIntoLines( $message ): array {
		if ( is_array( $message ) ) {
			$message = implode( "\n", $message );
		}

		return preg_split( '/$\R?^/m', (string)$message, -1, PREG_SPLIT_NO_EMPTY );
	}

	protected function write( array $record ): void {
		$lines = $this->splitMessageIntoLines( $record['formatted'] );
		$header = $this->syslogHeader(
			$this->logLevels[$record['level']],
			$this->appprefix . $record['channel'] );

		foreach ( $lines as $line ) {
			$this->socket->write( $line, $header );
		}
	}
}
