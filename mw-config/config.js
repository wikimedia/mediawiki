/* global extDependencyMap */
( function () {
	$( () => {
		let $label = null, labelText = null;

		function syncText() {
			let value = $( this ).val()
				.replace( /[\[\]{}|#<>%+? ]/g, '_' ) // eslint-disable-line no-useless-escape
				.replace( /&/, '&amp;' )
				.replace( /__+/g, '_' )
				.replace( /^_+/, '' )
				.replace( /_+$/, '' );
			value = value.charAt( 0 ).toUpperCase() + value.slice( 1 );
			$label.text( labelText.replace( '$1', value ) );
		}

		// Show/hide code for DB-specific options
		// FIXME: Do we want slow, fast, or even non-animated (instantaneous) showing/hiding here?
		$( '.dbRadio' ).each( function () {
			$( document.getElementById( $( this ).attr( 'rel' ) ) ).hide();
		} );
		$( document.getElementById( $( '.dbRadio:checked' ).attr( 'rel' ) ) ).show();
		$( '.dbRadio' ).on( 'click', () => {
			const $checked = $( '.dbRadio:checked' ),
				$wrapper = $( document.getElementById( $checked.attr( 'rel' ) ) );
			// eslint-disable-next-line no-jquery/no-sizzle
			if ( $wrapper.is( ':hidden' ) ) {
				// FIXME: Use CSS transition
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$( '.dbWrapper' ).hide( 'slow' );
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$wrapper.show( 'slow' );
			}
		} );

		// Scroll to the bottom of upgrade log
		$( '#config-live-log' ).children( 'textarea' ).each( function () {
			this.scrollTop = this.scrollHeight;
		} );

		// Show/hide random stuff (email, upload)
		$( '.showHideRadio' ).on( 'click', function () {
			const $wrapper = $( '#' + $( this ).attr( 'rel' ) );
			if ( $( this ).is( ':checked' ) ) {
				// FIXME: Use CSS transition
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$wrapper.show( 'slow' );
			} else {
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$wrapper.hide( 'slow' );
			}
		} );
		$( '.hideShowRadio' ).on( 'click', function () {
			const $wrapper = $( '#' + $( this ).attr( 'rel' ) );
			if ( $( this ).is( ':checked' ) ) {
				// FIXME: Use CSS transition
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$wrapper.hide( 'slow' );
			} else {
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$wrapper.show( 'slow' );
			}
		} );

		// Hide "other" textboxes by default
		// Should not be done in CSS for javascript disabled compatibility
		if ( !$( '#config__NamespaceType_other' ).is( ':checked' ) ) {
			$( '.enabledByOther' ).closest( '.config-block' ).hide();
		}

		// Enable/disable "other" textboxes
		$( '.enableForOther' ).on( 'click', function () {
			const $textbox = $( document.getElementById( $( this ).attr( 'rel' ) ) );
			// FIXME: Ugh, this is ugly
			if ( $( this ).val() === 'other' ) {
				// FIXME: Use CSS transition
				// eslint-disable-next-line no-jquery/no-slide
				$textbox.prop( 'readonly', false ).closest( '.config-block' ).slideDown( 'fast' );
			} else {
				// eslint-disable-next-line no-jquery/no-slide
				$textbox.prop( 'readonly', true ).closest( '.config-block' ).slideUp( 'fast' );
			}
		} );

		// Synchronize radio button label for sitename with textbox
		$label = $( 'label[for="config__NamespaceType_site-name"]' );
		labelText = $label.text();
		$label.text( labelText.replace( '$1', '' ) );
		$( '#config_wgSitename' ).on( 'keyup change', syncText ).each( syncText );

		// Show/Hide memcached servers when needed
		$( 'input[name$="config__MainCacheType"]' ).on( 'change', () => {
			const $memc = $( '#config-memcachewrapper' );
			if ( $( 'input[name$="config__MainCacheType"]:checked' ).val() === 'memcached' ) {
				// FIXME: Use CSS transition
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$memc.show( 'slow' );
			} else {
				// eslint-disable-next-line no-jquery/no-animate-toggle
				$memc.hide( 'slow' );
			}
		} );

		function areReqsSatisfied( name ) {
			let i, ext, skin, node;
			if ( !extDependencyMap[ name ] ) {
				return true;
			}

			if ( extDependencyMap[ name ].extensions ) {
				for ( i in extDependencyMap[ name ].extensions ) {
					ext = extDependencyMap[ name ].extensions[ i ];
					node = document.getElementById( 'config_ext-' + ext );
					if ( !node || !node.checked ) {
						return false;
					}
				}
			}
			if ( extDependencyMap[ name ].skins ) {
				for ( i in extDependencyMap[ name ].skins ) {
					skin = extDependencyMap[ name ].skins[ i ];
					node = document.getElementById( 'config_skin-' + skin );
					if ( !node || !node.checked ) {
						return false;
					}
				}
			}

			return true;
		}

		// Disable checkboxes if the extension has dependencies
		$( '.mw-ext-with-dependencies input' ).prop( 'disabled', true );
		$( '.config-ext-input[data-name]' ).on( 'change', () => {
			$( '.mw-ext-with-dependencies input' ).each( function () {
				const name = this.getAttribute( 'data-name' );
				if ( areReqsSatisfied( name ) ) {
					// Re-enable it!
					this.disabled = false;
				} else {
					// Uncheck and disable the checkbox
					this.checked = false;
					this.disabled = true;
				}
			} );
		} );

		const base = window.location.pathname.split( '/mw-config' )[ 0 ];
		function getLogoPath( src ) {
			return src.replace( '$wgResourceBasePath', base );
		}

		const nodes = {
			sidebar: 'config__Logo1x',
			icon: 'config__LogoIcon',
			wordmark: 'config__LogoWordmark',
			tagline: 'config__LogoTagline'
		};

		// setup live preview of logos
		function getLogoData() {
			const data = {};
			for ( const key in nodes ) {
				const input = document.getElementById( nodes[ key ] );
				if ( input ) {
					data[ key ] = getLogoPath( input.value );
				}
			}
			return data;
		}

		/**
		 * Render the logo based on the current input field values.
		 *
		 * @param {jQuery} $preview
		 */
		function renderLogo( $preview ) {
			const data = getLogoData();
			const $sidebar = $( '<div>' );
			$sidebar.addClass( 'sidebar' );
			const sidebarLogo = data.sidebar || data.icon;
			if ( sidebarLogo ) {
				const $sidebarCard = $( '<span>' ).addClass( 'cdx-card' ).css( 'display', 'inline-block' ).append(
					$( '<span>' ).addClass( 'cdx-card__thumbnail cdx-thumbnail' ).html(
						$( '<img>' ).attr( 'src', sidebarLogo ).addClass( 'logo-sidebar' )
					)
				).appendTo( $sidebar );

				const $menu = $( '<span>' ).addClass( 'cdx-card__text' ).append(
					$( '<span>' ).addClass( 'cdx-card__text__title' ).append(
						$( '<a>' ).attr( 'href', '#' ).text( $preview.data( 'main-page' ) )
					)
				);
				$menu.appendTo( $sidebarCard );
			}
			const $main = $( '<span>' ).addClass( 'logo-main' ).addClass( 'cdx-card' );
			if ( data.icon ) {
				$( '<span>' ).addClass( 'cdx-card__thumbnail cdx-thumbnail' ).html(
					$( '<img>' ).attr( 'src', data.icon ).addClass( 'logo-icon' )
				).appendTo( $main );
			}
			const $container = $( '<span>' ).addClass( 'cdx-card__text' ).appendTo( $main );

			const fallback = {
				wordmark: $( '[name=config_LogoSiteName]' ).val()
			};
			[ 'wordmark', 'tagline' ].forEach( ( key ) => {
				const src = data[ key ];
				const $el = src ?
					$( '<img>' ).attr( 'src', src ) :
					$( '<div>' ).text( fallback[ key ] );

				// The following classes are used here:
				// * logo-wordmark
				// * logo-tagline
				$el.addClass( 'logo-' + key ).appendTo( $container );
			} );
			$preview.empty().append( $sidebar, $main );
		}

		/**
		 * Adds file droppers
		 *
		 * @param {jQuery} $preview
		 * @param {string} tooltip
		 */
		function addDroppers( $preview, tooltip ) {
			for ( const key in nodes ) {
				const dropper = document.createElement( 'div' );
				const input = document.getElementById( nodes[ key ] );
				dropper.textContent = tooltip;
				input.parentNode.insertBefore( dropper, input.nextSibling );
				dropper.classList.add( 'logo-dropper' );
				dropper.addEventListener( 'dragover', ( ev ) => {
					ev.preventDefault();
				} );
				dropper.addEventListener( 'drop', function ( ev ) {
					// Prevent default behavior (Prevent file from being opened)
					ev.preventDefault();
					const d = this;
					const item = ev.dataTransfer.items[ 0 ];
					// Only allow images.
					if ( item && item.type.startsWith( 'image/' ) ) {
						const blob = item.getAsFile();
						const reader = new FileReader();
						reader.readAsDataURL( blob );
						reader.onloadend = function () {
							const base64data = reader.result;
							d.previousSibling.value = base64data;
							renderLogo( $preview );
						};
					}
				} );
			}
		}

		// setup preview area to respond to changes.
		const $pOptions = $( '.config-personalization-options' );
		if ( $pOptions.length ) {
			const $previewArea = $pOptions.find( '.logo-preview-area' );
			$pOptions.find( ' input' ).on( 'input', () => {
				renderLogo( $previewArea );
			} );
			addDroppers( $previewArea, $previewArea.data( 'filedrop' ) );
			renderLogo( $previewArea );
		}

		$( 'a.config-help-field-hint' ).on( 'click', function () {
			// eslint-disable-next-line no-jquery/no-class-state
			$( this )
				.siblings( 'div.config-help-field-content' )
				.toggleClass( 'config-help-field-content-hidden' );
		} );
	} );
}() );
