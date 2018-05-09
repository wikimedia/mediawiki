/*!
 * Show gallery captions when focused. Copied directly from jquery.mw-jump.js.
 * Also Dynamically resize images to justify them.
 */
( function ( mw, $ ) {
	var $galleries,
		bound = false,
		// Is there a better way to detect a touchscreen? Current check taken from stack overflow.
		isTouchScreen = !!( window.ontouchstart !== undefined ||
			window.DocumentTouch !== undefined && document instanceof window.DocumentTouch
		);

	/**
	 * Perform the layout justification.
	 *
	 * @ignore
	 * @context {HTMLElement} A `ul.mw-gallery-*` element
	 */
	function justify() {
		var lastTop,
			$img,
			imgWidth,
			imgHeight,
			captionWidth,
			rows = [],
			$gallery = $( this );

		$gallery.children( 'li.gallerybox' ).each( function () {
			// Math.floor to be paranoid if things are off by 0.00000000001
			var top = Math.floor( $( this ).position().top ),
				$this = $( this );

			if ( top !== lastTop ) {
				rows[ rows.length ] = [];
				lastTop = top;
			}

			$img = $this.find( 'div.thumb a.image img' );
			if ( $img.length && $img[ 0 ].height ) {
				imgHeight = $img[ 0 ].height;
				imgWidth = $img[ 0 ].width;
			} else {
				// If we don't have a real image, get the containing divs width/height.
				// Note that if we do have a real image, using this method will generally
				// give the same answer, but can be different in the case of a very
				// narrow image where extra padding is added.
				imgHeight = $this.children().children( 'div:first' ).height();
				imgWidth = $this.children().children( 'div:first' ).width();
			}

			// Hack to make an edge case work ok
			if ( imgHeight < 30 ) {
				// Don't try and resize this item.
				imgHeight = 0;
			}

			captionWidth = $this.children().children( 'div.gallerytextwrapper' ).width();
			rows[ rows.length - 1 ][ rows[ rows.length - 1 ].length ] = {
				$elm: $this,
				width: $this.outerWidth(),
				imgWidth: imgWidth,
				// XXX: can divide by 0 ever happen?
				aspect: imgWidth / imgHeight,
				captionWidth: captionWidth,
				height: imgHeight
			};

			// Save all boundaries so we can restore them on window resize
			$this.data( 'imgWidth', imgWidth );
			$this.data( 'imgHeight', imgHeight );
			$this.data( 'width', $this.outerWidth() );
			$this.data( 'captionWidth', captionWidth );
		} );

		( function () {
			var maxWidth,
				combinedAspect,
				combinedPadding,
				curRow,
				curRowHeight,
				wantedWidth,
				preferredHeight,
				newWidth,
				padding,
				$outerDiv,
				$innerDiv,
				$imageDiv,
				$imageElm,
				imageElm,
				$caption,
				i,
				j,
				avgZoom,
				totalZoom = 0;

			for ( i = 0; i < rows.length; i++ ) {
				maxWidth = $gallery.width();
				combinedAspect = 0;
				combinedPadding = 0;
				curRow = rows[ i ];
				curRowHeight = 0;

				for ( j = 0; j < curRow.length; j++ ) {
					if ( curRowHeight === 0 ) {
						if ( isFinite( curRow[ j ].height ) ) {
							// Get the height of this row, by taking the first
							// non-out of bounds height
							curRowHeight = curRow[ j ].height;
						}
					}

					if ( curRow[ j ].aspect === 0 || !isFinite( curRow[ j ].aspect ) ) {
						// One of the dimensions are 0. Probably should
						// not try to resize.
						combinedPadding += curRow[ j ].width;
					} else {
						combinedAspect += curRow[ j ].aspect;
						combinedPadding += curRow[ j ].width - curRow[ j ].imgWidth;
					}
				}

				// Add some padding for inter-element spacing.
				combinedPadding += 5 * curRow.length;
				wantedWidth = maxWidth - combinedPadding;
				preferredHeight = wantedWidth / combinedAspect;

				if ( preferredHeight > curRowHeight * 1.5 ) {
					// Only expand at most 1.5 times current size
					// As that's as high a resolution as we have.
					// Also on the off chance there is a bug in this
					// code, would prevent accidentally expanding to
					// be 10 billion pixels wide.
					if ( i === rows.length - 1 ) {
						// If its the last row, and we can't fit it,
						// don't make the entire row huge.
						avgZoom = ( totalZoom / ( rows.length - 1 ) ) * curRowHeight;
						if ( isFinite( avgZoom ) && avgZoom >= 1 && avgZoom <= 1.5 ) {
							preferredHeight = avgZoom;
						} else {
							// Probably a single row gallery
							preferredHeight = curRowHeight;
						}
					} else {
						preferredHeight = 1.5 * curRowHeight;
					}
				}
				if ( !isFinite( preferredHeight ) ) {
					// This *definitely* should not happen.
					// Skip this row.
					continue;
				}
				if ( preferredHeight < 5 ) {
					// Well something clearly went wrong...
					// Skip this row.
					continue;
				}

				if ( preferredHeight / curRowHeight > 1 ) {
					totalZoom += preferredHeight / curRowHeight;
				} else {
					// If we shrink, still consider that a zoom of 1
					totalZoom += 1;
				}

				for ( j = 0; j < curRow.length; j++ ) {
					newWidth = preferredHeight * curRow[ j ].aspect;
					padding = curRow[ j ].width - curRow[ j ].imgWidth;
					$outerDiv = curRow[ j ].$elm;
					$innerDiv = $outerDiv.children( 'div' ).first();
					$imageDiv = $innerDiv.children( 'div.thumb' );
					$imageElm = $imageDiv.find( 'img' ).first();
					imageElm = $imageElm.length ? $imageElm[ 0 ] : null;
					$caption = $outerDiv.find( 'div.gallerytextwrapper' );

					// Since we are going to re-adjust the height, the vertical
					// centering margins need to be reset.
					$imageDiv.children( 'div' ).css( 'margin', '0px auto' );

					if ( newWidth < 60 || !isFinite( newWidth ) ) {
						// Making something skinnier than this will mess up captions,
						if ( newWidth < 1 || !isFinite( newWidth ) ) {
							$innerDiv.height( preferredHeight );
							// Don't even try and touch the image size if it could mean
							// making it disappear.
							continue;
						}
					} else {
						$outerDiv.width( newWidth + padding );
						$innerDiv.width( newWidth + padding );
						$imageDiv.width( newWidth );
						$caption.width( curRow[ j ].captionWidth + ( newWidth - curRow[ j ].imgWidth ) );
					}

					if ( imageElm ) {
						// We don't always have an img, e.g. in the case of an invalid file.
						imageElm.width = newWidth;
						imageElm.height = preferredHeight;
					} else {
						// Not a file box.
						$imageDiv.height( preferredHeight );
					}
				}
			}
		}() );
	}

	function handleResizeStart() {
		$galleries.children( 'li.gallerybox' ).each( function () {
			var imgWidth = $( this ).data( 'imgWidth' ),
				imgHeight = $( this ).data( 'imgHeight' ),
				width = $( this ).data( 'width' ),
				captionWidth = $( this ).data( 'captionWidth' ),
				$innerDiv = $( this ).children( 'div' ).first(),
				$imageDiv = $innerDiv.children( 'div.thumb' ),
				$imageElm, imageElm;

			// Restore original sizes so we can arrange the elements as on freshly loaded page
			$( this ).width( width );
			$innerDiv.width( width );
			$imageDiv.width( imgWidth );
			$( this ).find( 'div.gallerytextwrapper' ).width( captionWidth );

			$imageElm = $( this ).find( 'img' ).first();
			imageElm = $imageElm.length ? $imageElm[ 0 ] : null;
			if ( imageElm ) {
				imageElm.width = imgWidth;
				imageElm.height = imgHeight;
			} else {
				$imageDiv.height( imgHeight );
			}
		} );
	}

	function handleResizeEnd() {
		$galleries.each( justify );
	}

	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		if ( isTouchScreen ) {
			// Always show the caption for a touch screen.
			$content.find( 'ul.mw-gallery-packed-hover' )
				.addClass( 'mw-gallery-packed-overlay' )
				.removeClass( 'mw-gallery-packed-hover' );
		} else {
			// Note use of just "a", not a.image, since we want this to trigger if a link in
			// the caption receives focus
			$content.find( 'ul.mw-gallery-packed-hover li.gallerybox' ).on( 'focus blur', 'a', function ( e ) {
				// Confusingly jQuery leaves e.type as focusout for delegated blur events
				var gettingFocus = e.type !== 'blur' && e.type !== 'focusout';
				$( this ).closest( 'li.gallerybox' ).toggleClass( 'mw-gallery-focused', gettingFocus );
			} );
		}

		$galleries = $content.find( 'ul.mw-gallery-packed-overlay, ul.mw-gallery-packed-hover, ul.mw-gallery-packed' );
		// Call the justification asynchronous because live preview fires the hook with detached $content.
		setTimeout( function () {
			$galleries.each( justify );

			// Bind here instead of in the top scope as the callbacks use $galleries.
			if ( !bound ) {
				bound = true;
				$( window )
					.resize( $.debounce( 300, true, handleResizeStart ) )
					.resize( $.debounce( 300, handleResizeEnd ) );
			}
		} );
	} );
}( mediaWiki, jQuery ) );
