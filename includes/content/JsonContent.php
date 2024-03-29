<?php
/**
 * JSON Content Model
 *
 * @file
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */

use MediaWiki\Html\Html;
use MediaWiki\Status\Status;

/**
 * JSON text content that can be viewed and edit directly by users.
 *
 * @since 1.24
 * @newable
 * @stable to extend
 * @ingroup Content
 */
class JsonContent extends TextContent {

	/**
	 * @since 1.25
	 * @var Status
	 */
	protected $jsonParse;

	/**
	 * @param string $text JSON
	 * @param string $modelId
	 * @stable to call
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * Decodes the JSON string.
	 *
	 * Note that this parses it without casting objects to associative arrays.
	 * Objects and arrays are kept as distinguishable types in the PHP values.
	 *
	 * @return Status
	 */
	public function getData() {
		$this->jsonParse ??= FormatJson::parse( $this->getText() );
		return $this->jsonParse;
	}

	/**
	 * @return bool Whether content is valid.
	 */
	public function isValid() {
		return $this->getData()->isGood();
	}

	/**
	 * Pretty-print JSON.
	 *
	 * If called before validation, it may return JSON "null".
	 *
	 * @return string
	 */
	public function beautifyJSON() {
		return FormatJson::encode( $this->getData()->getValue(), "\t", FormatJson::UTF8_OK );
	}

	/**
	 * Construct HTML table representation of any JSON value.
	 *
	 * See also valueCell, which is similar.
	 *
	 * @param mixed $val
	 * @return string HTML.
	 */
	public function rootValueTable( $val ) {
		if ( is_object( $val ) ) {
			return $this->objectTable( $val );
		}

		if ( is_array( $val ) ) {
			// Wrap arrays in another array so that they're visually boxed in a container.
			// Otherwise they are visually indistinguishable from a single value.
			return $this->arrayTable( [ $val ] );
		}

		return Html::rawElement( 'table', [ 'class' => 'mw-json mw-json-single-value' ],
			Html::rawElement( 'tbody', [],
				Html::rawElement( 'tr', [],
					Html::element( 'td', [], $this->primitiveValue( $val ) )
				)
			)
		);
	}

	/**
	 * Create HTML table representing a JSON object.
	 *
	 * @param stdClass $mapping
	 * @return string HTML
	 */
	protected function objectTable( $mapping ) {
		$rows = [];
		$empty = true;

		foreach ( $mapping as $key => $val ) {
			$rows[] = $this->objectRow( $key, $val );
			$empty = false;
		}
		if ( $empty ) {
			$rows[] = Html::rawElement( 'tr', [],
				Html::element( 'td', [ 'class' => 'mw-json-empty' ],
					wfMessage( 'content-json-empty-object' )->text()
				)
			);
		}
		return Html::rawElement( 'table', [ 'class' => 'mw-json' ],
			Html::rawElement( 'tbody', [], implode( '', $rows ) )
		);
	}

	/**
	 * Create HTML table row representing one object property.
	 *
	 * @param string $key
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function objectRow( $key, $val ) {
		$thContent = Html::element( 'span', [], $key );
		$th = Html::rawElement( 'th', [], $thContent );
		$td = $this->valueCell( $val );
		return Html::rawElement( 'tr', [], $th . $td );
	}

	/**
	 * Create HTML table representing a JSON array.
	 *
	 * @param array $mapping
	 * @return string HTML
	 */
	protected function arrayTable( $mapping ) {
		$rows = [];
		$empty = true;

		foreach ( $mapping as $val ) {
			$rows[] = $this->arrayRow( $val );
			$empty = false;
		}
		if ( $empty ) {
			$rows[] = Html::rawElement( 'tr', [],
				Html::element( 'td', [ 'class' => 'mw-json-empty' ],
					wfMessage( 'content-json-empty-array' )->text()
				)
			);
		}
		return Html::rawElement( 'table', [ 'class' => 'mw-json' ],
			Html::rawElement( 'tbody', [], implode( "\n", $rows ) )
		);
	}

	/**
	 * Create HTML table row representing the value in an array.
	 *
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function arrayRow( $val ) {
		$td = $this->valueCell( $val );
		return Html::rawElement( 'tr', [], $td );
	}

	/**
	 * Construct HTML table cell representing any JSON value.
	 *
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function valueCell( $val ) {
		if ( is_object( $val ) ) {
			return Html::rawElement( 'td', [], $this->objectTable( $val ) );
		}

		if ( is_array( $val ) ) {
			return Html::rawElement( 'td', [], $this->arrayTable( $val ) );
		}

		return Html::element( 'td', [ 'class' => 'mw-json-value' ], $this->primitiveValue( $val ) );
	}

	/**
	 * Construct text representing a JSON primitive value.
	 *
	 * @param mixed $val
	 * @return string Text.
	 */
	protected function primitiveValue( $val ) {
		if ( is_string( $val ) ) {
			// Don't FormatJson::encode for strings since we want quotes
			// and new lines to render visually instead of escaped.
			return '"' . $val . '"';
		}
		return FormatJson::encode( $val );
	}
}
