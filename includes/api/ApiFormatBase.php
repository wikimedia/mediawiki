<?php
/**
 *
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * This is the abstract base class for API formatters.
 *
 * @ingroup API
 */
abstract class ApiFormatBase extends ApiBase {
	private $mIsHtml, $mFormat, $mUnescapeAmps, $mHelp, $mCleared;
	private $mBufferResult = false, $mBuffer, $mDisabled = false;

	/**
	 * Constructor
	 * If $format ends with 'fm', pretty-print the output in HTML.
	 * @param $main ApiMain
	 * @param string $format Format name
	 */
	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );

		$this->mIsHtml = ( substr( $format, -2, 2 ) === 'fm' ); // ends with 'fm'
		if ( $this->mIsHtml ) {
			$this->mFormat = substr( $format, 0, -2 ); // remove ending 'fm'
		} else {
			$this->mFormat = $format;
		}
		$this->mFormat = strtoupper( $this->mFormat );
		$this->mCleared = false;
	}

	/**
	 * Overriding class returns the mime type that should be sent to the client.
	 * This method is not called if getIsHtml() returns true.
	 * @return string
	 */
	abstract public function getMimeType();

	/**
	 * Whether this formatter needs raw data such as _element tags
	 * @return bool
	 */
	public function getNeedsRawData() {
		return false;
	}

	/**
	 * Get the internal format name
	 * @return string
	 */
	public function getFormat() {
		return $this->mFormat;
	}

	/**
	 * Specify whether or not sequences like &amp;quot; should be unescaped
	 * to &quot; . This should only be set to true for the help message
	 * when rendered in the default (xmlfm) format. This is a temporary
	 * special-case fix that should be removed once the help has been
	 * reworked to use a fully HTML interface.
	 *
	 * @param bool $b Whether or not ampersands should be escaped.
	 */
	public function setUnescapeAmps( $b ) {
		$this->mUnescapeAmps = $b;
	}

	/**
	 * Returns true when the HTML pretty-printer should be used.
	 * The default implementation assumes that formats ending with 'fm'
	 * should be formatted in HTML.
	 * @return bool
	 */
	public function getIsHtml() {
		return $this->mIsHtml;
	}

	/**
	 * Whether this formatter can format the help message in a nice way.
	 * By default, this returns the same as getIsHtml().
	 * When action=help is set explicitly, the help will always be shown
	 * @return bool
	 */
	public function getWantsHelp() {
		return $this->getIsHtml();
	}

	/**
	 * Disable the formatter completely. This causes calls to initPrinter(),
	 * printText() and closePrinter() to be ignored.
	 */
	public function disable() {
		$this->mDisabled = true;
	}

	public function isDisabled() {
		return $this->mDisabled;
	}

	/**
	 * Whether this formatter can handle printing API errors. If this returns
	 * false, then on API errors the default printer will be instantiated.
	 * @since 1.23
	 * @return bool
	 */
	public function canPrintErrors() {
		return true;
	}

	/**
	 * Initialize the printer function and prepare the output headers, etc.
	 * This method must be the first outputting method during execution.
	 * A human-targeted notice about available formats is printed for the HTML-based output,
	 * except for help screens (caused by either an error in the API parameters,
	 * the calling of action=help, or requesting the root script api.php).
	 * @param bool $isHelpScreen Whether a help screen is going to be shown
	 */
	function initPrinter( $isHelpScreen ) {
		if ( $this->mDisabled ) {
			return;
		}
		$isHtml = $this->getIsHtml();
		$mime = $isHtml ? 'text/html' : $this->getMimeType();
		$script = wfScript( 'api' );

		// Some printers (ex. Feed) do their own header settings,
		// in which case $mime will be set to null
		if ( is_null( $mime ) ) {
			return; // skip any initialization
		}

		$this->getMain()->getRequest()->response()->header( "Content-Type: $mime; charset=utf-8" );

		//Set X-Frame-Options API results (bug 39180)
		global $wgApiFrameOptions;
		if ( $wgApiFrameOptions ) {
			$this->getMain()->getRequest()->response()->header( "X-Frame-Options: $wgApiFrameOptions" );
		}

		if ( $isHtml ) {
?>
<!DOCTYPE HTML>
<html>
<head>
<?php
			if ( $this->mUnescapeAmps ) {
?>	<title>MediaWiki API</title>
<?php
			} else {
?>	<title>MediaWiki API Result</title>
<?php
			}
?>
</head>
<body>
<?php
			if ( !$isHelpScreen ) {
// @codingStandardsIgnoreStart Exclude long line from CodeSniffer checks
?>
<br />
<small>
You are looking at the HTML representation of the <?php echo $this->mFormat; ?> format.<br />
HTML is good for debugging, but is unsuitable for application use.<br />
Specify the format parameter to change the output format.<br />
To see the non HTML representation of the <?php echo $this->mFormat; ?> format, set format=<?php echo strtolower( $this->mFormat ); ?>.<br />
See the <a href='https://www.mediawiki.org/wiki/API'>complete documentation</a>, or
<a href='<?php echo $script; ?>'>API help</a> for more information.
</small>
<pre style='white-space: pre-wrap;'>
<?php
// @codingStandardsIgnoreEnd
			// don't wrap the contents of the <pre> for help screens
			// because these are actually formatted to rely on
			// the monospaced font for layout purposes
			} else {
?>
<pre>
<?php
			}
		}
	}

	/**
	 * Finish printing. Closes HTML tags.
	 */
	public function closePrinter() {
		if ( $this->mDisabled ) {
			return;
		}
		if ( $this->getIsHtml() ) {
?>

</pre>
</body>
</html>
<?php
		}
	}

	/**
	 * The main format printing function. Call it to output the result
	 * string to the user. This function will automatically output HTML
	 * when format name ends in 'fm'.
	 * @param $text string
	 */
	public function printText( $text ) {
		if ( $this->mDisabled ) {
			return;
		}
		if ( $this->mBufferResult ) {
			$this->mBuffer = $text;
		} elseif ( $this->getIsHtml() ) {
			echo $this->formatHTML( $text );
		} else {
			// For non-HTML output, clear all errors that might have been
			// displayed if display_errors=On
			// Do this only once, of course
			if ( !$this->mCleared ) {
				ob_clean();
				$this->mCleared = true;
			}
			echo $text;
		}
	}

	/**
	 * Get the contents of the buffer.
	 */
	public function getBuffer() {
		return $this->mBuffer;
	}

	/**
	 * Set the flag to buffer the result instead of printing it.
	 * @param $value bool
	 */
	public function setBufferResult( $value ) {
		$this->mBufferResult = $value;
	}

	/**
	 * Sets whether the pretty-printer should format *bold*
	 * @param $help bool
	 */
	public function setHelp( $help = true ) {
		$this->mHelp = $help;
	}

	/**
	 * Pretty-print various elements in HTML format, such as xml tags and
	 * URLs. This method also escapes characters like <
	 * @param $text string
	 * @return string
	 */
	protected function formatHTML( $text ) {
		// Escape everything first for full coverage
		$text = htmlspecialchars( $text );
		// encode all comments or tags as safe blue strings
		$text = str_replace( '&lt;', '<span style="color:blue;">&lt;', $text );
		$text = str_replace( '&gt;', '&gt;</span>', $text );

		// identify requests to api.php
		$text = preg_replace( '#^(\s*)(api\.php\?[^ <\n\t]+)$#m', '\1<a href="\2">\2</a>', $text );
		if ( $this->mHelp ) {
			// make lines inside * bold
			$text = preg_replace( '#^(\s*)(\*[^<>\n]+\*)(\s*)$#m', '$1<b>$2</b>$3', $text );
		}

		// Armor links (bug 61362)
		$masked = array();
		$text = preg_replace_callback( '#<a .*?</a>#', function ( $matches ) use ( &$masked ) {
			$sha = sha1( $matches[0] );
			$masked[$sha] = $matches[0];
			return "<$sha>";
		}, $text );

		// identify URLs
		$protos = wfUrlProtocolsWithoutProtRel();
		// This regex hacks around bug 13218 (&quot; included in the URL)
		$text = preg_replace(
			"#(((?i)$protos).*?)(&quot;)?([ \\'\"<>\n]|&lt;|&gt;|&quot;)#",
			'<a href="\\1">\\1</a>\\3\\4',
			$text
		);

		// Unarmor links
		$text = preg_replace_callback( '#<([0-9a-f]{40})>#', function ( $matches ) use ( &$masked ) {
			$sha = $matches[1];
			return isset( $masked[$sha] ) ? $masked[$sha] : $matches[0];
		}, $text );

		/**
		 * Temporary fix for bad links in help messages. As a special case,
		 * XML-escaped metachars are de-escaped one level in the help message
		 * for legibility. Should be removed once we have completed a fully-HTML
		 * version of the help message.
		 */
		if ( $this->mUnescapeAmps ) {
			$text = preg_replace( '/&amp;(amp|quot|lt|gt);/', '&\1;', $text );
		}

		return $text;
	}

	public function getExamples() {
		return array(
			'api.php?action=query&meta=siteinfo&siprop=namespaces&format=' . $this->getModuleName()
				=> "Format the query result in the {$this->getModuleName()} format",
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Data_formats';
	}

	public function getDescription() {
		return $this->getIsHtml() ? ' (pretty-print in HTML)' : '';
	}
}

