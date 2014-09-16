<?php
/**
 * JSON Content Model
 *
 * @file
 *
 * @author Ori Livneh <ori@wikimedia.org>
 * @author Kunal Mehta <legoktm@gmail.com>
 * @author Yuri Astrakhan <yurik !@! wikimedia ! org>
 */

/**
 * Represents the content of a JSON content.
 * @since 1.24
 */
class JsonContent extends CodeContent {

	/** @var \Status */
	private $status;

	/** @var int */
	private $decodeOpts;

	public function __construct( $text, $modelId = CONTENT_MODEL_JSON ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * @return bool Whether content is valid JSON.
	 */
	public function isValid() {
		// It is possible for the valid JSON to be ok but not good,
		// implying that it decodes ok but did not pass some additional validation
		return $this->getJson()->isGood();
	}

	public function decodeOptions( $options = false ) {
		if ( $options !== false ) {
			$this->decodeOpts = $options;
		}
		return $this->decodeOpts;
	}

	/**
	 * Decodes JSON content into the PHP data structure.
	 * @deprecated use getJson()
	 * @return array
	 */
	public function getJsonData() {
		return $this->getJson()->getValue();
	}

	/**
	 * Return the status object that will contain parsed data if ok, or the error otherwise
	 * Note to inheritors: override parseJson() instead of this method
	 * @return Status
	 */
	public function getJson() {
		if ( $this->status === null ) {
			$this->status = $this->parseJson();
		}
		return $this->status;
	}

	/**
	 * Convert native data string into the Status object.
	 * On success, status' value is set to the decoded value.
	 * Derived classes may override this method to add additional validation
	 * @return Status
	 */
	protected function parseJson() {
		return FormatJson::parse( $this->getNativeData(), $this->decodeOptions() );
	}

	/**
	 * Beautifies JSON prior to save.
	 * @param Title $title Title
	 * @param User $user User
	 * @param ParserOptions $popts
	 * @return JsonContent
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$data = $this->getJson();
		if ( !$data->isOK() ) {
			return $this;
		}
		// When saving, we are ok to save optimally-encoded, non-pretty-printed data
		$newText = FormatJson::encode( $data->getValue(), false, FormatJson::ALL_OK );
		if ( $this->getNativeData() === $newText ) {
			return $this;
		}
		$new = new static( $newText, $this->getModel() );
		$new->decodeOptions( $this->decodeOptions() );
		return $new;
	}

	/**
	 * @return string JavaScript wrapped in a <pre> tag.
	 */
	protected function getHtml() {
		$data = $this->getJson();
		// Return original data if this is not a valid JSON
		$text = !$data->isOK()
			? $this->getNativeData()
			: FormatJson::encode( $data->getValue(), true, FormatJson::UTF8_OK );
		return $this->codeToPreElement( 'mw-json', $text );
	}

}
