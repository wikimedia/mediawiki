<?php
/**
 * Definition of core ResourceLoader modules.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

return array(

	/**
	 * Special modules who have their own classes
	 */

	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => array( 'class' => 'ResourceLoaderSiteModule' ),
	'noscript' => array( 'class' => 'ResourceLoaderNoscriptModule' ),
	'startup' => array( 'class' => 'ResourceLoaderStartUpModule' ),
	'filepage' => array( 'class' => 'ResourceLoaderFilePageModule' ),
	'user.groups' => array( 'class' => 'ResourceLoaderUserGroupsModule' ),

	// Scripts managed by the current user (stored in their user space)
	'user' => array( 'class' => 'ResourceLoaderUserModule' ),

	// Scripts generated based on the current user's preferences
	'user.cssprefs' => array( 'class' => 'ResourceLoaderUserCSSPrefsModule' ),

	// Populate mediawiki.user placeholders with information about the current user
	'user.options' => array( 'class' => 'ResourceLoaderUserOptionsModule' ),
	'user.tokens' => array( 'class' => 'ResourceLoaderUserTokensModule' ),

	// Scripts for the dynamic language specific data, like grammar forms.
	'mediawiki.language.data' => array( 'class' => 'ResourceLoaderLanguageDataModule' ),

	/**
	 * Skins
	 * Be careful not to add 'scripts' to these modules,
	 * since they are loaded with OutputPage::addModuleStyles so that the skin styles
	 * apply without javascript.
	 * If a skin needs custom js in the interface, register a separate module
	 * and add it to the load queue with OutputPage::addModules.
	 *
	 * See Vector for an example.
	 */
	'skins.cologneblue' => array(
		'styles' => array(
			'cologneblue/screen.css' => array( 'media' => 'screen' ),
			'cologneblue/print.css' => array( 'media' => 'print' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.modern' => array(
		'styles' => array(
			'modern/main.css' => array( 'media' => 'screen' ),
			'modern/print.css' => array( 'media' => 'print' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.monobook' => array(
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
			'monobook/main.css' => array( 'media' => 'screen' ),
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector' => array(
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
			'vector/styles.less',
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector.beta' => array(
		// Keep in sync with skins.vector
		'styles' => array(
			'common/commonElements.css' => array( 'media' => 'screen' ),
			'common/commonContent.css' => array( 'media' => 'screen' ),
			'common/commonInterface.css' => array( 'media' => 'screen' ),
			'vector/styles-beta.less',
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector.js' => array(
		'scripts' => array(
			'vector/collapsibleTabs.js',
			'vector/vector.js',
		),
		'position' => 'top',
		'dependencies' => 'jquery.delayedBind',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'skins.vector.collapsibleNav' => array(
		'scripts' => array(
			'vector/collapsibleNav.js',
		),
		'messages' => array(
			'vector-collapsiblenav-more',
		),
		'dependencies' => array(
			'jquery.client',
			'jquery.cookie',
			'jquery.tabIndex',
		),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'position' => 'bottom',
	),

	/* jQuery */

	'jquery' => array(
		'scripts' => 'resources/jquery/jquery.js',
		'debugRaw' => false,
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* jQuery Plugins */

	'jquery.appear' => array(
		'scripts' => 'resources/jquery/jquery.appear.js',
	),
	'jquery.arrowSteps' => array(
		'scripts' => 'resources/jquery/jquery.arrowSteps.js',
		'styles' => 'resources/jquery/jquery.arrowSteps.css',
	),
	'jquery.async' => array(
		'scripts' => 'resources/jquery/jquery.async.js',
	),
	'jquery.autoEllipsis' => array(
		'scripts' => 'resources/jquery/jquery.autoEllipsis.js',
		'dependencies' => 'jquery.highlightText',
	),
	'jquery.badge' => array(
		'scripts' => 'resources/jquery/jquery.badge.js',
		'styles' => 'resources/jquery/jquery.badge.css',
		'dependencies' => 'mediawiki.language',
	),
	'jquery.byteLength' => array(
		'scripts' => 'resources/jquery/jquery.byteLength.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.byteLimit' => array(
		'scripts' => 'resources/jquery/jquery.byteLimit.js',
		'dependencies' => 'jquery.byteLength',
	),
	'jquery.checkboxShiftClick' => array(
		'scripts' => 'resources/jquery/jquery.checkboxShiftClick.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.chosen' => array(
		'scripts' => 'resources/jquery.chosen/chosen.jquery.js',
		'styles' => 'resources/jquery.chosen/chosen.css',
	),
	'jquery.client' => array(
		'scripts' => 'resources/jquery/jquery.client.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.color' => array(
		'scripts' => 'resources/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.colorUtil' => array(
		'scripts' => 'resources/jquery/jquery.colorUtil.js',
	),
	'jquery.cookie' => array(
		'scripts' => 'resources/jquery/jquery.cookie.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.delayedBind' => array(
		'scripts' => 'resources/jquery/jquery.delayedBind.js',
	),
	'jquery.expandableField' => array(
		'scripts' => 'resources/jquery/jquery.expandableField.js',
		'dependencies' => 'jquery.delayedBind',
	),
	'jquery.farbtastic' => array(
		'scripts' => 'resources/jquery/jquery.farbtastic.js',
		'styles' => 'resources/jquery/jquery.farbtastic.css',
		'dependencies' => 'jquery.colorUtil',
	),
	'jquery.footHovzer' => array(
		'scripts' => 'resources/jquery/jquery.footHovzer.js',
		'styles' => 'resources/jquery/jquery.footHovzer.css',
	),
	'jquery.form' => array(
		'scripts' => 'resources/jquery/jquery.form.js',
	),
	'jquery.getAttrs' => array(
		'scripts' => 'resources/jquery/jquery.getAttrs.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.hidpi' => array(
		'scripts' => 'resources/jquery/jquery.hidpi.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.highlightText' => array(
		'scripts' => 'resources/jquery/jquery.highlightText.js',
		'dependencies' => 'jquery.mwExtension',
	),
	'jquery.hoverIntent' => array(
		'scripts' => 'resources/jquery/jquery.hoverIntent.js',
	),
	'jquery.json' => array(
		'scripts' => 'resources/jquery/jquery.json.js',
		'targets' => array( 'mobile', 'desktop' ),
	),
	'jquery.localize' => array(
		'scripts' => 'resources/jquery/jquery.localize.js',
	),
	'jquery.makeCollapsible' => array(
		'scripts' => 'resources/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/jquery/jquery.makeCollapsible.css',
		'messages' => array( 'collapsible-expand', 'collapsible-collapse' ),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.mockjax' => array(
		'scripts' => 'resources/jquery/jquery.mockjax.js',
	),
	'jquery.mw-jump' => array(
		'scripts' => 'resources/jquery/jquery.mw-jump.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.mwExtension' => array(
		'scripts' => 'resources/jquery/jquery.mwExtension.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.placeholder' => array(
		'scripts' => 'resources/jquery/jquery.placeholder.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.qunit' => array(
		'scripts' => 'resources/jquery/jquery.qunit.js',
		'styles' => 'resources/jquery/jquery.qunit.css',
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.qunit.completenessTest' => array(
		'scripts' => 'resources/jquery/jquery.qunit.completenessTest.js',
		'dependencies' => 'jquery.qunit',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'jquery.spinner' => array(
		'scripts' => 'resources/jquery/jquery.spinner.js',
		'styles' => 'resources/jquery/jquery.spinner.css',
	),
	'jquery.jStorage' => array(
		'scripts' => 'resources/jquery/jquery.jStorage.js',
		'dependencies' => 'jquery.json',
	),
	'jquery.suggestions' => array(
		'scripts' => 'resources/jquery/jquery.suggestions.js',
		'styles' => 'resources/jquery/jquery.suggestions.css',
		'dependencies' => 'jquery.autoEllipsis',
	),
	'jquery.tabIndex' => array(
		'scripts' => 'resources/jquery/jquery.tabIndex.js',
	),
	'jquery.tablesorter' => array(
		'scripts' => 'resources/jquery/jquery.tablesorter.js',
		'styles' => 'resources/jquery/jquery.tablesorter.css',
		'messages' => array( 'sort-descending', 'sort-ascending' ),
		'dependencies' => array(
			'jquery.mwExtension',
			'mediawiki.language.months',
		),
	),
	'jquery.textSelection' => array(
		'scripts' => 'resources/jquery/jquery.textSelection.js',
		'dependencies' => 'jquery.client',
	),
	'jquery.validate' => array(
		'scripts' => 'resources/jquery/jquery.validate.js',
	),
	'jquery.xmldom' => array(
		'scripts' => 'resources/jquery/jquery.xmldom.js',
	),

	/* jQuery Tipsy */

	'jquery.tipsy' => array(
		'scripts' => 'resources/jquery.tipsy/jquery.tipsy.js',
		'styles' => 'resources/jquery.tipsy/jquery.tipsy.css',
	),

	/* jQuery UI */

	// Core
	'jquery.ui.core' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.core.js',
		'skinStyles' => array(
			'default' => array(
				'resources/jquery.ui/themes/default/jquery.ui.core.css',
				'resources/jquery.ui/themes/default/jquery.ui.theme.css',
			),
			'vector' => array(
				'resources/jquery.ui/themes/vector/jquery.ui.core.css',
				'resources/jquery.ui/themes/vector/jquery.ui.theme.css',
			),
		),
		'dependencies' => 'jquery',
		'group' => 'jquery.ui',
	),
	'jquery.ui.widget' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	),
	'jquery.ui.mouse' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
		'group' => 'jquery.ui',
	),
	'jquery.ui.position' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.position.js',
		'group' => 'jquery.ui',
	),
	// Interactions
	'jquery.ui.draggable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.droppable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => array(
			'jquery.ui.core', 'jquery.ui.mouse', 'jquery.ui.widget', 'jquery.ui.draggable',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.resizable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.resizable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.resizable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.resizable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.selectable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.selectable.js',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.selectable.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.selectable.css',
		),
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	'jquery.ui.sortable' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'group' => 'jquery.ui',
	),
	// Widgets
	'jquery.ui.accordion' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.accordion.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.accordion.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.autocomplete' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.position' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.autocomplete.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.autocomplete.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.button' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.button.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.button.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.button.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.datepicker' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.datepicker.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.datepicker.css',
		),
		'languageScripts' => array(
			'af' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'az' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-gb' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'eo' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'gl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-gl.js',
			'he' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hi.js',
			'hr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ka' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ka.js',
			'kk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-kk.js',
			'km' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-km.js',
			'ko' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lb' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lb.js',
			'lt' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'mk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-mk.js',
			'ml' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ml.js',
			'ms' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'no' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pt.js',
			'pt-br' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'rm' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-rm.js',
			'ro' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ro.js',
			'ru' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ru.js',
			'sk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sk.js',
			'sl' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sl.js',
			'sq' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sq.js',
			'sr-sr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sr-SR.js',
			'sr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sr.js',
			'sv' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-sv.js',
			'ta' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-ta.js',
			'th' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-th.js',
			'tr' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-tr.js',
			'uk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-uk.js',
			'vi' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-vi.js',
			'zh-cn' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-CN.js',
			'zh-hk' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-HK.js',
			'zh-tw' => 'resources/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.dialog' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.dialog.js',
		'dependencies' => array(
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
			'jquery.ui.draggable',
			'jquery.ui.mouse',
			'jquery.ui.position',
			'jquery.ui.resizable',
		),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.dialog.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.dialog.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.progressbar' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.progressbar.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.progressbar.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.slider' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.slider.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget', 'jquery.ui.mouse' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.slider.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.slider.css',
		),
		'group' => 'jquery.ui',
	),
	'jquery.ui.tabs' => array(
		'scripts' => 'resources/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => array( 'jquery.ui.core', 'jquery.ui.widget' ),
		'skinStyles' => array(
			'default' => 'resources/jquery.ui/themes/default/jquery.ui.tabs.css',
			'vector' => 'resources/jquery.ui/themes/vector/jquery.ui.tabs.css',
		),
		'group' => 'jquery.ui',
	),
	// Effects
	'jquery.effects.core' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.core.js',
		'dependencies' => 'jquery',
		'group' => 'jquery.ui',
	),
	'jquery.effects.blind' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.bounce' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.clip' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.drop' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.explode' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fade' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.fold' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.highlight' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.pulsate' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.scale' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.shake' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.slide' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),
	'jquery.effects.transfer' => array(
		'scripts' => 'resources/jquery.effects/jquery.effects.transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	),

	/* MediaWiki */

	'mediawiki' => array(
		'scripts' => 'resources/mediawiki/mediawiki.js',
		'debugScripts' => 'resources/mediawiki/mediawiki.log.js',
		'debugRaw' => false,
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.api' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.js',
		'dependencies' => 'mediawiki.util',
	),
	'mediawiki.api.category' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.category.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.edit' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.edit.js',
		'dependencies' => array(
			'mediawiki.api',
			'mediawiki.Title',
		),
	),
	'mediawiki.api.login' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.login.js',
		'dependencies' => array(
			'mediawiki.api',
		),
	),
	'mediawiki.api.parse' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.parse.js',
		'dependencies' => 'mediawiki.api',
	),
	'mediawiki.api.watch' => array(
		'scripts' => 'resources/mediawiki.api/mediawiki.api.watch.js',
		'dependencies' => array(
			'mediawiki.api',
			'user.tokens',
		),
	),
	'mediawiki.icon' => array(
		'styles' => 'resources/mediawiki/mediawiki.icon.css',
	),
	'mediawiki.debug' => array(
		'scripts' => 'resources/mediawiki/mediawiki.debug.js',
		'styles' => 'resources/mediawiki/mediawiki.debug.css',
		'dependencies' => 'jquery.footHovzer',
		'position' => 'bottom',
	),
	'mediawiki.debug.init' => array(
		'scripts' => 'resources/mediawiki/mediawiki.debug.init.js',
		'dependencies' => 'mediawiki.debug',
		// Uses a custom mw.config variable that is set in debughtml,
		// must be loaded on the bottom
		'position' => 'bottom',
	),
	'mediawiki.inspect' => array(
		'scripts' => 'resources/mediawiki/mediawiki.inspect.js',
		'dependencies' => array(
			'jquery.byteLength',
			'jquery.json',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.feedback' => array(
		'scripts' => 'resources/mediawiki/mediawiki.feedback.js',
		'styles' => 'resources/mediawiki/mediawiki.feedback.css',
		'dependencies' => array(
			'mediawiki.api.edit',
			'mediawiki.Title',
			'mediawiki.jqueryMsg',
			'jquery.ui.dialog',
		),
		'messages' => array(
			'feedback-bugornote',
			'feedback-subject',
			'feedback-message',
			'feedback-cancel',
			'feedback-submit',
			'feedback-adding',
			'feedback-error1',
			'feedback-error2',
			'feedback-error3',
			'feedback-thanks',
			'feedback-close',
			'feedback-bugcheck',
			'feedback-bugnew',
		),
	),
	'mediawiki.hidpi' => array(
		'scripts' => 'resources/mediawiki/mediawiki.hidpi.js',
		'dependencies' => array(
			'jquery.hidpi',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.htmlform' => array(
		'scripts' => 'resources/mediawiki/mediawiki.htmlform.js',
		'messages' => array( 'htmlform-chosen-placeholder' ),
	),
	'mediawiki.notification' => array(
		'styles' => 'resources/mediawiki/mediawiki.notification.css',
		'scripts' => 'resources/mediawiki/mediawiki.notification.js',
		'dependencies' => array(
			'mediawiki.page.startup',
		),
	),
	'mediawiki.notify' => array(
		'scripts' => 'resources/mediawiki/mediawiki.notify.js',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.searchSuggest' => array(
		'scripts' => 'resources/mediawiki/mediawiki.searchSuggest.js',
		'styles' => 'resources/mediawiki/mediawiki.searchSuggest.css',
		'messages' => array(
			'searchsuggest-search',
			'searchsuggest-containing',
		),
		'dependencies' => array(
			'jquery.autoEllipsis',
			'jquery.client',
			'jquery.placeholder',
			'jquery.suggestions',
			'mediawiki.api',
		),
	),
	'mediawiki.Title' => array(
		'scripts' => 'resources/mediawiki/mediawiki.Title.js',
		'dependencies' => array(
			'jquery.byteLength',
			'mediawiki.util',
		),
	),
	'mediawiki.Uri' => array(
		'scripts' => 'resources/mediawiki/mediawiki.Uri.js',
	),
	'mediawiki.user' => array(
		'scripts' => 'resources/mediawiki/mediawiki.user.js',
		'dependencies' => array(
			'jquery.cookie',
			'mediawiki.api',
			'user.options',
			'user.tokens',
		),
	),
	'mediawiki.util' => array(
		'scripts' => 'resources/mediawiki/mediawiki.util.js',
		'dependencies' => array(
			'jquery.client',
			'jquery.cookie',
			'jquery.mwExtension',
			'mediawiki.notify',
		),
		'messages' => array( 'showtoc', 'hidetoc' ),
		'position' => 'top', // For $wgPreloadJavaScriptMwUtil
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* MediaWiki Action */

	'mediawiki.action.edit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.js',
		'dependencies' => array(
			'mediawiki.action.edit.styles',
			'jquery.textSelection',
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.action.edit.styles' => array(
		'styles' => 'resources/mediawiki.action/mediawiki.action.edit.styles.css',
		'position' => 'top',
	),
	'mediawiki.action.edit.collapsibleFooter' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js',
		'styles' => 'resources/mediawiki.action/mediawiki.action.edit.collapsibleFooter.css',
		'dependencies' => array(
			'jquery.makeCollapsible',
			'mediawiki.icon',
		),
	),
	'mediawiki.action.edit.preview' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.preview.js',
		'dependencies' => array(
			'jquery.form',
			'jquery.spinner',
			'mediawiki.action.history.diff',
		),
	),
	'mediawiki.action.history' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.history.js',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.history.diff' => array(
		'styles' => 'resources/mediawiki.action/mediawiki.action.history.diff.css',
		'group' => 'mediawiki.action.history',
	),
	'mediawiki.action.view.dblClickEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.page.startup',
		),
	),
	'mediawiki.action.view.metadata' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => array(
			'metadata-expand',
			'metadata-collapse',
		),
	),
	'mediawiki.action.view.postEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.postEdit.js',
		'styles' => 'resources/mediawiki.action/mediawiki.action.view.postEdit.css',
		'dependencies' => array(
			'jquery.cookie',
			'mediawiki.jqueryMsg'
		),
		'messages' => array(
			'postedit-confirmation',
		),
	),
	'mediawiki.action.view.rightClickEdit' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	),
	'mediawiki.action.edit.editWarning' => array(
		'scripts' => 'resources/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'messages' => array(
			'editwarning-warning',
		),
	),
	// Alias for backwards compatibility
	'mediawiki.action.watch.ajax' => array(
		'dependencies' => 'mediawiki.page.watch.ajax'
	),

	/* MediaWiki Language */

	'mediawiki.language' => array(
		'scripts' => array(
			'resources/mediawiki.language/mediawiki.language.js',
			'resources/mediawiki.language/mediawiki.language.numbers.js'
		),
		'languageScripts' => array(
			'bs' => 'resources/mediawiki.language/languages/bs.js',
			'dsb' => 'resources/mediawiki.language/languages/dsb.js',
			'fi' => 'resources/mediawiki.language/languages/fi.js',
			'ga' => 'resources/mediawiki.language/languages/ga.js',
			'he' => 'resources/mediawiki.language/languages/he.js',
			'hsb' => 'resources/mediawiki.language/languages/hsb.js',
			'hu' => 'resources/mediawiki.language/languages/hu.js',
			'hy' => 'resources/mediawiki.language/languages/hy.js',
			'la' => 'resources/mediawiki.language/languages/la.js',
			'os' => 'resources/mediawiki.language/languages/os.js',
			'ru' => 'resources/mediawiki.language/languages/ru.js',
			'sl' => 'resources/mediawiki.language/languages/sl.js',
			'uk' => 'resources/mediawiki.language/languages/uk.js',
		),
		'dependencies' => array(
				'mediawiki.language.data',
				'mediawiki.cldr',
			),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.cldr' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.cldr.js',
		'dependencies' => array(
			'mediawiki.libs.pluralruleparser',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.libs.pluralruleparser' => array(
		'scripts' => 'resources/mediawiki.libs/CLDRPluralRuleParser.js',
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.language.init' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.language.init.js',
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.jqueryMsg' => array(
		'scripts' => 'resources/mediawiki/mediawiki.jqueryMsg.js',
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.language',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),

	'mediawiki.language.months' => array(
		'scripts' => 'resources/mediawiki.language/mediawiki.language.months.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array_merge(
			Language::$mMonthMsgs,
			Language::$mMonthGenMsgs,
			Language::$mMonthAbbrevMsgs
		)
	),

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => array(
		'scripts' => 'resources/mediawiki.libs/mediawiki.libs.jpegmeta.js',
	),

	/* MediaWiki Page */

	'mediawiki.page.gallery' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.gallery.js',
	),
	'mediawiki.page.ready' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.ready.js',
		'dependencies' => array(
			'jquery.checkboxShiftClick',
			'jquery.makeCollapsible',
			'jquery.placeholder',
			'jquery.mw-jump',
			'mediawiki.util',
		),
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.page.startup' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.startup.js',
		'dependencies' => array(
			'jquery.client',
			'mediawiki.util',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),
	'mediawiki.page.patrol.ajax' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.patrol.ajax.js',
		'dependencies' => array(
			'mediawiki.page.startup',
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.notify',
			'jquery.spinner',
			'user.tokens'
		),
		'messages' => array(
			'markedaspatrollednotify',
			'markedaspatrollederrornotify',
			'markedaspatrollederror-noautopatrol'
		),
	),
	'mediawiki.page.watch.ajax' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.watch.ajax.js',
		'dependencies' => array(
			'mediawiki.page.startup',
			'mediawiki.api.watch',
			'mediawiki.util',
			'mediawiki.notify',
			'jquery.mwExtension',
		),
		'messages' => array(
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'watcherrortext',
		),
	),
	'mediawiki.page.image.pagination' => array(
		'scripts' => 'resources/mediawiki.page/mediawiki.page.image.pagination.js',
		'dependencies' => array(
			'mediawiki.Uri',
			'mediawiki.util',
			'jquery.spinner',
		)
	),

	/* MediaWiki Special pages */

	'mediawiki.special' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.css',
	),
	'mediawiki.special.block' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.block.js',
		'dependencies' => array(
			'mediawiki.util',
		),
	),
	'mediawiki.special.changeemail' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.changeemail.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeemail.css',
		'dependencies' => array(
			'mediawiki.util',
		),
		'messages' => array(
			'email-address-validity-valid',
			'email-address-validity-invalid',
		),
	),
	'mediawiki.special.changeslist' => array(
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeslist.css',
	),
	'mediawiki.special.changeslist.enhanced' => array(
		'styles' => 'resources/mediawiki.special/mediawiki.special.changeslist.enhanced.css',
	),
	'mediawiki.special.movePage' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.movePage.js',
		'dependencies' => 'jquery.byteLimit',
	),
	'mediawiki.special.pagesWithProp' => array(
		'styles' => 'resources/mediawiki.special/mediawiki.special.pagesWithProp.css',
	),
	'mediawiki.special.preferences' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.preferences.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.preferences.css',
		'position' => 'top',
	),
	'mediawiki.special.recentchanges' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.recentchanges.js',
		'dependencies' => array( 'mediawiki.special' ),
		'position' => 'top',
	),
	'mediawiki.special.search' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.search.js',
		'styles' => 'resources/mediawiki.special/mediawiki.special.search.css',
		'messages' => array(
			'powersearch-togglelabel',
			'powersearch-toggleall',
			'powersearch-togglenone',
		),
	),
	'mediawiki.special.undelete' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.undelete.js',
	),
	'mediawiki.special.upload' => array(
		// @todo merge in remainder of mediawiki.legacy.upload
		'scripts' => 'resources/mediawiki.special/mediawiki.special.upload.js',
		'messages' => array(
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
		),
		'dependencies' => array(
			'mediawiki.libs.jpegmeta',
			'mediawiki.util',
		),
	),
	'mediawiki.special.userlogin' => array(
		'styles' => array(
			'resources/mediawiki.special/mediawiki.special.vforms.css',
			'resources/mediawiki.special/mediawiki.special.userLogin.css',
		),
		'position' => 'top',
	),
	'mediawiki.special.createaccount' => array(
		'styles' => array(
			'resources/mediawiki.special/mediawiki.special.vforms.css',
			'resources/mediawiki.special/mediawiki.special.createAccount.css',
		),
	),
	'mediawiki.special.createaccount.js' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.createAccount.js',
		'messages' => array(
			'createacct-captcha',
			'createacct-emailrequired',
			'createacct-imgcaptcha-ph'
		),
		'dependencies' => 'mediawiki.jqueryMsg',
		'position' => 'top',
	),
	'mediawiki.special.javaScriptTest' => array(
		'scripts' => 'resources/mediawiki.special/mediawiki.special.javaScriptTest.js',
		'messages' => array_merge( Skin::getSkinNameMessages(), array(
			'colon-separator',
			'javascripttest-pagetext-skins',
		) ),
		'dependencies' => array( 'jquery.qunit' ),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* MediaWiki Tests */

	'mediawiki.tests.qunit.testrunner' => array(
		'scripts' => 'tests/qunit/data/testrunner.js',
		'dependencies' => array(
			'jquery.getAttrs',
			'jquery.qunit',
			'jquery.qunit.completenessTest',
			'mediawiki.page.startup',
			'mediawiki.page.ready',
		),
		'position' => 'top',
		'targets' => array( 'desktop', 'mobile' ),
	),

	/* MediaWiki Legacy */

	'mediawiki.legacy.ajax' => array(
		'scripts' => 'common/ajax.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.util',
			'mediawiki.legacy.wikibits',
		),
		'position' => 'top', // Temporary hack for legacy support
	),
	'mediawiki.legacy.commonPrint' => array(
		'styles' => array( 'common/commonPrint.css' => array( 'media' => 'print' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.config' => array(
		// Used in the web installer. Test it after modifying this definition!
		'scripts' => 'common/config.js',
		'styles' => array( 'common/config.css' ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => 'mediawiki.legacy.wikibits',
	),
	'mediawiki.legacy.protect' => array(
		'scripts' => 'common/protect.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'jquery.byteLimit',
		),
		'position' => 'top',
	),
	'mediawiki.legacy.shared' => array(
		// Used in the web installer. Test it after modifying this definition!
		'styles' => array( 'common/shared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.oldshared' => array(
		'styles' => array( 'common/oldshared.css' => array( 'media' => 'screen' ) ),
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
	),
	'mediawiki.legacy.upload' => array(
		'scripts' => 'common/upload.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'jquery.spinner',
			'mediawiki.api',
			'mediawiki.Title',
			'mediawiki.util',
		),
	),
	'mediawiki.legacy.wikibits' => array(
		'scripts' => 'common/wikibits.js',
		'remoteBasePath' => $GLOBALS['wgStylePath'],
		'localBasePath' => $GLOBALS['wgStyleDirectory'],
		'dependencies' => array(
			'mediawiki.util',
		),
		'position' => 'top',
	),
	'mediawiki.ui' => array(
		'skinStyles' => array(
			'default' => 'resources/mediawiki.ui/mediawiki.ui.default.css',
			'vector' => 'resources/mediawiki.ui/mediawiki.ui.vector.css',
		),
		'position' => 'top',
	),
);
