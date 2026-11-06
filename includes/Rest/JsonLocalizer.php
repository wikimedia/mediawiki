<?php

namespace MediaWiki\Rest;

use Wikimedia\Assert\Assert;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageSpecifier;
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
	private ITextFormatter $formatter;

	private const LOCALIZATION_PREFIX = 'x-i18n-';

	/**
	 * @param ITextFormatter $formatter
	 *
	 * @internal
	 */
	public function __construct(
		ITextFormatter $formatter
	) {
		$this->formatter = $formatter;
	}

	/**
	 * Recursively applies localization to a JSON structure.
	 *
	 * Two mechanisms apply:
	 * - when a key starts with self::LOCALIZATION_PREFIX, its value is
	 *   interpreted as a localization key; the prefix is stripped from the
	 *   key and the value is replaced with the localized string.
	 * - Any values that are MessageSpecifier objects are replaced with
	 *   a localized string.
	 *
	 * For example,
	 *    'x-i18n-description' => 'mw-rest-my-description-message-key'
	 * would be localized to something like:
	 *    'description' => 'My Description'
	 *
	 * @param array $json the input json, as a PHP array
	 *
	 * @return array the adjusted json, or the unchanged json if no adjustments were made
	 */
	public function localizeJson( array $json ): array {
		return $this->localizeJsonPairs( $json, self::LOCALIZATION_PREFIX );
	}

	/**
	 * Recursively localizes name/value pairs.
	 *
	 * @param array $json the input json, as a PHP array
	 * @param string $i18nPrefix keys beginning with this prefix will be localized
	 *
	 * @return array the adjusted json, or the unchanged json if no adjustments were made
	 */
	private function localizeJsonPairs( array $json, string $i18nPrefix ): array {
		// Use a reference for $value, because the loop may modify the value.
		// NOTE that this also makes unset() work for arbitrary keys.
		foreach ( $json as $key => &$value ) {
			if ( str_starts_with( $key, $i18nPrefix ) ) {
				Assert::precondition( is_string( $value ), "values of a property starting " .
					"with $i18nPrefix must be strings representing a message key." );

				$newKey = substr( $key, strlen( $i18nPrefix ) );
				$value = new MessageValue( $value );

				unset( $json[$key] );
				$json[$newKey] = &$value;
			}

			if ( $value instanceof MessageSpecifier ) {
				$value = $this->getFormattedMessage( $value );
			} elseif ( is_array( $value ) ) {
				$value = $this->localizeJsonPairs( $value, $i18nPrefix );
			}
		}
		return $json;
	}

	/**
	 * Returns the localized value if possible, or the non-localized value if one is
	 * available, or null otherwise. Translates only top-level keys. Useful when the value
	 * corresponding to the input key may be either a string to be used as-is or a
	 * MessageSpecifier object to be localized.
	 *
	 * Prior to MediaWiki 1.47, only MessageValue objects were localized, other
	 * implementations of MessageSpecifier were not supported.
	 *
	 * @deprecated since 1.47. Use localizeJson() or getFormattedMessage() instead.
	 *
	 * @param array $json the input json, as a PHP array
	 * @param string $key key name of the field
	 *
	 * @return ?string
	 */
	public function localizeValue( array $json, string $key ): ?string {
		wfDeprecated( __METHOD__, '1.47' );

		$value = null;

		if ( array_key_exists( $key, $json ) ) {
			if ( $json[ $key ] instanceof MessageSpecifier ) {
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
	 * @param string|MessageSpecifier $message the message format, or a key representing it.
	 *   Prior to MediaWiki 1.47 the type was string|MessageValue.
	 *
	 * @return string
	 */
	public function getFormattedMessage( $message ): string {
		if ( !$message instanceof MessageSpecifier ) {
			$message = new MessageValue( $message );
		}

		return $this->formatter->format( $message );
	}

	/**
	 * Returns the language this localizer produces.
	 *
	 * @since 1.47
	 */
	public function getLangCode(): string {
		return $this->formatter->getLangCode();
	}

}
