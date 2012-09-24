<?php

/**
 * @since WD.1
 */
class JavaScriptContent extends TextContent {
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_JAVASCRIPT );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo: make pre-save transformation optional for script pages
		// See bug #32858

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new JavaScriptContent( $pst );
	}


	protected function getHtml( ) {
		$html = "";
		$html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
		$html .= $this->getHighlightHtml( );
		$html .= "\n</pre>\n";

		return $html;
	}
}