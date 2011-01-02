<?php
/** Latgalian (Latgaļu)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dark Eagle
 * @author Jureits
 * @author Reedy
 */

$fallback = 'lv';

$messages = array(
'underline-always' => 'Vysod',
'underline-never'  => 'Nikod',

# Dates
'sunday'        => 'Svātdīne',
'monday'        => 'Pyrmūdīne',
'tuesday'       => 'Ūtardīne',
'wednesday'     => 'Trešdīne',
'thursday'      => 'Catūrtdīne',
'friday'        => 'Pīktdīne',
'saturday'      => 'Sastdīne',
'january'       => 'Janvars / Jaunagods mieness',
'february'      => 'Febraļs / Svacainis mieness',
'march'         => 'Marts / Pavasara mieness',
'april'         => 'Apreļs / Sulu mieness',
'may_long'      => 'Majs / Lopu mieness',
'june'          => 'Juņs / Vosorys mieness',
'july'          => 'Juļs / Sīna mieness',
'august'        => 'Augusts / Labeibys mieness',
'september'     => 'Seņtebrs / Rudiņa mieness',
'october'       => 'Oktobrs / Leita mieness',
'november'      => 'Nojabrs / Solnys mieness',
'december'      => 'Dekabrs / Zīmys mieness',
'january-gen'   => 'Janvara / Jaunagods mieneša',
'february-gen'  => 'Febraļa / Svacainis mieneša',
'march-gen'     => 'Marta / Pavasara mieneša',
'april-gen'     => 'Apreļa / Sulu mieneša',
'may-gen'       => 'Maja / Lopu mieneša',
'june-gen'      => 'Juņa / Vosorys mieneša',
'july-gen'      => 'Juļa / Sīna mieneša',
'august-gen'    => 'Augusta / Labeibys mieneša',
'september-gen' => 'Seņtebra / Rudiņa mieneša',
'october-gen'   => 'Oktoba / Leita mieneša',
'november-gen'  => 'Nojabra / Solnys mieneša',
'december-gen'  => 'Dekabra / Zīmys mieneša',
'jan'           => 'janv.',
'feb'           => 'febr.',
'mar'           => 'martā',
'apr'           => 'apr.',
'may'           => 'junī',
'jun'           => 'julī',
'jul'           => 'julī',
'aug'           => 'aug.',
'sep'           => 'seņt.',
'oct'           => 'okt.',
'nov'           => 'noj.',
'dec'           => 'dek.',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kategoreja|Kategorejis}}',
'category_header'        => 'Puslopys kategorejā "$1"',
'subcategories'          => 'Zamkategorejis',
'category-media-header'  => 'Faili kategorejā "$1"',
'hidden-categories'      => '{{PLURAL:$1|Nūglobuota kategoreja|Nūglobuotys kategorejis}}',
'category-subcat-count'  => '{{PLURAL:$2|Itymā kategorejā ir vīn dūtuo zamkategoreja.|{{PLURAL:$1|Paruodeita $1 zamkategoreja|Paruodeitys $1 zamkategorejis}} nu $2.}}',
'category-article-count' => '{{PLURAL:$2|Itymā kategorejā ir vīn dūtuo puslopa.|{{PLURAL:$1|Paruodeita $1 puslopa|Paruodeitys $1 puslopys}} nu $2.}}',
'listingcontinuesabbrev' => '(tuoļuojums)',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'         => 'Ap',
'article'       => 'Rakstīņs',
'newwindow'     => '(atdareišona jaunuo puslopā)',
'cancel'        => 'Atsaukt',
'moredotdotdot' => 'Vaira...',
'mypage'        => 'Muna puslopa',
'mytalk'        => 'Muna sprīža',
'navigation'    => 'Navigaceja',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Mekliešona',
'qbedit'         => 'Pataiseit',
'qbpageoptions'  => 'Ita puslopa',
'qbmyoptions'    => 'Munys puslopys',
'qbspecialpages' => 'Specialuos puslopys',

# Vector skin
'vector-action-addsection' => 'Dalikt padaļu',
'vector-action-delete'     => 'Iztreit',
'vector-action-protect'    => 'Apsorguot',
'vector-view-edit'         => 'Pataiseit',
'variants'                 => 'Varianti',

'errorpagetitle'   => 'Klaida',
'returnto'         => 'Grīztīs da puslopys $1.',
'tagline'          => 'Nu {{SITENAME}}',
'help'             => 'Paleigs',
'search'           => 'Maklātivs',
'searchbutton'     => 'Meklēt',
'go'               => 'Ruodeit',
'searcharticle'    => 'Īt',
'history'          => 'Puslopys viesture',
'history_short'    => 'Viesture',
'info_short'       => 'Informaceja',
'printableversion' => 'Verseja drukavuošonai',
'permalink'        => 'Nūtaleja nūruode',
'print'            => 'Drukavuot',
'edit'             => 'Pataiseit',
'create'           => 'Sataiseit',
'editthispage'     => 'Pataiseit itū puslopu',
'create-this-page' => 'Sataiseit itū puslopu',
'delete'           => 'Iztreit',
'deletethispage'   => 'Iztreit itū puslopu',
'protect'          => 'Apsorguot',
'protect_change'   => 'puormeit',
'protectthispage'  => 'Apsorguot itū puslopu',
'newpage'          => 'Jauna puslopa',
'talkpage'         => 'Apmīgt itū puslopu',
'talkpagelinktext' => 'sprīža',
'specialpage'      => 'Specialuo puslopa',
'personaltools'    => 'Muni reiki',
'postcomment'      => 'Dalikt komentaru',
'articlepage'      => 'Apsavērt rakstīņu',
'talk'             => 'Sprīža',
'views'            => 'Vierīņi',
'toolbox'          => 'Reiki',
'otherlanguages'   => 'Cytuos volūduos',
'redirectedfrom'   => '(Puoradresēts nu $1)',
'redirectpagesub'  => 'Puoradresiešonys puslopa',
'lastmodifiedat'   => 'Itymā lopā pādejuos izmainis izdareitys $2, $1.',
'jumpto'           => 'Puorlēkt da:',
'jumptonavigation' => 'navigaceja',
'jumptosearch'     => 'meklēt',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Ap {{grammar:akuzativs|{{SITENAME}}}}',
'aboutpage'            => 'Project:Ap',
'copyright'            => 'Saturs ir pīejams saisteibā ar $1.',
'copyrightpage'        => '{{ns:project}}:Autortīseibys',
'disclaimers'          => 'Daīšmu nūstatejumi',
'disclaimerpage'       => 'Project:Dasaīšonu nūstateišona',
'edithelp'             => 'Paleigs',
'edithelppage'         => 'Help:Pataiseišona',
'helppage'             => 'Help:Turīņs',
'mainpage'             => 'Suoku puslopa',
'mainpage-description' => 'Suoku puslopa',
'portal-url'           => 'Project:Dūmu meits',
'privacy'              => 'Privatuma politika',
'privacypage'          => 'Project:Privatuma politika',

