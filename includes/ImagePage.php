<?php
/**
 * @package MediaWiki
 */

/**
 *
 */
require_once( 'Image.php' );

/**
 * Special handling for image description pages
 * @package MediaWiki
 */
class ImagePage extends Article {

	/* private */ var $img;  // Image object this page is shown for. Initilaized in openShowImage, not
				 // available in doDelete etc.

	function view() {
		if ( Namespace::getImage() == $this->mTitle->getNamespace() ) {
			$this->openShowImage();
		}

		Article::view();
		
		# If the article we've just shown is in the "Image" namespace,
		# follow it with the history list and link list for the image
		# it describes.

		if ( Namespace::getImage() == $this->mTitle->getNamespace() ) {
			$this->closeShowImage();
			$this->imageHistory();
			$this->imageLinks();
		}
	}

	function openShowImage()
	{
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest, 
		       $wgUseImageResize, $wgRepositoryBaseUrl;
		$this->img  = Image::newFromTitle( $this->mTitle );
		$url  = $this->img->getViewURL();
		$anchoropen = '';
		$anchorclose = '';
		if ( $wgUseImageResize && $wgUser->getOption( 'imagesize' ) != '' ) {
			$max = $wgImageLimits[ intval( $wgUser->getOption( 'imagesize' ) ) ];
			$maxWidth = $max[0];
			$maxHeight = $max[1];
		}


		if ( $this->img->exists() ) {

			$sk = $wgUser->getSkin();
			
			if ( $this->img->getType() != '' ) {
				# image
				$width = $this->img->getWidth();
				$height = $this->img->getHeight();
				$msg = wfMsg('showbigimage', $width, $height, intval( $this->img->getSize()/1024 ) );
				if ( $width > $maxWidth && $wgUseImageResize ) {
					$anchoropen  = "<a href=\"{$url}\">";
					$anchorclose = "<br>{$msg}</a>";

					$url = $this->img->createThumb( $maxWidth );
					$height = floor( $height * $maxWidth / $width );
					$width  = $maxWidth;
				} 
				if ( $height > $maxHeight && $wgUseImageResize ) {
					$anchoropen  = "<a href=\"{$url}\">";
					$anchorclose = "<br>{$msg}</a>";

					$width = floor( $width * $maxHeight / $height );
					$height = $maxHeight;
					$url = $this->img->createThumb( $width );
				}
				$s = "<div class=\"fullImageLink\">" . $anchoropen .
				     "<img border=\"0\" src=\"{$url}\" width=\"{$width}\" height=\"{$height}\" alt=\"" .
				     htmlspecialchars( $wgRequest->getVal( 'image' ) )."\" />" . $anchorclose . "</div>";
			} else {
				$s = "<div class=\"fullMedia\">".$sk->makeMediaLink($this->img->getName(),"")."</div>";
			}
			$wgOut->addHTML( $s );
			if($this->img->fromSharedDirectory) {
				$sharedtext="<div class=\"sharedUploadNotice\">" . wfMsg("sharedupload");
				if($wgRepositoryBaseUrl) {
					$sharedtext .= " ". wfMsg("shareduploadwiki",$wgRepositoryBaseUrl . urlencode($this->mTitle->getDBkey()));
				}
				$sharedtext.="</div>";
				$wgOut->addWikiText($sharedtext);
			}
		}
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
		global $wgUser, $wgOut;

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
	}

