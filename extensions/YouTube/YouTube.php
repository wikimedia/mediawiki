<?php
/**
 * Parser hook-based extension to show audio and video players
 * from YouTube and other similar sites.
 *
 * @file
 * @ingroup Extensions
 * @author Przemek Piotrowski <ppiotr@wikia-inc.com> for Wikia, Inc.
 * @copyright © 2006-2008, Wikia Inc.
 * @licence GNU General Public Licence 2.0 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301
 * USA
 *
 * @todo one class (family) to rule 'em all
 * @todo make width/height_max != width/height_default; aoaudio height may be large - long playlist
 * @todo smart <video> and <audio> tag
 */

if( !defined( 'MEDIAWIKI' ) ) {
	echo "This is a MediaWiki extension.\n";
	exit( 1 );
}

$wgExtensionCredits['parserhook'][] = array(
	'path' => __FILE__,
	'name' => 'YouTube',
	'version' => '1.8.1',
	'author' => 'Przemek Piotrowski',
	'descriptionmsg' => 'youtube-desc',
);

$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['YouTube'] = $dir . 'YouTube.i18n.php';
$wgMessagesDirs['YouTube'] = $dir . '/i18n';

$wgHooks['ParserFirstCallInit'][] = 'wfYouTube';

function wfYouTube( &$parser ) {
	$parser->setHook( 'youtube', 'embedYouTube' );
	$parser->setHook( 'gvideo', 'embedGoogleVideo' );
	$parser->setHook( 'aovideo', 'embedArchiveOrgVideo' );
	$parser->setHook( 'aoaudio', 'embedArchiveOrgAudio' );
	$parser->setHook( 'wegame', 'embedWeGame' );
	$parser->setHook( 'tangler', 'embedTangler' );
	$parser->setHook( 'gtrailer', 'embedGametrailers' );
	$parser->setHook( 'nicovideo', 'embedNicovideo' );
	$parser->setHook( 'ggtube', 'embedGoGreenTube' );
	return true;
}

