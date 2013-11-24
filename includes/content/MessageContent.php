<?php
/**
 * Wrapper content object allowing to handle a system message as a Content object.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * Wrapper allowing us to handle a system message as a Content object.
 * Note that this is generally *not* used to represent content from the
 * MediaWiki namespace, and that there is no MessageContentHandler.
 * MessageContent is just intended as glue for wrapping a message programatically.
 *
 * @ingroup Content
 */
class MessageContent extends AbstractContent {
	/**
	 * @var Message
	 */
	protected $mMessage;

	/**
	 * @param Message|String $msg A Message object, or a message key
	 * @param array|null $params An optional array of message parameters
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
	 * @param bool $hasLinks
	 * @return bool false
	 */
	public function isCountable( $hasLinks = null ) {
		return false;
	}

	/**
	 * @see Content::getParserOutput
	 *
	 * @param Title $title
	 * @param int $revId Optional revision ID
	 * @param ParserOptions $options
	 * @param bool $generateHtml Wether to generate HTML
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
