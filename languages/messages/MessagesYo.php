<?php
/** Yoruba (Yorùbá)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Demmy
 * @author Meno25
 * @author Urhixidur
 */

$namespaceNames = array(
	NS_MEDIA            => 'Amóhùnmáwòrán',
	NS_SPECIAL          => 'Pàtàkì',
	NS_TALK             => 'Ọ̀rọ̀',
	NS_USER             => 'Oníṣe',
	NS_USER_TALK        => 'Ọ̀rọ̀_oníṣe',
	NS_PROJECT_TALK     => 'Ọ̀rọ̀_$1',
	NS_FILE             => 'Fáìlì',
	NS_FILE_TALK        => 'Ọ̀rọ̀_fáìlì',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Ọ̀rọ̀_mediaWiki',
	NS_TEMPLATE         => 'Àdàkọ',
	NS_TEMPLATE_TALK    => 'Ọ̀rọ̀_àdàkọ',
	NS_HELP             => 'Ìrànlọ́wọ́',
	NS_HELP_TALK        => 'Ọ̀rọ̀_ìrànlọ́wọ́',
	NS_CATEGORY         => 'Ẹ̀ka',
	NS_CATEGORY_TALK    => 'Ọ̀rọ̀_ẹ̀ka',
);

$namespaceAliases = array(
	'Àwòrán'       => NS_FILE,
	'Ọ̀rọ̀_àwòrán' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'ÌwọléOníse' ),
	'Userlogout'                => array( 'Ìbọ̀sódeOníṣe' ),
	'Preferences'               => array( 'ÀwọnÌfẹ́ràn' ),
	'Recentchanges'             => array( 'ÀwọnÀtúnṣeTuntun' ),
	'Newpages'                  => array( 'ÀwọnOjúewéTuntun' ),
	'Allpages'                  => array( 'GbogboÀwọnOjúewé' ),
	'Specialpages'              => array( 'ÀwọnOjúewéPàtàkì' ),
	'Contributions'             => array( 'ÀwọnÀfikún' ),
	'Categories'                => array( 'ÀwọnẸ̀ka' ),
	'Mypage'                    => array( 'OjúwéMi' ),
	'Mytalk'                    => array( 'Ọ̀rọ̀Mi' ),
	'Mycontributions'           => array( 'ÀwọnÀfikúnMi' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Fàlàsí àwọn ijápọ̀:',
'tog-highlightbroken'         => 'Ṣeonírú ìjápọ̀ jíjá <a href="" class="new">bíi báyìí</a> (ọnà míràn: bíi báyìí<a href="" class="internal">?</a>)',
'tog-justify'                 => "Ṣ'àlàyé gbólóhùn ọ̀rọ̀",
'tog-hideminor'               => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe kékeré nínú àwọn àtúnse tuntun',
'tog-hidepatrolled'           => 'Bo àwọn àtúnṣe síṣọ́ mọ́lẹ̀ nínú àwọn àtúnṣe tuntun',
'tog-newpageshidepatrolled'   => 'Bo àwọn ojúewé síṣọ́ mọ́lẹ̀ kúrò lọ́dọ̀ àkójọ ojúewé tuntun',
'tog-extendwatchlist'         => "Fífẹ̀ ìmójútó láti ṣ'àfihàn gbogbo àtúnṣe tó ṣẹ́lẹ̀, kìí ṣe tuntun nìkan",
'tog-usenewrc'                => 'Lílò áwọn àtúnṣe tuntun fífẹ̀ (JavaScript pọndandan)',
'tog-numberheadings'          => 'Nọmba àwọn àkọlé fúnra wọn',
'tog-showtoolbar'             => 'Ìfihàn pẹpẹ irinṣẹ́ àtúnṣe (JavaScript pọndandan)',
'tog-editondblclick'          => "Ṣ'àtúnṣe àwọn ojúewé ní kíkàn lẹ́mẹjì (JavaScript)",
'tog-editsection'             => 'Jọ̀wọ́ àtúnṣe abala láti inú àwọn ìjápọ̀',
'tog-editsectiononrightclick' => 'Ìgbàláyè àtúnṣe abala nípa klííkì ọ̀tún lórí àkọlé abala (JavaScript pọndandan)',
'tog-showtoc'                 => 'Ìfihàn tábìlì àkóónú (fún àwọn ojúewé tó ní ju orí ọ̀rọ̀ 3 lọ)',
'tog-rememberpassword'        => "Ṣè'rántí àkọọ́lẹ̀ ìwọlé mi lórí agbétàkùn yìí (fún {{PLURAL:$1|ọjọ́|ọjọ́}} $1 pípẹ́jùlọ)",
'tog-watchcreations'          => "Ṣ'àfikún ojúewé tí mo dá mọ́ ìmójútó mi",
'tog-watchdefault'            => "S'àfikún ojúewé tí mo s'àtúnse mọ́ ìmójútó mi",
'tog-watchmoves'              => "S'àfikún ojúewé tí mo yípò mọ́ ìmójútó mi",
'tog-watchdeletion'           => "S'àfikún ojúewé tí mo parẹ́ mọ́ ìmójútó mi",
'tog-minordefault'            => "Se àmì sí gbogbo àtúnse gẹ́gẹ́ bi kékeré lát'ìbẹ̀rẹ̀.",
'tog-previewontop'            => "Se àyẹ̀wò kí ẹ tó s'àtúnṣe",
'tog-previewonfirst'          => "S'àfihàn àgbéwò fún àtúnse àkọ́kọ́",
'tog-nocache'                 => 'Ìdínà fífi ojúewé pamọ́ sínú cache',
'tog-enotifwatchlistpages'    => 'Fi e-mail ránṣẹ́ sími tí ojúewé tí mò ún mójútó bá yípadà',
'tog-enotifusertalkpages'     => 'Fi e-mail ránṣẹ́ sími tí ojúewé oníṣe mi bá yípadà',
'tog-enotifminoredits'        => 'Fi e-mail ránṣẹ́ sími bákannà fún àtúnṣe kékékèé sí ojúewé',
'tog-enotifrevealaddr'        => "Ṣ'àfihàn àdírẹ́ẹ̀sì e-mail mi nínú àwọn ìránṣẹ́ e-mail",
'tog-shownumberswatching'     => "S'àfihàn iye àwọn oníṣe tí wọn tẹjú mọ́ọ",
'tog-oldsig'                  => 'Ìgbéwò ìtọwọ́bọ̀wé tó wà:',
'tog-fancysig'                => 'Ṣe ìtọwọ́bọ̀wé bíi ìkọ wiki (láìní ìjápọ̀ fúnrararẹ̀)',
'tog-externaleditor'          => 'Lo aláàtúnṣe ọ̀tọ̀ látìbẹ̀rẹ̀ (fún àwọn tó mọ̀ nìkan, ìtò ọ̀tọ̀ọ̀tọ̀ pọndandan lórí kọ̀mpútà yín. [http://www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'Lo awoìyàtò ọ̀tọ̀ látìbẹ̀rẹ̀ (fún àwọn tó mọ̀ nìkan, ìtò ọ̀tọ̀ọ̀tọ̀ pọndandan lórí kọ̀mpútà yín. [http://www.mediawiki.org/wiki/Manual:External_editors Ìfọ̀rọ̀tónilétí mìhínhìn.])',
'tog-showjumplinks'           => 'Ìgbàláyè "fò lọ sí" àwọn ìjápọ̀ ìṣeégbà',
'tog-uselivepreview'          => 'Ìlo àkọ́kọ́yẹ̀wò lẹ́ṣẹ̀kẹṣẹ̀ (JavaScript pọndandan) (aládànhánwò)',
'tog-forceeditsummary'        => 'Kìlọ̀ fún mi tí àkótán àtúnṣe bá jẹ́ òfo',
'tog-watchlisthideown'        => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe mi nínú ìmójútó',
'tog-watchlisthidebots'       => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe bot nínú ìmójútó',
'tog-watchlisthideminor'      => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe kéékèké nínú ìmójútó',
'tog-watchlisthideliu'        => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe àwọn oníṣe tó ti wọlé nínú ìmójútó',
'tog-watchlisthideanons'      => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe àwọn oníṣe aláìlórúkọ nínú ìmójútó',
'tog-watchlisthidepatrolled'  => 'Ìbòmọ́lẹ̀ àwọn àtúnṣe olùṣọ́ lọ́wọ́ ìmójútó',
'tog-ccmeonemails'            => 'Ìfiránṣẹ́ sími àwọn àwòkọ àwọn e-mail tí mo firánṣẹ́ sí àwọn oníṣe míràn',
'tog-diffonly'                => 'Kò gbọdọ̀ ṣàfihàn àkóónú ojúewé lábẹ́ àwọn ìyàtọ̀',
'tog-showhiddencats'          => "Ṣ'àfihàn àwọn ẹ̀ka pípamọ́",

'underline-always'  => 'Nígbà gbogbo',
'underline-never'   => 'Rárá',
'underline-default' => 'Ti agbétàkùn',

# Font style option in Special:Preferences
'editfont-style'     => 'Oge fọ́ntì ààlà àtúnṣe:',
'editfont-default'   => 'Ti agbétàkùn',
'editfont-monospace' => 'Fọ́ntì oníààyè kan',
'editfont-sansserif' => 'Fọnti san-sẹrif',
'editfont-serif'     => 'Fọnti sẹrif',

# Dates
'sunday'        => 'Ọjọ́àìkú',
'monday'        => 'Ọjọ́ajé',
'tuesday'       => 'Ọjọ́ìsẹ́gun',
'wednesday'     => 'Ọjọ́rú',
'thursday'      => 'Ọjọ́bọ̀',
'friday'        => 'Ọjọ́ẹtì',
'saturday'      => 'Ọjọ́àbámẹ́ta',
'sun'           => 'Àìkú',
'mon'           => 'Ajé',
'tue'           => 'Ìṣẹ́gun',
'wed'           => 'Rú',
'thu'           => 'Bọ̀',
'fri'           => 'Ẹtì',
'sat'           => 'Àbámẹ́ta',
'january'       => 'Oṣù Kínní',
'february'      => 'Oṣù Kejì',
'march'         => 'Oṣù Kẹta',
'april'         => 'Oṣù Kẹrin',
'may_long'      => 'Oṣù Kàrún',
'june'          => 'Oṣù Kẹfà',
'july'          => 'Oṣù Keje',
'august'        => 'Oṣù Kẹjọ',
'september'     => 'Oṣù Kẹ̀sán',
'october'       => 'Oṣù Kẹ̀wá',
'november'      => 'Oṣù Kọkànlá',
'december'      => 'Oṣù Kejìlá',
'january-gen'   => 'Oṣù Kínní',
'february-gen'  => 'Oṣù Kejì',
'march-gen'     => 'Oṣù Kẹta',
'april-gen'     => 'Oṣù Kẹrin',
'may-gen'       => 'Oṣù Kàrún',
'june-gen'      => 'Oṣù Kẹfà',
'july-gen'      => 'Oṣù Keje',
'august-gen'    => 'Oṣù Kẹjọ',
'september-gen' => 'Oṣù Kẹ̀sán',
'october-gen'   => 'Oṣù Kẹ̀wá',
'november-gen'  => 'Oṣù Kọkànlá',
'december-gen'  => 'Oṣù Kejìlá',
'jan'           => 'Oṣù 1',
'feb'           => 'Oṣù 2',
'mar'           => 'Oṣù 3',
'apr'           => 'Oṣù 4',
'may'           => 'Oṣù 5',
'jun'           => 'Oṣù 6',
'jul'           => 'Oṣù 7',
'aug'           => 'Oṣù 8',
'sep'           => 'Oṣù 9',
'oct'           => 'Oṣù 10',
'nov'           => 'Oṣù 11',
'dec'           => 'Oṣù 12',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Ẹ̀ka|Àwọn ẹ̀ka}}',
'category_header'                => 'Àwọn ojúewé nínú ẹ̀ka "$1"',
'subcategories'                  => 'Àwọn ẹ̀ka abẹ́',
'category-media-header'          => 'Amóunmáwòrán nínú ẹ̀ka "$1"',
'category-empty'                 => "''Lọ́wọ́lọ́wọ́ ẹ̀ka yìí kò ní ojúewé tàbí amóhùnmáwòrán kankan.''",
'hidden-categories'              => '{{PLURAL:$1|Ẹ̀ka bíbòmọ́lẹ̀|Áwọn ẹ̀ka bíbòmọ́lẹ̀}}',
'hidden-category-category'       => 'Àwọn ẹ̀ka ìbòmọ́lẹ̀',
'category-subcat-count'          => '{{PLURAL:$2|Ẹ̀ka yìí ní ẹ̀kà abẹ́ ìsàlẹ̀ yìí nìkan|Ẹ̀ka yìí ní {{PLURAL:$1|ẹ̀kà abẹ́ ìsàlẹ̀ yìí|àwọn ẹ̀kà abẹ́ $1 ìsàlẹ̀ wọ̀nyí}}, nínú àpapọ̀ $2.}}',
'category-subcat-count-limited'  => 'Ẹ̀ka yìí ní {{PLURAL:$1|ẹ̀kà abẹ́ yìí|àwọn ẹ̀kà abẹ́ $1 wọ̀nyí}}.',
'category-article-count'         => '{{PLURAL:$2|Ẹ̀ka yìí ní ojúewé kan péré.|{{PLURAL:$1|Ojúewé kan yìí nìkan|Àwọn ojúewé $1 yìí}} lówà nínú èka yìí, nínú àpapọ̀ $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Ojùewé ìsàlẹ̀ yìí|Àwọn ojúewé $1 ìsàlẹ̀ wọ̀nyí}} lówà nínú ẹ̀ka yìí.',
'category-file-count'            => '{{PLURAL:$2|Ẹ̀ka yìí ní fáìlì ìsàlẹ̀ yìí nìkan. |{{PLURAL:$1|Fáìlì ìsàlẹ̀ yìí|Àwọn fáìlì $1 ìsàlẹ̀ yìí ni wọ́n}} wà nínú ẹ̀ka yìí, nínú àpapọ̀ iye $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Fáìlì yìí|Àwọn fáìlì $1 yìí}} wà nìnú ẹ̀ka yìí.',
'listingcontinuesabbrev'         => 'tẹ̀síwájú',
'index-category'                 => 'Àwọn ojúewé títọ́kasí',
'noindex-category'               => 'Àwọn ojúewé àìjẹ́ títọ́kasí',

'mainpagetext'      => "'''MediaWiki ti jẹ́ gbígbékọ́sínú láyọrísírere.'''",
'mainpagedocfooter' => "Ẹ ṣàbẹ̀wò sí [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] fún ìfitólétí nípa líló atòlànà wíkì.

== Láti bẹ̀rẹ̀ ==
*  [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Nípa',
'article'       => 'Ojúewé àkóónú',
'newwindow'     => '(yíò sí nínú fèrèsè tuntun)',
'cancel'        => 'Fagilé',
'moredotdotdot' => 'Ẹ̀kúnrẹ́rẹ́...',
'mypage'        => 'Ojúewé mi',
'mytalk'        => 'Ọ̀rọ̀ mi',
'anontalk'      => 'Ọ̀rọ̀ fún IP yí',
'navigation'    => 'Atọ́ka',
'and'           => '&#32;àti',

# Cologne Blue skin
'qbfind'         => 'Wíwárí',
'qbbrowse'       => 'Ìṣíwò',
'qbedit'         => 'Àtúnṣe',
'qbpageoptions'  => 'Ojúewé yi',
'qbmyoptions'    => 'Àwọn ojúewé mi',
'qbspecialpages' => 'Àwọn ojúewé pàtàkì',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Àfikún orí-ọ̀rọ̀',
'vector-action-delete'     => 'Ìparẹ́',
'vector-action-move'       => 'Ìyípòdà',
'vector-action-protect'    => 'Àbò',
'vector-action-undelete'   => 'Ìmúkúrò ìparẹ́',
'vector-action-unprotect'  => 'Ìjáwọ́ àbò',
'vector-view-create'       => "Ṣ'èdá",
'vector-view-edit'         => 'Àtúnṣe',
'vector-view-history'      => 'Wo ìtàn',
'vector-view-view'         => 'Kíkà',
'vector-view-viewsource'   => 'Wo àmìọ̀rọ̀',
'actions'                  => 'Àwọn ìgbéṣe',
'namespaces'               => 'Àwọn orúkọàyè',

'errorpagetitle'    => 'Àsìṣe',
'returnto'          => 'Padà sí $1.',
'tagline'           => "Lát'ọwọ́ {{SITENAME}}",
'help'              => 'Ìrànlọ́wọ́',
'search'            => 'Àwárí',
'searchbutton'      => 'Àwárí',
'go'                => 'Rìnsó',
'searcharticle'     => 'Lọ',
'history'           => 'Ìtàn ojúewé',
'history_short'     => '
Ìtàn',
'updatedmarker'     => 'jẹ́ títúnṣe lẹ́yìn àbẹ̀wò mi',
'info_short'        => 'Ìfitọ́nilétí',
'printableversion'  => 'Àtẹ̀jáde tóṣeétẹ̀síìwé',
'permalink'         => 'Ìjápọ̀ tíkòníyípadà',
'print'             => 'Ìtẹ̀síìwé',
'view'              => 'Ìwòran',
'edit'              => 'Àtúnṣe',
'create'            => 'Ṣèdá',
'editthispage'      => "S'àtúnṣe ojúewé yi",
'create-this-page'  => "Ṣè'dá ojúewé yìí",
'delete'            => 'Ìparẹ́',
'deletethispage'    => 'Pa ojúewé yi rẹ́',
'undelete_short'    => 'Ìdápadà ìparẹ́ {{PLURAL:$1|àtúnṣe kan|àwọn àtúnṣe $1}}',
'viewdeleted_short' => 'Ìwòran {{PLURAL:$1|àtúnṣe ajẹ́píparẹ́ kan|àwọn àtúnṣe ajẹ́píparẹ́ $1}}',
'protect'           => 'Àbò',
'protect_change'    => 'yípadà',
'protectthispage'   => 'Dá àbò bo ojúewé yìí',
'unprotect'         => 'Mú àbò kúrò',
'unprotectthispage' => 'Mú àbò kúrò lórí ojúewé yìí',
'newpage'           => 'Ojúewé tuntun',
'talkpage'          => 'Ìfọ̀rọ̀wérọ̀ nípa ojúewé yìí',
'talkpagelinktext'  => 'Ọ̀rọ̀',
'specialpage'       => 'Ojúewé Pàtàkì',
'personaltools'     => 'Àwọn irinṣẹ́ àdáni',
'postcomment'       => 'Abala tuntun',
'articlepage'       => 'Ìfihàn àkóónú ojúewé',
'talk'              => 'Ìfọ̀rọ̀wérọ̀',
'views'             => 'Àwọn ìfihàn',
'toolbox'           => 'Àpótí irinṣẹ',
'userpage'          => 'Wo ojúewé oníṣe',
'projectpage'       => 'Wo ojúewé iṣẹ́ọwọ́',
'imagepage'         => 'Wo ojúewé fáìlì',
'mediawikipage'     => 'Wo ojúewé ìránṣẹ́',
'templatepage'      => 'Wo ojúewé àdàkọ',
'viewhelppage'      => 'Wo ojúewé ìrànlọ́wọ́',
'categorypage'      => 'Wo ojúewé ẹ̀ka',
'viewtalkpage'      => 'Wo ìfọ̀rọ̀wérọ̀',
'otherlanguages'    => 'Àwọn èdè míràn',
'redirectedfrom'    => '(Àtúnjúwe láti $1)',
'redirectpagesub'   => 'Ojúewé àtúnjúwe',
'lastmodifiedat'    => 'Àtunṣe ojúewé yi gbẹ̀yìn wáyé ni ago $2, ọjọ́ọdún $1.',
'viewcount'         => 'A ti wo ojúewé yi ni {{PLURAL:$1|ẹ̀kan péré|iye ìgbà $1}}.',
'protectedpage'     => 'Ojúewé ajẹ́dídáàbòbò',
'jumpto'            => 'Lọ sí:',
'jumptonavigation'  => 'atọ́ka',
'jumptosearch'      => 'àwárí',
'view-pool-error'   => 'Àforíjì, ẹ̀rọ ìwọ̀fà ti kún lọ́wọ́ báyìí.
Ọ̀pọ̀lọpọ̀ àwọn oníṣe úngbìyànjú láti wo ojúewé yìí.
Ẹ jọ̀wọ́ ẹ dúro ná díẹ̀ kí ẹ tó tún gbìyànjú láti wo ojúewé yìí.

$1',
'pool-errorunknown' => 'Àsìṣe àwámárìdí',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Nípa {{SITENAME}}',
'aboutpage'            => 'Project:Nípa',
'copyright'            => 'Gbogbo ohun inú ibí yìí wà lábẹ́  $1.',
'copyrightpage'        => '{{ns:project}}:Ẹ̀tọ́àwòko',
'currentevents'        => 'Ìṣẹ̀lẹ̀ lọ́wọ́lọ́wọ́',
'currentevents-url'    => 'Project:Ìṣẹ̀lẹ̀ lọ́wọ́lọ́wọ́',
'disclaimers'          => 'Ikìlọ̀',
'disclaimerpage'       => 'Project:Ìkìlọ̀ gbogbo',
'edithelp'             => 'Ìrànlọ́wọ́ fún àtúnṣe',
'edithelppage'         => 'Help:Àtúnṣe',
'helppage'             => 'Help:Àwon àkóónú',
'mainpage'             => 'Ojúewé Àkọ́kọ́',
'mainpage-description' => 'Ojúewé Àkọ́kọ́',
'policy-url'           => 'Project:Ìpinu',
'portal'               => 'Èbúté àwùjọ',
'portal-url'           => 'Project:Èbúté Àwùjọ',
'privacy'              => 'Ètò àbò',
'privacypage'          => 'Project:Ètò àbò',

'badaccess'        => 'Àṣìṣe ìyọ̀nda',
'badaccess-group0' => "A kò gbàyín l'áyè l'áti ṣe ohun tí ẹ bèrè fún.",
'badaccess-groups' => 'Ohun tí ẹ bèèrè fún wà fún àwọn oníṣe {{PLURAL:$2|inú ẹgbẹ́ yìí|inú ikan nínú àwọn ẹgbẹ́ yìí}}: $1.',

'versionrequired'     => 'Àtẹ̀jáde $1 ti MediaWiki ṣe dandan',
'versionrequiredtext' => 'Àtẹ̀jáde $1 ti MediaWiki ṣe dandan láti lo ojúewé yìí.
Ẹ wo [[Special:Version|ojúewé àtẹ̀jáde]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Jẹ́ kíkójáde láti "$1"',
'youhavenewmessages'      => 'Ẹ ní $1 ($2).',
'newmessageslink'         => 'ìránṣẹ́ tuntun',
'newmessagesdifflink'     => 'àtúnṣe tógbẹ̀yìn',
'youhavenewmessagesmulti' => 'Ẹ ní ìránsẹ́ tuntun ni $1',
'editsection'             => 'àtúnṣe',
'editold'                 => 'àtúnṣe',
'viewsourceold'           => 'wo àmìọ̀rọ̀',
'editlink'                => 'àtúnṣe',
'viewsourcelink'          => 'wo àmìọ̀rọ̀',
'editsectionhint'         => 'Àtúnṣe abala: $1',
'toc'                     => 'Àwọn àkóónú',
'showtoc'                 => 'fihàn',
'hidetoc'                 => 'bòmọ́lẹ̀',
'collapsible-collapse'    => 'Kálura',
'collapsible-expand'      => 'Fẹ̀hàn',
'thisisdeleted'           => 'Ìfihàn tàbí ìmúpadà $1?',
'viewdeleted'             => 'Ẹ wo $1?',
'restorelink'             => '{{PLURAL:$1|àtúnṣe ajẹ́píparẹ́ kan|àwọn àtúnṣe ajẹ́píparẹ́ $1}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'red-link-title'          => '$1 (kò sí ojúewé yìí)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Ojúewé',
'nstab-user'      => 'Ojúewé oníṣe',
'nstab-media'     => 'Ojúewé amóhùnmáwòrán',
'nstab-special'   => 'Ojúewé pàtàkì',
'nstab-project'   => 'Ojúewé iṣẹ́ọwọ́',
'nstab-image'     => 'Fáìlì',
'nstab-mediawiki' => 'Ìránṣẹ́',
'nstab-template'  => 'Àdàkọ',
'nstab-help'      => 'Ojúewé ìrànlọ́wọ́',
'nstab-category'  => 'Ẹ̀ka',

# Main script and global functions
'nosuchaction'      => 'Kò sí irú ìgbéṣe báun',
'nosuchactiontext'  => 'Ìgbéṣe tí URL yìí tọ́kasí kò tọ́.
Ó ṣe é ṣe kó jẹ́ pé ẹ ṣe àṣìṣe URL ọ̀hún, tàbí kó jẹ́ pé ẹ tẹ̀lé ìjápọ̀ tí kò tọ́.
Ó sì le jẹ́ pé kòkòrò wà nínú software tí {{SITENAME}} nlò.',
'nosuchspecialpage' => 'Kò sí irú ojúewé pàtàkì báun',
'nospecialpagetext' => '<strong>Ẹ tọrọ ojúewé pàtàkì tíkòtọ́.</strong>

Àkójọ àwọn ojúewé pàtàkì títọ́ wà ní [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Àsìṣe',
'databaseerror'        => 'Àsìṣe ibùdó dátà',
'laggedslavemode'      => "'''Ìkìlọ̀:''' Ojúewé náà le mọ́ nìí àwọn àtúnṣe tuntun.",
'readonly'             => 'Títìpa ibùdó dátà',
'enterlockreason'      => 'Ẹ ṣàlàyé ìtìpa náà, àti ìgbàtí ẹ rò pé ìtìpa náà yíò kúrò.',
'readonlytext'         => 'Ibùdó dátà jẹ́ títìpa sí àwọn ìkówọlé tuntun àti sí àwọn àtúnṣe míràn, bóyá fún ìtọ́jú ibùdó dátà gbogbo ìgbà, lẹ́yìn èyí yíò padà sí ní ṣiṣẹ́.

Olùmójútó tó tìípa ṣe àlàyé yìí: $1',
'missing-article'      => 'Ibùdó dátà kò rí ìkọ̀wé fún ojúewé kan tóyẹ kí ó rí, pẹ̀lú orúkọ "$1" $2.

Ohun tó ún fa èyí ní ìtẹ̀lé ìjapọ̀ "ìyàtọ́" tótipẹ́ tàbí ìjápọ̀ ìtàn ojúewé tí a ti parẹ́.

Tí kì bá ṣe bẹ́ẹ̀, ó lè jẹ́ pé ẹ ti rí àsìṣe nínú atòlànà kọ̀mpútà náà.
Ẹjọ̀wọ́ ẹ fi èyí tó [[Special:ListUsers/sysop|alámùójútó]] kan létí, kí ẹ sí mọ́ gbàgbé láti fúun ní URL ọ̀hún.',
'missingarticle-rev'   => '(àtúnyẹ̀wò#: $1)',
'missingarticle-diff'  => '(Ìyàtọ̀: $1, $2)',
'readonly_lag'         => 'Ibùdó dátà ti jẹ́ títìpa fúnrararẹ̀ kí àwọn ẹ̀rọ awọ̀fà ẹrú ibùdó dátà le baà yára bíi ti àwọn ẹ̀rọ awọ̀fà ọ̀gà.',
'internalerror'        => 'Àsìṣe inú',
'internalerror_info'   => 'Àsìṣe inú: $1',
'fileappenderrorread'  => '"$1" kò ṣe é kà lásìkò ìlẹ̀mọ́.',
'fileappenderror'      => 'Kò le so "$1" pọ̀ mọ́ "$2".',
'filecopyerror'        => 'Àwòkọ faili "$1" sí "$2" kò ṣe é ṣe.',
'filerenameerror'      => 'Àtúnsọlórúkọ fáìlì "$1" sí "$2" kò ṣe é ṣe.',
'filedeleteerror'      => 'Ìparẹ́ fáìlì "$1" kò ṣe é ṣe.',
'directorycreateerror' => 'Kò le dá àpò "$1".',
'filenotfound'         => 'Kò sí fáìlì "$1".',
'fileexistserror'      => 'Ìṣòro kíkọ sí inú fáìlì "$1": fáìlì ọ̀hún wà',
'unexpected'           => 'Iye àìretí: "$1"="$2".',
'formerror'            => 'Àsìṣe: fọ́ọ̀mù kò ṣe fi ránṣẹ́',
'badarticleerror'      => 'Ìgbéṣẹ̀ yìí kò ṣe é ṣe lórí ojúewé yìí.',
'cannotdelete'         => 'Ojúewé tàbí fáìlì "$1" kò ṣe é parẹ́.
Oníṣe mìíràn le ti paárẹ́.',
'badtitle'             => 'Àkọ́lé búrurú',
'badtitletext'         => 'Àkọlé ojúewé tí ẹ bèrè fún kò ní ìbáramu, jẹ́ òfo, tàbí áṣìṣe wà nínú ìjápọ̀ àkọlé láàrin èdè tàbí láàrin wiki.
Ó ṣe é ṣe kó jẹ́pé ó ní ìkan tàbí ọ̀pọ̀ àmi-lẹ́tà tí kò ṣe é lò nínú àkọlé.',
'perfcached'           => 'Ìwònyí jẹ́ dátà láti inú cache nítoríẹ̀ ó le mọ́ jẹ̀ẹ́ tuntun.',
'perfcachedts'         => 'Ìwònyí jẹ́ ìpèsè láti inú cache, ọjọ́ tí a ṣe àtúnṣe rẹ̀ gbẹ̀yìn ni $1.',
'querypage-no-updates' => 'Àtúnṣe sí ojúewé yìí kò ṣe é ṣe lọ́wọ́lọ́wọ́.
Àwọn ìpèsè tuntun kò ní hàn báyìí ná.',
'wrong_wfQuery_params' => 'Àwọn pàrámítà àìtọ́ sí wfQuery()<br />
Ìlò: $1<br />
Ìtọrọ: $2',
'viewsource'           => 'Wo àmìọ̀rọ̀',
'viewsourcefor'        => 'fún $1',
'actionthrottled'      => 'Ìgbése bíntín',
'actionthrottledtext'  => 'Láti dènà spam, ìgbése yìí kò ní ṣe é ṣe lọ́nà iye púpọ̀ láàrin àsìkò bíntín, ẹ̀yin sì ti kọjá iye náà.
Ẹjọ̀wọ́ ẹ gbíyànjú síi ní ìsẹ́jú díẹ̀.',
'protectedpagetext'    => 'Ojúewé yìí tijẹ́ títìpa. Ẹ kò le se àtúnṣe.',
'viewsourcetext'       => 'Ẹ lè wo ati ẹ lè se àwòkọ ọ̀rọ̀àmì ojúewé yi:',
'protectedinterface'   => 'Ojúewé yìí n pèsè ìfojúkojú ìkọ̀wé fún software, a ti dínà si láti mọ́ gba ìlòkulò ní ààyè.',
'editinginterface'     => "'''Ìkìlọ̀:''' Ẹ ún ṣàtúnṣe ojúewé tó jẹ́ lílò láti pèsè ìkọ ìfojúkojú fún àtòlànà kọ̀mpútà.
Àwọn ìyípadà sí ojúewé yìí yíò kan ìhànsí ìfojúkojú oníṣe fún àwọn oníṣe míràn.
Fún ìyédèpadà, ẹ jọ̀wọ́ ẹ lo [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], iṣẹ́-ọwọ́ ìṣọdìbílẹ̀ MediaWiki.",
'sqlhidden'            => '(bíbòmọ́lẹ̀ ìbéèrè SQL)',
'namespaceprotected'   => "A kò gbàyín ní ààyè láti ṣ'àtúnṣe àwọn ojúewé tó wà nínú orúkọàyè '''$1'''.",
'customcssjsprotected' => 'Ẹ kò ní ìyọ̀nda láti ṣàtúnṣe ojúewé yìí nítorípé ó ní ìtòjọ oníṣe ẹlòmíràn.',
'ns-specialprotected'  => 'Àtúnṣe kò ṣe é ṣe sí àwọn ojúewé pàtàkì.',
'titleprotected'       => "[[User:$1|$1]] ti dínà sí dídá àkọlé yìí. Àlàyé rẹ̀ ni pí ''$2''.",

# Virus scanner
'virus-scanfailed'     => 'ìkúnà scan (àmìọ̀rọ̀ $1)',
'virus-unknownscanner' => 'ògùn-kòkòrò àìmọ̀:',

# Login and logout pages
'logouttext'                 => "'''Ẹ ti bọ́sọ́de.'''

Ẹ le tẹ̀síwájú sí ní lo {{SITENAME}} láìmorúkọ yín, tàbí kí ẹ [[Special:UserLogin|padà wọlé]] bí ẹnikanan tàbí ẹlòmíràn.
Àkíyèsí wípé àwọn ojúewé kan le hàn b'ígbà tójẹ́pé ẹ sì wọlé títí tí ẹ ó fi jọ̀wọ́ cache browser yín.",
'welcomecreation'            => "== Ẹ kú àbọ̀, $1! ==

A ti ṣ'èdá àpamọ́ yín.
Ẹ mọ́ gbàgbé l'áti ṣ'àtúnṣe àwọn [[Special:Preferences|{{SITENAME}} ìfẹ́ràn]] yín.",
'yourname'                   => 'Orúkọ oníṣe (username):',
'yourpassword'               => 'Ọ̀rọ̀ìpamọ́:',
'yourpasswordagain'          => 'Kọ ọ̀rọ̀ìpamọ́ lẹ́ẹ̀kansí:',
'remembermypassword'         => "Ṣè'rántí ìwọlé mi lórí kọ̀mpútà yìí (fún ó pẹ́ jù {{PLURAL:$1|ọjọ́|ọjọ́}} $1)",
'securelogin-stick-https'    => 'Ìwàní sísopọ̀ mọ́ HTTPS lẹ́yín ìwọlé',
'yourdomainname'             => 'Domain yín:',
'externaldberror'            => 'Bóyá àsìṣe ìfidájú ibùdó dátà ló ṣẹlẹ̀ tàbí ẹ kò jẹ́ gbígbà ní ààyè láti sọ àpamọ́ òde yín di ọ̀tun.',
'login'                      => 'Ìwọlé',
'nav-login-createaccount'    => 'Ìwọlé / Ìforúkọ sílẹ̀',
'loginprompt'                => 'Ẹ gbọ́dọ̀ jọ̀wọ́ cookies láti wọlé sí {{SITENAME}}.',
'userlogin'                  => 'Ìwọlé / ìforúkọ sílẹ̀',
'userloginnocreate'          => 'Ìwọlé',
'logout'                     => 'Ìbọ́sóde',
'userlogout'                 => 'Ìbọ́sóde',
'notloggedin'                => "Ẹ kò tí w'ọlé",
'nologin'                    => "Ṣé ẹ fẹ́ wọlé? '''$1'''.",
'nologinlink'                => 'Ìforúkọsílẹ̀',
'createaccount'              => 'Ẹ fi orúkọ sílẹ̀',
'gotaccount'                 => "Ṣé ẹ ti ní àpamọ́ tẹ́lẹ̀? '''$1'''.",
'gotaccountlink'             => "Ẹ w'ọlé",
'createaccountmail'          => 'pẹ̀lú e-mail',
'createaccountreason'        => 'Ìdíẹ̀:',
'badretype'                  => 'Àwọn ọ̀rọ̀ìpamọ́ tí ẹ kọ kò jọ ra wọn.',
'userexists'                 => 'Orúkọ oníṣe tí ẹ mú jẹ́ ti ẹlòmíràn.
Ẹjọ̀wọ́ ẹ yan orúkọ mìíràn tó yàtọ̀.',
'loginerror'                 => 'Àsìṣe ìwọlé',
'createaccounterror'         => 'Kò le dá àkópamọ́: $1',
'nocookiesnew'               => 'A ti dá àpamọ́ oníṣe, ṣugbọ́n ẹ kò tíì wọlé.
{{SITENAME}} ún lo cookies láti gba àwọn oníṣe wọlé.
Ẹ ti dínà sí cookies.
Ẹjọ̀wọ́ ẹ fún cookies láàyè kí ẹ tó wọlé pẹ̀lú orúkọ oníṣe àti ọ̀rọ̀ìpamọ́ tuntun yín.',
'nocookieslogin'             => '{{SITENAME}} ún lo cookies láti gba àwọn oníṣe wọlé.
Ẹ ti dínà sí cookies.
Ẹjọ̀wọ́ ẹ fún cookies láàyè kí ẹ tún tó gbìyànjú láti wọlé.',
'noname'                     => 'Ẹ kò tọ́kasí orúkọ oníṣe tó ní ìbámu.',
'loginsuccesstitle'          => 'Ìwọlé ti yọrí sí rere',
'loginsuccess'               => "'''Ẹ ti wọlé sínú {{SITENAME}} gẹ́gẹ́ bi \"\$1\".'''",
'nosuchuser'                 => 'Kò sí oníṣe kankan pẹ̀lú orúkọ "$1".
Àwọn lẹ́tà àwọn orúkọ oníṣe gbọ́dọ̀ jẹ́ irúkanna.
Ẹ yẹ lẹ́tà yín wò, tàbí [[Special:UserLogin/signup|kí ẹ dá àkópamọ́ tuntun]].',
'nosuchusershort'            => "Kò sí oníṣe t'ón jẹ́ <nowiki>$1</nowiki>.
Ẹ yẹ lẹ́tà ọ̀rọ̀ yín wò.",
'nouserspecified'            => 'Ẹ gbọ́dọ̀ tọ́kasí orúkọ oníṣe kan.',
'login-userblocked'          => 'Oníṣe yìí jẹ́ dídínà. Ìwọlé kò jẹ́ gbígbà láyè.',
'wrongpassword'              => 'Ọ̀rọ̀ìpamọ́ tí ẹ kìbọlé kòtọ́.
Ẹ jọ̀wọ́ ẹ gbìyànjú lẹ́ẹ̀kansí.',
'wrongpasswordempty'         => 'Ọ̀ròìpamọ́ jẹ́ òfo.
Ẹ gbìyànjú lẹ́ ẹ̀kan síi.',
'passwordtooshort'           => 'Ọ̀rọ̀ìpamọ́ kò gbọ́dọ̀ dín ju {{PLURAL:$1|àmìlẹ́tà kan|àmìlẹ́tà $1}} lọ.',
'password-name-match'        => 'Ọ̀rọ̀ìpamọ́ yín gbọ́dọ̀ yàtọ̀ sí orúkọ oníṣe yín.',
'mailmypassword'             => 'Ìfiránṣẹ́ ọ̀rọ̀ìpamọ́ tuntun',
'passwordremindertitle'      => 'Ọ̀rọ̀ìpamọ́ tuntun fún ìgbà díẹ̀ fún {{SITENAME}}',
'passwordremindertext'       => 'Ẹnìkan (ó ṣe é ṣe kó jẹ́ ẹ̀yin gan, láti àdírẹ́ẹ̀sì IP $1) bèrè fún
ọ̀rọ̀ìpamọ́ tuntun fùn {{SITENAME}} ($4). A ti ṣ\'èdá ọ̀rọ̀ìpamọ́ ìgbádíẹ̀ fún
oníṣe "$2" bẹ́ ẹ̀ sì ni a ti ṣ\'ètò rẹ̀ sí "$3". Tó bá jẹ́ pé èrò yín nuhun, ẹ gbúdọ̀ wọlé
kí ẹ yan ọ̀rọ̀ìpamọ́ tuntun ní ìsinsìnyí. Ọ̀rọ̀ìpamọ́ ìgbàdíẹ̀ yín yíò parí lẹ́yìn ọjọ́ {{PLURAL:$5|kan|$5}}.

Tó bá jẹ́ pé ẹlòmíràn ni ò ṣe ìtọrọ yìí, tábí pé ẹ ti rántí ọ̀rọ̀ìpamọ́ yín,
tí ẹ kò sì fẹ́ yípadà mọ́, ẹ mọ́ kọbiara sí ìránṣẹ́ yìí.',
'noemail'                    => 'Kò sí àkọsílẹ̀ àdírẹ́ẹ̀sì e-mail fún oníṣe "$1".',
'noemailcreate'              => 'Ẹ gbọ́dọ̀ pèsè àdírẹ́ẹ̀sì e-mail títọ́',
'passwordsent'               => 'A ti fi ọ̀rọ̀ìpamọ́ tuntun ránṣẹ́ sí ojúọ̀nà e-mail tí a fisílẹ̀ fún "$1".
Ẹ jọ̀wọ́ ẹ padà wọlé tí ẹ bá ti gbàá.',
'blocked-mailpassword'       => 'Àdírẹ́sì IP yín jẹ́ dídèlọ́nà láti ṣàtúnṣe, nípa báyìí kò ní ààyè láti lo ìfigbéṣe ìtúnwárí ọ̀rọ̀ìpamọ́ kó le dínà ìbàjẹ́.',
'eauthentsent'               => 'A ti fi e-mail ìmúdájú ránṣẹ́ sí àdírẹ́ẹ̀sì e-mail tí ẹ fi sílẹ̀.
Kí á tó fi e-mail mìíràn ránṣẹ́ sí àkópamọ́ yìí, ẹ gbọ́dọ̀ tẹ̀lé àwọn ìlànà inú e-mail ọ̀ún, láti múdájú pé àkópamọ́ ọ̀ún jẹ́ ti yín lóòótọ́.',
'throttled-mailpassword'     => 'Aṣèránnilétí ọ̀rọ̀ìpamọ́ tilẹ̀ ti jẹ́ fífiránṣẹ́, láàrin {{PLURAL:$1|wákàtí kan|wákàtí $1}} ṣẹ́yìn.
Láti dínà ìbàjẹ́, aṣèránnilétí ọ̀rọ̀ìpamọ́ kan péré ni yíò jẹ́ fífiránṣẹ́ láàrin {{PLURAL:$1|wákàtí kọ̀ọ̀kan|wákàtí $1}}.',
'mailerror'                  => 'Àsìṣe ìfiránṣẹ́: $1',
'acct_creation_throttle_hit' => 'Àwọn aṣàbẹ̀wò sí wiki yìí tí wọ́n únlo àdírẹ́sì IP yín ti dá {{PLURAL:$1|àpamọ́ 1|àpamọ́ $1}} láàrin ọjọ́ tókọjá, èyí ni púpọ̀jùlọ tó jẹ́ gbígbà ní ààyè láàrin gbà àsìkò yìí.
Nítorí èyí, àwọn aṣàbẹ̀wò tí wọ́n únlo àdírẹ́sì IP yìí kò le dá àpamọ́ báyìí.',
'emailauthenticated'         => 'Àdírẹ́ẹ̀sì e-mail yín ti fidájú ní ago $3 ọjọ́ $2.',
'emailnotauthenticated'      => 'Àdírẹ́sì e-mail yín kò ì jẹ́ fífidájú.
E-mail kankan kò ní jẹ́ fífiránṣẹ́ fún ìkankan nínú àwọn ìní wọ̀nyí.',
'noemailprefs'               => 'Ẹ pèsè àdírẹ́sì e-mail kan nínú àwọn ìfẹ́ràn yín fún àwọn ìní yìí le ba ṣiṣẹ́.',
'emailconfirmlink'           => 'Ìmúdájú àdírẹ́ẹ̀sì e-mail yín',
'invalidemailaddress'        => 'Àdírẹ́sì e-mail náà kò ṣe é gbà torípé ó dà bi pé irú rẹ̀ kò tọ́.
Ẹ jọ̀wọ́ ẹ pèsè àdírẹ́sì tó tọ́ tàbí kí ẹ fi ààyè náà sí òfo.',
'accountcreated'             => 'Ẹ ti fi orúkọ sílẹ̀',
'accountcreatedtext'         => "A ti ṣ'èdá àkópamọ́ oniṣe fún $1.",
'createaccount-title'        => 'Ìforúkọ sílẹ̀ fún {{SITENAME}}',
'createaccount-text'         => 'Ẹnìkan dá àpamọ́ kan fún àdírẹ́sì e-mail yín sórí {{SITENAME}} ($4) tóún jẹ́ "$2", pẹ̀kú ọ̀rọ̀ìpamọ́ \'\'$3\'\'.
Ẹ gbọ́dọ̀ wọlé kí ẹ sì ṣàyípadà ọ́rọ́ìpamọ́ yín nísinsìyí.

Ẹ le fojúfo ìránṣẹ́ yìí, tó bá jẹ́ pé àpamọ́ yìí jẹ́ dídá nítorí àsìṣe.',
'usernamehasherror'          => 'Orúkọ oníṣe yín kò gbọdọ̀ ní àmílẹ́tà hash',
'login-throttled'            => 'Ẹ ti gbìyànjú bó ṣe yẹ lọ láti wọlé.
Ẹ jọ̀wọ́ ẹ dúró ná kí ẹ tó gbìyànjú lẹ́ẹ̀kan síi.',
'login-abort-generic'        => 'Ìwọlé yín kò yọrísírere - ó ti jẹ́ kíkáwọ́dà',
'loginlanguagelabel'         => 'Èdè: $1',

# E-mail sending
'php-mail-error-unknown' => 'Àsìṣe àìmọ̀ nínú ìgbéṣe mail() ti PHP',

# JavaScript password checks
'password-strength'            => 'Ìdíye okun ọ̀rọ̀ìpamọ́: $1',
'password-strength-bad'        => 'BÚBURÚ',
'password-strength-mediocre'   => 'àìkánú',
'password-strength-acceptable' => 'fífaramọ́',
'password-strength-good'       => 'dáradára',
'password-retype'              => 'Ẹ tún tẹ ọ̀rọ̀ìpamọ́ síbí',
'password-retype-mismatch'     => 'Àwọn ọ̀rọ̀ìpamọ́ kò jọra',

# Password reset dialog
'resetpass'                 => 'Ìyípadà ọ̀rọ̀ìpamọ́',
'resetpass_announce'        => 'Ẹ ti wọlé pẹ̀lú àmìọ̀rọ̀ e-mail ìgbàdíẹ̀.
Láti parí ìmúwọlẹ́, ẹ gbọ́dọ̀ ṣètò ọ̀rọ̀ìpamọ́ tuntun níbí:',
'resetpass_header'          => "Ẹ ṣ'àyípadà ọ̀rọ̀ìpamọ́",
'oldpassword'               => 'Ọ̀rọ̀ìpamọ́ titẹ́lẹ̀:',
'newpassword'               => 'Ọ̀rọ̀ìpamọ́ tuntun:',
'retypenew'                 => 'Àtúntẹ̀ ọ̀rọ̀ìpamọ́ tuntun:',
'resetpass_submit'          => 'Ẹ ṣe àtúntò ọ̀rọ̀ìpamọ́ kí ẹ tó wọlé',
'resetpass_success'         => 'Ìyípadà ọ̀rọ̀ìpamọ́ yín ti já sí rere! Ẹ̀ ún wọlé lọ́wọ́...',
'resetpass_forbidden'       => 'Àwọn ọ̀rọ̀ìpamọ́ kò ṣe é yípadà',
'resetpass-no-info'         => 'Ẹ gbọ́dọ̀ wọlẹ́ láti le lọ sí ojúewé yìí tààrà.',
'resetpass-submit-loggedin' => 'Ìyípadà ọ̀rọ̀ìpamọ́',
'resetpass-submit-cancel'   => 'Fagilé',
'resetpass-wrong-oldpass'   => 'Ọ̀rọ̀ìpamọ́ ìgbàdíẹ̀ tàbí tìsinsìnyí àìtọ́.
Ó le jẹ́ pé ẹ ti yí ọ̀rọ̀ìpamọ́ yín padà sí òmíràn tàbí ẹ ti tọrọ ọ́rọ́ìpamọ́ tuntun ìgbàdíẹ̀.',
'resetpass-temp-password'   => 'Ọ̀rọ̀ìpamọ́ fún ìgbà díẹ̀',

# Edit page toolbar
'bold_sample'     => 'Ìkọ kedere',
'bold_tip'        => 'Ìkọ kedere',
'italic_sample'   => 'Ìkọ italiki',
'italic_tip'      => 'Ìkọ̀wé italiki',
'link_sample'     => 'Àkọlé ìjápọ̀',
'link_tip'        => 'Ìjápọ̀ inú',
'extlink_sample'  => 'http://www.example.com àkọlé ìjápọ̀',
'extlink_tip'     => 'Ìjápọ̀ lóde (ẹ mọ́ gbàgbé àlẹ̀mọ́wájú http://)',
'headline_sample' => 'Ìkọ àkọlé',
'headline_tip'    => 'Àkọlé onípele 2',
'math_sample'     => "Ẹ fi àgbékalẹ̀ s'íhín",
'math_tip'        => 'Àgbékalẹ̀ ìsirò (LaTeX)',
'nowiki_sample'   => 'Ìkìbọ̀ ìkọ àìjẹ́ síṣèdá síbí',
'nowiki_tip'      => 'Kí á fojú fo bí wiki ṣe rí',
'image_tip'       => 'Fáìlì tí a kìbọ̀',
'media_tip'       => 'Ìjápọ̀ fáìlì',
'sig_tip'         => 'Ìtọwọ́bọ̀wé yín pẹ̀lú àsìkò àti déètì',
'hr_tip'          => 'Ìlà gbọlọjọ (ẹ lọ̀ọ́ pẹ̀lú àkíyèsì)',

# Edit pages
'summary'                          => 'Àkótán:',
'subject'                          => 'Orí ọ̀rọ̀/àkọlé:',
'minoredit'                        => 'Àtúnṣe kékeré nìyí',
'watchthis'                        => "M'ójútó ojúewé yìí",
'savearticle'                      => 'Ìmúpamọ́ ojúewé',
'preview'                          => 'Àyẹ̀wò',
'showpreview'                      => 'Àkọ́yẹ̀wò',
'showlivepreview'                  => 'Àkọ́yẹ̀wò lẹ́sẹ̀kẹsẹ̀',
'showdiff'                         => 'Ìfihàn àwọn àtúnṣe',
'anoneditwarning'                  => "'''Ìkìlọ̀:''' Ẹ kò tíì wọlé.
Àdírẹ́ẹ̀sì IP yín yíò jẹ́ kíkọpamọ́ sínú ìwé ìtàn àtúnṣe ojúewé yìí.",
'anonpreviewwarning'               => "''Ẹ kò tíì wọlé. Àdírẹ́ẹ̀sì IP yín yíò jẹ́ kíkọsílẹ̀ sínú ìwé ìtàn àtúnṣe ojúewé yìí tí ẹ bá ṣàmúpamọ́ rẹ̀.''",
'missingsummary'                   => "'''Ìránlétí:''' Ẹ kò pèsè àkótán fún àtúnṣe yìí
Tí ẹ bá tẹ Ìmúpamọ́ lẹ́ẹ̀kansi, àtúnṣe yín yíò jẹ̀ mímúpamọ́ láìní kankan.",
'missingcommenttext'               => 'Ẹjọ̀wọ́ ẹ ṣe áríwí ní ìsàlẹ̀',
'missingcommentheader'             => "'''Ìránlétí:''' Ẹ kò pèsè àkọlé/oríọ̀rọ̀ kankan fún àríwí yìí.
Tí ẹ bá tẹ \"{{int:savearticle}}\" lẹ́ẹ̀kansi, àtúnṣe yín yíò jẹ́ mímúpamọ́ láìní kankan.",
'summary-preview'                  => 'Àkọ́yẹ̀wò àkótán:',
'subject-preview'                  => 'Àyẹ̀wò àkọlé',
'blockedtitle'                     => 'Ìdínà oníṣe',
'blockedtext'                      => "'''Orúkọ oníṣe yín tàbí àdírẹ́sì IP yín ti jẹ́ dídílọ́nà.'''

$1 ni ó ṣe ìdínà.
Ìdí tó fun ni ''$2''.

* Ìbẹ̀rẹ̀ ìdínà: $8
* Ìparí ìdínà: $6
* Ẹni tí a fẹ́ dínà: $7

Ẹ ṣ'èránṣẹ́ sí $1 tàbí [[{{MediaWiki:Grouppage-sysop}}|alámùójútó]] mìíràn láti fọ̀rọ̀wérọ̀ lórí ìdínà ọ̀ún.
Ẹ kò le è 'ránṣẹ́ sí oníṣe yìí pẹ̀lú e-mail' àyàfi tí ojúọ̀nà e-mail tó dájú wà ní [[Special:Preferences|àwọn ìfẹ́ràn àpamọ́]] yín tí wọn kò sì ti dínà yín láti lò ó.
Àdírẹ́sì IP yín lọ́wọ́lọ́wọ́ ni $3, bẹ́ ẹ̀ sì ni ID fún ìdínà yín ni #$5.
Ẹ jọ̀wọ́ ẹ fi gbogbo ẹ̀kúnrẹ́rẹ́ òkè yìí kún ìbérè tí ẹ bá ṣe.",
'autoblockedtext'                  => "Àdírẹ́sì IP yín ti jẹ́ dídílọ́nà ní fúnrararẹ̀ nítorí pé ó jẹ́ lílò látọwọ́ oníṣe míràn tí ó jẹ́ dídílọ́nà látọwọ́ $1.
Ìdíẹ̀ tó ṣe jẹ́ bẹ́ẹ̀ nìyí:

:''$2''


* Ìbẹ̀rẹ̀ ìdínà: $8
* Ìparí ìdínà: $6
* Ẹni tí a fẹ́ dínà: $7

Ẹ le ránṣẹ́ sí $1 tàbí ìkan láàrin [[{{MediaWiki:Grouppage-sysop}}|àwọn olùmójútó]] mìíràn láti fọ̀rọ̀wérọ̀ lórí ìdínà ọ̀ún.

Àkíyèsí pé ẹ le mọ́ le lo ìní ''Ẹ fi e-mail ránṣẹ́ sí oníṣe yìí'' tí àdírẹ́sì e-mail tó tọ́ jẹ́ fífilórúkọsílẹ̀ sínú [[Special:Preferences|àwọn ìfẹ́ràn oníṣe]] yín tí wọn kò sì ti dínà yín láti lò ó.

Àdírẹ́sì IP yín lọ́wọ́lọ́wọ́ ni $3, bẹ́ ẹ̀ sì ni ID fún ìdínà yín ni #$5.
Ẹ jọ̀wọ́ ẹ fi gbogbo ẹ̀kúnrẹ́rẹ́ òkè yìí pọ̀mọ́ ìbérè tí ẹ bá ṣe.",
'blockednoreason'                  => 'kó sí àlàyé kankan',
'blockedoriginalsource'            => "Orísun fún '''$1''' hàn ni sàlẹ̀:",
'blockededitsource'                => "Ìkọ̀wé fún '''àwọn atúnṣe yín''' sí '''$1''' hàn nísàlẹ̀ yìí:",
'whitelistedittitle'               => "Ìwọlé ṣe dandan láti ṣ'àtúnṣe",
'whitelistedittext'                => "Ẹ gbọ́dọ̀ $1 láti ṣ'àtúnṣe àwọn ojúewé.",
'confirmedittext'                  => "Ẹ gbọ́dọ̀ ṣe ìmúdájú àdírẹ́ẹ̀sì e-mail yín kí ẹ tó le è mọ ṣ'àtúnṣe àwọn ojúewé.
Ẹjọ̀wọ́ ẹ ṣètò bẹ́ sìni ki ẹ fọwọ́sí àdírẹ́ẹ̀sì e-mail nínú [[Special:Preferences|àwọn ìfẹ́ràn ọníṣe]] yín.",
'nosuchsectiontitle'               => 'Kò le rí abala báun',
'nosuchsectiontext'                => 'Ẹ ti gbìyànjú láti ṣàtúnṣe abala tí kòsí.
Ó ti le jẹ́ yíyípò tàbí píparẹ́ nígbà tí ẹ ún bojúwo ojúewé náà.',
'loginreqtitle'                    => "Ẹ gbọ́dọ̀ kọ́kọ́ w'ọlé ná",
'loginreqlink'                     => 'ẹ wọlé',
'loginreqpagetext'                 => 'Ẹ gbọ́dọ̀ $1 láti wo àwọn ojúewé míràn.',
'accmailtitle'                     => 'Ti fi ọ̀rọ̀ìpamọ́ ránṣẹ́.',
'accmailtext'                      => "A ti fi ọ̀rọ̀ìpamọ́ àrìnàkò tí a pèsè fún [[User talk:$1|$1]] ránṣẹ́ sí $2.

Ẹ le ṣe àyípadà ọ̀rọ̀ìpamọ́ fún àpamọ́ tuntun yìí ní ''[[Special:ChangePassword|change password]]'' lẹ́yìn tí ẹ bá ti wọlé.",
'newarticle'                       => '(Tuntun)',
'newarticletext'                   => "Ẹ ti tẹ̀lé ìjápọ̀ mọ́ ojúewé tí kò sí.
Láti dá ojúewé yí ẹ bẹ̀rẹ̀ síní tẹ́kọ sí inú àpótí ìsàlẹ̀ yí (ẹ wo [[{{MediaWiki:Helppage}}|ojúewé ìrànlọ́wọ́ ]] fun ẹ̀kúnrẹ́rẹ́ ).
T'óbá sepé àsìse ló gbé yin dé bi, ẹ kọn bọ́tìnì ìpadàsẹ́yìn.",
'noarticletext'                    => 'Lọ́wọ́lọ́wọ́ kò sí ìkọ̀ nínú ojúewé yìí.
Ẹ le [[Special:Search/{{PAGENAME}}|wá àkọlé ojúewé yìí]] nínú àwọn ojúewé mìíràn,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} wá àkọọ́lẹ̀ rẹ̀], tàbí [{{fullurl:{{FULLPAGENAME}}|action=edit}} kí ẹ ṣ\'àtúnṣe ojúewé òún]</span>.',
'noarticletext-nopermission'       => 'Lọ́wọ́lọ́wọ́ kò sí ìkọ̀ nínú ojúewé yìí.
Ẹ le [[Special:Search/{{PAGENAME}}|wá àkọlé ojúewé yìí]] nínú àwọn ojúewé mìíràn, tàbí
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} wá àwọn àkọọ́lẹ̀ tó bámu]</span>.',
'userpage-userdoesnotexist'        => 'Àkópamọ́ oníṣe "$1" kò tíì jẹ́ fíforúkọsílẹ̀.
Ẹjọ̀wọ́ ẹ ṣ\'àgbéyẹ̀wò bóyá ẹ fẹ́ dá/ṣàtúnṣe ojúewé yìí.',
'userpage-userdoesnotexist-view'   => 'Àpamọ́ oníṣe "$1" kò jẹ́ fífilórúkọsílẹ̀.',
'blocked-notice-logextract'        => 'Lọ́wọ́lọ́wọ́ oníṣe yìí jẹ́ dídílọ́nà.
Àkọsílẹ̀ ìdínà àìpẹ́ nìyí nísàlẹ̀ fún ìtọ́kasí:',
'clearyourcache'                   => "'''Àkíyèsí: Lẹ́yìn ìmúpamọ́, ó ṣe é ṣe kó jẹ́ pé ẹ gbọ́dọ̀ fo cache agbétàkùn yín láti rí àwọn ìyípadà.'''
'''Mozilla / Firefox / Safari:''' ẹ di ''Shift'' mú bí ẹ ṣe ún tẹ ''Reload'', tàbí kí ẹ tẹ ''Ctrl-F5'' tàbí ''Ctrl-R'' (''Command-R'' lórí Macintosh);
'''Konqueror: '''ẹ tẹ ''Reload'' tàbí kí ẹ tẹ ''F5'';
'''Opera:''' ẹ pa cache rẹ́ nínú ''Tools → Preferences'';
'''Internet Explorer:''' ẹ di ''Ctrl'' mú bí ẹ ṣe ún tẹ ''Refresh,'' tàbí kí ẹ tẹ ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Ìrànlọ́wọ́:''' Ẹ lo bọ́tìnì \"{{int:showpreview}}\" fún dídánwò CSS tuntun yín kí ẹ tó múupamọ́.",
'userjsyoucanpreview'              => "'''Ìrànlọ́wọ́:''' Ẹ lo bọ́tìnì \"{{int:showpreview}}\" fún dídánwò JavaScript tuntun yín kí ẹ tó múupamọ́.",
'usercsspreview'                   => "''''Ẹ mọ́ gbàgbé pé àkọ́yẹ̀wò CSS oníṣe yín nìyí.'''
'''Kò tíì jẹ́ mímúpamọ́!'''",
'userjspreview'                    => "''''Ẹ mọ́ gbàgbé pé àdánwò/àkọ́yẹ̀wò JavaScript oníṣe yín nìyí.'''
'''Kò tíì jẹ́ mímúpamọ́!'''",
'sitecsspreview'                   => "'''Ẹ rántí pé àkọ́yẹ̀wò CSS nìyí.'''
'''Kò tíì jẹ́ mímúpamọ!'''",
'sitejspreview'                    => "'''Ẹ rántí pé àkọ́yẹ̀wò àmìọ̀rọ̀ JavaScript nìyí.'''
'''Kò tíì jẹ́ mímúpamọ!'''",
'userinvalidcssjstitle'            => "'''Ìkìlọ̀:''' Kò sí awọ-ìbojú \"\$1\".
Ẹ rántí pé àwọn ojúewé àkànṣe .css àti .js únlo àkọlé onílẹ́tà kékeré, f.a. {{ns:user}}:Foo/vector.css yàtò sí {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Sísọdọ̀tun)',
'note'                             => "'''Àkíyèsí:'''",
'previewnote'                      => "'''Ẹ rántí pé àyẹ̀wò lásán nì yí.'''
Àwọn àtúnṣe yín kò tíì jẹ́ kìkópamọ́!",
'session_fail_preview'             => "'''Àforíjìn! A kò le gbésẹ̀ àtúnṣe yín nítorí ìpòfo data ìsinsìyí.
Ẹ jọ̀wọ́ ẹ gbìyànjú lẹ́ẹ̀kan si.
Tí kò bá sì tún ṣiṣẹ́, ẹ gbìyànjú láti [[Special:UserLogout|bọ̀sòde]] kí ẹ sì padá wọlé.'''",
'session_fail_preview_html'        => "'''Àforíjìn! A kò le gbéṣẹ̀ àtúnṣe yín nítorí ìpòfo dátà ìgbànáà.'''

''Nítorípé {{SITENAME}} gba HTML àìgbéṣe láàyè, àkọ́kọ́yẹ̀wò jẹ́ bíbòmọ́lẹ̀ láti dínà àwọn ìkọlù JavaScript.''

'''Tó bá ṣe pé ìgbìyànj ìṣàtúnṣe gidi nìyí, ẹ jọ̀wọ́ ẹ gbìyànjú lẹ́ẹ̀kansíi.'''
Tí kò bá ṣiṣẹ́ síbẹ̀, ẹ gbìyànjú láti [[Special:UserLogout|jáde]] kí ẹ sì padà wọlé.",
'editing'                          => 'Àtúnṣe sí $1',
'editingsection'                   => 'Àtúnṣe sí $1 (abala)',
'editingcomment'                   => 'Àtúnṣe sí $1 (abala tuntun)',
'editconflict'                     => 'Ìtakora àtúnṣe: $1',
'yourtext'                         => 'Ìkọ̀ yín',
'storedversion'                    => 'Àtúnyẹ̀wò tí à múpamọ́',
'yourdiff'                         => 'Àwọn ìyàtọ̀',
'copyrightwarning'                 => "Ẹ jọ̀wọ́ ẹ kíyèsi wípé gbogbo àwọn àfikún sí {{SITENAME}} jẹ́ bẹ̀ lábẹ́  $2 (Ẹ wo $1 fún ẹkunrẹrẹ).
Tí ẹ kò bá fẹ́ kí ìkọọ́lẹ̀ yín ó jẹ́ títúnṣe tàbí kì ó jẹ́ pípìn kiri lọ́ná tí kò wù yín, ẹ mọ́ mù wá síbí.<br />
Bákannà ẹ tún ṣèlérí fún wa wípé ẹ̀yin lẹkọ́ fúnra arayín, tàbí ẹ wòókọ láti agbègbè ìgboro tàbí irú ìtìlẹ́yín ọ̀fẹ́ bíi bẹ́ẹ̀.
'''Ẹ MỌ́ MÚ IṢẸ́ TÓ NÍ Ẹ̀TỌ́ÀWÒKỌ SÍLẸ̀ LÁÌ GBÀṢẸ!'''",
'longpageerror'                    => "'''Àsìṣe: Ìkọ̀wé tí ẹ fisílẹ̀ gùn tó $1 Kilobytes, èyí gùn ju $2 kilobytes lọ tó jẹ́ àjà.'''
Kò ṣe é múpamọ́.",
'protectedpagewarning'             => "'''Ìkìlọ̀: Ojúewé yìí ti jẹ́ títìpa, nítoríẹ̀ àwọn alámòjútó nìkan ni wọ́n ní ẹ̀tọ́ láti ṣàtúnṣe rẹ̀.'''
Àkọọ́lẹ̀ àìpẹ́ nìyí nísàlẹ̀ fún ìtọ́kasí:",
'semiprotectedpagewarning'         => "'''Àkíyèsí:''' Ojúewé yìí ti jẹ́ títìpa nítoríẹ̀ àwọn oníṣe tí wọ́n ti forúkọsílẹ̀ nìkan ni wọ́n le ṣàtúnṣe rẹ̀.
Àkọọ́lẹ̀ àìpẹ́ nìyí nísàlẹ̀ fún ìtọ́kasí.",
'templatesused'                    => '{{PLURAL:$1|Àdàkọ|Àwọn àdàkọ}} tí a lò lórí ojúewé yìí:',
'templatesusedpreview'             => '{{PLURAL:$1|Àdàkọ|Àwọn àdàkọ}} tí a lò nìnú àkọ́yẹ́wò yìí:',
'templatesusedsection'             => '{{PLURAL:$1|Àdàkọ|Àwọn àdàkọ}} tí a lò nínú abala yìí:',
'template-protected'               => '(aláàbò)',
'template-semiprotected'           => '(aláàbò díẹ̀)',
'hiddencategories'                 => 'Ojúewé yìí jẹ́ ìkan nínú {{PLURAL:$1|ẹ̀ka bíbòmọ́lẹ̀ 1|àwọn ẹ̀ka bíbòmọ́lẹ̀ $1}}:',
'nocreatetitle'                    => 'Ìdènà ìdá ojúewé',
'nocreatetext'                     => "{{SITENAME}} ti pààlà ààyè láti ṣ'èdá ojúewé tuntun.
Ẹ le padà sẹ́yìn kí ẹ ṣ'àtúnṣe ojúewé tó wà, tàbí [[Special:UserLogin|kí ẹ wọlé tàbí kí ẹ ṣ'èdá àpamọ́]].",
'nocreate-loggedin'                => "Ẹ kò ní ìyọ̀nda láti ṣe'dá ojúewé tuntun.",
'sectioneditnotsupported-title'    => 'Ko sí títìlẹ́yìn àtúnṣe abala',
'sectioneditnotsupported-text'     => 'Ko sí títìlẹ́yìn àtúnṣe abala lórí ojúewé yìí.',
'permissionserrors'                => 'Àṣìṣe ìyọ̀nda',
'permissionserrorstext'            => 'Ẹ kò ní ìyọ̀nda láti ṣè yí nítorí {{PLURAL:$1|ìdí ìsàlẹ̀ yìí|àwọn ìdí ìsàlẹ̀ wọ̀nyí}}:',
'permissionserrorstext-withaction' => 'Ẹ kò ní ìyọ̀nda láti $2, fún {{PLURAL:$1|ìdí yìí|àwọn ìdí wọ̀nyí}}:',
'recreate-moveddeleted-warn'       => "'''Ìkìlọ̀: Ẹ̀ ún ṣ'èdá ojúewé tí a ti parẹ́ tẹ́lẹ̀.'''

Ẹ gbọ́dọ̀ gberò bóyá ó bójúmu láti tẹ̀síwájú pẹ̀lú àtúnṣe ojúewé yìí.
Àkọsílẹ̀ ìparẹ́ àti ìyípò fún ojúewé yìí nìyí fún ìrọ̀rùn:",
'moveddeleted-notice'              => 'Ojúewé yìí tijẹ́ píparẹ́.
Àkọọ́lẹ̀ ìparẹ́ àti ìyípò fún ojúewé náà wà nísàlẹ̀ fún ìtákasí.',
'log-fulllog'                      => 'Ẹ wo gbogbo àkọọ́lẹ̀',
'edit-hook-aborted'                => 'Hook ti ṣe ìdádúró àtúnṣe.
Kò ṣe àlàyé kankan.',
'edit-gone-missing'                => 'A kò le ṣe títúnṣe ojúewé.
Ó dà bíi pé a ti paárẹ́.',
'edit-conflict'                    => 'Ìtakora áwọn àtúnṣe',
'edit-no-change'                   => 'A ṣe àìkàsí àtúnṣe yín, nítorípé ìkọ̀wé kò ní àtúnṣe kankan.',
'edit-already-exists'              => "A kò le è ṣè'dá ojúewé tuntun.
Ó pilẹ̀ ti wà.",

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''Ìkìlọ̀:''' Ìtóbi àdàkọ tó jẹ́ mímúpọ̀ mọ ti pòjù.
Àwọn apá àdàkọ kan kò ní jẹ́ mímúpọ̀.",
'post-expand-template-inclusion-category' => 'Àwọn ojúewé tí ìtóbi àdàkọ mímúpọ̀ wọn pọ̀jù.',
'parser-template-loop-warning'            => 'Ìlọ́po àdàkọ ti ṣẹlẹ̀: [[$1]]',

# "Undo" feature
'undo-success' => 'Àtúnṣe náà ṣe é múkúrò.
Ẹ jọ̀wọ́ ẹ wo ìfiwéra ìsàlẹ̀ láti rídájú pé ohun tí ẹ fẹ́ nì yẹn, nígbà náà ẹ mú àwọn àtúnṣe náà pamọ́ láti parí ìmúkúrò àtúnṣe.',
'undo-failure' => 'Àtúnṣe náà kò ṣe é múkúrò nítorí títakora àwọn àtúnṣe inú àrin.',
'undo-norev'   => 'Àtúnṣe náà kò ṣe é múkúrò nítorí pé kò sí tàbí pé ó ti jẹ́ píparẹ́.',
'undo-summary' => 'Ìmúkúrò àtúnyẹ̀wò $1 ti [[Special:Contributions/$2|$2]] ([[User talk:$2|ọ̀rọ̀]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ìforúkọsílẹ̀ kò se é se',
'cantcreateaccount-text' => "[[User:$3|$3]] ti dènà dídá àkópamọ́ láti orí àdírẹ́ẹ̀sì IP yìí ('''$1''').

Ìdí tí $3 ṣe ṣèyí ni ''$2''",

# History pages
'viewpagelogs'           => 'Ẹ wo àkọsílẹ̀ ìṣẹ̀lẹ̀ fún ojúewé yìí',
'nohistory'              => 'Kò sí ìtàn àtùnṣe fún ojúewé yìí.',
'currentrev'             => 'Àtúnyẹ̀wò ìsinsìnyí',
'currentrev-asof'        => 'Àtúnyẹ̀wò lọ́wọ́lọ́wọ́ ní $1',
'revisionasof'           => 'Àtúnyẹ̀wò ní $1',
'revision-info'          => "Àtúnyẹ̀wò ní $1 l'átọwọ́ $2",
'previousrevision'       => '← Àtúnyẹ̀wò tópẹ́ju',
'nextrevision'           => 'Àtúnyẹ̀wò tótuntunju →',
'currentrevisionlink'    => 'Àtúnyẹ̀wò ìsinsìnyí',
'cur'                    => 'lọ́wọ́',
'next'                   => 'tókàn',
'last'                   => 'tẹ́lẹ̀',
'page_first'             => 'àkọ́kọ́',
'page_last'              => 'tógbẹ̀yìn',
'histlegend'             => "Àṣàyàn ìyàtọ̀: ẹ fagi sínú àpótí àwọn átúnyẹ̀wò tí ẹ fẹ́ ṣàfiwè, lẹ́yìn náà ẹ tẹ enter tàbí bọ́tìnì ìsàlẹ̀.<br />
Àlàyé: '''({{int:cur}})''' = ìyàtọ̀ sí àtúnyẹ̀wò tìsinyìí, '''({{int:last}})''' = ìyàtọ̀ sí àtúnyẹ̀wò tókọjá, '''{{int:minoreditletter}}''' = àtúnṣe kékeré.",
'history-fieldset-title' => 'Ìṣíwò ìwé ìtàn àtúnṣe',
'history-show-deleted'   => 'Ajẹ́píparẹ́ níkan',
'histfirst'              => 'Pípẹ́jùlọ',
'histlast'               => 'Tuntunjùlọ',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(òfo)',

# Revision feed
'history-feed-title'          => 'Ìtàn àtúnyẹ̀wò',
'history-feed-description'    => 'Ìtàn àtúnyẹ̀wò fún ojúewé yìí ní orí wiki',
'history-feed-item-nocomment' => '$1 ní $2',
'history-feed-empty'          => 'Ojúewé tí ẹ tọrọ fún kò sí.
Ó ṣe é ṣe kó ti jẹ́ píparẹ́ kúrò nínú wiki náà, tàbí kó ti jẹ́ títúnṣọlórùkọ.
Ẹ gbìyànjú láti [[Special:Search|wá inú wiki náà]] fún àwọn ojúewé tóbáramu.',

# Revision deletion
'rev-deleted-comment'         => '(ìyọkúrò àkótán àtúnṣe)',
'rev-deleted-user'            => '(orúkọ oníṣe ti jẹ́ yíyọkúrò)',
'rev-deleted-event'           => '(àkọọ́lẹ̀ ti jẹ́ yíyọkúrò)',
'rev-deleted-user-contribs'   => '[orúkọ oníṣe tàbí àdírẹ́sì IP jẹ́ yíyọkúrò - àtúnṣe jẹ́ bíbòmọ́lẹ̀ kúrò nínú àwọn àfikún]',
'rev-deleted-text-permission' => "Àtúnyẹ̀wò ojúewé yìí ti jẹ́ '''píparẹ́'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].",
'rev-deleted-text-unhide'     => "Àtúnyẹ̀wò ojúewé yìí ti jẹ́ '''píparẹ́'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le [$1 wo àtúnyẹ́wò yìí] tí ẹ bá fẹ́.",
'rev-suppressed-text-unhide'  => "Àtúnyẹ̀wò ojúewé yìí ti jẹ́ '''fífisílẹ̀'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìfisílẹ̀].
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le [$1 wo àtúnyẹ́wò yìí] tí ẹ bá fẹ́.",
'rev-deleted-text-view'       => "Àtúnyẹ̀wò ojúewé yìí ti jẹ́ '''píparẹ́'''.
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le wo; ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].",
'rev-suppressed-text-view'    => "Àtúnyẹ̀wò ojúewé yìí ti jẹ́ '''fífisílẹ̀'''.
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le wo; ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìfisílẹ̀].",
'rev-deleted-no-diff'         => "Ẹ kò le wo ìyàtọ̀ yìí nítorípé ìkan nínú àwọn àtúnyẹ̀wò ti jẹ́ '''píparẹ́'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].",
'rev-suppressed-no-diff'      => "Ẹ kò le wo ìyàtọ̀ yìí nítorípé ìkan nínú àwọn àtúnyẹ̀wò ti jẹ́ '''píparẹ́'''.",
'rev-deleted-unhide-diff'     => "Ìkan nínú àwọn àtúnyẹ̀wò ìyàtọ̀ yìí ti jẹ́ '''píparẹ́'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le [$1 wo àtúnyẹ́wò yìí] tí ẹ bá fẹ́.",
'rev-suppressed-unhide-diff'  => "Ìkan nínú àwọn àtúnyẹ̀wò ìyàtọ̀ yìí ti jẹ́ '''fífisílẹ̀'''.
Ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìfisílẹ̀].
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le [$1 wo àtúnyẹ́wò yìí] tí ẹ bá fẹ́.",
'rev-deleted-diff-view'       => "Ìkan nínú àwọn àtúnyẹ̀wò ìyàtọ̀ yìí ti jẹ́ '''píparẹ́'''.
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le wo ìyàtọ̀ yìí; ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìparẹ́].",
'rev-suppressed-diff-view'    => "Ìkan nínú àwọn àtúnyẹ̀wò ìyàtọ̀ yìí ti jẹ́ '''fífisílẹ̀'''.
Gẹ́gẹ́ bíi olùmójútó ẹ ṣì le wo ìyàtọ̀ yìí; ẹ̀kúnrẹ́rẹ́ wà nínú [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} àkọọ́lẹ̀ ìfisílẹ̀].",
'rev-delundel'                => 'fihàn/bòmọ́lẹ̀',
'rev-showdeleted'             => 'fihàn',
'revisiondelete'              => 'Ṣe ìparẹ́/àìparẹ́ àwọn àtúnyẹ̀wò',
'revdelete-nooldid-title'     => 'Wíwá àtúnyẹ̀wò tíkòtọ́',
'revdelete-nologtype-title'   => 'Kò sí irú àkọọ́lẹ̀ tó jẹ́ títọ́kasí',
'revdelete-nologtype-text'    => 'Ẹ kò tíì tọ́kasí irú àkọọ́lẹ̀ tí ìgbéṣe yìí yíò ṣẹlẹ̀ lórí.',
'revdelete-nologid-title'     => 'Àkọọ́lẹ̀ ìṣẹ̀lẹ̀ tíkòtọ́',
'revdelete-no-file'           => 'Fáìlì tójẹ́ títọ́kasí kò sí.',
'revdelete-show-file-submit'  => 'Bẹ́ẹ̀ni',
'revdelete-selected'          => "'''{{PLURAL:$2|Àtúnyẹ̀wò síṣàyàn|Àwọn àtúnyẹ̀wò síṣàyàn}} fún [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Àkọọ́lẹ̀ ìṣẹ̀lẹ̀ síṣàyàn|Àwọn àkọọ́lẹ̀ ìṣẹ̀lẹ̀ síṣàyàn}}:'''",
'revdelete-hide-text'         => 'Ìbòmọ́lẹ̀ ìkọ̀ àtúnyẹ̀wò',
'revdelete-hide-image'        => 'Ìbòmọ́lẹ̀ àkóónú fáìlì',
'revdelete-hide-name'         => 'Ìbòmọ́lẹ̀ ìgbéṣe àti wíwá',
'revdelete-hide-comment'      => 'Ìbòmọ́lẹ̀ àríwí àtúnṣe',
'revdelete-hide-user'         => 'Ìbòmọ́lẹ̀ orúkọ oníṣe/IP olóòtú',
'revdelete-hide-restricted'   => 'Ìbòmọ́lẹ̀ àwọn ìpèsè ti àwọn alámùójútó àti ti àwọn yìókù',
'revdelete-radio-same'        => '(láì yípadà)',
'revdelete-radio-set'         => 'Bẹ́ẹ̀ni',
'revdelete-radio-unset'       => 'Bẹ́ẹ̀kọ́',
'revdelete-suppress'          => 'Ìbòmọ́lẹ̀ àwọn ìpèsè ti àwọn alámùójútó àti ti àwọn yìókù',
'revdelete-log'               => 'Ìdíẹ̀:',
'revdelete-submit'            => 'Ṣe é sí {{PLURAL:$1|àtúnyẹ̀wò|àwọn àtúnyẹ̀wò}} ṣíṣàyàn',
'revdel-restore'              => 'ìyípadà ìríran',
'revdel-restore-deleted'      => 'àwọn àtúnyẹ̀wò píparẹ́',
'revdel-restore-visible'      => 'àwọn àtúnyẹ̀wò aṣeéfojúrí',
'pagehist'                    => 'Ìtàn ojúewé',
'deletedhist'                 => 'Ìtàn ìparẹ́',
'revdelete-content'           => 'àkóónú',
'revdelete-summary'           => 'àkótán àtúnṣe',
'revdelete-uname'             => 'orúkọ oníṣe',
'revdelete-hid'               => 'ìbòmọ́lẹ̀ $1',
'revdelete-unhid'             => 'ìfihàn $1',
'revdelete-log-message'       => '$1 fún $2 {{PLURAL:$2|àtúnyẹ̀wò|àwọn àtúnyẹ̀wò}}',
'logdelete-log-message'       => '$1 fún $2 {{PLURAL:$2|ìṣẹ̀lẹ̀|àwọn ìṣẹ̀lẹ̀}}',
'revdelete-reason-dropdown'   => '*Àwọn ìdí ìdarẹ́ awọ́pọ̀
** Àìtẹ̀lé ẹ̀tọ́àwòkọ
** Ìwífún taraẹni aláìyẹ
** Ìwífún tó le fa ẹjọ́',
'revdelete-otherreason'       => 'Ìdíẹ̀ míràn/àfikún',
'revdelete-reasonotherlist'   => 'Ìdí míràn',
'revdelete-edit-reasonlist'   => 'Àtúnṣe àwọn ìdí ìparẹ́',
'revdelete-offender'          => 'Olùdákọ àtúnyẹ̀wò:',