'badaccess' => 'Atļuovis klaida',

'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Atdabuot nu "$1"',
'youhavenewmessages'      => 'Tu dabuoji $1 ($2).',
'newmessageslink'         => 'jaunus viestejumus',
'newmessagesdifflink'     => 'pādejā pataise',
'editsection'             => 'pataiseit',
'editsection-brackets'    => '[$1]',
'editold'                 => 'pataiseit',
'editlink'                => 'pataiseit',
'viewsourcelink'          => 'Apsavērt suokūtnejū kodu',
'editsectionhint'         => 'Pataiseit padaļu: $1',
'toc'                     => 'Puslopu ruodeklis',
'showtoc'                 => 'paruodeit',
'hidetoc'                 => 'nūglobuot',
'site-rss-feed'           => '$1 RSS pādi',
'site-atom-feed'          => '$1 Atoma pādi',
'page-rss-feed'           => '"$1" RSS pādi',
'page-atom-feed'          => '"$1" Atom pādi',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (itaidys puslopys navā)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Rakstīņs',
'nstab-user'     => 'Lītuotuoja puslopa',
'nstab-special'  => 'Specialuo puslopa',
'nstab-project'  => 'Projekta puslopa',
'nstab-image'    => 'Fails',
'nstab-template' => 'Taiss',
'nstab-category' => 'Kategoreja',

# General errors
'error'              => 'Klaida',
'missing-article'    => 'Teksts lopai ar nūsaukumu "$1" $2 datu bazā nav atrūnams.

Tys parostai nūtiek vacu saišu gadejumā: pīprosūt izmainis voi viesturi lopai, kas ir dzāsta.

Ka lopai ir juobyut, tod, īspiejams, ir klaida programā.
Par to var ziņuot [[Special:ListUsers/sysop|kaidam administratoram]], nūruodūt ari URL.',
'missingarticle-rev' => '(Puorsavieršona#: $1)',
'badtitletext'       => 'Pīpraseituo lopa ir ar klaidu, tukša voi napareizai saisteits dažaidu volūdu voi dažaidu wiki viersroksti. Tys var saturēt vīnu voi vairuokus simbolus, kū navar izmontuot viersrokstūs.',
'viewsource'         => 'Apsavērt kodu',

# Login and logout pages
'yourname'                => 'Slāgvuords:',
'yourpassword'            => 'Paroļs:',
'remembermypassword'      => 'Atguoduot muni  iz ituo datora (na vaira kai $1 {{PLURAL:$1|dīnu|dīnom|dīnom}})',
'login'                   => 'Dasaslāgt',
'nav-login-createaccount' => 'Dasaslāgt / sataiseit jaunu lītuotuoju',
'userlogin'               => 'Dasaslāgt / sataiseit jaunu lītuotuoju',
'userloginnocreate'       => 'Dasaslāgt',
'logout'                  => 'Atsaslāgt',
'userlogout'              => 'Atsaslāgt',
'nologinlink'             => 'Registrētīs',
'createaccount'           => 'Sataiseit jaunu lītuotuoju',
'gotaccountlink'          => 'Dasaslāgt',
'mailmypassword'          => 'Atsyuteit maņ jaunu paroli',
'loginlanguagelabel'      => 'Volūda: $1',

# Password reset dialog
'resetpass_text' => '<!-- Dalikt tekstu ite -->',

# Edit page toolbar
'bold_sample'     => 'Pamalnais roksts',
'bold_tip'        => 'Pamalnais roksts',
'italic_sample'   => 'Sleipais roksts',
'italic_tip'      => 'Sleipais roksts',
'link_sample'     => 'Saitys pasauka',
'link_tip'        => 'Vydyskuo saita',
'extlink_sample'  => 'http://www.example.com saitys pasauka',
'extlink_tip'     => 'Uorejuo saite (naaizmierst suokumā dalikt "http://")',
'headline_sample' => 'Viersroksta teksts',
'headline_tip'    => '2. leimiņa viersroksts',
'math_sample'     => 'Formulu īrokst ite',
'math_tip'        => 'Matematiska formula (LaTeX)',
'nowiki_sample'   => 'Ite rokst naformatietu tekstu',
'nowiki_tip'      => 'Najimt vārā wiki formatiejumu',
'image_sample'    => 'Paraugs.jpg',
'image_tip'       => 'Paguļdeits fails',
'media_sample'    => 'Paraugs.ogg',
'media_tip'       => 'Saite iz multimediju failu',
'sig_tip'         => 'Tovs paroksts ar laika atzeimi',
'hr_tip'          => 'Horizontaluo lineja (nalīc bez vajadzeibys)',

