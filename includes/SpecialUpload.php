<?php

function wfSpecialUpload()
{
	global $wgUser, $wgOut, $wpUpload, $wpReUpload, $action;
	global $wgDisableUploads;
	
	$fields = array( "wpUploadFile", "wpUploadDescription" );
	wfCleanFormFields( $fields );

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
	if ( isset( $wpReUpload) ) {
		unsaveUploadedFile();
		mainUploadForm( "" );
	} else if ( "submit" == $action || isset( $wpUpload ) ) {
		processUpload();
	} else {
		mainUploadForm( "" );
	}
}

function processUpload()
{
	global $wgUser, $wgOut, $wgLang, $wpUploadAffirm, $wpUploadFile;
	global $wpUploadDescription, $wpIgnoreWarning;
	global $wgUploadDirectory;
	global $wpUploadSaveName, $wpUploadTempName, $wpUploadSize;
	global $wgSavedFile, $wgUploadOldVersion, $wpUploadOldVersion;
	global $wgUseCopyrightUpload , $wpUploadCopyStatus , $wpUploadSource ;
	global $wgCheckFileExtensions, $wgStrictFileExtensions;
	global $wgFileExtensions, $wgFileBlacklist;

	if ( $wgUseCopyrightUpload ) {
		$wpUploadAffirm = 1;
		if ( trim ( $wpUploadCopyStatus ) == "" || trim ( $wpUploadSource ) == "" ) {
			$wpUploadAffirm = 0;
		}
	}

	if ( 1 != $wpUploadAffirm ) {
		mainUploadForm( WfMsg( "noaffirmation" ) );
		return;
	}
	if ( ! $wpUploadTempName ) {
		$wpUploadTempName = $_FILES['wpUploadFile']['tmp_name'];
	}
	if ( ! $wpUploadSize ) {
		$wpUploadSize = $_FILES['wpUploadFile']['size'];
	}
	$prev = error_reporting( E_ALL & ~( E_NOTICE | E_WARNING ) );
	$oname = wfCleanQueryVar( $_FILES['wpUploadFile']['name'] );
	if ( $wpUploadSaveName != "" ) $wpUploadSaveName = wfCleanQueryVar( $wpUploadSaveName );
	error_reporting( $prev );

	if ( "" != $oname ) {
		$basename = strrchr( $oname, "/" );
		if ( false === $basename ) { $basename = $oname; }
		else ( $basename = substr( $basename, 1 ) );

		$ext = strrchr( $basename, "." );
		if ( false === $ext ) { $ext = ""; }
		else { $ext = substr( $ext, 1 ); }

		if ( "" == $ext ) { $xl = 0; } else { $xl = strlen( $ext ) + 1; }
		$partname = substr( $basename, 0, strlen( $basename ) - $xl );

		if ( strlen( $partname ) < 3 ) {
			mainUploadForm( wfMsg( "minlength" ) );
			return;
		}
		$nt = Title::newFromText( $basename );
		if( !$nt ) {
			mainUploadForm( wfMsg( "badtitle" ) );
			return;
		}
		$wpUploadSaveName = $nt->getDBkey();

		/* Don't allow users to override the blacklist */
		if( checkFileExtension( $ext, $wgFileBlacklist ) ||
			($wgStrictFileExtensions && !checkFileExtension( $ext, $wgFileExtensions ) ) ) {
			return uploadError( wfMsg( "badfiletype", $ext ) );
		}
		
		saveUploadedFile();
		if ( ( ! $wpIgnoreWarning ) &&
		  ( 0 != strcmp( ucfirst( $basename ), $wpUploadSaveName ) ) ) {
			return uploadWarning( wfMsg( "badfilename", $wpUploadSaveName ) );
		}
	    
		if ( $wgCheckFileExtensions ) {
			if ( ( ! $wpIgnoreWarning ) &&
				 ( ! checkFileExtension( $ext, $wgFileExtensions ) ) ) {
				return uploadWarning( wfMsg( "badfiletype", $ext ) );
			}
		}
		if ( ( ! $wpIgnoreWarning ) && ( $wpUploadSize > 150000 ) ) {
			return uploadWarning( WfMsg( "largefile" ) );
		}
	}
	if ( isset( $wpUploadOldVersion ) ) {
		$wgUploadOldVersion = $wpUploadOldVersion;
	}
	wfRecordUpload( $wpUploadSaveName, $wgUploadOldVersion,
	  $wpUploadSize, $wpUploadDescription );

	$sk = $wgUser->getSkin();
	$ilink = $sk->makeMediaLink( $wpUploadSaveName, wfImageUrl(
	  $wpUploadSaveName ) );
	$dname = $wgLang->getNsText( Namespace::getImage() ) . ":{$wpUploadSaveName}";
	$dlink = $sk->makeKnownLink( $dname, $dname );

	$wgOut->addHTML( "<h2>" . wfMsg( "successfulupload" ) . "</h2>\n" );
	$text = wfMsg( "fileuploaded", $ilink, $dlink );
	$wgOut->addHTML( "<p>{$text}\n" );
	$wgOut->returnToMain( false );
}