# Suppression log
'suppressionlog' => 'Àkọọ́lẹ̀ ìfisílẹ̀',

# Revision move
'moverevlogentry'              => 'ṣèyípò {{PLURAL:$3|àtúnyẹ̀wò kan|àtúnyẹ̀wò $3}} láti $1 sí $2',
'revisionmove'                 => "Ìyípò àwọn àtúnyẹ̀wò láti ''$1''",
'revmove-submit'               => 'Ìyípò àwọn àtúnyẹ̀wò sí ojúewé ṣíṣàyàn',
'revisionmoveselectedversions' => 'Ìyípò àwọn àtúnyẹ̀wò ṣíṣàyàn',
'revmove-reasonfield'          => 'Ìdíẹ̀:',
'revmove-badparam-title'       => 'Àwọn pàrámítà búrurú',
'revmove-nullmove-title'       => 'Àkọlé búrurú',
'revmove-success-existing'     => '{{PLURAL:$1|Àtúnyẹ̀wò kan láti [[$2]]|Àwọn àtúnyẹ̀wò $1 láti [[$2]]}} ti jẹ́ yíyípò sí ojúewé [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Àtúnyẹ̀wò kan láti [[$2]]|Àwọn àtúnyẹ̀wò $1 láti [[$2]]}} ti jẹ́ yíyípò sí ojúewé tuntun [[$3]].',

