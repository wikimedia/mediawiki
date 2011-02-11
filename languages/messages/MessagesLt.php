<?php
/** Lithuanian (Lietuvių)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Auwris
 * @author Break Through Pain
 * @author Dark Eagle
 * @author Garas
 * @author Homo
 * @author Hugo.arg
 * @author Matasg
 * @author Meno25
 * @author Pdxx
 * @author Pėstininkas
 * @author Siggis
 * @author Tomasdd
 * @author Urhixidur
 * @author Vpovilaitis
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialus',
	NS_TALK             => 'Aptarimas',
	NS_USER             => 'Naudotojas',
	NS_USER_TALK        => 'Naudotojo_aptarimas',
	NS_PROJECT_TALK     => '$1_aptarimas',
	NS_FILE             => 'Vaizdas',
	NS_FILE_TALK        => 'Vaizdo_aptarimas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarimas',
	NS_TEMPLATE         => 'Šablonas',
	NS_TEMPLATE_TALK    => 'Šablono_aptarimas',
	NS_HELP             => 'Pagalba',
	NS_HELP_TALK        => 'Pagalbos_aptarimas',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Kategorijos_aptarimas',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dvigubi_peradesavimai' ),
	'BrokenRedirects'           => array( 'Peradresavimai_į_niekur' ),
	'Disambiguations'           => array( 'Nukreipiamieji' ),
	'Userlogin'                 => array( 'Prisijungimas' ),
	'Userlogout'                => array( 'Atsijungimas' ),
	'CreateAccount'             => array( 'Sukurti_paskyrą' ),
	'Preferences'               => array( 'Nustatymai' ),
	'Watchlist'                 => array( 'Stebimieji' ),
	'Recentchanges'             => array( 'Naujausi_keitimai' ),
	'Upload'                    => array( 'Įkėlimas' ),
	'Listfiles'                 => array( 'Paveikslėlių_sąrašas' ),
	'Newimages'                 => array( 'Nauji_paveikslėliai' ),
	'Listusers'                 => array( 'Naudotojų_sąrašas' ),
	'Listgrouprights'           => array( 'Grupių_teisių_sąrašas' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Atsitiktinis_puslapis' ),
	'Lonelypages'               => array( 'Vieniši_puslapiai' ),
	'Uncategorizedpages'        => array( 'Puslapiai_be_kategorijų' ),
	'Uncategorizedcategories'   => array( 'Kategorijos_be_subkategorijų' ),
	'Uncategorizedimages'       => array( 'Paveikslėliai_be_kategorijų' ),
	'Uncategorizedtemplates'    => array( 'Šablonai_be_kategorijų' ),
	'Unusedcategories'          => array( 'Nenaudojamos_kategorijos' ),
	'Unusedimages'              => array( 'Nenaudojami_paveikslėliai' ),
	'Wantedpages'               => array( 'Trokštamiausi_puslapiai', 'Blogos_nuorodos' ),
	'Wantedcategories'          => array( 'Trokštamiausios_kategorijos' ),
	'Wantedfiles'               => array( 'Trokštami_failai' ),
	'Wantedtemplates'           => array( 'Trokštami_šablonai' ),
	'Mostlinked'                => array( 'Turintys_daugiausiai_nuorodų' ),
	'Mostlinkedcategories'      => array( 'Kategorijos_turinčios_daugiausiai_nuorodų' ),
	'Mostlinkedtemplates'       => array( 'Šablonai' ),
	'Mostimages'                => array( 'Daugiausiai_naudojami_paveikslėliai' ),
	'Mostcategories'            => array( 'Daugiausiai_naudojamos_kategorijos' ),
	'Mostrevisions'             => array( 'Daugiausiai_keičiami' ),
	'Fewestrevisions'           => array( 'Mažiausiai_keičiami' ),
	'Shortpages'                => array( 'Trumpiausi_puslapiai' ),
	'Longpages'                 => array( 'Ilgiausi_puslapiai' ),
	'Newpages'                  => array( 'Naujausi_puslapiai' ),
	'Ancientpages'              => array( 'Seniausi_puslapiai' ),
	'Deadendpages'              => array( 'Puslapiai-aklavietės' ),
	'Protectedpages'            => array( 'Užrakinti_puslapiai' ),
	'Protectedtitles'           => array( 'Apsaugoti_pavadinimai' ),
	'Allpages'                  => array( 'Visi_puslapiai' ),
	'Prefixindex'               => array( 'Prasidedantys' ),
	'Ipblocklist'               => array( 'IP_blokavimų_sąrašas' ),
	'Specialpages'              => array( 'Specialieji_puslapiai' ),
	'Contributions'             => array( 'Indėlis' ),
	'Emailuser'                 => array( 'Rašyti_laišką' ),
	'Confirmemail'              => array( 'Elektroninio_pašto_patvirtinimas' ),
	'Whatlinkshere'             => array( 'Kas_į_čia_rodo' ),
	'Recentchangeslinked'       => array( 'Pakeitimai_susijusiuose_puslapiuose' ),
	'Movepage'                  => array( 'Puslapio_pervadinimas' ),
	'Blockme'                   => array( 'Užblokuoti_mane' ),
	'Booksources'               => array( 'Knygų_šaltiniai' ),
	'Categories'                => array( 'Kategorijos' ),
	'Export'                    => array( 'Eksportas' ),
	'Version'                   => array( 'Versija' ),
	'Allmessages'               => array( 'Visi_pranešimai' ),
	'Log'                       => array( 'Sąrašas', 'Sąrašai' ),
	'Blockip'                   => array( 'Blokuoti_IP' ),
	'Undelete'                  => array( 'Netrinti' ),
	'Import'                    => array( 'Importas' ),
	'Lockdb'                    => array( 'Užrakinti_duomenų_bazę' ),
	'Unlockdb'                  => array( 'Atrakinti_duomenų_bazę' ),
	'Userrights'                => array( 'Naudotojo_teisės' ),
	'MIMEsearch'                => array( 'MIME_paieška' ),
	'FileDuplicateSearch'       => array( 'Failo_dublikatų_paieška' ),
	'Unwatchedpages'            => array( 'Nestebimi_puslapiai' ),
	'Listredirects'             => array( 'Peradresavimų_sąrašas' ),
	'Revisiondelete'            => array( 'Redagavimo_ištrynimas' ),
	'Unusedtemplates'           => array( 'Nenaudojami_šablonai' ),
	'Randomredirect'            => array( 'Atsitiktinis_peradresavimas' ),
	'Mypage'                    => array( 'Mano_puslapis' ),
	'Mytalk'                    => array( 'Mano_aptarimas' ),
	'Mycontributions'           => array( 'Mano_indėlis' ),
	'Listadmins'                => array( 'Administratorių_sąrašas' ),
	'Listbots'                  => array( 'Botų_sąrašas' ),
	'Popularpages'              => array( 'Populiarūs_puslapiai' ),
	'Search'                    => array( 'Paieška' ),
	'Resetpass'                 => array( 'Slaptažodžio_atstatymas' ),
	'Withoutinterwiki'          => array( 'Be_interwiki' ),
	'MergeHistory'              => array( 'Sujungti_istoriją' ),
	'Filepath'                  => array( 'Kelias_iki_failo' ),
	'Invalidateemail'           => array( 'Nutraukti_elektroninio_pašto_galiojimą' ),
	'Blankpage'                 => array( 'Tuščias_puslapis' ),
	'LinkSearch'                => array( 'Nuorodų_paieška' ),
	'DeletedContributions'      => array( 'Ištrintas_indėlis' ),
	'Tags'                      => array( 'Žymos' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#PERADRESAVIMAS', '#REDIRECT' ),
	'notoc'                 => array( '0', '__BETURIN__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__BEGALERIJOS__', '__NOGALLERY__' ),
	'toc'                   => array( '0', '__TURINYS__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BEREDAGSEKC__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'DABARTINISMĖNESIS', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'DABARTINIOMĖNESIOPAVADINIMAS', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'DABARTINĖDIENA', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DABARTINĖDIENA2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'DABARTINĖSDIENOSPAVADINIMAS', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'DABARTINIAIMETAI', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'DABARTINISLAIKAS', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'DABARTINĖVALANDA', 'CURRENTHOUR' ),
	'numberofpages'         => array( '1', 'PUSLAPIŲSKAIČIUS', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'STRAIPSNIŲSKAIČIUS', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'FAILŲSKAIČIUS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NAUDOTOJŲSKAIČIUS', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'KEITIMŲSKAIČIUS', 'NUMBEROFEDITS' ),
	'img_thumbnail'         => array( '1', 'miniatiūra', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'miniatiūra=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'dešinėje', 'right' ),
	'img_left'              => array( '1', 'kairėje', 'left' ),
);

$fallback8bitEncoding = 'windows-1257';
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$dateFormats = array(
	'ymd time' => 'H:i',
	'ymd date' => 'Y "m." F j "d."',
	'ymd both' => 'Y "m." F j "d.", H:i',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Pabraukti nuorodas:',
'tog-highlightbroken'         => 'Formuoti nesančių puslapių nuorodas <a href="#" class="new">šitaip</a> (priešingai - šitaip <a href="#" class="internal">?</a>).',
'tog-justify'                 => 'Lygiuoti pastraipas pagal abi puses',
'tog-hideminor'               => 'Slėpti smulkius pakeitimus naujausių keitimų sąraše',
'tog-hidepatrolled'           => 'Slėpti patikrintus keitimus paskutinių keitimų sąraše',
'tog-newpageshidepatrolled'   => 'Slėpti patikrintus puslapius iš naujausių straipsnių sąrašo',
'tog-extendwatchlist'         => 'Išplėsti stebimųjų sąrašą, kad rodytų visus tinkamus keitimus, ne tik pačius naujausius.',
'tog-usenewrc'                => 'Naudoti patobulintąjį paskutinių keitimų sąrašą (reikia JavaScript)',
'tog-numberheadings'          => 'Automatiškai numeruoti skyrelius',
'tog-showtoolbar'             => 'Rodyti redagavimo įrankinę (JavaScript)',
'tog-editondblclick'          => 'Puslapių redagavimas dvigubu spustelėjimu (JavaScript)',
'tog-editsection'             => 'Įjungti skyrelių redagavimą naudojant nuorodas [taisyti]',
'tog-editsectiononrightclick' => 'Įjungti skyrelių redagavimą paspaudus skyrelio pavadinimą dešiniuoju pelės klavišu (JavaScript)',
'tog-showtoc'                 => 'Rodyti turinį, jei puslapyje daugiau nei 3 skyreliai',
'tog-rememberpassword'        => 'Prisiminti prisijungimo informaciją šioje naršyklėje (daugiausiai $1 {{PLURAL:$1|dieną|dienas|dienų}})',
'tog-watchcreations'          => 'Pridėti puslapius, kuriuos sukuriu, į stebimų sąrašą',
'tog-watchdefault'            => 'Pridėti puslapius, kuriuos redaguoju, į stebimų sąrašą',
'tog-watchmoves'              => 'Pridėti puslapius, kuriuos perkeliu, į stebimų sąrašą',
'tog-watchdeletion'           => 'Pridėti puslapius, kuriuos ištrinu, į stebimų sąrašą',
'tog-minordefault'            => 'Pagal nutylėjimą pažymėti redagavimus kaip smulkius',
'tog-previewontop'            => 'Rodyti peržiūrą virš redagavimo lauko',
'tog-previewonfirst'          => 'Rodyti peržiūrą pirmą kartą pakeitus',
'tog-nocache'                 => 'Išjungti interneto naršyklės puslapių podėlį',
'tog-enotifwatchlistpages'    => 'Siųsti man laišką, kai pakeičiamas puslapis, kurį stebiu',
'tog-enotifusertalkpages'     => 'Siųsti man laišką, kai pakeičiamas mano naudotojo aptarimo puslapis',
'tog-enotifminoredits'        => 'Siųsti man laišką, kai puslapio keitimas yra smulkus',
'tog-enotifrevealaddr'        => 'Rodyti mano el. pašto adresą priminimo laiškuose',
'tog-shownumberswatching'     => 'Rodyti stebinčių naudotojų skaičių',
'tog-oldsig'                  => 'Esamo parašo peržiūra:',
'tog-fancysig'                => 'Laikyti parašą vikitekstu (be automatinių nuorodų)',
'tog-externaleditor'          => 'Pagal nutylėjimą naudoti išorinį redaktorių',
'tog-externaldiff'            => 'Pagal nutylėjimą naudoti išorinę skirtumų rodymo programą',
'tog-showjumplinks'           => 'Įjungti „peršokti į“ pasiekiamumo nuorodas',
'tog-uselivepreview'          => 'Naudoti tiesioginę peržiūrą (JavaScript) (Eksperimentinis)',
'tog-forceeditsummary'        => 'Klausti, kai palieku tuščią keitimo komentarą',
'tog-watchlisthideown'        => 'Slėpti mano keitimus stebimų sąraše',
'tog-watchlisthidebots'       => 'Slėpti robotų keitimus stebimų sąraše',
'tog-watchlisthideminor'      => 'Slėpti smulkius keitimus stebimų sąraše',
'tog-watchlisthideliu'        => 'Slėpti prisijungusių naudotojų keitimus stebimųjų sąraše',
'tog-watchlisthideanons'      => 'Slėpti anoniminių naudotojų keitimus stebimųjų sąraše',
'tog-watchlisthidepatrolled'  => 'Slėpti patikrintus keitimus stebimųjų sąraše',
'tog-nolangconversion'        => 'Išjungti variantų keitimą',
'tog-ccmeonemails'            => 'Siųsti man laiškų, kuriuos siunčiu kitiems naudotojams, kopijas',
'tog-diffonly'                => 'Nerodyti puslapio turinio po skirtumais',
'tog-showhiddencats'          => 'Rodyti paslėptas kategorijas',
'tog-norollbackdiff'          => 'Nepaisyti skirtumo atlikus atmetimą',

'underline-always'  => 'Visada',
'underline-never'   => 'Niekada',
'underline-default' => 'Pagal naršyklės nustatymus',

# Font style option in Special:Preferences
'editfont-style'     => 'Redagavimo srities šrifto stilius:',
'editfont-default'   => 'Naršyklės numatytasis',
'editfont-monospace' => 'Lygiaplotis šriftas',
'editfont-sansserif' => 'Šriftas be užraitų',
'editfont-serif'     => 'Šriftas su užraitais',

# Dates
'sunday'        => 'sekmadienis',
'monday'        => 'pirmadienis',
'tuesday'       => 'antradienis',
'wednesday'     => 'trečiadienis',
'thursday'      => 'ketvirtadienis',
'friday'        => 'penktadienis',
'saturday'      => 'šeštadienis',
'sun'           => 'Sek',
'mon'           => 'Pir',
'tue'           => 'Ant',
'wed'           => 'Tre',
'thu'           => 'Ket',
'fri'           => 'Pen',
'sat'           => 'Šeš',
'january'       => 'sausio',
'february'      => 'vasario',
'march'         => 'kovo',
'april'         => 'balandžio',
'may_long'      => 'gegužės',
'june'          => 'birželio',
'july'          => 'liepos',
'august'        => 'rugpjūčio',
'september'     => 'rugsėjo',
'october'       => 'spalio',
'november'      => 'lapkričio',
'december'      => 'gruodžio',
'january-gen'   => 'Sausis',
'february-gen'  => 'Vasaris',
'march-gen'     => 'Kovas',
'april-gen'     => 'Balandis',
'may-gen'       => 'Gegužė',
'june-gen'      => 'Birželis',
'july-gen'      => 'Liepa',
'august-gen'    => 'Rugpjūtis',
'september-gen' => 'Rugsėjis',
'october-gen'   => 'Spalis',
'november-gen'  => 'Lapkritis',
'december-gen'  => 'Gruodis',
'jan'           => 'sau',
'feb'           => 'vas',
'mar'           => 'kov',
'apr'           => 'bal',
'may'           => 'geg',
'jun'           => 'bir',
'jul'           => 'lie',
'aug'           => 'rgp',
'sep'           => 'rgs',
'oct'           => 'spa',
'nov'           => 'lap',
'dec'           => 'grd',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategorijos}}',
'category_header'                => 'Puslapiai kategorijoje „$1“',
'subcategories'                  => 'Subkategorijos',
'category-media-header'          => 'Daugialypės terpės failai kategorijoje „$1“',
'category-empty'                 => "''Šiuo metu ši kategorija neturi jokių puslapių ar failų.''",
'hidden-categories'              => '{{PLURAL:$1|Paslėpta kategorija|Paslėptos kategorijos}}',
'hidden-category-category'       => 'Paslėptos kategorijos',
'category-subcat-count'          => '{{PLURAL:$2|Šioje kategorijoje yra viena subkategorija.|{{PLURAL:$1|Rodoma|Rodomos|Rodoma}} $1 {{PLURAL:$1|subkategorija|subkategorijos|subkategorijų}} (iš viso yra $2 {{PLURAL:$2|subkategorija|subkategorijos|subkategorijų}}).}}',
'category-subcat-count-limited'  => 'Šioje kategorijoje yra $1 {{PLURAL:$1|subkategorija|subkategorijos|subkategorijų}}.',
'category-article-count'         => '{{PLURAL:$2|Šioje kategorijoje yra vienas puslapis.|{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|puslapis|puslapiai|puslapių}} (iš viso kategorijoje yra $2 {{PLURAL:$2|puslapis|puslapiai|puslapių}}).}}',
'category-article-count-limited' => '{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|puslapis|puslapiai|puslapių}}.',
'category-file-count'            => '{{PLURAL:$2|Šioje kategorijoje yra vienas failas.|{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|failas|failai|failų}} (iš viso kategorijoje yra $2 {{PLURAL:$2|failas|failai|failų}}).}}',
'category-file-count-limited'    => '{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|failas|failai|failų}}.',
'listingcontinuesabbrev'         => 'tęs.',
'index-category'                 => 'Indeksuoti puslapiai',
'noindex-category'               => 'Neindeksuoti puslapiai',

'mainpagetext'      => "'''MediaWiki sėkmingai įdiegta.'''",
'mainpagedocfooter' => 'Informacijos apie wiki programinės įrangos naudojimą, ieškokite [http://meta.wikimedia.org/wiki/Help:Contents žinyne].

== Pradžiai ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfigūracijos nustatymų sąrašas]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki DUK]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki pranešimai paštu apie naujas versijas]',

'about'         => 'Apie',
'article'       => 'Turinys',
'newwindow'     => '(atsidaro naujame lange)',
'cancel'        => 'Atšaukti',
'moredotdotdot' => 'Daugiau...',
'mypage'        => 'Mano puslapis',
'mytalk'        => 'Mano aptarimas',
'anontalk'      => 'Šio IP aptarimas',
'navigation'    => 'Naršymas',
'and'           => '&#32;ir',

# Cologne Blue skin
'qbfind'         => 'Paieška',
'qbbrowse'       => 'Naršymas',
'qbedit'         => 'Taisyti',
'qbpageoptions'  => 'Šis puslapis',
'qbpageinfo'     => 'Kontekstas',
'qbmyoptions'    => 'Mano puslapiai',
'qbspecialpages' => 'Specialieji puslapiai',
'faq'            => 'DUK',
'faqpage'        => 'Project:DUK',

# Vector skin
'vector-action-addsection'       => 'Pridėti temą',
'vector-action-delete'           => 'Ištrinti',
'vector-action-move'             => 'Perkelti',
'vector-action-protect'          => 'Užrakinti',
'vector-action-undelete'         => 'Atkurti',
'vector-action-unprotect'        => 'Atrakinti',
'vector-simplesearch-preference' => 'Įjungti išplėstinius paieškos pasiūlymus (tik „Vector“ išvaizda)',
'vector-view-create'             => 'Kurti',
'vector-view-edit'               => 'Redaguoti',
'vector-view-history'            => 'Istorija',
'vector-view-view'               => 'Skaityti',
'vector-view-viewsource'         => 'Žiūrėti kodą',
'actions'                        => 'Veiksmai',
'namespaces'                     => 'Vardų sritys',
'variants'                       => 'Variantai',

'errorpagetitle'    => 'Klaida',
'returnto'          => 'Grįžti į $1.',
'tagline'           => 'Iš {{SITENAME}}.',
'help'              => 'Pagalba',
'search'            => 'Paieška',
'searchbutton'      => 'Paieška',
'go'                => 'Rodyti',
'searcharticle'     => 'Rodyti',
'history'           => 'Puslapio istorija',
'history_short'     => 'Istorija',
'updatedmarker'     => 'atnaujinta nuo paskutinio mano apsilankymo',
'info_short'        => 'Informacija',
'printableversion'  => 'Versija spausdinimui',
'permalink'         => 'Nuolatinė nuoroda',
'print'             => 'Spausdinti',
'edit'              => 'Redaguoti',
'create'            => 'Sukurti',
'editthispage'      => 'Redaguoti šį puslapį',
'create-this-page'  => 'Sukurti šį puslapį',
'delete'            => 'Trinti',
'deletethispage'    => 'Ištrinti šį puslapį',
'undelete_short'    => 'Atstatyti $1 {{PLURAL:$1:redagavimą|redagavimus|redagavimų}}',
'protect'           => 'Užrakinti',
'protect_change'    => 'keisti',
'protectthispage'   => 'Rakinti šį puslapį',
'unprotect'         => 'Atrakinti',
'unprotectthispage' => 'Atrakinti šį puslapį',
'newpage'           => 'Naujas puslapis',
'talkpage'          => 'Aptarti šį puslapį',
'talkpagelinktext'  => 'Aptarimas',
'specialpage'       => 'Specialusis puslapis',
'personaltools'     => 'Asmeniniai įrankiai',
'postcomment'       => 'Rašyti komentarą',
'articlepage'       => 'Rodyti turinio puslapį',
'talk'              => 'Aptarimas',
'views'             => 'Žiūrėti',
'toolbox'           => 'Įrankiai',
'userpage'          => 'Rodyti naudotojo puslapį',
'projectpage'       => 'Rodyti projekto puslapį',
'imagepage'         => 'Žiūrėti failo puslapį',
'mediawikipage'     => 'Rodyti pranešimo puslapį',
'templatepage'      => 'Rodyti šablono puslapį',
'viewhelppage'      => 'Rodyti pagalbos puslapį',
'categorypage'      => 'Rodyti kategorijos puslapį',
'viewtalkpage'      => 'Rodyti aptarimo puslapį',
'otherlanguages'    => 'Kitomis kalbomis',
'redirectedfrom'    => '(Nukreipta iš $1)',
'redirectpagesub'   => 'Nukreipimo puslapis',
'lastmodifiedat'    => 'Šis puslapis paskutinį kartą keistas $1 $2.',
'viewcount'         => 'Šis puslapis buvo atvertas $1 {{PLURAL:$1|kartą|kartus|kartų}}.',
'protectedpage'     => 'Užrakintas puslapis',
'jumpto'            => 'Peršokti į:',
'jumptonavigation'  => 'navigaciją',
'jumptosearch'      => 'paiešką',
'view-pool-error'   => 'Atsiprašome, šiuo metu serveriai yra perkrauti.
Pernelyg daug naudotojų skaito šį puslapį.
Prašome palaukti ir bandyti į šį puslapį patekti dar kartą.

$1',
'pool-timeout'      => 'Baigėsi laikas laukiant užrakto',
'pool-queuefull'    => 'Telkinio eilė pilna',
'pool-errorunknown' => 'Nežinoma klaida',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Apie {{SITENAME}}',
'aboutpage'            => 'Project:Apie',
'copyright'            => 'Turinys pateikiamas pagal $1 licenciją.',
'copyrightpage'        => '{{ns:project}}:Autorystės teisės',
'currentevents'        => 'Naujienos',
'currentevents-url'    => 'Project:Naujienos',
'disclaimers'          => 'Atsakomybės apribojimas',
'disclaimerpage'       => 'Project:Atsakomybės apribojimas',
'edithelp'             => 'Kaip redaguoti',
'edithelppage'         => 'Help:Redagavimas',
'helppage'             => 'Help:Turinys',
'mainpage'             => 'Pagrindinis puslapis',
'mainpage-description' => 'Pagrindinis puslapis',
'policy-url'           => 'Project:Politika',
'portal'               => 'Bendruomenė',
'portal-url'           => 'Project:Bendruomenė',
'privacy'              => 'Privatumo politika',
'privacypage'          => 'Project:Privatumo politika',

'badaccess'        => 'Teisių klaida',
'badaccess-group0' => 'Jums neleidžiama įvykdyti veiksmo, kurio prašėte.',
'badaccess-groups' => 'Veiksmas, kurio prašėte, galimas tik naudotojams, esantiems {{PLURAL:$2|šioje grupėje|vienoje iš šių grupių}} $1.',

'versionrequired'     => 'Reikalinga $1 MediaWiki versija',
'versionrequiredtext' => 'Reikalinga $1 MediaWiki versija, kad pamatytumėte šį puslapį. Žiūrėkite [[Special:Version|versijos puslapį]].',

'ok'                      => 'Gerai',
'retrievedfrom'           => 'Gauta iš „$1“',
'youhavenewmessages'      => 'Jūs turite $1 ($2).',
'newmessageslink'         => 'naujų žinučių',
'newmessagesdifflink'     => 'paskutinis pakeitimas',
'youhavenewmessagesmulti' => 'Turite naujų žinučių $1',
'editsection'             => 'redaguoti',
'editold'                 => 'taisyti',
'viewsourceold'           => 'žiūrėti šaltinį',
'editlink'                => 'keisti',
'viewsourcelink'          => 'žiūrėti kodą',
'editsectionhint'         => 'Redaguoti skyrelį: $1',
'toc'                     => 'Turinys',
'showtoc'                 => 'rodyti',
'hidetoc'                 => 'slėpti',
'thisisdeleted'           => 'Žiūrėti ar atkurti $1?',
'viewdeleted'             => 'Rodyti $1?',
'restorelink'             => '$1 {{PLURAL:$1|ištrintą keitimą|ištrintus keitimus|ištrintų keitimų}}',
'feedlinks'               => 'Šaltinis:',
'feed-invalid'            => 'Neleistinas šaltinio tipas.',
'feed-unavailable'        => 'Keitimų prenumeratos negalimos',
'site-rss-feed'           => '$1 RSS prenumerata',
'site-atom-feed'          => '$1 Atom prenumerata',
'page-rss-feed'           => '„$1“ RSS prenumerata',
'page-atom-feed'          => '„$1“ Atom prenumerata',
'red-link-title'          => '$1 (puslapis neegzistuoja)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Puslapis',
'nstab-user'      => 'Naudotojo puslapis',
'nstab-media'     => 'Media puslapis',
'nstab-special'   => 'Specialusis puslapis',
'nstab-project'   => 'Projekto puslapis',
'nstab-image'     => 'Failas',
'nstab-mediawiki' => 'Pranešimas',
'nstab-template'  => 'Šablonas',
'nstab-help'      => 'Pagalbos puslapis',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nėra tokio veiksmo',
'nosuchactiontext'  => 'Veiksmas, nurodytas adrese, neatpažintas.
Galbūt Jūs padarėte klaidą adrese ar paspaudėte ant neteisingos nuorodos.
Šios problemos priežastis taip pat gali būti klaida programinėje įrangoje, kurią naudoja {{SITENAME}}.',
'nosuchspecialpage' => 'Nėra tokio specialiojo puslapio',
'nospecialpagetext' => '<strong>Toks specialusis puslapis neegzistuoja</strong>

Egzistuojančių specialiųjų puslapių sąrašą galite rasti [[Special:SpecialPages|specialiųjų puslapių sąraše]].',

# General errors
'error'                => 'Klaida',
'databaseerror'        => 'Duomenų bazės klaida',
'dberrortext'          => 'Įvyko duomenų bazės užklausos sintaksės klaida.
Tai gali reikšti klaidą programinėje įrangoje.
Paskutinė mėginta duomenų bazės užklausa buvo:
<blockquote><tt>$1</tt></blockquote>
iš funkcijos: „<tt>$2</tt>“.
Duomenų bazė grąžino klaidą „<tt>$3: $4</tt>“.',
'dberrortextcl'        => 'Įvyko duomenų bazės užklausos sintaksės klaida.
Paskutinė mėginta duomenų bazės užklausa buvo:
„$1“
iš funkcijos: „$2“.
Duomenų bazė grąžino klaidą „$3: $4“',
'laggedslavemode'      => 'Dėmesio: Puslapyje gali nesimatyti naujausių pakeitimų.',
'readonly'             => 'Duomenų bazė užrakinta',
'enterlockreason'      => 'Įveskite užrakinimo priežastį, taip pat datą, kada bus atrakinta',
'readonlytext'         => 'Duomenų bazė šiuo metu yra užrakinta naujiems įrašams ar kitiems keitimams,
turbūt duomenų bazės techninei profilaktikai,
po to viskas vėl veiks kaip įprasta.

Užrakinusiojo administratoriaus pateiktas rakinimo paaiškinimas: $1',
'missing-article'      => 'Duomenų bazė nerado puslapio teksto, kurį ji turėtų rasti, pavadinto „$1“ $2.

Paprastai tai būna dėl pasenusios skirtumo ar istorijos nuorodos į puslapį, kuris buvo ištrintas.

Jei tai ne tas atvejis, jūs galbūt radote klaidą programinėje įrangoje.
Prašome apie tai pranešti [[Special:ListUsers/sysop|administratoriui]], nepamiršdami nurodyti nuorodą.',
'missingarticle-rev'   => '(versija#: $1)',
'missingarticle-diff'  => '(Skirt.: $1, $2)',
'readonly_lag'         => 'Duomenų bazė buvo automatiškai užrakinta, kol pagalbinės duomenų bazės prisivys pagrindinę',
'internalerror'        => 'Vidinė klaida',
'internalerror_info'   => 'Vidinė klaida: $1',
'fileappenderrorread'  => 'Papildymo metu nepavyko perskaityti „$1“.',
'fileappenderror'      => 'Nepavyko pridėti „$1“ prie „$2“.',
'filecopyerror'        => 'Nepavyksta kopijuoti failo iš „$1“ į „$2“.',
'filerenameerror'      => 'Nepavyksta pervardinti failo iš „$1“ į „$2“.',
'filedeleteerror'      => 'Nepavyksta ištrinti failo „$1“.',
'directorycreateerror' => 'Nepavyko sukurti aplanko „$1“.',
'filenotfound'         => 'Nepavyksta rasti failo „$1“.',
'fileexistserror'      => 'Nepavyksta įrašyti į failą „$1“: failas jau yra',
'unexpected'           => 'Netikėta reikšmė: „$1“=„$2“.',
'formerror'            => 'Klaida: nepavyko apdoroti formos duomenų',
'badarticleerror'      => 'Veiksmas negalimas šiam puslapiui.',
'cannotdelete'         => 'Nepavyko ištrinti puslapio ar failo „$1“.
Galbūt jį jau kažkas kitas ištrynė.',
'badtitle'             => 'Blogas pavadinimas',
'badtitletext'         => 'Nurodytas puslapio pavadinimas buvo neleistinas, tuščias arba neteisingai sujungtas tarpkalbinis arba tarpprojektinis pavadinimas. Jame gali būti vienas ar daugiau simbolių, neleistinų pavadinimuose',
'perfcached'           => 'Rodoma išsaugota duomenų kopija, todėl duomenys gali būti ne patys naujausi.',
'perfcachedts'         => 'Rodoma išsaugota duomenų kopija, kuri buvo atnaujinta $1.',
'querypage-no-updates' => 'Atnaujinimai šiam puslapiui dabar yra išjungti. Duomenys čia dabar nebus atnaujinti.',
'wrong_wfQuery_params' => 'Neteisingi parametrai į funkciją wfQuery()<br />
Funkcija: $1<br />
Užklausa: $2',
'viewsource'           => 'Žiūrėti kodą',
'viewsourcefor'        => 'puslapiui $1',
'actionthrottled'      => 'Veiksmas apribotas',
'actionthrottledtext'  => 'Kad būtų apsisaugota nuo reklamų, jums neleidžiama daug kartų atlikti šį veiksmą per trumpą laiko tarpą, bet jūs pasiekėte šį limitą. Prašome vėl pamėginti po kelių minučių.',
'protectedpagetext'    => 'Šis puslapis yra užrakintas, saugant jį nuo redagavimo.',
'viewsourcetext'       => 'Jūs galite žiūrėti ir kopijuoti puslapio kodą:',
'protectedinterface'   => 'Šiame puslapyje yra apsaugotas nuo piktnaudžiavimo programinės įrangos sąsajos tekstas.',
'editinginterface'     => "'''Dėmesio:''' Jūs redaguojate puslapį, kuris yra naudojamas programinės įrangos sąsajos tekste. Pakeitimai šiame puslapyje taip pat pakeis naudotojo sąsajos išvaizdą ir kitiems naudojams. Jei norite išversti, siūlome pasinaudoti [http://translatewiki.net/wiki/Main_Page?setlang=lt „translatewiki.net“], „MediaWiki“ lokalizacijos projektu.",
'sqlhidden'            => '(SQL užklausa paslėpta)',
'cascadeprotected'     => 'Šis puslapis buvo apsaugotas nuo redagavimo, kadangi jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi:
$2',
'namespaceprotected'   => "Jūs neturite teisės redaguoti puslapių '''$1''' srityje.",
'customcssjsprotected' => 'Jūs neturite teisės redaguoti šio puslapio, nes jame yra kito nautotojo asmeninių nustatymų.',
'ns-specialprotected'  => 'Specialieji puslapiai negali būti redaguojami.',
'titleprotected'       => "[[User:$1|$1]] apsaugojo šį pavadinimą nuo sukūrimo.
Nurodyta priežastis yra ''$2''.",

# Virus scanner
'virus-badscanner'     => "Neleistina konfigūracija: nežinomas virusų skeneris: ''$1''",
'virus-scanfailed'     => 'skanavimas nepavyko (kodas $1)',
'virus-unknownscanner' => 'nežinomas antivirusas:',

# Login and logout pages
'logouttext'                 => "'''Dabar jūs esate atsijungęs.'''

Galite toliau naudoti {{SITENAME}} anonimiškai arba [[Special:UserLogin|prisijunkite]] iš naujo tuo pačiu ar kitu naudotoju.
Pastaba: kai kuriuose puslapiuose ir toliau gali rodyti, kad esate prisijungęs iki tol, kol išvalysite savo naršyklės podėlį.",
'welcomecreation'            => '== Sveiki, $1! ==

Jūsų paskyra buvo sukurta. Nepamirškite pakeisti savo [[Special:Preferences|{{SITENAME}} nustatymų]].',
'yourname'                   => 'Naudotojo vardas:',
'yourpassword'               => 'Slaptažodis:',
'yourpasswordagain'          => 'Pakartokite slaptažodį:',
'remembermypassword'         => 'Prisiminti prisijungimo duomenis šiame kompiuteryje (daugiausiai $1 {{PLURAL:$1|dieną|dienas|dienų}})',
'securelogin-stick-https'    => 'Likite prisijungę prie HTTPS po prisijungimo',
'yourdomainname'             => 'Jūsų domenas:',
'externaldberror'            => 'Yra arba išorinė autorizacijos duomenų bazės klaida arba jums neleidžiama atnaujinti jūsų išorinės paskyros.',
'login'                      => 'Prisijungti',
'nav-login-createaccount'    => 'Prisijungti / sukurti paskyrą',
'loginprompt'                => 'Įjunkite slapukus, jei norite prisijungti prie {{SITENAME}}.',
'userlogin'                  => 'Prisijungti / sukurti paskyrą',
'userloginnocreate'          => 'Prisijungti',
'logout'                     => 'Atsijungti',
'userlogout'                 => 'Atsijungti',
'notloggedin'                => 'Neprisijungęs',
'nologin'                    => "Neturite prisijungimo vardo? '''$1'''.",
'nologinlink'                => 'Sukurkite paskyrą',
'createaccount'              => 'Sukurti paskyrą',
'gotaccount'                 => "Jau turite paskyrą? '''$1'''.",
'gotaccountlink'             => 'Prisijunkite',
'createaccountmail'          => 'el. paštu',
'createaccountreason'        => 'Priežastis:',
'badretype'                  => 'Įvesti slaptažodžiai nesutampa.',
'userexists'                 => 'Įvestasis naudotojo vardas jau naudojamas.
Prašome pasirinkti kitą vardą.',
'loginerror'                 => 'Prisijungimo klaida',
'createaccounterror'         => 'Nepavyko sukurti paskyros: $1',
'nocookiesnew'               => 'Naudotojo paskyra buvo sukurta, bet jūs nesate prisijungęs. {{SITENAME}} naudoja slapukus, kad prijungtų naudotojus. Jūs esate išjungę slapukus. Prašome įjungti juos, tada prisijunkite su savo naujuoju naudotojo vardu ir slaptažodžiu.',
'nocookieslogin'             => '{{SITENAME}} naudoja slapukus, kad prijungtų naudotojus. Jūs esate išjungę slapukus. Prašome įjungti juos ir pamėginkite vėl.',
'noname'                     => 'Jūs nesate nurodęs teisingo naudotojo vardo.',
'loginsuccesstitle'          => 'Sėkmingai prisijungėte',
'loginsuccess'               => "'''Dabar jūs prisijungęs prie {{SITENAME}} kaip „$1“.'''",
'nosuchuser'                 => 'Nėra jokio naudotojo, turinčio vardą „$1“.
Naudotojų varduose skiriamos didžiosios ir mažosios raidės.
Patikrinkite rašybą, arba [[Special:UserLogin/signup|sukurkite naują paskyrą]].',
'nosuchusershort'            => 'Nėra jokio naudotojo, pavadinto „<nowiki>$1</nowiki>“. Patikrinkite rašybą.',
'nouserspecified'            => 'Jums reikia nurodyti naudotojo vardą.',
'login-userblocked'          => 'Šis naudotojas yra užblokuotas. Prisijungti neleidžiama.',
'wrongpassword'              => 'Įvestas neteisingas slaptažodis. Pamėginkite dar kartą.',
'wrongpasswordempty'         => 'Įvestas slaptažodis yra tuščias. Pamėginkite vėl.',
'passwordtooshort'           => 'Slaptažodžiai turi būti bent $1 {{PLURAL:$1|simbolio|simbolių|simbolių}} ilgio.',
'password-name-match'        => 'Jūsų slaptažodis turi skirtis nuo jūsų naudotojo vardo.',
'mailmypassword'             => 'Atsiųsti naują slaptažodį el. paštu',
'passwordremindertitle'      => 'Laikinasis {{SITENAME}} slaptažodis',
'passwordremindertext'       => 'Kažkas (tikriausiai jūs, IP adresu $1)
paprašė, kad atsiųstumėte naują slaptažodį projektui {{SITENAME}} ($4).
Laikinasis naudotojo „$2“ slaptažodis buvo sukurtas ir nustatytas į „$3“.
Jei tai buvo jūs, jūs turėtumėte prisijungti ir pasirinkti naują slaptažodį.
Jūsų laikinasis slaptažodis baigs galioti po {{PLURAL:$5|$5 dienos|$5 dienų|$5 dienų}}.

Jei kažkas kitas atliko šį prašymą arba jūs prisiminėte savo slaptažodį ir
nebenorite jo pakeisti, galite tiesiog nekreipti dėmesio į šį laišką ir toliau
naudotis savo senuoju slaptažodžiu.',
'noemail'                    => 'Nėra jokio el. pašto adreso įvesto naudotojui „$1“.',
'noemailcreate'              => 'Jūs turite nurodyti veikiantį el. pašto adresą',
'passwordsent'               => 'Naujas slaptažodis buvo nusiųstas į el. pašto adresą,
užregistruotą naudotojo „$1“.
Prašome prisijungti vėl, kai jūs jį gausite.',
'blocked-mailpassword'       => 'Jūsų IP adresas yra užblokuotas nuo redagavimo, taigi neleidžiama naudoti slaptažodžio priminimo funkcijos, kad apsisaugotume nuo piktnaudžiavimo.',
'eauthentsent'               => 'Patvirtinimo laiškas buvo nusiųstas į paskirtąjį el. pašto adresą.
Prieš išsiunčiant kitą laišką į jūsų dėžutę, jūs turite vykdyti nurodymus laiške, kad patvirtintumėte, kad dėžutė tikrai yra jūsų.',
'throttled-mailpassword'     => 'Slaptažodžio priminimas jau buvo išsiųstas, per {{PLURAL:$1|$1 paskutinę valandą|$1 paskutines valandas|$1 paskutinių valandų}}.
Norint apsisaugoti nuo piktnaudžiavimo, slaptažodžio priminimas gali būti išsiųstas tik kas {{PLURAL:$1|$1 valandą|$1 valandas|$1 valandų}}.',
'mailerror'                  => 'Klaida siunčiant paštą: $1',
'acct_creation_throttle_hit' => 'Šio projekto lankytojai, naudojantys jūsų IP adresą, sukūrė {{PLURAL:$1|$1 paskyrą|$1 paskyras|$1 paskyrų}} per paskutiniąją dieną, o tai yra didžiausias leidžiamas kiekis per šį laiko tarpą.
Todėl šiuo metu lankytojai, naudojantys šį IP adresą, daugiau negali kurti paskyrų.',
'emailauthenticated'         => 'Jūsų el. pašto adresas buvo patvirtintas $2 d. $3.',
'emailnotauthenticated'      => 'Jūsų el. pašto adresas dar nėra patvirtintas. Jokie laiškai
nebus siunčiami nei vienai žemiau išvardintai paslaugai.',
'noemailprefs'               => 'Nurodykite el. pašto adresą, kad šios funkcijos veiktų.',
'emailconfirmlink'           => 'Patvirtinkite savo el. pašto adresą',
'invalidemailaddress'        => 'El. pašto adresas negali būti priimtas, nes atrodo, kad jis nėra teisingo formato.
Prašome įvesti gerai suformuotą adresą arba palikite tą laukelį tuščią.',
'accountcreated'             => 'Paskyra sukurta',
'accountcreatedtext'         => 'Naudotojo paskyra $1 buvo sukurta.',
'createaccount-title'        => '{{SITENAME}} paskyros kūrimas',
'createaccount-text'         => 'Projekte {{SITENAME}} ($4) kažkas sukūrė paskyrą „$2“ su slaptažodžiu „$3“ panaudodamas jūsų el. pašto adresą.
Jūs turėtumėte prisijungti ir pasikeisti savo slaptažodį.

Jūs galite nekreipti dėmesio į laišką, jei ši paskyra buvo sukurta per klaidą.',
'usernamehasherror'          => 'Naudotojo vardas negali turėti grotelių simbolio',
'login-throttled'            => 'Jūs pernelyg daug kartų bandėte prisijungti.
Palaukite prieš bandant vėl.',
'loginlanguagelabel'         => 'Kalba: $1',
'suspicious-userlogout'      => 'Jūsų prašymas atsijungti buvo atmestas, nes, atrodo, jį klaidingai išsiuntė naršyklė arba spartinantysis tarpinis serveris.',

# JavaScript password checks
'password-strength'            => 'Apskaičiuotas slaptažodžio stiprumas: $1',
'password-strength-bad'        => 'BLOGAS',
'password-strength-mediocre'   => 'vidutiniškas',
'password-strength-acceptable' => 'priimtinas',
'password-strength-good'       => 'geras',
'password-retype'              => 'Čia pakartokite slaptažodį',
'password-retype-mismatch'     => 'Slaptažodžiai nesutampa',

# Password reset dialog
'resetpass'                 => 'Keisti slaptažodį',
'resetpass_announce'        => 'Jūs prisijungėte su atsiųstu laikinuoju kodu. Norėdami užbaigti prisijungimą, čia jums reikia nustatyti naująjį slaptažodį:',
'resetpass_text'            => '<!-- Įterpkite čia tekstą -->',
'resetpass_header'          => 'Keisti paskyros slaptažodį',
'oldpassword'               => 'Senas slaptažodis:',
'newpassword'               => 'Naujas slaptažodis:',
'retypenew'                 => 'Pakartokite naują slaptažodį:',
'resetpass_submit'          => 'Nustatyti slaptažodį ir prisijungti',
'resetpass_success'         => 'Jūsų slaptažodis pakeistas sėkmingai! Dabar prisijungiama...',
'resetpass_forbidden'       => 'Slaptažodžiai negali būti pakeisti',
'resetpass-no-info'         => 'Jūs turite būti prisijugęs, kad pasiektumėte puslapį tiesiogiai.',
'resetpass-submit-loggedin' => 'Keisti slaptažodį',
'resetpass-submit-cancel'   => 'Atšaukti',
'resetpass-wrong-oldpass'   => 'Klaidingas laikinas ar esamas slaptažodis.
Jūs galbūt jau sėkmingai pakeitėte savo slaptažodį ar gavote naują laikiną slaptažodį.',
'resetpass-temp-password'   => 'Laikinas slaptažodis:',

# Edit page toolbar
'bold_sample'     => 'Paryškintas tekstas',
'bold_tip'        => 'Paryškinti tekstą',
'italic_sample'   => 'Tekstas kursyvu',
'italic_tip'      => 'Tekstas kursyvu',
'link_sample'     => 'Nuorodos pavadinimas',
'link_tip'        => 'Vidinė nuoroda',
'extlink_sample'  => 'http://www.example.com nuorodos pavadinimas',
'extlink_tip'     => 'Išorinė nuoroda (nepamirškite http:// priedėlio)',
'headline_sample' => 'Skyriaus pavadinimas',
'headline_tip'    => 'Antro lygio skyriaus pavadinimas',
'math_sample'     => 'Įveskite formulę',
'math_tip'        => 'Matematinė formulė (LaTeX formatu)',
'nowiki_sample'   => 'Čia įterpkite neformuotą tekstą',
'nowiki_tip'      => 'Ignoruoti wiki formatą',
'image_sample'    => 'Pavyzdys.jpg',
'image_tip'       => 'Įdėti failą',
'media_sample'    => 'Pavyzdys.ogg',
'media_tip'       => 'Nuoroda į failą',
'sig_tip'         => 'Jūsų parašas bei laikas',
'hr_tip'          => 'Horizontali linija (naudokite taupiai)',

# Edit pages
'summary'                          => 'Komentaras:',
'subject'                          => 'Tema/antraštė:',
'minoredit'                        => 'Tai smulkus pataisymas',
'watchthis'                        => 'Stebėti šį puslapį',
'savearticle'                      => 'Išsaugoti puslapį',
'preview'                          => 'Peržiūra',
'showpreview'                      => 'Rodyti peržiūrą',
'showlivepreview'                  => 'Tiesioginė peržiūra',
'showdiff'                         => 'Rodyti skirtumus',
'anoneditwarning'                  => "'''Dėmesio:''' Jūs nesate prisijungęs. Jūsų IP adresas bus įrašytas į šio puslapio istoriją.",
'anonpreviewwarning'               => "''Jūs nesate prisijungęs. Išsaugojant jūsų IP adresas bus rodomas šio puslapio redagavimo istorijoje.''",
'missingsummary'                   => "'''Priminimas:''' Jūs nenurodėte keitimo komentaro. Jei vėl paspausite „{{int:savearticle}}“, jūsų keitimas bus išsaugotas be jo.",
'missingcommenttext'               => 'Prašome įvesti komentarą.',
'missingcommentheader'             => "'''Priminimas:''' Jūs nenurodėte šio komentaro pavadinimo/antraštės.
Jei vėl paspausite „{{int:savearticle}}“, jūsų keitimas bus įrašytas be jo.",
'summary-preview'                  => 'Komentaro peržiūra:',
'subject-preview'                  => 'Skyrelio/antraštės peržiūra:',
'blockedtitle'                     => 'Naudotojas yra užblokuotas',
'blockedtext'                      => "'''Jūsų naudotojo vardas arba IP adresas yra užblokuotas.'''

Užblokavo $1. Nurodyta priežastis yra ''$2''.

* Blokavimo pradžia: $8
* Blokavimo pabaiga: $6
* Numatytas blokuojamasis: $7

Jūs galite susisiekti su $1 arba kuriuo nors kitu [[{{MediaWiki:Grouppage-sysop}}|administratoriumi]] ir aptarti neaiškumus dėl blokavimo.
Atkreipkite dėmesį, kad negalėsite naudotis funkcija „Rašyti laišką šiam naudotojui“, jei nesate užsiregistravę ir pateikę realaus savo el. pašto adreso naudotojo [[Special:Preferences|nustatymuose]], arba, jei jums užblokuotas šios funkcijos naudojimas.
Jūsų IP adresas yra $3, o blokavimo ID yra #$5.
Prašome nurodyti vieną iš jų ar abu, kai kreipiatės dėl blokavimo.",
'autoblockedtext'                  => "Jūsų IP adresas buvo automatiškai užblokuotas, nes jį naudojo kitas naudotojas, kurį užblokavo $1.
Nurodyta priežastis yra ši:

:''$2''

* Blokavimo pradžia: $8
* Blokavimo pabaiga: $6
* Numatomas blokavimo laikas: $7

Jūs galite susisiekti su $1 arba kitu [[{{MediaWiki:Grouppage-sysop}}|administratoriumi]], kad aptartumėte neaiškumus dėl blokavimo.

Jūs negalite naudotis funkcija „Rašyti laišką šiam naudotojui“, jei nesate nurodę tikro el. pašto adreso savo [[Special:Preferences|naudotojo nustatymuose]]. Taip pat Jūs negalite naudotis šia funkcija, jei Jums užblokuotas jos naudojimas.

Jūsų IP adresas yra $3, blokavimo ID yra $5.
Prašome nurodyti šiuos duomenis visais atvejais, kai kreipiatės dėl blokavimo.",
'blockednoreason'                  => 'priežastis nenurodyta',
'blockedoriginalsource'            => "Žemiau yra rodomas '''$1''' turinys:",
'blockededitsource'                => "''Jūsų keitimų'' tekstas puslapiui '''$1''' yra rodomas žemiau:",
'whitelistedittitle'               => 'Norint redaguoti reikia prisijungti',
'whitelistedittext'                => 'Jūs turite $1, kad redaguotumėte puslapius.',
'confirmedittext'                  => 'Jums reikia patvirtinti el. pašto adresą, prieš redaguojant puslapius.
Prašome nurodyti ir patvirtinti jūsų el. pašto adresą per jūsų [[Special:Preferences|naudotojo nustatymus]].',
'nosuchsectiontitle'               => 'Nėra tokio skyriaus',
'nosuchsectiontext'                => 'Jūs mėginote redaguoti skyrių, kuris neegzistuoja.
Jis galėjo būti perkeltas arba ištrintas, kol peržiūrėjote puslapį.',
'loginreqtitle'                    => 'Reikalingas prisijungimas',
'loginreqlink'                     => 'prisijungti',
'loginreqpagetext'                 => 'Jums reikia $1, kad matytumėte kitus puslapius.',
'accmailtitle'                     => 'Slaptažodis išsiųstas.',
'accmailtext'                      => "Atsitiktinai sugeneruotas naudotojo [[User talk:$1|$1]] slaptažodis nusiųstas į $2.

Šios naujos paskyros slaptažodis gali būti pakeistas ''[[Special:ChangePassword|keisti slaptažodį]]'' puslapyje.",
'newarticle'                       => '(Naujas)',
'newarticletext'                   => "Jūs patekote į dar neegzistuojantį puslapį.
Norėdami sukurti puslapį, pradėkite rašyti žemiau esančiame įvedimo lauke
(plačiau [[{{MediaWiki:Helppage}}|pagalbos puslapyje]]).
Jei patekote čia per klaidą, paprasčiausiai spustelkite  naršyklės mygtuką '''atgal'''.",
'anontalkpagetext'                 => "----''Tai yra anoniminio naudotojo, nesusikūrusio arba nenaudojančio paskyros, aptarimų puslapis.
Dėl to naudojamas IP adresas jo identifikavimui.
Šis IP adresas gali būti dalinamas keliems naudotojams.
Jeigu Jūs esate anoniminis naudotojas ir atrodo, kad komentarai nėra skirti Jums, [[Special:UserLogin/signup|sukurkite paskyrą]] arba [[Special:UserLogin|prisijunkite]], ir nebūsite tapatinamas su kitais anoniminiais naudotojais.''",
'noarticletext'                    => 'Šiuo metu šiame puslapyje nėra jokio teksto.
Jūs galite [[Special:Search/{{PAGENAME}}|ieškoti šio puslapio pavadinimo]] kituose puslapiuose,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ieškoti susijusių įrašų],
arba [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaguoti šį puslapį]</span>.',
'noarticletext-nopermission'       => 'Šiuo metu šiame puslapyje nėra jokio teksto.
Jūs galite [[Special:Search/{{PAGENAME}}|ieškoti šio puslapio pavadinimo]] kituose puslapiuose,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ieškoti susijusių įrašų]</span>.',
'userpage-userdoesnotexist'        => 'Naudotojo paskyra „$1“ yra neužregistruota. Prašom patikrinti, ar jūs norite kurti/redaguoti šį puslapį.',
'userpage-userdoesnotexist-view'   => 'Naudotojo paskyra „$1“ neužregistruota.',
'blocked-notice-logextract'        => 'Šis naudotojas šiuo metu yra užblokuotas.
Žemiau pateikiamas paskutinis blokavimo istorijos įrašas:',
'clearyourcache'                   => "'''Dėmesio:''' Išsaugoję jums gali prireikti išvalyti jūsų naršyklės podėlį, kad pamatytumėte pokyčius. '''Mozilla / Safari / Konqueror:''' laikydami ''Shift'' pasirinkite ''Atsiųsti iš naujo'', arba paspauskite ''Ctrl-Shift-R'' (sistemoje Apple Mac ''Cmd-Shift-R''); '''IE:''' laikydami ''Ctrl'' paspauskite ''Atnaujinti'', arba paspauskite ''Ctrl-F5''; '''Konqueror:''' tiesiog paspauskite ''Perkrauti'' mygtuką, arba paspauskite ''F5''; '''Opera''' naudotojams gali prireikti pilnai išvalyti jų podėlį ''Priemonės→Nuostatos''.",
'usercssyoucanpreview'             => "'''Patarimas:''' Naudokite „{{int:showpreview}}“ mygtuką, kad išmėgintumėte savo naująjį CSS prieš išsaugant.",
'userjsyoucanpreview'              => "'''Patarimas:''' Naudokite „{{int:showpreview}}“ mygtuką, kad išmėgintumėte savo naująjį JS prieš išsaugant.",
'usercsspreview'                   => "'''Nepamirškite, kad jūs tik peržiūrit savo naudotojo CSS, jis dar nebuvo išsaugotas!'''",
'userjspreview'                    => "'''Nepamirškite, kad jūs tik testuojat/peržiūrit savo naudotojo JavaScript, jis dar nebuvo išsaugotas!'''",
'userinvalidcssjstitle'            => "'''Dėmesio:''' Nėra jokios išvaizdos „$1“. Nepamirškite, kad savo .css ir .js puslapiai naudoja pavadinimą mažosiomis raidėmis, pvz., {{ns:user}}:Foo/vector.css, o ne {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Atnaujinta)',
'note'                             => "'''Pastaba:'''",
'previewnote'                      => "'''Nepamirškite, kad tai tik peržiūra, pakeitimai dar nėra išsaugoti!'''",
'previewconflict'                  => 'Ši peržiūra parodo tekstą iš viršutiniojo teksto redagavimo lauko taip, kaip jis bus rodomas, jei pasirinksite išsaugoti.',
'session_fail_preview'             => "'''Atsiprašome! Mes negalime vykdyti jūsų keitimo dėl sesijos duomenų praradimo.
Prašome pamėginti vėl. Jei tai nepadeda, pamėginkite atsijungti ir prisijungti atgal.'''",
'session_fail_preview_html'        => "'''Atsiprašome! Mes negalime apdoroti jūsų keitimo dėl sesijos duomenų praradimo.'''

''Kadangi {{SITENAME}} grynasis HTML yra įjungtas, peržiūra yra paslėpta kaip atsargumo priemonė prieš JavaScript atakas.''

'''Jei tai teisėtas keitimo bandymas, prašome pamėginti vėl. Jei tai nepadeda, pamėginkite [[Special:UserLogout|atsijungti]] ir prisijungti atgal.'''",
'token_suffix_mismatch'            => "'''Jūsų pakeitimas buvo atmestas, nes jūsų naršyklė iškraipė skyrybos ženklus keitimo žymėje. Keitimas buvo atmestas norint apsaugoti puslapio tekstą nuo sugadinimo. Taip kartais būna, kai jūs naudojate anoniminį tarpinio serverio paslaugą.'''",
'editing'                          => 'Taisomas $1',
'editingsection'                   => 'Taisomas $1 (skyrelis)',
'editingcomment'                   => 'Taisomas $1 (komentaras)',
'editconflict'                     => 'Išpręskite konfliktą: $1',
'explainconflict'                  => "Kažkas kitas jau pakeitė puslapį nuo tada, kai jūs pradėjote jį redaguoti.
Viršutiniame tekstiniame lauke pateikta šiuo metu esanti puslapio versija.
Jūsų keitimai pateikti žemiau esančiame lauke.
Jums reikia sujungti jūsų pakeitimus su esančia versija.
Kai paspausite „{{int:savearticle}}“, bus įrašytas '''tik''' tekstas viršutiniame tekstiniame lauke.",
'yourtext'                         => 'Jūsų tekstas',
'storedversion'                    => 'Išsaugota versija',
'nonunicodebrowser'                => "'''ĮSPĖJIMAS: Jūsų naršyklė nepalaiko unikodo. Kad būtų saugu redaguoti puslapį, ne ASCII simboliai redagavimo lauke bus rodomi kaip šešioliktainiai kodai.'''",
'editingold'                       => "'''ĮSPĖJIMAS: Jūs keičiate ne naujausią puslapio versiją.
Jei išsaugosite savo keitimus, po to daryti pakeitimai pradings.'''",
'yourdiff'                         => 'Skirtumai',
'copyrightwarning'                 => "Primename, kad viskas, kas patenka į {{SITENAME}}, yra laikoma paskelbtu pagal $2 (detaliau - $1). Jei nenorite, kad jūsų indėlis būtų be gailesčio redaguojamas ir platinamas, čia nerašykite.<br />
Jūs taip pat pasižadate, kad tai jūsų pačių rašytas turinys arba kopijuotas iš viešų ar panašių nemokamų šaltinių.
'''NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!'''",
'copyrightwarning2'                => "Primename, kad viskas, kas patenka į {{SITENAME}} gali būti redaguojama, perdaroma, ar pašalinama kitų naudotojų. Jei nenorite, kad jūsų indėlis būtų be gailesčio redaguojamas, čia nerašykite.<br />
Taip pat jūs pasižadate, kad tai jūsų pačių rašytas tekstas arba kopijuotas
iš viešų ar panašių nemokamų šaltinių (detaliau - $1).
'''NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!'''",
'longpageerror'                    => "'''KLAIDA: Tekstas, kurį pateikėte, yra $1 kilobaitų ilgio,
kuris yra didesnis nei daugiausiai leistini $2 kilobaitai. Jis nebus išsaugotas.'''",
'readonlywarning'                  => "'''DĖMESIO: Duomenų bazė buvo užrakinta techninei profilaktikai, todėl šiuo metu negalėsite išsaugoti savo pakeitimų. Siūlome nusikopijuoti tekstą į tekstinį failą ir vėliau jį čia išsaugoti.'''

Ją užrakinusio administratoriaus paaiškinimas: $1",
'protectedpagewarning'             => "'''Dėmesio: Šis puslapis yra užrakintas taip, kad jį redaguoti gali tik administratoriaus teises turintys naudotojai.'''
Naujausias įrašas žurnale yra pateiktas žemiau:",
'semiprotectedpagewarning'         => "'''Pastaba:''' Šis puslapis buvo užrakintas, jį gali redaguoti tik registruoti naudotojai.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'cascadeprotectedwarning'          => "'''Dėmesio''': Šis puslapis buvo užrakintas taip, kad tik naudotojai su administratoriaus teisėmis galėtų jį redaguoti, nes jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi:",
'titleprotectedwarning'            => "'''Dėmesio: Šis puslapis buvo užrakintas taip, kad tik [[Special:ListGroupRights|kai kurie naudotojai]] galėtų jį sukurti.'''
Naujausias įrašas žurnale yra pateiktas žemiau:",
'templatesused'                    => '{{PLURAL:$1|Šablonas|Šablonai}}, naudojami puslapyje:',
'templatesusedpreview'             => '{{PLURAL:$1|Šablonas|Šablonai}}, naudoti šioje peržiūroje:',
'templatesusedsection'             => 'Šiame skyriuje {{PLURAL:$1|naudojamas šablonas|naudojami šablonai}}:',
'template-protected'               => '(apsaugotas)',
'template-semiprotected'           => '(pusiau apsaugotas)',
'hiddencategories'                 => 'Šis puslapis priklauso $1 {{PLURAL:$1|paslėptai kategorijai|paslėptoms kategorijoms|paslėptų kategorijų}}:',
'edittools'                        => '<!-- Šis tekstas bus rodomas po redagavimo ir įkėlimo formomis. -->',
'nocreatetitle'                    => 'Puslapių kūrimas apribotas',
'nocreatetext'                     => '{{SITENAME}} apribojo galimybę kurti naujus puslapius.
Jūs galite grįžti ir redaguoti jau esantį puslapį, arba [[Special:UserLogin|prisijungti arba sukurti paskyrą]].',
'nocreate-loggedin'                => 'Jūs neturite teisės kurti puslapius.',
'sectioneditnotsupported-title'    => 'Skyrių redagavimas nepalaikomas',
'sectioneditnotsupported-text'     => 'Šiame puslapyje skyrių redagavimas nepalaikomas.',
'permissionserrors'                => 'Teisių klaida',
'permissionserrorstext'            => 'Jūs neturite teisių tai daryti dėl {{PLURAL:$1|šios priežasties|šių priežasčių}}:',
'permissionserrorstext-withaction' => 'Jūs neturite leidimo $2 dėl {{PLURAL:$1|šios priežasties|šių priežasčių}}:',
'recreate-moveddeleted-warn'       => "'''Dėmesio: Jūs atkuriate puslapį, kuris anksčiau buvo ištrintas.'''

Jūs turite nuspręsti, ar reikėtų toliau redaguoti šį puslapį.
Dėl patogumo čia pateikta šio puslapio šalinimų ir perkėlimų istorija:",
'moveddeleted-notice'              => 'Šis puslapis buvo ištrintas.
Žemiau pateikta puslapio šalinimų ir pervadinimų istorija.',
'log-fulllog'                      => 'Rodyti visą istoriją',
'edit-hook-aborted'                => 'Keitimas nutrauktas užlūžimo.
Tam nėra paaiškinimo.',
'edit-gone-missing'                => 'Negalima atnaujinti puslapio.
Greičiausiai jis yra ištrintas.',
'edit-conflict'                    => 'Redagavimo konfliktas.',
'edit-no-change'                   => 'Jūsų keitimas buvo ignoruotas kadangi nebuvo atlikta jokių teksto pakeitimų.',
'edit-already-exists'              => 'Negalima sukurti naujo puslapio.
Jis jau egzistuoja.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Įspėjimas: Šiame puslapyje yra per daug užtrunkančių analizatoriaus funkcijų šaukinių.

Tai turėtų būti mažiau nei $2 {{PLURAL:$2|šaukinys|šaukiniai|šaukinių}}, tačiau dabar yra $1 {{PLURAL:$1|šaukinys|šaukiniai|šaukinių}}.',
'expensive-parserfunction-category'       => 'Puslapiai su per daug brangių kodo analizuoklio funkcijų šaukinių',
'post-expand-template-inclusion-warning'  => 'Įspėjimas: Šablonų įterpimo dydis per didelis.
Kai kurie šablonai nebus įtraukti.',
'post-expand-template-inclusion-category' => 'Puslapiai, kur šablonų įterpimo dydis viršijamas',
'post-expand-template-argument-warning'   => 'Įspėjimas: Šis puslapis turi bent vieną šablono argumentą, kuris turi per didelį išplėtimo dydį.
Šie argumentai buvo praleisti.',
'post-expand-template-argument-category'  => 'Puslapiai, turintys praleistų šablono argumentų',
'parser-template-loop-warning'            => 'Aptiktas šablono ciklas: [[$1]]',
'parser-template-recursion-depth-warning' => 'Šablono rekursinio gylio riba viršyta ($1)',
'language-converter-depth-warning'        => 'Kalbos keitiklio gylio riba viršyta ($1)',

# "Undo" feature
'undo-success' => 'Keitimas gali būti atšauktas. Prašome patikrinti palyginimą, esantį žemiau, kad patvirtintumėte, kad jūs tai ir norite padaryti, ir tada išsaugokite pakeitimus, esančius žemiau, kad užbaigtumėte keitimo atšaukimą.',
'undo-failure' => 'Keitimas negali būti atšauktas dėl konfliktuojančių tarpinių keitimų.',
'undo-norev'   => 'Keitimas negali būti atšauktas, kadangi jis neegzistuoja arba buvo ištrintas.',
'undo-summary' => 'Atšauktas [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarimas]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]]) keitimas ($1 versija)',

# Account creation failure
'cantcreateaccounttitle' => 'Paskyrų kūrimas negalimas',
'cantcreateaccount-text' => "Paskyrų kūrimą iš šio IP adreso ('''$1''') užblokavo [[User:$3|$3]].

$3 nurodyta priežastis yra ''$2''",

# History pages
'viewpagelogs'           => 'Rodyti šio puslapio specialiuosius veiksmus',
'nohistory'              => 'Šis puslapis neturi keitimų istorijos.',
'currentrev'             => 'Dabartinė versija',
'currentrev-asof'        => 'Dabartinė $1 versija',
'revisionasof'           => '$1 versija',
'revision-info'          => '$1 versija naudotojo $2',
'previousrevision'       => '←Ankstesnė versija',
'nextrevision'           => 'Vėlesnė versija→',
'currentrevisionlink'    => 'Dabartinė versija',
'cur'                    => 'dab',
'next'                   => 'kitas',
'last'                   => 'pask',
'page_first'             => 'pirm',
'page_last'              => 'pask',
'histlegend'             => "Skirtumai tarp versijų: pažymėkite lyginamas versijas ir spustelkite ''Enter'' klavišą arba mygtuką apačioje.<br />
Žymėjimai: (dab) = palyginimas su naujausia versija,
(pask) = palyginimas su prieš tai buvusia versija, S = smulkus keitimas.",
'history-fieldset-title' => 'Ieškoti istorijoje',
'history-show-deleted'   => 'Tik ištrinti',
'histfirst'              => 'Seniausi',
'histlast'               => 'Paskutiniai',
'historysize'            => '($1 {{PLURAL:$1|baitas|baitai|baitų}})',
'historyempty'           => '(tuščia)',

# Revision feed
'history-feed-title'          => 'Versijų istorija',
'history-feed-description'    => 'Šio puslapio versijų istorija projekte',
'history-feed-item-nocomment' => '$1 $2',
'history-feed-empty'          => 'Prašomas puslapis neegzistuoja.
Jis galėjo būti ištrintas iš projekto, arba pervardintas.
Pamėginkite [[Special:Search|ieškoti projekte]] susijusių naujų puslapių.',

# Revision deletion
'rev-deleted-comment'         => '(komentaras pašalintas)',
'rev-deleted-user'            => '(naudotojo vardas pašalintas)',
'rev-deleted-event'           => '(įrašas pašalintas)',
'rev-deleted-user-contribs'   => '[vardas arba IP adresas pašalintas - redagavimas paslėptas nuo prisidėjimų]',
'rev-deleted-text-permission' => "Ši puslapio versija buvo '''pašalinta'''.
Daugiau detalių galima rasti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-deleted-text-unhide'     => "Ši puslapio versija buvo '''ištrinta'''.
Trynimo detales rasite [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ištrintų puslapių sąraše].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-suppressed-text-unhide'  => "Ši puslapio versija buvo '''paslėpta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų istorijoje].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-deleted-text-view'       => "Ši puslapio versija buvo '''pašalinta'''.
Kaip administratorius, jūs galite ją pamatyti;
daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-suppressed-text-view'    => "Ši puslapio versija buvo '''paslėpta'''.
Kaip administratorius, jūs galite ją peržiūrėti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų sąraše].",
'rev-deleted-no-diff'         => "Jūs negalite peržiūrėti šio skirtumo, nes viena iš versijų yra '''ištrinta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-suppressed-no-diff'      => "Jūs negalite peržiūrėti šio skirtumo, nes viena iš versijų buvo '''ištrinta'''.",
'rev-deleted-unhide-diff'     => "Viena iš šio skirtumo versijų yra '''ištrinta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} trynimų istorijoje].
Kaip administratorius, jūs vis tiek galite [$1 pamatyti šį skirtumą].",
'rev-suppressed-unhide-diff'  => "Viena iš šio skirtumo versijų buvo '''paslėpta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų istorijoje].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-deleted-diff-view'       => "Viena iš šio palyginimo versija buvo '''pašalinta'''.
Kaip administratorius, jūs galite ją pamatyti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų žurnale].",
'rev-suppressed-diff-view'    => "Viena iš šio palyginimo versija buvo '''paslėpta'''.
Kaip administratorius, jūs galite ją pamatyti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų žurnale].",
'rev-delundel'                => 'rodyti/slėpti',
'rev-showdeleted'             => 'rodyti',
'revisiondelete'              => 'Trinti/atkurti versijas',
'revdelete-nooldid-title'     => 'Neleistina paskirties versija',
'revdelete-nooldid-text'      => 'Jūs nenurodėte versijos (-ų), kurioms įvykdyti šią funkciją, nurodyta versija neegzistuoja arba jūs bandote paslėpti esamą versiją.',
'revdelete-nologtype-title'   => 'Nenurodytas istorijos tipas',
'revdelete-nologtype-text'    => 'Jūs nenurodėte istorijos tipo, kuriam atlikti šį veiksmą.',
'revdelete-nologid-title'     => 'Neleistinas istorijos įrašas',
'revdelete-nologid-text'      => 'Jūs arba nenurodėte paskirties istorijos įvykio, kuriam atlikti šį veiksmą, arba nurodytas įrašas neegzistuoja.',
'revdelete-no-file'           => 'Nurodytas failas neegzistuoja.',
'revdelete-show-file-confirm' => 'Ar tikrai norite peržiūrėti ištrintą failo „<nowiki>$1</nowiki>“ $2 $3 versiją?',
'revdelete-show-file-submit'  => 'Taip',
'revdelete-selected'          => "'''{{PLURAL:$2|Pasirinkta [[:$1]] versija|Pasirinktos [[:$1]] versijos}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Pasirinktas istorijos įvykis|Pasirinkti istorijos įvykiai}}:'''",
'revdelete-text'              => "'''Ištrintos versijos bei įvykiai vistiek dar bus rodomi puslapio istorijoje ir specialiųjų veiksmų istorijoje, bet jų turinio dalys nebus viešai prieinamos.'''
Kiti administratoriai iš {{SITENAME}} vistiek galės pasiekti paslėptą turinį ir galės jį atkurti vėl per tą pačią sąsają, nebent yra nustatyti papildomi apribojimai.",
'revdelete-confirm'           => 'Prašome patvirtinti, kad jūs tai ketinate padaryti, kad jūs suprantate padarinius, ir kad jūs tai darote pagal [[{{MediaWiki:Policy-url}}|politiką]].',
'revdelete-suppress-text'     => "Ištrynimas turėtų būti taikomas '''tik''' šiais atvejais:
* Netinkama asmeninė informacija
*: ''namų adresai, telefonų numeriai, asmens kodai ir t. t.''",
'revdelete-legend'            => 'Nustatyti matomumo apribojimus:',
'revdelete-hide-text'         => 'Slėpti versijos tekstą',
'revdelete-hide-image'        => 'Slėpti failo turinį',
'revdelete-hide-name'         => 'Slėpti veiksmą ir paskirtį',
'revdelete-hide-comment'      => 'Slėpti redagavimo komentarą',
'revdelete-hide-user'         => 'Slėpti redagavusiojo naudotojo vardą ar IP adresą',
'revdelete-hide-restricted'   => 'Nuslėpti duomenis nuo adminstratorių kaip ir nuo kitų',
'revdelete-radio-same'        => '(nekeisti)',
'revdelete-radio-set'         => 'Taip',
'revdelete-radio-unset'       => 'Ne',
'revdelete-suppress'          => 'Slėpti duomenis nuo administratorių kaip ir nuo kitų',
'revdelete-unsuppress'        => 'Šalinti apribojimus atkurtose versijose',
'revdelete-log'               => 'Priežastis:',
'revdelete-submit'            => 'Taikyti {{PLURAL:$1|pasirinktai versijai|pasirinktoms versijoms}}',
'revdelete-logentry'          => 'pakeistas [[$1]] versijos matomumas',
'logdelete-logentry'          => 'pakeistas [[$1]] įvykio matomumas',
'revdelete-success'           => "'''Versijos matomumas sėkmingai pakeistas.'''",
'revdelete-failure'           => "'''Versijos rodomumas negali būti nustatytas:'''
$1",
'logdelete-success'           => "'''Įvykio matomumas sėkmingai nustatytas.'''",
'logdelete-failure'           => "'''Sąrašo rodomumas negali būti nustatytas:'''
$1",
'revdel-restore'              => 'Keisti matomumą',
'revdel-restore-deleted'      => 'Ištrintos versijos',
'revdel-restore-visible'      => 'Matomos versijos',
'pagehist'                    => 'Puslapio istorija',
'deletedhist'                 => 'Ištrinta istorija',
'revdelete-content'           => 'turinys',
'revdelete-summary'           => 'keitimo komentaras',
'revdelete-uname'             => 'naudotojo vardas',
'revdelete-restricted'        => 'uždėti apribojimai administratoriams',
'revdelete-unrestricted'      => 'pašalinti apribojimai administratoriams',
'revdelete-hid'               => 'slėpti $1',
'revdelete-unhid'             => 'atslėpti $1',
'revdelete-log-message'       => '$1 $2 {{PLURAL:$2|versijai|versijoms|versijų}}',
'logdelete-log-message'       => '$1 $2 {{PLURAL:$2|įvykiui|įvykiams|įvykių}}',
'revdelete-hide-current'      => 'Klaida slepiant $1, $2 keitimą: tai yra dabartinė versija.
Ji negali būti paslėpta.',
'revdelete-show-no-access'    => 'Klaida rodant $1, $2 keitimą: jis pažymėtas ženklu „apribotas“.
Jūs neturite teisių jo peržiūrai.',
'revdelete-modify-no-access'  => 'Klaida taisant $1, $2 keitimą: jis pažymėtas ženklu „apribotas“.
Jūs neturite teisių jo taisymui.',
'revdelete-modify-missing'    => 'Klaida keičiant versiją $1: ji nerandama duomenų bazėje!',
'revdelete-no-change'         => "'''Įspėjimas:''' versija $2, $1 jau turi norimus rodomumo nustatymus.",
'revdelete-concurrent-change' => 'Klaida keičiant $2, $1 versiją: jos statusas jau buvo pakeistas kažkieno kito kol jūs redagavote.
Prašome patikrinti sąrašus.',
'revdelete-only-restricted'   => 'Klaida slepiant $1 $2 elementą: jūs negalite paslėpti elementų nuo administratorių peržiūros nepasirenkant vieno iš kitų matomumo nustatymų.',
'revdelete-reason-dropdown'   => '*Dažnos trynimo priežastys
** Autorinių teisių pažeidimas
** Netinkama asmeninė informacija
** Informacija, kuri gali būti šmeižikiška',
'revdelete-otherreason'       => 'Kita/papildoma priežastis:',
'revdelete-reasonotherlist'   => 'Kita priežastis',
'revdelete-edit-reasonlist'   => 'Keisti trynimo priežastis',
'revdelete-offender'          => 'Versijos autorius:',

# Suppression log
'suppressionlog'     => 'Trynimo istorija',
'suppressionlogtext' => 'Žemiau yra trynimų ir blokavimų sąrašas, įtraukiant turinį, paslėptą nuo administratorių.
Žiūrėkite [[Special:IPBlockList|IP blokavimų sąrašą]], kad rastumėte dabar veikiančius draudimus ir blokavimus.',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|perkelta $3 versija|perkeltos $3 versijos|perkeltos $3 versijų}} iš $1 į $2',
'revisionmove'                 => 'Daugiau versijų iš „$1“',
'revmove-explain'              => 'Šie pakeitimai bus perkelti iš $1 į nurodytą paskirties puslapį. Jeigu paskirties puslapio nėra, jis bus sukurtas. Priešingu atveju šie pakeitimai bus sujungti su puslapio istorija.',
'revmove-legend'               => 'Nurodykite paskirties puslapį ir komentarą',
'revmove-submit'               => 'Perkelti versijas į pasirinktą puslapį',
'revisionmoveselectedversions' => 'Perkelti pasirinktas versijas',
'revmove-reasonfield'          => 'Priežastis:',
'revmove-titlefield'           => 'Paskirties puslapis:',
'revmove-badparam-title'       => 'Netinkami parametrai',
'revmove-badparam'             => 'Jūsų užklausoje yra negalimų parametrų ar jų nepakanka.
Grįžkite į ankstesnį puslapį ir bandykite dar kartą.',
'revmove-norevisions-title'    => 'Neleistina paskirties versija',
'revmove-norevisions'          => 'Nenurodėte nė vienos versijos arba nurodyta versija neegzistuoja.',
'revmove-nullmove-title'       => 'Blogas pavadinimas',
'revmove-nullmove'             => 'Puslapis, į kurį perkeliamas keitimas, negali būti tokio paties pavadinimo, kaip šaltinio puslapis.
Grįžkite į ankstesnį puslapį ir pasirinkite kitą pavadinimą iš "$1".',
'revmove-success-existing'     => '{{PLURAL:$1|[[$2]] keitimas buvo perkeltas|$1 [[$2]] keitimai buvo perkelti}} į egzistuojantį puslapį [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Vienas [[$2]] keitimas buvo|$1 [[$2]] keitimai buvo}} perkelti į naujai sukurtą puslapį [[$3]].',

# History merging
'mergehistory'                     => 'Sujungti puslapių istorijas',
'mergehistory-header'              => "Šis puslapis leidžia jus prijungti vieno pirminio puslapio istorijos versijas į naujesnį puslapį. Įsitikinkite, kad šis pakeitimas palaikys istorinį puslapio tęstinumą.

'''Turi likti bent dabartinė pirminio puslapio versija.'''",
'mergehistory-box'                 => 'Sujungti dviejų puslapių versijas:',
'mergehistory-from'                => 'Pirminis puslapis:',
'mergehistory-into'                => 'Paskirties puslapis:',
'mergehistory-list'                => 'Sujungiamos keitimų istorijos',
'mergehistory-merge'               => 'Šios [[:$1]] versijos gali būti sujungtos į [[:$2]]. Naudokite akučių stulpelį, kad sujungtumėte tik tas versijas, kurios sukurtos tuo ar ankstesniu laiku. Pastaba: panaudojus navigacines nuorodas, šis stulpelis bus grąžintas į pradinę būseną.',
'mergehistory-go'                  => 'Rodyti sujungiamus keitimus',
'mergehistory-submit'              => 'Sujungti versijas',
'mergehistory-empty'               => 'Versijos negali būti sujungtos',
'mergehistory-success'             => '$3 [[:$1]] {{PLURAL:$3|versija|versijos|versijų}} sėkmingai {{PLURAL:$3|sujungta|sujungtos|sujungta}} su [[:$2]].',
'mergehistory-fail'                => 'Nepavyksta atlikti istorijų sujungimo, prašome patikrinti puslapio ir laiko parametrus.',
'mergehistory-no-source'           => 'Šaltinio puslapis $1 neegzistuoja.',
'mergehistory-no-destination'      => 'Rezultato puslapis $1 neegzistuoja.',
'mergehistory-invalid-source'      => 'Pradinis puslapis turi turėti leistiną pavadinimą.',
'mergehistory-invalid-destination' => 'Rezultato puslapis turi turėti leistiną pavadinimą.',
'mergehistory-autocomment'         => '[[:$1]] prijungtas prie [[:$2]]',
'mergehistory-comment'             => '[[:$1]] prijungtas prie [[:$2]]: $3',
'mergehistory-same-destination'    => 'Šaltinio ir tikslo puslapiai negali būti vienodi',
'mergehistory-reason'              => 'Priežastis:',

# Merge log
'mergelog'           => 'Sujungimų istorija',
'pagemerge-logentry' => 'sujungė [[$1]] su [[$2]] (versijos iki $3)',
'revertmerge'        => 'Atskirti',
'mergelogpagetext'   => 'Žemiau yra paskiausių vieno su kitu puslapių sujungimų sąrašas.',

# Diffs
'history-title'            => '„$1“ versijų istorija',
'difference'               => '(Skirtumai tarp versijų)',
'difference-multipage'     => '(Skirtumai tarp puslapių)',
'lineno'                   => 'Eilutė $1:',
'compareselectedversions'  => 'Palyginti pasirinktas versijas',
'showhideselectedversions' => 'Rodyti/slėpti pasirinktas versijas',
'editundo'                 => 'atšaukti',
'diff-multi'               => '($2 {{PLURAL:$2|naudotojo|naudotojų|naudotojų}} $1 {{PLURAL:$1|tarpinis keitimas nėra rodomas|tarpiniai keitimai nėra rodomi|tarpinių keitimų nėra rodoma}})',
'diff-multi-manyusers'     => '(daugiau nei $2 {{PLURAL:$2|naudotojo|naudotojų|naudotojų}} $1 {{PLURAL:$1|tarpinis keitimas nėra rodomas|tarpiniai keitimai nėra rodomi|tarpinių keitimų nėra rodoma}})',

# Search results
'searchresults'                    => 'Paieškos rezultatai',
'searchresults-title'              => 'Paieškos rezultatai „$1“',
'searchresulttext'                 => 'Daugiau informacijos apie paiešką projekte {{SITENAME}} rasite [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Jūs ieškote '''[[:$1]]''' ([[Special:Prefixindex/$1|visi puslapiai, prasidedantys „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|visi puslapiai, rodantys į „$1“]])",
'searchsubtitleinvalid'            => "Ieškoma '''$1'''",
'toomanymatches'                   => 'Perdaug atitikmenų buvo grąžinta. Prašome pabandyti kitokią užklausą',
'titlematches'                     => 'Puslapių pavadinimų atitikmenys',
'notitlematches'                   => 'Jokių pavadinimo atitikmenų',
'textmatches'                      => 'Puslapio turinio atitikmenys',
'notextmatches'                    => 'Jokių puslapių teksto atitikmenų',
'prevn'                            => '{{PLURAL:$1|atgal|ankstesnius $1}}',
'nextn'                            => '{{PLURAL:$1|toliau|tolimesnius $1}}',
'prevn-title'                      => '{{PLURAL:$1|Ankstesnis $1 rezultatas|Ankstesni $1 rezultatai|Ankstesni $1 rezultatų}}',
'nextn-title'                      => '{{PLURAL:$1|Kitas $1 rezultatas|Kiti $1 rezultatai|Kiti $1 rezultatų}}',
'shown-title'                      => 'Rodyti $1 {{PLURAL:$1|rezultatą|rezultatus|rezultatus}} puslapyje',
'viewprevnext'                     => 'Žiūrėti ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Paieškos nustatymai',
'searchmenu-exists'                => "'''Puslapis pavadinimu „[[$1]]“ šioje wiki'''",
'searchmenu-new'                   => "'''Sukurti puslapį „[[:$1]]“ šioje wiki!'''",
'searchhelp-url'                   => 'Help:Turinys',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ieškoti puslapių su šiuo priešdėliu]]',
'searchprofile-articles'           => 'Turinio puslapiai',
'searchprofile-project'            => 'Pagalbos ir projekto puslapiai',
'searchprofile-images'             => 'Daugialypės terpės failai',
'searchprofile-everything'         => 'Viskas',
'searchprofile-advanced'           => 'Išplėstinė',
'searchprofile-articles-tooltip'   => 'Ieškoti čia: $1',
'searchprofile-project-tooltip'    => 'Ieškoti čia: $1',
'searchprofile-images-tooltip'     => 'Ieškoti failų',
'searchprofile-everything-tooltip' => 'Ieškoti viso turinio (tame tarpe aptarimų puslapių)',
'searchprofile-advanced-tooltip'   => 'Ieškoti skirtingose vardų srityse',
'search-result-size'               => '$1 ({{PLURAL:$2|1 žodis|$2 žodžiai|$2 žodžių}})',
'search-result-category-size'      => '{{PLURAL:$1|1 narys|$1 narių}} ({{PLURAL:$2|1 subkategorijoje|$2 subkategorijų}}, {{PLURAL:$3|1 failas|$3 failų}})',
'search-result-score'              => 'Tinkamumas: $1%',
'search-redirect'                  => '(peradresavimas $1)',
'search-section'                   => '(skyrius $1)',
'search-suggest'                   => 'Galbūt norėjote $1',
'search-interwiki-caption'         => 'Dukteriniai projektai',
'search-interwiki-default'         => '$1 rezultatai:',
'search-interwiki-more'            => '(daugiau)',
'search-mwsuggest-enabled'         => 'su pasiūlymais',
'search-mwsuggest-disabled'        => 'nėra pasiūlymų',
'search-relatedarticle'            => 'Susiję',
'mwsuggest-disable'                => 'Slėpti AJAX pasiūlymus',
'searcheverything-enable'          => 'Ieškoti visose vardų srityse',
'searchrelated'                    => 'susiję',
'searchall'                        => 'visi',
'showingresults'                   => "Žemiau rodoma iki '''$1''' {{PLURAL:$1|rezultato|rezultatų|rezultatų}} pradedant #'''$2'''.",
'showingresultsnum'                => "Žemiau rodoma '''$3''' {{PLURAL:$3|rezultato|rezultatų|rezultatų}}rezultatų pradedant #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultatas '''$1''' iš '''$3'''|Rezultatai '''$1 - $2''' iš '''$3'''}} pagal užklausą '''$4'''",
'nonefound'                        => "'''Pastaba''': Pagal nutylėjimą ieškoma tik kai kuriose vardų srityse. Pamėginkite prirašyti priešdėlį ''all:'', jei norite ieškoti viso turinio (įskaitant aptarimo puslapius, šablonus ir t. t.), arba naudokite norimą vardų sritį kaip priešdėlį.",
'search-nonefound'                 => 'Nėra rezultatų, atitinkančių užklausą.',
'powersearch'                      => 'Išplėstinė paieška',
'powersearch-legend'               => 'Išplėstinė paieška',
'powersearch-ns'                   => 'Ieškoti vardų srityse:',
'powersearch-redir'                => 'Įtraukti peradresavimus',
'powersearch-field'                => 'Ieškoti',
'powersearch-togglelabel'          => 'Pažymėti:',
'powersearch-toggleall'            => 'Viską',
'powersearch-togglenone'           => 'Nieko',
'search-external'                  => 'Išorinė paieška',
'searchdisabled'                   => 'Projekto {{SITENAME}} paieška yra uždrausta. Galite pamėginti ieškoti Google paieškos sistemoje. Paieškos sistemoje projekto {{SITENAME}} duomenys gali būti pasenę.',

# Quickbar
'qbsettings'               => 'Greitasis pasirinkimas',
'qbsettings-none'          => 'Nerodyti',
'qbsettings-fixedleft'     => 'Fiksuoti kairėje',
'qbsettings-fixedright'    => 'Fiksuoti dešinėje',
'qbsettings-floatingleft'  => 'Plaukiojantis kairėje',
'qbsettings-floatingright' => 'Plaukiojantis dešinėje',

# Preferences page
'preferences'                   => 'Nustatymai',
'mypreferences'                 => 'Mano nustatymai',
'prefs-edits'                   => 'Keitimų skaičius:',
'prefsnologin'                  => 'Neprisijungęs',
'prefsnologintext'              => 'Jums reikia būti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prisijungusiam]</span>, kad galėtumėte keisti savo nustatymus.',
'changepassword'                => 'Pakeisti slaptažodį',
'prefs-skin'                    => 'Išvaizda',
'skin-preview'                  => 'Peržiūra',
'prefs-math'                    => 'Matematika',
'datedefault'                   => 'Jokio pasirinkimo',
'prefs-datetime'                => 'Data ir laikas',
'prefs-personal'                => 'Naudotojo profilis',
'prefs-rc'                      => 'Paskutiniai keitimai',
'prefs-watchlist'               => 'Stebimų sąrašas',
'prefs-watchlist-days'          => 'Dienos rodomos stebimųjų sąraše:',
'prefs-watchlist-days-max'      => '(daugiausiai 7 dienos)',
'prefs-watchlist-edits'         => 'Kiek daugiausia keitimų rodyti išplėstiniame stebimųjų sąraše:',
'prefs-watchlist-edits-max'     => '(didžiausias skaičius: 1000)',
'prefs-watchlist-token'         => 'Stebimųjų sąrašo raktas:',
'prefs-misc'                    => 'Įvairūs nustatymai',
'prefs-resetpass'               => 'Keisti slaptažodį',
'prefs-email'                   => 'El. pašto nustatymai',
'prefs-rendering'               => 'Išvaizda',
'saveprefs'                     => 'Išsaugoti',
'resetprefs'                    => 'Išvalyti neišsaugotus pakeitimus',
'restoreprefs'                  => 'Atstatyti visus numatytuosius nustatymus',
'prefs-editing'                 => 'Redagavimas',
'prefs-edit-boxsize'            => 'Redagavimo lango dydis.',
'rows'                          => 'Eilutės:',
'columns'                       => 'Stulpeliai:',
'searchresultshead'             => 'Paieškos nustatymai',
'resultsperpage'                => 'Rezultatų puslapyje:',
'contextlines'                  => 'Eilučių rezultate:',
'contextchars'                  => 'Konteksto simbolių eilutėje:',
'stub-threshold'                => 'Puslapį žymėti <a href="#" class="stub">nebaigtu</a>, jei mažesnis nei:',
'stub-threshold-disabled'       => 'Išjungtas',
'recentchangesdays'             => 'Rodomos dienos paskutinių keitimų sąraše:',
'recentchangesdays-max'         => '(daugiausiai $1 {{PLURAL:$1|diena|dienos|dienų}})',
'recentchangescount'            => 'Numatytasis rodomas keitimų skaičius:',
'prefs-help-recentchangescount' => 'Į tai įeina paskutiniai keitimai, puslapių istorijos ir specialiųjų veiksmų istorijos.',
'prefs-help-watchlist-token'    => 'Įrašius slaptą raktą į šį laukelį, bus sugeneruotas RSS srautas su jūsų stebimųjų sąrašu.
Bet kas, žinantis šio lauko raktą, galės matyti jūsų stebimų sąrašą, todėl pasirinkite saugią reikšmę.
Galite panaudoti šią atsitiktinai sugeneruotą reikšmę: $1',
'savedprefs'                    => 'Nustatymai sėkmingai išsaugoti.',
'timezonelegend'                => 'Laiko juosta:',
'localtime'                     => 'Vietinis laikas:',
'timezoneuseserverdefault'      => 'Naudoti pagal nutylėjimą iš serverio',
'timezoneuseoffset'             => 'Kita (patikslinti skirtumą)',
'timezoneoffset'                => 'Skirtumas¹:',
'servertime'                    => 'Serverio laikas:',
'guesstimezone'                 => 'Paimti iš naršyklės',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktida',
'timezoneregion-arctic'         => 'Arktis',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlanto vandenynas',
'timezoneregion-australia'      => 'Australija',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Indijos vandenynas',
'timezoneregion-pacific'        => 'Ramusis vandenynas',
'allowemail'                    => 'Leisti siųsti el. laiškus iš kitų naudotojų',
'prefs-searchoptions'           => 'Paieškos nuostatos',
'prefs-namespaces'              => 'Vardų sritys',
'defaultns'                     => 'Pagal nutylėjimą ieškoti šiose vardų srityse:',
'default'                       => 'pagal nutylėjimą',
'prefs-files'                   => 'Failai',
'prefs-custom-css'              => 'Asmeninis CSS',
'prefs-custom-js'               => 'Asmeninis JavaSript',
'prefs-common-css-js'           => 'Bendras CSS/JS visoms išvaizdoms:',
'prefs-reset-intro'             => 'Jūs galite pasinaudoti šiuo puslapiu atstatyti jūsų nustatymus į svetainės numatytuosius.
Tai nebeatšaukiama.',
'prefs-emailconfirm-label'      => 'El. pašto patvirtinimas:',
'prefs-textboxsize'             => 'Redagavimo lango dydis',
'youremail'                     => 'El. paštas:',
'username'                      => 'Naudotojo vardas:',
'uid'                           => 'Naudotojo ID:',
'prefs-memberingroups'          => '{{PLURAL:$1|Grupės|Grupių}} narys:',
'prefs-registration'            => 'Registravimosi laikas:',
'yourrealname'                  => 'Tikrasis vardas:',
'yourlanguage'                  => 'Sąsajos kalba:',
'yourvariant'                   => 'Variantas:',
'yournick'                      => 'Parašas:',
'prefs-help-signature'          => 'Komentarai aptarimų puslapiuose turėtų būti pasirašyti su „<nowiki>~~~~</nowiki>“, kuris bus paverstas į jūsų parašą ir laiką.',
'badsig'                        => 'Neteisingas parašas; patikrinkite HTML žymes.',
'badsiglength'                  => 'Jūsų parašas per ilgas.
Jį turi sudaryti ne daugiau kaip $1 {{PLURAL:$1|simbolis|simboliai|simbolių}}.',
'yourgender'                    => 'Lytis:',
'gender-unknown'                => 'Nenurodyta',
'gender-male'                   => 'Vyras',
'gender-female'                 => 'Moteris',
'prefs-help-gender'             => 'Pasirinktinai: naudojama teisingam sistemos kreipimuisi į jus.
Ši informacija yra vieša.',
'email'                         => 'El. paštas',
'prefs-help-realname'           => 'Tikrasis vardas yra neprivalomas.
Jei jūs jį įvesite, jis bus naudojamas pažymėti jūsų darbą.',
'prefs-help-email'              => 'El. pašto adresas yra neprivalomas, bet jis leidžia jums gauti naują slaptažodį, jei jūs užmiršote koks jis buvo, o taip pat jūs galite leisti kitiems pasiekti jus per jūsų naudotojo ar naudotojo aptarimo puslapį neatskleidžiant jūsų tapatybės.',
'prefs-help-email-required'     => 'El. pašto adresas yra būtinas.',
'prefs-info'                    => 'Pagrindinė informacija',
'prefs-i18n'                    => 'Kalbos nustatymai',
'prefs-signature'               => 'Parašas',
'prefs-dateformat'              => 'Datos formatas',
'prefs-timeoffset'              => 'Laiko skirtumas',
'prefs-advancedediting'         => 'Papildomi nustatymai',
'prefs-advancedrc'              => 'Papildomi nustatymai',
'prefs-advancedrendering'       => 'Papildomi nustatymai',
'prefs-advancedsearchoptions'   => 'Papildomi nustatymai',
'prefs-advancedwatchlist'       => 'Papildomi nustatymai',
'prefs-displayrc'               => 'Rodymo nustatymai',
'prefs-displaysearchoptions'    => 'Rodymo nuostatos',
'prefs-displaywatchlist'        => 'Rodymo nuostatos',
'prefs-diffs'                   => 'Skirtumai',

# User rights
'userrights'                   => 'Naudotojų teisių valdymas',
'userrights-lookup-user'       => 'Tvarkyti naudotojo grupes',
'userrights-user-editname'     => 'Įveskite naudotojo vardą:',
'editusergroup'                => 'Redaguoti naudotojo grupes',
'editinguser'                  => "Taisomos naudotojo '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])
teisės",
'userrights-editusergroup'     => 'Redaguoti naudotojų grupes',
'saveusergroups'               => 'Saugoti naudotojų grupes',
'userrights-groupsmember'      => 'Narys:',
'userrights-groupsmember-auto' => 'Narys automatiškai:',
'userrights-groups-help'       => 'Jūs galite pakeisti grupes, kuriose yra šis naudotojas:
* Pažymėtas langelis reiškia, kad šis naudotojas yra toje grupėje.
* Nepažymėtas langelis reiškia, kad šis naudotojas nėra toje grupėje.
* * parodo, kad jūs nebegalėsite pašalinti grupės, kai ją pridėsite, ir atvirkščiai.',
'userrights-reason'            => 'Priežastis:',
'userrights-no-interwiki'      => 'Jūs neturite leidimo keisti naudotojų teises kituose projektuose.',
'userrights-nodatabase'        => 'Duomenų bazė $1 neegzistuoja arba yra ne vietinė.',
'userrights-nologin'           => 'Jūs privalote [[Special:UserLogin|prisijungti]] kaip administratorius, kad galėtumėte priskirti naudotojų teises.',
'userrights-notallowed'        => 'Jūsų paskyra neturi teisių priskirti naudotojų teises.',
'userrights-changeable-col'    => 'Grupės, kurias galite keisti',
'userrights-unchangeable-col'  => 'Grupės, kurių negalite keisti',

# Groups
'group'               => 'Grupė:',
'group-user'          => 'Naudotojai',
'group-autoconfirmed' => 'Automatiškai patvirtinti naudotojai',
'group-bot'           => 'Robotai',
'group-sysop'         => 'Administratoriai',
'group-bureaucrat'    => 'Biurokratai',
'group-suppress'      => 'Peržiūros',
'group-all'           => '(visi)',

'group-user-member'          => 'Naudotojas',
'group-autoconfirmed-member' => 'Automatiškai patvirtintas naudotojas',
'group-bot-member'           => 'Botas',
'group-sysop-member'         => 'Administratorius',
'group-bureaucrat-member'    => 'Biurokratas',
'group-suppress-member'      => 'Peržiūra',

'grouppage-user'          => '{{ns:project}}:Naudotojai',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatiškai patvirtinti naudotojai',
'grouppage-bot'           => '{{ns:project}}:Robotai',
'grouppage-sysop'         => '{{ns:project}}:Administratoriai',
'grouppage-bureaucrat'    => '{{ns:project}}:Biurokratai',
'grouppage-suppress'      => '{{ns:project}}:Peržiūra',

# Rights
'right-read'                  => 'Skaityti puslapius',
'right-edit'                  => 'Redaguoti puslapius',
'right-createpage'            => 'Kurti puslapius (kurie nėra aptarimų puslapiai)',
'right-createtalk'            => 'Kurti aptarimų puslapius',
'right-createaccount'         => 'Kurti naujas naudotojų paskyras',
'right-minoredit'             => 'Žymėti keitimus kaip smulkius',
'right-move'                  => 'Pervadinti puslapius',
'right-move-subpages'         => 'Perkelti puslapius su jų subpuslapiais',
'right-move-rootuserpages'    => 'Perkelti šakninius naudotojo puslapius',
'right-movefile'              => 'Perkelti failus',
'right-suppressredirect'      => 'Nekurti peradresavimo iš seno pavadinimo, kuomet puslapis pervadinamas',
'right-upload'                => 'Įkelti failus',
'right-reupload'              => 'Perrašyti egzistuojantį failą',
'right-reupload-own'          => 'Perrašyti paties įkeltą egzistuojantį failą',
'right-reupload-shared'       => 'Perrašyti failus bendrojoje failų saugykloje lokaliai',
'right-upload_by_url'         => 'Įkelti failą iš URL adreso',
'right-purge'                 => 'Išvalyti svetainės podėlį puslapiui be patvirtinimo',
'right-autoconfirmed'         => 'Redaguoti pusiau užrakintus puslapius',
'right-bot'                   => 'Laikyti automatiniu procesu',
'right-nominornewtalk'        => 'Atlikus smulkių keitimų aptarimų puslapiuose įjungia pranešimą apie naujas žinutes',
'right-apihighlimits'         => 'Mažesni apribojimai API užklausoms',
'right-writeapi'              => 'Naudoti rašymo API',
'right-delete'                => 'Trinti puslapius',
'right-bigdelete'             => 'Ištrinti puslapius su ilga istorija',
'right-deleterevision'        => 'Ištrinti ir atstatyti specifines puslapių versijas',
'right-deletedhistory'        => 'Žiūrėti ištrintų puslapių istoriją nerodant susieto teksto',
'right-deletedtext'           => 'Peržiūrėti ištrintą tekstą ir skirtumus tarp ištrintų puslapio versijų.',
'right-browsearchive'         => 'Ieškoti ištrintų puslapių',
'right-undelete'              => 'Atstatyti puslapį',
'right-suppressrevision'      => 'Peržiūrėti ir atstatyti versijas, paslėptas nuo administratorių',
'right-suppressionlog'        => 'Žiūrėti privačius įvykių sąrašus',
'right-block'                 => 'Blokuoti redagavimo galimybę kitiems naudotojams',
'right-blockemail'            => 'Blokuoti elektroninio pašto siuntimo galimybę naudotojui',
'right-hideuser'              => 'Blokuoti naudotojo vardą, paslepiant jį nuo viešinimo',
'right-ipblock-exempt'        => 'Apeiti IP blokavimus, autoblokavimus ir pakopinius blokavimus',
'right-proxyunbannable'       => 'Apeiti automatinius proxy serverių blokavimus',
'right-unblockself'           => 'Atblokuoti pačius',
'right-protect'               => 'Pakeisti apsaugos lygius ir redaguoti apsaugotus puslapius',
'right-editprotected'         => 'Redaguoti apsaugotus puslapius (be pakopinės apsaugos)',
'right-editinterface'         => 'Redaguoti naudotojo aplinką',
'right-editusercssjs'         => 'Redaguoti kitų naudotojų CSS ir JS failus',
'right-editusercss'           => 'Redaguoti kitų naudotojų CSS failus',
'right-edituserjs'            => 'Redaguoti kitų naudotojų JS failus',
'right-rollback'              => 'Greitai atmesti paskutinio naudotojo tam tikro puslapio pakeitimus',
'right-markbotedits'          => 'Žymėti atmestus keitimus kaip atliktus boto',
'right-noratelimit'           => 'Netaikyti greičio apribojimų',
'right-import'                => 'Importuoti puslapius iš kitų wiki',
'right-importupload'          => 'Puslapių importas per failų įkėlimą',
'right-patrol'                => 'Pažymėti kitų keitimus kaip patikrintus',
'right-autopatrol'            => 'Keitimai automatiškai pažymimi kaip patikrinti',
'right-patrolmarks'           => 'Atžymų apie patikrą peržiūra naujausiuose keitimuose',
'right-unwatchedpages'        => 'Žiūrėti nestebimų puslapių sąrašą',
'right-trackback'             => 'Siųsti „Trackback“ nuorodą',
'right-mergehistory'          => 'Sulieti puslapių istorijas',
'right-userrights'            => 'Redaguoti visų naudotojų teises',
'right-userrights-interwiki'  => 'Keisti naudotojų teises kitose wiki svetainėse',
'right-siteadmin'             => 'Atrakinti ir užrakinti duomenų bazę',
'right-reset-passwords'       => 'Atstatyti kitų naudotojų slaptažodžius',
'right-override-export-depth' => 'Eksportuoti puslapius įtraukiant susietus puslapius iki 5 lygio gylio',
'right-sendemail'             => 'Siųsti el. laišką kitiems naudotojams',
'right-revisionmove'          => 'Perkelti versijas',

# User rights log
'rightslog'      => 'Naudotojų teisių istorija',
'rightslogtext'  => 'Pateikiamas naudotojų teisių pakeitimų sąrašas.',
'rightslogentry' => 'pakeista $1 grupės narystė iš $2 į $3',
'rightsnone'     => '(jokių)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'skaityti šį puslapį',
'action-edit'                 => 'redaguoti šį puslapį',
'action-createpage'           => 'kurti puslapius',
'action-createtalk'           => 'kurti aptarimų puslapius',
'action-createaccount'        => 'kurti šią naudotojo paskyrą',
'action-minoredit'            => 'žymėti keitimą kaip smulkų',
'action-move'                 => 'pervadinti šį puslapį',
'action-move-subpages'        => 'pervadinti šį puslapį ir jo subpuslapius',
'action-move-rootuserpages'   => 'perkelti pagrindinius naudotojų puslapius',
'action-movefile'             => 'perkelti šį failą',
'action-upload'               => 'įkelti šį failą',
'action-reupload'             => 'perrašyti šį esamą failą',
'action-reupload-shared'      => 'perrašyti šį failą bendrojoje saugykloje',
'action-upload_by_url'        => 'įkelti šį failą iš URL adreso',
'action-writeapi'             => 'naudotis rašymo API',
'action-delete'               => 'ištrinti šį puslapį',
'action-deleterevision'       => 'ištrinti šią reviziją',
'action-deletedhistory'       => 'žiūrėti šio ištrinto puslapio istoriją',
'action-browsearchive'        => 'ieškoti ištrintų puslapių',
'action-undelete'             => 'atkurti šį puslapį',
'action-suppressrevision'     => 'peržiūrėti ir atstatyti šią paslėptą reviziją',
'action-suppressionlog'       => 'peržiūrėti šį privatų registrą',
'action-block'                => 'neleisti šiam naudotojui redaguoti',
'action-protect'              => 'pakeisti apsaugos lygius šiam puslapiui',
'action-import'               => 'importuoti šį puslapį iš kitos wiki',
'action-importupload'         => 'importuoti šį puslapį iš įkelto failo',
'action-patrol'               => 'pažymėti kitų keitimus kaip patikrintus',
'action-autopatrol'           => 'savo keitimų pažymėjimas patikrintais',
'action-unwatchedpages'       => 'žiūrėti nestebimų puslapių sąrašą',
'action-trackback'            => 'priimti trackback nuorodą',
'action-mergehistory'         => 'sulieti šio puslapio istoriją',
'action-userrights'           => 'keisti visų naudotojų teises',
'action-userrights-interwiki' => 'keisti naudotojų teises kitose wiki svetainėse',
'action-siteadmin'            => 'užrakinti ar atrakinti duomenų bazę',
'action-revisionmove'         => 'perkelti versijas',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pakeitimas|pakeitimai|pakeitimų}}',
'recentchanges'                     => 'Paskutiniai keitimai',
'recentchanges-legend'              => 'Naujausių keitimų parinktys',
'recentchangestext'                 => 'Šiame puslapyje yra patys naujausi pakeitimai šiame projekte.',
'recentchanges-feed-description'    => 'Sekite pačius naujausius projekto keitimus šiame šaltinyje.',
'recentchanges-label-newpage'       => 'Šiuo keitimu sukurtas naujas puslapis',
'recentchanges-label-minor'         => 'Tai smulkus pakeitimas',
'recentchanges-label-bot'           => 'Šį keitimą atliko automatinė programa',
'recentchanges-label-unpatrolled'   => 'Šis keitimas dar nebuvo patikrintas',
'rcnote'                            => "Žemiau yra {{PLURAL:$1|'''1''' pakeitimas|paskutiniai '''$1''' pakeitimai|paskutinių '''$1''' pakeitimų}} per {{PLURAL:$2|dieną|paskutiniąsias '''$2''' dienas|paskutiniųjų '''$2''' dienų}} skaičiuojant nuo $5, $4.",
'rcnotefrom'                        => "Žemiau yra pakeitimai pradedant '''$2''' (rodoma iki '''$1''' pakeitimų).",
'rclistfrom'                        => 'Rodyti naujus pakeitimus pradedant $1',
'rcshowhideminor'                   => '$1 smulkius keitimus',
'rcshowhidebots'                    => '$1 robotus',
'rcshowhideliu'                     => '$1 prisijungusius naudotojus',
'rcshowhideanons'                   => '$1 anoniminius naudotojus',
'rcshowhidepatr'                    => '$1 patikrintus keitimus',
'rcshowhidemine'                    => '$1 mano keitimus',
'rclinks'                           => 'Rodyti paskutinius $1 pakeitimų per paskutiniąsias $2 dienų<br />$3',
'diff'                              => 'skirt',
'hist'                              => 'ist',
'hide'                              => 'Slėpti',
'show'                              => 'Rodyti',
'minoreditletter'                   => 'S',
'newpageletter'                     => 'N',
'boteditletter'                     => 'R',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|stebintis naudotojas|stebintys naudotojai|stebinčių naudotojų}}]',
'rc_categories'                     => 'Rodyti tik šias kategorijas (atskirkite naudodami „|“)',
'rc_categories_any'                 => 'Bet kokia',
'newsectionsummary'                 => '/* $1 */ naujas skyrius',
'rc-enhanced-expand'                => 'Rodyti detales (reikia JavaScript)',
'rc-enhanced-hide'                  => 'Slėpti detales',

