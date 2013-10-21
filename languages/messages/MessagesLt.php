<?php
/** Lithuanian (lietuvių)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Audriusa
 * @author Auwris
 * @author Break Through Pain
 * @author Dark Eagle
 * @author Eitvys200
 * @author Garas
 * @author Geitost
 * @author Homo
 * @author Hugo.arg
 * @author Ignas693
 * @author Kaganer
 * @author Mantak111
 * @author Matasg
 * @author Meno25
 * @author Ola
 * @author Pdxx
 * @author Perkunas
 * @author Pėstininkas
 * @author Siggis
 * @author Tomasdd
 * @author Urhixidur
 * @author Vilius2001
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

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Naudotojas', 'female' => 'Naudotoja' ),
	NS_USER_TALK => array( 'male' => 'Naudotojo_aptarimas', 'female' => 'Naudotojos_aptarimas' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Visi_pranešimai' ),
	'Allpages'                  => array( 'Visi_puslapiai' ),
	'Ancientpages'              => array( 'Seniausi_puslapiai' ),
	'Blankpage'                 => array( 'Tuščias_puslapis' ),
	'Block'                     => array( 'Blokuoti_IP' ),
	'Blockme'                   => array( 'Užblokuoti_mane' ),
	'Booksources'               => array( 'Knygų_šaltiniai' ),
	'BrokenRedirects'           => array( 'Peradresavimai_į_niekur' ),
	'Categories'                => array( 'Kategorijos' ),
	'ChangePassword'            => array( 'Slaptažodžio_atstatymas' ),
	'Confirmemail'              => array( 'Elektroninio_pašto_patvirtinimas' ),
	'Contributions'             => array( 'Indėlis' ),
	'CreateAccount'             => array( 'Sukurti_paskyrą' ),
	'Deadendpages'              => array( 'Puslapiai-aklavietės' ),
	'DeletedContributions'      => array( 'Ištrintas_indėlis' ),
	'Disambiguations'           => array( 'Nukreipiamieji' ),
	'DoubleRedirects'           => array( 'Dvigubi_peradesavimai' ),
	'Emailuser'                 => array( 'Rašyti_laišką' ),
	'Export'                    => array( 'Eksportas' ),
	'Fewestrevisions'           => array( 'Mažiausiai_keičiami' ),
	'FileDuplicateSearch'       => array( 'Failo_dublikatų_paieška' ),
	'Filepath'                  => array( 'Kelias_iki_failo' ),
	'Import'                    => array( 'Importas' ),
	'Invalidateemail'           => array( 'Nutraukti_elektroninio_pašto_galiojimą' ),
	'BlockList'                 => array( 'IP_blokavimų_sąrašas' ),
	'LinkSearch'                => array( 'Nuorodų_paieška' ),
	'Listadmins'                => array( 'Administratorių_sąrašas' ),
	'Listbots'                  => array( 'Botų_sąrašas' ),
	'Listfiles'                 => array( 'Paveikslėlių_sąrašas' ),
	'Listgrouprights'           => array( 'Grupių_teisių_sąrašas' ),
	'Listredirects'             => array( 'Peradresavimų_sąrašas' ),
	'Listusers'                 => array( 'Naudotojų_sąrašas' ),
	'Lockdb'                    => array( 'Užrakinti_duomenų_bazę' ),
	'Log'                       => array( 'Sąrašas', 'Sąrašai' ),
	'Lonelypages'               => array( 'Vieniši_puslapiai' ),
	'Longpages'                 => array( 'Ilgiausi_puslapiai' ),
	'MergeHistory'              => array( 'Sujungti_istoriją' ),
	'MIMEsearch'                => array( 'MIME_paieška' ),
	'Mostcategories'            => array( 'Daugiausiai_naudojamos_kategorijos' ),
	'Mostimages'                => array( 'Daugiausiai_naudojami_paveikslėliai' ),
	'Mostlinked'                => array( 'Turintys_daugiausiai_nuorodų' ),
	'Mostlinkedcategories'      => array( 'Kategorijos_turinčios_daugiausiai_nuorodų' ),
	'Mostlinkedtemplates'       => array( 'Šablonai' ),
	'Mostrevisions'             => array( 'Daugiausiai_keičiami' ),
	'Movepage'                  => array( 'Puslapio_pervadinimas' ),
	'Mycontributions'           => array( 'Mano_indėlis' ),
	'Mypage'                    => array( 'Mano_puslapis' ),
	'Mytalk'                    => array( 'Mano_aptarimas' ),
	'Newimages'                 => array( 'Nauji_paveikslėliai' ),
	'Newpages'                  => array( 'Naujausi_puslapiai' ),
	'Popularpages'              => array( 'Populiarūs_puslapiai' ),
	'Preferences'               => array( 'Nustatymai' ),
	'Prefixindex'               => array( 'Prasidedantys' ),
	'Protectedpages'            => array( 'Užrakinti_puslapiai' ),
	'Protectedtitles'           => array( 'Apsaugoti_pavadinimai' ),
	'Randompage'                => array( 'Atsitiktinis_puslapis' ),
	'Randomredirect'            => array( 'Atsitiktinis_peradresavimas' ),
	'Recentchanges'             => array( 'Naujausi_keitimai' ),
	'Recentchangeslinked'       => array( 'Pakeitimai_susijusiuose_puslapiuose' ),
	'Revisiondelete'            => array( 'Redagavimo_ištrynimas' ),
	'Search'                    => array( 'Paieška' ),
	'Shortpages'                => array( 'Trumpiausi_puslapiai' ),
	'Specialpages'              => array( 'Specialieji_puslapiai' ),
	'Statistics'                => array( 'Statistika' ),
	'Tags'                      => array( 'Žymos' ),
	'Uncategorizedcategories'   => array( 'Kategorijos_be_subkategorijų' ),
	'Uncategorizedimages'       => array( 'Paveikslėliai_be_kategorijų' ),
	'Uncategorizedpages'        => array( 'Puslapiai_be_kategorijų' ),
	'Uncategorizedtemplates'    => array( 'Šablonai_be_kategorijų' ),
	'Undelete'                  => array( 'Netrinti' ),
	'Unlockdb'                  => array( 'Atrakinti_duomenų_bazę' ),
	'Unusedcategories'          => array( 'Nenaudojamos_kategorijos' ),
	'Unusedimages'              => array( 'Nenaudojami_paveikslėliai' ),
	'Unusedtemplates'           => array( 'Nenaudojami_šablonai' ),
	'Unwatchedpages'            => array( 'Nestebimi_puslapiai' ),
	'Upload'                    => array( 'Įkėlimas' ),
	'Userlogin'                 => array( 'Prisijungimas' ),
	'Userlogout'                => array( 'Atsijungimas' ),
	'Userrights'                => array( 'Naudotojo_teisės' ),
	'Version'                   => array( 'Versija' ),
	'Wantedcategories'          => array( 'Trokštamiausios_kategorijos' ),
	'Wantedfiles'               => array( 'Trokštami_failai' ),
	'Wantedpages'               => array( 'Trokštamiausi_puslapiai', 'Blogos_nuorodos' ),
	'Wantedtemplates'           => array( 'Trokštami_šablonai' ),
	'Watchlist'                 => array( 'Stebimieji' ),
	'Whatlinkshere'             => array( 'Kas_į_čia_rodo' ),
	'Withoutinterwiki'          => array( 'Be_interwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#PERADRESAVIMAS', '#REDIRECT' ),
	'notoc'                     => array( '0', '__BETURIN__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__BEGALERIJOS__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__TURINYS__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BEREDAGSEKC__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'DABARTINISMĖNESIS', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'DABARTINIOMĖNESIOPAVADINIMAS', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'DABARTINĖDIENA', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DABARTINĖDIENA2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'DABARTINĖSDIENOSPAVADINIMAS', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'DABARTINIAIMETAI', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DABARTINISLAIKAS', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'DABARTINĖVALANDA', 'CURRENTHOUR' ),
	'numberofpages'             => array( '1', 'PUSLAPIŲSKAIČIUS', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'STRAIPSNIŲSKAIČIUS', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FAILŲSKAIČIUS', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NAUDOTOJŲSKAIČIUS', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'KEITIMŲSKAIČIUS', 'NUMBEROFEDITS' ),
	'img_thumbnail'             => array( '1', 'miniatiūra', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatiūra=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'dešinėje', 'right' ),
	'img_left'                  => array( '1', 'kairėje', 'left' ),
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
'tog-underline' => 'Pabraukti nuorodas:',
'tog-justify' => 'Lygiuoti pastraipas pagal abi puses',
'tog-hideminor' => 'Slėpti smulkius pakeitimus naujausių keitimų sąraše',
'tog-hidepatrolled' => 'Slėpti patikrintus keitimus paskutinių keitimų sąraše',
'tog-newpageshidepatrolled' => 'Slėpti patikrintus puslapius iš naujausių straipsnių sąrašo',
'tog-extendwatchlist' => 'Išplėsti stebimųjų sąrašą, kad rodytų visus tinkamus keitimus, ne tik pačius naujausius.',
'tog-usenewrc' => 'Naudoti patobulintąjį paskutinių keitimų sąrašą (reikia JavaScript)',
'tog-numberheadings' => 'Automatiškai numeruoti skyrelius',
'tog-showtoolbar' => 'Rodyti redagavimo įrankinę (JavaScript)',
'tog-editondblclick' => 'Puslapių redagavimas dvigubu spustelėjimu (JavaScript)',
'tog-editsection' => 'Įjungti skyrelių redagavimą naudojant nuorodas [taisyti]',
'tog-editsectiononrightclick' => 'Įjungti skyrelių redagavimą paspaudus skyrelio pavadinimą dešiniuoju pelės klavišu (JavaScript)',
'tog-showtoc' => 'Rodyti turinį, jei puslapyje daugiau nei 3 skyreliai',
'tog-rememberpassword' => 'Prisiminti prisijungimo informaciją šioje naršyklėje (daugiausiai $1 {{PLURAL:$1|dieną|dienas|dienų}})',
'tog-watchcreations' => 'Pridėti puslapius, kuriuos aš sukuriu, į stebimų sąrašą',
'tog-watchdefault' => 'Pridėti puslapius, kuriuos aš redaguoju, į stebimų sąrašą',
'tog-watchmoves' => 'Pridėti puslapius, kuriuos aš perkeliu, į stebimų sąrašą',
'tog-watchdeletion' => 'Pridėti puslapius, kuriuos aš ištrinu, į stebimų sąrašą',
'tog-minordefault' => 'Pagal nutylėjimą pažymėti redagavimus kaip smulkius',
'tog-previewontop' => 'Rodyti peržiūrą virš redagavimo lauko',
'tog-previewonfirst' => 'Rodyti peržiūrą pirmą kartą pakeitus',
'tog-nocache' => 'Išjungti interneto naršyklės puslapių podėlį',
'tog-enotifwatchlistpages' => 'Siųsti man laišką, kai pakeičiamas puslapis, kurį stebiu',
'tog-enotifusertalkpages' => 'Siųsti man laišką, kai pakeičiamas mano naudotojo aptarimo puslapis',
'tog-enotifminoredits' => 'Siųsti man laišką, kai puslapio keitimas yra smulkus',
'tog-enotifrevealaddr' => 'Rodyti mano el. pašto adresą priminimo laiškuose',
'tog-shownumberswatching' => 'Rodyti stebinčių naudotojų skaičių',
'tog-oldsig' => 'Esamo parašo peržiūra:',
'tog-fancysig' => 'Laikyti parašą vikitekstu (be automatinių nuorodų)',
'tog-uselivepreview' => 'Naudoti tiesioginę peržiūrą (JavaScript) (Eksperimentinis)',
'tog-forceeditsummary' => 'Klausti, kai palieku tuščią keitimo komentarą',
'tog-watchlisthideown' => 'Slėpti mano keitimus stebimų sąraše',
'tog-watchlisthidebots' => 'Slėpti robotų keitimus stebimų sąraše',
'tog-watchlisthideminor' => 'Slėpti smulkius keitimus stebimų sąraše',
'tog-watchlisthideliu' => 'Slėpti prisijungusių naudotojų keitimus stebimųjų sąraše',
'tog-watchlisthideanons' => 'Slėpti anoniminių naudotojų keitimus stebimųjų sąraše',
'tog-watchlisthidepatrolled' => 'Slėpti patikrintus keitimus stebimųjų sąraše',
'tog-ccmeonemails' => 'Siųsti man laiškų, kuriuos siunčiu kitiems naudotojams, kopijas',
'tog-diffonly' => 'Nerodyti puslapio turinio po skirtumais',
'tog-showhiddencats' => 'Rodyti paslėptas kategorijas',
'tog-norollbackdiff' => 'Nepaisyti skirtumo atlikus atmetimą',
'tog-useeditwarning' => 'Perspėti mane, kai palieku redagavimo puslapį, o jame yra neišsaugotų pakeitimų',

'underline-always' => 'Visada',
'underline-never' => 'Niekada',
'underline-default' => 'Pagal naršyklės nustatymus',

# Font style option in Special:Preferences
'editfont-style' => 'Redagavimo srities šrifto stilius:',
'editfont-default' => 'Naršyklės numatytasis',
'editfont-monospace' => 'Lygiaplotis šriftas',
'editfont-sansserif' => 'Šriftas be užraitų',
'editfont-serif' => 'Šriftas su užraitais',

# Dates
'sunday' => 'sekmadienis',
'monday' => 'pirmadienis',
'tuesday' => 'antradienis',
'wednesday' => 'trečiadienis',
'thursday' => 'ketvirtadienis',
'friday' => 'penktadienis',
'saturday' => 'šeštadienis',
'sun' => 'Sek',
'mon' => 'Pir',
'tue' => 'Ant',
'wed' => 'Tre',
'thu' => 'Ket',
'fri' => 'Pen',
'sat' => 'Šeš',
'january' => 'sausio',
'february' => 'vasario',
'march' => 'kovo',
'april' => 'balandžio',
'may_long' => 'gegužės',
'june' => 'birželio',
'july' => 'liepos',
'august' => 'rugpjūčio',
'september' => 'rugsėjo',
'october' => 'spalio',
'november' => 'lapkričio',
'december' => 'gruodžio',
'january-gen' => 'Sausis',
'february-gen' => 'Vasaris',
'march-gen' => 'Kovas',
'april-gen' => 'Balandis',
'may-gen' => 'Gegužė',
'june-gen' => 'Birželis',
'july-gen' => 'Liepa',
'august-gen' => 'Rugpjūtis',
'september-gen' => 'Rugsėjis',
'october-gen' => 'Spalis',
'november-gen' => 'Lapkritis',
'december-gen' => 'Gruodis',
'jan' => 'sau',
'feb' => 'vas',
'mar' => 'kov',
'apr' => 'bal',
'may' => 'geg',
'jun' => 'bir',
'jul' => 'lie',
'aug' => 'rgp',
'sep' => 'rgs',
'oct' => 'spa',
'nov' => 'lap',
'dec' => 'grd',
'january-date' => 'Sausio $1',
'february-date' => 'Vasario $1',
'march-date' => 'Kovo $1',
'april-date' => 'Balandžio $1',
'may-date' => 'Gegužės $1',
'june-date' => 'Birželio $1',
'july-date' => 'Liepos $1',
'august-date' => 'Rugpjūčio $1',
'september-date' => 'Rugsėjo $1',
'october-date' => 'Spalio $1',
'november-date' => 'Lapkričio $1',
'december-date' => 'Gruodžio $1',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategorija|Kategorijos}}',
'category_header' => 'Puslapiai kategorijoje „$1“',
'subcategories' => 'Subkategorijos',
'category-media-header' => 'Daugialypės terpės failai kategorijoje „$1“',
'category-empty' => "''Šiuo metu ši kategorija neturi jokių puslapių ar failų.''",
'hidden-categories' => '{{PLURAL:$1|Paslėpta kategorija|Paslėptos kategorijos}}',
'hidden-category-category' => 'Paslėptos kategorijos',
'category-subcat-count' => '{{PLURAL:$2|Šioje kategorijoje yra viena subkategorija.|{{PLURAL:$1|Rodoma|Rodomos|Rodoma}} $1 {{PLURAL:$1|subkategorija|subkategorijos|subkategorijų}} (iš viso yra $2 {{PLURAL:$2|subkategorija|subkategorijos|subkategorijų}}).}}',
'category-subcat-count-limited' => 'Šioje kategorijoje yra $1 {{PLURAL:$1|subkategorija|subkategorijos|subkategorijų}}.',
'category-article-count' => '{{PLURAL:$2|Šioje kategorijoje yra vienas puslapis.|{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|puslapis|puslapiai|puslapių}} (iš viso kategorijoje yra $2 {{PLURAL:$2|puslapis|puslapiai|puslapių}}).}}',
'category-article-count-limited' => '{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|puslapis|puslapiai|puslapių}}.',
'category-file-count' => '{{PLURAL:$2|Šioje kategorijoje yra vienas failas.|{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|failas|failai|failų}} (iš viso kategorijoje yra $2 {{PLURAL:$2|failas|failai|failų}}).}}',
'category-file-count-limited' => '{{PLURAL:$1|Rodomas|Rodomi|Rodoma}} $1 šios kategorijos {{PLURAL:$1|failas|failai|failų}}.',
'listingcontinuesabbrev' => 'tęs.',
'index-category' => 'Indeksuoti puslapiai',
'noindex-category' => 'Neindeksuoti puslapiai',
'broken-file-category' => 'Puslapiai su neteisingomis nuorodomis į failus',

'about' => 'Apie',
'article' => 'Turinys',
'newwindow' => '(atsidaro naujame lange)',
'cancel' => 'Atšaukti',
'moredotdotdot' => 'Daugiau...',
'morenotlisted' => 'Daugiau nėra',
'mypage' => 'Naudotojo puslapis',
'mytalk' => 'Mano aptarimas',
'anontalk' => 'Šio IP aptarimas',
'navigation' => 'Naršymas',
'and' => '&#32;ir',

# Cologne Blue skin
'qbfind' => 'Paieška',
'qbbrowse' => 'Naršymas',
'qbedit' => 'Taisyti',
'qbpageoptions' => 'Šis puslapis',
'qbmyoptions' => 'Mano puslapiai',
'qbspecialpages' => 'Specialieji puslapiai',
'faq' => 'DUK',
'faqpage' => 'Project:DUK',

# Vector skin
'vector-action-addsection' => 'Pridėti temą',
'vector-action-delete' => 'Ištrinti',
'vector-action-move' => 'Pervardyti',
'vector-action-protect' => 'Užrakinti',
'vector-action-undelete' => 'Atkurti',
'vector-action-unprotect' => 'Keisti apsaugą',
'vector-simplesearch-preference' => 'Supaprastinta paieška (tik „Vector“ išvaizda)',
'vector-view-create' => 'Kurti',
'vector-view-edit' => 'Redaguoti',
'vector-view-history' => 'Istorija',
'vector-view-view' => 'Skaityti',
'vector-view-viewsource' => 'Žiūrėti kodą',
'actions' => 'Veiksmai',
'namespaces' => 'Vardų sritys',
'variants' => 'Variantai',

'navigation-heading' => 'Naršymo meniu',
'errorpagetitle' => 'Klaida',
'returnto' => 'Grįžti į $1.',
'tagline' => 'Iš {{SITENAME}}.',
'help' => 'Pagalba',
'search' => 'Paieška',
'searchbutton' => 'Paieška',
'go' => 'Rodyti',
'searcharticle' => 'Rodyti',
'history' => 'Puslapio istorija',
'history_short' => 'Istorija',
'updatedmarker' => 'atnaujinta nuo paskutinio mano apsilankymo',
'printableversion' => 'Versija spausdinimui',
'permalink' => 'Nuolatinė nuoroda',
'print' => 'Spausdinti',
'view' => 'Žiūrėti',
'edit' => 'Redaguoti',
'create' => 'Sukurti',
'editthispage' => 'Redaguoti šį puslapį',
'create-this-page' => 'Sukurti šį puslapį',
'delete' => 'Trinti',
'deletethispage' => 'Ištrinti šį puslapį',
'undeletethispage' => 'Attrinti šį puslapį',
'undelete_short' => 'Atkurti $1 {{PLURAL:$1:redagavimą|redagavimus|redagavimų}}',
'viewdeleted_short' => 'Peržiūrėti $1 {{PLURAL:$1|ištrintą keitimą|ištrintus keitimus|ištrintų keitimų}}',
'protect' => 'Užrakinti',
'protect_change' => 'keisti',
'protectthispage' => 'Rakinti šį puslapį',
'unprotect' => 'Keisti apsaugą',
'unprotectthispage' => 'Keisti šio puslapio apsaugą',
'newpage' => 'Naujas puslapis',
'talkpage' => 'Aptarti šį puslapį',
'talkpagelinktext' => 'Aptarimas',
'specialpage' => 'Specialusis puslapis',
'personaltools' => 'Asmeniniai įrankiai',
'postcomment' => 'Rašyti komentarą',
'articlepage' => 'Rodyti turinio puslapį',
'talk' => 'Aptarimas',
'views' => 'Žiūrėti',
'toolbox' => 'Įrankiai',
'userpage' => 'Rodyti naudotojo puslapį',
'projectpage' => 'Rodyti projekto puslapį',
'imagepage' => 'Žiūrėti failo puslapį',
'mediawikipage' => 'Rodyti pranešimo puslapį',
'templatepage' => 'Rodyti šablono puslapį',
'viewhelppage' => 'Rodyti pagalbos puslapį',
'categorypage' => 'Rodyti kategorijos puslapį',
'viewtalkpage' => 'Rodyti aptarimo puslapį',
'otherlanguages' => 'Kitomis kalbomis',
'redirectedfrom' => '(Nukreipta iš $1)',
'redirectpagesub' => 'Nukreipimo puslapis',
'lastmodifiedat' => 'Šis puslapis paskutinį kartą keistas $1 $2.',
'viewcount' => 'Šis puslapis buvo atvertas $1 {{PLURAL:$1|kartą|kartus|kartų}}.',
'protectedpage' => 'Užrakintas puslapis',
'jumpto' => 'Peršokti į:',
'jumptonavigation' => 'navigaciją',
'jumptosearch' => 'paiešką',
'view-pool-error' => 'Atsiprašome, šiuo metu serveriai yra perkrauti.
Pernelyg daug naudotojų skaito šį puslapį.
Prašome palaukti ir bandyti į šį puslapį patekti dar kartą.

$1',
'pool-timeout' => 'Baigėsi laikas laukiant užrakto',
'pool-queuefull' => 'Telkinio eilė pilna',
'pool-errorunknown' => 'Nežinoma klaida',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Apie {{SITENAME}}',
'aboutpage' => 'Project:Apie',
'copyright' => 'Turinys pateikiamas pagal $1 licenciją.',
'copyrightpage' => '{{ns:project}}:Autorystės teisės',
'currentevents' => 'Naujienos',
'currentevents-url' => 'Project:Naujienos',
'disclaimers' => 'Atsakomybės apribojimas',
'disclaimerpage' => 'Project:Atsakomybės apribojimas',
'edithelp' => 'Kaip redaguoti',
'helppage' => 'Help:Turinys',
'mainpage' => 'Pagrindinis puslapis',
'mainpage-description' => 'Pagrindinis puslapis',
'policy-url' => 'Project:Politika',
'portal' => 'Bendruomenė',
'portal-url' => 'Project:Bendruomenė',
'privacy' => 'Privatumo politika',
'privacypage' => 'Project:Privatumo politika',

'badaccess' => 'Teisių klaida',
'badaccess-group0' => 'Jums neleidžiama įvykdyti veiksmo, kurio prašėte.',
'badaccess-groups' => 'Veiksmas, kurio prašėte, galimas tik naudotojams, esantiems {{PLURAL:$2|šioje grupėje|vienoje iš šių grupių}} $1.',

'versionrequired' => 'Reikalinga $1 MediaWiki versija',
'versionrequiredtext' => 'Reikalinga $1 MediaWiki versija, kad pamatytumėte šį puslapį. Žiūrėkite [[Special:Version|versijos puslapį]].',

'ok' => 'Gerai',
'retrievedfrom' => 'Gauta iš „$1“',
'youhavenewmessages' => 'Jūs turite $1 ($2).',
'newmessageslink' => 'naujų žinučių',
'newmessagesdifflink' => 'paskutinis pakeitimas',
'youhavenewmessagesfromusers' => 'Jūs gavote $1 nuo {{PLURAL:$3|kito vartotojo|$3 vartotojų}} ($2).',
'youhavenewmessagesmanyusers' => 'Jūs turite $1 iš daugelio vartotojų ( $2 ) .',
'newmessageslinkplural' => '{{PLURAL:$1|naują žinutę|naujų žinučių}}',
'newmessagesdifflinkplural' => 'paskutinis {{PLURAL:$1|pakeitimas|pakeitimai}}',
'youhavenewmessagesmulti' => 'Turite naujų žinučių $1',
'editsection' => 'redaguoti',
'editold' => 'taisyti',
'viewsourceold' => 'žiūrėti šaltinį',
'editlink' => 'keisti',
'viewsourcelink' => 'žiūrėti kodą',
'editsectionhint' => 'Redaguoti skyrelį: $1',
'toc' => 'Turinys',
'showtoc' => 'rodyti',
'hidetoc' => 'slėpti',
'collapsible-collapse' => 'Sutraukti',
'collapsible-expand' => 'Išplėsti',
'thisisdeleted' => 'Žiūrėti ar atkurti $1?',
'viewdeleted' => 'Rodyti $1?',
'restorelink' => '$1 {{PLURAL:$1|ištrintą keitimą|ištrintus keitimus|ištrintų keitimų}}',
'feedlinks' => 'Šaltinis:',
'feed-invalid' => 'Neleistinas šaltinio tipas.',
'feed-unavailable' => 'Keitimų prenumeratos negalimos',
'site-rss-feed' => '$1 RSS prenumerata',
'site-atom-feed' => '$1 Atom prenumerata',
'page-rss-feed' => '„$1“ RSS prenumerata',
'page-atom-feed' => '„$1“ Atom prenumerata',
'red-link-title' => '$1 (puslapis neegzistuoja)',
'sort-descending' => 'Rūšiuoti mažėjimo tvarka',
'sort-ascending' => 'Rūšiuoti didėjimo tvarka',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Puslapis',
'nstab-user' => 'Naudotojo puslapis',
'nstab-media' => 'Media puslapis',
'nstab-special' => 'Specialusis puslapis',
'nstab-project' => 'Projekto puslapis',
'nstab-image' => 'Failas',
'nstab-mediawiki' => 'Pranešimas',
'nstab-template' => 'Šablonas',
'nstab-help' => 'Pagalbos puslapis',
'nstab-category' => 'Kategorija',

# Main script and global functions
'nosuchaction' => 'Nėra tokio veiksmo',
'nosuchactiontext' => 'Veiksmas, nurodytas adrese, neatpažintas.
Galbūt Jūs padarėte klaidą adrese ar paspaudėte ant neteisingos nuorodos.
Šios problemos priežastis taip pat gali būti klaida programinėje įrangoje, kurią naudoja {{SITENAME}}.',
'nosuchspecialpage' => 'Nėra tokio specialiojo puslapio',
'nospecialpagetext' => '<strong>Toks specialusis puslapis neegzistuoja</strong>

Egzistuojančių specialiųjų puslapių sąrašą galite rasti [[Special:SpecialPages|specialiųjų puslapių sąraše]].',

# General errors
'error' => 'Klaida',
'databaseerror' => 'Duomenų bazės klaida',
'laggedslavemode' => 'Dėmesio: Puslapyje gali nesimatyti naujausių pakeitimų.',
'readonly' => 'Duomenų bazė užrakinta',
'enterlockreason' => 'Įveskite užrakinimo priežastį, taip pat datą, kada bus atrakinta',
'readonlytext' => 'Duomenų bazė šiuo metu yra užrakinta naujiems įrašams ar kitiems keitimams,
turbūt duomenų bazės techninei profilaktikai,
po to viskas vėl veiks kaip įprasta.

Užrakinusiojo administratoriaus pateiktas rakinimo paaiškinimas: $1',
'missing-article' => 'Duomenų bazė nerado puslapio teksto, kurį ji turėtų rasti, pavadinto „$1“ $2.

Paprastai tai būna dėl pasenusios skirtumo ar istorijos nuorodos į puslapį, kuris buvo ištrintas.

Jei tai ne tas atvejis, jūs galbūt radote klaidą programinėje įrangoje.
Prašome apie tai pranešti [[Special:ListUsers/sysop|administratoriui]], nepamiršdami nurodyti nuorodą.',
'missingarticle-rev' => '(versija#: $1)',
'missingarticle-diff' => '(Skirt.: $1, $2)',
'readonly_lag' => 'Duomenų bazė buvo automatiškai užrakinta, kol pagalbinės duomenų bazės prisivys pagrindinę',
'internalerror' => 'Vidinė klaida',
'internalerror_info' => 'Vidinė klaida: $1',
'fileappenderrorread' => 'Papildymo metu nepavyko perskaityti „$1“.',
'fileappenderror' => 'Nepavyko pridėti „$1“ prie „$2“.',
'filecopyerror' => 'Nepavyksta kopijuoti failo iš „$1“ į „$2“.',
'filerenameerror' => 'Nepavyksta pervardinti failo iš „$1“ į „$2“.',
'filedeleteerror' => 'Nepavyksta ištrinti failo „$1“.',
'directorycreateerror' => 'Nepavyko sukurti aplanko „$1“.',
'filenotfound' => 'Nepavyksta rasti failo „$1“.',
'fileexistserror' => 'Nepavyksta įrašyti į failą „$1“: failas jau yra',
'unexpected' => 'Netikėta reikšmė: „$1“=„$2“.',
'formerror' => 'Klaida: nepavyko apdoroti formos duomenų',
'badarticleerror' => 'Veiksmas negalimas šiam puslapiui.',
'cannotdelete' => 'Nepavyko ištrinti puslapio ar failo „$1“.
Galbūt jį jau kažkas kitas ištrynė.',
'cannotdelete-title' => 'Negalite ištrinti puslapio "$1"',
'delete-hook-aborted' => 'Trynimą atšaukė kabliukas.
Nebuvo duotas joks paaiškinimas.',
'badtitle' => 'Blogas pavadinimas',
'badtitletext' => 'Nurodytas puslapio pavadinimas buvo neleistinas, tuščias arba neteisingai sujungtas tarpkalbinis arba tarpprojektinis pavadinimas. Jame gali būti vienas ar daugiau simbolių, neleistinų pavadinimuose',
'perfcached' => 'Rodoma išsaugota duomenų kopija, todėl duomenys gali būti ne patys naujausi. Maksimaliai $1 {{PLURAL:$1|rezultatas|rezultatai|rezultatų}} yra saugoma.',
'perfcachedts' => 'Rodoma išsaugota duomenų kopija, kuri buvo atnaujinta $2 $3. Maksimaliai $4 {{PLURAL:$4|rezultatas|rezultatai|rezultatų}} yra saugoma.',
'querypage-no-updates' => 'Atnaujinimai šiam puslapiui dabar yra išjungti. Duomenys čia dabar nebus atnaujinti.',
'wrong_wfQuery_params' => 'Neteisingi parametrai į funkciją wfQuery()<br />
Funkcija: $1<br />
Užklausa: $2',
'viewsource' => 'Žiūrėti kodą',
'viewsource-title' => 'Peržiūrėti šaltinį $1',
'actionthrottled' => 'Veiksmas apribotas',
'actionthrottledtext' => 'Kad būtų apsisaugota nuo reklamų, jums neleidžiama daug kartų atlikti šį veiksmą per trumpą laiko tarpą, bet jūs pasiekėte šį limitą. Prašome vėl pamėginti po kelių minučių.',
'protectedpagetext' => 'Šis puslapis yra užrakintas, saugant jį nuo redagavimo.',
'viewsourcetext' => 'Jūs galite žiūrėti ir kopijuoti puslapio kodą:',
'viewyourtext' => "Jūs galite matyti ir kopijuoti '''savo redagavimų''' tekstą į šį puslapį:",
'protectedinterface' => 'Šiame puslapyje yra apsaugotas nuo piktnaudžiavimo programinės įrangos sąsajos tekstas.',
'editinginterface' => "'''Dėmesio:''' Jūs redaguojate puslapį, kuris yra naudojamas programinės įrangos sąsajos tekste. Pakeitimai šiame puslapyje taip pat pakeis naudotojo sąsajos išvaizdą ir kitiems naudotojams šiame wiki.
Jei norite pridėti ir keisti vertimus, siūlome pasinaudoti [//translatewiki.net/wiki/Main_Page?setlang=lt „translatewiki.net“], „MediaWiki“ lokalizacijos projektu.",
'cascadeprotected' => 'Šis puslapis buvo apsaugotas nuo redagavimo, kadangi jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi:
$2',
'namespaceprotected' => "Jūs neturite teisės redaguoti puslapių '''$1''' srityje.",
'customcssprotected' => 'Jūs neturite teisės keisti šį CSS puslapį, nes jame yra kito naudotojo asmeniniai nustatymai.',
'customjsprotected' => 'Jūs neturite teisės keisti šį JavaScript puslapį, nes jame yra kito naudotojo asmeniniai nustatymai.',
'mycustomcssprotected' => 'Jūs neturite teisės redaguoti šio CSS puslapio.',
'mycustomjsprotected' => 'Jūs neturite teisės redaguoti šio JavaScript puslapio.',
'myprivateinfoprotected' => 'Jūs neturite teisių redaguoti savo asmeninę informaciją.',
'mypreferencesprotected' => 'Jūs neturite teisių redaguoti jūsų parinktys.',
'ns-specialprotected' => 'Specialieji puslapiai negali būti redaguojami.',
'titleprotected' => "[[User:$1|$1]] apsaugojo šį pavadinimą nuo sukūrimo.
Nurodyta priežastis yra ''$2''.",
'filereadonlyerror' => 'Neįmanoma pakeisti failo "$1" nes failų saugykla "$2" yra nustatyta tik skaitymo režimu.

Ją užrakinęs administratorius pateikė šį paaiškinimą: "$3".',
'invalidtitle-knownnamespace' => 'Klaidingas pavadinimas vardų erdvėje "$2" ir tekstu "$3"',
'invalidtitle-unknownnamespace' => 'Klaidingas pavadinimas nežinomoje vardų erdvėje numeriu $1 ir tekstu "$2"',
'exception-nologin' => 'Neprisijungęs',
'exception-nologin-text' => 'Šiam puslapiui ar veiksmui reikalingas prisijungimas šioje wiki.',

# Virus scanner
'virus-badscanner' => "Neleistina konfigūracija: nežinomas virusų skeneris: ''$1''",
'virus-scanfailed' => 'skanavimas nepavyko (kodas $1)',
'virus-unknownscanner' => 'nežinomas antivirusas:',

# Login and logout pages
'logouttext' => "'''Dabar jūs esate atsijungęs.'''

Galite toliau naudoti {{SITENAME}} anonimiškai arba <span class='plainlinks'>[$1 prisijunkite]</span> iš naujo tuo pačiu ar kitu naudotoju.
Pastaba: kai kuriuose puslapiuose ir toliau gali rodyti, kad esate prisijungęs iki tol, kol išvalysite savo naršyklės podėlį.",
'welcomeuser' => 'Sveiki,  $1 !',
'welcomecreation-msg' => 'Jūsų paskyra buvo sukurta.
Nepamirškite pakeisti savo [[Special:Preferences|{{SITENAME}} nustatymų]].',
'yourname' => 'Naudotojo vardas:',
'userlogin-yourname' => 'Naudotojo vardas',
'userlogin-yourname-ph' => 'Įveskite savo naudotojo vardą',
'createacct-another-username-ph' => 'Įveskite naudotojo vardą',
'yourpassword' => 'Slaptažodis:',
'userlogin-yourpassword' => 'Slaptažodis',
'userlogin-yourpassword-ph' => 'Įveskite savo slaptažodį',
'createacct-yourpassword-ph' => 'Įveskite slaptažodį',
'yourpasswordagain' => 'Pakartokite slaptažodį:',
'createacct-yourpasswordagain' => 'Patvirtinkite slaptažodį',
'createacct-yourpasswordagain-ph' => 'Įveskite slaptažodį dar kartą',
'remembermypassword' => 'Prisiminti prisijungimo duomenis šiame kompiuteryje (daugiausiai $1 {{PLURAL:$1|dieną|dienas|dienų}})',
'userlogin-remembermypassword' => 'Įsiminti mane',
'userlogin-signwithsecure' => 'Naudoti saugią jungtį',
'yourdomainname' => 'Jūsų domenas:',
'password-change-forbidden' => 'Jus negalite keisti slaptažodžių šioje wiki.',
'externaldberror' => 'Yra arba išorinė autorizacijos duomenų bazės klaida arba jums neleidžiama atnaujinti jūsų išorinės paskyros.',
'login' => 'Prisijungti',
'nav-login-createaccount' => 'Prisijungti / sukurti paskyrą',
'loginprompt' => 'Įjunkite slapukus, jei norite prisijungti prie {{SITENAME}}.',
'userlogin' => 'Prisijungti / sukurti paskyrą',
'userloginnocreate' => 'Prisijungti',
'logout' => 'Atsijungti',
'userlogout' => 'Atsijungti',
'notloggedin' => 'Neprisijungęs',
'userlogin-noaccount' => 'Neturite paskyros?',
'userlogin-joinproject' => 'Prisijungti prie {{SITENAME}}',
'nologin' => "Neturite prisijungimo vardo? '''$1'''.",
'nologinlink' => 'Sukurkite paskyrą',
'createaccount' => 'Sukurti paskyrą',
'gotaccount' => "Jau turite paskyrą? '''$1'''.",
'gotaccountlink' => 'Prisijunkite',
'userlogin-resetlink' => 'Pamiršote savo prisijungimo duomenis?',
'userlogin-resetpassword-link' => 'Nustatykite slaptažodį iš naujo',
'helplogin-url' => 'Help:Prisijungimas',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Prisijungimo pagalba]]',
'createacct-join' => 'Įveskite savo informaciją žemiau.',
'createacct-another-join' => 'Įveskite naujos paskyros informaciją žemiau.',
'createacct-emailrequired' => 'Elektroninio pašto adresas',
'createacct-emailoptional' => 'Elektroninio pašto adresas (neprivaloma)',
'createacct-email-ph' => 'Įveskite savo elektroninio pašto adresą',
'createacct-another-email-ph' => 'Įveskite elektroninio pašto adresą',
'createaccountmail' => 'Naudokite laikiną atsitiktinį slaptažodį ir nusiųskite jį į elektroninį paštą, nurodytą žemiau.',
'createacct-realname' => 'Vardas (neprivaloma)',
'createaccountreason' => 'Priežastis:',
'createacct-reason' => 'Priežastis',
'createacct-reason-ph' => 'Kodėl kuriate kitą paskyrą',
'createacct-captcha' => 'Saugumo patikrinimas',
'createacct-imgcaptcha-ph' => 'Įveskite tekstą, kurį matote aukščiau',
'createacct-submit' => 'Sukurkite savo paskyrą',
'createacct-another-submit' => 'Sukurti kitą paskyrą',
'createacct-benefit-heading' => '{{SITENAME}} sukurtas žmonių kaip jūs.',
'createacct-benefit-body1' => '{{PLURAL:$1|keitimas|keitimai|keitimų}}',
'createacct-benefit-body2' => '{{PLURAL:$1|puslapis|puslapiai}}',
'createacct-benefit-body3' => 'Neseni {{PLURAL:$1|autorius|autoriai|autorių}}',
'badretype' => 'Įvesti slaptažodžiai nesutampa.',
'userexists' => 'Įvestasis naudotojo vardas jau naudojamas.
Prašome pasirinkti kitą vardą.',
'loginerror' => 'Prisijungimo klaida',
'createacct-error' => 'Paskyros kūrimo klaida',
'createaccounterror' => 'Nepavyko sukurti paskyros: $1',
'nocookiesnew' => 'Naudotojo paskyra buvo sukurta, bet jūs nesate prisijungęs. {{SITENAME}} naudoja slapukus, kad prijungtų naudotojus. Jūs esate išjungę slapukus. Prašome įjungti juos, tada prisijunkite su savo naujuoju naudotojo vardu ir slaptažodžiu.',
'nocookieslogin' => '{{SITENAME}} naudoja slapukus, kad prijungtų naudotojus. Jūs esate išjungę slapukus. Prašome įjungti juos ir pamėginkite vėl.',
'nocookiesfornew' => 'Paskyra nebuvo sukurta, nes mums nepavyko nustatyti jos šaltinio.
Įsitikinkite, kad įjungti slapukai (angl. cookies) ir tada bandykite dar kartą.',
'noname' => 'Jūs nesate nurodęs teisingo naudotojo vardo.',
'loginsuccesstitle' => 'Sėkmingai prisijungėte',
'loginsuccess' => "'''Dabar jūs prisijungęs prie {{SITENAME}} kaip „$1“.'''",
'nosuchuser' => 'Nėra jokio naudotojo, turinčio vardą „$1“.
Naudotojų varduose skiriamos didžiosios ir mažosios raidės.
Patikrinkite rašybą, arba [[Special:UserLogin/signup|sukurkite naują paskyrą]].',
'nosuchusershort' => 'Nėra jokio naudotojo, pavadinto „$1“. Patikrinkite rašybą.',
'nouserspecified' => 'Jums reikia nurodyti naudotojo vardą.',
'login-userblocked' => 'Šis naudotojas yra užblokuotas. Prisijungti neleidžiama.',
'wrongpassword' => 'Įvestas neteisingas slaptažodis. Pamėginkite dar kartą.',
'wrongpasswordempty' => 'Įvestas slaptažodis yra tuščias. Pamėginkite vėl.',
'passwordtooshort' => 'Slaptažodžiai turi būti bent $1 {{PLURAL:$1|simbolio|simbolių|simbolių}} ilgio.',
'password-name-match' => 'Jūsų slaptažodis turi skirtis nuo jūsų naudotojo vardo.',
'password-login-forbidden' => 'Šito naudotojo vardo ir slaptažodžio naudojimas yra uždraustas.',
'mailmypassword' => 'Atsiųsti naują slaptažodį el. paštu',
'passwordremindertitle' => 'Laikinasis {{SITENAME}} slaptažodis',
'passwordremindertext' => 'Kažkas (tikriausiai jūs, IP adresu $1)
paprašė, kad atsiųstumėte naują slaptažodį projektui {{SITENAME}} ($4).
Laikinasis naudotojo „$2“ slaptažodis buvo sukurtas ir nustatytas į „$3“.
Jei tai buvo jūs, jūs turėtumėte prisijungti ir pasirinkti naują slaptažodį.
Jūsų laikinasis slaptažodis baigs galioti po {{PLURAL:$5|$5 dienos|$5 dienų|$5 dienų}}.

Jei kažkas kitas atliko šį prašymą arba jūs prisiminėte savo slaptažodį ir
nebenorite jo pakeisti, galite tiesiog nekreipti dėmesio į šį laišką ir toliau
naudotis savo senuoju slaptažodžiu.',
'noemail' => 'Nėra jokio el. pašto adreso įvesto naudotojui „$1“.',
'noemailcreate' => 'Jūs turite nurodyti veikiantį el. pašto adresą',
'passwordsent' => 'Naujas slaptažodis buvo nusiųstas į el. pašto adresą,
užregistruotą naudotojo „$1“.
Prašome prisijungti vėl, kai jūs jį gausite.',
'blocked-mailpassword' => 'Jūsų IP adresas yra užblokuotas nuo redagavimo, taigi neleidžiama naudoti slaptažodžio priminimo funkcijos, kad apsisaugotume nuo piktnaudžiavimo.',
'eauthentsent' => 'Patvirtinimo laiškas buvo nusiųstas į paskirtąjį el. pašto adresą.
Prieš išsiunčiant kitą laišką į jūsų dėžutę, jūs turite vykdyti nurodymus laiške, kad patvirtintumėte, kad dėžutė tikrai yra jūsų.',
'throttled-mailpassword' => 'Slaptažodžio priminimas jau buvo išsiųstas, per {{PLURAL:$1|$1 paskutinę valandą|$1 paskutines valandas|$1 paskutinių valandų}}.

Norint apsisaugoti nuo piktnaudžiavimo, slaptažodžio priminimas gali būti išsiųstas tik kas {{PLURAL:$1|$1 valandą|$1 valandas|$1 valandų}}.',
'mailerror' => 'Klaida siunčiant paštą: $1',
'acct_creation_throttle_hit' => 'Šio projekto lankytojai, naudojantys jūsų IP adresą, sukūrė {{PLURAL:$1|$1 paskyrą|$1 paskyras|$1 paskyrų}} per paskutiniąją dieną, o tai yra didžiausias leidžiamas kiekis per šį laiko tarpą.
Todėl šiuo metu lankytojai, naudojantys šį IP adresą, daugiau negali kurti paskyrų.',
'emailauthenticated' => 'Jūsų el. pašto adresas buvo patvirtintas $2 d. $3.',
'emailnotauthenticated' => 'Jūsų el. pašto adresas dar nėra patvirtintas. Jokie laiškai
nebus siunčiami nei vienai žemiau išvardintai paslaugai.',
'noemailprefs' => 'Nurodykite el. pašto adresą, kad šios funkcijos veiktų.',
'emailconfirmlink' => 'Patvirtinkite savo el. pašto adresą',
'invalidemailaddress' => 'El. pašto adresas negali būti priimtas, nes atrodo, kad jis nėra teisingo formato.
Prašome įvesti gerai suformuotą adresą arba palikite tą laukelį tuščią.',
'cannotchangeemail' => 'Paskyros e-mail adresas šiame viki negali būti keičiamas.',
'emaildisabled' => 'Ši svetainė negali siųsti elektroninių laiškų.',
'accountcreated' => 'Paskyra sukurta',
'accountcreatedtext' => 'Naudotojo paskyra [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|talk]]) buvo sukurta.',
'createaccount-title' => '{{SITENAME}} paskyros kūrimas',
'createaccount-text' => 'Projekte {{SITENAME}} ($4) kažkas sukūrė paskyrą „$2“ su slaptažodžiu „$3“ panaudodamas jūsų el. pašto adresą.
Jūs turėtumėte prisijungti ir pasikeisti savo slaptažodį.

Jūs galite nekreipti dėmesio į laišką, jei ši paskyra buvo sukurta per klaidą.',
'usernamehasherror' => 'Naudotojo vardas negali turėti grotelių simbolio',
'login-throttled' => 'Jūs pernelyg daug kartų bandėte prisijungti.
Palaukite prieš bandant vėl.',
'login-abort-generic' => 'Jūsų prisijungimas buvo nesėkmingas - Nutraukta',
'loginlanguagelabel' => 'Kalba: $1',
'suspicious-userlogout' => 'Jūsų prašymas atsijungti buvo atmestas, nes, atrodo, jį klaidingai išsiuntė naršyklė arba spartinantysis tarpinis serveris.',

# Email sending
'php-mail-error-unknown' => 'Nežinoma klaida PHP mail() funkcijoje',
'user-mail-no-addy' => 'Bandyta išsiųsti elektroninį laišką be el. pašto adreso.',
'user-mail-no-body' => 'Mėginta siųsti tuščia ar pernelyg trumpą E-pašto žinutė.',

# Change password dialog
'resetpass' => 'Keisti slaptažodį',
'resetpass_announce' => 'Jūs prisijungėte su atsiųstu laikinuoju kodu. Norėdami užbaigti prisijungimą, čia jums reikia nustatyti naująjį slaptažodį:',
'resetpass_text' => '<!-- Įterpkite čia tekstą -->',
'resetpass_header' => 'Keisti paskyros slaptažodį',
'oldpassword' => 'Senas slaptažodis:',
'newpassword' => 'Naujas slaptažodis:',
'retypenew' => 'Pakartokite naują slaptažodį:',
'resetpass_submit' => 'Nustatyti slaptažodį ir prisijungti',
'changepassword-success' => 'Jūsų slaptažodis pakeistas sėkmingai! Dabar prisijungiama...',
'resetpass_forbidden' => 'Slaptažodžiai negali būti pakeisti',
'resetpass-no-info' => 'Jūs turite būti prisijungęs, kad pasiektumėte puslapį tiesiogiai.',
'resetpass-submit-loggedin' => 'Keisti slaptažodį',
'resetpass-submit-cancel' => 'Atšaukti',
'resetpass-wrong-oldpass' => 'Klaidingas laikinas ar esamas slaptažodis.
Jūs galbūt jau sėkmingai pakeitėte savo slaptažodį ar gavote naują laikiną slaptažodį.',
'resetpass-temp-password' => 'Laikinas slaptažodis:',
'resetpass-abort-generic' => 'Slaptažodžio keitimas buvo nutrauktas nuo ekstenzijos.',

# Special:PasswordReset
'passwordreset' => 'Atstatyti slaptažodį',
'passwordreset-text-one' => 'Užpildykite šią formą, norėdami atkurti savo slaptažodį.',
'passwordreset-text-many' => '{{PLURAL:$1|Užpildykite viena iš laukų slaptažodžio atkurimui.}}',
'passwordreset-legend' => 'Atstatyti slaptažodį',
'passwordreset-disabled' => 'Slaptažodžių atstatymai šiame wikyje išjungti.',
'passwordreset-emaildisabled' => 'El. pašto funkcijos uždraustos šiame wiki.',
'passwordreset-username' => 'Naudotojo vardas:',
'passwordreset-domain' => 'Domenas:',
'passwordreset-capture' => 'Peržiūrėti galutinį e-mail laišką?',
'passwordreset-capture-help' => 'Jei jūs čia pažymėsite, tai e-mail laiškas (su laikinuoju slaptažodžiu) bus parodytas jums prieš išsiunčiant jį naudotojui.',
'passwordreset-email' => 'E-pašto adresas:',
'passwordreset-emailtitle' => 'Paskyros informacija apie {{sitename}}',
'passwordreset-emailtext-ip' => 'Kažkas (tikriausiai jūs, IP adresu $1) paprašė priminti jūsų slaptažodį svetainėje {{SITENAME}} ($4). Šio naudotojo {PLURAL:$3|paskyra|paskyros}} yra susietos su šiuo elektroninio pašto adresu $2

{{PLURAL:$3|Šis laikinas slaptažodis |Šie laikini slaptažodžiai}} baigsis po {{PLURAL:$5|vienos dienos| $5 dienų}}. 

Jūs turėtumėte prisijungti ir pasirinkti naują slaptažodį. Jei kažkas kitas padarė šį prašymą arba jūs prisiminėte savo pirminį slaptažodį, ir jums nebereikia jo pakeisti, galite ignoruoti šį pranešimą ir toliau naudotis savo senuoju slaptažodžiu.',
'passwordreset-emailtext-user' => 'Naudotojas $1 svetainėje {{SITENAME}} sukūrė užklausą slaptažodžio priminimui svetainėje {{SITENAME}}
($4). Šio naudotojo {{PLURAL:$3|paskyra|paskyros}} susieto su šiuo elektroniniu paštu $2. 

{{PLURAL:$3|Šis laikinas slaptažodis|Šie laikini slaptažodžiai}} baigs galioti po {{PLURAL:$5|vienos dienos|$5 dienų}}. Jūs turėtumėte prisijungti ir pasirinkti naują slaptažodį. Jei kažkas padarė tai be jūsų žinios arba jūs prisiminėte savo pirminį slaptažodį, ir jūs nebenorite jo pakeisti, galite ignoruoti šį pranešimą ir toliau naudotis savo senuoju slaptažodžiu.',
'passwordreset-emailelement' => 'Naudotojo vardas: $1
Laikinas slaptažodis: $2',
'passwordreset-emailsent' => 'Slaptažodžio priminimo laiškas buvo išsiųstas.',
'passwordreset-emailsent-capture' => 'Slaptažodžio priminimo laiškas bus išsiųstas, toks koks parodytas.',
'passwordreset-emailerror-capture' => 'Priminimo e-mail laiškas buvo sugeneruotas, toks koks parodytas, bet pasiuntimas naudotojui buvo nesėkmingas: $1',

# Special:ChangeEmail
'changeemail' => 'Pakeisti el. pašto adresą',
'changeemail-header' => 'Keisti paskyros el. pašto adresą',
'changeemail-text' => 'Užpildykite šią formą, jei norite pakeisti savo el. pašto adresą. Jums reikės įvesti savo slaptažodį, siekiant patvirtinti šį pakeitimą.',
'changeemail-no-info' => 'Jūs turite būti prisijungęs, kad pasiektumėte puslapį tiesiogiai.',
'changeemail-oldemail' => 'Dabartinis el. pašto adresas:',
'changeemail-newemail' => 'Naujas el. pašto adresas:',
'changeemail-none' => '(nėra)',
'changeemail-password' => 'Jūsų {{SITENAME}} slaptažodis:',
'changeemail-submit' => 'Keisti el. pašto adresą',
'changeemail-cancel' => 'Atšaukti',

# Edit page toolbar
'bold_sample' => 'Paryškintas tekstas',
'bold_tip' => 'Paryškinti tekstą',
'italic_sample' => 'Tekstas kursyvu',
'italic_tip' => 'Tekstas kursyvu',
'link_sample' => 'Nuorodos pavadinimas',
'link_tip' => 'Vidinė nuoroda',
'extlink_sample' => 'http://www.example.com nuorodos pavadinimas',
'extlink_tip' => 'Išorinė nuoroda (nepamirškite http:// priedėlio)',
'headline_sample' => 'Skyriaus pavadinimas',
'headline_tip' => 'Antro lygio skyriaus pavadinimas',
'nowiki_sample' => 'Čia įterpkite neformuotą tekstą',
'nowiki_tip' => 'Ignoruoti wiki formatą',
'image_sample' => 'Pavyzdys.jpg',
'image_tip' => 'Įdėti failą',
'media_sample' => 'Pavyzdys.ogg',
'media_tip' => 'Nuoroda į failą',
'sig_tip' => 'Jūsų parašas bei laikas',
'hr_tip' => 'Horizontali linija (naudokite taupiai)',

# Edit pages
'summary' => 'Komentaras:',
'subject' => 'Tema/antraštė:',
'minoredit' => 'Tai smulkus pataisymas',
'watchthis' => 'Stebėti šį puslapį',
'savearticle' => 'Išsaugoti puslapį',
'preview' => 'Peržiūra',
'showpreview' => 'Rodyti peržiūrą',
'showlivepreview' => 'Tiesioginė peržiūra',
'showdiff' => 'Rodyti skirtumus',
'anoneditwarning' => "'''Dėmesio:''' Jūs nesate prisijungęs. Jūsų IP adresas bus įrašytas į šio puslapio istoriją.",
'anonpreviewwarning' => "''Jūs nesate prisijungęs. Išsaugojant jūsų IP adresas bus rodomas šio puslapio redagavimo istorijoje.''",
'missingsummary' => "'''Priminimas:''' Jūs nenurodėte keitimo komentaro. Jei vėl paspausite „{{int:savearticle}}“, jūsų keitimas bus išsaugotas be jo.",
'missingcommenttext' => 'Prašome įvesti komentarą.',
'missingcommentheader' => "'''Priminimas:''' Jūs nenurodėte šio komentaro pavadinimo/antraštės.
Jei vėl paspausite „{{int:savearticle}}“, jūsų keitimas bus įrašytas be jo.",
'summary-preview' => 'Komentaro peržiūra:',
'subject-preview' => 'Skyrelio/antraštės peržiūra:',
'blockedtitle' => 'Naudotojas yra užblokuotas',
'blockedtext' => "'''Jūsų naudotojo vardas arba IP adresas yra užblokuotas.'''

Užblokavo $1. Nurodyta priežastis yra ''$2''.

* Blokavimo pradžia: $8
* Blokavimo pabaiga: $6
* Numatytas blokuojamasis: $7

Jūs galite susisiekti su $1 arba kuriuo nors kitu [[{{MediaWiki:Grouppage-sysop}}|administratoriumi]] ir aptarti neaiškumus dėl blokavimo.
Atkreipkite dėmesį, kad negalėsite naudotis funkcija „Rašyti laišką šiam naudotojui“, jei nesate užsiregistravę ir pateikę realaus savo el. pašto adreso naudotojo [[Special:Preferences|nustatymuose]], arba, jei jums užblokuotas šios funkcijos naudojimas.
Jūsų IP adresas yra $3, o blokavimo ID yra #$5.
Prašome nurodyti vieną iš jų ar abu, kai kreipiatės dėl blokavimo.",
'autoblockedtext' => "Jūsų IP adresas buvo automatiškai užblokuotas, nes jį naudojo kitas naudotojas, kurį užblokavo $1.
Nurodyta priežastis yra ši:

:''$2''

* Blokavimo pradžia: $8
* Blokavimo pabaiga: $6
* Numatomas blokavimo laikas: $7

Jūs galite susisiekti su $1 arba kitu [[{{MediaWiki:Grouppage-sysop}}|administratoriumi]], kad aptartumėte neaiškumus dėl blokavimo.

Jūs negalite naudotis funkcija „Rašyti laišką šiam naudotojui“, jei nesate nurodę tikro el. pašto adreso savo [[Special:Preferences|naudotojo nustatymuose]]. Taip pat Jūs negalite naudotis šia funkcija, jei Jums užblokuotas jos naudojimas.

Jūsų IP adresas yra $3, blokavimo ID yra $5.
Prašome nurodyti šiuos duomenis visais atvejais, kai kreipiatės dėl blokavimo.",
'blockednoreason' => 'priežastis nenurodyta',
'whitelistedittext' => 'Jūs turite $1, kad redaguotumėte puslapius.',
'confirmedittext' => 'Jums reikia patvirtinti el. pašto adresą, prieš redaguojant puslapius.
Prašome nurodyti ir patvirtinti jūsų el. pašto adresą per jūsų [[Special:Preferences|naudotojo nustatymus]].',
'nosuchsectiontitle' => 'Nėra tokio skyriaus',
'nosuchsectiontext' => 'Jūs mėginote redaguoti skyrių, kuris neegzistuoja.
Jis galėjo būti perkeltas arba ištrintas, kol peržiūrėjote puslapį.',
'loginreqtitle' => 'Reikalingas prisijungimas',
'loginreqlink' => 'prisijungti',
'loginreqpagetext' => 'Jums reikia $1, kad matytumėte kitus puslapius.',
'accmailtitle' => 'Slaptažodis išsiųstas.',
'accmailtext' => "Atsitiktinai sugeneruotas naudotojo [[User talk:$1|$1]] slaptažodis nusiųstas į $2.

Šios naujos paskyros slaptažodis gali būti pakeistas ''[[Special:ChangePassword|keisti slaptažodį]]'' puslapyje.",
'newarticle' => '(Naujas)',
'newarticletext' => "Jūs patekote į dar neegzistuojantį puslapį.
Norėdami sukurti puslapį, pradėkite rašyti žemiau esančiame įvedimo lauke
(plačiau [[{{MediaWiki:Helppage}}|pagalbos puslapyje]]).
Jei patekote čia per klaidą, paprasčiausiai spustelkite  naršyklės mygtuką '''atgal'''.",
'anontalkpagetext' => "----''Tai yra anoniminio naudotojo, nesusikūrusio arba nenaudojančio paskyros, aptarimų puslapis.
Dėl to naudojamas IP adresas jo identifikavimui.
Šis IP adresas gali būti dalinamas keliems naudotojams.
Jeigu Jūs esate anoniminis naudotojas ir atrodo, kad komentarai nėra skirti Jums, [[Special:UserLogin/signup|sukurkite paskyrą]] arba [[Special:UserLogin|prisijunkite]], ir nebūsite tapatinamas su kitais anoniminiais naudotojais.''",
'noarticletext' => 'Šiuo metu šiame puslapyje nėra jokio teksto.
Jūs galite [[Special:Search/{{PAGENAME}}|ieškoti šio puslapio pavadinimo]] kituose puslapiuose,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ieškoti susijusių įrašų],
arba [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaguoti šį puslapį]</span>.',
'noarticletext-nopermission' => 'Šiuo metu šiame puslapyje nėra jokio teksto.
Jūs galite [[Special:Search/{{PAGENAME}}|ieškoti šio puslapio pavadinimo]] kituose puslapiuose,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ieškoti susijusių įrašų]</span>, bet jūs neturite teisės sukurti šį puslapį.',
'userpage-userdoesnotexist' => 'Naudotojo paskyra „<nowiki>$1</nowiki>“ yra neužregistruota. Prašom patikrinti, ar jūs norite kurti/redaguoti šį puslapį.',
'userpage-userdoesnotexist-view' => 'Naudotojo paskyra „$1“ neužregistruota.',
'blocked-notice-logextract' => 'Šis naudotojas šiuo metu yra užblokuotas.
Žemiau pateikiamas paskutinis blokavimo istorijos įrašas:',
'clearyourcache' => "'''Dėmesio:''' Išsaugojus jums gali prireikti išvalyti jūsų naršyklės podėlį, kad pamatytumėte pokyčius.
* '''Firefox / Safari:''' laikydami ''Shift'' pasirinkite ''Atsiųsti iš naujo'', arba paspauskite ''Ctrl-F5'' ar ''Ctrl-R'' (sistemoje Apple Mac ''Commandd-R'')
* '''Google Chrome:''' spauskite ''Ctrl-Shift-R'' (sistemoje Apple Mac ''Command-Shift-R'')
* '''Internet Explorer:''' laikydami ''Ctrl'' paspauskite ''Atnaujinti'', arba paspauskite ''Ctrl-F5''
* '''Konqueror:''' tiesiog paspauskite ''Perkrauti'' mygtuką, arba paspauskite ''F5''
* '''Opera''' pilnai išvalykite podėlį ''Priemonės→Nuostatos''.",
'usercssyoucanpreview' => "'''Patarimas:''' Naudokite „{{int:showpreview}}“ mygtuką, kad išmėgintumėte savo naująjį CSS prieš išsaugant.",
'userjsyoucanpreview' => "'''Patarimas:''' Naudokite „{{int:showpreview}}“ mygtuką, kad išmėgintumėte savo naująjį JS prieš išsaugant.",
'usercsspreview' => "'''Nepamirškite, kad jūs tik peržiūrit savo naudotojo CSS, jis dar nebuvo išsaugotas!'''",
'userjspreview' => "'''Nepamirškite, kad jūs tik testuojat/peržiūrit savo naudotojo JavaScript, jis dar nebuvo išsaugotas!'''",
'sitecsspreview' => "'''Nepamirškite, kad jūs tik peržiūrit šio CSS .'''! N!''' Tai dar nebuvo išsaugotas!'''",
'sitejspreview' => "'''Nepamirškite, kad jūs tik peržiūrit šis JavaScript kodas .'''! N!''' Tai dar nebuvo išsaugotas!'''",
'userinvalidcssjstitle' => "'''Dėmesio:''' Nėra jokios išvaizdos „$1“. Nepamirškite, kad savo .css ir .js puslapiai naudoja pavadinimą mažosiomis raidėmis, pvz., {{ns:user}}:Foo/vector.css, o ne {{ns:user}}:Foo/Vector.css.",
'updated' => '(Atnaujinta)',
'note' => "'''Pastaba:'''",
'previewnote' => "''Nepamirškite, kad tai tik peržiūra, pakeitimai dar nėra išsaugoti!'''",
'continue-editing' => 'Eiti į redagavimo sritį',
'previewconflict' => 'Ši peržiūra parodo tekstą iš viršutiniojo teksto redagavimo lauko taip, kaip jis bus rodomas, jei pasirinksite išsaugoti.',
'session_fail_preview' => "'''Atsiprašome! Mes negalime vykdyti jūsų keitimo dėl sesijos duomenų praradimo.
Prašome pamėginti vėl. Jei tai nepadeda, pamėginkite atsijungti ir prisijungti atgal.'''",
'session_fail_preview_html' => "'''Atsiprašome! Mes negalime apdoroti jūsų keitimo dėl sesijos duomenų praradimo.'''

''Kadangi {{SITENAME}} grynasis HTML yra įjungtas, peržiūra yra paslėpta kaip atsargumo priemonė prieš JavaScript atakas.''

'''Jei tai teisėtas keitimo bandymas, prašome pamėginti vėl. Jei tai nepadeda, pamėginkite [[Special:UserLogout|atsijungti]] ir prisijungti atgal.'''",
'token_suffix_mismatch' => "'''Jūsų pakeitimas buvo atmestas, nes jūsų naršyklė iškraipė skyrybos ženklus keitimo žymėje. Keitimas buvo atmestas norint apsaugoti puslapio tekstą nuo sugadinimo. Taip kartais būna, kai jūs naudojate anoniminį tarpinio serverio paslaugą.'''",
'edit_form_incomplete' => "'''Kai redaguoti formos dalys nepasiekė serverio; du kartus patikrinti, kad jūsų pakeitimai yra nesugadintos ir bandykite dar kartą.'''",
'editing' => 'Taisomas $1',
'creating' => 'Kuriama $1',
'editingsection' => 'Taisomas $1 (skyrelis)',
'editingcomment' => 'Taisomas $1 (komentaras)',
'editconflict' => 'Išpręskite konfliktą: $1',
'explainconflict' => "Kažkas kitas jau pakeitė puslapį nuo tada, kai jūs pradėjote jį redaguoti.
Viršutiniame tekstiniame lauke pateikta šiuo metu esanti puslapio versija.
Jūsų keitimai pateikti žemiau esančiame lauke.
Jums reikia sujungti jūsų pakeitimus su esančia versija.
Kai paspausite „{{int:savearticle}}“, bus įrašytas '''tik''' tekstas viršutiniame tekstiniame lauke.",
'yourtext' => 'Jūsų tekstas',
'storedversion' => 'Išsaugota versija',
'nonunicodebrowser' => "'''ĮSPĖJIMAS: Jūsų naršyklė nepalaiko unikodo. Kad būtų saugu redaguoti puslapį, ne ASCII simboliai redagavimo lauke bus rodomi kaip šešioliktainiai kodai.'''",
'editingold' => "'''ĮSPĖJIMAS: Jūs keičiate ne naujausią puslapio versiją.
Jei išsaugosite savo keitimus, po to daryti pakeitimai pradings.'''",
'yourdiff' => 'Skirtumai',
'copyrightwarning' => "Primename, kad viskas, kas patenka į {{SITENAME}}, yra laikoma paskelbtu pagal $2 (detaliau - $1). Jei nenorite, kad jūsų indėlis būtų be gailesčio redaguojamas ir platinamas, čia nerašykite.<br />
Jūs taip pat pasižadate, kad tai jūsų pačių rašytas turinys arba kopijuotas iš viešų ar panašių nemokamų šaltinių.
'''NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!'''",
'copyrightwarning2' => "Primename, kad viskas, kas patenka į {{SITENAME}} gali būti redaguojama, perdaroma, ar pašalinama kitų naudotojų. Jei nenorite, kad jūsų indėlis būtų be gailesčio redaguojamas, čia nerašykite.<br />
Taip pat jūs pasižadate, kad tai jūsų pačių rašytas tekstas arba kopijuotas
iš viešų ar panašių nemokamų šaltinių (detaliau - $1).
'''NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!'''",
'longpageerror' => "'''KLAIDA: Tekstas, kurį pateikėte, yra $1 {{PLURAL:$1|kilobaito|kilobaitų|kilobaitų}} ilgio, tai yra didesnis nei yra leistina. Yra leidžiami tiktai $2 {{PLURAL:$2|kilobaitas|kilobaitai|kilobaitų}}.''' Jis nebus išsaugotas.",
'readonlywarning' => "'''Įspėjimas: Duomenų bazė buvo užrakinta techninei profilaktikai, todėl šiuo metu negalėsite išsaugoti savo pakeitimų. Siūlome nusikopijuoti tekstą į tekstinį failą ir vėliau jį čia išsaugoti.'''

Ją užrakinusio administratoriaus paaiškinimas: $1",
'protectedpagewarning' => "'''Dėmesio: Šis puslapis yra užrakintas taip, kad jį redaguoti gali tik administratoriaus teises turintys naudotojai.'''
Naujausias įrašas žurnale yra pateiktas žemiau:",
'semiprotectedpagewarning' => "'''Pastaba:''' Šis puslapis buvo užrakintas, jį gali redaguoti tik registruoti naudotojai.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'cascadeprotectedwarning' => "'''Dėmesio''': Šis puslapis buvo užrakintas taip, kad tik naudotojai su administratoriaus teisėmis galėtų jį redaguoti, nes jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi:",
'titleprotectedwarning' => "'''Dėmesio: Šis puslapis buvo užrakintas taip, kad tik [[Special:ListGroupRights|kai kurie naudotojai]] galėtų jį sukurti.'''
Naujausias įrašas žurnale yra pateiktas žemiau:",
'templatesused' => '{{PLURAL:$1|Šablonas|Šablonai}}, naudojami puslapyje:',
'templatesusedpreview' => '{{PLURAL:$1|Šablonas|Šablonai}}, naudoti šioje peržiūroje:',
'templatesusedsection' => 'Šiame skyriuje {{PLURAL:$1|naudojamas šablonas|naudojami šablonai}}:',
'template-protected' => '(apsaugotas)',
'template-semiprotected' => '(pusiau apsaugotas)',
'hiddencategories' => 'Šis puslapis priklauso $1 {{PLURAL:$1|paslėptai kategorijai|paslėptoms kategorijoms|paslėptų kategorijų}}:',
'edittools' => '<!-- Šis tekstas bus rodomas po redagavimo ir įkėlimo formomis. -->',
'nocreatetext' => '{{SITENAME}} apribojo galimybę kurti naujus puslapius.
Jūs galite grįžti ir redaguoti jau esantį puslapį, arba [[Special:UserLogin|prisijungti arba sukurti paskyrą]].',
'nocreate-loggedin' => 'Jūs neturite teisės kurti puslapius.',
'sectioneditnotsupported-title' => 'Skyrių redagavimas nepalaikomas',
'sectioneditnotsupported-text' => 'Šiame puslapyje skyrių redagavimas nepalaikomas.',
'permissionserrors' => 'Teisių klaida',
'permissionserrorstext' => 'Jūs neturite teisių tai daryti dėl {{PLURAL:$1|šios priežasties|šių priežasčių}}:',
'permissionserrorstext-withaction' => 'Jūs neturite leidimo $2 dėl {{PLURAL:$1|šios priežasties|šių priežasčių}}:',
'recreate-moveddeleted-warn' => "'''Dėmesio: Jūs atkuriate puslapį, kuris anksčiau buvo ištrintas.'''

Turėtumėte nuspręsti, ar reikėtų toliau redaguoti šį puslapį.
Jūsų patogumui čia pateikiamas šio puslapio šalinimų ir perkėlimų sąrašas:",
'moveddeleted-notice' => 'Šis puslapis buvo ištrintas.
Žemiau pateikiamas puslapio šalinimų ir pervadinimų sąrašas.',
'log-fulllog' => 'Rodyti visą istoriją',
'edit-hook-aborted' => 'Keitimas nutrauktas užlūžimo.
Tam nėra paaiškinimo.',
'edit-gone-missing' => 'Negalima atnaujinti puslapio.
Greičiausiai jis yra ištrintas.',
'edit-conflict' => 'Redagavimo konfliktas.',
'edit-no-change' => 'Jūsų keitimas buvo ignoruotas kadangi nebuvo atlikta jokių teksto pakeitimų.',
'postedit-confirmation' => 'Jūsų pakeitimas išsaugotas.',
'edit-already-exists' => 'Negalima sukurti naujo puslapio.
Jis jau egzistuoja.',
'defaultmessagetext' => 'Numatytasis pranešimo tekstas',
'invalid-content-data' => 'Neleistinas turinys.',
'content-not-allowed-here' => 'Turinys "$1" puslapyje [[$2]] nėra leistinas.',
'editwarning-warning' => 'Palikdamas šį puslapį jūs prarasite visus padarytus pakeitimus.',

# Content models
'content-model-wikitext' => 'wikitekstas',
'content-model-text' => 'paprastasis tekstas',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Įspėjimas: Šiame puslapyje yra per daug užtrunkančių analizatoriaus funkcijų šaukinių.

Tai turėtų būti mažiau nei $2 {{PLURAL:$2|šaukinys|šaukiniai|šaukinių}}, tačiau dabar yra $1 {{PLURAL:$1|šaukinys|šaukiniai|šaukinių}}.',
'expensive-parserfunction-category' => 'Puslapiai su per daug brangių kodo analizuoklio funkcijų šaukinių',
'post-expand-template-inclusion-warning' => 'Įspėjimas: Šablonų įterpimo dydis per didelis.
Kai kurie šablonai nebus įtraukti.',
'post-expand-template-inclusion-category' => 'Puslapiai, kur šablonų įterpimo dydis viršijamas',
'post-expand-template-argument-warning' => 'Įspėjimas: Šis puslapis turi bent vieną šablono argumentą, kuris turi per didelį išplėtimo dydį.
Šie argumentai buvo praleisti.',
'post-expand-template-argument-category' => 'Puslapiai, turintys praleistų šablono argumentų',
'parser-template-loop-warning' => 'Aptiktas šablono ciklas: [[$1]]',
'parser-template-recursion-depth-warning' => 'Šablono rekursinio gylio riba viršyta ($1)',
'language-converter-depth-warning' => 'Kalbos keitiklio gylio riba viršyta ($1)',

# "Undo" feature
'undo-success' => 'Keitimas gali būti atšauktas. Prašome patikrinti palyginimą, esantį žemiau, kad patvirtintumėte, kad jūs tai ir norite padaryti, ir tada išsaugokite pakeitimus, esančius žemiau, kad užbaigtumėte keitimo atšaukimą.',
'undo-failure' => 'Keitimas negali būti atšauktas dėl konfliktuojančių tarpinių keitimų.',
'undo-norev' => 'Keitimas negali būti atšauktas, kadangi jis neegzistuoja arba buvo ištrintas.',
'undo-summary' => 'Atšauktas [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarimas]] | [[Special:Contributions/$2|{{MediaWiki:Contribslink}}]]) keitimas ($1 versija)',

# Account creation failure
'cantcreateaccounttitle' => 'Paskyrų kūrimas negalimas',
'cantcreateaccount-text' => "Paskyrų kūrimą iš šio IP adreso ('''$1''') užblokavo [[User:$3|$3]].

$3 nurodyta priežastis yra ''$2''",

# History pages
'viewpagelogs' => 'Rodyti šio puslapio specialiuosius veiksmus',
'nohistory' => 'Šis puslapis neturi keitimų istorijos.',
'currentrev' => 'Dabartinė versija',
'currentrev-asof' => 'Dabartinė $1 versija',
'revisionasof' => '$1 versija',
'revision-info' => '$1 versija naudotojo $2',
'previousrevision' => '←Ankstesnė versija',
'nextrevision' => 'Vėlesnė versija→',
'currentrevisionlink' => 'Dabartinė versija',
'cur' => 'dab',
'next' => 'kitas',
'last' => 'pask',
'page_first' => 'pirm',
'page_last' => 'pask',
'histlegend' => "Skirtumai tarp versijų: pažymėkite lyginamas versijas ir spustelkite ''Enter'' klavišą arba mygtuką apačioje.<br />
Žymėjimai: (dab) = palyginimas su naujausia versija,
(pask) = palyginimas su prieš tai buvusia versija, S = smulkus keitimas.",
'history-fieldset-title' => 'Ieškoti istorijoje',
'history-show-deleted' => 'Tik ištrinti',
'histfirst' => 'seniausi',
'histlast' => 'paskutiniai',
'historysize' => '($1 {{PLURAL:$1|baitas|baitai|baitų}})',
'historyempty' => '(tuščia)',

# Revision feed
'history-feed-title' => 'Versijų istorija',
'history-feed-description' => 'Šio puslapio versijų istorija projekte',
'history-feed-item-nocomment' => '$1 $2',
'history-feed-empty' => 'Prašomas puslapis neegzistuoja.
Jis galėjo būti ištrintas iš projekto, arba pervardintas.
Pamėginkite [[Special:Search|ieškoti projekte]] susijusių naujų puslapių.',

# Revision deletion
'rev-deleted-comment' => '(keitimo aprašymas pašalintas)',
'rev-deleted-user' => '(naudotojo vardas pašalintas)',
'rev-deleted-event' => '(įrašas pašalintas)',
'rev-deleted-user-contribs' => '[vardas arba IP adresas pašalintas - redagavimas paslėptas nuo prisidėjimų]',
'rev-deleted-text-permission' => "Ši puslapio versija buvo '''pašalinta'''.
Daugiau detalių galima rasti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-deleted-text-unhide' => "Ši puslapio versija buvo '''ištrinta'''.
Trynimo detales rasite [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} ištrintų puslapių sąraše].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-suppressed-text-unhide' => "Ši puslapio versija buvo '''paslėpta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų istorijoje].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-deleted-text-view' => "Ši puslapio versija buvo '''pašalinta'''.
Kaip administratorius, jūs galite ją pamatyti;
daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-suppressed-text-view' => "Ši puslapio versija buvo '''paslėpta'''.
Kaip administratorius, jūs galite ją peržiūrėti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų sąraše].",
'rev-deleted-no-diff' => "Jūs negalite peržiūrėti šio skirtumo, nes viena iš versijų yra '''ištrinta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} trynimų istorijoje].",
'rev-suppressed-no-diff' => "Jūs negalite peržiūrėti šio skirtumo, nes viena iš versijų buvo '''ištrinta'''.",
'rev-deleted-unhide-diff' => "Viena iš šio skirtumo versijų yra '''ištrinta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} trynimų istorijoje].
Kaip administratorius, jūs vis tiek galite [$1 pamatyti šį skirtumą].",
'rev-suppressed-unhide-diff' => "Viena iš šio skirtumo versijų buvo '''paslėpta'''.
Daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų istorijoje].
Kaip administratorius, jūs vis dar galite [$1 peržiūrėti šią versiją].",
'rev-deleted-diff-view' => "Viena iš šio palyginimo versija buvo '''pašalinta'''.
Kaip administratorius, jūs galite ją pamatyti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} trynimų žurnale].",
'rev-suppressed-diff-view' => "Viena iš šio palyginimo versija buvo '''paslėpta'''.
Kaip administratorius, jūs galite ją pamatyti; daugiau detalių gali būti [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} slėpimų žurnale].",
'rev-delundel' => 'rodyti/slėpti',
'rev-showdeleted' => 'rodyti',
'revisiondelete' => 'Trinti/atkurti versijas',
'revdelete-nooldid-title' => 'Neleistina paskirties versija',
'revdelete-nooldid-text' => 'Jūs nenurodėte versijos (-ų), kurioms įvykdyti šią funkciją, nurodyta versija neegzistuoja arba jūs bandote paslėpti esamą versiją.',
'revdelete-nologtype-title' => 'Nenurodytas istorijos tipas',
'revdelete-nologtype-text' => 'Jūs nenurodėte istorijos tipo, kuriam atlikti šį veiksmą.',
'revdelete-nologid-title' => 'Neleistinas istorijos įrašas',
'revdelete-nologid-text' => 'Jūs arba nenurodėte paskirties istorijos įvykio, kuriam atlikti šį veiksmą, arba nurodytas įrašas neegzistuoja.',
'revdelete-no-file' => 'Nurodytas failas neegzistuoja.',
'revdelete-show-file-confirm' => 'Ar tikrai norite peržiūrėti ištrintą failo „<nowiki>$1</nowiki>“ $2 $3 versiją?',
'revdelete-show-file-submit' => 'Taip',
'revdelete-selected' => "'''{{PLURAL:$2|Pasirinkta [[:$1]] versija|Pasirinktos [[:$1]] versijos}}:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Pasirinktas istorijos įvykis|Pasirinkti istorijos įvykiai}}:'''",
'revdelete-text' => "'''Ištrintos versijos bei įvykiai vis tiek dar bus rodomi puslapio istorijoje ir specialiųjų veiksmų sąraše, bet jų turinio dalys nebus viešai prieinamos.'''
Kiti administratoriai iš {{SITENAME}} vis tiek galės pasiekti paslėptą turinį ir galės jį atkurti per tą pačią sąsają, nebent yra nustatyti papildomi apribojimai.",
'revdelete-confirm' => 'Prašome patvirtinti, kad jūs tai ketinate padaryti, kad jūs suprantate padarinius, ir kad jūs tai darote pagal [[{{MediaWiki:Policy-url}}|politiką]].',
'revdelete-suppress-text' => "Ištrynimas turėtų būti taikomas '''tik''' šiais atvejais:
* Netinkama asmeninė informacija
*: ''namų adresai, telefonų numeriai, asmens kodai ir t. t.''",
'revdelete-legend' => 'Nustatyti matomumo apribojimus:',
'revdelete-hide-text' => 'Slėpti versijos tekstą',
'revdelete-hide-image' => 'Slėpti failo turinį',
'revdelete-hide-name' => 'Slėpti veiksmą ir paskirtį',
'revdelete-hide-comment' => 'Slėpti redagavimo komentarą',
'revdelete-hide-user' => 'Slėpti redagavusiojo naudotojo vardą ar IP adresą',
'revdelete-hide-restricted' => 'Nuslėpti duomenis nuo adminstratorių kaip ir nuo kitų',
'revdelete-radio-same' => '(nekeisti)',
'revdelete-radio-set' => 'Taip',
'revdelete-radio-unset' => 'Ne',
'revdelete-suppress' => 'Slėpti duomenis nuo administratorių kaip ir nuo kitų',
'revdelete-unsuppress' => 'Šalinti apribojimus atkurtose versijose',
'revdelete-log' => 'Priežastis:',
'revdelete-submit' => 'Taikyti {{PLURAL:$1|pasirinktai versijai|pasirinktoms versijoms}}',
'revdelete-success' => "'''Versijos matomumas sėkmingai pakeistas.'''",
'revdelete-failure' => "'''Versijos rodomumas negali būti nustatytas:'''
$1",
'logdelete-success' => "'''Įvykio matomumas sėkmingai nustatytas.'''",
'logdelete-failure' => "'''Sąrašo rodomumas negali būti nustatytas:'''
$1",
'revdel-restore' => 'Keisti matomumą',
'revdel-restore-deleted' => 'Ištrintos versijos',
'revdel-restore-visible' => 'Matomos versijos',
'pagehist' => 'Puslapio istorija',
'deletedhist' => 'Ištrinta istorija',
'revdelete-hide-current' => 'Klaida slepiant $1, $2 keitimą: tai yra dabartinė versija.
Ji negali būti paslėpta.',
'revdelete-show-no-access' => 'Klaida rodant $1, $2 keitimą: jis pažymėtas ženklu „apribotas“.
Jūs neturite teisių jo peržiūrai.',
'revdelete-modify-no-access' => 'Klaida taisant $1, $2 keitimą: jis pažymėtas ženklu „apribotas“.
Jūs neturite teisių jo taisymui.',
'revdelete-modify-missing' => 'Klaida keičiant versiją $1: ji nerandama duomenų bazėje!',
'revdelete-no-change' => "'''Įspėjimas:''' versija $2, $1 jau turi norimus rodomumo nustatymus.",
'revdelete-concurrent-change' => 'Klaida keičiant $2, $1 versiją: jos statusas jau buvo pakeistas kažkieno kito kol jūs redagavote.
Prašome patikrinti sąrašus.',
'revdelete-only-restricted' => 'Klaida slepiant $1 $2 elementą: jūs negalite paslėpti elementų nuo administratorių peržiūros nepasirenkant vieno iš kitų matomumo nustatymų.',
'revdelete-reason-dropdown' => '*Dažnos trynimo priežastys
** Autorinių teisių pažeidimas
** Netinkamas komentaras ar asmeninė informacija
** Netinkamas naudotojo vardas
** Informacija, kuri gali būti šmeižikiška',
'revdelete-otherreason' => 'Kita/papildoma priežastis:',
'revdelete-reasonotherlist' => 'Kita priežastis',
'revdelete-edit-reasonlist' => 'Keisti trynimo priežastis',
'revdelete-offender' => 'Versijos autorius:',

# Suppression log
'suppressionlog' => 'Trynimo sąrašas',
'suppressionlogtext' => 'Žemiau yra trynimų ir blokavimų sąrašas, įtraukiant turinį, paslėptą nuo administratorių.
Žiūrėkite [[Special:BlockList|blokavimų sąrašą]], kad rastumėte dabar veikiančius draudimus ir blokavimus.',

# History merging
'mergehistory' => 'Sujungti puslapių istorijas',
'mergehistory-header' => "Šis puslapis leidžia jus prijungti vieno pirminio puslapio istorijos versijas į naujesnį puslapį. Įsitikinkite, kad šis pakeitimas palaikys istorinį puslapio tęstinumą.

'''Turi likti bent dabartinė pirminio puslapio versija.'''",
'mergehistory-box' => 'Sujungti dviejų puslapių versijas:',
'mergehistory-from' => 'Pirminis puslapis:',
'mergehistory-into' => 'Paskirties puslapis:',
'mergehistory-list' => 'Sujungiamos keitimų istorijos',
'mergehistory-merge' => 'Šios [[:$1]] versijos gali būti sujungtos į [[:$2]]. Naudokite akučių stulpelį, kad sujungtumėte tik tas versijas, kurios sukurtos tuo ar ankstesniu laiku. Pastaba: panaudojus navigacines nuorodas, šis stulpelis bus grąžintas į pradinę būseną.',
'mergehistory-go' => 'Rodyti sujungiamus keitimus',
'mergehistory-submit' => 'Sujungti versijas',
'mergehistory-empty' => 'Versijos negali būti sujungtos',
'mergehistory-success' => '$3 [[:$1]] {{PLURAL:$3|versija|versijos|versijų}} sėkmingai {{PLURAL:$3|sujungta|sujungtos|sujungta}} su [[:$2]].',
'mergehistory-fail' => 'Nepavyksta atlikti istorijų sujungimo, prašome patikrinti puslapio ir laiko parametrus.',
'mergehistory-no-source' => 'Šaltinio puslapis $1 neegzistuoja.',
'mergehistory-no-destination' => 'Rezultato puslapis $1 neegzistuoja.',
'mergehistory-invalid-source' => 'Pradinis puslapis turi turėti leistiną pavadinimą.',
'mergehistory-invalid-destination' => 'Rezultato puslapis turi turėti leistiną pavadinimą.',
'mergehistory-autocomment' => '[[:$1]] prijungtas prie [[:$2]]',
'mergehistory-comment' => '[[:$1]] prijungtas prie [[:$2]]: $3',
'mergehistory-same-destination' => 'Šaltinio ir tikslo puslapiai negali būti vienodi',
'mergehistory-reason' => 'Priežastis:',

# Merge log
'mergelog' => 'Sujungimų sąrašas',
'pagemerge-logentry' => 'sujungė [[$1]] su [[$2]] (versijos iki $3)',
'revertmerge' => 'Atskirti',
'mergelogpagetext' => 'Žemiau yra paskiausių vieno su kitu puslapių sujungimų sąrašas.',

# Diffs
'history-title' => '„$1“ versijų istorija',
'difference-title' => '$1: Skirtumas tarp puslapio versijų',
'difference-title-multipage' => 'Skirtumas tarp puslapių „$1 ir $2“',
'difference-multipage' => '(Skirtumai tarp puslapių)',
'lineno' => 'Eilutė $1:',
'compareselectedversions' => 'Palyginti pasirinktas versijas',
'showhideselectedversions' => 'Rodyti/slėpti pasirinktas versijas',
'editundo' => 'atšaukti',
'diff-multi' => '($2 {{PLURAL:$2|naudotojo|naudotojų|naudotojų}} $1 {{PLURAL:$1|tarpinis keitimas nėra rodomas|tarpiniai keitimai nėra rodomi|tarpinių keitimų nėra rodoma}})',
'diff-multi-manyusers' => '(daugiau nei $2 {{PLURAL:$2|naudotojo|naudotojų|naudotojų}} $1 {{PLURAL:$1|tarpinis keitimas nėra rodomas|tarpiniai keitimai nėra rodomi|tarpinių keitimų nėra rodoma}})',

# Search results
'searchresults' => 'Paieškos rezultatai',
'searchresults-title' => 'Paieškos rezultatai „$1“',
'searchresulttext' => 'Daugiau informacijos apie paiešką projekte {{SITENAME}} rasite [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => "Jūs ieškote '''[[:$1]]''' ([[Special:Prefixindex/$1|visi puslapiai, prasidedantys „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|visi puslapiai, rodantys į „$1“]])",
'searchsubtitleinvalid' => "Ieškoma '''$1'''",
'toomanymatches' => 'Perdaug atitikmenų buvo grąžinta. Prašome pabandyti kitokią užklausą',
'titlematches' => 'Puslapių pavadinimų atitikmenys',
'notitlematches' => 'Jokių pavadinimo atitikmenų',
'textmatches' => 'Puslapio turinio atitikmenys',
'notextmatches' => 'Jokių puslapių teksto atitikmenų',
'prevn' => '{{PLURAL:$1|atgal|ankstesnius $1}}',
'nextn' => '{{PLURAL:$1|toliau|tolimesnius $1}}',
'prevn-title' => '{{PLURAL:$1|Ankstesnis $1 rezultatas|Ankstesni $1 rezultatai|Ankstesni $1 rezultatų}}',
'nextn-title' => '{{PLURAL:$1|Kitas $1 rezultatas|Kiti $1 rezultatai|Kiti $1 rezultatų}}',
'shown-title' => 'Rodyti $1 {{PLURAL:$1|rezultatą|rezultatus|rezultatus}} puslapyje',
'viewprevnext' => 'Žiūrėti ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Paieškos nustatymai',
'searchmenu-exists' => "'''Puslapis pavadinimu „[[$1]]“ šioje wiki'''",
'searchmenu-new' => "'''Sukurti puslapį „[[:$1]]“ šioje wiki!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Ieškoti puslapių su šiuo priešdėliu]]',
'searchprofile-articles' => 'Turinio puslapiai',
'searchprofile-project' => 'Pagalbos ir projekto puslapiai',
'searchprofile-images' => 'Daugialypės terpės failai',
'searchprofile-everything' => 'Viskas',
'searchprofile-advanced' => 'Išplėstinė',
'searchprofile-articles-tooltip' => 'Ieškoti čia: $1',
'searchprofile-project-tooltip' => 'Ieškoti čia: $1',
'searchprofile-images-tooltip' => 'Ieškoti failų',
'searchprofile-everything-tooltip' => 'Ieškoti viso turinio (tame tarpe aptarimų puslapių)',
'searchprofile-advanced-tooltip' => 'Ieškoti skirtingose vardų srityse',
'search-result-size' => '$1 ({{PLURAL:$2|1 žodis|$2 žodžiai|$2 žodžių}})',
'search-result-category-size' => '{{PLURAL:$1|1 narys|$1 narių}} ({{PLURAL:$2|1 subkategorijoje|$2 subkategorijų}}, {{PLURAL:$3|1 failas|$3 failų}})',
'search-result-score' => 'Tinkamumas: $1%',
'search-redirect' => '(peradresavimas $1)',
'search-section' => '(skyrius $1)',
'search-suggest' => 'Galbūt norėjote $1',
'search-interwiki-caption' => 'Dukteriniai projektai',
'search-interwiki-default' => '$1 rezultatai:',
'search-interwiki-more' => '(daugiau)',
'search-relatedarticle' => 'Susiję',
'mwsuggest-disable' => 'Slėpti AJAX pasiūlymus',
'searcheverything-enable' => 'Ieškoti visose vardų srityse',
'searchrelated' => 'susiję',
'searchall' => 'visi',
'showingresults' => "Žemiau rodoma iki '''$1''' {{PLURAL:$1|rezultato|rezultatų|rezultatų}} pradedant #'''$2'''.",
'showingresultsnum' => "Žemiau rodoma '''$3''' {{PLURAL:$3|rezultato|rezultatų|rezultatų}}rezultatų pradedant #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Rezultatas '''$1''' iš '''$3'''|Rezultatai '''$1 - $2''' iš '''$3'''}} pagal užklausą '''$4'''",
'nonefound' => "'''Pastaba''': Pagal nutylėjimą ieškoma tik kai kuriose vardų srityse. Pamėginkite prirašyti priešdėlį ''all:'', jei norite ieškoti viso turinio (įskaitant aptarimo puslapius, šablonus ir t. t.), arba naudokite norimą vardų sritį kaip priešdėlį.",
'search-nonefound' => 'Nėra rezultatų, atitinkančių užklausą.',
'powersearch' => 'Išplėstinė paieška',
'powersearch-legend' => 'Išplėstinė paieška',
'powersearch-ns' => 'Ieškoti vardų srityse:',
'powersearch-redir' => 'Įtraukti peradresavimus',
'powersearch-field' => 'Ieškoti',
'powersearch-togglelabel' => 'Pažymėti:',
'powersearch-toggleall' => 'Viską',
'powersearch-togglenone' => 'Nieko',
'search-external' => 'Išorinė paieška',
'searchdisabled' => 'Projekto {{SITENAME}} paieška yra uždrausta. Galite pamėginti ieškoti Google paieškos sistemoje. Paieškos sistemoje projekto {{SITENAME}} duomenys gali būti pasenę.',

# Preferences page
'preferences' => 'Nustatymai',
'mypreferences' => 'Nustatymai',
'prefs-edits' => 'Keitimų skaičius:',
'prefsnologin' => 'Neprisijungęs',
'prefsnologintext' => 'Jums reikia būti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prisijungusiam]</span>, kad galėtumėte keisti savo nustatymus.',
'changepassword' => 'Pakeisti slaptažodį',
'prefs-skin' => 'Išvaizda',
'skin-preview' => 'Peržiūra',
'datedefault' => 'Jokio pasirinkimo',
'prefs-beta' => 'Beta funkcijos',
'prefs-datetime' => 'Data ir laikas',
'prefs-labs' => 'Bandomosios funkcijos',
'prefs-user-pages' => 'Naudotojo puslapiai',
'prefs-personal' => 'Naudotojo profilis',
'prefs-rc' => 'Naujausi keitimai',
'prefs-watchlist' => 'Stebimų sąrašas',
'prefs-watchlist-days' => 'Dienos rodomos stebimųjų sąraše:',
'prefs-watchlist-days-max' => 'Daugiausiai 7 {{PLURAL:$1|diena|dienos|dienų}}',
'prefs-watchlist-edits' => 'Kiek daugiausia keitimų rodyti išplėstiniame stebimųjų sąraše:',
'prefs-watchlist-edits-max' => 'Didžiausias skaičius: 1000',
'prefs-watchlist-token' => 'Stebimųjų sąrašo raktas:',
'prefs-misc' => 'Įvairūs nustatymai',
'prefs-resetpass' => 'Keisti slaptažodį',
'prefs-changeemail' => 'Keisti el. pašto adresą',
'prefs-setemail' => 'Nustatyti el. pašto adresą',
'prefs-email' => 'El. pašto nustatymai',
'prefs-rendering' => 'Išvaizda',
'saveprefs' => 'Išsaugoti',
'resetprefs' => 'Išvalyti neišsaugotus pakeitimus',
'restoreprefs' => 'Grąžinti visus numatytuosius nustatymus',
'prefs-editing' => 'Redagavimas',
'rows' => 'Eilutės:',
'columns' => 'Stulpeliai:',
'searchresultshead' => 'Paieškos nustatymai',
'resultsperpage' => 'Rezultatų puslapyje:',
'stub-threshold' => 'Puslapį žymėti <a href="#" class="stub">nebaigtu</a>, jei mažesnis nei:',
'stub-threshold-disabled' => 'Išjungtas',
'recentchangesdays' => 'Rodomos dienos paskutinių keitimų sąraše:',
'recentchangesdays-max' => '(daugiausiai $1 {{PLURAL:$1|diena|dienos|dienų}})',
'recentchangescount' => 'Numatytasis rodomas keitimų skaičius:',
'prefs-help-recentchangescount' => 'Į tai įeina naujausi keitimai, puslapių istorijos ir specialiųjų veiksmų sąrašai.',
'savedprefs' => 'Nustatymai sėkmingai išsaugoti.',
'timezonelegend' => 'Laiko juosta:',
'localtime' => 'Vietinis laikas:',
'timezoneuseserverdefault' => 'Naudoti wiki pradinį ($1)',
'timezoneuseoffset' => 'Kita (patikslinti skirtumą)',
'timezoneoffset' => 'Skirtumas¹:',
'servertime' => 'Serverio laikas:',
'guesstimezone' => 'Paimti iš naršyklės',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktida',
'timezoneregion-arctic' => 'Arktis',
'timezoneregion-asia' => 'Azija',
'timezoneregion-atlantic' => 'Atlanto vandenynas',
'timezoneregion-australia' => 'Australija',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Indijos vandenynas',
'timezoneregion-pacific' => 'Ramusis vandenynas',
'allowemail' => 'Leisti siųsti el. laiškus iš kitų naudotojų',
'prefs-searchoptions' => 'Paieška',
'prefs-namespaces' => 'Vardų sritys',
'defaultns' => 'Pagal nutylėjimą ieškoti šiose vardų srityse:',
'default' => 'pagal nutylėjimą',
'prefs-files' => 'Failai',
'prefs-custom-css' => 'Asmeninis CSS',
'prefs-custom-js' => 'Asmeninis JavaSript',
'prefs-common-css-js' => 'Bendras CSS/JS visoms išvaizdoms:',
'prefs-reset-intro' => 'Jūs galite pasinaudoti šiuo puslapiu, kad grąžintumėte savo nustatymus į svetainės numatytuosius.
Tai nebeatšaukiama.',
'prefs-emailconfirm-label' => 'El. pašto patvirtinimas:',
'youremail' => 'El. paštas:',
'username' => '{{GENDER:$1Naudotojo vardas}}:',
'uid' => '{{GENDER:$1|Naudotojo}} ID:',
'prefs-memberingroups' => '{{PLURAL:$1|Grupės|Grupių}} narys:',
'prefs-registration' => 'Registravimosi laikas:',
'yourrealname' => 'Tikrasis vardas:',
'yourlanguage' => 'Sąsajos kalba:',
'yourvariant' => 'Kalbos variantas:',
'prefs-help-variant' => 'Puslapio tūrinis šioje viki yra rodomas, naudojant jūsų pasirinktą variantą arba rašymo kryptį.',
'yournick' => 'Parašas:',
'prefs-help-signature' => 'Komentarai aptarimų puslapiuose turėtų būti pasirašyti su „<nowiki>~~~~</nowiki>“, kuris bus paverstas į jūsų parašą ir laiką.',
'badsig' => 'Neteisingas parašas; patikrinkite HTML žymes.',
'badsiglength' => 'Jūsų parašas per ilgas.
Jį turi sudaryti ne daugiau kaip $1 {{PLURAL:$1|simbolis|simboliai|simbolių}}.',
'yourgender' => 'Lytis:',
'gender-unknown' => 'Nenurodyta',
'gender-male' => 'Vyras',
'gender-female' => 'Moteris',
'prefs-help-gender' => 'Pasirinktinai: naudojama teisingam sistemos kreipimuisi į jus.
Ši informacija yra vieša.',
'email' => 'El. paštas',
'prefs-help-realname' => 'Tikrasis vardas yra neprivalomas.
Jei jūs jį įvesite, jis bus naudojamas pažymėti jūsų darbą.',
'prefs-help-email' => 'E-pašto adresas yra neprivalomas, tačiau reikalingas slaptažodį naujo, turi tu pamiršai savo slaptažodį.',
'prefs-help-email-others' => 'Taip pat galite pasirinkti, kad žmonės galėtų susisiekti su jumis per jūsų naudotojo ar naudotojo aptarimo puslapį neatskleidžiant jūsų tapatybės.',
'prefs-help-email-required' => 'El. pašto adresas yra būtinas.',
'prefs-info' => 'Pagrindinė informacija',
'prefs-i18n' => 'Kalbos nustatymai',
'prefs-signature' => 'Parašas',
'prefs-dateformat' => 'Datos formatas',
'prefs-timeoffset' => 'Laiko skirtumas',
'prefs-advancedediting' => 'Bendras',
'prefs-editor' => 'Redaktorius',
'prefs-preview' => 'Peržiūra',
'prefs-advancedrc' => 'Papildomi nustatymai',
'prefs-advancedrendering' => 'Papildomi nustatymai',
'prefs-advancedsearchoptions' => 'Papildomi nustatymai',
'prefs-advancedwatchlist' => 'Papildomi nustatymai',
'prefs-displayrc' => 'Rodymo nustatymai',
'prefs-displaysearchoptions' => 'Rodymo nuostatos',
'prefs-displaywatchlist' => 'Rodymo nuostatos',
'prefs-diffs' => 'Skirtumai',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Panašu, kad E-pašto adresas yra teisingas',
'email-address-validity-invalid' => 'Įveskite korektišką e-pašto adresą',

# User rights
'userrights' => 'Naudotojų teisių valdymas',
'userrights-lookup-user' => 'Tvarkyti naudotojo grupes',
'userrights-user-editname' => 'Įveskite naudotojo vardą:',
'editusergroup' => 'Redaguoti naudotojo grupes',
'editinguser' => "Taisomos naudotojo '''[[User:$1|$1]]''' $2
teisės",
'userrights-editusergroup' => 'Redaguoti naudotojų grupes',
'saveusergroups' => 'Saugoti naudotojų grupes',
'userrights-groupsmember' => 'Narys:',
'userrights-groupsmember-auto' => 'Narys automatiškai:',
'userrights-groups-help' => 'Jūs galite pakeisti grupes, kuriose yra šis naudotojas:
* Pažymėtas langelis reiškia, kad šis naudotojas yra toje grupėje.
* Nepažymėtas langelis reiškia, kad šis naudotojas nėra toje grupėje.
* * parodo, kad jūs nebegalėsite pašalinti grupės, kai ją pridėsite, ir atvirkščiai.',
'userrights-reason' => 'Priežastis:',
'userrights-no-interwiki' => 'Jūs neturite leidimo keisti naudotojų teises kituose projektuose.',
'userrights-nodatabase' => 'Duomenų bazė $1 neegzistuoja arba yra ne vietinė.',
'userrights-nologin' => 'Jūs privalote [[Special:UserLogin|prisijungti]] kaip administratorius, kad galėtumėte priskirti naudotojų teises.',
'userrights-notallowed' => 'Jūsų paskyra neturi teisių priskirti ar panaikinti naudotojų teises.',
'userrights-changeable-col' => 'Grupės, kurias galite keisti',
'userrights-unchangeable-col' => 'Grupės, kurių negalite keisti',
'userrights-conflict' => 'Naudotojo teisių konfliktas! Prašome dar kartą taikyti savo keitimus.',

# Groups
'group' => 'Grupė:',
'group-user' => 'Naudotojai',
'group-autoconfirmed' => 'Automatiškai patvirtinti naudotojai',
'group-bot' => 'Robotai',
'group-sysop' => 'Administratoriai',
'group-bureaucrat' => 'Biurokratai',
'group-suppress' => 'Peržiūrėtojai',
'group-all' => '(visi)',

'group-user-member' => '{{GENDER:$1|naudotojas|naudotoja}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automatiškai patvirtintas naudotojas|automatiškai patvirtinta naudotoja}}',
'group-bot-member' => 'Botas',
'group-sysop-member' => 'Administratorius',
'group-bureaucrat-member' => 'Biurokratas',
'group-suppress-member' => 'Peržiūrėtojas',

'grouppage-user' => '{{ns:project}}:Naudotojai',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatiškai patvirtinti naudotojai',
'grouppage-bot' => '{{ns:project}}:Robotai',
'grouppage-sysop' => '{{ns:project}}:Administratoriai',
'grouppage-bureaucrat' => '{{ns:project}}:Biurokratai',
'grouppage-suppress' => '{{ns:project}}:Peržiūra',

# Rights
'right-read' => 'Skaityti puslapius',
'right-edit' => 'Redaguoti puslapius',
'right-createpage' => 'Kurti puslapius (kurie nėra aptarimų puslapiai)',
'right-createtalk' => 'Kurti aptarimų puslapius',
'right-createaccount' => 'Kurti naujas naudotojų paskyras',
'right-minoredit' => 'Žymėti keitimus kaip smulkius',
'right-move' => 'Pervadinti puslapius',
'right-move-subpages' => 'Perkelti puslapius su jų subpuslapiais',
'right-move-rootuserpages' => 'Perkelti šakninius naudotojo puslapius',
'right-movefile' => 'Perkelti failus',
'right-suppressredirect' => 'Nekurti peradresavimo iš seno pavadinimo, kuomet puslapis pervadinamas',
'right-upload' => 'Įkelti failus',
'right-reupload' => 'Perrašyti egzistuojantį failą',
'right-reupload-own' => 'Perrašyti paties įkeltą egzistuojantį failą',
'right-reupload-shared' => 'Perrašyti failus bendrojoje failų saugykloje lokaliai',
'right-upload_by_url' => 'Įkelti failą iš URL adreso',
'right-purge' => 'Išvalyti svetainės podėlį puslapiui be patvirtinimo',
'right-autoconfirmed' => 'Redaguoti pusiau užrakintus puslapius',
'right-bot' => 'Laikyti automatiniu procesu',
'right-nominornewtalk' => 'Atlikus smulkių keitimų aptarimų puslapiuose įjungia pranešimą apie naujas žinutes',
'right-apihighlimits' => 'Mažesni apribojimai API užklausoms',
'right-writeapi' => 'Naudoti rašymo API',
'right-delete' => 'Trinti puslapius',
'right-bigdelete' => 'Ištrinti puslapius su ilga istorija',
'right-deletelogentry' => 'Naikinti ir anuliuoti konkrečius žurnalo įrašus',
'right-deleterevision' => 'Ištrinti ir atkurti specifines puslapių versijas',
'right-deletedhistory' => 'Žiūrėti ištrintų puslapių istoriją, nerodant susieto teksto',
'right-deletedtext' => 'Peržiūrėti ištrintą tekstą ir skirtumus tarp ištrintų puslapio versijų.',
'right-browsearchive' => 'Ieškoti ištrintų puslapių',
'right-undelete' => 'Atkurti puslapį',
'right-suppressrevision' => 'Peržiūrėti ir atkurti versijas, paslėptas nuo administratorių',
'right-suppressionlog' => 'Žiūrėti privačius įvykių sąrašus',
'right-block' => 'Blokuoti redagavimo galimybę kitiems naudotojams',
'right-blockemail' => 'Blokuoti elektroninio pašto siuntimo galimybę naudotojui',
'right-hideuser' => 'Blokuoti naudotojo vardą, paslepiant jį nuo viešinimo',
'right-ipblock-exempt' => 'Apeiti IP blokavimus, autoblokavimus ir pakopinius blokavimus',
'right-proxyunbannable' => 'Apeiti automatinius proxy serverių blokavimus',
'right-unblockself' => 'Atblokuoti pačius',
'right-protect' => 'Pakeisti apsaugos lygius ir redaguoti apsaugotus puslapius',
'right-editprotected' => 'Redaguoti apsaugotus puslapius (be pakopinės apsaugos)',
'right-editinterface' => 'Redaguoti naudotojo aplinką',
'right-editusercssjs' => 'Redaguoti kitų naudotojų CSS ir JS failus',
'right-editusercss' => 'Redaguoti kitų naudotojų CSS failus',
'right-edituserjs' => 'Redaguoti kitų naudotojų JS failus',
'right-editmyusercss' => 'Redaguoti savo vartotojo CSS failus',
'right-editmyuserjs' => 'Redaguokite savo naudotojo vartotojo JavaScript failus',
'right-viewmywatchlist' => 'Peržiūrėti savo stebimų sąrašą',
'right-editmyoptions' => 'Redaguoti savo nuostatas',
'right-rollback' => 'Greitai atmesti paskutinio naudotojo tam tikro puslapio pakeitimus',
'right-markbotedits' => 'Žymėti atmestus keitimus kaip atliktus boto',
'right-noratelimit' => 'Netaikyti greičio apribojimų',
'right-import' => 'Importuoti puslapius iš kitų wiki',
'right-importupload' => 'Puslapių importas per failų įkėlimą',
'right-patrol' => 'Pažymėti kitų keitimus kaip patikrintus',
'right-autopatrol' => 'Keitimai automatiškai pažymimi kaip patikrinti',
'right-patrolmarks' => 'Atžymų apie patikrą peržiūra naujausiuose keitimuose',
'right-unwatchedpages' => 'Žiūrėti nestebimų puslapių sąrašą',
'right-mergehistory' => 'Sulieti puslapių istorijas',
'right-userrights' => 'Redaguoti visų naudotojų teises',
'right-userrights-interwiki' => 'Keisti naudotojų teises kitose wiki svetainėse',
'right-siteadmin' => 'Atrakinti ir užrakinti duomenų bazę',
'right-override-export-depth' => 'Eksportuoti puslapius įtraukiant susietus puslapius iki 5 lygio gylio',
'right-sendemail' => 'Siųsti el. laišką kitiems naudotojams',
'right-passwordreset' => 'Peržiūrėti slaptažodžio pakeitimo e-mail laiškus',

# Special:Log/newusers
'newuserlogpage' => 'Prisiregistravę naudotojai',
'newuserlogpagetext' => 'Tai naudotojų kūrimo sąrašas.',

# User rights log
'rightslog' => 'Naudotojų teisių pakeitimai',
'rightslogtext' => 'Pateikiamas naudotojų teisių pakeitimų sąrašas.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'skaityti šį puslapį',
'action-edit' => 'redaguoti šį puslapį',
'action-createpage' => 'kurti puslapius',
'action-createtalk' => 'kurti aptarimų puslapius',
'action-createaccount' => 'kurti šią naudotojo paskyrą',
'action-minoredit' => 'žymėti keitimą kaip smulkų',
'action-move' => 'pervadinti šį puslapį',
'action-move-subpages' => 'pervadinti šį puslapį ir jo subpuslapius',
'action-move-rootuserpages' => 'perkelti pagrindinius naudotojų puslapius',
'action-movefile' => 'perkelti šį failą',
'action-upload' => 'įkelti šį failą',
'action-reupload' => 'perrašyti šį esamą failą',
'action-reupload-shared' => 'perrašyti šį failą bendrojoje saugykloje',
'action-upload_by_url' => 'įkelti šį failą iš URL adreso',
'action-writeapi' => 'naudotis rašymo API',
'action-delete' => 'ištrinti šį puslapį',
'action-deleterevision' => 'ištrinti šią reviziją',
'action-deletedhistory' => 'žiūrėti šio ištrinto puslapio istoriją',
'action-browsearchive' => 'ieškoti ištrintų puslapių',
'action-undelete' => 'atkurti šį puslapį',
'action-suppressrevision' => 'peržiūrėti ir atkurti šią paslėptą versiją',
'action-suppressionlog' => 'peržiūrėti šį privatų registrą',
'action-block' => 'neleisti šiam naudotojui redaguoti',
'action-protect' => 'pakeisti apsaugos lygius šiam puslapiui',
'action-rollback' => 'greitai atmesti paskutinio naudotojo atliktų tam tikro puslapio pakeitimų',
'action-import' => 'importuoti puslapius iš kitos wiki',
'action-importupload' => 'importuoti puslapius iš įkelto failo',
'action-patrol' => 'pažymėti kitų keitimus kaip patikrintus',
'action-autopatrol' => 'savo keitimų pažymėjimas patikrintais',
'action-unwatchedpages' => 'žiūrėti nestebimų puslapių sąrašą',
'action-mergehistory' => 'sulieti šio puslapio istoriją',
'action-userrights' => 'keisti visų naudotojų teises',
'action-userrights-interwiki' => 'keisti naudotojų teises kitose wiki svetainėse',
'action-siteadmin' => 'užrakinti ar atrakinti duomenų bazę',
'action-sendemail' => 'siųsti e-mail laiškus',
'action-editmywatchlist' => 'redaguoti savo stebėjimų sąrašą',
'action-viewmywatchlist' => 'rodyti savo stebėjimų sąrašą',
'action-viewmyprivateinfo' => 'peržiūrėti jūsų privačią informaciją',
'action-editmyprivateinfo' => 'redaguoti savo privačią informaciją',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|pakeitimas|pakeitimai|pakeitimų}}',
'recentchanges' => 'Naujausi keitimai',
'recentchanges-legend' => 'Naujausių keitimų parinktys',
'recentchanges-summary' => 'Šiame puslapyje yra patys naujausi pakeitimai šiame projekte.',
'recentchanges-feed-description' => 'Sekite pačius naujausius projekto keitimus šiame šaltinyje.',
'recentchanges-label-newpage' => 'Šiuo keitimu sukurtas naujas puslapis',
'recentchanges-label-minor' => 'Tai smulkus pakeitimas',
'recentchanges-label-bot' => 'Šį keitimą atliko automatinė programa',
'recentchanges-label-unpatrolled' => 'Šis keitimas dar nebuvo patikrintas',
'rcnote' => "Žemiau yra {{PLURAL:$1|'''1''' pakeitimas|paskutiniai '''$1''' pakeitimai|paskutinių '''$1''' pakeitimų}} per {{PLURAL:$2|dieną|paskutiniąsias '''$2''' dienas|paskutiniųjų '''$2''' dienų}} skaičiuojant nuo $5, $4.",
'rcnotefrom' => "Žemiau yra pakeitimai pradedant '''$2''' (rodoma iki '''$1''' pakeitimų).",
'rclistfrom' => 'Rodyti naujus pakeitimus pradedant $1',
'rcshowhideminor' => '$1 smulkius keitimus',
'rcshowhidebots' => '$1 robotus',
'rcshowhideliu' => '$1 prisijungusius naudotojus',
'rcshowhideanons' => '$1 anoniminius naudotojus',
'rcshowhidepatr' => '$1 patikrintus keitimus',
'rcshowhidemine' => '$1 mano keitimus',
'rclinks' => 'Rodyti paskutinius $1 pakeitimų per paskutiniąsias $2 dienų<br />$3',
'diff' => 'skirt',
'hist' => 'ist',
'hide' => 'Slėpti',
'show' => 'Rodyti',
'minoreditletter' => 'S',
'newpageletter' => 'N',
'boteditletter' => 'R',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|stebintis naudotojas|stebintys naudotojai|stebinčių naudotojų}}]',
'rc_categories' => 'Rodyti tik šias kategorijas (atskirkite naudodami „|“)',
'rc_categories_any' => 'Bet kokia',
'rc-change-size-new' => '$1 {{PLURAL:$1|baitas|baitai|baitų}} po pakeitimo',
'newsectionsummary' => '/* $1 */ naujas skyrius',
'rc-enhanced-expand' => 'Rodyti detales',
'rc-enhanced-hide' => 'Slėpti detales',
'rc-old-title' => 'iš pradžių sukurtas kaip " $1 "',

