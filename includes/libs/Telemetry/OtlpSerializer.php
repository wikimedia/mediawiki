<?php
namespace Wikimedia\Telemetry;

/**
 * Utility class for serializing data in OTLP JSON format.
 *
 * @since 1.43
 * @internal
 */
class OtlpSerializer {
	/**
	 * Map of PHP types to their corresponding type name used in the OTLP JSON format.
	 */
	private const TYPE_MAP = [
		'string' => 'stringValue',
		'integer' => 'intValue',
		'boolean' => 'boolValue',
		'double' => 'doubleValue',
		'array' => 'arrayValue'
	];

	/**
	 * Serialize an associative array into the format expected by the OTLP JSON format.
	 * @param array $keyValuePairs The associative array to serialize
	 * @return array
	 */
	public static function serializeKeyValuePairs( array $keyValuePairs ): array {
		$serialized = [];

		foreach ( $keyValuePairs as $key => $value ) {
			$type = gettype( $value );

			if ( isset( self::TYPE_MAP[$type] ) ) {
				$serialized[] = [
					'key' => $key,
					'value' => [ self::TYPE_MAP[$type] => $value ]
				];
			}
		}

		return $serialized;
	}
}
