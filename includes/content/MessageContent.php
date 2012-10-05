<?php

/**
 * Wrapper allowing us to handle a system message as a Content object. Note that this is generally *not* used
 * to represent content from the MediaWiki namespace, and that there is no MessageContentHandler. MessageContent
 * is just intended as glue for wrapping a message programatically.
 *
 * @since 1.21
 */
class MessageContent extends AbstractContent {

	/**
	 * @var Message
	 */
	protected $mMessage;

	/**
	 * @param Message|String $msg    A Message object, or a message key
	 * @param array|null     $params An optional array of message parameters
	 */
	public function __construct( $msg, $params = null ) {
		# XXX: messages may be wikitext, html or plain text! and maybe even something else entirely.
		parent::__construct( CONTENT_MODEL_WIKITEXT );

		if ( is_string( $msg ) ) {
			$this->mMessage = wfMessage( $msg );
		} else {
			$this->mMessage = clone $msg;
		}

		if ( $params ) {
			$this->mMessage = $this->mMessage->params( $params );
		}
	}

	/**
	 * Returns the message as rendered HTML
	 *
	 * @return string The message text, parsed into html
	 */
	public function getHtml() {
		return $this->mMessage->parse();
	}

	/**
	 * Returns the message as rendered HTML
	 *
	 * @return string The message text, parsed into html
	 */
	public function getWikitext() {
		return $this->mMessage->text();
	}

	/**
	 * Returns the message object, with any parameters already substituted.
	 *
	 * @return Message The message object.
	 */
	public function getNativeData() {
		//NOTE: Message objects are mutable. Cloning here makes MessageContent immutable.
		return clone $this->mMessage;
	}

	/**
	 * @see Content::getTextForSearchIndex
	 */
	public function getTextForSearchIndex() {
		return $this->mMessage->plain();
	}

	/**
	 * @see Content::getWikitextForTransclusion
	 */
	public function getWikitextForTransclusion() {
		return $this->getWikitext();
	}

	/**
	 * @see Content::getTextForSummary
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return substr( $this->mMessage->plain(), 0, $maxlength );
	}

	/**
	 * @see Content::getSize
	 *
	 * @return int
	 */
	public function getSize() {
		return strlen( $this->mMessage->plain() );
	}

	/**
	 * @see Content::copy
	 *
	 * @return Content. A copy of this object
	 */
	public function copy() {
		// MessageContent is immutable (because getNativeData() returns a clone of the Message object)
		return $this;
	}

	/**
	 * @see Content::isCountable
	 *
	 * @return bool false
	 */
	public function isCountable( $hasLinks = null ) {
		return false;
	}

	/**
	 * @see Content::getParserOutput
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput(
		Title $title, $revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {

		if ( $generateHtml ) {
			$html = $this->getHtml();
		} else {
			$html = '';
		}

		$po = new ParserOutput( $html );
		return $po;
	}
}