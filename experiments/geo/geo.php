<?
#include_once ( "geo_data.php" ) ;
include_once ( "geo_functions.php" ) ;

$geo_cache = array () ; # Evil global variable - beware!

function get_raw_text ( $id )
	{
	global $geo_cache ;
	
	if ( isset ( $geo_cache[$id] ) ) # Try the cache first...
		return $geo_cache[$id] ;

	# Get the page contents. This is stupidly done through reading the URL right now
	# It *should* be done by querying the DB through the Article class, of course
	$filename = "http://127.0.0.1/phase3/index.php?title=Geo:{$id}&action=raw" ;
	$handle = fopen($filename, "r");
	$contents = '';
	while (!feof($handle))
		$contents .= fread($handle, 8192);
	fclose($handle);

	$geo_cache[$id] = $contents ; # Cache the result
	return $contents ;
	}

# Global functions
function geo_get_text ( $id )
	{
	$id = trim ( strtolower ( $id ) ) ;
	
	$parts = explode ( "#" , $id ) ;
	if ( count ( $parts ) == 2 )
		{
		$id = array_shift ( $parts ) ;
		$subid = array_shift ( $parts ) ;
		}
	else $subid = "" ;
	
	$ret = "\n" . get_raw_text ( $id ) ;
	$ret = explode ( "\n==" , $ret ) ;
	
	if ( $subid == "" ) return $ret[0] ; # Default
	
	array_shift ( $ret ) ;
	foreach ( $ret AS $s )
		{
		$s = explode ( "\n" , $s , 2 ) ;
		$heading = array_shift ( $s ) ;
		$heading = strtolower ( trim ( str_replace ( "=" , "" , $heading ) ) ) ;
		if ( $heading == $subid ) return array_shift ( $s ) ;
		}
#	print "Not found : {$id}#{$subid}\n" ;
	return "" ; # Query not found
	}



# geo paramater class
class geo_params
	{
	var $min_x = 1000000 ;
	var $max_x = -1000000 ;
	var $min_y = 1000000 ;
	var $max_y = -1000000 ;
	var $labels = array () ;
	var $languages = array ( "en" ) ; # Default language
	var $style_fill = array () ;
	var $style_border = array () ;
	var $style_label = array () ;

	function get_styles ( $id , $type )
		{
		if ( isset ( $this->style_fill[$id] ) ) $fill = $this->style_fill[$id] ;
		else $fill = "fill:#CCCCCC" ;
		if ( isset ( $this->style_border[$id] ) ) $border = $this->style_border[$id] ;
		else $border = "stroke:black; stroke-width:10" ;
		return $fill . "; " . $border ;
		}

	function data_to_real ( &$x , &$y )
		{
		$x = coordinate_to_number ( coordinate_take_apart ( $x ) ) ;
		$y = coordinate_to_number ( coordinate_take_apart ( $y ) ) ;
	
		$z = $x ; $x = $y ; $y = $z ; # Switching y/x to x/y
		$y = 90 * 3600 - $y ; # displaying from north to south

		# Recording min and max
		$this->min_x = min ( $this->min_x , $x ) ;
		$this->min_y = min ( $this->min_y , $y ) ;
		$this->max_x = max ( $this->max_x , $x ) ;
		$this->max_y = max ( $this->max_y , $y ) ;
		}

	function get_view_box ()
		{
		$min_x = $this->min_x ;
		$max_x = $this->max_x ;
		$min_y = $this->min_y ;
		$max_y = $this->max_y ;
		$width = $max_x - $min_x ;
		$height = $max_y - $min_y ;
		$min_x -= $width / 10 ;
		$min_y -= $height / 10 ;
		$max_x += $width / 10 ;
		$max_y += $height / 10 ;
		
		$max_x -= $min_x ;
		$max_y -= $min_y ;
		return "{$min_x} {$min_y} {$max_x} {$max_y}" ;
		}

	function add_label ( $text_array )
		{
		$this->labels[] = $text_array ;
		}

	function get_svg_labels ()
		{
		$ret = "" ;
		$medium_font_size = floor ( ( $this->max_x - $this->min_x ) / 50 ) ;
		foreach ( $this->labels AS $l )
			{
			$text = $l['text'] ;
			$x = $l['x'] ;
			$y = $l['y'] ;
			$s = "<text style='" ;
			$fs = $l['font-size'] ;
			if ( $fs == "medium" ) $fs = $medium_font_size ;
			if ( $fs == "" ) $fs = $medium_font_size * 8 / 10 ;
			
			$p = array() ;
			$p[] = "text-anchor:middle" ;
			$p[] = "fill-opacity:0.7" ;
			$p[] = "font-size:{$fs}pt" ;
			$s .= implode ( ";" , $p ) ;
			
			$s .= "' x='{$x}' y='{$y}'>{$text}</text>\n" ;
			$ret .= $s ;
			}
		return $ret ;
		}
	}

