<?php
/** Picard (Picard)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Geoleplubo
 * @author Hercule
 */

$fallback = 'fr';

$messages = array(
# User preference toggles
'tog-underline'               => 'Loïens soulinés:',
'tog-justify'                 => 'Aligner ches paragrafes',
'tog-hideminor'               => 'Muche ches tiots edits din ches nouvieus cangemints',
'tog-hidepatrolled'           => 'Mucher chés wardés canjemints din chés nouvieus canjemints',
'tog-newpageshidepatrolled'   => 'Muche ches paches pormenées del lisse ed ches nouvèles paches',
'tog-extendwatchlist'         => "Étènne l'lisse pou vir tortous ches cangemints, poin seulemint ches nouvieus",
'tog-numberheadings'          => 'liméro automatique ed ches intétes',
'tog-showtoolbar'             => "Afiquer chés otis pou l'édichon (i feut JavaScript)",
'tog-editondblclick'          => 'Éditer ches paches aveuc un doube buke (i feut JavaScript)',
'tog-editsection'             => "Pérmet l'édichion del sekchion via [edit] loïens",
'tog-editsectiononrightclick' => "Pérmet l'édichion del sekchion par un droé buke su ch'tite del sekchion (i feut JavaScript)",
'tog-showtoc'                 => "Aficher l'tabe ed ches étnus (pou ches paches aveuc plu ed 3 intétes)",
'tog-rememberpassword'        => "Warder min lodjine su chl'ordinateu-lo (for a maximum of $1 {{PLURAL:$1|day|days}})",
'tog-watchcreations'          => "Ajouter ches paches qu'éj crée su em lisse",
'tog-watchdefault'            => "Ajouter ches paches qu'éj édite su em lisse.",
'tog-watchmoves'              => "Ajouter ches paches qu'éj déplache su m'lisse.",
'tog-watchdeletion'           => "Ajouter ches paches qu'éj déface su m'lisse.",
'tog-previewontop'            => "Aficher l'prévue édvint el bouéte édite",
'tog-previewonfirst'          => "Aficher l'prévue au preumié édite.",
'tog-enotifwatchlistpages'    => "Éspédier din m'boéte un imèle quante eune pache su m'lisse est candgée",
'tog-enotifusertalkpages'     => 'Éspédier un imèle su em bouéte quante m\'pache "Dvise Uzeu" est candgée.',
'tog-enotifminoredits'        => 'Éspédier à mi étous un imèle pou ches tiots édites éd ches paches',
'tog-shownumberswatching'     => "Aficher ch'nombe ed gins qu'ont vu.",
'tog-watchlisthideown'        => 'Muche mes édites su el lisse',
'tog-watchlisthidebots'       => 'Muche ches robots édites su el lisse',
'tog-watchlisthideminor'      => 'Muche ches tiots édites su el lisse.',
'tog-watchlisthideliu'        => 'Muche ches édites ed ches lodjés gins su el lisse.',
'tog-watchlisthideanons'      => 'Muche ches édites ed ches gins annonimes su el lisse.',
'tog-watchlisthidepatrolled'  => 'Muche ches édites pormenés su el lisse.',
'tog-ccmeonemails'            => "Éspédier din m'bouéte ches copies ed ches imèles éq j'éspédie à ches autes uzeus",
'tog-diffonly'                => "N'poin aficher chl'étnu del pache édsou diffs",
'tog-showhiddencats'          => 'Foaire vir chés muchées catégories',

'underline-always' => 'Toudis',
'underline-never'  => 'Janmoais',

# Dates
'sunday'        => 'Diminche',
'monday'        => 'Lindi',
'tuesday'       => 'Mardi',
'wednesday'     => 'Mérkédi',
'thursday'      => 'Judi',
'friday'        => 'Verdi',
'saturday'      => 'Sinmedi',
'sun'           => 'Dim',
'mon'           => 'Lin',
'tue'           => 'Mar',
'wed'           => 'Mér',
'thu'           => 'Jud',
'fri'           => 'Ver',
'sat'           => 'Sin',
'january'       => 'ed Janvié',
'february'      => 'ed Févrié',
'march'         => 'ed Marche',
'april'         => "d'Avri",
'may_long'      => 'ed Moai',
'june'          => 'ed Join',
'july'          => 'ed Juillet',
'august'        => "d'Aout",
'september'     => 'ed Sétimbe',
'october'       => "d'Octobe",
'november'      => 'ed Novimbe',
'december'      => 'ed Déchimbe',
'january-gen'   => 'Janvié',
'february-gen'  => 'Févrié',
'march-gen'     => 'Marche',
'april-gen'     => 'Avri',
'may-gen'       => 'Moai',
'june-gen'      => 'Join',
'july-gen'      => 'Juillet',
'august-gen'    => 'Aout',
'september-gen' => 'Sétimbe',
'october-gen'   => 'Octobe',
'november-gen'  => 'Novimbe',
'december-gen'  => 'Déchimbe',
'jan'           => 'Jan',
'feb'           => 'Fév',
'mar'           => 'Mar',
'apr'           => 'Avr',
'may'           => 'Moa',
'jun'           => 'Joi',
'jul'           => 'Jui',
'aug'           => 'Aou',
'sep'           => 'Sét',
'oct'           => 'Oct',
'nov'           => 'Nov',
'dec'           => 'Déc',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Catégorie|Catégories}}',
'category_header'          => 'Paches in catégorie "$1"',
'subcategories'            => 'Dsoucatégories',
'category-media-header'    => 'Média in catégorie "$1"',
'category-empty'           => "''Din l'catégorie-lo, i n'y o poin d'paches ou d'média.''",
'hidden-categories'        => '{{PLURAL:$1|Catégorie muchée|Catégories muchées}}',
'hidden-category-category' => 'Catégouries muchées',
'category-subcat-count'    => "{{PLURAL:$2|Chol catégorie o seulemint el sou-catégorie-lo.|Chol catégorie o  {{PLURAL:$1|l'sou-catégorie-lo|$1 sou-catégories}}, pou un total éd $2.}}",
'category-article-count'   => "{{PLURAL:$2|Chol catégorie o seulemint chol pache-lo.|{{PLURAL:$1|El pache-lo est|$1 Chés paches-lo sont}} din l'catégorie-lo, pou un total éd $2 .}}",
'listingcontinuesabbrev'   => 'cont.',

'mainpagetext' => "'''MediaWiki o té instalé aveuc victoère.'''",

'about'         => 'À pérpos',
'article'       => 'Étnu del pache',
'newwindow'     => '(ouvrir din eune nouvèle fernéte)',
'cancel'        => 'Canchler',
'moredotdotdot' => 'Plu...',
'mypage'        => 'Em pache',
'mytalk'        => 'Mi bavouér',
'anontalk'      => "Bavouér pou chl'IP-lo",
'navigation'    => 'Navigachon',
'and'           => '&#32;pi',

