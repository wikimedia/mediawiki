/*
 * Make sure that the upload code loads before the booklet, since the upload code creates
 * mw.ForeignStructuredUpload and the booklet creates mw.ForeignStructuredUpload.BookletLayout,
 * requiring that the object mw.ForeignStructuredUpload be defined first.
 */
require( './ForeignStructuredUpload.js' );
require( './BookletLayout.js' );
