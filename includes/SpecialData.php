<?php
/**
 * @package MediaWiki
 */

/* You'll need to run these CREATEs :

CREATE TABLE data (
data_cur_id INT NOT NULL ,
data_revision INT NOT NULL ,
data_key VARCHAR( 255 ) NOT NULL ,
data_value MEDIUMTEXT NOT NULL ,
INDEX ( data_cur_id ),
INDEX (data_revision) ,
INDEX (data_key )
) TYPE = MYISAM ;

CREATE TABLE data_rev (
rev_cur_id INT NOT NULL ,
rev_id INT NOT NULL ,
rev_masterkey VARCHAR( 255 ) NOT NULL,
rev_user_id INT NOT NULL ,
rev_user_text VARCHAR( 255 ) NOT NULL ,
rev_comment VARCHAR( 255 ) NOT NULL ,
rev_time INT NOT NULL ,
INDEX ( rev_cur_id) ,
INDEX (rev_id),
INDEX ( rev_user_id ),
INDEX ( rev_masterkey )
) TYPE = MYISAM ;

*/

function wfDataPreview ( &$t , &$dk )
	{
	$s = "" ;
	$u = explode ( "((" , $t ) ;
	foreach ( $u AS $x )
		{
		$y = explode ( "))" , $x ) ;
		if ( count ( $y ) == 2 )
			{
			$z = explode ( "/" , $y[0] ) ;
			$keyname = $z[0] ;
			$isMasterKey = false ;
			if ( substr ( $keyname , 0 , 1 ) == "!" )
				{
				$keyname = substr ( $keyname , 1 ) ;
				$isMasterKey = true ;
				}
			if ( isset ( $dk[$keyname] ) ) $value= $dk[$keyname] ;
			else $value = "" ;
			if ( $isMasterKey ) $value = "<b>{$value}</b>" ;
			$s .= $value . $y[1] ;
			}
		else $s .= $x ;
		}
	return $s ;
	}

function wfDataView ( $dt ) # $dt = data type
	{
	if ( $dt == "" ) return ;
	global $wgParser, $wgTitle;
	global $wgOut , $wgUser ;
	$nsdata = 20 ;
	$s = "<h2>{$dt}</h2>" ;
	
	# Read from source
	$dbr =& wfGetDB( DB_SLAVE );
	$sql = "SELECT * FROM cur WHERE cur_namespace={$nsdata} AND cur_title=\"{$dt}\"";
	$res1 = $dbr->query( $sql, "wfDataEdit" );
	$data = $dbr->fetchObject( $res1 ) ;

	$sql = "SELECT * FROM data_rev WHERE rev_cur_id={$data->cur_id} GROUP BY rev_masterkey ORDER BY rev_masterkey" ;
	$r = $dbr->query( $sql, "wfDataEdit" );
	$mk = array () ;
	while ( ($d = $dbr->fetchObject( $r )) ) $mk[] = $d->rev_masterkey ;
	foreach ( $mk AS $x )
		{
		$s .= "<li>{$x}</li>" ;
		}
		
	$wgOut->AddHTML ( $s ) ;
	}

