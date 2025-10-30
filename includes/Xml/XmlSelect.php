<?php
/**
 * Class for generating HTML <select> elements.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Xml;

use MediaWiki\Html\Html;

/**
 * Class for generating HTML <select> or <datalist> elements.
 */
class XmlSelect {
	/** @var array<array<string,string|int|float|array>> */
	private array $options = [];
	/** @var string|int|float|array|false */
	private $default;
	private string $tagName = 'select';
	/** @var (string|int)[] */
	private array $attributes = [];

	/**
	 * @param string|false $name
	 * @param string|false $id
	 * @param string|int|float|array|false $default
	 */
	public function __construct( $name = false, $id = false, $default = false ) {
		if ( $name ) {
			$this->setAttribute( 'name', $name );
		}

		if ( $id ) {
			$this->setAttribute( 'id', $id );
		}

		$this->default = $default;
	}

	/**
	 * @param string|int|float|array $default
	 */
	public function setDefault( $default ): void {
		$this->default = $default;
	}

	/**
	 * @deprecated since 1.45
	 */
	public function setTagName( string $tagName ): void {
		wfDeprecated( __METHOD__, '1.45' );
		$this->tagName = $tagName;
	}

	/**
	 * @param string $name
	 * @param string|int $value
	 */
	public function setAttribute( string $name, $value ): void {
		$this->attributes[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return string|int|null
	 */
	public function getAttribute( string $name ) {
		return $this->attributes[$name] ?? null;
	}

	/**
	 * @param string $label
	 * @param string|int|float|array|false $value If not given, assumed equal to $label
	 */
	public function addOption( string $label, $value = false ): void {
		$value = $value !== false ? $value : $label;
		$this->options[] = [ $label => $value ];
	}

	/**
	 * This accepts an array of form
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array<string,string|int|float|array> $options
	 */
	public function addOptions( array $options ): void {
		$this->options[] = $options;
	}

	/**
	 * This accepts an array of form:
	 * label => value
	 * label => ( label => value, label => value )
	 *
	 * @param array<string,string|int|float|array> $options
	 * @param string|int|float|array|false $default
	 * @return string HTML
	 */
	public static function formatOptions( array $options, $default = false ): string {
		$data = '';

		foreach ( $options as $label => $value ) {
			if ( is_array( $value ) ) {
				$contents = self::formatOptions( $value, $default );
				$data .= Html::rawElement( 'optgroup', [ 'label' => $label ], $contents ) . "\n";
			} else {
				// If $default is an array, then the <select> probably has the multiple attribute,
				// so we should check if each $value is in $default, rather than checking if
				// $value is equal to $default.
				$selected = is_array( $default ) ? in_array( $value, $default ) : $value === $default;
				$data .= Xml::option( $label, $value, $selected ) . "\n";
			}
		}

		return $data;
	}

	public function getHTML(): string {
		$contents = '';

		foreach ( $this->options as $options ) {
			$contents .= self::formatOptions( $options, $this->default );
		}

		return Html::rawElement( $this->tagName, $this->attributes, rtrim( $contents ) );
	}

	/**
	 * Parse labels and values out of a comma- and colon-separated list of options, such as is used for
	 * expiry and duration lists. Documentation of the format is on translatewiki.net.
	 * @since 1.35
	 * @link https://translatewiki.net/wiki/Template:Doc-mediawiki-options-list
	 * @param string $msg The message to parse.
	 * @return array<string,string> The options array, where keys are option labels (i.e. translations)
	 * and values are option values (i.e. untranslated).
	 */
	public static function parseOptionsMessage( string $msg ): array {
		$options = [];
		foreach ( explode( ',', $msg ) as $option ) {
			$parts = explode( ':', $option, 2 );
			// Normalize options that only have one part.
			$options[ trim( $parts[0] ) ] = trim( $parts[1] ?? $parts[0] );
		}
		return $options;
	}
}
/** @deprecated class alias since 1.43 */
class_alias( XmlSelect::class, 'XmlSelect' );
