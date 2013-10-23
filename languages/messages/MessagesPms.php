<?php
/** Piedmontese (Piemontèis)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Borichèt
 * @author Bèrto 'd Sèra
 * @author Dragonòt
 * @author Geitost
 * @author Kaganer
 * @author MaxSem
 * @author SabineCretella
 * @author Teak
 * @author The Evil IP address
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
 * @author לערי ריינהארט
 */

$fallback = 'it';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Discussion',
	NS_USER             => 'Utent',
	NS_USER_TALK        => 'Ciaciarade',
	NS_PROJECT_TALK     => 'Discussion_ant_sla_$1',
	NS_FILE             => 'Figura',
	NS_FILE_TALK        => 'Discussion_dla_figura',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Discussion_dla_MediaWiki',
	NS_TEMPLATE         => 'Stamp',
	NS_TEMPLATE_TALK    => 'Discussion_dlë_stamp',
	NS_HELP             => 'Agiut',
	NS_HELP_TALK        => 'Discussion_ant_sl\'agiut',
	NS_CATEGORY         => 'Categorìa',
	NS_CATEGORY_TALK    => 'Discussion_ant_sla_categorìa',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Anliure con la sotliniadura',
'tog-justify' => 'Paràgraf: giustificà',
'tog-hideminor' => "Stërmé le modìfiche cite ant sla pàgina dj'ùltime modìfiche",
'tog-hidepatrolled' => "Stërmé le modìfiche dzorvejà ant j'ùltime modìfiche",
'tog-newpageshidepatrolled' => 'Stërmé le pàgine dzorvejà da la lista dle pàgine neuve',
'tog-extendwatchlist' => "Slarghé la lista ëd ròba che as ten sot-euj an manera che a la smon-a tute le modìfiche, nen mach j'ùltime",
'tog-usenewrc' => "Argropré le modìfiche për pàgina ant j'ùltime modìfiche e ant la lista dla ròba ch'as ten sot-euj",
'tog-numberheadings' => 'Tìtoj ëd paràgraf<br />che as nùmero daspërlor',
'tog-showtoolbar' => "Smon-e la bara dj'utiss ëd modìfica",
'tog-editondblclick' => 'Dobia sgnacà për modifiché la pàgina',
'tog-editsection' => "Abilité le modìfiche ëd session con j'anliure [modifiché]",
'tog-editsectiononrightclick' => 'Abilité la modìfica dle session ën sgnacand-je ansima ai tìtoj col tast drit dël rat',
'tog-showtoc' => "Smon-e la tàula dij contnù (për le pàgine che l'han pì che 3 session)",
'tog-rememberpassword' => "Visesse ëd mia ciav ansima a 's navigador (për al pi $1 {{PLURAL:$1|di}})",
'tog-watchcreations' => "Gionté le pàgine che i creo mi e j'archivi che i cario mi a la lista ëd lòn che im ten-o sot-euj",
'tog-watchdefault' => "Gionté le pàgine e j'archivi che i modìfico mi a la lista dle ròbe ch'i ten-o sot-euj",
'tog-watchmoves' => "Gionté le pàgine e j'archivi che i tramudo a lòn che im ten-o sot-euj",
'tog-watchdeletion' => "Gionté le pàgine e j'archivi che i scancelo via a la lista ëd lòn che im ten-o sot-euj",
'tog-minordefault' => 'Marché tute le modìfiche coma cite coma predefinission',
'tog-previewontop' => 'Smon-e la preuva dzora al quàder ëd modìfica dël test e nen sota',
'tog-previewonfirst' => 'Smon-e na preuva la prima vira che as fa na modìfica',
'tog-nocache' => 'Disabilité la memòria local ëd le pàgine dël navigador',
'tog-enotifwatchlistpages' => "Mandeme un mëssagi an pòsta eletrònica quand a-i son dle modìfiche a le pàgine ch'im ten-o sot-euj",
'tog-enotifusertalkpages' => 'Mandeme un mëssagi ëd pòsta eletrònica quand a-i son dle modìfiche a mia pàgina dle ciaciarade',
'tog-enotifminoredits' => "Mandeme un mëssagi an pòsta eletrònica bele che për le modìfiche cite dle pàgine o dj'archivi",
'tog-enotifrevealaddr' => 'Lassé che a së s-ciàira mia adrëssa ëd pòsta eletrònica ant ij mëssagi ëd notìfica',
'tog-shownumberswatching' => "Smon-e ël nùmer d'utent che as ten-o la pàgina sot-euj",
'tog-oldsig' => 'Firma esistenta:',
'tog-fancysig' => "Traté la firma com dël test wiki (sensa n'anliura automàtica)",
'tog-uselivepreview' => "Dovré la fonsion ''Preuva dal viv'' (sperimental)",
'tog-forceeditsummary' => "Ciamé conferma se ël resumé dla modìfica a l'é veujd",
'tog-watchlisthideown' => 'Stërmé mie modìfiche ant la ròba che im ten-o sot-euj',
'tog-watchlisthidebots' => 'Stërmé le modìfiche fàite daj trigomiro ant la lista dle ròbe che im ten-o sot-euj',
'tog-watchlisthideminor' => "Stërmé le modìfiche cite da 'nt lòn che im ten-o sot-euj",
'tog-watchlisthideliu' => "Stërmé le modìfiche fàite da j'utent registrà ant la lista dle ròbe che im ten-o sot-euj",
'tog-watchlisthideanons' => "Stërmé le modìfiche fàite da j'utent anònim da 'nt lòn che im ten-o sot-euj",
'tog-watchlisthidepatrolled' => "Stërmé le modìfiche dzorvejà da 'nt la ròba che im ten-o sot-euj",
'tog-ccmeonemails' => "Mandeme na còpia dij mëssagi ëd pòsta eletrònica che i-j mando a j'àotri utent",
'tog-diffonly' => 'Smon-e pa ël contnù dle pàgine sota le diferense',
'tog-showhiddencats' => 'Smon-e le categorìe stërmà',
'tog-noconvertlink' => "Disativé la conversion dij tìtoj ant j'anliure",
'tog-norollbackdiff' => "Fé nen vëdde le diferense apress d'avèj ripristinà",
'tog-useeditwarning' => 'Aviseme quand che i chito na pàgina ëd modìfiche con dle modìfiche nen salvà',
'tog-prefershttps' => "Dovré sempe na conession sigura pr'ësté andrinta al sistema",

'underline-always' => 'Sempe',
'underline-never' => 'Mai',
'underline-default' => 'Stàndard dël navigator o dël tema',

# Font style option in Special:Preferences
'editfont-style' => "Stil dij caràter ëd l'àrea ëd modìfica:",
'editfont-default' => 'Stàndard dël navigator',
'editfont-monospace' => 'Caràter mono-spassià',
'editfont-sansserif' => 'Caràter sensa piòte',
'editfont-serif' => 'Caràter con piòte',

# Dates
'sunday' => 'dumìnica',
'monday' => 'lùn-es',
'tuesday' => 'màrtes',
'wednesday' => 'merco',
'thursday' => 'giòbia',
'friday' => 'vënner',
'saturday' => 'saba',
'sun' => 'dum',
'mon' => 'lùn',
'tue' => 'màr',
'wed' => 'mer',
'thu' => 'giò',
'fri' => 'vën',
'sat' => 'sab',
'january' => 'gené',
'february' => 'fërvé',
'march' => 'mars',
'april' => 'avril',
'may_long' => 'maj',
'june' => 'giugn',
'july' => 'luj',
'august' => 'ost',
'september' => 'stèmber',
'october' => 'otóber',
'november' => 'novèmber',
'december' => 'dzèmber',
'january-gen' => 'gené',
'february-gen' => 'fërvé',
'march-gen' => 'mars',
'april-gen' => 'avril',
'may-gen' => 'maj',
'june-gen' => 'giugn',
'july-gen' => 'luj',
'august-gen' => 'ost',
'september-gen' => 'stèmber',
'october-gen' => 'otóber',
'november-gen' => 'novèmber',
'december-gen' => 'dzèmber',
'jan' => 'gen',
'feb' => 'fër',
'mar' => 'mar',
'apr' => 'avr',
'may' => 'maj',
'jun' => 'giu',
'jul' => 'luj',
'aug' => 'ost',
'sep' => 'stè',
'oct' => 'otó',
'nov' => 'nov',
'dec' => 'dzè',
'january-date' => '$1 gené',
'february-date' => '$1 fërvé',
'march-date' => '$1 mars',
'april-date' => '$1 avril',
'may-date' => '$1 maj',
'june-date' => '$1 giugn',
'july-date' => '$1 luj',
'august-date' => '$1 ost',
'september-date' => '$1 stèmber',
'october-date' => '$1 otóber',
'november-date' => '$1 novèmber',
'december-date' => '$1 dzèmber',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categorìa|Categorìe}}',
'category_header' => 'Pàgine ant la categorìa «$1»',
'subcategories' => 'Sot-categorìe',
'category-media-header' => 'Archivi multimojen ant la categorìa «$1»',
'category-empty' => "''Al dì d'ancheuj la categorìa a l'ha pa andrinta nì 'd pàgine, nì d'archivi multimojen.''",
'hidden-categories' => '{{PLURAL:$1|Categorìa stërmà|Categorìe stërmà}}',
'hidden-category-category' => 'Categorìe stërmà',
'category-subcat-count' => "{{PLURAL:$2|Sta categorìa-sì a l'ha mach la sot-categorìa sì-dapress.|Sta categorìa-sì a l'ha {{PLURAL:$1|la sot-categorìa|le $1 sot-categorìe}} sì-dapress, ëd $2 ch'a-i në j'é an total.}}",
'category-subcat-count-limited' => "Sta categorìa-sì a l'ha {{PLURAL:$1|la sot-categorìa|le $1 sot-categorìe}} sì-dapress.",
'category-article-count' => "{{PLURAL:$2|Sta categorìa-sì a l'ha mach sta pàgina.|Ant sta categorìa-sì a-i {{PLURAL:$1|intra mach sta pàgina|intro $1 pàgine}} ëd $2 ch'a-i në j'é an total.}}",
'category-article-count-limited' => 'Ant sta categorìa-sì a-i {{PLURAL:$1|resta mach sta pàgina|resto $1 pàgine}}.',
'category-file-count' => "{{PLURAL:$2|Sta categorìa-sì a l'ha nomach st'archivi.|Sta categorìa-sì a l'ha {{PLURAL:$1|n'|$1}} archivi, ëd $2 ch'a-i në j'é an total.}}",
'category-file-count-limited' => "Ant sta categorìa-sì a-i {{PLURAL:$1|intra mach st'|intro $1}} archivi.",
'listingcontinuesabbrev' => 'anans',
'index-category' => 'Pàgine indicisà',
'noindex-category' => 'Pàgine nen indicisà',
'broken-file-category' => "Pàgine con dle liure cioche a d'archivi",

'about' => 'A propòsit ëd',
'article' => 'Pàgina ëd contnù',
'newwindow' => '(as deurb ant na fnestra neuva)',
'cancel' => 'Anulé',
'moredotdotdot' => 'Ëd pì...',
'morenotlisted' => "Costa lista a l'é nen completa.",
'mypage' => 'Pàgina',
'mytalk' => 'Ciaciarade',
'anontalk' => "Ciaciarade për st'adrëssa IP-sì",
'navigation' => 'Navigassion',
'and' => '&#32;e',

# Cologne Blue skin
'qbfind' => 'Trové',
'qbbrowse' => 'Sfojé',
'qbedit' => 'Modifiché',
'qbpageoptions' => 'Costa pàgina',
'qbmyoptions' => 'Mie pàgine',
'qbspecialpages' => 'Pàgine speciaj',
'faq' => 'Chestion frequente',
'faqpage' => 'Project:Soèns An Ciamo',

# Vector skin
'vector-action-addsection' => "Gionté n'argoment",
'vector-action-delete' => 'Scancelé',
'vector-action-move' => 'Tramudé',
'vector-action-protect' => 'Protege',
'vector-action-undelete' => 'Arcuperé',
'vector-action-unprotect' => 'Cangé la protession',
'vector-simplesearch-preference' => "Abilité la bara d'arserca semplificà (mach për la pel Vector)",
'vector-view-create' => 'Creé',
'vector-view-edit' => 'Modifiché',
'vector-view-history' => 'Smon-e la stòria',
'vector-view-view' => 'Lese',
'vector-view-viewsource' => 'Vëdde la sorgiss',
'actions' => 'Assion',
'namespaces' => 'Spassi nominaj',
'variants' => 'Variant',

'navigation-heading' => 'Lista ëd navigassion',
'errorpagetitle' => 'Eror',
'returnto' => 'Torné andré a $1.',
'tagline' => 'Da {{SITENAME}}.',
'help' => 'Agiut',
'search' => 'Sërché',
'searchbutton' => 'Sërché',
'go' => 'Andé',
'searcharticle' => 'Andé',
'history' => 'Version pì veje',
'history_short' => 'Stòria',
'updatedmarker' => "agiornà da l'ùltima vira che i son passà",
'printableversion' => 'Version bon-a për stampé',
'permalink' => 'Anliura fissa',
'print' => 'Stampé',
'view' => 'Vardé',
'edit' => 'Modifiché',
'create' => 'Creé',
'editthispage' => 'Modifiché costa pàgina',
'create-this-page' => 'Creé sta pàgina',
'delete' => 'Scancelé',
'deletethispage' => 'Scancelé sa pàgina',
'undeletethispage' => 'Arcuperé sta pàgina',
'undelete_short' => 'Arcuperé {{PLURAL:$1|na modìfica|$1 modìfiche}}',
'viewdeleted_short' => 'Vardé {{PLURAL:$1|na modìfica scancelà|$1 modìfiche scancelà}}',
'protect' => 'Protege',
'protect_change' => 'modifiché',
'protectthispage' => 'Protege sta pàgina-sì',
'unprotect' => 'Cangé la protession',
'unprotectthispage' => 'Cangé la protession ëd sa pàgina',
'newpage' => 'Pàgina neuva',
'talkpage' => 'Discussion',
'talkpagelinktext' => 'discussion',
'specialpage' => 'Pàgina special',
'personaltools' => 'Utiss përsonaj',
'postcomment' => 'Session neuva',
'articlepage' => 'Vëdde la pàgina ëd contnù',
'talk' => 'Discussion',
'views' => 'Vìsite',
'toolbox' => 'Utiss',
'userpage' => 'Che a varda la pàgina Utent',
'projectpage' => 'Che a varda la pàgina ëd proget',
'imagepage' => "Vëdde la pàgina dl'archivi",
'mediawikipage' => 'Mostré ël mëssagi',
'templatepage' => 'Vëdde lë stamp',
'viewhelppage' => "Smon-e la pàgina d'agiut",
'categorypage' => 'Vëdde la categorìa',
'viewtalkpage' => 'Vardé la discussion',
'otherlanguages' => 'Àutre lenghe',
'redirectedfrom' => '(Ridiression da $1)',
'redirectpagesub' => 'Pàgina ëd ridiression',
'lastmodifiedat' => "Modificà l'ùltima vira ai $1 a $2.",
'viewcount' => "St'artìcol-sì a l'é stàit lesù {{PLURAL:$1|na vira|$1 vire}}.",
'protectedpage' => 'Pàgina proteta',
'jumpto' => 'Andé a:',
'jumptonavigation' => 'navigassion',
'jumptosearch' => 'arserché',
'view-pool-error' => "An dëspias, ij servent a son motobin carià al moment.
Tròpi utent a son an camin ch'a preuvo a lese sta pàgina-sì.
Për piasì, ch'a speta un pòch prima ëd prové torna a lese costa pàgina.

$1",
'pool-timeout' => "Ël temp a l'é finì antramentre ch'a së spetava la saradura",
'pool-queuefull' => "La coa ëd travaj a l'é pien-a",
'pool-errorunknown' => 'Eror pa conossù',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'A propòsit ëd {{SITENAME}}',
'aboutpage' => 'Project:A propòsit',
'copyright' => "Ël contnù a resta disponìbil sota $1 gavà ch'a sia marcà an n'àutra manera.",
'copyrightpage' => "{{ns:project}}:Drit d'autor",
'currentevents' => 'Neuve',
'currentevents-url' => 'Project:Neuve',
'disclaimers' => 'Avertense',
'disclaimerpage' => 'Project:Avertense generaj',
'edithelp' => 'Agiut a la modìfica',
'helppage' => 'Help:Contnù',
'mainpage' => 'Intrada',
'mainpage-description' => 'Intrada',
'policy-url' => 'Project:Régole',
'portal' => 'Piòla',
'portal-url' => 'Project:Piòla',
'privacy' => 'Régole ëd confidensialità',
'privacypage' => 'Project:Régole ëd confidensialità',

'badaccess' => 'Përmess nen giust',
'badaccess-group0' => "A l'ha pa ij përmess dont a fa dë manca për fé st'operassion-sì.",
'badaccess-groups' => "Costa funsion-sì a l'é riservà a j'utent che a sio almanch ant {{PLURAL:$2|la partìa|un-a dle partìe}}: $1.",

'versionrequired' => 'A-i va për fòrsa la version $1 ëd MediaWiki',
'versionrequiredtext' => 'Për dovré sta pàgina-sì a-i va la version $1 dël programa MediaWiki. Che a varda [[Special:Version|la pàgina dle version]].',

'ok' => 'Va bin',
'retrievedfrom' => 'Pijàit da «$1»',
'youhavenewmessages' => "A l'ha $1 ($2).",
'newmessageslink' => 'ëd mëssagi neuv',
'newmessagesdifflink' => 'ùltima modìfica',
'youhavenewmessagesfromusers' => "A l'ha $1 da {{PLURAL:$3|n'autr utent|$3 utent}} ($2).",
'youhavenewmessagesmanyusers' => "A l'ha $1 da vàire utent ($2).",
'newmessageslinkplural' => '{{PLURAL:$1|un mëssagi neuv|$1 mëssagi neuv}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|ùltima modìfica|ùltime modìfiche}}',
'youhavenewmessagesmulti' => "A l'ha dij neuv mëssagi an $1",
'editsection' => 'modifiché',
'editold' => 'modifiché',
'viewsourceold' => 'vëdde la sorgiss',
'editlink' => 'modifiché',
'viewsourcelink' => 'vëdde la sorgiss',
'editsectionhint' => 'Modifiché la session: $1',
'toc' => 'Contnù',
'showtoc' => 'smon-e',
'hidetoc' => 'stërmé',
'collapsible-collapse' => 'Saré',
'collapsible-expand' => 'deurbe',
'thisisdeleted' => 'Veul-lo vardé ò ripristiné $1?',
'viewdeleted' => 'Veul-lo vardé $1?',
'restorelink' => '{{PLURAL:$1|na modìfica scancelà|$1 modìfiche scancelà}}',
'feedlinks' => 'Fluss:',
'feed-invalid' => "Sòrt ëd fluss d'abonament nen vàlida.",
'feed-unavailable' => 'Ij fluss ëd neuve a son nen disponìbij',
'site-rss-feed' => 'Emission RSS $1',
'site-atom-feed' => 'Emission Atom ëd $1',
'page-rss-feed' => 'Emission RSS ëd «$1»',
'page-atom-feed' => 'Emission Atom ëd «$1»',
'red-link-title' => "$1 (pàgina ch'a esist pa)",
'sort-descending' => 'Ordinament an caland',
'sort-ascending' => 'Ordinament an chërsend',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pàgina',
'nstab-user' => "Pàgina dl'utent",
'nstab-media' => 'Pàgina multimojen',
'nstab-special' => 'Pàgina special',
'nstab-project' => 'Pàgina ëd proget',
'nstab-image' => 'Archivi',
'nstab-mediawiki' => 'Mëssagi',
'nstab-template' => 'Stamp',
'nstab-help' => 'Agiut',
'nstab-category' => 'Categorìa',

# Main script and global functions
'nosuchaction' => 'Operassion nen arconossùa',
'nosuchactiontext' => "L'operassion che a l'ha ciamà ant l'anliura a l'é nen arconossùa.
A peul esse che a l'abie batù mal l'adrëssa, o che a sia andàit dapress a n'anliura nen giusta.
Sossì a podrìa ëdcò esse un givo andrinta al programa dovrà da {{SITENAME}}.",
'nosuchspecialpage' => "A-i é gnun-a pàgina special tan-me cola che chiel a l'ha ciamà.",
'nospecialpagetext' => "<strong>A l'ha ciamà na pàgina special ch'a esist pa.</strong>

