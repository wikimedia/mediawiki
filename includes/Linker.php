<?php
/**
 * Some internal bits split of from Skin.php. These functions are used
 * for primarily page content: links, embedded images, table of contents. Links
 * are also used in the skin.
 *
 * @ingroup Skins
 */
class Linker {

	/**
	 * Flags for userToolLinks()
	 */
	const TOOL_LINKS_NOBLOCK = 1;

	/**
	 * Get the appropriate HTML attributes to add to the "a" element of an ex-
	 * ternal link, as created by [wikisyntax].
	 *
	 * @param $class String: the contents of the class attribute; if an empty
	 *   string is passed, which is the default value, defaults to 'external'.
	 * @deprecated since 1.18 Just pass the external class directly to something using Html::expandAttributes
	 */
	static function getExternalLinkAttributes( $class = 'external' ) {
		wfDeprecated( __METHOD__ );
		return self::getLinkAttributesInternal( '', $class );
	}

	/**
	 * Get the appropriate HTML attributes to add to the "a" element of an in-
	 * terwiki link.
	 *
	 * @param $title String: the title text for the link, URL-encoded (???) but
	 *   not HTML-escaped
	 * @param $unused String: unused
	 * @param $class String: the contents of the class attribute; if an empty
	 *   string is passed, which is the default value, defaults to 'external'.
	 */
	static function getInterwikiLinkAttributes( $title, $unused = null, $class = 'external' ) {
		global $wgContLang;

		# @todo FIXME: We have a whole bunch of handling here that doesn't happen in
		# getExternalLinkAttributes, why?
		$title = urldecode( $title );
		$title = $wgContLang->checkTitleEncoding( $title );
		$title = preg_replace( '/[\\x00-\\x1f]/', ' ', $title );

		return self::getLinkAttributesInternal( $title, $class );
	}

	/**
	 * Get the appropriate HTML attributes to add to the "a" element of an in-
	 * ternal link.
	 *
	 * @param $title String: the title text for the link, URL-encoded (???) but
	 *   not HTML-escaped
	 * @param $unused String: unused
	 * @param $class String: the contents of the class attribute, default none
	 */
	static function getInternalLinkAttributes( $title, $unused = null, $class = '' ) {
		$title = urldecode( $title );
		$title = str_replace( '_', ' ', $title );
		return self::getLinkAttributesInternal( $title, $class );
	}

	/**
	 * Get the appropriate HTML attributes to add to the "a" element of an in-
	 * ternal link, given the Title object for the page we want to link to.
	 *
	 * @param $nt Title
	 * @param $unused String: unused
	 * @param $class String: the contents of the class attribute, default none
	 * @param $title Mixed: optional (unescaped) string to use in the title
	 *   attribute; if false, default to the name of the page we're linking to
	 */
	static function getInternalLinkAttributesObj( $nt, $unused = null, $class = '', $title = false ) {
		if ( $title === false ) {
			$title = $nt->getPrefixedText();
		}
		return self::getLinkAttributesInternal( $title, $class );
	}

	/**
	 * Common code for getLinkAttributesX functions
	 *
	 * @param $title string
	 * @param $class string
	 *
	 * @return string
	 */
	private static function getLinkAttributesInternal( $title, $class ) {
		$title = htmlspecialchars( $title );
		$class = htmlspecialchars( $class );
		$r = '';
		if ( $class != '' ) {
			$r .= " class=\"$class\"";
		}
		if ( $title != '' ) {
			$r .= " title=\"$title\"";
		}
		return $r;
	}

	/**
	 * Return the CSS colour of a known link
	 *
	 * @param $t Title object
	 * @param $threshold Integer: user defined threshold
	 * @return String: CSS class
	 */
	static function getLinkColour( $t, $threshold ) {
		$colour = '';
		if ( $t->isRedirect() ) {
			# Page is a redirect
			$colour = 'mw-redirect';
		} elseif ( $threshold > 0 &&
			   $t->exists() && $t->getLength() < $threshold &&
			   $t->isContentPage() ) {
			# Page is a stub
			$colour = 'stub';
		}
		return $colour;
	}

