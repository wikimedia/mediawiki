<?php

class DummyNonTextContent extends AbstractContent {

	public function __construct( $data ) {
		parent::__construct( "testing-nontext" );

		$this->data = $data;
	}

	public function serialize( $format = null ) {
		return serialize( $this->data );
	}

	/**
	 * @return string A string representing the content in a way useful for
	 *   building a full text search index. If no useful representation exists,
	 *   this method returns an empty string.
	 */
	public function getTextForSearchIndex() {
		return '';
	}

	/**
	 * @return string|bool The wikitext to include when another page includes this  content,
	 *  or false if the content is not includable in a wikitext page.
	 */
	public function getWikitextForTransclusion() {
		return false;
	}

	/**
	 * Returns a textual representation of the content suitable for use in edit
	 * summaries and log messages.
	 *
	 * @param int $maxlength Maximum length of the summary text.
	 * @return string The summary text.
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		return '';
	}

	/**
	 * Returns native represenation of the data. Interpretation depends on the data model used,
	 * as given by getDataModel().
	 *
	 * @return mixed The native representation of the content. Could be a string, a nested array
	 *  structure, an object, a binary blob... anything, really.
	 */
	public function getNativeData() {
		return $this->data;
	}

	/**
	 * returns the content's nominal size in bogo-bytes.
	 *
	 * @return int
	 */
	public function getSize() {
		return strlen( $this->data );
	}

	/**
	 * Return a copy of this Content object. The following must be true for the object returned
	 * if $copy = $original->copy()
	 *
	 * * get_class($original) === get_class($copy)
	 * * $original->getModel() === $copy->getModel()
	 * * $original->equals( $copy )
	 *
	 * If and only if the Content object is imutable, the copy() method can and should
	 * return $this. That is,  $copy === $original may be true, but only for imutable content
	 * objects.
	 *
	 * @return Content A copy of this object
	 */
	public function copy() {
		return $this;
	}

	/**
	 * Returns true if this content is countable as a "real" wiki page, provided
	 * that it's also in a countable location (e.g. a current revision in the main namespace).
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains links,
	 * provide this information here, to avoid redundant parsing to find out.
	 * @return bool
	 */
	public function isCountable( $hasLinks = null ) {
		return false;
	}

	/**
	 * @param Title $title
	 * @param int|null $revId Unused.
	 * @param null|ParserOptions $options
	 * @param bool $generateHtml Whether to generate Html (default: true). If false, the result
	 *  of calling getText() on the ParserOutput object returned by this method is undefined.
	 *
	 * @return ParserOutput
	 */
	public function getParserOutput( Title $title, $revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		return new ParserOutput( $this->serialize() );
	}

	/**
	 * @see AbstractContent::fillParserOutput()
	 *
	 * @param Title $title Context title for parsing
	 * @param int|null $revId Revision ID (for {{REVISIONID}})
	 * @param ParserOptions $options
	 * @param bool $generateHtml Whether or not to generate HTML
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput( Title $title, $revId,
			ParserOptions $options, $generateHtml, ParserOutput &$output ) {
		$output = new ParserOutput( $this->serialize() );
	}
}
