<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 * @todo document
 */
$textsourcefile_web = "http://meta.wikipedia.org/w/index.php?title=PortalText&action=raw" ;
$textsourcefile = "t.txt" ;
#$articlecountfile_web = "http://magnusmanske.de/wikipedia/num.txt" ;
$articlecountfile_web = "num.txt" ;
$articlecountfile = "n.txt" ;
$perrow = 3 ;

if ( isset ( $HTTP_SERVER_VARS["HTTP_ACCEPT_LANGUAGE"] ) )
	$userlang = $HTTP_SERVER_VARS["HTTP_ACCEPT_LANGUAGE"] ;
else $userlang = '' ;

# Update local files
if ( isset ( $_GET["update"] ) )
	{
	copy ( $textsourcefile_web , $textsourcefile ) ;
	copy ( $articlecountfile_web , $articlecountfile ) ;
	}

/**
 * Reads a file into a string
 */
function readafile ( $filename )
	{
	$handle = fopen($filename, "r");
	$contents = '';
	while (!feof($handle))
		$contents .= fread($handle, 8192);
	fclose($handle);
	return $contents ;
	}

/** Parsing statistics file */
function get_numbers ( $filename )
	{
	$r = array () ;
	$nt = readafile ( $filename ) ;
	$nt = explode ( "\n" , $nt ) ;
	foreach ( $nt AS $x )
		{
		$y = explode ( ":" , $x ) ;
		if ( count ( $y ) == 2 )
			$r[strtolower($y[0])] = $y[1] ;
		}
	return $r ;
	}

/** Make shades for pref. language(s) */
function getshades ( $l )
	{
	$r = array () ;
	$l = explode ( "," , $l ) ;
	foreach ( $l AS $x )
		{
		$y = explode ( ";" , $x ) ;
		if ( count ( $y ) == 2 ) $weight = array_pop ( explode ( "=" , $y[1] ) ) ;
		else $weight = "1.0" ;

		$lang = array_shift ( $y ) ;
		$lang = explode ( "-" , $lang ) ;
		$lang = trim ( strtolower ( array_shift ( $lang ) ) ) ;

		$w = 5 * $weight ;
		$w = chr ( 70 - $w ) ;
		$w = $w . $w ;
		$w = $w.$w.$w ;
		if ( !isset ( $r[$lang] ) || $r[$lang] < $w )
			$r[$lang] = $w ;
		}
	return $r ;
	}

# Parsing text file and generating output
$n = get_numbers ( $articlecountfile ) ;
$shade = getshades ( $userlang ) ;
$l = "<table align=center border=1 width='50%' cellpadding=2>" ;
$count = 0 ;
$t = readafile ( $textsourcefile ) ;
$t = explode ( "\n" , $t ) ;
foreach ( $t AS $x )
	{
	$y = explode ( ':' , $x , 2 ) ;
	if ( count ( $y ) == 2 )
		{
		$language = trim ( strtolower ( $y[0] ) ) ; # language id
		if ( isset ( $n[$language] ) ) # Only if there's a number to show
			{
			$ltext = $y[1] ; # Language text
			$noa = $n[$language] ; # Number of articles
			$ltext = str_replace ( "###" , "<b>{$noa}</b>" , $ltext ) ;
			if ( isset ( $shade[$language] ) ) $s = " bgcolor='#" . $shade[$language] . "'" ;
			else $s = "" ;
			$ltext = "<td{$s}>{$ltext}</td>\n" ;
			if ( $count == 0 ) $l .= "<tr>" ;
			$l .= $ltext ;
			if ( $count == $perrow-1 )
				{
				$l .= "</tr>" ;
				$count = 0 ;
				}
			else $count++ ;
			}
		}
	}
if ( $count != 0 ) $l .= "</tr>" ;
$l .= "</table>" ;

print "<html><head></head><body>" ;
print $l ;
print "</body></html>" ;

?>