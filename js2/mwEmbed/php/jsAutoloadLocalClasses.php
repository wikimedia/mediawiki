<?php
if ( !defined( 'MEDIAWIKI' ) ) die( 1 );

global $wgJSAutoloadLocalClasses, $wgMwEmbedDirectory;

// the basis of the loader calls:
$wgJSAutoloadLocalClasses['mv_embed']			= $wgMwEmbedDirectory . 'mv_embed.js';

// core:
$wgJSAutoloadLocalClasses['window.jQuery']		= $wgMwEmbedDirectory . 'jquery/jquery-1.3.2.js';

$wgJSAutoloadLocalClasses['j.secureEvalJSON']	= $wgMwEmbedDirectory . 'jquery/plugins/jquery.json-1.3.js';

$wgJSAutoloadLocalClasses['j.cookie']			= $wgMwEmbedDirectory . 'jquery/plugins/jquery.cookie.js';

$wgJSAutoloadLocalClasses['j.contextMenu']		= $wgMwEmbedDirectory . 'jquery/plugins/jquery.contextMenu.js';
$wgJSAutoloadLocalClasses['j.fn.pngFix']		= $wgMwEmbedDirectory . 'jquery/plugins/jquery.pngFix.js';

$wgJSAutoloadLocalClasses['j.fn.autocomplete']	= $wgMwEmbedDirectory . 'jquery/plugins/jquery.autocomplete.js';
$wgJSAutoloadLocalClasses['j.fn.hoverIntent']	= $wgMwEmbedDirectory . 'jquery/plugins/jquery.hoverIntent.js';
$wgJSAutoloadLocalClasses['Date.fromString']  	= $wgMwEmbedDirectory . 'jquery/plugins/date.js';
$wgJSAutoloadLocalClasses['j.fn.datePicker']	= $wgMwEmbedDirectory . 'jquery/plugins/jquery.datePicker.js';

// jcrop
$wgJSAutoloadLocalClasses['j.Jcrop'] 			= $wgMwEmbedDirectory . 'libClipEdit/Jcrop/js/jquery.Jcrop.js';

// color picker
$wgJSAutoloadLocalClasses['j.fn.ColorPicker']	= $wgMwEmbedDirectory . 'libClipEdit/colorpicker/js/colorpicker.js';

// jquery.ui
$wgJSAutoloadLocalClasses['j.ui']				= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.core.js';

$wgJSAutoloadLocalClasses['j.effects.blind']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.blind.js';
$wgJSAutoloadLocalClasses['j.effects.drop']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.drop.js';
$wgJSAutoloadLocalClasses['j.effects.pulsate']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.pulsate.js';
$wgJSAutoloadLocalClasses['j.effects.transfer']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.transfer.js';
$wgJSAutoloadLocalClasses['j.ui.droppable']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.droppable.js';
$wgJSAutoloadLocalClasses['j.ui.slider']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.slider.js';
$wgJSAutoloadLocalClasses['j.effects.bounce']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.bounce.js';
$wgJSAutoloadLocalClasses['j.effects.explode']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.explode.js';
$wgJSAutoloadLocalClasses['j.effects.scale']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.scale.js';
$wgJSAutoloadLocalClasses['j.ui.datepicker']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.datepicker.js';
$wgJSAutoloadLocalClasses['j.ui.progressbar']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.progressbar.js';
$wgJSAutoloadLocalClasses['j.ui.sortable']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.sortable.js';
$wgJSAutoloadLocalClasses['j.effects.clip']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.clip.js';
$wgJSAutoloadLocalClasses['j.effects.fold']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.fold.js';
$wgJSAutoloadLocalClasses['j.effects.shake']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.shake.js';
$wgJSAutoloadLocalClasses['j.ui.dialog']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.dialog.js';
$wgJSAutoloadLocalClasses['j.ui.resizable']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.resizable.js';
$wgJSAutoloadLocalClasses['j.ui.tabs']			= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.tabs.js';
$wgJSAutoloadLocalClasses['j.effects.core']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.core.js';
$wgJSAutoloadLocalClasses['j.effects.highlight']= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.highlight.js';
$wgJSAutoloadLocalClasses['j.effects.slide']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/effects.slide.js';
$wgJSAutoloadLocalClasses['j.ui.accordion']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.accordion.js';
$wgJSAutoloadLocalClasses['j.ui.draggable']		= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.draggable.js';
$wgJSAutoloadLocalClasses['j.ui.selectable']	= $wgMwEmbedDirectory . 'jquery/jquery.ui-1.7.1/ui/ui.selectable.js';