# "geo" class
class geo
	{
	var $id ;
	var $data = array () ;
	var $xsum , $ysum , $count ;
	
	function set_from_id ( $id )
		{
		$this->id = $id ;
		$t = explode ( "\n;" , "\n".geo_get_text ( $id ) ) ;
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

	function get_data ( &$params )
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
				$params->data_to_real ( $x , $y ) ;
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
		
	function get_current_type ( &$params ) # params may override native type
		{
		$t = $this->get_specs ( "type" , array ( "political" ) ) ;
		if ( $t != "" ) $t = $this->data[$t][0] ;
		return $t ;
		}

	function get_current_style ( &$params )
		{
		$t = trim ( strtolower ( $this->get_current_type ( $params ) ) ) ;
		if ( $t == "river" ) $s = "fill:none; stroke:blue; stroke-width:2" ;
		else $s = $params->get_styles ( $this->id , $t ) ;
		return "style=\"{$s}\"" ;
		}

	function draw_line ( $line , &$params )
		{
		$ret = "" ;
		$a = explode ( "(" , $line , 2 ) ;
		while ( count ( $a ) < 2 ) $a[] = "" ;
		$command = trim ( strtolower ( array_shift ( $a ) ) ) ;
		$values = trim ( str_replace ( ")" , "" , array_shift ( $a ) ) ) ;
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
				$b = $ng->get_data ( $params ) ;
				$this->add_reordered_data ( $data , $b ) ;
				}

			$style = $this->get_current_style ( $params ) ;
			if ( $command == "polygon" ) $ret .= "<polygon {$style} points=\"" ;
			if ( $command == "polyline" ) $ret .= "<polyline {$style} points=\"" ;
			foreach ( $data AS $a )
				{
				$x = $a[0] ;
				$y = $a[1] ;
				$this->xsum += $x ;
				$this->ysum += $y ;
				$this->count++ ;
				$ret .= "{$x},{$y} " ;
				}
			$ret = trim ( $ret ) . "\"/>\n" ;
			
			}
		return $ret ;
		}

	function add_label ( $x , $y , &$params )
		{
		$text = $this->get_specs ( "name" , $params->languages ) ;
		if ( $text == "" ) return "" ;
		$text = utf8_decode ( $this->data[$text][0] ) ;
		$x = floor ( $x ) ;
		$y = floor ( $y ) ;
		
		$a = array ( "text" => $text , "x" => $x , "y" => $y , "font-size" => "medium" ) ;
		$params->add_label ( $a ) ;
		}
	
	function draw ( &$params )
		{
		$ret = "" ;
		$this->xsum = $this->ysum = $this->count = 0 ;
		$match = $this->get_specs ( "region" , array ( "political" ) ) ;
		if ( $match != "" )
			{
			$a = $this->data[$match] ;
			foreach ( $a AS $line )
				$ret .= $this->draw_line ( $line , $params ) ;
			}
		if ( $this->count > 0 )
			{
			$x = $this->xsum / $this->count ;
			$y = $this->ysum / $this->count ;
			$this->add_label ( $x , $y , $params ) ;
			}
		return $ret ;
		}
	}


$g = new geo ;
$g->set_from_id ( "germany" ) ;

$p = new geo_params ;
$p->languages = array ( "de" , "en" ) ; # Fallback to "en" if there's no "de"
$p->style_fill = array ( "germany.hamburg" => "fill:red" ) ;

$svg = $g->draw ( $p ) ;
$svg .= $p->get_svg_labels () ;

$styles = "" ;

$viewBox = $p->get_view_box () ;

$svg = 
'<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/SVG/DTD/svg10.dtd">
<svg viewBox="' . $viewBox .
'" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve">
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