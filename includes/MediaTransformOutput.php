<?php

/**
 * Base class for the output of MediaHandler::doTransform() and Image::transform().
 */
abstract class MediaTransformOutput {
	/**
	 * Get the width of the output box
	 */
	function getWidth() {
		return $this->width;
	} 

	/**
	 * Get the height of the output box
	 */
	function getHeight() {
		return $this->height;
	}

	/**
	 * @return string The thumbnail URL
	 */
	function getUrl() {
		return $this->url;
	}

	/**
	 * @return string Destination file path (local filesystem)
	 */
	function getPath() {
		return $this->path;
	}

	/**
	 * Fetch HTML for this transform output
	 * @param array $attribs Advisory associative array of HTML attributes supplied 
	 *    by the linker. These can be incorporated into the output in any way.
	 * @param array $linkAttribs Attributes of a suggested enclosing <a> tag. 
	 *    May be ignored.
	 */
	abstract function toHtml( $attribs = array() , $linkAttribs = false );

	/**
	 * This will be overridden to return true in error classes
	 */
	function isError() {
		return false;
	}

	/**
	 * Wrap some XHTML text in an anchor tag with the given attributes
	 */
	protected function linkWrap( $linkAttribs, $contents ) {
		if ( $linkAttribs ) {
			return Xml::tags( 'a', $linkAttribs, $contents );
		} else {
			return $contents;
		}
	}
}


/**
 * Media transform output for images
 */
class ThumbnailImage extends MediaTransformOutput {
	/**
	 * @param string $path Filesystem path to the thumb
	 * @param string $url URL path to the thumb
	 * @private
	 */
	function ThumbnailImage( $url, $width, $height, $path = false ) {
		$this->url = $url;
		# These should be integers when they get here.
		# If not, there's a bug somewhere.  But let's at
		# least produce valid HTML code regardless.
		$this->width = round( $width );
		$this->height = round( $height );
		$this->path = $path;
	}

	/**
	 * Return HTML <img ... /> tag for the thumbnail, will include
	 * width and height attributes and a blank alt text (as required).
	 *
	 * You can set or override additional attributes by passing an
	 * associative array of name => data pairs. The data will be escaped
	 * for HTML output, so should be in plaintext.
	 *
	 * If $linkAttribs is given, the image will be enclosed in an <a> tag.
	 *
	 * @param array $attribs
	 * @param array $linkAttribs
	 * @return string
	 * @public
	 */
	function toHtml( $attribs = array(), $linkAttribs = false ) {
		$attribs['src'] = $this->url;
		$attribs['width'] = $this->width;
		$attribs['height'] = $this->height;
		if( !isset( $attribs['alt'] ) ) $attribs['alt'] = '';
		return $this->linkWrap( $linkAttribs, Xml::element( 'img', $attribs ) );
	}

}

/**
 * Basic media transform error class
 */
class MediaTransformError extends MediaTransformOutput {
	var $htmlMsg, $textMsg, $width, $height, $url, $path;

	function __construct( $msg, $width, $height /*, ... */ ) {
		$args = array_slice( func_get_args(), 3 );
		$htmlArgs = array_map( 'htmlspecialchars', $args );
		$htmlArgs = array_map( 'nl2br', $htmlArgs );

		$this->htmlMsg = wfMsgReplaceArgs( htmlspecialchars( wfMsgGetKey( $msg, true ) ), $htmlArgs );
		$this->textMsg = wfMsgReal( $msg, $args );
		$this->width = intval( $width );
		$this->height = intval( $height );
		$this->url = false;
		$this->path = false;
	}

	function toHtml( $attribs = array(), $linkAttribs = false ) {
		return "<table class=\"MediaTransformError\" style=\"" .
			"width: {$this->width}px; height: {$this->height}px;\"><tr><td>" .
			$this->htmlMsg .
			"</td></tr></table>";
	}

	function toText() {
		return $this->textMsg;
	}

	function getHtmlMsg() {
		return $this->htmlMsg;
	}

	function isError() {
		return true;
	}
}

/**
 * Shortcut class for parameter validation errors
 */
class TransformParameterError extends MediaTransformError {
	function __construct( $params ) {
		parent::__construct( 'thumbnail_error', 
			max( @$params['width'], 180 ), max( @$params['height'], 180 ), 
			wfMsg( 'thumbnail_invalid_params' ) );
	}
}

?>
