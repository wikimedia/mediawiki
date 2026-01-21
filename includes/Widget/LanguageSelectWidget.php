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
	 *   - string $config['cssclass'] Additional CSS classes for the select element
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

		if ( isset( $this->config['name'] ) && $this->config['name'] !== null ) {
			$selectAttribs['name'] = $this->config['name'];
		}

		if ( isset( $this->config['id'] ) && $this->config['id'] !== null ) {
			$selectAttribs['id'] = $this->config['id'];
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
			foreach ( $languages as $code => $name ) {
				$optionAttribs = [ 'value' => $code ];
				$value = $this->config['value'] ?? null;
				if ( $value === $code ) {
					$optionAttribs['selected'] = 'selected';
				}

				// FIXME: the language labels will changed based on
				// https://phabricator.wikimedia.org/T414468
				$selectHtml .= Html::element( 'option', $optionAttribs, $code . ' - ' . $name );
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