# Recent changes linked
'recentchangeslinked'          => 'Susiję keitimai',
'recentchangeslinked-feed'     => 'Susiję keitimai',
'recentchangeslinked-toolbox'  => 'Susiję keitimai',
'recentchangeslinked-title'    => 'Su „$1“ susiję keitimai',
'recentchangeslinked-noresult' => 'Nėra jokių pakeitimų susietuose puslapiuose duotu periodu.',
'recentchangeslinked-summary'  => "Tai paskutinių keitimų, atliktų puslapiuose, į kuriuos yra nuoroda iš nurodyto puslapio (arba į nurodytos kategorijos narius), sąrašas.
Puslapiai iš jūsų [[Special:Watchlist|stebimųjų sąrašo]] yra '''paryškinti'''.",
'recentchangeslinked-page'     => 'Puslapio pavadinimas:',
'recentchangeslinked-to'       => 'Rodyti su duotuoju puslapiu susijusių puslapių pakeitimus',

# Upload
'upload'                      => 'Įkelti failą',
'uploadbtn'                   => 'Įkelti failą',
'reuploaddesc'                => 'Atšaukti įkėlimą ir grįžti į įkėlimo formą.',
'upload-tryagain'             => 'Siųsti pakeistą failo aprašymą',
'uploadnologin'               => 'Neprisijungęs',
'uploadnologintext'           => 'Norėdami įkelti failą, turite būti [[Special:UserLogin|prisijungęs]].',
'upload_directory_missing'    => 'Nėra įkėlimo aplanko ($1) ir negali būti sukurtas tinklo serverio.',
'upload_directory_read_only'  => 'Tinklapio serveris negali rašyti į įkėlimo aplanką ($1).',
'uploaderror'                 => 'Įkėlimo klaida',
'upload-recreate-warning'     => "'''Dėmėsio: Failas šiuo pavadinimu buvo ištrintas arba pervadintas.'''

