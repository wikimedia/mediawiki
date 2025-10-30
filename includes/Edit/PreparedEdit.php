<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Edit;

use MediaWiki\Content\Content;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use RuntimeException;

/**
 * Represents information returned by WikiPage::prepareContentForEdit()
 *
 * @deprecated since 1.32; Since 1.37, use PreparedUpdate instead.
 *
 * @since 1.30
 */
class PreparedEdit {
	/**
	 * Revision ID
	 *
	 * @var int|null
	 */
	public $revid;

	/**
	 * Content after going through pre-save transform
	 *
	 * @var Content|null
	 */
	public $pstContent;

	/**
	 * Content format
	 *
	 * @var string
	 */
	public $format;

	/**
	 * Parser options used to get parser output
	 *
	 * @var ParserOptions
	 */
	public $popts;

	/**
	 * Parser output
	 *
	 * @var ParserOutput|null
	 */
	private $canonicalOutput;

	/**
	 * Content that is being saved (before PST)
	 *
	 * @var Content
	 */
	public $newContent;

	/**
	 * Current content of the page, if any
	 *
	 * @var Content|null
	 */
	public $oldContent;

	/**
	 * Lazy-loading callback to get canonical ParserOutput object
	 *
	 * @var callable
	 */
	public $parserOutputCallback;

	/**
	 * @return ParserOutput Canonical parser output
	 */
	public function getOutput() {
		if ( !$this->canonicalOutput ) {
			$this->canonicalOutput = ( $this->parserOutputCallback )();
		}

		return $this->canonicalOutput;
	}

	/**
	 * Fetch the ParserOutput via a lazy-loaded callback (for backwards compatibility).
	 *
	 * @deprecated since 1.33
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name ) {
		if ( $name === 'output' ) {
			return $this->getOutput();
		} elseif ( $name === 'timestamp' ) {
			return $this->getOutput()->getCacheTime();
		}

		throw new RuntimeException( "Undefined field $name." );
	}
}
