<?php

# Image download authorisation script
define( "MEDIAWIKI", true );
require_once( "./LocalSettings.php" );
require_once( "includes/Setup.php" );
if ( $wgWhitelistRead && !$wgUser->getID() ) {
	header( "HTTP/1.0 403 Forbidden" );
	exit;
}

# Check if the filename is in the correct directory
$filename = realpath( $wgUploadDirectory . $_SERVER['PATH_INFO'] );
$realUploadDirectory = realpath( $wgUploadDirectory );
if ( substr( $filename, 0, strlen( $realUploadDirectory ) ) != $realUploadDirectory ) {
	header( "HTTP/1.0 403 Forbidden" );
	exit;
}

# Write file
$type = wfGetType( $filename );
if ( $type ) {
	header("Content-type: $type");
}

readfile( $filename );

function wfGetType( $filename ) {
	# There's probably a better way to do this
	$types = "application/andrew-inset ez
application/mac-binhex40 hqx
application/mac-compactpro cpt
application/mathml+xml mathml
application/msword doc
application/octet-stream bin dms lha lzh exe class so dll
application/oda oda
application/ogg ogg
application/pdf pdf
application/postscript ai eps ps
application/rdf+xml rdf
application/smil smi smil
application/srgs gram
application/srgs+xml grxml
application/vnd.mif mif
application/vnd.ms-excel xls
application/vnd.ms-powerpoint ppt
application/vnd.wap.wbxml wbxml
application/vnd.wap.wmlc wmlc
application/vnd.wap.wmlscriptc wmlsc
application/voicexml+xml vxml
application/x-bcpio bcpio
application/x-cdlink vcd
application/x-chess-pgn pgn
application/x-cpio cpio
application/x-csh csh
application/x-director dcr dir dxr
application/x-dvi dvi
application/x-futuresplash spl
application/x-gtar gtar
application/x-hdf hdf
application/x-javascript js
application/x-koan skp skd skt skm
application/x-latex latex
application/x-netcdf nc cdf
application/x-sh sh
application/x-shar shar
application/x-shockwave-flash swf
application/x-stuffit sit
application/x-sv4cpio sv4cpio
application/x-sv4crc sv4crc
application/x-tar tar
application/x-tcl tcl
application/x-tex tex
application/x-texinfo texinfo texi
application/x-troff t tr roff
application/x-troff-man man
application/x-troff-me me
application/x-troff-ms ms
application/x-ustar ustar
application/x-wais-source src
application/xhtml+xml xhtml xht
application/xslt+xml xslt
application/xml xml xsl
application/xml-dtd dtd
application/zip zip
audio/basic au snd
audio/midi mid midi kar
audio/mpeg mpga mp2 mp3
audio/x-aiff aif aiff aifc
audio/x-mpegurl m3u
audio/x-pn-realaudio ram rm
audio/x-pn-realaudio-plugin rpm
audio/x-realaudio ra
audio/x-wav wav
chemical/x-pdb pdb
chemical/x-xyz xyz
image/bmp bmp
image/cgm cgm
image/gif gif
image/ief ief
image/jpeg jpeg jpg jpe
image/png png
image/svg+xml svg
image/tiff tiff tif
image/vnd.djvu djvu djv
image/vnd.wap.wbmp wbmp
image/x-cmu-raster ras
image/x-icon ico
image/x-portable-anymap pnm
image/x-portable-bitmap pbm
image/x-portable-graymap pgm
image/x-portable-pixmap ppm
image/x-rgb rgb
image/x-xbitmap xbm
image/x-xpixmap xpm
image/x-xwindowdump xwd
model/iges igs iges
model/mesh msh mesh silo
model/vrml wrl vrml
text/calendar ics ifb
text/css css
text/html html htm
text/plain asc txt
text/richtext rtx
text/rtf rtf
text/sgml sgml sgm
text/tab-separated-values tsv
text/vnd.wap.wml wml
text/vnd.wap.wmlscript wmls
text/x-setext etx
video/mpeg mpeg mpg mpe
video/quicktime qt mov
video/vnd.mpegurl mxu
video/x-msvideo avi
video/x-sgi-movie movie
x-conference/x-cooltalk ice";

	$types = explode( "\n", $types );
	if ( !preg_match( "/\.(.*?)$/", $filename, $matches ) ) {
		return false;
	}

	foreach( $types as $type ) {
		$extensions = explode( " ", $type );
		for ( $i=1; $i<count( $extensions ); $i++ ) {
			if ( $extensions[$i] == $matches[1] ) {
				return $extensions[0];
			}
		}
	}
	return false;
}
?>
