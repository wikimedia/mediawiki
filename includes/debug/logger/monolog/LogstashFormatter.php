<?php

namespace MediaWiki\Logger\Monolog;

/**
 * LogstashFormatter squashes the base message array and the context and extras subarrays into one.
 * This can result in unfortunately named context fields overwriting other data (T145133).
 * This class modifies the standard LogstashFormatter to rename such fields and flag the message.
 * Also changes exception JSON-ification which is done poorly by the standard class.
 *
 * @since 1.29
 */
class LogstashFormatter extends \Monolog\Formatter\LogstashFormatter {

	public const V0 = 0;
	public const V1 = 1;

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
	 * @var int Logstash format version to use
	 */
	protected $version;

	/**
	 * See T247675 for removing this override.
	 *
	 * @param string $applicationName The application that sends the data, used as the "type"
	 * field of logstash
	 * @param string|null $systemName The system/machine name, used as the "source" field of
	 * logstash, defaults to the hostname of the machine
	 * @param string $extraKey The key for extra keys inside logstash "fields", defaults to ''
	 * @param string $contextKey The key for context keys inside logstash "fields", defaults
	 * @param int $version The logstash format version to use, defaults to V0
	 * to ''
	 */
	public function __construct( string $applicationName, ?string $systemName = null,
		string $extraKey = '', string $contextKey = 'ctxt_', $version = self::V0
	) {
		$this->version = $version;
		parent::__construct( $applicationName, $systemName, $extraKey, $contextKey );
	}

	public function format( array $record ): string {
		$record = \Monolog\Formatter\NormalizerFormatter::format( $record );
		if ( $this->version === self::V1 ) {
			$message = $this->formatv1( $record );
		} elseif ( $this->version === self::V0 ) {
			$message = $this->formatV0( $record );
		}

		return $this->toJson( $message ) . "\n";
	}

	/**
	 * Prevent key conflicts
	 * @param array $record
	 * @return array
	 */
	protected function formatV0( array $record ) {
		if ( $this->contextKey !== '' ) {
			return $this->formatMonologV0( $record );
		}

		$context = !empty( $record['context'] ) ? $record['context'] : [];
		$record['context'] = [];
		$formatted = $this->formatMonologV0( $record );

		$formatted['@fields'] = $this->fixKeyConflicts( $formatted['@fields'], $context );

		return $formatted;
	}

	/**
	 * Borrowed from monolog/monolog 1.25.3
	 * https://github.com/Seldaek/monolog/blob/1.x/src/Monolog/Formatter/LogstashFormatter.php#L87-L128
	 *
	 * @param array $record
	 * @return array
	 */
	protected function formatMonologV0( array $record ) {
		if ( empty( $record['datetime'] ) ) {
			$record['datetime'] = gmdate( 'c' );
		}
		$message = [
			'@timestamp' => $record['datetime'],
			'@source' => $this->systemName,
			'@fields' => [],
		];
		if ( isset( $record['message'] ) ) {
			$message['@message'] = $record['message'];
		}
		if ( isset( $record['channel'] ) ) {
			$message['@tags'] = [ $record['channel'] ];
			$message['@fields']['channel'] = $record['channel'];
		}
		if ( isset( $record['level'] ) ) {
			$message['@fields']['level'] = $record['level'];
		}
		if ( $this->applicationName ) {
			$message['@type'] = $this->applicationName;
		}
		if ( isset( $record['extra']['server'] ) ) {
			$message['@source_host'] = $record['extra']['server'];
		}
		if ( isset( $record['extra']['url'] ) ) {
			$message['@source_path'] = $record['extra']['url'];
		}
		if ( !empty( $record['extra'] ) ) {
			foreach ( $record['extra'] as $key => $val ) {
				$message['@fields'][$this->extraKey . $key] = $val;
			}
		}
		if ( !empty( $record['context'] ) ) {
			foreach ( $record['context'] as $key => $val ) {
				$message['@fields'][$this->contextKey . $key] = $val;
			}
		}

		return $message;
	}

	/**
	 * Prevent key conflicts
	 * @param array $record
	 * @return array
	 */
	protected function formatV1( array $record ) {
		if ( $this->contextKey ) {
			return $this->formatMonologV1( $record );
		}

		$context = !empty( $record['context'] ) ? $record['context'] : [];
		$record['context'] = [];
		$formatted = $this->formatMonologV1( $record );

		return $this->fixKeyConflicts( $formatted, $context );
	}

	/**
	 * Borrowed mostly from monolog/monolog 1.25.3
	 * https://github.com/Seldaek/monolog/blob/1.25.3/src/Monolog/Formatter/LogstashFormatter.php#L130-165
	 *
	 * @param array $record
	 * @return array
	 */
	protected function formatMonologV1( array $record ) {
		if ( empty( $record['datetime'] ) ) {
			$record['datetime'] = gmdate( 'c' );
		}
		$message = [
			'@timestamp' => $record['datetime'],
			'@version' => 1,
			'host' => $this->systemName,
		];
		if ( isset( $record['message'] ) ) {
			$message['message'] = $record['message'];
		}
		if ( isset( $record['channel'] ) ) {
			$message['type'] = $record['channel'];
			$message['channel'] = $record['channel'];
		}
		if ( isset( $record['level_name'] ) ) {
			$message['level'] = $record['level_name'];
		}
		// level -> monolog_level is new in 2.0
		// https://github.com/Seldaek/monolog/blob/2.0.2/src/Monolog/Formatter/LogstashFormatter.php#L86-L88
		if ( isset( $record['level'] ) ) {
			$message['monolog_level'] = $record['level'];
		}
		if ( $this->applicationName ) {
			$message['type'] = $this->applicationName;
		}
		if ( !empty( $record['extra'] ) ) {
			foreach ( $record['extra'] as $key => $val ) {
				$message[$this->extraKey . $key] = $val;
			}
		}
		if ( !empty( $record['context'] ) ) {
			foreach ( $record['context'] as $key => $val ) {
				$message[$this->contextKey . $key] = $val;
			}
		}

		return $message;
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
	 * @param int $depth
	 * @return array
	 */
	protected function normalizeException( \Throwable $e, int $depth = 0 ) {
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
