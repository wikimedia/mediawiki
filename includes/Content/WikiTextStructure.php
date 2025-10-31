<?php

namespace MediaWiki\Content;

use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Sanitizer;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * Class allowing to explore the structure of parsed wikitext.
 */
class WikiTextStructure {

	private ?string $openingText = null;
	private ?string $allText = null;
	/** @var string[] */
	private array $auxText = [];
	private ParserOutput $parserOutput;

	/**
	 * Selectors to elements that are excluded entirely from search
	 */
	private const EXCLUDED_ELEMENT_SELECTORS = [
		// "it looks like you don't have javascript enabled..." – do not need to index
		'audio', 'video',
		// CSS stylesheets aren't content
		'style',
		// The [1] for references from Cite
		'sup.reference',
		// The ↑ next to references in the references section from Cite
		'.mw-cite-backlink',
		// Headings are already indexed in their own field.
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		// Collapsed fields are hidden by default, so we don't want them showing up.
		'.autocollapse',
		// Content explicitly decided to be not searchable by editors such
		// as custom navigation templates.
		'.navigation-not-searchable',
		// User-facing interface code prompting the user to act from WikibaseMediaInfo
		'.wbmi-entityview-emptyCaption',
	];

	/**
	 * Selectors to elements that are considered auxiliary to the article text for search
	 */
	private const AUXILIARY_ELEMENT_SELECTORS = [
		// Thumbnail captions aren't really part of the text proper
		'.thumbcaption',
		'figcaption',
		// Neither are tables
		'table',
		// Common style for "See also:".
		'.rellink',
		// Common style for calling out helpful links at the top of the article.
		'.dablink',
		// New class users can use to mark stuff as auxiliary to searches.
		'.searchaux',
	];

	public function __construct( ParserOutput $parserOutput ) {
		$this->parserOutput = $parserOutput;
	}

	/**
	 * Gets headings from the page.
	 *
	 * @return string[]
	 * First strip out things that look like references.  We can't use HTML filtering because
	 * the references come back as <sup> tags without a class.  To keep from breaking stuff like
	 *  ==Applicability of the strict mass–energy equivalence formula, ''E'' = ''mc''<sup>2</sup>==
	 * we don't remove the whole <sup> tag.
	 *
	 * We also don't want to strip the <sup> tag and remove everything that looks like [2] because,
	 * I don't know, maybe there is a band named Word [2] Foo r something. Whatever.
	 *
	 * So we only strip things that look like <sup> tags wrapping a reference. And since the data
	 * looks like:
	 *      Reference in heading <sup>&#91;1&#93;</sup><sup>&#91;2&#93;</sup>
	 * we can not really use HtmlFormatter as we have no suitable selector.
	 */
	public function headings() {
		$headings = [];
		$tocData = $this->parserOutput->getTOCData();
		if ( $tocData === null ) {
			return $headings;
		}
		$ignoredHeadings = $this->getIgnoredHeadings();
		foreach ( $tocData->getSections() as $heading ) {
			$heading = $heading->line;

			// Some wikis wrap the brackets in a span:
			// https://en.wikipedia.org/wiki/MediaWiki:Cite_reference_link
			$heading = preg_replace( '/<\/?span>/', '', $heading );
			// Normalize [] so the following regexp would work.
			$heading = preg_replace( [ '/&#91;/', '/&#93;/' ], [ '[', ']' ], $heading );
			$heading = preg_replace( '/<sup>\s*\[\s*\d+\s*\]\s*<\/sup>/i', '', $heading );

			// Strip tags from the heading or else we'll display them (escaped) in search results
			$heading = trim( Sanitizer::stripAllTags( $heading ) );

			// Note that we don't take the level of the heading into account - all headings are equal.
			// Except the ones we ignore.
			if ( !in_array( $heading, $ignoredHeadings ) ) {
				$headings[] = $heading;
			}
		}

		return $headings;
	}

	/**
	 * Parse a message content into an array. This function is generally used to
	 * parse settings stored as i18n messages (see search-ignored-headings).
	 *
	 * @param string $message
	 *
	 * @return string[]
	 */
	public static function parseSettingsInMessage( $message ) {
		$lines = explode( "\n", $message );
		// Remove comments
		$lines = preg_replace( '/#.*$/', '', $lines );
		// Remove extra spaces
		$lines = array_map( 'trim', $lines );

		// Remove empty lines
		return array_filter( $lines );
	}

