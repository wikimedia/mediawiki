/*!
 * mw.GallerySlideshow: Interface controls for the slideshow gallery
 */
( function () {
	/**
	 * mw.GallerySlideshow encapsulates the user interface of the slideshow
	 * galleries. An object is instantiated for each `.mw-gallery-slideshow`
	 * element.
	 *
	 * @class mw.GallerySlideshow
	 * @uses mw.Title
	 * @uses mw.Api
	 * @param {jQuery} gallery The `<ul>` element of the gallery.
	 */
	mw.GallerySlideshow = function ( gallery ) {
		// Properties
		this.$gallery = $( gallery );
		this.$galleryCaption = this.$gallery.find( '.gallerycaption' );
		this.$galleryBox = this.$gallery.find( '.gallerybox' );
		this.$currentImage = null;
		this.imageInfoCache = {};

		// Initialize
		this.drawCarousel();
		this.toggleThumbnails( !!this.$gallery.attr( 'data-showthumbnails' ) );
		this.showCurrentImage( true );

		// Disable thumbnails' link, instead show the image in the carousel
		this.$galleryBox.on( 'click', function ( e ) {
			this.$currentImage = $( e.currentTarget );
			this.showCurrentImage();
			return false;
		}.bind( this ) );
		this.$gallery.addClass( 'mw-gallery-slideshow-ooui' );
	};

	/* Properties */
	/**
	 * @property {jQuery} $gallery The `<ul>` element of the gallery.
	 */

	/**
	 * @property {jQuery} $galleryCaption The `<li>` that has the gallery caption.
	 */

	/**
	 * @property {jQuery} $galleryBox Selection of `<li>` elements that have thumbnails.
	 */

	/**
	 * @property {jQuery} $carousel The `<li>` elements that contains the carousel.
	 */

	/**
	 * @property {jQuery} $interface The `<div>` elements that contains the interface buttons.
	 */

	/**
	 * @property {jQuery} $img The `<img>` element that'll display the current image.
	 */

	/**
	 * @property {jQuery} $imgCaption The `<p>` element that holds the image caption.
	 */

	/**
	 * @property {jQuery} $imgContainer The `<div>` element that contains the image.
	 */

	/**
	 * @property {jQuery} $currentImage The `<li>` element of the current image.
	 */

	/**
	 * @property {Object} imageInfoCache A key value pair of thumbnail URLs and image info.
	 */

	/**
	 * @property {number} imageHeight Height of the image based on viewport size
	 *   the URLs in the required size.
	 */
	/* Setup */
	OO.initClass( mw.GallerySlideshow );

	/* Methods */
	/**
	 * Draws the carousel and the interface around it.
	 */
	mw.GallerySlideshow.prototype.drawCarousel = function () {
		var nextButton, prevButton, toggleButton, interfaceElements, carouselStack;

		this.$carousel = $( '<li>' ).addClass( 'gallerycarousel' );

		// Buttons for the interface
		prevButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'previous'
		} ).connect( this, { click: 'prevImage' } );

		nextButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'next'
		} ).connect( this, { click: 'nextImage' } );

		toggleButton = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'imageGallery',
			title: mw.msg( 'gallery-slideshow-toggle' )
		} ).connect( this, { click: 'toggleThumbnails' } );

		interfaceElements = new OO.ui.PanelLayout( {
			expanded: false,
			classes: [ 'mw-gallery-slideshow-buttons' ],
			$content: $( '<div>' ).append(
				prevButton.$element,
				toggleButton.$element,
				nextButton.$element
			)
		} );
		this.$interface = interfaceElements.$element;

		// Containers for the current image, caption etc.
		this.$imgCaption = $( '<p>' ).attr( 'class', 'mw-gallery-slideshow-caption' );
		this.$imgContainer = $( '<div>' )
			.attr( 'class', 'mw-gallery-slideshow-img-container' );

		carouselStack = new OO.ui.StackLayout( {
			continuous: true,
			expanded: false,
			items: [
				interfaceElements,
				new OO.ui.PanelLayout( {
					expanded: false,
					$content: this.$imgContainer
				} ),
				new OO.ui.PanelLayout( {
					expanded: false,
					$content: this.$imgCaption
				} )
			]
		} );
		this.$carousel.append( carouselStack.$element );

		// Append below the caption or as the first element in the gallery
		if ( this.$galleryCaption.length !== 0 ) {
			this.$galleryCaption.after( this.$carousel );
		} else {
			this.$gallery.prepend( this.$carousel );
		}
	};

	/**
	 * Gets the height of the interface elements and the
	 * gallery's caption.
	 *
	 * @return {number} Height
	 */
	mw.GallerySlideshow.prototype.getChromeHeight = function () {
		return this.$interface.outerHeight() + ( this.$galleryCaption.outerHeight() || 0 );
	};

	/**
	 * Sets the height and width of {@link #$img} based on the
	 * proportion of the image and the values generated in the constructor.
	 */
	mw.GallerySlideshow.prototype.setImageSize = function () {
		if ( this.$img === undefined || this.$thumbnail === undefined ) {
			return;
		}

		// Reset height and width
		this.$img
			.removeAttr( 'width' )
			.removeAttr( 'height' );

		// Stretch image to take up the required size
		var imageHeight = this.imageHeight - this.$imgCaption.outerHeight();
		this.$img.attr( 'height', imageHeight );
		// also add to the image above in case the image exhibits responsive behaviours
		// e.g. skin sets height to 100%.
		this.$img.parent( 'a.image' ).height( imageHeight );

		// Make the image smaller in case the current image
		// size is larger than the original file size.
		this.getImageInfo( this.$thumbnail ).then( function ( info ) {
			// NOTE: There will be a jump when resizing the window
			// because the cache is cleared and this a new network request.
			if (
				info.thumbwidth < this.$img.width() ||
				info.thumbheight < this.$img.height()
			) {
				var attrs = {
					width: info.thumbwidth,
					height: info.thumbheight
				};
				this.$img.attr( attrs );
				// also add to the image above in case the image exhibits responsive behaviours
				// e.g. skin sets height to 100%.
				this.$img.parent( 'a.image' ).css( attrs );
			}
		}.bind( this ) );
	};

	/**
	 * Displays the image set as {@link #$currentImage} in the carousel.
	 *
	 * @param {boolean} init Image being show during gallery init (i.e. first image)
	 */
	mw.GallerySlideshow.prototype.showCurrentImage = function ( init ) {
		var $thumbnail, $imgLink,
			$imageLi = this.getCurrentImage(),
			thumbAlt = $imageLi.data( 'alt' ),
			thumbUrl = $imageLi.data( 'src' ),
			$caption = $imageLi.find( '.gallerytext' );

		// The order of the following is important for size calculations
		// 1. Highlight current thumbnail
		this.$gallery
			.find( '.gallerybox.slideshow-current' )
			.removeClass( 'slideshow-current' );
		$imageLi.addClass( 'slideshow-current' );

		this.$thumbnail = $imageLi.find( 'img' );
		// 2a. Create thumbnail.
		this.$img = $( '<img>' ).attr( {
			// prefer dataset if available, as less likely to be manipulated.
			src: thumbUrl || this.$thumbnail.attr( 'src' ),
			alt: thumbAlt || this.$thumbnail.attr( 'alt' )
		} );

		if ( this.$img.attr( 'src' ) ) {
			// 2b. Show thumbnail
			// 'image' class required for detection by MultimediaViewer
			$imgLink = $( '<a>' ).addClass( 'image' )
				.attr( 'href', $imageLi.find( 'a' ).eq( 0 ).attr( 'href' ) )
				.append( this.$img );

			this.$imgContainer.empty().append( $imgLink );
		} else {
			this.$imgContainer.html( $imageLi.find( '.thumb' ).html() );
		}
		this.imageHeight = this.$imgContainer.height();

		// 3. Copy caption
		this.$imgCaption
			.empty()
			.append( $caption.clone() );

		if ( !this.$thumbnail.length ) {
			return;
		}

		// 4. Stretch thumbnail to correct size
		this.setImageSize();

		$thumbnail = this.$thumbnail;
		// 5. Load image at the required size
		this.loadImage( this.$thumbnail ).done( function ( info ) {
			// Show this image to the user only if its still the current one
			if ( this.$thumbnail.attr( 'src' ) === $thumbnail.attr( 'src' ) ) {
				this.$img.attr( 'src', info.thumburl );
				this.setImageSize();
				// Don't fire hook twice during init
				if ( !init ) {
					mw.hook( 'wikipage.content' ).fire( this.$imgContainer );
				}

				// Pre-fetch the next image
				this.loadImage( this.getNextImage().find( 'img' ) );
			}
		}.bind( this ) ).fail( function () {
			// Image didn't load
			var title = mw.Title.newFromImg( this.$img );
			this.$imgContainer.text( title ? title.getMainText() : '' );
		}.bind( this ) );
	};

	/**
	 * Loads the full image given the `<img>` element of the thumbnail.
	 *
	 * @param {jQuery} $img
	 * @return {jQuery.Promise} Resolves with the images URL and original
	 *  element once the image has loaded.
	 */
	mw.GallerySlideshow.prototype.loadImage = function ( $img ) {
		return this.getImageInfo( $img ).then( function ( info ) {
			var img, d = $.Deferred();
			img = new Image();
			img.src = info.thumburl;
			img.onload = function () {
				d.resolve( info );
			};
			img.onerror = function () {
				d.reject();
			};
			return d.promise();
		} );
	};

	/**
	 * Gets the image's info given an `<img>` element.
	 *
	 * @param {Object} $img
	 * @return {jQuery.Promise} Resolves with the image's info.
	 */
	mw.GallerySlideshow.prototype.getImageInfo = function ( $img ) {
		var api, title, params,
			imageSrc = $img.attr( 'src' );

		// Reject promise if there is no thumbnail image
		if ( $img[ 0 ] === undefined ) {
			return $.Deferred().reject();
		}

		if ( this.imageInfoCache[ imageSrc ] === undefined ) {
			api = new mw.Api();
			// TODO: This supports only gallery of images
			title = mw.Title.newFromImg( $img );
			params = {
				action: 'query',
				formatversion: 2,
				titles: title.toString(),
				prop: 'imageinfo',
				iiprop: 'url',
				iiurlwidth: 640
			};

			this.imageInfoCache[ imageSrc ] = api.get( params ).then( function ( data ) {
				if ( OO.getProp( data, 'query', 'pages', 0, 'imageinfo', 0, 'thumburl' ) !== undefined ) {
					return data.query.pages[ 0 ].imageinfo[ 0 ];
				} else {
					return $.Deferred().reject();
				}
			} );
		}

		return this.imageInfoCache[ imageSrc ];
	};

	/**
	 * Toggles visibility of the thumbnails.
	 *
	 * @param {boolean} show Optional argument to control the state
	 */
	mw.GallerySlideshow.prototype.toggleThumbnails = function ( show ) {
		this.$galleryBox.toggle( show );
		this.$carousel.toggleClass( 'mw-gallery-slideshow-thumbnails-toggled', show );
	};

	/**
	 * Getter method for {@link #$currentImage}
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getCurrentImage = function () {
		this.$currentImage = this.$currentImage || this.$galleryBox.eq( 0 );
		return this.$currentImage;
	};

	/**
	 * Gets the image after the current one. Returns the first image if
	 * the current one is the last.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getNextImage = function () {
		// Not the last image in the gallery
		if ( this.$currentImage.next( '.gallerybox' )[ 0 ] !== undefined ) {
			return this.$currentImage.next( '.gallerybox' );
		} else {
			return this.$galleryBox.eq( 0 );
		}
	};

	/**
	 * Gets the image before the current one. Returns the last image if
	 * the current one is the first.
	 *
	 * @return {jQuery}
	 */
	mw.GallerySlideshow.prototype.getPrevImage = function () {
		// Not the first image in the gallery
		if ( this.$currentImage.prev( '.gallerybox' )[ 0 ] !== undefined ) {
			return this.$currentImage.prev( '.gallerybox' );
		} else {
			return this.$galleryBox.last();
		}
	};

	/**
	 * Sets the {@link #$currentImage} to the next one and shows
	 * it in the carousel
	 */
	mw.GallerySlideshow.prototype.nextImage = function () {
		this.$currentImage = this.getNextImage();
		this.showCurrentImage();
	};

	/**
	 * Sets the {@link #$currentImage} to the previous one and shows
	 * it in the carousel
	 */
	mw.GallerySlideshow.prototype.prevImage = function () {
		this.$currentImage = this.getPrevImage();
		this.showCurrentImage();
	};

	// Bootstrap all slideshow galleries
	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		$content.find( '.mw-gallery-slideshow' ).each( function () {
			// eslint-disable-next-line no-new
			new mw.GallerySlideshow( this );
		} );
	} );
}() );
