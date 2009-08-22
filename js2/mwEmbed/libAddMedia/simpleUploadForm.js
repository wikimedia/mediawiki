/*
 * simple form output jquery binding
 * enables dynamic form output to a given target
 *
 */

loadGM({
	"mwe-select_file" : "Select file",
	"mwe-more_licence_options" : "For more licence options, view the <a href=\"$1\">normal upload page<\/a>",
	"mwe-select_ownwork" : "I am uploading entirely my own work, and licencing it under:",
	"mwe-licence_cc-by-sa" : "Creative Commons Share Alike (3.0)",
	"mwe-upload" : "Upload file",
	"mwe-destfilename" : "Destination filename:",
	"mwe-summary" : "Summary",
	"mwe-error_not_loggedin" : "You do not appear to be logged in or do not have upload privileges."
});

var default_form_options = {
	'enable_fogg'	 : true,
	'licence_options':['cc-by-sa'],
	'api_target' : false,
	'ondone_cb' : null
};

(function($) {
	$.fn.simpleUploadForm = function( opt , callback){
		var _this = this;
		//set the options:
		for(var i in default_form_options){
			if(!opt[i])
				opt[i] = default_form_options[i];
		}

		//first do a reality check on the options:
		if( !opt.api_target ){
			$(this.selector).html('Error: Missing api target');
			return false;
		}

		//@@todo this is just a proof of concept
		//much todo to improved this web form
		get_mw_token('File:MyRandomFileTokenCheck', opt.api_target, function(eToken){
			if( !eToken || eToken == '+\\' ){
				$(this.selector).html( gM('mwe-error_not_loggedin') );
				return false;
			}

			//build an upload form:
			var o = ''+
				'<form id="suf-upload" enctype="multipart/form-data" action="' + opt.api_target + '" method="post">'  +
				//hidden input:
				'<input type="hidden" name="action" value="upload">'+
				'<input type="hidden" name="format" value="jsonfm">'+
				'<input type="hidden" name="token" value="'+ eToken +'">' +

				//form name set:
				'<label for="wpUploadFile">' + gM('mwe-select_file') + '</label><br>'+
				'<input type="file" style="display: inline;" name="wpUploadFile" size="15"/><br>' +

				'<label for="wpDestFile">' +gM('mwe-destfilename') + '</label><br>'+
				'<input type="text" name="wpDestFile" size="30" /><br>'+

				'<label for="wpUploadDescription">' + gM('mwe-summary') + ':</label><br>' +
				'<textarea cols="30" rows="3" name="wpUploadDescription" tabindex="3"/><br>'+

				'<div id="wpDestFile-warning"></div>' +
				'<div style="clear:both;"></div>' +

				gM('mwe-select_ownwork') + '<br>' +
				'<input type="checkbox" id="wpLicence" name="wpLicence" value="cc-by-sa">' + gM('mwe-licence_cc-by-sa') + '<br>' +

				'<input type="submit" accesskey="s" value="' + gM('mwe-upload') + '" name="wpUploadBtn" id="wpUploadBtn"  tabindex="9"/>' +
				//close the form and div
				'</form>';

			//set the target with the form output:
			$( _this.selector ).html( o );
			//by default dissable:
			$j('#wpUploadBtn').attr('disabled', 'disabled');

			//set up basic licence binding:
			$j('#wpLicence').click(function(){
				if( $j(this).is(':checked') ){
					$j('#wpUploadBtn').removeAttr('disabled');
				}else{
					$j('#wpUploadBtn').attr('disabled', 'disabled');
				}
			});
			//do destination fill:
			//@@should integrate with doDestinationFill on upload page
			$j("[name='wpUploadFile']").change(function(){
				var path = $j(this).val();
				// Find trailing part
				var slash = path.lastIndexOf('/');
				var backslash = path.lastIndexOf('\\');
				var fname;
				if (slash == -1 && backslash == -1) {
					fname = path;
				} else if (slash > backslash) {
					fname = path.substring(slash+1, 10000);
				} else {
					fname = path.substring(backslash+1, 10000);
				}
				fname = fname.charAt(0).toUpperCase().concat(fname.substring(1,10000)).replace(/ /g, '_');
				// Output result
				$j("[name='wpDestFile']").val( fname );
				//do destination check
				$j("[name='wpDestFile']").doDestCheck({
					'warn_target':'#wpDestFile-warning'
				});
			});


			//do destination check:
			$j("[name='wpDestFile']").change(function(){
				$j(this).doDestCheck({
					'warn_target':'#wpDestFile-warning'
				});
			});

			if(typeof opt.ondone_cb == 'undefined')
				opt.ondone_cb = false;

			//set up the binding per the config
			if( opt.enable_fogg ){
				$j("#suf-upload [name='wpUploadFile']").firefogg({
					//an api url (we won't submit directly to action of the form)
					'api_url' : opt.api_target,
					'form_rewrite': true,
					'target_edit_from' : '#suf-upload',
					'new_source_cb' : function( orgFilename, oggName ){
						$j("#suf-upload [name='wpDestFile']").val( oggName ).doDestCheck({
							warn_target: "#wpDestFile-warning"
						});
					},
					'done_upload_cb' : opt.ondone_cb
				});
			}
		});
	}
})(jQuery);