Jūsų patogumui pateiktas įrašas apie šio puslapio trynimą ar pervadinimą:",
'uploadtext'                  => "Naudokitės žemiau pateikta forma failų įkėlimui.
Norėdami peržiūrėti ar ieškoti anksčiau įkeltų paveikslėlių, eikite į [[Special:FileList|įkeltų failų sąrašą]], įkėlimai yra registruojami [[Special:Log/upload|įkėlimų istorijoje]], trynimai — [[Special:Log/delete|trynimų istorijoje]].

Norėdami panaudoti įkeltą failą puslapyje, naudokite tokias nuorodas:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Failas.jpg]]</nowiki></tt>''' norėdami naudoti pilną failo versiją
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Failas.png|200px|thumb|left|alternatyvusis tekstas]]</nowiki></tt>''' norėdami naudoti 200 pikselių pločio paveikslėlį rėmelyje puslapio kairėje; „alternatyvus tekstas“ bus naudojamas paveikslėlio aprašymui.
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Failas.ogg]]</nowiki></tt>''' tiesioginei nuorodai į failą.",
'upload-permitted'            => 'Leidžiami failų tipai: $1.',
'upload-preferred'            => 'Pageidautini failų tipai: $1.',
'upload-prohibited'           => 'Uždrausti failų tipai: $1.',
'uploadlog'                   => 'įkėlimų istorija',
'uploadlogpage'               => 'Įkėlimų istorija',
'uploadlogpagetext'           => 'Žemiau pateikiamas paskutinių failų įkėlimų istorija.',
'filename'                    => 'Failo vardas',
'filedesc'                    => 'Aprašymas',
'fileuploadsummary'           => 'Komentaras:',
'filereuploadsummary'         => 'Failo pakeitimai:',
'filestatus'                  => 'Autorystės teisės:',
'filesource'                  => 'Šaltinis:',
'uploadedfiles'               => 'Įkelti failai',
'ignorewarning'               => 'Ignoruoti įspėjimą ir išsaugoti failą vistiek.',
'ignorewarnings'              => 'Ignuoruoti bet kokius įspėjimus',
'minlength1'                  => 'Failo pavadinimas turi būti bent viena raidė.',
'illegalfilename'             => 'Failo varde „$1“ yra simbolių, neleidžiamų puslapio pavadinimuose. Prašome pervadint failą ir mėginkite įkelti jį iš naujo.',
'badfilename'                 => 'Failo pavadinimas pakeistas į „$1“.',
'filetype-mime-mismatch'      => 'Failo plėtinys neatitinka MIME tipo.',
'filetype-badmime'            => 'Neleidžiama įkelti „$1“ MIME tipo failų.',
'filetype-bad-ie-mime'        => 'Negalima įkelti šio failo, kadangi Internet Explorer jį pažymėtų kaip „$1“. Tai yra neleistinas ir potencialiai pavojingas failo tipas.',
'filetype-unwanted-type'      => "„.$1“''' yra nepageidautinas failo tipas. {{PLURAL:$3|Pageidautinas failų tipas|pageidautini failų tipai}} yra $2.",
'filetype-banned-type'        => "„.$1“''' nėra leistinas failo tipas. {{PLURAL:$3|Leistinas failų tipas|Leistini failų tipai}} yra $2.",
'filetype-missing'            => 'Failas neturi galūnės (pavyzdžiui „.jpg“).',
'empty-file'                  => 'Failas, kurį patvirtinote, tuščias.',
'file-too-large'              => 'Failas, kurį patvirtinote, pernelyg didelis.',
'filename-tooshort'           => 'Failo pavadinimas per trumpas.',
'filetype-banned'             => 'Šis failo tipas yra uždraustas.',
'verification-error'          => 'Šis failas nepraėjo patikrinimo.',
'hookaborted'                 => 'Pakeitimą, kurį bandėte atlikti, nutraukė priedo gaudlys.',
'illegal-filename'            => 'Failo vardas neleidžiamas.',
'overwrite'                   => 'Perrašyti esamą failą neleidžiama.',
'unknown-error'               => 'Įvyko nežinoma klaida.',
'tmp-create-error'            => 'Nepavyko sukurti laikino failo.',
'tmp-write-error'             => 'Klaida rašant laikinąjį failą.',
'large-file'                  => 'Rekomenduojama, kad failų dydis būtų nedidesnis nei $1; šio failo dydis yra $2.',
'largefileserver'             => 'Šis failas yra didesnis nei serveris yra sukonfigūruotas leisti.',
'emptyfile'                   => 'Panašu, kad failas, kurį įkėlėte yra tuščias. Tai gali būti dėl klaidos failo pavadinime. Pasitikrinkite ar tikrai norite įkelti šitą failą.',
'fileexists'                  => "Failas tuo pačiu vardu jau egzistuoja, prašome pažiūrėti '''<tt>[[:$1]]</tt>''', jei nesate tikras, ar norite perrašyti šį failą.
[[$1|thumb]]",
'filepageexists'              => "Šio failo aprašymo puslapis jau buvo sukurtas '''<tt>[[:$1]]</tt>''', bet šiuo metu nėra jokio failo šiuo pavadinimu.
Jūsų įvestas komentaras neatsiras aprašymo puslapyje.
Jei norite, kad jūsų komentaras ten atsirastų, jums reikia jį pakeisti pačiam.
[[$1|thumb]]",
'fileexists-extension'        => "Failas su panašiu pavadinimu jau yra: [[$2|thumb]]
* Įkeliamo failo pavadinimas: '''<tt>[[:$1]]</tt>'''
* Jau esančio failo pavadinimas: '''<tt>[[:$2]]</tt>'''
Prašome pasirinkti kitą vardą.",
'fileexists-thumbnail-yes'    => "Failas turbūt yra sumažinto dydžio failas ''(miniatiūra)''. [[$1|thumb]]
Prašome peržiūrėti failą '''<tt>[[:$1]]</tt>'''.
Jeigu tai yra toks pats pradinio dydžio paveikslėlis, tai įkelti papildomos miniatūros nereikia.",
'file-thumbnail-no'           => "Failo pavadinimas prasideda  '''<tt>$1</tt>'''.
Atrodo, kad yra sumažinto dydžio paveikslėlis ''(miniatiūra)''.
Jei jūs turite šį paveisklėlį pilna raiška, įkelkite šitą, priešingu atveju prašome pakeisti failo pavadinimą.",
'fileexists-forbidden'        => 'Failas tokiu pačiu vardu jau egzistuoja ir negali būti perrašytas;
prašome eiti atgal ir įkelti šį failą kitu vardu. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Failas tokiu vardu jau egzistuoja bendrojoje failų saugykloje;
Jei visvien norite įkelti savo failą, prašome eiti atgal ir įkelti šį failą kitu vardu. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Šis failas yra {{PLURAL:$1|šio failo|šių failų}} dublikatas:',
'file-deleted-duplicate'      => 'Failas, identiškas šiam failui ([[:$1]]), seniau buvo ištrintas. Prieš įkeldami jį vėl patikrinkite šio failo ištrynimo istoriją.',
'uploadwarning'               => 'Dėmesio',
'uploadwarning-text'          => 'Prašome pakeisti failo aprašymą ir bandykite dar kartą.',
'savefile'                    => 'Išsaugoti failą',
'uploadedimage'               => 'įkėlė „[[$1]]“',
'overwroteimage'              => 'įkėlė naują „[[$1]]“ versiją',
'uploaddisabled'              => 'Įkėlimai uždrausti',
'copyuploaddisabled'          => 'Įkėlimas pagal URL išjungtas.',
'uploadfromurl-queued'        => 'Jūsų įkėlimas įtrauktas į eilę.',
'uploaddisabledtext'          => 'Failų įkėlimai yra uždrausti.',
'php-uploaddisabledtext'      => "Failų įkėlimai uždrausti PHP nustatymuose.
Patikrinkite ''file_uploads'' nustatą.",
'uploadscripted'              => 'Šis failas turi HTML arba programinį kodą, kuris gali būti klaidingai suprastas interneto naršyklės.',
'uploadvirus'                 => 'Šiame faile yra virusas! Smulkiau: $1',
'upload-source'               => 'Failo šaltinis',
'sourcefilename'              => 'Įkeliamas failas:',
'sourceurl'                   => 'Šaltinio adresas:',
'destfilename'                => 'Norimas failo vardas:',
'upload-maxfilesize'          => 'Didžiausias failo dydis: $1',
'upload-description'          => 'Failo aprašymas',
'upload-options'              => 'Įkėlimo nustatymai',
'watchthisupload'             => 'Stebėti šį failą',
'filewasdeleted'              => 'Failas šiuo vardu anksčiau buvo įkeltas, o paskui ištrintas. Jums reikėtų patikrinti $1 prieš bandant įkelti jį vėl.',
'upload-wasdeleted'           => "'''Įspėjimas: Jūs įkeliate failą, kuris anksčiau buvo ištrintas.'''

