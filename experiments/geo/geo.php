<?

$a = array (

"germany.bavaria/bw" => "!data:484627,092026 484900,092638 485407,092842 485407,093801 490146,092945 490329,094006 490146,094720 485640,094720 485822,095333 485549,095741 484718,100048 484354,100456 484536,101109 484212,101517 483341,101619 482146,101824 481639,102130 481224,102640 480302,103355 474925,103457 474509,102743 474600,102538 474145,102640 473456,102640 473041,101619 473041,101415 472535,101007 471339,101619 470053,101721 465314,101721 464443,101517 463521,101619 463521,100558 463521,095639 463014,094822",
"germany.bavaria/hesse" => "!data:484900,091923 485407,091821 490004,092026 490926,091617 492212,091107 492537,092026 492628,093251 492537,094108 493316,094006 493458,094720 493914,095231 494745,095537 494602,100252 495251,100905",
"germany.bavaria/thuringia" => "!data:495615,101109 495615,101926 494602,102947 494329,103804 493407,104008 493641,105131 494238,104620 494602,105335 494420,110805 493914,111111 494238,111622 495200,111417 495524,112132 494927,112541 494602,112949 494927,115031",
"germany.bavaria/saxony" => "!data:494602,115235 494329,115644 494056,120256",
"germany.bavaria/east" => "!data:493549,120154 492901,120909 492121,122134 491433,122747 490329,121930 484212,123604 483756,125033 482834,130054 482328,131013 481224,132137 481224,132749 480626,133912 475431,134321 474236,133810 474509,132545 473912,132443 472950,132034 472352,131115 472210,125952 471613,125238 471015,124625 470418,125340 465405,125850 464352,130054 464301,130401 463936,130605 462832,130401",
"germany.bavaria/south" => "!data:462832,130401 463014,125238 463754,125135 464118,124319 463703,123502 464118,122747 463936,121930 464443,121828 464118,121317 463430,121726 463157,115542 463157,114316 462741,114010 462650,113153 462235,113255 462001,112336 462235,111928 461910,111315 462001,110458 462650,110152 463014,104620 462650,104110 463014,103559 462235,103804 461910,103804 460949,102436 460806,101926 461455,102334 461404,101721 462001,101517 462650,100150 462923,095844 462508,095639 462741,095027",

"germany.bw/south" => "!data:463014,094312 463521,093659 463703,092945 464716,091617 463521,092332 464210,091107 463703,091311 463339,090658 463936,085943 463936,085535 464210,085535 464352,084922 464301,084106 463612,084003 463339,084310 463612,085229 463106,084922 462923,084106 462923,083351 463248,083147 463106,082330 462832,082125 462650,081513 462650,081207 463106,080656 462650,075737",
"germany.bw/west" => "!data:463703,074920 464807,075022 465820,075329 470509,075124 472210,075941 473132,080248 474145,080758 475107,081615 475704,082228 480717,082738",
"germany.bw/rp" => "!data:480808,083044 481639,083759 482743,084514 483847,084412 483847,084208 484900,084003",
"germany.bw/hesse" => "!data:484951,084106 484536,084922 485042,085024 485133,085535 484536,085535 484445,090352 483847,090556 484718,091719",

"germany.nrw/west" => "!data:515435,072940 514747,072021 514240,071306 513734,072021 513045,071409 513045,065531 513136,064305 512903,063244 511850,063551 510928,064305 510057,064612 504447,063857 504356,064305 503252,062529 502745,063346 502006,063551 501641,063142 501044,063755 500304,064612 495758,064101 495342,064816 494836,064918 494511,065122",
"germany.nrw/rp" => "!data:494602,065020 494511,065428 494420,065939 494420,070552 494420,071409 495109,071000 495524,071306 495109,071715 495342,071919 495524,072021 500213,073655 500810,074614 500953,075022 501226,075941 501732,080350 502421,080656 502006,081207 501641,081411 501459,081717 500902,082023 500537,082432",
"germany.nrw/hesse" => "!data:500628,082534 500902,082636 501317,082738 502006,083555 501732,084208 502148,084514 503252,084718 503343,085127 503434,085943 504214,085841 504538,085943 504538,085229 504811,085024 505409,085637 505551,090902 510148,090800 510422,091719 505733,091923 510057,092740 511110,093353",
"germany.nrw/ns" => "!data:511343,093353 511941,093251 512812,093801 512903,093047 513551,092434 514604,092230 514656,091413 515344,091719 515526,091107 520033,091617 520448,091923 521228,091821 520630,090902 520357,090148 521319,085739 521319,085331 520904,083901 520306,084310 515344,084514 514929,084616 514422,083555 514240,082228 514149,081819 514422,081615 514838,082125 515111,081615 515800,082023 520357,081717 520306,080900 520955,080145 520448,080145 520124,075431 515617,074308 515526,073144",

"germany.nrw" => "
!type[political]:state
!name[de]:Nordrhein-Westphalen
!name[en]:North Rhine-Westphalia
!region[political]:
polygon(germany.nrw/west,germany.nrw/rp,germany.nrw/hesse,germany.nrw/ns)
",

"germany.bavaria" => "
!type[political]:state
!name[de]:Bayern
!name[en]:Bavaria
!region[political]:
polygon(germany.bavaria/hesse,germany.bavaria/thuringia,germany.bavaria/saxony,germany.bavaria/east,germany.bavaria/south,germany.bavaria/bw)
",

"germany.bw" => "
!type[political]:state
!name[de]:Baden-Württemberg
!name[en]:Baden-Württemberg
!region[political]:
polygon(germany.bavaria/bw,germany.bw/south,germany.bw/west,germany.bw/rp,germany.bw/hesse)
",

"germany" => "
!type[political]:country
!name[de]:Deutschland
!name[en]:Germany
!region[political]:
addregs(germany.bavaria,germany.bw,germany.nrw)
",

"danube" => "
!type:river
!region:polyline(danube_germany,danube_austria)
" ,

"danube_germany" =>"!data:120,350 180,250 200,290" ,
"danube_austria" =>"!data:220,350 250,320 300,370" ,

) ;

include_once ( "geo_functions.php" ) ;

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
	$x = coordinate_to_number ( coordinate_take_apart ( $x ) ) ;
	$y = coordinate_to_number ( coordinate_take_apart ( $y ) ) ;

	$z = $x ; $x = $y ; $y = $z ;
	$y = 90 * 3600 - $y ;

	$x /= 100 ;
	$y /= 100 ;

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
				data_to_real ( $x , $y ) ;
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
//				data_to_real ( $x , $y ) ;
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
		$x = floor ( $x ) ;
		$y = floor ( $y ) ;
		$ret = "<text style='text-anchor:middle;fill-opacity:0.7' x='{$x}' y='{$y}'>{$text}</text>\n" ;
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

$max_x -= $min_x ;
$max_y -= $min_y ;

$svg = 
'<?xml version="1.0" encoding="iso-8859-1" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/SVG/DTD/svg10.dtd">
<svg viewBox="' . "{$min_x} {$min_y} {$max_x} {$max_y}" .
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