# History merging
'mergehistory'                     => 'Ìdàpọ̀ àwọn ìtàn ojúewé',
'mergehistory-box'                 => 'Ìdàpọ̀ àwọn àtúnyẹ̀wò ti àwọn ojúewé méjì:',
'mergehistory-from'                => 'Ojúewé orísun:',
'mergehistory-into'                => 'Ojúewé ìdópin:',
'mergehistory-submit'              => 'Ìdàpọ̀ àwọn àtúnyẹ̀wò',
'mergehistory-no-source'           => 'Ojúewé orísun $1 kò sí.',
'mergehistory-no-destination'      => 'Ojúewé ìdópin $1 kò sí.',
'mergehistory-invalid-source'      => 'Ojúewé orísun gbọ́dọ̀ ní àkọlé tótọ́.',
'mergehistory-invalid-destination' => 'Ojúewé ìdópin gbọ́dọ̀ ní àkọlé tótọ́.',
'mergehistory-autocomment'         => '[[:$1]] ti jẹ́ dídàpọ̀ sínú [[:$2]]',
'mergehistory-comment'             => '[[:$1]] ti jẹ́ dídàpọ̀ sínú [[:$2]]: $3',
'mergehistory-same-destination'    => 'Ojúewé orísun àti ojúewé ìdópin kò gbọdọ̀ jẹ́ ìkannáà',
'mergehistory-reason'              => 'Ìdíẹ̀:',

