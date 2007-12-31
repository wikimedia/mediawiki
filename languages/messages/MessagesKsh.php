<?php
/** Ripoarisch (Ripoarisch)
 *
 * @addtogroup Language
 *
 * @author Caesius noh en Idee vum Manes
 * @author Purodha
 * @author לערי ריינהארט
 * @author Siebrand
 * @author SPQRobin
 */

/**
 * Sources:
 * The following expressions are based on the Kölsch dictionaries: 
 * Das Kölsche Wörterbuch, written by Christa Bhatt and Alice Herrwegen, 
 * published by: Akademie för uns kölsche Sproch, Cologne 2005,
 * ISBN 3-7616-1942-1
 * and
 * Neuer Kölnischer Sprachschatz in 3 Bänden, written by Adam Wrede, Cologne 1958, 
 * ISBN 3-7743-0155-7  ISBN 3-7743-0156-5  ISBN 3-7743-0157-3
 *
 * The grammar (especially: conjugation) is taken from: 
 * De kölsche Sproch - Kurzgrammatik Kölsch / Deutsch, written by Alice Tiling-Herrwegen, 
 * published by: Akademie för uns kölsche Sproch, Cologne 2002,
 * ISBN 3-7616-1604-X
 *
 * Special feature: Because of utilization in modern ripuarian literature
 * (for example: Asterix op kölsch - Däm Asterix singe Jung, ISBN 3-7704-0468-8) the rules for the letters G and J 
 * are taken from Adam Wrede (for example: Jedöns, jeeße, jejovve, adich, iggelich, nüdich ) 
 * and not from the Akademie (for example: Gedöns, geeße, gegovve, aadig, iggelig, nüdig)
 * Otherwise most part of the following expressions are taken from the Akademie.
 *
 * @addtogroup Language
 *
 * @author Caesius noh en Idee vum Manes
 */
/** 
 * Hints for editing
 * Avoid Ã¤ and other special codings because of legibility for those users, 
 * who will take this as a basis for further ripuarian message interfaces
 * Ã¤ => ä, Ã¶ => ö, Ã¼ => ü, Ã„ => Ä, Ã– => Ö, Ãœ => Ü, ÃŸ => ß
 * â€ž => „, â€œ => “
 */
/**
 * Fallback language, used for all unspecified messages and behaviour. This
 * is English by default, for all files other than this one.
 */
$fallback = 'de';

$namespaceNames = array(
        NS_MEDIA            => 'Medie',
        NS_SPECIAL          => 'Spezial',
        NS_MAIN             => '',
        NS_TALK             => 'Klaaf',
        NS_USER             => 'Metmaacher',
        NS_USER_TALK        => 'Metmaacher_Klaaf',
        # NS_PROJECT set by $wgMetaNamespace
        NS_PROJECT_TALK     => '$1_Klaaf',
        NS_IMAGE            => 'Beld',
        NS_IMAGE_TALK       => 'Belder_Klaaf',
        NS_MEDIAWIKI        => 'MediaWiki',
        NS_MEDIAWIKI_TALK   => 'MediaWiki_Klaaf',
        NS_TEMPLATE         => 'Schablon',
        NS_TEMPLATE_TALK    => 'Schablone_Klaaf',
        NS_HELP             => 'Hölp',
        NS_HELP_TALK        => 'Hölp_Klaaf',
        NS_CATEGORY         => 'Saachjrupp',
        NS_CATEGORY_TALK    => 'Saachjrupp_Klaaf',
);

/**
 * Array of namespace aliases, mapping from name to NS_xxx index
 */
$namespaceAliases = array(
	'Meedije'           => NS_MEDIA,
	'Shpezjal'          => NS_SPECIAL,
	'Medmaacher'        => NS_USER,
	'Medmaacher_Klaaf'  => NS_USER_TALK,
	'Belld'             => NS_IMAGE,
	'Bellder_Klaaf'     => NS_IMAGE_TALK,
	'MedijaWikki'       => NS_MEDIAWIKI,
	'MedijaWikki_Klaaf' => NS_MEDIAWIKI_TALK,
	'Hülp'              => NS_HELP,
	'Hülp_Klaaf'        => NS_HELP_TALK,
	'Sachjrop'          => NS_CATEGORY,
	'Sachjrop_Klaaf'    => NS_CATEGORY_TALK,
	'Saachjropp'        => NS_CATEGORY,
	'Saachjroppe_Klaaf' => NS_CATEGORY_TALK,
	'Kattejori'         => NS_CATEGORY,
	'Kattejori_Klaaf'   => NS_CATEGORY_TALK,
	'Kategorie'         => NS_CATEGORY,
	'Kategorie_Klaaf'   => NS_CATEGORY_TALK,
	'Katejori'          => NS_CATEGORY,
	'Katejorije_Klaaf'  => NS_CATEGORY_TALK,
);

$separatorTransformTable = array(',' => "\xc2\xa0", '.' => ',' );

/**
 * Skin names. If any key is not specified, the English one will be used.
 */
$skinNames = array(
        'standard'      => 'Standaad',
        'nostalgia'     => 'Nostaljesch',
        'cologneblue'   => 'Kölsch Blau',
        'monobook'      => 'MonoBoch',
        'myskin'        => 'Ming Skin',
        'chick'         => 'Höhnche'
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Donn de Lėngkß ungershtriishe:',
'tog-highlightbroken'         => 'Zëijsh de Lėngkß op Sigge, di_jet_non_nit_jitt, esu met: „<a href="" class="new">Lämma</a>“ aan.<br />Wännß_De dat nit wellß, weed et esu: „Lämma<a href="" class="internal">?</a>“ jezëijsh.',
'tog-justify'                 => 'Donn de Affschnedde em <a href="http://ksh.wikipedia.org/wiki/Help:Bloksaz">Bloksaz</a> aanzëije',
'tog-hideminor'               => 'Donn de klëijn minni_Ännderonge (<strong>÷:ksh:MediaWiki:Minoreditletter</strong>) en_de Lėßß_met „÷:ksh:MediaWiki:Recentchanges“ shtanndad_määßish <strong>nit</strong> aanzëije',
'tog-extendwatchlist'         => 'Forjrüüßo de Oppaßß_Lėßß för jeede Aat fun mööshlėshe Ännderonge ze_zëije',
'tog-usenewrc'                => 'Donn_de Oppjemozzde Lėßß_met „÷:ksh:MediaWiki:Recentchanges“ aanzëije (bruch <a href="http://ksh.wikipedia.org/wiki/Help:Java_Skripp">Java_Skripp</a>)',
'tog-numberheadings'          => 'Donn de Övverschreffte automatish nummerėere',
'tog-showtoolbar'             => 'Zëijsh de Wërrkzöüsh_Lëßß zom ÷:ksh:MediaWiki:Edit aan (bruch <a href="http://ksh.wikipedia.org/wiki/Help:Java_Skripp">Java_Skripp</a>)',
'tog-editondblclick'          => 'Sigge med Dubbel-Klikke ÷:ksh:MediaWiki:Edit (bruch <a href="http://ksh.wikipedia.org/wiki/Help:Java_Skripp">Java_Skripp</a>)',
'tog-editsection'             => 'Maach [÷:ksh:MediaWiki:Editsection]-Lėngkß aan de Affschnedde raan',
'tog-editsectiononrightclick' => 'Affschnedde med Räähß-Klikke op de Övverschrevv_Änndere
(bruch [http://ksh.wikipedia.org/wiki/Help:Java_Skripp Java_Skripp])',
'tog-showtoc'                 => 'Zëijsj_en Ėnnhallds_Övverseesh bëij Sigge met_mieh_vi drëij Övverschreffte dren',
'tog-rememberpassword'        => '÷:ksh:MediaWiki:Remembermypassword',
'tog-editwidth'               => 'Maach dat Felld zom Täxx_Ėnnjävve_su_brëijdt, vi_t jëijdt',
'tog-watchcreations'          => 'Donn di Sigge fö ming Oppaßß_Lėßß fürschlaare, di_ish nöü aanläje',
'tog-watchdefault'            => 'Donn di Sigge fö ming Oppaßß_Lėßß fürschlaare, di_isch aanpakke un änndere donn',
'tog-minordefault'            => 'Donn all ming Ännderonge shtandad_mäßėj_allß klëijn Minni_Ännderonge fürschlaare',
'tog-previewontop'            => 'Zëijsh de ÷:ksh:MediaWiki:Preview övver dämm Felld för_dä Täxx ėnn_ze_jävve aan.',
'tog-previewonfirst'          => 'Zëijsh de ÷:ksh:MediaWiki:Preview tirräg füür_em eetzte Mool bëijm Beärrbëijde aan',
'tog-nocache'                 => 'Donn et Sigge_Zweshe_Shpëijshere — et <a href="http://ksh.wikipedia.org/wiki/Help:Cache">Caching</a> — affschallde',
'tog-enotifwatchlistpages'    => 'Schegg_en e-mail, wänn_en Sigg_uß minge Oppaßß_Lėßß jeänndot wood',
'tog-enotifusertalkpages'     => 'Scheck mer e-mail, wänn ming ÷:ksh:Talk_Sigk jeänndot weed',
'tog-enotifminoredits'        => 'Scheck mer och en e-mail för klëijn Minni_Ännderonge',
'tog-enotifrevealaddr'        => 'Zëijsh ming e-mail Addräßß aan, en de Benohreshtėjonge pä e-mail',
'tog-shownumberswatching'     => 'Zëijsh de Aanzal ÷:ksh:Users di op di Sigk op_am_paßße sinn',
'tog-fancysig'                => 'Ungerschreff oohne outomatėshe Lėngk',
'tog-externaleditor'          => 'Nemm shtandad_mäßėsh en ëxtärrn „<a href="http://ksh.wikipedia.org/wiki/Help:Editor">editor</a>“-Projramm',
'tog-externaldiff'            => 'Nemm shtandad_mäßėsh en ëxtärrn „<a href="http://ksh.wikipedia.org/wiki/Help:Diff">diff</a>“-Projramm',
'tog-showjumplinks'           => 'Lėngkß ußjävve, di dem „bajjeerefrëije Zoojang“ hellfe důnn',
'tog-uselivepreview'          => 'Zëijsh_de „÷:ksh:MediaWiki:Showlivepreview“ (bruch <a href="http://ksh.wikipedia.org/wiki/Help:Java_Skripp">Java_Skripp</a>) (em Ußprobier_Shtadijum)',
'tog-forceeditsummary'        => 'Frooch nooh, wänn_en_dämm Felldt „÷:ksh:MediaWiki:Summary“ bëijem Affshpëijshere nix dren shtëijdt',
'tog-watchlisthideown'        => 'Donn stanndad_määßisch ming ëijen
Änderonge <strong>nit</strong> en minger Oppaßß_Lėßß aanzëije',
'tog-watchlisthidebots'       => 'Donn stanndad_määßisch dä <a class="plainlinks" href="http://ksh.wikipedia.org/wiki/Help:÷:ksh:MediaWiki:group-bot-member">÷:ksh:MediaWiki:group-bot</a>
ier Änderonge <strong>nit</strong> en minger Oppaßß_Lėßß zëije',

'underline-always'  => 'jo, ėmmer',
'underline-never'   => 'nä',
'underline-default' => 'nemm dem Brauser sing Ėnshtällung',

'skinpreview' => '<!-- --> 
(Aanluere)',

# Dates
'sunday'       => 'Sunndaach',
'monday'       => 'Mohndaach',
'tuesday'      => 'Dinnßdaach',
'wednesday'    => 'Medtvoch',
'thursday'     => 'Dunnorßdaach',
'friday'       => 'Friedaach',
'saturday'     => 'Sammbsdaach',
'sun'          => 'So.',
'mon'          => 'Mo.',
'tue'          => 'Di.',
'wed'          => 'Me.',
'thu'          => 'Do.',
'fri'          => 'Fr.',
'sat'          => 'Sa.',
'january'      => 'Jannowaa',
'february'     => 'Febrewar',
'march'        => 'Määz',
'april'        => 'Aprel',
'may_long'     => 'Mëij',
'june'         => 'Juuni',
'july'         => 'Juuli',
'august'       => 'Aujuss',
'september'    => 'Säptämmbo',
'october'      => 'Oktoobo',
'november'     => 'Novämmbo',
'december'     => 'Dezember',
'january-gen'  => 'Janewar',
'february-gen' => 'Febrewar',
'march-gen'    => 'Määz',
'april-gen'    => 'Aprel',
'june-gen'     => 'Juni',
'july-gen'     => 'Juli',
'august-gen'   => 'Aujuss',
'october-gen'  => 'Oktober',
'december-gen' => 'Dezember',
'jan'          => 'Jan',
'mar'          => 'Mäz',
'may'          => 'Mej',
'jun'          => 'Jun',
'jul'          => 'Jul',
'aug'          => 'Auj',
'sep'          => 'Sep',
'oct'          => 'Okt',
'nov'          => 'Nov',
'dec'          => 'Dez',

# Bits of text used by many pages
'categories'            => 'Saachjruppe',
'pagecategories'        => '{{PLURAL:$1|Saachjrupp|Saachjruppe}}',
'category_header'       => 'Atikkele in de Saachjrupp „$1“',
'subcategories'         => '÷:ksh:Subcategories',
'category-media-header' => 'Medie en de Saachjrupp "$1"',

'mainpagetext'      => '<big><strong>MediaWiki eß jäz enshtallėerdt.</strong></big>',
'mainpagedocfooter' => "Luer en dä [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] wänn De weßße wellß wi de Wikki_ßoffwäer jebruch un bedeendt weede moß.

== För der Aanfang ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikipedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Üvver {{SITENAME}}',
'article'        => 'Atikkel',
'newwindow'      => '(Määd_e nöü Finßter op, wänn Dinge Brauser datt kann)',
'cancel'         => 'Stopp! Avbreche!',
'qbfind'         => 'Fingk',
'qbbrowse'       => 'Aanluere',
'qbedit'         => 'Änndere',
'qbpageoptions'  => 'Sigge_Ëijnshtällunge',
'qbpageinfo'     => 'Zosammehang',
'qbmyoptions'    => 'Ming Sigge',
'qbspecialpages' => 'Shpezzjahl_Sigge',
'moredotdotdot'  => 'Mieh…',
'mypage'         => 'Ming Sigk',
'mytalk'         => 'ming ÷:ksh:Talksigk',
'anontalk'       => 'Klaaf för de IP-Adress',
'navigation'     => 'Jangk_noh',

# Metadata in edit box
'metadata_help' => 'Däm Belld_sing Metta_Daate ([[{{ns:project}}:Metta_Daate fun Bellder|hee sin_se äkliert]])',

'errorpagetitle'    => 'Fähler',
'returnto'          => 'Jangk widdo_noh: „$1“.',
'tagline'           => 'Uß de {{SITENAME}}',
'help'              => 'Hülp',
'search'            => 'em Täxx',
'searchbutton'      => 'Sööke',
'go'                => 'Loss Jonn',
'searcharticle'     => 'Atikkel',
'history'           => 'Väsjohne',
'history_short'     => 'Väsjohne',
'updatedmarker'     => '(foänndot)',
'info_short'        => 'Ėnnfommazjohn',
'printableversion'  => 'För_ze Drokke',
'permalink'         => 'Allß Permalink',
'print'             => 'För_ze Drokke',
'edit'              => 'Ändere',
'editthispage'      => 'De Sigg ändere',
'delete'            => 'Fottschmieße',
'deletethispage'    => 'De Sigg fottschmieße',
'undelete_short'    => '{{PLURAL:$1|ëijn Ännderong|$1 Ännderonge}} zerrökholle',
'protect'           => 'Shöze',
'protectthispage'   => 'Di Sigk schöze',
'unprotect'         => 'Schoz änndere',
'unprotectthispage' => 'Dä Schoz fö_di Sigk ophävve',
'newpage'           => 'Nöü Sigk',
'talkpage'          => 'Övver di Sigk hee schwaade',
'specialpage'       => 'Söndersigk',
'personaltools'     => '÷:ksh:User_Wërrkzöüsh',
'postcomment'       => 'Nöü Affschnett op_de ÷:ksh:Talk_Sigk',
'articlepage'       => 'Aanluure wat op dä Sigg drop steiht',
'talk'              => '÷:ksh:Talk',
'views'             => 'Aansėshte',
'toolbox'           => 'Wërrkzöüsh',
'userpage'          => 'Däm ÷:ksh:User sing Sigk aanluere',
'projectpage'       => 'De Projäkk_Sigk aanluere',
'imagepage'         => 'Bėlld_Sigk aanluere',
'mediawikipage'     => 'De Meddëijongß_Sigk aanluere',
'templatepage'      => 'De Schablohn ier Sigk aanluere',
'viewhelppage'      => 'De Hülp_Sigk aanluere',
'categorypage'      => 'De Saachjruppesigg aanluure',
'viewtalkpage'      => '÷:ksh:Talk aanluere',
'otherlanguages'    => 'En annder Shprooche',
'redirectedfrom'    => '(Ömjelëijdt fun $1)',
'redirectpagesub'   => 'Ömlëijdungß_Sigk',
'viewcount'         => 'Di Sigk eß beß jäz {{PLURAL:$1|ëijmol|$1 Mol}} affjeroofe woode.',
'protectedpage'     => 'Jeshözde Sigk',
'jumpto'            => 'Jangk noh:',
'jumptonavigation'  => 'Noh_de Navvijazzjohn',
'jumptosearch'      => 'Jangk Sööke!',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Üvver de {{SITENAME}}',
'aboutpage'         => 'Project:Üvver de {{SITENAME}}',
'bugreports'        => 'Fähler melde',
'bugreportspage'    => 'Project:Kontak',
'copyright'         => 'Dä Enhald steiht unger de $1.',
'copyrightpagename' => 'Lizenz',
'copyrightpage'     => '{{ns:project}}:Lizenz',
'currentevents'     => 'Et Neuste',
'currentevents-url' => 'Project:Et Neuste',
'disclaimers'       => 'Hinwies',
'disclaimerpage'    => 'Project:Impressum',
'edithelp'          => 'Hölp för et Bearbeide',
'edithelppage'      => 'Help:Hölp',
'helppage'          => 'Help:Hölp',
'mainpage'          => 'Houpsigk',
'portal'            => 'Övver {{SITENAME}}',
'portal-url'        => 'Project:Metmaacher Pooz',
'privacy'           => 'Dateschotz un Jehëijmhalldung',
'privacypage'       => 'Project:Daateschotz un Jeheimhaldung',
'sitesupport'       => 'Shpännde',
'sitesupport-url'   => 'Project:Spende',

'badaccess'        => 'Nit jenoch Räächde',
'badaccess-group0' => 'Do häs nit jenoch Räächde.',
'badaccess-group1' => 'Wat Do wells, dat dürfe nor Metmaacher, die $1 sin.',
'badaccess-group2' => 'Wat Do wells, dat dürfe nor de Metmaacher us dä Jruppe: $1.',
'badaccess-groups' => 'Wat Do wells, dat dürfe nor de Metmaacher us dä Jruppe: $1.',

'versionrequired'     => 'De Värsjon $1 fun MediaWiki ßoffwäer eß nüüdish',
'versionrequiredtext' => 'De Värsjon $1 fun MediaWiki ßoffwäer eß nüüdish, öm di Sigk he bruche ze künne. Süsh op [[Special:Version|de Väsjohnß_Sigk]], wat mer hee förr_enne ßoffwäer_shtanndt hann.',

'ok'                      => 'Okee',
'retrievedfrom'           => 'Die Sigk hee shtammp uß „$1“.',
'youhavenewmessages'      => 'Do häßß $1 ($2).',
'newmessageslink'         => 'nöü Meddëijlonge op Dinger ÷:ksh:Talk_Sigk',
'newmessagesdifflink'     => 'Ungerscheed zor füürläzde Väsjoon',
'youhavenewmessagesmulti' => 'Do häßß nöü Nohrishte op $1',
'editsection'             => 'Ändere',
'editold'                 => 'Hee die Version ändere',
'editsectionhint'         => 'Avschnedd ändere: $1',
'toc'                     => 'Enhalldtß_Övverseesh',
'showtoc'                 => 'ennblännde',
'hidetoc'                 => 'ußblännde',
'thisisdeleted'           => '$1 — aanluere odder widder zerrögk_holle?',
'viewdeleted'             => '$1 aanzëije?',
'restorelink'             => '{{PLURAL:$1|ëijn fottjeschmeßßen Ännderong|$1 fottjeschmeßßene Ännderonge}}',
'feed-invalid'            => 'Esu en Zoot Abonnemang jitt et nit.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Atikkel',
'nstab-user'      => '÷:ksh:User_Sigk',
'nstab-media'     => 'Medije_Sigk',
'nstab-special'   => 'Shpezzjahl',
'nstab-project'   => 'Projägk_Sigk',
'nstab-image'     => 'Belldt',
'nstab-mediawiki' => 'Täxx',
'nstab-template'  => 'Schabbloon',
'nstab-help'      => 'Hülp',
'nstab-category'  => '÷:ksh:Category',

# Main script and global functions
'nosuchaction'      => 'Di Oppjav (action=) känne mer nit',
'nosuchactiontext'  => '<strong>Na su_jëtt:</strong> Di Oppjaaf us dä [http://ksh.wikipedia.org/wiki/URL URL], di_do hėnger „<code>action=</code>“ dren shtëijdt, jo_di kännt hee dat Wikki jaa_nit.',
'nosuchspecialpage' => 'Esu en Söndersigk ham_mer nit',
'nospecialpagetext' => 'De aanjefrochte Sondersigg jitt et nit, de [[Special:Specialpages|Liss met de Sondersigge]] helfe dir wigger.',

# General errors
'error'                => 'Fähler',
'databaseerror'        => 'Fähler en de Daatebank',
'dberrortext'          => 'Ene Fähler es opjefalle en dä Syntax vun enem Befähl för de Daatebank. Dat künnt ene Fähler en de Soffwär vum Wiki sin.
De letzte Daatebankbefähl es jewäse:
<blockquote><code>$1</code></blockquote>
us däm Projramm singe Funktion: „<code>$2</code>“.<br />
MySQL meld dä Fähler: „<code>$3: $4</code>“.',
'dberrortextcl'        => 'Ene Fähler es opjefalle en dä Syntax vun enem Befähl för de Daatebank.
Dä letzte Befähl för de Daatebank es jewäse:
<blockquote><code>$1</code></blockquote>
us däm Projramm sing Funktion: „<code>$2</code>“.<br />
MySQL meld dä Fähler: „<code>$3: $4</code>“.',
'noconnect'            => 'Schadt!
Mer kunnte këijn Fobinndung med_däm Daate_Bank_ßöövo op „$1“ krijje.',
'nodb'                 => 'Kunnt de Daate_Bangk „$1“ nit ußßwääle',
'cachederror'          => 'Dat hee es en Kopie vun dä Sigg us em Cache. Möchlich, se es nit aktuell.',
'laggedslavemode'      => '<strong>Opjepaßß:</strong> Künnt sinn, dat hee nit dä nöüßte Shtanndt fun dä Sigk annjezëijsh weedt.',
'readonly'             => 'De Daate_Bangg_eß jeshpächt',
'enterlockreason'      => 'Jevv aan, woröm un för wie lang dat de Daatebank jesperrt wääde soll',
'readonlytext'         => 'De Daate_Bangk eß jeshpächt. Nöü Saache dren affshpëijshere jëijd_jrad nit, un Änndere och nit. Et weed wall_öm_de nommaale Waadung joonn. Důnn_et ëijnfarr_enn_e_paa Menutte widdo fosööke.',
'missingarticle'       => 'Dä Täxx fö de Sigk „$1“ kunndte mer nit en de Date_Bank finge.

Di Sigk iß fellëijsh fottjeschmeßße oddo ömmjenanndt woode.

Wann dat esu nit sinn sullt, dann hadd_Er fellëijsh_enne Fääler en de ßoffwäer fefonge.
Vozälld_ed_ennem [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]],
un doohd_em och de URL fun dä Sigk hee saare.',
'readonly_lag'         => 'De Daate_Bank eß fö_n koote Zigk jeshpächt, fö_de Daate aff_ze_jliishe.',
'internalerror'        => 'De Wikki-ßoffwäer hädd_enne Fääler jefonge',
'filecopyerror'        => 'Kunnt de Datei „$1“ nit noh „$2“ kopeere.',
'filerenameerror'      => 'Kunnt de Datei „$1“ nit op „$2“ ömdäufe.',
'filedeleteerror'      => 'Kunnt de Datei „$1“ nit fottschmieße.',
'filenotfound'         => 'Kunnt de Datei „$1“ nit finge.',
'unexpected'           => 'Domet hät këijne jo jeräshnet: „$1“=„$2“',
'formerror'            => 'Dat es donevve jejange: Wor nix, met däm Fomular.',
'badarticleerror'      => 'Dat jeiht met hee dä Sigg nit ze maache.',
'cannotdelete'         => 'De Sigg oder de Datei hee fottzeschmieße es nit möchlich. Möchlich, dat ene andere Metmaacher flöcker wor, hät et vürher hee jo ald jedon, un jetz es die Sigg ald fott.',
'badtitle'             => 'Verkihrte Üvverschreff',
'badtitletext'         => 'De Üvverschreff es esu nit en Odenung. Et muss jet dren stonn.
Et künnt sin, dat ein vun de speziell Zeiche dren steiht,
wat en Üvverschrefte nit erlaub es.
Et künnt ussinn, wie ene InterWikiLink,
dat jeiht ävver nit.
Muss De repareere.',
'perfdisabled'         => "<strong>'''Opjepaßß:'''</strong> Dat maache mer jäz nit — dä ßööver hät jraad zo_fill Laßß — do sim_mer jät fürseshtesh.",
'perfcached'           => 'De Daate he_noh kumme ussem Zwesheshpëijshor ([http://ksh.wikipedia.org/wiki/Help:Cache cache]) un künnte nit_mieh_janz de allonöüßte sinn.',
'perfcachedts'         => 'De Daate he_noh kumme ussem Zwesheshpëijshor ([http://ksh.wikipedia.org/wiki/Help:Cache cache]) un woodte $1 opjenumme. Se künnte nit_janz de allonöüßte sinn.',
'wrong_wfQuery_params' => 'Fokiehrte Parrammeter för: <strong><code>wfQuery()</code></strong><br />
De Funkßjohn eß: „<code>$1</code>“<br />
De Aanfrooch eß: „<code>$2</code>“<br />',
'viewsource'           => 'Wikki_Täx Aanluere',
'viewsourcefor'        => 'för di Sigk: „$1“',
'protectedinterface'   => 'Op dä Sigk hee shtëijdt Täggs_ussem Ingerfäjß fun de Wikki-ßoffwäer.
Dröm eß dii jäje Änderonge jeschöz, domet Këijne ööhndsenne Meßß domet aanshtälle künne sull.',
'editinginterface'     => '<strong>Opjepass:</strong> 
Op dä Sigg hee steiht Tex usem Interface vun de Wiki-Soffwär. Dröm es die jäje Änderunge jeschötz, domet keine Mess domet aanjestallt weed. Nor de Wiki-Köbese künne 
se ändere. Denk dran, hee ändere deit et Ussinn un de Wööt ändere met dänne et Wiki op de Metmaacher un de Besöker drop aankütt!',
'sqlhidden'            => '(Dä SQL_Befääl dům_mer nit zëije)',

# Login and logout pages
'logouttitle'                => 'Ußß_Logge',
'logouttext'                 => 'Jäz beß_De ußßjelogg.

* Do künnz op de {{SITENAME}} wigger maache, alls_enne name_lose ÷:ksh:User.

* Do kannß Dėjj_ävver_och widdo [[Special:Userlogin|ėnnlogge]], allß do_sälləve oddo och enne anndere ÷:ksh:User.

* Un Do kanns_enne <span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} enne nöüje ÷:ksh:User aanmällde]</span>.