# Edit pages
'summary'                          => 'Kūpsavylkums',
'subject'                          => 'Tema/viersroksts:',
'minoredit'                        => 'nanūzeimeigs lobuojums',
'watchthis'                        => 'Puorraudzeit itū lopu',
'savearticle'                      => 'Izglobuot puslopu',
'preview'                          => 'Apsavērt',
'showpreview'                      => 'Apsavērt',
'showdiff'                         => 'Paruodeit izmainis',
'anoneditwarning'                  => "'''Uzmaneibu:''' tu naesi īguojs kai lītuotuojs. Lopys viesturē tiks īraksteita tovs IP adress.",
'summary-preview'                  => 'Apsavērt kūpsavylkumu',
'newarticle'                       => '(Jauns roksts)',
'newarticletext'                   => "Tu ite tyki caur saitis nu, pagaidam vēļ nauzraksteitys, lopys.
Kab radeitu lopu, suoc raksteit teksta lūgā apaškā (par teksta formatiešonu i seikuokai informaceja verīs [[{{MediaWiki:Helppage}}|paleigu]]).
Ka Tu ite tyki deļ klaidys, vīnkuorši daspīd '''back''' pūgu puorlyukprogramā.",
'noarticletext'                    => 'Itymā lopā nav nikaida teksta, tu vari [[Special:Search/{{PAGENAME}}|meklēt cytuos lopuos piec ituos lopys nūsaukuma]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} meklēt saisteitūs registru īrokstūs] voi ari [{{fullurl:{{FULLPAGENAME}}|action=edit}} suokt taiseit itū lopu]</span>.',
'previewnote'                      => "'''Itei ir tikai apsavieršona!'''
Jiusu izmanis vēļ nav saglobuotys!",
'editing'                          => 'Pataiseit $1',
'editingsection'                   => 'Pataiseit $1 (padaļa)',
'editingcomment'                   => 'Pataiseit $1 (jauna padaļa)',
'copyrightwarning'                 => "Lyudzu, atguodoj, ka vyss īguļdejums, kas dareits {{grammar:lokatīvs|{{SITENAME}}}}, ir skaitams par publiskuotu saskaņā ar \$2 (vairuok info verīs \$1).
Ka nagribi, kab Tevi raksteitū kaids redigej i izplota tuoluok, tod, lyudzu, nalīc tū ite!<br />

Izavieljūt \"Saglobuot lopu\", Tu aplīcynoj, ka itū rokstu esi rakstejs voi papyldynovs pats voi izmantovs informaceju nu dorba, kuru naaizsorgoj autortīseibys, voi tamleidzeiga breivi lītojama resursa.
'''BEZ ATĻUOVIS NADAVĪNOJ DORBU, KURU AIZSORGOJ AUTORTĪSEIBYS!'''",
'templatesused'                    => 'Itymā puslopā {{PLURAL:$1|izlītuots taiss|izlītuoti taisi}}:',
'templatesusedpreview'             => 'Itymā apsavierīņā {{PLURAL:$1|izlītuots taiss|izlītuoti taisi}}:',
'template-protected'               => '(aizsorguota)',
'template-semiprotected'           => '(daleji aizsorguota)',
'hiddencategories'                 => 'Itei lopa ir {{PLURAL:$1|1 nūglobuotajā kategorejā|$1 nuoglobuotajuos kategorejuos}}:',
'permissionserrorstext-withaction' => 'Tev nav atļuovis iz $2, deļ {{PLURAL:$1|itaida pamata|itaidu pamatu}}:',

# History pages
'viewpagelogs'           => 'Apsavērt ar itū lopu saisteitūs registru īrokstus',
'currentrev-asof'        => 'Niulinejuo verseja, $1',
'revisionasof'           => 'Verseja, kas saglobuota $1',
'previousrevision'       => '←Vacuoka verseja',
'nextrevision'           => 'Jaunuokuo verseja →',
'currentrevisionlink'    => 'apsavērt tagadejū verseju',
'cur'                    => 'ar tagadejū',
'last'                   => 'ar īprīškejū',
'histlegend'             => 'Atškireibu izvēle: atzeimej vajadzeigū verseju opoluos pūgys i spīd "Saleidzynuot ituos versejis".<br />
Apzeimiejumi:
n = nasvareigs lobuojums.',
'history-fieldset-title' => 'Meklēt viesturē',
'history-show-deleted'   => 'Tik iztreitūs',
'histfirst'              => 'Vacuokuos',
'histlast'               => 'Jaunuokuos',

# Revision deletion
'rev-delundel'   => 'ruodeit/nūglobuot',
'revdel-restore' => 'maineit radzameibu',

# Merge log
'revertmerge' => 'Atceļt apvīnuošonu',

# Diffs
'history-title'           => '"$1" verseju viesture',
'difference'              => '(Versiju saleidzynuojums)',
'lineno'                  => '$1 aiļa',
'compareselectedversions' => 'Saleidzynuot ituos versejis',
'editundo'                => 'atsaukt',