# Cologne Blue skin
'qbfind'         => 'Trouvoèr',
'qbbrowse'       => 'Trifouille',
'qbedit'         => 'Editer',
'qbpageoptions'  => 'Chol pache-lo',
'qbpageinfo'     => 'Conteske',
'qbmyoptions'    => 'Mes paches',
'qbspecialpages' => 'Espéciales paches',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Rajouter un sujeut',
'vector-action-delete'           => 'Défacer',
'vector-action-move'             => "Canger ch'nom",
'vector-action-protect'          => 'Garantir',
'vector-action-undelete'         => "N'poin défacher",
'vector-action-unprotect'        => "N'poin garantir",
'vector-simplesearch-preference' => "Déhousser chés avanches d'ércherche améliorées (seulemint pour Vector)",
'vector-view-create'             => 'Créer',
'vector-view-edit'               => 'Éditer',
'vector-view-history'            => "Vir l'histoère",
'vector-view-view'               => 'Lire',
'vector-view-viewsource'         => "Vir l'source",
'actions'                        => 'Acchons',
'namespaces'                     => "Éspaces d'chés noms",
'variants'                       => 'Ércanjantes',

'errorpagetitle'    => 'Bérlure',
'returnto'          => 'Értrouve $1.',
'tagline'           => 'Cha vient éd {{SITENAME}}',
'help'              => 'Aïude',
'search'            => 'Tracher',
'searchbutton'      => 'Tracher',
'go'                => 'Aller',
'searcharticle'     => 'Aller',
'history'           => 'Pache historique',
'history_short'     => 'Histoère',
'updatedmarker'     => 'Cangé édpui em darinne visite',
'info_short'        => 'Informachion',
'printableversion'  => 'Imprimabe vérchon',
'permalink'         => 'Loïen pérmanint',
'print'             => 'Imprimer',
'edit'              => 'Éditer',
'create'            => 'Créer',
'editthispage'      => "Éditer chl'pache-lo",
'create-this-page'  => "Créer chl'pache lo",
'delete'            => 'Défacer',
'deletethispage'    => "Défacer chl'pache lo",
'undelete_short'    => 'Déface poin {{PLURAL:$1|un édite|$1 édites}}',
'protect'           => 'Garantir',
'protect_change'    => 'canger',
'protectthispage'   => "Défènner l'pache",
'unprotect'         => 'Mie défènné',
'unprotectthispage' => "N'poin garantir chol pache",
'newpage'           => 'Nouvèle pache',
'talkpage'          => "Alédjer l'pache-lo",
'talkpagelinktext'  => 'Dviser',
'specialpage'       => 'Pache éspéchiale',
'personaltools'     => 'Otis dech uzeu',
'postcomment'       => 'Nouvèle sekchion',
'articlepage'       => 'Vir el pache ed ches étnus',
'talk'              => 'distchuter',
'views'             => 'Vues',
'toolbox'           => 'Boéte à otis',
'userpage'          => 'Vir el pache dech uzeu',
'projectpage'       => "Vir l'pache dech prodjé",
'imagepage'         => 'Vir el pache dech fichié',
'mediawikipage'     => 'Vir messache pache',
'templatepage'      => 'Vir el pache dech modéle',
'viewhelppage'      => 'Vir aïude pache',
'categorypage'      => "Vir l'pache éd chés catégories",
'viewtalkpage'      => 'Vir distchussion',
'otherlanguages'    => "Din d'eutes langaches",
'redirectedfrom'    => '(Érdirection édpis $1)',
'redirectpagesub'   => 'Pache érdérivée',
'lastmodifiedat'    => "L'pache-lo ale o té modifiée l'fouos darin l' $1, à $2.",
'protectedpage'     => 'Pache défènnée',
'jumpto'            => 'Aler à:',
'jumptonavigation'  => 'navigachon',
'jumptosearch'      => 'tracher',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'à pérpos éd {{SITENAME}}',
'aboutpage'            => 'Project:à pérpos',
'copyright'            => "Ch'contnu, il est disponipe dsou $1.",
'copyrightpage'        => '{{ns:project}}:Copyrights',
'currentevents'        => 'Darinnetés picardes',
'currentevents-url'    => 'Project:Darinnetés picardes',
'disclaimers'          => 'Démintis',
'disclaimerpage'       => 'Project:Déminti général',
'edithelp'             => 'Éditer el aiyude',
'edithelppage'         => 'Help:Édichion',
'helppage'             => 'Help:Étnus',
'mainpage'             => 'Moaite Pache',
'mainpage-description' => 'Moaite Pache',
'portal'               => 'Portal del conmeunauté',
'portal-url'           => 'Project:Accueul del conminnité',
'privacy'              => "Politique d'éscrè",
'privacypage'          => "Project:Politique d'éscrè",

'badaccess' => 'Bérlure éd pérmission',

'ok'                      => 'OK',
'retrievedfrom'           => 'Érprind din  "$1"',
'youhavenewmessages'      => 'Os avez $1 ($2).',
'newmessageslink'         => 'nouvieus messaches',
'newmessagesdifflink'     => 'darin cangemint',
'youhavenewmessagesmulti' => 'Os avez des nouvieus messaches su $1',
'editsection'             => 'éditer',
'editold'                 => 'éditer',
'viewsourceold'           => "vir l'source",
'editlink'                => 'édite',
'viewsourcelink'          => 'vir el source',
'editsectionhint'         => 'Éditer el sekchon: $1',
'toc'                     => 'Étnus',
'showtoc'                 => 'Aficher',
'hidetoc'                 => 'muche',
'thisisdeleted'           => 'Vir ou érfoaire $1?',
'viewdeleted'             => 'Vir $1?',
'restorelink'             => '{{PLURAL:$1|eune édition défacée|$1 chés éditions défacées}}',
'feedlinks'               => 'Pipe:',
'feed-unavailable'        => "I n'y o poin ed sindicachion ed ches pipes",
'site-rss-feed'           => '$1 RSS Fil',
'site-atom-feed'          => '$1 Atom Fil',
'page-rss-feed'           => '"$1" RSS Fil',
'page-atom-feed'          => '"$1" Atom Fil',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => "$1 (el pache, ale n'écsiste mie)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pache',
'nstab-user'      => 'Pache dech uzeu',
'nstab-media'     => 'Média pache',
'nstab-special'   => 'Pache éspéchiale',
'nstab-project'   => 'Pache éd prodjé',
'nstab-image'     => 'Fichié',
'nstab-mediawiki' => 'Messache',
'nstab-template'  => 'Modéle',
'nstab-help'      => "Pache d'aiyude",
'nstab-category'  => 'Catégorie',

