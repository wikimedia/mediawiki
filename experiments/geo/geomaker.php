<?

print "
<html>
<head></head>
<body>
" ;

$image = "./Germany_Map.jpg" ;

if ( isset ( $_GET['coords'] ) ) $coords = $_GET['coords'] ;
else $coords = "" ;

$x = $y = -1 ;
foreach ( $_GET AS $k => $v )
	{
	$a = explode ( "," , str_replace ( "?" , "" , $k ) ) ;
	if ( $v == "" AND count ( $a ) == 2 )
		{
		$x = array_shift ( $a ) ;
		$y = array_shift ( $a ) ;
		}
	}

if ( $coords != "" ) $coords = explode ( ";" , $coords ) ;
else $coords = array () ;

if ( $x != -1 AND $y != -1 ) # Adding coordinates
	{
	$coords[] = "{$x},{$y}" ;
	}

$c2 = $coords ;
if ( count ( $c2 ) > 0 ) array_pop ( $c2 ) ;
$c2 = implode ( ";" , $c2 ) ;

$coords = implode ( ";" , $coords ) ;

print "
<a href='./geomaker.php?coords={$coords}&'><image src='{$image}' ismap/></a>
" ;

print "<br/><a href='geomaker.php?coords={$c2}'>Remove last coordinates</a>" ;
print " | <a href='geomaker.php'>Reset</a>" ;

print "<br/>Coordinates so far:<br>\n" ;
print str_replace ( ";" , " " , $coords ) ;

print "
</body>
</html>" ;

?>