# Recent changes linked
'recentchangeslinked' => 'Susiję keitimai',
'recentchangeslinked-feed' => 'Susiję keitimai',
'recentchangeslinked-toolbox' => 'Susiję keitimai',
'recentchangeslinked-title' => 'Su „$1“ susiję keitimai',
'recentchangeslinked-summary' => "Tai paskutinių keitimų, atliktų puslapiuose, į kuriuos yra nuoroda iš nurodyto puslapio (arba į nurodytos kategorijos narius), sąrašas.
Puslapiai iš jūsų [[Special:Watchlist|stebimųjų sąrašo]] yra '''paryškinti'''.",
'recentchangeslinked-page' => 'Puslapio pavadinimas:',
'recentchangeslinked-to' => 'Rodyti su duotuoju puslapiu susijusių puslapių pakeitimus',

# Upload
'upload' => 'Įkelti failą',
'uploadbtn' => 'Įkelti failą',
'reuploaddesc' => 'Atšaukti įkėlimą ir grįžti į įkėlimo formą.',
'upload-tryagain' => 'Siųsti pakeistą failo aprašymą',
'uploadnologin' => 'Neprisijungęs',
'uploadnologintext' => 'Jūs turite $1, norėdami įkelti failus',
'upload_directory_missing' => 'Nėra įkėlimo aplanko ($1) ir negali būti sukurtas tinklo serverio.',
'upload_directory_read_only' => 'Tinklapio serveris negali rašyti į įkėlimo aplanką ($1).',
'uploaderror' => 'Įkėlimo klaida',
'upload-recreate-warning' => "'''Dėmėsio: Failas šiuo pavadinimu buvo ištrintas arba pervadintas.'''

