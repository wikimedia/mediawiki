<?php
/**
 */

/**
 *
 */
if( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Special handling for image description pages
 *
 * @addtogroup Media
 */
class ImagePage extends Article {

	/* private */ var $img;  // Image object this page is shown for
	var $mExtraDescription = false;

	function __construct( $title ) {
		parent::__construct( $title );
		$this->img = wfFindFile( $this->mTitle );
		if ( !$this->img ) {
			$this->img = wfLocalFile( $this->mTitle );
		}
	}

	/**
	 * Handler for action=render
	 * Include body text only; none of the image extras
	 */
	function render() {
		global $wgOut;
		$wgOut->setArticleBodyOnly( true );
		$wgOut->addSecondaryWikitext( $this->getContent() );
	}

	function view() {
		global $wgOut, $wgShowEXIF, $wgRequest, $wgUser;

		$diff = $wgRequest->getVal( 'diff' );
		$diffOnly = $wgRequest->getBool( 'diffonly', $wgUser->getOption( 'diffonly' ) );

		if ( $this->mTitle->getNamespace() != NS_IMAGE || ( isset( $diff ) && $diffOnly ) )
			return Article::view();

		if ($wgShowEXIF && $this->img->exists()) {
			$formattedMetadata = $this->img->formatMetadata();
			$showmeta = $formattedMetadata !== false;
		} else {
			$showmeta = false;
		}

		if ($this->img->exists())
			$wgOut->addHTML($this->showTOC($showmeta));

		$this->openShowImage();

		# No need to display noarticletext, we use our own message, output in openShowImage()
		if ( $this->getID() ) {
			Article::view();
		} else {
			# Just need to set the right headers
			$wgOut->setArticleFlag( true );
			$wgOut->setRobotpolicy( 'index,follow' );
			$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
			$this->viewUpdates();
		}

		# Show shared description, if needed
		if ( $this->mExtraDescription ) {
			$fol = wfMsg( 'shareddescriptionfollows' );
			if( $fol != '-' && !wfEmptyMsg( 'shareddescriptionfollows', $fol ) ) {
				$wgOut->addWikiText( $fol );
			}
			$wgOut->addHTML( '<div id="shared-image-desc">' . $this->mExtraDescription . '</div>' );
		}

		$this->closeShowImage();
		$this->imageHistory();
		$this->imageLinks();

		if ( $showmeta ) {
			global $wgStylePath, $wgStyleVersion;
			$expand = htmlspecialchars( wfEscapeJsString( wfMsg( 'metadata-expand' ) ) );
			$collapse = htmlspecialchars( wfEscapeJsString( wfMsg( 'metadata-collapse' ) ) );
			$wgOut->addHTML( Xml::element( 'h2', array( 'id' => 'metadata' ), wfMsg( 'metadata' ) ). "\n" );
			$wgOut->addWikiText( $this->makeMetadataTable( $formattedMetadata ) );
			$wgOut->addHTML(
				"<script type=\"text/javascript\" src=\"$wgStylePath/common/metadata.js?$wgStyleVersion\"></script>\n" .
				"<script type=\"text/javascript\">attachMetadataToggle('mw_metadata', '$expand', '$collapse');</script>\n" );
		}
	}

	/**
	 * Create the TOC
	 *
	 * @access private
	 *
	 * @param bool $metadata Whether or not to show the metadata link
	 * @return string
	 */
	function showTOC( $metadata ) {
		global $wgLang;
		$r = '<ul id="filetoc">
			<li><a href="#file">' . $wgLang->getNsText( NS_IMAGE ) . '</a></li>
			<li><a href="#filehistory">' . wfMsgHtml( 'filehist' ) . '</a></li>
			<li><a href="#filelinks">' . wfMsgHtml( 'imagelinks' ) . '</a></li>' .
			($metadata ? ' <li><a href="#metadata">' . wfMsgHtml( 'metadata' ) . '</a></li>' : '') . '
		</ul>';
		return $r;
	}

	/**
	 * Make a table with metadata to be shown in the output page.
	 *
	 * @access private
	 *
	 * @param array $exif The array containing the EXIF data
	 * @return string
	 */
	function makeMetadataTable( $metadata ) {
		$r = wfMsg( 'metadata-help' ) . "\n\n";
		$r .= "{| id=mw_metadata class=mw_metadata\n";
		foreach ( $metadata as $type => $stuff ) {
			foreach ( $stuff as $k => $v ) {
				$class = Sanitizer::escapeId( $v['id'] );
				if( $type == 'collapsed' ) {
					$class .= ' collapsable';
				}
				$r .= "|- class=\"$class\"\n";
				$r .= "!| {$v['name']}\n";
				$r .= "|| {$v['value']}\n";
			}
		}
		$r .= '|}';
		return $r;
	}

	/**
	 * Overloading Article's getContent method.
	 * 
	 * Omit noarticletext if sharedupload; text will be fetched from the
	 * shared upload server if possible.
	 */
	function getContent() {
		if( $this->img && !$this->img->isLocal() && 0 == $this->getID() ) {
			return '';
		}
		return Article::getContent();
	}

	function openShowImage() {
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest, $wgLang;

		$full_url  = $this->img->getURL();
		$linkAttribs = false;
		$sizeSel = intval( $wgUser->getOption( 'imagesize') );
		if( !isset( $wgImageLimits[$sizeSel] ) ) {
			$sizeSel = User::getDefaultOption( 'imagesize' );

			// The user offset might still be incorrect, specially if
			// $wgImageLimits got changed (see bug #8858).
			if( !isset( $wgImageLimits[$sizeSel] ) ) {
				// Default to the first offset in $wgImageLimits
				$sizeSel = 0;
			}
		}
		$max = $wgImageLimits[$sizeSel];
		$maxWidth = $max[0];
		$maxHeight = $max[1];
		$sk = $wgUser->getSkin();

		if ( $this->img->exists() ) {
			# image
			$page = $wgRequest->getIntOrNull( 'page' );
			if ( is_null( $page ) ) {
				$params = array();
				$page = 1;
			} else {
				$params = array( 'page' => $page );
			}
			$width_orig = $this->img->getWidth();
			$width = $width_orig;
			$height_orig = $this->img->getHeight();
			$height = $height_orig;
			$mime = $this->img->getMimeType();
			$showLink = false;
			$linkAttribs = array( 'href' => $full_url );

      wfRunHooks( 'ImageOpenShowImageInlineBefore', array( &$this , &$wgOut ) )	;

			if ( $this->img->allowInlineDisplay() and $width and $height) {
				# image

				# "Download high res version" link below the image
				$msgsize = wfMsgHtml('file-info-size', $width_orig, $height_orig, $sk->formatSize( $this->img->getSize() ), $mime );
				# We'll show a thumbnail of this image
				if ( $width > $maxWidth || $height > $maxHeight ) {
					# Calculate the thumbnail size.
					# First case, the limiting factor is the width, not the height.
					if ( $width / $height >= $maxWidth / $maxHeight ) {
						$height = round( $height * $maxWidth / $width);
						$width = $maxWidth;
						# Note that $height <= $maxHeight now.
					} else {
						$newwidth = floor( $width * $maxHeight / $height);
						$height = round( $height * $newwidth / $width );
						$width = $newwidth;
						# Note that $height <= $maxHeight now, but might not be identical
						# because of rounding.
					}
					$msgbig  = wfMsgHtml( 'show-big-image' );
					$msgsmall = wfMsgExt( 'show-big-image-thumb',
						array( 'parseinline' ), $width, $height );
				} else {
					# Image is small enough to show full size on image page
					$msgbig = htmlspecialchars( $this->img->getName() );
					$msgsmall = wfMsgExt( 'file-nohires', array( 'parseinline' ) );
				}

				$params['width'] = $width;
				$thumbnail = $this->img->transform( $params );

				$anchorclose = "<br />";
				if( $this->img->mustRender() ) {
					$showLink = true;
				} else {
					$anchorclose .= 
						$msgsmall .
						'<br />' . Xml::tags( 'a', $linkAttribs,  $msgbig ) . ' ' . $msgsize;
				}

				if ( $this->img->isMultipage() ) {
					$wgOut->addHTML( '<table class="multipageimage"><tr><td>' );
				}

				$imgAttribs = array(
					'border' => 0,
					'alt' => $this->img->getTitle()->getPrefixedText()
				);

				if ( $thumbnail ) {
					$wgOut->addHTML( '<div class="fullImageLink" id="file">' . 
						$thumbnail->toHtml( $imgAttribs, $linkAttribs ) .
						$anchorclose . '</div>' );
				}

				if ( $this->img->isMultipage() ) {
					$count = $this->img->pageCount();

					if ( $page > 1 ) {
						$label = $wgOut->parse( wfMsg( 'imgmultipageprev' ), false );
						$link = $sk->makeKnownLinkObj( $this->mTitle, $label, 'page='. ($page-1) );
						$thumb1 = $sk->makeThumbLinkObj( $this->mTitle, $this->img, $link, $label, 'none', 
							array( 'page' => $page - 1 ) );
					} else {
						$thumb1 = '';
					}

					if ( $page < $count ) {
						$label = wfMsg( 'imgmultipagenext' );
						$link = $sk->makeKnownLinkObj( $this->mTitle, $label, 'page='. ($page+1) );
						$thumb2 = $sk->makeThumbLinkObj( $this->mTitle, $this->img, $link, $label, 'none', 
							array( 'page' => $page + 1 ) );
					} else {
						$thumb2 = '';
					}

					global $wgScript;
					$select = '<form name="pageselector" action="' . 
						htmlspecialchars( $wgScript ) .
						'" method="get" onchange="document.pageselector.submit();">' .
						Xml::hidden( 'title', $this->getTitle()->getPrefixedDbKey() );
					$select .= $wgOut->parse( wfMsg( 'imgmultigotopre' ), false ) .
						' <select id="pageselector" name="page">';
					for ( $i=1; $i <= $count; $i++ ) {
						$select .= Xml::option( $wgLang->formatNum( $i ), $i,
							$i == $page );
					}
					$select .= '</select>' . $wgOut->parse( wfMsg( 'imgmultigotopost' ), false ) .
						'<input type="submit" value="' .
						htmlspecialchars( wfMsg( 'imgmultigo' ) ) . '"></form>';

					$wgOut->addHTML( '</td><td><div class="multipageimagenavbox">' .
						"$select<hr />$thumb1\n$thumb2<br clear=\"all\" /></div></td></tr></table>" );
				}
			} else {
				#if direct link is allowed but it's not a renderable image, show an icon.
				if ($this->img->isSafeFile()) {
					$icon= $this->img->iconThumb();

					$wgOut->addHTML( '<div class="fullImageLink" id="file"><a href="' . $full_url . '">' .
					$icon->toHtml() .
					'</a></div>' );
				}

				$showLink = true;
			}


			if ($showLink) {
				// Workaround for incorrect MIME type on SVGs uploaded in previous versions
				if ($mime == 'image/svg') $mime = 'image/svg+xml';

				$filename = wfEscapeWikiText( $this->img->getName() );
				$info = wfMsg( 'file-info', $sk->formatSize( $this->img->getSize() ), $mime );
				$infores = '';

				// Check for MIME type. Other types may have more information in the future.
				if (substr($mime,0,9) == 'image/svg' ) {
					$infores = wfMsg('file-svg', $width_orig, $height_orig ) . '<br />';
				}

				global $wgContLang;
				$dirmark = $wgContLang->getDirMark();
				if (!$this->img->isSafeFile()) {
					$warning = wfMsg( 'mediawarning' );
					$wgOut->addWikiText( <<<EOT
<div class="fullMedia">$infores
<span class="dangerousLink">[[Media:$filename|$filename]]</span>$dirmark
<span class="fileInfo"> $info</span>
</div>

<div class="mediaWarning">$warning</div>
EOT
						);
				} else {
					$wgOut->addWikiText( <<<EOT
<div class="fullMedia">$infores
[[Media:$filename|$filename]]$dirmark <span class="fileInfo"> $info</span>
</div>
EOT
						);
				}
			}

			if(!$this->img->isLocal()) {
				$this->printSharedImageText();
			}
		} else {
			# Image does not exist

			$title = SpecialPage::getTitleFor( 'Upload' );
			$link = $sk->makeKnownLinkObj($title, wfMsgHtml('noimage-linktext'),
				'wpDestFile=' . urlencode( $this->img->getName() ) );
			$wgOut->addHTML( wfMsgWikiHtml( 'noimage', $link ) );
		}
	}

	function printSharedImageText() {
		global $wgOut, $wgUser;

		$descUrl = $this->img->getDescriptionUrl();
		$descText = $this->img->getDescriptionText();
		$s = "<div class='sharedUploadNotice'>" . wfMsgWikiHtml("sharedupload");
		if ( $descUrl && !$descText) {
			$sk = $wgUser->getSkin();
			$link = $sk->makeExternalLink( $descUrl, wfMsg('shareduploadwiki-linktext') );
			$s .= " " . wfMsgWikiHtml('shareduploadwiki', $link);
		}
		$s .= "</div>";
		$wgOut->addHTML($s);

		if ( $descText ) {
			$this->mExtraDescription = $descText;
		}
	}

	function getUploadUrl() {
		global $wgServer;
		$uploadTitle = SpecialPage::getTitleFor( 'Upload' );
		return $wgServer . $uploadTitle->getLocalUrl( 'wpDestFile=' . urlencode( $this->img->getName() ) );
	}

	/**
	 * Print out the various links at the bottom of the image page, e.g. reupload,
	 * external editing (and instructions link) etc.
	 */
	function uploadLinksBox() {
		global $wgUser, $wgOut;

		if( !$this->img->isLocal() )
			return;

		$sk = $wgUser->getSkin();
		
		$wgOut->addHtml( '<br /><ul>' );
		
		# "Upload a new version of this file" link
		if( UploadForm::userCanReUpload($wgUser,$this->img->name) ) {
			$ulink = $sk->makeExternalLink( $this->getUploadUrl(), wfMsg( 'uploadnewversion-linktext' ) );
			$wgOut->addHtml( "<li><div class='plainlinks'>{$ulink}</div></li>" );
		}
		
		# External editing link
		$elink = $sk->makeKnownLinkObj( $this->mTitle, wfMsgHtml( 'edit-externally' ), 'action=edit&externaledit=true&mode=file' );
		$wgOut->addHtml( '<li>' . $elink . '<div>' . wfMsgWikiHtml( 'edit-externally-help' ) . '</div></li>' );
		
		$wgOut->addHtml( '</ul>' );
	}

	function closeShowImage()
	{
		# For overloading

	}

	/**
	 * If the page we've just displayed is in the "Image" namespace,
	 * we follow it with an upload history of the image and its usage.
	 */
	function imageHistory()
	{
		global $wgUser, $wgOut, $wgUseExternalEditor;

		$sk = $wgUser->getSkin();

		$line = $this->img->nextHistoryLine();

		if ( $line ) {
			$list = new ImageHistoryList( $sk, $this->img );
			$s = $list->beginImageHistoryList() .
				$list->imageHistoryLine( true, wfTimestamp(TS_MW, $line->img_timestamp),
					$this->mTitle->getDBkey(),  $line->img_user,
					$line->img_user_text, $line->img_size, $line->img_description,
					$line->img_width, $line->img_height
				);

			while ( $line = $this->img->nextHistoryLine() ) {
				$s .= $list->imageHistoryLine( false, $line->img_timestamp,
			  		$line->oi_archive_name, $line->img_user,
			  		$line->img_user_text, $line->img_size, $line->img_description,
					$line->img_width, $line->img_height
				);
			}
			$s .= $list->endImageHistoryList();
		} else { $s=''; }
		$wgOut->addHTML( $s );

		$this->img->resetHistory();	// free db resources

		# Exist check because we don't want to show this on pages where an image
		# doesn't exist along with the noimage message, that would suck. -ævar
		if( $wgUseExternalEditor && $this->img->exists() ) {
			$this->uploadLinksBox();
		}

	}

	function imageLinks()
	{
		global $wgUser, $wgOut;

		$wgOut->addHTML( Xml::element( 'h2', array( 'id' => 'filelinks' ), wfMsg( 'imagelinks' ) ) . "\n" );

		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$imagelinks = $dbr->tableName( 'imagelinks' );

		$sql = "SELECT page_namespace,page_title FROM $imagelinks,$page WHERE il_to=" .
		  $dbr->addQuotes( $this->mTitle->getDBkey() ) . " AND il_from=page_id";
		$sql = $dbr->limitResult($sql, 500, 0);
		$res = $dbr->query( $sql, "ImagePage::imageLinks" );

		if ( 0 == $dbr->numRows( $res ) ) {
			$wgOut->addHtml( '<p>' . wfMsg( "nolinkstoimage" ) . "</p>\n" );
			return;
		}
		$wgOut->addHTML( '<p>' . wfMsg( 'linkstoimage' ) .  "</p>\n<ul>" );

		$sk = $wgUser->getSkin();
		while ( $s = $dbr->fetchObject( $res ) ) {
			$name = Title::MakeTitle( $s->page_namespace, $s->page_title );
			$link = $sk->makeKnownLinkObj( $name, "" );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}

	function delete()
	{
		global $wgUser, $wgOut, $wgRequest;

		if ( !$this->img->exists() || !$this->img->isLocal() ) {
			# Use standard article deletion
			Article::delete();
			return;
		}

		$confirm = $wgRequest->wasPosted();
		$reason = $wgRequest->getVal( 'wpReason' );
		$image = $wgRequest->getVal( 'image' );
		$oldimage = $wgRequest->getVal( 'oldimage' );

		# Only sysops can delete images. Previously ordinary users could delete
		# old revisions, but this is no longer the case.
		if ( !$wgUser->isAllowed('delete') ) {
			$wgOut->permissionRequired( 'delete' );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( 'confirmdelete' ) );
		if ( ( !is_null( $image ) )
		  && ( '' == trim( $image ) ) ) {
			$wgOut->showFatalError( wfMsg( 'cannotdelete' ) );
			return;
		}

		# Deleting old images doesn't require confirmation
		if ( !is_null( $oldimage ) || $confirm ) {
			if( $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
				$this->doDeleteImage( $reason );
			} else {
				$wgOut->showFatalError( wfMsg( 'sessionfailure' ) );
			}
			return;
		}

		if ( !is_null( $image ) ) {
			$q = '&image=' . urlencode( $image );
		} else if ( !is_null( $oldimage ) ) {
			$q = '&oldimage=' . urlencode( $oldimage );
		} else {
			$q = '';
		}
		return $this->confirmDelete( $q, $wgRequest->getText( 'wpReason' ) );
	}

	/*
	 * Delete an image.
	 * Called doDeleteImage() not doDelete() so that Article::delete() doesn't 
	 * call back to here.
	 *
	 * @param $reason User provided reason for deletion.
	 */
	function doDeleteImage( $reason ) {
		global $wgOut, $wgRequest;

		$oldimage = $wgRequest->getVal( 'oldimage' );

		if ( !is_null( $oldimage ) ) {
			if ( strlen( $oldimage ) < 16 ) {
				$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}
			if( strpos( $oldimage, '/' ) !== false || strpos( $oldimage, '\\' ) !== false ) {
				$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}
			$status = $this->doDeleteOldImage( $oldimage );
			$deleted = $oldimage;
		} else {
			$status = $this->img->delete( $reason );
			if ( !$status->isGood() ) {
				// Warning or error
				$wgOut->addWikiText( $status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' ) );
			}
			if ( $status->ok ) {
				# Image itself is now gone, and database is cleaned.
				# Now we remove the image description page.
				$article = new Article( $this->mTitle );
				$article->doDeleteArticle( $reason ); # ignore errors
				$deleted = $this->img->getName();
			}
		}

		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if ( !$status->ok ) {
			// Fatal error flagged
			$wgOut->setPagetitle( wfMsg( 'errorpagetitle' ) );
			$wgOut->returnToMain( false, $this->mTitle->getPrefixedText() );
		} else {
			// Operation completed
			$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
			$loglink = '[[Special:Log/delete|' . wfMsg( 'deletionlog' ) . ']]';
			$text = wfMsg( 'deletedtext', $deleted, $loglink );
			$wgOut->addWikiText( $text );
			$wgOut->returnToMain( false, $this->mTitle->getPrefixedText() );
		}
	}

	/**
	 * Delete an old revision of an image, 
	 * @return FileRepoStatus
	 */
	function doDeleteOldImage( $oldimage ) {
		global $wgOut;

		$status = $this->img->deleteOld( $oldimage, '' );
		if( !$status->isGood() ) {
			$wgOut->addWikiText( $status->getWikiText( 'filedeleteerror-short', 'filedeleteerror-long' ) );
		}
		if ( $status->ok ) {
			# Log the deletion
			$log = new LogPage( 'delete' );
			$log->addEntry( 'delete', $this->mTitle, wfMsg('deletedrevision',$oldimage) );
		}
		return $status;
	}

	function revert() {
		global $wgOut, $wgRequest, $wgUser;

		$oldimage = $wgRequest->getText( 'oldimage' );
		if ( strlen( $oldimage ) < 16 ) {
			$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
			return;
		}
		if ( strstr( $oldimage, "/" ) || strstr( $oldimage, "\\" ) ) {
			$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
			return;
		}

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'uploadnologin', 'uploadnologintext' );
			return;
		}
		if ( ! $this->mTitle->userCan( 'edit' ) ) {
			$wgOut->readOnlyPage( $this->getContent(), true );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			$wgOut->blockedPage();
			return;
		}
		if( !$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
			$wgOut->showErrorPage( 'internalerror', 'sessionfailure' );
			return;
		}

		$sourcePath = $this->img->getArchiveVirtualUrl( $oldimage );
		$comment = wfMsg( "reverted" );
		// TODO: preserve file properties from DB instead of reloading from file
		$status = $this->img->upload( $sourcePath, $comment, $comment );

		if ( !$status->isGood() ) {
			$this->showError( $status->getWikiText() );
			return;
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsg( 'imagereverted' ) );

		$descTitle = $this->img->getTitle();
		$wgOut->returnToMain( false, $descTitle->getPrefixedText() );
	}
	
	/**
	 * Override handling of action=purge
	 */
	function doPurge() {
		if( $this->img->exists() ) {
			wfDebug( "ImagePage::doPurge purging " . $this->img->getName() . "\n" );
			$update = new HTMLCacheUpdate( $this->mTitle, 'imagelinks' );
			$update->doUpdate();
			$this->img->upgradeRow();
			$this->img->purgeCache();
		} else {
			wfDebug( "ImagePage::doPurge no image\n" );
		}
		parent::doPurge();
	}

	/**
	 * Display an error with a wikitext description
	 */
	function showError( $description ) {
		global $wgOut;
		$wgOut->setPageTitle( wfMsg( "internalerror" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->setArticleRelated( false );
		$wgOut->enableClientCache( false );
		$wgOut->addWikiText( $description );
	}

}

/**
 * Builds the image revision log shown on image pages
 *
 * @addtogroup Media
 */
class ImageHistoryList {

	protected $img, $skin, $title;

	public function __construct( $skin, $img ) {
		$this->skin = $skin;
		$this->img = $img;
		$this->title = $img->getTitle();
	}

	public function beginImageHistoryList() {
		global $wgOut, $wgUser;
		return Xml::element( 'h2', array( 'id' => 'filehistory' ), wfMsg( 'filehist' ) )
			. $wgOut->parse( wfMsgNoTrans( 'filehist-help' ) )
			. Xml::openElement( 'table', array( 'class' => 'filehistory' ) ) . "\n"
			. '<tr><th></th>'
			. ( $this->img->isLocal() && $wgUser->isAllowed( 'delete' ) ? '<th></th>' : '' )
			. '<th>' . wfMsgHtml( 'filehist-datetime' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-user' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-dimensions' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-filesize' ) . '</th>'
			. '<th>' . wfMsgHtml( 'filehist-comment' ) . '</th>'
			. "</tr>\n";
	}

	public function endImageHistoryList() {
		return "</table>\n";
	}

	public function imageHistoryLine( $iscur, $timestamp, $img, $user, $usertext, $size, $description, $width, $height ) {
		global $wgUser, $wgLang, $wgTitle, $wgContLang;
		$local = $this->img->isLocal();
		$row = '';
		
		// Deletion link
		if( $local && $wgUser->isAllowed( 'delete' ) ) {
			$row .= '<td>';
			$q[] = 'action=delete';
			$q[] = ( $iscur ? 'image=' . $this->title->getPartialUrl() : 'oldimage=' . urlencode( $img ) );
			if( !$iscur )
				$q[] = 'wpEditToken=' . urlencode( $wgUser->editToken( $img ) );
			$row .= '(' . $this->skin->makeKnownLinkObj(
				$this->title,
				wfMsgHtml( $iscur ? 'filehist-deleteall' : 'filehist-deleteone' ),
				implode( '&', $q )
			) . ')';
			$row .= '</td>';
		}
		
		// Reversion link/current indicator
		$row .= '<td>';
		if( $iscur ) {
			$row .= '(' . wfMsgHtml( 'filehist-current' ) . ')';
		} elseif( $local && $wgUser->isLoggedIn() && $this->title->userCan( 'edit' ) ) {
			$q[] = 'action=revert';
			$q[] = 'oldimage=' . urlencode( $img );
			$q[] = 'wpEditToken=' . urlencode( $wgUser->editToken( $img ) );
			$row .= '(' . $this->skin->makeKnownLinkObj(
				$this->title,
				wfMsgHtml( 'filehist-revert' ),
				implode( '&', $q )
			) . ')';
		}
		$row .= '</td>';
		
		// Date/time and image link
		$row .= '<td>';
		$url = $iscur ? $this->img->getUrl() : $this->img->getArchiveUrl( $img );
		$row .= Xml::element(
			'a',
			array( 'href' => $url ),
			$wgLang->timeAndDate( $timestamp, true )
		);
		$row .= '</td>';
		
		// Uploading user
		$row .= '<td>';
		if( $local ) {
			$row .= $this->skin->userLink( $user, $usertext ) . $this->skin->userToolLinks( $user, $usertext );
		} else {
			$row .= htmlspecialchars( $usertext );
		}
		$row .= '</td>';
		
		// Image dimensions
		// FIXME: What about sound files? Should have the duration instead...
		$row .= '<td>' . wfMsgHtml( 'widthheight', $width, $height ) . '</td>';
		
		// File size
		$row .= '<td>' . $this->skin->formatSize( $size ) . '</td>';
		
		// Comment
		$row .= '<td>' . $this->skin->formatComment( $description, $this->title ) . '</td>';
		
		return "<tr>{$row}</tr>\n";
	}

}