<?

$a = array (

"germany-bavaria-bw" => "!data:231,458 237,455 239,449 248,449 240,440 250,438 257,440 257,446 263,444 267,447 270,457 274,461 280,459 284,463 285,473 287,487 290,493 295,498 302,509 303,525 296,530 294,529 295,534 295,542 285,547 283,547 279,553 285,567 286,582 286,591 284,601 285,612 275,612 266,612 258,618",
"germany-bavaria-hesse" => "!data:230,455 229,449 231,442 227,431 222,416 231,412 243,411 251,412 250,403 257,401 262,396 265,386 272,388 278,380",
"germany-bavaria-thuringia" => "!data:280,376 288,376 298,388 306,391 308,402 319,399 314,392 321,388 335,390 338,396 343,392 341,381 348,377 352,384 356,388 376,384",
"germany-bavaria-saxony" => "!data:378,388 382,391 388,394",
"germany-bavaria-east" => "!data:387,400 394,408 406,417 412,425 404,438 420,463 434,468 444,479 453,485 464,498 470,498 481,505 485,519 480,533 468,530 467,537 463,548 454,555 443,557 436,564 430,571 437,578 442,590 444,602 447,603 449,607 447,620",
"germany-bavaria-south" => "!data:447,620 436,618 435,609 427,605 419,610 412,605 404,607 403,601 398,605 402,613 381,616 369,616 366,621 358,622 359,627 350,630 346,627 340,631 332,630 329,622 314,618 309,622 304,618 306,627 306,631 293,642 288,644 292,636 286,637 284,630 271,622 268,619 266,624 260,621",

"germany-bw-south" => "!data:253,618 247,612 240,610 227,598 234,612 222,604 224,610 218,614 211,607 207,607 207,604 201,602 193,603 192,611 195,614 204,611 201,617 193,619 186,619 184,615 176,617 174,620 168,622 165,622 160,617 151,622",
"germany-bw-west" => "!data:143,610 144,597 147,585 145,577 153,557 156,546 161,534 169,523 175,516 180,504",
"germany-bw-rp" => "!data:183,503 190,493 197,480 196,467 194,467 192,455",
"germany-bw-hesse" => "!data:193,454 201,459 202,453 207,452 207,459 215,460 217,467 228,457",

"germany-bavaria" => "
!type[political]:state
!name[de]:Bayern
!name[en]:Bavaria
!region[political]:
polygon(germany-bavaria-hesse,germany-bavaria-thuringia,germany-bavaria-saxony,germany-bavaria-east,germany-bavaria-south,germany-bavaria-bw)
",

"germany-bw" => "
!type[political]:state
!name[de]:Baden-Württemberg
!name[en]:Baden-Württemberg
!region[political]:
polygon(germany-bavaria-bw,germany-bw-south,germany-bw-west,germany-bw-rp,germany-bw-hesse)
",

"germany" => "
!type[political]:country
!name[de]:Deutschland
!name[en]:Germany
!region[political]:
addregs(germany-bavaria,germany-bw);
include(danube)
",

"danube" => "
!type:river
!region:polyline(danube_germany,danube_austria)
" ,

"danube_germany" =>"!data:1.2,3.5 1.8,2.5 2,2.9" ,
"danube_austria" =>"!data:2.2,3.5 2.5,3.2 3,3.7" ,

) ;

# Global, evil variables
$min_x = $min_y = 1000000 ;
$max_x = $max_y = -1000000 ;

# Global functions
function geo_get_text ( $id )
	{
	global $a ;
	return $a[$id] ;
	}

function data_to_real ( &$x , &$y )
	{
#	$x = $x * 100 ;
#	$y = $y * 100 ;

	# Recording min and max
	global $min_x , $min_y , $max_x , $max_y ;
	$min_x = min ( $min_x , $x ) ;
	$min_y = min ( $min_y , $y ) ;
	$max_x = max ( $max_x , $x ) ;
	$max_y = max ( $max_y , $y ) ;
	}


# "geo" class
class geo
	{
	var $data = array () ;
	var $xsum , $ysum , $count ;
	
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
				$this->xsum += $x ;
				$this->ysum += $y ;
				$this->count++ ;
				$ret .= "{$x},{$y} " ;
				}
			$ret = trim ( $ret ) . "\"/>\n" ;
			
			}
		return $ret ;
		}

	function add_label ( $x , $y )
		{
		$text = $this->get_specs ( "name" , array ( "de" ) ) ;
		if ( $text == "" ) return "" ;
		$text = utf8_decode ( $this->data[$text][0] ) ;
		$ret = "<text style='text-anchor:middle' x='{$x}' y='{$y}'>{$text}</text>\n" ;
		return $ret ;
		}
	
	function draw ( $params = array() )
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
			$ret .= $this->add_label ( $x , $y ) ;
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

$width = $max_x - $min_x ;
$height = $max_y - $min_y ;
$min_x -= $width / 10 ;
$min_y -= $height / 10 ;
$max_x += $width / 10 ;
$max_y += $height / 10 ;

$svg = 
'<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/SVG/DTD/svg10.dtd">
<svg viewBox="' . "{$min_x} {$min_y} {$max_x} {$max_y}" . '"
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