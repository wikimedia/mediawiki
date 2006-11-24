<?php
/** Ripuarian (Ripoarėsh)
 * The majority of users are bilingual in a Ripuarian language plus German, use German as fallback.
 * (could also choose english, dutch, or limburgish)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 * @author Purodha Blissenbach
 */

$fallback = 'de';

$namespaceNames = array(
	NS_MEDIA            => 'Meedije',
	NS_SPECIAL          => 'Shpezjal',
	NS_MAIN             => '',
	NS_TALK             => 'Klaaf',
	NS_USER             => 'Medmaacher',
	NS_USER_TALK        => 'Medmaacher_Klaaf',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_Klaaf',
	NS_IMAGE            => 'Beld',
	NS_IMAGE_TALK       => 'Belder_Klaaf',
	NS_MEDIAWIKI        => 'MedijaWikki',
	NS_MEDIAWIKI_TALK   => 'MedijaWikki_Klaaf',
	NS_TEMPLATE         => 'Schablon',
	NS_TEMPLATE_TALK    => 'Schablone_Klaaf',
	NS_HELP             => 'Hülp',
	NS_HELP_TALK        => 'Hülp_Klaaf',
	NS_CATEGORY         => 'Saachjropp',
	NS_CATEGORY_TALK    => 'Saachjroppe_Klaaf',
);

/**
 * Array of namespace aliases, mapping from name to NS_xxx index
 */
$namespaceAliases = array(
	'Belld'             => NS_IMAGE,
	'Bellder_Klaaf'     => NS_IMAGE_TALK,
	'Sachjrop'          => NS_CATEGORY,
	'Sachjrop_Klaaf'    => NS_CATEGORY_TALK,
	'Kattejori'         => NS_CATEGORY,
	'Kattejori_Klaaf'   => NS_CATEGORY_TALK,
	'Kategorie'         => NS_CATEGORY,
	'Kategorie_Klaaf'   => NS_CATEGORY_TALK,
	'Katejori'          => NS_CATEGORY,
	'Katejorije_Klaaf'  => NS_CATEGORY_TALK,
);

/**
 * Labels of the quickbar settings in Special:Preferences
 */
$quickbarSettings = array(
	"Fotlohse, dat well ish nitt sinn",
	"Am lėnke Rand faßß aanjepapp",
	"Am räächte Rand fßß aanjepapp",
	"Am lėnke Rand am Shwääve"
);


/**
 * Skin names. If any key is not specified, the English one will be used.
 */
$skinNames = array(
	'standard'      => 'Shtandad',
	'nostalgia'     => 'Noßtalljesh',
	'cologneblue'   => 'Kölsch Blau',
	'smarty'        => 'Päddingtonn',
	'montparnasse'  => 'Mont_Panaßß',
	'davinci'       => 'Da_Vintshi',
	'mono'          => 'Monno',
	'monobook'      => 'MonnoBooch',
	'myskin'        => 'Ming_Skin',
	'chick'         => 'Küüke'
);

$messages = array(
'tog-underline'         => 'Donn de Lėngkß ungershtriishe:',
'tog-highlightbroken'   => 'Zëijsh de Lėngkß op Sigge, di_jet_non_nit_jitt, esu met: „<a href="" 

class="new">Lämma</a>“ aan.<br />Wännß_De dat nit wellß, weed et esu: „Lämma<a href="" class="internal">?</a>“ 

jezëijsh.',
'tog-justify'           => 'Donn de Affschnedde em Bloksaz aanzëije',
'tog-hideminor'         => 'Donn de klëijn minni_Ännderonge (\'\'\'M\'\'\') en_de Lėßß_met „Nöüßte_Ännderonge“ 

shtanndad_määßish \'\'\'nit\'\'\' aanzëije',
'tog-extendwatchlist'   => 'Forjrüüßo de Oppaßß_Lėßß för jeede Aat fun mööshlėshe Ännderonge ze_zëije',
'tog-usenewrc'          => 'Donn_de Oppjemozzde Lėßß_met „Nöüßte_Ännderonge“ aanzëije (bruch Java_Skripp)',
'tog-numberheadings'    => 'Donn de Övverschreffte automatish nummerėere',
'tog-showtoolbar'       => 'Zëijsh de Wërrkzöüsh_Lëßß zom Änndere aan (bruch Java_Skripp)',
'tog-editondblclick'    => 'Sigge med Dubbel-Klikke Änndere (bruch Java_Skripp)',
'tog-editsection'       => 'Maach [Änndere]-Lėngkß aan de Affschnedde raan',
'tog-editsectiononrightclick'=> 'Affschnedde med Räähß-Klikke op de Övverschrevv_Änndere (bruch Java_Skripp)',
'tog-showtoc'           => 'Zëijsj_en Ėnnhallds_Övverseesh bëij Sigge met_mieh_vi drëij Övverschreffte dren',
'tog-rememberpassword'  => 'Op_Dauer Aanmällde',
'tog-editwidth'         => 'Maach dat Felld zom Täxx_Ėnnjävve_su_brëijdt, vi_t jëijdt',
'tog-watchcreations'    => 'Donn di Sigge fö ming Oppaßß_Lėßß fürschlaare, di_ish nöü aanläje',
'tog-watchdefault'      => 'Donn di Sigge fö ming Oppaßß_Lėßß fürschlaare, di_isch aanpakke un änndere donn',
'tog-minordefault'      => 'Donn all ming Ännderonge shtandad_mäßėj_allß klëijn Minni_Ännderonge fürschlaare',
'tog-previewontop'      => 'Zëijsh de Füür_Aansėsh övver dämm Felld för_dä Täxx ėnn_ze_jävve aan.',
'tog-previewonfirst'    => 'Zëijsh de Füür_Aansėsh tirräg füür_em eetzte Mool bëijm Beärrbëijde aan',
'tog-nocache'           => 'Donn et Sigge_Zweshe_Shpëijshere — et Caching — affschallde',
'tog-enotifwatchlistpages'=> 'Schegg_en e-mail, wänn_en Sigg_uß minge Oppaßß_Lėßß jeänndot wood',
'tog-enotifusertalkpages'=> 'Scheck mer e-mail, wänn ming Klaaf_Sigk jeänndot weed',
'tog-enotifminoredits'  => 'Scheck mer och en e-mail för klëijn Minni_Ännderonge',
'tog-enotifrevealaddr'  => 'Zëijsh ming e-mail Addräßß aan, en de Benohreshtėjonge pä e-mail',
'tog-shownumberswatching'=> 'Zëijsh de Aanzal Medmaacher di op di Sigk op_am_paßße sinn',
'tog-fancysig'          => 'Ungerschreff oohne outomatėshe Lėngk',
'tog-externaleditor'    => 'Nemm shtandad_mäßėsh en ëxtärrn „editor“-Projramm',
'tog-externaldiff'      => 'Nemm shtandad_mäßėsh en ëxtärrn „diff“-Projramm',
'tog-showjumplinks'     => 'Lėngkß ußjävve, di dem „bajjeerefrëije Zoojang“ hellfe důnn',
'tog-uselivepreview'    => 'Zëijsh_de „Lebänndijje Füür_Aansėsh Zëije“ (bruch Java_Skripp) (em Ußprobier_Shtadijum)',
'tog-autopatrol'        => 'Wänn esh jät ännder, dann jėlld di Sigk alß kontrollėet.',
'tog-forceeditsummary'  => 'Frooch nooh, wänn_en_dämm Felldt „Koot Zosammejefaßß, Kwälle“ bëijem Affshpëijshere nix 

dren shtëijdt',
'tog-watchlisthideown'  => 'Donn stanndad_määßisch ming ëijen
Änderonge <strong>nit</strong> en minger Oppaßß_Lėßß aanzëije',
'tog-watchlisthidebots' => 'Donn stanndad_määßisch dä Botß ier Änderonge 

<strong>nit</strong> en minger Oppaßß_Lėßß zëije',
'underline-always'      => 'jo, ėmmer',
'underline-never'       => 'nä',
'underline-default'     => 'nemm dem Brauser sing Ėnshtällung',
'skinpreview'           => '(Aanluere)',

# dates
'sunday'                => 'Sunndaach',
'monday'                => 'Mohndaach',
'tuesday'               => 'Dinnßdaach',
'wednesday'             => 'Medtvoch',
'thursday'              => 'Dunnorßdaach',
'friday'                => 'Friidaach',
'saturday'              => 'Sammbsdaach',
'sun'                   => 'Su.',
'mon'                   => 'Mo.',
'tue'                   => 'Di.',
'wed'                   => 'Me.',
'thu'                   => 'Du.',
'fri'                   => 'Fr.',
'sat'                   => 'Sa.',
'january'               => 'Jannowaa',
'february'              => 'Fäbrowaa',
'march'                 => 'Määz',
'april'                 => 'Apprell',
'may_long'              => 'Mëij',
'june'                  => 'Juuni',
'july'                  => 'Juuli',
'august'                => 'Ojjoßß',
'september'             => 'Säptämmbo',
'october'               => 'Oktoobo',
'november'              => 'Novämmbo',
'december'              => 'Dezämmbo',
'january-gen'           => 'Jannowaa',
'february-gen'          => 'Fäbrowaa',
'march-gen'             => 'Määz',
'april-gen'             => 'Apprell',
'may-gen'               => 'Mëij',
'june-gen'              => 'Juuni',
'july-gen'              => 'Juuli',
'august-gen'            => 'Ojjoßß',
'september-gen'         => 'Säptämmbo',
'october-gen'           => 'Oktoobo',
'november-gen'          => 'Novämmbo',
'december-gen'          => 'Dezämmbo',
'jan'                   => 'Jan',
'feb'                   => 'Feb',
'mar'                   => 'Mäz',
'apr'                   => 'Apr',
'may'                   => 'Mej',
'jun'                   => 'Jun',
'jul'                   => 'Jul',
'aug'                   => 'Auj',
'sep'                   => 'Sep',
'oct'                   => 'Okt',
'nov'                   => 'Nov',
'dec'                   => 'Dez',
'categories'            => 'Saachjroppe',
'pagecategories'        => '{{PLURAL:$1|Saachjropp|Saachjroppe }}',
'category_header'       => 'Attikkelle in_de Saachjropp „$1“',
'subcategories'         => 'Ungerjroppe',
'mainpage'              => 'Houpsigk',
'mainpagetext'          => '<big><strong>MediaWiki eß jäz enshtallėerdt.</strong></big>',
'mainpagedocfooter'     => 'Luer en dä [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide] wänn 

De weßße wellß wi de Wikki_ßoffwäer jebruch un bedeendt weede moß.

== För der Aanfang ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',
'portal'                => 'Övver {{SITENAME}}',
'portal-url'            => '{{ns:project}}:Medmaacher Pooz',
'about'                 => 'Övver {{SITENAME}}',
'aboutsite'             => 'Övver de {{SITENAME}}',
'aboutpage'             => '{{ns:project}}:Övver_de_{{SITENAME}}',
'article'               => 'Atikkel',
'help'                  => 'Hülp',
'helppage'              => '{{ns:project}}:Hülp',
'bugreports'            => 'Fähler mällde',
'bugreportspage'        => '{{ns:project}}:Konntak',
'sitesupport'           => 'Shpännde',
'sitesupport-url'       => '{{ns:project}}:Shpännde',
'faq'                   => 'FAQ',
'faqpage'               => '{{ns:project}}:FAQ',
'edithelp'              => 'Hülp fö_t Beärrbëijde',
'newwindow'             => '(Määd_e nöü Finßter op, wänn Dinge Brauser datt kann)',
'edithelppage'          => '{{ns:project}}:Hülp',
'cancel'                => 'Shtopp! Affbrëshe!',
'qbfind'                => 'Fingk',
'qbbrowse'              => 'Aanluere',
'qbedit'                => 'Änndere',
'qbpageoptions'         => 'Sigge_Ëijnshtällunge',
'qbpageinfo'            => 'Zosammehang',
'qbmyoptions'           => 'Ming Sigge',
'qbspecialpages'        => 'Shpezzjahl_Sigge',
'moredotdotdot'         => 'Mieh…',
'mypage'                => 'Ming Sigk',
'mytalk'                => 'ming Klaafsigk',
'anontalk'              => 'Klaaf fö_di IP_Addräßß',
'navigation'            => 'Jangk_noh',
'metadata_help'         => 'Däm Belld_sing Metta_Daate ([[{{ns:project}}:Metta_Daate fun Bellder|hee sin_se 

äkliert]])',
'currentevents'         => 'Nöüishkëijte',
'currentevents-url'     => '{{ns:project}}:Et Nöüßte',
'disclaimers'           => 'Henwieß',
'disclaimerpage'        => '{{ns:project}}:Impressum',
'privacy'               => 'Dateschotz un Jehëijmhalldung',
'privacypage'           => '{{ns:project}}:Dateschotz un Jehëijmhalldung',
'errorpagetitle'        => 'Fääler',
'returnto'              => 'Jangk widdo_noh: „$1“.',
'tagline'               => 'Uß de {{SITENAME}}',
'search'                => 'em Täxx',
'searchbutton'          => 'Sööke',
'go'                    => 'alß Tittel',
'searcharticle'                    => 'alß Tittel',
'history'               => 'Väsjohne',
'history_short'         => 'Väsjohne',
'updatedmarker'         => '(foänndot)',
'info_short'            => 'Ėnnfommazjohn',
'printableversion'      => 'För_ze Drokke',
'permalink'             => 'Allß Permalink',
'print'                 => 'För_ze Drokke',
'edit'                  => 'Änndere',
'editthispage'          => 'Di Sigk änndere',
'delete'                => 'Fottshmiiße',
'deletethispage'        => 'Di Sigk fott_schmiiße',
'undelete_short'        => '{{PLURAL:$1|ëijn Ännderong|$1 Ännderonge}} zerrökholle',
'protect'               => 'Shöze',
'protectthispage'       => 'Di Sigk schöze',
'unprotect'             => 'Schoz änndere',
'unprotectthispage'     => 'Dä Schoz fö_di Sigk ophävve',
'newpage'               => 'Nöü Sigk',
'talkpage'              => 'Övver di Sigk hee schwaade',
'specialpage'           => 'Söndersigk',
'personaltools'         => 'Medmaacher_Wërrkzöüsh',
'postcomment'           => 'Nöü Affschnett op_de Klaaf_Sigk',
'articlepage'           => 'Aanluere wat op_Dä Sigk drop_shtëijdt',
'talk'                  => 'Klaaf',
'views'                 => 'Aansėshte',
'toolbox'               => 'Wërrkzöüsh',
'userpage'              => 'Däm Medmaacher sing Sigk aanluere',
'projectpage'           => 'De Projäkk_Sigk aanluere',
'imagepage'             => 'Bėlld_Sigk aanluere',
'mediawikipage'         => 'De Meddëijongß_Sigk aanluere',
'templatepage'          => 'De Schablohn ier Sigk aanluere',
'viewhelppage'          => 'De Hülp_Sigk aanluere',
'categorypage'          => 'De Saachjroppe_Sigk aanluere',
'viewtalkpage'          => 'Klaaf aanluere',
'otherlanguages'        => 'En annder Shprooche',
'redirectedfrom'        => '(Ömjelëijdt fun $1)',
'autoredircomment'      => 'Lëijdt öm op „[[$1]]“',
'redirectpagesub'       => 'Ömlëijdungß_Sigk',
'lastmodifiedat'          => 'Shtand fum $2, $1',
'viewcount'             => 'Di Sigk eß beß jäz {{PLURAL:$1|ëijmol|$1 Mol}} affjeroofe woode.',
'copyright'             => 'Dä Ennhalldt_shtëijdt unger_de $1.',
'protectedpage'         => 'Jeshözde Sigk',
'jumpto'                => 'Jangk noh:',
'jumptonavigation'      => 'Noh_de Navvijazzjohn',
'jumptosearch'          => 'Jangk Sööke!',
'badaccess'             => 'Nit jenooch Rääshde',
'badaccess-group0'      => 'You are not allowed to execute the action you have requested.',
'badaccess-group1'      => 'Wat Do wellß, dat dörrve nuur Medmaacher, di $1 senn.',
'badaccess-group2'      => 'Wat Do wellß, dat dörrve nuur de Medmaacher uß dä Jroppe: $1.',
'badaccess-groups'      => 'Wat Do wellß, dat dörrve nuur de Medmaacher uß dä Jroppe: $1.',
'versionrequired'       => 'De Värsjon $1 fun MediaWiki ßoffwäer eß nüüdish',
'versionrequiredtext'   => 'De Värsjon $1 fun MediaWiki ßoffwäer eß nüüdish, öm di Sigk he bruche ze künne. Süsh op 

[[Special:Version|de Väsjohnß_Sigk]], wat mer hee förr_enne ßoffwäer_shtanndt hann.',
'ok'                    => 'Okee',
'pagetitle'             => '$1 - {{SITENAME}}',
'retrievedfrom'         => 'Die Sigk hee shtammp uß „$1“.',
'youhavenewmessages'    => 'Do häßß $1 ($2).',
'newmessageslink'       => 'nöü Meddëijlonge op Dinger Klaaf_Sigk',
'newmessagesdifflink'   => 'Ungerscheed zor füürläzde Väsjoon',
'editsection'           => 'Änndere',
'editold'               => 'Hee di Väsjohn Änndere',
'editsectionhint'       => 'Affshnedt änndere: $1',
'toc'                   => 'Enhalldtß_Övverseesh',
'showtoc'               => 'ennblännde',
'hidetoc'               => 'ußblännde',
'thisisdeleted'         => '$1 — aanluere odder widder zerrögk_holle?',
'viewdeleted'           => '$1 aanzëije?',
'restorelink'           => '{{PLURAL:$1|ëijn fottjeschmeßßen Ännderong|$1 fottjeschmeßßene Ännderonge}}',
'feedlinks'             => 'Fiidt:',
'feed-invalid'          => 'Esu enne Tüüp Abonnomang jid_det nit.',
'nstab-main'            => 'Atikkel',
'nstab-user'            => 'Medmaacher_Sigk',
'nstab-media'           => 'Medije_Sigk',
'nstab-special'         => 'Shpezzjahl',
'nstab-project'         => 'Projägk_Sigk',
'nstab-image'           => 'Belldt',
'nstab-mediawiki'       => 'Täxx',
'nstab-template'        => 'Schabbloon',
'nstab-help'            => 'Hülp',
'nstab-category'        => 'Saachjropp',
'nosuchaction'          => 'Di Oppjav (action=) känne mer nit',
'nosuchactiontext'      => '<strong>Na su_jëtt:</strong> Di Oppjaaf us dä URL, 

di_do hėnger „<code>action=</code>“ dren shtëijdt, jo_di kännt hee dat Wikki jaa_nit.',
'nosuchspecialpage'     => 'Esu en Söndersigk ham_mer nit',
'nospecialpagetext'     => 'Di aanjefroochte Söndersigk jidd_et nit, de [[Special:Specialpages|Lėßß met_te 

Söndersigge]] hėllef Do wigger.',
'error'                 => 'Fääler',
'databaseerror'         => 'Fääler in_de Daate_Bangk',
'dberrortext'           => 'Enne Fääler es_opjefalle en dä Süntax fun_ennem Befääl fö_de_Date_Bank.
Dat künnd_enne Fääler en de ßoffwäer fum Wikki sinn.
De läzde Date_Bank_Befääl eß jewääse:
<blockquote><code>$1</code></blockquote>
uß däm Projramm singe Funkzjohn: „<code>$2</code>“.<br />
MySQL mälldt dä Fääler: „<code>$3: $4</code>“.',
'dberrortextcl'         => 'Enne Fääler es_opjefalle en dä Süntax fun_ennem Befääl fö_de_Date_Bangk.
Dä läzde Befääl fö_de Date_Bangg_eß jewääse:
<blockquote><code>$1</code></blockquote>
uß däm Projramm singe Funkzjohn: „<code>$2</code>“.<br />
MySQL mälldt dä Fääler: „<code>$3: $4</code>“.',
'noconnect'             => 'Schadt!
Mer kunnte këijn Fobinndung med_däm Daate_Bank_ßöövo op „$1“ krijje.',
'nodb'                  => 'Kunnt de Daate_Bangk „$1“ nit ußßwääle',
'cachederror'           => 'Dät hee ėss_en Kopii fun_dä Sigk uss_em Cache. 

Mööshlish, se iß nit aktowäll.',
'laggedslavemode'       => '<strong>Opjepaßß:</strong> Künnt sinn, dat hee nit dä nöüßte Shtanndt fun dä Sigk 

