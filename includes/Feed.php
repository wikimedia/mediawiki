<?php

$wgFeedClasses = array(
	"rss" => "RSSFeed",
	# "atom" => "AtomFeed",
	);

class FeedItem {
	var $Title = "Wiki";
	var $Description = "";
	var $Url = "";
	var $Date = "";
	
	function FeedItem( $Title, $Description, $Url, $Date = "" ) {
		$this->Title = $Title;
		$this->Description = $Description;
		$this->Url = $Url;
		$this->Date = $Date;
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
	function getDate() {
		return $this->Date;
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
	function formatTime( $ts ) {
		return gmdate( "D, d M Y H:i:s T", wfTimestamp2Unix( $ts ) );
	}
	
	function outHeader() {
		global $wgVersion;
		
		print '<' . '?xml version="1.0" encoding="utf-8"?' . ">\n";
		?><rss version="2.0">
	<channel>
		<title><?php print $this->getTitle() ?></title>
		<link><?php print $this->getUrl() ?></link>
		<description><?php print $this->getDescription() ?></description>
		<language><?php print $this->getLanguage() ?></language>
		<generator>MediaWiki <?php print $wgVersion ?></generator>
		<lastBuildDate><?php print $this->formatTime( wfTimestampNow() ) ?></lastBuildDate>
<?php
	}
	
	function outItem( $item ) {
	?>
		<item>
			<title><?php print $item->getTitle() ?></title>
			<link><?php print $item->getUrl() ?></link>
			<description><?php print $item->getDescription() ?></description>
			<?php if( $item->getDate() ) { ?><pubDate><?php print $this->formatTime( $item->getDate() ) ?></pubDate><?php } ?>

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