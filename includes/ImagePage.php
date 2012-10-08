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

/**
 * Class for viewing MediaWiki file description pages
 *
 * @ingroup Media
 */
class ImagePage extends Article {

	/**
	 * @var File
	 */
	private $displayImg;
	/**
	 * @var FileRepo
	 */
	private $repo;
	private $fileLoaded;

	var $mExtraDescription = false;

	/**
	 * @param $title Title
	 * @return WikiFilePage
	 */
	protected function newPage( Title $title ) {
		// Overload mPage with a file-specific page
		return new WikiFilePage( $title );
	}

	/**
	 * Constructor from a page id
	 * @param $id Int article ID to load
	 * @return ImagePage|null
	 */
	public static function newFromID( $id ) {
		$t = Title::newFromID( $id );
		# @todo FIXME: Doesn't inherit right
		return $t == null ? null : new self( $t );
		# return $t == null ? null : new static( $t ); // PHP 5.3
	}

	/**
	 * @param $file File:
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
		wfRunHooks( 'ImagePageFindFile', array( $this, &$img, &$this->displayImg ) );
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
		$diffOnly = $request->getBool( 'diffonly', $this->getContext()->getUser()->getOption( 'diffonly' ) );

		if ( $this->getTitle()->getNamespace() != NS_FILE || ( isset( $diff ) && $diffOnly ) ) {
			parent::view();
			return;
		}

		$this->loadFile();

		if ( $this->getTitle()->getNamespace() == NS_FILE && $this->mPage->getFile()->getRedirected() ) {
			if ( $this->getTitle()->getDBkey() == $this->mPage->getFile()->getName() || isset( $diff ) ) {
				// mTitle is the same as the redirect target so ask Article
				// to perform the redirect for us.
				$request->setVal( 'diffonly', 'true' );
				parent::view();
				return;
			} else {
				// mTitle is not the same as the redirect target so it is
				// probably the redirect page itself. Fake the redirect symbol
				$out->setPageTitle( $this->getTitle()->getPrefixedText() );
				$out->addHTML( $this->viewRedirect( Title::makeTitle( NS_FILE, $this->mPage->getFile()->getName() ),
					/* $appendSubtitle */ true, /* $forceKnown */ true ) );
				$this->mPage->doViewUpdates( $this->getContext()->getUser() );
				return;
			}
		}

		if ( $wgShowEXIF && $this->displayImg->exists() ) {
			// @todo FIXME: Bad interface, see note on MediaHandler::formatMetadata().
			$formattedMetadata = $this->displayImg->formatMetadata();
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
		if ( $this->mPage->getID() ) {
			# NS_FILE is in the user language, but this section (the actual wikitext)
			# should be in page content language
			$pageLang = $this->getTitle()->getPageViewLanguage();
			$out->addHTML( Xml::openElement( 'div', array( 'id' => 'mw-imagepage-content',
				'lang' => $pageLang->getHtmlCode(), 'dir' => $pageLang->getDir(),
				'class' => 'mw-content-'.$pageLang->getDir() ) ) );

			parent::view();

			$out->addHTML( Xml::closeElement( 'div' ) );
		} else {
			# Just need to set the right headers
			$out->setArticleFlag( true );
			$out->setPageTitle( $this->getTitle()->getPrefixedText() );
			$this->mPage->doViewUpdates( $this->getContext()->getUser() );
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = wfMessage( 'shareddescriptionfollows' );
			if ( !$fol->isDisabled() ) {
				$out->addWikiText( $fol->plain() );
			}
			$out->addHTML( '<div id="shared-image-desc">' . $this->mExtraDescription . "</div>\n" );
		}

		$this->closeShowImage();
		$this->imageHistory();
		// TODO: Cleanup the following

		$out->addHTML( Xml::element( 'h2',
			array( 'id' => 'filelinks' ),
			wfMessage( 'imagelinks' )->text() ) . "\n" );
		$this->imageDupes();
		# @todo FIXME: For some freaky reason, we can't redirect to foreign images.
		# Yet we return metadata about the target. Definitely an issue in the FileRepo
		$this->imageLinks();

		# Allow extensions to add something after the image links
		$html = '';
		wfRunHooks( 'ImagePageAfterImageLinks', array( $this, &$html ) );
		if ( $html ) {
			$out->addHTML( $html );
		}

		if ( $showmeta ) {
			$out->addHTML( Xml::element(
				'h2',
				array( 'id' => 'metadata' ),
				wfMessage( 'metadata' )->text() ) . "\n" );
			$out->addWikiText( $this->makeMetadataTable( $formattedMetadata ) );
			$out->addModules( array( 'mediawiki.action.view.metadata' ) );
		}

		// Add remote Filepage.css
		if( !$this->repo->isLocal() ) {
			$css = $this->repo->getDescriptionStylesheetUrl();
			if ( $css ) {
				$out->addStyle( $css );
			}
		}
		// always show the local local Filepage.css, bug 29277
		$out->addModuleStyles( 'filepage' );
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
	 * @param $metadata Boolean: whether or not to show the metadata link
	 * @return String
	 */
	protected function showTOC( $metadata ) {
		$r = array(
			'<li><a href="#file">' . wfMessage( 'file-anchor-link' )->escaped() . '</a></li>',
			'<li><a href="#filehistory">' . wfMessage( 'filehist' )->escaped() . '</a></li>',
			'<li><a href="#filelinks">' . wfMessage( 'imagelinks' )->escaped() . '</a></li>',
		);
		if ( $metadata ) {
			$r[] = '<li><a href="#metadata">' . wfMessage( 'metadata' )->escaped() . '</a></li>';
		}

		wfRunHooks( 'ImagePageShowTOC', array( $this, &$r ) );

		return '<ul id="filetoc">' . implode( "\n", $r ) . '</ul>';
	}

	/**
	 * Make a table with metadata to be shown in the output page.
	 *
	 * @todo FIXME: Bad interface, see note on MediaHandler::formatMetadata().
	 *
	 * @param $metadata Array: the array containing the EXIF data
	 * @return String The metadata table. This is treated as Wikitext (!)
	 */
	protected function makeMetadataTable( $metadata ) {
		$r = "<div class=\"mw-imagepage-section-metadata\">";
		$r .= wfMessage( 'metadata-help' )->plain();
		$r .= "<table id=\"mw_metadata\" class=\"mw_metadata\">\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $v ) {
				# @todo FIXME: Why is this using escapeId for a class?!
				$class = Sanitizer::escapeId( $v['id'] );
				if ( $type == 'collapsed' ) {
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
		if ( $this->mPage->getFile() && !$this->mPage->getFile()->isLocal() && 0 == $this->getID() ) {
			return null;
		}
		return parent::getContentObject();
	}

	protected function openShowImage() {
		global $wgImageLimits, $wgEnableUploads, $wgSend404Code;

		$this->loadFile();
		$out = $this->getContext()->getOutput();
		$user = $this->getContext()->getUser();
		$lang = $this->getContext()->getLanguage();
		$dirmark = $lang->getDirMarkEntity();
		$request = $this->getContext()->getRequest();

		$sizeSel = intval( $user->getOption( 'imagesize' ) );
		if ( !isset( $wgImageLimits[$sizeSel] ) ) {
			$sizeSel = User::getDefaultOption( 'imagesize' );

			// The user offset might still be incorrect, specially if
			// $wgImageLimits got changed (see bug #8858).
			if ( !isset( $wgImageLimits[$sizeSel] ) ) {
				// Default to the first offset in $wgImageLimits
				$sizeSel = 0;
			}
		}
		$max = $wgImageLimits[$sizeSel];
		$maxWidth = $max[0];
		$maxHeight = $max[1];

		if ( $this->displayImg->exists() ) {
			# image
			$page = $request->getIntOrNull( 'page' );
			if ( is_null( $page ) ) {
				$params = array();
				$page = 1;
			} else {
				$params = array( 'page' => $page );
			}
			$width_orig = $this->displayImg->getWidth( $page );
			$width = $width_orig;
			$height_orig = $this->displayImg->getHeight( $page );
			$height = $height_orig;

			$longDesc = wfMessage( 'parentheses', $this->displayImg->getLongDesc() )->text();

			wfRunHooks( 'ImageOpenShowImageInlineBefore', array( &$this, &$out ) );

			if ( $this->displayImg->allowInlineDisplay() ) {
				# image

				# "Download high res version" link below the image
				# $msgsize = wfMessage( 'file-info-size', $width_orig, $height_orig, Linker::formatSize( $this->displayImg->getSize() ), $mime )->escaped();
				# We'll show a thumbnail of this image
				if ( $width > $maxWidth || $height > $maxHeight ) {
					# Calculate the thumbnail size.
					# First case, the limiting factor is the width, not the height.
					if ( $width / $height >= $maxWidth / $maxHeight ) {
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
					$msgbig = wfMessage( 'show-big-image' )->escaped();
					if ( $this->displayImg->getRepo()->canTransformVia404() ) {
						$thumbSizes = $wgImageLimits;
					} else {
						# Creating thumb links triggers thumbnail generation.
						# Just generate the thumb for the current users prefs.
						$thumbOption = $user->getOption( 'thumbsize' );
						$thumbSizes = array( isset( $wgImageLimits[$thumbOption] )
							? $wgImageLimits[$thumbOption]
							: $wgImageLimits[User::getDefaultOption( 'thumbsize' )] );
					}
					# Generate thumbnails or thumbnail links as needed...
					$otherSizes = array();
					foreach ( $thumbSizes as $size ) {
						if ( $size[0] < $width_orig && $size[1] < $height_orig
							&& $size[0] != $width && $size[1] != $height )
						{
							$otherSizes[] = $this->makeSizeLink( $params, $size[0], $size[1] );
						}
					}
					$msgsmall = wfMessage( 'show-big-image-preview' )->
						rawParams( $this->makeSizeLink( $params, $width, $height ) )->
						parse();
					if ( count( $otherSizes ) ) {
						$msgsmall .= ' ' .
						Html::rawElement( 'span', array( 'class' => 'mw-filepage-other-resolutions' ),
							wfMessage( 'show-big-image-other' )->rawParams( $lang->pipeList( $otherSizes ) )->
							params( count( $otherSizes ) )->parse()
						);
					}
				} elseif ( $width == 0 && $height == 0 ){
					# Some sort of audio file that doesn't have dimensions
					# Don't output a no hi res message for such a file
					$msgsmall = '';
				} elseif ( $this->displayImg->isVectorized() ) {
					# For vectorized images, full size is just the frame size
					$msgsmall = '';
				} else {
					# Image is small enough to show full size on image page
					$msgsmall = wfMessage( 'file-nohires' )->parse();
				}

				$params['width'] = $width;
				$params['height'] = $height;
				$thumbnail = $this->displayImg->transform( $params );

				$showLink = true;
				$anchorclose = Html::rawElement( 'div', array( 'class' => 'mw-filepage-resolutioninfo' ), $msgsmall );

				$isMulti = $this->displayImg->isMultipage() && $this->displayImg->pageCount() > 1;
				if ( $isMulti ) {
					$out->addHTML( '<table class="multipageimage"><tr><td>' );
				}

				if ( $thumbnail ) {
					$options = array(
						'alt' => $this->displayImg->getTitle()->getPrefixedText(),
						'file-link' => true,
					);
					$out->addHTML( '<div class="fullImageLink" id="file">' .
						$thumbnail->toHtml( $options ) .
						$anchorclose . "</div>\n" );
				}

				if ( $isMulti ) {
					$count = $this->displayImg->pageCount();

					if ( $page > 1 ) {
						$label = $out->parse( wfMessage( 'imgmultipageprev' )->text(), false );
						$link = Linker::linkKnown(
							$this->getTitle(),
							$label,
							array(),
							array( 'page' => $page - 1 )
						);
						$thumb1 = Linker::makeThumbLinkObj( $this->getTitle(), $this->displayImg, $link, $label, 'none',
							array( 'page' => $page - 1 ) );
					} else {
						$thumb1 = '';
					}

					if ( $page < $count ) {
						$label = wfMessage( 'imgmultipagenext' )->text();
						$link = Linker::linkKnown(
							$this->getTitle(),
							$label,
							array(),
							array( 'page' => $page + 1 )
						);
						$thumb2 = Linker::makeThumbLinkObj( $this->getTitle(), $this->displayImg, $link, $label, 'none',
							array( 'page' => $page + 1 ) );
					} else {
						$thumb2 = '';
					}

					global $wgScript;

					$formParams = array(
						'name' => 'pageselector',
						'action' => $wgScript,
						'onchange' => 'document.pageselector.submit();',
					);
					$options = array();
					for ( $i = 1; $i <= $count; $i++ ) {
						$options[] = Xml::option( $lang->formatNum( $i ), $i, $i == $page );
					}
					$select = Xml::tags( 'select',
						array( 'id' => 'pageselector', 'name' => 'page' ),
						implode( "\n", $options ) );

					$out->addHTML(
						'</td><td><div class="multipageimagenavbox">' .
						Xml::openElement( 'form', $formParams ) .
						Html::hidden( 'title', $this->getTitle()->getPrefixedDBkey() ) .
							wfMessage( 'imgmultigoto' )->rawParams( $select )->parse() .
						Xml::submitButton( wfMessage( 'imgmultigo' )->text() ) .
						Xml::closeElement( 'form' ) .
						"<hr />$thumb1\n$thumb2<br style=\"clear: both\" /></div></td></tr></table>"
					);
				}
			} else {
				# if direct link is allowed but it's not a renderable image, show an icon.
				if ( $this->displayImg->isSafeFile() ) {
					$icon = $this->displayImg->iconThumb();

					$out->addHTML( '<div class="fullImageLink" id="file">' .
						$icon->toHtml( array( 'file-link' => true ) ) .
						"</div>\n" );
				}

				$showLink = true;
			}

			if ( $showLink ) {
				$filename = wfEscapeWikiText( $this->displayImg->getName() );
				$linktext = $filename;
				if ( isset( $msgbig ) ) {
					$linktext = wfEscapeWikiText( $msgbig );
				}
				$medialink = "[[Media:$filename|$linktext]]";

				if ( !$this->displayImg->isSafeFile() ) {
					$warning = wfMessage( 'mediawarning' )->plain();
					// dirmark is needed here to separate the file name, which
					// most likely ends in Latin characters, from the description,
					// which may begin with the file type. In RTL environment
					// this will get messy.
					// The dirmark, however, must not be immediately adjacent
					// to the filename, because it can get copied with it.
					// See bug 25277.
					$out->addWikiText( <<<EOT
<div class="fullMedia"><span class="dangerousLink">{$medialink}</span> $dirmark<span class="fileInfo">$longDesc</span></div>
<div class="mediaWarning">$warning</div>
EOT
						);
				} else {
					$out->addWikiText( <<<EOT
<div class="fullMedia">{$medialink} {$dirmark}<span class="fileInfo">$longDesc</span>
</div>
EOT
					);
				}
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
			if ( !$this->getID() ) {
				# No article exists either
				# Show deletion log to be consistent with normal articles
				LogEventsList::showLogExtract(
					$out,
					array( 'delete', 'move' ),
					$this->getTitle()->getPrefixedText(),
					'',
					array(  'lim' => 10,
						'conds' => array( "log_action != 'revision'" ),
						'showIfEmpty' => false,
						'msgKey' => array( 'moveddeleted-notice' )
					)
				);
			}

			if ( $wgEnableUploads && $user->isAllowed( 'upload' ) ) {
				// Only show an upload link if the user can upload
				$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
				$nofile = array(
					'filepage-nofile-link',
					$uploadTitle->getFullURL( array( 'wpDestFile' => $this->mPage->getFile()->getName() ) )
				);
			} else {
				$nofile = 'filepage-nofile';
			}
			// Note, if there is an image description page, but
			// no image, then this setRobotPolicy is overriden
			// by Article::View().
			$out->setRobotPolicy( 'noindex,nofollow' );
			$out->wrapWikiMsg( "<div id='mw-imagepage-nofile' class='plainlinks'>\n$1\n</div>", $nofile );
			if ( !$this->getID() && $wgSend404Code ) {
				// If there is no image, no shared image, and no description page,
				// output a 404, to be consistent with articles.
				$request->response()->header( 'HTTP/1.1 404 Not Found' );
			}
		}
		$out->setFileVersion( $this->displayImg );
	}

	/**
	 * Creates an thumbnail of specified size and returns an HTML link to it
	 * @param $params array Scaler parameters
	 * @param $width int
	 * @param $height int
	 * @return string
	 */
	private function makeSizeLink( $params, $width, $height ) {
		$params['width'] = $width;
		$params['height'] = $height;
		$thumbnail = $this->displayImg->transform( $params );
		if ( $thumbnail && !$thumbnail->isError() ) {
			return Html::rawElement( 'a', array(
				'href' => $thumbnail->getUrl(),
				'class' => 'mw-thumbnail-link'
				), wfMessage( 'show-big-image-size' )->numParams(
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
		$descText = $this->mPage->getFile()->getDescriptionText();

		/* Add canonical to head if there is no local page for this shared file */
		if( $descUrl && $this->mPage->getID() == 0 ) {
			$out->addLink( array( 'rel' => 'canonical', 'href' => $descUrl ) );
		}

		$wrap = "<div class=\"sharedUploadNotice\">\n$1\n</div>\n";
		$repo = $this->mPage->getFile()->getRepo()->getDisplayName();

		if ( $descUrl && $descText && wfMessage( 'sharedupload-desc-here' )->plain() !== '-'  ) {
			$out->wrapWikiMsg( $wrap, array( 'sharedupload-desc-here', $repo, $descUrl ) );
		} elseif ( $descUrl && wfMessage( 'sharedupload-desc-there' )->plain() !== '-' ) {
			$out->wrapWikiMsg( $wrap, array( 'sharedupload-desc-there', $repo, $descUrl ) );
		} else {
			$out->wrapWikiMsg( $wrap, array( 'sharedupload', $repo ), ''/*BACKCOMPAT*/ );
		}

		if ( $descText ) {
			$this->mExtraDescription = $descText;
		}
	}

	public function getUploadUrl() {
		$this->loadFile();
		$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
		return $uploadTitle->getFullURL( array(
			'wpDestFile' => $this->mPage->getFile()->getName(),
			'wpForReUpload' => 1
		 ) );
	}

	/**
	 * Print out the various links at the bottom of the image page, e.g. reupload,
	 * external editing (and instructions link) etc.
	 */
	protected function uploadLinksBox() {
		global $wgEnableUploads, $wgUseExternalEditor;

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
		$canUpload = $this->getTitle()->userCan( 'upload', $this->getContext()->getUser() );
		if ( $canUpload && UploadBase::userCanReUpload( $this->getContext()->getUser(), $this->mPage->getFile()->name ) ) {
			$ulink = Linker::makeExternalLink( $this->getUploadUrl(), wfMessage( 'uploadnewversion-linktext' )->text() );
			$out->addHTML( "<li id=\"mw-imagepage-reupload-link\"><div class=\"plainlinks\">{$ulink}</div></li>\n" );
		} else {
			$out->addHTML( "<li id=\"mw-imagepage-upload-disallowed\">" . $this->getContext()->msg( 'upload-disallowed-here' )->escaped() . "</li>\n" );
		}

		# External editing link
		if ( $wgUseExternalEditor ) {
			$elink = Linker::linkKnown(
				$this->getTitle(),
				wfMessage( 'edit-externally' )->escaped(),
				array(),
				array(
					'action' => 'edit',
					'externaledit' => 'true',
					'mode' => 'file'
				)
			);
			$out->addHTML(
				'<li id="mw-imagepage-edit-external">' . $elink . ' <small>' .
					wfMessage( 'edit-externally-help' )->parse() .
					"</small></li>\n"
			);
		}

		$out->addHTML( "</ul>\n" );
	}

	protected function closeShowImage() { } # For overloading

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
	 * @param $target
	 * @param $limit
	 * @return ResultWrapper
	 */
	protected function queryImageLinks( $target, $limit ) {
		$dbr = wfGetDB( DB_SLAVE );

		return $dbr->select(
			array( 'imagelinks', 'page' ),
			array( 'page_namespace', 'page_title', 'page_is_redirect', 'il_to' ),
			array( 'il_to' => $target, 'il_from = page_id' ),
			__METHOD__,
			array( 'LIMIT' => $limit + 1, 'ORDER BY' => 'il_from', )
		);
	}

	protected function imageLinks() {
		$limit = 100;

		$out = $this->getContext()->getOutput();
		$res = $this->queryImageLinks( $this->getTitle()->getDbKey(), $limit + 1);
		$rows = array();
		$redirects = array();
		foreach ( $res as $row ) {
			if ( $row->page_is_redirect ) {
				$redirects[$row->page_title] = array();
			}
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
					array( 'id' => 'mw-imagepage-nolinkstoimage' ), "\n$1\n" ),
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
				array( 'class' => 'mw-imagepage-linkstoimage' ) ) . "\n"
		);
		$count = 0;

		// Sort the list by namespace:title
		usort( $rows, array( $this, 'compare' ) );

		// Create links for every element
		$currentCount = 0;
		foreach( $rows as $element ) {
			$currentCount++;
			if ( $currentCount > $limit ) {
				break;
			}

			$link = Linker::linkKnown( Title::makeTitle( $element->page_namespace, $element->page_title ) );
			if ( !isset( $redirects[$element->page_title] ) ) {
				$liContents = $link;
			} else {
				$ul = "<ul class='mw-imagepage-redirectstofile'>\n";
				foreach ( $redirects[$element->page_title] as $row ) {
					$currentCount++;
					if ( $currentCount > $limit ) {
						break;
					}

					$link2 = Linker::linkKnown( Title::makeTitle( $row->page_namespace, $row->page_title ) );
					$ul .= Html::rawElement(
						'li',
						array( 'class' => 'mw-imagepage-linkstoimage-ns' . $element->page_namespace ),
						$link2
						) . "\n";
				}
				$ul .= '</ul>';
				$liContents = wfMessage( 'linkstoimage-redirect' )->rawParams(
					$link, $ul )->parse();
			}
			$out->addHTML( Html::rawElement(
					'li',
					array( 'class' => 'mw-imagepage-linkstoimage-ns' . $element->page_namespace ),
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
				$fromSrc = wfMessage( 'shared-repo-from', $file->getRepo()->getDisplayName() )->text();
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
	 * @param $description String
	 */
	function showError( $description ) {
		$out = $this->getContext()->getOutput();
		$out->setPageTitle( wfMessage( 'internalerror' ) );
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->setArticleRelated( false );
		$out->enableClientCache( false );
		$out->addWikiText( $description );
	}

	/**
	 * Callback for usort() to do link sorts by (namespace, title)
	 * Function copied from Title::compare()
	 *
	 * @param $a object page to compare with
	 * @param $b object page to compare with
	 * @return Integer: result of string comparison, or namespace comparison
	 */
	protected function compare( $a, $b ) {
		if ( $a->page_namespace == $b->page_namespace ) {
			return strcmp( $a->page_title, $b->page_title );
		} else {
			return $a->page_namespace - $b->page_namespace;
		}
	}
}

/**
 * Builds the image revision log shown on image pages
 *
 * @ingroup Media
 */
class ImageHistoryList extends ContextSource {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var File
	 */
	protected $img;

	/**
	 * @var ImagePage
	 */
	protected $imagePage;

	/**
	 * @var File
	 */
	protected $current;

	protected $repo, $showThumb;
	protected $preventClickjacking = false;

	/**
	 * @param ImagePage $imagePage
	 */
	public function __construct( $imagePage ) {
		global $wgShowArchiveThumbnails;
		$this->current = $imagePage->getFile();
		$this->img = $imagePage->getDisplayedFile();
		$this->title = $imagePage->getTitle();
		$this->imagePage = $imagePage;
		$this->showThumb = $wgShowArchiveThumbnails && $this->img->canRender();
		$this->setContext( $imagePage->getContext() );
	}

	/**
	 * @return ImagePage
	 */
	public function getImagePage() {
		return $this->imagePage;
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->img;
	}

	/**
	 * @param $navLinks string
	 * @return string
	 */
	public function beginImageHistoryList( $navLinks = '' ) {
		return Xml::element( 'h2', array( 'id' => 'filehistory' ), $this->msg( 'filehist' )->text() ) . "\n"
			. "<div id=\"mw-imagepage-section-filehistory\">\n"
			. $this->msg( 'filehist-help' )->parseAsBlock()
			. $navLinks . "\n"
			. Xml::openElement( 'table', array( 'class' => 'wikitable filehistory' ) ) . "\n"
			. '<tr><td></td>'
			. ( $this->current->isLocal() && ( $this->getUser()->isAllowedAny( 'delete', 'deletedhistory' ) ) ? '<td></td>' : '' )
			. '<th>' . $this->msg( 'filehist-datetime' )->escaped() . '</th>'
			. ( $this->showThumb ? '<th>' . $this->msg( 'filehist-thumb' )->escaped() . '</th>' : '' )
			. '<th>' . $this->msg( 'filehist-dimensions' )->escaped() . '</th>'
			. '<th>' . $this->msg( 'filehist-user' )->escaped() . '</th>'
			. '<th>' . $this->msg( 'filehist-comment' )->escaped() . '</th>'
			. "</tr>\n";
	}

	/**
	 * @param $navLinks string
	 * @return string
	 */
	public function endImageHistoryList( $navLinks = '' ) {
		return "</table>\n$navLinks\n</div>\n";
	}

	/**
	 * @param $iscur
	 * @param $file File
	 * @return string
	 */
	public function imageHistoryLine( $iscur, $file ) {
		global $wgContLang;

		$user = $this->getUser();
		$lang = $this->getLanguage();
		$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );
		$img = $iscur ? $file->getName() : $file->getArchiveName();
		$userId = $file->getUser( 'id' );
		$userText = $file->getUser( 'text' );
		$description = $file->getDescription( File::FOR_THIS_USER, $user );

		$local = $this->current->isLocal();
		$row = $selected = '';

		// Deletion link
		if ( $local && ( $user->isAllowedAny( 'delete', 'deletedhistory' ) ) ) {
			$row .= '<td>';
			# Link to remove from history
			if ( $user->isAllowed( 'delete' ) ) {
				$q = array( 'action' => 'delete' );
				if ( !$iscur ) {
					$q['oldimage'] = $img;
				}
				$row .= Linker::linkKnown(
					$this->title,
					$this->msg( $iscur ? 'filehist-deleteall' : 'filehist-deleteone' )->escaped(),
					array(), $q
				);
			}
			# Link to hide content. Don't show useless link to people who cannot hide revisions.
			$canHide = $user->isAllowed( 'deleterevision' );
			if ( $canHide || ( $user->isAllowed( 'deletedhistory' ) && $file->getVisibility() ) ) {
				if ( $user->isAllowed( 'delete' ) ) {
					$row .= '<br />';
				}
				// If file is top revision or locked from this user, don't link
				if ( $iscur || !$file->userCan( File::DELETED_RESTRICTED, $user ) ) {
					$del = Linker::revDeleteLinkDisabled( $canHide );
				} else {
					list( $ts, ) = explode( '!', $img, 2 );
					$query = array(
						'type'   => 'oldimage',
						'target' => $this->title->getPrefixedText(),
						'ids'    => $ts,
					);
					$del = Linker::revDeleteLink( $query,
						$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
				}
				$row .= $del;
			}
			$row .= '</td>';
		}

		// Reversion link/current indicator
		$row .= '<td>';
		if ( $iscur ) {
			$row .= $this->msg( 'filehist-current' )->escaped();
		} elseif ( $local && $this->title->quickUserCan( 'edit', $user )
			&& $this->title->quickUserCan( 'upload', $user )
		) {
			if ( $file->isDeleted( File::DELETED_FILE ) ) {
				$row .= $this->msg( 'filehist-revert' )->escaped();
			} else {
				$row .= Linker::linkKnown(
					$this->title,
					$this->msg( 'filehist-revert' )->escaped(),
					array(),
					array(
						'action' => 'revert',
						'oldimage' => $img,
						'wpEditToken' => $user->getEditToken( $img )
					)
				);
			}
		}
		$row .= '</td>';

		// Date/time and image link
		if ( $file->getTimestamp() === $this->img->getTimestamp() ) {
			$selected = "class='filehistory-selected'";
		}
		$row .= "<td $selected style='white-space: nowrap;'>";
		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			# Don't link to unviewable files
			$row .= '<span class="history-deleted">' . $lang->userTimeAndDate( $timestamp, $user ) . '</span>';
		} elseif ( $file->isDeleted( File::DELETED_FILE ) ) {
			if ( $local ) {
				$this->preventClickjacking();
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				# Make a link to review the image
				$url = Linker::linkKnown(
					$revdel,
					$lang->userTimeAndDate( $timestamp, $user ),
					array(),
					array(
						'target' => $this->title->getPrefixedText(),
						'file' => $img,
						'token' => $user->getEditToken( $img )
					)
				);
			} else {
				$url = $lang->userTimeAndDate( $timestamp, $user );
			}
			$row .= '<span class="history-deleted">' . $url . '</span>';
		} else {
			$url = $iscur ? $this->current->getUrl() : $this->current->getArchiveUrl( $img );
			$row .= Xml::element( 'a', array( 'href' => $url ), $lang->userTimeAndDate( $timestamp, $user ) );
		}
		$row .= "</td>";

		// Thumbnail
		if ( $this->showThumb ) {
			$row .= '<td>' . $this->getThumbForLine( $file ) . '</td>';
		}

		// Image dimensions + size
		$row .= '<td>';
		$row .= htmlspecialchars( $file->getDimensionsString() );
		$row .= $this->msg( 'word-separator' )->plain();
		$row .= '<span style="white-space: nowrap;">';
		$row .= $this->msg( 'parentheses' )->rawParams( Linker::formatSize( $file->getSize() ) )->plain();
		$row .= '</span>';
		$row .= '</td>';

		// Uploading user
		$row .= '<td>';
		// Hide deleted usernames
		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$row .= '<span class="history-deleted">' . $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		} else {
			if ( $local ) {
				$row .= Linker::userLink( $userId, $userText );
				$row .= $this->msg( 'word-separator' )->plain();
				$row .= '<span style="white-space: nowrap;">';
				$row .= Linker::userToolLinks( $userId, $userText );
				$row .= '</span>';
			} else {
				$row .= htmlspecialchars( $userText );
			}
		}
		$row .= '</td>';

		// Don't show deleted descriptions
		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$row .= '<td><span class="history-deleted">' . $this->msg( 'rev-deleted-comment' )->escaped() . '</span></td>';
		} else {
			$row .= '<td dir="' . $wgContLang->getDir() . '">' . Linker::formatComment( $description, $this->title ) . '</td>';
		}

		$rowClass = null;
		wfRunHooks( 'ImagePageFileHistoryLine', array( $this, $file, &$row, &$rowClass ) );
		$classAttr = $rowClass ? " class='$rowClass'" : '';

		return "<tr{$classAttr}>{$row}</tr>\n";
	}

	/**
	 * @param $file File
	 * @return string
	 */
	protected function getThumbForLine( $file ) {
		$lang = $this->getLanguage();
		$user = $this->getUser();
		if ( $file->allowInlineDisplay() && $file->userCan( File::DELETED_FILE,$user )
			&& !$file->isDeleted( File::DELETED_FILE ) )
		{
			$params = array(
				'width' => '120',
				'height' => '120',
			);
			$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );

			$thumbnail = $file->transform( $params );
			$options = array(
				'alt' => $this->msg( 'filehist-thumbtext',
					$lang->userTimeAndDate( $timestamp, $user ),
					$lang->userDate( $timestamp, $user ),
					$lang->userTime( $timestamp, $user ) )->text(),
				'file-link' => true,
			);

			if ( !$thumbnail ) {
				return $this->msg( 'filehist-nothumb' )->escaped();
			}

			return $thumbnail->toHtml( $options );
		} else {
			return $this->msg( 'filehist-nothumb' )->escaped();
		}
	}

	/**
	 * @param $enable bool
	 */
	protected function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}
}

class ImageHistoryPseudoPager extends ReverseChronologicalPager {
	protected $preventClickjacking = false;

	/**
	 * @var File
	 */
	protected $mImg;

	/**
	 * @var Title
	 */
	protected $mTitle;

	/**
	 * @param ImagePage $imagePage
	 */
	function __construct( $imagePage ) {
		parent::__construct();
		$this->mImagePage = $imagePage;
		$this->mTitle = clone ( $imagePage->getTitle() );
		$this->mTitle->setFragment( '#filehistory' );
		$this->mImg = null;
		$this->mHist = array();
		$this->mRange = array( 0, 0 ); // display range
	}

	/**
	 * @return Title
	 */
	function getTitle() {
		return $this->mTitle;
	}

	function getQueryInfo() {
		return false;
	}

	/**
	 * @return string
	 */
	function getIndexField() {
		return '';
	}

	/**
	 * @param $row object
	 * @return string
	 */
	function formatRow( $row ) {
		return '';
	}

	/**
	 * @return string
	 */
	function getBody() {
		$s = '';
		$this->doQuery();
		if ( count( $this->mHist ) ) {
			$list = new ImageHistoryList( $this->mImagePage );
			# Generate prev/next links
			$navLink = $this->getNavigationBar();
			$s = $list->beginImageHistoryList( $navLink );
			// Skip rows there just for paging links
			for ( $i = $this->mRange[0]; $i <= $this->mRange[1]; $i++ ) {
				$file = $this->mHist[$i];
				$s .= $list->imageHistoryLine( !$file->isOld(), $file );
			}
			$s .= $list->endImageHistoryList( $navLink );

			if ( $list->getPreventClickjacking() ) {
				$this->preventClickjacking();
			}
		}
		return $s;
	}

	function doQuery() {
		if ( $this->mQueryDone ) {
			return;
		}
		$this->mImg = $this->mImagePage->getFile(); // ensure loading
		if ( !$this->mImg->exists() ) {
			return;
		}
		$queryLimit = $this->mLimit + 1; // limit plus extra row
		if ( $this->mIsBackwards ) {
			// Fetch the file history
			$this->mHist = $this->mImg->getHistory( $queryLimit, null, $this->mOffset, false );
			// The current rev may not meet the offset/limit
			$numRows = count( $this->mHist );
			if ( $numRows <= $this->mLimit && $this->mImg->getTimestamp() > $this->mOffset ) {
				$this->mHist = array_merge( array( $this->mImg ), $this->mHist );
			}
		} else {
			// The current rev may not meet the offset
			if ( !$this->mOffset || $this->mImg->getTimestamp() < $this->mOffset ) {
				$this->mHist[] = $this->mImg;
			}
			// Old image versions (fetch extra row for nav links)
			$oiLimit = count( $this->mHist ) ? $this->mLimit : $this->mLimit + 1;
			// Fetch the file history
			$this->mHist = array_merge( $this->mHist,
				$this->mImg->getHistory( $oiLimit, $this->mOffset, null, false ) );
		}
		$numRows = count( $this->mHist ); // Total number of query results
		if ( $numRows ) {
			# Index value of top item in the list
			$firstIndex = $this->mIsBackwards ?
				$this->mHist[$numRows - 1]->getTimestamp() : $this->mHist[0]->getTimestamp();
			# Discard the extra result row if there is one
			if ( $numRows > $this->mLimit && $numRows > 1 ) {
				if ( $this->mIsBackwards ) {
					# Index value of item past the index
					$this->mPastTheEndIndex = $this->mHist[0]->getTimestamp();
					# Index value of bottom item in the list
					$lastIndex = $this->mHist[1]->getTimestamp();
					# Display range
					$this->mRange = array( 1, $numRows - 1 );
				} else {
					# Index value of item past the index
					$this->mPastTheEndIndex = $this->mHist[$numRows - 1]->getTimestamp();
					# Index value of bottom item in the list
					$lastIndex = $this->mHist[$numRows - 2]->getTimestamp();
					# Display range
					$this->mRange = array( 0, $numRows - 2 );
				}
			} else {
				# Setting indexes to an empty string means that they will be
				# omitted if they would otherwise appear in URLs. It just so
				# happens that this  is the right thing to do in the standard
				# UI, in all the relevant cases.
				$this->mPastTheEndIndex = '';
				# Index value of bottom item in the list
				$lastIndex = $this->mIsBackwards ?
					$this->mHist[0]->getTimestamp() : $this->mHist[$numRows - 1]->getTimestamp();
				# Display range
				$this->mRange = array( 0, $numRows - 1 );
			}
		} else {
			$firstIndex = '';
			$lastIndex = '';
			$this->mPastTheEndIndex = '';
		}
		if ( $this->mIsBackwards ) {
			$this->mIsFirst = ( $numRows < $queryLimit );
			$this->mIsLast = ( $this->mOffset == '' );
			$this->mLastShown = $firstIndex;
			$this->mFirstShown = $lastIndex;
		} else {
			$this->mIsFirst = ( $this->mOffset == '' );
			$this->mIsLast = ( $numRows < $queryLimit );
			$this->mLastShown = $lastIndex;
			$this->mFirstShown = $firstIndex;
		}
		$this->mQueryDone = true;
	}

	/**
	 * @param $enable bool
	 */
	protected function preventClickjacking( $enable = true ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

}
