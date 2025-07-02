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

use MediaWiki\Actions\WatchAction;
use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\CodexModule;
use MediaWiki\ResourceLoader\Context;
use MediaWiki\ResourceLoader\DateFormatterConfig;
use MediaWiki\ResourceLoader\FilePath;
use MediaWiki\ResourceLoader\ForeignApiModule;
use MediaWiki\ResourceLoader\LessVarFileModule;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\OOUIFileModule;
use MediaWiki\ResourceLoader\OOUIIconPackModule;
use MediaWiki\ResourceLoader\OOUIImageModule;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\ResourceLoader\SiteModule;
use MediaWiki\ResourceLoader\SiteStylesModule;
use MediaWiki\ResourceLoader\SkinModule;
use MediaWiki\ResourceLoader\UserModule;
use MediaWiki\ResourceLoader\UserOptionsModule;
use MediaWiki\ResourceLoader\UserStylesModule;
use MediaWiki\ResourceLoader\WikiModule;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\ChangesListSpecialPage;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

global $wgResourceBasePath;

return [
	// Scripts managed by the local wiki (stored in the MediaWiki namespace)
	'site' => [ 'class' => SiteModule::class ],
	'site.styles' => [ 'class' => SiteStylesModule::class ],
	'noscript' => [
		'class' => WikiModule::class,
		'styles' => [ 'MediaWiki:Noscript.css' ],
		'group' => Module::GROUP_NOSCRIPT,
	],
	'filepage' => [
		'class' => WikiModule::class,
		'styles' => [ 'MediaWiki:Filepage.css' ],
	],

	// Scripts managed by the current user (stored in their user space)
	'user' => [ 'class' => UserModule::class ],
	'user.styles' => [ 'class' => UserStylesModule::class ],

	'user.options' => [ 'class' => UserOptionsModule::class ],

	'mediawiki.skinning.interface' => [
		'class' => SkinModule::class,
		'features' => [
			'elements' => true,
			'content-media' => true,
			'interface' => true,
			'interface-message-box' => true,
			'logo' => true,
		],
	],
	'jquery.makeCollapsible.styles' => [
		'class' => LessVarFileModule::class,
		'lessMessages' => [
			'collapsible-collapse',
			'collapsible-expand',
		],
		'styles' => [
			'resources/src/jquery/jquery.makeCollapsible.styles.less',
		],
	],

	'mediawiki.skinning.content.parsoid' => [
		// Style Parsoid HTML+RDFa output consistent with wikitext from PHP parser
		// with the interface.css styles; skinStyles should be used if your
		// skin over-rides common content styling.
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.skinning/content.parsoid.less',
				'resources/src/mediawiki.skinning/content.media-common.less',
				'resources/src/mediawiki.skinning/content.media-screen.less',
			],
		],
	],
	'mediawiki.skinning.typeaheadSearch' => [
		'dependencies' => [
			'mediawiki.codex.typeaheadSearch'
		],
		'packageFiles' => [
			'resources/src/mediawiki.skinning.typeaheadSearch/index.js',
			'resources/src/mediawiki.skinning.typeaheadSearch/App.vue',
			'resources/src/mediawiki.skinning.typeaheadSearch/TypeaheadSearchWrapper.vue',
			[
				'name' => 'resources/src/mediawiki.skinning.typeaheadSearch/icons.json',
				'callback' => 'MediaWiki\\ResourceLoader\\CodexModule::getIcons',
				'callbackParam' => [
					'cdxIconArrowPrevious'
				],
			],
			'resources/src/mediawiki.skinning.typeaheadSearch/instrumentation.js',
			'resources/src/mediawiki.skinning.typeaheadSearch/fetch.js',
			'resources/src/mediawiki.skinning.typeaheadSearch/restSearchClient.js',
			'resources/src/mediawiki.skinning.typeaheadSearch/urlGenerator.js',
		],
		'messages' => [
			'search-close',
			'searchbutton',
			'searchresults',
			'search-loader',
			'searchsuggest-containing-html'
		]
	],

	/* Polyfills */
	'web2017-polyfills' => [
		'scripts' => [
			'resources/lib/intersection-observer/intersection-observer.js',
			'resources/lib/fetch-polyfill/fetch.umd.js',
			// The URL polyfill depends on the following in addition to the ES6 baseline
			// https://github.com/Financial-Times/polyfill-library/blob/v3.110.1/polyfills/URL/config.toml#L10
			// - ES6 Symbol.iterator (no fill needed, used conditionally)
			'resources/lib/url/URL.js',
			'resources/lib/url/URL-toJSON.js',
		],
		'skipFunction' => 'resources/src/skip-web2017-polyfills.js',
		'dependencies' => []
	],

	/* Base modules */
	// These modules' dependencies MUST also be included in StartUpModule::getBaseModules().
	// These modules' dependencies MUST be dependency-free (having dependencies would cause recursion).

	'jquery' => [
		'scripts' => [
			'resources/lib/jquery/jquery.js'
		],
	],
	'mediawiki.base' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.base',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.base",
		'packageFiles' => [
			'mediawiki.base.js',
			'log.js',
			'errorLogger.js',
			[ 'name' => 'config.json', 'callback' => [ ResourceLoader::class, 'getSiteConfigSettings' ] ],
			[
				'name' => 'user.json',
				'callback' => static function ( Context $context ) {
					$services = MediaWikiServices::getInstance();

					return ResourceLoader::getUserDefaults(
						$context,
						$services->getHookContainer(),
						$services->getUserOptionsLookup()
					);
				}
			],
		],
		'dependencies' => 'jquery',
	],

	/* jQuery Plugins */

	'jquery.chosen' => [
		'scripts' => 'resources/lib/jquery.chosen/chosen.jquery.js',
		'styles' => 'resources/lib/jquery.chosen/chosen.css',
	],
	'jquery.client' => [
		'scripts' => 'resources/lib/jquery.client/jquery.client.js',
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
		'styles' => 'resources/src/jquery/jquery.confirmable.less',
		'dependencies' => 'mediawiki.jqueryMsg',
	],
	'jquery.highlightText' => [
		'scripts' => 'resources/src/jquery/jquery.highlightText.js',
		'dependencies' => [
			'mediawiki.util',
		],
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
	],
	'jquery.lengthLimit' => [
		'scripts' => 'resources/src/jquery.lengthLimit.js',
		'dependencies' => 'mediawiki.String',
	],
	'jquery.makeCollapsible' => [
		'dependencies' => [
			'jquery.makeCollapsible.styles',
			'mediawiki.util',
		],
		'scripts' => 'resources/src/jquery/jquery.makeCollapsible.js',
		'styles' => 'resources/src/jquery/jquery.makeCollapsible.less',
		'messages' => [ 'collapsible-expand', 'collapsible-collapse' ],
	],
	'jquery.spinner' => [
		'scripts' => 'resources/src/jquery.spinner/spinner.js',
		'dependencies' => [ 'jquery.spinner.styles' ],
	],
	'jquery.spinner.styles' => [
		'styles' => 'resources/src/jquery.spinner/spinner.less',
	],
	'jquery.suggestions' => [
		'scripts' => 'resources/src/jquery/jquery.suggestions.js',
		'styles' => 'resources/src/jquery/jquery.suggestions.less',
		'dependencies' => 'jquery.highlightText',
	],
	'jquery.tablesorter' => [
		'scripts' => 'resources/src/jquery.tablesorter/jquery.tablesorter.js',
		'messages' => [ 'sort-descending', 'sort-ascending', 'sort-initial', 'sort-rowspan-error' ],
		'dependencies' => [
			'jquery.tablesorter.styles',
			'mediawiki.util',
			'mediawiki.language.months',
		],
	],
	'jquery.tablesorter.styles' => [
		'styles' => 'resources/src/jquery.tablesorter.styles/jquery.tablesorter.styles.less',
	],
	'jquery.textSelection' => [
		'scripts' => 'resources/src/jquery/jquery.textSelection.js',
		'dependencies' => [
			'jquery.client',
		]
	],

	/* jQuery UI */

	'jquery.ui' => [
		'deprecated' => 'Please use Codex instead.',
		'scripts' => [
			'resources/lib/jquery.ui/jquery.ui.core.js',
			'resources/lib/jquery.ui/jquery.ui.widget.js',
			'resources/lib/jquery.ui/jquery.ui.mouse.js',
			'resources/lib/jquery.ui/jquery.ui.draggable.js',
			'resources/lib/jquery.ui/jquery.ui.droppable.js',
			'resources/lib/jquery.ui/jquery.ui.resizable.js',
			'resources/lib/jquery.ui/jquery.ui.selectable.js',
			'resources/lib/jquery.ui/jquery.ui.sortable.js',
			'resources/lib/jquery.ui/jquery.ui.effect.js',
			'resources/lib/jquery.ui/jquery.ui.accordion.js',
			'resources/lib/jquery.ui/jquery.ui.autocomplete.js',
			'resources/lib/jquery.ui/jquery.ui.button.js',
			'resources/lib/jquery.ui/jquery.ui.datepicker.js',
			'resources/lib/jquery.ui/jquery.ui.dialog.js',
			'resources/lib/jquery.ui/jquery.ui.effect-blind.js',
			'resources/lib/jquery.ui/jquery.ui.effect-clip.js',
			'resources/lib/jquery.ui/jquery.ui.effect-drop.js',
			'resources/lib/jquery.ui/jquery.ui.effect-highlight.js',
			'resources/lib/jquery.ui/jquery.ui.effect-scale.js',
			'resources/lib/jquery.ui/jquery.ui.effect-shake.js',
			'resources/lib/jquery.ui/jquery.ui.menu.js',
			'resources/lib/jquery.ui/jquery.ui.position.js',
			'resources/lib/jquery.ui/jquery.ui.progressbar.js',
			'resources/lib/jquery.ui/jquery.ui.slider.js',
			'resources/lib/jquery.ui/jquery.ui.tabs.js',
			'resources/lib/jquery.ui/jquery.ui.tooltip.js',
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
		'skinStyles' => [
			'default' => [
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.core.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.accordion.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.autocomplete.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.button.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.datepicker.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.dialog.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.menu.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.progressbar.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.resizable.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.selectable.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.slider.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tabs.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.tooltip.css',
				'resources/lib/jquery.ui/themes/smoothness/jquery.ui.theme.css',
			],
		],
	],

	/* Moment.js */

	'moment' => [
		'deprecated' => '[1.44] Use mediawiki.DateFormatter or native Intl function instead.'
			. ' See https://phabricator.wikimedia.org/T146798',
		'scripts' => [
			'resources/lib/moment/moment.js',
			'resources/src/moment/moment-module.js',
		],
		'languageScripts' => [
			'aeb-arab' => 'resources/lib/moment/locale/ar-tn.js',
			'af' => 'resources/lib/moment/locale/af.js',
			'ar' => 'resources/lib/moment/locale/ar.js',
			'ar-ma' => 'resources/lib/moment/locale/ar-ma.js',
			'ar-ps' => 'resources/lib/moment/locale/ar-ps.js',
			'ar-sa' => 'resources/lib/moment/locale/ar-sa.js',
			'az' => 'resources/lib/moment/locale/az.js',
			'be' => 'resources/lib/moment/locale/be.js',
			'bg' => 'resources/lib/moment/locale/bg.js',
			'bm' => 'resources/lib/moment/locale/bm.js',
			'bn' => 'resources/lib/moment/locale/bn.js',
			'bn-bd' => 'resources/lib/moment/locale/bn-bd.js',
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
			'de-ch' => 'resources/lib/moment/locale/de-ch.js',
			'dv' => 'resources/lib/moment/locale/dv.js',
			'el' => 'resources/lib/moment/locale/el.js',
			'en' => 'resources/src/moment/moment-dmy.js',
			'en-au' => 'resources/lib/moment/locale/en-au.js',
			'en-ca' => 'resources/lib/moment/locale/en-ca.js',
			'en-gb' => 'resources/lib/moment/locale/en-gb.js',
			'eo' => 'resources/lib/moment/locale/eo.js',
			'es' => 'resources/lib/moment/locale/es.js',
			'es-mx' => 'resources/lib/moment/locale/es-mx.js',
			'et' => 'resources/lib/moment/locale/et.js',
			'eu' => 'resources/lib/moment/locale/eu.js',
			'fa' => 'resources/lib/moment/locale/fa.js',
			'fi' => 'resources/lib/moment/locale/fi.js',
			'fo' => 'resources/lib/moment/locale/fo.js',
			'fr' => 'resources/lib/moment/locale/fr.js',
			'fr-ca' => 'resources/lib/moment/locale/fr-ca.js',
			'fy' => 'resources/lib/moment/locale/fy.js',
			'gd' => 'resources/lib/moment/locale/gd.js',
			'gl' => 'resources/lib/moment/locale/gl.js',
			'gom' => 'resources/lib/moment/locale/gom-latn.js',
			'gom-deva' => 'resources/lib/moment/locale/gom-deva.js',
			'gom-latn' => 'resources/lib/moment/locale/gom-latn.js',
			'gu' => 'resources/lib/moment/locale/gu.js',
			'he' => 'resources/lib/moment/locale/he.js',
			'hi' => 'resources/lib/moment/locale/hi.js',
			'hr' => 'resources/lib/moment/locale/hr.js',
			'hu' => 'resources/lib/moment/locale/hu.js',
			'hy-am' => 'resources/lib/moment/locale/hy-am.js',
			'id' => 'resources/lib/moment/locale/id.js',
			'is' => 'resources/lib/moment/locale/is.js',
			'it' => 'resources/lib/moment/locale/it.js',
			'ja' => 'resources/lib/moment/locale/ja.js',
			'jv' => 'resources/lib/moment/locale/jv.js',
			'ka' => 'resources/lib/moment/locale/ka.js',
			'kk-cyrl' => 'resources/lib/moment/locale/kk.js',
			'kn' => 'resources/lib/moment/locale/kn.js',
			'ko' => 'resources/lib/moment/locale/ko.js',
			'ku' => 'resources/lib/moment/locale/ku-kmr.js',
			'ky' => 'resources/lib/moment/locale/ky.js',
			'lo' => 'resources/lib/moment/locale/lo.js',
			'lt' => 'resources/lib/moment/locale/lt.js',
			'lv' => 'resources/lib/moment/locale/lv.js',
			'mi' => 'resources/lib/moment/locale/mi.js',
			'mk' => 'resources/lib/moment/locale/mk.js',
			'ml' => 'resources/lib/moment/locale/ml.js',
			'mr' => 'resources/lib/moment/locale/mr.js',
			'ms-my' => 'resources/lib/moment/locale/ms-my.js',
			'ms' => 'resources/lib/moment/locale/ms.js',
			'my' => 'resources/lib/moment/locale/my.js',
			'nb' => 'resources/lib/moment/locale/nb.js',
			'ne' => 'resources/lib/moment/locale/ne.js',
			'nl' => 'resources/lib/moment/locale/nl.js',
			'nn' => 'resources/lib/moment/locale/nn.js',
			'pa' => 'resources/lib/moment/locale/pa-in.js',
			'pl' => 'resources/lib/moment/locale/pl.js',
			'pt' => 'resources/lib/moment/locale/pt.js',
			'pt-br' => 'resources/lib/moment/locale/pt-br.js',
			'ro' => 'resources/lib/moment/locale/ro.js',
			'ru' => 'resources/lib/moment/locale/ru.js',
			'sd' => 'resources/lib/moment/locale/sd.js',
			'se' => 'resources/lib/moment/locale/se.js',
			'si' => 'resources/lib/moment/locale/si.js',
			'sk' => 'resources/lib/moment/locale/sk.js',
			'sl' => 'resources/lib/moment/locale/sl.js',
			'sq' => 'resources/lib/moment/locale/sq.js',
			'sr-ec' => 'resources/lib/moment/locale/sr-cyrl.js',
			'sr-el' => 'resources/lib/moment/locale/sr.js',
			'ss' => 'resources/lib/moment/locale/ss.js',
			'sv' => 'resources/lib/moment/locale/sv.js',
			'sw' => 'resources/lib/moment/locale/sw.js',
			'ta' => 'resources/lib/moment/locale/ta.js',
			'te' => 'resources/lib/moment/locale/te.js',
			'tet' => 'resources/lib/moment/locale/tet.js',
			'th' => 'resources/lib/moment/locale/th.js',
			'tk' => 'resources/lib/moment/locale/tk.js',
			'tl' => 'resources/lib/moment/locale/tl-ph.js',
			'tr' => 'resources/lib/moment/locale/tr.js',
			'tzm' => 'resources/lib/moment/locale/tzm.js',
			'tzm-latn' => 'resources/lib/moment/locale/tzm-latn.js',
			'uk' => 'resources/lib/moment/locale/uk.js',
			'ur' => 'resources/lib/moment/locale/ur.js',
			'uz' => 'resources/lib/moment/locale/uz-latn.js', # https://phabricator.wikimedia.org/T308123
			'uz-latn' => 'resources/lib/moment/locale/uz-latn.js',
			'vi' => 'resources/lib/moment/locale/vi.js',
			'yo' => 'resources/lib/moment/locale/yo.js',
			'zh-hans' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-hant' => 'resources/lib/moment/locale/zh-tw.js',
			'zh-cn' => 'resources/lib/moment/locale/zh-cn.js',
			'zh-hk' => 'resources/lib/moment/locale/zh-hk.js',
			'zh-mo' => 'resources/lib/moment/locale/zh-mo.js',
			'zh-tw' => 'resources/lib/moment/locale/zh-tw.js',
		],
		// HACK: skinScripts come after languageScripts, and we need locale overrides to come
		// after locale definitions
		'skinScripts' => [
			'default' => [
				'resources/src/moment/moment-locale-overrides.js',
			],
		],
		'dependencies' => [
			'mediawiki.language',
			'mediawiki.util',
		],
	],

	/* Vue */

	'vue' => [
		'packageFiles' => [
			'resources/src/vue/index.js',
			'resources/src/vue/errorLogger.js',
			'resources/src/vue/i18n.js',
			[
				'name' => 'resources/lib/vue/vue.js',
				'callback' => static function ( Context $context, Config $config ) {
					// Use the development version if development mode is enabled, or if we're in debug mode
					$file = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug() ?
						'resources/lib/vue/vue.global.js' :
						'resources/lib/vue/vue.global.prod.js';
					// The file shipped by Vue does var Vue = ...;, but doesn't export it
					// Add module.exports = Vue; programmatically
					return file_get_contents( MW_INSTALL_PATH . "/$file" ) .
						';module.exports=Vue;';
				},
				'versionCallback' => static function ( Context $context, Config $config ) {
					$file = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug() ?
						'resources/lib/vue/vue.global.js' :
						'resources/lib/vue/vue.global.prod.js';
					return new FilePath( $file );
				}
			],

		],
		'dependencies' => [
			'mediawiki.page.ready'
		]
	],

	'vuex' => [
		'packageFiles' => [
			[
				'name' => 'resources/lib/vuex/vuex.js',
				'callback' => static function ( Context $context, Config $config ) {
					// Use the development version if development mode is enabled, or if we're in debug mode
					$file = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug() ?
						'resources/lib/vuex/vuex.global.js' :
						'resources/lib/vuex/vuex.global.prod.js';
					// The file shipped by Vuex does var Vuex = ...;, but doesn't export it
					// Add module.exports = Vuex; programmatically, and import Vue
					return "var Vue=require('vue');" .
						file_get_contents( MW_INSTALL_PATH . "/$file" ) .
						';module.exports=Vuex;';
				},
				'versionCallback' => static function ( Context $context, Config $config ) {
					$file = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug() ?
						'resources/lib/vuex/vuex.global.js' :
						'resources/lib/vuex/vuex.global.prod.js';
					return new FilePath( $file );
				}
			],
		],
		'dependencies' => [
			'vue',
		],
		'deprecated' => '[1.42] Use Pinia instead. See migration guidelines: https://w.wiki/7pLU'
	],

	'pinia' => [
		'packageFiles' => [
			[
				'name' => 'resources/lib/pinia/pinia.js',
				'callback' => static function ( Context $context, Config $config ) {
					// Use the development version if development mode is enabled, or if we're in debug mode
					$developmentMode = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug();

					$file = $developmentMode ?
						'resources/lib/pinia/pinia.iife.js' :
						'resources/lib/pinia/pinia.iife.prod.js';

					// The file shipped by Pinia does var Pinia = ...;, but doesn't export it
					// Add module.exports = Pinia; programmatically, and inject vue-demi.
					return "var VueDemi=require('./vue-demi.js');" .
						file_get_contents( MW_INSTALL_PATH . "/$file" ) .
						';module.exports=Pinia;';
				},
				'versionCallback' => static function ( Context $context, Config $config ) {
					$file = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug() ?
						'resources/lib/pinia/pinia.iife.js' :
						'resources/lib/pinia/pinia.iife.prod.js';
					return new FilePath( $file );
				}
			],
			[
				'name' => 'resources/lib/pinia/vue-demi.js',
				'callback' => static function ( Context $context, Config $config ) {
					$developmentMode = $config->get( MainConfigNames::VueDevelopmentMode ) || $context->getDebug();

					// In non-development mode, Pinia doesn't need vue-demi, so don't load it.
					// Instead, just wrap Vue.
					if ( !$developmentMode ) {
						return "module.exports=require('vue');";
					}

					return new FilePath( 'resources/lib/vue-demi/index.cjs' );
				}
			]
		],
		'dependencies' => [
			'vue'
		],
	],

	'@wikimedia/codex' => [
		'class' => CodexModule::class,
		'codexFullLibrary' => true,
		'codexScriptOnly' => true,
		'dependencies' => [
			'vue',
			'codex-styles',
		],
	],

	'codex-styles' => [
		'class' => CodexModule::class,
		'codexFullLibrary' => true,
		'codexStyleOnly' => true,
	],

	'mediawiki.codex.messagebox.styles' => [
		'class' => CodexModule::class,
		'codexComponents' => [
			'CdxMessage',
		],
		'codexStyleOnly' => true
	],

	'mediawiki.codex.typeaheadSearch' => [
		'class' => 'MediaWiki\\ResourceLoader\\CodexModule',
		'codexComponents' => [
			"CdxIcon",
			"CdxButton",
			"CdxDialog",
			'CdxTypeaheadSearch'
		]
	],

	/* MediaWiki */
	'mediawiki.template' => [
		'scripts' => 'resources/src/mediawiki.template.js',
	],
	'mediawiki.template.mustache' => [
		'scripts' => [
			'resources/lib/mustache/mustache.js',
			'resources/src/mediawiki.template.mustache.js',
		],
		'dependencies' => 'mediawiki.template',
	],
	'mediawiki.apipretty' => [
		'styles' => [
			'resources/src/mediawiki.apipretty/apipretty.css',
			'resources/src/mediawiki.apipretty/apihelp.css',
		],
	],
	'mediawiki.api' => [
		'scripts' => [
			'resources/src/mediawiki.api/index.js',
			'resources/src/mediawiki.api/AbortablePromise.js',
			'resources/src/mediawiki.api/AbortController.js',
			'resources/src/mediawiki.api/rest.js',
			'resources/src/mediawiki.api/category.js',
			'resources/src/mediawiki.api/edit.js',
			'resources/src/mediawiki.api/login.js',
			'resources/src/mediawiki.api/messages.js',
			'resources/src/mediawiki.api/options.js',
			'resources/src/mediawiki.api/parse.js',
			'resources/src/mediawiki.api/rollback.js',
			'resources/src/mediawiki.api/upload.js',
			'resources/src/mediawiki.api/user.js',
			'resources/src/mediawiki.api/watch.js',
		],
		'dependencies' => [
			'mediawiki.Title',
			'mediawiki.util',
			'mediawiki.jqueryMsg',
			'user.options',
		],
		'messages' => [
			'api-clientside-error-noconnect',
			'api-clientside-error-http',
			'api-clientside-error-timeout',
			'api-clientside-error-aborted',
			'api-clientside-error-invalidresponse',
		],
	],
	'mediawiki.content.json' => [
		'styles' => 'resources/src/mediawiki.content.json.less',
	],
	'mediawiki.confirmCloseWindow' => [
		'scripts' => [
			'resources/src/mediawiki.confirmCloseWindow.js',
		],
		'messages' => [
			'confirmleave-warning',
		],
	],
	'mediawiki.DateFormatter' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.DateFormatter',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.DateFormatter",
		'packageFiles' => [
			'DateFormatter.js',
			[
				'name' => 'config.json',
				'callback' => [ DateFormatterConfig::class, 'getData' ]
			]
		],
		'dependencies' => [
			'user.options',
		]
	],
	'mediawiki.debug' => [
		'scripts' => [
			'resources/src/mediawiki.debug/jquery.footHovzer.js',
			'resources/src/mediawiki.debug/debug.js',
		],
		'styles' => [
			'resources/src/mediawiki.debug/jquery.footHovzer.css',
			'resources/src/mediawiki.debug/debug.less',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.diff' => [
		'packageFiles' => [
			'resources/src/mediawiki.diff/diff.js',
			'resources/src/mediawiki.diff/inlineFormatToggle.js',
		],
		'styles' => [
			'resources/src/mediawiki.diff/styles.less'
		],
		'dependencies' => [
			'mediawiki.api',
		],
		'messages' => [
			'diff-inline-tooltip-ins',
			'diff-inline-tooltip-del',
			'diff-inline-format-label',
			'diff-inline-switch-desc'
		]
	],
	'mediawiki.diff.styles' => [
		'class' => LessVarFileModule::class,
		'lessMessages' => [
			'diff-line-deleted',
			'diff-newline'
		],
		'styles' => [
			'resources/src/mediawiki.diff.styles/diff.less',
			'resources/src/mediawiki.diff.styles/print.css' => [
				'media' => 'print'
			],
		],
	],
	'mediawiki.feedback' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.feedback',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.feedback",
		'packageFiles' => [
			'feedback.js',
			'FeedbackDialog.js',
		],
		'styles' => 'feedback.less',
		'dependencies' => [
			'mediawiki.jqueryMsg',
			'mediawiki.messagePoster',
			'mediawiki.Title',
			'oojs-ui-core',
			'oojs-ui-windows',
		],
		'messages' => [
			'feedback-dialog-intro',
			'feedback-external-bug-report-button',
			'feedback-cancel',
			'feedback-close',
			'feedback-dialog-title',
			'feedback-error1',
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
	],
	'mediawiki.feedlink' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.feedlink/feedlink.css',
			],
			'vector-2022' => [],
		],
	],
	'mediawiki.filewarning' => [
		'scripts' => 'resources/src/mediawiki.filewarning/filewarning.js',
		'styles' => 'resources/src/mediawiki.filewarning/filewarning.less',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-alerts',
		],
	],
	'mediawiki.ForeignApi' => [
		'class' => ForeignApiModule::class,
		// Additional dependencies generated dynamically
		'dependencies' => 'mediawiki.ForeignApi.core',
	],
	'mediawiki.ForeignApi.core' => [
		'packageFiles' => [
			'resources/src/mediawiki.ForeignApi/index.js',
			'resources/src/mediawiki.ForeignApi/mediawiki.ForeignApi.core.js',
			'resources/src/mediawiki.ForeignApi/mediawiki.ForeignRest.core.js'
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs',
			'web2017-polyfills',
		],
	],
	'mediawiki.helplink' => [
		'styles' => [
			'resources/src/mediawiki.helplink/helplink.less',
		],
	],
	'mediawiki.hlist' => [
		'class' => LessVarFileModule::class,
		'lessMessages' => [
			'colon-separator',
			'parentheses-start',
			'parentheses-end',
		],
		'styles' => [
			'resources/src/mediawiki.hlist/hlist.less',
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.hlist/default.less',
		],
	],
	'mediawiki.htmlform' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.htmlform',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.htmlform",
		'packageFiles' => [
			'htmlform.js',
			'autocomplete.js',
			'autoinfuse.js',
			'cloner.js',
			'cond-state.js',
			'selectandother.js',
			'selectorother.js',
			'timezone.js',
			[
				'name' => 'contentMessages.json',
				'callback' => static function ( Context $context ) {
					return [
						'colonSeparator' => $context->msg( 'colon-separator' )->inContentLanguage()->text(),
					];
				}
			],
		],
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.widgets.visibleLengthLimit',
		]
	],
	'mediawiki.htmlform.ooui' => [
		'scripts' => [
			'resources/src/mediawiki.htmlform.ooui/Element.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.htmlform.styles' => [
		'styles' => 'resources/src/mediawiki.htmlform.styles/styles.less',
	],
	'mediawiki.htmlform.codex.styles' => [
		'class' => CodexModule::class,
		'styles' => [
			'resources/src/mediawiki.htmlform.codex.styles.less'
		],
		'codexComponents' => [
			'CdxMessage',
			'CdxField',
			'CdxLabel',
			'CdxButton',
			'CdxCheckbox',
			'CdxRadio',
			'CdxSelect',
			'CdxTextArea',
			'CdxTextInput'
		],
		'codexStyleOnly' => true
	],
	'mediawiki.htmlform.ooui.styles' => [
		'styles' => [
			'resources/src/mediawiki.collapsiblefieldsetlayout.styles.less',
			'resources/src/mediawiki.htmlform.ooui.styles.less'
		],
	],
	'mediawiki.inspect' => [
		'scripts' => 'resources/src/mediawiki.inspect.js',
		'dependencies' => [
			'mediawiki.String',
			'mediawiki.util',
		],
	],
	'mediawiki.notification' => [
		'styles' => [
			'resources/src/mediawiki.notification/common.css',
			'resources/src/mediawiki.notification/print.css'
				=> [ 'media' => 'print' ],
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.notification/default.less',
		],
		'scripts' => 'resources/src/mediawiki.notification/notification.js',
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.visibleTimeout',
		],
	],
	'mediawiki.notification.convertmessagebox' => [
		'scripts' => 'resources/src/mediawiki.notification.convertmessagebox.js',
		'dependencies' => [
			'mediawiki.notification',
		],
	],
	'mediawiki.notification.convertmessagebox.styles' => [
		'styles' => [
			'resources/src/mediawiki.notification.convertmessagebox.styles.less',
		],
	],
	'mediawiki.String' => [
		'scripts' => 'resources/src/mediawiki.String.js',
	],
	'mediawiki.pager.styles' => [
		'styles' => [
			'resources/src/mediawiki.pager.styles/TablePager.less',
			'resources/src/mediawiki.pager.styles/DataTable.less',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.pager.styles/IndexPager.less',
			]
		],
	],
	'mediawiki.pager.codex' => [
		'scripts' => 'resources/src/mediawiki.pager.codex/codexTablePager.js',
	],
	'mediawiki.pager.codex.styles' => [
		'class' => CodexModule::class,
		'codexComponents' => [ 'CdxTable' ],
		'codexStyleOnly' => true,
		'styles' => [
			'resources/src/mediawiki.pager.codex/CodexTablePager.less',
		]
	],
	'mediawiki.pulsatingdot' => [
		'styles' => [
			'resources/src/mediawiki.pulsatingdot/mediawiki.pulsatingdot.less',
		],
	],
	'mediawiki.searchSuggest' => [
		'scripts' => 'resources/src/mediawiki.searchSuggest/searchSuggest.js',
		'styles' => 'resources/src/mediawiki.searchSuggest/searchSuggest.css',
		'messages' => [
			'searchsuggest-search',
			'searchsuggest-containing',
		],
		'dependencies' => [
			'jquery.suggestions',
			'mediawiki.api',
			'user.options',
		],
	],
	'mediawiki.storage' => [
		'packageFiles' => [
			'resources/src/mediawiki.storage/index.js',
			'resources/src/mediawiki.storage/SafeStorage.js',
		],
		'dependencies' => [
			'mediawiki.util',
		],
	],
	'mediawiki.Title' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.Title',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.Title",
		'packageFiles' => [
			'Title.js',
			'phpCharToUpper.json'
		],
		'dependencies' => [
			'mediawiki.String',
			'mediawiki.util',
		],
	],
	'mediawiki.Upload' => [
		'scripts' => 'resources/src/mediawiki.Upload.js',
		'dependencies' => [
			'mediawiki.api',
		],
	],
	'mediawiki.ForeignUpload' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src',
		'remoteBasePath' => "$wgResourceBasePath/resources/src",
		'packageFiles' => [
			'mediawiki.ForeignUpload.js',
			[
				'name' => 'config.json',
				'config' => [ MainConfigNames::ForeignUploadTargets, MainConfigNames::EnableUploads ],
			],
		],
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
	'mediawiki.Upload.Dialog' => [
		'scripts' => [
			'resources/src/mediawiki.Upload.Dialog.js',
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
			'resources/src/mediawiki.Upload.BookletLayout/mw.widgets.StashedFileWidget.js',
			'resources/src/mediawiki.Upload.BookletLayout/BookletLayout.js',
		],
		'styles' => [
			'resources/src/mediawiki.Upload.BookletLayout/BookletLayout.less',
		],
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.Upload.BookletLayout/mw.widgets.StashedFileWidget.less',
			],
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-windows',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'mediawiki.Title',
			'mediawiki.api',
			'mediawiki.user',
			'mediawiki.Upload',
			'mediawiki.jqueryMsg',
		],
		'messages' => [
			'upload-form-label-infoform-title',
			'upload-form-label-infoform-name',
			'upload-form-label-infoform-name-tooltip',
			'upload-form-label-infoform-description',
			'upload-form-label-infoform-description-tooltip',
			'upload-form-label-usage-title',
			'upload-form-label-usage-filename',
			'action-upload',
			'apierror-mustbeloggedin',
			'apierror-permissiondenied',
			'badaccess-groups',
			'api-error-unknown-warning',
			'fileexists',
			'filepageexists',
			'file-exists-duplicate',
			'file-deleted-duplicate',
			'filename-bad-prefix',
			'filename-thumb-name',
			'filewasdeleted',
			'badfilename',
			'protectedpagetext',
		],
	],
	'mediawiki.ForeignStructuredUpload.BookletLayout' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.ForeignStructuredUpload.BookletLayout',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.ForeignStructuredUpload.BookletLayout",
		'packageFiles' => [
			"index.js",
			'ForeignStructuredUpload.js',
			[ 'name' => 'config.json', 'config' => [ MainConfigNames::UploadDialog ] ],
			"BookletLayout.js",
		],
		'dependencies' => [
			'mediawiki.ForeignUpload',
			'mediawiki.Upload.BookletLayout',
			'mediawiki.widgets.CategoryMultiselectWidget',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.jqueryMsg',
			'mediawiki.api',
			'mediawiki.libs.jpegmeta',
		],
		'messages' => [
			'upload-foreign-cant-load-config',
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
		'scripts' => 'resources/src/mediawiki.toc/toc.js',
		'styles' => [
			'resources/src/mediawiki.toc/toc.css'
				=> [ 'media' => 'screen' ],
		],
		'dependencies' => [
			'mediawiki.cookie',
		],
		'messages' => [
			'table-of-contents-show-button-aria-label',
			'table-of-contents-hide-button-aria-label'
		],
	],
	'mediawiki.Uri' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.Uri',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.Uri",
		'packageFiles' => [
			'Uri.js',
		],
		'dependencies' => 'mediawiki.util',
		'deprecated' =>
			'[1.43] Please use browser native URL. See https://www.mediawiki.org/wiki/Migrating_mw.Uri_to_URL',
	],
	'mediawiki.user' => [
		'scripts' => 'resources/src/mediawiki.user.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.cookie',
			// user.options is not directly used in mediawiki.user, but it
			// provides part of the mw.user API that consumers expect
			'user.options',
		],
	],
	'mediawiki.userSuggest' => [
		'scripts' => 'resources/src/mediawiki.userSuggest.js',
		'dependencies' => [
			'jquery.suggestions',
			'mediawiki.api'
		],
	],
	'mediawiki.util' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.util',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.util",
		'packageFiles' => [
			'util.js',
			'jquery.accessKeyLabel.js',
			[ 'name' => 'config.json', 'config' => [
				MainConfigNames::FragmentMode,
				MainConfigNames::GenerateThumbnailOnParse,
				MainConfigNames::LoadScript,
				MainConfigNames::AutoCreateTempUser,
			] ],
			[ 'name' => 'portletLinkOptions.json', 'callback' => [ Skin::class, 'getPortletLinkOptions' ] ],
			[
				'name' => 'infinityValues.json',
				'callback' => static function () {
					return ExpiryDef::INFINITY_VALS;
				}
			]
		],
		'dependencies' => [
			'jquery.client',
			'web2017-polyfills',
		],
		'messages' => [ 'brackets', 'word-separator' ],
	],
	'mediawiki.checkboxtoggle' => [
		'scripts' => 'resources/src/mediawiki.checkboxtoggle.js',
	],
	'mediawiki.checkboxtoggle.styles' => [
		'styles' => 'resources/src/mediawiki.checkboxtoggle.styles.less',
	],
	'mediawiki.cookie' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.cookie',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.cookie",
		'packageFiles' => [
			'index.js',
			'jar.js',
			'jquery.js',
			[ 'name' => 'config.json', 'config' => [
				'prefix' => MainConfigNames::CookiePrefix,
				'domain' => MainConfigNames::CookieDomain,
				'path' => MainConfigNames::CookiePath,
				'expires' => MainConfigNames::CookieExpiration,
			] ],
		],
	],
	'mediawiki.experiments' => [
		'scripts' => 'resources/src/mediawiki.experiments.js',
	],
	'mediawiki.editfont.styles' => [
		'styles' => 'resources/src/mediawiki.editfont.less',
	],
	'mediawiki.visibleTimeout' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.visibleTimeout',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.visibleTimeout",
		'packageFiles' => [
			'visibleTimeout.js'
		],
	],

	/* MediaWiki Action */

	'mediawiki.action.edit' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.action.edit',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.action.edit",
		'packageFiles' => [
			'edit.js',
			'stash.js',
			'watchlistExpiry.js',
		],
		'dependencies' => [
			'mediawiki.action.edit.styles',
			'mediawiki.editfont.styles',
			'jquery.textSelection',
			'oojs-ui-core',
			'mediawiki.widgets.visibleLengthLimit',
			'mediawiki.api',
			'mediawiki.util',
		],
	],
	'mediawiki.action.edit.styles' => [
		'styles' => [
			'resources/src/mediawiki.action/mediawiki.action.edit.styles.less',
			'resources/src/mediawiki.action/mediawiki.action.edit.checkboxes.less',
		]
	],
	'mediawiki.action.edit.collapsibleFooter' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.less',
		'dependencies' => [
			'jquery.makeCollapsible',
			'mediawiki.storage',
		],
	],
	'mediawiki.action.edit.preview' => [
		'packageFiles' => [
			'resources/src/mediawiki.action/mediawiki.action.edit.preview.js',
			[
				'name' => 'resources/src/mediawiki.action/mediawiki.action.edit.preview.parsedMessages.json',
				'callback' => static function ( MessageLocalizer $messageLocalizer ) {
					return [
						'previewnote' => $messageLocalizer->msg( 'previewnote' )->parse(),
					];
				},
				// Use versionCallback to avoid calling the parser from version invalidation code.
				'versionCallback' => static function ( MessageLocalizer $messageLocalizer ) {
					return [
						'previewnote' => [
							// Include the text of the message, in case the canonical translation changes
							$messageLocalizer->msg( 'previewnote' )->plain(),
							// Include the page touched time, in case the on-wiki override is invalidated
							Title::makeTitle( NS_MEDIAWIKI, 'Previewnote' )->getTouched(),
						],
					];
				},
			]
		],
		'dependencies' => [
			'jquery.spinner',
			'mediawiki.api',
			'mediawiki.diff',
			'mediawiki.diff.styles',
			'mediawiki.user',
			'mediawiki.page.preview',
		],
		'messages' => [
			'otherlanguages',
			'preview',
		],
	],
	'mediawiki.action.history' => [
		'dependencies' => [ 'jquery.makeCollapsible' ],
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.history.js',
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.history.css',
	],

	'mediawiki.action.history.styles' => [
		'class' => CodexModule::class,
		'codexComponents' => [ 'CdxButton' ],
		'codexStyleOnly' => true,
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.action/mediawiki.action.history.styles.less',
		],
	],

	'mediawiki.action.protect' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.action',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.action",
		'packageFiles' => [
			'mediawiki.action.protect.js',
			[ 'name' => 'config.json', 'config' => [ MainConfigNames::CascadingRestrictionLevels ] ],
		],
		'dependencies' => [
			'oojs-ui-core',
			'mediawiki.widgets.visibleLengthLimit',
		],
		'messages' => [
			'protect-unchain-permissions',
		],
	],
	'mediawiki.action.view.metadata' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.less',
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.metadata.js',
		'messages' => [
			'metadata-expand',
			'metadata-collapse',
		],
		'dependencies' => 'mediawiki.action.view.filepage',
	],

	'mediawiki.editRecovery.postEdit' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.editRecovery',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.editRecovery",
		'packageFiles' => [
			'postEdit.js',
			'storage.js',
			[
				'name' => 'config.json',
				'config' => [ 'EditRecoveryExpiry' ],
			],
		],
	],
	'mediawiki.editRecovery.edit' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.editRecovery',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.editRecovery",
		'packageFiles' => [
			'edit.js',
			'storage.js',
			'LoadNotification.js',
			[
				'name' => 'config.json',
				'config' => [ 'EditRecoveryExpiry' ],
			],
		],
		'styles' => [
			'styles.less',
		],
		'dependencies' => [
			'mediawiki.widgets.AbandonEditDialog',
			'mediawiki.notification',
			'oojs-ui.styles.icons-editing-core',
		],
		'messages' => [
			'edit-recovery-loaded-title',
			'edit-recovery-loaded-message',
			'edit-recovery-loaded-message-different-rev',
			'edit-recovery-loaded-message-different-rev-publish',
			'edit-recovery-loaded-message-different-rev-save',
			'edit-recovery-loaded-recover',
			'edit-recovery-loaded-discard',
			'word-separator',
		],
	],

	'mediawiki.action.view.postEdit' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.action',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.action",
		'packageFiles' => [
			'mediawiki.action.view.postEdit.js',
			[ 'name' => 'config.json', 'config' => [ MainConfigNames::EditSubmitButtonLabelPublish ] ],
		],
		'dependencies' => [
			'mediawiki.tempUserCreated',
			'mediawiki.jqueryMsg',
			'mediawiki.notification',
			'mediawiki.storage',
			'oojs-ui-core',
			'oojs-ui.styles.icons-interactions',
		],
		'messages' => [
			'postedit-confirmation-created',
			'postedit-confirmation-restored',
			'postedit-confirmation-saved',
			'postedit-confirmation-published',
			'postedit-temp-created-label',
			'postedit-temp-created',
		],
	],
	'mediawiki.action.view.redirect' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.view.redirect.js',
	],
	'mediawiki.action.view.redirectPage' => [
		'styles' => 'resources/src/mediawiki.action/mediawiki.action.view.redirectPage.less',
	],
	'mediawiki.action.edit.editWarning' => [
		'scripts' => 'resources/src/mediawiki.action/mediawiki.action.edit.editWarning.js',
		'dependencies' => [
			'jquery.textSelection',
			'mediawiki.jqueryMsg',
			'mediawiki.confirmCloseWindow',
			'user.options',
		],
	],
	'mediawiki.action.view.filepage' => [
		'styles' => [
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.print.less' =>
				[ 'media' => 'print' ],
			'resources/src/mediawiki.action/mediawiki.action.view.filepage.less',
		],
	],

	// This bundles small stylesheets (<2KB) that:
	// - .. are not loaded when viewing or editing content pages.
	// - .. style the rendering of other wikipage actions and/or other namespaces.
	'mediawiki.action.styles' => [
		'styles' => [
			'resources/src/mediawiki.action.styles/styles.less',
			'resources/src/mediawiki.action.styles/categoryPage.less',
		],
	],

	/* MediaWiki Language */

	'mediawiki.language' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.language',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.language",
		'scripts' => [
			'mediawiki.language.init.js',
			'mediawiki.language.js',
			'mediawiki.language.numbers.js',
			'mediawiki.language.fallback.js',
			[
				'name' => 'mediawiki.language.config.js',
				'callback' => static function ( Context $context, Config $config ) {
					$langCode = $context->getLanguage();
					$language = MediaWikiServices::getInstance()->getLanguageFactory()
						->getLanguage( $langCode );
					return 'mw.language.setData('
					. $context->encodeJson( $langCode ) . ','
					. $context->encodeJson( $language->getJsData() )
					. ');';
				}
			],
		],
		'languageScripts' => [
			'bs' => 'languages/bs.js',
			'dsb' => 'languages/dsb.js',
			'fi' => 'languages/fi.js',
			'ga' => 'languages/ga.js',
			'hsb' => 'languages/hsb.js',
			'hu' => 'languages/hu.js',
			'hy' => 'languages/hy.js',
			'la' => 'languages/la.js',
			'os' => 'languages/os.js',
			'sl' => 'languages/sl.js',
		],
		'dependencies' => [
			'mediawiki.cldr',
		],
		'messages' => [
			'and',
			'comma-separator',
			'word-separator'
		],
	],

	'mediawiki.cldr' => [
		'scripts' => 'resources/src/mediawiki.cldr/index.js',
		'dependencies' => [
			'mediawiki.libs.pluralruleparser',
		],
	],

	'mediawiki.libs.pluralruleparser' => [
		'scripts' => [
			'resources/lib/CLDRPluralRuleParser/CLDRPluralRuleParser.js',
			'resources/src/mediawiki.libs.pluralruleparser/export.js',
		],
	],

	'mediawiki.jqueryMsg' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.jqueryMsg',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.jqueryMsg",
		'packageFiles' => [
			'mediawiki.jqueryMsg.js',
			[ 'name' => 'parserDefaults.json', 'callback' => static function (
				Context $context, Config $config
			) {
				$tagData = Sanitizer::getRecognizedTagData();
				$allowedHtmlElements = array_merge(
					array_keys( $tagData['htmlpairs'] ),
					array_diff(
						array_keys( $tagData['htmlsingle'] ),
						array_keys( $tagData['htmlsingleonly'] )
					)
				);

				$magicWords = [
					'SITENAME' => $config->get( MainConfigNames::Sitename ),
				];
				( new RL\HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
					->onResourceLoaderJqueryMsgModuleMagicWords( $context, $magicWords );

				// if changing this structure, please update the static JSON example file
				// at resources/src/mediawiki.jqueryMsg
				return [
					'allowedHtmlElements' => $allowedHtmlElements,
					'magic' => $magicWords,
				];
			} ],
		],
		'dependencies' => [
			'mediawiki.util',
			'mediawiki.language',
			'mediawiki.String',
			'mediawiki.Title',
			'user.options',
		],
	],

	'mediawiki.language.months' => [
		'scripts' => 'resources/src/mediawiki.language.months/months.js',
		'dependencies' => 'mediawiki.language',
		'messages' => array_merge(
			Language::MONTH_MESSAGES,
			Language::MONTH_GENITIVE_MESSAGES,
			Language::MONTH_ABBREVIATED_MESSAGES
		)
	],

	'mediawiki.language.names' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.language.names',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.language.names",
		'packageFiles' => [
			'names.js',
			[ 'name' => 'names.json', 'callback' => static function ( Context $context ) {
				return MediaWikiServices::getInstance()
					->getLanguageNameUtils()
					->getLanguageNames( $context->getLanguage(), LanguageNameUtils::ALL );
			} ],
		],
		'dependencies' => 'mediawiki.language',
	],

	'mediawiki.language.specialCharacters' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.language.specialCharacters',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.language.specialCharacters",
		'packageFiles' => [
			'specialCharacters.js',
			'specialcharacters.json'
		],
		'dependencies' => 'mediawiki.language',
		'messages' => [
			'special-characters-recently-used',
			'special-characters-group-latin',
			'special-characters-group-latinextended',
			'special-characters-group-ipa',
			'special-characters-group-symbols',
			'special-characters-group-greek',
			'special-characters-group-greekextended',
			'special-characters-group-cyrillic',
			'special-characters-group-arabic',
			'special-characters-group-arabicextended',
			'special-characters-group-persian',
			'special-characters-group-hebrew',
			'special-characters-group-bangla',
			'special-characters-group-tamil',
			'special-characters-group-telugu',
			'special-characters-group-sinhala',
			'special-characters-group-devanagari',
			'special-characters-group-gujarati',
			'special-characters-group-thai',
			'special-characters-group-lao',
			'special-characters-group-khmer',
			'special-characters-group-canadianaboriginal',
			'special-characters-group-runes',
			'special-characters-title-endash',
			'special-characters-title-emdash',
			'special-characters-title-minus'
		]
	],

	/* MediaWiki Libs */

	'mediawiki.libs.jpegmeta' => [
		'scripts' => [
			'resources/src/mediawiki.libs.jpegmeta/jpegmeta.js',
			'resources/src/mediawiki.libs.jpegmeta/export.js',
		],
	],

	/* MediaWiki Page */

	'mediawiki.page.gallery' => [
		'scripts' => 'resources/src/mediawiki.page.gallery.js',
		'dependencies' => [
			'mediawiki.page.gallery.styles',
			'mediawiki.util'
		],
	],
	'mediawiki.page.gallery.styles' => [
		'styles' => [
			'resources/src/mediawiki.page.gallery.styles/gallery.less',
			'resources/src/mediawiki.page.gallery.styles/print.less' => [ 'media' => 'print' ],
		],
	],
	'mediawiki.page.gallery.slideshow' => [
		'scripts' => 'resources/src/mediawiki.page.gallery.slideshow.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
			'oojs',
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui.styles.icons-media',
			'oojs-ui.styles.icons-movement'
		],
		'messages' => [
			'gallery-slideshow-toggle'
		]
	],
	'mediawiki.page.ready' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.page.ready',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.page.ready",
		'packageFiles' => [
			'ready.js',
			'enableSearchDialog.js',
			'checkboxShift.js',
			'checkboxHack.js',
			'clearAddressBar.js',
			'teleportTarget.js',
			'toggleAllCollapsibles.js',
			[ 'name' => 'config.json', 'callback' => static function (
				Context $context,
				Config $config
			) {
				$readyConfig = [
					'search' => true,
					'searchModule' => 'mediawiki.searchSuggest',
					'collapsible' => true,
					'sortable' => true,
					'selectorLogoutLink' => '#pt-logout a[data-mw="interface"]'
				];

				( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
					->onSkinPageReadyConfig( $context, $readyConfig );
				return $readyConfig;
			} ],
		],
		'dependencies' => [
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.api'
		],
		'messages' => [
			'collapsible-collapse-all-text',
			'collapsible-collapse-all-tooltip',
			'collapsible-expand-all-text',
			'collapsible-expand-all-tooltip',
			'logging-out-notify'
		],
		'skinStyles' => [
			'default' => 'teleportTarget.less'
		]
	],
	'mediawiki.page.watch.ajax' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.page.watch.ajax',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.page.watch.ajax",
		'packageFiles' => [
			'watch-ajax.js',
			[ 'name' => 'config.json', 'config' => [ MainConfigNames::WatchlistExpiry ] ],
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.Title',
			'mediawiki.jqueryMsg',
		],
		'messages' => [
			'watch',
			'unwatch',
			'watching',
			'unwatching',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'tooltip-ca-unwatch-expiring',
			'tooltip-ca-unwatch-expiring-hours',
			'addedwatchtext',
			'addedwatchtext-talk',
			'removedwatchtext',
			'removedwatchtext-talk',
		],
	],
	'mediawiki.page.preview' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src',
		'remoteBasePath' => "$wgResourceBasePath/resources/src",
		"class" => CodexModule::class,
		"codexComponents" => [
			"CdxMessage",
		],
		"codexStyleOnly" => true,
		'packageFiles' => [
			'mediawiki.page.preview.js',
		],
		'styles' => 'mediawiki.page.preview.css',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.diff',
			'mediawiki.diff.styles',
			'mediawiki.jqueryMsg',
			'mediawiki.language',
			'mediawiki.util',
			'mediawiki.user',
			'jquery.makeCollapsible',
			'jquery.textSelection',
			'oojs-ui-core',
		],
		'messages' => [
			'summary-preview',
			'parentheses',
			'word-separator',
			'comma-separator',
			'templatesusedpreview',
			'editlink',
			'viewsourcelink',
			'template-semiprotected',
			'template-protected',
			'restriction-level-sysop',
			'restriction-level-autoconfirmed',
			'diff-empty',
			'currentrev',
			'yourtext',
			'continue-editing',
			'previewerrortext',
		]
	],
	'mediawiki.page.image.pagination' => [
		'scripts' => 'resources/src/mediawiki.page.image.pagination.js',
		'dependencies' => [
			'mediawiki.util',
			'jquery.spinner',
		],
	],
	'mediawiki.page.media' => [
		'scripts' => 'resources/src/mediawiki.page.media.js',
	],

	/* MediaWiki Special pages */

	'mediawiki.rcfilters.filters.base.styles' => [
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.rcfilters/styles/mw.rcfilters.less',
		],
	],
	'mediawiki.rcfilters.highlightCircles.seenunseen.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.rcfilters/' .
				'styles/mw.rcfilters.ui.ChangesListWrapperWidget.highlightCircles.seenunseen.less',
			],
		],
	],
	// TODO consider renaming to mediawiki.rcfilters.filters following merge of
	// mediawiki.rcfilters.filters.dm into mediawiki.rcfilters.filters.ui, see T256836
	'mediawiki.rcfilters.filters.ui' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.rcfilters',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.rcfilters",
		'packageFiles' => [
			'mw.rcfilters.js',
			'utils.js',
			'Controller.js',
			'UriProcessor.js',
			'dm/ChangesListViewModel.js',
			'dm/FilterGroup.js',
			'dm/FilterItem.js',
			'dm/FiltersViewModel.js',
			'dm/ItemModel.js',
			'dm/SavedQueriesModel.js',
			'dm/SavedQueryItemModel.js',
			// TODO consider merging this with the config.json for the ui code
			[ 'name' => 'dmConfig.json', 'config' =>
				[ MainConfigNames::StructuredChangeFiltersLiveUpdatePollingRate ] ],
			'HighlightColors.js',
			'ui/CheckboxInputWidget.js',
			'ui/FilterTagMultiselectWidget.js',
			'ui/ItemMenuOptionWidget.js',
			'ui/FilterMenuOptionWidget.js',
			'ui/FilterMenuSectionOptionWidget.js',
			'ui/TagItemWidget.js',
			'ui/FilterTagItemWidget.js',
			'ui/FilterMenuHeaderWidget.js',
			'ui/MenuSelectWidget.js',
			'ui/MainWrapperWidget.js',
			'ui/ViewSwitchWidget.js',
			'ui/ValuePickerWidget.js',
			'ui/ChangesLimitPopupWidget.js',
			'ui/ChangesLimitAndDateButtonWidget.js',
			'ui/DatePopupWidget.js',
			'ui/FilterWrapperWidget.js',
			'ui/ChangesListWrapperWidget.js',
			'ui/SavedLinksListWidget.js',
			'ui/SavedLinksListItemWidget.js',
			'ui/SaveFiltersPopupButtonWidget.js',
			'ui/FormWrapperWidget.js',
			'ui/FilterItemHighlightButton.js',
			'ui/HighlightPopupWidget.js',
			'ui/HighlightColorPickerWidget.js',
			'ui/GroupByToggleWidget.js',
			'ui/LiveUpdateButtonWidget.js',
			'ui/MarkSeenButtonWidget.js',
			'ui/RcTopSectionWidget.js',
			'ui/RclTopSectionWidget.js',
			'ui/RclTargetPageWidget.js',
			'ui/RclToOrFromWidget.js',
			'ui/WatchlistTopSectionWidget.js',
			[ 'name' => 'config.json',
				'versionCallback' => [ ChangesListSpecialPage::class, 'getRcFiltersConfigSummary' ],
				'callback' => [ ChangesListSpecialPage::class, 'getRcFiltersConfigVars' ],
			],
		],
		'styles' => [
			'styles/mw.rcfilters.mixins.less',
			'styles/mw.rcfilters.variables.less',
			'styles/mw.rcfilters.ui.less',
			'styles/mw.rcfilters.ui.Overlay.less',
			'styles/mw.rcfilters.ui.FilterTagMultiselectWidget.less',
			'styles/mw.rcfilters.ui.ItemMenuOptionWidget.less',
			'styles/mw.rcfilters.ui.FilterMenuOptionWidget.less',
			'styles/mw.rcfilters.ui.FilterMenuSectionOptionWidget.less',
			'styles/mw.rcfilters.ui.TagItemWidget.less',
			'styles/mw.rcfilters.ui.FilterMenuHeaderWidget.less',
			'styles/mw.rcfilters.ui.MenuSelectWidget.less',
			'styles/mw.rcfilters.ui.ViewSwitchWidget.less',
			'styles/mw.rcfilters.ui.ValuePickerWidget.less',
			'styles/mw.rcfilters.ui.ChangesLimitPopupWidget.less',
			'styles/mw.rcfilters.ui.DatePopupWidget.less',
			'styles/mw.rcfilters.ui.FilterWrapperWidget.less',
			'styles/mw.rcfilters.ui.ChangesListWrapperWidget.less',
			'styles/mw.rcfilters.ui.HighlightColorPickerWidget.less',
			'styles/mw.rcfilters.ui.FilterItemHighlightButton.less',
			'styles/mw.rcfilters.ui.SavedLinksListWidget.less',
			'styles/mw.rcfilters.ui.SavedLinksListItemWidget.less',
			'styles/mw.rcfilters.ui.SaveFiltersPopupButtonWidget.less',
			'styles/mw.rcfilters.ui.GroupByToggleWidget.less',
			'styles/mw.rcfilters.ui.LiveUpdateButtonWidget.less',
			'styles/mw.rcfilters.ui.RcTopSectionWidget.less',
			'styles/mw.rcfilters.ui.RclToOrFromWidget.less',
			'styles/mw.rcfilters.ui.RclTargetPageWidget.less',
			'styles/mw.rcfilters.ui.WatchlistTopSectionWidget.less',
			'styles/mw.rcfilters.ui.FilterTagMultiselectWidgetMobile.less'
		],
		'skinStyles' => [
			'monobook' => [
				'styles/mw.rcfilters.ui.CapsuleItemWidget.monobook.less',
				'styles/mw.rcfilters.ui.FilterMenuOptionWidget.monobook.less',
			],
		],
		'messages' => [
			'rcfilters-tag-remove',
			'rcfilters-activefilters',
			'rcfilters-activefilters-hide',
			'rcfilters-activefilters-show',
			'rcfilters-activefilters-hide-tooltip',
			'rcfilters-activefilters-show-tooltip',
			'rcfilters-advancedfilters',
			'rcfilters-group-results-by-page',
			'rcfilters-limit-title',
			'rcfilters-limit-and-date-label',
			'rcfilters-limit-and-date-popup-dialog-aria-label',
			'rcfilters-date-popup-title',
			'rcfilters-days-title',
			'rcfilters-hours-title',
			'rcfilters-days-show-days',
			'rcfilters-days-show-hours',
			'rcfilters-highlighted-filters-list',
			'rcfilters-quickfilters',
			'rcfilters-quickfilters-placeholder-title',
			'rcfilters-quickfilters-placeholder-description',
			'rcfilters-savedqueries-defaultlabel',
			'rcfilters-savedqueries-rename',
			'rcfilters-savedqueries-setdefault',
			'rcfilters-savedqueries-unsetdefault',
			'rcfilters-savedqueries-remove',
			'rcfilters-savedqueries-new-name-label',
			'rcfilters-savedqueries-new-name-placeholder',
			'rcfilters-savedqueries-add-new-title',
			'rcfilters-savedqueries-already-saved',
			'rcfilters-savedqueries-apply-label',
			'rcfilters-savedqueries-cancel-label',
			'rcfilters-restore-default-filters',
			'rcfilters-clear-all-filters',
			'rcfilters-show-new-changes',
			'rcfilters-search-placeholder',
			'rcfilters-search-placeholder-mobile',
			'rcfilters-invalid-filter',
			'rcfilters-empty-filter',
			'rcfilters-filterlist-title',
			'rcfilters-filterlist-noresults',
			'rcfilters-filterlist-whatsthis',
			'rcfilters-highlightbutton-title',
			'rcfilters-highlightmenu-title',
			'rcfilters-highlightmenu-help',
			'rcfilters-noresults-conflict',
			'rcfilters-state-message-subset',
			'rcfilters-state-message-fullcoverage',
			'rcfilters-filter-excluded',
			'rcfilters-tag-help',
			'rcfilters-tag-prefix-namespace',
			'rcfilters-tag-prefix-namespace-inverted',
			'rcfilters-tag-prefix-tags',
			'rcfilters-tag-prefix-tags-inverted',
			'rcfilters-exclude-button-off',
			'rcfilters-exclude-button-on',
			'rcfilters-view-tags',
			'rcfilters-view-namespaces-tooltip',
			'rcfilters-view-tags-tooltip',
			'rcfilters-view-return-to-default-tooltip',
			'rcfilters-view-tags-help-icon-tooltip',
			'rcfilters-liveupdates-button',
			'rcfilters-liveupdates-button-title-on',
			'rcfilters-liveupdates-button-title-off',
			'rcfilters-watchlist-markseen-button',
			'rcfilters-watchlist-edit-watchlist-button',
			'rcfilters-watchlist-edit-watchlist-preferences-button',
			'rcfilters-other-review-tools',
			'rcfilters-filter-showlinkedfrom-label',
			'rcfilters-filter-showlinkedfrom-option-label',
			'rcfilters-filter-showlinkedto-label',
			'rcfilters-filter-showlinkedto-option-label',
			'rcfilters-target-page-placeholder',
			'rcfilters-allcontents-label',
			'rcfilters-alldiscussions-label',
			'blanknamespace',
			'namespaces',
			'tags-title',
			'invert',
			'recentchanges-noresult',
			'recentchanges-timeout',
			'recentchanges-network',
			'recentchanges-notargetpage',
			'allpagesbadtitle',
			'quotation-marks',
		],
		'dependencies' => [
			'web2017-polyfills',
			'jquery.makeCollapsible',
			'mediawiki.String',
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.language',
			'mediawiki.user',
			'mediawiki.util',
			'mediawiki.widgets',
			'oojs',
			'oojs-ui-widgets',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-moderation',
			'oojs-ui.styles.icons-editing-core',
			'oojs-ui.styles.icons-editing-styling',
			'oojs-ui.styles.icons-interactions',
			'oojs-ui.styles.icons-layout',
			'oojs-ui.styles.icons-media',
			'oojs-ui-windows.icons',
			'user.options',
		],
	],
	'mediawiki.interface.helpers.linker.styles' => [
		'class' => CodexModule::class,
		'codexStyleOnly' => true,
		'codexComponents' => [
			'CdxTooltip',
		],
	],
	'mediawiki.interface.helpers.styles' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.interface.helpers.styles',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.interface.helpers.styles",
		'class' => LessVarFileModule::class,
		'lessMessages' => [
			'comma-separator',
			'parentheses-start',
			'parentheses-end',
			'semicolon-separator',
			'brackets-start',
			'brackets-end',
			'pipe-separator'
		],
		'styles' => [
			'linker.styles.less',
		],
		'skinStyles' => [
			'default' => 'skinStyles.less',
		],
	],
	'mediawiki.special' => [
		'styles' => [
			'resources/src/mediawiki.special/special.less',
			'resources/src/mediawiki.special/apisandbox.css',
			'resources/src/mediawiki.special/comparepages.less',
			'resources/src/mediawiki.special/contributions.less',
			'resources/src/mediawiki.special/edittags.css',
			'resources/src/mediawiki.special/movePage.css',
			'resources/src/mediawiki.special/newpages.less',
			'resources/src/mediawiki.special/pagesWithProp.css',
			'resources/src/mediawiki.special/upload.css',
			'resources/src/mediawiki.special/userrights.css',
			'resources/src/mediawiki.special/watchlist.css',
			'resources/src/mediawiki.special/whatlinkshere.less',
			'resources/src/mediawiki.special/block.less',
			'resources/src/mediawiki.special/listFiles.less',
			'resources/src/mediawiki.special/blocklist.less',
			'resources/src/mediawiki.special/version.less',
			'resources/src/mediawiki.special/contribute.less',
			'resources/src/mediawiki.special/specialPages.less',
		],
	],
	'mediawiki.special.apisandbox' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.special.apisandbox',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.special.apisandbox",
		'styles' => 'apisandbox.less',
		'packageFiles' => [
			'init.js',
			'ApiSandbox.js',
			'ApiSandboxLayout.js',
			'OptionalParamWidget.js',
			'ParamLabelWidget.js',
			'BooleanToggleSwitchParamWidget.js',
			'DateTimeParamWidget.js',
			'LimitParamWidget.js',
			'PasswordParamWidget.js',
			'UploadSelectFileParamWidget.js',
			'TextParamMixin.js',
			'Util.js',
			'UtilMixin.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.util',
			'oojs-ui',
			'oojs-ui.styles.icons-content',
			'oojs-ui.styles.icons-editing-advanced',
			'oojs-ui.styles.icons-interactions',
			'oojs-ui.styles.icons-moderation',
			'mediawiki.widgets',
			'mediawiki.widgets.datetime',
			'mediawiki.widgets.TitlesMultiselectWidget',
			'jquery.makeCollapsible',
			'web2017-polyfills'
		],
		'messages' => [
			'apisandbox-intro',
			'apisandbox-submit',
			'apisandbox-reset',
			'apisandbox-retry',
			'apisandbox-loading',
			'apisandbox-load-error',
			'apisandbox-fetch-token',
			'apisandbox-add-multi',
			'apisandbox-helpurls',
			'apisandbox-examples',
			'apisandbox-dynamic-parameters',
			'apisandbox-dynamic-parameters-add-label',
			'apisandbox-dynamic-parameters-add-placeholder',
			'apisandbox-dynamic-error-exists',
			'apisandbox-templated-parameter-reason',
			'apisandbox-deprecated-parameters',
			'apisandbox-no-parameters',
			'paramvalidator-help-type-number-min',
			'paramvalidator-help-type-number-max',
			'paramvalidator-help-type-number-minmax',
			'api-help-param-deprecated',
			'api-help-param-deprecated-label',
			'api-help-param-internal',
			'api-help-param-internal-label',
			'api-help-param-multi-separate',
			'paramvalidator-help-multi-max',
			'paramvalidator-help-type-string-maxbytes',
			'paramvalidator-help-type-string-maxchars',
			'apisandbox-submit-invalid-fields-title',
			'apisandbox-submit-invalid-fields-message',
			'apisandbox-results',
			'apisandbox-sending-request',
			'apisandbox-loading-results',
			'apisandbox-results-error',
			'apisandbox-results-login-suppressed',
			'apisandbox-request-selectformat-label',
			'apisandbox-request-format-url-label',
			'apisandbox-request-url-label',
			'apisandbox-request-format-json-label',
			'apisandbox-request-json-label',
			'apisandbox-request-format-php-label',
			'apisandbox-request-php-label',
			'apisandbox-request-time',
			'apisandbox-request-post',
			'apisandbox-request-formdata',
			'apisandbox-results-fixtoken',
			'apisandbox-results-fixtoken-fail',
			'apisandbox-alert-page',
			'apisandbox-alert-field',
			'apisandbox-continue',
			'apisandbox-continue-clear',
			'apisandbox-continue-help',
			'apisandbox-param-limit',
			'apisandbox-multivalue-all-namespaces',
			'apisandbox-multivalue-all-values',
			'api-format-prettyprint-status',
			'blanknamespace',
			'comma-separator',
			'word-separator',
			'and'
		],
	],
	'mediawiki.special.restsandbox.styles' => [
		'styles' => [
			'resources/src/mediawiki.special.restsandbox/restsandbox.css',
		],
	],
	'mediawiki.special.restsandbox' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources',
		'remoteBasePath' => "$wgResourceBasePath/resources",
		'packageFiles' => [
			"src/mediawiki.special.restsandbox/restsandbox.js",
			"lib/swagger-ui/swagger-ui-bundle.js",
			"lib/swagger-ui/swagger-ui-standalone-preset.js",
			[
				'name' => 'src/mediawiki.special.restsandbox/config.json',
				'config' => [ 'RestSandboxSpecs' ],
			],
		],
		'styles' => [
			'lib/swagger-ui/swagger-ui.css',
		],
		'dependencies' => [
			'mediawiki.special.restsandbox.styles'
		]
	],
	'mediawiki.special.block' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src',
		'remoteBasePath' => "$wgResourceBasePath/resources/src",
		'packageFiles' => [
			'mediawiki.special.block.js',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui.styles.icons-editing-core',
			'oojs-ui.styles.icons-editing-advanced',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.widgets.SelectWithInputWidget',
			'mediawiki.widgets.NamespacesMultiselectWidget',
			'mediawiki.widgets.TitlesMultiselectWidget',
			'mediawiki.widgets.UserInputWidget',
			'mediawiki.util',
			'mediawiki.htmlform',
			'moment',
		],
	],
	// This bundles various small (under 5 KB?) JavaScript files that:
	// - .. are never loaded when viewing or editing wiki pages.
	// - .. are only used by logged-in users.
	// - .. depend on oojs-ui-core.
	// - .. contain UI initialisation code (e.g. no public module exports, because
	//      requiring or depending on this bundle is awkward)
	'mediawiki.misc-authed-ooui' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.misc-authed-ooui',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.misc-authed-ooui",
		'scripts' => [
			'action.delete.js',
			'special.changecredentials.js',
			'special.import.js',
			'special.movePage.js',
			'special.mute.js',
			'special.pageLanguage.js',
			'special.revisionDelete.js',
			'special.undelete.js',
			'special.undelete.loadMoreRevisions.js',
		],
		'dependencies' => [
			'jquery.spinner',
			'mediawiki.util',
			'mediawiki.api',
			'mediawiki.htmlform.ooui',
			'mediawiki.widgets.visibleLengthLimit',
			'mediawiki.widgets',
			'oojs-ui-core',
		],
	],
	// This bundles various small (under 2 KB?) JavaScript files that:
	// - .. are only used by logged-in users when a non-default preference was enabled.
	// - .. may be loaded in the critical path for those users on page views.
	// - .. do NOT depend on OOUI.
	// - .. contain only UI initialisation code (e.g. no public exports)
	'mediawiki.misc-authed-pref' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.misc-authed-pref',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.misc-authed-pref",
		'scripts' => [
			'rightClickEdit.js',
			'dblClickEdit.js',
		],
		'dependencies' => [
			'user.options',
		],
	],
	// This bundles various small scripts that relate to moderation or curation
	// in some way, and:
	// - .. only have an effect for a privileged subset of logged-in users.
	// - .. may be loaded in the critical path on page views.
	// - .. do NOT depend on OOUI or other "large" modules.
	// - .. contain only UI initialisation code (e.g. no public exports)
	'mediawiki.misc-authed-curate' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.misc-authed-curate',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.misc-authed-curate",
		'scripts' => [
			'patrol.js',
			'rollback.js',
			'edittags.js',
		],
		'dependencies' => [
			'jquery.chosen',
			'jquery.lengthLimit',
			'jquery.spinner',
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.Title',
			'user.options',
			'jquery.confirmable',
		],
		'messages' => [
			'markedaspatrollednotify',
			'rollback-confirmation-confirm',
			'rollback-confirmation-yes',
			'rollback-confirmation-no',
			'tags-edit-chosen-placeholder',
			'tags-edit-chosen-no-results',
		],
	],
	'mediawiki.special.block.codex' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.special.block',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.special.block",
		'packageFiles' => [
			'init.js',
			'util.js',
			'stores/block.js',
			'components/AdditionalDetailsField.vue',
			'components/BlockDetailsField.vue',
			'components/BlockTypeField.vue',
			'components/ConfirmationDialog.vue',
			'components/ExpiryField.vue',
			'components/NamespacesField.vue',
			'components/PagesField.vue',
			'components/ReasonField.vue',
			'components/BlockLog.vue',
			'components/UserLookup.vue',
			'components/ValidatingTextInput.js',
			'SpecialBlock.vue',
			[
				'name' => 'icons.json',
				'callback' => 'MediaWiki\\ResourceLoader\\CodexModule::getIcons',
				'callbackParam' => [
					'cdxIconCancel',
					'cdxIconEdit',
					'cdxIconTrash',
					'cdxIconSearch',
					'cdxIconAlert',
					'cdxIconInfoFilled',
				],
			],
		],
		'dependencies' => [
			'vue',
			'@wikimedia/codex',
			'pinia',
			'mediawiki.api',
			'mediawiki.util',
			'mediawiki.jqueryMsg',
			'mediawiki.confirmCloseWindow',
			'mediawiki.Title',
			'mediawiki.DateFormatter',
		],
		'messages' => [
			'api-error-unknownerror',
			'blanknamespace',
			'block-actions',
			'block-added-message',
			'block-change-visibility',
			'block-confirm-no',
			'block-confirm-yes',
			'block-cancel',
			'block-create',
			'block-details',
			'block-details-description',
			'block-expiry',
			'block-expiry-custom',
			'block-expiry-custom-days',
			'block-expiry-custom-hours',
			'block-expiry-custom-minutes',
			'block-expiry-custom-months',
			'block-expiry-custom-weeks',
			'block-expiry-custom-years',
			'block-expiry-datetime',
			'block-expiry-preset',
			'block-expiry-preset-placeholder',
			'block-item-edit',
			'block-unblock-redirected',
			'block-invalid-id',
			'block-item-remove',
			'block-log-flags-angry-autoblock',
			'block-log-flags-anononly',
			'block-log-flags-hiddenname',
			'block-log-flags-noautoblock',
			'block-log-flags-nocreate',
			'block-log-flags-noemail',
			'block-log-flags-nousertalk',
			'block-namespaces-placeholder',
			'block-options',
			'block-pages-placeholder',
			'block-reason',
			'block-reason-help',
			'block-reason-other',
			'block-removal-confirm-no',
			'block-removal-confirm-yes',
			'block-removal-title',
			'block-removal-reason-placeholder',
			'block-removed',
			'block-submit',
			'block-success',
			'block-target',
			'block-target-placeholder',
			'block-update',
			'block-updated-message',
			'block-user-active-blocks',
			'block-user-active-range-blocks',
			'block-user-label-count-exceeds-limit',
			'block-user-no-active-blocks',
			'block-user-no-active-range-blocks',
			'block-user-no-previous-blocks',
			'block-user-no-suppressed-blocks',
			'block-user-previous-blocks',
			'block-user-suppressed-blocks',
			'block-view-target',
			'blockipsuccesssub',
			'blocklist-actions-header',
			'blocklist-by',
			'blocklist-editing',
			'blocklist-editing-sitewide',
			'blocklist-editing-page',
			'blocklist-editing-ns',
			'blocklist-editing-action',
			'blocklist-expiry',
			'blocklist-params',
			'blocklist-reason',
			'blocklist-target',
			'blocklist-timestamp',
			'blocklist-type-header',
			'blocklist-type-opt-partial',
			'blocklist-type-opt-sitewide',
			'colon-separator',
			'contribslink',
			'htmlform-optional-flag',
			'htmlform-selectorother-other',
			'infiniteblock',
			'ipb-action-create',
			'ipb-action-move',
			'ipb-action-upload',
			'ipb-blockingself',
			'ipb-blocklist-contribs',
			'ipb-change-block',
			'ipb-confirm',
			'ipb-confirmaction',
			'ipb-confirmhideuser',
			'ipb-disableusertalk',
			'ipb-edit-dropdown',
			'ipb-hardblock',
			'block-multiblocks-new-feature',
			'ipb-namespaces-label',
			'ipb-needreblock',
			'ipb-pages-label',
			'ipb-partial-help',
			'ipb-sitewide-help',
			'ipb_expiry_invalid',
			'ipbcreateaccount',
			'ipbemailban',
			'ipbenableautoblock',
			'ipbhidename',
			'ipbwatchuser',
			'ip_range_toolarge',
			'log-action-filter-block-block',
			'log-action-filter-block-reblock',
			'log-action-filter-block-unblock',
			'log-fulllog',
			'nosuchusershort',
			'parentheses-end',
			'parentheses-start',
			'pipe-separator',
			'talkpagelinktext',
		],
	],
	'mediawiki.protectionIndicators.styles' => [
		'styles' => 'resources/src/mediawiki.protectionIndicators/styles.less',
	],
	'mediawiki.special.changeslist' => [
		'styles' => [
			'resources/src/mediawiki.special.changeslist/changeslist.less'
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.special.changeslist/default.less',
		],
	],
	'mediawiki.special.changeslist.watchlistexpiry' => [
		'scripts' => 'resources/src/mediawiki.special.changeslist.watchlistexpiry/watchlistexpiry.js',
		'styles' => 'resources/src/mediawiki.special.changeslist.watchlistexpiry/watchlistexpiry.less',
		'messages' => [
			'parentheses',
			'watchlist-expiry-days-left',
			'watchlist-expiry-hours-left',
		],
		'dependencies' => [
			'mediawiki.special',
			'oojs-ui.styles.icons-interactions'
		],
	],
	'mediawiki.special.changeslist.enhanced' => [
		'styles' => 'resources/src/mediawiki.special.changeslist.enhanced.less',
	],
	'mediawiki.special.changeslist.legend' => [
		'styles' => 'resources/src/mediawiki.special.changeslist.legend.less',
	],
	'mediawiki.special.changeslist.legend.js' => [
		'scripts' => 'resources/src/mediawiki.special.changeslist.legend.js',
		'dependencies' => [
			'mediawiki.cookie',
		],
	],
	'mediawiki.special.contributions' => [
		'scripts' => 'resources/src/mediawiki.special.contributions.js',
		'dependencies' => [
			'jquery.makeCollapsible',
			'oojs-ui',
			'mediawiki.widgets.DateInputWidget',
			'mediawiki.jqueryMsg',
		],
	],
	'mediawiki.special.import.styles.ooui' => [
		'styles' => 'resources/src/mediawiki.special.import.styles.ooui.less',
	],
	'mediawiki.special.interwiki' => [
		'styles' => 'resources/src/mediawiki.special.interwiki.less',
	],
	'mediawiki.special.changecredentials' => [
		'scripts' => [ 'resources/src/mediawiki.special.changecredentails.js' ],
	],
	'mediawiki.special.changeemail' => [
		'scripts' => [ 'resources/src/mediawiki.special.changeemail.js' ],
	],

	'mediawiki.special.preferences.ooui' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.special.preferences.ooui',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.special.preferences.ooui",
		'packageFiles' => [
			'init.js',
			'confirmClose.js',
			'convertmessagebox.js',
			'editfont.js',
			'nav.js',
			[
				'name' => 'nav-setup.js',
				'callback' => static function ( Context $context ) {
					$skinName = $context->getSkin();
					( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
						->onPreferencesGetLayout( $useMobileLayout, $skinName );
					$file = $useMobileLayout ? 'nav-mobile.js' : 'nav-tabs.js';
					return new FilePath( $file );
				},
			],
			'skinPrefs.js',
			'signature.js',
			'timezone.js',
		],
		'messages' => [
			'prefs-tabs-navigation-hint',
			'prefs-sections-navigation-hint',
			'prefs-signature-highlight-error',
			'prefs-back-title',
			'searchprefs',
			'searchprefs-noresults',
			'searchprefs-results',
		],
		'dependencies' => [
			'mediawiki.language',
			'mediawiki.confirmCloseWindow',
			'mediawiki.notification.convertmessagebox',
			'mediawiki.storage',
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-windows',
			'mediawiki.widgets.SelectWithInputWidget',
			'mediawiki.editfont.styles',
			'mediawiki.widgets.visibleLengthLimit',
		],
	],
	'mediawiki.special.preferences.styles.ooui' => [
		'styles' => 'resources/src/mediawiki.special.preferences.styles.ooui.less',
	],
	'mediawiki.special.editrecovery.styles' => [
		'styles' => 'resources/src/mediawiki.special.editrecovery/styles.less',
	],
	'mediawiki.special.editrecovery' => [
		'packageFiles' => [
			'resources/src/mediawiki.special.editrecovery/init.js',
			'resources/src/mediawiki.special.editrecovery/SpecialEditRecovery.vue',
			'resources/src/mediawiki.editRecovery/storage.js',
			[
				'name' => 'resources/src/mediawiki.editRecovery/config.json',
				'config' => [ 'EditRecoveryExpiry' ],
			],
		],
		'dependencies' => [
			'vue',
		],
		'messages' => [
			'editlink',
			'parentheses-start',
			'parentheses-end',
			'pipe-separator',
			'edit-recovery-special-intro',
			'edit-recovery-special-intro-empty',
			'edit-recovery-special-view',
			'edit-recovery-special-edit',
			'edit-recovery-special-delete',
			'edit-recovery-special-recovered-on',
			'edit-recovery-special-recovered-on-tooltip',
		],
	],
	'mediawiki.special.search' => [
		'scripts' => 'resources/src/mediawiki.special.search/search.js',
		'dependencies' => 'mediawiki.widgets.SearchInputWidget',
	],
	'mediawiki.special.search.commonsInterwikiWidget' => [
		'scripts' => 'resources/src/mediawiki.special.search.commonsInterwikiWidget.js',
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
			'web2017-polyfills'
		],
		'messages' => [
			'search-interwiki-more-results',
			'searchprofile-images'
		],
	],
	'mediawiki.special.search.interwikiwidget.styles' => [
		'styles' => 'resources/src/mediawiki.special.search.interwikiwidget.styles.less',
	],
	'mediawiki.special.search.styles' => [
		'styles' => 'resources/src/mediawiki.special.search.styles.less',
	],
	'mediawiki.special.unwatchedPages' => [
		'scripts' => 'resources/src/mediawiki.special.unwatchedPages/unwatchedPages.js',
		'styles' => 'resources/src/mediawiki.special.unwatchedPages/unwatchedPages.css',
		'messages' => [
			'addedwatchtext-short',
			'removedwatchtext-short',
			'unwatch',
			'unwatching',
			'watch',
			'watching',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.Title',
			'mediawiki.util',
		],
	],
	'mediawiki.special.upload' => [
		'templates' => [
			'thumbnail.html' => 'resources/src/mediawiki.special.upload/templates/thumbnail.html',
		],
		'scripts' => 'resources/src/mediawiki.special.upload/upload.js',
		'messages' => [
			'widthheight',
			'size-bytes',
			'size-kilobytes',
			'size-megabytes',
			'size-gigabytes',
			'largefileserver',
		],
		'dependencies' => [
			'mediawiki.special',
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
	'mediawiki.authenticationPopup' => [
		'packageFiles' => [
			'resources/src/mediawiki.authenticationPopup/index.js',
			'resources/src/mediawiki.authenticationPopup/constants.js',
			'resources/src/mediawiki.authenticationPopup/AuthPopup.js',
			'resources/src/mediawiki.authenticationPopup/AuthMessageDialog.js',
			'resources/src/mediawiki.authenticationPopup/AuthPopupError.js',
			[
				'name' => 'resources/src/mediawiki.authenticationPopup/config.json',
				'callback' => static function ( Context $context ) {
					$specials = MediaWikiServices::getInstance()->getSpecialPageFactory();
					return [
						'specialPageNames' => [
							'UserLogin' => $specials->getLocalNameFor( 'Userlogin' ),
							'AuthenticationPopupSuccess' => $specials->getLocalNameFor( 'AuthenticationPopupSuccess' ),
						],
					];
				}
			],
		],
		'messages' => [
			'userlogin-authpopup-loggingin-title',
			'userlogin-authpopup-loggingin-body',
			'userlogin-authpopup-loggingin-body-link',
			'userlogin-authpopup-retry',
			'userlogin-authpopup-cancel',
		],
		'dependencies' => [
			'jquery.spinner',
			'mediawiki.Title',
			'oojs-ui-windows',
		]
	],
	'mediawiki.authenticationPopup.success' => [
		'packageFiles' => [
			'resources/src/mediawiki.authenticationPopup/success.js',
			'resources/src/mediawiki.authenticationPopup/constants.js',
		]
	],
	'mediawiki.special.userlogin.common.styles' => [
		'styles' => [
			'resources/src/mediawiki.special.userlogin.common.styles/userlogin.less',
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.special.userlogin.common.styles/skinStyles.less',
		],
	],
	'mediawiki.special.userlogin.login.styles' => [
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.special.userlogin.login.styles/login.less',
		],
	],
	'mediawiki.special.userlogin.authentication-popup' => [
		'styles' => 'resources/src/mediawiki.special.userlogin.common.styles/authentication-popup.less',
	],
	'mediawiki.special.createaccount' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.special.createaccount',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.special.createaccount",
		'packageFiles' => [
			'signup.js',
			'HtmlformChecker.js'
		],
		'messages' => [
			'createacct-emailrequired',
			'noname',
			'userexists',
			'createacct-normalization',
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.util',
		],
	],
	'mediawiki.special.userlogin.signup.styles' => [
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.special.userlogin.signup.styles/signup.less',
		],
	],
	'mediawiki.special.specialpages' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src',
		'remoteBasePath' => "$wgResourceBasePath/resources/src",
		'packageFiles' => [
			'mediawiki.special.specialpages/init.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.special.userrights' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src',
		'remoteBasePath' => "$wgResourceBasePath/resources/src",
		'packageFiles' => [
			'mediawiki.special.userrights.js',
			[
				'name' => 'config.json',
				'config' => [
					MainConfigNames::UserrightsInterwikiDelimiter
				],
			],
		],
		'dependencies' => [
			'mediawiki.notification.convertmessagebox',
			'jquery.lengthLimit',
		],
	],
	'mediawiki.special.watchlist' => [
		'scripts' => [
			'resources/src/mediawiki.special.watchlist/watchlist.js',
			'resources/src/mediawiki.special.watchlist/visitedstatus.js',
			'resources/src/mediawiki.special.watchlist/editwatchlist.js'
		],
		'messages' => [
			'addedwatchtext',
			'addedwatchtext-talk',
			'removedwatchtext',
			'removedwatchtext-talk',
			'tooltip-ca-watch',
			'tooltip-ca-unwatch',
			'tooltip-ca-unwatch-expiring',
			'tooltip-ca-unwatch-expiring-hours',
			'watchlist-unwatch',
			'watchlist-unwatch-undo',
			'watchlistedit-normal-check-all'
		],
		'dependencies' => [
			'mediawiki.api',
			'mediawiki.jqueryMsg',
			'mediawiki.Title',
			'mediawiki.util',
			'oojs-ui-core',
			'oojs-ui.styles.icons-interactions',
			'user.options',
		],
	],
	'mediawiki.tempUserBanner.styles' => [
		'styles' => [
			'resources/src/mediawiki.tempUserBanner/tempUserBanner.less',
		]
	],
	'mediawiki.tempUserBanner' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.tempUserBanner',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.tempUserBanner",
		'packageFiles' => [
			'tempUserBanner.js',
			[ 'name' => 'config.json', 'config' => [ MainConfigNames::AutoCreateTempUser ] ],
		],
		'dependencies' => [
			'mediawiki.jqueryMsg',
		],
		'messages' => [
			'temp-user-banner-tooltip-title',
			'temp-user-banner-tooltip-description-expiration-soon',
			'temp-user-banner-tooltip-description-expiration-soon-day',
			'temp-user-banner-tooltip-description-learn-more',
			'temp-user-banner-tooltip-description-login'
		]
	],
	'mediawiki.tempUserCreated' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.tempUserCreated',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.tempUserCreated",
		'packageFiles' => [
			'mediawiki.tempUserCreated.js',
			[ 'name' => 'contLangMessages.json', 'callback' => static function ( MessageLocalizer $messageLocalizer ) {
				return [
					'tempuser-helppage' => $messageLocalizer->msg( 'tempuser-helppage' )->inContentLanguage()->text(),
				];
			} ],
		],
		'dependencies' => [
			'mediawiki.util',
		],
	],

	/* MediaWiki UI */

	'mediawiki.ui' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui/default.less',
			],
		],
	],
	'mediawiki.ui.checkbox' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui.checkbox/checkbox.less',
			],
		],
	],
	'mediawiki.ui.radio' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui.radio/radio.less',
			],
		],
	],
	// Lightweight compatibility module for legacy message box styles
	'mediawiki.legacy.messageBox' => [
		'class' => SkinModule::class,
		'deprecated' => '[1.43] Please use Codex if possible. If styling user generated ' .
			'content, please subscribe to https://phabricator.wikimedia.org/T363607 .',
		'features' => [ 'interface-message-box' ],
	],
	// Lightweight module for button styles
	'mediawiki.ui.button' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui.button/button.less',
			],
		],
	],
	'mediawiki.ui.input' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.ui.input/input.less',
			],
		],
	],
	'mediawiki.ui.icon' => [
		'deprecated' => '[1.41] Please use Codex. See migration guidelines: ' .
			'https://www.mediawiki.org/wiki/Codex/Migrating_from_MediaWiki_UI',
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.ui.icon/icons-2.less',
		],
	],

	'mediawiki.widgets' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.widgets',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.widgets",
		'packageFiles' => [
			'index.js',
			[ 'name' => 'data.json', 'callback' => static function ( Context $context ) {
				// $context only has a language code, we need to look up the language object
				$langCode = $context->getLanguage();
				$services = MediaWikiServices::getInstance();
				$userLang = $services->getLanguageFactory()->getLanguage( $langCode );
				$converter = $services->getLanguageConverterFactory()
					->getLanguageConverter( $services->getContentLanguage() );

				$isContLangVariant = $converter->hasVariant( $langCode );
				$namespaces = $userLang->getFormattedNamespaces();
				if ( $isContLangVariant ) {
					foreach ( $namespaces as $nsId => $_ ) {
						$namespaces[$nsId] = $converter->convertNamespace( $nsId, $langCode );
					}
				}

				return [
					'isContLangVariant' => $isContLangVariant,
					'formattedNamespaces' => $namespaces,
				];
			} ],
			'mw.widgets.NamespaceInputWidget.js',
			'mw.widgets.ComplexNamespaceInputWidget.js',
			'mw.widgets.CopyTextLayout.js',
			'mw.widgets.TitleWidget.js',
			'mw.widgets.TitleInputWidget.js',
			'mw.widgets.TitleSearchWidget.js',
			'mw.widgets.ComplexTitleInputWidget.js',
			'mw.widgets.TitleOptionWidget.js',
		],
		'styles' => [],
		'skinStyles' => [
			'default' => [
				'mw.widgets.TitleWidget.less',
			],
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.widgets.styles',
			// TitleInputWidget
			'oojs-ui.styles.icons-content',
			// CopyTextLayout uses 'copy'
			'oojs-ui.styles.icons-editing-advanced',
			'mediawiki.Title',
			'mediawiki.api',
			'mediawiki.String',
			'mediawiki.language',
		],
		'messages' => [
			// NamespaceInputWidget
			'blanknamespace',
			'namespacesall',
			// CopyTextLayout
			'mw-widgets-copytextlayout-copy-fail',
			'mw-widgets-copytextlayout-copy-success',
			// TitleInputWidget
			'mw-widgets-titleinput-description-new-page',
			'mw-widgets-titleinput-description-redirect',
		],
	],
	'mediawiki.widgets.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.ComplexNamespaceInputWidget.base.less',
				'resources/src/mediawiki.widgets/mw.widgets.ComplexTitleInputWidget.base.less',
			],
		],
	],
	'mediawiki.widgets.AbandonEditDialog' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.AbandonEditDialog.js',
		],
		'messages' => [
			'mw-widgets-abandonedit',
			'mw-widgets-abandonedit-discard',
			'mw-widgets-abandonedit-keep',
			'mw-widgets-abandonedit-title',
		],
		'dependencies' => [
			'oojs-ui-windows',
		],
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
			'oojs-ui.styles.icons-movement',
			'moment',
			'mediawiki.widgets.DateInputWidget.styles',
		],
	],
	'mediawiki.widgets.DateInputWidget.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.DateInputWidget.styles.less',
			],
		],
	],
	'mediawiki.widgets.DateTimeInputWidget.styles' => [
		'skinStyles' => [
			'default' => [
				'resources/src/mediawiki.widgets/mw.widgets.DateTimeInputWidget.styles.less',
			],
		],
	],
	'mediawiki.widgets.visibleLengthLimit' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.widgets.visibleLengthLimit',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.widgets.visibleLengthLimit",
		'packageFiles' => [
			'mediawiki.widgets.visibleLengthLimit.js',
			[
				'name' => 'contentMessages.json',
				'type' => 'data',
				'callback' => static function ( Context $context ) {
					return [
						'colonSeparator' => $context->msg( 'colon-separator' )->inContentLanguage()->text(),
					];
				}
			],
		],
		'dependencies' => [
			'oojs-ui-core',
			'jquery.lengthLimit',
			'mediawiki.language',
			'mediawiki.String',
		],
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
			'mediawiki.util',
			'mediawiki.widgets.DateTimeInputWidget.styles',
			'oojs-ui-core',
			'oojs-ui.styles.icons-moderation',
			'oojs-ui.styles.icons-movement',
			'oojs-ui.styles.icons-interactions',
		],
	],
	'mediawiki.widgets.expiry' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.ExpiryInputWidget.js',
		],
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'moment',
			'mediawiki.widgets.datetime'
		],
		'skinStyles' => [
			'default' => 'resources/src/mediawiki.widgets/mw.widgets.ExpiryInputWidget.less',
		],
	],
	'mediawiki.widgets.CheckMatrixWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CheckMatrixWidget.js',
		],
		'dependencies' => [
			'oojs-ui-core',
		],
	],
	'mediawiki.widgets.CategoryMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.CategoryTagItemWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.CategoryMultiselectWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.api',
			'mediawiki.ForeignApi',
			'mediawiki.Title',
		],
		'messages' => [
			'red-link-title',
			'mw-widgets-categoryselector-add-category-placeholder',
		],
	],
	'mediawiki.widgets.SelectWithInputWidget' => [
		'scripts' => 'resources/src/mediawiki.widgets/mw.widgets.SelectWithInputWidget.js',
		'dependencies' => [
			'mediawiki.widgets.SelectWithInputWidget.styles',
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.SelectWithInputWidget.styles' => [
		'styles' => 'resources/src/mediawiki.widgets/mw.widgets.SelectWithInputWidget.base.less',
	],
	'mediawiki.widgets.SizeFilterWidget' => [
		'scripts' => 'resources/src/mediawiki.widgets/mw.widgets.SizeFilterWidget.js',
		'dependencies' => [
			'mediawiki.widgets.SizeFilterWidget.styles',
			'oojs-ui-widgets',
		],
		'messages' => [
			'minimum-size',
			'maximum-size',
			'pagesize',
		],
	],
	'mediawiki.widgets.SizeFilterWidget.styles' => [
		'styles' => 'resources/src/mediawiki.widgets/mw.widgets.SizeFilterWidget.base.less',
	],
	'mediawiki.widgets.MediaSearch' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.APIResultsProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.APIResultsQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResourceProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaUserUploadsProvider.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResourceQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaUserUploadsQueue.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchWidget.js',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResultWidget.js',
		],
		'styles' => [
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaSearchWidget.less',
			'resources/src/mediawiki.widgets/MediaSearch/mw.widgets.MediaResultWidget.less',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			'mediawiki.ForeignApi',
			'mediawiki.Title',
			'mediawiki.user',
			'mediawiki.util',
		],
		'messages' => [
			'mw-widgets-mediasearch-noresults',
			'mw-widgets-mediasearch-input-placeholder',
			'mw-widgets-mediasearch-results-aria-label',
			'mw-widgets-mediasearch-recent-uploads',
		],
	],
	'mediawiki.widgets.Table' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/Table/mw.widgets.RowWidget.js',
			'resources/src/mediawiki.widgets/Table/mw.widgets.RowWidgetModel.js',
			'resources/src/mediawiki.widgets/Table/mw.widgets.TableWidget.js',
			'resources/src/mediawiki.widgets/Table/mw.widgets.TableWidgetModel.js'
		],
		'styles' => [
			'resources/src/mediawiki.widgets/Table/mw.widgets.RowWidget.less',
			'resources/src/mediawiki.widgets/Table/mw.widgets.TableWidget.less',
		],
		'dependencies' => [
			'oojs-ui-widgets'
		],
		'messages' => [
			'mw-widgets-table-row-delete',
		],
	],
	'mediawiki.widgets.TagMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.TagMultiselectWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.OrderedMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.OrderedMultiselectWidget.js'
		],
		'dependencies' => [
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.MenuTagMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.MenuTagMultiselectWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.UserInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.UserInputWidget.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.UsersMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.UsersMultiselectWidget.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-widgets',
		],
	],
	'mediawiki.widgets.NamespacesMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.NamespacesMultiselectWidget.js',
			'resources/src/mediawiki.widgets/mw.widgets.NamespacesMenuOptionWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
			// FIXME: Only needs NamespaceInputWidget
			'mediawiki.widgets',
		],
	],
	'mediawiki.widgets.TitlesMultiselectWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.TitlesMultiselectWidget.js',
		],
		'dependencies' => [
			'mediawiki.api',
			'oojs-ui-widgets',
			// FIXME: Needs TitleInputWidget only
			'mediawiki.widgets',
		],
	],
	'mediawiki.widgets.TagMultiselectWidget.styles' => [
		'styles' => 'resources/src/mediawiki.widgets/mw.widgets.TagMultiselectWidget.base.css',
	],
	'mediawiki.widgets.SearchInputWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.SearchInputWidget.js',
		],
		'dependencies' => [
			'mediawiki.searchSuggest',
			'oojs-ui.styles.icons-interactions',
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
	],
	'mediawiki.widgets.ToggleSwitchWidget' => [
		'scripts' => [
			'resources/src/mediawiki.widgets/mw.widgets.ToggleSwitchWidget.js',
		],
		'dependencies' => [
			'oojs-ui-widgets',
		],
	],
	'mediawiki.watchstar.widgets' => [
		'localBasePath' => MW_INSTALL_PATH . '/resources/src/mediawiki.watchstar.widgets',
		'remoteBasePath' => "$wgResourceBasePath/resources/src/mediawiki.watchstar.widgets",
		'packageFiles' => [
			'WatchlistExpiryWidget.js',
			[ 'name' => 'data.json', 'callback' => static function ( MessageLocalizer $messageLocalizer ) {
				return WatchAction::getExpiryOptions( $messageLocalizer, false );
			} ]
		],
		'styles' => 'WatchlistExpiryWidget.css',
		'dependencies' => [
			'oojs-ui',
			'mediawiki.api'
		],
		'messages' => [
			'accesskey-ca-watch',
			'addedwatchexpiry-options-label',
			'addedwatchexpirytext',
			'addedwatchexpirytext-talk',
			'addedwatchindefinitelytext',
			'addedwatchindefinitelytext-talk'
		],
	],

	'mediawiki.deflate' => [
		'packageFiles' => [
			'resources/src/mediawiki.deflate/mw.deflate.js',
			[
				'name' => 'resources/lib/pako/pako_deflate.js',
				'callback' => static function ( Context $context, Config $config ) {
					return new FilePath( $context->getDebug() ?
						'resources/lib/pako/pako_deflate.js' :
						'resources/lib/pako/pako_deflate.min.js' );
				}
			],
		],
	],

	/* OOjs */
	'oojs' => [
		'scripts' => [
			'resources/lib/oojs/oojs.js',
			'resources/src/oojs-global.js',
		],
	],

	'mediawiki.router' => [
		'packageFiles' => [
			'resources/src/mediawiki.router/router.js',
		],
		'dependencies' => [
			'oojs',
		],
	],

	/* OOjs UI */

	// Omnibus module.
	'oojs-ui' => [
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets',
			'oojs-ui-toolbars',
			'oojs-ui-windows',
		],
	],

	// The core JavaScript library.
	'oojs-ui-core' => [
		'class' => OOUIFileModule::class,
		'scripts' => [
			'resources/lib/ooui/oojs-ui-core.js',
			'resources/src/ooui-local.js',
		],
		'themeScripts' => 'core',
		'dependencies' => [
			'oojs',
			'oojs-ui-core.styles',
			'oojs-ui-core.icons',
			'oojs-ui.styles.indicators',
			'mediawiki.language',
			'mediawiki.page.ready',
		],
		'messages' => [
			'ooui-field-help',
			'ooui-combobox-button-label',
			'ooui-popup-widget-close-button-aria-label',
			'ooui-selectfile-button-select',
			'ooui-selectfile-button-select-multiple',
			'ooui-selectfile-dragdrop-placeholder',
			'ooui-selectfile-dragdrop-placeholder-multiple',
			'ooui-selectfile-placeholder',
		],
	],
	// This contains only the styles required by core widgets.
	'oojs-ui-core.styles' => [
		'class' => OOUIFileModule::class,
		'styles' => [
			'resources/lib/ooui/wikimedia-ui-base.less', // Providing Wikimedia UI LESS variables to all
		],
		'themeStyles' => 'core',
	],
	'oojs-ui-core.icons' => [
		'class' => OOUIIconPackModule::class,
		'icons' => [
			'add', 'alert', 'infoFilled', 'error', 'check', 'close', 'info', 'search', 'subtract', 'success'
		],
	],
	// Additional widgets and layouts module.
	'oojs-ui-widgets' => [
		'class' => OOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-widgets.js',
		'themeStyles' => 'widgets',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-widgets.icons',
		],
		'messages' => [
			'ooui-copytextlayout-copy',
			'ooui-item-remove',
			'ooui-outline-control-move-down',
			'ooui-outline-control-move-up',
			'ooui-outline-control-remove',
		],
	],
	// You should never directly load this module. The CSS classes it defines are not a public API,
	// they depend on the internal structure of OOUI widgets, which can change at any time. If you
	// find that you need to load this module, you're probably doing something wrong or very hacky.
	'oojs-ui-widgets.styles' => [
		'class' => OOUIFileModule::class,
		'themeStyles' => 'widgets',
	],
	'oojs-ui-widgets.icons' => [
		'class' => OOUIIconPackModule::class,
		// Do not repeat icons already used in 'oojs-ui-core.icons'
		'icons' => [ 'attachment', 'collapse', 'expand', 'trash', 'upload' ],
	],
	// Toolbar and tools module.
	'oojs-ui-toolbars' => [
		'class' => OOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-toolbars.js',
		'themeStyles' => 'toolbars',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-toolbars.icons',
		],
		'messages' => [
			'ooui-toolbar-more',
			'ooui-toolgroup-collapse',
			'ooui-toolgroup-expand',
		],
	],
	'oojs-ui-toolbars.icons' => [
		'class' => OOUIIconPackModule::class,
		// Do not repeat icons already used in 'oojs-ui-core.icons': 'check'
		'icons' => [ 'collapse', 'expand' ],
	],
	// Windows and dialogs module.
	'oojs-ui-windows' => [
		'class' => OOUIFileModule::class,
		'scripts' => 'resources/lib/ooui/oojs-ui-windows.js',
		'themeStyles' => 'windows',
		'dependencies' => [
			'oojs-ui-core',
			'oojs-ui-windows.icons',
		],
		'messages' => [
			'ooui-dialog-message-accept',
			'ooui-dialog-message-reject',
			'ooui-dialog-process-continue',
			'ooui-dialog-process-dismiss',
			'ooui-dialog-process-error',
			'ooui-dialog-process-retry',
		],
	],
	'oojs-ui-windows.icons' => [
		'class' => OOUIIconPackModule::class,
		// Do not repeat icons already used in 'oojs-ui-core.icons': 'close'
		'icons' => [ 'previous' ],
	],

	'oojs-ui.styles.indicators' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'indicators',
	],
	'oojs-ui.styles.icons-accessibility' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-accessibility',
	],
	'oojs-ui.styles.icons-alerts' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-alerts',
	],
	'oojs-ui.styles.icons-content' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-content',
	],
	'oojs-ui.styles.icons-editing-advanced' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-advanced',
	],
	'oojs-ui.styles.icons-editing-citation' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-citation',
	],
	'oojs-ui.styles.icons-editing-core' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-core',
	],
	'oojs-ui.styles.icons-editing-functions' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-functions',
	],
	'oojs-ui.styles.icons-editing-list' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-list',
	],
	'oojs-ui.styles.icons-editing-styling' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-editing-styling',
	],
	'oojs-ui.styles.icons-interactions' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-interactions',
	],
	'oojs-ui.styles.icons-layout' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-layout',
	],
	'oojs-ui.styles.icons-location' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-location',
	],
	'oojs-ui.styles.icons-media' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-media',
	],
	'oojs-ui.styles.icons-moderation' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-moderation',
	],
	'oojs-ui.styles.icons-movement' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-movement',
	],
	'oojs-ui.styles.icons-user' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-user',
	],
	'oojs-ui.styles.icons-wikimedia' => [
		'class' => OOUIImageModule::class,
		'themeImages' => 'icons-wikimedia',
	],
];
