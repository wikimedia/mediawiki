<?php
declare( strict_types=1 );

namespace MediaWiki\Widget;

use MediaWiki\Html\Html;

/**
 * The Language Select Widget is a reusable component in PHP and JS.
 * It automatically transforms native HTML <select> elements into the
 * Codex language selector <LookupLanguageSelector> with the capabilities
 * that <cdx-lookup> have, it also maintains backward compatibility
 * with JavaScript disabled environments.
 *
 * @see https://phabricator.wikimedia.org/T415013
 * @copyright 2026 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license MIT
 */
class LanguageSelectWidget {
	private array $config;

	/**
	 * @param array $config Configuration options
	 *   - array|null $config['languages'] Array of language codes to language names.
	 *     If null, JavaScript will use default supported languages.
	 *   - string $config['name'] Name attribute for form submission
	 *   - string $config['value'] Selected language code
	 *   - string $config['id'] ID attribute for the select element
	 *   - bool $config['disabled'] Whether the select is disabled
	 *   - bool $config['required'] Whether the select is required
	 *   - bool $config['multiple'] multi select language selector
	 *   - string|null $config['placeholder'] Placeholder text for the JavaScript
	 *     input field
	 *   - string $config['cssclass'] Additional CSS classes for the select element
	 *   - callable $config['labelFormat'] Callback to format option labels.
	 *     Receives the language code, and name as parameters, should return a string.
	 *     Default is "code · name".
	 */
	public function __construct( array $config = [] ) {
		$this->config = $config;
	}

	/**
	 * Get the HTML output for the widget.
	 *
	 * @return string HTML
	 */
	public function toString(): string {
		// Create native HTML select element for non-JS support
		$defaultClass = 'mw-widgets-languageSelectWidget mw-widgets-languageSelectWidget-select';
		$cssclass = $this->config['cssclass'] ?? '';
		$selectAttribs = [
			'class' => $cssclass !== '' ? $defaultClass . ' ' . $cssclass : $defaultClass,
		];

		if ( isset( $this->config['name'] ) ) {
			$selectAttribs['name'] = $this->config['name'];
		}

		if ( isset( $this->config['id'] ) ) {
			$selectAttribs['id'] = $this->config['id'];
		}

		if ( isset( $this->config['size'] ) ) {
			$selectAttribs['size'] = $this->config['size'];
		}

		if ( isset( $this->config['placeholder'] ) ) {
			$selectAttribs['data-mw-placeholder'] = $this->config['placeholder'];
		}

		// These are standard HTML attributes that can be passed from HTMLFormField
		$booleanAttribs = [ 'disabled', 'required', 'multiple' ];
		foreach ( $booleanAttribs as $attr ) {
			if ( isset( $this->config[$attr] ) ) {
				$selectAttribs[$attr] = true;
			}
		}

		$languages = $this->config['languages'] ?? null;
		$selectAttribs['data-mw-languages'] = $languages !== null ? json_encode( $languages ) : 'null';

		$selectHtml = Html::openElement( 'select', $selectAttribs );

		// Add options (empty for non-JS fallback when languages is null, JS will handle it)
		if ( $languages !== null ) {
			$value = $this->config['value'] ?? null;
			$selectedValues = is_array( $value ) ? $value : [ $value ];
			$labelFormat = $this->config['labelFormat'] ?? static function ( string $code, string $name ): string {
				return $code . ' · ' . $name;
			};

			foreach ( $languages as $code => $name ) {
				$optionAttribs = [ 'value' => $code ];
				if ( in_array( $code, $selectedValues, true ) ) {
					$optionAttribs['selected'] = 'selected';
				}

				$selectHtml .= Html::element( 'option', $optionAttribs, $labelFormat( $code, $name ) );
			}
		}

		$selectHtml .= Html::closeElement( 'select' );

		return $selectHtml;
	}

	/**
	 * Magic method to convert to string.
	 *
	 * @return string HTML
	 */
	public function __toString(): string {
		return $this->toString();
	}
}
