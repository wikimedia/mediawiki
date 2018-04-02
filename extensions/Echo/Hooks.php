<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

class EchoHooks {
	public static function registerExtension() {
		global $wgNotificationSender, $wgPasswordSender, $wgAllowHTMLEmail,
			$wgEchoNotificationCategories, $wgDefaultUserOptions;

		$wgNotificationSender = $wgPasswordSender;

		if ( $wgAllowHTMLEmail ) {
			$wgDefaultUserOptions['echo-email-format'] = 'html'; /*EchoHooks::EMAIL_FORMAT_HTML*/
		} else {
			$wgDefaultUserOptions['echo-email-format'] = 'plain-text'; /*EchoHooks::EMAIL_FORMAT_PLAIN_TEXT*/
		}

		// Set all of the events to notify by web but not email by default (won't affect events that don't email)
		foreach ( $wgEchoNotificationCategories as $category => $categoryData ) {
			$wgDefaultUserOptions["echo-subscriptions-email-{$category}"] = false;
			$wgDefaultUserOptions["echo-subscriptions-web-{$category}"] = true;
		}

		// most settings default to web on, email off, but override these
		$wgDefaultUserOptions['echo-subscriptions-email-system'] = true;
		$wgDefaultUserOptions['echo-subscriptions-email-user-rights'] = true;
		$wgDefaultUserOptions['echo-subscriptions-web-article-linked'] = false;
		$wgDefaultUserOptions['echo-subscriptions-web-mention-failure'] = false;
		$wgDefaultUserOptions['echo-subscriptions-web-mention-success'] = false;
	}

	/**
	 * Initialize Echo extension with necessary data, this function is invoked
	 * from $wgExtensionFunctions
	 */
	public static function initEchoExtension() {
		global $wgEchoNotifications, $wgEchoNotificationCategories, $wgEchoNotificationIcons,
			$wgEchoEventLoggingSchemas, $wgEchoMentionStatusNotifications, $wgAllowArticleReminderNotification, $wgAPIModules;

		// allow extensions to define their own event
		Hooks::run( 'BeforeCreateEchoEvent', [ &$wgEchoNotifications, &$wgEchoNotificationCategories, &$wgEchoNotificationIcons ] );

		// Only allow mention status notifications when enabled
		if ( !$wgEchoMentionStatusNotifications ) {
			unset( $wgEchoNotificationCategories['mention-failure'] );
			unset( $wgEchoNotificationCategories['mention-success'] );
		}

		// Only allow article reminder notifications when enabled
		if ( !$wgAllowArticleReminderNotification ) {
			unset( $wgEchoNotificationCategories['article-reminder'] );
			unset( $wgAPIModules['echoarticlereminder'] );
		}

		// turn schema off if eventLogging is not enabled
		if ( !ExtensionRegistry::getInstance()->isLoaded( 'EventLogging' ) ) {
			foreach ( $wgEchoEventLoggingSchemas as $schema => $property ) {
				if ( $property['enabled'] ) {
					$wgEchoEventLoggingSchemas[$schema]['enabled'] = false;
				}
			}
		}
	}

	public static function getNotificationSenderName() {
		global $wgNotificationSenderName;
		if ( $wgNotificationSenderName === null ) {
			$wgNotificationSenderName = wfMessage( 'emailsender' )->inContentLanguage()->text();
		}

		return $wgNotificationSenderName;
	}

	/**
	 * ResourceLoaderTestModules hook handler
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ResourceLoaderTestModules
	 *
	 * @param array &$testModules
	 * @param ResourceLoader $resourceLoader
	 * @return bool
	 */
	public static function onResourceLoaderTestModules( array &$testModules,
		ResourceLoader $resourceLoader
	) {
		global $wgResourceModules;

		$testModuleBoilerplate = [
			'localBasePath' => __DIR__,
			'remoteExtPath' => 'Echo',
		];

		// find test files for every RL module
		$prefix = 'ext.echo';
		foreach ( $wgResourceModules as $key => $module ) {
			if ( substr( $key, 0, strlen( $prefix ) ) === $prefix && isset( $module['scripts'] ) ) {
				$testFiles = [];
				foreach ( $module['scripts'] as $script ) {
					$testFile = 'tests/qunit/' . dirname( $script ) . '/test_' . basename( $script );
					// if a test file exists for a given JS file, add it
					if ( file_exists( $testModuleBoilerplate['localBasePath'] . '/' . $testFile ) ) {
						$testFiles[] = $testFile;
					}
				}
				// if test files exist for given module, create a corresponding test module
				if ( count( $testFiles ) > 0 ) {
					$testModules['qunit']["$key.tests"] = $testModuleBoilerplate + [
						'dependencies' => [ $key ],
						'scripts' => $testFiles,
					];
				}
			}
		}

		return true;
	}

	public static function onEventLoggingRegisterSchemas( array &$schemas ) {
		global $wgEchoEventLoggingSchemas;
		foreach ( $wgEchoEventLoggingSchemas as $schema => $property ) {
			if ( $property['enabled'] && $property['client'] ) {
				$schemas[$schema] = $property['revision'];
			}
		}
	}

	/**
	 * Handler for ResourceLoaderRegisterModules hook
	 * @param ResourceLoader &$resourceLoader
	 */
	public static function onResourceLoaderRegisterModules( ResourceLoader &$resourceLoader ) {
		global $wgEchoEventLoggingSchemas;

		// ext.echo.logger is used by mobile notifications as well, so be sure not to add any
		// dependencies that do not target mobile.
		$definition = [
			'scripts' => [
				'logger/mw.echo.Logger.js',
			],
			'dependencies' => [
				'oojs'
			],
			'localBasePath' => __DIR__ . '/modules',
			'remoteExtPath' => 'Echo/modules',
			'targets' => [ 'desktop', 'mobile' ],
		];

		$hasSchemas = false;
		foreach ( $wgEchoEventLoggingSchemas as $schema => $property ) {
			if ( $property['enabled'] && $property['client'] ) {
				$definition['dependencies'][] = 'schema.' . $schema;
				$hasSchemas = true;
			}
		}

		if ( $hasSchemas ) {
			$definition['dependencies'][] = 'ext.eventLogging';
		}

		$resourceLoader->register( 'ext.echo.logger', $definition );

		global $wgExtensionDirectory, $wgEchoNotificationIcons, $wgEchoSecondaryIcons;
		$resourceLoader->register( 'ext.echo.emailicons', [
			'class' => 'ResourceLoaderEchoImageModule',
			'icons' => $wgEchoNotificationIcons,
			'selector' => '.mw-echo-icon-{name}',
			'localBasePath' => $wgExtensionDirectory,
			'remoteExtPath' => 'Echo/modules'
		] );
		$resourceLoader->register( 'ext.echo.secondaryicons', [
			'class' => 'ResourceLoaderEchoImageModule',
			'icons' => $wgEchoSecondaryIcons,
			'selector' => '.mw-echo-icon-{name}',
			'localBasePath' => $wgExtensionDirectory,
			'remoteExtPath' => 'Echo/modules'
		] );
	}