<strong>Opjepaßß:</strong>

Eß mööshlish, dat_Te de ëijn_oddo_anndere Sigk ėmmo wiggo aanjezëijsh krißß, wi wänn de noch ėnnjelogg_wööhß. Donn Dingem [http://ksh.wikipedia.org/wiki/Help:Brauser Brauser] singe [http://ksh.wikipedia.org/wiki/Help:Cache Cache] fottschmiiße oddo leddish_maache, öm uß dä Nummo_erruß_ze_kumme!<br />',
'welcomecreation'            => '== Tach, $1! ==

Dinge Zojang fö_hee eß doh. Do beß jäz aanjemälldt. Dengk draan, Do küünz Der Ding [[Special:Preferences|÷:ksh:MediaWiki:preferences>]] hee op de {{SITENAME}} zerrääshmaache.',
'loginpagetitle'             => 'Ėnnlogge',
'yourname'                   => '÷:ksh:User_Name',
'yourpassword'               => 'Paßßwoodt',
'yourpasswordagain'          => 'Norr_enß dat Paßßwoodt',
'remembermypassword'         => 'Op_Dauer Aanmällde',
'yourdomainname'             => 'Ding Domain',
'externaldberror'            => 'Do wor ene Fähler en de externe Daatebank, oder Do darfs Ding extern Daate nit ändere. Dat Aanmelde jingk donevve.',
'loginproblem'               => '<strong>Med däm Ėnnlogge eß jëtt schëijf jeloufe.</strong><br />Beß esu jood, un důnn_et norr_enß fosööhke!',
'login'                      => 'Ėnnlogge',
'loginprompt'                => 'Öm op de {{SITENAME}} [[Special:Userlogin|ennlogge]] ze künne,
moßß_De [http://ksh.wikipedia.org/wiki/Help:Cookie%C3%9F de Cookieß] en Dingem [http://ksh.wikipedia.org/wiki/Brauser Brause] ennjeschalldt hann.',
'userlogin'                  => 'Ėnnlogge / ÷:ksh:User wääde',
'logout'                     => 'Ußß_Logge',
'userlogout'                 => 'Ußlogge',
'notloggedin'                => 'Nėd_Ėnnjelogg',
'nologin'                    => 'Wänn_De Dėsh noh_nit aanjemälldt häßß,
dann donn Dėsh $1.',
'nologinlink'                => 'Nöü Aanmällde',
'createaccount'              => 'Aanmelde als ene neue Metmaacher',
'gotaccount'                 => 'Do häs ald en Aanmeldung op de {{SITENAME}}? Dann jangk nohm $1.',
'gotaccountlink'             => 'Enlogge',
'createaccountmail'          => 'Passwood met E-Mail Schecke',
'badretype'                  => 'Ding zwei enjejovve Passwööder sin ungerscheedlich. Do muss De Dich för ein entscheide.',
'userexists'                 => 'Enne ÷:ksh:User med_däm name: „<strong>$1</strong>“ jidd_et alld. Schaadt. Doh moßß De Der_enne anndere Naame ußdängke.',
'youremail'                  => 'E-mail *',
'username'                   => '÷:ksh:User_Name:',
'uid'                        => '÷:ksh:User ID:',
'yourrealname'               => 'Dinge rishtijje Name *',
'yourlanguage'               => '<span title="Söök de Shprooch uß, di_t Wikki kalle sůll!">Shprooch:<span>',
'yourvariant'                => 'Ding Varijant',
'yournick'                   => 'Name fö_en_Dinge Ungerschreff:',
'badsig'                     => 'De Ungeschreff jeiht esu nit - luur noh dem HTML dodren un maach et richtich.',
'email'                      => 'E-Mail',
'prefs-help-realname'        => '* Dinge rishtijje Name — kannz_E fott_loohße — wänn_De_n nänne wellß, do weedt_e jebruch, öm Ding Bëijdrääsh hee, domet ze schmökke.',
'loginerror'                 => 'Fääler bem Ennlogge',
'prefs-help-email'           => '* E-mail — kannß_De fott_loohße, un es för Anndre nit_tse sinn — määd_et ävver mööshlish, dat mer met Dier en Kontak_kumme kann, oohne dat mer Dinge Name odder Ding e-mail Adräß känne däät.',
'nocookiesnew'               => 'Dinge nöüje ÷:ksh:User_Name eß ėnnjerėshdt, ävver dat outomaatish Ėnnlogge woo dan_nix. Schaadt. De {{SITENAME}} bruch [http://ksh.wikipedia.org/wiki/Help:Cookie%C3%9F Cookieß], öm ze merrəke, wä ėnjelogg_eß. Wänn_De Cookieß affjeschaldt häß, en Dingem [http://ksh.wikipedia.org/wiki/Brauser Brauser], dann kann dat nit loufe. Söök_Der_enne Brauser, dä et kann, donn_se ennschallde, un dann log Dėsh norr_enß nöü ėnn, met Dingem nöüje ÷:ksh:User_Name un Paßßwoodt.',
'nocookieslogin'             => 'De {{SITENAME}} bruch [http://ksh.wikipedia.org/wiki/Help:Cookie%C3%9F Cookieß] förr_et Ėnlogge. Et süüht esu uß, alß hättß_de Cookieß affjeschalldt. Důnn_se aanschallde un_dann fosöhg_et norr_enß.',
'noname'                     => 'Dat jëijdt nidd_alls_enne ÷:ksh:User_Naame. Jäz moßß_De_et norr_enß fosööke.',
'loginsuccesstitle'          => 'Dat Ėnlogge hät jeflupp.',
'loginsuccess'               => '<br />Do beß jäz enjelogg_bëij_de <strong>{{SITENAME}}</strong>, un Dinge ÷:ksh:User_Naame eß „<strong>$1</strong>“.<br />',
'nosuchuser'                 => 'Dat Passwoot odder dä ÷:ksh:User_Naam woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.

Odder_<span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} enne nöüje ÷:ksh:User aanmällde]</span>.',
'nosuchusershort'            => 'Dä ÷:ksh:User_Naam woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.',
'nouserspecified'            => 'Dat jëijdt nidd_alls_enne ÷:ksh:User_Naame',
'wrongpassword'              => 'Dat Passwoot odder dä ÷:ksh:User_Naame woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.',
'wrongpasswordempty'         => 'Dat Paßßwoodt kam_mer nit fott_loohße.
Jäz moßß_De_et norr_enß fosööke.',
'passwordtooshort'           => 'Dat Paßßwood_ėß jät koot — et mööte alld winnishßdenß <strong>$1</strong> Zëijshe, Zėffere, un Boochshtaave do_dren sinn.',
'mailmypassword'             => 'Paßßwoodt fojäßße?',
'passwordremindertitle'      => 'Login op {{SITENAME}}',
'passwordremindertext'       => 'Joot mööshlish, Do wooß et sellver,
fun de IP_Addräßß $1,
jedenfallß hät Eijne aanjefrooch, dat
mer Dier e neu Paßßwoodt zo_schekke sull,
för et Ennlogge en de {{SITENAME}} op
{{FULLURL:{{MediaWiki:Mainpage}}}}
($4)

Allso, e neu Paßßwoodt för "$2"
es jäz füürjemerrek: "$3".
Do sulltß De tiräg jlish enlogge,
un dat Passwoot widde ännderre.
Dä Tranßpocht övver et Näz met e-mail
eß unsesher, do künne Främbde metlässe,
un winnishßtenß de Jehäjmdeenßte dunn
dat och. Ußßerdämm eß "$3" 
felleijsh ned_esu joot ze merreke?

Wänn nit Do, söndern sönß wer noh däm
neue Paßßwoodt forlangk hätt, wänn De 
Desh jäz doch widde aan Ding ahl Paßßwoodt
äntsenne kannß, jo da bruchß de jaa nix
ze donn, da kannß De Ding ahl Paßßwood_wigge
bruche, un di e-mail hee, di kannß De 
jlatt forjäßße.

Enne schööne Jrooß fun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',
'noemail'                    => 'Dä ÷:ksh:User hät këijn e-mail Addräßß aanjejovve.',
'passwordsent'               => 'E nöü Paßßwoodt eß aan de e-mail Addräßß fun däm ÷:ksh:User ungerwähß. Mälldt desh do_met aan, wänn_De_t häßß. Dat aahle Paßßwoodt blief ähallde un kann och noch jebruch wääde, beß dat De Dejj_et eezt Mohl met däm Nöüe ėnnjelogk häßß.',
'blocked-mailpassword'       => 'Ding IP Adress es blockeet.',
'eauthentsent'               => 'En E-Mail es jetz ungerwähs aan de Adress, die en de Enstellunge vum Metmaacher $1 steiht.
Ih dat E-Mails üvver de {{SITENAME}} ehre E-Mail-Knopp verscheck wääde künne, muss de E-Mail Adress 
eets  ens bestätich woode sin. Wat mer doför maache muss, steiht en dä E-Mail dren, die jrad avjescheck woode es. 

