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
'tog-underline'               => 'Anliure con la sotliniadura',
'tog-highlightbroken'         => "Buta an evidensa j'anliure che a men-o a<br />
dj'artìcoj ancó pa scrit",
'tog-justify'                 => 'Paràgraf: giustificà',
'tog-hideminor'               => 'Stërma le modìfiche cite<br />ant sla pàgina "Ùltime Modìfiche"',
'tog-hidepatrolled'           => "Stërma le modìfiche verificà ant j'ùltime modìfiche",
'tog-newpageshidepatrolled'   => 'Stërma le pàgine verificà da la lista dle pàgine neuve',
'tog-extendwatchlist'         => 'Slarga la funsion "ten sot euj" an manera che a la smon-a tute le modìfiche, nen mach l\'ùltima',
'tog-usenewrc'                => "Deuvra j'ùltime modìfiche an bela forma (a-i va JavaScript)",
'tog-numberheadings'          => 'Tìtoj ëd paràgraf<br />che as nùmero daspërlor',
'tog-showtoolbar'             => "Mostra la bara dj'utiss (a-i va Javascript)",
'tog-editondblclick'          => "Dobia sgnacà për modifiché l'artìcol<br />(a-i va JavaScript)",
'tog-editsection'             => "Abìlita la modìfica dle session con j'anliure [modìfica]",
'tog-editsectiononrightclick' => 'Abilité la modìfica dle session ën sgnacand-je ansima<br />  al tìtol col tast drit dël rat (a-i va Javascript)',
'tog-showtoc'                 => "Buta le tàole dij contnù<br />(për j'artìcoj che l'han pì che 3 session)",
'tog-rememberpassword'        => "Visesse ëd mia ciav ansima a 's navigador (për al pi $1 {{PLURAL:$1|di|di}})",
'tog-watchcreations'          => 'Gionta le pàgine che i creo mi a la lista ëd lòn che im ten-o sot euj',
'tog-watchdefault'            => "Gionta le pàgine che i modìfico mi a la lista dle ròbe ch'i ten-o sot-euj",
'tog-watchmoves'              => 'Gionta le pàgine che i tramudo a lòn che im ten-o sot euj',
'tog-watchdeletion'           => 'Gionta le pàgine che i scancelo via a la lista ëd lòn che im ten-o sot euj',
'tog-minordefault'            => 'Marca tute le modìfice coma cite<br />(mach coma predefinission dla casela)',
'tog-previewontop'            => 'Smon-e la preuva dzora al quàder ëd modìfica dël test e nen sota',
'tog-previewonfirst'          => 'Smon na preuva la prima vira che as fa na modìfica',
'tog-nocache'                 => 'Disabilité la memòria local ëd le pàgine dël navigador',
'tog-enotifwatchlistpages'    => "Mand-me un messagi an pòsta eletrònica quand a-i son dle modìfiche a le pàgine ch'im ten-o sot euj",
'tog-enotifusertalkpages'     => 'Mand-me un messagi ëd pòsta eletrònica quand a-i son dle modìfiche a mia pàgina dle ciaciarade',
'tog-enotifminoredits'        => 'Mand-me un messagi an pòsta eletrònica bele che për le modìfiche cite',
'tog-enotifrevealaddr'        => 'Lassa che a së s-ciàira mia adrëssa ëd pòsta eletrònica ant ij messagi ëd notìfica',
'tog-shownumberswatching'     => "Smon ël nùmer d'utent che as ten-o la pàgina sot euj",
'tog-oldsig'                  => 'Anteprima dla firma esistenta:',
'tog-fancysig'                => "Trata la firma com test wiki (sensa n'anliura automàtica)",
'tog-externaleditor'          => "Dovré coma stàndard n'editor estern (mach për espert, a-i é dabzògn d'ampostassion speciaj dzora a sò ordinator. [http://www.mediawiki.org/wiki/Manual:External_editors Për savèjne ëd pi.])",
'tog-externaldiff'            => "Dovré për stàndard un programa comparator estern (mach për espert, a-i é dabzògn d'ampostassion speciaj ansima a sò ordinator [http://www.mediawiki.org/wiki/Manual:External_editors Për savèjne ëd pi.])",
'tog-showjumplinks'           => 'Dovré j\'anliure d\'acessibilità dla sòrt "Va a"',
'tog-uselivepreview'          => "Dovré la fonsion ''Preuva dal viv'' (a-i va JavaScript e a l'é mach sperimental)",
'tog-forceeditsummary'        => "Ciama conferma se ël somari dla modìfica a l'é veujd",
'tog-watchlisthideown'        => 'Stërma mie modìfiche ant la ròba che im ten-o sot euj',
'tog-watchlisthidebots'       => 'Stërma le modìfiche fàite daj trigomiro ant la lista dle ròbe che im ten-o sot euj',
'tog-watchlisthideminor'      => "Stërma le modìfiche cite da 'nt lòn che im ten-o sot euj",
'tog-watchlisthideliu'        => "Stërma le modìfiche fàite da j'utent registrà ant la lista dle ròbe che im ten-o sot euj",
'tog-watchlisthideanons'      => "Stërma le modìfiche fàite da j'utent anònim da 'nt lòn che im ten-o sot euj",
'tog-watchlisthidepatrolled'  => "Stërma le modìfiche verificà da 'nt la ròba che im ten-o sot euj",
'tog-nolangconversion'        => 'Fërma la conversion antra variant lenghìstiche',
'tog-ccmeonemails'            => "Mand-me còpia dij messagi ëd pòsta eletrònica che i-j mando a j'àotri utent",
'tog-diffonly'                => 'Smon pa ël contnù dla pàgina dapress a le diferense',
'tog-showhiddencats'          => 'Smon le categorìe stërmà',
'tog-noconvertlink'           => "Disativé la conversion dij tìtoj ant j'anliure",
'tog-norollbackdiff'          => "Fa nen vëdde le diferense apress d'avèj ripristinà",

'underline-always'  => 'Sempe',
'underline-never'   => 'Mai',
'underline-default' => 'Deuvra lë stàndard dël programma ëd navigassion (browser)',

# Font style option in Special:Preferences
'editfont-style'     => "Stil dël font ëd l'àrea ëd modìfica:",
'editfont-default'   => 'Stàndard dël navigator',
'editfont-monospace' => 'Font mono-spassià',
'editfont-sansserif' => 'Font sans-serif',
'editfont-serif'     => 'Font serif',

# Dates
'sunday'        => 'Dumìnica',
'monday'        => 'Lùn-es',
'tuesday'       => 'Màrtes',
'wednesday'     => 'Merco',
'thursday'      => 'Giòbia',
'friday'        => 'Vënner',
'saturday'      => 'Saba',
'sun'           => 'Dum',
'mon'           => 'Lun',
'tue'           => 'Màr',
'wed'           => 'Mer',
'thu'           => 'Giò',
'fri'           => 'Vën',
'sat'           => 'Sab',
'january'       => 'Gené',
'february'      => 'Fërvé',
'march'         => 'Mars',
'april'         => 'Avril',
'may_long'      => 'Magg',
'june'          => 'Giugn',
'july'          => 'Luj',
'august'        => 'Aost',
'september'     => 'Stèmber',
'october'       => 'Otóber',
'november'      => 'Novèmber',
'december'      => 'Dzèmber',
'january-gen'   => 'Gené',
'february-gen'  => 'Fërvé',
'march-gen'     => 'Mars',
'april-gen'     => 'Avril',
'may-gen'       => 'Magg',
'june-gen'      => 'Giugn',
'july-gen'      => 'Luj',
'august-gen'    => 'Aost',
'september-gen' => 'Stèmber',
'october-gen'   => 'Otóber',
'november-gen'  => 'Novèmber',
'december-gen'  => 'Dzèmber',
'jan'           => 'Gen',
'feb'           => 'Fër',
'mar'           => 'Mar',
'apr'           => 'Avr',
'may'           => 'Mag',
'jun'           => 'Giu',
'jul'           => 'Luj',
'aug'           => 'Aos',
'sep'           => 'Stè',
'oct'           => 'Otó',
'nov'           => 'Nov',
'dec'           => 'Dzè',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categorìa|Categorìe}}',
'category_header'                => 'Artìcoj ant la categorìa "$1"',
'subcategories'                  => 'Sotacategorìe',
'category-media-header'          => 'Archivi ant la categorìa "$1"',
'category-empty'                 => "''Al dì d'ancheuj la categorìa a l'ha pa andrinta nì 'd pàgine, nì d'archivi multimojen.''",
'hidden-categories'              => '{{PLURAL:$1|Categorìa stërmà|Categorìe stërmà}}',
'hidden-category-category'       => 'Categorìe stërmà',
'category-subcat-count'          => "{{PLURAL:$2|Sta categorìa-sì a l'ha mach na sot-categorìa, listà ambelessì sota.|Sta categorìa-sì a l'ha {{PLURAL:$1|na sot-categorìa|$1 sot-categorìe}}, ëd $2 ch'a-i në j'é an total.}}",
'category-subcat-count-limited'  => "Sta categorìa-sì a l'ha {{PLURAL:$1|la sot-categorìa|le $1 sot-categorìe}} sì-dapress.",
'category-article-count'         => "{{PLURAL:$2|Sta categorìa-sì a l'ha mach sta pàgina.|Ant sta categorìa-sì a-i {{PLURAL:$1|intra mach sta pàgina|intro $1 pàgine}} ëd $2 ch'a-i në j'é an total.}}",
'category-article-count-limited' => 'Ant sta categorìa-sì a-i {{PLURAL:$1|resta mach sta pàgina|resto $1 pàgine}}.',
'category-file-count'            => "{{PLURAL:$2|Sta categorìa-sì a l'ha nomach st'archivi.|Sta categorìa-sì a l'ha {{PLURAL:$1|n'|$1}} archivi, ëd $2 ch'a-i në j'é an total.}}",
'category-file-count-limited'    => "Ant sta categorìa-sì a-i {{PLURAL:$1|intra mach st'|intro $1}} archivi.",
'listingcontinuesabbrev'         => ' anans',
'index-category'                 => 'Pàgine indicisà',
'noindex-category'               => 'Pàgine pa indicisà',

'mainpagetext'      => "'''MediaWiki a l'é staita anstalà a la përfession.'''",
'mainpagedocfooter' => "Che a varda la [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] për avèj dj'anformassion ant sël coma dovré ël programa dla wiki.

== Për anandiesse a travajé ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista dij paràmeter ëd configurassion]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Chestion frequente]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista ëd discussion an sla distribussion ëd MediaWiki]",

'about'         => 'A propòsit ëd',
'article'       => 'Pàgina ëd contnù',
'newwindow'     => '(as deurb ant na fnestra neuva)',
'cancel'        => 'Scancela',
'moredotdotdot' => 'Dë pì...',
'mypage'        => 'Mia pàgina',
'mytalk'        => 'Mie ciaciarade',
'anontalk'      => "Ciaciarade për st'adrëssa IP-sì",
'navigation'    => 'Navigassion',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Treuva',
'qbbrowse'       => 'Sfeuja',
'qbedit'         => 'Modìfica',
'qbpageoptions'  => 'Opsion dla pàgina',
'qbpageinfo'     => 'Anformassion rësguard a la pàgina',
'qbmyoptions'    => 'Mie opsion',
'qbspecialpages' => 'Pàgine speciaj',
'faq'            => 'Chestion frequente',
'faqpage'        => 'Project:Soèns An Ciamo',

# Vector skin
'vector-action-addsection'       => 'Gionta argoment',
'vector-action-delete'           => 'Scancela',
'vector-action-move'             => 'Tramuda',
'vector-action-protect'          => 'Protegg',
'vector-action-undelete'         => 'Arcùpera',
'vector-action-unprotect'        => 'Sprotegg',
'vector-simplesearch-preference' => "Abilité ij sugeriment d'arserca ameliorà (mach për la pel Vector)",
'vector-view-create'             => 'Crea',
'vector-view-edit'               => 'Modìfica',
'vector-view-history'            => 'Varda stòria',
'vector-view-view'               => 'Les',
'vector-view-viewsource'         => 'Varda sorgiss',
'actions'                        => 'Assion',
'namespaces'                     => 'Spassi nominaj',
'variants'                       => 'Variant',

'errorpagetitle'    => 'Eror',
'returnto'          => 'Torna andré a $1.',
'tagline'           => 'Da {{SITENAME}}.',
'help'              => 'Agiut',
'search'            => 'Sërca',
'searchbutton'      => 'Sërca',
'go'                => 'Va',
'searcharticle'     => 'Va',
'history'           => 'Version pì veje',
'history_short'     => 'Stòria',
'updatedmarker'     => "Agiornà da 'nt l'ùltima vira che i son passà",
'info_short'        => 'Anformassion',
'printableversion'  => 'Version bon-a për stampé',
'permalink'         => 'Anliura fissa',
'print'             => 'Stampa',
'view'              => 'Vardé',
'edit'              => 'Modìfica',
'create'            => 'Creé',
'editthispage'      => "Modìfica st'artìcol-sì",
'create-this-page'  => 'Creé sta pàgina',
'delete'            => 'Scancela',
'deletethispage'    => 'Scancela pàgina',
'undelete_short'    => 'Disdëscancela {{PLURAL:$1|na modìfica|$1 modìfiche}}',
'viewdeleted_short' => 'Vardé {{PLURAL:$1|na modìfica scancelà|$1 modìfiche scancelà}}',
'protect'           => 'Protegg',
'protect_change'    => 'cambia',
'protectthispage'   => 'Protegg sta pàgina-sì',
'unprotect'         => 'Gava la protession',
'unprotectthispage' => 'Gava via la protession',
'newpage'           => 'Pàgina neuva',
'talkpage'          => 'Discussion',
'talkpagelinktext'  => 'discussion',
'specialpage'       => 'Pàgina Special',
'personaltools'     => 'Utiss përsonaj',
'postcomment'       => 'Session neuva',
'articlepage'       => "Che a varda l'artìcol",
'talk'              => 'Discussion',
'views'             => 'vìsite',
'toolbox'           => 'utiss',
'userpage'          => 'Che a varda la pàgina Utent',
'projectpage'       => 'Che a varda la pàgina ëd servissi',
'imagepage'         => "Varda la pàgina dl'archivi",
'mediawikipage'     => 'Mostra ël mëssagi',
'templatepage'      => 'Mostra lë stamp',
'viewhelppage'      => "Smon la pàgina d'agiut",
'categorypage'      => 'Fa vëdde la categorìa',
'viewtalkpage'      => 'Vardé la discussion',
'otherlanguages'    => 'Àutre lenghe',
'redirectedfrom'    => '(Ridiression da $1)',
'redirectpagesub'   => 'Pàgina ëd ridiression',
'lastmodifiedat'    => "Modificà l'ùltima vira al $2, $1.",
'viewcount'         => "St'artìcol-sì a l'é stàit lesù {{PLURAL:$1|na vira|$1 vire}}.",
'protectedpage'     => 'Pàgina proteta',
'jumpto'            => 'Va a:',
'jumptonavigation'  => 'navigassion',
'jumptosearch'      => 'arserca',
'view-pool-error'   => "An dëspias, ij servent a son motobin carià al moment.
Tròpi utent a son an camin ch'a preuvo a lese sta pàgina-sì.
Për piasì, speta un pòch prima ëd prové torna a vardé sta pàgina-sì.

$1",
'pool-timeout'      => "Ël temp a l'é finì antramentre ch'a së spetava la saradura",
'pool-queuefull'    => "La coa ëd travaj a l'é pien-a",
'pool-errorunknown' => 'Eror pa conossù',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A propòsit ëd {{SITENAME}}',
'aboutpage'            => 'Project:A propòsit',
'copyright'            => 'Ël contnù a resta disponìbil sota a na licensa $1.',
'copyrightpage'        => "{{ns:project}}:Drit d'autor",
'currentevents'        => 'Neuve',
'currentevents-url'    => 'Project:Neuve',
'disclaimers'          => 'Difide',
'disclaimerpage'       => 'Project:Avertense generaj',
'edithelp'             => 'Manual dë spiegassion',
'edithelppage'         => "Help:Coma scrive n'artìcol",
'helppage'             => 'Help:Agiut',
'mainpage'             => 'Intrada',
'mainpage-description' => 'Intrada',
'policy-url'           => 'Project:Deuit',
'portal'               => 'Piòla',
'portal-url'           => 'Project:Piòla',
'privacy'              => 'Polìtica ëd confindensialità',
'privacypage'          => 'Project:Polìtica ëd confidensialità',

'badaccess'        => 'Përmess nen giust',
'badaccess-group0' => "A l'ha pa ij përmess dont a fa dë manca për fé st'operassion-sì.",
'badaccess-groups' => "Costa funsion-sì a l'é riservà a j'utent che a sio almanch ant {{PLURAL:$2|la partìa|un-a dle partìe}}: $1.",

'versionrequired'     => 'A-i va për fòrsa la version $1 ëd MediaWiki',
'versionrequiredtext' => 'Për dovré sta pàgina-sì a-i va la version $1 dël programa MediaWiki. Che a varda [[Special:Version]]',

'ok'                      => 'Va bin',
'retrievedfrom'           => 'Pijàit da  "$1"',
'youhavenewmessages'      => "A l'ha $1 ($2).",
'newmessageslink'         => 'mëssagi neuv',
'newmessagesdifflink'     => "A-i é chèich-còs ëd diferent da 'nt l'ùltima revision",
'youhavenewmessagesmulti' => "A l'ha dij neuv mëssagi an $1",
'editsection'             => 'modìfica',
'editold'                 => 'modìfica',
'viewsourceold'           => 'fa vëdde ël còdes sorgiss',
'editlink'                => 'modìfica',
'viewsourcelink'          => 'fà vëdde ël còdes sorgiss',
'editsectionhint'         => 'I soma dapress a modifiché la session: $1',
'toc'                     => 'Contnù',
'showtoc'                 => 'smon',
'hidetoc'                 => 'stërma',
'collapsible-collapse'    => 'Sëré',
'collapsible-expand'      => 'Deurbe',
'thisisdeleted'           => 'Veul-lo vardé ò ripristiné $1?',
'viewdeleted'             => 'Veul-lo vardé $1?',
'restorelink'             => '{{PLURAL:$1|na modìfica scancelà|$1 modìfiche scancelà}}',
'feedlinks'               => 'Fluss:',
'feed-invalid'            => 'Modalità ëd sot-ëscrission dël fluss nen vàlida.',
'feed-unavailable'        => 'Ij fluss ëd neuve a son nen disponìbij',
'site-rss-feed'           => 'Emission RSS $1',
'site-atom-feed'          => 'Emission Atom $1',
'page-rss-feed'           => 'Emission RSS "$1"',
'page-atom-feed'          => 'Emission Atom "$1"',
'red-link-title'          => "$1 (pàgina ch'a-i é ancor pa)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artìcol',
'nstab-user'      => "Pàgina dl'utent",
'nstab-media'     => 'Pàgina multimedial',
'nstab-special'   => 'Pàgina special',
'nstab-project'   => 'Pàgina ëd servissi',
'nstab-image'     => 'Figura',
'nstab-mediawiki' => 'Mëssagi',
'nstab-template'  => 'Stamp',
'nstab-help'      => 'Agiut',
'nstab-category'  => 'Categorìa',

# Main script and global functions
'nosuchaction'      => 'Operassion nen arconossùa',
'nosuchactiontext'  => "L'operassion che a l'ha ciamà ant l'anliura a l'é nen arconossùa.
A peul esse che it l'abie batù mal l'URL, o che it sie andàit dapress a n'anliura nen giusta.
Sossì a podrìa ëdcò esse un bigat andrinta al programa dovrà da {{SITENAME}}.",
'nosuchspecialpage' => "A-i é pa gnun-a pàgina special tan-me cola che chiel a l'ha ciamà.",
'nospecialpagetext' => "<strong>It l'has ciamà na pàgina special pa bon-a.</strong>

