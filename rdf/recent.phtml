<?php
include("Setup.php");
header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\"?>";

if( $style == "new" ) {
	$addl = " - " . wfMsg( "newpages");
} else {
	$addl = "";
}

?>

<rdf:RDF
xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
xmlns="http://my.netscape.com/rdf/simple/0.9/">

<channel>
<title><?php echo iconv($wgInputEncoding, "utf-8", wfMsg("sitetitle") . $addl ) ?></title>
<link><?php echo $wgServer ?></link>
<description><?php echo iconv($wgInputEncoding, "utf-8", wfMsg("sitesubtitle") ) ?></description>
</channel>

<?php
#<image>
#<title>Wikipedia</title>
#<url>...</url>
#<link>http://wikipedia.org/</link>
#</image>

if(isset($limit)) {
	if( $limit < 1) $limit = 1;
	if( $tlimit > 500) $limit = 500;
}
if(!isset($limit)) $limit = 10;

if($style == 'new') {
	# 10 newest articles
$sql = "SELECT rc_title as cur_title, rc_comment as cur_comment FROM recentchanges,cur
WHERE rc_cur_id=cur_id AND rc_new=1 AND rc_namespace=0 AND cur_is_redirect=0
AND LENGTH(cur_text) > 75
ORDER BY rc_timestamp DESC LIMIT {$limit}";
} else {
	# 10 most recently edit articles that aren't frickin tiny
$sql = "SELECT rc_title as cur_title,rc_comment as cur_comment FROM recentchanges,cur
WHERE rc_cur_id=cur_id AND rc_namespace=0 AND rc_this_oldid=0 AND cur_is_redirect=0
AND LENGTH(cur_text) > 150
ORDER BY rc_timestamp DESC LIMIT {$limit}";
}
$res = wfQuery( $sql );

while( $row = wfFetchObject( $res ) ) {
	$title = htmlspecialchars(
		iconv($wgInputEncoding, "utf-8",
		str_replace( "_", " ", $row->cur_title ) ) );
	$url = wfLocalUrl( wfUrlencode( $row->cur_title ) );
	$description = "<description>" . iconv($wgInputEncoding, "utf-8",
		htmlspecialchars( $row->cur_comment )) . "</description>";
	echo "
<item>
<title>{$title}</title>
<link>{$url}</link>
{$description}
</item>
";
}

#<textinput>
#<title>Search Wikipedia</title>
#<description>Search Wikipedia articles</description>
#<name>query</name>
#<link>http://www.wikipedia.org/w/wiki.phtml?search=</link>
#</textinput>
?>

</rdf:RDF>