Jūs turėtumėte nuspręsti, ar verta toliau įkeldinėti šį failą.
Šio failo šalinimų istorija yra pateikta dėl patogumo:",
'filename-bad-prefix'         => "Jūsų įkeliamas failas prasideda su '''„$1“''', bet tai yra neapibūdinantis pavadinimas, dažniausiai priskirtas skaitmeninių kamerų. Prašome suteikti labiau apibūdinantį pavadinimą savo failui.",
'upload-success-subj'         => 'Įkelta sėkmingai',
'upload-success-msg'          => 'Jūsų įkėlimas iš [$2] buvo sėkmingas. Jį galima rasti čia: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Įkėlimo problema',
'upload-failure-msg'          => 'Įvyko įkėlimo iš [$2] problema:

$1',
'upload-warning-subj'         => 'Įkėlimo įspėjimas',
'upload-warning-msg'          => 'Įvyko įkėlimo iš [$2] problema. Jums reikėtų grįžti į [[Special:Upload/stash/$1|įkėlimo formą]], norint išspręsti šią problemą.',

'upload-proto-error'        => 'Neteisingas protokolas',
'upload-proto-error-text'   => 'Nuotoliniai įkėlimas reikalauja, kad URL prasidėtų <code>http://</code> arba <code>ftp://</code>.',
'upload-file-error'         => 'Vidinė klaida',
'upload-file-error-text'    => 'Įvyko vidinė klaida bandant sukurti laikinąjį failą serveryje.
Prašome susisiekti su [[Special:ListUsers/sysop|sistemos administratoriumi]].',
'upload-misc-error'         => 'Nežinoma įkėlimo klaida',
'upload-misc-error-text'    => 'Įvyko nežinoma klaida vykstant įkėlimui. Prašome patikrinti, kad URL teisingas bei pasiekiamas ir pamėginkite vėl. Jei problema lieka, susisiekite su sistemos administratoriumi.',
'upload-too-many-redirects' => 'URL yra per daug kartų peradresuotas',
'upload-unknown-size'       => 'Nežinomas dydis',
'upload-http-error'         => 'Įvyko HTTP klaida: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Prieiga uždrausta',
'img-auth-nopathinfo'   => 'Trūksta PATH_INFO.
Jūsų serveris nenustatytas perduoti šią informaciją.
Tai gali būti CGI paremta ir negali palaikyti img_auth.
Daugiau informacijos http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Užklaustas kelias nėra sukonfigūruotame įkėlimo kataloge.',
'img-auth-badtitle'     => 'Nepavyksta padaryti leistino pavadinimo iš „$1“.',
'img-auth-nologinnWL'   => 'Jūs nesate prisijungęs ir „$1“ nėra baltajame sąraše.',
'img-auth-nofile'       => 'Failas „$1“ neegzistuoja.',
'img-auth-isdir'        => 'Jūs bandote pasiekti katalogą „$1“.
Leidžiama prieiga tik prie failų.',
'img-auth-streaming'    => 'Siunčiamas „$1“.',
'img-auth-public'       => 'img_auth.php paskirtis yra pateikti failus iš privačių projektų.
Šis projektas sukonfigūruotas kaip viešasis.
Dėl saugumo, img_auth.php yra išjungtas.',
'img-auth-noread'       => 'Naudotojas neturi teisės peržiūrėti „$1“.',

# HTTP errors
'http-invalid-url'      => 'Neleistinas URL: $1',
'http-invalid-scheme'   => 'URL su priedėliu „$1“ nepalaikomi.',
'http-request-error'    => 'HTTP užklausa nepavyko dėl nežinomos klaidos.',
'http-read-error'       => 'HTTP skaitymo klaida.',
'http-timed-out'        => 'HTTP užklausos laikas baigėsi.',
'http-curl-error'       => 'Klaida siunčiantis URL: $1',
'http-host-unreachable' => 'Nepavyko pasiekti URL.',
'http-bad-status'       => 'Iškilo problemų vykdant HTTP užklausą: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Nepavyksta pasiekti URL',
'upload-curl-error6-text'  => 'Pateiktas URL negali būti pasiektas. Prašome patikrinti, kad URL yra teisingas ir svetainė veikia.',
'upload-curl-error28'      => 'Per ilgai įkeliama',
'upload-curl-error28-text' => 'Atsakant svetainė užtrunka per ilgai. Patikrinkite, ar svetainė veikia, palaukite truputį ir vėl pamėginkite. Galbūt jums reikėtų pamėginti ne tokiu apkrautu metu.',