/**
 * This printer is used to wrap an instance of the Feed class
 * @ingroup API
 */
class ApiFormatFeedWrapper extends ApiFormatBase {

	public function __construct( $main ) {
		parent::__construct( $main, 'feed' );
	}

	/**
	 * Call this method to initialize output data. See execute()
	 * @param $result ApiResult
	 * @param $feed object an instance of one of the $wgFeedClasses classes
	 * @param array $feedItems of FeedItem objects
	 */
	public static function setResult( $result, $feed, $feedItems ) {
		// Store output in the Result data.
		// This way we can check during execution if any error has occurred
		// Disable size checking for this because we can't continue
		// cleanly; size checking would cause more problems than it'd
		// solve
		$result->disableSizeCheck();
		$result->addValue( null, '_feed', $feed );
		$result->addValue( null, '_feeditems', $feedItems );
		$result->enableSizeCheck();
	}

	/**
	 * Feed does its own headers
	 *
	 * @return null
	 */
	public function getMimeType() {
		return null;
	}

	/**
	 * Optimization - no need to sanitize data that will not be needed
	 *
	 * @return bool
	 */
	public function getNeedsRawData() {
		return true;
	}

	/**
	 * ChannelFeed doesn't give us a method to print errors in a friendly
	 * manner, so just punt errors to the default printer.
	 * @return false
	 */
	public function canPrintErrors() {
		return false;
	}

	/**
	 * This class expects the result data to be in a custom format set by self::setResult()
	 * $result['_feed'] - an instance of one of the $wgFeedClasses classes
	 * $result['_feeditems'] - an array of FeedItem instances
	 */
	public function execute() {
		$data = $this->getResultData();
		if ( isset( $data['_feed'] ) && isset( $data['_feeditems'] ) ) {
			$feed = $data['_feed'];
			$items = $data['_feeditems'];

			$feed->outHeader();
			foreach ( $items as & $item ) {
				$feed->outItem( $item );
			}
			$feed->outFooter();
		} else {
			// Error has occurred, print something useful
			ApiBase::dieDebug( __METHOD__, 'Invalid feed class/item' );
		}
	}
}
