<?php
/**
 * Methods to make links and related items.
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
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;

/**
 * Some internal bits split of from Skin.php. These functions are used
 * for primarily page content: links, embedded images, table of contents. Links
 * are also used in the skin.
 *
 * @todo turn this into a legacy interface for HtmlPageLinkRenderer and similar services.
 *
 * @ingroup Skins
 */
class Linker {
	/**
	 * Flags for userToolLinks()
	 */
	const TOOL_LINKS_NOBLOCK = 1;
	const TOOL_LINKS_EMAIL = 2;

	/**
	 * Return the CSS colour of a known link
	 *
	 * @deprecated since 1.28, use LinkRenderer::getLinkClasses() instead
	 *
	 * @since 1.16.3
	 * @param LinkTarget $t
	 * @param int $threshold User defined threshold
	 * @return string CSS class
	 */
	public static function getLinkColour( LinkTarget $t, $threshold ) {
		wfDeprecated( __METHOD__, '1.28' );
		$services = MediaWikiServices::getInstance();
		$linkRenderer = $services->getLinkRenderer();
		if ( $threshold !== $linkRenderer->getStubThreshold() ) {
			// Need to create a new instance with the right stub threshold...
			$linkRenderer = $services->getLinkRendererFactory()->create();
			$linkRenderer->setStubThreshold( $threshold );
		}

		return $linkRenderer->getLinkClasses( $t );
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
	 * @since 1.18 Method exists since 1.16 as non-static, made static in 1.18.
	 * @deprecated since 1.28, use MediaWiki\Linker\LinkRenderer instead
	 *
	 * @param Title $target Can currently only be a Title, but this may
	 *   change to support Images, literal URLs, etc.
	 * @param string $html The HTML contents of the <a> element, i.e.,
	 *   the link text.  This is raw HTML and will not be escaped.  If null,
	 *   defaults to the prefixed text of the Title; or if the Title is just a
	 *   fragment, the contents of the fragment.
	 * @param array $customAttribs A key => value array of extra HTML attributes,
	 *   such as title and class.  (href is ignored.)  Classes will be
	 *   merged with the default classes, while other attributes will replace
	 *   default attributes.  All passed attribute values will be HTML-escaped.
	 *   A false attribute value means to suppress that attribute.
	 * @param array $query The query string to append to the URL
	 *   you're linking to, in key => value array form.  Query keys and values
	 *   will be URL-encoded.
	 * @param string|array $options String or array of strings:
	 *     'known': Page is known to exist, so don't check if it does.
	 *     'broken': Page is known not to exist, so don't check if it does.
	 *     'noclasses': Don't add any classes automatically (includes "new",
	 *       "stub", "mw-redirect", "extiw").  Only use the class attribute
	 *       provided, if any, so you get a simple blue link with no funny i-
	 *       cons.
	 *     'forcearticlepath': Use the article path always, even with a querystring.
	 *       Has compatibility issues on some setups, so avoid wherever possible.
	 *     'http': Force a full URL with http:// as the scheme.
	 *     'https': Force a full URL with https:// as the scheme.
	 *     'stubThreshold' => (int): Stub threshold to use when determining link classes.
	 * @return string HTML <a> attribute
	 */
	public static function link(
		$target, $html = null, $customAttribs = [], $query = [], $options = []
	) {
		if ( !$target instanceof Title ) {
			wfWarn( __METHOD__ . ': Requires $target to be a Title object.', 2 );
			return "<!-- ERROR -->$html";
		}

		if ( is_string( $query ) ) {
			// some functions withing core using this still hand over query strings
			wfDeprecated( __METHOD__ . ' with parameter $query as string (should be array)', '1.20' );
			$query = wfCgiToArray( $query );
		}

		$services = MediaWikiServices::getInstance();
		$options = (array)$options;
		if ( $options ) {
			// Custom options, create new LinkRenderer
			if ( !isset( $options['stubThreshold'] ) ) {
				$defaultLinkRenderer = $services->getLinkRenderer();
				$options['stubThreshold'] = $defaultLinkRenderer->getStubThreshold();
			}
			$linkRenderer = $services->getLinkRendererFactory()
				->createFromLegacyOptions( $options );
		} else {
			$linkRenderer = $services->getLinkRenderer();
		}

		if ( $html !== null ) {
			$text = new HtmlArmor( $html );
		} else {
			$text = $html; // null
		}
		if ( in_array( 'known', $options, true ) ) {
			return $linkRenderer->makeKnownLink( $target, $text, $customAttribs, $query );
		} elseif ( in_array( 'broken', $options, true ) ) {
			return $linkRenderer->makeBrokenLink( $target, $text, $customAttribs, $query );
		} elseif ( in_array( 'noclasses', $options, true ) ) {
			return $linkRenderer->makePreloadedLink( $target, $text, '', $customAttribs, $query );
		} else {
			return $linkRenderer->makeLink( $target, $text, $customAttribs, $query );
		}
	}

	/**
	 * Identical to link(), except $options defaults to 'known'.
	 *
	 * @since 1.16.3
	 * @deprecated since 1.28, use MediaWiki\Linker\LinkRenderer instead
	 * @see Linker::link
	 * @return string
	 */
	public static function linkKnown(
		$target, $html = null, $customAttribs = [],
		$query = [], $options = [ 'known' ]
	) {
		return self::link( $target, $html, $customAttribs, $query, $options );
	}

	/**
	 * Make appropriate markup for a link to the current article. This is since
	 * MediaWiki 1.29.0 rendered as an <a> tag without an href and with a class
	 * showing the link text. The calling sequence is the same as for the other
	 * make*LinkObj static functions, but $query is not used.
	 *
	 * @since 1.16.3
	 * @param Title $nt
	 * @param string $html [optional]
	 * @param string $query [optional]
	 * @param string $trail [optional]
	 * @param string $prefix [optional]
	 *
	 * @return string
	 */
	public static function makeSelfLinkObj( $nt, $html = '', $query = '', $trail = '', $prefix = '' ) {
		$ret = "<a class=\"mw-selflink selflink\">{$prefix}{$html}</a>{$trail}";
		if ( !Hooks::run( 'SelfLinkBegin', [ $nt, &$html, &$trail, &$prefix, &$ret ] ) ) {
			return $ret;
		}

		if ( $html == '' ) {
			$html = htmlspecialchars( $nt->getPrefixedText() );
		}
		list( $inside, $trail ) = self::splitTrail( $trail );
		return "<a class=\"mw-selflink selflink\">{$prefix}{$html}{$inside}</a>{$trail}";
	}

	/**
	 * Get a message saying that an invalid title was encountered.
	 * This should be called after a method like Title::makeTitleSafe() returned
	 * a value indicating that the title object is invalid.
	 *
	 * @param IContextSource $context Context to use to get the messages
	 * @param int $namespace Namespace number
	 * @param string $title Text of the title, without the namespace part
	 * @return string
	 */
	public static function getInvalidTitleDescription( IContextSource $context, $namespace, $title ) {
		global $wgContLang;

		// First we check whether the namespace exists or not.
		if ( MWNamespace::exists( $namespace ) ) {
			if ( $namespace == NS_MAIN ) {
				$name = $context->msg( 'blanknamespace' )->text();
			} else {
				$name = $wgContLang->getFormattedNsText( $namespace );
			}
			return $context->msg( 'invalidtitle-knownnamespace', $namespace, $name, $title )->text();
		} else {
			return $context->msg( 'invalidtitle-unknownnamespace', $namespace, $title )->text();
		}
	}

	/**
	 * @since 1.16.3
	 * @param LinkTarget $target
	 * @return LinkTarget
	 */
	public static function normaliseSpecialPage( LinkTarget $target ) {
		if ( $target->getNamespace() == NS_SPECIAL && !$target->isExternal() ) {
			list( $name, $subpage ) = SpecialPageFactory::resolveAlias( $target->getDBkey() );
			if ( !$name ) {
				return $target;
			}
			$ret = SpecialPage::getTitleValueFor( $name, $subpage, $target->getFragment() );
			return $ret;
		} else {
			return $target;
		}
	}

