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
	'select_transcript_set' => 'Select layers',
	'auto_scroll' => 'auto scroll',
	'close' => 'close',
	'improve_transcript' => 'Improve',

	/*
	 * js file: /libAddMedia/mvBaseUploadInterface.js
	 */
	'upload-transcode-in-progress' => 'Transcode and upload in progress (do not close this window)',
	'upload-in-progress' => 'Upload in progress (do not close this window)',
	'upload-transcoded-status' => 'Transcoded',
	'uploaded-status' => 'Uploaded',
	'wgfogg_wrong_version' => 'You have Firefogg installed but it is outdated. <a href="http://firefogg.org">Please upgrade</a>.',
	'upload-stats-fileprogres' => '$1 of $2',
	'mv_upload_completed' => 'Your upload is complete',
	'mv_upload_done' => '<a href="$1">Your upload <i>should be</i> accessible</a>.',
	'upload-unknown-size' => 'Unknown size',
	'mv-cancel-confim' => 'Are you sure you want to cancel?',
	'successfulupload' => 'Upload successful',
	'uploaderror' => 'Upload error',
	'uploadwarning' => 'Upload warning',
	'unknown-error' => 'Unknown error:',
	'return-to-form' => 'Return to form',
	'file-exists-duplicate' => 'This file is a duplicate of the following file:',
	'fileexists' => 'A file with this name exists already. Please check <b><tt>$1</tt></b> if you are not sure if you want to change it.',
	'fileexists-thumb' => '<center><b>Existing file</b></center>',
	'ignorewarning' => 'Ignore warning and save file anyway',
	'file-thumbnail-no' => 'The filename begins with <b><tt>$1</tt></b>',
	'go-to-resource' => 'Go to resource page',
	'upload-misc-error' => 'Unknown upload error',
	'wgfogg_waring_bad_extension' => 'You have selected a file with an unsuported extension (<a href="http://commons.wikimedia.org/wiki/Commons:Firefogg#Supported_File_Types">more information</a>).',
	'cancel-button' => 'Cancel',
	'ok-button' => 'OK',

	/*
	 * js file: /libAddMedia/mvAdvFirefogg.js
	 */
	'help-sticky' => 'Help (click to stick)',
	'fogg-cg-preset' => 'Preset: <strong>$1</strong>',
	'fogg-cg-quality' => 'Basic quality and resolution control',
	'fogg-cg-meta' => 'Metadata for the clip',
	'fogg-cg-range' => 'Encoding range',
	'fogg-cg-advVideo' => 'Advanced video encoding controls',
	'fogg-cg-advAudio' => 'Advanced audio encoding controls',
	'fogg-preset-custom' => 'Custom settings',

	/*
	 * js file: /libAddMedia/searchLibs/metavidSearch.js
	 */
	'mv_stream_title' => '$1 $2 to $3',

	/*
	 * js file: /libAddMedia/searchLibs/baseRemoteSearch.js
	 */
	'imported_from' => '$1 imported from [$2 $3]. See the original [$4 resource page] for more information.',

	/*
	 * js file: /libAddMedia/mvFirefogg.js
	 */
	'fogg-select_file' => 'Select file',
	'fogg-select_new_file' => 'Select new file',
	'fogg-select_url' => 'Select URL',
	'fogg-save_local_file' => 'Save Ogg',
	'fogg-check_for_fogg' => 'Checking for Firefogg <blink>...</blink>',
	'fogg-installed' => 'Firefogg is installed',
	'fogg-for_improved_uplods' => 'For improved uploads:',
	'fogg-please_install' => '<a href="$1">Install Firefogg</a>. More <a href="http://commons.wikimedia.org/wiki/Commons:Firefogg">about Firefogg</a>',
	'fogg-use_latest_fox' => 'Please first install <a href="http://www.mozilla.com/en-US/firefox/upgrade.html?from=firefogg">Firefox 3.5</a> (or later). <i>Then revisit this page to install the <b>Firefogg</b> extension.</i>',
	'fogg-passthrough_mode' => 'Your selected file is already Ogg or not a video file',
	'fogg-transcoding' => 'Encoding video to Ogg',
	'fogg-encoding-done' => 'Encoding complete',
	'fogg-badtoken' => 'Token is not valid',

	/*
	 * js file: /libAddMedia/simpleUploadForm.js
	 */
	'select_file' => 'Select file',
	'more_licence_options' => 'For more licence options, view the <a href="$1">normal upload page</a>',
	'select_ownwork' => 'I am uploading entirely my own work, and licencing it under:',
	'licence_cc-by-sa' => 'Creative Commons Share Alike (3.0)',
	'upload' => 'Upload file',
	'destfilename' => 'Destination filename:',
	'summary' => 'Summary',
	'error_not_loggedin' => 'You do not appear to be logged in or do not have upload privlages.',

	/*
	 * js file: /libAddMedia/remoteSearchDriver.js
	 */
	'add_media_wizard' => 'Add media wizard',
	'mv_media_search' => 'Media search',
	'rsd_box_layout' => 'Box layout',
	'rsd_list_layout' => 'List layout',
	'rsd_results_desc' => 'Results',
	'rsd_results_next' => 'next',
	'rsd_results_prev' => 'previous',
	'rsd_no_results' => 'No search results for <b>$1</b>',
	'upload_tab' => 'Upload',
	'rsd_layout' => 'Layout:',
	'rsd_resource_edit' => 'Edit resource: $1',
	'resource_description_page' => 'Resource description page',
	'rsd_local_resource_title' => 'Local resource title',
	'rsd_do_insert' => 'Do insert',
	'cc_title' => 'Creative Commons',
	'cc_by_title' => 'Attribution',
	'cc_nc_title' => 'Noncommercial',
	'cc_nd_title' => 'No Derivative Works',
	'cc_sa_title' => 'Share Alike',
	'cc_pd_title' => 'Public Domain',
	'unknown_license' => 'Unknown license',
	'no_import_by_url' => 'This user or wiki <b>can not</b> import assets from remote URLs.</p><p>Do you need to login?</p><p>If permissions are set, you may have to enable $wgAllowCopyUploads (<a href="http://www.mediawiki.org/wiki/Manual:$wgAllowCopyUploads">more information</a>).</p>',
	'results_from' => 'Results from <a href="$1" target="_new" >$2</a>',
	'missing_desc_see_soruce' => 'This asset is missing a description. Please see the [$1 orginal source] and help describe it.',
	'rsd_config_error' => 'Add media wizard configuration error: $1',

	/*
	 * js file: /libSequencer/mvSequencer.js
	 */
	'menu_clipedit' => 'Edit media',
	'menu_transition' => 'Transitions and effects',
	'menu_cliplib' => 'Add media',
	'menu_resource_overview' => 'Resource overview',
	'menu_options' => 'Options',
	'loading_timeline' => 'Loading timeline <blink>...</blink>',
	'loading_user_rights' => 'Loading user rights <blink>...</blink>',
	'no_edit_permissions' => 'You do not have permissions to save changes to this sequence',
	'edit_clip' => 'Edit clip',
	'edit_save' => 'Save sequence changes',
	'saving_wait' => 'Save in progress (please wait)',
	'save_done' => 'Save complete',
	'edit_cancel' => 'Cancel sequence edit',
	'edit_cancel_confirm' => 'Are you sure you want to cancel your edit? Changes will be lost.',
	'zoom_in' => 'Zoom in',
	'zoom_out' => 'Zoom out',
	'cut_clip' => 'Cut clips',
	'expand_track' => 'Expand track',
	'colapse_track' => 'Collapse track',
	'play_from_position' => 'Play from playline position',
	'pixle2sec' => 'pixles to seconds',
	'rmclip' => 'Remove clip',
	'clip_in' => 'clip in',
	'clip_out' => 'clip out',
	'mv_welcome_to_sequencer' => '<h3>Welcome to the sequencer demo</h3> Very <b>limited</b> functionality right now. Not much documentation yet either.',
	'no_selected_resource' => '<h3>No resource selected</h3> Select a clip to enable editing.',
	'error_edit_multiple' => '<h3>Multiple resources selected</h3> Select a single clip to edit it.',
	'mv_editor_options' => 'Editor options',
	'mv_editor_mode' => 'Editor mode',
	'mv_simple_editor_desc' => 'simple editor (iMovie style)',
	'mv_advanced_editor_desc' => 'advanced editor (Final Cut style)',
	'mv_other_options' => 'Other options',
	'mv_contextmenu_opt' => 'Enable context menus',
	'mv_sequencer_credit_line' => 'Developed by <a href="http://kaltura.com">Kaltura, Inc.</a> in partnership with the <a href="http://wikimediafoundation.org/wiki/Home">Wikimedia Foundation</a> (<a href="#">more information</a>).',

	/*
	 * js file: /libSequencer/mvTimedEffectsEdit.js
	 */
	'transition_in' => 'Transition in',
	'transition_out' => 'Transition out',
	'effects' => 'Effects stack',
	'remove_transition' => 'Remove transition',
	'edit_transin' => 'Edit transition into clip',
	'edit_transout' => 'Edit transition out of clip',

	/*
	 * js file: /libEmbedVideo/embedVideo.js
	 */
	'loading_plugin' => 'loading plugin <blink>...</blink>',
	'select_playback' => 'Set playback preference',
	'link_back' => 'Link back',
	'error_load_lib' => 'Error: mv_embed was unable to load required JavaScript libraries.
