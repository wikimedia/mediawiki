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

namespace MediaWiki\Page;

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\FileRepo;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Linker\Linker;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use stdClass;
use UploadBase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Rendering of file description pages.
 *
 * @ingroup Media
 * @method WikiFilePage getPage()
 */
class ImagePage extends Article {
	use \MediaWiki\FileRepo\File\MediaFileTrait;

	/** @var File|false Only temporary false, most code can assume this is a File */
	private $displayImg;

	/** @var FileRepo */
	private $repo;

	/** @var bool */
	private $fileLoaded;

	/** @var string|false Guaranteed to be HTML, {@see File::getDescriptionText} */
	protected $mExtraDescription = false;

	/**
	 * @param Title $title
	 * @return WikiFilePage
	 */
	protected function newPage( Title $title ) {
		// Overload mPage with a file-specific page
		return new WikiFilePage( $title );
	}

	/**
	 * @param File $file
	 * @return void
	 */
	public function setFile( $file ) {
		$this->getPage()->setFile( $file );
		$this->displayImg = $file;
		$this->fileLoaded = true;
	}

	protected function loadFile() {
		if ( $this->fileLoaded ) {
			return;
		}
		$this->fileLoaded = true;

		$this->displayImg = $img = false;

		$this->getHookRunner()->onImagePageFindFile( $this, $img, $this->displayImg );
		if ( !$img ) { // not set by hook?
			$services = MediaWikiServices::getInstance();
			$img = $services->getRepoGroup()->findFile( $this->getTitle() );
			if ( !$img ) {
				$img = $services->getRepoGroup()->getLocalRepo()->newFile( $this->getTitle() );
			}
		}
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable should be set
		$this->getPage()->setFile( $img );
		if ( !$this->displayImg ) { // not set by hook?
			// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty should be set
			$this->displayImg = $img;
		}
		$this->repo = $img->getRepo();
	}

	public function view() {
		$context = $this->getContext();
		$showEXIF = $context->getConfig()->get( MainConfigNames::ShowEXIF );

		// For action=render, include body text only; none of the image extras
		if ( $this->viewIsRenderAction ) {
			parent::view();
			return;
		}

		$out = $context->getOutput();
		$request = $context->getRequest();
		$diff = $request->getVal( 'diff' );

		if ( $this->getTitle()->getNamespace() !== NS_FILE || ( $diff !== null && $this->isDiffOnlyView() ) ) {
			parent::view();
			return;
		}

		$this->loadFile();

		if (
			$this->getTitle()->getNamespace() === NS_FILE
			&& $this->getFile()->getRedirected()
		) {
			if (
				$this->getTitle()->getDBkey() == $this->getFile()->getName()
				|| $diff !== null
			) {
				$request->setVal( 'diffonly', 'true' );
			}

			parent::view();
			return;
		}

		if ( $showEXIF && $this->displayImg->exists() ) {
			// @todo FIXME: Bad interface, see note on MediaHandler::formatMetadata().
			$formattedMetadata = $this->displayImg->formatMetadata( $this->getContext() );
		} else {
			$formattedMetadata = false;
		}

		if ( !$diff && $this->displayImg->exists() ) {
			$out->addHTML( $this->showTOC( (bool)$formattedMetadata ) );
		}

		if ( !$diff ) {
			$this->openShowImage();
		}

		# No need to display noarticletext, we use our own message, output in openShowImage()
		if ( $this->getPage()->getId() ) {
			$out->addHTML( Html::openElement( 'div', [ 'id' => 'mw-imagepage-content' ] ) );
			// NS_FILE pages render mostly in the user language (like special pages),
			// except the editable wikitext content, which is rendered in the page content
			// language by the parent class.
			parent::view();
			$out->addHTML( Html::closeElement( 'div' ) );
		} else {
			# Just need to set the right headers
			$out->setArticleFlag( true );
			$out->setPageTitle( $this->getTitle()->getPrefixedText() );
			$this->getPage()->doViewUpdates(
				$context->getAuthority(),
				$this->getOldID()
			);
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = $context->msg( 'shareddescriptionfollows' );
			if ( !$fol->isDisabled() ) {
				$out->addWikiTextAsInterface( $fol->plain() );
			}
			$out->addHTML(
				Html::rawElement(
					'div',
					[ 'id' => 'shared-image-desc' ],
					$this->mExtraDescription
				) . "\n"
			);
		}

		$this->closeShowImage();
		$this->imageHistory();
		// TODO: Cleanup the following

		$out->addHTML( Html::element(
			'h2',
			[ 'id' => 'filelinks' ],
			$context->msg( 'imagelinks' )->text() ) . "\n"
		);
		$this->imageDupes();
		# @todo FIXME: For some freaky reason, we can't redirect to foreign images.
		# Yet we return metadata about the target. Definitely an issue in the FileRepo
		$this->imageLinks();

		# Allow extensions to add something after the image links
		$html = '';
		$this->getHookRunner()->onImagePageAfterImageLinks( $this, $html );
		if ( $html ) {
			$out->addHTML( $html );
		}

		if ( $formattedMetadata ) {
			$out->addHTML(
				Html::element(
					'h2',
					[ 'id' => 'metadata' ],
					$context->msg( 'metadata' )->text()
				) . "\n"
			);
			$out->wrapWikiTextAsInterface(
				'mw-imagepage-section-metadata',
				$this->makeMetadataTable( $formattedMetadata )
			);
			$out->addModules( [ 'mediawiki.action.view.metadata' ] );
		}

		// Add remote Filepage.css
		if ( !$this->repo->isLocal() ) {
			$css = $this->repo->getDescriptionStylesheetUrl();
			if ( $css ) {
				$out->addStyle( $css );
			}
		}

		$out->addModuleStyles( [
			'mediawiki.action.view.filepage', // Add MediaWiki styles for a file page
		] );
	}

