<?php
/**
 * ISBNs in wiki pages will create links to this page, with the ISBN passed
 * in via the query string.
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialBooksources( $par ) {
	global $wgRequest;
	
	$isbn = $par;
	if( empty( $par ) ) {
		$isbn = $wgRequest->getVal( 'isbn' );
	}
	$isbn = preg_replace( '/[^0-9X]/', '', $isbn );
	
	$bsl = new BookSourceList( $isbn );
	$bsl->show();
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class BookSourceList {
	var $mIsbn;

	function BookSourceList( $isbn ) {
		$this->mIsbn = $isbn;
	}

	function show() {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "booksources" ) );
		if( empty( $this->mIsbn ) ) {
			$this->askForm();
		} else {
			$this->showList();
		}
	}
	
	function showList() {
		global $wgOut, $wgUser, $wgLang;
		$fname = "BookSourceList::showList()";
		
		# First, see if we have a custom list setup in
		# [[Wikipedia:Book sources]] or equivalent.
		$bstitle = Title::makeTitleSafe( NS_PROJECT, wfMsg( "booksources" ) );
		$dbr =& wfGetDB( DB_SLAVE );
		$bstext = $dbr->selectField( 'cur', 'cur_text', $bstitle->curCond(), $fname );
		if( $bstext ) {	
			$bstext = str_replace( "MAGICNUMBER", $this->mIsbn, $bstext );
			$wgOut->addWikiText( $bstext );
			return;
		}
		
		# Otherwise, use the list of links in the default Language.php file.
		$s = wfMsg( "booksourcetext" ) . "<ul>\n";
		$bs = $wgLang->getBookstoreList() ;
		$bsn = array_keys ( $bs ) ;
		foreach ( $bsn as $name ) {
			$adr = $bs[$name] ;
			if ( ! $this->mIsbn ) {
				$adr = explode( ":" , $adr , 2 );
				$adr = explode( "/" , $adr[1] );
				$a = "";
				while ( $a == "" ) {
					$a = array_shift( $adr );
				}
				$adr = "http://".$a ;
			} else {
				$adr = str_replace ( "$1" , $this->mIsbn , $adr ) ;
			}
			$name = htmlspecialchars( $name );
			$adr = htmlspecialchars( $adr );
			$s .= "<li><a href=\"{$adr}\" class=\"external\">{$name}</a></li>\n" ;
		}
		$s .= "</ul>\n";

		$wgOut->addHTML( $s );
	}
	
	function askForm() {
		global $wgOut, $wgLang, $wgTitle;
		$fname = "BookSourceList::askForm()";
		
		$action = $wgTitle->escapeLocalUrl();
		$isbn = htmlspecialchars( wfMsg( "isbn" ) );
		$go = htmlspecialchars( wfMsg( "go" ) );
		$out = "<form action=\"$action\" method='post'>
			$isbn: <input name='isbn' id='isbn' />
			<input type='submit' value=\"$go\" />
		</form>";
		$wgOut->addHTML( $out );
	}
}

?>
