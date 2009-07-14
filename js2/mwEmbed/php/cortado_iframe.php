<?php
/*
cortado_embed.php
all file checks and conditions should be checked prior to loading this page.
this page serves as a wrapper for the cortado java applet

@@this may be depreciated in favor of a central hosted java applet
*/

cortado_iframe();

function cortado_iframe() {
	if(!function_exists('filter_input')){
		die('your version of php lacks <b>filter_input()</b> function</br>');
	}
	//load the http GETS:
	// set the parent domain if provided
	$parent_domain = isset( $_GET['parent_domain'] ) ? $_GET['parent_domain'] : false;

	//default to null media in not provided:
	$media_url = isset( $_GET['media_url'] ) ? $_GET['media_url'] : false;
	if( strval($media_url) === ''){
		error_out('not valid or missing media url');
	}
	//default duration to 30 seconds if not provided. (ideally cortado would read this from the video file)
	//$duration = (isset($_GET['duration']))?$_GET['duration']:0;
	$duration = filter_input(INPUT_GET, 'duration', FILTER_SANITIZE_NUMBER_INT);
	if( is_null($duration) || $duration===false){
		$duration=0;
	}

	//id (set to random if none provided)
	//$id = (isset($_GET['id']))?$_GET['id']:'vid_'.rand('10000000');
	$id = isset($_GET['id']) ? $_GET['id'] : false;
	if( is_null($id) || $id===false){
		$id = 'vid_'.rand(0,10000000);
	}

	$width = filter_input(INPUT_GET, 'width', FILTER_SANITIZE_NUMBER_INT);
	if( is_null($width) || $width===false){
		$width=320;
	}
	$height = filter_input(INPUT_GET, 'height', FILTER_SANITIZE_NUMBER_INT);
	//default to video:
	$stream_type = (isset($_GET['stream_type']))?$_GET['stream_type']:'video';
	if($stream_type=='video'){
		$audio=$video='true';	
		if(is_null($height) || $height===false)
			$height = 240;
	} else { // if($stream_type=='audio')
		$audio='true';
		$video='false';
		if(is_null($height) || $height===false)
			$height = 20;	
	}

	//everything good output page: 
	output_page(array(
		'id' => $id,
		'media_url' => $media_url,
		'audio' => $audio,
		'video' => $video,
		'duration' => $duration,
		'width' => $width,
		'height' => $height,
		'parent_domain' => $parent_domain
	));
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

function error_out($error=''){
	output_page(array('error' => $error));
	exit();
}
function output_page($params){
	extract( $params );
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>cortado_embed</title>	
	<script type="text/javascript">
		<? //set the parent domain: 
			if( $parent_domain ){?>
			try{
				document.domain = '<?php echo  htmlspecialchars( $parent_domain )?>';
			}catch (e){
				if( window.console )
					console.log('could not set domain to <?php echo  htmlspecialchars( $parent_domain )?>');
			}
		<?
		 } ?>	
		 var jPlayer = null;
		 function setGlobalJplayer(){
		 	jPlayer = document.getElementById('<?php echo  htmlspecialchars( $id ) ?>');
		 }	 						
	</script>
	<style type="text/css">
	<!--
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
	}
	-->
	</style></head>
	<body onload="setGlobalJplayer()" >
	<?php 
		$appid = ( preg_match("/MSIE/i", getenv("HTTP_USER_AGENT")) ) ? '' : 'classid="java:com.fluendo.player.Cortado.class"';
		if (empty($error)){ ?>
			<div id="jPlayer"></div>			
			<OBJECT id="<?php echo  htmlspecialchars( $id ) ?>" 
		  code="com.fluendo.player.Cortado.class" 
		  <?php echo $appid ?>
		  archive="binPlayers/cortado/cortado-wmf-r46643.jar" 
		  width="<?php echo  htmlspecialchars( $width )?>" 
		  height="<?php echo htmlspecialchars( $height )?>" >
			<param name="url" value="<?php echo  htmlspecialchars( $media_url )?>" />
			<param name="local" value="false"/>
			<param name="keepaspect" value="true" />
			<param name="video" value="<?php echo  htmlspecialchars( $video )?>" />
			<param name="audio" value="<?php echo  htmlspecialchars( $audio )?>" />
			<param name="seekable" value="false" />
			<? if($duration!=0){
				?>
				<param name="duration" value="<?php echo  htmlspecialchars( $duration )?>" />
				<?
			 } ?>
			<param name="showStatus" value="hide" />
			<param name="autoPlay" value="true" />
			<param name="BufferSize" value="8192" />
			<param name="BufferHigh" value="30" />
			<param name="BufferLow" value="5" />
	</OBJECT>
	<? }else{ ?>
		<b>Error:</b> <?php echo  htmlspecialchars( $error )?>	
	<?
	}
	?>
	</body>
	</html>
<?
}
/* 
javascript envoked version:
	function doPlayer(){
			jPlayer = document.createElement('OBJECT');
			jPlayer.setAttribute('classid', 'java:com.fluendo.player.Cortado.class');
			jPlayer.type = 'application/x-java-applet';
			jPlayer.setAttribute('archive', this.CortadoLocation);
			jPlayer.id = '<?php echo  htmlspecialchars( $id ) ?>';
			jPlayer.width = '<?php echo  htmlspecialchars( $width )?>';
			jPlayer.height = '<?php echo  htmlspecialchars( $height )?>';
		
			var params = {
			  'code': 'com.fluendo.player.Cortado',
			  'archive': 'cortado-wmf-r46643.jar',
			  'url': '<?php echo  htmlspecialchars( $media_url )?>',
			  'local': 'false',
			  'keepAspect': 'true',
			  'video': '<?php echo  htmlspecialchars( $video )?>',
			  'audio': '<?php echo  htmlspecialchars( $audio )?>',
			  'seekable': 'false',
			  'showStatus': 'hide',
			  'autoPlay': 'true',
			  'bufferSize': '8192',
			  'BufferHigh':'30',
			  'BufferLow' : '5',
				 <? if($duration!=0){
					?>
					'duration':'<?php echo  htmlspecialchars( $duration )?>',
					<?
				 } ?>
			  'debug': 0
			}
			for(name in params){
			  var p = document.createElement('param');
			  p.name = name;
			  p.value = params[name];
			  jPlayer.appendChild(p);
			}
			var pHolder = document.getElementById('jPlayer');
			if(pHolder)
				pHolder.appendChild( jPlayer );
		}
		doPlayer();		
//then in the page: 
<script type="text/javascript">
				doPlayer();
			</script>
 * 
*/
?>
