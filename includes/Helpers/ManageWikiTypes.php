<?php

namespace Miraheze\ManageWiki\Helpers;

use ContentHandler;
use MediaWiki\MediaWikiServices;
use Miraheze\ManageWiki\ManageWiki;
use Status;
use UserGroupMembership;

class ManageWikiTypes {
	public static function process( $config, $disabled, $groupList, $module, $options, $value, $name = false, $overrideDefault = false, $type = false ) {
		if ( $module === 'namespaces' ) {
			if ( $overrideDefault ) {
				$options['overridedefault'] = $overrideDefault;
			}

			if ( $type ) {
				$options['type'] = $type;
			}

			return self::namespaces( $overrideDefault, $type, $value ) ?: self::common( $config, $disabled, $groupList, $name, $options, $value );
		}

		return self::common( $config, $disabled, $groupList, $name, $options, $value );
	}

	private static function common( $config, $disabled, $groupList, $name, $options, $value ) {
		switch ( $options['type'] ) {
			case 'database':
				$configs = [
					'type' => 'text',
					'default' => $value ?? $options['overridedefault'],
					'validation-callback' => static function ( $database ) use ( $config, $name ) {
						if ( !in_array( $database, $config->get( 'LocalDatabases' ) ) ) {
							return Status::newFatal( 'managewiki-invalid-database', $database, $name )->getMessage();
						}

						return true;
					}
				];
				break;
			case 'float':
				$configs = [
					'type' => 'float',
					'min' => $options['minfloat'],
					'max' => $options['maxfloat'],
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'integer':
				$configs = [
					'type' => 'int',
					'min' => $options['minint'],
					'max' => $options['maxint'],
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'integers':
				$configs = [
					'type' => 'cloner',
					'fields' => [
						'value' => [
							'type' => 'int',
							'min' => $options['minint'] ?? null,
							'max' => $options['maxint'] ?? null,
						],
						'delete' => [
							'type' => 'submit',
							'default' => wfMessage( 'htmlform-cloner-delete' )->escaped(),
							'flags' => [ 'destructive' ],
						],
					],
					'default' => array_map( static function ( $num ) {
						return [ 'value' => $num ];
					}, $value ?? $options['overridedefault'] ),
				];
				break;
			case 'interwiki':
				$interwikiPrefixes = [];

				$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
				$prefixes = $interwikiLookup->getAllPrefixes();

				foreach ( $prefixes as $row ) {
					$prefix = $row['iw_prefix'];

					$interwikiPrefixes[$prefix] = $prefix;
				}

				$configs = [
					'type' => 'multiselect',
					'options' => $interwikiPrefixes,
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'language':
				$configs = [
					'type' => 'language',
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'list':
				$configs = [
					'type' => 'select',
					'options' => $options['options'],
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'list-multi':
				$configs = [
					'type' => 'multiselect',
					'options' => $options['options'],
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'list-multi-bool':
				$configs = [
					'type' => 'multiselect',
					'options' => $options['options'],
					'default' => ( isset( $value ) && $value !== null ) ? array_keys( $value, true ) : array_keys( $options['overridedefault'], true )
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'matrix':
				$configs = [
					'type' => 'checkmatrix',
					'rows' => $options['rows'],
					'columns' => $options['cols'],
					'default' => ( isset( $value ) && $value !== null ) ? ManageWiki::handleMatrix( $value, 'php' ) : $options['overridedefault']
				];
				break;
			case 'preferences':
				$preferences = [];
				$excludedPrefs = [];
				$allPreferences = MediaWikiServices::getInstance()->getUserOptionsLookup()->getDefaultOptions();

				// Don't show preferences hidden by configuratiom
				if ( !$config->get( 'AllowUserCssPrefs' ) ) {
					$excludedPrefs[] = 'underline';
					$excludedPrefs[] = 'editfont';
				}

				if ( $config->get( 'DisableLangConversion' ) ) {
					$excludedPrefs[] = 'variant';
				} else {
					foreach ( preg_grep( '/variant-[A-Za-z0-9]/', array_keys( $allPreferences ) ) as $pref => $val ) {
						$excludedPrefs[] = array_keys( $allPreferences )[$pref];
					}
				}

				if ( $config->get( 'ForceHTTPS' ) || !$config->get( 'SecureLogin' ) ) {
					$excludedPrefs[] = 'prefershttps';
				}

				if ( !$config->get( 'RCShowWatchingUsers' ) ) {
					$excludedPrefs[] = 'shownumberswatching';
				}

				if ( !$config->get( 'RCWatchCategoryMembership' ) ) {
					$excludedPrefs[] = 'hidecategorization';
					$excludedPrefs[] = 'watchlisthidecategorization';
				}

				if ( !$config->get( 'SearchMatchRedirectPreference' ) ) {
					$excludedPrefs[] = 'search-match-redirect';
				}

				if ( !$config->get( 'EnableEmail' ) ) {
					if ( !$config->get( 'AllowRequiringEmailForResets' ) ) {
						$excludedPrefs[] = 'requireemail';
					}

					if ( !$config->get( 'EnableUserEmail' ) ) {
						$excludedPrefs[] = 'disablemail';
						$excludedPrefs[] = 'email-allow-new-users';
						$excludedPrefs[] = 'ccmeonemails';

						if ( !$config->get( 'EnableUserEmailMuteList' ) ) {
							$excludedPrefs[] = 'email-blacklist';
						}
					}

					if ( !$config->get( 'EnotifWatchlist' ) ) {
						$excludedPrefs[] = 'enotifwatchlistpages';
					}

					if ( !$config->get( 'EnotifUserTalk' ) ) {
						$excludedPrefs[] = 'enotifusertalkpages';
					}

					if ( !$config->get( 'EnotifUserTalk' ) && !$config->get( 'EnotifWatchlist' ) ) {
						if ( !$config->get( 'EnotifMinorEdits' ) ) {
							$excludedPrefs[] = 'enotifminoredits';
						}

						if ( !$config->get( 'EnotifRevealEditorAddress' ) ) {
							$excludedPrefs[] = 'enotifrevealaddr';
						}
					}
				}

				// Exclude searchNs* preferences
				foreach ( preg_grep( '/searchNs[0-9]/', array_keys( $allPreferences ) ) as $pref => $val ) {
					$excludedPrefs[] = array_keys( $allPreferences )[$pref];
				}

				// Exclude echo-subscriptions-* preferences
				foreach ( preg_grep( '/echo-subscriptions-(?s).*/', array_keys( $allPreferences ) ) as $pref => $val ) {
					$excludedPrefs[] = array_keys( $allPreferences )[$pref];
				}

				// Exclude downloaduserdata preference
				$excludedPrefs[] = 'downloaduserdata';

				foreach ( $allPreferences as $pref => $val ) {
					if ( !in_array( $pref, $excludedPrefs ) ) {
						$preferences[$pref] = $pref;
					}
				}

				ksort( $preferences );

				$configs = [
					'type' => 'multiselect',
					'options' => $preferences,
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'skin':
				$enabledSkins = MediaWikiServices::getInstance()->getSkinFactory()->getSkinNames();

				unset( $enabledSkins['fallback'] );
				unset( $enabledSkins['apioutput'] );

				if ( $options['excludeSkipSkins'] ?? false ) {
					foreach ( $config->get( 'SkipSkins' ) as $skip ) {
						unset( $enabledSkins[$skip] );
					}
				}

				$enabledSkins = array_flip( $enabledSkins );
				ksort( $enabledSkins );

				$configs = [
					'type' => 'select',
					'options' => isset( $options['options'] ) ? array_merge( $enabledSkins, $options['options'] ) : $enabledSkins,
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'skins':
				$enabledSkins = MediaWikiServices::getInstance()->getSkinFactory()->getSkinNames();

				unset( $enabledSkins['fallback'] );
				unset( $enabledSkins['apioutput'] );

				if ( $options['excludeSkipSkins'] ?? false ) {
					foreach ( $config->get( 'SkipSkins' ) as $skip ) {
						unset( $enabledSkins[$skip] );
					}
				}

				$enabledSkins = array_flip( $enabledSkins );
				ksort( $enabledSkins );

				$configs = [
					'type' => 'multiselect',
					'options' => isset( $options['options'] ) ? array_merge( $enabledSkins, $options['options'] ) : $enabledSkins,
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'texts':
				$configs = [
					'type' => 'cloner',
					'fields' => [
						'value' => [
							'type' => 'text',
						],
						'delete' => [
							'type' => 'submit',
							'default' => wfMessage( 'htmlform-cloner-delete' )->escaped(),
							'flags' => [ 'destructive' ],
						],
					],
					'default' => array_map( static function ( $text ) {
						return [ 'value' => $text ];
					}, $value ?? $options['overridedefault'] ),
				];
				break;
			case 'timezone':
				$configs = [
					'type' => 'select',
					'options' => ManageWiki::getTimezoneList(),
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'user':
				$configs = [
					'type' => 'user',
					'exists' => true,
					'default' => $value ?? $options['overridedefault']
				];
				break;
			case 'users':
				$configs = [
					'type' => 'usersmultiselect',
					'exists' => true,
					'default' => implode( "\n", $value ?? $options['overridedefault'] )
				];
				break;
			case 'usergroups':
				$groups = [];
				foreach ( (array)$groupList as $group ) {
					$groups[UserGroupMembership::getGroupName( $group )] = $group;
				}

				$configs = [
					'type' => 'multiselect',
					'options' => isset( $options['options'] ) ? array_merge( $groups, $options['options'] ) : $groups,
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'userrights':
				$rights = [];
				foreach ( MediaWikiServices::getInstance()->getPermissionManager()->getAllPermissions() as $right ) {
					$rights[$right] = $right;
				}

				$configs = [
					'type' => 'multiselect',
					'options' => isset( $options['options'] ) ? array_merge( $rights, $options['options'] ) : $rights,
					'default' => $value ?? $options['overridedefault']
				];

				if ( !$disabled ) {
					$configs['dropdown'] = true;
				}
				break;
			case 'wikipage':
				$configs = [
					'type' => 'title',
					'exists' => $options['exists'] ?? true,
					'default' => $value ?? $options['overridedefault'],
					'required' => false
				];
				break;
			case 'wikipages':
				$configs = [
					'type' => 'titlesmultiselect',
					'exists' => $options['exists'] ?? true,
					'default' => implode( "\n", $value ?? $options['overridedefault'] ),
					'required' => false
				];
				break;
			default:
				$configs = [
					'type' => $options['type'],
					'default' => $value ?? $options['overridedefault']
				];
				break;
		}

		return $configs;
	}

	private static function namespaces( $overrideDefault, $type, $value ) {
		$configs = [];

		if ( $type === 'contentmodel' ) {
			$contentHandlerFactory = MediaWikiServices::getInstance()->getContentHandlerFactory();

			$models = $contentHandlerFactory->getContentModels();
			$contentModels = [];
			foreach ( $models as $model ) {
				$contentModels[ucfirst( ContentHandler::getLocalizedName( $model ) )] = $model;
			}

			uksort( $contentModels, 'strcasecmp' );

			$configs = [
				'type' => 'select',
				'options' => $contentModels,
				'default' => $value
			];
		} elseif ( $type === 'vestyle' ) {
			$configs = [
				'type' => 'check',
				'default' => $value ?? $overrideDefault
			];
		}

		return $configs;
	}
}
