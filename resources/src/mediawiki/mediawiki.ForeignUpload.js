( function ( mw, OO ) {
	/**
	 * @class mw.ForeignUpload
	 * @extends mw.Upload
	 *
	 * Used to represent an upload in progress on the frontend.
	 *
	 * Subclassed to upload to a foreign API, with no other goodies. Use
	 * this for a generic foreign image repository on your wiki farm.
	 *
	 * Note you can provide the {@link #targetHost targetHost} or not - if the first argument is
	 * an object, we assume you want the default, and treat it as apiconfig
	 * instead.
	 *
	 * @constructor
	 * @param {string} [targetHost="commons.wikimedia.org"] Used to set up the target
	 *     wiki. If not remote, this class behaves identically to mw.Upload (unless further subclassed)
	 * @param {Object} [apiconfig] Passed to the constructor of mw.ForeignApi or mw.Api, as needed.
	 */
	function ForeignUpload( targetHost, apiconfig ) {
		var api;

		if ( typeof targetHost === 'object' ) {
			// targetHost probably wasn't passed in, it must
			// be apiconfig
			apiconfig = targetHost;
		} else {
			// targetHost is a useful string, set it here
			this.targetHost = targetHost || this.targetHost;
		}

		if ( location.host !== this.targetHost ) {
			api = new mw.ForeignApi(
				location.protocol + '//' + this.targetHost + '/w/api.php',
				apiconfig
			);
		} else {
			// We'll ignore the CORS and centralauth stuff if we're on Commons already
			api = new mw.Api( apiconfig );
		}

		mw.Upload.call( this, api );
	}

	OO.inheritClass( ForeignUpload, mw.Upload );

	/**
	 * @property targetHost
	 * Used to specify the target repository of the upload.
	 *
	 * You could override this to point at something that isn't Commons,
	 * but be sure it has the correct templates and is CORS and CentralAuth
	 * ready.
	 */
	ForeignUpload.prototype.targetHost = 'commons.wikimedia.org';

	mw.ForeignUpload = ForeignUpload;
}( mediaWiki, OO ) );
