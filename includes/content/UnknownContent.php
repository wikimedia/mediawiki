<?php
/**
 * Content object implementation for representing unknown content.
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
 * @since 1.34
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

/**
 * Content object implementation representing unknown content.
 *
 * This can be used to handle content for which no ContentHandler exists on the system,
 * perhaps because the extension that provided it has been removed.
 *
 * UnknownContent instances are immutable.
 *
 * @ingroup Content
 */
class UnknownContent extends AbstractContent {

	/** @var string */
	private $data;

	/**
	 * @param string $data
	 * @param string $model_id The model ID to handle
	 */
	public function __construct( $data, $model_id ) {
		parent::__construct( $model_id );

		$this->data = $data;
	}

	/**
	 * @return Content $this
	 */
	public function copy() {
		// UnknownContent is immutable, so no need to copy.
		return $this;
	}

	/**
	 * Returns an empty string.
	 *
	 * @param int $maxlength
	 *
	 * @return string
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * Returns the data size in bytes.
	 *
	 * @return int
	 */
	public function getSize() {
		return strlen( $this->data );
	}

	/**
	 * Returns false.
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		return false;
	}

	/**
	 * @return string data of unknown format and meaning
	 */
	public function getNativeData() {
		return $this->getData();
	}

	/**
	 * @return string data of unknown format and meaning
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * Returns an empty string.
	 *
	 * @return string The raw text.
	 */
	public function getTextForSearchIndex() {
		return '';
	}

	/**
	 * Returns false.
	 */
	public function getWikitextForTransclusion() {
		return false;
	}

	/**
	 * Fills the ParserOutput with an error message.
	 */
	protected function fillParserOutput( Title $title, $revId,
		ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		$msg = wfMessage( 'unsupported-content-model', [ $this->getModel() ] );
		$html = Html::rawElement( 'div', [ 'class' => 'error' ], $msg->inContentLanguage()->parse() );
		$output->setText( $html );
	}

	/**
	 * Returns false.
	 */
	public function convert( $toModel, $lossy = '' ) {
		return false;
	}

	protected function equalsInternal( Content $that ) {
		if ( !$that instanceof UnknownContent ) {
			return false;
		}

		return $this->getData() == $that->getData();
	}

}
