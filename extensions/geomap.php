<?
$wgExtensionFunctions[] = "wfGeomapExtension";
 
function wfGeomapExtension ()
	{
	global $wgParser ;
	$wgParser->setHook ( "geomap" , parse_geomap ) ;
	}

function parse_geomap ( $text )
	{
	global $wgGeomapURL ;
	if ( !isset ( $wgGeomapURL  ) ) return "" ; # Hide broken extension
	
	$url = $wgGeomapURL ;
#	$url = "http://127.0.0.1/extensions/geo/index.php" ;
	$url .= "?params=" . urlencode ( $text ) ;

	$ret = '
<object data="' . $url . '" width="500" height="500" type="image/svg+xml" border=1>
<embed src="' . $url . '" width="500" height="500" type="image/svg+xml" />
</object>' ;

#	$ret = "<iframe width=200 height=200 src=\"{$url}\"/>" ;
	return $ret ;
	}

?>