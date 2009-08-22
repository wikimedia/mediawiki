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
	 * js file: /libClipEdit/mvClipEdit.js
	 */
	'mwe-crop' => 'Crop image',
	'mwe-apply_crop' => 'Apply crop to image',
	'mwe-reset_crop' => 'Reset crop',
	'mwe-insert_image_page' => 'Insert into page',
	'mwe-insert_into_sequence' => 'Insert into sequence',
	'mwe-preview_insert' => 'Preview insert',
	'mwe-cancel_image_insert' => 'Cancel insert',
	'mwe-sc_fileopts' => 'Clip detail edit',
	'mwe-sc_inoutpoints' => 'Set in-out points',
	'mwe-sc_overlays' => 'Overlays',
	'mwe-sc_audio' => 'Audio control',
	'mwe-sc_duration' => 'Duration',
	'mwe-template_properties' => 'Template properties',
	'mwe-custom_title' => 'Custom title',
	'mwe-edit_properties' => 'Edit properties',
	'mwe-other_properties' => 'Other properties',
	'mwe-resource_page' => 'Resource page:',
	'mwe-set_in_out_points' => 'Set in-out points',
	'mwe-start_time' => 'Start time',
	'mwe-end_time' => 'End time',
	'mwe-preview_inout' => 'Preview/play in-out points',

	/*
	 * js file: /libTimedText/mvTextInterface.js
	 */
	'mwe-select_transcript_set' => 'Select layers',
	'mwe-auto_scroll' => 'auto scroll',
	'mwe-close' => 'close',
	'mwe-improve_transcript' => 'Improve',
	'mwe-no_text_tracks_found' => 'No text tracks were found',

	/*
	 * js file: /libSequencer/mvTimedEffectsEdit.js
	 */
	'mwe-transition_in' => 'Transition in',
	'mwe-transition_out' => 'Transition out',
	'mwe-effects' => 'Effects stack',
	'mwe-remove_transition' => 'Remove transition',
	'mwe-edit_transin' => 'Edit transition into clip',
	'mwe-edit_transout' => 'Edit transition out of clip',

	/*
	 * js file: /libSequencer/mvSequencer.js
	 */
	'mwe-menu_clipedit' => 'Edit media',
	'mwe-menu_transition' => 'Transitions and effects',
	'mwe-menu_cliplib' => 'Add media',
	'mwe-menu_resource_overview' => 'Resource overview',
	'mwe-menu_options' => 'Options',
	'mwe-loading_timeline' => 'Loading timeline <blink>...</blink>',
	'mwe-loading_user_rights' => 'Loading user rights <blink>...</blink>',
	'mwe-no_edit_permissions' => 'You do not have permissions to save changes to this sequence',
	'mwe-edit_clip' => 'Edit clip',
	'mwe-edit_save' => 'Save sequence changes',
	'mwe-saving_wait' => 'Save in progress (please wait)',
	'mwe-save_done' => 'Save complete',
	'mwe-edit_cancel' => 'Cancel sequence edit',
	'mwe-edit_cancel_confirm' => 'Are you sure you want to cancel your edit? Changes will be lost.',
	'mwe-zoom_in' => 'Zoom in',
	'mwe-zoom_out' => 'Zoom out',
	'mwe-cut_clip' => 'Cut clips',
	'mwe-expand_track' => 'Expand track',
	'mwe-collapse_track' => 'Collapse track',
	'mwe-play_from_position' => 'Play from playline position',
	'mwe-pixle2sec' => 'pixels to seconds',
	'mwe-rmclip' => 'Remove clip',
	'mwe-clip_in' => 'clip in',
	'mwe-clip_out' => 'clip out',
	'mwe-welcome_to_sequencer' => '<h3>Welcome to the sequencer demo</h3> Very <b>limited</b> functionality right now. Not much documentation yet either.',
	'mwe-no_selected_resource' => '<h3>No resource selected</h3> Select a clip to enable editing.',
	'mwe-error_edit_multiple' => '<h3>Multiple resources selected</h3> Select a single clip to edit it.',
	'mwe-editor_options' => 'Editor options',
	'mwe-editor_mode' => 'Editor mode',
	'mwe-simple_editor_desc' => 'simple editor (iMovie style)',
	'mwe-advanced_editor_desc' => 'advanced editor (Final Cut style)',
	'mwe-other_options' => 'Other options',
	'mwe-contextmenu_opt' => 'Enable context menus',
	'mwe-sequencer_credit_line' => 'Developed by <a href="http://kaltura.com">Kaltura, Inc.</a> in partnership with the <a href="http://wikimediafoundation.org/wiki/Home">Wikimedia Foundation</a> (<a href="#">more information</a>).',

	/*
	 * js file: /mv_embed.js
	 */
	'mwe-loading_txt' => 'loading <blink>...</blink>',
	'mwe-loading_title' => 'Loading...',
	'mwe-size-gigabytes' => '$1 GB',
	'mwe-size-megabytes' => '$1 MB',
	'mwe-size-kilobytes' => '$1 K',
	'mwe-size-bytes' => '$1 B',

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
	 * js file: /libAddMedia/searchLibs/baseRemoteSearch.js
	 */
	'mwe-imported_from' => '$1 imported from [$2 $3]. See the original [$4 resource page] for more information.',

	/*
	 * js file: /libAddMedia/searchLibs/metavidSearch.js
	 */
	'mwe-stream_title' => '$1 $2 to $3',

	/*
	 * js file: /libAddMedia/mvAdvFirefogg.js
	 */
	'fogg-help-sticky' => 'Help (click to stick)',
	'fogg-cg-preset' => 'Preset: <strong>$1</strong>',
	'fogg-cg-quality' => 'Basic quality and resolution control',
	'fogg-cg-meta' => 'Metadata for the clip',
	'fogg-cg-range' => 'Encoding range',
	'fogg-cg-advVideo' => 'Advanced video encoding controls',
	'fogg-cg-advAudio' => 'Advanced audio encoding controls',
	'fogg-preset-custom' => 'Custom settings',

	/*
	 * js file: /libAddMedia/remoteSearchDriver.js
	 */
	'mwe-add_media_wizard' => 'Add media wizard',
	'mwe-media_search' => 'Media search',
	'rsd_box_layout' => 'Box layout',
	'rsd_list_layout' => 'List layout',
	'rsd_results_desc' => 'Results',
	'rsd_results_next' => 'next',
	'rsd_results_prev' => 'previous',
	'rsd_no_results' => 'No search results for <b>$1</b>',
	'mwe-upload_tab' => 'Upload',
	'rsd_layout' => 'Layout:',
	'rsd_resource_edit' => 'Edit resource: $1',
	'mwe-resource_description_page' => 'Resource description page',
	'rsd_local_resource_title' => 'Local resource title',
	'rsd_do_insert' => 'Do insert',
	'mwe-cc_title' => 'Creative Commons',
	'mwe-cc_by_title' => 'Attribution',
	'mwe-cc_nc_title' => 'Noncommercial',
	'mwe-cc_nd_title' => 'No Derivative Works',
	'mwe-cc_sa_title' => 'Share Alike',
	'mwe-cc_pd_title' => 'Public Domain',
	'mwe-unknown_license' => 'Unknown license',
	'mwe-no_import_by_url' => 'This user or wiki <b>can not</b> import assets from remote URLs.</p><p>Do you need to login?</p><p>If permissions are set, you may have to enable $wgAllowCopyUploads (<a href="http://www.mediawiki.org/wiki/Manual:$wgAllowCopyUploads">more information</a>).</p>',
	'mwe-results_from' => 'Results from <a href="$1" target="_new" >$2</a>',
	'mwe-missing_desc_see_source' => 'This asset is missing a description. Please see the [$1 orginal source] and help describe it.',
	'rsd_config_error' => 'Add media wizard configuration error: $1',
	'mwe-your_recent_uploads' => 'Your recent uploads',
	'mwe-upload_a_file' => 'Upload a new file',
	'mwe-resource_page_desc' => 'Resource page description:',
	'mwe-edit_resource_desc' => 'Edit wiki text resource description:',
	'mwe-local_resource_title' => 'Local resource title:',
	'mwe-watch_this_page' => 'Watch this page',
	'mwe-do_import_resource' => 'Import resource',
	'mwe-update_preview' => 'Update preview',
	'mwe-cancel_import' => 'Cancel import',
	'mwe-importing_asset' => 'Importing asset',
	'mwe-preview_insert_resource' => 'Preview insert of resource: $1',

	/*
	 * js file: /libAddMedia/simpleUploadForm.js
	 */
	'mwe-select_file' => 'Select file',
	'mwe-more_licence_options' => 'For more licence options, view the <a href="$1">normal upload page</a>',
	'mwe-select_ownwork' => 'I am uploading entirely my own work, and licencing it under:',
	'mwe-licence_cc-by-sa' => 'Creative Commons Share Alike (3.0)',
	'mwe-upload' => 'Upload file',
	'mwe-destfilename' => 'Destination filename:',
	'mwe-summary' => 'Summary',
	'mwe-error_not_loggedin' => 'You do not appear to be logged in or do not have upload privileges.',

	/*
	 * js file: /libAddMedia/mvBaseUploadInterface.js
	 */
	'mwe-upload-transcode-in-progress' => 'Transcode and upload in progress (do not close this window)',
	'mwe-upload-in-progress' => 'Upload in progress (do not close this window)',
	'mwe-upload-transcoded-status' => 'Transcoded',
	'mwe-uploaded-status' => 'Uploaded',
	'mwe-upload-stats-fileprogres' => '$1 of $2',
	'mwe-upload_completed' => 'Your upload is complete',
	'mwe-upload_done' => '<a href="$1">Your upload <i>should be</i> accessible</a>.',
	'mwe-upload-unknown-size' => 'Unknown size',
	'mwe-cancel-confim' => 'Are you sure you want to cancel?',
	'mwe-successfulupload' => 'Upload successful',
	'mwe-uploaderror' => 'Upload error',
	'mwe-uploadwarning' => 'Upload warning',
	'mwe-unknown-error' => 'Unknown error:',
	'mwe-return-to-form' => 'Return to form',
	'mwe-file-exists-duplicate' => 'This file is a duplicate of the following file:',
	'mwe-fileexists' => 'A file with this name exists already. Please check <b><tt>$1</tt></b> if you are not sure if you want to change it.',
	'mwe-fileexists-thumb' => '<center><b>Existing file</b></center>',
	'mwe-ignorewarning' => 'Ignore warning and save file anyway',
	'mwe-file-thumbnail-no' => 'The filename begins with <b><tt>$1</tt></b>',
	'mwe-go-to-resource' => 'Go to resource page',
	'mwe-upload-misc-error' => 'Unknown upload error',
	'mwe-wgfogg_warning_bad_extension' => 'You have selected a file with an unsuported extension (<a href="http://commons.wikimedia.org/wiki/Commons:Firefogg#Supported_File_Types">more information</a>).',
	'mwe-cancel-button' => 'Cancel',
	'mwe-ok-button' => 'OK',

	/*
	 * js file: /libEmbedVideo/embedVideo.js
	 */
	'mwe-loading_plugin' => 'loading plugin <blink>...</blink>',
	'mwe-select_playback' => 'Set playback preference',
	'mwe-link_back' => 'Link back',
	'mwe-error_load_lib' => 'Error: mv_embed was unable to load required JavaScript libraries.