annjezëijsh weedt.',
'readonly'              => 'De Daate_Bangg_eß jeshpächt',
'enterlockreason'       => 'Jiff aan, woröm un fö_wi_lang dat de Daate_Bangk jeshpächt wääde sull',
'readonlytext'          => 'De Daate_Bangk eß jeshpächt. Nöü Saache dren affshpëijshere jëijd_jrad nit, un Änndere 
och nit. Dä Jrunndt: „$1“',
#Et weed wall_öm_de nommaale Waadung joonn. Důnn_et ëijnfarr_enn_e_paa Menutte widdo fosööke.
#The administrator who locked it offered this explanation: $1',
'missingarticle'        => 'Dä Täxx fö de Sigk „$1“ kunndte mer nit en de Date_Bank finge.

Di Sigk iß fellëijsh fottjeschmeßße oddo ömmjenanndt woode.

Wann dat esu nit sinn sullt, dann hadd_Er fellëijsh_enne Fääler en de ßoffwäer fefonge.
Vozälld_ed_ennem Wikki_Köbes,
un doohd_em och de URL fun dä Sigk hee saare.',
'readonly_lag'          => 'De Daate_Bank eß fö_n koote Zigk jeshpächt, fö_de Daate aff_ze_jliishe.',
'internalerror'         => 'De Wikki-ßoffwäer hädd_enne Fääler jefonge',
'filecopyerror'         => 'Kunnt dė Dattëij „$1“ nit noh „$2“ kopėere.',
'filerenameerror'       => 'Kunnt dė Dattëij „$1“ nit op „$2“ ömdöüfe.',
'filedeleteerror'       => 'Kunnt dė Dattëij „$1“ nit fottschmiiße.',
'filenotfound'          => 'Kunnt dė Dattëij „$1“ nit finge.',
'unexpected'            => 'Domet hät këijne jo jeräshnet: „$1“=„$2“',
'formerror'             => 'Dat eß donävve jejange: Woh nix, met dämm Fomullaa.',
'badarticleerror'       => 'Dat jëijdt met hee dä Sigk nit ze maache.',
'cannotdelete'          => 'Di Sigk odder di Dattëij hee fott_ze_schmiiße eß nit mööshlėsh. Mööshlish, dat enne 

anndere Medmaacher flöcker wooh, hädd_et füjo_hejo alld jedonn, un jäz eß_di Sigg_ald fott.',
'badtitle'              => 'Fokiehrte Övverschreff',
'badtitletext'          => 'Di Övverschreff ėß esu nidd_ėn_Ochtnung.
Et moßß jët dren shtonn.
Et künnt sinn, dat ëijn fun_de Spėzjäll Zëijshe drėn shtëijdt,
wat ėn Övverschreffte nit älaub_eß.
Et künnd_ußsinn, wi_enne Inter_Wikki_Lėngk,
dat_jëijd_ävver nit.
Moßß_De reparėere.',
'perfdisabled'          => '<strong>\'\'\'Opjepaßß:\'\'\'</strong> Dat maache mer jäz nit — dä ßööver hät jraad 

zo_fill Laßß — do sim_mer jät fürseshtesh.',
'perfdisabledsub'       => 'Hee kütt en jeshpëijshote Koppii fun $1:',
'perfcached'            => 'De Daate he_noh kumme ussem Zwesheshpëijshor (cache) un künnte nit_mieh_janz de allonöüßte sinn.',
'perfcachedts'          => 'De Daate he_noh kumme ussem Zwesheshpëijshor (cache) un woodte $1 opjenumme. Se künnte nit_janz de allonöüßte sinn.',
'wrong_wfQuery_params'  => 'Fokiehrte Parrammeter för: <strong><code>wfQuery()</code></strong><br />
De Funkßjohn eß: „<code>$1</code>“<br />
De Aanfrooch eß: „<code>$2</code>“<br />',
'viewsource'            => 'Wikki_Täx Aanluere',
'viewsourcefor'         => 'för di Sigk: „$1“',
'protectedtext'         => 'Di Sigk hee eß jäje Veränderonge jeschöz.

Do künnd_et an Aanzaal fun Ursaache fö_jëvve. Fellëijsh fingk mer jädd_em <span 

class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logbooch]</span> do_drövver.

Jeede kann sijj_ävver dä Wikkitäx fun dä Sigk aanluere un och koppėere. He kütt_e:',
'protectedinterface'    => 'Op dä Sigk hee shtëijdt Täggs_ussem Ingerfäjß fun de Wikki-ßoffwäer.
Dröm eß dii jäje Änderonge jeschöz, domet Këijne ööhndsenne Meßß domet aanshtälle künne sull.',
'editinginterface'      => '<strong>Opjepaßß:</strong> 
Op dä Sigk hee shtëijdt Täggs_ussem Ingerfäijß fun de Wikki-ßoffwäer. Dröm eß dii jäje Änderonge jeschöz, domet 

Këijne ööhndsenne Meßß domet aanshtälle künne sull. Nuur de Wikki_Köbeßße künne 

se änndere. Dängk draan, hee Änndere dëijd_et Ußsinn un de Wööt änndere met dänne et Wikki op de Medmaacher un de 

Besööker drop aankütt!',
'sqlhidden'             => '(Dä SQL_Befääl dům_mer nit zëije)',
'logouttitle'           => 'Ußß_Logge',
'logouttext'            => 'Jäz beß_De ußßjelogg.

* Do künnz op de {{SITENAME}} wigger maache, alls_enne name_lose Medmaacher.

* Do kannß Dėjj_ävver_och widdo [[Special:Userlogin|ėnnlogge]], allß do_sälləve oddo och enne anndere Medmaacher.

* Un Do kanns_enne <span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} enne nöüje Medmaacher 

aanmällde]</span>.

<strong>Opjepaßß:</strong>

Eß mööshlish, dat_Te de ëijn_oddo_anndere Sigk ėmmo wiggo aanjezëijsh krißß, wi wänn de noch ėnnjelogg_wööhß. Donn 

Dingem Brauser singe Cache 

fottschmiiße oddo leddish_maache, öm uß dä Nummo_erruß_ze_kumme!<br />',
'welcomecreation'       => '== Tach, $1! ==

Dinge Zojang fö_hee eß doh. Do beß jäz aanjemälldt. Dengk draan, Do küünz Der Ding [[Special:Preferences|ming 

Ëijnshtellunge]] hee op de {{SITENAME}} zerrääshmaache.',
'loginpagetitle'        => 'Ėnnlogge',
'yourname'              => 'Medmaacher_Name',
'yourpassword'          => 'Paßßwoodt',
'yourpasswordagain'     => 'Norr_enß dat Paßßwoodt',
'remembermypassword'    => 'Op_Dauer Aanmällde',
'yourdomainname'        => 'Ding Domain',
'externaldberror'       => 'Do woo enne Fääler en de äxtärrne Daate_Bangk, oddo Do darrəfß Ding äxtärrn Daate nit 

änndere. Dat Aanmällde jingk donävve.',
'loginproblem'          => '<strong>Med däm Ėnnlogge eß jëtt schëijf jeloufe.</strong><br />Beß esu jood, un důnn_et 

norr_enß fosööhke!',
'alreadyloggedin'       => 'Do beß alld ennjelogg, als dä Medmaacher „<strong>$1</strong>“. Wänn_De nuur ann_ennem 

Kompjuto sez, wo sesj_enne Anndere enjelogg hätt, dann bess_esu jood, un důnn [[Special:Userlogout|ußßlogge]], iih 

dat_Te Sigge änndere dëijß! Dat moß_De och donn, wänn_De Desh don_noh medd_ennem anndere Naame wi 

„<strong>$1</strong>“ widde ennlogge wellß.<br />',
'login'                 => 'Ėnnlogge',
'loginprompt'           => 'Öm op de {{SITENAME}} [[Special:Userlogin|ennlogge]] ze künne,
moßß_De de Cookieß]en Dingem Brause]ennjeschalldt hann.',
'userlogin'             => 'Ėnnlogge / Medmaacher wääde',
'logout'                => 'Ußß_Logge',
'userlogout'            => 'Ußlogge',
'notloggedin'           => 'Nėd_Ėnnjelogg',
'nologin'               => 'Wänn_De Dėsh noh_nit aanjemälldt häßß,
dann donn Dėsh $1.',
'nologinlink'           => 'Nöü Aanmällde',
'createaccount'         => 'Aanmällde als_enne nöüje Medmaacher',
'gotaccount'            => 'Do häßß alld_enn Aanmälldung op de {{SITENAME}}? Dann jangk noh_m $1.',
'gotaccountlink'        => 'Enlogge',
'createaccountmail'     => 'Paßßwoodt med e-mail Schekke',
'badretype'             => 'Ding zwëij ennjejovvene Paßßwööter sinn ungerscheedlish. Do moßß_De Desch fö_ëijn 

änntschëijde.',
'userexists'            => 'Enne Medmaacher med_däm name: „<strong>$1</strong>“ jidd_et alld. Schaadt. Doh moßß De 

Der_enne anndere Naame ußdängke.',
'youremail'             => 'E-mail *',
'username'              => 'Medmaacher_Name:',
'uid'                   => 'Medmaacher ID:',
'yourrealname'          => 'Dinge rishtijje Name *',
'yourlanguage'          => 'Shprooch:',
'yourvariant'           => 'Ding Varijant',
'yournick'              => 'Name fö_en_Dinge Ungerschreff:',
'badsig'                => 'Di Ungeschreff jëijd_esu nit — luer noh dem HTML 

do_dren un maach et rėshtėsh.',
'email'                 => 'E-Mail',
'prefs-help-email-enotif'=> 'Di e-mail Addräßß weed och jebruch, öm Der övver Ännderonge beshëijdt_ze_saare, wänn_De 

dat ußßjevääldt häßß, en Dinge Ëijnshtellunge.',
'prefs-help-realname'   => '* Dinge rishtijje Name — kannz_E fott_loohße — wänn_De_n nänne wellß, do weedt_e jebruch, 

öm Ding Bëijdrääsh hee, domet ze schmökke.',
'loginerror'            => 'Fääler bem Ennlogge',
'prefs-help-email'      => '* E-mail — kannß_De fott_loohße, un es för Anndre nit_tse sinn — määd_et ävver mööshlish, 

dat mer met Dier en Kontak_kumme kann, oohne dat mer Dinge Name odder Ding e-mail Adräß känne däät.',
'nocookiesnew'          => 'Dinge nöüje Medmaacher_Name eß ėnnjerėshdt, ävver dat outomaatish Ėnnlogge woo dan_nix. 

Schaadt. De {{SITENAME}} bruch Cookieß, öm ze merrəke, wä 

ėnjelogg_eß. Wänn_De Cookieß affjeschaldt häß, en Dingem Brauser, dann kann 

dat nit loufe. Söök_Der_enne Brauser, dä et kann, donn_se ennschallde, un dann log Dėsh norr_enß nöü ėnn, met Dingem 

nöüje Medmaacher_Name un Paßßwoodt.',
'nocookieslogin'        => 'De {{SITENAME}} bruch Cookieß förr_et 

Ėnlogge. Et süüht esu uß, alß hättß_de Cookieß affjeschalldt. Důnn_se aanschallde un_dann fosöhg_et norr_enß.',
'noname'                => 'Dat jëijdt nidd_alls_enne Medmaacher_Naame. Jäz moßß_De_et norr_enß fosööke.',
'loginsuccesstitle'     => 'Dat Ėnlogge hät jeflupp.',
'loginsuccess'          => '<br />Do beß jäz enjelogg_bëij_de <strong>{{SITENAME}}</strong>, un Dinge 

Medmaacher_Naame eß „<strong>$1</strong>“.<br />',
'nosuchuser'            => 'Dat Passwoot odder dä Medmaacher_Naam woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.
Odder_<span class="plainlinks">[{{FULLURL:Special:Userlogin|type=signup}} enne nöüje Medmaacher aanmällde]</span>.',
'nosuchusershort'       => 'Dä Medmaacher_Naam woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.',
'nouserspecified'       => 'Dat jëijdt nidd_alls_enne Medmaacher_Naame',
'wrongpassword'         => 'Dat Passwoot odder dä Medmaacher_Naame woo fokiehrt. Jäz moßß_De_et norr_enß fosööke.',
'wrongpasswordempty'    => 'Dat Paßßwoodt kam_mer nit fott_loohße.
Jäz moßß_De_et norr_enß fosööke.',
'mailmypassword'        => 'Paßßwoodt fojäßße?',
'passwordremindertitle' => 'Login op {{SITENAME}}',
'passwordremindertext'  => 'Joot mööshlish, Do wooß et sellver,
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
'noemail'               => 'Dä Medmaacher hät en de $1 këijn e-mail Addräßß aanjejovve.',
'passwordsent'          => 'E nöü Paßßwoodt eß aan de e-mail Addräßß fun däm Medmaacher ungerwähß. Mälldt desh do_met 
aan, wänn_De_t häßß. Dat aahle Paßßwoodt blief ähallde un kann och noch jebruch wääde, beß dat De Dejj_et eezt Mohl 
met däm Nöüe ėnnjelogk häßß.',
'eauthentsent'          => 'En e-mail eß jäz ungerwääß aan di Addräß, di en de Ëijnshtellunge vum Medmaacher $1 

shtëijdt.
Ih dat_do_hen jäds_och e-mails övver de {{SITENAME}} iere e-mail-Knopp foshekk wääde künne, moß di e-mail Addräßß 
eetß_enß beshtähtish woode sinn. Wat mer do_fö_maache moß, shtëijd_en dä e-mail dren, di jrad affjeschek woode_eß. 

Allso luer do_erinn, un donn_et.',
'mailerror'             => 'Fääle bëij_em e-mail foshekke: $1.',
'acct_creation_throttle_hit'=> '<b>Schaadt.</b> Do häß alld {{PLURAL:$1|ëijne|$1}} Medmaacher_Naame aanjelaat. Mieh 

senn nit mööshlėsh.',
'emailauthenticated'    => 'Ding e-mail Addrëßß wood beshtäätisj_om: $1.',
'emailnotauthenticated' => 'Ding e-mail Addrëßß ėß nit beshtäätish.
Dröm kann këijn e-mail aan Dėsh jeschekk wääde för:',
'noemailprefs'          => 'Důnn_en e-mail Adräßß enndraare, domet dadd_all fluppe kann.',
'emailconfirmlink'      => 'Donn Ding e-mail Addrëßß beshtäätije loohße',
'invalidemailaddress'   => 'Wat_De do alls_en e-mail Adräßß aanjejovve häß, süüt noh Drißß uß. En e-mail Addräss_en 

däm Fommat, dat jidd_et nit. Moß De reparėere — oddo Do määß dat Fëlld lëddish un schriifß nigs_errinn. Un_dann 

fosöög_et norr_enß.',
'accountcreated'        => 'Aanjemëlldt',
'accountcreatedtext'    => 'De Aanmëlldung fö_dä Medmaacher „<strong>$1</strong>“ eß dorsh, kann jätz enlogge.',
'bold_sample'           => 'Fätt_Schreff',
'bold_tip'              => 'Fätt_Schreff',
'italic_sample'         => 'Shëijve Schreff',
'italic_tip'            => 'Sheeve Schreff',
'link_sample'           => 'Angkor_Täxx',
'link_tip'              => 'Enne Lingk en de {{SITENAME}}',
'extlink_sample'        => 'http://www.example.com/ Dä Angkor_Täx',
'extlink_tip'           => 'Enne Lingk noh drußße (dängk draan, http:// aan der Aanfang!)',
'headline_sample'       => 'Övverschreff',
'headline_tip'          => 'Övverschreff op de bövverschte Ebenne',
'math_sample'           => 'Hee schrieef di Forrmel hen',
'math_tip'              => 'En mattemaatisch Forrmelle nemm „LaTeX“',
'nowiki_sample'         => 'Hee kütt dä Täx hen, dä fun de Wikki_ßoffwäer net beärbëijdt, un en Rou jeloohße wääde 

sull',
'nowiki_tip'            => 'De Wikki_Koode övverjonn',
'image_sample'          => 'Beijshpill.jpg',
'image_tip'             => 'E Belltsche ennboue',
'media_sample'          => 'Beijshpill.ogg',
'media_tip'             => 'Enne Lengk ob_enn Ton_Datteij, e Filləmshe, odder_esu_jät',
'sig_tip'               => 'Dinge Naame, med de Urzigk unn_em Dattum',
'hr_tip'                => 'En Qweerlinnish',
'summary'               => 'Koot Zosammejefaßß, Kwälle',
'subject'               => 'Övverschreff — wo_dröm jëijd_et?',
'minoredit'             => 'Dad_ess_en klëijn Ännderung (mini)',
'watchthis'             => 'Op di Sigk hee op_paßße',
'savearticle'           => 'Di Sigk Affspëijshere',
'preview'               => 'Füür_Aansėsh',
'showpreview'           => 'Füür_Aansėsh Zëije',
'showlivepreview'       => 'Lebänndijje Füür_Aansėsh Zëije',
'showdiff'              => 'De Ungerscheed zëije',
'anoneditwarning'       => 'Wëijl De net [[Special:Userlogin|ennjelogg]] beß, weedt Ding 

IP_Addräßß opjezëijshnet wääde, bëij_de Lėßß fun de <span 

class="plainlinks">[{{FULLURL:{{PAGENAME}}|action=history}} Donn DingVäsjohne]</span> fun_de Ännderonge fö_di_Sigk.

Wänn_De dat nit hann wellß, důnn_nix affshpëijshere, oddo Dëijß äävenß [[Special:Userlogin|ennjelogge]], nê.',
'missingsummary'        => '<strong>Opjepaßß:</strong> Do häß nix bëij „Koot Zosammejefaßß, Kwälle“ ennjejovve. Donn 

norr_enß op „<b style="padding:2px; background-color:#ddd; color:black">Di Sigk Affspëijshere</b>“ klikke, öm Ding 

Ännderonge der_oohne_ze Shpëijshere, ävver bäßßo jißß_De do jätß_tirrägg_enß jätt enn!',
'missingcommenttext'    => 'Jivv_en „Koot Zosammejefaßß, Kwälle“ aan!',
'blockedtitle'          => 'Dä Medmaacher eß jeshpächt',
'blockedtext'           => '<big><b>Dinge Medmaacher_Name odder IP_Addräßß eß fun „$1“ jeshpächt woode.</b></big>

Alß Jrund_eß ennjedraare: „<i>$2</i>“

Do kannß met „$1“ oddo enne anndere Wikki_Köbes övver dat Shpärre schwade, wänn 

de wellß.

Do kannß ävver nur dann dat „<i>E-mail aan dä Medmaacher</i>“ aanwände, wänn de ald en e-mail Adräßß en Dinge 

[[Special:Preferences|ming Ëijnshtellunge]] ennjedrare un frëijjejovve häßß.

Ding IP_Addräßß eß de „$3“. Dunn se en Dinge Aanfroore nänne.',
'blockedoriginalsource' => 'Dä Orrijinaal Wikki_Täxx fun dä Sigk „<strong>$1</strong>“ shtëijdt he drungo:',
'blockededitsource'     => 'Dä Wikki_Täxx fun <strong>Dinge Ännderonge</strong> aan dä Sigk „<strong>$1</strong>“ 

shtëijdt he drungo:',
'whitelistedittitle'    => 'Enlogge nüüdish för Sigge ze Änndere',
'whitelistedittext'     => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]], öm hee em Wikki Sigge änndere ze 
dörrve.',
'whitelistreadtitle'    => 'Enlogge nüüdish för ze Lässe',
'whitelistreadtext'     => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]], öm hee Sigge Lësse ze dörrve.',
'whitelistacctitle'     => 'Këij Rääsh för Medmaacher aan_ze_lääje.',
'whitelistacctext'      => 'Do möötß alld_[[Special:Userlogin|ėnnjelogk_sinn]] un shpezzjäll_et Rääsh dofüer hann, öm 

hee en dämm Wikki Medmaacher ėnnrishte un aanlääje ze dörrəve.',
'confirmedittitle'      => 'För_et Sigge Änndere moß_De_Ding e-mail Adräßß ald beshtätėsh hann.',
'confirmedittext'       => 'De moß_Ding e-mail Adräßß ald beshtätėsh hann, ih_dat_De hee Sigge änndere darrəfß. 

Draach Ding e-mail Addräßß en Dinge [[{{ns:special}}:Preferences|ming Ëijnshtellunge]] enn, un důnn „<span 