# Main script and global functions
'nosuchspecialpage' => "I n'y o poin chot éspéchiale pache-lo",

# General errors
'error'                => 'Bérlurache',
'databaseerror'        => "Bérlurache din l'database",
'laggedslavemode'      => "'''Afute:''' Pététe éq l'pache-lo n'o poin chés darins canjemints.",
'missing-article'      => "El base éd dounées n'o poin treuvé ech teske d'eune pache éq ale d'vroait treuver, aveuc ch'nom  \"\$1\" \$2. <br />  Généralemint, ch'est pasqué in o sui eune anthieusse diff o bin un histourique érlié aveuc eune pache défachée.

Si s'n'est poin ch'cas-lo, pététe éq ch'est un bogue din ch'businkillache. <br /> I feut signaler ch'probléme-lo à un [[Special:ListUsers/sysop|administrateu]], aveuc l'URL.",
'missingarticle-rev'   => '(révision#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'internalerror'        => 'Bérlurache intérne',
'internalerror_info'   => 'Bérlurache intérne: $1',
'filecopyerror'        => 'Éj pux poin copier ch\'fichié "$1" su "$2".',
'filerenameerror'      => 'Éj pux poin canger ch\'nom dech fichié "$1" su "$2".',
'filedeleteerror'      => 'Éj pux poin défacer ch\'fichié "$1".',
'directorycreateerror' => 'Éj pux poin créer ch\'répértoère "$1".',
'filenotfound'         => 'Éj pux poin trouvoér ch\'fichié "$1".',
'fileexistserror'      => 'Éj pux poin écrire su ch\'fichié "$1": ech fichié écsiste',
'unexpected'           => 'Valeur poin prévue: "$1"="$2".',
'badarticleerror'      => "Os n'povez poin foaire cha su l'pache-lo.",
'badtitle'             => 'Méchant tite',
'badtitletext'         => "Ch'tite del pache écmindée n'est poin valabe, est vide, ou bin ch'est un tite inter-langue ou inter-proujé aveuc des méchands loïens. Pététe qu'il y o un ou des caractére(s) éq i feut poin mette din chés tites.",
'viewsource'           => "Vir l'source",
'viewsourcefor'        => 'pou $1',
'protectedpagetext'    => "L'pache-lo ale o té garantie pou impétcher chés canjemints.",
'sqlhidden'            => "(l'édminde SQL est muchée)",
'ns-specialprotected'  => "Ches paches éspéchiales, is n'peute poin éte éditées.",

# Virus scanner
'virus-unknownscanner' => 'intivirus poin connu:',

# Login and logout pages
'yourname'                => "nom d'uzeu:",
'yourpassword'            => "Mot d'passe:",
'yourpasswordagain'       => "Intrer à nouvieu ch'mot d'passe:",
'remembermypassword'      => "Intrer oùtonmatiquemint l'prochaine fouos (pour un maximum éd $1 {{PLURAL:$1|jour|jours}})",
'yourdomainname'          => 'Vote donmène:',
'login'                   => 'Intrer',
'nav-login-createaccount' => 'Intrer / créer vote conpte',
'loginprompt'             => 'I feut avoèr dés coukies pou pouvoèr intrer din {{SITENAME}}.',
'userlogin'               => 'Intrer / créer vote conpte',
'logout'                  => 'Sortir',
'userlogout'              => 'Sortir',
'notloggedin'             => 'Poin connékté',
'nologin'                 => "os n'avez mie un conpte? '''$1'''.",
'nologinlink'             => 'Créer un conpte',
'createaccount'           => 'Créer un conpte',
'gotaccount'              => "Jou qu'os avez piécha un conpte? '''$1'''.",
'gotaccountlink'          => 'Intrer',
'createaccountmail'       => 'par imèle',
'badretype'               => "Chés mots d'passe intrés, is sont poin bon.",
'userexists'              => "ch'nom d'uzeu intré, il est piécha donné.

j'm'escuse mais i feut prinde un aute nom.",
'loginerror'              => 'Bérlurache del intrée',
'noname'                  => "Os n'avez poin donné un nom d'uzeu valabe.",
'loginsuccess'            => "'''Achteur os ètes intré{{GENDER:||e|(e)}} din {{SITENAME}} conme \"\$1\".'''",
'nouserspecified'         => "Os dvez intrer un nom d'uzeu.",
'mailmypassword'          => "Imèle un nouvieu mot d'passe",
'passwordremindertitle'   => "Nouvieu mot d'passe tanporoère pou {{SITENAME}}",
'noemail'                 => "I n'y o poin d'adél pou echl' uzeu  « $1 ».",
'accountcreated'          => "Ch'conpte est créé",
'accountcreatedtext'      => "Ech conpte d'uzeu pou $1 o té créé.",
'loginlanguagelabel'      => 'Langache: $1',

# Password reset dialog
'resetpass'                 => "Canger ch'mot d'passe",
'resetpass_header'          => "Canger ch'mot d'passe dech conpte",
'oldpassword'               => "Anthiu mot d'passe:",
'newpassword'               => "Nouvieu mot d'passe:",
'resetpass_forbidden'       => "Chés mots d'passe is n'peu'te poin ète cangés",
'resetpass-submit-loggedin' => "Canger ch'mot d'passe",

# Edit page toolbar
'bold_sample'     => 'Cros teske',
'bold_tip'        => 'Cros teske',
'italic_sample'   => 'Teske italique',
'italic_tip'      => 'Teske italique',
'link_sample'     => 'Tite dech loïen',
'link_tip'        => 'Loïen intérne',
'extlink_sample'  => 'http://www.example.com tite dech loïen',
'extlink_tip'     => "Éstérne loïen ( n'obliez mie ech préfix http:// )",
'headline_sample' => 'Teske dechl in-téte',
'headline_tip'    => 'In-téte nivieu 2',
'math_sample'     => "Mètte l'formule ichi",
'math_tip'        => 'Formule matématike (LaTeX)',
'nowiki_sample'   => "Placher ch'teske non-formaté ichi",
'nowiki_tip'      => "Poin d'format wiki",
'image_tip'       => 'fichié incorporé',
'media_tip'       => 'Loïen dech fichié',
'sig_tip'         => "Vo pataraf aveuc l'date",
'hr_tip'          => 'line orizontale (imploéïer aveuc modérachon)',

# Edit pages
'summary'                          => 'Résumè:',
'subject'                          => 'Sujet/in-téte:',
'minoredit'                        => "Ch'est eune tiote édition",
'watchthis'                        => "Suire l'pache-lo",
'savearticle'                      => "Seuver l'pache",
'preview'                          => 'Prévir',
'showpreview'                      => "Fouaire vir l'prévue",
'showdiff'                         => 'Montrer chés cangemints',
'anoneditwarning'                  => "'''Wàrte ! :''' Vos n'ètes poin lodjé.