	/**
	 * Returns the filename part of an url.
	 * Used as alternative text for external images.
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private static function fnamePart( $url ) {
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
	 * @since 1.16.3
	 * @param string $url
	 * @param string $alt
	 *
	 * @return string
	 */
	public static function makeExternalImage( $url, $alt = '' ) {
		if ( $alt == '' ) {
			$alt = self::fnamePart( $url );
		}
		$img = '';
		$success = Hooks::run( 'LinkerMakeExternalImage', [ &$url, &$alt, &$img ] );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalImage changed the output of external image "
				. "with url {$url} and alt text {$alt} to {$img}\n", true );
			return $img;
		}
		return Html::element( 'img',
			[
				'src' => $url,
				'alt' => $alt ] );
	}

	/**
	 * Given parameters derived from [[Image:Foo|options...]], generate the
	 * HTML that that syntax inserts in the page.
	 *
	 * @param Parser $parser
	 * @param Title $title Title object of the file (not the currently viewed page)
	 * @param File $file File object, or false if it doesn't exist
	 * @param array $frameParams Associative array of parameters external to the media handler.
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
	 *          class           HTML for image classes. Plain text.
	 *          caption         HTML for image caption.
	 *          link-url        URL to link to
	 *          link-title      Title object to link to
	 *          link-target     Value for the target attribute, only with link-url
	 *          no-link         Boolean, suppress description link
	 *
	 * @param array $handlerParams Associative array of media handler parameters, to be passed
	 *       to transform(). Typical keys are "width" and "page".
	 * @param string|bool $time Timestamp of the file, set as false for current
	 * @param string $query Query params for desc url
	 * @param int|null $widthOption Used by the parser to remember the user preference thumbnailsize
	 * @since 1.20
	 * @return string HTML for an image, with links, wrappers, etc.
	 */
	public static function makeImageLink( Parser $parser, Title $title,
		$file, $frameParams = [], $handlerParams = [], $time = false,
		$query = "", $widthOption = null
	) {
		$res = null;
		$dummy = new DummyLinker;
		if ( !Hooks::run( 'ImageBeforeProduceHTML', [ &$dummy, &$title,
			&$file, &$frameParams, &$handlerParams, &$time, &$res ] ) ) {
			return $res;
		}

		if ( $file && !$file->allowInlineDisplay() ) {
			wfDebug( __METHOD__ . ': ' . $title->getPrefixedDBkey() . " does not allow inline display\n" );
			return self::link( $title );
		}

		// Clean up parameters
		$page = isset( $handlerParams['page'] ) ? $handlerParams['page'] : false;
		if ( !isset( $frameParams['align'] ) ) {
			$frameParams['align'] = '';
		}
		if ( !isset( $frameParams['alt'] ) ) {
			$frameParams['alt'] = '';
		}
		if ( !isset( $frameParams['title'] ) ) {
			$frameParams['title'] = '';
		}
		if ( !isset( $frameParams['class'] ) ) {
			$frameParams['class'] = '';
		}

		$prefix = $postfix = '';

		if ( 'center' == $frameParams['align'] ) {
			$prefix = '<div class="center">';
			$postfix = '</div>';
			$frameParams['align'] = 'none';
		}
		if ( $file && !isset( $handlerParams['width'] ) ) {
			if ( isset( $handlerParams['height'] ) && $file->isVectorized() ) {
				// If its a vector image, and user only specifies height
				// we don't want it to be limited by its "normal" width.
				global $wgSVGMaxSize;
				$handlerParams['width'] = $wgSVGMaxSize;
			} else {
				$handlerParams['width'] = $file->getWidth( $page );
			}

			if ( isset( $frameParams['thumbnail'] )
				|| isset( $frameParams['manualthumb'] )
				|| isset( $frameParams['framed'] )
				|| isset( $frameParams['frameless'] )
				|| !$handlerParams['width']
			) {
				global $wgThumbLimits, $wgThumbUpright;

				if ( $widthOption === null || !isset( $wgThumbLimits[$widthOption] ) ) {
					$widthOption = User::getDefaultOption( 'thumbsize' );
				}

				// Reduce width for upright images when parameter 'upright' is used
				if ( isset( $frameParams['upright'] ) && $frameParams['upright'] == 0 ) {
					$frameParams['upright'] = $wgThumbUpright;
				}

				// For caching health: If width scaled down due to upright
				// parameter, round to full __0 pixel to avoid the creation of a
				// lot of odd thumbs.
				$prefWidth = isset( $frameParams['upright'] ) ?
					round( $wgThumbLimits[$widthOption] * $frameParams['upright'], -1 ) :
					$wgThumbLimits[$widthOption];

				// Use width which is smaller: real image width or user preference width
				// Unless image is scalable vector.
				if ( !isset( $handlerParams['height'] ) && ( $handlerParams['width'] <= 0 ||
						$prefWidth < $handlerParams['width'] || $file->isVectorized() ) ) {
					$handlerParams['width'] = $prefWidth;
				}
			}
		}

		if ( isset( $frameParams['thumbnail'] ) || isset( $frameParams['manualthumb'] )
			|| isset( $frameParams['framed'] )
		) {
			# Create a thumbnail. Alignment depends on the writing direction of
			# the page content language (right-aligned for LTR languages,
			# left-aligned for RTL languages)
			# If a thumbnail width has not been provided, it is set
			# to the default user option as specified in Language*.php
			if ( $frameParams['align'] == '' ) {
				$frameParams['align'] = $parser->getTargetLanguage()->alignEnd();
			}
			return $prefix .
				self::makeThumbLink2( $title, $file, $frameParams, $handlerParams, $time, $query ) .
				$postfix;
		}

		if ( $file && isset( $frameParams['frameless'] ) ) {
			$srcWidth = $file->getWidth( $page );
			# For "frameless" option: do not present an image bigger than the
			# source (for bitmap-style images). This is the same behavior as the
			# "thumb" option does it already.
			if ( $srcWidth && !$file->mustRender() && $handlerParams['width'] > $srcWidth ) {
				$handlerParams['width'] = $srcWidth;
			}
		}

		if ( $file && isset( $handlerParams['width'] ) ) {
			# Create a resized image, without the additional thumbnail features
			$thumb = $file->transform( $handlerParams );
		} else {
			$thumb = false;
		}

		if ( !$thumb ) {
			$s = self::makeBrokenImageLinkObj( $title, $frameParams['title'], '', '', '', $time == true );
		} else {
			self::processResponsiveImages( $file, $thumb, $handlerParams );
			$params = [
				'alt' => $frameParams['alt'],
				'title' => $frameParams['title'],
				'valign' => isset( $frameParams['valign'] ) ? $frameParams['valign'] : false,
				'img-class' => $frameParams['class'] ];
			if ( isset( $frameParams['border'] ) ) {
				$params['img-class'] .= ( $params['img-class'] !== '' ? ' ' : '' ) . 'thumbborder';
			}
			$params = self::getImageLinkMTOParams( $frameParams, $query, $parser ) + $params;

			$s = $thumb->toHtml( $params );
		}
		if ( $frameParams['align'] != '' ) {
			$s = "<div class=\"float{$frameParams['align']}\">{$s}</div>";
		}
		return str_replace( "\n", ' ', $prefix . $s . $postfix );
	}

	/**
	 * Get the link parameters for MediaTransformOutput::toHtml() from given
	 * frame parameters supplied by the Parser.
	 * @param array $frameParams The frame parameters
	 * @param string $query An optional query string to add to description page links
	 * @param Parser|null $parser
	 * @return array
	 */
	private static function getImageLinkMTOParams( $frameParams, $query = '', $parser = null ) {
		$mtoParams = [];
		if ( isset( $frameParams['link-url'] ) && $frameParams['link-url'] !== '' ) {
			$mtoParams['custom-url-link'] = $frameParams['link-url'];
			if ( isset( $frameParams['link-target'] ) ) {
				$mtoParams['custom-target-link'] = $frameParams['link-target'];
			}
			if ( $parser ) {
				$extLinkAttrs = $parser->getExternalLinkAttribs( $frameParams['link-url'] );
				foreach ( $extLinkAttrs as $name => $val ) {
					// Currently could include 'rel' and 'target'
					$mtoParams['parser-extlink-' . $name] = $val;
				}
			}
		} elseif ( isset( $frameParams['link-title'] ) && $frameParams['link-title'] !== '' ) {
			$mtoParams['custom-title-link'] = Title::newFromLinkTarget(
				self::normaliseSpecialPage( $frameParams['link-title'] )
			);
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
	 * @param Title $title
	 * @param File|bool $file File object or false if it doesn't exist
	 * @param string $label
	 * @param string $alt
	 * @param string $align
	 * @param array $params
	 * @param bool $framed
	 * @param string $manualthumb
	 * @return string
	 */
	public static function makeThumbLinkObj( Title $title, $file, $label = '', $alt,
		$align = 'right', $params = [], $framed = false, $manualthumb = ""
	) {
		$frameParams = [
			'alt' => $alt,
			'caption' => $label,
			'align' => $align
		];
		if ( $framed ) {
			$frameParams['framed'] = true;
		}
		if ( $manualthumb ) {
			$frameParams['manualthumb'] = $manualthumb;
		}
		return self::makeThumbLink2( $title, $file, $frameParams, $params );
	}

	/**
	 * @param Title $title
	 * @param File $file
	 * @param array $frameParams
	 * @param array $handlerParams
	 * @param bool $time
	 * @param string $query
	 * @return string
	 */
	public static function makeThumbLink2( Title $title, $file, $frameParams = [],
		$handlerParams = [], $time = false, $query = ""
	) {
		$exists = $file && $file->exists();

		$page = isset( $handlerParams['page'] ) ? $handlerParams['page'] : false;
		if ( !isset( $frameParams['align'] ) ) {
			$frameParams['align'] = 'right';
		}
		if ( !isset( $frameParams['alt'] ) ) {
			$frameParams['alt'] = '';
		}
		if ( !isset( $frameParams['title'] ) ) {
			$frameParams['title'] = '';
		}
		if ( !isset( $frameParams['caption'] ) ) {
			$frameParams['caption'] = '';
		}

		if ( empty( $handlerParams['width'] ) ) {
			// Reduce width for upright images when parameter 'upright' is used
			$handlerParams['width'] = isset( $frameParams['upright'] ) ? 130 : 180;
		}
		$thumb = false;
		$noscale = false;
		$manualthumb = false;

		if ( !$exists ) {
			$outerWidth = $handlerParams['width'] + 2;
		} else {
			if ( isset( $frameParams['manualthumb'] ) ) {
				# Use manually specified thumbnail
				$manual_title = Title::makeTitleSafe( NS_FILE, $frameParams['manualthumb'] );
				if ( $manual_title ) {
					$manual_img = wfFindFile( $manual_title );
					if ( $manual_img ) {
						$thumb = $manual_img->getUnscaledThumb( $handlerParams );
						$manualthumb = true;
					} else {
						$exists = false;
					}
				}
			} elseif ( isset( $frameParams['framed'] ) ) {
				// Use image dimensions, don't scale
				$thumb = $file->getUnscaledThumb( $handlerParams );
				$noscale = true;
			} else {
				# Do not present an image bigger than the source, for bitmap-style images
				# This is a hack to maintain compatibility with arbitrary pre-1.10 behavior
				$srcWidth = $file->getWidth( $page );
				if ( $srcWidth && !$file->mustRender() && $handlerParams['width'] > $srcWidth ) {
					$handlerParams['width'] = $srcWidth;
				}
				$thumb = $file->transform( $handlerParams );
			}

			if ( $thumb ) {
				$outerWidth = $thumb->getWidth() + 2;
			} else {
				$outerWidth = $handlerParams['width'] + 2;
			}
		}

		# ThumbnailImage::toHtml() already adds page= onto the end of DjVu URLs
		# So we don't need to pass it here in $query. However, the URL for the
		# zoom icon still needs it, so we make a unique query for it. See T16771
		$url = $title->getLocalURL( $query );
		if ( $page ) {
			$url = wfAppendQuery( $url, [ 'page' => $page ] );
		}
		if ( $manualthumb
			&& !isset( $frameParams['link-title'] )
			&& !isset( $frameParams['link-url'] )
			&& !isset( $frameParams['no-link'] ) ) {
			$frameParams['link-url'] = $url;
		}

		$s = "<div class=\"thumb t{$frameParams['align']}\">"
			. "<div class=\"thumbinner\" style=\"width:{$outerWidth}px;\">";

		if ( !$exists ) {
			$s .= self::makeBrokenImageLinkObj( $title, $frameParams['title'], '', '', '', $time == true );
			$zoomIcon = '';
		} elseif ( !$thumb ) {
			$s .= wfMessage( 'thumbnail_error', '' )->escaped();
			$zoomIcon = '';
		} else {
			if ( !$noscale && !$manualthumb ) {
				self::processResponsiveImages( $file, $thumb, $handlerParams );
			}
			$params = [
				'alt' => $frameParams['alt'],
				'title' => $frameParams['title'],
				'img-class' => ( isset( $frameParams['class'] ) && $frameParams['class'] !== ''
					? $frameParams['class'] . ' '
					: '' ) . 'thumbimage'
			];
			$params = self::getImageLinkMTOParams( $frameParams, $query ) + $params;
			$s .= $thumb->toHtml( $params );
			if ( isset( $frameParams['framed'] ) ) {
				$zoomIcon = "";
			} else {
				$zoomIcon = Html::rawElement( 'div', [ 'class' => 'magnify' ],
					Html::rawElement( 'a', [
						'href' => $url,
						'class' => 'internal',
						'title' => wfMessage( 'thumbnail-more' )->text() ],
						"" ) );
			}
		}
		$s .= '  <div class="thumbcaption">' . $zoomIcon . $frameParams['caption'] . "</div></div></div>";
		return str_replace( "\n", ' ', $s );
	}

	/**
	 * Process responsive images: add 1.5x and 2x subimages to the thumbnail, where
	 * applicable.
	 *
	 * @param File $file
	 * @param MediaTransformOutput $thumb
	 * @param array $hp Image parameters
	 */
	public static function processResponsiveImages( $file, $thumb, $hp ) {
		global $wgResponsiveImages;
		if ( $wgResponsiveImages && $thumb && !$thumb->isError() ) {
			$hp15 = $hp;
			$hp15['width'] = round( $hp['width'] * 1.5 );
			$hp20 = $hp;
			$hp20['width'] = $hp['width'] * 2;
			if ( isset( $hp['height'] ) ) {
				$hp15['height'] = round( $hp['height'] * 1.5 );
				$hp20['height'] = $hp['height'] * 2;
			}

			$thumb15 = $file->transform( $hp15 );
			$thumb20 = $file->transform( $hp20 );
			if ( $thumb15 && !$thumb15->isError() && $thumb15->getUrl() !== $thumb->getUrl() ) {
				$thumb->responsiveUrls['1.5'] = $thumb15->getUrl();
			}
			if ( $thumb20 && !$thumb20->isError() && $thumb20->getUrl() !== $thumb->getUrl() ) {
				$thumb->responsiveUrls['2'] = $thumb20->getUrl();
			}
		}
	}

	/**
	 * Make a "broken" link to an image
	 *
	 * @since 1.16.3
	 * @param Title $title
	 * @param string $label Link label (plain text)
	 * @param string $query Query string
	 * @param string $unused1 Unused parameter kept for b/c
	 * @param string $unused2 Unused parameter kept for b/c
	 * @param bool $time A file of a certain timestamp was requested
	 * @return string
	 */
	public static function makeBrokenImageLinkObj( $title, $label = '',
		$query = '', $unused1 = '', $unused2 = '', $time = false
	) {
		if ( !$title instanceof Title ) {
			wfWarn( __METHOD__ . ': Requires $title to be a Title object.' );
			return "<!-- ERROR -->" . htmlspecialchars( $label );
		}

		global $wgEnableUploads, $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		if ( $label == '' ) {
			$label = $title->getPrefixedText();
		}
		$encLabel = htmlspecialchars( $label );
		$currentExists = $time ? ( wfFindFile( $title ) != false ) : false;

		if ( ( $wgUploadMissingFileUrl || $wgUploadNavigationUrl || $wgEnableUploads )
			&& !$currentExists
		) {
			$redir = RepoGroup::singleton()->getLocalRepo()->checkRedirect( $title );

			if ( $redir ) {
				// We already know it's a redirect, so mark it
				// accordingly
				return self::link(
					$title,
					$encLabel,
					[ 'class' => 'mw-redirect' ],
					wfCgiToArray( $query ),
					[ 'known', 'noclasses' ]
				);
			}

			$href = self::getUploadUrl( $title, $query );

			return '<a href="' . htmlspecialchars( $href ) . '" class="new" title="' .
				htmlspecialchars( $title->getPrefixedText(), ENT_QUOTES ) . '">' .
				$encLabel . '</a>';
		}

		return self::link( $title, $encLabel, [], wfCgiToArray( $query ), [ 'known', 'noclasses' ] );
	}

	/**
	 * Get the URL to upload a certain file
	 *
	 * @since 1.16.3
	 * @param Title $destFile Title object of the file to upload
	 * @param string $query Urlencoded query string to prepend
	 * @return string Urlencoded URL
	 */
	protected static function getUploadUrl( $destFile, $query = '' ) {
		global $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		$q = 'wpDestFile=' . $destFile->getPartialURL();
		if ( $query != '' ) {
			$q .= '&' . $query;
		}

		if ( $wgUploadMissingFileUrl ) {
			return wfAppendQuery( $wgUploadMissingFileUrl, $q );
		} elseif ( $wgUploadNavigationUrl ) {
			return wfAppendQuery( $wgUploadNavigationUrl, $q );
		} else {
			$upload = SpecialPage::getTitleFor( 'Upload' );
			return $upload->getLocalURL( $q );
		}
	}

	/**
	 * Create a direct link to a given uploaded file.
	 *
	 * @since 1.16.3
	 * @param Title $title
	 * @param string $html Pre-sanitized HTML
	 * @param string $time MW timestamp of file creation time
	 * @return string HTML
	 */
	public static function makeMediaLinkObj( $title, $html = '', $time = false ) {
		$img = wfFindFile( $title, [ 'time' => $time ] );
		return self::makeMediaLinkFile( $title, $img, $html );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 * This will make a broken link if $file is false.
	 *
	 * @since 1.16.3
	 * @param Title $title
	 * @param File|bool $file File object or false
	 * @param string $html Pre-sanitized HTML
	 * @return string HTML
	 *
	 * @todo Handle invalid or missing images better.
	 */
	public static function makeMediaLinkFile( Title $title, $file, $html = '' ) {
		if ( $file && $file->exists() ) {
			$url = $file->getUrl();
			$class = 'internal';
		} else {
			$url = self::getUploadUrl( $title );
			$class = 'new';
		}

		$alt = $title->getText();
		if ( $html == '' ) {
			$html = $alt;
		}

		$ret = '';
		$attribs = [
			'href' => $url,
			'class' => $class,
			'title' => $alt
		];

		if ( !Hooks::run( 'LinkerMakeMediaLinkFile',
			[ $title, $file, &$html, &$attribs, &$ret ] ) ) {
			wfDebug( "Hook LinkerMakeMediaLinkFile changed the output of link "
				. "with url {$url} and text {$html} to {$ret}\n", true );
			return $ret;
		}

		return Html::rawElement( 'a', $attribs, $html );
	}

	/**
	 * Make a link to a special page given its name and, optionally,
	 * a message key from the link text.
	 * Usage example: Linker::specialLink( 'Recentchanges' )
	 *
	 * @since 1.16.3
	 * @param string $name
	 * @param string $key
	 * @return string
	 */
	public static function specialLink( $name, $key = '' ) {
		if ( $key == '' ) {
			$key = strtolower( $name );
		}

		return self::linkKnown( SpecialPage::getTitleFor( $name ), wfMessage( $key )->text() );
	}

	/**
	 * Make an external link
	 * @since 1.16.3. $title added in 1.21
	 * @param string $url URL to link to
	 * @param string $text Text of link
	 * @param bool $escape Do we escape the link text?
	 * @param string $linktype Type of external link. Gets added to the classes
	 * @param array $attribs Array of extra attributes to <a>
	 * @param Title|null $title Title object used for title specific link attributes
	 * @return string
	 */
	public static function makeExternalLink( $url, $text, $escape = true,
		$linktype = '', $attribs = [], $title = null
	) {
		global $wgTitle;
		$class = "external";
		if ( $linktype ) {
			$class .= " $linktype";
		}
		if ( isset( $attribs['class'] ) && $attribs['class'] ) {
			$class .= " {$attribs['class']}";
		}
		$attribs['class'] = $class;

		if ( $escape ) {
			$text = htmlspecialchars( $text );
		}

		if ( !$title ) {
			$title = $wgTitle;
		}
		$newRel = Parser::getExternalLinkRel( $url, $title );
		if ( !isset( $attribs['rel'] ) || $attribs['rel'] === '' ) {
			$attribs['rel'] = $newRel;
		} elseif ( $newRel !== '' ) {
			// Merge the rel attributes.
			$newRels = explode( ' ', $newRel );
			$oldRels = explode( ' ', $attribs['rel'] );
			$combined = array_unique( array_merge( $newRels, $oldRels ) );
			$attribs['rel'] = implode( ' ', $combined );
		}
		$link = '';
		$success = Hooks::run( 'LinkerMakeExternalLink',
			[ &$url, &$text, &$link, &$attribs, $linktype ] );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalLink changed the output of link "
				. "with url {$url} and text {$text} to {$link}\n", true );
			return $link;
		}
		$attribs['href'] = $url;
		return Html::rawElement( 'a', $attribs, $text );
	}

	/**
	 * Make user link (or user contributions for unregistered users)
	 * @param int $userId User id in database.
	 * @param string $userName User name in database.
	 * @param string $altUserName Text to display instead of the user name (optional)
	 * @return string HTML fragment
	 * @since 1.16.3. $altUserName was added in 1.19.
	 */
	public static function userLink( $userId, $userName, $altUserName = false ) {
		$classes = 'mw-userlink';
		if ( $userId == 0 ) {
			$page = SpecialPage::getTitleFor( 'Contributions', $userName );
			if ( $altUserName === false ) {
				$altUserName = IP::prettifyIP( $userName );
			}
			$classes .= ' mw-anonuserlink'; // Separate link class for anons (T45179)
		} else {
			$page = Title::makeTitle( NS_USER, $userName );
		}

		// Wrap the output with <bdi> tags for directionality isolation
		return self::link(
			$page,
			'<bdi>' . htmlspecialchars( $altUserName !== false ? $altUserName : $userName ) . '</bdi>',
			[ 'class' => $classes ]
		);
	}

	/**
	 * Generate standard user tool links (talk, contributions, block link, etc.)
	 *
	 * @since 1.16.3
	 * @param int $userId User identifier
	 * @param string $userText User name or IP address
	 * @param bool $redContribsWhenNoEdits Should the contributions link be
	 *   red if the user has no edits?
	 * @param int $flags Customisation flags (e.g. Linker::TOOL_LINKS_NOBLOCK
	 *   and Linker::TOOL_LINKS_EMAIL).
	 * @param int $edits User edit count (optional, for performance)
	 * @return string HTML fragment
	 */
	public static function userToolLinks(
		$userId, $userText, $redContribsWhenNoEdits = false, $flags = 0, $edits = null
	) {
		global $wgUser, $wgDisableAnonTalk, $wgLang;
		$talkable = !( $wgDisableAnonTalk && 0 == $userId );
		$blockable = !( $flags & self::TOOL_LINKS_NOBLOCK );
		$addEmailLink = $flags & self::TOOL_LINKS_EMAIL && $userId;

		$items = [];
		if ( $talkable ) {
			$items[] = self::userTalkLink( $userId, $userText );
		}
		if ( $userId ) {
			// check if the user has an edit
			$attribs = [];
			$attribs['class'] = 'mw-usertoollinks-contribs';
			if ( $redContribsWhenNoEdits ) {
				if ( intval( $edits ) === 0 && $edits !== 0 ) {
					$user = User::newFromId( $userId );
					$edits = $user->getEditCount();
				}
				if ( $edits === 0 ) {
					$attribs['class'] .= ' new';
				}
			}
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $userText );

			$items[] = self::link( $contribsPage, wfMessage( 'contribslink' )->escaped(), $attribs );
		}
		if ( $blockable && $wgUser->isAllowed( 'block' ) ) {
			$items[] = self::blockLink( $userId, $userText );
		}

		if ( $addEmailLink && $wgUser->canSendEmail() ) {
			$items[] = self::emailLink( $userId, $userText );
		}

		Hooks::run( 'UserToolLinksEdit', [ $userId, $userText, &$items ] );

		if ( $items ) {
			return wfMessage( 'word-separator' )->escaped()
				. '<span class="mw-usertoollinks">'
				. wfMessage( 'parentheses' )->rawParams( $wgLang->pipeList( $items ) )->escaped()
				. '</span>';
		} else {
			return '';
		}
	}

	/**
	 * Alias for userToolLinks( $userId, $userText, true );
	 * @since 1.16.3
	 * @param int $userId User identifier
	 * @param string $userText User name or IP address
	 * @param int $edits User edit count (optional, for performance)
	 * @return string
	 */
	public static function userToolLinksRedContribs( $userId, $userText, $edits = null ) {
		return self::userToolLinks( $userId, $userText, true, 0, $edits );
	}

	/**
	 * @since 1.16.3
	 * @param int $userId User id in database.
	 * @param string $userText User name in database.
	 * @return string HTML fragment with user talk link
	 */
	public static function userTalkLink( $userId, $userText ) {
		$userTalkPage = Title::makeTitle( NS_USER_TALK, $userText );
		$moreLinkAttribs['class'] = 'mw-usertoollinks-talk';
		$userTalkLink = self::link( $userTalkPage,
						wfMessage( 'talkpagelinktext' )->escaped(),
						$moreLinkAttribs );
		return $userTalkLink;
	}

	/**
	 * @since 1.16.3
	 * @param int $userId Userid
	 * @param string $userText User name in database.
	 * @return string HTML fragment with block link
	 */
	public static function blockLink( $userId, $userText ) {
		$blockPage = SpecialPage::getTitleFor( 'Block', $userText );
		$moreLinkAttribs['class'] = 'mw-usertoollinks-block';
		$blockLink = self::link( $blockPage,
					 wfMessage( 'blocklink' )->escaped(),
					 $moreLinkAttribs );
		return $blockLink;
	}

	/**
	 * @param int $userId Userid
	 * @param string $userText User name in database.
	 * @return string HTML fragment with e-mail user link
	 */
	public static function emailLink( $userId, $userText ) {
		$emailPage = SpecialPage::getTitleFor( 'Emailuser', $userText );
		$moreLinkAttribs['class'] = 'mw-usertoollinks-mail';
		$emailLink = self::link( $emailPage,
					 wfMessage( 'emaillink' )->escaped(),
					 $moreLinkAttribs );
		return $emailLink;
	}

	/**
	 * Generate a user link if the current user is allowed to view it
	 * @since 1.16.3
	 * @param Revision $rev
	 * @param bool $isPublic Show only if all users can see it
	 * @return string HTML fragment
	 */
	public static function revUserLink( $rev, $isPublic = false ) {
		if ( $rev->isDeleted( Revision::DELETED_USER ) && $isPublic ) {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		} elseif ( $rev->userCan( Revision::DELETED_USER ) ) {
			$link = self::userLink( $rev->getUser( Revision::FOR_THIS_USER ),
				$rev->getUserText( Revision::FOR_THIS_USER ) );
		} else {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		}
		if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @since 1.16.3
	 * @param Revision $rev
	 * @param bool $isPublic Show only if all users can see it
	 * @return string HTML
	 */
	public static function revUserTools( $rev, $isPublic = false ) {
		if ( $rev->isDeleted( Revision::DELETED_USER ) && $isPublic ) {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		} elseif ( $rev->userCan( Revision::DELETED_USER ) ) {
			$userId = $rev->getUser( Revision::FOR_THIS_USER );
			$userText = $rev->getUserText( Revision::FOR_THIS_USER );
			$link = self::userLink( $userId, $userText )
				. self::userToolLinks( $userId, $userText );
		} else {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		}
		if ( $rev->isDeleted( Revision::DELETED_USER ) ) {
			return ' <span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * This function is called by all recent changes variants, by the page history,
	 * and by the user contributions list. It is responsible for formatting edit
	 * summaries. It escapes any HTML in the summary, but adds some CSS to format
	 * auto-generated comments (from section editing) and formats [[wikilinks]].
	 *
	 * @author Erik Moeller <moeller@scireview.de>
	 * @since 1.16.3. $wikiId added in 1.26
	 *
	 * Note: there's not always a title to pass to this function.
	 * Since you can't set a default parameter for a reference, I've turned it
	 * temporarily to a value pass. Should be adjusted further. --brion
	 *
	 * @param string $comment
	 * @param Title|null $title Title object (to generate link to the section in autocomment)
	 *  or null
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id (as used by WikiMap) of the wiki to generate links to.
	 *  For use with external changes.
	 *
	 * @return mixed|string
	 */
	public static function formatComment(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		# Sanitize text a bit:
		$comment = str_replace( "\n", " ", $comment );
		# Allow HTML entities (for T15815)
		$comment = Sanitizer::escapeHtmlAllowEntities( $comment );

		# Render autocomments and make links:
		$comment = self::formatAutocomments( $comment, $title, $local, $wikiId );
		$comment = self::formatLinksInComment( $comment, $title, $local, $wikiId );

		return $comment;
	}

	/**
	 * Converts autogenerated comments in edit summaries into section links.
	 *
	 * The pattern for autogen comments is / * foo * /, which makes for
	 * some nasty regex.
	 * We look for all comments, match any text before and after the comment,
	 * add a separator where needed and format the comment itself with CSS
	 * Called by Linker::formatComment.
	 *
	 * @param string $comment Comment text
	 * @param Title|null $title An optional title object used to links to sections
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 *
	 * @return string Formatted comment (wikitext)
	 */
	private static function formatAutocomments(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		// @todo $append here is something of a hack to preserve the status
		// quo. Someone who knows more about bidi and such should decide
		// (1) what sane rendering even *is* for an LTR edit summary on an RTL
		// wiki, both when autocomments exist and when they don't, and
		// (2) what markup will make that actually happen.
		$append = '';
		$comment = preg_replace_callback(
			// To detect the presence of content before or after the
			// auto-comment, we use capturing groups inside optional zero-width
			// assertions. But older versions of PCRE can't directly make
			// zero-width assertions optional, so wrap them in a non-capturing
			// group.
			'!(?:(?<=(.)))?/\*\s*(.*?)\s*\*/(?:(?=(.)))?!',
			function ( $match ) use ( $title, $local, $wikiId, &$append ) {
				global $wgLang;

				// Ensure all match positions are defined
				$match += [ '', '', '', '' ];

				$pre = $match[1] !== '';
				$auto = $match[2];
				$post = $match[3] !== '';
				$comment = null;

				Hooks::run(
					'FormatAutocomments',
					[ &$comment, $pre, $auto, $post, $title, $local, $wikiId ]
				);

				if ( $comment === null ) {
					$link = '';
					if ( $title ) {
						$section = $auto;
						# Remove links that a user may have manually put in the autosummary
						# This could be improved by copying as much of Parser::stripSectionName as desired.
						$section = str_replace( '[[:', '', $section );
						$section = str_replace( '[[', '', $section );
						$section = str_replace( ']]', '', $section );

						$section = Sanitizer::normalizeSectionNameWhitespace( $section ); # T24784
						if ( $local ) {
							$sectionTitle = Title::newFromText( '#' . $section );
						} else {
							$sectionTitle = Title::makeTitleSafe( $title->getNamespace(),
								$title->getDBkey(), $section );
						}
						if ( $sectionTitle ) {
							$link = Linker::makeCommentLink( $sectionTitle, $wgLang->getArrow(), $wikiId, 'noclasses' );
						} else {
							$link = '';
						}
					}
					if ( $pre ) {
						# written summary $presep autocomment (summary /* section */)
						$pre = wfMessage( 'autocomment-prefix' )->inContentLanguage()->escaped();
					}
					if ( $post ) {
						# autocomment $postsep written summary (/* section */ summary)
						$auto .= wfMessage( 'colon-separator' )->inContentLanguage()->escaped();
					}
					$auto = '<span class="autocomment">' . $auto . '</span>';
					$comment = $pre . $link . $wgLang->getDirMark()
						. '<span dir="auto">' . $auto;
					$append .= '</span>';
				}
				return $comment;
			},
			$comment
		);
		return $comment . $append;
	}

	/**
	 * Formats wiki links and media links in text; all other wiki formatting
	 * is ignored
	 *
	 * @since 1.16.3. $wikiId added in 1.26
	 * @todo FIXME: Doesn't handle sub-links as in image thumb texts like the main parser
	 *
	 * @param string $comment Text to format links in. WARNING! Since the output of this
	 *	function is html, $comment must be sanitized for use as html. You probably want
	 *	to pass $comment through Sanitizer::escapeHtmlAllowEntities() before calling
	 *	this function.
	 * @param Title|null $title An optional title object used to links to sections
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 *
	 * @return string
	 */
	public static function formatLinksInComment(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		return preg_replace_callback(
			'/
				\[\[
				:? # ignore optional leading colon
				([^\]|]+) # 1. link target; page names cannot include ] or |
				(?:\|
					# 2. link text
					# Stop matching at ]] without relying on backtracking.
					((?:]?[^\]])*+)
				)?
				\]\]
				([^[]*) # 3. link trail (the text up until the next link)
			/x',
			function ( $match ) use ( $title, $local, $wikiId ) {
				global $wgContLang;

				$medians = '(?:' . preg_quote( MWNamespace::getCanonicalName( NS_MEDIA ), '/' ) . '|';
				$medians .= preg_quote( $wgContLang->getNsText( NS_MEDIA ), '/' ) . '):';

				$comment = $match[0];

				# fix up urlencoded title texts (copied from Parser::replaceInternalLinks)
				if ( strpos( $match[1], '%' ) !== false ) {
					$match[1] = strtr(
						rawurldecode( $match[1] ),
						[ '<' => '&lt;', '>' => '&gt;' ]
					);
				}

				# Handle link renaming [[foo|text]] will show link as "text"
				if ( $match[2] != "" ) {
					$text = $match[2];
				} else {
					$text = $match[1];
				}
				$submatch = [];
				$thelink = null;
				if ( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
					# Media link; trail not supported.
					$linkRegexp = '/\[\[(.*?)\]\]/';
					$title = Title::makeTitleSafe( NS_FILE, $submatch[1] );
					if ( $title ) {
						$thelink = Linker::makeMediaLinkObj( $title, $text );
					}
				} else {
					# Other kind of link
					# Make sure its target is non-empty
					if ( isset( $match[1][0] ) && $match[1][0] == ':' ) {
						$match[1] = substr( $match[1], 1 );
					}
					if ( $match[1] !== false && $match[1] !== '' ) {
						if ( preg_match( $wgContLang->linkTrail(), $match[3], $submatch ) ) {
							$trail = $submatch[1];
						} else {
							$trail = "";
						}
						$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
						list( $inside, $trail ) = Linker::splitTrail( $trail );

						$linkText = $text;
						$linkTarget = Linker::normalizeSubpageLink( $title, $match[1], $linkText );

						$target = Title::newFromText( $linkTarget );
						if ( $target ) {
							if ( $target->getText() == '' && !$target->isExternal()
								&& !$local && $title
							) {
								$newTarget = clone $title;
								$newTarget->setFragment( '#' . $target->getFragment() );
								$target = $newTarget;
							}

							$thelink = Linker::makeCommentLink( $target, $linkText . $inside, $wikiId ) . $trail;
						}
					}
				}
				if ( $thelink ) {
					// If the link is still valid, go ahead and replace it in!
					$comment = preg_replace(
						$linkRegexp,
						StringUtils::escapeRegexReplacement( $thelink ),
						$comment,
						1
					);
				}

				return $comment;
			},
			$comment
		);
	}

	/**
	 * Generates a link to the given Title
	 *
	 * @note This is only public for technical reasons. It's not intended for use outside Linker.
	 *
	 * @param Title $title
	 * @param string $text
	 * @param string|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 * @param string|string[] $options See the $options parameter in Linker::link.
	 *
	 * @return string HTML link
	 */
	public static function makeCommentLink(
		Title $title, $text, $wikiId = null, $options = []
	) {
		if ( $wikiId !== null && !$title->isExternal() ) {
			$link = Linker::makeExternalLink(
				WikiMap::getForeignURL(
					$wikiId,
					$title->getPrefixedText(),
					$title->getFragment()
				),
				$text,
				/* escape = */ false // Already escaped
			);
		} else {
			$link = Linker::link( $title, $text, [], [], $options );
		}

		return $link;
	}

	/**
	 * @param Title $contextTitle
	 * @param string $target
	 * @param string $text
	 * @return string
	 */
	public static function normalizeSubpageLink( $contextTitle, $target, &$text ) {
		# Valid link forms:
		# Foobar -- normal
		# :Foobar -- override special treatment of prefix (images, language links)
		# /Foobar -- convert to CurrentPage/Foobar
		# /Foobar/ -- convert to CurrentPage/Foobar, strip the initial and final / from text
		# ../ -- convert to CurrentPage, from CurrentPage/CurrentSubPage
		# ../Foobar -- convert to CurrentPage/Foobar,
		#              (from CurrentPage/CurrentSubPage)
		# ../Foobar/ -- convert to CurrentPage/Foobar, use 'Foobar' as text
		#              (from CurrentPage/CurrentSubPage)

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
			# T9425
			$target = trim( $target );
			# Look at the first character
			if ( $target != '' && $target[0] === '/' ) {
				# / at end means we don't want the slash to be shown
				$m = [];
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
					$exploded = explode( '/', $contextTitle->getPrefixedText() );
					if ( count( $exploded ) > $dotdotcount ) { # not allowed to go below top level page
						$ret = implode( '/', array_slice( $exploded, 0, -$dotdotcount ) );
						# / at the end means don't show full path
						if ( substr( $nodotdot, -1, 1 ) === '/' ) {
							$nodotdot = rtrim( $nodotdot, '/' );
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

		return $ret;
	}

	/**
	 * Wrap a comment in standard punctuation and formatting if
	 * it's non-empty, otherwise return empty string.
	 *
	 * @since 1.16.3. $wikiId added in 1.26
	 * @param string $comment
	 * @param Title|null $title Title object (to generate link to section in autocomment) or null
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id (as used by WikiMap) of the wiki to generate links to.
	 *  For use with external changes.
	 *
	 * @return string
	 */
	public static function commentBlock(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		// '*' used to be the comment inserted by the software way back
		// in antiquity in case none was provided, here for backwards
		// compatibility, acc. to brion -ævar
		if ( $comment == '' || $comment == '*' ) {
			return '';
		} else {
			$formatted = self::formatComment( $comment, $title, $local, $wikiId );
			$formatted = wfMessage( 'parentheses' )->rawParams( $formatted )->escaped();
			return " <span class=\"comment\">$formatted</span>";
		}
	}

	/**
	 * Wrap and format the given revision's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @since 1.16.3
	 * @param Revision $rev
	 * @param bool $local Whether section links should refer to local page
	 * @param bool $isPublic Show only if all users can see it
	 * @return string HTML fragment
	 */
	public static function revComment( Revision $rev, $local = false, $isPublic = false ) {
		if ( $rev->getComment( Revision::RAW ) == "" ) {
			return "";
		}
		if ( $rev->isDeleted( Revision::DELETED_COMMENT ) && $isPublic ) {
			$block = " <span class=\"comment\">" . wfMessage( 'rev-deleted-comment' )->escaped() . "</span>";
		} elseif ( $rev->userCan( Revision::DELETED_COMMENT ) ) {
			$block = self::commentBlock( $rev->getComment( Revision::FOR_THIS_USER ),
				$rev->getTitle(), $local );
		} else {
			$block = " <span class=\"comment\">" . wfMessage( 'rev-deleted-comment' )->escaped() . "</span>";
		}
		if ( $rev->isDeleted( Revision::DELETED_COMMENT ) ) {
			return " <span class=\"history-deleted\">$block</span>";
		}
		return $block;
	}

	/**
	 * @since 1.16.3
	 * @param int $size
	 * @return string
	 */
	public static function formatRevisionSize( $size ) {
		if ( $size == 0 ) {
			$stxt = wfMessage( 'historyempty' )->escaped();
		} else {
			$stxt = wfMessage( 'nbytes' )->numParams( $size )->escaped();
			$stxt = wfMessage( 'parentheses' )->rawParams( $stxt )->escaped();
		}
		return "<span class=\"history-size\">$stxt</span>";
	}

	/**
	 * Add another level to the Table of Contents
	 *
	 * @since 1.16.3
	 * @return string
	 */
	public static function tocIndent() {
		return "\n<ul>";
	}

	/**
	 * Finish one or more sublevels on the Table of Contents
	 *
	 * @since 1.16.3
	 * @param int $level
	 * @return string
	 */
	public static function tocUnindent( $level ) {
		return "</li>\n" . str_repeat( "</ul>\n</li>\n", $level > 0 ? $level : 0 );
	}

	/**
	 * parameter level defines if we are on an indentation level
	 *
	 * @since 1.16.3
	 * @param string $anchor
	 * @param string $tocline
	 * @param string $tocnumber
	 * @param string $level
	 * @param string|bool $sectionIndex
	 * @return string
	 */
	public static function tocLine( $anchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
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
	 * @since 1.16.3
	 * @return string
	 */
	public static function tocLineEnd() {
		return "</li>\n";
	}

	/**
	 * Wraps the TOC in a table and provides the hide/collapse javascript.
	 *
	 * @since 1.16.3
	 * @param string $toc Html of the Table Of Contents
	 * @param string|Language|bool $lang Language for the toc title, defaults to user language
	 * @return string Full html of the TOC
	 */
	public static function tocList( $toc, $lang = false ) {
		$lang = wfGetLangObj( $lang );
		$title = wfMessage( 'toc' )->inLanguage( $lang )->escaped();

		return '<div id="toc" class="toc">'
			. '<div id="toctitle" class="toctitle"><h2>' . $title . "</h2></div>\n"
			. $toc
			. "</ul>\n</div>\n";
	}

	/**
	 * Generate a table of contents from a section tree.
	 *
	 * @since 1.16.3. $lang added in 1.17
	 * @param array $tree Return value of ParserOutput::getSections()
	 * @param string|Language|bool $lang Language for the toc title, defaults to user language
	 * @return string HTML fragment
	 */
	public static function generateTOC( $tree, $lang = false ) {
		$toc = '';
		$lastLevel = 0;
		foreach ( $tree as $section ) {
			if ( $section['toclevel'] > $lastLevel ) {
				$toc .= self::tocIndent();
			} elseif ( $section['toclevel'] < $lastLevel ) {
				$toc .= self::tocUnindent(
					$lastLevel - $section['toclevel'] );
			} else {
				$toc .= self::tocLineEnd();
			}

			$toc .= self::tocLine( $section['anchor'],
				$section['line'], $section['number'],
				$section['toclevel'], $section['index'] );
			$lastLevel = $section['toclevel'];
		}
		$toc .= self::tocLineEnd();
		return self::tocList( $toc, $lang );
	}

	/**
	 * Create a headline for content
	 *
	 * @since 1.16.3
	 * @param int $level The level of the headline (1-6)
	 * @param string $attribs Any attributes for the headline, starting with
	 *   a space and ending with '>'
	 *   This *must* be at least '>' for no attribs
	 * @param string $anchor The anchor to give the headline (the bit after the #)
	 * @param string $html Html for the text of the header
	 * @param string $link HTML to add for the section edit link
	 * @param bool|string $legacyAnchor A second, optional anchor to give for
	 *   backward compatibility (false to omit)
	 *
	 * @return string HTML headline
	 */
	public static function makeHeadline( $level, $attribs, $anchor, $html,
		$link, $legacyAnchor = false
	) {
		$ret = "<h$level$attribs"
			. "<span class=\"mw-headline\" id=\"$anchor\">$html</span>"
			. $link
			. "</h$level>";
		if ( $legacyAnchor !== false ) {
			$ret = "<div id=\"$legacyAnchor\"></div>$ret";
		}
		return $ret;
	}

	/**
	 * Split a link trail, return the "inside" portion and the remainder of the trail
	 * as a two-element array
	 * @param string $trail
	 * @return array
	 */
	static function splitTrail( $trail ) {
		global $wgContLang;
		$regex = $wgContLang->linkTrail();
		$inside = '';
		if ( $trail !== '' ) {
			$m = [];
			if ( preg_match( $regex, $trail, $m ) ) {
				$inside = $m[1];
				$trail = $m[2];
			}
		}
		return [ $inside, $trail ];
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
	 * If the option verify is set this function will return the link only in case the
	 * revision can be reverted. Please note that due to performance limitations
	 * it might be assumed that a user isn't the only contributor of a page while
	 * (s)he is, which will lead to useless rollback links. Furthermore this wont
	 * work if $wgShowRollbackEditCount is disabled, so this can only function
	 * as an additional check.
	 *
	 * If the option noBrackets is set the rollback link wont be enclosed in "[]".
	 *
	 * @since 1.16.3. $context added in 1.20. $options added in 1.21
	 *
	 * @param Revision $rev
	 * @param IContextSource $context Context to use or null for the main context.
	 * @param array $options
	 * @return string
	 */
	public static function generateRollback( $rev, IContextSource $context = null,
		$options = [ 'verify' ]
	) {
		if ( $context === null ) {
			$context = RequestContext::getMain();
		}

		$editCount = false;
		if ( in_array( 'verify', $options, true ) ) {
			$editCount = self::getRollbackEditCount( $rev, true );
			if ( $editCount === false ) {
				return '';
			}
		}

		$inner = self::buildRollbackLink( $rev, $context, $editCount );

		if ( !in_array( 'noBrackets', $options, true ) ) {
			$inner = $context->msg( 'brackets' )->rawParams( $inner )->escaped();
		}

		return '<span class="mw-rollback-link">' . $inner . '</span>';
	}

	/**
	 * This function will return the number of revisions which a rollback
	 * would revert and, if $verify is set it will verify that a revision
	 * can be reverted (that the user isn't the only contributor and the
	 * revision we might rollback to isn't deleted). These checks can only
	 * function as an additional check as this function only checks against
	 * the last $wgShowRollbackEditCount edits.
	 *
	 * Returns null if $wgShowRollbackEditCount is disabled or false if $verify
	 * is set and the user is the only contributor of the page.
	 *
	 * @param Revision $rev
	 * @param bool $verify Try to verify that this revision can really be rolled back
	 * @return int|bool|null
	 */
	public static function getRollbackEditCount( $rev, $verify ) {
		global $wgShowRollbackEditCount;
		if ( !is_int( $wgShowRollbackEditCount ) || !$wgShowRollbackEditCount > 0 ) {
			// Nothing has happened, indicate this by returning 'null'
			return null;
		}

		$dbr = wfGetDB( DB_REPLICA );

		// Up to the value of $wgShowRollbackEditCount revisions are counted
		$res = $dbr->select(
			'revision',
			[ 'rev_user_text', 'rev_deleted' ],
			// $rev->getPage() returns null sometimes
			[ 'rev_page' => $rev->getTitle()->getArticleID() ],
			__METHOD__,
			[
				'USE INDEX' => [ 'revision' => 'page_timestamp' ],
				'ORDER BY' => 'rev_timestamp DESC',
				'LIMIT' => $wgShowRollbackEditCount + 1
			]
		);

		$editCount = 0;
		$moreRevs = false;
		foreach ( $res as $row ) {
			if ( $rev->getUserText( Revision::RAW ) != $row->rev_user_text ) {
				if ( $verify &&
					( $row->rev_deleted & Revision::DELETED_TEXT
						|| $row->rev_deleted & Revision::DELETED_USER
				) ) {
					// If the user or the text of the revision we might rollback
					// to is deleted in some way we can't rollback. Similar to
					// the sanity checks in WikiPage::commitRollback.
					return false;
				}
				$moreRevs = true;
				break;
			}
			$editCount++;
		}

		if ( $verify && $editCount <= $wgShowRollbackEditCount && !$moreRevs ) {
			// We didn't find at least $wgShowRollbackEditCount revisions made by the current user
			// and there weren't any other revisions. That means that the current user is the only
			// editor, so we can't rollback
			return false;
		}
		return $editCount;
	}

	/**
	 * Build a raw rollback link, useful for collections of "tool" links
	 *
	 * @since 1.16.3. $context added in 1.20. $editCount added in 1.21
	 * @param Revision $rev
	 * @param IContextSource|null $context Context to use or null for the main context.
	 * @param int $editCount Number of edits that would be reverted
	 * @return string HTML fragment
	 */
	public static function buildRollbackLink( $rev, IContextSource $context = null,
		$editCount = false
	) {
		global $wgShowRollbackEditCount, $wgMiserMode;

		// To config which pages are affected by miser mode
		$disableRollbackEditCountSpecialPage = [ 'Recentchanges', 'Watchlist' ];

		if ( $context === null ) {
			$context = RequestContext::getMain();
		}

		$title = $rev->getTitle();
		$query = [
			'action' => 'rollback',
			'from' => $rev->getUserText(),
			'token' => $context->getUser()->getEditToken( 'rollback' ),
		];
		$attrs = [
			'data-mw' => 'interface',
			'title' => $context->msg( 'tooltip-rollback' )->text(),
		];
		$options = [ 'known', 'noclasses' ];

		if ( $context->getRequest()->getBool( 'bot' ) ) {
			$query['bot'] = '1';
			$query['hidediff'] = '1'; // T17999
		}

		$disableRollbackEditCount = false;
		if ( $wgMiserMode ) {
			foreach ( $disableRollbackEditCountSpecialPage as $specialPage ) {
				if ( $context->getTitle()->isSpecial( $specialPage ) ) {
					$disableRollbackEditCount = true;
					break;
				}
			}
		}

		if ( !$disableRollbackEditCount
			&& is_int( $wgShowRollbackEditCount )
			&& $wgShowRollbackEditCount > 0
		) {
			if ( !is_numeric( $editCount ) ) {
				$editCount = self::getRollbackEditCount( $rev, false );
			}

			if ( $editCount > $wgShowRollbackEditCount ) {
				$html = $context->msg( 'rollbacklinkcount-morethan' )
					->numParams( $wgShowRollbackEditCount )->parse();
			} else {
				$html = $context->msg( 'rollbacklinkcount' )->numParams( $editCount )->parse();
			}

			return self::link( $title, $html, $attrs, $query, $options );
		} else {
			$html = $context->msg( 'rollbacklink' )->escaped();
			return self::link( $title, $html, $attrs, $query, $options );
		}
	}

	/**
	 * @deprecated since 1.28, use TemplatesOnThisPageFormatter directly
	 *
	 * Returns HTML for the "templates used on this page" list.
	 *
	 * Make an HTML list of templates, and then add a "More..." link at
	 * the bottom. If $more is null, do not add a "More..." link. If $more
	 * is a Title, make a link to that title and use it. If $more is a string,
	 * directly paste it in as the link (escaping needs to be done manually).
	 * Finally, if $more is a Message, call toString().
	 *
	 * @since 1.16.3. $more added in 1.21
	 * @param Title[] $templates Array of templates
	 * @param bool $preview Whether this is for a preview
	 * @param bool $section Whether this is for a section edit
	 * @param Title|Message|string|null $more An escaped link for "More..." of the templates
	 * @return string HTML output
	 */
	public static function formatTemplates( $templates, $preview = false,
		$section = false, $more = null
	) {
		wfDeprecated( __METHOD__, '1.28' );

		$type = false;
		if ( $preview ) {
			$type = 'preview';
		} elseif ( $section ) {
			$type = 'section';
		}

		if ( $more instanceof Message ) {
			$more = $more->toString();
		}

		$formatter = new TemplatesOnThisPageFormatter(
			RequestContext::getMain(),
			MediaWikiServices::getInstance()->getLinkRenderer()
		);
		return $formatter->format( $templates, $type, $more );
	}

	/**
	 * Returns HTML for the "hidden categories on this page" list.
	 *
	 * @since 1.16.3
	 * @param array $hiddencats Array of hidden categories from Article::getHiddenCategories
	 *   or similar
	 * @return string HTML output
	 */
	public static function formatHiddenCategories( $hiddencats ) {

		$outText = '';
		if ( count( $hiddencats ) > 0 ) {
			# Construct the HTML
			$outText = '<div class="mw-hiddenCategoriesExplanation">';
			$outText .= wfMessage( 'hiddencategories' )->numParams( count( $hiddencats ) )->parseAsBlock();
			$outText .= "</div><ul>\n";

			foreach ( $hiddencats as $titleObj ) {
				# If it's hidden, it must exist - no need to check with a LinkBatch
				$outText .= '<li>'
					. self::link( $titleObj, null, [], [], 'known' )
					. "</li>\n";
			}
			$outText .= '</ul>';
		}
		return $outText;
	}

	/**
	 * @deprecated since 1.28, use Language::formatSize() directly
	 *
	 * Format a size in bytes for output, using an appropriate
	 * unit (B, KB, MB or GB) according to the magnitude in question
	 *
	 * @since 1.16.3
	 * @param int $size Size to format
	 * @return string
	 */
	public static function formatSize( $size ) {
		wfDeprecated( __METHOD__, '1.28' );

		global $wgLang;
		return htmlspecialchars( $wgLang->formatSize( $size ) );
	}

	/**
	 * Given the id of an interface element, constructs the appropriate title
	 * attribute from the system messages.  (Note, this is usually the id but
	 * isn't always, because sometimes the accesskey needs to go on a different
	 * element than the id, for reverse-compatibility, etc.)
	 *
	 * @since 1.16.3 $msgParams added in 1.27
	 * @param string $name Id of the element, minus prefixes.
	 * @param string|null $options Null or the string 'withaccess' to add an access-
	 *   key hint
	 * @param array $msgParams Parameters to pass to the message
	 *
	 * @return string Contents of the title attribute (which you must HTML-
	 *   escape), or false for no title attribute
	 */
	public static function titleAttrib( $name, $options = null, array $msgParams = [] ) {
		$message = wfMessage( "tooltip-$name", $msgParams );
		if ( !$message->exists() ) {
			$tooltip = false;
		} else {
			$tooltip = $message->text();
			# Compatibility: formerly some tooltips had [alt-.] hardcoded
			$tooltip = preg_replace( "/ ?\[alt-.\]$/", '', $tooltip );
			# Message equal to '-' means suppress it.
			if ( $tooltip == '-' ) {
				$tooltip = false;
			}
		}

		if ( $options == 'withaccess' ) {
			$accesskey = self::accesskey( $name );
			if ( $accesskey !== false ) {
				// Should be build the same as in jquery.accessKeyLabel.js
				if ( $tooltip === false || $tooltip === '' ) {
					$tooltip = wfMessage( 'brackets', $accesskey )->text();
				} else {
					$tooltip .= wfMessage( 'word-separator' )->text();
					$tooltip .= wfMessage( 'brackets', $accesskey )->text();
				}
			}
		}

		return $tooltip;
	}

	public static $accesskeycache;

	/**
	 * Given the id of an interface element, constructs the appropriate
	 * accesskey attribute from the system messages.  (Note, this is usually
	 * the id but isn't always, because sometimes the accesskey needs to go on
	 * a different element than the id, for reverse-compatibility, etc.)
	 *
	 * @since 1.16.3
	 * @param string $name Id of the element, minus prefixes.
	 * @return string Contents of the accesskey attribute (which you must HTML-
	 *   escape), or false for no accesskey attribute
	 */
	public static function accesskey( $name ) {
		if ( isset( self::$accesskeycache[$name] ) ) {
			return self::$accesskeycache[$name];
		}

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

		self::$accesskeycache[$name] = $accesskey;
		return self::$accesskeycache[$name];
	}

	/**
	 * Get a revision-deletion link, or disabled link, or nothing, depending
	 * on user permissions & the settings on the revision.
	 *
	 * Will use forward-compatible revision ID in the Special:RevDelete link
	 * if possible, otherwise the timestamp-based ID which may break after
	 * undeletion.
	 *
	 * @param User $user
	 * @param Revision $rev
	 * @param Title $title
	 * @return string HTML fragment
	 */
	public static function getRevDeleteLink( User $user, Revision $rev, Title $title ) {
		$canHide = $user->isAllowed( 'deleterevision' );
		if ( !$canHide && !( $rev->getVisibility() && $user->isAllowed( 'deletedhistory' ) ) ) {
			return '';
		}

		if ( !$rev->userCan( Revision::DELETED_RESTRICTED, $user ) ) {
			return Linker::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
		} else {
			if ( $rev->getId() ) {
				// RevDelete links using revision ID are stable across
				// page deletion and undeletion; use when possible.
				$query = [
					'type' => 'revision',
					'target' => $title->getPrefixedDBkey(),
					'ids' => $rev->getId()
				];
			} else {
				// Older deleted entries didn't save a revision ID.
				// We have to refer to these by timestamp, ick!
				$query = [
					'type' => 'archive',
					'target' => $title->getPrefixedDBkey(),
					'ids' => $rev->getTimestamp()
				];
			}
			return Linker::revDeleteLink( $query,
				$rev->isDeleted( Revision::DELETED_RESTRICTED ), $canHide );
		}
	}

	/**
	 * Creates a (show/hide) link for deleting revisions/log entries
	 *
	 * @param array $query Query parameters to be passed to link()
	 * @param bool $restricted Set to true to use a "<strong>" instead of a "<span>"
	 * @param bool $delete Set to true to use (show/hide) rather than (show)
	 *
	 * @return string HTML "<a>" link to Special:Revisiondelete, wrapped in a
	 * span to allow for customization of appearance with CSS
	 */
	public static function revDeleteLink( $query = [], $restricted = false, $delete = true ) {
		$sp = SpecialPage::getTitleFor( 'Revisiondelete' );
		$msgKey = $delete ? 'rev-delundel' : 'rev-showdeleted';
		$html = wfMessage( $msgKey )->escaped();
		$tag = $restricted ? 'strong' : 'span';
		$link = self::link( $sp, $html, [], $query, [ 'known', 'noclasses' ] );
		return Xml::tags(
			$tag,
			[ 'class' => 'mw-revdelundel-link' ],
			wfMessage( 'parentheses' )->rawParams( $link )->escaped()
		);
	}

	/**
	 * Creates a dead (show/hide) link for deleting revisions/log entries
	 *
	 * @since 1.16.3
	 * @param bool $delete Set to true to use (show/hide) rather than (show)
	 *
	 * @return string HTML text wrapped in a span to allow for customization
	 * of appearance with CSS
	 */
	public static function revDeleteLinkDisabled( $delete = true ) {
		$msgKey = $delete ? 'rev-delundel' : 'rev-showdeleted';
		$html = wfMessage( $msgKey )->escaped();
		$htmlParentheses = wfMessage( 'parentheses' )->rawParams( $html )->escaped();
		return Xml::tags( 'span', [ 'class' => 'mw-revdelundel-link' ], $htmlParentheses );
	}

	/* Deprecated methods */

	/**
	 * Returns the attributes for the tooltip and access key.
	 *
	 * @since 1.16.3. $msgParams introduced in 1.27
	 * @param string $name
	 * @param array $msgParams Params for constructing the message
	 *
	 * @return array
	 */
	public static function tooltipAndAccesskeyAttribs( $name, array $msgParams = [] ) {
		# @todo FIXME: If Sanitizer::expandAttributes() treated "false" as "output
		# no attribute" instead of "output '' as value for attribute", this
		# would be three lines.
		$attribs = [
			'title' => self::titleAttrib( $name, 'withaccess', $msgParams ),
			'accesskey' => self::accesskey( $name )
		];
		if ( $attribs['title'] === false ) {
			unset( $attribs['title'] );
		}
		if ( $attribs['accesskey'] === false ) {
			unset( $attribs['accesskey'] );
		}
		return $attribs;
	}

	/**
	 * Returns raw bits of HTML, use titleAttrib()
	 * @since 1.16.3
	 * @param string $name
	 * @param array|null $options
	 * @return null|string
	 */
	public static function tooltip( $name, $options = null ) {
		# @todo FIXME: If Sanitizer::expandAttributes() treated "false" as "output
		# no attribute" instead of "output '' as value for attribute", this
		# would be two lines.
		$tooltip = self::titleAttrib( $name, $options );
		if ( $tooltip === false ) {
			return '';
		}
		return Xml::expandAttributes( [
			'title' => $tooltip
		] );
	}

}