Jūsų patogumui pateiktas įrašas apie šio puslapio trynimą ar pervadinimą:",
'uploadtext' => "Kad įkeltumėte failą, naudokitės žemiau pateikta forma.
Norėdami peržiūrėti ar ieškoti anksčiau įkeltų paveikslėlių, eikite į [[Special:FileList|įkeltų failų sąrašą]], įkėlimai yra registruojami [[Special:Log/upload|įkėlimų sąraše]], trynimai — [[Special:Log/delete|trynimų sąraše]].

Norėdami panaudoti įkeltą failą puslapyje, naudokite tokias nuorodas:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Failas.jpg]]</nowiki></code>''' norėdami naudoti pilną failo versiją
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Failas.png|200px|thumb|left|alternatyvusis tekstas]]</nowiki></code>''' norėdami naudoti 200 pikselių pločio paveikslėlį rėmelyje puslapio kairėje; „alternatyvus tekstas“ bus naudojamas paveikslėlio aprašymui.
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Failas.ogg]]</nowiki></code>''' tiesioginei nuorodai į failą.",
'upload-permitted' => 'Leidžiami failų tipai: $1.',
'upload-preferred' => 'Pageidautini failų tipai: $1.',
'upload-prohibited' => 'Uždrausti failų tipai: $1.',
'uploadlog' => 'įkėlimų sąrašas',
'uploadlogpage' => 'Įkėlimų sąrašas',
'uploadlogpagetext' => 'Žemiau pateikiamas paskutinių failų įkėlimų sąrašas.
Taip pat galite peržvelgti [[Special:NewFiles|naujausių failų galeriją]].',
'filename' => 'Failo vardas',
'filedesc' => 'Aprašymas',
'fileuploadsummary' => 'Komentaras:',
'filereuploadsummary' => 'Failo pakeitimai:',
'filestatus' => 'Autorystės teisės:',
'filesource' => 'Šaltinis:',
'uploadedfiles' => 'Įkelti failai',
'ignorewarning' => 'Ignoruoti įspėjimą ir išsaugoti failą vistiek.',
'ignorewarnings' => 'Ignuoruoti bet kokius įspėjimus',
'minlength1' => 'Failo pavadinimas turi būti bent viena raidė.',
'illegalfilename' => 'Failo varde „$1“ yra simbolių, neleidžiamų puslapio pavadinimuose. Prašome pervadint failą ir mėginkite įkelti jį iš naujo.',
'filename-toolong' => 'Failo vardas negali būti ilgesnis nei 240 baitų.',
'badfilename' => 'Failo pavadinimas pakeistas į „$1“.',
'filetype-mime-mismatch' => 'Failo plėtinys „.$1“ neatitinka nustatyto šio failo MIME tipo($2).',
'filetype-badmime' => 'Neleidžiama įkelti „$1“ MIME tipo failų.',
'filetype-bad-ie-mime' => 'Negalima įkelti šio failo, kadangi Internet Explorer jį pažymėtų kaip „$1“. Tai yra neleistinas ir potencialiai pavojingas failo tipas.',
'filetype-unwanted-type' => "„.$1“''' yra nepageidautinas failo tipas. {{PLURAL:$3|Pageidautinas failų tipas|pageidautini failų tipai}} yra $2.",
'filetype-banned-type' => "'''„.$1“''' nėra {{PLURAL:$4|leistinas failo tipas|leistini failo tipai}}.
{{PLURAL:$3|Leistinas failų tipas|Leistini failų tipai}} yra $2.",
'filetype-missing' => 'Failas neturi galūnės (pavyzdžiui „.jpg“).',
'empty-file' => 'Failas, kurį patvirtinote, tuščias.',
'file-too-large' => 'Failas, kurį patvirtinote, pernelyg didelis.',
'filename-tooshort' => 'Failo pavadinimas per trumpas.',
'filetype-banned' => 'Šis failo tipas yra uždraustas.',
'verification-error' => 'Šis failas nepraėjo patikrinimo.',
'hookaborted' => 'Pakeitimą, kurį bandėte atlikti, nutraukė priedo gaudlys.',
'illegal-filename' => 'Failo vardas neleidžiamas.',
'overwrite' => 'Perrašyti esamą failą neleidžiama.',
'unknown-error' => 'Įvyko nežinoma klaida.',
'tmp-create-error' => 'Nepavyko sukurti laikino failo.',
'tmp-write-error' => 'Klaida rašant laikinąjį failą.',
'large-file' => 'Rekomenduojama, kad failų dydis būtų nedidesnis nei $1; šio failo dydis yra $2.',
'largefileserver' => 'Šis failas yra didesnis nei serveris yra sukonfigūruotas leisti.',
'emptyfile' => 'Panašu, kad failas, kurį įkėlėte yra tuščias. Tai gali būti dėl klaidos failo pavadinime. Pasitikrinkite ar tikrai norite įkelti šitą failą.',
'windows-nonascii-filename' => 'Ši viki neleidžia naudoti failų vardų su specialiais simboliais.',
'fileexists' => 'Failas tuo pačiu vardu jau egzistuoja, prašome pažiūrėti <strong>[[:$1]]</strong>, jei nesate tikras, ar norite perrašyti šį failą.
[[$1|thumb]]',
'filepageexists' => 'Šio failo aprašymo puslapis jau buvo sukurtas <strong>[[:$1]]</strong>, bet šiuo metu nėra jokio failo šiuo pavadinimu.
Jūsų įvestas komentaras neatsiras aprašymo puslapyje.
Jei norite, kad jūsų komentaras ten atsirastų, jums reikia jį pakeisti pačiam.
[[$1|thumb]]',
'fileexists-extension' => 'Failas su panašiu pavadinimu jau yra: [[$2|thumb]]
* Įkeliamo failo pavadinimas: <strong>[[:$1]]</strong>
* Jau esančio failo pavadinimas: <strong>[[:$2]]</strong>
Prašome pasirinkti kitą vardą.',
'fileexists-thumbnail-yes' => "Failas turbūt yra sumažinto dydžio failas ''(miniatiūra)''. [[$1|thumb]]
Prašome peržiūrėti failą <strong>[[:$1]]</strong>.
Jeigu tai yra toks pats pradinio dydžio paveikslėlis, tai įkelti papildomos miniatūros nereikia.",
'file-thumbnail-no' => "Failo pavadinimas prasideda  <strong>$1</strong>.
Atrodo, kad yra sumažinto dydžio paveikslėlis ''(miniatiūra)''.
Jei jūs turite šį paveisklėlį pilna raiška, įkelkite šitą, priešingu atveju prašome pakeisti failo pavadinimą.",
'fileexists-forbidden' => 'Failas tokiu pačiu vardu jau egzistuoja ir negali būti perrašytas;
prašome eiti atgal ir įkelti šį failą kitu vardu. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Failas tokiu vardu jau egzistuoja bendrojoje failų saugykloje;
Jei visvien norite įkelti savo failą, prašome eiti atgal ir įkelti šį failą kitu vardu. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Šis failas yra {{PLURAL:$1|šio failo|šių failų}} dublikatas:',
'file-deleted-duplicate' => 'Failas, identiškas šiam failui ([[:$1]]), seniau buvo ištrintas. Prieš įkeldami jį vėl patikrinkite šio failo ištrynimo istoriją.',
'uploadwarning' => 'Dėmesio',
'uploadwarning-text' => 'Prašome pakeisti failo aprašymą ir bandykite dar kartą.',
'savefile' => 'Išsaugoti failą',
'uploadedimage' => 'įkėlė „[[$1]]“',
'overwroteimage' => 'įkėlė naują „[[$1]]“ versiją',
'uploaddisabled' => 'Įkėlimai uždrausti',
'copyuploaddisabled' => 'Įkėlimas pagal URL išjungtas.',
'uploadfromurl-queued' => 'Jūsų įkėlimas įtrauktas į eilę.',
'uploaddisabledtext' => 'Failų įkėlimai yra uždrausti.',
'php-uploaddisabledtext' => "Failų įkėlimai uždrausti PHP nustatymuose.
Patikrinkite ''file_uploads'' nustatą.",
'uploadscripted' => 'Šis failas turi HTML arba programinį kodą, kuris gali būti klaidingai suprastas interneto naršyklės.',
'uploadvirus' => 'Šiame faile yra virusas! Smulkiau: $1',
'uploadjava' => 'Failas yra ZIP failas, kuriame yra Java .class failas.
Įkelti Java failus neleidžiama, nes jie gali padėti apeiti saugumo apribojimus.',
'upload-source' => 'Failo šaltinis',
'sourcefilename' => 'Įkeliamas failas:',
'sourceurl' => 'Šaltinio adresas:',
'destfilename' => 'Norimas failo vardas:',
'upload-maxfilesize' => 'Didžiausias failo dydis: $1',
'upload-description' => 'Failo aprašymas',
'upload-options' => 'Įkėlimo nustatymai',
'watchthisupload' => 'Stebėti šį failą',
'filewasdeleted' => 'Failas šiuo vardu anksčiau buvo įkeltas, o paskui ištrintas. Jums reikėtų patikrinti $1 prieš bandant įkelti jį vėl.',
'filename-bad-prefix' => "Jūsų įkeliamas failas prasideda su '''„$1“''', bet tai yra neapibūdinantis pavadinimas, dažniausiai priskirtas skaitmeninių kamerų. Prašome suteikti labiau apibūdinantį pavadinimą savo failui.",
'upload-success-subj' => 'Įkelta sėkmingai',
'upload-success-msg' => 'Jūsų įkėlimas iš [$2] buvo sėkmingas. Jį galima rasti čia: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Įkėlimo problema',
'upload-failure-msg' => 'Įvyko įkėlimo iš [$2] problema:

