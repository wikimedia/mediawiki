<?php
/**
 * @package MediaWiki
 */

/**
 *
 */
if( !defined( 'MEDIAWIKI' ) )
	die();

require_once( 'Image.php' );

/**
 * Special handling for image description pages
 * @package MediaWiki
 */
class ImagePage extends Article {

	/* private */ var $img;  // Image object this page is shown for
	var $mExtraDescription = false;

	function render() {
		global $wgOut;
		$wgOut->setArticleBodyOnly(true);
		$wgOut->addWikitext($this->getContent(true));
	}

	function view() {
		global $wgUseExternalEditor, $wgOut, $wgShowEXIF;

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
			if ($exif)
				$wgOut->addWikiText($this->makeMetadataTable($exif));

			# No need to display noarticletext, we use our own message, output in openShowImage()
			if( $this->getID() ) {
				Article::view();
			} else {
				# Just need to set the right headers
				$wgOut->setArticleFlag( true );
				$wgOut->setRobotpolicy( 'index,follow' );
				$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );
				$wgOut->addMetaTags();
				$this->viewUpdates();
			}

			if ($this->mExtraDescription) {
				$fol = wfMsg('shareddescriptionfollows');
				if ($fol != '-')
					$wgOut->addWikiText(wfMsg('shareddescriptionfollows'));
				$wgOut->addHTML($this->mExtraDescription);
			}

