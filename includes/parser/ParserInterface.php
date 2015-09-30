<?php
interface ParserInterface {
	/**
	 * Return the parser that should be used for interface messages
	 * @return ParserInterface
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
	 * Wrapper for preprocess()
	 *
	 * @param string $text The text to preprocess
	 * @param ParserOptions $options Options
	 * @param Title|null $title Title object or null to use $wgTitle
	 * @return string
	 */
	public function transformMsg( $text, $options, $title = null );
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
