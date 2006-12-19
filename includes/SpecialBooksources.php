<?php

/**
 * Special page outputs information on sourcing a book with a particular ISBN
 * The parser creates links to this page when dealing with ISBNs in wikitext
 *
 * @package MediaWiki
 * @subpackage Special pages
 * @author Rob Church <robchur@gmail.com>
 * @todo Validate ISBNs using the standard check-digit method
 */
class SpecialBookSources extends SpecialPage {

	/**
	 * ISBN passed to the page, if any
	 */
	private $isbn = '';
	
	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'Booksources' );
	}
	
	/**
	 * Show the special page
	 *
	 * @param $isbn ISBN passed as a subpage parameter
	 */
	public function execute( $isbn = false ) {
		global $wgOut, $wgRequest;
		$this->setHeaders();
		$this->isbn = $this->cleanIsbn( $isbn ? $isbn : $wgRequest->getText( 'isbn' ) );
		$wgOut->addWikiText( wfMsgNoTrans( 'booksources-summary' ) );
		$wgOut->addHtml( $this->makeForm() );
		if( strlen( $this->isbn) > 0 )
			$wgOut->addHtml( $this->makeList() );
	}
	
	/**
	 * Trim ISBN and remove characters which aren't required
	 *
	 * @param $isbn Unclean ISBN
	 * @return string
	 */
	private function cleanIsbn( $isbn ) {
		return trim( preg_replace( '![^0-9X]!', '', $isbn ) );
	}
	
	/**
	 * Generate a form to allow users to enter an ISBN
	 *
	 * @return string
	 */
	private function makeForm() {
		global $wgScript;
		$title = self::getTitleFor( 'Booksources' );
		$form  = '<fieldset><legend>' . wfMsgHtml( 'booksources-search-legend' ) . '</legend>';
		$form .= Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$form .= Xml::hidden( 'title', $title->getPrefixedText() );
		$form .= '<p>' . Xml::inputLabel( wfMsg( 'booksources-isbn' ), 'isbn', 'isbn', 20, $this->isbn );
		$form .= '&nbsp;' . Xml::submitButton( wfMsg( 'booksources-go' ) ) . '</p>';
		$form .= Xml::closeElement( 'form' );
		$form .= '</fieldset>';
		return $form;
	}
	
	/**
	 * Generate the list of book sources
	 *
	 * @return string
	 */
	private function makeList() {
		global $wgOut, $wgContLang;
		
		# Check for a local page such as Project:Book_sources and use that if available
		$title = Title::makeTitleSafe( NS_PROJECT, wfMsg( 'booksources' ) ); # Should this be wfMsgForContent()? -- RC
		if( is_object( $title ) && $title->exists() ) {
			$rev = Revision::newFromTitle( $title );
			return $wgOut->parse( str_replace( 'MAGICNUMBER', $this->isbn, $rev->getText() ) );
		}
		
		# Fall back to the defaults given in the language file
		$html  = $wgOut->parse( wfMsg( 'booksources-text' ) );
		$html .= '<ul>';
		$items = $wgContLang->getBookstoreList();
		foreach( $items as $label => $url )
			$html .= $this->makeListItem( $label, $url );
		$html .= '</ul>';
		return $html;		
	}
	
	/**
	 * Format a book source list item
	 *
	 * @param $label Book source label
	 * @param $url Book source URL
	 * @return string
	 */
	private function makeListItem( $label, $url ) {
		$url = str_replace( '$1', $this->isbn, $url );
		return '<li><a href="' . htmlspecialchars( $url ) . '">' . htmlspecialchars( $label ) . '</a></li>';
	}

}

?>