# Merge log
'mergelog'           => 'Àkọọ́lẹ̀ ìdàpọ̀',
'pagemerge-logentry' => '[[$1]] ti jẹ́ dídàpọ̀ sínúu [[$2]] (àwọn àtúnyẹ̀wò títí dé $3)',
'revertmerge'        => 'Ìdápadà ìdàpọ̀',

# Diffs
'history-title'            => 'Ìtàn àtúnyẹ̀wò fún "$1"',
'difference'               => '(Ìyàtọ̀ láàrin àwọn àtúnyẹ́wò)',
'difference-multipage'     => '(Ìyàtọ̀ láàrin àwọn ojúewé)',
'lineno'                   => 'Ìlà $1:',
'compareselectedversions'  => 'Ìfiwéra àwọn àtúnṣe ìṣàyàn',
'showhideselectedversions' => 'Ìfihàn/ìbòmọ́lẹ̀ àwọn àtúnyẹ̀wò ṣíṣàyàn',
'editundo'                 => 'dápadà',
'diff-multi'               => '({{PLURAL:$1|Àtúnyẹ̀wò inú àrin kan|Àwọn àtúnyẹ̀wò inú àrin $1}} látọwọ́ {{PLURAL:$2|oníṣe kan|àwọn oníṣe $2}} kò jẹ́ fífihàn)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Àtúnyẹ̀wò inú àrin kan|Àwọn àtúnyẹ̀wò inú àrin $1}} látọwọ́ {{PLURAL:$2|oníṣe|àwọn oníṣe}} tó pọ̀ju $2 lọ kò jẹ́ fífihàn)',

# Search results
'searchresults'                    => 'Àwọn èsì àwárí',
'searchresults-title'              => 'Àwọn èsì àwárí fún "$1"',
'searchresulttext'                 => 'Fún ẹ̀kúnrẹ́rẹ́ nípa ṣíṣe ìwárí {{SITENAME}}, ẹ̀ wo [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Ẹ ṣ\'àwáàrí fun \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|gbogbo ojúewé tó bẹ̀rẹ̀ pẹ̀lu "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|gbogbo ojúewé tó jápọ̀ mọ́ "$1"]])',
'searchsubtitleinvalid'            => "Ẹ ti ṣ'àwáàrí fun '''$1'''",
'toomanymatches'                   => 'Àwọn ìbáramu ti pọ̀jù, ẹ jọ̀wọ́ ẹ gbìyànjú lọ́nà mìíràn',
'titlematches'                     => 'Àkọlé ojúewé báramu',
'notitlematches'                   => 'Kò sí àkọlé ojúewé tóbáramu',
'textmatches'                      => 'Ọ̀rọ̀ ojúewé tóbáramu:',
'notextmatches'                    => 'Kò sí ọ̀rọ̀ ojúewé tóbáramu',
'prevn'                            => '{{PLURAL:$1|$1}} tókọjá',
'nextn'                            => '{{PLURAL:$1|$1}} tókàn',
'prevn-title'                      => '{{PLURAL:$1|Èsì $1 sẹ́yìn|Àwọn èsì $1 sẹ́yìn}}',
'nextn-title'                      => '{{PLURAL:$1|Èsì $1 tóúnbọ̀|Àwọn èsì $1 tóúnbọ̀}}',
'shown-title'                      => '{{PLURAL:$1|Ìfihàn èsì $1|Ìfihàn àwọn èsì $1}} nínú ojúewé kọ̀ọ̀kan',
'viewprevnext'                     => 'Ẹ wo ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Àwọn àṣàyàn àwáàrí',
'searchmenu-exists'                => "'''Ojúewé tó ún jẹ́ \"[[:\$1]]\" wà lórí wiki yìí'''",
'searchmenu-new'                   => "'''Dá ojúewé \"[[:\$1]]\" sí orí wiki yìí!'''",
'searchhelp-url'                   => 'Help:Àwon àkóónú',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ẹ lọ sí àwọn ojúewé tí wọ́n ní àsopọ̀ yìí]]',
'searchprofile-articles'           => 'Àwọn ojúewé Àkóónú',
'searchprofile-project'            => 'Àwọn ojúewé Ìrànwọ́ àti Iṣẹ́-ọwọ́',
'searchprofile-images'             => 'Amóhùnmáwòrán',
'searchprofile-everything'         => 'Èyíkéyìí',
'searchprofile-advanced'           => 'Onígíga',
'searchprofile-articles-tooltip'   => 'Ṣàwáàrí nínú $1',
'searchprofile-project-tooltip'    => 'Ṣàwáàrí nínú $1',
'searchprofile-images-tooltip'     => 'Ṣàwáàrí fún faili',
'searchprofile-everything-tooltip' => 'Ṣàwáàrí nínú gbogbo àkóónú (pẹ̀lú àwọn ojúewé ọ̀rọ̀)',
'searchprofile-advanced-tooltip'   => 'Ṣàwáàrí nínú àwọn orúkọàyè pàtó',
'search-result-size'               => '$1 ({{PLURAL:$2|ọ̀rọ̀ 1|àwọn ọ̀rọ̀ $2}})',
'search-result-score'              => 'Ìbáramu: $1%',
'search-redirect'                  => '(àtúnjúwe $1)',
'search-section'                   => '(abala $1)',
'search-suggest'                   => 'Ṣé ẹ fẹ́: $1',
'search-interwiki-caption'         => 'Àwọn iṣẹ́-ọwọ́ mìràn',
'search-interwiki-default'         => 'èsì $1',
'search-interwiki-more'            => '(tókù)',
'search-mwsuggest-enabled'         => 'pẹ̀lú àbá',
'search-mwsuggest-disabled'        => 'láìsí àbá',
'search-relatedarticle'            => 'Tóbáramu',
'mwsuggest-disable'                => 'Ìdálẹ́kun àwọn àbá AJAX',
'searchrelated'                    => 'tóbáramu',
'searchall'                        => 'gbogbo',
'showingresults'                   => "Ìfihàn nísàlẹ̀ títí dé {{PLURAL:$1|èsì '''1'''|àwọn èsì '''$1'''}} láti ìbẹ̀rẹ̀ ní #'''$2'''.",
'showingresultsnum'                => "Ìfihàn nísàlẹ̀ {{PLURAL:$3|èsì '''1'''|àwọn èsì '''$3'''}} láti ìbẹ̀rẹ̀ ní #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Èsì '''$1''' nínú ''''$3'''|Àwọn èsì '''$1 - $2''' nínú '''$3'''}} fún '''$4'''",
'nonefound'                        => "'''Àkíyèsí''': Àwọn orúkọàyè mélòó níkan ni wọ́n jẹ́ wíwárí látìbẹ̀rẹ̀.
Ẹ ṣàlẹ̀mọ́wájú ìtọrọ yín pẹ̀lú ''gbogbo'' láti ṣàwárí gbogbo àkóónú (nínú àwọn ojúewé ọ̀rọ̀, àwọn àdàkọ, a.bẹ.bẹ.lọ), tàbí kí ẹ lo orúkọàyè tóyẹ gẹ́gẹ́ bíi àlẹ̀mọ́wájú.",
'search-nonefound'                 => 'Kò sí àwọn èsì kankan tóbáramu mọ́ ìtọrọ.',
'powersearch'                      => 'Ṣe àwárí',
'powersearch-legend'               => 'Àwárí kíkúnrẹ́rẹ́',
'powersearch-ns'                   => 'Àwárí nínú orúkọàyè:',
'powersearch-redir'                => 'Àkójọ àwọn àtúnjúwe',
'powersearch-field'                => 'Àwáàrí fún',
'powersearch-togglelabel'          => 'Ìyẹ̀wò:',
'powersearch-toggleall'            => 'Gbogbo wọn',
'powersearch-togglenone'           => 'Ìkankan',
'search-external'                  => 'Àwárí lóde',
'searchdisabled'                   => 'Ṣíṣàwárí nínú {{SITENAME}} wà ní dídálẹ́kun.
Ní báyìí ná ẹ le ṣàwárí lọ́dọ̀ Google.
Àkíyèsí pé àwọn atọ́ka wọn fún àkóónú {{SITENAME}} le mọ́ jẹ́ tuntun.',

# Quickbar
'qbsettings-none'          => 'Ìkankan',
'qbsettings-fixedleft'     => 'Kíkàn sí òsì',
'qbsettings-fixedright'    => 'Kíkàn sí ọ̀tún',
'qbsettings-floatingleft'  => 'Léfòó sí òsì',
'qbsettings-floatingright' => 'Léfòó sí ọ̀tún',

# Preferences page
'preferences'                 => 'Àwọn ìfẹ́ràn',
'mypreferences'               => 'Àwọn ìfẹ́ràn mi',
'prefs-edits'                 => 'Iye àwọn àtúnṣe:',
'prefsnologin'                => 'Ẹ kò tíì wọlé',
'prefsnologintext'            => 'Ẹ gbọ́dọ̀ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} wọlé]</span> láti to àwọn ìfẹ́ràn oníṣe.',
'changepassword'              => 'Ìyípadà ọ̀rọ̀ìpamọ́',
'prefs-skin'                  => 'Skin (Àwọ̀)',
'skin-preview'                => 'Àkọ́yẹ̀wò',
'prefs-math'                  => 'Ìṣirò',
'datedefault'                 => 'Kò sí ìfẹ́ràn',
'prefs-datetime'              => 'Ọjọ́ọdún àti àkókò',
'prefs-personal'              => 'Ọ̀rọ̀ nípa oníṣe',
'prefs-rc'                    => 'Àwọn àtúnṣe tuntun',
'prefs-watchlist'             => 'Ìmójútó',
'prefs-watchlist-days'        => 'Ọjọ́ láti fihàn nínú ìmójútó:',
'prefs-watchlist-days-max'    => 'Ọjọ́ 7 púpọ̀jùlọ',
'prefs-watchlist-edits'       => 'Iye àwọn àtúnṣe láti fìhàn nínú ìmójútó kíkúnrẹ́rẹ́:',
'prefs-watchlist-edits-max'   => 'Iye púpọ̀jùlọ: 1000',
'prefs-misc'                  => 'Oríṣiríṣi',
'prefs-resetpass'             => 'Ìyípadà ọ̀rọ̀ìpamọ́',
'prefs-email'                 => 'Àwọn àṣàyàn e-mail',
'prefs-rendering'             => 'Wíwò',
'saveprefs'                   => 'Ìmúpamọ́',
'resetprefs'                  => 'Ìpalẹ̀mọ́ àwọn àyípadà àìmúpamọ́',
'restoreprefs'                => 'Ìdápadà áwọn ìtò àtìbẹ̀rẹ̀',
'prefs-editing'               => 'Àtúnṣe ṣíṣẹ',
'prefs-edit-boxsize'          => 'Ìtóbi fèrèsé àtúnṣe',
'rows'                        => 'Àwọn ìtẹ̀lé gbọlọjọ:',
'columns'                     => 'Àwọn ìtẹ̀lé gogoro:',
'searchresultshead'           => 'Àwárí',
'resultsperpage'              => 'Àwọn èsì ní ojúewé kọ̀ọ̀kan:',
'contextlines'                => 'Ìye ìlà lórí èsì kọ̀ọ̀kan:',
'stub-threshold-disabled'     => 'Dídálẹ́kun',
'recentchangesdays'           => 'Iye ọjọ́ láti fihàn nínú àwọn àtúnṣe tuntun:',
'recentchangesdays-max'       => '{{PLURAL:$1|Ọjọ́|Ọjọ́}} $1 púpọ̀jùlọ',
'recentchangescount'          => 'Iye àtúnṣe láti fihàn látìbẹ̀rẹ̀:',
'savedprefs'                  => 'Àwọn ìfẹ́ràn yín ti jẹ́mímúpapọ́.',
'timezonelegend'              => 'Àsìkò ilẹ̀àmùrè:',
'localtime'                   => 'Àkókò ìbílẹ̀:',
'timezoneuseserverdefault'    => 'Lo ti ẹ̀rọ ìwọ̀fà',
'timezoneuseoffset'           => 'Òmíràn (ẹ tọ́ka ìyàtọ̀)',
'timezoneoffset'              => 'Ìyàtọ̀¹:',
'servertime'                  => 'Àsìkò ẹ̀rọ-ìwọ̀fà:',
'timezoneregion-africa'       => 'Áfríkà',
'timezoneregion-america'      => 'Amẹ́ríkà',
'timezoneregion-antarctica'   => 'Antarktikà',
'timezoneregion-arctic'       => 'Árktíkì',
'timezoneregion-asia'         => 'Ásíà',
'timezoneregion-atlantic'     => 'Òkun Atlantiki',
'timezoneregion-australia'    => 'Australia',
'timezoneregion-europe'       => 'Europe',
'timezoneregion-indian'       => 'Òkun India',
'timezoneregion-pacific'      => 'Òkun Pàsífíkì',
'allowemail'                  => 'Ìgbàláyè e-mail látọ̀dọ̀ àwọn oníṣe mìíràn',
'prefs-searchoptions'         => 'Àwọn àṣàyàn àwáàrí',
'prefs-namespaces'            => 'Àwọn orúkọàyè',
'default'                     => 'níbẹ̀rẹ̀',
'prefs-files'                 => 'Àwọn faili',
'prefs-custom-css'            => 'CSS àkànṣe',
'prefs-custom-js'             => 'JavaScript àkànṣe',
'prefs-emailconfirm-label'    => 'E-mail ìmúdájú:',
'prefs-textboxsize'           => 'Ìtóbi fèrèsé àtúnṣe',
'youremail'                   => 'E-mail:',
'username'                    => 'Orúkọ oníṣe:',
'uid'                         => 'Nọmba ìdámọ̀ fún oníṣe:',
'prefs-memberingroups'        => 'Ọ̀kan nínú {{PLURAL:$1|ẹgbẹ́|àwọn ẹgbẹ́}}:',
'prefs-registration'          => 'Àsìkò ìforúkọsílẹ́:',
'yourrealname'                => 'Orúkọ ganangan:',
'yourlanguage'                => 'Èdè:',
'yournick'                    => 'Ìtọwọ́bọ̀wé tuntun:',
'badsiglength'                => 'Ìtọwọ́bọ̀ yín ti gùnjù.
Kò gbodọ̀ ju $1 {{PLURAL:$1|àmìlẹ́tà|àwọn àmìlẹ́tà}} lọ.',
'yourgender'                  => 'Akọmbábo:',
'gender-unknown'              => 'Àláìtọ́kasí',
'gender-male'                 => 'Akọ',
'gender-female'               => 'Abo',
'email'                       => 'E-mail',
'prefs-help-realname'         => 'Orúkọ gangan kò pọndandan.
Tí ẹ bá fisílẹ̀ a ó lòó láti tóka iṣẹ́ yín fún yín.',
'prefs-help-email'            => 'Àdírẹ́ẹ̀sì e-mail yín kò ṣe dandan, ṣùgbọ́n yíò jẹ́ kí á le fi ọ̀rọ̀ìpamọ́ tuntun ránṣẹ́ sí yín tí ẹ bá gbàgbé ọ̀rọ̀ìpamọ́.
Bákannáà ẹ le è yàn láti jẹ́ kí àwọn ẹlòmíràn kó báyiín sọ̀rọ̀ láti ojúewé oníṣe tàbí ojúewé ọ̀rọ̀ yín láìfi taani yín hàn.',
'prefs-help-email-required'   => 'E-mail ṣe dandan.',
'prefs-info'                  => 'Ìfitónilétí tóṣekókó',
'prefs-i18n'                  => 'Ìṣekáríayé',
'prefs-signature'             => 'Ìtọwọ́bọ̀wé',
'prefs-dateformat'            => 'Irú ọjọ́ọdún',
'prefs-timeoffset'            => 'Ìyàtọ̀ àsìkò',
'prefs-advancedediting'       => 'Àwọn àṣàyàn onígíga',
'prefs-advancedrc'            => 'Àwọn àṣàyàn onígíga',
'prefs-advancedrendering'     => 'Àwọn àṣàyàn onígíga',
'prefs-advancedsearchoptions' => 'Àwọn àṣàyàn onígíga',
'prefs-advancedwatchlist'     => 'Àwọn àṣàyàn onígíga',
'prefs-displayrc'             => 'Ìfihàn àwọn àṣàyàn',
'prefs-diffs'                 => 'Àwọn ìyàtọ̀',