'license'            => 'Licencija:',
'license-header'     => 'Licensija',
'nolicense'          => 'Nepasirinkta',
'license-nopreview'  => '(Peržiūra negalima)',
'upload_source_url'  => ' (tikras, viešai prieinamas URL)',
'upload_source_file' => ' (failas jūsų kompiuteryje)',

# Special:ListFiles
'listfiles-summary'     => 'Šis specialus puslapis rodo visus įkeltus failus.
Pagal numatymą paskutiniai įkelti failai rodomi sąrašo viršuje.
Paspaudę ant stulpelio antraštės pakeiste išrikiavimą.',
'listfiles_search_for'  => 'Ieškoti failo pavadinimo:',
'imgfile'               => 'failas',
'listfiles'             => 'Failų sąrašas',
'listfiles_thumb'       => 'Miniatiūra',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Pavadinimas',
'listfiles_user'        => 'Naudotojas',
'listfiles_size'        => 'Dydis',
'listfiles_description' => 'Aprašymas',
'listfiles_count'       => 'Versijos',

# File description page
'file-anchor-link'                  => 'Failas',
'filehist'                          => 'Paveikslėlio istorija',
'filehist-help'                     => 'Paspauskite ant datos/laiko, kad pamatytumėte failą tokį, koks jis buvo tuo metu.',
'filehist-deleteall'                => 'trinti visus',
'filehist-deleteone'                => 'trinti',
'filehist-revert'                   => 'grąžinti',
'filehist-current'                  => 'dabartinis',
'filehist-datetime'                 => 'Data/Laikas',
'filehist-thumb'                    => 'Miniatiūra',
'filehist-thumbtext'                => 'Versijos $1 miniatiūra',
'filehist-nothumb'                  => 'Nėra miniatiūros',
'filehist-user'                     => 'Naudotojas',
'filehist-dimensions'               => 'Matmenys',
'filehist-filesize'                 => 'Failo dydis',
'filehist-comment'                  => 'Komentaras',
'filehist-missing'                  => 'Failo nėra',
'imagelinks'                        => 'Failų nuorodos',
'linkstoimage'                      => '{{PLURAL:$1|Šis puslapis|Šie puslapiai}} nurodo į šį failą:',
'linkstoimage-more'                 => 'Daugiau nei $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}} rodo į šį failą.
Šis sąrašas rodo tik {{PLURAL:$1|puslapio|pirmų $1 puslapių}} nuorodas į šį failą.
Yra pasiekiamas ir [[Special:WhatLinksHere/$2|visas sąrašas]].',
'nolinkstoimage'                    => 'Į failą nenurodo joks puslapis.',
'morelinkstoimage'                  => 'Žiūrėti [[Special:WhatLinksHere/$1|daugiau nuorodų]] į šį failą.',
'redirectstofile'                   => '{{PLURAL:$1|Šis failas|$1 failai}} peradresuoja į šį failą:',
'duplicatesoffile'                  => 'Šis failas turi {{PLURAL:$1|$1 dublikatą|$1 dublikatus|$1 dublikatų}} ([[Special:FileDuplicateSearch/$2|daugiau informacijos]]):',
'sharedupload'                      => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.',
'sharedupload-desc-there'           => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.
Norėdami sužinoti daugiau, žiūrėkite [$2 failo aprašymą].',
'sharedupload-desc-here'            => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.
Informacija iš [$2 failo aprašymo puslapio] yra pateikiama žemiau.',
'filepage-nofile'                   => 'Joks failas su duotu pavadinimu neegzistuoja.',
'filepage-nofile-link'              => 'Joks failas su duotu pavadinimu neegzistuoja, bet vis dar galite [$1 jį įkelti].',
'uploadnewversion-linktext'         => 'Įkelti naują failo versiją',
'shared-repo-from'                  => 'iš $1',
'shared-repo'                       => 'bendrosios failų saugyklos',
'shared-repo-name-wikimediacommons' => 'Vikiteka',

# File reversion
'filerevert'                => 'Sugrąžinti $1',
'filerevert-legend'         => 'Failo sugrąžinimas',
'filerevert-intro'          => '<span class="plainlinks">Jūs grąžinate \'\'\'[[Media:$1|$1]]\'\'\' į versiją $4 ($2, $3).</span>',
'filerevert-comment'        => 'Priežastis:',
'filerevert-defaultcomment' => 'Grąžinta į $1, $2 versiją',
'filerevert-submit'         => 'Grąžinti',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' buvo sugrąžintas į versiją $4 ($2, $3).</span>',
'filerevert-badversion'     => 'Nėra jokių ankstesnių vietinių šio failo versijų su pateiktu laiku.',

# File deletion
'filedelete'                  => 'Trinti $1',
'filedelete-legend'           => 'Trinti failą',
'filedelete-intro'            => "Jūs ketinate ištrinti failą '''[[Media:$1|$1]]''' su visa istorija.",
'filedelete-intro-old'        => '<span class="plainlinks">Jūs trinate \'\'\'[[Media:$1|$1]]\'\'\' [$4 $3, $2] versiją.</span>',
'filedelete-comment'          => 'Priežastis:',
'filedelete-submit'           => 'Trinti',
'filedelete-success'          => "'''$1''' buvo ištrintas.",
'filedelete-success-old'      => "'''[[Media:$1|$1]]''' $3, $2 versija buvo ištrinta.",
'filedelete-nofile'           => "'''$1''' neegzistuoja.",
'filedelete-nofile-old'       => "Nėra jokios '''$1''' suarchyvuotos versijos su nurodytais atributais.",
'filedelete-otherreason'      => 'Kita/papildoma priežastis:',
'filedelete-reason-otherlist' => 'Kita priežastis',
'filedelete-reason-dropdown'  => '*Dažnos trynimo priežastys
** Autorystės teisių pažeidimai
** Pasikartojantis failas',
'filedelete-edit-reasonlist'  => 'Keisti trynimo priežastis',
'filedelete-maintenance'      => 'Failų trynimas ir atkūrimas laikinai išjungtas dėl profilaktikos.',

# MIME search
'mimesearch'         => 'MIME paieška',
'mimesearch-summary' => 'Šis puslapis leidžia rodyti failus pagal jų MIME tipą. Įveskite: turiniotipas/potipis, pvz. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tipas:',
'download'           => 'parsisiųsti',

# Unwatched pages
'unwatchedpages' => 'Nestebimi puslapiai',

# List redirects
'listredirects' => 'Peradresavimų sąrašas',

# Unused templates
'unusedtemplates'     => 'Nenaudojami šablonai',
'unusedtemplatestext' => 'Šis puslapis rodo sąrašą puslapių, esančių {{ns:template}} vardų srityje, kurie nėra įterpti į jokį kitą puslapį. Nepamirškite patikrinti kitų nuorodų prieš juos ištrinant.',
'unusedtemplateswlh'  => 'kitos nuorodos',

# Random page
'randompage'         => 'Atsitiktinis puslapis',
'randompage-nopages' => '{{PLURAL:$2|Šioje vardų srityje|Šiose vardų srityse}} nėra jokių puslapių: $1.',

# Random redirect
'randomredirect'         => 'Atsitiktinis peradresavimas',
'randomredirect-nopages' => 'Vardų srityje „$1“ nėra jokių peradresavimų.',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Puslapių statistika',
'statistics-header-edits'      => 'Redagavimų statistika',
'statistics-header-views'      => 'Peržiūrų statistika',
'statistics-header-users'      => 'Naudotojų statistika',
'statistics-header-hooks'      => 'Kita statistika',
'statistics-articles'          => 'Turinio puslapiai',
'statistics-pages'             => 'Puslapiai',
'statistics-pages-desc'        => 'Visi puslapiai, tarp jų aptarimo, nukreipimų, ir kiti puslapiai.',
'statistics-files'             => 'Įkelti failai',
'statistics-edits'             => 'Puslapių redagavimų skaičius nuo {{SITENAME}} sukūrimo',
'statistics-edits-average'     => 'Vidutinis redakcijų kiekis puslapiui',
'statistics-views-total'       => 'Iš viso peržiūrų',
'statistics-views-total-desc'  => 'Neegzistuojančių ir specialiųjų puslapių parodymai neįtraukti',
'statistics-views-peredit'     => 'Peržiūrų skaičius redakcijai',
'statistics-users'             => 'Registruotų [[Special:ListUsers|naudotojų]]',
'statistics-users-active'      => 'Aktyvių naudotojų',
'statistics-users-active-desc' => 'Naudotojai, kurie per {{PLURAL:$1|paskutinę dieną|paskutines $1 dienų}} padarė keitimų',
'statistics-mostpopular'       => 'Daugiausiai rodyti puslapiai',

'disambiguations'      => 'Daugiaprasmių žodžių puslapiai',
'disambiguationspage'  => 'Template:Daugiareikšmis',
'disambiguations-text' => "Žemiau išvardinti puslapiai nurodo į '''daugiaprasmių žodžių puslapius'''.
Nuorodos turėtų būti patikslintos, kad rodytų į konkretų puslapį.<br />
Puslapis laikomas daugiaprasmiu puslapiu, jei jis naudoja šabloną, kuris yra nurodomas iš [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Dvigubi peradresavimai',
'doubleredirectstext'        => 'Šiame puslapyje yra puslapių, kurie nukreipia į kitus peradresavimo puslapius, sąrašas.
Kiekvienoje eilutėje yra nuorodos į pirmąjį ir antrąjį peradresavimą, taip pat antrojo peradresavimo paskirtis, kuris paprastai yra „tikrasis“ paskirties puslapis, į kurį pirmasis peradresavimas ir turėtų rodyti.
<del>Išbraukti</del> įrašai yra išspręsti.',
'double-redirect-fixed-move' => '[[$1]] buvo perkeltas, dabar tai peradresavimas į [[$2]]',
'double-redirect-fixer'      => 'Peradresavimų tvarkyklė',

'brokenredirects'        => 'Peradresavimai į niekur',
'brokenredirectstext'    => 'Šie peradresavimo puslapiai nurodo į neegzistuojančius puslapius:',
'brokenredirects-edit'   => 'redaguoti',
'brokenredirects-delete' => 'trinti',

'withoutinterwiki'         => 'Puslapiai be kalbų nuorodų',
'withoutinterwiki-summary' => 'Šie puslapiai nenurodo į kitų kalbų versijas:',
'withoutinterwiki-legend'  => 'Priešdėlis',
'withoutinterwiki-submit'  => 'Rodyti',

'fewestrevisions' => 'Puslapiai su mažiausiai keitimų',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|baitas|baitai|baitų}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategorijos|kategorijų}}',
'nlinks'                  => '$1 {{PLURAL:$1|nuoroda|nuorodos|nuorodų}}',
'nmembers'                => '$1 {{PLURAL:$1|narys|nariai|narių}}',
'nrevisions'              => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'nviews'                  => '$1 {{PLURAL:$1|parodymas|parodymai|parodymų}}',
'nimagelinks'             => 'Naudojama $1 {{PLURAL:$1|puslapyje|puslapiuose|puslapių}}',
'ntransclusions'          => 'naudojama $1 {{PLURAL:$1|puslapyje|puslapiuose|puslapių}}',
'specialpage-empty'       => 'Šiai ataskaitai nėra rezultatų.',
'lonelypages'             => 'Vieniši puslapiai',
'lonelypagestext'         => 'Į šiuos puslapius nėra nuorodų ar įtraukimų iš kitų {{SITENAME}} puslapių.',
'uncategorizedpages'      => 'Puslapiai, nepriskirti jokiai kategorijai',
'uncategorizedcategories' => 'Kategorijos, nepriskirtos jokiai kategorijai',
'uncategorizedimages'     => 'Failai, nepriskirti jokiai kategorijai',
'uncategorizedtemplates'  => 'Šablonai, nepriskirti jokiai kategorijai',
'unusedcategories'        => 'Nenaudojamos kategorijos',
'unusedimages'            => 'Nenaudojami failai',
'popularpages'            => 'Populiarūs puslapiai',
'wantedcategories'        => 'Geidžiamiausios kategorijos',
'wantedpages'             => 'Geidžiamiausi puslapiai',
'wantedpages-badtitle'    => 'Neleistinas pavadinimas rezultatų rinkinyje: $1',
'wantedfiles'             => 'Trokštami failai',
'wantedtemplates'         => 'Trokštami šablonai',
'mostlinked'              => 'Daugiausiai nurodomi puslapiai',
'mostlinkedcategories'    => 'Daugiausiai nurodomos kategorijos',
'mostlinkedtemplates'     => 'Daugiausiai nurodomi šablonai',
'mostcategories'          => 'Puslapiai su daugiausiai kategorijų',
'mostimages'              => 'Daugiausiai nurodomi failai',
'mostrevisions'           => 'Puslapiai su daugiausiai keitimų',
'prefixindex'             => 'Visi puslapiai pagal pavadinimo pradžią',
'shortpages'              => 'Trumpiausi puslapiai',
'longpages'               => 'Ilgiausi puslapiai',
'deadendpages'            => 'Puslapiai-aklavietės',
'deadendpagestext'        => 'Šie puslapiai neturi nuorodų į kitus puslapius šiame projekte.',
'protectedpages'          => 'Užrakinti puslapiai',
'protectedpages-indef'    => 'Tik neapibrėžtos apsaugos',
'protectedpages-cascade'  => 'Tik pakopinė apsauga',
'protectedpagestext'      => 'Šie puslapiai yra apsaugoti nuo perkėlimo ar redagavimo',
'protectedpagesempty'     => 'Šiuo metu nėra apsaugotas joks failas su šiais parametrais.',
'protectedtitles'         => 'Apsaugoti pavadinimai',
'protectedtitlestext'     => 'Šie pavadinimai yra apsaugoti nuo sukūrimo',
'protectedtitlesempty'    => 'Šiuo metu nėra jokių pavadinimų apsaugotų šiais parametrais.',
'listusers'               => 'Naudotojų sąrašas',
'listusers-editsonly'     => 'Rodyti tik keitimus atlikusius naudotojus',
'listusers-creationsort'  => 'Rodyti pagal paskyros sukūrimo datą',
'usereditcount'           => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'usercreated'             => 'Paskyra sukurta $1 $2',
'newpages'                => 'Naujausi puslapiai',
'newpages-username'       => 'Naudotojo vardas:',
'ancientpages'            => 'Seniausi puslapiai',
'move'                    => 'Pervadinti',
'movethispage'            => 'Pervadinti šį puslapį',
'unusedimagestext'        => 'Šie failai yra, bet jie neįtraukti į jokį kitą puslapį.
Primename, kad kitos svetainės gali turėti tiesioginę nuorodą į failą, bet vis tiek gali būti šiame sąraše, nors ir yra aktyviai naudojamas.',
'unusedcategoriestext'    => 'Šie kategorijų puslapiai sukurti, nors joks kitas puslapis ar kategorija jo nenaudoja.',
'notargettitle'           => 'Nenurodytas objektas',
'notargettext'            => 'Jūs nenurodėte norimo puslapio ar naudotojo, kuriam įvykdyti šią funkciją.',
'nopagetitle'             => 'Nėra puslapio tokiu adresu',
'nopagetext'              => 'Adresas, kurį nurodėte, neegzistuoja.',
'pager-newer-n'           => '$1 {{PLURAL:$1|naujesnis|naujesni|naujesnių}}',
'pager-older-n'           => '$1 {{PLURAL:$1|senesnis|senesni|senesnių}}',
'suppress'                => 'Peržiūra',

# Book sources
'booksources'               => 'Knygų šaltiniai',
'booksources-search-legend' => 'Knygų šaltinių paieška',
'booksources-go'            => 'Rodyti',
'booksources-text'          => 'Žemiau yra nuorodų sąrašas į kitas svetaines, kurios parduoda naujas ar naudotas knygas, bei galbūt turinčias daugiau informacijos apie knygas, kurių ieškote:',
'booksources-invalid-isbn'  => 'Duotas ISBN atrodo neteisingas; patikrinkite, ar nepadarėte kopijavimo klaidų.',

# Special:Log
'specialloguserlabel'  => 'Naudotojas:',
'speciallogtitlelabel' => 'Pavadinimas:',
'log'                  => 'Specialiųjų veiksmų istorija',
'all-logs-page'        => 'Visos viešosios istorijos',
'alllogstext'          => 'Bendras visų galimų „{{SITENAME}}“ specialiųjų veiksmų istorijų rodinys.
Galima sumažinti rezultatų skaičių patikslinant veiksmo rūšį, naudotoją ar susijusį puslapį.',
'logempty'             => 'Istorijoje nėra jokių atitinkančių įvykių.',
'log-title-wildcard'   => 'Ieškoti pavadinimų, prasidedančių šiuo tekstu',

# Special:AllPages
'allpages'          => 'Visi puslapiai',
'alphaindexline'    => 'Nuo $1 iki $2',
'nextpage'          => 'Kitas puslapis ($1)',
'prevpage'          => 'Ankstesnis puslapis ($1)',
'allpagesfrom'      => 'Rodyti puslapius pradedant nuo:',
'allpagesto'        => 'Rodyti puslapius, besibaigiančius su:',
'allarticles'       => 'Visi puslapiai',
'allinnamespace'    => 'Visi puslapiai ($1 vardų sritis)',
'allnotinnamespace' => 'Visi puslapiai (nesantys $1 vardų srityje)',
'allpagesprev'      => 'Atgal',
'allpagesnext'      => 'Pirmyn',
'allpagessubmit'    => 'Rodyti',
'allpagesprefix'    => 'Rodyti puslapiu su priedėliu:',
'allpagesbadtitle'  => 'Duotas puslapio pavadinimas yra neteisingas arba turi tarpkalbininį arba tarpprojektinį priedėlį. Jame yra vienas ar keli simboliai, kurių negalima naudoti pavadinimuose.',
'allpages-bad-ns'   => '{{SITENAME}} neturi „$1“ vardų srities.',

# Special:Categories
'categories'                    => 'Kategorijos',
'categoriespagetext'            => '{{PLURAL:$1|Ši kategorija|Šios kategorijos}} turi puslapių ar failų.
[[Special:UnusedCategories|Nenaudojamos kategorijos]] čia nerodomos.
Taip pat žiūrėkite [[Special:WantedCategories|trokštamas kategorijas]].',
'categoriesfrom'                => 'Vaizduoti kategorijas pradedant nuo:',
'special-categories-sort-count' => 'rikiuoti pagal skaičių',
'special-categories-sort-abc'   => 'rikiuoti pagal abėcėlę',

# Special:DeletedContributions
'deletedcontributions'             => 'Ištrintas naudotojo indėlis',
'deletedcontributions-title'       => 'Ištrintas naudotojo indėlis',
'sp-deletedcontributions-contribs' => 'indėlis',

# Special:LinkSearch
'linksearch'       => 'Ieškoti išorinių nuorodų',
'linksearch-pat'   => 'Ieškoti modulio:',
'linksearch-ns'    => 'Vardų sritis:',
'linksearch-ok'    => 'Ieškoti',
'linksearch-text'  => 'Galima naudoti žvaigždutės, pvz., „*.wikipedia.org“.<br />
Palaikomi protokolai: <tt>$1</tt>',
'linksearch-line'  => '$1 yra susietas iš $2',
'linksearch-error' => 'Žvaigždutės gali būti tik adreso pradžioje.',

# Special:ListUsers
'listusersfrom'      => 'Rodyti naudotojus pradedant nuo:',
'listusers-submit'   => 'Rodyti',
'listusers-noresult' => 'Nerasta jokių naudotojų.',
'listusers-blocked'  => '(užblokuotas)',

# Special:ActiveUsers
'activeusers'            => 'Aktyvių naudotojų sąrašas',
'activeusers-intro'      => 'Tai naudotojų sąrašas, kurie ką nors padarė per $1 {{PLURAL:$1|paskutinę dieną|paskutines dienas|paskutinių dienų}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}} per $3 {{PLURAL:$3|paskutinę dieną|paskutines dienas|paskutinių dienų}}',
'activeusers-from'       => 'Rodyti naudotojus, pradedant:',
'activeusers-hidebots'   => 'Slėpti robotus',
'activeusers-hidesysops' => 'Slėpti administratorius',
'activeusers-noresult'   => 'Nerasta jokių naudotojų.',

# Special:Log/newusers
'newuserlogpage'              => 'Prisiregistravę naudotojai',
'newuserlogpagetext'          => 'Tai naudotojų kūrimo sąrašas.',
'newuserlog-byemail'          => 'slaptažodis nusiųstas elektroniniu paštu',
'newuserlog-create-entry'     => 'Naujas naudotojas',
'newuserlog-create2-entry'    => 'sukurta nauja paskyra $1',
'newuserlog-autocreate-entry' => 'Paskyra sukurta automatiškai',

# Special:ListGroupRights
'listgrouprights'                      => 'Naudotojų grupių teisės',
'listgrouprights-summary'              => 'Žemiau pateiktas naudotojų grupių, apibrėžtų šioje wiki, ir su jomis susijusių teisių sąrašas.
Čia gali būti [[{{MediaWiki:Listgrouprights-helppage}}|papildoma informacija]] apie individualias teises.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Suteikta teisė</span>
* <span class="listgrouprights-revoked">Atimta teisė</span>',
'listgrouprights-group'                => 'Grupė',
'listgrouprights-rights'               => 'Teisės',
'listgrouprights-helppage'             => 'Help:Grupės teisės',
'listgrouprights-members'              => '(narių sąrašas)',
'listgrouprights-addgroup'             => 'Gali pridėti {{PLURAL:$2|grupę|grupes}}: $1',
'listgrouprights-removegroup'          => 'Gali pašalinti {{PLURAL:$2|grupę|grupes}}: $1',
'listgrouprights-addgroup-all'         => 'Gali pridėti visas grupes',
'listgrouprights-removegroup-all'      => 'Gali pašalinti visas grupes',
'listgrouprights-addgroup-self'        => 'Priskirti {{PLURAL:$2|grupę|grupes}} savo paskyrai: $1',
'listgrouprights-removegroup-self'     => 'Pašalinti {{PLURAL:$2|grupę|grupes}} iš savo paskyros: $1',
'listgrouprights-addgroup-self-all'    => 'Priskirti visas grupes prie paskyros',
'listgrouprights-removegroup-self-all' => 'Pašalinti visas grupes iš savo paskyros',

# E-mail user
'mailnologin'          => 'Nėra adreso',
'mailnologintext'      => 'Jums reikia būti [[Special:UserLogin|prisijungusiam]] ir turi būti įvestas teisingas el. pašto adresas jūsų [[Special:Preferences|nustatymuose]], kad siųstumėte el. laiškus kitiems nautotojams.',
'emailuser'            => 'Rašyti laišką šiam naudotojui',
'emailpage'            => 'Siųsti el. laišką naudotojui',
'emailpagetext'        => 'Jūs gali pasinaudoti šia forma norėdami nusiųsti el. laišką šiam naudotojui.
El. pašto adresas, kurį įvedėte [[Special:Preferences|savo naudotojo nustatymuose]], bus rodomas kaip el. pašto siuntėjo adresas, tam, kad gavėjas galėtų jums iškart atsakyti.',
'usermailererror'      => 'Pašto objektas grąžino klaidą:',
'defemailsubject'      => '{{SITENAME}} el. paštas',
'usermaildisabled'     => 'Naudotojo elektroninis paštas išjungtas',
'usermaildisabledtext' => 'Jūs negalite siūlsti el. laiško kitiems šio wiki projekto naudotojams.',
'noemailtitle'         => 'Nėra el. pašto adreso',
'noemailtext'          => 'Šis naudotojas nėra nurodęs teisingo el. pašto adreso, arba yra pasirinkęs negauti el. pašto iš kitų naudotojų.',
'nowikiemailtitle'     => 'El. laiškai neleidžiami',
'nowikiemailtext'      => 'Šis naudotojas yra pasirinkęs negauti elektroninių laiškų iš kitų naudotojų.',
'email-legend'         => 'Siųsti elektroninį laišką kitam {{SITENAME}} naudotojui',
'emailfrom'            => 'Nuo:',
'emailto'              => 'Kam:',
'emailsubject'         => 'Tema:',
'emailmessage'         => 'Tekstas:',
'emailsend'            => 'Siųsti',
'emailccme'            => 'Siųsti man mano laiško kopiją.',
'emailccsubject'       => 'Laiško kopija naudotojui $1: $2',
'emailsent'            => 'El. laiškas išsiųstas',
'emailsenttext'        => 'Jūsų el. pašto žinutė išsiųsta.',
'emailuserfooter'      => 'Šis elektroninis laiškas buvo išsiųstas naudotojo $1 naudotojui $2 naudojant „Rašyti elektroninį laišką“ funkciją projekte {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Paliekamas sistemos pranešimas.',
'usermessage-editor'  => 'Sistemos pranešėjas',

# Watchlist
'watchlist'            => 'Stebimi puslapiai',
'mywatchlist'          => 'Stebimi puslapiai',
'watchlistfor2'        => 'Naudotojo $1 $2',
'nowatchlist'          => 'Neturite nei vieno stebimo puslapio.',
'watchlistanontext'    => 'Prašome $1, kad peržiūrėtumėte ar pakeistumėte elementus savo stebimųjų sąraše.',
'watchnologin'         => 'Neprisijungęs',
'watchnologintext'     => 'Jums reikia būti [[Special:UserLogin|prisijungusiam]], kad pakeistumėte savo stebimųjų sąrašą.',
'addedwatch'           => 'Pridėta į Stebimųjų sąrašą',
'addedwatchtext'       => "Puslapis „[[:$1]]“ pridėtas į [[Special:Watchlist|stebimųjų sąrašą]].
Būsimi puslapio bei atitinkamo aptarimo puslapio pakeitimai bus rodomi stebimųjų puslapių sąraše,
taip pat bus '''paryškinti''' [[Special:RecentChanges|naujausių keitimų sąraše]], kad išsiskirtų iš kitų puslapių.",
'removedwatch'         => 'Pašalinta iš stebimų',
'removedwatchtext'     => 'Puslapis „[[:$1]]“ pašalintas iš jūsų [[Special:Watchlist|stebimųjų sąrašo]].',
'watch'                => 'Stebėti',
'watchthispage'        => 'Stebėti šį puslapį',
'unwatch'              => 'Nebestebėti',
'unwatchthispage'      => 'Nustoti stebėti',
'notanarticle'         => 'Ne turinio puslapis',
'notvisiblerev'        => 'Versija buvo ištrinta',
'watchnochange'        => 'Pasirinktu laikotarpiu nebuvo redaguotas nei vienas stebimas puslapis.',
'watchlist-details'    => 'Stebima {{PLURAL:$1|$1 puslapis|$1 puslapiai|$1 puslapių}} neskaičiuojant aptarimų puslapių.',
'wlheader-enotif'      => '* El. pašto priminimai yra įjungti.',
'wlheader-showupdated' => "* Puslapiai pakeisti nuo tada, kai paskutinį kartą apsilankėte juose, yra pažymėti '''pastorintai'''",
'watchmethod-recent'   => 'tikrinami paskutiniai keitimai stebimiems puslapiams',
'watchmethod-list'     => 'ieškoma naujausių keitimų stebimuose puslapiuose',
'watchlistcontains'    => 'Jūsų stebimųjų sąraše yra $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}}.',
'iteminvalidname'      => 'Problema su elementu „$1“, neteisingas vardas...',
'wlnote'               => "{{PLURAL:$1|Rodomas '''$1''' paskutinis pakeitimas, atliktas|Rodomi '''$1''' paskutiniai pakeitimai, atlikti|Rodoma '''$1''' paskutinių pakeitimų, atliktų}} per '''$2''' {{PLURAL:$2|paskutinę valandą|paskutines valandas|paskutinių valandų}}.",
'wlshowlast'           => 'Rodyti paskutinių $1 valandų, $2 dienų ar $3 pakeitimus',
'watchlist-options'    => 'Stebimųjų sąrašo parinktys',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Įtraukiama į stebimųjų sąrašą...',
'unwatching' => 'Šalinama iš stebimųjų sąrašo...',

'enotif_mailer'                => '{{SITENAME}} Pranešimų sistema',
'enotif_reset'                 => 'Pažymėti visus puslapius kaip aplankytus',
'enotif_newpagetext'           => 'Tai naujas puslapis.',
'enotif_impersonal_salutation' => '{{SITENAME}} naudotojau',
'changed'                      => 'pakeitė',
'created'                      => 'sukurė',
'enotif_subject'               => '{{SITENAME}} projekte $PAGEEDITOR $CHANGEDORCREATED $PAGETITLE',
'enotif_lastvisited'           => 'Užeikite į $1, jei norite matyti pakeitimus nuo paskutiniojo apsilankymo.',
'enotif_lastdiff'              => 'Užeikite į $1, jei norite pamatyti šį pakeitimą.',
'enotif_anon_editor'           => 'anoniminis naudotojas $1',
'enotif_body'                  => '$WATCHINGUSERNAME,


$PAGEEDITDATE {{SITENAME}} projekte $PAGEEDITOR $CHANGEDORCREATED puslapį „$PAGETITLE“, dabartinę versiją rasite adresu $PAGETITLE_URL.

$NEWPAGE

Redaguotojo komentaras: $PAGESUMMARY $PAGEMINOREDIT

Susisiekti su redaguotoju:
el. paštu: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Daugiau pranešimų apie vėlesnius pakeitimus nebus siunčiama, jei neapsilankysite puslapyje.
Jūs taip pat galite išjungti pranešimo žymę visiems jūsų stebimiems puslapiams savo stebimųjų sąraše.

      Jūsų draugiškoji projekto {{SITENAME}} pranešimų sistema