Na lista dle pàgine speciaj bon-e a peul esse trovà ambelessì [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Eror',
'databaseerror' => 'Eror ant la base ëd dat',
'databaseerror-text' => "A l'é rivaje n'eror d'arcesta ëd base ëd dàit. Sòn a podrìa ven-e da 'n givo ant ël programa.",
'databaseerror-textcl' => "A l'é rivaje n'eror d'arcesta ëd base ëd dàit.",
'databaseerror-query' => 'Arcesta: $1',
'databaseerror-function' => 'Fonsion: $1',
'databaseerror-error' => 'Eror: $1',
'laggedslavemode' => "'''Avis:''' la pàgina a podrìa ëdcò nen mostré le modìfiche pi recente.",
'readonly' => 'Acess a la base ëd dat sërà për chèich temp.',
'enterlockreason' => 'Che a buta na rason për ël blocagi, con andrinta data e ora ëd quand che a stima che a sarà gavà.',
'readonlytext' => "La base ëd dat për adess a l'é blocà, e as peulo pa fesse nì dle neuve imission, nì dle modìfiche, con tute le probabilità për n'operassion ëd manutension dël servent. Se a l'é parèj, motobin ampressa la base a sarà torna duvèrta.

L'aministrator che a l'ha blocala a l'ha lassà sta spiegassion: $1",
'missing-article' => "La base ëd dàit a l'ha nen trovà ël test ëd na pàgina che a l'avrìa dovù trové, ciamà «$1» $2.

Sossì ëd sòlit a l'é causà përché a l'é ciamasse na diferensa o n'anliura stòrica a na pàgina scancelà.

Se cost a l'é nen ël cas, a podrìa avèj trovà un givo ant ël programa.
Për piasì, ch'a lo signala a n'[[Special:ListUsers/sysop|aministrator]], sensa dësmentié l'adrëssa dla liura.",
'missingarticle-rev' => '(nùmer ëd revision: $1)',
'missingarticle-diff' => '(Dif: $1, $2)',
'readonly_lag' => "La base ëd dat a l'é staita blocà n'automàtich antramentr che le màchine dël sircùit secondari as buto an pari con cole dël prinsipal",
'internalerror' => 'Eror intern',
'internalerror_info' => 'Eror antern: $1',
'fileappenderrorread' => 'As peul pa les-se «$1» durant la gionta.',
'fileappenderror' => "A l'é pa podusse taché «$1» a «$2».",
'filecopyerror' => "A l'é pa stàit possìbil copié l'archivi «$1» coma «$2».",
'filerenameerror' => "A l'é pa podusse cangeje nòm a l'archivi «$1» an «$2».",
'filedeleteerror' => "A l'é pa podusse scancelé l'archivi «$1».",
'directorycreateerror' => "A l'é pa podusse creé ël dossié «$1».",
'filenotfound' => "A l'é pa trovasse l'archivi «$1».",
'fileexistserror' => "As peul pa scriv-se l'archivi «$1»: a-i é già.",
'unexpected' => 'Valor che i së spetavo pa: «$1»=«$2».',
'formerror' => "Eror: A l'é nen podusse mandé ël formolari.",
'badarticleerror' => "N'operassion parèj as peul pa fesse ansima a sta pàgina-sì.",
'cannotdelete' => "La pàgina o l'archivi «$1» a l'ha pa podù esse scancelà.
Peul desse ch'a l'é già stàit ëscancelà da cheidun d'àutr.",
'cannotdelete-title' => 'As peul pa scancelesse la pàgina «$1»',
'delete-hook-aborted' => "Scancelassion anulà da n'estension.
A l'ha smonù gnun-e spiegassion.",
'no-null-revision' => 'Impossìbil creé na neuva revision veuida për la pàgina « $1 »',
'badtitle' => 'Tìtol nen giust',
'badtitletext' => "Ël tìtol ëd la pàgina che a l'ha ciamà a l'era nen giust, veuid, o un tìtol nen lijà ëd fasson giusta antra le lenghe o antra le wiki. A podrìa conten-e un o pi caràter ch'a peulo nen esse dovrà ant ij tìtoj.",
'perfcached' => "Ij dat sì-dapress a sòn ëstàit memorisà an local e a peulo esse nen agiornà. Al pi {{PLURAL:$1|n'arzultà a l'é disponìbil|$1 arzultà a son disponìbij}} ant la memòria local.",
'perfcachedts' => "Ij dat sì-dapress a son ëstàit memorisà an local, e a son ëstàit agiornà l'ùltima vira ël $1. Al pi {{PLURAL:$4|n'arzultà a l'é disponìbil|$4 arzultà a son disponìbij}} ant la memòria local.",
'querypage-no-updates' => "J'agiornament për sta pàgina-sì për adess a marcio nen. Ij dat ambelessì a saran nen agiornà.",
'wrong_wfQuery_params' => 'Paràmetro nen giust për wfQuery()<br />
Funsion: $1<br />
Arcesta: $2',
'viewsource' => 'Vardé la sorgiss',
'viewsource-title' => 'Vëdde la sorgiss ëd $1',
'actionthrottled' => 'Assion limità',
'actionthrottledtext' => "Për evité che 'd gent ò 'd màchine an carìo dla rumenta, st'assion-sì as peul nen fesse tròp ëd soèns, e chiel a l'ha arpetula tròpe vire. Ch'a sia gentil, ch'a preuva torna antra dontré minute.",
'protectedpagetext' => "Sta pàgina-sì a l'è stàita blocà për evité 'd modìfiche o d'àutre assion.",
'viewsourcetext' => 'A peul vardé e copié la sorgiss dë sta pàgina:',
'viewyourtext' => "A peul vëdde e copié la sorgiss ëd '''soe modìfiche''' a costa pàgina-sì:",
'protectedinterface' => "Costa pàgina-sì a l'ha andrinta un cheicòs che a fa part dl'antërfacia dël programa che a deuvro tùit; donca a l'é proteta për evité che a-i rivo dle ròbe brute.
Për gionté o modifiché dle tradussion për tute le wiki, për piasì ch'a deuvra [//translatewiki.net/ translatewiki.net], ël proget ëd localisassion ëd MediaWiki.",
'editinginterface' => "'''Dossman!''' A l'é dapress ch'a modìfica na pàgina ch'as deuvra për generé ël test dl'antërfacia dël programa. 
Le modìfiche a sta pàgina a toco l'aparensa ëd l'antërfacia utent a tuti j'utent dzora a sta wiki. 
Për gionté o cangé dle tradussion për tute le wiki, për piasì ch'a deuvra [//translatewiki.net/translatewiki.net], ël proget ëd localisassion ëd MediaWiki.",
'cascadeprotected' => 'Ant sta pàgina-sì as peulo pa fé ëd modìfiche, përché a-i intra ant {{PLURAL:$1|la pàgina|le pàgine}} butà sot a protession con la fonsion «a tombé» viscà ansima a: $2',
'namespaceprotected' => "A l'ha nen ël përmess dë feje dle modìfiche a le pàgine dlë spassi nominal '''$1'''.",
'customcssprotected' => "Ch'a varda ch'a l'ha pa ël përmess ëd modifiché sta pàgina ëd CSS, për via ch'a l'ha andrinta ij gust ëd n'àutr utent.",
'customjsprotected' => "Ch'a varda ch'a l'ha pa ël përmess ëd modifiché sta pàgina ëd JavaScript, për via ch'a l'ha andrinta ij gust ëd n'àutr utent.",
'mycustomcssprotected' => "A l'ha pa ël përmess ëd modifiché costa pàgina CSS.",
'mycustomjsprotected' => "A l'ha pa ël përmess ëd modifiché costa pàgina JavaScript.",
'myprivateinfoprotected' => "A l'ha pa ël përmess ëd modifiché soe anformassion privà.",
'mypreferencesprotected' => "A l'ha pa ël përmess ëd modifiché ij sò gust.",
'ns-specialprotected' => 'As peulo nen modifichesse le pàgine speciaj.',
'titleprotected' => "La creassion ëd pàgine con ës tìtol-sì a l'é stàita proibìa da [[User:$1|$1]].
Coma rason a l'ha butà: «''$2''».",
'filereadonlyerror' => "As peul pa modifichesse l'archivi «$1» përchè ël depòsit d'archivi «$2» a l'é an sola letura.

L'aministrator ch'a l'ha blocalo a l'ha lassà sta spiegassion: «$3».",
'invalidtitle-knownnamespace' => "Tìtol ch'a va nen bin con lë spassi nominal «$2» e ël test «$3»",
'invalidtitle-unknownnamespace' => 'Tìtol pa bon con nùmer dë spassi nominal $1 e test «$2» sconossù',
'exception-nologin' => 'Nen rintrà ant ël sistema',
'exception-nologin-text' => "Costa pàgina o assion a l'ha damanca ch'a sia rintrà an costa wiki.",

# Virus scanner
'virus-badscanner' => "Configurassion falà: antivìrus nen conossù: ''$1''",
'virus-scanfailed' => 'scansion falìa (còdes $1)',
'virus-unknownscanner' => 'antivìrus nen conossù:',

# Login and logout pages
'logouttext' => "'''A l'é surtì da 'nt ël sistema.'''

Ch'a nòta che chèiche pàgine a peulo continué a esse visualisà com s'a fussa ancor ant ël sistema, fin ch'a scancela nen la memòria local ëd sò navigador.",
'welcomeuser' => 'Bin ëvnù, $1!',
'welcomecreation-msg' => "Sò cont a l'é stàit creà.
Che a dësmentia pa ëd cambié ij [[Special:Preferences|sò gust për {{SITENAME}}]].",
'yourname' => 'Stranòm:',
'userlogin-yourname' => 'Stranòm',
'userlogin-yourname-ph' => "Ch'a buta sò stranòm",
'createacct-another-username-ph' => 'Buté lë stranòm',
'yourpassword' => 'Soa ciav:',
'userlogin-yourpassword' => 'Ciav',
'userlogin-yourpassword-ph' => "Ch'a buta soa ciav",
'createacct-yourpassword-ph' => "Ch'a buta na ciav",
'yourpasswordagain' => 'Che a bata torna soa ciav:',
'createacct-yourpasswordagain' => "Ch'a confirma la ciav",
'createacct-yourpasswordagain-ph' => "Ch'a buta torna la ciav",
'remembermypassword' => "Visesse ëd mia ciav ansima a 's navigador (për al pi $1 {{PLURAL:$1|di}})",
'userlogin-remembermypassword' => 'Ten-me andrinta al sistema',
'userlogin-signwithsecure' => 'Dovré na conession sigura',
'yourdomainname' => 'Sò domini:',
'password-change-forbidden' => 'A peul pa modifiché le ciav dzora a costa wiki.',
'externaldberror' => "Ò che a l'é rivaje n'eror con la base ëd dàit d'autenticassion esterna, ò pura a l'é chiel che a l'é nen autorisà a agiornesse sò cont estern.",
'login' => 'Conession',
'nav-login-createaccount' => 'Creé un cont o rintré ant ël sistema',
'loginprompt' => 'Che a varda mach che a venta avèj ij bëscotin abilità për podèj rintré an {{SITENAME}}.',
'userlogin' => 'Creé un cont o rintré ant ël sistema',
'userloginnocreate' => 'Conession',
'logout' => "Seurte da 'nt ël sistema",
'userlogout' => 'Dësconession',
'notloggedin' => 'Nen rintrà ant ël sistema',
'userlogin-noaccount' => 'Ha-lo nen un cont?',
'userlogin-joinproject' => "Ch'as gionza a {{SITENAME}}",
'nologin' => 'Ha-lo ancó nen un cont? $1.',
'nologinlink' => 'Creé un cont',
'createaccount' => 'Creé un cont',
'gotaccount' => 'Ha-lo già un sò cont? $1.',
'gotaccountlink' => "Ch'a rintra ant ël sistema",
'userlogin-resetlink' => "A l'ha dësmentià ij sò detaj për intré ant ël sistema?",
'userlogin-resetpassword-link' => 'Riamposté la ciav',
'helplogin-url' => 'Help:Conession',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Agiut con la conession]]',
'userlogin-loggedin' => "A l'é già rintrà an ël sistema tanme {{GENDER:$1|$1}}.
Ch'a deuvra ël formolari sì-sota për rintré coma n'àutr n'utent.",
'userlogin-createanother' => "Creé n'àutr cont",
'createacct-join' => "Ch'a anserissa soe anformassion sì-sota.",
'createacct-another-join' => "Anserì j'anformassion dël cont neuv sì-sota.",
'createacct-emailrequired' => 'Adrëssa ëd pòsta eletrònica',
'createacct-emailoptional' => 'Adrëssa ëd pòsta eletrònica (opsional)',
'createacct-email-ph' => "Ch'a buta soa adrëssa ëd pòsta eletrònica",
'createacct-another-email-ph' => "Buté l'adrëssa ëd pòsta eletrònica",
'createaccountmail' => "Dovré na ciav temporania d'ancàpit e mandela a l'adrëssa ëd pòsta eletrònica spessificà",
'createacct-realname' => 'Nòm ver (opsional)',
'createaccountreason' => 'Rason:',
'createacct-reason' => 'Rason',
'createacct-reason-ph' => "Përchè a crea n'àutr cont",
'createacct-captcha' => 'Contròl ëd sigurëssa',
'createacct-imgcaptcha-ph' => "Ch'a anserissa ël test ch'a s-ciàira sì-dzora",
'createacct-submit' => "Ch'a crea sò cont",
'createacct-another-submit' => "Creé n'àutr cont",
'createacct-benefit-heading' => "{{SITENAME}} a l'é fàit da 'd gent coma chiel.",
'createacct-benefit-body1' => '{{PLURAL:$1|modìfica|modìfiche}}',
'createacct-benefit-body2' => '{{PLURAL:$1|pàgina|pàgine}}',
'createacct-benefit-body3' => '{{PLURAL:$1|contributor}} recent',
'badretype' => "Le doe ciav che a l'ha scrivù a resto diferente antra 'd lor.",
'userexists' => "Lë stranòm anserì a l'é già dovrà.
Për piasì ch'a serna në stranòm diferent.",
'loginerror' => 'Eror ën rintrand ant ël sistema',
'createacct-error' => 'Eror durant la creassion dël cont',
'createaccounterror' => "A l'é nen podusse creé ël cont: $1",
'nocookiesnew' => "Sò cont a l'é duvèrt, ma chiel a l'ha nen podù rintré ant ël sistema.
{{SITENAME}} a deuvra ij bëscotin për fé rintré la gent ant sò sistema. Belavans chiel a l'ha pa ij bëscotin abilità.
Për piasì, che as j'abìlita e peuj che a preuva torna a rintré con sò stranòm e soa ciav.",
'nocookieslogin' => "{{SITENAME}} a deuvra ij bëscotin për fé rintré la gent ant sò sistema. Belavans chiel a l'ha pa ij bëscotin abilità. Për piasì, che a j'abìlita e peuj che a preuva torna.",
'nocookiesfornew' => "Ël cont utent a l'é pa stàit creà, përchè i l'oma pa podù confirmé soa sorgiss.
Ch'a contròla d'avèj ij bëscotin abilità, ch'a caria torna la pàgina e ch'a preuva torna.",
'noname' => "A l'ha nen ëspessificà në stranòm vàlid.",
'loginsuccesstitle' => "Compliment! A l'é pen-a rintrà ant ël sistema.",
'loginsuccess' => "'''Adess a l'é colegà a {{SITENAME}} con lë stranòm «$1».'''",
'nosuchuser' => "A-i é pa gnun utent con lë stranòm «$1».
Jë stranòm ëd j'utent a son sensìbij a le majùscole.
Ch'a contròla ël nòm che a l'ha batù, o [[Special:UserLogin/signup|ch'a crea un neuv cont]].",
'nosuchusershort' => "A-i é pa gnun utent che as ciama «$1». Për piasì, che a contròla se a l'ha scrit tut giust.",
'nouserspecified' => "A venta che a specìfica në stranòm d'utent",
'login-userblocked' => "St'utent-sì a l'é blocà. A peul pa intré ant ël sistema.",
'wrongpassword' => "La ciav batùa a l'é pa giusta.
Che a preuva torna, për piasì.",
'wrongpasswordempty' => "A l'ha butà na ciav veujda. Për piasì, che a preuva torna.",
'passwordtooshort' => 'Le ciav a devo avèj almanch {{PLURAL:$1|1 caràter|$1 caràter}}.',
'password-name-match' => 'Soa ciav a dev esse diferenta da sò stranòm.',
'password-login-forbidden' => "L'usagi ëd së stranòm d'utent e ëd sa ciav a son ëstàit proibì.",
'mailmypassword' => 'Mandeme na neuva ciav për pòsta eletrònica',
'passwordremindertitle' => 'Neuva ciav provisòria për {{SITENAME}}',
'passwordremindertext' => "Cheidun (a l'é belfé che a sia stàit pròpe chiel, da 'nt l'adrëssa IP $1) a l'ha ciamà na neuva
ciav për rintré ant ël sistema ëd {{SITENAME}} ($4).
Na ciav provisòria për l'utent «$2» a l'é stàita creà e adess a resta «$3». Se cost a l'era sò intent,
che a la deuvra për rintré e che a serna na neuva ciav adess.
Soa ciav provisòria a scad da-sì {{PLURAL:$5|un di|$5 di}}.

Se cheidun d'àutri a l'ha fàit costa arcesta, o se chiel a l'é arcordasse dla ciav,
e a veul pì nen cambiela, che a fasa finta ëd gnente e ch'a continua a dovré soa ciav veja.",
'noemail' => "A-i é gnun-a casela ëd pòsta eletrònica argistrà për l'Utent «$1».",
'noemailcreate' => "A dev buté n'adrëssa ëd pòsta eletrònica bon-a.",
'passwordsent' => "Na neuva paròla ciav a l'é stàita mandà a l'adrëssa eletrònica registrà ëd l'Utent «$1».
Për piasì, che a la deuvra sùbit për rintré ant ël sistema pen-a che a l'arsèiv.",
'blocked-mailpassword' => "Soa adrëssa IP a l'é blocà an scritura e donca a peul pa dovré la fonsion d'arciam ëd la ciav për evité j'abus.",
'eauthentsent' => "A l'adrëssa che a l'ha dane i l'oma mandaje un mëssagi ëd pòsta eletrònica për conferma.
Anans che qualsëssìa àutr messagi ëd pòsta a ven-a mandà a 's cont-sì, a venta che a a fasa coma che a-j diso dë fé ant ël mëssagi, për confermé che ës cont a l'é da bon sò.",
'throttled-mailpassword' => "Na ciav neuva a l'é gia stàita mandà da manch che {{PLURAL:$1|n'ora|$1 ore}}. Për evité dj'abus, mach un mëssagi ëd ri-inissialisassion ëd ciav a sarà mandà minca {{PLURAL:$1|ora|$1 ore}}.",
'mailerror' => 'Eror ën mandand via un mëssagi ëd pòsta eletrònica: $1',
'acct_creation_throttle_hit' => "Dij visitador ëd costa wiki, an dovrand soa adrëssa IP a l'han creà {{PLURAL:$1|1 cont|$1 cont}} ant l'ùltim di, che a l'é tut lòn che as peul fesse ant cost temp.
Ëd conseguensa, ij visitador che a deuvro costa adrëssa IP a peulo pì nen fé dij cont al moment.",
'emailauthenticated' => "Soa adrëssa ëd pòsta eletrònica a l'é stàita autenticà ël $2 a $3.",
'emailnotauthenticated' => "Soa adrëssa ëd pòsta eletrònica a l'é pa ancó stàita autenticà.
Për qualsëssìa ëd coste funsion a sarà mandà gnun mëssagi.",
'noemailprefs' => "Che a specìfica n'adrëssa ëd pòsta eletrònica se a veul dovré coste funsion-sì.",
'emailconfirmlink' => 'Che a conferma soa adrëssa ëd pòsta eletrònica',
'invalidemailaddress' => "Costa adrëssa ëd pòsta eletrònica-sì as peul nen pijesse përchè a l'ha na forma nen bon-a.
Për piasì che a buta n'adrëssa scrita giusta ò che a lassa ël camp veujd.",
'cannotchangeemail' => "J'adrësse ëd pòsta eletrònica dij cont a peulo pa esse modificà ansima a costa wiki.",
'emaildisabled' => 'Ës sit a peul pa mandé dij mëssagi ëd pòsta eletrònica.',
'accountcreated' => 'Cont creà',
'accountcreatedtext' => "Ël cont utent për [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|ciaciarade]]) a l'é stàit creà.",
'createaccount-title' => "Creassion d'un cont për {{SITENAME}}",
'createaccount-text' => "Cheidun a l'ha duvertà un cont për soa adrëssa ëd pòsta eletrònica ansima a {{SITENAME}} ($4) butand da stranòm «$2» e da ciav «$3». A dovrìa rintré ant ël sistema e cambiesse soa ciav pì ampressa ch'a peul.

Se sòn a l'é rivà për eror, a peul lassé perde e fé gnente sensa problema.",
'usernamehasherror' => "Lë stranòm d'utent a peul pa conten-e dij caràter ciapulà",
'login-throttled' => "A l'ha fàit tròpi tentativ recent d'intré ant ël sistema.
Për piasì, ch'a speta $1 prima ëd prové torna.",
'login-abort-generic' => "Sò tentitiv d'intré ant ël sistema a l'é falì - Abortì",
'loginlanguagelabel' => 'Lenga: $1',
'suspicious-userlogout' => "Soa arcesta ëd seurte dal sistema a l'é stàita arfudà përchè a smija com s'a fussa stàita mandà da 'n navigador rot o da l'archiviassion an local d'un prëstanòm.",
'createacct-another-realname-tip' => "Ël nòm ver a l'é opsional.
S'a decid ëd butelo, a sarà dovrà për dé a l'utent ël mérit ëd sò travaj.",

# Email sending
'php-mail-error-unknown' => 'Eror pa conossù ant la funsion mail() ëd PHP.',
'user-mail-no-addy' => 'Provà a spedì un mëssagi sensa adrëssa ëd pòsta eletrònica.',
'user-mail-no-body' => 'Tentativ ëd mandé un mëssagi con un còrp veuid o curt ëd fasson esagerà.',

# Change password dialog
'resetpass' => "Cangé 'd ciav",
'resetpass_announce' => "A l'é rintrà ant ël sistema con na ciav provisòria mandà për pòsta eletrònica. Për podèj livré la procedura a l'ha da butesse na ciav neuva ambelessì:",
'resetpass_text' => '<!-- Gionté ël test ambelessì -->',
'resetpass_header' => 'Cangé la ciav dël cont',
'oldpassword' => 'Veja ciav:',
'newpassword' => 'Neuva ciav:',
'retypenew' => 'Che a scriva torna soa neuva ciav:',
'resetpass_submit' => 'Argistré la ciav e rintré ant ël sistema',
'changepassword-success' => "Soa ciav a l'é stàita modificà sensa problema!",
'resetpass_forbidden' => 'Le ciav as peulo pa cambiesse',
'resetpass-no-info' => 'A dev esse rintrà ant ël sustema për acede diretament a sta pàgina.',
'resetpass-submit-loggedin' => "Cangé 'd ciav",
'resetpass-submit-cancel' => 'Anulé',
'resetpass-wrong-oldpass' => "Ciav provisòria o corenta nen bon-a.
Peul desse ch'a l'ha già cambià la ciav o a l'ha ciamà na neuva ciav provisòria.",
'resetpass-temp-password' => 'Ciav provisòria:',
'resetpass-abort-generic' => "La modìfica ëd la ciav a l'é stàita anulà da n'estension.",

# Special:PasswordReset
'passwordreset' => 'Ri-inissialisassion ëd la ciav',
'passwordreset-text-one' => "Ch'a completa 's formolari për reimposté soa ciav.",
'passwordreset-text-many' => "{{PLURAL:$1|Ch'a compila un dij camp për riamposté soa ciav.}}",
'passwordreset-legend' => 'Riampostassion ëd la ciav',
'passwordreset-disabled' => "La reinissialisassion ëd le ciav a l'é stàita disabilità su sta wiki.",
'passwordreset-emaildisabled' => 'Le fonsionalità ëd pòsta eletrònica a son ëstàite disativà su sta wiki.',
'passwordreset-username' => 'Stranòm:',
'passwordreset-domain' => 'Domini:',
'passwordreset-capture' => 'Vëdde ël mëssagi arzultant?',
'passwordreset-capture-help' => "S'a marca costa casela, ël mëssagi ëd pòsta eletrònica (con la ciav provisòria) a-j sarà smonù e ant l'istess temp a sarà mandà a l'utent.",
'passwordreset-email' => 'Adrëssa ëd pòsta eletrònica:',
'passwordreset-emailtitle' => 'Detaj dël cont ansima a {{SITENAME}}',
'passwordreset-emailtext-ip' => "Quaidun (a l'é bel fé ch'a sia chiel, da l'adrëssa IP $1) a l'ha ciamà na riampostassion ëd soa ciav për {{SITENAME}} ($4). {{PLURAL:$3|Ël cont utent sì-sota a l'é|Ij cont utent sì-sota a son}} 
associà a st'adrëssa ëd pòsta eletrònica:

$2

{{PLURAL:$3|Costa ciav provisòria|Coste ciav provisòrie}} a scadran da-sì {{PLURAL:$5|un di|$5 di}}.
A dovrìa intré ant ël sistema e serne na ciav neuva adess. Se quaidun d'àutr a l'ha fàit costa arcesta, o s'a l'é arcordasse soa ciav original, e a veul pa pi cangela, a peule ignoré ës mëssagi e continué a dovré soa veja ciav.",
'passwordreset-emailtext-user' => "L'utent $1 ansima a {{SITENAME}} a l'ha ciamà na riampostassion ëd soa ciav për {{SITENAME}} ($4). {{PLURAL:$3|Ël cont utent sì-sota a l'é|Ij cont utent sì-sota a son}} associà a st'adrëssa ëd pòsta eletrònica:

$2

{{PLURAL:$3|Costa ciav provisòria|Coste ciav provisòrie}} a scadran da-sì {{PLURAL:$5|un di|$5 di}}.
A dovrìa intré ant ël sistema e serne na ciav neuva adess. Se quaidun d'àutr a l'ha fàit costa arcesta, o s'a l'é arcordasse soa ciav original, e a veul pa pi cangela, a peul ignoré ës mëssagi e continué a dovré soa veja ciav.",
'passwordreset-emailelement' => 'Stranòm: $1
Ciav provisòria: $2',
'passwordreset-emailsent' => "Un mëssagi ëd riampostassion ëd la ciav a l'é stàit spedì.",
'passwordreset-emailsent-capture' => "Un mëssagi ëd riampostassion ëd la ciav a l'é stàit mandà, e a l'é mostrà sì-sota.",
'passwordreset-emailerror-capture' => "Un mëssagi ëd riampostassion ëd la ciav a l'é stàit generà, e a l'é smonù sì-sota, ma la spedission a {{GENDER:$2|l'utent}} a l'é falìa: $1",

# Special:ChangeEmail
'changeemail' => "Cangé l'adrëssa ëd pòsta eletrònica",
'changeemail-header' => "Cangé l'adrëssa ëd pòsta eletrònica dël cont",
'changeemail-text' => "Ch'a completa 's formolari për cangé soa adrëssa eletrònica. A dev anserì soa ciav për confirmé costa modìfica.",
'changeemail-no-info' => 'A dev esse intrà ant ël sistema për andé diretament a costa pàgina.',
'changeemail-oldemail' => 'Adrëssa ëd pòsta eletrònica atual:',
'changeemail-newemail' => 'Adrëssa ëd pòsta eletrònica neuva:',
'changeemail-none' => '(gnun-a)',
'changeemail-password' => 'Soa ciav su {{SITENAME}}:',
'changeemail-submit' => "Cangé l'adrëssa ëd pòsta eletrònica",
'changeemail-cancel' => 'Anulé',

# Special:ResetTokens
'resettokens' => 'Riamposté ij geton',
'resettokens-text' => "Ambelessì a peul riamposté ij geton ch'a permëtto d'acede a chèich dàit privà associà a sò cont.

A dovrìa felo si për asar chiel a l'ha partagiaje con cheidun o si sò cont a l'é stàit compromëttù.",
'resettokens-no-tokens' => 'A-i é gnun geton da riamposté.',
'resettokens-legend' => 'Riamposté ij geton.',
'resettokens-tokens' => 'Geton:',
'resettokens-token-label' => '$1 (valor atual: $2)',
'resettokens-watchlist-token' => "Geton për ël fluss an sl'aragnà (Atom/RSS) ëd [[Special:Watchlist|modìfiche a le pàgine che as ten sot-euj]]",
'resettokens-done' => 'Geton riampostà.',
'resettokens-resetbutton' => 'Riamposté ij geton selessionà',

# Edit page toolbar
'bold_sample' => 'Test an grassèt',
'bold_tip' => 'Test an grassèt',
'italic_sample' => 'Test an corsiv',
'italic_tip' => 'Test an corsiv',
'link_sample' => "Tìtol dl'anliura",
'link_tip' => 'Anliura interna',
'extlink_sample' => "http://www.example.com tìtol dl'anliura",
'extlink_tip' => 'Anliura esterna (che as visa dë buté ël prefiss http://)',
'headline_sample' => 'Test dël tìtol',
'headline_tip' => 'Antestassion dë scond livel',
'nowiki_sample' => 'Che a buta ël test brut ambelessì',
'nowiki_tip' => 'Lassé un tòch ëd test fòra dla sintassi dla wiki',
'image_sample' => 'Esempi.jpg',
'image_tip' => 'Archivi anglobà',
'media_sample' => 'Esempi.ogg',
'media_tip' => "Anliura a n'archivi multimedial",
'sig_tip' => "Soa signadura con la data e l'ora",
'hr_tip' => 'Riga orisontal (da dovresse nen tròp soèns)',

# Edit pages
'summary' => 'Resumé:',
'subject' => 'Sogèt/antestassion:',
'minoredit' => "Costa a l'é na modìfica cita",
'watchthis' => 'Ten-e sot euj costa pàgina-sì',
'savearticle' => 'Salvé la pàgina',
'preview' => 'Previsualisassion',
'showpreview' => 'Mostré na preuva',
'showlivepreview' => "Funsion ''Preuva dal viv''",
'showdiff' => 'Smon-me le modìfiche',
'anoneditwarning' => "'''Atension:''' A l'é nen rintrà ant ël sistema. Soa adrëssa IP a sarà registrà ant la stòria dle modìfiche ëd sa pàgina.",
'anonpreviewwarning' => "''A l'é nen rintrà ant ël sistema. An salvand a sarà memorisà soa adrëssa IP ant la stòria dle modìfiche ëd sa pàgina.''",
'missingsummary' => "'''Nòta:''' a l'ha butà gnun resumé dla modìfica. Se a sgnaca «{{int:savearticle}}» n'àutra vira, soa modìfica a resterà salvà sensa resumé.",
'missingcommenttext' => 'Për piasì, che a buta un coment sì-sota.',
'missingcommentheader' => "'''Ch'a arcòrda:''' A l'ha pa dàit ëd soget o d'intestassion për cost coment.
Se a sgnaca torna «{{int:savearticle}}», soa modìfica a sarà salvà sensa gnun-a intestassion.",
'summary-preview' => 'Preuva dël resumé:',
'subject-preview' => "Preuva dl'oget/intestassion:",
'blockedtitle' => "L'utent a l'é blocà.",
'blockedtext' => "'''Sò stranòm ò pura adrëssa IP a l'é stàit blocà.'''

Ël blocagi a l'é stàit fàit da $1.
Coma rason a l'ha butà ''$2''.

* Blocà a parte dal: $8
* Fin al: $6
* As veul blochesse: $7

A peul butesse an contat con $1 ò pura n'àotr [[{{MediaWiki:Grouppage-sysop}}|aministrator]] për discute ëd sò blocagi.
Ch'a ten-a present ch'a podrà dovré la fonsion «mandeje un messagi ëd pòsta eletrònica a l'utent» mach s'a l'ha specificà n'adrëssa ëd vàlida ant [[Special:Preferences|sò gust]] e se sta fonsion a l'é nen ëstàita blocà 'cò chila.
Soa adrëssa IP corenta a l'é $3, e l'identificativ dël blocagi a l'é #$5.
Për piasì, ch'a-j buta tut e doj ant soe comunicassion ant sta question-sì.",
'autoblockedtext' => "Soa adrëssa IP a l'è stàita blocà n'automàtich ën essend ch'a l'era dovrà da n'àutr utent, che a l'é stàit blocà da $1.
La rason ësmonùa a l'é

:''$2''

* Ël blocagi a part dai: $8
* A va a la fin ai: $6
* As veul blochesse: $7

A peul contaté $1 ò pura n'àotr dj'[[{{MediaWiki:Grouppage-sysop}}|aministrator]] për discute d'ës blocagi.

Ch'a varda mach ch'a peul nen dovré l'opsion ëd «mandeje un mëssagi a l'utent» se a l'ha nen n'adrëssa ëd pòsta eletrònica vàlida registrà ant [[Special:Preferences|sò gust]] o se chiel a l'é stàit blocà ëdcò dal dovrela.

Soa adrëssa IP corenta a l'é $3, e sò nùmer ëd blocagi a l'é $5.
Për piasì, ch'a buta sempe tùit ij detaj an tute j'arceste ch'a farà.",
'blockednoreason' => 'gnun-a rason butà',
'whitelistedittext' => 'A dev $1 për podèj fé dle modìfiche a le pàgine.',
'confirmedittext' => 'A dev confermé soa adrëssa ëd pòsta eletrònica, anans che modifiché dle pàgine. Për piasì, che a convàlida soa adrëssa ën dovrand la pàgina dij [[Special:Preferences|sò gust]].',
'nosuchsectiontitle' => 'As peul pa trovesse la session',
'nosuchsectiontext' => "A l'ha provasse a modifiché na session ch'a-i é pa.
A peul essa stàita tramudà o scancelà antramentre ch'a vëdìa la pàgina.",
'loginreqtitle' => 'A venta rintré ant ël sistema',
'loginreqlink' => 'rintré ant ël sistema',
'loginreqpagetext' => "A dev $1 për podèj vëdde j'àutre pàgine.",
'accmailtitle' => 'Ciav spedìa',
'accmailtext' => "Na ciav generà a l'ancàpit për [[User talk:$1|$1]] a l'é stàita mandà a $2.
A peul esse modificà an sla pàgina ''[[Special:ChangePassword|modìfica dla ciav]]'' apress esse rintrà ant ël sistema.",
'newarticle' => '(Neuv)',
'newarticletext' => "A l'é andaje dapress a na liura a na pàgina che a esist ancor nen.
Për creé la pàgina, ch'a ancamin-a a scrive ant lë spassi sì-sota (vëdde la [[{{MediaWiki:Helppage}}|pàgina d'agiut]] për savèjne ëd pì).
S'a l'é rivà sì për eror, ch'a sgnaca ël boton '''andaré''' ëd sò navigador.",
'anontalkpagetext' => "----''Costa a l'é la pàgina ëd ciaciarade për n'utent anònim che a l'é ancó pa dorbusse un cont, ò pura che a lo deuvra nen. Alora i l'oma da dovré ël nùmer d'adrëssa IP për deje n'identificassion a chiel o chila.
N'adrëssa IP përparèj a peul esse partagià da vàire utent.
Se chiel a l'é n'utent anònim e a l'ha l'impression d'arsèive dij coment sensa sust, për piasì [[Special:UserLogin/signup|ch'a crea un cont]] o [[Special:UserLogin|ch'a rintra ant ël sistema]] për evité dë fé confusion con d'àutri utent anònim.''",
'noarticletext' => 'Al moment costa pàgina a l\'é veuida.
A peul [[Special:Search/{{PAGENAME}}|sërché cost tìtol]] andrinta a d\'àutre pàgine, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sërché ant ij registr colegà],
o purament [{{fullurl:{{FULLPAGENAME}}|action=edit}} modìfiché sta pàgina]</span>.',
'noarticletext-nopermission' => "Al moment a-i é gnun test ansima a costa pàgina.
A peul [[Special:Search/{{PAGENAME}}|sërché ës tìtol ëd pàgina]] an d'àutre pàgine,
o <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sërché ant j'argistr colegà]</span>, ma a l'ha pa ël përmess ëd creé costa pàgina.",
'missing-revision' => "La revision nùmer $1 dla pàgina antitolà «{{PAGENAME}}» a esist pa.

Sòn a l'é normalment causà da l'andèje dapress a na vej liura stòrica a na pàgina ch'a l'é stàita scancelà. Ij detaj a peulo esse trovà ant ël [registr ëd jë scancelament ëd {{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}}].",
'userpage-userdoesnotexist' => "Lë stranòm «<nowiki>$1</nowiki>» a l'é pa registrà. Për piasì ch'a varda se da bon a veul creé o modifiché costa pàgina.",
'userpage-userdoesnotexist-view' => "Ël cont utent «$1» a l'é pa registrà.",
'blocked-notice-logextract' => "S'utent a l'é al moment blocà.
'Me arferiment, sì-sota a-i é la dariera anotassion da l'argistr dij blocagi:",
'clearyourcache' => "'''Nòta:''' na vira che a l'ha salvà, a peul esse che a-j fasa da manca ëd passé via la memorisassion ëd sò programa ëd navigassion për podèj ës-ciairé le modìfiche.
* '''Firefox / Safari:''' Che a ten-a sgnacà ''Majùscole'' antramentre che a sgnaca col rat ansima a ''Agiorné'', ò pura che a sgnaca ''Ctrl-F5'' o ''Cmd-R'' (''⌘-R'' ansima ai Mac)
* '''Google Chrome:''' Che a sgnaca ''Ctrl-Majùscole-R'' (''⌘-Majùscole-R'' ansima ai Mac)
* '''Internet Explorer:''' Che a ten-a sgnacà ''Ctrl'' antramentre che a sgnaca col rat ansima a ''Agiorné'', ò pura che a sgnaca tut ansema ''Ctrl-F5''
*'''Opera''' Ch'a dësveuida soa memorisassion andrinta a ''Utiss → Gust''",
'usercssyoucanpreview' => "'''Drita:''' che a deuvra ël boton «{{int:showpreview}}» për controlé l'efet ëd sò neuv còdes CSS dnans ëd salvelo.",
'userjsyoucanpreview' => "'''Drita:''' che a deuvra ël boton «{{int:showpreview}}» për controlé l'efet ëd sò neuv còdes JavaScript dnans ëd salvelo.",
'usercsspreview' => "'''Che a varda che lòn che a s-ciàira a l'é nomach na preuva ëd sò feuj CSS.'''
'''A l'é ancó nen stàit salvà!'''",
'userjspreview' => "'''Che as visa che a l'é mach antramentre che as fa na preuva ëd sò còdes Javascript e che a l'é ancó pa stàit salvà!'''",
'sitecsspreview' => "'''Che a varda che a l'é mach an camin ch'a preuva cost CSS.'''
'''A l'é pa ancora stàit salvà!'''",
'sitejspreview' => "'''Che a varda che a l'é mach an camin ch'a preuva cost còdes JavaScript.'''
'''A l'é pa ancora stàit salvà!'''",
'userinvalidcssjstitle' => "'''Atension:''' A-i é gnun-a pel «$1». Che as visa che le pàgine .css e .js che un as fa daspërchiel a deuvro tute minùscole për tìtol, pr'esempi {{ns:user}}:Scaramacaj/vector.css nopà che {{ns:user}}:Scaramacaj/Vector.css.",
'updated' => '(Agiornà)',
'note' => "'''Nòta:'''",
'previewnote' => "'''Che a ten-a da ment che costa-sì a l'é mach na preuva.'''
Soe modìfiche a son pa ancora stàite salvà!",
'continue-editing' => 'Andé a la zòna ëd modìfica',
'previewconflict' => "Costa preuva a mostra ël test dl'artìcol ambelessì-dzora. Se a sern dë salvelo, a l'é parèj che a lo s-ciairëran ëdcò tuti j'àutri Utent.",
'session_fail_preview' => "'''Darmagi! I l'oma pa podù processé soa modìfica per via che a son përdusse për la stra ij dat ëd session.'''
Për piasì che a preuva n'àutra vira. Se a dovèissa torna nen marcé, che a preuva a [[Special:UserLogout|seurte dal sistema]] e peuj torna a rintré.",
'session_fail_preview_html' => "'''Darmagi! I l'oma nen podù processé soa modìfica ën essend che a son përdusse për la stra ij dat ëd session.'''

''Për via che {{SITENAME}} a lassa mostré còdes HTML nen filtrà, la preuva a l'é stërmà coma precaussion contra a dij possìbij atach fàit an Javascript.''

'''Se costa a l'era na modìfica normal, për piasì che a preuva a fela n'àutra vira. Se a dovèissa mai torna deje dle gran-e, che a preuva a [[Special:UserLogout|seurte da 'nt ël sistema]] e peuj torna a rintré.'''",
'token_suffix_mismatch' => "'''Soa modìfica a l'é nen stàita acetà përché sò navigator a l'hai fàit ciadel con ij pont e le vìrgole
ant ël quàder ëd modìfica.'''
La rason che a l'é nen stàit acetà a l'é për evité ch'a-j fasa darmagi al
test ch'a-i é già. Sossì dle vire a riva quand un a deuvra un servent anònim an sl'Aragnà ëd coj un pòch dla Bajòna.",
'edit_form_incomplete' => "'''Chèiche part dël formolari ëd modìfica a son pa rivà al servent; ch'a contròla për da bin che soe modìfiche a-i sio ancora e ch'a preuva torna.'''",
'editing' => 'Modìfica ëd $1',
'creating' => 'Creé $1',
'editingsection' => 'Modìfica ëd $1 (session)',
'editingcomment' => 'Modìfica ëd $1 (neuva session)',
'editconflict' => 'Conflit ëd modìfica: $1',
'explainconflict' => "Cheidun d'àutr a l'ha salvà soa version dl'artìcol antramentre che chiel as prontava la soa.
Ël quàder ëd modìfica dë dzora a mostra ël test ëd l'artìcol coma a resta adess (visadì, lòn che a-i é ant sla Ragnà). Soe modìfiche a stan ant ël quàder dë sota.
Ën volend a peul gionté soe modìfiche ant ël quàder dë dzora.
'''Mach''' ël test ant ël quàder dë dzora a sarà salvà, ën sgnacand ël boton \"{{int:savearticle}}\".",
'yourtext' => 'Sò test',
'storedversion' => 'La version memorisà',
'nonunicodebrowser' => "'''A L'EUJ! Sò programa ëd navigassion a marcia pa giust con lë stàndard Unicode. I soma obligà a dovré dij truschin përchè a peula salvesse sò artìcoj sensa problema: ij caràter che a son nen ASCII a jë s-ciairerà ant ël quàder ëd modìfica dël test coma còdes esadecimaj.'''",
'editingold' => "'''CHE A FASA MACH ATENSION: che a l'é an camin ch'a modìfica na version nen agiornà dl'artìcol.<br />
Se a la salva parèj, lòn che a l'era stàit fàit dapress a sta revision-sì as perdrà d'autut.'''",
'yourdiff' => 'Diferense',
'copyrightwarning' => "Che a ten-a për piasì da ment che tute le contribussion a {{SITENAME}} as consìdero dàite sota a na licensa ëd la sòrt $2 (che a varda $1 për avèj pì 'd detaj).
Se a veul nen che sò test a peula esse modificà e distribuì da qualsëssìa përson-a sensa gnun-a limitassion ëd gnun-a sòrt, che a lo buta pa ambelessì.<br />
Ën mandand ës test-sì chiel as fa garant sota soa responsabilità che ël test a l'ha scrivusslo despërchiel, ò pura che a l'ha tracopialo da na sorgiss ëd pùblich domini, ò da n'àutra sorgiss dla midema sòrt.
'''Anserì mai dël material coatà da drit d'autor sensa avèj n'autorisassion për felo!'''",
'copyrightwarning2' => "Për piasì, che a ten-a da ment che tute le contribussion a {{SITENAME}} a peulo esse modificà ò scancelà da d'àutri contributor. Se a veul nen che lòn che a scriv a ven-a modificà sensa limitassion ëd gnun-a sòrt, che a lo manda nen ambelessì.<br />
Ant l'istess temp, ën mandand dël material un as pija la responsabilità dë dì che a l'ha scrivusslo daspërchiel, ò pura che a l'ha copialo da na sorgiss ëd domini pùblich, ò pura da 'nt n'àutra sorgiss dla midema sòrt (che a varda $1 për avèj pì d'anformassion).
'''Che a manda pa dël material coata da drit d'autor sensa avèj avù ël përmess ëd felo!'''",
'longpageerror' => "'''EROR: Ël test che a l'ha mandà a l'é longh {{PLURAL:$1|un kilobyte|$1 kilobytes}}, che a resta pì che ël lìmit màssim {{PLURAL:$2|d'un kilobyte|ëd $2 kilobyte}}.''' Parèj as peul pa salvesse.",
'readonlywarning' => "'''Avis: La base ëd dat a l'é stàita blocà për manutension, e donca a podrà pa salvesse soe modìfiche tut sùbit.'''
A peul esse che a-j ven-a còmod copiesse via sò test e ancoless-lo an n'archivi ëd test e goernelo për pi tard.

L'aministrator che a l'ha fàit ël blocagi a l'ha dàit costa spiegassion: $1",
'protectedpagewarning' => "'''Avis: costa pàgina-sì a l'é stàita protegiùa an manera che mach j'utent con la qualìfica da aministrator a peulo feje dle modìfiche.'''
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'semiprotectedpagewarning' => "'''Nòta:''' Costa pàgina-sì a l'é stàita blocà an manera che mach j'utent registrà a peulo modifichela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'cascadeprotectedwarning' => "'''Tension:''' Sta pàgina a l'é stàita blocà an manera che mach j'utent con la qualìfica da aministrator a peulo modifichela, për via che a l'é comprèisa an {{PLURAL:$1|costa pàgina-sì|an coste pàgine-sì}} ch'a l'han ël sistema ëd protession a cascada:",
'titleprotectedwarning' => "'''Avis: sta pàgina-sì a l'é stàita blocà an manera che a-i é dabzògn ëd [[Special:ListGroupRights|drit specìfich]] për creela.'''
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'templatesused' => '{{PLURAL:$1|Stamp}} dovrà da costa pàgina-sì:',
'templatesusedpreview' => '{{PLURAL:$1|Stamp}} dovrà an costa preuva:',
'templatesusedsection' => '{{PLURAL:$1|Stamp}} dovrà an costa session-sì:',
'template-protected' => '(protet)',
'template-semiprotected' => '(mes-protet)',
'hiddencategories' => 'Sta pàgina-sì a fa part ëd {{PLURAL:$1|na categorìa|$1 categorìe}} stërmà:',
'edittools' => "<!-- Test ch'a së s-ciàira sot a ij mòduj ëd modìfica e 'd carie d'archivi. -->",
'nocreatetext' => "{{SITENAME}} a l'ha limità la possibilità ëd creé dle pàgine neuve.
A peul torné andaré e modifiché na pàgina che a-i é già, ò pura [[Special:UserLogin|rintré ant ël sistema ò deurb-se un cont]].",
'nocreate-loggedin' => "A l'ha pa ël përmess ëd creé dle pàgine neuve.",
'sectioneditnotsupported-title' => "La modìfica dla session a l'é nen prevëdùa",
'sectioneditnotsupported-text' => "La modìfica dla session a l'é nen prevëdùa an costa pàgina ëd modìfica.",
'permissionserrors' => 'Eror ant ij përmess',
'permissionserrorstext' => "A l'ha pa ij përmess dont a fa da manca për {{PLURAL:$1|via che|via che}}:",
'permissionserrorstext-withaction' => "It l'has nen ij përmess për $2, për {{PLURAL:$1|cost motiv|costi motiv}}:",
'recreate-moveddeleted-warn' => "A l'é an camin ch'a crea torna na pàgina ch'a l'era stàita scancelà.'''

Ch'a varda d'esse sigur ch'a vala la pen-a ëd travajé an sna pàgina parèj.
Për soa comodità i-j mostroma la lista djë scancelament ch'a toco sta pàgina-sì:",
'moveddeleted-notice' => "Sta pàgina-sì a l'é stàita scancelà.
Ël registr ëd le scancelassion e dij tramud a l'é arportà sota për arferiment.",
'log-fulllog' => 'Varda tut ël registr',
'edit-hook-aborted' => "Modìfica anulà da n'estension.
A-i é pa gnun-e spiegassion.",
'edit-gone-missing' => 'As peul nen modifiché la pàgina.
A smija che a sia stàita scancelà.',
'edit-conflict' => "Conflit d'edission.",
'edit-no-change' => "Toa modìfica a l'é stàita ignorà, përchè a l'é pa stàit fàit gnun cambiament al test.",
'postedit-confirmation' => "Soa modìfica a l'é stàita salvà!",
'edit-already-exists' => 'As peul nen creesse la pàgina.
A esist già.',
'defaultmessagetext' => "Test che a-i sarìa se a-i fusso pa 'd modìfiche",
'content-failed-to-parse' => "Faliment ëd l'anàlisi dël contnù ëd $2 për ël model $1: $3",
'invalid-content-data' => 'Dat dël contnù pa bon',
'content-not-allowed-here' => "Ël contnù «$1» a l'é nen autorisà an sla pàgina [[$2]]",
'editwarning-warning' => "Chité sta pàgina-sì a peul feje perde tute le modìfiche ch'a l'ha fàit.
S'a l'é rintrà ant ël sistema, a peul disabilité st'avis ant la session «Modìfiche» dij sò gust.",

