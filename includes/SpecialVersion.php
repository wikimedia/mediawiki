<?php

function wfSpecialVersion()
{
	global $wgUser, $wgOut, $wgVersion;
	$fname = "wfSpecialVersion";

	$versions = array(
		"[http://wikipedia.sf.net/ MediaWiki]" => $wgVersion,
		"[http://www.php.net/ PHP]" => phpversion(),
		"[http://www.mysql.com/ MySQL]" => mysql_get_server_info()
	);
	
	$out = "";
	foreach( $versions as $module => $ver ) {
		$out .= ":$module: $ver\n";
	}
	$wgOut->addWikiText( $out );
	
	return;
}

?>
