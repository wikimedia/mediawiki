<?

# ISBNs in wiki pages will create links to this page, with
# the ISBN passed in via the query string.

function wfSpecialBooksources()
{
	$isbn = $_REQUEST["isbn"];

	$bsl = new BookSourceList( $isbn );
	$bsl->show();
}

class BookSourceList {

	var $mIsbn;

	function BookSourceList( $isbn )
	{
		$this->mIsbn = $isbn;
	}

	function show()
	{
		global $wgOut, $wgUser, $wgLang;
		global $ip, $wpBlockAddress, $wpBlockReason;

		$wgOut->setPagetitle( wfMsg( "booksources" ) );
		$wgOut->addWikiText( wfMsg( "booksourcetext" ) );

		# If ISBN is blank, just show a list of links to the
		# home page of the various book sites.  Otherwise, show
		# a list of links directly to the book.

		$s = "<ul>\n";
		$bs = $wgLang->getBookstoreList() ;
		$bsn = array_keys ( $bs ) ;
		foreach ( $bsn as $name ) {
			$adr = $bs[$name] ;
			if ( ! $this->mIsbn ) {
				$adr = explode ( ":" , $adr , 2 ) ;
				$adr = explode ( "/" , $adr[1] ) ;
				$a = "" ;
				while ( $a == "" ) $a = array_shift ( $adr ) ;
				$adr = "http://".$a ;
			} else {
				$adr = str_replace ( "$1" , $this->mIsbn , $adr ) ;
			}
			$s .= "<li><a href=\"{$adr}\">{$name}</a></li>\n" ;
			}
		$s .= "</ul>\n";

		$wgOut->addHTML( $s );
	}
}

?>