Alsu luur do eren, un dun et.',
'throttled-mailpassword'     => 'En Erennerung för di Passwood es ungerwähs. Domet ene fiese Möpp keine Dress fabrizeet, passeet dat hüchstens eimol en $1 Stunde.',
'mailerror'                  => 'Fääle bëij_em e-mail foshekke: $1.',
'acct_creation_throttle_hit' => '<b>Schad.</b> Do häs ald {{PLURAL:$1|eine|$1}} Metmaacher Name aanjelaht. Mieh sin nit möchlich.',
'emailauthenticated'         => 'Ding E-Mail Adress wood bestätich om: <strong>$1</strong>.',
'emailnotauthenticated'      => 'Ding E-Mail Adress es <strong>nit</strong> bestätich. Dröm kann kein E-Mail aan Dich jescheck wääde för:',
'noemailprefs'               => 'Důnn_en e-mail Adräßß enndraare, domet dadd_all fluppe kann.',
'emailconfirmlink'           => 'Dun Ding E-Mail Adress bestätije looße',
'invalidemailaddress'        => 'Wat_De do alls_en e-mail Adräßß aanjejovve häß, süüt noh Drißß uß. En e-mail Addräss_en däm Fommat, dat jidd_et nit. Moß De reparėere — oddo Do määß dat Fëlld lëddish un schriifß nigs_errinn. Un_dann fosöög_et norr_enß.',
'accountcreated'             => 'Aanjemeldt',
'accountcreatedtext'         => 'De Aanmeldung för dä Metmaacher „<strong>$1</strong>“ es durch, kann jetz enlogge.',
'loginlanguagelabel'         => 'Shprooch: $1',

# Edit page toolbar
'bold_sample'     => 'Fett Schreff',
'bold_tip'        => 'Fett Schreff',
'italic_sample'   => '÷:ksh:Mediawiki:Italic_tip',
'italic_tip'      => 'Sheeve Schreff',
'link_sample'     => 'Angkor_Täxx',
'link_tip'        => 'Enne Lingk en de {{SITENAME}}',
'extlink_sample'  => 'http://www.example.com/ Dä Anker Tex',
'extlink_tip'     => 'Ene Link noh drusse (denk dran, http:// aan dr Aanfang!)',
'headline_sample' => 'Övverschreff',
'headline_tip'    => 'Övverschreff op de bövverschte Ebenne',
'math_sample'     => 'Hee schrieef di Forrmel hen',
'math_tip'        => 'En mattemaatisch Forrmelle nemm „LaTeX“',
'nowiki_sample'   => 'Hee kütt dä Täx hen, dä fun de Wikki_ßoffwäer net beärbëijdt, un en Rou jeloohße wääde sull',
'nowiki_tip'      => 'De Wikki_Koode övverjonn',
'image_sample'    => 'Beijshpill.jpg',
'image_tip'       => 'E Belltsche ennboue',
'media_sample'    => 'Beijshpill.ogg',
'media_tip'       => 'Enne Lengk ob_enn Ton_Datteij, e Filləmshe, odder_esu_jät',
'sig_tip'         => 'Dinge Naame, med de Urzigk unn_em Dattum',
'hr_tip'          => 'En Qweerlinnish',

# Edit pages
'summary'                   => 'Koot Zosammejefaßß, Kwälle',
'subject'                   => 'Övverschreff — wo_dröm jëijd_et?',
'minoredit'                 => 'Dad_ess_en klëijn Ännderung (mini)',
'watchthis'                 => 'Op di Sigk hee op_paßße',
'savearticle'               => 'Di Sigk Affspëijshere',
'preview'                   => 'Füür_Aansėsh',
'showpreview'               => 'Füür_Aansėsh Zëije',
'showlivepreview'           => 'Lebänndijje Füür_Aansėsh Zëije',
'showdiff'                  => 'De Ungerscheed zëije',
'anoneditwarning'           => 'Weil De nit aanjemeldt bes, weed Ding IP-Adress opjezeichnet wääde.',
'missingsummary'            => '<strong>Opjepaßß:</strong> Do häß nix bëij „÷:ksh:MediaWiki:Summary“ ennjejovve. Donn norr_enß op „<b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:Savearticle</b>“ klikke, öm Ding Ännderonge der_oohne_ze Shpëijshere, ävver bäßßo jißß_De do jätß_tirrägg_enß jätt enn!',
'missingcommenttext'        => 'Jivv_en „÷:ksh:MediaWiki:Summary“ aan!',
'missingcommentheader'      => "'''Opjepass:''' Do häs kein Üvverschreff för Dinge Beidrach enjejovve. Wann De noch ens op „De Sigg Avspeichere“ dröcks, weed dä Beidrach ohne Üvverschreff avjespeichert.",
'summary-preview'           => 'Vör-Aansich vun „Koot Zosammejefass, Quell“',
'subject-preview'           => 'Vör-Aansich vun de Üvverschreff',
'blockedtitle'              => 'Dä Metmaacher es jesperrt',
'blockedtext'               => '<big><b>Dinge Metmaacher Name oder IP Adress es vun „$1“ jesperrt woode.</b></big>
Als Jrund es enjedrage: „<i>$2</i>“
Do kanns met „$1“ oder ene andere Wiki-Köbes üvver dat Sperre schwaade, wann 
de wells.
Do kanns ävver nor dann dat „<i>E-Mail aan dä Metmaacher</i>“ aanwende, wann de ald en E-Mail Adress en Ding 
[[Special:Preferences|ming Enstellunge]] enjedrage un freijejovve häs.

Ding IP Adress es de „$3“. Dun se en Ding Aanfroge nenne.',
'blockedoriginalsource'     => 'Dä orjenal Wiki Tex vun dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'blockededitsource'         => 'Dä Wiki Tex vun <strong>Dinge Änderunge</strong> aan dä Sigg „<strong>$1</strong>“ steiht hee drunger:',
'whitelistedittitle'        => 'Enlogge nüüdish för Sigge ze Änndere',
'whitelistedittext'         => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]], öm hee em Wikki Sigge änndere ze dörrve.',
'whitelistreadtitle'        => 'Enlogge nüüdish för ze Lässe',
'whitelistreadtext'         => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]], öm hee Sigge Lësse ze dörrve.',
'whitelistacctitle'         => 'Këij Rääsh för ÷:ksh:User aan_ze_lääje.',
'whitelistacctext'          => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]] un shpezzjäll_et Rääsh dofüer hann, öm hee en dämm Wikki ÷:ksh:User ėnnrishte un aanlääje ze dörrəve.',
'confirmedittitle'          => 'För et Sigge Ändere muss De Ding E-Mail Adress ald bestätich han.',
'confirmedittext'           => 'Do muss Ding E-Mail Adress ald bestätich han, ih dat De hee Sigge ändere darfs. Drag Ding E-Mail Adress en Ding [[{{ns:special}}:Preferences|ming Enstellunge]] en, un dun „<span style="padding:2px; background-color:#ddd; color:black">Dun Ding E-Mail Adress bestätije looße</span>“ klicke.',
'loginreqtitle'             => 'Enlogge is nüüdish',
'loginreqlink'              => 'ėnnlogge',
'loginreqpagetext'          => 'Do moßß $1 ömm annder Sigge aanzeluere.',
'accmailtitle'              => 'Passwood verscheck',
'accmailtext'               => 'Dat Passwood för dä Metmaacher „$1“ es aan „$2“ jescheck woode.',
'newarticle'                => '(Nöü)',
'newarticletext'            => 'Ene Link op en Sigg, wo noch nix drop steiht, weil et se noch jar nit jitt, hät Dich 
noh hee jebraht.<br />
<small>Öm die Sigg aanzeläje, schriev hee unge en dat Feld eren, un dun et dann avspeichere. (Luur op de 
[[int:MediaWiki:Helppage|Sigge met Hölp]] noh, wann De mieh dodrüvver wesse wells)<br />Wann De jar nit hee hen 
kumme wollts, dann jangk zeröck op die Sigg, wo De herjekumme bes, Dinge Brauser hät ene Knopp doför.</small>',
'anontalkpagetext'          => '----
<i>Dat hee es de Klaaf Sigg för ene namenlose Metmaacher. Dä hät sich noch keine Metmaacher Name jejovve un 
enjerich, ov deit keine bruche. Dröm bruche mer sing IP Adress öm It oder In en uns Lisste fasszehalde. 
Su en IP Adress kann vun janz vill Metmaacher jebruch wääde, un eine Metmaacher kann janz flöck 
zwesche de ungerscheedlichste IP Adresse wähßele, womöchlich ohne dat hä et merk. Wann Do jetz ene namenlose 
Metmaacher bes, un fings, dat hee Saache an Dich jeschrevve wääde, wo Do jar nix met am Hot häs, dann bes Do 
wahrscheinlich och nit jemeint. Denk villeich ens drüvver noh, datte Dich [[Special:Userlogin|anmelde]] deis, 
domet De dann donoh nit mieh met esu en Ömständ ze dun häs, wie de andere namenlose Metmaacher hee.</i>',
'noarticletext'             => 'Hee eß jeds_em Momang këijne Täggs_ob_dä Sigk.<br />Jangk en de Täxte fun annder Sigge [[Special:Search/{{PAGENAME}}|noh däm Tittel sööhke]], oddor jangk, un <span class="plainlinks">[{{FULLURL:{{FULLPAGENAME}}|action=edit}} fang di Sigk aan]</span> ze schriive.<br /><small>Oddo_jangk zerök wo de heer koohmß. Dinge Brauser hädd_enne Knopp do_füer.</small>',
'clearyourcache'            => "<br clear=\"all\" style=\"clear:both\">
'''Opjepass:'''
Noh em Speichere, künnt et sin, datte Dingem Brauser singe Cache Speicher 
üvverlisste muss, ih datte de Änderunge och ze sinn kriss.
Beim '''Mozilla''' un  '''Firefox''' un '''Safari''', dröck de ''Jroß Schreff Knopp'' un 
Klick op ''Refresh'' / ''Aktualisieren'', oder dröck ''Ctrl-Shift-R'' / ''Strg+Jroß Schreff+R'', oder 
dröck ''Ctrl-F5'' / ''Strg/F5'' / ''Cmd+Shift+R'' / ''Cmd+Jroß Schreff+R'', je noh Ding Tastatur 
un Dingem Kompjuter.
Beim '''Internet Explorer''' dröck op ''Ctrl'' / ''Strg'' un Klick op ''Refresh'', oder dröck 
''Ctrl-F5'' / ''Strg+F5''.
Beim '''Konqueror:''' klick dä ''Reload''-Knopp oder dröck dä ''F5''-Knopp.
Beim  '''Opera''' kanns De üvver et Menue jonn un 
däm janze Cache singe Enhald üvver ''Tools?Preferences'' fottschmieße.",
'usercssjsyoucanpreview'    => '<b>Tipp:</b> Donn met dämm <b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:Showpreview</b>-Knobb_ußßprobėere, wat Ding nöü ÷:ksh:User_[http://ksh.wikipedia.org/wiki/Help:Cascading_Style_Sheets CSS]/[http://ksh.wikipedia.org/wiki/Help:Java_Skripp Java_Skripp] määd,_iih_dat_et affshpëijshore dëijß!',
'usercsspreview'            => '<b>Opjepaßß: Do beß hee nur am Ußßprobėere, wat Ding ÷:ksh:User_[http://ksh.wikipedia.org/wiki/Help:Cascading_Style_Sheets CSS] määd,_ed_eß non_nit jeseshot!</b>',
'userjspreview'             => '<b>Opjepaßß: Do beß hee nur am Ußßprobėere, wat Ding ÷:ksh:User_[http://ksh.wikipedia.org/wiki/Help:Java_Skripp Java_Skripp] määd_ed_eß non_nit jeseshot!</b>',
'userinvalidcssjstitle'     => '<strong>Opjepass:</strong> Et jitt kein Ussinn met däm Name: „<strong>$1</strong>“ - 
denk dran, dat ene Metmaacher eije Dateie för et Ussinn han kann, un dat die met kleine Buchstave 
aanfange dun, alsu etwa: {{ns:user}}:Name/monobook.css, un {{ns:user}}:Name/monobook.js heiße.',
'updated'                   => '(Aanjepakk)',
'note'                      => '<strong>Opjepaßß:</strong>',
'previewnote'               => '<strong>He kütt nur de Füür_Aanseesh — Ding Ännderonge sin_non_nit jeseshort!</strong>',
'previewconflict'           => 'He_di Füür_Aansėsh zëijsh dä Enhalldt fum bovvere Täxx_Fëlldt. Esu wöödt_dä Atikkel ußsinn, wänn_De_n jäz affshpëijshere dääts.',
'session_fail_preview'      => '<strong>Schaadt: Ding Ännderonge kunnte mer su nix mėd aanfange.

De Daate fun Dinge Login-Sëschen sinn nit öhndlėsh erövver jekumme, odder ëijnfach ze alldt.

Fosöög_et jraadt norr_enß. Wänn dat widder nit flupp, dann fosöög_et enß met [[Special:Userlogout|Ußlogge]] un_widder_Ėnnlogge. Ävver pass_op, dat_Te Ding Änderonge do_bëij behällß! Zo_Nuud důnn_se eetß enß bëij Dir om Räshno affshpëijshere.</strong>',
'session_fail_preview_html' => '<strong>Schaadt: Ding Ännderonge kunnte mer su nix mėd aanfange.<br />De Daate fun Dinge Login-Sëschen sinn nit öhndlėsh erövver jekumme, odder ëijnfach ze alldt.</strong>

Dat Wikki hee hät <i>rüüh HTML</i> zojeloohße, dröm weed de ÷:ksh:MediaWiki:Preview nit jezëijsh. Domet sollß_De jeschöz wääde — hoffe mer — un Aanjreffe med Java_Skripp jääje Dinge Kompjuto künne_Der nix aandonn.

<strong>Fallß fö Dėsh sönß alles jood_ußsüüht, fosöög_et jraadt norr_enß. Wann dat widder nit flupp, dann fosöög_et enß met [[Special:Userlogout|Ußlogge]] un_widder_Ėnnlogge. Ävver pass_op, dat_Te Ding Änderonge do_bëij behällß! Zo_Nuud důnn_se eetß enß bëij Dir om Räshno affshpëijshere.</strong>',
'editing'                   => 'De Sigg „$1“ ändere',
'editinguser'               => 'Metmaacher <b>$1</b> ändere',
'editingsection'            => 'Ne Avschnedd vun dä Sigg: „$1“ ändere',
'editingcomment'            => '„$1“ Ändere (ene neue Avschnedd schrieve)',
'editconflict'              => 'Problemche: „$1“ dubbelt bearbeidt.',
'explainconflict'           => '<br />Ene andere Metmaacher hät aan dä Sigg och jet jeändert, un zwar nohdäm Do et Ändere aanjefange häs. Jetz ha\'mer dr Dress am Jang, un Do darfs et widder uszoteere.
<strong>Opjepass:</strong>
<ul><li>Dat bovvere Texfeld zeich die Sigg esu, wie se jetz em Momang jespeichert es, alsu met de Änderunge vun alle andere Metmaacher, die flöcker wie Do jespeichert han.</li><li>Dat ungere Texfeld zeich die Sigg esu, wie De se selver zoletz zerääch jebraselt häs.</li></ul>
Do muss jetz Ding Änderunge och in dat <strong>bovvere</strong> Texxfeld eren bränge. Natörlich ohne dä 
Andere ihr Saache kapott ze maache.
<strong>Nor wat em bovvere Texfeld steiht,</strong> dat weed üvvernomme un avjespeichert, wann De „<b 
style="padding:2px; background-color:#ddd; color:black">De Sigg Avspeichere</b>“ klicks. Bes dohin kanns De esu off 
wie De wells op „<b style="padding:2px; background-color:#ddd; color:black">Vör-Aansich zeije</b>“ un „<b 
style="padding:2px; background-color:#ddd; color:black">De Ungerscheed zeije</b>“ klicke, öm ze pröfe, watte ald 
jods jemaat häs.

Alles Klor?<br /><br />',
'yourtext'                  => 'Dinge Täxx',
'storedversion'             => 'De jeshpëijshote Väsjohn',
'nonunicodebrowser'         => '<strong>Opjepaßß:</strong> Dinge [http://ksh.wikipedia.org/wiki/Brauser Brauser] kann nit ööndlėsh met däm [http://ksh.wikipedia.org/wiki/Unicode Unicode] un singe Boochstaave ömjonn. Bess_esu_joot un nėmmbs_enne anndere Brauser fö hee di Sigk!',
'editingold'                => '<strong>Opjepass!<br />
Do bes en ahle, üvverhollte Version vun dä Sigg hee am Ändere.
Wann De die avspeichere deis,
wie se es,
dann jonn all die Änderunge fleute,
die zickdäm aan dä Sigg jemaht woode sin.
Alsu:
Bes De secher, watte mähs?
</strong>',
'yourdiff'                  => 'Ungerscheede',
'copyrightwarning'          => 'Ding Beidräch stonn unger de [[$2]], süch $1. Wann De nit han wells, dat Dinge Tex ömjemodelt weed, un söns wohin verdeilt, dun en hee nit speichere. Mem Avspeichere sähs De och zo, dat et vun Dir selvs es, un/oder Do dat Rääch häs, en hee zo verbreide. Wann et nit stemmp, oder Do kanns et nit nohwiese, kann Dich dat en dr Bau bränge!',
'copyrightwarning2'         => 'De Beidräch en de {{SITENAME}} künne vun andere Metmaacher ömjemodelt 
oder fottjeschmesse wääde. Wann Der dat nit rääch es, schriev nix. Et es och nüdich, dat et vun Dir selvs es, oder dat Do dat Rääch häs, et hee öffentlich wigger ze jevve. Süch $1. Wann et nit stemmp, oder Do kanns et nit nohwiese, künnt Dich dat en dr Bau bränge!',
'longpagewarning'           => '<strong>Oppjepaßß:</strong> Dä Täxx, dä De hee jeschekk häß, dä eß <strong>$1</strong> [http://ksh.wikipedia.org/wiki/Help:Kilobyte Kilobyte] jruuß. Mansh [http://ksh.wikipedia.org/wiki/Help:Brauser Brauser] kütt nėt domet klooh, wänn_et mieh wi <strong>32</strong> Kilobyte sinn. Do künntß De drövver nohdängke, dat Dinge en klëijner Shtökshe ze_zerklope.',
'longpageerror'             => '<big><strong>Jannz Schlemme Fääler:</strong></big>