# Search results
'searchresults'             => 'Mekliešonys rezultati',
'searchresults-title'       => 'Mekliešonys rezultati "$1"',
'searchresulttext'          => 'Lai dabuotu vaira informacejis par mekliešuonu {{grammar:akuzativs|{{SITENAME}}}}, vērtīs [[{{MediaWiki:Helppage}}|{{grammar:genitivs|{{SITENAME}}}} meklēšana]].',
'searchsubtitle'            => 'Pīprasejums: \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|vysys lopys, kas suoknās ar "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|vysys lopys, kuramuos ir saite iz "$1"]])',
'searchsubtitleinvalid'     => 'Pīprasejums: $1',
'notitlematches'            => 'Nav rezuļtata meklejūt lopys viersrokstā',
'notextmatches'             => 'Nav rezuļtatu meklejūt lopys tekstā',
'prevn'                     => 'īprīškejuos {{PLURAL:$1|$1}}',
'nextn'                     => 'nuokamuos {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Apsavērt ($1 {{int:pipe-separator}} $2) ($3 vīnā lopā).',
'searchprofile-articles'    => 'Rakstīņuos',
'search-result-size'        => '$1 ({{PLURAL:$2|$2 vuords|$2 vuordi|$2 vuordi}})',
'search-redirect'           => '(puoradresiešona nu $1)',
'search-section'            => '(sadaļa $1)',
'search-suggest'            => 'Voi jius dūmovat: $1',
'search-interwiki-caption'  => 'Citi projekti',
'search-interwiki-default'  => 'Rezuļtati nu $1',
'search-interwiki-more'     => '(vaira)',
'search-mwsuggest-enabled'  => 'Ar īsacejumim',
'search-mwsuggest-disabled' => 'Bez īsacejumim',
'searchall'                 => 'vysi',
'nonefound'                 => "'''Pīzeime:''' bīži vin mekliešona ir naveiksmeiga, meklejūt plaši izplateitus vuordus, pīvadumam, \"kai\" voi \"ir\", deļ tam ka tī nateik īkļauti mekliešonys datu bazā, voi ari meklejūt vairuok par vīnu vuordu (deļ tam ka rezuļtatūs pasaruodeis tikai lopys, kuramuos ir visi meklietī vuordi). Vēļ, piec nūklusiejuma, puormeklej tikai dažys ''namespaces''. Lai meklētu vysuos, mekliešonys pīprasejumam prīškā juolīkn ''all:'', voi ari analogā veidā juonūruoda puormekliejamuo ''namespace''.",
'powersearch'               => 'Smolkuo mekliešona',
'powersearch-legend'        => 'Smolkuo mekliešona',
'powersearch-ns'            => 'Meklēt itamuos lopu grupuos:',
'powersearch-redir'         => 'Ruodeit puoradresacejis',
'powersearch-field'         => 'Meklēt',
'powersearch-toggleall'     => 'Vysi',
'powersearch-togglenone'    => 'Nikas',

# Preferences page
'preferences'    => 'Īstatejumi',
'mypreferences'  => 'Muni īstatejumi',
'saveprefs'      => 'Izglobuot',
'uid'            => 'Lītuotuoja ID:',
'yourlanguage'   => 'Volūda:',
'yourgender'     => 'Kuorta:',
'gender-unknown' => 'Nava nūruodeits',
'gender-male'    => 'Veirīts',
'gender-female'  => 'Sīvīts',
'prefs-info'     => 'Pamatinformaceja',

# Groups
'group-user'  => 'Lītuotuoji',
'group-sysop' => 'Administratori',

'group-user-member'  => 'Lītuotuojs',
'group-sysop-member' => 'Administrators',

'grouppage-user'  => '{{ns:project}}:Lītuotuoji',
'grouppage-sysop' => '{{ns:project}}:Administratori',

# Rights
'right-upload'        => 'Īsyuteit failus',
'right-upload_by_url' => 'Īsyuteit failu nu URL adresa',

# User rights log
'rightslog'  => 'Lītuotuoju tīseibu registrs',
'rightsnone' => '(navā)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'       => 'skaiteit itū puslopu',
'action-edit'       => 'pataiseit itū puslopu',
'action-createpage' => 'sataiseit puslopys',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|puormeja|puormejis}}',
'recentchanges'                  => 'Nasenejis puormejis',
'recentchanges-legend'           => 'Pādejūs izmaiņu īspiejis',
'recentchanges-feed-description' => 'Redzit jaunuokuos wiki izmainis ar itīm pādim.',
'rcnote'                         => 'Tagad ir {{PLURAL:$1|radzama pādejuo <strong>$1</strong> izmaiņa, kas izdareita|redzamys pādejuos <strong>$1</strong> izmainis, kas izdareitys}} {{PLURAL:$2|pādejā|pādejuos}} <strong>$2</strong> {{PLURAL:$2|dīnā|dīnuos}} (da $4, $5).',
'rclistfrom'                     => 'Paruodeit jaunys izmainis nu $1',
'rcshowhideminor'                => '$1 nasvareigūs',
'rcshowhidebots'                 => '$1 robotprogramys',
'rcshowhideliu'                  => '$1 dasaslāgtu lītuotuoju',
'rcshowhideanons'                => '$1 anonimūs',
'rcshowhidemine'                 => '$1 munys puormejis',
'rclinks'                        => 'Paruodeit pādejuos $1 izmainis pādejūs $2 dīnu laikā.<br />$3',
'diff'                           => 'izmainis',
'hist'                           => 'viesture',
'hide'                           => 'nūglobuot',
'show'                           => 'Ruodeit',
'minoreditletter'                => 'n',
'newpageletter'                  => 'J',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Ruodeit informaceju (vajadzeigs JavaScript)',
'rc-enhanced-hide'               => 'Nūglobuot detalis',

# Recent changes linked
'recentchangeslinked'         => 'Sasītuos puormejis',
'recentchangeslinked-title'   => 'Izmainis, kas saisteitys ar "$1"',
'recentchangeslinked-summary' => "Ite ir naseņ izdareituos izmainis lopuos, iz kurom ir saitis nu paruodeituos lopys (voi paruodeitajā kategorejā īlyktuos lopys).
Lopys, kas ir tovā [[Special:Watchlist|puorraugamūs rokstu sarokstā]] ir '''rasnys'''.",
'recentchangeslinked-page'    => 'Puslopys pasauka:',
'recentchangeslinked-to'      => 'Ruodeit izmainis lopuos, kur ir saitis iz itū lopu (a na lopuos, iz kurom ir saitis nu ituos lopys)',