// libAddMedia:
$wgJSAutoloadLocalClasses['mvFirefogg'] 		= $wgMwEmbedDirectory . 'libAddMedia/mvFirefogg.js';
$wgJSAutoloadLocalClasses['mvAdvFirefogg'] 		= $wgMwEmbedDirectory . 'libAddMedia/mvAdvFirefogg.js';
$wgJSAutoloadLocalClasses['mvBaseUploadInterface'] = $wgMwEmbedDirectory . 'libAddMedia/mvBaseUploadInterface.js';
$wgJSAutoloadLocalClasses['remoteSearchDriver'] = $wgMwEmbedDirectory . 'libAddMedia/remoteSearchDriver.js';
$wgJSAutoloadLocalClasses['seqRemoteSearchDriver'] = $wgMwEmbedDirectory . 'libAddMedia/seqRemoteSearchDriver.js';
$wgJSAutoloadLocalClasses['baseRemoteSearch'] 	= $wgMwEmbedDirectory . 'libAddMedia/searchLibs/baseRemoteSearch.js';
$wgJSAutoloadLocalClasses['mediaWikiSearch'] 	= $wgMwEmbedDirectory . 'libAddMedia/searchLibs/mediaWikiSearch.js';
$wgJSAutoloadLocalClasses['metavidSearch'] 		= $wgMwEmbedDirectory . 'libAddMedia/searchLibs/metavidSearch.js';
$wgJSAutoloadLocalClasses['archiveOrgSearch'] 	= $wgMwEmbedDirectory . 'libAddMedia/searchLibs/archiveOrgSearch.js';
$wgJSAutoloadLocalClasses['baseRemoteSearch']	= $wgMwEmbedDirectory . 'libAddMedia/searchLibs/baseRemoteSearch.js';

// libClipEdit:
$wgJSAutoloadLocalClasses['mvClipEdit'] 		= $wgMwEmbedDirectory . 'libClipEdit/mvClipEdit.js';

// libEmbedObj:
$wgJSAutoloadLocalClasses['embedVideo'] 		= $wgMwEmbedDirectory . 'libEmbedVideo/embedVideo.js';
$wgJSAutoloadLocalClasses['flashEmbed'] 		= $wgMwEmbedDirectory . 'libEmbedVideo/flashEmbed.js';
$wgJSAutoloadLocalClasses['genericEmbed'] 		= $wgMwEmbedDirectory . 'libEmbedVideo/genericEmbed.js';
$wgJSAutoloadLocalClasses['htmlEmbed'] 			= $wgMwEmbedDirectory . 'libEmbedVideo/htmlEmbed.js';
$wgJSAutoloadLocalClasses['javaEmbed'] 			= $wgMwEmbedDirectory . 'libEmbedVideo/javaEmbed.js';
$wgJSAutoloadLocalClasses['nativeEmbed'] 		= $wgMwEmbedDirectory . 'libEmbedVideo/nativeEmbed.js';
$wgJSAutoloadLocalClasses['quicktimeEmbed'] 	= $wgMwEmbedDirectory . 'libEmbedVideo/quicktimeEmbed.js';
$wgJSAutoloadLocalClasses['vlcEmbed'] 			= $wgMwEmbedDirectory . 'libEmbedVideo/vlcEmbed.js';

// libSequencer:
$wgJSAutoloadLocalClasses['mvPlayList'] 		= $wgMwEmbedDirectory . 'libSequencer/mvPlayList.js';
$wgJSAutoloadLocalClasses['mvSequencer']		= $wgMwEmbedDirectory . 'libSequencer/mvSequencer.js';
$wgJSAutoloadLocalClasses['mvSequencer']		= $wgMwEmbedDirectory . 'libSequencer/mvSequencer.js';
$wgJSAutoloadLocalClasses['mvFirefoggRender']	= $wgMwEmbedDirectory . 'libSequencer/mvFirefoggRender.js';

// libTimedText:
$wgJSAutoloadLocalClasses['mvTimedEffectsEdit']	= $wgMwEmbedDirectory . 'libSequencer/mvTimedEffectsEdit.js';