Dä Täxx, dä De hee jeschekk häß, dä eß <strong>$1</strong> [http://ksh.wikipedia.org/wiki/Kilobyte Kilobyte] jruuß. Dat sinn mieh wi <strong>$2</strong> Kilobyte. Dat künne mer nit Shpëijshere!

<strong>Maach klëijner Shtökke druß.</strong><br />',
'readonlywarning'           => '<strong>Opjepaßß:</strong> De Daate_Bangk eß jeshpächt woode, wo Do ald_am Änndere woohß. Däh. Jëz kannß_De Ding Änderonge nit mieh affshpëijshere. Donn se bëij Dir om Räshno faßßhallde un fosöög_et spääder norr_enß.',
'protectedpagewarning'      => '<strong>Opjepaßß:</strong> Di Sigk hee eß jäje Veränderonge jeschöz — wiso weed_em <span class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logbooch]</span> shtonn. Nuur de  [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop]] künne se änndere. Bess_esu jood un halldt Desh aan de Räjelle för dä Fall!',
'semiprotectedpagewarning'  => '<strong>Opjepaßß:</strong> Di Sigk hee eß hallef jeshpächt, wi mer sare, dat hëijß, Do moß [[Special:Userlogin|aanjemälldt un ėnnjelogk]] sinn, wänn_De draan änndere wellß.',
'templatesused'             => 'De Shabloone, di fun dä Sigk hee jebruch wääde, sinn:',
'templatesusedpreview'      => 'Schablone en dä Vör-Aansich hee:',
'templatesusedsection'      => 'Schablone en däm Avschnedd hee:',
'edittools'                 => '<!-- Dä Tex hee zeich et Wiki unger däm Texfeld zom „Ändere/Bearbeide“ un beim Texfeld vum „Huhlade“. -->',
'nocreatetitle'             => 'Ėnnlogge eß nüüdėsh',
'nocreatetext'              => 'Sigge nöü aanläje eß nur möshlesh, wänn_de [[Special:Userlogin|enjelogk]] beß. Der oohne kannß_De ävver Sigge änndere, di ald_doo sinn.',

# Account creation failure
'cantcreateaccounttitle' => 'Kann keine Zojang enrichte',

# History pages
'viewpagelogs'        => 'De LogBöösher fö hee di Sigk',
'nohistory'           => 'Et jitt këijn Väsjohne fun dä Sigk.',
'revnotfound'         => 'Di Väsjohn ham_mer nit jefonge.',
'revnotfoundtext'     => '<b>Däh.</b> Di ählere Väsjohn fun dä Sigk, wo De noh froochß, eß nit do. Schadt. Luer_enß op di URL, di Dėsh hääjebraadt hät, di weed fokiehrt sinn, oder se iß fellëijsj_övverholldt, wëijl Ëijne di Sigk fottjeschmeßße hätt?',
'loadhist'            => 'Donn de Lėßß met ahle Väsohne laade',
'currentrev'          => 'Neuste Version',
'revisionasof'        => 'Väsjohn fum $1',
'previousrevision'    => '← De Revisjohn dö_für zëije',
'nextrevision'        => 'De Väsjohn do_noh zëije →',
'currentrevisionlink' => 'De neuste Version',
'cur'                 => 'neu',
'next'                => 'Wiggo',
'last'                => 'läz',
'orig'                => 'Orrjinahl',
'histlegend'          => 'Hee kanns_De Väsjohne för_et Forjlishe ußsööke: Donn met dä Knöpp di zwëij makkeere, zwesche dänne De de Ungescheed jezëijsh krijje wellß, dann dröck „<b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:Compareselectedversions</b>“ bëij Dinge Taßte, oddo klick op ëijn fun dä Knöpp övver odder unger de Lėßß.

Äklierong: (÷:ksh:MediaWiki:Cur) = Fojlishe met de nöüßte Väsjohn, (÷:ksh:MediaWiki:Last) = Fojlishe met de Väsjohn ëijn_do_füer, <b>÷:ksh:MediaWiki:Minoreditletter</b> = en klëijne <b>M</b>ini_Ännderongk.',
'deletedrev'          => '[fott]',
'histfirst'           => 'Ählßte',
'histlast'            => 'Nöüßte',

# Revision feed
'history-feed-title'          => 'Väsjohne',
'history-feed-description'    => 'Äählere Väsjohne fun dä Sigk en de {{SITENAME}}',
'history-feed-item-nocomment' => '$1 öm $2', # user at time
'history-feed-empty'          => 'Di aanjefroocht Sigk jidd_et nit. Künnt sinn, dat se enzwesche fott_jeschmeßße oddo ömm_jenanndt voode eß. Kannß jo enß [[Special:Search|em Wikki sööke lohße]], öm paßßend nöüje Sigge ze finge.',

# Revision deletion
'rev-deleted-comment'         => '(„÷:ksh:MediaWiki:Summary“ ußßjeblenndt)',
'rev-deleted-user'            => '(÷:ksh:User_Name ußßjeblenndt)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">Di Väsjohn eß fottjeschmeßße woode. Jäz kam_mer se nit mieh beluere. Enne Wikki_Köbeß künnt se ävver zerrög_holle. Mieh drövver, vat met däm Fottschmiiße fun dä Sigk jewääse eß, künnd_Er em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logbooch] nohlässe.</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Di Väsjohn eß fottjeschmeßße woode. Jäz kam_mer se nit mieh beluere. Alls_enne [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]] krėßß_De_se ävver doch ze_sinn, un küünz_e och zerrög_holle. Mieh drövver, vat met däm Fottschmiiße fun dä Sigk jewääse eß, künnd_Er em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logbooch] nohlässe.</div>',
'rev-delundel'                => 'zëije/ußblännde',
'revisiondelete'              => 'Väsjohne fottschmiiße un widdo zerrögk_holle',
'revdelete-nooldid-title'     => 'Kein Version aanjejovve',
'revdelete-nooldid-text'      => 'Do häs kein Version aanjejovve.',
'revdelete-selected'          => 'Ußßjewählte Värsjohn fun [[:$1]]:',
'revdelete-text'              => 'Dä fottjeschmeßßene Sigge ier Ennhaldt kannß_De nit mieh aanluere. Se bliive ävver en de Lėßß met_e Väsjohne dren.

Enne ÷:ksh:MediaWiki:Admin kann de fottjeschmeßßene Krohm emmo noch aanluere un kann_en och widdo_hää_holle, ußßer wänn bëij dem Wikki singe Inshtallzjohn dat angersch faßßjelaat woode eß.',
'revdelete-legend'            => 'Dä öffentlijje Zojang ennschrängke, fö_di Väsjohn:',
'revdelete-hide-text'         => 'Dä Täx fun dä Väsjohn ußblännde',
'revdelete-hide-comment'      => 'Dä Ennhaldt fun „÷:ksh:MediaWiki:Summary“ ußblännde',
'revdelete-hide-user'         => 'Däm Beärrbëijder sing IP_Addräßß oddo ÷:ksh:User_Naame ußblännde',
'revdelete-hide-restricted'   => 'Donn dat och för de ÷:ksh:MediaWiki:group-sysop esu maache wi_fö_jeede Anndere',
'revdelete-log'               => 'Bemärrkung fö_t LogBooch:',
'revdelete-submit'            => 'Op di aanjekrüzde Väsjohn aanvënnde',
'revdelete-logentry'          => 'Zojang zo de Väsjohn foänndot för [[$1]]',

# Diffs
'difference'              => '(Ungerscheed zwesche de Versione)',
'lineno'                  => 'Rëij $1:',
'compareselectedversions' => 'Dun de markeete Version verjliche',

# Search results
'searchresults'         => 'Wat bëijm Sööke errußkohm',
'searchresulttext'      => 'Luur op de Sigg üvver et [[{{MediaWiki:Helppage}}|{{int:help}}]] noh, wann de mieh drüvver wesse wells, wie mer en de {{SITENAME}} jet fingk.',
'searchsubtitle'        => 'För Ding Frooch noh „[[:$1]]“.',
'searchsubtitleinvalid' => 'För Ding Frooch noh „$1“.',
'noexactmatch'          => 'Mer han këijn Sigk met jenou däm Name „<strong>$1</strong>“ jefonge. Do kannß_ße [[:$1|aanlääje]], wänn_De wellß.',
'titlematches'          => 'Paßßende Övverschreffte',
'notitlematches'        => 'Këij_paßßende Övverschreffte',
'textmatches'           => 'Sigge met_däm Täx',
'notextmatches'         => 'Këij Sigk met_däm Täx',
'prevn'                 => 'de $1 do_für zëije',
'nextn'                 => 'de nääkßte $1 zëije',
'viewprevnext'          => 'Bläddere: ($1) ($2) ($3).',
'showingresults'        => 'Unge weede beß <strong>$1</strong> fun de jefungene Enndrääsh jezëijsch,
fun de Nommer <strong>$2</strong> aff.',
'showingresultsnum'     => 'Unge sinn <strong>$1</strong> fun de jefungene Enndrääsh opjelėßß,
fun de Nommer <strong>$2</strong> aff.',
'nonefound'             => '<strong>Opjepaßß:</strong> Wänn bëijm Söhke nix eruß kütt, do kann dat draan lijje, dat mer esu jannz jewöönlijje Wööt, wi „hätt“, „allso“, „wääde“, un „senn“, uew. jaa__nid_esu en_de Daate_Bank dren_hann, dat_se jefonge wääde künnte.',
'powersearch'           => 'Söhke',
'powersearchtext'       => 'Söök in de ÷:ksh:Namespaces:<br />$1<br />$2 Zëijsh Ömëijdunge<br />Söhk noh $3 $9',
'searchdisabled'        => 'Dat Sööke he op de {{SITENAME}} eß em Mommänndt affjeschalldt.
Dat weed fun de ßööver ald_enß jemaat, domet_te Laßß op inne nit_ze jrůůß_weedt,
un winnishßtenß de Nommaalle Sigge_Oproofe flöck_jenooch jonn.

Ühr künnd_esu lang övver en Söök_Maschiin fun ußßerhallf ėmmer noch
Sigge op de {{SITENAME}} finge.
Ed_eß nit_jesaat,
dat denne ier Daate top_aktowäll sinn,
ävve_ed_eß_bäßßo wi jaa_nix.',

# Preferences page
'preferences'              => 'ming Ëijnshtellunge',
'prefsnologin'             => '÷:ksh:MediaWiki:notloggedin',
'prefsnologintext'         => 'Do mööds_alld [[Special:Userlogin|ennjelogg]] sinn, öm Ding Ėnnshtellunge ze ännderre.',
'prefsreset'               => 'Dė Ëijnshtellunge woodte jäz op Shtanndadt zerrögk_jesaz.',
'qbsettings'               => '„Flöcke Lėngkß“',
'qbsettings-none'          => 'Fottlooße, dat well ich nit sinn',
'qbsettings-fixedleft'     => 'Am linke Rand fass aanjepapp',
'qbsettings-fixedright'    => 'Am rächte Rand fass aanjepapp',
'qbsettings-floatingleft'  => 'Am linke Rand am Schwevve',
'qbsettings-floatingright' => 'Am rächte Rand am Schwevve',
'changepassword'           => 'Passwood ändere',
'skin'                     => 'Et Uß_Sinn',
'math'                     => 'Mattematisch Forrmelle',
'dateformat'               => 'Em Datum sing Fomat',
'datedefault'              => 'Ejaal - kein Vörliebe',
'datetime'                 => 'Datum un Uhrzigge',
'math_failure'             => 'Fääler fum Paaser',
'math_unknown_error'       => 'Fääler, dä_mmer nit känne',
'math_unknown_function'    => 'en Funkzjohn, di_mmer nit känne',
'math_lexing_error'        => 'Fääler bëijm Lëxing',
'math_syntax_error'        => 'Fääler en de Sünntax',
'math_image_error'         => 'De Ömwandlung noh PNG eß donëvve jejange. Donn enß noh de reshtijje Ėnnshtallazjoohn luere bëij <i>latex</i>, <i>dvips</i>, <i>gs</i>, un <i>convert</i>. Odder saar_et ennem ßööver_Admin, odder_ennem [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]].',
'math_bad_tmpdir'          => 'Dat Zwesche_Fozëijshniß fö de mattematėshe Forrmelle lööt sėsh nit aanlääje oddo nix erinn_schriive, Dat eß Dißß. Saar_et ennem [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]] odder ennem ßööver_Minsch.',
'math_bad_output'          => 'Dat Fozëijshniß fö de mattematėshe Forrmelle lööt sėsh nit aanlääje oddo nix erinn_schriive, Dat eß Dißß. Saar_et ennem [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]] odder ennem ßööver_Minsch.',
'math_notexvc'             => 'Dat Projamm <code>texvc</code> ham_mer nit jefonge. Saar_et ennem [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop-member]], ennem ßööver_Minsch, odder luer_enß en de <code>math/README</code>.',
'prefs-personal'           => 'De Ëijnshtellonge',
'prefs-rc'                 => 'Nöüßte Ännderunge',
'prefs-watchlist'          => 'De Oppaßß_Lėßß',
'prefs-watchlist-days'     => 'Aanzal Dare fö_en minger Oppaßß_Lėßß aan_ze_zëije:',
'prefs-watchlist-edits'    => 'Aanzal Änderonge fö_en minger forjrüüßorte Oppaßß_Lėßß aan_ze_zëije:',
'prefs-misc'               => 'Sönß',
'saveprefs'                => 'Faßßhallde',
'resetprefs'               => 'Zerrögk_Säzze',
'oldpassword'              => 'Et aahle Paßßwordt:',
'newpassword'              => 'Nöü Paßßwoodt:',
'retypenew'                => 'Norr_enß dat neue Paßßwoodt:',
'textboxsize'              => 'Bëijm Beärrbëijde',
'rows'                     => 'Rëije:',
'columns'                  => 'Spalte:',
'searchresultshead'        => 'Bëijm Sööke',
'resultsperpage'           => 'Zëijsh Träfo pro Sigk:',
'contextlines'             => 'Reihe för jede Treffer:',
'contextchars'             => 'Zeiche us de Ömjevvung, pro Reih:',
'recentchangescount'       => 'Enndrääsh en de Lėßß_met_de „÷:ksh:MediaWiki:Recentchanges“:',
'savedprefs'               => 'Ding Ėnnshtellunge sinn jäz jeseshot.',
'timezonelegend'           => 'Zigk_Zoone Ungerscheed',
'timezonetext'             => '<!-- ¹ -->Dat sin_de Shtunnde un Menutte zwesche de Zigk op de Uure bëij Dir am Oot un däm ßööver, dä med <a href="http://ksh.wikipedia.org/wiki/UTC">UTC</a> leuf.',
'localtime'                => 'De Zigg_op Dingem Kompjutor:',
'timezoneoffset'           => 'Dä Ungerscheed ¹ eß:',
'servertime'               => 'De Ur_Zigg_öm ßööver eß jäz:',
'guesstimezone'            => 'Fing et erus üvver dä Brauser',
'allowemail'               => 'E-Mail vun andere Metmaacher zolooße',
'defaultns'                => 'Dun standaadmäßich en hee dä Appachtemengs söke:',
'default'                  => 'Standaad',
'files'                    => 'Dateie',

# User rights
'userrights-lookup-user'     => '÷:ksh:User Jroppe fowallde',
'userrights-user-editname'   => '÷:ksh:User_Name: <!-- -->',
'editusergroup'              => 'Däm Metmaacher sing Jruppe Räächde bearbeide',
'userrights-editusergroup'   => '÷:ksh:User_Jroppe aanpaßße',
'saveusergroups'             => '÷:ksh:User_Jroppe affshpëijshere',
'userrights-groupsmember'    => 'Eß en_de ÷:ksh:User_Jroppe:<br />',
'userrights-groupsavailable' => 'Eß nit en de ÷:ksh:User_Jroppe:<br />',
'userrights-groupshelp'      => "Söök de Jroppe uß, wo dä ÷:ksh:User bëij kumme sull oddo druss_eruß sull. Jroppe, di De hee nid_ußsöökß, bliive, wi_se_sėnn. Dat Ußsööke kannß_De bëij de miihßte [http://ksh.wikipedia.org/wikki/Brauser Brausere] met '''Ctrl + Lenkß_Klikke''' / '''Strg + Lenkß_Klikke''' maache.",

# Groups
'group'            => 'Jrupp:',
'group-sysop'      => 'Wiki Köbese',
'group-bureaucrat' => 'Bürrokrade',

'group-sysop-member'      => 'Wiki Köbes',
'group-bureaucrat-member' => 'Bürrokrad',

'grouppage-sysop'      => '{{ns:project}}:Wiki Köbes',
'grouppage-bureaucrat' => '{{ns:project}}:Bürrokrad',

# User rights log
'rightslog'      => 'Logbooch fö_Ännderonge aan ÷:ksh:User-Rääshde',
'rightslogtext'  => 'He sin de Änderonge an ÷:ksh:User ier Rääshde opjelėßß. Op de Sigge övver [[÷:MSG:MediaWiki:grouppage-user|÷:ksh:Users]], [[÷:MSG:MediaWiki:grouppage-sysop|÷:ksh:MediaWiki:group-sysop]], [[÷:MSG:MediaWiki:grouppage-bureaucrat|÷:ksh:MediaWiki:group-bureaucrat]], [[÷:MSG:MediaWiki:grouppage-steward|÷:ksh:MediaWiki:group-steward]], … kannß_De noh_lässe, wat domet eß.',
'rightslogentry' => 'hät däm ÷:ksh:User „$1“ sing Rääshde fun „$2“ op „$3“ ömjestalldt',
'rightsnone'     => '(nix)',

# Recent changes
'recentchanges'                     => 'Nöüßte_Ännderonge',
'recentchangestext'                 => 'Op dä Sigk hee sinn de nöüßte Änderonge aam Wikki opjelėßß.',
'rcnote'                            => 'Hee sinn de läzde <strong>$1</strong> Änderonge uß de läzde <strong>$2</strong> Daare fum $3 aan.',
'rcnotefrom'                        => 'Hee sinn beß_op <strong>$1</strong> Änderonge zigk <strong>$2</strong> opjelėßß.',
'rclistfrom'                        => 'Zëijsh de nöüje Ännderonge fum $1 aff',
'rcshowhideminor'                   => '$1 klëijn minni_Ännderonge',
'rcshowhidebots'                    => '$1 de ÷:ksh:MediaWiki:group-bot ier Ännderonge',
'rcshowhideliu'                     => '$1 de aanjemälldte ÷:ksh:Users ier Ännderonge',
'rcshowhideanons'                   => '$1 de namenlose ÷:ksh:Users ier Ännderonge',
'rcshowhidepatr'                    => '$1 de aanjeluerte Ännderonge',
'rcshowhidemine'                    => '$1 ming ëijen Ännderonge',
'rclinks'                           => 'Zëijsh de läzde | $1 | Ännderonge uß de läzde | $2 | Daare, un donn | $3 |',
'diff'                              => 'Ungerscheed',
'hist'                              => 'Väsjohne',
'hide'                              => 'Ußblände:',
'show'                              => 'Zëije:',
'minoreditletter'                   => 'M',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 Oppaßßer]',
'rc_categories'                     => 'Nur di ÷:ksh:categories (med „|“ dozwesche):',
'rc_categories_any'                 => 'All, wat mer hann',

