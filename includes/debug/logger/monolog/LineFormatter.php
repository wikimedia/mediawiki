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

use Error;
use Exception;
use Monolog\Formatter\LineFormatter as MonologLineFormatter;
use MWExceptionHandler;
use Throwable;

/**
 * Formats incoming records into a one-line string.
 *
 * An 'exeception' in the log record's context will be treated specially.
 * It will be output for an '%exception%' placeholder in the format and
 * excluded from '%context%' output if the '%exception%' placeholder is
 * present.
 *
 * Throwables that are logged with this formatter will optional have their
 * stack traces appended. If that is done, MWExceptionHandler::redactedTrace()
 * will be used to redact the trace information.
 *
 * @since 1.26
 * @copyright © 2015 Wikimedia Foundation and contributors
 */
class LineFormatter extends MonologLineFormatter {

	/**
	 * @param string|null $format The format of the message
	 * @param string|null $dateFormat The format of the timestamp: one supported by DateTime::format
	 * @param bool $allowInlineLineBreaks Whether to allow inline line breaks in log entries
	 * @param bool $ignoreEmptyContextAndExtra
	 * @param bool $includeStacktraces
	 */
	public function __construct(
		$format = null, $dateFormat = null, $allowInlineLineBreaks = false,
		$ignoreEmptyContextAndExtra = false, $includeStacktraces = false
	) {
		parent::__construct(
			$format, $dateFormat, $allowInlineLineBreaks,
			$ignoreEmptyContextAndExtra
		);
		$this->includeStacktraces( $includeStacktraces );
	}

	/**
	 * @inheritDoc
	 */
	public function format( array $record ) {
		// Drop the 'private' flag from the context
		unset( $record['context']['private'] );

		// Handle exceptions specially: pretty format and remove from context
		// Will be output for a '%exception%' placeholder in format
		$prettyException = '';
		if ( isset( $record['context']['exception'] ) &&
			strpos( $this->format, '%exception%' ) !== false
		) {
			$e = $record['context']['exception'];
			unset( $record['context']['exception'] );

			if ( $e instanceof Throwable || $e instanceof Exception ) {
				$prettyException = $this->normalizeException( $e );
			} elseif ( is_array( $e ) ) {
				$prettyException = $this->normalizeExceptionArray( $e );
			} else {
				$prettyException = $this->stringify( $e );
			}
		}

		$output = parent::format( $record );

		if ( strpos( $output, '%exception%' ) !== false ) {
			$output = str_replace( '%exception%', $prettyException, $output );
		}
		return $output;
	}

	/**
	 * Convert a Throwable to a string.
	 *
	 * @param Exception|Throwable $e
	 * @return string
	 */
	protected function normalizeException( $e ) {
		return $this->normalizeExceptionArray( $this->exceptionAsArray( $e ) );
	}

	/**
	 * Convert a throwable to an array of structured data.
	 *
	 * @param Exception|Throwable $e
	 * @return array
	 */
	protected function exceptionAsArray( $e ) {
		$out = [
			'class' => get_class( $e ),
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
			'trace' => MWExceptionHandler::redactTrace( $e->getTrace() ),
		];

		$prev = $e->getPrevious();
		if ( $prev ) {
			$out['previous'] = $this->exceptionAsArray( $prev );
		}

		return $out;
	}

	/**
	 * Convert an array of Throwable data to a string.
	 *
	 * @param array $e
	 * @return string
	 */
	protected function normalizeExceptionArray( array $e ) {
		$defaults = [
			'class' => 'Unknown',
			'file' => 'unknown',
			'line' => null,
			'message' => 'unknown',
			'trace' => [],
		];
		$e = array_merge( $defaults, $e );

		$which = is_a( $e['class'], Error::class, true ) ? 'Error' : 'Exception';
		$str = "\n[$which {$e['class']}] (" .
			"{$e['file']}:{$e['line']}) {$e['message']}";

		if ( $this->includeStacktraces && $e['trace'] ) {
			$str .= "\n" .
				MWExceptionHandler::prettyPrintTrace( $e['trace'], '  ' );
		}

		if ( isset( $e['previous'] ) ) {
			$prev = $e['previous'];
			while ( $prev ) {
				$prev = array_merge( $defaults, $prev );
				$which = is_a( $prev['class'], Error::class, true ) ? 'Error' : 'Exception';
				$str .= "\nCaused by: [$which {$prev['class']}] (" .
					"{$prev['file']}:{$prev['line']}) {$prev['message']}";

				if ( $this->includeStacktraces && $prev['trace'] ) {
					$str .= "\n" .
						MWExceptionHandler::prettyPrintTrace(
							$prev['trace'], '  '
						);
				}

				$prev = $prev['previous'] ?? null;
			}
		}
		return $str;
	}
}
