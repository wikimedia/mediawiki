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
		if ( this.$gallery.parent().attr( 'id' ) !== 'mw-content-text' ) {
			this.$container = this.$gallery.parent();
		}

		// Initialize
		this.drawCarousel();
		this.setSizeRequirement();
		this.toggleThumbnails( !!this.$gallery.attr( 'data-showthumbnails' ) );
		this.showCurrentImage();

		// Events
		$( window ).on(
			'resize',
			OO.ui.debounce(
				this.setSizeRequirement.bind( this ),
				100
			)
		);

		// Disable thumbnails' link, instead show the image in the carousel
		this.$galleryBox.on( 'click', function ( e ) {
			this.$currentImage = $( e.currentTarget );
			this.showCurrentImage();
			return false;
		}.bind( this ) );
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
	 * @property {jQuery} $imgLink The `<a>` element that links to the image's File page.
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
	 * @property {jQuery} $container If the gallery contained in an element that is
	 *   not the main content element, then it stores that element.
	 */

	/**
	 * @property {Object} imageInfoCache A key value pair of thumbnail URLs and image info.
	 */

	/**
	 * @property {number} imageWidth Width of the image based on viewport size
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
		var next, prev, toggle,	interfaceElements, carouselStack;

		this.$carousel = $( '<li>' ).addClass( 'gallerycarousel' );

		// Buttons for the interface
		prev = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'previous'
		} ).on( 'click', this.prevImage.bind( this ) );

		next = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'next'
		} ).on( 'click', this.nextImage.bind( this ) );

		toggle = new OO.ui.ButtonWidget( {
			framed: false,
			icon: 'imageGallery',
			title: mw.msg( 'gallery-slideshow-toggle' )
		} ).on( 'click', this.toggleThumbnails.bind( this ) );

		interfaceElements = new OO.ui.PanelLayout( {
			expanded: false,
			classes: [ 'mw-gallery-slideshow-buttons' ],
			$content: $( '<div>' ).append(
				prev.$element,
				toggle.$element,
				next.$element
			)
		} );
		this.$interface = interfaceElements.$element;

		// Containers for the current image, caption etc.
		this.$img = $( '<img>' );
		this.$imgLink = $( '<a>' ).append( this.$img );
		this.$imgCaption = $( '<p>' ).attr( 'class', 'mw-gallery-slideshow-caption' );
		this.$imgContainer = $( '<div>' )
			.attr( 'class', 'mw-gallery-slideshow-img-container' )
			.append( this.$imgLink );

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
	 * Sets the {@link #imageWidth} and {@link #imageHeight} properties
	 * based on the size of the window. Also flushes the
	 * {@link #imageInfoCache} as we'll now need URLs for a different
	 * size.
	 */
	mw.GallerySlideshow.prototype.setSizeRequirement = function () {
		var w, h;

		if ( this.$container !== undefined ) {
			w = this.$container.width() * 0.9;
			h = ( this.$container.height() - this.getChromeHeight() ) * 0.9;
		} else {
			w = this.$imgContainer.width();
			h = Math.min( $( window ).height() * ( 3 / 4 ), this.$imgContainer.width() ) - this.getChromeHeight();
		}

		// Only update and flush the cache if the size changed
		if ( w !== this.imageWidth || h !== this.imageHeight ) {
			this.imageWidth = w;
			this.imageHeight = h;
			this.imageInfoCache = {};
			this.setImageSize();
		}
	};

	/**
	 * Gets the height of the interface elements and the
	 * gallery's caption.
	 *
	 * @return {number} Height
	 */
	mw.GallerySlideshow.prototype.getChromeHeight = function () {
		return this.$interface.outerHeight() + this.$galleryCaption.outerHeight();
	};

	/**
	 * Sets the height and width of {@link #$img} based on the
	 * proportion of the image and the values generated by
	 * {@link #setSizeRequirement}.
	 *
	 * @return {boolean} Whether or not the image was sized.
	 */
	mw.GallerySlideshow.prototype.setImageSize = function () {
		if ( this.$img === undefined || this.$thumbnail === undefined ) {
			return false;
		}

		// Reset height and width
		this.$img
			.removeAttr( 'width' )
			.removeAttr( 'height' );

		// Stretch image to take up the required size
		this.$img.attr( 'height', ( this.imageHeight - this.$imgCaption.outerHeight() ) + 'px' );

		// Make the image smaller in case the current image
		// size is larger than the original file size.
		this.getImageInfo( this.$thumbnail ).done( function ( info ) {
			// NOTE: There will be a jump when resizing the window
			// because the cache is cleared and this a new network request.
			if (
				info.thumbwidth < this.$img.width() ||
				info.thumbheight < this.$img.height()
			) {
				this.$img.attr( 'width', info.thumbwidth + 'px' );
				this.$img.attr( 'height', info.thumbheight + 'px' );
			}
		}.bind( this ) );

		return true;
	};

	/**
	 * Displays the image set as {@link #$currentImage} in the carousel.
	 */
	mw.GallerySlideshow.prototype.showCurrentImage = function () {
		var imageLi = this.getCurrentImage(),
			caption = imageLi.find( '.gallerytext' );

		// The order of the following is important for size calculations
		// 1. Highlight current thumbnail
		this.$gallery
			.find( '.gallerybox.slideshow-current' )
			.removeClass( 'slideshow-current' );
		imageLi.addClass( 'slideshow-current' );

		// 2. Show thumbnail
		this.$thumbnail = imageLi.find( 'img' );
		this.$img.attr( 'src', this.$thumbnail.attr( 'src' ) );
		this.$img.attr( 'alt', this.$thumbnail.attr( 'alt' ) );
		this.$imgLink.attr( 'href', imageLi.find( 'a' ).eq( 0 ).attr( 'href' ) );

		// 3. Copy caption
		this.$imgCaption
			.empty()
			.append( caption.clone() );

		// 4. Stretch thumbnail to correct size
		this.setImageSize();

		// 5. Load image at the required size
		this.loadImage( this.$thumbnail ).done( function ( info, $img ) {
			// Show this image to the user only if its still the current one
			if ( this.$thumbnail.attr( 'src' ) === $img.attr( 'src' ) ) {
				this.$img.attr( 'src', info.thumburl );
				this.setImageSize();

				// Keep the next image ready
				this.loadImage( this.getNextImage().find( 'img' ) );
			}
		}.bind( this ) );
	};

	/**
	 * Loads the full image given the `<img>` element of the thumbnail.
	 *
	 * @param {Object} $img
	 * @return {jQuery.Promise} Resolves with the images URL and original
	 *	element once the image has loaded.
	 */
	mw.GallerySlideshow.prototype.loadImage = function ( $img ) {
		var img, d = $.Deferred();

		this.getImageInfo( $img ).done( function ( info ) {
			img = new Image();
			img.src = info.thumburl;
			img.onload = function () {
				d.resolve( info, $img );
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
				iiprop: 'url'
			};

			// Check which dimension we need to request, based on
			// image and container proportions.
			if ( this.getDimensionToRequest( $img ) === 'height' ) {
				params.iiurlheight = this.imageHeight;
			} else {
				params.iiurlwidth = this.imageWidth;
			}

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
	 * Given an image, the method checks whether to use the height
	 * or the width to request the larger image.
	 *
	 * @param {jQuery} $img
	 * @return {string}
	 */
	mw.GallerySlideshow.prototype.getDimensionToRequest = function ( $img ) {
		var ratio = $img.width() / $img.height();

		if ( this.imageHeight * ratio <= this.imageWidth ) {
			return 'height';
		} else {
			return 'width';
		}
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