$1',
'upload-warning-subj' => 'Įkėlimo įspėjimas',
'upload-warning-msg' => 'Įvyko įkėlimo iš [$2] problema. Jums reikėtų grįžti į [[Special:Upload/stash/$1|įkėlimo formą]], norint išspręsti šią problemą.',

'upload-proto-error' => 'Neteisingas protokolas',
'upload-proto-error-text' => 'Nuotoliniai įkėlimas reikalauja, kad URL prasidėtų <code>http://</code> arba <code>ftp://</code>.',
'upload-file-error' => 'Vidinė klaida',
'upload-file-error-text' => 'Įvyko vidinė klaida bandant sukurti laikinąjį failą serveryje.
Prašome susisiekti su [[Special:ListUsers/sysop|administratoriumi]].',
'upload-misc-error' => 'Nežinoma įkėlimo klaida',
'upload-misc-error-text' => 'Įvyko nežinoma klaida vykstant įkėlimui. Prašome patikrinti, kad URL teisingas bei pasiekiamas ir pamėginkite vėl. Jei problema lieka, susisiekite su [[Special:ListUsers/sysop|administratoriumi]].',
'upload-too-many-redirects' => 'URL yra per daug kartų peradresuotas',
'upload-unknown-size' => 'Nežinomas dydis',
'upload-http-error' => 'Įvyko HTTP klaida: $1',
'upload-copy-upload-invalid-domain' => 'Pakrovimų kopijos yra neleidžiamos iš šio domeno.',

# File backend
'backend-fail-stream' => 'Negali būti apdorotas failas $1.',
'backend-fail-backup' => 'Negali būti išsaugotas failas $1.',
'backend-fail-notexists' => 'Failas $1 neegzistuoja.',
'backend-fail-hashes' => 'Negalima gauti failo maišos palyginimui.',
'backend-fail-notsame' => 'Jau egzistuoja neidentiškas failas $1.',
'backend-fail-invalidpath' => '$1 yra neteisinga saugojimo nuoroda.',
'backend-fail-delete' => 'Negalima panaikinti failo $1.',
'backend-fail-describe' => 'Nepavyko pakeisti failo metaduomenis "$1".',
'backend-fail-alreadyexists' => 'Failas $1 jau egzistuoja.',
'backend-fail-store' => 'Negalima išsaugoti failo $1 kaip $2.',
'backend-fail-copy' => 'Negalima nukopijuoti failo $1 į $2.',
'backend-fail-move' => 'Negalima pervadinti failo $1 į $2.',
'backend-fail-opentemp' => 'Negalima atidaryti laikino failo.',
'backend-fail-writetemp' => 'Negalima rašyti į laikiną failą.',
'backend-fail-closetemp' => 'Negalima uždaryti laikino failo.',
'backend-fail-read' => 'Negalima nuskaityti failo $1.',
'backend-fail-create' => 'Negalima sukurti failo $1.',
'backend-fail-maxsize' => 'Failo $1 sukurti nepavyko nes jis didesnis nei {{PLURAL:$2|vienas baitas|$2 baitai|$2 baitų}}.',
'backend-fail-readonly' => 'Galutinė saugykla "$1" dabar yra skirta tik skaitymui. Buvo nurodyta priežastis: "$2"',
'backend-fail-synced' => 'Failas "$1", esantis vidinėje galutinėje saugykloje, yra pažymėtas kaip nepilnas.',
'backend-fail-connect' => 'Negalima prisijungti prie galutinės saugyklos "$1".',
'backend-fail-internal' => 'Nežinoma klaida įvyko galutinėje saugykloje "$1".',
'backend-fail-contenttype' => 'Negalima nustatyti failo turinio tipo, kuris saugomas "$1".',

# Lock manager
'lockmanager-notlocked' => 'Negalima atrakinti "$1", nes jis nėra užrakintas.',
'lockmanager-fail-closelock' => 'Negalima uždaryti rakinimų failo dėl "$1".',
'lockmanager-fail-deletelock' => 'Negalima panaikinti rakinimų failo dėl "$1".',
'lockmanager-fail-acquirelock' => 'Negalima nustatyti rakinimo dėl "$1".',
'lockmanager-fail-openlock' => 'Negalima atidaryti rakinimų failo dėl "$1".',
'lockmanager-fail-releaselock' => 'Negalima panaikinti rakinimo dėl "$1".',

# ZipDirectoryReader
'zip-file-open-error' => 'Įvyko klaida atidarant ZIP patikrinimus failą.',
'zip-wrong-format' => 'Nurodytas failas nėra ZIP failas.',
'zip-bad' => 'Šis failas yra sugadintas ar kitaip neįskaitomas ZIP failą.! N! Ji negali būti tinkamai patikrinti dėl jų saugumo.',
'zip-unsupported' => 'Šis failas yra ZIP failas, kurį naudoja ZIP funkcijos nepalaiko MediaWiki.! N! Ji negali būti tinkamai patikrinti dėl jų saugumo.',

# Special:UploadStash
'uploadstash' => 'Įkelti Atlicināt',
'uploadstash-summary' => 'Šis puslapis suteikia prieigą prie failų, kurie yra įkeltas į serverį (arba įkelti procesas), tačiau dar nepaskelbta prie wiki. Šie failai nėra matomas visiems kitiems, bet vartotojas, kuris nusiuntė juos.',
'uploadstash-clear' => 'Išvalyti stashed failai',
'uploadstash-nofiles' => 'Jūs neturite stashed failus.',
'uploadstash-badtoken' => 'Scenos šį ieškinį, buvo nesėkmingas, galbūt todėl, kad jūsų redagavimo įgaliojimai pasibaigė. Bandykite dar kartą.',
'uploadstash-errclear' => 'Kliringo failai buvo nesėkmingas.',
'uploadstash-refresh' => 'Atnaujinti failų sąrašą',
'invalid-chunk-offset' => 'Neleistinas segmento poslinkis',

# img_auth script messages
'img-auth-accessdenied' => 'Prieiga uždrausta',
'img-auth-nopathinfo' => 'Trūksta PATH_INFO.
Jūsų serveris nenustatytas perduoti šią informaciją.
Tai gali būti CGI paremta ir negali palaikyti img_auth.
Daugiau informacijos https://www.mediawiki.org/wiki/Manual:Image_Authorization. žr.',
'img-auth-notindir' => 'Užklaustas kelias nėra sukonfigūruotame įkėlimo kataloge.',
'img-auth-badtitle' => 'Nepavyksta padaryti leistino pavadinimo iš „$1“.',
'img-auth-nologinnWL' => 'Jūs nesate prisijungęs ir „$1“ nėra baltajame sąraše.',
'img-auth-nofile' => 'Failas „$1“ neegzistuoja.',
'img-auth-isdir' => 'Jūs bandote pasiekti katalogą „$1“.
Leidžiama prieiga tik prie failų.',
'img-auth-streaming' => 'Siunčiamas „$1“.',
'img-auth-public' => 'img_auth.php paskirtis yra pateikti failus iš privačių projektų.
Šis projektas sukonfigūruotas kaip viešasis.
Dėl saugumo, img_auth.php yra išjungtas.',
'img-auth-noread' => 'Naudotojas neturi teisės peržiūrėti „$1“.',
'img-auth-bad-query-string' => 'URL neteisingas užklausos eilutę.',

# HTTP errors
'http-invalid-url' => 'Neleistinas URL: $1',
'http-invalid-scheme' => 'URL su priedėliu „$1“ nepalaikomi.',
'http-request-error' => 'HTTP užklausa nepavyko dėl nežinomos klaidos.',
'http-read-error' => 'HTTP skaitymo klaida.',
'http-timed-out' => 'HTTP užklausos laikas baigėsi.',
'http-curl-error' => 'Klaida siunčiantis URL: $1',
'http-bad-status' => 'Iškilo problemų vykdant HTTP užklausą: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Nepavyksta pasiekti URL',
'upload-curl-error6-text' => 'Pateiktas URL negali būti pasiektas. Prašome patikrinti, kad URL yra teisingas ir svetainė veikia.',
'upload-curl-error28' => 'Per ilgai įkeliama',
'upload-curl-error28-text' => 'Atsakant svetainė užtrunka per ilgai. Patikrinkite, ar svetainė veikia, palaukite truputį ir vėl pamėginkite. Galbūt jums reikėtų pamėginti ne tokiu apkrautu metu.',

'license' => 'Licencija:',
'license-header' => 'Licensija',
'nolicense' => 'Nepasirinkta',
'license-nopreview' => '(Peržiūra negalima)',
'upload_source_url' => ' (tikras, viešai prieinamas URL)',
'upload_source_file' => ' (failas jūsų kompiuteryje)',

# Special:ListFiles
'listfiles-summary' => 'Šiame specialiame puslapyje rodomi visi įkelti failai.
Kai sąrašas susiaurinamas pagal naudotoją, rodomi tik tie failai, kurių naujausią versiją jis yra įkėlęs.',
'listfiles_search_for' => 'Ieškoti failo pavadinimo:',
'imgfile' => 'failas',
'listfiles' => 'Failų sąrašas',
'listfiles_thumb' => 'Miniatiūra',
'listfiles_date' => 'Data',
'listfiles_name' => 'Pavadinimas',
'listfiles_user' => 'Naudotojas',
'listfiles_size' => 'Dydis',
'listfiles_description' => 'Aprašymas',
'listfiles_count' => 'Versijos',
'listfiles-latestversion' => 'Dabartinė versija',
'listfiles-latestversion-yes' => 'Taip',
'listfiles-latestversion-no' => 'Ne',

# File description page
'file-anchor-link' => 'Failas',
'filehist' => 'Paveikslėlio istorija',
'filehist-help' => 'Paspauskite ant datos/laiko, kad pamatytumėte failą tokį, koks jis buvo tuo metu.',
'filehist-deleteall' => 'trinti visus',
'filehist-deleteone' => 'trinti',
'filehist-revert' => 'grąžinti',
'filehist-current' => 'dabartinis',
'filehist-datetime' => 'Data/Laikas',
'filehist-thumb' => 'Miniatiūra',
'filehist-thumbtext' => 'Versijos $1 miniatiūra',
'filehist-nothumb' => 'Nėra miniatiūros',
'filehist-user' => 'Naudotojas',
'filehist-dimensions' => 'Matmenys',
'filehist-filesize' => 'Failo dydis',
'filehist-comment' => 'Komentaras',
'filehist-missing' => 'Failo nėra',
'imagelinks' => 'Failų panaudojimas',
'linkstoimage' => '{{PLURAL:$1|Šis puslapis|Šie puslapiai}} nurodo į šį failą:',
'linkstoimage-more' => 'Daugiau nei $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}} rodo į šį failą.
Šis sąrašas rodo tik {{PLURAL:$1|puslapio|pirmų $1 puslapių}} nuorodas į šį failą.
Yra pasiekiamas ir [[Special:WhatLinksHere/$2|visas sąrašas]].',
'nolinkstoimage' => 'Į failą nenurodo joks puslapis.',
'morelinkstoimage' => 'Žiūrėti [[Special:WhatLinksHere/$1|daugiau nuorodų]] į šį failą.',
'linkstoimage-redirect' => '$1 (failo peradresavimas) $2',
'duplicatesoffile' => 'Šis failas turi {{PLURAL:$1|$1 dublikatą|$1 dublikatus|$1 dublikatų}} ([[Special:FileDuplicateSearch/$2|daugiau informacijos]]):',
'sharedupload' => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.',
'sharedupload-desc-there' => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.
Norėdami sužinoti daugiau, žiūrėkite [$2 failo aprašymą].',
'sharedupload-desc-here' => 'Šis failas yra iš $1 ir gali būti naudojamas kituose projektuose.
Informacija iš [$2 failo aprašymo puslapio] yra pateikiama žemiau.',
'filepage-nofile' => 'Joks failas su duotu pavadinimu neegzistuoja.',
'filepage-nofile-link' => 'Joks failas su duotu pavadinimu neegzistuoja, bet vis dar galite [$1 jį įkelti].',
'uploadnewversion-linktext' => 'Įkelti naują failo versiją',
'shared-repo-from' => 'iš $1',
'shared-repo' => 'bendrosios failų saugyklos',
'shared-repo-name-wikimediacommons' => 'Vikiteka',
'upload-disallowed-here' => 'Jūs negalite perrašyti šio failo.',

# File reversion
'filerevert' => 'Sugrąžinti $1',
'filerevert-legend' => 'Failo sugrąžinimas',
'filerevert-intro' => '<span class="plainlinks">Jūs grąžinate \'\'\'[[Media:$1|$1]]\'\'\' į versiją $4 ($2, $3).</span>',
'filerevert-comment' => 'Priežastis:',
'filerevert-defaultcomment' => 'Grąžinta į $1, $2 versiją',
'filerevert-submit' => 'Grąžinti',
'filerevert-success' => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' buvo sugrąžintas į versiją $4 ($2, $3).</span>',
'filerevert-badversion' => 'Nėra jokių ankstesnių vietinių šio failo versijų su pateiktu laiku.',