# Upload
'upload'        => 'Īsyuteit failu',
'uploadbtn'     => 'Īsyuteit failu',
'uploadlogpage' => 'Davīnuotūs failu registrs',
'uploadedfiles' => 'Īsyuteiti faili',
'savefile'      => 'Izglobuot failu',
'uploadedimage' => 'davīnuots "[[$1]]"',

'license'        => 'Liceņceja:',
'license-header' => 'Liceņceja',

# Special:ListFiles
'imgfile'               => 'fails',
'listfiles'             => 'Failu saroksts',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Pasauka',
'listfiles_user'        => 'Lītuotuojs',
'listfiles_size'        => 'Mārs',
'listfiles_description' => 'Aproksts',
'listfiles_count'       => 'Versejis',

# File description page
'file-anchor-link'          => 'Fails',
'filehist'                  => 'Faila viesture',
'filehist-help'             => 'Spīd iz datums/laiks kolonā īlyktuos saitis, kab apsavārtu, kai itais fails izavēre tūlaik.',
'filehist-current'          => 'tagadejais',
'filehist-datetime'         => 'Data/Laiks',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Atzeime ? par verseji nu $1',
'filehist-user'             => 'Lītuotuojs',
'filehist-dimensions'       => 'Izmāri',
'filehist-comment'          => 'Komentars',
'imagelinks'                => 'Failu saitis',
'linkstoimage'              => '{{PLURAL:$1|Itamā lopā ir saite|Itamuos $1 lopuos ir saite}} iz itū failu:',
'sharedupload'              => 'Itais fails ir saglobuots nu $1 i ir kūplītuojams cytūs projektūs.',
'uploadnewversion-linktext' => 'Saglobuot jaunu ituo faila verseju',
'shared-repo-from'          => 'nu $1',

# File reversion
'filerevert-comment' => 'Īmesle:',

# File deletion
'filedelete'        => 'Iztreit $1',
'filedelete-legend' => 'Iztreit failu',
'filedelete-submit' => 'Iztreit',

# Statistics
'statistics'          => 'Statistika',
'statistics-articles' => 'Rakstīņi',
'statistics-pages'    => 'Puslopys',
'statistics-files'    => 'Īsyuteiti faili',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|baits|baiti|baitu}}',
'nmembers'      => '$1 {{PLURAL:$1|lopa|lopys}}',
'prefixindex'   => 'Meklēt piec viersroksta pyrmajim burtim',
'newpages'      => 'Jaunys puslopys',
'move'          => 'Puorceļt',
'movethispage'  => 'Puorceļt itū puslopu',
'pager-newer-n' => '{{PLURAL:$1|jaunuoku 1|jaunuokuos $1}}',
'pager-older-n' => '{{PLURAL:$1|vacuoku 1|vacuokys $1}}',

# Book sources
'booksources'               => 'Gruomotu olūti',
'booksources-search-legend' => 'Meklēt gruomotu olūtus',
'booksources-go'            => 'Meklēt',

# Special:Log
'log' => 'Registri',

# Special:AllPages
'allpages'       => 'Vysys puslopys',
'alphaindexline' => 'nu $1 da $2',
'prevpage'       => 'Īprīškejuo lopa ($1)',
'allpagesfrom'   => 'Paruodeit puslopys, kurys aizsuokys ar:',
'allpagesto'     => 'Paruodeit lopys da:',
'allarticles'    => 'Vysys puslopys',
'allpagessubmit' => 'Īt',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'devīņs',

# Special:LinkSearch
'linksearch' => 'Uorejuos saitys',

# Special:Log/newusers
'newuserlogpage'          => 'Jaunūs lītuotuoju registrs',
'newuserlog-create-entry' => 'Registrāts lītuotuojvuords',

# Special:ListGroupRights
'listgrouprights-members'  => '(dalinīku saroksts)',
'listgrouprights-addgroup' => 'Dalikt {{PLURAL:$2|grupu|grupys}}: $1',

# E-mail user
'emailuser' => 'Syuteit e-postu itam lītuotuojam',

# Watchlist
'watchlist'         => 'Muns davēris saroksts',
'mywatchlist'       => 'Muns davēris saroksts',
'addedwatch'        => 'Davīnuots puorraugamū sarokstam.',
'addedwatchtext'    => "Lopa \"[[:\$1]]\" ir davīnuota [[Special:Watchlist|tevis puorraugamajom lopom]], kur tiks paruodeitys izmainis, kas izdareitys itymā lopā voi ituos lopys sarunu lopā, kai ari itei lopa tiks īzeimāta '''pusrasna''' [[Special:RecentChanges|pādejūs izmaiņu lopā]], lai itū byutu vīgluok pamaneit.

Ka vāluok puordūmuosi i nagribiesi vairs puorraudzeit itū lopu, spīd iz saitis '''napuorraudzeit''' reiku jūslā.",
'removedwatch'      => 'Lopa vairs nateik puorraudzeita',
'removedwatchtext'  => 'Lopa "[[:$1]]" ir izjimta nu tova [[Special:Watchlist|puorraugamūs lopu saroksta]].',
'watch'             => 'Puorraudzeit',
'watchthispage'     => 'Puorraudzeit itū lopu',
'unwatch'           => 'Vairs napuorraudzeit',
'watchlist-details' => '(Tu puorraug $1 {{PLURAL:$1|lopu|lopys}}, naskaitūt sarunu lopys.)',
'wlshowlast'        => 'Paruodeit izmainis pādejūs $1 stuņžu laikā voi $2 dīnu laikā, voi ari $3',
'watchlist-options' => 'Puorraugamū rokstu saroksta īspiejis',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Davīnoj puorraudzeišonai...',
'unwatching' => 'Atslādz puorraudzeišonu...',