function wfDataEdit ( $dt ) # $dt = data type
	{
	if ( $dt == "" ) return ;
	global $wgParser, $wgTitle;
	global $wgOut , $wgUser ;
	$nsdata = 20 ;
	$s = "<h2>{$dt}</h2>" ;
	
	if ( isset ( $_POST['revision'] ) ) $revision = $_POST['revision'] ;
	else if ( isset ( $_GET['revision'] ) ) $revision = $_GET['revision'] ;
	else $revision = "" ;
	
	if ( isset ( $_POST['masterkey'] ) ) $masterkey = $_POST['masterkey'] ;
	else $masterkey = "" ;
	
	if ( isset ( $_POST['comment'] ) ) $comment = $_POST['comment'] ;
	else $comment = "" ;
	
	# Read form source
	$dbr =& wfGetDB( DB_SLAVE );
	$sql = "SELECT * FROM cur WHERE cur_namespace={$nsdata} AND cur_title=\"{$dt}\"";
	$res1 = $dbr->query( $sql, "wfDataEdit" );
	$data = $dbr->fetchObject( $res1 ) ;

	# Pre-render
	$parserOutput = $wgParser->parse( $data->cur_text, $wgTitle, $wgOut->mParserOptions, true );
	$t = $parserOutput->getText() ;	

	# Read from last form
	if ( isset ( $_POST['dk'] ) ) $dk = $_POST['dk'] ;

	# Store new version
	if ( isset ( $_POST['doit'] ) && $dk[$masterkey] )
		{
		# Get next revision number
		$dbw =& wfGetDB( DB_MASTER ); # Maybe DB_SLAVE didn't update yet
		$sql = "SELECT MAX(rev_id) AS m FROM data_rev WHERE rev_cur_id={$data->cur_id} AND rev_masterkey=\"" . $dk[$masterkey] . "\"" ;
		$r = $dbw->query( $sql, "wfDataEdit" );
		$newrev = $dbr->fetchObject( $r ) ;
		if ( isset ( $newrev ) AND isset ( $newrev->m ) ) $newrev = $newrev->m ;
		else $newrev = "" ;
		if ( $newrev == "" ) $newrev = 1 ;
		
		# Generate SQL
		$dbw->query( "BEGIN", "wfDataEdit" );
		$sql = "INSERT INTO data_rev (rev_cur_id,rev_id,rev_masterkey,rev_user_id,rev_user_text,rev_comment,rev_time) VALUES (" .
				"\"{$data->cur_id}\"," .
				"\"{$newrev}\",".
				"\"" . $dk[$masterkey] . "\"," .
				"\"" . $wgUser->getID() . "\",".
				"\"" . $wgUser->getName() . "\",".
				"\"{$comment}\",".
				"\"" . time() . "\");" ;
		$dbw->query( $sql, "wfDataEdit" );
				
		foreach ( $dk AS $k => $v )
			{
			$sql = "INSERT INTO data (data_cur_id,data_revision,data_key,data_value) VALUES (" .
					"\"" . $data->cur_id . "\"," .
					"\"" . $newrev . "\"," .
					"\"" . $k . "\"," .
					"\"" . $v . "\");" ;
			$dbw->query( $sql, "wfDataEdit" );
			}
		$dbw->query( "COMMIT", "wfDataEdit" );
				
		$s .= "Action complete.<br>\n" ;
		$s .= wfDataPreview ( $t , $dk ) ;
		$wgOut->AddHTML ( $s ) ;
		
		return ;
		}

	# Preview
	if ( isset ( $_POST['preview'] ) ) $s .= wfDataPreview ( $t , $dk ) . "\n<hr>\n" ;

	# Editing
	$t = explode ( "((" , $t ) ;
	$s .= "<form method=post href=\"index.php?title=Special:Data\">" ;
	foreach ( $t AS $x )
		{
		$y = explode ( "))" , $x ) ;
		if ( count ( $y ) == 2 )
			{
			$z = explode ( "/" , $y[0] ) ;
			$keyname = $z[0] ;
			$isMasterKey = false ;
			if ( substr ( $keyname , 0 , 1 ) == "!" )
				{
				$keyname = substr ( $keyname , 1 ) ;
				$isMasterKey = true ;
				}

			$value = "" ;
			if ( isset ( $dk[$keyname] ) ) $value= $dk[$keyname] ;
			if ( $isMasterKey )
				{
				$masterkey = $keyname ;
				$masterkeyvalue = $value ;
				}
			
			$input = "" ;
			$name = "dk[" . $keyname . "]" ;
			
			if ( count ( $z ) == 1 ) $z[] = "line" ; # Default
			$type = strtolower ( $z[1] ) ;
			if ( $type == "line" ) $input = "<input type=text style=\"width:100%\" name=\"{$name}\" value=\"{$value}\"></input>" ;
			else if ( $type == "multiline" ) $input = "<textarea style=\"width:100%\" name=\"{$name}\">{$value}</textarea>" ;
			else if ( $type == "number" ) $input = "<input type=int width=5 name=\"{$name}\" value=\"{$value}\"></input>" ;
			else if ( $type == "date" ) $input = "<input type=date width=10 name=\"{$name}\" value=\"{$value}\"></input>" ;
			else if ( $type == "dropdown" ) 
				{
				$s .= "<select name=\"{$name}\">" ;
				foreach ( $z AS $k => $v )
					{
					if ( htmlentities ( $value ) == $v ) $default = " selected" ;
					else $default = "" ;
					if ( $k > 1 ) $s .= "<option value=\"{$v}\"{$default}>{$v}</option>\n" ;
					}
				$s .= "</select>" ;
				}
			if ( $isMasterKey AND $revision != "" ) $input = "<b>{$value}</b>" ;
				
			$s .= "{$input}" ;
			$s .= $y[1] ;
			}
		else $s .= $x ;
		}
	$s .= "<br>Comment : <input type='test' name='comment' value='{$comment}'></input>" ;
	$s .= " &nbsp; <input type='hidden' name='revision' value='{$revision}'></input>" ;
	$s .= "<input type='hidden' name='add_data' value='1'></input>" ;
	if ( $masterkey ) $s .= "<input type='hidden' name='masterkey' value='{$masterkey}'></input>" ;
	$s .= "<input type='hidden' name='data_type' value='{$dt}'></input>" ;
	$s .= "<input type=submit name='doit' value=\"Do it\"></input> " ;
	$s .= "<input type=submit name='preview' value=\"Preview\"></input>" ;
	$s .= "</form>" ;
	
	$wgOut->AddHTML ( $s ) ;
	}


