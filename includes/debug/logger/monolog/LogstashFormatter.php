<?php

namespace MediaWiki\Logger\Monolog;

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
		$message = parent::formatV0( $record );

		$fields = &$message['@fields'];
		$this->fixKeyConflicts( $fields, $context );
		return $message;
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
		$message = parent::formatV1( $record );

		$this->fixKeyConflicts( $message, $context );
		return $message;
	}

	/**
	 * Check whether some context field would overwrite another message key. If so, rename
	 * and flag. (T145133)
	 * @param array $message
	 */
	protected function fixKeyConflicts( array &$message, array &$context ) {
		foreach ( $context as $key => $val ) {
			if (
				in_array( $key, $this->reservedKeys, true ) &&
				isset( $message[$key] ) && $message[$key] !== $val
			) {
				$message['logstash_formatter_key_conflict'][] = $key;
				$key = 'c_' . $val;
			}
			$message[$key] = $val;
		}
	}

	/**
	 * Use a more user-friendly trace format than NormalizerFormatter
	 * @param \Exception|\Throwable $e
	 * @return array
	 */
	protected function normalizeException( $e ) {
		if ( !$e instanceof \Exception && !$e instanceof \Throwable ) {
			throw new \InvalidArgumentException( 'Exception/Throwable expected, got '
				. gettype( $e ) . ' / ' . get_class( $e ) );
		}

		$data = [
			'id' => \WebRequest::getRequestId(),
			'class' => get_class( $e ),
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'file' => $e->getFile() . ':' . $e->getLine(),
			'trace' => \MWExceptionHandler::getRedactedTraceAsString( $e ),
		];

		if ( $previous = $e->getPrevious() ) {
			$data['previous'] = $this->normalizeException( $previous );
		}

		return $data;
	}
}
