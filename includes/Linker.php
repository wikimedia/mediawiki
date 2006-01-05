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

	function Linker() {}

	/**
	 * @deprecated
	 */
	function postParseLinkColour( $s = NULL ) {
		return NULL;
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

		$r .= " title=\"{$link}\"";
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

		$r .= " title=\"{$link}\"";
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

		$r .= ' title="' . $nt->getEscapedText() . '"';
		return $r;
	}

	/**
	 * Note: This function MUST call getArticleID() on the link,
	 * otherwise the cache won't get updated properly.  See LINKCACHE.DOC.
	 */
	function makeLink( $title, $text = '', $query = '', $trail = '' ) {
		wfProfileIn( 'Linker::makeLink' );
	 	$nt = Title::newFromText( $title );
		if ($nt) {
			$result = $this->makeLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeLink(): "'.$title."\"\n" );
			$result = $text == "" ? $title : $text;
		}

		wfProfileOut( 'Linker::makeLink' );
		return $result;
	}

	/** @todo document */
	function makeKnownLink( $title, $text = '', $query = '', $trail = '', $prefix = '',$aprops = '') {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail, $prefix , $aprops );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeKnownLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/** @todo document */
	function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeBrokenLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/** @todo document */
	function makeStubLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeStubLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeStubLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeLinkObj( $nt, $text= '', $query = '', $trail = '', $prefix = '' ) {
		global $wgUser;
		$fname = 'Linker::makeLinkObj';
		wfProfileIn( $fname );

		# Fail gracefully
		if ( ! is_object($nt) ) {
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

			# Check for anchors, normalize the anchor

			$parts = explode( '#', $u, 2 );
			if ( count( $parts ) == 2 ) {
				$anchor = urlencode( Sanitizer::decodeCharReferences( str_replace(' ', '_', $parts[1] ) ) );
				$replacearray = array(
					'%3A' => ':',
					'%' => '.'
				);
				$u = $parts[0] . '#' .
				     str_replace( array_keys( $replacearray ),
				    		 array_values( $replacearray ),
						 $anchor );
			}

			$t = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>";
				
			wfProfileOut( $fname );
			return $t;
		} elseif ( $nt->isAlwaysKnown() ) {
			# Image links, special page links and self-links with fragements are always known.
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
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
						array( 'page' ),
						array( 'page_len',
							'page_namespace',
							'page_is_redirect' ),
						array( 'page_id' => $aid ), $fname ) ;
					if ( $s !== false ) {
						$size = $s->page_len;
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
	function makeKnownLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' , $aprops = '' ) {
		global $wgTitle;

		$fname = 'Linker::makeKnownLinkObj';
		wfProfileIn( $fname );

		if ( !is_object( $nt ) ) {
			wfProfileOut( $fname );
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
			$anchor = urlencode( Sanitizer::decodeCharReferences( str_replace( ' ', '_', $nt->getFragment() ) ) );
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
		
		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$r = "<a href=\"{$u}\"{$style}{$aprops}>{$prefix}{$text}{$inside}</a>{$trail}";
		wfProfileOut( $fname );
		return $r;
	}

	/**
	 * Pass a title object, not a title string
	 */
	function makeBrokenLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$fname = 'Linker::makeBrokenLinkObj';
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
		
		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";

		wfProfileOut( $fname );
		return $s;
	}

	/**
 	 * Pass a title object, not a title string
	 */
	function makeStubLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$link = $nt->getPrefixedURL();

		$u = $nt->escapeLocalURL( $query );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, 'stub' );

		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$s = "<a href=\"{$u}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";
		return $s;
	}

	/**
	 * Generate either a normal exists-style link or a stub link, depending
	 * on the given page size.
	 *
 	 * @param int $size
 	 * @param Title $nt
 	 * @param string $text
 	 * @param string $query
 	 * @param string $trail
 	 * @param string $prefix
 	 * @return string HTML of link
	 */
	function makeSizeLinkObj( $size, $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgUser;
		$threshold = IntVal( $wgUser->getOption( 'stubthreshold' ) );
		if( $size < $threshold ) {
			return $this->makeStubLinkObj( $nt, $text, $query, $trail, $prefix );
		} else {
			return $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		}
	}

	/** @todo document */
	function makeSelfLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$u = $nt->escapeLocalURL( $query );
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		list( $inside, $trail ) = Linker::splitTrail( $trail );
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

	/** Obsolete alias */
	function makeImage( $url, $alt = '' ) {
		return $this->makeExternalImage( $url, $alt );
	}

	/** @todo document */
	function makeExternalImage( $url, $alt = '' ) {
		if ( '' == $alt ) {
			$alt = $this->fnamePart( $url );
		}
		$s = '<img src="'.$url.'" alt="'.$alt.'" />';
		return $s;
	}

	/** @todo document */
	function makeImageLinkObj( $nt, $label, $alt, $align = '', $width = false, $height = false, $framed = false, 
	  $thumb = false, $manual_thumb = '' ) 
	{
		global $wgContLang, $wgUser, $wgThumbLimits;
		
		$img   = new Image( $nt );
		if ( !$img->allowInlineDisplay() ) {
			return $this->makeKnownLinkObj( $nt );
		}

		$url   = $img->getViewURL();
		$prefix = $postfix = '';
		
		wfDebug( "makeImageLinkObj: '$width'x'$height'\n" );
		
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
			# to the default user option as specified in Language*.php
			if ( $align == '' ) {
				$align = $wgContLang->isRTL() ? 'left' : 'right';
			}

			
			if ( $width === false ) {
				$wopt = $wgUser->getOption( 'thumbsize' );

				if( !isset( $wgThumbLimits[$wopt] ) ) {
					 $wopt = User::getDefaultOption( 'thumbsize' );
				}
				
				$width = min( $img->getWidth(), $wgThumbLimits[$wopt] );
			}
			
			return $prefix.$this->makeThumbLinkObj( $img, $label, $alt, $align, $width, $height, $framed, $manual_thumb ).$postfix;

		} elseif ( $width ) {

			# Create a resized image, without the additional thumbnail
			# features

			if ( $height !== false && ( $img->getHeight() * $width / $img->getWidth() > $height ) ) {
				$width = $img->getWidth() * $height / $img->getHeight();
			}
			if ( $manual_thumb == '') {
				$thumb = $img->getThumbnail( $width );
				if ( $thumb ) {
					if( $width > $thumb->width ) {
						// Requested a display size larger than the actual image;
						// fake it up!
						$height = floor($thumb->height * $width / $thumb->width);
						wfDebug( "makeImageLinkObj: client-size height set to '$height'\n" );
					} else {
						$height = $thumb->height;
						wfDebug( "makeImageLinkObj: thumb height set to '$height'\n" );
					}
					$url = $thumb->getUrl();
				}
			}
		} else {
			$width = $img->width;
			$height = $img->height;
		}

		wfDebug( "makeImageLinkObj2: '$width'x'$height'\n" );
		$u = $nt->escapeLocalURL();
		if ( $url == '' ) {
			$s = $this->makeBrokenImageLinkObj( $img->getTitle() );
			//$s .= "<br />{$alt}<br />{$url}<br />\n";
		} else {
			$s = '<a href="'.$u.'" class="image" title="'.$alt.'">' .
				 '<img src="'.$url.'" alt="'.$alt.'" ' .
				 ( $width
				 	? ( 'width="'.$width.'" height="'.$height.'" ' )
				 	: '' ) .
				 'longdesc="'.$u.'" /></a>';
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
	function makeThumbLinkObj( $img, $label = '', $alt, $align = 'right', $boxwidth = 180, $boxheight=false, $framed=false , $manual_thumb = "" ) {
		global $wgStylePath, $wgContLang;
		$url  = $img->getViewURL();

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
			$h  = round( $height/($width/$boxwidth) );
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
			$manual_img = new Image( $manual_title );
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
			$s .= $this->makeBrokenImageLinkObj( $img->getTitle() );
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
	
	/**
	 * Pass a title object, not a title string
	 */
	function makeBrokenImageLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		# Fail gracefully
		if ( ! isset($nt) ) {
			# wfDebugDieBacktrace();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$fname = 'Linker::makeBrokenImageLinkObj';
		wfProfileIn( $fname );

		$q = 'wpDestFile=' . urlencode( $nt->getDBkey() );
		if ( '' != $query ) {
			$q .= "&$query";
		}
		$uploadTitle = Title::makeTitle( NS_SPECIAL, 'Upload' );
		$url = $uploadTitle->escapeLocalURL( $q );

		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		$style = $this->getInternalLinkAttributesObj( $nt, $text, "yes" );
		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$s = "<a href=\"{$url}\"{$style}>{$prefix}{$text}{$inside}</a>{$trail}";

		wfProfileOut( $fname );
		return $s;
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
	function makeMediaLinkObj( $title, $text = '' ) {
		if( is_null( $title ) ) {
			### HOTFIX. Instead of breaking, return empty string.
			return $text;
		} else {
			$name = $title->getDBKey();	
			$img  = new Image( $title );
			if( $img->exists() ) {
				$url  = $img->getURL();
				$class = 'internal';
			} else {
				$upload = Title::makeTitle( NS_SPECIAL, 'Upload' );
				$url = $upload->getLocalUrl( 'wpDestFile=' . urlencode( $img->getName() ) );
				$class = 'new';
			}
			$alt = htmlspecialchars( $title->getText() );
			if( $text == '' ) {
				$text = $alt;
			}
			$u = htmlspecialchars( $url );
			return "<a href=\"{$u}\" class='$class' title=\"{$alt}\">{$text}</a>";			
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
		$fname = 'Linker::formatComment';
		wfProfileIn( $fname );
		
		global $wgContLang;
		$comment = str_replace( "\n", " ", $comment );
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
		global $wgJsMimeType;
		return "<table id='toc' class='toc'><tr><td>" 
			   . "<div id='toctitle'><h2>" . wfMsgForContent('toc') . "</h2></div>\n"
		     . $toc
				 . "</ul>\n</td></tr></table>\n"
				 . '<script type="'.$wgJsMimeType.'">'
				 . ' if (window.showTocToggle) {'
				 . ' var tocShowText = "' . wfEscapeJsString( wfMsgForContent('showtoc') ) . '";'
				 . ' var tocHideText = "' . wfEscapeJsString( wfMsgForContent('hidetoc') ) . '";'
				 . ' showTocToggle();'
				 . ' } '
				 . "</script>\n";
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
		global $wgContLang;

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
	 * Split a link trail, return the "inside" portion and the remainder of the trail
	 * as a two-element array
	 * 
	 * @static
	 */
	function splitTrail( $trail ) {
		static $regex = false;
		if ( $regex === false ) {
			global $wgContLang;
			$regex = $wgContLang->linkTrail();
		}
		$inside = '';
		if ( '' != $trail ) {
			if ( preg_match( $regex, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		return array( $inside, $trail );
	}

}
?>
