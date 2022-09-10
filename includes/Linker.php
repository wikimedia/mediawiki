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

use HtmlFormatter\HtmlFormatter;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\SelectQueryBuilder;

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
	public const TOOL_LINKS_NOBLOCK = 1;
	public const TOOL_LINKS_EMAIL = 2;

	/**
	 * This function returns an HTML link to the given target.  It serves a few
	 * purposes:
	 *   1) If $target is a LinkTarget, the correct URL to link to will be figured
	 *      out automatically.
	 *   2) It automatically adds the usual classes for various types of link
	 *      targets: "new" for red links, "stub" for short articles, etc.
	 *   3) It escapes all attribute values safely so there's no risk of XSS.
	 *   4) It provides a default tooltip if the target is a LinkTarget (the page
	 *      name of the target).
	 * link() replaces the old functions in the makeLink() family.
	 *
	 * @since 1.18 Method exists since 1.16 as non-static, made static in 1.18.
	 * @deprecated since 1.28, use MediaWiki\Linker\LinkRenderer instead
	 *
	 * @param LinkTarget $target Can currently only be a LinkTarget, but this may
	 *   change to support Images, literal URLs, etc.
	 * @param string|null $html The HTML contents of the <a> element, i.e.,
	 *   the link text.  This is raw HTML and will not be escaped.  If null,
	 *   defaults to the prefixed text of the LinkTarget; or if the LinkTarget is just a
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
	 * @return string HTML <a> attribute
	 */
	public static function link(
		$target, $html = null, $customAttribs = [], $query = [], $options = []
	) {
		if ( !$target instanceof LinkTarget ) {
			wfWarn( __METHOD__ . ': Requires $target to be a LinkTarget object.', 2 );
			return "<!-- ERROR -->$html";
		}

		$services = MediaWikiServices::getInstance();
		$options = (array)$options;
		if ( $options ) {
			// Custom options, create new LinkRenderer
			$linkRenderer = $services->getLinkRendererFactory()
				->createFromLegacyOptions( $options );
		} else {
			$linkRenderer = $services->getLinkRenderer();
		}

		if ( $html !== null ) {
			$text = new HtmlArmor( $html );
		} else {
			$text = null;
		}

		if ( in_array( 'known', $options, true ) ) {
			return $linkRenderer->makeKnownLink( $target, $text, $customAttribs, $query );
		}

		if ( in_array( 'broken', $options, true ) ) {
			return $linkRenderer->makeBrokenLink( $target, $text, $customAttribs, $query );
		}

		if ( in_array( 'noclasses', $options, true ) ) {
			return $linkRenderer->makePreloadedLink( $target, $text, '', $customAttribs, $query );
		}

		return $linkRenderer->makeLink( $target, $text, $customAttribs, $query );
	}

	/**
	 * Identical to link(), except $options defaults to 'known'.
	 *
	 * @since 1.16.3
	 * @deprecated since 1.28, use MediaWiki\Linker\LinkRenderer instead
	 * @see Linker::link
	 * @param LinkTarget $target
	 * @param string|null $html
	 * @param array $customAttribs
	 * @param array $query
	 * @param string|array $options
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
	 * @param LinkTarget $nt
	 * @param string $html
	 * @param string $query
	 * @param string $trail
	 * @param string $prefix
	 *
	 * @return string
	 */
	public static function makeSelfLinkObj( $nt, $html = '', $query = '', $trail = '', $prefix = '' ) {
		$nt = Title::newFromLinkTarget( $nt );
		$ret = "<a class=\"mw-selflink selflink\">{$prefix}{$html}</a>{$trail}";
		if ( !Hooks::runner()->onSelfLinkBegin( $nt, $html, $trail, $prefix, $ret ) ) {
			return $ret;
		}

		if ( $html == '' ) {
			$html = htmlspecialchars( $nt->getPrefixedText() );
		}
		[ $inside, $trail ] = self::splitTrail( $trail );
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
		// First we check whether the namespace exists or not.
		if ( MediaWikiServices::getInstance()->getNamespaceInfo()->exists( $namespace ) ) {
			if ( $namespace == NS_MAIN ) {
				$name = $context->msg( 'blanknamespace' )->text();
			} else {
				$name = MediaWikiServices::getInstance()->getContentLanguage()->
					getFormattedNsText( $namespace );
			}
			return $context->msg( 'invalidtitle-knownnamespace', $namespace, $name, $title )->text();
		}

		return $context->msg( 'invalidtitle-unknownnamespace', $namespace, $title )->text();
	}

	/**
	 * @since 1.16.3
	 * @deprecated since 1.35, use LinkRenderer::normalizeTarget()
	 * @param LinkTarget $target
	 * @return LinkTarget
	 */
	public static function normaliseSpecialPage( LinkTarget $target ) {
		wfDeprecated( __METHOD__, '1.35' );
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		return $linkRenderer->normalizeTarget( $target );
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
		if ( $basename === false ) {
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
		$success = Hooks::runner()->onLinkerMakeExternalImage( $url, $alt, $img );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalImage changed the output of external image "
				. "with url {$url} and alt text {$alt} to {$img}" );
			return $img;
		}
		return Html::element( 'img',
			[
				'src' => $url,
				'alt' => $alt
			]
		);
	}

	/**
	 * Given parameters derived from [[Image:Foo|options...]], generate the
	 * HTML that that syntax inserts in the page.
	 *
	 * @param Parser $parser
	 * @param LinkTarget $title LinkTarget object of the file (not the currently viewed page)
	 * @param File|false $file File object, or false if it doesn't exist
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
	 *          title           Used for tooltips if caption isn't visible.
	 *          class           HTML for image classes. Plain text.
	 *          caption         HTML for image caption.
	 *          link-url        URL to link to
	 *          link-title      LinkTarget object to link to
	 *          link-target     Value for the target attribute, only with link-url
	 *          no-link         Boolean, suppress description link
	 *
	 * @param array $handlerParams Associative array of media handler parameters, to be passed
	 *       to transform(). Typical keys are "width" and "page".
	 *          targetlang      (optional) Target language code, see Parser::getTargetLanguage()
	 * @param string|false $time Timestamp of the file, set as false for current
	 * @param string $query Query params for desc url
	 * @param int|null $widthOption Used by the parser to remember the user preference thumbnailsize
	 * @since 1.20
	 * @return string HTML for an image, with links, wrappers, etc.
	 */
	public static function makeImageLink( Parser $parser, LinkTarget $title,
		$file, $frameParams = [], $handlerParams = [], $time = false,
		$query = '', $widthOption = null
	) {
		$title = Title::newFromLinkTarget( $title );
		$res = null;
		$dummy = new DummyLinker;
		if ( !Hooks::runner()->onImageBeforeProduceHTML( $dummy, $title,
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$file, $frameParams, $handlerParams, $time, $res,
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$parser, $query, $widthOption )
		) {
			return $res;
		}

		if ( $file && !$file->allowInlineDisplay() ) {
			wfDebug( __METHOD__ . ': ' . $title->getPrefixedDBkey() . ' does not allow inline display' );
			return self::link( $title );
		}

		// Clean up parameters
		$page = $handlerParams['page'] ?? false;
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

		$services = MediaWikiServices::getInstance();
		$config = $services->getMainConfig();
		$enableLegacyMediaDOM = $config->get( MainConfigNames::ParserEnableLegacyMediaDOM );

		$classes = [];
		if (
			!isset( $handlerParams['width'] ) &&
			!isset( $frameParams['manualthumb'] ) &&
			!isset( $frameParams['framed'] )
		) {
			$classes[] = 'mw-default-size';
		}

		$prefix = $postfix = '';

		if ( $enableLegacyMediaDOM ) {
			if ( $frameParams['align'] == 'center' ) {
				$prefix = '<div class="center">';
				$postfix = '</div>';
				$frameParams['align'] = 'none';
			}
		}

		if ( $file && !isset( $handlerParams['width'] ) ) {
			if ( isset( $handlerParams['height'] ) && $file->isVectorized() ) {
				// If its a vector image, and user only specifies height
				// we don't want it to be limited by its "normal" width.
				$svgMaxSize = $config->get( MainConfigNames::SVGMaxSize );
				$handlerParams['width'] = $svgMaxSize;
			} else {
				$handlerParams['width'] = $file->getWidth( $page );
			}

			if ( isset( $frameParams['thumbnail'] )
				|| isset( $frameParams['manualthumb'] )
				|| isset( $frameParams['framed'] )
				|| isset( $frameParams['frameless'] )
				|| !$handlerParams['width']
			) {
				$thumbLimits = $config->get( MainConfigNames::ThumbLimits );
				$thumbUpright = $config->get( MainConfigNames::ThumbUpright );
				if ( $widthOption === null || !isset( $thumbLimits[$widthOption] ) ) {
					$userOptionsLookup = $services->getUserOptionsLookup();
					$widthOption = $userOptionsLookup->getDefaultOption( 'thumbsize' );
				}

				// Reduce width for upright images when parameter 'upright' is used
				if ( isset( $frameParams['upright'] ) && $frameParams['upright'] == 0 ) {
					$frameParams['upright'] = $thumbUpright;
				}

				// For caching health: If width scaled down due to upright
				// parameter, round to full __0 pixel to avoid the creation of a
				// lot of odd thumbs.
				$prefWidth = isset( $frameParams['upright'] ) ?
					round( $thumbLimits[$widthOption] * $frameParams['upright'], -1 ) :
					$thumbLimits[$widthOption];

				// Use width which is smaller: real image width or user preference width
				// Unless image is scalable vector.
				if ( !isset( $handlerParams['height'] ) && ( $handlerParams['width'] <= 0 ||
						$prefWidth < $handlerParams['width'] || $file->isVectorized() ) ) {
					$handlerParams['width'] = $prefWidth;
				}
			}
		}

		// Parser::makeImage has a similarly named variable
		$hasVisibleCaption = isset( $frameParams['thumbnail'] ) ||
			isset( $frameParams['manualthumb'] ) ||
			isset( $frameParams['framed'] );

		if ( $hasVisibleCaption ) {
			if ( $enableLegacyMediaDOM ) {
				// This is no longer needed in our new media output, since the
				// default styling in content.media-common.less takes care of it;
				// see T269704.

				# Create a thumbnail. Alignment depends on the writing direction of
				# the page content language (right-aligned for LTR languages,
				# left-aligned for RTL languages)
				# If a thumbnail width has not been provided, it is set
				# to the default user option as specified in Language*.php
				if ( $frameParams['align'] == '' ) {
					$frameParams['align'] = $parser->getTargetLanguage()->alignEnd();
				}
			}
			return $prefix . self::makeThumbLink2(
				$title, $file, $frameParams, $handlerParams, $time, $query,
				$classes, $parser
			) . $postfix;
		}

		$rdfaType = 'mw:File';

		if ( $file && isset( $frameParams['frameless'] ) ) {
			$rdfaType .= '/Frameless';
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
			$rdfaType = 'mw:Error ' . $rdfaType;
			$label = '';
			if ( $enableLegacyMediaDOM ) {
				// This is the information for tooltips for inline images which
				// Parsoid stores in data-mw.  See T273014
				$label = $frameParams['title'];
			}
			$s = self::makeBrokenImageLinkObj(
				$title, $label, '', '', '', (bool)$time, $handlerParams
			);
		} else {
			self::processResponsiveImages( $file, $thumb, $handlerParams );
			$params = [
				'alt' => $frameParams['alt'],
				'title' => $frameParams['title'],
			];
			if ( $enableLegacyMediaDOM ) {
				$params += [
					'valign' => $frameParams['valign'] ?? false,
					'img-class' => $frameParams['class'],
				];
				if ( isset( $frameParams['border'] ) ) {
					$params['img-class'] .= ( $params['img-class'] !== '' ? ' ' : '' ) . 'thumbborder';
				}
			}
			$params = self::getImageLinkMTOParams( $frameParams, $query, $parser ) + $params;
			$s = $thumb->toHtml( $params );
		}

		if ( $enableLegacyMediaDOM ) {
			if ( $frameParams['align'] != '' ) {
				$s = Html::rawElement(
					'div',
					[ 'class' => 'float' . $frameParams['align'] ],
					$s
				);
			}
			return str_replace( "\n", ' ', $prefix . $s . $postfix );
		}

		$wrapper = 'span';
		$caption = '';

		if ( $frameParams['align'] != '' ) {
			$wrapper = 'figure';
			// Possible values: mw-halign-left mw-halign-center mw-halign-right mw-halign-none
			$classes[] = "mw-halign-{$frameParams['align']}";
			$caption = Html::rawElement(
				'figcaption', [], $frameParams['caption'] ?? ''
			);
		} elseif ( isset( $frameParams['valign'] ) ) {
			// Possible values: mw-valign-middle mw-valign-baseline mw-valign-sub
			// mw-valign-super mw-valign-top mw-valign-text-top mw-valign-bottom
			// mw-valign-text-bottom
			$classes[] = "mw-valign-{$frameParams['valign']}";
		}

		if ( isset( $frameParams['border'] ) ) {
			$classes[] = 'mw-image-border';
		}

		if ( isset( $frameParams['class'] ) ) {
			$classes[] = $frameParams['class'];
		}

		$attribs = [
			'class' => $classes,
			'typeof' => $rdfaType,
		];

		$s = Html::rawElement( $wrapper, $attribs, $s . $caption );

		return str_replace( "\n", ' ', $s );
	}

	/**
	 * Get the link parameters for MediaTransformOutput::toHtml() from given
	 * frame parameters supplied by the Parser.
	 * @param array $frameParams The frame parameters
	 * @param string $query An optional query string to add to description page links
	 * @param Parser|null $parser
	 * @return array
	 */
	public static function getImageLinkMTOParams( $frameParams, $query = '', $parser = null ) {
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
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			$mtoParams['custom-title-link'] = Title::newFromLinkTarget(
				$linkRenderer->normalizeTarget( $frameParams['link-title'] )
			);
			if ( isset( $frameParams['link-title-query'] ) ) {
				$mtoParams['custom-title-link-query'] = $frameParams['link-title-query'];
			}
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
	 * @param LinkTarget $title
	 * @param File|false $file File object or false if it doesn't exist
	 * @param string $label
	 * @param string $alt
	 * @param string|null $align
	 * @param array $params
	 * @param bool $framed
	 * @param string $manualthumb
	 * @return string
	 */
	public static function makeThumbLinkObj(
		LinkTarget $title, $file, $label = '', $alt = '', $align = null,
		$params = [], $framed = false, $manualthumb = ''
	) {
		$frameParams = [
			'alt' => $alt,
			'caption' => $label,
			'align' => $align
		];
		$classes = [];
		if ( $manualthumb ) {
			$frameParams['manualthumb'] = $manualthumb;
		} elseif ( $framed ) {
			$frameParams['framed'] = true;
		} elseif ( !isset( $params['width'] ) ) {
			$classes[] = 'mw-default-size';
		}
		return self::makeThumbLink2(
			$title, $file, $frameParams, $params, false, '', $classes
		);
	}

	/**
	 * @param LinkTarget $title
	 * @param File|false $file
	 * @param array $frameParams
	 * @param array $handlerParams
	 * @param bool $time
	 * @param string $query
	 * @param string[] $classes @since 1.36
	 * @param Parser|null $parser @since 1.38
	 * @return string
	 */
	public static function makeThumbLink2(
		LinkTarget $title, $file, $frameParams = [], $handlerParams = [],
		$time = false, $query = '', array $classes = [], ?Parser $parser = null
	) {
		$exists = $file && $file->exists();

		$services = MediaWikiServices::getInstance();
		$enableLegacyMediaDOM = $services->getMainConfig()->get( MainConfigNames::ParserEnableLegacyMediaDOM );

		$page = $handlerParams['page'] ?? false;
		if ( !isset( $frameParams['align'] ) ) {
			$frameParams['align'] = '';
			if ( $enableLegacyMediaDOM ) {
				$frameParams['align'] = 'right';
			}
		}
		if ( !isset( $frameParams['alt'] ) ) {
			$frameParams['alt'] = '';
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
		$rdfaType = 'mw:File/Thumb';

		if ( !$exists ) {
			$outerWidth = $handlerParams['width'] + 2;
		} else {
			if ( isset( $frameParams['manualthumb'] ) ) {
				# Use manually specified thumbnail
				$manual_title = Title::makeTitleSafe( NS_FILE, $frameParams['manualthumb'] );
				if ( $manual_title ) {
					$manual_img = $services->getRepoGroup()
						->findFile( $manual_title );
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
				$rdfaType = 'mw:File/Frame';
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

		$url = Title::newFromLinkTarget( $title )->getLocalURL( $query );
		$linkTitleQuery = [];

		if ( $page ) {
			$linkTitleQuery['page'] = $page;
			# ThumbnailImage::toHtml() already adds page= onto the end of DjVu URLs
			# So we don't need to pass it here in $query. However, the URL for the
			# zoom icon still needs it, so we make a unique query for it. See T16771
			# FIXME: What about "lang" and other querystring parameters
			$url = wfAppendQuery( $url, $linkTitleQuery );
		}

		if ( $manualthumb
			&& !isset( $frameParams['link-title'] )
			&& !isset( $frameParams['link-url'] )
			&& !isset( $frameParams['no-link'] ) ) {
			$frameParams['link-title'] = $title;
			$frameParams['link-title-query'] = $linkTitleQuery;
		}

		if ( $frameParams['align'] != '' ) {
			// Possible values: mw-halign-left mw-halign-center mw-halign-right mw-halign-none
			$classes[] = "mw-halign-{$frameParams['align']}";
		}

		if ( isset( $frameParams['class'] ) ) {
			$classes[] = $frameParams['class'];
		}

		$s = '';

		if ( $enableLegacyMediaDOM ) {
			$s .= "<div class=\"thumb t{$frameParams['align']}\">"
				. "<div class=\"thumbinner\" style=\"width:{$outerWidth}px;\">";
		}

		if ( !$exists ) {
			$label = '';
			$s .= self::makeBrokenImageLinkObj(
				$title, $label, '', '', '', (bool)$time, $handlerParams
			);
			$zoomIcon = '';
		} elseif ( !$thumb ) {
			if ( $enableLegacyMediaDOM ) {
				$s .= wfMessage( 'thumbnail_error', '' )->escaped();
			} else {
				$s .= self::makeBrokenImageLinkObj(
					$title, '', '', '', '', (bool)$time, $handlerParams
				);
			}
			$zoomIcon = '';
		} else {
			if ( !$noscale && !$manualthumb ) {
				self::processResponsiveImages( $file, $thumb, $handlerParams );
			}
			$params = [
				'alt' => $frameParams['alt'],
			];
			if ( $enableLegacyMediaDOM ) {
				$params += [
					'img-class' => ( isset( $frameParams['class'] ) && $frameParams['class'] !== ''
						? $frameParams['class'] . ' '
						: '' ) . 'thumbimage'
				];
			}
			$params = self::getImageLinkMTOParams( $frameParams, $query, $parser ) + $params;
			$s .= $thumb->toHtml( $params );
			if ( isset( $frameParams['framed'] ) ) {
				$zoomIcon = '';
			} else {
				$zoomIcon = Html::rawElement( 'div', [ 'class' => 'magnify' ],
					Html::rawElement( 'a', [
						'href' => $url,
						'class' => 'internal',
						'title' => wfMessage( 'thumbnail-more' )->text(),
					] )
				);
			}
		}

		if ( $enableLegacyMediaDOM ) {
			$s .= '  <div class="thumbcaption">' . $zoomIcon . $frameParams['caption'] . '</div></div></div>';
			return str_replace( "\n", ' ', $s );
		}

		$s .= Html::rawElement(
			'figcaption', [], $frameParams['caption'] ?? ''
		);

		if ( !$exists || !$thumb ) {
			$rdfaType = 'mw:Error ' . $rdfaType;
		}

		$attribs = [
			'class' => $classes,
			'typeof' => $rdfaType,
		];

		$s = Html::rawElement( 'figure', $attribs, $s );

		return str_replace( "\n", ' ', $s );
	}

	/**
	 * Process responsive images: add 1.5x and 2x subimages to the thumbnail, where
	 * applicable.
	 *
	 * @param File $file
	 * @param MediaTransformOutput|null $thumb
	 * @param array $hp Image parameters
	 */
	public static function processResponsiveImages( $file, $thumb, $hp ) {
		$responsiveImages = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::ResponsiveImages );
		if ( $responsiveImages && $thumb && !$thumb->isError() ) {
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
	 * @param LinkTarget $title
	 * @param string $label Link label (plain text)
	 * @param string $query Query string
	 * @param string $unused1 Unused parameter kept for b/c
	 * @param string $unused2 Unused parameter kept for b/c
	 * @param bool $time A file of a certain timestamp was requested
	 * @param array $handlerParams @since 1.36
	 * @return string
	 */
	public static function makeBrokenImageLinkObj(
		$title, $label = '', $query = '', $unused1 = '', $unused2 = '',
		$time = false, array $handlerParams = []
	) {
		if ( !$title instanceof LinkTarget ) {
			wfWarn( __METHOD__ . ': Requires $title to be a LinkTarget object.' );
			return "<!-- ERROR -->" . htmlspecialchars( $label );
		}

		$title = Title::castFromLinkTarget( $title );
		$services = MediaWikiServices::getInstance();
		$mainConfig = $services->getMainConfig();
		$enableUploads = $mainConfig->get( MainConfigNames::EnableUploads );
		$uploadMissingFileUrl = $mainConfig->get( MainConfigNames::UploadMissingFileUrl );
		$uploadNavigationUrl = $mainConfig->get( MainConfigNames::UploadNavigationUrl );
		if ( $label == '' ) {
			$label = $title->getPrefixedText();
		}

		$html = Html::element( 'span', [
			'class' => 'mw-broken-media',
			// These data attributes are used to dynamically size the span, see T273013
			'data-width' => $handlerParams['width'] ?? null,
			'data-height' => $handlerParams['height'] ?? null,
		], $label );

		if ( $mainConfig->get( MainConfigNames::ParserEnableLegacyMediaDOM ) ) {
			$html = htmlspecialchars( $label, ENT_COMPAT );
		}

		$repoGroup = $services->getRepoGroup();
		$currentExists = $time
			&& $repoGroup->findFile( $title ) !== false;

		if ( ( $uploadMissingFileUrl || $uploadNavigationUrl || $enableUploads )
			&& !$currentExists
		) {
			if (
				$title->inNamespace( NS_FILE ) &&
				$repoGroup->getLocalRepo()->checkRedirect( $title )
			) {
				// We already know it's a redirect, so mark it accordingly
				return self::link(
					$title,
					$html,
					[ 'class' => 'mw-redirect' ],
					wfCgiToArray( $query ),
					[ 'known', 'noclasses' ]
				);
			}
			return Html::rawElement( 'a', [
					'href' => self::getUploadUrl( $title, $query ),
					'class' => 'new',
					'title' => $title->getPrefixedText()
				], $html );
		}
		return self::link(
			$title,
			$html,
			[],
			wfCgiToArray( $query ),
			[ 'known', 'noclasses' ]
		);
	}

	/**
	 * Get the URL to upload a certain file
	 *
	 * @since 1.16.3
	 * @param LinkTarget $destFile LinkTarget object of the file to upload
	 * @param string $query Urlencoded query string to prepend
	 * @return string Urlencoded URL
	 */
	protected static function getUploadUrl( $destFile, $query = '' ) {
		$mainConfig = MediaWikiServices::getInstance()->getMainConfig();
		$uploadMissingFileUrl = $mainConfig->get( MainConfigNames::UploadMissingFileUrl );
		$uploadNavigationUrl = $mainConfig->get( MainConfigNames::UploadNavigationUrl );
		$q = 'wpDestFile=' . Title::castFromLinkTarget( $destFile )->getPartialURL();
		if ( $query != '' ) {
			$q .= '&' . $query;
		}

		if ( $uploadMissingFileUrl ) {
			return wfAppendQuery( $uploadMissingFileUrl, $q );
		}

		if ( $uploadNavigationUrl ) {
			return wfAppendQuery( $uploadNavigationUrl, $q );
		}

		$upload = SpecialPage::getTitleFor( 'Upload' );

		return $upload->getLocalURL( $q );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 *
	 * @since 1.16.3
	 * @param LinkTarget $title
	 * @param string $html Pre-sanitized HTML
	 * @param string|false $time MW timestamp of file creation time
	 * @return string HTML
	 */
	public static function makeMediaLinkObj( $title, $html = '', $time = false ) {
		$img = MediaWikiServices::getInstance()->getRepoGroup()->findFile(
			$title, [ 'time' => $time ]
		);
		return self::makeMediaLinkFile( $title, $img, $html );
	}

	/**
	 * Create a direct link to a given uploaded file.
	 * This will make a broken link if $file is false.
	 *
	 * @since 1.16.3
	 * @param LinkTarget $title
	 * @param File|false $file File object or false
	 * @param string $html Pre-sanitized HTML
	 * @return string HTML
	 *
	 * @todo Handle invalid or missing images better.
	 */
	public static function makeMediaLinkFile( LinkTarget $title, $file, $html = '' ) {
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

		if ( !Hooks::runner()->onLinkerMakeMediaLinkFile(
			Title::castFromLinkTarget( $title ), $file, $html, $attribs, $ret )
		) {
			wfDebug( "Hook LinkerMakeMediaLinkFile changed the output of link "
				. "with url {$url} and text {$html} to {$ret}" );
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

		return self::linkKnown( SpecialPage::getTitleFor( $name ), wfMessage( $key )->escaped() );
	}

	/**
	 * Make an external link
	 *
	 * @since 1.16.3. $title added in 1.21
	 * @param string $url URL to link to
	 * @param-taint $url escapes_html
	 * @param string $text Text of link
	 * @param-taint $text escapes_html
	 * @param bool $escape Do we escape the link text?
	 * @param-taint $escape none
	 * @param string $linktype Type of external link. Gets added to the classes
	 * @param-taint $linktype escapes_html
	 * @param array $attribs Array of extra attributes to <a>
	 * @param-taint $attribs escapes_html
	 * @param LinkTarget|null $title LinkTarget object used for title specific link attributes
	 * @param-taint $title none
	 * @return string
	 */
	public static function makeExternalLink( $url, $text, $escape = true,
		$linktype = '', $attribs = [], $title = null
	) {
		global $wgTitle;
		$class = 'external';
		if ( $linktype ) {
			$class .= " $linktype";
		}
		if ( isset( $attribs['class'] ) && $attribs['class'] ) {
			$class .= " {$attribs['class']}";
		}
		$attribs['class'] = $class;

		if ( $escape ) {
			$text = htmlspecialchars( $text, ENT_COMPAT );
		}

		if ( !$title ) {
			$title = $wgTitle;
		}
		$newRel = Parser::getExternalLinkRel( $url, $title );
		if ( !isset( $attribs['rel'] ) || $attribs['rel'] === '' ) {
			$attribs['rel'] = $newRel;
		} elseif ( $newRel !== null ) {
			// Merge the rel attributes.
			$newRels = explode( ' ', $newRel );
			$oldRels = explode( ' ', $attribs['rel'] );
			$combined = array_unique( array_merge( $newRels, $oldRels ) );
			$attribs['rel'] = implode( ' ', $combined );
		}
		$link = '';
		$success = Hooks::runner()->onLinkerMakeExternalLink(
			$url, $text, $link, $attribs, $linktype );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalLink changed the output of link "
				. "with url {$url} and text {$text} to {$link}" );
			return $link;
		}
		$attribs['href'] = $url;
		return Html::rawElement( 'a', $attribs, $text );
	}

	/**
	 * Make user link (or user contributions for unregistered users)
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @param int $userId User id in database.
	 * @param string $userName User name in database.
	 * @param string|false $altUserName Text to display instead of the user name (optional)
	 * @return string HTML fragment
	 * @since 1.16.3. $altUserName was added in 1.19.
	 */
	public static function userLink( $userId, $userName, $altUserName = false ) {
		if ( $userName === '' || $userName === false || $userName === null ) {
			wfDebug( __METHOD__ . ' received an empty username. Are there database errors ' .
				'that need to be fixed?' );
			return wfMessage( 'empty-username' )->parse();
		}

		$classes = 'mw-userlink';
		$page = null;
		if ( $userId == 0 ) {
			$page = ExternalUserNames::getUserLinkTitle( $userName );

			if ( ExternalUserNames::isExternal( $userName ) ) {
				$classes .= ' mw-extuserlink';
			} elseif ( $altUserName === false ) {
				$altUserName = IPUtils::prettifyIP( $userName );
			}
			$classes .= ' mw-anonuserlink'; // Separate link class for anons (T45179)
		} else {
			$page = TitleValue::tryNew( NS_USER, strtr( $userName, ' ', '_' ) );
		}

		// Wrap the output with <bdi> tags for directionality isolation
		$linkText =
			'<bdi>' . htmlspecialchars( $altUserName !== false ? $altUserName : $userName ) . '</bdi>';

		return $page
			? self::link( $page, $linkText, [ 'class' => $classes ] )
			: Html::rawElement( 'span', [ 'class' => $classes ], $linkText );
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
	 * @param int|null $edits User edit count (optional, for performance)
	 * @param bool $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string HTML fragment
	 */
	public static function userToolLinks(
		$userId, $userText, $redContribsWhenNoEdits = false, $flags = 0, $edits = null,
		$useParentheses = true
	) {
		if ( $userText === '' ) {
			wfDebug( __METHOD__ . ' received an empty username. Are there database errors ' .
				'that need to be fixed?' );
			return ' ' . wfMessage( 'empty-username' )->parse();
		}
		global $wgLang;
		$disableAnonTalk = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::DisableAnonTalk );
		$talkable = !( $disableAnonTalk && $userId == 0 );
		$blockable = !( $flags & self::TOOL_LINKS_NOBLOCK );
		$addEmailLink = $flags & self::TOOL_LINKS_EMAIL && $userId;

		if ( $userId == 0 && ExternalUserNames::isExternal( $userText ) ) {
			// No tools for an external user
			return '';
		}

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
					// Note: "new" class is inappropriate here, as "new" class
					// should only be used for pages that do not exist.
					$attribs['class'] .= ' mw-usertoollinks-contribs-no-edits';
				}
			}
			$contribsPage = SpecialPage::getTitleFor( 'Contributions', $userText );

			$items[] = self::link( $contribsPage, wfMessage( 'contribslink' )->escaped(), $attribs );
		}
		$userCanBlock = RequestContext::getMain()->getAuthority()->isAllowed( 'block' );
		if ( $blockable && $userCanBlock ) {
			$items[] = self::blockLink( $userId, $userText );
		}

		$user = RequestContext::getMain()->getUser();
		if ( $addEmailLink && $user->canSendEmail() ) {
			$items[] = self::emailLink( $userId, $userText );
		}

		Hooks::runner()->onUserToolLinksEdit( $userId, $userText, $items );

		if ( !$items ) {
			return '';
		}

		if ( $useParentheses ) {
			return wfMessage( 'word-separator' )->escaped()
				. '<span class="mw-usertoollinks">'
				. wfMessage( 'parentheses' )->rawParams( $wgLang->pipeList( $items ) )->escaped()
				. '</span>';
		}

		$tools = [];
		foreach ( $items as $tool ) {
			$tools[] = Html::rawElement( 'span', [], $tool );
		}
		return ' <span class="mw-usertoollinks mw-changeslist-links">' .
			implode( ' ', $tools ) . '</span>';
	}

	/**
	 * Alias for userToolLinks( $userId, $userText, true );
	 * @since 1.16.3
	 * @param int $userId User identifier
	 * @param string $userText User name or IP address
	 * @param int|null $edits User edit count (optional, for performance)
	 * @param bool $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string
	 */
	public static function userToolLinksRedContribs(
		$userId, $userText, $edits = null, $useParentheses = true
	) {
		return self::userToolLinks( $userId, $userText, true, 0, $edits, $useParentheses );
	}

	/**
	 * @since 1.16.3
	 * @param int $userId User id in database.
	 * @param string $userText User name in database.
	 * @return string HTML fragment with user talk link
	 */
	public static function userTalkLink( $userId, $userText ) {
		if ( $userText === '' ) {
			wfDebug( __METHOD__ . ' received an empty username. Are there database errors ' .
				'that need to be fixed?' );
			return wfMessage( 'empty-username' )->parse();
		}

		$userTalkPage = TitleValue::tryNew( NS_USER_TALK, strtr( $userText, ' ', '_' ) );
		$moreLinkAttribs = [ 'class' => 'mw-usertoollinks-talk' ];
		$linkText = wfMessage( 'talkpagelinktext' )->escaped();

		return $userTalkPage
			? self::link( $userTalkPage, $linkText, $moreLinkAttribs )
			: Html::rawElement( 'span', $moreLinkAttribs, $linkText );
	}

	/**
	 * @since 1.16.3
	 * @param int $userId
	 * @param string $userText User name in database.
	 * @return string HTML fragment with block link
	 */
	public static function blockLink( $userId, $userText ) {
		if ( $userText === '' ) {
			wfDebug( __METHOD__ . ' received an empty username. Are there database errors ' .
				'that need to be fixed?' );
			return wfMessage( 'empty-username' )->parse();
		}

		$blockPage = SpecialPage::getTitleFor( 'Block', $userText );
		$moreLinkAttribs = [ 'class' => 'mw-usertoollinks-block' ];

		return self::link( $blockPage,
			wfMessage( 'blocklink' )->escaped(),
			$moreLinkAttribs
		);
	}

	/**
	 * @param int $userId
	 * @param string $userText User name in database.
	 * @return string HTML fragment with e-mail user link
	 */
	public static function emailLink( $userId, $userText ) {
		if ( $userText === '' ) {
			wfLogWarning( __METHOD__ . ' received an empty username. Are there database errors ' .
				'that need to be fixed?' );
			return wfMessage( 'empty-username' )->parse();
		}

		$emailPage = SpecialPage::getTitleFor( 'Emailuser', $userText );
		$moreLinkAttribs = [ 'class' => 'mw-usertoollinks-mail' ];
		return self::link( $emailPage,
			wfMessage( 'emaillink' )->escaped(),
			$moreLinkAttribs
		);
	}

	/**
	 * Generate a user link if the current user is allowed to view it
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @since 1.16.3
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param bool $isPublic Show only if all users can see it
	 * @return string HTML fragment
	 */
	public static function revUserLink( RevisionRecord $revRecord, $isPublic = false ) {
		// TODO inject authority
		$authority = RequestContext::getMain()->getAuthority();

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_USER ) && $isPublic ) {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		} elseif ( $revRecord->userCan( RevisionRecord::DELETED_USER, $authority ) ) {
			$revUser = $revRecord->getUser( RevisionRecord::FOR_THIS_USER, $authority );
			$link = self::userLink(
				$revUser ? $revUser->getId() : 0,
				$revUser ? $revUser->getName() : ''
			);
		} else {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		}
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$class = self::getRevisionDeletedClass( $revRecord );
			return '<span class="' . $class . '">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Returns css class of a deleted revision
	 * @param RevisionRecord $revisionRecord
	 * @return string 'history-deleted', 'mw-history-suppressed' added if suppressed too
	 * @since 1.37
	 */
	public static function getRevisionDeletedClass( RevisionRecord $revisionRecord ): string {
		$class = 'history-deleted';
		if ( $revisionRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ) ) {
			$class .= ' mw-history-suppressed';
		}
		return $class;
	}

	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @since 1.16.3
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param bool $isPublic Show only if all users can see it
	 * @param bool $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string HTML
	 */
	public static function revUserTools(
		RevisionRecord $revRecord,
		$isPublic = false,
		$useParentheses = true
	) {
		// TODO inject authority
		$authority = RequestContext::getMain()->getAuthority();

		if ( $revRecord->userCan( RevisionRecord::DELETED_USER, $authority ) &&
			( !$revRecord->isDeleted( RevisionRecord::DELETED_USER ) || !$isPublic )
		) {
			$revUser = $revRecord->getUser( RevisionRecord::FOR_THIS_USER, $authority );
			$userId = $revUser ? $revUser->getId() : 0;
			$userText = $revUser ? $revUser->getName() : '';

			if ( $userId || $userText !== '' ) {
				$link = self::userLink( $userId, $userText )
					. self::userToolLinks( $userId, $userText, false, 0, null,
						$useParentheses );
			}
		}

		if ( !isset( $link ) ) {
			$link = wfMessage( 'rev-deleted-user' )->escaped();
		}

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$class = self::getRevisionDeletedClass( $revRecord );
			return ' <span class="' . $class . ' mw-userlink">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Helper function to expand local links. Mostly used in action=render
	 *
	 * @since 1.38
	 * @unstable
	 *
	 * @param string $html
	 *
	 * @return string HTML
	 */
	public static function expandLocalLinks( string $html ) {
		$formatter = new HtmlFormatter( $html );
		$doc = $formatter->getDoc();
		$xpath = new DOMXPath( $doc );
		$nodes = $xpath->query( '//a[@href]' );
		/** @var DOMElement $node */
		foreach ( $nodes as $node ) {
			$node->setAttribute(
				'href',
				wfExpandUrl( $node->getAttribute( 'href' ), PROTO_RELATIVE )
			);
		}
		return $formatter->getText( 'html' );
	}

	/**
	 * This function is called by all recent changes variants, by the page history,
	 * and by the user contributions list. It is responsible for formatting edit
	 * summaries. It escapes any HTML in the summary, but adds some CSS to format
	 * auto-generated comments (from section editing) and formats [[wikilinks]].
	 *
	 * This method produces HTML that can require CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @since 1.16.3. $wikiId added in 1.26
	 * @deprecated since 1.38 use CommentFormatter
	 *
	 * @param string $comment
	 * @param LinkTarget|null $title LinkTarget object (to generate link to the section in
	 *  autocomment) or null
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id (as used by WikiMap) of the wiki to generate links to.
	 *  For use with external changes.
	 *
	 * @return string HTML
	 */
	public static function formatComment(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		$formatter = MediaWikiServices::getInstance()->getCommentFormatter();
		return $formatter->format( $comment, $title, $local, $wikiId );
	}

	/**
	 * Formats wiki links and media links in text; all other wiki formatting
	 * is ignored
	 *
	 * @since 1.16.3. $wikiId added in 1.26
	 * @deprecated since 1.38 use CommentFormatter
	 *
	 * @param string $comment Text to format links in. WARNING! Since the output of this
	 * 	function is html, $comment must be sanitized for use as html. You probably want
	 * 	to pass $comment through Sanitizer::escapeHtmlAllowEntities() before calling
	 * 	this function.
	 * @param LinkTarget|null $title An optional LinkTarget object used to links to sections
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 *
	 * @return string HTML
	 * @return-taint onlysafefor_html
	 */
	public static function formatLinksInComment(
		$comment, $title = null, $local = false, $wikiId = null
	) {
		$formatter = MediaWikiServices::getInstance()->getCommentFormatter();
		return $formatter->formatLinksUnsafe( $comment, $title, $local, $wikiId );
	}

	/**
	 * @param LinkTarget|null $contextTitle
	 * @param string $target
	 * @param string &$text
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
		if (
			$contextTitle && MediaWikiServices::getInstance()->getNamespaceInfo()->
			hasSubpages( $contextTitle->getNamespace() )
		) {
			$hash = strpos( $target, '#' );
			if ( $hash !== false ) {
				$suffix = substr( $target, $hash );
				$target = substr( $target, 0, $hash );
			} else {
				$suffix = '';
			}
			# T9425
			$target = trim( $target );
			$contextPrefixedText = MediaWikiServices::getInstance()->getTitleFormatter()->
				getPrefixedText( $contextTitle );
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

				$ret = $contextPrefixedText . '/' . trim( $noslash ) . $suffix;
				if ( $text === '' ) {
					$text = $target . $suffix;
				} # this might be changed for ugliness reasons
			} else {
				# check for .. subpage backlinks
				$dotdotcount = 0;
				$nodotdot = $target;
				while ( str_starts_with( $nodotdot, '../' ) ) {
					++$dotdotcount;
					$nodotdot = substr( $nodotdot, 3 );
				}
				if ( $dotdotcount > 0 ) {
					$exploded = explode( '/', $contextPrefixedText );
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
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @since 1.16.3. $wikiId added in 1.26
	 * @deprecated since 1.38 use CommentFormatter
	 *
	 * @param string $comment
	 * @param LinkTarget|null $title LinkTarget object (to generate link to section in autocomment)
	 *  or null
	 * @param bool $local Whether section links should refer to local page
	 * @param string|null $wikiId Id (as used by WikiMap) of the wiki to generate links to.
	 *  For use with external changes.
	 * @param bool $useParentheses Whether the comment is wrapped in parentheses
	 *
	 * @return string
	 */
	public static function commentBlock(
		$comment, $title = null, $local = false, $wikiId = null, $useParentheses = true
	) {
		return MediaWikiServices::getInstance()->getCommentFormatter()
			->formatBlock( $comment, $title, $local, $wikiId, $useParentheses );
	}

	/**
	 * Wrap and format the given revision's comment block, if the current
	 * user is allowed to view it.
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
	 *
	 * @since 1.16.3
	 * @deprecated since 1.38 use CommentFormatter
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param bool $local Whether section links should refer to local page
	 * @param bool $isPublic Show only if all users can see it
	 * @param bool $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string HTML fragment
	 */
	public static function revComment(
		RevisionRecord $revRecord,
		$local = false,
		$isPublic = false,
		$useParentheses = true
	) {
		$authority = RequestContext::getMain()->getAuthority();
		$formatter = MediaWikiServices::getInstance()->getCommentFormatter();
		return $formatter->formatRevision( $revRecord, $authority, $local, $isPublic, $useParentheses );
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
		}
		return "<span class=\"history-size mw-diff-bytes\" data-mw-bytes=\"$size\">$stxt</span>";
	}

	/**
	 * Add another level to the Table of Contents
	 *
	 * @since 1.16.3
	 * @return string
	 */
	public static function tocIndent() {
		return "\n<ul>\n";
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
	 * @param int $level
	 * @param int|false $sectionIndex
	 * @return string
	 */
	public static function tocLine( $anchor, $tocline, $tocnumber, $level, $sectionIndex = false ) {
		$classes = "toclevel-$level";
		if ( $sectionIndex !== false ) {
			$classes .= " tocsection-$sectionIndex";
		}

		// <li class="$classes"><a href="#$anchor"><span class="tocnumber">
		// $tocnumber</span> <span class="toctext">$tocline</span></a>
		return Html::openElement( 'li', [ 'class' => $classes ] )
			. Html::rawElement( 'a',
				[ 'href' => "#$anchor" ],
				Html::element( 'span', [ 'class' => 'tocnumber' ], $tocnumber )
					. ' '
					. Html::rawElement( 'span', [ 'class' => 'toctext' ], $tocline )
			);
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
	 * Wraps the TOC in a div with ARIA navigation role and provides the hide/collapse JavaScript.
	 *
	 * @since 1.16.3
	 * @param string $toc Html of the Table Of Contents
	 * @param Language|null $lang Language for the toc title, defaults to user language
	 * @return string Full html of the TOC
	 */
	public static function tocList( $toc, Language $lang = null ) {
		$lang = $lang ?? RequestContext::getMain()->getLanguage();

		$title = wfMessage( 'toc' )->inLanguage( $lang )->escaped();

		return '<div id="toc" class="toc" role="navigation" aria-labelledby="mw-toc-heading">'
			. Html::element( 'input', [
				'type' => 'checkbox',
				'role' => 'button',
				'id' => 'toctogglecheckbox',
				'class' => 'toctogglecheckbox',
				'style' => 'display:none',
			] )
			. Html::openElement( 'div', [
				'class' => 'toctitle',
				'lang' => $lang->getHtmlCode(),
				'dir' => $lang->getDir(),
			] )
			. '<h2 id="mw-toc-heading">' . $title . '</h2>'
			. '<span class="toctogglespan">'
			. Html::label( '', 'toctogglecheckbox', [
				'class' => 'toctogglelabel',
			] )
			. '</span>'
			. '</div>'
			. $toc
			. "</ul>\n</div>\n";
	}

	/**
	 * Generate a table of contents from a section tree.
	 *
	 * @since 1.16.3. $lang added in 1.17
	 * @param array[] $tree Return value of ParserOutput::getSections()
	 * @param Language|null $lang Language for the toc title, defaults to user language
	 * @return string HTML fragment
	 */
	public static function generateTOC( $tree, Language $lang = null ) {
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
	 * @param string $html HTML for the text of the header
	 * @param string $link HTML to add for the section edit link
	 * @param string|false $fallbackAnchor A second, optional anchor to give for
	 *   backward compatibility (false to omit)
	 *
	 * @return string HTML headline
	 */
	public static function makeHeadline( $level, $attribs, $anchor, $html,
		$link, $fallbackAnchor = false
	) {
		$anchorEscaped = htmlspecialchars( $anchor, ENT_COMPAT );
		$fallback = '';
		if ( $fallbackAnchor !== false && $fallbackAnchor !== $anchor ) {
			$fallbackAnchor = htmlspecialchars( $fallbackAnchor, ENT_COMPAT );
			$fallback = "<span id=\"$fallbackAnchor\"></span>";
		}
		return "<h$level$attribs"
			. "$fallback<span class=\"mw-headline\" id=\"$anchorEscaped\">$html</span>"
			. $link
			. "</h$level>";
	}

	/**
	 * Split a link trail, return the "inside" portion and the remainder of the trail
	 * as a two-element array
	 * @param string $trail
	 * @return string[]
	 */
	public static function splitTrail( $trail ) {
		$regex = MediaWikiServices::getInstance()->getContentLanguage()->linkTrail();
		$inside = '';
		if ( $trail !== '' && preg_match( $regex, $trail, $m ) ) {
			[ , $inside, $trail ] = $m;
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
	 *   $rev could be a RevisionRecord since 1.35
	 *
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param IContextSource|null $context Context to use or null for the main context.
	 * @param array $options
	 * @return string
	 */
	public static function generateRollback(
		RevisionRecord $revRecord,
		IContextSource $context = null,
		$options = [ 'verify' ]
	) {
		if ( $context === null ) {
			$context = RequestContext::getMain();
		}

		$editCount = false;
		if ( in_array( 'verify', $options, true ) ) {
			$editCount = self::getRollbackEditCount( $revRecord, true );
			if ( $editCount === false ) {
				return '';
			}
		}

		$inner = self::buildRollbackLink( $revRecord, $context, $editCount );

		// Allow extensions to modify the rollback link.
		// Abort further execution if the extension wants full control over the link.
		if ( !Hooks::runner()->onLinkerGenerateRollbackLink(
			$revRecord, $context, $options, $inner ) ) {
			return $inner;
		}

		if ( !in_array( 'noBrackets', $options, true ) ) {
			$inner = $context->msg( 'brackets' )->rawParams( $inner )->escaped();
		}

		if ( MediaWikiServices::getInstance()->getUserOptionsLookup()
			->getBoolOption( $context->getUser(), 'showrollbackconfirmation' )
		) {
			$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
			$stats->increment( 'rollbackconfirmation.event.load' );
			$context->getOutput()->addModules( 'mediawiki.misc-authed-curate' );
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
	 * @todo Unused outside of this file - should it be made private?
	 *
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param bool $verify Try to verify that this revision can really be rolled back
	 * @return int|false|null
	 */
	public static function getRollbackEditCount( RevisionRecord $revRecord, $verify ) {
		$showRollbackEditCount = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::ShowRollbackEditCount );

		if ( !is_int( $showRollbackEditCount ) || !$showRollbackEditCount > 0 ) {
			// Nothing has happened, indicate this by returning 'null'
			return null;
		}

		$dbr = wfGetDB( DB_REPLICA );

		// Up to the value of $wgShowRollbackEditCount revisions are counted
		$revQuery = MediaWikiServices::getInstance()->getRevisionStore()->getQueryInfo();
		$res = $dbr->newSelectQueryBuilder()
			->select( [ 'rev_user_text' => $revQuery['fields']['rev_user_text'], 'rev_deleted' ] )
			->tables( $revQuery['tables'] )
			->where( [ 'rev_page' => $revRecord->getPageId() ] )
			->joinConds( $revQuery['joins'] )
			->useIndex( [ 'revision' => 'rev_page_timestamp' ] )
			->orderBy( [ 'rev_timestamp', 'rev_id' ], SelectQueryBuilder::SORT_DESC )
			->limit( $showRollbackEditCount + 1 )
			->caller( __METHOD__ )
			->fetchResultSet();

		$revUser = $revRecord->getUser( RevisionRecord::RAW );
		$revUserText = $revUser ? $revUser->getName() : '';

		$editCount = 0;
		$moreRevs = false;
		foreach ( $res as $row ) {
			if ( $row->rev_user_text != $revUserText ) {
				if ( $verify &&
					( $row->rev_deleted & RevisionRecord::DELETED_TEXT
						|| $row->rev_deleted & RevisionRecord::DELETED_USER
				) ) {
					// If the user or the text of the revision we might rollback
					// to is deleted in some way we can't rollback. Similar to
					// the checks in WikiPage::commitRollback.
					return false;
				}
				$moreRevs = true;
				break;
			}
			$editCount++;
		}

		if ( $verify && $editCount <= $showRollbackEditCount && !$moreRevs ) {
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
	 *   $rev could be a RevisionRecord since 1.35
	 *
	 * @todo Unused outside of this file - should it be made private?
	 *
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param IContextSource|null $context Context to use or null for the main context.
	 * @param int|false|null $editCount Number of edits that would be reverted
	 * @return string HTML fragment
	 */
	public static function buildRollbackLink(
		RevisionRecord $revRecord,
		IContextSource $context = null,
		$editCount = false
	) {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$showRollbackEditCount = $config->get( MainConfigNames::ShowRollbackEditCount );
		$miserMode = $config->get( MainConfigNames::MiserMode );
		// To config which pages are affected by miser mode
		$disableRollbackEditCountSpecialPage = [ 'Recentchanges', 'Watchlist' ];

		if ( $context === null ) {
			$context = RequestContext::getMain();
		}

		$title = $revRecord->getPageAsLinkTarget();
		$revUser = $revRecord->getUser();
		$revUserText = $revUser ? $revUser->getName() : '';

		$query = [
			'action' => 'rollback',
			'from' => $revUserText,
			'token' => $context->getUser()->getEditToken( 'rollback' ),
		];

		$attrs = [
			'data-mw' => 'interface',
			'title' => $context->msg( 'tooltip-rollback' )->text()
		];

		$options = [ 'known', 'noclasses' ];

		if ( $context->getRequest()->getBool( 'bot' ) ) {
			// T17999
			$query['hidediff'] = '1';
			$query['bot'] = '1';
		}

		if ( $miserMode ) {
			foreach ( $disableRollbackEditCountSpecialPage as $specialPage ) {
				if ( $context->getTitle()->isSpecial( $specialPage ) ) {
					$showRollbackEditCount = false;
					break;
				}
			}
		}

		// The edit count can be 0 on replica lag, fall back to the generic rollbacklink message
		$msg = [ 'rollbacklink' ];
		if ( is_int( $showRollbackEditCount ) && $showRollbackEditCount > 0 ) {
			if ( !is_numeric( $editCount ) ) {
				$editCount = self::getRollbackEditCount( $revRecord, false );
			}

			if ( $editCount > $showRollbackEditCount ) {
				$msg = [ 'rollbacklinkcount-morethan', Message::numParam( $showRollbackEditCount ) ];
			} elseif ( $editCount ) {
				$msg = [ 'rollbacklinkcount', Message::numParam( $editCount ) ];
			}
		}

		$html = $context->msg( ...$msg )->parse();
		return self::link( $title, $html, $attrs, $query, $options );
	}

	/**
	 * Returns HTML for the "hidden categories on this page" list.
	 *
	 * @since 1.16.3
	 * @param array $hiddencats Array of hidden categories
	 *    from {@link WikiPage::getHiddenCategories} or similar
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
	 * @return ContextSource
	 */
	private static function getContextFromMain() {
		$context = RequestContext::getMain();
		$context = new DerivativeContext( $context );
		return $context;
	}

	/**
	 * Given the id of an interface element, constructs the appropriate title
	 * attribute from the system messages.  (Note, this is usually the id but
	 * isn't always, because sometimes the accesskey needs to go on a different
	 * element than the id, for reverse-compatibility, etc.)
	 *
	 * @since 1.16.3 $msgParams added in 1.27
	 * @param string $name Id of the element, minus prefixes.
	 * @param string|array|null $options Null, string or array with some of the following options:
	 *   - 'withaccess' to add an access-key hint
	 *   - 'nonexisting' to add an accessibility hint that page does not exist
	 * @param array $msgParams Parameters to pass to the message
	 * @param MessageLocalizer|null $localizer
	 *
	 * @return string|false Contents of the title attribute (which you must HTML-
	 *   escape), or false for no title attribute
	 */
	public static function titleAttrib( $name, $options = null, array $msgParams = [], $localizer = null ) {
		if ( !$localizer ) {
			$localizer = self::getContextFromMain();
		}
		$message = $localizer->msg( "tooltip-$name", $msgParams );
		if ( $message->isDisabled() ) {
			$tooltip = false;
		} else {
			$tooltip = $message->text();
			# Compatibility: formerly some tooltips had [alt-.] hardcoded
			$tooltip = preg_replace( "/ ?\[alt-.\]$/", '', $tooltip );
		}

		$options = (array)$options;

		if ( in_array( 'nonexisting', $options ) ) {
			$tooltip = $localizer->msg( 'red-link-title', $tooltip ?: '' )->text();
		}
		if ( in_array( 'withaccess', $options ) ) {
			$accesskey = self::accesskey( $name, $localizer );
			if ( $accesskey !== false ) {
				// Should be build the same as in jquery.accessKeyLabel.js
				if ( $tooltip === false || $tooltip === '' ) {
					$tooltip = $localizer->msg( 'brackets', $accesskey )->text();
				} else {
					$tooltip .= $localizer->msg( 'word-separator' )->text();
					$tooltip .= $localizer->msg( 'brackets', $accesskey )->text();
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
	 * @param MessageLocalizer|null $localizer
	 * @return string|false Contents of the accesskey attribute (which you must HTML-
	 *   escape), or false for no accesskey attribute
	 */
	public static function accesskey( $name, $localizer = null ) {
		if ( !isset( self::$accesskeycache[$name] ) ) {
			if ( !$localizer ) {
				$localizer = self::getContextFromMain();
			}
			$msg = $localizer->msg( "accesskey-$name" );
			self::$accesskeycache[$name] = $msg->isDisabled() ? false : $msg->plain();
		}
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
	 * @param Authority $performer
	 * @param RevisionRecord $revRecord (Switched from the old Revision class to RevisionRecord
	 *    since 1.35)
	 * @param LinkTarget $title
	 * @return string HTML fragment
	 */
	public static function getRevDeleteLink(
		Authority $performer,
		RevisionRecord $revRecord,
		LinkTarget $title
	) {
		$canHide = $performer->isAllowed( 'deleterevision' );
		$canHideHistory = $performer->isAllowed( 'deletedhistory' );
		if ( !$canHide && !( $revRecord->getVisibility() && $canHideHistory ) ) {
			return '';
		}

		if ( !$revRecord->userCan( RevisionRecord::DELETED_RESTRICTED, $performer ) ) {
			return self::revDeleteLinkDisabled( $canHide ); // revision was hidden from sysops
		}
		$prefixedDbKey = MediaWikiServices::getInstance()->getTitleFormatter()->
			getPrefixedDBkey( $title );
		if ( $revRecord->getId() ) {
			// RevDelete links using revision ID are stable across
			// page deletion and undeletion; use when possible.
			$query = [
				'type' => 'revision',
				'target' => $prefixedDbKey,
				'ids' => $revRecord->getId()
			];
		} else {
			// Older deleted entries didn't save a revision ID.
			// We have to refer to these by timestamp, ick!
			$query = [
				'type' => 'archive',
				'target' => $prefixedDbKey,
				'ids' => $revRecord->getTimestamp()
			];
		}
		return self::revDeleteLink(
			$query,
			$revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED ),
			$canHide
		);
	}

	/**
	 * Creates a (show/hide) link for deleting revisions/log entries
	 *
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
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
	 * This method produces HTML that requires CSS styles in mediawiki.interface.helpers.styles.
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

	/**
	 * Updates the tooltip message and its parameters if watchlist expiry is enabled.
	 *
	 * @param string &$tooltip the default tooltip
	 * @param array &$msgParams the tooltip message parameters.
	 * @param Config|null $config Only needed for generating tooltip for watchlist expiry.
	 * @param User|null $user Only needed for generating tooltip for watchlist expiry.
	 * @param Title|null $relevantTitle Only needed for generating tooltip for watchlist expiry.
	 *
	 * @return void
	 */
	private static function updateWatchstarTooltipMessage(
		string &$tooltip, array &$msgParams, $config, $user, $relevantTitle
	): void {
		if ( !$config || !$user || !$relevantTitle ) {
			$mainContext = self::getContextFromMain();
			if ( !$config ) {
				$config = $mainContext->getConfig();
			}
			if ( !$user ) {
				$user = $mainContext->getUser();
			}
			if ( !$relevantTitle ) {
				$relevantTitle = $mainContext->getSkin()->getRelevantTitle();
			}
		}

		$isWatchlistExpiryEnabled = $config->get( MainConfigNames::WatchlistExpiry );
		if ( !$isWatchlistExpiryEnabled || !$relevantTitle || !$relevantTitle->canExist() ) {
			return;
		}

		$watchStore = MediaWikiServices::getInstance()->getWatchedItemStore();
		$watchedItem = $watchStore->getWatchedItem( $user, $relevantTitle );
		if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() !== null ) {
			$diffInDays = $watchedItem->getExpiryInDays();

			if ( $diffInDays ) {
				$msgParams = [ $diffInDays ];
				// Resolves to tooltip-ca-unwatch-expiring message
				$tooltip = 'ca-unwatch-expiring';
			} else { // Resolves to tooltip-ca-unwatch-expiring-hours message
				$tooltip = 'ca-unwatch-expiring-hours';
			}
		}
	}

	/**
	 * Returns the attributes for the tooltip and access key.
	 *
	 * @since 1.16.3. $msgParams introduced in 1.27
	 * @param string $name
	 * @param array $msgParams Params for constructing the message
	 * @param string|array|null $options Options to be passed to titleAttrib.
	 * @param MessageLocalizer|null $localizer
	 * @param User|null $user Only needed for generating tooltip for watchlist expiry.
	 * @param Config|null $config Only needed for generating tooltip for watchlist expiry.
	 * @param Title|null $relevantTitle Only needed for generating tooltip for watchlist expiry.
	 *
	 * @see Linker::titleAttrib for what options could be passed to $options.
	 *
	 * @return array
	 */
	public static function tooltipAndAccesskeyAttribs(
		$name,
		array $msgParams = [],
		$options = null,
		$localizer = null,
		$user = null,
		$config = null,
		$relevantTitle = null
	) {
		$options = (array)$options;
		$options[] = 'withaccess';
		$tooltipTitle = $name;

		// Get optional parameters from global context if any missing.
		if ( !$localizer ) {
			$localizer = self::getContextFromMain();
		}

		// @since 1.35 - add a WatchlistExpiry feature flag to show new watchstar tooltip message
		if ( $name === 'ca-unwatch' ) {
			self::updateWatchstarTooltipMessage( $tooltipTitle, $msgParams, $config, $user, $relevantTitle );
		}

		$attribs = [
			'title' => self::titleAttrib( $tooltipTitle, $options, $msgParams, $localizer ),
			'accesskey' => self::accesskey( $name, $localizer )
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
		$tooltip = self::titleAttrib( $name, $options );
		if ( $tooltip === false ) {
			return '';
		}
		return Xml::expandAttributes( [
			'title' => $tooltip
		] );
	}

}
