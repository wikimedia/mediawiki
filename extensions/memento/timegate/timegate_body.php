<?php
class TimeGate extends SpecialPage
{
	function TimeGate() {
		parent::__construct( "TimeGate" );
	}


	function execute( $par ) {

		global $wgRequest, $wgOut;
		global $wgMementoConfigDeleted;
		global $wgArticlePath;
		global $wgServer;

		$this->setHeaders();

		$requestURL = $wgRequest->getRequestURL();
		$mementoResponse = $wgRequest->response();

		if ( !$par ) {
			$wgOut->addHTML( wfMsg( 'timegate-welcome-message' ) );
			return;
		}

		if ( $_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'HEAD' ) {
			$header = array(
					"Allow" => "GET, HEAD",
					"Vary" => "negotiate, accept-datetime"
					);
			mmSend( 405, $header, null );
			exit();
		}

		$waddress = str_replace( '$1', '', $wgArticlePath );

		// getting the title of the page from the request uri
		$title = str_replace( $wgServer . $waddress, "", $par );

		$waddress = str_replace( '/$1', '', $wgArticlePath );
		$historyuri = $wgServer . $waddress;

		$page_namespace_id = 0;

		$objTitle =  Title::newFromText( $title );
		$pg_id = $objTitle->getArticleID();

		$page_namespace_id = $objTitle->getNamespace();
		$new_title = $objTitle->getPrefixedURL();
		$new_title = urlencode( $new_title );

		if ( $pg_id > 0 ) {
			$this->GetMementoForResource( $pg_id, $historyuri, $new_title );
		}
		// if the title was not found in the page table, the archive table is checked for deleted versions of that article.
		// provided, the variable $wgMementoConfigDeleted is set to true in the LocalSettings.php file. 
		elseif ( $wgMementoConfigDeleted == true ) {
			$this->GetMementoForDeletedResource( $new_title, $page_namespace_id );
		}    
		else {
			$msg = wfMsgForContent( 'timegate-404-title', $new_title );
			$header = array( "Vary" => "negotiate, accept-datetime" );
			mmSend( 404, $header, $msg );
			exit();
		}
	}


	function getMementoForDeletedResource( $new_title, $page_namespace_id ) {
		global $wgArticlePath;


		// creating a db object to retrieve the old revision id from the db. 
		$dbr = wfGetDB( DB_SLAVE );
		$dbr->begin();

		$waddress = str_replace( '/$1', '', $wgArticlePath );

		$res_ar = $dbr->select( 
				'archive', 
				array( 'ar_timestamp' ), 
				array( "ar_title='$new_title'", "ar_namespace=$page_namespace_id" ), 
				__METHOD__, 
				array( 'ORDER BY'=>'ar_timestamp ASC', 'LIMIT'=>'1' ) 
				);

		if ( $dbr->fetchObject( $res_ar ) ) {  
			// checking if a revision exists for the requested date. 
			if (  
					$res_ar_ts = $dbr->select( 
						'archive', 
						array( 'ar_timestamp' ), 
						array( "ar_title='$new_title'", "ar_namespace=$page_namespace_id", "ar_timestamp <= $dt" ), 
						__METHOD__, 
						array( 'ORDER BY'=>'ar_timestamp DESC', "LIMIT"=>"1" ) 
						)
			   ) { 
				$row_ar_ts = $dbr->fetchObject( $res_ar_ts );
				$ar_ts = $row_ar_ts->ar_timestamp;

				if ( $ar_ts ) {
					// redirection is done to the "special page" for deleted articles. 
					$historyuri = wfAppendQuery( 
							wfExpandUrl( $waddress ), 
							array( "title"=>SpecialPage::getTitleFor( 'Undelete' ), "target"=>$new_title, "timestamp"=>$ar_ts ) 
							);
					$header = array( "Location" => $historyuri );
					$dbr->commit();
					mmSend( 302, $header, null );
					exit();
				}
			}
		}    
		else {
			$msg = wfMsgForContent( 'timegate-404-title', $title );
			$header = array( "Vary" => "negotiate, accept-datetime" );

			$dbr->commit();

			mmSend( 404, $header, $msg );
			exit();
		}
	}