Vote adrèche IP, ale sro inrégistrée din l'historique éd chol pache.",
'summary-preview'                  => 'Prévue dech résumè :',
'newarticle'                       => '(nouvieu)',
'newarticletext'                   => "Os avez sui un loïen vers eune pache qui n’essiste poin coère ou qu' o té [{{fullurl:Special:Log|type=delete&page={{FULLPAGENAMEE}}}} défacée].
Pou créer chol pache, intrez vote teske din l'boéte édsou (vir [[{{MediaWiki:Helppage}}|l'pache d’aïude]] ). <br />
Si vos ètes ichi par bérlure, bukez su l'bouton '''értour''' du navigateu.",
'noarticletext'                    => 'Achteure i n’y o nu teske su l\'pache-lo.
Os povez [[Special:Search/{{PAGENAME}}|foaire eune érchérche du tite del pache]] din chés eutes paches,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} érchércher din chés érliées opéracions]
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} créer chol pache]</span>.',
'previewnote'                      => "'''Afute! ch'teske-lo ch'est seulemint eune prévue.'''

Vos cangemints, is sont poin coèr inrégistrés!",
'editing'                          => 'Éditer $1',
'editingsection'                   => '$1 éditée (sekchon)',
'yourtext'                         => 'Vote teske',
'copyrightwarning'                 => "Toutes chés contérbuchons su {{SITENAME}} ont érbéyées conme publiées dsou chés térmes del $2 (vir $1 pou pus d'détals). Si vos n'volez poin éq vos écrivures euchette canjés pi départis à volontè, mérci éd n'poin les soumétte ichi.<br />
Os prométtez auchi éq vos avez écrit ch'teske vous-méme, ou éq vos l’avez ércopié d’eune source din ch'donmène public, ou d’eune libe érsource.<br /> '''N’IMPLOÉYEZ POIN D'TRAVAUX ÉDSOU DROÉ D’AUTEU SINS ACOR ÉSPRÉSSE !'''",
'templatesused'                    => '{{PLURAL:$1|Modéle imploïé|Modéles imploïés}} pou chol pache:',
'templatesusedpreview'             => '{{PLURAL:$1|Modéle imploïé|Modéles imploïés}} din chol prévue-lo:',
'template-protected'               => '(garanti)',
'template-semiprotected'           => '(semi-garanti)',
'hiddencategories'                 => '{{PLURAL:$1|Catégorie muchée|Catégories muchées}} pou chol pache:',
'permissionserrorstext-withaction' => "Vos n’avez poin l'pérmichon éd $2, pou {{PLURAL:$1|ch'motif suivant|chés motifs suivants}}:",

# History pages
'viewpagelogs'           => 'Vir chés gasètes del pache-lo',
'currentrev-asof'        => 'Coursaule vérchon in date du $1',
'revisionasof'           => 'Ércordé conme $1',
'previousrevision'       => '← érvue dvant',
'nextrevision'           => 'Cangemint pu nouvieu →',
'currentrevisionlink'    => 'Érvision éd qhére',
'cur'                    => 'cour',
'last'                   => 'dvant',
'page_first'             => 'preumié',
'page_last'              => 'darin',
'histlegend'             => "Diff séléccion: buke chés boétes d'chés canjemints à comparète pi détriquer intrer ou ch'bouton édsou.<br />
Léginde : ({{MediaWiki:Cur}}) = différinches aveuc el vérchon à ch'momint-chi, ({{MediaWiki:Last}}) = différinches aveuc el vérchon édvant, <b>m</b> = tiot canjemint.",
'history-fieldset-title' => "S'déplacher din l'historique",
'histfirst'              => 'preumières paches',
'histlast'               => 'Darin',

# Revision deletion
'rev-delundel'   => 'montrer/mucher',
'revdel-restore' => 'cange écmint vir',
'pagehist'       => 'Histoère del pache',

# Merge log
'revertmerge' => "N'poin mélinger",

# Diffs
'history-title'           => 'Histoère des cangemints éd "$1"',
'difference'              => '(Diférinche intre chés érvisions)',
'lineno'                  => 'Line $1:',
'compareselectedversions' => 'Compérer chés couésies contérbuchons',
'editundo'                => "n'poin foaire",