# File deletion
'filedelete' => 'Trinti $1',
'filedelete-legend' => 'Trinti failą',
'filedelete-intro' => "Jūs ketinate ištrinti failą '''[[Media:$1|$1]]''' su visa istorija.",
'filedelete-intro-old' => '<span class="plainlinks">Jūs trinate \'\'\'[[Media:$1|$1]]\'\'\' [$4 $3, $2] versiją.</span>',
'filedelete-comment' => 'Priežastis:',
'filedelete-submit' => 'Trinti',
'filedelete-success' => "'''$1''' buvo ištrintas.",
'filedelete-success-old' => "'''[[Media:$1|$1]]''' $3, $2 versija buvo ištrinta.",
'filedelete-nofile' => "'''$1''' neegzistuoja.",
'filedelete-nofile-old' => "Nėra jokios '''$1''' suarchyvuotos versijos su nurodytais atributais.",
'filedelete-otherreason' => 'Kita/papildoma priežastis:',
'filedelete-reason-otherlist' => 'Kita priežastis',
'filedelete-reason-dropdown' => '*Dažnos trynimo priežastys
** Autorystės teisių pažeidimai
** Pasikartojantis failas',
'filedelete-edit-reasonlist' => 'Keisti trynimo priežastis',
'filedelete-maintenance' => 'Failų trynimas ir atkūrimas laikinai išjungtas dėl profilaktikos.',
'filedelete-maintenance-title' => 'Negalima panaikinti failo',

# MIME search
'mimesearch' => 'MIME paieška',
'mimesearch-summary' => 'Šis puslapis leidžia rodyti failus pagal jų MIME tipą. Įveskite: turiniotipas/potipis, pvz. <code>image/jpeg</code>.',
'mimetype' => 'MIME tipas:',
'download' => 'parsisiųsti',

# Unwatched pages
'unwatchedpages' => 'Nestebimi puslapiai',

# List redirects
'listredirects' => 'Peradresavimų sąrašas',

# Unused templates
'unusedtemplates' => 'Nenaudojami šablonai',
'unusedtemplatestext' => 'Šis puslapis rodo sąrašą puslapių, esančių {{ns:template}} vardų srityje, kurie nėra įterpti į jokį kitą puslapį. Nepamirškite patikrinti kitų nuorodų prieš juos ištrinant.',
'unusedtemplateswlh' => 'kitos nuorodos',

# Random page
'randompage' => 'Atsitiktinis puslapis',
'randompage-nopages' => '{{PLURAL:$2|Šioje vardų srityje|Šiose vardų srityse}} nėra jokių puslapių: $1.',

# Random redirect
'randomredirect' => 'Atsitiktinis peradresavimas',
'randomredirect-nopages' => 'Vardų srityje „$1“ nėra jokių peradresavimų.',

# Statistics
'statistics' => 'Statistika',
'statistics-header-pages' => 'Puslapių statistika',
'statistics-header-edits' => 'Redagavimų statistika',
'statistics-header-views' => 'Peržiūrų statistika',
'statistics-header-users' => 'Naudotojų statistika',
'statistics-header-hooks' => 'Kita statistika',
'statistics-articles' => 'Turinio puslapiai',
'statistics-pages' => 'Puslapiai',
'statistics-pages-desc' => 'Visi puslapiai, tarp jų aptarimo, nukreipimų, ir kiti puslapiai.',
'statistics-files' => 'Įkelti failai',
'statistics-edits' => 'Puslapių redagavimų skaičius nuo {{SITENAME}} sukūrimo',
'statistics-edits-average' => 'Vidutinis puslapio keitimų skaičius',
'statistics-views-total' => 'Iš viso peržiūrų',
'statistics-views-total-desc' => 'Neegzistuojančių ir specialiųjų puslapių parodymai neįtraukti',
'statistics-views-peredit' => 'Peržiūrų skaičius puslapio versijai',
'statistics-users' => 'Registruotų [[Special:ListUsers|naudotojų]]',
'statistics-users-active' => 'Aktyvių naudotojų',
'statistics-users-active-desc' => 'Naudotojai, kurie per {{PLURAL:$1|paskutinę dieną|paskutines $1 dienų}} padarė keitimų',
'statistics-mostpopular' => 'Daugiausiai rodyti puslapiai',

'pageswithprop' => 'Puslapiai su puslapio atributais',
'pageswithprop-legend' => 'Puslapiai su puslapio atributais',
'pageswithprop-text' => 'Šiame puslapyje pateikiami puslapiai, kurie ypač naudoja puslapio atributus.',
'pageswithprop-prop' => 'Ypatybės pavadinimas:',
'pageswithprop-submit' => 'Eiti',

'doubleredirects' => 'Dvigubi peradresavimai',
'doubleredirectstext' => 'Šiame puslapyje yra puslapių, kurie nukreipia į kitus peradresavimo puslapius, sąrašas.
Kiekvienoje eilutėje yra nuorodos į pirmąjį ir antrąjį peradresavimą, taip pat antrojo peradresavimo paskirtis, kuris paprastai yra „tikrasis“ paskirties puslapis, į kurį pirmasis peradresavimas ir turėtų rodyti.
<del>Išbraukti</del> įrašai yra išspręsti.',
'double-redirect-fixed-move' => '[[$1]] buvo perkeltas, dabar tai peradresavimas į [[$2]]',
'double-redirect-fixed-maintenance' => 'Tvarkomas dvigubas peradresavimas iš [[$1]] į [[$2]].',
'double-redirect-fixer' => 'Peradresavimų tvarkyklė',

'brokenredirects' => 'Peradresavimai į niekur',
'brokenredirectstext' => 'Šie peradresavimo puslapiai nurodo į neegzistuojančius puslapius:',
'brokenredirects-edit' => 'redaguoti',
'brokenredirects-delete' => 'trinti',

'withoutinterwiki' => 'Puslapiai be kalbų nuorodų',
'withoutinterwiki-summary' => 'Šie puslapiai nenurodo į kitų kalbų versijas:',
'withoutinterwiki-legend' => 'Priešdėlis',
'withoutinterwiki-submit' => 'Rodyti',

'fewestrevisions' => 'Puslapiai su mažiausiai keitimų',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|baitas|baitai|baitų}}',
'ncategories' => '$1 {{PLURAL:$1|kategorija|kategorijos|kategorijų}}',
'ninterwikis' => '$1 {{PLURAL:$1|interviki nuoroda|interviki nuorodos}}',
'nlinks' => '$1 {{PLURAL:$1|nuoroda|nuorodos|nuorodų}}',
'nmembers' => '$1 {{PLURAL:$1|narys|nariai|narių}}',
'nrevisions' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'nviews' => '$1 {{PLURAL:$1|parodymas|parodymai|parodymų}}',
'nimagelinks' => 'Naudojama $1 {{PLURAL:$1|puslapyje|puslapiuose|puslapių}}',
'ntransclusions' => 'naudojama $1 {{PLURAL:$1|puslapyje|puslapiuose|puslapių}}',
'specialpage-empty' => 'Šiai ataskaitai nėra rezultatų.',
'lonelypages' => 'Vieniši puslapiai',
'lonelypagestext' => 'Į šiuos puslapius nėra nuorodų ar įtraukimų iš kitų {{SITENAME}} puslapių.',
'uncategorizedpages' => 'Puslapiai, nepriskirti jokiai kategorijai',
'uncategorizedcategories' => 'Kategorijos, nepriskirtos jokiai kategorijai',
'uncategorizedimages' => 'Failai, nepriskirti jokiai kategorijai',
'uncategorizedtemplates' => 'Šablonai, nepriskirti jokiai kategorijai',
'unusedcategories' => 'Nenaudojamos kategorijos',
'unusedimages' => 'Nenaudojami failai',
'popularpages' => 'Populiarūs puslapiai',
'wantedcategories' => 'Geidžiamiausios kategorijos',
'wantedpages' => 'Geidžiamiausi puslapiai',
'wantedpages-badtitle' => 'Neleistinas pavadinimas rezultatų rinkinyje: $1',
'wantedfiles' => 'Trokštami failai',
'wantedfiletext-cat' => 'Sekantys failai yra naudojami, bet neegzistuoja. Čia failai iš išorinių saugyklų gali būti išvardinti, nors jie jose ir egzistuoja. Failai netenkinantys šių sąlygų gali būti <del>perbraukti</del>. Papildomai peržiūrėkite [[:$1|puslapius]], kuriuose yra naudojami čia išvardinti neegzistuojantys failai.',
'wantedfiletext-nocat' => 'Sekantys failai yra naudojami, bet neegzistuoja. Čia failai iš išorinių saugyklų gali būti išvardinti, nors jie jose ir egzistuoja. Failai netenkinantys šių sąlygų gali būti <del>perbraukti</del>.',
'wantedtemplates' => 'Trokštami šablonai',
'mostlinked' => 'Daugiausiai nurodomi puslapiai',
'mostlinkedcategories' => 'Daugiausiai nurodomos kategorijos',
'mostlinkedtemplates' => 'Daugiausiai nurodomi šablonai',
'mostcategories' => 'Puslapiai su daugiausiai kategorijų',
'mostimages' => 'Daugiausiai nurodomi failai',
'mostinterwikis' => 'Puslapiai, turintys daugiausiai tarpkalbinių nuorodų',
'mostrevisions' => 'Puslapiai su daugiausiai keitimų',
'prefixindex' => 'Visi puslapiai pagal pavadinimo pradžią',
'prefixindex-namespace' => 'Visi puslapiai prasidedantys ($1 vardų sritis)',
'shortpages' => 'Trumpiausi puslapiai',
'longpages' => 'Ilgiausi puslapiai',
'deadendpages' => 'Puslapiai-aklavietės',
'deadendpagestext' => 'Šie puslapiai neturi nuorodų į kitus puslapius šiame projekte.',
'protectedpages' => 'Užrakinti puslapiai',
'protectedpages-indef' => 'Tik neapibrėžtos apsaugos',
'protectedpages-cascade' => 'Tik pakopinė apsauga',
'protectedpagestext' => 'Šie puslapiai yra apsaugoti nuo perkėlimo ar redagavimo',
'protectedpagesempty' => 'Šiuo metu nėra apsaugotas joks failas su šiais parametrais.',
'protectedtitles' => 'Apsaugoti pavadinimai',
'protectedtitlestext' => 'Šie pavadinimai yra apsaugoti nuo sukūrimo',
'protectedtitlesempty' => 'Šiuo metu nėra jokių pavadinimų apsaugotų šiais parametrais.',
'listusers' => 'Naudotojų sąrašas',
'listusers-editsonly' => 'Rodyti tik keitimus atlikusius naudotojus',
'listusers-creationsort' => 'Rodyti pagal paskyros sukūrimo datą',
'usereditcount' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'usercreated' => '{{GENDER:$3|Naudotojo|Naudotojos|Naudotojo}} $3 paskyra sukurta $1 $2',
'newpages' => 'Naujausi puslapiai',
'newpages-username' => 'Naudotojo vardas:',
'ancientpages' => 'Seniausi puslapiai',
'move' => 'Pervadinti',
'movethispage' => 'Pervadinti šį puslapį',
'unusedimagestext' => 'Šie failai yra, bet jie neįtraukti į jokį kitą puslapį.
Primename, kad kitos svetainės gali turėti tiesioginę nuorodą į failą, bet vis tiek gali būti šiame sąraše, nors ir yra aktyviai naudojamas.',
'unusedcategoriestext' => 'Šie kategorijų puslapiai sukurti, nors joks kitas puslapis ar kategorija jo nenaudoja.',
'notargettitle' => 'Nenurodytas objektas',
'notargettext' => 'Jūs nenurodėte norimo puslapio ar naudotojo, kuriam įvykdyti šią funkciją.',
'nopagetitle' => 'Nėra puslapio tokiu adresu',
'nopagetext' => 'Adresas, kurį nurodėte, neegzistuoja.',
'pager-newer-n' => '$1 {{PLURAL:$1|naujesnis|naujesni|naujesnių}}',
'pager-older-n' => '$1 {{PLURAL:$1|senesnis|senesni|senesnių}}',
'suppress' => 'Peržiūra',
'querypage-disabled' => 'Šiame specialiajame puslapyje yra išjungta dėl neefektyvumo.',

# Book sources
'booksources' => 'Knygų šaltiniai',
'booksources-search-legend' => 'Knygų šaltinių paieška',
'booksources-go' => 'Rodyti',
'booksources-text' => 'Žemiau yra nuorodų sąrašas į kitas svetaines, kurios parduoda naujas ar naudotas knygas, bei galbūt turinčias daugiau informacijos apie knygas, kurių ieškote:',
'booksources-invalid-isbn' => 'Duotas ISBN atrodo neteisingas; patikrinkite, ar nepadarėte kopijavimo klaidų.',

# Special:Log
'specialloguserlabel' => 'Naudotojas:',
'speciallogtitlelabel' => 'Pavadinimas:',
'log' => 'Specialiųjų veiksmų sąrašas',
'all-logs-page' => 'Visi viešieji sąrašai',
'alllogstext' => 'Bendrai pateikiamas visų galimų „{{SITENAME}}“ specialiųjų veiksmų sąrašas.
Galima sumažinti rezultatų skaičių, patikslinant veiksmo rūšį, naudotoją ar susijusį puslapį.',
'logempty' => 'Sąraše nėra jokių atitinkančių įvykių.',
'log-title-wildcard' => 'Ieškoti pavadinimų, prasidedančių šiuo tekstu',
'showhideselectedlogentries' => 'Rodyti/slėpti pasirinktus sąrašo elementus',

# Special:AllPages
'allpages' => 'Visi puslapiai',
'alphaindexline' => 'Nuo $1 iki $2',
'nextpage' => 'Kitas puslapis ($1)',
'prevpage' => 'Ankstesnis puslapis ($1)',
'allpagesfrom' => 'Rodyti puslapius pradedant nuo:',
'allpagesto' => 'Rodyti puslapius, besibaigiančius su:',
'allarticles' => 'Visi puslapiai',
'allinnamespace' => 'Visi puslapiai ($1 vardų sritis)',
'allnotinnamespace' => 'Visi puslapiai (nesantys $1 vardų srityje)',
'allpagesprev' => 'Atgal',
'allpagesnext' => 'Pirmyn',
'allpagessubmit' => 'Rodyti',
'allpagesprefix' => 'Rodyti puslapiu su priedėliu:',
'allpagesbadtitle' => 'Duotas puslapio pavadinimas yra neteisingas arba turi tarpkalbininį arba tarpprojektinį priedėlį. Jame yra vienas ar keli simboliai, kurių negalima naudoti pavadinimuose.',
'allpages-bad-ns' => '{{SITENAME}} neturi „$1“ vardų srities.',
'allpages-hide-redirects' => 'Slėpti peradresavimus',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Jūs matote kompiuterio atmintyje išsaugotą puslapio versiją, kuri gali būti $1 senumo.',
'cachedspecial-viewing-cached-ts' => 'Jūs matote kompiuterio atmintyje išsaugotą puslapio versiją, kuri gali neatitikti naujausios versijos.',
'cachedspecial-refresh-now' => 'Peržiūrėti naujausius.',

# Special:Categories
'categories' => 'Kategorijos',
'categoriespagetext' => '{{PLURAL:$1|Ši kategorija|Šios kategorijos}} turi puslapių ar failų.
[[Special:UnusedCategories|Nenaudojamos kategorijos]] čia nerodomos.
Taip pat žiūrėkite [[Special:WantedCategories|trokštamas kategorijas]].',
'categoriesfrom' => 'Vaizduoti kategorijas pradedant nuo:',
'special-categories-sort-count' => 'rikiuoti pagal skaičių',
'special-categories-sort-abc' => 'rikiuoti pagal abėcėlę',

# Special:DeletedContributions
'deletedcontributions' => 'Ištrintas naudotojo indėlis',
'deletedcontributions-title' => 'Ištrintas naudotojo indėlis',
'sp-deletedcontributions-contribs' => 'indėlis',

# Special:LinkSearch
'linksearch' => 'Ieškoti išorinių nuorodų',
'linksearch-pat' => 'Ieškoti modulio:',
'linksearch-ns' => 'Vardų sritis:',
'linksearch-ok' => 'Ieškoti',
'linksearch-text' => 'Galima naudoti žvaigždutes, pvz., „*.wikipedia.org“.<br />
Yra būtinas bent jau aukščiausio lygio domenas, pvz., „*.org“.<br />
{{PLURAL:$2|Palaikomas protokolas|Palaikomi protokolai|Palaikomų protokolų}}: <code>$1</code> (numato į http://, jei nenurodytas joks protokolas).',
'linksearch-line' => '$1 yra susietas iš $2',
'linksearch-error' => 'Žvaigždutės gali būti tik adreso pradžioje.',

# Special:ListUsers
'listusersfrom' => 'Rodyti naudotojus pradedant nuo:',
'listusers-submit' => 'Rodyti',
'listusers-noresult' => 'Nerasta jokių naudotojų.',
'listusers-blocked' => '(užblokuotas)',

# Special:ActiveUsers
'activeusers' => 'Aktyvių naudotojų sąrašas',
'activeusers-intro' => 'Tai naudotojų sąrašas, kurie ką nors padarė per $1 {{PLURAL:$1|paskutinę dieną|paskutines dienas|paskutinių dienų}}.',
'activeusers-count' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}} per {{PLURAL:$3|paskutinę dieną|$3 paskutines dienas|$3 paskutinių dienų}}',
'activeusers-from' => 'Rodyti naudotojus, pradedant:',
'activeusers-hidebots' => 'Slėpti robotus',
'activeusers-hidesysops' => 'Slėpti administratorius',
'activeusers-noresult' => 'Nerasta jokių naudotojų.',

# Special:ListGroupRights
'listgrouprights' => 'Naudotojų grupių teisės',
'listgrouprights-summary' => 'Žemiau pateiktas naudotojų grupių, apibrėžtų šioje wiki, ir su jomis susijusių teisių sąrašas.
Čia gali būti [[{{MediaWiki:Listgrouprights-helppage}}|papildoma informacija]] apie individualias teises.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Suteikta teisė</span>
* <span class="listgrouprights-revoked">Atimta teisė</span>',
'listgrouprights-group' => 'Grupė',
'listgrouprights-rights' => 'Teisės',
'listgrouprights-helppage' => 'Help:Grupės teisės',
'listgrouprights-members' => '(narių sąrašas)',
'listgrouprights-addgroup' => 'Gali pridėti {{PLURAL:$2|grupę|grupes}}: $1',
'listgrouprights-removegroup' => 'Gali pašalinti {{PLURAL:$2|grupę|grupes}}: $1',
'listgrouprights-addgroup-all' => 'Gali pridėti visas grupes',
'listgrouprights-removegroup-all' => 'Gali pašalinti visas grupes',
'listgrouprights-addgroup-self' => 'Priskirti {{PLURAL:$2|grupę|grupes}} savo paskyrai: $1',
'listgrouprights-removegroup-self' => 'Pašalinti {{PLURAL:$2|grupę|grupes}} iš savo paskyros: $1',
'listgrouprights-addgroup-self-all' => 'Priskirti visas grupes prie paskyros',
'listgrouprights-removegroup-self-all' => 'Pašalinti visas grupes iš savo paskyros',

# Email user
'mailnologin' => 'Nėra adreso',
'mailnologintext' => 'Jums reikia būti [[Special:UserLogin|prisijungusiam]] ir turi būti įvestas teisingas el. pašto adresas jūsų [[Special:Preferences|nustatymuose]], kad siųstumėte el. laiškus kitiems nautotojams.',
'emailuser' => 'Rašyti laišką šiam naudotojui',
'emailuser-title-target' => 'Siųsti E-pašto žinutę {{GENDER:$1|user}}',
'emailuser-title-notarget' => 'El. pašto vartotojas',
'emailpage' => 'Siųsti el. laišką naudotojui',
'emailpagetext' => 'Jūs gali pasinaudoti šia forma norėdami nusiųsti el. laišką šiam naudotojui.
El. pašto adresas, kurį įvedėte [[Special:Preferences|savo naudotojo nustatymuose]], bus rodomas kaip el. pašto siuntėjo adresas, tam, kad gavėjas galėtų jums iškart atsakyti.',
'usermailererror' => 'Pašto objektas grąžino klaidą:',
'defemailsubject' => '{{SITENAME}} el. pašto iš vartotojo " $1 "',
'usermaildisabled' => 'Naudotojo elektroninis paštas išjungtas',
'usermaildisabledtext' => 'Jūs negalite siūlsti el. laiško kitiems šio wiki projekto naudotojams.',
'noemailtitle' => 'Nėra el. pašto adreso',
'noemailtext' => 'Šis naudotojas nėra nurodęs teisingo el. pašto adreso, arba yra pasirinkęs negauti el. pašto iš kitų naudotojų.',
'nowikiemailtitle' => 'El. laiškai neleidžiami',
'nowikiemailtext' => 'Šis naudotojas yra pasirinkęs negauti elektroninių laiškų iš kitų naudotojų.',
'emailnotarget' => 'Nesamas arba neteisingas vartotojo vardas gavėjui.',
'emailtarget' => 'Įveskite vartotojo vardą gavėjo',
'emailusername' => 'Naudotojo vardas:',
'emailusernamesubmit' => 'Pateikti',
'email-legend' => 'Siųsti elektroninį laišką kitam {{SITENAME}} naudotojui',
'emailfrom' => 'Nuo:',
'emailto' => 'Kam:',
'emailsubject' => 'Tema:',
'emailmessage' => 'Tekstas:',
'emailsend' => 'Siųsti',
'emailccme' => 'Siųsti man mano laiško kopiją.',
'emailccsubject' => 'Laiško kopija naudotojui $1: $2',
'emailsent' => 'El. laiškas išsiųstas',
'emailsenttext' => 'Jūsų el. pašto žinutė išsiųsta.',
'emailuserfooter' => 'Šis elektroninis laiškas buvo išsiųstas naudotojo $1 naudotojui $2 naudojant „Rašyti elektroninį laišką“ funkciją projekte {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Paliekamas sistemos pranešimas.',
'usermessage-editor' => 'Sistemos pranešėjas',

# Watchlist
'watchlist' => 'Stebimų sąrašas',
'mywatchlist' => 'Stebimų sąrašas',
'watchlistfor2' => 'Naudotojo $1 $2',
'nowatchlist' => 'Neturite nei vieno stebimo puslapio.',
'watchlistanontext' => 'Prašome $1, kad peržiūrėtumėte ar pakeistumėte elementus savo stebimųjų sąraše.',
'watchnologin' => 'Neprisijungęs',
'watchnologintext' => 'Jums reikia būti [[Special:UserLogin|prisijungusiam]], kad pakeistumėte savo stebimųjų sąrašą.',
'addwatch' => 'Pridėti į stebimųjų sąrašą',
'addedwatchtext' => "Puslapis „[[:$1]]“ pridėtas į [[Special:Watchlist|stebimųjų sąrašą]].
Būsimi puslapio bei atitinkamo aptarimo puslapio pakeitimai bus rodomi stebimųjų puslapių sąraše,
taip pat bus '''paryškinti''' [[Special:RecentChanges|naujausių keitimų sąraše]], kad išsiskirtų iš kitų puslapių.",
'removewatch' => 'Pašalinti iš stebimųjų sąrašo',
'removedwatchtext' => 'Puslapis „[[:$1]]“ pašalintas iš jūsų [[Special:Watchlist|stebimųjų sąrašo]].',
'watch' => 'Stebėti',
'watchthispage' => 'Stebėti šį puslapį',
'unwatch' => 'Nebestebėti',
'unwatchthispage' => 'Nustoti stebėti',
'notanarticle' => 'Ne turinio puslapis',
'notvisiblerev' => 'Versija buvo ištrinta',
'watchlist-details' => 'Stebima {{PLURAL:$1|$1 puslapis|$1 puslapiai|$1 puslapių}} neskaičiuojant aptarimų puslapių.',
'wlheader-enotif' => 'El. pašto pranešimai yra įjungti.',
'wlheader-showupdated' => "Puslapiai pakeisti nuo tada, kai paskutinį kartą apsilankėte juose, yra pažymėti '''pastorintai'''",
'watchmethod-recent' => 'tikrinami naujausi stebimųjų puslapių pakeitimai',
'watchmethod-list' => 'ieškoma naujausių keitimų stebimuose puslapiuose',
'watchlistcontains' => 'Jūsų stebimųjų sąraše yra $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}}.',
'iteminvalidname' => 'Problema su elementu „$1“, neteisingas vardas...',
'wlnote' => "{{PLURAL:$1|Rodomas '''$1''' paskutinis pakeitimas, atliktas|Rodomi '''$1''' paskutiniai pakeitimai, atlikti|Rodoma '''$1''' paskutinių pakeitimų, atliktų}} per '''$2''' {{PLURAL:$2|paskutinę valandą|paskutines valandas|paskutinių valandų}}, nuo $3 $4.",
'wlshowlast' => 'Rodyti paskutinių $1 valandų, $2 dienų ar $3 pakeitimus',
'watchlist-options' => 'Stebimųjų sąrašo parinktys',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Įtraukiama į stebimųjų sąrašą...',
'unwatching' => 'Šalinama iš stebimųjų sąrašo...',
'watcherrortext' => 'Keičiant jūsų stebėjimo nustatymus puslapiui „$1“ įvyko klaida.',

