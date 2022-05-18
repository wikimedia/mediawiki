<?php
/**
 * Schema containing merge strategies to supplement the information coming from
 * DefaultSettings.php. Only used in Setup.php if MW_USE_LEGACY_DEFAULT_SETTINGS
 * is set.
 *
 * @todo Remove this when it is no longer needed.
 */
use MediaWiki\MainConfigNames;

return [
	'config-schema' => [
		MainConfigNames::AuthManagerAutoConfig => [
			'mergeStrategy' => 'array_plus_2d'
		],
		MainConfigNames::CapitalLinkOverrides => [
			'mergeStrategy' => 'array_plus'
		],
		MainConfigNames::ExtraGenderNamespaces => [
			'mergeStrategy' => 'array_plus'
		],
		MainConfigNames::GrantPermissions => [
			'mergeStrategy' => 'array_plus_2d'
		],
		MainConfigNames::GroupPermissions => [
			'mergeStrategy' => 'array_plus_2d'
		],
		MainConfigNames::Hooks => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		MainConfigNames::NamespaceContentModels => [
			'mergeStrategy' => 'array_plus'
		],
		MainConfigNames::NamespaceProtection => [
			'mergeStrategy' => 'array_plus'
		],
		MainConfigNames::NamespacesWithSubpages => [
			'mergeStrategy' => 'array_plus'
		],
		MainConfigNames::PasswordPolicy => [
			'mergeStrategy' => 'array_merge_recursive'
		],
		MainConfigNames::RateLimits => [
			'mergeStrategy' => 'array_plus_2d'
		],
		MainConfigNames::RevokePermissions => [
			'mergeStrategy' => 'array_plus_2d'
		],
		MainConfigNames::ActionFilteredLogs => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::Actions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::AddGroups => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::APIFormatModules => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::APIListModules => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::APIMetaModules => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::APIModules => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::APIPropModules => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::AvailableRights => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::CentralIdLookupProviders => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ChangeCredentialsBlacklist => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ConfigRegistry => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ContentHandlers => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::DefaultUserOptions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ExtensionEntryPointListFiles => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ExtensionFunctions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::FeedClasses => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::FileExtensions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::FilterLogTypes => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::GrantPermissionGroups => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::GroupsAddToSelf => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::GroupsRemoveFromSelf => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::HiddenPrefs => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ImplicitGroups => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::JobClasses => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogActions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogActionsHandlers => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogHeaders => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogNames => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogRestrictions => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::LogTypes => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::MediaHandlers => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::RawHtmlMessages => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ReauthenticateTime => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::RecentChangesFlags => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::RemoveCredentialsBlacklist => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::RemoveGroups => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ResourceLoaderSources => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::SessionProviders => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::SpecialPages => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ServiceWiringFiles => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ContentNamespaces => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::NonincludableNamespaces => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ValidSkinNames => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ExtensionMessagesFiles => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::MessagesDirs => [
			'mergeStrategy' => 'array_merge'
		],
		MainConfigNames::ParserTestFiles => [
			'mergeStrategy' => 'array_merge'
		],
	]
];