	/**
	 * Gets a list of heading to ignore.
	 *
	 * @return string[]
	 */
	private function getIgnoredHeadings() {
		static $ignoredHeadings = null;
		if ( $ignoredHeadings === null ) {
			$ignoredHeadings = [];
			$source = wfMessage( 'search-ignored-headings' )->inContentLanguage();
			if ( !$source->isDisabled() ) {
				$lines = self::parseSettingsInMessage( $source->plain() );
				// Now we just have headings!
				$ignoredHeadings = $lines;
			}
		}

		return $ignoredHeadings;
	}

	/**
	 * Extract parts of the text - opening, main and auxiliary.
	 */
	private function extractWikitextParts() {
		if ( $this->allText !== null ) {
			return;
		}
		$text = $this->parserOutput->getRawText();
		if ( $text === '' ) {
			$this->allText = "";

			// empty text - nothing to seek here
			return;
		}

		$this->openingText = $this->extractTextBeforeFirstHeading( $text );

		$doc = DOMUtils::parseHTML( $text );

		// Strip elements from the page that we never want in the search text.
		foreach ( self::EXCLUDED_ELEMENT_SELECTORS as $selector ) {
			foreach ( DOMCompat::querySelectorAll( $doc, $selector ) as $element ) {
				$element->parentNode->removeChild( $element );
			}
		}

		// Strip elements from the page that are auxiliary text.  These will still be
		// searched, but matches will be ranked lower and non-auxiliary matches will be
		// preferred in highlighting.
		foreach ( self::AUXILIARY_ELEMENT_SELECTORS as $selector ) {
			foreach ( DOMCompat::querySelectorAll( $doc, $selector ) as $element ) {
				$this->auxText[] = trim( Sanitizer::stripAllTags( DOMCompat::getInnerHTML( $element ) ) );
				$element->parentNode->removeChild( $element );
			}
		}

		$this->allText = trim( Sanitizer::stripAllTags( DOMCompat::getInnerHTML( DOMCompat::getBody( $doc ) ) ) );
	}

	/**
	 * Get text before first heading.
	 *
	 * @param string $text
	 *
	 * @return string|null
	 */
	private function extractTextBeforeFirstHeading( $text ) {
		$matches = [];
		if ( !preg_match( '/<h[123456]\b/', $text, $matches, PREG_OFFSET_CAPTURE ) ) {
			// There isn't a first heading, so we interpret this as the article
			// being entirely without heading.
			return null;
		}
		$text = substr( $text, 0, $matches[ 0 ][ 1 ] );
		if ( !$text ) {
			// There isn't any text before the first heading, so we declare there isn't
			// a first heading.
			return null;
		}

		$doc = DOMUtils::parseHTML( $text );
		foreach ( array_merge( self::EXCLUDED_ELEMENT_SELECTORS, self::AUXILIARY_ELEMENT_SELECTORS ) as $selector ) {
			foreach ( DOMCompat::querySelectorAll( $doc, $selector ) as $element ) {
				$element->parentNode->removeChild( $element );
			}
		}

		$text = trim( Sanitizer::stripAllTags( DOMCompat::getInnerHTML( DOMCompat::getBody( $doc ) ) ) );

		if ( !$text ) {
			// There isn't any text after filtering before the first heading, so we declare
			// that there isn't a first heading.
			return null;
		}

		return $text;
	}

	/**
	 * @return string|null
	 */
	public function getOpeningText() {
		$this->extractWikitextParts();

		return $this->openingText;
	}

	/**
	 * @return string
	 */
	public function getMainText() {
		$this->extractWikitextParts();

		return $this->allText;
	}

	/**
	 * @return string[]
	 */
	public function getAuxiliaryText() {
		$this->extractWikitextParts();

		return $this->auxText;
	}

	/**
	 * Get the "defaultsort" property
	 *
	 * @return string|null
	 */
	public function getDefaultSort() {
		$sort = $this->parserOutput->getPageProperty( 'defaultsort' );
		if ( $sort === false ) {
			return null;
		}

		return $sort;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( WikiTextStructure::class, 'WikiTextStructure' );