# Content models
'content-model-wikitext' => 'test wiki',
'content-model-text' => 'mach test',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Atension:''' Costa pàgina a l'ha tròpe ciamà costose a le fonsions ëd parser.

A dovrìa essnie men che {{PLURAL:$2|$2|$2}}, adess a-i na j'é {{PLURAL:$1|$1|$1}}.",
'expensive-parserfunction-category' => 'Pàgine con tròpe ciamà costose a le fonsion parser',
'post-expand-template-inclusion-warning' => "'''Atension:''' La dimension djë stamp anserì a l'é tròp gròssa.
Chèich stamp a saran nen anserì.",
'post-expand-template-inclusion-category' => "Pàgine andoa la dimension djë stamp anserì a l'é tròpa",
'post-expand-template-argument-warning' => "'''Atension:''' Costa pàgina a conten almanch un paràmeter dë stamp che a l'ha n'espansion tròp gròssa.
Costi paràmeter a son stàit lassà fòra.",
'post-expand-template-argument-category' => 'Pàgine contenente stamp con paràmeter mancant',
'parser-template-loop-warning' => 'Trovà na liassa dlë stamp: [[$1]]',
'parser-template-recursion-depth-warning' => 'Passà ël lìmit ëd ricorsion dlë stamp ($1)',
'language-converter-depth-warning' => 'Passà lìmit ëd profondità dël convertidor ëd lenghe ($1)',
'node-count-exceeded-category' => "Pàgine anté che ël nùmer ëd grop a l'é sorpassà",
'node-count-exceeded-warning' => "La pàgina a l'ha sorpassà ël nùmer ëd grop",
'expansion-depth-exceeded-category' => "Pàgine anté che la profondeur d'espansion a l'é sorpassà",
'expansion-depth-exceeded-warning' => "La pàgina a l'ha sorpassà la profondità d'espansion",
'parser-unstrip-loop-warning' => 'Trovà un sicl nen dësmontàbil',
'parser-unstrip-recursion-limit' => "Sorpassà ël lìmit d'arcorensa nen dësmontàbil: $1",
'converter-manual-rule-error' => 'Eror trovà ant la régola ëd conversion manual ëd la lenga',

# "Undo" feature
'undo-success' => "Sta modìfica-sì as peul scancelesse. Për piasì, ch'a contròla ambelessì sota për esse sigur che a l'é pro lòn che a veul fé, e peuj ch'as salva lòn ch'a l'ha butà chiel/chila për finì dë scancelé la modìfica ch'a-i era.",
'undo-failure' => "Sta modìfica a l'é nen podusse scancelé për via che a-i son dle contradission antra version antrames.",
'undo-norev' => "La modìfica a peul nen esse anulà përchè a esist pa o a l'é stàita anulà.",
'undo-summary' => 'Gavà la revision $1 fàita da [[Special:Contributions/$2|$2]] ([[User talk:$2|Ciaciarade]])',
'undo-summary-username-hidden' => "Anulé la revision $1 ëd n'utent ëstërmà",

# Account creation failure
'cantcreateaccounttitle' => "As peul pa registresse d'utent",
'cantcreateaccount-text' => "La cression ëd cont neuv a parte da st'adrëssa IP-sì ('''$1''') a l'é stàita blocà da [[User:$3|$3]].

La rason butà da $3 për ël blocagi a l'é stàita: ''$2''",

# History pages
'viewpagelogs' => 'Smon ij registr dë sta pàgina-sì',
'nohistory' => "La stòria dle version dë sta pàgina-sì a l'é pa trovasse.",
'currentrev' => "Version dël dì d'ancheuj",
'currentrev-asof' => 'Version corenta dij $1',
'revisionasof' => 'Revision $1',
'revision-info' => 'Revision al $1; $2',
'previousrevision' => '←Version pì veja',
'nextrevision' => 'Revision pì neuva →',
'currentrevisionlink' => 'Vardé la version corenta',
'cur' => 'cor',
'next' => 'anans',
'last' => 'andaré',
'page_first' => 'prima',
'page_last' => 'ùltima',
'histlegend' => 'Confront antra version diferente: che as selession-a le casele dle version che a veul e peui che a sgnaca ël boton për anandié ël process.<br />
Legenda: (cor) = diferense con la version corenta,
(prim) = diferense con la version prima, c = modìfica cita',
'history-fieldset-title' => 'Varda la cronologìa',
'history-show-deleted' => 'Mach ëscancelà',
'histfirst' => 'ij pì vej',
'histlast' => 'ij pì recent',
'historysize' => '({{PLURAL:$1|1|$1}} byte)',
'historyempty' => '(veujda)',

# Revision feed
'history-feed-title' => 'Stòria',
'history-feed-description' => 'Stòria dla pàgina ansima a sto sit-sì',
'history-feed-item-nocomment' => '$1 al $2',
'history-feed-empty' => "La pàgina che a l'ha ciamà a-i é pa; a podrìa esse stàita scancelà da 'nt ël sit, ò pura tramudà a n'àutr nòm.

Che a verìfica con la [[Special:Search|pàgina d'arserca]] se a-i fusso mai dj'àutre pàgine che a podèisso andeje bin.",

# Revision deletion
'rev-deleted-comment' => '(resumé dla modìfica gavà)',
'rev-deleted-user' => '(stranòm gavà)',
'rev-deleted-event' => '(assion dël registr gavà)',
'rev-deleted-user-contribs' => '[nòm utent o adrëssa IP gavà - modìfica stërmà ai contributor]',
'rev-deleted-text-permission' => "Sta revision-sì dla pàgina a l'é staita '''scancelà'''.
A-i peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-deleted-text-unhide' => "Sta version-sì dla pàgina a l'é stàita '''scancelà'''.
A peulo ess-ie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd la scancelassion].
A peul anco' [$1 vardé sta version-sì] se a veul.",
'rev-suppressed-text-unhide' => "Sta version-sì dla pàgina a l'é stàita '''gavà via'''.
A peulo ess-ie dle marche ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion]. 
A peul anco' [$1 vëdde sta version] se a veul.",
'rev-deleted-text-view' => "Costa revision dla pàgina-sì a l'é staita '''scancelà'''.
Chiel a peul ës-ciairela; a peulo ess-ie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-suppressed-text-view' => "Costa revision dla pàgina-sì a l'é stàita '''gavà via'''.
Chiel a peul ës-ciairela; a peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-deleted-no-diff' => "A peul pa vëdde coste diferense përchè un-a dle revision a l'é stàita '''scancelà'''.
A peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-suppressed-no-diff' => "It peule pa vëdde sta diferensa-sì përchè un-a dle revision a l'é stàita '''scanselà'''.",
'rev-deleted-unhide-diff' => "Un-a dle revision ëd coste diferense a l'é stàita '''scancelà'''.
A peulo ess-ie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].
It peule ancó [$1 vëdde le diferense] se a fà dbzògn.",
'rev-suppressed-unhide-diff' => "Un-a dle revision dë sta diferensa-sì a l'é stàita '''scancelà'''.
A peulo ess-ie dij detaj ant ël  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].
Chiel a peul ancò [$1 vëdde sta diferensa-sì] s'a veul.",
'rev-deleted-diff-view' => "Un-a dle revision dë sta diferensa-sì a l'é stàita '''scancelà'''.
It peule ancó vëdde sta diferensa-sì; a peulo ess-ie dij detaj ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].",
'rev-suppressed-diff-view' => "Un-a dle revision ëd costa diferensa-sì a l'é stàita '''eliminà'''.
Chiel a peul ancora s-ciairé costa diferensa; a peulo essje pì 'd detaj ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd j'eliminassion].",
'rev-delundel' => 'mostra/stërma',
'rev-showdeleted' => 'Mostra',
'revisiondelete' => 'Scancela/disdëscancela revision',
'revdelete-nooldid-title' => 'Version nen spessificà',
'revdelete-nooldid-text' => "A l'ha nen spessificà na version ëd la pàgina për aplicheje costa fonsion, la version spessificà a esist pa, o a preuva a stërmé la version corenta.",
'revdelete-nologtype-title' => "Gnun-a sòrt d'argistr spessificà",
'revdelete-nologtype-text' => "A l'ha nen spessificà na sòrt ëd registr për fé costa assion.",
'revdelete-nologid-title' => 'Intrada dël registr pa giusta',
'revdelete-nologid-text' => "A l'ha pa spessificà n'event dël registr bërsaj andoa apliché costa fonsion o l'intrada spessificà a esist nen.",
'revdelete-no-file' => "L'archivi sërcà a-i é pa.",
'revdelete-show-file-confirm' => 'É-lo sigur ëd vorèj vëdde na vërsion scancelà dl\'archivi "<nowiki>$1</nowiki>" da $2 a $3?',
'revdelete-show-file-submit' => 'Bò!',
'revdelete-selected' => "'''{{PLURAL:$2|Revision|Revision}} selessionà për [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Event|Event}} dël registr selessionà:'''",
'revdelete-text' => "Le version scancelà e j'event a së s-ciaireran sempe ant la stòria dla pàgina e ant ij registr, ma sò test al pùblich a j'andrà pì nen.'''
J'àutri aministrator dzora a {{SITENAME}} a saran ancó sempe bon a s-ciairé ël contnù stërmà e a podran disdëscancelelo andré con la midema antërfacia, sempe che a sia nen stàita butà na restrission adissional.",
'revdelete-confirm' => "Për piasì, ch'a confema ch'a veul fé sòn, ch'as rend cont dle conseguense, e ch'a lo fa an acòrd con [[{{MediaWiki:Policy-url}}|le régole]].",
'revdelete-suppress-text' => "La scancelassion a dovrìa '''mach''' esse dovrà për cost cas:
* Anformassion përsonaj nen aproprià
*: ''adrësse ëd ca e nùmer ëd teléfon, còdes fiscaj, e via fòrt''",
'revdelete-legend' => 'But-je coste limitassion-sì a le version scancelà:',
'revdelete-hide-text' => 'Stërma ël test dla revision',
'revdelete-hide-image' => "Stërma ël contnù dl'archivi",
'revdelete-hide-name' => 'Stërma assion e oget',
'revdelete-hide-comment' => 'Stërma ël coment a la modìfica',
'revdelete-hide-user' => "Stërma lë stranòm ò l'adrëssa IP dël contributor",
'revdelete-hide-restricted' => "Stërmé j'anformassion a j'aministrator tan-me a j'àutri",
'revdelete-radio-same' => '(cambia pa)',
'revdelete-radio-set' => 'É!',
'revdelete-radio-unset' => 'Nò',
'revdelete-suppress' => "Smon-je pa ij dat gnanca a j'aministrator",
'revdelete-unsuppress' => "Gava le limitassion da 'nt le version ciapà andaré",
'revdelete-log' => 'Rason:',
'revdelete-submit' => 'Bùtejlo a {{PLURAL:$1|la version|le version}} selessionà',
'revdelete-success' => "'''Visibilità dla revision modificà com ch'as dev.'''",
'revdelete-failure' => "'''La visibilità dla version a peul pa esse modificà:'''
$1",
'logdelete-success' => "'''Visibilità dla revision butà coma ch'as dev.'''",
'logdelete-failure' => "'''La visibilità dël registr a peul pa esse ampostà:'''
$1",
'revdel-restore' => 'cambia visibilità',
'revdel-restore-deleted' => 'revision ëscancelà',
'revdel-restore-visible' => 'revision visìbij',
'pagehist' => 'Stòria dla pàgina',
'deletedhist' => 'Stòria scancelà',
'revdelete-hide-current' => "Eror an stërmand l'element datà $2, $1: costa-sì a l'é la version corenta.
A peul pa esse stërmà.",
'revdelete-show-no-access' => 'Eror an mostrand l\'element datà $2, $1: st\'element-sì a l\'é stàit marcà "riservà".
It peule pa vëddlo.',
'revdelete-modify-no-access' => 'Eror an modificand l\'element datà $2, $1: st\'element-sì a l\'é stàit marcà "riservà".
It peule pa vëddlo.',
'revdelete-modify-missing' => "Eror an modificand l'element con ID $1: a-i é pa ant la base ëd dàit!",
'revdelete-no-change' => "'''Atension:''' l'element datà $2, $1 a l'ha già j'ampostassion ëd visibilità ciamà.",
'revdelete-concurrent-change' => "Eror an modificand l'element $2, $1: sò stat a smija che a sia stàit cambià da cheidun d'àutri antramentre che chiel a provava a modifichelo. Për piasì, ch'a contròla ij registr.",
'revdelete-only-restricted' => "Eror an stërmand l'element datà $2, $1: it peule pa vieté la vista d'element a j'aministrator sensa ëdcò selessioné un-a dj'àutre opsion ëd visibilità.",
'revdelete-reason-dropdown' => "*Rason sòlite dë scancelassion
** Violassion dël drit d'autor
** Coment o anformassion përsonaj pa aproprià
** Nòm utent pa aproprià
** Anformassion potensialment difamatòria",
'revdelete-otherreason' => 'Àutra rason o adissional:',
'revdelete-reasonotherlist' => 'Àutra rason',
'revdelete-edit-reasonlist' => 'Modifiché la rason ëd lë scancelament',
'revdelete-offender' => 'Autor ëd la revision:',

# Suppression log
'suppressionlog' => 'Registr ëd le scancelassion',
'suppressionlogtext' => "Sì-sota a-i é na lista djë scancelament e dij blocagi che a rësguardo dij contnù stërmà a j'aministrator.
Beiché la [[Special:BlockList|lista dij blocagi]] për la lista dj'esclusion operassionaj e dij blocagi ativ.",

# History merging
'mergehistory' => 'Buté ansema je stòrie',
'mergehistory-header' => "Sta pàgina-sì a lassa fene buté le revision ëd na pàgina ansema a cole 'd n'àutra.
Ch'a varda mach che a-i ven-a nen fòra un rabel ant la continuità stòrica.",
'mergehistory-box' => 'Fene un-a dle stòrie ëd doe pàgine:',
'mergehistory-from' => 'Pàgina sorgiss:',
'mergehistory-into' => "Pàgina 'd destinassion:",
'mergehistory-list' => "Stòria ch'as peul unifichesse",
'mergehistory-merge' => "Ambelessì sota a-i son le revision ëd [[:$1]] ch'as peulo butesse ansema a [[:$2]]. Ch'a deuvra la casela për marché l'ùltima version da gionté. Ch'a ten-a da ment che se a nàviga via da sta pàgina-sì sta colòna as veujda.",
'mergehistory-go' => "Smon le modìfiche ch'as peulo butesse ansema",
'mergehistory-submit' => 'Buta ansema le revision',
'mergehistory-empty' => "Pa gnun-a revision ch'as peula butesse ansema.",
'mergehistory-success' => '$3 {{PLURAL:$3|revision|revision}} ëd [[:$1]] a son ëstàite butà ansema a [[:$2]] sensa problema.',
'mergehistory-fail' => "A l'é nen riessusse a buté ansema le revision, për piasì, ch'as contròla la pàgina e ij temp.",
'mergehistory-no-source' => 'La pàgina sorgiss $1 a-i é pa.',
'mergehistory-no-destination' => 'La pàgina ëd destinassion $1 a-i é pa.',
'mergehistory-invalid-source' => "La pàgina sorgiss a l'ha d'avèj un tìtol bon.",
'mergehistory-invalid-destination' => "La pàgina ëd destinassion a l'ha d'avèj un tìtol bon.",
'mergehistory-autocomment' => 'Butà [[:$1]] andrinta a [[:$2]]',
'mergehistory-comment' => 'Butà [[:$1]] andrinta a [[:$2]]: $3',
'mergehistory-same-destination' => "La pagina ëd partensa e cola d'ariv a peulo nen esse le mideme",
'mergehistory-reason' => 'Rason:',

# Merge log
'mergelog' => "Registr dj'union",
'pagemerge-logentry' => "a l'ha butà [[$1]] ansema a [[$2]] (revision fin-a a la $3)",
'revertmerge' => 'Gavé da ansema',
'mergelogpagetext' => "Ambelessì sota a-i é na lista dj'ùltime vire che la stòria ëd na pàgina a l'é stàita butà ansema a cola 'd n'àutra.",

# Diffs
'history-title' => '$1: Cronologìa dle modìfiche',
'difference-title' => '$1: Diferensa tra revision',
'difference-title-multipage' => 'Diferensa tra le pàgine «$1» e «$2»',
'difference-multipage' => '(Diferense tra pàgine)',
'lineno' => 'Riga $1:',
'compareselectedversions' => 'Paragon-a le version selessionà',
'showhideselectedversions' => 'Smon-e/stërmé le version selessionà',
'editundo' => "buta 'me ch'a l'era",
'diff-empty' => '(Gnun-a diferensa)',
'diff-multi' => "({{PLURAL:$1|Na revision antërmedia|$1 revision antërmedie}} ëd {{PLURAL:$2|n'utent|$2 utent}} pa mostrà)",
'diff-multi-manyusers' => "({{PLURAL:$1|Na revision antërmedia|$1 revision antërmedie}} da pi che $2 {{PLURAL:$2|n'utent|utent}} pa mostrà)",
'difference-missing-revision' => "{{PLURAL:$2|Na revision|$2 revision}} dë sta diferensa ($1) a {{PLURAL:$2|l'é pa stàita|son pa stàite}} trovà.



Sòn a l'é normalment causà da l'andèje dapress a na veja liura stòrica a na pàgina ch'a l'é stàita scancelà. Ij detaj a peulo esse trovà ant ël [registr ëd jë scanselament ëd {{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}}].",

# Search results
'searchresults' => "Arzultà dl'arserca",
'searchresults-title' => "Arzultà dl'arserca për «$1»",
'searchresulttext' => "Për avèj pì d'anformassion ant sl'arserca interna ëd {{SITENAME}}, che a varda [[{{MediaWiki:Helppage}}|Arserca ant la {{SITENAME}}]].",
'searchsubtitle' => 'A l\'ha sërcà \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tute le pàgine che a ancamin-o con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tute le pàgine che a men-o a "$1"]])',
'searchsubtitleinvalid' => 'Domanda "$1"',
'toomanymatches' => "Parèj a-i ven fòra tròpa ròba, për piasì, ch'a preuva n'arserca diferenta.",
'titlematches' => "Ant ij tìtoj dj'artìcoj",
'notitlematches' => "La vos che a l'ha ciamà a l'é pa trovasse antrames aj tìtoj dj'articoj",
'textmatches' => "Ant ël test ëd j'artìcoj",
'notextmatches' => "La vos che a l'ha ciamà a l'é pa trovasse antrames aj test dj'artìcoj",
'prevn' => 'ij {{PLURAL:$1|$1}} prima',
'nextn' => 'ij {{PLURAL:$1|$1}} peuj',
'prevn-title' => '$1 {{PLURAL:$1|arzultà|arzultà}} prima',
'nextn-title' => '$1 {{PLURAL:$1|arzultà|arzultà}} apress',
'shown-title' => 'Smon-e $1 {{PLURAL:$1|arzultà|arzultà}} për pàgina',
'viewprevnext' => 'Che a varda ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => "Opsion d'arserca",
'searchmenu-exists' => "'''A-i é na pàgina ciamà \"[[:\$1]]\" dzora a costa wiki'''",
'searchmenu-new' => "'''Creé la pàgina «[[:$1]]» ansima a sta wiki-sì!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Visualisé le pàgine con sto prefiss-sì]]',
'searchprofile-articles' => 'Pàgine ëd contnù',
'searchprofile-project' => "Pàgine d'agiut e ëd proget",
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Tut',
'searchprofile-advanced' => 'Avansà',
'searchprofile-articles-tooltip' => 'Sërché an $1',
'searchprofile-project-tooltip' => 'Sërché an $1',
'searchprofile-images-tooltip' => "Sërché dj'archivi",
'searchprofile-everything-tooltip' => 'Sërché daspërtut (ëdcò ant le pàgine ëd discussion)',
'searchprofile-advanced-tooltip' => 'Sërché ant jë spassi nominaj përsonalisà',
'search-result-size' => '$1 ({{PLURAL:$2|un|$2}} mòt)',
'search-result-category-size' => '{{PLURAL:$1|1 mèmber|$1 mèmber}} ({{PLURAL:$2|1 sot-categorìa|$2 sot-categorìe}}, {{PLURAL:$3|1 archivi|$3 archivi}})',
'search-result-score' => 'Arlevansa: $1%',
'search-redirect' => '(ridiression $1)',
'search-section' => '(session $1)',
'search-suggest' => 'Vorìi-lo pa dì: $1',
'search-interwiki-caption' => 'Proget frej',
'search-interwiki-default' => 'Arzultà da $1:',
'search-interwiki-more' => '(ëd pì)',
'search-relatedarticle' => 'Corelà',
'mwsuggest-disable' => "Disabilité ij sugeriment d'arserca",
'searcheverything-enable' => 'Sërché ant tùit jë spassi nominaj',
'searchrelated' => 'corelà',
'searchall' => 'tuti',
'showingresults' => "Ambelessì-sota a treuva fin a {{PLURAL:$1|'''1'''|'''$1'''}} arzultà, a parte dal nùmer #'''$2'''.",
'showingresultsnum' => "Ambelessì-sota a treuva {{PLURAL:$3|'''1'''|'''$3'''}} arzultà a parte da #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Arzultà '''$1''' ëd '''$3'''|Arzultà '''$1 - $2''' ëd '''$3'''}} për '''$4'''",
'nonefound' => "'''Nòta''': për stàndard a s'arserca mach an chèich ëspassi nominal.
Ch'a preuva a gionté dnans a soa arserca ël prefiss ''all:'' për sërché an tùit jë spassi nominaj (comprèis le discussion, jë stamp, e via fòrt), o ch'a deuvra lë spassi nominal vorsù com prefiss.",
'search-nonefound' => "A-i é gnun arzultà për l'arserca.",
'powersearch' => 'Arserca avansà',
'powersearch-legend' => 'Arserca avansà',
'powersearch-ns' => 'Sërché ant jë spassi nominaj:',
'powersearch-redir' => 'Smon-e le ridiression',
'powersearch-field' => 'Sërché',
'powersearch-togglelabel' => 'Buté na marca:',
'powersearch-toggleall' => 'Tùit',
'powersearch-togglenone' => 'Gnun',
'search-external' => 'Arserca esterna',
'searchdisabled' => "L'arserca anterna ëd {{SITENAME}} a l'é nen abilità; për adess a peul prové a dovré un motor d'arserca estern coma Google. (Però che a ten-a da ment che ij contnù ëd {{SITENAME}} listà ant ij motor pùblich a podrìo ëdcò esse nen d'autut agiornà)",
'search-error' => "A l'é rivaje n'eror durant l'arserca: $1",

# Preferences page
'preferences' => 'Mè gust',
'mypreferences' => 'Gust',
'prefs-edits' => 'Nùmer ëd modìfiche fàite:',
'prefsnologin' => "A l'é ancó pa rintrà ant ël sistema",
'prefsnologintext' => 'A deuv esse <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} intrà ant ël sistema]</span> për amposté ij sò gust.',
'changepassword' => 'Cangé la ciav',
'prefs-skin' => 'Facia',
'skin-preview' => 'Preuva',
'datedefault' => "Franch l'istess",
'prefs-beta' => 'Caraterìstiche dla Beta',
'prefs-datetime' => 'Data e ora',
'prefs-labs' => 'Caraterìstiche dël laboratòri',
'prefs-user-pages' => 'Pàgine utent',
'prefs-personal' => "Profil dl'utent",
'prefs-rc' => 'Ùltime modìfiche',
'prefs-watchlist' => 'Ròba che as ten sot euj',
'prefs-watchlist-days' => 'Vàire dì che a veul ës-ciairé an soa lista ëd lòn che as ten sot euj:',
'prefs-watchlist-days-max' => 'Al pì $1 {{PLURAL:$1|di|di}}',
'prefs-watchlist-edits' => 'Vàire modìfiche che a veul ës-ciairé con le funsion avansà:',
'prefs-watchlist-edits-max' => 'Nùmer màssim: 1000',
'prefs-watchlist-token' => 'Geton ëd lòn che as ten sot euj:',
'prefs-misc' => 'Sòn e lòn',
'prefs-resetpass' => 'Cangé la ciav',
'prefs-changeemail' => "Cangé l'adrëssa ëd pòsta eletrònica",
'prefs-setemail' => "Amposté n'adrëssa ëd pòsta eletrònica",
'prefs-email' => 'Opsion ëd pòsta eletrònica',
'prefs-rendering' => 'Sembiansa',
'saveprefs' => 'Salvé ij sò gust',
'resetprefs' => 'Buté torna ij "mè gust" coma a-i ero al prinsipi',
'restoreprefs' => "Buté torna j'ampostassion dë stàndard (an tute le session)",
'prefs-editing' => 'Quàder ëd modìfica dël test',
'rows' => 'Righe:',
'columns' => 'Colòne:',
'searchresultshead' => "Specifiché soe preferense d'arserca",
'resultsperpage' => 'Arzultà da mostré për vira pàgina:',
'stub-threshold' => 'Valor mìnim për j\'<a href="#" class="stub">anliure a jë sbòss</a>:',
'stub-threshold-disabled' => 'Disabilità',
'recentchangesdays' => "Vàire dì smon-e ant j'ùltime modìfiche:",
'recentchangesdays-max' => '(al pì $1 {{PLURAL:$1|di|di}})',
'recentchangescount' => 'Nùmer ëd modìfiche da smon-e për stàndard:',
'prefs-help-recentchangescount' => "Sòn a comprend j'ùltime modìfiche, le stòrie dle pàgine e ij registr.",
'prefs-help-watchlist-token2' => "Costa a l'é la ciav segreta dël fluss an sl'Aragnà dla lista ëd lòn ch'as ten sot-euj.
Qualsëssìa përson-a ch'a la conòssa a podrà lese la lista ëd lòn che chiel a ten sot-euj, ch'a-j la mostra donca a gnun.
[[Special:ResetTokens|Ch'a sgnaca ambelessì s'a l'ha damanca d'ampostela torna]].",
'savedprefs' => 'Ij sò gust a son ëstàit salvà.',
'timezonelegend' => 'Fus orari:',
'localtime' => 'Ora local:',
'timezoneuseserverdefault' => 'Dovré lë stàndard ëd la wiki ($1)',
'timezoneuseoffset' => 'Àutr (spessifiché la diferensa)',
'timezoneoffset' => 'Diferensa oraria¹:',
'servertime' => 'Ora dël servent:',
'guesstimezone' => "Ciapa sù l'ora da 'nt ël mè programa ëd navigassion (browser)",
'timezoneregion-africa' => 'Àfrica',
'timezoneregion-america' => 'América',
'timezoneregion-antarctica' => 'Antàrtid',
'timezoneregion-arctic' => 'Àrtich',
'timezoneregion-asia' => 'Asia',
'timezoneregion-atlantic' => 'Océan Atlàntich',
'timezoneregion-australia' => 'Australia',
'timezoneregion-europe' => 'Euròpa',
'timezoneregion-indian' => 'Océan Indian',
'timezoneregion-pacific' => 'Océan Passìfich',
'allowemail' => "Lassa che j'àutri utent am mando ëd mëssagi ëd pòsta eletrònica",
'prefs-searchoptions' => 'Sërca',
'prefs-namespaces' => 'Spassi nominaj',
'defaultns' => 'Dësnò, sërché an costi spassi nominaj-sì:',
'default' => 'stàndard',
'prefs-files' => 'Archivi',
'prefs-custom-css' => 'CSS përsonaj',
'prefs-custom-js' => 'JS përsonaj',
'prefs-common-css-js' => 'CSS e JS condividù për tute le pej:',
'prefs-reset-intro' => 'A peul dovré costa pàgina për amposté torna ij sò gust a coj dë stàndard.
Sòn a peul pa esse anulà.',
'prefs-emailconfirm-label' => "Conferma dl'adrëssa ëd pòsta eletrònica:",
'youremail' => 'Soa adrëssa ëd pòsta eletrònica:',
'username' => '{{GENDER:$1|Stranòm}}:',
'uid' => "Identificativ dl'{{GENDER:$1|utent}}:",
'prefs-memberingroups' => '{{GENDER:$2|Mèmber}} {{PLURAL:$1|dla partìa|dle partìe}}:',
'prefs-memberingroups-type' => '$1',
'prefs-registration' => 'Data ëd registrassion:',
'prefs-registration-date-time' => '$1',
'yourrealname' => 'Nòm vèir:',
'yourlanguage' => 'Lenga:',
'yourvariant' => 'Variant ëd la lenga dël contnù:',
'prefs-help-variant' => 'Varianta o ortografìa ëd sò gust për smon-e le pàgine ëd contnù ansima a sta wiki-sì.',
'yournick' => 'Sò stranòm (për firmé):',
'prefs-help-signature' => 'Ij coment an sle pàgine ëd discussion a dovrìo esse firmà con "<nowiki>~~~~</nowiki>" che a sarà convertì ant soa firma e orari.',
'badsig' => "Soa firma a l'é nen giusta, che a controla j'istrussion HTML.",
'badsiglength' => "Sò stranòm a l'é tròp longh.
A deuv nen esse pì longh che $1 {{PLURAL:$1|caràter|caràter}}.",
'yourgender' => "'Me ch'a preferiss esse descrivù?",
'gender-unknown' => 'I preferisso nen dilo',
'gender-male' => 'Chiel a modìfica dle pàgine dla wiki',
'gender-female' => 'Chila a modìfica dle pàgine dla wiki',
'prefs-help-gender' => "Definì coste preferense a l'é opsional.
Ël programa a deuvra sò valor për adressesse a chiel e massionelo a j'àutri an dovrand ël géner gramatical giust.
Costa anformassion a sarà pùblica.",
'email' => 'Pòsta eletrònica',
'prefs-help-realname' => '* Nòm vèir (opsional): se i sërne da butelo ambelessì a sarà dovrà për deve mérit ëd vòstr travaj.',
'prefs-help-email' => "L'adrëssa ëd pòsta eletrònica a l'é opsional, ma a-i n'a j'é dabzògn për torna amposté la ciav, s'a dovèissa dësmentié soa ciav.",
'prefs-help-email-others' => "A peul ëdcò serne ëd lassé che j'àutri a lo contato a travers soa pàgina utent o ëd ciaciarada sensa ch'a-i sia da manca d'arvelé soa identità.",
'prefs-help-email-required' => "A-i va l'adrëssa ëd pòsta eletrònica.",
'prefs-info' => 'Anformassion ëd base',
'prefs-i18n' => 'Antërnassionalisassion',
'prefs-signature' => 'Firma',
'prefs-dateformat' => 'Formà dla data',
'prefs-timeoffset' => "Diferensa d'ora",
'prefs-advancedediting' => 'Opsion generaj',
'prefs-editor' => 'Redator',
'prefs-preview' => 'Preuva',
'prefs-advancedrc' => 'Opsion avansà',
'prefs-advancedrendering' => 'Opsion avansà',
'prefs-advancedsearchoptions' => 'Opsion avansà',
'prefs-advancedwatchlist' => 'Opsion avansà',
'prefs-displayrc' => 'Opsion ëd visualisassion',
'prefs-displaysearchoptions' => 'Opsion ëd visualisassion',
'prefs-displaywatchlist' => 'Opsion ëd visualisassion',
'prefs-tokenwatchlist' => 'Geton',
'prefs-diffs' => 'Diferense',
'prefs-help-prefershttps' => 'Costa preferensa a ancaminrà a marcé a soa pròssima conession.',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'A smija bon',
'email-address-validity-invalid' => "A-i é da manca ëd n'adrëssa bon-a!",

# User rights
'userrights' => "Gestion dij drit dj'utent",
'userrights-lookup-user' => "Gestion dle partìe d'utent",
'userrights-user-editname' => 'Che a buta në stranòm:',
'editusergroup' => "Modifiché le partìe d'utent",
'editinguser' => "Modìfiché ij drit d'utent ëd l'utent '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => "Modifiché le partìe dl'utent",
'saveusergroups' => "Salvé le partìe d'utent",
'userrights-groupsmember' => "A l'é andrinta a:",
'userrights-groupsmember-auto' => 'Mèmber amplìssit ëd:',
'userrights-groups-help' => "A peul cambié le partìe anté ch'a l'é st'utent-sì:
* Na casela marcà a veul dì che l'utent a l'é an cola partìa.
* Na casela nen marcà a veul dì che l'utent a l'é pa an cola partìa.
* N'asterisch (*) a veul dì che a peul pa gavé cola partìa na vira ch'a l'abia giontala, o viceversa.",
'userrights-reason' => 'Rason:',
'userrights-no-interwiki' => "A l'ha pa ij përmess dont a fa da manca për podèj cambieje ij drit a dj'utent ansima a dj'àutre wiki.",
'userrights-nodatabase' => "La base ëd dat $1 a-i é pa, ò pura a l'é nen local.",
'userrights-nologin' => "A l'ha da [[Special:UserLogin|rintré ant ël sistema]] con un cont da aministrator për podej-je dé dij drit a j'utent.",
'userrights-notallowed' => "Chiel a l'ha pa ij përmess për dé o gavé dij drit a j'utent.",
'userrights-changeable-col' => "Partìe ch'a peul cambié",
'userrights-unchangeable-col' => "Partìe ch'a peul pa cambié",
'userrights-irreversible-marker' => '$1*',
'userrights-conflict' => "Conflit ëd modìfica ëd drit utent! Për piasì, ch'a lesa torna e ch'a confirma soe modìfiche.",
'userrights-removed-self' => "A l'ha gavà për da bin ij sò drit. Parèj a peul pa pi acede a costa pàgina.",

# Groups
'group' => 'Partìa:',
'group-user' => 'Utent',
'group-autoconfirmed' => "Utent ch'a son convalidasse daspërlor",
'group-bot' => 'Trigomiro',
'group-sysop' => 'Aministrator',
'group-bureaucrat' => 'Mangiapapé',
'group-suppress' => 'Supervisor',
'group-all' => '(utent)',

'group-user-member' => '{{GENDER:$1|utent}}',
'group-autoconfirmed-member' => "{{GENDER:$1|utent ch'a l'é convalidasse daspërchiel|utent ch'a l'é convalidasse daspërchila}}",
'group-bot-member' => '{{GENDER:$1|trigomiro}}',
'group-sysop-member' => '{{GENDER:$1|aministrator|aministratris}}',
'group-bureaucrat-member' => '{{GENDER:$1|mangiapapé}}',
'group-suppress-member' => '{{GENDER:$1|supervisor}}',

'grouppage-user' => '{{ns:project}}:Utent',
'grouppage-autoconfirmed' => "{{ns:project}}:Utent ch'a son convalidasse daspërlor",
'grouppage-bot' => '{{ns:project}}:Trigomiro',
'grouppage-sysop' => '{{ns:project}}:Aministrator',
'grouppage-bureaucrat' => '{{ns:project}}:Mangiapapé',
'grouppage-suppress' => '{{ns:project}}:Supervisor',

# Rights
'right-read' => 'Lese le pàgine',
'right-edit' => 'Modifiché le pàgine',
'right-createpage' => 'Creé dle pàgine (che a son pa dle pàgine ëd discussion)',
'right-createtalk' => 'Creé dle pàgine ëd discussion',
'right-createaccount' => 'Creé dij cont utent neuv',
'right-minoredit' => 'Marché le modìfiche com cite',
'right-move' => 'Tramudé le pàgine',
'right-move-subpages' => 'Tramudé dle pàgine con soe sot-pàgine',
'right-move-rootuserpages' => "Tramudé le pàgine prinsipaj ëd j'utent",
'right-movefile' => "Tramudé j'archivi",
'right-suppressredirect' => 'Creé nen ëd ridiression da la pàgina sorgiss an tramudand le pàgine',
'right-upload' => "Carié d'archivi",
'right-reupload' => "Coaté n'archivi esistent",
'right-reupload-own' => "Coaté d'archivi esistent carià da l'utent midem",
'right-reupload-shared' => "Coaté an local j'archivi ant ël depòsit dij mojen partagià",
'right-upload_by_url' => "Carié dj'archivi da n'adrëssa an sl'aragnà",
'right-purge' => 'Polidé la memòria local ëd na pàgina sensa ciamé conferma',
'right-autoconfirmed' => "Nen esse tocà dal lìmit d'assion basà an sl'IP",
'right-bot' => 'Esse tratà com un process automàtich',
'right-nominornewtalk' => "Fé nen comparì l'avis ëd mëssagi neuv, an fasend ëd modìfiche cite a le pàgine ëd discussion",
'right-apihighlimits' => "Dovré ël lìmit pì àut ant j'anterogassion API",
'right-writeapi' => "Dovré l'API dë scritura",
'right-delete' => 'Scancelé dle pàgine',
'right-bigdelete' => 'Scancelé dle pàgine con na stòria longa',
'right-deletelogentry' => 'Scancelé e ripristiné dle vos ëd registr specìfiche',
'right-deleterevision' => 'Scancelé e disdëscancelé na version ëspessìfica ëd na pàgina',
'right-deletedhistory' => 'Vardé le revision ëscancelà ëd la stòria, sensa sò test',
'right-deletedtext' => 'Vëdde ël test ëscancelà e le modìfiche antra le revision ëscancelà',
'right-browsearchive' => 'Sërché dle pàgine scancelà',
'right-undelete' => 'Arcuperé na pàgina',
'right-suppressrevision' => "Esaminé e arcuperé le revision stërmà da j'aministrator",
'right-suppressionlog' => 'Vardé ij registr privà',
'right-block' => "Bloché le modìfiche d'àutri utent",
'right-blockemail' => "Bloché n'utent da mandé 'd mëssagi an pòsta eletrònica",
'right-hideuser' => 'Bloché un nòm utent, stërmandlo al pùblich',
'right-ipblock-exempt' => "Dëscavalché ij blocagi ëd j'IP, ij blocagi automàtich e ij blocagi ëd partìe d'IP",
'right-proxyunbannable' => "Dëscavalché ij blòch automatich dij servent d'anonimà",
'right-unblockself' => 'Dësblochesse da soj',
'right-protect' => 'Cambié ij livej ëd protession e modifiché le pàgine protegiùe an cascada',
'right-editprotected' => 'Modifiché le pàgine protegiùe con «{{int:protect-level-sysop}}»',
'right-editsemiprotected' => 'Modifiché le pàgine protegiùe con «{{int:protect-level-autoconfirmed}}»',
'right-editinterface' => "Modifiché l'antërfacia utent",
'right-editusercssjs' => "Modifiché j'archivi CSS e JavaScript d'àutri utent",
'right-editusercss' => "Modifiché j'archivi CSS d'àutri utent",
'right-edituserjs' => "Modifiché j'archivi JavaScript d'àutri utent",
'right-editmyusercss' => 'Modifiché ij sò archivi CSS utent',
'right-editmyuserjs' => 'Modifiché ij sò archivi JavaScript utent',
'right-viewmywatchlist' => "Vëdde la lista ëd lòn ch'as ten sot-euj",
'right-editmywatchlist' => "Modifiché la lista ëd lòn ch'as ten sot-euj. Ch'a nòta che chèiche assion a giontran ancora dle pàgine sensa 's drit.",
'right-viewmyprivateinfo' => "Vëdde ij sò dàit përsonaj (pr'esempi adrëssa ëd pòsta eletrònica, nòm ver)",
'right-editmyprivateinfo' => "Modifiché ij sò dàit privà (pr'esempi adrëssa ëd pòsta eletrònica, nòm ver)",
'right-editmyoptions' => 'Modifiché ij sò gust',
'right-rollback' => "Gavé an pressa le modìfiche ëd l'ùltim utent che a l'ha modificà na pàgina particolar",
'right-markbotedits' => "Marché le modìfiche tirà andré com modìfiche d'un trigomiro",
'right-noratelimit' => "Nen esse tocà dal lìmit d'assion",
'right-import' => "Amporté dle pàgine da d'àutre wiki",
'right-importupload' => "Amporté dle pàgine da n'archivi carià",
'right-patrol' => "Marché le modìfiche dj'àutri com verificà",
'right-autopatrol' => 'Avèj na pròpria modìfica automaticament marcà com verificà',
'right-patrolmarks' => "Vëdde le marche ëd verìfica ant j'ùltime modìfiche",
'right-unwatchedpages' => 'Vëdde na lista dle pàgine nen cudìe',
'right-mergehistory' => 'Fonde la stòria dle pàgine',
'right-userrights' => "Modifiché tùit ij drit ëd n'utent",
'right-userrights-interwiki' => "Modifiché ij drit utent dj'utent ansima a d'àutre wiki",
'right-siteadmin' => 'Bloché e dësbloché la base ëd dàit',
'right-override-export-depth' => 'Esporté le pàgine ancludend le pàgine colegà fin-a a na profondeur ëd 5',
'right-sendemail' => "Mandé un mëssagi an pòsta eletrònica a j'àutri utent",
'right-passwordreset' => 'Vëdde ij mëssagi ëd pòsta eletrònica ëd riampostassion dle ciav',

# Special:Log/newusers
'newuserlogpage' => "Registr dla creassion dj'utent",
'newuserlogpagetext' => "Sossì a l'é un registr andova ch'as marco le creassion dj'utent.",

