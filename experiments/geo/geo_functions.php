<?

# Converts a string like "-123456" into array ( "-12" , "34" , "56" )
function coordinate_take_apart ( $c )
	{
	if ( substr ( $c , 0 , 1 ) == "-" )
		{
		$mul = -1 ;
		$c = substr ( $c , 1 ) ;
		}
	else $mul = 1 ;
	while ( strlen ( $c ) < 6 ) $c .= "0" ;
	$c = array ( substr ( $c , 0 , 2 ) , substr ( $c , 2 , 2 ) , substr ( $c , 4 , 2 ) ) ;
	$c[0] *= $mul ;
	return $c ;
	}

function coordinate_write ( $a )
	{
	if ( !is_array ( $a ) ) $a = number_to_coordinate ( $a ) ;
	if ( $a[0] < 0 ) $mul = -1 ;
	else $mul = 1 ;
	$a[0] *= $mul ;
	while ( strlen ( $a[0] ) < 2 ) $a[0] = "0" . $a[0] ;
	while ( strlen ( $a[1] ) < 2 ) $a[1] = "0" . $a[1] ;
	while ( strlen ( $a[2] ) < 2 ) $a[2] = "0" . $a[2] ;
	if ( $mul == -1 ) $r = "-" ;
	else $r = "" ;
	$r .= $a[0] . $a[1] . $a[2] ;
	return $r ;
	}

function coordinate_to_number ( $a )
	{
	if ( is_string ( $a ) ) $a = coordinate_take_apart ( $a ) ;
	if ( $a[0] < 0 ) $mul = -1 ;
	else $mul = 1 ;
	return $mul * ( $a[2] + $a[1] * 60 + $a[0] * 3600 * $mul ) ;
	}

function number_to_coordinate ( $a )
	{
	$b = array () ;
	$b[0] = floor ( $a / 3600 ) ;
	$rest = $a - $b[0] * 3600 ;
	$b[1] = floor ( $rest / 60 ) ;
	$b[2] = floor ( $rest - $b[1] * 60 ) ;
	return $b ;
	}

function coord_conversion_params ( $c1 , $c2 , $p1 , $p2 )
	{
	$a = ( $c1 - $c2 ) / ( $p1 - $p2 ) ;
	$b = $c1 - $a * $p1 ;
	return array ( $a , $b ) ;
	}

function point_to_coords ( $s , $cx , $cy )
	{
	$x = $s[0] * $cx[0] + $cx[1] ;
	$y = $s[1] * $cy[0] + $cy[1] ;
	return array ( $x , $y ) ;
	}

?>