# Delete
'deletepage'            => 'Iztreit puslopu',
'confirmdeletetext'     => 'Tu tagad nu datu bazys iztreisi lopu voi biļdi, kai ari tūs īprīškejuos versejis. Lyudzu, apstypryni, ka tu pa eistam tū gribi dareit, ka tu saprūt, ka tū dori i atbylstūši [[{{MediaWiki:Policy-url}}|nūsacejumim]].',
'actioncomplete'        => 'Darbeiba pabeigta',
'deletedtext'           => '"<nowiki>$1</nowiki>" beja iztreits.
Kab apsavērtu pādejuo iztreitū sarokstu, verīs $2.',
'deletedarticle'        => 'dzāsts "[[$1]]"',
'dellogpage'            => 'Iztreišonys registris',
'deletionlog'           => 'iztreišonys registru',
'deletecomment'         => 'Īmesle:',
'deleteotherreason'     => 'Cyts/papyldu pamats:',
'deletereasonotherlist' => 'Cyts pamats',

# Rollback
'rollbacklink' => 'nūgrīzt',

# Protect
'protectlogpage'              => 'Aizsorguošonys registrs',
'protectedarticle'            => 'Aizsorgova [[$1]]',
'modifiedarticleprotection'   => 'imaineja aizsardzeibys leimini "[[$1]]"',
'protectcomment'              => 'Īmesle:',
'protectexpiry'               => 'Izabeidz:',
'protect_expiry_invalid'      => 'Beigu termiņš ir nadereigs.',
'protect_expiry_old'          => 'Beigu termiņš jau paguojs.',
'protect-text'                => "Ite var apsavērt i izmaineit lopys '''<nowiki>$1</nowiki>''' aizsardzeibys leimini.",
'protect-locked-access'       => "Jiusu kontam nav atļuovis maineit lopys aizsardzeibys pakuopi.
Pašreizejī lopys '''$1''' īstatejumi ir:",
'protect-cascadeon'           => 'Itei lopa niu ir aizsorguota, deļ tam ka tei ir īlykta {{PLURAL:$1|itadā lopā|itaiduos lopuos}} (mainūt ituos lopys aizsardzeibys leimini tuos aizsardzeiba nabyus nūjimta):',
'protect-default'             => 'Atļaut visim lītuotuojim',
'protect-fallback'            => 'Vajadzeiga atļuove "$1"',
'protect-level-autoconfirmed' => 'Bloķēt jaunim i naregistrātim lītuotuojim',
'protect-level-sysop'         => 'Viņ administratorim',
'protect-summary-cascade'     => 'Aizsardzeibys kaskads',
'protect-expiring'            => 'da $1 (UTC)',
'protect-cascade'             => "Aizsorguot itymā lopā īkļautuos lopys (veidnis) ''(cascading protection)''",
'protect-cantedit'            => 'Tu navari izmaineit ituos lopys aizsardzeibys leimiņus, deļ tuo ka tur navari izmaineit itū lopu.',
'restriction-type'            => 'Atļuove:',
'restriction-level'           => 'Aizsardzeibys leimiņs:',

# Restrictions (nouns)
'restriction-move' => 'Puorceļt',

# Undelete
'undeletelink'     => 'apsavērt/atjaunynuot',
'undeletedarticle' => 'atjaunynoju "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Paleigpuslopa:',
'invert'         => 'Izavielēt pretejū',
'blanknamespace' => '(Pamatpuslopa)',

# Contributions
'contributions'       => 'Lītuotuoja devīņs',
'contributions-title' => 'Lītuotuoja $1 devīņs',
'mycontris'           => 'Muns devīņs',
'contribsub2'         => 'Lītuotuojs: $1 ($2)',
'uctop'               => '(pādejā pataise)',
'month'               => 'Nu mieneša (i vacuoki):',
'year'                => 'Nu goda (i vacuoki):',

'sp-contributions-newbies'  => 'Ruodeit jaunūs lituotuoju īguļdejumu',
'sp-contributions-blocklog' => 'Blokiešonys registrs',
'sp-contributions-search'   => 'Meklēt lītuotuoju izdareitūs lobuojumus',
'sp-contributions-username' => 'IP adress ci slāgvuords:',
'sp-contributions-submit'   => 'Meklēt',

# What links here
'whatlinkshere'            => 'Sasītuos nūruodis',
'whatlinkshere-title'      => 'Lopys, kuramuos ir saitis iz lopu $1',
'whatlinkshere-page'       => 'Puslopa:',
'linkshere'                => "Itamuos lopuos ir nūruodis iz lopu '''[[:$1]]''':",
'isredirect'               => 'puoradresiešonys puslopa',
'istemplate'               => 'izsaukts',
'isimage'                  => 'atvaiga saita',
'whatlinkshere-prev'       => '{{PLURAL:$1|īprīškejū|īprīškejūs $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nuokamū|nuokamūs $1}}',
'whatlinkshere-links'      => '← saitys',
'whatlinkshere-hideredirs' => '$1 puoradresiešonys',
'whatlinkshere-hidetrans'  => '$1 transclusions ?',
'whatlinkshere-hidelinks'  => '$1 saitys',
'whatlinkshere-filters'    => 'Filtrys',

# Block/unblock
'blockip'                  => 'Nūblokēt lītuotuoju',
'ipboptions'               => '2 stuņdis:2 hours,1 dīna:1 day,3 dīnys:3 days,1 nedeļa:1 week,2 nedelis:2 weeks,1 mieness:1 month,3 mieneši:3 months,6 mieneši:6 months,1 gods:1 year,iz nanūsokamu laiku:infinite',
'ipb-blocklist-contribs'   => '$1 devīņs',
'ipblocklist'              => 'Blokietais IP adress i lītuotuojvuords',
'blocklink'                => 'nūblokēt',
'unblocklink'              => 'atblokēt',
'change-blocklink'         => 'puormeit bloku',
'contribslink'             => 'devīņs',
'blocklogpage'             => 'Blokiejumu registris',
'blocklogentry'            => 'noblokieja [[$1]] iz $2 $3',
'unblocklogentry'          => 'atblokieja $1',
'block-log-flags-nocreate' => 'Kontu radeišonys atslāga',

