<?php

# ISBNs in wiki pages will create links to this page, with
# the ISBN passed in via the query string.

function wfSpecialBooksources()
{
	$isbn = preg_replace( '/[^0-9X]/', '', $_REQUEST["isbn"] );

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
		$fname="BookSourceList::show()";

		$wgOut->setPagetitle( wfMsg( "booksources" ) );
		$bstext=wfMsg( "booksourcetext" );

		if($this->mIsbn) {
			$bstitle = Title::newFromText( wfmsg( "booksources" ) );
			$sql = "SELECT cur_text FROM cur " .
				"WHERE cur_namespace=4 and cur_title='" .
				wfStrencode( $bstitle->getDBkey() ) . "'";
			$res = wfQuery( $sql, DB_READ, $fname );
			if( ( $s = wfFetchObject( $res ) ) and ( $s->cur_text != "" ) ) {	
				$bstext = $s->cur_text;
				$bstext = str_replace( "MAGICNUMBER", $this->mIsbn, $bstext );
				$noautolist = 1;
			}
		}

		$wgOut->addWikiText( $bstext );
		
		# If ISBN is blank, just show a list of links to the
		# home page of the various book sites.  Otherwise, show
		# a list of links directly to the book.

		if( !$noautolist ) { # only do this if we haven't already shown [[Wikipedia:Book sources]]
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
				$s .= "<li><a href=\"{$adr}\" class=\"external\">{$name}</a></li>\n" ;
			}
			$s .= "</ul>\n";

			$wgOut->addHTML( $s );
		}
	}
}

?>
