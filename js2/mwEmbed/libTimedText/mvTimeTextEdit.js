/*
 * JS2-style mvTimedTextEdit.js
 */

// Setup configuration vars (if not set already)
if ( !mwAddMediaConfig )
  var mwAddMediaConfig = { };

var mvTimeTextEdit = { };

loadGM( {
  "mwe-upload-subs-file" : "Upload subtitle",
  "mwe-add-subs-file-title" : "Select subtitle to upload",
  "mwe-error-only-srt" : "You can only upload srt files.",
  "mwe-watch-video" : "Watch video",
  "mwe-select-other-language" : "Select another language",
  "mwe-saving" : "saving..."
} )


js2AddOnloadHook( function() {
  function getSubtitle( f ) {
      var name = f.files[0].name;
      var srtData = f.files[0].getAsBinary();
      srtData = srtData.replace( '\r\n', '\n' );
      return srtData;
  }
  function getVideoTitle() {
    var videoTitle = wgPageName.split( '.' );
    videoTitle.pop();
    videoTitle.pop();
    videoTitle = videoTitle.join( '.' ).replace( 'TimedText:', 'File:' );
    return videoTitle;
  }
  function uploadSubtitles() {
    do_api_req( {
      'data': {
        'meta' : 'siteinfo',
        'siprop' : 'languages'
      }
      }, function( langDataRaw ) {
        var apprefix = wgTitle.split( '.' );
        apprefix.pop();
        apprefix.pop();
        apprefix = apprefix.join( '.' );
			  do_api_req( {
					  'data': {
						  'list' : 'allpages',
						  'apprefix' : apprefix
					  }
			  }, function( subData ) {
			    var availableSubtitles = { };
					for ( var i in subData.query.allpages ) {
						var subPage = subData.query.allpages[i];
						var langKey = subPage.title.split( '.' );
						var extension = langKey.pop();
						langKey = langKey.pop();
						availableSubtitles[langKey] = subPage.title;
          }
          var langData = { };
          var languageSelect = '<select id="timed_text_language">';

          var lagRaw = langDataRaw.query.languages;
          for ( var j in lagRaw ) {
            var code = lagRaw[j].code;
            var language = lagRaw[j]['*'];
            langData[ code ] = language;
            languageSelect += '<option value="' + code + '">';
            if ( availableSubtitles[code] ) {
              languageSelect += language + '(' + code + ') +';
            } else {
              languageSelect += language + '(' + code + ') -';
            }
            languageSelect += '</option>';
          }
          languageSelect += '/</select>';
          var cBtn = { };
          cBtn[ gM( 'mwe-cancel' ) ] = function() {
            $j( this ).dialog( 'close' );
          }
          cBtn[ gM( 'mwe-ok' ) ] = function() {
          	// get language from form
            langKey = $j( '#timed_text_language' ).val();
            var title = wgPageName.split( '.' );
            title.pop();
            title.pop();
            title = title.join( '.' ) + '.' + langKey + '.srt';
            
            var file = $j( '#timed_text_file_upload' );
            if ( !file[0].files[0] ) {
            	// no file to upload just jump to the lang key: 
            	document.location.href = wgArticlePath.replace( '/$1', '?title=' + title + '&action=edit' );
            	return ;
            }
            var langKey = file[0].files[0].name.split( '.' );
            var extension = langKey.pop();
            langKey = langKey.pop();
            var mimeTypes = {
                'srt': 'text/x-srt',
                'cmml': 'text/cmml'
            }
            if ( !mimeTypes[ extension ] ) {
              js_log( 'Error: unknown extension:' + extension );
            }
            

            if ( extension == "srt" ) {
              var srt = getSubtitle( file[0] );
              $j( this ).text( gM( 'mwe-saving' ) );
              $j( '.ui-dialog-buttonpane' ).remove();

              var editToken = $j( 'input[name=wpEditToken]' ).val();
            
              do_api_req( {
                'data': {
                  'action' : 'edit',
                  'title' : title,
                  'text' : srt,
                  'token': editToken
                }
              }, function( dialog ) {
                  return function( result ) {
                    document.location.href = wgArticlePath.replace( '/$1', '?title=' + title + '&action=edit' );
                    $j( dialog ).dialog( 'close' );
                 } }( this )
              );
            } else {
              $j( this ).html( gM( "mwe-error-only-srt" ) );
            }
          }
          $j.addDialog( gM( "mwe-add-subs-file-title" ),
             '<input type="file" id="timed_text_file_upload"></input><br />' + languageSelect,
             cBtn );
          $j( '#timed_text_file_upload' ).change( function( ev ) {
          	if ( this.files[0] ) {
	            var langKey = this.files[0].name.split( '.' );
	            var extension = langKey.pop();
	            langKey = langKey.pop();
	            $j( '#timed_text_language' ).val( langKey );
            }
          } );
      } );
    } );
  }
  var tselect = ( $j( '#wikiEditor-ui-top' ).length != 0 ) ? '#wikiEditor-ui-top':'#toolbar';
  $j( tselect ).hide();
  var ttoolbar = $j( '<div>' );
  $j( tselect ).after( ttoolbar );

  var button = $j( '<button>' );
	  button.click( uploadSubtitles )
	  button.text( gM( "mwe-upload-subs-file" ) );
	  ttoolbar.append( button );
  ttoolbar.append( ' ' );

  var button = $j( '<button>' );
	  button.click( function() { document.location.href = wgArticlePath.replace( '$1', getVideoTitle() ); } )
	  button.text( gM( "mwe-watch-video" ) );
  ttoolbar.append( button );

} );

