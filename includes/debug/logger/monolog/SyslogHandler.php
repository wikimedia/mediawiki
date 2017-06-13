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
 * Log handler that supports sending log events to a syslog server using RFC
 * 3164 formatted UDP packets.
 *
 * Monolog's SyslogUdpHandler creates a partial RFC 5424 header (PRI and
 * VERSION) and relies on the associated formatter to complete the header and
 * message payload. This makes using it with a fixed format formatter like
 * \Monolog\Formatter\LogstashFormatter impossible. Additionally, the
 * direct syslog input for Logstash only handles RFC 3164 syslog packets.
 *
 * This Handler should work with any Formatter. The formatted message will be
 * prepended with an RFC 3164 message header and a partial message body. The
 * resulting packet will looks something like:
 *
 *   <PRI>DATETIME HOSTNAME PROGRAM: MESSAGE
 *
 * This format works as input to rsyslog and can also be processed by the
 * default Logstash syslog input handler.
 *
 * @since 1.25
 * @copyright © 2015 Wikimedia Foundation and contributors
 */
class SyslogHandler extends SyslogUdpHandler {

	/**
	 * @var string $appname
	 */
	private $appname;

	/**
	 * @var string $hostname
	 */
	private $hostname;

	/**
	 * @param string $appname Application name to report to syslog
	 * @param string $host Syslog host
	 * @param int $port Syslog port
	 * @param int $facility Syslog message facility
	 * @param string $level The minimum logging level at which this handler
	 *   will be triggered
	 * @param bool $bubble Whether the messages that are handled can bubble up
	 *   the stack or not
	 */
	public function __construct(
		$appname,
		$host,
		$port = 514,
		$facility = LOG_USER,
		$level = Logger::DEBUG,
		$bubble = true
	) {
		parent::__construct( $host, $port, $facility, $level, $bubble );
		$this->appname = $appname;
		$this->hostname = php_uname( 'n' );
	}

	protected function makeCommonSyslogHeader( $severity ) {
		$pri = $severity + $this->facility;

		// Goofy date format courtesy of RFC 3164 :(
		// RFC 3164 actually specifies that the day of month should be space
		// padded rather than unpadded but this seems to work with rsyslog and
		// Logstash.
		$timestamp = date( 'M j H:i:s' );

		return "<{$pri}>{$timestamp} {$this->hostname} {$this->appname}: ";
	}
}
