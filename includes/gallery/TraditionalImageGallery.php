<?php

use MediaWiki\FileRepo\File\File;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parser;
use MediaWiki\Title\Title;
use Wikimedia\Assert\Assert;

/**
 * Image gallery.
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

class TraditionalImageGallery extends ImageGalleryBase {
	/**
	 * Return a HTML representation of the image gallery
	 *
	 * For each image in the gallery, display
	 * - a thumbnail
	 * - the image name
	 * - the additional text provided when adding the image
	 * - the size of the image
	 *
	 * @return string
	 */
	public function toHTML() {
		$resolveFilesViaParser = $this->mParser instanceof Parser;
		if ( $resolveFilesViaParser ) {
			$parserOutput = $this->mParser->getOutput();
			$repoGroup = null;
			$linkRenderer = $this->mParser->getLinkRenderer();
			$badFileLookup = $this->mParser->getBadFileLookup();
		} else {
			$parserOutput = $this->getOutput();
			$services = MediaWikiServices::getInstance();
			$repoGroup = $services->getRepoGroup();
			$linkRenderer = $services->getLinkRenderer();
			$badFileLookup = $services->getBadFileLookup();
		}

		Html::addClass( $this->mAttribs['class'], 'gallery' );
		Html::addClass( $this->mAttribs['class'], 'mw-gallery-' . $this->mMode );

		if ( $this->mPerRow > 0 ) {
			$maxwidth = $this->mPerRow * ( $this->mWidths + $this->getAllPadding() );
			$oldStyle = $this->mAttribs['style'] ?? '';
			$this->mAttribs['style'] = "max-width: {$maxwidth}px;" . $oldStyle;
		}

		$parserOutput->addModules( $this->getModules() );
		$parserOutput->addModuleStyles( [ 'mediawiki.page.gallery.styles' ] );
		$output = Html::openElement( 'ul', $this->mAttribs );
		if ( $this->mCaption ) {
			$output .= "\n\t" . Html::rawElement( 'li', [ 'class' => 'gallerycaption' ], $this->mCaption );
		}

		if ( $this->mShowFilename ) {
			// Preload LinkCache info for when generating links
			// of the filename below
			$linkBatchFactory = MediaWikiServices::getInstance()->getLinkBatchFactory();
			$lb = $linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
			foreach ( $this->mImages as [ $title, /* see below */ ] ) {
				$lb->addObj( $title );
			}
			$lb->execute();
		}

		$lang = $this->getRenderLang();
		$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );

		# Output each image...
		foreach ( $this->mImages as [ $nt, $text, $alt, $link, $handlerOpts, $loading, $imageOptions ] ) {
			// "text" means "caption" here
			/** @var Title $nt */

			$descQuery = false;
			if ( $nt->inNamespace( NS_FILE ) && !$nt->isExternal() ) {
				# Get the file...
				if ( $resolveFilesViaParser ) {
					# Give extensions a chance to select the file revision for us
					$options = [];
					$hookRunner->onBeforeParserFetchFileAndTitle(
						// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
						$this->mParser, $nt, $options, $descQuery );
					# Fetch and register the file (file title may be different via hooks)
					[ $img, $nt ] = $this->mParser->fetchFileAndTitle( $nt, $options );
				} else {
					$img = $repoGroup->findFile( $nt );
				}
			} else {
				$img = false;
			}

			$transformOptions = $this->getThumbParams( $img ) + $handlerOpts;
			$thumb = $img ? $img->transform( $transformOptions ) : false;

			$rdfaType = 'mw:File';

			$isBadFile = $img && $thumb && $this->mHideBadImages &&
				$badFileLookup->isBadFile( $nt->getDBkey(), $this->getContextTitle() );

			if ( !$img || !$thumb || $thumb->isError() || $isBadFile ) {
				$rdfaType = 'mw:Error ' . $rdfaType;

				$currentExists = $img && $img->exists();
				if ( $currentExists && !$thumb ) {
					$label = wfMessage( 'thumbnail_error', '' )->text();
				} elseif ( $thumb && $thumb->isError() ) {
					Assert::invariant(
						$thumb instanceof MediaTransformError,
						'Unknown MediaTransformOutput: ' . get_class( $thumb )
					);
					$label = $thumb->toText();
				} else {
					$label = $alt ?? '';
				}
				$thumbhtml = Linker::makeBrokenImageLinkObj(
					$nt, $label, '', '', '', false, $transformOptions, $currentExists
				);
				$thumbhtml = Html::rawElement( 'span', [ 'typeof' => $rdfaType ], $thumbhtml );

				$thumbhtml = "\n\t\t\t" . Html::rawElement(
					'div',
					[
						'class' => 'thumb',
						'style' => 'height: ' . ( $this->getThumbPadding() + $this->mHeights ) . 'px;'
					],
					$thumbhtml
				);

				if ( !$img && $resolveFilesViaParser ) {
					$this->mParser->addTrackingCategory( 'broken-file-category' );
				}
			} else {
				/** @var MediaTransformOutput $thumb */
				$vpad = $this->getVPad( $this->mHeights, $thumb->getHeight() );

				// Backwards compat before the $imageOptions existed
				if ( $imageOptions === null ) {
					$imageParameters = [
						'desc-link' => true,
						'desc-query' => $descQuery,
						'alt' => $alt ?? '',
						'custom-url-link' => $link
					];
				} else {
					$params = [];
					// An empty alt indicates an image is not a key part of the
					// content and that non-visual browsers may omit it from
					// rendering.  Only set the parameter if it's explicitly
					// requested.
					if ( $alt !== null ) {
						$params['alt'] = $alt;
					}
					$params['title'] = $imageOptions['title'];
					$params['img-class'] = 'mw-file-element';
					$imageParameters = Linker::getImageLinkMTOParams(
						$imageOptions, $descQuery, $this->mParser
					) + $params;
				}

				if ( $loading === ImageGalleryBase::LOADING_LAZY ) {
					$imageParameters['loading'] = 'lazy';
				}

				$this->adjustImageParameters( $thumb, $imageParameters );

				Linker::processResponsiveImages( $img, $thumb, $transformOptions );

				$thumbhtml = $thumb->toHtml( $imageParameters );
				$thumbhtml = Html::rawElement(
					'span', [ 'typeof' => $rdfaType ], $thumbhtml
				);

				# Set both fixed width and min-height.
				$width = $this->getThumbDivWidth( $thumb->getWidth() );
				$height = $this->getThumbPadding() + $this->mHeights;
				$thumbhtml = "\n\t\t\t" . Html::rawElement( 'div', [
					'class' => 'thumb',
					'style' => "width: {$width}px;" .
						( $this->mMode === 'traditional' ? " height: {$height}px;" : '' ),
				], $thumbhtml );

				// Call parser transform hook
				if ( $resolveFilesViaParser ) {
					/** @var MediaHandler $handler */
					$handler = $img->getHandler();
					if ( $handler ) {
						$handler->parserTransformHook( $this->mParser, $img );
					}
					$this->mParser->modifyImageHtml(
						$img, [ 'handler' => $imageParameters ], $thumbhtml );
				}
			}

			$meta = [];
			if ( $img ) {
				if ( $this->mShowDimensions ) {
					$meta[] = htmlspecialchars( $img->getDimensionsString() );
				}
				if ( $this->mShowBytes ) {
					$meta[] = htmlspecialchars( $lang->formatSize( $img->getSize() ) );
				}
			} elseif ( $this->mShowDimensions || $this->mShowBytes ) {
				$meta[] = $this->msg( 'filemissing' )->escaped();
			}
			$meta = $lang->semicolonList( $meta );
			if ( $meta ) {
				$meta .= Html::rawElement( 'br', [] ) . "\n";
			}

			$textlink = $this->mShowFilename ?
				$this->getCaptionHtml( $nt, $lang, $linkRenderer ) :
				'';

			$galleryText = $this->wrapGalleryText( $textlink . $text . $meta, $thumb );

			$gbWidth = $this->getGBWidthOverwrite( $thumb ) ?: $this->getGBWidth( $thumb ) . 'px';
			# Weird double wrapping (the extra div inside the li) needed due to FF2 bug
			# Can be safely removed if FF2 falls completely out of existence
			$output .= "\n\t\t" .
			Html::rawElement(
				'li',
				[ 'class' => 'gallerybox', 'style' => 'width: ' . $gbWidth ],
				$thumbhtml
					. $galleryText
					. "\n\t\t"
			);
		}
		$output .= "\n" . Html::closeElement( 'ul' );

		return $output;
	}

	/**
	 * @param Title $nt
	 * @param Language $lang
	 * @param LinkRenderer $linkRenderer
	 * @return string HTML
	 */
	protected function getCaptionHtml( Title $nt, Language $lang, LinkRenderer $linkRenderer ) {
		// Preloaded into LinkCache in toHTML
		return $linkRenderer->makeKnownLink(
			$nt,
			is_int( $this->getCaptionLength() ) ?
				$lang->truncateForVisual( $nt->getText(), $this->getCaptionLength() ) :
				$nt->getText(),
			[
				'class' => 'galleryfilename' .
					( $this->getCaptionLength() === true ? ' galleryfilename-truncate' : '' )
			]
		) . "\n";
	}

	/**
	 * Add the wrapper html around the thumb's caption
	 *
	 * @param string $galleryText The caption
	 * @param MediaTransformOutput|false $thumb The thumb this caption is for
	 *   or false for bad image.
	 * @return string
	 */
	protected function wrapGalleryText( $galleryText, $thumb ) {
		return "\n\t\t\t" . Html::rawElement( 'div', [ 'class' => "gallerytext" ], $galleryText );
	}

	/**
	 * How much padding the thumb has between the image and the inner div
	 * that contains the border. This is for both vertical and horizontal
	 * padding. (However, it is cut in half in the vertical direction).
	 * @return int
	 */
	protected function getThumbPadding() {
		return 30;
	}

	/**
	 * @note GB stands for gallerybox (as in the <li class="gallerybox"> element)
	 *
	 * @return int
	 */
	protected function getGBPadding() {
		return 5;
	}

	/**
	 * Get how much extra space the borders around the image takes up.
	 *
	 * For this mode, it is 2px borders on each side + 2px implied padding on
	 * each side from the stylesheet, giving us 2*2+2*2 = 8.
	 * @return int
	 */
	protected function getGBBorders() {
		return 8;
	}

	/**
	 * Length (in characters) to truncate filename to in caption when using "showfilename" (if int).
	 * A value of 'true' will truncate the filename to one line using CSS, while
	 * 'false' will disable truncating.
	 *
	 * @return int|bool
	 */
	protected function getCaptionLength() {
		return $this->mCaptionLength;
	}

	/**
	 * Get total padding.
	 *
	 * @return int Number of pixels of whitespace surrounding the thumbnail.
	 */
	protected function getAllPadding() {
		return $this->getThumbPadding() + $this->getGBPadding() + $this->getGBBorders();
	}

	/**
	 * Get vertical padding for a thumbnail
	 *
	 * Generally this is the total height minus how high the thumb is.
	 *
	 * @param int $boxHeight How high we want the box to be.
	 * @param int $thumbHeight How high the thumbnail is.
	 * @return float Vertical padding to add on each side.
	 */
	protected function getVPad( $boxHeight, $thumbHeight ) {
		return ( $this->getThumbPadding() + $boxHeight - $thumbHeight ) / 2;
	}

	/**
	 * Get the transform parameters for a thumbnail.
	 *
	 * @param File|false $img The file in question. May be false for invalid image
	 * @return array
	 */
	protected function getThumbParams( $img ) {
		return [
			'width' => $this->mWidths,
			'height' => $this->mHeights
		];
	}

	/**
	 * Get the width of the inner div that contains the thumbnail in
	 * question. This is the div with the class of "thumb".
	 *
	 * @param int $thumbWidth The width of the thumbnail.
	 * @return float Width of inner thumb div.
	 */
	protected function getThumbDivWidth( $thumbWidth ) {
		return $this->mWidths + $this->getThumbPadding();
	}

	/**
	 * Computed width of gallerybox <li>.
	 *
	 * Generally is the width of the image, plus padding on image
	 * plus padding on gallerybox.
	 *
	 * @note Important: parameter will be false if no thumb used.
	 * @param MediaTransformOutput|false $thumb
	 * @return float Width of gallerybox element
	 */
	protected function getGBWidth( $thumb ) {
		return $this->mWidths + $this->getThumbPadding() + $this->getGBPadding();
	}

	/**
	 * Allows overwriting the computed width of the gallerybox <li> with a string,
	 * like '100%'.
	 *
	 * Generally is the width of the image, plus padding on image
	 * plus padding on gallerybox.
	 *
	 * @note Important: parameter will be false if no thumb used.
	 * @param MediaTransformOutput|false $thumb
	 * @return string|false Ignored if false.
	 */
	protected function getGBWidthOverwrite( $thumb ) {
		return false;
	}

	/**
	 * Get a list of modules to include in the page.
	 *
	 * Primarily intended for subclasses.
	 *
	 * @return array Modules to include
	 */
	protected function getModules() {
		return [];
	}

	/**
	 * Adjust the image parameters for a thumbnail.
	 *
	 * Used by a subclass to insert extra high resolution images.
	 * @param MediaTransformOutput $thumb The thumbnail
	 * @param array &$imageParameters Array of options
	 */
	protected function adjustImageParameters( $thumb, &$imageParameters ) {
	}
}
