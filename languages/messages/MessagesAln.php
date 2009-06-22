<?php
/** Gheg Albanian (Gegë)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bresta
 * @author Cradel
 * @author Dardan
 */

$fallback = 'sq';

$specialPageAliases = array(
	'Popularpages'              => array( 'Faqe të famshme' ),
	'Search'                    => array( 'Kërko' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Nënvizoji lidhjet',
'tog-highlightbroken'         => 'Shfaqi lidhjet për në faqe të zbrazëta <a href="" class="new">kështu </a> (ndryshe: kështu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Drejto kryerreshtat',
'tog-hideminor'               => 'Mshef redaktimet e vogla të bâme së voni',
'tog-hidepatrolled'           => 'Mshef redaktimet e mbikëqyruna në ndryshimet e fundit',
'tog-newpageshidepatrolled'   => 'Mshef redaktimet e mbikëqyruna prej listës së faqeve të reja',
'tog-extendwatchlist'         => "Zgjâno listën mbikëqyrëse me i pa të tâna ndryshimet, jo veç ato t'fundit",
'tog-usenewrc'                => 'Përdor ndryshimet e mëdha të bâme së voni (JavaScript)',
'tog-numberheadings'          => 'Numëro automatikisht mbititujt',
'tog-showtoolbar'             => 'Trego butonat për redaktim (JavaScript)',
'tog-editondblclick'          => 'Redakto faqet me klikim të dyfishtë (JavaScript)',
'tog-editsection'             => 'Lejo redaktimin e seksioneve me opcionin [redakto]',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve tue klikue me të djathtë mbi titull (JavaScript)',
'tog-showtoc'                 => 'Trego përmbajtjen<br />(për faqet me mâ shum se 3 tituj)',
'tog-rememberpassword'        => 'Ruej fjalëkalimin në këtë kompjuter',
'tog-editwidth'               => 'Zgjâno kutinë për redaktim sa krejt ekrani',
'tog-watchcreations'          => 'Shtoji në listë mbikëqyrëse faqet që i krijoj vetë',
'tog-watchdefault'            => 'Shtoji në listë mbikëqyrëse faqet që i redaktoj',
'tog-watchmoves'              => 'Shtoji në listë mbikëqyrëse faqet që i zhvendosi',
'tog-watchdeletion'           => 'Shtoji në listë mbikëqyrëse faqet që i fshij',
'tog-minordefault'            => 'Shêjoji fillimisht tâna redaktimet si të vogla',
'tog-previewontop'            => 'Vendose parapamjen përpara kutisë redaktuese',
'tog-previewonfirst'          => 'Shfaqe parapamjen në redaktimin e parë',
'tog-nocache'                 => 'Mos ruej kopje të faqeve',
'tog-enotifwatchlistpages'    => 'Njoftomë me email kur ndryshojnë faqet nën mbikëqyrje',
'tog-enotifusertalkpages'     => 'Njoftomë me email kur ndryshon faqja ime e diskutimit',
'tog-enotifminoredits'        => 'Njoftomë me email për redaktime të vogla të faqeve',
'tog-enotifrevealaddr'        => 'Shfaqe adresën time në emailat njoftues',
'tog-shownumberswatching'     => 'Shfaqe numrin e përdoruesve mbikëqyrës',
'tog-fancysig'                => 'Mos e përpuno nënshkrimin për formatim',
'tog-externaleditor'          => 'Përdor program të jashtem për redaktime',
'tog-externaldiff'            => 'Përdor program të jashtem për të tréguar ndryshimét',
'tog-showjumplinks'           => 'Lejo lidhjet é afrueshmerisë "kapërce tek"',
'tog-uselivepreview'          => 'Trego parapamjén meniheré (JavaScript) (Eksperimentale)',
'tog-forceeditsummary'        => 'Pyetem kur e le përmbledhjen e redaktimit zbrazt',
'tog-watchlisthideown'        => "M'sheh redaktimet e mia nga lista mbikqyrëse",
'tog-watchlisthidebots'       => 'Mshef redaktimet e robotave nga lista e vrojtimit',
'tog-watchlisthideminor'      => 'Mshef redaktimet e vogla nga lista e vrojtimit',
'tog-watchlisthideliu'        => "Mshef redaktimet e përdoruesve t'kyçun prej listës së vrojtimit",
'tog-watchlisthideanons'      => 'Mshef redaktimet e anonimëve prej listës së vrojtimit',
'tog-watchlisthidepatrolled'  => 'Mshef redaktimet e mbikëqyruna prej listës së vrojtimit',
'tog-nolangconversion'        => 'Mos lejo konvertimin e variantëve',
'tog-ccmeonemails'            => 'Më ço kopje të mesazheve qi ua dërgoj të tjerëve',
'tog-diffonly'                => 'Mos e trego përmbajtjen e faqes nën ndryshimin',
'tog-showhiddencats'          => 'Trego kategoritë e mshefta',
'tog-noconvertlink'           => 'Mos lejo konvertimin e titullit vegëz',
'tog-norollbackdiff'          => 'Trego ndryshimin mbas procedurës së kthimit mbrapa',

'underline-always'  => 'gjithmonë',
'underline-never'   => 'kurrë',
'underline-default' => 'njisoj si shfletuesi',

# Dates
'sunday'        => 'E diel',
'monday'        => 'E hâne',
'tuesday'       => 'E marte',
'wednesday'     => 'E mërkure',
'thursday'      => 'E êjte',
'friday'        => 'E premte',
'saturday'      => 'E shtune',
'sun'           => 'Dje',
'mon'           => 'Hân',
'tue'           => 'Mar',
'wed'           => 'Mër',
'thu'           => 'Êjt',
'fri'           => 'Pre',
'sat'           => 'Sht',
'january'       => 'kallnor',
'february'      => 'fror',
'march'         => 'mars',
'april'         => 'prill',
'may_long'      => 'maj',
'june'          => 'qershor',
'july'          => 'korrik',
'august'        => 'gusht',
'september'     => 'shtator',
'october'       => 'tetor',
'november'      => 'nândor',
'december'      => 'dhetor',
'january-gen'   => 'kallnorit',
'february-gen'  => 'shkurtit',
'march-gen'     => 'marsit',
'april-gen'     => 'prillit',
'may-gen'       => 'majit',
'june-gen'      => 'qershorit',
'july-gen'      => 'korrikut',
'august-gen'    => 'gushtit',
'september-gen' => 'shtatorit',
'october-gen'   => 'tetorit',
'november-gen'  => 'nândorit',
'december-gen'  => 'dhetorit',
'jan'           => 'Kall',
'feb'           => 'Fro',
'mar'           => 'Mar',
'apr'           => 'Pri',
'may'           => 'Maj',
'jun'           => 'Qer',
'jul'           => 'Korr',
'aug'           => 'Gush',
'sep'           => 'Sht',
'oct'           => 'Tet',
'nov'           => 'Nân',
'dec'           => 'Dhe',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategoritë}}',
'category_header'                => 'Artikuj në kategorinë "$1"',
'subcategories'                  => 'Nënkategori',
'category-media-header'          => 'Media në kategori "$1"',
'category-empty'                 => "''Kjo kategori tashpërtash nuk përmban asnji faqe apo media.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori e msheftë|Kategori të mshefta}}',
'hidden-category-category'       => 'Kategori të mshefta',
'category-subcat-count'          => '{{PLURAL:$2|Kjo kategori ka vetëm këtë nënkategori.|Kjo kategori ka {{PLURAL:$1|këtë nënkategori|$1 këto nënkategori}}, nga gjithsejt $2.}}',
'category-subcat-count-limited'  => 'Kjo kategori ka {{PLURAL:$1|këtë nënkategori|$1 këto nënkategori}}.',
'category-article-count'         => '{{PLURAL:$2|Kjo kategori ka vetëm këtë faqe.|Kjo kategori ka {{PLURAL:$1|këtë faqe|$1 faqe}} nga gjithsejt $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Faqja âsht|$1 faqe janë}} në këtë kategori.',
'category-file-count'            => '{{PLURAL:$2|Kjo kategori ka vetëm këtë skedë.|{{PLURAL:$1|kjo skedë âsht|$1 skeda janë}} në këtë kategori, prej gjithsejt $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Kjo skedë âsht|$1 skeda janë}} në këtë kategori.',
'listingcontinuesabbrev'         => 'vazh.',