'enotif_mailer' => '{{SITENAME}} Pranešimų sistema',
'enotif_reset' => 'Pažymėti visus puslapius kaip aplankytus',
'enotif_impersonal_salutation' => '{{SITENAME}} naudotojau',
'enotif_subject_deleted' => '{{GENDER:$2|Naudotojas}} ištrynė puslapį $1, priklausantį projektui {{SITENAME}}',
'enotif_subject_created' => '{{GENDER:$2|Naudotojas}} sukūrė puslapį $1, priklausantį projektui {{SITENAME}}',
'enotif_subject_moved' => '{{GENDER:$2|Naudotojas}} pervardino puslapį $1, priklausantį projektui {{SITENAME}}',
'enotif_subject_restored' => '{{GENDER:$2|Naudotojas}} atstatė puslapį $1, priklausantį projektui {{SITENAME}}',
'enotif_subject_changed' => '{{GENDER:$2|Naudotojas}} redagavo puslapį $1, priklausantį projektui {{SITENAME}}',
'enotif_body_intro_deleted' => '$PAGEEDITDATE {{GENDER:$2|Naudotojas}} ištrynė puslapį $1, priklausantį projektui {{SITENAME}}, žr.                     $3.',
'enotif_body_intro_created' => '$PAGEEDITDATE {{GENDER:$2|Naudotojas}} sukūrė puslapį $1, priklausantį projektui {{SITENAME}}. Dabartinė versija matoma $3.',
'enotif_body_intro_moved' => '$PAGEEDITDATE {{GENDER:$2|Naudotojas}} pervardino puslapį $1, priklausantį projektui {{SITENAME}}. Dabartinė versija matoma $3.',
'enotif_body_intro_restored' => '$PAGEEDITDATE {{GENDER:$2|Naudotojas}} atstatė puslapį $1, priklausantį projektui {{SITENAME}}. Dabartinė versija matoma $3.',
'enotif_body_intro_changed' => '$PAGEEDITDATE {{GENDER:$2|Naudotojas}} redagavo puslapį $1, priklausantį projektui {{SITENAME}}. Dabartinė versija matoma $3.',
'enotif_lastvisited' => 'Užeikite į $1, jei norite matyti pakeitimus nuo paskutiniojo apsilankymo.',
'enotif_lastdiff' => 'Užeikite į $1, jei norite pamatyti šį pakeitimą.',
'enotif_anon_editor' => 'anoniminis naudotojas $1',
'enotif_body' => '$WATCHINGUSERNAME,


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
Norėdami pakeisti e-paštu siunčiamų pranešimų nustatymus, užeikite į
{{canonicalurl:{{#special:Preferences}}}}

Norėdami pakeisti stebimų puslapių nustatymus, užeikite į
{{canonicalurl:{{#special:EditWatchlist}}}}

Norėdami puslapį iš stebimų puslapių sąrašo, užeikite į
$UNWATCHURL

Atsiliepimai ir pagalba:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'sukurė',
'changed' => 'pakeitė',

# Delete
'deletepage' => 'Trinti puslapį',
'confirm' => 'Tvirtinu',
'excontent' => 'buvęs turinys: „$1“',
'excontentauthor' => 'buvęs turinys: „$1“ (redagavo tik „[[Special:Contributions/$2|$2]]“)',
'exbeforeblank' => 'prieš ištrinant turinys buvo: „$1“',
'exblank' => 'puslapis buvo tuščias',
'delete-confirm' => 'Ištrinti „$1“',
'delete-legend' => 'Trynimas',
'historywarning' => "'''Dėmesio:''' Trinamas puslapis turi istoriją su maždaug $1 {{PLURAL:$1|versija|versijomis|versijų}}:",
'confirmdeletetext' => 'Jūs pasirinkote ištrinti puslapį ar paveikslėlį kartu su visa jo istorija.
Prašome patvirtinti, kad jūs tikrai norite tai padaryti, žinote apie galimus padarinius ir kad tai darote pagal [[{{MediaWiki:Policy-url}}|taisykles]].',
'actioncomplete' => 'Veiksmas atliktas',
'actionfailed' => 'Veiksmas atšauktas',
'deletedtext' => '„$1“ ištrintas.
Paskutinių šalinimų istorija - $2.',
'dellogpage' => 'Šalinimų sąrašas',
'dellogpagetext' => 'Žemiau pateikiamas paskutinių trynimų sąrašas.',
'deletionlog' => 'šalinimų sąrašas',
'reverted' => 'Atkurta į ankstesnę versiją',
'deletecomment' => 'Priežastis:',
'deleteotherreason' => 'Kita/papildoma priežastis:',
'deletereasonotherlist' => 'Kita priežastis',
'deletereason-dropdown' => '*Dažnos trynimo priežastys
** Autoriaus prašymas
** Autorystės teisių pažeidimas
** Vandalizmas',
'delete-edit-reasonlist' => 'Keisti trynimo priežastis',
'delete-toobig' => 'Šis puslapis turi ilgą keitimų istoriją, daugiau nei $1 {{PLURAL:$1|revizija|revizijos|revizijų}}. Tokių puslapių trynimas yra apribotas, kad būtų išvengta atsitiktinio {{SITENAME}} žlugdymo.',
'delete-warning-toobig' => 'Šis puslapis turi ilgą keitimų istoriją, daugiau nei $1 {{PLURAL:$1|revizija|revizijos|revizijų}}. Trinant jis gali sutrikdyti {{SITENAME}} duomenų bazės operacijas; būkite atsargūs.',

# Rollback
'rollback' => 'Atmesti keitimus',
'rollback_short' => 'Atmesti',
'rollbacklink' => 'atmesti',
'rollbacklinkcount' => 'atmesti $1 {{PLURAL:$1|keitimą|keitimus}}',
'rollbacklinkcount-morethan' => 'atmesti daugiau nei $1 {{PLURAL:$1|keitimą|keitimų}}',
'rollbackfailed' => 'Atmetimas nepavyko',
'cantrollback' => 'Negalima atmesti redagavimo; paskutinis keitęs naudotojas yra šio puslapio autorius.',
'alreadyrolled' => 'Nepavyko atmesti paskutinio [[User:$2|$2]] ([[User talk:$2|Aptarimas]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) daryto puslapio [[:$1]] keitimo;
kažkas jau pakeitė puslapį arba suspėjo pirmas atmesti keitimą.

Paskutimas keitimas darytas naudotojo [[User:$3|$3]] ([[User talk:$3|Aptarimas]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Redagavimo komentaras: „''$1''“.",
'revertpage' => 'Atmestas [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarimas]]) pakeitimas; sugrąžinta [[User:$1|$1]] versija',
'revertpage-nouser' => 'Atversti pakeitimai paslėpto vartotojo, grąžino prieš tai buvusią versiją {{GENDER:$1|[[User:$1|$1]]}}',
'rollback-success' => 'Atmesti $1 pakeitimai;
grąžinta prieš tai buvusi $2 versija.',

# Edit tokens
'sessionfailure-title' => 'Sesijos klaida',
'sessionfailure' => 'Atrodo yra problemų su jūsų prisijungimo sesija; šis veiksmas buvo atšauktas kaip atsargumo priemonė prieš sesijos vogimą.
Prašome paspausti „atgal“ ir perkraukite puslapį iš kurio atėjote, ir pamėginkite vėl.',

# Protect
'protectlogpage' => 'Rakinimų sąrašas',
'protectlogtext' => 'Žemiau yra puslapių užrakinimų bei atrakinimų sąrašas.
Dabar veikiančių puslapių apsaugų sąrašą rasite [[Special:ProtectedPages|apsaugotų puslapių sąraše]].',
'protectedarticle' => 'užrakino „[[$1]]“',
'modifiedarticleprotection' => 'pakeistas „[[$1]]“ apsaugos lygis',
'unprotectedarticle' => 'pašalino apsaugą nuo „[[$1]]“',
'movedarticleprotection' => 'perkelti apsaugos nustatymai iš „[[$2]]“ į „[[$1]]“',
'protect-title' => 'Nustatomas apsaugos lygis puslapiui „$1“',
'protect-title-notallowed' => 'Peržiūrėti "$1" apsaugos lygį',
'prot_1movedto2' => '[[$1]] pervadintas į [[$2]]',
'protect-badnamespace-title' => 'Neapsaugota vardų sritis',
'protect-badnamespace-text' => 'Puslapiai šioje vardų srityje negali būti apsaugoti.',
'protect-norestrictiontypes-text' => 'Šis puslapis negali būti apsaugotas nes neturi galimų apribojimų tipų.',
'protect-norestrictiontypes-title' => 'Neapsaugomas puslapis',
'protect-legend' => 'Užrakinimo patvirtinimas',
'protectcomment' => 'Priežastis:',
'protectexpiry' => 'Baigia galioti:',
'protect_expiry_invalid' => 'Galiojimo laikas neteisingas.',
'protect_expiry_old' => 'Galiojimo laikas yra praeityje.',
'protect-unchain-permissions' => 'Atrakinti šiuos apsaugos nustatymus',
'protect-text' => "Čia jūs gali matyti ir keisti apsaugos lygį puslapiui '''$1'''.",
'protect-locked-blocked' => "Jūs negalite keisti apsaugos lygių, kol esate užbluokuotas.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-locked-dblock' => "Apsaugos lygiai negali būti pakeisti dėl duomenų bazės užrakinimo.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-locked-access' => "Jūsų paskyra neturi teisių keisti puslapių apsaugos lygių.
Čia yra dabartiniai nustatymai puslapiui '''$1''':",
'protect-cascadeon' => 'Šis puslapis dabar yra apsaugotas, nes jis yra įtrauktas į {{PLURAL:$1|šį puslapį, apsaugotą|šiuos puslapius, apsaugotus}} „pakopinės apsaugos“ pasirinktimi. Jūs galite pakeisti šio puslapio apsaugos lygį, bet tai nepaveiks pakopinės apsaugos.',
'protect-default' => 'Leisti visiems naudotojams',
'protect-fallback' => 'Reikalauti „$1“ teisės',
'protect-level-autoconfirmed' => 'Blokuoti naujai prisiregistravusius ir neregistruotus naudotojus',
'protect-level-sysop' => 'Leisti tik administratoriams',
'protect-summary-cascade' => 'pakopinė apsauga',
'protect-expiring' => 'baigia galioti $1 (UTC)',
'protect-expiring-local' => 'baigia galioti $1',
'protect-expiry-indefinite' => 'neribotai',
'protect-cascade' => 'Apsaugoti puslapius, įtrauktus į šį puslapį (pakopinė apsauga).',
'protect-cantedit' => 'Jūs negalite keisti šio puslapio apsaugojimo lygių, nes neturite teisių jo redaguoti.',
'protect-othertime' => 'Kitas laikas:',
'protect-othertime-op' => 'kitas laikas',
'protect-existing-expiry' => 'Esamas galiojimo laikas: $3, $2',
'protect-otherreason' => 'Kita/papildoma priežastis:',
'protect-otherreason-op' => 'Kita priežastis',
'protect-dropdown' => '*Įprastos užrakinimo priežastys
** Intensyvus vandalizmas
** Nuolatinis nepageidautinų nuorodų dėliojimas
** Beprasmis redagavimo karas
** Didelės svarbos puslapis
** Pakartotinis ištrinto puslapio atkūrinėjimas',
'protect-edit-reasonlist' => 'Keisti užrakinimo priežastis',
'protect-expiry-options' => '1 valanda:1 hour,1 diena:1 day,1 savaitė:1 week,2 savaitės:2 weeks,1 mėnuo:1 month,3 mėnesiai:3 months,6 mėnesiai:6 months,1 metai:1 year,neribotai:infinite',
'restriction-type' => 'Leidimas:',
'restriction-level' => 'Apribojimo lygis:',
'minimum-size' => 'Min. dydis',
'maximum-size' => 'Maks. dydis:',
'pagesize' => '(baitais)',

# Restrictions (nouns)
'restriction-edit' => 'Redagavimas',
'restriction-move' => 'Pervardinimas',
'restriction-create' => 'Sukurti',
'restriction-upload' => 'Įkelti',

# Restriction levels
'restriction-level-sysop' => 'pilnai apsaugota',
'restriction-level-autoconfirmed' => 'pusiau apsaugota',
'restriction-level-all' => 'bet koks',

# Undelete
'undelete' => 'Atkurti ištrintą puslapį',
'undeletepage' => 'Rodyti ir atkurti ištrintus puslapius',
'undeletepagetitle' => "'''Tai sudaryta iš ištrintų [[:$1]] versijų'''.",
'viewdeletedpage' => 'Rodyti ištrintus puslapius',
'undeletepagetext' => '{{PLURAL:$1|Šis $1 puslapis buvo ištrintas|Šie $1 puslapiai buvo ištrinti|Šie $1 puslapių buvo ištrinti}}, bet dar yra archyve ir gali būti {{PLURAL:$1|atkurtas|atkurti|atkurti}}.
Archyvas gali būti periodiškai valomas.',
'undelete-fieldset-title' => 'Atkurti versijas',
'undeleteextrahelp' => "Norėdami atkurti visą puslapio istoriją, palikite visas varneles nepažymėtas ir spauskite '''''{{int:undeletebtn}}'''''.
Norėdami atlikti pasirinktinį atkūrimą, pažymėkite varneles tų versijų, kurias norėtumėte atkurti, ir spauskite '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|versija|versijos|versijų}} suarchyvuota',
'undeletehistory' => 'Jei atstatysite puslapį, istorijoje bus atstatytos visos versijos.
Jei po ištrynimo buvo sukurtas puslapis tokiu pačiu pavadinimu, atstatytos versijos atsiras ankstesnėje istorijoje.',
'undeleterevdel' => 'Atkūrimas nebus įvykdytas, jei tai nulems paskutinės puslapio ar failo versijos dalinį ištrynimą.
Tokiais atvejais, jums reikia atžymėti arba atslėpti naujausią ištrintą versiją.',
'undeletehistorynoadmin' => 'Šis puslapis buvo ištrintas. Žemiau rodoma trynimo priežastis bei kas redagavo puslapį iki ištrynimo. Ištrintų puslapių tekstas yra galimas tik administratoriams.',
'undelete-revision' => 'Ištrinta $1 versija, kurią $4 d. $5 sukūrė $3:',
'undeleterevision-missing' => 'Neteisinga arba dingusi versija. Jūs turbūt turite blogą nuorodą, arba versija buvo atkurta arba pašalinta iš archyvo.',
'undelete-nodiff' => 'Nerasta jokių ankstesnių versijų.',
'undeletebtn' => 'Atkurti',
'undeletelink' => 'žiūrėti/atkurti',
'undeleteviewlink' => 'žiūrėti',
'undeletereset' => 'Iš naujo',
'undeleteinvert' => 'Žymėti priešingai',
'undeletecomment' => 'Priežastis:',
'undeletedrevisions' => '{{PLURAL:$1|atkurta $1 versija|atkurtos $1 versijos|atkurta $1 versijų}}',
'undeletedrevisions-files' => '{{PLURAL:$1|atkurta $1 versija|atkurtos $1 versijos|atkurta $1 versijų}} ir $2 {{PLURAL:$2|failas|failai|failų}}',
'undeletedfiles' => '{{PLURAL:$1|atkurtas $1 failas|atkurti $1 failai|atkurta $1 failų}}',
'cannotundelete' => 'Atkūrimas nepavyko; kažkas kitas pirmas galėjo atkurti puslapį.',
'undeletedpage' => "'''$1 buvo atkurtas'''

Peržiūrėkite [[Special:Log/delete|trynimų sąrašą]], norėdami rasti paskutinių trynimų ir atkūrimų sąrašą.",
'undelete-header' => 'Kad sužinotumėte, kurie puslapiai paskiausiai ištrinti, žiūrėkite [[Special:Log/delete|šalinimų sąrašą]].',
'undelete-search-title' => 'Panaikintų puslapių paieška',
'undelete-search-box' => 'Ieškoti ištrintų puslapių',
'undelete-search-prefix' => 'Rodyti puslapius pradedant su:',
'undelete-search-submit' => 'Ieškoti',
'undelete-no-results' => 'Nebuvo rasta jokio atitinkančio puslapio ištrynimo archyve.',
'undelete-filename-mismatch' => 'Nepavyksta atkurti failo versijos su laiku $1: failo pavadinimas nesutampa',
'undelete-bad-store-key' => 'Nepavyksta atkurti failo versijos su laiku $1: failas buvo dingęs pries ištrynimą.',
'undelete-cleanup-error' => 'Klaida trinant nenaudotą archyvo failą „$1“.',
'undelete-missing-filearchive' => 'Nepavyksta atkurti failo archyvo ID $1, nes jo nėra duomenų bazėje. Jis gali būti jau atkurtas.',
'undelete-error' => 'Klaida panaikinant puslapį',
'undelete-error-short' => 'Klaida atkuriant failą: $1',
'undelete-error-long' => 'Įvyko klaidų atkuriant failą:

$1',
'undelete-show-file-confirm' => 'Ar tikrai norite peržiūrėti ištrintą failo „<nowiki>$1</nowiki>“ $2 $3 versiją?',
'undelete-show-file-submit' => 'Taip',

# Namespace form on various pages
'namespace' => 'Vardų sritis:',
'invert' => 'Žymėti priešingai',
'tooltip-invert' => 'Įjunkite šią parinktį, jei norite paslėpti nurodytos vardų srities (ir susijusių, jei įjungta parinktis) puslapių pakeitimus',
'namespace_association' => 'Susijusi vardų sritis',
'tooltip-namespace_association' => 'Įjunkite šią parinktį, kad taip pat įtrauktumėte aptarimų arba temos sritį, susijusią su pasirinkta sritimi',
'blanknamespace' => '(Pagrindinė)',

# Contributions
'contributions' => '{{GENDER:$1|Naudotojo}} įndėlis',
'contributions-title' => '{{GENDER:$1|Naudotojo|Naudotojos}} $1 indėlis',
'mycontris' => 'Įnašai',
'contribsub2' => 'Dėl {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'Jokie keitimai neatitiko šių kriterijų.',
'uctop' => '(dabartinis)',
'month' => 'Nuo mėnesio (ir anksčiau):',
'year' => 'Nuo metų (ir anksčiau):',

'sp-contributions-newbies' => 'Rodyti tik naujų paskyrų keitimus',
'sp-contributions-newbies-sub' => 'Neseniai prisiregistravusieji',
'sp-contributions-newbies-title' => 'Naujai užsiregistravusių naudotojų indėlis',
'sp-contributions-blocklog' => 'Blokavimų sąrašas',
'sp-contributions-deleted' => 'ištrintas naudotojo indėlis',
'sp-contributions-uploads' => 'nuotraukos',
'sp-contributions-logs' => 'Specialiųjų veiksmų sąrašas',
'sp-contributions-talk' => 'Aptarimas',
'sp-contributions-userrights' => 'naudotojų teisių valdymas',
'sp-contributions-blocked-notice' => 'Šis naudotojas šiuo metu užblokuotas.
Pateikiamas paskutinis blokavimo istorijos įrašas.',
'sp-contributions-blocked-notice-anon' => 'Šis IP adresas yra užblokuotas.
Paskutinis blokavimo įrašas pateikiamas žemiau:',
'sp-contributions-search' => 'Ieškoti įnašo',
'sp-contributions-username' => 'IP adresas arba naudotojo vardas:',
'sp-contributions-toponly' => 'Rodyti tik paskutinius keitimus',
'sp-contributions-submit' => 'Ieškoti',

# What links here
'whatlinkshere' => 'Susiję puslapiai',
'whatlinkshere-title' => 'Puslapiai, kurie nurodo į „$1“',
'whatlinkshere-page' => 'Puslapis:',
'linkshere' => "Šie puslapiai rodo į '''[[:$1]]''':",
'nolinkshere' => "Į '''[[:$1]]''' nuorodų nėra.",
'nolinkshere-ns' => "Nurodytoje vardų srityje nei vienas puslapis nenurodo į '''[[:$1]]'''.",
'isredirect' => 'nukreipiamasis puslapis',
'istemplate' => 'įterpimas',
'isimage' => 'failo nuoroda',
'whatlinkshere-prev' => '$1 {{PLURAL:$1|ankstesnis|ankstesni|ankstesnių}}',
'whatlinkshere-next' => '$1 {{PLURAL:$1|kitas|kiti|kitų}}',
'whatlinkshere-links' => '← nuorodos',
'whatlinkshere-hideredirs' => '$1 nukreipimus',
'whatlinkshere-hidetrans' => '$1 įtraukimus',
'whatlinkshere-hidelinks' => '$1 nuorodas',
'whatlinkshere-hideimages' => '$1 failų nuorodos',
'whatlinkshere-filters' => 'Filtrai',

# Block/unblock
'autoblockid' => 'Automatinis blokavimas # $1',
'block' => 'Blokuoti naudotoją',
'unblock' => 'Atblokuoti naudotoją',
'blockip' => 'Blokuoti naudotoją',
'blockip-title' => 'Blokuoti naudotoją',
'blockip-legend' => 'Blokuoti naudotoją',
'blockiptext' => 'Naudokite šią formą, kad uždraustumėte redagavimo prieigą pasirinktam IP adresui ar naudotojui. Tai turėtų būti atliekama tik tam, kad sustabdytumėte vandalizmą, ir neprieštarauti [[{{MediaWiki:Policy-url}}|projekte galiojančioms taisyklėms]].
Žemiau pateikite tikslią priežastį (pavyzdžiui, nurodydami sugadintus puslapius).',
'ipadressorusername' => 'IP adresas arba naudotojo vardas',
'ipbexpiry' => 'Galiojimo laikas',
'ipbreason' => 'Priežastis:',
'ipbreasonotherlist' => 'Kita priežastis',
'ipbreason-dropdown' => '*Bendrosios blokavimo priežastys
** Klaidingos informacijos įterpimas
** Turinio šalinimas iš puslapių
** Kitų svetainių reklamavimas
** Nesusijusio arba beprasmio teksto įterpimas į puslapius
** Gąsdinimai/įžeidinėjimai
** Piktnaudžiavimas keliomis paskyromis
** Nepriimtinas naudotojo vardas',
'ipb-hardblock' => 'Neleisti prisijungusiems naudotojams redaguoti iš šio IP adreso',
'ipbcreateaccount' => 'Neleisti kurti paskyrų',
'ipbemailban' => 'Neleisti naudotojui siųsti el. pašto',
'ipbenableautoblock' => 'Automatiškai blokuoti paskutinį naudotojo naudotą IP adresą ir visus kitus adresus, iš kurių jis bandys redaguoti',
'ipbsubmit' => 'Blokuoti šį naudotoją',
'ipbother' => 'Kitoks laikas',
'ipboptions' => '2 valandos:2 hours,1 diena:1 day,3 dienos:3 days,1 savaitė:1 week,2 savaitės:2 weeks,1 mėnesis:1 month,3 mėnesiai:3 months,6 mėnesiai:6 months,1 metai:1 year,neribotai:infinite',
'ipbotheroption' => 'kita',
'ipbotherreason' => 'Kita/papildoma priežastis',
'ipbhidename' => 'Slėpti naudotojo vardą keitimuose bei sąrašuose',
'ipbwatchuser' => 'Stebėti šio naudotojo puslapį ir jo aptarimų puslapį',
'ipb-disableusertalk' => 'Neleisti redaguoti savo naudotojo aptarimo puslapio',
'ipb-change-block' => 'Iš naujo užblokuoti naudotoją, naudojant šiuos nustatymus',
'ipb-confirm' => 'Patvirtinkite blokavimą',
'badipaddress' => 'Neleistinas IP adresas',
'blockipsuccesssub' => 'Užblokavimas pavyko',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] buvo užblokuotas.<br />
Aplankykite [[Special:BlockList|IP blokavimų istoriją]] norėdami jį peržiūrėti.',
'ipb-blockingself' => 'Jūs ruošiatės užblokuoti save! Ar tikrai norite tai padaryti?',
'ipb-confirmhideuser' => 'Jūs ruošiatės užblokuoti naudotoją, pasirinkę „slėpti naudotoją“ nustatymą. Tai paslėps naudotojo vardą visuose sąrašuose ir žurnalo įrašuose. Ar tikrai norite tai padaryti?',
'ipb-edit-dropdown' => 'Redaguoti blokavimų priežastis',
'ipb-unblock-addr' => 'Atblokuoti $1',
'ipb-unblock' => 'Atblokuoti naudotojo vardą arba IP adresą',
'ipb-blocklist' => 'Rodyti egzistuojančius blokavimus',
'ipb-blocklist-contribs' => '$1 indėlis',
'unblockip' => 'Atblokuoti naudotoją',
'unblockiptext' => 'Naudokite šią formą, kad atkurtumėte redagavimo galimybę
ankščiau užblokuotam IP adresui ar naudotojui.',
'ipusubmit' => 'Atblokuoti šį adresą',
'unblocked' => '[[User:$1|$1]] buvo atblokuotas',
'unblocked-range' => '$1 buvo atblokuotas',
'unblocked-id' => 'Blokavimas $1 buvo pašalintas',
'blocklist' => 'Blokuoti naudotojai',
'ipblocklist' => 'Blokuoti naudotojai',
'ipblocklist-legend' => 'Rasti užblokuotą naudotoją',
'blocklist-userblocks' => 'Slėpti paskyrų blokavimus',
'blocklist-tempblocks' => 'Slėpti laikinus blokavimus',
'blocklist-addressblocks' => 'Slėpti vieno IP adreso blokavimus',
'blocklist-rangeblocks' => 'Slėpti IP adresų sričių blokavimus',
'blocklist-timestamp' => 'Laiko žyma',
'blocklist-target' => 'Užblokuotasis',
'blocklist-expiry' => 'Galioja iki',
'blocklist-by' => 'Užblokavęs administratorius',
'blocklist-params' => 'Blokavimo nustatymai',
'blocklist-reason' => 'Priežastis',
'ipblocklist-submit' => 'Ieškoti',
'ipblocklist-localblock' => 'Vietinis blokavimas',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Kitas blokavimas|Kiti blokavimai}}',
'infiniteblock' => 'neribotai',
'expiringblock' => 'baigia galioti $1 $2',
'anononlyblock' => 'tik anonimai',
'noautoblockblock' => 'automatinis blokavimas išjungtas',
'createaccountblock' => 'paskyrų kūrimas uždraustas',
'emailblock' => 'el. paštas užblokuotas',
'blocklist-nousertalk' => 'negali redaguoti savo aptarimų puslapio',
'ipblocklist-empty' => 'Blokavimų sąrašas tuščias.',
'ipblocklist-no-results' => 'Pasirinktas IP adresas ar naudotojo vardas nėra užblokuotas.',
'blocklink' => 'blokuoti',
'unblocklink' => 'atblokuoti',
'change-blocklink' => 'keisti blokavimo nustatymus',
'contribslink' => 'įnašas',
'emaillink' => 'siųsti el. laišką',
'autoblocker' => 'Jūs buvote automatiškai užblokuotas, nes jūsų IP adresą neseniai naudojo „[[User:$1|$1]]“. Nurodyta naudotojo $1 blokavimo priežastis: „$2“.',
'blocklogpage' => 'Blokavimų sąrašas',
'blocklog-showlog' => 'Šis naudotojas buvo užblokuotas.
Pateikiamas paskutinis blokavimo istorijos įrašas.',
'blocklog-showsuppresslog' => 'Šis naudotojas buvo užblokuotas ir paslėptas anksčiau.
Žemiau yra pateiktas slėpimų žurnalas:',
'blocklogentry' => 'blokavo [[$1]], blokavimo laikas - $2 $3',
'reblock-logentry' => 'pakeisti [[$1]] blokavimo nustatymai, naujas blokavimo laikas – $2 $3',
'blocklogtext' => 'Čia yra naudotojų blokavimo ir atblokavimo sąrašas.
Automatiškai blokuoti IP adresai neišvardinti.
Jei norite pamatyti dabar blokuojamus adresus, žiūrėkite [[Special:BlockList|blokavimų sąrašą]].',
'unblocklogentry' => 'atblokavo $1',
'block-log-flags-anononly' => 'tik anoniminiai naudotojai',
'block-log-flags-nocreate' => 'paskyrų kūrimas išjungtas',
'block-log-flags-noautoblock' => 'automatinis blokavimas išjungtas',
'block-log-flags-noemail' => 'el. paštas užblokuotas',
'block-log-flags-nousertalk' => 'negali redaguoti savo naudotojo aptarimo puslapio',
'block-log-flags-angry-autoblock' => 'išplėstasis automatinis blokavimas įjungtas',
'block-log-flags-hiddenname' => 'naudotojo vardas paslėptas',
'range_block_disabled' => 'Administratoriams neleidžiama blokuoti IP adresų sričių.',
'ipb_expiry_invalid' => 'Galiojimo laikas neleistinas.',
'ipb_expiry_temp' => 'Paslėptų naudotojų vardų blokavimas turi būti neribotas.',
'ipb_hide_invalid' => 'Negalima paslėpti šios paskyros; ji gali turėti per daug keitimų.',
'ipb_already_blocked' => '„$1“ jau užblokuotas',
'ipb-needreblock' => '$1 jau yra užblokuotas. Ar norite pakeisti nustatymus?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Kitas blokavimas|Kiti blokavimai}}',
'unblock-hideuser' => 'Jūs negalite atblokuoti šio naudotojo, nes jo vardas buvo paslėptas.',
'ipb_cant_unblock' => 'Klaida: Blokavimo ID $1 nerastas. Galbūt jis jau atblokuotas.',
'ipb_blocked_as_range' => 'Klaida: IP $1 nebuvo užblokuotas tiesiogiai, tad negali būti atblokuotas. Tačiau jis buvo užblokuotas kaip srities $2 dalis, kuri gali būti atblokuota.',
'ip_range_invalid' => 'Neleistina IP sritis.',
'ip_range_toolarge' => 'Didesni nei /$1 blokai neleidžiami.',
'proxyblocker' => 'Tarpinių serverių („proxy“) blokavimo priemonė',
'proxyblockreason' => 'Jūsų IP adresas yra užblokuotas, nes jis yra atvirasis tarpinis serveris. Prašome susisiekti su savo interneto paslaugų tiekėju ar technine pagalba ir praneškite jiems apie šią svarbią saugumo problemą.',
'sorbsreason' => 'Jūsų IP adresas yra įtrauktas į atvirųjų tarpinių serverių DNSBL sąrašą, naudojamą šios svetainės.',
'sorbs_create_account_reason' => 'Jūsų IP adresas yra įtrauktas į atvirųjų tarpinių serverių DNSBL sąrašą, naudojamą šios svetainės. Jūs negalite sukurti paskyros',
'cant-block-while-blocked' => 'Jūs negalite blokuoti kitų naudotojų, pats būdamas užblokuotas.',
'cant-see-hidden-user' => 'Naudotojas, kurį bandote užblokuoti, jau yra užblokuotas arba paslėptas.
Kadangi jūs neturi hideuser teisės, jūs negalite pamatyti arba pakeisti naudotojo blokavimo.',
'ipbblocked' => 'Jūs negalite blokuoti ar atblokuoti kitų naudotojų, nes pats esate užblokuotas',
'ipbnounblockself' => 'Jums nėra leidžiama savęs atblokuoti',

# Developer tools
'lockdb' => 'Užrakinti duomenų bazę',
'unlockdb' => 'Atrakinti duomenų bazę',
'lockdbtext' => 'Užrakinus duomenų bazę sustabdys galimybę visiems
naudotojams redaguoti puslapius, keisti jų nustatymus, keisti jų stebimųjų sąrašą bei
kitus dalykus, reikalaujančius pakeitimų duomenų bazėje.
Prašome patvirtinti, kad tai, ką ketinate padaryti, ir kad jūs
atrakinsite duomenų bazę, kai techninė profilaktika bus baigta.',
'unlockdbtext' => 'Atrakinus duomenų bazę grąžins galimybę visiems
naudotojams redaguoti puslapius, keisti jų nustatymus, keisti jų stebimųjų sąrašą bei
kitus dalykus, reikalaujančius pakeitimų duomenų bazėje.
Prašome patvirtinti tai, ką ketinate padaryti.',
'lockconfirm' => 'Taip, aš tikrai noriu užrakinti duomenų bazę.',
'unlockconfirm' => 'Taip, aš tikrai noriu atrakinti duomenų bazę.',
'lockbtn' => 'Užrakinti duomenų bazę',
'unlockbtn' => 'Atrakinti duomenų bazę',
'locknoconfirm' => 'Jūs neuždėjote patvirtinimo varnelės.',
'lockdbsuccesssub' => 'Duomenų bazės užrakinimas pavyko',
'unlockdbsuccesssub' => 'Duomenų bazės užrakinimas pašalintas',
'lockdbsuccesstext' => 'Duomenų bazė buvo užrakinta.
<br />Nepamirškite [[Special:UnlockDB|pašalinti užraktą]], kai techninė profilaktika bus baigta.',
'unlockdbsuccesstext' => 'Duomenų bazė buvo atrakinta.',
'lockfilenotwritable' => 'Duomenų bazės užrakto failas nėra įrašomas. Norint užrakinti ar atrakinti duomenų bazę, tinklapio serveris privalo turėti įrašymo teises šiam failui.',
'databasenotlocked' => 'Duomenų bazė neužrakinta.',
'lockedbyandtime' => '(užrakino {{GENDER:$1|$1}}, diena $2, laikas $3)',

# Move page
'move-page' => 'Pervadinti $1',
'move-page-legend' => 'Puslapio pervadinimas',
'movepagetext' => "Naudodamiesi žemiau pateikta forma, pervadinsite puslapį
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
'movepagetalktext' => "Susietas aptarimo puslapis bus automatiškai perkeltas kartu su juo, '''išskyrus:''':
*Puslapis nauju pavadinimu jau turi netuščią aptarimo puslapį, arba
*Paliksite žemiau esančia varnelę nepažymėtą.

Šiais atvejais jūs savo nuožiūra turite perkelti arba apjungti aptarimo puslapį.",
'movearticle' => 'Pervardinti puslapį:',
'moveuserpage-warning' => "'''Dėmesio:''' Jūs ruošiatės perkelti naudotojo puslapį. Atkreipkite dėmesį, kad bus perkeltas tik puslapis, naudotojas ''nebus'' pervadintas.",
'movenologin' => 'Neprisijungęs',
'movenologintext' => 'Norėdami pervadinti puslapį, turite būti užsiregistravęs naudotojas ir būti  [[Special:UserLogin|prisijungęs]].',
'movenotallowed' => 'Jūs neturite teisių pervadinti puslapių.',
'movenotallowedfile' => 'Jūs neturite teisės perkelti failus.',
'cant-move-user-page' => 'Jūs neturite teisės pervardyti naudotojų puslapių (išskyrus subpuslapius).',
'cant-move-to-user-page' => 'Jūs neturite teisių perkelti puslapį į naudotojo puslapį (išskyrus į naudotojo popuslapį).',
'newtitle' => 'Naujas pavadinimas:',
'move-watch' => 'Stebėti šį puslapį',
'movepagebtn' => 'Pervadinti puslapį',
'pagemovedsub' => 'Pervadinta sėkmingai',
'movepage-moved' => "'''„$1“ buvo pervadintas į „$2“'''",
'movepage-moved-redirect' => 'Nukreipimas sukurtas.',
'movepage-moved-noredirect' => 'Nukreipimo sukūrimas buvo atšauktas.',
'articleexists' => 'Puslapis tokiu pavadinimu jau egzistuoja
arba pasirinktas vardas yra neteisingas.
Pasirinkite kitą pavadinimą.',
'cantmove-titleprotected' => 'Jūs negalite pervadinti puslapio, nes naujasis pavadinimas buvo apsaugotas nuo sukūrimo',
'talkexists' => "'''Pats puslapis buvo sėkmingai pervadintas, bet aptarimų puslapis nebuvo perkeltas, kadangi naujo
pavadinimo puslapis jau turėjo aptarimų puslapį.
Prašome sujungti šiuos puslapius.'''",
'movedto' => 'pervardintas į',
'movetalk' => 'Perkelti susijusį aptarimo puslapį.',
'move-subpages' => 'Perkelti visus subpuslapius (baigiant $1)',
'move-talk-subpages' => 'Perkelti visus aptarimo subpuslapius (iki $1)',
'movepage-page-exists' => 'Puslapis $1 jau egzistuoja ir negali būti automatiškai perrašytas.',
'movepage-page-moved' => 'Puslapis $1 perkeltas į $2.',
'movepage-page-unmoved' => 'Puslapio $1 negalima perkelti į $2.',
'movepage-max-pages' => 'Daugiausiai $1 {{PLURAL:$1|puslapis buvo perkeltas|puslapiai buvo perkelti|puslapių buvo perkelta}} ir daugiau nebus perkelta automatiškai.',
'movelogpage' => 'Perkėlimų sąrašas',
'movelogpagetext' => 'Pervardintų puslapių sąrašas.',
'movesubpage' => '{{PLURAL:$1|Subpuslapis|Subpuslapiai}}',
'movesubpagetext' => 'Žemiau yra šio puslapio $1 {{PLURAL:$1|subpuslapis|subpuslapiai|subpuslapių}}.',
'movenosubpage' => 'Šis puslapis neturi subpuslapių.',
'movereason' => 'Priežastis:',
'revertmove' => 'atmesti',
'delete_and_move' => 'Ištrinti ir perkelti',
'delete_and_move_text' => '==Reikia ištrinti==

Paskirties puslapis „[[:$1]]“ jau yra. Ar norite jį ištrinti, kad galėtumėte pervardinti?',
'delete_and_move_confirm' => 'Taip, trinti puslapį',
'delete_and_move_reason' => 'Ištrinta dėl perkėlimo iš "[[$1]]"',
'selfmove' => 'Šaltinio ir paskirties pavadinimai yra tokie patys; negalima pervardinti puslapio į save.',
'immobile-source-namespace' => 'Negalima perkelti puslapių vardų srityje „$1“',
'immobile-target-namespace' => 'Perkelti puslapius į „$1“ vardų sritį negalima',
'immobile-target-namespace-iw' => 'Tarprojektinė nuoroda yra neleistina paskirtis perkelti puslapį.',
'immobile-source-page' => 'Šio puslapio perkelti negalima.',
'immobile-target-page' => 'Negalima perkelti į paskirtąją vietą.',
'imagenocrossnamespace' => 'Negalima pervadinti failo į ne failo vardų sritį',
'nonfile-cannot-move-to-file' => 'Negalima perkelti ne failo į failų vardų sritį',
'imagetypemismatch' => 'Naujas failo plėtinys neatitinka jo tipo',
'imageinvalidfilename' => 'Failo pavadinimas yra klaidingas',
'fix-double-redirects' => 'Atnaujinti peradresavimus, kad šie rodytų į originalų straipsnio pavadinimą',
'move-leave-redirect' => 'Pervadinant palikti nukreipimą',
'protectedpagemovewarning' => "'''Dėmesio:''' Šis puslapis buvo užrakintas, kad tik naudotojai su administratoriaus teisėmis galėtų jį pervadinti.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'semiprotectedpagemovewarning' => "'''Pastaba''': Šis puslapis buvo užrakintas, kad tik registruoti naudotojai galėtų jį redaguoti.
Naujausias įrašas žurnale yra pateiktas žemiau:",
'move-over-sharedrepo' => '== Failas jau yra ==
[[:$1]] egzistuoja bendrojoje saugykloje. Perkėlus failą į šį pavadinimą, jis pakeis bendrąjį failą.',
'file-exists-sharedrepo' => 'Pasirinktas failo pavadinimas jau yra naudojamas bendrojoje saugykloje.
Prašome pasirinkti kitą pavadinimą.',

# Export
'export' => 'Eksportuoti puslapius',
'exporttext' => 'Galite eksportuoti vieno puslapio tekstą ir istoriją ar kelių puslapių vienu metu tame pačiame XML atsakyme.
Šie puslapiai galės būti importuojami į kitą projektą, veikiantį MediaWiki pagrindu, per [[Special:Import|importo puslapį]].

Norėdami eksportuoti puslapius, įveskite pavadinimus žemiau esančiame tekstiniame lauke po vieną pavadinimą eilutėje, taip pat pasirinkite ar norite eksportuoti ir istoriją ar tik dabartinę versiją su paskutinio redagavimo informacija.

Pastaruoju atveju, jūs taip pat galite naudoti nuorodą, pvz. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] puslapiui „[[{{MediaWiki:Mainpage}}]]“.',
'exportall' => 'Eksportuoti visus puslapius',
'exportcuronly' => 'Eksportuoti tik dabartinę versiją, neįtraukiant istorijos',
'exportnohistory' => "----
'''Pastaba:''' Pilnos puslapių istorijos eksportavimas naudojantis šia forma yra išjungtas dėl spartos.",
'exportlistauthors' => 'Įtraukti kiekvieno puslapio pilną visų redaktorių sąrašą',
'export-submit' => 'Eksportuoti',
'export-addcattext' => 'Pridėti puslapius iš kategorijos:',
'export-addcat' => 'Pridėti',
'export-addnstext' => 'Pridėti puslapius iš vardų srities:',
'export-addns' => 'Pridėti',
'export-download' => 'Saugoti kaip failą',
'export-templates' => 'Įtraukti šablonus',
'export-pagelinks' => 'Įtraukti susietus puslapius iki šio gylio:',

# Namespace 8 related
'allmessages' => 'Visi sistemos tekstai bei pranešimai',
'allmessagesname' => 'Pavadinimas',
'allmessagesdefault' => 'Pradinis tekstas',
'allmessagescurrent' => 'Dabartinis tekstas',
'allmessagestext' => 'Čia pateikiamas sisteminių pranešimų sąrašas, esančių MediaWiki vardų srityje.
Aplankykite [//www.mediawiki.org/wiki/Localisation „MediaWiki“ lokaliziciją] ir [//translatewiki.net „translatewiki.net“], jei norite prisidėti prie bendrojo „MediaWiki“ lokalizavimo.',
'allmessagesnotsupportedDB' => "Šis puslapis nepalaikomas, nes nuostata '''\$wgUseDatabaseMessages''' yra išjungtas.",
'allmessages-filter-legend' => 'Filtras',
'allmessages-filter' => 'Filtruoti pagal būseną:',
'allmessages-filter-unmodified' => 'Nepakeisti',
'allmessages-filter-all' => 'Visi',
'allmessages-filter-modified' => 'Pakeisti',
'allmessages-prefix' => 'Filtruoti pagal pradžią:',
'allmessages-language' => 'Kalba:',
'allmessages-filter-submit' => 'Rodyti',

# Thumbnails
'thumbnail-more' => 'Padidinti',
'filemissing' => 'Dingęs failas',
'thumbnail_error' => 'Klaida kuriant sumažintą paveikslėlį: $1',
'thumbnail_error_remote' => 'Klaidos pranešimas iš $1: $2',
'djvu_page_error' => 'DjVu puslapis nepasiekiamas',
'djvu_no_xml' => 'Nepavyksta gauti XML DjVu failui',
'thumbnail-temp-create' => 'Negalima sukurti laikinos failo miniatiūros',
'thumbnail-dest-create' => 'Negalima išsaugoti failo miniatiūros',
'thumbnail_invalid_params' => 'Neleistini miniatiūros parametrai',
'thumbnail_dest_directory' => 'Nepavyksta sukurti paskirties aplanko',
'thumbnail_image-type' => 'Paveikslėlio tipas nėra palaikomas',
'thumbnail_gd-library' => 'Nepilna GD bibliotekos konfigūracija: trūksta funkcijos $1',
'thumbnail_image-missing' => 'Gali būti, kad failo nėra: $1',

# Special:Import
'import' => 'Importuoti puslapius',
'importinterwiki' => 'Tarpprojektinis importas',
'import-interwiki-text' => 'Pasirinkite projektą ir puslapio pavadinimą importavimui.
Versijų datos ir redaktorių vardai bus išlaikyti.
Visi tarpprojektiniai importo veiksmai yra registruojami  [[Special:Log/import|importo istorijoje]].',
'import-interwiki-source' => 'Šaltinio wiki projektas/puslapis:',
'import-interwiki-history' => 'Kopijuoti visas istorijos versijas šiam puslapiui',
'import-interwiki-templates' => 'Įtraukti visus šablonus',
'import-interwiki-submit' => 'Importuoti',
'import-interwiki-namespace' => 'Paskirties vardų sritis:',
'import-interwiki-rootpage' => 'Paskirties namų puslapis (pasirinktinai):',
'import-upload-filename' => 'Failo pavadinimas:',
'import-comment' => 'Komentaras:',
'importtext' => 'Prašome eksportuoti iš projekto-šaltinio failo, naudojant [[Special:Export|eksportavimo pagalbininką.]]
Išsaugokite jį savo kompiuteryje ir įkelkite jį čia.',
'importstart' => 'Imporuojami puslapiai...',
'import-revision-count' => '$1 {{PLURAL:$1|versija|versijos|versijų}}',
'importnopages' => 'Nėra puslapių importavimui.',
'imported-log-entries' => 'Importuota $1 {{PLURAL:$1|prisijungimo įrašas|prisijungimo įrašai}}.',
'importfailed' => 'Importavimas nepavyko: <nowiki>$1</nowiki>',
'importunknownsource' => 'Nežinomas importo šaltinio tipas',
'importcantopen' => 'Nepavyksta atverti importo failo',
'importbadinterwiki' => 'Bloga tarpprojektinė nuoroda',
'importnotext' => 'Tuščia arba jokio teksto',
'importsuccess' => 'Importas užbaigtas!',
'importhistoryconflict' => 'Yra konfliktuojanti istorijos versija (galbūt šis puslapis buvo importuotas anksčiau)',
'importnosources' => 'Nenustatyti transwiki importo šaltiniai, o tiesioginis praeities įkėlimas uždraustas.',
'importnofile' => 'Nebuvo įkeltas joks importo failas.',
'importuploaderrorsize' => 'Importavimo failo įkėlimas nepavyko. Failas didesnis nei leidžiamas dydis.',
'importuploaderrorpartial' => 'Importavimo failo įkėlimas nepavyko. Failas buvo tik dalinai įkeltas.',
'importuploaderrortemp' => 'Importavimo failo įkėlimas nepavyko. Trūksta laikinojo aplanko.',
'import-parse-failure' => 'XML importo nagrinėjimo klaida',
'import-noarticle' => 'Nėra puslapių importuoti!',
'import-nonewrevisions' => 'Visos versijos buvo importuotos anksčiau.',
'xml-error-string' => '$1 $2 eilutėje, $3 stulpelyje ($4 baitas): $5',
'import-upload' => 'Įkelti XML duomenis',
'import-token-mismatch' => 'Sesijos duomenys prarasti. Bandykite iš naujo.',
'import-invalid-interwiki' => 'Nepavyko importuoti iš nurodyto wiki projekto.',
'import-error-edit' => 'Puslapis "$1" nebuvo įkeltas, kadangi jūs neturite teisės jį redaguoti.',
'import-error-create' => 'Puslapis "$1" nebuvo įkeltas, kadangi jūs neturite teisės jį sukurti.',
'import-error-interwiki' => 'Puslapis "$1" nebuvo įkeltas, kadangi jo pavadinimas yra rezervuotas išorinei nuorodai (interviki).',
'import-error-special' => 'Puslapis "$1" nebuvo įkeltas, kadangi jis priklauso specialiai vardų sričiai, kurioje yra negalimi puslapiai.',
'import-error-invalid' => 'Puslapis "$1" nebuvo įkeltas, kadangi jo vardas yra neteisingas.',
'import-rootpage-invalid' => 'Duotas šaknų puslapis yra blogas pavadinimas.',

# Import log
'importlogpage' => 'Importavimų sąrašas',
'importlogpagetext' => 'Administraciniai puslapių importai su keitimų istorija iš kitų wiki projektų.',
'import-logentry-upload' => 'importuota $1 įkeliant failą',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}}',
'import-logentry-interwiki' => 'tarpprojektinis $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|keitimas|keitimai|keitimų}} iš $2',

# JavaScriptTest
'javascripttest' => 'JavaScript testavimas',
'javascripttest-title' => 'Vykdomas $1 testavimas',
'javascripttest-pagetext-noframework' => 'Šis puslapis yra skirtas vykdyti JavaScript testavimus.',
'javascripttest-pagetext-unknownframework' => 'Nežinoma "$1" testavimo struktūra.',
'javascripttest-pagetext-frameworks' => 'Prašome pasirinkti vieną iš išvardintų testavimo struktūrų: $1',
'javascripttest-pagetext-skins' => 'Pasirinkite naudotojo sąsajos išvaizdą, kuriai atliksite testavimą:',
'javascripttest-qunit-intro' => 'Peržiūrėkite [$1 testavimo dokumentaciją]',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit bandymų komplektas',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Jūsų naudotojo puslapis',
'tooltip-pt-anonuserpage' => 'Naudotojo puslapis jūsų IP adresui',
'tooltip-pt-mytalk' => 'Jūsų aptarimo puslapis',
'tooltip-pt-anontalk' => 'Pakeitimų aptarimas, darytus naudojant šį IP adresą',
'tooltip-pt-preferences' => 'Mano nustatymai',
'tooltip-pt-watchlist' => 'Puslapių sąrašas, kuriuos jūs pasirinkote stebėti.',
'tooltip-pt-mycontris' => 'Jūsų darytų keitimų sąrašas',
'tooltip-pt-login' => 'Rekomenduojame prisijungti, nors tai nėra privaloma.',
'tooltip-pt-anonlogin' => 'Rekomenduojame prisijungti, nors tai nėra privaloma.',
'tooltip-pt-logout' => 'Atsijungti',
'tooltip-ca-talk' => 'Puslapio turinio aptarimas',
'tooltip-ca-edit' => 'Jūs galite redaguoti šį puslapį. Nepamirškite paspausti peržiūros mygtuką prieš išsaugodami.',
'tooltip-ca-addsection' => 'Pradėti naują aptariamą temą',
'tooltip-ca-viewsource' => 'Puslapis yra užrakintas. Galite pažiūrėti turinį.',
'tooltip-ca-history' => 'Ankstesnės puslapio versijos.',
'tooltip-ca-protect' => 'Užrakinti šį puslapį',
'tooltip-ca-unprotect' => 'Keisti šio puslapio apsaugą',
'tooltip-ca-delete' => 'Ištrinti šį puslapį',
'tooltip-ca-undelete' => 'Atkurti puslapį su visais darytais keitimais',
'tooltip-ca-move' => 'Pervadinti puslapį',
'tooltip-ca-watch' => 'Pridėti puslapį į stebimųjų sąrašą',
'tooltip-ca-unwatch' => 'Pašalinti puslapį iš stebimųjų sąrašo',
'tooltip-search' => 'Ieškoti šiame projekte',
'tooltip-search-go' => 'Eiti į puslapį su tokiu pavadinimu, jei toks yra',
'tooltip-search-fulltext' => 'Ieškoti puslapių su šiuo tekstu',
'tooltip-p-logo' => 'Eiti į pradinį puslapį',
'tooltip-n-mainpage' => 'Eiti į pradinį puslapį',
'tooltip-n-mainpage-description' => 'Eiti į pradinį puslapį',
'tooltip-n-portal' => 'Apie projektą, ką galima daryti, kur ką rasti',
'tooltip-n-currentevents' => 'Raskite naujausią informaciją',
'tooltip-n-recentchanges' => 'Paskutinių keitimų sąrašas šiame projekte.',
'tooltip-n-randompage' => 'Įkelti atsitiktinį puslapį',
'tooltip-n-help' => 'Vieta, kur rasite rūpimus atsakymus.',
'tooltip-t-whatlinkshere' => 'Puslapių sąrašas, rodančių į čia',
'tooltip-t-recentchangeslinked' => 'Paskutiniai keitimai puslapiuose, pasiekiamuose iš šio puslapio',
'tooltip-feed-rss' => 'Šio puslapio RSS šaltinis',
'tooltip-feed-atom' => 'Šio puslapio Atom šaltinis',
'tooltip-t-contributions' => 'Rodyti šio naudotojo keitimų sąrašą',
'tooltip-t-emailuser' => 'Siųsti laišką šiam naudotojui',
'tooltip-t-upload' => 'Įkelti failus',
'tooltip-t-specialpages' => 'Specialiųjų puslapių sąrašas',
'tooltip-t-print' => 'Šio puslapio versija spausdinimui',
'tooltip-t-permalink' => 'Nuolatinė nuoroda į šią puslapio versiją',
'tooltip-ca-nstab-main' => 'Rodyti puslapio turinį',
'tooltip-ca-nstab-user' => 'Rodyti naudotojo puslapį',
'tooltip-ca-nstab-media' => 'Rodyti media puslapį',
'tooltip-ca-nstab-special' => 'Šis puslapis yra specialusis - jo negalima redaguoti.',
'tooltip-ca-nstab-project' => 'Rodyti projekto puslapį',
'tooltip-ca-nstab-image' => 'Rodyti failo puslapį',
'tooltip-ca-nstab-mediawiki' => 'Rodyti sisteminį pranešimą',
'tooltip-ca-nstab-template' => 'Rodyti šabloną',
'tooltip-ca-nstab-help' => 'Rodyti pagalbos puslapį',
'tooltip-ca-nstab-category' => 'Rodyti kategorijos puslapį',
'tooltip-minoredit' => 'Pažymėti keitimą kaip smulkų',
'tooltip-save' => 'Išsaugoti pakeitimus',
'tooltip-preview' => 'Pakeitimų peržiūra, prašome pažiūrėti prieš išsaugant!',
'tooltip-diff' => 'Rodo, kokius pakeitimus padarėte tekste.',
'tooltip-compareselectedversions' => 'Žiūrėti dviejų pasirinktų puslapio versijų skirtumus.',
'tooltip-watch' => 'Pridėti šį puslapį į stebimųjų sąrašą',
'tooltip-watchlistedit-normal-submit' => 'Šalinti puslapius',
'tooltip-watchlistedit-raw-submit' => 'Atnaujinti stebimųjų sąrašą',
'tooltip-recreate' => 'Atkurti puslapį nepaisant to, kad jis buvo ištrintas',
'tooltip-upload' => 'Pradėti įkėlimą',
'tooltip-rollback' => 'Vienu spustelėjimu grąžinama prieš tai redagavusio naudotojo versija',
'tooltip-undo' => '„Anuliuoti“ atmeta šį keitimą ir atveria ankstesnės versijos redagavimo formą.
Leidžia pridėti atmetimo priežastį komentaruose',
'tooltip-preferences-save' => 'Išsaugoti nustatymus',
'tooltip-summary' => 'Įvesti trumpą santrauką',

# Stylesheets
'common.css' => '/** Čia įdėtas CSS bus taikomas visoms išvaizdoms */',
'monobook.css' => '/* Čia įdėtas CSS bus rodomas Monobook išvaizdos naudotojams */',

# Scripts
'common.js' => '/* Bet koks čia parašytas JavaScript bus rodomas kiekviename puslapyje kievienam naudotojui. */',
'monobook.js' => '/* Šis JavaScript bus įkeltas tik „MonoBook“ išvaizdos naudotojams. */',

# Metadata
'notacceptable' => 'Projekto serveris negali pateikti duomenų formatu, kurį jūsų klientas galėtų skaityti.',

# Attribution
'anonymous' => '{{SITENAME}} {{PLURAL:$1|anoniminis naudotojas|anoniminiai naudotojai}}',
'siteuser' => '{{SITENAME}} {{GENDER:$2|naudotojas|naudotoja}} $1',
'anonuser' => '{{SITENAME}} anoniminis naudotojas $1',
'lastmodifiedatby' => 'Šį puslapį paskutinį kartą redagavo $3 $2, $1.',
'othercontribs' => 'Paremta $1 darbu.',
'others' => 'kiti',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|naudotojas|naudotojai}} $1',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|anoniminis naudotojas|anoniminiai naudotojai}} $1',
'creditspage' => 'Puslapio kūrėjai',
'nocredits' => 'Kūrėjų informacija negalima šiam puslapiui.',

# Spam protection
'spamprotectiontitle' => 'Priešreklaminis filtras',
'spamprotectiontext' => 'Tekstas, kurį norėjote išsaugoti, buvo užblokuotas priešreklaminio filtro. Taip turbūt įvyko dėl nuorodos į juodajame sąraše esančią svetainę.',
'spamprotectionmatch' => 'Šis tekstas buvo atpažintas priešreklaminio filtro: $1',
'spambot_username' => 'MediaWiki reklamų šalinimas',
'spam_reverting' => 'Atkuriama į ankstesnę versiją, neturinčios nuorodų į $1',
'spam_blanking' => 'Visos versijos turėjo nuorodų į $1, išvaloma',
'spam_deleting' => 'Visos versijos turėjo nuorodų į $1, ištrinama',

# Info page
'pageinfo-title' => '„$1“ informacija',
'pageinfo-not-current' => 'Atsiprašome, neįmanoma pateikti šios senų versijų informacijos.',
'pageinfo-header-basic' => 'Pagrindinė informacija',
'pageinfo-header-edits' => 'Redagavimo istorija',
'pageinfo-header-restrictions' => 'Puslapio apsaugos lygmuo',
'pageinfo-header-properties' => 'Puslapio savybės',
'pageinfo-display-title' => 'Rodyti pavadinimą',
'pageinfo-default-sort' => 'Numatytasis rūšiavimo raktas',
'pageinfo-length' => 'Puslapio ilgis (baitais)',
'pageinfo-article-id' => 'Puslapio ID',
'pageinfo-language' => 'Puslapio turinio kalba',
'pageinfo-robot-policy' => 'Paieškos variklio būsena',
'pageinfo-robot-index' => 'Indeksuotas',
'pageinfo-robot-noindex' => 'Neindeksuotas',
'pageinfo-views' => 'Peržiūrų skaičius',
'pageinfo-watchers' => 'Puslapio stebėtojų skaičius',
'pageinfo-redirects-name' => 'Nukreipimai į šį puslapį',
'pageinfo-subpages-name' => 'Šio puslapio papuslapiai',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|peradresavimas|peradresavimai}}; $3 {{PLURAL:$3|neperadresavimas|peradresavimai}})',
'pageinfo-firstuser' => 'Puslapio kūrėjas',
'pageinfo-firsttime' => 'Puslapio sukūrimo data',
'pageinfo-lastuser' => 'Paskutinis redaktorius',
'pageinfo-lasttime' => 'Paskutinio keitimo data',
'pageinfo-edits' => 'Keitimų skaičius',
'pageinfo-authors' => 'Skirtingų autorių skaičius',
'pageinfo-recent-edits' => 'Paskutinųjų keitimų skaičius (per $1 laikotarpį)',
'pageinfo-recent-authors' => 'Pastarųjų skirtingų redaguotojų skaičius',
'pageinfo-magic-words' => 'Magiškas(-i) {{PLURAL:$1|žodis|žodžiai}} ($1)',
'pageinfo-toolboxlink' => 'Puslapio informacija',
'pageinfo-redirectsto' => 'Nukreipimai į',
'pageinfo-redirectsto-info' => 'informacija',
'pageinfo-contentpage' => 'Priskirtas turinio puslapiams',
'pageinfo-contentpage-yes' => 'Taip',
'pageinfo-protect-cascading' => 'Apsaugos yra kaskaduotos iš čia',
'pageinfo-protect-cascading-yes' => 'Taip',
'pageinfo-protect-cascading-from' => 'Apsaugos yra kaskaduotos iš',
'pageinfo-category-info' => 'Informacija apie kategoriją',
'pageinfo-category-pages' => 'Puslapių skaičius',
'pageinfo-category-subcats' => 'Dukterinių kategorijų skaičius',
'pageinfo-category-files' => 'Failų skaičius',

# Skin names
'skinname-cologneblue' => 'Kelno mėlyna',
'skinname-monobook' => 'MonoBook',
'skinname-modern' => 'Moderni',
'skinname-vector' => 'Vektorinė',

# Patrolling
'markaspatrolleddiff' => 'Žymėti, kad patikrinta',
'markaspatrolledtext' => 'Pažymėti, kad puslapis patikrintas',
'markedaspatrolled' => 'Pažymėtas kaip patikrintas',
'markedaspatrolledtext' => 'Pasirinkta [[:$1]] versija pažymėta kaip patikrinta.',
'rcpatroldisabled' => 'Paskutinių keitimų tikrinimas išjungtas',
'rcpatroldisabledtext' => 'Paskutinių keitimų tikrinimo funkcija šiuo metu išjungta.',
'markedaspatrollederror' => 'Negalima pažymėti, kad patikrinta',
'markedaspatrollederrortext' => 'Jums reikia nurodyti versiją, kurią pažymėti kaip patikrintą.',
'markedaspatrollederror-noautopatrol' => 'Jums neleidžiama pažymėti savo paties keitimų kaip patikrintų.',
'markedaspatrollednotify' => '$1 keitimas pažymėtas patikrintu.',
'markedaspatrollederrornotify' => 'Nepavyko pažymėti kaip patikrinto.',

# Patrol log
'patrol-log-page' => 'Patikrinimų sąrašas',
'patrol-log-header' => 'Tai patvirtintų versijų sąrašas.',
'log-show-hide-patrol' => '$1 patvirtinimų sąrašą',

# Image deletion
'deletedrevision' => 'Ištrinta sena versija $1',
'filedeleteerror-short' => 'Klaida trinant failą: $1',
'filedeleteerror-long' => 'Įvyko klaidų trinant failą:

$1',
'filedelete-missing' => 'Failas „$1“ negali būti ištrintas, nes jo nėra.',
'filedelete-old-unregistered' => 'Nurodytos failo versijos „$1“ nėra duomenų bazėje.',
'filedelete-current-unregistered' => 'Nurodyto failo „$1“ nėra duomenų bazėje.',
'filedelete-archive-read-only' => 'Serveriui neleidžiama rašyti į archyvo aplanką „$1“.',

# Browsing diffs
'previousdiff' => '← Ankstesnis keitimas',
'nextdiff' => 'Vėlesnis pakeitimas →',

# Media information
'mediawarning' => "'''Dėmesio''': Šis failas gali turėti kenksmingą kodą.
Jį paleidus jūsų sistema gali būti pažeista.",
'imagemaxsize' => "Riboti paveikslėlių dydį:<br />''(failų aprašymo puslapiuose)''",
'thumbsize' => 'Sumažintų paveikslėlių dydis:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|puslapis|puslapiai|puslapių}}',
'file-info' => 'failo dydis: $1, MIME tipas: $2',
'file-info-size' => '$1 × $2 taškų, failo dydis: $3, MIME tipas: $4',
'file-info-size-pages' => '$1 × $2 taškų, failo dydis: $3, MIME tipas: $4, $5 {{PLURAL:$5|page|pages}}',
'file-nohires' => 'Geresnė raiška negalima.',
'svg-long-desc' => 'SVG failas, formaliai $1 × $2 taškų, failo dydis: $3',
'svg-long-desc-animated' => 'Animuotas SVG failas, formaliai $1 × $2 pikselių, failo dydis: $3',
'svg-long-error' => 'Neleistinas SVG failas: $1',
'show-big-image' => 'Pilna raiška',
'show-big-image-preview' => 'Sumažintos iliustracijos dydis: $1 .',
'show-big-image-other' => '{{PLURAL:$2|Kita rezoliucija|Kitos $2 rezoliucijos|Kitų $2 rezoliucijų}}: $1 .',
'show-big-image-size' => '$1 × $2 taškų',
'file-info-gif-looped' => 'ciklinis',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kadras|kadrai|kadrų}}',
'file-info-png-looped' => 'ciklinis',
'file-info-png-repeat' => 'grota $1 {{PLURAL:$1|kartą|kartus|kartų}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|kadras|kadrai|kadrų}}',
'file-no-thumb-animation' => "'''Pastaba: Dėl techninių apribojimų, miniatiūrų, šis failas negali būti animacinis.'''",

# Special:NewFiles
'newimages' => 'Naujausių failų galerija',
'imagelisttext' => "Žemiau yra '''$1''' {{PLURAL:$1|failo|failų|failų}} sąrašas, surūšiuotas $2.",
'newimages-summary' => 'Šis specialus puslapis rodo paskiausiai įkeltus failus.',
'newimages-legend' => 'Filtras',
'newimages-label' => 'Failo vardas (ar jo dalis):',
'showhidebots' => '($1 robotus)',
'noimages' => 'Nėra ką parodyti.',
'ilsubmit' => 'Ieškoti',
'bydate' => 'pagal datą',
'sp-newimages-showfrom' => 'Rodyti naujus failus pradedant nuo $1 $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekundę|$1 sekundes|$1 sekundžių}}',
'minutes' => '{{PLURAL:$1|$1 minutę|$1 minutes|$1 minučių}}',
'hours' => '{{PLURAL:$1|$1 valandą|$1 valandas|$1 valandų}}',
'days' => '{{PLURAL:$1|$1 dieną|$1 dienas|$1 dienų}}',
'weeks' => '{{PLURAL:$1|$1 savaitė|$1 savaitės}}',
'months' => '{{PLURAL:$1|$1 mėnuo|$1 mėnesiai}}',
'years' => '{{PLURAL:$1|$1 metai}}',
'ago' => 'prieš $1',
'just-now' => 'tik dabar',

# Human-readable timestamps
'hours-ago' => 'prieš $1 {{PLURAL:$1|valandą|valandas|valandų}}',
'minutes-ago' => 'prieš $1 {{PLURAL:$1|minutę|minutes|minučių}}',
'seconds-ago' => 'prieš $1 {{PLURAL:$1|sekundę|sekundes|sekundžių}}',
'monday-at' => 'Pirmadienį $1',
'tuesday-at' => 'Antradienį $1',
'wednesday-at' => 'Trečiadienį $1',
'thursday-at' => 'Ketvirtadienį $1',
'friday-at' => 'Penktadienį $1',
'saturday-at' => 'Šeštadienį $1',
'sunday-at' => 'Sekmadienį $1',
'yesterday-at' => 'Vakar $1',

# Bad image list
'bad_image_list' => 'Formatas yra toks:

Tik eilutės, prasidedančios *, yra įtraukiamos.
Pirmoji nuoroda eilutėje turi būti nuoroda į blogą failą.
Visos kitos nuorodos toje pačioje eilutėje yra laikomos išimtimis, t. y. puslapiai, kuriuose leidžiama įterpti failą.',

# Metadata
'metadata' => 'Metaduomenys',
'metadata-help' => 'Šiame faile yra papildomos informacijos, tikriausiai pridėtos skaitmeninės kameros ar skaitytuvo, naudoto jam sukurti ar perkelti į skaitmeninį formatą. Jei failas buvo pakeistas iš pradinės versijos, kai kurios detalės gali nepilnai atspindėti naują failą.',
'metadata-expand' => 'Rodyti išplėstinę informaciją',
'metadata-collapse' => 'Slėpti išplėstinę informaciją',
'metadata-fields' => 'Vaizdo metaduomenų laukai, nurodyti šiame pranešime, bus įtraukti į paveikslėlio puslapį, kai metaduomenų lentelė bus suskleista.! N! kiti bus paslėpti.!
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
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Plotis',
'exif-imagelength' => 'Aukštis',
'exif-bitspersample' => 'Bitai komponente',
'exif-compression' => 'Suspaudimo tipas',
'exif-photometricinterpretation' => 'Taškų struktūra',
'exif-orientation' => 'Pasukimas',
'exif-samplesperpixel' => 'Komponentų skaičius',
'exif-planarconfiguration' => 'Duomenų išdėstymas',
'exif-ycbcrsubsampling' => 'Y iki C atrankos santykis',
'exif-ycbcrpositioning' => 'Y ir C pozicija',
'exif-xresolution' => 'Horizontali raiška',
'exif-yresolution' => 'Vertikali raiška',
'exif-stripoffsets' => 'Paveikslėlio duomenų vieta',
'exif-rowsperstrip' => 'Eilių skaičius juostoje',
'exif-stripbytecounts' => 'Baitai suspaustje juostoje',
'exif-jpeginterchangeformat' => 'JPEG SOI pozicija',
'exif-jpeginterchangeformatlength' => 'JPEG duomenų baitai',
'exif-whitepoint' => 'Balto taško chromatiškumas',
'exif-primarychromaticities' => 'Pagrindinių spalvų chromiškumas',
'exif-ycbcrcoefficients' => 'Spalvų pristatym matricos matricos koeficientai',
'exif-referenceblackwhite' => 'Juodos ir baltos poros nuorodos reikšmės',
'exif-datetime' => 'Failo keitimo data ir laikas',
'exif-imagedescription' => 'Paveikslėlio pavadinimas',
'exif-make' => 'Kameros gamintojas',
'exif-model' => 'Kameros modelis',
'exif-software' => 'Naudota programinė įranga',
'exif-artist' => 'Autorius',
'exif-copyright' => 'Autorystės teisių savininkas',
'exif-exifversion' => 'Exif versija',
'exif-flashpixversion' => 'Palaikoma Flashpix versija',
'exif-colorspace' => 'Spalvų pristatymas',
'exif-componentsconfiguration' => 'kiekvieno komponento reikšmė',
'exif-compressedbitsperpixel' => 'Paveikslėlio suspaudimo režimas',
'exif-pixelydimension' => 'Paveikslėlio plotis',
'exif-pixelxdimension' => 'Vaizdo aukštis',
'exif-usercomment' => 'Naudotojo komentarai',
'exif-relatedsoundfile' => 'Susijusi garso byla',
'exif-datetimeoriginal' => 'Duomenų generavimo data ir laikas',
'exif-datetimedigitized' => 'Pervedimo į skaitmeninį formatą data ir laikas',
'exif-subsectime' => 'Datos ir laiko sekundės dalys',
'exif-subsectimeoriginal' => 'Duomenų generavimo datos ir laiko sekundės dalys',
'exif-subsectimedigitized' => 'Pervedimo į skaitmeninį formatą datos ir laiko sekundės dalys',
'exif-exposuretime' => 'Išlaikymo laikas',
'exif-exposuretime-format' => '$1 sek. ($2)',
'exif-fnumber' => 'F numeris',
'exif-exposureprogram' => 'Išlaikymo programa',
'exif-spectralsensitivity' => 'Spektrinis jautrumas',
'exif-isospeedratings' => 'ISO greitis',
'exif-shutterspeedvalue' => 'APEX užrakto greičio',
'exif-aperturevalue' => 'APEX diafragma',
'exif-brightnessvalue' => 'APEX ryškumas',
'exif-exposurebiasvalue' => 'Išlaikymo paklaida',
'exif-maxaperturevalue' => 'Mažiausias lešio F numeris',
'exif-subjectdistance' => 'Objekto atstumas',
'exif-meteringmode' => 'Matavimo režimas',
'exif-lightsource' => 'Šviesos šaltinis',
'exif-flash' => 'Blykstė',
'exif-focallength' => 'Židinio nuotolis',
'exif-subjectarea' => 'Objekto zona',
'exif-flashenergy' => 'Blykstės energija',
'exif-focalplanexresolution' => 'Židinio projekcijos X raiška',
'exif-focalplaneyresolution' => 'Židinio projekcijos Y raiška',
'exif-focalplaneresolutionunit' => 'Židinio projekcijos raiškos matavimo vienetai',
'exif-subjectlocation' => 'Objekto vieta',
'exif-exposureindex' => 'Išlaikymo indeksas',
'exif-sensingmethod' => 'Jutimo režimas',
'exif-filesource' => 'Failo šaltinis',
'exif-scenetype' => 'Scenos tipas',
'exif-customrendered' => 'Pasirinktinis vaizdo apdorojimas',
'exif-exposuremode' => 'Išlaikymo režimas',
'exif-whitebalance' => 'Baltumo balansas',
'exif-digitalzoomratio' => 'Skaitmeninio priartinimo koeficientas',
'exif-focallengthin35mmfilm' => 'Židinio nuotolis 35 mm juostoje',
'exif-scenecapturetype' => 'Scenos fiksavimo tipas',
'exif-gaincontrol' => 'Scenos kontrolė',
'exif-contrast' => 'Kontrastas',
'exif-saturation' => 'Sodrumas',
'exif-sharpness' => 'Aštrumas',
'exif-devicesettingdescription' => 'Įrenginio nustatymų aprašas',
'exif-subjectdistancerange' => 'Objekto nuotolis',
'exif-imageuniqueid' => 'Unikalusis paveikslėlio ID',
'exif-gpsversionid' => 'GPS versija',
'exif-gpslatituderef' => 'Šiaurės ar pietų platuma',
'exif-gpslatitude' => 'Platuma',
'exif-gpslongituderef' => 'Rytų ar vakarų ilguma',
'exif-gpslongitude' => 'Ilguma',
'exif-gpsaltituderef' => 'Aukščio nuoroda',
'exif-gpsaltitude' => 'Aukštis',
'exif-gpstimestamp' => 'GPS laikas (atominis laikrodis)',
'exif-gpssatellites' => 'Palydovai, naudoti matavimui',
'exif-gpsstatus' => 'Gaviklio būsena',
'exif-gpsmeasuremode' => 'Matavimo režimas',
'exif-gpsdop' => 'Matavimo tikslumas',
'exif-gpsspeedref' => 'Greičio vienetai',
'exif-gpsspeed' => 'GPS gaviklio greitis',
'exif-gpstrackref' => 'Nuoroda judėjimo krypčiai',
'exif-gpstrack' => 'Judėjimo kryptis',
'exif-gpsimgdirectionref' => 'Nuoroda vaizdo krypčiai',
'exif-gpsimgdirection' => 'Nuotraukos kryptis',
'exif-gpsmapdatum' => 'Panaudoti geodeziniai apžvalgos duomenys',
'exif-gpsdestlatituderef' => 'Nuoroda paskirties platumai',
'exif-gpsdestlatitude' => 'Paskirties platuma',
'exif-gpsdestlongituderef' => 'Nuoroda paskirties ilgumai',
'exif-gpsdestlongitude' => 'Paskirties ilguma',
'exif-gpsdestbearingref' => 'Nuoroda į paskirties pelengą',
'exif-gpsdestbearing' => 'Paskirties pelengas',
'exif-gpsdestdistanceref' => 'Nuoroda atstumui iki paskirties',
'exif-gpsdestdistance' => 'Atstumas iki paskirties',
'exif-gpsprocessingmethod' => 'GPS apdorojimo metodo pavadinimas',
'exif-gpsareainformation' => 'GPS zonos pavadinimas',
'exif-gpsdatestamp' => 'GPS data',
'exif-gpsdifferential' => 'GPS diferiancialo pataisymas',
'exif-jpegfilecomment' => 'JPEG failas komentarą',
'exif-keywords' => 'Raktiniai žodžiai',
'exif-worldregioncreated' => 'Pasaulio regione, kad nuotrauka buvo imtasi',
'exif-countrycreated' => 'Šalis, kad nuotrauka buvo imtasi',
'exif-countrycodecreated' => 'Kodas šaliai, kad nuotrauka buvo imtasi',
'exif-provinceorstatecreated' => 'Provincijos ar nurodyti, kad nuotrauka buvo imtasi',
'exif-citycreated' => 'Miestas, kad nuotrauka buvo imtasi',
'exif-sublocationcreated' => 'Sublocation miesto, kad nuotrauka buvo imtasi',
'exif-worldregiondest' => 'Pasaulio regionas rodomas',
'exif-countrydest' => 'Šalis rodomas',
'exif-countrycodedest' => 'Kodas šalies rodomas',
'exif-provinceorstatedest' => 'Rodoma provincija arba valstija',
'exif-citydest' => 'Rodomas miestas',
'exif-sublocationdest' => 'Miesto vietovė rodoma',
'exif-objectname' => 'Trumpas pavadinimas',
'exif-specialinstructions' => 'Specialiosios instrukcijos',
'exif-headline' => 'Antraštė',
'exif-credit' => 'Padėka/tiekėjas',
'exif-source' => 'Šaltinis',
'exif-editstatus' => 'Paveikslėlio redagavimo būsena',
'exif-urgency' => 'Skuba',
'exif-fixtureidentifier' => 'Pastovių duomenų pavadinimas',
'exif-locationdest' => 'Rodoma vietovė',
'exif-locationdestcode' => 'Rodomos vietovės kodas',
'exif-objectcycle' => 'Dienos laikas, kuriam skiriamas turinys',
'exif-contact' => 'Kontaktinė informacija',
'exif-writer' => 'Rašytojas',
'exif-languagecode' => 'Kalba',
'exif-iimversion' => 'IIM versija',
'exif-iimcategory' => 'Kategorija',
'exif-iimsupplementalcategory' => 'Papildomos kategorijos',
'exif-datetimeexpires' => 'Nenaudokite po',
'exif-datetimereleased' => 'Išleista',
'exif-originaltransmissionref' => 'Pradinis perdavimo vietos kodas',
'exif-identifier' => 'Identifikatorius',
'exif-lens' => 'Naudotas objektyvas',
'exif-serialnumber' => 'kameros serijinis numeris',
'exif-cameraownername' => 'Fotoaparato savininkas',
'exif-label' => 'Etiketė',
'exif-datetimemetadata' => 'Paskutinį kartą metadata duomenys keisti',
'exif-nickname' => 'Neoficialus paveikslėlio pavadinimas',
'exif-rating' => 'Vertinimas (iki 5)',
'exif-rightscertificate' => 'Teisių valdymo sertifikatas',
'exif-copyrighted' => 'Autorių teisių statusas',
'exif-copyrightowner' => 'Autorystės teisių savininkas',
'exif-usageterms' => 'Naudojimo sąlygos',
'exif-webstatement' => 'Autorių teisių pareiškimas internete',
'exif-originaldocumentid' => 'Unikalus ID orginalus dokumentas',
'exif-licenseurl' => 'Autorių teisių licencijos URL',
'exif-morepermissionsurl' => 'Alternatyvi licencijavimo informacija',
'exif-attributionurl' => 'Kai pakartotinai naudojate ši darbą, prašome nurodyti į',
'exif-preferredattributionname' => 'Kai naudojate ši darbą prašome nurodyti',
'exif-pngfilecomment' => 'JPEG failo komentaras',
'exif-disclaimer' => 'Atsakomybės apribojimas',
'exif-contentwarning' => 'Turinio įspėjimas',
'exif-giffilecomment' => 'GIF failo komentaras',
'exif-intellectualgenre' => 'Elemento tipas',
'exif-subjectnewscode' => 'Objektas kodas',
'exif-scenecode' => 'IPTC scenos kodas',
'exif-event' => 'Vaizduojamas įvykis',
'exif-organisationinimage' => 'Vaizduojama organizacija',
'exif-personinimage' => 'Vaizduojamas asmuo',
'exif-originalimageheight' => 'Piešinio aukštis prieš apkarpymą',
'exif-originalimagewidth' => 'Piešinio plotis prieš apkarpymą',

# Exif attributes
'exif-compression-1' => 'Nesuspausta',
'exif-compression-2' => 'CCITT grupės 3 1-Dimensijos Modifikuotas Hafmano duomenų paleidimo ilgio kodavimas.',
'exif-compression-3' => 'CCITT 3 grupės fakso kodavimas',
'exif-compression-4' => 'CCITT 4 grupės fakso kodavimas',

'exif-copyrighted-true' => 'Autorinės teisės',
'exif-copyrighted-false' => 'Viešas domenas',

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

'exif-colorspace-65535' => 'Spalvos nekalibruotos',

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

'exif-meteringmode-0' => 'Nežinoma',
'exif-meteringmode-1' => 'Vidurkis',
'exif-meteringmode-2' => 'Centruotas vidurkis',
'exif-meteringmode-3' => 'Taškas',
'exif-meteringmode-4' => 'Daugiataškis',
'exif-meteringmode-5' => 'Raštas',
'exif-meteringmode-6' => 'Dalinis',
'exif-meteringmode-255' => 'Kita',

'exif-lightsource-0' => 'Nežinomas',
'exif-lightsource-1' => 'Dienos šviesa',
'exif-lightsource-2' => 'Fluorescentinis',
'exif-lightsource-3' => 'Volframas (kaitinamoji lempa)',
'exif-lightsource-4' => 'Blykstė',
'exif-lightsource-9' => 'Giedras oras',
'exif-lightsource-10' => 'Debesuotas oras',
'exif-lightsource-11' => 'Šešėlis',
'exif-lightsource-12' => 'Dienos šviesos fluorescentinis (D 5700 – 7100K)',
'exif-lightsource-13' => 'Dienos baltumo fluorescentinis (N 4600 – 5400K)',
'exif-lightsource-14' => 'Šalto baltumo fluorescentinis (W 3900 – 4500K)',
'exif-lightsource-15' => 'Baltas fluorescentinis (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standartinis apšvietimas A',
'exif-lightsource-18' => 'Standartinis apšvietimas B',
'exif-lightsource-19' => 'Standartinis apšvietimas C',
'exif-lightsource-24' => 'ISO studijos volframas',
'exif-lightsource-255' => 'Kitas šviesos šaltinis',

# Flash modes
'exif-flash-fired-0' => 'Blykstė nemirktelėjo',
'exif-flash-fired-1' => 'Blykstė mirktelėjo',
'exif-flash-return-0' => 'jokios blyksčių grįžties aptikimo funkcijos',
'exif-flash-return-2' => 'blykstės grįžtamoji šviesa neaptikta',
'exif-flash-return-3' => 'blykstės grįžtamoji šviesa aptikta',
'exif-flash-mode-1' => 'priverstinė blykstė',
'exif-flash-mode-2' => 'priverstinis blykstės sulaikymas',
'exif-flash-mode-3' => 'automatinis režimas',
'exif-flash-function-1' => 'Be blykstės funkcijos',
'exif-flash-redeye-1' => 'raudonų akių šalinimo režimas',

'exif-focalplaneresolutionunit-2' => 'coliai',

'exif-sensingmethod-1' => 'Nenurodytas',
'exif-sensingmethod-2' => 'Vienalustis spalvų zonos jutiklis',
'exif-sensingmethod-3' => 'Dvilustis spalvų zonos jutiklis',
'exif-sensingmethod-4' => 'Trilustis spalvų zonos jutiklis',
'exif-sensingmethod-5' => 'Nuoseklusis spalvų zonos jutiklis',
'exif-sensingmethod-7' => 'Trilinijinis jutiklis',
'exif-sensingmethod-8' => 'Spalvų nuoseklusis linijinis jutiklis',

'exif-filesource-3' => 'Skaitmeninis fotoaparatas',

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

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1| metras | metrai}} virš jūros lygio',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1| metras | metrai}} žemiau jūros lygio',

'exif-gpsstatus-a' => 'Matavimas vykdyme',
'exif-gpsstatus-v' => 'Matuojamas programinis sąveikumas',

'exif-gpsmeasuremode-2' => 'Dvimatis matavimas',
'exif-gpsmeasuremode-3' => 'Trimatis matavimas',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometrai per valandą',
'exif-gpsspeed-m' => 'Mylios per valandą',
'exif-gpsspeed-n' => 'Mazgai',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometrai',
'exif-gpsdestdistance-m' => 'Mylios',
'exif-gpsdestdistance-n' => 'Jūrmylės',

'exif-gpsdop-excellent' => 'Puikus ($1)',
'exif-gpsdop-good' => 'Geras ( $1 )',
'exif-gpsdop-moderate' => 'Vidutinis ($1)',
'exif-gpsdop-fair' => 'Prastas ($1)',
'exif-gpsdop-poor' => 'Blogas ( $1 )',

'exif-objectcycle-a' => 'Tik ryte',
'exif-objectcycle-p' => 'Tik vakare',
'exif-objectcycle-b' => 'Ir ryte ir vakare',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Tikroji kryptis',
'exif-gpsdirection-m' => 'Magnetinė kryptis',

'exif-ycbcrpositioning-1' => 'Centruotas',
'exif-ycbcrpositioning-2' => 'Bendras išdėstymas',

'exif-dc-contributor' => 'Autoriai',
'exif-dc-coverage' => 'Erdvės ar laiko apimtis',
'exif-dc-date' => 'Data (-os)',
'exif-dc-publisher' => 'Leidėjas',
'exif-dc-relation' => 'Susijusi medija',
'exif-dc-rights' => 'Teisės',
'exif-dc-source' => 'Šaltinis',
'exif-dc-type' => 'Laikmenos tipas',

'exif-rating-rejected' => 'Atmesta',

'exif-isospeedratings-overflow' => 'Didesnis už 65535',

'exif-iimcategory-ace' => 'Menas, kultūra ir pramogos',
'exif-iimcategory-clj' => 'Nusikalstamumas ir įstatymas',
'exif-iimcategory-dis' => 'Nelaimės ir nelaimingi atsitikimai',
'exif-iimcategory-fin' => 'Ekonomika ir verslas',
'exif-iimcategory-edu' => 'Švietimas',
'exif-iimcategory-evn' => 'Aplinka',
'exif-iimcategory-hth' => 'Sveikata',
'exif-iimcategory-hum' => 'Žmogaus interesai',
'exif-iimcategory-lab' => 'Darbas',
'exif-iimcategory-lif' => 'Gyvenimo būdas ir laisvalaikis',
'exif-iimcategory-pol' => 'Politika',
'exif-iimcategory-rel' => 'Raligija ir tikėjimas',
'exif-iimcategory-sci' => 'Mokslas ir technologijos',
'exif-iimcategory-soi' => 'Socialiniai klausimai',
'exif-iimcategory-spo' => 'Sportas',
'exif-iimcategory-war' => 'Karas, konfliktas ir neramumai',
'exif-iimcategory-wea' => 'Oras',

'exif-urgency-normal' => 'Normalus ( $1 )',
'exif-urgency-low' => 'Žemas ( $1 )',
'exif-urgency-high' => 'Aukštas ( $1 )',
'exif-urgency-other' => 'Vartotojo nustatyta pirmenybė ($1)',

# External editor support
'edit-externally' => 'Atverti išoriniame redaktoriuje',
'edit-externally-help' => '(Norėdami gauti daugiau informacijos, žiūrėkite [//www.mediawiki.org/wiki/Manual:External_editors diegimo instrukcijas])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'visus',
'namespacesall' => 'visos',
'monthsall' => 'visi',
'limitall' => 'visi',

# Email address confirmation
'confirmemail' => 'Patvirtinkite el. pašto adresą',
'confirmemail_noemail' => 'Jūs neturite nurodę teisingo el. pašto adreso [[Special:Preferences|savo nustatymuose]].',
'confirmemail_text' => 'Šiame projekte būtina patvirtinti el. pašto adresą prieš naudojant el. pašto funkcijas. Spustelkite žemiau esantį mygtuką,
kad jūsų el. pašto adresu būtų išsiųstas patvirtinimo kodas.
Laiške bus atsiųsta nuoroda su kodu, kuria nuėjus, el. pašto adresas bus patvirtintas.',
'confirmemail_pending' => 'Patvirtinimo kodas jums jau nusiųstas; jei neseniai sukūrėte savo paskyrą, jūs turėtumėte palaukti jo dar kelias minutes prieš prašydami naujo kodo.',
'confirmemail_send' => 'Išsiųsti patvirtinimo kodą',
'confirmemail_sent' => 'Patvirtinimo laiškas išsiųstas.',
'confirmemail_oncreate' => 'Patvirtinimo kodas buvo išsiųstas jūsų el. pašto adresu.
Šis kodas nėra būtinas, kad prisijungtumėte, bet jums reikės jį duoti prieš įjungiant el. pašto paslaugas projekte.',
'confirmemail_sendfailed' => '{{SITENAME}} neišsiuntė patvirtinamojo laiško. Patikrinkite, ar adrese nėra klaidingų simbolių.

Pašto tarnyba atsakė: $1',
'confirmemail_invalid' => 'Neteisingas patvirtinimo kodas. Kodo galiojimas gali būti jau pasibaigęs.',
'confirmemail_needlogin' => 'Jums reikia $1, kad patvirtintumėte savo el. pašto adresą.',
'confirmemail_success' => 'Jūsų el. pašto adresas patvirtintas. Dabar galite prisijungti ir mėgautis projektu.',
'confirmemail_loggedin' => 'Jūsų el. pašto adresas patvirtintas.',
'confirmemail_error' => 'Patvirtinimo metu įvyko neatpažinta klaida.',
'confirmemail_subject' => '{{SITENAME}} el. pašto adreso patvirtinimas',
'confirmemail_body' => 'Kažkas, tikriausiai jūs IP adresu $1, užregistravo
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
'confirmemail_body_set' => 'Kažkas (tikriausiai jūs) iš IP adreso $1,
nustatė svetainės {{SITENAME}} paskyros „$2“ e-pašto adresą į jūsiškį.

Kad patvirtintumėte, kad ši paskyra tikrai priklauso jums ir tokiu būdu aktyvuotumėte
e-pašto funkcijas svetainėje {{SITENAME}}, atverkite šią nuorodą jūsų naršyklėje:

$3

Jei paskyra jums *nepriklauso*, spauskite šią nuorodą,
kad atšauktumėte e-pašto adreso patvirtinimą:

$5

Šis patvirtinimo kodas baigs galioti $4.',
'confirmemail_invalidated' => 'El. pašto adreso patvirtinimas atšauktas',
'invalidateemail' => 'El. pašto patvirtinimo atšaukimas',

# Scary transclusion
'scarytranscludedisabled' => '[Tarpprojektinis įterpimas yra išjungtas]',
'scarytranscludefailed' => '[Šablono gavimas iš $1 nepavyko]',
'scarytranscludefailed-httpstatus' => '[Šablono iškviesti nepavyko $1: HTTP $2]',
'scarytranscludetoolong' => '[URL per ilgas]',

# Delete conflict
'deletedwhileediting' => 'Dėmesio: Šis puslapis ištrintas po to, kai pradėjote redaguoti!',
'confirmrecreate' => "{{GENDER:$1|Naudotojas&nbsp;|Naudotoja&nbsp;|}}[[User:$1|$1]] ([[User talk:$1|aptarimas]]) ištrynė šį puslapį po to, kai pradėjote jį redaguoti. Trynimo priežastis:
: ''$2''
Prašome patvirtinti, kad tikrai norite iš naujo sukurti puslapį.",
'confirmrecreate-noreason' => '{{GENDER:$1|Naudotojas&nbsp;|Naudotoja&nbsp;|}}[[User:$1|$1]] ([[User talk:$1|aptarimas]]) ištrynė šį puslapį po to, kai jūs pradėjote redaguoti. Prašome patvirtinti, jog jūs tikrai norite atkurti šį puslapį.',
'recreate' => 'Atkurti',

# action=purge
'confirm_purge_button' => 'Gerai',
'confirm-purge-top' => 'Išvalyti šio puslapio podėlį?',
'confirm-purge-bottom' => 'Puslapio perkūrimas išvalo podėlį ir priverčia sugeneruoti pačią naujausią puslapio versiją.',

# action=watch/unwatch
'confirm-watch-button' => 'Gerai',
'confirm-watch-top' => 'Pridėti šį puslapį į stebimųjų sąrašą?',
'confirm-unwatch-button' => 'Gerai',
'confirm-unwatch-top' => 'Pašalinti šį puslapį iš jūsų stebimųjų sąrašo?',

# Multipage image navigation
'imgmultipageprev' => '← ankstesnis puslapis',
'imgmultipagenext' => 'kitas puslapis →',
'imgmultigo' => 'Eiti!',
'imgmultigoto' => 'Eitį į puslapį $1',

# Table pager
'ascending_abbrev' => 'didėjanti tvarka',
'descending_abbrev' => 'mažėjanti tvarka',
'table_pager_next' => 'Kitas puslapis',
'table_pager_prev' => 'Ankstesnis puslapis',
'table_pager_first' => 'Pirmas puslapis',
'table_pager_last' => 'Paskutinis puslapis',
'table_pager_limit' => 'Rodyti po $1 puslapyje',
'table_pager_limit_label' => 'Elementai puslapyje:',
'table_pager_limit_submit' => 'Rodyti',
'table_pager_empty' => 'Jokių rezultatų',

# Auto-summaries
'autosumm-blank' => 'Šalinamas visas turinys iš puslapio',
'autosumm-replace' => 'Puslapis keičiamas su „$1“',
'autoredircomment' => 'Nukreipiama į [[$1]]',
'autosumm-new' => 'Naujas puslapis: $1',

# Size units
'size-kilobytes' => '$1 KiB',
'size-megabytes' => '$1 MiB',
'size-gigabytes' => '$1 GiB',

# Live preview
'livepreview-loading' => 'Įkeliama…',
'livepreview-ready' => 'Įkeliama… Paruošta!',
'livepreview-failed' => 'Nepavyko tiesioginė peržiūra! Pamėginkite paprastąją peržiūrą.',
'livepreview-error' => 'Nepavyko prisijungti: $1 „$2“. Pamėginkite paprastąją peržiūrą.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pakeitimai, naujesni nei $1 {{PLURAL:$1|sekundė|sekundės|sekundžių}}, šiame sąraše gali būti nerodomi.',
'lag-warn-high' => 'Dėl didelio duomenų bazės atsilikimo pakeitimai, naujesni nei $1 {{PLURAL:$1|sekundė|sekundės|sekundžių}}, šiame sąraše gali būti nerodomi.',

# Watchlist editor
'watchlistedit-numitems' => 'Jūsų stebimųjų sąraše yra $1 {{PLURAL:$1|puslapis|puslapiai|puslapių}} neskaičiuojant aptarimų puslapių.',
'watchlistedit-noitems' => 'Jūsų stebimųjų sąraše nėra jokių puslapių.',
'watchlistedit-normal-title' => 'Redaguoti stebimųjų sąrašą',
'watchlistedit-normal-legend' => 'Šalinti puslapius iš stebimųjų sąrašo',
'watchlistedit-normal-explain' => 'Žemiau yra rodomi puslapiai jūsų stebimųjų sąraše.
Norėdami pašalinti puslapį, prie jo uždėkite varnelė ir paspauskite „{{int:Watchlistedit-normal-submit}}“.
Jūs taip pat galite [[Special:EditWatchlist/raw|redaguoti grynąjį stebimųjų sąrašą]].',
'watchlistedit-normal-submit' => 'Šalinti puslapius',
'watchlistedit-normal-done' => '$1 {{PLURAL:$1|puslapis buvo pašalintas|puslapiai buvo pašalinti|puslapių buvo pašalinta}} iš jūsų stebimųjų sąrašo:',
'watchlistedit-raw-title' => 'Redaguoti grynąjį stebimųjų sąrašą',
'watchlistedit-raw-legend' => 'Redaguoti grynąjį stebimųjų sąrašą',
'watchlistedit-raw-explain' => 'Žemiau rodomi puslapiai jūsų stebimųjų sąraše, ir gali būti pridėti į ar pašalinti iš sąrašo;
vienas puslapis eilutėje.
Baigę paspauskite „{{int:Watchlistedit-raw-submit}}“.
Jūs taip pat galite [[Special:EditWatchlist|naudoti standartinį redaktorių]].',
'watchlistedit-raw-titles' => 'Puslapiai:',
'watchlistedit-raw-submit' => 'Atnaujinti stebimųjų sąrašą',
'watchlistedit-raw-done' => 'Jūsų stebimųjų sąrašas buvo atnaujintas.',
'watchlistedit-raw-added' => '$1 {{PLURAL:$1|puslapis buvo pridėtas|puslapiai buvo pridėti|puslapių buvo pridėta}}:',
'watchlistedit-raw-removed' => '$1 {{PLURAL:$1|puslapis buvo pašalintas|puslapiai buvo pašalinti|puslapių buvo pašalinta}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Rodyti susijusius keitimus',
'watchlisttools-edit' => 'Rodyti ir redaguoti stebimųjų sąrašą',
'watchlisttools-raw' => 'Redaguoti grynąjį sąrašą',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|aptarimas]])',

# Core parser functions
'unknown_extension_tag' => 'Nežinoma priedo žymė „$1“',
'duplicate-defaultsort' => 'Įspėjimas: Numatytasis rikiavimo raktas „$2“ pakeičia ankstesnį numatytąjį rikiavimo raktą „$1“.',

# Special:Version
'version' => 'Versija',
'version-extensions' => 'Įdiegti priedai',
'version-specialpages' => 'Specialieji puslapiai',
'version-parserhooks' => 'Analizatoriaus gaudliai',
'version-variables' => 'Kintamieji',
'version-antispam' => 'Apsauga nuo šlamšto',
'version-skins' => 'Išvaizda',
'version-other' => 'Kita',
'version-mediahandlers' => 'Daugialypės terpės grotuvai',
'version-hooks' => 'Gaudliai',
'version-parser-extensiontags' => 'Analizatoriaus papildomosios gairės',
'version-parser-function-hooks' => 'Analizatoriaus funkciniai gaudliai',
'version-hook-name' => 'Gaudlio pavadinimas',
'version-hook-subscribedby' => 'Užsakyta',
'version-version' => '(Versija $1)',
'version-license' => 'Licencija',
'version-poweredby-credits' => "Šis projektas naudoja '''[//www.mediawiki.org/ MediaWiki]''', autorystės teisės © 2001-$1 $2.",
'version-poweredby-others' => 'kiti',
'version-credits-summary' => 'Už indėlį kuriant [[Special:Version|MediaWiki]] dėkojame',
'version-license-info' => 'MediaWiki yra nemokama programinė įranga; galite ją platinti ir/arba modifikuoti pagal GNU General Public License, kurią publikuoja Free Software Foundation; taikoma 2-oji licenzijos versija arba (Jūsų pasirinkimu) bet kuri vėlesnė versija. 

MediaWiki yra platinama su viltimi, kad ji bus naudinga, bet BE JOKIOS GARANTIJOS; be jokios numanomos PARDAVIMO arba TINKAMUMO TAM TIKRAM TIKSLUI garantijos. Daugiau informacijos galite sužinoti GNU General Public License. 

Jūs turėjote gauti [{{SERVER}}{{SCRIPTPATH}}/COPYING GNU General Public License kopiją] kartu su šia programa, jei ne, rašykite Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, JAV arba [//www.gnu.org/licenses/old-licenses/gpl-2.0.html perskaitykite ją internete].',
'version-software' => 'Įdiegta programinė įranga',
'version-software-product' => 'Produktas',
'version-software-version' => 'Versija',
'version-entrypoints' => 'Įėjimo taško URL',
'version-entrypoints-header-entrypoint' => 'Įėjimo taškas',
'version-entrypoints-header-url' => 'URL',

# Special:Redirect
'redirect' => 'Nukreipkite iš failo, naudotojo arba pakeiskite ID',
'redirect-legend' => 'Nukreipti į failą ar puslapį',
'redirect-submit' => 'Eiti',
'redirect-lookup' => 'Peržvalgos:',
'redirect-value' => 'Vertė:',
'redirect-user' => 'Naudotojo ID',
'redirect-revision' => 'Puslapio peržiūra',
'redirect-file' => 'Failo vardas',
'redirect-not-exists' => 'Vertė nėra nustatyta',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Ieškoti dublikuotų failų',
'fileduplicatesearch-summary' => 'Pasikartojančių failų paieška pagal jų kontrolinę sumą.',
'fileduplicatesearch-legend' => 'Ieškoti dublikatų',
'fileduplicatesearch-filename' => 'Failo vardas:',
'fileduplicatesearch-submit' => 'Ieškoti',
'fileduplicatesearch-info' => '$1 × $2 pikselių<br />Failo dydis: $3<br />MIME tipas: $4',
'fileduplicatesearch-result-1' => 'Failas „$1“ neturi identiškų dublikatų.',
'fileduplicatesearch-result-n' => 'Šis failas „$1“ turi $2 {{PLURAL:$2|identišką dublikatą|identiškus dublikatus|identiškų dublikatų}}.',
'fileduplicatesearch-noresults' => 'Nėra failo pavadinimu "$1".',

# Special:SpecialPages
'specialpages' => 'Specialieji puslapiai',
'specialpages-note' => '----
 * įprastą specialius puslapius.
 * <span class="mw-specialpagerestricted">tik specialius puslapius.</span>
 * <span class="mw-specialpagecached">Talpyklinių specialius puslapius (gali būti pasenusius).</span>',
'specialpages-group-maintenance' => 'Sistemos palaikymo pranešimai',
'specialpages-group-other' => 'Kiti specialieji puslapiai',
'specialpages-group-login' => 'Prisijungti / sukurti paskyrą',
'specialpages-group-changes' => 'Naujausi keitimai ir istorijos',
'specialpages-group-media' => 'Informacija apie failus ir jų pakrovimas',
'specialpages-group-users' => 'Naudotojai ir teisės',
'specialpages-group-highuse' => 'Plačiai naudojami puslapiai',
'specialpages-group-pages' => 'Puslapių sąrašas',
'specialpages-group-pagetools' => 'Puslapių priemonės',
'specialpages-group-wiki' => 'Wiki duomenys ir priemonės',
'specialpages-group-redirects' => 'Specialieji nukreipimo puslapiai',
'specialpages-group-spam' => 'Šlamšto valdymo priemonės',

# Special:BlankPage
'blankpage' => 'Tuščias puslapis',
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
'tags' => 'Leistinos keitimų žymės',
'tag-filter' => '[[Special:Tags|Žymų]] filtras:',
'tag-filter-submit' => 'Filtras',
'tags-title' => 'Žymos',
'tags-intro' => 'Šiame puslapyje yra žymų, kuriomis programinė įranga gali pažymėti keitimus, sąrašas bei jų reikšmės.',
'tags-tag' => 'Žymos pavadinimas',
'tags-display-header' => 'Išvaizda keitimų sąrašuose',
'tags-description-header' => 'Visas reikšmės aprašymas',
'tags-hitcount-header' => 'Pažymėti pakeitimai',
'tags-edit' => 'taisyti',
'tags-hitcount' => '$1 {{PLURAL:$1|pakeitimas|pakeitimai|pakeitimų}}',

# Special:ComparePages
'comparepages' => 'Palyginti puslapius',
'compare-selector' => 'Palyginti puslapio keitimus',
'compare-page1' => 'Puslapis 1',
'compare-page2' => 'Puslapis 2',
'compare-rev1' => 'Pirma versija',
'compare-rev2' => 'Antra versija',
'compare-submit' => 'Palyginti',
'compare-invalid-title' => 'Jūsų nurodytas pavadinimas neleistinas.',
'compare-title-not-exists' => 'Pavadinimas, kurį nurodėte, neegzistuoja.',
'compare-revision-not-exists' => 'Keitimas, kurį nurodėte, neegzistuoja.',

# Database error messages
'dberr-header' => 'Ši svetainė turi problemų.',
'dberr-problems' => 'Atsiprašome! Svetainei iškilo techninių problemų.',
'dberr-again' => 'Palaukite kelias minutes ir perkraukite puslapį.',
'dberr-info' => '(Nepavyksta pasiekti duomenų bazės serverio: $1)',
'dberr-usegoogle' => 'Šiuo metu jūs galite ieškoti per „Google“.',
'dberr-outofdate' => 'Mūsų turinio kopijos ten gali būti pasenusios.',
'dberr-cachederror' => 'Tai prašomo puslapio išsaugota kopija, ji gali būti pasenusi.',

# HTML forms
'htmlform-invalid-input' => 'Yra problemų su jūsų įvestimi',
'htmlform-select-badoption' => 'Reikšmė, kurią nurodėte, nėra leistina.',
'htmlform-int-invalid' => 'Reikšmė, kurią nurodėte, nėra sveikasis skaičius.',
'htmlform-float-invalid' => 'Nurodyta reikšmė nėra skaičius.',
'htmlform-int-toolow' => 'Reikšmė, kurią nurodėte, yra mažesnė nei mažiausia leistina $1',
'htmlform-int-toohigh' => 'Reikšmė, kurią nurodėte, yra didesnė nei didžiausia leistina $1',
'htmlform-required' => 'Ši vertė būtina',
'htmlform-submit' => 'Siųsti',
'htmlform-reset' => 'Atšaukti pakeitimus',
'htmlform-selectorother-other' => 'Kita',
'htmlform-no' => 'Ne',
'htmlform-yes' => 'Taip',
'htmlform-chosen-placeholder' => 'Pasirinkite parinktį',

# SQLite database support
'sqlite-has-fts' => '$1 su visatekstės paieškos palaikymu',
'sqlite-no-fts' => '$1 be visatekstės paieškos palaikymo',

# New logging system
'logentry-delete-delete' => '$1 {{GENDER:$2|ištrynė}} puslapį $3',
'logentry-delete-restore' => '$1 {{GENDER:$2|atkūrė}} puslapį $3',
'logentry-delete-event' => '$1 pakeistas  matomumas {{PLURAL:$5|žurnalo įvykio|$5 žurnalo įvykių}} tarp $3: $4',
'logentry-delete-revision' => '$1 pakeitė puslapio „$3“ {{PLURAL:$5|versijos|$5 versijų}} matomumą: $4',
'logentry-delete-event-legacy' => '$1 pakeistas matomumą žurnalo renginiams tarp $3',
'logentry-delete-revision-legacy' => '$1 pakeistas matomumas pažiūrų puslapio $3',
'logentry-suppress-delete' => '$1 nuslopino puslapį $3',
'logentry-suppress-event' => '$1 slaptai pakeistas matomumas {{PLURAL:$5|žurnalo įvykio|$5 žurnalo įvykiu}} tarp $3: $4',
'logentry-suppress-revision' => '$1 slaptai pakeistas matomumas {{PLURAL:$5|peržiūros|$5 peržiūrų}} puslapyje $3: $4',
'logentry-suppress-event-legacy' => '$1 slaptai pakeistas matomumas žurnalo įvykių tarp $3',
'logentry-suppress-revision-legacy' => '$1 slaptai pakeistas matomumas peržiūrų puslapyje $3',
'revdelete-content-hid' => 'turinys paslėptas',
'revdelete-summary-hid' => 'paslėptas keitimo komentaras',
'revdelete-uname-hid' => 'paslėptas naudotojo vardas',
'revdelete-content-unhid' => 'turinys paviešintas',
'revdelete-summary-unhid' => 'keitimo komentaras paviešintas',
'revdelete-uname-unhid' => 'naudotojo vardas paviešintas',
'revdelete-restricted' => 'uždėti apribojimai administratoriams',
'revdelete-unrestricted' => 'pašalinti apribojimai administratoriams',
'logentry-move-move' => '$1 pervadino puslapį $3 į $4',
'logentry-move-move-noredirect' => '$1 pervadino puslapį $3 į $4, nepalikdamas nukreipimo',
'logentry-move-move_redir' => '$1 pervadino puslapį iš $3 į $4, vietoje buvusio nukreipimo',
'logentry-move-move_redir-noredirect' => '$1 pervadino puslapį iš $3 į $4, vietoje buvusio nukreipimo, bet nesukurdamas naujo',
'logentry-patrol-patrol' => '$1 pažymėjo peržiūrą $4 puslapio $3 patruliuojama',
'logentry-patrol-patrol-auto' => '$1 automatiškai pažymėjo peržiūrą $4 puslapio $3 patruliuojama',
'logentry-newusers-newusers' => '$1 sukūrė naudotojo paskyrą',
'logentry-newusers-create' => '$1 sukūrė naudotojo paskyrą',
'logentry-newusers-create2' => '$1 sukūrė naudotojo paskyrą $3',
'logentry-newusers-byemail' => 'Naudotojas $1 sukūrė paskyrą $3, slaptažodis išsiųstas E-paštu.',
'logentry-newusers-autocreate' => 'Paskyra $1 buvo sukurta automatiškai',
'logentry-rights-rights' => '$1 pakeista narystė grupėje $3 iš $4 į $5',
'logentry-rights-rights-legacy' => '$1 {{GENDER:$2|pakeista}} narystė grupėje $3',
'logentry-rights-autopromote' => '$1 buvo automatiškai {{GENDER:$2|pervestas}} iš $4 į $5',
'rightsnone' => '(jokių)',

# Feedback
'feedback-bugornote' => 'Jei jūs esate pasirengę aprašyti techninę problemą išsamiau, [$1 praneškite apie programinę klaidą].
Kitu atveju, galite naudotis žemiau esančia paprastesne forma. Jūsų komentaras bus įtrauktas į puslapį „[$3 $2]“, kartu su jūsų naudotojo vardu ir jūsų naudojama naršykle.',
'feedback-subject' => 'Tema:',
'feedback-message' => 'Pranešimas:',
'feedback-cancel' => 'Atšaukti',
'feedback-submit' => 'Siųsti Atsiliepimą',
'feedback-adding' => 'Pridedamas atsiliepimas į puslapį ...',
'feedback-error1' => 'Klaida: Neatpažįstamas rezultatas iš API',
'feedback-error2' => 'Klaida: Redagavimas nepavyko',
'feedback-error3' => 'Klaida: Jokio atsakymo iš API',
'feedback-thanks' => 'Ačiū! Jūsų atsiliepimas buvo užregistruotas puslapyje „[$2 $1]“.',
'feedback-close' => 'Atlikta',
'feedback-bugcheck' => 'Puiku! Tiesiog patikrinkite, ar tai ne viena [$1 jau žinomų klaidų].',
'feedback-bugnew' => 'Patikrinau. Pranešti apie naują klaidą',

# Search suggestions
'searchsuggest-search' => 'Ieškoti',
'searchsuggest-containing' => 'turintys',

# API errors
'api-error-badaccess-groups' => 'Jums neleidžiama įkelti failus į šią wiki.',
'api-error-badtoken' => 'Vidinė klaida: blogai atpažinimo ženklas.',
'api-error-copyuploaddisabled' => 'Siuntimas pagal URL yra išjungtas šiame serveryje.',
'api-error-duplicate' => 'Jau {{PLURAL:$1|yra [$2 kitas failas]|yra [$2 kiti failai]}} puslapyje su tuo pačiu turiniu..',
'api-error-duplicate-archive' => 'Jau {{PLURAL:$1|buvo [$2 kitas failas]|buvo [$2 kitų failų]}} puslapyje su tuo pačiu turiniu, bet {{PLURAL:$1|buvo|buvo}} ištrinti.',
'api-error-duplicate-archive-popup-title' => 'Dubliuoti {{PLURAL:$1|failą kuris buvo|failus kurie buvo}} jau buvo ištrinti.',
'api-error-duplicate-popup-title' => 'Dubliuoti  {{PLURAL:$1|failą|failus}}',
'api-error-empty-file' => 'Pateikta failas buvo tuščias.',
'api-error-emptypage' => 'Kurti naujus, tuščius puslapius neleidžiama.',
'api-error-fetchfileerror' => 'Vidinė klaida: Kažkas nutiko gaunant failą.',
'api-error-fileexists-forbidden' => 'Failas, kurio pavadinimas "$1" jau egzistuoja, ir negali būti perrašytas.',
'api-error-fileexists-shared-forbidden' => 'Failas, kurio pavadinimas "$1" jau egzistuoja bendro naudojimo failų saugykloje, ir negali būti perrašytas.',
'api-error-file-too-large' => 'Failą, kurį pateikėte buvo per didelis.',
'api-error-filename-tooshort' => 'Failo vardas yra per trumpas.',
'api-error-filetype-banned' => 'Šis failų tipas yra uždraustas.',
'api-error-filetype-banned-type' => '$1 nėra {{PLURAL:$4|leistinas failo tipas|leistini failo tipai}}. {{PLURAL:$3|Leistinas failų tipas|Leistini failų tipai}} yra $2.',
'api-error-filetype-missing' => 'Failas neturi galūnės.',
'api-error-hookaborted' => 'Pakeitimą, kurį bandėte atlikti, nutraukė priedas.',
'api-error-http' => 'Vidinė klaida: nepavyko prisijungti prie serverio.',
'api-error-illegal-filename' => 'Failo vardas neleidžiamas.',
'api-error-internal-error' => 'Vidinė klaida: Kažkas ne taip su jūsų įkėlimo apdorojimu wiki.',
'api-error-invalid-file-key' => 'Vidinė klaida: failas nerastas saugykloje.',
'api-error-missingparam' => 'Vidinė klaida: Trūksta reikalingų parametrų.',
'api-error-missingresult' => 'Vidinė klaida: nepavyko nustatyti, ar pavyko nukopijuoti.',
'api-error-mustbeloggedin' => 'Jūs turite būti prisijungęs kad galėtumėte įkelti failus.',
'api-error-mustbeposted' => 'Vidinė klaida: prašymas reikalauja HTTP POST.',
'api-error-noimageinfo' => 'Įkelti pavyko, bet serveris nepateikė mums jokios informacijos apie failą.',
'api-error-nomodule' => 'Vidinė klaida: nėra nustatytas įkėlimų modulis.',
'api-error-ok-but-empty' => 'Vidinė klaida: nėra atsakymo iš serverio.',
'api-error-overwrite' => 'Perrašymas esamą failą neleidžiamas.',
'api-error-stashfailed' => 'Vidinė klaida: serveriui nepavyko išsaugoti laikinąjį failą.',
'api-error-publishfailed' => 'Vidinė klaida: serveriui nepavyko paskelbti laikino failo.',
'api-error-timeout' => 'Serveris neatsakė per numatytą laiką.',
'api-error-unclassified' => 'Įvyko nežinoma klaida',
'api-error-unknown-code' => 'Nežinoma klaida: " $1 "',
'api-error-unknown-error' => 'Vidinė klaida: kažkas nutiko bandant įkelti failą.',
'api-error-unknown-warning' => 'Nežinomas įspėjimas: $1',
'api-error-unknownerror' => 'Nežinoma klaida: "$1"',
'api-error-uploaddisabled' => 'Įkėlimas išjungtas šioje wiki.',
'api-error-verification-error' => 'Šis failas gali būti sugadintas arba turi neteisingą papildinį.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekundė|sekundės|sekundžių}}',
'duration-minutes' => '$1 {{PLURAL:$1|minutė|minutės|minučių}}',
'duration-hours' => '$1 {{PLURAL:$1|valanda|valandos|valandų}}',
'duration-days' => '$1 {{PLURAL:$1|diena|dienos|dienų}}',
'duration-weeks' => '$1 {{PLURAL:$1|savaitė|savaitės|savaičių}}',
'duration-years' => '$1 {{PLURAL:$1|metai|metai|metų}}',
'duration-decades' => '$1 {{PLURAL:$1|dešimtmetis|dešimtmečiai|dešimtmečių}}',
'duration-centuries' => '$1 {{PLURAL:$1|amžius|amžiai|amžių}}',
'duration-millennia' => '$1 {{PLURAL:$1|tūkstantmetis|tūkstantmečiai|tūkstantmečių}}',

);