	function imageLinks()
	{
		global $wgUser, $wgOut;

		$wgOut->addHTML( '<h2>' . wfMsg( 'imagelinks' ) . "</h2>\n" );

		$dbr =& wfGetDB( DB_SLAVE );
		$cur = $dbr->tableName( 'cur' );
		$imagelinks = $dbr->tableName( 'imagelinks' );
		
		$sql = "SELECT cur_namespace,cur_title FROM $imagelinks,$cur WHERE il_to=" .
		  $dbr->addQuotes( $this->mTitle->getDBkey() ) . " AND il_from=cur_id"
		  . " LIMIT 500"; # quickie emergency brake
		$res = $dbr->query( $sql, DB_SLAVE, "Article::imageLinks" );

		if ( 0 == $dbr->numRows( $res ) ) {
			$wgOut->addHtml( '<p>' . wfMsg( "nolinkstoimage" ) . "</p>\n" );
			return;
		}
		$wgOut->addHTML( '<p>' . wfMsg( 'linkstoimage' ) .  "</p>\n<ul>" );

		$sk = $wgUser->getSkin();
		while ( $s = $dbr->fetchObject( $res ) ) {
			$name = Title::MakeTitle( $s->cur_namespace, $s->cur_title );
			$link = $sk->makeKnownLinkObj( $name, "" );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}

	function delete()
	{
		global $wgUser, $wgOut, $wgRequest;

		$confirm = $wgRequest->getBool( 'wpConfirm' );
		$image = $wgRequest->getVal( 'image' );
		$oldimage = $wgRequest->getVal( 'oldimage' );
		
		# Only sysops can delete images. Previously ordinary users could delete 
		# old revisions, but this is no longer the case.
		if ( !$wgUser->isAllowed('delete') ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( 'confirmdelete' ) );
		if ( !is_null( $image ) ) {
			if ( '' == trim( $image ) ) {
				$wgOut->fatalError( wfMsg( 'cannotdelete' ) );
				return;
			}
		}
		
		# Deleting old images doesn't require confirmation
		if ( !is_null( $oldimage ) || $confirm ) {
			$this->doDelete();
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
		global $wgUseSquid, $wgInternalServer, $wgDeferredUpdateList;
		$fname = 'ImagePage::doDelete';

		$reason = $wgRequest->getVal( 'wpReason' );
		$image = $wgRequest->getVal( 'image' );
		$oldimage = $wgRequest->getVal( 'oldimage' );
		
		$dbw =& wfGetDB( DB_MASTER );

		if ( !is_null( $oldimage ) ) {
			# Squid purging
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer.wfImageArchiveUrl( $oldimage )
				);
				wfPurgeSquidServers($urlArr);
			}
			$this->doDeleteOldImage( $oldimage );
			$dbw->delete( 'oldimage', array( 'oi_archive_name' => $oldimage ) );
			$deleted = $oldimage;
		} else {
			if ( is_null ( $image ) ) {
				$image = $this->mTitle->getDBkey();
			}
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
						
			# Squid purging
			if ( $wgUseSquid ) {
				$urlArr = Array(
					$wgInternalServer . Image::wfImageUrl( $image )
				);
				wfPurgeSquidServers($urlArr);
			}
			

			$urlArr = Array();
			while ( $s = $dbw->fetchObject( $res ) ) {
				$this->doDeleteOldImage( $s->oi_archive_name );
				$urlArr[] = $wgInternalServer.wfImageArchiveUrl( $s->oi_archive_name );
			}	
			
			# Squid purging, part II
			if ( $wgUseSquid ) {
				/* this needs to be done after LinksUpdate */
				$u = new SquidUpdate( $urlArr );
				array_push( $wgDeferredUpdateList, $u );
			}
			
			$dbw->delete( 'oldimage', array( 'oi_name' => $image ) );

			# Image itself is now gone, and database is cleaned.
			# Now we remove the image description page.

			$nt = Title::newFromText( $wgContLang->getNsText( Namespace::getImage() ) . ":" . $image );
			$article = new Article( $nt );
			$article->doDeleteArticle( $reason ); # ignore errors

			$deleted = $image;
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$sk = $wgUser->getSkin();
		$loglink = $sk->makeKnownLink( $wgContLang->getNsText(
		  Namespace::getWikipedia() ) .
		  ':' . wfMsg( 'dellogpage' ), wfMsg( 'deletionlog' ) );

		$text = wfMsg( 'deletedtext', $deleted, $loglink );

		$wgOut->addHTML( '<p>' . $text . "</p>\n" );
		$wgOut->returnToMain( false );
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
		global $wgOut, $wgRequest;
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
		if ( ! $this->mTitle->userCanEdit() ) {
			$wgOut->sysopRequired();
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
		$size = $dbr->selectField( 'oldimage', 'oi_size', 'oi_archive_name=\'' .
		  $dbr->strencode( $oldimage ) . "'" );

		if ( ! rename( $curfile, "${archive}/{$oldver}" ) ) {
			$wgOut->fileRenameError( $curfile, "${archive}/{$oldver}" );
			return;
		}
		if ( ! copy( "{$archive}/{$oldimage}", $curfile ) ) {
			$wgOut->fileCopyError( "${archive}/{$oldimage}", $curfile );
		}
		wfRecordUpload( $name, $oldver, $size, wfMsg( "reverted" ) );
		# Squid purging
		if ( $wgUseSquid ) {
			$urlArr = Array(
				$wgInternalServer.wfImageArchiveUrl( $name ),
				$wgInternalServer . Image::wfImageUrl( $name )
			);
			wfPurgeSquidServers($urlArr);
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );
		$wgOut->addHTML( wfMsg( 'imagereverted' ) );
		$wgOut->returnToMain( false );
	}
}

class ImageHistoryList {
	function ImageHistoryList( &$skin ) {
		$this->skin =& $skin;
	}
	
	function beginImageHistoryList() {
		$s = "\n<h2>" . wfMsg( 'imghistory' ) . "</h2>\n" .
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
			$url = Image::wfImageUrl( $img );
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
				$rlink = $this->skin->makeKnownLink( $wgTitle->getPrefixedText(),
				           wfMsg( 'revertimg' ), 'action=revert&oldimage=' .
				           urlencode( $img ) );
				$dlink = $this->skin->makeKnownLink( $wgTitle->getPrefixedText(),
				           $del, 'action=delete&oldimage=' . urlencode( $img ) );
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
			$userlink = $this->skin->makeLink( $wgContLang->getNsText( Namespace::getUser() ) .
			               ':'.$usertext, $usertext );
		}
		$nbytes = wfMsg( 'nbytes', $size );
		$style = $this->skin->getInternalLinkAttributes( $url, $datetime );

		$s = "<li> ({$dlink}) ({$rlink}) <a href=\"{$url}\"{$style}>{$datetime}</a>"
		  . " . . {$userlink} ({$nbytes})";

		if ( '' != $description && '*' != $description ) {
			$sk=$wgUser->getSkin();
			$s .= $wgContLang->emphasize(' (' . $sk->formatComment($description,$wgTitle) . ')');
		}
		$s .= "</li>\n";
		return $s;
	}

}


?>
