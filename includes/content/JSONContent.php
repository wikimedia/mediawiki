<?php
/**
 * JSON Schema Content Model
 *
 * @file
 * @ingroup Extensions
 * @ingroup EventLogging
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 */


/**
 * Represents the content of a JSON Schema article.
 */
class JSONContent extends TextContent {

	function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * Decodes the JSON schema into a PHP associative array.
	 * @return array: Schema array.
	 */
	function getJsonData() {
		return FormatJson::decode( $this->getNativeData(), true );
	}

	/**
	 * @return bool Whether content is valid JSON.
	 */
	function isValid() {
		return $this->getJsonData() !== null;
	}

	/**
	 * Pretty-print JSON
	 *
	 * @param string $json
	 * @return bool|null|string
	 */
	public function beautifyJSON( $json ) {
		$decoded = FormatJson::decode( $json, true );
		if ( !is_array( $decoded ) ) {
			return null;
		}
		return FormatJson::encode( $decoded, true );

	}

	/**
	 * Beautifies JSON prior to save.
	 * @param Title $title Title
	 * @param User $user User
	 * @param ParserOptions $popts
	 * @return JsonSchemaContent
	 */
	function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		return new JSONContent( $this->beautifyJSON( $this->getNativeData() ) );
	}

	/**
	 * Set the HTML and add the appropriate styles
	 *
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
		if ( $generateHtml ) {
			$output->setText( $this->objectTable( $this->getJsonData() ) );
			$output->addModuleStyles( 'mediawiki.content.json' );
		} else {
			$output->setText( '' );
		}
	}
	/**
	 * Constructs an HTML representation of a JSON object.
	 * @param Array $mapping
	 * @return string: HTML.
	 */
	function objectTable( $mapping ) {
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
	 * @return string: HTML.
	 */
	function objectRow( $key, $val ) {
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
