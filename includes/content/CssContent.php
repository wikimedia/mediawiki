<?php
/**
 * @since 1.21
 */
class CssContent extends TextContent {
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_CSS );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param $title Title
	 * @param $user User
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;
		// @todo: make pre-save transformation optional for script pages

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

		return new CssContent( $pst );
	}


	protected function getHtml( ) {
		$html = "";
		$html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
		$html .= $this->getHighlightHtml( );
		$html .= "\n</pre>\n";

		return $html;
	}
}
