<?php

$wgFeedClasses = array(
	"rss" => "RSSFeed",
	# "atom" => "AtomFeed",
	);

class FeedItem {
	var $Title = "Wiki";
	var $Description = "";
	var $Url = "";
	
	function FeedItem( $Title, $Description, $Url ) {
		$this->Title = $Title;
		$this->Description = $Description;
		$this->Url = $Url;
	}
	
	/* Static... */
	function xmlEncode( $string ) {
		global $wgInputEncoding, $wgLang;
		$string = str_replace( "\r\n", "\n", $string );
		if( strcasecmp( $wgInputEncoding, "utf-8" ) != 0 ) {
			$string = $wgLang->iconv( $wgInputEncoding, "utf-8" );
		}
		return htmlspecialchars( $string );
	}
	function getTitle() {
		return $this->xmlEncode( $this->Title );
	}
	function getUrl() {
		return $this->xmlEncode( $this->Url );
	}
	function getDescription() {
		return $this->xmlEncode( $this->Description );
	}
	function getLanguage() {
		global $wgLanguageCode;
		return $wgLanguageCode;
	}
}

class ChannelFeed extends FeedItem {
	/* Abstract functions, override! */
	function outHeader() {
		# print "<feed>";
	}
	function outItem( $item ) {
		# print "<item>...</item>";
	}
	function outFooter() {
		# print "</feed>";
	}
}

class RSSFeed extends ChannelFeed {
	function outHeader() {
		print '<' . '?xml version="1.0" encoding="utf-8"?' . ">\n";
		?><!DOCTYPE rss PUBLIC "-//Netscape Communications//DTD RSS 0.91//EN" "http://my.netscape.com/publish/formats/rss-0.91.dtd">
<rss version="0.91">
	<channel>
		<title><?php print $this->getTitle() ?></title>
		<link><?php print $this->getUrl() ?></link>
		<description><?php print $this->getDescription() ?></description>
		<language><?php print $this->getLanguage() ?></language>
<?php
	}
	
	function outItem( $item ) {
	?>
		<item>
			<title><?php print $item->getTitle() ?></title>
			<link><?php print $item->getUrl() ?></link>
			<description><?php print $item->getDescription() ?></description>
		</item>
<?php
	}

	function outFooter() {
	?>
	</channel>
</rss><?php
	}
}

?>