// HACK: OO.ui.infuse assumes all widgets are in the OO.ui. namespace.
// Make it so until this is fixed. (T104989)
jQuery.extend( OO.ui, mediaWiki.widgets );
