<?php
/**
 * Split off some of the internal bits from Skin.php.
 * These functions are used for primarily page content:
 * links, embedded images, table of contents. Links are
 * also used in the skin.
 * @package MediaWiki
 */

/**
 * For the moment, Skin is a descendent class of Linker.
 * In the future, it should probably be further split
 * so that ever other bit of the wiki doesn't have to
 * go loading up Skin to get at it.
 *
 * @package MediaWiki
 */
class Linker {
	var $linktrail ; # linktrail regexp
	var $postParseLinkColour = false;

	/** @todo document */
	function Linker() {
		global $wgContLang;
		$this->linktrail = $wgContLang->linkTrail();
		
		# Cache option lookups done very frequently
		$options = array( 'highlightbroken', 'hover' );
		foreach( $options as $opt ) {
			global $wgUser;
			$this->mOptions[$opt] = $wgUser->getOption( $opt );
		}
	}
	
	/**
	 * Get/set accessor for delayed link colouring
	 */
	function postParseLinkColour( $setting = NULL ) {
		return wfSetVar( $this->postParseLinkColour, $setting );
	}

	/** @todo document */
	function getExternalLinkAttributes( $link, $text, $class='' ) {
		global $wgContLang;

		$same = ($link == $text);
		$link = urldecode( $link );
		$link = $wgContLang->checkTitleEncoding( $link );
		$link = preg_replace( '/[\\x00-\\x1f_]/', ' ', $link );
		$link = htmlspecialchars( $link );

		$r = ($class != '') ? " class='$class'" : " class='external'";

		if( !$same && $this->mOptions['hover'] ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

	/** @todo document */
	function getInternalLinkAttributes( $link, $text, $broken = false ) {
		$link = urldecode( $link );
		$link = str_replace( '_', ' ', $link );
		$link = htmlspecialchars( $link );

		if( $broken == 'stub' ) {
			$r = ' class="stub"';
		} else if ( $broken == 'yes' ) {
			$r = ' class="new"';
		} else {
			$r = '';
		}

		if( $this->mOptions['hover'] ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

	/**
	 * @param bool $broken
	 */
	function getInternalLinkAttributesObj( &$nt, $text, $broken = false ) {
		if( $broken == 'stub' ) {
			$r = ' class="stub"';
		} else if ( $broken == 'yes' ) {
			$r = ' class="new"';
		} else {
			$r = '';
		}

		if( $this->mOptions['hover'] ) {
			$r .= ' title="' . $nt->getEscapedText() . '"';
		}
		return $r;
	}

	/**
	 * Note: This function MUST call getArticleID() on the link,
	 * otherwise the cache won't get updated properly.  See LINKCACHE.DOC.
	 */
	function makeLink( $title, $text = '', $query = '', $trail = '' ) {
		wfProfileIn( 'Skin::makeLink' );
	 	$nt = Title::newFromText( $title );
		if ($nt) {
			$result = $this->makeLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeLink(): "'.$title."\"\n" );
			$result = $text == "" ? $title : $text;
		}

		wfProfileOut( 'Skin::makeLink' );
		return $result;
	}

	/** @todo document */
	function makeKnownLink( $title, $text = '', $query = '', $trail = '', $prefix = '',$aprops = '') {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail, $prefix , $aprops );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeKnownLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/** @todo document */
	function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeBrokenLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/** @todo document */
	function makeStubLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeStubLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeStubLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeLinkObj( &$nt, $text= '', $query = '', $trail = '', $prefix = '' ) {
		global $wgOut, $wgUser, $wgLinkHolders;
		$fname = 'Skin::makeLinkObj';
		wfProfileIn( $fname );

		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			wfProfileOut( $fname );
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$ns = $nt->getNamespace();
		$dbkey = $nt->getDBkey();
		if ( $nt->isExternal() ) {
			$u = $nt->getFullURL();
			$link = $nt->getPrefixedURL();
			if ( '' == $text ) { $text = $nt->getPrefixedText(); }
			$style = $this->getExternalLinkAttributes( $link, $text, 'extiw' );

			$inside = '';
			if ( '' != $trail ) {
				if ( preg_match( '/^([a-z]+)(.*)$$/sD', $trail, $m ) ) {
					$inside = $m[1];
					$trail = $m[2];
				}
			}
			$t = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>";
			if( $this->postParseLinkColour ) {
				# There's no existence check, but this will prevent
				# interwiki links from being parsed as external links.
				global $wgInterwikiLinkHolders;
				$nr = array_push($wgInterwikiLinkHolders, $t);
				$retVal = '<!--IWLINK '. ($nr-1) ."-->{$trail}";
			} else {
				return $t;
			}
		} elseif ( 0 == $ns && "" == $dbkey ) {
			# A self-link with a fragment; skip existence check.
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} elseif ( ( NS_SPECIAL == $ns ) || ( NS_IMAGE == $ns ) ) {
			# These are always shown as existing, currently.
			# Special pages don't exist in the database; images may
			# occasionally be present when there is no description
			# page per se, so we always shown them.
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} elseif ( $this->postParseLinkColour ) {
			wfProfileIn( $fname.'-postparse' );
			# Insert a placeholder, and we'll work out the existence checks
			# in a big lump later.
			$inside = '';
			if ( '' != $trail ) {
				if ( preg_match( $this->linktrail, $trail, $m ) ) {
					$inside = $m[1];
					$trail = $m[2];
				}
			}

			# These get picked up by Parser::replaceLinkHolders()
			$nr = array_push( $wgLinkHolders['namespaces'], $nt->getNamespace() );
			$wgLinkHolders['dbkeys'][] = $dbkey;
			$wgLinkHolders['queries'][] = $query;
			$wgLinkHolders['texts'][] = $prefix.$text.$inside;
			$wgLinkHolders['titles'][] =& $nt;

			$retVal = '<!--LINK '. ($nr-1) ."-->{$trail}";
			wfProfileOut( $fname.'-postparse' );
		} else {
			wfProfileIn( $fname.'-immediate' );
			# Work out link colour immediately
			$aid = $nt->getArticleID() ;
			if ( 0 == $aid ) {
				$retVal = $this->makeBrokenLinkObj( $nt, $text, $query, $trail, $prefix );
			} else {
				$threshold = $wgUser->getOption('stubthreshold') ;
				if ( $threshold > 0 ) {
					$dbr =& wfGetDB( DB_SLAVE );
					$s = $dbr->selectRow(
						array( 'page', 'text' ),
						array( 'LENGTH(old_text) AS x',
							'page_namespace',
							'page_is_redirect' ),
						array( 'page_id' => $aid,
							'old_id = page_latest' ), $fname ) ;
					if ( $s !== false ) {
						$size = $s->x;
						if ( $s->page_is_redirect OR $s->page_namespace != NS_MAIN ) {
							$size = $threshold*2 ; # Really big
						}
					} else {
						$size = $threshold*2 ; # Really big
					}
				} else {
					$size = 1 ;
				}
				if ( $size < $threshold ) {
					$retVal = $this->makeStubLinkObj( $nt, $text, $query, $trail, $prefix );
				} else {
					$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
				}
			}
			wfProfileOut( $fname.'-immediate' );
		}
		wfProfileOut( $fname );
		return $retVal;
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeKnownLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' , $aprops = '' ) {
		global $wgOut, $wgTitle, $wgInputEncoding;

		$fname = 'Skin::makeKnownLinkObj';
		wfProfileIn( $fname );

		if ( !is_object( $nt ) ) {
			wfProfileIn( $fname );
			return $text;
		}
		
		$u = $nt->escapeLocalURL( $query );
		if ( '' != $nt->getFragment() ) {
			if( $nt->getPrefixedDbkey() == '' ) {
				$u = '';
				if ( '' == $text ) {
					$text = htmlspecialchars( $nt->getFragment() );
				}
			}
			$anchor = urlencode( do_html_entity_decode( str_replace(' ', '_', $nt->getFragment()), ENT_COMPAT, $wgInputEncoding ) );
			$replacearray = array(
				'%3A' => ':',
				'%' => '.'
			);
			$u .= '#' . str_replace(array_keys($replacearray),array_values($replacearray),$anchor);
		}
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		$r = "<a href=\"{$u}\"{$style}{$aprops}>{$prefix}{$text}{$inside}</a>{$trail}";
		wfProfileOut( $fname );
		return $r;
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeBrokenLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$fname = 'Skin::makeBrokenLinkObj';
		wfProfileIn( $fname );

		if ( '' == $query ) {
			$q = 'action=edit';
		} else {
			$q = 'action=edit&'.$query;
		}
		$u = $nt->escapeLocalURL( $q );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, "yes" );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $this->mOptions['highlightbroken'] ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>?</a>{$trail}";
		}

		wfProfileOut( $fname );
		return $s;
	}

	/**
 	 * Pass a title object, not a title string
	 */
	function makeStubLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$link = $nt->getPrefixedURL();

		$u = $nt->escapeLocalURL( $query );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, 'stub' );

		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		if ( $this->mOptions['highlightbroken'] ) {
			$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		} else {
			$s = "{$prefix}{$text}{$inside}<a href=\"{$u}\"{$style}>!</a>{$trail}";
		}
		return $s;
	}

	/** @todo document */
	function makeSelfLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$u = $nt->escapeLocalURL( $query );
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $this->linktrail, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		return "<strong>{$prefix}{$text}{$inside}</strong>{$trail}";
	}

	/** @todo document */
	function fnamePart( $url ) {
		$basename = strrchr( $url, '/' );
		if ( false === $basename ) {
			$basename = $url;
		} else {
			$basename = substr( $basename, 1 );
		}
		return htmlspecialchars( $basename );
	}

	/** @todo document */
	function makeImage( $url, $alt = '' ) {
		global $wgOut;
		if ( '' == $alt ) {
			$alt = $this->fnamePart( $url );
		}
		$s = '<img src="'.$url.'" alt="'.$alt.'" />';
		return $s;
	}

	/** @todo document */
	function makeImageLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeImageLinkObj( $nt, $alt );
	}

	/** @todo document */
	function makeImageLinkObj( $nt, $alt = '' ) {
		global $wgContLang, $wgUseImageResize;
		$img   = Image::newFromTitle( $nt );
		$url   = $img->getViewURL();

		$align = '';
		$prefix = $postfix = '';

		# Check if the alt text is of the form "options|alt text"
		# Options are:
		#  * thumbnail       	make a thumbnail with enlarge-icon and caption, alignment depends on lang
		#  * left		no resizing, just left align. label is used for alt= only
		#  * right		same, but right aligned
		#  * none		same, but not aligned
		#  * ___px		scale to ___ pixels width, no aligning. e.g. use in taxobox
		#  * center		center the image
		#  * framed		Keep original image size, no magnify-button.

		$part = explode( '|', $alt);

		$mwThumb  =& MagicWord::get( MAG_IMG_THUMBNAIL );
		$mwLeft   =& MagicWord::get( MAG_IMG_LEFT );
		$mwRight  =& MagicWord::get( MAG_IMG_RIGHT );
		$mwNone   =& MagicWord::get( MAG_IMG_NONE );
		$mwWidth  =& MagicWord::get( MAG_IMG_WIDTH );
		$mwCenter =& MagicWord::get( MAG_IMG_CENTER );
		$mwFramed =& MagicWord::get( MAG_IMG_FRAMED );
		$alt = '';

		$height = $framed = $thumb = false;
		$manual_thumb = "" ;

		foreach( $part as $key => $val ) {
			$val_parts = explode ( "=" , $val , 2 ) ;
			$left_part = array_shift ( $val_parts ) ;
			if ( $wgUseImageResize && ! is_null( $mwThumb->matchVariableStartToEnd($val) ) ) {
				$thumb=true;
			} elseif ( $wgUseImageResize && count ( $val_parts ) == 1 && ! is_null( $mwThumb->matchVariableStartToEnd($left_part) ) ) {
				# use manually specified thumbnail
				$thumb=true;
				$manual_thumb = array_shift ( $val_parts ) ;
			} elseif ( ! is_null( $mwRight->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'right';
			} elseif ( ! is_null( $mwLeft->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'left';
			} elseif ( ! is_null( $mwCenter->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'center';
			} elseif ( ! is_null( $mwNone->matchVariableStartToEnd($val) ) ) {
				# remember to set an alignment, don't render immediately
				$align = 'none';
			} elseif ( $wgUseImageResize && ! is_null( $match = $mwWidth->matchVariableStartToEnd($val) ) ) {
				# $match is the image width in pixels
				if ( preg_match( '/^([0-9]*)x([0-9]*)$/', $match, $m ) ) {
					$width = intval( $m[1] );
					$height = intval( $m[2] );
				} else {
					$width = intval($match);
				}
			} elseif ( ! is_null( $mwFramed->matchVariableStartToEnd($val) ) ) {
				$framed=true;
			} else {
				$alt = $val;
			}
		}
		if ( 'center' == $align )
		{
			$prefix  = '<div class="center">';
			$postfix = '</div>';
			$align   = 'none';
		}

		if ( $thumb || $framed ) {

			# Create a thumbnail. Alignment depends on language
			# writing direction, # right aligned for left-to-right-
			# languages ("Western languages"), left-aligned
			# for right-to-left-languages ("Semitic languages")
			#
			# If  thumbnail width has not been provided, it is set
			# here to 180 pixels
			if ( $align == '' ) {
				$align = $wgContLang->isRTL() ? 'left' : 'right';
			}
			if ( ! isset($width) ) {
				$width = 180;
			}
			return $prefix.$this->makeThumbLinkObj( $img, $alt, $align, $width, $height, $framed, $manual_thumb ).$postfix;

		} elseif ( isset($width) ) {

			# Create a resized image, without the additional thumbnail
			# features

			if (    ( ! $height === false )
			     && ( $img->getHeight() * $width / $img->getWidth() > $height ) ) {
				$width = $img->getWidth() * $height / $img->getHeight();
			}
			if ( '' == $manual_thumb ) $url = $img->createThumb( $width );
		}

		# FIXME: This is a gross hack using a global.
		# Replace link color holders in the caption text so the
		# text portion can be placed int the alt/title attributes.
		global $wgParser;
		$wgParser->replaceLinkHolders( $alt );
		
		$alt = Sanitizer::stripAllTags( $alt );

		$u = $nt->escapeLocalURL();
		if ( $url == '' ) {
			$s = wfMsg( 'missingimage', $img->getName() );
			$s .= "<br />{$alt}<br />{$url}<br />\n";
		} else {
			$s = '<a href="'.$u.'" class="image" title="'.$alt.'">' .
				 '<img src="'.$url.'" alt="'.$alt.'" longdesc="'.$u.'" /></a>';
		}
		if ( '' != $align ) {
			$s = "<div class=\"float{$align}\"><span>{$s}</span></div>";
		}
		return str_replace("\n", ' ',$prefix.$s.$postfix);
	}

	/**
	 * Make HTML for a thumbnail including image, border and caption
	 * $img is an Image object
	 */
	function makeThumbLinkObj( $img, $label = '', $align = 'right', $boxwidth = 180, $boxheight=false, $framed=false , $manual_thumb = "" ) {
		global $wgStylePath, $wgContLang;
		# $image = Title::makeTitleSafe( NS_IMAGE, $name );
		$url  = $img->getViewURL();

		#$label = htmlspecialchars( $label );
		$alt = Sanitizer::stripAllTags( $label );

		$width = $height = 0;
		if ( $img->exists() )
		{
			$width  = $img->getWidth();
			$height = $img->getHeight();
		}
		if ( 0 == $width || 0 == $height )
		{
			$width = $height = 200;
		}
		if ( $boxwidth == 0 )
		{
			$boxwidth = 200;
		}
		if ( $framed )
		{
			// Use image dimensions, don't scale
			$boxwidth  = $width;
			$oboxwidth = $boxwidth + 2;
			$boxheight = $height;
			$thumbUrl  = $url;
		} else {
			$h  = intval( $height/($width/$boxwidth) );
			$oboxwidth = $boxwidth + 2;
			if ( ( ! $boxheight === false ) &&  ( $h > $boxheight ) )
			{
				$boxwidth *= $boxheight/$h;
			} else {
				$boxheight = $h;
			}
			if ( '' == $manual_thumb ) $thumbUrl = $img->createThumb( $boxwidth );
		}

		if ( $manual_thumb != '' ) # Use manually specified thumbnail
		{
			$manual_title = Title::makeTitleSafe( NS_IMAGE, $manual_thumb ); #new Title ( $manual_thumb ) ;
			$manual_img = Image::newFromTitle( $manual_title );
			$thumbUrl = $manual_img->getViewURL();
			if ( $manual_img->exists() )
			{
				$width  = $manual_img->getWidth();
				$height = $manual_img->getHeight();
				$boxwidth = $width ;
				$boxheight = $height ;
				$oboxwidth = $boxwidth + 2 ;
			}
		}

		$u = $img->getEscapeLocalURL();

		$more = htmlspecialchars( wfMsg( 'thumbnail-more' ) );
		$magnifyalign = $wgContLang->isRTL() ? 'left' : 'right';
		$textalign = $wgContLang->isRTL() ? ' style="text-align:right"' : '';

		$s = "<div class=\"thumb t{$align}\"><div style=\"width:{$oboxwidth}px;\">";
		if ( $thumbUrl == '' ) {
			$s .= wfMsg( 'missingimage', $img->getName() );
			$zoomicon = '';
		} else {
			$s .= '<a href="'.$u.'" class="internal" title="'.$alt.'">'.
				'<img src="'.$thumbUrl.'" alt="'.$alt.'" ' .
				'width="'.$boxwidth.'" height="'.$boxheight.'" ' .
				'longdesc="'.$u.'" /></a>';
			if ( $framed ) {
				$zoomicon="";
			} else {
				$zoomicon =  '<div class="magnify" style="float:'.$magnifyalign.'">'.
					'<a href="'.$u.'" class="internal" title="'.$more.'">'.
					'<img src="'.$wgStylePath.'/common/images/magnify-clip.png" ' .
					'width="15" height="11" alt="'.$more.'" /></a></div>';
			}
		}
		$s .= '  <div class="thumbcaption" '.$textalign.'>'.$zoomicon.$label."</div></div></div>";
		return str_replace("\n", ' ', $s);
	}

	/** @todo document */
	function makeMediaLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeMediaLinkObj( $nt, $alt );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 *
	 * @param Title  $title
	 * @param string $text   pre-sanitized HTML
	 * @param bool   $nourl  Mask absolute URLs, so the parser doesn't
	 *                       linkify them (it is currently not context-aware)
	 * @return string HTML
	 *
	 * @access public
	 * @todo Handle invalid or missing images better.
	 */
	function makeMediaLinkObj( $title, $text = '', $nourl=false ) {
		if( is_null( $title ) ) {
			### HOTFIX. Instead of breaking, return empty string.
			return $text;
		} else {
			$name = $title->getDBKey();	
			$img  = Image::newFromTitle( $title );
			$url  = $img->getURL();
			if( $nourl ) {
				$url = str_replace( "http://", "http-noparse://", $url );
			}
			$alt = htmlspecialchars( $title->getText() );
			if( $text == '' ) {
				$text = $alt;
			}
			$u = htmlspecialchars( $url );
			return "<a href=\"{$u}\" class='internal' title=\"{$alt}\">{$text}</a>";			
		}
	}

	/** @todo document */
	function specialLink( $name, $key = '' ) {
		global $wgContLang;

		if ( '' == $key ) { $key = strtolower( $name ); }
		$pn = $wgContLang->ucfirst( $name );
		return $this->makeKnownLink( $wgContLang->specialPage( $pn ),
		  wfMsg( $key ) );
	}

	/** @todo document */
	function makeExternalLink( $url, $text, $escape = true, $linktype = '' ) {
		$style = $this->getExternalLinkAttributes( $url, $text, 'external ' . $linktype );
		global $wgNoFollowLinks;
		if( $wgNoFollowLinks ) {
			$style .= ' rel="nofollow"';
		}
		$url = htmlspecialchars( $url );
		if( $escape ) {
			$text = htmlspecialchars( $text );
		}
		return '<a href="'.$url.'"'.$style.'>'.$text.'</a>';
	}

	/**
	 * This function is called by all recent changes variants, by the page history,
	 * and by the user contributions list. It is responsible for formatting edit
	 * comments. It escapes any HTML in the comment, but adds some CSS to format
	 * auto-generated comments (from section editing) and formats [[wikilinks]].
	 *
	 * The &$title parameter must be a title OBJECT. It is used to generate a
	 * direct link to the section in the autocomment.
	 * @author Erik Moeller <moeller@scireview.de>
	 *
	 * Note: there's not always a title to pass to this function.
	 * Since you can't set a default parameter for a reference, I've turned it
	 * temporarily to a value pass. Should be adjusted further. --brion
	 */
	function formatComment($comment, $title = NULL) {
		$fname = 'Skin::formatComment';
		wfProfileIn( $fname );
		
		global $wgContLang;
		$comment = htmlspecialchars( $comment );

		# The pattern for autogen comments is / * foo * /, which makes for
		# some nasty regex.
		# We look for all comments, match any text before and after the comment,
		# add a separator where needed and format the comment itself with CSS
		while (preg_match('/(.*)\/\*\s*(.*?)\s*\*\/(.*)/', $comment,$match)) {
			$pre=$match[1];
			$auto=$match[2];
			$post=$match[3];
			$link='';
			if($title) {
				$section=$auto;

				# This is hackish but should work in most cases.
				$section=str_replace('[[','',$section);
				$section=str_replace(']]','',$section);
				$title->mFragment=$section;
				$link=$this->makeKnownLinkObj($title,wfMsg('sectionlink'));
			}
			$sep='-';
			$auto=$link.$auto;
			if($pre) { $auto = $sep.' '.$auto; }
			if($post) { $auto .= ' '.$sep; }
			$auto='<span class="autocomment">'.$auto.'</span>';
			$comment=$pre.$auto.$post;
		}

		# format regular and media links - all other wiki formatting
		# is ignored
		$medians = $wgContLang->getNsText( NS_MEDIA ) . ':';
		while(preg_match('/\[\[(.*?)(\|(.*?))*\]\](.*)$/',$comment,$match)) {
			# Handle link renaming [[foo|text]] will show link as "text"
			if( "" != $match[3] ) {
				$text = $match[3];
			} else {
				$text = $match[1];
			}
			if( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
				# Media link; trail not supported.
				$linkRegexp = '/\[\[(.*?)\]\]/';
				$thelink = $this->makeMediaLink( $submatch[1], "", $text );
			} else {
				# Other kind of link
				if( preg_match( wfMsgForContent( "linktrail" ), $match[4], $submatch ) ) {
					$trail = $submatch[1];
				} else {
					$trail = "";
				}
				$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
				if ($match[1][0] == ':')
					$match[1] = substr($match[1], 1);
				$thelink = $this->makeLink( $match[1], $text, "", $trail );
			}
			$comment = preg_replace( $linkRegexp, $thelink, $comment, 1 );
		}
		wfProfileOut( $fname );
		return $comment;
	}
	
	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return empty string.
	 *
	 * @param string $comment
	 * @param Title $title
	 * @return string
	 * @access public
	 */
	function commentBlock( $comment, $title = NULL ) {
		if( $comment == '' || $comment == '*' ) {
			return '';
		} else {
			$formatted = $this->formatComment( $comment, $title );
			return " <span class='comment'>($formatted)</span>";
		}
	}

	/** @todo document */
	function tocIndent() {
		return "\n<ul>";
	}

	/** @todo document */
	function tocUnindent($level) {
		return "</li>\n" . str_repeat( "</ul>\n</li>\n", $level>0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 */
	function tocLine( $anchor, $tocline, $tocnumber, $level ) {
		return "\n<li class='toclevel-$level'><a href=\"#" .
			$anchor . '"><span class="tocnumber">' .
			$tocnumber . '</span> <span class="toctext">' .
			$tocline . '</span></a>';
	}

	/** @todo document */
	function tocLineEnd() {
		return "</li>\n";
 	}

	/** @todo document */
	function tocList($toc) {
		return "<div id='toc'>\n" 
			   . "<div id='toctitle'><h2>" . wfMsg('toc') . "</h2></div>\n"
		     . $toc
				 . "</ul>\n</div>\n"
				 . '<script type="text/javascript">'
				 . ' if (window.showTocToggle) {'
				 . ' var tocShowText = "' . addslashes( wfMsg('showtoc') ) . '";'
				 . ' var tocHideText = "' . addslashes( wfMsg('hidetoc') ) . '"; '
				 . ' showTocToggle();'
				 . ' } '
				 . '</script>'
				 . "<div class='visualClear'></div>\n";
	}

	/**
	 * These two do not check for permissions: check $wgTitle->userCanEdit
	 * before calling them
	 */
	function editSectionScriptForOther( $title, $section, $head ) {
		$ttl = Title::newFromText( $title );
		$url = $ttl->escapeLocalURL( 'action=edit&section='.$section );
		return '<span oncontextmenu=\'document.location="'.$url.'";return false;\'>'.$head.'</span>';
	}

	/** @todo document */
	function editSectionScript( $nt, $section, $head ) {
		global $wgRequest;
		if( $wgRequest->getInt( 'oldid' ) && ( $wgRequest->getVal( 'diff' ) != '0' ) ) {
			return $head;
		}
		$url = $nt->escapeLocalURL( 'action=edit&section='.$section );
		return '<span oncontextmenu=\'document.location="'.$url.'";return false;\'>'.$head.'</span>';
	}

	/** @todo document */
	function editSectionLinkForOther( $title, $section ) {
		global $wgRequest;
		global $wgContLang;

		$title = Title::newFromText($title);
		$editurl = '&section='.$section;
		$url = $this->makeKnownLink($title->getPrefixedText(),wfMsg('editsection'),'action=edit'.$editurl);

		if( $wgContLang->isRTL() ) {
			$farside = 'left';
			$nearside = 'right';
		} else {
			$farside = 'right';
			$nearside = 'left';
		}
		return "<div class=\"editsection\" style=\"float:$farside;margin-$nearside:5px;\">[".$url."]</div>";

	}

	/** @todo document */
	function editSectionLink( $nt, $section ) {
		global $wgRequest;
		global $wgContLang;

		if( $wgRequest->getInt( 'oldid' ) && ( $wgRequest->getVal( 'diff' ) != '0' ) ) {
			# Section edit links would be out of sync on an old page.
			# But, if we're diffing to the current page, they'll be
			# correct.
			return '';
		}

		$editurl = '&section='.$section;
		$url = $this->makeKnownLink($nt->getPrefixedText(),wfMsg('editsection'),'action=edit'.$editurl);

		if( $wgContLang->isRTL() ) {
			$farside = 'left';
			$nearside = 'right';
		} else {
			$farside = 'right';
			$nearside = 'left';
		}
		return "<div class=\"editsection\" style=\"float:$farside;margin-$nearside:5px;\">[".$url."]</div>";
	}
}
?>
