class HtmlformCheckerV2 {
	constructor( $element, validator, options = { feedback: false } ) {
		this.valid = true;
		this.validationState = null;
		this.validator = validator;
		this.options = options;
		this.$element = $element;
		this.$form = $element.parents( 'form' );
		this.element = $element.get( 0 );
		this.field = $element.parents( '.cdx-field' ).get( 0 );
		this.elementWrapper = this.element.parentNode;
		this.validationMessageWrapper = this.field.querySelector( '.cdx-field__validation-message' );
		this.validationMessage = this.field.querySelector( '.cdx-message' );

		this.debouncedValidation = mw.util.debounce( this.validate, 2000 );
	}

	enhanceElement() {
		this.createValidationMessageElement();
		this.createProgressIndicatorElement();
		this.hideElement( this.validationMessageProgressIndicator );
		this.validationMessage.appendChild( this.validationMessageProgressIndicator );
		this.validationMessage.appendChild( this.validationMessageIcon );
		this.validationMessage.appendChild( this.validationMessageContent );

		if ( !this.validationMessageWrapper ) {
			this.validationMessageWrapper = document.createElement( 'div' );
			this.validationMessageWrapper.classList.add( 'cdx-field__validation-message' );
			this.validationMessageWrapper.appendChild( this.validationMessage );
			this.hideElement( this.validationMessageWrapper );
			this.field.appendChild( this.validationMessageWrapper );
		} else {
			this.validationMessageWrapper.appendChild( this.validationMessage );
		}
	}

	createValidationMessageElement() {
		this.validationMessage = document.createElement( 'div' );
		this.validationMessage.classList.add( 'cdx-message', 'cdx-message--inline' );
		this.validationMessageIcon = document.createElement( 'span' );
		this.validationMessageContent = document.createElement( 'div' );
		this.validationMessageContent.classList.add( 'cdx-message__content' );
		this.validationMessageIcon.classList.add( 'cdx-message__icon' );
	}

	createProgressIndicatorElement() {
		this.validationMessageProgressIndicator = document.createElement( 'div' );
		this.validationMessageProgressIndicator.classList.add( 'cdx-progress-indicator' );
		this.validationMessageProgressIndicatorElement = document.createElement( 'div' );
		this.validationMessageProgressIndicatorElement.classList.add( 'cdx-progress-indicator__indicator' );
		this.validationMessageProgressIndicatorProgress = document.createElement( 'progress' );
		this.validationMessageProgressIndicatorProgress.classList.add( 'cdx-progress-indicator__indicator__progress' );
		this.validationMessageProgressIndicator.appendChild( this.validationMessageProgressIndicatorElement );
		this.validationMessageProgressIndicatorElement.appendChild( this.validationMessageProgressIndicatorProgress );
	}

	attach( $extraElements ) {
		let $e = this.$element;

		this.enhanceElement();

		if ( $extraElements ) {
			$e = $e.add( $extraElements );
		}

		if ( this.options.feedback ) {
			$e.on( 'input', ( e ) => {
				const { value } = e.target;
				if ( value === '' ) {
					this.setErrors( true, [] );
					return;
				}
				if ( this.validationState !== 'notice' ) {
					setTimeout(
						() => this.showCheckFeedback(),
						250
					);
				}
			} );
		}

		$e.on( 'input', () => this.debouncedValidation() );
		$e.on( 'blur', () => this.validate() );

		// Check validation state on submit and trigger 'error' state instead of 'warning'
		this.$form.on( 'submit', () => {
			if ( this.valid ) {
				this.setErrors( true, [] );
			} else {
				this.setError( this.lastError );
				this.resetValidationStateClasses();
				this.setValidationStateClasses( 'error' );
				this.$element.parents( '.cdx-field' ).get( 0 ).scrollIntoView();
			}
			return this.valid;
		} );
	}

	// Custom show/hide methods to avoid having to memoize all the initial display values for Codex
	// markup (eg: inline-block, flex, etc.) that needs to be toggled
	showElement( el ) {
		el.classList.toggle( 'mw-htmlformchecker-v2-hide', false );
	}

	hideElement( el ) {
		el.classList.toggle( 'mw-htmlformchecker-v2-hide', true );
	}