style="padding:2px; background-color:#ddd; color:black">Donn Ding e-mail Addrëßß beshtäätije loohße</span>“ klikke.',
'loginreqtitle'         => 'Enlogge is nüüdish',
'loginreqlink'          => 'ėnnlogge',
'loginreqpagetext'      => 'Do moßß $1 ömm annder Sigge aanzeluere.',
'accmailtitle'          => 'Paßßwordt fosheck',
'accmailtext'           => 'Dat Paßßwoodt fö_dä Medmaacher „$1“ eß aan „$2“ jeschek woode.',
'newarticle'            => '(Nöü)',
'newarticletext'        => 'Enne Lėngk ob_en Sigk, wo non_nix drop shtëijdt, wëijl_et se noh jaa nit jitt, hät_Dish 

noh_hee jebraat.<br />

<small>Öm di Sigk aanzelääje, schrief hee unge en dat Fëlld_errinn, un donn et dann affshpëijshere. (Luer op de 

Sigge met Hülp noh, wänn De mih do drövver weßße wellß)<br />Wänn De jaa nit hee hen 

kumme wůlltß, da_jangk zerög_op di Sigk, wo De heeo_jekumme beß, Dinge Brauser hädd_enne Knopp do_för.</small>',
'anontalkpagetext'      => '----
<i>Dat hee eß de Klaaf_Sigk för_enne namenlose Medmaacher. Dä hät sesh noch këijne Medmaacher_Name jejovve un 

ėnnjereshdt, ov dëijt këijne bruche. Dröm bruche mier sing IP Addräßß ömm It oddo Inn en unsere Lėßßte faßzehallde. 

Su_w_en IP Adräßß kann fun janzz fille Medmaacher op ëijmool jebruch wääde, un ëijne Medmaacher kann janz flögk 

zwesche de ungescheedlishßte IP Adräßße wääßelle, wö_mööshlish oohn_datt_er_et märrək. Wänn Důů_jäds_enne namenlose 

Medmaacher beß, un fingkß, dat hee Saache an Dish jeschrevve wääde, wo Do jaa_nix medd_am_Hoot häßß, dann beß Doo 

vashëijnlijj_och nit jemëijndt. Dängk fellëijsj_enß drövver noh, dat_E Desh [[Special:Userlogin|aanmällde]] däijß, 

domet De dann don_noh nit mieh medd_esu_en Ömshtändt_ze donn häßß, vi di anndere namenlose Medmaacher hee.</i>',
'noarticletext'         => 'Hee eß jeds_em Momang këijne Täggs_ob_dä Sigk.<br />Jangk en de Täxte fun annder Sigge 

[[Special:Search/{{PAGENAME}}|noh däm Tittel sööhke]], oddor jangk, un <span 

class="plainlinks">[{{FULLURL:{{FULLPAGENAME}}|action=edit}} fang di Sigk aan]</span> ze schriive.<br 

/><small>Oddo_jangk zerök wo de heer koohmß. Dinge Brauser hädd_enne Knopp do_füer.</small>',
'clearyourcache'        => '<br clear="all" style="clear:both">
\'\'\'Opjepaßß:\'\'\'
Noh_rem Shpëijshere, künnd_et sinn, dat_Te Dingem Brauser singe Cache_Spëijsher 

övverlißßte moß, ih_dat_Te di Ännderonge och ze sinn krißß.
Bëijm \'\'\'Mozilla\'\'\' un  \'\'\'Firefox\'\'\' un \'\'\'Safari\'\'\', dröck de \'\'Jruß_Schreff_Knopp\'\' un 

Kligg_op \'\'Refresh\'\' / \'\'Aktualisieren\'\', oddo drögk \'\'Ctrl-Shift-R\'\' / \'\'Strg+Jruß_Schreff+R\'\', oddo 

drögk \'\'Ctrl-F5\'\' / \'\'Strg/F5\'\' / \'\'Cmd+Shift+R\'\' / \'\'Cmd+Jruß_Schreff+R\'\', je noh Dinge Taßtattuuer 

un Dingem Kompjutor.
Bëijm \'\'\'Internet Explorer\'\'\' drögg_op \'\'Ctrl\'\' / \'\'Strg\'\' un Kligg_op \'\'Refresh\'\', oddo drögk 

\'\'Ctrl-F5\'\' / \'\'Strg+F5\'\'.
Bëijm \'\'\'Konqueror:\'\'\' kligk dä \'\'Reload\'\'-Knopp oddo dröck dä \'\'F5\'\'-Knopp.
Bëijm  \'\'\'Opera\'\'\' kannß_De övver_et Mennüh jonn un 

däm jannze Cache singe Ėnnhalld_övver \'\'Tools→Preferences\'\' fott_schmiiße.',
'usercssjsyoucanpreview'=> '<b>Tipp:</b> Donn met dämm <b style="padding:2px; background-color:#ddd; 

color:black">Füür_Aansėsh Zëije</b>-Knobb_ußßprobėere, wat Ding nöü 

Medmaacher_CSS/Java_Skripp määd,_iih_dat_et affshpëijshore dëijß!',
'usercsspreview'        => '<b>Opjepaßß: Do beß hee nur am Ußßprobėere, wat Ding 

Medmaacher_CSS määd,_ed_eß non_nit jeseshot!</b>',
'userjspreview'         => '<b>Opjepaßß: Do beß hee nur am Ußßprobėere, wat Ding 

Medmaacher_Java_Skripp määd_ed_eß non_nit jeseshot!</b>',
'userinvalidcssjstitle' => '<strong>Opjepaßß:</strong> Et jitt këijn Uß_sinn med_däm Name: „<strong>$1</strong>“ — 

dängk draan, dat enne Medmaacher ëijenne Datëije förr_et Uß_sinn hann kann, un dat_di met klëijne Boochashtave 

aanfange donn, also ätva: {{ns:User}}:Name/monobook.css, un {{ns:User}}:Name/monobook.js uew. hëijshe.',
'updated'               => '(Aanjepakk)',
'note'                  => '<strong>Opjepaßß:</strong>',
'previewnote'           => '<strong>He kütt nur de Füür_Aanseesh — Ding Ännderonge sin_non_nit jeseshort!</strong>',
'session_fail_preview'  => '<strong>Schaadt: Ding Ännderonge kunnte mer su nix mėd aanfange.

De Daate fun Dinge Login-Sëschen sinn nit öhndlėsh erövver jekumme, odder ëijnfach ze alldt.

Fosöög_et jraadt norr_enß. Wänn dat widder nit flupp, dann fosöög_et enß met [[Special:Userlogout|Ußlogge]] 

un_widder_Ėnnlogge. Ävver pass_op, dat_Te Ding Änderonge do_bëij behällß! Zo_Nuud důnn_se eetß enß bëij Dir om Räshno 

affshpëijshere.</strong>',
'previewconflict'       => 'He_di Füür_Aansėsh zëijsh dä Enhalldt fum bovvere Täxx_Fëlldt. Esu wöödt_dä Atikkel 

ußsinn, wänn_De_n jäz affshpëijshere dääts.',
'session_fail_preview_html'=> '<strong>Schaadt: Ding Ännderonge kunnte mer su nix mėd aanfange.<br />De Daate fun 

Dinge Login-Sëschen sinn nit öhndlėsh erövver jekumme, odder ëijnfach ze alldt.</strong>

Dat Wikki hee hät <i>rüüh HTML</i> zojeloohße, dröm weed de Füür_Aansėsh nit jezëijsh. Domet sollß_De jeschöz wääde — 

hoffe mer — un Aanjreffe med Java_Skripp jääje Dinge Kompjuto künne_Der nix aandonn.

<strong>Fallß fö Dėsh sönß alles jood_ußsüüht, fosöög_et jraadt norr_enß. Wann dat widder nit flupp, dann fosöög_et 

enß met [[Special:Userlogout|Ußlogge]] un_widder_Ėnnlogge. Ävver pass_op, dat_Te Ding Änderonge do_bëij behällß! 

Zo_Nuud důnn_se eetß enß bëij Dir om Räshno affshpëijshere.</strong>',
'importing'             => '„$1“ am Impochtėere',
'editing'               => 'Di Sigk „$1“ änndere',
'editinguser'               => 'Di Sigk „$1“ änndere',
'editingsection'        => 'Ne Affshnedt fun dä Sigk: „$1“ änndere',
'editingcomment'        => '„$1“ Änndere (enne nöüje Affshnedd schriive)',
'editconflict'          => 'Problemshe: „$1“ dubbeld beärrbëijdt.',
'explainconflict'       => '<br />Enne anndere Medmaacher hät aan dä Sigk och jät jeänndodt, un zwa nohdämm Do en 

Änndere aanjefange häß. Jäz ham_mer dä salladt, un Do darrvs_et widdo ußzotteere.

<strong>Opjepass:</strong>

<ul><li>Dat bovverre Täxx_Fëlldt zëijsh di Sigg_esu, wi_se jäds_em Mommändt jeshpëijsherd_eß, allso med_de Ännderönge 

fun alle anndere Medmaacher, di flöcker wi Do jeshpëijshot hann.</li><li>Dat ungerre Täxx_Fëlldt zëijsh di Sigg_esu, 

wi_ß_De_se sellver zoläz zerääsh jebrasselt häß.</li></ul>

Do moßß jäz Ding Ännderonge och in dat <strong>bovvere</strong> Täxx_Fëlldt erinn brenge. Nattöörlėsh oohne dä 

Anndere ier Saache kapott ze maache.

<strong>Nuur wat em bovvere Täxx_Fëlldt shtëijt,</strong> dat weed övvernumme un affjeshpëijshot, wänn_De „<b 

style="padding:2px; background-color:#ddd; color:black">Di Sigk Affspëijshere</b>“ klix. Beß do_hen kannß_De_esu öff 

wi_De_wellß op „<b style="padding:2px; background-color:#ddd; color:black">Füür_Aansėsh Zëije</b>“ un „<b 

style="padding:2px; background-color:#ddd; color:black">De Ungerscheed zëije</b>“ klikke, öm ze prööfe, wat_Te alld 

joodß jemaat häß.

Alleß Klooh?<br /><br />',
'yourtext'              => 'Dinge Täxx',
'storedversion'         => 'De jeshpëijshote Väsjohn',
'nonunicodebrowser'     => '<strong>Opjepaßß:</strong> Dinge Brauser kann nit 

ööndlėsh met däm Unicode un singe Boochstaave ömjonn. Bess_esu_joot un 

nėmmbs_enne anndere Brauser fö hee di Sigk!',
'editingold'            => '<strong>Opjepaßß!<br />
Do beß en aahle, övverhollte, Väsjohn fun dä Sigk hee am Änndere.
Wänn_De di affshpëijshere dëijß,
wi se eß,
dann sinn all_di Ännderonge foloohre,
di zigk dämm aan dä Sigk jemaat woode sėnn.
Allso:
Beß De sesher,
watt de määhß?
</strong>',
'yourdiff'              => 'Ungerscheede',
'copyrightwarning'      => 'Ding Bëijdräsh shtonn unger de [[$2]], süch $1. Wänn De nit hann wellß, dat Dinge Täx 

ömjemoodeldt weed, un sönß wo_hen fodëijlt, donn_en hee nit shpëijshere. Me_m Affshpëijshere sääß De och zo, dadd_et 

fun Dier sellfß eß, un/oddo Do dat Rääsh häß, en hee zo forbrëijde. Wenn_t nit shtimmp, oddo Do kanns_et nit 

nohwiise, kann Dish dad_en_do Bou brenge!',
'copyrightwarning2'     => 'De Bëijdräsh en de {{SITENAME}} künne fun anndere Medmaacher ömjemoodeldt 

of_fottjeschmeßße wääde. Wänn Der dat nit rääsh eß, schriif nix. Et eß och nüüdish, dadd_et fun Dier sellfß eß, oddo 

dat Do dat Rääsh häß, et hee öffentlish wigger ze jävve. Süsh $1. Wenn_t nit shtimmp, oddo Do kanns_et nit nohwiise, 

künnt Dish dad_en_do Bou brenge!',
'longpagewarning'       => '<strong>Oppjepaßß:</strong> Dä Täxx, dä De hee jeschekk häß, dä eß <strong>$1</strong> 

Kilobyte jruuß. Mansh Brauser kütt nėt domet klooh, wänn_et mieh wi <strong>32</strong> Kilobyte sinn. Do künntß De drövver nohdängke, dat 

Dinge en klëijner Shtökshe ze_zerklope.',
'longpageerror'         => '<big><strong>Jannz Schlemme Fääler:</strong></big>

Dä Täxx, dä De hee jeschekk häß, dä eß <strong>$1</strong> Kilobyte jruuß. 

Dat sinn mieh wi <strong>$2</strong> Kilobyte. Dat künne mer nit Shpëijshere!

<strong>Maach klëijner Shtökke druß.</strong><br />',
'readonlywarning'       => '<strong>Opjepaßß:</strong> De Daate_Bangk eß jeshpächt woode, wo Do ald_am Änndere woohß. 

Däh. Jëz kannß_De Ding Änderonge nit mieh affshpëijshere. Donn se bëij Dir om Räshno faßßhallde un fosöög_et spääder 

norr_enß.',
'protectedpagewarning'  => '<strong>Opjepaßß:</strong> Di Sigk hee eß jäje Veränderonge jeschöz — wiso weed_em <span 

class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logbooch]</span> shtonn. Nuur de  

Wikki_Köbeßße künne se änndere. Bess_esu jood un halldt Desh aan de Räjelle för 

dä Fall!',
'semiprotectedpagewarning'=> '<strong>Opjepaßß:</strong> Di Sigk hee eß hallef jeshpächt, wi mer sare, dat hëijß, Do 

moß [[Special:Userlogin|aanjemälldt un ėnnjelogk]] sinn, wänn_De draan änndere wellß.',
'templatesused'         => 'De Shabloone, di fun dä Sigk hee jebruch wääde, sinn:',
'edittools'             => '<!-- Dä Täx hee zëijsj_et Wikki unger dä Täxx_Fälldt zem „Änndere/Beärbëijde“ un bëijm 

Täxx_Fälldt fum „Huhlade“. -->',
'nocreatetitle'         => 'Ėnnlogge eß nüüdėsh',
'nocreatetext'          => 'Sigge nöü aanläje eß nur möshlesh, wänn_de [[Special:Userlogin|enjelogk]] beß. Der oohne 

kannß_De ävver Sigge änndere, di ald_doo sinn.',
'cantcreateaccounttitle'=> 'Kann këijne Zojang ėnnreshde',
'cantcreateaccounttext' => 'Aanmälldunge fun Dinge IP_Addräßß [<strong>$1</strong>] senn jeshpächt. Dat hät fö_jewöönlijj_enne Jrundt. Zom Bëijshpill künnt sinn, dat 

fill_ze_fill SPAM fun däm Berëijsh fun dä Adräßße jekumme eß.',
'revhistory'            => 'De Väsjohne',
'viewpagelogs'          => 'De LogBöösher fö hee di Sigk',
'nohistory'             => 'Et jitt këijn Väsjohne fun dä Sigk.',
'revnotfound'           => 'Di Väsjohn ham_mer nit jefonge.',
'revnotfoundtext'       => '<b>Däh.</b> Di ählere Väsjohn fun dä Sigk, wo De noh froochß, eß nit do. Schadt. Luer_enß 

op di URL, di Dėsh hääjebraadt hät, di weed fokiehrt sinn, oder se iß fellëijsj_övverholldt, wëijl Ëijne di Sigk 

fottjeschmeßße hätt?',
'loadhist'              => 'Donn de Lėßß met ahle Väsohne laade',
'currentrev'            => 'Nöüßte Väsjohn',
'revisionasof'          => 'Väsjohn fum $1',
'revision-info' => 'Väsjohn fum $1; $2',
'previousrevision'      => '← De Revisjohn dö_für zëije',
'nextrevision'          => 'De Väsjohn do_noh zëije →',
'currentrevisionlink'   => 'De nöüßte Väsjohn',
'cur'                   => 'nöü',
'next'                  => 'Wiggo',
'last'                  => 'läz',
'orig'                  => 'Orrjinahl',
'histlegend'            => 'Hee kanns_De Väsjohne för_et Forjlishe ußsööke: Donn met dä Knöpp di zwëij makkeere, 

zwesche dänne De de Ungescheed jezëijsh krijje wellß, dann dröck „<b style="padding:2px; background-color:#ddd; 

color:black">Důnn de makėete Väsjohne fojlishe</b>“ bëij Dinge Taßte, oddo klick op ëijn fun dä Knöpp övver odder 

unger de Lėßß.

Äklierong: (nöü) = Fojlishe met de nöüßte Väsjohn, (läz) = Fojlishe met de Väsjohn ëijn_do_füer, <b>M</b> = en 

klëijne <b>M</b>ini_Ännderongk.',
'deletedrev'            => '[fott]',
'histfirst'             => 'Ählßte',
'histlast'              => 'Nöüßte',
'rev-deleted-comment'   => '(„Koot Zosammejefaßß, Kwälle“ ußßjeblenndt)',
'rev-deleted-user'      => '(Medmaacher_Name ußßjeblenndt)',
'rev-deleted-text-permission'=> '<div class="mw-warning plainlinks">Di Väsjohn eß fottjeschmeßße woode. Jäz kam_mer 

se nit mieh beluere. Enne Wikki_Köbeß künnt se ävver zerrög_holle. Mieh drövver, vat met däm Fottschmiiße fun dä Sigk 

jewääse eß, künnd_Er em [{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logbooch] nohlässe.</div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks">Di Väsjohn eß fottjeschmeßße woode. Jäz kam_mer se nit 

mieh beluere. Alls_enne Wikki_Köbes krėßß_De_se ävver doch ze_sinn, un küünz_e 

och zerrög_holle. Mieh drövver, vat met däm Fottschmiiße fun dä Sigk jewääse eß, künnd_Er em 

[{{FULLURL:Spezial:Log/delete|page={{PAGENAMEE}}}} Logbooch] nohlässe.</div>',
'rev-delundel'          => 'zëije/ußblännde',
'history-feed-title'    => 'Väsjohne',
'history-feed-description'=> 'Äählere Väsjohne fun dä Sigk en de {{SITENAME}}',
'history-feed-item-nocomment'=> '$1 öm $2',
'history-feed-empty'    => 'Di aanjefroocht Sigk jidd_et nit. Künnt sinn, dat se enzwesche fott_jeschmeßße oddo 

ömm_jenanndt voode eß. Kannß jo enß [[Special:Search|em Wikki sööke lohße]], öm paßßend nöüje Sigge ze finge.',
'revisiondelete'        => 'Väsjohne fottschmiiße un widdo zerrögk_holle',
'revdelete-selected'    => 'Ußßjewählte Värsjohn fun [[:$1]]:',
'revdelete-text'        => 'Dä fottjeschmeßßene Sigge ier Ennhaldt kannß_De nit mieh aanluere. Se bliive ävver en de 

Lėßß met_e Väsjohne dren.

Enne Wikki_Köbes kann de fottjeschmeßßene Krohm emmo noch aanluere un kann_en och widdo_hää_holle, ußßer wänn bëij 

dem Wikki singe Inshtallzjohn dat angersch faßßjelaat woode eß.',
'revdelete-legend'      => 'Dä öffentlijje Zojang ennschrängke, fö_di Väsjohn:',
'revdelete-hide-text'   => 'Dä Täx fun dä Väsjohn ußblännde',
'revdelete-hide-comment'=> 'Dä Ennhaldt fun „Koot Zosammejefaßß, Kwälle“ ußblännde',
'revdelete-hide-user'   => 'Däm Beärrbëijder sing IP_Addräßß oddo Medmaacher_Naame ußblännde',
'revdelete-hide-restricted'=> 'Donn dat och för de Wikki_Köbeßße esu maache wi_fö_jeede Anndere',
'revdelete-log'         => 'Bemärrkung fö_t LogBooch:',
'revdelete-submit'      => 'Op di aanjekrüzde Väsjohn aanvënnde',
'revdelete-logentry'    => 'Zojang zo de Väsjohn foänndot för [[$1]]',
'difference'            => '(Ungerscheed zwesche de Väsjohne)',
'loadingrev'            => 'ben en Väsjohn för_t Fojliishe am laade',
'lineno'                => 'Rëij $1:',
'editcurrent'           => 'Donn_de_nöüßte Vasjohn fun dämm Atikkel änndere',
'selectnewerversionfordiff'=> 'Důnn en nöüre Väsjohn för_t Fojliishe ußsööke',
'selectolderversionfordiff'=> 'Důnn en äälere Väsjohn för_t Fojliishe ußsööke',
'compareselectedversions'=> 'Důnn de makėete Väsjohne fojlishe',
'searchresults'         => 'Wat bëijm Sööke errußkohm',
'searchresulttext'      => 'Luer op de Sigk övver_et [[{{ns:project}}:Sööke en de {{SITENAME}}|Sööke en de 

{{SITENAME}}]] noh, wänn de mieh drövver weßße wellß, wi_mer en de {{SITENAME}} jät fingk.',
'searchsubtitle'        => 'För Ding Frooch noh „[[:$1]]“.',
'searchsubtitleinvalid' => 'För Ding Frooch noh „$1“.',
'badquery'              => 'Fokierte Aanfroch fö_t Sööke',
'badquerytext'          => 'För Ding Frooch förr_et Sööke hät_dat nix jebraat.
Zem Bëijshpill künnd_et sinn, dat_De noh ennem jannz koote Woot jefrooch häß — kööter wi fier Boochshtaven künne_mer 

