<?php
/**
 * JSON Content Model
 *
 * This class requires the root structure to be an object (not primitives or arrays).
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

	/**
	 * @since 1.25
	 * @var Status
	 */
	protected $parsedJson;

	public function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * Decodes the JSON into a PHP associative array.
	 * @deprecated since 1.25
	 * @return array|null
	 */
	public function getJsonData() {
		return FormatJson::decode( $this->getNativeData(), true );
	}

	/**
	 * Decodes the JSON string into a PHP object.
	 *
	 * @deprecated since 1.25
	 * @return Status
	 */
	public function getData() {
		if ( $this->parsedJson === null ) {
			$this->parsedJson = FormatJson::parse( $this->getNativeData() );
		}
		return $this->parsedJson;
	}

	/**
	 * @return bool Whether content is valid.
	 */
	public function isValid() {
		return $this->getData()->isGood() && is_object( $this->getData()->getValue() );
	}

	/**
	 * Pretty-print JSON.
	 *
	 * If called before validation, it may return JSON "null".
	 *
	 * @return string
	 */
	public function beautifyJSON() {
		return FormatJson::encode( $this->getData()->getValue(), true );
	}

	/**
	 * Beautifies JSON prior to save.
	 *
	 * @param Title $title Title
	 * @param User $user User
	 * @param ParserOptions $popts
	 * @return JsonContent
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		// FIXME: WikiPage::doEditContent invokes PST before validation. As such, native data
		// may be invalid (though PST result is discarded later in that case).
		if ( !$this->isValid() ) {
			return $this;
		}

		return new static( $this->beautifyJSON() );
	}

	/**
	 * Set the HTML and add the appropriate styles
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
		// FIXME: WikiPage::doEditContent generates parser output before validation.
		// As such, native data may be invalid (though output is discarded later in that case).
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
