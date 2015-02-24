/*!
 * mediawiki.feedback
 *
 * @author Ryan Kaldari, 2010
 * @author Neil Kandalgaonkar, 2010-11
 * @author Moriel Schottlender, 2015
 * @since 1.19
 */
/*jshint es5:true */
/*global OO*/
( function ( mw, $ ) {
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
	 * This feature works with classic MediaWiki pages
	 * and is not compatible with LiquidThreads or Flow.
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
	 * @param {Object} [config]
	 * @cfg {mw.Api} [config.api] if omitted, will just create a standard API
	 * @cfg {mw.Title} [config.title="Feedback"] The title of the page where you collect
	 * feedback.
	 * @cfg {string} [config.dialogTitleMessageKey="feedback-submit"] Message key for the
	 * title of the dialog box
	 * @cfg {string} [config.bugsLink="//bugzilla.wikimedia.org/enter_bug.cgi"] URL where
	 * bugs can be posted
	 * @cfg {mw.Uri|string} [config.bugsListLink="//bugzilla.wikimedia.org/query.cgi"]
	 * URL where bugs can be listed
	 */
	mw.Feedback = function MwFeedback( config ) {
		config = config || {};

		this.api = config.api || new mw.Api();
		this.feedbackPageTitle = config.title || new mw.Title( 'Feedback' );
		this.dialogTitleMessageKey = config.dialogTitleMessageKey || 'feedback-publish';
		this.bugsLink = config.bugsLink || '//bugzilla.wikimedia.org/enter_bug.cgi';
		this.bugsListLink = config.bugsListLink || '//bugzilla.wikimedia.org/query.cgi';
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
	 * submitted, either successfully or with an error, open
	 * a MessageDialog to thank the user.
	 */
	mw.Feedback.prototype.onDialogSubmit = function ( status ) {
		var dialogConfig = {};
		switch ( status ) {
			case 'submitted':
				dialogConfig = {
					title: mw.msg( 'feedback-thanks-title' ),
					message: $( '<span>' ).append(
						mw.message(
							'feedback-thanks',
							this.feedbackPageTitle.getNameText(),
							$( '<a>' )
								.attr( {
									target: '_blank',
									href: this.feedbackPageTitle.getUrl()
								} )
						).parse()
					),
					actions: [
						{
							action: 'accept',
							label: mw.msg( 'feedback-close' ),
							flags: 'primary'
						}
					]
				};
				break;
			case 'error1':
			case 'error2':
			case 'error3':
				dialogConfig = {
					title: mw.msg( 'feedback-error-title' ),
					message: mw.msg( 'feedback-' + status ),
					actions: [
						{
							action: 'accept',
							label: mw.msg( 'feedback-close' ),
							flags: 'primary'
						}
					]
				};
				break;
		}

		// Show the message dialog
		if ( !$.isEmptyObject( dialogConfig ) ) {
			this.constructor.static.windowManager.openWindow(
				this.thankYouDialog,
				dialogConfig
			);
		}
	};
	/**
	 * Modify the display form, and then open it, focusing interface on the subject.
	 *
	 * @param {Object} [contents] Prefilled contents for the feedback form.
	 * @param {string} [contents.subject] The subject of the feedback
	 * @param {string} [contents.message] The content of the feedback
	 */
	mw.Feedback.prototype.launch = function ( contents ) {
		// Dialog
		if ( !this.constructor.static.dialog ) {
			this.constructor.static.dialog = new mw.Feedback.Dialog( this );
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
				settings: {
					api: this.api,
					title: this.feedbackPageTitle,
					dialogTitleMessageKey: this.dialogTitleMessageKey,
					bugsLink: this.bugsLink,
					bugsListLink: this.bugsListLink,
					thankyouDialog: this.thankYouDialog
				},
				content: contents
			}
		);
	};

	/**
	 * mw.Feedback Dialog
	 * @param {Object} config Configuration object
	 */
	mw.Feedback.Dialog = function mwFeedbackDialog( feedback, config ) {
		// Parent constructor
		mw.Feedback.Dialog.super.call( this, config );

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
			flags: [ 'primary', 'constructive' ],
			modes: 'feedback'
		},
		{
			action: 'back',
			label: mw.msg( 'feedback-back' ),
			flags: 'safe',
			modes: 'reportBug'
		},
		{
			action: 'close',
			label: mw.msg( 'feedback-close' ),
			flags: 'safe',
			modes: 'thanks'
		},
		{
			action: 'cancel',
			label: mw.msg( 'feedback-cancel' ),
			flags: 'safe',
			modes: 'feedback'
		}
	];

	/**
	 * @inheritDoc
	 */
	mw.Feedback.Dialog.prototype.initialize = function () {
		var feedbackSubjectFieldLayout, feedbackMessageFieldLayout, termsOfUseLabel,
			feedbackFieldsetLayout, doubleCheckFieldset, termsFieldLayout;

		// Parent method
		mw.Feedback.Dialog.super.prototype.initialize.call( this );

		this.panels = new OO.ui.StackLayout( {
			scrollable: false,
			continuous: false,
			expanded: false
		} );
		this.feedbackPanel = new OO.ui.PanelLayout( {
			scrollable: false,
			expanded: false,
			padded: true
		} );
		this.reportBugPanel = new OO.ui.PanelLayout( {
			scrollable: false,
			expanded: false,
			padded: true
		} );
		this.$spinner = $( '<div>' )
			.addClass( 'feedback-spinner' );

		// Feedback form
		this.feedbackMessageLabel = new OO.ui.LabelWidget( {
			classes: [ 'mwFeedbackDialog-welcome-message' ]
		} );
		this.feedbackSubjectInput = new OO.ui.TextInputWidget( {
			multiline: false
		} );
		this.feedbackMessageInput = new OO.ui.TextInputWidget( {
			multiline: true
		} );
		feedbackSubjectFieldLayout = new OO.ui.FieldLayout( this.feedbackSubjectInput, {
			label: mw.msg( 'feedback-subject' )
		} );
		feedbackMessageFieldLayout = new OO.ui.FieldLayout( this.feedbackMessageInput, {
			label: mw.msg( 'feedback-message' )
		} );
		feedbackFieldsetLayout = new OO.ui.FieldsetLayout( {
			items: [ feedbackSubjectFieldLayout, feedbackMessageFieldLayout	],
			classes: [ 'mwFeedbackDialog-feedback-form' ]
		} );
		// Terms of service checkbox
		this.termsCheckbox = new OO.ui.CheckboxInputWidget();
		termsFieldLayout = new OO.ui.FieldLayout( this.termsCheckbox, {
			classes: [ 'mwFeedbackDialog-feedback-terms' ],
			align: 'inline',
			label: mw.msg( 'feedback-terms' )
		} );
		termsOfUseLabel = new OO.ui.LabelWidget( {
			label: $( '<span>' ).append( mw.message( 'feedback-termsofuse' ).parse() ),
			classes: [ 'mwFeedbackDialog-feedback-termsofuse' ]
		} );
		termsFieldLayout.$element.append( termsOfUseLabel.$element );

		// Report bug button (goes inside the label)
		this.reportBugButton = new OO.ui.ButtonWidget( {
			classes: [ 'mwFeedbackDialog-feedback-reportbug-button' ],
			framed: false
		} );

		// HACK: Since we don't want to outright change the i18n message,
		// we will construct it the same way as before, but then replace
		// the bug report link with an OOUI button.
		// TODO: We should, at some point, replace the i18n message an
		// the way it is displayed and constructed with a better design
		// for the entire feedback dialog experience.
		this.feedbackMessageLabel.setLabel(
			$( '<span>')
				.append(
					mw.message(
						'feedback-bugornote',
						$( '<span>' )
							.addClass( 'mwFeedbackDialog-bugReportLink' ),
						// Empty string that will be replaced
						'',
						$( '<a>' )
							.attr( {
								target: '_blank'
							} )
							.addClass( 'mwFeedbackDialog-bugListLink' )
					).parse()
				)
		);

		// Report bug panel
		this.doubleCheckLabel = new OO.ui.LabelWidget();
		this.doubleCheckButton = new OO.ui.ButtonWidget( {
			classes: [ 'mwFeedbackDialog-feedback-doublecheck-button' ],
			label: mw.msg( 'feedback-bugnew' )
		} );
		doubleCheckFieldset = new OO.ui.FieldsetLayout( {
			items: [ this.doubleCheckButton	],
			classes: [ 'mwFeedbackDialog-doublecheck-fieldset' ]
		} );

		// Initialization
		this.reportBugPanel.$element.append(
			this.doubleCheckLabel.$element,
			doubleCheckFieldset.$element
		);
		this.feedbackPanel.$element.append(
			this.feedbackMessageLabel.$element,
			feedbackFieldsetLayout.$element,
			termsFieldLayout.$element
		);

		this.panels.addItems( [
			this.feedbackPanel,
			this.reportBugPanel
		] );

		// Events
		this.reportBugButton.connect( this, { click: 'onReportBugButtonClick' } );
		this.doubleCheckButton.connect( this, { click: 'onDoubleCheckButtonClick' } );
		this.feedbackSubjectInput.connect( this, { change: 'validateFeedbackForm' } );
		this.feedbackMessageInput.connect( this, { change: 'validateFeedbackForm' } );
		this.termsCheckbox.connect( this, { change: 'validateFeedbackForm' } );

		this.$body.append( this.panels.$element );
	};

	/**
	 * Respond to report bug button click
	 */
	mw.Feedback.Dialog.prototype.onReportBugButtonClick = function () {
		this.switchPanels( 'reportBug' );
	};

	/**
	 * Respond to double-check button click
	 */
	mw.Feedback.Dialog.prototype.onDoubleCheckButtonClick = function () {
		// Open in a new window
		window.open( this.getBugReportLink(), '_blank' );
		// Close the dialog
		this.close();
	};

	/**
	 * Validate the feedback form
	 */
	mw.Feedback.Dialog.prototype.validateFeedbackForm = function () {
		var isValid = (
				this.termsCheckbox.isSelected() &&
				(
					!!this.feedbackMessageInput.getValue() ||
					!!this.feedbackSubjectInput.getValue()
				)
			);

		this.actions.setAbilities( { submit:  isValid } );
	};

	/**
	 * @inheritDoc
	 */
	mw.Feedback.Dialog.prototype.getBodyHeight = function () {
		return this.panels.$element.outerHeight( true );
	};

	/**
	 * Switch between panels
	 * @param {string} panel Panel name
	 */
	mw.Feedback.Dialog.prototype.switchPanels = function ( panel ) {
		switch ( panel ) {
			case 'reportBug':
				this.panels.setItem( this.reportBugPanel );
				this.actions.setMode( 'reportBug' );
				break;
			default:
			case 'feedback':
				this.panels.setItem( this.feedbackPanel );
				this.actions.setMode( 'feedback' );
				break;
		}
	};

	/**
	 * @inheritDoc
	 */
	mw.Feedback.Dialog.prototype.getSetupProcess = function ( data ) {
		var feedback = this;
		return mw.Feedback.Dialog.super.prototype.getSetupProcess.call( this, data )
			.next( function () {
				var settings = data.settings;
				data.contents = data.contents || {};

				this.switchPanels( 'feedback' );

				this.feedbackSubjectInput.setValue( data.contents.subject );
				this.feedbackMessageInput.setValue( data.contents.message );

				this.status = '';
				this.api = settings.api;
				this.feedbackPageTitle = settings.title;

				this.feedbackPageName = settings.title.getNameText();
				this.feedbackPageUrl = settings.title.getUrl();

				// Replace the details for the feedback page
				this.feedbackMessageLabel.$element.find( '.mwFeedbackDialog-bugListLink' )
					.attr( 'href', this.feedbackPageUrl )
					.text( this.feedbackPageName );

				// Replace the external bug report button
				this.feedbackMessageLabel.$element.find( '.mwFeedbackDialog-bugReportLink' )
					.replaceWith( function () {
						feedback.reportBugButton.setLabel( $( this ).text() );
						return feedback.reportBugButton.$element;
					} );

				// Update the bug list link in the double check panel label
				this.doubleCheckLabel.setLabel(
					$( '<span>')
						.append(
							mw.message(
								'feedback-bugcheck',
								$( '<a>' )
									.attr( {
										href: settings.bugsListLink,
										target: '_blank'
									} )
							).parse()
						)
				);

				// Update the link to report a new bug in phabricator
				this.setBugReportLink( settings.bugsLink );

				this.validateFeedbackForm();
			}, this );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getReadyProcess = function ( data ) {
		return mw.Feedback.Dialog.super.prototype.getReadyProcess.call( this, data )
			.next( function () {
				this.feedbackSubjectInput.focus();
			}, this );
	};

	/**
	 * @inheritDoc
	 */
	mw.Feedback.Dialog.prototype.getActionProcess = function ( action ) {
		if ( action === 'cancel' ) {
			return new OO.ui.Process( function () {
				this.close( { action: action } );
			}, this );
		} else if ( action === 'back' ) {
			return new OO.ui.Process( function () {
				this.switchPanels( 'feedback' );
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
					message = userAgentMessage +
						this.feedbackMessageInput.getValue();

				// Add signature if needed
				if ( message.indexOf( '~~~' ) === -1 ) {
					message += '\n\n~~~~';
				}
				// Post the message, resolving redirects
				this.api.newSection(
					this.feedbackPageTitle,
					subject,
					message,
					{ redirect: true }
				)
				.done( function ( result ) {
					if ( result.edit.result === 'Success' ) {
						fb.status = 'submitted';
					} else {
						fb.status = 'error1';
					}
				} )
				.fail( function ( code, result ) {
					if ( code === 'http' ) {
						fb.status = 'error3';
						// ajax request failed
						mw.log.warn( 'Feedback report failed with HTTP error: ' +  result.textStatus );
					} else {
						fb.status = 'error2';
						mw.log.warn( 'Feedback report failed with API error: ' +  code );
					}
				} );

				this.close();
			}, this );
		}
		// Fallback to parent handler
		return mw.Feedback.Dialog.super.prototype.getActionProcess.call( this, action );
	};

	/**
	 * @inheritdoc
	 */
	mw.Feedback.Dialog.prototype.getTeardownProcess = function ( data ) {
		return mw.Feedback.Dialog.super.prototype.getTeardownProcess.call( this, data )
			.first( function () {
				this.emit( 'submit', this.status, this.feedbackPageName, this.feedbackPageUrl );
				// Cleanup
				this.status = '';
				this.feedbackPageTitle = null;
				this.feedbackSubjectInput.setValue( '' );
				this.feedbackMessageInput.setValue( '' );
				this.termsCheckbox.setSelected( false );
			}, this );
	};

	/**
	 * Set the bug report link
	 * @param {string} link Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.setBugReportLink = function ( link ) {
		this.bugReportLink = link;
	};

	/**
	 * Get the bug report link
	 * @returns {string} Link to the external bug report form
	 */
	mw.Feedback.Dialog.prototype.getBugReportLink = function () {
		return this.bugReportLink;
	};

}( mediaWiki, jQuery ) );