# Recent changes linked
'recentchangeslinked' => 'Folingg_Ännderonge',

# Upload
'upload'                      => 'Daate huh_laade',
'uploadbtn'                   => 'Huh_Laade!',
'reupload'                    => 'Norr_enß huh_laade',
'reuploaddesc'                => 'Zerrögk noh de Sigk zem Huh_Laade.',
'uploadnologin'               => '÷:ksh:MediaWiki:notloggedin',
'uploadnologintext'           => 'Do möötds_alld [[Special:Userlogin|ennjelogg]] sinn, öm Daate huh_ze_lade.',
'upload_directory_read_only'  => '<b>Doof:</b> En dat Fozëijshnißß <code>$1</code> fö_Dattëije drėn huh_ze_laade, do kann dat Web_ßööver_Projramm nix errinnschriive.',
'uploaderror'                 => 'Fääler bem Huh_Laade',
'uploadtext'                  => "<div dir=\"ltr\">Met dämm Formular unge kannß_de Bellder oddo annder Daate huh_laade. Do kannß dann Ding Werrək diräg enbinge, en dä Aate:<ul style=\"list-style:none outside none; list-style-position:outside; list-style-image:none; list-style-type:none\"><li style=\"list-style:none outside none; list-style-position:outside; list-style-image:none; list-style-type:none\"><code>'''[[{{NS:Image}}:'''''Belldshe'''''.jpg]]'''</code></li><li style=\"list-style:none outside none; list-style-position:outside; list-style-image:none; list-style-type:none\"><code>'''[[{{NS:Image}}:'''''Esu_süühd_dat_uß'''''.png | '''''enne Täx, dä di Brausere zëije, di këij Bellder künne''''']]'''</code></li><li style=\"list-style:none outside none; list-style-position:outside; list-style-image:none; list-style-type:none\"><code>'''[[{{NS:Media}}:'''''Su_hüert_sesh_dat_aan'''''.ogg]]'''</code></li></ul>
Ußßfüerlish met alle Möshlishkëijte finkß_de dat bëij de [http://ksh.wikipedia.org/wiki/Help:Daate_huhlaade Hülp].

Wänn De jäz entschloßße beß, dat De et hee huh_laade wellß:
* Aanluere, wat mer he en de {{SITENAME}} ald hann, kannß De en unß [[Special:Imagelist|Bellder_Leßß]].
* Wenn De jät söhke wellß, eetß enß noh_luere wellß, wat alld huhjelaade, oddo fellëijsh widdo fottjeschmeßße wood, dat shtëijd_em [[Special:Log/upload|Logbooch fum Huh_laade]].

Esu, un jäz loß jonn:</div>
== <span dir=\"ltr\">Date en de {{SITENAME}} lade</span> ==",
'uploadlog'                   => 'LogBooch fum Dattëije_Huh_Laade',
'uploadlogpage'               => 'Logbooch med_de huh_jelaadene Datëije',
'uploadlogpagetext'           => 'Hee sinn de nöüßte huh_jelaadenne Datëije opjelėßß un wä dat jedonn hät.',
'filename'                    => 'Name vun dä Datei',
'filedesc'                    => 'Beschrievungstex un Zosammefassung',
'fileuploadsummary'           => 'Beschrievungstex un Zosammefassung:',
'filestatus'                  => 'Urhevver Räächsstatus',
'filesource'                  => 'Quell',
'uploadedfiles'               => 'Huh_jeladenne Dattëije',
'ignorewarning'               => 'Warnung övverjonn, un Dattëij trozdämm affshpëijsherre.',
'ignorewarnings'              => 'Alle Warnunge övverjonn',
'illegalfilename'             => 'Schaadt:
<br />
En däm Name fun dä Dattëij sin Zëijshe enthallde,
di mer en Tittelle fun Sigge nit bruche kann.
<br />
Söök Der shtatt „$1“ jäd_andoß uß,
un dann mußß_de dat Dinge norr_enß huh_laade.',
'badfilename'                 => 'De Datei es en „$1“ ömjedäuf.',
'largefileserver'             => 'Di Datëij eß ze jruuß. Jrüüßo_wi_däm ßööver sing Ennshtëllung ällaup.',
'emptyfile'                   => 'Wat De hee jetz huhjelade häs, hät kein Daate dren jehatt. Künnt sin, dat De Dich 
verdon häs, un dä Name wo verkihrt jeschrevve. Luur ens ov De wirklich <strong>die</strong> Dateie hee 
huhlade wells.',
'fileexists'                  => 'Et jitt ald en Datei met däm Name. Wann De op „<span style="padding:2px; 
background-color:#ddd; color:black">Datei avspeichere</span>“ klicks, weed se ersetz. Bes esu jod  un luur Der $1 
aan, wann De nit 100% secher bes.',
'fileexists-forbidden'        => 'Et jitt ald en Datei met däm Name. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Et jitt ald en Datei met däm Name em jemeinsame Speicher. Jangk zeröck un lad se unger enem andere Name huh. [[{{ns:image}}:$1|thumb|center|$1]]',
'successfulupload'            => 'Et Huh_laade hät jeflupp',
'uploadwarning'               => 'Warrnung bëijm Huh_laade',
'savefile'                    => 'Dattëij affshpëijshere',
'uploadedimage'               => 'hät huhjelade: „[[$1]]“',
'uploaddisabled'              => 'Huh_Lade jeshpächt',
'uploaddisabledtext'          => 'Et Huh_Lade eß jeshpächt he en dämm Wikki.',
'uploadscripted'              => 'En dä Datëij eß [http://ksh.wikipedia.org/wiki/HTML HTML] dren oddo Kood fun_ennem [http://ksh.wikipedia.org/wiki/Skripp Skripp], dä künnt Dinge Brauser en do fallsche Hallß krijje un ußföere.',
'uploadcorrupt'               => 'Schaad.
<br />
Di Dattëij iß kapott, hädd_en fokiehjəte File_Name Ëxtensjen, odder ööhnds_enne anndere Drißß eß paßßėet.
<br />
<br />
Luer_enß noh_dä Dattëij, un dann moßß_de_t norr_enß fosöhke.',
'uploadvirus'                 => 'Esu enne Drißß:
<br />
En dä Dattëij shtish e Kompjuto_Viruß!
<br />
De Ëijnzelhäijte: $1',
'sourcefilename'              => 'Dattëij zem huh_laade',
'destfilename'                => 'Unger däm Dateiname avspeichere',
'filewasdeleted'              => 'Unger däm Name wood ald ens en Datei huhjelade. Die es enzwesche ävver widder fottjeschmesse woode. Luur leever eets ens en et $1 ih dat De se dann avspeichere deis.',

'upload-proto-error'     => 'Verkihrt Protokoll',
'upload-file-error-text' => 'Ene internal error es passeet beim Aanläje vun en Datei om Server.  Verzäll et enem system administrator.',
'upload-misc-error'      => 'Dat Huhlaade jing donevve',
'upload-misc-error-text' => 'Dat Huhlaade jing donevve. Mer wesse nit woröm.  Pröf de URL un versök et noch ens.  Wann et nit flupp, verzäll et enem system administrator.',

'license'            => 'Lizzänz',
'nolicense'          => 'Nix üßßjesöök',
'upload_source_url'  => ' (richtije öffentlije URL)',
'upload_source_file' => ' (en Datei op Dingem Kompjuter)',

# Image list
'imagelist'                 => 'Bellder, Tööhn, uew. (all)',
'imagelisttext'             => 'Hee küdd_en Lėßß fun <strong>$1</strong> Dattëij{{PLURAL:$1||e}}, zotteet $2.',
'getimagelist'              => 'ben de Liss met de Dateiname am lade',
'ilsubmit'                  => 'Söök',
'showlast'                  => 'Zëijsh de läzde | $1 | Dattëije, zotteed $2.',
'byname'                    => 'nohm Name',
'bydate'                    => 'nohm Datum',
'bysize'                    => 'noh de Dateijröße',
'imgdelete'                 => 'fott!',
'imgdesc'                   => 'täxx',
'imgfile'                   => 'Datei',
'imagelinks'                => 'Lėngkß',
'linkstoimage'              => 'He kumme de Sigge, di op di Dattëij lingke donn:',
'nolinkstoimage'            => 'Nix lėngk op hee_di Dattëij.',
'sharedupload'              => 'Di Dattëij eß esu parat lejaat, dat se en divärse, ungesheedlijje Projäkkte jebruch wääde kann.',
'shareduploadwiki'          => 'Mieh Ėnnfommazjohne fingkß_De hee: $1.',
'shareduploadwiki-linktext' => 'Hee eß en Dattëij beschrėvve',
'noimage'                   => 'Mer han këij_Dattëij med dämm Naame, kannz_E ävver $1.',
'noimage-linktext'          => 'Kannz_E huh_laade!',
'uploadnewversion-linktext' => 'Donn en nöüje Väsjohn fun dä Dattëij huh_laade',
'imagelist_date'            => 'Datum',
'imagelist_user'            => 'Metmaacher',
'imagelist_size'            => 'Byte',
'imagelist_description'     => 'Wat es op däm Beld drop?',
'imagelist_search_for'      => 'Sök noh däm Name vun däm Beld:',

# MIME search
'mimesearch' => 'Bellder, Tööhn, uew. övver ier MIME_Tüppe Sööhke',
'mimetype'   => 'MIME-Tüp:',
'download'   => 'Erungerlade',

# Unwatched pages
'unwatchedpages' => 'Sigge, wo Këijne dob_oppaßß',

# List redirects
'listredirects' => 'Ömlëijdunge',

# Unused templates
'unusedtemplates'     => 'Schabloone oddo Boushtëijn, di nit jebruch wääde',
'unusedtemplatestext' => 'Hee sinn all di Schabloone opjelëßß, di em ÷:ksh:Namespace „Schabbloon“ sinn, di nidd_en annder Sigge ennjeföösh wääde. Iih De jät dofun fottschmiiß, dängk draan, se künnte och obb_en annder Aat jebruch wääde, un luer Der di ÷:ksh:MediaWiki:Unusedtemplateswlh aan!',
'unusedtemplateswlh'  => 'annder Lėngkß',

# Random page
'randompage' => 'Zofällije Sigk',

# Random redirect
'randomredirect' => 'Zofällije Ömlëijdung',

# Statistics
'statistics'             => 'Shtatißtikke',
'sitestats'              => 'Shtatißtikke övver de {{SITENAME}}',
'userstats'              => 'Shtatißtikke övver de ÷:ksh:Users',
'sitestatstext'          => '* Et jidd_en_ättwa <strong>$2</strong> rėshtijje Atikkelle hee.

* En de Daatebangk sinn_er ävvo <strong>$1</strong> Sigge, aan dänne beß jäz_zosamme <strong>$4</strong> mool jät jeänndort woode eß.  Em_Shnett woote allso <strong>$5</strong> Ännderonge pro Sigk jemaat. <br /><small> (Do sinn ävvo de ÷:ksh:Talk_Sigge medjezalldt, de Sigge övver de {{SITENAME}}, un ußßodämm jeede klëijne Fuzz_un_Shtümpshenß_Sigk, Ömlëijdunge, Shabloone, ÷:ksh:Categories, un anndor Zeush, wat mer nit jood alls_enne Atikkel zälle kann)</small>

* <strong>$8</strong> Bellder, Töön, un_esu_n äänlijje Daate woodte ald huhjelade.

* Et {{PLURAL:$7|eß noch <strong>ëijn</strong> Oppjaf|sin_noch <strong>$7</strong> Oppjave|eß <strong>këijn</strong> Oppjaf mieh}} en_de_Lėßß.

* <strong>$3</strong> mool wood_en Sigk hee affjeroofe, dat sinn <strong>$6</strong> Affroofe pro Sigk.',
'userstatstext'          => '* <strong>$1</strong> ÷:ksh:Users han sėsh beß jëz aanjemelldt.
* <strong>$2</strong> do_fun sinn [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop]], dat sin_ner <strong>$4%</strong>.',
'statistics-mostpopular' => 'De miihz beluerte Sigge',

'disambiguations'     => '„(Wat es dat?)“-Sigge',
'disambiguationspage' => 'Template:Disambig',

'doubleredirects'     => 'Ömleitunge op Ömleitunge (Dubbel Ömleitunge sin verkihrt)',
'doubleredirectstext' => 'Dubbel Ömleitunge sin immer verkihrt, weil däm Wiki sing Soffwär de eetse Ömleitung 
folg, de zweite Ömleitung ävver dann aanzeije deit - un dat well mer jo normal nit han.
Hee fings De en jede Reih ene Link op de iertste un de zweite Ömleitung, donoh ene Link op de Sigg, wo de 
zweite Ömleitung hin jeiht. För jewöhnlich es dat dann och de richtije Sigg, wo de iertste Ömleitung ald hin 
jonn sollt.
Met däm „(Ändere)“-link kanns De de eetste Sigg tirek aanpacke. Tipp: Merk Der dat Lemma - de Üvverschreff - 
vun dä Sigg dovör.',

'brokenredirects'     => 'Ömleitunge, die en et Leere jonn (kapott oder op Vörrod aanjelaht)',
'brokenredirectstext' => "Die Ömleitunge hee jonn op Sigge, die mer
[[#ast|<small>noch'''<sup>*</sup>?'''</small>]]
jar nit han.
<small id=\"ast\">'''<sup>*</sup>?''' Die künnte op Vörrod aanjelaht sin.
Die alsu jod ussinn,
un wo die Sigge wo se drop zeije,
späder wall noch kumme wääde,
die sollt mer behalde.</small>",

# Miscellaneous special pages
'nbytes'                  => '$1 Byte',
'ncategories'             => '{{PLURAL:$1| eijn ÷:ksh:Category | $1 ÷:ksh:Categories }}',
'nlinks'                  => '{{PLURAL:$1|ëijne Lėngk|$1 Lėngkß}}',
'nmembers'                => 'met {{PLURAL:$1|ëijn Sigk|$1 Sigge}} dren',
'nrevisions'              => '{{PLURAL:$1|ëijn Ännderong|$1 Ännderonge}}',
'nviews'                  => '{{PLURAL:$1|1 Affroof|$1 Affroofe}}',
'lonelypages'             => 'Sigge wo nix drop lingk',
'lonelypagestext'         => 'The following pages are not linked from other pages in this wiki.',
'uncategorizedpages'      => 'Sigge di in këij ÷:ksh:Category senn',
'uncategorizedcategories' => '÷:ksh:Categories di sellvs_in këijn ÷:ksh:Categories senn',
'uncategorizedimages'     => 'Bellder, Tööhn, uew. di en këijn ÷:ksh:Categories dren sinn',
'unusedcategories'        => '÷:ksh:Categories med nix dren',
'unusedimages'            => 'Bellder, Tööhn, uew. di nit en Sigge dren_shtäshe',
'popularpages'            => 'Sigge, di öff affjeroofe wääde',
'wantedcategories'        => '÷:ksh:Categories di_mer non_nit hann, di noch_jebruch wääde',
'wantedpages'             => 'Sigge di_mer non_nit hann, di noch_jebruch wääde',
'mostlinked'              => 'Sigge med_e miehßte Lingkß drop',
'mostlinkedcategories'    => '÷:ksh:Categories med_e miehßte Lingkß drop',
'mostcategories'          => 'Atikkelle met_e miehßte ÷:ksh:Categories',
'mostimages'              => 'Bellder, Tööhn, uew. met_e miehßte Lingkß drop',
'mostrevisions'           => 'Atikkelle met_e miehßte Änderonge',
'allpages'                => 'All Sigge',
'prefixindex'             => 'All Sigge, di dänne ier Name medd_ennem beshtemmpte Woot oddo Täx aanfange dëijdt',
'shortpages'              => 'Sigge zoteet fun koot noh lang',
'longpages'               => 'Sigge zotėet fun Lang noh Koot',
'deadendpages'            => 'Sigge ohne Links dren',
'deadendpagestext'        => 'The following pages do not link to other pages in this wiki.',
'listusers'               => '÷:ksh:Users',
'specialpages'            => 'Söndersigge',
'spheading'               => 'Södersigge för all ÷:ksh:Users',
'restrictedpheading'      => 'Söndersigge med beshrängkte Zojangsrääshte',
'newpages'                => 'Nöü Sigge',
'newpages-username'       => '÷:ksh:User_Naam:',
'ancientpages'            => 'Sigge zoteet vun Ahl noh Neu',
'intl'                    => 'Ingerwikki _Lėngkß',
'move'                    => 'Ömnänne',
'movethispage'            => 'Di Sigk Ömnänne',
'unusedimagestext'        => '<p><strong>Opjepaßß:</strong> Annder Websigge künnte emmer noch di Dattëije hee tirrägk për <a class="plainlinks" href="http://ksh.wikipedia.org/wiki/URL">URL</a> aanshpräshe. Su künnd_et sinn, dadd_en Dattëij hee en de Lėßß shtëijdt, ävver doch jebruch weedt. Ußßerdämm, vinnishßtens bëij nöüe Dattëije, künnd sinn, dat_se non_nit enn_ennem Attikkel enjebout sinn, wëijl_noch Ëijne draan am brasselle eß.</p>',
'unusedcategoriestext'    => 'Di ÷:ksh:Categories hee senn ennjereshdt, ävver jäds_em Mommänndt, eß këijne Atikkel un këijnolëij ÷:ksh:Category dren ze fėnge.',
'notargettitle'           => 'Këijne Bezoch obb_en Ziiel',
'notargettext'            => 'Et fäält enne ÷:ksh:User odder en Sigk, wo mer jät zo erußfinge oddo oplißte sůlle.',

# Book sources
'booksources' => 'Böcher',

'categoriespagetext' => 'Dat sin de Saachjruppe vun däm Wiki hee.',
'data'               => 'Daate',
'userrights'         => '÷:ksh:User ier Rääshte fowallde',
'alphaindexline'     => '$1 â€¦ $2',
'version'            => 'Väsjohn fun de Wikki_ßoffwäer zëije',

# Special:Log
'specialloguserlabel'  => '÷:ksh:User:',
'speciallogtitlelabel' => '  Sigge_Naame:',
'log'                  => 'Logböösher ier Oppzëijshnonge (all)',
'alllogstext'          => "Dat hee es en jesamte Liss us all dä Logböcher för et [[Special:Log/block|Metmaacher 
oder IP Adress Sperre]], et [[Special:Log/protect|Sigge Sperre]], [[Special:Log/delete|et Sigge Fottschmieße]], et 
[[Special:Log/move|Sigge Ömnenne]], et [[Special:Log/renameuser|Metmaacher Ömnenne]], oder 
[[Special:Log/newusers|neue Metmaacher ehr Aanmeldunge]], et [[Special:Log/upload|Daate Huhlade]], 
[[Special:Log/rights|de Bürrokrade ehre Krom]], un de [[Special:Log/makebot|Bots ehr Status Änderunge]].
Dä Logböcher ehre Enhald ka'mer all noh de Aat, de Metmaacher, oder de Sigge ehr Name, un esu, einzel zoteet 
aanluure.",
'logempty'             => '<i>Mer han këijn paßßende Enndrääsh en däm Logbooch.</i>',