	/**
	 * @param DatabaseUpdater $updater
	 */
	public static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		global $wgEchoCluster;
		if ( $wgEchoCluster !== false ) {
			// DatabaseUpdater does not support other databases, so skip
			return;
		}
		$dir = __DIR__;
		$baseSQLFile = "$dir/echo.sql";
		$updater->addExtensionTable( 'echo_event', $baseSQLFile );
		$updater->addExtensionTable( 'echo_email_batch', "$dir/db_patches/echo_email_batch.sql" );
		$updater->addExtensionTable( 'echo_target_page', "$dir/db_patches/echo_target_page.sql" );

		if ( $updater->getDB()->getType() === 'sqlite' ) {
			$updater->modifyExtensionField( 'echo_event', 'event_agent', "$dir/db_patches/patch-event_agent-split.sqlite.sql" );
			$updater->modifyExtensionField( 'echo_event', 'event_variant', "$dir/db_patches/patch-event_variant_nullability.sqlite.sql" );
			$updater->addExtensionField( 'echo_target_page', 'etp_id', "$dir/db_patches/patch-multiple_target_pages.sqlite.sql" );
			$updater->dropExtensionField( 'echo_target_page', 'etp_user', "$dir/db_patches/patch-drop-echo_target_page-etp_user.sqlite.sql" );
			// There is no need to run the patch-event_extra-size or patch-event_agent_ip-size because
			// sqlite ignores numeric arguments in parentheses that follow the type name (ex: VARCHAR(255))
			// see http://www.sqlite.org/datatype3.html Section 2.2 for more info
		} else {
			$updater->modifyExtensionField( 'echo_event', 'event_agent', "$dir/db_patches/patch-event_agent-split.sql" );
			$updater->modifyExtensionField( 'echo_event', 'event_variant', "$dir/db_patches/patch-event_variant_nullability.sql" );
			$updater->modifyExtensionField( 'echo_event', 'event_extra', "$dir/db_patches/patch-event_extra-size.sql" );
			$updater->modifyExtensionField( 'echo_event', 'event_agent_ip', "$dir/db_patches/patch-event_agent_ip-size.sql" );
			$updater->addExtensionField( 'echo_target_page', 'etp_id', "$dir/db_patches/patch-multiple_target_pages.sql" );
			$updater->dropExtensionField( 'echo_target_page', 'etp_user', "$dir/db_patches/patch-drop-echo_target_page-etp_user.sql" );
		}

