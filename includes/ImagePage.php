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
		global $wgOut, $wgUser, $wgImageLimits, $wgRequest, $wgUseImageResize;
		$this->img  = Image::newFromTitle( $this->mTitle );
		$url  = $this->img->getUrl();
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
				     $wgRequest->getVal( 'image' )."\" />" . $anchorclose . "</div>";
			} else {
				$s = "<div class=\"fullMedia\">".$sk->makeMediaLink($this->img->getName(),"")."</div>";
			}
			$wgOut->addHTML( $s );
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
			$s = $sk->beginImageHistoryList() .
				$sk->imageHistoryLine( true, $line->img_timestamp,
					$this->mTitle->getDBkey(),  $line->img_user,
					$line->img_user_text, $line->img_size, $line->img_description );

			while ( $line = $this->img->nextHistoryLine() ) {
				$s .= $sk->imageHistoryLine( false, $line->img_timestamp,
			  	$line->oi_archive_name, $line->img_user,
			  	$line->img_user_text, $line->img_size, $line->img_description );
			}
			$s .= $sk->endImageHistoryList();
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
		  $dbr->addQuotes( $this->mTitle->getDBkey() ) . " AND il_from=cur_id";
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
		if ( !$wgUser->isSysop() ) {
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
		global $wgOut, $wgUser, $wgLang, $wgRequest;
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
			if ( ! @unlink( "{$dest}/{$image}" ) ) {
				$wgOut->fileDeleteError( "{$dest}/{$image}" );
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

			$nt = Title::newFromText( $wgLang->getNsText( Namespace::getImage() ) . ":" . $image );
			$article = new Article( $nt );
			$article->doDeleteArticle( $reason ); # ignore errors

			$deleted = $image;
		}

		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		$sk = $wgUser->getSkin();
		$loglink = $sk->makeKnownLink( $wgLang->getNsText(
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
		if ( ! @unlink( "{$archive}/{$oldimage}" ) ) {
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
			$wgOut->unexpectedValueError( 'oldimage', $oldimage );
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
			$wgOut->fileNotFoundError( $curfile );
			return;
		}
		$oldver = wfTimestampNow() . "!{$name}";
		
		$dbr =& wfGetDB( DB_SLAVE );
		$size = $dbr->getField( 'oldimage', 'oi_size', 'oi_archive_name=\'' .
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


?>