ëijnfach nit. Odder Do häß_Desch fotipp, un noh „Kölle am am Rhing“ sööke lohße. Unn_et künnt sinn, dat mer Ding 

Schriifwiiß nit en de Daate_Bangk hann. Wannt jëijt, dann donn do_för jlish_en Ömlëijdung enjävve!',
'matchtotals'           => '„$1“ küdd_en <strong>$2</strong> Övverschreffte un em Täx fun <strong>$3</strong> 

Atikkelle für.',
'noexactmatch'          => 'Mer han këijn Sigk met jenou däm Name „<strong>$1</strong>“ jefonge. Do kannß_ße 

[[:$1|aanlääje]], wänn_De wellß.',
'titlematches'          => 'Paßßende Övverschreffte',
'notitlematches'        => 'Këij_paßßende Övverschreffte',
'textmatches'           => 'Sigge met_däm Täx',
'notextmatches'         => 'Këij Sigk met_däm Täx',
'prevn'                 => 'de $1 do_für zëije',
'nextn'                 => 'de nääkßte $1 zëije',
'viewprevnext'          => 'Bläddere: ($1) ($2) ($3).',
'showingresults'        => 'Unge weede beß <strong>$1</strong> fun de jefungene Enndrääsh jezëijsch,
fun de Nommer <strong>$2</strong> aff.',
'showingresultsnum'     => 'Unge sinn <strong>$3</strong> fun de jefungene Enndrääsh opjelėßß,
fun de Nommer <strong>$2</strong> aff.',
'nonefound'             => '<strong>Opjepaßß:</strong> Wänn bëijm Söhke nix eruß kütt, do kann dat draan lijje, dat 

mer esu jannz jewöönlijje Wööt, wi „hätt“, „allso“, „wääde“, un „senn“, uew. jaa__nid_esu en_de Daate_Bank dren_hann, 

dat_se jefonge wääde künnte.',
'powersearch'           => 'Söhke',
'powersearchtext'       => 'Söök in de Appachtemengß:<br />$1<br />$2 Zëijsh Ömëijdunge<br />Söhk noh $3 $9',
'searchdisabled'        => 'Dat Sööke he op de {{SITENAME}} eß em Mommänndt affjeschalldt.
Dat weed fun de ßööver ald_enß jemaat, domet_te Laßß op inne nit_ze jrůůß_weedt,
un winnishßtenß de Nommaalle Sigge_Oproofe flöck_jenooch jonn.

Ühr künnd_esu lang övver en Söök_Maschiin fun ußßerhallf ėmmer noch
Sigge op de {{SITENAME}} finge.
Ed_eß nit_jesaat,
dat denne ier Daate top_aktowäll sinn,
ävve_ed_eß_bäßßo wi jaa_nix.',
'blanknamespace'        => '(Atikkelle)',
'preferences'           => 'ming Ëijnshtellunge',
'prefsnologin'          => 'Nėd_Ėnnjelogg',
'prefsnologintext'      => 'Do mööds_alld [[Special:Userlogin|ennjelogg]] sinn, öm Ding Ėnnshtellunge ze ännderre.',
'prefsreset'            => 'Dė Ëijnshtellunge woodte jäz op Shtanndadt zerrögk_jesaz.',
'qbsettings'            => '„Flöcke Lėngkß“',
'changepassword'        => 'Paßßwoodt Änndere',
'skin'                  => 'Et Uß_Sinn',
'math'                  => 'Mattematisch Forrmelle',
'dateformat'            => 'Em Dattum sing Fommaat',
'datedefault'           => 'Ejaal — këijn Füürliėbe',
'datetime'              => 'Dattum un Uur_Zigge',
'math_failure'          => 'Fääler fum Paaser',
'math_unknown_error'    => 'Fääler, dä_mmer nit känne',
'math_unknown_function' => 'en Funkzjohn, di_mmer nit känne',
'math_lexing_error'     => 'Fääler bëijm Lëxing',
'math_syntax_error'     => 'Fääler en de Sünntax',
'math_image_error'      => 'De Ömwandlung noh PNG eß donëvve jejange. Donn enß noh de reshtijje Ėnnshtallazjoohn 

luere bëij <i>latex</i>, <i>dvips</i>, <i>gs</i>, un <i>convert</i>. Odder saar_et ennem ßööver_Admin, odder_ennem 

Wikki_Köbes.',
'math_bad_tmpdir'       => 'Dat Zwesche_Fozëijshniß fö de mattematėshe Forrmelle lööt sėsh nit aanlääje oddo nix 

erinn_schriive, Dat eß Dißß. Saar_et ennem Wikki_Köbes odder ennem 

ßööver_Minsch.',
'math_bad_output'       => 'Dat Fozëijshniß fö de mattematėshe Forrmelle lööt sėsh nit aanlääje oddo nix 

erinn_schriive, Dat eß Dißß. Saar_et ennem Wikki_Köbes odder ennem 

ßööver_Minsch.',
'math_notexvc'          => 'Dat Projamm <code>texvc</code> ham_mer nit jefonge. Saar_et ennem 

Wikki_Köbes, ennem ßööver_Minsch, odder luer_enß en de 

<code>math/README</code>.',
'prefs-personal'        => 'De Ëijnshtellonge',
'prefs-rc'              => 'Nöüßte Ännderunge',
'prefs-watchlist'       => 'De Oppaßß_Lėßß',
'prefs-watchlist-days'  => 'Aanzal Dare fö_en minger Oppaßß_Lėßß aan_ze_zëije:',
'prefs-watchlist-edits' => 'Aanzal Änderonge fö_en minger forjrüüßorte Oppaßß_Lėßß aan_ze_zëije:',
'prefs-misc'            => 'Sönß',
'saveprefs'             => 'Faßßhallde',
'resetprefs'            => 'Zerrögk_Säzze',
'oldpassword'           => 'Et aahle Paßßwordt:',
'newpassword'           => 'Nöü Paßßwoodt:',
'retypenew'             => 'Norr_enß dat neue Paßßwoodt:',
'textboxsize'           => 'Bëijm Beärrbëijde',
'rows'                  => 'Rëije:',
'columns'               => 'Shpallde:',
'searchresultshead'     => 'Bëijm Sööke',
'resultsperpage'        => 'Zëijsh Träfo pro Sigk:',
'contextlines'          => 'Rëije fö_jeede Träfor:',
'contextchars'          => 'Zëijshe uß de Ömjävung, pro Rëij:',
'stubthreshold'         => 'Aanzal Zëijshe fun_woh avv_en_Sigk alls_enne Atikkel zälldt:',
'recentchangescount'    => 'Enndrääsh en de Lėßß_met_de „Nöüßte_Ännderonge“:',
'savedprefs'            => 'Ding Ėnnshtellunge sinn jäz jeseshot.',
'timezonelegend'        => 'Zigk_Zoone Ungerscheed',
'timezonetext'          => 'Dat sin_de Shtunnde un Menutte zwesche de Zigk op de Uure bëij Dir am Oot un däm ßööver, dä med UTC leuf.',
'localtime'             => 'De Zigg_op Dingem Kompjutor:',
'timezoneoffset'        => 'Dä Ungerscheed ¹ eß:',
'servertime'            => 'De Ur_Zigg_öm ßööver eß jäz:',
'guesstimezone'         => 'Fingk ed_eruß övver dä Brauser',
'allowemail'            => 'e-mail fun anndere Medmaacher zo_loohße',
'defaultns'             => 'Donn shtandad_mäßėsh en hee dä Appachtemengß sööke:',
'default'               => 'Shtanndat',
'files'                 => 'Dateije',
'userrights-lookup-user'=> 'Medmaacher Jroppe fowallde',
'userrights-user-editname'=> 'Medmaacher_Name: <!-- -->',
'editusergroup'         => 'Däm Medmaacher sing Jroppe Rääshte beärrbëijde',
'userrights-editusergroup'=> 'Medmaacher_Jroppe aanpaßße',
'saveusergroups'        => 'Medmaacher_Jroppe affshpëijshere',
'userrights-groupsmember'=> 'Eß en_de Medmaacher_Jroppe:<br />',
'userrights-groupsavailable'=> 'Eß nit en de Medmaacher_Jroppe:<br />',
'userrights-groupshelp' => 'Söök de Jroppe uß, wo dä Medmaacher bëij kumme sull oddo druss_eruß sull. Jroppe, di De 

hee nid_ußsöökß, bliive, wi_se_sėnn. Dat Ußsööke kannß_De bëij de miihßte Brausere met \'\'\'Ctrl + Lenkß_Klikke\'\'\' / \'\'\'Strg + Lenkß_Klikke\'\'\' maache.',
'group'                 => 'Jropp:',
'group-bot'             => 'Botß',
'group-sysop'           => 'Wikki_Köbeßße',
'group-bureaucrat'      => 'BürroKraade',
'group-all'             => '(all)',
'group-bot-member'      => 'Bot',
'group-sysop-member'    => 'Wikki_Köbes',
'group-bureaucrat-member'=> 'Bürrokraat',
'grouppage-bot'         => '{{ns:project}}:Botß',
'grouppage-sysop'       => '{{ns:project}}:Wikki_Köbes',
'grouppage-bureaucrat'  => '{{ns:project}}:Bürrokraat',
'changes'               => 'Ännderonge',
'recentchanges'         => 'Nöüßte_Ännderonge',
'recentchangestext'     => 'Op dä Sigk hee sinn de nöüßte Änderonge aam Wikki opjelėßß.',
'rcnote'                => 'Hee sinn de läzde <strong>$1</strong> Änderonge uß de läzde <strong>$2</strong> Daare fum 

$3 aan.',
'rcnotefrom'            => 'Hee sinn beß_op <strong>$1</strong> Änderonge zigk <strong>$2</strong> opjelėßß.',
'rclistfrom'            => 'Zëijsh de nöüje Ännderonge fum $1 aff',
'rcshowhideminor'       => '$1 klëijn minni_Ännderonge',
'rcshowhidebots'        => '$1 de Botß ier Ännderonge',
'rcshowhideliu'         => '$1 de aanjemälldte Medmaacher ier Ännderonge',
'rcshowhideanons'       => '$1 de namenlose Medmaacher ier Ännderonge',
'rcshowhidepatr'        => '$1 de aanjeluerte Ännderonge',
'rcshowhidemine'        => '$1 ming ëijen Ännderonge',
'rclinks'               => 'Zëijsh de läzde | $1 | Ännderonge uß de läzde | $2 | Daare, un donn | $3 |',
'diff'                  => 'Ungerscheed',
'hist'                  => 'Väsjohne',
'hide'                  => 'Ußblände:',
'show'                  => 'Zëije:',
'minoreditletter'       => 'M',
'newpageletter'         => 'N',
'boteditletter'         => 'B',
'sectionlink'           => '→',
'number_of_watching_users_pageview'=> '[$1 Oppaßßer]',
'rc_categories'         => 'Nur di Saachjroppe (med „|“ dozwesche):',
'rc_categories_any'     => 'All, wat mer hann',
'upload'                => 'Daate huh_laade',
'uploadbtn'             => 'Huh_Laade!',
'reupload'              => 'Norr_enß huh_laade',
'reuploaddesc'          => 'Zerrögk noh de Sigk zem Huh_Laade.',
'uploadnologin'         => 'Nėd_Ėnnjelogg',
'uploadnologintext'     => 'Do möötds_alld [[Special:Userlogin|ennjelogg]] sinn, öm Daate huh_ze_lade.',
'upload_directory_read_only'=> '<b>Doof:</b> En dat Fozëijshnißß <code>$1</code> fö_Dattëije drėn huh_ze_laade, do 

kann dat Web_ßööver_Projramm nix errinnschriive.',
'uploaderror'           => 'Fääler bem Huh_Laade',
'uploadtext'            => '<div dir="ltr">Met dämm Formular unge kannß_de Bellder oddo annder Daate huh_laade. Do 

kannß dann Ding Werrək diräg enbinge, en dä Aate:<ul style="list-style:none outside none; 

list-style-position:outside; list-style-image:none; list-style-type:none"><li style="list-style:none outside none; 

list-style-position:outside; list-style-image:none; 

list-style-type:none"><code>\'\'\'[[{{NS:Image}}:\'\'\'\'\'Belldshe\'\'\'\'\'.jpg]]\'\'\'</code></li><li 

style="list-style:none outside none; list-style-position:outside; list-style-image:none; 

list-style-type:none"><code>\'\'\'[[{{NS:Image}}:\'\'\'\'\'Esu_süühd_dat_uß\'\'\'\'\'.png | \'\'\'\'\'enne Täx, dä di 

Brausere zëije, di këij Bellder künne\'\'\'\'\']]\'\'\'</code></li><li style="list-style:none outside none; 

list-style-position:outside; list-style-image:none; 

list-style-type:none"><code>\'\'\'[[{{NS:Media}}:\'\'\'\'\'Su_hüert_sesh_dat_aan\'\'\'\'\'.ogg]]\'\'\'</code></li></u

l>
Ußßfüerlish met alle Möshlishkëijte finkß_de dat bëij de Hülp.

Wänn De jäz entschloßße beß, dat De et hee huh_laade wellß:
* Aanluere, wat mer he en de {{SITENAME}} ald hann, kannß De en unß [[Special:Imagelist|Bellder_Leßß]].
* Wenn De jät söhke wellß, eetß enß noh_luere wellß, wat alld huhjelaade, oddo fellëijsh widdo fottjeschmeßße wood, 

dat shtëijd_em [[Special:Log/upload|Logbooch fum Huh_laade]].

Esu, un jäz loß jonn:</div>
== <span dir="ltr">Date en de {{SITENAME}} lade</span> ==',
'uploadlog'             => 'LogBooch fum Dattëije_Huh_Laade',
'uploadlogpage'         => 'Logbooch med_de huh_jelaadene Datëije',
'uploadlogpagetext'     => 'Hee sinn de nöüßte huh_jelaadenne Datëije opjelėßß un wä dat jedonn hät.',
'filename'              => 'Name fun dä Dattëij',
'filedesc'              => 'Beschriivungß_Täxx un Zosammefaßßung',
'fileuploadsummary'     => 'Beschriivungß_Täxx un Zosammefaßßung:',
'filestatus'            => 'Urhävver_Räächß_Shtattuß',
'filesource'            => 'Qwäll',
'copyrightpage'         => '{{ns:project}}:Lizänz',
'copyrightpagename'     => 'Lizänz',
'uploadedfiles'         => 'Huh_jeladenne Dattëije',
'ignorewarning'         => 'Warnung övverjonn, un Dattëij trozdämm affshpëijsherre.',
'ignorewarnings'        => 'Alle Warnunge övverjonn',
'minlength'             => 'De Name fun_de Dattëije künne_nit kööto_wi_drëij Boochshtawe sinn.',
'illegalfilename'       => 'Schaadt:
<br />
En däm Name fun dä Dattëij sin Zëijshe enthallde,
di mer en Tittelle fun Sigge nit bruche kann.
<br />
Söök Der shtatt „$1“ jäd_andoß uß,
un dann mußß_de dat Dinge norr_enß huh_laade.',
'badfilename'           => 'Dė Dattëij eß en „$1“ ömjedeuf.',
'badfiletype'           => '„.$1“ iß këijn fun de Fomatte fun Bėllder, wo mer jäz jät met aanfange künnte.',
'largefile'             => 'Di Datëij eß <strong>$2</strong> Byte jruß. Datëije Huh_ze_Laade, di jrüüßer wi 

<strong>$1</strong> Byte sind, do_för dům_mer affroode.',
'largefileserver'       => 'Di Datëij eß ze jruuß. Jrüüßo_wi_däm ßööver sing Ennshtëllung ällaup.',
'emptyfile'             => 'Wat De hee jäz huh_jelaade häßß, hatt kenn Daate drenn jehatt. Künnt sinn, dat_De Dėsh 

fordonn häßß, un dä Naame woh falləsch jeschrėvve. Luer_enß ov_De werreklesch <strong>di</strong> Dattëij hee 

huhlaade wellß.',
'fileexists'            => 'Et jidd_ald en Datëij med_dämm Name. Wänn_De op „<span style="padding:2px; 

background-color:#ddd; color:black">Dattëij affshpëijshere</span>“ klix, weed se äsäzz. Bess_esu joot, un_luer_Der $1 

aan, wänn_De nit 100% sescher beß.',
'fileexists-forbidden'  => 'Et jidd_ald en Datëij med_dämm Name. Jangk zerrögg_un laad_se unger_ennem anndere Naame 

huh. [[{{ns:Image}}:$1|thumb|center|$1]]',
'fileexists-shared-forbidden'=> 'Et jidd_ald en Datëij med_dämm Name em jemëijnsame Shpëijsho. Jangk zerrögg_un 

laad_se unger_ennem anndere Naame huh. [[{{ns:Image}}:$1|thumb|center|$1]]',
'successfulupload'      => 'Et Huh_laade hät jeflupp',
'fileuploaded'          => 'Di Dattëij „$1“ eß jäz huh_jelaade.
Jangk op di Sigk met dä Dattëij ier Beshriivung un doh draach alles enn wat De övver se wëijß.
Wo se her kohm, Wää se jemaat hädd_un wann, un_vat_De_Dėsch sönß noch draan entsėnne kannß.
Do küßß_De hen övver dä Lėngk: $2

Wänn dadd_e_Belldt wooh, do kannß_De met:
:<code><nowiki>[[{{NS:Image}}:$1|thumb|Täxx för onger dat Belld ze donn]]</code>
e Breefmarreke_Belldsche op dä Sigk moole lohße.',
'uploadwarning'         => 'Warrnung bëijm Huh_laade',
'savefile'              => 'Dattëij affshpëijshere',
'uploadedimage'         => 'hät huhjelade: „[[$1]]“',
'uploaddisabled'        => 'Huh_Lade jeshpächt',
'uploaddisabledtext'    => 'Et Huh_Lade eß jeshpächt he en dämm Wikki.',
'uploadscripted'        => 'En dä Datëij eß HTML dren oddo Kood fun_ennem 
Skripp, dä künnt Dinge Brauser en do fallsche Hallß krijje un ußföere.',
'uploadcorrupt'         => 'Schaad.
<br />
Di Dattëij iß kapott, hädd_en fokiehjəte File_Name Ëxtensjen, odder ööhnds_enne anndere Drißß eß paßßėet.
<br />
<br />
Luer_enß noh_dä Dattëij, un dann moßß_de_t norr_enß fosöhke.',
'uploadvirus'           => 'Esu enne Drißß:
<br />
En dä Dattëij shtish e Kompjuto_Viruß!
<br />
De Ëijnzelhäijte: $1',
'sourcefilename'        => 'Dattëij zem huh_laade',
'destfilename'          => 'Unger dämm Dattëijname affshpëijshere',
'filewasdeleted'        => 'Unger dämm Name wood ald_enß en Datëij huh_jelaade. Di eß enzwesche ääver widdo 
fottjeschmeßße woode. Luer leever eeds_enß en_et $1 iih dat De se dann affshpëijsherre dëijß.',
'license'               => 'Lizzänz',
'nolicense'             => 'Nix üßßjesöök',
'upload_source_url'     => ' (reshtijje öffentlijje URL)',
'imagelist'             => 'Bellder, Tööhn, uew. (all)',
'imagelisttext'         => 'Hee küdd_en Lėßß fun <strong>$1</strong> Dattëij{{PLURAL:$1||e}}, zotteet $2.',
'imagelistforuser'      => 'Hee süühß De nuur de Bėllder, di dä Medmaacher „$1“ huh_jelaade hätt.',
'getimagelist'          => 'ben de Lėßß met de Datëij_Name am laade',
'ilsubmit'              => 'Söök',
'showlast'              => 'Zëijsh de läzde | $1 | Dattëije, zotteed $2.',
'byname'                => 'noh_m Name',
'bydate'                => 'noh_m Dattum',
'bysize'                => 'noh de Dattëij_Jrüüße',
'imgdelete'             => 'fott!',
'imgdesc'               => 'täxx',
'imgfile'               => 'Dattëij',
'imglegend'             => 'Legende: (täxx) = ännder odder zëijsh de Beschrivungß_Täxx för di Dattëij.',
'imghistory'            => 'Väsjohne',
'revertimg'             => 'retuur',
'deleteimg'             => 'Fottschmiiße',
'deleteimgcompletely'   => 'Alle Väsjohne fun dä Dattëij fottschmiiße',
'imghistlegend'         => 'Legende:
(nöü) = dat iß de nöüßte Väsjohn —
(fott!) = schmiiß di aale Väsjohn fott! —
(retuur) = jangk zeröck op di aale Väsjohn —
Op_et Dattum klikke = zëijsh di Väsjohn fun dohmols aan.',
'imagelinks'            => 'Lėngkß',
'linkstoimage'          => 'He kumme de Sigge, di op di Dattëij lingke donn:',
'nolinkstoimage'        => 'Nix lėngk op hee_di Dattëij.',
'sharedupload'          => 'Di Dattëij eß esu parat jelaat, dat se en divärse, ungesheedlijje Projäkkte jebruch wääde 

