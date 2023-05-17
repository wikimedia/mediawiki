<?php

namespace Miraheze\ManageWiki\FormFactory;

use Config;
use ExtensionProcessor;
use ExtensionRegistry;
use HTMLForm;
use IContextSource;
use Linker;
use ManualLogEntry;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\RemoteWiki;
use Miraheze\ManageWiki\Helpers\ManageWikiExtensions;
use Miraheze\ManageWiki\Helpers\ManageWikiNamespaces;
use Miraheze\ManageWiki\Helpers\ManageWikiPermissions;
use Miraheze\ManageWiki\Helpers\ManageWikiRequirements;
use Miraheze\ManageWiki\Helpers\ManageWikiSettings;
use Miraheze\ManageWiki\Helpers\ManageWikiTypes;
use Miraheze\ManageWiki\ManageWiki;
use MWException;
use SpecialPage;
use User;
use UserGroupMembership;
use Wikimedia\Rdbms\DBConnRef;

class ManageWikiFormFactoryBuilder {
	public static function buildDescriptor(
		string $module,
		string $dbName,
		bool $ceMW,
		IContextSource $context,
		RemoteWiki $wiki,
		string $special,
		string $filtered,
		Config $config
	) {
		switch ( $module ) {
			case 'core':
				$formDescriptor = self::buildDescriptorCore( $dbName, $ceMW, $context, $wiki, $config );
				break;
			case 'extensions':
				$formDescriptor = self::buildDescriptorExtensions( $dbName, $ceMW, $wiki, $config );
				break;
			case 'settings':
				$formDescriptor = self::buildDescriptorSettings( $dbName, $ceMW, $context, $wiki, $config, $filtered );
				break;
			case 'namespaces':
				$formDescriptor = self::buildDescriptorNamespaces( $dbName, $ceMW, $context, $special, $wiki, $config );
				break;
			case 'permissions':
				$formDescriptor = self::buildDescriptorPermissions( $dbName, $ceMW, $special, $config );
				break;
			default:
				throw new MWException( "{$module} not recognised" );
		}

		return $formDescriptor;
	}

	private static function buildDescriptorCore(
		string $dbName,
		bool $ceMW,
		IContextSource $context,
		RemoteWiki $wiki,
		Config $config
	) {
		$formDescriptor = [];

		$formDescriptor['dbname'] = [
			'label-message' => 'managewiki-label-dbname',
			'type' => 'text',
			'default' => $dbName,
			'disabled' => true,
			'section' => 'main'
		];

		if ( $ceMW && ( $config->get( 'DBname' ) == $config->get( 'CreateWikiGlobalWiki' ) ) && ( $wiki->getDBname() !== $config->get( 'CreateWikiGlobalWiki' ) ) ) {
			$mwActions = [
				( $wiki->isDeleted() ) ? 'undelete' : 'delete',
				( $wiki->isLocked() ) ? 'unlock' : 'lock'
			];

			foreach ( $mwActions as $mwAction ) {
				$formDescriptor[$mwAction] = [
					'type' => 'check',
					'label-message' => "managewiki-label-{$mwAction}wiki",
					'default' => false,
					'section' => 'main'
				];
			}
		}

		$formDescriptor += [
			'sitename' => [
				'label-message' => 'managewiki-label-sitename',
				'type' => 'text',
				'default' => $wiki->getSitename(),
				'disabled' => !$ceMW,
				'required' => true,
				'section' => 'main'
			],
			'language' => [
				'label-message' => 'managewiki-label-language',
				'type' => 'language',
				'default' => $wiki->getLanguage(),
				'disabled' => !$ceMW,
				'required' => true,
				'cssclass' => 'managewiki-infuse',
				'section' => 'main'
			]
		];

		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		$addedModules = [
			'private' => [
				'if' => $config->get( 'CreateWikiUsePrivateWikis' ),
				'type' => 'check',
				'default' => (bool)$wiki->isPrivate(),
				'access' => !$ceMW
			],
			'closed' => [
				'if' => $config->get( 'CreateWikiUseClosedWikis' ),
				'type' => 'check',
				'default' => (bool)$wiki->isClosed(),
				'access' => !$ceMW
			],
			'inactive' => [
				'if' => $config->get( 'CreateWikiUseInactiveWikis' ),
				'type' => 'check',
				'default' => (bool)$wiki->isInactive(),
				'access' => !$ceMW
			],
			'inactive-exempt' => [
				'if' => $config->get( 'CreateWikiUseInactiveWikis' ),
				'type' => 'check',
				'default' => (bool)$wiki->isInactiveExempt(),
				'access' => !$permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' )
			],
			'inactive-exempt-reason' => [
				'if' => $config->get( 'CreateWikiUseInactiveWikis' ) && $config->get( 'CreateWikiInactiveExemptReasonOptions' ),
				'hide-if' => [ '!==', 'wpinactive-exempt', '1' ],
				'type' => 'selectorother',
				'default' => $wiki->getInactiveExemptReason(),
				'access' => !$permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' ),
				'options' => $config->get( 'CreateWikiInactiveExemptReasonOptions' )
			],
			'server' => [
				'if' => $config->get( 'CreateWikiUseCustomDomains' ),
				'type' => 'text',
				'default' => $wiki->getServerName(),
				'access' => !$permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' )
			],
			'experimental' => [
				'if' => $config->get( 'CreateWikiUseExperimental' ),
				'type' => 'check',
				'default' => (bool)$wiki->isExperimental(),
				'access' => !$permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' )
			]
		];

		foreach ( $addedModules as $name => $data ) {
			if ( $data['if'] ) {
				$formDescriptor[$name] = [
					'type' => $data['type'],
					'label-message' => "managewiki-label-$name",
					'default' => $data['default'],
					'disabled' => $data['access'],
					'cssclass' => 'managewiki-infuse',
					'section' => 'main'
				];

				if ( $data['hide-if'] ?? false ) {
					$formDescriptor[$name]['hide-if'] = $data['hide-if'];
				}

				if ( $data['options'] ?? false ) {
					$formDescriptor[$name]['options'] = $data['options'];
				}
			}
		}

		if ( $config->get( 'CreateWikiUseCategories' ) ) {
			$formDescriptor['category'] = [
				'type' => 'select',
				'label-message' => 'managewiki-label-category',
				'options' => $config->get( 'CreateWikiCategories' ),
				'default' => $wiki->getCategory(),
				'disabled' => !$ceMW,
				'cssclass' => 'managewiki-infuse',
				'section' => 'main'
			];
		}

		if ( ExtensionRegistry::getInstance()->isLoaded( 'WikiDiscover' ) && $config->get( 'WikiDiscoverUseDescriptions' ) ) {
			$mwSettings = new ManageWikiSettings( $dbName );
			$setList = $mwSettings->list();

			$formDescriptor['description'] = [
				'label-message' => 'managewiki-label-description',
				'type' => 'text',
				'default' => $setList['wgWikiDiscoverDescription'] ?? '',
				'maxlength' => 512,
				'disabled' => !$ceMW,
				'section' => 'main'
			];
		}

		$hookRunner = MediaWikiServices::getInstance()->get( 'ManageWikiHookRunner' );
		$hookRunner->onManageWikiCoreAddFormFields( $ceMW, $context, $dbName, $formDescriptor );

		if ( $config->get( 'CreateWikiDatabaseClusters' ) ) {
			$clusterList = array_merge( (array)$config->get( 'CreateWikiDatabaseClusters' ), (array)$config->get( 'CreateWikiDatabaseClustersInactive' ) );
			$formDescriptor['dbcluster'] = [
				'type' => 'select',
				'label-message' => 'managewiki-label-dbcluster',
				'options' => array_combine( $clusterList, $clusterList ),
				'default' => $wiki->getDBCluster(),
				'disabled' => !$permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' ),
				'cssclass' => 'managewiki-infuse',
				'section' => 'main'
			];
		}

		return $formDescriptor;
	}

