<?

$a = array (
"germany" => "
!type[political]:country
!name[de]:Deutschland
!name[en]:Germany
!region[political]:
addregs(germany-west,germany-east);
include(danube)
",

"germany-west" => "
!type[political]:county
!name[de]:Westdeutschland
!name[en]:Western Germany
!region[political]:
polygon(germany-border-north-w,germany-border-ew,germany-border-south-w,germany-border-west-w)
",

"germany-east" => "
!type[political]:county
!name[de]:Westdeutschland
!name[en]:Western Germany
!region[political]:
polygon(germany-border-north-e,germany-border-east-e,germany-border-south-e,germany-border-ew)
",

"germany-border-ew" => "!data:2,2 2,3",

"germany-border-north-w" => "!data:1,1 1.8,1",
"germany-border-south-w" => "!data:1,4 2.2,4",
"germany-border-west-w" => "!data:1,1 1,4",

"germany-border-north-e" => "!data:2,2 3,2",
"germany-border-south-e" => "!data:2,3 3,3",
"germany-border-east-e" => "!data:3,2 3,3",

"danube" => "
!type:river
!region:polyline(danube_germany,danube_austria)
" ,

"danube_germany" =>"!data:1.2,3.5 1.8,2.5 2,2.9" ,
"danube_austria" =>"!data:2.2,3.5 2.5,3.2 3,3.7" ,

) ;

# Global functions
function geo_get_text ( $id )
	{
	global $a ;
	return $a[$id] ;
	}

function data_to_real ( &$x , &$y )
	{
	$x = $x * 100 ;
	$y = $y * 100 ;
	}


