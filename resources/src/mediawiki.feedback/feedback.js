/*!
 * mediawiki.feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @author Moriel Schottlender, 2015
 * @since 1.19
 */
( function () {
	/**
	 * This is a way of getting simple feedback from users. It's useful
	 * for testing new features -- users can give you feedback without
	 * the difficulty of opening a whole new talk page. For this reason,
	 * it also tends to collect a wider range of both positive and negative
	 * comments. However you do need to tend to the feedback page. It will
	 * get long relatively quickly, and you often get multiple messages
	 * reporting the same issue.
	 *
	 * It takes the form of thing on your page which, when clicked, opens a small
	 * dialog box. Submitting that dialog box appends its contents to a
	 * wiki page that you specify, as a new section.
	 *
	 * This feature works with any content model that defines a
	 * `mw.messagePoster.MessagePoster`.
	 *
	 * Minimal usage example:
	 *
	 *     var feedback = new mw.Feedback();
	 *     $( '#myButton' ).click( function () { feedback.launch(); } );
	 *
	 * You can also launch the feedback form with a prefilled subject and body.
	 * See the docs for the #launch() method.
	 *
	 * @class
	 * @constructor
	 * @param {Object} [config] Configuration object
	 * @cfg {mw.Title} [title="Feedback"] The title of the page where you collect
	 *  feedback.
	 * @cfg {string} [apiUrl] api.php URL if the feedback page is on another wiki
	 * @cfg {string} [dialogTitleMessageKey="feedback-dialog-title"] Message key for the
	 *  title of the dialog box
	 * @cfg {mw.Uri|string} [bugsLink="//phabricator.wikimedia.org/maniphest/task/edit/form/1/"] URL where
	 *  bugs can be posted
	 * @cfg {mw.Uri|string} [bugsListLink="//phabricator.wikimedia.org/maniphest/query/advanced"] URL
	 *  where bugs can be listed
	 * @cfg {boolean} [showUseragentCheckbox=false] Show a Useragent agreement checkbox as part of the form.
	 * @cfg {boolean} [useragentCheckboxMandatory=false] Make the Useragent checkbox mandatory.
	 * @cfg {string|jQuery} [useragentCheckboxMessage] Supply a custom message for the useragent checkbox.
	 *  defaults to the message 'feedback-terms'.
	 */
	mw.Feedback = function MwFeedback( config ) {
		config = config || {};

		this.dialogTitleMessageKey = config.dialogTitleMessageKey || 'feedback-dialog-title';

		// Feedback page title
		this.feedbackPageTitle = config.title || new mw.Title( 'Feedback' );

		this.messagePosterPromise = mw.messagePoster.factory.create( this.feedbackPageTitle, config.apiUrl );
		this.foreignApi = config.apiUrl ? new mw.ForeignApi( config.apiUrl ) : null;

		// Links
		this.bugsTaskSubmissionLink = config.bugsLink || '//phabricator.wikimedia.org/maniphest/task/edit/form/1/';
		this.bugsTaskListLink = config.bugsListLink || '//phabricator.wikimedia.org/maniphest/query/advanced';

		// Terms of use
		this.useragentCheckboxShow = !!config.showUseragentCheckbox;
		this.useragentCheckboxMandatory = !!config.useragentCheckboxMandatory;
		this.useragentCheckboxMessage = config.useragentCheckboxMessage ||
			$( '<p>' ).append( mw.msg( 'feedback-terms' ) );

		// Message dialog
		this.thankYouDialog = new OO.ui.MessageDialog();
	};

	/* Initialize */
	OO.initClass( mw.Feedback );

	/* Static Properties */
	mw.Feedback.static.windowManager = null;
	mw.Feedback.static.dialog = null;

	/* Methods */

	/**
	 * Respond to dialog submit event. If the information was
	 * submitted successfully, open a MessageDialog to thank the user.
	 *
	 * @param {string} status A status of the end of operation
	 *  of the main feedback dialog. Empty if the dialog was
	 *  dismissed with no action or the user followed the button
	 *  to the external task reporting site.
	 * @param {string} feedbackPageName
	 * @param {string} feedbackPageUrl
	 */
	mw.Feedback.prototype.onDialogSubmit = function ( status, feedbackPageName, feedbackPageUrl ) {
		var dialogConfig;

		if ( status !== 'submitted' ) {
			return;
		}

		dialogConfig = {
			title: mw.msg( 'feedback-thanks-title' ),
			message: $( '<span>' ).msg(
				'feedback-thanks',
				feedbackPageName,
				$( '<a>' ).attr( {
					target: '_blank',
					href: feedbackPageUrl
				} )
			),
			actions: [
				{
					action: 'accept',
					label: mw.msg( 'feedback-close' ),
					flags: 'primary'
				}
			]
		};

		// Show the message dialog
		this.constructor.static.windowManager.openWindow(
			this.thankYouDialog,
			dialogConfig
		);
	};

	/**
	 * Modify the display form, and then open it, focusing interface on the subject.
	 *
	 * @param {Object} [contents] Prefilled contents for the feedback form.
	 * @param {string} [contents.subject] The subject of the feedback, as plaintext
	 * @param {string} [contents.message] The content of the feedback, as wikitext
	 */
	mw.Feedback.prototype.launch = function ( contents ) {
		// Dialog
		if ( !this.constructor.static.dialog ) {
			this.constructor.static.dialog = new mw.Feedback.Dialog();
			this.constructor.static.dialog.connect( this, { submit: 'onDialogSubmit' } );
		}
		if ( !this.constructor.static.windowManager ) {
			this.constructor.static.windowManager = new OO.ui.WindowManager();
			this.constructor.static.windowManager.addWindows( [
				this.constructor.static.dialog,
				this.thankYouDialog
			] );
			$( 'body' )
				.append( this.constructor.static.windowManager.$element );
		}
		// Open the dialog
		this.constructor.static.windowManager.openWindow(
			this.constructor.static.dialog,
			{
				title: mw.msg( this.dialogTitleMessageKey ),
				foreignApi: this.foreignApi,
				settings: {
					messagePosterPromise: this.messagePosterPromise,
					title: this.feedbackPageTitle,
					dialogTitleMessageKey: this.dialogTitleMessageKey,
					bugsTaskSubmissionLink: this.bugsTaskSubmissionLink,
					bugsTaskListLink: this.bugsTaskListLink,
					useragentCheckbox: {
						show: this.useragentCheckboxShow,
						mandatory: this.useragentCheckboxMandatory,
						message: this.useragentCheckboxMessage
					}
				},
				contents: contents
			}
		);
	};

	/**
	 * mw.Feedback Dialog
	 *
	 * @class
	 * @extends OO.ui.ProcessDialog
	 *
	 * @constructor
	 * @param {Object} config Configuration object
	 */
	mw.Feedback.Dialog = function mwFeedbackDialog( config ) {
		// Parent constructor
		mw.Feedback.Dialog.parent.call( this, config );

		this.status = '';
		this.feedbackPageTitle = null;
		// Initialize
		this.$element.addClass( 'mwFeedback-Dialog' );
	};

	OO.inheritClass( mw.Feedback.Dialog, OO.ui.ProcessDialog );

	/* Static properties */
	mw.Feedback.Dialog.static.name = 'mwFeedbackDialog';
	mw.Feedback.Dialog.static.title = mw.msg( 'feedback-dialog-title' );
	mw.Feedback.Dialog.static.size = 'medium';
	mw.Feedback.Dialog.static.actions = [
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
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.initialize = function () {
		var feedbackSubjectFieldLayout, feedbackMessageFieldLayout,
			feedbackFieldsetLayout, termsOfUseLabel;

		// Parent method
		mw.Feedback.Dialog.parent.prototype.initialize.call( this );

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
		feedbackSubjectFieldLayout = new OO.ui.FieldLayout( this.feedbackSubjectInput, {
			label: mw.msg( 'feedback-subject' )
		} );
		feedbackMessageFieldLayout = new OO.ui.FieldLayout( this.feedbackMessageInput, {
			label: mw.msg( 'feedback-message' )
		} );
		feedbackFieldsetLayout = new OO.ui.FieldsetLayout( {
			items: [ feedbackSubjectFieldLayout, feedbackMessageFieldLayout ],
			classes: [ 'mw-feedbackDialog-feedback-form' ]
		} );

		// Useragent terms of use
		this.useragentCheckbox = new OO.ui.CheckboxInputWidget();
		this.useragentFieldLayout = new OO.ui.FieldLayout( this.useragentCheckbox, {
			classes: [ 'mw-feedbackDialog-feedback-terms' ],
			align: 'inline'
		} );

		termsOfUseLabel = new OO.ui.LabelWidget( {
			classes: [ 'mw-feedbackDialog-feedback-termsofuse' ],
			label: $( '<p>' ).append( mw.msg( 'feedback-termsofuse' ) )
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
	 * Validate the feedback form
	 */
	mw.Feedback.Dialog.prototype.validateFeedbackForm = function () {
		var isValid = (
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
	 */
	mw.Feedback.Dialog.prototype.getBodyHeight = function () {
		return this.feedbackPanel.$element.outerHeight( true );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getSetupProcess = function ( data ) {
		return mw.Feedback.Dialog.parent.prototype.getSetupProcess.call( this, data )
			.next( function () {
				// Get the URL of the target page, we want to use that in links in the intro
				// and in the success dialog
				var dialog = this;
				if ( data.foreignApi ) {
					return data.foreignApi.get( {
						action: 'query',
						prop: 'info',
						inprop: 'url',
						formatversion: 2,
						titles: data.settings.title.getPrefixedText()
					} ).then( function ( data ) {
						dialog.feedbackPageUrl = OO.getProp( data, 'query', 'pages', 0, 'canonicalurl' );
					} );
				} else {
					this.feedbackPageUrl = data.settings.title.getUrl();
				}
			}, this )
			.next( function () {
				var $link,
					settings = data.settings;
				data.contents = data.contents || {};

				// Prefill subject/message
				this.feedbackSubjectInput.setValue( data.contents.subject );
				this.feedbackMessageInput.setValue( data.contents.message );

				this.status = '';
				this.messagePosterPromise = settings.messagePosterPromise;
				this.setBugReportLink( settings.bugsTaskSubmissionLink );
				this.feedbackPageTitle = settings.title;
				this.feedbackPageName = settings.title.getNameText();

				// Useragent checkbox
				if ( settings.useragentCheckbox.show ) {
					this.useragentFieldLayout.setLabel( settings.useragentCheckbox.message );
				}

				this.useragentMandatory = settings.useragentCheckbox.mandatory;
				this.useragentFieldLayout.toggle( settings.useragentCheckbox.show );

				$link = $( '<a>' )
					.attr( 'href', this.feedbackPageUrl )
					.attr( 'target', '_blank' )
					.text( this.feedbackPageName );
				this.feedbackMessageLabel.setLabel(
					mw.message( 'feedback-dialog-intro', $link ).parseDom()
				);

				this.validateFeedbackForm();
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getReadyProcess = function ( data ) {
		return mw.Feedback.Dialog.parent.prototype.getReadyProcess.call( this, data )
			.next( function () {
				this.feedbackSubjectInput.focus();
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getActionProcess = function ( action ) {
		if ( action === 'cancel' ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		} else if ( action === 'external' ) {
			return new OO.ui.Process( function () {
				// Open in a new window
				window.open( this.getBugReportLink(), '_blank' );
				// Close the dialog
				this.close();
			}, this );
		} else if ( action === 'submit' ) {
			return new OO.ui.Process( function () {
				var fb = this,
					userAgentMessage = ':' +
						'<small>' +
						mw.msg( 'feedback-useragent' ) +
						' ' +
						mw.html.escape( navigator.userAgent ) +
						'</small>\n\n',
					subject = this.feedbackSubjectInput.getValue(),
					message = this.feedbackMessageInput.getValue();

				// Add user agent if checkbox is selected
				if ( this.useragentCheckbox.isSelected() ) {
					message = userAgentMessage + message;
				}

				// Post the message
				return this.messagePosterPromise.then( function ( poster ) {
					return fb.postMessage( poster, subject, message );
				}, function () {
					fb.status = 'error4';
					mw.log.warn( 'Feedback report failed because MessagePoster could not be fetched' );
				} ).then( function () {
					fb.close();
				}, function () {
					return fb.getErrorMessage();
				} );
			}, this );
		}
		// Fallback to parent handler
		return mw.Feedback.Dialog.parent.prototype.getActionProcess.call( this, action );
	};

	/**
	 * Returns an error message for the current status.
	 *
	 * @private
	 *
	 * @return {OO.ui.Error}
	 */
	mw.Feedback.Dialog.prototype.getErrorMessage = function () {
		// Messages: feedback-error1, feedback-error2, feedback-error3, feedback-error4
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
	mw.Feedback.Dialog.prototype.postMessage = function ( poster, subject, message ) {
		var fb = this;

		return poster.post(
			subject,
			message
		).then( function () {
			fb.status = 'submitted';
		}, function ( mainCode, secondaryCode, details ) {
			if ( mainCode === 'api-fail' ) {
				if ( secondaryCode === 'http' ) {
					fb.status = 'error3';
					// ajax request failed
					mw.log.warn( 'Feedback report failed with HTTP error: ' + details.textStatus );
				} else {
					fb.status = 'error2';
					mw.log.warn( 'Feedback report failed with API error: ' + secondaryCode );
				}
			} else {
				fb.status = 'error1';
			}
		} );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Feedback.Dialog.parent.prototype.getTeardownProcess.call( this, data )
			.first( function () {
				this.emit( 'submit', this.status, this.feedbackPageName, this.feedbackPageUrl );
				// Cleanup
				this.status = '';
				this.feedbackPageTitle = null;
				this.feedbackSubjectInput.setValue( '' );
				this.feedbackMessageInput.setValue( '' );
				this.useragentCheckbox.setSelected( false );
			}, this );
	};

	/**
	 * Set the bug report link
	 *
	 * @param {string} link Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.setBugReportLink = function ( link ) {
		this.bugReportLink = link;
	};

	/**
	 * Get the bug report link
	 *
	 * @return {string} Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.getBugReportLink = function () {
		return this.bugReportLink;
	};

}() );