# User rights log
'rightslog' => "Argistr dij drit ëd j'utent",
'rightslogtext' => "Costa a l'é na lista dij cambiament aj drit ëd j'utent.",

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lese sta pàgina-sì',
'action-edit' => 'modifiché costa pàgina',
'action-createpage' => 'creé dle pàgine',
'action-createtalk' => 'creé dle pàgine ëd discussion',
'action-createaccount' => 'creé ës cont utent',
'action-minoredit' => 'marché sta modìfica-sì com minor',
'action-move' => 'tramudé sta pàgina-sì',
'action-move-subpages' => 'tramudé sta pàgina-sì e soe sot-pàgine',
'action-move-rootuserpages' => "tramudé le pàgine prinsipaj dj'utent",
'action-movefile' => "tramudé cost'archivi",
'action-upload' => "carié st'archivi",
'action-reupload' => 'coaté cost archivi esistent',
'action-reupload-shared' => 'sorpassé cost archivi ant un depòsit partagià',
'action-upload_by_url' => "carié s'archivi da n'adrëssa an sl'Aragnà",
'action-writeapi' => "dovré l'API dë scritura",
'action-delete' => 'scancelé sta pàgina-sì',
'action-deleterevision' => 'scancelé sta revision-sì',
'action-deletedhistory' => 'vardé la stòria scancelà dë sta pàgina-sì',
'action-browsearchive' => 'sërché dle pàgine scancelà',
'action-undelete' => 'arcuperé sta pàgina-sì',
'action-suppressrevision' => 'rivëdde e arcuperé sta revision stërmà-sì',
'action-suppressionlog' => 'vardé sto registr privà-sì',
'action-block' => 'bloché cost utent-sì a modifiché',
'action-protect' => 'cambié ij livej ëd protession për sta pàgina-sì',
'action-rollback' => "gavé an pressa le modìfiche ëd l'ùltim utent che a l'ha modificà na pàgina particolar",
'action-import' => "amporté dle pàgine da n'àutra wiki",
'action-importupload' => "amporté dle pàgine da n'archivi carià",
'action-patrol' => "marché la modìfica dj'àutri com verificà",
'action-autopatrol' => 'avèj soe modìfiche marcà com verificà',
'action-unwatchedpages' => 'vardé la lista dle pàgine che gnun a ten sot-euj',
'action-mergehistory' => 'fonde la stòria dë sta pàgina-sì',
'action-userrights' => "modifiché tùit ij drit dj'utent",
'action-userrights-interwiki' => "modifiché ij drit ëd j'utent ansima a d'àutre wiki",
'action-siteadmin' => 'bloché o dësbloché la base ëd dàit',
'action-sendemail' => 'mandé dij mëssage an pòsta eletrònica',
'action-editmywatchlist' => "modifiché la lista ëd la ròba ch'as ten sot-euj",
'action-viewmywatchlist' => "vëdde la lista ëd la ròba ch'as ten sot-euj",
'action-viewmyprivateinfo' => 'vëdde soe anformassion përsonaj',
'action-editmyprivateinfo' => 'modifiché soe anformassion përsonaj',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|modìfica|modìfiche}}',
'enhancedrc-since-last-visit' => "$1 {{PLURAL:$1|da l'ùltima visita}}",
'enhancedrc-history' => 'stòria',
'recentchanges' => 'Ùltime modìfiche',
'recentchanges-legend' => "Opsion dj'ùltime modìfiche",
'recentchanges-summary' => 'An costa pàgina as ten cont dle modìfiche pì recente a la wiki.',
'recentchanges-noresult' => 'Gnun-e modìfiche corëspondente a costi criteri ant ël perìod dàit.',
'recentchanges-feed-description' => 'Trassé le modìfiche dla wiki pì davzin-e ant ël temp an cost fluss.',
'recentchanges-label-newpage' => "Sta modìfica-sì a l'ha creà na neuva pàgina",
'recentchanges-label-minor' => "Costa a l'é na modìfica cita",
'recentchanges-label-bot' => "Sa modìfica a l'é stàita fàita da un trigomiro",
'recentchanges-label-unpatrolled' => "Sta modìfica-sì a l'é pa ancó stàita verificà",
'rcnote' => "Ambelessì sota a-i {{PLURAL:$1|é '''1''' modìfica|son j'ùltime '''$1''' modìfiche}} ant j'ùltim {{PLURAL:$2|di|'''$2''' di}}, a parte da $5 dël $4.",
'rcnotefrom' => ' Ambelessì sota a-i é la lista dle modìfiche da <b>$2</b> (fin-a a <b>$1</b>).',
'rclistfrom' => 'Mostré le modìfiche a parte da $1',
'rcshowhideminor' => '$1 le modìfiche cite',
'rcshowhidebots' => '$1 ij trigomiro',
'rcshowhideliu' => "$1 j'utent registrà",
'rcshowhideanons' => "$1 j'utent anònim",
'rcshowhidepatr' => '$1 le modìfiche verificà',
'rcshowhidemine' => '$1 mie modìfiche',
'rclinks' => "Mostré j'ùltime $1 modìfiche ëd j'ùltim $2 dì<br />$3",
'diff' => 'dif.',
'hist' => 'stòria',
'hide' => 'Stërmé',
'show' => 'Smon-e',
'minoreditletter' => 'c',
'newpageletter' => 'N',
'boteditletter' => 't',
'number_of_watching_users_pageview' => "[tnùa sot-euj da {{PLURAL:$1|n'utent|$1 utent}}]",
'rc_categories' => 'Limité a le categorìe (che a jë scriva separand-je antra \'d lor con un "|")',
'rc_categories_any' => 'Qualsëssìa',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|byte}} apress ij cambi',
'newsectionsummary' => '/* $1 */ session neuva',
'rc-enhanced-expand' => 'Mostré ij detaj',
'rc-enhanced-hide' => 'Stërmé ij detaj',
'rc-old-title' => 'originalment creà com "$1"',