# Search results
'searchresults'             => 'Tracher chés résultats',
'searchresults-title'       => 'Tracher chés résultats pou "$1"',
'searchresulttext'          => "Pou pus d'informachons quant qu'vos trachez {{SITENAME}}, vir [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'            => "Vos trachez  « '''[[:$1]]''' » ([[Special:Prefixindex/$1|toutes chés paches aroutant pèr « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|toutes chés paches qu'ont un loïen dsus « $1 »]])",
'searchsubtitleinvalid'     => "vos trachez apreu '''$1'''",
'notitlematches'            => "Éj déniche mie d'pache aveuc ch'tite-lo",
'textmatches'               => 'Teske del pache déniché',
'notextmatches'             => "I n'y o poin d'pache aveuc ch'teske-lo",
'prevn'                     => 'dvant {{PLURAL:$1|$1}}',
'nextn'                     => 'apreu {{PLURAL:$1|$1}}',
'prevn-title'               => 'Dvant $1 {{PLURAL:$1|résultat|résultats}}',
'viewprevnext'              => 'Vir ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 mot|$2 mots}})',
'search-redirect'           => '(érdirection $1)',
'search-section'            => '(sekchon $1)',
'search-suggest'            => 'Cha vo ti dire: $1',
'search-interwiki-caption'  => 'Proujé analocq',
'search-interwiki-default'  => '$1 résultats:',
'search-interwiki-more'     => '(pus)',
'search-mwsuggest-enabled'  => 'aveuc avanches',
'search-mwsuggest-disabled' => "mie d'avanches",
'nonefound'                 => "'''Note''': il y o tasseulemint quéques éspaces éd noms éq sont trachés pèr défeut. <br /> Pou tracher din tous chés contnus (paches éd pérlache, modéles, etc... comprins) insséyer in imploéyant ch'préfixe ''all:'' o bin imploéyer echl éspace éd noms édmindé conme préfixe.",
'powersearch'               => 'Érvue avanchée',
'powersearch-legend'        => 'Érvue avanchée',
'powersearch-ns'            => 'Tracher din chés éspaches éd chés noms:',
'powersearch-redir'         => "Lisse d'chés érdirécchons",
'powersearch-field'         => 'Tracher pou',

# Preferences page
'preferences'               => 'Préférinches',
'mypreferences'             => 'Mes préférinches',
'timezoneregion-europe'     => 'Urope',
'youremail'                 => 'Imèle:',
'username'                  => "Nom d'uzeu:",
'uid'                       => 'ID dech uzeu:',
'prefs-memberingroups'      => 'Mimbe éd {{PLURAL:$1|groupe|groupes}}:',
'yourrealname'              => 'Vrai nom:',
'yourlanguage'              => 'Langache:',
'badsiglength'              => 'Vote signature est gramint longue.
Ale doét mie éte pu longue éq $1 {{PLURAL:$1|caracter|caractéres}}.',
'gender-male'               => 'Marle',
'gender-female'             => 'Femelle',
'email'                     => 'Imèle',
'prefs-help-email-required' => 'I feut eune iméle adérche',

# User rights
'userrights-groupsmember' => 'Mimbe éd:',

# Groups
'group-sysop'      => 'Aménistrateus',
'group-bureaucrat' => 'Buroécrates',

'group-sysop-member'      => 'Aménistrateu',
'group-bureaucrat-member' => 'Buroécrate',

'grouppage-sysop' => '{{ns:project}}:Aménistrateus',

# User rights log
'rightslog' => "Jornal d'chés droés dechl uzeu",

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => "Vir l'pache-lo",
'action-edit' => "édite l'pache-lo",

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|cange|canges}}',
'recentchanges'                  => 'Darins canjemints',
'recentchanges-legend'           => 'Opchons éd chés nouvieus canjemints',
'recentchanges-feed-description' => 'Tracher chés pus darins cangemints du wiki din chol alimintachon.',
'rcnote'                         => "Vlo {{PLURAL:$1|ech darin canjemint foait|chés $1 darins canjemints foaits}} din {{PLURAL:$2|l'darinne jornèe|chés <b>$2</b> darins jours}} dusque  l' $4 à $5.",
'rclistfrom'                     => "Montrer chés nouvieus cangemints d'puis $1",
'rcshowhideminor'                => '$1 tiotes éditions',
'rcshowhidebots'                 => '$1 bots',
'rcshowhideliu'                  => '$1 lodjés uzeus',
'rcshowhideanons'                => '$1 uzeus anonimes',
'rcshowhidemine'                 => '$1 ems éditions',
'rclinks'                        => 'Afiqher chés $1 darins canjemints din chés $2 darins jours<br />$3',
'diff'                           => 'dif',
'hist'                           => 'hist',
'hide'                           => 'Mucher',
'show'                           => 'Montrer',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Montrer chés détals (i feut avoér JavaScript)',
'rc-enhanced-hide'               => 'Mucher chés détals',

# Recent changes linked
'recentchangeslinked'         => 'Darins canjemints érliés',
'recentchangeslinked-title'   => 'Cangemints à pérpos éd "$1"',
'recentchangeslinked-summary' => "Ch'est eune lisse d'chés darins canjemints su chés paches qu'ont un loïen aveuc l'pache-lo. Chés paches din vote [[Special:Watchlist|''lisse à suire'']] il sont in '''cros'''.",
'recentchangeslinked-page'    => 'Nom del pache:',
'recentchangeslinked-to'      => "Vir putot chés canjemints d'chés paches aveuc un loïen su l'pache-lo",

# Upload
'upload'        => 'Quértcher chés fichiés',
'uploadlogpage' => 'Jornal éd chés quértchémints',
'uploadedimage' => '"[[$1]]" quértchée',

# File description page
'filehist'                  => 'Histoère dech fichié',
'filehist-help'             => "Buke su eune date/heure pou vir ch'fichié conme il étoait ach momint-lo.",
'filehist-current'          => 'courant',
'filehist-datetime'         => 'Date/Tans',
'filehist-thumb'            => 'Tiote image',
'filehist-thumbtext'        => "Image pou l'vérchon éd $1",
'filehist-user'             => 'Uzeu',
'filehist-dimensions'       => 'Diminsions',
'filehist-comment'          => 'Fichié éd chés conmints',
'imagelinks'                => 'Loïens dech fichié',
'linkstoimage'              => "{{PLURAL:$1|L'pache d'apreu est liée|Chés $1 paches d'apreu sont liées}} à ch'fichié-lo :",
'sharedupload'              => "Cht'fichié vient éd $1 pi i put ète imploïé par d'eutes proujés.",
'uploadnewversion-linktext' => 'Quértcher eune novèle vérchion del pache-lo',

# Random page
'randompage' => "Pache à l'bérlure",

# Statistics
'statistics' => 'Éstatistikes',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|octé|octés}}',
'nmembers'      => '$1 {{PLURAL:$1|mimbe|mimbes}}',
'prefixindex'   => 'Tertous chés paches aveuc préfix',
'newpages'      => 'Novèles paches',
'move'          => 'Déplacher',
'movethispage'  => "Déplacher l'pache-lo",
'pager-newer-n' => '{{PLURAL:$1|pu nouvieu 1|pu nouvieus $1}}',
'pager-older-n' => '{{PLURAL:$1|pus viu 1|pus vius $1}}',

# Book sources
'booksources'               => 'Sources dech live',
'booksources-search-legend' => "Tracher chés référinches d'chés lives",
'booksources-go'            => 'Aler',

# Special:Log
'log' => 'Gasètes',

# Special:AllPages
'allpages'          => 'Tertous chés paches',
'alphaindexline'    => '$1 à $2',
'prevpage'          => 'Pache édvant ($1)',
'allpagesfrom'      => 'Afiquer chés paches éq partent à:',
'allpagesto'        => "Foaire vir chés paches qui s'términette à:",
'allarticles'       => 'Tertous chés artikes',
'allinnamespace'    => 'Tertous chés paches ($1 namespace)',
'allnotinnamespace' => 'Tertous chés paches (mie din $1 namespace)',
'allpagesprev'      => "D'vant",
'allpagesnext'      => "D'apreu",
'allpagessubmit'    => 'Aler',
'allpagesprefix'    => "Foaire vir chés paches aveuc ch'préfix:",

# Special:LinkSearch
'linksearch' => 'Loïens éstérieurs',

# Special:Log/newusers
'newuserlogpage'          => 'Jornal del créachon pou echl uzeu',
'newuserlog-create-entry' => "Nouvieu conpte d'uzeu",

# Special:ListGroupRights
'listgrouprights-members' => '(lisse éd chés mimbes)',

# E-mail user
'emailuser' => "Imèle echl'uzeu-lo",
'emailpage' => 'Imèle dech uzeu',