# User rights
'userrights'                  => 'Ìmójútó àwọn ẹ̀tọ́ oníṣe',
'userrights-lookup-user'      => 'Àkóso àwọn àdìpò oníṣe',
'userrights-user-editname'    => 'Ẹ tẹ orúkọ oníṣe kan:',
'editusergroup'               => 'Àtúnṣe àwọn ẹgbẹ́ oníṣe',
'editinguser'                 => "Ṣíṣàyípadà àwọn ẹ̀tọ́ oníṣe fún oníṣe '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Àtúnṣe àwọn ẹgbẹ́ oníṣe',
'saveusergroups'              => 'Ìmúpamọ́ àwọn ẹgbẹ́ oníṣe',
'userrights-groupsmember'     => 'Ọ̀kan nínú:',
'userrights-reason'           => 'Ìdíẹ̀:',
'userrights-no-interwiki'     => 'Ẹ kò ní ìyọ̀nda láti ṣàtúnṣe àwọn ẹ̀tọ́ oníṣe lórí àwọn wiki míràn.',
'userrights-nodatabase'       => 'Ibùdó dátà $1 kò sí tàbí kò sí lábẹ́lé.',
'userrights-nologin'          => 'Ẹ gbọ́dọ̀ [[Special:UserLogin|wọlé]] pẹ̀lú àpamọ́ alámòójútó láti pín àwọn ẹ̀tọ́ oníṣe.',
'userrights-notallowed'       => 'Àpamọ́ yín kò ní ìyọ̀nda láti pín àwọn ẹ̀tọ́ oníṣe.',
'userrights-changeable-col'   => 'Àwọn ẹgbẹ́ tí ẹ le túnṣe',
'userrights-unchangeable-col' => 'Àwọn ẹgbẹ́ tí ẹ kò le túnṣe',

# Groups
'group'               => 'Ìdìpọ̀:',
'group-user'          => 'Àwọn oníṣe',
'group-autoconfirmed' => 'Àwọn oníṣe aláàmúdájúarawọn',
'group-bot'           => 'Àwọn Bot',
'group-sysop'         => 'Àwọn alámùójútó',
'group-suppress'      => 'Àwọn alábẹ̀wò',
'group-all'           => '(gbogbo)',

'group-user-member'          => 'Oníṣe',
'group-autoconfirmed-member' => 'Oníṣe aláàmúdájúararẹ̀',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Alámùójútó',
'group-bureaucrat-member'    => 'Aláàmúṣe',
'group-suppress-member'      => 'Alábẹ̀wò',

'grouppage-user'          => '{{ns:project}}:Àwọn oníṣe',
'grouppage-autoconfirmed' => '{{ns:project}}:Àwọn oníṣe ìmúdájú fùnrawọn',
'grouppage-bot'           => '{{ns:project}}:Àwọn Bot',
'grouppage-sysop'         => '{{ns:project}}:Àwọn alámùójútó',
'grouppage-bureaucrat'    => '{{ns:project}}:Àwọn aláàmúṣe',
'grouppage-suppress'      => '{{ns:project}}:Alábẹ̀wò',

# Rights
'right-read'                  => 'Wo ojúewé',
'right-edit'                  => 'Àtúnṣe àwọn ojúewé',
'right-createpage'            => 'Dá ojúewé (tí kò jẹ́ ojúewé ìfọ̀rọ̀wérọ̀)',
'right-createtalk'            => 'Dá ojúewé ìfọ̀rọ̀wérọ̀',
'right-createaccount'         => 'Dá àpamọ́ oníṣe tuntun',
'right-minoredit'             => "Ṣ'àmì sí àwọn àtúnṣe bíi kékeré",
'right-move'                  => 'Yípò ojúewé',
'right-move-subpages'         => 'Yípò ojúewé pẹ̀lú àwọn ọmọ ojúewẹ́ rẹ̀',
'right-movefile'              => 'Yípò fáìlì',
'right-upload'                => 'Ìrùsókè àwọn faili',
'right-upload_by_url'         => 'Ìrùsókè àwọn faili láti URL kan',
'right-autoconfirmed'         => 'Àtúnṣe àwọn ojúewé aláàbò díẹ̀',
'right-delete'                => 'Pa àwọn ojúewé rẹ́',
'right-bigdelete'             => 'Pa àwọn ojúewé pẹ̀lú àwọn ìtàn títóbi rẹ́',
'right-browsearchive'         => 'Ṣàwárí àwọn ojúewé píparẹ́',
'right-undelete'              => 'Ìmúkúrò ìparẹ́ ojúewé kan',
'right-suppressionlog'        => 'Ẹ wo àwọn àkọọ́lẹ̀ àdáni',
'right-block'                 => 'Ìdínà àwọn oníṣe yìókù láti ṣàtúnṣe',
'right-blockemail'            => 'Ìdínà oníṣe kan láti fi e-mail ránṣẹ́',
'right-hideuser'              => 'Ìdínà orúkọ oníṣe kan, ìbòmọ́lẹ̀ rẹ̀ kúrò ní ìgboro',
'right-unblockself'           => 'Ìmúkúrò ìdínà ara wọn',
'right-editinterface'         => 'Àtúnṣe ìfojúkojú oníṣe',
'right-editusercssjs'         => 'Àtúnṣe àwọn fáìlì CSS àti JS ti àwọn oníṣe mìíràn',
'right-editusercss'           => 'Àtúnṣe àwọn fáìlì CSS ti àwọn oníṣe mìíràn',
'right-edituserjs'            => 'Àtúnṣe àwọn fáìlì JS ti àwọn oníṣe mìíràn',
'right-import'                => 'Ìkówọlé àwọn ojúewé láti ọ̀dọ̀ àwọn wiki míràn',
'right-importupload'          => 'Ìkówọlé àwọn ojúewé láti inú ìrùsókè fáìlì',
'right-userrights'            => 'Àtúnṣe gbogbo àwọn ẹ̀tọ́ oníṣe',
'right-userrights-interwiki'  => 'Àtúnṣe àwọn ẹ̀tọ́ oníṣe àwọn oníṣe lórí àwọn wiki míràn',
'right-siteadmin'             => 'Ìtìpa àti ìṣí ibùdó dátà',
'right-override-export-depth' => 'Ìkójáde àwọn ojúewé lámùúpọ̀ mọ́ àwọn ojúewé jíjápọ̀ títí dé ìbú 5',
'right-sendemail'             => 'Fi e-mail ránṣẹ́ sí àwọn oníṣe míràn',
'right-revisionmove'          => 'Ìyípò àwọn àtúnyẹ̀wò',
'right-disableaccount'        => 'Ìdálẹ́kun àwọn àkópamọ́',

# User rights log
'rightslog'     => 'Àwọn ẹ̀tọ́ oníṣe',
'rightslogtext' => 'Èyì ni àkọọ́lẹ̀ kan àwọn àtúnṣe sí àwọn ẹ̀tọ́ oníṣe.',
'rightsnone'    => '(kósí)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'wo ojúewé yìí',
'action-edit'                 => 'ṣàtúnṣe ojúewé yìí',
'action-createpage'           => 'dá ojúewé yìí',
'action-createtalk'           => 'dá ojúewé ìfọ̀rọ̀wérọ̀',
'action-createaccount'        => 'dá àpamọ́ oníṣe yìí',
'action-minoredit'            => 'fagisí àtúnṣe yìí gẹ́gẹ́ bíi kékeré',
'action-move'                 => 'yípò ojúewé yìí',
'action-move-subpages'        => 'yípò ojúewé yìí àti àwọn ọmọ ojúewé rẹ̀',
'action-movefile'             => 'yípò fáìlì yìí',
'action-upload'               => 'rùsókè fáìlì yìí',
'action-reupload'             => 'kọléṣórí fáìlì tó wà yìí',
'action-upload_by_url'        => 'rùsókè fáìlí yìí láti URL',
'action-writeapi'             => 'lo ìkọ API',
'action-delete'               => 'pa ojúewé yìí rẹ́',
'action-deleterevision'       => 'pa àtúnyẹ̀wò yìí rẹ́',
'action-deletedhistory'       => 'bojúwo ìtàn ìparẹ́ ojúewé yìí',
'action-browsearchive'        => 'ṣàwárí ojúewé píparẹ́',
'action-undelete'             => 'yípadà ìparẹ́ ojúewé yìí',
'action-suppressrevision'     => 'gbéwò tàbí yíṣẹ́yìn àtúnyẹ́wò pípamọ́ yìí',
'action-suppressionlog'       => 'wo àkọọ́lẹ̀ àdáni yìí',
'action-block'                => 'dínà oníṣe yìí láti ṣàtúnṣe',
'action-protect'              => 'yí irú àbò padà fún ojúewé yìí',
'action-import'               => 'kó ojúewé yìí wolé wá láti ọ̀dọ̀ wíkì mìíràn',
'action-userrights'           => 'àtúnṣe gbogbo àwọn ẹ̀tọ́ oníṣe',
'action-userrights-interwiki' => 'àtúnṣe àwọn ẹ̀tọ́ oníṣe àwọn oníṣe lórí àwọn wiki míràn',
'action-siteadmin'            => 'tìpa tàbí ṣí ibùdó dátà',
'action-revisionmove'         => 'yípò àwọn àtúnyẹ̀wò',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|àtúnṣe|àwọn àtúnṣe}} $1',
'recentchanges'                     => 'Àwọn àtúnṣe tuntun',
'recentchanges-legend'              => 'Àwọn àṣàyàn fún àtúnṣe tuntun',
'recentchanges-feed-description'    => 'Ẹ tẹ̀ lé àwọn àtúnṣe àìpẹ́ ọjọ́ sí wiki nínú àkótán feed yìí.',
'recentchanges-label-newpage'       => 'Àtúnṣe yìí dá ojúewé tuntun',
'recentchanges-label-minor'         => 'Àtùnṣe kékeré nìyí',
'recentchanges-label-bot'           => 'Rọ́bọ́ọ̀tì ni ó ṣe àtúnṣe yìí',
'rcnote'                            => "Lábẹ́ ni {{PLURAL:$1|àtúnṣe '''kan'''|àwọn àtúnṣe '''$1''' tí wọn gbẹ̀yìn}} láàrin {{PLURAL:$2|ọjọ́ kan|ọjọ́ '''$2'''}} sẹ́yìn ní ago $5, lọ́jọ́ $4.",
'rcnotefrom'                        => "Àwọn àtúnṣe láti ''''$2''' (títí dé '''$1''' hàn) lábẹ́.",
'rclistfrom'                        => 'Àfihàn àwọn àtúnṣe tuntun nípa bíbẹ̀rẹ̀ láti $1',
'rcshowhideminor'                   => '$1 àwọn àtúnṣe kékéèké',
'rcshowhidebots'                    => '$1 àwọn bot',
'rcshowhideliu'                     => '$1 àwọn oníṣe tótiwọlé',
'rcshowhideanons'                   => '$1 àwọn oníṣe aláìlórúkọ',
'rcshowhidepatr'                    => '$1 àwọn àtúnṣe ọlùṣọ́',
'rcshowhidemine'                    => '$1 àwọn àtúnṣe mi',
'rclinks'                           => "Ṣ'àfihàn àtúnṣe $1 tó kẹ̀yìn ní ọjọ́ $2 sẹ́yìn<br />$3",
'diff'                              => 'ìyàtọ̀',
'hist'                              => 'ìtàn',
'hide'                              => 'Ìbòmọ́lẹ̀',
'show'                              => 'Ìfihàn',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'T',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|Oníṣe $1|Àwọn oníṣe $1}} ún ṣe ìmójútó]',
'rc_categories'                     => 'Òpin sí àwọn ẹ̀ka (pínsọ́tọ̀ pẹ̀lú "|")',
'rc_categories_any'                 => 'Èyíkéyìí',
'newsectionsummary'                 => '/* $1 */ abala tuntun',
'rc-enhanced-expand'                => 'Ìfihàn ẹ̀kúnrẹ́rẹ́ (JavaScript pọndandan)',
'rc-enhanced-hide'                  => 'Ìfipamọ́ ẹ̀kúnrẹ́rẹ́',

# Recent changes linked
'recentchangeslinked'          => 'Àtúnṣe tó báramu',
'recentchangeslinked-feed'     => 'Àtúnṣe tó báramu',
'recentchangeslinked-toolbox'  => 'Àtúnṣe tó báramu',
'recentchangeslinked-title'    => 'Àtúnṣe tó báramu mọ́ "$1"',
'recentchangeslinked-noresult' => 'Kò sí ìyàtọ̀ nínú àwọn ojúewé ìjápọ̀ láàrin ìgbà tí ẹ sọ.',
'recentchangeslinked-summary'  => "Àkójọ àwọn àtúnṣe tí a sẹ̀sẹ̀ ṣe sí àwọn ojúewé tó jápọ̀ wá láti ojúewé pàtó kan (tàbí sí ìkan nìnú ẹ̀ka pàtó kan).
Àwọn ojúewé inú [[Special:Watchlist|ìmójútó yín]] jẹ́ '''kedere'''.",
'recentchangeslinked-page'     => 'Orúkọ ojúewé:',
'recentchangeslinked-to'       => 'Àfihàn àwọn àtúnṣe sí àwọn ojúewé tójápọ̀ mọ́ ojúewé ọ̀hún dípò',

# Upload
'upload'                => 'Ìrùsókè fáìlì',
'uploadbtn'             => 'Ìrùsókè fáìlì',
'uploadnologin'         => 'Ẹ kò tíì wọlé',
'uploadnologintext'     => 'Ẹ gbọ́dọ̀ [[Special:UserLogin|wọlè]] láti rùsókè faili.',
'uploaderror'           => 'Àsìse ìrùsókè',
'upload-permitted'      => 'Àwọn irú fáìlì yíyọ̀nda: $1',
'upload-preferred'      => 'Àwọn irú fáìlì fífẹ́ràn: $1',
'upload-prohibited'     => 'Àwọn irú fáìlì dídènà: $1',
'uploadlog'             => 'àkọọ́lẹ̀ ìrùsókè',
'uploadlogpage'         => 'Àkọsílẹ̀ ìrùsókè',
'uploadlogpagetext'     => 'Lábẹ́ yìí ni àkójọ àwọn ìrùsókè fáìlì áìpẹ́.
Ẹ wo [[Special:NewFiles|ọ̀dẹ̀dẹ̀ àwọn fáìlì tuntun]] fún àgbéwò aláfojúrí',
'filename'              => 'Ọrúkọ fáìlì',
'filedesc'              => 'Àkótán',
'fileuploadsummary'     => 'Àkótán:',
'filereuploadsummary'   => 'Àwọn àtúnṣe fáìlì:',
'filestatus'            => 'Ipò ẹ̀tọ́àwòkọ:',
'filesource'            => 'Orísun:',
'uploadedfiles'         => 'Àwọn fáìlì ajẹ́rírùsókè',
'ignorewarning'         => 'Fojúfo ìkìlọ̀ sì fi faili pamọ́',
'ignorewarnings'        => 'Fojúfo ìkìlọ̀ tó wù kó jẹ́',
'minlength1'            => 'Ó kéréjù àwọn orúkọ fáìlì gbọdọ̀ jẹ́ lẹ́tà kan.',
'badfilename'           => 'Orúkọ fáìlì ti yípadà sí "$1".',
'filetype-badmime'      => 'Àwọn fáìlì MIME irú "$1" kò jẹ́ gbígbà láyè láti rù wọ́n sókè.',
'empty-file'            => 'Fáílì tí ẹ fúnsílẹ̀ jẹ́ òfo nínú.',
'file-too-large'        => 'Fáílì tí ẹ fúnsílẹ̀ jẹ́ títóbijù',
'filename-tooshort'     => 'Orúkọ fáílì jẹ́ kíkéréjú.',
'filetype-banned'       => 'Irú fáílì yìí ti jẹ́ dídí lọ́nà.',
'illegal-filename'      => 'Orúkọ fáílì yìí kò jẹ́ gbígbàláàyè.',
'fileexists'            => "Fáìlì kan tilẹ̀ wà pẹ̀lú orúkọ yìí, ẹ jọ̀wọ́ ẹ yẹ '''<tt>[[:$1]]</tt>''' wò tí kò bá dá yín lójú pé ẹ fẹ́ yipadà.
[[$1|thumb]]",
'fileexists-extension'  => "Fáìlì kan wà pẹ̀lú orúkọ tó jọra: [[$2|thumb]]
* Orúkọ fáìlì ìrùsókè: '''<tt>[[:$1]]</tt>'''
* Orúkọ fáìlì tó wà: '''<tt>[[:$2]]</tt>'''
Ẹ jọ̀wọ́ ẹ mú orúkọ tó yàtọ̀.",
'file-exists-duplicate' => 'Fáìlì yìí jẹ́ àwòkọ kan {{PLURAL:$1|fáìlì yìí|àwọn fáìlì wọ̀nyí}}:',
'uploadwarning'         => 'Ìkìlọ̀ ìrùsókè',
'savefile'              => 'Ìmúpamọ́ fáìlì',
'uploadedimage'         => '"[[$1]]" ti jẹ́rírùsókè',
'overwroteimage'        => 'ṣe ìrùsókè àtúnyẹ̀wò tuntun "[[$1]]"',
'uploaddisabled'        => 'Dídálẹ́kun àwọn ìrùsókè.',
'uploaddisabledtext'    => 'Dídálẹ́kun àwọn ìrùsókè fáìlì.',
'uploadvirus'           => 'Fáìlì náà ní èràn nínú!
Ẹ̀kúnrẹ́rẹ́: $1',
'upload-source'         => 'Fáìlì ìsun',
'sourcefilename'        => 'Orúkọ fáìlì orísun:',
'sourceurl'             => 'Orísun URL:',
'destfilename'          => 'Ìdópin orúkọ fáìlì:',
'upload-maxfilesize'    => 'Púpọ̀jùlọ ìtóbi fáìlì: $1',
'upload-description'    => 'Ìjúwe fáìlì',
'upload-options'        => 'Àwọn àṣàyàn ìrùsókè',
'watchthisupload'       => "M'ójútó fáilì yìí",
'upload-success-subj'   => 'Ìjásírere ìrùsókè',
'upload-success-msg'    => 'Ìrùsókè yín láti [$2] ti jásírere. Ó ṣeéwò níbí: [[:{{ns:file}}:$1]]',
'upload-failure-subj'   => 'Ìṣòro ìrùsókè',
'upload-failure-msg'    => 'Ìṣòro kan wà pẹ̀lú fọ́ọ̀mù ìrùsókè yín [$2]:

$1',
'upload-warning-subj'   => 'Ìkìlọ̀ ìrùsókè',
'upload-warning-msg'    => 'Ìṣòro kan wà pẹ̀lú ìrùsókè yín láti [$2]. Ẹ le padà sí orí [[Special:Upload/stash/$1|fọ́ọ́mù ìrùsókè]] láti ṣàtúnṣe ìṣòro náà.',

'upload-proto-error'        => 'Prótókólù àìtọ́',
'upload-file-error'         => 'Àsiṣe ínú',
'upload-misc-error'         => 'Àsìṣe àìmọ̀ ìrùsókè',
'upload-too-many-redirects' => 'URL náà ní àwọn àtúnjúwe pípọ̀jùlọ',
'upload-unknown-size'       => 'Iye ìtóbi kòsí',
'upload-http-error'         => 'Àṣìṣe HTTP ti ṣẹlẹ̀: $1',

# Special:UploadStash
'uploadstash-refresh' => 'Àtúnraṣe àtòjọ àwọn fáìlì',

# img_auth script messages
'img-auth-accessdenied' => 'Ìdínà igbàwọlé',
'img-auth-nofile'       => 'Fáìlì "$1" kò sí.',
'img-auth-noread'       => 'Oníṣe kò ní ààyè láti wo "$1".',

# HTTP errors
'http-invalid-url'      => 'URL àìtọ́: $1',
'http-request-error'    => 'Ìtọrọ HTTP kùnà nítorí àsìṣe àìmọ̀.',
'http-read-error'       => 'Àṣìṣe kíkà HTTP.',
'http-timed-out'        => 'Àsìkò ìtọrọ HTTP ti tán.',
'http-curl-error'       => 'Àsìṣe ìmúwá URL: $1',
'http-host-unreachable' => 'Kò le dé ibi URL.',
'http-bad-status'       => 'Ìṣòro kan ṣẹlẹ̀ nìgbà ìtọrọ HTTP: $1, $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Kò le jámọ́ URL',

