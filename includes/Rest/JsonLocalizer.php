<?php

namespace MediaWiki\Rest;

use Wikimedia\Message\MessageValue;

/**
 * Utility class for json localization needs in the REST API.
 *
 * Much of this involves replacing custom name/value pairs (herein referred to as i18n pairs) with
 * translated standard name/value pairs (herein referred to as schema pairs). For example, OpenAPI
 * specifications include a "description" field represented as a name/value pair, like:
 *   "description": "My description".
 * We allow a "x-i18n-description" field, whose value is a MediaWiki message key, like:
 *   "x-i18n-description": "rest-my-description-message-key"
 * Functions in this class will replace the "x-i18n-description" with a translated "description".
 */
class JsonLocalizer {
	private ResponseFactory $responseFactory;

	private const LOCALIZATION_PREFIX = 'x-i18n-';

	/**
	 * @param ResponseFactory $responseFactory
	 *
	 * @internal
	 */
	public function __construct(
		ResponseFactory $responseFactory
	) {
		$this->responseFactory = $responseFactory;
	}

	/**
	 * Recursively localizes name/value pairs, where the name begins with "x-i18-n-"
	 *
	 * @param array $json the input json, as a PHP array
	 *
	 * @return array the adjusted json, or the unchanged json if no adjustments were made
	 */
	public function localizeJson( array $json ): array {
		return $this->localizeJsonPairs( $json, self::LOCALIZATION_PREFIX );
	}

	/**
	 * Recursively localizes name/value pairs. Pairs to be localized are identified by prefix,
	 * and values must be message keys.
	 *
	 * The resulting key has the prefix removed, and a localized value. For example this pair:
	 *   'x-i18n-description' => 'mw-rest-my-description-message-key'
	 * Would be localized to something like:
	 *   'description' => 'My Description'
	 *
	 * @param array $json the input json, as a PHP array
	 * @param string $i18nPrefix keys beginning with this prefix will be localized
	 *
	 * @return array the adjusted json, or the unchanged json if no adjustments were made
	 */
	private function localizeJsonPairs( array $json, string $i18nPrefix ): array {
		// Use a reference for $value, because it is potentially modified within the loop.
		foreach ( $json as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->localizeJsonPairs( $value, $i18nPrefix );
			} elseif ( str_starts_with( $key, $i18nPrefix ) ) {
				$newKey = substr( $key, strlen( $i18nPrefix ) );

				// Add the description to the top of the json, for visibility in raw specs
				$msg = new MessageValue( $value );
				$pair = [ $newKey => $msg ];
				$json = $pair + $json;
				$json[$newKey] = $this->getFormattedMessage( $msg );

				unset( $json[$key] );
			}
		}
		return $json;
	}

	/**
	 * Returns the localized value if possible, or the non-localized value if one is
	 * available, or null otherwise. Translates only top-level keys. Useful when the value
	 * corresponding to the input key may be either a string to be used as-is or a MessageValue
	 * object to be localized.
	 *
	 * @param array $json the input json, as a PHP array
	 * @param string $key key name of the field
	 *
	 * @return ?string
	 */
	public function localizeValue( array $json, string $key ): ?string {
		$value = null;

		if ( array_key_exists( $key, $json ) ) {
			if ( $json[ $key ] instanceof MessageValue ) {
				$value = $this->getFormattedMessage( $json[ $key ] );
			} else {
				$value = $json[ $key ];
			}
		}

		return $value;
	}

	/**
	 * Tries to return one formatted string for a message key or message value object.
	 *
	 * @param string|MessageValue $message the message format, or a key representing it
	 *
	 * @return string
	 */
	public function getFormattedMessage( $message ): string {
		if ( !$message instanceof MessageValue ) {
			$message = new MessageValue( $message );
		}

		// TODO: consider if we want to request a specific preferred language
		return $this->responseFactory->getFormattedMessage( $message );
	}

}