# Watchlist
'watchlist'         => 'Em lisse à suire',
'mywatchlist'       => "M'lisse à suire",
'addedwatch'        => "Rajouté su l'lisse à suire",
'addedwatchtext'    => "L' pache « [[:$1]] » o té rajoutée à vote [[Special:Watchlist|lisse à suire]].<br /> Chés  canjemints à vnir del pache-lo pi del page éd pérlache sront mis din l'lisse. L'pache sro '''in cros''' din el [[Special:RecentChanges|lisse d'chés darins canjemints]] pou les értreuver fachilmint. Pou értirer chol pache del ''lisse à suire'', bukez su « {{MediaWiki:Unwatch}} ».",
'removedwatch'      => 'Értiré del lisse à suire',
'removedwatchtext'  => "L'pache « [[:$1]] » o té értirée éd vote [[Special:Watchlist|lisse à suire]].",
'watch'             => 'Suire',
'watchthispage'     => "Suire l'pache-lo",
'unwatch'           => "N'poin suire",
'watchlist-details' => "{{PLURAL:$1|$1 pache|$1 paches}} din vote lisse à suire, chés paches éd disqhuchon n'sont poin conptées.",
'wlshowlast'        => 'Montrer darin $1 eûres $2 jours $3',
'watchlist-options' => 'Opchons del lisse à suire',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Suire…',
'unwatching' => "n'poin suire…",

# Delete
'deletepage'            => "Défacer l'pache",
'confirmdeletetext'     => "Vos alez défacer eune pache ou un fichié aveuc toutes chés antieusses vérchons.<br /> Confreumer éq ch'est cho éq vos voulez foaire, éq vos conprindez chés consécanches et pi éq ch'est bin s'lon el [[{{MediaWiki:Policy-url}}|politique éd MédiaWiki]].",
'actioncomplete'        => 'Plònne acchon',
'deletedtext'           => "« <nowiki>$1</nowiki> » o té défacé.
Vir $2 pou eune lisse d'chés darinnes défachons.",
'deletedarticle'        => 'défacé "[[$1]]"',
'dellogpage'            => 'jornal éd chés défacions',
'deletecomment'         => 'Motif:',
'deleteotherreason'     => 'Motif eute/suplémintère :',
'deletereasonotherlist' => 'Eute motif',

# Rollback
'rollbacklink'   => 'èrtour',
'rollbackfailed' => 'Értour loupé',
'cantrollback'   => "éj peus mie invérser l'édition;
ch'darin contérbucheu, ch'est ch'seu auteur del pache-lo.",
'alreadyrolled'  => "éj pus mie invérser el darin édition éd [[:$1]] par [[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
queuque-un il o édité ou invérsé l'pache déjo.

L' passèie édition del pache étoait par  [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",

# Protect
'protectlogpage'              => 'Gasète éd chés protéccions',
'protectedarticle'            => '"[[$1]]" est garanti',
'modifiedarticleprotection'   => 'canger ch\'nivieu d\'protékchon pou "[[$1]]"',
'protectcomment'              => 'Motif:',
'protectexpiry'               => "Date d'éspirachon:",
'protect_expiry_invalid'      => "L'date d'éspirachon ale n'est mie possibe.",
'protect_expiry_old'          => "L'date d'éspirachon ale est déjo érpassée.",
'protect-text'                => "Os pouvez vir pi canger ech nivieu d'protécchon ichi pou l'pache-lo '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Vos n’avez poin chés droés pou canger chés nivieus d'protécchon des paches.<br />
Vlo chés réglages del pache '''$1''' à ch'momint-chi:",
'protect-cascadeon'           => "L'pache-lo ale est garantie ker ale est incluse din {{PLURAL:$1|eune pache qu'o té garantie|des paches éq ont té garanties}} aveuc l'option « protécchon in cascate» écanillée. <br /> Os povez canger ch'nivieu d'garantie del pache mais el garantie in cascate n'sro poin cangée.",
'protect-default'             => 'Por tertous chés uzeus',
'protect-fallback'            => 'I feut avoèr l\'pérmission "$1"',
'protect-level-autoconfirmed' => 'Blotcher nouvieus pi mie-inrégistrés uzeus',
'protect-level-sysop'         => 'Aménistrateus seulemint',
'protect-summary-cascade'     => 'cascates',
'protect-expiring'            => "éspire l'$1 (UTC)",
'protect-cascade'             => "Défènne étou chés paches éq sont din l'pache-lo (protécchon in cascate)",
'protect-cantedit'            => "Vos n'pouvez poin canjer chés nivieus d'protécchon del pache-lo aladon vos n’avez poin l'pérmichon éd foaire des modificachons.",
'restriction-type'            => 'Pérmission',
'restriction-level'           => 'Nivieu éd réstricchon:',

# Undelete
'undeletelink'     => 'vir/érfoaire',
'undeletedarticle' => 'érfoaire "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Éspace du nom:',
'invert'         => 'Invérser el sélékchon',
'blanknamespace' => '(Moaite)',

# Contributions
'contributions'       => 'Contérbuchons dechl uzeu',
'contributions-title' => 'Contérbuchons dechl uzeu à pérpos éd $1',
'mycontris'           => 'Ems contérbuchons',
'contribsub2'         => 'Pou $1 ($2)',
'uctop'               => '(darin)',
'month'               => "Dpuis ch'moés (pi édvant)",
'year'                => 'Del innée (pi avint)',

'sp-contributions-newbies'  => 'Montrer chés contérbuchons éd chés nouvieus conptes seulemint',
'sp-contributions-blocklog' => 'jornal éd chés blotcåjhes',
'sp-contributions-search'   => 'Tracher pou chés contérbuchons',
'sp-contributions-username' => "Adérche IP ou nom d'uzeu",
'sp-contributions-toponly'  => "n'montrer qu'chés darins canjemints",
'sp-contributions-submit'   => 'Tracher',

# What links here
'whatlinkshere'            => 'Cha lie quoé ichi',
'whatlinkshere-title'      => 'Paches qu\'il ont des loïens aveuc "$1"',
'whatlinkshere-page'       => 'Pache:',
'linkshere'                => "Chés paches-lo il sont érliées à '''[[:$1]]''':",
'nolinkshere'              => "i n'y o poin d'pache aveuc un loïen vers  '''[[:$1]]'''.",
'nolinkshere-ns'           => "i n'y o poin d'pache aveuc un loïen vers '''[[:$1]]''' dins echl'éspace d'noms coési.",
'isredirect'               => 'pache érdirigée',
'istemplate'               => 'transclusion',
'isimage'                  => "Loïen aveuc l'imache",
'whatlinkshere-prev'       => '{{PLURAL:$1|édvant|édvants $1}}',
'whatlinkshere-next'       => "{{PLURAL:$1|d'apreu|d'apreu $1}}",
'whatlinkshere-links'      => '← loïens',
'whatlinkshere-hideredirs' => '$1 érdireccions',
'whatlinkshere-hidetrans'  => 'transclusions éd $1',
'whatlinkshere-hidelinks'  => '$1 loïens',
'whatlinkshere-hideimages' => '$1 chés loïés fichiés',
'whatlinkshere-filters'    => 'Filtes',