# Special:Allpages
'nextpage'          => 'De näkßte Sigk: „$1“',
'allpagesfrom'      => 'Sigge aanzeije av däm Name:',
'allarticles'       => 'All Atikkele',
'allinnamespace'    => 'All Sigge (Em Appachtemeng „$1“)',
'allnotinnamespace' => 'All Sigge (usser em Appachtemeng "$1")',
'allpagesprev'      => 'Zeröck',
'allpagesnext'      => 'Nächste',
'allpagessubmit'    => 'Loss Jonn!',
'allpagesprefix'    => 'Sigge zeije, wo dä Name aanfängk met:',
'allpagesbadtitle'  => 'Dä Siggename es nit ze jebruche. Dä hät e Köözel för en Sproch oder för ne 
Interwiki Link am Aanfang, oder et kütt e Zeiche dren för, wat en Siggename nit jeiht, villeich och mieh wie 
eins vun all däm op eimol.',

# Special:Listusers
'listusersfrom' => 'Zeich de Metmaacher vun:',

# E-mail user
'mailnologin'     => 'Do beß nit ennjelogk.',
'mailnologintext' => 'Do mööds_alld aanjemäldt un [[Special:Userlogin|ennjelogg]] sinn, un en joode e-mail Adräßß en Dinge [[Special:Preferences|÷:ksh:MediaWiki:preferences]] shtonn hann, öm_men e-mail aan anndere ÷:ksh:User ze schekke.',
'emailuser'       => 'E-mail aan dä Metmaacher',
'emailpage'       => 'E-mail aan ene Metmaacher',
'emailpagetext'   => 'Wann dä Metmaacher en E-mail Adress aanjejovve hätt en singe Enstellunge, un die 
deit et och, dann kanns De met däm Fomular hee unge, en einzelne E-Mail aan dä Metmaacher schecke. Ding E-mail 
Adress, die De en Ding eije Enstellunge aanjejovve häs, die weed als de Avsender Adress en die E-Mail 
enjedrage. Domet kann, wä die E-Mail kritt, drop antwoode, un die Antwood jeiht tirek aan Dich.
Alles klor?',
'usermailererror' => 'Dat e-mail-<a href="http://ksh.wikipedia.org/wiki/Obj%C3%A4k_%28OOP%29">Objägk</a> joov_ennen_Fääler uß:',
'defemailsubject' => 'E-Mail üvver de {{SITENAME}}.',
'noemailtitle'    => 'Këijn e-mail Addräßß',
'noemailtext'     => 'Dä ÷:ksh:User hät këijn e-mail Addräßß ėnnjedraare, oddo hä well këijn e-mail krijje.',
'emailfrom'       => 'Vun',
'emailto'         => 'Aan',
'emailsubject'    => 'Üvver',
'emailmessage'    => 'Dä Tex',
'emailsend'       => 'Avschecke',
'emailccme'       => 'Scheck mer en Kopie vun dä E-Mail.',
'emailccsubject'  => 'En Kopie vun Dinger E-Mail aan $1: $2',
'emailsent'       => 'E-Mail es ungerwähs',
'emailsenttext'   => 'Ding E-Mail es jetz lossjescheck woode.',

# Watchlist
'watchlist'            => 'ming Op_paßß_Lėßß',
'mywatchlist'          => 'ming Oppassliss',
'watchlistfor'         => '(för <strong>$1</strong>)',
'nowatchlist'          => 'En Dinger Oppaßß_Lėßß eß nix dren.',
'watchlistanontext'    => 'Do moß $1, domet de en Ding Oppaßß_Lėßß errinnluere kannß, odder jät draan änndere.',
'watchnologin'         => '÷:ksh:MediaWiki:notloggedin',
'watchnologintext'     => 'Öm Ding Oppaßß_Lėßß ze änndere, möötß_de alld [[Special:Userlogin|ennjelogg]] sinn.',
'addedwatch'           => 'En de Oppassliss jedon',
'addedwatchtext'       => 'Die Sigg „[[$1]]“ es jetz en Ding [[Special:Watchlist|Oppassliss]]. Av jetz, wann die Sigg 
verändert weed, oder ehr Klaafsigg, dann weed dat en de Oppassliss jezeich. Dä Endrach för die Sigg kütt en 
Fettschreff en de „[[Special:Recentchanges|Neuste Änderunge]]“, domet De dä do och flöck fings.
Wann de dä widder loss wääde wells us Dinger Oppassliss, dann klick op „Nimieh drop oppasse“ wann De die Sigg om 
Schirm häs.',
'removedwatch'         => 'Uß de Oppaßß_Lėßß jenůmme',
'removedwatchtext'     => 'Di Sigk „[[$1]]“ eß jäz uß de Oppaßß_Lėßß errußß_jenůmme.',
'watch'                => 'Drob_Oppaßße',
'watchthispage'        => 'Op_di Sigg op_paßße',
'unwatch'              => 'Nim_mieh drobb_Oppaßße',
'unwatchthispage'      => 'Nim_mieh op di Sigk op_paßße',
'notanarticle'         => 'Këijne Atikkel',
'watchnochange'        => 'Këijne Atikkel en Dinge Oppaßß_Lėßß eß en dä aanjezëijshte Zick foänndot woode.',
'watchlist-details'    => '<strong>$1</strong> Sigge sin en dä Oppassliss, ohne de Klaafsigge.',
'wlheader-enotif'      => '* Et E-mail Schekke eß ennjeschalldt.',
'wlheader-showupdated' => '* Wënn_se Ëijne jeänndot hätt, zigk_dämm_De_se_t läzde moohl aanjeluert häß, sen di Sigge <strong>ëxtra makkeet</strong>.',
'watchmethod-recent'   => 'Ben de läzde Ännderonge jääje de Op_paßß_Lėßß am pröfe',
'watchmethod-list'     => 'Ben de Op_paßß_Lėßß am pröfe, noh de läzde Ännderong',
'watchlistcontains'    => 'En dä Oppaßß_Lėßß sinn_er <strong>$1</strong> Sigge.',
'iteminvalidname'      => 'Dä Ėnndrach „<nowiki>$1</nowiki>“ hädd_enne kapodde Naame.',
'wlnote'               => 'Hee sinn de läzde <strong>$1</strong> Ännderonge uß de läzde <strong>$2</strong> Shtund.',
'wlshowlast'           => 'Zëijsh de läzde | $1 | Shtunnde | $2 | Daare | $3 | aan, donn',

'enotif_mailer'      => 'Dä {{SITENAME}} Nachrichte Versand',
'enotif_reset'       => 'Setz all Änderunge op „Aanjeluurt“ un Erledich.',
'enotif_newpagetext' => 'Dat es en neu aanjelahte Sigg.',
'changed'            => 'jeändert',
'created'            => 'neu aanjelaht',
'enotif_subject'     => '{{SITENAME}}: Sigg "$PAGETITLE" vun "$PAGEEDITOR" $CHANGEDORCREATED.',
'enotif_lastvisited' => 'Luur unger „$1“ - do fings de all die Änderunge zick Dingem letzte Besoch hee.',
'enotif_body'        => 'Leeven $WATCHINGUSERNAME,
en de {{SITENAME}} wood die Sigg „$PAGETITLE“ am $PAGEEDITDATE vun „$PAGEEDITOR“ $CHANGEDORCREATED, unger 
$PAGETITLE_URL fings Do de Neuste Version.
$NEWPAGE
Koot Zosammejefass, Quell: „$PAGESUMMARY“ $PAGEMINOREDIT
Do kanns dä Metmaacher „$PAGEEDITOR“ aanspreche:
* E-Mail: $PAGEEDITOR_EMAIL
* wiki: $PAGEEDITOR_WIKI
Do kriss vun jetz aan kein E-Mail mieh, bes dat Do Der die Sigg aanjeluurt häs. Do kanns ävver och all die E-Mail 

Merker för die Sigge en Dinger Oppassliss op eimol ändere.

Ene schöne Jroß vun de {{SITENAME}}.

--
Do kanns hee Ding Oppassliss ändere:
{{FULLURL:Special:Watchlist/edit}}

Do kanns hee noh Hölp luure:
{{FULLURL:int:MediaWiki:Helppage}}',

# Delete/protect/revert
'deletepage'                  => 'Schmieß die Sigg jetz fott',
'confirm'                     => 'Dä Schotz för die Sigg ändere',
'excontent'                   => 'drop stundt: „$1“',
'excontentauthor'             => 'drop stundt: „$1“ un dä einzije Schriever woh: „$2“',
'exbeforeblank'               => 'drop stundt vörher: „$1“',
'exblank'                     => 'drop stundt nix',
'confirmdelete'               => 'Dat Fottschmieße muss bestätich wääde:',
'deletesub'                   => '(De Sigg „$1“ soll fottjeschmesse wääde)',
'historywarning'              => '<strong>Opjepaßß:</strong> Di Sigk hätt (mieh wi ëijn) für_her_jejangene',
'confirmdeletetext'           => 'Do bes koot dovör, en Sigg för iwich fottzeschmieße. Dobei verschwind och de janze Verjangenheit vun dä Sigg us de Daatebank, met all ehr Änderunge un Metmaacher Name, un all dä Opwand, dä do dren stich. Do muss hee jetz bestätije, dat de versteihs, wat dat bedügg, un dat De weiß, wat Do do mähs.
<strong>Dun et nor, wann De met de [[{{MediaWiki:Policy-url}}]] wirklich zosamme jeihs!</strong>',
'actioncomplete'              => 'Erledich',
'deletedtext'                 => 'De Sigg „$1“ es jetz fottjeschmesse woode. Luur Der „$2“ aan, do häs De en Liss met de Neuste fottjeschmesse Sigge.',
'deletedarticle'              => 'hät fottjeschmesse: „[[$1]]“',
'dellogpage'                  => 'Logboch met de fottjeschmesse Sigge',
'dellogpagetext'              => 'Hee sin de Sigge oppjeliss, die et neus fottjeschmesse woodte.',
'deletionlog'                 => 'Dat Logboch met de fottjeschmesse Sigge dren',
'reverted'                    => 'Han de äählere Väsjohn fun dä Sigk zoröck_jeholldt.',
'deletecomment'               => 'Aanlass för et Fottschmieße',
'rollback'                    => 'Ännderonge Zerög_Nämme',
'rollback_short'              => 'Zerög_Nämme',
'rollbacklink'                => 'Zerröck_Nämme',
'rollbackfailed'              => 'Dat Zerög_Nämme jingk sheef',
'cantrollback'                => 'De letzte Änderung zeröckzenemme es nit möchlich. Dä letzte Schriever es dä einzije, dä aan dä Sigg hee jet jedon hät!',
'alreadyrolled'               => '<strong>Dat wor nix!</strong>
Mer künne de letzte Änderunge vun dä Sigg „[[$1]]“ vum Metmaacher „[[User:$2|$2]]“ (?[[User talk:$2|däm sing Klaafs]]) nimieh zeröcknemme, dat hät ene Andere enzwesche ald jedon.
De Neuste letzte Änderung es jetz vun däm Metmaacher „[[User:$3|$3]]“ (?[[User talk:$3|däm sing Klaafs]]).',
'editcomment'                 => 'Bei dä Änderung stundt: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Ännderonge fun däm ÷:ksh:User „[[User:$2|$2]]“ (→[[User talk:$2|däm_singe ÷:ksh:Talks]]) fottjeschmeßße, unn_do_föe de läzde Väsjohn fum „[[User:$1|$1]]“ widdo zerrökjeholldt',
'sessionfailure'              => 'Ed_joov_wall_e täshnesh Problehm med_Dingem Login. Dröm ham_mer dad_uß Füürsesh jäz nit jemaat, domet me_nid_fellëijsh Ding Ännderong dem fokierte ÷:ksh:User ungerjuubelle. Jangk zerrögg_un fosöög_ed_norr_enß.',
'protectlogpage'              => 'Logbooch fum Sigge_Schöze',
'protectlogtext'              => 'He eß de Lėß fun Sigge, di jeschöz odder frëij jejovve woode sinn.',
'protectedarticle'            => 'hätt jeschöz: „[[$1]]“',
'unprotectedarticle'          => 'Schoz fö „[[$1]]“ opjehovve',
'protectsub'                  => '(Sigge_Schoz för „$1“ änndere)',
'confirmprotect'              => 'Sigg schötze',
'protectcomment'              => 'Dä Jronnd oddo Aanlaß fö_t Schözze',
'unprotectsub'                => '(Schoz fö „$1“ ophävve)',
'protect-unchain'             => 'Et Schözze jäje Ömnänne ëxtra ëijnshtëlle loohße',
'protect-text'                => 'Hee kannß_De dä Schoz jäje Veränderonge fö_de Sigk „$1“ aanlooere un änndere. Em <span class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logbooch]</span> fingkß De ählere Ännderonge fun däm Schoz, wännt_se jitt. Bess_esu jood un halldt Desh aan de Räjelle för_esu Fäll!',
'protect-default'             => '—(Shtanndadt)—',
'protect-level-autoconfirmed' => 'nur ÷:ksh:User raanloohße, di sesh aanjemälldt hann',
'protect-level-sysop'         => 'Nuur de ÷:ksh:MediaWiki:group-sysop raanloohße',

# Restrictions (nouns)
'restriction-edit' => 'An et Änndere …',
'restriction-move' => 'An et Ömnänne …',

# Undelete
'undelete'                 => 'Fottjeschmeßßene Krohm aanluere/zerrökholle',
'undeletepage'             => 'Fottjeschmeßßen Sigge aanluere un widdo zerögk_holle',
'viewdeletedpage'          => 'Fottjeschmessen Sigge aanzëije',
'undeletepagetext'         => 'De Sigge hee_noh si fottjeschmeßße, mer künne se ävver ėmmer noch uss_em Möll_Ëmmer erruß_kroose.',
'undeleteextrahelp'        => 'Öm die jannze Sigk met alle iere Väsjoohne widder ze holle, loohß all de Väsjoohne oohne Höökshe, un kligg_op „<b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:undeletebtn</b>“.

Öm blooß ëijnzel Väsjoohne zerögk_ze_holle, maach Höökshe aan di_Väsjoohne, di_De widder hann wellß, un dann donn „<b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:undeletebtn</b>“ klikke.

Op „<b style="padding:2px; background-color:#ddd; color:black">÷:ksh:MediaWiki:Undeletereset</b>“
klikk, wänn_De all Ding Höökshe un Ding „÷:ksh:MediaWiki:Undeletecomment“ widder fott hann wellß.',
'undeleterevisions'        => '<strong>$1</strong> Väsjohne en_t Aschihf jedonn',
'undeletehistory'          => 'Wänn_De di Sigk widdo zerrögk_hollß,
dann kriß_De alle fottjeshmeßßene Väsjohne widder.
Wänn_enzwesche en nöüe Sigk unger dämm aahle Name ennjereshtdt woode eß,
dann wääde de zerögkjeholldte Väsjohne ëijnfach alß zosätzlijje älldere Väsjohne fö_di nöüje Sigk ennjerëijdt wääde.
Di nöüje Sigk weed nidd_äsäzz.',
'undeletehistorynoadmin'   => 'Di Sigk es fottjeschmeßße woode. Dä Jrunnd_dö_füüer iß en de Leßß unge ze finge, jenau_esu wi de ÷:ksh:User, wo de Sigk fo_änndot hann, iih dat se fotjeschmeßße wood. Wat op dä Sigk iere fotjeschmeßßene aahle Väsjohne shtundt, dat künne nuuer de [[÷:MSG:MediaWiki:administrators|÷:ksh:MediaWiki:group-sysop]] noch aansinn (un och widder zerögk holle)',
'undeleterevision-missing' => 'De Version stemmp nit. Dat wor ene verkihrte Link, oder de Version wood usem Archiv zeröck jehollt, oder fottjeschmesse.',
'undeletebtn'              => 'Zerröck_Holle!',
'undeletereset'            => 'De Fällder ußliihre',
'undeletecomment'          => 'Äklierong (fö_enn_et LogBooch):',
'undeletedarticle'         => '„$1“ zerrögk_jeholldt',
'undeletedrevisions'       => '{{PLURAL:$1|ëijne Väsjohn|$1 Väsjohne}} zerrögk_jeholldt',
'undeletedrevisions-files' => 'Zesamme_jenůmme <strong>$1</strong> Väsjohne fun <strong>$2</strong> Dattëije zerrögk_jeholldt',
'undeletedfiles'           => '<strong>$1</strong> Dattëije zerrögk_jeholldt',
'cannotundelete'           => '<strong>Dä.</strong> Dat Zeröckholle jing donevve. Möchlich, dat ene andere Metmaacher flöcker wor, un et ald et eets jedon hät, un jetz es die Sigg ald widder do jewäse.',
'undeletedpage'            => '<big><strong>Di Sigk „$1“ eß jäz widdo_doo</strong></big>

Luer Der_et [[Special:Log/delete|÷:ksh:MediaWiki:Dellogpage]] aan, do häßß De de nöüßte fottjeschmeßßene un widdo herjeholldte Sigge.',