	private static function buildDescriptorExtensions(
		string $dbName,
		bool $ceMW,
		RemoteWiki $wiki,
		Config $config
	) {
		$mwExt = new ManageWikiExtensions( $dbName );
		$extList = $mwExt->list();

		$manageWikiSettings = $config->get( 'ManageWikiSettings' );

		$queue = array_fill_keys( array_merge(
				glob( $config->get( 'ExtensionDirectory' ) . '/*/extension*.json' ),
				glob( $config->get( 'StyleDirectory' ) . '/*/skin.json' )
			),
		true );

		$processor = new ExtensionProcessor();

		foreach ( $queue as $path => $mtime ) {
			$json = file_get_contents( $path );
			$info = json_decode( $json, true );
			$version = $info['manifest_version'];

			$processor->extractInfo( $path, $info, $version );
		}

		$data = $processor->getExtractedInfo();

		$credits = $data['credits'];
		$legacyCredits = $config->get( 'ExtensionCredits' );
		if ( $legacyCredits ) {
			$credits = array_merge( $credits, array_values(
					array_merge( ...array_values( $legacyCredits ) )
				)
			);
		}

		$formDescriptor = [];

		foreach ( $config->get( 'ManageWikiExtensions' ) as $name => $ext ) {
			$filteredList = array_filter( $manageWikiSettings, static function ( $value ) use ( $name ) {
				return $value['from'] == $name;
			} );

			$hasSettings = count( array_diff_assoc( $filteredList, array_keys( $manageWikiSettings ) ) ) > 0;

			$mwRequirements = $ext['requires'] ? ManageWikiRequirements::process( $ext['requires'], $extList, false, $wiki ) : true;

			$help = [];
			$conflictLabel = wfMessage( 'managewiki-conflicts' )->text();
			$requiresLabel = wfMessage( 'managewiki-requires' )->text();

			if ( $ext['conflicts'] ) {
				$help[] = "{$conflictLabel} {$ext['conflicts']}<br/>";
			}

			if ( $ext['requires'] ) {
				$requires = [];
				foreach ( $ext['requires'] as $require => $data ) {
					if ( is_array( $data ) ) {
						foreach ( $data as $index => $element ) {
							if ( is_array( $element ) ) {
								$data[$index] = '( ' . implode( ' OR ', $element ) . ' )';
							}
						}
					}

					$requires[] = ucfirst( $require ) . " - " . ( is_array( $data ) ? implode( ', ', $data ) : $data );
				}

				$help[] = "{$requiresLabel}: " . implode( ' & ', $requires ) . '<br/>';
			}

			$descriptionmsg = array_column( $credits, 'descriptionmsg', 'name' )[ $ext['name'] ] ?? false;
			$description = array_column( $credits, 'description', 'name' )[ $ext['name'] ] ?? null;

			$namemsg = array_column( $credits, 'namemsg', 'name' )[ $ext['name'] ] ?? false;
			$extname = array_column( $credits, 'name', 'name' )[ $ext['name'] ] ?? null;

			$extDescription = ( $ext['description'] ?? false ) ? ( wfMessage( $ext['description'] )->exists() ? wfMessage( $ext['description'] )->parse() : $ext['description'] ) : null;
			$extDisplayName = ( $ext['displayname'] ?? false ) ? ( wfMessage( $ext['displayname'] )->exists() ? wfMessage( $ext['displayname'] )->parse() : $ext['displayname'] ) : null;

			$help[] = $extDescription ?? ( $descriptionmsg ? ( wfMessage( $descriptionmsg )->exists() ? wfMessage( $descriptionmsg )->parse() : $descriptionmsg ) : null ) ?? $description;

			if ( $ext['help'] ?? false ) {
				$help[] = '<br/>' . $ext['help'];
			}

			if ( $hasSettings && in_array( $name, $extList ) ) {
				$help[] = '<br/>' . Linker::makeExternalLink( SpecialPage::getTitleFor( 'ManageWiki', 'settings' )->getFullURL() . '/' . $name, wfMessage( 'managewiki-extension-settings' )->text() );
			}

			$formDescriptor["ext-$name"] = [
				'type' => 'check',
				'label-message' => [
					'managewiki-extension-name',
					$ext['linkPage'],
					$extDisplayName ?? ( $namemsg ? wfMessage( $namemsg )->text() : $extname ) ?? $ext['name']
				],
				'default' => in_array( $name, $extList ),
				'disabled' => ( $ceMW ) ? !$mwRequirements : true,
				'help' => implode( ' ', $help ),
				'section' => $ext['section'],
			];
		}

		return $formDescriptor;
	}