# Move page
'movepagetext'     => "Itamā lopā tu vari puorsaukt voi puorlikt lopu, kūpā ar tuos izmaiņu hronologeju puorlīkūt tū iz cytu nūsaukumu.
Īprīškejuo lopa klius par lopu, kas puoradresēs iz jaunū lopu.
Ite var automatiskai izmaineit vysys puoradresacejis (redirektus) iz itū lopu (2. atzeime apakšā).
Saitis puorejuos lopuos iz īprīškejū lopu nabyus maineitys. Ka gribi namaineit puoradresacejis automatiskai, puorbaud i izloboj, napīļaunūt [[Special:DoubleRedirects|dubultu puoradresaceji]] voi [[Special:BrokenRedirects|puoradresaceji iz naasūšu lopu]].
Tev ir juopuorsalīcynoj, voi saitis vēļ vys īt iz tīni, kur tuos ir īdūmuotys.

Jem vārā, ka lopa '''nabyus''' puorvītuota, ka jau eksistej kaida cyta lopa ar itaidu nūsaukumu (izjemūt gadīņus, kod tei ir tukša voi kod tei ir puoradresacejis lopa, kai ari tod, ka tai nav izmaiņu hronologejis).
Tys zeimoj, ka tu vari puorlikt lopu atpakaļ, nu kurīnis tu jau nazkod esi tū puorlics, ka byusi pīlaids klaidu, bet tu navari puorraksteit jau asūšu lopu.

'''BREIDYNUOJUMS!'''
Popularom lopom tei var byut kruosa i nagaideita puormaiņa;
pyrma tuoluok īsšonys apdūmoj, voi tu saprūt, kū eistyn dori.",
'movepagetalktext' => "Saisteituo sarunu lopa, ka taida eksistej, tiks automatiski puorvītuota, '''izjemūt gadejumus, kod''':
*tu puorlīc lopu iz cytu paleiglopu,
*ar jaunū nūsaukumu jau eksistej sarunu lopa, voi ari
*atzeimiesi zamuok atrūnamū lauceņu.

Ka gribiesi, tod tev itei sarunu lopa byus juopuolīk voi juoapvīnoj pošam.",
'movearticle'      => 'Puorceļt puslopu',
'newtitle'         => 'Jauna pasauka:',
'move-watch'       => 'Puorraudzeit itū lopu',
'movepagebtn'      => 'Puorceļt puslopu',
'pagemovedsub'     => 'Puorvītuošona nūtykuse veiksmeigai',
'movepage-moved'   => '\'\'\'"$1" tyka puorvītuots iz "$2"\'\'\'',
'articleexists'    => 'Lopa ar itaidu nūsaukumu jau ir voi ari tovs nūsaukums nav dereigs. Lyudzu, izavielejit cytu nūsaukumu.',
'talkexists'       => "'''Itei lopa tyka puorvītuota veiksmeigai, bet tuos sarunu lopu navarieja puorvītuot, deļ tuo ka jaunuo nūsaukuma lopai jau ir diskuseju lopa. Lyudzu apvīnoj ituos sarunu lopys pats.'''",
'movedto'          => 'puorvītuota iz',
'movetalk'         => 'Puorceļt sasītū sprīžu.',
'1movedto2'        => '"[[$1]]" puorsauču par "[[$2]]"',
'1movedto2_redir'  => '[[$1]] tyka puorsauktys par [[$2]], lītojūt puoradresaceju',
'movelogpage'      => 'Puorvītuošonys registrs',
'movesubpage'      => '{{PLURAL:$1|Zampuslopa|Zampuslopys}}',
'movereason'       => 'Īmesle:',
'revertmove'       => 'atsaukt',

# Export
'export'            => 'Eksportēt lopu',
'export-addcattext' => 'Dalikt puslopys nu kategorejis',
'export-addcat'     => 'Dalikt',
'export-addns'      => 'Dalikt',
'export-download'   => 'Izglobuot kai failu',

# Namespace 8 related
'allmessagesname'           => 'Pasauka',
'allmessages-filter-submit' => 'Īt',