kann.',
'shareduploadwiki'      => 'Mieh Ėnnfommazjohne fingkß_De hee: $1.',
'shareduploadwiki-linktext'=> 'Hee eß en Dattëij beschrėvve',
'noimage'               => 'Mer han këij_Dattëij med dämm Naame, kannz_E ävver $1.',
'noimage-linktext'      => 'Kannz_E huh_laade!',
'uploadnewversion-linktext'=> 'Donn en nöüje Väsjohn fun dä Dattëij huh_laade',
'imagelist_date'        => 'Dattum',
'imagelist_name'        => 'Name',
'imagelist_user'        => 'Medmaacher',
'imagelist_size'        => 'Byte',
'imagelist_description' => 'Description',
'imagelist_search_for'  => 'Search for image name:',
'mimesearch'            => 'Bellder, Tööhn, uew. övver ier MIME_Tüppe Sööhke',
'mimetype'              => 'MIME-Tüp:',
'download'              => 'Erunge_Laade',
'unwatchedpages'        => 'Sigge, wo Këijne dob_oppaßß',
'listredirects'         => 'Ömlëijdunge',
'unusedtemplates'       => 'Schabloone oddo Boushtëijn, di nit jebruch wääde',
'unusedtemplatestext'   => 'Hee sinn all di Schabloone opjelëßß, di em Appachtemeng „Schabbloon“ sinn, di nidd_en 

annder Sigge ennjeföösh wääde. Iih De jät dofun fottschmiiß, dängk draan, se künnte och obb_en annder Aat jebruch 

wääde, un luer Der di annder Lėngkß aan!',
'unusedtemplateswlh'    => 'annder Lėngkß',
'randomredirect'        => 'Zofällije Ömlëijdung',
'statistics'            => 'Shtatißtikke',
'sitestats'             => 'Shtatißtikke övver de {{SITENAME}}',
'userstats'             => 'Shtatißtikke övver de Medmaacher',
'sitestatstext'         => '* Et jidd_en_ättwa <strong>$2</strong> rėshtijje Atikkelle hee.

* En de Daatebangk sinn_er ävvo <strong>$1</strong> Sigge, aan dänne beß jäz_zosamme <strong>$4</strong> mool jät 

jeänndort woode eß.  Em_Shnett woote allso <strong>$5</strong> Ännderonge pro Sigk jemaat. <br /><small> (Do sinn 

ävvo de Klaaf_Sigge medjezalldt, de Sigge övver de {{SITENAME}}, un ußßodämm jeede klëijne Fuzz_un_Shtümpshenß_Sigk, 

Ömlëijdunge, Shabloone, Saachjroppe, un anndor Zeush, wat mer nit jood alls_enne Atikkel zälle kann)</small>

* <strong>$8</strong> Bellder, Töön, un_esu_n äänlijje Daate woodte ald huhjelade.

* Et {{PLURAL:$7|eß noch <strong>ëijn</strong> Oppjaf|sin_noch <strong>$7</strong> Oppjave|eß <strong>këijn</strong> 

Oppjaf mieh}} en_de_Lėßß.

* <strong>$3</strong> mool wood_en Sigk hee affjeroofe, dat sinn <strong>$6</strong> Affroofe pro Sigk.',
'userstatstext'         => '* <strong>$1</strong> Medmaacher han sėsh beß jëz aanjemelldt.
* <strong>$2</strong> do_fun sinn $5, dat sin_ner <strong>$4%</strong>.',
'statistics-mostpopular'=> 'De miihz beluerte Sigge',
'disambiguations'       => '„(Watt ėßß datt?)“-Sigge',
'disambiguationspage'   => 'Template:Disambig',
'disambiguationstext'   => 'De Sigge,
di hee_noh oppjelėßß wääde,hann Lėngkß op „(Watt ėßß datt?)“-Sigge.

Allß „(Watt ėßß datt?)“-Sigge wääde all_di jezälldt, di_di_Schabloon <strong>„$1“</strong> bruche.

Fill_fun_dänne sůllte_wůll bëßßer obb_en Sigk_lėngke,
wo tiräg_de rishtijje Ėnhallde drop shtonn.
Ävver nit unnbedėngk jeede, et küdd_op dä Lėngk drob_aan.

Lėngkß uß annder Appachtemengß wääde he nit jezëijsh.',
'doubleredirects'       => 'Ömlëijdunge op Ömlëijdunge (Dubbel Ömlëijdunge sin fokiert)',
'doubleredirectstext'   => 'Dubbel Ömlëijdunge sin ėmmer fokiert, wëijl dem Wikki sing ßoffwäer de eezte Ömlëijdung 

follesh, dė zvëijte Ömlëijdung ävver dann aanzëije dëijt — un dat well mer jo nommall nit hann.

Hee fingks De en jeede Rëij enne Lingg_op de iertßte un de zvëijte Ömlëijdung, don_noh enne Lėngg_op_di Sigk, wo de 

zvëijte Ömlëijdung hėn jëijdt. Fö_jewöönlish eß dat dann och de rishtijje Sigk, wo de iertßte Ömlëijdunge ald hėn 

jonn sůllt.

Met däm „(Änndere)“-Lingk kannz_E Di eetßte Sigk tirëgg_aanpakke. Tipp: Merrek_Der_dat Lämma — de Övverschreff — 

fun_dä drette Sigk do_föer.',
'brokenredirects'       => 'Ömlëijdunge di inn_et Leere jonn (kappott oddo_op Fürat aanjelaat)',
'brokenredirectstext'   => 'Di Ömlëijdunge hee jonn op Sigge, di mer
[[#ast|<small>noch\'\'\'<sup>*</sup>?\'\'\'</small>]]
jaa nit hann.

<small id="ast">\'\'\'<sup>*</sup>?\'\'\' Di künnte op Füeraat aanjelaat sinn.
Di allso joot ußsinn,
un woh_di Sigge woh_se drop zëije,
spääder vall noch_kumme weede,
di sullt mer behallde.</small>',
'nbytes'                => '$1 Byte',
'ncategories'           => '{{PLURAL:$1| eijn Saachjropp | $1 Saachjroppe }}',
'nlinks'                => '{{PLURAL:$1|ëijne Lėngk|$1 Lėngkß}}',
'nmembers'              => 'met {{PLURAL:$1|ëijn Sigk|$1 Sigge}} dren',
'nrevisions'            => '{{PLURAL:$1|ëijn Ännderong|$1 Ännderonge}}',
'nviews'                => '{{PLURAL:$1|1 Affroof|$1 Affroofe}}',
'lonelypages'           => 'Sigge wo nix drop lingk',
'uncategorizedpages'    => 'Sigge di in këij Saachjropp senn',
'uncategorizedcategories'=> 'Saachjroppe di sellvs_in këijn Saachjroppe senn',
'uncategorizedimages'   => 'Bellder, Tööhn, uew. di en këijn Saachjroppe dren sinn',
'unusedcategories'      => 'Saachjroppe med nix dren',
'unusedimages'          => 'Bellder, Tööhn, uew. di nit en Sigge dren_shtäshe',
'popularpages'          => 'Sigge, di öff affjeroofe wääde',
'wantedcategories'      => 'Saachjroppe di_mer non_nit hann, di noch_jebruch wääde',
'wantedpages'           => 'Sigge di_mer non_nit hann, di noch_jebruch wääde',
'mostlinked'            => 'Sigge med_e miehßte Lingkß drop',
'mostlinkedcategories'  => 'Saachjroppe med_e miehßte Lingkß drop',
'mostcategories'        => 'Atikkelle met_e miehßte Saachjroppe',
'mostimages'            => 'Bellder, Tööhn, uew. met_e miehßte Lingkß drop',
'mostrevisions'         => 'Atikkelle met_e miehßte Änderonge',
'allpages'              => 'All Sigge',
'prefixindex'           => 'All Sigge, di dänne ier Name medd_ennem beshtemmpte Woot oddo Täx aanfange dëijdt',
'randompage'            => 'Zofällije Sigk',
'shortpages'            => 'Sigge zoteet fun koot noh lang',
'longpages'             => 'Sigge zotėet fun Lang noh Koot',
'deadendpages'          => 'Sigge oohne Lėngkß dren',
'listusers'             => 'Medmaacher',
'specialpages'          => 'Söndersigge',
'spheading'             => 'Södersigge för all Medmaacher',
'restrictedpheading'    => 'Söndersigge med beshrängkte Zojangsrääshte',
'recentchangeslinked'   => 'Folingg_Ännderonge',
'rclsub'                => '(aan Sigge, noh dänne de Sigk: „$1“ hen lėngk)',
'newpages'              => 'Nöü Sigge',
'newpages-username'     => 'Medmaacher_Naam:',
'ancientpages'          => 'Sigge zoteet fun Ahl noh Nöü',
'intl'                  => 'Ingerwikki _Lėngkß',
'move'                  => 'Ömnänne',
'movethispage'          => 'Di Sigk Ömnänne',
'unusedimagestext'      => '<p><strong>Opjepaßß:</strong> Annder Websigge künnte emmer noch di Dattëije hee tirrägk 

për URL aanshpräshe. Su künnd_et sinn, dadd_en 

Dattëij hee en de Lėßß shtëijdt, ävver doch jebruch weedt. Ußßerdämm, vinnishßtens bëij nöüe Dattëije, künnd sinn, 

dat_se non_nit enn_ennem Attikkel enjebout sinn, wëijl_noch Ëijne draan am brasselle eß.</p>',
'unusedcategoriestext'  => 'Di Saachjroppe hee senn ennjereshdt, ävver jäds_em Mommänndt, eß këijne Atikkel un 

këijnolëij Saachjropp dren ze fėnge.',
'booksources'           => 'Böösher',
'categoriespagetext'    => 'Dat sin_de Saachjroppe fun däm Wikki hee.',
'data'                  => 'Daate',
'userrights'            => 'Medmaacher ier Rääshte fowallde',
'groups'                => 'Jroppe fun Medmaacher',
'booksourcetext'        => 'Hee noh küdd_en Lėßß met Websigge,
wo mir fun de {{SITENAME}} nix wigger med ze donn hänn,
wo mer jät övver Böösher erfaare
un zom Dëijl och Böösher koufe kann.
Doför moßß De Desh mannshmool allodengs eetß ennß aanmällde,
wat Koßte und anddere Jefaare met sesh brenge künndt.
Wo_t jëijdt, jonn di
Lengkß hee tirrägg_op dat Booch,
wadd_Er am Sööke sidt.',
'isbn'                  => 'ISBN',
'alphaindexline'        => '$1 … $2',
'version'               => 'Väsjohn fun de Wikki_ßoffwäer zëije',
'log'                   => 'Logböösher ier Oppzëijshnonge (all)',
'alllogstext'           => 'Dat hee es en jesammdte Lėßß uß all_dä LogBöösher för_et [[Special:Log/block|Medmaacher 

oddo IP_Adräßß_Shpärre]], et [[Special:Log/protect|Sigge_Shpärre]], [[Special:Log/delete|et Sigge_Fottschmiiße]], et 

[[Special:Log/move|Sigge_Ömnänne]], et [[Special:Log/renameuser|Medmaacher_Ömnänne]], dor 

[[Special:Log/newusers|nöüje Medmaacher ier Aanmälldunge]], et [[Special:Log/upload|Daate Huhlaade]], 

[[Special:Log/rights|de Bürro_Kraade iere Kroohm]], unn_de [[Special:Log/makebot|Botß ier Shtattuß_Ännderonge]].

Dä_LogBöösher iere Enhhalldt kam_mer all noh_de Aat, de Medmaacher, oddo de Sigge ier Naame, unn_esu, ëijnzel zottėet 

aanluere.',
'logempty'              => '<i>Mer han këijn paßßende Enndrääsh en däm Logbooch.</i>',
# Special:Allpages
'nextpage'              => 'De näkßte Sigk: „$1“',
'allpagesfrom'          => 'Sigge aanzëije aff dämm Naame:',
'allarticles'           => 'All Atikkelle',
'allinnamespace'        => 'All Sigge (Em Appachtemeng „$1“)',
'allnotinnamespace'     => 'All Sigge (ußßer_em Appachtemeng "$1")',
'allpagesprev'          => 'Zerrögk',
'allpagesnext'          => 'Nääkß',
'allpagessubmit'        => 'Loß Jonn!',
'allpagesprefix'        => 'Sigge zëije, wo dä Naame aanfängk med:',
'allpagesbadtitle'      => 'Dä Sigge_Name eß nit ze bruche. Dä hädd_e Köözel fö_n Shprooch oddo_fö_ne 

Ingerwikki_Lėngk am Aanfang, odder_et küdd_e Zëijshe dren für, wat en Sigge_Name nit jëijt, fellëijsh och mieh wi 

ëijnß fun all_dämm op ëijmohl.',
# Special:Listusers
'listusersfrom'         => 'Zëijsh de Medmaacher fun:',
# e-mail this user
'mailnologin'           => 'Do beß nit ennjelogk.',
'mailnologintext'       => 'Do mööds_alld aanjemäldt un [[Special:Userlogin|ennjelogg]] sinn, un en joode e-mail 

Adräßß en Dinge [[Special:Preferences|ming Ëijnshtellunge]] shtonn hann, öm_men e-mail aan anndere Medmaacher ze 

schekke.',
'emailuser'             => 'E-mail aan dä Medmaacher',
'emailpage'             => 'E-mail aan enne Medmaacher',
'emailpagetext'         => 'Wänn dä Medmaacher en E-mail Adräßß aanjejovve hätt, en singe Ëijnshtellunge, un di 

dëijd_et och, dann kannß_De me_däm Fomulaa hee unge, en ëijnzellne E-mail aan dää Medmaacher schekke. Ding E-mail 

Adräßß, di De en Dinge ëijene Ëijnshtellunge aanjejovve häß, di weed allß de Affsändo Adräßß en di E-mail 

ennjedraare. Domet kann, wä di E-mail kritt, drop antwoote, un di Antwoot jëij_tirägg_aan Dish.

Alleßß klooh?',
'usermailererror'       => 'Dat e-mail-Objägk 

joov_ennen_Fääler uß:',
'defemailsubject'       => 'E-mail övver de {{SITENAME}}.',
'noemailtitle'          => 'Këijn e-mail Addräßß',
'noemailtext'           => 'Dä Medmaacher hät këijn e-mail Addräßß ėnnjedraare, oddo hä well këijn e-mail krijje.',
'emailfrom'             => 'Fun',
'emailto'               => 'Aan',
'emailsubject'          => 'Övver',
'emailmessage'          => 'Dä Täxx',
'emailsend'             => 'Affschekke',
'emailsent'             => 'E-mail eß ungerwäähß',
'emailsenttext'         => 'Ding e-mail eß jäz loßßjeshekk woode.',
'watchlist'             => 'ming Op_paßß_Lėßß',
'watchlistfor'          => '(för <strong>$1</strong>)',
'nowatchlist'           => 'En Dinger Oppaßß_Lėßß eß nix dren.',
'watchlistanontext'     => 'Do moß $1, domet de en Ding Oppaßß_Lėßß errinnluere kannß, odder jät draan änndere.',
'watchlistcount'        => '<strong>En Dinger Oppaßß_Lėßß {{PLURAL:$1|eß ëijne Ėnndrach|sinn_er $1 Ėnndrääsh sinn|eß 

këijne Ėnndrach}} dren, de Klaaf_Sigge medjezalldt.</strong>',
'clearwatchlist'        => 'Dė Oppaßß_Lėßß fottschmiiße',
'watchlistcleartext'    => 'Beß_De sesher, dat De Ding jannze Oppaßß_Lėßß fottschmiiße wellß?',
'watchlistclearbutton'  => 'De jannze Oppaßß_Lėßß fott_schmiiße',
'watchlistcleardone'    => 'Ding Oppaßß_Lėßß wood fottjeschmeßße. {{PLURAL:$1|Dä Ėnndrach eß|De <strong>$1</strong> 

Ėnndrääsh sinn}} bëijm Döüvel.',
'watchnologin'          => 'Nėd_Ėnnjelogg',
'watchnologintext'      => 'Öm Ding Oppaßß_Lėßß ze änndere, möötß_de alld [[Special:Userlogin|ennjelogg]] sinn.',
'addedwatch'            => 'En dė Oppaßß_Lėßß jedonn',
'addedwatchtext'        => 'Di Sigk „[[$1]]“ eß jäz in Dinger [[Special:Watchlist|Oppaßß_Lėßß]]. Af_jäz, wänn di Sigk 

foänndot weed, odder ier Klaaf_Sigk, dann weed dat en de Oppaßß_Lėßß jezëijsh. Dä Enndraach fö_di_Sigk küdd_en 

Fättschreff en_de „[[Special:Recentchanges|Nöüßte Ännderonge]]“, domet_De_dä_do och flöck fingx.

Wänn_de_Dä widdo loßß wääde wells uß Dinger Oppaßß_Lėßß, dann klick op „Nimmieh drob_oppaßße“ wann De_di Sigk om 

Schirrəm häßß.',
'removedwatch'          => 'Uß de Oppaßß_Lėßß jenůmme',
'removedwatchtext'      => 'Di Sigk „[[$1]]“ eß jäz uß de Oppaßß_Lėßß errußß_jenůmme.',
'watch'                 => 'Drob_Oppaßße',
'watchthispage'         => 'Op_di Sigg op_paßße',
'unwatch'               => 'Nim_mieh drobb_Oppaßße',
'unwatchthispage'       => 'Nim_mieh op di Sigk op_paßße',
'notanarticle'          => 'Këijne Atikkel',
'watchnochange'         => 'Këijne Atikkel en Dinge Oppaßß_Lėßß eß en dä aanjezëijshte Zick foänndot woode.',
'watchdetails'          => '*  <strong>$1</strong> Sigge sin in dä Oppaßß_Lėßß, oohne de Klaaf_Sigge
* [[Special:Watchlist/edit|Zëijsh di_jannze Oppaßß_Lėßß aan, kann_ze och änndere]]
* [[Special:Watchlist/clear|Schmiiß dä jannze Krohm fott, un pass_op nix mieh op]]',
'wlheader-enotif'       => '* Et E-mail Schekke eß ennjeschalldt.',
'wlheader-showupdated'  => '* Wënn_se Ëijne jeänndot hätt, zigk_dämm_De_se_t läzde moohl aanjeluert häß, sen di Sigge 

<strong>ëxtra makkeet</strong>.',
'watchmethod-recent'    => 'Ben de läzde Ännderonge jääje de Op_paßß_Lėßß am pröfe',
'watchmethod-list'      => 'Ben de Op_paßß_Lėßß am pröfe, noh de läzde Ännderong',
'removechecked'         => 'Schmiiß di Sigge medt Hökshe uß dä Oppaßß_Less_eruß',
'watchlistcontains'     => 'En dä Oppaßß_Lėßß sinn_er <strong>$1</strong> Sigge.',
'watcheditlist'         => 'Hee en dä Lėßß med_tä Sigge en Dinger Oppaßß_Lėßß, do důnn e Höökshe maache bëij dää 

Sigge, wo De nimmieh drobb_oppaßße wellß. Wänn De fäädish beß, donn unge op dä Knopp „<span style="padding:2px; 

