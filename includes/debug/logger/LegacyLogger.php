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

namespace MediaWiki\Logger;

use DateTimeZone;
use Exception;
use WikiMap;
use MWDebug;
use MWExceptionHandler;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use UDPTransport;

/**
 * PSR-3 logger that mimics the historic implementation of MediaWiki's former
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
 * @see \MediaWiki\Logger\LoggerFactory
 * @since 1.25
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
class LegacyLogger extends AbstractLogger {

	/**
	 * @var string $channel
	 */
	protected $channel;

	/**
	 * Convert \Psr\Log\LogLevel constants into int for sane comparisons
	 * These are the same values that Monlog uses
	 *
	 * @var array $levelMapping
	 */
	protected static $levelMapping = [
		LogLevel::DEBUG => 100,
		LogLevel::INFO => 200,
		LogLevel::NOTICE => 250,
		LogLevel::WARNING => 300,
		LogLevel::ERROR => 400,
		LogLevel::CRITICAL => 500,
		LogLevel::ALERT => 550,
		LogLevel::EMERGENCY => 600,
	];

	/**
	 * @var array
	 */
	protected static $dbChannels = [
		'DBQuery' => true,
		'DBConnection' => true
	];

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
	 * @return null
	 */
	public function log( $level, $message, array $context = [] ) {
		global $wgDBerrorLog;

		if ( is_string( $level ) ) {
			$level = self::$levelMapping[$level];
		}
		if ( $this->channel === 'DBQuery'
			&& isset( $context['method'] )
			&& isset( $context['master'] )
			&& isset( $context['runtime'] )
		) {
			// Also give the query information to the MWDebug tools
			$enabled = MWDebug::query(
				$message,
				$context['method'],
				$context['master'],
				$context['runtime']
			);
			if ( $enabled ) {
				// If we the toolbar was enabled, return early so that we don't
				// also log the query to the main debug output.
				return;
			}
		}

		// If this is a DB-related error, and the site has $wgDBerrorLog
		// configured, rewrite the channel as wfLogDBError instead.
		// Likewise, if the site does not use  $wgDBerrorLog, it should
		// configurable like any other channel via $wgDebugLogGroups
		// or $wgMWLoggerDefaultSpi.
		if ( isset( self::$dbChannels[$this->channel] )
			&& $level >= self::$levelMapping[LogLevel::ERROR]
			&& $wgDBerrorLog
		) {
			// Format and write DB errors to the legacy locations
			$effectiveChannel = 'wfLogDBError';
		} else {
			$effectiveChannel = $this->channel;
		}

		if ( self::shouldEmit( $effectiveChannel, $message, $level, $context ) ) {
			$text = self::format( $effectiveChannel, $message, $context );
			$destination = self::destination( $effectiveChannel, $message, $context );
			self::emit( $text, $destination );
		}
		if ( !isset( $context['private'] ) || !$context['private'] ) {
			// Add to debug toolbar if not marked as "private"
			MWDebug::debugMsg( $message, [ 'channel' => $this->channel ] + $context );
		}
	}

	/**
	 * Determine if the given message should be emitted or not.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param string|int $level \Psr\Log\LogEvent constant or Monolog level int
	 * @param array $context
	 * @return bool True if message should be sent to disk/network, false
	 * otherwise
	 */
	public static function shouldEmit( $channel, $message, $level, $context ) {
		global $wgDebugLogFile, $wgDBerrorLog, $wgDebugLogGroups;

		if ( is_string( $level ) ) {
			$level = self::$levelMapping[$level];
		}

		if ( $channel === 'wfLogDBError' ) {
			// wfLogDBError messages are emitted if a database log location is
			// specfied.
			$shouldEmit = (bool)$wgDBerrorLog;

		} elseif ( $channel === 'wfDebug' ) {
			// wfDebug messages are emitted if a catch all logging file has
			// been specified. Checked explicitly so that 'private' flagged
			// messages are not discarded by unset $wgDebugLogGroups channel
			// handling below.
			$shouldEmit = $wgDebugLogFile != '';

		} elseif ( isset( $wgDebugLogGroups[$channel] ) ) {
			$logConfig = $wgDebugLogGroups[$channel];

			if ( is_array( $logConfig ) ) {
				$shouldEmit = true;
				if ( isset( $logConfig['sample'] ) ) {
					// Emit randomly with a 1 in 'sample' chance for each message.
					$shouldEmit = mt_rand( 1, $logConfig['sample'] ) === 1;
				}

				if ( isset( $logConfig['level'] ) ) {
					$shouldEmit = $level >= self::$levelMapping[$logConfig['level']];
				}
			} else {
				// Emit unless the config value is explictly false.
				$shouldEmit = $logConfig !== false;
			}

		} elseif ( isset( $context['private'] ) && $context['private'] ) {
			// Don't emit if the message didn't match previous checks based on
			// the channel and the event is marked as private. This check
			// discards messages sent via wfDebugLog() with dest == 'private'
			// and no explicit wgDebugLogGroups configuration.
			$shouldEmit = false;
		} else {
			// Default return value is the same as the historic wfDebug
			// method: emit if $wgDebugLogFile has been set.
			$shouldEmit = $wgDebugLogFile != '';
		}

		return $shouldEmit;
	}

	/**
	 * Format a message.
	 *
	 * Messages to the 'wfDebug' and 'wfLogDBError' channels receive special formatting to mimic the
	 * historic output of the functions of the same name. All other channel values are formatted
	 * based on the historic output of the `wfDebugLog()` global function.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	public static function format( $channel, $message, $context ) {
		global $wgDebugLogGroups, $wgLogExceptionBacktrace;

		if ( $channel === 'wfDebug' ) {
			$text = self::formatAsWfDebug( $channel, $message, $context );

		} elseif ( $channel === 'wfLogDBError' ) {
			$text = self::formatAsWfLogDBError( $channel, $message, $context );

		} elseif ( $channel === 'profileoutput' ) {
			// Legacy wfLogProfilingData formatitng
			$forward = '';
			if ( isset( $context['forwarded_for'] ) ) {
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

		// Append stacktrace of exception if available
		if ( $wgLogExceptionBacktrace && isset( $context['exception'] ) ) {
			$e = $context['exception'];
			$backtrace = false;

			if ( $e instanceof Exception ) {
				$backtrace = MWExceptionHandler::getRedactedTrace( $e );

			} elseif ( is_array( $e ) && isset( $e['trace'] ) ) {
				// Exception has already been unpacked as structured data
				$backtrace = $e['trace'];
			}

			if ( $backtrace ) {
				$text .= MWExceptionHandler::prettyPrintTrace( $backtrace ) .
					"\n";
			}
		}

		return self::interpolate( $text, $context );
	}

	/**
	 * Format a message as `wfDebug()` would have formatted it.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	protected static function formatAsWfDebug( $channel, $message, $context ) {
		$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $message );
		if ( isset( $context['seconds_elapsed'] ) ) {
			// Prepend elapsed request time and real memory usage with two
			// trailing spaces.
			$text = "{$context['seconds_elapsed']} {$context['memory_used']}  {$text}";
		}
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
	 * @return string
	 */
	protected static function formatAsWfLogDBError( $channel, $message, $context ) {
		global $wgDBerrorLogTZ;
		static $cachedTimezone = null;

		if ( !$cachedTimezone ) {
			$cachedTimezone = new DateTimeZone( $wgDBerrorLogTZ );
		}

		$d = date_create( 'now', $cachedTimezone );
		$date = $d->format( 'D M j G:i:s T Y' );

		$host = wfHostname();
		$wiki = WikiMap::getWikiIdFromDbDomain( WikiMap::getCurrentWikiDbDomain() );

		$text = "{$date}\t{$host}\t{$wiki}\t{$message}\n";
		return $text;
	}

	/**
	 * Format a message as `wfDebugLog() would have formatted it.
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 * @return string
	 */
	protected static function formatAsWfDebugLog( $channel, $message, $context ) {
		$time = wfTimestamp( TS_DB );
		$wiki = WikiMap::getWikiIdFromDbDomain( WikiMap::getCurrentWikiDbDomain() );
		$host = wfHostname();
		$text = "{$time} {$host} {$wiki}: {$message}\n";
		return $text;
	}

	/**
	 * Interpolate placeholders in logging message.
	 *
	 * @param string $message
	 * @param array $context
	 * @return string Interpolated message
	 */
	public static function interpolate( $message, array $context ) {
		if ( strpos( $message, '{' ) !== false ) {
			$replace = [];
			foreach ( $context as $key => $val ) {
				$replace['{' . $key . '}'] = self::flatten( $val );
			}
			$message = strtr( $message, $replace );
		}
		return $message;
	}

	/**
	 * Convert a logging context element to a string suitable for
	 * interpolation.
	 *
	 * @param mixed $item
	 * @return string
	 */
	protected static function flatten( $item ) {
		if ( $item === null ) {
			return '[Null]';
		}

		if ( is_bool( $item ) ) {
			return $item ? 'true' : 'false';
		}

		if ( is_float( $item ) ) {
			if ( is_infinite( $item ) ) {
				return ( $item > 0 ? '' : '-' ) . 'INF';
			}
			if ( is_nan( $item ) ) {
				return 'NaN';
			}
			return (string)$item;
		}

		if ( is_scalar( $item ) ) {
			return (string)$item;
		}

		if ( is_array( $item ) ) {
			return '[Array(' . count( $item ) . ')]';
		}

		if ( $item instanceof \DateTime ) {
			return $item->format( 'c' );
		}

		if ( $item instanceof Exception ) {
			return '[Exception ' . get_class( $item ) . '( ' .
				$item->getFile() . ':' . $item->getLine() . ') ' .
				$item->getMessage() . ']';
		}

		if ( is_object( $item ) ) {
			if ( method_exists( $item, '__toString' ) ) {
				return (string)$item;
			}

			return '[Object ' . get_class( $item ) . ']';
		}

		if ( is_resource( $item ) ) {
			return '[Resource ' . get_resource_type( $item ) . ']';
		}

		return '[Unknown ' . gettype( $item ) . ']';
	}

	/**
	 * Select the appropriate log output destination for the given log event.
	 *
	 * If the event context contains 'destination'
	 *
	 * @param string $channel
	 * @param string $message
	 * @param array $context
	 * @return string
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
	 */
	public static function emit( $text, $file ) {
		if ( substr( $file, 0, 4 ) == 'udp:' ) {
			$transport = UDPTransport::newFromString( $file );
			$transport->emit( $text );
		} else {
			\Wikimedia\suppressWarnings();
			$exists = file_exists( $file );
			$size = $exists ? filesize( $file ) : false;
			if ( !$exists ||
				( $size !== false && $size + strlen( $text ) < 0x7fffffff )
			) {
				file_put_contents( $file, $text, FILE_APPEND );
			}
			\Wikimedia\restoreWarnings();
		}
	}

}
