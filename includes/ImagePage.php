<?

/*
	Special handling for image description pages
*/

class ImagePage extends Article {

	function view() {
		Article::view();
		
		# If the article we've just shown is in the "Image" namespace,
		# follow it with the history list and link list for the image
		# it describes.

		if ( Namespace::getImage() == $this->mTitle->getNamespace() ) {
			$this->imageHistory();
			$this->imageLinks();
		}
	}
	
	# If the page we've just displayed is in the "Image" namespace,
	# we follow it with an upload history of the image and its usage.

	function imageHistory()
	{
		global $wgUser, $wgOut, $wgLang;
		$fname = "Article::imageHistory";

		$sql = "SELECT img_size,img_description,img_user," .
		  "img_user_text,img_timestamp FROM image WHERE " .
		  "img_name='" . wfStrencode( $this->mTitle->getDBkey() ) . "'";
		$res = wfQuery( $sql, DB_READ, $fname );

		if ( 0 == wfNumRows( $res ) ) { return; }

		$sk = $wgUser->getSkin();
		$s = $sk->beginImageHistoryList();		

		$line = wfFetchObject( $res );
		$s .= $sk->imageHistoryLine( true, $line->img_timestamp,
		  $this->mTitle->getText(),  $line->img_user,
		  $line->img_user_text, $line->img_size, $line->img_description );

		$sql = "SELECT oi_size,oi_description,oi_user," .
		  "oi_user_text,oi_timestamp,oi_archive_name FROM oldimage WHERE " .
		  "oi_name='" . wfStrencode( $this->mTitle->getDBkey() ) . "' " .
		  "ORDER BY oi_timestamp DESC";
		$res = wfQuery( $sql, DB_READ, $fname );

		while ( $line = wfFetchObject( $res ) ) {
			$s .= $sk->imageHistoryLine( false, $line->oi_timestamp,
			  $line->oi_archive_name, $line->oi_user,
			  $line->oi_user_text, $line->oi_size, $line->oi_description );
		}
		$s .= $sk->endImageHistoryList();
		$wgOut->addHTML( $s );
	}

	function imageLinks()
	{
		global $wgUser, $wgOut;

		$wgOut->addHTML( "<h2>" . wfMsg( "imagelinks" ) . "</h2>\n" );

		$sql = "SELECT il_from FROM imagelinks WHERE il_to='" .
		  wfStrencode( $this->mTitle->getDBkey() ) . "'";
		$res = wfQuery( $sql, DB_READ, "Article::imageLinks" );

		if ( 0 == wfNumRows( $res ) ) {
			$wgOut->addHtml( "<p>" . wfMsg( "nolinkstoimage" ) . "\n" );
			return;
		}
		$wgOut->addHTML( "<p>" . wfMsg( "linkstoimage" ) .  "\n<ul>" );

		$sk = $wgUser->getSkin();
		while ( $s = wfFetchObject( $res ) ) {
			$name = $s->il_from;
			$link = $sk->makeKnownLink( $name, "" );
			$wgOut->addHTML( "<li>{$link}</li>\n" );
		}
		$wgOut->addHTML( "</ul>\n" );
	}