background-color:#ddd; color:black">Schmiiß di Sigge medt Hökshe uß dä Oppaßß_Less_eruß</span>“ klikke, öm Ding Lėßß 

dann wörrəklijj_esu affzeshpëijsherre. Wänn De hee en Sigk fottlööhß, dann dëijhd_dä ier Klaaf_Sigk orr_erußfleeje, 

unn ömmjedriiht.<br /><br /><hr />',
'removingchecked'       => 'Ben di ußßjeväälte Sigge uß_dä Oppaßß_Lėßß eruss_am_schmiiße …',
'couldntremove'         => 'Kunnt „$1“ nit fott_schmiiße …',
'iteminvalidname'       => 'Dä Ėnndrach „$1“ hädd_enne kapodde Naame.',
'wlnote'                => 'Hee sinn de läzde <strong>$1</strong> Ännderonge uß de läzde <strong>$2</strong> 

Shtund.',
'wlshowlast'            => 'Zëijsh de läzde | $1 | Shtunnde | $2 | Daare | $3 | aan, donn',
'wlsaved'               => 'Dat ess_en jeseshorte Väsjohn fun Dinger Oppaßß_Lėßß.',
'wlhideshowown'         => '$1 ming ëijen Ännderonge',
'wlhideshowbots'        => '$1 de Botß ier Ännderonge',
'wldone'                => 'Fädish.',
'enotif_mailer'         => 'Dä {{SITENAME}} Nohreshte_Forsandt',
'enotif_reset'          => 'Säzz all Änderönge op „Aanjeluert“ un Äleedish.',
'enotif_newpagetext'    => 'Dad_ess_en neu aanjelaate Sigk.',
'changed'               => 'jeänndot',
'created'               => 'neu aanjelaat',
'enotif_subject'        => '{{SITENAME}}: Sigk "$PAGETITLE" fun "$PAGEEDITOR" $CHANGEDORCREATED.',
'enotif_lastvisited'    => 'Luer unger „$1“ — do fingkß de all di Änderonge zigk Dingem läzde Besooch hee.',
'enotif_body'           => 'Leeven $WATCHINGUSERNAME,

en de {{SITENAME}} wood di Sigk „$PAGETITLE“ am $PAGEEDITDATE fun „$PAGEEDITOR“ $CHANGEDORCREATED, unger 

$PAGETITLE_URL fingkß de de nöüßte Värsjohn.

$NEWPAGE

Koot Zosammejefaßß, Kwälle: „$PAGESUMMARY“ $PAGEMINOREDIT

Do kannß dä Medmaacher „$PAGEEDITOR“ aanshpräshe:
* e-mail: $PAGEEDITOR_EMAIL
* wiki: $PAGEEDITOR_WIKI

Do krißß fun jäds aan keijn e-mail mieh, beß dat Do Der di Sigk aanjeluert häß. Do kannß ävver och all di e-mail 

Märreker för di Sigge en Dinger Oppaßß_Leßß op eijmool änndere.

Enne schööne Jrooß fun de {{SITENAME}}.

--
Do kannß hee Ding Oppaßß_Leßß änndere:
{{FULLURL:Special:Watchlist/edit}}

Do kannß hee noh Hülp luere:
{{FULLURL:int:MediaWiki:Helppage}}',
'deletepage'            => 'Schmiiß di Sigk jäz fott',
'confirm'               => 'Dä Schoz fö_di Sigk ännderre',
'excontent'             => 'drop shtunndt: „$1“',
'excontentauthor'       => 'drop shtunndt: „$1“ un dä ëijnzijje Shriiver woh: „$2“',
'exbeforeblank'         => 'drop shtunndt für_her: „$1“',
'exblank'               => 'drop shtunndt nix',
'confirmdelete'         => 'Dat Fottschmiiße moß beshtäätish wääde:',
'deletesub'             => '(Di_Sigk „$1“ sůll fott_jeschmeßße wääde)',
'historywarning'        => '<strong>Opjepaßß:</strong> Di Sigk hätt (mieh wi ëijn) für_her_jejangene',
'confirmdeletetext'     => 'Do beß koot do_füür, en Sigk för iewish fott_ze_schmiiße. Dobëij foschwindt och de jannze 

Fojangenhëijdt fun dä Sigk uß de Daate_Bangk, med all ier Ännderonge un Medmaacher_Name, un all dä Oppwandt, dä do 

dren shtish. Do moßß hee jäz beshtätijje, dat de foshtëijß, wat dat bedögk, un dat De wëijß, wat Do do määß.

<strong>Donn et nuur, wänn_t met de [[{{ns:project}}:Övver et Sigge Fottschmiiße|Räjelle do_för]] wörrəklėsh zosamme 

jëijdt!</strong>',
'actioncomplete'        => 'Äledish',
'deletedtext'           => 'Di Sigk „$1“ eß jäz fottjeschmeßße woode.
Luer_Der „$2“ aan, do häß_De en Lėßß med de nöüßte fotjeschmeßßenne Sigge.',
'deletedarticle'        => 'hät fottjeschmeßße: „[[$1]]“',
'dellogpage'            => 'Logbooch med_e fottjeschmeßßenne Sigge',
'dellogpagetext'        => 'He sinn de Sigeg oppjelėßß di et nöüß fottjeschmeßße woodte.',
'deletionlog'           => 'Dat Logbooch med_e fottjeschmeßßenne Sigge dren',
'reverted'              => 'Han de äählere Väsjohn fun dä Sigk zoröck_jeholldt.',
'deletecomment'         => 'Aanlaßß för_t Fottschmiiße',
'imagereverted'         => 'Dat Bėlld eß jäz op di Väsohn fun fröjer zerögk_jesaz.',
'rollback'              => 'Ännderonge Zerög_Nämme',
'rollback_short'        => 'Zerög_Nämme',
'rollbacklink'          => 'Zerröck_Nämme',
'rollbackfailed'        => 'Dat Zerög_Nämme jingk sheef',
'cantrollback'          => 'De läzte Änderong zerrögk_ze_nämme eß nit mööshlėsh. Dä läzte Schriiver eß dä ëijnzijje, 

dä aan dä Sigk hee jät jedonn hät!',
'alreadyrolled'         => '<strong>Dat wooh nix!</strong>

Mer künne de läzde Ännderonge fun dä Sigk „[[$1]]“ fum Medmaacher „[[User:$2|$2]]“ (→[[User talk:$2|däm_singe 

Klaafs]]) nim_mieh zerögk_nämme, dat hädd_enne Anndere enzwesche alld jedonn.

De nöüßte läzde Ännderong eß jäz funn dämm Medmaacher „[[User:$3|$3]]“ (→[[User talk:$3|däm_singe Klaafs]]).',
'editcomment'           => 'Bëij dä Ännderung shtundt: „<i>$1</i>“.',
'revertpage'            => 'Ännderonge fun däm Medmaacher „[[User:$2|$2]]“ (→[[User talk:$2|däm_singe Klaafs]]) 

fottjeschmeßße, unn_do_föe de läzde Väsjohn fum „[[User:$1|$1]]“ widdo zerrökjeholldt',
'sessionfailure'        => 'Ed_joov_wall_e täshnesh Problehm med_Dingem Login. Dröm ham_mer dad_uß Füürsesh jäz nit 

jemaat, domet me_nid_fellëijsh Ding Ännderong dem fokierte Medmaacher ungerjuubelle. Jangk zerrögg_un 

fosöög_ed_norr_enß.',
'protectlogpage'        => 'Logbooch fum Sigge_Schöze',
'protectlogtext'        => 'He eß de Lėß fun Sigge, di jeschöz odder frëij jejovve woode sinn.',
'protectedarticle'      => 'hätt jeschöz: „[[$1]]“',
'unprotectedarticle'    => 'Schoz fö „[[$1]]“ opjehovve',
'protectsub'            => '(Sigge_Schoz för „$1“ änndere)',
'confirmprotecttext'    => 'Wellß De di Sigk schözze?',
'confirmprotect'        => 'Sigk schözze',
'protectmoveonly'       => 'Nuur jäje et Ömnänne schöze',
'protectcomment'        => 'Dä Jronnd oddo Aanlaß fö_t Schözze',
'unprotectsub'          => '(Schoz fö „$1“ ophävve)',
'confirmunprotecttext'  => 'Wellß De di Sigk frëij jëvve un dä iere Schoz opphävve?',
'confirmunprotect'      => 'Sigk frëij jëvve',
'unprotectcomment'      => 'Dä Aanlaßß för dä Schoz op_ze_häve',
'protect-unchain'       => 'Et Schözze jäje Ömnänne ëxtra ëijnshtëlle loohße',
'protect-text'          => 'Hee kannß_De dä Schoz jäje Veränderonge fö_de Sigk „$1“ aanlooere un änndere. Em <span 

class="plainlinks">[{{FULLURL:Special:Log/protect|page={{FULLPAGENAMEE}}}} Logbooch]</span> fingkß De ählere 

Ännderonge fun däm Schoz, wännt_se jitt. Bess_esu jood un halldt Desh aan de Räjelle för_esu Fäll!',
'protect-viewtext'      => 'Ding Beräshtijung als_enne Medmaacher eß nit jenooch, öm dä Sigge_Schoz ze änndere.

Hee de aktowälle Ėnnshtällonge för di Sigk „<strong>$1</strong>“:',
'protect-default'       => '—(Shtanndadt)—',
'protect-level-autoconfirmed'=> 'nur Medmaacher raanloohße, di sesh aanjemälldt hann',
'protect-level-sysop'   => 'Nuur de Wikki_Köbeßße raanloohße',
'restriction-edit'      => 'An et Änndere …',
'restriction-move'      => 'An et Ömnänne …',
'undelete'              => 'Fottjeschmeßßene Krohm aanluere/zerrökholle',
'undeletepage'          => 'Fottjeschmeßßen Sigge aanluere un widdo zerögk_holle',
'viewdeletedpage'       => 'Fottjeschmessen Sigge aanzëije',
'undeletepagetext'      => 'De Sigge hee_noh si fottjeschmeßße, mer künne se ävver ėmmer noch uss_em Möll_Ëmmer 

erruß_kroose.',
'undeleteextrahelp'     => 'Öm die jannze Sigk met alle iere Väsjoohne widder ze holle, loohß all de Väsjoohne oohne 

Höökshe, un kligg_op „<b style="padding:2px; background-color:#ddd; color:black">Zerröck_Holle!</b>“.

Öm blooß ëijnzel Väsjoohne zerögk_ze_holle, maach Höökshe aan di_Väsjoohne, di_De widder hann wellß, un dann donn „<b 

style="padding:2px; background-color:#ddd; color:black">Zerröck_Holle!</b>“ klikke.

Op „<b style="padding:2px; background-color:#ddd; color:black">De Fällder ußliihre</b>“
klikk, wänn_De all Ding Höökshe un Ding „Äklierong (fö_enn_et LogBooch):“ widder fott hann wellß.',
'undeletearticle'       => 'Enne fottjeschmeßßene Atikkel widdo zerrögk_holle',
'undeleterevisions'     => '<strong>$1</strong> Väsjohne en_t Aschihf jedonn',
'undeletehistory'       => 'Wänn_De di Sigk widdo zerrögk_hollß,
dann kriß_De alle fottjeshmeßßene Väsjohne widder.
Wänn_enzwesche en nöüe Sigk unger dämm aahle Name ennjereshtdt woode eß,
dann wääde de zerögkjeholldte Väsjohne ëijnfach alß zosätzlijje älldere Väsjohne fö_di nöüje Sigk ennjerëijdt wääde.
Di nöüje Sigk weed nidd_äsäzz.',
'undeletehistorynoadmin'=> 'Di Sigk es fottjeschmeßße woode. Dä Jrunnd_dö_füüer iß en de Leßß unge ze finge, 

jenau_esu wi de Medmaacher, wo de Sigk fo_änndot hann, iih dat se fotjeschmeßße wood. Wat op dä Sigk iere 

fotjeschmeßßene aahle Väsjohne shtundt, dat künne nuuer de Wikki_Köbeßße noch 

aansinn (un och widder zerögk holle)',
'undeleterevision'      => 'Fottjeschmeßßen Väsjohne noh_m Shtanndt fum $1',
'undeletebtn'           => 'Zerröck_Holle!',
'undeletereset'         => 'De Fällder ußliihre',
'undeletecomment'       => 'Äklierong (fö_enn_et LogBooch):',
'undeletedarticle'      => '„$1“ zerrögk_jeholldt',
'undeletedrevisions'    => '{{PLURAL:$1|ëijne Väsjohn|$1 Väsjohne}} zerrögk_jeholldt',
'undeletedrevisions-files'=> 'Zesamme_jenůmme <strong>$1</strong> Väsjohne fun <strong>$2</strong> Dattëije 

zerrögk_jeholldt',
'undeletedfiles'        => '<strong>$1</strong> Dattëije zerrögk_jeholldt',
'cannotundelete'        => '<strong>Däh.</strong> Dat Zerrögk_Holle jing donevve. Mööshlish, dat enne Anndere 

Medmaacher flöcker wooh, un et alld et eetz jedonn hät, un jäz eß di Sigk ald widder do jewääse.',
'undeletedpage'         => '<big><strong>Di Sigk „$1“ eß jäz widdo_doo</strong></big>

Luer Der_et [[Special:Log/delete|Logbooch med_e fottjeschmeßßenne Sigge]] aan, do häßß De de nöüßte fottjeschmeßßene 
un widdo herjeholldte Sigge.',
# Namespace form on various pages
'namespace'             => 'Appachtemeng:',
'invert'                => 'donn di Ußßwaal ömmdriije',

# Contributions
'contributions'         => 'Däm_Medmaacher sing Bëijdräsh',
'mycontris'             => 'ming Bëijdräsh',
'contribsub'            => 'För dä Medmaacher: $1',
'nocontribs'            => 'Mer han këijn Ännderonge jefonge, enn_de_Log_Böösher, di_do paßße dääte.',
'ucnote'                => 'Hee sinn däm Medmaacher sing läzde <strong>$1</strong> Änderonge fun de läzde 

<strong>$2</strong> Daare.',
'uclinks'               => 'Zëijsh de läzde <strong>$1</strong> Bëijdräsh, zëijsh de läzde <strong>$2</strong> 

Dare.',
'uctop'                 => ' (Nöüßte)',
'newbies'               => 'Nöüje Medmaacher',

# What links here
'sp-newimages-showfrom' => 'Zëijsh de nöüje Bellder aff däm $1',
'sp-contributions-newest'=> 'Nöüste',
'sp-contributions-oldest'=> 'Ählßte',
'sp-contributions-newer'=> 'Nöüste $1',
'sp-contributions-older'=> 'Ähler $1',
'sp-contributions-newbies-sub'=> 'Fö_Nöüje Medmaacher',
'whatlinkshere'         => 'Wat noh hee lingk',
'notargettitle'         => 'Këijne Bezoch obb_en Ziiel',
'notargettext'          => 'Et fäält enne Medmaacher odder en Sigk, wo mer jät zo erußfinge oddo oplißte sůlle.',
'linklistsub'           => '(Lėßß met de Lėngkß)',
'linkshere'             => 'Dat sinn di Sigge, di op <strong>„[[:$1]]“</strong> lingke důnn:',
'nolinkshere'           => 'Këijn_Sigk lėngk noh <strong>„[[:$1]]“</strong>.',
'isredirect'            => 'Ömlëijdungß_Sigk',
'istemplate'            => 'weed ėnnjeföösh',
'blockip'               => 'Medmaacher spärre',
'blockiptext'           => 'He kannz De beshtemmpte Medmaacher odder 

IP_Addräßße]shpärre, su dat_se he em Wikki nit mieh 

schriive und Sigge Änndere künne. Dat sullt nuur jedonn wääde om su jenannte 

Fandaale ze brämmse. Un mer mößße unß do_bëij natöörlish aan uns 

[[{{ns:project}}:Policy|Rääjelle]] fö_su_n Fäll hallde.

Draach bëij „Aanlaßß“ enne mööshlishßt jenoue Jrunnd_en, wöröm dat Shpärre. Nänn un Link op de Sigge wo Ëijne kapott 

jemaat hätt, zem Bäijshpill.

Luůr op [[Special:Ipblocklist|de Lėßß met jeshpächte IP_Aräßße]] wänn de ne Övverblegg_över de Shpärrunge hann wellß, 

un och wänn_De_se änndere wellß.',
'ipaddress'             => 'IP-Addräßß',
'ipadressorusername'    => 'IP Addräßß oddo Medmaacher_Name',
'ipbexpiry'             => 'Dauer fö_wi lang',
'ipbreason'             => 'Aanlaßß',
'ipbanononly'           => 'Nur de namelose Medmaacher shpärre',
'ipbcreateaccount'      => 'Nöüj_aanmällde fobeede',
'ipbsubmit'             => 'Důnn dä Medmaacher shpärre',
'ipbother'              => 'En annder Zigk',
'ipboptions'            => '1 Shrundt:1 hour,2 Shrundt:2 hours,3 Shrundt:3 hours,6 Shtund:6 hours,12 Shtund:12 

hours,1 Daach:1 day,3 Daare:3 days,1 Woch:1 week,2 Woche:2 weeks,3 Woche:3 weeks,1 Moohnd:1 month,3 Moohnde:3 

months,6 Moohnde:6 months,9 Moohnde:9 months,1 Joohr:1 year,2 Joohre:2 years,3 Joohre:3 years,Onbejrännz:infinite',
'ipbotheroption'        => 'Sönß_wi lang',
'badipaddress'          => 'Wat De do jeschrevve häß, dat eß këijn ööndlijje 

IP_Addräßß.',
'blockipsuccesssub'     => 'De IP-Addräß eß jeshpächt',
'blockipsuccesstext'    => '[[Special:Contributions/$1|$1]] eß jëz jeshpächt.

Luůr op [[Special:Ipblocklist|de Lėßß met jeshpächte IP_Aräßße]] wänn de ne Övverblegg_över de Shpärrunge hann wellß, 

un och wänn_De_se änndere wellß.',
'unblockip'             => 'Dä Medmacher widdor maache loohße',
'unblockiptext'         => 'Hee kannz De für_her jeshpächte IP_Addräßße oddo Medmaacher widdo frëijäavve, un 

dänne_esu dat Rääsh fö_ze_Schriive he em Wikki widdo_jävve.

Luůr op [[Special:Ipblocklist|de Lėßß met jeshpächte IP_Aräßße]] wänn de ne Övverblegg_över de Shpärrunge hann wellß, 

un och wänn_De_se änndere wellß.',
'ipusubmit'             => 'Důnn de Shpärr_fö_di Adräßß widdo ophävve',
'unblocked'             => '[[User:$1|$1]] wood widdo zohjelohße',
'ipblocklist'           => 'Lėßß med jeshpächte IP-Adräßße un Medmaacher_Naame',
'blocklistline'         => '$1, $2 hät „$3“ jeshpächt ($4)',
'infiniteblock'         => 'fö_iiwish',
'expiringblock'         => 'endt aam $1',
'anononlyblock'         => 'nur annonühme',
'createaccountblock'    => 'Aanmällde nit mööshlėsh',
'ipblocklistempty'      => 'Ed_eß nix en de Lėßß med jeshpächte IP-Adräßße un Medmaacher_Naame.',
'blocklink'             => 'Shpärre',
'unblocklink'           => 'widde_frëijjävve',
'contribslink'          => 'Bëijdrääsh',
'autoblocker'           => 'Automatesh jeshpächt. Ding IP_Addräßß wood köözlėsh fun däm Medmaacher „[[User:$1|$1]]“ 

jebruch. Dä eß jeshpächt woode wääje: „<i>$2</i>“',
'blocklogpage'          => 'Logbooch med Medmaacher-Shpärre',
'blocklogentry'         => '„[[$1]]“ jeshpächt, för $2',
'blocklogtext'          => 'Hee ėß dat LogBooch för et Medmaacher Shpärre un Frëijävve. Automaatėsh jeshpächte 

IP-Addräßße sin nit hee, ävver em 

[[Special:Ipblocklist|Logbooch med jeshpächte IP-Adräße]] ze finge.',
'unblocklogentry'       => 'Medmaacher „[[User:$1|$1]]“ frëijejovve',
'range_block_disabled'  => 'Adräßße_Jebeede ze shpärre, eß nit älaup.',
'ipb_expiry_invalid'    => 'De Dauer eß Drißß. Jävv_se rishtish aan.',
'ipb_already_blocked'   => '„$1“ eß ald jeshpächt',
'ip_range_invalid'      => 'Dä Berëijsh fun IP_Addräßße eß nidd_en Ochdnung.',
'proxyblocker'          => 'Proxy_Blokker',
'ipb_cant_unblock'      => 'Enne Fääler: De Shpärr Nommer $1 eß nit ze finge. Se künndt ald widdo frëij_jejovve woode 

