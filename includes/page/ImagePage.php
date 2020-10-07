<?php
/**
 * Special handling for file description pages.
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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Class for viewing MediaWiki file description pages
 *
 * @ingroup Media
 *
 * @method WikiFilePage getPage()
 */
class ImagePage extends Article {
	use MediaFileTrait;

	/** @var File|false */
	private $displayImg;

	/** @var FileRepo */
	private $repo;

	/** @var bool */
	private $fileLoaded;

	/** @var bool */
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
		$this->getPage()->setFile( $img );
		if ( !$this->displayImg ) { // not set by hook?
			$this->displayImg = $img;
		}
		$this->repo = $img->getRepo();
	}

	public function view() {
		global $wgShowEXIF;

		// For action=render, include body text only; none of the image extras
		if ( $this->viewIsRenderAction ) {
			parent::view();
			return;
		}

		$out = $this->getContext()->getOutput();
		$request = $this->getContext()->getRequest();
		$diff = $request->getVal( 'diff' );
		$diffOnly = $request->getBool(
			'diffonly',
			$this->getContext()->getUser()->getOption( 'diffonly' )
		);

		if ( $this->getTitle()->getNamespace() != NS_FILE || ( $diff !== null && $diffOnly ) ) {
			parent::view();
			return;
		}

		$this->loadFile();

		if (
			$this->getTitle()->getNamespace() == NS_FILE
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

		if ( $wgShowEXIF && $this->displayImg->exists() ) {
			// @todo FIXME: Bad interface, see note on MediaHandler::formatMetadata().
			$formattedMetadata = $this->displayImg->formatMetadata( $this->getContext() );
			$showmeta = $formattedMetadata !== false;
		} else {
			$showmeta = false;
		}

		if ( !$diff && $this->displayImg->exists() ) {
			$out->addHTML( $this->showTOC( $showmeta ) );
		}

		if ( !$diff ) {
			$this->openShowImage();
		}

		# No need to display noarticletext, we use our own message, output in openShowImage()
		if ( $this->getPage()->getId() ) {
			# NS_FILE is in the user language, but this section (the actual wikitext)
			# should be in page content language
			$pageLang = $this->getTitle()->getPageViewLanguage();
			$out->addHTML( Xml::openElement( 'div', [ 'id' => 'mw-imagepage-content',
				'lang' => $pageLang->getHtmlCode(), 'dir' => $pageLang->getDir(),
				'class' => 'mw-content-' . $pageLang->getDir() ] ) );

			parent::view();

			$out->addHTML( Xml::closeElement( 'div' ) );
		} else {
			# Just need to set the right headers
			$out->setArticleFlag( true );
			$out->setPageTitle( $this->getTitle()->getPrefixedText() );
			$this->getPage()->doViewUpdates(
				$this->getContext()->getUser(),
				$this->getOldID()
			);
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = $this->getContext()->msg( 'shareddescriptionfollows' );
			if ( !$fol->isDisabled() ) {
				$out->addWikiTextAsInterface( $fol->plain() );
			}
			$out->addHTML( '<div id="shared-image-desc">' . $this->mExtraDescription . "</div>\n" );
		}

		$this->closeShowImage();
		$this->imageHistory();
		// TODO: Cleanup the following

		$out->addHTML( Xml::element( 'h2',
			[ 'id' => 'filelinks' ],
				$this->getContext()->msg( 'imagelinks' )->text() ) . "\n" );
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

		if ( $showmeta ) {
			'@phan-var array $formattedMetadata';
			$out->addHTML( Xml::element(
				'h2',
				[ 'id' => 'metadata' ],
					$this->getContext()->msg( 'metadata' )->text() ) . "\n" );
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
			'filepage', // always show the local local Filepage.css, T31277
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
			'<li><a href="#file">' . $this->getContext()->msg( 'file-anchor-link' )->escaped() . '</a></li>',
			'<li><a href="#filehistory">' . $this->getContext()->msg( 'filehist' )->escaped() . '</a></li>',
			'<li><a href="#filelinks">' . $this->getContext()->msg( 'imagelinks' )->escaped() . '</a></li>',
		];

		$this->getHookRunner()->onImagePageShowTOC( $this, $r );

		if ( $metadata ) {
			$r[] = '<li><a href="#metadata">' .
				$this->getContext()->msg( 'metadata' )->escaped() .
				'</a></li>';
		}

		return '<ul id="filetoc">' . implode( "\n", $r ) . '</ul>';
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
		// Intial state is collapsed
		// see filepage.css and mediawiki.action.view.metadata module.
		$r .= "<table id=\"mw_metadata\" class=\"mw_metadata collapsed\">\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $v ) {
				$class = str_replace( ' ', '_', $v['id'] );
				if ( $type == 'collapsed' ) {
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
	 * Overloading Article's getEmptyPageParserOutput method.
	 *
	 * Omit noarticletext if sharedupload; text will be fetched from the
	 * shared upload server if possible.
	 *
	 * @param ParserOptions $options
	 * @return ParserOutput
	 */
	public function getEmptyPageParserOutput( ParserOptions $options ) {
		$this->loadFile();
		if (
			$this->getFile()
			&& !$this->getFile()->isLocal()
			&& !$this->getPage()->getId()
		) {
			return new ParserOutput();
		}
		return parent::getEmptyPageParserOutput( $options );
	}

	/**
	 * Returns language code to be used for dispaying the image, based on request context and
	 * languages available in the file.
	 *
	 * @param WebRequest $request
	 * @param File $file
	 * @return string|null
	 */
	private function getLanguageForRendering( WebRequest $request, File $file ) {
		$handler = $file->getHandler();
		if ( !$handler ) {
			return null;
		}

		$config = MediaWikiServices::getInstance()->getMainConfig();
		$requestLanguage = $request->getVal( 'lang', $config->get( 'LanguageCode' ) );
		if ( $handler->validateParam( 'lang', $requestLanguage ) ) {
			return $file->getMatchedLanguage( $requestLanguage );
		}

		return $handler->getDefaultRenderLanguage( $file );
	}

	protected function openShowImage() {
		global $wgEnableUploads, $wgSend404Code, $wgSVGMaxSize;

		$this->loadFile();
		$out = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$lang = $this->getContext()->getLanguage();
		$dirmark = $lang->getDirMarkEntity();
		$request = $this->getContext()->getRequest();

		if ( $this->displayImg->exists() ) {
			list( $maxWidth, $maxHeight ) = $this->getImageLimitsFromOption( $user, 'imagesize' );

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
					list( $width, $height ) = $this->displayImg->getDisplayWidthHeight(
						$maxWidth, $maxHeight, $page
					);
					$linktext = $this->getContext()->msg( 'show-big-image' )->escaped();

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
									&& max( $size[0], $size[1] ) <= $wgSVGMaxSize )
							)
							&& $size[0] != $width && $size[1] != $height
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
							$this->getContext()->msg( 'show-big-image-other' )
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
					$out->addHTML( '<table class="multipageimage"><tr><td>' );
				}

				if ( $thumbnail ) {
					$options = [
						'alt' => $this->displayImg->getTitle()->getPrefixedText(),
						'file-link' => true,
					];
					$out->addHTML( '<div class="fullImageLink" id="file">' .
						$thumbnail->toHtml( $options ) .
						$anchorclose . "</div>\n" );
				}

				if ( $isMulti ) {
					$count = $this->displayImg->pageCount();
					$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

					if ( $page > 1 ) {
						$label = $this->getContext()->msg( 'imgmultipageprev' )->text();
						// on the client side, this link is generated in ajaxifyPageNavigation()
						// in the mediawiki.page.image.pagination module
						$link = $linkRenderer->makeKnownLink(
							$this->getTitle(),
							$label,
							[],
							[ 'page' => $page - 1 ]
						);
						$thumb1 = Linker::makeThumbLinkObj(
							$this->getTitle(),
							$this->displayImg,
							$link,
							$label,
							'none',
							[ 'page' => $page - 1 ]
						);
					} else {
						$thumb1 = '';
					}

					if ( $page < $count ) {
						$label = $this->getContext()->msg( 'imgmultipagenext' )->text();
						$link = $linkRenderer->makeKnownLink(
							$this->getTitle(),
							$label,
							[],
							[ 'page' => $page + 1 ]
						);
						$thumb2 = Linker::makeThumbLinkObj(
							$this->getTitle(),
							$this->displayImg,
							$link,
							$label,
							'none',
							[ 'page' => $page + 1 ]
						);
					} else {
						$thumb2 = '';
					}

					global $wgScript;

					$formParams = [
						'name' => 'pageselector',
						'action' => $wgScript,
					];
					$options = [];
					for ( $i = 1; $i <= $count; $i++ ) {
						$options[] = Xml::option( $lang->formatNum( $i ), $i, $i == $page );
					}
					$select = Xml::tags( 'select',
						[ 'id' => 'pageselector', 'name' => 'page' ],
						implode( "\n", $options ) );

					$out->addHTML(
						'</td><td><div class="multipageimagenavbox">' .
						Xml::openElement( 'form', $formParams ) .
						Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
						$this->getContext()->msg( 'imgmultigoto' )->rawParams( $select )->parse() .
						$this->getContext()->msg( 'word-separator' )->escaped() .
						Xml::submitButton( $this->getContext()->msg( 'imgmultigo' )->text() ) .
						Xml::closeElement( 'form' ) .
						"<hr />$thumb1\n$thumb2<br style=\"clear: both\" /></div></td></tr></table>"
					);
				}
			} elseif ( $this->displayImg->isSafeFile() ) {
				# if direct link is allowed but it's not a renderable image, show an icon.
				$icon = $this->displayImg->iconThumb();

				$out->addHTML( '<div class="fullImageLink" id="file">' .
					$icon->toHtml( [ 'file-link' => true ] ) .
					"</div>\n" );
			}

			$longDesc = $this->getContext()->msg( 'parentheses', $this->displayImg->getLongDesc() )->text();

			$handler = $this->displayImg->getHandler();

			// If this is a filetype with potential issues, warn the user.
			if ( $handler ) {
				$warningConfig = $handler->getWarningConfig( $this->displayImg );

				if ( $warningConfig !== null ) {
					// The warning will be displayed via CSS and JavaScript.
					// We just need to tell the client side what message to use.
					$output = $this->getContext()->getOutput();
					$output->addJsConfigVars( 'wgFileWarning', $warningConfig );
					$output->addModules( $warningConfig['module'] );
					$output->addModules( 'mediawiki.filewarning' );
				}
			}

			$medialink = "[[Media:$filename|$linktext]]";

			if ( !$this->displayImg->isSafeFile() ) {
				$warning = $this->getContext()->msg( 'mediawarning' )->plain();
				// dirmark is needed here to separate the file name, which
				// most likely ends in Latin characters, from the description,
				// which may begin with the file type. In RTL environment
				// this will get messy.
				// The dirmark, however, must not be immediately adjacent
				// to the filename, because it can get copied with it.
				// See T27277.
				// phpcs:disable Generic.Files.LineLength
				$out->wrapWikiTextAsInterface( 'fullMedia', <<<EOT
<span class="dangerousLink">{$medialink}</span> $dirmark<span class="fileInfo">$longDesc</span>
EOT
				);
				// phpcs:enable
				$out->wrapWikiTextAsInterface( 'mediaWarning', $warning );
			} else {
				$out->wrapWikiTextAsInterface( 'fullMedia', <<<EOT
{$medialink} {$dirmark}<span class="fileInfo">$longDesc</span>
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
				)->plain();

				$out->wrapWikiTextAsInterface( 'mw-noanimatethumb', $noAnimMesg );
			}

			if ( !$this->displayImg->isLocal() ) {
				$this->printSharedImageText();
			}
		} else {
			# Image does not exist
			if ( !$this->getPage()->getId() ) {
				$dbr = wfGetDB( DB_REPLICA );

				# No article exists either
				# Show deletion log to be consistent with normal articles
				LogEventsList::showLogExtract(
					$out,
					[ 'delete', 'move', 'protect' ],
					$this->getTitle()->getPrefixedText(),
					'',
					[ 'lim' => 10,
						'conds' => [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ],
						'showIfEmpty' => false,
						'msgKey' => [ 'moveddeleted-notice' ]
					]
				);
			}

			if ( $wgEnableUploads && MediaWikiServices::getInstance()
					->getPermissionManager()
					->userHasRight( $user, 'upload' )
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
			if ( !$this->getPage()->getId() && $wgSend404Code ) {
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
				list( $thumbExt, $thumbMime ) = $this->displayImg->getHandler()->getThumbType(
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
	 * Creates an thumbnail of specified size and returns an HTML link to it
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
			$this->getContext()->msg( 'sharedupload-desc-here' )->plain() !== '-'
		) {
			$out->wrapWikiMsg( $wrap, [ 'sharedupload-desc-here', $repo, $descUrl ] );
		} elseif ( $descUrl &&
			$this->getContext()->msg( 'sharedupload-desc-there' )->plain() !== '-'
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
		if ( !$this->getContext()->getConfig()->get( 'EnableUploads' ) ) {
			return;
		}

		$this->loadFile();
		if ( !$this->getFile()->isLocal() ) {
			return;
		}

		$canUpload = MediaWikiServices::getInstance()->getPermissionManager()
			->quickUserCan( 'upload', $this->getContext()->getUser(), $this->getTitle() );
		if ( $canUpload && UploadBase::userCanReUpload(
				$this->getContext()->getUser(),
				$this->getFile() )
		) {
			// "Upload a new version of this file" link
			$ulink = Linker::makeExternalLink(
				$this->getUploadUrl(),
				$this->getContext()->msg( 'uploadnewversion-linktext' )->text()
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
		$pager = new ImageHistoryPseudoPager( $this );
		$out->addHTML( $pager->getBody() );
		$out->preventClickjacking( $pager->getPreventClickjacking() );

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
		$dbr = wfGetDB( DB_REPLICA );

		return $dbr->select(
			[ 'imagelinks', 'page' ],
			[ 'page_namespace', 'page_title', 'il_to' ],
			[ 'il_to' => $target, 'il_from = page_id' ],
			__METHOD__,
			[ 'LIMIT' => $limit + 1, 'ORDER BY' => 'il_from', ]
		);
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

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		// Create links for every element
		$currentCount = 0;
		foreach ( $rows as $element ) {
			$currentCount++;
			if ( $currentCount > $limit ) {
				break;
			}

			$query = [];
			# Add a redirect=no to make redirect pages reachable
			if ( isset( $redirects[$element->page_title] ) ) {
				$query['redirect'] = 'no';
			}
			$link = $linkRenderer->makeKnownLink(
				Title::makeTitle( $element->page_namespace, $element->page_title ),
				null, [], $query
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

					$link2 = $linkRenderer->makeKnownLink(
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

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		/**
		 * @var File $file
		 */
		foreach ( $dupes as $file ) {
			$fromSrc = '';
			if ( $file->isLocal() ) {
				$link = $linkRenderer->makeKnownLink( $file->getTitle() );
			} else {
				$link = Linker::makeExternalLink( $file->getDescriptionUrl(),
					$file->getTitle()->getPrefixedText() );
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
	 * Delete the file, or an earlier version of it
	 */
	public function delete() {
		$file = $this->getFile();
		if ( !$file->exists() || !$file->isLocal() || $file->getRedirected() ) {
			// Standard article deletion
			parent::delete();
			return;
		}
		'@phan-var LocalFile $file';

		$deleter = new FileDeleteForm( $file, $this->getContext()->getUser() );
		$deleter->execute();
	}

	/**
	 * Display an error with a wikitext description
	 *
	 * @param string $description
	 */
	public function showError( $description ) {
		$out = $this->getContext()->getOutput();
		$out->setPageTitle( $this->getContext()->msg( 'internalerror' ) );
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setArticleRelated( false );
		$out->enableClientCache( false );
		$out->addWikiTextAsInterface( $description );
	}

	/**
	 * Callback for usort() to do link sorts by (namespace, title)
	 * Function copied from Title::compare()
	 *
	 * @param object $a Object page to compare with
	 * @param object $b Object page to compare with
	 * @return int Result of string comparison, or namespace comparison
	 */
	protected function compare( $a, $b ) {
		return $a->page_namespace <=> $b->page_namespace
			?: strcmp( $a->page_title, $b->page_title );
	}

	/**
	 * Returns the corresponding $wgImageLimits entry for the selected user option
	 *
	 * @param User $user
	 * @param string $optionName Name of a option to check, typically imagesize or thumbsize
	 * @return int[]
	 * @since 1.21
	 * @deprecated Since 1.35 Use static function MediaFileTrait::getImageLimitsFromOption
	 */
	public function getImageLimitsFromOption( $user, $optionName ) {
		return MediaFileTrait::getImageLimitsFromOption( $user, $optionName );
	}

	/**
	 * Output a drop-down box for language options for the file
	 *
	 * @param array $langChoices Array of string language codes
	 * @param string $renderLang Language code for the language we want the file to rendered in.
	 * @return string HTML to insert underneath image.
	 */
	protected function doRenderLangOpt( array $langChoices, $renderLang ) {
		global $wgScript;
		$opts = '';

		$matchedRenderLang = $this->displayImg->getMatchedLanguage( $renderLang );

		foreach ( $langChoices as $lang ) {
			$opts .= $this->createXmlOptionStringForLanguage(
				$lang,
				$matchedRenderLang === $lang
			);
		}

		// Allow for the default case in an svg <switch> that is displayed if no
		// systemLanguage attribute matches
		$opts .= "\n" .
			Xml::option(
				$this->getContext()->msg( 'img-lang-default' )->text(),
				'und',
				$matchedRenderLang === null
			);

		$select = Html::rawElement(
			'select',
			[ 'id' => 'mw-imglangselector', 'name' => 'lang' ],
			$opts
		);
		$submit = Xml::submitButton( $this->getContext()->msg( 'img-lang-go' )->text() );

		$formContents = $this->getContext()->msg( 'img-lang-info' )
			->rawParams( $select, $submit )
			->parse();
		$formContents .= Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() );

		$langSelectLine = Html::rawElement( 'div', [ 'id' => 'mw-imglangselector-line' ],
			Html::rawElement( 'form', [ 'action' => $wgScript ], $formContents )
		);
		return $langSelectLine;
	}

	/**
	 * @param string $lang
	 * @param bool $selected
	 * @return string
	 */
	private function createXmlOptionStringForLanguage( $lang, $selected ) {
		$code = LanguageCode::bcp47( $lang );
		$name = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageName( $code, $this->getContext()->getLanguage()->getCode() );
		if ( $name !== '' ) {
			$display = $this->getContext()->msg( 'img-lang-opt', $code, $name )->text();
		} else {
			$display = $code;
		}
		return "\n" .
			Xml::option(
				$display,
				$lang,
				$selected
			);
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
		global $wgImageLimits;
		if ( $this->displayImg->getRepo()->canTransformVia404() ) {
			$thumbSizes = $wgImageLimits;
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
				$this->getImageLimitsFromOption( $this->getContext()->getUser(), 'thumbsize' )
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
	 * @return bool|File
	 */
	public function getFile() {
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
	 * @return array|null
	 */
	public function getDuplicates() {
		return $this->getPage()->getDuplicates();
	}

	/**
	 * @see WikiFilePage::getForeignCategories
	 * @return TitleArray|Title[]
	 */
	public function getForeignCategories() {
		return $this->getPage()->getForeignCategories();
	}

}
