<?php
class TimeMap extends SpecialPage
{
	function TimeMap() {
		parent::__construct( "TimeMap" );
	}

	function execute( $par ) {

		global $wgArticlePath;
		global $wgServer;
		global $wgRequest;
		global $wgTimemapNumberOfMementos;

		$requestURL = $wgRequest->getRequestURL();
		$this->setHeaders();


		if ( !$par ) {
			return;
		}

		// getting the title of the page from the request uri
		$waddress = str_replace( '$1', '', $wgArticlePath );

		$tmRevTs = false;
		$tmDir = "next";
		$tmSize = isset( $wgTimemapNumberOfMementos ) ? $wgTimemapNumberOfMementos : 500;

		if ( stripos( $par, $wgServer.$waddress ) == 0 ) {
			$title = str_replace( $wgServer . $waddress, "", $par );
		}
		else if ( stripos( $par, $wgServer. $waddress ) > 0 ) {
			$titleParts = explode( $wgServer.$waddress, $par );

			if ( isset( $titleParts[1] ) ) {
				$title = $titleParts[1];
			}
			else {
				$msg = wfMsgForContent( 'timemap-404-title', $par );
				mmSend( 404, null, $msg );
				exit();
			}

			if ( isset($titleParts[0]) ) {
				$arrayParams = explode( '/', $titleParts[0] );

				if ( isset( $arrayParams[0] ) ) {
					$tmRevTS = wfTimestamp( TS_MW, $arrayParams[0] );
					if ( !$tmRevTS ) {
						$msg = wfMsgForContent( 'timemap-404-title', $par );
						mmSend( 404, null, $msg );
						exit();
					}
				}

				if ( isset( $arrayParams[1] ) ) {
					$tmDir = ( $arrayParams[1] > 0 ) ? "next" : "prev";
				}
			}
		}

		$waddress = str_replace( '/$1', '', $wgArticlePath );



		$objTitle = Title::newFromText( $title );
		$pg_id = $objTitle->getArticleID();
		$title = $objTitle->getPrefixedURL();

		$title = urlencode( $title );

		$splPageTimemapName = SpecialPage::getTitleFor( 'TimeMap' )->getPrefixedText();

		if ( $pg_id > 0 ) {
			// creating a db object to retrieve the old revision id from the db. 
			$dbr = wfGetDB( DB_SLAVE );
			$dbr->begin();

			$wikiaddr = wfExpandUrl( $waddress . "/" . $title );
			$requri = wfExpandUrl( $wgServer . $requestURL );

			// querying the database and building info for the link header. 
			if ( !$tmRevTS ) {
				$xares = $dbr->select( 
						"revision", 
						array( 'rev_id', 'rev_timestamp' ), 
						array( "rev_page=$pg_id" ), 
						__METHOD__, 
						array( "ORDER BY"=>"rev_timestamp DESC", "LIMIT"=>"$tmSize" ) 
						);
			}
			else if ( $tmDir == 'next' ) {
				$xares = $dbr->select( 
						"revision", 
						array( 'rev_id', 'rev_timestamp' ), 
						array( "rev_page=$pg_id", "rev_timestamp>$tmRevTS" ), 
						__METHOD__, 
						array( "ORDER BY"=>"rev_timestamp DESC", "LIMIT"=>"$tmSize" ) 
						);
			}
			else {
				$xares = $dbr->select( 
						"revision", 
						array( 'rev_id', 'rev_timestamp' ), 
						array( "rev_page=$pg_id", "rev_timestamp<$tmRevTS" ), 
						__METHOD__, 
						array( "ORDER BY"=>"rev_timestamp DESC", "LIMIT"=>"$tmSize" ) 
						);
			}


			while ( $xarow = $dbr->fetchObject( $xares ) ) {
				$revTS[] = $xarow->rev_timestamp;
				$revID[] = $xarow->rev_id;
			}

			$cnt = count( $revTS );

			$orgTmUri = wfExpandUrl( $waddress ) ."/". $splPageTimemapName . "/" . $wikiaddr;
			$timegate = str_replace( $splPageTimemapName, SpecialPage::getTitleFor( 'TimeGate' ), $orgTmUri );

			$header = array( 
					"Content-Type" => "application/link-format;charset=UTF-8",
					"Link" => "<" . $requri . ">; anchor=\"" . $wikiaddr . "\"; rel=\"timemap\"; type=\"application/link-format\"" 
					);

			mmSend( 200, $header, null );

			echo "<" . $timegate . ">;rel=\"timegate\", \n";
			echo "<" . $requri . ">;rel=\"timemap self\", \n";

			if ( $tmRevTS )
				echo "<" . wfExpandUrl( $waddress ) . "/" . $splPageTimemapName . "/" . $revTS[0] . "/1/" . $wikiaddr . ">;rel=\"timemap next\", \n";

			if ( $cnt == $tmSize )
				echo "<" . wfExpandUrl( $waddress ) . "/" . $splPageTimemapName . "/" . $revTS[$cnt-1] . "/-1/" . $wikiaddr . ">;rel=\"timemap prev\", \n";

			echo "<" . $wikiaddr . ">;rel=\"original latest-version\", \n";

			for ( $i=$cnt-1; $i>0; $i-- ) {
				$uri = wfAppendQuery( wfExpandUrl( $waddress ), array( "title" => $title, "oldid" => $revID[$i] ) );
				echo "<" . $uri . ">;rel=\"memento\";datetime=\"" . wfTimestamp( TS_RFC2822,  $revTS[$i] ) . "\", \n";
			}

			$uri = wfAppendQuery( wfExpandUrl( $waddress ), array( "title" => $title, "oldid" => $revID[0] ) );
			echo "<" . $uri . ">;rel=\"memento\";datetime=\"" . wfTimestamp( TS_RFC2822,  $revTS[0] ) . "\"";

			$dbr->commit();
			exit();
		}
		else {
			$msg = wfMsgForContent( 'timemap-404-title', $title );
			mmSend( 404, null, $msg );
			exit();
		}
	} 
}
