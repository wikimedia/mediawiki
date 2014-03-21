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
 * PSR-3 logger that mimics the historic implementation of MediaWiki's
 * wfErrorLog logging implementation.
 *
 * This logger is configured by the following global configuration variables:
 * - `$wgDebugLogFile`
 * - `$wgDebugLogGroups`
 * - `$wgDBerrorLog`
 * - `$wgDBerrorLogTZ`
 *
 * See documentation in DefaultSettings.php for detailed explanations of each
 * variable.
 *
 * @see MWLogger
 * @since 1.24
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
class MWLoggerLegacyLogger extends \Psr\Log\AbstractLogger {

	/**
	 * @var string $channel
	 */
	protected $channel;


	/**
	 * @param string $channel
	 */
	public function __construct( $channel ) {
		$this->channel = $channel;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param string|int $level
	 * @param string $message
	 * @param array $context
	 */
	public function log( $level, $message, array $context = array() ) {
		if ( self::shouldEmit( $this->channel, $message, $context ) ) {
			$text = self::format( $this->channel, $message, $context );
			$destination = self::destination( $this->channel, $message, $context );
			self::emit( $text, $destination );
		}
	}


	/**
	 * Determine if the given message should be emitted or not.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 * @return bool True if message should be sent to disk/network, false
	 * otherwise
	 */
	protected static function shouldEmit( $channel, $message, $context ) {
		global $wgDebugLogFile, $wgDBerrorLog, $wgDebugLogGroups;

		// Default return value is the the same as the historic wfDebug method:
		// emit if $wgDebugLogFile has been set.
		$shouldEmit = $wgDebugLogFile != '';

		if ( $channel === 'wfLogDBError' ) {
			// wfLogDBError messages are emitted if a database log location is
			// specfied.
			$shouldEmit = (bool) $wgDBerrorLog;

		} elseif ( $channel === 'wfErrorLog' ) {
			// All messages on the wfErrorLog channel should be emitted.
			$shouldEmit = true;

		} elseif ( isset( $wgDebugLogGroups[$channel] ) ) {
			$logConfig = $wgDebugLogGroups[$channel];

			if ( is_array( $logConfig ) && isset( $logConfig['sample'] ) ) {
				// Emit randomly with a 1 in 'sample' chance for each message.
				$shouldEmit = mt_rand( 1, $logConfig['sample'] ) === 1;

			} else {
				// Emit unless the config value is explictly false.
				$shouldEmit = $logConfig !== false;
			}

		} elseif ( isset( $context['private'] ) && $context['private'] ) {
			// Don't emit if the message didn't match previous checks based on the
			// channel and the event is marked as private. This check discards
			// messages sent via wfDebugLog() with dest == 'private' and no explicit
			// wgDebugLogGroups configuration.
			$shouldEmit = false;
		}

		return $shouldEmit;
	}


	/**
	 * Format a message.
	 *
	 * Messages to the 'wfDebug', 'wfLogDBError' and 'wfErrorLog' channels
	 * receive special fomatting to mimic the historic output of the functions
	 * of the same name. All other channel values are formatted based on the
	 * historic output of the `wfDebugLog()` global function.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 */
	protected static function format( $channel, $message, $context ) {
		global $wgDebugLogGroups;

		if ( $channel === 'wfDebug' ) {
			$text = self::formatAsWfDebug( $channel, $message, $context );

		} elseif ( $channel === 'wfLogDBError' ) {
			$text = self::formatAsWfLogDBError( $channel, $message, $context );

		} elseif ( $channel === 'wfErrorLog' ) {
			$text = "{$message}\n";

		} elseif ( $channel === 'profileoutput' ) {
			// Legacy wfLogProfilingData formatitng
			$forward = '';
			if ( isset( $context['forwarded_for'] )) {
				$forward = " forwarded for {$context['forwarded_for']}";
			}
			if ( isset( $context['client_ip'] ) ) {
				$forward .= " client IP {$context['client_ip']}";
			}
			if ( isset( $context['from'] ) ) {
				$forward .= " from {$context['from']}";
			}
			if ( $forward ) {
				$forward = "\t(proxied via {$context['proxy']}{$forward})";
			}
			if ( $context['anon'] ) {
				$forward .= ' anon';
			}
			if ( !isset( $context['url'] ) ) {
				$context['url'] = 'n/a';
			}

			$log = sprintf( "%s\t%04.3f\t%s%s\n",
				gmdate( 'YmdHis' ), $context['elapsed'], $context['url'], $forward );

			$text = self::formatAsWfDebugLog(
				$channel, $log . $context['output'], $context );

		} elseif ( !isset( $wgDebugLogGroups[$channel] ) ) {
			$text = self::formatAsWfDebug(
				$channel, "[{$channel}] {$message}", $context );

		} else {
			// Default formatting is wfDebugLog's historic style
			$text = self::formatAsWfDebugLog( $channel, $message, $context );
		}
		return $text;
	}


	/**
	 * Format a message as `wfDebug()` would have formatted it.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 */
	protected static function formatAsWfDebug( $channel, $message, $context ) {
		$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $message );
		if ( isset( $context['prefix'] ) ) {
			$text = "{$context['prefix']}{$text}";
		}
		return "{$text}\n";
	}