--
Norėdami pakeisti stebimų puslapių nustatymus, užeikite į
{{fullurl:{{#special:Watchlist}}/edit}}

Norėdami puslapį iš stebimų puslapių sąrašo, užeikite į
$UNWATCHURL

Atsiliepimai ir pagalba:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Trinti puslapį',
'confirm'                => 'Tvirtinu',
'excontent'              => 'buvęs turinys: „$1“',
'excontentauthor'        => 'buvęs turinys: „$1“ (redagavo tik „[[Special:Contributions/$2|$2]]“)',
'exbeforeblank'          => 'prieš ištrinant turinys buvo: „$1“',
'exblank'                => 'puslapis buvo tuščias',
'delete-confirm'         => 'Ištrinti „$1“',
'delete-legend'          => 'Trynimas',
'historywarning'         => "'''Dėmesio:''' Trinamas puslapis turi istoriją su maždaug $1 {{PLURAL:$1|versija|versijomis|versijų}}:",
'confirmdeletetext'      => 'Jūs pasirinkote ištrinti puslapį ar paveikslėlį kartu su visa jo istorija.
Prašome patvirtinti, kad jūs tikrai norite tai padaryti, žinote apie galimus padarinius, ir kad jūs tai darote atsižvelgdami į [[{{MediaWiki:Policy-url}}|politiką]].',
'actioncomplete'         => 'Veiksmas atliktas',
'actionfailed'           => 'Veiksmas atšauktas',
'deletedtext'            => '„<nowiki>$1</nowiki>“ ištrintas.
Paskutinių šalinimų istorija - $2.',
'deletedarticle'         => 'ištrynė „[[$1]]“',
'suppressedarticle'      => 'apribotas „[[$1]]“',
'dellogpage'             => 'Šalinimų istorija',
'dellogpagetext'         => 'Žemiau pateikiamas paskutinių trynimų sąrašas.',
'deletionlog'            => 'šalinimų istorija',
'reverted'               => 'Atkurta į ankstesnę versiją',
'deletecomment'          => 'Priežastis:',
'deleteotherreason'      => 'Kita/papildoma priežastis:',
'deletereasonotherlist'  => 'Kita priežastis',
'deletereason-dropdown'  => '*Dažnos trynimo priežastys
** Autoriaus prašymas
** Autorystės teisių pažeidimas
** Vandalizmas',
'delete-edit-reasonlist' => 'Keisti trynimo priežastis',
'delete-toobig'          => 'Šis puslapis turi ilgą keitimų istoriją, daugiau nei $1 {{PLURAL:$1|revizija|revizijos|revizijų}}. Tokių puslapių trynimas yra apribotas, kad būtų išvengta atsitiktinio {{SITENAME}} žlugdymo.',
'delete-warning-toobig'  => 'Šis puslapis turi ilgą keitimų istoriją, daugiau nei $1 {{PLURAL:$1|revizija|revizijos|revizijų}}. Trinant jis gali sutrikdyti {{SITENAME}} duomenų bazės operacijas; būkite atsargūs.',

# Rollback
'rollback'          => 'Atmesti keitimus',
'rollback_short'    => 'Atmesti',
'rollbacklink'      => 'atmesti',
'rollbackfailed'    => 'Atmetimas nepavyko',
'cantrollback'      => 'Negalima atmesti redagavimo; paskutinis keitęs naudotojas yra šio puslapio autorius.',
'alreadyrolled'     => 'Nepavyko atmesti paskutinio [[User:$2|$2]] ([[User talk:$2|Aptarimas]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) daryto puslapio [[:$1]] keitimo;
kažkas jau pakeitė puslapį arba suspėjo pirmas atmesti keitimą.

Paskutimas keitimas darytas naudotojo [[User:$3|$3]] ([[User talk:$3|Aptarimas]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Redagavimo komentaras: „''$1''“.",
'revertpage'        => 'Atmestas [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarimas]]) pakeitimas; sugrąžinta naudotojo [[User:$1|$1]] versija',
'revertpage-nouser' => 'Atmesti (naudotojo vardas pašalintas) pakeitimai, grąžinta prieš tai buvusi [[User:$1|$1]] versija',
'rollback-success'  => 'Atmesti $1 pakeitimai;
grąžinta prieš tai buvusi $2 versija.',

# Edit tokens
'sessionfailure-title' => 'Sesijos klaida',
'sessionfailure'       => 'Atrodo yra problemų su jūsų prisijungimo sesija; šis veiksmas buvo atšauktas kaip atsargumo priemonė prieš sesijos vogimą.
Prašome paspausti „atgal“ ir perkraukite puslapį iš kurio atėjote, ir pamėginkite vėl.',

# Protect
'protectlogpage'              => 'Rakinimų istorija',
'protectlogtext'              => 'Žemiau yra puslapių užrakinimų bei atrakinimų istorija.
Dabar veikiančių puslapių apsaugų sąrašą rasite [[Special:ProtectedPages|apsaugotų puslapių sąraše]].',
'protectedarticle'            => 'užrakino „[[$1]]“',
'modifiedarticleprotection'   => 'pakeistas „[[$1]]“ apsaugos lygis',
'unprotectedarticle'          => 'atrakino „[[$1]]“',
'movedarticleprotection'      => 'perkelti apsaugos nustatymai iš „[[$2]]“ į „[[$1]]“',
'protect-title'               => 'Nustatomas apsaugos lygis puslapiui „$1“',
'prot_1movedto2'              => '[[$1]] pervadintas į [[$2]]',
'protect-legend'              => 'Užrakinimo patvirtinimas',
'protectcomment'              => 'Priežastis:',
'protectexpiry'               => 'Baigia galioti:',
'protect_expiry_invalid'      => 'Galiojimo laikas neteisingas.',
'protect_expiry_old'          => 'Galiojimo laikas yra praeityje.',
'protect-unchain-permissions' => 'Atrakinti šiuos apsaugos nustatymus',
'protect-text'                => "Čia jūs gali matyti ir keisti apsaugos lygį puslapiui '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Jūs negalite keisti apsaugos lygių, kol esate užbluokuotas.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-locked-dblock'       => "Apsaugos lygiai negali būti pakeisti dėl duomenų bazės užrakinimo.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-locked-access'       => "Jūsų paskyra neturi teisių keisti puslapių apsaugos lygių.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-cascadeon'           => 'Šis puslapis dabar yra apsaugotas, nes jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi. Jūs galite pakeisti šio puslapio apsaugos lygį, bet tai nepaveiks pakopinės apsaugos.',
'protect-default'             => 'Leisti visiems naudotojams',
'protect-fallback'            => 'Reikalauti „$1“ teisės',
'protect-level-autoconfirmed' => 'Blokuoti naujai prisiregistravusius ir neregistruotus naudotojus',
'protect-level-sysop'         => 'Tik administratoriai',
'protect-summary-cascade'     => 'pakopinė apsauga',
'protect-expiring'            => 'baigia galioti $1 (UTC)',
'protect-expiry-indefinite'   => 'neribotai',
'protect-cascade'             => 'Apsaugoti puslapius, įtrauktus į šį puslapį (pakopinė apsauga).',
'protect-cantedit'            => 'Jūs negalite keisti šio puslapio apsaugojimo lygių, nes neturite teisių jo redaguoti.',
'protect-othertime'           => 'Kitas laikas:',
'protect-othertime-op'        => 'kitas laikas',
'protect-existing-expiry'     => 'Esamas galiojimo laikas: $3, $2',
'protect-otherreason'         => 'Kita/papildoma priežastis:',
'protect-otherreason-op'      => 'Kita priežastis',
'protect-dropdown'            => '*Įprastos užrakinimo priežastys
** Intensyvus vandalizmas
** Intensyvus nuorodų reklamavimas
** Neproduktyvus redagavimo karas
** Didelės svarbos puslapis',
'protect-edit-reasonlist'     => 'Keisti užrakinimo priežastis',
'protect-expiry-options'      => '1 valanda:1 hour,1 diena:1 day,1 savaitė:1 week,2 savaitės:2 weeks,1 mėnuo:1 month,3 mėnesiai:3 months,6 mėnesiai:6 months,1 metai:1 year,neribotai:infinite',
'restriction-type'            => 'Leidimas:',
'restriction-level'           => 'Apribojimo lygis:',
'minimum-size'                => 'Min. dydis',
'maximum-size'                => 'Maks. dydis:',
'pagesize'                    => '(baitais)',

# Restrictions (nouns)
'restriction-edit'   => 'Redagavimas',
'restriction-move'   => 'Pervardinimas',
'restriction-create' => 'Sukurti',
'restriction-upload' => 'Įkelti',

# Restriction levels
'restriction-level-sysop'         => 'pilnai apsaugota',
'restriction-level-autoconfirmed' => 'pusiau apsaugota',
'restriction-level-all'           => 'bet koks',

# Undelete
'undelete'                     => 'Atstatyti ištrintą puslapį',
'undeletepage'                 => 'Rodyti ir atkurti ištrintus puslapius',
'undeletepagetitle'            => "'''Tai sudaryta iš ištrintų [[:$1]] versijų'''.",
'viewdeletedpage'              => 'Rodyti ištrintus puslapius',
'undeletepagetext'             => '{{PLURAL:$1|Šis $1 puslapis buvo ištrintas|Šie $1 puslapiai buvo ištrinti|Šie $1 puslapių buvo ištrinti}}, bet dar yra archyve ir gali būti {{PLURAL:$1|atkurtas|atkurti|atkurti}}.
Archyvas gali būti periodiškai valomas.',
'undelete-fieldset-title'      => 'Atstatyti versijas',
'undeleteextrahelp'            => "Norėdami atkurti visą puslapį, palikite visas varneles nepažymėtas ir
spauskite '''''Atkurti'''''. Norėdami atlikti pasirinktinį atstatymą, pažymėkite varneles tų versijų, kurias norėtumėte atstatyti, ir spauskite '''''Atkurti'''''. Paspaudus
'''''Iš naujo''''' bus išvalytos visos varnelės bei komentaro laukas.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versija|versijos|versijų}} suarchyvuota',
'undeletehistory'              => 'Jei atstatysite puslapį, istorijoje bus atstatytos visos versijos.
Jei po ištrynimo buvo sukurtas puslapis tokiu pačiu pavadinimu, atstatytos versijos atsiras ankstesnėje istorijoje.',
'undeleterevdel'               => 'Atkūrimas nebus įvykdytas, jei tai nulems paskutinės puslapio ar failo versijos dalinį ištrynimą.
Tokiais atvejais, jums reikia atžymėti arba atslėpti naujausią ištrintą versiją.',
'undeletehistorynoadmin'       => 'Šis puslapis buvo ištrintas. Žemiau rodoma trynimo priežastis bei kas redagavo puslapį iki ištrynimo. Ištrintų puslapių tekstas yra galimas tik administratoriams.',
'undelete-revision'            => 'Ištrinta $1 versija, kurią $4 d. $5 sukūrė $3:',
'undeleterevision-missing'     => 'Neteisinga arba dingusi versija. Jūs turbūt turite blogą nuorodą, arba versija buvo atkurta arba pašalinta iš archyvo.',
'undelete-nodiff'              => 'Nerasta jokių ankstesnių versijų.',
'undeletebtn'                  => 'Atkurti',
'undeletelink'                 => 'žiūrėti/atstatyti',
'undeleteviewlink'             => 'žiūrėti',
'undeletereset'                => 'Iš naujo',
'undeleteinvert'               => 'Žymėti priešingai',
'undeletecomment'              => 'Priežastis:',
'undeletedarticle'             => 'atkurta „[[$1]]“',
'undeletedrevisions'           => '{{PLURAL:$1|atkurta $1 versija|atkurtos $1 versijos|atkurta $1 versijų}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|atkurta $1 versija|atkurtos $1 versijos|atkurta $1 versijų}} ir $2 {{PLURAL:$2|failas|failai|failų}}',
'undeletedfiles'               => '{{PLURAL:$1|atkurtas $1 failas|atkurti $1 failai|atkurta $1 failų}}',
'cannotundelete'               => 'Atkūrimas nepavyko; kažkas kitas pirmas galėjo atkurti puslapį.',
'undeletedpage'                => "'''$1 buvo atkurtas'''

Peržiūrėkite [[Special:Log/delete|trynimų sąrašą]], norėdami rasti paskutinių trynimų ir atkūrimų sąrašą.",
'undelete-header'              => 'Žiūrėkite [[Special:Log/delete|trynimo istorijoje]] paskiausiai ištrintų puslapių.',
'undelete-search-box'          => 'Ieškoti ištrintų puslapių',
'undelete-search-prefix'       => 'Rodyti puslapius pradedant su:',
'undelete-search-submit'       => 'Ieškoti',
'undelete-no-results'          => 'Nebuvo rasta jokio atitinkančio puslapio ištrynimo archyve.',
'undelete-filename-mismatch'   => 'Nepavyksta atkurti failo versijos su laiku $1: failo pavadinimas nesutampa',
'undelete-bad-store-key'       => 'Nepavyksta atkurti failo versijos su laiku $1: failas buvo dingęs pries ištrynimą.',
'undelete-cleanup-error'       => 'Klaida trinant nenaudotą archyvo failą „$1“.',
'undelete-missing-filearchive' => 'Nepavyksta atkurti failo archyvo ID $1, nes jo nėra duomenų bazėje. Jis gali būti jau atkurtas.',
'undelete-error-short'         => 'Klaida atkuriant failą: $1',
'undelete-error-long'          => 'Įvyko klaidų atkuriant failą:

$1',
'undelete-show-file-confirm'   => 'Ar tikrai norite peržiūrėti ištrintą failo „<nowiki>$1</nowiki>“ $2 $3 versiją?',
'undelete-show-file-submit'    => 'Taip',

# Namespace form on various pages
'namespace'      => 'Vardų sritis:',
'invert'         => 'Žymėti priešingai',
'blanknamespace' => '(Pagrindinė)',

# Contributions
'contributions'       => 'Naudotojo įnašas',
'contributions-title' => 'Naudotojo $1 indėlis',
'mycontris'           => 'Mano įnašas',
'contribsub2'         => 'Naudotojo $1 ($2)',
'nocontribs'          => 'Jokie keitimai neatitiko šių kriterijų.',
'uctop'               => ' (paskutinis)',
'month'               => 'Nuo mėnesio (ir anksčiau):',
'year'                => 'Nuo metų (ir anksčiau):',

'sp-contributions-newbies'             => 'Rodyti tik naujų paskyrų įnašus',
'sp-contributions-newbies-sub'         => 'Naujoms paskyroms',
'sp-contributions-newbies-title'       => 'Naudotojų keitimai naujoms paskyroms',
'sp-contributions-blocklog'            => 'Blokavimų istorija',
'sp-contributions-deleted'             => 'ištrintas naudotojo indėlis',
'sp-contributions-logs'                => 'Specialiųjų veiksmų istorija',
'sp-contributions-talk'                => 'Aptarimas',
'sp-contributions-userrights'          => 'naudotojų teisių valdymas',
'sp-contributions-blocked-notice'      => 'Šis naudotojas šiuo metu užblokuotas.
Pateikiamas paskutinis blokavimo istorijos įrašas.',
'sp-contributions-blocked-notice-anon' => 'Šis IP adresas yra užblokuotas.
Paskutinis blokavimo įrašas pateikiamas žemiau:',
'sp-contributions-search'              => 'Ieškoti įnašo',
'sp-contributions-username'            => 'IP adresas arba naudotojo vardas:',
'sp-contributions-toponly'             => 'Rodyti tik paskutinius keitimus',
'sp-contributions-submit'              => 'Ieškoti',

# What links here
'whatlinkshere'            => 'Susiję puslapiai',
'whatlinkshere-title'      => 'Puslapiai, kurie nurodo į „$1“',
'whatlinkshere-page'       => 'Puslapis:',
'linkshere'                => "Šie puslapiai rodo į '''[[:$1]]''':",
'nolinkshere'              => "Į '''[[:$1]]''' nuorodų nėra.",
'nolinkshere-ns'           => "Nurodytoje vardų srityje nei vienas puslapis nenurodo į '''[[:$1]]'''.",
'isredirect'               => 'nukreipiamasis puslapis',
'istemplate'               => 'įterpimas',
'isimage'                  => 'paveikslėlio nuoroda',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|ankstesnis|ankstesni|ankstesnių}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|kitas|kiti|kitų}}',
'whatlinkshere-links'      => '← nuorodos',
'whatlinkshere-hideredirs' => '$1 nukreipimus',
'whatlinkshere-hidetrans'  => '$1 įtraukimus',
'whatlinkshere-hidelinks'  => '$1 nuorodas',
'whatlinkshere-hideimages' => '$1 paveikslėlių nuorodos',
'whatlinkshere-filters'    => 'Filtrai',

# Block/unblock
'blockip'                         => 'Blokuoti naudotoją',
'blockip-title'                   => 'Blokuoti naudotoją',
'blockip-legend'                  => 'Blokuoti naudotoją',
'blockiptext'                     => 'Naudokite šią formą norėdami uždrausti rašymo teises nurodytui IP adresui ar naudotojui. Tai turėtų būti atliekama tam, kad sustabdytumėte vandalizmą, ir pagal [[{{MediaWiki:Policy-url}}|politiką]].
Žemiau nurodykite tikslią priežastį (pavyzdžiui, nurodydami sugadintus puslapius).',
'ipaddress'                       => 'IP adresas',
'ipadressorusername'              => 'IP adresas arba naudotojo vardas',
'ipbexpiry'                       => 'Galiojimo laikas',
'ipbreason'                       => 'Priežastis:',
'ipbreasonotherlist'              => 'Kita priežastis',
'ipbreason-dropdown'              => '*Bendrosios blokavimo priežastys
** Melagingos informacijos įterpimas
** Turinio šalinimas iš puslapių
** Kitų svetainių reklamavimas
** Nesąmonių/bet ko įterpimas į puslapius
** Gąsdinimai/Įžeidinėjimai
** Piktnaudžiavimas keliomis paskyromis
** Nepriimtinas naudotojo vardas',
'ipbanononly'                     => 'Blokuoti tik anoniminius naudotojus',
'ipbcreateaccount'                => 'Neleisti kurti paskyrų',
'ipbemailban'                     => 'Neleisti naudotojui siųsti el. pašto',
'ipbenableautoblock'              => 'Automatiškai blokuoti šio naudotojo paskiausiai naudotą IP adresą, bei bet kokius vėlesnius IP adresus, iš kurių jie mėgina redaguoti',
'ipbsubmit'                       => 'Blokuoti šį naudotoją',
'ipbother'                        => 'Kitoks laikas',
'ipboptions'                      => '2 valandos:2 hours,1 diena:1 day,3 dienos:3 days,1 savaitė:1 week,2 savaitės:2 weeks,1 mėnesis:1 month,3 mėnesiai:3 months,6 mėnesiai:6 months,1 metai:1 year,neribotai:infinite',
'ipbotheroption'                  => 'kita',
'ipbotherreason'                  => 'Kita/papildoma priežastis',
'ipbhidename'                     => 'Slėpti naudotojo vardą keitimuose bei sąrašuose',
'ipbwatchuser'                    => 'Stebėti šio naudotojo puslapį ir jo aptarimų puslapį',
'ipballowusertalk'                => 'Leisti šiam naudotojui, kol jis yra blokuotas, redaguoti savo aptarimų puslapį.',
'ipb-change-block'                => 'Perblokuoti šį naudotoją su šiais nustatymais',
'badipaddress'                    => 'Neleistinas IP adresas',
'blockipsuccesssub'               => 'Užblokavimas pavyko',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] buvo užblokuotas.<br />
Aplankykite [[Special:IPBlockList|IP blokavimų istoriją]] norėdami jį peržiūrėti.',
'ipb-edit-dropdown'               => 'Redaguoti blokavimų priežastis',
'ipb-unblock-addr'                => 'Atblokuoti $1',
'ipb-unblock'                     => 'Atblokuoti naudotojo vardą arba IP adresą',
'ipb-blocklist'                   => 'Rodyti egzistuončius blokavimus',
'ipb-blocklist-contribs'          => '$1 indėlis',
'unblockip'                       => 'Atblokuoti naudotoją',
'unblockiptext'                   => 'Naudokite šią formą, kad atkurtumėte rašymo teises
ankščiau užblokuotam IP adresui ar naudotojui.',
'ipusubmit'                       => 'Atblokuoti šį adresą',
'unblocked'                       => '[[User:$1|$1]] buvo atblokuotas',
'unblocked-id'                    => 'Blokavimas $1 buvo pašalintas',
'ipblocklist'                     => 'Blokuoti IP adresai bei naudotojų vardai',
'ipblocklist-legend'              => 'Rasti užblokuotą naudotoją',
'ipblocklist-username'            => 'Naudotojas arba IP adresas:',
'ipblocklist-sh-userblocks'       => '$1 paskyros blokavimus',
'ipblocklist-sh-tempblocks'       => '$1 laikinus blokavimus',
'ipblocklist-sh-addressblocks'    => '$1 pavienių IP blokavimus',
'ipblocklist-submit'              => 'Ieškoti',
'ipblocklist-localblock'          => 'Vietinis blokavimas',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Kitas blokavimas|Kiti blokavimai}}',
'blocklistline'                   => '$1, $2 blokavo $3 ($4)',
'infiniteblock'                   => 'neribotai',
'expiringblock'                   => 'baigia galioti $1 $2',
'anononlyblock'                   => 'tik anonimai',
'noautoblockblock'                => 'automatinis blokavimas išjungtas',
'createaccountblock'              => 'paskyrų kūrimas uždraustas',
'emailblock'                      => 'el. paštas užblokuotas',
'blocklist-nousertalk'            => 'negali redaguoti savo aptarimų puslapio',
'ipblocklist-empty'               => 'Blokavimų sąrašas tuščias.',
'ipblocklist-no-results'          => 'Prašomas IP adresas ar naudotojo vardas nėra užblokuotas.',
'blocklink'                       => 'blokuoti',
'unblocklink'                     => 'atblokuoti',
'change-blocklink'                => 'keisti blokavimo nustatymus',
'contribslink'                    => 'įnašas',
'autoblocker'                     => 'Jūs buvote automatiškai užblokuotas, nes jūsų IP adresą neseniai naudojo „[[User:$1|$1]]“. Nurodyta naudotojo $1 blokavimo priežastis: „$2“.',
'blocklogpage'                    => 'Blokavimų istorija',
'blocklog-showlog'                => 'Šis naudotojas buvo užblokuotas.
Pateikiamas paskutinis blokavimo istorijos įrašas.',
'blocklog-showsuppresslog'        => 'Šis naudotojas buvo užblokuotas ir paslėptas anksčiau.
Žemiau yra pateiktas slėpimų žurnalas:',
'blocklogentry'                   => 'blokavo [[$1]], blokavimo laikas - $2 $3',
'reblock-logentry'                => 'pakeisti [[$1]] blokavimo nustatymai, naujas blokavimo laikas – $2 $3',
'blocklogtext'                    => 'Čia yra naudotojų blokavimo ir atblokavimo sąrašas.
Automatiškai blokuoti IP adresai neišvardinti.
Jei norite pamatyti dabar blokuojamus adresus, žiūrėkite [[Special:IPBlockList|IP blokavimų sąrašą]].',
'unblocklogentry'                 => 'atblokavo $1',
'block-log-flags-anononly'        => 'tik anoniminiai naudotojai',
'block-log-flags-nocreate'        => 'paskyrų kūrimas išjungtas',
'block-log-flags-noautoblock'     => 'automatinis blokavimas išjungtas',
'block-log-flags-noemail'         => 'el. paštas užblokuotas',
'block-log-flags-nousertalk'      => 'negali redaguoti savo naudotojo aptarimo puslapio',
'block-log-flags-angry-autoblock' => 'išplėstasis automatinis blokavimas įjungtas',
'block-log-flags-hiddenname'      => 'naudotojo vardas paslėptas',
'range_block_disabled'            => 'Administratoriaus galimybė kurti intevalinius blokus yra išjungta.',
'ipb_expiry_invalid'              => 'Galiojimo laikas neleistinas.',
'ipb_expiry_temp'                 => 'Paslėptų naudotojų vardų blokavimas turi būti nuolatinis.',
'ipb_hide_invalid'                => 'Negalima paslėpti šios paskyros; ji gali turėti per daug keitimų.',
'ipb_already_blocked'             => '„$1“ jau užblokuotas',
'ipb-needreblock'                 => '== Jau užblokuotas ==
$1 jau yra užblokuotas. Ar norite pakeisti nustatymus?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Kitas blokavimas|Kiti blokavimai}}',
'ipb_cant_unblock'                => 'Klaida: Blokavimo ID $1 nerastas. Galbūt jis jau atblokuotas.',
'ipb_blocked_as_range'            => 'Klaida: IP $1 nebuvo užblokuotas tiesiogiai, tad negali būti atblokuotas. Tačiau jis buvo užblokuotas kaip srities $2 dalis, kuri gali būti atblokuota.',
'ip_range_invalid'                => 'Neleistina IP sritis.',
'ip_range_toolarge'               => 'Didesni nei /$1 blokai neleidžiami.',
'blockme'                         => 'Užblokuoti mane',
'proxyblocker'                    => 'Tarpinių serverių blokuotojas',
'proxyblocker-disabled'           => 'Ši funkcija yra išjungta.',
'proxyblockreason'                => 'Jūsų IP adresas yra užblokuotas, nes jis yra atvirasis tarpinis serveris. Prašome susisiekti su savo interneto paslaugų tiekėju ar technine pagalba ir praneškite jiems apie šią svarbią saugumo problemą.',
'proxyblocksuccess'               => 'Atlikta.',
'sorbsreason'                     => 'Jūsų IP adresas yra įtrauktas į atvirųjų tarpinių serverių DNSBL sąrašą, naudojamą šios svetainės.',
'sorbs_create_account_reason'     => 'Jūsų IP adresas yra įtrauktas į atvirųjų tarpinių serverių DNSBL sąrašą, naudojamą šios svetainės. Jūs negalite sukurti paskyros',
'cant-block-while-blocked'        => 'Jūs negalite blokuoti kitų naudotojų kuomet pats esate užblokuotas.',
'cant-see-hidden-user'            => 'Naudotojas, kurį bandote užblokuoti, jau yra užblokuotas arba paslėptas.
Kadangi jūs neturi hideuser teisės, jūs negalite pamatyti arba pakeisti naudotojo blokavimo.',
'ipbblocked'                      => 'Jūs negalite blokuoti ar atblokuoti kitų naudotojų, nes pats esate užblokuotas',
'ipbnounblockself'                => 'Jums nėra leidžiama savęs atblokuoti',

# Developer tools
'lockdb'              => 'Užrakinti duomenų bazę',
'unlockdb'            => 'Atrakinti duomenų bazę',
'lockdbtext'          => 'Užrakinus duomenų bazę sustabdys galimybę visiems
naudotojams redaguoti puslapius, keisti jų nustatymus, keisti jų stebimųjų sąrašą bei
kitus dalykus, reikalaujančius pakeitimų duomenų bazėje.
Prašome patvirtinti, kad tai, ką ketinate padaryti, ir kad jūs
atrakinsite duomenų bazę, kai techninė profilaktika bus baigta.',
'unlockdbtext'        => 'Atrakinus duomenų bazę grąžins galimybę visiems
naudotojams redaguoti puslapius, keisti jų nustatymus, keisti jų stebimųjų sąrašą bei
kitus dalykus, reikalaujančius pakeitimų duomenų bazėje.
Prašome patvirtinti tai, ką ketinate padaryti.',
'lockconfirm'         => 'Taip, aš tikrai noriu užrakinti duomenų bazę.',
'unlockconfirm'       => 'Taip, aš tikrai noriu atrakinti duomenų bazę.',
'lockbtn'             => 'Užrakinti duomenų bazę',
'unlockbtn'           => 'Atrakinti duomenų bazę',
'locknoconfirm'       => 'Jūs neuždėjote patvirtinimo varnelės.',
'lockdbsuccesssub'    => 'Duomenų bazės užrakinimas pavyko',
'unlockdbsuccesssub'  => 'Duomenų bazės užrakinimas pašalintas',
'lockdbsuccesstext'   => 'Duomenų bazė buvo užrakinta.
<br />Nepamirškite [[Special:UnlockDB|pašalinti užraktą]], kai techninė profilaktika bus baigta.',
'unlockdbsuccesstext' => 'Duomenų bazė buvo atrakinta.',
'lockfilenotwritable' => 'Duomenų bazės užrakto failas nėra įrašomas. Norint užrakinti ar atrakinti duomenų bazę, tinklapio serveris privalo turėti įrašymo teises šiam failui.',
'databasenotlocked'   => 'Duomenų bazė neužrakinta.',

# Move page
'move-page'                    => 'Pervadinti $1',
'move-page-legend'             => 'Puslapio pervadinimas',
'movepagetext'                 => "Naudodamiesi žemiau pateikta forma, pervadinsite puslapį
neprarasdami jo istorijos.
Senasis pavadinimas taps nukreipiamuoju - rodys į naująjį.
Nuorodos į senąjį puslapį nebus automatiškai pakeistos, todėl būtinai
patikrinkite ar nesukūrėte [[Special:DoubleRedirects|dvigubų]] ar
[[Special:BrokenRedirects|neveikiančių]] nukreipimų.
Jūs esate atsakingas už tai, kad nuorodos rodytų į ten, kur ir norėta.

Primename, kad puslapis '''nebus''' pervadintas, jei jau yra puslapis
nauju pavadinimu, nebent tas puslapis tuščias arba nukreipiamasis ir
neturi redagavimo istorijos. Taigi, jūs galite pervadinti puslapį
seniau naudotu vardu, jei prieš tai jis buvo per klaidą pervadintas,
o egzistuojančių puslapių sugadinti negalite.

'''DĖMESIO!'''
Jei pervadinate populiarų puslapį, tai gali sukelti nepageidaujamų
šalutinių efektų, dėl to šį veiksmą vykdykite tik įsitikinę,
kad suprantate visas pasekmes.",
'movepagetext-noredirectfixer' => "Naudodamiesi žemiau pateikta forma, pervadinsite puslapį perkeldami visą jo istoriją į naująjį pavadinimą.
Senasis pavadinimas taps nukreipiamuoju puslapiu į naująjį.
Nuorodos į senąjį puslapį nebus automatiškai pakeistos, todėl būtinai
patikrinkite, ar nesukūrėte [[Special:DoubleRedirects|dvigubų]] ar [[Special:BrokenRedirects|neveikiančių]] nukreipimų.
Jūs esate atsakingas už tai, kad nuorodos rodytų į ten, kur ir norėta.

Primename, kad puslapis '''nebus''' pervadintas, jei jau yra puslapis nauju pavadinimu, nebent tas puslapis yra tuščias arba nukreipiamasis ir neturi redagavimo istorijos.
Taigi, jūs galite pervadinti puslapį seniau naudotu vardu, jei prieš tai jis buvo per klaidą pervadintas, o egzistuojančių puslapių sugadinti negalite.

'''Dėmesio!'''
Jei pervadinate populiarų puslapį, tai gali sukelti nepageidaujamų šalutinių efektų,
dėl to šį veiksmą vykdykite tik įsitikinę, kad suprantate visas pasekmes.",
'movepagetalktext'             => "Susietas aptarimo puslapis bus automatiškai perkeltas kartu su juo, '''išskyrus:''':
*Puslapis nauju pavadinimu jau turi netuščią aptarimo puslapį, arba
*Paliksite žemiau esančia varnelę nepažymėtą.