# Recent changes linked
'recentchangeslinked' => 'Modìfiche colegà',
'recentchangeslinked-feed' => 'Modìfiche colegà',
'recentchangeslinked-toolbox' => 'Modìfiche colegà',
'recentchangeslinked-title' => 'Modìfiche ch\'a-i intro con "$1"',
'recentchangeslinked-summary' => "Costa a l'é na lista ëd modìfiche fàite da pòch a pàgine colegà a cola spessificà (o a mèmber ëd na categorìa spessificà).
Le pàgine dzora a [[Special:Watchlist|la lista ëd lòn ch'as ten sot-euj]] a resto marcà an '''grassèt'''.",
'recentchangeslinked-page' => 'Nòm ëd la pàgina:',
'recentchangeslinked-to' => 'Mostré nopà le modìfiche a le pàgine colegà a cola dàita',

# Upload
'upload' => "Carié n'archivi",
'uploadbtn' => "Carié l'archivi",
'reuploaddesc' => "Chité e torné al formolari për carié dj'archivi",
'upload-tryagain' => "Mandé la descrission ëd l'archivi modificà",
'uploadnologin' => 'Nen rintrà ant ël sistema',
'uploadnologintext' => "A dev $1 për podèj carié dj'archivi.",
'upload_directory_missing' => 'Ël repertòri ëd caria ($1) a-i é nen e a peul pa esse creà dal servent.',
'upload_directory_read_only' => "Ël servent ëd l'aragnà a-i la fa nen a scrive ansima a la diretris ëd càrich ($1).",
'uploaderror' => 'Eror dëmentré che as cariava',
'upload-recreate-warning' => "'''Atension: n'archivi con col nòm a l'é già stàit ëscancelà o tramudà.'''

Ël registr dle scancelassion e dij tramud për sta pàgina a l'é butà ambelessì për comodità:",
'uploadtext' => "Dovra ël formolari sì-sota për carié dj'archivi.
Për vardé ò sërché dle figure già carià, ch'a vada an sla [[Special:FileList|lista dle figure]], ij (ri)càrich a son ëdcò registrà ant ël [[Special:Log/upload|registr dij càrich]], jë scancelament ant ël [[Special:Log/delete|registr djë scancelament]].

Për buté na figura ant n'artìcol, dovré n'anliura ant un-a dle forme sì sota:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' për dovré la version pien-a dla figura
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' për dovré na dimension ëd 200 pontin ant un quàder a la bordura snistra con 'alt text' com descrission
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' për coleghé diretament la figura sensa fé vëdde l'archivi",
'upload-permitted' => "Sòrt d'archivi consentìe: $1.",
'upload-preferred' => "Sòrt d'archivi preferìe: $1.",
'upload-prohibited' => "Sòrt d'archivi proibìe: $1.",
'uploadlog' => 'Registr dij càrich',
'uploadlogpage' => 'Registr dij càrich',
'uploadlogpagetext' => "Ambelessì-sota a-i é na lista dj'ùltim archivi carià.
Beiché la [[Special:NewFiles|galarìa dj'archivi neuv]] për na presentassion pì visual.",
'filename' => "Nòm dl'archivi",
'filedesc' => 'Oget',
'fileuploadsummary' => "Detaj dl'archivi:",
'filereuploadsummary' => "Modìfiche dl'archivi:",
'filestatus' => "Situassion dij drit d'autor:",
'filesource' => 'Sorgiss:',
'uploadedfiles' => 'Archivi carià',
'ignorewarning' => "Lassé perde j'avis e salvé an tute le manere",
'ignorewarnings' => "Lassé perde j'avis",
'minlength1' => "Ij nòm ëd j'archivi a devo esse longh almanch un caràter.",
'illegalfilename' => 'Ël nòm d\'archivi "$1" a l\'ha andrinta dij caràter che as peulo pa dovresse ant ij tìtoj dle pàgine. Për piasì che a-j cangia \'d nòm e peui che a torna a carielo.',
'filename-toolong' => "Ij nòm dj'archivi a peul pa esse pi longh che 240 byte.",
'badfilename' => 'Ël nòm dl\'archivi a l\'é stait cambià an "$1".',
'filetype-mime-mismatch' => 'L\'estension dl\'archivi ".$1" a rispeta pa la sòrt ëd MIME trovà për l\'archivi ($2).',
'filetype-badmime' => 'J\'archivi dla sòrt MIME "$1" as peulo pa carié.',
'filetype-bad-ie-mime' => 'As peul pa carié st\'archivi-sì përchè Internet Explorer a podrìa considerelo com "$1", che a l\'é na rassa d\'archivi vietà e potensialment pericolos.',
'filetype-unwanted-type' => "'''\".\$1\"''' a l'é na sòrt d'archivi ch'as pija nen ëd bon-a veuja.
{{PLURAL:\$3|La sòrt preferìa a l'é|Le sòrt preferìe a son}} \$2.",
'filetype-banned-type' => "'''\".\$1\"''' {{PLURAL:\$4|a l'é na sòrt d'archivi proibìa|a son ëd sòrt d'archivi proibìe}}.
{{PLURAL:\$3|La sòrt d'archivi consentìa a l'é|Le sòrt d'archivi consentìe a son}} \$2.",
'filetype-missing' => "A l'archivi a-j manca l'estension (pr'esempi \".jpg\").",
'empty-file' => "L'archivi ch'a l'ha mandà a l'era veuid.",
'file-too-large' => "L'archivi ch'a l'ha mandà a l'era tròp gròss.",
'filename-tooshort' => "Ël nòm ëd l'archivi a l'é tròp curt.",
'filetype-banned' => "Costa sòrt d'archivi a l'é proibìa.",
'verification-error' => "Cost archivi a l'ha pa passà la verìfica dj'archivi.",
'hookaborted' => "La modìfica ch'a l'ha provà a fé a l'é stàita blocà dal gancio ëd n'estension.",
'illegal-filename' => "Ël nòm dl'archivi a l'é nen consentì.",
'overwrite' => "Dzorascrive ansima a n'archivi esistent a l'é nen përmëttù.",
'unknown-error' => "A l'é capitaje n'eror nen conossù.",
'tmp-create-error' => "A l'é nen podusse creé l'archivi temporani.",
'tmp-write-error' => "Eror dë scritura dl'archivi temporani.",
'large-file' => "La racomandassion a l'é che j'archivi a sio nen pì gròss che $1; st'archivi-sì a l'amzura $2.",
'largefileserver' => "St'archivi-sì a resta pì gròss che lòn che la màchina sentral a përmet.",
'emptyfile' => "L'archivi che a l'ha pen-a carià a smija veujd.
Sòn a podrìa esse rivà përchè che chiel a l'ha scrivù mal ël nòm dl'archivi midem.
Për piasì che a contròla se a l'é pro cost l'archivi che a veul carié.",
'windows-nonascii-filename' => "Sta wiki-sì a manten pa ij nòm d'archivi con caràter speciaj.",
'fileexists' => "N'archivi con ës nòm-sì a-i é già, për piasì che a contròla <strong>[[:$1]]</strong> se a l'é pa sigur dë vorèj cangelo.
[[$1|thumb]]",
'filepageexists' => "La pàgina ëd descrission për st'archivi-sì a l'é già stàita creà an <strong>[[:$1]]</strong>, mach ch'a-i é gnun archivi ch'as ciama parèj.
Lòn ch'a buta për somari as ës-ciairerà nen ant la pàgina ëd descrission.
Për podèj buté sò somari a l'ha da modifichesse la pàgina a man.
[[$1|thumb]]",
'fileexists-extension' => "N'archivi con ës nòm-sì a-i é già: [[$2|thumb]]
* Nòm dl'archivi ch'as carìa: <strong>[[:$1]]</strong>
* Nòm dl'archivi ch'a-i é già: <strong>[[:$2]]</strong>
Për piasì, ch'a serna un nòm diferent.",
'fileexists-thumbnail-yes' => "L'archivi a jë smija a na ''figurin-a''. [[$1|thumb]]
Për piasì, ch'a contròla l'archivi <strong>[[:$1]]</strong>.
S'a l'é la midema figura a amzura pijn-a, a veul dì ch'a fa nen dë manca dë carié na figurin-a.",
'file-thumbnail-no' => "Ël nòm dl'archivi as anandia con <strong>$1</strong>.
A jë smija a na ''figurin-a''.
Se a l'ha na figura a amzura pijn-a a l'é mej ch'a carìa cola-lì, dësnò ch'a-j cangia nòm a l'archivi, për piasì.",
'fileexists-forbidden' => "Belavans n'archivi con ës nòm-sì a-i é già, donca ël nòm as peul pa pì dovresse.
Se a veul ancó cariè sò archivi, për piasì ch'a torna andré e ch'a deuvra n'àutr nòm. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Belavans n'archivi con ës nòm-sì ant la diretris dj'archivi condivis a-i é già.
Se a veul ancó carié sò archivi, për piasì ch'a torna andré e ch'a deuvra un nòm diferent. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate' => "S'archivi a l'é un duplicà ëd {{PLURAL:$1|cost-sì|costi-sì}}:",
'file-deleted-duplicate' => "N'archivi idéntich a cost-sì ([[:$1]]) a l'é scàit ëscancelà an passà.
A dovrìa controlé la stòria djë scancelament ëd l'archivi prima ëd carielo torna.",
'uploadwarning' => 'Avis che i soma dapress a carié',
'uploadwarning-text' => "Për piasì, ch'a modìfica la descrission ëd l'archivi sì-sota e ch'a preuva torna.",
'savefile' => "Salvé l'archivi",
'uploadedimage' => 'a l\'ha carià "[[$1]]"',
'overwroteimage' => 'a l\'ha carìa na version neuva ëd "[[$1]]"',
'uploaddisabled' => 'Càrich blocà.',
'copyuploaddisabled' => "Ël càrich për mojen ëd n'adrëssa dl'aragnà a l'é disabilità.",
'uploadfromurl-queued' => "Sò càrich a l'é stàit butà an coa.",
'uploaddisabledtext' => "La possibilità ëd carié dj'archivi a l'é staita disabilità.",
'php-uploaddisabledtext' => "Ij cariament d'archivi a son disabilità an PHP.
Për piasì, ch'a controla l'ampostassion file_uploads.",
'uploadscripted' => "St'archivi-sì a l'ha andrinta chèich-còs (dël còdes HTML ò pura un senari) che a podrìa esse travajà mal da chèich programa ëd navigassion.",
'uploadvirus' => "St'archivi-sì a l'han andrinta un '''vìrus!''' Detaj: $1",
'uploadjava' => "L'archivi a l'é n'archivi ZIP ch'a conten n'archivi Java .class.
As peulo pa cariesse dj'archivi Java, përché a peulo causé l'agirament ëd le restrission ëd sicurëssa.",
'upload-source' => 'Archivi sorgiss',
'sourcefilename' => "Nòm dl'archivi sorgiss:",
'sourceurl' => "Adrëssa dl'aragnà sorgiss:",
'destfilename' => "Nòm dl'archivi ëd destinassion:",
'upload-maxfilesize' => "Dimension màssima ëd l'archivi: $1.",
'upload-description' => "Descrission dl'archivi",
'upload-options' => 'Opsion për carié',
'watchthisupload' => "Ten-e d'euj s'archivi.",
'filewasdeleted' => "N'archivi con ës nòm-sì a l'é già stàit carià e peui scancelà. Për piasì, che a verìfica $1 anans che carielo n'àutra vira.",
'filename-bad-prefix' => "Ël nòm dl'archivi ch'a l'é dapress a carié as anandia për '''\"\$1\"''', ch'a l'é un nòm sensa sust, për sòlit butà-lì n'aotomàtich da le màchine fotogràfiche digitaj, basta ch'a-i në sia un. Për piasì, ch'a-j daga a sò archivi un nòm ch'a disa lòn ch'a l'é.",
'filename-prefix-blacklist' => " #<!-- ch'a lassa sta riga-sì tanme ch'a l'é --> <pre>
# La sintassi a l'é:
#   * Tut lòn ch'a-i ven dapress al segn \"#\" fin a la fin dla riga a resta mach un coment
#   * Na riga nen veujda a la resta un prefiss ëd nòm d'archivi për sòlit dovrà da na chèich màchina fotogràfica digitala
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # chèich sacociàbil
IMG # genérich
JD # Jenoptik
MGP # Pentax
PICT # vàire marche diferente
 #</pre> <!-- ch'a lassa sta riga-sì tanme ch'a l'é -->",
'upload-success-subj' => 'Carià con sucess',
'upload-success-msg' => "A l'ha carià da [$2] për da bin. Lòn ch'a l'ha carià a l'é disponìbil ambelessì: [[:{{ns:file}}:$1]]",
'upload-failure-subj' => 'Problema a carié',
'upload-failure-msg' => "A-i é staje un problema con lòn ch'a l'ha carià da [$2]:

$1",
'upload-warning-subj' => "Avis antramentre ch'as caria",
'upload-warning-msg' => "A-i era un problema con lòn ch'a l'ha carià da [$2]. A peul artorné al [[Special:Upload/stash/$1|formolari për carié]] për corege ël problema.",

'upload-proto-error' => 'Protocòl cioch',
'upload-proto-error-text' => "Për carié da dij servent lontan a venta buté dj'anliure ch'as anandio për <code>http://</code> ò pura <code>ftp://</code>.",
'upload-file-error' => 'Eror antern',
'upload-file-error-text' => "A l'é rivaie n'eror antern dëmentrè che as fasìa n'archivi provisòri ant sël servent.
Për piasì, ch'as butà an comunicassion con n'[[Special:ListUsers/sysop|aministrator]].",
'upload-misc-error' => "Eror nen identificà antramentr ch'as cariava",
'upload-misc-error-text' => "A l'é staie n'eror nen identificà dëmentrè ch'as cariava chèich-còs.
Për piasì, ch'a varda che soa anliura a sia bon-a e che a rësponda e peuj ch'a preuva torna.
Se a-i riva sossì n'àotra vira, ch'as buta an comunicassion con n'[[Special:ListUsers/sysop|aministrator]].",
'upload-too-many-redirects' => "L'adrëssa dl'aragnà a l'avìa tròpe ridiression",
'upload-unknown-size' => 'Dimension pa conossùa',
'upload-http-error' => "A l'é staje n'eror HTTP: $1.",
'upload-copy-upload-invalid-domain' => "Cariagi ëd cobie a l'é pa disponìbil da sto domini.",

# File backend
'backend-fail-stream' => "A peul pa sequensialisé l'archivi $1.",
'backend-fail-backup' => "As peul pa fesse na còpia ëd l'archivi $1.",
'backend-fail-notexists' => "L'archivi $1 a esist pa.",
'backend-fail-hashes' => "As peul pa oten-se la s-ciapura dl'archivi për paragon.",
'backend-fail-notsame' => "N'archivi nen idéntich a esist già a $1.",
'backend-fail-invalidpath' => "$1 a l'é pa un përcors ëd memorisassion bon.",
'backend-fail-delete' => "As peul pa scanselesse l'archivi $1.",
'backend-fail-describe' => "Impossìbil cangé ij metadat për l'archivi «$1».",
'backend-fail-alreadyexists' => 'L\'archivi "$1" a esist già.',
'backend-fail-store' => "As peul pa memorisesse l'archivi $1 a $2.",
'backend-fail-copy' => "As peul pa copiesse l'archivi $1 su $2.",
'backend-fail-move' => "As peul pa tramudesse l'archivi $1 su $2.",
'backend-fail-opentemp' => "As peul pa duvertesse l'archivi temporani.",
'backend-fail-writetemp' => "As peul pa scrivse sl'archivi temporani.",
'backend-fail-closetemp' => "As peul pa saresse l'archivi temporani.",
'backend-fail-read' => "As peul pa les-se l'archivi $1.",
'backend-fail-create' => "As peul pa creesse l'archivi $1.",
'backend-fail-maxsize' => "As peul pa scrivse l'archivi $1 përchè a l'é pi gròss che {{PLURAL:$2|un byte|$2 byte}}.",
'backend-fail-readonly' => "Ël dispositiv ëd memòria «$1» a l'é al moment an sola letura. La rason dàita a l'era: «$2»",
'backend-fail-synced' => "L'archivi «$1» a l'é ant në stat incoerent andrinta ai dispositiv ëd memòria intern",
'backend-fail-connect' => 'Impossìbil coleghesse al dispositiv ëd memòria «$1».',
'backend-fail-internal' => "N'eror pa conossù a l'é rivaje ant ël dispositiv ëd memòria «$1».",
'backend-fail-contenttype' => "As peul pa determinesse la sòrt ëd contnù dl'archivi da memorisé a «$1».",
'backend-fail-batchsize' => "Ël dispositiv ëd memòria a l'ha dàit un total ëd $1 {{PLURAL:$1|operassion|operassion}} d'archivi; ël lìmit a l'é $2 {{PLURAL:$1|operassion|operassion}}.",
'backend-fail-usable' => 'As peul pa les-se o scrivse l\'archivi "$1" a motiv ëd drit insuficent o liste/contnidor mancant.',

# File journal errors
'filejournal-fail-dbconnect' => 'Impossìbil coleghesse a la base ëd dàit ëd lë scartari për ël terminal ëd memorisassion «$1».',
'filejournal-fail-dbquery' => 'Impossìbil agiorné la base ëd dàit ëd lë scartari për ël terminal ëd memorisassion «$1».',

# Lock manager
'lockmanager-notlocked' => "As peul pa dësblochesse «$1»; a l'é nen blocà.",
'lockmanager-fail-closelock' => "As peul pa saresse l'archivi ëd saradura për «$1».",
'lockmanager-fail-deletelock' => "As peul pa scancelesse l'archivi ëd saradura për «$1».",
'lockmanager-fail-acquirelock' => 'As peul pa oten-se la saradura për «$1».',
'lockmanager-fail-openlock' => "As peul pa durbisse l'archivi ëd saradura për «$1».",
'lockmanager-fail-releaselock' => 'As peul pa gavé la saradura për «$1».',
'lockmanager-fail-db-bucket' => 'As peul pa contatesse a basta ëd base ëd dàit ëd saradura ant ël sëstin $1.',
'lockmanager-fail-db-release' => 'As peulo pa gavesse le saradure an sla base ëd dàit $1.',
'lockmanager-fail-svr-acquire' => 'As peul pa butesse le saradure an sël servent $1.',
'lockmanager-fail-svr-release' => 'As peulo pa arlassesse le saradure an sël servent $1.',

# ZipDirectoryReader
'zip-file-open-error' => "N'eror a l'é capità an duvertand l'archivi për ij contròj ZIP.",
'zip-wrong-format' => "L'archivi specificà a l'é pa n'archivi ZIP.",
'zip-bad' => "L'archivi a l'é n'archivi ZIP danegià o comsëssìa pa lesìbil.
A peul pa esse controlà për da bin për la sicurëssa.",
'zip-unsupported' => "L'archivi a l'é n'archivi ZIP ch'a deuvra dle caraterìstiche ZIP nen mantnùe da MediaWiki.
A peul pa esse controlà da bin për la sicurëssa.",

# Special:UploadStash
'uploadstash' => "Memorisassion d'amportassion",
'uploadstash-summary' => "Sta pàgina a dà acess a d'archivi ch'a son carià (o an mente ch'as cario) ma a son pa anco' publicà an sla wiki. Costi archivi a son pa visìbij a gnun gavà a l'utent ch'a l'ha cariaje.",
'uploadstash-clear' => "Scancelé j'archivi an memòria",
'uploadstash-nofiles' => "A l'han gnun archivi an memòria d'amportassion.",
'uploadstash-badtoken' => "L'esecussion dë st'assion a l'é pa andàita bin, miraco përchè toe credensiaj ëd modìfica a son scadùe. Preuva torna.",
'uploadstash-errclear' => "La scancelassion ëd j'archivi a l'é falìa.",
'uploadstash-refresh' => "Agiorné la lista dj'archivi",
'invalid-chunk-offset' => 'Inissi dël segment pa bon',

# img_auth script messages
'img-auth-accessdenied' => 'Acess negà',
'img-auth-nopathinfo' => "PATH_INFO mancant.
Sò servent a l'é nen ampostà për passé costa anformassion.
Peul desse ch'a sia basà an sij CGI e a peul pa mantnì img_auth.
Varda https://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir' => "Ël senté ciamà a l'é pa ant ël dossié configurà për carié.",
'img-auth-badtitle' => 'As peul pa fesse un tìtol bon për "$1".',
'img-auth-nologinnWL' => 'A l\'é pa intrà ant ël sistema e "$1" a l\'é pa ant la lista bianca.',
'img-auth-nofile' => 'L\'archivi "$1" a esist pa.',
'img-auth-isdir' => 'A l\'é an camin ch\'a preuve a intré ant un dossié "$1".
As peul mach avèj acess a j\'archivi.',
'img-auth-streaming' => 'Letura an continuà ëd "$1".',
'img-auth-public' => "La funsion d'img_auth.php a l'é dë smone dj'archivi da na wiki privà.
Sta wiki-sì a l'é configurà com na wiki pùblica.
Për na sicurëssa otimal, img_auth.php a l'é disabilità.",
'img-auth-noread' => 'L\'utent a l\'ha pa ij privilegi për lese "$1".',
'img-auth-bad-query-string' => "L'anliura a l'ha na stringa d'arcesta pa bon-a.",

# HTTP errors
'http-invalid-url' => "Adrëssa dl'aragnà pa bon-a: $1.",
'http-invalid-scheme' => 'J\'adrësse dl\'aragnà con ël prefiss "$1" a son pa mantnùe.',
'http-request-error' => "L'arcesta Http a l'é falìa për n'eror pa conossù.",
'http-read-error' => 'Eror ëd letura HTTP.',
'http-timed-out' => "L'arcesta HTTP a l'ha finì sò temp.",
'http-curl-error' => "Eror an sërcand d'arcuperé l'adrëssa dl'aragnà: $1.",
'http-bad-status' => "A l'é staje un problema durant l'arcesta HTTP: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "L'anliura a rëspond pa",
'upload-curl-error6-text' => "L'anliura che a l'ha butà a marcia pa. Për piasì, ch'a contròla che st'anliura a sia scrita giusta e che ël sit a marcia.",
'upload-curl-error28' => "A l'é finìje ël temp ch'as peul dovresse për carié",
'upload-curl-error28-text' => "Ël sit a-i buta tròp temp a rësponde. Për piasì, ch'a contròla che a l'é an pé, ch'a speta na minuta e peuj che a torna a prové. A peul esse che a-j ven-a a taj serne un moment che ës sit a sia nen tant carià ëd tràfich.",

'license' => 'Licensa:',
'license-header' => 'Licensa',
'nolicense' => 'Gnun-a selession fàita',
'license-nopreview' => "(Gnun-a preuva ch'as peula smon-se)",
'upload_source_url' => "(n'anliura bon-a e che as peula dovresse)",
'upload_source_file' => "(n'archivi da sò ordinator)",

# Special:ListFiles
'listfiles-summary' => "Sta pàgina special-sì a smon tuti j'archivi ch'a son ëstàit carià.",
'listfiles_search_for' => "Arserché un nòm d'archivi multimojen:",
'imgfile' => 'archivi',
'listfiles' => "Lista d'archivi",
'listfiles_thumb' => 'Miniadura',
'listfiles_date' => 'Data',
'listfiles_name' => 'Nòm',
'listfiles_user' => 'Utent',
'listfiles_size' => 'Amzura an otet',
'listfiles_description' => 'Descrission',
'listfiles_count' => 'Version',
'listfiles-show-all' => 'Anclude le veje version ëd le plance',
'listfiles-latestversion' => 'Version corenta',
'listfiles-latestversion-yes' => 'É',
'listfiles-latestversion-no' => 'Nò',

# File description page
'file-anchor-link' => 'Archivi',
'filehist' => "Stòria dl'archivi",
'filehist-help' => "Ch'a-i daga un colp col rat ant sna cobia data/ora për ës-ciairé coma a restèissa l'archivi ant col moment-là.",
'filehist-deleteall' => 'dëscancelé tut',
'filehist-deleteone' => 'scancelé',
'filehist-revert' => "buté torna 'me ch'a l'era",
'filehist-current' => "dël dì d'ancheuj",
'filehist-datetime' => 'Data e Ora',
'filehist-thumb' => 'Miniadura',
'filehist-thumbtext' => 'Miniadura dla version ëd $1',
'filehist-nothumb' => 'Gnun-e miniadure',
'filehist-user' => 'Utent',
'filehist-dimensions' => 'Amzure',
'filehist-filesize' => "Amzure dl'archivi",
'filehist-comment' => 'Coment',
'filehist-missing' => 'Archivi mancant',
'imagelinks' => "Usagi dl'archivi",
'linkstoimage' => "{{PLURAL:$1|La pàgina sì-sota a l'ha|Le $1 pàgine sì-sota a l'han}} andrinta dj'anliure a cost archivi:",
'linkstoimage-more' => "Pì che $1 {{PLURAL:$1|pàgina|pàgine}} a l'han dj'anliure a cost archivi.
La lista sì-sota a smon mach {{PLURAL:$1|la prima pàgina ch'a l'ha|le prime $1 pàgine ch'a l'han}} d'anliure a s'archivi.
A l'é disponìbil na [[Special:WhatLinksHere/$2|lista completa]].",
'nolinkstoimage' => "Pa gnun-a pàgina che a l'abia n'anliura a sta figura-sì.",
'morelinkstoimage' => "Vëdde [[Special:WhatLinksHere/$1|d'àutri colegament]] a s'archivi.",
'linkstoimage-redirect' => "$1 (ridiression d'archivi) $2",
'duplicatesoffile' => "{{PLURAL:$1|L'archivi sì-dapress a l'é un|Ij $1 archivi sì-dapress a son dij}} duplicà ëd s'archivi ([[Special:FileDuplicateSearch/$2|pì ëd detaj]]):",
'sharedupload' => "St'archivi-sì a ven da $1 e a peul esse dovrà da d'àutri proget.",
'sharedupload-desc-there' => "Cost archivi a riva da $1 e a peul esse dovrà da d'àutri proget.
Për piasì, vëdde la [$2 pàgina ëd descrission ëd l'archivi] per d'àutre anformassion.",
'sharedupload-desc-here' => "Cost archivi a riva da $1 e a peul esse dovrà da dj'àutri proget.
La descrission an soa [$2 pàgina ëd dëscrission ëd l'archivi] a l'é smonùa sì-sota.",
'sharedupload-desc-edit' => "St'archivi-sì a riva da $1 e a peul esse dovrà da d'àutri proget. 
Peul desse ch'a veula modifiché la descrission dzora soa [pàgina ëd descrission dl'archivi $2] ambelelà.",
'sharedupload-desc-create' => "St'archivi-sì a riva da $1 e a peul esse dovrà da d'àutri proget. 
Peul desse ch'a veula modifiché la descrission dzora soa [pàgina ëd descrission dl'archivi $2]",
'filepage-nofile' => 'A esist gnun archivi con ës nòm.',
'filepage-nofile-link' => "N'archivi con sto nòm-sì a esist pa, ma a peul [$1 carielo].",
'uploadnewversion-linktext' => "Carié na version neuva dë st'archivi-sì",
'shared-repo-from' => 'da $1',
'shared-repo' => "n'archivi condivis",
'shared-repo-name-wikimediacommons' => 'Wikimedia Commons',
'upload-disallowed-here' => 'A peul pa rampiassé cost archivi.',

# File reversion
'filerevert' => "Buté torna $1 tanme ch'a l'era",
'filerevert-legend' => "Buté torna l'archivi tanme ch'a l'era",
'filerevert-intro' => "A l'é dapress a buté torna l'archivi  '''[[Media:$1|$1]]''' com a l'era ant la [version $4 dël $2, $3].",
'filerevert-comment' => 'Rason:',
'filerevert-defaultcomment' => 'Version dël $1, $2 butà torna coma corenta.',
'filerevert-submit' => "Buté tanme ch'a l'era",
'filerevert-success' => "'''L<nowiki>'</nowiki>archivi [[Media:$1|$1]]''' a l'é stàit torna butà com a l'era ant la [$4 version dël $2, $3].",
'filerevert-badversion' => "A-i é pa gnun-a version local dl'archivi ch'a l'abia un marcatemp parèj.",

# File deletion
'filedelete' => 'Dëscancelé $1',
'filedelete-legend' => "Dëscancelé l'archivi",
'filedelete-intro' => "A l'é an broa dë scancelé l'archivi '''[[Media:$1|$1]]''' ansema a tuta soa stòria.",
'filedelete-intro-old' => "A l'é dapress ch'a scancela l'archivi '''[[Media:$1|$1]]''' dël [$4 $3, $2].",
'filedelete-comment' => 'Rason:',
'filedelete-submit' => 'Dëscancelé',
'filedelete-success' => "A l'é dëscancelasse l'archivi '''$1'''.",
'filedelete-success-old' => "La version ëd '''[[Media:$1|$1]]''' dël $3, $2 a l'é stàita scancelà.",
'filedelete-nofile' => "A-i é pa gnun archivi ch'as ciama: $1",
'filedelete-nofile-old' => "A-i é pa gnun-a version parej ëd l'archivi '''$1'''",
'filedelete-otherreason' => 'Àutra rason o rason adissional:',
'filedelete-reason-otherlist' => 'Àutra rason',
'filedelete-reason-dropdown' => "*Për sòlit la ròba a së scancela për
** violassion dij drit d'autor
** duplicassion (visadì ch'a-i era già)",
'filedelete-edit-reasonlist' => 'Modifiché la rason ëd lë scancelament',
'filedelete-maintenance' => "Lë scancelament e la restaurassion d'archivi a l'é al moment disabilità durant la manutension.",
'filedelete-maintenance-title' => "As peul pa scancelesse l'archivi",

# MIME search
'mimesearch' => 'Arserca për sòrt MIME',
'mimesearch-summary' => "Sta pàgina-sì a lassa filtré j'archivi për sòrt MIME. Buté: sòrt/sotasòrt, pr'es. <code>image/jpeg</code>.",
'mimetype' => 'Sòrt MIME:',
'download' => 'dëscarié',

# Unwatched pages
'unwatchedpages' => 'Pàgine che gnun a ten sot-euj',

# List redirects
'listredirects' => 'Lista dle ridiression',

# Unused templates
'unusedtemplates' => 'Stamp nen dovrà',
'unusedtemplatestext' => "Sta pàgina-sì a la smon tute lë pàgine ant lë spassi nominal {{ns:template}} che a son pa dovrà andrinta a d'àutre pàgine.
Ch'as visa ëd controlé che në stamp a-j serva nen a dj'àutri stamp anans che fé che ranchelo via.",
'unusedtemplateswlh' => 'àutre anliure',

# Random page
'randompage' => 'Na pàgina qualsëssìa',
'randompage-nopages' => 'A-i é pa gnun-a pàgina ant {{PLURAL:$2|lë spassi nominal|jë spassi nominaj}}: lë spassi nominal "$1"',

# Random page in category
'randomincategory' => "Pàgina a l'ancàpit ant la categorìa",
'randomincategory-invalidcategory' => "«$1» a l'é pa un nòm ëd categorìa bon.",
'randomincategory-nopages' => 'A-i é gnun-e pàgine ant la categorìa [[:Category:$1|$1]].',
'randomincategory-selectcategory' => "Pijé na pàgina a l'ancàpit da 'nt la categorìa: $1 $2.",
'randomincategory-selectcategory-submit' => 'Andé',

# Random redirect
'randomredirect' => 'Na ridiression qualsëssìa',
'randomredirect-nopages' => 'A-i é pa gnun-a ridiression ant lë spassi nominal "$1".',

# Statistics
'statistics' => 'Statìstiche',
'statistics-header-pages' => 'Statìstiche dla pàgina',
'statistics-header-edits' => 'Statìstiche dle modìfiche',
'statistics-header-views' => 'Statìstiche dle visualisassion',
'statistics-header-users' => 'Statìstiche ëd {{SITENAME}}',
'statistics-header-hooks' => 'Àutre statìstiche',
'statistics-articles' => 'Pàgine ëd contnù',
'statistics-pages' => 'Pàgine',
'statistics-pages-desc' => 'Tute le pàgine dla wiki, comprèise le pàgine ëd discussion, le ridiression e via fòrt.',
'statistics-files' => 'Archivi carià',
'statistics-edits' => "Pàgine modificà da quand ël {{SITENAME}} a l'é stàit tirà su",
'statistics-edits-average' => 'Media dle modìfiche për pàgina',
'statistics-views-total' => 'Total dle visualisassion',
'statistics-views-total-desc' => 'Le visualisassion ëd le pàgine pa esistente e ëd le pàgine speciaj a son nen comprèise',
'statistics-views-peredit' => 'Visualisassion për modìfica',
'statistics-users' => '[[Special:ListUsers|Utent]] argistrà',
'statistics-users-active' => 'Utent ativ',
'statistics-users-active-desc' => "Utent che a l'han fàit n'assion ant {{PLURAL:$1|l'ùltim di|j'ùltim $1 di}}",
'statistics-mostpopular' => "Pàgine ch'a 'ncontro dë pì",

'pageswithprop' => 'Pàgine con na propietà ëd pàgina',
'pageswithprop-legend' => 'Pàgine con na propietà ëd pàgina',
'pageswithprop-text' => "Costa pàgina a lista le pàgine ch'a deuvro na propietà 'd pàgina particolar.",
'pageswithprop-prop' => 'Nòm ëd la propietà:',
'pageswithprop-submit' => 'Andé',
'pageswithprop-prophidden-long' => 'valor ëd propietà ëd test longh stërmà ($1)',
'pageswithprop-prophidden-binary' => 'valor ëd propietà binaria stërmà ($1)',

'doubleredirects' => 'Ridiression dobie',
'doubleredirectstext' => "Sta pàgina-sì a a lista dle pàgine ch'a armando a d'àutre pàgine ëd ridiression.
Vira riga a l'ha andrinta j'anliure a la prima e a la sconda ridiression, ant sël pat ëd la prima riga ëd test dla seconda ridiression, che për sòlit a l'ha andrinta l'artìcol ëd destinassion vèir, col andoa che a dovrìa ëmné ëdcò la prima ridiression.
Le ridiression <del>sganfà</del> a son stàite arzolvùe.",
'double-redirect-fixed-move' => "[[$1]] a l'é stàit spostà.
Adess a l'é na ridiression a [[$2]].",
'double-redirect-fixed-maintenance' => 'Rangé le ridiression dobie da [[$1]] a [[$2]].',
'double-redirect-fixer' => 'Coretor ëd ridiression',

'brokenredirects' => 'Ridiression nen giuste',
'brokenredirectstext' => "Coste ridiression-sì a men-o a d'artìcoj ch'a-i son pa:",
'brokenredirects-edit' => 'modifiché',
'brokenredirects-delete' => 'scancelé',

'withoutinterwiki' => "Pàgine ch'a l'han gnun-a anliura interwiki",
'withoutinterwiki-summary' => "Le pàgine ambelessì-sota a l'han gnun-a anliura a dj'àotre lenghe:",
'withoutinterwiki-legend' => 'Prefiss',
'withoutinterwiki-submit' => 'Smon-e',

'fewestrevisions' => 'Artìcoj con manch ëd modìfiche',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|categorìa|categorìe}}',
'ninterwikis' => '$1 {{PLURAL:$1|antërwiki|antërwiki}}',
'nlinks' => '$1 {{PLURAL:$1|anliura|anliure}}',
'nmembers' => '$1 {{PLURAL:$1|element|element}}',
'nrevisions' => '{{PLURAL:$1|na revision|$1 revision}}',
'nviews' => '{{PLURAL:$1|na consultassion|$1 consultassion}}',
'nimagelinks' => 'Dovrà dzora a $1 {{PLURAL:$1|pàgina|pàgine}}',
'ntransclusions' => 'dovrà dzora a $1 {{PLURAL:$1|pàgina|pàgine}}',
'specialpage-empty' => 'Pàgina veujda.',
'lonelypages' => 'Pàgine daspërlor',
'lonelypagestext' => "Le pàgine ambelessì-sota a l'han gnun-e anliure che a-j men-o ansima nì a son anserìe an d'àotre pàgine ëd {{SITENAME}}.",
'uncategorizedpages' => 'Pàgine che a son nen assignà a na categorìa',
'uncategorizedcategories' => 'Categorìe che a son pa assignà a na categorìa',
'uncategorizedimages' => 'Archivi multimojen nen categorisà',
'uncategorizedtemplates' => "Stamp sensa pa 'd categorìe",
'unusedcategories' => 'Categorìe nen dovrà',
'unusedimages' => 'Figure nen dovrà',
'popularpages' => 'Pàgine pì s-ciairà',
'wantedcategories' => 'Categorìe dont a fa da manca',
'wantedpages' => 'Artìcoj pì ciamà',
'wantedpages-badtitle' => "Tìtol nen vàlid ant l'ansema dj'arzultà: $1",
'wantedfiles' => 'Archivi pì ciamà',
'wantedfiletext-cat' => "J'archivi ch'a ven-o a son dovrà ma a esisto pa. J'archivi dai sò depòsit estern a peulo esse listà sensa consideré l'esistensa. Chèich fàuss positiv a saran <del>sganfà</del>. An pi, le pàgine ch'a conten-o dj'archivi ch'a esisto pa a son listà an [[:$1]].",
'wantedfiletext-nocat' => "J'archivi sì-dapress a son dovrà ma a esisto pa. J'archivi da depòsit estern a peulo esse listà sensa considerene l'esistensa. Tùit costi fàuss positiv a saran <del>ësganfà</del>.",
'wantedtemplates' => 'Stamp ciamà',
'mostlinked' => "Pàgine che a l'han pì d'anliure che a-i men-o la gent ansima",
'mostlinkedcategories' => "Categorìe che a l'han pì d'anliure che a-i men-o la gent ansima",
'mostlinkedtemplates' => 'Stamp pì dovrà',
'mostcategories' => 'Artìcoj che a son marcà an pì categorìe',
'mostimages' => 'Figure pì dovrà',
'mostinterwikis' => "Pàgine con pi 'd liure antërwiki",
'mostrevisions' => 'Artìcoj pì modificà',
'prefixindex' => "Tute le pàgine ch'a ancamin-o con",
'prefixindex-namespace' => 'Tute le pàgine con prefiss ($1 spassi nominal)',
'prefixindex-strip' => 'Gavé ël prefiss da la lista',
'shortpages' => 'Pàgine curte',
'longpages' => 'Pàgine longhe',
'deadendpages' => 'Pàgine che a men-o da gnun-a part',
'deadendpagestext' => "Le pàgine ambelessì-sota a l'han pa d'anliure anvers a j'àutre pàgine ëd {{SITENAME}}.",
'protectedpages' => 'Pàgine sota protession',
'protectedpages-indef' => 'Mach protession anfinìe',
'protectedpages-cascade' => 'Mach protession a cascà',
'protectedpagestext' => "Ambelessì-sota a-i é na lista ëd pàgine ch'a son protegiùe përchè as peulo nen modifichesse ò pura tramudesse",
'protectedpagesempty' => 'Për adess a-i é pa gnun-a pàgina protegiùa',
'protectedtitles' => 'Tìtoj protegiù',
'protectedtitlestext' => 'Ij tìtoj ambelessì-sota as peulo pa creesse',
'protectedtitlesempty' => "A-i é pa gnun tìtol protegiù ch'a-i intra coi criteri ch'a l'ha butà.",
'listusers' => "Lista dj'utent",
'listusers-editsonly' => "Mostré mach j'utent ch'a l'han fàit dle modìfiche",
'listusers-creationsort' => 'Ordiné për data ëd creassion',
'listusers-desc' => 'Ordiné an órdin calant',
'usereditcount' => '$1 {{PLURAL:$1|modìfica|modìfiche}}',
'usercreated' => '{{GENDER:$3|Creà}}  ël $1 a $2',
'newpages' => 'Pàgine neuve',
'newpages-username' => 'Stranòm:',
'ancientpages' => 'Le pàgine pì veje',
'move' => 'Tramudé',
'movethispage' => 'Tramudé costa pàgina',
'unusedimagestext' => "J'archivi sì-sota a esisto ma a son pa andrinta a gnun-e pàgine.
Për piasì, ch'a nòta che d'àutri sit an sl'aragnà a peulo coleghesse a n'archivi con n'anliura direta, e parèj a peulo ancó esse listà ambelessì, bele s'a son dovrà an coj sit.",
'unusedcategoriestext' => "Le pàgine ëd coste categorìe-sì a son fasse ma peuj a l'han andrinta nì d'artìcoj, nì ëd sotacategorìe.",
'notargettitle' => 'A manco ij dat',
'notargettext' => "A l'ha pa dit a che pàgina ò Utent apliché l'operassion ciamà.",
'nopagetitle' => 'La pàgina ëd destinassion a-i é pa',
'nopagetext' => "La pàgina che a l'ha spessificà a esist pa.",
'pager-newer-n' => '{{PLURAL:$1|1|$1}} pì neuv',
'pager-older-n' => '{{PLURAL:$1|1|$1}} pì vej',
'suppress' => 'Supervisor',
'querypage-disabled' => "Sta pàgina special a l'é disabilità për dle rason ëd prestassion.",

# Book sources
'booksources' => 'Andoa trové dij lìber',
'booksources-search-legend' => "Sërché antra ij lìber d'arferiment",
'booksources-go' => 'Andé',
'booksources-text' => "Ambelessì sota a-i é na lista d'àotri sit che a vendo dij lìber neuv e dë sconda man, e che a peulo ëdcò smon-e dj'anformassion rësgoard ai test che a l'é antramentr che a sërca:",
'booksources-invalid-isbn' => "L'ISBN dàit a smija che a sia pa vàlid; ch'a contròla s'a-i é n'eror an copiand da la sorgiss original.",

# Special:Log
'specialloguserlabel' => 'Esecutor:',
'speciallogtitlelabel' => 'Obietiv (tìtol o utent):',
'log' => 'Registr',
'all-logs-page' => 'Tùit ij registr pùblich',
'alllogstext' => 'Visualisassion combinà ëd tùit ij registr ëd {{SITENAME}}.
A peul arstrenze la visualisassion an selessionand la sòrt ëd registr, lë stranòm utent (sensìbil a majùscol/minùscol), e la pàgina anteressà (sensìbil a majùscol/minùscol).',
'logempty' => 'Pa gnun element parèj che a sia trovasse ant ij registr.',
'log-title-wildcard' => "Sërché ant ij tìtoj ch'as anandio për",
'showhideselectedlogentries' => 'Smon-e/stërmé le vos ëd registr selessionà',

# Special:AllPages
'allpages' => 'Tute le pàgine',
'alphaindexline' => '$1 a $2',
'nextpage' => 'Pàgina che a-i ven ($1)',
'prevpage' => 'Pàgina anans ($1)',
'allpagesfrom' => 'Smon-e le pàgine ën partend da:',
'allpagesto' => 'Smon-e le pàgine fin-a a:',
'allarticles' => "Tùit j'artìcoj",
'allinnamespace' => 'Tute le pàgine (spassi nominal $1)',
'allnotinnamespace' => 'Tute le pàgine (che a son nen ant lë spassi nominal $1)',
'allpagesprev' => 'Cole prima',
'allpagesnext' => 'Cole che a ven-o',
'allpagessubmit' => 'Andé',
'allpagesprefix' => "Smon-e le pàgine che a l'han ël prefiss:",
'allpagesbadtitle' => "Ël tìtol che a l'ha daje a la pàgina a va nen bin, ò pura a l'ha andrinta un prefiss inter-lenga ò inter-wiki. A peul esse ëdcò che a l'abia andrinta dij caràter che as peulo nen dovresse ant ij tìtoj.",
'allpages-bad-ns' => '{{SITENAME}} a l\'ha pa gnun ëspassi nominal "$1".',
'allpages-hide-redirects' => 'Stërma le ridiression',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => "A l'é ancamin ch'a vëd na version memorisà ëd costa pàgina, che a peul esse veja fin a $1.",
'cachedspecial-viewing-cached-ts' => "A l'é ancamin ch'a s-ciàira na version memorisà ëd costa pagina, che a peul esse nen completament agiornà.",
'cachedspecial-refresh-now' => "Varda l'ùltima.",

# Special:Categories
'categories' => 'Categorìe',
'categoriespagetext' => "{{PLURAL:$1|Costa categorìa a conten|Coste categorìe a conten-o}} dle pàgine ò dj'archivi.
[[Special:UnusedCategories|Le categorìe nen dovrà]] a son pa mostrà ambelessì.
Ch'a bèica ëdcò [[Special:WantedCategories|le categorìe domandà]].",
'categoriesfrom' => 'Smon-e le categorìe an partend da:',
'special-categories-sort-count' => 'ordiné për nùmer',
'special-categories-sort-abc' => 'rangé la lista an órdin alfabétich',

# Special:DeletedContributions
'deletedcontributions' => 'Modìfiche faite da utent scancelà',
'deletedcontributions-title' => 'Modìfiche faite da utent scancelà',
'sp-deletedcontributions-contribs' => 'contribussion',

# Special:LinkSearch
'linksearch' => 'Arserca ëd colegament estern',
'linksearch-pat' => "Schema d'arsërca:",
'linksearch-ns' => 'Spassi nominal:',
'linksearch-ok' => 'Sërché',
'linksearch-text' => 'As peulo dovresse dij ciapatut com "*.wikipedia.org".
A-i é dabzògn almanch d\'un domini a livel pi àut, për esempi "*.org".<br />
{{PLURAL:$2|Protocòl|Protocòj}} ch\'as peulo dovresse: <code>$1</code> (predefinì http:// se gnun protocòl a l\'é specificà).',
'linksearch-line' => "$1 a l'ha n'anliura ch'a-j riva dzora da $2",
'linksearch-error' => 'Ij ciapatut as peulo butesse mach an prinsipi dël nòm dël sërvent.',

# Special:ListUsers
'listusersfrom' => "Smon-me j'utent a parte da:",
'listusers-submit' => 'Smon-e',
'listusers-noresult' => 'Gnun utent përparèj.',
'listusers-blocked' => '(blocà)',

# Special:ActiveUsers
'activeusers' => "Lista dj'utent ativ",
'activeusers-intro' => "Costa a l'é na lista d'utent ch'a l'han avù n'atività qualsëssìa ant j'ùltim $1 {{PLURAL:$1|di|di}}.",
'activeusers-count' => "$1 {{PLURAL:$1|assion}} ant {{PLURAL:$3|l'ùltim di|j'ùltim $3 di}}",
'activeusers-from' => "Smon-me j'utent a parte da:",
'activeusers-hidebots' => 'Stërmé ij trigomiro',
'activeusers-hidesysops' => "Stërmé j'aministrator",
'activeusers-noresult' => 'Pa gnun utent trovà.',

# Special:ListGroupRights
'listgrouprights' => "Drit dël grup d'utent",
'listgrouprights-summary' => "Ambelessì a-i é na lista dle partìe d'utent definìe ansima a costa wiki, con ij sò drit d'acess associà.
A peulo ess-ie d'[[{{MediaWiki:Listgrouprights-helppage}}|anformassion adissionaj]] ansima a dij drit individuaj.",
'listgrouprights-key' => 'Legenda:
* <span class="listgrouprights-granted">Drit assignà</span>
* <span class="listgrouprights-revoked">Drit revocà</span>',
'listgrouprights-group' => 'Partìa',
'listgrouprights-rights' => 'Drit',
'listgrouprights-helppage' => 'Help:Drit ëd le partìe',
'listgrouprights-members' => '(Lista dij mèmber)',
'listgrouprights-addgroup' => 'Gionté a {{PLURAL:$2|la partìa|le partìe}}: $1',
'listgrouprights-removegroup' => 'Gavé {{PLURAL:$2|la partìa|le partìe}}: $1',
'listgrouprights-addgroup-all' => 'Gionté tute le partìe',
'listgrouprights-removegroup-all' => 'Gavé tute le partìe',
'listgrouprights-addgroup-self' => 'Gionté {{PLURAL:$2|la partìa|le partìe}} al sò cont: $1',
'listgrouprights-removegroup-self' => 'Gavé {{PLURAL:$2|la partìa|le partìe}} da sò cont: $1',
'listgrouprights-addgroup-self-all' => 'Gionté tute le partìe a sò cont',
'listgrouprights-removegroup-self-all' => 'Gavé tute le partìe da sò cont',

# Email user
'mailnologin' => "A-i é pa l'adrëssa për mandé ël mëssagi",
'mailnologintext' => "A dev [[Special:UserLogin|rintré ant ël sistema]]
e avèj registrà n'adrëssa ëd pòsta eletrònica vàlida ant ij [[Special:Preferences|sò gust]] për podèj mandé dij mëssagi ëd pòsta eletrònica a j'àutri Utent.",
'emailuser' => "Mandeje un mëssagi eletrònich a st'utent-sì",
'emailuser-title-target' => 'Mandé un mëssagi ëd pòsta eletrònica a cost {{GENDER:$1|utent}}',
'emailuser-title-notarget' => "Mandeje un mëssagi ëd pòsta eletrònica a st'utent-sì",
'emailpage' => "Mandeje un mëssagi ëd pòsta eletrònica a st'utent-sì",
'emailpagetext' => "A peul dovré ël formolari sì-sota për mandé un mëssagi ëd pòsta eletrònica a st'{{GENDER:$1|utent}}-sì.
L'adrëssa ëd pòsta eletrònica ch'a l'ha butà ant ij [[Special:Preferences|sò gust]] a sarà butà ant l'adrëssa «Da» ëd sò mëssagi, parèj chi ch'a l'arsèiv a podrà rësponde diretament a chiel.",
'usermailererror' => "L'oget che a goèrna la pòsta eletrònica a l'ha dàit eror:",
'defemailsubject' => 'Mëssagi da l\'utent "$1"',
'usermaildisabled' => "Pòsta eletrònica dl'utent disabilità",
'usermaildisabledtext' => "A peul pa mandé ëd mëssagi ëd pòsta eletrònica a d'àutri utent ansima a costa wiki",
'noemailtitle' => 'Gnun-a adrëssa ëd pòsta eletrònica',
'noemailtext' => "Cost utent-sì a l'ha pa spessificà n'adrëssa ëd pòsta eletrònica vàlida.",
'nowikiemailtitle' => "Gnun mëssagi ëd pòsta eletrònica a l'é autorisà",
'nowikiemailtext' => "Cost utent a l'ha sërnù ëd nen arsèive dij mëssagi ëd pòsta eletrònica da j'àutri utent.",
'emailnotarget' => 'Stranòm dël destinatari pa esistent o pa bon.',
'emailtarget' => "Ch'a anserissa lë stranòm dël destinatari",
'emailusername' => 'Stranòm:',
'emailusernamesubmit' => 'Spedì',
'email-legend' => "Mandé un mëssagi ëd pòsta eletrònica a n'àutr utent ëd {{SITENAME}}",
'emailfrom' => 'Da:',
'emailto' => 'A:',
'emailsubject' => 'Oget:',
'emailmessage' => 'Mëssagi:',
'emailsend' => 'Mandé',
'emailccme' => 'Mandemne na còpia ëdcò a mia adrëssa.',
'emailccsubject' => 'Còpia dël mëssagi mandà a $1: $2',
'emailsent' => 'Mëssagi eletrònich mandà',
'emailsenttext' => "Sò mëssagi eletrònich a l'é stàit mandà",
'emailuserfooter' => "Ës mëssagi eletrònich a l'é stàit mandà da $1 a $2 con la fonsion «Mandé un mëssagi eletrònich a l'utent» ëd {{SITENAME}}.",

# User Messenger
'usermessage-summary' => "A l'ha lassà un mëssagi ëd sistema.",
'usermessage-editor' => 'Mëssagerìa ëd sistema',

# Watchlist
'watchlist' => 'Ròba che as ten sot-euj',
'mywatchlist' => 'Ròba che as ten sot euj',
'watchlistfor2' => 'Për $1 $2',
'nowatchlist' => "A l'ha ancó pa marcà dj'artìcoj coma ròba da tnì sot-euj.",
'watchlistanontext' => "Për piasì, $1 për ës-ciairé ò pura modifiché j'element ëd soa lista dla ròba che as ten sot-euj.",
'watchnologin' => "A l'é ancó nen rintrà ant ël sistema",
'watchnologintext' => "A l'ha da manca prima ëd tut ëd [[Special:UserLogin|rintré ant ël sistema]]
për podèj modifiché soa lista dla ròba dë tnì sot-euj.",
'addwatch' => "Gionté a la lista ëd lòn ch'as ten sot-euj",
'addedwatchtext' => "La pàgina «[[:$1]]» a l'é staita giontà a soa [[Special:Watchlist|lista dla ròba da tnì sot-euj]].
Le modìfiche che a-i saran ant costa pàgina-sì e ant soa pàgina ëd discussion a saran listà ambelessì.",
'removewatch' => "Gavé da la lista ëd lòn ch'as ten sot-euj",
'removedwatchtext' => "La pàgina «[[:$1]]» a l'è staita gavà via da [[Special:Watchlist|soa lista dla ròba da tnì sot-euj]].",
'watch' => 'ten-e sot-euj',
'watchthispage' => 'Ten-e sot-euj cost artìcol-sì',
'unwatch' => 'Chité-lì ëd ten-e sossì sot-euj',
'unwatchthispage' => 'Chité-lì ëd ten-e sossì sot-euj',
'notanarticle' => "Sòn a l'é pa n'artìcol",
'notvisiblerev' => "La revision a l'é stàita scancelà",
'watchlist-details' => "A l'é dëmentrè ch'as ten sot-euj {{PLURAL:$1|$1 pàgina|$1 pàgine}}, nen contand cole ëd discussion.",
'wlheader-enotif' => "La notìfica për pòsta eletrònica a l'é abilità.",
'wlheader-showupdated' => "Le pàgine che a son ëstàite modificà da quand che a l'é passaje ansima l'ùltima vira a resto marcà an '''grassèt'''",
'watchmethod-recent' => "contròl a j'ùltime modìfiche fàite a le pàgine che as ten sot-euj",
'watchmethod-list' => 'contròl ëd le pàgine che as ten sot-euj për vëdde se a-i sio staje dle modìfiche recente',
'watchlistcontains' => "Soa lista dla ròba ch'as ten sot-euj a l'ha andrinta {{PLURAL:$1|na pàgina|$1 pàgine}}.",
'iteminvalidname' => "Problema con l'element '$1', nòm nen vàlid...",
'wlnote' => "Ambelessì sota a-i {{PLURAL:$1|é l'ùltima modìfica|son j'ùltime '''$1''' modìfiche}} ant {{PLURAL:$2|l'ùltima ora|j'ùltime '''$2''' ore}}, a parte da $3, $4.",
'wlshowlast' => "Smon-e j'ùltime $1 ore $2 dì $3",
'watchlist-options' => "Opsion ëd la lista dla ròba ch'as ten sot-euj",

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Sot-euj...',
'unwatching' => "Ën gavand da lòn ch'as ten sot-euj...",
'watcherrortext' => "A l'é capitaje n'eror durant la modìfica ëd j'ampostassion ëd lòn ch'as ten sot-euj për «$1».",

'enotif_mailer' => '{{SITENAME}} - Servissi ëd Notìfica Postal',
'enotif_reset' => 'Marché tute le pàgine tanme visità',
'enotif_impersonal_salutation' => 'utent ëd {{SITENAME}}',
'enotif_subject_deleted' => "La pàgina $1 ëd {{SITENAME}} a l'é stàita scancelà da {{gender:$2|$2}}",
'enotif_subject_created' => "La pàgina $1 ëd {{SITENAME}} a l'é stàita creà da {{gender:$2|$2}}",
'enotif_subject_moved' => "La pàgina $1 ëd {{SITENAME}} a l'é stàita tramudà da {{gender:$2|$2}}",
'enotif_subject_restored' => "La pàgina $1 ëd {{SITENAME}} a l'é stàita ripristinà da {{gender:$2|$2}}",
'enotif_subject_changed' => "La pàgina $1 ëd {{SITENAME}} a l'é stàita modificà da {{gender:$2|$2}}",
'enotif_body_intro_deleted' => 'La pàgina $1 ëd {{SITENAME}} a l\'é stàita scancelà da {{gender:$2|$2}} ël $PAGEEDITDATE, vëdde $3.',
'enotif_body_intro_created' => 'La pàgina $1 ëd {{SITENAME}} a l\'é stàita creà da {{gender:$2|$2}} ël $PAGEEDITDATE, vëdde $3 për la revision corenta.',
'enotif_body_intro_moved' => 'La pàgina $1 ëd {{SITENAME}} a l\'é stàita tramudà da {{gender:$2|$2}} ël $PAGEEDITDATE, vëdde $3 për la revision corenta.',
'enotif_body_intro_restored' => 'La pàgina $1 ëd {{SITENAME}} a l\'é stàita ripristinà da {{gender:$2|$2}} ël $PAGEEDITDATE, vëdde $3 për la revision corenta.',
'enotif_body_intro_changed' => 'La pàgina $1 ëd {{SITENAME}} a l\'é stàita modificà da {{gender:$2|$2}} ël $PAGEEDITDATE, vëdde $3 për la revision corenta.',
'enotif_lastvisited' => "Che as varda $1 për ës-ciaré tute le modìfiche da 'nt l'ùltima vira che a l'é passà.",
'enotif_lastdiff' => "Ch'a varda $1 për visioné sta modìfica.",
'enotif_anon_editor' => 'utent anònim $1',
'enotif_body' => 'Car $WATCHINGUSERNAME,

$PAGEINTRO $NEWPAGE

Resumé dl\'editor: $PAGESUMMARY $PAGEMINOREDIT

Për contaté l\'editor:
pòsta eletrònica: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

A-i sarà pì gnun-a notìfica ëd modìfiche se chiel a vìsita nen costa pàgina. Che as visa che a peul cangeje la configurassion dle notìfiche a le pàgine che as ten sot-euj ansima a soa lista dla ròba ch\'as ten sot-euj.

Comunicassion dël sistema ëd notìfica da {{SITENAME}}

--
Për cangé la configurassion ëd soe notìfiche an pòsta eletrònica, che a vada ansima a
{{canonicalurl:{{#special:Preferences}}}}

Për cangé la configurassion ëd lòn che as ten sot-euj che a vada ansima a
{{canonicalurl:{{#special:EditWatchlist}}}}

Për scancelé la pàgina da lòn ch\'a ten sot-euj, ch\'a vìsita
$UNWATCHURL

Comunicassion ëd servissi e pì d\'agiut:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'creà',
'changed' => 'modificà',

# Delete
'deletepage' => 'Scancelé la pàgina',
'confirm' => 'Confermé',
'excontent' => "Ël contnù a l'era: '$1'",
'excontentauthor' => "ël contnù a l'era: «$1» (e l'ùnich contributor a l'era stàit «[[Special:Contributions/$2|$2]]»)",
'exbeforeblank' => "anans d'esse dësvujdà ël contnù a l'era: «$1»",
'exblank' => "La pàgina a l'era veujda",
'delete-confirm' => 'Scancelé «$1»',
'delete-legend' => 'Scancelé',
'historywarning' => "'''Avis:''' La pàgina che a l'é antramentr che a scancela a l'ha na stòria con pi o men $1 {{PLURAL:$1|revision|revision}}:",
'confirmdeletetext' => "A sta për scancelé d'autut da 'nt la base dat na pàgina ò pura na figura, ansema a tuta soa cronologìa.<p>
Për piasì, che an conferma che sòn a l'é da bon sò but, che a as rend cont ëd le conseguense ëd lòn che a fa, e che sòn a resta an pien an régola con lòn che a l'é stabilì ant la [[{{MediaWiki:Policy-url}}]].",
'actioncomplete' => 'Travaj fait e finì',
'actionfailed' => 'Assion falìa',
'deletedtext' => "La pàgina «$1» a l'é stàita scancelà.
Che a varda $2 për na lista dle pàgine scancelà ant j'ùltim temp.",
'dellogpage' => 'Registr djë scancelament',
'dellogpagetext' => "Ambelessì-sota a-i é na lista dle pàgine scancelà ant j'ùltim temp.",
'deletionlog' => 'Registr djë scancelament',
'reverted' => 'Version prima butà torna sù',
'deletecomment' => 'Rason:',
'deleteotherreason' => 'Rason àutra/adissional:',
'deletereasonotherlist' => 'Àutra rason',
'deletereason-dropdown' => "*Rason sòlite ch'a së scancela la ròba
** Rumenta
** Vandalism
** Violassion dij drit d'autor
** A lo ciama l'àutor
** Ridiression cioca",
'delete-edit-reasonlist' => 'Modifiché la rason dlë scancelament',
'delete-toobig' => "Sta pàgina-sì a l'ha na stòria motobin longa, bele pì che $1 {{PLURAL:$1|revision|revision}}.
Lë scancelassion ëd pàgine parèj a l'é stàita limità për evité ch'as fasa darmagi për eror a {{SITENAME}}.",
'delete-warning-toobig' => "Sta pàgina-sì a l'ha na stòria motobin longa, bele pì che $1 {{PLURAL:$1|revision|revision}}.
A scancelela as peul fesse darmagi a j'operassion dla base ëd dat ëd {{SITENAME}};
ch'a daga da ment a lòn ch'a fa.",

# Rollback
'rollback' => 'Gavé via le modìfiche',
'rollback_short' => 'Ripristiné',
'rollbacklink' => "ripristiné j'archivi",
'rollbacklinkcount' => 'tiré andré $1 {{PLURAL:$1|modìfica|modìfiche}}',
'rollbacklinkcount-morethan' => 'tiré andré pi che $1 {{PLURAL:$1|modìfica|modìfiche}}',
'rollbackfailed' => "A l'é pa podusse ripristiné",
'cantrollback' => "As peul pa tornesse a na version pì veja: l'ùltima modìfica a l'ha fala l'ùnich utent che a l'abia travajà a cost artìcol-sì.",
'alreadyrolled' => "As peulo pa anulé j'ultime modìfiche ëd [[:$1]] fàite da [[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
cheidun d'àutr a l'ha già modificà ò pura anulà le modìfiche a sta pàgina-sì.

L'ùltima modìfica a la pàgina a l'é stàita fàita da [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment' => "Ël coment dla modìfica a l'era: \"''\$1''\".",
'revertpage' => "Gavà via le modìfiche ëd [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]); ël contnù a l'é stàit tirà andarè a l'ùltima version dl'utent [[User:$1|$1]]",
'revertpage-nouser' => "Révoca dle modìfiche da part ëd n'utent ëstërmà a l'ùltima version ëd {{GENDER:$1|[[User:$1|$1]]}}",
'rollback-success' => "Modìfiche anulà da $1; tirà andré a l'ùltima version da $2.",

# Edit tokens
'sessionfailure-title' => 'Eror ëd session',
'sessionfailure' => "A-i son ëstaje dle gran-e con la session che a identìfica sò acess; ël sistema a l'ha nen eseguì l'ordin che a l'ha daje për precaussion. Che a torna andaré a la pàgina prima con ël boton «andaré» ëd sò programa ëd navigassion, peuj che as carìa n'àutra vira costa pàgina-sì e che a preuva torna a fé lòn che vorìa fé.",

# Protect
'protectlogpage' => 'Registr dle protession',
'protectlogtext' => "Ambelessì sota a-i é na lista ëd cambiament a le protession ëd la pàgina.
Ch'a varda la [[Special:ProtectedPages|Lista dle pàgine protegiùe]] për la lista ëd le protession ëd la pàgina ch'a son an fonsion adess.",
'protectedarticle' => '"[[$1]]" a l\'é protet',
'modifiedarticleprotection' => 'A l\'é cambia-ie ël livel ëd protession për "[[$1]]"',
'unprotectedarticle' => "a l'ha gavà la protession da «[[$1]]»",
'movedarticleprotection' => "a l'ha cambià j'ampostassion ëd protession da «[[$2]]» a «[[$1]]»",
'protect-title' => 'I soma antramentr che i protegioma «$1»',
'protect-title-notallowed' => 'Vëdde ël livel ëd protession ëd «$1»',
'prot_1movedto2' => '[[$1]] tramudà a [[$2]]',
'protect-badnamespace-title' => 'Spassi nominal pa protegìbil',
'protect-badnamespace-text' => 'Le pàgine an cost ëspassi nominal-sì a peulo pa esse protegiùe.',
'protect-norestrictiontypes-text' => 'Sta pàgina a peul pa esse protegiùa përchè a-i son gnun-e sòrt ëd restrission disponìbij.',
'protect-norestrictiontypes-title' => 'Pàgina nen protegìbil',
'protect-legend' => 'Che an conferma la protession',
'protectcomment' => 'Rason:',
'protectexpiry' => 'Scadensa:',
'protect_expiry_invalid' => 'Scadensa pa bon-a.',
'protect_expiry_old' => 'Scadensa già passà.',
'protect-unchain-permissions' => "Sbloché d'àutre opsion ëd protession",
'protect-text' => "Ambelessì a peul vardé e cangé ël livel ëd protession dla pàgina '''$1'''.",
'protect-locked-blocked' => "Un a peul pa modifiché ij livel ëd protession antramentr ch'a l'é blocà chiel. Ambelessì a-i son le regolassion corente për la pàgina '''$1''':",
'protect-locked-dblock' => "Ij livej ëd protession as peulo nen cambiesse antramentr che la base dat a l'é blocà.
Ambelessì a-i son le regolassion corente për la pàgina '''$1''':",
'protect-locked-access' => "Sò cont a l'ha pa la qualìfica për podèj cambié ij livej ëd protession.
Ambelessì a-i son j'ampostassion atuaj për la pàgina '''$1''':",
'protect-cascadeon' => "Sta pàgina për adess a l'é blocà përchè a-i intra ant {{PLURAL:$1|la pàgina sì-sota, ch'a l'ha|le-pàgine sì sota, ch'a l'han}} na protession a sàut anvisca. A peul cambie-je sò livel ëd protession a sta pàgina-sì ma lòn a tochërà pa la protession a sàut.",
'protect-default' => "Autorisé tùit j'utent",
'protect-fallback' => "Përmëtt mach a j'utent con ël përmess «$1»",
'protect-level-autoconfirmed' => "Përmëtte mach j'utent autoconfirmà",
'protect-level-sysop' => "Përmëtt mach a j'aministrator",
'protect-summary-cascade' => 'a sàut',
'protect-expiring' => 'scadensa: $1 (UTC)',
'protect-expiring-local' => 'a finiss ai $1',
'protect-expiry-indefinite' => 'për sempe',
'protect-cascade' => "Protege le pàgine ch'a fan part ëd costa (protession a sàut)",
'protect-cantedit' => "A peul pa cambieje livel ëd protession a sta pàgina-sì, për via ch'a l'ha nen ël përmess dë modifichela.",
'protect-othertime' => "N'àutra durà:",
'protect-othertime-op' => "N'àutra durà",
'protect-existing-expiry' => 'Scadensa esistenta:$3, $2',
'protect-otherreason' => 'Rason àutra/adissional:',
'protect-otherreason-op' => 'Àutra rason',
'protect-dropdown' => '*Rason comun-e ëd protession
** Tròp vandalism
** Tròpa rumenta
** Guère ëd modìfiche danose
** Pàgina con motobin ëd tràfich',
'protect-edit-reasonlist' => 'Rason ëd la protession da le modìfiche',
'protect-expiry-options' => '1 ora:1 hour,1 di:1 day,1 sman-a:1 week,2 sman-e:2 weeks,1 meis:1 month,3 meis:3 months,6 meis:6 months,1 ann:1 year,për sempe:infinite',
'restriction-type' => 'Përmess',
'restriction-level' => 'Livel ëd restrission',
'minimum-size' => 'Amzura mìnima',
'maximum-size' => 'Amzura màssima:',
'pagesize' => '(byte)',

# Restrictions (nouns)
'restriction-edit' => 'Modifiché',
'restriction-move' => 'Tramudé',
'restriction-create' => 'Creé',
'restriction-upload' => 'Carié',

# Restriction levels
'restriction-level-sysop' => 'protegiùa',
'restriction-level-autoconfirmed' => 'mesa-protegiùa',
'restriction-level-all' => 'tuti ij livej',

# Undelete
'undelete' => 'Vëdde le pàgine scancelà',
'undeletepage' => 'Vëdde e pijé andaré le pàgine scancelà',
'undeletepagetitle' => "'''Lòn ch'a-i é ambelessì a son tute revision scancelà ëd [[:$1]]'''.",
'viewdeletedpage' => 'Smon-e le pàgine scancelà',
'undeletepagetext' => "{{PLURAL:$1|La pàgina ambelessì-sota a l'é stàita scancelà, ma a resta|$1 Le pàgine ambelessì-sota a son stàite scancelà, ma a resto}} ancó memorisà ant l'archivi a as peulo pijesse andaré.
L'archivi a ven polidà passaje un pòch ëd temp.",
'undelete-fieldset-title' => 'Pijé andré le revision',
'undeleteextrahelp' => "Për ripristiné l'antrega stòria dla pàgina, che a lassa tute le casele nen selessionà e che a jë sgnaca ansima a '''''{{int:undeletebtn}}'''''.
Për ripristiné mach chèich-còs, che a selession-a le casele corispondente a le revision da ripristiné, e che a sgnaca ansima a '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '{{PLURAL:$1|Na|$1}} revision memorisà',
'undeletehistory' => "Se a pija andré st'articol-sì, ëdcò tute soe revision a saran pijàite andaré ansema a chiel ant soa cronologìa.
Se a fussa mai stàita creà na pàgina neuva con l'istess nòm dòp che la veja a l'era stàita scancelà, le revision a saran butà ant la cronologìa ëd prima.",
'undeleterevdel' => "Ël dëscancelament as farà pa s'a-i intrèissa në scancelament parsial dla version corenta dla pàgina. Quand a-i riva lolì, un a dev gave-ie la crosëtta da 'nt la pì neuva dle version scancelà, ò pura gavela da stërmà.",
'undeletehistorynoadmin' => "Sta pàgina-sì a l'é stàita scancelà.
Ël motiv che a l'é scancelasse as peul savejsse ën vardand ël resumé ambelessì-sota, andoa che a së s-ciàira ëdcò chi che a
l'avìa travajaje ansima anans che a la scancelèisso.
Ël test che a-i era ant le vàire version a peulo s-ciairelo mach j'aministrator.",
'undelete-revision' => 'Revision ëscancelà ëd $1 (dël $4, a $5) da $3:',
'undeleterevision-missing' => "Revision nen bon-a ò ch'a-i é nen d'autut. A peul esse ch'a l'abia n'anliura cioca, ma a peul ëdcò esse che la revision a la sia stàita torna butà ò gavà via da 'nt la base dij dat.",
'undelete-nodiff' => 'Pa gnun-a revision anans ëd costa.',
'undeletebtn' => 'Ripristiné',
'undeletelink' => 'vëdde/ripristiné',
'undeleteviewlink' => 'vëdde',
'undeletereset' => "Buté torna tut tanme 'l sòlit",
'undeleteinvert' => 'Anvertì la selession',
'undeletecomment' => 'Rason:',
'undeletedrevisions' => '{{PLURAL:$1|Na revision pijàita|$1 revision pijàite}} andré',
'undeletedrevisions-files' => "{{PLURAL:$1|Na|$1}} revision e {{PLURAL:$2|n'|$2&nbsp;}}archivi pijàit andré",
'undeletedfiles' => "{{PLURAL:$1|N'|$1&nbsp;}}archivi pijàit andaré",
'cannotundelete' => 'Riprìstin falì:
$1',
'undeletedpage' => "'''$1 a l'é stàit pijait andaré'''

Che as varda ël [[Special:Log/delete|Registr djë scancelament]] për ës-ciairé j'ùltim scancelament e arcuperassion.",
'undelete-header' => "Ch'a varda [[Special:Log/delete|ël registr djë scancelament]] për ës-ciairé j'ùltim dëscancelament.",
'undelete-search-title' => 'Sërché dle pàgine scancelà',
'undelete-search-box' => 'Arserché dle pàgine scancelà',
'undelete-search-prefix' => "Smon-e le pàgine ch'as anandio për:",
'undelete-search-submit' => 'Sërché',
'undelete-no-results' => "A-i é gnun-a pàgina parèj ant l'archivi dje scancelassion.",
'undelete-filename-mismatch' => "As peul nen ciapé andré la revision dl'archivi col marcatemp $1: Nòm d'archivi nen giust",
'undelete-bad-store-key' => "As peul pa pijesse andré la revision dl'archivi col marcatemp $1: L'archivi a-i era già pì nen anans d'esse scancelà.",
'undelete-cleanup-error' => "Eror ën scanceland l'archivi nen dovrà «$1».",
'undelete-missing-filearchive' => "As peul nen ricuperesse l'archivi con l'identità $1 përchè a-i é pa ant la base ëd dat. A peul esse ch'a l'abio già pijalo andré.",
'undelete-error' => "Pàgina d'eror d'arcuperassion",
'undelete-error-short' => "Eror ën arcuperand l'archivi: $1",
'undelete-error-long' => "Eror antramentr ch'as arcuperava l'archivi:

$1",
'undelete-show-file-confirm' => "É-lo sicur ëd vorèj vëdde la revision scancelà ëd l'archivi «<nowiki>$1</nowiki>» da $2 a $3?",
'undelete-show-file-submit' => 'É!',

# Namespace form on various pages
'namespace' => 'Spassi nominal:',
'invert' => 'Anvertì la selession',
'tooltip-invert' => "Ch'a selession-a sta casela për stërmé le modìfiche a le pàgine ant lë spassi nominal selessionà (e ant lë spassi nominal associà se selessionà)",
'namespace_association' => 'Spassi nominal associà',
'tooltip-namespace_association' => "Ch'a selession-a sta casela për anserì ëdcò la discussion o lë spassi nominal dël soget associà a lë spassi nomina selessionà",
'blanknamespace' => '(Prinsipal)',

# Contributions
'contributions' => "Contribussion dë st'{{GENDER:$1|utent}}-sì",
'contributions-title' => 'Contribussion ëd $1',
'mycontris' => 'Contribussion',
'contribsub2' => 'Për {{GENDER:$3|$1}} ($2)',
'nocontribs' => "A l'é pa trovasse gnun-a modìfica che a fussa conforma a costi criteri-sì",
'uctop' => '(corenta)',
'month' => 'Mèis:',
'year' => 'Ann:',

'sp-contributions-newbies' => 'Smon-e mach ël travaj dij cont neuv',
'sp-contributions-newbies-sub' => "Për j'utent neuv",
'sp-contributions-newbies-title' => "Contribussion ëd j'utent për ij neuv cont",
'sp-contributions-blocklog' => 'argistr dij blocagi',
'sp-contributions-deleted' => "Modìfiche d'utent scancelà",
'sp-contributions-uploads' => 'cariagi',
'sp-contributions-logs' => 'registr',
'sp-contributions-talk' => 'discussion',
'sp-contributions-userrights' => "gestion dij drit ëd j'utent",
'sp-contributions-blocked-notice' => "St'utent-sì a l'é al moment blocà. L'ùltima vos dël registr dij blocagi a l'é butà sì-sota 'me arferiment:",
'sp-contributions-blocked-notice-anon' => "St'adrëssa IP a l'é blocà al moment.
L'ùltima intrada dël registr dij blocagi a l'é butà sì-sota për arferiment:",
'sp-contributions-search' => 'Arserché le contribussion',
'sp-contributions-username' => "Adrëssa IP ò stranòm dl'utent:",
'sp-contributions-toponly' => "Mostré mach le modìfiche ch'a son j'ùltime revision",
'sp-contributions-submit' => 'Arserché',

# What links here
'whatlinkshere' => "Pàgine con dj'anliure che a men-o a costa-sì",
'whatlinkshere-title' => "Pàgine ch'a men-o a «$1»",
'whatlinkshere-page' => 'Pàgina:',
'linkshere' => "Le pàgine sì-sota a l'han andrinta dj'anliure che a men-o a '''[[:$1]]''':",
'nolinkshere' => "A-i é pa gnun-a pàgina che a l'abia dj'anliure che a men-o a '''[[:$1]]'''.",
'nolinkshere-ns' => "An cost ëspassi nominal-sì a-i é gnun-e pàgine con dj'anliure ch'a men-o a '''[[:$1]]'''.",
'isredirect' => 'ridiression',
'istemplate' => 'inclusion',
'isimage' => "anliura a l'archivi",
'whatlinkshere-prev' => "{{PLURAL:$1|d'un andré|andré ëd $1}}",
'whatlinkshere-next' => "{{PLURAL:$1|d'un anans|anans ëd $1}}",
'whatlinkshere-links' => '← anliure',
'whatlinkshere-hideredirs' => '$1 le ridiression',
'whatlinkshere-hidetrans' => '$1 anclusion',
'whatlinkshere-hidelinks' => '$1 anliura',
'whatlinkshere-hideimages' => "$1 j'archivi lijà",
'whatlinkshere-filters' => 'Filtr',

# Block/unblock
'autoblockid' => 'Blocagi automàtich #$1',
'block' => "Bloché l'utent",
'unblock' => "Dësbloché l'utent",
'blockip' => "Bloché l'utent",
'blockip-title' => "Bloché l'utent",
'blockip-legend' => "Bloché l'utent",
'blockiptext' => "Che a deuvra ël mòdulo ëd domanda 'd blocagi ambelessì sota për bloché l'acess con drit dë scritura da chèich adrëssa IP o stranòm.<br />
Ës blocagi-sì as dev dovresse MACH për evité dij comportament vandàlich, ën strèita osservansa ëd tùit ij prinsipi dle [[{{MediaWiki:Policy-url}}|régole ëd {{SITENAME}}]].<br />
Ël blocagi a peul ën gnun-a manera esse dovrà për dle question d'ideologìa.

Che a scriva codì che st'adrëssa IP o së stranòm a dev second chiel esse blocà (pr'esempi, che a buta ij tìtoj ëd pàgine che a l'abio già patì dj'at vandàlich da cost'adrëssa IP o së stranòm).",
'ipadressorusername' => 'Adrëssa IP ò stranòm',
'ipbexpiry' => 'Fin-a al',
'ipbreason' => 'Rason:',
'ipbreasonotherlist' => 'Àotr motiv',
'ipbreason-dropdown' => "*Motiv sòlit për ij blocagi
** Avej butà d'anformassion fàosse
** Avej gavà dël contnù da 'nt le pàgine
** Avèj butà dla rumenta porcherìa coma anliure d'areclam
** Avèj butà test sensa sust ant le pàgine
** Avèj un deuit da bërsach con la gent
** Avèj dovrà vàire cont fòra dij deuit
** Stranòm ch'as peul nen acetesse",
'ipb-hardblock' => "Proibì a j'utent intrà ant ël sistema ëd modifiché da cost'adrëssa IP",
'ipbcreateaccount' => 'Lassé pa pi creé dij cont neuv',
'ipbemailban' => "Nen lassé che l'utent a peula mandé ëd mëssagi ëd pòsta eletrònica",
'ipbenableautoblock' => "Bloché an automàtich la dariera adrëssa IP dovrà da l'utent e tute cole dont peuj cheidun as preuva a fé dle modìfiche",
'ipbsubmit' => "Bloché st'utent-sì",
'ipbother' => "N'àutra durà",
'ipboptions' => "2 ore:2 hours,1 di:1 day,3 di:3 days,na sman-a:1 week,2 sman-e:2 weeks,1 mèis:1 month,3 mèis:3 months,6 mèis:6 months,n'ann:1 year,për sempe:infinite",
'ipbotheroption' => "d'àutr",
'ipbotherreason' => 'Àotri motiv/spiegon',
'ipbhidename' => "Stërmé lë stranòm da 'nt le modìfiche e da 'nt j'elench",
'ipbwatchuser' => "Ten-e d'euj le pàgine utent e ëd discussion dë st'utent-sì",
'ipb-disableusertalk' => "Proibì a st'utent ëd modifiché soa pàgina ëd discussion quand a l'é blocà",
'ipb-change-block' => "Bloché l'utent con coste ampostassion",
'ipb-confirm' => 'Confermé ël blocagi',
'badipaddress' => "L'adrëssa IP che a l'ha dane a l'é nen giusta.",
'blockipsuccesssub' => 'Blocagi fàit',
'blockipsuccesstext' => "[[Special:Contributions/$1|$1]] a l'é stàit blocà.<br />
Ch'a consulta la [[Special:BlockList|lista dij blocagi]] për rivëdde ij blocagi.",
'ipb-blockingself' => "A l'é an camin ch'as blòca chiel-midem! É-lo sigur ëd vorèj fé lòn?",
'ipb-confirmhideuser' => "A l'é an camin ch'a blòca n'utent con «stërmé l'utent» abilità. Sòn a gaverà lë stranòm ëd l'utent da tute le liste e le vos ëd registr. É-lo sigur ëd vorèj fé lòn?",
'ipb-edit-dropdown' => 'Modifiché le rason dël blocagi',
'ipb-unblock-addr' => 'Dësbloché $1',
'ipb-unblock' => "Dësbloché n'utent ò n'adrëssa IP",
'ipb-blocklist' => 'Vardé ij blocagi ativ',
'ipb-blocklist-contribs' => 'Contribussion për $1',
'unblockip' => "Dësbloché n'utent",
'unblockiptext' => "Che a deuvra ël formolari ambelessì-sota për deje andré ël drit dë scritura a n'adrëssa IP o në stranòm che a l'era stàit blocà.",
'ipusubmit' => 'Gavé ës blocagi',
'unblocked' => "[[User:$1|$1]] a l'é stait dësblocà",
'unblocked-range' => "$1 a l'é stàit dësblocà",
'unblocked-id' => "Ël blocagi $1 a l'é stait gavà via",
'blocklist' => 'Utent blocà',
'ipblocklist' => 'Utent blocà',
'ipblocklist-legend' => "Trové n'utent blocà",
'blocklist-userblocks' => 'Stërmé ij blocagi dij cont',
'blocklist-tempblocks' => 'Stërmé ij blocagi a temp',
'blocklist-addressblocks' => "Stërmé ij blocagi d'adrësse IP ùniche",
'blocklist-rangeblocks' => "Stërmé ij blocagi d'antërval",
'blocklist-timestamp' => 'Stampin data e ora',
'blocklist-target' => 'Bërsaj',
'blocklist-expiry' => 'Scadensa',
'blocklist-by' => "Aministrator ch'a l'ha blocà",
'blocklist-params' => 'Paràmeter ëd blocage',
'blocklist-reason' => 'Rason',
'ipblocklist-submit' => 'Arserché',
'ipblocklist-localblock' => 'Blocagi local',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Àutr blocagi|Àutri blocagi}}',
'infiniteblock' => 'për sempe',
'expiringblock' => 'a finiss ël $1 a $2',
'anononlyblock' => "mach j'utent anònim",
'noautoblockblock' => 'blocagi automàtich nen ativ',
'createaccountblock' => 'creassion dij cont blocà',
'emailblock' => 'pòsta eletrònica blocà',
'blocklist-nousertalk' => 'a peul nen modifiché soa pàgina ëd discussion',
'ipblocklist-empty' => "La lista dij blocagi a l'é veujda.",
'ipblocklist-no-results' => "L'adrëssa IP ò lë stranòm ch'a l'ha ciamà a l'é pa blocà.",
'blocklink' => 'bloché',
'unblocklink' => 'dësbloché',
'change-blocklink' => 'modifiché ël blocagi',
'contribslink' => 'contribussion',
'emaillink' => 'mandé un mëssagi eletrònich',
'autoblocker' => "A l'é scataje un blocagi përchè soa adrëssa IP a l'é staita dovrà ant j'ùltim temp da l'Utent «[[User:$1|$1]]». Ël motiv për bloché $1 a l'é stait: «'''$2'''»",
'blocklogpage' => 'Registr dij blocagi',
'blocklog-showlog' => "St'utent-sì a l'é già stàit blocà ant ël passà. Ël registr dij blocagi a l'é disponìbil sì-sota 'me arferiment:",
'blocklog-showsuppresslog' => "St'utent-sì a l'é già stàit blocà e stërmà. Ël registr ëd j'eliminassion a l'é smonù sì-sota për arferiment:",
'blocklogentry' => "«[[$1]]» a l'é stait blocà për $2 $3",
'reblock-logentry' => "a l'ha cambià j'ampostassion dël blocagi për [[$1]] con na scadensa ai $2 $3",
'blocklogtext' => "Sossì a l'é ël registr dij blocagi e dësblocagi dj'Utent. J'adrësse che
a son ëstàite blocà n'automàtich ambelessì a së s-ciàiro nen.
Che a varda la [[Special:BlockList|lista dij blocagi]] për vëdde
coj che sio ij blocagi ativ al dì d'ancheuj.",
'unblocklogentry' => "a l'ha dësblocà $1",
'block-log-flags-anononly' => 'mach utent anònim',
'block-log-flags-nocreate' => 'creassion ëd cont neuv blocà',
'block-log-flags-noautoblock' => "blocagi n'autòmatich dësmòrt",
'block-log-flags-noemail' => 'pòsta eletrònica blocà',
'block-log-flags-nousertalk' => 'a peul nen modifiché soa pàgina ëd discussion',
'block-log-flags-angry-autoblock' => 'blocagi automàtich avansà ativà',
'block-log-flags-hiddenname' => 'stranòm stërmà',
'range_block_disabled' => "La possibilità che n'aministrator a fasa dij blocagi a ragg a l'é disabilità.",
'ipb_expiry_invalid' => 'Temp dë scadensa nen bon.',
'ipb_expiry_temp' => 'Ij blocagi djë stranòm ëstërmà a devo esse përmanent.',
'ipb_hide_invalid' => 'Impossìbil scancelé ës cont; a podrìa avèj tròpe modìfiche.',
'ipb_already_blocked' => "«$1» a l'é già blocà",
'ipb-needreblock' => "$1 a l'é già blocà. Veul-lo cambié j'ampostassion?",
'ipb-otherblocks-header' => '{{PLURAL:$1|Àutr|Àutri}} blocagi',
'unblock-hideuser' => "A peul pa dësbloché st'utent, da già che sò nòm a l'é stàit stërmà.",
'ipb_cant_unblock' => 'Eror: As treuva nen ël blocagi con identificativ $1. A peul esse che a sia un blocagi già gavà via.',
'ipb_blocked_as_range' => "Eror: L'adrëssa IP $1 a l'ha gnun blocagi diret ansima e donca a peul pa esse dësblocà. A resta blocà mach për via ch'a l'é ciapà andrinta al ragg $2, e lolì as peul pa dësblochesse.",
'ip_range_invalid' => 'Nùmer IP nen bon.',
'ip_range_toolarge' => "Ij blocagi d'antërvaj pi gròss che /$1 a son pa përmëttù.",
'proxyblocker' => "Blocador dj'arpetitor",
'proxyblockreason' => "Soa adrëssa IP a l'é stàita blocà përchè a l'é cola ëd n'arpetitor duvèrt.
Për piasì che a contata sò fornitor ëd conession e che a lo anforma. As trata d'un problema ëd sigurëssa motobin serios.",
'sorbsreason' => "Soa adrëssa IP a l'é listà coma arpetitor duvert (open proxy) ansima al DNSBL dovrà da {{SITENAME}}.",
'sorbs_create_account_reason' => "Soa adrëssa IP a l'é listà coma arpetitor duvèrt (open proxy) ansima al DNSBL dovrà da {{SITENAME}}. A peul nen creésse un cont.",
'xffblockreason' => "N'adrëssa IP ant l'antestassion X-Forwarded-For, la soa o cola d'un servent fantasma che chiel a deuvra, a l'é stàita blocà. La rason dël blocagi inissial a l'era: $1",
'cant-block-while-blocked' => "A peul pa bloché d'àutri utent antramentre che chiel a l'é blocà.",
'cant-see-hidden-user' => "L'utent ch'a l'é an camin ch'a preuva a bloché a l'é già stàit blocà e stërmà. Da già ch'a l'ha pa ël drit hideuser, a peul pa vëdde o modifiché ël blocagi ëd cost utent.",
'ipbblocked' => "A peul pa bloché o dësbloché d'àutri utent, përchè a l'é blocà chiel-midem",
'ipbnounblockself' => "A l'é nen autorisà a dësblochesse da sol",

# Developer tools
'lockdb' => 'Bloché la base ëd dat',
'unlockdb' => 'Dësbloché la base ëd dat',
'lockdbtext' => "Ën blocand la base dat as fërma la possibilità che tuti j'Utent a peulo modifiché le pàgine ò pura fene 'd neuve, che a peulo cambiesse ij «sò gust», che a peulo modifichesse soe liste dla ròba da tnì sot euj, e an general gnun a podrà pì fé dj'operassion che a ciamo dë modifiché la base dat.<br /><br />
Për piasì, che an conferma che sossì a l'é pròpe lòn che a veul fé, e dzortut che a dësblocherà la base dat pì ampressa che a peul, an manera che tut a funsion-a torna coma che as dev, pen-a che a l'avrà finisse soa manutension.",
'unlockdbtext' => "Ën dësblocand la base dat as darà andaré a tuti j'Utent la possibilità dë fé 'd modìfiche a le pàgine ò dë fene ëd neuve, ëd cangé ij «sò gust», ëd modifiché soe liste 'd ròba da tnì sot euj, e pì an general dë fé tute cole operassion che a l'han da manca dë fé 'd modìfiche a la base dat.
Për piasì, che an conferma che sòn a l'é da bon lòn che chiel a veul fé.",
'lockconfirm' => 'Bò, i veuj da bon, e sota mia responsabilità, bloché la base dij dat.',
'unlockconfirm' => 'Bò, da bon i veuj dësbloché la base dij dat, sota mia responsabilità përsonal.',
'lockbtn' => 'Bloché la base dij dat',
'unlockbtn' => 'Dësbloché la base dij dat',
'locknoconfirm' => "Che a varda che a l'é dësmentiasse dë sponté ël quadrèt ëd conferma.",
'lockdbsuccesssub' => 'Blocagi dla base dij dat fàit',
'unlockdbsuccesssub' => "Dësblocagi dla base ëd dat fàit, ël blocagi a l'é stàit gavà",
'lockdbsuccesstext' => "La base dat ëd {{SITENAME}} a l'é stàita blocà.
<br />Che as visa mach dë [[Special:UnlockDB|gavé ël blocagi]] pen-a che a l'ha finì soa manutension.",
'unlockdbsuccesstext' => "La base ëd dat a l'é stàita dësblocà.",
'lockfilenotwritable' => "As peul nen ëscrive ant sl'archivi ëd blocagi dla base ëd dat. A fa dë manca d'avèj n'acess an scritura a st'archivi dal servent për podèj bloché o dësbloché la base ëd dat.",
'databasenotlocked' => "La base ëd dat a l'é nen blocà.",
'lockedbyandtime' => '(për {{GENDER:$1|$1}} ël $2 a $3)',

# Move page
'move-page' => 'Tramud ëd $1',
'move-page-legend' => 'Tramudé na pàgina',
'movepagetext' => "An dovrand ël mòdul ambelessì-sota a cangerà nòm a na pàgina, tramudand-je dapress ëdcò tuta soa cronologìa anvers al nòm neuv.
Ël vej tìtol a resterà trasformà ant na ridiression che a men-a al tìtol neuv.
As peul agiorné an automàtich le ridiression che a men-o al tìtol original.
Se a decid ëd nen felo, ch'a contròla le [[Special:DoubleRedirects|ridiression dobie]] o le [[Special:BrokenRedirects|ridiression ch'a men-o da gnun-e part]].
A l'é responsàbil ëd controlé che le liure a men-o ancora andoa as pensa che a devo mné.

Noté bin che la pàgina a sarà '''nen''' tramudà se a-i fussa già mai n'artìcol che a l'ha ël nòm neuv, gavà col cas che a sia na ridiression, e che a l'abia già nen na soa cronologìa.
Sòn a veul dì che, se a fèissa n'operassion nen giusta, a podrìa sempe torné a rinominé la pàgina col nòm vej, ma ant gnun cas a podrìa coaté na pàgina che a-i é già.

'''ATENSION!'''
Un cambiament dràstich e nen ëspetà parèj a podrìa dé dle gran-e dzora a na pàgina motobin visità.
Che a varda mach dë esse pì che sigur d'avèj presente le conseguense, prima che fé che fé.",
'movepagetext-noredirectfixer' => "Dovré ël formolari sì-sota a arnominërà na pàgina, tramudand tuta soa stòria al nòm neuv.
Ël tìtol vèj a vnirà na pàgina ëd ridiression al tìtol neuv.
Ch'as sigura ëd controlé le ridiression [[Special:DoubleRedirects|dobie]] o cole [[Special:BrokenRedirects|ch'a marcio nen]].
A l'é responsàbil ëd fé an manera che le liure a continuo a ponté andova as pensa ch'a vado.

Ch'a armarca che la pàgina a sarà '''pa''' tramudà s'a-i é già na pàgina con ël tìtol neuv, gavà ch'a sia veuida o na ridiression e ch'a l'abia pa na stòria ëd modìfiche passà.
Son a veul dì ch'a peul torna arnominé na pàgina andré da andova a l'avìa arnominala s'a fa n'eror, e ch'a peul pa coaté na pàgina esistenta.

'''Avis!'''
Sossì a peul esse un cambi dràstich e pa spetà për na pàgina popolar;
për piasì ch'as renda bin cont ëd le conseguense ëd sòn prima d'andé anans.",
'movepagetalktext' => "La pàgina ëd discussion tacà a costa pàgina d'artìcol, se a-i é, a sarà tramudà n'automatich ansema a l'artìcol, '''gavà costi cas-sì''':
*quand as tramuda la pàgina tra diferent spassi nominaj,
*quand na pàgina ëd discussion nen veujda a-i é già për ël nòm neuv, ò pura
*a l'ha desselessionà ël quadrèt ëd conferma ambelessì-sota.

Ant costi cas-sì, se a chërd dë felo, a-j farà da manca dë tramudesse la pàgina ëd discussion daspërchiel, a man.",
'movearticle' => "Cangeje nòm a l'artìcol:",
'moveuserpage-warning' => "'''Atension:''' A sta për tramudé na pàgina d'utent. Për piasì ch'a nòta che a sarà tramudà mach la pàgina e che l'utent a sarà ''pa'' arbatjà.",
'movenologin' => "Che a varda che chiel a l'é pa rintrà ant ël sistema",
'movenologintext' => "A venta esse n'Utent registrà e esse [[Special:UserLogin|rintrà ant ël sistema]]
për podèj tramudé na pàgina.",
'movenotallowed' => "A l'ha pa ij përmess dont a fa da manca për tramudé le pàgine.",
'movenotallowedfile' => "A l'ha pa ij përmess për tramudé j'archivi.",
'cant-move-user-page' => "A l'ha pa ij përmess për tramudé le pàgine d'utent (gavà le sot-pàgine).",
'cant-move-to-user-page' => "A l'ha pa ël përmess për tramudé na pàgina a na pàgina utent (gavà a na sot-pàgina utent).",
'newtitle' => 'Neuv tìtol ëd',
'move-watch' => 'Ten-e sot-euj la pàgina sorgiss e la pàgina selessionà',
'movepagebtn' => 'Tramudé la pàgina',
'pagemovedsub' => 'San Martin bele finì!',
'movepage-moved' => "'''«$1» a l'é stàit tramudà a «$2»'''",
'movepage-moved-redirect' => "A l'é stàita creà na ridiression.",
'movepage-moved-noredirect' => "La creassion ëd na ridiression a l'é stàita scancelà.",
'articleexists' => "Na pàgina che as ciama parej a-i é già, ò pura ël nòm che a l'ha sërnù a va nen bin.<br />
Che as sërna, për piasì, un nòm diferent për st'artìcol.",
'cantmove-titleprotected' => "As peul pa fesse San Martin ambelelì, për via che col tìtol-lì a l'é stàit proibì e a peul pa ess-ie na pàgina ciamà parèj",
'talkexists' => "La pàgina a l'é staita bin tramudà, ma a l'é pa podusse tramudé soa pàgina ëd discussion, përchè a-i në j'é già n'àutra ant la pàgina con ël tìtol neuv. Për piasì, che a modìfica a man ij contnù dle doe pàgine ëd discussion, an manera che as perdo nen dij pensé anteressant.",
'movedto' => 'tramudà a',
'movetalk' => "Tramudé ëdcò la pàgina ëd discussion che a l'ha tacà",
'move-subpages' => 'Tramudé le sot-pàgine (fin a $1)',
'move-talk-subpages' => 'Tramudé le sot-pàgine ëd la pàgina ëd discussion (fin a $1)',
'movepage-page-exists' => 'La pàgina $1 a esist già e a peul pa esse coatà automaticament.',
'movepage-page-moved' => "La pàgina $1 a l'é stàita tramudà a $2.",
'movepage-page-unmoved' => 'La pàgina $1 a peul pa esse tramudà a $2.',
'movepage-max-pages' => "Ël màssim ëd {{PLURAL:$1|na pàgina|pàgine}} a l'é stàit tramudà e a na saran pa pì tramudà automaticament.",
'movelogpage' => 'Registr dij San Martin',
'movelogpagetext' => 'Ambelessì sota a-i é na lista ëd tute le pàgine che a son ëstàite tramudà.',
'movesubpage' => '{{PLURAL:$1|Sot-pàgina|Sot-pàgine}}',
'movesubpagetext' => "Costa pàgina-sì a l'ha $1 {{PLURAL:$1|sot-pàgina|sot-pàgine}} smonùe sì-sota.",
'movenosubpage' => "Sta pàgina-sì a l'ha gnun-e sot-pàgine.",
'movereason' => 'Rason:',
'revertmove' => "buté torna coma a l'era",
'delete_and_move' => 'Scancelé e tramudé',
'delete_and_move_text' => "==A fa da manca dë scancelé==

L'artìcol ëd destinassion «[[:$1]]» a-i é già. Veul-lo scancelelo për avèj ëd pòst për tramudé l'àutr?",
'delete_and_move_confirm' => 'É, scancelé la pàgina',
'delete_and_move_reason' => 'Scancelà për liberé ël pòst për tramudé «[[$1]]»',
'selfmove' => "Tìtol neuv e tìtol vej a resto midem antra 'd lor; as peul pa tramudesse na pàgina butand-la andoa che a l'é già.",
'immobile-source-namespace' => 'A peul pa tramudé le pàgine ant lë spassi nominal «$1»',
'immobile-target-namespace' => 'A peul pa tramudé dle pàgine vers lë spassi nominal «$1»',
'immobile-target-namespace-iw' => "Na liura interwiki a l'é pa na destinassion vàlida për tramudé na pàgina.",
'immobile-source-page' => 'Sta pàgina-sì as peul pa tramudesse.',
'immobile-target-page' => 'As peul pa tramudesse vers cost tìtol ëd destinassion.',
'bad-target-model' => 'La destinassion vorsùa a deuvra un model ëd contnù diferent. As peul pa convertisse da $1 a $2.',
'imagenocrossnamespace' => "As peul pa tramudesse n'archivi a në spassi nominal diferent",
'nonfile-cannot-move-to-file' => "As peul nen tramudesse lòn ch'a l'é pa n'archivi a lë spassi nominal dj'archivi",
'imagetypemismatch' => "La neuva estension ëd l'archivi a corispond pa a sò tipo",
'imageinvalidfilename' => "Ël nòm ëd l'archivi bërsaj a l'é nen bon",
'fix-double-redirects' => 'Agiorné tute le ridiression che a ponto vers ël tìtol original',
'move-leave-redirect' => 'Lassé na ridiression',
'protectedpagemovewarning' => "'''Avis:''' Sta pàgina-sì a l'é stàita blocà parèj che mach j'utent con ij drit d'aministrator a peulo tramudela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'semiprotectedpagemovewarning' => "'''Nòta:''' Sta pàgina-sì a l'é stàita blocà parèj che mach j'utent argistrà a peulo tramudela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'move-over-sharedrepo' => "== L'archivi a esist ==
[[:$1]] a esist già dzora a un depòsit partagià. Tramudé n'archivi a cost tìtol-sì a coaterà l'archivi partagià.",
'file-exists-sharedrepo' => "Ël nòm d'archivi sërnù a l'é già dovrà ant ël depòsit condivis.
Për piasì ch'a serna n'àutr nòm.",

# Export
'export' => 'Esporté dle pàgine',
'exporttext' => "A peul esporté ël test e modifiché la stòria ëd na pàgina ò pura
ëd n'ansema ëd pàgine gropà ant n'archivi XML. Sòn a peul peuj amportesse
ant n'àutra wiki ën dovrand MediaWiki con la [[Special:Import|pàgina d'amportassion]].

Për esporté le pàgine, che a së scriva ij tìtoj ant ël quàder ambelessì-sota, butandje un tìtol për riga,
e che as serna se a veul la version corenta ansema a cole veje, con le righe che a conto la stòria dla pàgina,
ò pura mach l'anformassion an  sl'ùltima modìfica.

Se costa ùltima possibilità a fussa lòn che a-j serv, a podrìa ëdcò dovré n'anliura, pr'esempi [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] për la pàgina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportall' => 'Esporté tute le pàgine',
'exportcuronly' => 'Ciapé sù mach la version corenta, pa tuta la stòria',
'exportnohistory' => "----
'''Nòta:''' la possibilità d'esporté la stòria completa dle pàgine a l'é staita gavà për dle question corelà a le prestassion dël sistema.",
'exportlistauthors' => 'Anclude na lista completa dij contributor për minca pàgina',
'export-submit' => 'Esporté',
'export-addcattext' => "Gionté le pàgine da 'nt la categorìa:",
'export-addcat' => 'Gionté',
'export-addnstext' => 'Gionté dle pàgine da lë spassi nominal:',
'export-addns' => 'Gionté',
'export-download' => 'Ciamé dë salvelo coma archivi',
'export-templates' => 'Ciapa andrinta jë stamp',
'export-pagelinks' => 'Anclude le pàgine colegà a na përfondità ëd:',

# Namespace 8 related
'allmessages' => 'Mëssagi ëd sistema',
'allmessagesname' => 'Nòm',
'allmessagesdefault' => "Test che a-i sarìa se a-i fusso pa 'd modìfiche",
'allmessagescurrent' => 'Test corent',
'allmessagestext' => "Costa-sì a l'é na lista dij mëssagi ëd sistema disponìbij ant lë spassi nominal MediaWiki.
Për piasì, ch'a vìsita la [//www.mediawiki.org/wiki/Localisation Localisassion ëd MediaWiki] e [//translatewiki.net translatewiki.net] se a veul contribuì a la localisassion general ëd MediaWiki.",
'allmessagesnotsupportedDB' => "Sta pàgina-sì a peul pa esse dovrà përchè '''\$wgUseDatabaseMessages''' a l'é stàit disabilità.",
'allmessages-filter-legend' => 'Filtr',
'allmessages-filter' => 'Filtré për stat ëd përsonalisassion:',
'allmessages-filter-unmodified' => 'Pa modificà',
'allmessages-filter-all' => 'Tùit',
'allmessages-filter-modified' => 'Modificà',
'allmessages-prefix' => 'Filtré për prefiss:',
'allmessages-language' => 'Lenga:',
'allmessages-filter-submit' => 'Apliché',

# Thumbnails
'thumbnail-more' => 'Slarghé',
'filemissing' => 'Archivi che a manca',
'thumbnail_error' => 'Eror antramentr che as fasìa la figurin-a: $1',
'thumbnail_error_remote' => "Mëssagi d'eror ëd $1:
$2",
'djvu_page_error' => 'Pàgina DjVu fòra dij lìmit',
'djvu_no_xml' => "As rièss pa a carié l'XML për l'archivi DjVu",
'thumbnail-temp-create' => "Pa bon a creé l'archivi ëd miniadura temporania",
'thumbnail-dest-create' => 'Pa bon a salvé na miniadura sla destinassion',
'thumbnail_invalid_params' => 'Paràmetro dla figurin-a pa giust',
'thumbnail_dest_directory' => 'As peul pa fesse ël dossié ëd destinassion',
'thumbnail_image-type' => 'Sòrt ëd figura nen gestì',
'thumbnail_gd-library' => 'Configurassion incompleta dla biblioteca GD: Fonsion $1 mancanta',
'thumbnail_image-missing' => "L'archivi a smija ch'a manca: $1",

# Special:Import
'import' => 'Amportassion ëd pàgine',
'importinterwiki' => 'Amportassion da wiki diferente',
'import-interwiki-text' => "Che a selession-a na wiki e ël tìtol dla pàgina da amporté.
Date dle revision e stranòm dj'editor a resteran piàjit sù 'cò lor.
Tute j'amportassion antra wiki diferente a resto marcà ant ël [[Special:Log/import|Registr dj'amportassion]].",
'import-interwiki-source' => 'Wiki e pàgina sorgiss:',
'import-interwiki-history' => 'Copié tute le revision ëd la stòria ëd costa pàgina',
'import-interwiki-templates' => 'Anserì tùit jë stamp',
'import-interwiki-submit' => 'Amporté',
'import-interwiki-namespace' => 'Spassi nominal ëd destinassion:',
'import-interwiki-rootpage' => 'Pàgina prinsipal ëd destinassion (opsional):',
'import-upload-filename' => "Nòm ëd l'archivi:",
'import-comment' => 'Oget:',
'importtext' => "Për piasì, che as espòrta l'archivi da 'nt la sorgiss wiki ën dovrand l'[[Special:Export|utiss d'esportassion]]. 
Che as lo salva ansima a sò ordinator e peui che a lo caria ambelessì.",
'importstart' => 'I soma antramentr che amportoma le pàgine...',
'import-revision-count' => '{{PLURAL:$1|Na|$1}} revision',
'importnopages' => 'Pa gnun-a pàgina da amporté',
'imported-log-entries' => 'Amportà $1 {{PLURAL:$1|vos ëd registr|vos ëd registr}}.',
'importfailed' => 'Amportassion falìa: $1',
'importunknownsource' => "Sorgiss d'amportassion ëd na sòrt nen conossùa",
'importcantopen' => "L'archivi da amporté a l'é pa podusse deurbe",
'importbadinterwiki' => 'Liura antra wiki diferente cioca',
'importnotext' => 'Veujd o con gnun test',
'importsuccess' => 'Amportassion finìa!',
'importhistoryconflict' => "A-i son dle stòrie dë sta pàgina-sì che as contradiso un-a con l'àutra (a peul esse che sta pàgina-sì a l'avèissa già amportala)",
'importnosources' => "A l'é pa stàita definìa gnun-a sorgiss d'amportassion da na wiki diferenta, e carié mach le stòrie as peul nen.",
'importnofile' => "Pa gnun archivi d'amportassion carià.",
'importuploaderrorsize' => "A l'é falìe la caria dl'archivi d'amporté. L'archivi a resta pì gròss che lòn ch'as peul cariesse.",
'importuploaderrorpartial' => "A l'é falìe la caria dl'archivi d'amporté. L'archivi a resta carià mach për un tòch.",
'importuploaderrortemp' => "A l'é falìe la caria dl'archivi d'amporté. A-i manca un dossié provisòri.",
'import-parse-failure' => "Eror dë scomposission XML ant l'amportassion",
'import-noarticle' => "Pa gnun-a pàgina d'amporté.",
'import-nonewrevisions' => "Tute le revision a j'ero già stàite amportà.",
'xml-error-string' => '$1 ant la riga $2, colòna $3 (byte $4): $5',
'import-upload' => 'Cariament ëd dat XML',
'import-token-mismatch' => "Pèrdita dij dat ëd session.
Për piasì, ch'a preuva torna.",
'import-invalid-interwiki' => 'As peul pa amportesse da la wiki spessificà.',
'import-error-edit' => "La pàgina «$1» a l'é pa stàita amportà përchè chiel a peul pa modifichela.",
'import-error-create' => "La pàgina «$1» a l'é pa stàita amportà përchè chiel a peul pa creela.",
'import-error-interwiki' => "La pàgina «$1» a l'é pa amportà përchè sò nòm a l'é arzervà për na liura esterna (antërwiki).",
'import-error-special' => "La pàgina «$1» a l'é pa amportà përchè a ponta a në spassi nominal ch'a përmët pa dle pàgine.",
'import-error-invalid' => "La pàgina «$1» a l'é pa amportà përchè sò nòm a l'é pa bon.",
'import-error-unserialize' => "La revision $2 dla pagina «$1» a peul pa esse desserialisà. La revision a l'era arportà përchè a deuvra ël model ëd contnù $3 serialisà com $4.",
'import-options-wrong' => '{{PLURAL:$2|Opsion|Opsion}} sbalià: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => "La pàgina prinsipal dàita a l'é un tìtol pa bon.",
'import-rootpage-nosubpage' => 'Lë spassi nominal «$1» ëd la pàgina prinsipal a përmët pa dle sot-pagine.',

# Import log
'importlogpage' => "Registr dj'amportassion",
'importlogpagetext' => "Amportassion aministrative ëd pàgine e ëd soa stòria da dj'àutre wiki.",
'import-logentry-upload' => "a l'ha amportà [[$1]] con un càrich d'archivi",
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|revision|revision}}',
'import-logentry-interwiki' => "Amportà da n'àutra wiki $1",
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revision|revision}} da $2',

