( function ( mw, OO, $ ) {
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
	 * @param {string} [target="local"] Used to set up the target
	 *     wiki. If not remote, this class behaves identically to mw.Upload (unless further subclassed)
	 *     Use the same names as set in $wgForeignFileRepos for this. Also,
	 *     make sure there is an entry in the $wgForeignUploadTargets array
	 *     set to "true" for this name.
	 * @param {Object} [apiconfig] Passed to the constructor of mw.ForeignApi or mw.Api, as needed.
	 */
	function ForeignUpload( target, apiconfig ) {
		var api, upload = this;

		if ( typeof target === 'object' ) {
			// target probably wasn't passed in, it must
			// be apiconfig
			apiconfig = target;
			target = undefined;
		}

		// Resolve defaults etc. - if target isn't passed in, we use
		// the default.
		this.target = target || this.target;

		// Now we have several different options.
		// If the local wiki is the target, then we can skip a bunch of steps
		// and just return an mw.Api object, because we don't need any special
		// configuration for that.
		// However, if the target is a remote wiki, we must check the API
		// to confirm that the target is one that this site is configured to
		// support.
		if ( this.target !== 'local' ) {
			api = new mw.Api();
			this.apiPromise = api.get( {
				action: 'query',
				meta: 'filerepoinfo',
				friprop: [ 'name', 'scriptDirUrl', 'canUpload' ]
			} ).then( function ( data ) {
				var i, repo,
					repos = data.query.repos;

				for ( i in repos ) {
					repo = repos[ i ];

					if ( repo.name === upload.target ) {
						// This is our target repo.
						if ( !repo.canUpload ) {
							// But it's not configured correctly.
							return $.Deferred().reject( 'repo-cannot-upload' );
						}

						return new mw.ForeignApi(
							repo.scriptDirUrl + '/api.php',
							apiconfig
						);
					}
				}
			} );
		} else {
			// We'll ignore the CORS and centralauth stuff if the target is
			// the local wiki.
			this.apiPromise = $.Deferred().resolve( new mw.Api( apiconfig ) );
		}

		// Build the upload object without an API - this class overrides the
		// actual API call methods to wait for the apiPromise to resolve
		// before continuing.
		mw.Upload.call( this, null );
	}

	OO.inheritClass( ForeignUpload, mw.Upload );

	/**
	 * @property targetHost
	 * Used to specify the target repository of the upload.
	 *
	 * If you set this to something that isn't 'local', you must be sure to
	 * add that target to $wgForeignUploadTargets in LocalSettings, and the
	 * repository must be set up to use CORS and CentralAuth.
	 */
	ForeignUpload.prototype.target = 'local';

	/**
	 * Override from mw.Upload to make sure the API info is found and allowed
	 */
	ForeignUpload.prototype.upload = function () {
		var upload = this;
		return this.apiPromise.then( function ( api ) {
			upload.api = api;
			mw.Upload.prototype.upload.call( upload );
		} );
	};

	/**
	 * Override from mw.Upload to make sure the API info is found and allowed
	 */
	ForeignUpload.prototype.uploadToStash = function () {
		var upload = this;
		return this.apiPromise.then( function ( api ) {
			upload.api = api;
			mw.Upload.prototype.uploadToStash.call( upload );
		} );
	};

	mw.ForeignUpload = ForeignUpload;
}( mediaWiki, OO, jQuery ) );