# Thumbnails
'thumbnail-more' => 'Palelynuot',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tova lopa',
'tooltip-pt-mytalk'               => 'Tova sprīža',
'tooltip-pt-preferences'          => 'Muni īstatejumi',
'tooltip-pt-watchlist'            => 'Lopys, kuru izmainis Tu puorraug',
'tooltip-pt-mycontris'            => 'Tevis padareitais',
'tooltip-pt-login'                => 'Vadynojam dasaslāgt, koč i tys navā aizstateigai',
'tooltip-pt-logout'               => 'Atsaslāgt',
'tooltip-ca-talk'                 => 'Sprīža ap itū rakstīņa puslopu',
'tooltip-ca-edit'                 => 'Jius varit izmaineit itū lopu. Lyudzam apsavērt, kai lopa izaver, pyrma tuos saglobuošnoys.',
'tooltip-ca-addsection'           => 'Suokt jaunu sadali',
'tooltip-ca-viewsource'           => 'Itei lopa ir aizsorguota. Tu vari apsavērt tuos suokuma kodu.',
'tooltip-ca-history'              => 'Ituos lopys īprīškejī varianti',
'tooltip-ca-protect'              => 'Apsorguot itū puslopu',
'tooltip-ca-delete'               => 'Iztreit itū puslopu',
'tooltip-ca-move'                 => 'Puorceļt itū puslopu',
'tooltip-ca-watch'                => 'Davīnuot itū lopu munam puorraugamajam sarokstam',
'tooltip-ca-unwatch'              => 'Izjimt itū lopu nu puorraugamū saroksta',
'tooltip-search'                  => 'Meklēt itymā projektā',
'tooltip-search-go'               => 'Īt da puslopu ar taidu pasauku, ka taida irā',
'tooltip-search-fulltext'         => 'Meklēt puslopys ar itū tekstu',
'tooltip-n-mainpage'              => 'Īt da suoku puslopys',
'tooltip-n-mainpage-description'  => 'Īt da suoku puslopys',
'tooltip-n-portal'                => 'Ap projektu, kū vareiba dareit, kur vareiba kū atrast',
'tooltip-n-currentevents'         => 'Papyldinformaceja par tagad aktualajom nūtikšonom',
'tooltip-n-recentchanges'         => 'Pošjaunuo puormeju saroksts',
'tooltip-n-randompage'            => 'Īceļt navuošu puslopu',
'tooltip-n-help'                  => 'Vīta, kur dazynuot',
'tooltip-t-whatlinkshere'         => 'Vysys wiki lopys, kuramuos ir saitis iz itēni',
'tooltip-t-recentchangeslinked'   => 'Izmainis, kas naseņ pataiseitys, kuramuos ir saitis iz itū lopu',
'tooltip-feed-rss'                => 'Ituos lopys RSS pādi',
'tooltip-feed-atom'               => 'Ituos lopys Atom pādi',
'tooltip-t-contributions'         => 'Apsavērt ituo lītuotuoja padareitūs dorbus.',
'tooltip-t-emailuser'             => 'Syuteit e-postu itam lītuotuojam',
'tooltip-t-upload'                => 'Īsyuteit atvaigus ci daudziviesteitivu failus',
'tooltip-t-specialpages'          => 'Specialū puslopu saroksts',
'tooltip-t-print'                 => 'Verseja drukavuošonai',
'tooltip-t-permalink'             => 'Stypruo saite iz itū lopys verseju',
'tooltip-ca-nstab-main'           => 'Apsavērt rakstīņu',
'tooltip-ca-nstab-user'           => 'Apsavērt lītuotuoja lopu',
'tooltip-ca-nstab-special'        => 'Itei irā specialuo puslopa, tu navari puormeit pošu puslopu.',
'tooltip-ca-nstab-project'        => 'Apsavērt projekta lopu',
'tooltip-ca-nstab-image'          => 'Apsavērt faila lopu',
'tooltip-ca-nstab-template'       => 'Apsavērt šablonu',
'tooltip-ca-nstab-category'       => 'Apsavērt kategorejis lopu',
'tooltip-minoredit'               => 'Atzeimēt itū kai nasvareigu lobuojumu',
'tooltip-save'                    => 'Izglobuot jiusu puormejis',
'tooltip-preview'                 => 'Apsaver izmainis! Lyudzam tū izdareit pyrma saglobuošonys!',
'tooltip-diff'                    => 'Paruodeit, kur tekstā esi kū pamainejs.',
'tooltip-compareselectedversions' => 'Apsavērt atškireibys itamuos lopu versejuos.',
'tooltip-watch'                   => 'Davīnuot itū lopu puorraugamajam sarokstam',
'tooltip-rollback'                => 'Apsaver iprīškejūs lobuojumus',
'tooltip-undo'                    => '"Atgrīzt" atceļ ituos izmainis i attaisa lobuošonys formu prīškskatejuma veidā.
Tys ļaun davīnuot pamatuojumu kūpsavylkumā.',

# Browsing diffs
'previousdiff' => '← Vacuoka verseja',
'nextdiff'     => 'Jaunuokuo verseja →',

# Media information
'file-info-size'       => '($1 × $2 pikseli, faila izmārs: $3, MIME tips: $4)',
'file-nohires'         => '<small>Augstuoka izškirtspieja nav pīejama.</small>',
'svg-long-desc'        => '(SVG fails, definātais lelums $1 × $2 pikseli, faila lelums: $3)',
'show-big-image'       => 'Pylnā lelumā',
'show-big-image-thumb' => '<small>Ituo pyrmsskota lelums: $1 × $2 pikseli</small>',

# Bad image list
'bad_image_list' => 'Formats ir taids: tikai saroksta elementi (ryndys, suocūt ar *), ir skaitams par failu. Pyrmuo saite iz ryndys ir saite uz nalobu failu. Sevkurys vāluokys saitis tymā pošā ryndā tīk skaiteitys par izjāmumim, t.i., lopom, kur fails var atsarast ryndys vydā.',

# Metadata
'metadata'          => 'Suokumolūts',
'metadata-help'     => 'Itais fails satur vaira informacejis, kuru vysa dreižuok ir davīnovs digitalais fotoaparats voi cyts aparats, kas itū failu radeja. Ka itais fails piec tam ir maineits, itī dati var byut nūvacovuši.',
'metadata-expand'   => 'Paruodeit papyldu detalis',
'metadata-collapse' => 'Nūglobuot papyldu detalis',
'metadata-fields'   => 'Itymā paziņuojumā asūši metadatu lauki byus radzami attāla lopā ari tod, kod metadatu tabula byus sateita.
Puorejī lauki, piec nūklusiejuma, byus nūglobuoti.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Izmaineit itū failu ar uoreju programu',
'edit-externally-help' => '(Verīs [http://www.mediawiki.org/wiki/Manual:External_editors instrukcijas] Mediawiki.org, kab dabuotu vaira informacejis).',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'vysys',
'namespacesall' => 'vysys',
'monthsall'     => 'vysi',

# Watchlist editing tools
'watchlisttools-view' => 'Apsavērt atbylstūšuos izmainis',
'watchlisttools-edit' => 'Apsavērt i pamaineit puorraugamū rokstu listi',
'watchlisttools-raw'  => 'Maineit puorraugamūs rokstu listes kodu',

# Special:Version
'version-specialpages' => 'Specialuos puslopys',

# Special:SpecialPages
'specialpages' => 'Specialuos puslopys',

);