# JavaScriptTest
'javascripttest' => 'Preuva ëd JavaScript',
'javascripttest-title' => 'Fé dle preuve $1',
'javascripttest-pagetext-noframework' => "Costa pàgina a l'é arservà për fé dle preuve JavaScript.",
'javascripttest-pagetext-unknownframework' => 'Strutura ëd preuva pa conossùa «$1».',
'javascripttest-pagetext-frameworks' => "Për piasì, ch'a serna un-a dle struture ëd preuva sì-dapress: $1",
'javascripttest-pagetext-skins' => "Ch'a serna na pel për fé le preuve:",
'javascripttest-qunit-intro' => 'Vëdde [$1 la documentassion dle preuve] dzora a mediawiki.org.',
'javascripttest-qunit-heading' => 'Sequensa ëd preuve QUnit ëd JavaScript su MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Soa pàgina utent',
'tooltip-pt-anonuserpage' => "La pàgina utent për l'IP con ël qual chiel a contribuiss",
'tooltip-pt-mytalk' => 'Soa pàgina ëd discussion e ciaciarade',
'tooltip-pt-anontalk' => 'La pàgina ëd ciaciarade an sle contribussion da costa adrëssa IP',
'tooltip-pt-preferences' => 'Coma che i veuj mia {{SITENAME}}.',
'tooltip-pt-watchlist' => 'Lista dle pàgine che chiel as ten sot euj.',
'tooltip-pt-mycontris' => 'Lista ëd soe contribussion',
'tooltip-pt-login' => "Un a l'é nen obligà a rintré ant al sistema, ma se a lo fa a l'é mej",
'tooltip-pt-anonlogin' => "Un a l'é nen obligà a rintré ant al sistema, ma se a lo fa a l'é mej",
'tooltip-pt-logout' => 'Seurte da',
'tooltip-ca-talk' => 'Discussion ansima a sta pàgina ëd contnù.',
'tooltip-ca-edit' => 'A peul modifiché sa pàgina-sì. Për piasì, che as fasa na preuva anans che salvé.',
'tooltip-ca-addsection' => 'Ancaminé na neuva session',
'tooltip-ca-viewsource' => "Sta pàgina-sì a l'é protegiùa.
A peul visualisene la sorgiss",
'tooltip-ca-history' => 'Veje version dla pàgina.',
'tooltip-ca-protect' => 'Protege costa pàgina',
'tooltip-ca-unprotect' => 'Cangé la protession ëd costa pàgina-sì',
'tooltip-ca-delete' => 'Scancelé sta pàgina-sì',
'tooltip-ca-undelete' => 'Pijé andré le modìfiche fàite a sta pàgina-sì, anans che a fussa scancelà.',
'tooltip-ca-move' => "Tramudé sta pàgina, visadì cangeje 'd tìtol.",
'tooltip-ca-watch' => 'Gionté sta pàgina-sì a la lista dle ròbe che as ten-o sot euj.',
'tooltip-ca-unwatch' => 'Gavé via sta pàgina da',
'tooltip-search' => 'Sërché an {{SITENAME}}',
'tooltip-search-go' => "Andé a na pàgina ch'as ciama parèj, sempe ch'a-i në sia un-a",
'tooltip-search-fulltext' => 'Sërché ës test-sì antra le pàgine dël sit',
'tooltip-p-logo' => 'Pàgina prinsipal.',
'tooltip-n-mainpage' => 'Visité la pàgina prinsipal.',
'tooltip-n-mainpage-description' => "Andé a la pàgina d'intrada",
'tooltip-n-portal' => 'Rësguard al proget, lòn che a peul fé, andoa trové còsa.',
'tooltip-n-currentevents' => 'Informassion ansima a lòn che a-i riva.',
'tooltip-n-recentchanges' => "Lista dj'ùltime modìfiche an sla wiki",
'tooltip-n-randompage' => 'Carié na pàgina basta che a sia.',
'tooltip-n-help' => 'Ël pòst për capì.',
'tooltip-t-whatlinkshere' => 'Lista ëd tute le pàgine dla wiki che a men-o ambelessì.',
'tooltip-t-recentchangeslinked' => 'Ùltime modìfiche dle pàgine andoa as peul andesse da costa.',
'tooltip-feed-rss' => 'Fluss RSS për costa pàgina',
'tooltip-feed-atom' => 'Fluss Atom për costa pàgina.',
'tooltip-t-contributions' => 'Vardé la lista dle contribussion ëd cost utent',
'tooltip-t-emailuser' => "Mandeje un mëssagi ëd pòsta a st'utent",
'tooltip-t-upload' => "Carié n'archivi ëd figure ò son.",
'tooltip-t-specialpages' => 'Lista ëd tute le pàgine speciaj.',
'tooltip-t-print' => 'Version bon-a da stampé dë sta pàgina',
'tooltip-t-permalink' => 'Anliura fissa a sta version-sì dla pàgina',
'tooltip-ca-nstab-main' => 'Vardé la pàgina ëd contnù.',
'tooltip-ca-nstab-user' => 'Vardé la pàgina Utent.',
'tooltip-ca-nstab-media' => 'Vardé la pàgina dël mojen',
'tooltip-ca-nstab-special' => "Costa a l'é na pàgina special, a peul nen modifichela.",
'tooltip-ca-nstab-project' => 'Vardé la pàgina proteta.',
'tooltip-ca-nstab-image' => "Vardé la pàgina dl'archivi",
'tooltip-ca-nstab-mediawiki' => 'Vardé ël mëssagi ëd sistema.',
'tooltip-ca-nstab-template' => 'Vardé lë stamp.',
'tooltip-ca-nstab-help' => "Vardé la pàgina d'agiut",
'tooltip-ca-nstab-category' => 'Vardé la pàgina dla categorìa.',
'tooltip-minoredit' => 'Marché sòn coma modìfica cita',
'tooltip-save' => 'Salvé le modìfiche',
'tooltip-preview' => 'Preuva dle modìfiche (mej sempe fela, prima che fé che salvé!)',
'tooltip-diff' => "A fa vëdde le modìfiche che a l'ha faje al test",
'tooltip-compareselectedversions' => 'Fé ël paragon dle diferense antra le version selessionà.',
'tooltip-watch' => 'Gionté sta pàgina-sì a la lista dle ròbe che im ten-o sot euj',
'tooltip-watchlistedit-normal-submit' => 'Gavé via ij tìtoj',
'tooltip-watchlistedit-raw-submit' => "Agiorné la lista dle ròbe ch'as ten-o sot-euj",
'tooltip-recreate' => 'Creé torna la pàgina contut che a la sia stàita scancelà',
'tooltip-upload' => 'Anandiesse a carié',
'tooltip-rollback' => "«Tiré andré» a gava con un colp ëd rat le modìfiche fàite a costa pàgina da l'ùltim contributor",
'tooltip-undo' => "«Buté 'me ch'a l'era» a scancela costa modìfica e a deurb la fnestra ëd modìfica an manera ëd preuva.
A lassa gionté na spiegassion ant ël resumé.",
'tooltip-preferences-save' => 'Salvé ij sò gust',
'tooltip-summary' => 'Anserì un curt resumé',