Šiais atvejais jūs savo nuožiūra turite perkelti arba apjungti aptarimo puslapį.",
'movearticle'                  => 'Pervardinti puslapį:',
'moveuserpage-warning'         => "'''Dėmesio:''' Jūs ruošiatės perkelti naudotojo puslapį. Atkreipkite dėmesį, kad bus perkeltas tik puslapis, o naudotojas ''nebus'' pervadintas.",
'movenologin'                  => 'Neprisijungęs',
'movenologintext'              => 'Norėdami pervadinti puslapį, turite būti užsiregistravęs naudotojas ir būti  [[Special:UserLogin|prisijungęs]].',
'movenotallowed'               => 'Jūs neturite teisių pervadinti puslapių.',
'movenotallowedfile'           => 'Jūs neturite teisės perkelti failus.',
'cant-move-user-page'          => 'Jūs neturite teisės perkelti naudotojų puslapių (išskyrus popuslapius).',
'cant-move-to-user-page'       => 'Jūs neturite teisių perkelti puslapį į naudotojo puslapį (išskyrus į naudotojo popuslapį).',
'newtitle'                     => 'Naujas pavadinimas:',
'move-watch'                   => 'Stebėti šį puslapį',
'movepagebtn'                  => 'Pervadinti puslapį',
'pagemovedsub'                 => 'Pervadinta sėkmingai',
'movepage-moved'               => "'''„$1“ buvo pervadintas į „$2“'''",
'movepage-moved-redirect'      => 'Nukreipimas sukurtas.',
'movepage-moved-noredirect'    => 'Nukreipimo sukūrimas buvo atšauktas.',
'articleexists'                => 'Puslapis tokiu pavadinimu jau egzistuoja
arba pasirinktas vardas yra neteisingas.
Pasirinkite kitą pavadinimą.',
'cantmove-titleprotected'      => 'Jūs negalite pervadinti puslapio, nes naujasis pavadinimas buvo apsaugotas nuo sukūrimo',
'talkexists'                   => "'''Pats puslapis buvo sėkmingai pervadintas, bet aptarimų puslapis nebuvo perkeltas, kadangi naujo
pavadinimo puslapis jau turėjo aptarimų puslapį.
Prašome sujungti šiuos puslapius.'''",
'movedto'                      => 'pervardintas į',
'movetalk'                     => 'Perkelti susijusį aptarimo puslapį.',
'move-subpages'                => 'Perkelti visus subpuslapius (baigiant $1)',
'move-talk-subpages'           => 'Perkelti visus aptarimo subpuslapius (iki $1)',
'movepage-page-exists'         => 'Puslapis $1 jau egzistuoja ir negali būti automatiškai perrašytas.',
'movepage-page-moved'          => 'Puslapis $1 perkeltas į $2.',
'movepage-page-unmoved'        => 'Puslapio $1 negalima perkelti į $2.',
'movepage-max-pages'           => 'Daugiausiai $1 {{PLURAL:$1|puslapis buvo perkeltas|puslapiai buvo perkelti|puslapių buvo perkelta}} ir daugiau nebus perkelta automatiškai.',
'1movedto2'                    => '[[$1]] pervadintas į [[$2]]',
'1movedto2_redir'              => '[[$1]] pervadintas į [[$2]] (anksčiau buvo nukreipiamasis)',
'move-redirect-suppressed'     => 'nukreipimas nesukurtas',
'movelogpage'                  => 'Pervardinimų istorija',
'movelogpagetext'              => 'Pervardintų puslapių sąrašas.',
'movesubpage'                  => '{{PLURAL:$1|Subpuslapis|Subpuslapiai}}',
'movesubpagetext'              => 'Žemiau yra šio puslapio $1 {{PLURAL:$1|subpuslapis|subpuslapiai|subpuslapių}}.',
'movenosubpage'                => 'Šis puslapis neturi subpuslapių.',
'movereason'                   => 'Priežastis:',
'revertmove'                   => 'atmesti',
'delete_and_move'              => 'Ištrinti ir perkelti',
'delete_and_move_text'         => '==Reikia ištrinti==

Paskirties puslapis „[[:$1]]“ jau yra. Ar norite jį ištrinti, kad galėtumėte pervardinti?',
'delete_and_move_confirm'      => 'Taip, trinti puslapį',
'delete_and_move_reason'       => 'Ištrinta dėl perkėlimo',
'selfmove'                     => 'Šaltinio ir paskirties pavadinimai yra tokie patys; negalima pervardinti puslapio į save.',
'immobile-source-namespace'    => 'Negalima perkelti puslapių vardų srityje „$1“',
'immobile-target-namespace'    => 'Perkelti puslapius į „$1“ vardų sritį negalima',
'immobile-target-namespace-iw' => 'Tarprojektinė nuoroda yra neleistina paskirtis perkelti puslapį.',
'immobile-source-page'         => 'Šio puslapio perkelti negalima.',
'immobile-target-page'         => 'Negalima perkelti į paskirtąją vietą.',
'imagenocrossnamespace'        => 'Negalima pervadinti failo į ne failo vardų sritį',
'nonfile-cannot-move-to-file'  => 'Negalima perkelti ne failo į failų vardų sritį',
'imagetypemismatch'            => 'Naujas failo plėtinys neatitinka jo tipo',
'imageinvalidfilename'         => 'Failo pavadinimas yra klaidingas',
'fix-double-redirects'         => 'Atnaujinti peradresavimus, kad šie rodytų į originalų straipsnio pavadinimą',
'move-leave-redirect'          => 'Pervadinant palikti nukreipimą',
'protectedpagemovewarning'     => "'''Dėmesio:''' Šis puslapis buvo užrakintas, kad tik naudotojai su administratoriaus teisėmis galėtų jį pervadinti.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'semiprotectedpagemovewarning' => "'''Pastaba''': Šis puslapis buvo užrakintas, kad tik registruoti naudotojai galėtų jį redaguoti.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'move-over-sharedrepo'         => '== Failas jau yra ==
[[:$1]] egzistuoja bendrojoje saugykloje. Perkėlus failą į šį pavadinimą, jis pakeis bendrąjį failą.',
'file-exists-sharedrepo'       => 'Pasirinktas failo pavadinimas jau yra naudojamas bendrojoje saugykloje.
Prašome pasirinkti kitą pavadinimą.',

# Export
'export'            => 'Eksportuoti puslapius',
'exporttext'        => 'Galite eksportuoti vieno puslapio tekstą ir istoriją ar kelių puslapių vienu metu tame pačiame XML atsakyme.
Šie puslapiai galės būti importuojami į kitą projektą, veikiantį MediaWiki pagrindu, per [[Special:Import|importo puslapį]].

Norėdami eksportuoti puslapius, įveskite pavadinimus žemiau esančiame tekstiniame lauke po vieną pavadinimą eilutėje, taip pat pasirinkite ar norite eksportuoti ir istoriją ar tik dabartinę versiją su paskutinio redagavimo informacija.

Pastaruoju atveju, jūs taip pat galite naudoti nuorodą, pvz. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] puslapiui „[[{{MediaWiki:Mainpage}}]]“.',
'exportcuronly'     => 'Eksportuoti tik dabartinę versiją, neįtraukiant istorijos',
'exportnohistory'   => "----
'''Pastaba:''' Pilnos puslapių istorijos eksportavimas naudojantis šia forma yra išjungtas dėl spartos.",
'export-submit'     => 'Eksportuoti',
'export-addcattext' => 'Pridėti puslapius iš kategorijos:',
'export-addcat'     => 'Pridėti',
'export-addnstext'  => 'Pridėti puslapius iš vardų srities:',
'export-addns'      => 'Pridėti',
'export-download'   => 'Saugoti kaip failą',
'export-templates'  => 'Įtraukti šablonus',
'export-pagelinks'  => 'Įtraukti susietus puslapius iki šio gylio:',

# Namespace 8 related
'allmessages'                   => 'Visi sistemos tekstai bei pranešimai',
'allmessagesname'               => 'Pavadinimas',
'allmessagesdefault'            => 'Pradinis tekstas',
'allmessagescurrent'            => 'Dabartinis tekstas',
'allmessagestext'               => 'Čia pateikiamas sisteminių pranešimų sąrašas, esančių MediaWiki vardų srityje.
Aplankykite [http://www.mediawiki.org/wiki/Localisation „MediaWiki“ lokaliziciją] ir [http://translatewiki.net „translatewiki.net“], jei norite prisidėti prie bendrojo „MediaWiki“ lokalizavimo.',
'allmessagesnotsupportedDB'     => "Šis puslapis nepalaikomas, nes nuostata '''\$wgUseDatabaseMessages''' yra išjungtas.",
'allmessages-filter-legend'     => 'Filtras',
'allmessages-filter'            => 'Filtruoti pagal būseną:',
'allmessages-filter-unmodified' => 'Nepakeisti',
'allmessages-filter-all'        => 'Visi',
'allmessages-filter-modified'   => 'Pakeisti',
'allmessages-prefix'            => 'Filtruoti pagal pradžią:',
'allmessages-language'          => 'Kalba:',
'allmessages-filter-submit'     => 'Rodyti',

# Thumbnails
'thumbnail-more'           => 'Padidinti',
'filemissing'              => 'Dingęs failas',
'thumbnail_error'          => 'Klaida kuriant sumažintą paveikslėlį: $1',
'djvu_page_error'          => 'DjVu puslapis nepasiekiamas',
'djvu_no_xml'              => 'Nepavyksta gauti XML DjVu failui',
'thumbnail_invalid_params' => 'Neleistini miniatiūros parametrai',
'thumbnail_dest_directory' => 'Nepavyksta sukurti paskirties aplanko',
'thumbnail_image-type'     => 'Paveikslėlio tipas nėra palaikomas',
'thumbnail_gd-library'     => 'Nepilna GD bibliotekos konfigūracija: trūksta funkcijos $1',
'thumbnail_image-missing'  => 'Gali būti, kad failo nėra: $1',

# Special:Import
'import'                     => 'Importuoti puslapius',
'importinterwiki'            => 'Tarpprojektinis importas',
'import-interwiki-text'      => 'Pasirinkite projektą ir puslapio pavadinimą importavimui.
Versijų datos ir redaktorių vardai bus išlaikyti.
Visi tarpprojektiniai importo veiksmai yra registruojami  [[Special:Log/import|importo istorijoje]].',
'import-interwiki-source'    => 'Šaltinio wiki projektas/puslapis:',
'import-interwiki-history'   => 'Kopijuoti visas istorijos versijas šiam puslapiui',
'import-interwiki-templates' => 'Įtraukti visus šablonus',
'import-interwiki-submit'    => 'Importuoti',
'import-interwiki-namespace' => 'Paskirties vardų sritis:',
'import-upload-filename'     => 'Failo pavadinimas:',
'import-comment'             => 'Komentaras:',
'importtext'                 => 'Prašome eksportuoti failą iš projekto-šaltinio naudojantis {{ns:special}}:Export priemone, išsaugokite jį savo diske ir įkelkite jį čia.',
'importstart'                => 'Imporuojami puslapiai...',
'import-revision-count'      => '$1 {{PLURAL:$1|versija|versijos|versijų}}',
'importnopages'              => 'Nėra puslapių importavimui.',
'imported-log-entries'       => 'Importuota $1 {{PLURAL:$1|prisijungimo įrašas|prisijungimo įrašai}}.',
'importfailed'               => 'Importavimas nepavyko: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Nežinomas importo šaltinio tipas',
'importcantopen'             => 'Nepavyksta atverti importo failo',
'importbadinterwiki'         => 'Bloga tarpprojektinė nuoroda',
'importnotext'               => 'Tuščia arba jokio teksto',
'importsuccess'              => 'Importas užbaigtas!',
'importhistoryconflict'      => 'Yra konfliktuojanti istorijos versija (galbūt šis puslapis buvo importuotas anksčiau)',
'importnosources'            => 'Nenustatyti transwiki importo šaltiniai, o tiesioginis praeities įkėlimas uždraustas.',
'importnofile'               => 'Nebuvo įkeltas joks importo failas.',
'importuploaderrorsize'      => 'Importavimo failo įkėlimas nepavyko. Failas didesnis nei leidžiamas dydis.',
'importuploaderrorpartial'   => 'Importavimo failo įkėlimas nepavyko. Failas buvo tik dalinai įkeltas.',
'importuploaderrortemp'      => 'Importavimo failo įkėlimas nepavyko. Trūksta laikinojo aplanko.',
'import-parse-failure'       => 'XML importo nagrinėjimo klaida',
'import-noarticle'           => 'Nėra puslapių importuoti!',
'import-nonewrevisions'      => 'Visos versijos buvo importuotos anksčiau.',
'xml-error-string'           => '$1 $2 eilutėje, $3 stulpelyje ($4 baitas): $5',
'import-upload'              => 'Įkelti XML duomenis',
'import-token-mismatch'      => 'Sesijos duomenys prarasti. Bandykite iš naujo.',
'import-invalid-interwiki'   => 'Nepavyko importuoti iš nurodyto wiki projekto.',

# Import log
'importlogpage'                    => 'Importo istorija',
'importlogpagetext'                => 'Administraciniai puslapių importai su keitimų istorija iš kitų wiki projektų.',
'import-logentry-upload'           => 'importuota $1 įkeliant failą',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'import-logentry-interwiki'        => 'tarpprojektinis $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}} iš $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Jūsų naudotojo puslapis',
'tooltip-pt-anonuserpage'         => 'Naudotojo puslapis jūsų IP adresui',
'tooltip-pt-mytalk'               => 'Jūsų aptarimo puslapis',
'tooltip-pt-anontalk'             => 'Pakeitimų aptarimas, darytus naudojant šį IP adresą',
'tooltip-pt-preferences'          => 'Mano nustatymai',
'tooltip-pt-watchlist'            => 'Puslapių sąrašas, kuriuos jūs pasirinkote stebėti.',
'tooltip-pt-mycontris'            => 'Jūsų darytų keitimų sąrašas',
'tooltip-pt-login'                => 'Rekomenduojame prisijungti, nors tai nėra privaloma.',
'tooltip-pt-anonlogin'            => 'Rekomenduojame prisijungti, nors tai nėra privaloma.',
'tooltip-pt-logout'               => 'Atsijungti',
'tooltip-ca-talk'                 => 'Puslapio turinio aptarimas',
'tooltip-ca-edit'                 => 'Jūs galite redaguoti šį puslapį. Nepamirškite paspausti peržiūros mygtuką prieš išsaugodami.',
'tooltip-ca-addsection'           => 'Pradėti naują aptariamą temą',
'tooltip-ca-viewsource'           => 'Puslapis yra užrakintas. Galite pažiūrėti turinį.',
'tooltip-ca-history'              => 'Ankstesnės puslapio versijos.',
'tooltip-ca-protect'              => 'Užrakinti šį puslapį',
'tooltip-ca-unprotect'            => 'Atrakinti šį puslapį',
'tooltip-ca-delete'               => 'Ištrinti šį puslapį',
'tooltip-ca-undelete'             => 'Atkurti puslapį su visais darytais keitimais',
'tooltip-ca-move'                 => 'Pervadinti puslapį',
'tooltip-ca-watch'                => 'Pridėti puslapį į stebimųjų sąrašą',
'tooltip-ca-unwatch'              => 'Pašalinti puslapį iš stebimųjų sąrašo',
'tooltip-search'                  => 'Ieškoti šiame projekte',
'tooltip-search-go'               => 'Eiti į puslapį su tokiu pavadinimu, jei toks yra',
'tooltip-search-fulltext'         => 'Ieškoti puslapių su šiuo tekstu',
'tooltip-p-logo'                  => 'Pradinis puslapis',
'tooltip-n-mainpage'              => 'Eiti į pradinį puslapį',
'tooltip-n-mainpage-description'  => 'Eiti į pradinį puslapį',
'tooltip-n-portal'                => 'Apie projektą, ką galima daryti, kur ką rasti',
'tooltip-n-currentevents'         => 'Raskite naujausią informaciją',
'tooltip-n-recentchanges'         => 'Paskutinių keitimų sąrašas šiame projekte.',
'tooltip-n-randompage'            => 'Įkelti atsitiktinį puslapį',
'tooltip-n-help'                  => 'Vieta, kur rasite rūpimus atsakymus.',
'tooltip-t-whatlinkshere'         => 'Puslapių sąrašas, rodančių į čia',
'tooltip-t-recentchangeslinked'   => 'Paskutiniai keitimai puslapiuose, pasiekiamuose iš šio puslapio',
'tooltip-feed-rss'                => 'Šio puslapio RSS šaltinis',
'tooltip-feed-atom'               => 'Šio puslapio Atom šaltinis',
'tooltip-t-contributions'         => 'Rodyti šio naudotojo keitimų sąrašą',
'tooltip-t-emailuser'             => 'Siųsti laišką šiam naudotojui',
'tooltip-t-upload'                => 'Įkelti failus',
'tooltip-t-specialpages'          => 'Specialiųjų puslapių sąrašas',
'tooltip-t-print'                 => 'Šio puslapio versija spausdinimui',
'tooltip-t-permalink'             => 'Nuolatinė nuoroda į šią puslapio versiją',
'tooltip-ca-nstab-main'           => 'Rodyti puslapio turinį',
'tooltip-ca-nstab-user'           => 'Rodyti naudotojo puslapį',
'tooltip-ca-nstab-media'          => 'Rodyti media puslapį',
'tooltip-ca-nstab-special'        => 'Šis puslapis yra specialusis - jo negalima redaguoti.',
'tooltip-ca-nstab-project'        => 'Rodyti projekto puslapį',
'tooltip-ca-nstab-image'          => 'Rodyti failo puslapį',
'tooltip-ca-nstab-mediawiki'      => 'Rodyti sisteminį pranešimą',
'tooltip-ca-nstab-template'       => 'Rodyti šabloną',
'tooltip-ca-nstab-help'           => 'Rodyti pagalbos puslapį',
'tooltip-ca-nstab-category'       => 'Rodyti kategorijos puslapį',
'tooltip-minoredit'               => 'Pažymėti keitimą kaip smulkų',
'tooltip-save'                    => 'Išsaugoti pakeitimus',
'tooltip-preview'                 => 'Pakeitimų peržiūra, prašome pažiūrėti prieš išsaugant!',
'tooltip-diff'                    => 'Rodo, kokius pakeitimus padarėte tekste.',
'tooltip-compareselectedversions' => 'Žiūrėti dviejų pasirinktų puslapio versijų skirtumus.',
'tooltip-watch'                   => 'Pridėti šį puslapį į stebimųjų sąrašą',
'tooltip-recreate'                => 'Atkurti puslapį nepaisant to, kad jis buvo ištrintas',
'tooltip-upload'                  => 'Pradėti įkėlimą',
'tooltip-rollback'                => 'Atšaukti atmestus šio puslapio pakeitimus į paskutinę versiją vienu spustelėjimu',
'tooltip-undo'                    => '„Anuliuoti“ atmeta šį keitimą ir atveria ankstesnės versijos redagavimo formą.
Leidžia pridėti atmetimo priežastį komentaruose',
'tooltip-preferences-save'        => 'Išsaugoti nustatymus',
'tooltip-summary'                 => 'Įvesti trumpą santrauką',

# Stylesheets
'common.css'   => '/** Čia įdėtas CSS bus taikomas visoms išvaizdoms */',
'monobook.css' => '/* Čia įdėtas CSS bus rodomas Monobook išvaizdos naudotojams */',

# Scripts
'common.js'   => '/* Bet koks čia parašytas JavaScript bus rodomas kiekviename puslapyje kievienam naudotojui. */',
'monobook.js' => '/* Šis JavaScript bus įkeltas tik „MonoBook“ išvaizdos naudotojams. */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metaduomenys yra išjungti šiame serveryje.',
'nocreativecommons' => 'Creative Commons RDF metaduomenys yra išjungti šiame serveryje.',
'notacceptable'     => 'Projekto serveris negali pateikti duomenų formatu, kurį jūsų klientas galėtų skaityti.',

# Attribution
'anonymous'        => '{{SITENAME}} {{PLURAL:$1|anoniminis naudotojas|anoniminiai naudotojai}}',
'siteuser'         => '{{SITENAME}} naudotojas $1',
'anonuser'         => '{{SITENAME}} anoniminis naudotojas $1',
'lastmodifiedatby' => 'Šį puslapį paskutinį kartą redagavo $3 $2, $1.',
'othercontribs'    => 'Paremta $1 darbu.',
'others'           => 'kiti',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|naudotojas|naudotojai}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|anoniminis naudotojas|anoniminiai naudotojai}} $1',
'creditspage'      => 'Puslapio kūrėjai',
'nocredits'        => 'Kūrėjų informacija negalima šiam puslapiui.',

# Spam protection
'spamprotectiontitle' => 'Priešreklaminis filtras',
'spamprotectiontext'  => 'Puslapis, kurį norėjote išsaugoti buvo užblokuotas priešreklaminio filtro. Tai turbūt sukėlė nuoroda į juodajame sąraše esančią svetainę.',
'spamprotectionmatch' => 'Šis tekstas buvo atpažintas priešreklaminio filtro: $1',
'spambot_username'    => 'MediaWiki reklamų šalinimas',
'spam_reverting'      => 'Atkuriama į ankstesnę versiją, neturinčios nuorodų į $1',
'spam_blanking'       => 'Visos versijos turėjo nuorodų į $1, išvaloma',

# Info page
'infosubtitle'   => 'Puslapio informacija',
'numedits'       => 'Keitimų skaičius (puslapis): $1',
'numtalkedits'   => 'Keitimų skaičius (aptarimo puslapis): $1',
'numwatchers'    => 'Stebinčiųjų skaičius: $1',
'numauthors'     => 'Skirtingų autorių skaičius (puslapis): $1',
'numtalkauthors' => 'Skirtingų autorių skaičius (aptarimo puslapis): $1',

# Skin names
'skinname-standard'    => 'Klasikinė',
'skinname-nostalgia'   => 'Nostalgija',
'skinname-cologneblue' => 'Kelno mėlyna',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'Mano išvaizda',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Paprasta',
'skinname-modern'      => 'Moderni',
'skinname-vector'      => 'Vektorinė',

# Math options
'mw_math_png'    => 'Visada formuoti PNG',
'mw_math_simple' => 'HTML paprastais atvejais, kitaip - PNG',
'mw_math_html'   => 'HTML kai įmanoma, kitaip - PNG',
'mw_math_source' => 'Palikti TeX formatą (tekstinėms naršyklėms)',
'mw_math_modern' => 'Rekomenduojama modernioms naršyklėms',
'mw_math_mathml' => 'MathML jei įmanoma (eksperimentinis)',

# Math errors
'math_failure'          => 'Nepavyko apdoroti',
'math_unknown_error'    => 'nežinoma klaida',
'math_unknown_function' => 'nežinoma funkcija',
'math_lexing_error'     => 'leksikos klaida',
'math_syntax_error'     => 'sintaksės klaida',
'math_image_error'      => 'PNG konvertavimas nepavyko; patikrinkite, ar teisingai įdiegta latex, dvips, gs, ir convert',
'math_bad_tmpdir'       => 'Nepavyksta sukurti arba rašyti į matematikos laikinąjį aplanką',
'math_bad_output'       => 'Nepavyksta sukurti arba rašyti į matematikos išvesties aplanką',
'math_notexvc'          => 'Trūksta texvc vykdomojo failo; pažiūrėkite math/README kaip konfigūruoti.',

# Patrolling
'markaspatrolleddiff'                 => 'Žymėti, kad patikrinta',
'markaspatrolledtext'                 => 'Pažymėti, kad puslapis patikrintas',
'markedaspatrolled'                   => 'Pažymėtas kaip patikrintas',
'markedaspatrolledtext'               => 'Pasirinkta [[:$1]] versija pažymėta kaip patikrinta.',
'rcpatroldisabled'                    => 'Paskutinių keitimų tikrinimas išjungtas',
'rcpatroldisabledtext'                => 'Paskutinių keitimų tikrinimo funkcija šiuo metu išjungta.',
'markedaspatrollederror'              => 'Negalima pažymėti, kad patikrinta',
'markedaspatrollederrortext'          => 'Jums reikia nurodyti versiją, kurią pažymėti kaip patikrintą.',
'markedaspatrollederror-noautopatrol' => 'Jums neleidžiama pažymėti savo paties keitimų kaip patikrintų.',

# Patrol log
'patrol-log-page'      => 'Patikrinimo istorija',
'patrol-log-header'    => 'Tai patvirtintų versijų sąrašas.',
'patrol-log-line'      => 'Puslapio „$2“ $1 pažymėta kaip patikrinta $3',
'patrol-log-auto'      => '(automatiškai)',
'patrol-log-diff'      => 'versija $1',
'log-show-hide-patrol' => '$1 patvirtinimų sąrašą',

# Image deletion
'deletedrevision'                 => 'Ištrinta sena versija $1',
'filedeleteerror-short'           => 'Klaida trinant failą: $1',
'filedeleteerror-long'            => 'Įvyko klaidų trinant failą:

$1',
'filedelete-missing'              => 'Failas „$1“ negali būti ištrintas, nes jo nėra.',
'filedelete-old-unregistered'     => 'Nurodytos failo versijos „$1“ nėra duomenų bazėje.',
'filedelete-current-unregistered' => 'Nurodyto failo „$1“ nėra duomenų bazėje.',
'filedelete-archive-read-only'    => 'Serveriui neleidžiama rašyti į archyvo aplanką „$1“.',

# Browsing diffs
'previousdiff' => '← Ankstesnis keitimas',
'nextdiff'     => 'Vėlesnis pakeitimas →',

# Media information
'mediawarning'         => "'''Dėmesio''': Šis failas gali turėti kenksmingą kodą.
Jį paleidus jūsų sistema gali būti pažeista.",
'imagemaxsize'         => "Riboti paveikslėlių dydį:<br />''(failų aprašymo puslapiuose)''",
'thumbsize'            => 'Sumažintų paveikslėlių dydis:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|puslapis|puslapiai|puslapių}}',
'file-info'            => 'failo dydis: $1, MIME tipas: $2',
'file-info-size'       => '$1 × $2 taškų, failo dydis: $3, MIME tipas: $4',
'file-nohires'         => '<small>Geresnė raiška negalima.</small>',
'svg-long-desc'        => 'SVG failas, formaliai $1 × $2 taškų, failo dydis: $3',
'show-big-image'       => 'Pilna raiška',
'show-big-image-thumb' => '<small>Šios peržiūros dydis: $1 × $2 taškų</small>',
'file-info-gif-looped' => 'ciklinis',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kadras|kadrai|kadrų}}',
'file-info-png-looped' => 'ciklinis',
'file-info-png-repeat' => 'grota $1 {{PLURAL:$1|kartą|kartus|kartų}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|kadras|kadrai|kadrų}}',

# Special:NewFiles
'newimages'             => 'Naujausių failų galerija',
'imagelisttext'         => "Žemiau yra '''$1''' {{PLURAL:$1|failo|failų|failų}} sąrašas, surūšiuotas $2.",
'newimages-summary'     => 'Šis specialus puslapis rodo paskiausiai įkeltus failus.',
'newimages-legend'      => 'Filtras',
'newimages-label'       => 'Failo vardas (ar jo dalis):',
'showhidebots'          => '($1 robotus)',
'noimages'              => 'Nėra ką parodyti.',
'ilsubmit'              => 'Ieškoti',
'bydate'                => 'pagal datą',
'sp-newimages-showfrom' => 'Rodyti naujus failus pradedant nuo $1 $2',

# Bad image list
'bad_image_list' => 'Formatas yra toks:

Tik eilutės, prasidedančios *, yra įtraukiamos.
Pirmoji nuoroda eilutėje turi būti nuoroda į blogą failą.
Visos kitos nuorodos toje pačioje eilutėje yra laikomos išimtimis, t. y. puslapiai, kuriuose leidžiama įterpti failą.',

# Metadata
'metadata'          => 'Metaduomenys',
'metadata-help'     => 'Šiame faile yra papildomos informacijos, tikriausiai pridėtos skaitmeninės kameros ar skaitytuvo, naudoto jam sukurti ar perkelti į skaitmeninį formatą. Jei failas buvo pakeistas iš pradinės versijos, kai kurios detalės gali nepilnai atspindėti naują failą.',
'metadata-expand'   => 'Rodyti išplėstinę informaciją',
'metadata-collapse' => 'Slėpti išplėstinę informaciją',
'metadata-fields'   => 'EXIF metaduomenų laukai, nurodyti šiame pranešime, bus įtraukti į paveikslėlio puslapį, kai metaduomenų lentelė bus suskleista. Pagal nutylėjimą kiti laukai bus paslėpti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Plotis',
'exif-imagelength'                 => 'Aukštis',
'exif-bitspersample'               => 'Bitai komponente',
'exif-compression'                 => 'Suspaudimo tipas',
'exif-photometricinterpretation'   => 'Taškų struktūra',
'exif-orientation'                 => 'Pasukimas',
'exif-samplesperpixel'             => 'Komponentų skaičius',
'exif-planarconfiguration'         => 'Duomenų išdėstymas',
'exif-ycbcrsubsampling'            => 'Y iki C atrankos santykis',
'exif-ycbcrpositioning'            => 'Y ir C pozicija',
'exif-xresolution'                 => 'Horizontali raiška',
'exif-yresolution'                 => 'Vertikali raiška',
'exif-resolutionunit'              => 'X ir Y raiškos matavimo vienetai',
'exif-stripoffsets'                => 'Paveikslėlio duomenų vieta',
'exif-rowsperstrip'                => 'Eilių skaičius juostoje',
'exif-stripbytecounts'             => 'Baitai suspaustje juostoje',
'exif-jpeginterchangeformat'       => 'JPEG SOI pozicija',
'exif-jpeginterchangeformatlength' => 'JPEG duomenų baitai',
'exif-transferfunction'            => 'Perkėlimo funkcija',
'exif-whitepoint'                  => 'Balto taško chromatiškumas',
'exif-primarychromaticities'       => 'Pagrindinių spalvų chromiškumas',
'exif-ycbcrcoefficients'           => 'Spalvų pristatym matricos matricos koeficientai',
'exif-referenceblackwhite'         => 'Juodos ir baltos poros nuorodos reikšmės',
'exif-datetime'                    => 'Failo keitimo data ir laikas',
'exif-imagedescription'            => 'Paveikslėlio pavadinimas',
'exif-make'                        => 'Kameros gamintojas',
'exif-model'                       => 'Kameros modelis',
'exif-software'                    => 'Naudota programinė įranga',
'exif-artist'                      => 'Autorius',
'exif-copyright'                   => 'Autorystės teisių savininkas',
'exif-exifversion'                 => 'Exif versija',
'exif-flashpixversion'             => 'Palaikoma Flashpix versija',
'exif-colorspace'                  => 'Spalvų pristatymas',
'exif-componentsconfiguration'     => 'kiekvieno komponento reikšmė',
'exif-compressedbitsperpixel'      => 'Paveikslėlio suspaudimo režimas',
'exif-pixelydimension'             => 'Leistinas paveikslėlio plotis',
'exif-pixelxdimension'             => 'Leistinas paveikslėlio aukštis',
'exif-makernote'                   => 'Gamintojo pastabos',
'exif-usercomment'                 => 'Naudotojo komentarai',
'exif-relatedsoundfile'            => 'Susijusi garso byla',
'exif-datetimeoriginal'            => 'Duomenų generavimo data ir laikas',
'exif-datetimedigitized'           => 'Pervedimo į skaitmeninį formatą data ir laikas',
'exif-subsectime'                  => 'Datos ir laiko sekundės dalys',
'exif-subsectimeoriginal'          => 'Duomenų generavimo datos ir laiko sekundės dalys',
'exif-subsectimedigitized'         => 'Pervedimo į skaitmeninį formatą datos ir laiko sekundės dalys',
'exif-exposuretime'                => 'Išlaikymo laikas',
'exif-exposuretime-format'         => '$1 sek. ($2)',
'exif-fnumber'                     => 'F numeris',
'exif-exposureprogram'             => 'Išlaikymo programa',
'exif-spectralsensitivity'         => 'Spektrinis jautrumas',
'exif-isospeedratings'             => 'ISO greitis',
'exif-oecf'                        => 'Optoelektronikos konversijos daugiklis',
'exif-shutterspeedvalue'           => 'Užrakto greitis',
'exif-aperturevalue'               => 'Diafragma',
'exif-brightnessvalue'             => 'Šviesumas',
'exif-exposurebiasvalue'           => 'Išlaikymo paklaida',
'exif-maxaperturevalue'            => 'Mažiausias lešio F numeris',
'exif-subjectdistance'             => 'Objekto atstumas',
'exif-meteringmode'                => 'Matavimo režimas',
'exif-lightsource'                 => 'Šviesos šaltinis',
'exif-flash'                       => 'Blykstė',
'exif-focallength'                 => 'Židinio nuotolis',
'exif-subjectarea'                 => 'Objekto zona',
'exif-flashenergy'                 => 'Blykstės energija',
'exif-spatialfrequencyresponse'    => 'Erdvės dažnio atsakas',
'exif-focalplanexresolution'       => 'Židinio projekcijos X raiška',
'exif-focalplaneyresolution'       => 'Židinio projekcijos Y raiška',
'exif-focalplaneresolutionunit'    => 'Židinio projekcijos raiškos matavimo vienetai',
'exif-subjectlocation'             => 'Objekto vieta',
'exif-exposureindex'               => 'Išlaikymo indeksas',
'exif-sensingmethod'               => 'Jutimo režimas',
'exif-filesource'                  => 'Failo šaltinis',
'exif-scenetype'                   => 'Scenos tipas',
'exif-cfapattern'                  => 'CFA raštas',
'exif-customrendered'              => 'Pasirinktinis vaizdo apdorojimas',
'exif-exposuremode'                => 'Išlaikymo režimas',
'exif-whitebalance'                => 'Baltumo balansas',
'exif-digitalzoomratio'            => 'Skaitmeninio priartinimo koeficientas',
'exif-focallengthin35mmfilm'       => 'Židinio nuotolis 35 mm juostoje',
'exif-scenecapturetype'            => 'Scenos fiksavimo tipas',
'exif-gaincontrol'                 => 'Scenos kontrolė',
'exif-contrast'                    => 'Kontrastas',
'exif-saturation'                  => 'Sodrumas',
'exif-sharpness'                   => 'Aštrumas',
'exif-devicesettingdescription'    => 'Įrenginio nustatymų aprašas',
'exif-subjectdistancerange'        => 'Objekto nuotolis',
'exif-imageuniqueid'               => 'Unikalusis paveikslėlio ID',
'exif-gpsversionid'                => 'GPS versija',
'exif-gpslatituderef'              => 'Šiaurės ar pietų platuma',
'exif-gpslatitude'                 => 'Platuma',
'exif-gpslongituderef'             => 'Rytų ar vakarų ilguma',
'exif-gpslongitude'                => 'Ilguma',
'exif-gpsaltituderef'              => 'Aukščio nuoroda',
'exif-gpsaltitude'                 => 'Aukštis',
'exif-gpstimestamp'                => 'GPS laikas (atominis laikrodis)',
'exif-gpssatellites'               => 'Palydovai, naudoti matavimui',
'exif-gpsstatus'                   => 'Gaviklio būsena',
'exif-gpsmeasuremode'              => 'Matavimo režimas',
'exif-gpsdop'                      => 'Matavimo tikslumas',
'exif-gpsspeedref'                 => 'Greičio vienetai',
'exif-gpsspeed'                    => 'GPS gaviklio greitis',
'exif-gpstrackref'                 => 'Nuoroda judėjimo krypčiai',
'exif-gpstrack'                    => 'Judėjimo kryptis',
'exif-gpsimgdirectionref'          => 'Nuoroda vaizdo krypčiai',
'exif-gpsimgdirection'             => 'Nuotraukos kryptis',
'exif-gpsmapdatum'                 => 'Panaudoti geodeziniai apžvalgos duomenys',
'exif-gpsdestlatituderef'          => 'Nuoroda paskirties platumai',
'exif-gpsdestlatitude'             => 'Paskirties platuma',
'exif-gpsdestlongituderef'         => 'Nuoroda paskirties ilgumai',
'exif-gpsdestlongitude'            => 'Paskirties ilguma',
'exif-gpsdestbearingref'           => 'Nuoroda į paskirties pelengą',
'exif-gpsdestbearing'              => 'Paskirties pelengas',
'exif-gpsdestdistanceref'          => 'Nuoroda atstumui iki paskirties',
'exif-gpsdestdistance'             => 'Atstumas iki paskirties',
'exif-gpsprocessingmethod'         => 'GPS apdorojimo metodo pavadinimas',
'exif-gpsareainformation'          => 'GPS zonos pavadinimas',
'exif-gpsdatestamp'                => 'GPS data',
'exif-gpsdifferential'             => 'GPS diferiancialo pataisymas',

# EXIF attributes
'exif-compression-1' => 'Nesuspausta',

'exif-unknowndate' => 'Nežinoma data',

'exif-orientation-1' => 'Standartinis',
'exif-orientation-2' => 'Apversta horizontaliai',
'exif-orientation-3' => 'Pasukta 180°',
'exif-orientation-4' => 'Apversta vertikaliai',
'exif-orientation-5' => 'Pasukta 90° prieš laikrodžio rodyklę ir apversta vertikaliai',
'exif-orientation-6' => 'Pasukta 90° laikrodžio rodyklės kryptimi',
'exif-orientation-7' => 'Pasukta 90° laikrodžio rodyklės kryptimi ir apversta vertikaliai',
'exif-orientation-8' => 'Pasukta 90° prieš laikrodžio rodyklę',

'exif-planarconfiguration-1' => 'stambusis formatas',
'exif-planarconfiguration-2' => 'plokštuminis formatas',

'exif-xyresolution-i' => '$1 taškai colyje',
'exif-xyresolution-c' => '$1 taškai centimetre',

'exif-componentsconfiguration-0' => 'neegzistuoja',

'exif-exposureprogram-0' => 'Nenurodyta',
'exif-exposureprogram-1' => 'Rankinė',
'exif-exposureprogram-2' => 'Paprasta programa',
'exif-exposureprogram-3' => 'Diafragmos pirmenybė',
'exif-exposureprogram-4' => 'Užrakto pirmenybė',
'exif-exposureprogram-5' => 'Kūrybos programa (linkusi į lauko gylį)',
'exif-exposureprogram-6' => 'Veiksmo programa (linkusi link greito užrakto greičio)',
'exif-exposureprogram-7' => 'Portreto režimas (nuotraukoms iš arti nepabrėžiant fono)',
'exif-exposureprogram-8' => 'Peizažo režimas (peizažo nuotraukoms pabrėžiant foną)',

'exif-subjectdistance-value' => '$1 metrų',

'exif-meteringmode-0'   => 'Nežinoma',
'exif-meteringmode-1'   => 'Vidurkis',
'exif-meteringmode-2'   => 'Centruotas vidurkis',
'exif-meteringmode-3'   => 'Taškas',
'exif-meteringmode-4'   => 'Daugiataškis',
'exif-meteringmode-5'   => 'Raštas',
'exif-meteringmode-6'   => 'Dalinis',
'exif-meteringmode-255' => 'Kita',

'exif-lightsource-0'   => 'Nežinomas',
'exif-lightsource-1'   => 'Dienos šviesa',
'exif-lightsource-2'   => 'Fluorescentinis',
'exif-lightsource-3'   => 'Volframas (kaitinamoji lempa)',
'exif-lightsource-4'   => 'Blykstė',
'exif-lightsource-9'   => 'Giedras oras',
'exif-lightsource-10'  => 'Debesuotas oras',
'exif-lightsource-11'  => 'Šešėlis',
'exif-lightsource-12'  => 'Dienos šviesos fluorescentinis (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dienos baltumo fluorescentinis (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Šalto baltumo fluorescentinis (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Baltas fluorescentinis (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standartinis apšvietimas A',
'exif-lightsource-18'  => 'Standartinis apšvietimas B',
'exif-lightsource-19'  => 'Standartinis apšvietimas C',
'exif-lightsource-24'  => 'ISO studijos volframas',
'exif-lightsource-255' => 'Kitas šviesos šaltinis',

# Flash modes
'exif-flash-fired-0'    => 'Blykstė nemirktelėjo',
'exif-flash-fired-1'    => 'Blykstė mirktelėjo',
'exif-flash-return-0'   => 'jokios blyksčių grįžties aptikimo funkcijos',
'exif-flash-return-2'   => 'blykstės grįžtamoji šviesa neaptikta',
'exif-flash-return-3'   => 'blykstės grįžtamoji šviesa aptikta',
'exif-flash-mode-1'     => 'priverstinė blykstė',
'exif-flash-mode-2'     => 'priverstinis blykstės sulaikymas',
'exif-flash-mode-3'     => 'automatinis režimas',
'exif-flash-function-1' => 'Be blykstės funkcijos',
'exif-flash-redeye-1'   => 'raudonų akių šalinimo režimas',

'exif-focalplaneresolutionunit-2' => 'coliai',

'exif-sensingmethod-1' => 'Nenurodytas',
'exif-sensingmethod-2' => 'Vienalustis spalvų zonos jutiklis',
'exif-sensingmethod-3' => 'Dvilustis spalvų zonos jutiklis',
'exif-sensingmethod-4' => 'Trilustis spalvų zonos jutiklis',
'exif-sensingmethod-5' => 'Nuoseklusis spalvų zonos jutiklis',
'exif-sensingmethod-7' => 'Trilinijinis jutiklis',
'exif-sensingmethod-8' => 'Spalvų nuoseklusis linijinis jutiklis',

'exif-scenetype-1' => 'Tiesiogiai fotografuotas vaizdas',

'exif-customrendered-0' => 'Standartinis procesas',
'exif-customrendered-1' => 'Pasirinktinis procesas',

'exif-exposuremode-0' => 'Automatinis išlaikymas',
'exif-exposuremode-1' => 'Rankinis išlaikymas',
'exif-exposuremode-2' => 'Automatinis skliaustas',

'exif-whitebalance-0' => 'Automatinis baltumo balansas',
'exif-whitebalance-1' => 'Rankinis baltumo balansas',

'exif-scenecapturetype-0' => 'Paprastas',
'exif-scenecapturetype-1' => 'Peizažas',
'exif-scenecapturetype-2' => 'Portretas',
'exif-scenecapturetype-3' => 'Nakties vaizdas',

'exif-gaincontrol-0' => 'Jokia',
'exif-gaincontrol-1' => 'Nedidelis pakėlimas',
'exif-gaincontrol-2' => 'Didelis pakėlimas',
'exif-gaincontrol-3' => 'Mažas nuleidimas',
'exif-gaincontrol-4' => 'Didelis nuleidimas',

'exif-contrast-0' => 'Paprastas',
'exif-contrast-1' => 'Mažas',
'exif-contrast-2' => 'Didelis',

'exif-saturation-0' => 'Paprastas',
'exif-saturation-1' => 'Mažas sodrumas',
'exif-saturation-2' => 'Didelis sodrumas',

'exif-sharpness-0' => 'Paprastas',
'exif-sharpness-1' => 'Mažas',
'exif-sharpness-2' => 'Didelis',

'exif-subjectdistancerange-0' => 'Nežinomas',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Artimas vaizdas',
'exif-subjectdistancerange-3' => 'Tolimas vaizdas',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Šiaurės platuma',
'exif-gpslatitude-s' => 'Pietų platuma',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Rytų ilguma',
'exif-gpslongitude-w' => 'Vakarų ilguma',

'exif-gpsstatus-a' => 'Matavimas vykdyme',
'exif-gpsstatus-v' => 'Matuojamas programinis sąveikumas',

'exif-gpsmeasuremode-2' => 'Dvimatis matavimas',
'exif-gpsmeasuremode-3' => 'Trimatis matavimas',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometrai per valandą',
'exif-gpsspeed-m' => 'Mylios per valandą',
'exif-gpsspeed-n' => 'Mazgai',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tikroji kryptis',
'exif-gpsdirection-m' => 'Magnetinė kryptis',

# External editor support
'edit-externally'      => 'Atverti išoriniame redaktoriuje',
'edit-externally-help' => '(Norėdami gauti daugiau informacijos, žiūrėkite [http://www.mediawiki.org/wiki/Manual:External_editors diegimo instrukcijas])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'visos',
'imagelistall'     => 'visi',
'watchlistall2'    => 'visus',
'namespacesall'    => 'visos',
'monthsall'        => 'visi',
'limitall'         => 'visi',

# E-mail address confirmation
'confirmemail'              => 'Patvirtinkite el. pašto adresą',
'confirmemail_noemail'      => 'Jūs neturite nurodę teisingo el. pašto adreso [[Special:Preferences|savo nustatymuose]].',
'confirmemail_text'         => 'Šiame projekte būtina patvirtinti el. pašto adresą prieš naudojant el. pašto funkcijas. Spustelkite žemiau esantį mygtuką,
kad jūsų el. pašto adresu būtų išsiųstas patvirtinimo kodas.
Laiške bus atsiųsta nuoroda su kodu, kuria nuėjus, el. pašto adresas bus patvirtintas.',
'confirmemail_pending'      => 'Patvirtinimo kodas jums jau nusiųstas; jei neseniai sukūrėte savo paskyrą, jūs turėtumėte palaukti jo dar kelias minutes prieš prašydami naujo kodo.',
'confirmemail_send'         => 'Išsiųsti patvirtinimo kodą',
'confirmemail_sent'         => 'Patvirtinimo laiškas išsiųstas.',
'confirmemail_oncreate'     => 'Patvirtinimo kodas buvo išsiųstas jūsų el. pašto adresu.
Šis kodas nėra būtinas, kad prisijungtumėte, bet jums reikės jį duoti prieš įjungiant el. pašto paslaugas projekte.',
'confirmemail_sendfailed'   => '{{SITENAME}} neišsiuntė patvirtinamojo laiško. Patikrinkite, ar adrese nėra klaidingų simbolių.

Pašto tarnyba atsakė: $1',
'confirmemail_invalid'      => 'Neteisingas patvirtinimo kodas. Kodo galiojimas gali būti jau pasibaigęs.',
'confirmemail_needlogin'    => 'Jums reikia $1, kad patvirtintumėte savo el. pašto adresą.',
'confirmemail_success'      => 'Jūsų el. pašto adresas patvirtintas. Dabar galite prisijungti ir mėgautis projektu.',
'confirmemail_loggedin'     => 'Jūsų el. pašto adresas patvirtintas.',
'confirmemail_error'        => 'Patvirtinimo metu įvyko neatpažinta klaida.',
'confirmemail_subject'      => '{{SITENAME}} el. pašto adreso patvirtinimas',
'confirmemail_body'         => 'Kažkas, tikriausiai jūs IP adresu $1, užregistravo
paskyrą „$2“ susietą su šiuo el. pašto adresu projekte {{SITENAME}}.

Kad patvirtintumėte, kad ši dėžutė tikrai priklauso jums, ir aktyvuotumėte
el. pašto paslaugas projekte {{SITENAME}}, atverkite šią nuorodą savo naršyklėje:

$3

Jei paskyrą registravote *ne* jūs, eikite šia nuoroda,
kad atšauktumėte el. pašto adreso patvirtinimą:

$5

Patvirtinimo kodas baigs galioti $4.',
'confirmemail_body_changed' => 'Kažkas, tikriausiai jūs IP adresu $1, projekte {{SITENAME}}
pakeitė paskyros „$2“ el. pašto adresą.

Kad patvirtintumėte, kad ši dėžutė tikrai priklauso jums, ir vėl aktyvuotumėte
el. pašto paslaugas projekte {{SITENAME}}, atverkite šią nuorodą savo naršyklėje:

$3

Jei paskyra jums *nepriklauso*, eikite šia nuoroda,
kad atšauktumėte el. pašto adreso patvirtinimą:

$5

Patvirtinimo kodas baigs galioti $4.',
'confirmemail_invalidated'  => 'El. pašto adreso patvirtinimas atšauktas',
'invalidateemail'           => 'El. pašto patvirtinimo atšaukimas',

# Scary transclusion
'scarytranscludedisabled' => '[Tarpprojektinis įterpimas yra išjungtas]',
'scarytranscludefailed'   => '[Šablono gavimas iš $1 nepavyko]',
'scarytranscludetoolong'  => '[URL per ilgas]',

# Trackbacks
'trackbackbox'      => 'Šio puslapio „Trackback“ nuorodos:<br />
$1',
'trackbackremove'   => '([$1 Trinti])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback buvo sėkmingai ištrintas.',

# Delete conflict
'deletedwhileediting' => 'Dėmesio: Šis puslapis ištrintas po to, kai pradėjote redaguoti!',
'confirmrecreate'     => "Naudotojas [[User:$1|$1]] ([[User talk:$1|aptarimas]]) ištrynė šį puslapį po to, kai pradėjote jį redaguoti. Trynimo priežastis:
: ''$2''
Prašome patvirtinti, kad tikrai norite iš naujo sukurti puslapį.",
'recreate'            => 'Atkurti',

# action=purge
'confirm_purge_button' => 'Gerai',
'confirm-purge-top'    => 'Išvalyti šio puslapio podėlį?',
'confirm-purge-bottom' => 'Puslapio perkūrimas išvalo podėlį ir priverčia sugeneruoti pačią naujausią puslapio versiją.',

# Multipage image navigation
'imgmultipageprev' => '← ankstesnis puslapis',
'imgmultipagenext' => 'kitas puslapis →',
'imgmultigo'       => 'Eiti!',
'imgmultigoto'     => 'Eitį į puslapį $1',

# Table pager
'ascending_abbrev'         => 'didėjanti tvarka',
'descending_abbrev'        => 'mažėjanti tvarka',
'table_pager_next'         => 'Kitas puslapis',
'table_pager_prev'         => 'Ankstesnis puslapis',
'table_pager_first'        => 'Pirmas puslapis',
'table_pager_last'         => 'Paskutinis puslapis',
'table_pager_limit'        => 'Rodyti po $1 puslapyje',
'table_pager_limit_label'  => 'Elementai puslapyje:',
'table_pager_limit_submit' => 'Rodyti',
'table_pager_empty'        => 'Jokių rezultatų',

# Auto-summaries
'autosumm-blank'   => 'Šalinamas visas turinys iš puslapio',
'autosumm-replace' => 'Puslapis keičiamas su „$1“',
'autoredircomment' => 'Nukreipiama į [[$1]]',
'autosumm-new'     => 'Naujas puslapis: $1',

# Size units
'size-kilobytes' => '$1 KiB',
'size-megabytes' => '$1 MiB',
'size-gigabytes' => '$1 GiB',

# Live preview
'livepreview-loading' => 'Įkeliama…',
'livepreview-ready'   => 'Įkeliama… Paruošta!',
'livepreview-failed'  => 'Nepavyko tiesioginė peržiūra! Pamėginkite paprastąją peržiūrą.',
'livepreview-error'   => 'Nepavyko prisijungti: $1 „$2“. Pamėginkite paprastąją peržiūrą.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pakeitimai, naujesni nei $1 {{PLURAL:$1|sekundė|sekundės|sekundžių}}, šiame sąraše gali būti nerodomi.',
'lag-warn-high'   => 'Dėl didelio duomenų bazės atsilikimo pakeitimai, naujesni nei $1 {{PLURAL:$1|sekundė|sekundės|sekundžių}}, šiame sąraše gali būti nerodomi.',

# Watchlist editor
'watchlistedit-numitems'       => 'Jūsų stebimųjų sąraše yra $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}} neskaičiuojant aptarimų puslapių.',
'watchlistedit-noitems'        => 'Jūsų stebimųjų sąraše nėra jokių puslapių.',
'watchlistedit-normal-title'   => 'Redaguoti stebimųjų sąrašą',
'watchlistedit-normal-legend'  => 'Šalinti puslapius iš stebimųjų sąrašo',
'watchlistedit-normal-explain' => 'Žemiau yra rodomi puslapiai jūsų stebimųjų sąraše.
Norėdami pašalinti puslapį, prie jo uždėkite varnelė ir paspauskite „{{int:Watchlistedit-normal-submit}}“.
Jūs taip pat galite [[Special:Watchlist/raw|redaguoti grynąjį stebimųjų sąrašą]].',
'watchlistedit-normal-submit'  => 'Šalinti puslapius',
'watchlistedit-normal-done'    => '$1 {{PLURAL:$1|puslapis buvo pašalintas|puslapiai buvo pašalinti|puslapių buvo pašalinta}} iš jūsų stebimųjų sąrašo:',
'watchlistedit-raw-title'      => 'Redaguoti grynąjį stebimųjų sąrašą',
'watchlistedit-raw-legend'     => 'Redaguoti grynąjį stebimųjų sąrašą',
'watchlistedit-raw-explain'    => 'Žemiau rodomi puslapiai jūsų stebimųjų sąraše, ir gali būti pridėti į ar pašalinti iš sąrašo;
vienas puslapis eilutėje.
Baigę paspauskite „{{int:Watchlistedit-raw-submit}}“.
Jūs taip pat galite [[Special:Watchlist/edit|naudoti standartinį redaktorių]].',
'watchlistedit-raw-titles'     => 'Puslapiai:',
'watchlistedit-raw-submit'     => 'Atnaujinti stebimųjų sąrašą',
'watchlistedit-raw-done'       => 'Jūsų stebimųjų sąrašas buvo atnaujintas.',
'watchlistedit-raw-added'      => '$1 {{PLURAL:$1|puslapis buvo pridėtas|puslapiai buvo pridėti|puslapių buvo pridėta}}:',
'watchlistedit-raw-removed'    => '$1 {{PLURAL:$1|puslapis buvo pašalintas|puslapiai buvo pašalinti|puslapių buvo pašalinta}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Rodyti susijusius keitimus',
'watchlisttools-edit' => 'Rodyti ir redaguoti stebimųjų sąrašą',
'watchlisttools-raw'  => 'Redaguoti grynąjį sąrašą',

# Core parser functions
'unknown_extension_tag' => 'Nežinoma priedo žymė „$1“',
'duplicate-defaultsort' => 'Įspėjimas: Numatytasis rikiavimo raktas „$2“ pakeičia ankstesnį numatytąjį rikiavimo raktą „$1“.',

# Special:Version
'version'                          => 'Versija',
'version-extensions'               => 'Įdiegti priedai',
'version-specialpages'             => 'Specialieji puslapiai',
'version-parserhooks'              => 'Analizatoriaus gaudliai',
'version-variables'                => 'Kintamieji',
'version-other'                    => 'Kita',
'version-mediahandlers'            => 'Daugialypės terpės grotuvai',
'version-hooks'                    => 'Gaudliai',
'version-extension-functions'      => 'Papildomos funkcijos',
'version-parser-extensiontags'     => 'Analizatoriaus papildomosios gairės',
'version-parser-function-hooks'    => 'Analizatoriaus funkciniai gaudliai',
'version-skin-extension-functions' => 'Išvaizdos papildinių funkcijos',
'version-hook-name'                => 'Gaudlio pavadinimas',
'version-hook-subscribedby'        => 'Užsakyta',
'version-version'                  => '(Versija $1)',
'version-license'                  => 'Licencija',
'version-poweredby-credits'        => "Šis projektas naudoja '''[http://www.mediawiki.org/ MediaWiki]''', autorystės teisės © 2001-$1 $2.",
'version-poweredby-others'         => 'kiti',
'version-license-info'             => 'MediaWiki yra nemokama programinė įranga; galite ją platinti ir/arba modifikuoti pagal GNU General Public License, kurią publikuoja Free Software Foundation; taikoma 2-oji licenzijos versija arba (Jūsų pasirinkimu) bet kuri vėlesnė versija. 

MediaWiki yra platinama su viltimi, kad ji bus naudinga, bet BE JOKIOS GARANTIJOS; be jokios numanomos PARDAVIMO arba TINKAMUMO TAM TIKRAM TIKSLUI garantijos. Daugiau informacijos galite sužinoti GNU General Public License. 

Jūs turėjote gauti [{{SERVER}}{{SCRIPTPATH}}/COPYING GNU General Public License kopiją] kartu su šia programa, jei ne, rašykite Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, JAV arba [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html perskaitykite ją internete].',
'version-software'                 => 'Įdiegta programinė įranga',
'version-software-product'         => 'Produktas',
'version-software-version'         => 'Versija',

# Special:FilePath
'filepath'         => 'Failo kelias',
'filepath-page'    => 'Failas:',
'filepath-submit'  => 'Kelias',
'filepath-summary' => 'Šis specialusis puslapis parašo pilną kelią iki failo. Paveikslėliai yra rodomi pilna raiška, kiti failų tipai paleidžiami tiesiogiai su jų susietąja programa.

Įveskite failo pavadinimą be „{{ns:file}}:“ priedėlio.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Ieškoti dublikuotų failų',
'fileduplicatesearch-summary'  => 'Pasikartojančių failų paieška pagal jų kontrolinę sumą.',
'fileduplicatesearch-legend'   => 'Ieškoti dublikatų',
'fileduplicatesearch-filename' => 'Failo vardas:',
'fileduplicatesearch-submit'   => 'Ieškoti',
'fileduplicatesearch-info'     => '$1 × $2 pikselių<br />Failo dydis: $3<br />MIME tipas: $4',
'fileduplicatesearch-result-1' => 'Failas „$1“ neturi identiškų dublikatų.',
'fileduplicatesearch-result-n' => 'Šis failas „$1“ turi $2 {{PLURAL:$2|identišką dublikatą|identiškus dublikatus|identiškų dublikatų}}.',

# Special:SpecialPages
'specialpages'                   => 'Specialieji puslapiai',
'specialpages-note'              => '----
* Normalūs specialieji puslapiai.
* <strong class="mw-specialpagerestricted">Apriboti specialieji puslapiai.</strong>',
'specialpages-group-maintenance' => 'Sistemos palaikymo pranešimai',
'specialpages-group-other'       => 'Kiti specialieji puslapiai',
'specialpages-group-login'       => 'Prisijungimas / Registracija',
'specialpages-group-changes'     => 'Naujausi keitimai ir istorijos',
'specialpages-group-media'       => 'Informacija apie failus ir jų pakrovimas',
'specialpages-group-users'       => 'Naudotojai ir teisės',
'specialpages-group-highuse'     => 'Plačiai naudojami puslapiai',
'specialpages-group-pages'       => 'Puslapių sąrašas',
'specialpages-group-pagetools'   => 'Puslapių priemonės',
'specialpages-group-wiki'        => 'Wiki duomenys ir priemonės',
'specialpages-group-redirects'   => 'Specialieji nukreipimo puslapiai',
'specialpages-group-spam'        => 'Šlamšto valdymo priemonės',

# Special:BlankPage
'blankpage'              => 'Tuščias puslapis',
'intentionallyblankpage' => 'Šis puslapis specialiai paliktas tuščias',

# External image whitelist
'external_image_whitelist' => ' #Palikite šią eilutę, tokią kokia yra <pre>
#Įrašykite standartinių išraiškų fragmentus (tik dalį tarp //)
#Juos bus bandoma sutapdinti su išorinių paveikslėlių adresais
#Tie, kurie sutaps, bus rodomi kaip paveikslėliai, o kiti bus rodomi tik kaip nuorodos
#Raidžių dydis nėra svarbus
#Eilutės, prasidedančios # yra komentarai

#Įterpkite visus standartinių išraiškų fragmentus prieš šią eilutę. Palikite šią eilutę, tokią kokia yra </pre>',

# Special:Tags
'tags'                    => 'Leistinos keitimų žymės',
'tag-filter'              => '[[Special:Tags|Žymų]] filtras:',
'tag-filter-submit'       => 'Filtras',
'tags-title'              => 'Žymos',
'tags-intro'              => 'Šiame puslapyje yra žymų, kuriomis programinė įranga gali pažymėti keitimus, sąrašas bei jų reikšmės.',
'tags-tag'                => 'Žymos pavadinimas',
'tags-display-header'     => 'Išvaizda keitimų sąrašuose',
'tags-description-header' => 'Visas reikšmės aprašymas',
'tags-hitcount-header'    => 'Pažymėti pakeitimai',
'tags-edit'               => 'taisyti',
'tags-hitcount'           => '$1 {{PLURAL:$1|pakeitimas|pakeitimai|pakeitimų}}',

# Special:ComparePages
'comparepages'     => 'Palyginti puslapius',
'compare-selector' => 'Palyginti puslapio keitimus',
'compare-page1'    => 'Puslapis 1',
'compare-page2'    => 'Puslapis 2',
'compare-rev1'     => 'Pirma versija',
'compare-rev2'     => 'Antra versija',
'compare-submit'   => 'Palyginti',

# Database error messages
'dberr-header'      => 'Ši svetainė turi problemų.',
'dberr-problems'    => 'Atsiprašome! Svetainei iškilo techninių problemų.',
'dberr-again'       => 'Palaukite kelias minutes ir perkraukite puslapį.',
'dberr-info'        => '(Nepavyksta pasiekti duomenų bazės serverio: $1)',
'dberr-usegoogle'   => 'Šiuo metu jūs galite ieškoti per „Google“.',
'dberr-outofdate'   => 'Mūsų turinio kopijos ten gali būti pasenusios.',
'dberr-cachederror' => 'Tai prašomo puslapio išsaugota kopija, ji gali būti pasenusi.',

# HTML forms
'htmlform-invalid-input'       => 'Yra problemų su jūsų įvestimi',
'htmlform-select-badoption'    => 'Reikšmė, kurią nurodėte, nėra leistina.',
'htmlform-int-invalid'         => 'Reikšmė, kurią nurodėte, nėra sveikasis skaičius.',
'htmlform-float-invalid'       => 'Nurodyta reikšmė nėra skaičius.',
'htmlform-int-toolow'          => 'Reikšmė, kurią nurodėte, yra mažesnė nei mažiausia leistina $1',
'htmlform-int-toohigh'         => 'Reikšmė, kurią nurodėte, yra didesnė nei didžiausia leistina $1',
'htmlform-required'            => 'Ši vertė būtina',
'htmlform-submit'              => 'Siųsti',
'htmlform-reset'               => 'Atšaukti pakeitimus',
'htmlform-selectorother-other' => 'Kita',

# SQLite database support
'sqlite-has-fts' => '$1 su visatekstės paieškos palaikymu',
'sqlite-no-fts'  => '$1 be visatekstės paieškos palaikymo',

);
