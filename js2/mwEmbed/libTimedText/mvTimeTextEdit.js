/*
 * JS2-style edit.js
 */

// Setup configuration vars (if not set already)
if( !mwAddMediaConfig )
  var mwAddMediaConfig = {};

var mvTimedTextEdit = {};

loadGM({
  "mwe-add-subs-file"      : "Add/Replace Subtitle",
  "mwe-add-subs-file-title": "Select Subtitle to upload",
  "mwe-error-only-srt"     : "Only srt files can be uploaded right now.",
  "mwe-watch-video"        : "Watch video",
  "mwe-select-other-language": "Select Other Language"
})


js2AddOnloadHook( function() {
  function getSubtitle(f) {
      var name = f.files[0].name;
      var srtData = f.files[0].getAsBinary();
      srtData = srtData.replace('\r\n', '\n');
      return srtData;
  }
  function getVideoTitle() {
    var videoTitle = wgPageName.split('.');
    videoTitle.pop();
    videoTitle.pop();
    videoTitle = videoTitle.join('.').replace('TimedText:', 'File:');
    return videoTitle;
  }

  var availableSubtitles = {};
  var langData = {};

  function addLanguageSelect(ttoolbar) {
    var apprefix = wgPageName.split('.');
    apprefix.pop();
    var currentLanguage = apprefix.pop();
    apprefix = apprefix.join('.');
    do_api_req({
      'data': {
        'meta' : 'siteinfo',
        'siprop' : 'languages'
      }
    }, function( langDataRaw ) {
      var lagRaw = langDataRaw.query.languages;
      for(var j in lagRaw){
        var code = lagRaw[j].code;
        var language = lagRaw[j]['*'];
        langData[ code ] = language;
      }
      do_api_req({
	        'data': {
  					'list' : 'allpages',
		        'apprefix' : apprefix.replace("TimedText:", ""),
  					'apnamespace' : 102
	        }
      }, function( subData ) {
	      for(var i in subData.query.allpages){
		      var subPage = subData.query.allpages[i];
		      var langKey = subPage.title.split('.');
		      var extension = langKey.pop();
		      langKey = langKey.pop();
		      availableSubtitles[langKey] = subPage.title;
        }
        var select = $j('<select>');
        var haveOtherLanguages = 0;
        for(var i in availableSubtitles){
          haveOtherLanguages++;
          var opt = $j('<option>')
              .html(langData[ i ] + '('+i+')')
              .click(function(i) {
                return function() {
                  document.location.href = wgArticlePath.replace('/$1', '?title=' + availableSubtitles[i] + '&action=edit');
                }}(i));
          if(currentLanguage == i) {
            opt.attr('selected', 'selcted');
          }
          select.append(opt);
        }
        if (haveOtherLanguages > 1) {
          ttoolbar.append(" " +gM('mwe-select-other-language')+": ");
          ttoolbar.append(select);
        }
      });
    });
  }

  function uploadSubtitles() {
    var languageSelect = '<select id="timed_text_language">';
    for(code in langData) {
      var language = langData[ code ];
      languageSelect += '<option value="'+code+'">';
      if (availableSubtitles[code]) {
        languageSelect += language+'('+code+') +';
      } else {
        languageSelect += language+'('+code+') -';
      }
      languageSelect += '</option>';
    }
    languageSelect += '/</select>';
    var cBtn = {};
    cBtn[ gM('mwe-cancel') ] = function(){
      $j(this).dialog('close');
    }
    cBtn[ gM('mwe-ok') ] = function(){
      var file = $j('#timed_text_file_upload');
      var langKey = file[0].files[0].name.split('.');
      var extension = langKey.pop();
      langKey = langKey.pop();
      var mimeTypes = {
          'srt': 'text/x-srt',
          'cmml': 'text/cmml'
      }
      if( !mimeTypes[ extension ] ){
        js_log('Error: unknown extension:'+ extension);
      }
      //get language from form
      langKey = $j('#timed_text_language').val();
      if(extension == "srt") {
        var srt = getSubtitle(file[0]);
        $j(this).html("saving...<br>"+mv_get_loading_img());
        $j('.ui-dialog-buttonpane').remove();
        var editToken = $j('input[name=wpEditToken]').val();
        var title = wgPageName.split('.');
        title.pop();
        title.pop();
        title = title.join('.') + '.' + langKey + '.srt';
        do_api_req({
          'data': {
            'action' : 'edit',
            'title' : title,
            'text' : srt,
            'token': editToken
          }
        }, function(dialog) {
            return function( result ) {
              document.location.href = wgArticlePath.replace('/$1', '?title=' + title + '&action=edit');
              $j(dialog).dialog('close');
           }}(this)
        );
      } else {
        $j(this).html(gM("mwe-error-only-srt"));
      }
    }
    $j.addDialog(gM("mwe-add-subs-file-title"),
       '<input type="file" id="timed_text_file_upload"></input><br />' + languageSelect,
       cBtn);
    $j('#timed_text_file_upload').change(function(ev) {
      var langKey = this.files[0].name.split('.');
      var extension = langKey.pop();
      langKey = langKey.pop();
      $j('#timed_text_language').val( langKey );
    });
  }
  $j('#toolbar').hide();
  var ttoolbar = $j('<div>');
  $j('#toolbar').after(ttoolbar);

  var button = $j('<button>');
  button.click(uploadSubtitles)
  button.text(gM("mwe-add-subs-file"));
  ttoolbar.append(button);
  ttoolbar.append(' ');

  var button = $j('<button>');
  button.click(function() { document.location.href = wgArticlePath.replace('$1', getVideoTitle()); })
  button.text(gM("mwe-watch-video"));
  ttoolbar.append(button);
  addLanguageSelect(ttoolbar);
});