'license'            => 'Ìwé àṣẹ:',
'license-header'     => 'Ìwé àṣẹ',
'nolicense'          => 'Ìkankan kò jẹ́ yíyàn',
'license-nopreview'  => '(Àkọ́yẹ̀wò kò sí)',
'upload_source_file' => '(fáìlì lórí kọ̀mpútà yín)',

# Special:ListFiles
'listfiles_search_for'  => 'Ṣàwàrí fún orúkọ amóhùnmáwòrán:',
'imgfile'               => 'fáìlì',
'listfiles'             => 'Àkójọ 	fáìlì',
'listfiles_date'        => 'Ọjọ́ọdún',
'listfiles_name'        => 'Orúkọ',
'listfiles_user'        => 'Oníṣe',
'listfiles_size'        => 'Ìtóbi',
'listfiles_description' => 'Ìjúwe',
'listfiles_count'       => 'Àwọn àtẹ̀jáde',

# File description page
'file-anchor-link'          => 'Fáìlì',
'filehist'                  => 'Ìtàn fáìlì',
'filehist-help'             => 'Ẹ kan kliki lórí ọjọ́ọdún/àkókò kan láti wo fáìlì ọ̀ún bó ṣe hàn ní àkókò na.',
'filehist-deleteall'        => 'ìparẹ́ gbogbo wọn',
'filehist-deleteone'        => 'paarẹ́',
'filehist-current'          => 'lọ́wọ́',
'filehist-datetime'         => 'Ọjọ́ọdún/Àkókò',
'filehist-thumb'            => 'Àwòrán kékeré',
'filehist-thumbtext'        => 'Àwòrán kékeré fún ní $1',
'filehist-nothumb'          => 'Kò sí àwòrán kékeré',
'filehist-user'             => 'Oníṣe',
'filehist-dimensions'       => 'Àwọn ìwọ̀n',
'filehist-filesize'         => 'Ìtóbi fáìlì',
'filehist-comment'          => 'Àríwí',
'filehist-missing'          => 'Fáìlì kò sí',
'imagelinks'                => 'Àwọn ìjápọ̀ fáìlì',
'linkstoimage'              => '{{PLURAL:$1|Ojúewé kan yìí|Àwọn ojúewé $1 wọ̀nyí}} jápọ̀ mọ́ fáìlì yí:',
'linkstoimage-more'         => '{{PLURAL:$1|Ojúewé|Àwọn ojúewé}} tó pọ̀ju $1 lọ jápọ̀ mọ́ fáìlì yìí.
Àkòjọ ìṣàlẹ̀ yìí ṣàfihàn {{PLURAL:$1|ojúewé àkọ́kọ́|ojúewé $1 àkọ́kọ́}} tó jápọ̀ mọ́ fáìlì yìí nìkan.
[[Special:WhatLinksHere/$2|Àkójọ kíkúnrẹ́rẹ́]] wà nígbèéwọ́.',
'nolinkstoimage'            => 'Kò sí ojúewé tó jápọ̀ mọ́ fáìlì yìí.',
'morelinkstoimage'          => 'Ìwòrán [[Special:WhatLinksHere/$1|àwọn ìjápọ̀ míhìn]] sí fáìlì yìí.',
'sharedupload'              => 'Fáìlì yìí jẹ́ ìrùsókè láti $1 à ṣì le pin pẹ̀lú àwọn iṣẹ́owọ́ mìíràn tí wọ́n n lòó.',
'filepage-nofile'           => 'Kò sí fáìlì pẹ̀lú orúkọ yìí.',
'filepage-nofile-link'      => 'Kò sí fáìlì pẹ̀lú orúkọ yìí, sùgbọ́n ẹ le [$1 rùúsókè].',
'uploadnewversion-linktext' => 'Ẹ ṣe ìrùsókè àtúnṣe tuntun fáìlì yìí',
'shared-repo-from'          => 'láti $1',

# File reversion
'filerevert-comment' => 'Ìdíẹ̀:',

# File deletion
'filedelete'                  => 'Ìparẹ́ $1',
'filedelete-legend'           => 'Ìparẹ́ fáìlì',
'filedelete-comment'          => 'Ìdíẹ̀:',
'filedelete-submit'           => 'Paarẹ́',
'filedelete-success'          => "'''$1''' ti jẹ́ píparẹ́.",
'filedelete-nofile'           => "'''$1''' kò sí.",
'filedelete-otherreason'      => 'Ìdíẹ̀ míràn/àfikún:',
'filedelete-reason-otherlist' => 'Ìdí mìíràn',
'filedelete-reason-dropdown'  => '*Àwọn ìdí fún ìparẹ́ 
**Ìtakùnà ẹ̀tọ́àwòkọ
**Fáìlì ẹ̀mejì',
'filedelete-edit-reasonlist'  => 'Àtúnṣe àwọn ìdí ìparẹ́',

# MIME search
'mimesearch' => 'àwáàrí pẹ́lú MIME',
'mimetype'   => 'irú MIME:',
'download'   => 'ìrùsílẹ̀',

# List redirects
'listredirects' => 'Àkójọ àwọn àtúnjúwe',

# Unused templates
'unusedtemplates'    => 'Àdàkọ àìlò',
'unusedtemplateswlh' => 'àwọn ìjápọ̀ míràn',

# Random page
'randompage' => 'Ojúewé àrìnàkò',

# Random redirect
'randomredirect' => 'Àtúndarí àrìnàkò',

# Statistics
'statistics'                   => 'Àwọn statistiki',
'statistics-header-pages'      => 'Àwọn statistiki ojúewé',
'statistics-header-edits'      => 'Àwọn statistiki àtúnṣe',
'statistics-header-views'      => 'Ẹ wo àwọn statístíkì',
'statistics-header-users'      => 'Àwọn statistiki oníṣe',
'statistics-header-hooks'      => 'Àwọn statistiki míràn',
'statistics-articles'          => 'Àwọn ojúewé àkóónú',
'statistics-pages'             => 'Àwọn ojúewé',
'statistics-pages-desc'        => 'Gbogbo àwọn ojúewé inú wiki, lámùpọ́ọ̀ mọ́ àwọn ojúewé ọ̀rọ̀, àwọn àtúnjúwe, at. bb.lo',
'statistics-files'             => 'Àwọn fáìlì ajẹ́rírùsókè',
'statistics-edits'             => 'Àwọn iye àtúnṣe ojúewé láti ìgbà tí {{SITENAME}} ti bẹ̀rẹ̀',
'statistics-edits-average'     => 'Iye àtúnṣe apínlàrin fún ojúewé kọ̀ọ̀kan',
'statistics-views-total'       => 'Àpapọ̀ iye ìwò',
'statistics-views-peredit'     => 'Iye ìwò fún àtúnṣe kọ̀ọ̀kan',
'statistics-users'             => '[[Special:ListUsers|Àwọn oníṣe]] ajẹ́fífilórúkọsílẹ̀',
'statistics-users-active'      => 'Àwọn oníṣe agbéṣe',
'statistics-users-active-desc' => 'Àwọn oníṣe tí wọ́n ti ṣe ìgbéṣe kan ní {{PLURAL:$1|ọjọ́ kan|ọjọ́ $1}} sẹ́yìn',
'statistics-mostpopular'       => 'Àwọn ojúewé tí wọ́n jẹ́ wíwò jùlọ',

'disambiguations'     => 'Àwọn ojúewé ìpínsọ́tọ̀',
'disambiguationspage' => 'Template:ojútùú',

'doubleredirects'            => 'Àwọn àtúnjúwe ẹ̀mẹjì',
'double-redirect-fixed-move' => '[[$1]] ti yípò padà.
Ó ti ṣe àtúnjúwe sí [[$2]].',

'brokenredirects'        => 'Àwọn àtúnjúwe tótigé',
'brokenredirects-edit'   => 'àtúnṣe',
'brokenredirects-delete' => 'ìparẹ́',

'withoutinterwiki'        => 'Àwọn ojúewé tí kò ní ìjápọ̀ èdè',
'withoutinterwiki-legend' => 'Àlẹ̀mọ́wájú',
'withoutinterwiki-submit' => 'Ìfihàn',

'fewestrevisions' => 'Àwọn ojúewé pẹ̀lú àwọn àtúnyẹ̀wọ̀ tókéréjù',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '{{PLURAL:$1|ẹ̀ka|àwọn ẹ̀ka}} $1',
'nlinks'                  => '{{PLURAL:$1|ìjápọ̀|àwọn ìjápọ̀}} $1',
'nmembers'                => '{{PLURAL:$1|ará|àwọn ará}} $1',
'nrevisions'              => '{{PLURAL:$1|àtúnyẹ̀wò|àwọn àtúnyẹ̀wò}} $1',
'nviews'                  => '{{PLURAL:$1|Ìwò|Àwọn ìwò}} $1',
'lonelypages'             => 'Àwọn ojúewé aláìlóbìí',
'lonelypagestext'         => 'Àwọn ojúewé wọ̀nyí kò ní ìjápọ̀ láti ọ̀dọ̀ tàbí ìdàpọ̀ mọ́ àwọn ojúewé míràn nínú {{SITENAME}}.',
'uncategorizedpages'      => 'Àwọn ojúewé aláìlẹ́ka',
'uncategorizedcategories' => 'Àwọn ẹ̀ka aláìlẹ́ka',
'uncategorizedimages'     => 'Àwọn faili aláìlẹ́ka',
'uncategorizedtemplates'  => 'Àwọn àdàkọ aláìlẹ́ka',
'unusedcategories'        => 'Ẹ̀ka àìlò',
'unusedimages'            => 'Faili àìlò',
'popularpages'            => 'Ojúewé tógbajúmọ̀',
'wantedcategories'        => 'Àwọn ẹ̀ka aláìní',
'wantedpages'             => 'Àwọn ojúewé àìsí',
'wantedfiles'             => 'Àwọn fáìlì àìsí',
'wantedtemplates'         => 'Àwọn àdàkọ àìsí',
'mostlinked'              => 'Àwọn ojúewé tó ní ìjápọ̀ mọ́ jùlọ',
'mostlinkedcategories'    => 'Àwọn ẹ̀ka tó ní ìjápọ̀ mọ́ jùlọ',
'mostlinkedtemplates'     => 'Àwọn àdákọ tó ní ìjápọ̀mọ́ jùlọ',
'mostcategories'          => 'Àwọn ojúewé pẹ̀lú àwọn ẹ̀ka tópọ̀jùlọ',
'mostimages'              => 'Àwọn fáìlì tó ní ìjápọ̀mọ́ jùlọ',
'mostrevisions'           => 'Àwọn ojúewé pẹ̀lu àwọn àtúnyẹ̀wò tópọ̀jùlọ',
'prefixindex'             => 'Gbogbo ojúewé tó ní ìtọ́ka ìpele',
'shortpages'              => 'Àwọn ojúewé kúkúrú',
'longpages'               => 'Ojúewé gúngùn',
'deadendpages'            => 'Àwọn ojúewé aláìníjàápọ́',
'deadendpagestext'        => 'Àwọn ojúewé wọ̀nyí kò jápọ̀ mọ́ àwọn ojúewé míràn ní {{SITENAME}}.',
'protectedpages'          => 'Àwọn ojúewé aláàbò',
'protectedtitles'         => 'Àwọn àkọlé ajẹ́dídáàbòbò',
'listusers'               => 'Àkójọ àwọn oníṣe',
'usereditcount'           => '{{PLURAL:$1|Àtúnṣe|Àwọn àtúnṣe}} $1',
'usercreated'             => 'Ó jẹ́ dídá ní ọjọ́ $1 ní ago $2',
'newpages'                => 'Àwọn ojúewé tuntun',
'newpages-username'       => 'Orúkọ oníṣe:',
'ancientpages'            => 'Àwọn ojúewé tópẹ́jùlọ',
'move'                    => 'Ìyípòdà',
'movethispage'            => 'Yípò ojúewé yìí',
'pager-newer-n'           => '{{PLURAL:$1|tuntunjùlọ 1|tuntunjùlọ $1}}',
'pager-older-n'           => '{{PLURAL:$1|pípẹ́jùlọ 1|pípẹ́jùlọ $1}}',

# Book sources
'booksources'               => 'Àwọn orísun ìwé',
'booksources-search-legend' => 'Àwáàrí fún áwọn ìwé ìtọ́ka',
'booksources-go'            => 'Lọ',

# Special:Log
'specialloguserlabel'  => 'Oníṣe:',
'speciallogtitlelabel' => 'Àkọlé:',
'log'                  => 'Àwọn àkọọ́lẹ̀',
'all-logs-page'        => 'Gbogbo àkọsílẹ̀',

# Special:AllPages
'allpages'          => 'Gbogbo ojúewé',
'alphaindexline'    => '$1 dé $2',
'nextpage'          => 'Ojúewé tókàn ($1)',
'prevpage'          => 'Ojúewé tókọjá ($1)',
'allpagesfrom'      => 'Ìfihàn àwọn ojúewé nípa bíbẹ̀rẹ̀ láti:',
'allpagesto'        => 'Ìfihàn àwọn ojúewé tó parí pẹ̀lú:',
'allarticles'       => 'Gbogbo ojúewé',
'allinnamespace'    => 'Gbogbo ojúewé ($1 namespace)',
'allnotinnamespace' => 'Gbogbo ojúewé (tí kòsí ní $1 namespace)',
'allpagesprev'      => 'Tókọjá',
'allpagesnext'      => 'Tóúnbọ̀',
'allpagessubmit'    => 'Lọ',
'allpagesprefix'    => 'Ìgbéhàn àwọn ojúewé tóbẹ̀rẹ̀ pẹ̀lú:',

# Special:Categories
'categories'                    => 'Àwọn ẹ̀ka',
'categoriesfrom'                => 'Ìfihàn àwọn ẹ̀ka nípa bíbẹ̀rẹ̀ láti:',
'special-categories-sort-count' => 'títò bíi nọ́mbà',
'special-categories-sort-abc'   => 'títò bíi lẹ́tà',

# Special:DeletedContributions
'deletedcontributions'             => 'Àwọn àfikún píparẹ́ oníṣe',
'deletedcontributions-title'       => 'Àwọn àfikún píparẹ́ oníṣe',
'sp-deletedcontributions-contribs' => 'àwọn àfikún',

# Special:LinkSearch
'linksearch'      => "Àwọn ìjápọ̀ s'íta",
'linksearch-ns'   => 'Orúkọàyè:',
'linksearch-ok'   => 'Ṣàwárí',
'linksearch-line' => '$1 jẹ́ jíjápọ̀ láti $2',

# Special:ListUsers
'listusersfrom'      => 'Ìfihàn àwọn oníṣe nípa bíbẹ̀rẹ̀ láti:',
'listusers-submit'   => 'Ìfihan',
'listusers-noresult' => 'Kò rí oníṣe kankan.',
'listusers-blocked'  => '(dídínà)',

# Special:ActiveUsers
'activeusers-count'      => '{{PLURAL:$1|Àtúnṣe|Àwọn àtúnṣe}} $1 ní {{PLURAL:$3|ọjọ́|ọjọ́}} $3 sẹ́yìn',
'activeusers-from'       => 'Ìfihàn àwọn oníṣe nípa bíbẹ̀rẹ̀ láti:',
'activeusers-hidebots'   => 'Ìbòmọ́lẹ̀ àwọn bọt',
'activeusers-hidesysops' => 'Ìbòmọ́lẹ̀ àwọn olùmójútó',
'activeusers-noresult'   => 'Kò rí oníṣe kankan.',

# Special:Log/newusers
'newuserlogpage'           => 'Àkọsílẹ̀ ìdá oníṣe',
'newuserlog-byemail'       => 'ọ̀rọ̀ìpamọ́ jẹ́ fífiránṣẹ́ pẹ̀lú e-mail',
'newuserlog-create-entry'  => 'Àpamọ́ oníṣe tuntun',
'newuserlog-create2-entry' => 'dídá àpamọ́ tuntun $1',

# Special:ListGroupRights
'listgrouprights'                 => 'Àwọn ẹ̀tọ́ ẹgbẹ́ oníṣe',
'listgrouprights-key'             => '* <span class="listgrouprights-granted">Ẹ̀tọ́ tó ní</span>
* <span class="listgrouprights-revoked">Ẹ̀tọ́ tí kò ní mọ́</span>',
'listgrouprights-group'           => 'Ẹgbẹ́',
'listgrouprights-rights'          => 'Àwọn ẹ̀tọ́',
'listgrouprights-helppage'        => 'Help:Àwọn ẹ̀tọ́ ẹgbẹ́',
'listgrouprights-members'         => '(àkójọ àwọn ọmọ ẹgbẹ́)',
'listgrouprights-addgroup'        => 'Ṣàfikún {{PLURAL:$2|ẹgbẹ́|àwọn ẹgbẹ́}}: $1',
'listgrouprights-removegroup'     => 'Múkúrò {{PLURAL:$2|ẹgbẹ́|àwọn ẹgbẹ́}}: $1',
'listgrouprights-addgroup-all'    => 'Ṣàfikún gbogbo ẹgbẹ́',
'listgrouprights-removegroup-all' => 'Mú gbogbo ẹgbẹ́ kúrò',

# E-mail user
'emailuser'        => 'Ẹ fi e-mail ránṣẹ́ sí oníṣe yìí',
'emailpage'        => 'E-mail sí oníṣe',
'defemailsubject'  => 'e-mail {{SITENAME}}',
'noemailtitle'     => 'Kò sí àdírẹ́ẹ̀sì e-mail',
'noemailtext'      => 'Oníṣe yìí kò tìí ṣètò àdírẹ́ẹ̀sì e-mail tótọ́ kankan.',
'nowikiemailtitle' => 'E-mail kankan kò jẹ́ gbígbà láyè',
'email-legend'     => 'Fi e-mail ránṣẹ́ sí oníṣe {{SITENAME}} mìíràn',
'emailfrom'        => 'Láti:',
'emailto'          => 'Sí:',
'emailsubject'     => 'Oríọ̀rọ̀:',
'emailmessage'     => 'Ìránṣẹ́:',
'emailsend'        => 'Firánṣẹ́',
'emailccsubject'   => 'Àwòkọ ìránṣẹ́ yín sí $1: $2',
'emailsent'        => 'E-mail ti jẹ́ fìfiránṣẹ́',
'emailsenttext'    => 'Ìránṣẹ̀ e-mail yín ti jẹ́ fífiránṣé.',
'emailuserfooter'  => 'E-mail yìí wá látọ̀dọ̀ $1 sí $2 pẹ̀lú ìfigbéṣe "E-mail oníṣe" ní {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Ìmójútó mi',
'mywatchlist'          => 'Ìmójútó mi',
'watchlistfor2'        => 'Fún $1 $2',
'nowatchlist'          => 'Ẹ kò ní ohun kankan nínú ìmójútó yín.',
'watchlistanontext'    => 'Ẹ jọ̀wọ́ $1 láti wò tàbí ṣàtúnṣe àwọn ohun inú ìmójútó yín.',
'watchnologin'         => 'Ẹ kò tíì wọlé',
'watchnologintext'     => 'Ẹ gbọ́dọ̀ [[Special:UserLogin|wọlè]] láti ṣàtúnṣe ìmójútó yín.',
'addedwatch'           => 'Ti fikún sí ìmójútó',
'addedwatchtext'       => "A ti ṣ'àfikún \"[[:\$1]]\" sí [[Special:Watchlist|ìmójútó]] yín.
A óò ṣ'àkójọ àwọn àtúnṣe ọjọ́wajú sí ojúewé yìí àti ojúewé ọ̀rọ̀ rẹ̀ sí bẹ̀. Bákanáà ojúewé náà yíò hàn '''kedere''' nìnú [[Special:RecentChanges|àkójọ àwọn àtúnṣe tuntun]] kó le ba à rọrùn láti rí.",
'removedwatch'         => 'Ti mú kúrò nínú ìmójútó',
'removedwatchtext'     => 'A ti yọ ojúewé "[[:$1]]" kúrò nínú [[Special:Watchlist|ìmójútó yín]].',
'watch'                => 'Ìmójútó',
'watchthispage'        => "M'ójútó ojúewé yi",
'unwatch'              => "Já'wọ́ ìmójútó",
'unwatchthispage'      => "Já'wọ́ ìmójútó ojúewé yi",
'notanarticle'         => 'Kìí ṣe ojúewé àkóónú',
'notvisiblerev'        => 'Àtúnyẹ̀wò gbígbẹ̀yìn látọwọ́ oníṣe míràn ti jẹ́ píparẹ́',
'watchnochange'        => 'Kò sí ìkankan nínú àwọn ohun ìmójútó yín tó jẹ́ títúnṣe láàrin àsìkò títẹ́kalẹ̀.',
'watchlist-details'    => '{{PLURAL:$1|Ojúewé $1|Àwọn ojúewé $1}} ló wà nínú ìmójútó yín, tí a kò bá ka àwọn ojúewé ọ̀rọ̀.',
'wlheader-enotif'      => '* Ìfitónilétí e-mail wà ní gbígbàláyè.',
'wlheader-showupdated' => "* Àwọn ojúewé tí wọn ti yípadà látìgbà tí ẹ ṣàbẹ̀wò wọn gbẹ̀yìn jẹ́ fífihàn ní ''kedere'''",
'watchmethod-recent'   => 'únwo àwọn àtúnṣe tuntun fún àwọn ojúewé mímójútó',
'watchmethod-list'     => 'únwo àwọn ojúewé mímójútó fún àwọn àtúnṣe tuntun',
'watchlistcontains'    => 'Àwọn ìmójútó yín ní {{PLURAL:$1|ojúewé|àwọn ojúewé}} $1 nínú.',
'iteminvalidname'      => "Ìṣòro wà pẹ̀lú '$1', orúkọ àìtọ́...",
'wlnote'               => "Lábẹ́ {{PLURAL:$1|ni àtúnṣe tó gbẹ̀yìn wà|ni àwọn àtúnṣe '''$1''' tí wọn gbẹ̀yìn wà}} láàrin {{PLURAL:$2|wákàtí kan|wákàtí '''$2'''}} sẹ́yìn.",
'wlshowlast'           => 'Ìfihàn wákàtí $1 sẹ́yìn ọjọ́ $2 sẹ́yìn $3',
'watchlist-options'    => 'Àṣàyàn ìmójútọ́',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ó ún mójútó...',
'unwatching' => 'Jíjáwọ́ ìmójútó...',

'enotif_mailer'                => 'Olùránṣẹ́ ìfitọ́nilétí {{SITENAME}}',
'enotif_reset'                 => 'Fàlà sí gbogbo àwọn ojúewé bíi bíbẹ̀wò',
'enotif_newpagetext'           => 'Ojúewé tuntun nìyí.',
'enotif_impersonal_salutation' => 'Oníṣe {{SITENAME}}',
'changed'                      => 'títúnṣẹ',
'created'                      => 'dídá',
'enotif_subject'               => '$PAGEEDITOR $CHANGEDORCREATED ojúewé $PAGETITLE lórí {{SITENAME}}',
'enotif_lastvisited'           => 'Ẹ wo $1 fún gbogbo àwọn àtúnṣe látìgbà ìbẹ̀wò yín gbẹ̀yìn.',
'enotif_lastdiff'              => 'Ẹ wo $1 láti wo àtúnṣe yìí.',
'enotif_anon_editor'           => 'oníṣe aláìlórúkọ $1',
'enotif_body'                  => '$WATCHINGUSERNAME ọ̀wọ́n,


Ojúewé {{SITENAME}} $PAGETITLE ti jẹ́ $CHANGEDORCREATED lọ́jọ́ $PAGEEDITDATE látọwọ́ $PAGEEDITOR, ẹ wo $PAGETITLE_URL fún àtúnyẹ̀wò rẹ̀ báyìí.

$NEWPAGE

Àkótán olùtúnṣe: $PAGESUMMARY $PAGEMINOREDIT

Ìpàdé pẹ̀lú olùtúnṣe:
lẹ́tà: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Kò ní sí ìfitọ́nilétí míràn mọ́ fún àyípadà ọjọ́ọwájú àyàfi tí ẹ bá ṣàbẹ̀wò ojúewé yìí.
Ẹ sì tún le ṣe àtúntò àwọn àmì ìfitọ́nilétí fún gbogbo àwọn ojúewé mímójútó nínú ìmójútó yín.

             Sístẹ́mù ìfitọ́nilétí {{SITENAME}} yín 