Insert script via DOM has failed. Please try reloading this page.',
	'mwe-error_swap_vid' => 'Error: mv_embed was unable to swap the video tag for the mv_embed interface',
	'mwe-add_to_end_of_sequence' => 'Add to end of sequence',
	'mwe-missing_video_stream' => 'The video file for this stream is missing',
	'mwe-play_clip' => 'Play clip',
	'mwe-pause_clip' => 'Pause clip',
	'mwe-volume_control' => 'Volume control',
	'mwe-player_options' => 'Player options',
	'mwe-closed_captions' => 'Close captions',
	'mwe-player_fullscreen' => 'Fullscreen',
	'mwe-next_clip_msg' => 'Play next clip',
	'mwe-prev_clip_msg' => 'Play previous clip',
	'mwe-current_clip_msg' => 'Continue playing this clip',
	'mwe-seek_to' => 'Seek to',
	'mwe-download_segment' => 'Download selection:',
	'mwe-download_full' => 'Download full video file:',
	'mwe-download_right_click' => 'To download, right click and select <i>Save target as...</i>',
	'mwe-download_clip' => 'Download video',
	'mwe-download_text' => 'Download text (<a style="color:white" title="cmml" href="http://wiki.xiph.org/index.php/CMML">CMML</a> xml):',
	'mwe-download' => 'Download',
	'mwe-share' => 'Share',
	'mwe-credits' => 'Credits',
	'mwe-clip_linkback' => 'Clip source page',
	'mwe-chose_player' => 'Choose Video Player',
	'mwe-share_this_video' => 'Share this video',
	'mwe-video_credits' => 'Video credits',
	'mwe-menu_btn' => 'Menu',
	'mwe-close_btn' => 'Close',
	'mwe-ogg-player-vlc-mozilla' => 'VLC plugin',
	'mwe-ogg-player-videoElement' => 'Native Ogg video support',
	'mwe-ogg-player-vlc-activex' => 'VLC ActiveX',
	'mwe-ogg-player-oggPlugin' => 'Generic Ogg plugin',
	'mwe-ogg-player-quicktime-mozilla' => 'Quicktime plugin',
	'mwe-ogg-player-quicktime-activex' => 'Quicktime ActiveX',
	'mwe-ogg-player-cortado' => 'Java Cortado',
	'mwe-ogg-player-flowplayer' => 'Flowplayer',
	'mwe-ogg-player-selected' => '(selected)',
	'mwe-ogg-player-omtkplayer' => 'OMTK Flash Vorbis',
	'mwe-generic_missing_plugin' => 'You browser does not appear to support the following playback type: <b>$1</b><br />Visit the <a href="http://commons.wikimedia.org/wiki/Commons:Media_help">Playback Methods</a> page to download a player.<br />',
	'mwe-for_best_experience' => 'For a better video playback experience we recommend:<br /><b><a href="http://www.mozilla.com/en-US/firefox/upgrade.html?from=mwEmbed">Firefox 3.5</a>.</b>',
	'mwe-do_not_warn_again' => 'Dissmiss for now.',
	'mwe-playerselect' => 'Players',
	'mwe-read_before_embed' => 'Please <a href="http://mediawiki.org/wiki/Security_Notes_on_Remote_Embedding" target="_new">Read This</a> before embeding!',
	'mwe-embed_site_or_blog' => 'Embed on your site or blog',
);
