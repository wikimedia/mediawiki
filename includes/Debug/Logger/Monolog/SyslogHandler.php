<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use DateTimeInterface;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Logger;

/**
 * Write logs to a syslog server, using RFC 3164 formatted UDP packets.
 *
 * This builds on Monolog's SyslogUdpHandler, which creates only a partial
 * RFC 5424 header (PRI and VERSION), with rest intending to come
 * from a specifically configured LineFormatter.
 *
 * This makes use of SyslogUdpHandler it impossible with a formatter like
 * \Monolog\Formatter\LogstashFormatter. Additionally, the direct syslog
 * input for Logstash requires and accepts only RFC 3164 formatted packets.
 *
 * This is a complete syslog handler and should work with any formatter. The
 * formatted message will be prepended with a complete RFC 3164 message
 * header and a partial message body. The resulting packet looks like:
 *
 *   <PRI>DATETIME HOSTNAME PROGRAM: MESSAGE
 *
 * This format works as input to rsyslog and can also be processed by the
 * default Logstash syslog input handler.
 *
 * @since 1.25
 * @ingroup Debug
 * @copyright © 2015 Wikimedia Foundation and contributors
 */
class SyslogHandler extends SyslogUdpHandler {

	private string $appname;
	private string $hostname;

	/**
	 * @param string $appname Application name to report to syslog
	 * @param string $host Syslog host
	 * @param int $port Syslog port
	 * @param int $facility Syslog message facility
	 * @param int $level The minimum logging level at which this handler
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

	protected function makeCommonSyslogHeader( int $severity, DateTimeInterface $datetime ): string {
		$pri = $severity + $this->facility;

		// Goofy date format courtesy of RFC 3164 :(
		// RFC 3164 actually specifies that the day of month should be space
		// padded rather than unpadded but this seems to work with rsyslog and
		// Logstash.
		$timestamp = date( 'M j H:i:s' );

		return "<{$pri}>{$timestamp} {$this->hostname} {$this->appname}: ";
	}
}