Na lista ëd pàgine speciaj bon-e a peul esse trovà ambelessì [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Eror',
'databaseerror'        => 'Eror ant la base dat',
'dberrortext'          => 'A l\'é capitaje n\'eror ëd sintassi ant la domanda mandà a la base dat.
Sòn a peul vorèj dì n\'eror ant ël programa.
L\'ùltima domanda mandà a la base dat a l\'é stàita:
<blockquote><tt>$1</tt></blockquote>
da \'nt la funsion "<tt>$2</tt>".
La base dat a l\'ha dane andré n\'eror "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'A-i é staje n\'eror ant la sintassi d\'anterogassion dla base dat.
L\'ùltima anterogassion a l\'é stàita:
"$1"
da andrinta a la funsion "$2".
La base dat a l\'ha dane n\'eror "$3: $4"',
'laggedslavemode'      => 'Avis: la pàgina a podrìa ëdcò nen mostré tute soe modìfiche.',
'readonly'             => 'Acess a la base dat sërà për chèich temp.',
'enterlockreason'      => 'Che a buta na rason për ël blocagi, con andrinta data e ora ëd quand che a stima che a sarà gavà.',
'readonlytext'         => "La base dat ëd {{SITENAME}} për adess a l'é blocà, e as peulo pa fesse nì dle neuve imission, nì dle modìfiche, con tute le probabilità për n'operassion ëd manutension dël servent. Se a l'é parèj, motobin ampressa la base a sarà torna duvèrta.<br />
L'aministrator che a l'ha blocala a l'ha lassà sto messagi-sì:
<p>:$1",
'missing-article'      => "Ël database a l'ha nen trovà ël test ëd na pagina che a l'avrìa dovù trové, ciamà \"\$1\" \$2.

Sossì ëd sòlit a l'é causà përché a l'é ciamasse na diferensa o n'anliura stòrica a na paginà scancelà.

Se cost a l'é nen ël cas, it podrìe avèj trovà un bigat ant ël programa.
Për piasì, fa rapòrt a n'[[Special:ListUsers/sysop|aministrator]], pijand nòta ëd la URL.",
'missingarticle-rev'   => '(revision#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => "La base dat a l'é staita blocà n'automàtich antramentr che le màchine dël sircùit secondari (slave) as buto an pari con cole dël prinsipal (master)",
'internalerror'        => 'Eror intern',
'internalerror_info'   => 'Eror antern: $1',
'fileappenderrorread'  => 'As peul pa les-se "$1" durant dla gionta.',
'fileappenderror'      => 'As peul pa pendse "$1" a "$2".',
'filecopyerror'        => 'A l\'é pa stàit possibil copié l\'archivi "$1" coma "$2".',
'filerenameerror'      => 'A l\'é pa podusse cangeje nòm a l\'archivi "$1" an "$2".',
'filedeleteerror'      => 'A l\'é pa podusse scancelé l\'archivi "$1".',
'directorycreateerror' => 'A l\'é pa podusse fé ël dossié "$1".',
'filenotfound'         => ' A l\'é pa trovasse l\'archivi "$1".',
'fileexistserror'      => 'As peul pa scriv-se l\'archivi "$1": a-i é già',
'unexpected'           => 'Valor che i së spitavo pa: "$1"="$2".',
'formerror'            => "Eror: la domanda a l'é stàita mandà mal",
'badarticleerror'      => "N'operassion parèj as peul pa fesse ansima a sta pàgina-sì.",
'cannotdelete'         => "La pàgina o l'archivi \"\$1\" a peul pa esse scancelà.
Peul desse ch'a l'é già stàit ëscancelà da cheidun d'àutr.",
'badtitle'             => 'Tìtol nen giust',
'badtitletext'         => "La pàgina che a l'ha ciamà a peul pa esse mostrà. A podrìa tratesse ëd na pàgina nen bon-a, veujda, ò pura a podrìa ëdcò esse n'eror ant n'anliura antra lenghe diferente ò tra diferente version ëd {{SITENAME}}.",
'perfcached'           => "Sòn a l'é stait memorisà an local e podrìa ëdcò nen esse agiornà:",
'perfcachedts'         => "Lòn che a-j ven dapress a sossì a l'é pijàit da 'nt na còpia local \"cache\" dla base dat. L'ùltim agiornament a l'é dël: \$1.",
'querypage-no-updates' => "J'agiornament për sta pàgina-sì për adess a travajo nen. Ij dat ambelessì a saran nen rinfrescà.",
'wrong_wfQuery_params' => 'Paràmetro nen giust për wfQuery()<br />
Funsion: $1<br />
Arcesta: $2',
'viewsource'           => 'Vardé la sorgiss',
'viewsourcefor'        => 'ëd $1',
'actionthrottled'      => 'Assion frenà',
'actionthrottledtext'  => "Për evité che gent ò màchine an carìo dla rumenta, st'assion-sì as peul nen fesse tròp ëd soèns, e chiel/chila a l'ha arpetula tròpe vire. Ch'a sia gentil, ch'a preuva torna antra dontre minute.",
'protectedpagetext'    => "Sta pàgina-sì a l'è stàita blocà për evité che a-j faso dle modìfiche.",
'viewsourcetext'       => 'A peul vardé e copié la sorgiss dë sta pàgina:',
'protectedinterface'   => "Costa pàgina-sì a l'ha andrinta un chèich-còs che a fa part d'antërfacia dël programa che a deuvro tùit; donca a l'é proteta për evité che a-i rivo dle ròbe brute.",
'editinginterface'     => "'''Dossman!''' A l'é dapress ch'a-i travaja ansima a na pàgina ch'as deuvra për generé ël test dl'antërfacia dël programa. Le modìfiche fàite ambelessì a-j bogio l'antërfacia a tuti j'utent. Se sò but a l'é col ëd fé na tradussion, për piasì ch'a considerà la possibilità dë dovré [http://translatewiki.net/wiki/Main_Page?setlang=pms translatewiki.net], ël proget ëd localisassion ëd MediaWiki.",
'sqlhidden'            => "(l'anterogassion SQL a l'é stërmà)",
'cascadeprotected'     => 'Ant sta pàgina-sì as peulo pa fé ëd modìfiche, përché a-i intra ant {{PLURAL:$1|la pàgina|le pàgine}} butà sot a protession con la fonsion "a tombé" viscà ansima a: $2',
'namespaceprotected'   => "A l'ha nen ël përmess dë feje dle modìfiche a le pàgine dlë spassi nominal '''$1'''.",
'customcssjsprotected' => "Ch'a varda ch'a l'ha pa ël përmess ëd modifiché sta pàgina-sì, për via ch'a l'ha andrinta ij gust ëd n'àotr utent.",
'ns-specialprotected'  => 'As peulo nen modifichesse le pàgine dlë spassi nominal {{ns:special}}.',
'titleprotected'       => "La creassion ëd pàgine con ës tìtol-sì a l'é stàita proibìa da [[User:$1|$1]].
Coma rason a l'ha butà: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configurassion falà: antivìrus nen conossù: ''$1''",
'virus-scanfailed'     => 'scansion falìa (còdes $1)',
'virus-unknownscanner' => 'antivìrus nen conossù:',

# Login and logout pages
'logouttext'                 => "'''A l'é sortù da 'nt ël sistema.'''

A peul tiré anans a dovré {{SITENAME}} coma Utent anonim, ò pura a peul [[Special:UserLogin|rintré torna ant ël sistema]] con l'istess stranòm che a dovrava prima, ò con un diferent.
Ch'a nòta che chèich pàgine a peulo continué a esse visualisà com s'a fussa ancó ant ël sistema, fin ch'a scancela pa la cache ëd sò navigador.",
'welcomecreation'            => '==Bin ëvnù, $1!==
Sò cont a l\'é stàit creà.
Che as dësmentia pa ëd cambié ij [[Special:Preferences|"sò gust" an {{SITENAME}}]].',
'yourname'                   => 'Sò stranòm',
'yourpassword'               => 'Soa ciav',
'yourpasswordagain'          => 'Che a bata torna soa ciav',
'remembermypassword'         => "↓ Vis-te mia ciav ansima a st'ordinator-sì (për al pi $1 {{PLURAL:$1|di|di}})",
'securelogin-stick-https'    => "Resté colegà an HTTPS apress d'esse intrà ant ël sistema",
'yourdomainname'             => 'Sò domini',
'externaldberror'            => "Ò che a l'é rivaje n'eror d'autenticassion esterna, ò pura a l'é chiel (chila) che a l'é nen autorisà a agiornesse sò cont estern.",
'login'                      => 'Rintré ant ël sistema',
'nav-login-createaccount'    => 'rintré ant ël sistema',
'loginprompt'                => 'Che a varda mach che a venta avèj ij cookies abilità për podèj rintré an {{SITENAME}}.',
'userlogin'                  => 'rintré ant ël sistema',
'userloginnocreate'          => 'Intra',
'logout'                     => "Seurte da 'nt ël sistema",
'userlogout'                 => 'seurte dal sistema',
'notloggedin'                => "a l'é pa ant ël sistema",
'nologin'                    => "Ha-lo ancó nen un cont? '''$1'''.",
'nologinlink'                => 'creésse un cont.',
'createaccount'              => 'Crea un cont neuv',
'gotaccount'                 => "Ha-lo già un sò cont? '''$1'''.",
'gotaccountlink'             => 'Rintré ant ël sistema',
'createaccountmail'          => 'për pòsta eletrònica',
'createaccountreason'        => 'Rason:',
'badretype'                  => "Le doe ciav che a l'ha scrivù a resto diferente antra lor, e a venta che a sio mideme.",
'userexists'                 => "Lë stranòm anserì a l'é già dovrà.
Për piasì, sern në stranòm diferent.",
'loginerror'                 => 'Eror ën rintrand ant ël sistema',
'createaccounterror'         => 'As peul pa creesse ël cont: $1',
'nocookiesnew'               => "Sò cont a l'é duvèrt, ma chiel (ò chila) a l'ha nen podù rintré ant ël sistema.
{{SITENAME}} a deuvra ij cookies për fé rintré la gent ant sò sistema. Belavans chiel a l'ha pa ij cookies abilità.
Për piasì, che as j'abìlita e peuj che a preuva torna a rintré con sò stranòm e soa ciav.",
'nocookieslogin'             => "{{SITENAME}} a deuvra ij cookies për fé rintré la gent ant sò sistema. Belavans chiel a l'ha pa ij cookies abilità. Për piasì, che a j'abìlita e peuj che a preuva torna.",
'nocookiesfornew'            => "Ël cont utent a l'é pa stàit creà, antlora i podoma pa confirmé soa sorgiss.
Ch'a contròla d'avèj ij bëscotin abilità, ch'a caria torna la pàgina e ch'a preuva torna.",
'noname'                     => "Lë stranòm che a l'ha batù as peul pa dovresse, as peul nen creésse un cont Utent con ës nòm-sì.",
'loginsuccesstitle'          => "Compliment! A l'é pen-a rintrà ant ël sistema. A-i é pa staje gnun eror.",
'loginsuccess'               => 'A l\'ha avù ël përmess ëd conession al servent ëd {{SITENAME}} con lë stranòm utent ëd "$1".',
'nosuchuser'                 => 'A-i é pa gnun utent con ël nòm "$1".
Ij nòm ëd j\'utent a son sensìbij a le majùscole.
Controla ël nòm che it l\'has batù, o [[Special:UserLogin/signup|fà un neuv cont]].',
'nosuchusershort'            => 'A-i é pa gnun utent che as ciama "<nowiki>$1</nowiki>". Për piasì, che a contròla se a l\'ha scrit tut giust.',
'nouserspecified'            => 'A venta che a specìfica në stranòm utent',
'login-userblocked'          => "St'utent-sì a l'é blocà. A peul pa intré ant ël sistema.",
'wrongpassword'              => "La ciav batùa a l'é pa giusta.<br /><br />Che a preuva torna, për piasì.",
'wrongpasswordempty'         => "A l'ha butà na ciav veujda. Për piasì, che a preuva torna.",
'passwordtooshort'           => 'Le ciav a devo avèj almanch {{PLURAL:$1|1 caràter|$1 caràter}}.',
'password-name-match'        => 'Toa ciav a deuv esse diferenta da tò stranòm.',
'password-login-forbidden'   => "L'usagi dë sto nòm utent e ciav a son ëstàit vietà.",
'mailmypassword'             => 'Mandme na neuva ciav për pòsta eletrònica',
'passwordremindertitle'      => 'Servissi për visé la paròla ciav ëd {{SITENAME}}',
'passwordremindertext'       => "Cheidun (a l'é belfé che a sia stàit pròpe chiel, da 'nt l'adrëssa IP \$1) a l'ha ciamà che i-j mandèisso
na neuva paròla ciav për rintré ant ël sistema ëd {{SITENAME}} (\$4).
Na ciav a temp për l'utent \"\$2\" a l'é stàita fàita e adess a resta \"\$3\". Se cost a l'era sò intent,
che a la deuvra për rintré e che a serna na neuva ciav adess.
Soa ciav a temp a scad an {{PLURAL:\$5|un di|\$5 di}}.

Se cheidun d'àutr a l'ha fàit costa arcesta, o se chiel a l'é arcordasse dla ciav,
e a veul pì nen cambiela, che a fasa finta ëd gnente e ch'a continua a dovré soa ciav veja.",
'noemail'                    => 'An arzulta pa gnun-a casela ëd pòsta eletrònica për l\'Utent "$1".',
'noemailcreate'              => "It deve dé n'adrëssa ëd pòsta eletrònica bon-a",
'passwordsent'               => "Na neuva paròla ciav a l'é stàita mandà a l'adrëssa eletrònica registrà për l'Utent \"\$1\".
Për piasì, che a la deuvra sùbit për rintré ant ël sistema pen-a che a l'arsèiv.",
'blocked-mailpassword'       => "Për evité dj'assion nen corete as peul pa dovresse la funsion \"Mand-me na ciav neuva\" da 'nt n'adrëssa IP ëd cole blocà.",
'eauthentsent'               => "A l'adrëssa che a l'ha dane i l'oma mandaje un messagi ëd pòsta eletrònica për conferma.
Anans che qualsëssìa àutr messagi ëd pòsta a ven-a mandà a 's cont-sì, a venta che a a fasa coma che a-j diso dë fé ant ël messagi, për confermé che ës cont a l'é da bon sò.",
'throttled-mailpassword'     => 'Na ciav neuva a l\'é gia stàita mandà da manch che {{PLURAL:$1|n\'ora|$1 ore}}. Për evité dij dovré nen regolar, la funsion "Mand-me na ciav neuva" as peul dovresse mach vira {{PLURAL:$1|n\'ora|$1 ore}}.',
'mailerror'                  => 'Eror ën mandand via un mëssagi ëd pòsta eletrònica: $1',
'acct_creation_throttle_hit' => "I visitador ëd costa wiki, an dovrand toa adrëssa IP a l'han fàit {{PLURAL:$1|1 cont|$1 cont}} ant l'ùltim di, che a l'é tut lòn che as peul fesse ant cost temp.
Com arzultà, ij visitador che a deuvro costa adrëssa IP a peulo pì nen fé dij cont al moment.",
'emailauthenticated'         => "Soa adrëssa ëd pòsta eletrònica a l'é stàita autenticà ël $2 a $3.",
'emailnotauthenticated'      => "Soa adrëssa ëd pòsta eletrònica a l'é ancó pa stàita autenticà.
Da qualsëssìa ëd coste funsion a sarà mandà gnun messagi fin che chiel (chila) a s'auténtica nen.",
'noemailprefs'               => "Che a specìfica n'adrëssa ëd pòsta eletrònica se a veul dovré coste funsion-sì.",
'emailconfirmlink'           => 'Che an conferma soa adrëssa ëd pòsta eletrònica',
'invalidemailaddress'        => "Costa adrëssa ëd pòsta eletrònica-sì as peul nen pijesse përchè a l'ha na forma nen bon-a.
Për piasì che a buta n'adrëssa scrita giusta ò che a lassa ël camp veujd.",
'accountcreated'             => 'Cont creà',
'accountcreatedtext'         => "Ël cont Utent për $1 a l'é stàit creà.",
'createaccount-title'        => 'Creassion ëd cont për {{SITENAME}}',
'createaccount-text'         => 'Cheidun a l\'ha dorbù un cont për st\'adrëssa ëd pòsta eletrònica-sì ansima a {{SITENAME}} ($4) butand da stranòm "$2" e da ciav "$3". A dovrìa rintré ant ël sistema e cambiesse soa ciav pì ampressa ch\'a peul.

Se sòn a l\'é rivà për eror, a peul lassé sté e fe gnente sensa problema.',
'usernamehasherror'          => "Un nòm utent a peul pa conten-e caràter ciapulà (''hash'')",
'login-throttled'            => "It l'has fàit tròpi tentativ recent d'intré.
Për piasì speta prima ëd prové torna.",
'login-abort-generic'        => "Tò login a l'é pa riussì - Abortì",
'loginlanguagelabel'         => 'Lenga: $1',
'suspicious-userlogout'      => "Soa arcesta ëd seurte dal sistema a l'é stàita arfudà përchè a smija com s'a fussa stàita mandà da 'n navigador scolegà o da l'archiviassion an local d'un proxy.",

# E-mail sending
'php-mail-error-unknown' => 'Eror pa conossù ant la funsion PHP mail()',

# JavaScript password checks
'password-strength'            => 'Fòrsa stimà dla ciav: $1',
'password-strength-bad'        => 'GRAMA',
'password-strength-mediocre'   => 'mediòcr',
'password-strength-acceptable' => 'a peul andé',
'password-strength-good'       => 'bon-a',
'password-retype'              => 'Bat torna la ciav ambelessì',
'password-retype-mismatch'     => 'Le ciav a son pa mideme',

# Password reset dialog
'resetpass'                 => 'Cambia la ciav',
'resetpass_announce'        => "A l'é rintrà ant ël sistema con na ciav provisòria mandà via për pòsta eletrònica. Për podèj finì la procedura a l'ha da butesse na ciav neuva ambelessì:",
'resetpass_text'            => '<!-- Gionté dël test ambelessì -->',
'resetpass_header'          => 'Cambia la ciav dël cont',
'oldpassword'               => 'Veja ciav',
'newpassword'               => 'Neuva ciav',
'retypenew'                 => 'Che a scriva torna soa neuva ciav',
'resetpass_submit'          => 'Registra la ciav e rintra ant ël sistema',
'resetpass_success'         => "Soa ciav a l'é stàita registrà sensa problema. I soma dapress a rintré ant ël sistema...",
'resetpass_forbidden'       => 'Le ciav as peulo pa cambiesse',
'resetpass-no-info'         => 'It deve esse intrà për andé diretament a sta pàgina.',
'resetpass-submit-loggedin' => 'Cambia ciav',
'resetpass-submit-cancel'   => 'Scancela',
'resetpass-wrong-oldpass'   => "Ciav a temp o corenta nen bon-a.
Miraco it l'has già cambià la ciav o it l'has ciamà na neuva ciav a temp.",
'resetpass-temp-password'   => 'Ciav a temp:',

# Edit page toolbar
'bold_sample'     => 'Test an grassèt',
'bold_tip'        => 'Test an grassèt',
'italic_sample'   => 'Test an corsiv',
'italic_tip'      => 'Test an corsiv',
'link_sample'     => "Tìtol dl'anliura",
'link_tip'        => 'Anliura interna',
'extlink_sample'  => "http://www.example.com tìtol dl'anliura",
'extlink_tip'     => 'Anliura esterna (che as visa dë buté ël prefiss http://)',
'headline_sample' => "Antestassion dl'artìcol",
'headline_tip'    => 'Antestassion dë scond livel',
'math_sample'     => 'Che a buta la fòrmula ambelessì',
'math_tip'        => 'Fòrmula matemàtica (LaTeX)',
'nowiki_sample'   => 'Che a buta ël test nen formatà ambelessì',
'nowiki_tip'      => 'Lassé un tòch ëd test fòra dla formatassion dla wiki',
'image_sample'    => 'Esempi.jpg',
'image_tip'       => 'Figura anglobà ant ël test',
'media_sample'    => 'Esempi.ogg',
'media_tip'       => "Anliura a n'archivi multimedial",
'sig_tip'         => 'Firma butand data e ora',
'hr_tip'          => 'Riga orisontal (da dovresse nen tròp soèns)',

# Edit pages
'summary'                          => 'Resumé:',
'subject'                          => 'Sogèt:',
'minoredit'                        => "Costa-sì a l'é na modìfica cita",
'watchthis'                        => "Ten sot euj st'artìcol-sì",
'savearticle'                      => 'Salva sta pàgina',
'preview'                          => 'Preuva',
'showpreview'                      => 'Mostra na preuva',
'showlivepreview'                  => "Funsion ''Preuva dal viv''",
'showdiff'                         => 'Smon-me le modìfiche',
'anoneditwarning'                  => "A l'é ancó nen rintrà ant ël sistema. Soa adrëssa IP a sarà registrà ant la stòria dle modìfiche dë sta pàgina-sì.",
'anonpreviewwarning'               => "''It ses pa intrà. An salvand a sarà memorisà toa adrëssa IP ant la stòria dle modìfiche dë sta pàgina-sì.''",
'missingsummary'                   => "'''Nòta:''' a l'ha pa butà gnun somari dla modìfica. Se a sgnaca Salva n'àutra vira, soa modìfica a resterà salvà sensa pa ëd somari.",
'missingcommenttext'               => 'Për piasì che a buta un coment ambelessì sota.',
'missingcommentheader'             => "'''Ch'a arcòrda:''' A l'ha pa dàit soget o intestassion për sto coment-sì.
Se a sgnaca torna \"{{int:savearticle}}\", soa modìfica a sarà salvà sensa gnun-a intestassion.",
'summary-preview'                  => "Preuva dl'oget:",
'subject-preview'                  => "Preuva d'oget/intestassion:",
'blockedtitle'                     => "Belavans cost ëstranòm-sì a resta col ëd n'utent che a l'é stàit disabilità a fé 'd modìfiche a j'artìcoj.",
'blockedtext'                      => "'''Sò stranòm ò pura adrëssa IP a l'é stàit blocà.'''

Ël blocagi a l'é stàit fàit da \$1.
Coma rason a l'ha butà ''\$2''.

* Blocà a parte dal: \$8
* Fin al: \$6
* As veul blochesse: \$7

A peul butesse an contat con \$1 ò pura n'àotr [[{{MediaWiki:Grouppage-sysop}}|aministrator]] për discute ëd sò blocagi.
Ch'a ten-a present ch'a podrà dovré la fonsion \"mandeje un messagi ëd pòsta a l'utent\" mach s'a l'ha specificà n'adrëssa ëd pòsta vàlida ant [[Special:Preferences|sò gust]] e se sta fonsion a l'é nen ëstàita blocà 'cò chila.
Soa adrëssa IP corenta a l'é \$3, e l'identificativ dël blocagi a l'é #\$5.
Për piasì, ch'a-j buta tut e doj ant soe comunicassion ant sta question-sì.",
'autoblockedtext'                  => "Soa adrëssa IP a l'è stàita blocà n'automàtich ën essend ch'a l'era dovrà da n'àutr utent, che a l'é stàit blocà da \$1.
La rason butà për ël blocagi a l'é

:''\$2''

* Ël blocagi a part dël: \$8
* A va a la fin dël: \$6
* Antërval ëd blocagi: \$7

A peul contaté \$1 ò pura n'àotr dj'[[{{MediaWiki:Grouppage-sysop}}|aministrator]] për discute d'ës blocagi.

Ch'a varda mach ch'a peul nen dovré l'opsion ëd \"mandeje un messagi a l'utent\" se a l'ha nen n'adrëssa ëd pòsta eletrònica registrà e verificà ant [[Special:Preferences|sò gust]] e se chiel a l'é stàit blocà ëdcò dal dovrela.

Soa adrëssa IP corenta a l'é \$3, e sò nùmer ëd blocagi a l'é \$5.
Për piasì, ch'a buta sempe tùit ij detaj an tute le comunicassion andova ch'as parla ëd sò blocagi.",
'blockednoreason'                  => "a l'han pa butà gnun-a rason",
'blockedoriginalsource'            => "La sorgiss ëd '''$1''' a së s-ciàira ambelessì sota:",
'blockededitsource'                => "Ël test ëd le '''soe modìfiche''' a '''$1''' a së s-ciàira ambelessì sota:",
'whitelistedittitle'               => 'Sòn as peul pa fesse nen rintrand ant ël sistema',
'whitelistedittext'                => 'A venta $1 për podèj fé dle modìfiche.',
'confirmedittext'                  => 'A dev confermé soa adrëssa ëd pòsta eletrònica, anans che modifiché dle pàgine. Për piasì, che a convàlida soa adrëssa ën dovrand la pàgina [[Special:Preferences|mè gust]].',
'nosuchsectiontitle'               => 'As peul pa trovesse la session',
'nosuchsectiontext'                => "A l'ha provasse a modifiché na session ch'a-i é pa.
A peul essa stàita tramudà o scancelà an mente ch'a vëdìa la pàgina.",
'loginreqtitle'                    => 'a venta rintré ant ël sistema',
'loginreqlink'                     => 'rintré ant ël sistema',
'loginreqpagetext'                 => "Che a pòrta passiensa, ma a dev $1 për podèj vëdde dj'àutre pàgine.",
'accmailtitle'                     => 'Ciav spedìa.',
'accmailtext'                      => "Na ciav generà a cas për [[User talk:$1|$1]] a l'é stàita mandà a $2.

La ciav për cost neuv cont a peul esse cambià an duvertand la pàgina ''[[Special:ChangePassword|cambia ciav]]''",
'newarticle'                       => '(Neuv)',
'newarticletext'                   => "It ses andàit daré a un colegament a na pàgina che a esist ancó pa.
Për creé la pàgina, ancamin-a a scrive ant lë spassi sì-sota (varda la [[{{MediaWiki:Helppage}}|pàgina d'agiut]] për savèjne ëd pì).
S'it ses sì për eror, sgnaca ël boton '''andaré''' ëd tò navigador.",
'anontalkpagetext'                 => "----''Costa a l'é la pàgina ëd ciaciarade për n'utent anònim che a l'é ancó pa dorbusse un cont, ò pura che a lo deuvra nen. Alora i l'oma da dovré ël nùmer d'adrëssa IP për deje n'identificassion a chiel/chila. S'it ses n'utent anònim e it l'has l'impression d'arsèive dij coment sensa sust, për piasì [[Special:UserLogin/signup|crea un cont]] o [[Special:UserLogin|Intra]] për evité dë fé confusion con dj'àutri utent anònim.''",
'noarticletext'                    => 'Al moment costa pàgina a l\'é veuida.
It peule [[Special:Search/{{PAGENAME}}|sërché costa vos]] andrinta a d\'àutre pàgine, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sërché ant ij registr colegà],
o purament [{{fullurl:{{FULLPAGENAME}}|action=edit}} modìfiché la pàgina adess]</span>.',
'noarticletext-nopermission'       => 'Al moment a-i é pa gnun test an sta pàgina-sì.
It peule [[Special:Search/{{PAGENAME}}|sërché sto tìtol ëd pàgina-sì]] an d\'àutre pàgine,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} sërché j\'argistrassion colegà]</span>.',
'userpage-userdoesnotexist'        => 'Lë stranòm "$1" a l\'é pa registrà. Për piasì ch\'a varda se da bon a veul creé/modifiché sta pàgina.',
'userpage-userdoesnotexist-view'   => 'Ël cont utent "$1" a l\'é pa registrà.',
'blocked-notice-logextract'        => "S'utent a l'é al moment blocà.
'Me arferiment, sì-sota a-i é la dariera anotassion da l'argistr dij blocagi.",
'clearyourcache'                   => "'''Nòta:''' na vira che a l'ha salvà, a peul esse che a-j fasa da manca da passé via la memorisassion (cache) dël sò programa ëd navigassion (browser) për podèj ës-ciairé le modìfiche.
*'''Mozilla / Firefox / Safari:''' Che a ten-a sgnacà ''Shift'' antramentr che a sgnaca col rat ansima a ''Reload'', ò pura che a sgnaca tut ansema ''Ctrl-Shift-R'' (''Cmd-Shift-R'' ansima a j'Apple Mac);
*'''IE:''' che a ten-a sgnacà ''Ctrl'' antramentr che a sgnaca col rat ansima a ''Refresh'', ò pura che a sgnaca tut ansema ''Ctrl-F5'';
*'''Konqueror:''': a basta mach sgnaché ël boton ''Reload'', ò pura sgnaché ''F5'';
*'''Opera''' j'utent a peulo avèj da manca dë vujdé 'd continuo soa memorisassion (cache) andrinta a ''Tools&rarr;Preferences''.",
'usercssyoucanpreview'             => "'''Drita:''' che a deuvra ël boton \"{{int:showpreview}}\" për controlé l'efet ëd sò còdes CSS dnans ëd salvelo.",
'userjsyoucanpreview'              => "'''Drita:''' che a deuvra ël boton «{{int:showpreview}}» për controlé l'efet ëd sò còdes JS dnans ëd salvelo.",
'usercsspreview'                   => "'''Che a varda che lòn che a s-ciàira a l'é nomach na preuva ëd sò CSS.'''
'''A l'é ancó nen stàit salvà!'''",
'userjspreview'                    => "'''Che as visa che a l'é mach antramentr che as fa na preuva ëd sò Javascript, che a l'é ancó pa stàit salvà!'''",
'sitecsspreview'                   => "'''Che a varda che a l'é mach an mente ch'a preuva sto CSS.'''
'''A l'é ancó pa stàit salvà!'''",
'sitejspreview'                    => "'''Che a varda che a l'é mach an mente ch'a preuva sto còdes JavaScript.'''
'''A l'é ancó pa stàit salvà!'''",
'userinvalidcssjstitle'            => "'''Avis:''' A-i é pa gnun-a pel \"\$1\". Che as visa che le pàgine .css e .js che un as fa daspërchiel a deuvro tute minùscole për tìtol, pr'esempi {{ns:user}}:Scaramacaj/vector.css nopà che {{ns:user}}:Scaramacaj/Vector.css.",
'updated'                          => '(Agiornà)',
'note'                             => "'''NÒTA:'''",
'previewnote'                      => "'''Che a ten-a mach present che costa-sì a l'é nomach na PREUVA, e che soa version a l'é ancó pa stàita salvà!'''",
'previewconflict'                  => "Costa preuva a-j mostra ël test dl'artìcol ambelessì dzora. Se a sërn dë salvelo, a l'é parèj che a lo s-ciairëran ëdcò tuti j'àutri Utent.",
'session_fail_preview'             => "'''Darmagi! I l'oma pa podù processé soa modìfica per via che a son përdusse për la stra ij dat ëd session.
Për piasì che a preuva n'àutra vira. Se a dovèissa mai torna riveje sossì, che a preuva a seurte dal sistema e peuj torna a rintré.'''",
'session_fail_preview_html'        => "'''Darmagi! I l'oma nen podù processé soa modìfica ën essend che a son përdusse për la stra ij dat ëd session.'''

''Për via che {{SITENAME}} a lassa mostré còdes HTML nen filtrà, la preuva a l'é stërmà coma precaussion contra a dij possìbij atach fàit an Javascript.''

'''Se sòn a l'era na modìfica normal, për piasì che a preuva a fela n'àutra vira. Se a dovèissa mai torna deje dle gran-e, che a preuva a [[Special:UserLogout|seurte da 'nt ël sistema]] e peuj torna a rintré.'''",
'token_suffix_mismatch'            => "'''Soa modìfica a l'é nen stàita acetà përché sò navigator a l'hai fàit ciadel con ij pont e le vìrgole
ant ël quàder ëd modìfica. La rason che a l'é nen stàit acetà a l'é për evité ch'a-i fasa darmagi al
test ch'a-i é già. Sossì dle vire a riva quand un a deuvra un programa proxy ëd coj un pòch dla Bajòna.'''",
'edit_form_incomplete'             => "'''Quàich part dël formolari ëd modìfica a l'é pa rivà al sërvent; contròla doe vire che toe modìfiche a-i sio anco' e preuva torna.'''",
'editing'                          => 'Modìfica ëd $1',
'editingsection'                   => 'I soma dapress a modifiché $1 (session)',
'editingcomment'                   => 'I soma dapress a modifiché $1 (neuva session)',
'editconflict'                     => "Conflit d'edission: $1",
'explainconflict'                  => "Cheidun d'àutr a l'ha salvà soa version dl'artìcol antramentré che chiel (chila) as prontava la soa.
Ël quàder ëd modìfica dë dzora a mostra ël test ëd l'artìcol coma a resta adess (visadì, lòn che a-i é ant sla Ragnà). Soe modìfiche a stan ant ël quàder dë sota.
Ën volend a peul gionté soe modìfiche ant ël quàder dë dzora.
'''Mach''' ël test ant ël quàder dë dzora a sarà salvà, ën sgnacand ël boton \"{{int:savearticle}}\".",
'yourtext'                         => 'Sò test',
'storedversion'                    => 'Version memorisà',
'nonunicodebrowser'                => "'''A L'EUJ! Sò programa ëd navigassion (browser) a travaja pa giust con lë stàndard unicode. I soma obligà a dovré dij truschin përchè a peula salvesse sò artìcoj sensa problema: ij caràter che a son nen ASCII a jë s-ciairerà ant ël quàder ëd modìfica test coma còdes esadecimaj.'''",
'editingold'                       => "'''CHE A FASA MACH ATENSION: che a sta fasend-je dle modìfiche a na version nen agiornà dl'artìcol.<br />
Se a la salva parèj, lòn che a l'era stàit fàit dapress a sta revision-sì as perdrà d'autut.'''",
'yourdiff'                         => 'Diferense',
'copyrightwarning'                 => "Che a ten-a për piasì present che tute le contribussion a {{SITENAME}} as consìdero dàite sota a na licensa ëd la sòrt $2 (che a varda $1 për avèj pì 'd detaj).
Se a veul nen che sò test a peula esse modificà e distribuì da qualsëssìa përson-a sensa gnun-a limitassion ëd gnun-a sòrt, che a lo buta pa ansima a {{SITENAME}}, ma pitòst che as lo pùblica ansima a un sò sit përsonal.<br />
Ën mandand ës test-sì chiel (chila) as fa garant sota soa responsabilità che ël test a l'ha scrivusslo despërchiel (daspërchila) coma original, ò pura che a l'ha tracopialo da na sorgiss ëd pùblich domini, ò da n'àutra sorgiss dla midema sòrt, ò pura che chiel (chila) a l'ha arseivù autorisassion scrita a dovré sto test e che sòn a peul dimostrelo.<br />
'''DOVRÉ PA MAI DËL MATERIAL COATÀ DA DRIT D'AUTOR (c) SENSA AVÈJ N'AUTORISASSION SCRITA PËR FELO!!!'''",
'copyrightwarning2'                => "Për piasì, che a ten-a present che tute le contribussion a {{SITENAME}} a peulo esse modificà ò scancelà da dj'àutri contributor. Se a veul nen che lòn che a scriv a ven-a modificà sensa limitassion ëd gnun-a sòrt, che a lo manda nen ambelessì.<br />
Ant l'istess temp, ën mandand dël material un as pija la responsabilità dë dì che a l'ha scrivusslo daspërchiel (ò daspërchila), ò pura che a l'ha copialo da na sorgiss ëd domini pùblich, ò pura da 'nt n'àutra sorgiss dla midema sòrt (che a varda $1 për avèj pì d'anformassion).
'''CHE A MANDA PA DËL MATERIAL COATÀ DA DRIT D'AUTOR SENSA AVÈJ AVÙ ËL PËRMESS SCRIT DË FELO!'''",
'longpageerror'                    => "'''EROR: Ël test che a l'ha mandà a l'é longh $1 kb, che a resta pì che ël
lìmit màssim ëd $2 kb. Parèj as peul nen salvesse. A venta che a në fasa vàire
pàgine diferente për rintré ant ij lìmit técnich.'''",
'readonlywarning'                  => "'''Avis: La base dat a l'é stàita blocà për manutension, e donca a podrà pa salvesse soe modìfiche tut sùbit.'''
A peul esse che a-j ven-a còmod copiesse via sò test e butesslo da na part për salvelo peuj.

L'aministrator che a l'ha fàit ël blocagi a l'ha dàit costa spiegassion: $1",
'protectedpagewarning'             => "'''Avis: costa pàgina-sì a l'é stàita blocà an manera che mach j'utent con la qualìfica da aministrator a peulo feje dle modìfiche.'''
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'semiprotectedpagewarning'         => "'''Nòta:''' Costa pàgina-sì a l'é stàita blocà an manera che mach j'utent registrà a peulo modifichela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'cascadeprotectedwarning'          => "'''Tension:''' sta pàgina-sì a l'é stàita blocà an manera che mach j'utent con la qualìfica da aministrator a peulo modifichela, për via che {{PLURAL:\$1|a l'é proteta|a-i intra ant le pàgine protete}} col sistema \"a cascada\":",
'titleprotectedwarning'            => "'''Avis: sta pàgina-sì a l'é stàita blocà an manera che a-i é dabzògn ëd [[Special:ListGroupRights|drit specìfich]] për creela.'''
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'templatesused'                    => '{{PLURAL:$1|Stamp|Stamp}} dovrà dzora a sta pàgina-sì:',
'templatesusedpreview'             => '{{PLURAL:$1|Stamp|Stamp}} dovrà ant sta preuva-sì:',
'templatesusedsection'             => '{{PLURAL:$1|Stamp|Stamp}} dovrà ant sta session-sì:',
'template-protected'               => '(protet)',
'template-semiprotected'           => '(mes-protet)',
'hiddencategories'                 => 'Sta pàgina-sì a fa part ëd {{PLURAL:$1|na categorìa|$1 categorìe}} stërmà:',
'edittools'                        => "<!-- Test ch'a së s-ciàira sot a ij mòduj ëd mòdifica e 'd càrich d'archivi. -->",
'nocreatetitle'                    => 'Creassion ëd pàgine limità',
'nocreatetext'                     => "Cost sit-sì a l'ha limità la possibilità ëd creé dle pàgine neuve.
A peul torné andaré e modifiché na pàgina che a-i é già, ò pura [[Special:UserLogin|rintré ant ël sistema ò deurb-se un cont]].",
'nocreate-loggedin'                => "A l'ha pa ij përmess për creé dle pàgine neuve.",
'sectioneditnotsupported-title'    => "La modìfica dla session a l'é nen prevëdùa",
'sectioneditnotsupported-text'     => "La modìfica dla session a l'é nen prevëdùa an costa pàgina ëd modìfica.",
'permissionserrors'                => 'Eror ant ij përmess',
'permissionserrorstext'            => "A l'ha pa ij përmess dont a fa da manca për {{PLURAL:$1|via che|via che}}:",
'permissionserrorstext-withaction' => "It l'has nen ij përmess për $2, për {{PLURAL:$1|cost motiv|costi motiv}}:",
'recreate-moveddeleted-warn'       => "A l'é an camin ch'a crea torna na pàgina ch'a l'era stàita scancelà.'''

Ch'a varda d'esse sigur ch'a vala la pen-a ëd travajé an sna pàgina parèj.
Për soa comodità i-j mostroma la lista djë scancelament ch'a toco sta pàgina-sì:",
'moveddeleted-notice'              => "Sta pàgina-sì a l'é stàita scancelà.
Ël registr ëd le scancelassion e dij tramud a l'é arportà sota për arferiment.",
'log-fulllog'                      => 'Varda tut ël registr',
'edit-hook-aborted'                => "Modìfica anulà da n'estension.
A-i é pa gnun-e spiegassion.",
'edit-gone-missing'                => 'As peul nen modifiché la pàgina.
A smija che a sia stàita scancelà.',
'edit-conflict'                    => "Conflit d'edission.",
'edit-no-change'                   => "Toa modìfica a l'é stàita ignorà, përchè a l'é pa stàit fàit gnun cambiament al test.",
'edit-already-exists'              => 'As peul nen creesse la pàgina.
A esist già.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Atension:''' Costa pàgina a l'ha tròpe ciamà costose a le fonsions ëd parser.

A dovrìa essnie men che {{PLURAL:$2|$2|$2}}, adess a-i na j'é {{PLURAL:$1|$1|$1}}.",
'expensive-parserfunction-category'       => 'Pàgine con tròpe ciamà costose a le fonsion parser',
'post-expand-template-inclusion-warning'  => "'''Atension:''' La dimension djë stamp anserì a l'é tròp gròssa.
Chèich stamp a saran nen anserì.",
'post-expand-template-inclusion-category' => "Pàgine andoa la dimension djë stamp anserì a l'é tròpa",
'post-expand-template-argument-warning'   => "'''Atension:''' Costa pàgina a conten almanch un paràmeter dë stamp che a l'ha n'espansion tròp gròssa.
Costi paràmeter a son stàit lassà fòra.",
'post-expand-template-argument-category'  => 'Pàgine contenente stamp con paràmeter mancant',
'parser-template-loop-warning'            => 'Trovà na liassa dlë stamp: [[$1]]',
'parser-template-recursion-depth-warning' => 'Passà ël lìmit ëd ricorsion dlë stamp ($1)',
'language-converter-depth-warning'        => 'Passà lìmit ëd profondità dël convertidor ëd lenghe ($1)',

# "Undo" feature
'undo-success' => "Sta modìfica-sì as peul scancelesse. Për piasì, ch'a contròla ambelessì sota për esse sigur che a l'é pro lòn che a veul fé, e peuj ch'as salva lòn ch'a l'ha butà chiel/chila për finì dë scancelé la modìfica ch'a-i era.",
'undo-failure' => "Sta modìfica a l'é nen podusse scancelé për via che a-i son dle contradission antra version antrames.",
'undo-norev'   => "La modìfica a peul nen esse anulà përchè a esist pa o a l'é stàita anulà.",
'undo-summary' => 'Gavà la revision $1 fàita da [[Special:Contributions/$2|$2]] ([[User talk:$2|Ciaciarade]])',

# Account creation failure
'cantcreateaccounttitle' => "As peul pa registresse d'utent",
'cantcreateaccount-text' => "La cression ëd cont neuv a parte da st'adrëssa IP-sì ('''$1''') a l'é stàita blocà da [[User:$3|$3]].

La rason butà da $3 për ël blocagi a l'é stàita: ''$2''",

# History pages
'viewpagelogs'           => 'Smon ij registr dë sta pàgina-sì',
'nohistory'              => "La stòria dle version dë sta pàgina-sì a l'é pa trovasse.",
'currentrev'             => "Version dël dì d'ancheuj",
'currentrev-asof'        => 'Version corenta dij $1',
'revisionasof'           => 'Revision $1',
'revision-info'          => 'Revision al $1; $2',
'previousrevision'       => '←Version pì veja',
'nextrevision'           => 'Revision pì neuva →',
'currentrevisionlink'    => 'Vardé la version corenta',
'cur'                    => 'cor',
'next'                   => 'anans',
'last'                   => 'andaré',
'page_first'             => 'prima',
'page_last'              => 'ùltima',
'histlegend'             => 'Confront antra version diferente: che as selession-a le casele dle version che a veul e peui che a sgnaca ël boton për anandié ël process.<br />
Legenda: (cor) = diferense con la version corenta,
(prim) = diferense con la version prima, c = modìfica cita',
'history-fieldset-title' => 'Varda la cronologìa',
'history-show-deleted'   => 'Mach ëscancelà',
'histfirst'              => 'Prima',
'histlast'               => 'Ùltima',
'historysize'            => '({{PLURAL:$1|1|$1}} byte)',
'historyempty'           => '(veujda)',

# Revision feed
'history-feed-title'          => 'Stòria',
'history-feed-description'    => 'Stòria dla pàgina ansima a sto sit-sì',
'history-feed-item-nocomment' => '$1 al $2',
'history-feed-empty'          => "La pàgina che a l'ha ciamà a-i é pa; a podrìa esse stàita scancelà da 'nt ël sit, ò pura tramudà a n'àutr nòm.

Che a verìfica con la [[Special:Search|pàgina d'arserca]] se a-i fusso mai dj'àutre pàgine che a podèisso andeje bin.",

# Revision deletion
'rev-deleted-comment'         => '(resumé dla modìfica gavà)',
'rev-deleted-user'            => '(stranòm gavà)',
'rev-deleted-event'           => '(assion dël registr gavà)',
'rev-deleted-user-contribs'   => '[nòm utent o adrëssa IP gavà - modìfica stërmà ai contributor]',
'rev-deleted-text-permission' => "Sta revision-sì dla pàgina a l'é staita '''scancelà'''.
A-i peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-deleted-text-unhide'     => "Sta version-sì dla pàgina a l'é stàita '''scancelà'''.
A peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd la scancelassion].
Com aministrator a peul ancó [$1 vardé sta version-sì] se a veul.",
'rev-suppressed-text-unhide'  => "Sta version-sì dla pàgina a l'é stàita '''gavà via'''.
A peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion]. Com aministrator a peul ancó [$1 vëdde le diferense] se a n'ha damanca.",
'rev-deleted-text-view'       => "Costa revision dla pàgina-sì a l'é staita '''scancelà'''.
Coma aministrator chiel a peul ës-ciairela; a peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-suppressed-text-view'    => "Costa revision dla pàgina-sì a l'é stàita '''gavà via'''.
Coma aministrator chiel a peul ës-ciairela; a peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-deleted-no-diff'         => "A peul pa vëdde coste diferense përchè un-a dle revision a l'é stàita '''scancelà'''.
A peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd jë scancelament].",
'rev-suppressed-no-diff'      => "It peule pa vëdde sta diferensa-sì përchè un-a dle revision a l'é stàita '''scanselà'''.",
'rev-deleted-unhide-diff'     => "Un-a dle revision ëd coste diferense a l'é stàita '''scancelà'''.
A peulo essnie dle marche ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].
Com aministrator it peule ancó [$1 vëdde le diferense] se a fà dbzògn.",
'rev-suppressed-unhide-diff'  => "Un-a dle revision dë sta diferensa-sì a l'é stàita '''scancelà'''.
A peulo essnje dij detaj ant ël  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].
Com aministrator a peul ancò [$1 vëdde sta diferensa-sì] s'a veul.",
'rev-deleted-diff-view'       => "Un-a dle revision dë sta diferensa-sì a l'é stàita '''scancelà'''.
Com aministrator it peule ancó vëdde sta diferensa-sì; a peulo ess-ie dij detaj ant ël [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registr ëd le scancelassion].",
'rev-suppressed-diff-view'    => "Un-a dle revision ëd costa diferensa-sì a l'é stàita '''eliminà'''.
Tanme aministrator, a peul ancora s-ciairé costa diferensa; a peulo essje pì 'd detaj ant ël [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registr ëd j'eliminassion].",
'rev-delundel'                => 'mostra/stërma',
'rev-showdeleted'             => 'Mostra',
'revisiondelete'              => 'Scancela/disdëscancela revision',
'revdelete-nooldid-title'     => 'Version nen spessificà',
'revdelete-nooldid-text'      => "A l'ha nen spessificà na version ëd la pàgina për aplicheje costa fonsion, la version spessificà a esist pa, o a preuva a stërmé la version corenta.",
'revdelete-nologtype-title'   => "Gnun-a sòrt d'argistr spessificà",
'revdelete-nologtype-text'    => "A l'ha nen spessificà na sòrt ëd registr për fé costa assion.",
'revdelete-nologid-title'     => 'Intrada dël registr pa giusta',
'revdelete-nologid-text'      => "A l'ha pa spessificà n'event dël registr bërsaj andoa apliché costa fonsion o l'intrada spessificà a esist nen.",
'revdelete-no-file'           => "L'archivi sërcà a-i é pa.",
'revdelete-show-file-confirm' => 'É-lo sigur ëd vorèj vëdde na vërsion scancelà dl\'archivi "<nowiki>$1</nowiki>" da $2 a $3?',
'revdelete-show-file-submit'  => 'Bò!',
'revdelete-selected'          => "'''{{PLURAL:$2|Revision|Revision}} selessionà për [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Event|Event}} dël registr selessionà:'''",
'revdelete-text'              => "Le version scancelà e j'event a së s-ciaireran sempe ant la stòria dla pàgina e ant ij registr, ma sò test al pùblich a j'andrà pì nen.'''
J'àutri aministrator dzora a {{SITENAME}} a saran ancó sempe bon a s-ciairé ël contnù stërmà e a podran disdëscancelelo andré con la midema antërfacia, sempe che a sia nen stàita butà na restrission adissional.",
'revdelete-confirm'           => "Për piasì, ch'a confema ch'a veul fé sòn, ch'as rend cont dle conseguense, e ch'a lo fa an acòrd con [[{{MediaWiki:Policy-url}}|le régole]].",
'revdelete-suppress-text'     => "La scancelassion a dovrìa '''mach''' esse dovrà për cost cas:
* Anformassion përsonaj nen aproprià
*: ''adrësse ëd ca e nùmer ëd teléfon, còdes fiscaj, e via fòrt''",
'revdelete-legend'            => 'But-je coste limitassion-sì a le version scancelà:',
'revdelete-hide-text'         => 'Stërma ël test dla revision',
'revdelete-hide-image'        => "Stërma ël contnù dl'archivi",
'revdelete-hide-name'         => 'Stërma assion e oget',
'revdelete-hide-comment'      => 'Stërma ël coment a la modìfica',
'revdelete-hide-user'         => "Stërma lë stranòm ò l'adrëssa IP dël contributor",
'revdelete-hide-restricted'   => "Stërmé j'anformassion a j'aministrator tan-me a j'àutri",
'revdelete-radio-same'        => '(cambia pa)',
'revdelete-radio-set'         => 'É!',
'revdelete-radio-unset'       => 'Nò',
'revdelete-suppress'          => "Smon-je pa ij dat gnanca a j'aministrator",
'revdelete-unsuppress'        => "Gava le limitassion da 'nt le version ciapà andaré",
'revdelete-log'               => 'Rason:',
'revdelete-submit'            => 'Bùtejlo a {{PLURAL:$1|la version|le version}} selessionà',
'revdelete-logentry'          => 'visibilità dla revision cangià për [[$1]]',
'logdelete-logentry'          => "a l'ha cangiaje visibilità a l'event [[$1]]",
'revdelete-success'           => "'''Visibilità dla revision modificà com ch'as dev.'''",
'revdelete-failure'           => "'''La visibilità dla version a peul pa esse modificà:'''
$1",
'logdelete-success'           => "'''Visibilità dla revision butà coma ch'as dev.'''",
'logdelete-failure'           => "'''La visibilità dël registr a peul pa esse ampostà:'''
$1",
'revdel-restore'              => 'cambia visibilità',
'revdel-restore-deleted'      => 'revision ëscancelà',
'revdel-restore-visible'      => 'revision visìbij',
'pagehist'                    => 'Stòria dla pàgina',
'deletedhist'                 => 'Stòria scancelà',
'revdelete-content'           => 'contnù',
'revdelete-summary'           => 'resumé dla modìfica',
'revdelete-uname'             => 'stranòm',
'revdelete-restricted'        => "a l'ha aplicà le restrission a j'aministrator",
'revdelete-unrestricted'      => "restrission për j'aministrator gavà",
'revdelete-hid'               => 'stërma $1',
'revdelete-unhid'             => 'dëscoata $1',
'revdelete-log-message'       => '$1 për $2 {{PLURAL:$2|revision|revision}}',
'logdelete-log-message'       => '$1 për $2 {{PLURAL:$2|event|event}}',
'revdelete-hide-current'      => "Eror an stërmand l'element datà $2, $1: costa-sì a l'é la version corenta.
A peul pa esse stërmà.",
'revdelete-show-no-access'    => 'Eror an mostrand l\'element datà $2, $1: st\'element-sì a l\'é stàit marcà "riservà".
It peule pa vëddlo.',
'revdelete-modify-no-access'  => 'Eror an modificand l\'element datà $2, $1: st\'element-sì a l\'é stàit marcà "riservà".
It peule pa vëddlo.',
'revdelete-modify-missing'    => "Eror an modificand l'element con ID $1: a-i é pa ant la base ëd dàit!",
'revdelete-no-change'         => "'''Atension:''' l'element datà $2, $1 a l'ha già j'ampostassion ëd visibilità ciamà.",
'revdelete-concurrent-change' => "Eror an modificand l'element $2, $1: sò stat a smija che a sia stàit cambià da cheidun d'àutri antramentre che chiel a provava a modifichelo. Për piasì, ch'a contròla ij registr.",
'revdelete-only-restricted'   => "Eror an stërmand l'element datà $2, $1: it peule pa vieté la vista d'element a j'aministrator sensa ëdcò selessioné un-a dj'àutre opsion ëd visibilità.",
'revdelete-reason-dropdown'   => "*Rason sòlite dë scancelassion
** Violassion dël drit d'autor
** Anformassion përsonaj pa aproprià",
'revdelete-otherreason'       => 'Àutra rason o adissional:',
'revdelete-reasonotherlist'   => 'Àutra rason',
'revdelete-edit-reasonlist'   => 'Modifiché la rason ëd lë scancelament',
'revdelete-offender'          => 'Autor ëd la revision:',

# Suppression log
'suppressionlog'     => 'Registr ëd le scancelassion',
'suppressionlogtext' => "Sota a-i é na lista djë scancelament e dij blocagi che a rësguardo contnù stërmà a j'aministrator.
Beiché la [[Special:IPBlockList|lista dj'IP blocà]] për la lista dij blocagi ativ.",

# Revision move
'moverevlogentry'              => "a l'ha tramudà {{PLURAL:$3|narevision|$3 revision}} da $1 a $2",
'revisionmove'                 => 'Tramudé dle revision da «$1»',
'revmove-explain'              => 'Le revision sì-dapress a saran tramudà da $1 a la pàgina final spessificà. Se la pàgina final a esist nen, a sarà creà. Dësnò, coste revision a saran butà ansema a la stòria dla pàgina.',
'revmove-legend'               => 'Anserì la pàgina oget e ël resumé',
'revmove-submit'               => 'Tramudé le revision vers la pàgina selessionà',
'revisionmoveselectedversions' => 'Tramudé le revision selessionà',
'revmove-reasonfield'          => 'Rason:',
'revmove-titlefield'           => 'Pàgina bërsaj:',
'revmove-badparam-title'       => 'Paràmeter nen bon',
'revmove-badparam'             => "Soa arcesta a conten ëd paràmeter nen bon o ch'a basto pa. Për piasì, ch'a sgnaca «andaré» e ch'a preuva torna.",
'revmove-norevisions-title'    => 'Revision bërsaj nen vàlida',
'revmove-norevisions'          => "A l'ha pa spessificà un-a o vàire revision bërsaj për dovré costa fonsionalità opura la revision ëspessificà a esist nen.",
'revmove-nullmove-title'       => 'Tìtol nen bon',
'revmove-nullmove'             => "La pàgina bërsaj a peul pa esse la pàgina sorgiss.
Ch'a torna andré a la pàgina precedenta e ch'a serna un nòm diferent da «$1».",
'revmove-success-existing'     => "{{PLURAL:$1|Na revision da [[$2]] a l'é stàita|$1 revision da [[$2]] a son ëstàite}} tramudà a la pàgina esistenta [[$3]].",
'revmove-success-created'      => "{{PLURAL:$1|Na revision da [[$2]] a l'é stàita|$1 revision da [[$2]] a son ëstàite}} tramudà a la pàgina [[$3]] pen-a creà.",

# History merging
'mergehistory'                     => 'Buté ansema je stòrie',
'mergehistory-header'              => "Sta pàgina-sì a lassa fene buté le revision ëd na pàgina ansema a cole 'd n'àutra.
Ch'a varda mach che a-i ven-a nen fòra un rabel ant la continuità stòrica.",
'mergehistory-box'                 => 'Fene un-a dle stòrie ëd doe pàgine:',
'mergehistory-from'                => 'Pàgina sorgiss:',
'mergehistory-into'                => "Pàgina 'd destinassion:",
'mergehistory-list'                => "Stòria ch'as peul unifichesse",
'mergehistory-merge'               => "Ambelessì sota a-i son le revision ëd [[:$1]] ch'as peulo butesse ansema a [[:$2]]. Ch'a deuvra la casela për marché l'ùltima version da gionté. Ch'a ten-a da ment che se a nàviga via da sta pàgina-sì sta colòna as veujda.",
'mergehistory-go'                  => "Smon le modìfiche ch'as peulo butesse ansema",
'mergehistory-submit'              => 'Buta ansema le revision',
'mergehistory-empty'               => "Pa gnun-a revision ch'as peula butesse ansema.",
'mergehistory-success'             => '$3 {{PLURAL:$3|revision|revision}} ëd [[:$1]] a son ëstàite butà ansema a [[:$2]] sensa problema.',
'mergehistory-fail'                => "A l'é nen riessusse a buté ansema le revision, për piasì, ch'as contròla la pàgina e ij temp.",
'mergehistory-no-source'           => 'La pàgina sorgiss $1 a-i é pa.',
'mergehistory-no-destination'      => 'La pàgina ëd destinassion $1 a-i é pa.',
'mergehistory-invalid-source'      => "La pàgina sorgiss a l'ha d'avèj un tìtol bon.",
'mergehistory-invalid-destination' => "La pàgina ëd destinassion a l'ha d'avèj un tìtol bon.",
'mergehistory-autocomment'         => 'Butà [[:$1]] andrinta a [[:$2]]',
'mergehistory-comment'             => 'Butà [[:$1]] andrinta a [[:$2]]: $3',
'mergehistory-same-destination'    => "La pagina ëd partensa e cola d'ariv a peulo nen esse le mideme",
'mergehistory-reason'              => 'Rason:',

# Merge log
'mergelog'           => "Registr dj'union",
'pagemerge-logentry' => "a l'ha butà [[$1]] ansema a [[$2]] (revision fin-a a la $3)",
'revertmerge'        => 'Gavé da ansema',
'mergelogpagetext'   => "Ambelessì sota a-i é na lista dj'ùltime vire che la stòria ëd na pàgina a l'é stàita butà ansema a cola 'd n'àutra.",

# Diffs
'history-title'            => 'Cronologìa dle modìfiche ëd "$1"',
'difference'               => '(Diferense antra revision)',
'difference-multipage'     => '(Diferense tra pàgine)',
'lineno'                   => 'Riga $1:',
'compareselectedversions'  => 'Paragon-a le version selessionà',
'showhideselectedversions' => 'Smon-e/stërmé le version selessionà',
'editundo'                 => "buta 'me ch'a l'era",
'diff-multi'               => "({{PLURAL:$1|Na revision antërmedia|$1 revision antërmedie}} ëd {{PLURAL:$2|n'utent|$2 utent}} pa mostrà)",
'diff-multi-manyusers'     => "({{PLURAL:$1|Na revision antërmedia|$1 revision antërmedie}} da pi che $2 {{PLURAL:$2|n'utent|utent}} pa mostrà)",

# Search results
'searchresults'                    => "Arzultà dl'arserca",
'searchresults-title'              => "Arzultà dl'arserca për «$1»",
'searchresulttext'                 => "Për avèj pì d'anformassion ant sl'arserca interna ëd {{SITENAME}}, che a varda [[{{MediaWiki:Helppage}}|Arserca ant la {{SITENAME}}]].",
'searchsubtitle'                   => 'A l\'ha sërcà \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tute le pàgine che a ancamin-o con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tute le pàgine che a men-o a "$1"]])',
'searchsubtitleinvalid'            => 'Domanda "$1"',
'toomanymatches'                   => "Parèj a-i ven fòra tròpa ròba, për piasì, ch'a preuva n'arserca diferenta.",
'titlematches'                     => "Ant ij tìtoj dj'artìcoj",
'notitlematches'                   => "La vos che a l'ha ciamà a l'é pa trovasse antrames aj tìtoj dj'articoj",
'textmatches'                      => "Ant ël test ëd j'artìcoj",
'notextmatches'                    => "La vos che a l'ha ciamà a l'é pa trovasse antrames aj test dj'artìcoj",
'prevn'                            => 'ij {{PLURAL:$1|$1}} prima',
'nextn'                            => 'ij {{PLURAL:$1|$1}} peuj',
'prevn-title'                      => '$1 {{PLURAL:$1|arzultà|arzultà}} prima',
'nextn-title'                      => '$1 {{PLURAL:$1|arzultà|arzultà}} apress',
'shown-title'                      => 'Smon-e $1 {{PLURAL:$1|arzultà|arzultà}} për pàgina',
'viewprevnext'                     => 'Che a varda ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => "Opsion d'arserca",
'searchmenu-exists'                => "'''A-i é na pàgina ciamà \"[[:\$1]]\" dzora a costa wiki'''",
'searchmenu-new'                   => "'''Creé la pàgina «[[:$1]]» ansima a sta wiki-sì!'''",
'searchmenu-new-nocreate'          => '"$1" a l\'é un nòm ëd pàgina pa bon o a peul pa esse creà da ti.',
'searchhelp-url'                   => 'Help:Contnù',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Visualisé le pàgine con sto prefiss-sì]]',
'searchprofile-articles'           => 'Pàgine ëd contnù',
'searchprofile-project'            => "Pàgine d'agiut e ëd proget",
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tut',
'searchprofile-advanced'           => 'Avansà',
'searchprofile-articles-tooltip'   => 'Sërché an $1',
'searchprofile-project-tooltip'    => 'Sërché an $1',
'searchprofile-images-tooltip'     => "Sërché dj'archivi",
'searchprofile-everything-tooltip' => 'Sërché daspërtut (ëdcò ant le pàgine ëd discussion)',
'searchprofile-advanced-tooltip'   => 'Sërché ant jë spassi nominaj përsonalisà',
'search-result-size'               => '$1 ({{PLURAL:$2|un|$2}} mòt)',
'search-result-category-size'      => '{{PLURAL:$1|1 mèmber|$1 mèmber}} ({{PLURAL:$2|1 sot-categorìa|$2 sot-categorìe}}, {{PLURAL:$3|1 archivi|$3 archivi}})',
'search-result-score'              => 'Arlevansa: $1%',
'search-redirect'                  => '(ridiression $1)',
'search-section'                   => '(session $1)',
'search-suggest'                   => 'Vorìi-lo pa dì: $1',
'search-interwiki-caption'         => 'Proget frej',
'search-interwiki-default'         => 'Arzultà da $1:',
'search-interwiki-more'            => '(ëd pì)',
'search-mwsuggest-enabled'         => 'con sugeriment',
'search-mwsuggest-disabled'        => 'gnun sugeriment',
'search-relatedarticle'            => 'Corelà',
'mwsuggest-disable'                => 'Disabilité ij sugeriment AJAX',
'searcheverything-enable'          => 'Sërché ant tùit jë spassi nominaj',
'searchrelated'                    => 'corelà',
'searchall'                        => 'tuti',
'showingresults'                   => "Ambelessì-sota a treuva fin a {{PLURAL:$1|'''1'''|'''$1'''}} arzultà, a parte dal nùmer #'''$2'''.",
'showingresultsnum'                => "Ambelessì-sota a treuva {{PLURAL:$3|'''1'''|'''$3'''}} arzultà a parte da #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Arzultà '''$1''' ëd '''$3'''|Arzultà '''$1 - $2''' ëd '''$3'''}} për '''$4'''",
'nonefound'                        => "'''Nòta''': për stàndard a s'arserca mach an chèich ëspassi nominal.
Ch'a preuva a gionté dnans a soa arserca ël prefiss ''all:'' për sërché an tùit jë spassi nominaj (comprèis le discussion, jë stamp, e via fòrt), o ch'a deuvra lë spassi nominal vorsù com prefiss.",
'search-nonefound'                 => "A-i é gnun arzultà për l'arserca.",
'powersearch'                      => 'Arserca avansà',
'powersearch-legend'               => 'Arserca avansà',
'powersearch-ns'                   => 'Sërché ant jë spassi nominaj:',
'powersearch-redir'                => 'Smon-e le ridiression',
'powersearch-field'                => 'Sërché',
'powersearch-togglelabel'          => 'Buté na marca:',
'powersearch-toggleall'            => 'Tùit',
'powersearch-togglenone'           => 'Gnun',
'search-external'                  => 'Arserca esterna',
'searchdisabled'                   => "L'arserca anterna ëd {{SITENAME}} a l'é nen abilità; për adess a peul prové a dovré un motor d'arserca estern coma Google. (Però che a ten-a da ment che ij contnù ëd {{SITENAME}} listà ant ij motor pùblich a podrìo ëdcò esse nen d'autut agiornà)",

# Quickbar
'qbsettings'               => 'Regolassion dla bara dij menù',
'qbsettings-none'          => 'Gnun',
'qbsettings-fixedleft'     => 'Fissà a la man ësnista',
'qbsettings-fixedright'    => 'Fissà a la man drita',
'qbsettings-floatingleft'  => 'Flotant a la man ësnista',
'qbsettings-floatingright' => 'Flotant a la man drita',

# Preferences page
'preferences'                   => 'Mè gust',
'mypreferences'                 => 'mè gust',
'prefs-edits'                   => 'Nùmer ëd modìfiche fàite:',
'prefsnologin'                  => "A l'é ancó pa rintrà ant ël sistema",
'prefsnologintext'              => 'A deuv esse <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} intrà ant ël sistema]</span> për amposté ij sò gust.',
'changepassword'                => 'Cangé la ciav',
'prefs-skin'                    => 'Facia',
'skin-preview'                  => 'Preuva',
'prefs-math'                    => 'Fòrmule ëd matemàtica',
'datedefault'                   => "Franch l'istess",
'prefs-datetime'                => 'Data e ora',
'prefs-personal'                => "Profil dl'utent",
'prefs-rc'                      => 'Ùltime modìfiche',
'prefs-watchlist'               => 'Ròba che as ten sot euj',
'prefs-watchlist-days'          => 'Vàire dì che a veul ës-ciairé an soa lista ëd lòn che as ten sot euj:',
'prefs-watchlist-days-max'      => 'Al pì 7 di',
'prefs-watchlist-edits'         => 'Vàire modìfiche che a veul ës-ciairé con le funsion avansà:',
'prefs-watchlist-edits-max'     => 'Nùmer màssim: 1000',
'prefs-watchlist-token'         => 'Geton ëd lòn che as ten sot euj:',
'prefs-misc'                    => 'Sòn e lòn',
'prefs-resetpass'               => 'Cangé la ciav',
'prefs-email'                   => 'Opsion ëd pòsta eletrònica',
'prefs-rendering'               => 'Sembiansa',
'saveprefs'                     => 'Salvé ij sò gust',
'resetprefs'                    => 'Buté torna ij "mè gust" coma a-i ero al prinsipi',
'restoreprefs'                  => "Buté torna j'ampostassion dë stàndard",
'prefs-editing'                 => 'Quàder ëd modìfica dël test',
'prefs-edit-boxsize'            => 'Dimension ëd la fnesta ëd modìfica.',
'rows'                          => 'Righe:',
'columns'                       => 'Colòne:',
'searchresultshead'             => "Specifiché soe preferense d'arserca",
'resultsperpage'                => 'Arzultà da mostré për vira pàgina:',
'contextlines'                  => 'Righe ëd test për minca arzultà:',
'contextchars'                  => 'Caràter për riga:',
'stub-threshold'                => 'Valor mìnim për j\'<a href="#" class="stub">anliure a jë sbòss</a>:',
'stub-threshold-disabled'       => 'Disabilità',
'recentchangesdays'             => "Vàire dì smon-e ant j'ùltime modìfiche:",
'recentchangesdays-max'         => '(al pì $1 {{PLURAL:$1|di|di}})',
'recentchangescount'            => 'Nùmer ëd modìfiche da smon-e për stàndard:',
'prefs-help-recentchangescount' => "Sòn a comprend j'ùltime modìfiche, le stòrie dle pàgine e ij registr.",
'prefs-help-watchlist-token'    => "An ampinend sto camp-sì con na ciav segreta as genererà un fluss RSS për la ròba che as ten sot euj.
Chicassìa che a conossa la ciav an sto camp-sì a podrà lese la ròba ch'a ten sot euj, parèj ch'a serna un valor sigur.
Ambelessì a-i é un valor generà a asar che a peul dovré: $1",
'savedprefs'                    => 'Ij sò gust a son ëstàit salvà.',
'timezonelegend'                => 'Fus orari:',
'localtime'                     => 'Ora local:',
'timezoneuseserverdefault'      => 'Dovré lë stàndard dël servent',
'timezoneuseoffset'             => 'Àutr (spessifiché la diferensa)',
'timezoneoffset'                => 'Diferensa oraria¹:',
'servertime'                    => 'Ora dël servent:',
'guesstimezone'                 => "Ciapa sù l'ora da 'nt ël mè programa ëd navigassion (browser)",
'timezoneregion-africa'         => 'Àfrica',
'timezoneregion-america'        => 'América',
'timezoneregion-antarctica'     => 'Antàrtid',
'timezoneregion-arctic'         => 'Àrtich',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Océan Atlàntich',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Euròpa',
'timezoneregion-indian'         => 'Océan Indian',
'timezoneregion-pacific'        => 'Océan Passìfich',
'allowemail'                    => "Lassa che j'àutri utent am mando ëd mëssagi ëd pòsta eletrònica",
'prefs-searchoptions'           => "Opsion d'arserca",
'prefs-namespaces'              => 'Spassi nominaj',
'defaultns'                     => 'Dësnò, sërché an costi spassi nominaj-sì:',
'default'                       => 'stàndard',
'prefs-files'                   => 'Archivi',
'prefs-custom-css'              => 'CSS përsonaj',
'prefs-custom-js'               => 'JS përsonaj',
'prefs-common-css-js'           => 'CSS e JS condividù për tute le pej:',
'prefs-reset-intro'             => 'A peul dovré costa pàgina për amposté torna ij sò gust a coj dë stàndard.
Sòn a peul pa esse anulà.',
'prefs-emailconfirm-label'      => "Conferma dl'adrëssa ëd pòsta eletrònica:",
'prefs-textboxsize'             => 'Dimension ëd la fnestra ëd modìfica',
'youremail'                     => 'Soa adrëssa ëd pòsta eletrònica:',
'username'                      => 'Stranòm:',
'uid'                           => "ID dl'utent:",
'prefs-memberingroups'          => 'Mèmber {{PLURAL:$1|dla partìa|dle partìe}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Data ëd registrassion:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'Nòm vèir:',
'yourlanguage'                  => 'Lenga:',
'yourvariant'                   => 'Variant:',
'yournick'                      => 'Sò stranòm (për firmé):',
'prefs-help-signature'          => 'Ij coment an sle pàgine ëd discussion a dovrìo esse firmà con "<nowiki>~~~~</nowiki>" che a sarà convertì ant soa firma e orari.',
'badsig'                        => "Soa firma a l'é nen giusta, che a controla j'istrussion HTML.",
'badsiglength'                  => "Sò stranòm a l'é tròp longh.
A deuv nen esse pì longh che $1 {{PLURAL:$1|caràter|caràter}}.",
'yourgender'                    => 'Sess:',
'gender-unknown'                => 'Nen spessificà',
'gender-male'                   => 'Òm',
'gender-female'                 => 'Fomna',
'prefs-help-gender'             => "Opsional: a l'é dovrà për adaté ël programa al géner.
Costa anformassion a sarà pùblica.",
'email'                         => 'Pòsta eletrònica',
'prefs-help-realname'           => '* Nòm vèir (opsional): se i sërne da butelo ambelessì a sarà dovrà për deve mérit ëd vòstr travaj.',
'prefs-help-email'              => "L'adrëssa ëd pòsta eletrònica a l'é opsional, ma a-i n'a-i é dabzògn për torna amposté la ciav, s'a dovèissa  dësmentié soa ciav.",
'prefs-help-email-others'       => "It peule ëdcò serne ëd lassé che àutri at contato travers toa pàgina utend o ëd discussion sensa dabzògn d'arvelé toa identità.",
'prefs-help-email-required'     => "A-i va l'adrëssa ëd pòsta eletrònica.",
'prefs-info'                    => 'Anformassion ëd base',
'prefs-i18n'                    => 'Antërnassionalisassion',
'prefs-signature'               => 'Firma',
'prefs-dateformat'              => 'Formà dla data',
'prefs-timeoffset'              => "Diferensa d'ora",
'prefs-advancedediting'         => 'Opsion avansà',
'prefs-advancedrc'              => 'Opsion avansà',
'prefs-advancedrendering'       => 'Opsion avansà',
'prefs-advancedsearchoptions'   => 'Opsion avansà',
'prefs-advancedwatchlist'       => 'Opsion avansà',
'prefs-displayrc'               => 'Opsion ëd visualisassion',
'prefs-displaysearchoptions'    => 'Opsion ëd visualisassion',
'prefs-displaywatchlist'        => 'Opsion ëd visualisassion',
'prefs-diffs'                   => 'Diferense',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'A smija bon',
'email-address-validity-invalid' => "A l'é ciamà n'adrëssa bon-a!",

# User rights
'userrights'                     => "Gestion dij drit dj'utent",
'userrights-lookup-user'         => "Gestion dle partìe d'utent",
'userrights-user-editname'       => 'Che a buta në stranòm:',
'editusergroup'                  => "Modifiché le partìe d'utent",
'editinguser'                    => "Modìfica dij drit ëd l'utent '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => "Modifiché le partìe dl'utent",
'saveusergroups'                 => "Salvé le partìe d'utent",
'userrights-groupsmember'        => "A l'é andrinta a:",
'userrights-groupsmember-auto'   => 'Mèmber amplìssit ëd:',
'userrights-groups-help'         => "A peul cambié le partìe anté ch'a l'é st'utent-sì:
* Na casela marcà a veul dì che l'utent a l'é an cola partìa.
* Na casela nen marcà a veul dì che l'utent a l'é pa an cola partìa.
* N'asterisch (*) a veul dì che a peul pa gavé cola partìa na vira ch'a l'abia giontala, o viceversa.",
'userrights-reason'              => 'Rason:',
'userrights-no-interwiki'        => "A l'ha pa ij përmess dont a fa da manca për podèj cambieje ij drit a dj'utent ansima a dj'àutre wiki.",
'userrights-nodatabase'          => "La base ëd dat $1 a-i é pa, ò pura a l'é nen local.",
'userrights-nologin'             => "A l'ha da [[Special:UserLogin|rintré ant ël sistema]] con un cont da aministrator për podej-je dé dij drit a j'utent.",
'userrights-notallowed'          => "A l'ha pa ij përmess dont a fa da manca për podej-je dé dij drit a j'utent.",
'userrights-changeable-col'      => "Partìe ch'a peul cambié",
'userrights-unchangeable-col'    => "Partìe ch'a peul pa cambié",
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Partìa:',
'group-user'          => 'Utent',
'group-autoconfirmed' => "Utent ch'a son convalidasse daspërlor",
'group-bot'           => 'Trigomiro',
'group-sysop'         => 'Aministrator',
'group-bureaucrat'    => 'Mangiapapé',
'group-suppress'      => 'Supervisor',
'group-all'           => '(utent)',

'group-user-member'          => 'utent',
'group-autoconfirmed-member' => "utent ch'a l'é convalidasse daspërchiel/chila",
'group-bot-member'           => 'trigomiro',
'group-sysop-member'         => 'aministrator',
'group-bureaucrat-member'    => 'mangiapapé',
'group-suppress-member'      => 'supervisor',

'grouppage-user'          => '{{ns:project}}:Utent',
'grouppage-autoconfirmed' => "{{ns:project}}:Utent ch'a son convalidasse daspërlor",
'grouppage-bot'           => '{{ns:project}}:Trigomiro',
'grouppage-sysop'         => '{{ns:project}}:Aministrator',
'grouppage-bureaucrat'    => '{{ns:project}}:Mangiapapé',
'grouppage-suppress'      => '{{ns:project}}:Supervisor',

# Rights
'right-read'                  => 'Lese le pàgine',
'right-edit'                  => 'Modifiché le pàgine',
'right-createpage'            => 'Creé dle pàgine (che a son pa dle pàgine ëd discussion)',
'right-createtalk'            => 'Creé dle pàgine ëd discussion',
'right-createaccount'         => 'Creé dij cont utent neuv',
'right-minoredit'             => 'Marché le modìfiche com cite',
'right-move'                  => 'Tramudé le pàgine',
'right-move-subpages'         => 'Tramudé dle pàgine con soe sot-pàgine',
'right-move-rootuserpages'    => "Tramudé le pàgine prinsipaj ëd j'utent",
'right-movefile'              => "Tramudé j'archivi",
'right-suppressredirect'      => 'Creé nen ëd ridiression da la pàgina sorgiss an tramudand le pàgine',
'right-upload'                => "Carié d'archivi",
'right-reupload'              => "Coaté n'archivi esistent",
'right-reupload-own'          => "Coaté d'archivi esistent carià da l'utent midem",
'right-reupload-shared'       => "Coaté an local j'archivi ant ël depòsit dij mojen partagià",
'right-upload_by_url'         => "Carié dj'archivi da n'adrëssa an sl'aragnà",
'right-purge'                 => 'Polidé la memòria local ëd na pàgina sensa ciamé conferma',
'right-autoconfirmed'         => 'Modifiché le pàgine semi-protegiùe',
'right-bot'                   => 'Esse tratà com un process automàtich',
'right-nominornewtalk'        => "Fé nen comparì l'avis ëd mëssagi neuv, an fasend ëd modìfiche cite a le pàgine ëd discussion",
'right-apihighlimits'         => "Dovré ël lìmit pì àut ant j'anterogassion API",
'right-writeapi'              => "Dovré l'API dë scritura",
'right-delete'                => 'Scancelé dle pàgine',
'right-bigdelete'             => 'Scancelé dle pàgine con na stòria longa',
'right-deleterevision'        => 'Scancelé e disdëscancelé na version ëspessìfica ëd na pàgina',
'right-deletedhistory'        => 'Vardé le revision ëscancelà ëd la stòria, sensa sò test',
'right-deletedtext'           => 'Vëdde ël test ëscancelà e le modìfiche antra le revision ëscancelà',
'right-browsearchive'         => 'Sërché dle pàgine scancelà',
'right-undelete'              => 'Arcuperé na pàgina',
'right-suppressrevision'      => "Esaminé e arcuperé le revision stërmà da j'aministrator",
'right-suppressionlog'        => 'Vardé ij registr privà',
'right-block'                 => "Bloché le modìfiche d'àutri utent",
'right-blockemail'            => "Bloché n'utent da mandé 'd mëssagi an pòsta eletrònica",
'right-hideuser'              => 'Bloché un nòm utent, stërmandlo al pùblich',
'right-ipblock-exempt'        => "Dëscavalché ij blocagi ëd j'IP, ij blocagi automàtich e ij blocagi ëd partìe d'IP",
'right-proxyunbannable'       => "Dëscavalché ij blòch automatich dij servent d'anonimà",
'right-unblockself'           => 'Dësblochesse da soj',
'right-protect'               => 'Cambié ij livej ëd protession e modifiché le pàgine protegiùe',
'right-editprotected'         => 'Modifiché le pàgine protegiùe (sensa protession a cascada)',
'right-editinterface'         => "Modifiché l'antërfacia utent",
'right-editusercssjs'         => "Modifiché j'archivi CSS e JavaScript d'àutri utent",
'right-editusercss'           => "Modifiché j'archivi CSS d'àutri utent",
'right-edituserjs'            => "Modifiché j'archivi JavaScript d'àutri utent",
'right-rollback'              => "Gavé an pressa le modìfiche ëd l'ùltim utent che a l'ha modificà na pàgina particolar",
'right-markbotedits'          => "Marché le modìfiche tirà andré com modìfiche d'un trigomiro",
'right-noratelimit'           => "Nen esse tocà dal lìmit d'assion",
'right-import'                => "Amporté dle pàgine da d'àutre wiki",
'right-importupload'          => "Amporté dle pàgine da n'archivi carià",
'right-patrol'                => "Marché le modìfiche dj'àutri com verificà",
'right-autopatrol'            => 'Avèj na pròpria modìfica automaticament marcà com verificà',
'right-patrolmarks'           => "Vëdde le marche ëd verìfica ant j'ùltime modìfiche",
'right-unwatchedpages'        => 'Vëdde na lista dle pàgine nen cudìe',
'right-trackback'             => "Gionté dj'anliure anverse",
'right-mergehistory'          => 'Fonde la stòria dle pàgine',
'right-userrights'            => "Modifiché tùit ij drit ëd n'utent",
'right-userrights-interwiki'  => "Modifiché ij drit utent dj'utent ansima a d'àutre wiki",
'right-siteadmin'             => 'Bloché e dësbloché la base ëd dàit',
'right-reset-passwords'       => "Modifiché le ciav d'àutri utent",
'right-override-export-depth' => 'Esporté le pàgine ancludend le pàgine colegà fin-a a na profondeur ëd 5',
'right-sendemail'             => "Mandé un mëssagi an pòsta eletrònica a j'àutri utent",
'right-revisionmove'          => 'Tramudé le revision',
'right-disableaccount'        => 'Disabilité dij cont',

# User rights log
'rightslog'      => "Argistr dij drit ëd j'utent",
'rightslogtext'  => "Costa a l'é na lista dij cambiament aj drit ëd j'utent.",
'rightslogentry' => "a l'ha tramudà $1 da 'nt la partìa $2 a la partìa $3",
'rightsnone'     => '(gnun)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lese sta pàgina-sì',
'action-edit'                 => 'modifiché costa pàgina',
'action-createpage'           => 'creé dle pàgine',
'action-createtalk'           => 'creé dle pàgine ëd discussion',
'action-createaccount'        => 'creé ës cont utent',
'action-minoredit'            => 'marché sta modìfica-sì com minor',
'action-move'                 => 'tramudé sta pàgina-sì',
'action-move-subpages'        => 'tramudé sta pàgina-sì e soe sot-pàgine',
'action-move-rootuserpages'   => "tramudé le pàgine prinsipaj dj'utent",
'action-movefile'             => "tramudé cost'archivi",
'action-upload'               => "carié st'archivi",
'action-reupload'             => 'coaté cost archivi esistent',
'action-reupload-shared'      => 'sorpassé cost archivi ant un depòsit partagià',
'action-upload_by_url'        => "carié s'archivi da n'adrëssa an sl'Aragnà",
'action-writeapi'             => "dovré l'API dë scritura",
'action-delete'               => 'scancelé sta pàgina-sì',
'action-deleterevision'       => 'scancelé sta revision-sì',
'action-deletedhistory'       => 'vardé la stòria scancelà dë sta pàgina-sì',
'action-browsearchive'        => 'sërché dle pàgine scancelà',
'action-undelete'             => 'arcuperé sta pàgina-sì',
'action-suppressrevision'     => 'rivëdde e arcuperé sta revision stërmà-sì',
'action-suppressionlog'       => 'vardé sto registr privà-sì',
'action-block'                => 'bloché cost utent-sì a modifiché',
'action-protect'              => 'cambié ij livej ëd protession për sta pàgina-sì',
'action-import'               => "amporté costa pàgina da n'àutra wiki",
'action-importupload'         => "amporté costa pàgina da n'archivi carià",
'action-patrol'               => "marché la modìfica dj'àutri com verificà",
'action-autopatrol'           => 'avèj soe modìfiche marcà com verificà',
'action-unwatchedpages'       => 'vardé la lista dle pàgine che gnun a ten sot-euj',
'action-trackback'            => "spedì n'anliura anversa",
'action-mergehistory'         => 'fonde la stòria dë sta pàgina-sì',
'action-userrights'           => "modifiché tùit ij drit dj'utent",
'action-userrights-interwiki' => "modifiché ij drit ëd j'utent ansima a d'àutre wiki",
'action-siteadmin'            => 'bloché o dësbloché la base ëd dàit',
'action-revisionmove'         => 'tramudé dle revision',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|modìfica|modìfiche}}',
'recentchanges'                     => 'Ùltime modìfiche',
'recentchanges-legend'              => "Opsion dj'ùltime modìfiche",
'recentchangestext'                 => 'An costa pàgina as ten cont dle modìfiche pì recente a la wiki.',
'recentchanges-feed-description'    => 'Trassé le modìfiche dla wiki pì davzin-e ant ël temp an cost fluss.',
'recentchanges-label-newpage'       => "Sta modìfica-sì a l'ha creà na neuva pàgina",
'recentchanges-label-minor'         => "Costa a l'é na modìfica cita",
'recentchanges-label-bot'           => "Sa modìfica a l'é stàita fàita da un trigomiro",
'recentchanges-label-unpatrolled'   => "Sta modìfica-sì a l'é pa ancó stàita verificà",
'rcnote'                            => "Ambelessì sota a-i {{PLURAL:$1|é '''1''' modìfica|son j'ùltime '''$1''' modìfiche}} ant j'ùltim {{PLURAL:$2|di|'''$2''' di}}, a parte da $5 dël $4.",
'rcnotefrom'                        => ' Ambelessì sota a-i é la lista dle modìfiche da <b>$2</b> (fin-a a <b>$1</b>).',
'rclistfrom'                        => 'Mostré le modìfiche a parte da $1',
'rcshowhideminor'                   => '$1 le modìfiche cite',
'rcshowhidebots'                    => '$1 ij trigomiro',
'rcshowhideliu'                     => "$1 j'utent registrà",
'rcshowhideanons'                   => "$1 j'utent anònim",
'rcshowhidepatr'                    => '$1 le modìfiche verificà',
'rcshowhidemine'                    => '$1 mie modìfiche',
'rclinks'                           => "Mostré j'ùltime $1 modìfiche ëd j'ùltim $2 dì<br />$3",
'diff'                              => 'dif.',
'hist'                              => 'stòria',
'hide'                              => 'Stërmé',
'show'                              => 'Smon-e',
'minoreditletter'                   => 'c',
'newpageletter'                     => 'N',
'boteditletter'                     => 't',
'number_of_watching_users_pageview' => "[tnùa sot-euj da {{PLURAL:$1|n'utent|$1 utent}}]",
'rc_categories'                     => 'Limité a le categorìe (che a jë scriva separand-je antra \'d lor con un "|")',
'rc_categories_any'                 => 'Qualsëssìa',
'newsectionsummary'                 => '/* $1 */ session neuva',
'rc-enhanced-expand'                => 'Mostré ij detaj (a-i é da manca ëd JavaScript)',
'rc-enhanced-hide'                  => 'Stërmé ij detaj',

# Recent changes linked
'recentchangeslinked'          => 'Modìfiche colegà',
'recentchangeslinked-feed'     => 'Modìfiche colegà',
'recentchangeslinked-toolbox'  => 'Modìfiche colegà',
'recentchangeslinked-title'    => 'Modìfiche ch\'a-i intro con "$1"',
'recentchangeslinked-noresult' => "Ant ël moment dont ës parla a-i é pa staie gnun-a modìfica a le pàgine con dj'anliure ch'a men-o ambelessì.",
'recentchangeslinked-summary'  => "Costa a l'é na lista ëd modìfiche fàite da pòch a pàgine colegà a cola spessificà (o a mèmber ëd na categorìa spessificà).
Le pàgine dzora a [[Special:Watchlist|la lista ëd lòn ch'as ten sot-euj]] a resto marcà an '''grassèt'''.",
'recentchangeslinked-page'     => 'Nòm ëd la pàgina:',
'recentchangeslinked-to'       => 'Mostré nopà le modìfiche a le pàgine colegà a cola dàita',

# Upload
'upload'                      => "Carié n'archivi",
'uploadbtn'                   => "Carié l'archivi",
'reuploaddesc'                => "Chité e torné al formolari për carié dj'archivi",
'upload-tryagain'             => "Mandé la descrission ëd l'archivi modificà",
'uploadnologin'               => 'Nen rintrà ant ël sistema',
'uploadnologintext'           => "A dev [[Special:UserLogin|rintré ant ël sistema]] për podèj carié dj'archivi.",
'upload_directory_missing'    => 'Ël repertòri ëd caria ($1) a-i é nen e a peul pa esse creà dal servent.',
'upload_directory_read_only'  => "Ël servent ëd l'aragnà a-i la fa nen a scrive ansima a la diretris ëd càrich ($1).",
'uploaderror'                 => 'Eror dëmentré che as cariava',
'upload-recreate-warning'     => "'''Atension: n'archivi con col nòm a l'é già stàit ëscancelà o tramudà.'''

Ël registr dle scancelassion e dij tramud për sta pàgina a l'é butà ambelessì për comodità:",
'uploadtext'                  => "Dovra ël formolari sì-sota për carié dj'archivi.
Për vardé ò sërché dle figure già carià, ch'a vada an sla [[Special:FileList|lista dle figure]], ij (ri)càrich a son ëdcò registrà ant ël [[Special:Log/upload|registr dij càrich]], jë scancelament ant ël [[Special:Log/delete|registr djë scancelament]].

Për buté na figura ant n'artìcol, dovré n'anliura ant un-a dle forme sì sota:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' për dovré la version pien-a dla figura
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' për dovré na dimension ëd 200 pontin ant un quàder a la bordura snistra con 'alt text' com descrission
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' për coleghé diretament la figura sensa fé vëdde l'archivi",
'upload-permitted'            => "Sòrt d'archivi consentìe: $1.",
'upload-preferred'            => "Sòrt d'archivi preferìe: $1.",
'upload-prohibited'           => "Sòrt d'archivi proibìe: $1.",
'uploadlog'                   => 'Registr dij càrich',
'uploadlogpage'               => 'Registr dij càrich',
'uploadlogpagetext'           => "Ambelessì-sota a-i é na lista dj'ùltim archivi carià.
Beiché la [[Special:NewFiles|galarìa dj'archivi neuv]] për na presentassion pì visual.",
'filename'                    => "Nòm dl'archivi",
'filedesc'                    => 'Oget',
'fileuploadsummary'           => "Detaj dl'archivi:",
'filereuploadsummary'         => "Modìfiche dl'archivi:",
'filestatus'                  => "Situassion dij drit d'autor:",
'filesource'                  => 'Sorgiss:',
'uploadedfiles'               => 'Archivi carià',
'ignorewarning'               => "Lassé perde j'avis e salvé an tute le manere",
'ignorewarnings'              => "Lassé perde j'avis",
'minlength1'                  => "Ij nòm ëd j'archivi a devo esse longh almanch un caràter.",
'illegalfilename'             => 'Ël nòm d\'archivi "$1" a l\'ha andrinta dij caràter che as peulo pa dovresse ant ij tìtoj dle pàgine. Për piasì che a-j cangia \'d nòm e peui che a torna a carielo.',
'badfilename'                 => 'Ël nòm dl\'archivi a l\'é stait cambià an "$1".',
'filetype-mime-mismatch'      => 'L\'estension dl\'archivi ".$1" a rispeta pa la sòrt ëd MIME trovà për l\'archivi ($2).',
'filetype-badmime'            => 'J\'archivi dla sòrt MIME "$1" as peulo pa carié.',
'filetype-bad-ie-mime'        => 'As peul pa carié st\'archivi-sì përchè Internet Explorer a podrìa considerelo com "$1", che a l\'é na rassa d\'archivi vietà e potensialment pericolos.',
'filetype-unwanted-type'      => "'''\".\$1\"''' a l'é na sòrt d'archivi ch'as pija nen ëd bon-a veuja.
{{PLURAL:\$3|La sòrt preferìa a l'é|Le sòrt preferìe a son}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' {{PLURAL:\$4|a l'é na sòrt d'archivi proibìa|a son ëd sòrt d'archivi proibìe}}.
{{PLURAL:\$3|Sòrt d'archivi consentìa a l'é|Sòrt d'archivi consentìe a son}} \$2.",
'filetype-missing'            => "A l'archivi a-j manca l'estension (pr'esempi \".jpg\").",
'empty-file'                  => "L'archivi ch'a l'ha mandà a l'era veuid.",
'file-too-large'              => "L'archivi ch'a l'ha mandà a l'era tròp gròss.",
'filename-tooshort'           => "Ël nòm ëd l'archivi a l'é tròp curt.",
'filetype-banned'             => "Costa sòrt d'archivi a l'é proibìa.",
'verification-error'          => "Cost archivi a l'ha pa passà la verìfica dj'archivi.",
'hookaborted'                 => "La modìfica ch'a l'ha provà a fé a l'é stàita blocà dal gancio ëd n'estension.",
'illegal-filename'            => "Ël nòm dl'archivi a l'é nen consentì.",
'overwrite'                   => "Dzorascrive ansima a n'archivi esistent a l'é nen përmëttù.",
'unknown-error'               => "A l'é capitaje n'eror nen conossù.",
'tmp-create-error'            => "A l'é nen podusse creé l'archivi temporani.",
'tmp-write-error'             => "Eror dë scritura dl'archivi temporani.",
'large-file'                  => "La racomandassion a l'é che j'archivi a sio nen pì gròss che $1; st'archivi-sì a l'amzura $2.",
'largefileserver'             => "St'archivi-sì a resta pì gròss che lòn che la màchina sentral a përmet.",
'emptyfile'                   => "L'archivi che a l'ha pen-a carià a smija veujd.
Sòn a podrìa esse rivà përchè che chiel a l'ha scrivù mal ël nòm dl'archivi midem.
Për piasì che a contròla se a l'é pro cost l'archivi che a veul carié.",
'fileexists'                  => "N'archivi con ës nòm-sì a-i é già, për piasì che a contròla '''<tt>[[:$1]]</tt>''' se a l'é pa sigur dë vorèj cangelo.
[[$1|thumb]]",
'filepageexists'              => "La pàgina ëd descrission për st'archivi-sì a l'é già stàita creà an '''<tt>[[:$1]]</tt>''', mach ch'a-i é gnun archivi ch'as ciama parèj.
Lòn ch'a buta për somari as ës-ciairerà nen ant la pàgina ëd descrission.
Për podèj buté sò somari a l'ha da modifichesse la pàgina a man.
[[$1|thumb]]",
'fileexists-extension'        => "N'archivi con ës nòm-sì a-i é già: [[$2|thumb]]
* Nòm dl'archivi ch'as carìa: '''<tt>[[:$1]]</tt>'''
* Nòm dl'archivi ch'a-i é già: '''<tt>[[:$2]]</tt>'''
Për piasì, ch'a serna un nòm diferent.",
'fileexists-thumbnail-yes'    => "L'archivi a jë smija a na ''figurin-a''. [[$1|thumb]]
Për piasì, ch'a contròla l'archivi '''<tt>[[:$1]]</tt>'''.
S'a l'é la midema figura a amzura pijn-a, a veul dì ch'a fa nen dë manca dë carié na figurin-a.",
'file-thumbnail-no'           => "Ël nòm dl'archivi as anandia con '''<tt>$1</tt>'''.
A jë smija a na ''figurin-a''.
Se a l'ha na figura a amzura pijn-a a l'é mej ch'a carìa cola-lì, dësnò ch'a-j cangia nòm a l'archivi, për piasì.",
'fileexists-forbidden'        => "Belavans n'archivi con ës nòm-sì a-i é già, donca ël nòm as peul pa pì dovresse.
Se a veul ancó cariè sò archivi, për piasì ch'a torna andré e ch'a deuvra n'àutr nòm. [[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Belavans n'archivi con ës nòm-sì ant la diretris dj'archivi condivis a-i é già.
Se a veul ancó carié sò archivi, për piasì ch'a torna andré e ch'a deuvra un nòm diferent. [[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "S'archivi a l'é un duplicà ëd {{PLURAL:$1|cost-sì|costi-sì}}:",
'file-deleted-duplicate'      => "N'archivi idéntich a cost-sì ([[:$1]]) a l'é scàit ëscancelà an passà.
A dovrìa controlé la stòria djë scancelament ëd l'archivi prima ëd carielo torna.",
'uploadwarning'               => 'Avis che i soma dapress a carié',
'uploadwarning-text'          => "Për piasì, ch'a modìfica la descrission ëd l'archivi sì-sota e ch'a preuva torna.",
'savefile'                    => "Salvé l'archivi",
'uploadedimage'               => 'a l\'ha carià "[[$1]]"',
'overwroteimage'              => 'a l\'ha carìa na version neuva ëd "[[$1]]"',
'uploaddisabled'              => 'Càrich blocà.',
'copyuploaddisabled'          => "Ël càrich për mojen ëd n'adrëssa dl'aragnà a l'é disabilità.",
'uploadfromurl-queued'        => "Sò càrich a l'é stàit butà an coa.",
'uploaddisabledtext'          => "La possibilità ëd carié dj'archivi a l'é staita disabilità.",
'php-uploaddisabledtext'      => "Ij cariament d'archivi a son disabilità an PHP.
Për piasì, ch'a controla l'ampostassion file_uploads.",
'uploadscripted'              => "St'archivi-sì a l'ha andrinta chèich-còs (dël còdes HTML ò pura un senari) che a podrìa esse travajà mal da chèich programa ëd navigassion.",
'uploadvirus'                 => "St'archivi-sì a l'han andrinta un '''vìrus!''' Detaj: $1",
'uploadjava'                  => "L'archivi a l'é un file ZIP ch'a conten un file Java .class.
As peul pa cariesse file Java, përché a peulo causé che le restrission ëd sicurëssa a sio superà.",
'upload-source'               => 'Archivi sorgiss',
'sourcefilename'              => "Nòm dl'archivi sorgiss:",
'sourceurl'                   => "Adrëssa dl'aragnà sorgiss:",
'destfilename'                => "Nòm dl'archivi ëd destinassion:",
'upload-maxfilesize'          => "Dimension màssima ëd l'archivi: $1.",
'upload-description'          => "Descrission dl'archivi",
'upload-options'              => 'Opsion për carié',
'watchthisupload'             => "Ten-e d'euj s'archivi.",
'filewasdeleted'              => "N'archivi con ës nòm-sì a l'é già stàit carià e peui scancelà. Për piasì, che a verìfica $1 anans che carielo n'àutra vira.",
'upload-wasdeleted'           => "'''Dossman: a l'é antramentr ch'a carìa torna n'archivi ch'a l'era dëscancelasse.'''

Për piasì, ch'a contròla s'a val la pen-a dë felo.
Për soa comodità, ambelessì a-i son ij dat dla scancelament:",
'filename-bad-prefix'         => "Ël nòm dl'archivi ch'a l'é dapress a carié as anandia për '''\"\$1\"''', ch'a l'é un nòm sensa sust, për sòlit butà-lì n'aotomàtich da le màchine fotogràfiche digitaj, basta ch'a-i në sia un. Për piasì, ch'a-j daga a sò archivi un nòm ch'a disa lòn ch'a l'é.",
'filename-prefix-blacklist'   => " #<!-- ch'a lassa sta riga-sì tanme ch'a l'é --> <pre>
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
'upload-success-subj'         => 'Carià con sucess',
'upload-success-msg'          => "A l'ha carià da [$2] për da bin. Lòn ch'a l'ha carià a l'é disponìbil ambelessì: [[:{{ns:file}}:$1]]",
'upload-failure-subj'         => 'Problema a carié',
'upload-failure-msg'          => "A-i é staje un problema con lòn ch'a l'ha carià da [$2]:

$1",
'upload-warning-subj'         => "Avis antramentre ch'as caria",
'upload-warning-msg'          => "A-i era un problema con lòn ch'a l'ha carià da [$2]. A peul artorné al [[Special:Upload/stash/$1|formolari për carié]] për corege ël problema.",

'upload-proto-error'        => 'Protocòl cioch',
'upload-proto-error-text'   => "Për carié da dij servent lontan a venta buté dj'anliure ch'as anandio për <code>http://</code> ò pura <code>ftp://</code>.",
'upload-file-error'         => 'Eror antern',
'upload-file-error-text'    => "A l'é rivaie n'eror antern dëmentrè che as fasìa n'archivi provisòri ant sël servent.
Për piasì, ch'as butà an comunicassion con n'[[Special:ListUsers/sysop|aministrator]].",
'upload-misc-error'         => "Eror nen identificà antramentr ch'as cariava",
'upload-misc-error-text'    => "A l'é staie n'eror nen identificà dëmentrè ch'as cariava chèich-còs.
Për piasì, ch'a varda che soa anliura a sia bon-a e che a l'arsponda e peuj ch'a preuva torna.
Se a-i riva sossì n'àotra vira, ch'as buta an comunicassion con n'[[Special:ListUsers/sysop|aministrator]].",
'upload-too-many-redirects' => "L'adrëssa dl'aragnà a l'avìa tròpe ridiression",
'upload-unknown-size'       => 'Dimension pa conossùa',
'upload-http-error'         => "A l'é staje n'eror HTTP: $1.",

# ZipDirectoryReader
'zip-file-open-error' => "N'eror a l'é capità an dorbend ël file për ij contròj ZIP.",
'zip-wrong-format'    => "Ël file specificà a l'é pa un file ZIP.",
'zip-bad'             => "Ël file a l'é un file ZIP brëccà o autriment pa lesìbil.
A peul pa esse controlà da bin për la sicurëssa.",
'zip-unsupported'     => "Ël file a l'é un file ZIP ch'a dòvra funzion ZIP pa apogià da MediaWiki.
A peul pa esse controlà da bin pël la sicurëssa.",

# Special:UploadStash
'uploadstash'          => "Memorisassion d'amportassion",
'uploadstash-summary'  => "Sta pàgina a dà acess a d'archivi ch'a son carià (o an mente ch'as cario) ma a son pa anco' publicà an sla wiki. Costi archivi a son pa visìbij a gnun gavà a l'utent ch'a l'ha cariaje.",
'uploadstash-clear'    => "Scancelé j'archivi an memòria",
'uploadstash-nofiles'  => "A l'han gnun archivi an memòria d'amportassion.",
'uploadstash-badtoken' => "L'esecussion dë st'assion a l'é pa andàita bin, miraco përchè toe credensiaj ëd modìfica a son scadùe. Preuva torna.",
'uploadstash-errclear' => "La scancelassion ëd j'archivi a l'é falìa.",
'uploadstash-refresh'  => "Agiorné la lista dj'archivi",

# img_auth script messages
'img-auth-accessdenied' => 'Acess negà',
'img-auth-nopathinfo'   => "PATH_INFO mancant.
Sò servent a l'é nen ampostà për passé costa anformassion.
Peul desse ch'a sia basà an sij CGI e a peul pa mantnì img_auth.
Ch'a bèica http://www.mediawiki.org/wiki/Manual:Image_Authorization.",
'img-auth-notindir'     => "Ël senté ciamà a l'é pa ant ël dossié configurà për carié.",
'img-auth-badtitle'     => 'As peul pa fesse un tìtol bon për "$1".',
'img-auth-nologinnWL'   => 'A l\'é pa intrà ant ël sistema e "$1" a l\'é pa ant la lista bianca.',
'img-auth-nofile'       => 'L\'archivi "$1" a esist pa.',
'img-auth-isdir'        => 'A l\'é an camin ch\'a preuve a intré ant un dossié "$1".
As peul mach avèj acess a j\'archivi.',
'img-auth-streaming'    => 'Letura an continuà ëd "$1".',
'img-auth-public'       => "La funsion d'img_auth.php a l'é dë smone dj'archivi da na wiki privà.
Sta wiki-sì a l'é configurà com na wiki pùblica.
Për na sicurëssa otimal, img_auth.php a l'é disabilità.",
'img-auth-noread'       => 'L\'utent a l\'ha pa ij privilegi për lese "$1".',

# HTTP errors
'http-invalid-url'      => "Adrëssa dl'aragnà pa bon-a: $1.",
'http-invalid-scheme'   => 'J\'adrësse dl\'aragnà con ël prefiss "$1" a son pa mantnùe.',
'http-request-error'    => "L'arcesta Http a l'é falìa për n'eror pa conossù.",
'http-read-error'       => 'Eror ëd letura HTTP.',
'http-timed-out'        => "L'arcesta HTTP a l'ha finì sò temp.",
'http-curl-error'       => "Eror an sërcand d'arcuperé l'adrëssa dl'aragnà: $1.",
'http-host-unreachable' => "L'anliura a rispond pa.",
'http-bad-status'       => "A l'é staje un problema durant l'arcesta HTTP: $1 $2",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "L'anliura a l'arspond pa",
'upload-curl-error6-text'  => "L'anliura che a l'ha butà a marcia pa. Për piasì, ch'a contròla che st'anliura a sia scrita giusta e che ël sit a marcia.",
'upload-curl-error28'      => "A l'é finìje ël temp ch'as peul dovresse për carié",
'upload-curl-error28-text' => "Ël sit a-i buta tròp temp a arsponde. Për piasì, ch'a contròla che a l'é an pé, ch'a speta na minuta e peuj che a torna a prové. A peul esse che a-j ven-a a taj serne un moment che ës sit a sia nen tant carià ëd tràfich.",

'license'            => 'Licensa:',
'license-header'     => 'Licensa',
'nolicense'          => 'Gnun-a selession fàita',
'license-nopreview'  => "(Gnun-a preuva ch'as peula smon-se)",
'upload_source_url'  => "(n'anliura bon-a e che as peula dovresse)",
'upload_source_file' => "(n'archivi da sò ordinator)",

# Special:ListFiles
'listfiles-summary'     => "Sta pàgina special-sì a la smon tuti j'archivi ch'a son ëstàit carià.
Për sòlit j'ùltim carià a resto an sima.
Ch'a-i bata 'n colp col rat ansima a j'antestassion dle colòne për cangé l'órdin.",
'listfiles_search_for'  => "Arserché un nòm d'archivi multimojen:",
'imgfile'               => 'archivi',
'listfiles'             => "Lista d'archivi",
'listfiles_thumb'       => 'Miniadura',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nòm',
'listfiles_user'        => 'Utent',
'listfiles_size'        => 'Amzura an otet',
'listfiles_description' => 'Descrission',
'listfiles_count'       => 'Version',

# File description page
'file-anchor-link'                  => 'Archivi',
'filehist'                          => "Stòria dl'archivi",
'filehist-help'                     => "Ch'a-i daga un colp col rat ant sna cobia data/ora për ës-ciairé coma a restèissa l'archivi ant col moment-là.",
'filehist-deleteall'                => 'dëscancelé tut',
'filehist-deleteone'                => 'scancelé',
'filehist-revert'                   => "buté torna 'me ch'a l'era",
'filehist-current'                  => "dël dì d'ancheuj",
'filehist-datetime'                 => 'Data e Ora',
'filehist-thumb'                    => 'Miniadura',
'filehist-thumbtext'                => 'Miniadura dla version ëd $1',
'filehist-nothumb'                  => 'Gnun-e miniadure',
'filehist-user'                     => 'Utent',
'filehist-dimensions'               => 'Amzure',
'filehist-filesize'                 => "Amzure dl'archivi",
'filehist-comment'                  => 'Coment',
'filehist-missing'                  => 'Archivi mancant',
'imagelinks'                        => "Anliure a l'archivi",
'linkstoimage'                      => "{{PLURAL:$1|La pàgina sì-sota a l'ha|Le $1 pàgine sì-sota a l'han}} andrinta dj'anliure a cost archivi:",
'linkstoimage-more'                 => "Pì che $1 {{PLURAL:$1|pàgina|pàgine}} a l'han dj'anliure a cost archivi.
La lista sì-sota a smon mach {{PLURAL:$1|la prima pàgina ch'a l'ha|le prime $1 pàgine ch'a l'han}} d'anliure a s'archivi.
A l'é disponìbil na [[Special:WhatLinksHere/$2|lista completa]].",
'nolinkstoimage'                    => "Pa gnun-a pàgina che a l'abia n'anliura a sta figura-sì.",
'morelinkstoimage'                  => "Vëdde [[Special:WhatLinksHere/$1|d'àutri colegament]] a s'archivi.",
'redirectstofile'                   => "Sì-sota a-i {{PLURAL:$1|é n'archivi|son $1 archivi}} ch'a armando a cost-sì:",
'duplicatesoffile'                  => "{{PLURAL:$1|L'archivi sì-dapress a l'é un|Ij $1 archivi sì-dapress a son dij}} duplicà ëd s'archivi ([[Special:FileDuplicateSearch/$2|pì ëd detaj]]):",
'sharedupload'                      => "St'archivi-sì a ven da $1 e a peul esse dovrà da d'àutri proget.",
'sharedupload-desc-there'           => "Cost archivi a riva da $1 e a peul esse dovrà da d'àutri proget.
Për piasì, vëdde la [$2 pàgina ëd descrission ëd l'archivi] per d'àutre anformassion.",
'sharedupload-desc-here'            => "Cost archivi a riva da $1 e a peul esse dovrà da dj'àutri proget.
La descrission an soa [$2 pàgina ëd dëscrission ëd l'archivi] a l'é smonùa sì-sota.",
'filepage-nofile'                   => 'A esist gnun archivi con ës nòm.',
'filepage-nofile-link'              => "N'archivi con sto nòm-sì a esist pa, ma a peul [$1 carielo].",
'uploadnewversion-linktext'         => "Carié na version neuva dë st'archivi-sì",
'shared-repo-from'                  => 'da $1',
'shared-repo'                       => "n'archivi condivis",
'shared-repo-name-wikimediacommons' => 'Wikimedia Commons',

# File reversion
'filerevert'                => "Buté torna $1 tanme ch'a l'era",
'filerevert-legend'         => "Buté torna l'archivi tanme ch'a l'era",
'filerevert-intro'          => "A l'é dapress a buté torna l'archivi  '''[[Media:$1|$1]]''' com a l'era ant la [version $4 dël $2, $3].",
'filerevert-comment'        => 'Rason:',
'filerevert-defaultcomment' => 'Version dël $1, $2 butà torna coma corenta.',
'filerevert-submit'         => "Buté tanme ch'a l'era",
'filerevert-success'        => "'''L<nowiki>'</nowiki>archivi [[Media:$1|$1]]''' a l'é stàit torna butà com a l'era ant la [$4 version dël $2, $3].",
'filerevert-badversion'     => "A-i é pa gnun-a version local dl'archivi ch'a l'abia un marcatemp parèj.",

# File deletion
'filedelete'                  => 'Dëscancelé $1',
'filedelete-legend'           => "Dëscancelé l'archivi",
'filedelete-intro'            => "A l'é an broa dë scancelé l'archivi '''[[Media:$1|$1]]''' ansema a tuta soa stòria.",
'filedelete-intro-old'        => "A l'é dapress ch'a scancela l'archivi '''[[Media:$1|$1]]''' dël [$4 $3, $2].",
'filedelete-comment'          => 'Rason:',
'filedelete-submit'           => 'Dëscancelé',
'filedelete-success'          => "A l'é dëscancelasse l'archivi '''$1'''.",
'filedelete-success-old'      => "La version ëd '''[[Media:$1|$1]]''' dël $3, $2 a l'é stàita scancelà.",
'filedelete-nofile'           => "A-i é pa gnun archivi ch'as ciama: $1",
'filedelete-nofile-old'       => "A-i é pa gnun-a version parej ëd l'archivi '''$1'''",
'filedelete-otherreason'      => 'Àutra rason o rason adissional:',
'filedelete-reason-otherlist' => 'Àutra rason',
'filedelete-reason-dropdown'  => "*Për sòlit la ròba a së scancela për
** violassion dij drit d'autor
** duplicassion (visadì ch'a-i era già)",
'filedelete-edit-reasonlist'  => 'Modifiché la rason ëd lë scancelament',
'filedelete-maintenance'      => "Lë scancelament e la restaurassion d'archivi a l'é al moment disabilità durant la manutension.",

# MIME search
'mimesearch'         => 'Arserca për sòrt MIME',
'mimesearch-summary' => "Sta pàgina-sì a lassa filtré j'archivi për sòrt MIME. Buté: sòrt/sotasòrt, pr'es. <tt>image/jpeg</tt>.",
'mimetype'           => 'Sòrt MIME:',
'download'           => 'dëscarié',

# Unwatched pages
'unwatchedpages' => 'Pàgine che gnun a ten sot-euj',

# List redirects
'listredirects' => 'Lista dle ridiression',

# Unused templates
'unusedtemplates'     => 'Stamp nen dovrà',
'unusedtemplatestext' => "Sta pàgina-sì a la smon tute lë pàgine ant lë spassi nominal {{ns:template}} che a son pa dovrà andrinta a d'àutre pàgine.
Ch'as visa ëd controlé che në stamp a-j serva nen a dj'àutri stamp anans che fé che ranchelo via.",
'unusedtemplateswlh'  => 'àutre anliure',

# Random page
'randompage'         => 'Na pàgina qualsëssìa',
'randompage-nopages' => 'A-i é pa gnun-a pàgina ant {{PLURAL:$2|lë spassi nominal|jë spassi nominaj}}: lë spassi nominal "$1"',

# Random redirect
'randomredirect'         => 'Na ridiression qualsëssìa',
'randomredirect-nopages' => 'A-i é pa gnun-a ridiression ant lë spassi nominal "$1".',

# Statistics
'statistics'                   => 'Statìstiche',
'statistics-header-pages'      => 'Statìstiche dla pàgina',
'statistics-header-edits'      => 'Statìstiche dle modìfiche',
'statistics-header-views'      => 'Statìstiche dle visualisassion',
'statistics-header-users'      => 'Statìstiche ëd {{SITENAME}}',
'statistics-header-hooks'      => 'Àutre statìstiche',
'statistics-articles'          => 'Pàgine ëd contnù',
'statistics-pages'             => 'Pàgine',
'statistics-pages-desc'        => 'Tute le pàgine dla wiki, comprèise le pàgine ëd discussion, le ridiression e via fòrt.',
'statistics-files'             => 'Archivi carià',
'statistics-edits'             => "Pàgine modificà da quand ël {{SITENAME}} a l'é stàit tirà su",
'statistics-edits-average'     => 'Media dle modìfiche për pàgina',
'statistics-views-total'       => 'Total dle visualisassion',
'statistics-views-total-desc'  => 'Le visualisassion ëd le pàgine pa esistente e ëd le pàgine speciaj a son nen comprèise',
'statistics-views-peredit'     => 'Visualisassion për modìfica',
'statistics-users'             => '[[Special:ListUsers|Utent]] argistrà',
'statistics-users-active'      => 'Utent ativ',
'statistics-users-active-desc' => "Utent che a l'han fàit n'assion ant {{PLURAL:$1|l'ùltim di|j'ùltim $1 di}}",
'statistics-mostpopular'       => "Pàgine ch'a 'ncontro dë pì",

'disambiguations'      => "Pàgine për la gestion dj'omonimìe",
'disambiguationspage'  => "Template:Gestion dj'omonimìe",
'disambiguations-text' => "Ste pàgine-sì a men-o a na '''pàgina ëd gestion dj'omònim''', mach che a dovrìo ëmné bele drit a n'artìcol.<br />
Na pàgina as trata coma \"pàgina ëd gestion dj'omònim\" se a deuvra në stamp dont l'anliura as treuva ant ël [[MediaWiki:Disambiguationspage]]",

'doubleredirects'                   => 'Ridiression dobie',
'doubleredirectstext'               => "Sta pàgina-sì a a lista dle pàgine ch'a armando a d'àutre pàgine ëd ridiression.
Vira riga a l'ha andrinta j'anliure a la prima e a la sconda ridiression, ant sël pat ëd la prima riga ëd test dla seconda ridiression, che për sòlit a l'ha andrinta l'artìcol ëd destinassion vèir, col andoa che a dovrìa ëmné ëdcò la prima ridiression.
Le ridiression <del>sganfà</del> a son stàite arzolvùe.",
'double-redirect-fixed-move'        => "[[$1]] a l'é stàit spostà.
Adess a l'é na ridiression a [[$2]].",
'double-redirect-fixed-maintenance' => 'Rangé le rediression dobie da [[$1]] a [[$2]].',
'double-redirect-fixer'             => 'Coretor ëd ridiression',

'brokenredirects'        => 'Ridiression nen giuste',
'brokenredirectstext'    => "Coste ridiression-sì a men-o a d'artìcoj ch'a-i son pa:",
'brokenredirects-edit'   => 'modifiché',
'brokenredirects-delete' => 'scancelé',

'withoutinterwiki'         => "Pàgine ch'a l'han gnun-a anliura interwiki",
'withoutinterwiki-summary' => "Le pàgine ambelessì-sota a l'han gnun-a anliura a dj'àotre lenghe:",
'withoutinterwiki-legend'  => 'Prefiss',
'withoutinterwiki-submit'  => 'Smon-e',

'fewestrevisions' => 'Artìcoj con manch ëd modìfiche',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categorìa|categorìe}}',
'nlinks'                  => '$1 {{PLURAL:$1|anliura|anliure}}',
'nmembers'                => '$1 {{PLURAL:$1|element|element}}',
'nrevisions'              => '{{PLURAL:$1|na revision|$1 revision}}',
'nviews'                  => '{{PLURAL:$1|na consultassion|$1 consultassion}}',
'nimagelinks'             => 'Dovrà dzora a $1 {{PLURAL:$1|pàgina|pàgine}}',
'ntransclusions'          => 'dovrà dzora a $1 {{PLURAL:$1|pàgina|pàgine}}',
'specialpage-empty'       => 'Pàgina veujda.',
'lonelypages'             => 'Pàgine daspërlor',
'lonelypagestext'         => "Le pàgine ambelessì-sota a l'han gnun-e anliure che a-j men-o ansima nì a son anserìe an d'àotre pàgine ëd {{SITENAME}}.",
'uncategorizedpages'      => 'Pàgine che a son nen assignà a na categorìa',
'uncategorizedcategories' => 'Categorìe che a son pa assignà a na categorìa',
'uncategorizedimages'     => 'Archivi multimojen nen categorisà',
'uncategorizedtemplates'  => "Stamp sensa pa 'd categorìe",
'unusedcategories'        => 'Categorìe nen dovrà',
'unusedimages'            => 'Figure nen dovrà',
'popularpages'            => 'Pàgine pì s-ciairà',
'wantedcategories'        => 'Categorìe dont a fa da manca',
'wantedpages'             => 'Artìcoj pì ciamà',
'wantedpages-badtitle'    => "Tìtol nen vàlid ant l'ansema dj'arzultà: $1",
'wantedfiles'             => 'Archivi pì ciamà',
'wantedtemplates'         => 'Stamp ciamà',
'mostlinked'              => "Pàgine che a l'han pì d'anliure che a-i men-o la gent ansima",
'mostlinkedcategories'    => "Categorìe che a l'han pì d'anliure che a-i men-o la gent ansima",
'mostlinkedtemplates'     => 'Stamp pì dovrà',
'mostcategories'          => 'Artìcoj che a son marcà an pì categorìe',
'mostimages'              => 'Figure pì dovrà',
'mostrevisions'           => 'Artìcoj pì modificà',
'prefixindex'             => "Tute le pàgine ch'a ancamin-o con",
'shortpages'              => 'Pàgine curte',
'longpages'               => 'Pàgine longhe',
'deadendpages'            => 'Pàgine che a men-o da gnun-a part',
'deadendpagestext'        => "Le pàgine ambelessì-sota a l'han pa d'anliure anvers a j'àutre pàgine ëd {{SITENAME}}.",
'protectedpages'          => 'Pàgine sota protession',
'protectedpages-indef'    => 'Mach protession anfinìe',
'protectedpages-cascade'  => 'Mach protession a cascà',
'protectedpagestext'      => "Ambelessì-sota a-i é na lista ëd pàgine ch'a son protegiùe përchè as peulo nen modifichesse ò pura tramudesse",
'protectedpagesempty'     => 'Për adess a-i é pa gnun-a pàgina protegiùa',
'protectedtitles'         => 'Tìtoj protegiù',
'protectedtitlestext'     => 'Ij tìtoj ambelessì-sota as peulo pa creesse',
'protectedtitlesempty'    => "A-i é pa gnun tìtol protegiù ch'a-i intra coi criteri ch'a l'ha butà.",
'listusers'               => "Lista dj'utent",
'listusers-editsonly'     => "Mostré mach j'utent ch'a l'han fàit dle modìfiche",
'listusers-creationsort'  => 'Ordiné për data ëd creassion',
'usereditcount'           => '$1 {{PLURAL:$1|modìfica|modìfiche}}',
'usercreated'             => 'Creà ël $1 a $2',
'newpages'                => 'Pàgine neuve',
'newpages-username'       => 'Stranòm:',
'ancientpages'            => 'Le pàgine pì veje',
'move'                    => 'Tramudé',
'movethispage'            => 'Tramudé costa pàgina',
'unusedimagestext'        => "J'archivi sì-sota a esisto ma a son pa andrinta a gnun-e pàgine.
Për piasì, ch'a nòta che d'àutri sit an sl'aragnà a peulo coleghesse a n'archivi con n'anliura direta, e parèj a peulo ancó esse listà ambelessì, bele s'a son dovrà an coj sit.",
'unusedcategoriestext'    => "Le pàgine ëd coste categorìe-sì a son fasse ma peuj a l'han andrinta nì d'artìcoj, nì ëd sotacategorìe.",
'notargettitle'           => 'A manco ij dat',
'notargettext'            => "A l'ha pa dit a che pàgina ò Utent apliché l'operassion ciamà.",
'nopagetitle'             => 'La pàgina ëd destinassion a-i é pa',
'nopagetext'              => "La pàgina che a l'ha spessificà a esist pa.",
'pager-newer-n'           => '{{PLURAL:$1|1|$1}} pì neuv',
'pager-older-n'           => '{{PLURAL:$1|1|$1}} pì vej',
'suppress'                => 'Supervisor',
'querypage-disabled'      => "Sta pàgina special a l'é disabilità për dle rason ëd prestassion.",

# Book sources
'booksources'               => 'Andoa trové dij lìber',
'booksources-search-legend' => "Sërché antra ij lìber d'arferiment",
'booksources-go'            => 'Andé',
'booksources-text'          => "Ambelessì sota a-i é na lista d'àotri sit che a vendo dij lìber neuv e dë sconda man, e che a peulo ëdcò smon-e dj'anformassion rësgoard ai test che a l'é antramentr che a sërca:",
'booksources-invalid-isbn'  => "L'ISBN dàit a smija che a sia pa vàlid; ch'a contròla s'a-i é n'eror an copiand da la sorgiss original.",

# Special:Log
'specialloguserlabel'  => 'Utent:',
'speciallogtitlelabel' => 'Tìtol:',
'log'                  => 'Registr',
'all-logs-page'        => 'Tùit ij registr pùblich',
'alllogstext'          => 'Visualisassion combinà ëd tùit ij registr ëd {{SITENAME}}.
A peul arstrenze la visualisassion an selessionand la sòrt ëd registr, lë stranòm utent (sensìbil a majùscol/minùscol), e la pàgina anteressà (sensìbil a majùscol/minùscol).',
'logempty'             => 'Pa gnun element parèj che a sia trovasse ant ij registr.',
'log-title-wildcard'   => "Sërché ant ij tìtoj ch'as anandio për",

# Special:AllPages
'allpages'          => 'Tute le pàgine',
'alphaindexline'    => '$1 a $2',
'nextpage'          => 'Pàgina che a-i ven ($1)',
'prevpage'          => 'Pàgina anans ($1)',
'allpagesfrom'      => 'Smon-e le pàgine ën partend da:',
'allpagesto'        => 'Smon-e le pàgine fin-a a:',
'allarticles'       => "Tùit j'artìcoj",
'allinnamespace'    => 'Tute le pàgine (spassi nominal $1)',
'allnotinnamespace' => 'Tute le pàgine (che a son nen ant lë spassi nominal $1)',
'allpagesprev'      => 'Cole prima',
'allpagesnext'      => 'Cole che a ven-o',
'allpagessubmit'    => 'Andé',
'allpagesprefix'    => "Smon-e le pàgine che a l'han ël prefiss:",
'allpagesbadtitle'  => "Ël tìtol che a l'ha daje a la pàgina a va nen bin, ò pura a l'ha andrinta un prefiss inter-lenga ò inter-wiki. A peul esse ëdcò che a l'abia andrinta dij caràter che as peulo nen dovresse ant ij tìtoj.",
'allpages-bad-ns'   => '{{SITENAME}} a l\'ha pa gnun ëspassi nominal "$1".',

# Special:Categories
'categories'                    => 'Categorìe',
'categoriespagetext'            => "{{PLURAL:$1|Costa categorìa a conten|Coste categorìe a conten-o}} dle pàgine ò dj'archivi.
[[Special:UnusedCategories|Le categorìe non dovrà]] A son pa mostà ambelessì.
Varda ëdcò [[Special:WantedCategories|Categorìe ciamà]].",
'categoriesfrom'                => 'Mosta le categorìe an partend da:',
'special-categories-sort-count' => 'ordiné për nùmer',
'special-categories-sort-abc'   => 'òrdiné për alfabétich',

# Special:DeletedContributions
'deletedcontributions'             => 'Modìfiche faite da utent scancelà',
'deletedcontributions-title'       => 'Modìfiche faite da utent scancelà',
'sp-deletedcontributions-contribs' => 'contribussion',

# Special:LinkSearch
'linksearch'       => 'Anliure an sla Ragnà',
'linksearch-pat'   => "Schema d'arsërca:",
'linksearch-ns'    => 'Spassi nominal:',
'linksearch-ok'    => 'Sërca',
'linksearch-text'  => 'As peulo dovresse dij ciapatut coma "*.wikipedia.org".<br />Protocòj ch\'as peulo dovré: <tt>$1</tt>',
'linksearch-line'  => "$1 a l'ha n'anliura ch'a-i riva dzora da $2",
'linksearch-error' => 'Ij ciapatut as peulo butesse mach an prinsipi dël nòm dël servent.',

# Special:ListUsers
'listusersfrom'      => "Smon-me j'utent a parte da:",
'listusers-submit'   => 'Smon',
'listusers-noresult' => 'Pa gnun utent parej.',
'listusers-blocked'  => '(blocà)',

# Special:ActiveUsers
'activeusers'            => "Lista dj'utent ativ",
'activeusers-intro'      => "Costa a l'é na lista d'utent ch'a l'han avù n'atività qualsëssìa ant j'ùltim $1 {{PLURAL:$1|di|di}}.",
'activeusers-count'      => "$1 {{PLURAL:$1|modìfica neuva|modìfiche neuve}} ant {{PLURAL:$3|l'ùltim di|j'ùltim $3 di}}",
'activeusers-from'       => "Mosta j'utent a parte da:",
'activeusers-hidebots'   => 'Stërma trigomiro',
'activeusers-hidesysops' => "Stërma j'aministrator",
'activeusers-noresult'   => 'Pa gnun utent trovà.',

# Special:Log/newusers
'newuserlogpage'              => "Registr dla creassion dj'utent",
'newuserlogpagetext'          => "Sossì a l'é un registr andova ch'as marco le creassion dj'utent.",
'newuserlog-byemail'          => 'ciav spedìa via e-mail',
'newuserlog-create-entry'     => 'Neuv utent',
'newuserlog-create2-entry'    => 'Creà ël neuv cont $1',
'newuserlog-autocreate-entry' => 'Cont creà automaticament',

# Special:ListGroupRights
'listgrouprights'                      => "Drit dël grup d'utent",
'listgrouprights-summary'              => "Ambelessì a-i é na lista dij grup utent definì an dzora a sta wiki-sì, con ij sò drit d'acess assocssià.
A peulo ess-ie [[{{MediaWiki:Listgrouprights-helppage}}|anformassion adissionaj]] an dzora a dij drit individuaj.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Drit asignà</span>
* <span class="listgrouprights-revoked">Drit revocà</span>',
'listgrouprights-group'                => 'Grup',
'listgrouprights-rights'               => 'Drit',
'listgrouprights-helppage'             => 'Help:Drit dël grup',
'listgrouprights-members'              => '(Lista dij mèmber)',
'listgrouprights-addgroup'             => 'Gionta {{PLURAL:$2|dla partìa|djë partìe}}: $1',
'listgrouprights-removegroup'          => 'Gava {{PLURAL:$2|dla partìa|djë partìe}}: $1',
'listgrouprights-addgroup-all'         => 'Gionta tute le partìe',
'listgrouprights-removegroup-all'      => 'Gava tute le partìe',
'listgrouprights-addgroup-self'        => 'Gionta {{PLURAL:$2|la partìa|le partìe}} al tò cont: $1',
'listgrouprights-removegroup-self'     => 'Gava {{PLURAL:$2|la partìa|le partìe}} da tò cont: $1',
'listgrouprights-addgroup-self-all'    => 'Gionta tute le partìe a tò cont',
'listgrouprights-removegroup-self-all' => 'Gava tute le partìe da tò cont',

# E-mail user
'mailnologin'          => 'A-i é pa gnun-a adrëssa për mandé ël messagi',
'mailnologintext'      => "A dev [[Special:UserLogin|rintré ant ël sistema]]
e avej registrà n'adrëssa ëd pòsta eletrònica vàlida ant ij [[Special:Preferences|sò gust]] për podej mandé dij messagi ëd pòsta eletrònica a j'àutri Utent.",
'emailuser'            => "Mand-je un messagi eletrònich a st'Utent-sì",
'emailpage'            => "Mand-je un messagi ëd pòsta eletrònica a st'utent-sì",
'emailpagetext'        => "It peule dovré la forma ambelessì për mandé un messagi e-mail a st'utent-sì.
L'adrëssa e-mail ch'it l'has butà an [[Special:Preferences|Ij sò gust]] a sarà butò ant l'adrëssa \"From\" ëd toa e-mail, parèj ël ricevent a podrà arsponde diretament a ti.",
'usermailererror'      => "L'oget che a goèrna la pòsta eletrònica a l'ha dait eror:",
'defemailsubject'      => 'Messagi da {{SITENAME}}',
'usermaildisabled'     => "Pòsta eletrònica dl'utent disabilità",
'usermaildisabledtext' => "A peul pa mandé ëd mësagi ëd pòsta eletrònica a d'àutri utent dzora a sta wiki-sì",
'noemailtitle'         => 'Pa gnun-a adrëssa ëd pòsta eletrònica',
'noemailtext'          => "Cost Utent-sì a l'ha pa spessificà n'adrëssa e-mail vàlida.",
'nowikiemailtitle'     => 'Gnun-e e-mail',
'nowikiemailtext'      => "Stutent-sì a l'ha sërnù ëd pa arseive e-mail da dj'àutri utent.",
'email-legend'         => "Manda n'e-mail a n'àutr utent ëd {{SITENAME}}",
'emailfrom'            => 'Da:',
'emailto'              => 'A:',
'emailsubject'         => 'Oget:',
'emailmessage'         => 'Messagi:',
'emailsend'            => 'Manda',
'emailccme'            => 'Mand-ne na còpia ëdcò a mia adrëssa.',
'emailccsubject'       => 'Còpia dël messagi mandà a $1: $2',
'emailsent'            => 'Messagi eletrònich mandà',
'emailsenttext'        => "Sò messagi eletrònich a l'é stait mandà",
'emailuserfooter'      => 'St\'e-mail-sì a l\'é stàita mandà da $1 a $2 con la fonsion "E-mail utent" a {{SITENAME}}.',

# User Messenger
'usermessage-summary' => "A l'ha lassà un mëssagi ëd sistema.",
'usermessage-editor'  => 'Mëssagerìa ëd sistema',

# Watchlist
'watchlist'            => 'Ròba che im ten-o sot euj',
'mywatchlist'          => 'Ròba che im ten-o sot euj',
'watchlistfor2'        => 'Për $1 $2',
'nowatchlist'          => 'A l\'ha ancó pa marcà dj\'artìcoj coma "ròba da tnì sot euj".',
'watchlistanontext'    => "Për piasì, $1 për ës-ciairé ò pura modifiché j'element ëd soa lista dla ròba che as ten sot euj.",
'watchnologin'         => "A l'é ancó nen rintrà ant ël sistema",
'watchnologintext'     => "A l'ha da manca prima ëd tut dë [[Special:UserLogin|rintré ant ël sistema]]
për podej modifiché soa lista dla ròba dë tnì sot euj.",
'addedwatch'           => "Sòn a l'é stait giontà a le pàgine che it ten-e sot euj",
'addedwatchtext'       => 'La pàgina  "[[:$1]]" a l\'é staita giontà a soa [[Special:Watchlist|lista dla ròba da tnì sot-euj]].
Le modìfiche che a-i vniran ant costa pàgina-sì e ant soa pàgina ëd discussion a saran listà ambelessì, e la pàgina a së s-ciairërà ën <b>grassèt</b> ant la pàgina ëd j\'[[Special:RecentChanges|ùltime modìfiche]] përchè che a resta belfé a ten-la d\'euj.',
'removedwatch'         => "Gavà via da 'nt la lista dla ròba da tnì sot euj",
'removedwatchtext'     => 'La pàgina "[[:$1]]" a l\'è staita gavà via da [[Special:Watchlist|soa lista dla ròba da tnì sot euj]].',
'watch'                => 'ten sot euj',
'watchthispage'        => "Ten sot euj st'artìcol-sì",
'unwatch'              => 'Chita-lì da ten-e sossì sot euj',
'unwatchthispage'      => 'Chita-lì da ten-e sossì sot euj',
'notanarticle'         => "Sòn a l'é pa n'artìcol",
'notvisiblerev'        => "La revision a l'é stàita scancelà",
'watchnochange'        => 'Pa gnun-a dle ròbe che as ten sot euj che a sia staita modificà ant ël temp indicà.',
'watchlist-details'    => "A l'é dëmentrè ch'as ten sot euj {{PLURAL:$1|$1 pàgina|$1 pàgine}}, nen contand cole ëd discussion.",
'wlheader-enotif'      => '* Le notìfiche për pòsta eletrònica a son abilità.',
'wlheader-showupdated' => "* Cole pàgine che a son staite modificà da quand che a l'é passa l'ùltima vira a resto marcà an '''grassèt'''",
'watchmethod-recent'   => "controland j'ùltime modìfiche faite a le pàgine che as ten sot euj",
'watchmethod-list'     => 'controland le pàgine che as ten sot euj për vëdde se a-i sio mai staje dle modìfiche',
'watchlistcontains'    => "Soa lista dla ròba ch'as ten sot euj a l'ha andrinta {{PLURAL:$1|na pàgina|$1 pàgine}}.",
'iteminvalidname'      => "Problema con l'element '$1', nòm nen vàlid...",
'wlnote'               => "Ambelessì sota a-i {{PLURAL:$1|é l'ùltima modìfica|son j'ùltime '''$1''' modìfiche}} ant {{PLURAL:$2|l'ùltima ora|j'ùltime '''$2''' ore}}.",
'wlshowlast'           => "Most-me j'ùltime $1 ore $2 dì $3",
'watchlist-options'    => "Opsion lista d'osservassion",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Sot euj...',
'unwatching' => "Ën gavand da lòn ch'as ten sot euj...",

'enotif_mailer'                => '{{SITENAME}} - Servissi ëd Notìfica Postal',
'enotif_reset'                 => 'March-me tute le pàgine visità',
'enotif_newpagetext'           => "Costa-sì a l'é na pàgina neuva",
'enotif_impersonal_salutation' => 'utent ëd {{SITENAME}}',
'changed'                      => 'cangià',
'created'                      => 'creà',
'enotif_subject'               => 'La pàgina $PAGETITLE ëd {{SITENAME}} a l\'é staita $CHANGEDORCREATED da $PAGEEDITOR',
'enotif_lastvisited'           => "Che as varda $1 për ës-ciaré tute le modìfiche da 'nt l'ùltima vira che a l'é passà.",
'enotif_lastdiff'              => "Ch'a varda $1 për visioné sta modìfica.",
'enotif_anon_editor'           => 'utent anònim $1',
'enotif_body'                  => 'Car $WATCHINGUSERNAME,

La pàgina $PAGETITLE dël sit {{SITENAME}} a l\'é stàita $CHANGEDORCREATED al $PAGEEDITDATE da $PAGEEDITOR, che a varda $PAGETITLE_URL për la version corenta.

$NEWPAGE

Resumé dl\'editor: $PAGESUMMARY $PAGEMINOREDIT

Për contaté l\'editor:
Pòsta eletrònica: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

A-i sarà pì gnun-a notìfica ëd modìfiche se chiel a vìsita nen costa pàgina.
Che as visa che a peul cangeje la configurassion dle notìfiche a le pàgine che as ten sot-euj ansima a soa lista dla ròba da ten-e sot euj.

             Comunicassion dël sistema ëd notìfica da {{SITENAME}}

--
Për cangé la configurassion ëd lòn che as ten sot euj che a vada ansima a
{{fullurl:{{#special:Watchlist}}/edit}}

Për scancelé la pàgina da lòn ch\'a ten sot euj, ch\'a vìsita
$UNWATCHURL

Për fé dle comunicassion ëd servissi e avèj pì d\'agiut:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Scancela pàgina',
'confirm'                => 'Conferma',
'excontent'              => "Ël contnù a l'era: '$1'",
'excontentauthor'        => "ël contnù a l'era: '$1' (e l'ùnich contributor a l'era stait '$2')",
'exbeforeblank'          => "Anans d'esse dësvojdà ël contnù a l'era: '$1'",
'exblank'                => "La pàgina a l'era veujda",
'delete-confirm'         => 'Scancela "$1"',
'delete-legend'          => 'Scancela',
'historywarning'         => "'''Avis:''' La pàgina che a l'é antramentr che a scancela a l'ha na stòria con pi o men $1 {{PLURAL:$1|revision|revision}}:",
'confirmdeletetext'      => "A sta për scancelé d'autut da 'nt la base dat na pàgina ò pura na figura, ansema a tuta soa cronologìa.<p>
Për piasì, che an conferma che sòn a l'é da bon sò but, che a as rend cont ëd le conseguense ëd lòn che a fa, e che sòn a resta an pien an régola con lòn che a l'é stabilì ant la [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'         => 'Travaj fait e finì',
'actionfailed'           => 'Assion falìa',
'deletedtext'            => 'La pàgina "<nowiki>$1</nowiki>" a l\'é staita scancelà.
Che a varda $2 për na lista dle pàgine scancelà ant j\'ùltim temp.',
'deletedarticle'         => 'Scancelà "$1"',
'suppressedarticle'      => 'a l\'ha scancelà "[[$1]]"',
'dellogpage'             => 'Registr djë scancelament',
'dellogpagetext'         => "Ambelessì sota na lista dle pàgine scancelà ant j'ùltim temp.
Ij temp a son conforma a l'ora dël server.",
'deletionlog'            => 'Registr djë scancelament',
'reverted'               => 'Version prima butà torna sù',
'deletecomment'          => 'Rason:',
'deleteotherreason'      => 'Rason àutra/adissional:',
'deletereasonotherlist'  => 'Àutra rason',
'deletereason-dropdown'  => "*Rason sòlite ch'as ëscancela la ròba
** a lo ciama l'àutor
** violassion dij drit d'autor
** vanadalism",
'delete-edit-reasonlist' => 'Modifiché la rason dlë scancelament',
'delete-toobig'          => "Sta pàgina-sì a l'ha na stòria motobin longa, bele pì che $1 {{PLURAL:$1|revision|revision}}.
Lë scancelassion ëd pàgine parej a l'é stàita limità për evité ch'as fasa darmagi për eror a {{SITENAME}}.",
'delete-warning-toobig'  => "Sta pàgina-sì a l'ha na stòria motobin longa, bele pì che $1 {{PLURAL:$1|revision|revision}}.
A scancelela as peul fesse darmagi a j'operassion dla base dat ëd {{SITENAME}};
ch'a fasa euj a lòn ch'a fa.",

# Rollback
'rollback'          => 'Dòvra na revision pì veja',
'rollback_short'    => 'Ripristinè',
'rollbacklink'      => "ripristiné j'archivi",
'rollbackfailed'    => "A l'é pa podusse ripristiné",
'cantrollback'      => "As peul pa tornesse a na version pì veja: l'ùltima modìfica a l'ha fala l'ùnich utent che a l'abia travajà a cost artìcol-sì.",
'alreadyrolled'     => "As peulo pa anulé j'ultime modìfiche ëd [[:$1]] faite da [[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
cheidun d'àutr a l'ha già modificà ò pura anulà le modìfiche a sta pàgina-sì.

L'ùltima modìfica a la pàgina a l'é staita faita da [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'       => "Ël coment dla modìfica a l'era: \"''\$1''\".",
'revertpage'        => "Gavà via le modìfiche ëd [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]); ël contnù a l'é stait tirà andarè a l'ùltima version dl'utent [[User:$1|$1]]",
'revertpage-nouser' => "Scanselà le modìfiche dë (stranòm gavà) a l'ùltima vërsion ëd [[User:$1|$1]]",
'rollback-success'  => "Modìfiche anulà da $1; tirà andré a l'ùltima version da $2.",

# Edit tokens
'sessionfailure-title' => 'Eror ëd session',
'sessionfailure'       => "A-i son ëstaje dle gran-e con la session che a identìfica sò acess; ël sistema a l'ha nen eseguì l'ordin che a l'ha daje për precaussion. Che a torna andaré a la pàgina prima con ël boton \"andaré\" ëd sò programa ëd navigassion (browser), peuj che as carìa n'àutra vira costa pàgina-sì e che a preuva torna a fé lòn che vorìa fé.",

# Protect
'protectlogpage'              => 'Registr dle protession',
'protectlogtext'              => "Ambelessì sota a-i é na lista d'event ëd protession e dësprotession ëd pàgine.
Ch'a varda la [[Special:ProtectedPages|Lista dle pàgine protegiùe]] për ës-ciairé le protession corente.",
'protectedarticle'            => '"[[$1]]" a l\'é protet',
'modifiedarticleprotection'   => 'A l\'é cambia-ie ël livel ëd protession për "[[$1]]"',
'unprotectedarticle'          => 'Dësprotegiù "[[$1]]"',
'movedarticleprotection'      => 'Cambià le ampostassion ëd protession da "[[$2]]" a "[[$1]]"',
'protect-title'               => 'I soma antramentr che i protegioma "$1"',
'prot_1movedto2'              => '[[$1]] Tramudà a [[$2]]',
'protect-legend'              => 'Che an conferma la protession',
'protectcomment'              => 'Rason:',
'protectexpiry'               => 'Scadensa:',
'protect_expiry_invalid'      => 'Scadensa pa bon-a.',
'protect_expiry_old'          => 'Scadensa già passà.',
'protect-unchain-permissions' => 'Sblòca àutre opsion ëd protession',
'protect-text'                => "Ambelessì a peul vardé e cangé ël livel ëd protession dla pàgina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Un a peul pa modifiché ij livel ëd protession antramentr ch'a l'é blocà chiel. Ambelessì a-i son le regolassion corente për la pàgina '''$1''':",
'protect-locked-dblock'       => "Ij livej ëd protession as peulo nen cambiesse antramentr che la base dat a l'é blocà.
Ambelessì a-i son le regolassion corente për la pàgina '''$1''':",
'protect-locked-access'       => "Sò cont a l'ha pa la qualìfica për podej cambié ij livej ëd protession.
Ambelessì a-i son le regolassion corente për la pàgina '''$1''':",
'protect-cascadeon'           => "Sta pàgina për adess a l'é blocà përchè a-i intra an {{PLURAL:$1|la pàgina sì sota, ch'a l'ha|le pàgine sì sota, ch'a l'han}} na protession a sàut avisca. A peul cambie-je sò livel ëd protession a sta pàgina-sì ma lòn a tochërà pa la protession a sàut.",
'protect-default'             => "Autorisa tùit j'utent",
'protect-fallback'            => 'A-i va ël përmess "$1"',
'protect-level-autoconfirmed' => "Bloca j'utent neuv e coj nen registrà",
'protect-level-sysop'         => "mach për j'aministrator",
'protect-summary-cascade'     => 'a sàut',
'protect-expiring'            => 'scadensa: $1 (UTC)',
'protect-expiry-indefinite'   => 'për sempe',
'protect-cascade'             => "Protege le pàgine ch'a fan part ëd costa (protession a sàut)",
'protect-cantedit'            => "A peul pa cambieje livel ëd protession a sta pàgina-sì, për via ch'a l'ha nen ël përmess dë modifichela.",
'protect-othertime'           => "N'àutra durà:",
'protect-othertime-op'        => "N'àutra durà",
'protect-existing-expiry'     => 'Scadensa esistenta:$3, $2',
'protect-otherreason'         => 'Rason àutra/adissional:',
'protect-otherreason-op'      => 'Àutra rason',
'protect-dropdown'            => '*Rason comun-e ëd protession
** Tròp vandalism
** Tròp spamming
** Edit war nen produtiv
** Pàgina con motobin ëd tràfich',
'protect-edit-reasonlist'     => 'Rason ëd la protession da le modìfiche',
'protect-expiry-options'      => '1 ora:1 hour,1 di:1 day,1 sman-a:1 week,2 sman-e:2 weeks,1 meis:1 month,3 meis:3 months,6 meis:6 months,1 ann:1 year,për sempe:infinite',
'restriction-type'            => 'Përmess',
'restriction-level'           => 'Livel ëd restrission',
'minimum-size'                => 'Amzura mìnima',
'maximum-size'                => 'Amzura màssima:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Modìfica',
'restriction-move'   => 'Tramuda',
'restriction-create' => 'Creé',
'restriction-upload' => 'Caria',

# Restriction levels
'restriction-level-sysop'         => 'protegiùa',
'restriction-level-autoconfirmed' => 'mesa-protegiùa',
'restriction-level-all'           => 'tuti ij livej',

# Undelete
'undelete'                     => 'Pija andré na pàgina scancelà',
'undeletepage'                 => 'S-ciàira e pija andaré le pàgine scancelà',
'undeletepagetitle'            => "'''Lòn ch'a-i é ambelessì a son tute revision scancelà ëd [[:$1]]'''.",
'viewdeletedpage'              => 'Smon le pàgine scancelà',
'undeletepagetext'             => "{{PLURAL:$1|La pàgina ambelessì sota a l'é staita scancelà, ma a resta|$1 Le pàgine ambelessì sota a son staite scancelà, ma a resto}} ancó memorisà ant l'archivi a as peulo pijesse andaré.
L'archivi a ven polidà passaje un pòch ëd temp.",
'undelete-fieldset-title'      => 'Arcùpera le revision',
'undeleteextrahelp'            => "Për ripristiné l'antrega stòria dla pàgina, che a lassa tute le casele nen selessionà e che a jë sgnaca ansima a '''''Buta coma a l'era'''''.
Për ripristiné mach chèich-còs, che a selession-a le casele corispondente a le revision da ripristiné anans che sgnaché.
Ën sgacand-je ansima a '''''Veujda casele''''' peul polidesse d'amblé tute le casele selessionà e dësvojdé ël coment.",
'undeleterevisions'            => '{{PLURAL:$1|Na|$1}} revision memorisà',
'undeletehistory'              => "Se a pija andré st'articol-sì, ëdcò tute soe revision a saran pijaite andaré ansema a chiel ant soa cronologìa.
Se a fussa mai staita creà na pàgina neuva con l'istess nòm dòp che la veja a l'era staita scancelà, le revision a saran buta ant la cronologìa ëd prima.",
'undeleterevdel'               => "Ël dëscancelament as farà pa s'a-i intrèissa në scancelament parsial dla version corenta dla pàgina. Quand a-i riva lolì, un a dev gave-ie la crosëtta da 'nt la pì neuva dle version scancelà, ò pura gavela da stërmà.",
'undeletehistorynoadmin'       => "Sta pàgina-sì a l'é staita scancelà.
Ël motiv che a l'é scancelasse as peul savejsse ën vardand ël somari ambelessì sota, andoa che a së s-ciàira ëdcò chi che a
l'avìa travaje ansima anans che a la scancelèisso.
Ël test che a-i era ant le vàire version a peulo s-ciairelo mach j'aministrator.",
'undelete-revision'            => 'Revision ëscancelà ëd $1 (dël $4, a $5) da $3:',
'undeleterevision-missing'     => "Revision nen bon-a ò ch'a-i é nen d'autut. A peul esse ch'a l'abia n'anliura cioca, ma a peul ëdcò esse che la revision a la sia staita dëscancelà ò gavà via da 'nt la base dat.",
'undelete-nodiff'              => 'Pa gnun-a revision anans ëd costa.',
'undeletebtn'                  => 'Ripristiné',
'undeletelink'                 => 'varda/buta andré',
'undeleteviewlink'             => 'varda',
'undeletereset'                => 'Gava tute le selession',
'undeleteinvert'               => 'Anvert la selession',
'undeletecomment'              => 'Rason:',
'undeletedarticle'             => 'Pijaita andré "$1"',
'undeletedrevisions'           => '{{PLURAL:$1|Na revision pijaita|$1 revision pijaite}} andré',
'undeletedrevisions-files'     => "{{PLURAL:$1|Na|$1}} revision e {{PLURAL:$2|n'|$2&nbsp;}}archivi pijait andré",
'undeletedfiles'               => "{{PLURAL:$1|N'|$1&nbsp;}}archivi pijait andaré",
'cannotundelete'               => "Disdëscancelament falì; a peul esse che i fusse antra doi a felo ant l'istess temp e l'àutr a sia riva prima.",
'undeletedpage'                => "'''$1 a l'é stait pijait andaré'''

Che as varda ël [[Special:Log/delete|Registr djë scancelament]] për ës-ciairé j'ùltim scancelament e disdëscancelament.",
'undelete-header'              => "Ch'a varda [[Special:Log/delete|ël registr djë scancelament]] për ës-ciairé j'ùltim dëscancelament.",
'undelete-search-box'          => 'Arsërca ant le pàgine scancelà',
'undelete-search-prefix'       => "Smon le pàgine ch'as anandio për:",
'undelete-search-submit'       => 'Sërca',
'undelete-no-results'          => "A-i é pa gnun-a pàgina parej ant l'archivi djë scancelassion.",
'undelete-filename-mismatch'   => "As peul nen disdëscancelesse la revision d'archivi col marcatemp $1: sòrt d'archivi nen giusta",
'undelete-bad-store-key'       => "As peul pa disdëscancelesse la revision d'archivi col marcatemp $1: l'archivi a-i era già pì anans d'esse scancelà.",
'undelete-cleanup-error'       => 'Eror ën scanceland l\'archivi nen dovrà "$1".',
'undelete-missing-filearchive' => "As peul nen ricuperesse l'archivi con l'identità $1 përchè a-i é pa ant la base dat. A peul esse ch'a l'abio già disdëscancelalo.",
'undelete-error-short'         => "Eror ën disdëscanceland l'archivi: $1",
'undelete-error-long'          => "Eror antramentr ch'as disdëscancelava l'archivi:

$1",
'undelete-show-file-confirm'   => 'É-lo sicur ëd vorèj vëdde la revision scancelà ëd l\'archivi "<nowiki>$1</nowiki>" da $2 a $3?',
'undelete-show-file-submit'    => 'É!',

# Namespace form on various pages
'namespace'             => 'Spassi nominal:',
'invert'                => 'Anvert la selession',
'namespace_association' => 'Spassi nominal assossià',
'blanknamespace'        => '(Prinsipal)',

# Contributions
'contributions'       => "Contribussion dë st'Utent-sì",
'contributions-title' => 'Contribussion ëd $1',
'mycontris'           => 'Mie contribussion',
'contribsub2'         => 'Për $1 ($2)',
'nocontribs'          => "A l'é pa trovasse gnun-a modìfica che a fussa conforma a costi criteri-sì",
'uctop'               => ' (ùltima dla pàgina)',
'month'               => 'Mèis:',
'year'                => 'Ann:',

'sp-contributions-newbies'             => 'Smon mach ël travaj dij cont neuv',
'sp-contributions-newbies-sub'         => "Për j'utent neuv",
'sp-contributions-newbies-title'       => "Contribussion ëd j'utent për ij neuv cont",
'sp-contributions-blocklog'            => "Fërma l'agiornament dij registr",
'sp-contributions-deleted'             => "Modìfiche d'utent scancelà",
'sp-contributions-uploads'             => 'cariagi',
'sp-contributions-logs'                => 'registr',
'sp-contributions-talk'                => 'discussion',
'sp-contributions-userrights'          => 'gestion dij drit utent',
'sp-contributions-blocked-notice'      => "St'utent-sì a l'é blocà al moment. L'ùltima intrada dël registr dij blòch a l'é butà sì sota për arferiment:",
'sp-contributions-blocked-notice-anon' => "St'adrëssa IP a l'é blocà al moment.
L'ùltima intrada dël registr dij blocagi a l'é butà sì-sota për arferiment:",
'sp-contributions-search'              => 'Arsërca contribussion',
'sp-contributions-username'            => 'Adrëssa IP ò nòm utent:',
'sp-contributions-toponly'             => "Mostré mach le modìfiche ch'a son j'ùltime revision",
'sp-contributions-submit'              => 'Arsërca',

# What links here
'whatlinkshere'            => "Pàgine con dj'anliure che a men-o a costa-sì",
'whatlinkshere-title'      => 'Pàgine ch\'a men-o a "$1"',
'whatlinkshere-page'       => 'Pàgina:',
'linkshere'                => "Le pàgine sì sota a l'han andrinta dj'anliure che a men-o a '''[[:$1]]''':",
'nolinkshere'              => "A-i é pa gnun-a pàgina che a l'abia dj'anliure che a men-o a '''[[:$1]]'''.",
'nolinkshere-ns'           => "An stë spassi nominal-sì a-i è pa gnun-a pagina con dj'anliure ch'a men-o a '''[[:$1]]'''.",
'isredirect'               => 'ridiression',
'istemplate'               => 'inclusion',
'isimage'                  => 'anliura a figura',
'whatlinkshere-prev'       => "{{PLURAL:$1|d'un andré|andré ëd $1}}",
'whatlinkshere-next'       => "{{PLURAL:$1|d'un anans|anans ëd $1}}",
'whatlinkshere-links'      => '← anliure',
'whatlinkshere-hideredirs' => '$1 rediression',
'whatlinkshere-hidetrans'  => '$1 anclusion',
'whatlinkshere-hidelinks'  => '$1 anliura',
'whatlinkshere-hideimages' => '$1 anliure ëd figure',
'whatlinkshere-filters'    => 'Filtr',

# Block/unblock
'blockip'                         => "Blochè n'adrëssa IP",
'blockip-title'                   => "Bloché l'utent",
'blockip-legend'                  => "Bloché l'utent",
'blockiptext'                     => "Che a dòvra ël mòdulo ëd domanda 'd blocagi ambelessì sota për bloché l'acess con drit dë scritura da na chèich adrëssa IP.<br />
Ës blocagi-sì as dev dovresse MACH për evité dij comportament vandàlich, ën strèita osservansa ëd tùit ij prinsipi dla [[{{MediaWiki:Policy-url}}|polìtica ëd {{SITENAME}}]].<br />
Ël blocagi a peul nen ën gnun-a manera esse dovrà për dle question d'ideologìa.

Che a scriva codì che st'adrëssa IP-sì a dev second chiel (chila) esse blocà (pr'esempi, che a buta ij tìtoj ëd pàgine che a l'abio già patì dj'at vandàlich da cost'adrëssa IP-sì).",
'ipaddress'                       => 'Adrëssa IP',
'ipadressorusername'              => 'Adrëssa IP ò stranòm',
'ipbexpiry'                       => 'Fin-a al',
'ipbreason'                       => 'Rason:',
'ipbreasonotherlist'              => 'Àotr motiv',
'ipbreason-dropdown'              => "*Motiv sòlit për ij blòch
** Avej butà d'anformassion fàosse
** Avej gavà contnù da 'nt le pàgine
** Buté porcherìa coma anliure ëd reclam
** Avej butà test sensa sust ant le pàgine
** Avej un deuit da bërsach con la gent
** Avej dovrà vàire cont fòra dij deuit
** Stranòm ch'as peul nen acetesse",
'ipbanononly'                     => "Blòca mach j'utent anònim",
'ipbcreateaccount'                => 'Lassa pa pi creé dij cont neuv',
'ipbemailban'                     => "Nen lassé che l'utent a peula mandé ëd messagi ëd pòsta eletrònica",
'ipbenableautoblock'              => "Blòca an automàtich la dariera adrëssa IP dovrà da l'utent e tute cole dont peuj cheidun as preuva a fé dle modìfiche",
'ipbsubmit'                       => "Bloca st'adrëssa IP-sì",
'ipbother'                        => "N'àutra durà",
'ipboptions'                      => "2 ore:2 hours,1 di:1 day,3 di:3 days,na sman-a:1 week,2 sman-e:2 weeks,1 mèis:1 month,3 mèis:3 months,6 mèis:6 months,n'ann:1 year,për sempe:infinite",
'ipbotheroption'                  => "d'àutr",
'ipbotherreason'                  => 'Àotri motiv/spiegon',
'ipbhidename'                     => "Stërma lë stranòm da 'nt le modìfiche e da 'nt j'elench.",
'ipbwatchuser'                    => "Ten d'euj le pàgine utent e ëd discussion dë st'utent-sì",
'ipballowusertalk'                => "Përmëtt a st'utent-sì ëd modifiché la soa pàgina ëd discussion an mente a l'é blocà",
'ipb-change-block'                => "Torna bloché l'utent con ste ampostassion-sì",
'badipaddress'                    => "L'adrëssa IP che a l'ha dane a l'é nen giusta.",
'blockipsuccesssub'               => 'Blocagi fait',
'blockipsuccesstext'              => "[[Special:Contributions/$1|$1]] a l'é stàit blocà.<br />
Varda [[Special:IPBlockList|lista dj'IP blocà]] Për rivëdde ij blòch.",
'ipb-edit-dropdown'               => 'Motiv dël blòch',
'ipb-unblock-addr'                => 'Dësbloché $1',
'ipb-unblock'                     => "Dësbloché n'utent ò n'adrëssa IP",
'ipb-blocklist'                   => 'Vardé ij blòch ativ',
'ipb-blocklist-contribs'          => 'Contribussion për $1',
'unblockip'                       => "Dësblòca st'adrëssa IP-sì",
'unblockiptext'                   => "Che a dòvra ël mòdulo ëd domanda ambelessì sota për deje andé al drit dë scritura a n'adrëssa IP che a l'era staita blocà.",
'ipusubmit'                       => 'Gava sto blòch-sì',
'unblocked'                       => "[[User:$1|$1]] a l'é stait dësblocà",
'unblocked-id'                    => "Ël blòch $1 a l'é stait gavà via.",
'ipblocklist'                     => 'Adrësse IP e utent blocà',
'ipblocklist-legend'              => "Trové n'utent blocà",
'ipblocklist-username'            => 'Stranòm ò pura adrëssa IP:',
'ipblocklist-sh-userblocks'       => '$1 blòch dij cont',
'ipblocklist-sh-tempblocks'       => '$1 blòch a temp',
'ipblocklist-sh-addressblocks'    => "$1 blòch ëd j'IP",
'ipblocklist-submit'              => 'Arsërca',
'ipblocklist-localblock'          => 'Blocagi local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Àutr blocagi|Àutri blocagi}}',
'blocklistline'                   => "$1, $2 a l'ha blocà $3 ($4)",
'infiniteblock'                   => 'për sempe',
'expiringblock'                   => 'a finiss ël $1 a $2',
'anononlyblock'                   => "mach j'utent anònim",
'noautoblockblock'                => 'blòch automàtich nen ativ',
'createaccountblock'              => 'creassion dij cont blocà',
'emailblock'                      => 'pòsta eletrònica blocà',
'blocklist-nousertalk'            => 'a peul nen modifiché soa pàgina ëd discussion',
'ipblocklist-empty'               => "La lista dij blòch a l'é veujda.",
'ipblocklist-no-results'          => "L'adrëssa IP ò lë stranòm ch'a l'ha ciamà a l'é pa blocà.",
'blocklink'                       => 'blòca',
'unblocklink'                     => 'dësblòca',
'change-blocklink'                => 'cambia blòch',
'contribslink'                    => 'contribussion',
'autoblocker'                     => "A l'é scataje un blocagi përchè soa adrëssa IP a l'é staita dovrà ant j'ùltim temp da l'Utent \"[[User:\$1|\$1]]\". Ël motiv për bloché \$1 a l'é stait: \"'''\$2'''\"",
'blocklogpage'                    => 'Registr dij blocagi',
'blocklog-showlog'                => "St'utent-sì a l'é già stàit blocà. Ël registr dij blòch a l'é dàit sì sota për arferiment:",
'blocklog-showsuppresslog'        => "St'utent-sì a l'é già stàit blocà e stërmà. Ël registr stërmà a l'é dàit sì sota për arferiment:",
'blocklogentry'                   => '"[[$1]]" a l\'é stait blocà për $2 $3',
'reblock-logentry'                => 'cambià le ampostassion dël blòch për [[$1]] con un temp ëd fin ëd $2 $3',
'blocklogtext'                    => "Sossì a l'é ël registr dij blocagi e dësblocagi dj'Utent. J'adrësse che
a son staite blocà n'automàtich ambelessì a së s-ciàiro nen.
Che a varda la [[Special:IPBlockList|lista dj'adrësse IP blocà]] për vëdde
coj che sio ij blocagi ativ al dì d'ancheuj.",
'unblocklogentry'                 => "a l'ha dësblocà $1",
'block-log-flags-anononly'        => 'mach utent anònim',
'block-log-flags-nocreate'        => 'creassion ëd cont neuv blocà',
'block-log-flags-noautoblock'     => "blòch n'autòmatich dësmòrt",
'block-log-flags-noemail'         => 'pòsta eletrònica blocà',
'block-log-flags-nousertalk'      => 'a peul nen modifiché soa pàgina ëd discussion',
'block-log-flags-angry-autoblock' => 'blòch automàtich avansà ativà',
'block-log-flags-hiddenname'      => 'stranòm stërmà',
'range_block_disabled'            => "La possibilità che n'aministrator a fasa dij blocagi a ragg a l'é disabilità.",
'ipb_expiry_invalid'              => 'Temp dë scadensa nen bon.',
'ipb_expiry_temp'                 => "Ij blòch ëd j'utent stërmà a deuvo esse përmanent.",
'ipb_hide_invalid'                => 'Ampossìbil scanselé sto cont-sì; a peul avèj tròpe modìfiche.',
'ipb_already_blocked'             => '"$1" a l\'é già blocà',
'ipb-needreblock'                 => "== Già blocà ==
$1 a l'é già blocà.
It veule cambié le ampostassion?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Àutr|Àutri}} blocagi',
'ipb_cant_unblock'                => 'Eror: As treuva nen ël blòch con identificativ $1. A peul esse che a sia un blòch già gavà via.',
'ipb_blocked_as_range'            => "Eror: L'adrëssa IP $1 a l'ha gnun blòch diret ansima e donca a peul pa esse dësblocà. A resta blocà mach për via ch'a l'é ciapà andrinta al ragg $2, e lolì as peul pa dësblochesse.",
'ip_range_invalid'                => 'Nùmer IP nen bon.',
'ip_range_toolarge'               => "N'antërval ëd blòch pi largh ëd /$1 a l'é pa përmëttù.",
'blockme'                         => 'Blòch-me',
'proxyblocker'                    => "Bloché j'arpetitor (Proxy) doèrt",
'proxyblocker-disabled'           => "Sta funsion-sì a l'é pa abilità.",
'proxyblockreason'                => "Soa adrëssa IP a l'é staita bloca përchè a l'é cola ëd n'arpetitor (proxy) doèrt. Për piasì che a contata al sò fornitor ëd conession e che a lo anforma. As trata d'un problema ëd siguressa motobin serio.",
'proxyblocksuccess'               => 'Bele fait.',
'sorbsreason'                     => "Soa adrëssa IP a l'é listà coma arpetitor doèrt (open proxy) ansima al DNSBL dovrà da {{SITENAME}}.",
'sorbs_create_account_reason'     => "Soa adrëssa IP a l'é listà coma arpetitor doèrt (open proxy) ansima al DNSBL dovrà da {{SITENAME}}. A peul nen creésse un cont.",
'cant-block-while-blocked'        => 'It peule pa bloché àutri utent an mente it ses blocà.',
'cant-see-hidden-user'            => "L'utent che të stas provand a bloché a l'é già stàit blocà e stërmà. Da già ch'it l'has pa ël drit hideuser, it peule pa vëdde o modifiché ël blòch ëd l'utent.",
'ipbblocked'                      => "A peul pa bloché o dësbloché d'àutri utent, përchè a l'é blocà chiel-midem",
'ipbnounblockself'                => 'It peule pa dësbloché ti midem',

# Developer tools
'lockdb'              => 'Blòca la base dat',
'unlockdb'            => 'Dësblòca la base dat',
'lockdbtext'          => "Ën blocand la base dat as fërma la possibilità che tuti j'Utent a peulo modifiché le pàgine ò pura fene 'd neuve, che a peulo cambiesse ij \"sò gust\", che a peulo modifichesse soe liste dla ròba da tnì sot euj, e an general gnun a podrà pì fé dj'operassion che a ciamo dë modifiché la base dat.<br /><br />
Për piasì, che an conferma che sossì a l'é pròpe lòn che a veul fé, e dzortut che a sblocherà la base dat pì ampressa che a peul, an manera che tut a funsion-a torna coma che as dev, pen-a che a l'avrà finisse soa manutension.",
'unlockdbtext'        => "Ën dësblocand la base dat as darà andaré a tuti j'Utent la possibilità dë fé 'd modìfiche a le pàgine ò dë fene ëd neuve, ëd cangé ij \"sò gust\", ëd modifiché soe liste 'd ròba da tnì sot euj, e pì an general dë fé tute cole operassion che a l'han da manca dë fé 'd modìfiche a la base dat.
Për piasì, che an conferma che sòn a l'é da bon lòn che chiel (chila) a veul fé.",
'lockconfirm'         => 'É, i veuj da bon, e sota mia responsabilità, bloché la base dat.',
'unlockconfirm'       => ' É, da bon i veuj dësbloché la base dat, sota mia responsabilità personal.',
'lockbtn'             => 'Blòca la base dat',
'unlockbtn'           => 'Dësblòca la base dat',
'locknoconfirm'       => "Che a varda che a l'é dësmentiasse dë spunté ël quadrèt ëd conferma.",
'lockdbsuccesssub'    => 'Blocagi dla base dat fait',
'unlockdbsuccesssub'  => "Dësblocagi dla base dat fait, ël blòch a l'é stait gavà",
'lockdbsuccesstext'   => "La base dat ëd {{SITENAME}} a l'è staita blocà.
<br />Che as visa mach dë gavé ël blocagi pen-a che a l'ha finì soa manutension.",
'unlockdbsuccesstext' => " La base dat ëd {{SITENAME}} a l'è staita dësblocà.",
'lockfilenotwritable' => "As peul nen ëscrive ant sl'archivi ëd blòch dla base dat. A fa dë manca d'avej n'acess an scritura a st'archivi për podej bloché e dësbloché la base dat.",
'databasenotlocked'   => "La base dat a l'é nen blocà.",

# Move page
'move-page'                    => 'Tramud ëd $1',
'move-page-legend'             => 'Tramudé na pàgina',
'movepagetext'                 => "Dovrand ël mòdul ambelessì sota a cangerà nòm a na pàgina, tramudand-je dapress ëdcò tuta soa cronologìa anvers al nòm neuv.
Ël vej tìtol a resterà trasformà ant na ridiression che a men-a al tìtol neuv.
As peul modifiché automaticament ij redirect che a colego al tìtol original.
Se it desside ëd nen felo, contròla [[Special:DoubleRedirects|rediression dobie]] o [[Special:BrokenRedirects|rediression ch'a men-o da gnun-e part]].
It ses responsàbil ëd controlé che le anliure a men-o andoa as pensa che a deubio mné.

Noté bin: la pàgina a sarà '''nen''' tramudà se a-i fussa già mai n'articol che a l'ha ël nòm neuv, gavà col cas che a sia na pàgina veujda ò pura na ridiression, sempre che bele che essend mach parej a l'abia già nen na soa cronologìa.
Sòn a veul dì che, se a l'avèissa mai da fé n'operassion nen giusta, a podrìa sempe torné a rinominé la pàgina col nòm vej, ma ant gnun cas a podrìa coerté na pàgina che a-i é già.

'''ATENSION!'''
Un cambiament dràstich parej a podrìa dé dle gran-e an dzora a na pàgina motobin visità.
Che a varda mach dë esse pì che sigur d'avej presente le conseguense, prima che fé che fé.",
'movepagetext-noredirectfixer' => "Dovré ël formolari sì-sota a arnominërà na pàgina, tramudand tuta soa stòria al nòm neuv.
Ël tìtol vèj a vnirà na pàgina ëd ridiression al tìtol neuv.
Ch'as sigura ëd controlé le ridiression [[Special:DoubleRedirects|dobie]] o cole [[Special:BrokenRedirects|ch'a marcio nen]].
A l'é responsàbil ëd fé an manera che j'anliure a continuo a ponté andova as pensa ch'a vado.

Ch'a armarca che la pàgina a sarà '''pa''' tramudà s'a-i é già na pàgina con ël tìtol neuv, gavà ch'a sia veuida o na ridiression e ch'a l'abia pa na stòria ëd modìfiche passà.
Son a veul dì ch'a peul torna arnominé na pàgina andré da andova a l'avìa arnominala s'a fa n'eror, e ch'a peul pa coaté na pàgina esistenta.

'''Avis!'''
Sossì a peul esse un cambi dràstich e pa spetà për na pàgina popolar;
për piasì ch'as renda bin cont ëd le conseguense ëd sòn prima d'andé anans.",
'movepagetalktext'             => "La pàgina ëd discussion tacà a costa pàgina d'articol, se a-i é, a sarà tramudà n'automatich ansema a l'artìcol, '''gavà costi cas-sì''':
*quand as tramuda la pàgina tra diferent spassi nominal,
*quand na pàgina ëd discussion nen veujda a-i é già për ël nòm neuv, ò pura
*a l'ha deselessionà ël quadrèt ëd conferma ambelessì sota.
Ant costi cas-sì, se a chërd dë felo, a-j farà da manca dë tramudesse la pàgina ëd discussion daspërchiel, a man.",
'movearticle'                  => "Cang-je nòm a l'artìcol",
'moveuserpage-warning'         => "'''Atension:''' A sta për tramudé na pàgina d'utent. Për piasì ch'a nòta che a sarà tramudà mach la pàgina e che l'utent a sarà \"pa\" arnominà.",
'movenologin'                  => "Che a varda che chiel (chila) a l'è pa rintrà ant ël sistema",
'movenologintext'              => "A venta esse n'Utent registrà e esse [[Special:UserLogin|rintrà ant ël sistema]]
për podej tramudé na pàgina.",
'movenotallowed'               => "A l'ha pa ij përmess dont a fa da manca për tramudé pàgine.",
'movenotallowedfile'           => "It l'has pa ij përmess për tramudé dij file.",
'cant-move-user-page'          => "It l'has pa ij përmess për tramudé le pàgine utent (a men dle sotpàgine).",
'cant-move-to-user-page'       => "It l'has pa ij përmess për tramudé na pàgina a na pàgina utent (an gavand a na sotpàgina utent).",
'newtitle'                     => 'Neuv tìtol ëd',
'move-watch'                   => 'Ten sot euj sta pàgina-sì',
'movepagebtn'                  => 'Tramuda sta pàgina-sì',
'pagemovedsub'                 => 'San Martin bele finì!',
'movepage-moved'               => "'''\"\$1\" a l'é stàit spostà a \"\$2\"'''",
'movepage-moved-redirect'      => "A l'é stàita creà na rediression.",
'movepage-moved-noredirect'    => "La creassion ëd na ridiression a l'é stàita scancelà.",
'articleexists'                => "Na pàgina che as ciama parej a-i é già, ò pura ël nòm che a l'ha sërnù a va nen bin.<br />
Che as sërna, për piasì, un nòm diferent për st'articol.",
'cantmove-titleprotected'      => "As peul pa fesse San Martin ambelelì, për via che col tìtol-lì a l'é stàit proibì e a peul pa ess-ie na pàgina ciamà parej",
'talkexists'                   => "La pàgina a l'é staita bin tramudà, ma a l'é pa podusse tramudé soa pàgina ëd discussion, përchè a-i në j'é già n'àutra ant la pàgina con ël tìtol neuv. Për piasì, che a modìfica a man ij contnù dle doe pàgine ëd discussion, an manera che as perdo nen dij pensé anteressant.",
'movedto'                      => 'tramudà a',
'movetalk'                     => "Podend, tramuda ëdcò la pàgina ëd discussion che a l'ha tacà.",
'move-subpages'                => 'Tramuda le sotpàgine (fin a $1)',
'move-talk-subpages'           => 'Tramuda le sotpàgine ëd na pàgina ëd discussion (fin a $1)',
'movepage-page-exists'         => 'La pàgina $1 a esist già e a peul pa esse coatà automaticament.',
'movepage-page-moved'          => "La pàgina $1 a l'é stàita tramudà a $2.",
'movepage-page-unmoved'        => 'La pàgina $1 a peul pa esse tramudà a $2.',
'movepage-max-pages'           => "Ël massim ëd {{PLURAL:$1|na pàgina a l'é stàita |$1 pàgine a son stàite}} tramudà e a na saran pa pì tramudà automaticament.",
'1movedto2'                    => '[[$1]] Tramudà a [[$2]]',
'1movedto2_redir'              => '[[$1]] tramudà a [[$2]] ën passand për na ridiression',
'move-redirect-suppressed'     => 'ridiression ëscancelà',
'movelogpage'                  => 'Registr dij San Martin',
'movelogpagetext'              => 'Ambelessì sota a-i é na lista ëd pàgine che a son staite tramudà.',
'movesubpage'                  => '{{PLURAL:$1|Sotpàgina|Sotpàgine}}',
'movesubpagetext'              => "Sta pàgina-sì a l'ha $1 {{PLURAL:$1|sotpàgina|sotpàgine}} mostà ambelessì.",
'movenosubpage'                => "Sta pàgina-sì a l'ha pa ëd sotpàgine.",
'movereason'                   => 'Rason:',
'revertmove'                   => "buta torna coma a l'era",
'delete_and_move'              => 'Scancela e tramuda',
'delete_and_move_text'         => '==A fa da manca dë scancelé==

L\'artìcol ëd destinassion "[[:$1]]" a-i é già. Veul-lo scancelelo për avej ëd pòst për tramudé l\'àutr?',
'delete_and_move_confirm'      => 'É, scancela la pàgina',
'delete_and_move_reason'       => "Scancelà për liberé ël pòst për tramudene n'àutra",
'selfmove'                     => "Tìtol neuv e tìtol vej a resto midem antra lor; as peul pa tramudesse na pàgina butand-la andoa che a l'é già.",
'immobile-source-namespace'    => 'As peul pa tramudé ëd pàgine ant ël namespace "$1"',
'immobile-target-namespace'    => 'As peul pa tramudé ëd pàgine ant ël namespace "$1"',
'immobile-target-namespace-iw' => "Un colegament interwiki a l'é pa na destinassion vàlida për tramudé na pàgina.",
'immobile-source-page'         => 'Sta pàgina-sì as peul pa tramudesse.',
'immobile-target-page'         => 'As peul pa tramudess al tìtol dë sta destinassion-sì.',
'imagenocrossnamespace'        => 'As peul pa tramudé un file fòra dal sò namespace',
'nonfile-cannot-move-to-file'  => "As peul nen tramudesse lòn ch'a l'é pa n'archivi a lë spassi nominal dj'archivi",
'imagetypemismatch'            => 'La neuva estension dël file a corispond pa a sò tipo',
'imageinvalidfilename'         => "Ël nòm dël file pontà a l'é pa vàlid",
'fix-double-redirects'         => 'Modìfica minca rediression che a ponta al tìtol original',
'move-leave-redirect'          => 'Lassa na rediression',
'protectedpagemovewarning'     => "'''Avis:''' Sta pàgina-sì a l'é stàita blocà parèj che mach utent con drit d'aministrator a peulo tramudela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'semiprotectedpagemovewarning' => "'''Nòta:''' Sta pàgina-sì a l'é stàita blocà parèj che mach j'utent argistrà a peulo tramudela.
L'ùltima vos dël registr a l'é smonùa sì-sota për arferiment:",
'move-over-sharedrepo'         => "== L'archivi a esist ==
[[:$1]] a esist già dzora a un depòsit partagià. Tramudé n'archivi a cost tìtol-sì a coaterà l'archivi partagià.",
'file-exists-sharedrepo'       => "Ël nòm dël file sërnù a l'é già dovrà ant ël depòsit condivis.
Për piasì sern n'àutr nòm.",

# Export
'export'            => 'Esporté dle pàgine',
'exporttext'        => "A peul esporté ël test e modifiché la stòria ëd na pàgina ò pura
ëd n'ansema ëd pàgine gropa ant n'archivi XML. Sòn a peul peuj amportesse
ant n'àutra wiki ën dovrand la funsion Special:Ampòrta pàgina.

Për esporté le pàgine, che a së scriva ij tìtoj ant ël quàder ambelessì sota, butand-ji un tìtol për riga,
e che as serna se a veul la version corenta ansema a cole veje, con le righe che conto la stòria dla pàgina,
ò pura mach l'anformassion ant sël quand che a sia staje l'ùltima modìfica.

Se costa ùltima possibilità a fussa lòn che a-j serv, a podrìa ëdcò dovré n'anliura, pr'esempi [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] për la pàgina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => 'Ciapa sù mach la version corenta, pa tuta la stòria',
'exportnohistory'   => "----
'''Nòta:''' la possibilità d'esporté la stòria completa dle pàgine a l'é staita gavà për dle question corelà a le prestassion dël sistema.",
'export-submit'     => 'Esporté',
'export-addcattext' => "Gionta pàgine da 'nt la categorìa:",
'export-addcat'     => 'Gionta',
'export-addnstext'  => 'Gionta pàgine dal namespace',
'export-addns'      => 'Gionta',
'export-download'   => 'Ciamé dë salvelo coma archivi',
'export-templates'  => 'Ciapa andrinta jë stamp',
'export-pagelinks'  => 'Anseriss pàgine colegà a na përfondità ëd:',

# Namespace 8 related
'allmessages'                   => 'Messagi ëd sistema',
'allmessagesname'               => 'Nòm',
'allmessagesdefault'            => "Test che a-i sarìa se a-i fusso pa 'd modìfiche",
'allmessagescurrent'            => 'Test corent',
'allmessagestext'               => "Costa-sì a l'é na lista ëd messagi disponìbij ëd sistema ant lë spassi nominal MediaWiki.
Për piasì vìsita [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisassion] e [http://translatewiki.net translatewiki.net] se it veule contribuì a la localisassion general ëd MediaWiki.",
'allmessagesnotsupportedDB'     => "Sta pàgina-sì a peul pa esse dovrà përchè '''\$wgUseDatabaseMessages''' a l'é stàit disabilità.",
'allmessages-filter-legend'     => 'Filtr',
'allmessages-filter'            => 'Filtra për stat ëd përsonalisassion:',
'allmessages-filter-unmodified' => 'Pa modificà',
'allmessages-filter-all'        => 'Tùit',
'allmessages-filter-modified'   => 'Modificà',
'allmessages-prefix'            => 'Filtra për prefiss:',
'allmessages-language'          => 'Lenga:',
'allmessages-filter-submit'     => 'Và',

# Thumbnails
'thumbnail-more'           => 'Slarga',
'filemissing'              => 'Archivi che a manca',
'thumbnail_error'          => 'Eror antramentr che as fasìa la figurin-a: $1',
'djvu_page_error'          => 'Pàgina DjVu fòra dij lìmit',
'djvu_no_xml'              => "As rièss pa a carié l'XML për l'archivi DjVu",
'thumbnail_invalid_params' => 'Paràmetro dla figurin-a pa giust',
'thumbnail_dest_directory' => 'As peul pa fesse ël dossié ëd destinassion',
'thumbnail_image-type'     => 'Sòrt ëd figura nen gestì',
'thumbnail_gd-library'     => 'Configurassion pa finìa dla librerìa GD: Fonsion $1 mancanta',
'thumbnail_image-missing'  => 'Ël file a smija esse mancant: $1',

# Special:Import
'import'                     => 'Amportassion ëd pàgine',
'importinterwiki'            => 'Amportassion da wiki diferente',
'import-interwiki-text'      => "Che a selession-a na wiki e ël tìtol dla pàgina da amporté.
Date dle revision e stranòm dj'editor a resteran piajit sù 'cò lor.
Tute le amportassion antra wiki diferente a resto marcà ant ël [[Special:Log/import|Registr dj'amportassion]].",
'import-interwiki-source'    => 'Sorziss wiki/pàgina:',
'import-interwiki-history'   => 'Còpia tute le version stòriche dë sta pàgina-sì',
'import-interwiki-templates' => 'Ansëriss tùit jë stamp',
'import-interwiki-submit'    => 'Amporté',
'import-interwiki-namespace' => 'Spassi nominal ëd destinassion:',
'import-upload-filename'     => 'Nòm dël file:',
'import-comment'             => 'Oget:',
'importtext'                 => "Për piasì, che as espòrta l'archivi da 'nt la sorgiss wiki esterna ën dovrand l'utiss  Special:Esportassion, che as lo salva ansima a sò disch e peui che a lo caria ambelessì.",
'importstart'                => 'I soma antramentr che amportoma le pàgine...',
'import-revision-count'      => '{{PLURAL:$1|Na|$1}} revision',
'importnopages'              => 'Pa gnun-a pàgina da amporté',
'imported-log-entries'       => 'Amportà $1 {{PLURAL:$1|vos ëd registr|vos ëd registr}}.',
'importfailed'               => 'Amportassion falìa: $1',
'importunknownsource'        => "Sorgiss d'amportassion ëd na sòrt nen conossùa",
'importcantopen'             => "L'archivi da amporté a l'é pa podusse deurbe",
'importbadinterwiki'         => 'Anliura antra wiki diferente malfaita',
'importnotext'               => 'Veujd ò sensa pa gnun test',
'importsuccess'              => 'Amportassion andaita a bon fin!',
'importhistoryconflict'      => "A-i son dle stòrie dë sta pàgina-sì che as contradisso un-a con l'àutra (a peul esse che sta pàgina-sì a l'avèissa già amportala)",
'importnosources'            => "A l'é pa staita definìa gnun-a sorgiss d'amportassion da na wiki diferenta, e carié mach le stòrie as peul nen.",
'importnofile'               => "Pa gnun archivi d'amportassion carià.",
'importuploaderrorsize'      => "A l'é falìe la caria dl'archivi d'amporté. L'archivi a resta pì gròss che lòn ch'as peul cariesse.",
'importuploaderrorpartial'   => "A l'é falìe la caria dl'archivi d'amporté. L'archivi a resta carià mach për un tòch.",
'importuploaderrortemp'      => "A l'é falìe la caria dl'archivi d'amporté. A-i manca un dossié provisòri.",
'import-parse-failure'       => "Eror dë scomposission XML ant l'amportassion",
'import-noarticle'           => "Pa gnun-a pàgina d'amporté.",
'import-nonewrevisions'      => "Tute le revision a l'ero già stàite amportà.",
'xml-error-string'           => '$1 ant la riga $2, colòna $3 (byte $4): $5',
'import-upload'              => 'Carìa dat XML',
'import-token-mismatch'      => 'Perdù ij dat ëd session.
Për piasì preuva torna.',
'import-invalid-interwiki'   => 'As peul pa amportesse da la wiki spessificà.',

# Import log
'importlogpage'                    => "Registr dj'amportassion",
'importlogpagetext'                => "Amportassion aministrative ëd pàgine e ëd soa stòria da dj'àutre wiki.",
'import-logentry-upload'           => "amportà [[$1]] con un càrich d'archivi",
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revision|revision}}',
'import-logentry-interwiki'        => "Amportà da n'àutra wiki $1",
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revision|revision}} da $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Toa pàgina utent',
'tooltip-pt-anonuserpage'         => 'Pàgina Utent për l',
'tooltip-pt-mytalk'               => 'Toa pàgina ëd discussion e ciaciarade.',
'tooltip-pt-anontalk'             => 'Pàgina ëd ciaciarade për l',
'tooltip-pt-preferences'          => 'Coma che i veuj mia {{SITENAME}}.',
'tooltip-pt-watchlist'            => 'Lista dle pàgine che chiel as ten sot euj.',
'tooltip-pt-mycontris'            => 'Lista ëd toe contribussion',
'tooltip-pt-login'                => "Un a l'é nen obligà a rintré ant al sistema, ma se a lo fa a l",
'tooltip-pt-anonlogin'            => "Un a l'é nen obligà a rintré ant al sistema, ma se a lo fa a l",
'tooltip-pt-logout'               => 'Seurte da',
'tooltip-ca-talk'                 => 'Discussion ansima a sta pàgina ëd contnù.',
'tooltip-ca-edit'                 => 'Modifiché sta pàgina-sì. Për piasì, che as fasa na preuva anans che salvé .',
'tooltip-ca-addsection'           => 'Ancamin-a na neuva session',
'tooltip-ca-viewsource'           => 'Sta pàgina-sì a l',
'tooltip-ca-history'              => 'Veje version dla pàgina.',
'tooltip-ca-protect'              => 'Për protege sta pàgina-sì.',
'tooltip-ca-unprotect'            => 'Sprotegg sta pàgina-sì',
'tooltip-ca-delete'               => 'Scancelé sta pàgina-sì',
'tooltip-ca-undelete'             => 'Pijé andré le modìfiche faite a sta pàgina-sì, anans che a fussa scancelà.',
'tooltip-ca-move'                 => 'Tramudé sta pàgina, visadì cangeje tìtol.',
'tooltip-ca-watch'                => 'Gionté sta pàgina-sì a la lista dle ròbe che as ten-o sot euj.',
'tooltip-ca-unwatch'              => 'Gavé via sta pàgina da',
'tooltip-search'                  => 'Sërca an {{SITENAME}}',
'tooltip-search-go'               => "Andé a na pàgina ch'as ciama parej, sempe ch'a-i në sia un-a",
'tooltip-search-fulltext'         => 'Sërché ës test-sì antra le pàgine dël sit',
'tooltip-p-logo'                  => 'Pàgina prinsipal.',
'tooltip-n-mainpage'              => 'Visité la pàgina prinsipal.',
'tooltip-n-mainpage-description'  => 'Vìsita la pàgina prinsipal',
'tooltip-n-portal'                => 'Rësguard al proget, lòn che a peul fé, andoa trové còsa.',
'tooltip-n-currentevents'         => 'Informassion ansima a lòn che a-i riva.',
'tooltip-n-recentchanges'         => 'Lista dj',
'tooltip-n-randompage'            => 'Carié na pàgina basta che a sia.',
'tooltip-n-help'                  => 'Ël pòst për capì.',
'tooltip-t-whatlinkshere'         => 'Lista ëd tute le pàgine dla wiki che a men-o ambelessì.',
'tooltip-t-recentchangeslinked'   => 'Ùltime modìfiche dle pàgine andoa as peul andesse da costa.',
'tooltip-feed-rss'                => 'RSS feed për sta pàgina-sì.',
'tooltip-feed-atom'               => 'Atom feed për sta pàgina-sì.',
'tooltip-t-contributions'         => 'Vardé la lista dle contribussion dë st',
'tooltip-t-emailuser'             => 'Mandeje un messagi ëd pòsta a st',
'tooltip-t-upload'                => 'Carié archivi ëd figure ò son.',
'tooltip-t-specialpages'          => 'Lista ëd tute le pàgine speciaj.',
'tooltip-t-print'                 => 'Version bon-a da stampé dë sta pàgina',
'tooltip-t-permalink'             => 'Anliura fissa a sta version-i dla pàgina',
'tooltip-ca-nstab-main'           => 'Vardé la pàgina ëd contnù.',
'tooltip-ca-nstab-user'           => 'Vardé la pàgina Utent.',
'tooltip-ca-nstab-media'          => 'Vardé la pàgina dl',
'tooltip-ca-nstab-special'        => 'Costa a l',
'tooltip-ca-nstab-project'        => 'Vardé la pàgina proteta.',
'tooltip-ca-nstab-image'          => 'Vardé la pàgina dl',
'tooltip-ca-nstab-mediawiki'      => 'Vardé ël messagi ëd sistema.',
'tooltip-ca-nstab-template'       => 'Vardé lë stamp.',
'tooltip-ca-nstab-help'           => 'Vardé la pàgina d',
'tooltip-ca-nstab-category'       => 'Vardé la pàgina dla categorìa.',
'tooltip-minoredit'               => 'Marca sossì coma modìfica cita',
'tooltip-save'                    => 'Salva le modìfiche',
'tooltip-preview'                 => 'Preuva dle modìfiche (mej sempe fela, prima che fé che salvé!)',
'tooltip-diff'                    => "Fame vëdde che modìfiche che i l'hai faje al test.",
'tooltip-compareselectedversions' => 'Fame ël paragon dle diferense antra le version selessionà.',
'tooltip-watch'                   => 'Gionta sta pàgina-sì a la lista dle ròbe che im ten-o sot euj',
'tooltip-recreate'                => 'Creé torna la pàgina contut che a la sia staita scancelà',
'tooltip-upload'                  => 'Anandiesse a carié',
'tooltip-rollback'                => '"Rollback" a scansela con un clich le modìfiche fàite a costa pagina da l\'ùltim contribudor',
'tooltip-undo'                    => '"Undo" a scansela costa modìfica e a deurb la fnestra ëd modìfica an manera ëd vardé prima.
At lassa gionté na spiegassion ëd la modìfica.',
'tooltip-preferences-save'        => 'Salvé ij sò gust',
'tooltip-summary'                 => 'Anserì un curt resumé',

# Stylesheets
'common.css'   => '/** Ël còdes CSS che as buta ambelessì a resta dovrà ant tute le "facie" */',
'monobook.css' => "/* cangé st'archivi-sì për modifiché la formatassion dël sit antregh */",

# Scripts
'common.js'   => "/* Ël còdes JavaScript ch'as buta ambelessì a ven carià da vira utent për vira pàgina */",
'monobook.js' => "/* Ës messagi-sì as dovrìa pa pì dovrelo; a sò pòst ch'a dòvra [[MediaWiki:common.js]] */",

# Metadata
'nodublincore'      => "Ij metadat dla sòrt '''Dublin Core RDF''' a son disabilità ansima a sta màchina-sì.",
'nocreativecommons' => "Ij metadat dla sòrt '''Creative Commons RDF''' a son disabilità ansima a sta màchina-sì.",
'notacceptable'     => 'Ël server dla wiki a-i la fa pa a provëdde dij dat ant na forma che sò programa local a peula lese.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utent|Utent}} anònim ëd {{SITENAME}}',
'siteuser'         => '$1, utent ëd {{SITENAME}}',
'anonuser'         => '{{SITENAME}} utent anònim $1',
'lastmodifiedatby' => "Sta pàgina-sì a l'é staita modificà l'ùltima vira al $2, $1 da $3.",
'othercontribs'    => 'Basà ant sëj travaj ëd $1.',
'others'           => 'àutri',
'siteusers'        => '$1, {{PLURAL:$2|utent|utent}} ëd {{SITENAME}}',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|utent|utent}} anònim $1',
'creditspage'      => 'Credit dla pàgina',
'nocredits'        => 'A-i é pa gnun crédit për sta pagina-sì.',

# Spam protection
'spamprotectiontitle' => 'Filtror dla rumenta',
'spamprotectiontext'  => "La pàgina che a vorìa salvé a l'é staita blocà dal filtror dla rumenta.
Sòn a l'é motobin belfé che a sia rivà përchè a-i era n'anliura a un sit estern ëd coj blocà.",
'spamprotectionmatch' => "Cost-sì a l'é ël test che a l'é restà ciapà andrinta al filtror dla rumenta: $1",
'spambot_username'    => 'MediaWiki - trigomiro che a-j dà deuit a la rumenta',
'spam_reverting'      => "Buta andaré a l'ùltima version che a l'avèissa pa andrinta dj'anliure a $1",
'spam_blanking'       => "Pàgina dësveujdà, che tute le version a l'avìo andrinta dj'anliure a $1",

# Info page
'infosubtitle'   => 'Anformassion për la pàgina',
'numedits'       => 'Nùmer ëd modìfiche (artìcol): $1',
'numtalkedits'   => 'Nùmer ëd modìfiche (pàgina ëd discussion): $1',
'numwatchers'    => "Nùmer d'utent che as ten-o sossì sot euj: $1",
'numauthors'     => "Nùmer d'autor diferent (artìcol): $1",
'numtalkauthors' => "Nùmer d'autor distint (pàgina ëd discussion): $1",

# Math options
'mw_math_png'    => 'Most-lo sempe coma PNG',
'mw_math_simple' => "But-lo an HTML se a l'é motobin belfé a fesse, dësnò but-lo an PNG",
'mw_math_html'   => 'But-lo an HTML se as peul, dësnò an PNG',
'mw_math_source' => 'Lass-lo coma TeX (për ij programa ëd navigassion testual)',
'mw_math_modern' => 'As racomanda për ij programa ëd navigassion pì modern',
'mw_math_mathml' => 'But-lo an MathML se as peul (sperimental)',

# Math errors
'math_failure'          => 'Parsificassion falà',
'math_unknown_error'    => 'Eror nen conossù',
'math_unknown_function' => 'funsion che as sa pa lòn che a la sia',
'math_lexing_error'     => 'eror ëd léssich',
'math_syntax_error'     => 'eror ëd sintassi',
'math_image_error'      => "Conversion a PNG falà; che a contròla l'ùltima instalassion ëd latex e dvipng (o dvips + gs + convert)",
'math_bad_tmpdir'       => "Ël sistema a-i la fa pa a creé la diretriss '''math temp''', ò pura a-i la fa nen a scriv-je andrinta",
'math_bad_output'       => "Ël sistema a-i la fa pa a creé la diretriss '''math output''', ò pura a-i la fa nen a scriv-je andrinta",
'math_notexvc'          => 'Pa gnun texvc executable; për piasì, che a contròla math/README për la configurassion.',

# Patrolling
'markaspatrolleddiff'                 => 'Marca coma verificà',
'markaspatrolledtext'                 => "Marca st'artìcol-sì coma verificà",
'markedaspatrolled'                   => 'Marca dla verìfica butà',
'markedaspatrolledtext'               => "La version selessionà ëd [[:$1]] a l'é staita marcà coma verificà.",
'rcpatroldisabled'                    => "Verìfica dj'ùltime modìfiche disabilità",
'rcpatroldisabledtext'                => "La possibilità ëd verifichè j'ùltime modìfiche a l'é disabilità.",
'markedaspatrollederror'              => 'As peul pa marchè verificà',
'markedaspatrollederrortext'          => 'A venta che a specìfica che version che a veul marchè verificà.',
'markedaspatrollederror-noautopatrol' => 'A l\'ha nen ël përmess dë marchesse soe modìfiche coma "controlà".',

# Patrol log
'patrol-log-page'      => 'Registr dij contròj',
'patrol-log-header'    => "Sto sì a l'é un registr ëd le revision verificà.",
'patrol-log-line'      => "a l'ha marcà la $1 ëd $2 coma controlà $3",
'patrol-log-auto'      => '(automàtich)',
'patrol-log-diff'      => 'modìfica $1',
'log-show-hide-patrol' => '$1 registr verificà',

# Image deletion
'deletedrevision'                 => 'Veja version scancelà $1',
'filedeleteerror-short'           => "Eror ën scanceland l'archivi: $1",
'filedeleteerror-long'            => "A son ësta-ie dj'eror ën scanceland l'archivi:

$1",
'filedelete-missing'              => 'L\'archivi "$1" as peul pa dëscancelesse, për via ch\'a-i é nen.',
'filedelete-old-unregistered'     => 'La revision d\'archivi specificà "$1" ant la base dat a-i é nen.',
'filedelete-current-unregistered' => 'Ant la base dat l\'archivi "$1" ch\'a l\'é specificasse a-i é pa.',
'filedelete-archive-read-only'    => 'Ël servent dla Ragnà a peul pa scriv-ie ant ël dossié dj\'archivi "$1".',

# Browsing diffs
'previousdiff' => '← Diferensa pì veja',
'nextdiff'     => 'Modìfica pì neuva →',

# Media information
'mediawarning'         => "'''Atension!''': st'archivi-sì a podrìa avèj andrinta dël còdes butà-lì da cheidun për fé ëd darmagi.
An fasend-lo travajé ansima a sò ordinator chiel a podrìa porteje ëd dann a sò sistema.",
'imagemaxsize'         => "Lìmit ëd la dimension ëd la figura:<br /> ''(për pàgine ëd descrission dij file)''",
'thumbsize'            => 'Amzura dle figurin-e:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pàgina|pàgine}}',
'file-info'            => "amzura dl'archivi: $1, sòrt MIME: $2",
'file-info-size'       => '$1 × $2 pixel, amzure: $3, sòrt MIME: $4',
'file-nohires'         => '<small>Gnun-a risolussion pì bela disponibila.</small>',
'svg-long-desc'        => "archivi an forma SVG, amzure nominaj $1 × $2 pixel, amzura dl'archivi: $3",
'show-big-image'       => 'Version a amzura pijn-a',
'show-big-image-thumb' => '<small>Amzure dë sta figurin-a: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'ciclà',
'file-info-gif-frames' => '$1 {{PLURAL:$1|fnesta|fneste}}',
'file-info-png-looped' => 'an sìrcol',
'file-info-png-repeat' => 'sonà $1 {{PLURAL:$1|vira|vire}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|quàder|quàder}}',

# Special:NewFiles
'newimages'             => 'Galerìa ëd figure e son neuv',
'imagelisttext'         => "Ambelessì sota a-i é {{PLURAL:$1|l'ùnica figura che a-i sia|na lista ëd '''$1''' figure, ordinà për $2}}.",
'newimages-summary'     => "Sta pàgina special-sì a la smon j'ùltim archivi carià.",
'newimages-legend'      => 'Filtror',
'newimages-label'       => 'Nòm dël file (o ëd part dël file):',
'showhidebots'          => '($1 trigomiro)',
'noimages'              => 'Pa gnente da vëdde.',
'ilsubmit'              => 'Sërca',
'bydate'                => 'për data',
'sp-newimages-showfrom' => "Smon j'ùltim archivi multimojen a anandiesse da $2 dël $1",

# Bad image list
'bad_image_list' => "La forma a l'é costa-sì:

As ten-o bon-e mach le liste pontà (cole faite ëd righe ch'as ancamin-o për *). La prima anliura ëd minca riga a l'ha da mné a n'archivi multimojen nen bon.
J'anliure ch'a-i ven-o dapress, ant sla midema riga, as conto për ecession (visadì, për pàgine andova st'archivi as peul butesse).",

# Metadata
'metadata'          => 'Dat adissionaj',
'metadata-help'     => "Costi-sì a son dij dat adissionaj, che a l'é belfé che a sio stait giontà da la màchina fotogràfica digital ò pura da lë scanner che a l'é stiat dovrà për creé la figura digital. Se la figura a fussa mai staita modificà da 'nt soa forma original, a podrìa ëdcò riveje che chèich detaj a fussa ancò butà coma ant l'original, donca sensa pa ten-e cont ëd le modìfiche.",
'metadata-expand'   => 'Most-me tùit ij dat',
'metadata-collapse' => 'Stërma ij dat adissionaj',
'metadata-fields'   => "Ij camp dij metadat EXIF lista ant ës messagi-sì a sarà smonù ant sla pàgina dla figura quand la tabela dij metadat a l'é scondùa. J'àotri a saran scondù.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Larghëssa',
'exif-imagelength'                 => 'Autëssa',
'exif-bitspersample'               => 'Bit për campion',
'exif-compression'                 => 'Schema ëd compression',
'exif-photometricinterpretation'   => 'Composission dij pixel',
'exif-orientation'                 => 'Orientament',
'exif-samplesperpixel'             => 'Nùmer ëd component',
'exif-planarconfiguration'         => 'Sistemassion dij dat',
'exif-ycbcrsubsampling'            => 'Rapòrt ëd campionament antra Y e C',
'exif-ycbcrpositioning'            => 'Posissionament Y e C',
'exif-xresolution'                 => 'Risolussion orizontal',
'exif-yresolution'                 => 'Risolussion vertical',
'exif-resolutionunit'              => "Unità d'amzura për le coordinà X e Y",
'exif-stripoffsets'                => 'Posission dij dat dla figura',
'exif-rowsperstrip'                => 'Nùmer ëd righe për banda',
'exif-stripbytecounts'             => 'Bytes për banda compressa',
'exif-jpeginterchangeformat'       => 'Diferensa posissional anvers al SOI dël JPEG',
'exif-jpeginterchangeformatlength' => 'Byte ëd dat an formà JPEG',
'exif-transferfunction'            => 'Funsion ëd trasferiment',
'exif-whitepoint'                  => 'Pont cromàtich dël bianch',
'exif-primarychromaticities'       => 'Coordinà cromàtiche dij color primari',
'exif-ycbcrcoefficients'           => 'Coeficent dla matriss ëd trasformassion dlë spassi color',
'exif-referenceblackwhite'         => "Pàira ëd valor d'arferiment për bianch e nèir",
'exif-datetime'                    => 'Data e ora dle modìfiche',
'exif-imagedescription'            => 'Tìtol dla figura',
'exif-make'                        => 'Fabricant dla màchina fotogràfica ò videocàmera',
'exif-model'                       => 'Model dla màchina',
'exif-software'                    => 'Programa dovrà',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => "Titolar dël drit d'autor",
'exif-exifversion'                 => 'Version dël formà Exif',
'exif-flashpixversion'             => 'A riva a la version Flashpix',
'exif-colorspace'                  => 'Spassi color',
'exif-componentsconfiguration'     => 'Sust ëd vira component',
'exif-compressedbitsperpixel'      => 'Sistema ëd compression dle figure',
'exif-pixelydimension'             => 'Larghëssa vàlida dla figura',
'exif-pixelxdimension'             => 'Autëssa vàlida dla figura',
'exif-makernote'                   => 'Nòte dël fabricant',
'exif-usercomment'                 => 'Nòte lìbere',
'exif-relatedsoundfile'            => 'Archivi audio colegà',
'exif-datetimeoriginal'            => 'Data e ora dla generassion dij dat',
'exif-datetimedigitized'           => 'Data e ora dla digitalisassion',
'exif-subsectime'                  => 'Data, ora e frassion ëd second',
'exif-subsectimeoriginal'          => 'Data e ora ëd creassion, con frassion ëd second',
'exif-subsectimedigitized'         => 'Data e ora ëd digitalisassion, con frassion ëd second',
'exif-exposuretime'                => "Temp d'esposission",
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => "Nùmer d'F",
'exif-exposureprogram'             => "Programa d'esposission",
'exif-spectralsensitivity'         => 'Sensibilità dë spetro',
'exif-isospeedratings'             => 'Sensibilità ISO',
'exif-oecf'                        => 'Fator ëd conversion optoeletrònica',
'exif-shutterspeedvalue'           => 'Temp dë scat',
'exif-aperturevalue'               => 'Diaframa',
'exif-brightnessvalue'             => 'Luminosità',
'exif-exposurebiasvalue'           => "Coression dl'esposission",
'exif-maxaperturevalue'            => 'Apertura màssima',
'exif-subjectdistance'             => 'Distansa dël soget',
'exif-meteringmode'                => "Càlcol dl'espossision",
'exif-lightsource'                 => "Sorgiss d'anluminassion",
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Lunghëssa focal dle lent',
'exif-subjectarea'                 => "Spassi d'anquadratura dël soget",
'exif-flashenergy'                 => 'Potensa dël flash',
'exif-spatialfrequencyresponse'    => 'Arspòsta an frequensa spassial',
'exif-focalplanexresolution'       => 'Resolussion dla coordinà X ant sël pian dla focal',
'exif-focalplaneyresolution'       => 'Resolussion dla coordinà Y ant sël pian dla focal',
'exif-focalplaneresolutionunit'    => "Unità d'amzura për ël pian dla focal",
'exif-subjectlocation'             => 'Posission dël soget',
'exif-exposureindex'               => "Ìndes dl'esposission",
'exif-sensingmethod'               => 'Metod ëd campionament',
'exif-filesource'                  => "Sorgiss dl'archivi",
'exif-scenetype'                   => "Sòrt d'anquadratura",
'exif-cfapattern'                  => 'Schema CFA',
'exif-customrendered'              => 'Process dla figura particolar',
'exif-exposuremode'                => "Modalità dl'esposission",
'exif-whitebalance'                => 'Balansa dël bianch',
'exif-digitalzoomratio'            => 'Rapòrt ëd lë zoom digital',
'exif-focallengthin35mmfilm'       => 'Lunghëssa focal an film da 35 mm',
'exif-scenecapturetype'            => 'Sistema ëd campionament',
'exif-gaincontrol'                 => 'Contròl ëd sienari',
'exif-contrast'                    => 'Contrast',
'exif-saturation'                  => 'Saturassion',
'exif-sharpness'                   => 'Definission dij bòrd',
'exif-devicesettingdescription'    => "Nòm dla configurassion dl'aparechiatura",
'exif-subjectdistancerange'        => 'Ragg ëd distansa dël soget',
'exif-imageuniqueid'               => 'Identificator ùnich dla figura',
'exif-gpsversionid'                => 'Version dël GPS',
'exif-gpslatituderef'              => 'Latitùdin setentrional ò meridional',
'exif-gpslatitude'                 => 'Latitùdin',
'exif-gpslongituderef'             => 'Longitùdin oriental ò ossidental',
'exif-gpslongitude'                => 'Longitùdin',
'exif-gpsaltituderef'              => "Arferiment d'autëssa",
'exif-gpsaltitude'                 => 'Autëssa',
'exif-gpstimestamp'                => 'Ora dël GPS (mostra atòmica)',
'exif-gpssatellites'               => "Satélit dovrà për l'amzura",
'exif-gpsstatus'                   => 'Condission dël ricevitor',
'exif-gpsmeasuremode'              => "Sistema d'amzura",
'exif-gpsdop'                      => "Precision dl'amzura",
'exif-gpsspeedref'                 => "Unità d'amzura për la velocità",
'exif-gpsspeed'                    => 'Velocità dël ricevitor GPS',
'exif-gpstrackref'                 => 'Arferiment për la diression dël moviment',
'exif-gpstrack'                    => 'Diression dël moviment',
'exif-gpsimgdirectionref'          => 'Arferiment për la diression dla figura',
'exif-gpsimgdirection'             => 'Diression dla figura',
'exif-gpsmapdatum'                 => "Dat dl'amzura geodética che a son dovrà",
'exif-gpsdestlatituderef'          => 'Arferiment për la latitùdin dla destinassion',
'exif-gpsdestlatitude'             => 'Latitùdin dla destinassion',
'exif-gpsdestlongituderef'         => 'Arferiment për la longitùdin dla destinassion',
'exif-gpsdestlongitude'            => 'Longitùdin dla destinassion',
'exif-gpsdestbearingref'           => "Arferiment për l'orientament a destinassion",
'exif-gpsdestbearing'              => 'Orientament anvers a la destinassion',
'exif-gpsdestdistanceref'          => "Arferiment për la lontanansa da 'nt la destinassion",
'exif-gpsdestdistance'             => "Lontanansa da 'nt la destinassion",
'exif-gpsprocessingmethod'         => 'Nòm dël sistema ëd process an GPS',
'exif-gpsareainformation'          => 'Nòm dlë spassi GPS',
'exif-gpsdatestamp'                => 'Data dël GPS',
'exif-gpsdifferential'             => 'Coression diferensial dël GPS',
'exif-objectname'                  => 'Tìtol curt',

# EXIF attributes
'exif-compression-1' => 'Pa compress',

'exif-unknowndate' => 'Data nen conossùa',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Specolar',
'exif-orientation-3' => 'Arvirà ëd 180°',
'exif-orientation-4' => 'Arvirà dzorsuta',
'exif-orientation-5' => 'Arvirà dzorsota e ëd 90° contramostra',
'exif-orientation-6' => 'Arvirà ëd 90° ant ël sens dla mostra',
'exif-orientation-7' => 'Arvirà dzorsota e ëd 90° ant ël sens dla mostra',
'exif-orientation-8' => 'Arvirà ëd 90° contramostra',

'exif-planarconfiguration-1' => 'për blòch (chunky)',
'exif-planarconfiguration-2' => 'an planar',

'exif-xyresolution-i' => '$1 pont për pòles (dpi)',
'exif-xyresolution-c' => '$1 pont për centim (dpc)',

'exif-colorspace-ffff.h' => 'Nen calibrà',

'exif-componentsconfiguration-0' => 'a esist pa',

'exif-exposureprogram-0' => 'Nen definì',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Priorità ëd temp',
'exif-exposureprogram-4' => 'Priorità ëd diaframa',
'exif-exposureprogram-5' => "Programa creativ (coregiù për avej pì ëd profondità 'd camp)",
'exif-exposureprogram-6' => "Programa d'assion (coregiù për avej ël temp pì curt che as peul)",
'exif-exposureprogram-7' => 'Programa ritrat (për fotografìe pijaite da davsin, con lë sfond fòra feu)',
'exif-exposureprogram-8' => 'Panorama (sogèt lontan e con lë sfond a feu)',

'exif-subjectdistance-value' => '$1 méter',

'exif-meteringmode-0'   => 'as sa nen coma',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Media centrà',
'exif-meteringmode-3'   => 'Quadrèt (Spot)',
'exif-meteringmode-4'   => 'Vàire quadrèt (MultiSpot)',
'exif-meteringmode-5'   => 'Schema (Pattern)',
'exif-meteringmode-6'   => 'Parsial',
'exif-meteringmode-255' => "n'àutr",

'exif-lightsource-0'   => 'Nen marcà',
'exif-lightsource-1'   => 'Lus dël dì',
'exif-lightsource-2'   => 'Fluoressenta',
'exif-lightsource-3'   => 'Lus al tungsten (a incandessensa)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Temp bel',
'exif-lightsource-10'  => 'Temp an-nivolà',
'exif-lightsource-11'  => 'Ombra',
'exif-lightsource-12'  => 'Fluoressensa tipo lus dël dì (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluoressensa bianca për ël dì (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluoressensa bianca frèida (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluoressensa bianca (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lus stàndard sòrt A',
'exif-lightsource-18'  => 'Lus stàndard sòrt B',
'exif-lightsource-19'  => 'Lus stàndard sòrt C',
'exif-lightsource-20'  => 'Anluminant D55',
'exif-lightsource-21'  => 'Anluminant D65',
'exif-lightsource-22'  => 'Anluminant D75',
'exif-lightsource-23'  => 'Anluminant D50',
'exif-lightsource-24'  => 'Làmpada da studio ISO al tungsten',
'exif-lightsource-255' => "Aùtra sorgiss d'anluminassion",

# Flash modes
'exif-flash-fired-0'    => "Ël flash a l'é pa scatà",
'exif-flash-fired-1'    => 'Flash scatà',
'exif-flash-return-0'   => "Gnun-e fonsion ëd rilevassion dl'artorn ëd lë stroboscòpi",
'exif-flash-return-2'   => "lus stoboscòpica d'artorn pa arlevà",
'exif-flash-return-3'   => "lus stroboscòpica d'artorn arlevà",
'exif-flash-mode-1'     => 'scat dël flash sforsà',
'exif-flash-mode-2'     => 'eliminassion dël flash sforsà',
'exif-flash-mode-3'     => 'manera automàtica',
'exif-flash-function-1' => 'Gnente fonsion flash',
'exif-flash-redeye-1'   => "Manera ëd ridussion ëd j'euj ross",

'exif-focalplaneresolutionunit-2' => 'pòles anglèis (inches)',

'exif-sensingmethod-1' => 'Nen definì',
'exif-sensingmethod-2' => 'Sensor dlë spassi color a 1 processor',
'exif-sensingmethod-3' => 'Sensor dlë spassi color a 2 processor',
'exif-sensingmethod-4' => 'Sensor dlë spassi color a 3 processor',
'exif-sensingmethod-5' => 'Sensor sequensial dlë spassi color',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear ëd color sequensiaj',

'exif-scenetype-1' => 'Fotografìa an diret',

'exif-customrendered-0' => 'Process normal',
'exif-customrendered-1' => 'Process particular',

'exif-exposuremode-0' => 'Esposission automàtica',
'exif-exposuremode-1' => 'Esposission manual',
'exif-exposuremode-2' => 'Esposission automàtica (auto bracket)',

'exif-whitebalance-0' => "Balansa dël bianch n'automàtich",
'exif-whitebalance-1' => 'Balansa dël bianch an manual',

'exif-scenecapturetype-0' => 'Stàndard',
'exif-scenecapturetype-1' => 'Paisagi',
'exif-scenecapturetype-2' => 'Ritrat',
'exif-scenecapturetype-3' => 'La neuit',

'exif-gaincontrol-0' => 'Gnun',
'exif-gaincontrol-1' => 'Sparé ij contrast bass',
'exif-gaincontrol-2' => 'Sparé ij contrast fòrt',
'exif-gaincontrol-3' => 'Bassé ij contrast bass',
'exif-gaincontrol-4' => 'Bassé ij contrast fòrt',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'dosman',
'exif-contrast-2' => 'contrastà fòrt',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturassion bassa',
'exif-saturation-2' => 'Saturassion àuta',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'dossa',
'exif-sharpness-2' => 'contrastà',

'exif-subjectdistancerange-0' => 'Nen specificà',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Prim pian',
'exif-subjectdistancerange-3' => 'Anquadratura a soget lontan',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitùdin setentrional',
'exif-gpslatitude-s' => 'Latitùdin meridional',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitùdin oriental',
'exif-gpslongitude-w' => 'Longitùdin ossidental',

'exif-gpsstatus-a' => 'Amzura antramentr che as fa',
'exif-gpsstatus-v' => "Interoperabilità dl'amzura",

'exif-gpsmeasuremode-2' => 'amzura bidimensional',
'exif-gpsmeasuremode-3' => 'amzura tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Km/h',
'exif-gpsspeed-m' => 'mija/h',
'exif-gpsspeed-n' => 'Grop (marin)',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Diression vèira',
'exif-gpsdirection-m' => 'Diression magnética',

# External editor support
'edit-externally'      => "Modifiché st'archivi con un programa estern",
'edit-externally-help' => "(Varda [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] për avej pì d'anformassion)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tute',
'imagelistall'     => 'tùit/tute',
'watchlistall2'    => 'tute',
'namespacesall'    => 'tùit',
'monthsall'        => 'tuti',
'limitall'         => 'tùit',

# E-mail address confirmation
'confirmemail'              => "Confermé l'adrëssa postal",
'confirmemail_noemail'      => "A l'ha pa butà gnun-a adrëssa vàlida ëd pòsta eletrònica ant ij [[Special:Preferences|sò gust]].",
'confirmemail_text'         => "Costa wiki a ciama che chiel a convalida n'adrëssa postal anans che
dovré lòn che toca la pòsta. Che a sgnaca ël boton ambelessì sota
për fesse mandé un messa ëd conferma a soa adrëssa eletrònica.
Andrinta al messagi a-i sara n'anliura (URL) con andrinta un còdes.
Che a deurba st'anliura andrinta a sò programa ëd navigassion (browser)
për confermé che soa adrëssa a l'é pròpe cola.",
'confirmemail_pending'      => "I l'oma già mandaje sò còdes ëd conferma;
se a l'ha pen-a creasse sò cont, miraco a venta che a speta dontre minute che a-j riva ant la pòsta, nopà che ciamene un neuv.",
'confirmemail_send'         => 'Manda un còdes ëd conferma për pòsta eletrònica',
'confirmemail_sent'         => "Ël messagi ëd conferma a l'é stait mandà.",
'confirmemail_oncreate'     => "Un còdes ëd conferma a l'é stait mandà a soa adrëssa ëd pòsta eletrònica.
D'ës còdes a fa pa dë manca për rintré ant ël sistema, ma a ventrà che a lo mostra al sistema për podej abilité cole funsion dla wiki che a son basà ant sla pòsta eletrònica.",
'confirmemail_sendfailed'   => "{{SITENAME}} a l'ha pa podù mandete l'e-mail ëd conferma.
Che a controla l'adrëssa che a l'ha dane, mai che a-i fusso dij caràter nen vàlid.

Ël programa ëd pòsta a l'ha arspondù: $1",
'confirmemail_invalid'      => 'Còdes ëd conferma nen vàlid. A podrìa ëdcò mach esse scadù.',
'confirmemail_needlogin'    => 'A venta che a fasa $1 për confermé soa addrëssa postal eletrònica.',
'confirmemail_success'      => "Soa adrëssa postal a l'é staita confermà, adess a peul rintré ant ël sistema e i-j auguroma da fessla bin ant la wiki!",
'confirmemail_loggedin'     => "Motobin mersì. Soa adrëssa ëd pòsta eletrònica adess a l'é confermà.",
'confirmemail_error'        => "Cheich-còs a l'é andà mal ën salvand soa conferma.",
'confirmemail_subject'      => "Conferma dl'adrëssa postal da 'nt la {{SITENAME}}",
'confirmemail_body'         => "Cheidun, a l'é belfé che a sia stait pròpe chiel (ò chila), da 'nt l'adrëssa IP \$1,
a l'ha doertà un cont utent \"\$2\" ansima a {{SITENAME}}, lassand-ne st'adrëssa ëd pòsta eletrònica-sì.

Për confermé che ës cont a l'é da bon sò e për ativé
le possibilità corelà a la pòsta eletrònica ansima a {{SITENAME}}, che a deurba st'adrëssa-sì andrinta a sò programa ëd navigassion (browser):

\$3

Se a fussa *nen* stait chiel a deurbe ël cont, anlora che a vada daré a sto colegament-sì
për scanselé la conferma ëd l'adrëssa e-mail:

\$5

Cost còdes ëd conferma a l'é bon fin-a al \$4.",
'confirmemail_body_changed' => "Cheidun, a l'é belfé ch'a sia chiel, da l'adrëssa IP \$1,
a l'ha cangià l'adrëssa ëd pòsta eletrònica dël cont \"\$2\" con st'adrëssa-sì dzora a {{SITENAME}}.

Për confirmé che sto cont-sì a l'é pròpi sò e për riativé
le possibilità ëd pòsta eletrònica dzora a {{SITENAME}}, ch'a deurba sto colegament-sì an sò navigador:

\$3

Se ël cont a l'é *nen* sò, ch'a vada andré a sto colegament-sì
për scancelé la conferma dl'adrëssa ëd pòsta eletrònica:

\$5

Ës còdes ëd conferma a scadrà a \$4.",
'confirmemail_body_set'     => 'Quaidun, miraco ti, da l\'adrëssa IP $1,
a l\'ha ampostà l\'adrëssa ëd corel dël cont "$2" con costa adrëssa su {{SITENAME}}.

Për confirmé che sto cont a l\'é pròpi tò e torna ativé
le funsion ëd corel su {{SITENAME}}, deurb sto colegament an tò browser:

$3

Se ël cont a l\'é *pa* tò, va daré a sto colegament
për scanselé la confirma ëd l\'adrëssa ëd corel:

$5

Sto còdes ëd confirma a scad ai $4.',
'confirmemail_invalidated'  => "Conferma ëd l'adrëssa e-mail scanselà",
'invalidateemail'           => "Scansela l'e-mail ëd conferma",

# Scary transclusion
'scarytranscludedisabled' => "[L'inclusion ëd pàgine antra wiki diferente a l'é nen abilità]",
'scarytranscludefailed'   => "[Darmagi, ma lë stamp $1 a l'é pa podusse carié]",
'scarytranscludetoolong'  => "[L'URL a l'é tròp longa]",

# Trackbacks
'trackbackbox'      => 'Anformassion për feje ël traciament a sta vos-sì:<br />
$1',
'trackbackremove'   => '([$1 Gava via])',
'trackbacklink'     => 'Traciament',
'trackbackdeleteok' => "J'anformassion për fé traciament a son staite gavà via.",

# Delete conflict
'deletedwhileediting' => "'''Avertensa''': sta pàgina-sì a l'é staita scancelà quand che chiel (chila) a l'avìa già anandiasse a modifichela!",
'confirmrecreate'     => "L'utent [[User:$1|$1]] ([[User talk:$1|talk]]) a l'ha scancelà st'articol-sì quand che chiel (chila) a l'avia già anandiasse a modifichelo, dand coma motiv ëd la scancelament:
''$2''
Për piasì, che an conferma che da bon a veul torna creélo.",
'recreate'            => "Créa n'àutra vira",

# action=purge
'confirm_purge_button' => 'Va bin',
'confirm-purge-top'    => 'Veujdé la memorisassion dë sta pàgina-sì?',
'confirm-purge-bottom' => 'Spurghé na pàgina a scansela la "cache" e a fà aparì le revision pì neuve.',

# Multipage image navigation
'imgmultipageprev' => '← pàgina andré',
'imgmultipagenext' => 'pàgina anans →',
'imgmultigo'       => 'Va',
'imgmultigoto'     => 'Và a la pàgina $1',

# Table pager
'ascending_abbrev'         => 'a chërse',
'descending_abbrev'        => 'a calé',
'table_pager_next'         => 'Pàgina anans',
'table_pager_prev'         => 'Pàgina andré',
'table_pager_first'        => 'Prima pàgina',
'table_pager_last'         => 'Ùltima pàgina',
'table_pager_limit'        => 'Smon-me $1 archivi për pàgina',
'table_pager_limit_label'  => 'Oget për pàgina:',
'table_pager_limit_submit' => 'Va',
'table_pager_empty'        => 'Pa gnun arsultà',

# Auto-summaries
'autosumm-blank'   => 'Pàgina dësveujdà',
'autosumm-replace' => "Pàgina cambià con '$1'",
'autoredircomment' => 'Ridiression anvers a [[$1]]',
'autosumm-new'     => "Creà la pàgina con '$1'",

# Size units
'size-bytes'     => '$1 Byte',
'size-kilobytes' => '$1 KByte',
'size-megabytes' => '$1 MByte',
'size-gigabytes' => '$1 GByte',

# Live preview
'livepreview-loading' => "Antramentr ch'as caria…",
'livepreview-ready'   => "Antramentr ch'as caria… Carià.",
'livepreview-failed'  => 'La "preuva dal viv" a l\'é falìa!
Ch\'a preuva an manera sòlita.',
'livepreview-error'   => 'Conession falà: $1 "$2"
Ch\'a preuva an manera sòlita.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Le modìfiche pì neuve ëd $1 {{PLURAL:$1|second|second}} a podrìo nen ess-ie ant sta lista-sì.',
'lag-warn-high'   => "Për via che la màchina serventa a tarda a dene d'arspòsta, le modìfiche pì giovne che $1 {{PLURAL:$1|second|second}} fa
a podrìo ëdcò nen ess-ie ant sta lista -sì.",

# Watchlist editor
'watchlistedit-numitems'       => "A l'é antramentr ch'a ten sot ëuj {{PLURAL:$1|1 tìtol|$1 tìtoj}}, nen contand le pàgine ëd discussion.",
'watchlistedit-noitems'        => "A-i é pa gnun tìtol ch'as ten-a sot euj.",
'watchlistedit-normal-title'   => "Modifiché la lista ëd lòn ch'as ten sot euj",
'watchlistedit-normal-legend'  => "Gavé via ij tìtoj da 'nt la lista ëd lòn ch'as ten sot euj",
'watchlistedit-normal-explain' => "Ij tìtoj ch'a ten sot euj a son ësmonù ambelessì-sota. Për gavene via un ch'a-i fasa la crosëtta ant la casela ch'a l'ha aranda, e peuj ch'ai bata ansima a «{{int:Watchlistedit-normal-submit}}». As peul ëdcò [[Special:Watchlist/raw|modifiché la lista ampressa]].",
'watchlistedit-normal-submit'  => 'Gavé via ij tìtoj',
'watchlistedit-normal-done'    => "{{PLURAL:$1|1 tìtol a l'é|$1 tìtoj a son}} stait gavà via da 'nt la lista ëd lòn ch'as ten sot euj:",
'watchlistedit-raw-title'      => "Modifiché ampressa la lista ëd lòn ch'as ten sot euj",
'watchlistedit-raw-legend'     => "Modifiché ampressa la lista ëd lòn ch'as ten sot euj",
'watchlistedit-raw-explain'    => "Ij tìtoj ch'a l'é antramentr ch'as ten sot euj a son ambelessì-sota, e a peulo modifichesse ën giontand-ne e gavand-ne via da 'nt la lista; un tìtol për riga.
Quand a l'ha finì, ch'a-i bata ansima a \"{{int:Watchlistedit-raw-submit}}\".
As peul ëdcò [[Special:Watchlist/edit|dovré l'editor sòlit]].",
'watchlistedit-raw-titles'     => 'Tìtoj:',
'watchlistedit-raw-submit'     => 'Agiorné la Lista',
'watchlistedit-raw-done'       => "La lista ëd lòn ch'as ten sot euj a l'é staita agiornà.",
'watchlistedit-raw-added'      => "A {{PLURAL:$1|l'é|son}} giontasse {{PLURAL:$1|1 tìtol|$1 tìtoj}}:",
'watchlistedit-raw-removed'    => "A {{PLURAL:$1|l'é|son}} gavasse via {{PLURAL:$1|1 tìtol|$1 tìtoj}}:",

# Watchlist editing tools
'watchlisttools-view' => 'S-ciairé le modifiché amportante',
'watchlisttools-edit' => "Vardé e modifiché la lista ëd lòn ch'as ten sot euj",
'watchlisttools-raw'  => "Modifiché ampressa la lista ëd lòn ch'as ten sot euj",

# Iranian month names
'iranian-calendar-m1'  => 'Prim mèis Jalāli',
'iranian-calendar-m2'  => 'Scond mèis Jalāli',
'iranian-calendar-m3'  => 'Tèrs mèis Jalāli',
'iranian-calendar-m4'  => 'Quart mèis Jalāli',
'iranian-calendar-m5'  => 'Quint mèis Jalāli',
'iranian-calendar-m6'  => "Mèis Jalāli ch'a fa ses",
'iranian-calendar-m7'  => "Mèis Jalāli ch'a fa set",
'iranian-calendar-m8'  => "Mèis Jalāli ch'a fa eut",
'iranian-calendar-m9'  => "Mèis Jalāli ch'a fa neuv",
'iranian-calendar-m10' => "Mèis Jalāli ch'a fa des",
'iranian-calendar-m11' => "Mèis Jalāli ch'a fa óndes",
'iranian-calendar-m12' => "Meis Jalāli ch'a fa dódes",

# Core parser functions
'unknown_extension_tag' => 'Tacolèt d\'estension "$1" pa conossù',
'duplicate-defaultsort' => "'''Atension:''' La ciav d'ordinament ëd default \"\$2\" a ven al pòst ëd cola ëd prima \"\$1\"",

# Special:Version
'version'                          => 'Version',
'version-extensions'               => 'Estension anstalà',
'version-specialpages'             => 'Pàgine speciaj',
'version-parserhooks'              => 'Gancio dlë scompositor',
'version-variables'                => 'Variàbij',
'version-antispam'                 => 'Prevension dla rumenta',
'version-skins'                    => 'Pej',
'version-other'                    => 'Àutr',
'version-mediahandlers'            => 'Gestor multimojen',
'version-hooks'                    => 'Gancio',
'version-extension-functions'      => "Fonsion dj'estension",
'version-parser-extensiontags'     => "Tacolèt dj'estension conossùe da lë scompositor",
'version-parser-function-hooks'    => 'Gancio për le fonsion dlë scompositor',
'version-skin-extension-functions' => "Fonsion për j'estension dle facie",
'version-hook-name'                => 'Nòm dël gancio',
'version-hook-subscribedby'        => 'A son scrivusse',
'version-version'                  => '(Version $1)',
'version-license'                  => 'Licensa',
'version-poweredby-credits'        => "Sta wiki-sì a l'é basà su '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'àutri',
'version-license-info'             => "MediaWiki a l'é un programa lìber; a peul passelo an gir e/o modifichelo sota le condission dla Licensa Pùblica General GNU coma publicà da la Free Software Foundation; o la version 2 dla licensa o (a soa decision) qualsëssìa version apress.

MediaWiki a l'é distribuì ant la speransa che a sia ùtil, ma SENSA GNUN-A GARANSÌA; sensa gnanca la garansìa implìcita ëd COMERSIABILITA' o d'ADATAMENT A UN BUT PARTICOLAR. Ch'a lesa la Licensa General Pùblica GNU per pi 'd detaj.

A dovrìa avèj arseivù [{{SERVER}}{{SCRIPTPATH}}/COPYING na còpia dla Licensa Pùblica General GNU] ansema a sto programa-sì; dësnò, ch'a scriva a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html ch'a la lesa an linia].",
'version-software'                 => 'Programa anstalà',
'version-software-product'         => 'Prodot',
'version-software-version'         => 'Version',

# Special:FilePath
'filepath'         => "Përcors d'archivi",
'filepath-page'    => 'Archivi:',
'filepath-submit'  => 'Përcors',
'filepath-summary' => "Sta pàgina special-sì a la smon ël përcors complet për rive-ie a n'archivi.
Le figure as ësmon-o a amzura pijn-a, j'àotre sòrt d'archivi a ven-o faite parte da sò programa a pòsta.

Ch'a buta mach ël nòm dl'archivi sensa pa ël prefiss \"{{ns:file}}:\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'           => "Arsërca dj'archivi dobi",
'fileduplicatesearch-summary'   => "Arsërca dj'archivi dobi a parte dal valor d'ordinament.",
'fileduplicatesearch-legend'    => 'Arsërca ëd na dobia',
'fileduplicatesearch-filename'  => "Nòm dl'archivi:",
'fileduplicatesearch-submit'    => 'Arsërca',
'fileduplicatesearch-info'      => '$1 × $2 pixel<br />Amzure: $3<br />Sòrt MIME: $4',
'fileduplicatesearch-result-1'  => 'Pa gnun-a dobia për l\'archivi "$1".',
'fileduplicatesearch-result-n'  => 'A-i {{PLURAL:$2|é \'n dobion midem|son $2 dobion midem}} ëd l\'archivi "$1".',
'fileduplicatesearch-noresults' => 'Pa trovà gnun file ciamà "$1".',

# Special:SpecialPages
'specialpages'                   => 'Pàgine Speciaj',
'specialpages-note'              => '----
* Pàgine speciaj normaj.
* <span class="mw-specialpagerestricted">Pàgine speciaj riservà.</span>
* <span class="mw-specialpagecached">Pàgine speciaj mach an cache.</span>',
'specialpages-group-maintenance' => 'Rapòrt ëd manutension',
'specialpages-group-other'       => 'Àutre pàgine speciaj',
'specialpages-group-login'       => 'Login / registrassion',
'specialpages-group-changes'     => 'Ùltime modìfiche e registr',
'specialpages-group-media'       => 'Rapòrt dij file multimediaj e dle carie',
'specialpages-group-users'       => 'Utent e drit',
'specialpages-group-highuse'     => 'Pàgine motobin dovrà',
'specialpages-group-pages'       => 'Liste ëd pàgine',
'specialpages-group-pagetools'   => 'Utiss për le pàgine',
'specialpages-group-wiki'        => 'Dat e utiss ëd la wiki',
'specialpages-group-redirects'   => 'Pàgine speciaj ëd rediression',
'specialpages-group-spam'        => 'Utiss contra lë spam',

# Special:BlankPage
'blankpage'              => 'Pàgina bianca',
'intentionallyblankpage' => "Sta pàgina-sì a l'é lassà antensionalment an bianch.",

# External image whitelist
'external_image_whitelist' => "  #Lassa sta riga-sì pròpi con a l'é<pre>
#Buta ij tòch d'espression regolar (mach la part che a va an tra //) sota
#Ste sì a saran confrontà con le URL dle figure esterne (hotlinked)
#Cole che as cobio a saran visualisà com figure, dasnò a sarà mach mostà un colegament a la figura
#Le linie che a ancamin-o con # a saran tratà com coment
#Sòn sì a l'é pa sensìbil a minuscol o maiuscol

#Buta tùit ij tòch ëd regex sota sta linia-sì. Lassa sta linia-sì pròpi com a l'é</pre>",

# Special:Tags
'tags'                    => 'Tag ëd modìfiche vàlid',
'tag-filter'              => '[[Special:Tags|Tag]] filtror:',
'tag-filter-submit'       => 'Filtror',
'tags-title'              => 'Tag',
'tags-intro'              => 'Sta pàgina-sì a lista ij tag che ël software a peul dovré për identifiché na modìfica, e ël sò significà.',
'tags-tag'                => 'Nòm dël tag',
'tags-display-header'     => 'Aparensa ant la lista dle modìfiche',
'tags-description-header' => 'Descrission completa dël significà',
'tags-hitcount-header'    => 'Modìfiche con tag',
'tags-edit'               => 'modìfica',
'tags-hitcount'           => '$1 {{PLURAL:$1|cambiament|cambiament}}',

# Special:ComparePages
'comparepages'     => 'Confronté dle pàgine',
'compare-selector' => 'Confronté le revision dle pàgine',
'compare-page1'    => 'Pàgina 1',
'compare-page2'    => 'Pàgina 2',
'compare-rev1'     => 'Revision 1',
'compare-rev2'     => 'Revision 2',
'compare-submit'   => 'Confronta',

# Database error messages
'dberr-header'      => "Sta wiki-sì a l'ha un problema",
'dberr-problems'    => "Spiasent! Sto sit-sì a l'ha dle dificoltà técniche.",
'dberr-again'       => 'Preuva a speté cheich minute e a torna carié.',
'dberr-info'        => '(As peul pa contaté ël database server: $1)',
'dberr-usegoogle'   => 'It peule prové a serché con Google ant ël mentre.',
'dberr-outofdate'   => 'Nòta che la soa indicisassion dij nòst contnù a podrìa nen esse agiornà.',
'dberr-cachederror' => 'Sta sì a l\'ìé na còpia an "cache" ëd la pàgina ciamà, e a peul esse pa agiornà.',

# HTML forms
'htmlform-invalid-input'       => 'A-i son dij problema con cheidun dij tò input',
'htmlform-select-badoption'    => "Ël valor che it l'has spessificà a l'é n'opsion pa vàlida.",
'htmlform-int-invalid'         => "Ël valor ch'it l'has spessificà a l'é pa n'antregh.",
'htmlform-float-invalid'       => "Ël valor ch'it l'has spessificà a l'é pa un nùmer.",
'htmlform-int-toolow'          => "Ël valor ch'it l'has spessificà a l'é sota al mìnim ëd $1.",
'htmlform-int-toohigh'         => "Ël valor ch'it l'has spessificà a l'é dzora dël màssim ëd $1.",
'htmlform-required'            => 'A-i é dabzògn ëd cost valor',
'htmlform-submit'              => 'Spediss',
'htmlform-reset'               => 'Scansela ij cambiament',
'htmlform-selectorother-other' => 'Àutr',

# SQLite database support
'sqlite-has-fts' => '$1 con arserca an test pien mantnùa',
'sqlite-no-fts'  => '$1 sensa arserca an test pien mantnùa',

# Special:DisableAccount
'disableaccount'             => 'Disabìlita un cont utent',
'disableaccount-user'        => 'Stranòm:',
'disableaccount-reason'      => 'Rason:',
'disableaccount-confirm'     => "Disabilité sto cont utent.
L'utent a podrà pi intré ant ël sistema, amposté torna soa ciav, o arsèive notìfiche për pòsta eletrònica.
Se l'utent a l'é al moment intrà da chèich part, a sarà sùbit barà fòra.
''Ch'a nòta che disabiité un cont a l'é pa reversìbil senta l'antërvension ëd n'aministrator ëd sistema.''",
'disableaccount-mustconfirm' => "A dev confirmé ch'a veul disabilité ës cont.",
'disableaccount-nosuchuser'  => 'Ël cont utent "$1" a esist pa.',
'disableaccount-success'     => 'Ël cont utent "$1" a l\'é stàit disabilità përmanentement.',
'disableaccount-logentry'    => 'disabilità përmanentement ël cont utent [[$1]]',

);
