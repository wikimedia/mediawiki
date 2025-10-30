<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Logger\Monolog;

use Error;
use MediaWiki\Exception\MWExceptionHandler;
use Monolog\Formatter\LineFormatter as MonologLineFormatter;
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
 * @ingroup Debug
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
	public function format( array $record ): string {
		// Drop the 'private' flag from the context
		unset( $record['context']['private'] );

		// Handle throwables specially: pretty format and remove from context
		// Will be output for a '%exception%' placeholder in format
		$prettyException = '';
		if ( isset( $record['context']['exception'] ) &&
			str_contains( $this->format, '%exception%' )
		) {
			$e = $record['context']['exception'];
			unset( $record['context']['exception'] );

			if ( $e instanceof Throwable ) {
				$prettyException = $this->normalizeException( $e );
			} elseif ( is_array( $e ) ) {
				$prettyException = $this->normalizeExceptionArray( $e );
			} else {
				$prettyException = $this->stringify( $e );
			}
		}

		$output = parent::format( $record );

		if ( str_contains( $output, '%exception%' ) ) {
			$output = str_replace( '%exception%', $prettyException, $output );
		}
		return $output;
	}

	/**
	 * Convert a Throwable to a string.
	 *
	 * @param Throwable $e
	 * @param int $depth
	 * @return string
	 */
	protected function normalizeException( Throwable $e, int $depth = 0 ): string {
		// Can't use typehint. Must match Monolog\Formatter\LineFormatter::normalizeException($e)
		return $this->normalizeExceptionArray( $this->exceptionAsArray( $e ) );
	}

	/**
	 * Convert a throwable to an array of structured data.
	 *
	 * @param Throwable $e
	 * @return array
	 */
	protected function exceptionAsArray( Throwable $e ) {
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

		// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal class is always set
		$which = is_a( $e['class'], Error::class, true ) ? 'Error' : 'Exception';
		$str = "\n[$which {$e['class']}] (" .
			"{$e['file']}:{$e['line']}) {$e['message']}";

		if ( $this->includeStacktraces && $e['trace'] ) {
			$str .= "\n" .
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable trace is always set
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
