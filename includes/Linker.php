<?php
/**
 * Split off some of the internal bits from Skin.php.
 * These functions are used for primarily page content:
 * links, embedded images, table of contents. Links are
 * also used in the skin.
 * For the moment, Skin is a descendent class of Linker.
 * In the future, it should probably be further split
 * so that ever other bit of the wiki doesn't have to
 * go loading up Skin to get at it.
 *
 * @addtogroup Skins
 */
class Linker {

	/**
	 * Flags for userToolLinks()
	 */
	const TOOL_LINKS_NOBLOCK = 1;

	function __construct() {}

	/**
	 * @deprecated
	 */
	function postParseLinkColour( $s = NULL ) {
		return NULL;
	}

	/** @todo document */
	function getExternalLinkAttributes( $link, $text, $class='' ) {
		$link = htmlspecialchars( $link );

		$r = ($class != '') ? " class=\"$class\"" : " class=\"external\"";

		$r .= " title=\"{$link}\"";
		return $r;
	}

	function getInterwikiLinkAttributes( $link, $text, $class='' ) {
		global $wgContLang;

		$link = urldecode( $link );
		$link = $wgContLang->checkTitleEncoding( $link );
		$link = preg_replace( '/[\\x00-\\x1f]/', ' ', $link );
		$link = htmlspecialchars( $link );

		$r = ($class != '') ? " class=\"$class\"" : " class=\"external\"";

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
	 * @param $nt Title object.
	 * @param $text String: FIXME
	 * @param $broken Boolean: FIXME, default 'false'.
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
	 * This function is a shortcut to makeLinkObj(Title::newFromText($title),...). Do not call
	 * it if you already have a title object handy. See makeLinkObj for further documentation.
	 *
	 * @param $title String: the text of the title
	 * @param $text  String: link text
	 * @param $query String: optional query part
	 * @param $trail String: optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
	function makeLink( $title, $text = '', $query = '', $trail = '' ) {
		wfProfileIn( 'Linker::makeLink' );
	 	$nt = Title::newFromText( $title );
		if ($nt) {
			$result = $this->makeLinkObj( $nt, $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeLink(): "'.$title."\"\n" );
			$result = $text == "" ? $title : $text;
		}

		wfProfileOut( 'Linker::makeLink' );
		return $result;
	}

	/**
	 * This function is a shortcut to makeKnownLinkObj(Title::newFromText($title),...). Do not call
	 * it if you already have a title object handy. See makeKnownLinkObj for further documentation.
	 * 
	 * @param $title String: the text of the title
	 * @param $text  String: link text
	 * @param $query String: optional query part
	 * @param $trail String: optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
	function makeKnownLink( $title, $text = '', $query = '', $trail = '', $prefix = '',$aprops = '') {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeKnownLinkObj( Title::newFromText( $title ), $text, $query, $trail, $prefix , $aprops );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeKnownLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * This function is a shortcut to makeBrokenLinkObj(Title::newFromText($title),...). Do not call
	 * it if you already have a title object handy. See makeBrokenLinkObj for further documentation.
	 * 
	 * @param string $title The text of the title
	 * @param string $text Link text
	 * @param string $query Optional query part
	 * @param string $trail Optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
	function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ($nt) {
			return $this->makeBrokenLinkObj( Title::newFromText( $title ), $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to Linker::makeBrokenLink(): "'.$title."\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * This function is a shortcut to makeStubLinkObj(Title::newFromText($title),...). Do not call
	 * it if you already have a title object handy. See makeStubLinkObj for further documentation.
	 * 
	 * @param $title String: the text of the title
	 * @param $text  String: link text
	 * @param $query String: optional query part
	 * @param $trail String: optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
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
	 * Make a link for a title which may or may not be in the database. If you need to
	 * call this lots of times, pre-fill the link cache with a LinkBatch, otherwise each
	 * call to this will result in a DB query.
	 * 
	 * @param $nt     Title: the title object to make the link from, e.g. from
	 *                      Title::newFromText.
	 * @param $text  String: link text
	 * @param $query String: optional query part
	 * @param $trail String: optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 * @param $prefix String: optional prefix. As trail, only before instead of after.
	 */
	function makeLinkObj( $nt, $text= '', $query = '', $trail = '', $prefix = '' ) {
		global $wgUser;
		$fname = 'Linker::makeLinkObj';
		wfProfileIn( $fname );

		# Fail gracefully
		if ( ! is_object($nt) ) {
			# throw new MWException();
			wfProfileOut( $fname );
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		if ( $nt->isExternal() ) {
			$u = $nt->getFullURL();
			$link = $nt->getPrefixedURL();
			if ( '' == $text ) { $text = $nt->getPrefixedText(); }
			$style = $this->getInterwikiLinkAttributes( $link, $text, 'extiw' );

			$inside = '';
			if ( '' != $trail ) {
				$m = array();
				if ( preg_match( '/^([a-z]+)(.*)$$/sD', $trail, $m ) ) {
					$inside = $m[1];
					$trail = $m[2];
				}
			}
			$t = "<a href=\"{$u}\"{$style}>{$text}{$inside}</a>";

			wfProfileOut( $fname );
			return $t;
		} elseif ( $nt->isAlwaysKnown() ) {
			# Image links, special page links and self-links with fragements are always known.
			$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		} else {
			wfProfileIn( $fname.'-immediate' );

			# Handles links to special pages wich do not exist in the database:
			if( $nt->getNamespace() == NS_SPECIAL ) {
				if( SpecialPage::exists( $nt->getDbKey() ) ) {
					$retVal = $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
				} else {
					$retVal = $this->makeBrokenLinkObj( $nt, $text, $query, $trail, $prefix );
				}
				wfProfileOut( $fname.'-immediate' );
				wfProfileOut( $fname );
				return $retVal;
			}

			# Work out link colour immediately
			$aid = $nt->getArticleID() ;
			if ( 0 == $aid ) {
				$retVal = $this->makeBrokenLinkObj( $nt, $text, $query, $trail, $prefix );
			} else {
				$stub = false;
				if ( $nt->isContentPage() ) {
					$threshold = $wgUser->getOption('stubthreshold');
					if ( $threshold > 0 ) {
						$dbr = wfGetDB( DB_SLAVE );
						$s = $dbr->selectRow(
							array( 'page' ),
							array( 'page_len',
							       'page_is_redirect' ),
							array( 'page_id' => $aid ), $fname ) ;
						$stub = ( $s !== false && !$s->page_is_redirect &&
							  $s->page_len < $threshold );
					}
				}
				if ( $stub ) {
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
	 * Make a link for a title which definitely exists. This is faster than makeLinkObj because
	 * it doesn't have to do a database query. It's also valid for interwiki titles and special
	 * pages.
	 *
	 * @param $nt Title object of target page
	 * @param $text   String: text to replace the title
	 * @param $query  String: link target
	 * @param $trail  String: text after link
	 * @param $prefix String: text before link text
	 * @param $aprops String: extra attributes to the a-element
	 * @param $style  String: style to apply - if empty, use getInternalLinkAttributesObj instead
	 * @return the a-element
	 */
	function makeKnownLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' , $aprops = '', $style = '' ) {

		$fname = 'Linker::makeKnownLinkObj';
		wfProfileIn( $fname );

		if ( !is_object( $nt ) ) {
			wfProfileOut( $fname );
			return $text;
		}

		$u = $nt->escapeLocalURL( $query );
		if ( $nt->getFragment() != '' ) {
			if( $nt->getPrefixedDbkey() == '' ) {
				$u = '';
				if ( '' == $text ) {
					$text = htmlspecialchars( $nt->getFragment() );
				}
			}
			$u .= $nt->getFragmentForURL();
		}
		if ( $text == '' ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		if ( $style == '' ) {
			$style = $this->getInternalLinkAttributesObj( $nt, $text );
		}

		if ( $aprops !== '' ) $aprops = ' ' . $aprops;

		list( $inside, $trail ) = Linker::splitTrail( $trail );
		$r = "<a href=\"{$u}\"{$style}{$aprops}>{$prefix}{$text}{$inside}</a>{$trail}";
		wfProfileOut( $fname );
		return $r;
	}

	/**
	 * Make a red link to the edit page of a given title.
	 * 
	 * @param $title String: The text of the title
	 * @param $text  String: Link text
	 * @param $query String: Optional query part
	 * @param $trail String: Optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
	function makeBrokenLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		# Fail gracefully
		if ( ! isset($nt) ) {
			# throw new MWException();
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}

		$fname = 'Linker::makeBrokenLinkObj';
		wfProfileIn( $fname );

		if( $nt->getNamespace() == NS_SPECIAL ) {
			$q = $query;
		} else if ( '' == $query ) {
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
	 * Make a brown link to a short article.
	 * 
	 * @param $title String: the text of the title
	 * @param $text  String: link text
	 * @param $query String: optional query part
	 * @param $trail String: optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 */
	function makeStubLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		$style = $this->getInternalLinkAttributesObj( $nt, $text, 'stub' );
		return $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix, '', $style );
	}

	/**
	 * Generate either a normal exists-style link or a stub link, depending
	 * on the given page size.
	 *
 	 * @param $size Integer
 	 * @param $nt Title object.
 	 * @param $text String
 	 * @param $query String
 	 * @param $trail String
 	 * @param $prefix String
 	 * @return string HTML of link
	 */
	function makeSizeLinkObj( $size, $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgUser;
		$threshold = intval( $wgUser->getOption( 'stubthreshold' ) );
		if( $size < $threshold ) {
			return $this->makeStubLinkObj( $nt, $text, $query, $trail, $prefix );
		} else {
			return $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		}
	}

	/** 
	 * Make appropriate markup for a link to the current article. This is currently rendered
	 * as the bold link text. The calling sequence is the same as the other make*LinkObj functions,
	 * despite $query not being used.
	 */
	function makeSelfLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		if ( '' == $text ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		list( $inside, $trail ) = Linker::splitTrail( $trail );
		return "<strong class=\"selflink\">{$prefix}{$text}{$inside}</strong>{$trail}";
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

	/** Creates the HTML source for images
	* @param object $nt
	* @param string $label label text
	* @param string $alt alt text
	* @param string $align horizontal alignment: none, left, center, right)
	* @param array $params some format keywords: width, height, page, upright, upright_factor, frameless, border
	* @param boolean $framed shows image in original size in a frame
	* @param boolean $thumb shows image as thumbnail in a frame
	* @param string $manual_thumb image name for the manual thumbnail
	* @param string $valign vertical alignment: baseline, sub, super, top, text-top, middle, bottom, text-bottom
	* @return string
	*/
	function makeImageLinkObj( $nt, $label, $alt, $align = '', $params = array(), $framed = false,
	  $thumb = false, $manual_thumb = '', $valign = '', $time = false )
	{
		global $wgContLang, $wgUser, $wgThumbLimits, $wgThumbUpright;

		$img = wfFindFile( $nt, $time );

		if ( $img && !$img->allowInlineDisplay() ) {
			wfDebug( __METHOD__.': '.$nt->getPrefixedDBkey()." does not allow inline display\n" );
			return $this->makeKnownLinkObj( $nt );
		}

		$error = $prefix = $postfix = '';
		$page = isset( $params['page'] ) ? $params['page'] : false;

		if ( 'center' == $align )
		{
			$prefix  = '<div class="center">';
			$postfix = '</div>';
			$align   = 'none';
		}
		if ( $img && !isset( $params['width'] ) ) {
			$params['width'] = $img->getWidth( $page );
			if( $thumb || $framed || isset( $params['frameless'] ) ) {
				$wopt = $wgUser->getOption( 'thumbsize' );

				if( !isset( $wgThumbLimits[$wopt] ) ) {
					 $wopt = User::getDefaultOption( 'thumbsize' );
				}

				// Reduce width for upright images when parameter 'upright' is used
				if ( !isset( $params['upright_factor'] ) || $params['upright_factor'] == 0 ) {
					$params['upright_factor'] = $wgThumbUpright;
				}
				// Use width which is smaller: real image width or user preference width
				// For caching health: If width scaled down due to upright parameter, round to full __0 pixel to avoid the creation of a lot of odd thumbs
				$params['width'] = min( $params['width'], isset( $params['upright'] ) ? round( $wgThumbLimits[$wopt] * $params['upright_factor'], -1 ) : $wgThumbLimits[$wopt] );
			}
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
			return $prefix.$this->makeThumbLinkObj( $nt, $img, $label, $alt, $align, $params, $framed, $manual_thumb ).$postfix;
		}

		if ( $img && $params['width'] ) {
			# Create a resized image, without the additional thumbnail features
			$thumb = $img->transform( $params );
		} else {
			$thumb = false;
		}

		if ( $page ) {
			$query = 'page=' . urlencode( $page );
		} else {
			$query = '';
		}
		$u = $nt->getLocalURL( $query );
		$imgAttribs = array(
			'alt' => $alt,
			'longdesc' => $u
		);

		if ( $valign ) {
			$imgAttribs['style'] = "vertical-align: $valign";
		}
		if ( isset( $params['border'] ) ) {
			$imgAttribs['class'] = "thumbborder";
		}
		$linkAttribs = array(
			'href' => $u,
			'class' => 'image',
			'title' => $alt
		);

		if ( !$thumb ) {
			$s = $this->makeBrokenImageLinkObj( $nt );
		} else {
			$s = $thumb->toHtml( $imgAttribs, $linkAttribs );
		}
		if ( '' != $align ) {
			$s = "<div class=\"float{$align}\"><span>{$s}</span></div>";
		}
		return str_replace("\n", ' ',$prefix.$s.$postfix);
	}

	/**
	 * Make HTML for a thumbnail including image, border and caption
	 * @param Title $nt 
	 * @param Image $img Image object or false if it doesn't exist
	 */
	function makeThumbLinkObj( Title $nt, $img, $label = '', $alt, $align = 'right', $params = array(), $framed=false , $manual_thumb = "" ) {
		global $wgStylePath, $wgContLang;
		$exists = $img && $img->exists();

		$page = isset( $params['page'] ) ? $params['page'] : false;

		if ( empty( $params['width'] ) ) {
			// Reduce width for upright images when parameter 'upright' is used 
			$params['width'] = isset( $params['upright'] ) ? 130 : 180;
		}
		$thumb = false;

		if ( !$exists ) {
			$outerWidth = $params['width'] + 2;
		} else {
			if ( $manual_thumb != '' ) {
				# Use manually specified thumbnail
				$manual_title = Title::makeTitleSafe( NS_IMAGE, $manual_thumb );
				if( $manual_title ) {
					$manual_img = wfFindFile( $manual_title );
					if ( $manual_img ) {
						$thumb = $manual_img->getUnscaledThumb();
					} else {
						$exists = false;
					}
				}
			} elseif ( $framed ) {
				// Use image dimensions, don't scale
				$thumb = $img->getUnscaledThumb( $page );
			} else {
				# Do not present an image bigger than the source, for bitmap-style images
				# This is a hack to maintain compatibility with arbitrary pre-1.10 behaviour
				$srcWidth = $img->getWidth( $page );
				if ( $srcWidth && !$img->mustRender() && $params['width'] > $srcWidth ) {
					$params['width'] = $srcWidth;
				}
				$thumb = $img->transform( $params );
			}

			if ( $thumb ) {
				$outerWidth = $thumb->getWidth() + 2;
			} else {
				$outerWidth = $params['width'] + 2;
			}
		}

		$query = $page ? 'page=' . urlencode( $page ) : '';
		$u = $nt->getLocalURL( $query );

		$more = htmlspecialchars( wfMsg( 'thumbnail-more' ) );
		$magnifyalign = $wgContLang->isRTL() ? 'left' : 'right';
		$textalign = $wgContLang->isRTL() ? ' style="text-align:right"' : '';

		$s = "<div class=\"thumb t{$align}\"><div class=\"thumbinner\" style=\"width:{$outerWidth}px;\">";
		if( !$exists ) {
			$s .= $this->makeBrokenImageLinkObj( $nt );
			$zoomicon = '';
		} elseif ( !$thumb ) {
			$s .= htmlspecialchars( wfMsg( 'thumbnail_error', '' ) );
			$zoomicon = '';
		} else {
			$imgAttribs = array(
				'alt' => $alt,
				'longdesc' => $u,
				'class' => 'thumbimage'
			);
			$linkAttribs = array(
				'href' => $u,
				'class' => 'internal',
				'title' => $alt
			);
				
			$s .= $thumb->toHtml( $imgAttribs, $linkAttribs );
			if ( $framed ) {
				$zoomicon="";
			} else {
				$zoomicon =  '<div class="magnify" style="float:'.$magnifyalign.'">'.
					'<a href="'.$u.'" class="internal" title="'.$more.'">'.
					'<img src="'.$wgStylePath.'/common/images/magnify-clip.png" ' .
					'width="15" height="11" alt="" /></a></div>';
			}
		}
		$s .= '  <div class="thumbcaption"'.$textalign.'>'.$zoomicon.$label."</div></div></div>";
		return str_replace("\n", ' ', $s);
	}

	/**
	 * Make a "broken" link to an image
	 *
	 * @param Title $title Image title
	 * @param string $text Link label
	 * @param string $query Query string
	 * @param string $trail Link trail
	 * @param string $prefix Link prefix
	 * @return string
	 */
	public function makeBrokenImageLinkObj( $title, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgEnableUploads;
		if( $title instanceof Title ) {
			wfProfileIn( __METHOD__ );
			if( $wgEnableUploads ) {
				$upload = SpecialPage::getTitleFor( 'Upload' );
				if( $text == '' )
					$text = htmlspecialchars( $title->getPrefixedText() );
				$q = 'wpDestFile=' . $title->getPartialUrl();
				if( $query != '' )
					$q .= '&' . $query;
				list( $inside, $trail ) = self::splitTrail( $trail );
				$style = $this->getInternalLinkAttributesObj( $title, $text, 'yes' );
				wfProfileOut( __METHOD__ );
				return '<a href="' . $upload->escapeLocalUrl( $q ) . '"'
					. $style . '>' . $prefix . $text . $inside . '</a>' . $trail;
			} else {
				wfProfileOut( __METHOD__ );
				return $this->makeKnownLinkObj( $title, $text, $query, $trail, $prefix );
			}
		} else {
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}
	}

	/** @deprecated use Linker::makeMediaLinkObj() */
	function makeMediaLink( $name, $unused = '', $text = '' ) {
		$nt = Title::makeTitleSafe( NS_IMAGE, $name );
		return $this->makeMediaLinkObj( $nt, $text );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 *
	 * @param $title Title object.
	 * @param $text  String: pre-sanitized HTML
	 * @return string HTML
	 *
	 * @public
	 * @todo Handle invalid or missing images better.
	 */
	function makeMediaLinkObj( $title, $text = '' ) {
		if( is_null( $title ) ) {
			### HOTFIX. Instead of breaking, return empty string.
			return $text;
		} else {
			$img  = wfFindFile( $title );
			if( $img ) {
				$url  = $img->getURL();
				$class = 'internal';
			} else {
				$upload = SpecialPage::getTitleFor( 'Upload' );
				$url = $upload->getLocalUrl( 'wpDestFile=' . urlencode( $title->getDbKey() ) );
				$class = 'new';
			}
			$alt = htmlspecialchars( $title->getText() );
			if( $text == '' ) {
				$text = $alt;
			}
			$u = htmlspecialchars( $url );
			return "<a href=\"{$u}\" class=\"$class\" title=\"{$alt}\">{$text}</a>";
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
	function makeExternalLink( $url, $text, $escape = true, $linktype = '', $ns = null ) {
		$style = $this->getExternalLinkAttributes( $url, $text, 'external ' . $linktype );
		global $wgNoFollowLinks, $wgNoFollowNsExceptions;
		if( $wgNoFollowLinks && !(isset($ns) && in_array($ns, $wgNoFollowNsExceptions)) ) {
			$style .= ' rel="nofollow"';
		}
		$url = htmlspecialchars( $url );
		if( $escape ) {
			$text = htmlspecialchars( $text );
		}
		return '<a href="'.$url.'"'.$style.'>'.$text.'</a>';
	}

	/**
	 * Make user link (or user contributions for unregistered users)
	 * @param $userId   Integer: user id in database.
	 * @param $userText String: user name in database
	 * @return string HTML fragment
	 * @private
	 */
	function userLink( $userId, $userText ) {
		$encName = htmlspecialchars( $userText );
		if( $userId == 0 ) {
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $userText );
			return $this->makeKnownLinkObj( $contribsPage,
				$encName);
		} else {
			$userPage = Title::makeTitle( NS_USER, $userText );
			return $this->makeLinkObj( $userPage, $encName );
		}
	}

	/**
	 * Generate standard user tool links (talk, contributions, block link, etc.)
	 *
	 * @param int $userId User identifier
	 * @param string $userText User name or IP address
	 * @param bool $redContribsWhenNoEdits Should the contributions link be red if the user has no edits?
	 * @param int $flags Customisation flags (e.g. self::TOOL_LINKS_NOBLOCK)
	 * @return string
	 */
	public function userToolLinks( $userId, $userText, $redContribsWhenNoEdits = false, $flags = 0 ) {
		global $wgUser, $wgDisableAnonTalk, $wgSysopUserBans;
		$talkable = !( $wgDisableAnonTalk && 0 == $userId );
		$blockable = ( $wgSysopUserBans || 0 == $userId ) && !$flags & self::TOOL_LINKS_NOBLOCK;

		$items = array();
		if( $talkable ) {
			$items[] = $this->userTalkLink( $userId, $userText );
		}
		if( $userId ) {
			// check if the user has an edit
			if( $redContribsWhenNoEdits && User::edits( $userId ) == 0 ) {
				$style = " class='new'";
			} else {
				$style = '';
			}
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $userText );

			$items[] = $this->makeKnownLinkObj( $contribsPage, wfMsgHtml( 'contribslink' ), '', '', '', '', $style );
		}
		if( $blockable && $wgUser->isAllowed( 'block' ) ) {
			$items[] = $this->blockLink( $userId, $userText );
		}

		if( $items ) {
			return ' (' . implode( ' | ', $items ) . ')';
		} else {
			return '';
		}
	}

	/**
	 * Alias for userToolLinks( $userId, $userText, true );
	 */
	public function userToolLinksRedContribs( $userId, $userText ) {
		return $this->userToolLinks( $userId, $userText, true );
	}


	/**
	 * @param $userId Integer: user id in database.
	 * @param $userText String: user name in database.
	 * @return string HTML fragment with user talk link
	 * @private
	 */
	function userTalkLink( $userId, $userText ) {
		$userTalkPage = Title::makeTitle( NS_USER_TALK, $userText );
		$userTalkLink = $this->makeLinkObj( $userTalkPage, wfMsgHtml( 'talkpagelinktext' ) );
		return $userTalkLink;
	}

	/**
	 * @param $userId Integer: userid
	 * @param $userText String: user name in database.
	 * @return string HTML fragment with block link
	 * @private
	 */
	function blockLink( $userId, $userText ) {
		$blockPage = SpecialPage::getTitleFor( 'Blockip', $userText );
		$blockLink = $this->makeKnownLinkObj( $blockPage,
			wfMsgHtml( 'blocklink' ) );
		return $blockLink;
	}
	
	/**
	 * Generate a user link if the current user is allowed to view it
	 * @param $rev Revision object.
	 * @return string HTML
	 */
	function revUserLink( $rev ) {
		if( $rev->userCan( Revision::DELETED_USER ) ) {
			$link = $this->userLink( $rev->getRawUser(), $rev->getRawUserText() );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @param $rev Revision object.
	 * @return string HTML
	 */
	function revUserTools( $rev ) {
		if( $rev->userCan( Revision::DELETED_USER ) ) {
			$link = $this->userLink( $rev->getRawUser(), $rev->getRawUserText() ) .
				' ' .
				$this->userToolLinks( $rev->getRawUser(), $rev->getRawUserText() );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}
	
	/**
	 * This function is called by all recent changes variants, by the page history,
	 * and by the user contributions list. It is responsible for formatting edit
	 * comments. It escapes any HTML in the comment, but adds some CSS to format
	 * auto-generated comments (from section editing) and formats [[wikilinks]].
	 *
	 * @author Erik Moeller <moeller@scireview.de>
	 *
	 * Note: there's not always a title to pass to this function.
	 * Since you can't set a default parameter for a reference, I've turned it
	 * temporarily to a value pass. Should be adjusted further. --brion
	 *
	 * @param string $comment
	 * @param mixed $title Title object (to generate link to the section in autocomment) or null
	 * @param bool $local Whether section links should refer to local page
	 */
	function formatComment($comment, $title = NULL, $local = false) {
		wfProfileIn( __METHOD__ );

		# Sanitize text a bit:
		$comment = str_replace( "\n", " ", $comment );
		$comment = htmlspecialchars( $comment );

		# Render autocomments and make links:
		$comment = $this->formatAutoComments( $comment, $title, $local );
		$comment = $this->formatLinksInComment( $comment );

		wfProfileOut( __METHOD__ );
		return $comment;
	}

	/**
	 * The pattern for autogen comments is / * foo * /, which makes for
	 * some nasty regex.
	 * We look for all comments, match any text before and after the comment,
	 * add a separator where needed and format the comment itself with CSS
	 * Called by Linker::formatComment.
	 *
	 * @param $comment Comment text
	 * @param $title An optional title object used to links to sections
	 *
	 * @todo Document the $local parameter.
	 */
	private function formatAutocomments( $comment, $title = NULL, $local = false ) {
		$match = array();
		while (preg_match('!(.*)/\*\s*(.*?)\s*\*/(.*)!', $comment,$match)) {
			$pre=$match[1];
			$auto=$match[2];
			$post=$match[3];
			$link='';
			if( $title ) {
				$section = $auto;

				# Generate a valid anchor name from the section title.
				# Hackish, but should generally work - we strip wiki
				# syntax, including the magic [[: that is used to
				# "link rather than show" in case of images and
				# interlanguage links.
				$section = str_replace( '[[:', '', $section );
				$section = str_replace( '[[', '', $section );
				$section = str_replace( ']]', '', $section );
				if ( $local ) {
					$sectionTitle = Title::newFromText( '#' . $section);
				} else {
					$sectionTitle = wfClone( $title );
					$sectionTitle->mFragment = $section;
				}
				$link = $this->makeKnownLinkObj( $sectionTitle, wfMsg( 'sectionlink' ) );
			}
			$sep='-';
			$auto=$link.$auto;
			if($pre) { $auto = $sep.' '.$auto; }
			if($post) { $auto .= ' '.$sep; }
			$auto='<span class="autocomment">'.$auto.'</span>';
			$comment=$pre.$auto.$post;
		}

		return $comment;
	}

	/**
	 * Formats wiki links and media links in text; all other wiki formatting
	 * is ignored
	 *
	 * @param string $comment Text to format links in
	 * @return string
	 */
	public function formatLinksInComment( $comment ) {
		global $wgContLang;

		$medians = '(?:' . preg_quote( Namespace::getCanonicalName( NS_MEDIA ), '/' ) . '|';
		$medians .= preg_quote( $wgContLang->getNsText( NS_MEDIA ), '/' ) . '):';

		while(preg_match('/\[\[:?(.*?)(\|(.*?))*\]\](.*)$/',$comment,$match)) {
			# Handle link renaming [[foo|text]] will show link as "text"
			if( "" != $match[3] ) {
				$text = $match[3];
			} else {
				$text = $match[1];
			}
			$submatch = array();
			if( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
				# Media link; trail not supported.
				$linkRegexp = '/\[\[(.*?)\]\]/';
				$thelink = $this->makeMediaLink( $submatch[1], "", $text );
			} else {
				# Other kind of link
				if( preg_match( $wgContLang->linkTrail(), $match[4], $submatch ) ) {
					$trail = $submatch[1];
				} else {
					$trail = "";
				}
				$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
				if (isset($match[1][0]) && $match[1][0] == ':')
					$match[1] = substr($match[1], 1);
				$thelink = $this->makeLink( $match[1], $text, "", $trail );
			}
			$comment = preg_replace( $linkRegexp, StringUtils::escapeRegexReplacement( $thelink ), $comment, 1 );
		}

		return $comment;
	}

	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return empty string.
	 *
	 * @param string $comment
	 * @param mixed $title Title object (to generate link to section in autocomment) or null
	 * @param bool $local Whether section links should refer to local page
	 *
	 * @return string
	 */
	function commentBlock( $comment, $title = NULL, $local = false ) {
		// '*' used to be the comment inserted by the software way back
		// in antiquity in case none was provided, here for backwards
		// compatability, acc. to brion -ævar
		if( $comment == '' || $comment == '*' ) {
			return '';
		} else {
			$formatted = $this->formatComment( $comment, $title, $local );
			return " <span class=\"comment\">($formatted)</span>";
		}
	}

	/**
	 * Wrap and format the given revision's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @param Revision $rev
	 * @param bool $local Whether section links should refer to local page
	 * @return string HTML
	 */
	function revComment( Revision $rev, $local = false ) {
		if( $rev->userCan( Revision::DELETED_COMMENT ) ) {
			$block = $this->commentBlock( $rev->getRawComment(), $rev->getTitle(), $local );
		} else {
			$block = " <span class=\"comment\">" .
				wfMsgHtml( 'rev-deleted-comment' ) . "</span>";
		}
		if( $rev->isDeleted( Revision::DELETED_COMMENT ) ) {
			return " <span class=\"history-deleted\">$block</span>";
		}
		return $block;
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
		return "\n<li class=\"toclevel-$level\"><a href=\"#" .
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
		$title =  wfMsgHtml('toc') ;
		return
		   '<table id="toc" class="toc" summary="' . $title .'"><tr><td>'
		 . '<div id="toctitle"><h2>' . $title . "</h2></div>\n"
		 . $toc
		 # no trailing newline, script should not be wrapped in a
		 # paragraph
		 . "</ul>\n</td></tr></table>"
		 . '<script type="' . $wgJsMimeType . '">'
		 . ' if (window.showTocToggle) {'
		 . ' var tocShowText = "' . wfEscapeJsString( wfMsg('showtoc') ) . '";'
		 . ' var tocHideText = "' . wfEscapeJsString( wfMsg('hidetoc') ) . '";'
		 . ' showTocToggle();'
		 . ' } '
		 . "</script>\n";
	}

	/**
	 * Used to generate section edit links that point to "other" pages
	 * (sections that are really part of included pages).
	 *
	 * @param $title Title string.
	 * @param $section Integer: section number.
	 */
	public function editSectionLinkForOther( $title, $section ) {
		$title = Title::newFromText( $title );
		return $this->doEditSectionLink( $title, $section, '', 'EditSectionLinkForOther' );
	}

	/**
	 * @param $nt Title object.
	 * @param $section Integer: section number.
	 * @param $hint Link String: title, or default if omitted or empty
	 */
	public function editSectionLink( Title $nt, $section, $hint='' ) {
		if( $hint != '' ) {
			$hint = wfMsgHtml( 'editsectionhint', htmlspecialchars( $hint ) );
			$hint = " title=\"$hint\"";
		}
		return $this->doEditSectionLink( $nt, $section, $hint, 'EditSectionLink' );
	}

	/**
	 * Implement editSectionLink and editSectionLinkForOther.
	 *
	 * @param $nt      Title object
	 * @param $section Integer, section number
	 * @param $hint    String, for HTML title attribute
	 * @param $hook    String, name of hook to run
	 * @return         String, HTML to use for edit link
	 */
	protected function doEditSectionLink( Title $nt, $section, $hint, $hook ) {
		global $wgContLang;
		$editurl = '&section='.$section;
		$url = $this->makeKnownLinkObj(
			$nt,
			wfMsg('editsection'),
			'action=edit'.$editurl,
			'', '', '',  $hint
		);
		$result = null;

		// The two hooks have slightly different interfaces . . .
		if( $hook == 'EditSectionLink' ) {
			wfRunHooks( $hook, array( &$this, $nt, $section, $hint, $url, &$result ) );
		} elseif( $hook == 'EditSectionLinkForOther' ) {
			wfRunHooks( $hook, array( &$this, $nt, $section, $url, &$result ) );
		}
		
		// For reverse compatibility, add the brackets *after* the hook is run,
		// and even add them to hook-provided text.
		if( is_null( $result ) ) {
			$result = wfMsg( 'editsection-brackets', $url );
		} else {
			$result = wfMsg( 'editsection-brackets', $result );
		}
		return "<span class=\"editsection\">$result</span>";
	}

	/**
	 * Create a headline for content
	 *
	 * @param int    $level   The level of the headline (1-6)
	 * @param string $attribs Any attributes for the headline, starting with a space and ending with '>'
	 *                        This *must* be at least '>' for no attribs
	 * @param string $anchor  The anchor to give the headline (the bit after the #)
	 * @param string $text    The text of the header
	 * @param string $link    HTML to add for the section edit link
	 *
	 * @return string HTML headline
	 */
	public function makeHeadline( $level, $attribs, $anchor, $text, $link ) {
		return "<a name=\"$anchor\"></a><h$level$attribs$link <span class=\"mw-headline\">$text</span></h$level>";
	}

	/**
	 * Split a link trail, return the "inside" portion and the remainder of the trail
	 * as a two-element array
	 *
	 * @static
	 */
	static function splitTrail( $trail ) {
		static $regex = false;
		if ( $regex === false ) {
			global $wgContLang;
			$regex = $wgContLang->linkTrail();
		}
		$inside = '';
		if ( '' != $trail ) {
			$m = array();
			if ( preg_match( $regex, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		return array( $inside, $trail );
	}

	/**
	 * Generate a rollback link for a given revision.  Currently it's the
	 * caller's responsibility to ensure that the revision is the top one. If
	 * it's not, of course, the user will get an error message.
	 *
	 * If the calling page is called with the parameter &bot=1, all rollback
	 * links also get that parameter. It causes the edit itself and the rollback
	 * to be marked as "bot" edits. Bot edits are hidden by default from recent
	 * changes, so this allows sysops to combat a busy vandal without bothering
	 * other users.
	 *
	 * @param Revision $rev
	 */
	function generateRollback( $rev ) {
		return '<span class="mw-rollback-link">['
			. $this->buildRollbackLink( $rev )
			. ']</span>';
	}
	
	/**
	 * Build a raw rollback link, useful for collections of "tool" links
	 *
	 * @param Revision $rev
	 * @return string
	 */
	public function buildRollbackLink( $rev ) {
		global $wgRequest, $wgUser;
		$title = $rev->getTitle();
		$extra  = $wgRequest->getBool( 'bot' ) ? '&bot=1' : '';
		$extra .= '&token=' . urlencode( $wgUser->editToken( array( $title->getPrefixedText(),
			$rev->getUserText() ) ) );
		return $this->makeKnownLinkObj(
			$title,
			wfMsgHtml( 'rollbacklink' ),
			'action=rollback&from=' . urlencode( $rev->getUserText() ) . $extra
		);		
	}

	/**
	 * Returns HTML for the "templates used on this page" list.
	 *
	 * @param array $templates Array of templates from Article::getUsedTemplate
	 * or similar
	 * @param bool $preview Whether this is for a preview
	 * @param bool $section Whether this is for a section edit
	 * @return string HTML output
	 */
	public function formatTemplates( $templates, $preview = false, $section = false) {
		global $wgUser;
		wfProfileIn( __METHOD__ );

		$sk = $wgUser->getSkin();

		$outText = '';
		if ( count( $templates ) > 0 ) {
			# Do a batch existence check
			$batch = new LinkBatch;
			foreach( $templates as $title ) {
				$batch->addObj( $title );
			}
			$batch->execute();

			# Construct the HTML
			$outText = '<div class="mw-templatesUsedExplanation">';
			if ( $preview ) {
				$outText .= wfMsgExt( 'templatesusedpreview', array( 'parse' ) );
			} elseif ( $section ) {
				$outText .= wfMsgExt( 'templatesusedsection', array( 'parse' ) );
			} else {
				$outText .= wfMsgExt( 'templatesused', array( 'parse' ) );
			}
			$outText .= '</div><ul>';

			foreach ( $templates as $titleObj ) {
				$r = $titleObj->getRestrictions( 'edit' );
				if ( in_array( 'sysop', $r ) ) { 
					$protected = wfMsgExt( 'template-protected', array( 'parseinline' ) );
				} elseif ( in_array( 'autoconfirmed', $r ) ) {
					$protected = wfMsgExt( 'template-semiprotected', array( 'parseinline' ) );
				} else {
					$protected = '';
				}
				$outText .= '<li>' . $sk->makeLinkObj( $titleObj ) . ' ' . $protected . '</li>';
			}
			$outText .= '</ul>';
		}
		wfProfileOut( __METHOD__  );
		return $outText;
	}
	
	/**
	 * Format a size in bytes for output, using an appropriate
	 * unit (B, KB, MB or GB) according to the magnitude in question
	 *
	 * @param $size Size to format
	 * @return string
	 */
	public function formatSize( $size ) {
		global $wgLang;
		// For small sizes no decimal places necessary
		$round = 0;
		if( $size > 1024 ) {
			$size = $size / 1024;
			if( $size > 1024 ) {
				$size = $size / 1024;
				// For MB and bigger two decimal places are smarter
				$round = 2;
				if( $size > 1024 ) {
					$size = $size / 1024;
					$msg = 'size-gigabytes';
				} else {
					$msg = 'size-megabytes';
				}
			} else {
				$msg = 'size-kilobytes';
			}
		} else {
			$msg = 'size-bytes';
		}
		$size = round( $size, $round );
		return wfMsgHtml( $msg, $wgLang->formatNum( $size ) );
	}

	/**
	 * Given the id of an interface element, constructs the appropriate title
	 * and accesskey attributes from the system messages.  (Note, this is usu-
	 * ally the id but isn't always, because sometimes the accesskey needs to
	 * go on a different element than the id, for reverse-compatibility, etc.)
	 *
	 * @param string $name Id of the element, minus prefixes.
	 * @return string title and accesskey attributes, ready to drop in an
	 *   element (e.g., ' title="This does something [x]" accesskey="x"').
	 */
	public function tooltipAndAccesskey($name) {
		$out = '';

		$tooltip = wfMsg('tooltip-'.$name);
		if (!wfEmptyMsg('tooltip-'.$name, $tooltip) && $tooltip != '-') {
			// Compatibility: formerly some tooltips had [alt-.] hardcoded
			$tooltip = preg_replace( "/ ?\[alt-.\]$/", '', $tooltip );
			$out .= ' title="'.htmlspecialchars($tooltip);
		}
		$accesskey = wfMsg('accesskey-'.$name);
		if ($accesskey && $accesskey != '-' && !wfEmptyMsg('accesskey-'.$name, $accesskey)) {
			if ($out) $out .= " [$accesskey]\" accesskey=\"$accesskey\"";
			else $out .= " title=\"[$accesskey]\" accesskey=\"$accesskey\"";
		} elseif ($out) {
			$out .= '"';
		}
		return $out;
	}

	/**
	 * Given the id of an interface element, constructs the appropriate title
	 * attribute from the system messages.  (Note, this is usually the id but
	 * isn't always, because sometimes the accesskey needs to go on a different
	 * element than the id, for reverse-compatibility, etc.)
	 *
	 * @param string $name Id of the element, minus prefixes.
	 * @return string title attribute, ready to drop in an element
	 * (e.g., ' title="This does something"').
	 */
	public function tooltip($name) {
		$out = '';

		$tooltip = wfMsg('tooltip-'.$name);
		if (!wfEmptyMsg('tooltip-'.$name, $tooltip) && $tooltip != '-') {
			$out = ' title="'.htmlspecialchars($tooltip).'"';
		}

		return $out;
	}
}



