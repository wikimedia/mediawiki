<?
include_once ( "geo_functions.php" ) ;

print "
<html>
<head></head>
<body>
" ;

# One should only use free maps here, but since this is just a test...
$image = "http://www.bmwnation.com/members/ausgang/Germany%20Map.jpg" ;

# Corner points of the image above:
# 361,502 <=> 480900,113500
# 277,67 <=> 541919,100803

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

# Conversion form

if ( isset ( $_POST['convert'] ) )
	{
	$p1 = explode ( "," , $_POST['p1'] ) ;
	$p2 = explode ( "," , $_POST['p2'] ) ;
	$np1 = explode ( "," , $_POST['np1'] ) ;
	$np2 = explode ( "," , $_POST['np2'] ) ;

	$np1 = array ( coordinate_to_number ( $np1[0] ) , coordinate_to_number ( $np1[1] ) ) ;
	$np2 = array ( coordinate_to_number ( $np2[0] ) , coordinate_to_number ( $np2[1] ) ) ;

	$cx = coord_conversion_params ( $np1[1] , $np2[1] , $p1[0] , $p2[0] ) ;
	$cy = coord_conversion_params ( $np1[0] , $np2[0] , $p1[1] , $p2[1] ) ;

	$t = $_POST['ctext'] ;
	$coords = "" ;
	$t = explode ( " " , $t ) ;
	foreach ( $t AS $s )
		{
		$s = explode ( "," , trim ( $s ) ) ;
		if ( count ( $s ) == 2 )
			{
			$np = point_to_coords ( $s , $cx , $cy ) ;
			$coords[] = coordinate_write($np[1]) . "," . coordinate_write($np[0]) ;
			}
		}
	$coords = implode ( " " , $coords ) ;

	# For output
	$p1 = $_POST['p1'] ;
	$p2 = $_POST['p2'] ;
	$np1 = $_POST['np1'] ;
	$np2 = $_POST['np2'] ;
	}
else $p1 = $p2 = $np1 = $np2 = "" ;

print "<br />Coordinates so far:<br />\n" ;
print "<form method=post><textarea style='width:100%' rows=5 cols=40 name='ctext'>\n" ;
print str_replace ( ";" , " " , $coords ) ;
print "</textarea>\n" ;
print "Conversion : Point <input type='text' name='p1'/ value='{$p1}'> matches coordinates <input type='text' name='np1' value='{$np1}'/><br />" ;
print "and point <input type='text' name='p2' value='{$p2}'/> matches coordinates <input type='text' name='np2' value='{$np2}'/>" ;
print " <input type='submit' name='convert' value='Convert'/>" ;
print "</form>" ;


print "
</body>
</html>" ;

?>