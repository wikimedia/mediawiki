/*!
 * mw.GallerySlider: Interface controls for the slider gallery
 */
( function ( mw, $, OO ) {

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

	mw.GallerySlider.prototype.setSizeRequirement = function () {
		this.imageWidth = $( '#content' ).width() / 2;
		this.imageHeight = $( window ).height() / 2;

		// Flush the cache, all the images we had are now useless
		this.imageUrlsCache = {};
	};

	mw.GallerySlider.prototype.showCurrentImage = function () {
		var imageLi = this.getCurrentImage(),
			image = imageLi.find( 'img' );

		// TODO: Show preloader
		this.$img.attr( 'src', image.attr( 'src' ) );
		this.loadImage( image ).done( function ( url ) {
			this.$img.attr( 'src', url );
		}.bind( this ) );
	};

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

	mw.GallerySlider.prototype.toggleThumbnails = function ( show ) {
		this.$gallery.find( '.gallerybox' ).toggle( show );
	};

	mw.GallerySlider.prototype.getCurrentImage = function () {
		this.currentImage = this.currentImage || this.$gallery.find( '.gallerybox' ).eq( 0 );
		return this.currentImage;
	};

	mw.GallerySlider.prototype.getNextImage = function () {
		if ( this.currentImage.next( '.gallerybox' )[ 0 ] !== undefined ) { // Not the last image in the gallery
			return this.currentImage.next( '.gallerybox' );
		} else {
			return this.$gallery.find( '.gallerybox' ).eq( 0 );
		}
	};

	mw.GallerySlider.prototype.getPrevImage = function () {
		if ( this.currentImage.prev( '.gallerybox' )[ 0 ] !== undefined ) { // Not the last image in the gallery
			return this.currentImage.prev( '.gallerybox' );
		} else {
			return this.$gallery.find( '.gallerybox' ).last();
		}
	};

	mw.GallerySlider.prototype.nextImage = function () {
		this.currentImage = this.getNextImage();
		this.showCurrentImage();
	};

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
