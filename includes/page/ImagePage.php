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

use Wikimedia\Rdbms\ResultWrapper;

/**
 * Class for viewing MediaWiki file description pages
 *
 * @ingroup Media
 */
class ImagePage extends Article {
	/** @var File */
	private $displayImg;

	/** @var FileRepo */
	private $repo;

	/** @var bool */
	private $fileLoaded;

	/** @var bool */
	protected $mExtraDescription = false;

	/**
	 * @var WikiFilePage
	 */
	protected $mPage;

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
		$this->mPage->setFile( $file );
		$this->displayImg = $file;
		$this->fileLoaded = true;
	}

	protected function loadFile() {
		if ( $this->fileLoaded ) {
			return;
		}
		$this->fileLoaded = true;

		$this->displayImg = $img = false;

		Hooks::run( 'ImagePageFindFile', [ $this, &$img, &$this->displayImg ] );
		if ( !$img ) { // not set by hook?
			$img = wfFindFile( $this->getTitle() );
			if ( !$img ) {
				$img = wfLocalFile( $this->getTitle() );
			}
		}
		$this->mPage->setFile( $img );
		if ( !$this->displayImg ) { // not set by hook?
			$this->displayImg = $img;
		}
		$this->repo = $img->getRepo();
	}

	/**
	 * Handler for action=render
	 * Include body text only; none of the image extras
	 */
	public function render() {
		$this->getContext()->getOutput()->setArticleBodyOnly( true );
		parent::view();
	}

	public function view() {
		global $wgShowEXIF;

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

		if ( $this->getTitle()->getNamespace() == NS_FILE && $this->mPage->getFile()->getRedirected() ) {
			if ( $this->getTitle()->getDBkey() == $this->mPage->getFile()->getName() || $diff !== null ) {
				// mTitle is the same as the redirect target so ask Article
				// to perform the redirect for us.
				$request->setVal( 'diffonly', 'true' );
				parent::view();
				return;
			} else {
				// mTitle is not the same as the redirect target so it is
				// probably the redirect page itself. Fake the redirect symbol
				$out->setPageTitle( $this->getTitle()->getPrefixedText() );
				$out->addHTML( $this->viewRedirect(
					Title::makeTitle( NS_FILE, $this->mPage->getFile()->getName() ),
					/* $appendSubtitle */ true,
					/* $forceKnown */ true )
				);
				$this->mPage->doViewUpdates( $this->getContext()->getUser(), $this->getOldID() );
				return;
			}
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
		if ( $this->mPage->getId() ) {
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
			$this->mPage->doViewUpdates( $this->getContext()->getUser(), $this->getOldID() );
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = $this->getContext()->msg( 'shareddescriptionfollows' );
			if ( !$fol->isDisabled() ) {
				$out->addWikiText( $fol->plain() );
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
		Hooks::run( 'ImagePageAfterImageLinks', [ $this, &$html ] );
		if ( $html ) {
			$out->addHTML( $html );
		}

		if ( $showmeta ) {
			$out->addHTML( Xml::element(
				'h2',
				[ 'id' => 'metadata' ],
					$this->getContext()->msg( 'metadata' )->text() ) . "\n" );
			$out->addWikiText( $this->makeMetadataTable( $formattedMetadata ) );
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

		Hooks::run( 'ImagePageShowTOC', [ $this, &$r ] );

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
		$r = "<div class=\"mw-imagepage-section-metadata\">";
		$r .= $this->getContext()->msg( 'metadata-help' )->plain();
		$r .= "<table id=\"mw_metadata\" class=\"mw_metadata\">\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $v ) {
				# @todo FIXME: Why is this using escapeId for a class?!
				$class = Sanitizer::escapeId( $v['id'] );
				if ( $type == 'collapsed' ) {
					// Handled by mediawiki.action.view.metadata module.
					$class .= ' collapsable';
				}
				$r .= "<tr class=\"$class\">\n";
				$r .= "<th>{$v['name']}</th>\n";
				$r .= "<td>{$v['value']}</td>\n</tr>";
			}
		}
		$r .= "</table>\n</div>\n";
		return $r;
	}

	/**
	 * Overloading Article's getContentObject method.
	 *
	 * Omit noarticletext if sharedupload; text will be fetched from the
	 * shared upload server if possible.
	 * @return string
	 */
	public function getContentObject() {
		$this->loadFile();
		if ( $this->mPage->getFile() && !$this->mPage->getFile()->isLocal() && 0 == $this->getId() ) {
			return null;
		}
		return parent::getContentObject();
	}

	protected function openShowImage() {
		global $wgEnableUploads, $wgSend404Code, $wgSVGMaxSize;

		$this->loadFile();
		$out = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$lang = $this->getContext()->getLanguage();
		$dirmark = $lang->getDirMarkEntity();
		$request = $this->getContext()->getRequest();

		$max = $this->getImageLimitsFromOption( $user, 'imagesize' );
		$maxWidth = $max[0];
		$maxHeight = $max[1];

		if ( $this->displayImg->exists() ) {
			# image
			$page = $request->getIntOrNull( 'page' );
			if ( is_null( $page ) ) {
				$params = [];
				$page = 1;
			} else {
				$params = [ 'page' => $page ];
			}

			$renderLang = $request->getVal( 'lang' );
			if ( !is_null( $renderLang ) ) {
				$handler = $this->displayImg->getHandler();
				if ( $handler && $handler->validateParam( 'lang', $renderLang ) ) {
					$params['lang'] = $renderLang;
				} else {
					$renderLang = null;
				}
			}

			$width_orig = $this->displayImg->getWidth( $page );
			$width = $width_orig;
			$height_orig = $this->displayImg->getHeight( $page );
			$height = $height_orig;

			$filename = wfEscapeWikiText( $this->displayImg->getName() );
			$linktext = $filename;

			// Avoid PHP 7.1 warning from passing $this by reference
			$imagePage = $this;

			Hooks::run( 'ImageOpenShowImageInlineBefore', [ &$imagePage, &$out ] );

			if ( $this->displayImg->allowInlineDisplay() ) {
				# image
				# "Download high res version" link below the image
				# $msgsize = $this->getContext()->msg( 'file-info-size', $width_orig, $height_orig,
				#   Linker::formatSize( $this->displayImg->getSize() ), $mime )->escaped();
				# We'll show a thumbnail of this image
				if ( $width > $maxWidth || $height > $maxHeight || $this->displayImg->isVectorized() ) {
					list( $width, $height ) = $this->getDisplayWidthHeight(
						$maxWidth, $maxHeight, $width, $height
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

					if ( $page > 1 ) {
						$label = $out->parse( $this->getContext()->msg( 'imgmultipageprev' )->text(), false );
						// on the client side, this link is generated in ajaxifyPageNavigation()
						// in the mediawiki.page.image.pagination module
						$link = Linker::linkKnown(
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
						$link = Linker::linkKnown(
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
				// @codingStandardsIgnoreStart Ignore long line
				$out->addWikiText( <<<EOT
<div class="fullMedia"><span class="dangerousLink">{$medialink}</span> $dirmark<span class="fileInfo">$longDesc</span></div>
<div class="mediaWarning">$warning</div>
EOT
				);
				// @codingStandardsIgnoreEnd
			} else {
				$out->addWikiText( <<<EOT
<div class="fullMedia">{$medialink} {$dirmark}<span class="fileInfo">$longDesc</span>
</div>
EOT
				);
			}

			$renderLangOptions = $this->displayImg->getAvailableLanguages();
			if ( count( $renderLangOptions ) >= 1 ) {
				$currentLanguage = $renderLang;
				$defaultLang = $this->displayImg->getDefaultRenderLanguage();
				if ( is_null( $currentLanguage ) ) {
					$currentLanguage = $defaultLang;
				}
				$out->addHTML( $this->doRenderLangOpt( $renderLangOptions, $currentLanguage, $defaultLang ) );
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

				$out->addWikiText( <<<EOT
<div class="mw-noanimatethumb">{$noAnimMesg}</div>
EOT
				);
			}

			if ( !$this->displayImg->isLocal() ) {
				$this->printSharedImageText();
			}
		} else {
			# Image does not exist
			if ( !$this->getId() ) {
				$dbr = wfGetDB( DB_REPLICA );

				# No article exists either
				# Show deletion log to be consistent with normal articles
				LogEventsList::showLogExtract(
					$out,
					[ 'delete', 'move' ],
					$this->getTitle()->getPrefixedText(),
					'',
					[ 'lim' => 10,
						'conds' => [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ],
						'showIfEmpty' => false,
						'msgKey' => [ 'moveddeleted-notice' ]
					]
				);
			}

			if ( $wgEnableUploads && $user->isAllowed( 'upload' ) ) {
				// Only show an upload link if the user can upload
				$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
				$nofile = [
					'filepage-nofile-link',
					$uploadTitle->getFullURL( [ 'wpDestFile' => $this->mPage->getFile()->getName() ] )
				];
			} else {
				$nofile = 'filepage-nofile';
			}
			// Note, if there is an image description page, but
			// no image, then this setRobotPolicy is overridden
			// by Article::View().
			$out->setRobotPolicy( 'noindex,nofollow' );
			$out->wrapWikiMsg( "<div id='mw-imagepage-nofile' class='plainlinks'>\n$1\n</div>", $nofile );
			if ( !$this->getId() && $wgSend404Code ) {
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
	 * @param $params array parameters for thumbnail
	 * @param $sizeLinkBigImagePreview HTML for the current size
	 * @return string HTML output
	 */
	private function getThumbPrevText( $params, $sizeLinkBigImagePreview ) {
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
	private function makeSizeLink( $params, $width, $height ) {
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

		$descUrl = $this->mPage->getFile()->getDescriptionUrl();
		$descText = $this->mPage->getFile()->getDescriptionText( $this->getContext()->getLanguage() );

		/* Add canonical to head if there is no local page for this shared file */
		if ( $descUrl && $this->mPage->getId() == 0 ) {
			$out->setCanonicalUrl( $descUrl );
		}

		$wrap = "<div class=\"sharedUploadNotice\">\n$1\n</div>\n";
		$repo = $this->mPage->getFile()->getRepo()->getDisplayName();

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
			'wpDestFile' => $this->mPage->getFile()->getName(),
			'wpForReUpload' => 1
		] );
	}

	/**
	 * Print out the various links at the bottom of the image page, e.g. reupload,
	 * external editing (and instructions link) etc.
	 */
	protected function uploadLinksBox() {
		global $wgEnableUploads;

		if ( !$wgEnableUploads ) {
			return;
		}

		$this->loadFile();
		if ( !$this->mPage->getFile()->isLocal() ) {
			return;
		}

		$out = $this->getContext()->getOutput();
		$out->addHTML( "<ul>\n" );

		# "Upload a new version of this file" link
		$canUpload = $this->getTitle()->quickUserCan( 'upload', $this->getContext()->getUser() );
		if ( $canUpload && UploadBase::userCanReUpload(
				$this->getContext()->getUser(),
				$this->mPage->getFile() )
		) {
			$ulink = Linker::makeExternalLink(
				$this->getUploadUrl(),
				$this->getContext()->msg( 'uploadnewversion-linktext' )->text()
			);
			$out->addHTML( "<li id=\"mw-imagepage-reupload-link\">"
				. "<div class=\"plainlinks\">{$ulink}</div></li>\n" );
		} else {
			$out->addHTML( "<li id=\"mw-imagepage-upload-disallowed\">"
				. $this->getContext()->msg( 'upload-disallowed-here' )->escaped() . "</li>\n" );
		}

		$out->addHTML( "</ul>\n" );
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

		$this->mPage->getFile()->resetHistory(); // free db resources

		# Exist check because we don't want to show this on pages where an image
		# doesn't exist along with the noimage message, that would suck. -ævar
		if ( $this->mPage->getFile()->exists() ) {
			$this->uploadLinksBox();
		}
	}

	/**
	 * @param string $target
	 * @param int $limit
	 * @return ResultWrapper
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
		$count = 0;

		// Sort the list by namespace:title
		usort( $rows, [ $this, 'compare' ] );

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
			$link = Linker::linkKnown(
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

					$link2 = Linker::linkKnown( Title::makeTitle( $row->page_namespace, $row->page_title ) );
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

		};
		$out->addHTML( Html::closeElement( 'ul' ) . "\n" );
		$res->free();

		// Add a links to [[Special:Whatlinkshere]]
		if ( $count > $limit ) {
			$out->addWikiMsg( 'morelinkstoimage', $this->getTitle()->getPrefixedDBkey() );
		}
		$out->addHTML( Html::closeElement( 'div' ) . "\n" );
	}

	protected function imageDupes() {
		$this->loadFile();
		$out = $this->getContext()->getOutput();

		$dupes = $this->mPage->getDuplicates();
		if ( count( $dupes ) == 0 ) {
			return;
		}

		$out->addHTML( "<div id='mw-imagepage-section-duplicates'>\n" );
		$out->addWikiMsg( 'duplicatesoffile',
			$this->getContext()->getLanguage()->formatNum( count( $dupes ) ), $this->getTitle()->getDBkey()
		);
		$out->addHTML( "<ul class='mw-imagepage-duplicates'>\n" );

		/**
		 * @var $file File
		 */
		foreach ( $dupes as $file ) {
			$fromSrc = '';
			if ( $file->isLocal() ) {
				$link = Linker::linkKnown( $file->getTitle() );
			} else {
				$link = Linker::makeExternalLink( $file->getDescriptionUrl(),
					$file->getTitle()->getPrefixedText() );
				$fromSrc = $this->getContext()->msg(
					'shared-repo-from',
					$file->getRepo()->getDisplayName()
				)->text();
			}
			$out->addHTML( "<li>{$link} {$fromSrc}</li>\n" );
		}
		$out->addHTML( "</ul></div>\n" );
	}

	/**
	 * Delete the file, or an earlier version of it
	 */
	public function delete() {
		$file = $this->mPage->getFile();
		if ( !$file->exists() || !$file->isLocal() || $file->getRedirected() ) {
			// Standard article deletion
			parent::delete();
			return;
		}

		$deleter = new FileDeleteForm( $file );
		$deleter->execute();
	}

	/**
	 * Display an error with a wikitext description
	 *
	 * @param string $description
	 */
	function showError( $description ) {
		$out = $this->getContext()->getOutput();
		$out->setPageTitle( $this->getContext()->msg( 'internalerror' ) );
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setArticleRelated( false );
		$out->enableClientCache( false );
		$out->addWikiText( $description );
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
		if ( $a->page_namespace == $b->page_namespace ) {
			return strcmp( $a->page_title, $b->page_title );
		} else {
			return $a->page_namespace - $b->page_namespace;
		}
	}

	/**
	 * Returns the corresponding $wgImageLimits entry for the selected user option
	 *
	 * @param User $user
	 * @param string $optionName Name of a option to check, typically imagesize or thumbsize
	 * @return array
	 * @since 1.21
	 */
	public function getImageLimitsFromOption( $user, $optionName ) {
		global $wgImageLimits;

		$option = $user->getIntOption( $optionName );
		if ( !isset( $wgImageLimits[$option] ) ) {
			$option = User::getDefaultOption( $optionName );
		}

		// The user offset might still be incorrect, specially if
		// $wgImageLimits got changed (see bug #8858).
		if ( !isset( $wgImageLimits[$option] ) ) {
			// Default to the first offset in $wgImageLimits
			$option = 0;
		}

		return isset( $wgImageLimits[$option] )
			? $wgImageLimits[$option]
			: [ 800, 600 ]; // if nothing is set, fallback to a hardcoded default
	}

	/**
	 * Output a drop-down box for language options for the file
	 *
	 * @param array $langChoices Array of string language codes
	 * @param string $curLang Language code file is being viewed in.
	 * @param string $defaultLang Language code that image is rendered in by default
	 * @return string HTML to insert underneath image.
	 */
	protected function doRenderLangOpt( array $langChoices, $curLang, $defaultLang ) {
		global $wgScript;
		sort( $langChoices );
		$curLang = wfBCP47( $curLang );
		$defaultLang = wfBCP47( $defaultLang );
		$opts = '';
		$haveCurrentLang = false;
		$haveDefaultLang = false;

		// We make a list of all the language choices in the file.
		// Additionally if the default language to render this file
		// is not included as being in this file (for example, in svgs
		// usually the fallback content is the english content) also
		// include a choice for that. Last of all, if we're viewing
		// the file in a language not on the list, add it as a choice.
		foreach ( $langChoices as $lang ) {
			$code = wfBCP47( $lang );
			$name = Language::fetchLanguageName( $code, $this->getContext()->getLanguage()->getCode() );
			if ( $name !== '' ) {
				$display = $this->getContext()->msg( 'img-lang-opt', $code, $name )->text();
			} else {
				$display = $code;
			}
			$opts .= "\n" . Xml::option( $display, $code, $curLang === $code );
			if ( $curLang === $code ) {
				$haveCurrentLang = true;
			}
			if ( $defaultLang === $code ) {
				$haveDefaultLang = true;
			}
		}
		if ( !$haveDefaultLang ) {
			// Its hard to know if the content is really in the default language, or
			// if its just unmarked content that could be in any language.
			$opts = Xml::option(
					$this->getContext()->msg( 'img-lang-default' )->text(),
				$defaultLang,
				$defaultLang === $curLang
			) . $opts;
		}
		if ( !$haveCurrentLang && $defaultLang !== $curLang ) {
			$name = Language::fetchLanguageName( $curLang, $this->getContext()->getLanguage()->getCode() );
			if ( $name !== '' ) {
				$display = $this->getContext()->msg( 'img-lang-opt', $curLang, $name )->text();
			} else {
				$display = $curLang;
			}
			$opts = Xml::option( $display, $curLang, true ) . $opts;
		}

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
	 * Get the width and height to display image at.
	 *
	 * @note This method assumes that it is only called if one
	 *  of the dimensions are bigger than the max, or if the
	 *  image is vectorized.
	 *
	 * @param int $maxWidth Max width to display at
	 * @param int $maxHeight Max height to display at
	 * @param int $width Actual width of the image
	 * @param int $height Actual height of the image
	 * @throws MWException
	 * @return array Array (width, height)
	 */
	protected function getDisplayWidthHeight( $maxWidth, $maxHeight, $width, $height ) {
		if ( !$maxWidth || !$maxHeight ) {
			// should never happen
			throw new MWException( 'Using a choice from $wgImageLimits that is 0x0' );
		}

		if ( !$width || !$height ) {
			return [ 0, 0 ];
		}

		# Calculate the thumbnail size.
		if ( $width <= $maxWidth && $height <= $maxHeight ) {
			// Vectorized image, do nothing.
		} elseif ( $width / $height >= $maxWidth / $maxHeight ) {
			# The limiting factor is the width, not the height.
			$height = round( $height * $maxWidth / $width );
			$width = $maxWidth;
			# Note that $height <= $maxHeight now.
		} else {
			$newwidth = floor( $width * $maxHeight / $height );
			$height = round( $height * $newwidth / $width );
			$width = $newwidth;
			# Note that $height <= $maxHeight now, but might not be identical
			# because of rounding.
		}
		return [ $width, $height ];
	}

	/**
	 * Get alternative thumbnail sizes.
	 *
	 * @note This will only list several alternatives if thumbnails are rendered on 404
	 * @param int $origWidth Actual width of image
	 * @param int $origHeight Actual height of image
	 * @return array An array of [width, height] pairs.
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
		return $this->mPage->getFile();
	}

	/**
	 * @see WikiFilePage::isLocal
	 * @return bool
	 */
	public function isLocal() {
		return $this->mPage->isLocal();
	}

	/**
	 * @see WikiFilePage::getDuplicates
	 * @return array|null
	 */
	public function getDuplicates() {
		return $this->mPage->getDuplicates();
	}

	/**
	 * @see WikiFilePage::getForeignCategories
	 * @return TitleArray|Title[]
	 */
	public function getForeignCategories() {
		$this->mPage->getForeignCategories();
	}

}
