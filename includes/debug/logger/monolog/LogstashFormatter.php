<?php

namespace MediaWiki\Logger\Monolog;

/**
 * LogstashFormatter squashes the base message array and the context and extras subarrays into one.
 * This can result in unfortunately named context fields overwriting other data (T145133).
 * This class modifies the standard LogstashFormatter to rename such fields and flag the message.
 * Also changes exception JSON-ification which is done poorly by the standard class.
 *
 * Compatible with Monolog 1.x only.
 *
 * @since 1.29
 */
class LogstashFormatter extends \Monolog\Formatter\LogstashFormatter {
	/** @var array Keys which should not be used in log context */
	protected $reservedKeys = [
		// from LogstashFormatter
		'message', 'channel', 'level', 'type',
		// from WebProcessor
		'url', 'ip', 'http_method', 'server', 'referrer',
		// from WikiProcessor
		'host', 'wiki', 'reqId', 'mwversion',
		// from config magic
		'normalized_message',
	];

	/**
	 * Prevent key conflicts
	 * @param array $record
	 * @return array
	 */
	protected function formatV0( array $record ) {
		if ( $this->contextPrefix ) {
			return parent::formatV0( $record );
		}

		$context = !empty( $record['context'] ) ? $record['context'] : [];
		$record['context'] = [];
		$formatted = parent::formatV0( $record );

		$formatted['@fields'] = $this->fixKeyConflicts( $formatted['@fields'], $context );
		return $formatted;
	}

	/**
	 * Prevent key conflicts
	 * @param array $record
	 * @return array
	 */
	protected function formatV1( array $record ) {
		if ( $this->contextPrefix ) {
			return parent::formatV1( $record );
		}

		$context = !empty( $record['context'] ) ? $record['context'] : [];
		$record['context'] = [];
		$formatted = parent::formatV1( $record );

		$formatted = $this->fixKeyConflicts( $formatted, $context );
		return $formatted;
	}

	/**
	 * Check whether some context field would overwrite another message key. If so, rename
	 * and flag.
	 * @param array $fields Fields to be sent to logstash
	 * @param array $context Copy of the original $record['context']
	 * @return array Updated version of $fields
	 */
	protected function fixKeyConflicts( array $fields, array $context ) {
		foreach ( $context as $key => $val ) {
			if (
				in_array( $key, $this->reservedKeys, true ) &&
				isset( $fields[$key] ) && $fields[$key] !== $val
			) {
				$fields['logstash_formatter_key_conflict'][] = $key;
				$key = 'c_' . $key;
			}
			$fields[$key] = $val;
		}
		return $fields;
	}

	/**
	 * Use a more user-friendly trace format than NormalizerFormatter
	 * @param \Throwable $e
	 * @return array
	 */
	protected function normalizeException( $e ) {
		if ( !$e instanceof \Throwable ) {
			throw new \InvalidArgumentException( 'Throwable expected, got '
				. gettype( $e ) . ' / ' . get_class( $e ) );
		}

		$data = [
			'class' => get_class( $e ),
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'file' => $e->getFile() . ':' . $e->getLine(),
			'trace' => \MWExceptionHandler::getRedactedTraceAsString( $e ),
		];

		$previous = $e->getPrevious();
		if ( $previous ) {
			$data['previous'] = $this->normalizeException( $previous );
		}

		return $data;
	}
}