	private static function buildDescriptorSettings(
		string $dbName,
		bool $ceMW,
		IContextSource $context,
		RemoteWiki $wiki,
		Config $config,
		string $filtered
	) {
		$mwExt = new ManageWikiExtensions( $dbName );
		$extList = $mwExt->list();
		$mwSettings = new ManageWikiSettings( $dbName );
		$setList = $mwSettings->list();
		$mwPermissions = new ManageWikiPermissions( $dbName );
		$groupList = array_keys( $mwPermissions->list() );

		$manageWikiSettings = $config->get( 'ManageWikiSettings' );

		$filteredList = array_filter( $manageWikiSettings, static function ( $value ) use ( $filtered, $extList ) {
			return $value['from'] == strtolower( $filtered ) && ( in_array( $value['from'], $extList ) || ( array_key_exists( 'global', $value ) && $value['global'] ) );
		} );

		$formDescriptor = [];
		$filteredSettings = array_diff_assoc( $filteredList, array_keys( $manageWikiSettings ) ) ?: $manageWikiSettings;

		foreach ( $filteredSettings as $name => $set ) {
			$mwRequirements = $set['requires'] ? ManageWikiRequirements::process( $set['requires'], $extList, false, $wiki ) : true;

			$add = ( isset( $set['requires']['visibility'] ) ? $mwRequirements : true ) && ( $set['global'] ?? false || in_array( $set['from'], $extList ) );
			$disabled = ( $ceMW ) ? !$mwRequirements : true;

			$msgName = wfMessage( "managewiki-setting-{$name}-name" );
			$msgHelp = wfMessage( "managewiki-setting-{$name}-help" );

			if ( $add ) {
				$value = $setList[$name] ?? null;
				if ( isset( $set['associativeKey'] ) ) {
					$value = $setList[$name][ $set['associativeKey'] ] ?? $set['overridedefault'][ $set['associativeKey'] ];
				}

				$configs = ManageWikiTypes::process( $config, $disabled, $groupList, 'settings', $set, $value, $name );

				$help = ( $msgHelp->exists() ) ? $msgHelp->text() : $set['help'];
				if ( $set['requires'] ) {
					$requires = [];
					$requiresLabel = wfMessage( 'managewiki-requires' )->text();

					foreach ( $set['requires'] as $require => $data ) {
						if ( is_array( $data ) ) {
							foreach ( $data as $index => $element ) {
								if ( is_array( $element ) ) {
									$data[$index] = '( ' . implode( ' OR ', $element ) . ' )';
								}
							}
						}

						$requires[] = ucfirst( $require ) . " - " . ( is_array( $data ) ? implode( ', ', $data ) : $data );
					}

					$help .= "<br />{$requiresLabel}: " . implode( ' & ', $requires );
				}

				// Hack to prevent "implicit submission". See T275588 for more
				if ( ( $configs['type'] ?? '' ) === 'cloner' ) {
					$formDescriptor["fake-submit-$name"] = [
						'type' => 'submit',
						'disabled' => true,
						'section' => $set['section'],
						'cssclass' => 'managewiki-fakesubmit',
					];
				}

				$varName = " (\${$name})";
				if ( isset( $set['associativeKey'] ) ) {
					$varName = " (\${$name}['{$set['associativeKey']}'])";
				}

				$formDescriptor["set-$name"] = [
					'label' => ( ( $msgName->exists() ) ? $msgName->text() : $set['name'] ) . $varName,
					'disabled' => $disabled,
					'help' => $help,
					'cssclass' => 'managewiki-infuse',
					'section' => $set['section']
				] + $configs;
			}
		}

		return $formDescriptor;
	}

