<?php
/**
 * JSON Content Model
 *
 * This class considers primitives and arrays invalid.
 *
 * @file
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */

/**
 * Represents the content of a JSON content.
 * @since 1.24
 */
class JsonContent extends TextContent {
	protected $parsedJson;

	public function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * Decodes the JSON into a PHP associative array.
	 * @return array|null
	 */
	public function getJsonData() {
		if ( $this->parsedJson === null ) {
			// Use parse() and its Status instead of decode() so that we can
			// distinguish its null return value from uninitialised.
			$this->parsedJson = FormatJson::parse( $this->getNativeData(), FormatJson::FORCE_ASSOC );
		}
		return $this->parsedJson->getValue();
	}

	/**
	 * @return bool Whether content is valid JSON.
	 */
	public function isValid() {
		return is_array( $this->getJsonData() );
	}

	/**
	 * Pretty-print JSON.
	 *
	 * If called before validation, it may return false to invidate the
	 * content is invalid.
	 *
	 * @return null|string
	 */
	public function beautifyJSON() {
		if ( !$this->isValid() ) {
			return null;
		}
		if ( !count( $this->getJsonData() ) ) {
			// Don't transform {} to array. These are supposed to be JSON objects, we don't
			// even support arrays.
			return FormatJson::encode( new stdClass(), true );
		}
		return FormatJson::encode( $this->getJsonData(), true );
	}

	/**
	 * Beautifies JSON prior to save.
	 *
	 * WikiPage::doEditContent invokes chain WikiPage::prepareContentForEdit ->
	 * Content::preSaveTransform, before validation. As such, the native data here
	 * may be invalid.
	 *
	 * @param Title $title Title
	 * @param User $user User
	 * @param ParserOptions $popts
	 * @return JsonContent
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return new static( $this->beautifyJSON() );
	}

	/**
	 * Set the HTML and add the appropriate styles
	 *
	 * WikiPage::doEditContent invokes chain WikiPage::prepareContentForEdit ->
	 * Content::getParserOutput -> Content::fillParserOutput, before validation.
	 * As such, the native data here may be invalid.
	 *
	 * @param Title $title
	 * @param int $revId
	 * @param ParserOptions $options
	 * @param bool $generateHtml
	 * @param ParserOutput $output
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		if ( $this->isValid() && $generateHtml ) {
			$output->setText( $this->objectTable( $this->getJsonData() ) );
			$output->addModuleStyles( 'mediawiki.content.json' );
		} else {
			$output->setText( '' );
		}
	}
	/**
	 * Constructs an HTML representation of a JSON object.
	 * @param array $mapping
	 * @return string HTML
	 */
	protected function objectTable( $mapping ) {
		$rows = array();

		foreach ( $mapping as $key => $val ) {
			$rows[] = $this->objectRow( $key, $val );
		}
		return Xml::tags( 'table', array( 'class' => 'mw-json' ),
			Xml::tags( 'tbody', array(), join( "\n", $rows ) )
		);
	}

	/**
	 * Constructs HTML representation of a single key-value pair.
	 * @param string $key
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function objectRow( $key, $val ) {
		$th = Xml::elementClean( 'th', array(), $key );
		if ( is_array( $val ) ) {
			$td = Xml::tags( 'td', array(), self::objectTable( $val ) );
		} else {
			if ( is_string( $val ) ) {
				$val = '"' . $val . '"';
			} else {
				$val = FormatJson::encode( $val );
			}

			$td = Xml::elementClean( 'td', array( 'class' => 'value' ), $val );
		}

		return Xml::tags( 'tr', array(), $th . $td );
	}

}