# Stylesheets
'common.css' => '/** Ël còdes CSS che as buta ambelessì a resta dovrà ant tute le "facie" */',
'monobook.css' => "/* cangé st'archivi-sì për modifiché la formatassion dël sit antregh */",

# Scripts
'common.js' => "/* Ël còdes JavaScript ch'as buta ambelessì a ven carià da vira utent për vira pàgina */",
'monobook.js' => "/* Ës messagi-sì as dovrìa pa pì dovrelo; a sò pòst ch'a dòvra [[MediaWiki:common.js]] */",

# Metadata
'notacceptable' => 'Ës servent ëd la wiki a-i la fa pa a fornì dij dat ant na forma che sò programa local a peula lese.',

# Attribution
'anonymous' => '{{PLURAL:$1|Utent|Utent}} anònim ëd {{SITENAME}}',
'siteuser' => '$1, utent ëd {{SITENAME}}',
'anonuser' => "l'utent anònim $1 ëd {{SITENAME}}",
'lastmodifiedatby' => "Costa pàgina-sì a l'é staita modificà l'ùltima vira a $2, $1 da $3.",
'othercontribs' => 'Basà ant sëj travaj ëd $1.',
'others' => 'àutri',
'siteusers' => '$1, {{PLURAL:$2|utent|utent}} ëd {{SITENAME}}',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|utent|utent}} anònim $1',
'creditspage' => 'Paternità dla pàgina',
'nocredits' => "A-i é gnun-a anformassion d'atribussion disponìbil për costa pàgina.",

# Spam protection
'spamprotectiontitle' => 'Filtror dla rumenta',
'spamprotectiontext' => "Ël test che a vorìa salvé a l'é stàit blocà dal filtror dla rumenta.
Sòn a l'é motobin belfé che a sia rivà përchè a-i era n'anliura a un sit estern ëd coj blocà.",
'spamprotectionmatch' => "Cost-sì a l'é ël test che a l'é restà ciapà andrinta al filtror dla rumenta: $1",
'spambot_username' => 'MediaWiki - trigomiro che a-j dà deuit a la rumenta',
'spam_reverting' => "Butà andaré a l'ùltima version che a l'avèissa pa andrinta dj'anliure a $1",
'spam_blanking' => "Pàgina dësvujdà, che tute le version a l'avìo andrinta dj'anliure a $1",
'spam_deleting' => 'Scancelà, dagià che tute le revision a contnisìo dle liure a $1',

# Info page
'pageinfo-title' => 'Anformassion për «$1»',
'pageinfo-not-current' => "J'anformassion a peulo mach esse smonùe për la revision an cors.",
'pageinfo-header-basic' => 'Anformassion ëd base',
'pageinfo-header-edits' => 'Modìfiche',
'pageinfo-header-restrictions' => 'Protession ëd la pàgina',
'pageinfo-header-properties' => 'Proprietà ëd la pàgina',
'pageinfo-display-title' => 'Tìtol visualisà',
'pageinfo-default-sort' => "ciav d'ordinament për sòlit",
'pageinfo-length' => 'Longheur ëd la pàgina (an byte)',
'pageinfo-article-id' => 'Identificativ ëd la pàgina',
'pageinfo-language' => 'Lenga dël contnù dla pàgina',
'pageinfo-robot-policy' => 'Indicisassion con robò',
'pageinfo-robot-index' => 'Autorisà',
'pageinfo-robot-noindex' => 'Vietà',
'pageinfo-views' => 'Nùmer ëd vìsite',
'pageinfo-watchers' => "Vàire utent ch'a ten-o sot-euj la pàgina",
'pageinfo-few-watchers' => 'Men ëd $1 {{PLURAL:$1|osservator}}',
'pageinfo-redirects-name' => 'Nùmer ëd ridiression vers costa pàgina-sì',
'pageinfo-subpages-name' => 'Sot-pàgine ëd costa pàgina',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|ridiression|ridiression}}; $3 {{PLURAL:$3|nen ridiression|nen ridiression}})',
'pageinfo-firstuser' => 'Creator ëd la pàgina',
'pageinfo-firsttime' => 'Data ëd creassion ëd la pàgina',
'pageinfo-lastuser' => 'Ùltim contributor',
'pageinfo-lasttime' => "Data ëd l'ùltima modìfica",
'pageinfo-edits' => 'Nùmer ëd modìfiche',
'pageinfo-authors' => "Nùmer d'autor diferent",
'pageinfo-recent-edits' => "Nùmer ëd modìfiche recente (ant j'ùltim $1)",
'pageinfo-recent-authors' => "Nùmer d'autor diferent recent",
'pageinfo-magic-words' => '{{PLURAL:$1|Paròla màgica|Paròle màgiche}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Categorìa|Categorìe}} stërmà ($1)',
'pageinfo-templates' => '{{PLURAL:$1|stamp contnù|stamp contnù}} ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Pàgina|Pàgine}} transcludùe dzor ($1)',
'pageinfo-toolboxlink' => 'Anformassion an sla pàgina',
'pageinfo-redirectsto' => 'Ridiression-a a',
'pageinfo-redirectsto-info' => 'anformassion',
'pageinfo-contentpage' => 'Contà com na pagina ëd contnù',
'pageinfo-contentpage-yes' => 'É!',
'pageinfo-protect-cascading' => 'Le protession a son a cascada da sì',
'pageinfo-protect-cascading-yes' => 'É!',
'pageinfo-protect-cascading-from' => 'Le protession a son a cascada da',
'pageinfo-category-info' => 'Anformassion an sla categorìa',
'pageinfo-category-pages' => 'Nùmer ëd pàgine',
'pageinfo-category-subcats' => 'Nùmer ëd sot-categorìe',
'pageinfo-category-files' => "Nùmer d'archivi",

# Patrolling
'markaspatrolleddiff' => 'Marché coma verificà',
'markaspatrolledtext' => 'Marché costa pàgina coma verificà',
'markedaspatrolled' => 'Marca dla verìfica butà',
'markedaspatrolledtext' => "La version selessionà ëd [[:$1]] a l'é staita marcà coma verificà.",
'rcpatroldisabled' => "Verìfica dj'ùltime modìfiche disabilità",
'rcpatroldisabledtext' => "La possibilità ëd verifichè j'ùltime modìfiche a l'é disabilità.",
'markedaspatrollederror' => 'As peul pa marché coma verificà',
'markedaspatrollederrortext' => 'A venta che a spessìfica che version che a veul marchè coma verificà.',
'markedaspatrollederror-noautopatrol' => "A l'ha nen ël përmess dë marchesse soe modìfiche coma «controlà».",
'markedaspatrollednotify' => "Ës cambi a $1 a l'é stàit marcà com verificà.",
'markedaspatrollederrornotify' => 'Marcadura com verificà falìa.',

# Patrol log
'patrol-log-page' => 'Registr dij contròj',
'patrol-log-header' => "Cost-sì a l'é un registr ëd le revision controlà.",
'log-show-hide-patrol' => '$1 registr verificà',

# Image deletion
'deletedrevision' => 'Veja version scancelà $1',
'filedeleteerror-short' => "Eror ën scanceland l'archivi: $1",
'filedeleteerror-long' => "A-i son ësta-ie dj'eror ën scanceland l'archivi:

$1",
'filedelete-missing' => "L'archivi «$1» as peul pa dëscancelesse, për via ch'a-i é nen.",
'filedelete-old-unregistered' => "La revision d'archivi specificà «$1» ant la base dij dat a-i é nen.",
'filedelete-current-unregistered' => "Ant la base dij dat l'archivi «$1» ch'a l'é specificasse a-i é pa.",
'filedelete-archive-read-only' => "Ël servent dla Ragnà a peul pa scriv-ie ant ël dossié d'archiviassion «$1».",

# Browsing diffs
'previousdiff' => '← Modìfica precedenta',
'nextdiff' => 'Modìfica apress →',

# Media information
'mediawarning' => "'''Atension!''': st'archivi-sì a podrìa avèj andrinta dël còdes butà-lì da cheidun për fé ëd darmagi.
An fasend-lo marcé ansima a sò ordinator chiel a podrìa porteje ëd dann a sò sistema.",
'imagemaxsize' => "Lìmit ëd la dimension ëd le plance:<br /> ''(për le pàgine ëd descrission dj'archivi)''",
'thumbsize' => 'Amzura dle figurin-e:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|pàgina|pàgine}}',
'file-info' => "amzura dl'archivi: $1, sòrt MIME: $2",
'file-info-size' => '$1 × $2 pontin, amzure: $3, sòrt MIME: $4',
'file-info-size-pages' => "$1 × $2 pontin, dimension ëd l'archivi: $3, sòrt MIME: $4, $5 {{PLURAL:$5|pàgina|pàgine}}",
'file-nohires' => 'Gnun-a risolussion pì bela disponìbil.',
'svg-long-desc' => "archivi an forma SVG, amzure nominaj $1 × $2 pontin, amzura dl'archivi: $3",
'svg-long-desc-animated' => "Archivi SVG animà, dimension $1 × $2 pontin, amzura dl'archivi: $3",
'svg-long-error' => 'Archivi SVG nen bon: $1',
'show-big-image' => 'Version a arzolussion pien-a',
'show-big-image-preview' => 'Amzure dë sta preuva: $1.',
'show-big-image-other' => '{{PLURAL:$2|Àutra arzolussion|Àutre arzolussion}}: $1.',
'show-big-image-size' => '$1 × $2 pontin',
'file-info-gif-looped' => 'an sicl',
'file-info-gif-frames' => '$1 {{PLURAL:$1|quàder|quàder}}',
'file-info-png-looped' => 'an sìrcol',
'file-info-png-repeat' => 'fàit andé $1 {{PLURAL:$1|vira|vire}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|quàder|quàder}}',
'file-no-thumb-animation' => "'''Nòta: Për dle limitassion técniche, le miniadure ëd s'archivi a saran pa animà.'''",
'file-no-thumb-animation-gif' => "'''Nòta: Për limitassion técniche, le miniadure ëd figure GIF a àuta arzolussion com costa a saran pa animà.'''",

# Special:NewFiles
'newimages' => 'Galarìa ëd figure e son neuv',
'imagelisttext' => "Ambelessì-sota a-i é {{PLURAL:$1|l'ùnica figura che a-i sia|na lista ëd '''$1''' figure, ordinà $2}}.",
'newimages-summary' => "Sta pàgina special-sì a la smon j'ùltim archivi carià.",
'newimages-legend' => 'Filtror',
'newimages-label' => "Nòm ëd l'archivi (o na soa part):",
'showhidebots' => '($1 trigomiro)',
'noimages' => 'Pa gnente da vëdde.',
'ilsubmit' => 'Arserché',
'bydate' => 'për data',
'sp-newimages-showfrom' => "Smon-e j'ùltim archivi multimojen a anandiesse da $2 dël $1",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|un second|$1 second}}',
'minutes' => '{{PLURAL:$1|$1 minuta|$1 minute}}',
'hours' => '{{PLURAL:$1|$1 ora|$1 ore}}',
'days' => '{{PLURAL:$1|$1 di|$1 di}}',
'weeks' => '{{PLURAL:$1|$1 sman-a|$1 sman-e}}',
'months' => '{{PLURAL:$1|$1 mèis}}',
'years' => '{{PLURAL:$1|$1 ann|$1 agn}}',
'ago' => '$1 fa',
'just-now' => 'pròpi adess',

# Human-readable timestamps
'minutes-ago' => '$1 {{PLURAL:$1|minuta|minute}} fa',
'seconds-ago' => '$1 {{PLURAL:$1second}} fa',
'monday-at' => 'Lùn-es a $1',
'tuesday-at' => 'Màrtes a $1',
'wednesday-at' => 'Merco a $1',
'thursday-at' => 'Giòbia a $1',
'friday-at' => 'Vënner a $1',
'saturday-at' => 'Saba a $1',
'sunday-at' => 'Dùminica a $1',
'yesterday-at' => 'Jer a $1',

# Bad image list
'bad_image_list' => "La forma a l'é costa-sì:

As ten-o bon-e mach le liste pontà (cole fàite ëd righe ch'as ancamin-o për *). La prima anliura ëd minca riga a l'ha da mné a n'archivi multimojen nen bon.
J'anliure ch'a-i ven-o dapress, ant sla midema riga, as conto për ecession (visadì, për pàgine andova st'archivi as peul butesse).",

# Metadata
'metadata' => 'Dat adissionaj',
'metadata-help' => "St'archivi a conten dj'anformassion adissionaj, che a l'é belfé che a sio stait giontà da la màchina fotogràfica digital ò pura dal digitalisatorr che a l'é stàit dovrà për creé la figura digital. Se la figura a fussa mai stàita modificà da 'nt soa forma original, a podrìa ëdcò riveje che chèich detaj a fusso ancor butà coma ant l'original, donca sensa ten-e cont ëd le modìfiche.",
'metadata-expand' => 'Smon-e ij dat adissionaj',
'metadata-collapse' => 'Stërmé ij dat adissionaj',
'metadata-fields' => "Ij camp dij metadat ëd la figura listà ant ës messagi-sì a saran ësmonù ant la visualisassion ëd la pàgina dla figura quand la tàula dij metadat a l'é stërmà.
J'àutri a saran stërmà coma stàndard.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude",

# Exif tags
'exif-imagewidth' => 'Larghëssa',
'exif-imagelength' => 'Autëssa',
'exif-bitspersample' => 'Bit për campion',
'exif-compression' => 'Schema ëd compression',
'exif-photometricinterpretation' => 'Composission dij pontin',
'exif-orientation' => 'Orientament',
'exif-samplesperpixel' => 'Nùmer ëd component',
'exif-planarconfiguration' => 'Sistemassion dij dat',
'exif-ycbcrsubsampling' => 'Rapòrt ëd campionament antra Y e C',
'exif-ycbcrpositioning' => 'Posissionament Y e C',
'exif-xresolution' => 'Risolussion orisontal',
'exif-yresolution' => 'Risolussion vertical',
'exif-stripoffsets' => 'Posission dij dat dla figura',
'exif-rowsperstrip' => 'Nùmer ëd righe për banda',
'exif-stripbytecounts' => 'Bytes për banda compressa',
'exif-jpeginterchangeformat' => 'Diferensa posissional anvers al SOI dël JPEG',
'exif-jpeginterchangeformatlength' => 'Byte ëd dat an formà JPEG',
'exif-whitepoint' => 'Pont cromàtich dël bianch',
'exif-primarychromaticities' => 'Coordinà cromàtiche dij color primari',
'exif-ycbcrcoefficients' => 'Coefissient dla matris ëd trasformassion dlë spassi dij color',
'exif-referenceblackwhite' => "Cobia ëd valor d'arferiment për bianch e nèir",
'exif-datetime' => 'Data e ora dle modìfiche',
'exif-imagedescription' => 'Tìtol dla figura',
'exif-make' => 'Fabricant dla màchina fotogràfica ò videocàmera',
'exif-model' => 'Model dla màchina',
'exif-software' => 'Programa dovrà',
'exif-artist' => 'Autor',
'exif-copyright' => "Titolar dël drit d'autor",
'exif-exifversion' => 'Version dël formà Exif',
'exif-flashpixversion' => 'A riva a la version Flashpix',
'exif-colorspace' => 'Spassi dij color',
'exif-componentsconfiguration' => 'Significà ëd minca component',
'exif-compressedbitsperpixel' => 'Sistema ëd compression dle figure',
'exif-pixelydimension' => 'Larghëssa dla figura',
'exif-pixelxdimension' => 'Autëssa dla figura',
'exif-usercomment' => 'Nòte lìbere',
'exif-relatedsoundfile' => 'Archivi sonor colegà',
'exif-datetimeoriginal' => 'Data e ora dla generassion dij dat',
'exif-datetimedigitized' => 'Data e ora dla digitalisassion',
'exif-subsectime' => 'Data, ora e frassion ëd second',
'exif-subsectimeoriginal' => 'Data e ora ëd creassion, con frassion ëd second',
'exif-subsectimedigitized' => 'Data e ora ëd digitalisassion, con frassion ëd second',
'exif-exposuretime' => "Temp d'esposission",
'exif-exposuretime-format' => '$1 sec ($2)',
'exif-fnumber' => 'Duvertura',
'exif-exposureprogram' => "Programa d'esposission",
'exif-spectralsensitivity' => 'Sensibilità spetral',
'exif-isospeedratings' => 'Sensibilità ISO',
'exif-shutterspeedvalue' => "Temp dë scat ëd l'APEX",
'exif-aperturevalue' => "Diaframa ëd l'APEX",
'exif-brightnessvalue' => 'Luminosità APEX',
'exif-exposurebiasvalue' => "Coression dl'esposission",
'exif-maxaperturevalue' => 'Duvertura màssima',
'exif-subjectdistance' => 'Distansa dël soget',
'exif-meteringmode' => "Càlcol dl'espossision",
'exif-lightsource' => "Sorgiss d'anluminassion",
'exif-flash' => 'Lòsna',
'exif-focallength' => 'Longheur focal dle lent',
'exif-subjectarea' => "Spassi d'anquadratura dël soget",
'exif-flashenergy' => 'Energìa dla lòsna',
'exif-focalplanexresolution' => 'Arzolussion dla coordinà X ant sël pian dla focal',
'exif-focalplaneyresolution' => 'Arzolussion dla coordinà Y ant sël pian dla focal',
'exif-focalplaneresolutionunit' => "Unità d'amzura për ël pian dla focal",
'exif-subjectlocation' => 'Posission dël soget',
'exif-exposureindex' => "Ìndes dl'esposission",
'exif-sensingmethod' => 'Métod ëd campionament',
'exif-filesource' => "Sorgiss dl'archivi",
'exif-scenetype' => "Sòrt d'anquadratura",
'exif-customrendered' => 'Process dla figura particolar',
'exif-exposuremode' => "Modalità dl'esposission",
'exif-whitebalance' => 'Balansa dël bianch',
'exif-digitalzoomratio' => "Rapòrt ëd l'avzinament digital",
'exif-focallengthin35mmfilm' => 'Longheur focal an na pelìcola da 35 mm',
'exif-scenecapturetype' => 'Sistema ëd campionament',
'exif-gaincontrol' => 'Contròl dël senari',
'exif-contrast' => 'Contrast',
'exif-saturation' => 'Saturassion',
'exif-sharpness' => 'Definission dij bòrd',
'exif-devicesettingdescription' => "Descrission dla configurassion dl'angign",
'exif-subjectdistancerange' => 'Ragg ëd distansa dël soget',
'exif-imageuniqueid' => 'Identificator ùnich dla figura',
'exif-gpsversionid' => 'Version dël GPS',
'exif-gpslatituderef' => 'Latitùdin setentrional ò meridional',
'exif-gpslatitude' => 'Latitùdin',
'exif-gpslongituderef' => 'Longitùdin oriental ò ossidental',
'exif-gpslongitude' => 'Longitùdin',
'exif-gpsaltituderef' => "Arferiment d'autëssa",
'exif-gpsaltitude' => 'Autëssa',
'exif-gpstimestamp' => 'Ora dël GPS (mostra atòmica)',
'exif-gpssatellites' => "Satélit dovrà për l'amzura",
'exif-gpsstatus' => 'Condission dël ricevitor',
'exif-gpsmeasuremode' => "Sistema d'amzura",
'exif-gpsdop' => "Precision dl'amzura",
'exif-gpsspeedref' => "Unità d'amzura për l'andi",
'exif-gpsspeed' => 'Velocità dël ricevitor GPS',
'exif-gpstrackref' => 'Arferiment për la diression dël moviment',
'exif-gpstrack' => 'Diression dël moviment',
'exif-gpsimgdirectionref' => 'Arferiment për la diression dla figura',
'exif-gpsimgdirection' => 'Diression dla figura',
'exif-gpsmapdatum' => "Dat dl'amzura geodética che a son dovrà",
'exif-gpsdestlatituderef' => 'Arferiment për la latitùdin dla destinassion',
'exif-gpsdestlatitude' => 'Latitùdin dla destinassion',
'exif-gpsdestlongituderef' => 'Arferiment për la longitùdin dla destinassion',
'exif-gpsdestlongitude' => 'Longitùdin dla destinassion',
'exif-gpsdestbearingref' => "Arferiment për l'orientament a destinassion",
'exif-gpsdestbearing' => 'Orientament vers la destinassion',
'exif-gpsdestdistanceref' => "Arferiment për la lontanansa da 'nt la destinassion",
'exif-gpsdestdistance' => "Lontanansa da 'nt la destinassion",
'exif-gpsprocessingmethod' => 'Nòm dël sistema ëd process an GPS',
'exif-gpsareainformation' => 'Nòm dlë spassi GPS',
'exif-gpsdatestamp' => 'Data dël GPS',
'exif-gpsdifferential' => 'Coression diferensial dël GPS',
'exif-jpegfilecomment' => "Coment ëd l'archivi JPEG",
'exif-keywords' => 'Paròle ciav',
'exif-worldregioncreated' => "Region dël mond anté che la fòto a l'é stàita pijà",
'exif-countrycreated' => "Pais anté che la fòto a l'é stàita fàita",
'exif-countrycodecreated' => "Còdes dël pais anté che la fòto a l'é stàita pijà",
'exif-provinceorstatecreated' => "Provinsa o stat anté che la fòto a l'é stàita pijà",
'exif-citycreated' => "Sità anté che la fòto a l'é stàita pijà",
'exif-sublocationcreated' => "Borgh ëd la sità anté che la fòto a l'é stàita pijà",
'exif-worldregiondest' => 'Region dël mond mostrà',
'exif-countrydest' => 'Pais mostrà',
'exif-countrycodedest' => 'Còdes dël pais mostrà',
'exif-provinceorstatedest' => 'Provinsa o stat mostrà',
'exif-citydest' => 'Sità mostrà',
'exif-sublocationdest' => 'Borgh ëd la sità mostrà',
'exif-objectname' => 'Tìtol curt',
'exif-specialinstructions' => 'Istrussion speciaj',
'exif-headline' => 'Antestassion',
'exif-credit' => 'Arconossiment/Fornitor',
'exif-source' => 'Sorgiss',
'exif-editstatus' => 'Stat ëd modìfica dla figura',
'exif-urgency' => 'Pressa',
'exif-fixtureidentifier' => 'Nòm element arcorent',
'exif-locationdest' => 'Locassion fotografà',
'exif-locationdestcode' => 'Còdes ëd la locassion fotografà',
'exif-objectcycle' => "Ora dël di ëd destinassion d'ës mojen",
'exif-contact' => 'Anformassion ëd contat',
'exif-writer' => 'Scritor',
'exif-languagecode' => 'Lenga',
'exif-iimversion' => 'version IIM',
'exif-iimcategory' => 'Categorìa',
'exif-iimsupplementalcategory' => 'Categorìa suplementar',
'exif-datetimeexpires' => 'Dovré nen apress',
'exif-datetimereleased' => 'Butà fòra ël',
'exif-originaltransmissionref' => 'Còdes ëd locassion ëd la trasmission original',
'exif-identifier' => 'Identificator',
'exif-lens' => 'Lent dovrà',
'exif-serialnumber' => 'Nùmer serial ëd la màchina fotogràfica',
'exif-cameraownername' => 'Propietari ëd la màchina fotogràfica',
'exif-label' => 'Tichëtta',
'exif-datetimemetadata' => "Quand ij metadat a son stàit modificà l'ùltima vira",
'exif-nickname' => 'Nòm anformal ëd la figura',
'exif-rating' => 'Vot (su 5)',
'exif-rightscertificate' => 'Sertificà ëd gestion dij drit',
'exif-copyrighted' => "Stat dël drit d'autor",
'exif-copyrightowner' => "Titolar dël drit d'autor",
'exif-usageterms' => "Condission d'utilisassion",
'exif-webstatement' => "Diciarassion ëd drit d'autor an linia",
'exif-originaldocumentid' => 'Identificativ ùnich dël papé original',
'exif-licenseurl' => "Anliura ëd la licensa dij drit d'autor",
'exif-morepermissionsurl' => 'Anformassion an sle license alternative',
'exif-attributionurl' => "An dovrand n'àutra vira cost travaj, për piasì ch'a-j buta l'anliura a",
'exif-preferredattributionname' => "Quand as deuvra torna cost travaj, për piasì dé l'arconossiment a",
'exif-pngfilecomment' => "Coment ëd l'archivi PNG",
'exif-disclaimer' => 'Avis',
'exif-contentwarning' => 'Avis an sël contnù',
'exif-giffilecomment' => "Coment ëd l'archivi GIF",
'exif-intellectualgenre' => "Sòrt d'element",
'exif-subjectnewscode' => 'Còdes dël soget',
'exif-scenecode' => 'Còdes ëd sena IPTC',
'exif-event' => 'Event fotografà',
'exif-organisationinimage' => 'Organisassion fotografà',
'exif-personinimage' => 'Përson-a fotografà',
'exif-originalimageheight' => "Autëssa dla figura prima ch'a fussa ritajà",
'exif-originalimagewidth' => "Larghëssa dla figura prima ch'a fussa ritajà",

# Exif attributes
'exif-compression-1' => 'Nen comprimù',
'exif-compression-2' => "CCITT Partìa 3 longheur dla codìfica d'esecussion dla codìfica Huffman modificà ëd dimension 1",
'exif-compression-3' => 'CCITT Partìa 3 codìfica dël fax',
'exif-compression-4' => 'CCITT Partìa 4 codìfica dël fax',

'exif-copyrighted-true' => "Con drit d'autor",
'exif-copyrighted-false' => "Stat dij drit d'autor nen definì",

'exif-unknowndate' => 'Data nen conossùa',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'A specc',
'exif-orientation-3' => 'Arvirà ëd 180°',
'exif-orientation-4' => 'Arvirà dzor-sota',
'exif-orientation-5' => 'Arvirà dzor-sota e ëd 90° contramostra',
'exif-orientation-6' => 'Arvirà ëd 90° contramostra',
'exif-orientation-7' => 'Arvirà dzor-sota e ëd 90° ant ël sens dla mostra',
'exif-orientation-8' => 'Arvirà ëd 90° ant ël sens dla mostra',

'exif-planarconfiguration-1' => 'dàit a blòch',
'exif-planarconfiguration-2' => 'an planar',

'exif-xyresolution-i' => '$1 pont për pòles (dpi)',
'exif-xyresolution-c' => '$1 pont për centim (dpc)',

'exif-colorspace-65535' => 'Nen calibrà',

'exif-componentsconfiguration-0' => 'a esist pa',

'exif-exposureprogram-0' => 'Nen definì',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Priorità ëd temp',
'exif-exposureprogram-4' => 'Priorità ëd diaframa',
'exif-exposureprogram-5' => "Programa creativ (coregiù për avèj pì ëd profondità 'd camp)",
'exif-exposureprogram-6' => "Programa d'assion (coregiù për avèj ël temp pì curt che as peul)",
'exif-exposureprogram-7' => 'Programa ritrat (për fotografìe pijàite da davzin, con lë sfond fòra feu)',
'exif-exposureprogram-8' => 'Panorama (sogèt lontan e con lë sfond a feu)',

'exif-subjectdistance-value' => '$1 méter',

'exif-meteringmode-0' => 'Dësconossù',
'exif-meteringmode-1' => 'Media',
'exif-meteringmode-2' => 'Media centrà',
'exif-meteringmode-3' => 'Quadrèt',
'exif-meteringmode-4' => 'Vàire quadrèt',
'exif-meteringmode-5' => 'Schema',
'exif-meteringmode-6' => 'Parsial',
'exif-meteringmode-255' => "n'àutr",

'exif-lightsource-0' => 'Dësconossùa',
'exif-lightsource-1' => 'Lus dël dì',
'exif-lightsource-2' => 'Fluoressenta',
'exif-lightsource-3' => 'Lus al tungsten (a incandessensa)',
'exif-lightsource-4' => 'Lòsna',
'exif-lightsource-9' => 'Temp bel',
'exif-lightsource-10' => 'Temp nìvol',
'exif-lightsource-11' => 'Ombra',
'exif-lightsource-12' => 'Fluoressensa tipo lus dël dì (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluoressensa bianca për ël dì (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluoressensa bianca frèida (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fluoressensa bianca (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Lus stàndard sòrt A',
'exif-lightsource-18' => 'Lus stàndard sòrt B',
'exif-lightsource-19' => 'Lus stàndard sòrt C',
'exif-lightsource-20' => 'Anluminant D55',
'exif-lightsource-21' => 'Anluminant D65',
'exif-lightsource-22' => 'Anluminant D75',
'exif-lightsource-23' => 'Anluminant D50',
'exif-lightsource-24' => 'Làmpada da studi ISO al tungsten',
'exif-lightsource-255' => "Àutra sorgiss d'anluminassion",

# Flash modes
'exif-flash-fired-0' => "La lòsna a l'é nen ëscatà",
'exif-flash-fired-1' => "La lòsna a l'ha scatà",
'exif-flash-return-0' => "gnun ëstroboscòpi a dà andaré na fonsion d'artrovament",
'exif-flash-return-2' => "lë stoboscòpi a arleva gnun-a lus d'artorn",
'exif-flash-return-3' => "lë stroboscòpi a l'ha arlevà n'artorn ëd lus",
'exif-flash-mode-1' => 'lus dla lòsna obligatòria',
'exif-flash-mode-2' => 'eliminassion dla lòsna obligatòria',
'exif-flash-mode-3' => 'manera automàtica',
'exif-flash-function-1' => 'Gnun-a fonsion ëd lòsna',
'exif-flash-redeye-1' => "Manera ëd ridussion ëd j'euj ross",

'exif-focalplaneresolutionunit-2' => 'pòles anglèis',

'exif-sensingmethod-1' => 'Nen definì',
'exif-sensingmethod-2' => 'Sensor dlë spassi color a 1 processor',
'exif-sensingmethod-3' => 'Sensor dlë spassi color a 2 processor',
'exif-sensingmethod-4' => 'Sensor dlë spassi color a 3 processor',
'exif-sensingmethod-5' => 'Sensor sequensial dlë spassi color',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear ëd color sequensiaj',

'exif-filesource-3' => 'Màchina fotogràfica digital',

'exif-scenetype-1' => 'Fotografìa an diret',

'exif-customrendered-0' => 'Process normal',
'exif-customrendered-1' => 'Process particular',

'exif-exposuremode-0' => 'Esposission automàtica',
'exif-exposuremode-1' => 'Esposission manual',
'exif-exposuremode-2' => 'Forciolin-a automàtica',

'exif-whitebalance-0' => "Balansa dël bianch n'automàtich",
'exif-whitebalance-1' => 'Balansa dël bianch an manual',

'exif-scenecapturetype-0' => 'Stàndard',
'exif-scenecapturetype-1' => 'Paisagi',
'exif-scenecapturetype-2' => 'Ritrat',
'exif-scenecapturetype-3' => 'La neuit',

'exif-gaincontrol-0' => 'Gnun',
'exif-gaincontrol-1' => 'Sparé ij contrast bass',
'exif-gaincontrol-2' => 'Sparé ij contrast fòrt',
'exif-gaincontrol-3' => 'Sbassé ij contrast bass',
'exif-gaincontrol-4' => 'Sbassé ij contrast fòrt',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Doss',
'exif-contrast-2' => 'contrastà fòrt',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturassion bassa',
'exif-saturation-2' => 'Saturassion àuta',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'dossa',
'exif-sharpness-2' => 'contrastà',

'exif-subjectdistancerange-0' => 'Dësconossùa',
'exif-subjectdistancerange-1' => 'Motobin davzin',
'exif-subjectdistancerange-2' => 'Prim pian',
'exif-subjectdistancerange-3' => 'Anquadratura a soget lontan',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitùdin setentrional',
'exif-gpslatitude-s' => 'Latitùdin meridional',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitùdin oriental',
'exif-gpslongitude-w' => 'Longitùdin ossidental',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|méter|méter}} an sël livel dël mar',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|méter|méter}} sota ël livel dël mar',

'exif-gpsstatus-a' => 'Amzura antramentr che as fa',
'exif-gpsstatus-v' => "Interoperabilità dl'amzura",