# Namespace form on various pages
'namespace'      => '÷:ksh:Namespace:',
'invert'         => 'donn di Ußßwaal ömmdriije',
'blanknamespace' => '(Atikkele)',

# Contributions
'contributions' => 'Däm Metmaacher sing Beidräch',
'mycontris'     => 'ming Bëijdräsh',
'contribsub2'   => 'För dä Metmaacher: $1 ($2)',
'nocontribs'    => 'Mer han këijn Ännderonge jefonge, enn_de_Log_Böösher, di_do paßße dääte.',
'ucnote'        => 'Hee sinn däm ÷:ksh:User sing läzde <strong>$1</strong> Änderonge fun de läzde <strong>$2</strong> Daare.',
'uclinks'       => 'Zëijsh de läzde <strong>$1</strong> Bëijdräsh, zëijsh de läzde <strong>$2</strong> Dare.',
'uctop'         => ' (Nöüßte)',

'sp-contributions-newbies-sub' => 'Fö_Nöüje ÷:ksh:User',

'sp-newimages-showfrom' => 'Zëijsh de nöüje Bellder aff däm $1',

# What links here
'whatlinkshere' => 'Wat noh hee lingk',
'linklistsub'   => '(Lėßß met de Lėngkß)',
'linkshere'     => 'Dat sinn di Sigge, di hee drop lingke důnn:',
'nolinkshere'   => 'Këijn_Sigk lėngk noh_heh.',
'isredirect'    => 'Ömlëijdungß_Sigk',
'istemplate'    => 'weed ėnnjeföösh',

# Block/unblock
'blockip'                     => 'Metmaacher sperre',
'blockiptext'                 => 'Hee kanns De bestemmte Metmaacher oder 
IP-Adresse sperre, su dat se hee em Wiki nit mieh 
schrieve und Sigge ändere künne. Dat sollt nor jedon wääde om sujenannte 
Vandaale ze bremse. Un mer müsse uns dobei natörlich aan uns 
[[{{MediaWiki:Policy-url}}|Rejele]] för sun Fäll halde.
Drag bei „Aanlass“ ene möchlichs jenaue Jrund en, wöröm dat Sperre passeet. Nenn un Link op de Sigge wo Einer kapott jemaat hät, zem Beispill.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, un och wann De se ändere wells.',
'ipaddress'                   => 'IP-Addräßß',
'ipadressorusername'          => 'IP Addräßß oddo ÷:ksh:User_Name',
'ipbexpiry'                   => 'Dauer fö_wi lang',
'ipbreason'                   => 'Aanlaßß',
'ipbanononly'                 => 'Nur de namelose ÷:ksh:Users shpärre',
'ipbcreateaccount'            => 'Nöüj_aanmällde fobeede',
'ipbenableautoblock'          => 'Dun automatisch de letzte IP-Adress sperre, die dä Metmaacher jehatt hät, un och all die IP-Adresse, vun wo dä versök, jet ze ändere.',
'ipbsubmit'                   => 'Důnn dä ÷:ksh:User shpärre',
'ipbother'                    => 'En annder Zigk',
'ipboptions'                  => '1 Shrundt:1 hour,2 Shrundt:2 hours,3 Shrundt:3 hours,6 Shtund:6 hours,12 Shtund:12 hours,1 Daach:1 day,3 Daare:3 days,1 Woch:1 week,2 Woche:2 weeks,3 Woche:3 weeks,1 Moohnd:1 month,3 Moohnde:3 months,6 Moohnde:6 months,9 Moohnde:9 months,1 Joohr:1 year,2 Joohre:2 years,3 Joohre:3 years,Onbejrännz:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'Sönß_wi lang',
'badipaddress'                => 'Wat De do jeschrevve häs, dat es kein öntlije 
IP-Adress.',
'blockipsuccesssub'           => 'De IP-Adress es jesperrt',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] es jetz jesperrt.
Luur op [[Special:Ipblocklist|de Liss met jesperrte IP_Adresse]] wann de ne Üvverbleck üvver de Sperrunge han wells, 
un och wann De se ändere wells.',
'unblockip'                   => 'Dä Medmacher widdor maache loohße',
'unblockiptext'               => 'Hee kannz De für_her jeshpächte IP_Addräßße oddo ÷:ksh:User widdo frëijäavve, un dänne_esu dat Rääsh fö_ze_Schriive he em Wikki widdo_jävve.

Luůr op [[Special:Ipblocklist|de Lėßß met jeshpächte IP_Aräßße]] wänn de ne Övverblegg_över de Shpärrunge hann wellß, un och wänn_De_se änndere wellß.',
'ipusubmit'                   => 'Důnn de Shpärr_fö_di Adräßß widdo ophävve',
'unblocked'                   => '[[User:$1|$1]] wood widdo zohjelohße',
'ipblocklist'                 => 'Lėßß med jeshpächte IP-Adräßße un ÷:ksh:User_Naame',
'blocklistline'               => '$1, $2 hät „$3“ jesperrt ($4)',
'infiniteblock'               => 'fö_iiwish',
'expiringblock'               => 'endt am $1',
'anononlyblock'               => 'nor anonyme',
'noautoblockblock'            => 'automatisch Sperre avjeschalt',
'createaccountblock'          => 'Aanmelde nit möchlich',
'blocklink'                   => 'Sperre',
'unblocklink'                 => 'widde_frëijjävve',
'contribslink'                => 'Beidräch',
'autoblocker'                 => 'Automatich jesperrt. Ding IP_Adress wood vör kootem vun däm Metmaacher „[[User:$1|$1]]“ jebruch. Dä es jesperrt woode wäje: „<i>$2</i>“',
'blocklogpage'                => 'Logboch met Metmaacher-Sperre',
'blocklogentry'               => '„[[$1]]“ jesperrt, för $2',
'blocklogtext'                => 'Hee es dat Logboch för et Metmaacher Sperre un Freijevve. Automatich jesperrte 
IP-Adresse sin nit hee, ävver em 
[[Special:Ipblocklist|Logboch met jesperrte IP-Adresse]] ze finge.',
'unblocklogentry'             => '÷:ksh:User „[[User:$1|$1]]“ frëijejovve',
'range_block_disabled'        => 'Adräßße_Jebeede ze shpärre, eß nit älaup.',
'ipb_expiry_invalid'          => 'De Dauer eß Drißß. Jävv_se rishtish aan.',
'ipb_already_blocked'         => '„$1“ eß ald jeshpächt',
'ipb_cant_unblock'            => 'Enne Fääler: De Shpärr Nommer $1 eß nit ze finge. Se künndt ald widdo frëij_jejovve woode sinn.',
'ip_range_invalid'            => 'Dä Berëijsh fun IP_Addräßße eß nidd_en Ochdnung.',
'proxyblocker'                => 'Proxy_Blokker',
'proxyblockreason'            => 'Unger Dinge [http://ksh.wikipedia.org/wiki/IP_Addr%C3%A4%C3%9F%C3%9F IP_Addräßß] leuv_enne offene [http://ksh.wikipedia.org/wiki/Proxy Proxy]. Dröm kannß_De hee em Wikki nix maache. Schwadt med Dimgem Süßteem_Minsch oddo Näzwärrək_Täshnikko [http://ksh.wikipedia.org/wiki/ISP Internet Service Provider] un fozäll dänne fun däm Rissikko för Ühr Sesherhëijdt!',
'proxyblocksuccess'           => 'Fähdėsh',
'sorbsreason'                 => 'Ding [http://ksh.wikipedia.org/wiki/IP_Addr%C3%A4%C3%9F%C3%9F IP_Addräßß] weed en de [http://www.sorbs.net SORBS] [http://ksh.wikipedia.org/wiki/DNSbl DNSbl] als_enne offene [http://ksh.wikipedia.org/wiki/Proxy Proxy] jelėßß. Schwadt med Dimgem Süßteem_Minsch oddo Näzwärrək_Täshnikko [http://ksh.wikipedia.org/wiki/ISP Internet Service Provider] drövver, un fozäll dänne fun däm Rissikko för Ühr Sesherhëijdt!',
'sorbs_create_account_reason' => 'Ding IP_Addräßß weed en DNSbl als_enne offene Proxy jelėßß. Dröm kannß_De Desch hee em Wikki nit allse_enne nöüje User aanmällde.',

# Developer tools
'lockdb'              => 'Daate_Bangk Spärre',
'unlockdb'            => 'Daate_Bangk frëij_jäve',
'lockdbtext'          => 'Noh_m Shpärre kann Këijne mieh Ännderonge maache an singe Op_paßß_Lėßß, aan Ëijnshtellunge, Atikelle, uew. un nöüje ÷:ksh:Users jidd_et och nit. Beß sesher, dat_Te_dat wellß?',
'unlockdbtext'        => 'Noh_m Frëij_Jävve eß de Daate_Bangk nit mieh jeshpächt, un all_di nommaale Ännderonge weede widdo mööshlesh. Beß sesher, dat_Te_dat wellß?',
'lockconfirm'         => 'Jo, ėsh_well dė Daate_Bangk jeshpächt hann.',
'unlockconfirm'       => 'Jo, ėsh_well dė Daate_Bangk frëij jäve.',
'lockbtn'             => 'Daate_Bangk Spärre',
'unlockbtn'           => 'Daate_Bangk frëij jäve',
'locknoconfirm'       => 'Do häß këij Höhksche en_dämm Fëlldt zem Beshtätijje jemaat.',
'lockdbsuccesssub'    => 'De Daate_Bangk eß jäz jespächt',
'unlockdbsuccesssub'  => 'De Daate_Bangk eß jäz frëij_jejovve',
'lockdbsuccesstext'   => 'De Daate_Bank fun de {{SITENAME}} jäz jeshpächt.<br />
Důnn_se widdo frëij_jëvve, wann_Ding Waadung dorresch eß.',
'unlockdbsuccesstext' => 'De Daate_Bangk eß jäz frëij_jejovve.',
'lockfilenotwritable' => 'Dė Dattëij, wo dė Date_Bangk met jeshpächt weede wööd, künne_mer nit aanlääje, odder nit dren shriive. Esu enne Drißß! Dat mööt dä Web_ßööver ävver künne! Fozäll dadd_ennem Foanntwochtlijje fö de Inshtallazjohn fu däm ßööver, odder reparėer_et selləve, wänn_De kannß.',
'databasenotlocked'   => '<strong>Opjepass:</strong> De Daatebank es <strong>nit</strong> jesperrt.',

# Move page
'movepage'                => 'Sigk Ömnänne',
'movepagetext'            => 'Hee kannß De en Sigk en de {{SITENAME}} ömnänne. Domet kritt di Sigg_enne nöüje Name, un alle fürherijje Väsjohne fun dä Sigk och. Unger däm ahle Name weed_otomatijj_en [http://ksh.wikipedia.org/wiki/Help:Wat_dejd_en_Ömlejdung%3F Ömlëijdung] op dä nöüe Name enjedraare. Lėngkß op dä aahle Name blieve ävver wi se woohre. Dat hëijß, Do moßß sellver nohluere, ov do jäz [[Special:Doubleredireects|dubbelde]] oddo [[Special:Doubleredireects|kapotte]] Ömlëijdunge bëij eruß_kumme. Wenn_De_n Sigg_ömnänne dëijß, häß Do och dör ze sorrəje, dat_de betroffene Lingkß do hen jonn, wo se hen jonn sulle. Allso holl Der de Leßß „÷:ksh:MediaWiki:Whatlinkshere“ un jangk se dorrsh!

Di Sigk weed <strong>nit</strong> ömjenanndt, wann_et met däm nöüe Name alld_en Sigk jitt, <strong>ußßer</strong> do eß nix drop, odder et ess_en Ömlëijdung un se eß no nii jeänndot voode. Esu kam_mer en Sigk jlish widder zerögk ömnänne, wämmer sesh mem Ömnänne fodonn hätt, un mer kann_och këijn Sigge kapottmaache, wo alld jät drop shtëijdt.

<strong>Oppjepaßß!</strong> Wat bëijm Ömnänne eruß_kütt, künnd_en opfällije un fellëijsh shtüürende Änderong am Wikki sinn, besöndoß bëij öff jebruchte Sigge. Also beß sėsher, dat_E foshtëijß, wat_De hee am maache beß, ih_dat_E_t määß!',
'movepagetalktext'        => "Dä Sigk ier ÷:ksh:Talk_Sigk, wann_se_ëijn hätt, weed automattish medd_öm_jenanndt, '''ußßer''' wänn:
* di Sigg_enn_en annder ÷:ksh:Namespace kütt,
* en ÷:ksh:Talk_Sigk met däm nöüe Name alld do eß, un et shtëijd_och_jät drop,
* De unge en_däm Käßßje '''këij''' Höökshe aan häßß.

En dänne Fäll, moßß_De Der dä Ėnnhalldt fun dä ÷:ksh:Talk_Sigge slləfß für_nämme, un eröm_kopėere,
wat_De bruchß.",
'movearticle'             => 'Sigk Ömnänne',
'movenologin'             => '÷:ksh:MediaWiki:notloggedin',
'movenologintext'         => 'Do mööds_alld aanjemäldt un [[Special:Userlogin|ennjelogg]] sinn, öm en Sigk öm_ze_nänne.',
'newtitle'                => 'op dä nöüje Naame',
'movepagebtn'             => 'Ömnänne',
'pagemovedsub'            => 'Dat Ömnänne hätt_jeflupp',
'articleexists'           => "De Sigg met däm Name jitt et ald, oder dä Name ka'mer oder darf mer nit bruche.<br />Do muss Der ene andere Name ussöke.",
'talkexists'              => '<strong>Opjepaßß:</strong> Di Sigk sälləver woodt jäz ömjenanndt, ävver dä_ier ÷:ksh:Talk_Sigk kunnte mer net medt_öm_nänne. Et jidd_alld_ëijn met_däm nöüe Naame. Bess_esu_jood_un donn di zwëij fun hand zosamme lääje!',
'movedto'                 => 'ömjenanndt en',
'movetalk'                => 'dä_ier ÷:ksh:Talk_Sigk met_öm_nänne',
'talkpagemoved'           => 'Di ÷:ksh:Talk_Sigk do_zo wood medt_ömm_jenanndt.',
'talkpagenotmoved'        => 'Di ÷:ksh:Talk_Sigk do_zo wood <strong>nit</strong> ömmjenanndt.',
'1movedto2'               => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt.',
'1movedto2_redir'         => 'hät de Sigg vun „[[$1]]“ en „[[$2]]“ ömjenannt un doför de ahl Ömleitungs-Sigg fottjeschmesse.',
'movelogpage'             => 'Logbooch med de ömjenanndte Sigge',
'movelogpagetext'         => 'Hee sin_de_nöüßte ömjenanndte Sigge opjelėßß, unn_wä_t jedonn hätt.',
'movereason'              => 'Aanlaßß',
'revertmove'              => 'Et Ömnänne zerök_nämme',
'delete_and_move'         => 'Fottschmieße un Ömnenne',
'delete_and_move_text'    => '== Dä! Dubbelte Name ==
Dä Atikkel „[[$1]]“ jitt et ald. Wollts De en fottschmieße, öm hee dä Atikkel ömnenne ze künne?',
'delete_and_move_confirm' => 'Jo, dun dä Atikkel fottschmieße.',
'delete_and_move_reason'  => 'Fottjeschmesse, öm Platz för et Ömnenne ze maache',
'selfmove'                => 'Dů_Doof! — dä aahle Namme un dä nöüje Naame eß dä_sellve — do hädd_et Ömnänne winnish Sėnn.',
'immobile_namespace'      => 'Do künne_mer Sigge nit hen ömnänne, dat ÷:ksh:Namespace eß_shpezjäll, un_dä_nöüje_Name fö_di Sigk jëijd_däßwääje_nit.',

# Export
'export'          => 'Sigge Exporteere',
'exporttext'      => "Hee exportees De dä Tex un de Eijeschaffte vun ener Sigg, oder vun enem Knubbel Sigge, de aktuelle Version, met oder ohne ehr ählere Versione.
Dat Janze es enjepack en XML.
Dat ka'mer en en ander Wiki
- wann et och met dä MediaWiki-Soffwär läuf -
üvver de Sigg „[[Special:Import|Import]]“ do widder importeere.

* Schriev de Titele vun dä Sigge en dat Feld för Tex enzejevve, unge, eine Titel en jede Reih.
* Dann dun onoch ussöke, ov De all de vörherije Versione vun dä Sigge han wells, oder nor de aktuelle met dä 
Informatione vun de letzte Änderung. (En däm Fall künns De, för en einzelne Sigg, och ene tirekte Link bruche, 
zom Beispill „[[{{ns:special}}:Export/{{int:mainpage}}]]“ för de Sigg „[[{{int:mainpage}}]]“ ze exporteere)

Denk dran, datte dat Zeuch em Unicode Format avspeichere muss,
wann De jet domet aanfange künne wells.",
'exportcuronly'   => 'Bloß de aktuelle Version usjevve (un <strong>nit</strong> de janze ahle Versione onoch met dobei dun)',
'exportnohistory' => '----
<strong>Opjepass:</strong> de janze Versione Exporteere es hee em Wiki avjeschalt. Schad, ävver et wör en 
zo jroße Lass för dä Sörver.',
'export-submit'   => 'Loss_Jonn!',

# Namespace 8 related
'allmessages'               => 'All Tex, Baustein un Aanzeije vum Wiki-System',
'allmessagesdefault'        => 'Dä standaadmäßije Tex',
'allmessagescurrent'        => 'Esu es dä Tex jetz',
'allmessagestext'           => 'Hee kütt en Liss met Texte, Texstöck, un Nachrichte em Appachtemeng „MediaWiki:“',
'allmessagesnotsupportedDB' => '<strong>Dat wor nix!</strong> Mer künne „{{ns:special}}:Allmessages“ nit zeije, <code>wgUseDatabaseMessages</code> es usjeschalt!',
'allmessagesfilter'         => 'Fingk dat Stöck hee em Name:',
'allmessagesmodified'       => 'Dun nor de Veränderte aanzeije',

# Thumbnails
'thumbnail-more'  => 'Jrüüßer aanzëije',
'missingimage'    => '<b>Dat Bėlld es nit doh:</b><br />„$1“',
'filemissing'     => 'Datei es nit do',
'thumbnail_error' => 'Enne Fääler eß opjedouch bëijm Maache fun_em Breefmarrəke/Thumbnail-Belldshe: „$1“',

