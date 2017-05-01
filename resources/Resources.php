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

return [

	/**
	 * Special modules who have their own classes
	 */
	'startup' => [ 'class' => 'ResourceLoaderStartUpModule' ],

	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => [ 'class' => 'ResourceLoaderSiteModule' ],
	'site.styles' => [ 'class' => 'ResourceLoaderSiteStylesModule' ],
	'noscript' => [
		'class' => 'ResourceLoaderWikiModule',
		'styles' => [ 'MediaWiki:Noscript.css' ],
		'group' => 'noscript',
	],
	'filepage' => [
		'position' => 'top',
		'class' => 'ResourceLoaderWikiModule',
		'styles' => [ 'MediaWiki:Filepage.css' ],
	],
	'user.groups' => [
		// Merged into 'user' since MediaWiki 1.28 - kept for back-compat
		'dependencies' => 'user',
		'targets' => [ 'desktop', 'mobile' ],
	],

	// Scripts managed by the current user (stored in their user space)
	'user' => [ 'class' => 'ResourceLoaderUserModule' ],
	'user.styles' => [ 'class' => 'ResourceLoaderUserStylesModule' ],

	// Scripts generated based on the current user's preferences
	'user.cssprefs' => [ 'class' => 'ResourceLoaderUserCSSPrefsModule' ],

	// Populate mediawiki.user placeholders with information about the current user
	'user.defaults' => [ 'class' => 'ResourceLoaderUserDefaultsModule' ],
	'user.options' => [ 'class' => 'ResourceLoaderUserOptionsModule' ],
	'user.tokens' => [ 'class' => 'ResourceLoaderUserTokensModule' ],

	// Scripts for the dynamic language specific data, like grammar forms.
	'mediawiki.language.data' => [ 'class' => 'ResourceLoaderLanguageDataModule' ],

	/* MediaWiki base skinning modules */

	/**
	 * Common skin styles, grouped into three graded levels.
	 *
	 * Level 1 "elements":
	 *     The base level that only contains the most basic of common skin styles.
	 *     Only styles for single elements are included, no styling for complex structures like the
	 *     TOC is present. This level is for skins that want to implement the entire style of even
	 *     content area structures like the TOC themselves.
	 *
	 * Level 2 "content":
	 *     The most commonly used level for skins implemented from scratch. This level includes all
	 *     the single element styles from "elements" as well as styles for complex structures such
	 *     as the TOC that are output in the content area by MediaWiki rather than the skin.
	 *     Essentially this is the common level that lets skins leave the style of the content area
	 *     as it is normally styled, while leaving the rest of the skin up to the skin
	 *     implementation.
	 *
	 * Level 3 "interface":
	 *     The highest level, this stylesheet contains extra common styles for classes like
	 *     .firstHeading, #contentSub, et cetera which are not outputted by MediaWiki but are common
	 *     to skins like MonoBook, Vector, etc... Essentially this level is for styles that are
	 *     common to MonoBook clones. And since practically every skin that currently exists within
	 *     core is a MonoBook clone, all our core skins currently use this level.
	 *
	 * These modules are typically loaded by addModuleStyles(), which has absolutely no concept of
	 * dependency management. As a result they contain duplicate stylesheet references instead of
	 * setting 'dependencies' to the lower level the module is based on. For this reason avoid
	 * including more than one of them into your skin as this will result in duplicate CSS.
	 */
	'mediawiki.skinning.elements' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
		],
	],
	'mediawiki.skinning.content' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/content.css' => [ 'media' => 'screen' ],
		],
	],
	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.skinning.interface' => [
		'position' => 'top',
		'class' => 'ResourceLoaderSkinModule',
		'styles' => [
			'resources/src/mediawiki.skinning/elements.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/content.css' => [ 'media' => 'screen' ],
			'resources/src/mediawiki.skinning/interface.css' => [ 'media' => 'screen' ],
		],
	],

	'mediawiki.skinning.content.parsoid' => [
		'position' => 'top',
		// Style Parsoid HTML+RDFa output consistent with wikitext from PHP parser
		// with the interface.css styles; skinStyles should be used if your
		// skin over-rides common content styling.
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.skinning/content.parsoid.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.skinning.content.externallinks' => [
		'position' => 'bottom',
		'styles' => [
			'resources/src/mediawiki.skinning/content.externallinks.css' => [ 'media' => 'screen' ],
		],
	],

	/* jQuery */

	'jquery' => [
		'scripts' => [
			'resources/lib/jquery/jquery.js',
		],
		'raw' => true,
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* jQuery Plugins */

	'jquery.accessKeyLabel' => [
		'scripts' => 'resources/src/jquery/jquery.accessKeyLabel.js',
		'dependencies' => [
			'jquery.client',
			'mediawiki.RegExp',
		],
		'messages' => [ 'brackets', 'word-separator' ],
		'targets' => [ 'mobile', 'desktop' ],
	],
	'jquery.appear' => [
		'deprecated' => [
			'message' => 'Please use "mediawiki.viewport" instead.',
		],
		'scripts' => 'resources/lib/jquery/jquery.appear.js',
	],
	'jquery.arrowSteps' => [
		'deprecated' => true,
		'scripts' => 'resources/src/jquery/jquery.arrowSteps.js',
		'styles' => 'resources/src/jquery/jquery.arrowSteps.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.async' => [
		'scripts' => 'resources/lib/jquery/jquery.async.js',
	],
	'jquery.autoEllipsis' => [
		'scripts' => 'resources/src/jquery/jquery.autoEllipsis.js',
		'dependencies' => 'jquery.highlightText',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.badge' => [
		'scripts' => 'resources/src/jquery/jquery.badge.js',
		'styles' => 'resources/src/jquery/jquery.badge.css',
		'dependencies' => 'mediawiki.language',
	],
	'jquery.byteLength' => [
		'scripts' => 'resources/src/jquery/jquery.byteLength.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.byteLimit' => [
		'scripts' => 'resources/src/jquery/jquery.byteLimit.js',
		'dependencies' => 'jquery.byteLength',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.checkboxShiftClick' => [
		'scripts' => 'resources/src/jquery/jquery.checkboxShiftClick.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.chosen' => [
		'scripts' => 'resources/lib/jquery.chosen/chosen.jquery.js',
		'styles' => 'resources/lib/jquery.chosen/chosen.css',
	],
	'jquery.client' => [
		'scripts' => 'resources/lib/jquery.client/jquery.client.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.color' => [
		'scripts' => 'resources/src/jquery/jquery.color.js',
		'dependencies' => 'jquery.colorUtil',
	],
	'jquery.colorUtil' => [
		'scripts' => 'resources/src/jquery/jquery.colorUtil.js',
	],
	'jquery.confirmable' => [
		'scripts' => [
			'resources/src/jquery/jquery.confirmable.js',
			'resources/src/jquery/jquery.confirmable.mediawiki.js',
		],
		'messages' => [
			'confirmable-confirm',
			'confirmable-yes',
			'confirmable-no',
			'word-separator',
		],
		'styles' => 'resources/src/jquery/jquery.confirmable.css',
		'dependencies' => 'mediawiki.jqueryMsg',
	],
	'jquery.cookie' => [
		'scripts' => 'resources/lib/jquery/jquery.cookie.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.expandableField' => [
		'scripts' => 'resources/src/jquery/jquery.expandableField.js',
	],
	'jquery.farbtastic' => [
		'scripts' => 'resources/src/jquery/jquery.farbtastic.js',
		'styles' => 'resources/src/jquery/jquery.farbtastic.css',
		'dependencies' => 'jquery.colorUtil',
	],
	'jquery.footHovzer' => [
		'scripts' => 'resources/src/jquery/jquery.footHovzer.js',
		'styles' => 'resources/src/jquery/jquery.footHovzer.css',
	],
	'jquery.form' => [
		'scripts' => 'resources/lib/jquery/jquery.form.js',
	],
	'jquery.fullscreen' => [
		'scripts' => 'resources/lib/jquery/jquery.fullscreen.js',
	],
	'jquery.getAttrs' => [
		'scripts' => 'resources/src/jquery/jquery.getAttrs.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.hidpi' => [
		'scripts' => 'resources/src/jquery/jquery.hidpi.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.highlightText' => [
		'scripts' => 'resources/src/jquery/jquery.highlightText.js',
		'dependencies' => [
			'mediawiki.RegExp',
			'dom-level2-shim',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.hoverIntent' => [
		'scripts' => 'resources/lib/jquery/jquery.hoverIntent.js',
	],
	'jquery.i18n' => [
		'scripts' => [
			'resources/lib/jquery.i18n/src/jquery.i18n.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.messagestore.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.parser.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.emitter.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.emitter.bidi.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.language.js',
			'resources/lib/jquery.i18n/src/jquery.i18n.fallbacks.js',
		],
		'dependencies' => 'mediawiki.libs.pluralruleparser',
		'languageScripts' => [
			'bs' => 'resources/lib/jquery.i18n/src/languages/bs.js',
			'dsb' => 'resources/lib/jquery.i18n/src/languages/dsb.js',
			'fi' => 'resources/lib/jquery.i18n/src/languages/fi.js',
			'ga' => 'resources/lib/jquery.i18n/src/languages/ga.js',
			'he' => 'resources/lib/jquery.i18n/src/languages/he.js',
			'hsb' => 'resources/lib/jquery.i18n/src/languages/hsb.js',
			'hu' => 'resources/lib/jquery.i18n/src/languages/hu.js',
			'hy' => 'resources/lib/jquery.i18n/src/languages/hy.js',
			'la' => 'resources/lib/jquery.i18n/src/languages/la.js',
			'ml' => 'resources/lib/jquery.i18n/src/languages/ml.js',
			'os' => 'resources/lib/jquery.i18n/src/languages/os.js',
			'ru' => 'resources/lib/jquery.i18n/src/languages/ru.js',
			'sl' => 'resources/lib/jquery.i18n/src/languages/sl.js',
			'uk' => 'resources/lib/jquery.i18n/src/languages/uk.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.localize' => [
		'scripts' => 'resources/src/jquery/jquery.localize.js',
	],
	'jquery.makeCollapsible' => [
		'scripts' => 'resources/src/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/src/jquery/jquery.makeCollapsible.css',
		'messages' => [ 'collapsible-expand', 'collapsible-collapse' ],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.mockjax' => [
		'scripts' => 'resources/lib/jquery/jquery.mockjax.js',
	],
	'jquery.mw-jump' => [
		'scripts' => 'resources/src/jquery/jquery.mw-jump.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.mwExtension' => [
		'scripts' => 'resources/src/jquery/jquery.mwExtension.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.placeholder' => [
		'scripts' => 'resources/src/jquery/jquery.placeholder.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.qunit' => [
		'scripts' => 'resources/lib/qunitjs/qunit.js',
		'styles' => 'resources/lib/qunitjs/qunit.css',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.qunit.completenessTest' => [
		'scripts' => 'resources/src/jquery/jquery.qunit.completenessTest.js',
		'dependencies' => 'jquery.qunit',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.spinner' => [
		'scripts' => 'resources/src/jquery/jquery.spinner.js',
		'styles' => 'resources/src/jquery/jquery.spinner.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.jStorage' => [
		'deprecated' => [
			'message' => 'Please use "mediawiki.storage" instead.',
		],
		'scripts' => 'resources/lib/jquery/jquery.jStorage.js',
		'dependencies' => 'json',
	],
	'jquery.suggestions' => [
		'scripts' => 'resources/src/jquery/jquery.suggestions.js',
		'styles' => 'resources/src/jquery/jquery.suggestions.css',
		'dependencies' => 'jquery.highlightText',
	],
	'jquery.tabIndex' => [
		'scripts' => 'resources/src/jquery/jquery.tabIndex.js',
	],
	'jquery.tablesorter' => [
		'scripts' => 'resources/src/jquery/jquery.tablesorter.js',
		'styles' => 'resources/src/jquery/jquery.tablesorter.less',
		'messages' => [ 'sort-descending', 'sort-ascending' ],
		'dependencies' => [
			'dom-level2-shim',
			'mediawiki.RegExp',
			'mediawiki.language.months',
		],
	],
	'jquery.textSelection' => [
		'scripts' => 'resources/src/jquery/jquery.textSelection.js',
		'dependencies' => 'jquery.client',
		'targets' => [ 'mobile', 'desktop' ],
	],
	'jquery.throttle-debounce' => [
		'scripts' => 'resources/lib/jquery/jquery.ba-throttle-debounce.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'jquery.xmldom' => [
		'scripts' => 'resources/lib/jquery/jquery.xmldom.js',
	],

	/* jQuery Tipsy */

	'jquery.tipsy' => [
		'deprecated' => true,
		'scripts' => 'resources/src/jquery.tipsy/jquery.tipsy.js',
		'styles' => 'resources/src/jquery.tipsy/jquery.tipsy.css',
	],

	/* jQuery UI */

	'jquery.ui.core' => [
		'deprecated' => [
			'message' => 'Please use "mediawiki.ui.button" or "oojs-ui" instead.',
		],
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.core.js',
		'dependencies' => [
			'jquery.ui.core.styles',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.core.styles' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.core.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.theme.css',
			],
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.accordion' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.accordion.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.accordion.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.autocomplete' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.autocomplete.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
			'jquery.ui.menu',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.autocomplete.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.button' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.button.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.button.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.datepicker' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.datepicker.js',
		'dependencies' => 'jquery.ui.core',
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.datepicker.css',
		],
		'languageScripts' => [
			'af' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-af.js',
			'ar' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar.js',
			'ar-dz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ar-DZ.js',
			'az' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-az.js',
			'bg' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bg.js',
			'bs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-bs.js',
			'ca' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ca.js',
			'cs' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-cs.js',
			'da' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-da.js',
			'de-at' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de-AT.js',
			'de-ch' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de-CH.js',
			'de' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-de.js',
			'el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-el.js',
			'en-au' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-AU.js',
			'en-gb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-GB.js',
			'en-nz' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-en-NZ.js',
			'eo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eo.js',
			'es' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-es.js',
			'et' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-et.js',
			'eu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-eu.js',
			'fa' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fa.js',
			'fi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fi.js',
			'fo' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fo.js',
			'fr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr.js',
			'fr-ch' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-fr-CH.js',
			'gl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-gl.js',
			'he' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-he.js',
			'hi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hi.js',
			'hr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hr.js',
			'hu' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hu.js',
			'hy' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-hy.js',
			'id' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-id.js',
			'is' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-is.js',
			'it' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-it.js',
			'ja' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ja.js',
			'ka' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ka.js',
			'kk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-kk.js',
			'km' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-km.js',
			'ko' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ko.js',
			'lb' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lb.js',
			'lt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lt.js',
			'lv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-lv.js',
			'mk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-mk.js',
			'ml' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ml.js',
			'ms' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ms.js',
			'nl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl.js',
			'nl-be' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-nl-BE.js',
			'no' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-no.js',
			'pl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pl.js',
			'pt' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt.js',
			'pt-br' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-pt-BR.js',
			'rm' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-rm.js',
			'ro' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ro.js',
			'ru' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ru.js',
			'sk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sk.js',
			'sl' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sl.js',
			'sq' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sq.js',
			'sr-ec' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr.js',
			'sr-el' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sr-SR.js',
			'sv' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-sv.js',
			'ta' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-ta.js',
			'th' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-th.js',
			'tj' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tj.js',
			'tr' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-tr.js',
			'uk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-uk.js',
			'vi' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-vi.js',
			'zh-cn' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-CN.js',
			'zh-hk' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-HK.js',
			'zh-tw' => 'resources/lib/jquery.ui/i18n/jquery.ui.datepicker-zh-TW.js',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.dialog' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.dialog.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
			'jquery.ui.draggable',
			'jquery.ui.mouse',
			'jquery.ui.position',
			'jquery.ui.resizable',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.dialog.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.draggable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.draggable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.droppable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.droppable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
			'jquery.ui.draggable',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.menu' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.menu.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.menu.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.mouse' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.mouse.js',
		'dependencies' => 'jquery.ui.widget',
		'group' => 'jquery.ui',
	],
	'jquery.ui.position' => [
		'deprecated' => true,
		'targets' => [ 'mobile', 'desktop' ],
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.position.js',
		'group' => 'jquery.ui',
	],
	'jquery.ui.progressbar' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.progressbar.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.progressbar.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.resizable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.resizable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.resizable.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.selectable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.selectable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.selectable.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.slider' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.slider.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.mouse',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.slider.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.sortable' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.sortable.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.mouse',
			'jquery.ui.widget',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.spinner' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.spinner.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.button',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.spinner.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.tabs' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tabs.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tabs.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.tooltip' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.tooltip.js',
		'dependencies' => [
			'jquery.ui.core',
			'jquery.ui.widget',
			'jquery.ui.position',
		],
		'skinStyles' => [
			'default' => 'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tooltip.css',
		],
		'group' => 'jquery.ui',
	],
	'jquery.ui.widget' => [
		'deprecated' => true,
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.widget.js',
		'group' => 'jquery.ui',
	],
	// Effects
	'jquery.effects.core' => [
		'deprecated' => true,
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect.js',
		'group' => 'jquery.ui',
	],
	'jquery.effects.blind' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-blind.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.bounce' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-bounce.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.clip' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-clip.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.drop' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-drop.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.explode' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-explode.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.fade' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fade.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.fold' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-fold.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.highlight' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-highlight.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.pulsate' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-pulsate.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.scale' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-scale.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.shake' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-shake.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.slide' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-slide.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],
	'jquery.effects.transfer' => [
		'scripts' => 'resources/lib/jquery.ui/jquery.ui.effect-transfer.js',
		'dependencies' => 'jquery.effects.core',
		'group' => 'jquery.ui',
	],

	/* json2 */

	'json' => [
		'scripts' => 'resources/lib/json2/json2.js',
		'targets' => [ 'desktop', 'mobile' ],
		'skipFunction' => 'resources/src/json-skip.js',
	],

	/* Moment.js */

	'moment' => [
		'scripts' => [
			'resources/lib/moment/moment.js',
			'resources/src/moment-global.js',
		],
		'languageScripts' => [
			'af' => 'resources/lib/moment/locale/af.js',
			'ar' => 'resources/lib/moment/locale/ar.js',
			'ar-ma' => 'resources/lib/moment/locale/ar-ma.js',
			'ar-sa' => 'resources/lib/moment/locale/ar-sa.js',
			'az' => 'resources/lib/moment/locale/az.js',
			'be' => 'resources/lib/moment/locale/be.js',
			'bg' => 'resources/lib/moment/locale/bg.js',
			'bn' => 'resources/lib/moment/locale/bn.js',
			'bo' => 'resources/lib/moment/locale/bo.js',
			'br' => 'resources/lib/moment/locale/br.js',
			'bs' => 'resources/lib/moment/locale/bs.js',
			'ca' => 'resources/lib/moment/locale/ca.js',
			'cs' => 'resources/lib/moment/locale/cs.js',
			'cv' => 'resources/lib/moment/locale/cv.js',
			'cy' => 'resources/lib/moment/locale/cy.js',
			'da' => 'resources/lib/moment/locale/da.js',
			'de' => 'resources/lib/moment/locale/de.js',
			'de-at' => 'resources/lib/moment/locale/de-at.js',
			'el' => 'resources/lib/moment/locale/el.js',
			'en' => 'resources/src/moment-dmy.js',
			'en-au' => 'resources/lib/moment/locale/en-au.js',
			'en-ca' => 'resources/lib/moment/locale/en-ca.js',
			'en-gb' => 'resources/lib/moment/locale/en-gb.js',
			'eo' => 'resources/lib/moment/locale/eo.js',
			'es' => 'resources/lib/moment/locale/es.js',
			'et' => 'resources/lib/moment/locale/et.js',
			'eu' => 'resources/lib/moment/locale/eu.js',
			'fa' => 'resources/lib/moment/locale/fa.js',
			'fi' => 'resources/lib/moment/locale/fi.js',
			'fo' => 'resources/lib/moment/locale/fo.js',
			'fr-ca' => 'resources/lib/moment/locale/fr-ca.js',
			'fr' => 'resources/lib/moment/locale/fr.js',
			'gl' => 'resources/lib/moment/locale/gl.js',
			'he' => 'resources/lib/moment/locale/he.js',
			'hi' => 'resources/lib/moment/locale/hi.js',
			'hr' => 'resources/lib/moment/locale/hr.js',
			'hu' => 'resources/lib/moment/locale/hu.js',
			'hy-am' => 'resources/lib/moment/locale/hy-am.js',
			'id' => 'resources/lib/moment/locale/id.js',
			'is' => 'resources/lib/moment/locale/is.js',
			'it' => 'resources/lib/moment/locale/it.js',
			'ja' => 'resources/lib/moment/locale/ja.js',
			'ka' => 'resources/lib/moment/locale/ka.js',
			'ko' => 'resources/lib/moment/locale/ko.js',
			'lt' => 'resources/lib/moment/locale/lt.js',
			'lv' => 'resources/lib/moment/locale/lv.js',
			'mk' => 'resources/lib/moment/locale/mk.js',
			'ml' => 'resources/lib/moment/locale/ml.js',
			'mr' => 'resources/lib/moment/locale/mr.js',
			'ms-my' => 'resources/lib/moment/locale/ms-my.js',
			'my' => 'resources/lib/moment/locale/my.js',
			'nb' => 'resources/lib/moment/locale/nb.js',
			'ne' => 'resources/lib/moment/locale/ne.js',
			'nl' => 'resources/lib/moment/locale/nl.js',
			'nn' => 'resources/lib/moment/locale/nn.js',
			'pl' => 'resources/lib/moment/locale/pl.js',
			'pt-br' => 'resources/lib/moment/locale/pt-br.js',
			'pt' => 'resources/lib/moment/locale/pt.js',
			'ro' => 'resources/lib/moment/locale/ro.js',
			'ru' => 'resources/lib/moment/locale/ru.js',
			'sk' => 'resources/lib/moment/locale/sk.js',
			'sl' => 'resources/lib/moment/locale/sl.js',
			'sq' => 'resources/lib/moment/locale/sq.js',
			'sr-ec' => 'resources/lib/moment/locale/sr-cyrl.js',
			'sr-el' => 'resources/lib/moment/locale/sr.js',
			'sv' => 'resources/lib/moment/locale/sv.js',
			'ta' => 'resources/lib/moment/locale/ta.js',
			'th' => 'resources/lib/moment/locale/th.js',
			'tl-ph' => 'resources/lib/moment/locale/tl-ph.js',
			'tr' => 'resources/lib/moment/locale/tr.js',
			'tzm' => 'resources/lib/moment/locale/tzm.js',
			'tzm-latn' => 'resources/lib/moment/locale/tzm-latn.js',
			'uk' => 'resources/lib/moment/locale/uk.js',
			'uz' => 'resources/lib/moment/locale/uz.js',
			'vi' => 'resources/lib/moment/locale/vi.js',
			'zh-cn' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-tw' => 'resources/lib/moment/locale/zh-tw.js',
			'zh-hans' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-hant' => 'resources/lib/moment/locale/zh-tw.js',
		],
		// HACK: skinScripts come after languageScripts, and we need locale overrides to come
		// after locale definitions
		'skinScripts' => [
			'default' => [
				'resources/src/moment-locale-overrides.js',
			],
		],
		'dependencies' => [
			'mediawiki.language',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki */

	'mediawiki' => [
		'class' => 'ResourceLoaderRawFileModule',
		// Keep in sync with maintenance/jsduck/eg-iframe.html
		'scripts' => [
			'resources/src/mediawiki/mediawiki.js',
			'resources/src/mediawiki/mediawiki.requestIdleCallback.js',
			'resources/src/mediawiki/mediawiki.errorLogger.js',
		],
		'debugScripts' => 'resources/src/mediawiki/mediawiki.log.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.apihelp' => [
		'styles' => 'resources/src/mediawiki/mediawiki.apihelp.css',
		'targets' => [ 'desktop' ],
		'position' => 'top',
	],
	'mediawiki.template' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.template.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.template.mustache' => [
		'scripts' => [
			'resources/lib/mustache/mustache.js',
			'resources/src/mediawiki/mediawiki.template.mustache.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => 'mediawiki.template',
	],
	'mediawiki.template.regexp' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.template.regexp.js',
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => 'mediawiki.template',
	],
	'mediawiki.apipretty' => [
		'styles' => 'resources/src/mediawiki/mediawiki.apipretty.css',
		'targets' => [ 'desktop', 'mobile' ],
		'position' => 'top',
	],
	'mediawiki.api' => [
		'scripts' => 'resources/src/mediawiki/api.js',
		'dependencies' => [
			'mediawiki.util',
			'user.tokens',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.category' => [
		'scripts' => 'resources/src/mediawiki/api/category.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
		],
	],
	'mediawiki.api.edit' => [
		'scripts' => 'resources/src/mediawiki/api/edit.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.login' => [
		'scripts' => 'resources/src/mediawiki/api/login.js',
		'dependencies' => 'mediawiki.api',
	],
	'mediawiki.api.options' => [
		'scripts' => 'resources/src/mediawiki/api/options.js',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.parse' => [
		'scripts' => 'resources/src/mediawiki/api/parse.js',
		'dependencies' => 'mediawiki.api',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.upload' => [
		'scripts' => 'resources/src/mediawiki/api/upload.js',
		'dependencies' => [
			'dom-level2-shim',
			'mediawiki.api',
			'mediawiki.api.edit',
			'json',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.user' => [
		'scripts' => 'resources/src/mediawiki/api/user.js',
		'dependencies' => [
			'mediawiki.api',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.watch' => [
		'scripts' => 'resources/src/mediawiki/api/watch.js',
		'dependencies' => [
			'mediawiki.api',
		],
	],
	'mediawiki.api.messages' => [
		'scripts' => 'resources/src/mediawiki/api/messages.js',
		'dependencies' => [
			'mediawiki.api',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.api.rollback' => [
		'scripts' => 'resources/src/mediawiki/api/rollback.js',
		'dependencies' => [
			'mediawiki.api',
		],
	],
	'mediawiki.content.json' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki/mediawiki.content.json.less',
	],
	'mediawiki.confirmCloseWindow' => [
		'scripts' => [
			'resources/src/mediawiki/mediawiki.confirmCloseWindow.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.debug' => [
		'scripts' => [
			'resources/src/mediawiki/mediawiki.debug.js',
		],
		'styles' => [
			'resources/src/mediawiki/mediawiki.debug.less',
		],
		'dependencies' => [
			'jquery.footHovzer',
		],
		// Uses a custom mw.config variable that is set in debughtml,
		// must be loaded on the bottom
		'position' => 'bottom',
	],
	'mediawiki.diff.styles' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki/mediawiki.diff.styles.css',
			'resources/src/mediawiki/mediawiki.diff.styles.print.css' => [
				'media' => 'print'
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.feedback' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.feedback.js',
		'styles' => 'resources/src/mediawiki/mediawiki.feedback.css',
		'dependencies' => [
			'mediawiki.messagePoster',
			'mediawiki.Title',
			'oojs-ui-core',
			'oojs-ui-windows',
		],
		'messages' => [
			'feedback-adding',
			'feedback-back',
			'feedback-bugcheck',
			'feedback-dialog-intro',
			'feedback-external-bug-report-button',
			'feedback-bugnew',
			'feedback-bugornote',
			'feedback-cancel',
			'feedback-close',
			'feedback-dialog-title',
			'feedback-error1',
			'feedback-error2',
			'feedback-error3',
			'feedback-error4',
			'feedback-message',
			'feedback-subject',
			'feedback-submit',
			'feedback-terms',
			'feedback-termsofuse',
			'feedback-thanks',
			'feedback-thanks-title',
			'feedback-useragent'
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.feedlink' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki/mediawiki.feedlink.css',
	],
	'mediawiki.filewarning' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.filewarning.js',
		'styles' => 'resources/src/mediawiki/mediawiki.filewarning.less',
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.ForeignApi' => [
		'targets' => [ 'desktop', 'mobile' ],
		'class' => 'ResourceLoaderForeignApiModule',
		// Additional dependencies generated dynamically
		'dependencies' => 'mediawiki.ForeignApi.core',
	],
	'mediawiki.ForeignApi.core' => [
		'scripts' => 'resources/src/mediawiki/ForeignApi.js',
		'dependencies' => [
			'mediawiki.api',
			'oojs',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.helplink' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki/mediawiki.helplink.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.hidpi' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.hidpi.js',
		'dependencies' => 'jquery.hidpi',
		'skipFunction' => 'resources/src/mediawiki.hidpi-skip.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.hlist' => [
		'styles' => 'resources/src/mediawiki/mediawiki.hlist.css',
	],
	'mediawiki.htmlform' => [
		'scripts' => [
			'resources/src/mediawiki/htmlform/htmlform.js',
			'resources/src/mediawiki/htmlform/autocomplete.js',
			'resources/src/mediawiki/htmlform/autoinfuse.js',
			'resources/src/mediawiki/htmlform/checkmatrix.js',
			'resources/src/mediawiki/htmlform/datetime.js',
			'resources/src/mediawiki/htmlform/cloner.js',
			'resources/src/mediawiki/htmlform/hide-if.js',
			'resources/src/mediawiki/htmlform/multiselect.js',
			'resources/src/mediawiki/htmlform/selectandother.js',
			'resources/src/mediawiki/htmlform/selectorother.js',
		],
		'dependencies' => [
			'mediawiki.RegExp',
			'jquery.byteLimit',
		],
		'messages' => [
			'htmlform-chosen-placeholder',
			// @todo Load this message in content language
			'colon-separator',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.ooui' => [
		'scripts' => [
			'resources/src/mediawiki/htmlform/htmlform.Element.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.styles' => [
		'styles' => 'resources/src/mediawiki/htmlform/styles.css',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.htmlform.ooui.styles' => [
		'styles' => 'resources/src/mediawiki/htmlform/ooui.styles.css',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.icon' => [
		'styles' => 'resources/src/mediawiki/mediawiki.icon.less',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.inspect' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.inspect.js',
		'dependencies' => [
			'jquery.byteLength',
			'mediawiki.RegExp',
			'json',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.messagePoster' => [
		'scripts' => [
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.factory.js',
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.MessagePoster.js',
		],
		'dependencies' => [
			'oojs',
			'mediawiki.api',
			'mediawiki.ForeignApi',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.messagePoster.wikitext' => [
		'scripts' => [
			'resources/src/mediawiki.messagePoster/mediawiki.messagePoster.WikitextMessagePoster.js',
		],
		'dependencies' => [
			'mediawiki.api.edit',
			'mediawiki.messagePoster',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification' => [
		'styles' => [
			'resources/src/mediawiki/mediawiki.notification.common.css',
			'resources/src/mediawiki/mediawiki.notification.print.css'
				=> [ 'media' => 'print' ],
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki/mediawiki.notification.css',
		],
		'scripts' => 'resources/src/mediawiki/mediawiki.notification.js',
		'dependencies' => 'mediawiki.page.startup',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notify' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.notify.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification.convertmessagebox' => [
		'dependencies' => [
			'mediawiki.notification',
		],
		'scripts' => 'resources/src/mediawiki/mediawiki.notification.convertmessagebox.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.notification.convertmessagebox.styles' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki/mediawiki.notification.convertmessagebox.styles.less',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.RegExp' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.RegExp.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.pager.tablePager' => [
		'styles' => 'resources/src/mediawiki/mediawiki.pager.tablePager.less',
		'position' => 'top',
	],
	'mediawiki.searchSuggest' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.searchSuggest.js',
		'styles' => 'resources/src/mediawiki/mediawiki.searchSuggest.css',
		'messages' => [
			'searchsuggest-search',
			'searchsuggest-containing',
		],
		'dependencies' => [
			'jquery.client',
			'jquery.placeholder',
			'jquery.suggestions',
			'jquery.getAttrs',
			'mediawiki.api',
		],
	],
	'mediawiki.sectionAnchor' => [
		'position' => 'top',
		// Back-compat to hide it on cached pages (T18691; Ie9e334e973; 2015-03-17)
		'styles' => 'resources/src/mediawiki/mediawiki.sectionAnchor.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.storage' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.storage.js',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Title' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.Title.js',
		'dependencies' => [
			'jquery.byteLength',
			'mediawiki.util',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Upload' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.Upload.js',
		'dependencies' => [
			'dom-level2-shim',
			'mediawiki.api.upload',
		],
	],
	'mediawiki.ForeignUpload' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.ForeignUpload.js',
		'dependencies' => [
			'mediawiki.ForeignApi',
			'mediawiki.Upload',
			'oojs',
		],
		'messages' => [
			'uploaddisabledtext',
			'upload-dialog-disabled',
			'upload-foreign-cant-upload',
		]
	],
	'mediawiki.ForeignStructuredUpload.config' => [
		'class' => 'ResourceLoaderUploadDialogModule',
	],
	'mediawiki.ForeignStructuredUpload' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.ForeignStructuredUpload.js',
		'dependencies' => [
			'mediawiki.ForeignUpload',
			'mediawiki.ForeignStructuredUpload.config',
		],
		'messages' => [
			'upload-foreign-cant-load-config',
		],
	],
	'mediawiki.Upload.Dialog' => [
		'scripts' => [
			'resources/src/mediawiki/mediawiki.Upload.Dialog.js',
		],
		'dependencies' => [
			'mediawiki.Upload.BookletLayout',
		],
		'messages' => [
			'upload-dialog-title',
			'upload-dialog-button-cancel',
			'upload-dialog-button-back',
			'upload-dialog-button-done',
			'upload-dialog-button-save',
			'upload-dialog-button-upload',
		],
	],
	'mediawiki.Upload.BookletLayout' => [
		'scripts' => [
			'resources/src/mediawiki/mediawiki.Upload.BookletLayout.js',
		],
		'styles' => [
			'resources/src/mediawiki/mediawiki.Upload.BookletLayout.css',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-windows',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'moment',
			'mediawiki.Title',
			'mediawiki.user',
			'mediawiki.Upload',
			'mediawiki.jqueryMsg',
			'mediawiki.widgets.StashedFileWidget'
		],
		'messages' => [
			'upload-form-label-infoform-title',
			'upload-form-label-infoform-name',
			'upload-form-label-infoform-name-tooltip',
			'upload-form-label-infoform-description',
			'upload-form-label-infoform-description-tooltip',
			'upload-form-label-usage-title',
			'upload-form-label-usage-filename',
			'api-error-unknownerror',
			'api-error-unknown-warning',
			'api-error-autoblocked',
			'api-error-blocked',
			'api-error-badaccess-groups',
			'api-error-badtoken',
			'api-error-copyuploaddisabled',
			'api-error-duplicate',
			'api-error-duplicate-archive',
			'api-error-empty-file',
			'api-error-emptypage',
			'api-error-fetchfileerror',
			'api-error-fileexists-forbidden',
			'api-error-fileexists-shared-forbidden',
			'api-error-file-too-large',
			'api-error-filename-tooshort',
			'api-error-filetype-banned',
			'api-error-filetype-banned-type',
			'api-error-filetype-missing',
			'api-error-hookaborted',
			'api-error-http',
			'api-error-illegal-filename',
			'api-error-internal-error',
			'api-error-invalid-file-key',
			'api-error-missingparam',
			'api-error-missingresult',
			'api-error-mustbeloggedin',
			'api-error-mustbeposted',
			'api-error-noimageinfo',
			'api-error-nomodule',
			'api-error-ok-but-empty',
			'api-error-overwrite',
			'api-error-stashfailed',
			'api-error-publishfailed',
			'api-error-stasherror',
			'api-error-stashedfilenotfound',
			'api-error-stashpathinvalid',
			'api-error-stashfilestorage',
			'api-error-stashzerolength',
			'api-error-stashnotloggedin',
			'api-error-stashwrongowner',
			'api-error-stashnosuchfilekey',
			'api-error-timeout',
			'api-error-unclassified',
			'api-error-unknown-code',
			'api-error-unknown-error',
			'api-error-uploaddisabled',
			'api-error-verification-error',
			'api-error-was-deleted',
			'fileexists',
			'filepageexists',
			'filename-bad-prefix',
			'filename-thumb-name',
			'badfilename',
			'protectedpagetext',
		],
	],
	'mediawiki.ForeignStructuredUpload.BookletLayout' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.ForeignStructuredUpload.BookletLayout.js',
		'styles' => 'resources/src/mediawiki/mediawiki.ForeignStructuredUpload.BookletLayout.less',
		'dependencies' => [
			'mediawiki.ForeignStructuredUpload',
			'mediawiki.Upload.BookletLayout',
			'mediawiki.widgets.CategorySelector',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.jqueryMsg',
			'mediawiki.api.messages',
			'moment',
			'mediawiki.libs.jpegmeta',
		],
		'messages' => [
			'upload-form-label-own-work',
			'upload-form-label-infoform-categories',
			'upload-form-label-infoform-date',
			'upload-form-label-own-work-message-generic-local',
			'upload-form-label-not-own-work-message-generic-local',
			'upload-form-label-not-own-work-local-generic-local',
			'upload-form-label-own-work-message-generic-foreign',
			'upload-form-label-not-own-work-message-generic-foreign',
			'upload-form-label-not-own-work-local-generic-foreign',
		],
	],
	'mediawiki.toc' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.toc.js',
		'styles' => [
			'resources/src/mediawiki/mediawiki.toc.css'
				=> [ 'media' => 'screen' ],
			'resources/src/mediawiki/mediawiki.toc.print.css'
				=> [ 'media' => 'print' ],
		],
		'dependencies' => 'mediawiki.cookie',
		'messages' => [ 'showtoc', 'hidetoc' ],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.Uri' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.Uri.js',
		'templates' => [
			'strict.regexp' => 'resources/src/mediawiki/mediawiki.Uri.strict.regexp',
			'loose.regexp' => 'resources/src/mediawiki/mediawiki.Uri.loose.regexp',
		],
		'dependencies' => 'mediawiki.util',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.user' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.user.js',
		'dependencies' => [
			'mediawiki.cookie',
			'mediawiki.api',
			'mediawiki.api.user',
			'user.options',
			'user.tokens',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.userSuggest' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.userSuggest.js',
		'dependencies' => [
			'jquery.suggestions',
			'mediawiki.api'
		]
	],
	'mediawiki.util' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.util.js',
		'dependencies' => [
			'jquery.accessKeyLabel',
			'mediawiki.RegExp',
			'mediawiki.notify',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.viewport' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.viewport.js',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.checkboxtoggle' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.checkboxtoggle.js',
	],
	'mediawiki.checkboxtoggle.styles' => [
		'styles' => 'resources/src/mediawiki/mediawiki.checkboxtoggle.css',
	],
	'mediawiki.cookie' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.cookie.js',
		'dependencies' => 'jquery.cookie',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.toolbar' => [
		'class' => 'ResourceLoaderEditToolbarModule',
		'scripts' => 'resources/src/mediawiki.toolbar/toolbar.js',
		'styles' => 'resources/src/mediawiki.toolbar/toolbar.less',
		'dependencies' => 'jquery.textSelection',
		'position' => 'top',
	],
	'mediawiki.experiments' => [
		'scripts' => 'resources/src/mediawiki/mediawiki.experiments.js',
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki Action */

	'mediawiki.action.edit' => [
		'scripts' => [
			'resources/src/mediawiki.action/mediawiki.action.edit.js',
			'resources/src/mediawiki.action/mediawiki.action.edit.stash.js',
		],
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.css',
		'dependencies' => [
			'mediawiki.action.edit.styles',
			'jquery.textSelection',
			'jquery.byteLimit',
			'mediawiki.api',
		],
		'position' => 'top',
	],
	'mediawiki.action.edit.styles' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.styles.css',
	],
	'mediawiki.action.edit.collapsibleFooter' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.css',
		'dependencies' => [
			'jquery.makeCollapsible',
			'mediawiki.cookie',
			'mediawiki.icon',
		],
	],
	'mediawiki.action.edit.preview' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.preview.js',
		'dependencies' => [
			'jquery.form',
			'jquery.spinner',
			'jquery.textSelection',
			'mediawiki.api',
			'mediawiki.diff.styles',
			'mediawiki.util',
			'mediawiki.jqueryMsg',
		],
		'messages' => [
			// Keep the uses message keys in sync with EditPage#setHeaders
			'creating',
			'editconflict',
			'editing',
			'editingcomment',
			'editingsection',
			'pagetitle',
			'otherlanguages',
			'summary-preview',
			'subject-preview',
			'parentheses',
			'previewerrortext',
		],
	],
	'mediawiki.action.history' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.history.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.css',
	],
	'mediawiki.action.history.styles' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.styles.css',
	],
	// using this module is deprecated, for diff styles use mediawiki.diff.styles instead
	'mediawiki.action.history.diff' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki/mediawiki.diff.styles.css',
			'resources/src/mediawiki/mediawiki.diff.styles.print.css' => [
				'media' => 'print'
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.action.view.dblClickEdit' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.dblClickEdit.js',
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.page.startup',
			'user.options',
		],
	],
	'mediawiki.action.view.metadata' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.css',
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => [
			'metadata-expand',
			'metadata-collapse',
		],
	],
	'mediawiki.action.view.categoryPage.styles' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.categoryPage.less',
		'targets' => [ 'desktop', 'mobile' ]
	],
	'mediawiki.action.view.postEdit' => [
		'templates' => [
			'postEdit.html' => 'resources/src/mediawiki.action/templates/postEdit.html',
		],
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.postEdit.css',
		'dependencies' => [
			'mediawiki.cookie',
			'mediawiki.jqueryMsg'
		],
		'messages' => [
			'postedit-confirmation-created',
			'postedit-confirmation-restored',
			'postedit-confirmation-saved',
		],
	],
	'mediawiki.action.view.redirect' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.redirect.js',
		'dependencies' => 'jquery.client',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.action.view.redirectPage' => [
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.redirectPage.css',
	],
	'mediawiki.action.view.rightClickEdit' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.rightClickEdit.js',
	],
	'mediawiki.action.edit.editWarning' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'dependencies' => [
			'jquery.textSelection',
			'mediawiki.jqueryMsg',
			'mediawiki.confirmCloseWindow',
			'user.options',
		],
		'messages' => [
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing'
		],
	],
	'mediawiki.action.view.filepage' => [
		'styles' => [
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.print.css' =>
				[ 'media' => 'print' ],
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.css',
		],
		'position' => 'top',
	],

	/* MediaWiki Language */

	'mediawiki.language' => [
		'scripts' => [
			'resources/src/mediawiki.language/mediawiki.language.js',
			'resources/src/mediawiki.language/mediawiki.language.numbers.js',
			'resources/src/mediawiki.language/mediawiki.language.fallback.js',
		],
		'languageScripts' => [
			'bs' => 'resources/src/mediawiki.language/languages/bs.js',
			'dsb' => 'resources/src/mediawiki.language/languages/dsb.js',
			'fi' => 'resources/src/mediawiki.language/languages/fi.js',
			'ga' => 'resources/src/mediawiki.language/languages/ga.js',
			'he' => 'resources/src/mediawiki.language/languages/he.js',
			'hsb' => 'resources/src/mediawiki.language/languages/hsb.js',
			'hu' => 'resources/src/mediawiki.language/languages/hu.js',
			'hy' => 'resources/src/mediawiki.language/languages/hy.js',
			'la' => 'resources/src/mediawiki.language/languages/la.js',
			'os' => 'resources/src/mediawiki.language/languages/os.js',
			'ru' => 'resources/src/mediawiki.language/languages/ru.js',
			'sl' => 'resources/src/mediawiki.language/languages/sl.js',
			'uk' => 'resources/src/mediawiki.language/languages/uk.js',
		],
		'dependencies' => [
			'mediawiki.language.data',
			'mediawiki.cldr',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'messages' => [
			'and',
			'comma-separator',
			'word-separator'
		],
	],

	'mediawiki.cldr' => [
		'scripts' => 'resources/src/mediawiki.language/mediawiki.cldr.js',
		'dependencies' => [
			'mediawiki.libs.pluralruleparser',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.libs.pluralruleparser' => [
		'scripts' => 'resources/src/mediawiki.libs/CLDRPluralRuleParser.js',
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.language.init' => [
		'scripts' => 'resources/src/mediawiki.language/mediawiki.language.init.js',
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.jqueryMsg' => [
		// Add data for mediawiki.jqueryMsg, such as allowed tags
		'class' => 'ResourceLoaderJqueryMsgModule',
		'scripts' => 'resources/src/mediawiki/mediawiki.jqueryMsg.js',
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.language',
			'user.options',
			'dom-level2-shim',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.language.months' => [
		'scripts' => 'resources/src/mediawiki.language/mediawiki.language.months.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array_merge(
			Language::$mMonthMsgs,
			Language::$mMonthGenMsgs,
			Language::$mMonthAbbrevMsgs
		)
	],

	'mediawiki.language.names' => [ 'class' => 'ResourceLoaderLanguageNamesModule' ],

	'mediawiki.language.specialCharacters' => [
		'class' => 'ResourceLoaderSpecialCharacterDataModule'
	],

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => [
		'scripts' => 'resources/src/mediawiki.libs/mediawiki.libs.jpegmeta.js',
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki Page */

	'mediawiki.page.gallery' => [
		'scripts' => 'resources/src/mediawiki/page/gallery.js',
		'dependencies' => [
			'mediawiki.page.gallery.styles',
			'jquery.throttle-debounce',
		]
	],
	'mediawiki.page.gallery.styles' => [
		'styles' => [
			'resources/src/mediawiki/page/gallery.print.css' => [ 'media' => 'print' ],
			'resources/src/mediawiki/page/gallery.css',
		],
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.gallery.slideshow' => [
		'scripts' => 'resources/src/mediawiki/page/gallery-slideshow.js',
		'position' => 'top',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
			'oojs',
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui.styles.icons-media'
		],
		'messages' => [
			'gallery-slideshow-toggle'
		]
	],
	'mediawiki.page.ready' => [
		'scripts' => 'resources/src/mediawiki/page/ready.js',
		'dependencies' => [
			'jquery.accessKeyLabel',
			'jquery.checkboxShiftClick',
			'jquery.makeCollapsible',
			'jquery.placeholder',
			'jquery.mw-jump',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.startup' => [
		'scripts' => 'resources/src/mediawiki/page/startup.js',
		'dependencies' => 'mediawiki.util',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.page.patrol.ajax' => [
		'scripts' => 'resources/src/mediawiki/page/patrol.ajax.js',
		'dependencies' => [
			'mediawiki.page.startup',
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.notify',
			'jquery.spinner',
			'user.tokens'
		],
		'messages' => [
			'markedaspatrollednotify',
			'markedaspatrollederrornotify',
			'markedaspatrollederror-noautopatrol'
		],
	],
	'mediawiki.page.watch.ajax' => [
		'scripts' => 'resources/src/mediawiki/page/watch.js',
		'dependencies' => [
			'mediawiki.page.startup',
			'mediawiki.api.watch',
			'mediawiki.notify',
			'mediawiki.util',
			'jquery.accessKeyLabel',
			'mediawiki.RegExp',
		],
		'messages' => [
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'watcherrortext',
		],
	],
	'mediawiki.page.rollback' => [
		'scripts' => 'resources/src/mediawiki/page/rollback.js',
		'dependencies' => [
			'mediawiki.api.rollback',
			'mediawiki.notify',
			'jquery.spinner',
		],
		'messages' => [
			'rollbackfailed',
			'actioncomplete',
		],
	],
	'mediawiki.page.image.pagination' => [
		'scripts' => 'resources/src/mediawiki/page/image-pagination.js',
		'dependencies' => [
			'mediawiki.util',
			'jquery.spinner',
		],
	],

	/* MediaWiki Special pages */

	'mediawiki.special' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.apisandbox.styles' => [
		'targets' => [ 'desktop', 'mobile' ],
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.apisandbox.top.css',
	],
	'mediawiki.special.apisandbox' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.apisandbox.css',
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.apisandbox.js',
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'oojs-ui',
			'mediawiki.widgets.datetime',
		],
		'messages' => [
			'apisandbox-intro',
			'apisandbox-submit',
			'apisandbox-reset',
			'apisandbox-fullscreen',
			'apisandbox-fullscreen-tooltip',
			'apisandbox-unfullscreen',
			'apisandbox-unfullscreen-tooltip',
			'apisandbox-retry',
			'apisandbox-loading',
			'apisandbox-load-error',
			'apisandbox-fetch-token',
			'apisandbox-helpurls',
			'apisandbox-examples',
			'apisandbox-dynamic-parameters',
			'apisandbox-dynamic-parameters-add-label',
			'apisandbox-dynamic-parameters-add-placeholder',
			'apisandbox-dynamic-error-exists',
			'apisandbox-deprecated-parameters',
			'apisandbox-no-parameters',
			'api-help-param-limit',
			'api-help-param-limit2',
			'api-help-param-integer-min',
			'api-help-param-integer-max',
			'api-help-param-integer-minmax',
			'api-help-param-multi-separate',
			'api-help-param-multi-max',
			'apisandbox-submit-invalid-fields-title',
			'apisandbox-submit-invalid-fields-message',
			'apisandbox-results',
			'apisandbox-sending-request',
			'apisandbox-loading-results',
			'apisandbox-results-error',
			'apisandbox-request-url-label',
			'apisandbox-request-time',
			'apisandbox-results-fixtoken',
			'apisandbox-results-fixtoken-fail',
			'apisandbox-alert-page',
			'apisandbox-alert-field',
			'apisandbox-continue',
			'apisandbox-continue-clear',
			'apisandbox-continue-help',
			'blanknamespace',
		],
	],
	'mediawiki.special.block' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.block.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.block.css',
		'dependencies' => 'mediawiki.util',
	],
	'mediawiki.special.changeslist' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.css',
	],
	'mediawiki.special.changeslist.legend' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.legend.css',
	],
	'mediawiki.special.changeslist.legend.js' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.legend.js',
		'dependencies' => [
			'jquery.makeCollapsible',
			'mediawiki.cookie',
		],
	],
	'mediawiki.special.changeslist.enhanced' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.enhanced.css',
	],
	'mediawiki.special.changeslist.visitedstatus' => [
		'position' => 'top',
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.changeslist.visitedstatus.js',
	],
	'mediawiki.special.comparepages.styles' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.comparepages.styles.less',
	],
	'mediawiki.special.edittags' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.edittags.js',
		'dependencies' => [
			'jquery.chosen',
		],
		'messages' => [
			'tags-edit-chosen-placeholder',
			'tags-edit-chosen-no-results',
		],
	],
	'mediawiki.special.edittags.styles' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.edittags.css',
		'position' => 'top',
	],
	'mediawiki.special.import' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.import.js',
	],
	'mediawiki.special.movePage' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.movePage.js',
		'dependencies' => [
			'jquery.byteLimit',
			'mediawiki.widgets',
		],
	],
	'mediawiki.special.movePage.styles' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.movePage.css',
		'position' => 'top',
	],
	'mediawiki.special.pageLanguage' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.pageLanguage.js',
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.special.pagesWithProp' => [
		'position' => 'top',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.pagesWithProp.css',
	],
	'mediawiki.special.preferences' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.preferences.js',
		'position' => 'top',
		'messages' => [
			'prefs-tabs-navigation-hint',
			'prefswarning-warning',
			'saveprefs',
			'savedprefs',
		],
		'dependencies' => [
			'mediawiki.language',
			'mediawiki.confirmCloseWindow',
			'mediawiki.notification.convertmessagebox',
		],
	],
	'mediawiki.special.userrights' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.userrights.js',
		'dependencies' => [
			'mediawiki.notification.convertmessagebox',
		],
	],
	'mediawiki.special.preferences.styles' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.preferences.styles.css',
		'position' => 'top',
	],
	'mediawiki.special.recentchanges' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.recentchanges.js',
		'position' => 'top',
	],
	'mediawiki.special.search' => [
		'position' => 'top',
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.search.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.search.css',
		'dependencies' => 'mediawiki.widgets.SearchInputWidget',
		'messages' => [
			'powersearch-togglelabel',
			'powersearch-toggleall',
			'powersearch-togglenone',
		],
	],
	'mediawiki.special.search.styles' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.search.styles.css',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.special.undelete' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.undelete.js',
	],
	'mediawiki.special.upload' => [
		'templates' => [
			'thumbnail.html' => 'resources/src/mediawiki.special/templates/thumbnail.html',
		],
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.upload.js',
		'messages' => [
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
			'editwarning-warning',
			// editwarning-warning uses {{int:prefs-editing}}
			'prefs-editing',
		],
		'dependencies' => [
			'mediawiki.special.upload.styles',
			'jquery.spinner',
			'mediawiki.jqueryMsg',
			'mediawiki.api',
			'mediawiki.libs.jpegmeta',
			'mediawiki.Title',
			'mediawiki.util',
			'mediawiki.confirmCloseWindow',
			'user.options',
		],
	],
	'mediawiki.special.upload.styles' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.upload.styles.css',
	],
	'mediawiki.special.userlogin.common.styles' => [
		'styles' => [
			'resources/src/mediawiki.special/mediawiki.special.userlogin.common.css',
		],
		'position' => 'top',
	],
	'mediawiki.special.userlogin.signup.styles' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.special/mediawiki.special.userlogin.signup.css',
		],
	],
	'mediawiki.special.userlogin.login.styles' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.special/mediawiki.special.userlogin.login.css',
		],
	],
	'mediawiki.special.userlogin.signup.js' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.userlogin.signup.js',
		'messages' => [
			'createacct-error',
			'createacct-emailrequired',
			'noname',
			'userexists',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'jquery.throttle-debounce',
		],
	],
	'mediawiki.special.unwatchedPages' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.unwatchedPages.js',
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.unwatchedPages.css',
		'messages' => [
			'addedwatchtext-short',
			'removedwatchtext-short',
			'unwatch',
			'unwatching',
			'watch',
			'watcherrortext',
			'watching',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.api.watch',
			'mediawiki.notify',
			'mediawiki.Title',
			'mediawiki.util',
		],
	],
	'mediawiki.special.watchlist' => [
		'scripts' => 'resources/src/mediawiki.special/mediawiki.special.watchlist.js',
	],
	'mediawiki.special.version' => [
		'styles' => 'resources/src/mediawiki.special/mediawiki.special.version.css',
	],

	/* MediaWiki Installer */

	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.legacy.config' => [
		// These files are not actually loaded via ResourceLoader, so dependencies etc. won't work.
		'scripts' => 'mw-config/config.js',
		'styles' => 'mw-config/config.css',
	],

	/* MediaWiki Legacy */

	'mediawiki.legacy.commonPrint' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.legacy/commonPrint.css' => [ 'media' => 'print' ]
		],
	],
	'mediawiki.legacy.protect' => [
		'scripts' => 'resources/src/mediawiki.legacy/protect.js',
		'dependencies' => 'jquery.byteLimit',
		'messages' => [ 'protect-unchain-permissions' ]
	],
	// Used in the web installer. Test it after modifying this definition!
	'mediawiki.legacy.shared' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.legacy/shared.css' => [ 'media' => 'screen' ]
		],
	],
	'mediawiki.legacy.oldshared' => [
		'position' => 'top',
		'styles' => [
			'resources/src/mediawiki.legacy/oldshared.css' => [ 'media' => 'screen' ]
		],
	],
	'mediawiki.legacy.wikibits' => [
		'scripts' => 'resources/src/mediawiki.legacy/wikibits.js',
		'dependencies' => 'mediawiki.util',
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],

	/* MediaWiki UI */

	'mediawiki.ui' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/default.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.checkbox' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/checkbox.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.radio' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/radio.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for anchor styles
	'mediawiki.ui.anchor' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/anchors.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for button styles
	'mediawiki.ui.button' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/buttons.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.input' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/inputs.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.ui.icon' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/icons.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	// Lightweight module for text styles
	'mediawiki.ui.text' => [
		'position' => 'top',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/components/text.less',
			],
		],
		'targets' => [ 'desktop', 'mobile' ],
	],

	'mediawiki.widgets' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.NamespaceInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.ComplexNamespaceInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleSearchWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.ComplexTitleInputWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.TitleOptionWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.TitleWidget.less',
			],
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.widgets.styles',
			// TitleInputWidget
			'mediawiki.Title',
			'mediawiki.api',
			'jquery.byteLimit',
			// TitleOptionWidget
			'jquery.autoEllipsis',
		],
		'messages' => [
			// NamespaceInputWidget
			'blanknamespace',
			'namespacesall',
			// TitleInputWidget
			'mw-widgets-titleinput-description-new-page',
			'mw-widgets-titleinput-description-redirect',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.ComplexNamespaceInputWidget.base.css',
				'resources/src/mediawiki.widgets/mw.widgets.ComplexTitleInputWidget.base.css',
			],
		],
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.DateInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CalendarWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.CalendarWidget.less',
				'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.less',
			],
		],
		'messages' => [
			'mw-widgets-dateinput-no-date',
			'mw-widgets-dateinput-placeholder-day',
			'mw-widgets-dateinput-placeholder-month',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'moment',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.datetime' => [
		'scripts' => [
			'resources/src/mediawiki.widgets.datetime/mediawiki.widgets.datetime.js',
			'resources/src/mediawiki.widgets.datetime/CalendarWidget.js',
			'resources/src/mediawiki.widgets.datetime/DateTimeFormatter.js',
			'resources/src/mediawiki.widgets.datetime/DateTimeInputWidget.js',
			'resources/src/mediawiki.widgets.datetime/ProlepticGregorianDateTimeFormatter.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets.datetime/CalendarWidget.less',
				'resources/src/mediawiki.widgets.datetime/DateTimeInputWidget.less',
			],
		],
		'messages' => [
			'timezone-utc',
			'timezone-local',
			'january',
			'february',
			'march',
			'april',
			'may_long',
			'june',
			'july',
			'august',
			'september',
			'october',
			'november',
			'december',
			'jan',
			'feb',
			'mar',
			'apr',
			'may',
			'jun',
			'jul',
			'aug',
			'sep',
			'oct',
			'nov',
			'dec',
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
			'sun',
			'mon',
			'tue',
			'wed',
			'thu',
			'fri',
			'sat',
			'period-am',
			'period-pm',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.CategorySelector' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CategoryCapsuleItemWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.CategorySelector.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.api',
			'mediawiki.ForeignApi',
			'mediawiki.Title',
		],
		'messages' => [
			'red-link-title',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.UserInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.UserInputWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
		],
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.SearchInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.SearchInputWidget.js',
		],
		'dependencies' => [
			'mediawiki.searchSuggest',
			// FIXME: Needs TitleInputWidget only
			'mediawiki.widgets',
		],
	],
	'mediawiki.widgets.SearchInputWidget.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.SearchInputWidget.css',
			],
		],
		'position' => 'top',
		'targets' => [ 'desktop', 'mobile' ],
	],
	'mediawiki.widgets.StashedFileWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.StashedFileWidget.js',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.StashedFileWidget.less',
			],
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	/* es5-shim */
	'es5-shim' => [
		'scripts' => [
			'resources/lib/es5-shim/es5-shim.js',
			'resources/src/polyfill-object-create.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'skipFunction' => 'resources/src/es5-skip.js',
	],

	/* dom-level2-shim */
	// IE 8
	'dom-level2-shim' => [
		'scripts' => 'resources/src/polyfill-nodeTypes.js',
		'targets' => [ 'desktop', 'mobile' ],
		'skipFunction' => 'resources/src/dom-level2-skip.js',
	],

	/* OOjs */
	'oojs' => [
		'scripts' => [
			'resources/lib/oojs/oojs.jquery.js',
			'resources/src/oojs-global.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'es5-shim',
			'json',
		],
	],

	'mediawiki.router' => [
		'scripts' => [
			'resources/src/mediawiki.router/index.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'oojs-router',
		],
	],

	'oojs-router' => [
		'scripts' => [
			'resources/lib/oojs-router/oojs-router.js',
		],
		'targets' => [ 'desktop', 'mobile' ],
		'dependencies' => [
			'oojs',
		],
	],

	/* OOjs UI */
	// @see ResourcesOOUI.php
];
