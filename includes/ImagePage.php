<?php

if ( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Special handling for image description pages
 *
 * @ingroup Media
 */
class ImagePage extends Article {

	/* private */ var $img;  // Image object
	/* private */ var $displayImg;
	/* private */ var $repo;
	/* private */ var $fileLoaded;
	var $mExtraDescription = false;
	var $dupes;

	function __construct( $title ) {
		parent::__construct( $title );
		$this->dupes = null;
		$this->repo = null;
	}
	
	public function setFile( $file ) {
		$this->displayImg = $file;
		$this->img = $file;
		$this->fileLoaded = true;
	}

	protected function loadFile() {
		if ( $this->fileLoaded ) {
			return true;
		}
		$this->fileLoaded = true;

		$this->displayImg = $this->img = false;
		wfRunHooks( 'ImagePageFindFile', array( $this, &$this->img, &$this->displayImg ) );
		if ( !$this->img ) {
			$this->img = wfFindFile( $this->mTitle );
			if ( !$this->img ) {
				$this->img = wfLocalFile( $this->mTitle );
			}
		}
		if ( !$this->displayImg ) {
			$this->displayImg = $this->img;
		}
		$this->repo = $this->img->getRepo();
	}

	/**
	 * Handler for action=render
	 * Include body text only; none of the image extras
	 */
	public function render() {
		global $wgOut;
		$wgOut->setArticleBodyOnly( true );
		parent::view();
	}

	public function view() {
		global $wgOut, $wgShowEXIF, $wgRequest, $wgUser;

		$diff = $wgRequest->getVal( 'diff' );
		$diffOnly = $wgRequest->getBool( 'diffonly', $wgUser->getOption( 'diffonly' ) );

		if ( $this->mTitle->getNamespace() != NS_FILE || ( isset( $diff ) && $diffOnly ) ) {
			return Article::view();
		}
			
		$this->loadFile();

		if ( $this->mTitle->getNamespace() == NS_FILE && $this->img->getRedirected() ) {
			if ( $this->mTitle->getDBkey() == $this->img->getName() || isset( $diff ) ) {
				// mTitle is the same as the redirect target so ask Article
				// to perform the redirect for us.
				$wgRequest->setVal( 'diffonly', 'true' );
				return Article::view();
			} else {
				// mTitle is not the same as the redirect target so it is 
				// probably the redirect page itself. Fake the redirect symbol
				$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
				$wgOut->addHTML( $this->viewRedirect( Title::makeTitle( NS_FILE, $this->img->getName() ),
					/* $appendSubtitle */ true, /* $forceKnown */ true ) );
				$this->viewUpdates();
				return;
			}
		}

		$this->showRedirectedFromHeader();

		if ( $wgShowEXIF && $this->displayImg->exists() ) {
			// FIXME: bad interface, see note on MediaHandler::formatMetadata().
			$formattedMetadata = $this->displayImg->formatMetadata();
			$showmeta = $formattedMetadata !== false;
		} else {
			$showmeta = false;
		}

		if ( !$diff && $this->displayImg->exists() )
			$wgOut->addHTML( $this->showTOC( $showmeta ) );

		if ( !$diff )
			$this->openShowImage();

		# No need to display noarticletext, we use our own message, output in openShowImage()
		if ( $this->getID() ) {
			Article::view();
		} else {
			# Just need to set the right headers
			$wgOut->setArticleFlag( true );
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$this->viewUpdates();
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = wfMsgNoTrans( 'shareddescriptionfollows' );
			if ( $fol != '-' && !wfEmptyMsg( 'shareddescriptionfollows', $fol ) ) {
				$wgOut->addWikiText( $fol );
			}
			$wgOut->addHTML( '<div id="shared-image-desc">' . $this->mExtraDescription . "</div>\n" );
		}

		$this->closeShowImage();
		$this->imageHistory();
		// TODO: Cleanup the following

		$wgOut->addHTML( Xml::element( 'h2',
			array( 'id' => 'filelinks' ),
			wfMsg( 'imagelinks' ) ) . "\n" );
		$this->imageDupes();
		# TODO! FIXME! For some freaky reason, we can't redirect to foreign images.
		# Yet we return metadata about the target. Definitely an issue in the FileRepo
		$this->imageRedirects();
		$this->imageLinks();
		
		# Allow extensions to add something after the image links
		$html = '';
		wfRunHooks( 'ImagePageAfterImageLinks', array( $this, &$html ) );
		if ( $html )
			$wgOut->addHTML( $html );

		if ( $showmeta ) {
			global $wgStylePath, $wgStyleVersion;
			$expand = htmlspecialchars( Xml::escapeJsString( wfMsg( 'metadata-expand' ) ) );
			$collapse = htmlspecialchars( Xml::escapeJsString( wfMsg( 'metadata-collapse' ) ) );
			$wgOut->addHTML( Xml::element( 'h2', array( 'id' => 'metadata' ), wfMsg( 'metadata' ) ) . "\n" );
			$wgOut->addWikiText( $this->makeMetadataTable( $formattedMetadata ) );
			$wgOut->addScriptFile( 'metadata.js' );
			$wgOut->addHTML(
				"<script type=\"text/javascript\">attachMetadataToggle('mw_metadata', '$expand', '$collapse');</script>\n" );
		}
		
		$css = $this->repo->getDescriptionStylesheetUrl();
		if ( $css ) {
			$wgOut->addStyle( $css );
		}
	}
	
	public function getRedirectTarget() {
		$this->loadFile();
		if ( $this->img->isLocal() ) {
			return parent::getRedirectTarget();
		}
		// Foreign image page
		$from = $this->img->getRedirected();
		$to = $this->img->getName();
		if ( $from == $to ) {
			return null;
		}
		return $this->mRedirectTarget = Title::makeTitle( NS_FILE, $to );
	}
	public function followRedirect() {
		$this->loadFile();
		if ( $this->img->isLocal() ) {
			return parent::followRedirect();
		}
		$from = $this->img->getRedirected();
		$to = $this->img->getName();
		if ( $from == $to ) {
			return false;
		}
		return Title::makeTitle( NS_FILE, $to );
	}
	public function isRedirect( $text = false ) {
		$this->loadFile();
		if ( $this->img->isLocal() )
			return parent::isRedirect( $text );
			
		return (bool)$this->img->getRedirected();
	}
	
	public function isLocal() {
		$this->loadFile();
		return $this->img->isLocal();
	}
	
	public function getFile() {
		$this->loadFile();
		return $this->img;
	}
	
	public function getDisplayedFile() {
		$this->loadFile();
		return $this->displayImg;
	}
	
	public function getDuplicates() {
		$this->loadFile();
		if ( !is_null( $this->dupes ) ) {
			return $this->dupes;
		}
		if ( !( $hash = $this->img->getSha1() ) ) {
			return $this->dupes = array();
		}
		$dupes = RepoGroup::singleton()->findBySha1( $hash );
		// Remove duplicates with self and non matching file sizes
		$self = $this->img->getRepoName() . ':' . $this->img->getName();
		$size = $this->img->getSize();
		foreach ( $dupes as $index => $file ) {
			$key = $file->getRepoName() . ':' . $file->getName();
			if ( $key == $self )
				unset( $dupes[$index] );
			if ( $file->getSize() != $size )
				unset( $dupes[$index] );
		}
		return $this->dupes = $dupes;
		
	}
	

	/**
	 * Create the TOC
	 *
	 * @param $metadata Boolean: whether or not to show the metadata link
	 * @return String
	 */
	protected function showTOC( $metadata ) {
		$r = array(
				'<li><a href="#file">' . wfMsgHtml( 'file-anchor-link' ) . '</a></li>',
				'<li><a href="#filehistory">' . wfMsgHtml( 'filehist' ) . '</a></li>',
				'<li><a href="#filelinks">' . wfMsgHtml( 'imagelinks' ) . '</a></li>',
		);
		if ( $metadata ) {
			$r[] = '<li><a href="#metadata">' . wfMsgHtml( 'metadata' ) . '</a></li>';
		}
	
		wfRunHooks( 'ImagePageShowTOC', array( $this, &$r ) );
		
		return '<ul id="filetoc">' . implode( "\n", $r ) . '</ul>';
	}

	/**
	 * Make a table with metadata to be shown in the output page.
	 *
	 * FIXME: bad interface, see note on MediaHandler::formatMetadata().
	 *
	 * @param $metadata Array: the array containing the EXIF data
	 * @return String
	 */
	protected function makeMetadataTable( $metadata ) {
		$r = "<div class=\"mw-imagepage-section-metadata\">";
		$r .= wfMsgNoTrans( 'metadata-help' );
		$r .= "<table id=\"mw_metadata\" class=\"mw_metadata\">\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $v ) {
				# FIXME, why is this using escapeId for a class?!
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
	 * Overloading Article's getContent method.
	 *
	 * Omit noarticletext if sharedupload; text will be fetched from the
	 * shared upload server if possible.
	 */
	public function getContent() {
		$this->loadFile();
		if ( $this->img && !$this->img->isLocal() && 0 == $this->getID() ) {
			return '';
		}
		return Article::getContent();
	}

	protected function openShowImage() {
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest,
			$wgLang, $wgContLang, $wgEnableUploads;

		$this->loadFile();

		$full_url  = $this->displayImg->getURL();
		$linkAttribs = false;
		$sizeSel = intval( $wgUser->getOption( 'imagesize' ) );
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
		$sk = $wgUser->getSkin();
		$dirmark = $wgContLang->getDirMark();

		if ( $this->displayImg->exists() ) {
			# image
			$page = $wgRequest->getIntOrNull( 'page' );
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
			$mime = $this->displayImg->getMimeType();
			$showLink = false;
			$linkAttribs = array( 'href' => $full_url );
			$longDesc = $this->displayImg->getLongDesc();

			wfRunHooks( 'ImageOpenShowImageInlineBefore', array( &$this, &$wgOut ) );

			if ( $this->displayImg->allowInlineDisplay() ) {
				# image

				# "Download high res version" link below the image
				# $msgsize = wfMsgHtml('file-info-size', $width_orig, $height_orig, $sk->formatSize( $this->displayImg->getSize() ), $mime );
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
					$msgbig  = wfMsgHtml( 'show-big-image' );
					$msgsmall = wfMsgExt( 'show-big-image-thumb', 'parseinline',
						$wgLang->formatNum( $width ),
						$wgLang->formatNum( $height )
					);
				} else {
					# Image is small enough to show full size on image page
					$msgbig = htmlspecialchars( $this->displayImg->getName() );
					$msgsmall = wfMsgExt( 'file-nohires', array( 'parseinline' ) );
				}

				$params['width'] = $width;
				$thumbnail = $this->displayImg->transform( $params );

				$anchorclose = "<br />";
				if ( $this->displayImg->mustRender() ) {
					$showLink = true;
				} else {
					$anchorclose .=
						$msgsmall .
						'<br />' . Xml::tags( 'a', $linkAttribs,  $msgbig ) . "$dirmark " . $longDesc;
				}

				$isMulti = $this->displayImg->isMultipage() && $this->displayImg->pageCount() > 1;
				if ( $isMulti ) {
					$wgOut->addHTML( '<table class="multipageimage"><tr><td>' );
				}

				if ( $thumbnail ) {
					$options = array(
						'alt' => $this->displayImg->getTitle()->getPrefixedText(),
						'file-link' => true,
					);
					$wgOut->addHTML( '<div class="fullImageLink" id="file">' .
						$thumbnail->toHtml( $options ) .
						$anchorclose . "</div>\n" );
				}

				if ( $isMulti ) {
					$count = $this->displayImg->pageCount();

					if ( $page > 1 ) {
						$label = $wgOut->parse( wfMsg( 'imgmultipageprev' ), false );
						$link = $sk->link(
							$this->mTitle,
							$label,
							array(),
							array( 'page' => $page - 1 ),
							array( 'known', 'noclasses' )
						);
						$thumb1 = $sk->makeThumbLinkObj( $this->mTitle, $this->displayImg, $link, $label, 'none',
							array( 'page' => $page - 1 ) );
					} else {
						$thumb1 = '';
					}

					if ( $page < $count ) {
						$label = wfMsg( 'imgmultipagenext' );
						$link = $sk->link(
							$this->mTitle,
							$label,
							array(),
							array( 'page' => $page + 1 ),
							array( 'known', 'noclasses' )
						);
						$thumb2 = $sk->makeThumbLinkObj( $this->mTitle, $this->displayImg, $link, $label, 'none',
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

					$option = array();
					for ( $i = 1; $i <= $count; $i++ ) {
						$options[] = Xml::option( $wgLang->formatNum( $i ), $i, $i == $page );
					}
					$select = Xml::tags( 'select',
						array( 'id' => 'pageselector', 'name' => 'page' ),
						implode( "\n", $options ) );

					$wgOut->addHTML(
						'</td><td><div class="multipageimagenavbox">' .
						Xml::openElement( 'form', $formParams ) .
						Xml::hidden( 'title', $this->getTitle()->getPrefixedDbKey() ) .
						wfMsgExt( 'imgmultigoto', array( 'parseinline', 'replaceafter' ), $select ) .
						Xml::submitButton( wfMsg( 'imgmultigo' ) ) .
						Xml::closeElement( 'form' ) .
						"<hr />$thumb1\n$thumb2<br clear=\"all\" /></div></td></tr></table>"
					);
				}
			} else {
				# if direct link is allowed but it's not a renderable image, show an icon.
				if ( $this->displayImg->isSafeFile() ) {
					$icon = $this->displayImg->iconThumb();

					$wgOut->addHTML( '<div class="fullImageLink" id="file">' .
					$icon->toHtml( array( 'file-link' => true ) ) .
					"</div>\n" );
				}

				$showLink = true;
			}


			if ( $showLink ) {
				$filename = wfEscapeWikiText( $this->displayImg->getName() );
				$medialink = "[[Media:$filename|$filename]]";

				if ( !$this->displayImg->isSafeFile() ) {
					$warning = wfMsgNoTrans( 'mediawarning' );
					$wgOut->addWikiText( <<<EOT
<div class="fullMedia">
<span class="dangerousLink">{$medialink}</span>$dirmark
<span class="fileInfo">$longDesc</span>
</div>
<div class="mediaWarning">$warning</div>
EOT
						);
				} else {
					$wgOut->addWikiText( <<<EOT
<div class="fullMedia">
{$medialink}{$dirmark}
<span class="fileInfo">$longDesc</span>
</div>
EOT
					);
				}
			}

			if ( !$this->displayImg->isLocal() ) {
				$this->printSharedImageText();
			}
		} else {
			# Image does not exist
			if ( $wgEnableUploads && $wgUser->isAllowed( 'upload' ) ) {
				// Only show an upload link if the user can upload
				$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
				$nofile = array(
					'filepage-nofile-link',
					$uploadTitle->getFullUrl( array( 'wpDestFile' => $this->img->getName() ) )
				);
			}
			else
			{
				$nofile = 'filepage-nofile';
			}
			$wgOut->setRobotPolicy( 'noindex,nofollow' );
			$wgOut->wrapWikiMsg( "<div id='mw-imagepage-nofile' class='plainlinks'>\n$1\n</div>", $nofile );
		}
	}

	/**
	 * Show a notice that the file is from a shared repository
	 */
	protected function printSharedImageText() {
		global $wgOut;

		$this->loadFile();

		$descUrl = $this->img->getDescriptionUrl();
		$descText = $this->img->getDescriptionText();

		$wrap = "<div class=\"sharedUploadNotice\">\n$1\n</div>\n";
		$repo = $this->img->getRepo()->getDisplayName();

		$msg = '';
		if ( $descUrl && $descText && wfMsgNoTrans( 'sharedupload-desc-here' ) !== '-'  ) {
			$wgOut->wrapWikiMsg( $wrap, array( 'sharedupload-desc-here', $repo, $descUrl ) );
		} elseif ( $descUrl && wfMsgNoTrans( 'sharedupload-desc-there' ) !== '-' ) {
			$wgOut->wrapWikiMsg( $wrap, array( 'sharedupload-desc-there', $repo, $descUrl ) );
		} else {
			$wgOut->wrapWikiMsg( $wrap, array( 'sharedupload', $repo ), ''/*BACKCOMPAT*/ );
		}

		if ( $descText ) {
			$this->mExtraDescription = $descText;
		}
	}

	public function getUploadUrl() {
		$this->loadFile();
		$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
		return $uploadTitle->getFullUrl( array(
			'wpDestFile' => $this->img->getName(),
			'wpForReUpload' => 1
		 ) );
	}

	/**
	 * Print out the various links at the bottom of the image page, e.g. reupload,
	 * external editing (and instructions link) etc.
	 */
	protected function uploadLinksBox() {
		global $wgUser, $wgOut, $wgEnableUploads, $wgUseExternalEditor;

		if ( !$wgEnableUploads ) { return; }

		$this->loadFile();
		if ( !$this->img->isLocal() )
			return;

		$sk = $wgUser->getSkin();

		$wgOut->addHTML( "<br /><ul>\n" );

		# "Upload a new version of this file" link
		if ( UploadBase::userCanReUpload( $wgUser, $this->img->name ) ) {
			$ulink = $sk->makeExternalLink( $this->getUploadUrl(), wfMsg( 'uploadnewversion-linktext' ) );
			$wgOut->addHTML( "<li id=\"mw-imagepage-reupload-link\"><div class=\"plainlinks\">{$ulink}</div></li>\n" );
		}

		# External editing link
		if ( $wgUseExternalEditor ) {
			$elink = $sk->link(
				$this->mTitle,
				wfMsgHtml( 'edit-externally' ),
				array(),
				array(
					'action' => 'edit',
					'externaledit' => 'true',
					'mode' => 'file'
				),
				array( 'known', 'noclasses' )
			);
			$wgOut->addHTML( '<li id="mw-imagepage-edit-external">' . $elink . ' <small>' . wfMsgExt( 'edit-externally-help', array( 'parseinline' ) ) . "</small></li>\n" );
		}

		$wgOut->addHTML( "</ul>\n" );
	}

	protected function closeShowImage() { } # For overloading

	/**
	 * If the page we've just displayed is in the "Image" namespace,
	 * we follow it with an upload history of the image and its usage.
	 */
	protected function imageHistory() {
		global $wgOut;

		$this->loadFile();
		$pager = new ImageHistoryPseudoPager( $this );
		$wgOut->addHTML( $pager->getBody() );

		$this->img->resetHistory(); // free db resources

		# Exist check because we don't want to show this on pages where an image
		# doesn't exist along with the noimage message, that would suck. -ævar
		if ( $this->img->exists() ) {
			$this->uploadLinksBox();
		}
	}

	protected function imageLinks() {
		global $wgUser, $wgOut, $wgLang;

		$limit = 100;

		$dbr = wfGetDB( DB_SLAVE );

		$res = $dbr->select(
			array( 'imagelinks', 'page' ),
			array( 'page_namespace', 'page_title' ),
			array( 'il_to' => $this->mTitle->getDBkey(), 'il_from = page_id' ),
			__METHOD__,
			array( 'LIMIT' => $limit + 1 )
		);
		$count = $dbr->numRows( $res );
		if ( $count == 0 ) {
			$wgOut->wrapWikiMsg( Html::rawElement( 'div', array ( 'id' => 'mw-imagepage-nolinkstoimage' ), "\n$1\n" ), 'nolinkstoimage' );
			return;
		}
		
		$wgOut->addHTML( "<div id='mw-imagepage-section-linkstoimage'>\n" );
		if ( $count <= $limit - 1 ) {
			$wgOut->addWikiMsg( 'linkstoimage', $count );
		} else {
			// More links than the limit. Add a link to [[Special:Whatlinkshere]]
			$wgOut->addWikiMsg( 'linkstoimage-more',
				$wgLang->formatNum( $limit ),
				$this->mTitle->getPrefixedDBkey()
			);
		}

		$wgOut->addHTML( Html::openElement( 'ul', array( 'class' => 'mw-imagepage-linkstoimage' ) ) . "\n" );
		$sk = $wgUser->getSkin();
		$count = 0;
		$elements = array();
		while ( $s = $res->fetchObject() ) {
			$count++;
			if ( $count <= $limit ) {
				// We have not yet reached the extra one that tells us there is more to fetch
				$elements[] =  $s;
			}
		}

		// Sort the list by namespace:title
		usort ( $elements, array( $this, 'compare' ) );

		// Create links for every element
		foreach( $elements as $element ) {    
			$link = $sk->linkKnown( Title::makeTitle( $element->page_namespace, $element->page_title ) );
			$wgOut->addHTML( Html::rawElement(
						'li',
						array( 'id' => 'mw-imagepage-linkstoimage-ns' . $element->page_namespace ),
						$link
					) . "\n"
			);

		};
		$wgOut->addHTML( Html::closeElement( 'ul' ) . "\n" );
		$res->free();

		// Add a links to [[Special:Whatlinkshere]]
		if ( $count > $limit ) {
			$wgOut->addWikiMsg( 'morelinkstoimage', $this->mTitle->getPrefixedDBkey() );
		}
		$wgOut->addHTML( Html::closeElement( 'div' ) . "\n" );
	}
	
	protected function imageRedirects() {
		global $wgUser, $wgOut, $wgLang;

		$redirects = $this->getTitle()->getRedirectsHere( NS_FILE );
		if ( count( $redirects ) == 0 ) return;

		$wgOut->addHTML( "<div id='mw-imagepage-section-redirectstofile'>\n" );
		$wgOut->addWikiMsg( 'redirectstofile',
			$wgLang->formatNum( count( $redirects ) )
		);
		$wgOut->addHTML( "<ul class='mw-imagepage-redirectstofile'>\n" );

		$sk = $wgUser->getSkin();
		foreach ( $redirects as $title ) {
			$link = $sk->link(
				$title,
				null,
				array(),
				array( 'redirect' => 'no' ),
				array( 'known', 'noclasses' )
			);
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul></div>\n" );

	}

	protected function imageDupes() {
		global $wgOut, $wgUser, $wgLang;

		$this->loadFile();

		$dupes = $this->getDuplicates();
		if ( count( $dupes ) == 0 ) return;

		$wgOut->addHTML( "<div id='mw-imagepage-section-duplicates'>\n" );
		$wgOut->addWikiMsg( 'duplicatesoffile',
			$wgLang->formatNum( count( $dupes ) ), $this->mTitle->getDBkey()
		);
		$wgOut->addHTML( "<ul class='mw-imagepage-duplicates'>\n" );

		$sk = $wgUser->getSkin();
		foreach ( $dupes as $file ) {
			$fromSrc = '';
			if ( $file->isLocal() ) {
				$link = $sk->link(
					$file->getTitle(),
					null,
					array(),
					array(),
					array( 'known', 'noclasses' )
				);
			} else {
				$link = $sk->makeExternalLink( $file->getDescriptionUrl(),
					$file->getTitle()->getPrefixedText() );
				$fromSrc = wfMsg( 'shared-repo-from', $file->getRepo()->getDisplayName() );
			}
			$wgOut->addHTML( "<li>{$link} {$fromSrc}</li>\n" );
		}
		$wgOut->addHTML( "</ul></div>\n" );
	}

	/**
	 * Delete the file, or an earlier version of it
	 */
	public function delete() {
		global $wgUploadMaintenance;
		if ( $wgUploadMaintenance && $this->mTitle && $this->mTitle->getNamespace() == NS_FILE ) {
			global $wgOut;
			$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n", array( 'filedelete-maintenance' ) );
			return;
		}

		$this->loadFile();
		if ( !$this->img->exists() || !$this->img->isLocal() || $this->img->getRedirected() ) {
			// Standard article deletion
			Article::delete();
			return;
		}
		$deleter = new FileDeleteForm( $this->img );
		$deleter->execute();
	}

	/**
	 * Revert the file to an earlier version
	 */
	public function revert() {
		$this->loadFile();
		$reverter = new FileRevertForm( $this->img );
		$reverter->execute();
	}

	/**
	 * Override handling of action=purge
	 */
	public function doPurge() {
		$this->loadFile();
		if ( $this->img->exists() ) {
			wfDebug( "ImagePage::doPurge purging " . $this->img->getName() . "\n" );
			$update = new HTMLCacheUpdate( $this->mTitle, 'imagelinks' );
			$update->doUpdate();
			$this->img->upgradeRow();
			$this->img->purgeCache();
		} else {
			wfDebug( "ImagePage::doPurge no image for " . $this->img->getName() . "; limiting purge to cache only\n" );
			// even if the file supposedly doesn't exist, force any cached information
			// to be updated (in case the cached information is wrong)
			$this->img->purgeCache();
		}
		parent::doPurge();
	}

	/**
	 * Display an error with a wikitext description
	 */
	function showError( $description ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "internalerror" ) );
		$wgOut->setRobotPolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );
		$wgOut->enableClientCache( false );
		$wgOut->addWikiText( $description );
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
class ImageHistoryList {

	protected $imagePage, $img, $skin, $title, $repo, $showThumb;

	public function __construct( $imagePage ) {
		global $wgUser, $wgShowArchiveThumbnails;
		$this->skin = $wgUser->getSkin();
		$this->current = $imagePage->getFile();
		$this->img = $imagePage->getDisplayedFile();
		$this->title = $imagePage->getTitle();
		$this->imagePage = $imagePage;
		$this->showThumb = $wgShowArchiveThumbnails && $this->img->canRender();
	}

	public function getImagePage() {
		return $this->imagePage;
	}

	public function getSkin() {
		return $this->skin;
	}

	public function getFile() {
		return $this->img;
	}

	public function beginImageHistoryList( $navLinks = '' ) {
		global $wgOut, $wgUser;
		return Xml::element( 'h2', array( 'id' => 'filehistory' ), wfMsg( 'filehist' ) ) . "\n"
			. "<div id=\"mw-imagepage-section-filehistory\">\n"
			. $wgOut->parse( wfMsgNoTrans( 'filehist-help' ) )
			. $navLinks . "\n"
			. Xml::openElement( 'table', array( 'class' => 'wikitable filehistory' ) ) . "\n"
			. '<tr><td></td>'
			. ( $this->current->isLocal() && ( $wgUser->isAllowed( 'delete' ) || $wgUser->isAllowed( 'deletedhistory' ) ) ? '<td></td>' : '' )
			. '<th>' . wfMsgHtml( 'filehist-datetime' ) . '</th>'
			. ( $this->showThumb ? '<th>' . wfMsgHtml( 'filehist-thumb' ) . '</th>' : '' )
			. '<th>' . wfMsgHtml( 'filehist-dimensions' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-user' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-comment' ) . '</th>'
			. "</tr>\n";
	}

	public function endImageHistoryList( $navLinks = '' ) {
		return "</table>\n$navLinks\n</div>\n";
	}

	public function imageHistoryLine( $iscur, $file ) {
		global $wgUser, $wgLang, $wgContLang;

		$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );
		$img = $iscur ? $file->getName() : $file->getArchiveName();
		$user = $file->getUser( 'id' );
		$usertext = $file->getUser( 'text' );
		$description = $file->getDescription();

		$local = $this->current->isLocal();
		$row = $css = $selected = '';

		// Deletion link
		if ( $local && ( $wgUser->isAllowed( 'delete' ) || $wgUser->isAllowed( 'deletedhistory' ) ) ) {
			$row .= '<td>';
			# Link to remove from history
			if ( $wgUser->isAllowed( 'delete' ) ) {
				$q = array( 'action' => 'delete' );
				if ( !$iscur )
					$q['oldimage'] = $img;
				$row .= $this->skin->link(
					$this->title,
					wfMsgHtml( $iscur ? 'filehist-deleteall' : 'filehist-deleteone' ),
					array(), $q, array( 'known' )
				);
			}
			# Link to hide content. Don't show useless link to people who cannot hide revisions.
			$canHide = $wgUser->isAllowed( 'deleterevision' );
			if ( $canHide || ( $wgUser->isAllowed( 'deletedhistory' ) && $file->getVisibility() ) ) {
				if ( $wgUser->isAllowed( 'delete' ) ) {
					$row .= '<br />';
				}
				// If file is top revision or locked from this user, don't link
				if ( $iscur || !$file->userCan( File::DELETED_RESTRICTED ) ) {
					$del = $this->skin->revDeleteLinkDisabled( $canHide );
				} else {
					list( $ts, $name ) = explode( '!', $img, 2 );
					$query = array(
						'type'   => 'oldimage',
						'target' => $this->title->getPrefixedText(),
						'ids'    => $ts,
					);
					$del = $this->skin->revDeleteLink( $query,
						$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
				}
				$row .= $del;
			}
			$row .= '</td>';
		}

		// Reversion link/current indicator
		$row .= '<td>';
		if ( $iscur ) {
			$row .= wfMsgHtml( 'filehist-current' );
		} elseif ( $local && $wgUser->isLoggedIn() && $this->title->userCan( 'edit' ) ) {
			if ( $file->isDeleted( File::DELETED_FILE ) ) {
				$row .= wfMsgHtml( 'filehist-revert' );
			} else {
				$row .= $this->skin->link(
					$this->title,
					wfMsgHtml( 'filehist-revert' ),
					array(),
					array(
						'action' => 'revert',
						'oldimage' => $img,
						'wpEditToken' => $wgUser->editToken( $img )
					),
					array( 'known', 'noclasses' )
				);
			}
		}
		$row .= '</td>';

		// Date/time and image link
		if ( $file->getTimestamp() === $this->img->getTimestamp() ) {
			$selected = "class='filehistory-selected'";
		}
		$row .= "<td $selected style='white-space: nowrap;'>";
		if ( !$file->userCan( File::DELETED_FILE ) ) {
			# Don't link to unviewable files
			$row .= '<span class="history-deleted">' . $wgLang->timeAndDate( $timestamp, true ) . '</span>';
		} elseif ( $file->isDeleted( File::DELETED_FILE ) ) {
			$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
			# Make a link to review the image
			$url = $this->skin->link(
				$revdel,
				$wgLang->timeAndDate( $timestamp, true ),
				array(),
				array(
					'target' => $this->title->getPrefixedText(),
					'file' => $img,
					'token' => $wgUser->editToken( $img )
				),
				array( 'known', 'noclasses' )
			);
			$row .= '<span class="history-deleted">' . $url . '</span>';
		} else {
			$url = $iscur ? $this->current->getUrl() : $this->current->getArchiveUrl( $img );
			$row .= Xml::element( 'a', array( 'href' => $url ), $wgLang->timeAndDate( $timestamp, true ) );
		}
		$row .= "</td>";

		// Thumbnail
		if ( $this->showThumb ) {
			$row .= '<td>' . $this->getThumbForLine( $file ) . '</td>';
		}

		// Image dimensions + size
		$row .= '<td>';
		$row .= htmlspecialchars( $file->getDimensionsString() );
		$row .= " <span style='white-space: nowrap;'>(" . $this->skin->formatSize( $file->getSize() ) . ')</span>';
		$row .= '</td>';

		// Uploading user
		$row .= '<td>';
		if ( $local ) {
			// Hide deleted usernames
			if ( $file->isDeleted( File::DELETED_USER ) ) {
				$row .= '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-user' ) . '</span>';
			} else {
				$row .= $this->skin->userLink( $user, $usertext ) . " <span style='white-space: nowrap;'>" .
					$this->skin->userToolLinks( $user, $usertext ) . "</span>";
			}
		} else {
			$row .= htmlspecialchars( $usertext );
		}
		$row .= '</td><td>';

		// Don't show deleted descriptions
		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$row .= '<span class="history-deleted">' . wfMsgHtml( 'rev-deleted-comment' ) . '</span>';
		} else {
			$row .= $this->skin->commentBlock( $description, $this->title );
		}
		$row .= '</td>';

		wfRunHooks( 'ImagePageFileHistoryLine', array( $this, $file, &$row, &$rowClass ) );
		$classAttr = $rowClass ? " class='$rowClass'" : "";

		return "<tr{$classAttr}>{$row}</tr>\n";
	}

	protected function getThumbForLine( $file ) {
		global $wgLang;

		if ( $file->allowInlineDisplay() && $file->userCan( File::DELETED_FILE ) && !$file->isDeleted( File::DELETED_FILE ) ) {
			$params = array(
				'width' => '120',
				'height' => '120',
			);
			$timestamp = wfTimestamp( TS_MW, $file->getTimestamp() );

			$thumbnail = $file->transform( $params );
			$options = array(
				'alt' => wfMsg( 'filehist-thumbtext',
					$wgLang->timeAndDate( $timestamp, true ),
					$wgLang->date( $timestamp, true ),
					$wgLang->time( $timestamp, true ) ),
				'file-link' => true,
			);
			
			if ( !$thumbnail ) return wfMsgHtml( 'filehist-nothumb' );

			return $thumbnail->toHtml( $options );
		} else {
			return wfMsgHtml( 'filehist-nothumb' );
		}
	}
}

class ImageHistoryPseudoPager extends ReverseChronologicalPager {
	function __construct( $imagePage ) {
		parent::__construct();
		$this->mImagePage = $imagePage;
		$this->mTitle = clone ( $imagePage->getTitle() );
		$this->mTitle->setFragment( '#filehistory' );
		$this->mImg = null;
		$this->mHist = array();
		$this->mRange = array( 0, 0 ); // display range
	}
	
	function getTitle() {
		return $this->mTitle;
	}

	function getQueryInfo() {
		return false;
	}

	function getIndexField() {
		return '';
	}

	function formatRow( $row ) {
		return '';
	}
	
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
		}
		return $s;
	}

	function doQuery() {
		if ( $this->mQueryDone ) return;
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
}
