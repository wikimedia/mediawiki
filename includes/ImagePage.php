<?php
/**
 * @package MediaWiki
 */

/**
 *
 */
if( !defined( 'MEDIAWIKI' ) )
	die( 1 );

/**
 * Special handling for image description pages
 * @package MediaWiki
 */
class ImagePage extends Article {

	/* private */ var $img;  // Image object this page is shown for
	var $mExtraDescription = false;

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
		global $wgOut, $wgShowEXIF;

		$this->img = new Image( $this->mTitle );

		if( $this->mTitle->getNamespace() == NS_IMAGE  ) {
			if ($wgShowEXIF && $this->img->exists()) {
				$exif = $this->img->getExifData();
				$showmeta = count($exif) ? true : false;
			} else {
				$exif = false;
				$showmeta = false;
			}

			if ($this->img->exists())
				$wgOut->addHTML($this->showTOC($showmeta));

			$this->openShowImage();

			# No need to display noarticletext, we use our own message, output in openShowImage()
			if( $this->getID() ) {
				Article::view();
			} else {
				# Just need to set the right headers
				$wgOut->setArticleFlag( true );
				$wgOut->setRobotpolicy( 'index,follow' );
				$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
				$this->viewUpdates();
			}

			# Show shared description, if needed
			if( $this->mExtraDescription ) {
				$fol = wfMsg( 'shareddescriptionfollows' );
				if( $fol != '-' ) {
					$wgOut->addWikiText( $fol );
				}
				$wgOut->addHTML( '<div id="shared-image-desc">' . $this->mExtraDescription . '</div>' );
			}

			$this->closeShowImage();
			$this->imageHistory();
			$this->imageLinks();
			if( $exif ) {
				global $wgStylePath, $wgStyleVersion;
				$expand = htmlspecialchars( wfEscapeJsString( wfMsg( 'metadata-expand' ) ) );
				$collapse = htmlspecialchars( wfEscapeJsString( wfMsg( 'metadata-collapse' ) ) );
				$wgOut->addHTML( "<h2 id=\"metadata\">" . wfMsgHtml( 'metadata' ) . "</h2>\n" );
				$wgOut->addWikiText( $this->makeMetadataTable( $exif ) );
				$wgOut->addHTML(
					"<script type=\"text/javascript\" src=\"$wgStylePath/common/metadata.js?$wgStyleVersion\"></script>\n" .
					"<script type=\"text/javascript\">attachMetadataToggle('mw_metadata', '$expand', '$collapse');</script>\n" );
			}
		} else {
			Article::view();
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
			<li><a href="#filehistory">' . wfMsgHtml( 'imghistory' ) . '</a></li>
			<li><a href="#filelinks">' . wfMsgHtml( 'imagelinks' ) . '</a></li>' .
			($metadata ? '<li><a href="#metadata">' . wfMsgHtml( 'metadata' ) . '</a></li>' : '') . '
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
	function makeMetadataTable( $exif ) {
		$r = wfMsg( 'metadata-help' ) . "\n\n";
		$r .= "{| id=mw_metadata class=mw_metadata\n";
		$visibleFields = $this->visibleMetadataFields();
		foreach( $exif as $k => $v ) {
			$tag = strtolower( $k );
			$msg = wfMsg( "exif-$tag" );
			$class = "exif-$tag";
			if( !in_array( $tag, $visibleFields ) ) {
				$class .= ' collapsable';
			}
			$r .= "|- class=\"$class\"\n";
			$r .= "!| $msg\n";
			$r .= "|| $v\n";
		}
		$r .= '|}';
		return $r;
	}

	/**
	 * Get a list of EXIF metadata items which should be displayed when
	 * the metadata table is collapsed.
	 *
	 * @return array of strings
	 * @access private
	 */
	function visibleMetadataFields() {
		$fields = array();
		$lines = explode( "\n", wfMsgForContent( 'metadata-fields' ) );
		foreach( $lines as $line ) {
			$matches = array();
			if( preg_match( '/^\\*\s*(.*?)\s*$/', $line, $matches ) ) {
				$fields[] = $matches[1];
			}
		}
		return $fields;
	}

	/**
	 * Overloading Article's getContent method.
	 * 
	 * Omit noarticletext if sharedupload; text will be fetched from the
	 * shared upload server if possible.
	 */
	function getContent() {
		if( $this->img && $this->img->fromSharedDirectory && 0 == $this->getID() ) {
			return '';
		}
		return Article::getContent();
	}

	function openShowImage() {
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest, $wgLang;
		global $wgUseImageResize, $wgGenerateThumbnailOnParse;

		$full_url  = $this->img->getURL();
		$anchoropen = '';
		$anchorclose = '';
		$sizeSel = intval( $wgUser->getOption( 'imagesize') );

		if( !isset( $wgImageLimits[$sizeSel] ) ) {
			$sizeSel = User::getDefaultOption( 'imagesize' );
		}
		$max = $wgImageLimits[$sizeSel];
		$maxWidth = $max[0];
		$maxHeight = $max[1];
		$sk = $wgUser->getSkin();

		if ( $this->img->exists() ) {
			# image
			$page = $wgRequest->getIntOrNull( 'page' );
			if ( ! is_null( $page ) ) {
				$this->img->selectPage( $page );
			} else {
				$page = 1;
			}
			$width = $this->img->getWidth();
			$height = $this->img->getHeight();
			$showLink = false;

			if ( $this->img->allowInlineDisplay() and $width and $height) {
				# image

				# "Download high res version" link below the image
				$msg = wfMsgHtml('showbigimage', $width, $height, intval( $this->img->getSize()/1024 ) );

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

					if( $wgUseImageResize ) {
						$thumbnail = $this->img->getThumbnail( $width, -1, $wgGenerateThumbnailOnParse );
						if ( $thumbnail == null ) {
							$url = $this->img->getViewURL();
						} else {
							$url = $thumbnail->getURL();
						}
					} else {
						# No resize ability? Show the full image, but scale
						# it down in the browser so it fits on the page.
						$url = $this->img->getViewURL();
					}
					$anchoropen  = "<a href=\"{$full_url}\">";
					$anchorclose = "</a><br />";
					if( $this->img->mustRender() ) {
						$showLink = true;
					} else {
						$anchorclose .= "\n$anchoropen{$msg}</a>";
					}
				} else {
					$url = $this->img->getViewURL();
					$showLink = true;
				}

				if ( $this->img->isMultipage() ) {
					$wgOut->addHTML( '<table class="multipageimage"><tr><td>' );
				}

				$wgOut->addHTML( '<div class="fullImageLink" id="file">' . $anchoropen .
				     "<img border=\"0\" src=\"{$url}\" width=\"{$width}\" height=\"{$height}\" alt=\"" .
				     htmlspecialchars( $this->img->getTitle()->getPrefixedText() ).'" />' . $anchorclose . '</div>' );

				if ( $this->img->isMultipage() ) {
					$count = $this->img->pageCount();

					if ( $page > 1 ) {
						$label = $wgOut->parse( wfMsg( 'imgmultipageprev' ), false );
						$link = $sk->makeLinkObj( $this->mTitle, $label, 'page='. ($page-1) );
						$this->img->selectPage( $page - 1 );
						$thumb1 = $sk->makeThumbLinkObj( $this->img, $link, $label, 'none' );
					} else {
						$thumb1 = '';
					}

					if ( $page < $count ) {
						$label = wfMsg( 'imgmultipagenext' );
						$this->img->selectPage( $page + 1 );
						$link = $sk->makeLinkObj( $this->mTitle, $label, 'page='. ($page+1) );
						$thumb2 = $sk->makeThumbLinkObj( $this->img, $link, $label, 'none' );
					} else {
						$thumb2 = '';
					}

					$select = '<form name="pageselector" action="' . $this->img->getEscapeLocalUrl( '' ) . '" method="GET" onchange="document.pageselector.submit();">' ;
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
				$filename = wfEscapeWikiText( $this->img->getName() );
				// Hacky workaround: for some reason we use the incorrect MIME type
				// image/svg for SVG.  This should be fixed internally, but at least
				// make the displayed type right.
				$mime = $this->img->getMimeType();
				if ($mime == 'image/svg') $mime = 'image/svg+xml';

				$info = wfMsg( 'fileinfo',
					ceil($this->img->getSize()/1024.0),
					$mime );

				global $wgContLang;
				$dirmark = $wgContLang->getDirMark();
				if (!$this->img->isSafeFile()) {
					$warning = wfMsg( 'mediawarning' );
					$wgOut->addWikiText( <<<END
<div class="fullMedia">
<span class="dangerousLink">[[Media:$filename|$filename]]</span>$dirmark
<span class="fileInfo"> ($info)</span>
</div>

<div class="mediaWarning">$warning</div>
END
						);
				} else {
					$wgOut->addWikiText( <<<END
<div class="fullMedia">
[[Media:$filename|$filename]]$dirmark <span class="fileInfo"> ($info)</span>
</div>
END
						);
				}
			}

			if($this->img->fromSharedDirectory) {
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
		global $wgRepositoryBaseUrl, $wgFetchCommonsDescriptions, $wgOut, $wgUser;

		$url = $wgRepositoryBaseUrl . urlencode($this->mTitle->getDBkey());
		$sharedtext = "<div class='sharedUploadNotice'>" . wfMsgWikiHtml("sharedupload");
		if ($wgRepositoryBaseUrl && !$wgFetchCommonsDescriptions) {

			$sk = $wgUser->getSkin();
			$title = SpecialPage::getTitleFor( 'Upload' );
			$link = $sk->makeKnownLinkObj($title, wfMsgHtml('shareduploadwiki-linktext'),
			array( 'wpDestFile' => urlencode( $this->img->getName() )));
			$sharedtext .= " " . wfMsgWikiHtml('shareduploadwiki', $link);
		}
		$sharedtext .= "</div>";
		$wgOut->addHTML($sharedtext);

		if ($wgRepositoryBaseUrl && $wgFetchCommonsDescriptions) {
			$text = Http::get($url . '?action=render');
			if ($text)
				$this->mExtraDescription = $text;
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

		if( $this->img->fromSharedDirectory )
			return;

		$sk = $wgUser->getSkin();
		
		$wgOut->addHtml( '<br /><ul>' );
		
		# "Upload a new version of this file" link
		if( $wgUser->isAllowed( 'reupload' ) ) {
			$ulink = $sk->makeExternalLink( $this->getUploadUrl(), wfMsg( 'uploadnewversion-linktext' ) );
			$wgOut->addHtml( "<li><div>{$ulink}</div></li>" );
		}
		
		# External editing link
		$elink = $sk->makeKnownLinkObj( $this->mTitle, wfMsg( 'edit-externally' ), 'action=edit&externaledit=true&mode=file' );
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
			$list = new ImageHistoryList( $sk );
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

		# Exist check because we don't want to show this on pages where an image
		# doesn't exist along with the noimage message, that would suck. -ævar
		if( $wgUseExternalEditor && $this->img->exists() ) {
			$this->uploadLinksBox();
		}

	}

	function imageLinks()
	{
		global $wgUser, $wgOut;

		$wgOut->addHTML( '<h2 id="filelinks">' . wfMsg( 'imagelinks' ) . "</h2>\n" );

		$dbr =& wfGetDB( DB_SLAVE );
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
			return $this->blockedIPpage();
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

		$this->img  = new Image( $this->mTitle );

		# Deleting old images doesn't require confirmation
		if ( !is_null( $oldimage ) || $confirm ) {
			if( $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
				$this->doDelete( $reason );
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
	 * @param $reason User provided reason for deletion.
	 */
	function doDelete( $reason ) {
		global $wgOut, $wgRequest;

		$oldimage = $wgRequest->getVal( 'oldimage' );

		if ( !is_null( $oldimage ) ) {
			if ( strlen( $oldimage ) < 16 ) {
				$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}
			if ( strstr( $oldimage, "/" ) || strstr( $oldimage, "\\" ) ) {
				$wgOut->showUnexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}
			if ( !$this->doDeleteOldImage( $oldimage ) ) {
				return;
			}
			$deleted = $oldimage;
		} else {
			$ok = $this->img->delete( $reason );
			if( !$ok ) {
				# If the deletion operation actually failed, bug out:
				$wgOut->showFileDeleteError( $this->img->getName() );
				return;
			}
			
			# Image itself is now gone, and database is cleaned.
			# Now we remove the image description page.
	
			$article = new Article( $this->mTitle );
			$article->doDeleteArticle( $reason ); # ignore errors

			$deleted = $this->img->getName();
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$loglink = '[[Special:Log/delete|' . wfMsg( 'deletionlog' ) . ']]';
		$text = wfMsg( 'deletedtext', $deleted, $loglink );

		$wgOut->addWikiText( $text );

		$wgOut->returnToMain( false, $this->mTitle->getPrefixedText() );
	}

	/**
	 * @return success
	 */
	function doDeleteOldImage( $oldimage )
	{
		global $wgOut;

		$ok = $this->img->deleteOld( $oldimage, '' );
		if( !$ok ) {
			# If we actually have a file and can't delete it, throw an error.
			# Something went awry...
			$wgOut->showFileDeleteError( "$oldimage" );
		} else {
			# Log the deletion
			$log = new LogPage( 'delete' );
			$log->addEntry( 'delete', $this->mTitle, wfMsg('deletedrevision',$oldimage) );
		}
		return $ok;
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
		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->readOnlyPage( $this->getContent(), true );
			return;
		}
		if ( $wgUser->isBlocked() ) {
			return $this->blockedIPpage();
		}
		if( !$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
			$wgOut->showErrorPage( 'internalerror', 'sessionfailure' );
			return;
		}
		$name = substr( $oldimage, 15 );

		$dest = wfImageDir( $name );
		$archive = wfImageArchiveDir( $name );
		$curfile = "{$dest}/{$name}";

		if ( !is_dir( $dest ) ) wfMkdirParents( $dest );
		if ( !is_dir( $archive ) ) wfMkdirParents( $archive );

		if ( ! is_file( $curfile ) ) {
			$wgOut->showFileNotFoundError( htmlspecialchars( $curfile ) );
			return;
		}
		$oldver = wfTimestampNow() . "!{$name}";

		$dbr =& wfGetDB( DB_SLAVE );
		$size = $dbr->selectField( 'oldimage', 'oi_size', array( 'oi_archive_name' => $oldimage )  );

		if ( ! rename( $curfile, "${archive}/{$oldver}" ) ) {
			$wgOut->showFileRenameError( $curfile, "${archive}/{$oldver}" );
			return;
		}
		if ( ! copy( "{$archive}/{$oldimage}", $curfile ) ) {
			$wgOut->showFileCopyError( "${archive}/{$oldimage}", $curfile );
			return;
		}

		# Record upload and update metadata cache
		$img = Image::newFromName( $name );
		$img->recordUpload( $oldver, wfMsg( "reverted" ) );

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsg( 'imagereverted' ) );

		$descTitle = $img->getTitle();
		$wgOut->returnToMain( false, $descTitle->getPrefixedText() );
	}

	function blockedIPpage() {
		$edit = new EditPage( $this );
		return $edit->blockedIPpage();
	}
	
	/**
	 * Override handling of action=purge
	 */
	function doPurge() {
		$this->img = new Image( $this->mTitle );
		if( $this->img->exists() ) {
			wfDebug( "ImagePage::doPurge purging " . $this->img->getName() . "\n" );
			$update = new HTMLCacheUpdate( $this->mTitle, 'imagelinks' );
			$update->doUpdate();
			$this->img->purgeCache();
		} else {
			wfDebug( "ImagePage::doPurge no image\n" );
		}
		parent::doPurge();
	}

}

/**
 * @todo document
 * @package MediaWiki
 */
class ImageHistoryList {
	function ImageHistoryList( &$skin ) {
		$this->skin =& $skin;
	}

	function beginImageHistoryList() {
		$s = "\n<h2 id=\"filehistory\">" . wfMsg( 'imghistory' ) . "</h2>\n" .
		  "<p>" . wfMsg( 'imghistlegend' ) . "</p>\n".'<ul class="special">';
		return $s;
	}

	function endImageHistoryList() {
		$s = "</ul>\n";
		return $s;
	}

	function imageHistoryLine( $iscur, $timestamp, $img, $user, $usertext, $size, $description, $width, $height ) {
		global $wgUser, $wgLang, $wgTitle, $wgContLang;

		$datetime = $wgLang->timeanddate( $timestamp, true );
		$del = wfMsg( 'deleteimg' );
		$delall = wfMsg( 'deleteimgcompletely' );
		$cur = wfMsg( 'cur' );

		if ( $iscur ) {
			$url = Image::imageUrl( $img );
			$rlink = $cur;
			if ( $wgUser->isAllowed('delete') ) {
				$link = $wgTitle->escapeLocalURL( 'image=' . $wgTitle->getPartialURL() .
				  '&action=delete' );
				$style = $this->skin->getInternalLinkAttributes( $link, $delall );

				$dlink = '<a href="'.$link.'"'.$style.'>'.$delall.'</a>';
			} else {
				$dlink = $del;
			}
		} else {
			$url = htmlspecialchars( wfImageArchiveUrl( $img ) );
			if( $wgUser->getID() != 0 && $wgTitle->userCanEdit() ) {
				$token = urlencode( $wgUser->editToken( $img ) );
				$rlink = $this->skin->makeKnownLinkObj( $wgTitle,
				           wfMsg( 'revertimg' ), 'action=revert&oldimage=' .
				           urlencode( $img ) . "&wpEditToken=$token" );
				$dlink = $this->skin->makeKnownLinkObj( $wgTitle,
				           $del, 'action=delete&oldimage=' . urlencode( $img ) .
				           "&wpEditToken=$token" );
			} else {
				# Having live active links for non-logged in users
				# means that bots and spiders crawling our site can
				# inadvertently change content. Baaaad idea.
				$rlink = wfMsg( 'revertimg' );
				$dlink = $del;
			}
		}
		
		$userlink = $this->skin->userLink( $user, $usertext ) . $this->skin->userToolLinks( $user, $usertext );
		$nbytes = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $size ) );
		$widthheight = wfMsg( 'widthheight', $width, $height );
		$style = $this->skin->getInternalLinkAttributes( $url, $datetime );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$datetime}</a> . . {$userlink} . . {$widthheight} ({$nbytes})";

		$s .= $this->skin->commentBlock( $description, $wgTitle );
		$s .= "</li>\n";
		return $s;
	}

}


?>