'mainpagetext'      => "<big>'''MediaWiki software u instalue me sukses.'''</big>",
'mainpagedocfooter' => 'Për mâ shumë informata rreth përdorimit të softwareit wiki, ju lutem shikoni [http://meta.wikimedia.org/wiki/Help:Contents dokumentacionin].


== Për fillim ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Konfigurimi i MediaWikit]
* [http://www.mediawiki.org/wiki/Help:FAQ Pyetjet e shpeshta rreth MediaWikit]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Njoftime rreth MediaWikit]',

'about'         => 'Rreth',
'article'       => 'Artikulli',
'newwindow'     => '(çelet në nji dritare të re)',
'cancel'        => 'Harroji',
'moredotdotdot' => 'Mâ shumë...',
'mypage'        => 'Faqja jeme',
'mytalk'        => 'Diskutimet e mija',
'anontalk'      => 'Diskutimet për këtë IP',
'navigation'    => 'Navigimi',
'and'           => '&#32;dhe',

# Cologne Blue skin
'qbfind'         => 'Kërko',
'qbbrowse'       => 'Shfleto',
'qbedit'         => 'Redakto',
'qbpageoptions'  => 'Kjo faqe',
'qbpageinfo'     => 'Konteksti',
'qbmyoptions'    => 'Faqet e mija',
'qbspecialpages' => 'Faqet speciale',
'faq'            => 'Pyetjet e shpeshta',
'faqpage'        => 'Project:Pyetjet e shpeshta',

# Vector skin
'vector-action-addsection'   => 'Shto temë',
'vector-action-delete'       => 'Fshij',
'vector-action-move'         => 'Zhvendos',
'vector-action-protect'      => 'Mbroj',
'vector-action-undelete'     => 'Kthe fshimjen mbrapsht',
'vector-action-unprotect'    => 'Hiq mbrojtjen',
'vector-namespace-category'  => 'Kategoria',
'vector-namespace-help'      => 'Faqja e ndihmës',
'vector-namespace-image'     => 'Skeda',
'vector-namespace-main'      => 'Faqja',
'vector-namespace-media'     => 'Faqja e mediave',
'vector-namespace-mediawiki' => 'Mesazhi',
'vector-namespace-project'   => 'Faqja e projektit',
'vector-namespace-special'   => 'Faqja speciale',
'vector-namespace-talk'      => 'Diskutimi',
'vector-namespace-template'  => 'Shablloni',
'vector-namespace-user'      => 'Faqja e përdoruesit',
'vector-view-create'         => 'Krijo',
'vector-view-edit'           => 'Redakto',
'vector-view-history'        => 'Shih historinë',
'vector-view-view'           => 'Lexo',
'vector-view-viewsource'     => 'Shih kodin',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Gabim',
'returnto'          => 'Kthehu te $1.',
'tagline'           => 'Nga {{SITENAME}}',
'help'              => 'Ndihmë',
'search'            => 'Kërko',
'searchbutton'      => 'Kërko',
'go'                => 'Shko',
'searcharticle'     => 'Shko',
'history'           => 'Historiku i faqes',
'history_short'     => 'Historiku',
'updatedmarker'     => 'ndryshue nga vizita jeme e fundit',
'info_short'        => 'Informacion',
'printableversion'  => 'Verzioni për shtyp',
'permalink'         => 'Vegëz e përhershme',
'print'             => 'Shtyp',
'edit'              => 'Redakto',
'create'            => 'Krijo',
'editthispage'      => 'Redakto këtë faqe',
'create-this-page'  => 'Krijo këtë faqe',
'delete'            => 'Fshij',
'deletethispage'    => 'Fshije këtë faqe',
'undelete_short'    => 'Kthe {{PLURAL:$1|redaktimin e fshimë|$1 redaktime të fshime}}',
'protect'           => 'Mbroj',
'protect_change'    => 'ndrysho',
'protectthispage'   => 'Mbroje këtë faqe',
'unprotect'         => 'Hiq mbrojtjen',
'unprotectthispage' => 'Hiq mbrojtjen nga kjo faqe',
'newpage'           => 'Faqe e re',
'talkpage'          => 'Diskuto këtë faqe',
'talkpagelinktext'  => 'Diskuto',
'specialpage'       => 'Faqe speciale',
'personaltools'     => 'Veglat personale',
'postcomment'       => 'Sekcion i ri',
'articlepage'       => 'Shiko artikullin',
'talk'              => 'Diskutimi',
'views'             => 'Shikime',
'toolbox'           => 'Veglat',
'userpage'          => 'Shiko faqen e përdoruesit',
'projectpage'       => 'Shiko faqen e projektit',
'imagepage'         => 'Shiko faqen e skedës',
'mediawikipage'     => 'Shiko faqen e mesazheve',
'templatepage'      => 'Shiko faqen e shabllonit',
'viewhelppage'      => 'Shiko faqen për ndihmë',
'categorypage'      => 'Shiko faqen e kategorisë',
'viewtalkpage'      => 'Shiko diskutimin',
'otherlanguages'    => 'Në gjuhë tjera',
'redirectedfrom'    => '(Përcjellë nga $1)',
'redirectpagesub'   => 'Faqe përcjellëse',
'lastmodifiedat'    => 'Kjo faqe âsht ndryshue për herë të fundit me $2, $1.',
'viewcount'         => 'Kjo faqe âsht pâ {{PLURAL:$1|nji|$1}} herë.',
'protectedpage'     => 'Faqe e mbrojtun',
'jumpto'            => 'Kce te:',
'jumptonavigation'  => 'navigim',
'jumptosearch'      => 'kërko',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Rreth {{SITENAME}}',
'aboutpage'            => 'Project:Rreth',
'copyright'            => 'Përmbajtja âsht lëshue nën $1.',
'copyrightpagename'    => '{{SITENAME}} e drejta autoriale',
'copyrightpage'        => '{{ns:project}}:Të drejtat autoriale',
'currentevents'        => 'Ndodhitë aktuale',
'currentevents-url'    => 'Project:Ndodhitë aktuale',
'disclaimers'          => 'Shfajsimet',
'disclaimerpage'       => 'Project:Shfajsimet e përgjithshme',
'edithelp'             => 'Ndihmë për redaktim',
'edithelppage'         => 'Help:Redaktimi',
'helppage'             => 'Help:Përmbajtja',
'mainpage'             => 'Faqja Kryesore',
'mainpage-description' => 'Faqja Kryesore',
'policy-url'           => 'Project:Politika',
'portal'               => 'Portali i komunitetit',
'portal-url'           => 'Project:Portali i komunitetit',
'privacy'              => 'Politika e të dhânave private',
'privacypage'          => 'Project:Politika e të dhânave private',

'badaccess'        => 'Gabim tagri',
'badaccess-group0' => 'Nuk keni tagër me ekzekutue veprimin e kërkuem.',
'badaccess-groups' => 'Në veprimin e kërkuem kanë tagër vetëm përdoruesit nga {{PLURAL:$2|grupi|grupet}}: $1.',

'versionrequired'     => 'Nevojitet verzioni $1 i MediaWikit',
'versionrequiredtext' => 'Nevojitet verzioni $1 i MediaWikit për përdorimin e kësaj faqeje. Shiko [[Special:Verzion|verzionin]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Marrë nga "$1"',
'youhavenewmessages'      => 'Keni $1 ($2).',
'newmessageslink'         => 'mesazhe të reja',
'newmessagesdifflink'     => 'ndryshimi i fundit',
'youhavenewmessagesmulti' => 'Keni mesazhe të reja në $1',
'editsection'             => 'redakto',
'editold'                 => 'redakto',
'viewsourceold'           => 'shih kodin',
'editlink'                => 'redakto',
'viewsourcelink'          => 'shih kodin',
'editsectionhint'         => 'Redakto sekcionin: $1',
'toc'                     => 'Përmbajtja',
'showtoc'                 => 'trego',
'hidetoc'                 => 'mshef',
'thisisdeleted'           => 'Shiko ose rikthe $1?',
'viewdeleted'             => 'Shiko $1?',
'restorelink'             => '{{PLURAL:$1|nji redaktim i fshimë|$1 redaktime të fshime}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Lloji i abonimit të feedit âsht i gabuem.',
'feed-unavailable'        => 'Feedsat Sindikal nuk pranohen',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (faqja nuk ekziston)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulli',
'nstab-user'      => 'Faqja e përdoruesit',
'nstab-media'     => 'Faqja e mediave',
'nstab-special'   => 'Faqja speciale',
'nstab-project'   => 'Faqja e projektit',
'nstab-image'     => 'Skeda',
'nstab-mediawiki' => 'Mesazhet',
'nstab-template'  => 'Shablloni',
'nstab-help'      => 'Faqja e ndihmës',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Ky veprim nuk ekziston',
'nosuchactiontext'  => "Veprimi i kërkuem me URL nuk âsht valid.
Ndoshta keni shkrue gabim URL'ën, ose keni përcjellë vegëz të gabueme.
Kjo gjithashtu mundet me tregue gabim në softwarein e {{SITENAME}}.",
'nosuchspecialpage' => 'Nuk ekziston kjo faqe speciale',
'nospecialpagetext' => "<big>'''Keni kërkue nji faqe speciale jovalide.'''</big>

Lista e faqeve speciale valide gjindet te [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Gabim',
'databaseerror'        => 'Gabim në databazë',
'dberrortext'          => 'Ka ndodh nji gabim në sintaksën e kërkesës në databazë. 
Kjo mundet me tregue gabim në software.
Kërkesa e fundit në databazë ishte:
<blockquote><tt>$1</tt></blockquote>
mbrenda funksionit "<tt>$2</tt>".
MySQL ktheu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ka ndodh një gabim sintaksor në kërkesën në databazë. 
Kërkesa e fundit në databazë ishte:
"$1"
mbrenda funksionit "$2".
MySQL ktheu gabimin "$3: $4".',
'laggedslavemode'      => "'''Kujdes:''' Kjo faqe mundet mos me përmbajtë ndryshime të reja.",
'readonly'             => 'Databaza âsht e bllokueme',
'enterlockreason'      => 'Futni një arsye për bllokimin, gjithashtu futni edhe kohën se kur pritet të çbllokohet',
'readonlytext'         => 'Databaza e {{SITENAME}} âsht e bllokueme dhe nuk lejon redaktime, me gjasë për mirëmbajtje rutinore, mbas së cillës do të kthehet në gjendje normale.

Administruesi, i cilli e ka bllokue dha këtë arsye: $1',
'missing-article'      => 'Databaza nuk e gjeti tekstin e faqes me emën "$1" $2.

Kjo zakonisht ndodh nga përcjellja e nji ndryshimi të vjetëruem apo të nji vegze në faqe të fshime.

Nëse nuk âsht kështu, mund ta keni gjetë nji gabim në software. Ju lutemi, njoftoni nji [[Special:ListUsers/sysop|administrues]], për këtë, tue tregue URL\'ën.',
'missingarticle-rev'   => '(rishikimi#: $1)',
'missingarticle-diff'  => '(Ndryshimi: $1, $2)',
'readonly_lag'         => 'Databaza âsht bllokue automatikisht përderisa serverat e mvarun të skinkronizohen me kryesorin.',
'internalerror'        => 'Gabim i mbrendshëm',
'internalerror_info'   => 'Gabimi i mbrendshëm: $1',
'filecopyerror'        => 'Nuk mujta me kopjue skedën "$1" te "$2".',
'filerenameerror'      => 'Nuk mujta me ndërrue emnin e skedës "$1" në "$2".',
'filedeleteerror'      => 'Nuk mujta me fshî skedën "$1".',
'directorycreateerror' => 'Nuk mujta me krijue direktorinë "$1".',
'filenotfound'         => 'Nuk mujta me gjetë skedën "$1".',
'fileexistserror'      => 'Nuk mujta me shkrue në skedën "$1": Kjo skedë ekziston',
'unexpected'           => 'Vlerë e papritun: "$1"="$2".',
'formerror'            => 'Gabim: nuk mujta me dërgue formularin',
'badarticleerror'      => 'Ky veprim nuk mundet me u ekzekutue në këtë faqe.',
'cannotdelete'         => 'Nuk mujta me fshi faqen apo skedën e dhânë. 
Ndodh që âsht fshi prej dikujt tjetër.',
'badtitle'             => 'Titull i keq',
'badtitletext'         => 'Titulli i faqes që kërkuet ishte jovalid, bosh, apo ishte nji vegëz gabim e lidhun ndërgjuhesisht apo ndër-wiki.
Ndodh që ka shêja që nuk munden me u përdorë në titull.',
'perfcached'           => 'Informacioni i mâposhtëm âsht kopje e memorizueme, por mundet mos me qenë verzioni i fundit:',
'perfcachedts'         => 'Shenimi i mâposhtëm âsht kopje e memorizueme dhe âsht rifreskue së fundit me $1.',
'querypage-no-updates' => 'Redaktimi i kësaj faqeje âsht ndalue për momentin.
Shenimet këtu nuk do të rifreskohen.',
'wrong_wfQuery_params' => 'Parametra gabim te wfQuery()<br />
Funksioni: $1<br />
Kërkesa: $2',
'viewsource'           => 'Shih kodin',
'viewsourcefor'        => 'e $1',
'actionthrottled'      => 'Veprimi âsht i kufizuem',
'actionthrottledtext'  => 'Si masë kunder spamit, jeni të kufizuem me kry këtë veprim shumë herë për nji kohë shumë të shkurtë, dhe e keni tejkalue këtë kufizim.
Ju lutemi provoni prap mbas disa minutave.',
'protectedpagetext'    => 'Kjo faqe âsht mbyllë për redaktim.',
'viewsourcetext'       => 'Mundeni me pâ dhe kopjue kodin burimor të kësaj faqeje:',
'protectedinterface'   => 'Kjo faqe përmban tekst të interfaceit të softwareit dhe âsht e mbrojtun për me pengue keqpërdorimin.',
'editinginterface'     => "'''Kujdes:''' Po redaktoni nji faqe që përdoret për me ofrue tekst të interfaceit të softwareit. 
Ndryshimet në këtë faqe do të prekin pamjen e interfaceit për të gjithë përdoruesit tjerë.
Për përkthim, konsideroni ju lutem përdorimin e [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], projektin e MediaWiki për përshtatje gjuhësore.",
'sqlhidden'            => '(Kërkesa SQL e msheftë)',
'cascadeprotected'     => 'Kjo faqe âsht e mbrojtun prej redaktimit, për shkak se âsht e përfshime në {{PLURAL:$1|faqen, e cila âsht e mbrojtun|faqet, të cilat janë të mbrojtuna}} me opcionin "zinxhir" të zgjedhun:
$2',
'customcssjsprotected' => 'Nuk keni leje me ndryshu këtë faqe sepse përmban informata personale të një përdoruesi tjetër',

# Login and logout pages
'logouttext'              => 'Keni dálë jashtë {{SITENAME}}-s. Muneni me vazhdu me përdor {{SITENAME}}-n anonimisht, ose muneni me hy brenda prap.',
'welcomecreation'         => '== Mirësevini, $1! ==

Llogaria juej asht hap. Mos harroni me ndryshu parapëlqimet e {{SITENAME}}-s.',
'yourpassword'            => 'Futni fjalëkalimin tuej',
'yourpasswordagain'       => 'Futni fjalëkalimin prap',
'remembermypassword'      => 'Mbaj mend fjalëkalimin tim për krejt vizitat e ardhshme.',
'yourdomainname'          => 'Faqja juej',
'externaldberror'         => 'Ose kishte një gabim te regjistri i identifikimit të jashtëm, ose nuk ju lejohet të përtërini llogarinë tuej të jashtme.',
'login'                   => 'Hyni',
'nav-login-createaccount' => 'Hyni ose çeleni një llogari',
'userlogin'               => 'Hyni ose çeleni një llogari',
'logout'                  => 'Dalje',
'userlogout'              => 'Dalje',
'notloggedin'             => 'Nuk keni hy brenda',
'nologinlink'             => 'Çeleni',
'gotaccount'              => 'A keni një llogari? $1.',
'gotaccountlink'          => 'Hyni',
'createaccountmail'       => 'me email',
'userexists'              => 'Nofka që përdorët asht në përdorim. Zgjidhni një nofkë tjetër.',
'nocookieslogin'          => '{{SITENAME}} përdor "biskota" për me futë brenda përdoruesit. Prandaj, duhet të pranoni "biskota" dhe të provoni prap.',

# Edit page toolbar
'bold_sample'     => 'Tekst i trashë',
'bold_tip'        => 'Tekst i trashë',
'italic_sample'   => 'Tekst i pjerrët',
'italic_tip'      => 'Tekst i pjerrët',
'link_sample'     => 'Titulli i lidhjes',
'link_tip'        => 'Lidhje e brendshme',
'extlink_sample'  => 'http://www.example.com Titulli i lidhjes',
'extlink_tip'     => 'Lidhje e jashtme (mos e harro prefiksin http://)',
'headline_sample' => 'Titull shembull',
'headline_tip'    => 'Titull i nivelit 2',
'math_sample'     => 'Vendos formulën këtu',
'math_tip'        => 'Formulë matematike (LaTeX)',
'nowiki_sample'   => 'Vendos tekst qi nuk duhet me u formatue',
'nowiki_tip'      => 'Mos përdor format wiki',
'image_tip'       => 'Vendose një figurë',
'media_tip'       => 'Lidhje media-skedave',
'sig_tip'         => 'Firma juej dhe koha e firmosjes',
'hr_tip'          => 'vijë horizontale (përdoreni rallë)',

# Edit pages
'summary'            => 'Përmbledhje:',
'subject'            => 'Subjekt/Titull:',
'minoredit'          => 'Ky asht një redaktim i vogël',
'watchthis'          => 'Mbikqyre kët faqe',
'showpreview'        => 'Trego parapamjen',
'showdiff'           => 'Trego ndryshimet',
'anoneditwarning'    => 'Ju nuk jeni regjistruem. IP adresa juej do të regjistrohet në historinë e redaktimeve të kësaj faqe.',
'newarticletext'     => "{{SITENAME}} nuk ka një ''{{NAMESPACE}} faqe'' të quajtme '''{{PAGENAME}}'''. Shtypni '''redaktoni''' ma sipër ose [[Special:Search/{{PAGENAME}}|bani një kërkim për {{PAGENAME}}]]",
'noarticletext'      => 'Tash për tash nuk ka tekst në kët faqe, muneni me [[Special:Search/{{PAGENAME}}|kërkue]] kët titull në faqe të tjera ose muneni me [{{fullurl:{{FULLPAGENAME}}|action=edit}} fillu] atë.',
'editing'            => 'Tuj redaktue $1',
'copyrightwarning'   => "Kontributet te {{SITENAME}} janë të konsiderueme të dhana nën licensën $2 (shikoni $1 për hollësirat).<br />
'''NDALOHET DHËNIA E PUNIMEVE PA PAS LEJE NGA AUTORI NË MOSPËRPUTHJE ME KËTË LICENSË!'''<br />",
'template-protected' => '(e mbrojtme)',

# History pages
'revisionasof'     => 'Versioni i $1',
'revision-info'    => 'Versioni me $1 nga $2',
'previousrevision' => '← Verzion ma i vjetër',
'cur'              => 'tash',
'last'             => 'fund',

# Diffs
'lineno'                  => 'Rreshti $1:',
'compareselectedversions' => 'Krahasoni versionet e zgjedhme',
'editundo'                => 'ktheje',

# Search results
'noexactmatch'   => 'Faqja me atë titull nuk asht krijue

Muneni me [[$1|fillu një artikull]] me kët titull.

Ju lutem kërkoni {{SITENAME}}-n para se me krijue një artikull të ri se munet me kánë nën një titull tjetër.',
'viewprevnext'   => 'Shikoni ($1) ($2) ($3).',
'searchhelp-url' => 'Help:Ndihmë',
'powersearch'    => 'Kërko',

# Preferences page
'mypreferences' => 'Parapëlqimet',
'skin-preview'  => 'Parapamje',
'youremail'     => 'Adresa e email-it*',
'username'      => 'Nofka e përdoruesit:',
'uid'           => 'Nr. i identifikimit:',
'yourrealname'  => 'Emri juej i vërtetë*',
'yourlanguage'  => 'Ndërfaqja gjuhësore',
'yournick'      => 'Nofka :',
'badsig'        => 'Sintaksa e nënshkrimit asht e pavlefshme, kontrolloni HTML-n.',
'badsiglength'  => 'Emri i zgjedhun asht shumë i gjatë; duhet me pas ma pak se $1 shkronja',
'email'         => 'Email',

# Recent changes
'recentchanges'   => 'Ndryshimet e fundit',
'rcnote'          => 'Ma poshtë janë <strong>$1</strong> ndryshimt e fundit gjatë <strong>$2</strong> ditëve sipas të dhanave nga $3.',
'rcshowhideminor' => '$1 redaktimet e vogla',
'rcshowhidepatr'  => '$1 redaktime të patrullueme',
'rclinks'         => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'diff'            => 'ndrysh',
'hist'            => 'hist',
'hide'            => 'msheh',
'show'            => 'kallzo',
'minoreditletter' => 'v',
'newpageletter'   => 'R',
'boteditletter'   => 'b',

# Recent changes linked
'recentchangeslinked'         => 'Ndryshimet fqinje',
'recentchangeslinked-feed'    => 'Ndryshimet fqinje',
'recentchangeslinked-toolbox' => 'Ndryshimet fqinje',
'recentchangeslinked-title'   => 'Ndryshimet në lidhje me "$1"',

# Upload
'upload' => 'Ngarkoni skeda',

# File description page
'file-anchor-link'    => 'Figura',
'filehist'            => 'Historiku i dosjes',
'filehist-datetime'   => 'Data/Ora',
'filehist-user'       => 'Përdoruesi',
'filehist-dimensions' => 'Dimenzionet',
'filehist-filesize'   => 'Madhësia e figurës/skedës',
'filehist-comment'    => 'Koment',
'imagelinks'          => 'Lidhje e skedave',
'linkstoimage'        => "K'to faqe lidhen te kjo figurë/skedë:",
'sharedupload'        => 'Kjo skedë asht një ngarkim i përbashkët dhe munet me u përdor nga projekte të tjera.',

# File deletion
'filedelete-reason-otherlist' => 'Arsyje tjera',

# MIME search
'download' => 'shkarkim',

# Random page
'randompage' => 'Artikull i rastit',

# Statistics
'statistics' => 'Statistika',

'withoutinterwiki' => 'Artikuj pa lidhje interwiki',

# Miscellaneous special pages
'nbytes'   => '$1 bytes',
'nlinks'   => '$1 lidhje',
'nmembers' => '$1 anëtarë',
'move'     => 'Zhvendose',

# Special:AllPages
'alphaindexline' => '$1 deri në $2',
'allpagessubmit' => 'Shko',

# Special:Categories
'categories' => 'Kategori',

# Watchlist
'mywatchlist'      => 'Lista mbikqyrëse',
'addedwatch'       => 'U shtu te lista mbikqyrëse',
'removedwatch'     => 'U hjek nga lista mibkqyrëse',
'removedwatchtext' => 'Faqja "<nowiki>$1</nowiki>" asht hjek nga lista mbikqyrëse e juej.',
'watch'            => 'Mbikqyre',
'unwatch'          => 'Çmbikqyre',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Tuj mbikqyrë...',
'unwatching' => 'Tuj çmbikqyrë...',

# Delete
'deletedarticle' => 'grisi "$1"',

# Protect
'protect-legend'              => 'Konfirmoni',
'protectcomment'              => 'Arsyja:',
'protectexpiry'               => 'Afáti',
'protect_expiry_invalid'      => 'Data e skadimit asht e pasaktë.',
'protect_expiry_old'          => 'Data e skadimit asht në kohën kalueme.',
'protect-unchain'             => 'Ndryshoje lejen e zhvendosjeve',
'protect-text'                => "Këtu muneni me shiku dhe me ndryshu nivelin e mbrojtjes për faqen '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Llogaria juej nuk ka privilegjet e nevojitme për me ndryshu nivelin e mbrojtjes. Kufizimet e kësaj faqe janë '''$1''':",
'protect-default'             => '(parazgjedhje)',
'protect-level-autoconfirmed' => 'Blloko përdoruesit pa llogari',
'protect-level-sysop'         => 'Lejo veç administruesit',
'protect-expiring'            => 'skadon me $1 (UTC)',
'protect-cascade'             => 'Mbrojtje e ndërlidhme - mbroj çdo faqe që përfshihet në këtë faqe.',
'protect-cantedit'            => 'Nuk nuk muneni me ndryshu nivelin e mbrojtjes në kët faqe, sepse nuk keni leje.',
'restriction-type'            => 'Lejet:',
'restriction-level'           => 'Mbrojtjet:',

# Namespace form on various pages
'namespace'      => 'Hapësira:',
'blanknamespace' => '(Artikujt)',

# Contributions
'contributions' => 'Kontributet',
'mycontris'     => 'Redaktimet e mia',

'sp-contributions-talk' => 'Diskuto',

# What links here
'whatlinkshere'       => "Lidhjet k'tu",
'whatlinkshere-title' => 'Faqe qi lidhen me $1',
'linkshere'           => "Faqet e mëposhtme lidhen k'tu '''[[:$1]]''':",
'isredirect'          => 'faqe përcjellëse',
'istemplate'          => 'përfshirë',
'whatlinkshere-links' => '← lidhje',

# Block/unblock
'blocklink'    => 'bllokoje',
'contribslink' => 'kontribute',

# Move page
'movearticle' => 'Zhvendose faqen',
'newtitle'    => 'Te titulli i ri',
'move-watch'  => 'Mbikqyre kët faqe',
'movepagebtn' => 'Zhvendose faqen',
'movedto'     => 'zhvendosur te',
'movereason'  => 'Arsyja',

# Thumbnails
'thumbnail-more'  => 'Zmadho',
'thumbnail_error' => 'Gabim gjatë krijimit të figurës përmbledhëse: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Faqja juej e përdoruesit',
'tooltip-pt-mytalk'               => 'Faqja juej e diskutimeve',
'tooltip-pt-preferences'          => 'Parapëlqimet tuaja',
'tooltip-pt-watchlist'            => 'Lista e faqeve nën mbikqyrjen tuej.',
'tooltip-pt-mycontris'            => 'Lista e kontributeve tueja',
'tooltip-pt-login'                => 'Me hy brenda nuk asht e detyrueshme, po ká shumë përparësi.',
'tooltip-pt-logout'               => 'Dalje',
'tooltip-ca-talk'                 => 'Diskuto për përmbajtjen e faqes',
'tooltip-ca-edit'                 => "Ju muneni me redaktue kët faqe. Përdorni butonin >>Trego parapamjen<< para se t'i kryni ndryshimet.",
'tooltip-ca-addsection'           => "Nis një temë t're diskutimi.",
'tooltip-ca-viewsource'           => 'Kjo faqe asht e mbrojtme. Ju muneni veç ta shikoni burimin e tekstit.',
'tooltip-ca-move'                 => 'Zhvendose faqen',
'tooltip-ca-watch'                => 'Shtoje kët faqe në lisën e faqeve nën mbikqyrje',
'tooltip-search'                  => 'Kërko në projekt',
'tooltip-n-mainpage'              => 'Vizitojeni Faqen kryesore',
'tooltip-n-portal'                => 'Mbi projektin, çka muneni me bá për të dhe ku gjénden faqet.',
'tooltip-n-currentevents'         => 'Informacion rreth ngjarjeve aktuale.',
'tooltip-n-recentchanges'         => 'Lista e ndryshimeve të fundme në projekt',
'tooltip-n-randompage'            => 'Shikoni një artikull të rastit.',
'tooltip-n-help'                  => 'Vendi ku muneni me gjetë ndihmë.',
'tooltip-t-whatlinkshere'         => 'Lista e faqeve qi lidhen te kjo faqe',
'tooltip-t-upload'                => 'Ngarkoni figura ose skeda tjera',
'tooltip-t-specialpages'          => 'Lista e krejt faqeve speciale.',
'tooltip-ca-nstab-image'          => 'Shikoni faqen e figurës',
'tooltip-ca-nstab-category'       => 'Shikoni faqen e kategorisë',
'tooltip-save'                    => 'Kryej ndryshimet',
'tooltip-preview'                 => 'Shiko parapamjen e ndryshimeve, përdore këtë para se me kry ndryshimet!',
'tooltip-diff'                    => 'Trego ndryshimet që Ju i keni bá tekstit.',
'tooltip-compareselectedversions' => 'Shikoni krahasimin midis dy versioneve të zgjedhme të kësaj faqe.',

# Browsing diffs
'previousdiff' => '← Nryshimi ma përpara',

# Media information
'file-nohires'   => '<small>Rezolucioni i plotë.</small>',
'show-big-image' => 'Rezolucion i plotë',

# Metadata
'metadata'        => 'Metadata',
'metadata-help'   => 'Kjo skedë përmban hollësira tjera të cilat munen qi jan shtue nga kamera ose skaneri dixhital që është përdorur për ta krijuar. Nëse se skeda asht ndryshue nga gjendja origjinale, disa hollësira munen mos me pasqyru skedën e tashme.',
'metadata-expand' => 'Tregoji detajet',

# External editor support
'edit-externally'      => 'Ndryshoni kët figurë/skedë me një mjet të jashtëm',
'edit-externally-help' => 'Shikoni [http://www.mediawiki.org/wiki/Manual:External_editors udhëzimet e instalimit] për ma shumë informacion.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'krejt',
'namespacesall' => 'krejt',

# Special:SpecialPages
'specialpages' => 'Faqet speciale',

);