			$this->closeShowImage();
			$this->imageHistory();
			$this->imageLinks();
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
			<li><a href="#file">' . $wgLang->getNsText( NS_IMAGE ) . '</a></li>' .
			($metadata ? '<li><a href="#metadata">' . wfMsg( 'metadata' ) . '</a></li>' : '') . '
			<li><a href="#filehistory">' . wfMsg( 'imghistory' ) . '</a></li>
			<li><a href="#filelinks">' . wfMsg( 'imagelinks' ) . '</a></li>
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
		$r = "{| class=metadata align=right width=250px\n";
		$r .= '|+ id=metadata | '. htmlspecialchars( wfMsg( 'metadata' ) ) . "\n";
		foreach( $exif as $k => $v ) {
			$tag = strtolower( $k );
			$r .= "! class=$tag |" . wfMsg( "exif-$tag" ) . "\n";
			$r .= "| class=$tag |" . htmlspecialchars( $v ) . "\n";
			$r .= "|-\n";
		}
		return substr($r, 0, -3) . '|}';
	}

	/**
	 * Overloading Article's getContent method.
	 * Omit noarticletext if sharedupload
	 *
	 * @param $noredir If true, do not follow redirects
	 */
	function getContent( $noredir )
	{
		if ( $this->img && $this->img->fromSharedDirectory && 0 == $this->getID() ) {
			return '';
		}
		return Article::getContent( $noredir );
	}

	function openShowImage()
	{
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest,
		       $wgUseImageResize, $wgRepositoryBaseUrl,
		       $wgUseExternalEditor, $wgServer, $wgFetchCommonsDescriptions;
		$full_url  = $this->img->getURL();
		$anchoropen = '';
		$anchorclose = '';

		if( $wgUser->getOption( 'imagesize' ) == '' ) {
			$sizeSel = User::getDefaultOption( 'imagesize' );
		} else {
			$sizeSel = IntVal( $wgUser->getOption( 'imagesize' ) );
		}
		if( !isset( $wgImageLimits[$sizeSel] ) ) {
			$sizeSel = User::getDefaultOption( 'imagesize' );
		}
		$max = $wgImageLimits[$sizeSel];
		$maxWidth = $max[0];
		$maxHeight = $max[1];
		$sk = $wgUser->getSkin();

		if ( $this->img->exists() ) {
			# image
			$width = $this->img->getWidth();
			$height = $this->img->getHeight();
			$showLink = false;

			if ( $this->img->allowInlineDisplay() and $width and $height) {
				# image

				# "Download high res version" link below the image
				$msg = wfMsg('showbigimage', $width, $height, intval( $this->img->getSize()/1024 ) );
				if ( $width > $maxWidth ) {
					$height = floor( $height * $maxWidth / $width );
					$width  = $maxWidth;
				}
				if ( $height > $maxHeight ) {
					$width = floor( $width * $maxHeight / $height );
					$height = $maxHeight;
				}
				if ( $width != $this->img->getWidth() || $height != $this->img->getHeight() ) {
					if( $wgUseImageResize ) {
						$thumbnail = $this->img->getThumbnail( $width );
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
				$wgOut->addHTML( '<div class="fullImageLink" id="file">' . $anchoropen .
				     "<img border=\"0\" src=\"{$url}\" width=\"{$width}\" height=\"{$height}\" alt=\"" .
				     htmlspecialchars( $wgRequest->getVal( 'image' ) ).'" />' . $anchorclose . '</div>' );
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
				$info = wfMsg( 'fileinfo',
					ceil($this->img->getSize()/1024.0),
					$this->img->getMimeType() );
	
				if (!$this->img->isSafeFile()) {
					$warning = wfMsg( 'mediawarning' );
					$wgOut->addWikiText( <<<END
<div class="fullMedia">
<span class="dangerousLink">[[Media:$filename|$filename]]</span>
<span class="fileInfo"> ($info)</span>
</div>

<div class="mediaWarning">$warning</div>
END
						);
				} else {
					$wgOut->addWikiText( <<<END
<div class="fullMedia">
[[Media:$filename|$filename]] <span class="fileInfo"> ($info)</span>
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
			$wgOut->addWikiText( wfMsg( 'noimage', $this->getUploadUrl() ) );
		}
	}

	function printSharedImageText() {
		global $wgRepositoryBaseUrl, $wgFetchCommonsDescriptions, $wgOut;

		$url = $wgRepositoryBaseUrl . urlencode($this->mTitle->getDBkey());
		$sharedtext = "<div class='sharedUploadNotice'>" . wfMsg("sharedupload");
		if ($wgRepositoryBaseUrl && !$wgFetchCommonsDescriptions) {
			$sharedtext .= " " . wfMsg("shareduploadwiki", $url);
		}
		$sharedtext .= "</div>";
		$wgOut->addWikiText($sharedtext);

		if ($wgRepositoryBaseUrl && $wgFetchCommonsDescriptions) {
			require_once("HttpFunctions.php");
			$ur = ini_set('allow_url_fopen', true);
			$text = wfGetHTTP($url . '?action=render');
			ini_set('allow_url_fopen', $ur);
			if ($text)
				$this->mExtraDescription = $text;
		}
	}

	function getUploadUrl() {
		global $wgServer;
		$uploadTitle = Title::makeTitle( NS_SPECIAL, 'Upload' );
		return $wgServer . $uploadTitle->getLocalUrl( 'wpDestFile=' . urlencode( $this->img->getName() ) );
	}


	function uploadLinksBox()
	{
		global $wgUser, $wgOut;

		if ($this->img->fromSharedDirectory)
			return;

		$sk = $wgUser->getSkin();
		$wgOut->addHTML( '<br /><ul><li>' );
		$wgOut->addWikiText( '<div>'. wfMsg( 'uploadnewversion', $this->getUploadUrl() ) .'</div>' );
		$wgOut->addHTML( '</li><li>' );
		$wgOut->addHTML( $sk->makeKnownLinkObj( $this->mTitle,
			wfMsg( 'edit-externally' ), "action=edit&externaledit=true&mode=file" ) );
		$wgOut->addWikiText( '<div>' .  wfMsg('edit-externally-help') . '</div>' );
		$wgOut->addHTML( '</li></ul>' );
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
			$list =& new ImageHistoryList( $sk );
			$s = $list->beginImageHistoryList() .
				$list->imageHistoryLine( true, $line->img_timestamp,
					$this->mTitle->getDBkey(),  $line->img_user,
					$line->img_user_text, $line->img_size, $line->img_description );

			while ( $line = $this->img->nextHistoryLine() ) {
				$s .= $list->imageHistoryLine( false, $line->img_timestamp,
			  	$line->oi_archive_name, $line->img_user,
			  	$line->img_user_text, $line->img_size, $line->img_description );
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
		  $dbr->addQuotes( $this->mTitle->getDBkey() ) . " AND il_from=page_id"
		  . " LIMIT 500"; # quickie emergency brake
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
		$image = $wgRequest->getVal( 'image' );
		$oldimage = $wgRequest->getVal( 'oldimage' );

		# Only sysops can delete images. Previously ordinary users could delete
		# old revisions, but this is no longer the case.
		if ( !$wgUser->isAllowed('delete') ) {
			$wgOut->sysopRequired();
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
			$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
			return;
		}

		$this->img  = new Image( $this->mTitle );

		# Deleting old images doesn't require confirmation
		if ( !is_null( $oldimage ) || $confirm ) {
			if( $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
				$this->doDelete();
			} else {
				$wgOut->fatalError( wfMsg( 'sessionfailure' ) );
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

	function doDelete()
	{
		global $wgOut, $wgUser, $wgContLang, $wgRequest;
		global $wgUseSquid, $wgInternalServer, $wgPostCommitUpdateList;
		$fname = 'ImagePage::doDelete';

		$reason = $wgRequest->getVal( 'wpReason' );
		$oldimage = $wgRequest->getVal( 'oldimage' );

		$dbw =& wfGetDB( DB_MASTER );

		if ( !is_null( $oldimage ) ) {
			if ( strlen( $oldimage ) < 16 ) {
				$wgOut->unexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}
			if ( strstr( $oldimage, "/" ) || strstr( $oldimage, "\\" ) ) {
				$wgOut->unexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
				return;
			}

			# Invalidate description page cache
			$this->mTitle->invalidateCache();

			# Squid purging
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer.wfImageArchiveUrl( $oldimage ),
					$wgInternalServer.$this->mTitle->getFullURL()
				);
				wfPurgeSquidServers($urlArr);
			}
			$this->doDeleteOldImage( $oldimage );
			$dbw->delete( 'oldimage', array( 'oi_archive_name' => $oldimage ) );
			$deleted = $oldimage;
		} else {
			$image = $this->mTitle->getDBkey();
			$dest = wfImageDir( $image );
			$archive = wfImageDir( $image );

			# Delete the image file if it exists; due to sync problems
			# or manual trimming sometimes the file will be missing.
			$targetFile = "{$dest}/{$image}";
			if( file_exists( $targetFile ) && ! @unlink( $targetFile ) ) {
				# If the deletion operation actually failed, bug out:
				$wgOut->fileDeleteError( $targetFile );
				return;
			}
			$dbw->delete( 'image', array( 'img_name' => $image ) );
			$res = $dbw->select( 'oldimage', array( 'oi_archive_name' ), array( 'oi_name' => $image ) );

			# Purge archive URLs from the squid
			$urlArr = Array();
			while ( $s = $dbw->fetchObject( $res ) ) {
				$this->doDeleteOldImage( $s->oi_archive_name );
				$urlArr[] = $wgInternalServer.wfImageArchiveUrl( $s->oi_archive_name );
			}

			# And also the HTML of all pages using this image
			$linksTo = $this->img->getLinksTo();
			if ( $wgUseSquid ) {
				$u = SquidUpdate::newFromTitles( $linksTo, $urlArr );
				array_push( $wgPostCommitUpdateList, $u );
			}

			$dbw->delete( 'oldimage', array( 'oi_name' => $image ) );

			# Image itself is now gone, and database is cleaned.
			# Now we remove the image description page.

			$article = new Article( $this->mTitle );
			$article->doDeleteArticle( $reason ); # ignore errors

			# Invalidate parser cache and client cache for pages using this image
			# This is left until relatively late to reduce lock time
			Title::touchArray( $linksTo );

			/* Delete thumbnails and refresh image metadata cache */
			$this->img->purgeCache();


			$deleted = $image;
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$loglink = '[[Special:Log/delete|' . wfMsg( 'deletionlog' ) . ']]';
		$text = wfMsg( 'deletedtext', $deleted, $loglink );

		$wgOut->addWikiText( $text );

		$wgOut->returnToMain( false, $this->mTitle->getPrefixedText() );
	}

	function doDeleteOldImage( $oldimage )
	{
		global $wgOut;

		$name = substr( $oldimage, 15 );
		$archive = wfImageArchiveDir( $name );

		# Delete the image if it exists. Sometimes the file will be missing
		# due to manual intervention or weird sync problems; treat that
		# condition gracefully and continue to delete the database entry.
		# Also some records may end up with an empty oi_archive_name field
		# if the original file was missing when a new upload was made;
		# don't try to delete the directory then!
		#
		$targetFile = "{$archive}/{$oldimage}";
		if( $oldimage != '' && file_exists( $targetFile ) && !@unlink( $targetFile ) ) {
			# If we actually have a file and can't delete it, throw an error.
			$wgOut->fileDeleteError( "{$archive}/{$oldimage}" );
		} else {
			# Log the deletion
			$log = new LogPage( 'delete' );
			$log->addEntry( 'delete', $this->mTitle, wfMsg('deletedrevision',$oldimage) );
		}
	}

	function revert()
	{
		global $wgOut, $wgRequest, $wgUser;
		global $wgUseSquid, $wgInternalServer, $wgDeferredUpdateList;

		$oldimage = $wgRequest->getText( 'oldimage' );
		if ( strlen( $oldimage ) < 16 ) {
			$wgOut->unexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
			return;
		}
		if ( strstr( $oldimage, "/" ) || strstr( $oldimage, "\\" ) ) {
			$wgOut->unexpectedValueError( 'oldimage', htmlspecialchars($oldimage) );
			return;
		}

		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if( $wgUser->isAnon() ) {
			$wgOut->errorpage( 'uploadnologin', 'uploadnologintext' );
			return;
		}
		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( $wgUser->isBlocked() ) {
			return $this->blockedIPpage();
		}
		if( !$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ), $oldimage ) ) {
			$wgOut->errorpage( 'internalerror', 'sessionfailure' );
			return;
		}
		$name = substr( $oldimage, 15 );

		$dest = wfImageDir( $name );
		$archive = wfImageArchiveDir( $name );
		$curfile = "{$dest}/{$name}";

		if ( ! is_file( $curfile ) ) {
			$wgOut->fileNotFoundError( htmlspecialchars( $curfile ) );
			return;
		}
		$oldver = wfTimestampNow() . "!{$name}";

		$dbr =& wfGetDB( DB_SLAVE );
		$size = $dbr->selectField( 'oldimage', 'oi_size', array( 'oi_archive_name' => $oldimage )  );

		if ( ! rename( $curfile, "${archive}/{$oldver}" ) ) {
			$wgOut->fileRenameError( $curfile, "${archive}/{$oldver}" );
			return;
		}
		if ( ! copy( "{$archive}/{$oldimage}", $curfile ) ) {
			$wgOut->fileCopyError( "${archive}/{$oldimage}", $curfile );
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
		require_once( 'EditPage.php' );
		$edit = new EditPage( $this );
		return $edit->blockedIPpage();
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

	function imageHistoryLine( $iscur, $timestamp, $img, $user, $usertext, $size, $description ) {
		global $wgUser, $wgLang, $wgContLang, $wgTitle;

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
		if ( 0 == $user ) {
			$userlink = $usertext;
		} else {
			$userlink = $this->skin->makeLinkObj(
				Title::makeTitle( NS_USER, $usertext ),
				$usertext );
		}
		$nbytes = wfMsg( 'nbytes', $size );
		$style = $this->skin->getInternalLinkAttributes( $url, $datetime );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$datetime}</a>"
		  . " . . {$userlink} ({$nbytes})";

		$s .= $this->skin->commentBlock( $description, $wgTitle );
		$s .= "</li>\n";
		return $s;
	}

}


?>