# Block/unblock
'blockip'                  => "Blotcher l'uzeu",
'blockip-title'            => "Blotcher l'uzeu",
'blockip-legend'           => "Blotcher l'uzeu",
'blockiptext'              => "Uzer dech teske-lo pour blotcher l’ahérse aux canjemints foaits dpui eune adrèche IP éspéchifique o bin d’un nom d’uzeu.
I feut l'foaire seleumint pour inréyer ech vindalime et pi i feut ète acordant aveuc chés [[{{MediaWiki:Policy-url}}|usages intérnes]].
Donner apré ch'motif  (pèr egzimpe chiter chés paches qu'ont té vindalisées).",
'ipboptions'               => '2 heures:2 hours,1 jour:1 day,3 jours:3 days,1 ésminne:1 week,2 ésminnes:2 weeks,1 moés:1 month,3 moés:3 months,6 moés:6 months,1 an:1 year,infini:infinite',
'ipbotheroption'           => 'eute',
'ipblocklist'              => "Adréches IP pi noms d'uzeu blotchés",
'blocklink'                => 'blotcher',
'unblocklink'              => 'déblotcher',
'change-blocklink'         => 'cange ech block',
'contribslink'             => 'contérbuchons',
'blocklogpage'             => 'jornal éd chés paches blotchées',
'blocklogentry'            => '[[$1]] est blotché aveuc eune durèe éd $2 $3',
'unblocklogentry'          => '$1 est déblotché',
'block-log-flags-nocreate' => "créhachon d'conpte intérdite",

# Move page
'movepagetext'     => "Implouéyez ch'formuloére édsou pou érlonmer éne pache, in déplachant tout sin histoérique vers ch'nouvieu nom. Echl’anthiu tite i varo eune pache éd érdirécchon vers ch'nouvieu tite. Os povez métte à jour oùtonmatiquemint chés érdirécchons à ch'momint-chi qu' pointette vers ch'tite original. <br /> Si os couésiyez éd n'poin l'foaire, os d'vez vérifier toute [[Special:DoubleRedirects|doube érdirécchon]] ou [[Special:BrokenRedirects|bérsiée érdirécchon]].<br /> Vos avez el résponsabilité d'vérifier éq chés loïens continuette éd pointer vers leu déstinnachon prévue.

Notez éq l'pache n'éro '''poin''' déplachée s’il essiste déjo eune pache aveuc ch'nouvieu tite, sauf si l'pache o un histoérique éd canjemints vièrge ,   vide ou est éne simpe érdiréctchn. Cha pérmet d'érlonmer eune pache vers esn posicion d’origine si ch'déplachemint est éne berlure.

'''AFUTE !'''<br />
Cha put provoquer un canjemint radical pi imprévu pou eune pache souvint arbéyée ; vos dvez vos asseurer d’avoér conpérte chés consécanches dvint d'continuer.",
'movepagetalktext' => "L'pache éd pérlache achochonnèe sro oùtonmatiquemint érlonmée aveuc l'pache-lo ''' hormis si: ''' <br />
*eune pache éd pérlache aveuc un teske essiste aveuc ch'nom-lo piécha, ou
*os débiffez el casse édzou.

Din chés cas-lo, I feut érlonmer ou ratatouiller l'pache aveuc l'main.",
'movearticle'      => "Déplacer l'pache",
'newtitle'         => 'Pou un nouvieu tite:',
'move-watch'       => "Suire l'pache-lo",
'movepagebtn'      => "Déplacer l'pache",
'pagemovedsub'     => 'Déplachemint réussi',
'movepage-moved'   => '\'\'\'"$1" o té déplaché su "$2"\'\'\'',
'articleexists'    => "Il y o eune pache aveuc ch'nom-lo dja, ou bin ch'tite couési n'est poin valabe. <br /> I feut in prinde un eute",
'talkexists'       => "'''L'pache ale o té déplachée   mais l'pache d'pérlache n'put poin éte déplachée ker il y o pécho eune pache d'pérlache aveuc ch'nouvieu nom. <br /> I feut foaire un touillache al main.'''",
'movedto'          => 'Déplaché dsus',
'movetalk'         => "Canjer ch'nom del pache d'pérlache apparièe",
'1movedto2'        => 'déplacher [[$1]] dsus [[$2]]',
'1movedto2_redir'  => "il o déplaché [[$1]] su [[$2]] in écatant l'érsin",
'movelogpage'      => 'Jornal éd chés déplachemints',
'movereason'       => 'Motif:',
'revertmove'       => 'invérser',

# Export
'export' => 'Ésporter chés paches',

# Thumbnails
'thumbnail-more' => 'Pu grand',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Vote pache éd uzeu',
'tooltip-pt-mytalk'               => "Vote pache d'pérlache",
'tooltip-pt-preferences'          => 'Vos préférinches',
'tooltip-pt-watchlist'            => "El lisse d'chés paches éq vos suivez chés canjemints",
'tooltip-pt-mycontris'            => 'Lisse éd vos contérbuchons',
'tooltip-pt-login'                => "vos ètes incoradjé éd vos lodjé; portanne ch'est mie oblidjé",
'tooltip-pt-logout'               => 'Sortir',
'tooltip-ca-talk'                 => 'Distchussion à pérpos del pache-lo',
'tooltip-ca-edit'                 => 'Os pouvez éditer l\'pache-lo.
Mérci d\'imploéyer ch\'bouton "vir" édvant éd "warder"',
'tooltip-ca-addsection'           => 'Débuter eune novèle sekchon',
'tooltip-ca-viewsource'           => "Cht' pache-lo ale est garantie.

