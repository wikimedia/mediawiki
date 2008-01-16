<?php
/** Haitian (Kreyòl ayisyen)
 *
 * @addtogroup Language
 *
 * @author Masterches
 * @author Siebrand
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_MEDIA          => 'Medya',
	NS_SPECIAL        => 'Espesyal',
	NS_MAIN           => '',
	NS_TALK           => 'Diskite',
	NS_USER           => 'Itilizatè',
	NS_USER_TALK      => 'Diskisyon_Itilizatè',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Diskisyon_$1',
	NS_IMAGE          => 'Imaj',
	NS_IMAGE_TALK     => 'Diskisyon_Imaj',
	NS_MEDIAWIKI      => 'MedyaWiki',
	NS_MEDIAWIKI_TALK => 'Diskisyon_MedyaWiki',
	NS_TEMPLATE       => 'Modèl',
	NS_TEMPLATE_TALK  => 'Diskisyon_Modèl',
	NS_HELP           => 'Èd',
	NS_HELP_TALK      => 'Diskisyon_Èd',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Diskisyon_Kategori'
);

$linkTrail = '/^([a-zàèòÀÈÒ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'       => 'Souliyen lyen yo :',
'tog-highlightbroken' => 'Afiche <a href="" class="new">nan koulè wouj</a> lyen yo ki ap mene nan paj ki pa egziste (oubyen : tankou <a href="" class="internal">?</a>)',
'tog-justify'         => 'Aliyen paragraf yo',
'tog-hideminor'       => 'Kache tout modifikasyon resan yo ki pa enpòtan',
'tog-extendwatchlist' => 'Itilize lis swivi ki miyò a',
'tog-usenewrc'        => 'Itilize lis swivi ki miyò a (JavaScript)',
'tog-numberheadings'  => 'Nimewote otomatiman tit yo',
'tog-showtoolbar'     => 'Montre panèl meni modifikasyon an',
'tog-watchcreations'  => 'Ajoute paj yo ke mwen ap kreye nan lis swivi mwen.',

'underline-always' => 'Toujou',
'underline-never'  => 'Jamè',

'skinpreview' => '(Voye kout zye)',

# Dates
'sunday'        => 'dimanch',
'monday'        => 'lendi',
'tuesday'       => 'madi',
'wednesday'     => 'mèkredi',
'thursday'      => 'jedi',
'friday'        => 'vandredi',
'saturday'      => 'samdi',
'sun'           => 'dim',
'mon'           => 'len',
'tue'           => 'mad',
'wed'           => 'mèr',
'thu'           => 'jed',
'fri'           => 'van',
'sat'           => 'sam',
'january'       => 'janvye',
'february'      => 'fevriye',
'march'         => 'mas',
'april'         => 'avril',
'may_long'      => 'me',
'june'          => 'jen',
'july'          => 'jiyè',
'august'        => 'out',
'september'     => 'septanm',
'october'       => 'oktòb',
'november'      => 'novanm',
'december'      => 'desanm',
'january-gen'   => 'janvye',
'february-gen'  => 'fevriye',
'march-gen'     => 'mas',
'april-gen'     => 'avril',
'may-gen'       => 'me',
'june-gen'      => 'jen',
'july-gen'      => 'jiyè',
'august-gen'    => 'out',
'september-gen' => 'septanm',
'october-gen'   => 'oktòb',
'november-gen'  => 'novanm',
'december-gen'  => 'desanm',
'jan'           => 'jan',
'feb'           => 'fev',
'mar'           => 'mas',
'apr'           => 'avr',
'may'           => 'me',
'jun'           => 'jen',
'jul'           => 'jiy',
'aug'           => 'out',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Bits of text used by many pages
'category_header'       => 'Paj yo ki nann  kategori « $1 »',
'subcategories'         => 'Pitit kategori yo',
'category-media-header' => 'Fichye miltimedya nan kategori « $1 »',

'about'         => 'Apwopo',
'article'       => 'Atik',
'newwindow'     => '(ouvè nan yon dòt fenèt)',
'cancel'        => 'Anile',
'qbpageoptions' => 'Opsyon Paj sa a',
'mytalk'        => 'Paj diskisyon mwen an',

'errorpagetitle'   => 'Erè',
'returnto'         => 'Ritounen nan paj $1.',
'tagline'          => 'Yon atik de {{SITENAME}}.',
'help'             => 'Èd',
'search'           => 'Chache',
'searchbutton'     => 'Fouye',
'searcharticle'    => 'Wè',
'history'          => 'istorik paj an',
'printableversion' => 'Vèsyon pou enprime',
'permalink'        => 'Lyen pou tout tan',
'edit'             => 'Modifye',
'editthispage'     => 'Modifye paj sa a',
'delete'           => 'Efase',
'protect'          => 'Pwoteje',
'newpage'          => 'Nouvo paj',
'talkpagelinktext' => 'Diskite',
'personaltools'    => 'Zouti pèsonèl yo',
'talk'             => 'Diskisyon',
'views'            => 'Afichay yo',
'toolbox'          => 'Bwat zouti yo',
'redirectedfrom'   => '(Redirije depi $1)',
'redirectpagesub'  => 'Paj pou redireksyon',
'jumpto'           => 'Ale nan:',
'jumptonavigation' => 'Navigasyon',
'jumptosearch'     => 'Fouye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Apwopo {{SITENAME}}',
'aboutpage'         => 'Project:Apwopo',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Aktyalite yo',
'currentevents-url' => 'Project:Aktyalite yo',
'disclaimers'       => 'Avètisman',
'disclaimerpage'    => 'Project:Avètisman jeneral yo',
'edithelp'          => 'Edite paj èd an',
'edithelppage'      => 'Èd:koman ou ap modifye yon paj',
'helppage'          => 'Help:Èd',
'mainpage'          => 'Akèy',
'portal'            => 'Pòtay kominote',
'portal-url'        => 'Project:Akèy',
'privacy'           => 'Politik konfidansyalite',
'privacypage'       => 'Project:Konfidansyalite',
'sitesupport'       => 'Fè yon don',
'sitesupport-url'   => 'Project:Fè yon don',

'retrievedfrom'       => 'Rekipere depi « $1 »',
'youhavenewmessages'  => 'Ou genyen $1 ($2).',
'newmessageslink'     => 'Ou genyen nouvo mesaj',
'newmessagesdifflink' => 'dènye chanjman',
'editsection'         => 'modifye',
'editold'             => 'modifye',
'editsectionhint'     => 'Modifye seksyon : $1',
'toc'                 => 'Kontni yo',
'showtoc'             => 'montre',
'hidetoc'             => 'kache',
'site-rss-feed'       => 'Flow RSS depi $1',
'site-atom-feed'      => 'Flow Atom depi $1',
'page-rss-feed'       => 'Flow RSS pou "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'Paj itilizatè',
'nstab-project'  => 'Paj pwojè a',
'nstab-image'    => 'Fichye',
'nstab-template' => 'Modèl',
'nstab-category' => 'Kategori',

# General errors
'viewsource'     => 'Wè kòd tèks sa a',
'viewsourcefor'  => 'pou $1',
'viewsourcetext' => 'Ou kapab gade epitou modifye kontni atik sa a pou ou travay anlè li :',

# Login and logout pages
'yourname'           => 'Non itilizatè ou an :',
'yourpassword'       => 'Mopas ou an :',
'remembermypassword' => 'Anrejistre mopas mwen an nan òdinatè mwen an',
'login'              => 'Idantifikasyon',
'loginprompt'        => 'Ou dwèt aksepte (aktive) koukiz (cookies) yopou ou kapab konekte nan {{SITENAME}}.',
'userlogin'          => 'Kreye yon kont oubyen konekte ou',
'userlogout'         => 'Dekoneksyon',
'nologin'            => 'Ou pa genyen yon kont ? $1.',
'nologinlink'        => 'Kreye yon kont',
'mailmypassword'     => 'Voye yon nouvo mopas',

# Edit page toolbar
'bold_sample'     => 'Tèks fonse',
'bold_tip'        => 'Tèks fonse',
'italic_sample'   => 'Tèks italik',
'italic_tip'      => 'Tèks italik',
'link_sample'     => 'Lyen pou tit an',
'link_tip'        => 'Lyen andidan',
'extlink_sample'  => 'http://www.example.com yon tit pou lyen an',
'extlink_tip'     => 'Lyen dewò (pa blye prefiks http:// an)',
'headline_sample' => 'Tèks pou tit',
'headline_tip'    => 'Sou-tit nivo 2',
'math_sample'     => 'Antre fòmil ou an isit',
'math_tip'        => 'Fòmil matematik (LaTeX)',
'nowiki_sample'   => 'Antre tèks ki pa fòmate a',
'nowiki_tip'      => 'Pa konte sentaks wiki an',
'image_tip'       => 'Imaj an mete',
'media_tip'       => 'Lyen pou yon fichye medya',
'sig_tip'         => 'Siyati ou ak dat an',
'hr_tip'          => 'Liy orizontal (pa abize)',

# Edit pages
'summary'                => 'Somè&nbsp;',
'subject'                => 'Sijè/tit',
'minoredit'              => 'Modifikasyon sa a pa enpòtan',
'watchthis'              => 'Swiv paj sa a',
'preview'                => 'Previzyalize (kout zye)',
'showpreview'            => 'Previzyalizasyon',
'showdiff'               => 'Montre chanjman yo',
'anoneditwarning'        => "'''Pòte atansyon :''' ou pa idantifye nan sistèm an. Adrès IP ou a ap anrejistre nan istorik paj sa a.",
'newarticle'             => '(Nouvo)',
'newarticletext'         => "Ou swiv yon paj ki poko egziste nan sistèm sa a.
Pou ou kapab kreye paj sa a, komanse ap ekri nan bwat sa a ki anba (gade [[{{MediaWiki:Helppage}}|paj èd an]] pou konnen plis, pou plis enfòmasyon).

Si se paske ou komèt yon erè ke ou ap twouve ou nan paj sa a, klike anlè bouton '''ritounen''' nan bwozè ou a.",
'noarticletext'          => 'Poko genyen tèks nan paj sa a, ou mèt [[{{ns:special}}:Search/{{PAGENAME}}|fè yon rechèch, fouye ak non paj sa a]] oubyen [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifye li].',
'previewnote'            => 'Atansyon, tèks sa a se yon previzyalizasyon, li poko anrejistre !',
'editing'                => 'Modifikasyon pou $1',
'editingsection'         => 'Modifikasyon pou $1 (seksyon)',
'copyrightwarning'       => 'Souple, raple ou ke tout piblikasyon ki fèt nan {{SITENAME}} piblye anba kontra $2 an (wè $1 pou konnen plis). Si ou pa ta vle ke sa ou ekri pataje oubyen  modifye, ou pa dwèt soumèt yo isit.<br />
Ou ap pwomèt tou ke sa ou ap ekri a se ou menm menm ki ekri li oubyen ke ou kopye li de yon sous ki nan domèn piblik, ou byen you sous ki lib. <strong>PA ITILIZE TRAVAY MOUN KI PA BAY OTORIZASYON PA LI TOUTBON !</strong>',
'templatesused'          => 'Modèl ki itilize nan paj sa a :',
'template-protected'     => '(pwoteje)',
'template-semiprotected' => '(semi-pwoteje)',
'nocreatetext'           => 'Pajwèb sa a anpeche kreyasyon nouvo paj sou li. Ou mèt ritounen nan brozè ou epi modifye yon paj ki deja egziste oubyen  [[Special:Userlogin|konekte ou oubyen kreye yon kont]].',
'recreate-deleted-warn'  => "'''Atansyon : ou ap kreye yon pak ki te efase deja.'''

Mande ou byen si ou ap byen fè kreye li ankò toutbon (gade jounal paj sa a pou konnene poukisa efasman yo te fèt anba) :s :",

# History pages
'viewpagelogs'        => 'gade jounal paj sa a',
'currentrev'          => 'Vèsyon kounye a',
'revisionasof'        => 'Vèsyon jou $1',
'revision-info'       => 'Vèsyon pou $1 pa $2',
'previousrevision'    => '← Vèsyon presedan',
'nextrevision'        => 'Vèsyon swivan →',
'currentrevisionlink' => 'Vèsyon kounye a',
'cur'                 => 'kounye a',
'last'                => 'dènye',
'histlegend'          => 'Lejand : ({{MediaWiki:Cur}}) = diferans ak vèsyon kounye a, ({{MediaWiki:Last}}) = diferans ak vèsyon anvan, <b>m</b> = modifikasyon ki pa enpòtan',
'histfirst'           => 'Premye kontribisyon yo',
'histlast'            => 'Dènye kontribisyon yo',

# Diffs
'history-title'           => 'Istorik pou vèsyon « $1 » yo',
'difference'              => '(Diferans ant vèsyon yo)',
'lineno'                  => 'Liy $1 :',
'compareselectedversions' => 'Konpare vèsyon ki seleksyone yo',
'editundo'                => 'Defè, anile',

# Search results
'noexactmatch' => "'''Pa genyen pyès paj ki genyen non sa a « $1 ».''' Ou mèt [[:$1|kreye atik sa a]].",
'prevn'        => '$1 anvan yo',
'nextn'        => '$1 swivan yo',
'viewprevnext' => 'Wè ($1) ($2) ($3).',
'powersearch'  => 'Fouye',

# Preferences page
'preferences'   => 'Preferans yo',
'mypreferences' => 'Preferans yo',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|modifikasyon|modifikasyon}}',
'recentchanges'                  => 'Modifikasyon yo ki fèk fèt',
'recentchanges-feed-description' => 'Swvi dènye modifikasyon pou wiki sa a nan flo sa a (RSS,Atom...)',
'rcnote'                         => 'Men {{PLURAL:$1|dènye modifikasyon an|dènye $1 modifikasyon yo}} depi {{PLURAL:$2|dènye jou|<b>$2</b> dènye jou yo}}, tankou $3.',
'rclistfrom'                     => 'Afiche nouvo modifikasyon yo depi $1.',
'rcshowhideminor'                => '$1 modifiksayon yo ki pa enpòtan',
'rcshowhidebots'                 => '$1 wobo',
'rcshowhideliu'                  => '$1 itilizatè ki anrejistre',
'rcshowhideanons'                => '$1 itilizatè anonim',
'rcshowhidepatr'                 => '$1 edisyon ki ap veye',
'rcshowhidemine'                 => '$1 kontribisyon mwen yo',
'rclinks'                        => 'Afiche dènye $1 modifikasyon ki fèt nan $2 dènye jou sa yo<br />$3.',
'diff'                           => 'diferans',
'hist'                           => 'istorik',
'hide'                           => 'Kache',
'show'                           => 'afiche',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',

# Recent changes linked
'recentchangeslinked'          => 'Swivi pou lyen yo',
'recentchangeslinked-title'    => 'Chanjman ki an relasyon ak $1',
'recentchangeslinked-noresult' => 'Pa genyen pyès chanjman nan paj sa yo pou peryòd ou chwazi an.e.',
'recentchangeslinked-summary'  => "Paj espesyal sa a ap monter dènye chanjman nan paj ki genyen lyen. Paj yo ki nan lis swivi ou an ap ekri '''fonse'''",

# Upload
'upload'        => 'Chaje fichye an',
'uploadbtn'     => 'Chaje fichye a',
'uploadlogpage' => 'Istorik chajman pou fichye miltimedya',
'uploadedimage' => 'chaje « [[$1]] »',

# Image list
'filehist'            => 'Istorik fichye a',
'filehist-help'       => 'Klike anlè yon dat epi yon lè pou fichye a jan li te ye nan moman sa a.',
'filehist-current'    => 'Kounye a',
'filehist-datetime'   => 'Dat ak lè',
'filehist-user'       => 'Itilizatè',
'filehist-dimensions' => 'Grandè yo',
'filehist-filesize'   => 'Lajè fichye a',
'filehist-comment'    => 'Komantè',
'imagelinks'          => 'Paj yo ki genyen imaj an',
'linkstoimage'        => 'Paj ki ap swiv yo genyen imaj sa a :',
'nolinkstoimage'      => 'Pyès paj pa genyen imaj sa a.',
'sharedupload'        => 'Fichye sa a pataje e li kapab itilize pa lòt pwojè yo.',

# Random page
'randompage' => 'Yon paj o aza',

# Statistics
'statistics' => 'Estatistik',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|okte|okte}}',
'nlinks'       => '$1 {{PLURAL:$1|lyen|lyen}}',
'nmembers'     => '$1 {{PLURAL:$1|manm|manm yo}} andidan',
'allpages'     => 'Tout paj yo',
'prefixindex'  => 'Tout paj yo ak yon prefiks (premye lèt)',
'specialpages' => 'Paj espesyal yo',
'newpages'     => 'Nouvo paj yo',
'move'         => 'Renonmen',

# Book sources
'booksources' => 'Ouvraj referans yo',

'alphaindexline' => '$1 jiska $2',

# Special:Log
'log' => 'Jounal yo',

# Special:Allpages
'nextpage'       => 'Paj swivan ($1)',
'prevpage'       => 'Paj anvan ($1)',
'allpagesfrom'   => 'Afiche paj yo depi :',
'allarticles'    => 'tout atik yo',
'allpagessubmit' => 'Ale',

# E-mail user
'emailuser' => 'Voye yon mesaj (imèl) pou itilizatè sa a',

# Watchlist
'watchlist'            => 'Lis swivi',
'mywatchlist'          => 'Lis swivi',
'watchlistfor'         => "(pou itilizatè '''$1''')",
'watch'                => 'Swiv',
'unwatch'              => 'Pa swiv ankò',
'watchlist-details'    => 'Ou ap swiv <b>$1</b> {{PLURAL:$1|paj|paj}}, san konte paj diskisyon yo.',
'wlshowlast'           => 'Montre dènye $1 zè yo, dènye $2 jou yo, oubyen $3.',
'watchlist-hide-bots'  => 'Kache kontribisyon wobo yo (Bòt)',
'watchlist-hide-own'   => 'kache modifikasyon mwen yo',
'watchlist-hide-minor' => 'Kache modifikasyon ki pa enpòtan yo',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Swiv...',
'unwatching' => 'Fini swiv paj sa a...',

# Delete/protect/revert
'deletedarticle' => 'efase « [[$1]] »',
'dellogpage'     => 'Istorik efasman yo',
'rollbacklink'   => 'anlve',

# Undelete
'undeletebtn' => 'Restore',

# Namespace form on various pages
'namespace'      => 'Espas non :',
'invert'         => 'Envèse seleksyon an',
'blanknamespace' => '(Prensipal)',

# Contributions
'contributions' => 'Kontribisyon itilizatè sa a',
'mycontris'     => 'Kontribisyon mwen yo',
'contribsub2'   => 'Lis kontribisyon $1 ($2). Paj yo ki te efase pa afiche non.',
'uctop'         => ' (komansman)',
'month'         => 'depi mwa (ak mwa anvan yo) :',
'year'          => 'Depi lane (ak anvan tou) :',

'sp-contributions-blocklog' => 'jounal blokaj yo',

# What links here
'whatlinkshere'       => 'Paj ki lye nan paj sa a',
'whatlinkshere-title' => 'Paj ki genyen lyen ki ap mennen nan $1',
'linklistsub'         => '(Lis lyen yo)',
'linkshere'           => 'Paj yo ki anba ap mene nan <b>[[:$1]]</b> :',
'nolinkshere'         => 'Pyès paj genyen lyen pou paj sa a <b>[[:$1]]</b>.',
'isredirect'          => 'Paj redireksyon',
'istemplate'          => 'andidan',
'whatlinkshere-prev'  => '{{PLURAL:$1|anvant|$1 anvan yo}}',
'whatlinkshere-next'  => '{{PLURAL:$1|swivan|$1 swivan yo}}',
'whatlinkshere-links' => '← lyen yo',

# Block/unblock
'ipboptions'    => '2 zè:2 hours,1 jou:1 day,3 jou:3 days,1 semèn:1 week,2 semèn:2 weeks,1 mwa:1 month,3 mwa:3 months,6 mwa:6 months,1 lane:1 year,ki pap janm fini:infinite', # display1:time1,display2:time2,...
'blocklink'     => 'Bloke',
'contribslink'  => 'Kontribisyon yo',
'blocklogpage'  => 'Istorik blokaj yo',
'blocklogentry' => 'bloke « [[$1]] » - dire : $2 $3',

# Move page
'movelogpage' => 'Istorik renomaj yo',
'revertmove'  => 'anile',

# Export
'export' => 'Ekspòte paj yo',

# Thumbnails
'thumbnail-more'  => 'Agrandi',
'thumbnail_error' => 'Erè nan kreyasyon minyati : $1',

# Import log
'importlogpage' => 'Istorik chajman pou paj sa a',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Paj itilizatè mwen an',
'tooltip-pt-mytalk'               => 'Paj diskisyon mwen an',
'tooltip-pt-preferences'          => 'Preferans mwen yo',
'tooltip-pt-watchlist'            => 'Lis paj ou ap swiv yo',
'tooltip-pt-mycontris'            => 'Lis kontribisyon mwen yo',
'tooltip-pt-login'                => 'Ou mèt idantifye ou mè tande ke li pa nesesè.',
'tooltip-pt-logout'               => 'Dekonekte ou',
'tooltip-ca-talk'                 => 'Diskisyon apwopo kontni paj sa a',
'tooltip-ca-edit'                 => 'Ou mèt modifye paj sa a. Souple, previzyalize mesaj ou an anvan ou anrejistre li.',
'tooltip-ca-addsection'           => 'Ajoute yon komantè nan diskisyon sa a.',
'tooltip-ca-viewsource'           => 'Paj sa a pwoteje. Ou kapab wè kòd sous li.',
'tooltip-ca-protect'              => 'Pwoteje paj sa a',
'tooltip-ca-delete'               => 'Efase paj sa a',
'tooltip-ca-move'                 => 'Renonmen paj sa a',
'tooltip-ca-watch'                => 'Ajoute paj sa a nan lis swivi ou a',
'tooltip-ca-unwatch'              => 'Retire paj sa a nan lis swivi ou an',
'tooltip-search'                  => 'Fouye nan wiki sa a',
'tooltip-n-mainpage'              => 'Vizite paj prensipal an',
'tooltip-n-portal'                => 'Apwopo pwojè a, sa ou kapab fè, ki kote ou mèt twouve kèk bagay',
'tooltip-n-currentevents'         => 'Twouve enfòmasyon yo anlè evènman ki ap fèt kounye a',
'tooltip-n-recentchanges'         => 'Lis modifikasyon ki fèk fèt nan wiki a',
'tooltip-n-randompage'            => 'Afiche yon paj o aza',
'tooltip-n-help'                  => 'Èd',
'tooltip-n-sitesupport'           => 'Soutni pwojè a',
'tooltip-t-whatlinkshere'         => 'Lis paj yo ki lye ak paj sa a',
'tooltip-t-contributions'         => 'Wè lis kontribisyon itilizatè sa a',
'tooltip-t-emailuser'             => 'Voye yon imèl pou itilizatè sa a',
'tooltip-t-upload'                => 'Chaje yon imaj oubyen yon fichye medya anlè sèvè a',
'tooltip-t-specialpages'          => 'Lis tout paj espesyal yo',
'tooltip-ca-nstab-user'           => 'Wè paj itilizatè',
'tooltip-ca-nstab-project'        => 'Wè paj pwojè a',
'tooltip-ca-nstab-image'          => 'Wè paj imaj an',
'tooltip-ca-nstab-template'       => 'Wè modèl an',
'tooltip-ca-nstab-category'       => 'Wè paj kategori a',
'tooltip-minoredit'               => 'make modifikasyon sa a pa enpòtan',
'tooltip-save'                    => 'Sove modifikasyon ou yo',
'tooltip-preview'                 => 'Souple, gade paj ou an (previzyalize li) anvan ou anrejistre li',
'tooltip-diff'                    => 'Montre ki chanjman ou fè nan tèks an.',
'tooltip-compareselectedversions' => 'Afiche diferans ant de vèsyon paj sa a ou seleksyone.',
'tooltip-watch'                   => 'Ajoute paj sa a nan lis swivi ou an',

# Spam protection
'subcategorycount'       => 'Genyen {{PLURAL:$1| yon sou-kategori ki liste |$1 sou-kategori ki liste}} anba kategori sa a.',
'categoryarticlecount'   => 'Genyen {{PLURAL:$1|yon atik|$1 atik}} nan kategori sa a.',
'category-media-count'   => 'Genyen {{plural:$1|yon fichye|$1 fichye}} miltimedya nan kategori sa a.',
'listingcontinuesabbrev' => '(kontinye)',

# Browsing diffs
'previousdiff' => '← Diferans anvan',
'nextdiff'     => 'Diferans swivan →',

# Media information
'file-info-size'       => '($1 × $2 piksèl, lajè fichye a : $3, tip MIME li ye : $4)',
'file-nohires'         => '<small>Pa genyen rezolisyon ki pli wo ki disponib.</small>',
'svg-long-desc'        => '(Fichye SVG, rezolisyon pou de $1 × $2 piksèl, lajè : $3)',
'show-big-image'       => 'Imaj pli gwo, pli fin',
'show-big-image-thumb' => '<small>Lajè kout zye sa a : $1 × $2 piksèl</small>',

# Bad image list
'bad_image_list' => 'Fòma a, se konsa li ye :

Sèl liy ki komanse pa * ke konte.  Premye lyen nan liy an se sa ki ap mene kote yon move imaj.
Lòt lyen yo nan menm liy an ke ap konsidere tankou eksepsyon, pa egzanp atik nan kilès yo imaj an dwèt parèt.',

# Metadata
'metadata'          => 'Metadone',
'metadata-help'     => 'Fichye sa genyen plis enfòmasyon ki petèt te ajoute pa aparèy foto ou an oubyen eskanè ki pwodui li. Si fichye a te modifye, kèk detay pa kapab koresponn ak imaj ki modifye a.',
'metadata-expand'   => 'Montre tout enfòmasyon',
'metadata-collapse' => 'Kache enfòmasyon ak tout detay sa yo',
'metadata-fields'   => 'Chan metadone sa yo pou EXIF ki liste nan mesaj sa a ke nan paj deskripsyon imaj an lè tab metadone a ke pli piti. Lòt chan yo ke ap kache.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Modifye fichye sa a epi yon aplikasyon pa ou (ki pa nan sistèm an, sou machin ou pa egzanp).',
'edit-externally-help' => 'Wè [http://meta.wikimedia.org/wiki/Help:External_editors komannd ak enstriksyon yo] pou plis enfòmasyon oubyen pou konnen plis.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tout',
'namespacesall' => 'Tout',
'monthsall'     => 'tout',

# Watchlist editing tools
'watchlisttools-view' => 'Lis swivi',
'watchlisttools-edit' => 'Wè epi modifye tou lis swivi',
'watchlisttools-raw'  => 'Modifye lis swivi (mòd bazik)',

);
