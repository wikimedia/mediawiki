/*!
 * mw.GallerySlider: Interface controls for the slider gallery
 */
( function ( mw, $, OO ) {
	/**
	 * mw.GallerySlider encapsulates the user interface of the slider
	 * galleries. An object is instantiated for each `.mw-gallery-slider`
	 * element.
	 *
	 * @class mw.GallerySlider
	 * @uses mw.Title
	 * @uses mw.Api
	 * @param gallery The `<ul>` element of the gallery
	 */
	mw.GallerySlider = function ( gallery ) {
		// TODO: document these properties
		this.$gallery = $( gallery );
		this.currentImage = null; // TODO: Rename this to $currentImage
		this.imageUrlsCache = {};

		this.setSizeRequirement();
		this.drawCarousel();
		this.toggleThumbnails( false );
		this.showCurrentImage();

		$( window ).on( 'resize', this.setSizeRequirement.bind( this ) ); // TODO: Debounce this!
	};

	/* Setup */
	OO.initClass( mw.GallerySlider );

	/* Methods */

	/**
	 * Draws the carousel and the interface around it.
	 */
	mw.GallerySlider.prototype.drawCarousel = function () {
		var $carousel, $next, $prev, $toggle;

		$carousel = $( '<li>' )
			.addClass( 'gallerycarousel' );

		$prev = $( '<a>' )
			.text( 'prev' )
			.on( 'click', this.prevImage.bind( this ) )
			.appendTo( $carousel );

		$next = $( '<a>' )
			.text( 'next' )
			.on( 'click', this.nextImage.bind( this ) )
			.appendTo( $carousel );

		$toggle = $( '<a>' )
			.text( 'toggle' )
			.on( 'click', this.toggleThumbnails.bind( this ) )
			.appendTo( $carousel );


		this.$img = $( '<img>' ).appendTo( $carousel ); // TODO: This needs to be a link to the image
		this.$text = $( '<p>' ).appendTo( $carousel );

		this.$gallery.find( '.gallerycaption' ).after( $carousel );
	};

	/**
	 * Sets the {@link imageWidth} and {@link imageHeight} properties
	 * based on the size of the window. Also flushes the
	 * {@link imageUrlsCache} as we'll now need URLs for a different
	 * size.
	 */
	mw.GallerySlider.prototype.setSizeRequirement = function () {
		this.imageWidth = $( '#content' ).width() / 2;
		this.imageHeight = $( window ).height() / 2;
		this.imageUrlsCache = {};
	};

	/**
	 * Displays the image set as {@link currentImage} in the carousel.
	 */
	mw.GallerySlider.prototype.showCurrentImage = function () {
		var imageLi = this.getCurrentImage(),
			image = imageLi.find( 'img' );

		// TODO: Show preloader
		this.$img.attr( 'src', image.attr( 'src' ) );
		this.loadImage( image ).done( function ( url ) {
			this.$img.attr( 'src', url );
		}.bind( this ) );
	};

	/**
	 * Loads the full image  given the `<img>` element of the thumbnail.
	 *
	 * @param {Object} $img
	 * @return {jQuery.Promise} Resolves with the images URL once the image has loaded.
	 */
	mw.GallerySlider.prototype.loadImage = function ( $img ) {
		var img, d = $.Deferred();

		this.getImageUrl( $img ).done( function ( url ) {
			img = new Image();
			img.src = url;
			img.onload = function () {
				d.resolve( url );
			};
			img.onerror = function () {
				d.reject();
			};
		} ).fail( function () {
			d.reject();
		} );

		return d.promise();
	};

	/**
	 * Gets the image's URL given an `<img>` element.
	 *
	 * @param {Object} $img
	 * @return {jQuery.Promise} Resolves with the images URL.
	 */
	mw.GallerySlider.prototype.getImageUrl = function ( $img ) {
		var api, title,
			imageSrc = $img.attr( 'src' ),
			d = $.Deferred();

		if ( this.imageUrlsCache[ imageSrc ] !== undefined ) {
			d.resolve( this.imageUrlsCache[ imageSrc ] );
		} else {
			api = new mw.Api();
			title = new mw.Title.newFromImg( $img ); // TODO: This supports only gallery of images

			api.get( {
				action: 'query',
				formatversion: 2,
				titles: title.toString(),
				prop: 'imageinfo',
				iiurlwidth: this.imageWidth,
				iiurlheight: this.imageHeight,
				iiprop: 'url'
			} ).done( function ( data ) {
				// TODO: There must be a better way to do this (try…catch?)
				if (
					data.query &&
					data.query.pages[ 0 ] &&
					data.query.pages[ 0 ].imageinfo[ 0 ] &&
					data.query.pages[ 0 ].imageinfo[ 0 ].thumburl
				) {
					this.imageUrlsCache[ imageSrc ] = data.query.pages[ 0 ].imageinfo[ 0 ].thumburl;
					d.resolve( this.imageUrlsCache[ imageSrc ] );
				} else {
					d.reject();
				}
			}.bind( this ) ).fail( function () {
				d.reject();
			} );
		}

		return d.promise();
	};

	/**
	 * Toggles visibility of the thumbnails.
	 *
	 * @param {boolean} show Optional argument to control the state
	 */
	mw.GallerySlider.prototype.toggleThumbnails = function ( show ) {
		this.$gallery.find( '.gallerybox' ).toggle( show );
	};

	/**
	 * Getter method for {@link #currentImage}
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlider.prototype.getCurrentImage = function () {
		this.currentImage = this.currentImage || this.$gallery.find( '.gallerybox' ).eq( 0 );
		return this.currentImage;
	};

	/**
	 * Gets the image after the current one. Returns the first image if
	 * the current one is the last.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlider.prototype.getNextImage = function () {
		if ( this.currentImage.next( '.gallerybox' )[ 0 ] !== undefined ) { // Not the last image in the gallery
			return this.currentImage.next( '.gallerybox' );
		} else {
			return this.$gallery.find( '.gallerybox' ).eq( 0 );
		}
	};

	/**
	 * Gets the image before the current one. Returns the last image if
	 * the current one is the first.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlider.prototype.getPrevImage = function () {
		if ( this.currentImage.prev( '.gallerybox' )[ 0 ] !== undefined ) { // Not the first image in the gallery
			return this.currentImage.prev( '.gallerybox' );
		} else {
			return this.$gallery.find( '.gallerybox' ).last();
		}
	};

	/**
	 * Sets the {@link currentImage} to the next one and shows
	 * it in the carousel
	 */
	mw.GallerySlider.prototype.nextImage = function () {
		this.currentImage = this.getNextImage();
		this.showCurrentImage();
	};

	/**
	 * Sets the {@link currentImage} to the previous one and shows
	 * it in the carousel
	 */
	mw.GallerySlider.prototype.prevImage = function () {
		this.currentImage = this.getPrevImage();
		this.showCurrentImage();
	};

	// Bootstrap all slider galleries
	$( function () {
		$( '.mw-gallery-slider' ).each( function () {
			var g = new mw.GallerySlider( this );
		} );
	} );
}( mediaWiki, jQuery, OO ) );
