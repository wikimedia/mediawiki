<?php
/**
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
namespace MediaWiki\Linker;

use DummyLinker;
use File;
use Hooks;
use Html;
use HtmlArmor;
use Linker;
use MediaWiki\MediaWikiServices;
use Parser;
use RepoGroup;
use Sanitizer;
use SpecialPage;
use Title;
use TitleFormatter;
use User;

/**
 * @since 1.28
 */
class FileLinkRenderer {

	/**
	 * @var LinkRenderer
	 */
	private $linkRenderer;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	public function __construct( LinkRenderer $linkRenderer, TitleFormatter $titleFormatter ) {
		$this->linkRenderer = $linkRenderer;
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * Make a "broken" link to an file
	 *
	 * @param LinkTarget $target
	 * @param string $label Link label (plain text)
	 * @param array $query Query parameters
	 * @param bool $time A file of a certain timestamp was requested
	 * @return string
	 */
	public function makeBrokenFileLink( LinkTarget $target, $label = '',
		$query = [], $time = false
	) {
		global $wgEnableUploads, $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		if ( $label == '' ) {
			$label = $this->titleFormatter->getPrefixedText( $target );
		}
		$encLabel = htmlspecialchars( $label );
		$title = Title::newFromLinkTarget( $target );
		$currentExists = $time ? ( wfFindFile( $title ) != false ) : false;

		if ( ( $wgUploadMissingFileUrl || $wgUploadNavigationUrl || $wgEnableUploads )
			&& !$currentExists
		) {
			$redir = RepoGroup::singleton()->getLocalRepo()->checkRedirect( $title );

			if ( $redir ) {
				// We already know it's a redirect, so mark it
				// accordingly
				return $this->linkRenderer->makePreloadedLink(
					$target,
					$label,
					[ 'mw-redirect' ],
					[],
					$query
				);
			}

			$href = $this->getUploadUrl( $title, $query );

			// FIXME: This should not be constructed manually
			return '<a href="' . htmlspecialchars( $href ) . '" class="new" title="' .
			htmlspecialchars( $title->getPrefixedText(), ENT_QUOTES ) . '">' .
			$encLabel . '</a>';
		}

		// We know that no classes will apply
		return $this->linkRenderer->makePreloadedLink( $target, $label, [], $query );
	}

	/**
	 * @param Title $title
	 * @param File|bool $file
	 * @param array $frameParams
	 * @param array $handlerParams
	 * @param bool $time
	 * @param array $query
	 * @return string
	 */
	public function makeThumbLink( Title $title, $file, array $frameParams = [],
		array $handlerParams = [], $time = false, array $query = []
	) {
		$exists = $file && $file->exists();

		// Are we displaying only one page out of multiple? (e.g. PDF, DjVu)
		$page = isset( $handlerParams['page'] ) ? $handlerParams['page'] : false;
		// Set defaults
		$frameParams += [
			'align' => 'right',
			'alt' => '',
			'title' => '',
			'caption' => '',
		];

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
		# zoom icon still needs it, so we make a unique query for it. See bug 14771
		if ( $page ) {
			$zoomQuery = $query + [ 'page' => $page ];
		} else {
			$zoomQuery = $query;
		}

		if ( $manualthumb
			&& !isset( $frameParams['link-title'] )
			&& !isset( $frameParams['link-url'] )
			&& !isset( $frameParams['no-link'] )
		) {
			$frameParams['link-url'] = $this->linkRenderer->getLinkURL( $title, $query );
		}

		$s = "<div class=\"thumb t{$frameParams['align']}\">"
			. "<div class=\"thumbinner\" style=\"width:{$outerWidth}px;\">";

		if ( !$exists ) {
			$s .= $this->makeBrokenFileLink( $title, $frameParams['title'], [], $time == true );
			$zoomIcon = '';
		} elseif ( !$thumb ) {
			$s .= wfMessage( 'thumbnail_error', '' )->escaped();
			$zoomIcon = '';
		} else {
			if ( !$noscale && !$manualthumb ) {
				Linker::processResponsiveImages( $file, $thumb, $handlerParams );
			}
			$imgClass = '';
			if ( isset( $frameParams['class'] ) && $frameParams['class'] !== '' ) {
				$imgClass = $frameParams['class'] . ' ';
			}
			// And always set thumbimage class.
			$imgClass .= 'thumbimage';
			$params = [
				'alt' => $frameParams['alt'],
				'title' => $frameParams['title'],
				'img-class' => $imgClass
			];
			$params = Linker::getImageLinkMTOParams( $frameParams, wfArrayToCgi( $query ) ) + $params;
			$s .= $thumb->toHtml( $params );
			if ( isset( $frameParams['framed'] ) ) {
				$zoomIcon = "";
			} else {
				$zoomIcon = Html::rawElement( 'div', [ 'class' => 'magnify' ],
					$this->linkRenderer->makePreloadedLink(
						$title,
						'',
						'internal',
						[ 'title' => wfMessage( 'thumbnail-more' )->text() ],
						$zoomQuery
					)
				);
			}
		}
		$s .= '  <div class="thumbcaption">' . $zoomIcon . $frameParams['caption'] . "</div></div></div>";
		return str_replace( "\n", ' ', $s );
	}

	/**
	 * Get the URL to upload a certain file
	 *
	 * @since 1.16.3
	 * @private Only public for use in Linker
	 * @param Title $destFile Title object of the file to upload
	 * @param array $query query values to append
	 * @return string Urlencoded URL
	 */
	public function getUploadUrl( $destFile, array $query ) {
		global $wgUploadMissingFileUrl, $wgUploadNavigationUrl;
		// wpDestFile will not be URL-encoded, so use getPartialURL(), which already is
		$encodedQuery = [ 'wpDestFile' => $destFile->getPartialURL() ] + $query;

		if ( $wgUploadMissingFileUrl ) {
			return wfAppendQuery( $wgUploadMissingFileUrl, $encodedQuery );
		} elseif ( $wgUploadNavigationUrl ) {
			return wfAppendQuery( $wgUploadNavigationUrl, $encodedQuery );
		} else {
			return $this->linkRenderer->getLinkURL(
				SpecialPage::getTitleValueFor( 'Upload' ),
				// This will be URL-encoded, so just use the dbkey
				[ 'wpDestFile' => $destFile->getDBkey() ] + $query
			);
		}
	}

}
