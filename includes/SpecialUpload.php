<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "Image.php" );

/**
 * Constructor
 */
function wfSpecialUpload() {
	global $wgRequest;
	$form = new UploadForm( $wgRequest );
	$form->execute();
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class UploadForm {
	var $mUploadAffirm, $mUploadFile, $mUploadDescription, $mIgnoreWarning;
	var $mUploadSaveName, $mUploadTempName, $mUploadSize, $mUploadOldVersion;
	var $mUploadCopyStatus, $mUploadSource, $mReUpload, $mAction, $mUpload;
	var $mOname, $mSessionKey;

	function UploadForm( &$request ) {
		$this->mUploadAffirm = $request->getVal( 'wpUploadAffirm' );
		$this->mUploadFile = $request->getVal( 'wpUploadFile' );
		$this->mUploadDescription = $request->getVal( 'wpUploadDescription');
		$this->mIgnoreWarning = $request->getVal( 'wpIgnoreWarning');
		$this->mUploadSaveName = $request->getVal( 'wpUploadSaveName');
		$this->mUploadTempName = $request->getVal( 'wpUploadTempName');
		$this->mUploadTempName = $request->getVal( 'wpUploadTempName');
		$this->mUploadSize = $request->getVal( 'wpUploadSize');
		$this->mUploadOldVersion = $request->getVal( 'wpUploadOldVersion');
		$this->mUploadCopyStatus = $request->getVal( 'wpUploadCopyStatus');
		$this->mUploadSource = $request->getVal( 'wpUploadSource');
		$this->mReUpload = $request->getCheck( 'wpReUpload' );
		$this->mAction = $request->getVal( 'action' );
		$this->mUpload = $request->getCheck( 'wpUpload' );
		$this->mSessionKey = $request->getVal( 'wpSessionKey' );

		if ( ! $this->mUploadTempName ) {
			$this->mUploadTempName = $request->getFileTempName( 'wpUploadFile' );
		}
		if ( ! $this->mUploadSize ) {
			$this->mUploadSize = $request->getFileSize( 'wpUploadFile' );
		}
		$this->mOname = $request->getFileName( 'wpUploadFile' );

	}

	function execute() {
		global $wgUser, $wgOut;
		global $wgDisableUploads;

		if ( $wgDisableUploads ) {
			$wgOut->addWikiText( wfMsg( "uploaddisabled" ) );
			return;
		}
		if ( ( 0 == $wgUser->getID() )
			or $wgUser->isBlocked() ) {
			$wgOut->errorpage( "uploadnologin", "uploadnologintext" );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if ( $this->mReUpload ) {
			$this->unsaveUploadedFile();
			$this->mainUploadForm( "" );
		} else if ( "submit" == $this->mAction || $this->mUpload ) {
			$this->processUpload();
		} else {
			$this->mainUploadForm( "" );
		}
	}


	function processUpload() {
		global $wgUser, $wgOut, $wgLang;
		global $wgUploadDirectory;
		global $wgSavedFile, $wgUploadOldVersion;
		global $wgUseCopyrightUpload, $wgCheckCopyrightUpload;
		global $wgCheckFileExtensions, $wgStrictFileExtensions;
		global $wgFileExtensions, $wgFileBlacklist, $wgUploadSizeWarning;

		if ( $wgUseCopyrightUpload ) {
			$this->mUploadAffirm = 1;
			if ($wgCheckCopyrightUpload && 
				(trim ( $this->mUploadCopyStatus ) == "" || trim ( $this->mUploadSource ) == "" )) {
				$this->mUploadAffirm = 0;
			}
		}

		if ( 1 != $this->mUploadAffirm ) {
			$this->mainUploadForm( WfMsg( "noaffirmation" ) );
			return;
		}

		if ( "" != $this->mOname ) {
			$basename = strrchr( $this->mOname, "/" );

			if ( false === $basename ) { $basename = $this->mOname; }
			else ( $basename = substr( $basename, 1 ) );


			$ext = strrchr( $basename, "." );
			if ( false === $ext ) { $ext = ""; }
			else { $ext = substr( $ext, 1 ); }

			if ( "" == $ext ) { $xl = 0; } else { $xl = strlen( $ext ) + 1; }
			$partname = substr( $basename, 0, strlen( $basename ) - $xl );

			if ( strlen( $partname ) < 3 ) {
				$this->mainUploadForm( WfMsg( "minlength" ) );
				return;
			}

			$changed_name = false;
			$bn = preg_replace ( "/[^".Title::legalChars()."]/", '-', $basename );
			if ( 0 != strcmp( $bn, $basename ) )
			{
				$changed_name = true;
				$basename = $bn;
			}


			$nt = Title::newFromText( $basename );
			if( !$nt ) {
				return $this->uploadError( wfMsg( "illegalfilename", htmlspecialchars( $basename ) ) );
			}
			$nt->setNamespace( Namespace::getImage() );
			$this->mUploadSaveName = $nt->getDBkey();

			/* Don't allow users to override the blacklist */
			if( $this->checkFileExtension( $ext, $wgFileBlacklist ) ||
				($wgStrictFileExtensions && !$this->checkFileExtension( $ext, $wgFileExtensions ) ) ) {
				return $this->uploadError( wfMsg( "badfiletype", htmlspecialchars( $ext ) ) );
			}
			
			$this->saveUploadedFile( $this->mUploadSaveName, $this->mUploadTempName );
			if ( !$nt->userCanEdit() ) {
				return $this->uploadError( wfMsg( "protectedpage" ) );
			}
			
			if ( ! $this->mIgnoreWarning ) {
				$warning = '';
				if( $changed_name || 0 != strcmp( ucfirst( $basename ), $this->mUploadSaveName ) ) {
					$warning .=  '<li>'.wfMsg( "badfilename", htmlspecialchars( $this->mUploadSaveName ) ).'</li>';
				}

				if ( $wgCheckFileExtensions ) {
					if ( ! $this->checkFileExtension( $ext, $wgFileExtensions ) ) {
						$warning .= '<li>'.wfMsg( "badfiletype", htmlspecialchars( $ext ) ).'</li>';
					}
				}
				if ( $wgUploadSizeWarning && ( $this->mUploadSize > $wgUploadSizeWarning ) ) {
					$warning .= '<li>'.wfMsg( "largefile" ).'</li>';
				}
				if ( $this->mUploadSize == 0 ) {
					$warning .= '<li>'.wfMsg( "emptyfile" ).'</li>';
				}
				if( $nt->getArticleID() ) {
					$sk = $wgUser->getSkin();
					$dname = $wgLang->getNsText( Namespace::getImage() ) . ":{$this->mUploadSaveName}";
					$dlink = $sk->makeKnownLink( $dname, $dname );
					$warning .= '<li>'.wfMsg( "fileexists", $dlink ).'</li>';
				}
				if($warning != '') return $this->uploadWarning($warning);
			}
		}
		if ( !is_null( $this->mUploadOldVersion ) ) {
			$wgUploadOldVersion = $this->mUploadOldVersion;
		}
		wfRecordUpload( $this->mUploadSaveName, $wgUploadOldVersion, $this->mUploadSize, 
		  $this->mUploadDescription, $this->mUploadCopyStatus, $this->mUploadSource );

		$sk = $wgUser->getSkin();
		$ilink = $sk->makeMediaLink( $this->mUploadSaveName, Image::wfImageUrl( $this->mUploadSaveName ) );
		$dname = $wgLang->getNsText( Namespace::getImage() ) . ":{$this->mUploadSaveName}";
		$dlink = $sk->makeKnownLink( $dname, $dname );

		$wgOut->addHTML( "<h2>" . wfMsg( "successfulupload" ) . "</h2>\n" );
		$text = wfMsg( "fileuploaded", $ilink, $dlink );
		$wgOut->addHTML( "<p>{$text}\n" );
		$wgOut->returnToMain( false );
	}

	function checkFileExtension( $ext, $list ) {
		return in_array( strtolower( $ext ), $list );
	}

	function saveUploadedFile( $saveName, $tempName ) {
		global $wgSavedFile, $wgUploadOldVersion;
		global $wgUploadDirectory, $wgOut;

		$dest = wfImageDir( $saveName );
		$archive = wfImageArchiveDir( $saveName );
		$wgSavedFile = "{$dest}/{$saveName}";

		if ( is_file( $wgSavedFile ) ) {
			$wgUploadOldVersion = gmdate( "YmdHis" ) . "!{$saveName}";

			if ( ! rename( $wgSavedFile, "${archive}/{$wgUploadOldVersion}" ) ) { 
				$wgOut->fileRenameError( $wgSavedFile,
				  "${archive}/{$wgUploadOldVersion}" );
				return;
			}
		} else {
			$wgUploadOldVersion = "";
		}
		if ( ! move_uploaded_file( $tempName, $wgSavedFile ) ) {
			$wgOut->fileCopyError( $tempName, $wgSavedFile );
		}
		chmod( $wgSavedFile, 0644 );
	}

	function unsaveUploadedFile() {
		global $wgUploadDirectory, $wgOut, $wgRequest;
		
		$wgSavedFile = $_SESSION['wsUploadFiles'][$this->mSessionKey];
		$wgUploadOldVersion = $this->mUploadOldVersion;

		if ( ! @unlink( $wgSavedFile ) ) {
			$wgOut->fileDeleteError( $wgSavedFile );
			return;
		}
		if ( "" != $wgUploadOldVersion ) {
			$hash = md5( substr( $wgUploadOldVersion, 15 ) );
			$archive = "{$wgUploadDirectory}/archive/" . $hash{0} .
			"/" . substr( $hash, 0, 2 );

			if ( ! rename( "{$archive}/{$wgUploadOldVersion}", $wgSavedFile ) ) {
				$wgOut->fileRenameError( "{$archive}/{$wgUploadOldVersion}",
				  $wgSavedFile );
			}
		}
	}

	function uploadError( $error ) {
		global $wgOut;
		$sub = wfMsg( "uploadwarning" );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		$wgOut->addHTML( "<h4 style='error'>{$error}</h4>\n" );
	}

	function uploadWarning( $warning ) {
		global $wgOut, $wgUser, $wgLang, $wgUploadDirectory, $wgRequest;
		global $wgSavedFile, $wgUploadOldVersion;
		global $wgUseCopyrightUpload;

		# wgSavedFile is stored in the session not the form, for security
		$this->mSessionKey = mt_rand( 0, 0x7fffffff );
		$_SESSION['wsUploadFiles'][$this->mSessionKey] = $wgSavedFile;

		$sub = wfMsg( "uploadwarning" );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		$wgOut->addHTML( "<ul class='warning'>{$warning}</ul><br/>\n" );

		$save = wfMsg( "savefile" );
		$reupload = wfMsg( "reupload" );
		$iw = wfMsg( "ignorewarning" );
		$reup = wfMsg( "reuploaddesc" );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Upload" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( $wgUseCopyrightUpload )
		{
			$copyright =  "
	<input type='hidden' name=\"wpUploadCopyStatus\" value=\"" . htmlspecialchars( $this->mUploadCopyStatus ) . "\" />
	<input type='hidden' name=\"wpUploadSource\" value=\"" . htmlspecialchars( $this->mUploadSource ) . "\" />
	";
		} else {
			$copyright = "";
		}

		$wgOut->addHTML( "
	<form id=\"uploadwarning\" method=\"post\" enctype=\"multipart/form-data\"
	action=\"{$action}\">
	<input type=hidden name=\"wpUploadAffirm\" value=\"1\" />
	<input type=hidden name=\"wpIgnoreWarning\" value=\"1\" />
	<input type=hidden name=\"wpUploadDescription\" value=\"" . htmlspecialchars( $this->mUploadDescription ) . "\" />
	{$copyright}
	<input type=hidden name=\"wpUploadSaveName\" value=\"" . htmlspecialchars( $this->mUploadSaveName ) . "\" />
	<input type=hidden name=\"wpUploadTempName\" value=\"" . htmlspecialchars( $this->mUploadTempName ) . "\" />
	<input type=hidden name=\"wpUploadSize\" value=\"" . htmlspecialchars( $this->mUploadSize ) . "\" />
	<input type=hidden name=\"wpSessionKey\" value=\"" . htmlspecialchars( $this->mSessionKey ) . "\" />
	<input type=hidden name=\"wpUploadOldVersion\" value=\"" . htmlspecialchars( $wgUploadOldVersion) . "\" />
	<table border='0'><tr>
	<tr><td align='right'>
	<input tabindex='2' type='submit' name=\"wpUpload\" value=\"{$save}\" />
	</td><td align='left'>{$iw}</td></tr>
	<tr><td align='right'>
	<input tabindex='2' type='submit' name=\"wpReUpload\" value=\"{$reupload}\" />
	</td><td align='left'>{$reup}</td></tr></table></form>\n" );
	}

	function mainUploadForm( $msg ) {
		global $wgOut, $wgUser, $wgLang, $wgUploadDirectory, $wgRequest;
		global $wgUseCopyrightUpload;

		if ( "" != $msg ) {
			$sub = wfMsg( "uploaderror" );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" .
			  "<h4 style='error'>{$msg}</h4>\n" );
		} else {
			$sub = wfMsg( "uploadfile" );
			$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
		}
		$wgOut->addWikiText( wfMsg( "uploadtext" ) );
		$sk = $wgUser->getSkin();

		$fn = wfMsg( "filename" );
		$fd = wfMsg( "filedesc" );
		$ulb = wfMsg( "uploadbtn" );

		$clink = $sk->makeKnownLink( wfMsg( "copyrightpage" ),
		  wfMsg( "copyrightpagename" ) );
		$ca = wfMsg( "affirmation", $clink );
		$iw = wfMsg( "ignorewarning" );

		$titleObj = Title::makeTitle( NS_SPECIAL, "Upload" );
		$action = $titleObj->escapeLocalURL();

		$source = "
	<td align='right'>
	<input tabindex='3' type='checkbox' name=\"wpUploadAffirm\" value=\"1\" id=\"wpUploadAffirm\" />
	</td><td align='left'><label for=\"wpUploadAffirm\">{$ca}</label></td>
	" ;
		if ( $wgUseCopyrightUpload )
		  {
			$source = "
	<td align='right' nowrap='nowrap'>" . wfMsg ( "filestatus" ) . ":</td>
	<td><input tabindex='3' type='text' name=\"wpUploadCopyStatus\" value=\"" .
	htmlspecialchars($this->mUploadCopyStatus). "\" size='40' /></td>
	</tr><tr>
	<td align='right'>". wfMsg ( "filesource" ) . ":</td>
	<td><input tabindex='4' type='text' name=\"wpUploadSource\" value=\"" .
	htmlspecialchars($this->mUploadSource). "\" size='40' /></td>
	" ;
		  }

		$wgOut->addHTML( "
	<form id=\"upload\" method=\"post\" enctype=\"multipart/form-data\"
	action=\"{$action}\">
	<table border='0'><tr>
	<td align='right'>{$fn}:</td><td align='left'>
	<input tabindex='1' type='file' name=\"wpUploadFile\" value=\""
	  . htmlspecialchars( $this->mUploadFile ) . "\" size='40' />
	</td></tr><tr>
	<td align='right'>{$fd}:</td><td align='left'>
	<input tabindex='2' type='text' name=\"wpUploadDescription\" value=\""
	  . htmlspecialchars( $this->mUploadDescription ) . "\" size='40' />
	</td></tr><tr>
	{$source}
	</tr>
	<tr><td></td><td align='left'>
	<input tabindex='5' type='submit' name=\"wpUpload\" value=\"{$ulb}\" />
	</td></tr></table></form>\n" );
	}
}
?>