	/**
	 * This function returns an HTML link to the given target.  It serves a few
	 * purposes:
	 *   1) If $target is a Title, the correct URL to link to will be figured
	 *      out automatically.
	 *   2) It automatically adds the usual classes for various types of link
	 *      targets: "new" for red links, "stub" for short articles, etc.
	 *   3) It escapes all attribute values safely so there's no risk of XSS.
	 *   4) It provides a default tooltip if the target is a Title (the page
	 *      name of the target).
	 * link() replaces the old functions in the makeLink() family.
	 *
	 * @param $target        Title  Can currently only be a Title, but this may
	 *   change to support Images, literal URLs, etc.
	 * @param $text          string The HTML contents of the <a> element, i.e.,
	 *   the link text.  This is raw HTML and will not be escaped.  If null,
	 *   defaults to the prefixed text of the Title; or if the Title is just a
	 *   fragment, the contents of the fragment.
	 * @param $customAttribs array  A key => value array of extra HTML attri-
	 *   butes, such as title and class.  (href is ignored.)  Classes will be
	 *   merged with the default classes, while other attributes will replace
	 *   default attributes.  All passed attribute values will be HTML-escaped.
	 *   A false attribute value means to suppress that attribute.
	 * @param $query         array  The query string to append to the URL
	 *   you're linking to, in key => value array form.  Query keys and values
	 *   will be URL-encoded.
	 * @param $options string|array  String or array of strings:
	 *     'known': Page is known to exist, so don't check if it does.
	 *     'broken': Page is known not to exist, so don't check if it does.
	 *     'noclasses': Don't add any classes automatically (includes "new",
	 *       "stub", "mw-redirect", "extiw").  Only use the class attribute
	 *       provided, if any, so you get a simple blue link with no funny i-
	 *       cons.
	 *     'forcearticlepath': Use the article path always, even with a querystring.
	 *       Has compatibility issues on some setups, so avoid wherever possible.
	 * @return string HTML <a> attribute
	 */
	public static function link(
		$target, $html = null, $customAttribs = array(), $query = array(), $options = array()
	) {
		wfProfileIn( __METHOD__ );
		if ( !$target instanceof Title ) {
			wfProfileOut( __METHOD__ );
			return "<!-- ERROR -->$html";
		}
		$options = (array)$options;

		$dummy = new DummyLinker; // dummy linker instance for bc on the hooks

		$ret = null;
		if ( !wfRunHooks( 'LinkBegin', array( $dummy, $target, &$html,
		&$customAttribs, &$query, &$options, &$ret ) ) ) {
			wfProfileOut( __METHOD__ );
			return $ret;
		}

		# Normalize the Title if it's a special page
		$target = self::normaliseSpecialPage( $target );

		# If we don't know whether the page exists, let's find out.
		wfProfileIn( __METHOD__ . '-checkPageExistence' );
		if ( !in_array( 'known', $options ) and !in_array( 'broken', $options ) ) {
			if ( $target->isKnown() ) {
				$options[] = 'known';
			} else {
				$options[] = 'broken';
			}
		}
		wfProfileOut( __METHOD__ . '-checkPageExistence' );

		$oldquery = array();
		if ( in_array( "forcearticlepath", $options ) && $query ) {
			$oldquery = $query;
			$query = array();
		}

		# Note: we want the href attribute first, for prettiness.
		$attribs = array( 'href' => self::linkUrl( $target, $query, $options ) );
		if ( in_array( 'forcearticlepath', $options ) && $oldquery ) {
			$attribs['href'] = wfAppendQuery( $attribs['href'], wfArrayToCgi( $oldquery ) );
		}

		$attribs = array_merge(
			$attribs,
			self::linkAttribs( $target, $customAttribs, $options )
		);
		if ( is_null( $html ) ) {
			$html = self::linkText( $target );
		}

		$ret = null;
		if ( wfRunHooks( 'LinkEnd', array( $dummy, $target, $options, &$html, &$attribs, &$ret ) ) ) {
			$ret = Html::rawElement( 'a', $attribs, $html );
		}

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Identical to link(), except $options defaults to 'known'.
	 */
	public static function linkKnown(
		$target, $text = null, $customAttribs = array(),
		$query = array(), $options = array( 'known', 'noclasses' ) )
	{
		return self::link( $target, $text, $customAttribs, $query, $options );
	}

	/**
	 * Returns the Url used to link to a Title
	 *
	 * @param $target Title
	 */
	private static function linkUrl( $target, $query, $options ) {
		wfProfileIn( __METHOD__ );
		# We don't want to include fragments for broken links, because they
		# generally make no sense.
		if ( in_array( 'broken', $options ) && $target->mFragment !== '' ) {
			$target = clone $target;
			$target->mFragment = '';
		}

		# If it's a broken link, add the appropriate query pieces, unless
		# there's already an action specified, or unless 'edit' makes no sense
		# (i.e., for a nonexistent special page).
		if ( in_array( 'broken', $options ) && empty( $query['action'] )
			&& $target->getNamespace() != NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}
		$ret = $target->getLinkUrl( $query );
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Returns the array of attributes used when linking to the Title $target
	 *
	 * @param $target Title
	 * @param $attribs
	 * @param $options
	 *
	 * @return array
	 */
	private static function linkAttribs( $target, $attribs, $options ) {
		wfProfileIn( __METHOD__ );
		global $wgUser;
		$defaults = array();

		if ( !in_array( 'noclasses', $options ) ) {
			wfProfileIn( __METHOD__ . '-getClasses' );
			# Now build the classes.
			$classes = array();

			if ( in_array( 'broken', $options ) ) {
				$classes[] = 'new';
			}

			if ( $target->isExternal() ) {
				$classes[] = 'extiw';
			}

			if ( !in_array( 'broken', $options ) ) { # Avoid useless calls to LinkCache (see r50387)
				$colour = self::getLinkColour( $target, $wgUser->getStubThreshold() );
				if ( $colour !== '' ) {
					$classes[] = $colour; # mw-redirect or stub
				}
			}
			if ( $classes != array() ) {
				$defaults['class'] = implode( ' ', $classes );
			}
			wfProfileOut( __METHOD__ . '-getClasses' );
		}

		# Get a default title attribute.
		if ( $target->getPrefixedText() == '' ) {
			# A link like [[#Foo]].  This used to mean an empty title
			# attribute, but that's silly.  Just don't output a title.
		} elseif ( in_array( 'known', $options ) ) {
			$defaults['title'] = $target->getPrefixedText();
		} else {
			$defaults['title'] = wfMsg( 'red-link-title', $target->getPrefixedText() );
		}

		# Finally, merge the custom attribs with the default ones, and iterate
		# over that, deleting all "false" attributes.
		$ret = array();
		$merged = Sanitizer::mergeAttributes( $defaults, $attribs );
		foreach ( $merged as $key => $val ) {
			# A false value suppresses the attribute, and we don't want the
			# href attribute to be overridden.
			if ( $key != 'href' and $val !== false ) {
				$ret[$key] = $val;
			}
		}
		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Default text of the links to the Title $target
	 *
	 * @param $target Title
	 *
	 * @return string
	 */
	private static function linkText( $target ) {
		# We might be passed a non-Title by make*LinkObj().  Fail gracefully.
		if ( !$target instanceof Title ) {
			return '';
		}

		# If the target is just a fragment, with no title, we return the frag-
		# ment text.  Otherwise, we return the title text itself.
		if ( $target->getPrefixedText() === '' && $target->getFragment() !== '' ) {
			return htmlspecialchars( $target->getFragment() );
		}
		return htmlspecialchars( $target->getPrefixedText() );
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
	 * @deprecated since 1.17
	 */
	static function makeSizeLinkObj( $size, $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		global $wgUser;
		wfDeprecated( __METHOD__ );

		$threshold = $wgUser->getStubThreshold();
		$colour = ( $size < $threshold ) ? 'stub' : '';
		// @todo FIXME: Replace deprecated makeColouredLinkObj by link()
		return self::makeColouredLinkObj( $nt, $colour, $text, $query, $trail, $prefix );
	}

	/**
	 * Make appropriate markup for a link to the current article. This is currently rendered
	 * as the bold link text. The calling sequence is the same as the other make*LinkObj static functions,
	 * despite $query not being used.
	 *
	 * @param $nt Title
	 *
	 * @return string
	 */
	static function makeSelfLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		if ( $text == '' ) {
			$text = htmlspecialchars( $nt->getPrefixedText() );
		}
		list( $inside, $trail ) = self::splitTrail( $trail );
		return "<strong class=\"selflink\">{$prefix}{$text}{$inside}</strong>{$trail}";
	}

	/**
	 * @param $title Title
	 * @return Title
	 */
	static function normaliseSpecialPage( Title $title ) {
		if ( $title->getNamespace() == NS_SPECIAL ) {
			list( $name, $subpage ) = SpecialPageFactory::resolveAlias( $title->getDBkey() );
			if ( !$name ) {
				return $title;
			}
			$ret = SpecialPage::getTitleFor( $name, $subpage );
			$ret->mFragment = $title->getFragment();
			return $ret;
		} else {
			return $title;
		}
	}

	/**
	 * Returns the filename part of an url.
	 * Used as alternative text for external images.
	 *
	 * @param $url string
	 *
	 * @return string
	 */
	static function fnamePart( $url ) {
		$basename = strrchr( $url, '/' );
		if ( false === $basename ) {
			$basename = $url;
		} else {
			$basename = substr( $basename, 1 );
		}
		return $basename;
	}

	/**
	 * Return the code for images which were added via external links,
	 * via Parser::maybeMakeExternalImage().
	 *
	 * @param $url
	 * @param $alt
	 *
	 * @return string
	 */
	static function makeExternalImage( $url, $alt = '' ) {
		if ( $alt == '' ) {
			$alt = self::fnamePart( $url );
		}
		$img = '';
		$success = wfRunHooks( 'LinkerMakeExternalImage', array( &$url, &$alt, &$img ) );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalImage changed the output of external image with url {$url} and alt text {$alt} to {$img}\n", true );
			return $img;
		}
		return Html::element( 'img',
			array(
				'src' => $url,
				'alt' => $alt ) );
	}

	/**
	 * Given parameters derived from [[Image:Foo|options...]], generate the
	 * HTML that that syntax inserts in the page.
	 *
	 * @param $title Title object
	 * @param $file File object, or false if it doesn't exist
	 * @param $frameParams Array: associative array of parameters external to the media handler.
	 *     Boolean parameters are indicated by presence or absence, the value is arbitrary and
	 *     will often be false.
	 *          thumbnail       If present, downscale and frame
	 *          manualthumb     Image name to use as a thumbnail, instead of automatic scaling
	 *          framed          Shows image in original size in a frame
	 *          frameless       Downscale but don't frame
	 *          upright         If present, tweak default sizes for portrait orientation
	 *          upright_factor  Fudge factor for "upright" tweak (default 0.75)
	 *          border          If present, show a border around the image
	 *          align           Horizontal alignment (left, right, center, none)
	 *          valign          Vertical alignment (baseline, sub, super, top, text-top, middle,
	 *                          bottom, text-bottom)
	 *          alt             Alternate text for image (i.e. alt attribute). Plain text.
	 *          caption         HTML for image caption.
	 *          link-url        URL to link to
	 *          link-title      Title object to link to
	 *          link-target     Value for the target attribue, only with link-url
	 *          no-link         Boolean, suppress description link
	 *
	 * @param $handlerParams Array: associative array of media handler parameters, to be passed
	 *       to transform(). Typical keys are "width" and "page".
	 * @param $time String: timestamp of the file, set as false for current
	 * @param $query String: query params for desc url
	 * @param $widthOption: Used by the parser to remember the user preference thumbnailsize
	 * @return String: HTML for an image, with links, wrappers, etc.
	 */
	static function makeImageLink2( Title $title, $file, $frameParams = array(),
		$handlerParams = array(), $time = false, $query = "", $widthOption = null )
	{
		$res = null;
		$dummy = new DummyLinker;
		if ( !wfRunHooks( 'ImageBeforeProduceHTML', array( &$dummy, &$title,
			&$file, &$frameParams, &$handlerParams, &$time, &$res ) ) ) {
			return $res;
		}

		if ( $file && !$file->allowInlineDisplay() ) {
			wfDebug( __METHOD__ . ': ' . $title->getPrefixedDBkey() . " does not allow inline display\n" );
			return self::link( $title );
		}

		// Shortcuts
		$fp =& $frameParams;
		$hp =& $handlerParams;

		// Clean up parameters
		$page = isset( $hp['page'] ) ? $hp['page'] : false;
		if ( !isset( $fp['align'] ) ) {
			$fp['align'] = '';
		}
		if ( !isset( $fp['alt'] ) ) {
			$fp['alt'] = '';
		}
		if ( !isset( $fp['title'] ) ) {
			$fp['title'] = '';
		}

		$prefix = $postfix = '';

		if ( 'center' == $fp['align'] ) {
			$prefix  = '<div class="center">';
			$postfix = '</div>';
			$fp['align']   = 'none';
		}
		if ( $file && !isset( $hp['width'] ) ) {
			if ( isset( $hp['height'] ) && $file->isVectorized() ) {
				// If its a vector image, and user only specifies height
				// we don't want it to be limited by its "normal" width.
				global $wgSVGMaxSize;
				$hp['width'] = $wgSVGMaxSize;
			} else {
				$hp['width'] = $file->getWidth( $page );
			}

			if ( isset( $fp['thumbnail'] ) || isset( $fp['framed'] ) || isset( $fp['frameless'] ) || !$hp['width'] ) {
				global $wgThumbLimits, $wgThumbUpright;
				if ( !isset( $widthOption ) || !isset( $wgThumbLimits[$widthOption] ) ) {
					$widthOption = User::getDefaultOption( 'thumbsize' );
				}

				// Reduce width for upright images when parameter 'upright' is used
				if ( isset( $fp['upright'] ) && $fp['upright'] == 0 ) {
					$fp['upright'] = $wgThumbUpright;
				}
				// For caching health: If width scaled down due to upright parameter, round to full __0 pixel to avoid the creation of a lot of odd thumbs
				$prefWidth = isset( $fp['upright'] ) ?
					round( $wgThumbLimits[$widthOption] * $fp['upright'], -1 ) :
					$wgThumbLimits[$widthOption];

				// Use width which is smaller: real image width or user preference width
				// Unless image is scalable vector.
				if ( !isset( $hp['height'] ) && ( $hp['width'] <= 0 ||
						$prefWidth < $hp['width'] || $file->isVectorized() ) ) {
					$hp['width'] = $prefWidth;
				}
			}
		}

		if ( isset( $fp['thumbnail'] ) || isset( $fp['manualthumb'] ) || isset( $fp['framed'] ) ) {
			global $wgContLang;
			# Create a thumbnail. Alignment depends on language
			# writing direction, # right aligned for left-to-right-
			# languages ("Western languages"), left-aligned
			# for right-to-left-languages ("Semitic languages")
			#
			# If  thumbnail width has not been provided, it is set
			# to the default user option as specified in Language*.php
			if ( $fp['align'] == '' ) {
				$fp['align'] = $wgContLang->alignEnd();
			}
			return $prefix . self::makeThumbLink2( $title, $file, $fp, $hp, $time, $query ) . $postfix;
		}

		if ( $file && isset( $fp['frameless'] ) ) {
			$srcWidth = $file->getWidth( $page );
			# For "frameless" option: do not present an image bigger than the source (for bitmap-style images)
			# This is the same behaviour as the "thumb" option does it already.
			if ( $srcWidth && !$file->mustRender() && $hp['width'] > $srcWidth ) {
				$hp['width'] = $srcWidth;
			}
		}

		if ( $file && isset( $hp['width'] ) ) {
			# Create a resized image, without the additional thumbnail features
			$thumb = $file->transform( $hp );
		} else {
			$thumb = false;
		}

		if ( !$thumb ) {
			$s = self::makeBrokenImageLinkObj( $title, $fp['title'], '', '', '', $time == true );
		} else {
			$params = array(
				'alt' => $fp['alt'],
				'title' => $fp['title'],
				'valign' => isset( $fp['valign'] ) ? $fp['valign'] : false ,
				'img-class' => isset( $fp['border'] ) ? 'thumbborder' : false );
			$params = self::getImageLinkMTOParams( $fp, $query ) + $params;

			$s = $thumb->toHtml( $params );
		}
		if ( $fp['align'] != '' ) {
			$s = "<div class=\"float{$fp['align']}\">{$s}</div>";
		}
		return str_replace( "\n", ' ', $prefix . $s . $postfix );
	}

	/**
	 * Get the link parameters for MediaTransformOutput::toHtml() from given
	 * frame parameters supplied by the Parser.
	 * @param $frameParams The frame parameters
	 * @param $query An optional query string to add to description page links
	 */
	static function getImageLinkMTOParams( $frameParams, $query = '' ) {
		$mtoParams = array();
		if ( isset( $frameParams['link-url'] ) && $frameParams['link-url'] !== '' ) {
			$mtoParams['custom-url-link'] = $frameParams['link-url'];
			if ( isset( $frameParams['link-target'] ) ) {
				$mtoParams['custom-target-link'] = $frameParams['link-target'];
			}
		} elseif ( isset( $frameParams['link-title'] ) && $frameParams['link-title'] !== '' ) {
			$mtoParams['custom-title-link'] = self::normaliseSpecialPage( $frameParams['link-title'] );
		} elseif ( !empty( $frameParams['no-link'] ) ) {
			// No link
		} else {
			$mtoParams['desc-link'] = true;
			$mtoParams['desc-query'] = $query;
		}
		return $mtoParams;
	}

	/**
	 * Make HTML for a thumbnail including image, border and caption
	 * @param $title Title object
	 * @param $file File object or false if it doesn't exist
	 * @param $label String
	 * @param $alt String
	 * @param $align String
	 * @param $params Array
	 * @param $framed Boolean
	 * @param $manualthumb String
	 */
	static function makeThumbLinkObj( Title $title, $file, $label = '', $alt,
		$align = 'right', $params = array(), $framed = false , $manualthumb = "" )
	{
		$frameParams = array(
			'alt' => $alt,
			'caption' => $label,
			'align' => $align
		);
		if ( $framed ) {
			$frameParams['framed'] = true;
		}
		if ( $manualthumb ) {
			$frameParams['manualthumb'] = $manualthumb;
		}
		return self::makeThumbLink2( $title, $file, $frameParams, $params );
	}

	/**
	 * @param $title Title
	 * @param  $file File
	 * @param array $frameParams
	 * @param array $handlerParams
	 * @param bool $time
	 * @param string $query
	 * @return mixed
	 */
	static function makeThumbLink2( Title $title, $file, $frameParams = array(),
		$handlerParams = array(), $time = false, $query = "" )
	{
		global $wgStylePath, $wgContLang;
		$exists = $file && $file->exists();

		# Shortcuts
		$fp =& $frameParams;
		$hp =& $handlerParams;

		$page = isset( $hp['page'] ) ? $hp['page'] : false;
		if ( !isset( $fp['align'] ) ) $fp['align'] = 'right';
		if ( !isset( $fp['alt'] ) ) $fp['alt'] = '';
		if ( !isset( $fp['title'] ) ) $fp['title'] = '';
		if ( !isset( $fp['caption'] ) ) $fp['caption'] = '';

		if ( empty( $hp['width'] ) ) {
			// Reduce width for upright images when parameter 'upright' is used
			$hp['width'] = isset( $fp['upright'] ) ? 130 : 180;
		}
		$thumb = false;

		if ( !$exists ) {
			$outerWidth = $hp['width'] + 2;
		} else {
			if ( isset( $fp['manualthumb'] ) ) {
				# Use manually specified thumbnail
				$manual_title = Title::makeTitleSafe( NS_FILE, $fp['manualthumb'] );
				if ( $manual_title ) {
					$manual_img = wfFindFile( $manual_title );
					if ( $manual_img ) {
						$thumb = $manual_img->getUnscaledThumb( $hp );
					} else {
						$exists = false;
					}
				}
			} elseif ( isset( $fp['framed'] ) ) {
				// Use image dimensions, don't scale
				$thumb = $file->getUnscaledThumb( $hp );
			} else {
				# Do not present an image bigger than the source, for bitmap-style images
				# This is a hack to maintain compatibility with arbitrary pre-1.10 behaviour
				$srcWidth = $file->getWidth( $page );
				if ( $srcWidth && !$file->mustRender() && $hp['width'] > $srcWidth ) {
					$hp['width'] = $srcWidth;
				}
				$thumb = $file->transform( $hp );
			}

			if ( $thumb ) {
				$outerWidth = $thumb->getWidth() + 2;
			} else {
				$outerWidth = $hp['width'] + 2;
			}
		}

		# ThumbnailImage::toHtml() already adds page= onto the end of DjVu URLs
		# So we don't need to pass it here in $query. However, the URL for the
		# zoom icon still needs it, so we make a unique query for it. See bug 14771
		$url = $title->getLocalURL( $query );
		if ( $page ) {
			$url = wfAppendQuery( $url, 'page=' . urlencode( $page ) );
		}

		$s = "<div class=\"thumb t{$fp['align']}\"><div class=\"thumbinner\" style=\"width:{$outerWidth}px;\">";
		if ( !$exists ) {
			$s .= self::makeBrokenImageLinkObj( $title, $fp['title'], '', '', '', $time == true );
			$zoomIcon = '';
		} elseif ( !$thumb ) {
			$s .= htmlspecialchars( wfMsg( 'thumbnail_error', '' ) );
			$zoomIcon = '';
		} else {
			$params = array(
				'alt' => $fp['alt'],
				'title' => $fp['title'],
				'img-class' => 'thumbimage' );
			$params = self::getImageLinkMTOParams( $fp, $query ) + $params;
			$s .= $thumb->toHtml( $params );
			if ( isset( $fp['framed'] ) ) {
				$zoomIcon = "";
			} else {
				$zoomIcon = Html::rawElement( 'div', array( 'class' => 'magnify' ),
					Html::rawElement( 'a', array(
						'href' => $url,
						'class' => 'internal',
						'title' => wfMsg( 'thumbnail-more' ) ),
						Html::element( 'img', array(
							'src' => $wgStylePath . '/common/images/magnify-clip' . ( $wgContLang->isRTL() ? '-rtl' : '' ) . '.png',
							'width' => 15,
							'height' => 11,
							'alt' => "" ) ) ) );
			}
		}
		$s .= '  <div class="thumbcaption">' . $zoomIcon . $fp['caption'] . "</div></div></div>";
		return str_replace( "\n", ' ', $s );
	}

	/**
	 * Make a "broken" link to an image
	 *
	 * @param $title Title object
	 * @param $text String: link label in unescaped text form
	 * @param $query String: query string
	 * @param $trail String: link trail (HTML fragment)
	 * @param $prefix String: link prefix (HTML fragment)
	 * @param $time Boolean: a file of a certain timestamp was requested
	 * @return String
	 */
	public static function makeBrokenImageLinkObj( $title, $text = '', $query = '', $trail = '', $prefix = '', $time = false ) {
		global $wgEnableUploads, $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		if ( ! $title instanceof Title ) {
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}
		wfProfileIn( __METHOD__ );
		$currentExists = $time ? ( wfFindFile( $title ) != false ) : false;

		list( $inside, $trail ) = self::splitTrail( $trail );
		if ( $text == '' )
			$text = htmlspecialchars( $title->getPrefixedText() );

		if ( ( $wgUploadMissingFileUrl || $wgUploadNavigationUrl || $wgEnableUploads ) && !$currentExists ) {
			$redir = RepoGroup::singleton()->getLocalRepo()->checkRedirect( $title );

			if ( $redir ) {
				wfProfileOut( __METHOD__ );
				return self::linkKnown( $title, "$prefix$text$inside", array(), $query ) . $trail;
			}

			$href = self::getUploadUrl( $title, $query );

			wfProfileOut( __METHOD__ );
			return '<a href="' . htmlspecialchars( $href ) . '" class="new" title="' .
				htmlspecialchars( $title->getPrefixedText(), ENT_QUOTES ) . '">' .
				"$prefix$text$inside</a>$trail";
		} else {
			wfProfileOut( __METHOD__ );
			return self::linkKnown( $title, "$prefix$text$inside", array(), $query ) . $trail;
		}
	}

	/**
	 * Get the URL to upload a certain file
	 *
	 * @param $destFile Title object of the file to upload
	 * @param $query String: urlencoded query string to prepend
	 * @return String: urlencoded URL
	 */
	protected static function getUploadUrl( $destFile, $query = '' ) {
		global $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		$q = 'wpDestFile=' . $destFile->getPartialUrl();
		if ( $query != '' )
			$q .= '&' . $query;

		if ( $wgUploadMissingFileUrl ) {
			return wfAppendQuery( $wgUploadMissingFileUrl, $q );
		} elseif( $wgUploadNavigationUrl ) {
			return wfAppendQuery( $wgUploadNavigationUrl, $q );
		} else {
			$upload = SpecialPage::getTitleFor( 'Upload' );
			return $upload->getLocalUrl( $q );
		}
	}

	/**
	 * Create a direct link to a given uploaded file.
	 *
	 * @param $title Title object.
	 * @param $text String: pre-sanitized HTML
	 * @param $time string: MW timestamp of file creation time
	 * @return String: HTML
	 */
	public static function makeMediaLinkObj( $title, $text = '', $time = false ) {
		$img = wfFindFile( $title, array( 'time' => $time ) );
		return self::makeMediaLinkFile( $title, $img, $text );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 * This will make a broken link if $file is false.
	 *
	 * @param $title Title object.
	 * @param $file File|false mixed File object or false
	 * @param $text String: pre-sanitized HTML
	 * @return String: HTML
	 *
	 * @todo Handle invalid or missing images better.
	 */
	public static function makeMediaLinkFile( Title $title, $file, $text = '' ) {
		if ( $file && $file->exists() ) {
			$url = $file->getURL();
			$class = 'internal';
		} else {
			$url = self::getUploadUrl( $title );
			$class = 'new';
		}
		$alt = htmlspecialchars( $title->getText(), ENT_QUOTES );
		if ( $text == '' ) {
			$text = $alt;
		}
		$u = htmlspecialchars( $url );
		return "<a href=\"{$u}\" class=\"$class\" title=\"{$alt}\">{$text}</a>";
	}

	/**
	 * Make a link to a special page given its name and, optionally,
	 * a message key from the link text.
	 * Usage example: $skin->specialLink( 'recentchanges' )
	 *
	 * @return bool
	 */
	static function specialLink( $name, $key = '' ) {
		if ( $key == '' ) {
			$key = strtolower( $name );
		}

		return self::linkKnown( SpecialPage::getTitleFor( $name ) , wfMsg( $key ) );
	}

	/**
	 * Make an external link
	 * @param $url String: URL to link to
	 * @param $text String: text of link
	 * @param $escape Boolean: do we escape the link text?
	 * @param $linktype String: type of external link. Gets added to the classes
	 * @param $attribs Array of extra attributes to <a>
	 */
	static function makeExternalLink( $url, $text, $escape = true, $linktype = '', $attribs = array() ) {
		$class = "external";
		if ( isset($linktype) && $linktype ) {
			$class .= " $linktype";
		}
		if ( isset($attribs['class']) && $attribs['class'] ) {
			$class .= " {$attribs['class']}";
		}
		$attribs['class'] = $class;

		if ( $escape ) {
			$text = htmlspecialchars( $text );
		}
		$link = '';
		$success = wfRunHooks( 'LinkerMakeExternalLink',
			array( &$url, &$text, &$link, &$attribs, $linktype ) );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalLink changed the output of link with url {$url} and text {$text} to {$link}\n", true );
			return $link;
		}
		$attribs['href'] = $url;
		return Html::rawElement( 'a', $attribs, $text );
	}

	/**
	 * Make user link (or user contributions for unregistered users)
	 * @param $userId   Integer: user id in database.
	 * @param $userText String: user name in database
	 * @return String: HTML fragment
	 * @private
	 */
	static function userLink( $userId, $userText ) {
		if ( $userId == 0 ) {
			$page = SpecialPage::getTitleFor( 'Contributions', $userText );
		} else {
			$page = Title::makeTitle( NS_USER, $userText );
		}
		return self::link( $page, htmlspecialchars( $userText ), array( 'class' => 'mw-userlink' ) );
	}

	/**
	 * Generate standard user tool links (talk, contributions, block link, etc.)
	 *
	 * @param $userId Integer: user identifier
	 * @param $userText String: user name or IP address
	 * @param $redContribsWhenNoEdits Boolean: should the contributions link be
	 *        red if the user has no edits?
	 * @param $flags Integer: customisation flags (e.g. Linker::TOOL_LINKS_NOBLOCK)
	 * @param $edits Integer: user edit count (optional, for performance)
	 * @return String: HTML fragment
	 */
	public static function userToolLinks(
		$userId, $userText, $redContribsWhenNoEdits = false, $flags = 0, $edits = null
	) {
		global $wgUser, $wgDisableAnonTalk, $wgLang;
		$talkable = !( $wgDisableAnonTalk && 0 == $userId );
		$blockable = !$flags & self::TOOL_LINKS_NOBLOCK;

		$items = array();
		if ( $talkable ) {
			$items[] = self::userTalkLink( $userId, $userText );
		}
		if ( $userId ) {
			// check if the user has an edit
			$attribs = array();
			if ( $redContribsWhenNoEdits ) {
				$count = !is_null( $edits ) ? $edits : User::edits( $userId );
				if ( $count == 0 ) {
					$attribs['class'] = 'new';
				}
			}
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $userText );

			$items[] = self::link( $contribsPage, wfMsgHtml( 'contribslink' ), $attribs );
		}
		if ( $blockable && $wgUser->isAllowed( 'block' ) ) {
			$items[] = self::blockLink( $userId, $userText );
		}

		if ( $items ) {
			return ' <span class="mw-usertoollinks">(' . $wgLang->pipeList( $items ) . ')</span>';
		} else {
			return '';
		}
	}

	/**
	 * Alias for userToolLinks( $userId, $userText, true );
	 * @param $userId Integer: user identifier
	 * @param $userText String: user name or IP address
	 * @param $edits Integer: user edit count (optional, for performance)
	 */
	public static function userToolLinksRedContribs( $userId, $userText, $edits = null ) {
		return self::userToolLinks( $userId, $userText, true, 0, $edits );
	}


	/**
	 * @param $userId Integer: user id in database.
	 * @param $userText String: user name in database.
	 * @return String: HTML fragment with user talk link
	 * @private
	 */
	static function userTalkLink( $userId, $userText ) {
		$userTalkPage = Title::makeTitle( NS_USER_TALK, $userText );
		$userTalkLink = self::link( $userTalkPage, wfMsgHtml( 'talkpagelinktext' ) );
		return $userTalkLink;
	}

	/**
	 * @param $userId Integer: userid
	 * @param $userText String: user name in database.
	 * @return String: HTML fragment with block link
	 * @private
	 */
	static function blockLink( $userId, $userText ) {
		$blockPage = SpecialPage::getTitleFor( 'Block', $userText );
		$blockLink = self::link( $blockPage, wfMsgHtml( 'blocklink' ) );
		return $blockLink;
	}

	/**
	 * Generate a user link if the current user is allowed to view it
	 * @param $rev Revision object.
	 * @param $isPublic Boolean: show only if all users can see it
	 * @return String: HTML fragment
	 */
	static function revUserLink( $rev, $isPublic = false ) {
		if ( $rev->isDeleted( Revision::DELETED_USER ) && $isPublic ) {
			$link = wfMsgHtml( 'rev-deleted-user' );
		} elseif ( $rev->userCan( Revision::DELETED_USER ) ) {
			$link = self::userLink( $rev->getUser( Revision::FOR_THIS_USER ),
				$rev->getUserText( Revision::FOR_THIS_USER ) );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @param $rev Revision object.
	 * @param $isPublic Boolean: show only if all users can see it
	 * @return string HTML
	 */
	static function revUserTools( $rev, $isPublic = false ) {
		if ( $rev->isDeleted( Revision::DELETED_USER ) && $isPublic ) {
			$link = wfMsgHtml( 'rev-deleted-user' );
		} elseif ( $rev->userCan( Revision::DELETED_USER ) ) {
			$userId = $rev->getUser( Revision::FOR_THIS_USER );
			$userText = $rev->getUserText( Revision::FOR_THIS_USER );
			$link = self::userLink( $userId, $userText ) .
				' ' . self::userToolLinks( $userId, $userText );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return ' <span class="history-deleted">' . $link . '</span>';
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
	 * @param $comment String
	 * @param $title Mixed: Title object (to generate link to the section in autocomment) or null
	 * @param $local Boolean: whether section links should refer to local page
	 */
	static function formatComment( $comment, $title = null, $local = false ) {
		wfProfileIn( __METHOD__ );

		# Sanitize text a bit:
		$comment = str_replace( "\n", " ", $comment );
		# Allow HTML entities (for bug 13815)
		$comment = Sanitizer::escapeHtmlAllowEntities( $comment );

		# Render autocomments and make links:
		$comment = self::formatAutocomments( $comment, $title, $local );
		$comment = self::formatLinksInComment( $comment, $title, $local );

		wfProfileOut( __METHOD__ );
		return $comment;
	}

	/**
	 * @var Title
	 */
	static $autocommentTitle;
	static $autocommentLocal;

	/**
	 * The pattern for autogen comments is / * foo * /, which makes for
	 * some nasty regex.
	 * We look for all comments, match any text before and after the comment,
	 * add a separator where needed and format the comment itself with CSS
	 * Called by Linker::formatComment.
	 *
	 * @param $comment String: comment text
	 * @param $title An optional title object used to links to sections
	 * @param $local Boolean: whether section links should refer to local page
	 * @return String: formatted comment
	 */
	private static function formatAutocomments( $comment, $title = null, $local = false ) {
		// Bah!
		self::$autocommentTitle = $title;
		self::$autocommentLocal = $local;
		$comment = preg_replace_callback(
			'!(.*)/\*\s*(.*?)\s*\*/(.*)!',
			array( 'Linker', 'formatAutocommentsCallback' ),
			$comment );
		self::$autocommentTitle = null;
		self::$autocommentLocal = null;
		return $comment;
	}

	/**
	 * @param $match
	 * @return string
	 */
	private static function formatAutocommentsCallback( $match ) {
		$title = self::$autocommentTitle;
		$local = self::$autocommentLocal;

		$pre = $match[1];
		$auto = $match[2];
		$post = $match[3];
		$link = '';
		if ( $title ) {
			$section = $auto;

			# Remove links that a user may have manually put in the autosummary
			# This could be improved by copying as much of Parser::stripSectionName as desired.
			$section = str_replace( '[[:', '', $section );
			$section = str_replace( '[[', '', $section );
			$section = str_replace( ']]', '', $section );

			$section = Sanitizer::normalizeSectionNameWhitespace( $section ); # bug 22784
			if ( $local ) {
				$sectionTitle = Title::newFromText( '#' . $section );
			} else {
				$sectionTitle = Title::makeTitleSafe( $title->getNamespace(),
					$title->getDBkey(), $section );
			}
			if ( $sectionTitle ) {
				$link = self::link( $sectionTitle,
					htmlspecialchars( wfMsgForContent( 'sectionlink' ) ), array(), array(),
					'noclasses' );
			} else {
				$link = '';
			}
		}
		$auto = "$link$auto";
		if ( $pre ) {
			# written summary $presep autocomment (summary /* section */)
			$auto = wfMsgExt( 'autocomment-prefix', array( 'escapenoentities', 'content' ) ) . $auto;
		}
		if ( $post ) {
			# autocomment $postsep written summary (/* section */ summary)
			$auto .= wfMsgExt( 'colon-separator', array( 'escapenoentities', 'content' ) );
		}
		$auto = '<span class="autocomment">' . $auto . '</span>';
		$comment = $pre . $auto . $post;
		return $comment;
	}

	/**
	 * @var Title
	 */
	static $commentContextTitle;
	static $commentLocal;

	/**
	 * Formats wiki links and media links in text; all other wiki formatting
	 * is ignored
	 *
	 * @todo FIXME: Doesn't handle sub-links as in image thumb texts like the main parser
	 * @param $comment String: text to format links in
	 * @param $title An optional title object used to links to sections
	 * @param $local Boolean: whether section links should refer to local page
	 * @return String
	 */
	public static function formatLinksInComment( $comment, $title = null, $local = false ) {
		self::$commentContextTitle = $title;
		self::$commentLocal = $local;
		$html = preg_replace_callback(
			'/\[\[:?(.*?)(\|(.*?))*\]\]([^[]*)/',
			array( 'Linker', 'formatLinksInCommentCallback' ),
			$comment );
		self::$commentContextTitle = null;
		self::$commentLocal = null;
		return $html;
	}

	/**
	 * @param $match
	 * @return mixed
	 */
	protected static function formatLinksInCommentCallback( $match ) {
		global $wgContLang;

		$medians = '(?:' . preg_quote( MWNamespace::getCanonicalName( NS_MEDIA ), '/' ) . '|';
		$medians .= preg_quote( $wgContLang->getNsText( NS_MEDIA ), '/' ) . '):';

		$comment = $match[0];

		# fix up urlencoded title texts (copied from Parser::replaceInternalLinks)
		if ( strpos( $match[1], '%' ) !== false ) {
			$match[1] = str_replace( array( '<', '>' ), array( '&lt;', '&gt;' ), rawurldecode( $match[1] ) );
		}

		# Handle link renaming [[foo|text]] will show link as "text"
		if ( $match[3] != "" ) {
			$text = $match[3];
		} else {
			$text = $match[1];
		}
		$submatch = array();
		$thelink = null;
		if ( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
			# Media link; trail not supported.
			$linkRegexp = '/\[\[(.*?)\]\]/';
			$title = Title::makeTitleSafe( NS_FILE, $submatch[1] );
			$thelink = self::makeMediaLinkObj( $title, $text );
		} else {
			# Other kind of link
			if ( preg_match( $wgContLang->linkTrail(), $match[4], $submatch ) ) {
				$trail = $submatch[1];
			} else {
				$trail = "";
			}
			$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
			if ( isset( $match[1][0] ) && $match[1][0] == ':' )
				$match[1] = substr( $match[1], 1 );
			list( $inside, $trail ) = self::splitTrail( $trail );

			$linkText = $text;
			$linkTarget = self::normalizeSubpageLink( self::$commentContextTitle,
				$match[1], $linkText );

			$target = Title::newFromText( $linkTarget );
			if ( $target ) {
				if ( $target->getText() == '' && $target->getInterwiki() === ''
					&& !self::$commentLocal && self::$commentContextTitle )
				{
					$newTarget = clone ( self::$commentContextTitle );
					$newTarget->setFragment( '#' . $target->getFragment() );
					$target = $newTarget;
				}
				$thelink = self::link(
					$target,
					$linkText . $inside
				) . $trail;
			}
		}
		if ( $thelink ) {
			// If the link is still valid, go ahead and replace it in!
			$comment = preg_replace( $linkRegexp, StringUtils::escapeRegexReplacement( $thelink ), $comment, 1 );
		}

		return $comment;
	}

	/**
	 * @param $contextTitle Title
	 * @param  $target
	 * @param  $text
	 * @return string
	 */
	static function normalizeSubpageLink( $contextTitle, $target, &$text ) {
		# Valid link forms:
		# Foobar -- normal
		# :Foobar -- override special treatment of prefix (images, language links)
		# /Foobar -- convert to CurrentPage/Foobar
		# /Foobar/ -- convert to CurrentPage/Foobar, strip the initial / from text
		# ../ -- convert to CurrentPage, from CurrentPage/CurrentSubPage
		# ../Foobar -- convert to CurrentPage/Foobar, from CurrentPage/CurrentSubPage

		wfProfileIn( __METHOD__ );
		$ret = $target; # default return value is no change

		# Some namespaces don't allow subpages,
		# so only perform processing if subpages are allowed
		if ( $contextTitle && MWNamespace::hasSubpages( $contextTitle->getNamespace() ) ) {
			$hash = strpos( $target, '#' );
			if ( $hash !== false ) {
				$suffix = substr( $target, $hash );
				$target = substr( $target, 0, $hash );
			} else {
				$suffix = '';
			}
			# bug 7425
			$target = trim( $target );
			# Look at the first character
			if ( $target != '' && $target { 0 } === '/' ) {
				# / at end means we don't want the slash to be shown
				$m = array();
				$trailingSlashes = preg_match_all( '%(/+)$%', $target, $m );
				if ( $trailingSlashes ) {
					$noslash = $target = substr( $target, 1, -strlen( $m[0][0] ) );
				} else {
					$noslash = substr( $target, 1 );
				}

				$ret = $contextTitle->getPrefixedText() . '/' . trim( $noslash ) . $suffix;
				if ( $text === '' ) {
					$text = $target . $suffix;
				} # this might be changed for ugliness reasons
			} else {
				# check for .. subpage backlinks
				$dotdotcount = 0;
				$nodotdot = $target;
				while ( strncmp( $nodotdot, "../", 3 ) == 0 ) {
					++$dotdotcount;
					$nodotdot = substr( $nodotdot, 3 );
				}
				if ( $dotdotcount > 0 ) {
					$exploded = explode( '/', $contextTitle->GetPrefixedText() );
					if ( count( $exploded ) > $dotdotcount ) { # not allowed to go below top level page
						$ret = implode( '/', array_slice( $exploded, 0, -$dotdotcount ) );
						# / at the end means don't show full path
						if ( substr( $nodotdot, -1, 1 ) === '/' ) {
							$nodotdot = substr( $nodotdot, 0, -1 );
							if ( $text === '' ) {
								$text = $nodotdot . $suffix;
							}
						}
						$nodotdot = trim( $nodotdot );
						if ( $nodotdot != '' ) {
							$ret .= '/' . $nodotdot;
						}
						$ret .= $suffix;
					}
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return empty string.
	 *
	 * @param $comment String
	 * @param $title Mixed: Title object (to generate link to section in autocomment) or null
	 * @param $local Boolean: whether section links should refer to local page
	 *
	 * @return string
	 */
	static function commentBlock( $comment, $title = null, $local = false ) {
		// '*' used to be the comment inserted by the software way back
		// in antiquity in case none was provided, here for backwards
		// compatability, acc. to brion -ævar
		if ( $comment == '' || $comment == '*' ) {
			return '';
		} else {
			$formatted = self::formatComment( $comment, $title, $local );
			return " <span class=\"comment\">($formatted)</span>";
		}
	}

	/**
	 * Wrap and format the given revision's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @param $rev Revision object
	 * @param $local Boolean: whether section links should refer to local page
	 * @param $isPublic Boolean: show only if all users can see it
	 * @return String: HTML fragment
	 */
	static function revComment( Revision $rev, $local = false, $isPublic = false ) {
		if ( $rev->getRawComment() == "" ) {
			return "";
		}
		if ( $rev->isDeleted( Revision::DELETED_COMMENT ) && $isPublic ) {
			$block = " <span class=\"comment\">" . wfMsgHtml( 'rev-deleted-comment' ) . "</span>";
		} elseif ( $rev->userCan( Revision::DELETED_COMMENT ) ) {
			$block = self::commentBlock( $rev->getComment( Revision::FOR_THIS_USER ),
				$rev->getTitle(), $local );
		} else {
			$block = " <span class=\"comment\">" . wfMsgHtml( 'rev-deleted-comment' ) . "</span>";
		}
		if ( $rev->isDeleted( Revision::DELETED_COMMENT ) ) {
			return " <span class=\"history-deleted\">$block</span>";
		}
		return $block;
	}

	/**
	 * @param $size
	 * @return string
	 */
	public static function formatRevisionSize( $size ) {
		if ( $size == 0 ) {
			$stxt = wfMsgExt( 'historyempty', 'parsemag' );
		} else {
			global $wgLang;
			$stxt = wfMsgExt( 'nbytes', 'parsemag', $wgLang->formatNum( $size ) );
			$stxt = "($stxt)";
		}
		$stxt = htmlspecialchars( $stxt );
		return "<span class=\"history-size\">$stxt</span>";
	}

	/**
	 * Add another level to the Table of Contents
	 *
	 * @return string
	 */
	static function tocIndent() {
		return "\n<ul>";
	}

	/**
	 * Finish one or more sublevels on the Table of Contents
	 *
	 * @return string
	 */
	static function tocUnindent( $level ) {
		return "</li>\n" . str_repeat( "</ul>\n</li>\n", $level > 0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 *
	 * @return string
	 */
	static function tocLine( $anchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
		$classes = "toclevel-$level";
		if ( $sectionIndex !== false ) {
			$classes .= " tocsection-$sectionIndex";
		}
		return "\n<li class=\"$classes\"><a href=\"#" .
			$anchor . '"><span class="tocnumber">' .
			$tocnumber . '</span> <span class="toctext">' .
			$tocline . '</span></a>';
	}

	/**
	 * End a Table Of Contents line.
	 * tocUnindent() will be used instead if we're ending a line below
	 * the new level.
	 */
	static function tocLineEnd() {
		return "</li>\n";
	}

	/**
	 * Wraps the TOC in a table and provides the hide/collapse javascript.
	 *
	 * @param $toc String: html of the Table Of Contents
	 * @param $lang mixed: Language code for the toc title
	 * @return String: full html of the TOC
	 */
	static function tocList( $toc, $lang = false ) {
		$title = wfMsgExt( 'toc', array( 'language' => $lang, 'escape' ) );
		return
		   '<table id="toc" class="toc"><tr><td>'
		 . '<div id="toctitle"><h2>' . $title . "</h2></div>\n"
		 . $toc
		 . "</ul>\n</td></tr></table>\n";
	}

	/**
	 * Generate a table of contents from a section tree
	 * Currently unused.
	 *
	 * @param $tree Return value of ParserOutput::getSections()
	 * @return String: HTML fragment
	 */
	public static function generateTOC( $tree ) {
		$toc = '';
		$lastLevel = 0;
		foreach ( $tree as $section ) {
			if ( $section['toclevel'] > $lastLevel )
				$toc .= self::tocIndent();
			elseif ( $section['toclevel'] < $lastLevel )
				$toc .= self::tocUnindent(
					$lastLevel - $section['toclevel'] );
			else
				$toc .= self::tocLineEnd();

			$toc .= self::tocLine( $section['anchor'],
				$section['line'], $section['number'],
				$section['toclevel'], $section['index'] );
			$lastLevel = $section['toclevel'];
		}
		$toc .= self::tocLineEnd();
		return self::tocList( $toc );
	}

	/**
	 * Create a headline for content
	 *
	 * @param $level Integer: the level of the headline (1-6)
	 * @param $attribs String: any attributes for the headline, starting with
	 *                 a space and ending with '>'
	 *                 This *must* be at least '>' for no attribs
	 * @param $anchor String: the anchor to give the headline (the bit after the #)
	 * @param $text String: the text of the header
	 * @param $link String: HTML to add for the section edit link
	 * @param $legacyAnchor Mixed: a second, optional anchor to give for
	 *   backward compatibility (false to omit)
	 *
	 * @return String: HTML headline
	 */
	public static function makeHeadline( $level, $attribs, $anchor, $text, $link, $legacyAnchor = false ) {
		$ret = "<h$level$attribs"
			. $link
			. " <span class=\"mw-headline\" id=\"$anchor\">$text</span>"
			. "</h$level>";
		if ( $legacyAnchor !== false ) {
			$ret = "<div id=\"$legacyAnchor\"></div>$ret";
		}
		return $ret;
	}

	/**
	 * Split a link trail, return the "inside" portion and the remainder of the trail
	 * as a two-element array
	 */
	static function splitTrail( $trail ) {
		global $wgContLang;
		$regex = $wgContLang->linkTrail();
		$inside = '';
		if ( $trail !== '' ) {
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
	 * @param $rev Revision object
	 */
	static function generateRollback( $rev ) {
		return '<span class="mw-rollback-link">['
			. self::buildRollbackLink( $rev )
			. ']</span>';
	}

	/**
	 * Build a raw rollback link, useful for collections of "tool" links
	 *
	 * @param $rev Revision object
	 * @return String: HTML fragment
	 */
	public static function buildRollbackLink( $rev ) {
		global $wgRequest, $wgUser;
		$title = $rev->getTitle();
		$query = array(
			'action' => 'rollback',
			'from' => $rev->getUserText(),
			'token' => $wgUser->editToken( array( $title->getPrefixedText(), $rev->getUserText() ) ),
		);
		if ( $wgRequest->getBool( 'bot' ) ) {
			$query['bot'] = '1';
			$query['hidediff'] = '1'; // bug 15999
		}
		return self::link(
			$title,
			wfMsgHtml( 'rollbacklink' ),
			array( 'title' => wfMsg( 'tooltip-rollback' ) ),
			$query,
			array( 'known', 'noclasses' )
		);
	}

	/**
	 * Returns HTML for the "templates used on this page" list.
	 *
	 * @param $templates Array of templates from Article::getUsedTemplate
	 * or similar
	 * @param $preview Boolean: whether this is for a preview
	 * @param $section Boolean: whether this is for a section edit
	 * @return String: HTML output
	 */
	public static function formatTemplates( $templates, $preview = false, $section = false ) {
		wfProfileIn( __METHOD__ );

		$outText = '';
		if ( count( $templates ) > 0 ) {
			# Do a batch existence check
			$batch = new LinkBatch;
			foreach ( $templates as $title ) {
				$batch->addObj( $title );
			}
			$batch->execute();

			# Construct the HTML
			$outText = '<div class="mw-templatesUsedExplanation">';
			if ( $preview ) {
				$outText .= wfMsgExt( 'templatesusedpreview', array( 'parse' ), count( $templates ) );
			} elseif ( $section ) {
				$outText .= wfMsgExt( 'templatesusedsection', array( 'parse' ), count( $templates ) );
			} else {
				$outText .= wfMsgExt( 'templatesused', array( 'parse' ), count( $templates ) );
			}
			$outText .= "</div><ul>\n";

			usort( $templates, array( 'Title', 'compare' ) );
			foreach ( $templates as $titleObj ) {
				$r = $titleObj->getRestrictions( 'edit' );
				if ( in_array( 'sysop', $r ) ) {
					$protected = wfMsgExt( 'template-protected', array( 'parseinline' ) );
				} elseif ( in_array( 'autoconfirmed', $r ) ) {
					$protected = wfMsgExt( 'template-semiprotected', array( 'parseinline' ) );
				} else {
					$protected = '';
				}
				if ( $titleObj->quickUserCan( 'edit' ) ) {
					$editLink = self::link(
						$titleObj,
						wfMsg( 'editlink' ),
						array(),
						array( 'action' => 'edit' )
					);
				} else {
					$editLink = self::link(
						$titleObj,
						wfMsg( 'viewsourcelink' ),
						array(),
						array( 'action' => 'edit' )
					);
				}
				$outText .= '<li>' . self::link( $titleObj ) . ' (' . $editLink . ') ' . $protected . '</li>';
			}
			$outText .= '</ul>';
		}
		wfProfileOut( __METHOD__  );
		return $outText;
	}

	/**
	 * Returns HTML for the "hidden categories on this page" list.
	 *
	 * @param $hiddencats Array of hidden categories from Article::getHiddenCategories
	 * or similar
	 * @return String: HTML output
	 */
	public static function formatHiddenCategories( $hiddencats ) {
		global $wgLang;
		wfProfileIn( __METHOD__ );

		$outText = '';
		if ( count( $hiddencats ) > 0 ) {
			# Construct the HTML
			$outText = '<div class="mw-hiddenCategoriesExplanation">';
			$outText .= wfMsgExt( 'hiddencategories', array( 'parse' ), $wgLang->formatnum( count( $hiddencats ) ) );
			$outText .= "</div><ul>\n";

			foreach ( $hiddencats as $titleObj ) {
				$outText .= '<li>' . self::link( $titleObj, null, array(), array(), 'known' ) . "</li>\n"; # If it's hidden, it must exist - no need to check with a LinkBatch
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
	 * @return String
	 */
	public static function formatSize( $size ) {
		global $wgLang;
		return htmlspecialchars( $wgLang->formatSize( $size ) );
	}

	/**
	 * Given the id of an interface element, constructs the appropriate title
	 * attribute from the system messages.  (Note, this is usually the id but
	 * isn't always, because sometimes the accesskey needs to go on a different
	 * element than the id, for reverse-compatibility, etc.)
	 *
	 * @param $name String: id of the element, minus prefixes.
	 * @param $options Mixed: null or the string 'withaccess' to add an access-
	 *   key hint
	 * @return String: contents of the title attribute (which you must HTML-
	 *   escape), or false for no title attribute
	 */
	public static function titleAttrib( $name, $options = null ) {
		global $wgEnableTooltipsAndAccesskeys;
		if ( !$wgEnableTooltipsAndAccesskeys )
			return false;

		wfProfileIn( __METHOD__ );

		$message = wfMessage( "tooltip-$name" );

		if ( !$message->exists() ) {
			$tooltip = false;
		} else {
			$tooltip = $message->text();
			# Compatibility: formerly some tooltips had [alt-.] hardcoded
			$tooltip = preg_replace( "/ ?\[alt-.\]$/", '', $tooltip );
			# Message equal to '-' means suppress it.
			if (  $tooltip == '-' ) {
				$tooltip = false;
			}
		}

		if ( $options == 'withaccess' ) {
			$accesskey = self::accesskey( $name );
			if ( $accesskey !== false ) {
				if ( $tooltip === false || $tooltip === '' ) {
					$tooltip = "[$accesskey]";
				} else {
					$tooltip .= " [$accesskey]";
				}
			}
		}

		wfProfileOut( __METHOD__ );
		return $tooltip;
	}

	static $accesskeycache;

	/**
	 * Given the id of an interface element, constructs the appropriate
	 * accesskey attribute from the system messages.  (Note, this is usually
	 * the id but isn't always, because sometimes the accesskey needs to go on
	 * a different element than the id, for reverse-compatibility, etc.)
	 *
	 * @param $name String: id of the element, minus prefixes.
	 * @return String: contents of the accesskey attribute (which you must HTML-
	 *   escape), or false for no accesskey attribute
	 */
	public static function accesskey( $name ) {
		if ( isset( self::$accesskeycache[$name] ) ) {
			return self::$accesskeycache[$name];
		}
		wfProfileIn( __METHOD__ );

		$message = wfMessage( "accesskey-$name" );

		if ( !$message->exists() ) {
			$accesskey = false;
		} else {
			$accesskey = $message->plain();
			if ( $accesskey === '' || $accesskey === '-' ) {
				# @todo FIXME: Per standard MW behavior, a value of '-' means to suppress the
				# attribute, but this is broken for accesskey: that might be a useful
				# value.
				$accesskey = false;
			}
		}

		wfProfileOut( __METHOD__ );
		return self::$accesskeycache[$name] = $accesskey;
	}

	/**
	 * Creates a (show/hide) link for deleting revisions/log entries
	 *
	 * @param $query Array: query parameters to be passed to link()
	 * @param $restricted Boolean: set to true to use a <strong> instead of a <span>
	 * @param $delete Boolean: set to true to use (show/hide) rather than (show)
	 *
	 * @return String: HTML <a> link to Special:Revisiondelete, wrapped in a
	 * span to allow for customization of appearance with CSS
	 */
	public static function revDeleteLink( $query = array(), $restricted = false, $delete = true ) {
		$sp = SpecialPage::getTitleFor( 'Revisiondelete' );
		$text = $delete ? wfMsgHtml( 'rev-delundel' ) : wfMsgHtml( 'rev-showdeleted' );
		$tag = $restricted ? 'strong' : 'span';
		$link = self::link( $sp, $text, array(), $query, array( 'known', 'noclasses' ) );
		return Xml::tags( $tag, array( 'class' => 'mw-revdelundel-link' ), "($link)" );
	}

	/**
	 * Creates a dead (show/hide) link for deleting revisions/log entries
	 *
	 * @param $delete Boolean: set to true to use (show/hide) rather than (show)
	 *
	 * @return string HTML text wrapped in a span to allow for customization
	 * of appearance with CSS
	 */
	public static function revDeleteLinkDisabled( $delete = true ) {
		$text = $delete ? wfMsgHtml( 'rev-delundel' ) : wfMsgHtml( 'rev-showdeleted' );
		return Xml::tags( 'span', array( 'class' => 'mw-revdelundel-link' ), "($text)" );
	}

	/* Deprecated methods */

	/**
	 * @deprecated since 1.16 Use link()
	 *
	 * This function is a shortcut to makeBrokenLinkObj(Title::newFromText($title),...). Do not call
	 * it if you already have a title object handy. See makeBrokenLinkObj for further documentation.
	 *
	 * @param $title String: The text of the title
	 * @param $text String: Link text
	 * @param $query String: Optional query part
	 * @param $trail String: Optional trail. Alphabetic characters at the start of this string will
	 *               be included in the link text. Other characters will be appended after
	 *               the end of the link.
	 */
	static function makeBrokenLink( $title, $text = '', $query = '', $trail = '' ) {
		$nt = Title::newFromText( $title );
		if ( $nt instanceof Title ) {
			return self::makeBrokenLinkObj( $nt, $text, $query, $trail );
		} else {
			wfDebug( 'Invalid title passed to self::makeBrokenLink(): "' . $title . "\"\n" );
			return $text == '' ? $title : $text;
		}
	}

	/**
	 * @deprecated since 1.16 Use link()
	 *
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
	static function makeLinkObj( $nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		wfProfileIn( __METHOD__ );
		$query = wfCgiToArray( $query );
		list( $inside, $trail ) = self::splitTrail( $trail );
		if ( $text === '' ) {
			$text = self::linkText( $nt );
		}

		$ret = self::link( $nt, "$prefix$text$inside", array(), $query ) . $trail;

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @deprecated since 1.16 Use link()
	 *
	 * Make a link for a title which definitely exists. This is faster than makeLinkObj because
	 * it doesn't have to do a database query. It's also valid for interwiki titles and special
	 * pages.
	 *
	 * @param $title  Title object of target page
	 * @param $text   String: text to replace the title
	 * @param $query  String: link target
	 * @param $trail  String: text after link
	 * @param $prefix String: text before link text
	 * @param $aprops String: extra attributes to the a-element
	 * @param $style  String: style to apply - if empty, use getInternalLinkAttributesObj instead
	 * @return the a-element
	 */
	static function makeKnownLinkObj(
		$title, $text = '', $query = '', $trail = '', $prefix = '' , $aprops = '', $style = ''
	) {
		wfProfileIn( __METHOD__ );

		if ( $text == '' ) {
			$text = self::linkText( $title );
		}
		$attribs = Sanitizer::mergeAttributes(
			Sanitizer::decodeTagAttributes( $aprops ),
			Sanitizer::decodeTagAttributes( $style )
		);
		$query = wfCgiToArray( $query );
		list( $inside, $trail ) = self::splitTrail( $trail );

		$ret = self::link( $title, "$prefix$text$inside", $attribs, $query,
			array( 'known', 'noclasses' ) ) . $trail;

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @deprecated since 1.16 Use link()
	 *
	 * Make a red link to the edit page of a given title.
	 *
	 * @param $title Title object of the target page
	 * @param $text  String: Link text
	 * @param $query String: Optional query part
	 * @param $trail String: Optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 * @param $prefix String: Optional prefix
	 */
	static function makeBrokenLinkObj( $title, $text = '', $query = '', $trail = '', $prefix = '' ) {
		wfProfileIn( __METHOD__ );

		list( $inside, $trail ) = self::splitTrail( $trail );
		if ( $text === '' ) {
			$text = self::linkText( $title );
		}

		$ret = self::link( $title, "$prefix$text$inside", array(),
			wfCgiToArray( $query ), 'broken' ) . $trail;

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @deprecated since 1.16 Use link()
	 *
	 * Make a coloured link.
	 *
	 * @param $nt Title object of the target page
	 * @param $colour Integer: colour of the link
	 * @param $text   String:  link text
	 * @param $query  String:  optional query part
	 * @param $trail  String:  optional trail. Alphabetic characters at the start of this string will
	 *                      be included in the link text. Other characters will be appended after
	 *                      the end of the link.
	 * @param $prefix String: Optional prefix
	 */
	static function makeColouredLinkObj( $nt, $colour, $text = '', $query = '', $trail = '', $prefix = '' ) {
		if ( $colour != '' ) {
			$style = self::getInternalLinkAttributesObj( $nt, $text, $colour );
		} else {
			$style = '';
		}
		return self::makeKnownLinkObj( $nt, $text, $query, $trail, $prefix, '', $style );
	}

	/**
	 * Returns the attributes for the tooltip and access key.
	 */
	public static function tooltipAndAccesskeyAttribs( $name ) {
		global $wgEnableTooltipsAndAccesskeys;
		if ( !$wgEnableTooltipsAndAccesskeys )
			return array();
		# @todo FIXME: If Sanitizer::expandAttributes() treated "false" as "output
		# no attribute" instead of "output '' as value for attribute", this
		# would be three lines.
		$attribs = array(
			'title' => self::titleAttrib( $name, 'withaccess' ),
			'accesskey' => self::accesskey( $name )
		);
		if ( $attribs['title'] === false ) {
			unset( $attribs['title'] );
		}
		if ( $attribs['accesskey'] === false ) {
			unset( $attribs['accesskey'] );
		}
		return $attribs;
	}

	/**
	 * @deprecated since 1.14
	 * Returns raw bits of HTML, use titleAttrib()
	 */
	public static function tooltip( $name, $options = null ) {
		global $wgEnableTooltipsAndAccesskeys;
		if ( !$wgEnableTooltipsAndAccesskeys )
			return '';
		# @todo FIXME: If Sanitizer::expandAttributes() treated "false" as "output
		# no attribute" instead of "output '' as value for attribute", this
		# would be two lines.
		$tooltip = self::titleAttrib( $name, $options );
		if ( $tooltip === false ) {
			return '';
		}
		return Xml::expandAttributes( array(
			'title' => $tooltip
		) );
	}
}

/**
 * @since 1.18
 */
class DummyLinker {

	/**
	 * Use PHP's magic __call handler to transform instance calls to a dummy instance
	 * into static calls to the new Linker for backwards compatibility.
	 *
	 * @param $fname String Name of called method
	 * @param $args Array Arguments to the method
	 */
	public function __call( $fname, $args ) {
		return call_user_func_array( array( 'Linker', $fname ), $args );
	}
}

