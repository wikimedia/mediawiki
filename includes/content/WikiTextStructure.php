<?php

use HtmlFormatter\HtmlFormatter;

/**
 * Class allowing to explore structure of parsed wikitext.
 */
class WikiTextStructure {
	/**
	 * @var string
	 */
	private $openingText;
	/**
	 * @var string
	 */
	private $allText;
	/**
	 * @var string[]
	 */
	private $auxText = [];
	/**
	 * @var ParserOutput
	 */
	private $parserOutput;

	/**
	 * @var string[] selectors to elements that are excluded entirely from search
	 */
	private $excludedElementSelectors = [
		// "it looks like you don't have javascript enabled..." – do not need to index
		'audio', 'video',
		// CSS stylesheets aren't content
		'style',
		// The [1] for references
		'sup.reference',
		// The ↑ next to references in the references section
		'.mw-cite-backlink',
		// Headings are already indexed in their own field.
		'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
		// Collapsed fields are hidden by default so we don't want them showing up.
		'.autocollapse',
		// Content explicitly decided to be not searchable by editors such
		// as custom navigation templates.
		'.navigation-not-searchable'
	];

	/**
	 * @var string[] selectors to elements that are considered auxiliary to article text for search
	 */
	private $auxiliaryElementSelectors = [
		// Thumbnail captions aren't really part of the text proper
		'.thumbcaption',
		// Neither are tables
		'table',
		// Common style for "See also:".
		'.rellink',
		// Common style for calling out helpful links at the top of the article.
		'.dablink',
		// New class users can use to mark stuff as auxiliary to searches.
		'.searchaux',
	];

	/**
	 * @param ParserOutput $parserOutput
	 */
	public function __construct( ParserOutput $parserOutput ) {
		$this->parserOutput = $parserOutput;
	}

	/**
	 * Get headings on the page.
	 * @return string[]
	 * First strip out things that look like references.  We can't use HTML filtering because
	 * the references come back as <sup> tags without a class.  To keep from breaking stuff like
	 *  ==Applicability of the strict mass–energy equivalence formula, ''E'' = ''mc''<sup>2</sup>==
	 * we don't remove the whole <sup> tag.  We also don't want to strip the <sup> tag and remove
	 * everything that looks like [2] because, I dunno, maybe there is a band named Word [2] Foo
	 * or something.  Whatever.  So we only strip things that look like <sup> tags wrapping a
	 * reference.  And since the data looks like:
	 *      Reference in heading <sup>&#91;1&#93;</sup><sup>&#91;2&#93;</sup>
	 * we can not really use HtmlFormatter as we have no suitable selector.
	 */
	public function headings() {
		$headings = [];
		$ignoredHeadings = $this->getIgnoredHeadings();
		foreach ( $this->parserOutput->getSections() as $heading ) {
			$heading = $heading[ 'line' ];

			// Some wikis wrap the brackets in a span:
			// https://en.wikipedia.org/wiki/MediaWiki:Cite_reference_link
			$heading = preg_replace( '/<\/?span>/', '', $heading );
			// Normalize [] so the following regexp would work.
			$heading = preg_replace( [ '/&#91;/', '/&#93;/' ], [ '[', ']' ], $heading );
			$heading = preg_replace( '/<sup>\s*\[\s*\d+\s*\]\s*<\/sup>/is', '', $heading );

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
	 * @return string[]
	 */
	public static function parseSettingsInMessage( $message ) {
		$lines = explode( "\n", $message );
		$lines = preg_replace( '/#.*$/', '', $lines ); // Remove comments
		$lines = array_map( 'trim', $lines );          // Remove extra spaces
		$lines = array_filter( $lines );               // Remove empty lines
		return $lines;
	}

	/**
	 * Get list of heading to ignore.
	 * @return string[]
	 */
	private function getIgnoredHeadings() {
		static $ignoredHeadings = null;
		if ( $ignoredHeadings === null ) {
			$ignoredHeadings = [];
			$source = wfMessage( 'search-ignored-headings' )->inContentLanguage();
			if ( $source->isBlank() ) {
				// Try old version too, just in case
				$source = wfMessage( 'cirrussearch-ignored-headings' )->inContentLanguage();
			}
			if ( !$source->isDisabled() ) {
				$lines = self::parseSettingsInMessage( $source->plain() );
				$ignoredHeadings = $lines;               // Now we just have headings!
			}
		}
		return $ignoredHeadings;
	}

	/**
	 * Extract parts of the text - opening, main and auxiliary.
	 */
	private function extractWikitextParts() {
		if ( !is_null( $this->allText ) ) {
			return;
		}
		$text = $this->parserOutput->getText( [
			'enableSectionEditTokens' => false,
			'allowTOC' => false,
		] );
		if ( strlen( $text ) == 0 ) {
			$this->allText = "";
			// empty text - nothing to seek here
			return;
		}
		$opening = null;

		$this->openingText = $this->extractHeadingBeforeFirstHeading( $text );

		$formatter = new HtmlFormatter( $text );

		// Strip elements from the page that we never want in the search text.
		$formatter->remove( $this->excludedElementSelectors );
		$formatter->filterContent();

		// Strip elements from the page that are auxiliary text.  These will still be
		// searched but matches will be ranked lower and non-auxiliary matches will be
		// preferred in highlighting.
		$formatter->remove( $this->auxiliaryElementSelectors );
		$auxiliaryElements = $formatter->filterContent();
		$this->allText = trim( Sanitizer::stripAllTags( $formatter->getText() ) );
		foreach ( $auxiliaryElements as $auxiliaryElement ) {
			$this->auxText[] =
				trim( Sanitizer::stripAllTags( $formatter->getText( $auxiliaryElement ) ) );
		}
	}

	/**
	 * Get text before first heading.
	 * @param string $text
	 * @return string|null
	 */
	private function extractHeadingBeforeFirstHeading( $text ) {
		$matches = [];
		if ( !preg_match( '/<h[123456]>/', $text, $matches, PREG_OFFSET_CAPTURE ) ) {
			// There isn't a first heading so we interpret this as the article
			// being entirely without heading.
			return null;
		}
		$text = substr( $text, 0, $matches[ 0 ][ 1 ] );
		if ( !$text ) {
			// There isn't any text before the first heading so we declare there isn't
			// a first heading.
			return null;
		}

		$formatter = new HtmlFormatter( $text );
		$formatter->remove( $this->excludedElementSelectors );
		$formatter->remove( $this->auxiliaryElementSelectors );
		$formatter->filterContent();
		$text = trim( Sanitizer::stripAllTags( $formatter->getText() ) );

		if ( !$text ) {
			// There isn't any text after filtering before the first heading so we declare
			// that there isn't a first heading.
			return null;
		}

		return $text;
	}

	/**
	 * Get opening text
	 * @return string
	 */
	public function getOpeningText() {
		$this->extractWikitextParts();
		return $this->openingText;
	}

	/**
	 * Get main text
	 * @return string
	 */
	public function getMainText() {
		$this->extractWikitextParts();
		return $this->allText;
	}

	/**
	 * Get auxiliary text
	 * @return string[]
	 */
	public function getAuxiliaryText() {
		$this->extractWikitextParts();
		return $this->auxText;
	}

	/**
	 * Get the defaultsort property
	 * @return string|null
	 */
	public function getDefaultSort() {
		return $this->parserOutput->getProperty( 'defaultsort' );
	}
}