# "geo" class
class geo
	{
	var $data = array () ;
	function set_from_text ( $t )
		{
		$t = explode ( "\n!" , "\n".$t ) ;
		$this->data = array () ;
		foreach ( $t AS $x )
			{
			$b = explode ( ":" , $x , 2 ) ;
			while ( count ( $b ) < 2 ) $b[] = "" ;
			$key = strtolower ( str_replace ( " " , "" , array_shift ( $b ) ) ) ;
			$key = str_replace ( "\n" , "" , $key ) ;
			$value = trim ( str_replace  ( "\n" , "" , array_shift ( $b ) ) ) ;
			$value = explode ( ";" , $value ) ;
			if ( $key != "" ) $this->data[$key] = $value ;
			}
		}
	
	function set_from_id ( $id )
		{
		$this->set_from_text ( geo_get_text ( $id ) ) ;
		}

	function get_data ()
		{
		$ret = array () ;
		if ( !isset ( $this->data["data"] ) ) return $ret ; # No data in this set
		$data = $this->data["data"] ;
		$data = array_shift ( $data ) ;
		$data = explode ( " " , $data ) ;
		foreach ( $data AS $a )
			{
			$a = explode ( "," , $a ) ;
			if ( count ( $a ) == 2 )
				{
				$x = trim ( array_shift ( $a ) ) ;
				$y = trim ( array_shift ( $a ) ) ;
				$ret[] = array ( $x , $y ) ;
				}
			}
		return $ret ;
		}

	function add_reordered_data ( &$original , &$toadd )
		{
		if ( count ( $toadd ) == 0 ) return ; # Nothing to add
		if ( count ( $original ) == 0 )
			{
			$original = $toadd ;
			return ;
			}
		
		$o_last = array_pop ( $original ) ; array_push ( $original , $o_last ) ; # Get last one and restore
		$t_last = array_pop ( $toadd ) ; array_push ( $toadd , $t_last ) ; # Get last one and restore
		$t_first = array_shift ( $toadd ) ; array_unshift ( $toadd , $t_first ) ; # Get first one and restore
		
		$dist_to_first =	( $o_last[0] - $t_first[0] ) * ( $o_last[0] - $t_first[0] ) +
								( $o_last[1] - $t_first[1] ) * ( $o_last[1] - $t_first[1] ) ;

		$dist_to_last =	( $o_last[0] - $t_last[0] ) * ( $o_last[0] - $t_last[0] ) +
								( $o_last[1] - $t_last[1] ) * ( $o_last[1] - $t_last[1] ) ;

		if ( $dist_to_last < $dist_to_first ) # If the last point of toadd is closer than the fist one,
			$toadd = array_reverse ( $toadd ) ; # add in other direction

		$original = array_merge ( $original , $toadd ) ;
		}

	function get_specs ( $base , $modes )
		{
		foreach ( $modes AS $x )
			{
			if ( isset ( $this->data["{$base}[{$x}]"] ) )
				return "{$base}[{$x}]" ;
			}
		if ( isset ( $this->data[$base] ) )
			return $base ;
		return "" ;
		}
		
	function get_current_type ( $params ) # params may override native type
		{
		$t = $this->get_specs ( "type" , array ( "political" ) ) ;
		if ( $t != "" ) $t = $this->data[$t][0] ;
		return $t ;
		}

	function get_current_style ( $params )
		{
		$t = trim ( strtolower ( $this->get_current_type ( $params ) ) ) ;
		if ( $t == "river" ) $s = "fill:none; stroke:blue; stroke-width:2" ;
		else $s = "fill:brown; stroke:black; stroke-width:1" ;
		return "style=\"{$s}\"" ;
		}

	function draw_line ( $line , $params )
		{
		$ret = "" ;
		$a = explode ( "(" , $line , 2 ) ;
		while ( count ( $a ) < 2 ) $a[] = "" ;
		$command = trim ( strtolower ( array_shift ( $a ) ) ) ;
		$values = trim ( str_replace ( ")" , "" , array_shift ( $a ) ) ) ;
#		print "Evaluating command {$line}\n" ;
		if ( $command == "addregs" || $command == "include" )
			{
			$values = explode ( "," , $values ) ;
			foreach ( $values AS $v )
				{
				$v = trim ( strtolower ( $v ) ) ;
				$ng = new geo ;
				$ng->set_from_id ( $v ) ;
				$ret .= $ng->draw ( $params ) ;
				}
			}
		else if ( $command == "polygon" || $command == "polyline" )
			{
			$data = array () ;
			$values = explode ( "," , $values ) ;
			foreach ( $values AS $v )
				{
				$v = trim ( strtolower ( $v ) ) ;
				$ng = new geo ;
				$ng->set_from_id ( $v ) ;
				$b = $ng->get_data () ;
				$this->add_reordered_data ( $data , $b ) ;
				}

			$style = $this->get_current_style ( $params ) ;
			if ( $command == "polygon" ) $ret .= "<polygon {$style} points=\"" ;
			if ( $command == "polyline" ) $ret .= "<polyline {$style} points=\"" ;
			foreach ( $data AS $a )
				{
				$x = $a[0] ;
				$y = $a[1] ;
				data_to_real ( $x , $y ) ;
				$ret .= "{$x},{$y} " ;
				}
			$ret = trim ( $ret ) . "\"/>\n" ;
			
			}
		return $ret ;
		}
	
	function draw ( $params = array() )
		{
		$ret = "" ;
		$match = $this->get_specs ( "region" , array ( "political" ) ) ;
		if ( $match != "" )
			{
			$a = $this->data[$match] ;
			foreach ( $a AS $line )
				$ret .= $this->draw_line ( $line , $params ) ;
			}
		return $ret ;
		}
	}


$g = new geo ;
$g->set_from_id ( "germany" ) ;

$p = array () ;
$svg = $g->draw ( $p ) ;

$styles = "" ;
/*
$styles = '	<defs>
		<style type="text/css"><![CDATA[
			.stuff {fill:none; stroke:blue; stroke-width:2}
   		]]></style>
	</defs>
' ;
*/

$svg = 
'<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/SVG/DTD/svg10.dtd">
<svg viewBox="0 0 270 400"
     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">
'
	. $styles .
'	<g id="mainlayer">
'
	. $svg .
	'</g>
</svg>
' ;

print $svg ;

?>