		$updater->addExtensionField( 'echo_notification', 'notification_bundle_base',
			"$dir/db_patches/patch-notification-bundling-field.sql" );
		// This index was renamed twice,  first from type_page to event_type and later from event_type to echo_event_type
		if ( $updater->getDB()->indexExists( 'echo_event', 'type_page', __METHOD__ ) ) {
			$updater->addExtensionIndex( 'echo_event', 'event_type', "$dir/db_patches/patch-alter-type_page-index.sql" );
		}
		$updater->dropExtensionTable( 'echo_subscription', "$dir/db_patches/patch-drop-echo_subscription.sql" );
		$updater->dropExtensionField( 'echo_event', 'event_timestamp', "$dir/db_patches/patch-drop-echo_event-event_timestamp.sql" );
		$updater->addExtensionField( 'echo_email_batch', 'eeb_event_hash',
			"$dir/db_patches/patch-email_batch-new-field.sql" );
		$updater->addExtensionField( 'echo_event', 'event_page_id', "$dir/db_patches/patch-add-echo_event-event_page_id.sql" );
		$updater->addExtensionIndex( 'echo_event', 'echo_event_type', "$dir/db_patches/patch-alter-event_type-index.sql" );
		$updater->addExtensionIndex( 'echo_notification', 'echo_user_timestamp', "$dir/db_patches/patch-alter-user_timestamp-index.sql" );
		$updater->addExtensionIndex( 'echo_notification', 'echo_notification_event', "$dir/db_patches/patch-add-notification_event-index.sql" );
		$updater->addPostDatabaseUpdateMaintenance( 'RemoveOrphanedEvents' );
		$updater->addExtensionField( 'echo_event', 'event_deleted', "$dir/db_patches/patch-add-echo_event-event_deleted.sql" );
		$updater->addExtensionIndex( 'echo_notification', 'echo_notification_user_read_timestamp', "$dir/db_patches/patch-add-user_read_timestamp-index.sql" );
		$updater->addExtensionIndex( 'echo_target_page', 'echo_target_page_page_event', "$dir/db_patches/patch-add-page_event-index.sql" );
		$updater->addExtensionIndex( 'echo_event', 'echo_event_page_id', "$dir/db_patches/patch-add-event_page_id-index.sql" );
		$updater->dropExtensionIndex( 'echo_notification', 'user_event', "$dir/db_patches/patch-notification-pk.sql" );
	}

	/**
	 * Handler for EchoGetBundleRule hook, which defines the bundle rule for each notification
	 *
	 * @param EchoEvent $event
	 * @param string &$bundleString Determines how the notification should be bundled, for example,
	 * talk page notification is bundled based on namespace and title, the bundle string would be
	 * 'edit-user-talk-' + namespace + title, email digest/email bundling would use this hash as
	 * a key to identify bundle-able event.  For web bundling, we bundle further based on user's
	 * visit to the overlay, we would generate a display hash based on the hash of $bundleString
	 *
	 * @return bool
	 */
	public static function onEchoGetBundleRules( $event, &$bundleString ) {
		switch ( $event->getType() ) {
			case 'edit-user-talk':
				$bundleString = 'edit-user-talk';
				if ( $event->getTitle() ) {
					$bundleString .= '-' . $event->getTitle()->getNamespace()
						. '-' . $event->getTitle()->getDBkey();
				}
				break;
			case 'page-linked':
				$bundleString = 'page-linked';
				if ( $event->getTitle() ) {
					$bundleString .= '-' . $event->getTitle()->getNamespace()
						. '-' . $event->getTitle()->getDBkey();
				}
				break;
			case 'mention-success':
			case 'mention-failure':
				$bundleString = 'mention-status-' . $event->getExtraParam( 'revid' );
				break;
		}

		return true;
	}

	/**
	 * Handler for the GetBetaFeaturePreferences hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetBetaFeaturePreferences
	 *
	 * @param User $user User to get preferences for
	 * @param array &$preferences Preferences array
	 *
	 * @return bool true in all cases
	 */
	public static function getBetaFeaturePreferences( User $user, array &$preferences ) {
		global $wgExtensionAssetsPath, $wgEchoUseCrossWikiBetaFeature, $wgEchoCrossWikiNotifications;

		if ( $wgEchoUseCrossWikiBetaFeature && $wgEchoCrossWikiNotifications ) {
			$preferences['echo-cross-wiki-notifications'] = [
				'label-message' => 'echo-pref-beta-feature-cross-wiki-message',
				'desc-message' => 'echo-pref-beta-feature-cross-wiki-description',
				// Paths to images that represents the feature.
				'screenshot' => [
					'rtl' => "$wgExtensionAssetsPath/Echo/images/betafeatures-icon-notifications-rtl.svg",
					'ltr' => "$wgExtensionAssetsPath/Echo/images/betafeatures-icon-notifications-ltr.svg",
				],
				'info-link' => 'https://www.mediawiki.org/wiki/Special:Mylanguage/Help:Notifications/Cross-wiki',
				// Link to discussion about the feature - talk pages might work
				'discussion-link' => 'https://www.mediawiki.org/wiki/Help_talk:Notifications',
			];
		}

		return true;
	}

	/**
	 * Handler for GetPreferences hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetPreferences
	 *
	 * @param User $user User to get preferences for
	 * @param array &$preferences Preferences array
	 *
	 * @throws MWException
	 * @return bool true in all cases
	 */
	public static function getPreferences( $user, &$preferences ) {
		global $wgEchoEnableEmailBatch,
			$wgEchoNotifiers, $wgEchoNotificationCategories, $wgEchoNotifications,
			$wgEchoNewMsgAlert, $wgAllowHTMLEmail, $wgEchoUseCrossWikiBetaFeature,
			$wgEchoShowFooterNotice, $wgEchoCrossWikiNotifications, $wgEchoPerUserBlacklist;

		$attributeManager = EchoAttributeManager::newFromGlobalVars();

		// Show email frequency options
		$never = wfMessage( 'echo-pref-email-frequency-never' )->plain();
		$immediately = wfMessage( 'echo-pref-email-frequency-immediately' )->plain();
		$freqOptions = [
			$never => EchoEmailFrequency::NEVER,
			$immediately => EchoEmailFrequency::IMMEDIATELY,
		];
		// Only show digest options if email batch is enabled
		if ( $wgEchoEnableEmailBatch ) {
			$daily = wfMessage( 'echo-pref-email-frequency-daily' )->plain();
			$weekly = wfMessage( 'echo-pref-email-frequency-weekly' )->plain();
			$freqOptions += [
				$daily => EchoEmailFrequency::DAILY_DIGEST,
				$weekly => EchoEmailFrequency::WEEKLY_DIGEST,
			];
		}
		$preferences['echo-email-frequency'] = [
			'type' => 'select',
			'label-message' => 'echo-pref-send-me',
			'section' => 'echo/emailsettings',
			'options' => $freqOptions
		];

		// Display information about the user's currently set email address
		$prefsTitle = SpecialPage::getTitleFor( 'Preferences', false, 'mw-prefsection-echo' );
		$link = Linker::link(
			SpecialPage::getTitleFor( 'ChangeEmail' ),
			wfMessage( $user->getEmail() ? 'prefs-changeemail' : 'prefs-setemail' )->escaped(),
			[],
			[ 'returnto' => $prefsTitle->getFullText() ]
		);
		$emailAddress = $user->getEmail() && $user->isAllowed( 'viewmyprivateinfo' )
			? htmlspecialchars( $user->getEmail() ) : '';
		if ( $user->isAllowed( 'editmyprivateinfo' ) && self::isEmailChangeAllowed() ) {
			if ( $emailAddress === '' ) {
				$emailAddress .= $link;
			} else {
				$emailAddress .= wfMessage( 'word-separator' )->escaped()
					. wfMessage( 'parentheses' )->rawParams( $link )->escaped();
			}
		}
		$preferences['echo-emailaddress'] = [
			'type' => 'info',
			'raw' => true,
			'default' => $emailAddress,
			'label-message' => 'echo-pref-send-to',
			'section' => 'echo/emailsettings'
		];

		// Only show this option if html email is allowed, otherwise it is always plain text format
		if ( $wgAllowHTMLEmail ) {
			// Email format
			$preferences['echo-email-format'] = [
				'type' => 'select',
				'label-message' => 'echo-pref-email-format',
				'section' => 'echo/emailsettings',
				'options' => [
					wfMessage( 'echo-pref-email-format-html' )->plain() => EchoEmailFormat::HTML,
					wfMessage( 'echo-pref-email-format-plain-text' )->plain() => EchoEmailFormat::PLAIN_TEXT,
				]
			];
		}

		// Sort notification categories by priority
		$categoriesAndPriorities = [];
		foreach ( $attributeManager->getInternalCategoryNames() as $category ) {
			// See if the category should be hidden from preferences.
			if ( !$attributeManager->isCategoryDisplayedInPreferences( $category ) ) {
				continue;
			}

			// See if user is eligible to receive this notification (per user group restrictions)
			if ( $attributeManager->getCategoryEligibility( $user, $category ) ) {
				$categoriesAndPriorities[$category] = $attributeManager->getCategoryPriority( $category );
			}
		}
		asort( $categoriesAndPriorities );
		$validSortedCategories = array_keys( $categoriesAndPriorities );

		// Show subscription options.  IMPORTANT: 'echo-subscriptions-email-edit-user-talk' is a
		// virtual option, its value is saved to existing talk page notification option
		// 'enotifusertalkpages', see onUserLoadOptions() and onUserSaveOptions() for more
		// information on how it is handled. Doing it in this way, we can avoid keeping running
		// massive data migration script to keep these two options synced when echo is enabled on
		// new wikis or Echo is disabled and re-enabled for some reason.  We can update the name
		// if Echo is ever merged to core

		// Build the columns (notify types)
		$columns = [];
		foreach ( $wgEchoNotifiers as $notifierType => $notifierData ) {
			$formatMessage = wfMessage( 'echo-pref-' . $notifierType )->escaped();
			$columns[$formatMessage] = $notifierType;
		}

		// Build the rows (notification categories)
		$rows = [];
		$tooltips = [];
		foreach ( $validSortedCategories as $category ) {
			$categoryMessage = wfMessage( 'echo-category-title-' . $category )->numParams( 1 )->escaped();
			$rows[$categoryMessage] = $category;
			if ( isset( $wgEchoNotificationCategories[$category]['tooltip'] ) ) {
				$tooltips[$categoryMessage] = wfMessage( $wgEchoNotificationCategories[$category]['tooltip'] )->text();
			}
		}

		// Figure out the individual exceptions in the matrix and make them disabled
		$forceOptionsOff = $forceOptionsOn = [];
		foreach ( $wgEchoNotifiers as $notifierType => $notifierData ) {
			foreach ( $validSortedCategories as $category ) {
				// See if this notify type is non-dismissable
				if ( !$attributeManager->isNotifyTypeDismissableForCategory( $category, $notifierType ) ) {
					$forceOptionsOn[] = "$notifierType-$category";
				}

				if ( !$attributeManager->isNotifyTypeAvailableForCategory( $category, $notifierType ) ) {
					$forceOptionsOff[] = "$notifierType-$category";
				}
			}
		}

		$invalid = array_intersect( $forceOptionsOff, $forceOptionsOn );
		if ( $invalid ) {
			throw new MWException( sprintf(
				'The following notifications are both forced and removed: %s',
				implode( ', ', $invalid )
			) );
		}
		$preferences['echo-subscriptions'] = [
			'class' => 'HTMLCheckMatrix',
			'section' => 'echo/echosubscriptions',
			'rows' => $rows,
			'columns' => $columns,
			'prefix' => 'echo-subscriptions-',
			'force-options-off' => $forceOptionsOff,
			'force-options-on' => $forceOptionsOn,
			'tooltips' => $tooltips,
		];

		if ( !$wgEchoUseCrossWikiBetaFeature && $wgEchoCrossWikiNotifications ) {
			$preferences['echo-cross-wiki-notifications'] = [
				'type' => 'toggle',
				'label-message' => 'echo-pref-cross-wiki-notifications',
				'section' => 'echo/echocrosswiki'
			];
		}

		if ( $wgEchoNewMsgAlert ) {
			$preferences['echo-show-alert'] = [
				'type' => 'toggle',
				'label-message' => 'echo-pref-new-message-indicator',
				'section' => 'echo/newmessageindicator',
			];
		}

		// If we're using Echo to handle user talk page post notifications,
		// hide the old (non-Echo) preference for this. If Echo is moved to core
		// we'll want to remove this old user option entirely. For now, though,
		// we need to keep it defined in case Echo is ever uninstalled.
		// Otherwise, that preference could be lost entirely. This hiding logic
		// is not abstracted since there is only a single preference in core
		// that is potentially made obsolete by Echo.
		if ( isset( $wgEchoNotifications['edit-user-talk'] ) ) {
			$preferences['enotifusertalkpages']['type'] = 'hidden';
			unset( $preferences['enotifusertalkpages']['section'] );
		}

		if ( $wgEchoShowFooterNotice ) {
			$preferences['echo-dismiss-special-page-invitation'] = [
				'type' => 'api',
			];
		}

		if ( $wgEchoPerUserBlacklist ) {
			$lookup = CentralIdLookup::factory();
			$ids = $user->getOption( 'echo-notifications-blacklist', [] );
			$names = $ids ? $lookup->namesFromCentralIds( $ids, $user ) : [];

			$preferences['echo-notifications-blacklist'] = [
				'type' => 'usersmultiselect',
				'label-message' => 'echo-pref-notifications-blacklist',
				'section' => 'echo/blocknotificationslist',
				'default' => implode( "\n", $names )
			];
		}

		return true;
	}

	/**
	 * Test whether email address change is supposed to be allowed
	 * @return bool
	 */
	private static function isEmailChangeAllowed() {
		return AuthManager::singleton()->allowsPropertyChange( 'emailaddress' );
	}

	/**
	 * Handler for PageContentSaveComplete hook
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/PageContentSaveComplete
	 *
	 * @param WikiPage &$wikiPage modified WikiPage
	 * @param User &$user User who edited
	 * @param Content $content New article text
	 * @param string $summary Edit summary
	 * @param bool $minoredit Minor edit or not
	 * @param bool $watchthis Watch this article?
	 * @param string $sectionanchor Section that was edited
	 * @param int &$flags Edit flags
	 * @param Revision $revision Revision that was created
	 * @param Status &$status
	 * @param int $baseRevId
	 * @param int $undidRevId
	 *
	 * @return bool true in all cases
	 */
	public static function onPageContentSaveComplete( WikiPage &$wikiPage, &$user, $content, $summary, $minoredit,
		$watchthis, $sectionanchor, &$flags, $revision, &$status, $baseRevId, $undidRevId = 0 ) {
		global $wgEchoNotifications;

		if ( !$revision ) {
			return true;
		}

		// unless status is "good" (not only ok, also no warnings or errors), we
		// probably shouldn't process it at all (e.g. null edits)
		if ( !$status->isGood() ) {
			return true;
		}

		$title = $wikiPage->getTitle();

		// Try to do this after the HTTP response
		DeferredUpdates::addCallableUpdate( function () use ( $revision ) {
			EchoDiscussionParser::generateEventsForRevision( $revision );
		} );

		// If the user is not an IP and this is not a null edit,
		// test for them reaching a congratulatory threshold
		$thresholds = [ 1, 10, 100, 1000, 10000, 100000, 1000000 ];
		if ( $user->isLoggedIn() && $status->value['revision'] ) {
			$thresholdCount = $user->getEditCount();
			if ( in_array( $thresholdCount, $thresholds ) ) {
				DeferredUpdates::addCallableUpdate( function () use ( $user, $title, $thresholdCount ) {
					$notificationMapper = new EchoNotificationMapper();
					$notifications = $notificationMapper->fetchByUser( $user, 10, null, [ 'thank-you-edit' ] );
					/** @var EchoNotification $notification */
					foreach ( $notifications as $notification ) {
						if ( $notification->getEvent()->getExtraParam( 'editCount' ) === $thresholdCount ) {
							LoggerFactory::getInstance( 'Echo' )->debug(
								'{user} (id: {id}) has already been thanked for their {count} edit',
								[
									'user' => $user->getName(),
									'id' => $user->getId(),
									'count' => $thresholdCount,
								]
							);
							return;
						}
					}

					EchoEvent::create( [
							'type' => 'thank-you-edit',
							'title' => $title,
							'agent' => $user,
							// Edit threshold notifications are sent to the agent
							'extra' => [
								'notifyAgent' => true,
								'editCount' => $thresholdCount,
							]
						]
					);
				} );
			}
		}

		// Handle the case of someone undoing an edit, either through the
		// 'undo' link in the article history or via the API.
		if ( isset( $wgEchoNotifications['reverted'] ) && $undidRevId ) {
			$undidRevision = Revision::newFromId( $undidRevId );
			if ( $undidRevision && $undidRevision->getTitle()->equals( $title ) ) {
				$victimId = $undidRevision->getUser();
				if ( $victimId ) { // No notifications for anonymous users
					EchoEvent::create( [
						'type' => 'reverted',
						'title' => $title,
						'extra' => [
							'revid' => $revision->getId(),
							'reverted-user-id' => $victimId,
							'reverted-revision-id' => $undidRevId,
							'method' => 'undo',
							'summary' => $summary,
						],
						'agent' => $user,
					] );
				}
			}
		}

		return true;
	}

	/**
	 * Handler for EchoAbortEmailNotification hook
	 * @param User $user
	 * @param EchoEvent $event
	 * @return bool true - send email, false - do not send email
	 */
	public static function onEchoAbortEmailNotification( $user, $event ) {
		if ( $event->getType() === 'edit-user-talk' ) {
			$extra = $event->getExtra();
			if ( !empty( $extra['minoredit'] ) ) {
				global $wgEnotifMinorEdits;
				if ( !$wgEnotifMinorEdits || !$user->getOption( 'enotifminoredits' ) ) {
					// Do not send talk page notification email
					return false;
				}
			}
		}

		// Proceed to send talk page notification email
		return true;
	}

	/**
	 * Get overrides for new users.  This allows changes that only apply going forward,
	 * without affecting existing users.
	 *
	 * @return array Associative array mapping key to bool for whether it should be enabled
	 */
	public static function getNewUserPreferenceOverrides() {
		return [
			'echo-subscriptions-web-reverted' => false,
			'echo-subscriptions-email-reverted' => false,
			'echo-subscriptions-web-article-linked' => true,
			'echo-subscriptions-email-mention' => true,
			'echo-subscriptions-email-article-linked' => true,
		];
	}

	/**
	 * Handler for LocalUserCreated hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LocalUserCreated
	 * @param User $user User object that was created.
	 * @param bool $autocreated True when account was auto-created
	 * @return bool
	 */
	public static function onLocalUserCreated( $user, $autocreated ) {
		if ( !$autocreated ) {
			$overrides = self::getNewUserPreferenceOverrides();
			foreach ( $overrides as $prefKey => $value ) {
				$user->setOption( $prefKey, $value );
			}
			$user->saveSettings();
			EchoEvent::create( [
				'type' => 'welcome',
				'agent' => $user,
				// Welcome notification is sent to the agent
				'extra' => [
					'notifyAgent' => true
				]
			] );
		}

		$seenTime = EchoSeenTime::newFromUser( $user );

		// Set seen time to UNIX epoch, so initially all notifications are unseen.
		$seenTime->setTime( wfTimestamp( TS_MW, 1 ), 'all' );

		return true;
	}

	/**
	 * Handler for UserGroupsChanged hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserGroupsChanged
	 *
	 * @param User $user user that was changed
	 * @param string[] $add strings corresponding to groups added
	 * @param string[] $remove strings corresponding to groups removed
	 * @param User|bool $performer
	 * @param string|bool $reason Reason given by the user changing the rights
	 * @param array $oldUGMs
	 * @param array $newUGMs
	 *
	 * @return bool
	 */
	public static function onUserGroupsChanged( $user, $add, $remove, $performer,
		$reason = false, array $oldUGMs = [], array $newUGMs = [] ) {
		if ( !$performer ) {
			// TODO: Implement support for autopromotion
			return true;
		}

		if ( !$user instanceof User ) {
			// TODO: Support UserRightsProxy
			return true;
		}

		if ( $user->equals( $performer ) ) {
			// Don't notify for self changes
			return true;
		}

		// If any old groups are in $add, those groups are having their expiry
		// changed, not actually being added
		$expiryChanged = [];
		$reallyAdded = [];
		foreach ( $add as $group ) {
			if ( isset( $oldUGMs[$group] ) ) {
				$expiryChanged[] = $group;
			} else {
				$reallyAdded[] = $group;
			}
		}

		if ( $expiryChanged ) {
			// use a separate notification for these, so the notification text doesn't
			// get too long
			EchoEvent::create(
				[
					'type' => 'user-rights',
					'extra' => [
						'user' => $user->getID(),
						'expiry-changed' => $expiryChanged,
						'reason' => $reason,
					],
					'agent' => $performer,
				]
			);
		}

		if ( $reallyAdded || $remove ) {
			EchoEvent::create(
				[
					'type' => 'user-rights',
					'extra' => [
						'user' => $user->getID(),
						'add' => $reallyAdded,
						'remove' => $remove,
						'reason' => $reason,
					],
					'agent' => $performer,
				]
			);
		}

		return true;
	}

	/**
	 * Handler for LinksUpdateAfterInsert hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/LinksUpdateAfterInsert
	 * @param LinksUpdate $linksUpdate
	 * @param string $table
	 * @param array $insertions
	 * @return bool
	 */
	public static function onLinksUpdateAfterInsert( $linksUpdate, $table, $insertions ) {
		global $wgRequest;

		// FIXME: This doesn't work in 1.27+
		// Rollback or undo should not trigger link notification
		// @Todo Implement a better solution so it doesn't depend on the checking of
		// a specific set of request variables
		if ( $wgRequest->getVal( 'wpUndidRevision' ) || $wgRequest->getVal( 'action' ) == 'rollback' ) {
			return true;
		}

		// Handle only
		// 1. inserts to pagelinks table &&
		// 2. content namespace pages &&
		// 3. non-transcluding pages &&
		// 4. non-redirect pages
		if ( $table !== 'pagelinks' || !MWNamespace::isContent( $linksUpdate->mTitle->getNamespace() )
			|| !$linksUpdate->mRecursive || $linksUpdate->mTitle->isRedirect()
		) {
			return true;
		}

		if ( is_callable( [ $linksUpdate, 'getTriggeringUser' ] ) ) {
			$user = $linksUpdate->getTriggeringUser();
		} else {
			global $wgUser;
			$user = $wgUser;
		}

		$revid = $linksUpdate->getRevision() ? $linksUpdate->getRevision()->getId() : null;

		// link notification is boundless as you can include infinite number of links in a page
		// db insert is expensive, limit it to a reasonable amount, we can increase this limit
		// once the storage is on Redis
		$max = 10;
		// Only create notifications for links to content namespace pages
		// @Todo - use one big insert instead of individual insert inside foreach loop
		foreach ( $insertions as $page ) {
			if ( MWNamespace::isContent( $page['pl_namespace'] ) ) {
				$title = Title::makeTitle( $page['pl_namespace'], $page['pl_title'] );
				if ( $title->isRedirect() ) {
					continue;
				}

				$linkFromPageId = $linksUpdate->mTitle->getArticleId();
				EchoEvent::create( [
					'type' => 'page-linked',
					'title' => $title,
					'agent' => $user,
					'extra' => [
						'target-page' => $linkFromPageId,
						'link-from-page-id' => $linkFromPageId,
						'revid' => $revid,
					]
				] );
				$max--;
			}
			if ( $max < 0 ) {
				break;
			}
		}

		return true;
	}

	/**
	 * Handler for BeforePageDisplay hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/BeforePageDisplay
	 * @param OutputPage $out OutputPage object
	 * @param Skin $skin Skin being used.
	 * @return bool true in all cases
	 */
	public static function beforePageDisplay( $out, $skin ) {
		if ( $out->getUser()->isLoggedIn() && $skin->getSkinName() !== 'minerva' ) {
			// Load the module for the Notifications flyout
			$out->addModules( [ 'ext.echo.init' ] );
			// Load the styles for the Notifications badge
			$out->addModuleStyles( [
				'ext.echo.styles.badge',
				'ext.echo.badgeicons'
			] );
		}

		return true;
	}

	/**
	 * Handler for PersonalUrls hook.
	 * Add a "Notifications" item to the user toolbar ('personal URLs').
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/PersonalUrls
	 * @param array &$personal_urls Array of URLs to append to.
	 * @param Title &$title Title of page being visited.
	 * @param SkinTemplate $sk
	 * @return bool true in all cases
	 */
	public static function onPersonalUrls( &$personal_urls, &$title, $sk ) {
		global $wgEchoNewMsgAlert, $wgEchoShowFooterNotice;
		$user = $sk->getUser();
		if ( $user->isAnon() ) {
			return true;
		}

		// Attempt to mark a notification as read when visiting a page
		$subtractAlerts = 0;
		$subtractMessages = 0;
		$eventIds = [];
		if ( $title->getArticleID() ) {
			$eventMapper = new EchoEventMapper();
			$events = $eventMapper->fetchUnreadByUserAndPage( $user, $title->getArticleID() );

			if ( $events ) {
				/* @var EchoEvent $event */
				foreach ( $events as $event ) {
					if ( $event->getSection() === EchoAttributeManager::MESSAGE ) {
						$subtractMessages++;
					} else {
						// ALERT
						$subtractAlerts++;
					}
					$eventIds[] = $event->getId();
				}
			}
		}

		// Attempt to mark as read the event IDs in the ?markasread= parameter, if present
		$markAsReadIds = explode( '|', $sk->getOutput()->getRequest()->getText( 'markasread' ) );
		if ( $markAsReadIds ) {
			// gather the IDs that we didn't already find with target_pages
			$eventsToMarkAsRead = [];
			foreach ( $markAsReadIds as $markAsReadId ) {
				$markAsReadId = intval( $markAsReadId );
				if ( $markAsReadId !== 0 && !in_array( $markAsReadId, $eventIds ) ) {
					$eventsToMarkAsRead[] = $markAsReadId;
				}
			}

			if ( $eventsToMarkAsRead ) {
				// fetch the notifications to adjust the counters
				$notifMapper = new EchoNotificationMapper();
				$notifs = $notifMapper->fetchByUserEvents( $user, $eventsToMarkAsRead );

				foreach ( $notifs as $notif ) {
					if ( !$notif->getReadTimestamp() ) {
						if ( $notif->getEvent()->getSection() === EchoAttributeManager::MESSAGE ) {
							$subtractMessages++;
						} else {
							$subtractAlerts++;
						}
						$eventIds[] = intval( $notif->getEvent()->getId() );
					}
				}
			}
		}

		if ( $eventIds ) {
			DeferredUpdates::addCallableUpdate( function () use ( $user, $eventIds ) {
				$notifUser = MWEchoNotifUser::newFromUser( $user );
				$notifUser->markRead( $eventIds );
			} );
		}

		// Add a "My notifications" item to personal URLs
		$notifUser = MWEchoNotifUser::newFromUser( $user );
		$msgCount = $notifUser->getMessageCount() - $subtractMessages;
		$alertCount = $notifUser->getAlertCount() - $subtractAlerts;
		// But make sure we never show a negative number (T130853)
		$msgCount = max( 0, $msgCount );
		$alertCount = max( 0, $alertCount );

		$msgNotificationTimestamp = $notifUser->getLastUnreadMessageTime();
		$alertNotificationTimestamp = $notifUser->getLastUnreadAlertTime();

		$seenTime = EchoSeenTime::newFromUser( $user );
		if ( $title->isSpecial( 'Notifications' ) ) {
			// If this is the Special:Notifications page, seenTime to now
			$seenTime->setTime( wfTimestamp( TS_MW ), EchoAttributeManager::ALL );
		}
		$seenAlertTime = $seenTime->getTime( 'alert', TS_ISO_8601 );
		$seenMsgTime = $seenTime->getTime( 'message', TS_ISO_8601 );

		$sk->getOutput()->addJsConfigVars( 'wgEchoSeenTime', [
			'alert' => $seenAlertTime,
			'notice' => $seenMsgTime,
		] );

		if (
			$wgEchoShowFooterNotice &&
			!$user->getOption( 'echo-dismiss-special-page-invitation' )
		) {
			$sk->getOutput()->addJsConfigVars( 'wgEchoShowSpecialPageInvitation', true );
		}

		$msgFormattedCount = EchoNotificationController::formatNotificationCount( $msgCount );
		$alertFormattedCount = EchoNotificationController::formatNotificationCount( $alertCount );

		$msgText = wfMessage( 'echo-notification-notice', $msgCount );
		$alertText = wfMessage( 'echo-notification-alert', $alertCount );

		$url = SpecialPage::getTitleFor( 'Notifications' )->getLocalURL();

		// HACK: inverted icons only work in the "MediaWiki" OOUI theme
		// Avoid flashes in skins that don't use it (T111821)
		$sk->getOutput()->setupOOUI( strtolower( $sk->getSkinName() ), $sk->getOutput()->getLanguage()->getDir() );

		$msgLinkClasses = [ "mw-echo-notifications-badge", "mw-echo-notification-badge-nojs" ];
		$alertLinkClasses = [ "mw-echo-notifications-badge", "mw-echo-notification-badge-nojs" ];

		$hasUnseen = false;
		if (
			$msgCount != 0 && // no unread notifications
			$msgNotificationTimestamp !== false && // should already always be false if count === 0
			( $seenMsgTime === null || $seenMsgTime < $msgNotificationTimestamp->getTimestamp( TS_ISO_8601 ) ) // there are no unseen notifications
		) {
			$msgLinkClasses[] = 'mw-echo-unseen-notifications';
			$hasUnseen = true;
		} elseif ( $msgCount === 0 ) {
			$msgLinkClasses[] = 'mw-echo-notifications-badge-all-read';
		}

		if ( $msgCount > MWEchoNotifUser::MAX_BADGE_COUNT ) {
			$msgLinkClasses[] = 'mw-echo-notifications-badge-long-label';
		}

		if (
			$alertCount != 0 && // no unread notifications
			$alertNotificationTimestamp !== false && // should already always be false if count === 0
			( $seenAlertTime === null || $seenAlertTime < $alertNotificationTimestamp->getTimestamp( TS_ISO_8601 ) ) // all notifications have already been seen
		) {
			$alertLinkClasses[] = 'mw-echo-unseen-notifications';
			$hasUnseen = true;
		} elseif ( $alertCount === 0 ) {
			$alertLinkClasses[] = 'mw-echo-notifications-badge-all-read';
		}

		if ( $alertCount > MWEchoNotifUser::MAX_BADGE_COUNT ) {
			$alertLinkClasses[] = 'mw-echo-notifications-badge-long-label';
		}

		$alertLink = [
			'href' => $url,
			'text' => $alertText,
			'active' => ( $url == $title->getLocalUrl() ),
			'class' => $alertLinkClasses,
			'data' => [
				'counter-num' => $alertCount,
				'counter-text' => $alertFormattedCount,
			],
		];

		$insertUrls = [
			'notifications-alert' => $alertLink,
		];

		$msgLink = [
			'href' => $url,
			'text' => $msgText,
			'active' => ( $url == $title->getLocalUrl() ),
			'class' => $msgLinkClasses,
			'data' => [
				'counter-num' => $msgCount,
				'counter-text' => $msgFormattedCount,
			],
		];

		$insertUrls['notifications-notice'] = $msgLink;

		$personal_urls = wfArrayInsertAfter( $personal_urls, $insertUrls, 'userpage' );

		if ( $hasUnseen ) {
			// Record that the user is going to see an indicator that they have unread notifications
			MediaWikiServices::getInstance()->getStatsdDataFactory()->increment( 'echo.unseen' );
		}

		// If the user has new messages, display a talk page alert
		// We need to check:
		// * Orange alert is enabled in configuration
		// * Enabled in user preferences
		// * User actually has new messages
		// * User is not viewing their user talk page, as user_newtalk
		// will not have been cleared yet. (bug T107655).
		if ( $wgEchoNewMsgAlert && $user->getOption( 'echo-show-alert' )
			&& $user->getNewtalk() && !$user->getTalkPage()->equals( $title )
		) {
			if ( Hooks::run( 'BeforeDisplayOrangeAlert', [ $user, $title ] ) ) {
				$personal_urls['mytalk']['text'] = $sk->msg( 'echo-new-messages' )->text();
				$personal_urls['mytalk']['class'] = [ 'mw-echo-alert' ];
				$sk->getOutput()->addModuleStyles( 'ext.echo.styles.alert' );
			}
		}

		return true;
	}

	/**
	 * Handler for AbortTalkPageEmailNotification hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/AbortTalkPageEmailNotification
	 * @param User $targetUser
	 * @param Title $title
	 * @return bool
	 */
	public static function onAbortTalkPageEmailNotification( $targetUser, $title ) {
		global $wgEchoNotifications;

		// Send legacy talk page email notification if
		// 1. echo is disabled for them or
		// 2. echo talk page notification is disabled
		if ( !isset( $wgEchoNotifications['edit-user-talk'] ) ) {
			// Legacy talk page email notification
			return true;
		}

		// Echo talk page email notification
		return false;
	}

	/**
	 * Handler for AbortWatchlistEmailNotification hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/AbortWatchlistEmailNotification
	 * @param User $targetUser
	 * @param Title $title
	 * @param EmailNotification $emailNotification The email notification object that sends non-echo notifications
	 * @return bool
	 */
	public static function onSendWatchlistEmailNotification( $targetUser, $title, $emailNotification ) {
		// If a user is watching his/her own talk page, do not send talk page watchlist
		// email notification if the user is receiving Echo talk page notification
		if ( $title->isTalkPage() && $targetUser->getTalkPage()->equals( $title ) ) {
			$attributeManager = EchoAttributeManager::newFromGlobalVars();
			$events = $attributeManager->getUserEnabledEvents( $targetUser, 'email' );
			if ( in_array( 'edit-user-talk', $events ) ) {
				// Do not send watchlist email notification, the user will receive an Echo notification
				return false;
			}
		}

		// Proceed to send watchlist email notification
		return true;
	}

	/**
	 * Handler for MakeGlobalVariablesScript hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/MakeGlobalVariablesScript
	 * @param array &$vars Variables to be added into the output
	 * @param OutputPage $outputPage OutputPage instance calling the hook
	 * @return bool true in all cases
	 */
	public static function makeGlobalVariablesScript( &$vars, OutputPage $outputPage ) {
		global $wgEchoEventLoggingSchemas, $wgEchoEventLoggingVersion;
		$user = $outputPage->getUser();

		// Provide info for the Overlay

		if ( $user->isLoggedIn() ) {
			$vars['wgEchoEventLoggingSchemas'] = $wgEchoEventLoggingSchemas;
			$vars['wgEchoEventLoggingVersion'] = $wgEchoEventLoggingVersion;
		} elseif (
			$outputPage->getTitle()->equals( SpecialPage::getTitleFor( 'JavaScriptTest', 'qunit' ) ) ||
			// Also if running from /plain or /export
			$outputPage->getTitle()->isSubpageOf( SpecialPage::getTitleFor( 'JavaScriptTest', 'qunit' ) )
		) {
			// For testing purposes
			$vars['wgEchoEventLoggingSchemas'] = [
				'EchoInteraction' => [],
			];
		}

		return true;
	}

	public static function onOutputPageCheckLastModified( array &$modifiedTimes, OutputPage $out ) {
		$user = $out->getUser();
		if ( $user->isLoggedIn() ) {
			$notifUser = MWEchoNotifUser::newFromUser( $user );
			$lastUpdate = $notifUser->getGlobalUpdateTime();
			if ( $lastUpdate !== false ) {
				$modifiedTimes['notifications-global'] = $lastUpdate;
			}

			$modifiedTimes['notifications-seen-alert'] = EchoSeenTime::newFromUser( $user )->getTime( 'alert' );
			$modifiedTimes['notifications-seen-message'] = EchoSeenTime::newFromUser( $user )->getTime( 'message' );
		}
	}

	/**
	 * Handler for GetNewMessagesAlert hook.
	 * We're using the GetNewMessagesAlert hook instead of the
	 * ArticleEditUpdateNewTalk hook since we still want the user_newtalk data
	 * to be updated and availble to client-side tools and the API.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/GetNewMessagesAlert
	 * @param string &$newMessagesAlert An alert that the user has new messages
	 *     or an empty string if the user does not (empty by default)
	 * @param array $newtalks This will be empty if the user has no new messages
	 *     or an Array containing links and revisions if there are new messages
	 * @param User $user The user who is loading the page
	 * @param OutputPage $out Output object
	 * @return bool Should return false to prevent the new messages alert (OBOD)
	 *     or true to allow the new messages alert
	 */
	public static function abortNewMessagesAlert( &$newMessagesAlert, $newtalks, $user, $out ) {
		global $wgEchoNotifications;

		// If the user has the notifications flyout turned on and is receiving
		// notifications for talk page messages, disable the new messages alert.
		if ( $user->isLoggedIn()
			&& isset( $wgEchoNotifications['edit-user-talk'] )
		) {
			// hide new messages alert
			return false;
		} else {
			// show new messages alert
			return true;
		}
	}

	/**
	 * Handler for ArticleRollbackComplete hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ArticleRollbackComplete
	 *
	 * @param WikiPage $wikiPage The article that was edited
	 * @param User $agent The user who did the rollback
	 * @param Revision $newRevision The revision the page was reverted back to
	 * @param Revision $oldRevision The revision of the top edit that was reverted
	 *
	 * @return bool true in all cases
	 */
	public static function onRollbackComplete( WikiPage $wikiPage, $agent, $newRevision, $oldRevision ) {
		$victimId = $oldRevision->getUser();

		if (
			$victimId && // No notifications for anonymous users
			!$oldRevision->getContent()->equals( $newRevision->getContent() ) // No notifications for null rollbacks
		) {
			EchoEvent::create( [
				'type' => 'reverted',
				'title' => $wikiPage->getTitle(),
				'extra' => [
					'revid' => $wikiPage->getRevision()->getId(),
					'reverted-user-id' => $victimId,
					'reverted-revision-id' => $oldRevision->getId(),
					'method' => 'rollback',
				],
				'agent' => $agent,
			] );
		}

		return true;
	}

	/**
	 * Handler for UserSaveSettings hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserSaveSettings
	 * @param User $user whose settings were saved
	 * @return bool true in all cases
	 */
	public static function onUserSaveSettings( $user ) {
		// Extensions like AbuseFilter might create an account, but
		// the tables we need might not exist. Bug 57335
		if ( !defined( 'MW_UPDATER' ) ) {
			// Reset the notification count since it may have changed due to user
			// option changes. This covers both explicit changes in the preferences
			// and changes made through the options API (since both call this hook).
			DeferredUpdates::addCallableUpdate( function () use ( $user ) {
				MWEchoNotifUser::newFromUser( $user )->resetNotificationCount();
			} );
		}

		return true;
	}

	/**
	 * Handler for UserLoadOptions hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserLoadOptions
	 * @param User $user User whose options were loaded
	 * @param array &$options Options can be modified
	 * @return bool true in all cases
	 */
	public static function onUserLoadOptions( $user, &$options ) {
		// Use existing enotifusertalkpages option for echo-subscriptions-email-edit-user-talk
		if ( isset( $options['enotifusertalkpages'] ) ) {
			$options['echo-subscriptions-email-edit-user-talk'] = $options['enotifusertalkpages'];
		}

		if ( isset( $options['echo-notifications-blacklist'] ) ) {
			$options['echo-notifications-blacklist'] = self::mapToInt( explode( "\n", $options['echo-notifications-blacklist'] ) );
		}

		return true;
	}

	/**
	 * Handler for UserSaveOptions hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserSaveOptions
	 * @param User $user User whose options are being saved
	 * @param array &$options Options can be modified
	 * @return bool true in all cases
	 */
	public static function onUserSaveOptions( $user, &$options ) {
		// echo-subscriptions-email-edit-user-talk is just a virtual option,
		// save the value in the real option enotifusertalkpages
		if ( isset( $options['echo-subscriptions-email-edit-user-talk'] ) ) {
			$options['enotifusertalkpages'] = $options['echo-subscriptions-email-edit-user-talk'];
			unset( $options['echo-subscriptions-email-edit-user-talk'] );
		}

		// Convert usernames to ids.
		if ( isset( $options['echo-notifications-blacklist'] ) ) {
			if ( $options['echo-notifications-blacklist'] ) {
				$value = $options['echo-notifications-blacklist'];
				// Notification Blacklist may be an array of ids or a string of new line
				// delimnated user names.
				if ( is_array( $value ) ) {
					$ids = array_filter( $value, 'is_numeric' );
				} else {
					$lookup = CentralIdLookup::factory();
					$names = explode( "\n", $value );
					$ids = $lookup->centralIdsFromNames( $names, $user );
				}

				$ids = self::mapToInt( $ids );

				if ( count( $ids ) > 0 ) {
					$user->setOption( 'echo-notifications-blacklist', $ids );
					$options['echo-notifications-blacklist'] = implode( "\n", $user->getOption( 'echo-notifications-blacklist' ) );
				} else {
					// If the blacklist is empty, set it to null rather than an empty
					// string.
					$options['echo-notifications-blacklist'] = null;
				}
			} else {
				// If the blacklist is empty, set it to null rather than an empty string.
				$options['echo-notifications-blacklist'] = null;
			}
		}

		return true;
	}

	/**
	 * Convert all values in an array to integers and filter out zeroes.
	 *
	 * @param array $numbers
	 *
	 * @return int[]
	 */
	protected static function mapToInt( array $numbers ) {
		$data = [];

		foreach ( $numbers as $value ) {
			$int = intval( $value );
			if ( $int === 0 ) {
				continue;
			}
			$data[] = $int;
		}

		return $data;
	}

	/**
	 * Handler for UserClearNewTalkNotification hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserClearNewTalkNotification
	 * @param User $user User whose talk page notification should be marked as read
	 * @return bool true in all cases
	 */
	public static function onUserClearNewTalkNotification( User $user ) {
		if ( !$user->isAnon() ) {
			DeferredUpdates::addCallableUpdate( function () use ( $user ) {
				MWEchoNotifUser::newFromUser( $user )->clearTalkNotification();
			} );
		}

		return true;
	}

	/**
	 * Handler for ParserTestTables hook, makes sure that Echo's tables are present during tests
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/UserClearNewTalkNotification
	 * @param array &$tables List of DB tables to be used for parser tests
	 * @return bool true in all cases
	 */
	public static function onParserTestTables( &$tables ) {
		$tables[] = 'echo_event';
		$tables[] = 'echo_notification';
		$tables[] = 'echo_email_batch';

		return true;
	}

	/**
	 * Handler for EmailUserComplete hook.
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/EmailUserComplete
	 * @param MailAddress $address Adress of receiving user
	 * @param MailAddress $from Adress of sending user
	 * @param string $subject Subject of the mail
	 * @param string $text Text of the mail
	 * @return bool true in all cases
	 */
	public static function onEmailUserComplete( $address, $from, $subject, $text ) {
		global $wgContLang;

		if ( $from->name === $address->name ) {
			// nothing to notify
			return true;
		}
		$userTo = User::newFromName( $address->name );
		$userFrom = User::newFromName( $from->name );

		$autoSubject = wfMessage( 'defemailsubject', $from->name )->inContentLanguage()->text();
		if ( $subject === $autoSubject ) {
			$autoFooter = "\n\n-- \n" . wfMessage( 'emailuserfooter', $from->name, $address->name )->inContentLanguage()->text();
			$textWithoutFooter = preg_replace( '/' . preg_quote( $autoFooter, '/' ) . '$/', '', $text );
			$preview = $wgContLang->truncate( $textWithoutFooter, 125 );
		} else {
			$preview = $subject;
		}

		EchoEvent::create( [
			'type' => 'emailuser',
			'extra' => [
				'to-user-id' => $userTo->getId(),
				'preview' => $preview,
			],
			'agent' => $userFrom,
		] );

		return true;
	}

	/**
	 * For integration with the UserMerge extension.
	 *
	 * @param array &$updateFields
	 * @return bool
	 */
	public static function onUserMergeAccountFields( &$updateFields ) {
		// array( tableName, idField, textField )
		$dbw = MWEchoDbFactory::newFromDefault()->getEchoDb( DB_MASTER );
		$updateFields[] = [ 'echo_event', 'event_agent_id', 'db' => $dbw ];
		$updateFields[] = [ 'echo_notification', 'notification_user', 'db' => $dbw, 'options' => [ 'IGNORE' ] ];
		$updateFields[] = [ 'echo_email_batch', 'eeb_user_id', 'db' => $dbw, 'options' => [ 'IGNORE' ] ];

		return true;
	}

	public static function onMergeAccountFromTo( User &$oldUser, User &$newUser ) {
		DeferredUpdates::addCallableUpdate( function () use ( $oldUser, $newUser ) {
			MWEchoNotifUser::newFromUser( $oldUser )->resetNotificationCount( DB_MASTER );
			if ( !$newUser->isAnon() ) {
				MWEchoNotifUser::newFromUser( $newUser )->resetNotificationCount( DB_MASTER );
			}
		} );

		return true;
	}

	public static function onUserMergeAccountDeleteTables( &$tables ) {
		$dbw = MWEchoDbFactory::newFromDefault()->getEchoDb( DB_MASTER );
		$tables['echo_notification'] = [ 'notification_user', 'db' => $dbw ];
		$tables['echo_email_batch'] = [ 'eeb_user_id', 'db' => $dbw ];

		return true;
	}

	/**
	 * Sets custom login message for redirect from notification page
	 *
	 * @param array &$messages
	 * @return bool
	 */
	public static function onLoginFormValidErrorMessages( &$messages ) {
		$messages[] = 'echo-notification-loginrequired';
		return true;
	}

	public static function onResourceLoaderGetConfigVars( &$vars ) {
		global $wgEchoFooterNoticeURL;

		$vars['wgEchoMaxNotificationCount'] = MWEchoNotifUser::MAX_BADGE_COUNT;
		$vars['wgEchoFooterNoticeURL'] = $wgEchoFooterNoticeURL;

		return true;
	}

	public static function onArticleDeleteComplete( WikiPage &$article, User &$user, $reason, $articleId, Content $content = null, LogEntry $logEntry ) {
		\DeferredUpdates::addCallableUpdate( function () use ( $articleId ) {
			$eventMapper = new EchoEventMapper();
			$eventIds = $eventMapper->fetchIdsByPage( $articleId );
			EchoModerationController::moderate( $eventIds, true );
		} );
		return true;
	}

	public static function onArticleUndelete( Title $title, $create, $comment, $oldPageId ) {
		if ( $create ) {
			\DeferredUpdates::addCallableUpdate( function () use ( $oldPageId ) {
				$eventMapper = new EchoEventMapper();
				$eventIds = $eventMapper->fetchIdsByPage( $oldPageId );
				EchoModerationController::moderate( $eventIds, false );
			} );
		}
		return true;
	}

}
