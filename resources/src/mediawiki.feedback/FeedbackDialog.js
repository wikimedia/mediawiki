/**
 * @class Dialog
 * @classdesc Feedback dialog for use within the context mw.Feedback. Typically
 * constructed using {@link mw.Feedback#launch} instead of directly using the constructor.
 * @memberof mw.Feedback
 * @extends OO.ui.ProcessDialog
 *
 * @constructor
 * @description Create an instance of `mw.Feedback.Dialog`.
 * @param {Object} config Configuration object
 */
function FeedbackDialog( config ) {
	// Parent constructor
	FeedbackDialog.super.call( this, config );

	this.status = '';
	this.feedbackPageTitle = null;
	// Initialize
	this.$element.addClass( 'mwFeedback-Dialog' );
}

OO.inheritClass( FeedbackDialog, OO.ui.ProcessDialog );

/* Static properties */
FeedbackDialog.static.name = 'mwFeedbackDialog';
FeedbackDialog.static.title = mw.msg( 'feedback-dialog-title' );
FeedbackDialog.static.size = 'medium';
FeedbackDialog.static.actions = [
	{
		action: 'submit',
		label: mw.msg( 'feedback-submit' ),
		flags: [ 'primary', 'progressive' ]
	},
	{
		action: 'external',
		label: mw.msg( 'feedback-external-bug-report-button' ),
		flags: 'progressive'
	},
	{
		action: 'cancel',
		label: mw.msg( 'feedback-cancel' ),
		flags: 'safe'
	}
];

/**
 * Initializes the dialog.
 *
 * @ignore
 * @inheritdoc
 */
FeedbackDialog.prototype.initialize = function () {
	// Parent method
	FeedbackDialog.super.prototype.initialize.call( this );

	this.feedbackPanel = new OO.ui.PanelLayout( {
		scrollable: false,
		expanded: false,
		padded: true
	} );

	// Feedback form
	this.feedbackMessageLabel = new OO.ui.LabelWidget( {
		classes: [ 'mw-feedbackDialog-welcome-message' ]
	} );
	this.feedbackSubjectInput = new OO.ui.TextInputWidget( {
		indicator: 'required'
	} );
	this.feedbackMessageInput = new OO.ui.MultilineTextInputWidget( {
		autosize: true
	} );
	const feedbackSubjectFieldLayout = new OO.ui.FieldLayout( this.feedbackSubjectInput, {
		label: mw.msg( 'feedback-subject' )
	} );
	const feedbackMessageFieldLayout = new OO.ui.FieldLayout( this.feedbackMessageInput, {
		label: mw.msg( 'feedback-message' )
	} );
	const feedbackFieldsetLayout = new OO.ui.FieldsetLayout( {
		items: [ feedbackSubjectFieldLayout, feedbackMessageFieldLayout ],
		classes: [ 'mw-feedbackDialog-feedback-form' ]
	} );

	// Useragent terms of use
	this.useragentCheckbox = new OO.ui.CheckboxInputWidget();
	this.useragentFieldLayout = new OO.ui.FieldLayout( this.useragentCheckbox, {
		classes: [ 'mw-feedbackDialog-feedback-terms' ],
		align: 'inline'
	} );

	const $termsOfUseLabelText = $( '<p>' ).append( mw.message( 'feedback-termsofuse' ).parseDom() );
	$termsOfUseLabelText.find( 'a' ).attr( 'target', '_blank' );
	const termsOfUseLabel = new OO.ui.LabelWidget( {
		classes: [ 'mw-feedbackDialog-feedback-termsofuse' ],
		label: $termsOfUseLabelText
	} );

	this.feedbackPanel.$element.append(
		this.feedbackMessageLabel.$element,
		feedbackFieldsetLayout.$element,
		this.useragentFieldLayout.$element,
		termsOfUseLabel.$element
	);

	// Events
	this.feedbackSubjectInput.connect( this, { change: 'validateFeedbackForm' } );
	this.feedbackMessageInput.connect( this, { change: 'validateFeedbackForm' } );
	this.feedbackMessageInput.connect( this, { change: 'updateSize' } );
	this.useragentCheckbox.connect( this, { change: 'validateFeedbackForm' } );

	this.$body.append( this.feedbackPanel.$element );
};

/**
 * Validate the feedback form.
 *
 * @method validateFeedbackForm
 * @memberof mw.Feedback.Dialog
 */
FeedbackDialog.prototype.validateFeedbackForm = function () {
	const isValid = (
		(
			!this.useragentMandatory ||
			this.useragentCheckbox.isSelected()
		) &&
		this.feedbackSubjectInput.getValue()
	);

	this.actions.setAbilities( { submit: isValid } );
};

/**
 * @inheritdoc
 * @ignore
 */
FeedbackDialog.prototype.getBodyHeight = function () {
	return this.feedbackPanel.$element.outerHeight( true );
};

/**
 * @inheritdoc
 * @ignore
 */