'exif-gpsmeasuremode-2' => 'amzura bidimensional',
'exif-gpsmeasuremode-3' => 'amzura tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Km/h',
'exif-gpsspeed-m' => 'mija/h',
'exif-gpsspeed-n' => 'Grop',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Chilòmeter',
'exif-gpsdestdistance-m' => 'Mija',
'exif-gpsdestdistance-n' => 'Mija marin-e',

'exif-gpsdop-excellent' => 'Ecelent ($1)',
'exif-gpsdop-good' => 'Bon ($1)',
'exif-gpsdop-moderate' => 'Moderà ($1)',
'exif-gpsdop-fair' => 'Discret ($1)',
'exif-gpsdop-poor' => 'Scadent ($1)',

'exif-objectcycle-a' => 'Mach ëd matin',
'exif-objectcycle-p' => 'Mach ëd dòp-mesdì',
'exif-objectcycle-b' => 'Sia matin che dòp-mesdì',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Diression vera',
'exif-gpsdirection-m' => 'Diression magnética',

'exif-ycbcrpositioning-1' => 'Sentrà',
'exif-ycbcrpositioning-2' => 'Postà ansema',

'exif-dc-contributor' => 'Contributor',
'exif-dc-coverage' => 'Camp spassial o temporal dël mojen',
'exif-dc-date' => 'Data(e)',
'exif-dc-publisher' => 'Editor',
'exif-dc-relation' => 'Mojen relativ',
'exif-dc-rights' => 'Drit',
'exif-dc-source' => 'Mojen sorgiss',
'exif-dc-type' => 'Sòrt ëd mojen',

'exif-rating-rejected' => 'Arfudà',

'exif-isospeedratings-overflow' => 'Pi gròss ëd 65535',

'exif-iimcategory-ace' => 'Art, cultura e spetàcol',
'exif-iimcategory-clj' => 'Sassin e lej',
'exif-iimcategory-dis' => 'Disastr e assident',
'exif-iimcategory-fin' => 'Economìa e afé',
'exif-iimcategory-edu' => 'Educassion',
'exif-iimcategory-evn' => 'Ambient',
'exif-iimcategory-hth' => 'Salute',
'exif-iimcategory-hum' => 'Anteressi uman',
'exif-iimcategory-lab' => 'Travaj',
'exif-iimcategory-lif' => 'Stil ëd vita e temp lìber',
'exif-iimcategory-pol' => 'Polìtica',
'exif-iimcategory-rel' => 'Religion e chërdense',
'exif-iimcategory-sci' => 'Siensa e tecnologìa',
'exif-iimcategory-soi' => 'Chestion sociaj',
'exif-iimcategory-spo' => 'Spòrt',
'exif-iimcategory-war' => 'Guèra, conflit e batibeuj',
'exif-iimcategory-wea' => 'Temp',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => 'Bassa ($1)',
'exif-urgency-high' => 'Àuta ($1)',
'exif-urgency-other' => "Priorità definìa da l'utent ($1)",

# External editor support
'edit-externally' => "Modifiché st'archivi con un programa estern",
'edit-externally-help' => "(Lese [//www.mediawiki.org/wiki/Manual:External_editors j'anstrussion d'anstalassion] për avèj pì d'anformassion)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tute',
'namespacesall' => 'tùit',
'monthsall' => 'tuti',
'limitall' => 'tùit',

# Email address confirmation
'confirmemail' => "Confermé l'adrëssa postal",
'confirmemail_noemail' => "A l'ha pa butà gnun-a adrëssa vàlida ëd pòsta eletrònica ant ij [[Special:Preferences|sò gust]].",
'confirmemail_text' => "Costa wiki a ciama che chiel a convàlida n'adrëssa ëd pòsta eletrònica anans che
dovré lòn che a toca la pòsta. Che a sgnaca ël boton ambelessì-sota
për fesse mandé un mëssage ëd conferma a soa adrëssa eletrònica.
Andrinta al messagi a-i sara n'anliura con andrinta un còdes.
Che a deurba st'anliura andrinta a sò programa ëd navigassion
për confermé che soa adrëssa a l'é pròpe cola.",
'confirmemail_pending' => "I l'oma già mandaje sò còdes ëd conferma;
se a l'ha pen-a creasse sò cont, miraco a venta che a speta dontré minute che a-j riva ant la pòsta, nopà che ciamene un neuv.",
'confirmemail_send' => 'Mandé un còdes ëd conferma për pòsta eletrònica',
'confirmemail_sent' => "Ël mëssagi ëd conferma a l'é stàit mandà.",
'confirmemail_oncreate' => "Un còdes ëd conferma a l'é stàit mandà a soa adrëssa ëd pòsta eletrònica.
D'ës còdes a fa pa dë manca për rintré ant ël sistema, ma a ventrà che a lo mostra al sistema për podèj abilité cole funsion dla wiki che a son basà ant sla pòsta eletrònica.",
'confirmemail_sendfailed' => "{{SITENAME}} a l'ha pa podù mandeje ël mëssagi ëd conferma.
Che a controla l'adrëssa che a l'ha dane, mai che a-i fusso dij caràter nen vàlid.

Ël programa ëd pòsta a l'ha rëspondù: $1",
'confirmemail_invalid' => 'Còdes ëd conferma nen vàlid. A podrìa ëdcò mach esse scadù.',
'confirmemail_needlogin' => 'A venta $1 për confermé soa adrëssa ëd pòsta eletrònica.',
'confirmemail_success' => "Soa adrëssa a l'é stàita confermà, adess a peul [[Special:UserLogin|rintré ant ël sistema]] e i-j auguroma da fessla bin ant la wiki!",
'confirmemail_loggedin' => "Motobin mersì. Soa adrëssa ëd pòsta eletrònica adess a l'é confermà.",
'confirmemail_error' => "Cheicòs a l'é andà mal ën salvand soa conferma.",
'confirmemail_subject' => "Conferma dl'adrëssa postal da 'nt la {{SITENAME}}",
'confirmemail_body' => "Cheidun, a l'é belfé che a sia stàit pròpe chiel, da 'nt l'adrëssa IP $1,
a l'ha duvertà un cont utent «$2» ansima a {{SITENAME}}, lassand-ne st'adrëssa ëd pòsta eletrònica-sì.

Për confermé che ës cont a l'é da bon sò e për ativé
le possibilità gropà a la pòsta eletrònica ansima a {{SITENAME}}, che a deurba st'adrëssa-sì andrinta a sò programa ëd navigassion:

$3

Se a fussa *nen* stàit chiel a deurbe ël cont, anlora che a vada dapress a la liura sì-sota
për scancelé la conferma ëd l'adrëssa ëd pòsta eletrònica:

$5

Cost còdes ëd conferma a l'é bon fin-a al $4.",
'confirmemail_body_changed' => "Cheidun, a l'é belfé ch'a sia chiel, da l'adrëssa IP $1,
a l'ha cangià l'adrëssa ëd pòsta eletrònica dël cont «$2» con st'adrëssa-sì dzora a {{SITENAME}}.

Për confirmé che sto cont-sì a l'é pròpi sò e për riativé
le fonsion ëd pòsta eletrònica dzora a {{SITENAME}}, ch'a deurba costa liura-sì an sò navigador:

$3

Se ël cont a l'é *nen* sò, ch'a-i vada dapress a costa liura-sì
për scancelé la conferma dl'adrëssa ëd pòsta eletrònica:

$5

Ës còdes ëd conferma a scadrà ël $4.",
'confirmemail_body_set' => "Quaidun, miraco chiel, da l'adrëssa IP $1,
a l'ha ampostà l'adrëssa ëd pòsta eletrònica dël cont «$2» a costa adrëssa su {{SITENAME}}.

Për confirmé che sto cont a l'é pròpi sò e ativé le funsion ëd pòsta eletrònica su {{SITENAME}}, ch'a duverta cost'anliura an sò navigador:

$3

Se ël cont a l'é *pa* sò, ch'a-j vada dapress a st'anliura për anulé la conferma ëd l'adrëssa ëd pòsta eletrònica:

$5

Cost còdes ëd conferma a scad ai $4.",
'confirmemail_invalidated' => "Conferma ëd l'adrëssa ëd pòsta eletrònica anulà",
'invalidateemail' => "Anulé la conferma dl'adrëssa ëd pòsta eletrònica",

# Scary transclusion
'scarytranscludedisabled' => "[L'inclusion ëd pàgine antra wiki diferente a l'é nen abilità]",
'scarytranscludefailed' => "[Darmagi, ma lë stamp $1 a l'é pa podusse carié]",
'scarytranscludefailed-httpstatus' => '[Letura dlë stamp falìa për $1: HTTP $2]',
'scarytranscludetoolong' => "[L'adrëssa dl'aragnà a l'é tròp longa]",

# Delete conflict
'deletedwhileediting' => "'''Avertensa''': sta pàgina-sì a l'é stàita scancelà quand che chiel a l'avìa già anandiasse a modifichela!",
'confirmrecreate' => "L'utent [[User:$1|$1]] ([[User talk:$1|talk]]) a l'ha scancelà st'articol-sì quand che chiel a l'avia già anandiasse a modifichelo, dasend coma motiv ëd lë scancelament:
''$2''
Për piasì, che an conferma che da bon a veul torna creélo.",
'confirmrecreate-noreason' => "L'utent [[User:$1|$1]] ([[User talk:$1|ciaciarade]]) a l'ha scancelà sta pàgina apress che chiel a l'ha ancaminà a modifiché.  Për piasì, ch'a confirma ch'a veul pròpi torna creé sta pàgina.",
'recreate' => 'Creé torna',

# action=purge
'confirm_purge_button' => 'Va bin',
'confirm-purge-top' => 'Dësvujdé la memorisassion dë sta pàgina-sì?',
'confirm-purge-bottom' => 'Spurghé na pàgina a scancela la memorisassion local e a fà comparì la revision pì neuva.',

# action=watch/unwatch
'confirm-watch-button' => 'Va bin',
'confirm-watch-top' => 'Gionté sta pàgina-sì a la lista dle ròbe che as ten-o sot euj?',
'confirm-unwatch-button' => 'Va bin',
'confirm-unwatch-top' => 'Gavé sta pàgina-sì da la lista dle ròbe che as ten-o sot euj?',

# Multipage image navigation
'imgmultipageprev' => '← pàgina andré',
'imgmultipagenext' => 'pàgina anans →',
'imgmultigo' => 'Andé!',
'imgmultigoto' => 'Andé a la pàgina $1',

# Table pager
'ascending_abbrev' => 'a chërse',
'descending_abbrev' => 'a calé',
'table_pager_next' => 'Pàgina anans',
'table_pager_prev' => 'Pàgina andré',
'table_pager_first' => 'Prima pàgina',
'table_pager_last' => 'Ùltima pàgina',
'table_pager_limit' => 'Smon-me $1 archivi për pàgina',
'table_pager_limit_label' => 'Arzultà për pàgina:',
'table_pager_limit_submit' => 'Andé',
'table_pager_empty' => 'Gnun arzultà',

# Auto-summaries
'autosumm-blank' => 'Pàgina dësvujdà',
'autosumm-replace' => "Pàgina cambià con '$1'",
'autoredircomment' => 'Ridiression anvers a [[$1]]',
'autosumm-new' => "Creà la pàgina con '$1'",

# Size units
'size-bytes' => '$1 Byte',
'size-kilobytes' => '$1 KByte',
'size-megabytes' => '$1 MByte',
'size-gigabytes' => '$1 GByte',

# Live preview
'livepreview-loading' => "Antramentr ch'as caria…",
'livepreview-ready' => "Antramentr ch'as caria… Carià.",
'livepreview-failed' => "La preuva dal viv a l'é falìa!
Ch'a preuva an manera sòlita.",
'livepreview-error' => "Conession falìa: $1 «$2».
Ch'a preuva an manera sòlita.",

# Friendlier slave lag warnings
'lag-warn-normal' => 'Le modìfiche pì neuve ëd $1 {{PLURAL:$1|second}} a podrìo nen ess-ie ant sta lista-sì.',
'lag-warn-high' => "Për via che la màchina serventa a tarda a dene 'd rispòste, le modìfiche fàite men che $1 {{PLURAL:$1|second}} fa a podrìo ëdcò nen ess-ie ant sta lista-sì.",

# Watchlist editor
'watchlistedit-numitems' => "A l'é antramentr ch'a ten sot-euj {{PLURAL:$1|1 tìtol|$1 tìtoj}}, nen contand le pàgine ëd discussion.",
'watchlistedit-noitems' => "A-i é pa gnun tìtol ch'as ten-a sot-euj.",
'watchlistedit-normal-title' => "Modifiché la lista ëd lòn ch'as ten sot-euj",
'watchlistedit-normal-legend' => "Gavé via ij tìtoj da 'nt la lista ëd lòn ch'as ten sot-euj",
'watchlistedit-normal-explain' => "Ij tìtoj ch'a ten sot-euj a son ësmonù ambelessì-sota.
Për gavene via un, ch'a-j fasa la crosëtta ant la casela ch'a l'ha aranda, e peuj ch'ai bata ansima a «{{int:Watchlistedit-normal-submit}}». As peul ëdcò [[Special:EditWatchlist/raw|modifiché la lista ampressa]].",
'watchlistedit-normal-submit' => 'Gavé via ij tìtoj',
'watchlistedit-normal-done' => "{{PLURAL:$1|Un tìtol a l'é|$1 tìtoj a son}} ëstàit gavà via da 'nt la lista ëd lòn ch'as ten sot-euj:",
'watchlistedit-raw-title' => "Modifiché ampressa la lista ëd lòn ch'as ten sot-euj",
'watchlistedit-raw-legend' => "Modìfica lesta ëd la lista ëd lòn ch'as ten sot-euj",
'watchlistedit-raw-explain' => "Ij tìtoj ch'a l'é antramentr ch'as ten sot-euj a son ambelessì-sota, e a peulo modifichesse ën giontand-ne e gavand-ne via da 'nt la lista; un tìtol për riga.
Quand a l'ha finì, ch'a-i bata ansima a «{{int:Watchlistedit-raw-submit}}».
As peul ëdcò [[Special:EditWatchlist|dovré l'editor sòlit]].",
'watchlistedit-raw-titles' => 'Tìtoj:',
'watchlistedit-raw-submit' => 'Agiorné la Lista',
'watchlistedit-raw-done' => "La lista ëd lòn ch'as ten sot-euj a l'é stàita agiornà.",
'watchlistedit-raw-added' => "{{PLURAL:$1|A l'é|As son}} giontasse {{PLURAL:$1|1 tìtol|$1 tìtoj}}:",
'watchlistedit-raw-removed' => "{{PLURAL:$1|A l'é|As son}} gavasse via {{PLURAL:$1|1 tìtol|$1 tìtoj}}:",

# Watchlist editing tools
'watchlisttools-view' => 'S-ciairé le modifiché amportante',
'watchlisttools-edit' => "Vardé e modifiché la lista ëd lòn ch'as ten sot-euj",
'watchlisttools-raw' => "Modifiché ampressa la lista ëd lòn ch'as ten sot-euj",

# Iranian month names
'iranian-calendar-m1' => 'Prim mèis Jalāli',
'iranian-calendar-m2' => 'Scond mèis Jalāli',
'iranian-calendar-m3' => 'Tèrs mèis Jalāli',
'iranian-calendar-m4' => 'Quart mèis Jalāli',
'iranian-calendar-m5' => 'Quint mèis Jalāli',
'iranian-calendar-m6' => "Mèis Jalāli ch'a fa ses",
'iranian-calendar-m7' => "Mèis Jalāli ch'a fa set",
'iranian-calendar-m8' => "Mèis Jalāli ch'a fa eut",
'iranian-calendar-m9' => "Mèis Jalāli ch'a fa neuv",
'iranian-calendar-m10' => "Mèis Jalāli ch'a fa des",
'iranian-calendar-m11' => "Mèis Jalāli ch'a fa óndes",
'iranian-calendar-m12' => "Meis Jalāli ch'a fa dódes",

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|ciaciarade]])',

# Core parser functions
'unknown_extension_tag' => "Tichëtta d'estension «$1» pa conossùa",
'duplicate-defaultsort' => "'''Atension:''' La ciav d'ordinament ëstàndard «$2» a pija ël pòst ëd cola ëd prima «$1».",

# Special:Version
'version' => 'Version',
'version-extensions' => 'Estension anstalà',
'version-specialpages' => 'Pàgine speciaj',
'version-parserhooks' => 'Gancio dël dëscompositor',
'version-variables' => 'Variàbij',
'version-antispam' => 'Prevension dla rumenta',
'version-skins' => 'Pej',
'version-other' => 'Àutr',
'version-mediahandlers' => 'Gestor multimojen',
'version-hooks' => 'Gancio',
'version-parser-extensiontags' => "Tichëtte dj'estension conossùe dal dëscompositor",
'version-parser-function-hooks' => 'Gancio për le fonsion dël dëscompositor',
'version-hook-name' => 'Nòm dël gancio',
'version-hook-subscribedby' => 'A son scrivusse',
'version-version' => '(Version $1)',
'version-license' => 'Licensa',
'version-poweredby-credits' => "Costa wiki-sì a marcia mersì a '''[//www.mediawiki.org/ MediaWiki]''', licensa © 2001-$1 $2.",
'version-poweredby-others' => 'àutri',
'version-poweredby-translators' => 'tradutor ëd translatewiki.net',
'version-credits-summary' => 'I tnoma a aringrassié le përson-e sì-dapress për soa contribussion a [[Special:Version|MediaWiki]].',
'version-license-info' => "MediaWiki a l'é un programa lìber; a peul passelo an gir o modifichelo sota le condission dla Licensa Pùblica General GNU coma publicà da la Free Software Foundation; o la version 2 dla licensa o (a soa decision) qualsëssìa version apress.

MediaWiki a l'é distribuì ant la speransa che a sia ùtil, ma SENSA GNUN-A GARANSÌA; sensa gnanca la garansìa implìcita ëd COMERSIABILITA' o d'ADATAMENT A UN BUT PARTICOLAR. Ch'a lesa la Licensa General Pùblica GNU per pi 'd detaj.

A dovrìa avèj arseivù [{{SERVER}}{{SCRIPTPATH}}/COPYING na còpia dla Licensa Pùblica General GNU] ansema a sto programa-sì; dësnò, ch'a scriva a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o [//www.gnu.org/licenses/old-licenses/gpl-2.0.html ch'a la lesa an linia].",
'version-software' => 'Programa anstalà',
'version-software-product' => 'Prodot',
'version-software-version' => 'Version',
'version-entrypoints' => "Anliure dij pont d'intrada",
'version-entrypoints-header-entrypoint' => "Pont d'intrada",
'version-entrypoints-header-url' => "Adrëssa an sl'aragnà",
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath Senté d\'artìcol]',

# Special:Redirect
'redirect' => 'Ridirigiù da archivi, utent o ID ëd revision',
'redirect-legend' => "Ridirige a n'archivi o na pàgina",
'redirect-summary' => "Costa pàgina special a ponta a n'archivi (dàit un nòm d'archivi), na pàgina (dàita n'ID a la revision) o na pàgina d'utent (dàit n'identificativ numérich a l'utent).",
'redirect-submit' => 'Andé',
'redirect-lookup' => 'Arserca:',
'redirect-value' => 'Valor:',
'redirect-user' => "ID dl'utent",
'redirect-revision' => 'Revision ëd la pàgina',
'redirect-file' => "Nòm ëd l'archivi",
'redirect-not-exists' => 'Valor nen trovà',

# Special:FileDuplicateSearch
'fileduplicatesearch' => "Arsërca dj'archivi dobi",
'fileduplicatesearch-summary' => "Arsërca dj'archivi dobi a parte dal valor d'ordinament.",
'fileduplicatesearch-legend' => 'Arsërca ëd na dobia',
'fileduplicatesearch-filename' => "Nòm dl'archivi:",
'fileduplicatesearch-submit' => 'Arsërca',
'fileduplicatesearch-info' => '$1 × $2 pontin<br />Amzure: $3<br />Sòrt MIME: $4',
'fileduplicatesearch-result-1' => "Pa gnun dobion për l'archivi «$1».",
'fileduplicatesearch-result-n' => "A-i {{PLURAL:$2|é 'n dobion midem|son $2 dobion midem}} ëd l'archivi «$1».",
'fileduplicatesearch-noresults' => "Gnun archivi ciamà «$1» a l'é stàit trovà.",

# Special:SpecialPages
'specialpages' => 'Pàgine Speciaj',
'specialpages-note' => '----
* Pàgine speciaj normaj.
* <span class="mw-specialpagerestricted">Pàgine speciaj riservà.</span>
* <span class="mw-specialpagecached">Pàgine speciaj mach an memòria local (a peulo esse veje).</span>',
'specialpages-group-maintenance' => 'Rapòrt ëd manutension',
'specialpages-group-other' => 'Àutre pàgine speciaj',
'specialpages-group-login' => 'Intré ant ël sistema / creé un cont',
'specialpages-group-changes' => 'Ùltime modìfiche e registr',
'specialpages-group-media' => "Rapòrt e amportassion d'archivi multimojen",
'specialpages-group-users' => 'Utent e drit',
'specialpages-group-highuse' => 'Pàgine motobin dovrà',
'specialpages-group-pages' => 'Liste ëd pàgine',
'specialpages-group-pagetools' => 'Utiss për le pàgine',
'specialpages-group-wiki' => 'Dat e utiss',
'specialpages-group-redirects' => 'Pàgine speciaj ëd ridiression',
'specialpages-group-spam' => 'Utiss contra la rumenta',

# Special:BlankPage
'blankpage' => 'Pàgina bianca',
'intentionallyblankpage' => "Costa pàgina a l'é lassà veuida a pòsta.",

# External image whitelist
'external_image_whitelist' => "  #Lassé costa riga-sì pròpi 'me ch'a l'é<pre>
#Buté ij fragment d'espression regolar (mach la part che a va antra le //) sì-sota
#Coste-sì a saran confrontà con le liure dle figure esterne
#Cole che as cobio a saran visualisà com figure, dësnò a sarà mach mostrà na liura a la figura
#Le linie che a ancamin-o con # a saran tratà com coment
#La lista a l'é indiferenta a minùscol o majùscol

#Buté tùit ij fragment d'espression regolar sota sta linia-sì. Lassé costa linia pròpi com a l'é</pre>",

# Special:Tags
'tags' => 'Tichëtte ëd le modìfiche vàlide',
'tag-filter' => 'Filtror ëd le [[Special:Tags|tichëtte]]:',
'tag-filter-submit' => 'Filtror',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Tichëtta|Tichëtte}}]]: $2)',
'tags-title' => 'Tichëtte',
'tags-intro' => 'Costa pàgina a lista le tichëtte che ël programa a peul dovré për marché na modìfica, e sò significà.',
'tags-tag' => 'Nòm ëd la tichëtta',
'tags-display-header' => 'Aparensa ant la lista dle modìfiche',
'tags-description-header' => 'Descrission completa dël significà',
'tags-active-header' => 'Ativ?',
'tags-hitcount-header' => 'Modìfiche con tichëtta',
'tags-active-yes' => 'Bò',
'tags-active-no' => 'Nò',
'tags-edit' => 'modifiché',
'tags-hitcount' => '$1 {{PLURAL:$1|cambiament|cambiament}}',

# Special:ComparePages
'comparepages' => 'Confronté dle pàgine',
'compare-selector' => 'Confronté le revision dle pàgine',
'compare-page1' => 'Pàgina 1',
'compare-page2' => 'Pàgina 2',
'compare-rev1' => 'Revision 1',
'compare-rev2' => 'Revision 2',
'compare-submit' => 'Confronté',
'compare-invalid-title' => "Ël tìtol ch'a l'ha spessificà a va pa bin.",
'compare-title-not-exists' => "Ël tìtol ch'a l'ha spessificà a esist pa.",
'compare-revision-not-exists' => "La revision che a l'ha spessificà a esist pa.",

# Database error messages
'dberr-header' => "Sta wiki-sì a l'ha un problema",
'dberr-problems' => "An dëspias! Ës sit a l'ha dle dificoltà técniche.",
'dberr-again' => "Ch'a speta chèiche minute e ch'a preuva torna a carié.",
'dberr-info' => '(Conession al servent ëd base ëd dàit impossìbil: $1)',
'dberr-info-hidden' => '(Conession al servent ëd base ëd dàit impossìbil)',
'dberr-usegoogle' => 'Antratant a peul prové a sërché con Google.',
'dberr-outofdate' => "Ch'a ten-a da ment che soe indesassion dij nòstri contnù a podrìo esse nen agiornà.",
'dberr-cachederror' => "Costa-sì a l'é na còpia an memòria local ëd la pàgina ciamà, e a peul esse nen agiornà.",

# HTML forms
'htmlform-invalid-input' => "A-i son dij problema con cheidun dij valor ch'a l'ha butà",
'htmlform-select-badoption' => "Ël valor che a l'ha spessificà a l'é pa n'opsion vàlida.",
'htmlform-int-invalid' => "Ël valor ch'a l'ha spessificà a l'é pa n'antregh.",
'htmlform-float-invalid' => "Ël valor ch'a l'ha spessificà a l'é pa un nùmer.",
'htmlform-int-toolow' => "Ël valor ch'a l'ha spessificà a l'é sota al mìnim ëd $1.",
'htmlform-int-toohigh' => "Ël valor ch'a l'ha spessificà a l'é dzora al màssim ëd $1.",
'htmlform-required' => 'A-i é dabzògn ëd cost valor',
'htmlform-submit' => 'Mandé',
'htmlform-reset' => 'Gavé le modìfiche',
'htmlform-selectorother-other' => 'Àutr',
'htmlform-no' => 'Nò',
'htmlform-yes' => 'É',
'htmlform-chosen-placeholder' => "Serne n'opsion",

# SQLite database support
'sqlite-has-fts' => '$1 con arserca an test pien mantnùa',
'sqlite-no-fts' => '$1 sensa arserca an test pien mantnùa',

# New logging system
'logentry-delete-delete' => "$1 a l'ha {{GENDER:$2|scancelà}} la pàgina $3",
'logentry-delete-restore' => "$1 {{GENDER:$2|a l'ha ripristinà}} la pàgina $3",
'logentry-delete-event' => "$1 {{GENDER:$2|a l'ha modificà}} la visibilità ëd {{PLURAL:$5|n'event dël registr|$5 event dël registr}} dzora $3: $4",
'logentry-delete-revision' => "$1 {{GENDER:$2|a l'ha modificà}} la visibilità ëd {{PLURAL:$5|na revision|$5 revision}} dzora la pàgina $3: $4",
'logentry-delete-event-legacy' => "$1 {{GENDER:$2|a l'ha modificà}} la visibilità dj'eveniment dël registr dzora $3",
'logentry-delete-revision-legacy' => "$1 {{GENDER:$2|a l'ha modificà}} la visibilità dle revision dzora la pàgina $3",
'logentry-suppress-delete' => "$1 {{GENDER:$2|a l'ha eliminà}} la pàgina $3",
'logentry-suppress-event' => "$1 {{GENDER:$2|a l'ha modificà}} da stërmà la visibilità ëd {{PLURAL:$5|n'eveniment dël registr|$5 eveniment dël registr}} dzora $3: $4",
'logentry-suppress-revision' => "$1 {{GENDER:$2|a l'ha modificà}} da stërmà la visibilità ëd {{PLURAL:$5|na revision|$5 revision}} dzora la pàgina $3: $4",
'logentry-suppress-event-legacy' => "$1 {{GENDER:$2|a l'ha modificà}} da stërmà la visibilità dj'eveniment dël registr dzora $3",
'logentry-suppress-revision-legacy' => "$1 {{GENDER:$2|a l'ha modificà}} da stërmà la visibilità dle revision dzora la pàgina $3",
'revdelete-content-hid' => 'contnù stërmà',
'revdelete-summary-hid' => 'resumé dle modìfiche stërmà',
'revdelete-uname-hid' => 'stranòm stërmà',
'revdelete-content-unhid' => 'contnù dëscoatà',
'revdelete-summary-unhid' => 'resumé dle modìfiche dëscoatà',
'revdelete-uname-unhid' => 'stranòm dëscoatà',
'revdelete-restricted' => "restrission aplicà a j'aministrator",
'revdelete-unrestricted' => "restrission për j'aministrator gavà",
'logentry-move-move' => "$1 {{GENDER:$2|a l'ha tramudà}} la pàgina $3 a $4",
'logentry-move-move-noredirect' => "$1 {{GENDER:$2|a l'ha tramudà}} la pàgina $3 a $4 sensa lassé na ridiression",
'logentry-move-move_redir' => "$1 {{GENDER:$2|a l'ha tramudà}} la pàgina $3 a $4 ansima a na ridiression",
'logentry-move-move_redir-noredirect' => "$1 {{GENDER:$2|a l'ha tramudà}} la pàgina $3 a $4 ansima a na ridiression sensa lassé na ridiression",
'logentry-patrol-patrol' => "$1 {{GENDER:$2|a l'ha marcà}} la revision $4 dla pàgina $3 'me controlà",
'logentry-patrol-patrol-auto' => "$1 {{GENDER:$2|a l'ha marcà}} an automàtich la revision $4 dla pàgina $3 'me controlà",
'logentry-newusers-newusers' => "Ël cont utent $1 {{GENDER:$2|a l'é stàit creà}}",
'logentry-newusers-create' => "Ël cont utent $1 {{GENDER:$2|a l'é stàit creà}}",
'logentry-newusers-create2' => "Ël cont utent $3 {{GENDER:$2|a l'é stàit creà}} da $1",
'logentry-newusers-byemail' => "Ël cont utent $3 a l'é {{GENDER:$2|stàit creà}} da $1 e la ciav a l'é stàita mandà për pòsta eletrònica",
'logentry-newusers-autocreate' => "Ël cont $1 a l'é {{GENDER:$2|stàit creà}} an automàtich",
'logentry-rights-rights' => "$1 {{GENDER:$2|a l'ha modificà}} l'apartenensa a la partìa për $3 da $4 a $5",
'logentry-rights-rights-legacy' => "$1 {{GENDER:$2|a l'ha modificà}} l'apartenensa a la partìa për $3",
'logentry-rights-autopromote' => "$1 a l'é {{GENDER:$2|stàit promovù}} an automàtich da $4 a $5",
'rightsnone' => '(gnun)',

# Feedback
'feedback-bugornote' => "S'a l'é pront a descrive un problema técnich an detaj, për piasì ch'a [$1 signala un bigat]. 
Dësnò, a peul dovré ël formolari semplificà sì-sota. Sò coment a sarà giontà a la pàgina «[$3 $2]», con sò stranòm.",
'feedback-subject' => 'Soget:',
'feedback-message' => 'Mëssagi:',
'feedback-cancel' => 'Anulé',
'feedback-submit' => 'Spedì ij coment',
'feedback-adding' => 'Gionta dij coment a la pàgina...',
'feedback-error1' => "Eror: Arzultà ëd l'API nen arconossù",
'feedback-error2' => 'Eror: Modìfica falìa',
'feedback-error3' => "Eror: gnun-e rispòste da l'API",
'feedback-thanks' => 'Mersì! Sò coment a l\'é stàit publicà an sla pàgina "[$2 $1]".',
'feedback-close' => 'Fàit',
'feedback-bugcheck' => "Bin fàit! Ch'a contròla mach ch'a sia pa già un dij [$1 bigat conossù].",
'feedback-bugnew' => "I l'heu controlà. Signalé n'eror neuv.",

# Search suggestions
'searchsuggest-search' => 'Arserché',
'searchsuggest-containing' => 'contenent ...',

# API errors
'api-error-badaccess-groups' => "Chiel a peul pa carié d'archivi su costa wiki.",
'api-error-badtoken' => 'Eror antern: sìmbol pa bon.',
'api-error-copyuploaddisabled' => 'Le carie a travers ëd liure a son disabilità ansima a cost servent.',
'api-error-duplicate' => "A-i {{PLURAL:$1|é [$2 n'àutr archivi]|son [$2 àutri archivi]}} già an sël sit col ël midem contnù.",
'api-error-duplicate-archive' => "A-i {{PLURAL:$1|era [$2 n'àutr archivi]|ero [$2 àutri archivi]}} già an sël sit con ël midem contnù, ma {{PLURAL:$1|a l'é stàit|a son ëstàit}} ëscancelà.",
'api-error-duplicate-archive-popup-title' => "Dupliché {{PLURAL:$1|l'archivi|j'archivi}} ch'a son già stàit ëscancelà",
'api-error-duplicate-popup-title' => "Dupliché {{PLURAL:$1|l'archivi|j'archivi}}",
'api-error-empty-file' => "L'archivi ch'a l'ha mandà a l'era veuid.",
'api-error-emptypage' => "La creassion ëd pàgine neuve veujde a l'é nen përmëttùa.",
'api-error-fetchfileerror' => "Eror antern: quaicòs a l'é andàit mal antramentre ch'as arcuperava l'archivi.",
'api-error-fileexists-forbidden' => "N'archivi con nòm «$1» a esist già, e a peul pa esse dzorascrivù.",
'api-error-fileexists-shared-forbidden' => "N'archivi con nòm «$1» a esist già ant ël depòsit condivis ëd j'archivi, e a peul pa esse dzorascrivù.",
'api-error-file-too-large' => "L'archivi ch'a l'ha mandà a l'era tròp gròss.",
'api-error-filename-tooshort' => "Ël nòm ëd l'archivi a l'é tròp curt.",
'api-error-filetype-banned' => "Costa sòrt d'archivi a l'é proibìa.",
'api-error-filetype-banned-type' => "$1 {{PLURAL:$4|a l'é na sòrt d'archivi proibìa|a son ëd sòrt d'archivi proibìe}}. {{PLURAL:$3|La sòrt d'archivi consentìa a l'é|Le sòrt d'archivi consentìe a son}} $2.",
'api-error-filetype-missing' => "L'archivi a l'é sensa estension.",
'api-error-hookaborted' => "La modìfica ch'a l'ha provà a fé a l'é stàita blocà dal gancio ëd n'estension.",
'api-error-http' => 'Eror antern: As peul pa coleghesse al servent.',
'api-error-illegal-filename' => "Ël nòm dl'archivi a l'é nen consentì.",
'api-error-internal-error' => "Eror antern: Cheicòs a l'é andàit mal con ël tratament ëd soa amportassion an sla wiki.",
'api-error-invalid-file-key' => 'Eror antern: archivi pa trovà ant la memòria a temp.',
'api-error-missingparam' => "Eror antern: paràmetr mancant ant l'arcesta.",
'api-error-missingresult' => "Eror antern: as peul pa determiné se la còpia a l'é andàita bin.",
'api-error-mustbeloggedin' => "A dev esse intrà ant ël sistema për carié dj'archivi.",
'api-error-mustbeposted' => "Eror antern: L'arcesta a l'ha da manca d'HTTP POST.",
'api-error-noimageinfo' => "Ël cariament a l'é andàit bin, ma ël servent a l'ha dane gnun-e anformassion an sl'archivi.",
'api-error-nomodule' => 'Eror antern: gnun mòdoj ëd caria ampostà.',
'api-error-ok-but-empty' => 'Eror antern: Gnun-a rispòsta dal servent.',
'api-error-overwrite' => "Dzorascrive ansima a n'archivi esistent a l'é nen përmëttù.",
'api-error-stashfailed' => "Eror antern: ël servent a l'ha pa podù memorisé l'archivi a temp.",
'api-error-publishfailed' => "Eror antern: Ël servent a l'ha pa podù publiché l'archivi provisòri.",
'api-error-timeout' => "Ël servent a l'ha pa rëspondù ant ël temp ëspetà.",
'api-error-unclassified' => "A l'é capitaje n'eror nen conossù.",
'api-error-unknown-code' => 'Eror sconossù: «$1».',
'api-error-unknown-error' => "Eror antern: Cheicòs a l'é andàit mal quand a l'é provasse a carié sò archivi.",
'api-error-unknown-warning' => 'Avis pa conossù: $1',
'api-error-unknownerror' => 'Eror sconossù: «$1».',
'api-error-uploaddisabled' => "Ël cariagi a l'é disabilità su sta wiki.",
'api-error-verification-error' => "Cost archivi a peul esse danegià, o avèj l'estension sbalià.",

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|second|second}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuta|minute}}',
'duration-hours' => '$1 {{PLURAL:$1|ora|ore}}',
'duration-days' => '$1 {{PLURAL:$1|di|di}}',
'duration-weeks' => '$1 {{PLURAL:$1|sman-a|sman-e}}',
'duration-years' => '$1 {{PLURAL:$1|ann|agn}}',
'duration-decades' => "$1 {{PLURAL:$1|desen-a d'agn|desen-e d'agn}}",
'duration-centuries' => '$1 {{PLURAL:$1|sécol|sécoj}}',
'duration-millennia' => '$1 {{PLURAL:$1|milenari|milenari}}',

# Image rotation
'rotate-comment' => 'Plancià virà ëd $1 {{PLURAL:$1|gre}} an sens orari',

# Limit report
'limitreport-title' => "Dàit d'otimisassion ëd l'analisator:",
'limitreport-cputime' => "Temp CPU d'utilisassion",
'limitreport-cputime-value' => '$1 {{PLURAL:$1|second}}',
'limitreport-walltime' => "Temp real d'utilisassion",
'limitreport-walltime-value' => '$1 {{PLURAL:$1|second}}',
'limitreport-ppvisitednodes' => 'Cont dij neu ëd prepocessor visità',
'limitreport-ppgeneratednodes' => 'Cont dij neu ëd preprocessor generà',
'limitreport-postexpandincludesize' => "Taja d'anclusion dòp espansion",
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',
'limitreport-templateargumentsize' => "Taja dl'argoment ëd lë stamp",
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',
'limitreport-expansiondepth' => "Pi granda përfondità d'espansion",
'limitreport-expensivefunctioncount' => "Cont ëd le fonsion d'anàlisi care",

);