	setError( err ) {
		// Existing messages may use html markup, support both plain text and markup for now, prefer
		// markup over plain text, consider using only plain text.
		if ( typeof err === 'string' ) {
			this.validationMessageContent.innerHTML = err;
		} else {
			const html = err.html();
			const text = err.text();
			if ( html ) {
				this.validationMessageContent.innerHTML = html;
			} else if ( text ) {
				this.validationMessageContent.innerText = text;
			} else {
				throw new Error( 'Unexpected jQuery error object, does not contain text neither html' );
			}
		}
	}

	/**
	 * @param {boolean} valid
	 * @param {Array.<(jQuery|string)>} errors
	 * @param {?string} type
	 * @param {?boolean} forceReplacement
	 */
	// eslint-disable-next-line no-unused-vars
	setErrors( valid, errors, type, forceReplacement ) {
		this.valid = valid;
		this.validationState = type;

		if ( errors.length === 0 ) {
			this.lastError = null;
			this.validationMessageContent.innerText = '';
			this.validationMessageContent.innerHTML = '';
			this.hideElement( this.validationMessageWrapper );
			return;
		}
		if ( errors.length && !type ) {
			throw new Error( 'No "type" informed while setting validation errors' );
		}
		// Always display a single error on live-validation, as opposed to post submit which will render all
		if ( errors.length > 0 ) {
			this.lastError = errors[ 0 ];
			this.setError( errors[ 0 ] );
			this.resetValidationStateClasses();
			this.showElement( this.validationMessageWrapper );
			this.setValidationStateClasses( type );
		}
	}

	resetValidationStateClasses() {
		const states = [ 'error', 'warning', 'success', 'notice' ];
		// Remove any possible prior added validation state classes
		states.forEach(
			// The following classes can be used here:
			// * cdx-text-input--status-notice
			// * cdx-text-input--status-success
			// * cdx-text-input--status-notice
			// * cdx-text-input--status-error
			( validationType ) => this.elementWrapper.classList.toggle( `cdx-text-input--status-${ validationType }`, false )
		);
		// Remove any possible prior added validation state classes
		states.forEach(
			// The following classes can be used here:
			// * cdx-message--notice
			// * cdx-message--success
			// * cdx-message--notice
			// * cdx-message--error
			( validationType ) => this.validationMessage.classList.toggle( `cdx-message--${ validationType }`, false )
		);
	}

	setValidationStateClasses( type, replaceIcon ) {
		// Flush prior replaced icons
		this.hideElement( this.validationMessageProgressIndicator );
		this.showElement( this.validationMessageIcon );
		// The following classes can be used here:
		// * cdx-text-input--status-notice
		// * cdx-text-input--status-success
		// * cdx-text-input--status-notice
		// * cdx-text-input--status-error
		this.elementWrapper.classList.add( `cdx-text-input--status-${ type }` );
		// The following classes can be used here:
		// * cdx-message--notice
		// * cdx-message--success
		// * cdx-message--notice
		// * cdx-message--error
		this.validationMessage.classList.add( `cdx-message--${ type }` );

		if ( replaceIcon ) {
			this.showElement( this.validationMessageProgressIndicator );
			this.hideElement( this.validationMessageIcon );
		}

		this.validationState = type;
	}

	showCheckFeedback() {
		this.resetValidationStateClasses();
		this.validationMessageContent.innerText = mw.message( 'available-username-check-feedback' ).text();
		this.showElement( this.validationMessageWrapper );
		this.setValidationStateClasses( 'notice', true );
	}

	validate() {
		const { value } = this.element;
		if ( this.abortController ) {
			this.abortController.abort();
		}

		if ( value === '' ) {
			this.currentValue = value;
			this.setErrors( true, [] );
			return;
		}

		this.abortController = new mw.Api.AbortController();

		return this.validator( value, this.abortController.signal )
			.then( ( { valid, messages, type } ) => {
				// TODO use forceReplacement to trigger a CSS animation when an error changes but its state doesn't
				const forceReplacement = value !== this.currentValue;
				this.currentValue = value;
				this.setErrors( valid, messages, type, forceReplacement );
			} ).catch( () => {
				this.currentValue = null;
				this.setErrors( true, [] );
			} );
	}
}

module.exports = HtmlformCheckerV2;