Os pouvez vir l'source",
'tooltip-ca-history'              => 'Antieus canjemints éd chol pache-lo',
'tooltip-ca-protect'              => "Garantir l'pache-lo",
'tooltip-ca-delete'               => "Défacer l'pache-lo",
'tooltip-ca-move'                 => "Déplacher l'pache",
'tooltip-ca-watch'                => "Ajouter l'pache-lo à vo lisse à suire",
'tooltip-ca-unwatch'              => "Értirer l'pache-lo d'vote lisse à suire",
'tooltip-search'                  => 'Tracher {{SITENAME}}',
'tooltip-search-go'               => "Aler à l'pache aveuc ech meume jusse nom s'il y in o eune",
'tooltip-search-fulltext'         => "Tracher chés paches aveuc ch'teske-lo",
'tooltip-p-logo'                  => "Aler vir l'moaite pache",
'tooltip-n-mainpage'              => "Vir l'pache princhipale",
'tooltip-n-mainpage-description'  => "Vir l'moaite pache",
'tooltip-n-portal'                => "à pérpos dech proujé, quô qu'vos pouvez foaire, d'ou trouvoér des coses",
'tooltip-n-currentevents'         => "Trouvoér des informachons d'base su l’darinneté dech momint-lo",
'tooltip-n-recentchanges'         => "Lisse éd chés darins canjemints din ch'wiki",
'tooltip-n-randompage'            => 'Quértcher eune aléatoère pache',
'tooltip-n-help'                  => "L'plache à trouvoér",
'tooltip-t-whatlinkshere'         => "Lisser tertous ches paches wiki qu'sont liées ichi",
'tooltip-t-recentchangeslinked'   => "Nouvieus cangemints din chés paches érliées aveuc l'pache-lo",
'tooltip-feed-rss'                => "RSS pipe pou l'pache-lo",
'tooltip-feed-atom'               => 'Fil Atom pou chol pache',
'tooltip-t-contributions'         => "Vir l'lisse éd chés contérbuchons dech uzeu-lo",
'tooltip-t-emailuser'             => "Éspédier un imèle à cht'uzeu-lo",
'tooltip-t-upload'                => 'Quértcher chés fichiés',
'tooltip-t-specialpages'          => 'Lisse éd tous chés paches éspéchiales',
'tooltip-t-print'                 => 'Imprimabe vérchon del pache-lo',
'tooltip-t-permalink'             => "Loïen définitive aveuc cht'canjemint del pache",
'tooltip-ca-nstab-main'           => "Vir echl'étnu del pache",
'tooltip-ca-nstab-user'           => 'Vir el pache dech uzeu',
'tooltip-ca-nstab-special'        => "Ch'est eune pache éspéchiale, os n'pouvez poin éditer l'pache-lo",
'tooltip-ca-nstab-project'        => "Vir l'pache dech proujé",
'tooltip-ca-nstab-image'          => "Vir ch'fichié del pache",
'tooltip-ca-nstab-template'       => "Vir ch'modèle",
'tooltip-ca-nstab-category'       => 'Vir el pache del catégorie',
'tooltip-minoredit'               => 'Mértcher cho conme eune tiote édition',
'tooltip-save'                    => 'Seuver vos cangemints',
'tooltip-preview'                 => "Prévir vos cangemints, uzer cha édvant d'inr'gister mérci!",
'tooltip-diff'                    => "Montrer chés cangemints éq'vos avez foait din ch'teske-lo",
'tooltip-compareselectedversions' => 'Vir chés diférinches intre chés deus couésies vérchons del pache-lo',
'tooltip-watch'                   => 'Ajouter chol pache-lo à vo lisse à suire',
'tooltip-rollback'                => '« Racacher » cancéle aveuc un clic el (ou chés) modificachon(s) del pache-lo pèr sin darin contérbucheu.',
'tooltip-undo'                    => "« Undo » ( ''démangler'' ) értire ch'canjemint-lo pi ouvre l' fénéte d'édichon din ch'mode ''prévir''. <br /> In put mette un motif din ch'résumé.",
'tooltip-preferences-save'        => 'Warder chés préférinches.',

# Info page
'numedits' => 'Nombe ed canjemints (pache) : $1',

# Browsing diffs
'previousdiff' => '← Pu vieille édition',
'nextdiff'     => 'Nouvèle édichon →',

# Media information
'file-info-size'       => '($1 × $2 picséls, diminchon dech fichié: $3, MIME tipe: $4)',
'file-nohires'         => '<small>Poin éd pu grande résoluchon possibe.</small>',
'svg-long-desc'        => '(Fichié SVG, résoluchon éd $1 × $2 picsels, diminchon: $3)',
'show-big-image'       => 'Plinne résoluchon',
'show-big-image-thumb' => '<small>Diminchon del intérvue-lo : $1 × $2 picséls</small>',

# Bad image list
'bad_image_list' => "Ch'format ch'est:

In érbéye seulemint chés lisses éd limérachon (aveuc * al copéte). <br /> Ech preumié loïen d'eune line i doét éte échti d'eune méchante image. <br /> Chés eutes loïens su el méme line s'ront érbéyés conme des éssékcions, pèr eximpe des paches où l'image put aparoète.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Ech fichié-lo i conprinte des informacions métadatas éq ils sont suremint rajoutées par echl’apàrèl foto limérique ou ech éscanère utilisé pou l' créer.<br /> Si ch' fichié o té canjé dpui sin étot avoyé, quéques détals peute n'poin corésponne à l’imache modifiée.",
'metadata-expand'   => 'Montrer chés métadatas del imache',
'metadata-collapse' => 'Mucher chés métadatas del imache',
'metadata-fields'   => "Chés cans d'chés métadonnées EXIF afiqués din ch'messache-lo is s'ront mis din l'pache d'édvisse éd l'image quant el tabe d'chés métadonnées ale s'ro rapetichée.<br />
Chés eutes cans is s'ront muchés pèr défeut.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# External editor support
'edit-externally'      => "Éditer ch'fichié-lo aveuc eune éstérne aplicachon",
'edit-externally-help' => '(Vir [http://www.mediawiki.org/wiki/Manual:External_editors/fr chés instruccions d’installachon] pou pus d’informachons)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tertous',
'namespacesall' => 'tous',
'monthsall'     => 'tertous',

# Multipage image navigation
'imgmultipageprev' => '← pache édvant',

# Table pager
'table_pager_next'  => 'Pache apré',
'table_pager_first' => 'Prumière pache',
'table_pager_last'  => 'Darinne pache',

# Watchlist editing tools
'watchlisttools-view' => 'Vir chés consécants cangemints',
'watchlisttools-edit' => "Vir pi éditer l'lisse à suire",
'watchlisttools-raw'  => 'Éditer eune brute lisse à suire',

# Special:Version
'version-specialpages' => 'Paches éspéchiales',

# Special:FilePath
'filepath-page' => 'Fichié :',

# Special:SpecialPages
'specialpages'             => 'Paches éspéchiales',
'specialpages-group-users' => 'Uzeus pi leus droués',
'specialpages-group-pages' => "Lisses d'chés paches",

# Special:BlankPage
'blankpage' => 'Blanke pache',

# Special:ComparePages
'compare-page1'  => 'Pache 1',
'compare-page2'  => 'Pache 2',
'compare-rev1'   => 'Canjemint 1',
'compare-rev2'   => 'Canjemint 2',
'compare-submit' => 'Aconparer',

# Database error messages
'dberr-header' => 'Ech wiki-lo il o dés problémes',

# HTML forms
'htmlform-reset' => "n'poin foaire chés canjemints",

);