function checkFileExtension( $ext, $list ) {
	return in_array( strtolower( $ext ), $list );
}

function saveUploadedFile()
{
	global $wpUploadSaveName, $wpUploadTempName;
	global $wgSavedFile, $wgUploadOldVersion;
	global $wgUploadDirectory, $wgOut;

	$dest = wfImageDir( $wpUploadSaveName );
	$archive = wfImageArchiveDir( $wpUploadSaveName );
	$wgSavedFile = "{$dest}/{$wpUploadSaveName}";

	if ( is_file( $wgSavedFile ) ) {
		$wgUploadOldVersion = gmdate( "YmdHis" ) . "!{$wpUploadSaveName}";

		if ( ! rename( $wgSavedFile, "${archive}/{$wgUploadOldVersion}" ) ) { 
			$wgOut->fileRenameError( $wgSavedFile,
			  "${archive}/{$wgUploadOldVersion}" );
			return;
		}
	} else {
		$wgUploadOldVersion = "";
	}
	if ( ! move_uploaded_file( $wpUploadTempName, $wgSavedFile ) ) {
		$wgOut->fileCopyError( $wpUploadTempName, $wgSavedFile );
	}
	chmod( $wgSavedFile, 0644 );
}

function unsaveUploadedFile()
{
	global $wpSessionKey, $wpUploadOldVersion;
	global $wgUploadDirectory, $wgOut;
	
	$wgSavedFile = $_SESSION['wsUploadFiles'][$wpSessionKey];
	$wgUploadOldVersion = $wpUploadOldVersion;

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

function uploadError( $error )
{
	global $wgOut;
	$sub = wfMsg( "uploadwarning" );
	$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
	$wgOut->addHTML( "<h4><font color=red>{$error}</font></h4>\n" );
}

function uploadWarning( $warning )
{
	global $wgOut, $wgUser, $wgLang, $wgUploadDirectory;
	global $wpUpload, $wpReUpload, $wpUploadAffirm, $wpUploadFile;
	global $wpUploadDescription, $wpIgnoreWarning;
	global $wpUploadSaveName, $wpUploadTempName, $wpUploadSize;
	global $wgSavedFile, $wgUploadOldVersion;
	global $wpSessionKey, $wpUploadOldVersion;
	global $wgUseCopyrightUpload , $wpUploadCopyStatus , $wpUploadSource ;

	# wgSavedFile is stored in the session not the form, for security
	$wpSessionKey = mt_rand( 0, 0x7fffffff );
	$_SESSION['wsUploadFiles'][$wpSessionKey] = $wgSavedFile;

	$sub = wfMsg( "uploadwarning" );
	$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
	$wgOut->addHTML( "<h4><font color=red>{$warning}</font></h4>\n" );

	$save = wfMsg( "savefile" );
	$reupload = wfMsg( "reupload" );
	$iw = wfMsg( "ignorewarning" );
	$reup = wfMsg( "reuploaddesc" );
	$action = wfLocalUrlE( $wgLang->specialPage( "Upload" ),
	  "action=submit" );

	if ( $wgUseCopyrightUpload )
	{
		$copyright =  "
<input type=hidden name=\"wpUploadCopyStatus\" value=\"" . htmlspecialchars( $wpUploadCopyStatus ) . "\">
<input type=hidden name=\"wpUploadSource\" value=\"" . htmlspecialchars( $wpUploadSource ) . "\">
";
	}

	$wgOut->addHTML( "
<form id=\"uploadwarning\" method=\"post\" enctype=\"multipart/form-data\"
action=\"{$action}\">
<input type=hidden name=\"wpUploadAffirm\" value=\"1\">
<input type=hidden name=\"wpIgnoreWarning\" value=\"1\">
<input type=hidden name=\"wpUploadDescription\" value=\"" . htmlspecialchars( $wpUploadDescription ) . "\">
{$copyright}
<input type=hidden name=\"wpUploadSaveName\" value=\"" . htmlspecialchars( $wpUploadSaveName ) . "\">
<input type=hidden name=\"wpUploadTempName\" value=\"" . htmlspecialchars( $wpUploadTempName ) . "\">
<input type=hidden name=\"wpUploadSize\" value=\"" . htmlspecialchars( $wpUploadSize ) . "\">
<input type=hidden name=\"wpSessionKey\" value=\"" . htmlspecialchars( $wpSessionKey ) . "\">
<input type=hidden name=\"wpUploadOldVersion\" value=\"" . htmlspecialchars( $wgUploadOldVersion) . "\">
<table border=0><tr>
<tr><td align=right>
<input tabindex=2 type=submit name=\"wpUpload\" value=\"{$save}\">
</td><td align=left>{$iw}</td></tr>
<tr><td align=right>
<input tabindex=2 type=submit name=\"wpReUpload\" value=\"{$reupload}\">
</td><td align=left>{$reup}</td></tr></table></form>\n" );
}

function mainUploadForm( $msg )
{
	global $wgOut, $wgUser, $wgLang, $wgUploadDirectory;
	global $wpUpload, $wpUploadAffirm, $wpUploadFile;
	global $wpUploadDescription, $wpIgnoreWarning;
	global $wgUseCopyrightUpload , $wpUploadSource , $wpUploadCopyStatus ;

	if ( "" != $msg ) {
		$sub = wfMsg( "uploaderror" );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" .
		  "<h4><font color=red>{$msg}</font></h4>\n" );
	} else {
		$sub = wfMsg( "uploadfile" );
		$wgOut->addHTML( "<h2>{$sub}</h2>\n" );
	}
	$wgOut->addHTML( "<p>" . wfMsg( "uploadtext" ) );
	$sk = $wgUser->getSkin();

	$fn = wfMsg( "filename" );
	$fd = wfMsg( "filedesc" );
	$ulb = wfMsg( "uploadbtn" );

	$clink = $sk->makeKnownLink( wfMsg( "copyrightpage" ),
	  wfMsg( "copyrightpagename" ) );
	$ca = wfMsg( "affirmation", $clink );
	$iw = wfMsg( "ignorewarning" );

	$action = wfLocalUrl( $wgLang->specialPage( "Upload" ) );

	$source = "
<td align=right>
<input tabindex=3 type=checkbox name=\"wpUploadAffirm\" value=\"1\" id=\"wpUploadAffirm\">
</td><td align=left><label for=\"wpUploadAffirm\">{$ca}</label></td>
" ;
	if ( $wgUseCopyrightUpload )
	  {
	    $source = "
<td align=right nowrap>" . wfMsg ( "filestatus" ) . ":</td>
<td><input tabindex=3 type=text name=\"wpUploadCopyStatus\" value=\"" .
htmlspecialchars($wpUploadCopyStatus). "\" size=40></td>
</tr><tr>
<td align=right>". wfMsg ( "filesource" ) . ":</td>
<td><input tabindex=4 type=text name=\"wpUploadSource\" value=\"" .
htmlspecialchars($wpUploadSource). "\" size=40></td>
" ;
	  }

	$wgOut->addHTML( "
<form id=\"upload\" method=\"post\" enctype=\"multipart/form-data\"
action=\"{$action}\">
<table border=0><tr>
<td align=right>{$fn}:</td><td align=left>
<input tabindex=1 type=file name=\"wpUploadFile\" value=\""
  . htmlspecialchars( $wpUploadFile ) . "\" size=40>
</td></tr><tr>
<td align=right>{$fd}:</td><td align=left>
<input tabindex=2 type=text name=\"wpUploadDescription\" value=\""
  . htmlspecialchars( $wpUploadDescription ) . "\" size=40>
</td></tr><tr>
{$source}
</tr>
<tr><td>&nbsp;</td><td align=left>
<input tabindex=5 type=submit name=\"wpUpload\" value=\"{$ulb}\">
</td></tr></table></form>\n" );
}

?>
