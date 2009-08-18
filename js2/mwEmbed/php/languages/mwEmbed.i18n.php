<?php
/**
 * Localization file for mwEmbed
 * updates can be merged from javascript by running maintenance/mergeJavascriptMsg.php
 * this file follows the "extension" conventions for language msgs in MediaWiki but should be "usable" stand-alone with the script-loader
 *
 * the following English language portion is automatically merged via the maintenance script.
 */

$messages['en'] = array(
	/*
	* js file: /libTimedText/mvTextInterface.js
	*/
	'select_transcript_set' => 'Select Layers',
	'auto_scroll' => 'auto scroll',
	'close' => 'close',
	'improve_transcript' => 'Improve',

	/*
	* js file: /libAddMedia/mvBaseUploadInterface.js
	*/
	'upload-transcode-in-progress' => 'Doing Transcode & Upload (do not close this window)',
	'upload-in-progress' => 'Upload in Progress (do not close this window)',
	'upload-transcoded-status' => 'Transcoded',
	'uploaded-status' => 'Uploaded',
	'wgfogg_wrong_version' => 'You have firefogg installed but its outdated, <a href="http://firefogg.org">please upgrade</a> ',
	'upload-stats-fileprogres' => '$1 of $2',
	'mv_upload_completed' => 'Your upload is complete',
	'mv_upload_done' => 'Your upload <i>should be</i> accessible <a href="$1">here</a>',
	'upload-unknown-size' => 'Unknown size',
	'mv-cancel-confim' => 'Are you sure you want to cancel?',
	'successfulupload' => 'Successful Upload',
	'uploaderror' => 'Upload error',
	'uploadwarning' => 'Upload warning',
	'unknown-error' => 'Unknown Error',
	'return-to-form' => 'Return to form',
	'file-exists-duplicate' => 'This file is a duplicate of the following file',
	'fileexists' => 'A file with this name exists already, please check <b><tt>$1</tt></b> if you are not sure if you want to change it.',
	'fileexists-thumb' => '<center><b>Existing file</b></center>',
	'ignorewarning' => 'Ignore warning and save file anyway',
	'file-thumbnail-no' => 'The filename begins with <b><tt>$1</tt></b>',
	'go-to-resource' => 'Go to Resource Page',
	'upload-misc-error' => 'Unknown upload error',
	'wgfogg_waring_bad_extension' => 'You have selected a file with an unsuported extension (<a href="http://commons.wikimedia.org/wiki/Commons:Firefogg#Supported_File_Types">more information</a>).',
	'cancel-button' => 'Cancel',
	'ok-button' => 'OK',

	/*
	* js file: /libAddMedia/mvAdvFirefogg.js
	*/
	'help-sticky' => 'Help (Click to Stick)',
	'fogg-cg-preset' => 'Preset: <strong>$1</strong>',
	'fogg-cg-quality' => 'Basic Quality and Resolution Control',
	'fogg-cg-meta' => 'Meta Data for the Clip',
	'fogg-cg-range' => 'Encoding Range',
	'fogg-cg-advVideo' => 'Advanced Video Encoding Controls',
	'fogg-cg-advAudio' => 'Advanced Audio Encoding Controls',
	'fogg-preset-custom' => 'Custom Settings',

	/*
	* js file: /libAddMedia/searchLibs/metavidSearch.js
	*/
	'mv_stream_title' => '$1 $2 to $3',

	/*
	* js file: /libAddMedia/searchLibs/baseRemoteSearch.js
	*/
	'imported_from' => '$1 imported from [$2 $3]. See the original [$4 resource page] for more info',

	/*
	* js file: /libAddMedia/mvFirefogg.js
	*/
	'fogg-select_file' => 'Select File',
	'fogg-select_new_file' => 'Select New File',
	'fogg-select_url' => 'Select Url',
	'fogg-save_local_file' => 'Save Ogg',
	'fogg-check_for_fogg' => 'Checking for Firefogg <blink>...</blink>',
	'fogg-installed' => 'Firefogg is Installed',
	'fogg-for_improved_uplods' => 'For Improved uploads: ',
	'fogg-please_install' => '<a href="$1">Install Firefogg</a>. More <a href="http://commons.wikimedia.org/wiki/Commons:Firefogg">about firefogg</a>',
	'fogg-use_latest_fox' => 'Please first install <a href="http://www.mozilla.com/en-US/firefox/upgrade.html?from=firefogg">Firefox 3.5</a> (or later). <i>then revisit this page to install the <b>firefogg</b> extention</i>',
	'fogg-passthrough_mode' => 'Your selected file is already ogg or not a video file',
	'fogg-transcoding' => 'Encoding Video to Ogg',
	'fogg-encoding-done' => 'Encoding Done',
	'fogg-badtoken' => 'Token is not valid',

	/*
	* js file: /libAddMedia/simpleUploadForm.js
	*/
	'select_file' => 'Select File',
	'more_licence_options' => 'For more licence options view the <a href="$1">normal upload page</a>',
	'select_ownwork' => 'I am uploading entirely my own work, and licencing it under:',
	'licence_cc-by-sa' => 'Creative Commons Share Alike (3.0)',
	'upload' => 'Upload File',
	'destfilename' => 'Destination filename:',
	'summary' => 'Summary',
	'error_not_loggedin' => 'You don\'t appear to be logged in or don\'t have upload privlages.',

	/*
	* js file: /libAddMedia/remoteSearchDriver.js
	*/
	'add_media_wizard' => 'Add Media Wizard',
	'mv_media_search' => 'Media Search',
	'rsd_box_layout' => 'Box layout',
	'rsd_list_layout' => 'List Layout',
	'rsd_results_desc' => 'Results ',
	'rsd_results_next' => 'next ',
	'rsd_results_prev' => 'previous ',
	'rsd_no_results' => 'No search results for <b>$1</b>',
	'upload_tab' => 'Upload',
	'rsd_layout' => 'Layout:',
	'rsd_resource_edit' => 'Edit Resource: $1',
	'resource_description_page' => 'Resource Description Page',
	'rsd_local_resource_title' => 'Local Resource Title',
	'rsd_do_insert' => 'Do Insert',
	'cc_title' => 'Creative Commons',
	'cc_by_title' => 'Attribution',
	'cc_nc_title' => 'Noncommercial',
	'cc_nd_title' => 'No Derivative Works',
	'cc_sa_title' => 'Share Alike',
	'cc_pd_title' => 'Public Domain',
	'unknown_license' => 'Unknown License',
	'no_import_by_url' => 'This User or Wiki <b>can not</b> import assets from remote URLs. </p><p> Do you need to Login? </p><p> If permissions are set you may have to enable $wgAllowCopyUploads, <a href="http://www.mediawiki.org/wiki/Manual:$wgAllowCopyUploads">more info</a></p>',
	'results_from' => 'Results from <a href="$1" target="_new" >$2</a>',
	'missing_desc_see_soruce' => 'This Asset is missing a description. Please see the [$1 orginal source] and help describe it',
	'rsd_config_error' => 'Add media Wizard configuation error: $1',

	/*
	* js file: /libSequencer/mvSequencer.js
	*/
	'menu_clipedit' => 'Edit Media',
	'menu_transition' => 'Transitions & Effects',
	'menu_cliplib' => 'Add Media',
	'menu_resource_overview' => 'Resource Overview',
	'menu_options' => 'Options',
	'loading_timeline' => 'Loading TimeLine <blink>...</blink>',
	'loading_user_rights' => 'Loading user rights <blink>...</blink>',
	'no_edit_permissions' => 'You don\'t have permissions to save changes to this sequence',
	'edit_clip' => 'Edit Clip',
	'edit_save' => 'Save Sequence Changes',
	'saving_wait' => 'Save in Progress (please wait)',
	'save_done' => 'Save Done',
	'edit_cancel' => 'Cancel Sequence Edit',
	'edit_cancel_confirm' => 'Are you sure you want to cancel your edit. Changes will be lost',
	'zoom_in' => 'Zoom In',
	'zoom_out' => 'Zoom Out',
	'cut_clip' => 'Cut Clips',
	'expand_track' => 'Expand Track',
	'colapse_track' => 'Collapse Track',
	'play_from_position' => 'Play From Playline Position',
	'pixle2sec' => 'pixles to seconds',
	'rmclip' => 'Remove Clip',
	'clip_in' => 'clip in',
	'clip_out' => 'clip out',
	'mv_welcome_to_sequencer' => '<h3>Welcome to the sequencer demo</h3> very <b>limited</b> functionality right now. Not much documentation yet either',
	'no_selected_resource' => '<h3>No Resource Selected</h3> Select a Clip to enable editing',
	'error_edit_multiple' => '<h3>Multiple Resources Selected</h3> Select a single clip to edit it',
	'mv_editor_options' => 'Editor options',
	'mv_editor_mode' => 'Editor mode',
	'mv_simple_editor_desc' => 'simple editor (iMovie style)',
	'mv_advanced_editor_desc' => 'advanced editor (Final Cut style)',
	'mv_other_options' => 'Other Options',
	'mv_contextmenu_opt' => 'Enable Context Menus',
	'mv_sequencer_credit_line' => 'Developed by <a href="http://kaltura.com">Kaltura, Inc.</a>  in partnership with the <a href="http://wikimediafoundation.org/wiki/Home">Wikimedia Foundation</a> ( <a href="#">more info</a> )',

	/*
	* js file: /libSequencer/mvTimedEffectsEdit.js
	*/
	'transition_in' => 'Transition In',
	'transition_out' => 'Transition Out',
	'effects' => 'Effects Stack',
	'remove_transition' => 'Remove Transition',
	'edit_transin' => 'Edit Transition Into Clip',
	'edit_transout' => 'Edit Transition Out of Clip',

	/*
	* js file: /libEmbedVideo/embedVideo.js
	*/
	'loading_plugin' => 'loading plugin<blink>...</blink>',
	'select_playback' => 'Set Playback Preference',
	'link_back' => 'Link Back',
	'error_load_lib' => 'mv_embed: Unable to load required javascript libraries
 insert script via DOM has failed, try reloading?  ',
	'error_swap_vid' => 'Error:mv_embed was unable to swap the video tag for the mv_embed interface',
	'add_to_end_of_sequence' => 'Add to End of Sequence',
	'missing_video_stream' => 'The video file for this stream is missing',
	'play_clip' => 'Play Clip',
	'pause_clip' => 'Pause Clip',
	'volume_control' => 'Volume Control',
	'player_options' => 'Player Options',
	'closed_captions' => 'Close Captions',
	'player_fullscreen' => 'Fullscreen',
	'next_clip_msg' => 'Play Next Clip',
	'prev_clip_msg' => 'Play Previous Clip',
	'current_clip_msg' => 'Continue Playing this Clip',
	'seek_to' => 'Seek to',
	'download_segment' => 'Download Selection:',
	'download_full' => 'Download Full Video File:',
	'download_right_click' => 'To download right click and select <i>save target as</i>',
	'download_clip' => 'Download Video',
	'download_text' => 'Download Text (<a style="color:white" title="cmml" href="http://wiki.xiph.org/index.php/CMML">cmml</a> xml):',
	'download' => 'Download',
	'share' => 'Share',
	'credits' => 'Credits',
	'clip_linkback' => 'Clip Source Page',
	'chose_player' => 'Choose Video Player',
	'share_this_video' => 'Share This Video',
	'video_credits' => 'Video Credits',
	'menu_btn' => 'MENU',
	'close_btn' => 'CLOSE',
	'mv_ogg-player-vlc-mozilla' => 'VLC Plugin',
	'mv_ogg-player-videoElement' => 'Native Ogg Video Support',
	'mv_ogg-player-vlc-activex' => 'VLC ActiveX',
	'mv_ogg-player-oggPlugin' => 'Generic Ogg Plugin',
	'mv_ogg-player-quicktime-mozilla' => 'Quicktime Plugin',
	'mv_ogg-player-quicktime-activex' => 'Quicktime ActiveX',
	'mv_ogg-player-cortado' => 'Java Cortado',
	'mv_ogg-player-flowplayer' => 'Flowplayer',
	'mv_ogg-player-selected' => ' (selected)',
	'mv_ogg-player-omtkplayer' => 'OMTK Flash Vorbis',
	'mv_generic_missing_plugin' => 'You browser does not appear to support playback type: <b>$1</b><br> visit the <a href="http://commons.wikimedia.org/wiki/Commons:Media_help">Playback Methods</a> page to download a player<br>',
	'mv_for_best_experience' => 'For a better video playback experience we recommend:<br> <b><a href="http://www.mozilla.com/en-US/firefox/upgrade.html?from=mwEmbed">Firefox 3.5</a></b>',
	'mv_do_not_warn_again' => 'Dissmiss for now.',
	'players' => 'Players',

	/*
	* js file: /libClipEdit/mvClipEdit.js
	*/
	'mv_crop' => 'Crop Image',
	'mv_apply_crop' => 'Apply Crop to Image',
	'mv_reset_crop' => 'Rest Crop',
	'mv_insert_image_page' => 'Insert Into page',
	'mv_insert_into_sequence' => 'Insert Into Sequence',
	'mv_preview_insert' => 'Preview Insert',
	'mv_cancel_image_insert' => 'Cancel Insert',
	'sc_fileopts' => 'Clip Detail Edit',
	'sc_inoutpoints' => 'Set In-Out points',
	'sc_overlays' => 'Overlays',
	'sc_audio' => 'Audio Control',
	'sc_duration' => 'Duration',
	'mv_template_properties' => 'Template Properties',
	'mv_custom_title' => 'Custom Title',
	'mv_edit_properties' => 'Edit Properties',
	'mv_other_properties' => 'Other Properties',
	'mv_resource_page' => 'Resource Page:',
	'mv_set_in_out_points' => 'Set in-out points',
	'mv_start_time' => 'Start Time',
	'mv_end_time' => 'End Time',
	'mv_preview_inout' => 'Preview/Play In-out points',

	/*
	* js file: /mv_embed.js
	*/
	'loading_txt' => 'loading <blink>...</blink>',
	'loading_title' => 'Loading...',
	'size-gigabytes' => '$1 GB',
	'size-megabytes' => '$1 MB',
	'size-kilobytes' => '$1 K',
	'size-bytes' => '$1 B',
);