	private static function buildDescriptorNamespaces(
		string $dbName,
		bool $ceMW,
		IContextSource $context,
		string $special,
		RemoteWiki $wiki,
		Config $config
	) {
		$mwNamespace = new ManageWikiNamespaces( $dbName );

		$mwExt = new ManageWikiExtensions( $dbName );
		$extList = $mwExt->list();

		$formDescriptor = [];
		$nsID = [];

		$nsID['namespace'] = (int)$special;

		if (
			$mwNamespace->list( (int)$special + 1 )['name'] ||
			!$mwNamespace->list( (int)$special )['name']
		) {
			$nsID['namespacetalk'] = (int)$special + 1;
		}

		$session = $context->getRequest()->getSession();

		foreach ( $nsID as $name => $id ) {
			$namespaceData = $mwNamespace->list( $id );

			$create = ucfirst( $session->get( 'create' ) ) . ( $name == 'namespacetalk' && $session->get( 'create' ) ? '_talk' : null );

			$formDescriptor += [
				"namespace-$name" => [
					'type' => 'text',
					'label' => wfMessage( "namespaces-$name" )->text() . ' ($wgExtraNamespaces)',
					'default' => $namespaceData['name'] ?: $create,
					'disabled' => ( $namespaceData['core'] || !$ceMW ),
					'required' => true,
					'section' => $name
				],
				"content-$name" => [
					'type' => 'check',
					'label' => wfMessage( 'namespaces-content' )->text() . ' ($wgContentNamespaces)',
					'default' => $namespaceData['content'],
					'disabled' => !$ceMW,
					'section' => $name
				],
				"subpages-$name" => [
					'type' => 'check',
					'label' => wfMessage( 'namespaces-subpages' )->text() . ' ($wgNamespacesWithSubpages)',
					'default' => $namespaceData['subpages'],
					'disabled' => !$ceMW,
					'section' => $name
				],
				"search-$name" => [
					'type' => 'check',
					'label' => wfMessage( 'namespaces-search' )->text() . ' ($wgNamespacesToBeSearchedDefault)',
					'default' => $namespaceData['searchable'],
					'disabled' => !$ceMW,
					'section' => $name
				],
				"contentmodel-$name" => [
					'label' => wfMessage( 'namespaces-contentmodel' )->text() . ' ($wgNamespaceContentModels)',
					'cssclass' => 'managewiki-infuse',
					'disabled' => !$ceMW,
					'section' => $name
				] + ManageWikiTypes::process( false, false, false, 'namespaces', [], $namespaceData['contentmodel'], false, false, 'contentmodel' ),
				"protection-$name" => [
					'type' => 'combobox',
					'label' => wfMessage( 'namespaces-protection' )->text() . ' ($wgNamespaceProtection)',
					'cssclass' => 'managewiki-infuse',
					'default' => $namespaceData['protection'],
					'options' => [
						'None' => '',
						'editinterface' => 'editinterface',
						'editsemiprotected' => 'editsemiprotected',
						'editprotected' => 'editprotected'
					],
					'disabled' => !$ceMW,
					'section' => $name
				]
			];

			foreach ( (array)$config->get( 'ManageWikiNamespacesAdditional' ) as $key => $a ) {
				$mwRequirements = $a['requires'] ? ManageWikiRequirements::process( $a['requires'], $extList, false, $wiki ) : true;

				$add = ( isset( $a['requires']['visibility'] ) ? $mwRequirements : true ) && ( ( $a['from'] == 'mediawiki' ) || ( in_array( $a['from'], $extList ) ) );
				$disabled = ( $ceMW ) ? !$mwRequirements : true;

				$msgName = wfMessage( "managewiki-namespaces-{$key}-name" );
				$msgHelp = wfMessage( "managewiki-namespaces-{$key}-help" );

				if ( $add && ( $a['main'] && $name == 'namespace' || $a['talk'] && $name == 'namespacetalk' ) && !in_array( $id, (array)( $a['excluded'] ?? [] ) ) && in_array( $id, (array)( $a['only'] ?? [ $id ] ) ) ) {
					if ( is_array( $a['overridedefault'] ) ) {
						$a['overridedefault'] = $a['overridedefault'][$id] ?? $a['overridedefault']['default'];
					}

					$configs = ManageWikiTypes::process( $config, $disabled, false, 'namespaces', $a, $namespaceData['additional'][$key] ?? null, false, $a['overridedefault'], $a['type'] );

					$help = ( $msgHelp->exists() ) ? $msgHelp->text() : $a['help'];
					if ( $a['requires'] ) {
						$requires = [];
						$requiresLabel = wfMessage( 'managewiki-requires' )->text();

						foreach ( $a['requires'] as $require => $data ) {
							if ( is_array( $data ) ) {
								foreach ( $data as $index => $element ) {
									if ( is_array( $element ) ) {
										$data[$index] = '( ' . implode( ' OR ', $element ) . ' )';
									}
								}
							}

							$requires[] = ucfirst( $require ) . " - " . ( is_array( $data ) ? implode( ', ', $data ) : $data );
						}

						$help .= "<br />{$requiresLabel}: " . implode( ' & ', $requires );
					}

					$formDescriptor["$key-$name"] = [
						'label' => ( ( $msgName->exists() ) ? $msgName->text() : $a['name'] ) . " (\${$key})",
						'help' => $help,
						'cssclass' => 'managewiki-infuse',
						'disabled' => $disabled,
						'section' => $name
					] + $configs;
				}
			}

			$formDescriptor["aliases-$name"] = [
				'label' => wfMessage( 'namespaces-aliases' )->text() . ' ($wgNamespaceAliases)',
				'cssclass' => 'managewiki-infuse',
				'disabled' => !$ceMW,
				'section' => $name
			] + ManageWikiTypes::process( false, false, false, 'namespaces', [], $namespaceData['aliases'], false, [], 'texts' );
		}

		if ( $ceMW && !$formDescriptor['namespace-namespace']['disabled'] ) {
			$craftedNamespaces = [];
			$canDelete = false;

			foreach ( $mwNamespace->list() as $id => $config ) {
				if ( $id % 2 ) {
					continue;
				}

				if ( $id !== $nsID['namespace'] ) {
					$craftedNamespaces[$config['name']] = $id;
				} else {
					// Existing namespace
					$canDelete = true;
				}
			}

			$formDescriptor += [
				'delete-checkbox' => [
					'type' => 'check',
					'label-message' => 'namespaces-delete-checkbox',
					'default' => 0,
					'disabled' => !$canDelete,
					'section' => 'delete'
				],
				'delete-migrate-to' => [
					'type' => 'select',
					'label-message' => 'namespaces-migrate-to',
					'cssclass' => 'managewiki-infuse',
					'options' => $craftedNamespaces,
					'default' => 0,
					'disabled' => !$canDelete,
					'section' => 'delete'
				]
			];
		}

		return $formDescriptor;
	}