# Special:Import
'import'                     => 'Sigge Ėmpochtėere',
'importinterwiki'            => 'Tranß_Wikki Ėmpocht',
'import-interwiki-text'      => 'Wähl_en Wikki unn_en Sigk zem Ėmmpochtėere uß. Et Dattum fun de Väsjohne un de ÷:ksh:User_Naame fun de Schriiver weede dobëij metjenůmme. All de Tranß_Wikki Ėmmpochte weede em [[{{ns:special}}:Log/import|Ėmmpocht_LogBooch]] faßßjehallde.',
'import-interwiki-history'   => 'All de Väsjohne fun dä Sigk hee kopėere',
'import-interwiki-submit'    => 'Huh_Laade!',
'import-interwiki-namespace' => 'Donn de Sigge ėmpochtėere em ÷:ksh:Namespace:',
'importtext'                 => 'Dunn de Daate med däm „[[Special:Export|Ëxpocht]]“ fun doo fun ennem Wikki Äxpochtėere, do_bëij don_net — ättwa bëij Dir om Räshnor — affshpëijsherre, un dann hee huh_laade.',
'importstart'                => 'Ben Sigge am ėmpochtėere …',
'import-revision-count'      => '({{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}})',
'importnopages'              => 'Këijn Sigk för ze_Ėmpochtėere jefonge.',
'importfailed'               => 'Dat Impochtėere eß donëvve_jejange: $1',
'importunknownsource'        => 'Di Zoot Qwäll fö_t Ėmpochtėere kënne_mer nit',
'importcantopen'             => 'Kunnt op de Dattëij fö_dä Ėmpocht nit zohjriife',
'importbadinterwiki'         => 'Fokiehjter Ingerwiki_Lėngk',
'importnotext'               => 'En dä Dattëij wooh nix dren ännthallde, oddo_winnishßdenß këijne Täxx',
'importsuccess'              => 'Dat Ėmpochtėere hätt jeflupp!',
'importhistoryconflict'      => 'Mer hann zwëij aahle Väsjohne jefonge, di donn sėsh biiße — di ëijn wooh alld_doo — de annder en dä Ėmpoot_Dattëij. Mööshlesh, Ühr hatt_i Daate alld_enß ėmpootėedt.',
'importnosources'            => 'Hee sin këijn Qwälle fö_do ÷:ksh:MediaWiki:Importinterwiki ennjereshdt.
Dat aahle Väsjohne Huhlaade eß affjeschalldt, un_nit mööshlėsh.',
'importnofile'               => 'Et wood ja_këij Dattëij huh_jelaade fö_ze Ėmpochtėere.',
'importuploaderror'          => 'Dat Huh_Laade eß donevve jejange. Mööshlėsh, dat_te Dattëij ze_jruuß woh, jrüüßo wi_mmer huh_laade darrəf.',

# Import log
'importlogpage'                    => 'Logbooch med ėmpochtėerte Sigge',
'importlogpagetext'                => 'Sigge met iere Väsjohne fun annder Wikkiß ėmpochtėere.',
'import-logentry-upload'           => '„[[$1]]“ ėmpochtėet',
'import-logentry-upload-detail'    => '{{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}} ėmpochtėet',
'import-logentry-interwiki'        => 'tranß_wikki_ėmmpochtėet: „$1“',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}} fun „$2“',

# Tooltip help for the actions
'tooltip-search'                  => 'En de {{SITENAME}} sööke [alt-÷:ksh:MediaWiki:accesskey-search]',
'tooltip-minoredit'               => 'Dëijt Ding Ännderonge allß klëijn Minni_Ännderonge makėere. [alt-÷:ksh:MediaWiki:accesskey-minoredit]',
'tooltip-save'                    => 'Dëijt Ding Ännderonge affsphëijshere. [alt-÷:ksh:MediaWiki:accesskey-save]',
'tooltip-preview'                 => 'Lißß de Füür_Aansėsh fun dä Sigk un_fun_Dinge Ännderonge ih_dat_De_n Affsphëijshere dëijß! [alt-÷:ksh:MediaWiki:accesskey-preview]',
'tooltip-diff'                    => 'Zëijsh Ding Ännderone am Täxx aan. [alt-÷:ksh:MediaWiki:accesskey-diff]',
'tooltip-compareselectedversions' => 'Donn de Ungescheed zweshe dä bëijde ußjewäälte Väsjohne zëije. [alt-÷:ksh:MediaWiki:accesskey-compareselectedversions]',
'tooltip-watch'                   => 'Op di Sigk hee oppaßße. [alt-÷:ksh:MediaWiki:accesskey-watch]',
'tooltip-recreate'                => 'En fottjeschmeßßenne Sigk widderholle',

# Stylesheets
'common.css'   => '/** CSS hee aan dä Stell hät Uswirkunge op alle Skins */',
'monobook.css' => ' /* edit this file to customize the monobook skin for the entire site */
 
 /* distinguish redirections in Special:Allpages directory */
 .allpagesredirect {font-style:italic}
 
 /* Visualizza i bordi arrotondati sui browser basati su Geko */
 .pBody {
    padding: 0.1em 0.1em;
    -moz-border-radius-topright: 1em;
    -moz-border-radius-bottomright: 1em;
 }
 #p-cactions ul li, #p-cactions ul li a {  
    -moz-border-radius-topright: 0.8em;
    -moz-border-radius-topleft: 0.8em;
 }
 
 /* Kleinschreibung nicht erzwingen */
 .portlet h5, .portlet h6,
 #p-personal ul, #p-cactions li a {
    text-transform: none;
 }',

# Metadata
'nodublincore'      => 'De RDF_Metta_Daate fun de „Dublin Core“ Aat senn affjeschalldt.',
'nocreativecommons' => 'De RDF_Metta_Daate fun de „Creative Commons“ Aat senn affjeschalldt.',
'notacceptable'     => '<strong>Blööd:</strong> Dä Wikki_ßööver kann de Daate nit en_ennem Fomaat erövverjävve, wat Dinge [http://ksh.wikipedia.org/wiki/Help:Client Client] odde [http://ksh.wikipedia.org/wiki/Help:Brauser Brauser] foshtonn künnt.',

# Attribution
'anonymous'     => 'Namelose Metmaacher vun de {{SITENAME}}',
'siteuser'      => '{{SITENAME}}-÷:ksh:User $1',
'and'           => 'un',
'othercontribs' => 'Bout op de Ärbeëijdt fun „<strong>$1</strong>“ op.',
'others'        => 'anndere',
'siteusers'     => '{{SITENAME}}-÷:ksh:User $1',
'creditspage'   => 'Üvver de Metmaacher un ehre Beidräch för die Sigg',
'nocredits'     => 'Fö_di Sigk ham_mer nix en de Lėßß.',

# Spam protection
'spamprotectiontitle'    => 'SPAM_Shoz',
'spamprotectiontext'     => 'Di Sigk, di de affshpëijshere wellß, di weed fun unsem SPAM_Shoz net dorschjelohße. Dat küt domiiz fun ennem Lėngg_obb_en främmbde Sigk.',
'spamprotectionmatch'    => 'Hee dä Täx hät dä SPAM_Shoz op_de Plan jeroofe: „<code><nowiki>$1</nowiki></code>“',
'subcategorycount'       => 'Hee {{PLURAL:$1|weed ëijn ÷:ksh:Subcategory|wääde $1 ÷:ksh:Subcategories}} jezëijsh <small>&nbsp; (Et künnt mieh op de füürije un nähkßte Sigge jëvve)</small>',
'categoryarticlecount'   => 'Hee {{PLURAL:$1|weed eine Atikkel|wääde $1 Atikkele}} jezeich <small>  (Et künnt mieh op de vörije un nächste Sigge jevve)</small>',
'listingcontinuesabbrev' => ' wigger',
'spambot_username'       => 'SPAM fottschmiiße',
'spam_reverting'         => 'De läzde Väsjohn eß oohne_de Lėnggs_obb „$1“ widdo zerrögk_jeholldt.',
'spam_blanking'          => 'All di Väsjohne hatte Lėnggs_obb „$1“, di_sen_jäds_erruß_jemaat.',

# Info page
'infosubtitle'   => 'Övver de Sigk',
'numedits'       => 'Aanzal Ännderonge an_däm Atikkel: <strong>$1</strong>',
'numtalkedits'   => 'Aanzal Ännderonge aan de ÷:ksh:Talk_Sigk: <strong>$1</strong>',
'numwatchers'    => 'Aanzal Oppaßßer: <strong>$1</strong>',
'numauthors'     => 'Aanzal ÷:ksh:Users, di_an_dämm Atikkel jeshrevve hann: <strong>$1</strong>',
'numtalkauthors' => 'Aanzal ÷:ksh:Users bëijem ÷:ksh:Talk: <strong>$1</strong>',

# Math options
'mw_math_png'    => 'Ėmmer nuur PNG aanzëije',
'mw_math_simple' => 'En ëijnfaache Fäll maach HTML, sönß PNG',
'mw_math_html'   => 'Maach HTML wann mööshlish, un sönß PNG',
'mw_math_source' => 'Loohs_et als TeX (joot fö_de Täxx_Brausere)',
'mw_math_modern' => 'De bëßß Ënnshtëllung_fö_de_Brauser fun hügk',
'mw_math_mathml' => 'Nemm „MathML“ wän_mööshlish (em probier_Shtadijum)',

# Patrolling
'markaspatrolleddiff'        => 'Nohjeluert. Důnn dat faßßhallde',
'markaspatrolledtext'        => 'Di Änderong eß nohjeluert, donn dat faßßhallde',
'markedaspatrolled'          => 'Et Kënnzëijshe „Nohjeluert“ shpëijshere',
'markedaspatrolledtext'      => 'Ed_eß_jäz faßßhallde, dat_dė ußßjewäälte Ännderonge nohjeluert woode sinn.',
'rcpatroldisabled'           => 'Et Nohluere fun de läzde Ännderonge eß affjeschalldt',
'rcpatroldisabledtext'       => 'Et Nohluere fun de läzde Ännderonge eß fö_do_Mommännt nit mööshlėsh.',
'markedaspatrollederror'     => 'Kann dat Kënnzëijshe „Nohjeluert“ nit affshpëijshere.',
'markedaspatrollederrortext' => 'Do_moss_en beshtemmpte Väsjohn ußsööke.',

# Image deletion
'deletedrevision' => 'De ahl Version „$1“ es fottjeschmesse',

# Browsing diffs
'previousdiff' => '← De Ungersheede dö_für zëije',
'nextdiff'     => 'De Ungersheede do_noh zëije →',

# Media information
'mediawarning' => '<strong>Opjepaßß</strong>: En dä Dattëij küünd_en <b>jefääerlish Projramm_Shtögk</b> dren shtäke. Wäm_mer_et joufe loohße däät, do künndt dä ßööver met fö de [http://ksh.wikipedia.org/wiki/Help:Kräkkor Kräkkor] opjemaat wääde.
<hr />',
'imagemaxsize' => 'Bėllder op_de Sigge, wo_se beschrivve vääde, nit jrüüßer maache wi:',
'thumbsize'    => 'Esu brëijdt sůlle de klëijn Belldsche (Thumbnails/Breefmarrke) sinn:',
'widthheight'  => '<strong>$1</strong> x <strong>$2</strong>',

# Special:Newimages
'newimages'    => 'Bellder, Tööhn, uew. allß Jallerih',
'showhidebots' => '(÷:ksh:MediaWiki:group-bot $1)',
'noimages'     => 'Këij_Dattëijje jefonge.',

# Metadata
'metadata'          => 'Metta_Daate',
'metadata-help'     => 'En dä Datttëij shish noh_mieh an Daate. Dat sin Metta_Daate, di nommaal fum Oppname_Jerät kumme. Wadd_en Kammera, ne Skänner, un_esu, do faßßjehallde hann, dat kann_ävver spääder medd_ennem Projramm beärrbtëijdt un ůßjetuusch woode sinn.',
'metadata-expand'   => 'Mieh zëije',
'metadata-collapse' => 'Daate Foshtäshe',
'metadata-fields'   => 'De Metta_Daate in dä Leßß med Shtähnshe bliive aanjezëijsh, och wänn dä ÷:ksh:User de Metta_daate ußbländt. Dä Räß weed dann foshtoche.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Dun de Datei met enem externe Projramm bei Dr om Rechner bearbeide',
'edit-externally-help' => 'Luur op [http://meta.wikimedia.org/wiki/Help:External_editors Installationsanweisungen] noh Hinwies, wie mer esu en extern Projramm opsetz un installeere deit.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'all',
'imagelistall'     => 'all',
'watchlistall2'    => 'Alles',
'namespacesall'    => 'all',

# E-mail address confirmation
'confirmemail'            => 'E-Mail Adress bestätije',
'confirmemail_noemail'    => 'En [[Special:Preferences||Ding Enstellunge]] es kein öntlich E-Mail Adress.',
'confirmemail_text'       => 'Ih datte en däm Wiki hee de E-Mail bruche kanns, muss De Ding E-Mail Adress bestätich 
han, dat se en Oodnung es un dat se och Ding eijene es. Klick op dä Knopp un Do kriss en E-Mail jescheck. Do 
steiht ene Link met enem Code dren. Wann De met Dingem Brauser op dä Link jeihs, dann deis De domet 
bestätije, dat et wirklich Ding E-Mail Adress es. Dat es nit allzo secher, alsu wör nix för Die 
Bankkonto oder bei de Sparkass, ävver et sorg doför, dat nit jede Peijaß met Dinger E-Mail oder Dingem 
Metmaachername eröm maache kann.',
'confirmemail_send'       => 'Scheck en E-Mail zem Bestätije',
'confirmemail_sent'       => 'En E-Mail zem Bestätije es ungerwähs.',
'confirmemail_sendfailed' => 'Beim E-Mail Adress Bestätije es jet donevve jejange, dä Sörver hatt e Problem met 
sing Konfijuration, oder en Dinger E-Mail Adress es e Zeiche verkihrt, oder esu jet.',
'confirmemail_invalid'    => 'Beim E-Mail Adress Bestätije es jet donevve jejange, dä Code es verkihrt, künnt 
avjelaufe jewäse sin.',
'confirmemail_needlogin'  => 'Do muss Dich $1, för de E-Mail Adress ze bestätije.',
'confirmemail_success'    => 'Ding E-Mail Adress es jetz bestätich. Jetz künns De och noch 

[[Special:Userlogin|enlogge]]. Vill Spass!',
'confirmemail_loggedin'   => 'Ding E-Mail Adress es jetz bestätich!',
'confirmemail_error'      => 'Beim E-Mail Adress Bestätije es jet donevve jejange, de Bestätijung kunnt nit 
avjespeichert wääde.',
'confirmemail_subject'    => 'Dun Ding E-Mail Adress bestätije för de {{SITENAME}}.',
'confirmemail_body'       => 'Jod möchlich, Do wors et selver,
vun de IP_Adress $1,
hät sich jedenfalls einer aanjemeldt,
un well dä Metmaacher „$2“ op de {{SITENAME}}
wääde, un hät en E-Mail Adress aanjejovve.
Öm jetz klor ze krije, dat die E-Mail
Adress un dä neue Metmaacher och beienander
jehüre, muss dä Neue en singem Brauser
dä Link:

$3

opmaache. Noch för em $4. 
Alsu dun dat, wann de et selver bes.

Wann nit Do, sondern söns wer Ding E-Mail
Adress aanjejovve hät, do bruchs de jar nix
ze dun. De E-Mail Adress kann nit jebruch
wääde, ih dat se nit bestätich es.

Wann de jetz neujeerich jewoode bes un wells
wesse, wat met de {{SITENAME}} loss es,
do  jang met Dingem Brauser noh:
{{FULLURL:{{MediaWiki:Mainpage}}}}
un luur Der et aan.

Ene schöne Jroß vun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',

# Scary transclusion
'scarytranscludedisabled' => '[Et Ennbinge për Ingerwikki eß affjeschalldt]',
'scarytranscludefailed'   => '[De Schabloon „$1“ en_ze_binge hät nit jeflupp]',
'scarytranscludetoolong'  => '[Schadt, dė URL eß ze lang]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacks för dä Atikkel hee:<br />
„<strong>$1</strong>“
</div>',
'trackbackremove'   => ' ([$1 Fottschmiiße])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback eß fottjeschmeßße.',

# Delete conflict
'deletedwhileediting' => '<strong>Opjepass:</strong> De Sigg wood fottjeschmesse, nohdäm Do ald aanjefange häs, dran ze Ändere.',
'confirmrecreate'     => 'Dä Metmaacher [[User:$1|$1]] (?[[User talk:$1|däm sing Klaafs]]) hät die Sigg 
fottjeschmesse, nohdäm Do do dran et Ändere aanjefange häs. Dä Jrund:
: „<i>$2</i>“
Wells Do jetz met en neu Version die Sigg neu aanläje?',
'recreate'            => 'Zerrögk_holle',

# HTML dump
'redirectingto' => 'Lëijdt öm op „[[$1]]“...',

# action=purge
'confirm_purge'        => 'Dä Zweschespeicher för die Sigg fottschmieße?

$1',
'confirm_purge_button' => 'Jo - loss jonn!',

# AJAX search
'searchcontaining' => 'Söök noh Atikkelle, wo „$1“ em Täxx fürkütt.',
'searchnamed'      => 'Söök noh Atikkelle, wo „$1“ em Name fürkütt.',
'articletitles'    => 'Atikkele, die met „$1“ aanfange',
'hideresults'      => 'Äjepniß foshtäshe',

# Multipage image navigation
'imgmultipageprev' => 'â† de Sigg dovör',
'imgmultipagenext' => 'de Sigg donoh â†’',
'imgmultigo'       => 'Loss jonn!',
'imgmultigotopre'  => 'Jangk op de Sigg',

# Table pager
'ascending_abbrev'         => 'opwääts zoteet',
'descending_abbrev'        => 'raffkaz zoteet',
'table_pager_next'         => 'De nächste Sigg',
'table_pager_prev'         => 'De Sigg dovör',
'table_pager_first'        => 'De eetste Sigg',
'table_pager_last'         => 'De letzte Sigg',
'table_pager_limit'        => 'Zeich $1 pro Sigg',
'table_pager_limit_submit' => 'Loss jonn!',
'table_pager_empty'        => 'Nix erus jekumme',

# Auto-summaries
'autosumm-blank'   => 'Dä janze Enhald vun dä Sigg fottjemaht',
'autoredircomment' => 'Leit öm op „[[$1]]“',
'autosumm-new'     => 'Neu Sigg: $1',

);

