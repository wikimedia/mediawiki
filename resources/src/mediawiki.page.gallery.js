/*!
 * Enhance MediaWiki galleries (from the `<gallery>` parser tag).
 *
 * - Toggle gallery captions when focused.
 * - Dynamically resize images to fill horizontal space.
 */
( function () {
	let $galleries,
		bound = false,
		lastWidth = window.innerWidth,
		justifyNeeded = false;
		// Is there a better way to detect a touchscreen? Current check taken from stack overflow.
	const isTouchScreen = !!( window.ontouchstart !== undefined ||
		window.DocumentTouch !== undefined && document instanceof window.DocumentTouch
	);

	/**
	 * Perform the layout justification.
	 *
	 * @ignore
	 * @this HTMLElement A `ul.mw-gallery-*` element
	 */
	function justify() {
		let lastTop;
		const rows = [],
			$gallery = $( this );

		$gallery.children( 'li.gallerybox' ).each( function () {
			// Math.floor, to be paranoid if things are off by 0.00000000001
			const top = Math.floor( $( this ).position().top ),
				$this = $( this );

			if ( top !== lastTop ) {
				rows.push( [] );
				lastTop = top;
			}

			const $imageDiv = $this.find( 'div.thumb' ).first();
			const $img = $imageDiv.find( 'img, video' ).first();
			let imgWidth, imgHeight;
			if ( $img.length && $img[ 0 ].height ) {
				imgHeight = $img[ 0 ].height;
				imgWidth = $img[ 0 ].width;
			} else {
				// If we don't have a real image, get the containing divs width/height.
				// Note that if we do have a real image, using this method will generally
				// give the same answer, but can be different in the case of a very
				// narrow image where extra padding is added.
				imgHeight = $imageDiv.height();
				imgWidth = $imageDiv.width();
			}

			// Hack to make an edge case work ok
			// (This happens for very small images, and for audio files)
			if ( imgHeight < 40 ) {
				// Don't try and resize this item.
				imgHeight = 0;
			}

			const captionWidth = $this.find( 'div.gallerytextwrapper' ).width();
			const outerWidth = $this.outerWidth();
			rows[ rows.length - 1 ].push( {
				$elm: $this,
				width: outerWidth,
				imgWidth: imgWidth,
				// FIXME: Deal with devision by 0.
				aspect: imgWidth / imgHeight,
				captionWidth: captionWidth,
				height: imgHeight
			} );

			// Save all boundaries so we can restore them on window resize
			$this.data( {
				imgWidth: imgWidth,
				imgHeight: imgHeight,
				width: outerWidth,
				captionWidth: captionWidth
			} );
		} );

		( function () {
			let totalZoom = 0;

			for ( let i = 0; i < rows.length; i++ ) {
				const maxWidth = $gallery.width();
				let combinedAspect = 0;
				let combinedPadding = 0;
				const curRow = rows[ i ];
				let curRowHeight = 0;

				for ( let j = 0; j < curRow.length; j++ ) {
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
				const wantedWidth = maxWidth - combinedPadding;
				let preferredHeight = wantedWidth / combinedAspect;

				if ( preferredHeight > curRowHeight * 1.5 ) {
					// Only expand at most 1.5 times current size
					// As that's as high a resolution as we have.
					// Also on the off chance there is a bug in this
					// code, would prevent accidentally expanding to
					// be 10 billion pixels wide.
					if ( i === rows.length - 1 ) {
						// If its the last row, and we can't fit it,
						// don't make the entire row huge.
						const avgZoom = totalZoom / ( rows.length - 1 );
						if ( isFinite( avgZoom ) && avgZoom >= 1 && avgZoom <= 1.5 ) {
							preferredHeight = avgZoom * curRowHeight;
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

				for ( let j = 0; j < curRow.length; j++ ) {
					const newWidth = preferredHeight * curRow[ j ].aspect;
					const padding = curRow[ j ].width - curRow[ j ].imgWidth;
					const $gallerybox = curRow[ j ].$elm;
					const $imageDiv = $gallerybox.find( 'div.thumb' ).first();
					const $imageElm = $imageDiv.find( 'img, video' ).first();
					const $caption = $gallerybox.find( 'div.gallerytextwrapper' );

					// Since we are going to re-adjust the height, the vertical
					// centering margins need to be reset.
					$imageDiv.children( 'div' ).css( 'margin', '0px auto' );

					if ( newWidth < 60 || !isFinite( newWidth ) ) {
						// Making something skinnier than this will mess up captions,
						if ( newWidth < 1 || !isFinite( newWidth ) ) {
							// Don't even try and touch the image size if it could mean
							// making it disappear.
							continue;
						}
					} else {
						$gallerybox.width( newWidth + padding );
						$imageDiv.width( newWidth );
						$caption.width( curRow[ j ].captionWidth + ( newWidth - curRow[ j ].imgWidth ) );
					}

					// We don't always have an img, e.g. in the case of an invalid file.
					if ( $imageElm[ 0 ] ) {
						const imageElm = $imageElm[ 0 ];
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
		// Only do anything if window width changed. We don't care about the height.
		if ( lastWidth === window.innerWidth ) {
			return;
		}

		justifyNeeded = true;
		// Temporarily set min-height, so that content following the gallery is not reflowed twice
		$galleries.css( 'min-height', function () {
			return $( this ).height();
		} );
		$galleries.children( 'li.gallerybox' ).each( function () {
			const imgWidth = $( this ).data( 'imgWidth' ),
				imgHeight = $( this ).data( 'imgHeight' ),
				width = $( this ).data( 'width' ),
				captionWidth = $( this ).data( 'captionWidth' ),
				$imageDiv = $( this ).find( 'div.thumb' ).first();

			// Restore original sizes so we can arrange the elements as on freshly loaded page
			$( this ).width( width );
			$imageDiv.width( imgWidth );
			$( this ).find( 'div.gallerytextwrapper' ).width( captionWidth );

			const $imageElm = $imageDiv.find( 'img, video' ).first();
			if ( $imageElm[ 0 ] ) {
				const imageElm = $imageElm[ 0 ];
				imageElm.width = imgWidth;
				imageElm.height = imgHeight;
			} else {
				$imageDiv.height( imgHeight );
			}
		} );
	}

	function handleResizeEnd() {
		// If window width never changed during the resize, don't do anything.
		if ( justifyNeeded ) {
			justifyNeeded = false;
			lastWidth = window.innerWidth;
			$galleries
				// Remove temporary min-height
				.css( 'min-height', '' )
				// Recalculate layout
				.each( justify );
		}
	}

	mw.hook( 'wikipage.content' ).add( ( $content ) => {
		if ( isTouchScreen ) {
			// Always show the caption for a touch screen.
			$content.find( 'ul.mw-gallery-packed-hover' )
				.addClass( 'mw-gallery-packed-overlay' )
				.removeClass( 'mw-gallery-packed-hover' );
		} else {
			// Note use of just `a`, not `a.image`, since we also want this to trigger if a link
			// within the caption text receives focus.
			$content.find( 'ul.mw-gallery-packed-hover li.gallerybox' ).on( 'focus blur', 'a', function ( e ) {
				// Confusingly jQuery leaves e.type as focusout for delegated blur events
				const gettingFocus = e.type !== 'blur' && e.type !== 'focusout';
				$( this ).closest( 'li.gallerybox' ).toggleClass( 'mw-gallery-focused', gettingFocus );
			} );
		}

		$galleries = $content.find( 'ul.mw-gallery-packed-overlay, ul.mw-gallery-packed-hover, ul.mw-gallery-packed' );
		// Call the justification asynchronous because live preview fires the hook with detached $content.
		setTimeout( () => {
			$galleries.each( justify );

			// Bind here instead of in the top scope as the callbacks use $galleries.
			if ( !bound ) {
				bound = true;
				$( window )
					.on( 'resize', mw.util.debounce( handleResizeStart, 300, true ) )
					.on( 'resize', mw.util.debounce( handleResizeEnd, 300 ) );
			}
		} );
	} );
}() );
