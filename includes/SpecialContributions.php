<?

function wfSpecialContributions()
{
	global $wgUser, $wgOut, $wgLang, $target, $offset, $limit, $hideminor;
	$fname = "wfSpecialContributions";
	$sysop = $wgUser->isSysop();

	if ( "" == $target ) {
		$wgOut->errorpage( "notargettitle", "notargettext" );
		return;
	}
	$offset = (int)$offset;
	$limit = (int)$limit;
	if( $offset < 0 ) { $offset = 0; }
	if( $limit < 1 ) { $limit = 50; }

	$target = wfCleanQueryVar( $target );
	$nt = Title::newFromURL( $target );
	$nt->setNamespace( Namespace::getUser() );

	$sk = $wgUser->getSkin();
	$id = User::idFromName( $nt->getText() );

	if ( 0 == $id ) { $ul = $nt->getText(); }
	else {
		$ul = $sk->makeKnownLink( $nt->getPrefixedText(), $nt->getText() );
	}
	$sub = str_replace( "$1", $ul, wfMsg( "contribsub" ) );
	$wgOut->setSubtitle( $sub );

	if ( ! isset( $hideminor ) ) {
		$hideminor = $wgUser->getOption( "hideminor" );
	}
	if ( $hideminor ) {
		$cmq = "AND cur_minor_edit=0";
		$omq = "AND old_minor_edit=0";
	} else { $cmq = $omq = ""; }

	$top = wfShowingResults( $offset, $limit );
	$wgOut->addHTML( "<p>{$top}\n" );

	$sl = wfViewPrevNext( $offset, $limit,
	  $wgLang->specialpage( "Contributions" ), "target=" . wfUrlEncode( $target ) );
	$wgOut->addHTML( "<br>{$sl}\n" );
	
	# Sorting slowness on cur and especially old
	# forces us to check RC table first
	if ( 0 == $id ) {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment FROM cur " .
		  "WHERE cur_user_text='" . wfStrencode( $nt->getText() ) . "' {$cmq} " .
		  "ORDER BY inverse_timestamp LIMIT {$offset}, {$limit}";
		$res1 = wfQuery( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment FROM old " .
		  "WHERE old_user_text='" . wfStrencode( $nt->getText() ) . "' {$omq} " .
		  "ORDER BY inverse_timestamp LIMIT {$offset}, {$limit}";
		$res2 = wfQuery( $sql, $fname );
	} else {
		$sql = "SELECT cur_namespace,cur_title,cur_timestamp,cur_comment FROM cur " .
		  "WHERE cur_user={$id} {$cmq} ORDER BY inverse_timestamp LIMIT {$offset}, {$limit}";
		$res1 = wfQuery( $sql, $fname );

		$sql = "SELECT old_namespace,old_title,old_timestamp,old_comment FROM old " .
		  "WHERE old_user={$id} {$omq} ORDER BY inverse_timestamp LIMIT {$offset}, {$limit}";
		$res2 = wfQuery( $sql, $fname );
	}
	$nCur = wfNumRows( $res1 );
	$nOld = wfNumRows( $res2 );


	if ( 0 == $nCur && 0 == $nOld && 0 == $rcrows ) {
		$wgOut->addHTML( "\n<p>" . wfMsg( "nocontribs" ) . "</p>\n" );
		return;
	}
	if ( 0 != $nCur ) { $obj1 = wfFetchObject( $res1 ); }
	if ( 0 != $nOld ) { $obj2 = wfFetchObject( $res2 ); }

	$wgOut->addHTML( "<ul>\n" );
	while ( $limit ) {
		if ( 0 == $nCur && 0 == $nOld ) { break; }

		if ( ( 0 == $nOld ) ||
		  ( ( 0 != $nCur ) &&
		  ( $obj1->cur_timestamp >= $obj2->old_timestamp ) ) ) {
			$ns = $obj1->cur_namespace;
			$t = $obj1->cur_title;
			$ts = $obj1->cur_timestamp;
			$comment =$obj1->cur_comment;

			$obj1 = wfFetchObject( $res1 );
			$topmark = true;			
			--$nCur;
		} else {
			$ns = $obj2->old_namespace;
			$t = $obj2->old_title;
			$ts = $obj2->old_timestamp;
			$comment =$obj2->old_comment;

			$obj2 = wfFetchObject( $res2 );
			$topmark = false;
			--$nOld;
		}
		ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment );

		--$limit;
	}
	$wgOut->addHTML( "</ul>\n" );
}

function ucListEdit( $sk, $ns, $t, $ts, $topmark, $comment )
{
	global $wgLang, $wgOut, $wgUser;
	$page = Title::makeName( $ns, $t );
	$link = $sk->makeKnownLink( $page, "" );
	$topmarktext = $topmark ? wfMsg ( "uctop" ) : "";
	$sysop = $wgUser->isSysop();
	if($sysop && $topmark ) {
		$topmarktext .= " [". $sk->makeKnownLink( $page,
		  wfMsg( "rollbacklink" ), "action=rollback" ) ."]";
	}
	if($comment) {
	
		$comment="<em>(". htmlspecialchars( $comment ) .")</em> ";
	
	}
	$d = $wgLang->timeanddate( $ts, true );

	$wgOut->addHTML( "<li>{$d} {$link} {$comment}{$topmarktext}</li>\n" );
}

function ucCountLink( $lim, $d )
{
	global $wgUser, $wgLang, $target;

	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$lim}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}

function ucDaysLink( $lim, $d )
{
	global $wgUser, $wgLang, $target;

	$sk = $wgUser->getSkin();
	$s = $sk->makeKnownLink( $wgLang->specialPage( "Contributions" ),
	  "{$d}", "target={$target}&days={$d}&limit={$lim}" );
	return $s;
}
?>