FeedbackDialog.prototype.getSetupProcess = function ( data ) {
	return FeedbackDialog.super.prototype.getSetupProcess.call( this, data )
		.next( () => {
			// Get the URL of the target page, we want to use that in links in the intro
			// and in the success dialog
			if ( data.foreignApi ) {
				return data.foreignApi.get( {
					action: 'query',
					prop: 'info',
					inprop: 'url',
					formatversion: 2,
					titles: data.settings.title.getPrefixedText()
				} ).then( ( response ) => {
					this.feedbackPageUrl = OO.getProp( response, 'query', 'pages', 0, 'canonicalurl' );
				} );
			} else {
				this.feedbackPageUrl = data.settings.title.getUrl();
			}
		} )
		.next( () => {
			const settings = data.settings;
			data.contents = data.contents || {};

			// Prefill subject/message
			this.feedbackSubjectInput.setValue( data.contents.subject );
			this.feedbackMessageInput.setValue( data.contents.message );

			this.status = '';
			this.messagePosterPromise = settings.messagePosterPromise;
			this.setBugReportLink( settings.bugsTaskSubmissionLink );
			this.feedbackPageTitle = settings.title;
			this.feedbackPageName = settings.title.getMainText();

			// Useragent checkbox
			if ( settings.useragentCheckbox.show ) {
				this.useragentFieldLayout.setLabel( settings.useragentCheckbox.message );
			}

			this.useragentMandatory = settings.useragentCheckbox.mandatory;
			this.useragentFieldLayout.toggle( settings.useragentCheckbox.show );

			const $link = $( '<a>' )
				.attr( 'href', this.feedbackPageUrl )
				.attr( 'target', '_blank' )
				.text( this.feedbackPageName );
			this.feedbackMessageLabel.setLabel(
				mw.message( 'feedback-dialog-intro', $link ).parseDom()
			);

			this.validateFeedbackForm();
		} );
};

/**
 * @inheritdoc
 * @ignore
 */
FeedbackDialog.prototype.getReadyProcess = function ( data ) {
	return FeedbackDialog.super.prototype.getReadyProcess.call( this, data )
		.next( () => {
			this.feedbackSubjectInput.focus();
		} );
};

/**
 * @inheritdoc
 * @ignore
 */
FeedbackDialog.prototype.getActionProcess = function ( action ) {
	if ( action === 'cancel' ) {
		return new OO.ui.Process( () => {
			this.close( { action: action } );
		} );
	} else if ( action === 'external' ) {
		return new OO.ui.Process( () => {
			// Open in a new window
			window.open( this.getBugReportLink(), '_blank' );
			// Close the dialog
			this.close();
		} );
	} else if ( action === 'submit' ) {
		return new OO.ui.Process( () => {
			const userAgentMessage = ':' +
					'<small>' +
					mw.msg( 'feedback-useragent' ) +
					' ' +
					mw.html.escape( navigator.userAgent ) +
					'</small>\n\n',
				subject = this.feedbackSubjectInput.getValue();
			let message = this.feedbackMessageInput.getValue();

			// Add user agent if checkbox is selected
			if ( this.useragentCheckbox.isSelected() ) {
				message = userAgentMessage + message;
			}

			// Post the message
			return this.messagePosterPromise.then( ( poster ) => this.postMessage( poster, subject, message ), () => {
				this.status = 'error4';
				mw.log.warn( 'Feedback report failed because MessagePoster could not be fetched' );
			} ).then( () => {
				this.close();
			}, () => {
				throw this.getErrorMessage();
			} );
		} );
	}
	// Fallback to parent handler
	return FeedbackDialog.super.prototype.getActionProcess.call( this, action );
};

/**
 * Returns an error message for the current status.
 *
 * @private
 *
 * @return {OO.ui.Error}
 */
FeedbackDialog.prototype.getErrorMessage = function () {
	if ( this.$statusFromApi ) {
		return new OO.ui.Error( this.$statusFromApi );
	}
	// The following messages can be used here:
	// * feedback-error1
	// * feedback-error4
	return new OO.ui.Error( mw.msg( 'feedback-' + this.status ) );
};

/**
 * Posts the message
 *
 * @private
 *
 * @param {mw.messagePoster.MessagePoster} poster Poster implementation used to leave feedback
 * @param {string} subject Subject of message
 * @param {string} message Body of message
 * @return {jQuery.Promise} Promise representing success of message posting action
 */
FeedbackDialog.prototype.postMessage = function ( poster, subject, message ) {
	return poster.post(
		subject,
		message
	).then( () => {
		this.status = 'submitted';
	}, ( mainCode, secondaryCode, details ) => {
		if ( mainCode === 'api-fail' ) {
			if ( secondaryCode === 'http' ) {
				this.status = 'error3';
				// ajax request failed
				mw.log.warn( 'Feedback report failed with HTTP error: ' + details.textStatus );
			} else {
				this.status = 'error2';
				mw.log.warn( 'Feedback report failed with API error: ' + secondaryCode );
			}
			this.$statusFromApi = ( new mw.Api() ).getErrorMessage( details );
		} else {
			this.status = 'error1';
		}
		throw this.getErrorMessage();
	} );
};

/**
 * @ignore
 * @inheritdoc
 */
FeedbackDialog.prototype.getTeardownProcess = function ( data ) {
	return FeedbackDialog.super.prototype.getTeardownProcess.call( this, data )
		.first( () => {
			this.emit( 'submit', this.status, this.feedbackPageName, this.feedbackPageUrl );
			// Cleanup
			this.status = '';
			this.feedbackPageTitle = null;
			this.feedbackSubjectInput.setValue( '' );
			this.feedbackMessageInput.setValue( '' );
			this.useragentCheckbox.setSelected( false );
		} );
};

/**
 * Set the bug report link.
 *
 * @method setBugReportLink
 * @param {string} link Link to the external bug report form
 * @memberof mw.Feedback.Dialog
 */
FeedbackDialog.prototype.setBugReportLink = function ( link ) {
	this.bugReportLink = link;
};

/**
 * Get the bug report link.
 *
 * @method getBugReportLink
 * @return {string} Link to the external bug report form
 * @memberof mw.Feedback.Dialog
 */
FeedbackDialog.prototype.getBugReportLink = function () {
	return this.bugReportLink;
};

module.exports = FeedbackDialog;