	function delete()
	{
		global $wgUser, $wgOut;
		global $wpConfirm, $wpReason, $image, $oldimage;

		# Anybody can delete old revisions of images; only sysops
		# can delete articles and current images

		if ( !$wgUser->isSysop() ) {
			$wgOut->sysopRequired();
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Better double-check that it hasn't been deleted yet!
		$wgOut->setPagetitle( wfMsg( "confirmdelete" ) );
		if ( $image ) {
			if ( "" == trim( $image ) ) {
				$wgOut->fatalError( wfMsg( "cannotdelete" ) );
				return;
			}
		}
		
		# Likewise, deleting old images doesn't require confirmation
		if ( $oldimage || 1 == $wpConfirm ) {
			$this->doDelete();
			return;
		}
		
		if ( $image ) {
			$q = "&image={$image}";
		} else if ( $oldimage ) {
			$q = "&oldimage={$oldimage}";
		}
		return $this->confirmDelete( $q );
	}

	function doDelete()
	{
		global $wgOut, $wgUser, $wgLang;
		global $image, $oldimage, $wpReason;
		$fname = "Article::doDelete";

		if ( $image ) {
			$dest = wfImageDir( $image );
			$archive = wfImageDir( $image );
			if ( ! unlink( "{$dest}/{$image}" ) ) {
				$wgOut->fileDeleteError( "{$dest}/{$image}" );
				return;
			}
			$sql = "DELETE FROM image WHERE img_name='" .
			  wfStrencode( $image ) . "'";
			wfQuery( $sql, DB_WRITE, $fname );

			$sql = "SELECT oi_archive_name FROM oldimage WHERE oi_name='" .
			  wfStrencode( $image ) . "'";
			$res = wfQuery( $sql, DB_READ, $fname );

			while ( $s = wfFetchObject( $res ) ) {
				$this->doDeleteOldImage( $s->oi_archive_name );
			}	
			$sql = "DELETE FROM oldimage WHERE oi_name='" .
			  wfStrencode( $image ) . "'";
			wfQuery( $sql, DB_WRITE, $fname );

			# Image itself is now gone, and database is cleaned.
			# Now we remove the image description page.

			$nt = Title::newFromText( $wgLang->getNsText( Namespace::getImage() ) . ":" . $image );
			$this->doDeleteArticle( $nt );

			$deleted = $image;
		} else if ( $oldimage ) {
			$this->doDeleteOldImage( $oldimage );
			$sql = "DELETE FROM oldimage WHERE oi_archive_name='" .
			  wfStrencode( $oldimage ) . "'";
			wfQuery( $sql, DB_WRITE, $fname );

			$deleted = $oldimage;
		} else {
			$this->doDeleteArticle( $this->mTitle );
			$deleted = $this->mTitle->getPrefixedText();
		}
		$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		$sk = $wgUser->getSkin();
		$loglink = $sk->makeKnownLink( $wgLang->getNsText(
		  Namespace::getWikipedia() ) .
		  ":" . wfMsg( "dellogpage" ), wfMsg( "deletionlog" ) );

		$text = wfMsg( "deletedtext", $deleted, $loglink );

		$wgOut->addHTML( "<p>" . $text );
		$wgOut->returnToMain( false );
	}

	function doDeleteOldImage( $oldimage )
	{
		global $wgOut;

		$name = substr( $oldimage, 15 );
		$archive = wfImageArchiveDir( $name );
		if ( ! unlink( "{$archive}/{$oldimage}" ) ) {
			$wgOut->fileDeleteError( "{$archive}/{$oldimage}" );
		}
	}

	function revert()
	{
		global $wgOut;
		global $oldimage;

		if ( strlen( $oldimage ) < 16 ) {
			$wgOut->unexpectedValueError( "oldimage", $oldimage );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
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
		$size = wfGetSQL( "oldimage", "oi_size", "oi_archive_name='" .
		  wfStrencode( $oldimage ) . "'" );

		if ( ! rename( $curfile, "${archive}/{$oldver}" ) ) {
			$wgOut->fileRenameError( $curfile, "${archive}/{$oldver}" );
			return;
		}
		if ( ! copy( "{$archive}/{$oldimage}", $curfile ) ) {
			$wgOut->fileCopyError( "${archive}/{$oldimage}", $curfile );
		}
		wfRecordUpload( $name, $oldver, $size, wfMsg( "reverted" ) );

		$wgOut->setPagetitle( wfMsg( "actioncomplete" ) );
		$wgOut->setRobotpolicy( "noindex,nofollow" );
		$wgOut->addHTML( wfMsg( "imagereverted" ) );
		$wgOut->returnToMain( false );
	}
}

?>