	function parseRequestDateTime( $first, $last, $Link ) {

		global $wgRequest;

		// getting the datetime from the http header
		$raw_dt = $wgRequest->getHeader( "ACCEPT-DATETIME" );

		// looks for datetime enclosed in ""
		$req_dt = str_replace( '"', '', $raw_dt ); 

		// validating date time...
		$dt = wfTimestamp( TS_MW, $req_dt );

		if ( !$dt ) {
			$msg = wfMsgForContent( 'timegate-400-date', $req_dt );

			$msg .= wfMsgForContent( 'timegate-400-first-memento', $first['uri'] );
			$msg .= wfMsgForContent( 'timegate-400-last-memento', $last['uri'] );

			$header = array( "Link" => mmConstructLinkHeader( $first, $last ) . $Link );
			mmSend( 400, $header, $msg );
			exit();
		}

		return array( $dt, $raw_dt ); 
	}




	function getMementoForResource( $pg_id, $historyuri, $title ) {

		global $wgRequest, $wgArticlePath;

		$waddress = str_replace( '/$1', '', $wgArticlePath );

		// creating a db object to retrieve the old revision id from the db. 
		$dbr = wfGetDB( DB_SLAVE );
		$dbr->begin();

		$alt_header = '';
		$last = array(); $first = array(); $next = array(); $prev = array(); $mem = array();

		$db_details = array( 'dbr'=>$dbr, 'title'=>$title, 'waddress'=>$waddress );

		// first/last version
		$last = mmFetchMementoFor( 'last', $pg_id, null, $db_details );
		$first = mmFetchMementoFor( 'first', $pg_id, null, $db_details );


		$Link = "<" . wfExpandUrl( $waddress . "/". $title ) . ">; rel=\"original latest-version\", ";
		$Link .= "<" . wfExpandUrl( $waddress . "/" . SpecialPage::getTitleFor('TimeMap') ) . "/" . wfExpandUrl( $waddress . "/" . $title) . ">; rel=\"timemap\"; type=\"application/link-format\"";

		// checking for the occurance of the accept datetime header.
		if ( !$wgRequest->getHeader( 'ACCEPT-DATETIME' ) ) {

			if ( isset( $last['uri'] ) ) {
				$memuri = $last['uri'];
				$mem = $last;
			}
			else {
				$memuri = $first['uri'];
				$mem = $first;
			}

			$prev = mmFetchMementoFor( 'prev', $pg_id, null, $db_details );

			$header = array( 
					"Location" => $memuri,
					"Vary" => "negotiate, accept-datetime",
					"Link" => mmConstructLinkHeader( $first, $last, $mem, '', $prev ) . $Link 
					);

			$dbr->commit();
			mmSend( 302, $header, null );
			exit();
		}

		list( $dt, $raw_dt ) = $this->ParseRequestDateTime( $first, $last, $Link );

		// if the requested time is earlier than the first memento, the first memento will be returned
		//if the requested time is past the last memento, or in the future, the last memento will be returned. 
		if ( $dt < wfTimestamp( TS_MW, $first['dt'] ) ) {
			$dt = wfTimestamp( TS_MW, $first['dt'] );
		}
		elseif ( $dt > wfTimestamp( TS_MW, $last['dt'] ) ) {
			$dt = wfTimestamp( TS_MW, $last['dt'] );
		}

		$prev = mmFetchMementoFor( 'prev', $pg_id, $dt, $db_details );
		$next = mmFetchMementoFor( 'next', $pg_id, $dt, $db_details );
		$mem = mmFetchMementoFor( 'memento', $pg_id, $dt, $db_details );

		$header = array( 
				"Location" => $mem['uri'],
				"Vary" => "negotiate, accept-datetime",
				"Link" => mmConstructLinkHeader( $first, $last, $mem, $next, $prev ) . $Link 
				);
		$dbr->commit();
		mmSend( 302, $header, null );
		exit();
	}
}