function embedYouTube_url2ytid( $url ) {
	$id = $url;

	if ( preg_match( '/^http:\/\/www\.youtube\.com\/watch\?v=(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	} elseif( preg_match( '/^http:\/\/www\.youtube\.com\/v\/([^&]+)(&autoplay=[0-1])?$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9A-Za-z_-]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedYouTube( $input, $argv, $parser ) {
	$ytid   = '';
	$width  = $width_max  = 425;
	$height = $height_max = 355;

	if ( !empty( $argv['ytid'] ) ) {
		$ytid = embedYouTube_url2ytid( $argv['ytid'] );
	} elseif ( !empty( $input ) ) {
		$ytid = embedYouTube_url2ytid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $ytid ) ) {
		$url = "http://www.youtube.com/v/{$ytid}";
		return "<object type=\"application/x-shockwave-flash\" data=\"{$url}\" width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"{$url}\"/><param name=\"wmode\" value=\"transparent\"/></object>";
	}
}

function embedYouTube_url2gvid( $url ) {
	$id = $url;

	if ( preg_match( '/^http:\/\/video\.google\.com\/videoplay\?docid=([^&]+)(&hl=.+)?$/', $url, $preg ) ) {
		$id = $preg[1];
	} elseif ( preg_match( '/^http:\/\/video\.google\.com\/googleplayer\.swf\?docId=(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9-]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedGoogleVideo( $input, $argv, $parser ) {
	$gvid   = '';
	$width  = $width_max  = 400;
	$height = $height_max = 326;

	if ( !empty( $argv['gvid'] ) ) {
		$gvid = embedYouTube_url2gvid( $argv['gvid'] );
	} elseif ( !empty( $input ) ) {
		$gvid = embedYouTube_url2gvid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $gvid ) ) {
		$url = "http://video.google.com/googleplayer.swf?docId={$gvid}";
		return "<object type=\"application/x-shockwave-flash\" data=\"{$url}\" width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"{$url}\"/><param name=\"wmode\" value=\"transparent\"/></object>";
	}
}

function embedYouTube_url2aovid( $url ) {
	$id = $url;

	if ( preg_match( '/http:\/\/www\.archive\.org\/download\/(.+)\.flv$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9A-Za-z_\/.]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedArchiveOrgVideo( $input, $argv, $parser ) {
	$aovid   = '';
	$width  = $width_max  = 320;
	$height = $height_max = 263;

	if ( !empty( $argv['aovid'] ) ) {
		$aovid = embedYouTube_url2aovid( $argv['aovid'] );
	} elseif ( !empty( $input ) ) {
		$aovid = embedYouTube_url2aovid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $aovid ) ) {
		$url = "http://www.archive.org/download/{$aovid}.flv";
		return "<object type=\"application/x-shockwave-flash\" data=\"http://www.archive.org/flv/FlowPlayerWhite.swf\" width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"http://www.archive.org/flv/FlowPlayerWhite.swf\"/><param name=\"flashvars\" value=\"config={loop: false, videoFile: '{$url}', autoPlay: false}\"/></object>";
	}
}

function embedYouTube_url2aoaid( $url ) {
	$id = $url;

	if ( preg_match( '/http:\/\/www\.archive\.org\/details\/(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9A-Za-z_\/.]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedArchiveOrgAudio( $input, $argv, $parser ) {
	$aoaid   = '';
	$width  = $width_max  = 400;
	$height = $height_max = 170;

	if ( !empty( $argv['aoaid'] ) ) {
		$aoaid = embedYouTube_url2aoaid( $argv['aoaid'] );
	} elseif ( !empty( $input ) ) {
		$aoaid = embedYouTube_url2aoaid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $aoaid ) ) {
		$url = urlencode( "http://www.archive.org/audio/xspf-maker.php?identifier={$aoaid}" );
		return "<object type=\"application/x-shockwave-flash\" data=\"http://www.archive.org/audio/xspf_player.swf?playlist_url={$url}\" width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"http://www.archive.org/audio/xspf_player.swf?playlist_url={$url}\"/></object>";
	}
}

function embedYouTube_url2weid( $url ) {
	$id = $url;

	if ( preg_match( '/^http:\/\/www\.wegame\.com\/watch\/(.+)\/$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9A-Za-z_-]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedWeGame( $input, $argv, $parser ) {
	$weid   = '';
	$width  = $width_max  = 488;
	$height = $height_max = 387;

	if ( !empty( $argv['weid'] ) ) {
		$weid = embedYouTube_url2weid( $argv['weid'] );
	} elseif ( !empty( $input ) ) {
		$weid = embedYouTube_url2weid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $weid ) ) {
		return "<object type=\"application/x-shockwave-flash\" data=\"http://www.wegame.com/static/flash/player2.swf\" width=\"{$width}\" height=\"{$height}\"><param name=\"flashvars\" value=\"tag={$weid}\"/></object>";
	}
}

function embedYouTube_url2tgid( $input ) {
	$tid = $gid = 0;

	if ( preg_match( '/^id=([0-9]+)\|gId=([0-9]+)$/i', $input, $preg ) ) {
		$tid = $preg[1];
		$gid = $preg[2];
	} elseif ( preg_match( '/^gId=([0-9]+)\|id=([0-9]+)$/i', $input, $preg ) ) {
		$tid = $preg[2];
		$gid = $preg[1];
	} elseif ( preg_match( '/^([0-9]+)\|([0-9]+)$/', $input, $preg ) ) {
		$tid = $preg[1];
		$gid = $preg[2];
	}

	return array( $tid, $gid );
}

function embedTangler( $input, $argv, $parser ) {
	$tid = $gid = '';

	if ( !empty( $argv['tid'] ) && !empty( $argv['gid'] ) ) {
		list( $tid, $gid ) = embedYouTube_url2tgid( "{$argv['tid']}|{$argv['gid']}" );
	} elseif ( !empty( $input ) ) {
		list( $tid, $gid ) = embedYouTube_url2tgid( $input );
	}

	if ( !empty( $tid ) && !empty( $gid ) ) {
		return "<p style=\"width: 410px; height: 480px\" id=\"tangler-embed-topic-{$tid}\"></p><script src=\"http://www.tangler.com/widget/embedtopic.js?id={$tid}&gId={$gid}\"></script>";
	}
}

function embedYouTube_url2gtid( $url ) {
	$id = $url;

	if ( preg_match( '/^http:\/\/www\.gametrailers\.com\/player\/(.+)\.html$/', $url, $preg ) ) {
		$id = $preg[1];
	} elseif ( preg_match( '/^http:\/\/www\.gametrailers\.com\/remote_wrap\.php\?mid=(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedGametrailers( $input, $argv, $parser ) {
	$gtid   = '';
	$width  = $width_max  = 480;
	$height = $height_max = 392;

	if ( !empty( $argv['gtid'] ) ) {
		$gtid = embedYouTube_url2gtid( $argv['gtid'] );
	} elseif ( !empty( $input ) ) {
		$gtid = embedYouTube_url2gtid( $input );
	}
	if ( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}
	if ( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if ( !empty( $gtid ) ) {
		$url = "http://www.gametrailers.com/remote_wrap.php?mid={$gtid}";
		// return "<object type=\"application/x-shockwave-flash\" width=\"{$width}\" height=\"{$height}\"><param name=\"movie\" value=\"{$url}\"/></object>";
		// gametrailers' flash doesn't work on FF with object tag alone )-: weird, yt and gvideo are ok )-: valid xhtml no more )-:
		return "<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"  codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" id=\"gtembed\" width=\"{$width}\" height=\"{$height}\">	<param name=\"allowScriptAccess\" value=\"sameDomain\" /> 	<param name=\"allowFullScreen\" value=\"true\" /> <param name=\"movie\" value=\"{$url}\"/> <param name=\"quality\" value=\"high\" /> <embed src=\"{$url}\" swLiveConnect=\"true\" name=\"gtembed\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"true\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"{$width}\" height=\"{$height}\"></embed> </object>";
	}
}

function embedYouTube_url2nvid( $url ) {
	$id = $url;

	preg_match( '/([0-9A-Za-z]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedNicovideo( $input, $argv, $parser ) {
	$nvid = '';

	if ( !empty( $argv['nvid'] ) ) {
		$nvid = embedYouTube_url2nvid( $argv['nvid'] );
	} elseif ( !empty( $input ) ) {
		$nvid = embedYouTube_url2nvid( $input );
	}

	if ( !empty( $nvid ) ) {
		$url = "http://ext.nicovideo.jp/thumb_watch/{$nvid}";
		return "<script type=\"text/javascript\" src=\"{$url}\"></script>";
	}
}

function embedYouTube_url2ggid( $url ) {
	$id = $url;

	if( preg_match( '/^http:\/\/www\.gogreentube\.com\/watch\.php\?v=(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	} elseif( preg_match( '/^http:\/\/www\.gogreentube\.com\/embed\/(.+)$/', $url, $preg ) ) {
		$id = $preg[1];
	}

	preg_match( '/([0-9A-Za-z]+)/', $id, $preg );
	$id = $preg[1];

	return $id;
}

function embedGoGreenTube( $input, $argv, $parser ) {
	$ggid = '';
	$width  = $width_max  = 432;
	$height = $height_max = 394;

	if( !empty( $argv['ggid'] ) ) {
		$ggid = embedYouTube_url2ggid( $argv['ggid'] );
	} elseif( !empty( $input ) ) {
		$ggid = embedYouTube_url2ggid( $input );
	}

	if( !empty( $argv['width'] ) && settype( $argv['width'], 'integer' ) && ( $width_max >= $argv['width'] ) ) {
		$width = $argv['width'];
	}

	if( !empty( $argv['height'] ) && settype( $argv['height'], 'integer' ) && ( $height_max >= $argv['height'] ) ) {
		$height = $argv['height'];
	}

	if( !empty( $ggid ) ) {
		$url = "http://www.gogreentube.com/embed/{$ggid}";
		return "<script type=\"text/javascript\" src=\"{$url}\"></script>";
	}
}
