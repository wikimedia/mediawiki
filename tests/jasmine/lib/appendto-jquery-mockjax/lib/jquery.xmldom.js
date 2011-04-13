/*!
 * jQuery xmlDOM Plugin v1.0
 * http://outwestmedia.com/jquery-plugins/xmldom/
 *
 * Released: 2009-04-06
 * Version: 1.0
 *
 * Copyright (c) 2009 Jonathan Sharp, Out West Media LLC.
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
 */
(function($) {
	// IE DOMParser wrapper
	if ( window['DOMParser'] == undefined && window.ActiveXObject ) {
		DOMParser = function() { };
		DOMParser.prototype.parseFromString = function( xmlString ) {
			var doc = new ActiveXObject('Microsoft.XMLDOM');
	        doc.async = 'false';
	        doc.loadXML( xmlString );
			return doc;
		};
	}
	
	$.xmlDOM = function(xml, onErrorFn) {
		try {
			var xmlDoc 	= ( new DOMParser() ).parseFromString( xml, 'text/xml' );
			if ( $.isXMLDoc( xmlDoc ) ) {
				var err = $('parsererror', xmlDoc);
				if ( err.length == 1 ) {
					throw('Error: ' + $(xmlDoc).text() );
				}
			} else {
				throw('Unable to parse XML');
			}
		} catch( e ) {
			var msg = ( e.name == undefined ? e : e.name + ': ' + e.message );
			if ( $.isFunction( onErrorFn ) ) {
				onErrorFn( msg );
			} else {
				$(document).trigger('xmlParseError', [ msg ]);
			}
			return $([]);
		}
		return $( xmlDoc );
	};
})(jQuery);