function wfSpecialData()
{
	global $wgUseData ;
	if ( !$wgUseData ) return "" ;
	
	global $wgOut ;
	if ( isset ( $_GET['data_action'] ) ) $data_action = $_GET['data_action'] ;
	else $data_action = "" ;
	if ( isset ( $_POST['add_data'] ) ) $data_action = "add_data" ;
	if ( isset ( $_POST['view_data'] ) ) $data_action = "view_data" ;
	$nsdata = 20 ;
	
	$last = "<hr><a href=\"index.php?title=Special:Data\">Back to data</a>" ;
	
	if ( $data_action == "" )
		{
		$s = "" ;

		$s .= "<form method=post>" ;
		$s .= "Data type " ;
		$dbr =& wfGetDB( DB_SLAVE );
		$sql = "SELECT cur_id,cur_title FROM cur WHERE cur_namespace={$nsdata}";
		$res1 = $dbr->query( $sql, "wfSpecialData" );
		$s .= "<select name='data_type'>\n" ;
		while ( ( $o = $dbr->fetchObject( $res1 ) ) )
			{
			$s .= "<option>{$o->cur_title}</option>\n" ;
			}
		$s .= "</select><br>\n" ;
		$s .= "<input type=submit name='add_data' value=\"Add data\"></input> " ;
		$s .= "<input type=submit name='view_data' value=\"View data\"></input> " ;
		$s .= "<br>\nTo add a new data type <i>Stuff</i>, edit the page <i>Data:Stuff</i>." ;
		$s .= "</form>" ;

		$wgOut->AddHTML ( $s ) ;
		$last = "" ;
		}
	else if ( $data_action == "add_data" )
		{
		wfDataEdit ( $_POST['data_type'] ) ;
		}
	else if ( $data_action == "view_data" )
		{
		wfDataView ( $_POST['data_type'] ) ;
		}
	
	if ( $last ) $wgOut->AddHTML ( $last ) ;
	
	return "" ;
	}

?>
