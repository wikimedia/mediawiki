<?php
# $Id$
# Basic support for outputting syndication feeds in RSS, other formats
# 
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * Contain a feed class as well as classes to build rss / atom ... feeds
 * Available feeds are defined in Defines.php
 * @package MediaWiki
 */


/**
 * @todo document
 * @package MediaWiki
 */
class FeedItem {
	/**#@+
	 * @var string
	 * @access private
	 */
	var $Title = 'Wiki';
	var $Description = '';
	var $Url = '';
	var $Date = '';
	var $Author = '';
	/**#@-*/
	
	/**
	 * @todo document
	 */
	function FeedItem( $Title, $Description, $Url, $Date = '', $Author = '', $Comments = '' ) {
		$this->Title = $Title;
		$this->Description = $Description;
		$this->Url = $Url;
		$this->Date = $Date;
		$this->Author = $Author;
		$this->Comments = $Comments;
	}
	
	/**
	 * @static
	 * @todo document
	 */
	function xmlEncode( $string ) {
		global $wgInputEncoding, $wgLang;
		$string = str_replace( "\r\n", "\n", $string );
		if( strcasecmp( $wgInputEncoding, 'utf-8' ) != 0 ) {
			$string = $wgLang->iconv( $wgInputEncoding, 'utf-8', $string );
		}
		return htmlspecialchars( $string );
	}
	
	/**
	 * @todo document
	 */
	function getTitle() { return $this->xmlEncode( $this->Title ); }
	/**
	 * @todo document
	 */
	function getUrl() { return $this->xmlEncode( $this->Url ); }
	/**
	 * @todo document
	 */
	function getDescription() { return $this->xmlEncode( $this->Description ); }
	/**
	 * @todo document
	 */
	function getLanguage() {
		global $wgLanguageCode;
		return $wgLanguageCode;
	}
	/**
	 * @todo document
	 */
	function getDate() { return $this->Date; }
	/**
	 * @todo document
	 */
	function getAuthor() { return $this->xmlEncode( $this->Author ); }
	/**
	 * @todo document
	 */
	function getComments() { return $this->xmlEncode( $this->Comments ); }
}

/**
 * @todo document
 * @package MediaWiki
 */
class ChannelFeed extends FeedItem {
	/**#@+
	 * Abstract function, override!
	 * @abstract
	 */
	 
	/**
	 * Generate Header of the feed
	 */
	function outHeader() {
		# print "<feed>";
	}
	
	/**
	 * Generate an item
	 * @param $item
	 */
	function outItem( $item ) {
		# print "<item>...</item>";
	}
	
	/**
	 * Generate Footer of the feed
	 */
	function outFooter() {
		# print "</feed>";
	}
	/**#@-*/
	
	/**
	 * @todo document
	 * @param string $mimetype (optional) type of output
	 */
	function outXmlHeader( $mimetype='application/xml' ) {
		global $wgServer, $wgStylePath, $wgOut;
		
		# We take over from $wgOut, excepting its cache header info
		$wgOut->disable();
		header( "Content-type: $mimetype; charset=UTF-8" );
		$wgOut->sendCacheControl();
		
		print '<' . '?xml version="1.0" encoding="utf-8"?' . ">\n";
		print '<' . '?xml-stylesheet type="text/css" href="' .
			htmlspecialchars( "$wgServer$wgStylePath/feed.css" ) . '"?' . ">\n";
	}
}

/**
 * Generate a RSS feed
 * @todo document
 * @package MediaWiki
 */
class RSSFeed extends ChannelFeed {

	/**
	 * Format a date given a timestamp
	 * @param integer $ts Timestamp
	 * @return string Date string
	 */
	function formatTime( $ts ) {
		return gmdate( 'D, d M Y H:i:s \G\M\T', wfTimestamp( TS_UNIX, $ts ) );
	}
	
	/**
	 * Ouput an RSS 2.0 header
	 */
	function outHeader() {
		global $wgVersion;
		
		$this->outXmlHeader();
		?><rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
	<channel>
		<title><?php print $this->getTitle() ?></title>
		<link><?php print $this->getUrl() ?></link>
		<description><?php print $this->getDescription() ?></description>
		<language><?php print $this->getLanguage() ?></language>
		<generator>MediaWiki <?php print $wgVersion ?></generator>
		<lastBuildDate><?php print $this->formatTime( wfTimestampNow() ) ?></lastBuildDate>
<?php
	}
	
	/**
	 * Output an RSS 2.0 item
	 * @param FeedItem item to be output
	 */
	function outItem( $item ) {
	?>
		<item>
			<title><?php print $item->getTitle() ?></title>
			<link><?php print $item->getUrl() ?></link>
			<description><?php print $item->getDescription() ?></description>
			<?php if( $item->getDate() ) { ?><pubDate><?php print $this->formatTime( $item->getDate() ) ?></pubDate><?php } ?>
			<?php if( $item->getAuthor() ) { ?><dc:creator><?php print $item->getAuthor() ?></dc:creator><?php }?>
			<?php if( $item->getComments() ) { ?><comments><?php print $item->getComments() ?></comments><?php }?>
		</item>
<?php
	}

	/**
	 * Ouput an RSS 2.0 footer
	 */
	function outFooter() {
	?>
	</channel>
</rss><?php
	}
}

/**
 * Generate an Atom feed
 * @todo document
 * @package MediaWiki
 */
class AtomFeed extends ChannelFeed {
	/**
	 * @todo document
	 */
	function formatTime( $ts ) {
		// need to use RFC 822 time format at least for rss2.0
		return gmdate( 'Y-m-d\TH:i:s', wfTimestamp( TS_UNIX, $ts ) );
	}

	/**
	 * @todo document
	 */
	function outHeader() {
		global $wgVersion, $wgOut;
		
		$this->outXmlHeader();
		?><feed version="0.3" xml:lang="<?php print $this->getLanguage() ?>">	
		<title><?php print $this->getTitle() ?></title>
		<link rel="alternate" type="text/html" href="<?php print $this->getUrl() ?>"/>
		<modified><?php print $this->formatTime( wfTimestampNow() ) ?>Z</modified>
		<tagline><?php print $this->getDescription() ?></tagline>
		<generator>MediaWiki <?php print $wgVersion ?></generator>
		
<?php
	}
	
	/**
	 * @todo document
	 */
	function outItem( $item ) {
		global $wgMimeType;
	?>
	<entry>
		<title><?php print $item->getTitle() ?></title>
		<link rel="alternate" type="<?php print $wgMimeType ?>" href="<?php print $item->getUrl() ?>"/>
		<?php if( $item->getDate() ) { ?>
		<modified><?php print $this->formatTime( $item->getDate() ) ?>Z</modified>
		<issued><?php print $this->formatTime( $item->getDate() ) ?></issued>
		<created><?php print $this->formatTime( $item->getDate() ) ?>Z</created><?php } ?>
	
		<summary type="text/plain"><?php print $item->getDescription() ?></summary>
		<?php if( $item->getAuthor() ) { ?><author><name><?php print $item->getAuthor() ?></name><!-- <url></url><email></email> --></author><?php }?>
		<comment>foobar</comment>
	</entry>

<?php /* FIXME need to add comments
	<?php if( $item->getComments() ) { ?><dc:comment><?php print $item->getComments() ?></dc:comment><?php }?>
      */
	}
	
	/**
	 * @todo document
	 */
	function outFooter() {?>
	</feed><?php
	}
}

?>