	/**
	 * @return File
	 */
	public function getDisplayedFile() {
		$this->loadFile();
		return $this->displayImg;
	}

	/**
	 * Create the TOC
	 *
	 * @param bool $metadata Whether or not to show the metadata link
	 * @return string
	 */
	protected function showTOC( $metadata ) {
		$r = [
			Html::rawElement(
				'li',
				[],
				Html::rawElement(
					'a',
					[ 'href' => '#file' ],
					$this->getContext()->msg( 'file-anchor-link' )->escaped()
				)
			),
			Html::rawElement(
				'li',
				[],
				Html::rawElement(
					'a',
					[ 'href' => '#filehistory' ],
					$this->getContext()->msg( 'filehist' )->escaped()
				)
			),
			Html::rawElement(
				'li',
				[],
				Html::rawElement(
					'a',
					[ 'href' => '#filelinks' ],
					$this->getContext()->msg( 'imagelinks' )->escaped()
				)
			),
		];

		$this->getHookRunner()->onImagePageShowTOC( $this, $r );

		if ( $metadata ) {
			$r[] = Html::rawElement(
				'li',
				[],
				Html::rawElement(
					'a',
					[ 'href' => '#metadata' ],
					$this->getContext()->msg( 'metadata' )->escaped()
				)
			);
		}

		return Html::rawElement( 'ul', [
			'id' => 'filetoc',
			'role' => 'navigation'
		], implode( "\n", $r ) );
	}

