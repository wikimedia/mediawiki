<?php

interface Preprocessor {
	function __construct( $parser );
	function newFrame();
	function preprocessToObj( $text, $flags = 0 );
}

interface PPFrame {
	const NO_ARGS = 1;
	const NO_TEMPLATES = 2;
	const STRIP_COMMENTS = 4;
	const NO_IGNORE = 8;
	const RECOVER_COMMENTS = 16;

	const RECOVER_ORIG = 27; // = 1|2|8|16 no constant expression support in PHP yet

	/**
	 * Create a child frame
	 */
	function newChild( $args = false, $title = false );

	/**
	 * Expand a document tree node
	 */
	function expand( $root, $flags = 0 );
	
	/**
	 * Implode with flags for expand()
	 */
	function implodeWithFlags( $sep, $flags /*, ... */ );

	/**
	 * Implode with no flags specified
	 */
	function implode( $sep /*, ... */ );

	/**
	 * Makes an object that, when expand()ed, will be the same as one obtained 
	 * with implode()
	 */	
	function virtualImplode( $sep /*, ... */ );

	/**
	 * Virtual implode with brackets
	 */
	function virtualBracketedImplode( $start, $sep, $end /*, ... */ );

	/**
	 * Returns true if there are no arguments in this frame
	 */
	function isEmpty();
	
	function getArgument( $name );

	/**
	 * Returns true if the infinite loop check is OK, false if a loop is detected
	 */
	function loopCheck( $title );

	/**
	 * Return true if the frame is a template frame
	 */
	function isTemplate();
}

interface PPNode {
	function getChildren();
	function getFirstChild();
	function getNextSibling();
	function getChildrenOfType( $type );
	function getLength();
	function item( $i );
	function getName();
	
	function splitArg();
	function splitExt();
	function splitHeading();
}

