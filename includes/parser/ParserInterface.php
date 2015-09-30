<?php
interface ParserInterface {
	/**
	 * This function returns $oldtext after the content of the section
	 * specified by $section has been replaced with $text. If the target
	 * section does not exist, $oldtext is returned unchanged.
	 *
	 * @param string $oldText Former text of the article
	 * @param string|number $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1').
	 * @param string $newText Replacing text
	 *
	 * @return string Modified text
	 */
	public function replaceSection( $oldText, $sectionId, $newText );

	/**
	 * This function returns the text of a section, specified by a number ($section).
	 * A section is text under a heading like == Heading == or \<h1\>Heading\</h1\>, or
	 * the first section before any such heading (section 0).
	 *
	 * If a section contains subsections, these are also returned.
	 *
	 * @param string $text Text to look in
	 * @param string|number $sectionId Section identifier as a number or string
	 * (e.g. 0, 1 or 'T-1').
	 * @param string $defaultText Default to return if section is not found
	 *
	 * @return string Text of the requested section
	 */
	public function getSection( $text, $sectionId, $defaultText = '' );

	/**
	 * Return the parser that should be used for interface messages
	 * @return MessageParserInterface
	*/
	public function getMessageParser();
	/**
	 * Perform pre-transforms on text.
	 *
	 * @param string $text The text to transform
	 * @param Title $title The Title object for the current article
	 * @param User $user The User object describing the current user
	 * @param ParserOptions $options Parsing options
	 * @param bool $clearState Whether to clear the parser state first
	 * @return string The altered wiki markup
	 */
	public function preSaveTransform( $text, Title $title, User $user,
		ParserOptions $options, $clearState = true );
	/**
	 * @param array $conf
	 */
	public function __construct( $conf = array() );

	/**
	 * Do various kinds of initialisation on the first call of the parser
	 */
	public function firstCallInit();

	/**
	 * Get the ParserOutput object
	 *
	 * @return ParserOutput
	 */
	public function getOutput();

	/**
	 * Return this parser if it is not doing anything, otherwise
	 * get a fresh parser. You can use this method by doing
	 * $myParser = $wgParser->getFreshParser(), or more simply
	 * $wgParser->getFreshParser()->parse( ... );
	 * if you're unsure if $wgParser is safe to use.
	 *
	 * @since 1.24
	 * @return Parser A parser object that is not parsing anything
	 */
	public function getFreshParser();

	/**
	 * Convert wikitext to HTML
	 * Do not call this function recursively.
	 *
	 * @param string $text Text we want to parse
	 * @param Title $title
	 * @param ParserOptions $options
	 * @param bool $linestart
	 * @param bool $clearState
	 * @param int $revid Number to pass in {{REVISIONID}}
	 * @return ParserOutput A ParserOutput
	 */
	public function parse( $text, Title $title, ParserOptions $options, $lineStart = true, $clearState = true, $revId = null );
}