	private static function buildDescriptorPermissions(
		string $wiki,
		bool &$ceMW,
		string $group,
		Config $config
	) {
		if ( in_array( $group, $config->get( 'ManageWikiPermissionsDisallowedGroups' ) ) ) {
			$ceMW = false;
		}

		$mwPermissions = new ManageWikiPermissions( $wiki );
		$permList = $mwPermissions->list( $group );

		$matrixConstruct = [
			'wgAddGroups' => $permList['addgroups'],
			'wgRemoveGroups' => $permList['removegroups'],
			'wgGroupsAddToSelf' => $permList['addself'],
			'wgGroupsRemoveFromSelf' => $permList['removeself']
		];

		$groupData = [
			'allPermissions' => array_diff( MediaWikiServices::getInstance()->getPermissionManager()->getAllPermissions(), ( isset( $config->get( 'ManageWikiPermissionsDisallowedRights' )[$group] ) ) ? array_merge( $config->get( 'ManageWikiPermissionsDisallowedRights' )[$group], $config->get( 'ManageWikiPermissionsDisallowedRights' )['any'] ) : $config->get( 'ManageWikiPermissionsDisallowedRights' )['any'] ),
			'assignedPermissions' => $permList['permissions'] ?? [],
			'allGroups' => array_diff( array_keys( $mwPermissions->list() ), $config->get( 'ManageWikiPermissionsDisallowedGroups' ), User::getImplicitGroups() ),
			'groupMatrix' => ManageWiki::handleMatrix( json_encode( $matrixConstruct ), 'php' ),
			'autopromote' => $permList['autopromote'] ?? null
		];

		$formDescriptor = [
			'assigned' => [
				'type' => 'info',
				'default' => wfMessage( 'managewiki-permissions-assigned' )->text(),
				'section' => 'assigned'
			],
			'unassigned' => [
				'type' => 'info',
				'default' => wfMessage( 'managewiki-permissions-unassigned' )->text(),
				'section' => 'unassigned'
			],
			'group' => [
				'type' => 'info',
				'default' => wfMessage( 'managewiki-permissions-group' )->text(),
				'section' => 'group'
			],
			'autopromote' => [
				'type' => 'info',
				'default' => wfMessage( 'managewiki-permissions-autopromote' )->text(),
				'section' => 'autopromote'
			]
		];

		foreach ( $groupData['allPermissions'] as $perm ) {
			$assigned = in_array( $perm, $groupData['assignedPermissions'] );

			$formDescriptor["right-{$perm}"] = [
				'type' => 'check',
				'label' => $perm,
				'help' => User::getRightDescription( $perm ),
				'section' => ( $assigned ) ? 'assigned' : 'unassigned',
				'default' => $assigned,
				'disabled' => !$ceMW
			];
		}

		$rowsBuilt = [];

		foreach ( $groupData['allGroups'] as $group ) {
			$rowsBuilt[UserGroupMembership::getGroupName( $group )] = $group;
		}

		$formDescriptor['group-matrix'] = [
			'type' => 'checkmatrix',
			'columns' => [
				wfMessage( 'managewiki-permissions-addall' )->text() => 'wgAddGroups',
				wfMessage( 'managewiki-permissions-removeall' )->text() => 'wgRemoveGroups',
				wfMessage( 'managewiki-permissions-addself' )->text() => 'wgGroupsAddToSelf',
				wfMessage( 'managewiki-permissions-removeself' )->text() => 'wgGroupsRemoveFromSelf'
			],
			'rows' => $rowsBuilt,
			'section' => 'group',
			'default' => $groupData['groupMatrix'],
			'disabled' => !$ceMW
		];

		// This is not a good method but it is a method.
		$aP = $groupData['autopromote'];
		$aPArray = [];
		foreach ( (array)$aP as $element ) {
			if ( is_array( $element ) ) {
				$aPArray[$element[0]] = $element[0] === APCOND_INGROUPS ?
					array_slice( $element, 1 ) : $element[1];
			}
		}

		$formDescriptor += [
			'enable' => [
				'type' => 'check',
				'label-message' => 'managewiki-permissions-autopromote-enable',
				'default' => $aP !== null,
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'conds' => [
				'type' => 'select',
				'label-message' => 'managewiki-permissions-autopromote-conds',
				'options' => [
					wfMessage( 'managewiki-permissions-autopromote-conds-and' )->text() => '&',
					wfMessage( 'managewiki-permissions-autopromote-conds-or' )->text() => '|',
					wfMessage( 'managewiki-permissions-autopromote-conds-not' )->text() => '!'
				],
				'default' => ( $aP === null ) ? '&' : $aP[0],
				'disabled' => !$ceMW,
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'section' => 'autopromote'
			],
			'once' => [
				'type' => 'check',
				'label-message' => 'managewiki-permissions-autopromote-once',
				'default' => is_int( array_search( 'once', (array)$aP ) ),
				'disabled' => !$ceMW,
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'section' => 'autopromote'
			],
			'editcount' => [
				'type' => 'int',
				'label-message' => 'managewiki-permissions-autopromote-editcount',
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'min' => 0,
				'default' => $aPArray[APCOND_EDITCOUNT] ?? 0,
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'age' => [
				'type' => 'int',
				'label-message' => 'managewiki-permissions-autopromote-age',
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'min' => 0,
				'default' => isset( $aPArray[APCOND_AGE] ) ? $aPArray[APCOND_AGE] / 86400 : 0,
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'emailconfirmed' => [
				'type' => 'check',
				'label-message' => 'managewiki-permissions-autopromote-email',
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'default' => is_int( array_search( APCOND_EMAILCONFIRMED, (array)$aP ) ),
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'blocked' => [
				'type' => 'check',
				'label-message' => 'managewiki-permissions-autopromote-blocked',
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'default' => is_int( array_search( APCOND_BLOCKED, (array)$aP ) ),
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'bot' => [
				'type' => 'check',
				'label-message' => 'managewiki-permissions-autopromote-bot',
				'hide-if' => [ '!==', 'wpenable', '1' ],
				'default' => is_int( array_search( APCOND_ISBOT, (array)$aP ) ),
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			],
			'groups' => [
				'type' => 'multiselect',
				'label-message' => 'managewiki-permissions-autopromote-groups',
				'options' => $rowsBuilt,
				'hide-if' => [ 'OR', [ '!==', 'wpenable', '1' ], [ '===', 'wpconds', '|' ] ],
				'default' => $aPArray[APCOND_INGROUPS] ?? [],
				'disabled' => !$ceMW,
				'section' => 'autopromote'
			]
		];

		if ( $ceMW && ( count( $permList['permissions'] ) > 0 ) ) {
			$formDescriptor['delete-checkbox'] = [
				'type' => 'check',
				'label-message' => 'permissions-delete-checkbox',
				'default' => 0,
				'section' => 'delete'
			];
		}

		return $formDescriptor;
	}

	public static function submissionHandler(
		array $formData,
		HTMLForm $form,
		string $module,
		string $dbName,
		IContextSource $context,
		RemoteWiki $wiki,
		DBConnRef $dbw,
		Config $config,
		string $special = '',
		string $filtered = ''
	) {
		switch ( $module ) {
			case 'core':
				$mwReturn = self::submissionCore( $formData, $dbName, $context, $wiki, $dbw, $config );
				break;
			case 'extensions':
				$mwReturn = self::submissionExtensions( $formData, $dbName, $config );
				break;
			case 'settings':
				$mwReturn = self::submissionSettings( $formData, $dbName, $filtered, $context, $wiki, $config );
				break;
			case 'namespaces':
				$mwReturn = self::submissionNamespaces( $formData, $dbName, $special, $config );
				$form->getRequest()->getSession()->remove( 'create' );
				break;
			case 'permissions':
				$mwReturn = self::submissionPermissions( $formData, $dbName, $special, $config );
				break;
			default:
				throw new MWException( "{$module} not recognised" );
		}

		if ( $mwReturn->changes ) {
			$mwReturn->commit();

			if ( $module != 'permissions' ) {
				$mwReturn->logParams['4::wiki'] = $dbName;
			}

			$mwLogEntry = new ManualLogEntry( 'managewiki', $mwReturn->log );
			$mwLogEntry->setPerformer( $context->getUser() );
			$mwLogEntry->setTarget( $form->getTitle() );
			$mwLogEntry->setComment( $formData['reason'] );
			$mwLogEntry->setParameters( $mwReturn->logParams );
			$mwLogID = $mwLogEntry->insert();
			$mwLogEntry->publish( $mwLogID );
		} else {
			return [ [ 'managewiki-changes-none' => null ] ];
		}

		if ( $mwReturn->errors ?? [] && $module == 'permissions' ) {
			return $mwReturn->errors;
		}

		return $mwReturn->errors ?? [];
	}

	private static function submissionCore(
		array $formData,
		string $dbName,
		IContextSource $context,
		RemoteWiki $wiki,
		DBConnRef $dbw,
		Config $config
	) {
		$mwActions = [
			'delete',
			'undelete',
			'lock',
			'unlock'
		];

		foreach ( $mwActions as $mwAction ) {
			if ( $formData[$mwAction] ?? false ) {
				$wiki->$mwAction();

				return $wiki;
			}
		}

		if ( $config->get( 'CreateWikiUsePrivateWikis' ) && ( $wiki->isPrivate() != $formData['private'] ) ) {
				( $formData['private'] ) ? $wiki->markPrivate() : $wiki->markPublic();
		}

		if ( $config->get( 'CreateWikiUseExperimental' ) && ( $wiki->isExperimental() != $formData['experimental'] ) ) {
				( $formData['experimental'] ) ? $wiki->markExperimental() : $wiki->unMarkExperimental();
		}

		if ( $config->get( 'CreateWikiUseClosedWikis' ) ) {
			$closed = (bool)$wiki->isClosed();
			$newClosed = $formData['closed'];

			if ( $newClosed && ( $closed != $newClosed ) ) {
				$wiki->markClosed();
			} elseif ( !$newClosed && ( $closed != $newClosed ) ) {
				$wiki->markActive();
			}
		}

		if ( $config->get( 'CreateWikiUseInactiveWikis' ) ) {
			$newInactive = $formData['inactive'];
			$inactive = (bool)$wiki->isInactive();
			$newInactiveExempt = $formData['inactive-exempt'];

			if ( $newInactive != $inactive ) {
				( $newInactive ) ? $wiki->markInactive() : $wiki->markActive();
			}

			$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
			if ( $permissionManager->userHasRight( $context->getUser(), 'managewiki-restricted' ) ) {
				if ( ( $newInactiveExempt != $wiki->isInactiveExempt() ) ) {
					if ( $newInactiveExempt ) {
						$wiki->markExempt();
					} else {
						$wiki->unExempt();
					}
				}

				$newInactiveExemptReason = $formData['inactive-exempt-reason'] ?? false;
				if ( $newInactiveExemptReason && ( $newInactiveExemptReason != $wiki->getInactiveExemptReason() ) ) {
					$wiki->setInactiveExemptReason( $formData['inactive-exempt-reason'] );
				}
			}
		}

		if ( $config->get( 'CreateWikiUseCategories' ) && ( $formData['category'] != $wiki->getCategory() ) ) {
			$wiki->setCategory( $formData['category'] );
		}

		if ( $config->get( 'CreateWikiUseCustomDomains' ) && ( $formData['server'] != $wiki->getServerName() ) ) {
			$wiki->setServerName( $formData['server'] );
		}

		if ( $formData['sitename'] != $wiki->getSitename() ) {
			$wiki->setSitename( $formData['sitename'] );
		}

		if ( $formData['language'] != $wiki->getLanguage() ) {
			$wiki->setLanguage( $formData['language'] );
		}

		if ( $config->get( 'CreateWikiDatabaseClusters' ) && ( $formData['dbcluster'] != $wiki->getDBCluster() ) ) {
			$wiki->setDBCluster( $formData['dbcluster'] );
		}

		if ( ExtensionRegistry::getInstance()->isLoaded( 'WikiDiscover' ) && $config->get( 'WikiDiscoverUseDescriptions' ) ) {
			$mwSettings = new ManageWikiSettings( $dbName );

			$description = $mwSettings->list()['wgWikiDiscoverDescription'] ?? '';

			if ( $formData['description'] != $description ) {
				$mwSettings->modify( [ 'wgWikiDiscoverDescription' => $formData['description'] ] );

				$mwSettings->commit();

				$wiki->changes['description'] = [
					'old' => $description,
					'new' => $formData['description']
				];
			}
		}

		$hookRunner = MediaWikiServices::getInstance()->get( 'ManageWikiHookRunner' );
		$hookRunner->onManageWikiCoreFormSubmission( $context, $dbName, $dbw, $formData, $wiki );

		return $wiki;
	}

	private static function submissionExtensions(
		array $formData,
		string $dbName,
		Config $config
	) {
		$mwExt = new ManageWikiExtensions( $dbName );
		$newExtList = [];

		foreach ( $config->get( 'ManageWikiExtensions' ) as $name => $ext ) {
			if ( $formData["ext-{$name}"] ) {
				$newExtList[] = $name;
			}
		}

		$mwExt->overwriteAll( $newExtList );

		return $mwExt;
	}

	private static function submissionSettings(
		array $formData,
		string $dbName,
		string $filtered,
		IContextSource $context,
		RemoteWiki $wiki,
		Config $config
	) {
		$mwExt = new ManageWikiExtensions( $dbName );
		$extList = $mwExt->list();

		$mwSettings = new ManageWikiSettings( $dbName );
		$settingsList = $mwSettings->list();

		$settingsArray = [];

		foreach ( $config->get( 'ManageWikiSettings' ) as $name => $set ) {
			// No need to do anything if setting does not 'exist'
			if ( !isset( $formData["set-$name"] ) ) {
				continue;
			}

			$current = $settingsList[$name] ?? $set['overridedefault'];
			if ( isset( $set['associativeKey'] ) ) {
				$current = $settingsList[$name][ $set['associativeKey'] ] ?? $set['overridedefault'][ $set['associativeKey'] ];
			}

			$mwAllowed = $set['requires'] ? ManageWikiRequirements::process( $set['requires'], $extList, false, $wiki ) : true;
			$type = $set['type'];

			$value = $formData["set-$name"];

			switch ( $type ) {
				case 'integers':
					$value = array_column( $value, 'value' );
					$value = array_filter( $value );
					$value = array_map( 'intval', $value );

					break;
				case 'list-multi-bool':
					$setValue = [];
					foreach ( $set['allopts'] as $opt ) {
						$setValue[$opt] = in_array( $opt, $value );
					}

					$value = $setValue;

					break;
				case 'matrix':
					$current = ManageWiki::handleMatrix( $current, 'php' );
					$value = ManageWiki::handleMatrix( $value, 'phparray' );

					break;
				case 'text':
					if ( !$value ) {
						$value = $set['overridedefault'];
					}

					break;
				case 'texts':
					$value = array_column( $value, 'value' );
					$value = array_filter( $value );

					break;
				case 'users':
				case 'wikipages':
					$value = $value ? explode( "\n", $value ) : [];

					break;
			}

			if ( !$mwAllowed ) {
				$value = $current;
			}

			if ( isset( $set['associativeKey'] ) ) {
				$settingsArray[$name] = $set['overridedefault'];
				$settingsArray[$name][ $set['associativeKey'] ] = $value;
			} else {
				$settingsArray[$name] = $value;
			}
		}

		$manageWikiSettings = $config->get( 'ManageWikiSettings' );
		$filteredList = array_filter( $manageWikiSettings, static function ( $value ) use ( $filtered, $extList ) {
			return $value['from'] == strtolower( $filtered ) && ( in_array( $value['from'], $extList ) || ( array_key_exists( 'global', $value ) && $value['global'] ) );
		} );

		$remove = !( count( array_diff_assoc( $filteredList, array_keys( $manageWikiSettings ) ) ) > 0 );

		$mwSettings->overwriteAll( $settingsArray, $remove );

		return $mwSettings;
	}

	private static function submissionNamespaces(
		array $formData,
		string $dbName,
		string $special,
		Config $config
	) {
		$mwNamespaces = new ManageWikiNamespaces( $dbName );

		if ( $formData['delete-checkbox'] ) {
			$mwNamespaces->remove( $special, $formData['delete-migrate-to'] );
			$mwNamespaces->remove( (int)$special + 1, $formData['delete-migrate-to'] + 1 );
			return $mwNamespaces;
		}

		$nsID = [
			'namespace' => (int)$special,
			'namespacetalk' => (int)$special + 1
		];

		foreach ( $nsID as $name => $id ) {
			$namespaceName = str_replace( ' ', '_', $formData["namespace-$name"] );

			$additionalBuilt = [];

			foreach ( (array)$config->get( 'ManageWikiNamespacesAdditional' ) as $key => $a ) {
				if ( isset( $formData["$key-$name"] ) ) {
					$additionalBuilt[$key] = $formData["$key-$name"];
				}
			}

			$build = [
				'name' => $namespaceName,
				'searchable' => (int)$formData["search-$name"],
				'subpages' => (int)$formData["subpages-$name"],
				'protection' => $formData["protection-$name"],
				'content' => (int)$formData["content-$name"],
				'contentmodel' => $formData["contentmodel-$name"],
				'aliases' => array_filter( array_column( $formData["aliases-$name"], 'value' ) ),
				'additional' => $additionalBuilt
			];

			$mwNamespaces->modify( $id, $build );
		}

		return $mwNamespaces;
	}

	private static function submissionPermissions(
		array $formData,
		string $wiki,
		string $group,
		Config $config
	) {
		$mwPermissions = new ManageWikiPermissions( $wiki );
		$permList = $mwPermissions->list( $group );
		$assignablePerms = array_diff( MediaWikiServices::getInstance()->getPermissionManager()->getAllPermissions(), ( isset( $config->get( 'ManageWikiPermissionsDisallowedRights' )[$group] ) ) ? array_merge( $config->get( 'ManageWikiPermissionsDisallowedRights' )[$group], $config->get( 'ManageWikiPermissionsDisallowedRights' )['any'] ) : $config->get( 'ManageWikiPermissionsDisallowedRights' )['any'] );

		// Early escape for deletion
		if ( $formData['delete-checkbox'] ?? false ) {
			$mwPermissions->remove( $group );
			return $mwPermissions;
		}

		$permData = [];

		$addedPerms = [];
		$removedPerms = [];

		foreach ( $assignablePerms as $perm ) {
			if ( $formData["right-$perm"] && !is_int( array_search( $perm, $permList['permissions'] ) ) ) {
				$addedPerms[] = $perm;
			} elseif ( !$formData["right-$perm"] && is_int( array_search( $perm, $permList['permissions'] ) ) ) {
				$removedPerms[] = $perm;
			}
		}

		// Add permission changes to permData
		$permData['permissions'] = [
			'add' => $addedPerms,
			'remove' => $removedPerms
		];

		$newMatrix = ManageWiki::handleMatrix( $formData['group-matrix'], 'phparray' );

		$matrixNew = [
			'addgroups' => array_diff( $newMatrix['wgAddGroups'] ?? [], $permList['addgroups'] ),
			'removegroups' => array_diff( $newMatrix['wgRemoveGroups'] ?? [], $permList['removegroups'] ),
			'addself' => array_diff( $newMatrix['wgGroupsAddToSelf'] ?? [], $permList['addself'] ),
			'removeself' => array_diff( $newMatrix['wgGroupsRemoveFromSelf'] ?? [], $permList['removeself'] )
		];

		$matrixOld = [
			'addgroups' => array_diff( $permList['addgroups'], $newMatrix['wgAddGroups'] ?? [] ),
			'removegroups' => array_diff( $permList['removegroups'], $newMatrix['wgRemoveGroups'] ?? [] ),
			'addself' => array_diff( $permList['addself'], $newMatrix['wgGroupsAddToSelf'] ?? [] ),
			'removeself' => array_diff( $permList['removeself'], $newMatrix['wgGroupsRemoveFromSelf'] ?? [] )
		];

		foreach ( $matrixNew as $type => $array ) {
			$newArray = [];
			foreach ( $array as $name ) {
				$newArray[] = $name;
			}

			$permData[$type]['add'] = $newArray;
		}

		foreach ( $matrixOld as $type => $array ) {
			$newArray = [];

			foreach ( $array as $name ) {
				$newArray[] = $name;
			}

			$permData[$type]['remove'] = $newArray;
		}

		$aE = $formData['enable'];

		$aPBuild = $aE ? [
			$formData['conds']
		] : [];

		if ( count( $aPBuild ) != 0 ) {
			$loopBuild = [
				'once' => 'once',
				'editcount' => [ APCOND_EDITCOUNT, (int)$formData['editcount'] ],
				'age' => [ APCOND_AGE, (int)$formData['age'] * 86400 ],
				'emailconfirmed' => APCOND_EMAILCONFIRMED,
				'blocked' => APCOND_BLOCKED,
				'bot' => APCOND_ISBOT,
				'groups' => array_merge( [ APCOND_INGROUPS ], $formData['groups'] )
			];

			foreach ( $loopBuild as $type => $value ) {
				if ( $formData[$type] ) {
					$aPBuild[] = $value;
				}
			}
		}

		$permData['autopromote'] = ( count( $aPBuild ) <= 1 ) ? null : $aPBuild;

		if ( !in_array( $group, $config->get( 'ManageWikiPermissionsPermanentGroups' ) ) && ( count( $permData['permissions']['remove'] ) > 0 ) && ( count( $permList['permissions'] ) == count( $permData['permissions']['remove'] ) ) ) {
			$mwPermissions->remove( $group );
		} else {
			$mwPermissions->modify( $group, $permData );
		}

		return $mwPermissions;
	}
}