	/**
	 * Make a table with metadata to be shown in the output page.
	 *
	 * @todo FIXME: Bad interface, see note on MediaHandler::formatMetadata().
	 *
	 * @param array $metadata The array containing the Exif data
	 * @return string The metadata table. This is treated as Wikitext (!)
	 */
	protected function makeMetadataTable( $metadata ) {
		$r = $this->getContext()->msg( 'metadata-help' )->plain();
		// Initial state of collapsible rows is collapsed
		// see mediawiki.action.view.filepage.less and mediawiki.action.view.metadata module.
		$r .= "<table id=\"mw_metadata\" class=\"mw_metadata collapsed\">\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $v ) {
				$class = str_replace( ' ', '_', $v['id'] );
				if ( $type === 'collapsed' ) {
					$class .= ' mw-metadata-collapsible';
				}
				$r .= Html::rawElement( 'tr',
					[ 'class' => $class ],
					Html::rawElement( 'th', [], $v['name'] )
						. Html::rawElement( 'td', [], $v['value'] )
				);
			}
		}
		$r .= "</table>\n";
		return $r;
	}

	/**
	 * Returns language code to be used for displaying the image, based on request context and
	 * languages available in the file.
	 *
	 * @param WebRequest $request
	 * @param File $file
	 * @return string|null a valid IETF language tag
	 */
	private function getLanguageForRendering( WebRequest $request, File $file ) {
		$handler = $file->getHandler();
		if ( !$handler ) {
			return null;
		}

		$requestLanguage = $request->getVal( 'lang' );
		if ( $requestLanguage === null ) {
			// For on File pages about a translatable SVG, decide which
			// language to render the large thumbnail in (T310445)
			$services = MediaWikiServices::getInstance();
			$variantLangCode = $services->getLanguageConverterFactory()
				->getLanguageConverter( $services->getContentLanguage() )
				->getPreferredVariant();
			$requestLanguage = LanguageCode::bcp47( $variantLangCode );
		}
		if ( $handler->validateParam( 'lang', $requestLanguage ) ) {
			return $file->getMatchedLanguage( $requestLanguage );
		}

		return $handler->getDefaultRenderLanguage( $file );
	}

	protected function openShowImage() {
		$context = $this->getContext();
		$mainConfig = $context->getConfig();
		$enableUploads = $mainConfig->get( MainConfigNames::EnableUploads );
		$send404Code = $mainConfig->get( MainConfigNames::Send404Code );
		$svgMaxSize = $mainConfig->get( MainConfigNames::SVGMaxSize );
		$this->loadFile();
		$out = $context->getOutput();
		$user = $context->getUser();
		$lang = $context->getLanguage();
		$sitedir = MediaWikiServices::getInstance()->getContentLanguage()->getDir();
		$request = $context->getRequest();

		if ( $this->displayImg->exists() ) {
			[ $maxWidth, $maxHeight ] = $this->getImageLimitsFromOption( $user, 'imagesize' );

			# image
			$page = $request->getIntOrNull( 'page' );
			if ( $page === null ) {
				$params = [];
				$page = 1;
			} else {
				$params = [ 'page' => $page ];
			}

			$renderLang = $this->getLanguageForRendering( $request, $this->displayImg );
			if ( $renderLang !== null ) {
				$params['lang'] = $renderLang;
			}

			$width_orig = $this->displayImg->getWidth( $page );
			$width = $width_orig;
			$height_orig = $this->displayImg->getHeight( $page );
			$height = $height_orig;

			$filename = wfEscapeWikiText( $this->displayImg->getName() );
			$linktext = $filename;

			$this->getHookRunner()->onImageOpenShowImageInlineBefore( $this, $out );

			if ( $this->displayImg->allowInlineDisplay() ) {
				# image
				# "Download high res version" link below the image
				# $msgsize = $this->getContext()->msg( 'file-info-size', $width_orig, $height_orig,
				#   Language::formatSize( $this->displayImg->getSize() ), $mime )->escaped();
				# We'll show a thumbnail of this image
				if ( $width > $maxWidth ||
					$height > $maxHeight ||
					$this->displayImg->isVectorized()
				) {
					[ $width, $height ] = $this->displayImg->getDisplayWidthHeight(
						$maxWidth, $maxHeight, $page
					);
					$linktext = $context->msg( 'show-big-image' )->escaped();

					$thumbSizes = $this->getThumbSizes( $width_orig, $height_orig );
					# Generate thumbnails or thumbnail links as needed...
					$otherSizes = [];
					foreach ( $thumbSizes as $size ) {
						// We include a thumbnail size in the list, if it is
						// less than or equal to the original size of the image
						// asset ($width_orig/$height_orig). We also exclude
						// the current thumbnail's size ($width/$height)
						// since that is added to the message separately, so
						// it can be denoted as the current size being shown.
						// Vectorized images are limited by $wgSVGMaxSize big,
						// so all thumbs less than or equal that are shown.
						if ( ( ( $size[0] <= $width_orig && $size[1] <= $height_orig )
								|| ( $this->displayImg->isVectorized()
									&& max( $size[0], $size[1] ) <= $svgMaxSize )
							)
							&& $size[0] != $width && $size[1] != $height
							&& $size[0] != $maxWidth && $size[1] != $maxHeight
						) {
							$sizeLink = $this->makeSizeLink( $params, $size[0], $size[1] );
							if ( $sizeLink ) {
								$otherSizes[] = $sizeLink;
							}
						}
					}
					$otherSizes = array_unique( $otherSizes );

					$sizeLinkBigImagePreview = $this->makeSizeLink( $params, $width, $height );
					$msgsmall = $this->getThumbPrevText( $params, $sizeLinkBigImagePreview );
					if ( count( $otherSizes ) ) {
						$msgsmall .= ' ' .
						Html::rawElement(
							'span',
							[ 'class' => 'mw-filepage-other-resolutions' ],
							$context->msg( 'show-big-image-other' )
								->rawParams( $lang->pipeList( $otherSizes ) )
								->params( count( $otherSizes ) )
								->parse()
						);
					}
				} elseif ( $width == 0 && $height == 0 ) {
					# Some sort of audio file that doesn't have dimensions
					# Don't output a no hi res message for such a file
					$msgsmall = '';
				} else {
					# Image is small enough to show full size on image page
					$msgsmall = $this->getContext()->msg( 'file-nohires' )->parse();
				}

				$params['width'] = $width;
				$params['height'] = $height;
				$params['isFilePageThumb'] = true;
				// Allow the MediaHandler to handle query string parameters on the file page,
				// e.g. start time for videos (T203994)
				$params['imagePageParams'] = $request->getQueryValuesOnly();
				$thumbnail = $this->displayImg->transform( $params );
				Linker::processResponsiveImages( $this->displayImg, $thumbnail, $params );

				$anchorclose = Html::rawElement(
					'div',
					[ 'class' => 'mw-filepage-resolutioninfo' ],
					$msgsmall
				);

				$isMulti = $this->displayImg->isMultipage() && $this->displayImg->pageCount() > 1;
				if ( $isMulti ) {
					$out->addModules( 'mediawiki.page.image.pagination' );
					/* TODO: multipageimage class is deprecated since Jan 2023 */
					$out->addHTML( '<div class="mw-filepage-multipage multipageimage">' );
				}

				if ( $thumbnail ) {
					$options = [
						'alt' => $this->displayImg->getTitle()->getPrefixedText(),
						'file-link' => true,
					];
					$out->addHTML(
						Html::rawElement(
							'div',
							[ 'class' => 'fullImageLink', 'id' => 'file' ],
							$thumbnail->toHtml( $options ) . $anchorclose
						) . "\n"
					);
				}

				if ( $isMulti ) {
					$linkPrev = $linkNext = '';
					$count = $this->displayImg->pageCount();
					$out->addModules( 'mediawiki.page.media' );

					if ( $page > 1 ) {
						$label = $context->msg( 'imgmultipageprev' )->text();
						// on the client side, this link is generated in ajaxifyPageNavigation()
						// in the mediawiki.page.image.pagination module
						$linkPrev = $this->linkRenderer->makeKnownLink(
							$this->getTitle(),
							$label,
							[],
							[ 'page' => $page - 1 ]
						);
						$thumbPrevPage = Linker::makeThumbLinkObj(
							$this->getTitle(),
							$this->displayImg,
							$linkPrev,
							$label,
							'none',
							[ 'page' => $page - 1, 'isFilePageThumb' => true ]
						);
					} else {
						$thumbPrevPage = '';
					}

					if ( $page < $count ) {
						$label = $context->msg( 'imgmultipagenext' )->text();
						$linkNext = $this->linkRenderer->makeKnownLink(
							$this->getTitle(),
							$label,
							[],
							[ 'page' => $page + 1 ]
						);
						$thumbNextPage = Linker::makeThumbLinkObj(
							$this->getTitle(),
							$this->displayImg,
							$linkNext,
							$label,
							'none',
							[ 'page' => $page + 1, 'isFilePageThumb' => true ]
						);
					} else {
						$thumbNextPage = '';
					}

					$script = $mainConfig->get( MainConfigNames::Script );

					$formParams = [
						'name' => 'pageselector',
						'action' => $script,
					];
					$options = [];
					for ( $i = 1; $i <= $count; $i++ ) {
						$options[] = Html::element(
							'option',
							[ 'value' => (string)$i, 'selected' => $i == $page ],
							$lang->formatNum( $i )
						);
					}
					$select = Html::rawElement( 'select',
						[ 'id' => 'pageselector', 'name' => 'page' ],
						implode( "\n", $options ) );

					/* TODO: multipageimagenavbox class is deprecated since Jan 2023 */
					$out->addHTML(
						'<div class="mw-filepage-multipage-navigation multipageimagenavbox">' .
						$linkPrev .
						Html::rawElement( 'form', $formParams,
							Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
							$context->msg( 'imgmultigoto' )->rawParams( $select )->parse() .
							$context->msg( 'word-separator' )->escaped() .
							Html::submitButton( $context->msg( 'imgmultigo' )->text() )
						) .
						"$thumbPrevPage\n$thumbNextPage\n$linkNext</div></div>"
					);
				}
			} elseif ( $this->displayImg->isSafeFile() ) {
				# if direct link is allowed but it's not a renderable image, show an icon.
				$icon = $this->displayImg->iconThumb();

				$out->addHTML(
					Html::rawElement(
						'div',
						[ 'class' => 'fullImageLink', 'id' => 'file' ],
						$icon->toHtml( [ 'file-link' => true ] )
					) . "\n"
				);
			}

			$longDesc = $context->msg( 'parentheses', $this->displayImg->getLongDesc() )->text();

			$handler = $this->displayImg->getHandler();

			// If this is a filetype with potential issues, warn the user.
			if ( $handler ) {
				$warningConfig = $handler->getWarningConfig( $this->displayImg );

				if ( $warningConfig !== null ) {
					// The warning will be displayed via CSS and JavaScript.
					// We just need to tell the client side what message to use.
					$output = $context->getOutput();
					$output->addJsConfigVars( 'wgFileWarning', $warningConfig );
					$output->addModules( $warningConfig['module'] );
					$output->addModules( 'mediawiki.filewarning' );
				}
			}

			$medialink = "[[Media:$filename|$linktext]]";

			if ( !$this->displayImg->isSafeFile() ) {
				$warning = $context->msg( 'mediawarning' )->plain();
				// <bdi> is needed here to separate the file name, which
				// most likely ends in Latin characters, from the description,
				// which may begin with the file type. In RTL environment
				// this will get messy.
				$out->wrapWikiTextAsInterface( 'fullMedia', <<<EOT
<bdi dir="$sitedir"><span class="dangerousLink">$medialink</span></bdi> <span class="fileInfo">$longDesc</span>
EOT
				);
				// phpcs:enable
				$out->wrapWikiTextAsInterface( 'mediaWarning', $warning );
			} else {
				$out->wrapWikiTextAsInterface( 'fullMedia', <<<EOT
<bdi dir="$sitedir">$medialink</bdi> <span class="fileInfo">$longDesc</span>
EOT
				);
			}

			$renderLangOptions = $this->displayImg->getAvailableLanguages();
			if ( count( $renderLangOptions ) >= 1 ) {
				$out->addHTML( $this->doRenderLangOpt( $renderLangOptions, $renderLang ) );
			}

			// Add cannot animate thumbnail warning
			if ( !$this->displayImg->canAnimateThumbIfAppropriate() ) {
				// Include the extension so wiki admins can
				// customize it on a per file-type basis
				// (aka say things like use format X instead).
				// additionally have a specific message for
				// file-no-thumb-animation-gif
				$ext = $this->displayImg->getExtension();
				$noAnimMesg = wfMessageFallback(
					'file-no-thumb-animation-' . $ext,
					'file-no-thumb-animation'
				)->setContext( $context )->plain();

				$out->wrapWikiTextAsInterface( 'mw-noanimatethumb', $noAnimMesg );
			}

			if ( !$this->displayImg->isLocal() ) {
				$this->printSharedImageText();
			}
		} else {
			# Image does not exist
			if ( !$this->getPage()->getId() ) {
				$dbr = $this->dbProvider->getReplicaDatabase();

				# No article exists either
				# Show deletion log to be consistent with normal articles
				LogEventsList::showLogExtract(
					$out,
					[ 'delete', 'move', 'protect', 'merge' ],
					$this->getTitle()->getPrefixedText(),
					'',
					[ 'lim' => 10,
						'conds' => [ $dbr->expr( 'log_action', '!=', 'revision' ) ],
						'showIfEmpty' => false,
						'msgKey' => [ 'moveddeleted-notice' ]
					]
				);
			}

			if ( $enableUploads &&
				$context->getAuthority()->isAllowed( 'upload' )
			) {
				// Only show an upload link if the user can upload
				$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
				$nofile = [
					'filepage-nofile-link',
					$uploadTitle->getFullURL( [
						'wpDestFile' => $this->getFile()->getName()
					] )
				];
			} else {
				$nofile = 'filepage-nofile';
			}
			// Note, if there is an image description page, but
			// no image, then this setRobotPolicy is overridden
			// by Article::View().
			$out->setRobotPolicy( 'noindex,nofollow' );
			$out->wrapWikiMsg( "<div id='mw-imagepage-nofile' class='plainlinks'>\n$1\n</div>", $nofile );
			if ( !$this->getPage()->getId() && $send404Code ) {
				// If there is no image, no shared image, and no description page,
				// output a 404, to be consistent with Article::showMissingArticle.
				$request->response()->statusHeader( 404 );
			}
		}
		$out->setFileVersion( $this->displayImg );
	}

	/**
	 * Make the text under the image to say what size preview
	 *
	 * @param array $params parameters for thumbnail
	 * @param string $sizeLinkBigImagePreview HTML for the current size
	 * @return string HTML output
	 */
	protected function getThumbPrevText( $params, $sizeLinkBigImagePreview ) {
		if ( $sizeLinkBigImagePreview ) {
			// Show a different message of preview is different format from original.
			$previewTypeDiffers = false;
			$origExt = $thumbExt = $this->displayImg->getExtension();
			if ( $this->displayImg->getHandler() ) {
				$origMime = $this->displayImg->getMimeType();
				$typeParams = $params;
				$this->displayImg->getHandler()->normaliseParams( $this->displayImg, $typeParams );
				[ $thumbExt, $thumbMime ] = $this->displayImg->getHandler()->getThumbType(
					$origExt, $origMime, $typeParams );
				if ( $thumbMime !== $origMime ) {
					$previewTypeDiffers = true;
				}
			}
			if ( $previewTypeDiffers ) {
				return $this->getContext()->msg( 'show-big-image-preview-differ' )->
					rawParams( $sizeLinkBigImagePreview )->
					params( strtoupper( $origExt ) )->
					params( strtoupper( $thumbExt ) )->
					parse();
			} else {
				return $this->getContext()->msg( 'show-big-image-preview' )->
					rawParams( $sizeLinkBigImagePreview )->
				parse();
			}
		} else {
			return '';
		}
	}

	/**
	 * Creates a thumbnail of specified size and returns an HTML link to it
	 * @param array $params Scaler parameters
	 * @param int $width
	 * @param int $height
	 * @return string
	 */
	protected function makeSizeLink( $params, $width, $height ) {
		$params['width'] = $width;
		$params['height'] = $height;
		$thumbnail = $this->displayImg->transform( $params );
		if ( $thumbnail && !$thumbnail->isError() ) {
			return Html::rawElement( 'a', [
				'href' => $thumbnail->getUrl(),
				'class' => 'mw-thumbnail-link'
				], $this->getContext()->msg( 'show-big-image-size' )->numParams(
					$thumbnail->getWidth(), $thumbnail->getHeight()
				)->parse() );
		} else {
			return '';
		}
	}

	/**
	 * Show a notice that the file is from a shared repository
	 */
	protected function printSharedImageText() {
		$out = $this->getContext()->getOutput();
		$this->loadFile();

		$descUrl = $this->getFile()->getDescriptionUrl();
		$descText = $this->getFile()->getDescriptionText( $this->getContext()->getLanguage() );

		/* Add canonical to head if there is no local page for this shared file */
		if ( $descUrl && !$this->getPage()->getId() ) {
			$out->setCanonicalUrl( $descUrl );
		}

		$wrap = "<div class=\"sharedUploadNotice\">\n$1\n</div>\n";
		$repo = $this->getFile()->getRepo()->getDisplayName();

		if ( $descUrl &&
			$descText &&
			!$this->getContext()->msg( 'sharedupload-desc-here' )->isDisabled()
		) {
			$out->wrapWikiMsg( $wrap, [ 'sharedupload-desc-here', $repo, $descUrl ] );
		} elseif ( $descUrl &&
			!$this->getContext()->msg( 'sharedupload-desc-there' )->isDisabled()
		) {
			$out->wrapWikiMsg( $wrap, [ 'sharedupload-desc-there', $repo, $descUrl ] );
		} else {
			$out->wrapWikiMsg( $wrap, [ 'sharedupload', $repo ], ''/*BACKCOMPAT*/ );
		}

		if ( $descText ) {
			$this->mExtraDescription = $descText;
		}
	}

	public function getUploadUrl() {
		$this->loadFile();
		$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
		return $uploadTitle->getFullURL( [
			'wpDestFile' => $this->getFile()->getName(),
			'wpForReUpload' => 1
		] );
	}

	/**
	 * Add the re-upload link (or message about not being able to re-upload) to the output.
	 */
	protected function uploadLinksBox() {
		if ( !$this->getContext()->getConfig()->get( MainConfigNames::EnableUploads ) ) {
			return;
		}

		$this->loadFile();
		if ( !$this->getFile()->isLocal() ) {
			return;
		}

		$canUpload = $this->getContext()->getAuthority()
			->probablyCan( 'upload', $this->getTitle() );
		if ( $canUpload && UploadBase::userCanReUpload(
				$this->getContext()->getAuthority(),
				$this->getFile() )
		) {
			// "Upload a new version of this file" link
			$ulink = $this->linkRenderer->makeExternalLink(
				$this->getUploadUrl(),
				$this->getContext()->msg( 'uploadnewversion-linktext' ),
				$this->getTitle()
			);
			$attrs = [ 'class' => 'plainlinks', 'id' => 'mw-imagepage-reupload-link' ];
			$linkPara = Html::rawElement( 'p', $attrs, $ulink );
		} else {
			// "You cannot overwrite this file." message
			$attrs = [ 'id' => 'mw-imagepage-upload-disallowed' ];
			$msg = $this->getContext()->msg( 'upload-disallowed-here' )->text();
			$linkPara = Html::element( 'p', $attrs, $msg );
		}

		$uploadLinks = Html::rawElement( 'div', [ 'class' => 'mw-imagepage-upload-links' ], $linkPara );
		$this->getContext()->getOutput()->addHTML( $uploadLinks );
	}

	/**
	 * For overloading
	 */
	protected function closeShowImage() {
	}

	/**
	 * If the page we've just displayed is in the "Image" namespace,
	 * we follow it with an upload history of the image and its usage.
	 */
	protected function imageHistory() {
		$this->loadFile();
		$out = $this->getContext()->getOutput();
		$pager = new ImageHistoryPseudoPager(
			$this,
			MediaWikiServices::getInstance()->getLinkBatchFactory()
		);
		$out->addHTML( $pager->getBody() );
		$out->getMetadata()->setPreventClickjacking( $pager->getPreventClickjacking() );

		$this->getFile()->resetHistory(); // free db resources

		# Exist check because we don't want to show this on pages where an image
		# doesn't exist along with the noimage message, that would suck. -ævar
		if ( $this->getFile()->exists() ) {
			$this->uploadLinksBox();
		}
	}

	/**
	 * @param string|string[] $target
	 * @param int $limit
	 * @return IResultWrapper
	 */
	protected function queryImageLinks( $target, $limit ) {
		return $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title', 'il_to' ] )
			->from( 'imagelinks' )
			->join( 'page', null, 'il_from = page_id' )
			->where( [ 'il_to' => $target ] )
			->orderBy( 'il_from' )
			->limit( $limit + 1 )
			->caller( __METHOD__ )->fetchResultSet();
	}

	protected function imageLinks() {
		$limit = 100;

		$out = $this->getContext()->getOutput();

		$rows = [];
		$redirects = [];
		foreach ( $this->getTitle()->getRedirectsHere( NS_FILE ) as $redir ) {
			$redirects[$redir->getDBkey()] = [];
			$rows[] = (object)[
				'page_namespace' => NS_FILE,
				'page_title' => $redir->getDBkey(),
			];
		}

		$res = $this->queryImageLinks( $this->getTitle()->getDBkey(), $limit + 1 );
		foreach ( $res as $row ) {
			$rows[] = $row;
		}
		$count = count( $rows );

		$hasMore = $count > $limit;
		if ( !$hasMore && count( $redirects ) ) {
			$res = $this->queryImageLinks( array_keys( $redirects ),
				$limit - count( $rows ) + 1 );
			foreach ( $res as $row ) {
				$redirects[$row->il_to][] = $row;
				$count++;
			}
			$hasMore = ( $res->numRows() + count( $rows ) ) > $limit;
		}

		if ( $count == 0 ) {
			$out->wrapWikiMsg(
				Html::rawElement( 'div',
					[ 'id' => 'mw-imagepage-nolinkstoimage' ], "\n$1\n" ),
				'nolinkstoimage'
			);
			return;
		}

		$out->addHTML( "<div id='mw-imagepage-section-linkstoimage'>\n" );
		if ( !$hasMore ) {
			$out->addWikiMsg( 'linkstoimage', $count );
		} else {
			// More links than the limit. Add a link to [[Special:Whatlinkshere]]
			$out->addWikiMsg( 'linkstoimage-more',
				$this->getContext()->getLanguage()->formatNum( $limit ),
				$this->getTitle()->getPrefixedDBkey()
			);
		}

		$out->addHTML(
			Html::openElement( 'ul',
				[ 'class' => 'mw-imagepage-linkstoimage' ] ) . "\n"
		);
		// Sort the list by namespace:title
		usort( $rows, [ $this, 'compare' ] );

		// Create links for every element
		$currentCount = 0;
		foreach ( $rows as $element ) {
			$currentCount++;
			if ( $currentCount > $limit ) {
				break;
			}

			$link = $this->linkRenderer->makeKnownLink(
				Title::makeTitle( $element->page_namespace, $element->page_title ),
				null,
				[],
				// Add a redirect=no to make redirect pages reachable
				[ 'redirect' => isset( $redirects[$element->page_title] ) ? 'no' : null ]
			);
			if ( !isset( $redirects[$element->page_title] ) ) {
				# No redirects
				$liContents = $link;
			} elseif ( count( $redirects[$element->page_title] ) === 0 ) {
				# Redirect without usages
				$liContents = $this->getContext()->msg( 'linkstoimage-redirect' )
					->rawParams( $link, '' )
					->parse();
			} else {
				# Redirect with usages
				$li = '';
				foreach ( $redirects[$element->page_title] as $row ) {
					$currentCount++;
					if ( $currentCount > $limit ) {
						break;
					}

					$link2 = $this->linkRenderer->makeKnownLink(
						Title::makeTitle( $row->page_namespace, $row->page_title ) );
					$li .= Html::rawElement(
						'li',
						[ 'class' => 'mw-imagepage-linkstoimage-ns' . $element->page_namespace ],
						$link2
						) . "\n";
				}

				$ul = Html::rawElement(
					'ul',
					[ 'class' => 'mw-imagepage-redirectstofile' ],
					$li
					) . "\n";
				$liContents = $this->getContext()->msg( 'linkstoimage-redirect' )->rawParams(
					$link, $ul )->parse();
			}
			$out->addHTML( Html::rawElement(
					'li',
					[ 'class' => 'mw-imagepage-linkstoimage-ns' . $element->page_namespace ],
					$liContents
				) . "\n"
			);

		}
		$out->addHTML( Html::closeElement( 'ul' ) . "\n" );
		$res->free();

		// Add a links to [[Special:Whatlinkshere]]
		if ( $currentCount > $limit ) {
			$out->addWikiMsg( 'morelinkstoimage', $this->getTitle()->getPrefixedDBkey() );
		}
		$out->addHTML( Html::closeElement( 'div' ) . "\n" );
	}

	protected function imageDupes() {
		$this->loadFile();
		$out = $this->getContext()->getOutput();

		$dupes = $this->getPage()->getDuplicates();
		if ( count( $dupes ) == 0 ) {
			return;
		}

		$out->addHTML( "<div id='mw-imagepage-section-duplicates'>\n" );
		$out->addWikiMsg( 'duplicatesoffile',
			$this->getContext()->getLanguage()->formatNum( count( $dupes ) ), $this->getTitle()->getDBkey()
		);
		$out->addHTML( "<ul class='mw-imagepage-duplicates'>\n" );

		/**
		 * @var File $file
		 */
		foreach ( $dupes as $file ) {
			$fromSrc = '';
			if ( $file->isLocal() ) {
				$link = $this->linkRenderer->makeKnownLink( $file->getTitle() );
			} else {
				$link = $this->linkRenderer->makeExternalLink(
					$file->getDescriptionUrl(),
					$file->getTitle()->getPrefixedText(),
					$this->getTitle()
				);
				$fromSrc = $this->getContext()->msg(
					'shared-repo-from',
					$file->getRepo()->getDisplayName()
				)->escaped();
			}
			$out->addHTML( "<li>{$link} {$fromSrc}</li>\n" );
		}
		$out->addHTML( "</ul></div>\n" );
	}

	/**
	 * Display an error with a wikitext description
	 *
	 * @param string $description
	 */
	public function showError( $description ) {
		$out = $this->getContext()->getOutput();
		$out->setPageTitleMsg( $this->getContext()->msg( 'internalerror' ) );
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setArticleRelated( false );
		$out->disableClientCache();
		$out->addWikiTextAsInterface( $description );
	}

	/**
	 * Callback for usort() to do link sorts by (namespace, title)
	 * Function copied from Title::compare()
	 *
	 * @param stdClass $a Object page to compare with
	 * @param stdClass $b Object page to compare with
	 * @return int Result of string comparison, or namespace comparison
	 */
	protected function compare( $a, $b ) {
		return $a->page_namespace <=> $b->page_namespace
			?: strcmp( $a->page_title, $b->page_title );
	}

	/**
	 * Output a drop-down box for language options for the file
	 *
	 * @param array $langChoices Array of string language codes
	 * @param string|null $renderLang Language code for the language we want the file to rendered in,
	 *  it is pre-selected in the drop down box, use null to select the default case in the option list
	 * @return string HTML to insert underneath image.
	 */
	protected function doRenderLangOpt( array $langChoices, $renderLang ) {
		$context = $this->getContext();
		$script = $context->getConfig()->get( MainConfigNames::Script );
		$opts = '';

		$matchedRenderLang = $renderLang === null ? null : $this->displayImg->getMatchedLanguage( $renderLang );

		foreach ( $langChoices as $lang ) {
			$opts .= $this->createXmlOptionStringForLanguage(
				$lang,
				$matchedRenderLang === $lang
			);
		}

		// Allow for the default case in an svg <switch> that is displayed if no
		// systemLanguage attribute matches
		$opts .= "\n" .
			Html::element(
				'option',
				[ 'value' => 'und', 'selected' => $matchedRenderLang === null || $matchedRenderLang === 'und' ],
				$context->msg( 'img-lang-default' )->text()
			);

		$select = Html::rawElement(
			'select',
			[ 'id' => 'mw-imglangselector', 'name' => 'lang' ],
			$opts
		);
		$submit = Html::submitButton( $context->msg( 'img-lang-go' )->text(), [] );

		$formContents = $context->msg( 'img-lang-info' )
			->rawParams( $select, $submit )
			->parse();
		$formContents .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() );

		$langSelectLine = Html::rawElement( 'div', [ 'id' => 'mw-imglangselector-line' ],
			Html::rawElement( 'form', [ 'action' => $script ], $formContents )
		);
		return $langSelectLine;
	}

	/**
	 * @param string $lang
	 * @param bool $selected
	 * @return string
	 */
	private function createXmlOptionStringForLanguage( $lang, $selected ) {
		// TODO: There is no good way to get the language name of a BCP code,
		// as MW language codes take precedence
		$name = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageName( $lang, $this->getContext()->getLanguage()->getCode() );
		if ( $name !== '' ) {
			$display = $this->getContext()->msg( 'img-lang-opt', $lang, $name )->text();
		} else {
			$display = $lang;
		}
		return "\n" . Html::element( 'option', [ 'value' => $lang, 'selected' => $selected ], $display );
	}

	/**
	 * Get alternative thumbnail sizes.
	 *
	 * @note This will only list several alternatives if thumbnails are rendered on 404
	 * @param int $origWidth Actual width of image
	 * @param int $origHeight Actual height of image
	 * @return int[][] An array of [width, height] pairs.
	 * @phan-return array<int,array{0:int,1:int}>
	 */
	protected function getThumbSizes( $origWidth, $origHeight ) {
		$context = $this->getContext();
		$imageLimits = $context->getConfig()->get( MainConfigNames::ImageLimits );
		if ( $this->displayImg->getRepo()->canTransformVia404() ) {
			$thumbSizes = $imageLimits;
			// Also include the full sized resolution in the list, so
			// that users know they can get it. This will link to the
			// original file asset if mustRender() === false. In the case
			// that we mustRender, some users have indicated that they would
			// find it useful to have the full size image in the rendered
			// image format.
			$thumbSizes[] = [ $origWidth, $origHeight ];
		} else {
			# Creating thumb links triggers thumbnail generation.
			# Just generate the thumb for the current users prefs.
			$thumbSizes = [
				$this->getImageLimitsFromOption( $context->getUser(), 'thumbsize' )
			];
			if ( !$this->displayImg->mustRender() ) {
				// We can safely include a link to the "full-size" preview,
				// without actually rendering.
				$thumbSizes[] = [ $origWidth, $origHeight ];
			}
		}
		return $thumbSizes;
	}

	/**
	 * @see WikiFilePage::getFile
	 * @return File
	 */
	public function getFile(): File {
		return $this->getPage()->getFile();
	}

	/**
	 * @see WikiFilePage::isLocal
	 * @return bool
	 */
	public function isLocal() {
		return $this->getPage()->isLocal();
	}

	/**
	 * @see WikiFilePage::getDuplicates
	 * @return File[]|null
	 */
	public function getDuplicates() {
		return $this->getPage()->getDuplicates();
	}

	/**
	 * @see WikiFilePage::getForeignCategories
	 * @return TitleArrayFromResult
	 */
	public function getForeignCategories() {
		return $this->getPage()->getForeignCategories();
	}

}

/** @deprecated class alias since 1.44 */
class_alias( ImagePage::class, 'ImagePage' );