	/**
	 * Format a message as `wfLogDBError()` would have formatted it.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 */
	protected static function formatAsWfLogDBError( $channel, $message, $context ) {
		global $wgDBerrorLogTZ;
		static $cachedTimezone = null;

		if ( $wgDBerrorLogTZ && !$cachedTimezone ) {
			$cachedTimezone = new DateTimeZone( $wgDBerrorLogTZ );
		}

		// Workaround for https://bugs.php.net/bug.php?id=52063
		// Can be removed when min PHP > 5.3.2
		if ( $cachedTimezone === null ) {
			$d = date_create( 'now' );
		} else {
			$d = date_create( 'now', $cachedTimezone );
		}
		$date = $d->format( 'D M j G:i:s T Y' );

		$host = wfHostname();
		$wiki = wfWikiID();

		$text = "{$date}\t{$host}\t{$wiki}\t{$message}\n";
		return $text;
	}


	/**
	 * Format a message as `wfDebugLog() would have formatted it.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 */
	protected static function formatAsWfDebugLog( $channel, $message, $context ) {
		$time = wfTimestamp( TS_DB );
		$wiki = wfWikiID();
		$host = wfHostname();
		$text = "{$time} {$host} {$wiki}: {$message}\n";
		return $text;
	}


	/**
	 * Select the appropriate log output destination for the given log event.
	 *
	 * If the event context contains 'destination'
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 */
	protected static function destination( $channel, $message, $context ) {
		global $wgDebugLogFile, $wgDBerrorLog, $wgDebugLogGroups;

		// Default destination is the debug log file as historically used by
		// the wfDebug function.
		$destination = $wgDebugLogFile;

		if ( isset( $context['destination'] ) ) {
			// Use destination explicitly provided in context
			$destination = $context['destination'];

		} elseif ( $channel === 'wfDebug' ) {
			$destination = $wgDebugLogFile;

		} elseif ( $channel === 'wfLogDBError' ) {
			$destination = $wgDBerrorLog;

		} elseif ( isset( $wgDebugLogGroups[$channel] ) ) {
			$logConfig = $wgDebugLogGroups[$channel];

			if ( is_array( $logConfig ) ) {
				$destination = $logConfig['destination'];
			} else {
				$destination = strval( $logConfig );
			}
		}

		return $destination;
	}


	/**
	* Log to a file without getting "file size exceeded" signals.
	*
	* Can also log to UDP with the syntax udp://host:port/prefix. This will send
	* lines to the specified port, prefixed by the specified prefix and a space.
	*
	* @param string $text
	* @param string $file Filename
	* @throws MWException
	*/
	public static function emit( $text, $file ) {
		if ( substr( $file, 0, 4 ) == 'udp:' ) {
			# Needs the sockets extension
			if ( preg_match( '!^udp:(?://)?\[([0-9a-fA-F:]+)\]:(\d+)(?:/(.*))?$!', $file, $m ) ) {
				// IPv6 bracketed host
				$host = $m[1];
				$port = intval( $m[2] );
				$prefix = isset( $m[3] ) ? $m[3] : false;
				$domain = AF_INET6;
			} elseif ( preg_match( '!^udp:(?://)?([a-zA-Z0-9.-]+):(\d+)(?:/(.*))?$!', $file, $m ) ) {
				$host = $m[1];
				if ( !IP::isIPv4( $host ) ) {
					$host = gethostbyname( $host );
				}
				$port = intval( $m[2] );
				$prefix = isset( $m[3] ) ? $m[3] : false;
				$domain = AF_INET;
			} else {
				throw new MWException( __METHOD__ . ': Invalid UDP specification' );
			}

			// Clean it up for the multiplexer
			if ( strval( $prefix ) !== '' ) {
				$text = preg_replace( '/^/m', $prefix . ' ', $text );

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

			$sock = socket_create( $domain, SOCK_DGRAM, SOL_UDP );
			if ( !$sock ) {
				return;
			}

			socket_sendto( $sock, $text, strlen( $text ), 0, $host, $port );
			socket_close( $sock );
		} else {
			wfSuppressWarnings();
			$exists = file_exists( $file );
			$size = $exists ? filesize( $file ) : false;
			if ( !$exists ||
				( $size !== false && $size + strlen( $text ) < 0x7fffffff )
			) {
				file_put_contents( $file, $text, FILE_APPEND );
			}
			wfRestoreWarnings();
		}
	}

}
