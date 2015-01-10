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
	protected $jsonParse;

	/**
	 * @param string $text JSON
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * Decodes the JSON into a PHP associative array.
	 *
	 * @deprecated since 1.25 Use getData instead.
	 * @return array|null
	 */
	public function getJsonData() {
		wfDeprecated( __METHOD__, '1.25' );
		return FormatJson::decode( $this->getNativeData(), true );
	}

	/**
	 * Decodes the JSON string into a PHP object.
	 *
	 * @return Status
	 */
	public function getData() {
		if ( $this->jsonParse === null ) {
			$this->jsonParse = FormatJson::parse( $this->getNativeData() );
		}
		return $this->jsonParse;
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
	 * Set the HTML and add the appropriate styles.
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
		if ( $generateHtml && $this->isValid() ) {
			$output->setText( $this->objectTable( $this->getData()->getValue() ) );
			$output->addModuleStyles( 'mediawiki.content.json' );
		} else {
			$output->setText( '' );
		}
	}

	/**
	 * Construct an HTML representation of a JSON object.
	 *
	 * Called recursively via valueCell().
	 *
	 * @param stdClass $mapping
	 * @return string HTML
	 */
	protected function objectTable( $mapping ) {
		$rows = array();
		$empty = true;

		foreach ( $mapping as $key => $val ) {
			$rows[] = $this->objectRow( $key, $val );
			$empty = false;
		}
		if ( $empty ) {
			$rows[] = Html::rawElement( 'tr', array(),
				Html::element( 'td', array( 'class' => 'mw-json-empty' ),
					wfMessage( 'content-json-empty-object' )->text()
				)
			);
		}
		return Html::rawElement( 'table', array( 'class' => 'mw-json' ),
			Html::rawElement( 'tbody', array(), join( "\n", $rows ) )
		);
	}

	/**
	 * Construct HTML representation of a single key-value pair.
	 * @param string $key
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function objectRow( $key, $val ) {
		$th = Xml::elementClean( 'th', array(), $key );
		$td = self::valueCell( $val );
		return Html::rawElement( 'tr', array(), $th . $td );
	}

	/**
	 * Constructs an HTML representation of a JSON array.
	 *
	 * Called recursively via valueCell().
	 *
	 * @param array $mapping
	 * @return string HTML
	 */
	protected function arrayTable( $mapping ) {
		$rows = array();
		$empty = true;

		foreach ( $mapping as $val ) {
			$rows[] = $this->arrayRow( $val );
			$empty = false;
		}
		if ( $empty ) {
			$rows[] = Html::rawElement( 'tr', array(),
				Html::element( 'td', array( 'class' => 'mw-json-empty' ),
					wfMessage( 'content-json-empty-array' )->text()
				)
			);
		}
		return Html::rawElement( 'table', array( 'class' => 'mw-json' ),
			Html::rawElement( 'tbody', array(), join( "\n", $rows ) )
		);
	}

	/**
	 * Construct HTML representation of a single array value.
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function arrayRow( $val ) {
		$td = self::valueCell( $val );
		return Html::rawElement( 'tr', array(), $td );
	}

	/**
	 * Construct HTML representation of a single value.
	 * @param mixed $val
	 * @return string HTML.
	 */
	protected function valueCell( $val ) {
		if ( is_object( $val ) ) {
			return Html::rawElement( 'td', array(), self::objectTable( $val ) );
		}
		if ( is_array( $val ) ) {
			return Html::rawElement( 'td', array(), self::arrayTable( $val ) );
		}
		if ( is_string( $val ) ) {
			$val = '"' . $val . '"';
		} else {
			$val = FormatJson::encode( $val );
		}

		return Xml::elementClean( 'td', array( 'class' => 'value' ), $val );
	}
}
