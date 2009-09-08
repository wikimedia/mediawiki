<?php
/*
mv_embed_iframe.php
This allows for remote embedding, without exposing the hosting site to remote JavaScript.
*/

mv_embed_iframe();

function mv_embed_iframe() {
	if ( !function_exists( 'filter_input' ) ) {
		die( 'your version of PHP lacks <b>filter_input()</b> function<br />' );
	}
	// Default to null media if not provided
	$stream_name = ( isset( $_GET['sn'] ) ) ? $_GET['sn'] : die( 'no stream name provided' );
	$time =	  ( isset( $_GET['t'] ) ) ? $_GET['t']: '';
	$width =  ( isset( $_GET['width'] )  ) ? intval( $_GET['width'] ) 	: '400';
	$height = ( isset( $_GET['height'] ) ) ? intval( $_GET['height'] ) 	: '300';		//

	$roe_url = 'http://metavid.org/wiki/Special:MvExportStream?feed_format=roe' . 
		'&stream_name=' . htmlspecialchars( $stream_name ) .
		'&t=' . htmlspecialchars( $time );
	// Everything good, output page:
	output_page( array(
		'roe_url' => $roe_url,
		'width' 	=> $width,
		'height'	=> $height,
	) );
}
function output_page( $params ) {
	extract( $params );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>mv_embed iframe</title>	
	<style type="text/css"> 
	<!--
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
	}
	-->
	</style>
		<script type="text/javascript" src="mv_embed.js"></script>
	</head>
	<body>
		<video roe="<?php echo $roe_url ?>" width="<?php echo htmlspecialchars( $width ) ?>"
			   height="<?php echo htmlspecialchars( $height ) ?>"></video>	
	</body>
	</html>
<?php
}

/**
 * JS escape function copied from MediaWiki's Xml::escapeJsString()
 */
function escapeJsString( $string ) {
	// See ECMA 262 section 7.8.4 for string literal format
	$pairs = array(
		"\\" => "\\\\",
		"\"" => "\\\"",
		'\'' => '\\\'',
		"\n" => "\\n",
		"\r" => "\\r",

		# To avoid closing the element or CDATA section
		"<" => "\\x3c",
		">" => "\\x3e",

		# To avoid any complaints about bad entity refs
		"&" => "\\x26",

		# Work around https://bugzilla.mozilla.org/show_bug.cgi?id=274152
		# Encode certain Unicode formatting chars so affected
		# versions of Gecko don't misinterpret our strings;
		# this is a common problem with Farsi text.
		"\xe2\x80\x8c" => "\\u200c", // ZERO WIDTH NON-JOINER
		"\xe2\x80\x8d" => "\\u200d", // ZERO WIDTH JOINER
	);
	return strtr( $string, $pairs );
}
