<?php

/**
 * Split off some of the internal bits from Skin.php.
 * These functions are used for primarily page content:
 * links, embedded images, table of contents. Links are
 * also used in the skin.
 *
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

	function getExternalLinkAttributes( $link, $text, $class='' ) {
		global $wgContLang;

		$same = ($link == $text);
		$link = urldecode( $link );
		$link = $wgContLang->checkTitleEncoding( $link );
		$link = str_replace( '_', ' ', $link );
		$link = htmlspecialchars( $link );

		$r = ($class != '') ? " class='$class'" : " class='external'";

		if( !$same && $this->mOptions['hover'] ) {
			$r .= " title=\"{$link}\"";
		}
		return $r;
	}

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

	function makeKnownLink( $title, $text = '', $query = '', $trail = '', $prefix = '',$aprops = '') {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail, $prefix , $aprops );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeKnownLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Skin::makeBrokenLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

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
			# Assume $this->postParseLinkColour(). This prevents
			# interwiki links from being parsed as external links.
			global $wgInterwikiLinkHolders;
			$t = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>";
			$nr = array_push($wgInterwikiLinkHolders, $t);
			$retVal = '<!--IWLINK '. ($nr-1) ."-->{$trail}";
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
					$s = $dbr->selectRow( 'cur', array( 'LENGTH(cur_text) AS x', 'cur_namespace',
						'cur_is_redirect' ), array( 'cur_id' => $aid ), $fname ) ;
					if ( $s !== false ) {
						$size = $s->x;
						if ( $s->cur_is_redirect OR $s->cur_namespace != 0 ) {
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

	function fnamePart( $url ) {
		$basename = strrchr( $url, '/' );
		if ( false === $basename ) {
			$basename = $url;
		} else {
			$basename = substr( $basename, 1 );
		}
		return htmlspecialchars( $basename );
	}

	function makeImage( $url, $alt = '' ) {
		global $wgOut;
		if ( '' == $alt ) {
			$alt = $this->fnamePart( $url );
		}
		$s = '<img src="'.$url.'" alt="'.$alt.'" />';
		return $s;
	}

	function makeImageLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeImageLinkObj( $nt, $alt );
	}

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

		$alt = preg_replace( '/<[^>]*>/', '', $alt );
		$alt = preg_replace('/&(?!:amp;|#[Xx][0-9A-fa-f]+;|#[0-9]+;|[a-zA-Z0-9]+;)/', '&amp;', $alt);
		$alt = str_replace( array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $alt );

		$u = $nt->escapeLocalURL();
		if ( $url == '' ) {
			$s = wfMsg( 'missingimage', $img->getName() );
			$s .= "<br>{$alt}<br>{$url}<br>\n";
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
		$alt = preg_replace( '/<[^>]*>/', '', $label);
		$alt = preg_replace('/&(?!:amp;|#[Xx][0-9A-fa-f]+;|#[0-9]+;|[a-zA-Z0-9]+;)/', '&amp;', $alt);
		$alt = str_replace( array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $alt );

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

	function makeMediaLink( $name, $url, $alt = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeMediaLinkObj( $nt, $alt );
	}

	function makeMediaLinkObj( $nt, $alt = '', $nourl=false ) {		
		if ( ! isset( $nt ) )
		{
			### HOTFIX. Instead of breaking, return empty string.
			$s = $alt;
		} else {
			$name = $nt->getDBKey();	
			$img   = Image::newFromTitle( $nt );
			$url = $img->getURL();
			# $nourl can be set by the parser
			# this is a hack to mask absolute URLs, so the parser doesn't
			# linkify them (it is currently not context-aware)
			# 2004-10-25
			if ($nourl) { $url=str_replace("http://","http-noparse://",$url) ; }
			if ( empty( $alt ) ) {
				$alt = preg_replace( '/\.(.+?)^/', '', $name );
			}
			$u = htmlspecialchars( $url );
			$s = "<a href=\"{$u}\" class='internal' title=\"{$alt}\">{$alt}</a>";			
		}
		return $s;
	}

	function specialLink( $name, $key = '' ) {
		global $wgContLang;

		if ( '' == $key ) { $key = strtolower( $name ); }
		$pn = $wgContLang->ucfirst( $name );
		return $this->makeKnownLink( $wgContLang->specialPage( $pn ),
		  wfMsg( $key ) );
	}

	function makeExternalLink( $url, $text, $escape = true ) {
		$style = $this->getExternalLinkAttributes( $url, $text );
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
		$medians = $wgContLang->getNsText(Namespace::getMedia()).':';
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
	
	function tocIndent($level) {
		return str_repeat( '<div class="tocindent">'."\n", $level>0 ? $level : 0 );
	}

	function tocUnindent($level) {
		return str_repeat( "</div>\n", $level>0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 */
	function tocLine( $anchor, $tocline, $level ) {
		$link = '<a href="#'.$anchor.'">'.$tocline.'</a><br />';
		if($level) {
			return $link."\n";
		} else {
			return '<div class="tocline">'.$link."</div>\n";
		}

	}

	function tocTable($toc) {
		# note to CSS fanatics: putting this in a div does not work -- div won't auto-expand
		# try min-width & co when somebody gets a chance
		$hideline = ' <script type="text/javascript">showTocToggle("' . addslashes( wfMsg('showtoc') ) . '","' . addslashes( wfMsg('hidetoc') ) . '")</script>';
		return
		'<table border="0" id="toc"><tr id="toctitle"><td align="center">'."\n".
		'<b>'.wfMsgForContent('toc').'</b>' .
		$hideline .
		'</td></tr><tr id="tocinside"><td>'."\n".
		$toc."</td></tr></table>\n";
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

	function editSectionScript( $nt, $section, $head ) {
		global $wgRequest;
		if( $wgRequest->getInt( 'oldid' ) && ( $wgRequest->getVal( 'diff' ) != '0' ) ) {
			return $head;
		}
		$url = $nt->escapeLocalURL( 'action=edit&section='.$section );
		return '<span oncontextmenu=\'document.location="'.$url.'";return false;\'>'.$head.'</span>';
	}

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

	/**
	 * @access public
	 */
	function suppressUrlExpansion() {
		return false;
	}

}

?>