/*
 * simple form output jquery binding
 * enables dynamic form output to a given target
 * 
 */

loadGM({ 
	"select_file" 			: "Select File",
	"more_licence_options" 	: "For more licence options view the <a href=\"$1\">normal upload page</a>",
	"select_ownwork"		: "I am uploading entirely my own work, and licencing it under:",
	"licence_cc-by-sa"		: "Creative Commons Share Alike (3.0)",		
	"upload"				: "Upload File",
	"destfilename"			: "Destination filename:",
	"summary"				: "Summary",
	"error_not_loggedin"	: "You don't appear to be logged in or don't have upload privlages."
});
 
var default_form_options = {
	'enable_fogg'	 : true,
	'licence_options':['cc-by-sa'],
	'api_target' : false,
	'ondone_cb' : null
};

(function($) {
	$.fn.simpleUploadForm = function( opt , callback){
		//set the options: 
		for(var i in default_form_options){
			if(!opt[i])
				opt[i] = default_form_options[i];
		}
		
		//first do a reality check on the options: 
		if(!opt.api_target){
			$(this.selector).html('Error: Missing api target');
			return false;
		}		
					
		//@@todo this is just a proof of concept
		//much todo to improved this web form
		get_mw_token('File:MyRandomFileTokenCheck', opt.api_target, function(eToken){		
			debugger;	
			if( !eToken || eToken == '+\\' ){
				$(this.selector).html( gM('error_not_loggedin') );
				return false;
			}
			
			var o = '<div style="margin: 0 auto;width:450px;">'+
				'<form id="suf-upload" enctype="multipart/form-data" action="" method="post">'  +	
				'<label for="wpUploadFile">' + gM('select_file') + '</label><br>'+									
				'<input type="file" style="display: inline;" name="wpUploadFile" id="wpUploadFile" size="10"/><br>' +		
				'<label for="wpDestFile">' +gM('destfilename') + '</label><br>'+
					'<input type="text" id="wpDestFile" name="wpDestFile" size="30" /><br>'+
				'<label for="wpUploadDescription">' + gM('summary') + ':</label><br>' +
					'<textarea cols="30" rows="3" id="wpUploadDescription" name="wpUploadDescription" tabindex="3"/><br>'+ 
					gM('select_ownwork') + '<br>' + 
				'<input type="checkbox" id="wpLicence" name="wpLicence" value="cc-by-sa">' + gM('licence_cc-by-sa') + '<br>' +			
							
				'<input type="submit" accesskey="s" value="' + gM('upload') + '" name="wpUploadBtn" id="wpUploadBtn"  tabindex="9"/>' +
				//close the form and div  
				'</form></div>';		
				
			//set the target with the form output:
			$( this.selector ).html( o );			
			//by default dissable: 
			$j('#wpUploadBtn').attr('disabled', 'disabled');
			
			//set up basic binding:
			$j('#wpLicence').click(function(){
				if( $j(this).is(':checked') ){
					$j('#wpUploadBtn').removeAttr('disabled');				
				}else{				
					$j('#wpUploadBtn').attr('disabled', 'disabled');
				} 			
			});
			
			if(typeof opt.ondone_cb == 'undefined')
				opt.ondone_cb = false;
				
			//set up the binding per the config
			if( opt.enable_fogg ){
				$j('#wpUploadFile').firefogg({ 
					//an api url (we won't submit directly to action of the form)
					'api_url' : opt.api_target,
					'form_rewrite': true,	
					'target_edit_from' : '#suf-upload',			
					'new_source_cb' : function( orgFilename, oggName ){										        
							$j('#wpDestFile').val( oggName );
							//@@TODO: 
							//mwUploadHelper.doDestCheck();
					},
					'done_upload_cb' : opt.ondone_cb
				});		
			}else{
				//simple web form rewrite		
			}
		});			
	}
})(jQuery);