--
Láti ṣèyípadà ìṣètò ìmójútó yín, ẹ lọ sí
{{fullurl:{{#special:Watchlist}}/edit}}

Láti ṣèparẹ́ ojúewé náà kúrò nínú ìmjútó yín, ẹ lọ sí
$UNWATCHURL

Fún ìrànwọ́:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Ìparẹ́ ojúewé',
'confirm'                => 'Ìmúdájú',
'excontent'              => "àkóónú rẹ̀ jẹ́: '$1'",
'exblank'                => 'ojúewé jẹ́ òfo',
'delete-confirm'         => 'Ìparẹ́ "$1"',
'delete-legend'          => 'Paárẹ́',
'historywarning'         => "'''Ìkìlọ̀:''' Ojúewé tí ẹ fẹ́ parẹ́ ní ìtàn pẹ̀lú {{PLURAL:$1|àtúnyẹ̀wò|àwọn àtúnyẹ̀wò}} $1:",
'confirmdeletetext'      => 'Ẹ ti fẹ́ ṣe ìparẹ́ ojúewé kan pọ̀mọ́ gbogbo ìtàn rẹ̀.
Ẹ jọ̀wọ́ ẹ fìdájú pé èyí ni èrò yín, pé ohun tí yíò ṣẹlẹ̀ yé yín, àti pé ẹ ún ṣe èyí gẹ́gẹ́ bí
[[{{MediaWiki:Policy-url}}|ìlànà]] ṣe làá kalẹ̀.',
'actioncomplete'         => 'Ìmúṣe ti parí',
'actionfailed'           => 'Ìkùnà ìgbéṣe',
'deletedtext'            => 'A ti pa "<nowiki>$1</nowiki>" rẹ́.
Ẹ wo $2 fún àkọọ́lẹ̀ àwọn ìparẹ́ àìpẹ́.',
'deletedarticle'         => 'A ti pa "[[$1]]" rẹ́',
'dellogpage'             => 'Àkọsílẹ̀ ìparẹ́',
'deletionlog'            => 'àkọsílẹ̀ ìparẹ́',
'deletecomment'          => 'Ìdíẹ̀:',
'deleteotherreason'      => 'Àwọn ìdí mìíràn:',
'deletereasonotherlist'  => 'Ìdí mìíràn',
'deletereason-dropdown'  => '*Àwọn ìdí tówọ́pọ̀ fún ìparẹ́
**Olùkọ̀wé ló tọrọ
**Àìtẹ̀lé ẹ́tọ́àwòkọ
**Ìbàjẹ́',
'delete-edit-reasonlist' => 'Àtúnṣe àwọn ìdí ìparẹ́',

# Rollback
'rollbacklink' => 'yísẹ́yìn',
'editcomment'  => "Àkótán àtúnṣe náà jẹ́: \"''\$1''\".",

# Protect
'protectlogpage'              => 'Àkọsílẹ̀ àbò',
'protectedarticle'            => 'ti dá àbò bo "[[$1]]"',
'modifiedarticleprotection'   => 'ṣe àyípadà ipò àbò fún "[[$1]]"',
'unprotectedarticle'          => '"[[$1]]" aláìjẹ́dídáàbòbò',
'prot_1movedto2'              => '[[$1]] ti yípò sí [[$2]]',
'protect-legend'              => 'Ìmúdájú ìdábòbò',
'protectcomment'              => 'Ìdíẹ̀:',
'protectexpiry'               => 'Ìparí:',
'protect_expiry_invalid'      => 'Àkókò ìparí kò ní ìbámu.',
'protect_expiry_old'          => 'Ìgbà tó ti kọjá ni ìparí.',
'protect-text'                => "Ẹ lè wo, bẹ́ ẹ̀ sìni ẹ lè ṣ'àtúnṣe ibi àbò níbí fún ojúewé '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Àpamọ́ yín kò ní àyè láti ṣ'àtúnṣe àwọn ibi àbò.
Bí a ṣe to ojúewé '''$1''' nì yí:",
'protect-cascadeon'           => 'Ojúewé yìí jẹ́ dídàbòbò lọ́wọ́lọ́wọ́ nítorí ó jẹ́ mímúpọ nínú {{PLURAL:$1|ojúewé ìsàlẹ̀ yìí, tó ní|àwọn ojúewé ìsàlẹ̀ wọ̀nyí, tí wọ́n ní}} àbò onípele tó ún ṣiṣé.
Ẹ le paradà ìpele àbò ojúewé yìí, sùgbọ́n kò ní nípa lórí àbò onípele náà.',
'protect-default'             => 'Ẹ gba gbogbo àwọn oníṣe láàyè',
'protect-fallback'            => 'Ìyọ̀nda "$1" pọn dandan',
'protect-level-autoconfirmed' => 'Dínà àwọn oníṣe tuntun àti tíkòforúkọ sílẹ́',
'protect-level-sysop'         => 'Alámùójútó nìkan',
'protect-summary-cascade'     => 'títẹ̀léra',
'protect-expiring'            => 'parí ní $1 (UTC)',
'protect-expiry-indefinite'   => 'kòdájú',
'protect-cascade'             => 'Àbò títẹ̀léra wọn - ó ún dá àbò bo àwọn ojúewé yìówù tí wọ́n bá jẹ́ mímúpọ̀ mọ́ ojúewé yìí.',
'protect-cantedit'            => "Ẹ kò le è ṣe àyípadà ibi àbò ojúewé yìí, nítorípé a kò yọ̀nda yín láti ṣ'àtúnṣe rẹ̀.",
'protect-othertime'           => 'Àkókò míràn:',
'protect-othertime-op'        => 'àkókò míràn',
'protect-otherreason'         => 'Ìdí míràn/àfikún:',
'protect-otherreason-op'      => 'Ìdí míràn',
'protect-expiry-options'      => '1 wákàtí:1 hour,1 ọjọ́:1 day,1 ọ̀ṣẹ̀:1 week,2 ọ̀ṣẹ̀:2 weeks,1 osù:1 month,3 osù:3 months,6 osù:6 months,1 ọdún:1 year,láìlópin:infinite',
'restriction-type'            => 'Ìyọ̀nda:',
'restriction-level'           => 'Ibi ìpààlà:',
'minimum-size'                => 'Ìtóbi kíkéréjúlọ',
'maximum-size'                => 'Ìtóbi púpọ̀jùlọ:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Àtúnṣe',
'restriction-move'   => 'Ìyípò',
'restriction-create' => 'Ìṣèdá',
'restriction-upload' => 'Ìrùsókè',

# Restriction levels
'restriction-level-autoconfirmed' => 'aláàbò díẹ̀',

# Undelete
'undelete'                   => 'Wíwò àwọn ojúewé tí a ti parẹ́',
'undeletepage'               => 'Wíwò àti dídápadà àwọn ojúewé tí a ti parẹ́',
'undeletepagetitle'          => "'''Ìwọ̀nyí ni àwọn àtúnyẹ̀wò píparẹ́ ti [[:$1|$1]]'''.",
'viewdeletedpage'            => 'Wíwò àwọn ojúewé tí a ti parẹ́',
'undeletepagetext'           => '{{PLURAL:$1|Ojúewé yìí ti jẹ́ píparẹ́ ṣùgbọ́n ó sì wà nínú àpòìkópamọ́. Ó sì ṣe é mú padà.|Àwọn ojúewé $1 wọ̀nyí ti jẹ́ píparẹ́ ṣùgbọ́n wọn sì wà nínú àpòìkópamọ́. Wọn sì ṣe é mú padà.}} Àpòìkópamọ́ náà ṣe é fọ̀nù nígbàkúgbà.',
'undelete-fieldset-title'    => 'Ìdápadà àwọn àtúnyẹ̀wò',
'undeleterevisions'          => '{{PLURAL:$1|Àtúnyẹ̀wò|Àwọn àtúnyẹ̀wò}} $1 ti jẹ́ kíkó sínú àpòìkópamọ́',
'undelete-revision'          => 'Àtúnyẹ̀wò píparẹ́ ti $1 (ní ọjọ́ $4, ní ago $5) látọwọ́ $3:',
'undelete-nodiff'            => 'Kò rí àtúnyẹ̀wò tẹ́lẹ̀ kankan.',
'undeletebtn'                => 'Dápadà',
'undeletelink'               => 'wò/dápadà',
'undeleteviewlink'           => 'wo',
'undeletereset'              => 'Ìtúnṣètò',
'undeletecomment'            => 'Ìdíẹ̀:',
'undeletedarticle'           => 'a ti dá "[[$1]]" padà',
'undeletedrevisions'         => '{{PLURAL:$1|Àtúnyẹ̀wò 1|Àwọn àtúnyẹ̀wò $1}} ti jẹ́ dídápadà',
'undeletedrevisions-files'   => '{{PLURAL:$1|Àtúnyẹ̀wò 1|Àwọn àtúnyẹ̀wò $1}} àti {{PLURAL:$2|fáìlì 1|àwọn fáìlì $2}} ti jẹ́ dídápadà',
'undeletedfiles'             => '{{PLURAL:$1|Fáílì 1|Àwọn fáìlì $1}} ti jẹ́ dídápadà',
'undeletedpage'              => "'''$1 ti jẹ́ dídápadà'''

Ẹ wo [[Special:Log/delete|àkọọ́lẹ̀ ìparẹ́]] fún àkọpamọ́ àwọn ìparẹ́ àti ìdápadà àìpẹ́.",
'undelete-header'            => 'Ẹ wo [[Special:Log/delete|àkọọ́lẹ̀ ìparẹ́]] fún àwọn ojúewé píparẹ́ láìpẹ́',
'undelete-search-box'        => 'Ṣàwárí àwọn ojúewé píparẹ́',
'undelete-search-prefix'     => 'Ìfihàn ojúewé tó bẹ̀rẹ̀ pẹ̀lú:',
'undelete-search-submit'     => 'Ṣàwárí',
'undelete-error-long'        => 'Àwọn àsìṣe ṣẹlẹ̀ nígbà ìdápadà fáìlì náà:

$1',
'undelete-show-file-confirm' => 'Ṣé ẹ ní ìdálójú pé ẹ fẹ́ wo àtúnyẹ̀wó píparẹ́ fáìlì "<nowiki>$1</nowiki>" látọjọ́ $2 ní ago $3?',
'undelete-show-file-submit'  => 'Bẹ́ẹ̀ni',

# Namespace form on various pages
'namespace'      => 'Orúkọàyè:',
'invert'         => 'Pàṣípààrọ̀ àsàyàn',
'blanknamespace' => '(Gbangba)',

# Contributions
'contributions'       => 'Àwọn àfikún ẹnitínṣe',
'contributions-title' => 'Àwọn àfikún oníṣe fún $1',
'mycontris'           => 'Àwọn àfikún mi',
'contribsub2'         => 'Fún $1 ($2)',
'uctop'               => '(lókè)',
'month'               => 'Láti osù (àti sẹ́yìn):',
'year'                => 'Láti ọdún (àti sẹ́yìn):',

'sp-contributions-newbies'        => 'Àfihàn àwọn àfikún àwọn àpamọ́ tuntun nìkan',
'sp-contributions-newbies-sub'    => 'Fún àwọn àpamọ́ tuntun',
'sp-contributions-newbies-title'  => 'Àwọn àfikún oníṣe fún àwọn àpamọ́ tuntun',
'sp-contributions-blocklog'       => 'Àkọsílẹ̀ ìdínà',
'sp-contributions-deleted'        => 'àwọn àfikún píparẹ́ oníṣe',
'sp-contributions-logs'           => 'àwọn àkọọ́lẹ̀',
'sp-contributions-talk'           => 'ọ̀rọ̀',
'sp-contributions-userrights'     => 'ìmójútó àwọn ẹ̀tọ́ oníṣe',
'sp-contributions-blocked-notice' => 'Lọ́wọ́lọ́wọ́ oníṣe yìí jẹ́ dídílọ́nà.
Àkọsílẹ̀ ìdínà àìpẹ́ nìyí nísàlẹ̀ fún ìtọ́kasí:',
'sp-contributions-search'         => 'Àwáàrí fún àwọn àfikún',
'sp-contributions-username'       => 'Àdírẹ́ẹ̀sì IP tàbí orúkọ oníṣe:',
'sp-contributions-toponly'        => 'Ìfihàn àwọn àtúnṣe tí wọn jẹ́ àtúnyẹ̀wò àìpẹ́ nìkan',
'sp-contributions-submit'         => 'Ṣàwárí',

# What links here
'whatlinkshere'            => 'Ìjápọ̀ mọ́ ojúewé yí',
'whatlinkshere-title'      => 'Àwọn ojúewé tó jápọ̀ mọ́ "$1"',
'whatlinkshere-page'       => 'Ojúewé:',
'linkshere'                => "Àwọn ojúewé wọ̀nyí jápọ̀ mọ́ '''[[:$1]]''':",
'nolinkshere'              => "Kò sí ojúewé tó jápọ̀ mọ́ '''[[:$1]]'''.",
'isredirect'               => 'àtúnjúwe ojúewé',
'istemplate'               => 'ìkómọ́ra',
'isimage'                  => 'Ìjápọ̀ àwòrán',
'whatlinkshere-prev'       => '{{PLURAL:$1|tẹ́lẹ̀|tẹ́lẹ̀ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|tókàn|tókàn $1}}',
'whatlinkshere-links'      => '← àwọn ìjápọ̀',
'whatlinkshere-hideredirs' => '$1 àtúnjúwe',
'whatlinkshere-hidetrans'  => '$1 ìkómọ́ra',
'whatlinkshere-hidelinks'  => '$1 ìjápọ̀',
'whatlinkshere-hideimages' => '$1 àwọn ìjápọ̀ àwòrán',
'whatlinkshere-filters'    => 'Ajọ̀',

# Block/unblock
'blockip'                     => 'Dínà oníṣe',
'blockip-title'               => 'Ìdínà oníṣẹ',
'blockip-legend'              => 'Ìdínà oníṣẹ',
'ipadressorusername'          => 'Àdírẹ́ẹ̀sì IP tàbí orúkọ oníṣe:',
'ipbexpiry'                   => 'Ìwásópin:',
'ipbreason'                   => 'Ìdíẹ̀:',
'ipbreasonotherlist'          => 'Ìdí mìíràn',
'ipbcreateaccount'            => 'Ìdínà dídá àpamọ́',
'ipbemailban'                 => 'Ìdínà oníṣe láti fi e-mail ránṣẹ́',
'ipbenableautoblock'          => 'Ní fúnrararẹ̀ dínà àdírẹ́sì IP tí oníṣe yìí lò gbẹ̀yìn, àti àwọn àdírẹ́sì IP ọjọ́ọwájú yìówù tí ó bá fẹ́ lò láti ṣátúnṣe',
'ipbsubmit'                   => 'Dínà oníṣe yìí',
'ipbother'                    => 'Àkókò míràn:',
'ipboptions'                  => '2 wákàtí:2 hours,1 ọjọ́:1 day,3 ọjọ́:3 days,1 ọ̀ṣẹ̀:1 week,2 ọ̀ṣẹ̀:2 weeks,1 osù:1 month,3 osù:3 months,6 osù:6 months,1 ọdún:1 year,àílópin:infinite',
'ipbotheroption'              => 'òmíràn',
'ipbotherreason'              => 'Ìdí míràn/àfikún:',
'ipbhidename'                 => 'Ìbómọ́lẹ̀ orúkọ oníṣe nínú àwọn àtúnṣe àti àwọn àkójọ',
'badipaddress'                => 'Àdírẹ́ẹ̀sì IP tíkòtọ́',
'blockipsuccesssub'           => 'Ìdínà yọrí sí rere',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] ti jẹ́ dídílọ́nà.<br />
Ẹ wo [[Special:IPBlockList|IP àkójọ ìdínà]] láti ṣàtúnyẹ̀wò àwọn ìdínà.',
'ipb-edit-dropdown'           => 'Àtúnṣe àwọn ìdí ìdínà',
'ipb-unblock-addr'            => 'Ìmúkúrò ìdínà $1',
'ipb-unblock'                 => 'Ìmúkúrò ìdínà orúkọ oníṣe kan tàbí àdírẹ́sì IP',
'ipb-blocklist-contribs'      => 'Àwọn àfikún fún $1',
'unblockip'                   => 'Ìmúkúrò ìdínà oníṣe',
'ipusubmit'                   => 'Ìmúkúrò ìdínà yìí',
'unblocked-id'                => 'Ìdínà $1 ti jẹ́ mímúkúrò',
'ipblocklist'                 => 'Àwọn àdírẹ́ẹ̀sì IP àti orúkọ ọníṣe tí a dínà',
'ipblocklist-submit'          => 'Ṣàwárí',
'ipblocklist-otherblocks'     => '{{PLURAL:$1|Ìdínà|Àwọn ìdínà}} mííràn',
'infiniteblock'               => 'àìlópin',
'expiringblock'               => 'yíò parí ní ọjọ́ $1 ní ago $2',
'anononlyblock'               => 'aláìlórúkọ nìkan',
'noautoblockblock'            => 'dídálẹ́kun ìdínà fúnrararẹ̀',
'emailblock'                  => 'e-mail jẹ́ dídílọ́nà',
'ipblocklist-empty'           => 'Àkójọ ìdínà jẹ́ òfo.',
'blocklink'                   => 'dínà',
'unblocklink'                 => 'jáwọ́ ìdínà',
'change-blocklink'            => 'yí ìdínà padà',
'contribslink'                => 'àfikún',
'blocklogpage'                => 'Àkosílẹ̀ ìdínà',
'blocklogentry'               => 'ìdínà [[$1]] yíò parí ní $2 $3',
'unblocklogentry'             => 'mú ìdínà kúrò fùn $1',
'block-log-flags-anononly'    => 'àwọn oníṣe aláìlórúkọ nìkan',
'block-log-flags-nocreate'    => 'ìdálẹ́kun ṣíṣèdá àkópamọ́',
'block-log-flags-noautoblock' => 'dídálẹ́kun ìdínà fúnrararẹ̀',
'block-log-flags-noemail'     => 'e-mail jẹ́ dídílọ́nà',
'block-log-flags-hiddenname'  => 'orúkọ oníṣe jẹ́ bíbòmọ́lẹ̀',
'ipb_already_blocked'         => '"$1" jẹ́ dídèlọ́nà tẹ́lẹ̀',
'ipb-needreblock'             => '"$1" jẹ́ dídèlọ́nà tẹ́lẹ̀
Ṣé ẹ fẹ́ yí àwọn ìtò yí padà?',
'ipb-otherblocks-header'      => '{{PLURAL:$1|Ìdínà|Àwọn ìdínà}} mìíràn',
'blockme'                     => 'Dínà mi',
'proxyblocksuccess'           => 'Ṣetán',

# Developer tools
'lockdb'    => 'Ti ìbùdó ìpèsè pa',
'unlockbtn' => 'Ṣí ìbùdó ìpèsè',

# Move page
'move-page'                 => 'Yípò $1',
'move-page-legend'          => 'Ìyípò ojúewé',
'movepagetext'              => "Fọ́ọ̀mù ìsàlẹ̀ yìí ṣàtúnṣọlórúkọ ojúewé, yíò kó gbogbo ìtàn rẹ̀ sí ojúewé tuntun.
Àkọlé rẹ̀ tẹ́lẹ̀ yíò di ojúewé àtúndarí sí ọ̀dọ̀ àkọlẹ́ tuntun.
Ẹ lè ṣọdọ̀tun àwọn àtúndarí tí wọ́n tọ́kasí àkọlé tìbẹ̀rẹ̀ fúnrararẹ̀.
Tí ẹ kò bá fẹ́ ṣèyí, ẹ ríi dájú pé ẹ kíyèsí [[Special:DoubleRedirects|ẹ̀mejì]] tàbí [[Special:BrokenRedirects|àwọn àtúndarí jíjá]].
Ojúṣe yín ni pé àwọn ìjápọ̀ ún tọ́kasí ibi tó yẹ kí wọn ó lọ sí.

Ẹ kíyèsí pé ojúewé '''kò''' ní yípò tí ojúewé mìíràn bá wà tó ní orúkọ ojúewé tuntun ọ̀hún, àyàfi tó bá jẹ́ òfo tàbí àtúndarí tí kò sì ní ìtàn àtúnṣe ṣẹ́yìn.
Èyí túmọ́sí wípé ẹ lẹ̀ ṣàtúnṣọlórúkọ ojúewé padà sí ibi tó ti jẹ́ ṣíṣàtúnṣọlórúkọ wá tí ẹ bá ṣe àṣìṣe, àti pé ẹ kò le ṣàkọléṣórí ojúewé tó wà.

'''Ìkìlọ̀!'''
Èyí le fa ìdàrú sí ojúewé tó gbajúmọ́;
ẹ ríi wípé ohun tí yíò ṣẹlẹ̀ ye yín kí ẹ tó tẹ̀síwájú.",
'movepagetalktext'          => "Ojúewé ọ̀rọ̀ tó sopọ̀ mọ náà yíó yípò pọ̀ mọ fún ra rẹ̀ '''àfibí:'''
*Tí ọ̀rọ̀ ojúewé tí kò jẹ́ òfo wà pẹ̀lú orúkọ tuntun náà, tàbí
*Ẹ mú àmí kúrò nínú àpótí ìṣàlẹ̀ yìí.

Tí ó bá jẹ́ báhun, ẹ gbúdọ̀ ṣe ìyípò rẹ̀ fúnra yín.",
'movearticle'               => 'Yípò ojúewé:',
'movenologin'               => 'Ẹ kò tíì wọlé',
'movenologintext'           => 'Ẹ gbọ́dọ̀ jẹ́ oníṣe ajẹ́fíforúkọsílẹ̀ kí ẹ sì [[Special:UserLogin|wọlẹ́]] láti yípò ojúewé kan.',
'movenotallowed'            => 'Ẹ kò ní ìyọ̀nda láti yípò ojúewé.',
'movenotallowedfile'        => 'Ẹ kò ní ìyọ̀nda láti yípò fáìlì.',
'cant-move-user-page'       => 'Ẹ kò ní ìyọ̀nda láti yípò àwọn ojúewé oníṣe (àyàfi láti ọ̀dọ̀ àwọn abẹ́ojúewé).',
'cant-move-to-user-page'    => 'Ẹ kò ní ìyọ̀nda láti yípò àwọn ojúewé sí ojúewé oníṣe (àyàfi sí abẹ́ojúewé oníṣe).',
'newtitle'                  => 'Sí àkọlé tuntun:',
'move-watch'                => 'Mójútó ojúewé yìí',
'movepagebtn'               => 'Yípò ojúewé',
'pagemovedsub'              => 'Ìyípò ti já sí rere',
'movepage-moved'            => '\'\'\'"$1" ti yípò sí "$2"\'\'\'.',
'movepage-moved-redirect'   => 'Àtúndarí ti jẹ́ dídá.',
'movepage-moved-noredirect' => 'Ìdá àtúnjúwe sí ojúewé yìí kò wáyé.',
'articleexists'             => 'Ojúewé pẹ̀lú orúkọ un wà tẹ́lẹ̀, tàbí kójẹ́pé orúkọ tí ẹ yàn kò ní ìbámu.
Ẹ jọ̀wọ́ ẹ yan orúkọ mìíràn.',
'talkexists'                => "'''Bótilẹ̀jẹ́pé ìyípò ojúewé ọ̀hún jásí rere, ojúewé ọ̀rọ̀ kò se é yípọ̀ nítorípé ìkan tiwà ní àkọlé tuntun.
Ẹ jọ̀wọ́ ẹ ti fún ra yín dà wọ́n pọ̀.'''",
'movedto'                   => 'tiyípò sí',
'movetalk'                  => 'Yípò ojúewé ọ̀rọ̀ rẹ̀',
'move-subpages'             => 'Yípò àwọn ọmọ ojúewé (títí dé $1)',
'movepage-page-moved'       => 'Ojúewé $1 ti jẹ́ yíyípò sí $2.',
'movepage-page-unmoved'     => 'Ojúewé $1 kò ṣe é yípò sí $2.',
'1movedto2'                 => '[[$1]] ti yípò sí [[$2]]',
'1movedto2_redir'           => 'yípò [[$1]] sí [[$2]] lórí àtúnjúwe',
'move-redirect-suppressed'  => 'àtúnjúwe tijẹ́ dídílọ́nà',
'movelogpage'               => 'Àkọsílẹ́ ìyípò',
'movesubpage'               => '{{PLURAL:$1|Ojúewé abẹ́|Àwọn ojúewé abẹ́}}',
'movenosubpage'             => 'Ojúewé yìí kò ní àwọn abẹ́ojúewé.',
'movereason'                => 'Ìdíẹ̀:',
'revertmove'                => 'dápadà',
'delete_and_move'           => 'Parẹ́ kí o sì yípò',
'delete_and_move_text'      => '== Ìparẹ́ pọndandan ==
Ojúewé àdésí "[[:$1]]" wà tẹ́lẹ̀tẹ́lẹ̀.
Ṣé ẹ fẹ́ paárẹ́ láti sínà fún ìyípò?',
'delete_and_move_confirm'   => 'Bẹ́ẹ̀ni, pa ojúewé náà rẹ́',
'immobile-source-namespace' => 'Ìyípò àwọn ojúewé nínú orúkọàyè ""$1" kò ṣe é ṣe.',
'immobile-target-namespace' => 'Ìyípò àwọn ojúewé sínú orúkọàyè ""$1" kò ṣe é ṣe.',
'immobile-source-page'      => 'Ojúewé yìí kòṣe é yínípò',
'move-leave-redirect'       => 'Ẹ fún ní àtúnjúwe',

# Export
'export'            => 'Ìkójáde àwọn ojúewé',
'export-submit'     => 'Kósíta',
'export-addcattext' => 'Àfikún àwọn ojúewé láti inú ẹ̀ka:',
'export-addcat'     => 'Ìròpọ̀',
'export-addnstext'  => 'Àfikún àwọn ojúewé láti inú orúkọààyè:',
'export-addns'      => 'Ìròpọ̀',
'export-download'   => 'Ìmúpamọ́ gẹ́gẹ́ bi faili',
'export-templates'  => 'Ìmúpọ̀ àwọn àdàkọ',

# Namespace 8 related
'allmessages'               => 'Àwọn ìránṣẹ́ sistẹmu',
'allmessagesname'           => 'Orúkọ',
'allmessagescurrent'        => 'Ìkọ ìránṣẹ́ lọ́wọ́',
'allmessages-filter-all'    => 'Gbogbo wọn',
'allmessages-language'      => 'Èdè:',
'allmessages-filter-submit' => 'Lọ',

# Thumbnails
'thumbnail-more'       => 'Ìmútóbi',
'filemissing'          => 'Fáìlì kò sí',
'thumbnail_image-type' => 'Kò sí àtìlẹ́yìn fún irú àwòrán yìí',

# Special:Import
'import'                     => 'Ìkówọlé àwọn ojúewé',
'importinterwiki'            => 'Ìkówọlé láàrin àwọn wiki',
'import-interwiki-source'    => 'Orísún wiki/ojúewé:',
'import-interwiki-templates' => 'Ìmúpọ̀ gbogbo àwọn àdàkọ',
'import-interwiki-submit'    => 'Ìkówọlé',
'import-interwiki-namespace' => 'Orúkọàyè ìdópin:',
'import-upload-filename'     => 'Orúkọ faili:',
'import-comment'             => 'Àríwí:',
'import-revision-count'      => '{{PLURAL:$1|Àtúnyẹ̀wò|Àwọn àtúnyẹ̀wò}} $1',
'importnopages'              => 'Kò sí àwọn ojúewé kankan láti kówọlé.',
'importbadinterwiki'         => 'Ìjápọ̀ interwiki búburú',
'importsuccess'              => 'Ìkówọlé ti parí!',
'import-upload'              => 'Ìrùsókè àwọn dátà XML',

# Import log
'importlogpage'                    => 'Ìgbéwọlé àkọọ́lẹ̀',
'import-logentry-upload-detail'    => '{{PLURAL:$1|Àtúnyẹ̀wò|Àwọn àtúnyẹ̀wò}} $1',
'import-logentry-interwiki'        => 'mú $1 wá láti inú wiki míràn',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|Àtúnyẹ̀wò|Àwọn àtúnyẹ̀wò}} $1 láti $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ojúewé oníṣe yín',
'tooltip-pt-mytalk'               => 'Ojúewé ọ̀rọ̀ yín',
'tooltip-pt-preferences'          => 'Àwọn ìfẹ́ràn mi',
'tooltip-pt-watchlist'            => 'Àkójọ àwọn ojúewé tí ẹ̀ ún mójútó bóyá wọ́nyí padà',
'tooltip-pt-mycontris'            => 'Àkójọ àwọn àfikún yín',
'tooltip-pt-login'                => 'A gbà yín níyànjú kí ẹwọlé, bótilẹ̀jẹ́pẹ́ kò pọndandan.',
'tooltip-pt-anonlogin'            => 'A gbàyín níyànjú láti wọlé, bótilẹ̀jẹ́pé kò ṣe dandan.',
'tooltip-pt-logout'               => 'Ìbọ́sódé',
'tooltip-ca-talk'                 => 'Ìfọ̀rọ̀wérọ̀ nípa ohun inú ojúewé yìí',
'tooltip-ca-edit'                 => 'Ẹ le ṣe àtúnṣe sí ojúewé yìí.
Ẹ jọ̀wọ́ ẹ lo bọtini àyẹ̀wò kí ẹ tó fipamọ́.',
'tooltip-ca-addsection'           => 'Ẹ bẹ̀rẹ̀ abẹlẹ tuntun',
'tooltip-ca-viewsource'           => 'Àbò wà lórí ojúewé yìí.
Ẹ le wo àmìọ̀rọ̀ rẹ̀.',
'tooltip-ca-history'              => 'Àwọn àtúnṣe tókọjá sí ojúewé yìí',
'tooltip-ca-protect'              => 'Dábòbò ojúewé yìí',
'tooltip-ca-delete'               => 'Ẹ pa ojúewé yìí rẹ́',
'tooltip-ca-move'                 => 'Ìyípòdà ojúewé yìí',
'tooltip-ca-watch'                => 'Ṣe ìfikún ojúewé yìí mọ́ ìmójútó yín',
'tooltip-ca-unwatch'              => 'Ẹ yọ ojúewé yìí kúrò nínú ìmójútó yín',
'tooltip-search'                  => "Ṣ'àwáàrí nínú {{SITENAME}}",
'tooltip-search-go'               => 'Lọ sí ojúewé tó ní orúkọ yìí tí ọ́ bá wà',
'tooltip-search-fulltext'         => 'Ṣe àwáàrí nínú àwọn ojúewé fún ìkọ yìí',
'tooltip-p-logo'                  => 'Ojúewé Àkọ́kọ́',
'tooltip-n-mainpage'              => 'Ẹ ṣe àbẹ̀wò sí Ojúewé Àkọ́kọ́',
'tooltip-n-mainpage-description'  => 'Àbẹ̀wò sí ojúewé àkọ́kọ́',
'tooltip-n-portal'                => 'Ẹ̀kúnrẹ́rẹ́ nípa iṣẹ́ọwọ́ yìí',
'tooltip-n-currentevents'         => 'Ìròhìn lọ́wọ́lọ́wọ́',
'tooltip-n-recentchanges'         => 'Àkójọ àwọn àtúnṣe tuntun nínú wiki.',
'tooltip-n-randompage'            => 'Ẹ ṣe àrìnàkò ojúewé kan',
'tooltip-n-help'                  => 'Fún ìrànlọ́wọ́.',
'tooltip-t-whatlinkshere'         => "Àkójọ gbogbo ojúewé wiki tó jápọ̀ s'íbí",
'tooltip-t-recentchangeslinked'   => 'Àwọn àtúnṣe tuntun nínú àwọn ojúewé tójápọ̀ láti inú ojúewé yìí',
'tooltip-feed-rss'                => 'RSS feed fùn ojúewé yìí',
'tooltip-feed-atom'               => 'Atom feed fún ojúewé yìí',
'tooltip-t-contributions'         => 'Ẹ wo àkójọ àwọn àfikún oníṣe yìí',
'tooltip-t-emailuser'             => 'Ẹ fi e-mail ránṣẹ́ sí oníṣe yìí',
'tooltip-t-upload'                => 'Ìrùsókè àwọn fáìlì',
'tooltip-t-specialpages'          => 'Àkójọ gbogbo àwọn ojúewé pàtàkì',
'tooltip-t-print'                 => "Ojúewé tí ó ṣe é tẹ̀ ṣ'íwèé",
'tooltip-t-permalink'             => 'Ìjápọ̀ tíkòyípadà sí àtúnyẹ̀wò fún ojúewé náà',
'tooltip-ca-nstab-main'           => 'Ìfihàn inú ojúewé',
'tooltip-ca-nstab-user'           => 'Ẹ wo ojúewé oníṣe',
'tooltip-ca-nstab-media'          => 'Ẹ wò ojúewé amóhùnmáwòrán',
'tooltip-ca-nstab-special'        => "Ojúewé yìí ṣe pàtàkì, ẹ kò le è ṣ'àtúnṣe rẹ̀",
'tooltip-ca-nstab-project'        => 'Ẹ wo ojúewé iṣẹ́ọwọ́',
'tooltip-ca-nstab-image'          => 'Ẹ wo ojúewé faili',
'tooltip-ca-nstab-template'       => 'Ẹ wo àdàkọ náà',
'tooltip-ca-nstab-help'           => 'Ẹ wo ojúewé ìrànlọ́wọ́',
'tooltip-ca-nstab-category'       => 'Ẹ wo ẹ́ka ojúewé',
'tooltip-minoredit'               => "Ṣ'àmì sí èyí gẹ́gẹ́ bi àtúnṣe kékeré",
'tooltip-save'                    => 'Ìmúpamọ́ àwọn àtúnṣe yín',
'tooltip-preview'                 => 'Àyẹ̀wò àwọn àtúnṣe yín, ẹ jọ̀wọ́ ẹ kọ́kọ́ lo è yí kí ẹ tó fipamọ́!',
'tooltip-diff'                    => 'Ìfihàn àwọn àtúnṣe tí ẹ ṣe sí ìkọ yìí.',
'tooltip-compareselectedversions' => 'Ẹ wo ìyàtò láàrin àwọn àtúnṣe tí a ṣàyàn fún ojúewé yìí.',
'tooltip-watch'                   => "Ẹ ṣ'àfikún ojúewé yìí mọ́ ìmójútó yín",
'tooltip-upload'                  => 'Bẹ̀rẹ̀ ìrùsókè',
'tooltip-rollback'                => '"Ìyíṣẹ́yìn" ún ṣe ìdápadà àwọn àtúnṣe sí ojúewé yìí',
'tooltip-undo'                    => '"Dápadà" ṣèyíṣẹ́yìn àtúnṣe yìí, yíò ṣí fọ́ọ̀mù àtúnṣe bíi àkọ́bojúwò. Ó gba ààyè láti sọ ìdí nínú àkótán.',
'tooltip-preferences-save'        => 'Ìmúpamọ́ àwọn ìfẹ́ràn',

# Attribution
'anonymous'        => '{{PLURAL:$1|Oníṣe|Àwọn oníṣe}} aláìlórúkọ ti {{SITENAME}}',
'siteuser'         => 'Oníṣe $1 lórí {{SITENAME}}',
'anonuser'         => 'Oníṣe aláìlórúkọ $1 {{SITENAME}}',
'lastmodifiedatby' => 'Ìgbà tí a ṣe àtúnṣe sí ojúewé yìí gbẹ̀yín ni $2, $1 látọwọ́ $3.',
'othercontribs'    => 'Dídálórí iṣẹ́ ti $1.',
'others'           => 'àwọn mìíràn',
'siteusers'        => '{{PLURAL:$2|Oníṣe|Àwọn oníṣe}} $1  {{SITENAME}}',
'anonusers'        => '{{PLURAL:$2|Oníṣe|Àwọn oníṣe}} aláìlórúkọ $1 {{SITENAME}}',

# Info page
'infosubtitle' => 'Ìfitọ́nilétí fún ojúewé',
'numedits'     => 'Íyé áwon àtúnṣe (ojúewé): $1',
'numtalkedits' => 'Íyé áwon àtúnṣe (ojúewé ìfọ̀rọ̀wérọ̀): $1',
'numwatchers'  => 'Iye àwọn aláàbójúwò: $1',

# Math errors
'math_unknown_error'    => 'àsiṣe àwámárìdí',
'math_unknown_function' => 'ìfiṣe àwámárìdí',
'math_lexing_error'     => 'àsiṣe òye ọ̀rọ̀',

# Patrol log
'patrol-log-auto' => '(fúnraara)',
'patrol-log-diff' => 'àtúnyẹ̀wò $1',

# Image deletion
'filedeleteerror-short' => 'Àsìṣe ìparẹ́ fáílì: $1',
'filedelete-missing'    => 'Fáìlì "$1" náà kò ṣe é parẹ́ nítorípé kò sí.',

# Browsing diffs
'previousdiff' => '← Àtúnṣe tópẹ́jù',
'nextdiff'     => 'Àtúnṣe tótuntunjù →',

# Media information
'thumbsize'            => 'Ìtóbi àwòrán kékeré:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|ojúewé|àwọn ojúewé}}',
'file-info'            => 'ìtóbi faili: $1, irú MIME: $2',
'file-info-size'       => '$1 × $2 pixel, ìtóbi faili: $3, irú MIME: $4',
'file-nohires'         => '<small>Kò sí ìgbéhàn gíga jù báun lọ.</small>',
'svg-long-desc'        => 'faili SVG, pẹ̀lú $1 × $2 pixels, ìtòbi faili: $3',
'show-big-image'       => 'Pẹ̀lú ìgbéhàn gíga',
'show-big-image-size'  => '$1 × $2 pixels',
'file-info-gif-looped' => 'lílọ́po',
'file-info-png-looped' => 'lílọ́po',

# Special:NewFiles
'newimages'             => 'Ọ̀dẹ̀dẹ̀ àwòrán àwọn faili tuntun',
'newimages-legend'      => 'Ajọ̀',
'newimages-label'       => 'Orúkọ faili (tàbí apá kan rẹ̀):',
'showhidebots'          => '(àwọn bot $1)',
'noimages'              => 'Kò sí àwòrán.',
'ilsubmit'              => 'Ṣàwárí',
'bydate'                => 'bíi ọjọ́ọdún',
'sp-newimages-showfrom' => 'Ìfihàn àwọn fáìlì tuntun nípa bíbẹ̀rẹ̀ láti ago $2, ọjọ́ $1',

# Bad image list
'bad_image_list' => 'Onírú jẹ́ gẹ́gẹ́ bíi àtèlé yìí:
Àwọn ohun àkójọ nìkan (àwọn ìlà tí wọ́n bẹ̀rẹ̀ pẹ̀lú *) ni wọ́n jẹ́ gbígbérò.
Ìjápọ̀ àkọ́kọ́ lórí ìlà gbọdọ̀ jẹ́ ìjápọ̀ mọ́ fáìlì búburú.
Àwọn ìjápọ̀ yìówù lẹ́yìn èyí lórí ìlà kannáà jẹ́ gbígbà pé wọ́n jẹ́ ọ̀tọ̀, wípé àwọn ojúewé níbití fáìlì náà le ṣẹlẹ̀ nínú ìlà.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Fáìlì yìí ní ìfitólétí aláròpọ̀mọ́, ó ṣe é ṣe kí ó jẹ́ ríròpọ̀ látọwọ́ kámẹ́rà oníka tàbí ẹ̀rọ skani lílò fún ìdá rẹ̀ tàbí ṣoníka rẹ̀.
Tóbájẹ́pé fáìlì ọ̀hún ti jẹ́ títúnṣe sí bóṣewà ní bẹ̀rẹ̀, àwọn ẹ̀kúnrẹ́rẹ́ méèló kan le mọ́ fi fáìlì títúnṣe náà hàn dáadáa.',
'metadata-expand'   => 'Ìfihàn gbogbo ẹ̀kúnrẹ́rẹ́',
'metadata-collapse' => 'Ìbòmọ́lẹ̀ ẹ̀kúnrẹ́rẹ́',
'metadata-fields'   => "EXIF àwọn pápá metadata tí a kójọ sínú ìránṣẹ́ yìí yíò jẹ́ àfipọ̀ sínú ojúewé àwòrán tóhàn ti tábìlì metadata bá fúnpọ̀.
Àwọn yìókù yíò pamọ́ lát'ìbẹ̀rẹ̀.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'          => 'Fífẹ̀sí',
'exif-imagelength'         => 'Gígasí',
'exif-imagedescription'    => 'Àkọlé àwòrán',
'exif-make'                => 'Olùṣẹ̀rọ kámẹ́rà',
'exif-model'               => 'Irú kámẹ́rà',
'exif-artist'              => 'Olùdá',
'exif-copyright'           => 'Ẹni tóni ẹ̀tọ́ àwòkọ',
'exif-exposuretime-format' => '$1 ìṣẹ́j/kejì ($2)',
'exif-fnumber'             => 'Nọ́mbà F',
'exif-filesource'          => 'Orísun fáìlì',
'exif-gpsdatestamp'        => 'Ọjọ́ọdún GPS',

'exif-orientation-1' => 'Déédé',

'exif-componentsconfiguration-0' => 'kòsí',

'exif-subjectdistance-value' => 'mítà $1',

'exif-meteringmode-0'   => 'Àìmọ̀',
'exif-meteringmode-1'   => 'Ìpínláàrin',
'exif-meteringmode-255' => 'Òmíràn',

'exif-lightsource-1'  => 'Ojúmọ́',
'exif-lightsource-11' => 'Ìbòji',

'exif-contrast-0' => 'Déédé',

'exif-saturation-0' => 'Déédé',

'exif-sharpness-0' => 'Déédé',

# External editor support
'edit-externally'      => "Ẹ lo ìmúlò òde láti ṣ'àtúnṣe fáìlì yìí",
'edit-externally-help' => '(Ẹ wo [http://www.mediawiki.org/wiki/Manual:External_editors ìlànà ìṣètò] fún ẹ̀kúnrẹ́rẹ́)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'gbogbo',
'imagelistall'     => 'gbogbo',
'watchlistall2'    => 'gbogbo',
'namespacesall'    => 'gbogbo',
'monthsall'        => 'gbogbo',
'limitall'         => 'gbogbo',

# E-mail address confirmation
'confirmemail'          => "Ṣè'múdájú àdírẹ́ẹ̀sì e-mail",
'confirmemail_noemail'  => 'Ẹ kò tíì ṣètò àdírẹ́ẹ̀sì e-mail tó tótọ́ nínú [[Special:Preferences|ìfẹ́ràn oníṣe]] yín.',
'confirmemail_send'     => 'Fi àmìọ̀rọ̀ ìmúdájú ránṣẹ́',
'confirmemail_sent'     => 'E-mail ìmúdájú ti jẹ́ fífiránṣẹ́.',
'confirmemail_oncreate' => 'A ti fi àmìọ̀rọ̀ ìmúdájú ránṣẹ́ sí ojúọ̀nà e-mail yín.
Àmìọ̀rọ̀ yìí kò pọndandan láti mú yín wọlé, sùgbọ́n ẹ gbọ́dọ̀ mu padà kí gbogbo àwọn ohun inú wiki yìí tó dúró lórí e-mail ó tó lè ṣiṣẹ́.',
'confirmemail_loggedin' => 'Àdírẹ́ẹ̀sì e-mail yín ti dájú.',
'confirmemail_subject'  => 'Ìmúdájú àdírẹ́ẹ̀sì e-mail fún {{SITENAME}}',
'invalidateemail'       => 'Fagilé ìmúdájú e-mail',

# Scary transclusion
'scarytranscludetoolong' => '[URL ti gùn jù]',

# Trackbacks
'trackbackremove' => '([Ìparẹ́ $1])',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => '← ojúewé tókọjá',
'imgmultipagenext' => 'ojúewé tóúnbọ̀ →',
'imgmultigo'       => 'Lọ!',
'imgmultigoto'     => 'Lọ sí ojúewé $1',

# Table pager
'ascending_abbrev'         => 'ròkè',
'descending_abbrev'        => 'relẹ̀',
'table_pager_next'         => 'Ojúewé tóúnbọ̀',
'table_pager_prev'         => 'Ojúewé tókọjá',
'table_pager_first'        => 'Ojúewé ìkíní',
'table_pager_last'         => 'Ojúewé tógbẹ̀yìn',
'table_pager_limit_submit' => 'Lọ',
'table_pager_empty'        => 'Kò sí èsì',

# Auto-summaries
'autoredircomment' => 'Ti ṣàtunjúwe ojúewé sí [[$1]]',

# Live preview
'livepreview-loading' => 'Únrùjáde...',
'livepreview-ready'   => 'Úngbéyọ... Ti ṣetán!',

# Watchlist editor
'watchlistedit-noitems'       => 'Ìmójútó yín kò ní àwọn àkọlé kankan.',
'watchlistedit-normal-title'  => 'Àtúnṣe ìmójútó',
'watchlistedit-normal-legend' => 'Ìyọkúrò àwọn àkọlé láti inú ìmójútó',
'watchlistedit-normal-submit' => 'Ìyọkúrò àwọn àkọlé',
'watchlistedit-raw-titles'    => 'Àwọn àkọlé:',

# Watchlist editing tools
'watchlisttools-view' => 'Ẹ wo àwon àtúnṣe tóbaamu',
'watchlisttools-edit' => 'Ìwò àti àtúnṣe ìmójútó',
'watchlisttools-raw'  => "Ẹ ṣ'àtúnṣe àkójọ ìmójútó látìbẹ̀rẹ̀",

# Special:Version
'version'                  => 'Àtẹ̀jáde',
'version-specialpages'     => 'Àwọn ojúewé pàtàkì',
'version-other'            => 'Òmíràn',
'version-version'          => '(Àtẹ̀jáde $1)',
'version-license'          => 'Ìwé àṣẹ',
'version-software-version' => 'Àtẹ̀jáde',

# Special:FilePath
'filepath'        => 'Ipaṣẹ̀ fáìlì',
'filepath-page'   => 'Faili:',
'filepath-submit' => 'Lọ',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Orúkọ fáìlì:',
'fileduplicatesearch-submit'   => 'Àwárí',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Ìtóbi fáìlì: $3<br />Irú MIME: $4',

# Special:SpecialPages
'specialpages'                   => 'Àwọn ojúewé pàtàkì',
'specialpages-group-maintenance' => 'Àwọn ìjábọ̀ ìtọ́jú',
'specialpages-group-other'       => 'Àwọn ojúewé pàtàkì míràn',
'specialpages-group-login'       => 'Ìwọlé / ìforúkọsílẹ́',
'specialpages-group-pages'       => 'Àkójọ àwọn ojúewé',
'specialpages-group-pagetools'   => 'Àwọn irinṣẹ́ ojúewé',

# Special:BlankPage
'blankpage' => 'Ojúewé òfo',

# Special:Tags
'tags-title'    => 'Àwọn àlẹ̀mọ́',
'tags-edit'     => 'àtúnṣe',
'tags-hitcount' => '{{PLURAL:$1|Àtúnṣe|Àwọn àtúnṣe}} $1',

# Special:ComparePages
'compare-page1'  => 'Ojúewé 1',
'compare-page2'  => 'Ojúewé 2',
'compare-rev1'   => 'Àtúnyẹ̀wò 1',
'compare-rev2'   => 'Àtúnyẹ̀wò 2',
'compare-submit' => 'Ṣàfiwé',

# Database error messages
'dberr-header' => 'Wiki yìí ní ìsòro',

# HTML forms
'htmlform-submit'              => 'Fúnsílẹ̀',
'htmlform-reset'               => 'Ìdápadà àwọn àtúnṣe',
'htmlform-selectorother-other' => 'Òmíràn',

# Special:DisableAccount
'disableaccount-user'   => 'Orúkọ́ oníṣe:',
'disableaccount-reason' => 'Ìdíẹ̀:',

);
