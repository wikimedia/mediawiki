/* the upload javascript 
presently does hackery to work with Special:Upload page...
will be replaced with upload API once that is ready
*/

loadGM({ 
    "upload-enable-converter" : "Enable video converter (to upload source video not yet converted to theora format) <a href=\"http://commons.wikimedia.org/wiki/Commons:Firefogg\">more info</a>",
    "upload-fogg_not_installed": "If you want to upload video consider installing <a href=\"http://firefogg.org\">firefogg.org</a>, <a href=\"http://commons.wikimedia.org/wiki/Commons:Firefogg\">more info</a>",
    "upload-transcode-in-progress":"Doing Transcode & Upload (do not close this window)",
    "upload-in-progress": "Upload in Progress (do not close this window)",
    "upload-transcoded-status": "Transcoded",
    "uploaded-status": "Uploaded",
    "upload-select-file": "Select File...",    
    "wgfogg_wrong_version": "You have firefogg installed but its outdated, <a href=\"http://firefogg.org\">please upgrade</a> ",
    "wgfogg_waring_ogg_upload": "You have selected an ogg file for conversion to ogg (this is probably unnessesary). Maybe disable the video converter?",
    "wgfogg_waring_bad_extension" : "You have selected a file with an unsuported extension. <a href=\"http://commons.wikimedia.org/wiki/Commons:Firefogg#Supported_File_Types\">More help</a>",
    "upload-stats-fileprogres": "$1 of $2",
    
    "mv_upload_done"       : "Your upload <i>should be</i> accessible <a href=\"$1\">here</a>",
    "upload-unknown-size": "Unknown size",    
    
    "successfulupload" : "Successful upload",
    "uploaderror" : "Upload error",
    "uploadwarning": "Upload warning",
    "unknown-error": "Unknown Error",
    "return-to-form": "Return to form",
    
    "file-exists-duplicate" : "This file is a duplicate of the following file",
    "fileexists" : "A file with this name exists already, please check <b><tt>$1</tt></b> if you are not sure if you want to change it.",
    "fileexists-thumb": "<center><b>Existing file</b></center>",
    "ignorewarning" : "Ignore warning and save file anyway",
    "file-thumbnail-no" :  "The filename begins with <b><tt>$1</tt></b>"    
});

var default_upload_options = {
    'target_div':'',
    'upload_done_action':'redirect',
    'api_url':false
}

var mediaWikiUploadHelper = function(iObj){
    return this.init( iObj );
}
mediaWikiUploadHelper.prototype = {
    init:function( iObj ){
        var _this = this;    
        js_log('init uploader');
        if(!iObj)
            iObj = {};
        for(var i in default_upload_options){
            if(iObj[i]){
                this[i] = iObj[i];
            }else{
                this[i] = default_upload_options[i];
            }
        }
        //check if we are on the uplaod page: 
        this.on_upload_page = ( wgPageName== "Special:Upload")?true:false;                    
        js_log('f:mvUploader: onuppage:' + this.on_upload_page);
        //grab firefogg.js: 
        mvJsLoader.doLoad([
                'mvFirefogg'
            ],function(){
                //if we are not on the upload page grab the upload html via ajax:
                //@@todo refactor with         
                if( !_this.on_upload_page){                    
                    $j.get(wgArticlePath.replace(/\$1/, 'Special:Upload'), {}, function(data){
                        //add upload.js: 
                        $j.getScript( stylepath + '/common/upload.js', function(){     
                            //really _really_ need an "upload api"!
                            wgAjaxUploadDestCheck = true;
                            wgAjaxLicensePreview = false;
                            wgUploadAutoFill = true;                                    
                            //strip out inline scripts:
                            sp = data.indexOf('<div id="content">');
                            se = data.indexOf('<!-- end content -->');    
                            if(sp!=-1 && se !=-1){        
                                result_data = data.substr(sp, (se-sp) ).replace('/\<script\s.*?\<\/script\>/gi',' ');
                                js_log("trying to set: " + result_data );                                                                            
                                //$j('#'+_this.target_div).html( result_data );
                            }                        
                            _this.setupFirefogg();
                        });    
                    });                
                }else{
                    //@@could check if firefogg is enabled here: 
                    _this.setupFirefogg();            
                    //if only want httpUploadFrom help enable it here:         
                }                            
            }
        );
    },
    /**
     * setupBaseUpInterface supports intefaces for progress indication if the browser supports it
     * also sets up ajax progress updates for http posts
     * //pre
     */     
    setupBaseUpInterface:function(){    
        //check if this feature is not false (we want it on by default (null) instances that don't have the upload api or any modifications)              
        this.upForm = new mvBaseUploadInterface( {
                'api_url' : this.api_url,
                'parent_uploader': this
            } 
        );        
        this.upForm.setupForm();        
    },
    setupFirefogg:function(){
        var _this = this;
        //add firefogg html if not already there: ( same as $wgEnableFirebug added in SpecialUpload.php )  
        if( $j('#fogg-video-file').length==0 ){
            js_log('add addFirefoggHtml');
            _this.addFirefoggHtml();
        }else{
            js_log('firefogg already init:');                    
        }    
        //set up the upload_done action 
        //redirect if we are on the upload page  
        //do a callback if in called from gui) 
        var intFirefoggObj = ( this.on_upload_page )? 
                {'upload_done_action':'redirect'}:
                {'upload_done_action':function( rTitle ){
                        js_log( 'add_done_action callback for uploader' );
                        //call the parent insert resource preview    
                        _this.upload_done_action( rTitle );        
                    }
                };
                
        if( _this.api_url )
            intFirefoggObj['api_url'] =  _this.api_url;
        
        js_log('new mvFirefogg  extends mvUploader (this)');        
        this.fogg = new mvFirefogg( intFirefoggObj );        
        this.fogg.setupForm();                    
    },
    //same add code as specialUpload if($wgEnableFirefogg){
    addFirefoggHtml:function(){        
        var itd_html = $j('#mw-upload-table .mw-input:first').html();            
        $j('#mw-upload-table .mw-input').eq(0).html('<div id="wg-base-upload">' + itd_html + '</div>');
        //add in firefogg control            
        $j('#wg-base-upload').after('<p id="fogg-enable-item" >' + 
            '<input style="display:none" id="fogg-video-file" name="fogg-video-file" type="button" value="' + gM('upload-select-file') + '">' +
            "<span id='wgfogg_not_installed'>" + 
                gM('upload-fogg_not_installed') +
            "</span>" +
            "<span class='error' id='wgfogg_wrong_version'  style='display:none;'><br>" +
                gM('wgfogg_wrong_version') +
            "<br>" +
            "</span>" +
            "<span class='error' id='wgfogg_waring_ogg_upload' style='display:none;'><br>"+
                gM('wgfogg_waring_ogg_upload') +
            "<br>" +
            "</span>" + 
            "<span class='error' id='wgfogg_waring_bad_extension' style='display:none;'><br>"+
                gM('wgfogg_waring_bad_extension') +                         
            "<br>" +
            "</span>" +  
            "<span id='wgfogg_installed' style='display:none' >"+
                '<input id="wgEnableFirefogg" type="checkbox" name="wgEnableFirefogg" >' +                             
                    gM('upload-enable-converter') +
            '</span><br></p>');                    
    },

};