sinn.',
'proxyblockreason'      => 'Unger Dinge IP_Addräßß 

leuv_enne offene Proxy. Dröm kannß_De hee em Wikki nix maache. Schwadt med 

Dimgem Süßteem_Minsch oddo Näzwärrək_Täshnikko Internet Service Provider un 

fozäll dänne fun däm Rissikko för Ühr Sesherhëijdt!',
'proxyblocksuccess'     => 'Fähdėsh',
'sorbs'                 => 'SORBS DNSbl',
'sorbsreason'           => 'Ding IP_Addräßß weed en de 

[http://www.sorbs.net SORBS] DNSbl als_enne offene 

Proxy jelėßß. Schwadt med Dimgem Süßteem_Minsch oddo Näzwärrək_Täshnikko 

Internet Service Provider drövver, un fozäll dänne fun däm Rissikko för Ühr 

Sesherhëijdt!',
'sorbs_create_account_reason'=> 'Ding IP_Addräßß weed en 

[http://www.sorbs.net SORBS] DNSbl als_enne offene 

Proxy jelėßß. Dröm kannß_De Desch hee em Wikki nit allse_enne nöüje Medmaacher 

aanmällde. Schwadt med Dimgem Süßteem_Minsch oddo Näzwärrək_Täshnikko oddo Internet Service Provider drövver, un fozäll dänne fun däm Rissikko för Ühr Sesherhëijdt!',
'lockdb'                => 'Daate_Bangk Spärre',
'unlockdb'              => 'Daate_Bangk frëij_jäve',
'lockdbtext'            => 'Noh_m Shpärre kann Këijne mieh Ännderonge maache an singe Op_paßß_Lėßß, aan 

Ëijnshtellunge, Atikelle, uew. un nöüje Medmaacher jidd_et och nit. Beß sesher, dat_Te_dat wellß?',
'unlockdbtext'          => 'Noh_m Frëij_Jävve eß de Daate_Bangk nit mieh jeshpächt, un all_di nommaale Ännderonge 

weede widdo mööshlesh. Beß sesher, dat_Te_dat wellß?',
'lockconfirm'           => 'Jo, ėsh_well dė Daate_Bangk jeshpächt hann.',
'unlockconfirm'         => 'Jo, ėsh_well dė Daate_Bangk frëij jäve.',
'lockbtn'               => 'Daate_Bangk Spärre',
'unlockbtn'             => 'Daate_Bangk frëij jäve',
'locknoconfirm'         => 'Do häß këij Höhksche en_dämm Fëlldt zem Beshtätijje jemaat.',
'lockdbsuccesssub'      => 'De Daate_Bangk eß jäz jespächt',
'unlockdbsuccesssub'    => 'De Daate_Bangk eß jäz frëij_jejovve',
'lockdbsuccesstext'     => 'De Daate_Bank fun de {{SITENAME}} jäz jeshpächt.<br />
Důnn_se widdo frëij_jëvve, wann_Ding Waadung dorresch eß.',
'unlockdbsuccesstext'   => 'De Daate_Bangk eß jäz frëij_jejovve.',
'lockfilenotwritable'   => 'Dė Dattëij, wo dė Date_Bangk met jeshpächt weede wööd, künne_mer nit aanlääje, odder nit 

dren shriive. Esu enne Drißß! Dat mööt dä Web_ßööver ävver künne! Fozäll dadd_ennem Foanntwochtlijje fö de 

Inshtallazjohn fu däm ßööver, odder reparėer_et selləve, wänn_De kannß.',
'databasenotlocked'     => '<strong>Opjepaßß:</strong> De Daate_Bangk eß <strong>nit</strong> jespächt.',
'makesysoptitle'        => 'Maach_enne Wikki_Köbes uß däm Medmaacher',
'makesysoptext'         => 'He künne Bürro_Kraade uss_ennem nommaale Medmaacher enne Wikki_Köbes oddo orr_enne 

Bürrokraat maache.
Shriif däm Medmaacher singe Medmaacher_Name hee erinn, un Lohß Jonn!',
'makesysopname'         => 'Hee dä Medmaacher_Name:',
'makesysopsubmit'       => 'Maach enne Wikki_Köbes uß dämm Medmaacher',
'makesysopok'           => '<strong>Dä Medmaacher „[[User:$1|$1]]“ iß jäds_enne Wikki_Köbes jewoode.</strong>',
'makesysopfail'         => '<strong>Dä Medmaacher „$1“ kunnt nit zom Wikki_Köbes jemaat wääde. Et jing nit. Häß_De dä 

Naame fellëijsh fokiijet jeshrevve?</strong>',
'setbureaucratflag'     => 'Maach och enne Bürrokraat druß',
'rightslog'             => 'Logbooch fö_Ännderonge aan Medmaacher-Rääshde',
'rightslogtext'         => 'He sin de Änderonge an Medmaacher ier Rääshde opjelėßß. Op de Sigge övver 

Wikki_Köbeßße, Bürro_Kraade, … kannß_De 

noh_lässe, wat domet eß.',
'rightslogentry'        => 'hät däm Medmaacher „$1“ sing Rääshde fun „$2“ op „$3“ ömjestalldt',
'rights'                => 'Rääschde:',
'set_user_rights'       => 'Däm Medmaacher sing Rääshte nöü beshtemme',
'user_rights_set'       => '<strong>Dem Medmaacher „$1“ sing Rääshte woote nöü jessäz</strong>',
'set_rights_fail'       => '<strong>Dem Medmaacher „$1“ sing Rääshte woote nit aanjepakk. Et jing nit. Häß_De dä 

Naame fellëijsh fokiijet jeshrevve?</strong>',
'makesysop'             => 'Medmaacher zom Wikki_Köbes maache',
'already_sysop'         => 'Dä Medmaacher aß alld_enne Wikki_Köbes.',
'already_bureaucrat'    => 'Dä Medmaacher eß ald_ene Bürrokraat.',
'rightsnone'            => '(nix)',
'movepage'              => 'Sigk Ömnänne',
'movepagetext'          => 'Hee kannß De en Sigk en de {{SITENAME}} ömnänne. Domet kritt di Sigg_enne nöüje Name, un 

alle fürherijje Väsjohne fun dä Sigk och. Unger däm ahle Name weed_otomatijj_en 

Ömlëijdung op dä nöüe Name enjedraare. Lėngkß op dä 

aahle Name blieve ävver wi se woohre. Dat hëijß, Do moßß sellver nohluere, ov do jäz 

[[Special:Doubleredireects|dubbelde]] oddo [[Special:Doubleredireects|kapotte]] Ömlëijdunge bëij eruß_kumme. 

Wenn_De_n Sigg_ömnänne dëijß, häß Do och dör ze sorrəje, dat_de betroffene Lingkß do hen jonn, wo se hen jonn sulle. 

Allso holl Der de Leßß „Wat noh hee lingk“ un jangk se dorrsh!

Di Sigk weed <strong>nit</strong> ömjenanndt, wann_et met däm nöüe Name alld_en Sigk jitt, <strong>ußßer</strong> do 

eß nix drop, odder et ess_en Ömlëijdung un se eß no nii jeänndot voode. Esu kam_mer en Sigk jlish widder zerögk 

ömnänne, wämmer sesh mem Ömnänne fodonn hätt, un mer kann_och këijn Sigge kapottmaache, wo alld jät drop shtëijdt.

<strong>Oppjepaßß!</strong> Wat bëijm Ömnänne eruß_kütt, künnd_en opfällije un fellëijsh shtüürende Änderong am Wikki 

sinn, besöndoß bëij öff jebruchte Sigge. Also beß sėsher, dat_E foshtëijß, wat_De hee am maache beß, ih_dat_E_t 

määß!',
'movepagetalktext'      => 'Dä Sigk ier Klaaf_Sigk, wann_se_ëijn hätt, weed automattish medd_öm_jenanndt, 

\'\'\'ußßer\'\'\' wänn:
* di Sigg_enn_en annder Appachtemeng kütt,
* en Klaaf_Sigk met däm nöüe Name alld do eß, un et shtëijd_och_jät drop,
* De unge en_däm Käßßje \'\'\'këij\'\'\' Höökshe aan häßß.

En dänne Fäll, moßß_De Der dä Ėnnhalldt fun dä Klaaf_Sigge slləfß für_nämme, un eröm_kopėere,
wat_De bruchß.',
'movearticle'           => 'Sigk Ömnänne',
'movenologin'           => 'Nėd_Ėnnjelogg',
'movenologintext'       => 'Do mööds_alld aanjemäldt un [[Special:Userlogin|ennjelogg]] sinn, öm en Sigk 

öm_ze_nänne.',
'newtitle'              => 'op dä nöüje Naame',
'movepagebtn'           => 'Ömnänne',
'pagemovedsub'          => 'Dat Ömnänne hätt_jeflupp',
'pagemovedtext'         => 'Di Sigk „[[$1]]“ eß jäz ömjenannd_en „[[$2]]“.',
'articleexists'         => 'Di Sigk met dämm Naame jidd_et alld, oddo dä Naame kam_mer odder darrəf 

mer_nit_bruche.<br />Do moß_De Der_enne anndere Name uß_sööhke.',
'talkexists'            => '<strong>Opjepaßß:</strong> Di Sigk sälləver woodt jäz ömjenanndt, ävver dä_ier Klaaf_Sigk 

kunnte mer net medt_öm_nänne. Et jidd_alld_ëijn met_däm nöüe Naame. Bess_esu_jood_un donn di zwëij fun hand zosamme 

lääje!',
'movedto'               => 'ömjenanndt en',
'movetalk'              => 'dä_ier Klaaf_Sigk met_öm_nänne',
'talkpagemoved'         => 'Di Klaaf_Sigk do_zo wood medt_ömm_jenanndt.',
'talkpagenotmoved'      => 'Di Klaaf_Sigk do_zo wood <strong>nit</strong> ömmjenanndt.',
'1movedto2'             => 'hät di Sigk fun „[[$1]]“ en „[[$2]]“ ömjenanndt.',
'1movedto2_redir'       => 'hät di Sigk fun „[[$1]]“ en „[[$2]]“ ömjenannd_un do_för de ahle Ömlëijdungß_Sigk 

fottjeschmeße.',
'movelogpage'           => 'Logbooch med de ömjenanndte Sigge',
'movelogpagetext'       => 'Hee sin_de_nöüßte ömjenanndte Sigge opjelėßß, unn_wä_t jedonn hätt.',
'movereason'            => 'Aanlaßß',
'revertmove'            => 'Et Ömnänne zerök_nämme',
'delete_and_move'       => 'Fottschmiiße un Ömnänne',
'delete_and_move_text'  => '== Däh! Dubbelte Name ==

Dä Atikkel „[[$1]]“ jidd_et_ald. Wůlltß_De_en fottschmiiße, öm hee dä Atikkel ömnänne ze künne?',
'delete_and_move_confirm'=> 'Jo, důnn dä Atikkel fottschmiiße.',
'delete_and_move_reason'=> 'Fottjeschmeßße, öm Plaz för_t Ömnänne ze maache',
'selfmove'              => 'Dů_Doof! — dä aahle Namme un dä nöüje Naame eß dä_sellve — do hädd_et Ömnänne winnish 

Sėnn.',
'immobile_namespace'    => 'Do künne_mer Sigge nit hen ömnänne, dat Appachtemeng eß_shpezjäll, un_dä_nöüje_Name fö_di 

Sigk jëijd_däßwääje_nit.',
'export'                => 'Sigge Ëxpochtėere',
'exporttext'            => 'Hee äxpochtėeß_De dä Täxx un de Ëijenschaffte fun enner Sigk, oddo fun_ennem Knubbel 

Sigge,
de aktowälle Väsjohn, 
met odder oohne ier äählere Väsjohne.
Dat Jannze es enjepagg_en XML.
Dat kam_mer enn_en annder Wikki
— wänn_et och met dä MediaWiki-ßoffwäer leuf -
övver di Sigk „[[Special:Import|Impocht]]“ doo, widder impochtėere.

* Schriif de Tittelle fun dä Sigge en dat Fälldt fö_Täxx ennzejäve, unge, ëijne Tittel in jeede Rëij.
* Dann dunn on_noch ußsööke, ov_De all de fürherijje Väsjohne fun dä Sigge hann wellß, oddo nuur de akktowälle met dä 

Infommazjoohne fun de läzde Ännderong. (En dämm Fall künnz_De, fö_n ëijnzellne Sigk, och enne tirägkte Lėngk bruche, 

zom Bëijshpill „[[{{ns:Special}}:Export/{{int:mainpage}}]]“ för de Sigk „[[{{int:mainpage}}]]“ ze äxpochtėere)

Dängk draan, dat_Te dat Zeush em Unicode Fommaat afshpäijshere moßß,
wänn_De jät domet aanfange künne wellß.',
'exportcuronly'         => 'Blooß de aktowälle Väsjohn ußßjävve (un_<strong>nit</strong> de jannze aaale Väsjohne 

on_noch met do_bëij donn)',
'exportnohistory'       => '----
<strong>Opjepaßß:</strong> de jannze Väsjohne Äxpochtėere eß he em Wikki affjeschaldt. Schaadt, ävver_et wöör_en 

zo_jůůße Laßß fö_dä ßöövor.',
'export-submit'         => 'Lohß_Jonn!',
'allmessages'           => 'All Täxx, Boushtejn un Aanzeije fum Wikki_Süßteem',
'allmessagesname'       => 'Name',
'allmessagesdefault'    => 'Dä shtandat_määßijje Täxx',
'allmessagescurrent'    => 'Esu eß dä Täxx jäz',
'allmessagestext'       => 'Hee kütt en Lëß met Täxxte, Täx_Shtök, un Nohrishte em Appachtemeng „MediaWiki:“',
'allmessagesnotsupportedUI'=> 'Mer künne „Special:Allmessages“ nit met dä Ingerfäijß_Shprooch <strong>$1</strong> 

zosamme, di De jraat ennjeshtälldt häß. Söök Der en anndere Shprooch uß, wo_t met jonn künnt!',
'allmessagesnotsupportedDB'=> '<strong>Dat wooh nix!</strong> Mer künne „Special:Allmessages“ nit zëije, 

<code>wgUseDatabaseMessages</code> eß ußßjeshalldt!',
'allmessagesfilter'     => 'Fingk dat Shtöck hee em Name:',
'allmessagesmodified'   => 'Důnn nur de Foänndotte aanzëije',
'thumbnail-more'        => 'Jrüüßer aanzëije',
'missingimage'          => '<b>Dat Bėlld es nit doh:</b><br />„$1“',
'filemissing'           => 'Dattëij eß nit_do',
'thumbnail_error'       => 'Enne Fääler eß opjedouch bëijm Maache fun_em Breefmarrəke/Thumbnail-Belldshe: „$1“',
'import'                => 'Sigge Ėmpochtėere',
'importinterwiki'       => 'Tranß_Wikki Ėmpocht',
'import-interwiki-text' => 'Wähl_en Wikki unn_en Sigk zem Ėmmpochtėere uß. Et Dattum fun de Väsjohne un de 

Medmaacher_Naame fun de Schriiver weede dobëij metjenůmme. All de Tranß_Wikki Ėmmpochte weede em 

[[{{ns:special}}:Log/import|Ėmmpocht_LogBooch]] faßßjehallde.',
'import-interwiki-history'=> 'All de Väsjohne fun dä Sigk hee kopėere',
'import-interwiki-submit'=> 'Huh_Laade!',
'import-interwiki-namespace'=> 'Donn de Sigge ėmpochtėere em Appachtemeng:',
'importtext'            => 'Dunn de Daate med däm „[[Special:Export|Ëxpocht]]“ fun doo fun ennem Wikki Äxpochtėere, 

do_bëij don_net — ättwa bëij Dir om Räshnor — affshpëijsherre, un dann hee huh_laade.',
'importstart'           => 'Ben Sigge am ėmpochtėere …',
'import-revision-count' => '({{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}})',
'importnopages'         => 'Këijn Sigk för ze_Ėmpochtėere jefonge.',
'importfailed'          => 'Dat Impochtėere eß donëvve_jejange: $1',
'importunknownsource'   => 'Di Zoot Qwäll fö_t Ėmpochtėere kënne_mer nit',
'importcantopen'        => 'Kunnt op de Dattëij fö_dä Ėmpocht nit zohjriife',
'importbadinterwiki'    => 'Fokiehjter Ingerwiki_Lėngk',
'importnotext'          => 'En dä Dattëij wooh nix dren ännthallde, oddo_winnishßdenß këijne Täxx',
'importsuccess'         => 'Dat Ėmpochtėere hätt jeflupp!',
'importhistoryconflict' => 'Mer hann zwëij aahle Väsjohne jefonge, di donn sėsh biiße — di ëijn wooh alld_doo — de 

annder en dä Ėmpoot_Dattëij. Mööshlesh, Ühr hatt_i Daate alld_enß ėmpootėedt.',
'importnosources'       => 'Hee sin këijn Qwälle fö_do Tranß_Wikki Ėmpocht ennjereshdt.
Dat aahle Väsjohne Huhlaade eß affjeschalldt, un_nit mööshlėsh.',
'importnofile'          => 'Et wood ja_këij Dattëij huh_jelaade fö_ze Ėmpochtėere.',
'importuploaderror'     => 'Dat Huh_Laade eß donevve jejange. Mööshlėsh, dat_te Dattëij ze_jruuß woh, jrüüßo wi_mmer 

huh_laade darrəf.',
'importlogpage'         => 'Logbooch med ėmpochtėerte Sigge',
'importlogpagetext'     => 'Sigge met iere Väsjohne fun annder Wikkiß ėmpochtėere.',
'import-logentry-upload'=> '„[[$1]]“ ėmpochtėet',
'import-logentry-upload-detail'=> '{{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}} ėmpochtėet',
'import-logentry-interwiki'=> 'tranß_wikki_ėmmpochtėet: „$1“',
'import-logentry-interwiki-detail'=> '{{PLURAL:$1|ëijn Väsjohn|$1 Väsjohne|këijn Väsjohn}} fun „$2“',
'accesskey-search'      => 'f',
'accesskey-minoredit'   => 'm',
'accesskey-save'        => 's',
'accesskey-preview'     => 'p',
'accesskey-diff'        => 'v',
'accesskey-compareselectedversions'=> 'v',
'accesskey-watch'       => 'w',
'tooltip-search'        => 'En de {{SITENAME}} sööke [alt-f]',
'tooltip-minoredit'     => 'Dëijt Ding Ännderonge allß klëijn Minni_Ännderonge makėere. [alt-m]',
'tooltip-save'          => 'Dëijt Ding Ännderonge affsphëijshere. [alt-s]',
'tooltip-preview'       => 'Lißß de Füür_Aansėsh fun dä Sigk un_fun_Dinge Ännderonge ih_dat_De_n Affsphëijshere 

dëijß! [alt-p]',
'tooltip-diff'          => 'Zëijsh Ding Ännderone am Täxx aan. [alt-v]',
'tooltip-compareselectedversions'=> 'Donn de Ungescheed zweshe dä bëijde ußjewäälte Väsjohne zëije. [alt-v]',
'tooltip-watch'         => 'Op di Sigk hee oppaßße. [alt-w]',
'Common.css'            => '.allpagesredirect, .titleNamespace {font-style:italic}',
'Monobook.css'          => ' /* edit this file to customize the monobook skin for the entire site */
 
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
'nodublincore'          => 'De RDF_Metta_Daate fun de „Dublin Core“ Aat senn affjeschalldt.',
'nocreativecommons'     => 'De RDF_Metta_Daate fun de „Creative Commons“ Aat senn affjeschalldt.',
'notacceptable'         => '<strong>Blööd:</strong> Dä Wikki_ßööver kann de Daate nit en_ennem Fomaat erövverjävve, 

wat Dinge Client odde Brauser foshtonn künnt.',
'anonymous'             => 'Namelose Medmaacher fun_de {{SITENAME}}',
'siteuser'              => '{{SITENAME}}-Medmaacher $1',
'lastmodifiedatby'        => 'Hee di Sigk wood_et läz jeänndort fun $3 om $2, $1',
'and'                   => 'un',
'othercontribs'         => 'Bout op de Ärbeëijdt fun „<strong>$1</strong>“ op.',
'others'                => 'anndere',
'siteusers'             => '{{SITENAME}}-Medmaacher $1',
'creditspage'           => 'Övver de Medmaacher un ier Bëijdräsh fö_di Sigk',
'nocredits'             => 'Fö_di Sigk ham_mer nix en de Lėßß.',
'spamprotectiontitle'   => 'SPAM_Shoz',
'spamprotectiontext'    => 'Di Sigk, di de affshpëijshere wellß, di weed fun unsem SPAM_Shoz net dorschjelohße. Dat 

küt domiiz fun ennem Lėngg_obb_en främmbde Sigk.',
'spamprotectionmatch'   => 'Hee dä Täx hät dä SPAM_Shoz op_de Plan jeroofe: „<code>$1</code>“',
'subcategorycount'      => 'Hee {{PLURAL:$1|weed ëijn Ungerjropp|wääde $1 Ungerjroppe}} jezëijsh <small>  (Et künnt 

mieh op de füürije un nähkßte Sigge jëvve)</small>',
'categoryarticlecount'  => 'Hee {{PLURAL:$1|weed ëijne Attikkel|wääde $1 Attikkelle}} jezëijsh <small>  (Et künnt 