Insert script via DOM has failed. Please try reloading this page.',
	'error_swap_vid' => 'Error: mv_embed was unable to swap the video tag for the mv_embed interface',
	'add_to_end_of_sequence' => 'Add to end of sequence',
	'missing_video_stream' => 'The video file for this stream is missing',
	'play_clip' => 'Play clip',
	'pause_clip' => 'Pause clip',
	'volume_control' => 'Volume control',
	'player_options' => 'Player options',
	'closed_captions' => 'Close captions',
	'player_fullscreen' => 'Fullscreen',
	'next_clip_msg' => 'Play next clip',
	'prev_clip_msg' => 'Play previous clip',
	'current_clip_msg' => 'Continue playing this clip',
	'seek_to' => 'Seek to',
	'download_segment' => 'Download selection:',
	'download_full' => 'Download full video file:',
	'download_right_click' => 'To download, right click and select <i>Save target as...</i>',
	'download_clip' => 'Download video',
	'download_text' => 'Download text (<a style="color:white" title="cmml" href="http://wiki.xiph.org/index.php/CMML">CMML</a> xml):',
	'download' => 'Download',
	'share' => 'Share',
	'credits' => 'Credits',
	'clip_linkback' => 'Clip source page',
	'chose_player' => 'Choose video player',
	'share_this_video' => 'Share this video',
	'video_credits' => 'Video credits',
	'menu_btn' => 'Menu',
	'close_btn' => 'Close',
	'mv_ogg-player-vlc-mozilla' => 'VLC plugin',
	'mv_ogg-player-videoElement' => 'Native Ogg video support',
	'mv_ogg-player-vlc-activex' => 'VLC ActiveX',
	'mv_ogg-player-oggPlugin' => 'Generic Ogg plugin',
	'mv_ogg-player-quicktime-mozilla' => 'Quicktime plugin',
	'mv_ogg-player-quicktime-activex' => 'Quicktime ActiveX',
	'mv_ogg-player-cortado' => 'Java Cortado',
	'mv_ogg-player-flowplayer' => 'Flowplayer',
	'mv_ogg-player-selected' => ' (selected)',
	'mv_ogg-player-omtkplayer' => 'OMTK Flash Vorbis',
	'mv_generic_missing_plugin' => 'You browser does not appear to support the following playback type: <b>$1</b><br />Visit the <a href="http://commons.wikimedia.org/wiki/Commons:Media_help">Playback Methods</a> page to download a player.<br />',
	'mv_for_best_experience' => 'For a better video playback experience we recommend:<br /><b><a href="http://www.mozilla.com/en-US/firefox/upgrade.html?from=mwEmbed">Firefox 3.5</a>.</b>',
	'mv_do_not_warn_again' => 'Dissmiss for now.',
	'players' => 'Players',

	/*
	 * js file: /libClipEdit/mvClipEdit.js
	 */
	'mv_crop' => 'Crop image',
	'mv_apply_crop' => 'Apply crop to image',
	'mv_reset_crop' => 'Reset crop',
	'mv_insert_image_page' => 'Insert into page',
	'mv_insert_into_sequence' => 'Insert into sequence',
	'mv_preview_insert' => 'Preview insert',
	'mv_cancel_image_insert' => 'Cancel insert',
	'sc_fileopts' => 'Clip detail edit',
	'sc_inoutpoints' => 'Set in-out points',
	'sc_overlays' => 'Overlays',
	'sc_audio' => 'Audio control',
	'sc_duration' => 'Duration',
	'mv_template_properties' => 'Template properties',
	'mv_custom_title' => 'Custom title',
	'mv_edit_properties' => 'Edit properties',
	'mv_other_properties' => 'Other properties',
	'mv_resource_page' => 'Resource page:',
	'mv_set_in_out_points' => 'Set in-out points',
	'mv_start_time' => 'Start time',
	'mv_end_time' => 'End time',
	'mv_preview_inout' => 'Preview/play in-out points',

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
