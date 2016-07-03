<?php
namespace MediaWiki\Tidy;

use ReplacementArray;
use ParserOutput;
use Parser;

/**
 * Class used to hide mw:editsection tokens from Tidy so that it doesn't break them
 * or break on them. This is a bit of a hack for now, but hopefully in the future
 * we may create a real postprocessor or something that will replace this.
 * It's called wrapper because for now it basically takes over MWTidy::tidy's task
 * of wrapping the text in a xhtml block
 *
 * This re-uses some of the parser's UNIQ tricks, though some of it is private so it's
 * duplicated. Perhaps we should create an abstract marker hiding class.
 *
 * @ingroup Parser
 */
class RaggettWrapper {

	/**
	 * @var ReplacementArray
	 */
	protected $mTokens;

	protected $mMarkerIndex;

	public function __construct() {
		$this->mTokens = null;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public function getWrapped( $text ) {
		$this->mTokens = new ReplacementArray;
		$this->mMarkerIndex = 0;

		// Replace <mw:editsection> elements with placeholders
		$wrappedtext = preg_replace_callback( ParserOutput::EDITSECTION_REGEX,
			[ &$this, 'replaceCallback' ], $text );
		// ...and <mw:toc> markers
		$wrappedtext = preg_replace_callback( '/\<\\/?mw:toc\>/',
			[ &$this, 'replaceCallback' ], $wrappedtext );
		// ... and <math> tags
		$wrappedtext = preg_replace_callback( '/\<math(.*?)\<\\/math\>/s',
			[ &$this, 'replaceCallback' ], $wrappedtext );
		// Modify inline Microdata <link> and <meta> elements so they say <html-link> and <html-meta> so
		// we can trick Tidy into not stripping them out by including them in tidy's new-empty-tags config
		$wrappedtext = preg_replace( '!<(link|meta)([^>]*?)(/{0,1}>)!', '<html-$1$2$3', $wrappedtext );

		// Preserve empty li elements (T49673) by abusing Tidy's datafld hack
		// The whitespace class is as in TY_(InitMap)
		$wrappedtext = preg_replace( "!<li>([ \r\n\t\f]*)</li>!",
			'<li datafld="" class="mw-empty-li">\1</li>', $wrappedtext );

		// Wrap the whole thing in a doctype and body for Tidy.
		$wrappedtext = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"' .
			' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html>' .
			'<head><title>test</title></head><body>' . $wrappedtext . '</body></html>';

		return $wrappedtext;
	}

	/**
	 * @param array $m
	 *
	 * @return string
	 */
	public function replaceCallback( $m ) {
		$marker = Parser::MARKER_PREFIX . "-item-{$this->mMarkerIndex}" . Parser::MARKER_SUFFIX;
		$this->mMarkerIndex++;
		$this->mTokens->setPair( $marker, $m[0] );
		return $marker;
	}

	/**
	 * @param string $text
	 * @return string
	 */
	public function postprocess( $text ) {
		// Revert <html-{link,meta}> back to <{link,meta}>
		$text = preg_replace( '!<html-(link|meta)([^>]*?)(/{0,1}>)!', '<$1$2$3', $text );

		// Remove datafld
		$text = str_replace( '<li datafld=""', '<li', $text );

		// Restore the contents of placeholder tokens
		$text = $this->mTokens->replace( $text );

		return $text;
	}

}