mieh op de füürije un nähkßte Sigge jëvve)</small>',
'listingcontinuesabbrev'=> ' wigger',
'spambot_username'      => 'SPAM fottschmiiße',
'spam_reverting'        => 'De läzde Väsjohn eß oohne_de Lėnggs_obb „$1“ widdo zerrögk_jeholldt.',
'spam_blanking'         => 'All di Väsjohne hatte Lėnggs_obb „$1“, di_sen_jäds_erruß_jemaat.',
'infosubtitle'          => 'Övver de Sigk',
'numedits'              => 'Aanzal Ännderonge an_däm Atikkel: <strong>$1</strong>',
'numtalkedits'          => 'Aanzal Ännderonge aan de Klaaf_Sigk: <strong>$1</strong>',
'numwatchers'           => 'Aanzal Oppaßßer: <strong>$1</strong>',
'numauthors'            => 'Aanzal Medmaacher, di_an_dämm Atikkel jeshrevve hann: <strong>$1</strong>',
'numtalkauthors'        => 'Aanzal Medmaacher bëijem Klaaf: <strong>$1</strong>',
'mw_math_png'           => 'Ėmmer nuur PNG aanzëije',
'mw_math_simple'        => 'En ëijnfaache Fäll maach HTML, sönß PNG',
'mw_math_html'          => 'Maach HTML wann mööshlish, un sönß PNG',
'mw_math_source'        => 'Loohs_et als TeX (joot fö_de Täxx_Brausere)',
'mw_math_modern'        => 'De bëßß Ënnshtëllung_fö_de_Brauser fun hügk',
'mw_math_mathml'        => 'Nemm „MathML“ wän_mööshlish (em probier_Shtadijum)',
'markaspatrolleddiff'   => 'Nohjeluert. Důnn dat faßßhallde',
'markaspatrolledtext'   => 'Di Änderong eß nohjeluert, donn dat faßßhallde',
'markedaspatrolled'     => 'Et Kënnzëijshe „Nohjeluert“ shpëijshere',
'markedaspatrolledtext' => 'Ed_eß_jäz faßßhallde, dat_dė ußßjewäälte Ännderonge nohjeluert woode sinn.',
'rcpatroldisabled'      => 'Et Nohluere fun de läzde Ännderonge eß affjeschalldt',
'rcpatroldisabledtext'  => 'Et Nohluere fun de läzde Ännderonge eß fö_do_Mommännt nit mööshlėsh.',
'markedaspatrollederror'=> 'Kann dat Kënnzëijshe „Nohjeluert“ nit affshpëijshere.',
'markedaspatrollederrortext'=> 'Do_moss_en beshtemmpte Väsjohn ußsööke.',
'Monobook.js'           => ' /* tooltips and access keys */
 var ta = new Object();
 ta[\'pt-userpage\'] = new Array(\'.\',\'Ding ëijen Medmaacher_Sigk.\');
 ta[\'pt-anonuserpage\'] = new Array(\'.\',\'De Medmaacher_Sigk fun Dinge aktlowälle IP_Adräßß.\');
 ta[\'pt-mytalk\'] = new Array(\'n\',\'Ding ëijen Klaaf_Sigk.\');
 ta[\'pt-anontalk\'] = new Array(\'n\',\'Klaaf_Sigk övver de Bëijdräsh fun Dinge aktowälle IP_Addräß.\');
 ta[\'pt-preferences\'] = new Array(\'\',\'Ding eijene Enshtëllunge hee op dä {{SITENAME}}.\');
 ta[\'pt-watchlist\'] = new Array(\'l\',\'De Leßß met dä Sigge wo De drop op_paßße lööhß.\');
 ta[\'pt-mycontris\'] = new Array(\'y\',\'De Leßß met Dinge eijene Bëijdräsh.\');
 ta[\'pt-login\'] = new Array(\'o\',\'Do küünz Desh widdo ennlogge, ed_eß ävver nit nüüdish.\');
 ta[\'pt-anonlogin\'] = new Array(\'o\',\'Do küünz Desh hee als_enne Medmaacher aanmëllde, moss_ävvor nit.\');
 ta[\'pt-logout\'] = new Array(\'o\',\'Ußlogge, domet De zem nameloose Medmaacher weeß.\');
 ta[\'ca-talk\'] = new Array(\'t\',\'Klaaf övver de Sigk med Enhalld.\');
 ta[\'ca-edit\'] = new Array(\'e\',\'Do kannß di Sigk fo_änndere. Luer Der de Füür_Aansesh aan, ih dat_De_se 

affshpëijsherre dëijß.\');
 ta[\'ca-addsection\'] = new Array(\'+\',\'Donn norr_enne Affschnett bëij dä Klaaf hee.\');
 ta[\'ca-viewsource\'] = new Array(\'e\',\'Di Sigk eß jääve Foänderonge jeschöz. Do kannß ävver dä Wikki_täx 

beluere.\');
 ta[\'ca-history\'] = new Array(\'h\',\'De Väsjohne fun dä Sigk hee op_lißßte.\');
 ta[\'ca-protect\'] = new Array(\'=\',\'Di Sigk jäje et Fo_Änndere un/oddo Ömnänne schözze.\');
 ta[\'ca-delete\'] = new Array(\'d\',\'Di Sigk fottschmiiße.\');
 ta[\'ca-undelete\'] = new Array(\'d\',\'Holl de Änderonge un Väsjohne fun dä Sigk zerög, di_t joof, ih_dat_se 

fott_jeschmeßße wood.\');
 ta[\'ca-move\'] = new Array(\'m\',\'Di Sigk ömnänne.\');
 ta[\'ca-watch\'] = new Array(\'w\',\'Pack di Sigk en Ding Oppaß_Less_errinn.\');
 ta[\'ca-unwatch\'] = new Array(\'w\',\'Schmiiß di Sigk uß Dinger Oppaß_Less_erruß.\');
 ta[\'search\'] = new Array(\'f\',\'Söök jëdd_em Projägk hee.\');
 ta[\'p-logo\'] = new Array(\'\',\'Zor Houpsigk fum Projägk.\');
 ta[\'n-mainpage\'] = new Array(\'z\',\'Jangk op de Houpsigk.\');
 ta[\'n-portal\'] = new Array(\'\',\'Övver dat Projägk hee, wat Do donn kannß, woh De jät fingkß, un esu …\');
 ta[\'n-currentevents\'] = new Array(\'\',\'Fingk jät do drövver jesaat, wadd_am Jang_eß.\');
 ta[\'n-newpages\'] = new Array(\'p\',\'De Leßß met dä nöüe Attikkelle em Wikki.\');
 ta[\'n-recentchanges\'] = new Array(\'r\',\'De Leßß met dä nöüßte Ännderonge em Wikki.\');
 ta[\'n-randompage\'] = new Array(\'x\',\'Jangg_obb_en zofällish üßßjewählte Sigk hee.\');
 ta[\'n-help\'] = new Array(\'\',\'Jangk noh_m Enhalltß_Forzeijshneß fun de Hülp_Sigge.\');
 ta[\'n-sitesupport\'] = new Array(\'\',\'Hellef dem Projägg Beshtonn.\');
 ta[\'t-whatlinkshere\'] = new Array(\'j\',\'De Leßß met all Sigge em Wikki, di no hee hen fowiise (lingke) donn.\');
 ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'De Leßß met dä nöüßte Ännderonge aan Sigge, di no hee hen fowiise 

(lingke) donn\');
 ta[\'feed-rss\'] = new Array(\'\',\'RSS Fied fö_di Sigk hee.\');
 ta[\'feed-atom\'] = new Array(\'\',\'Atom Fied fö_di Sigk hee.\');
 ta[\'t-contributions\'] = new Array(\'\',\'De Leßß met alle Bëijdrääsh fun däm Medmaacher hee.\');
 ta[\'t-emailuser\'] = new Array(\'\',\'Schegg_en e-mail aan dä Medmaacher hee.\');
 ta[\'t-upload\'] = new Array(\'u\',\'Bellder odde annder Medije_Enhallde Huhlaade.\');
 ta[\'t-specialpages\'] = new Array(\'q\',\'De Leßß met all dä Sönder_Sigge.\');
 ta[\'ca-nstab-main\'] = new Array(\'c\',\'De Sigk aanluere.\');
 ta[\'ca-nstab-user\'] = new Array(\'c\',\'De Medmaacher_Sigk aanluere.\');
 ta[\'ca-nstab-media\'] = new Array(\'c\',\'De Meedije_Sigk aanluere.\');
 ta[\'ca-nstab-special\'] = new Array(\'\',\'Dat eß en Söndersigk, di kam_mer nit änndere.\');
 ta[\'ca-nstab-project\'] = new Array(\'a\',\'De Projägk_Sigk aanluere.\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'De Bellder_Sigk aanluere.\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'De Wikki_Meddäijlongs- oddo Täx_Boushtëijn_Sigk aanluere.\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'De Schabloone_Sigk aanluere.\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'De Hülp_Sigk aanluere.\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'De Saachjropp_Sigk aanluere.\');',
'deletedrevision'       => 'De ahle Väsjohn „$1“ eß fottjeschmeßße.',
'previousdiff'          => '← De Ungersheede dö_für zëije',
'nextdiff'              => 'De Ungersheede do_noh zëije →',
'imagemaxsize'          => 'Bėllder op_de Sigge, wo_se beschrivve vääde, nit jrüüßer maache wi:',
'thumbsize'             => 'Esu brëijdt sůlle de klëijn Belldsche (Thumbnails/Breefmarrke) sinn:',
'showbigimage'          => 'Důnn de Väsjohn med de hüütßte Opplösung eraf_laade, dad_eß <strong>$1</strong> x 

<strong>$2</strong> Pixelle, un_di eß <strong>$3</strong> Killo_Byte jruß.',
'newimages'             => 'Bellder, Tööhn, uew. allß Jallerih',
'showhidebots'          => '(Botß $1)',
'noimages'              => 'Këij_Dattëijje jefonge.',
'variantname-zh-cn'     => 'cn',
'variantname-zh-tw'     => 'tw',
'variantname-zh-hk'     => 'hk',
'variantname-zh-sg'     => 'sg',
'variantname-zh'        => 'zh',
'variantname-sr-ec'     => 'sr-ec',
'variantname-sr-el'     => 'sr-el',
'variantname-sr-jc'     => 'sr-jc',
'variantname-sr-jl'     => 'sr-jl',
'variantname-sr'        => 'sr',
'specialloguserlabel'   => 'Medmaacher:',
'speciallogtitlelabel'  => '  Sigge_Naame:',
'passwordtooshort'      => 'Dat Paßßwood_ėß jät koot — et mööte alld winnishßdenß <strong>$1</strong> Zëijshe, 

Zėffere, un Boochshtaave do_dren sinn.',
'mediawarning'          => '<strong>Opjepaßß</strong>: En dä Dattëij küünd_en <b>jefääerlish Projramm_Shtögk</b> dren 

shtäke. Wäm_mer_et joufe loohße däät, do künndt dä ßööver met fö de Kräkkor opjemaat wääde.
<hr />',
'fileinfo'              => '<strong>$1</strong> Killo_Byte, MIME-Tüp: <code>$2</code>',

# Metadata
'metadata'              => 'Metta_Daate',
'metadata-help'         => 'En dä Datttëij shish noh_mieh an Daate. Dat sin Metta_Daate, di nommaal fum Oppname_Jerät 
kumme. Wadd_en Kammera, ne Skänner, un_esu, do faßßjehallde hann, dat kann_ävver spääder medd_ennem Projramm 
beärrbtëijdt un ußjetuusch woode sinn.',
'metadata-expand'       => 'Mieh zëije',
'metadata-collapse'     => 'Daate Foshtäshe',
'metadata-fields'       => 'EXIF metadata fields listed in this message will
be included on image page display when the metadata table
is collapsed. Others will be hidden by default.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',
# Exif tags


# external editor support
'edit-externally'       => 'Donn di Dattëij med_ennem äxtärrne Projramm bëij Dr_om Räshnor beärrbëijde',
'edit-externally-help'  => 'Luer op [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] noh 
Henwiiß, wi mer_esu_en äxtärrn Projramm opsäzz un inshtallėere dëijt.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall'      => 'all',
'imagelistall'          => 'all',
'watchlistall1'         => 'all',
'watchlistall2'         => 'Alles',
'namespacesall'         => 'all',

# E-mail address confirmation
'confirmemail'          => 'e-mail Adräßß beshtätijje',
'confirmemail_noemail'	=> 'En [[Special:Preferences||Dinge Ëijnshtellunge]] eß këijn ööndlijje e-mail Addräßß.',
'confirmemail_text'     => 'Ih dat De en dämm Wikki hee de e-mail bruche kannß, moß_De Ding e-mail Addräßß beshtätish 
hann, dat_se en Odenung_eß un dat_se och Ding ëijen eß. Kligk op dä Knopp un Do kriss_en e-mail jeschek. Do 
shtëijd_enne Lėngk medd_ennem Kood dren. Wänn_De met Dingem Brauser op dä Lėngk jëijß, dann dëijß_De domet 
beshtätijje, dadd_et wörrklėsh Ding e-mail Addräßß eß. Dat eß nit förshterlesh sesher, also wööh nix fö_Ding 
Bangk_Konto oddo bëij de Shpaakaßß, ävver et sorresh doför, dat nit jeede Paijjaßß met_Dinge e-mail oddo Dingem 
Medmaacher_Naame eröm_maache kann.',
'confirmemail_send'     => 'Schegg_en e-mail zem Beshtätijje',
'confirmemail_sent'     => 'En e-mail zem Beshtätijje eß ungerwääß.',
'confirmemail_sendfailed'=> 'Bëijm e-mail Addräßß Beshtätijje eß jät donëvve jejange, dä ßööver hadd_e Problem_med 

singe Konfijurazjohn, oddo en Dinge e-mail Addräßß ess_e Zëijshe fokihjet, oddor_esu_jät.',
'confirmemail_invalid'  => 'Bëijm e-mail Addräßß Beshtätijje eß jät donëvve jejange, dä Kood eß fokihjet, künnt 

affjeloufe jewääse sinn.',
'confirmemail_needlogin'=> 'Do moßß Desh $1, fö_de e-mail Addräßß ze beshtätijje.',
'confirmemail_success'  => 'Ding e-mail Adräßß eß jäz beshtäätisht. Jäz künnz_De och noch 

[[Special:Userlogin|enlogge]]. Fill_Shpaßß!',
'confirmemail_loggedin' => 'Ding e-mail Addräßß eß jäz beshtäätish!',
'confirmemail_error'    => 'Bëijm e-mail Addräßß Beshtätijje eß jät donëvve jejange, dė Beshtätijung kunnt nit 

affjeshpëijshot weede.',
'confirmemail_subject'  => 'Donn Ding e-mail Addräßß beshtätijje fö_de {{SITENAME}}.',
'confirmemail_body'     => 'Joot mööshlish, Do woos_et sellver,
fun de IP_Addräßß $1,
hät sesh jedenfallß Äijne aanjemälldt,
un well dä Medmaacher „$2“ op de {{SITENAME}}
wääde, un hädd_en e-mail Addräßß aanjejovve.
Öm jäz kloo_ze_krijje, dat_di e-mail
Addräßß un dä nöüje Medmaacher och bëijenander
jehüüre, moß dä Nöüje en singem Brauser
dä Lengk:

$3

op_maache. Noch für em $4. 
Also donn dat, wänn de et sellver beß.

Wänn nit Do, söndern sönß wer Ding e-mail
Addräßß aanjejovve hätt, do bruchß de jaa nix
ze donn. Di e-mail Addräß weed nimohlß jebruch
wääde, ih dat se beshtätish eß.

Wänn_de jäz nöüjieresh jewoode beß un wellß
weßße, wat met_de {{SITENAME}} loßß eß,
do  jang met Dingem Brauser noh:
{{FULLURL:{{MediaWiki:Mainpage}}}}
un luer Derr_et aan.

Enne schööne Jrooß fun de {{SITENAME}}.

-- 
{{SITENAME}}: {{fullurl:{{Mediawiki:mainpage}}}}',
'tryexact'              => 'Forsöök en akkoraate Överëijnstemmungk:',
'searchfulltext'        => 'Söök dorrsh dä jannze Täxx',
'createarticle'         => 'Atikkel ėnnreshte',
'scarytranscludedisabled'=> '[Et Ennbinge për Ingerwikki eß affjeschalldt]',
'scarytranscludefailed' => '[De Schabloon „$1“ en_ze_binge hät nit jeflupp]',
'scarytranscludetoolong'=> '[Schadt, dė URL eß ze lang]',
'trackbackbox'          => '<div id="mw_trackbacks">
Trackbacks för dä Atikkel hee:<br />
„<strong>$1</strong>“
</div>',
'trackbackremove'       => ' ([$1 Fottschmiiße])',
'trackbacklink'         => 'Trackback',
'trackbackdeleteok'     => 'Trackback eß fottjeschmeßße.',
'deletedwhileediting'   => '<strong>Opjepaßß:</strong> Di Sigk wood fottjeschmeßße, nohdämm Do alld aanjefange häß, 
draan ze Änndere.',
'confirmrecreate'       => 'Dä Medmaacher [[User:$1|$1]] (→[[User talk:$1|däm_singe Klaafs]]) hät di Sigk 
fottjeschmeßße, nohdämm Do do drahn et Änndere aanjefange häß. Dä Jrund:
: „<i>$2</i>“
Wellß Do jäz medd_en nöüe Väsjohn di Sigk nöü aanlääje?',
'recreate'              => 'Zerrögk_holle',
'tooltip-recreate'      => 'En fottjeschmeßßenne Sigk widderholle',
'unit-pixel'            => 'px',
'redirectingto'         => 'Lëijdt öm op „[[$1]]“...',
'confirm_purge'         => 'Donn dä Zwesche_Shpëijsher fö_di Sigk forschmiiße?

$1',
'confirm_purge_button'  => 'Jo — loßß jonn!',
'youhavenewmessagesmulti'=> 'Do häßß nöü Nohrishte op $1',
'searchcontaining'      => 'Söök noh Atikkelle, wo „$1“ em Täxx fürkütt.',
'searchnamed'           => 'Söök noh Atikkelle, wo „$1“ em Name fürkütt.',
'articletitles'         => 'Atikkelle di met „$1“ aanfange',
'hideresults'           => 'Äjepniß foshtäshe',
'displaytitle'          => '(Lėngkß op di Sigk allß [[$1]])',
'loginlanguagelabel'    => 'Shprooch: $1',

# Multipage image navigation
'imgmultipageprev'      => '← de Sigk do_füür',
'imgmultipagenext'      => 'de Sigk do_noh →',
'imgmultigo'            => 'Loßß jonn!',
'imgmultigotopre'       => 'Jangk op_de Sigk',

# Table pager
'ascending_abbrev'      => 'opwäätß zottėet',
'descending_abbrev'     => 'raffkaz zottėet',
'table_pager_next'      => 'De näkßte Sigk',
'table_pager_prev'      => 'De Sigk do_füür',
'table_pager_first'     => 'De eezde Sigk',
'table_pager_last'      => 'De läzde Sigk',
'table_pager_limit'     => 'Zëijsh $1 pro Sigk',
'table_pager_limit_submit' => 'Loßß jonn!',
'table_pager_empty'     => 'Këij Äjepniß',

);

?>
