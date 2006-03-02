<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

require_once( 'LanguageUtf8.php' );

/* private */ $wgNamespaceNamesFr = array(
	NS_MEDIA			=> 'Media',
	NS_SPECIAL			=> 'Special',
	NS_MAIN				=> '',
	NS_TALK				=> 'Discuter',
	NS_USER				=> 'Utilisateur',
	NS_USER_TALK		=> 'Discussion_Utilisateur',
	NS_PROJECT			=> $wgMetaNamespace,
	NS_PROJECT_TALK		=> 'Discussion_'.$wgMetaNamespace,
	NS_IMAGE			=> 'Image',
	NS_IMAGE_TALK		=> 'Discussion_Image',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Discussion_MediaWiki',
	NS_TEMPLATE			=> 'Modèle',
	NS_TEMPLATE_TALK	=> 'Discussion_Modèle',
	NS_HELP				=> 'Aide',
	NS_HELP_TALK		=> 'Discussion_Aide',
	NS_CATEGORY			=> 'Catégorie',
	NS_CATEGORY_TALK	=> 'Discussion_Catégorie'
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsFr = array(
	'Aucune', 'Gauche', 'Droite', 'Flottante à gauche'
);

/* private */ $wgSkinNamesFr = array(
	'standard'		=> 'Standard',
	'nostalgia'		=> 'Nostalgie',
) + $wgSkinNamesEn;



/* private */ $wgBookstoreListFr = array(
	'Amazon.fr'		=> 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'	=> 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'		=> 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com'	=> 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);


/* private */ $wgAllMessagesFr = array(

# User Toggles

'tog-editwidth' => 'La fenêtre d\'édition s\'affiche en pleine largeur',
'tog-editondblclick' => 'Double cliquer pour éditer une page (JavaScript)',
'tog-editsection'	=> 'Éditer une section via les liens [éditer]',
'tog-editsectiononrightclick'	=> 'Éditer une section en cliquant à droite<br /> sur le titre de la section',
'tog-fancysig' => 'Signatures brutes (sans lien automatique)',
'tog-hideminor' => 'Cacher les <i>Modifications récentes</i> mineures',
'tog-highlightbroken' => 'Liens vers les sujets non existants en rouge',
'tog-justify' => 'Paragraphes justifiés',
'tog-minordefault' => 'Mes modifications sont considérées<br /> comme mineures par défaut',
'tog-nocache' => 'Désactiver le cache des pages',
'tog-numberheadings' => 'Numérotation automatique des titres',
'tog-previewonfirst' => 'Montrer la prévisualisation lors de la première édition',
'tog-previewontop' => 'La prévisualisation s\'affiche au<br /> dessus de la boite de rédaction',
'tog-rememberpassword' => 'Se souvenir de mon mot de passe (cookie)',
'tog-showtoc'	=> 'Afficher la table des matières<br /> (pour les articles ayant plus de 3 sections)',
'tog-showtoolbar' => 'Montrer la barre de menu d\'édition',
'tog-usenewrc' => 'Modifications récentes améliorées<br /> (certains navigateurs seulement)',
'tog-underline' => 'Liens soulignés',
'tog-watchdefault' => 'Suivre les articles que je crée ou modifie',


# Dates

'sunday' => 'dimanche',
'monday' => 'lundi',
'tuesday' => 'mardi',
'wednesday' => 'mercredi',
'thursday' => 'jeudi',
'friday' => 'vendredi',
'saturday' => 'samedi',
'january' => 'janvier',
'february' => 'février',
'march' => 'mars',
'april' => 'avril',
'may_long' => 'mai',
'june' => 'juin',
'july' => 'juillet',
'august' => 'août',
'september' => 'septembre',
'october' => 'octobre',
'november' => 'novembre',
'december' => 'décembre',
'jan' => 'jan',
'feb' => 'fév',
'mar' => 'mar',
'apr' => 'avr',
'may' => 'mai',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aoû',
'sep' => 'sep',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'déc',


# Bits of text used by many pages:
#
'categories'	=> 'Catégories de la page',
'category'	=> 'catégorie',
'category_header' => 'Articles dans la catégorie « $1 ».',
'subcategories'	=> 'Sous-catégories',
'uncategorizedcategories' => 'Catégories sans catégories',
'uncategorizedpages' => 'Pages sans catégories',
'subcategorycount' => 'Cette catégorie possède $1 sous-catégories.',
'subcategorycount1' => 'Cette catégorie possède $1 sous-catégorie.',

'allarticles'   => 'Tous les articles',
'linktrail'     => '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sD',
'mainpage'      => 'Accueil',
'mainpagetext'	=> 'Logiciel {{SITENAME}} installé.',
'portal'        => 'Communauté',
'portal-url'	=> '{{ns:4}}:Accueil',
'about'         => 'À propos',
'aboutsite'     => 'À propos de {{SITENAME}}',
'aboutpage'     => '{{ns:4}}:À propos',
'article'       => 'Article',
'help'          => 'Aide',
'helppage'      => '{{ns:help}}:Aide',
'bugreports'    => 'Rapport d\'erreurs',
'bugreportspage' => '{{ns:4}}:Rapport d\'erreurs',
'sitesupport'	=> 'Participer en faisant un don',
'sitesupport'	=> 'Faire un don',
'sitesupportpage'	=> '{{ns:4}}:Dons',
'faq'           => 'FAQ',
'faqpage'       => '{{ns:4}}:FAQ',
'edithelp'      => 'Aide',
'edithelppage'  => '{{ns:help}}:Comment éditer une page',
'cancel'        => 'Annuler',
'qbfind'        => 'Rechercher',
'qbbrowse'      => 'Défiler',
'qbedit'        => 'Modifier',
'qbpageoptions' => 'Page d\'option',
'qbpageinfo'    => 'Page d\'information',
'qbmyoptions'   => 'Mes options',
'qbspecialpages'	=> 'Pages spéciales',
'moredotdotdot'	=> 'Et plus...',
'mypage'        => 'Ma page',
'mytalk'        => 'Ma page de discussion',
'anontalk'	=> 'Discussion avec cette adresse ip',
'navigation'	=> 'Navigation',
'currentevents' => 'Actualités',
'disclaimers'	=> 'Avertissements',
'disclaimerpage' => '{{ns:4}}:Avertissements généraux',
'errorpagetitle' => 'Erreur',
'returnto'      => 'Revenir à la page $1.',
'tagline'       => 'Un article de {{SITENAME}}.',
'whatlinkshere' => 'Références à cette page',
'help'          => 'Aide',
'search'        => 'Rechercher',
'history'       => 'Historique',
'printableversion' => 'Version imprimable',
'edit'		=> 'Modifier',
'editthispage'  => 'Modifier cette page',
'delete'	=> 'Supprimer',
'deletethispage' => 'Supprimer cette page',
'undelete_short' => 'Restaurer',
'undelete_short1' => 'Restaurer',
'protect' => 'Protéger',
'protectthispage' => 'Protéger cette page',
'unprotect' => 'Déprotéger',
'unprotectthispage' => 'Déprotéger cette page',
'newpage'       => 'Nouvelle page',
'talkpage'      => 'Page de discussion',
'specialpage'	=> 'Page Spéciale',
'personaltools'	=> 'Outils personels',
'postcomment'	=> 'Ajouter un commentaire',
'addsection'   => '+',
'articlepage'	=> 'Voir l\'article',
'subjectpage'   => 'Page sujet',
'talk'		=> 'Discussion',
'toolbox'	=> 'Boîte à outils',
'userpage'      => 'Page utilisateur',
'wikipediapage' => 'Page méta',
'imagepage'     => 'Page image',
'viewtalkpage'  => 'Page de discussion',
'otherlanguages' => 'Autres langues',
'redirectedfrom' => '(Redirigé depuis $1)',
'lastmodified'  => 'Dernière modification de cette page le $1.',
'viewcount'     => 'Cette page a été consultée $1 fois.',
'copyright'	=> 'Contenu disponible sous $1.',
'printsubtitle' => '(de {{SERVER}})',
'protectedpage' => 'Page protégée',
'administrators' => '{{ns:4}}:Administrateurs',
'sysoptitle'    => 'Accès administrateur requis',
'sysoptext'     => 'L\'action que vous avez tentée ne peut être effectuée que par un utilisateur ayant le statut d\'« administrateur ».
Voir $1.',
'developertitle' => 'Accès développeur requis',
'developertext' => 'L\'action que vous avez tentée ne peut être effectuée que par un utilisateur ayant le statut de « développeur ».
Voir $1.',
'nbytes'        => '$1 octets',
'go'            => 'Consulter',
'ok'            => 'OK',
'pagetitle'	=> '$1 - {{SITENAME}}',
'history'	=> 'Historique de la page',
'history_short' => 'Historique',
'sitetitle'     => '{{SITENAME}}',
'sitesubtitle'  => '',
'retrievedfrom' => 'Récupérée de « $1 »',
'newmessages'   => 'Vous avez des $1.',
'newmessageslink' => 'nouveaux messages',
'editsection'	=> 'modifier',
'toc'		=> 'Sommaire',
'showtoc'	=> 'afficher',
'hidetoc'	=> 'masquer',
'thisisdeleted' => 'Afficher ou restaurer $1 ?',
'restorelink'	=> '1 modifications effacées',
'feedlinks'	=> 'Flux:',
'sitenotice'	=> '-', # the equivalent to wgSiteNotice

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Article',
'nstab-user' => 'Page utilisateur',
'nstab-media' => 'Média',
'nstab-special' => 'Spécial',
'nstab-wp' => 'À propos',
'nstab-image' => 'Image',
'nstab-mediawiki' => 'Message',
'nstab-template' => 'Modèle',
'nstab-help' => 'Aide',
'nstab-category' => 'Catégorie',

# Main script and global functions
#
'nosuchaction'	=> 'Action inconnue',
'nosuchactiontext' => 'L\'action spécifiée dans l\'Url n\'est pas reconnue par le wiki.',
'nosuchspecialpage' => 'Page spéciale inexistante',
'nospecialpagetext' => 'Vous avez demandé une page spéciale qui n\'est pas reconnue par le wiki.',

# General errors
#
'error'		=> 'Erreur',
'badaccess' => 'Erreur de permission',
'badaccesstext' => 'L\'action entreprise est limitée aux utilisateurs possédant le droit « $2 ».
Voir $1',
'databaseerror' => 'Erreur base de données',
'dberrortext'	=> 'Erreur de syntaxe dans la base de données. La dernière requête traitée par la base de données était :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction « <tt>$2</tt> ».
MySQL a renvoyé l\'erreur « <tt>$3: $4</tt> ».',
'dberrortextcl' => 'Une requête à la base de donnée comporte une erreur de syntaxe. La dernière requête envoyée était:
« $1 »
effectuée par la fonction « $2 »
MySQL a retourné l\'erreur « $3 : $4 ».',
'noconnect'	=> 'Désolé! Suite à des problèmes techniques, il est impossible de se connecter à la base de données pour le moment.',
'nodb'		=> 'Sélection impossible de la base de données $1',
'cachederror'	=> 'Ceci est une copie de la page demandée et peut ne pas être à jour',
'readonly'	=> 'Mises à jour bloquées sur la base de données',
'enterlockreason' => 'Indiquez la raison du blocage, ainsi qu\'une estimation de la durée de blocage ',
'readonlytext'	=> 'Les ajouts et mises à jour sur la base de donnée {{SITENAME}} sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l\'ordre. Voici la raison pour laquelle l\'administrateur a bloqué la base :
<p>$1',
'missingarticle' => 'La base de données n\'a pas pu trouver le texte d\'une page existante, dont le titre est « $1 ».
Ce n\'est pas une erreur de la base de données, mais plus probablement un bogue du wiki.
Veuillez rapporter cette erreur à un administrateur, en lui indiquant l\'adresse de la page fautive.',
'internalerror' => 'Erreur interne',
'filecopyerror' => 'Impossible de copier « $1 » vers « $2 ».',
'fileinfo' => '$1Ko, type MIME: <tt>$2</tt>',
'filerenameerror' => 'Impossible de renommer « $1 » en « $2 ».',
'filedeleteerror' => 'Impossible de supprimer « $1 ».',
'filenotfound'	=> 'Le fichier « $1 » introuvable.',
'unexpected' => 'Valeur inattendue : « $1 » = « $2 ».',
'formerror'	=> 'Erreur: Impossible de soumettre le formulaire',
'badarticleerror' => 'Cette action ne peut pas être effectuée sur cette page.',
'cannotdelete'	=> 'Impossible de supprimer la page ou l\'image indiquée.',
'badtitle'	=> 'Mauvais titre',
'badtitletext'	=> 'Le titre de la page demandée est invalide, vide ou le lien interlangue est invalide',
'laggedslavemode' => 'Attention : la page n\'intègre peut être pas les dernières éditions',
'readonly_lag' => 'La base de donnée a été automatiquement bloquée pendant que les serveurs secondaires rattrapent leur retard avec le serveur principal',
'perfdisabled' => 'Désolé! Cette fonctionnalité est temporairement désactivée
car elle ralentit la base de données à un point tel que plus personne
ne peut utiliser le wiki.',
'perfdisabledsub' => 'Ceci est une copie de sauvegarde de $1:',
'viewsource'	=> 'Voir le texte source',
'protectedtext'	=> 'Cette page a été bloquée pour empêcher sa modification. Consulter [[{{ns:4}}:Page protégée]] pour voir les différentes raisons possibles.',
'allmessagesnotsupportedDB' => 'Special:AllMessages n\'est pas disponible parce que wgUseDatabaseMessages est désactivé.',
'allmessagesnotsupportedUI' => 'Special:AllMessages n\'accepte pas la langue de votre interface (<b>$1</b>) sur ce site.',
'wrong_wfQuery_params' => 'Paramètres incorrects sur la commande wfQuery()<br />
Fonction : $1<br />
Requête : $2',
'versionrequired' => 'Version $1 de MediaWiki nécessaire',
'versionrequiredtext' => 'La version $1 de MediaWiki est nécessaire pour utiliser cette page. Voyez [[Special:Version]]',


# Login and logout pages
#
'logouttitle'	=> 'Déconnexion',
'logouttext'	=> 'Vous êtes à présent déconnecté(e).
Vous pouvez continuer à utiliser {{SITENAME}} de façon anonyme, ou vous reconnecter, éventuellement sous un autre nom.',

'welcomecreation' => '<h2>Bienvenue, $1!</h2><p>Votre compte d\'utilisateur a été créé.
N\'oubliez pas de personnaliser votre {{SITENAME}} en consultant la page Préférences.',
'loginpagetitle'     => 'Votre identifiant',
'yourname'           => 'Votre nom d\'utilisateur',
'yourpassword'       => 'Votre mot de passe',
'yourpasswordagain'  => 'Entrez à nouveau votre mot de passe',
'newusersonly'       => ' (nouveaux utilisateurs uniquement)',
'remembermypassword' => 'Se souvenir de mon mot de passe (cookie)',
'loginproblem'       => '<b>Problème d\'identification.</b><br />Essayez à nouveau !',
'alreadyloggedin'    => '<strong>\'\'\'Utilisateur $1, vous êtes déjà identifié!\'\'\'</strong><br />',

'login'         => 'Identification',
'loginprompt'	=> 'Vous devez activer les cookies pour vous connecter à {{SITENAME}}.',
'userlogin'     => 'Créer un compte ou se connecter',
'logout'        => 'Déconnexion',
'userlogout'    => 'Déconnexion',
'notloggedin'	=> 'Non connecté',
'createaccount' => 'Créer un nouveau compte',
'createaccountmail'	=> 'par courriel',
'badretype'     => 'Les deux mots de passe que vous avez saisis ne sont pas identiques.',
'userexists'    => 'Le nom d\'utilisateur que vous avez saisi est déjà utilisé. Veuillez en choisir un autre.',
'youremail'     => 'Mon adresse électronique',
'yournick'      => 'Signature pour les discussions (avec <tt><nowiki>~~~</nowiki></tt>)&nbsp;',
'yourrealname'	=> 'Votre nom réel*',
'emailforlost'  => 'Si vous égarez votre mot de passe, vous pouvez demander à ce qu\'un nouveau vous soit envoyé à votre adresse électronique.',
'prefs-help-realname' => '* <strong>Votre nom</strong> (facultatif): si vous le spécifiez, il sera utilisé pour l\'attribution de vos contributions.',
'prefs-help-email' => '* <strong>Adresse électronique</strong> (facultatif): permet de vous contacter depuis le site sans dévoiler votre adresse, et utilisée pour vous envoyer un nouveau mot de passe si vous l\'oubliez.',
'loginerror'    => 'Problème d\'identification',
'nocookiesnew'	=> 'Le compte utilisateur a été créé, mais vous n\'êtes pas connecté. {{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactives. Merci de les activer et de vous reconnecter.',
'nocookieslogin' => '{{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactives. Merci de les activer et de vous reconnecter.',
'noname'        => 'Vous n\'avez pas saisi de nom d\'utilisateur.',
'loginsuccesstitle' => 'Identification réussie.',
'loginsuccess'  => 'Vous êtes actuellement connecté sur {{SITENAME}} en tant que « $1 ».',
'nosuchuser'    => 'L\'utilisateur « $1 » n\'existe pas.
Vérifiez que vous avez bien orthographié le nom, ou utilisez le formulaire ci-dessous pour créer un nouveau compte utilisateur.',
'nosuchusershort' => 'Il n\'y a pas de contributeur avec le nom « $1 ». Vérifiez l\'orthographe.',
'wrongpassword' => 'Le mot de passe est incorrect. Essayez à nouveau.',
'mailmypassword' => 'Envoyez-moi un nouveau mot de passe',
'passwordremindertitle' => 'Votre nouveau mot de passe sur {{SITENAME}}',
'passwordremindertext' => 'Quelqu\'un (probablement vous) ayant l\'adresse IP $1 a demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour votre accès au wiki.
Le mot de passe de l\'utilisateur « $2 » est à présent « $3 ».
Nous vous conseillons de vous connecter et de modifier ce mot de passe dès que possible.',
'noemail'  => 'Aucune adresse électronique n\'a été enregistrée pour l\'utilisateur « $1 ».',
'passwordsent' => 'Un nouveau mot de passe a été envoyé à l\'adresse électronique de l\'utilisateur « $1 ».
Veuillez vous identifier dès que vous l\'aurez reçu.',
'loginend'	=> '&nbsp;',
'mailerror'	=> 'Erreur en envoyant le courriel : $1',
'acct_creation_throttle_hit' => 'Désolé, vous avez déjà créé $1 comptes. Vous ne pouvez pas en créer de nouveaux.',

# Edit page toolbar
'bold_sample'   => 'Texte gras',
'bold_tip'      => 'Texte gras',
'italic_sample' => 'Texte italique',
'italic_tip'    => 'Texte italique',
'link_sample'   => 'Lien titre',
'link_tip'      => 'Lien interne',
'extlink_sample'  => 'http://www.example.com lien titre',
'extlink_tip'     => 'Lien externe (n\'oubliez pas http://)',
'headline_sample' => 'Texte de sous-titre',
'headline_tip'  => 'Sous-titre niveau 2',
'math_sample'   => 'Entrez votre formule ici',
'math_tip'      => 'Formule mathématique (LaTeX)',
'nowiki_sample' => 'Entrez le texte non formatté ici',
'nowiki_tip'    => 'Ignorer la syntaxe wiki',
'image_sample'  => 'Exemple.jpg',
'image_tip'     => 'Image insérée',
'media_sample'  => 'Exemple.ogg',
'media_tip'     => 'Lien fichier média',
'sig_tip'       => 'Votre signature avec la date',
'hr_tip'        => 'Lien horizontal (ne pas en abuser)',
'infobox'       => 'Cliquez ce bouton pour avoir un exemple de texte',
'infobox_alert'	=> "Veuillez entrer le texte que vous voulez formater.\\n Il sera affiché dans la boîte pour être copié et collé.\\nExemple\\n$1\\ndeviendra:\\n$2",

# Edit pages
#
'summary'      => 'Résumé&nbsp;',
'subject'	   => 'Sujet/titre',
'minoredit'    => 'Modification mineure.',
'watchthis'    => 'Suivre cet article',
'savearticle'  => 'Sauvegarder',
'preview'      => 'Prévisualiser',
'showpreview'  => 'Prévisualisation',
'blockedtitle' => 'Utilisateur bloqué',
'blockedtext'  => 'Votre compte utilisateur ou votre adresse IP ont été bloqués par $1 pour la raison suivante :<br />$2<p>Vous pouvez contacter $1 ou un des autres [[{{ns:4}}:Administrateurs|administrateurs]] pour en discuter.',
'whitelistedittitle' => 'Login requis pour rédiger',
'whitelistedittext' => 'Vous devez être [[Special:Userlogin|connecté]] pour pouvoir rédiger',
'whitelistreadtitle' => 'Login requis pour lire',
'whitelistreadtext' => 'Vous devez être [[Special:Userlogin|connecté]] pour pouvoir lire les articles',
'whitelistacctitle' => 'Vous n\'êtes pas autorisé à créer un compte',
'whitelistacctext' => 'Pour pouvoir créer un compte sur ce Wiki vous devez être [[Special:Userlogin|connecté]] et avoir les permissions appropriées', // Looxix
'loginreqtitle'	=> 'Nom d\'utilisateur nécessaire',
'loginreqtext'	=> "Vous devez vous [[Special:Userlogin|connecter]] pour voir les autres pages.",
'accmailtitle' => 'Mot de passe envoyé.',
'accmailtext' => 'Le mot de passe de « $1 » a été envoyé à $2.',

'newarticle'   => '(Nouveau)',
'newarticletext' => 'Saisissez ici le texte de votre article.',
'anontalkpagetext' => '---- \'\'Vous êtes sur la page de discussion d\'un utilisateur anonyme qui n\'a pas encore créé un compte ou qui ne l\'utilise pas. Pour cette raison, nous devons utiliser l\'[[adresse IP]] numérique pour l\'identifier. Une adresse de ce type peut être partagée entre plusieurs utilisateurs. Si vous êtes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:Userlogin|créer un compte ou vous connecter]] afin d\'éviter toute future confusion.\'\'',
'noarticletext' => '(Il n\'y a pour l\'instant aucun texte sur cette page)',
'clearyourcache'    => '\'\'\'Note :\'\'\' Après avoir sauvegardé, vous devez forcer le rechargement de la page pour voir les changements : \'\'\'Mozilla / Firefox\'\'\' : \'\'ctrl-shift-r\'\', \'\'\'IE\'\'\' : \'\'ctrl-f5\'\', \'\'\'Safari\'\'\' : \'\'cmd-shift-r\'\'; \'\'\'Konqueror\'\'\' : \'\'f5\'\'.',
'updated'      => '(Mis à jour)',
'note'         => '<strong>Note :</strong> ',
'previewnote'  => 'Attention, ce texte n\'est qu\'une prévisualisation et n\'a pas encore été sauvegardé!',
'previewconflict' => 'La prévisualisation montre le texte de cette page tel qu\'il apparaîtra une fois sauvegardé.',
'editing'         => 'modification de $1',
'editingsection'  => 'modification de $1 (section)',
'editingcomment'  => 'modification de $1 (commentaire)',
'editconflict' => 'Conflit de modification : $1',
'explainconflict' => '<b>Cette page a été sauvegardée après que vous avez commencé à la modifier.
La zone d\'édition supérieure contient le texte tel qu\'il est enregistré actuellement dans la base de données. Vos modifications apparaissent dans la zone d\'édition inférieure. Vous allez devoir apporter vos modifications au texte existant. Seul le texte de la zone supérieure sera sauvegardé.<br />',
'yourtext'     => 'Votre texte',
'storedversion' => 'Version enregistrée',
'editingold'   => '<strong>Attention : vous êtes en train de modifier une version obsolète de cette page. Si vous sauvegardez, toutes les modifications effectuées depuis cette version seront perdues.</strong>',
'yourdiff'  => 'Différences',
'copyrightwarning' => 'Toutes les contributions à {{SITENAME}} sont considérées comme publiées sous les termes de la GNU Free Documentation Licence, une licence de documentation libre (Voir $1 pour plus de détails). Si vous ne désirez pas que vos écrits soient édités et distribués à volonté, ne les envoyez pas. De même, merci de ne contribuer qu\'en apportant vos propres écrits ou des écrits issus d\'une source libre de droits. <b>N\'UTILISEZ PAS DE TRAVAUX SOUS COPYRIGHT SANS AUTORISATION EXPRESSE!</b>',
'longpagewarning' => '\'\'\'AVERTISSEMENT : cette page a une longueur de $1 ko;
quelques navigateurs gèrent mal les pages approchant ou dépassant 32 ko lors de leur rédaction.
Peut-être serait-il mieux que vous divisiez la page en sections plus petites.\'\'\'',
'readonlywarning' => '\'\'\'AVERTISSEMENT : cette page a été bloquée pour maintenance,
vous ne pourrez donc pas sauvegarder vos modifications maintenant. Vous pouvez copier le texte dans un fichier et le sauver pour plus tard.\'\'\'',
'protectedpagewarning' => '\'\'\'AVERTISSEMENT : cette page a été bloquée.
Seuls les utilisateurs ayant le statut d\'administrateur peuvent la modifier. Soyez certain que
vous suivez les [[Project:Page_protégée|directives concernant les pages protégées]].\'\'\'',

# History pages
#
'revhistory'   => 'Versions précédentes',
'nohistory'    => 'Il n\'existe pas d\'historique pour cette page.',
'revnotfound'  => 'Version introuvable',
'revnotfoundtext' => 'La version précédente de cette page n\'a pas pu être retrouvée. Vérifiez l\'URL que vous avez utilisée pour accéder à cette page.',

'loadhist'     => 'Chargement de l\'historique de la page',
'currentrev'   => 'Version actuelle',
'revisionasof' => 'Version du $1',
'cur'    => 'actu',
'next'   => 'suiv',
'last'   => 'dern',
'orig'   => 'orig',
'histlegend' => 'Légende : (actu) = différence avec la version actuelle ,
(dern) = différence avec la version précédente, <b>m</b> = modification mineure',
'selectnewerversionfordiff' => 'Choisir une version plus récente',
'selectolderversionfordiff' => 'Choisir une version plus ancienne',
'previousdiff' => '← Différence précédente',
'previousrevision' => '← Version précédente',
'nextdiff' => 'Différence suivante →',
'nextrevision' => 'Version suivante →',


# Category pages
#
'categoriespagetext' => 'Les catégories suivantes existent sur le wiki :',
'categoryarticlecount' => 'Il y a $1 articles dans cette catégorie.',
'categoryarticlecount1' => 'Il y a $1 article dans cette catégorie.',


#  Diffs
#
'difference' => '(Différences entre les versions)',
'loadingrev' => 'chargement de l\'ancienne version pour comparaison',
'lineno'  => 'Ligne $1:',
'editcurrent' => 'Modifier la version actuelle de cette page',


# Search results
#
'searchresults' => 'Résultat de la recherche',
'searchresulttext' => 'Pour plus d\'informations sur la recherche dans {{SITENAME}}, voir [[Project:Recherche|Chercher dans {{SITENAME}}]].',
'searchquery' => 'Pour la requête « $1 »',
'badquery'  => 'Requête mal formulée',
'badquerytext' => 'Nous n\'avons pas pu traiter votre requête.
Vous avez probablement recherché un mot d\'une longueur inférieure à trois lettres, ce qui n\'est pas encore possible. Vous avez aussi pu faire une erreur de syntaxe, telle que « poisson et écailles ». 
Veuillez essayer une autre requête.', // FIXME
'matchtotals' => 'La requête « $1 » correspond à $2 titre(s) d\'article et au texte de $3 article(s).',
'nogomatch' => 'Aucune page avec ce titre n\'existe, essai avec la recherche complète.',
'titlematches' => 'Correspondances dans les titres',
'notitlematches' => 'Aucun titre d\'article ne contient le(s) mot(s) demandé(s)',
'textmatches' => 'Correspondances dans les textes',
'notextmatches' => 'Aucun texte d\'article ne contient le(s) mot(s) demandé(s)',
'prevn'   => '$1 précédents',
'nextn'   => '$1 suivants',
'viewprevnext' => 'Voir ($1) ($2) ($3).',
'showingresults' => 'Affichage de <b>$1</b> résultats à partir du #<b>$2</b>.',
'showingresultsnum' => 'Affichage de <b>$3</b> résultats à partir du #<b>$2</b>.',
'nonefound'  => '<strong>Note</strong>: l\'absence de résultat est souvent due à l\'emploi de termes de recherche trop courants, comme « à » ou « de »,
qui ne sont pas indexés, ou à l\'emploi de plusieurs termes de recherche (seules les pages
contenant tous les termes apparaissent dans les résultats).',
'powersearch' => 'Recherche',
'powersearchtext' => 'Rechercher dans les espaces :<br />
$1<br />
$2 Inclure les page de redirections &nbsp; Rechercher $3 $9',
'searchdisabled' => '<p>La fonction de recherche sur l\'intégralité du texte a été temporairement désactivée à cause de la grande charge que cela impose au serveur. Nous espérons la rétablir prochainement lorsque nous disposerons d\'un serveur plus puissant. En attendant, vous pouvez faire la recherche avec Google:</p>
', // FIXME wikipedia specific
'blanknamespace' => '(Principal)',	// FIXME FvdP: translation of "(Main)"

# Preferences page
#
'preferences'       => 'Préférences',
'prefsnologin'      => 'Non connecté',
'prefsnologintext'  => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier vos préférences d\'utilisateur.',
'prefslogintext' => 'Je suis connecté(e) en tant que $1 avec le numéro d\'utilisateur $2.

Voir [[{{ns:4}}:Aide pour les préférences]] pour les explications concernant les options.',
'prefsreset'        => 'Les préférences ont été rétablies à partir de la version enregistrée.',
'qbsettings'        => 'Personnalisation de la barre outils',
'changepassword'    => 'Modification du mot de passe',
'skin'              => 'Apparence',
'math'				=> 'Rendu des maths',
'dateformat'		=> 'Format de date',
'math_failure'		=> 'Erreur math',
'math_unknown_error'	=> 'erreur indéterminée',
'math_unknown_function'	=> 'fonction inconnue',
'math_lexing_error'	=> 'erreur lexicale',
'math_syntax_error'	=> 'erreur de syntaxe',
'math_image_error'	=> 'La conversion en PNG a échouée, vérifiez l\'installation de Latex, dvips, gs et convert',
'math_bad_tmpdir'	=> 'Ne peux pas crééer ou écrire dans le répertoire temporaire',
'math_bad_output'	=> 'Ne peux pas crééer ou écrire dans le répertoire de sortie',
'math_notexvc'		=> 'L\'éxécutable « texvc » est introuvable. Lisez math/README pour le configurer.',
'prefs-personal'    => 'Informations personnelles',
'prefs-rc'          => 'Modifications récentes et affichage des ébauches',
'prefs-misc'        => 'Préférences diverses',
'saveprefs'         => 'Enregistrer les préférences',
'resetprefs'        => 'Rétablir les préférences',
'oldpassword'       => 'Ancien mot de passe',
'newpassword'       => 'Nouveau mot de passe&nbsp;',
'retypenew'         => 'Confirmer le nouveau mot de passe',
'textboxsize'       => 'Taille de la fenêtre d\'édition',
'rows'              => 'Rangées&nbsp;',
'columns'           => 'Colonnes',
'searchresultshead' => 'Affichage des résultats de recherche',
'resultsperpage'    => 'Nombre de réponses par page&nbsp;',
'contextlines'      => 'Nombre de lignes par réponse',
'contextchars'      => 'Nombre de caractères de contexte par ligne',
'stubthreshold'     => 'Taille minimale des articles courts',
'recentchangescount' => 'Nombre de titres dans les modifications récentes',
'savedprefs'        => 'Les préférences ont été sauvegardées.',
'timezonelegend'    => 'Zone horaire',
'timezonetext'      => 'Si vous ne précisez pas de décalage horaire, c\'est l\'heure de l\'Europe de l\'ouest qui sera utilisée.',
'localtime'         => 'Heure locale',
'timezoneoffset'    => 'Décalage horaire',
'servertime'	    => 'Heure du serveur',
'guesstimezone'     => 'Utiliser la valeur du navigateur',
'emailflag'         => 'Ne pas recevoir de courrier électronique<br /> des autres utilisateurs',
'defaultns'         => 'Par défaut, rechercher dans ces espaces :',
'yourlanguage' => 'Langue de l\'interface',

# Recent changes
#
'changes'	=> 'modifications',
'recentchanges' => 'Modifications récentes',
'recentchangestext' => "Suivez sur cette page les dernières modifications de {{SITENAME}}.
[[{{ns:4}}:Bienvenue|Bienvenue]] aux nouveaux participants!
Jetez un coup d'&oelig;il sur ces pages&nbsp;: [[{{ns:4}}:FAQ|foire aux questions]],
[[{{ns:4}}:Recommandations et règles à suivre|recommandations et règles à suivre]]
(notamment [[{{ns:4}}:Règles de nommage|conventions de nommage]],
[[{{ns:4}}:La neutralité de point de vue|la neutralité de point de vue]]),
et [[{{ns:4}}:Les faux-pas les plus courants|les faux-pas les plus courants]].

Si vous voulez que {{SITENAME}} connaisse le succès, merci de ne pas y inclure pas de matériaux protégés par des [[{{ns:4}}:Copyright|copyrights]]. La responsabilité juridique pourrait en effet compromettre le projet. ",
'rcloaderr'  => 'Chargement des dernières modifications',
'rcnote'  => 'Voici les <strong>$1</strong> dernières modifications effectuées au cours des <strong>$2</strong> derniers jours.',
'rcnotefrom'	=> 'Voici les modifications effectuées depuis le <strong>$2</strong> (<b>$1</b> au maximum).',
'rclistfrom'	=> 'Afficher les nouvelles modifications depuis le $1.',
# "rclinks"  => "Afficher les $1 dernières modifications effectuées au cours des $2 dernières heures / $3 derniers jours",
# "rclinks"  => "Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours.",
'showhideminor' => '$1 modifications mineures | $2 robots | $3 utilisateurs enregistrés | $4 patrolled edits',
'rclinks'	=> 'Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours; $3 modifications mineures.',
'rchide'  => 'in $4 form; $1 modifications mineures; $2 espaces secondaires; $3 modifications multiples.', // FIXME
'rcliu'	=> '; $1 modifications par des contributeurs connectés',
'diff'            => 'diff',
'hist'            => 'hist',
'hide'            => 'masquer',
'show'            => 'montrer',
'tableform'       => 'table',
'listform'        => 'liste',
'nchanges'        => '$1 modification(s)',
'minoreditletter' => 'M',
'newpageletter'   => 'N',

# Upload
#
'upload'       => 'Copier sur le serveur',
'uploadbtn'    => 'Copier un fichier',
'uploadlink'   => 'Copier des images',
'reupload'     => 'Copier à nouveau',
'reuploaddesc' => 'Retour au formulaire.',

'uploadnologin' => 'Non connecté(e)',
'uploadnologintext' => 'Vous devez être [[Special:Userlogin|connecté]] pour copier des fichiers sur le serveur.',
'uploaderror'  => 'Erreur',
'uploadtext'   => "'''STOP !''' Avant de copier votre fichier sur le serveur,
prenez connaissance des [[Project:règles d'utilisation des images|règles d'utilisation des images]] de {{SITENAME}} et assurez-vous que vous les respectez.<br />
N'oubliez pas de remplir la [[Project:Page de description d'une image|page de description de l'image]] quand celle-ci sera sur le serveur.

Pour voir les images déjà placées sur le serveur ou pour effectuer une recherche parmi celles-ci,
allez à la [[Special:Imagelist|liste des images]].
Les uploads et les suppressions sont listés dans le [[Project:Journal_des_uploads|journal des uploads]].

Utilisez le formulaire ci-dessous pour copier sur le serveur de nouvelles images destinées à illustrer vos articles.
Sur la plupart des navigateurs, vous verrez un bouton \"Browse...\" qui ouvre la fenêtre de dialogue standard de votre système d'exploitation pour l'ouverture des fichiers.
Sélectionnez un fichier, son nom apparaîtra dans le champ situé à côté du bouton.
Vous devez également confirmer, en cochant la case prévue à cet effet, que la copie de ce fichier ne viole aucun copyright.
Cliquez sur le bouton \"Envoyer\" pour terminer.
Si votre connexion est lente, l'opération peut prendre un certain temps.

Les formats recommandés sont JPEG pour les photos, PNG
pour les dessins et les autres images, et OGG pour les fichiers sonores.
Donnez à vos fichiers des noms descriptifs clairs, afin d'éviter toute confusion.
Pour incorporer l'image dans un article, placez dans celui-ci un lien rédigé comme suit:
'''<nowiki>[[image:nom_du_fichier.jpg]]</nowiki>''' ou
'''<nowiki>[[image:nom_du_fichier.png|autre texte]]</nowiki>''' ou
'''<nowiki>[[media:nom_du_fichier.ogg]]</nowiki>''' pour les sons.

N'oubliez pas que, comme toutes les pages de {{SITENAME}}, les fichiers que vous copiez peuvent être modifiés ou supprimés par les autres utilisateurs s'ils estiment que cela est dans l'intérêt du site. Sachez aussi que votre accès au serveur peut être bloqué si vous faites un mauvais usage du système.",
'uploadlog'  => 'log d\'upload',
'uploadlogpage' => 'Log_d\'upload',
'uploadlogpagetext' => 'Voici la liste des derniers fichiers copiés sur le serveur.
L\'heure indiquée est celle du serveur (UTC).
<ul>
</ul>
',
'filename'	=> 'Nom',
'filedesc'	=> 'Description',
'filestatus'	=> 'Statut du copyright',
'filesource'	=> 'Source',
'copyrightpage' => '{{ns:4}}:Copyright',
'copyrightpagename' => 'licence {{SITENAME}}',
'uploadedfiles' => 'Fichiers copiés',
'ignorewarning' => 'Ignorer l\'avertissement et copier le fichier quand même.',
'minlength'	=> 'Les noms des images doivent comporter au moins trois lettres.',
'illegalfilename'	=> 'Le nom de fichier « $1 » contient des caractères interdits dans les titres de pages. Merci de le renommer et de le copier à nouveau.',
'badfilename' => 'L\'image a été renommée « $1 ».',
'badfiletype' => '« .$1 » n\'est pas un format recommandé pour les fichiers images.',
'largefile'  => 'La taille maximale conseillée pour les images est de 100 ko.',
'successfulupload' => 'Copie réussie',
'fileuploaded' => 'Le fichier « $1 » a été copié sur le serveur.
Suivez ce lien ($2) pour accéder à la page de description, et donner des informations sur le fichier, par exemple son origine, sa date de création, son auteur, ou tout autre renseignement en votre possession.',
'uploadwarning' => 'Attention !',
'savefile'  => 'Sauvegarder le fichier',
'uploadedimage' => '« [[$1]] » copié sur le serveur',
'uploaddisabled' => 'Désolé, l\'envoi de fichier est désactivé.',
'uploadcorrupt' => 'Ce fichier est corrompu, a une taille nulle ou possède une extension invalide.
Veuillez vérifer le fichier.',
'fileexists' => 'Un fichier avec ce nom existe déjà. Merci de vérifier $1. Êtes-vous certain de vouloir modifier ce fichier ?',
'filemissing' => 'Fichier non présent',


# Image list
#
'imagelist'  => 'Liste des images',
'imagelisttext' => 'Voici une liste de $1 images classées $2.',
'getimagelist' => 'Récupération de la liste des images',
'ilsubmit'  => 'Chercher',
'showlast'  => 'Afficher les $1 dernières images classées $2.',
'byname'  => 'par nom',
'bydate'  => 'par date',
'bysize'  => 'par taille',
'imgdelete'  => 'suppr',
'imgdesc'  => 'descr',
'imglegend'  => 'Légende : (descr) = afficher/modifier la description de l\'image.',
'imghistory' => 'Historique de l\'image',
'revertimg'  => 'rétab',
'deleteimg'  => 'suppr',
'deleteimgcompletely'  => 'suppr',
'imghistlegend' => 'Légende : (actu) = ceci est l\'image actuelle, (suppr) = supprimer
cette ancienne version, (rétab) = rétablir cette ancienne version.
<br /><i>Cliquez sur la date pour voir l\'image copiée à cette date</i>.',
'imagelinks' => 'Liens vers l\'image',
'linkstoimage' => 'Les pages ci-dessous comportent un lien vers cette image :',
'nolinkstoimage' => 'Aucune page ne comporte de lien vers cette image.',
'showbigimage' => 'Télécharger une version haute résolution ($1x$2, $3 Ko)',

# Statistics

'statistics' => 'Statistiques',
'sitestats'  => 'Statistiques du site',
'userstats'  => 'Statistiques utilisateur',
'sitestatstext' => 'La base de données contient actuellement <b>$1</b> pages.

Ce chiffre inclut les pages « discussion », les pages relatives à {{SITENAME}}, les pages minimales ("bouchons"),  les pages de redirection, ainsi que d\'autres pages qui ne peuvent sans doute pas être considérées comme des articles.
Si l\'on exclut ces pages, il reste <b>$2</b> pages qui sont probablement de véritables articles.<p>
<b>$3</b> pages ont été consultées et <b>$4</b> pages modifiées.

Cela représente une moyenne de <b>$5</b> modifications par page et de <b>$6</b> consultations pour une modification.',
'userstatstext' => 'Il y a <b>$1</b> utilisateurs enregistrés. Parmi ceux-ci, <b>$2</b> ont le statut d\'administrateur (voir $3).',


# Maintenance Page
#
'maintenance'		=> 'Page de maintenance',
'maintnancepagetext'	=> 'Cette page inclut plusieurs utilitaires pour la maintenance quotidienne. Certains de ces outils ont tendance à charger la base de données; ne rechargez pas la page a chaque modification.',
'maintenancebacklink'	=> 'Retour à la page de maintenance',
'disambiguations'	=> 'Pages d\'homonymie',
'disambiguationspage'	=> '{{ns:template}}:Homonymie',
'disambiguationstext'	=> 'Les articles suivants sont liés à une <i>page d\'homonymie</i>. Or, ils devraient être liés au sujet.<br />Une page est considérée comme page d\'homonymie si elle est liée à partir de $1.<br />Les liens à partir d\'autres <i>espaces</i> ne sont pas pris en compte.',
'doubleredirects'	=> 'Double redirection',
'doubleredirectstext'	=> '<b>Attention:</b> cette liste peut contenir des « faux positifs ». Dans ce cas, c\'est probablement la page du premier #REDIRECT contient aussi du texte.<br />Chaque ligne contient les liens à la 1re et 2e page de redirection, ainsi que la première ligne de cette dernière, qui donne normalement la « vraie » destination. Le premier #REDIRECT devrait lier vers cette destination.',
'brokenredirects'	=> 'Redirections cassées',
'brokenredirectstext'	=> 'Ces redirections mènent a une page qui n\'existe pas.',
'selflinks'		=> 'Page avec un lien circulaire',
'selflinkstext'		=> 'Les pages suivantes contiennent un lien vers elles-mêmes, ce qui n\'est pas permis.',
'mispeelings'           => 'Pages avec fautes d\'orthographe',
'mispeelingstext'               => 'Les pages suivantes contiennent une faute d\'orthographe courante (la liste de celles-ci est sur $1). L\'orthographe correcte est peut-être (ceci).',
'mispeelingspage'       => 'Liste de fautes d\'orthographe courantes',

# FIXME les 3 messages suivants ne sont plus utilisés (plus de page Special:Intl)
'missinglanguagelinks'  => 'Liens inter-langues manquants',
'missinglanguagelinksbutton'    => 'Je n\'ai pas trouvé de lien/langue pour cette page',
'missinglanguagelinkstext'      => 'Ces articles ne lient pas à leur contrepartie sur $1. Les redirections et les liens ne sont pas affichés.',


# Miscellaneous special pages
#
'orphans'       => 'Pages orphelines',
'lonelypages'   => 'Pages orphelines',
'unusedimages'  => 'Images orphelines',
'popularpages'  => 'Pages les plus consultées',
'nviews'        => '$1 consultations',
'wantedpages'   => 'Pages les plus demandées',
'nlinks'        => '$1 références',
'allpages'      => 'Toutes les pages',
'randompage'    => 'Une page au hasard',
'shortpages'    => 'Articles courts',
'longpages'     => 'Articles longs',
'listusers'     => 'Liste des participants',
'specialpages'  => 'Pages spéciales',
'spheading'     => 'Pages spéciales',
'protectpage'   => 'Protéger la page',
'recentchangeslinked' => 'Suivi des liens',
'rclsub'        => '(des pages liées à « $1 »)',
'debug'         => 'Déboguer',
'newpages'      => 'Nouvelles pages',
'ancientpages'	=> 'Articles les plus anciens',
'move'		=> 'renommer',
'movethispage'  => 'Renommer la page',
'unusedimagestext' => '<p>N\'oubliez pas que d\'autres sites, comme certains Wikipédias non francophones, peuvent contenir un lien direct vers cette image, et que celle-ci peut être placée dans cette liste alors qu\'elle est en réalité utilisée.',
'booksources'   => 'Ouvrages de référence',
'booksourcetext' => 'Voici une liste de liens vers d\'autres sites qui vendent des livres neufs et d\'occasion et sur lesquels vous trouverez peut-être des informations sur les ouvrages que vous cherchez. {{SITENAME}} n\'étant liée à aucune de ces sociétés, elle n\'a aucunement l\'intention d\'en faire la promotion.',
'alphaindexline' => '$1 à $2',
'version' => 'Version',

# All pages
#
'allinnamespace' => 'Toutes les pages (espace $1)',
'allpagesnext' => 'Suivant',
'allpagesprev' => 'Précédent',
'allpagessubmit' => 'Valider',

# Email this user
#
'mailnologin' => 'Pas d\'adresse',
'mailnologintext' => 'Vous devez être [[Special:Userlogin|connecté]]
et avoir indiqué une adresse électronique valide dans vos [[Special:Preferences|préférences]]
pour pouvoir envoyer un message à un autre utilisateur.',
'emailuser'  => 'Envoyer un message à cet utilisateur',
'emailpage'  => 'Envoyer un email à l\'utilisateur',
'emailpagetext' => 'Si cet utilisateur a indiqué une adresse électronique valide dans ses préférences, le formulaire ci-dessous lui enverra un message.
L\'adresse électronique que vous avez indiquée dans vos préférences apparaîtra dans le champ « Expéditeur » de votre message afin que le destinataire puisse vous répondre.',
'noemailtitle' => 'Pas d\'adresse électronique',
'noemailtext' => 'Cet utilisateur n\'a pas spécifié d\'adresse électronique valide ou a choisi de ne pas recevoir de courrier électronique des autres utilisateurs.',

'emailfrom'  => 'Expéditeur',
'emailto'  => 'Destinataire',
'emailsubject' => 'Objet',
'emailmessage' => 'Message',
'emailsend'  => 'Envoyer',
'emailsent'  => 'Message envoyé',
'emailsenttext' => 'Votre message a été envoyé.',
'usermailererror' => 'Erreur de mail :',
'defemailsubject' => 'courriel envoyé depuis {{SITENAME}}',

# Watchlist
#
'watchlist'	=> 'Liste de suivi',
'watchlistsub'	=> '(pour l\'utilisateur « $1 »)',
'nowatchlist'	=> 'Votre liste de suivi ne contient aucun article.',
'watchnologin'	=> 'Non connecté',
'watchnologintext' => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier votre liste.',
'addedwatch'	=> 'Ajouté à la liste',
'addedwatchtext' => 'La page « $1 » a été ajoutée à votre [[Special:Watchlist|liste de suivi]].
Les prochaines modifications de cette page et de la page discussion associée seront répertoriées ici, et la page apparaîtra \'\'\'en gras\'\'\' dans la [[Special:Recentchanges|liste des modifications récentes]] pour être repérée plus facilement.

Pour supprimer cette page de votre liste de suivi, cliquez sur « Ne plus suivre » dans le cadre de navigation.',
'removedwatch'	=> 'Supprimée de la liste de suivi',
'removedwatchtext' => 'La page « $1 » a été supprimée de votre liste de suivi.',
'watch'		=> 'Suivre',
'watchthispage'	=> 'Suivre cette page',
'unwatch'	=> 'Ne plus suivre',
'unwatchthispage' => 'Ne plus suivre',
'notanarticle'	=> 'Aucun article',
'watchnochange' => "Aucune des pages que vous suivez n'a été modifiée pendant la période affichée",
'watchdetails' => 'Vous suivez $1 pages, sans compter les pages de discussion.  [$4 Afficher et modifier la liste complète].',
'watchmethod-recent' => 'vérification des modifications récentes des pages suivies',
'watchmethod-list' => 'vérification des pages suivies pour des modifications récentes',
'removechecked' => 'Retirer de la liste de suivi les articles sélectionnés',
'watchlistcontains' => 'Votre liste de suivi contient $1 pages',
'watcheditlist' => 'Ceci est votre liste de suivi par ordre alphabétique. Sélectionnez les pages que vous souhaitez retirer de la liste et cliquez le bouton « retirer de la liste de suivi » en bas de l\'écran.',
'removingchecked' => 'Les articles sélectionnés sont retirés de votre liste de suivi...',
'couldntremove' => 'Impossible de retirer l\'article « $1 »...',
'iteminvalidname' => 'Problème avec l\'article « $1 » : le nom est invalide...',
'wlnote' => 'Ci-dessous se trouvent les $1 dernières modifications depuis les <b>$2</b> dernières heures.',
'wlshowlast' => 'Montrer les dernières $1 heures $2 jours $3',
'wlsaved' => 'La liste de suivi n\'est remise à jour qu\'une fois par heure pour alléger la charge sur le serveur.',

# Delete/protect/revert
#
'deletepage'	=> 'Supprimer une page',
'confirm'	=> 'Confirmer',
'excontent'	=> 'contenant « $1 »',
'exbeforeblank' => 'Avant son blanchiment, cette page contenait : $1',
'exblank'	=> 'page vide',
'confirmdelete' => 'Confirmer la suppression',
'deletesub'	=> '(Suppression de « $1 »)',
'historywarning' => 'Attention : La page que vous êtes sur le point de supprimer a un historique : ',
'confirmdeletetext' => 'Vous êtes sur le point de supprimer définitivement de la base de données une page ou une image, ainsi que toutes ses versions antérieures.

Veuillez confirmer que c\'est bien là ce que vous voulez faire, que vous en comprenez les conséquences et que vous faites cela en accord avec les [[{{ns:4}}:Recommandations Et Règles à  Suivre|recommandations et règles à suivre]].',
'actioncomplete' => 'Suppression effectuée',
'deletedtext'	=> '« $1 » a été supprimé.
Voir $2 pour une liste des suppressions récentes.',
'deletedarticle' => 'a effacé « $1 »',
'dellogpage'	=> 'Historique des effacements',
'dellogpagetext' => 'Voici la liste des suppressions récentes.
L\'heure indiquée est celle du serveur (UTC).',
'deletionlog'	=> 'historique des effacements',
'reverted'	=> 'Rétablissement de la version précédente',
'deletecomment' => 'Motif de la suppression',
'imagereverted' => 'La version précédente a été rétablie.',
'rollback'	=> 'révoquer modifications',
'rollback_short' => 'Révoquer',
'rollbacklink'	=> 'révoquer',
'rollbackfailed' => 'La révocation a échoué',
'cantrollback'	=> 'Impossible de révoquer : dernier auteur est le seul à avoir modifié cet article',
'alreadyrolled'	=> 'Impossible de révoquer la dernière modification de « $1 » effectuée par [[User:$2|$2]] ([[User talk:$2|Talk]]); quelqu\'un d\'autre à déjà modifer ou révoquer l\'article.

La dernière modificaion a été effectuée par [[User:$3|$3]] ([[User talk:$3|Talk]]).', // FIXME: namespaces
#   only shown if there is an edit comment
'editcomment' => 'Le résumé de la modification était: <i>« $1 »</i>.',
'revertpage'	=> 'restitution de la dernière modification de $1',
'protectlogpage' => 'Log_de_protection',
'protectlogtext' => "Voir les [[{{ns:4}}:Page protégée|directives concernant les pages protégées]].",
'protectedarticle' => 'a protégé [[$1]]',
'unprotectedarticle' => 'a déprotégé [[$1]]',

'protectsub' => '(Protéger « $1 »)',
'confirmprotect' => 'Confirmer la protection',
'confirmprotecttext' => 'Voulez-vous réellement protéger cette page ?',
'protectcomment' => 'Raison du bloquage',

'unprotectsub' => '(Débloque « $1 »)',
'confirmunprotecttext' => 'Voulez-vous réellement déprotéger cette page ?',
'confirmunprotect' => 'Raison de la déprotection',
'unprotectcomment' => 'Raison du débloquage',
'protectmoveonly' => 'Protéger uniquement les déplacements',


# Groups
#
'addgroup' => 'Ajouter un groupe',
'editgroup' => 'Modification du groupe',
'editusergroup' => 'Modification des groupes utilisateurs',

# Special:Undelete
#
'undelete'	=> 'Restaurer la page effacée',
'undeletepage'	=> 'Voir et restaurer la page effacée',
'undeletepagetext' => 'Ces pages ont été effacées et se trouvent dans la corbeille, elles sont toujours dans la base de donnée et peuvent être restaurées.
La corbeille peut être effacée périodiquement.',

'undeletearticle' => 'Restaurer les articles effacés',
'undeleterevisions' => '$1 révisions archivées',
'undeletehistory' => 'Si vous restaurez la page, toutes les révisions seront restaurées dans l\'historique.

Si une nouvelle page avec le même nom a été crée depuis la suppression, les révisions restaurées apparaîtront dans l\'historique antérieur et la version courante ne sera pas automatiquement remplacée.',
'undeleterevision' => 'Version effacée ($1)',
'undeletebtn'	=> 'Restaurer!',
'undeletedarticle' => 'restauré « $1 »',
'undeletedtext'   => 'L\'article [[$1]] a été restauré avec succès.

Voir [[{{ns:4}}:Trace des effacements]] pour la liste des suppressions et des restaurations récentes.',
'undeletedrevisions' => '$1 versions ont été restaurées',

# Contributions
#
'contributions'	=> 'Contributions',
'mycontris'	=> 'Mes contributions',
'contribsub'	=> 'Pour $1',
'nocontribs'	=> 'Aucune modification correspondant à ces critères n\'a été trouvée.',
'ucnote'	=> 'Voici les <b>$1</b> dernières modifications effectuées par cet utilisateur au cours des <b>$2</b> derniers jours.',
'uclinks'	=> 'Afficher les $1 dernières modifications; afficher les $2 derniers jours.',
'uctop'		=> ' (dernière)',

# What links here
#
'whatlinkshere' => 'Pages liées',
'notargettitle' => 'Pas de cible',
'notargettext'	=> 'Indiquez une page cible ou un utilisateur cible.',
'linklistsub'	=> '(Liste de liens)',
'linkshere'	=> 'Les pages ci-dessous contiennent un lien vers celle-ci :',
'nolinkshere'	=> 'Aucune page ne contient de lien vers celle-ci.',
'isredirect'	=> 'page de redirection',

# Block/unblock IP
#
'blockip'	=> 'Bloquer une adresse IP ou un utilisateur',
'blockiptext'	=> 'Utilisez le formulaire ci-dessous pour bloquer l\'accès en écriture à partir d\'une adresse IP donnée ou d\'un nom d\'utilisateur.

Une telle mesure ne doit être prise que pour empêcher le vandalisme et en accord avec [[{{ns:4}}:Recommandations et règles à suivre|recommandations et règles à suivre]].
Donnez ci-dessous une raison précise (par exemple en indiquant les pages qui ont été vandalisées).',
'ipaddress'	=> 'Adresse IP ou pseudonyme',
'ipbreason'	=> 'Motif du blocage',
'ipbsubmit'	=> 'Bloquer cette adresse',
'badipaddress'	=> 'L\'adresse IP n\'est pas correcte.',
'blockipsuccesssub' => 'Blocage réussi',
'blockipsuccesstext' => 'L\'adresse IP « $1 » a été bloquée.<br />Vous pouvez consulter sur cette [[Special:Ipblocklist|page]] la liste des adresses IP bloquées.',
'unblockip'	=> "Débloquer une adresse IP",
'unblockiptext' => 'Utilisez le formulaire ci-dessous pour rétablir l\'accès en écriture
à partir d\'une adresse IP précédemment bloquée.',
'ipusubmit'	=> 'Débloquer cette adresse',
'ipusuccess'	=> 'L\'adresse IP « $1 » débloquée.',
'ipblocklist'	=> 'Liste des blocages',
'blocklistline' => '$1 (expire le $4): $2 a bloqué $3',
'blocklink'	=> 'bloquer',
'unblocklink'	=> 'débloquer',
'contribslink'	=> 'contribs',
'autoblocker'	=> 'Bloqué automatiquement parce que vous partagez une adresse IP avec « $1 ». Raison : « $2 ».',
'blocklogpage'	=> 'Log de blocage',
'blocklogentry'	=> 'blocage de « $1 »',
'blocklogtext'	=> 'Ceci est la trace des blocages et déblocages des utilisateurs. Les adresses IP automatiquement bloquées ne sont pas listées. Consultez la [[Special:Ipblocklist|liste des utilisateurs bloqués]] pour voir qui est actuellement effectivement bloqué.',
'unblocklogentry'	=> 'déblocage de « $1 »',
'ipb_expiry_invalid' => 'temps d\'expiration invalide.',
'ip_range_invalid' => 'Bloc IP incorrect.',
'proxyblocker' => 'Bloqueur de proxy',
'proxyblockreason' => 'Votre ip a été bloquée car c\'est un proxy ouvert. Merci de contacter votre fournisseur d\'accès internet ou votre support technique et de l\'informer de ce problème de sécurité.',
'proxyblocksuccess' => 'Terminé.',
'ipbexpiry' => 'Durée du blocage',

# Developer tools
#
'lockdb'  => 'Verrouiller la base',
'unlockdb'  => 'Déverrouiller la base',
'lockdbtext' => 'Le verrouillage de la base de données empêchera tous les utilisateurs de modifier des pages, de sauvegarder leurs préférences, de modifier leur liste de suivi et d\'effectuer toutes les autres opérations nécessitant des modifications dans la base de données.
Veuillez confirmer que c\'est bien là ce que vous voulez faire et que vous débloquerez la base dès que votre opération de maintenance sera terminée.',
'unlockdbtext' => 'Le déverrouillage de la base de données permettra à nouveau à tous les utilisateurs de modifier des pages, de mettre à jour leurs préférences et leur liste de suivi, ainsi que d\'effectuer les autres opérations nécessitant des modifications dans la base de données.

Veuillez confirmer que c\'est bien là ce que vous voulez faire.',
'lockconfirm' => 'Oui, je confirme que je souhaite verrouiller la base de données.',
'unlockconfirm' => 'Oui, je confirme que je souhaite déverrouiller la base de données.',

'lockbtn'  => 'Verrouiller la base',
'unlockbtn'  => 'Déverrouiller la base',
'locknoconfirm' => 'Vous n\'avez pas coché la case de confirmation.',
'lockdbsuccesssub' => 'Verrouillage de la base réussi.',
'unlockdbsuccesssub' => 'Base déverrouillée.',
'lockdbsuccesstext' => 'La base de données de {{SITENAME}} est verrouillée.

N\'oubliez pas de la déverrouiller lorsque vous aurez terminé votre opération de maintenance.',
'unlockdbsuccesstext' => 'La base de données de {{SITENAME}} est déverrouillée.',

# Special:Makesysop
'bureaucratlog'		=> 'Log_bureaucrate',
'bureaucratlogentry'	=> 'Droits de l\'utilisateur « $1 » mis à « $2 »',
'makesysoptitle'	=> 'Donne les droits d\'adminitrateur.',
'makesysoptext'		=> 'Ce formulaire est utilisé par les bureaucrates pour donner les droits d\'administrateur.
Tapez le nom de l\'utilisateur dans la boite et pressez le bouton pour lui donner les droits.',
'makesysopname'		=> 'Nom de l\'utilisateur:',
'makesysopsubmit'	=> 'Donner les droits d\'adminitrateur à cet utilisateur',
'makesysopok'		=> '<b>L\'utilisateur « $1 » est désormais administrateur.</b>',
'makesysopfail'		=> '<b>L\'utilisateur « $1 » n\'a pas pu recevoir les droits d\'adminitrateurs. (Avez vous entré le nom correctement?)</b>',
'rights'			=> 'Droits:',
'set_user_rights'	=> 'Met les droits de l\'utilisateur',
'user_rights_set'	=> '<b>Les droits de l\'utilisateur « $1 » sont mis à jour</b>',
'setbureaucratflag'	=> 'Ajouter le statut de bureaucrate',
'set_rights_fail'	=> '<b>Les droits de l\'utilisateur « $1 » n\'ont pas pu être mis en place. (Avez vous entré le nom correctement?)</b>',
'makesysop'         => 'Donner les droits d\'adminitrateur à un utilisateur',

# Special:Validate
'val_article_lists' => 'Liste d\'articles validés',
'val_clear_old' => 'Supprimer mes données de validation pour $1',
'val_details_th_user' => 'Utilisateur $1',
'val_merge_old' => 'Utiliser mes précédents choix pour les choix marqués \'Sans opinion\'',
'val_no_anon_validation' => 'Vous devez être identifié pour valider un article.',
'val_no' => 'Non',
'val_noop' => 'Sans opinion',
'val_page_validation_statistics' => 'Statistiques de validation pour $1',
'val_percent' => '<b>$1%</b><br />($2 sur $3 points<br />par $4 utilisateurs)',
'val_percent_single' => '<b>$1%</b><br />($2 sur $3 points<br />par un utilisateur)',
'val_rev_for' => 'Correction pour $1',
'val_revision' => 'Révision',
'val_show_my_ratings' => 'Voir mes validations',
'val_stat_link_text' => 'Statistiques de validation pour cet article',
'val_table_header' => "<tr><th>Class</th>$1<th colspan=4>Opinion</th>$1<th>Commentaire</th></tr>\n",
'val_tab' => 'Valider',
'val_this_is_current_version' => 'ceci est la dernière version',
'val_total' => 'Total',
'val_user_validations' => 'Cet utilisateur a validé $1 pages.',
'val_validate_article_namespace_only' => 'Seul les articles peuvent être validés. Cette page n\'est <i>pas</i> un article.',
'val_validated' => 'Validation effectuée.',
'val_validate_version' => 'Valider cette version',
'val_version_of' => 'Version de $1' ,
'val_version' => 'Version',
'val_view_version' => 'Voir cette version',
'val_yes' => 'Oui',


# Spam
#
'spamprotectionmatch' => 'Le texte suivant a déclenché le détecteur de spam: $1',
'spamprotectiontext' => 'Page automatiquement protégée pour cause de spam',
'spamprotectiontitle' => 'Page automatiquement protégée pour cause de spam',

# Patrolling
#
'markaspatrolleddiff' => 'Marquer comme vérifiée',
'markaspatrolledtext' => 'Marquer cet article comme vérifié',
'markedaspatrolled' => 'Marqué comme vérifié',
'markedaspatrolledtext' => 'La version sélectionnée a été marquée comme vérifiée.',
'rcpatroldisabledtext' => 'La fonctionnalité de surveillance des modifications récentes n\'est pas activée.',

# Move page
#
'movepage'  => 'Renommer un article',
'movepagetext' => 'Utilisez le formulaire ci-dessous pour renommer un article, en déplaçant toutes ses versions antérieures vers le nouveau nom.

Le titre précédent deviendra une page de redirection vers le nouveau titre. Les liens vers l\'ancien titre ne seront pas modifiés et la page discussion, si elle existe, ne sera pas déplacée.

\'\'\'ATTENTION!\'\'\'
Il peut s\'agir d\'un changement radical et inattendu pour un article souvent consulté; assurez-vous que vous en comprenez bien les conséquences avant de procéder.',
'movepagetalktext' => 'La page de discussion associée, si présente, sera automatiquement renommée avec \'\'\'sauf si:\'\'\'
*Vous renommez une page vers un autre espace,
*Une page de discussion existe déjà avec le nouveau nom, ou
*Vous avez désélectionné le bouton ci-dessous.

Dans ce cas, vous devrez renommer ou fusionner la page manuellement si vous le désirez.',

'movearticle'	=> 'Déplacer l\'article',
'movenologin'	=> 'Non connecté',
'movenologintext' => 'Pour pouvoir renommer un article, vous devez être [[Special:Userlogin|connecté]] en tant qu\'utilisateur enregistré.',
'newtitle'	=> 'Nouveau titre',
'movepagebtn'	=> 'Renommer l\'article',
'pagemovedsub' => 'Déplacement réussi',
'pagemovedtext' => 'L\'article « [[$1]] » a été déplacé vers « [[$2]] ».',
'articleexists' => 'Il existe déjà un article portant ce titre, ou le titre que vous avez choisi n\'est pas valide. Veuillez en choisir un autre.',
'talkexists'	=> 'La page elle-même a été déplacée avec succès, mais la page de discussion n\'a pas pu être déplacée car il en existait déjà une sous le nouveau nom. S\'il vous plait, fusionnez les manuellement.',
'1movedto2_redir' => '$1 déplacé vers $2 (redirect)',
'movedto'  => 'renommé en',
'movetalk'  => 'Renommer aussi la page « discussion », s\'il y a lieu.',
'talkpagemoved' => 'La page discussion correspondante a également été déplacée.',
'talkpagenotmoved' => 'La page discussion correspondante n\'a <strong>pas</strong> été déplacée.',
'1movedto2' => '$1 déplacé vers $2',
'movereason' => 'Raison du déplacement',


# Export page
'export'	=> 'Exporter des pages',
'exporttext'	=> 'Vous pouvez exporter en XML le texte et l\'historique d\'une page ou d\'un ensemble de pages; le résultat peut alors être importé dans un autre wiki fonctionnant avec le logiciel MediaWiki, transformé ou sauvegardé pour votre usage personnel.',
'exportcuronly'	=> 'Exporter uniquement la version courante sans l\'historique',

# Namespace 8 related

'allmessages'	=> 'Tous les messages',
'allmessagestext'	=> 'Ceci est la liste de tous les messages disponibles dans l\'espace MediaWiki',

# Thumbnails

'thumbnail-more'	=> 'Agrandir',
'missingimage'		=> '<b>Image manquante</b><br /><i>$1</i>',

# Special:Import
'import'	=> 'Importer des pages',
'importfailed'	=> 'Échec de l\'import : $1',
'importhistoryconflict' => 'Il y a un conflit dans l\'historique des versions (cette page à peut être déjà été importée avant)',
'importnotext'	=> 'Vide ou sans texte',
'importsuccess'	=> 'L\'import a réussi !',
'importtext'	=> 'Exportez un fichier depuis le wiki source en utilisant la fonction Special:Export, sauvez la page sur votre disque puis envoyez là ici.',

# Keyboard access keys for power users
'accesskey-compareselectedversions' => 'v',
'accesskey-minoredit'		=> 'i',
'accesskey-preferences'		=> '',
'accesskey-preview'			=> 'p',
'accesskey-save'			=> 's',
'accesskey-search'			=> 'f',

# tooltip help for the main actions
'tooltip-compareselectedversions' => 'Voir les différences entre les deux versions séléctionnées de cette page. [alt-v]',
'tooltip-minoredit' => 'Marquer cette modification comme mineur [alt-i]',
'tooltip-save' => 'Sauvegarder vos modifications [alt-s]',
'tooltip-search' => 'Rechercher dans ce wiki',

# Metadata
'nocreativecommons' => 'Les données méta « Creative Commons RDF » sont désactivées sur ce serveur.',
'nodublincore' => 'Les données méta « Dublin Core RDF » sont désactivées sur ce serveur.',
'notacceptable' => 'Ce serveur wiki ne peut pas fournir les données dans un format que votre client est capable de lire.',

# Attribution
'anonymous'	=> 'Utilisateur(s) anonyme(s) de {{SITENAME}}',
'siteuser'	=> 'Utilisateur $1 de {{SITENAME}}',
'lastmodifiedby' => 'Cette page a été modifiée pour la dernière fois le $1 par $2',
'and'	=> 'et',
'contributions' => 'Basé sur le travail de $1.',
'siteusers'	=> 'Utilisateur(s) $1 de {{SITENAME}}',
'creditspage' => 'Page crédits',

# confirmemail
'confirmemail' => 'Confirmer email',
'confirmemail_text' => 'Ce wiki nécessite la vérification de votre adresse email avant de pouvoir utiliser toute fonction de messagerie. Utilisez le bouton ci dessous pour envoyer un email de confirmation à votre adresse. L\'email contiendra un lien contenant un code, chargez ce lien dans votre navigateur pour valider votre adresse.',
'confirmemail_send' => 'Envoyer un code de confirmation',
'confirmemail_sent' => 'Email de confirmation envoyé',
'confirmemail_sendfailed' => 'Impossible d\'envoyer l\'email de confirmation. Vérifiez votre adresse.',
'confirmemail_invalid' => 'Code de confirmation incorrect. Le code a peut être expiré',
'confirmemail_success' => 'Votre email est confirmée. Vous pouvez maintenant vous connecter et profiter du wiki.',
'confirmemail_loggedin' => 'Votre adresse est maintenant confirmée',
'confirmemail_error' => 'Un problème est survenu en voulant enregistrer votre confirmation',
'confirmemail_subject' => '{{SITENAME}} email address confirmation',
'confirmemail_body' => 'Quelqu\'un, probablement vous avec l\'adresse email $1, a enregistré un compte « $2 » avec cette email sur le site {{SITENAME}}.

Pour confirmer que ce compte vous appartient vraiment et activer les fonctions de messagerie sur {{SITENAME}}, veuillez suivre le lien ci dessous dans votre navigateur :

$3

Si il ne s\'agit pas de vous, n\'ouvrez pas le lien. Ce code de confirmation expirera le $4.',


# Math
'mw_math_html' => 'HTML si possible, autrement PNG',
'mw_math_mathml' => 'MathML',
'mw_math_modern' => 'Pour les navigateurs modernes',
'mw_math_png' => 'Toujours produire une image PNG',
'mw_math_simple' => 'HTML si très simple, autrement PNG',
'mw_math_source' => 'Laisser le code TeX original',


'usercssjsyoucanpreview' => "'''Astuce :''' utilisez le bouton '''Prévisualisation''' pour tester votre nouvelle feuille css/js avant de l'enregistrer.",
'usercsspreview' => "'''Rappelez-vous que vous êtes en train de prévisualiser votre propre feuille css et qu'elle n'a pas encore été enregistrée !'''",
'userjspreview' => "'''Rappelez-vous que vous êtes en train de visualiser ou de tester votre code javascript et qu'il n'a pas encore été enregistré !'''",
'validate' => 'Valider la page',

# EXIF
'exif-imagewidth' => 'Largeur',
'exif-imagelength' => 'Hauteur',
'exif-compression' => 'Type de compression',
'exif-samplesperpixel' => 'Nombre d\'échantillons',
'exif-xresolution' => 'Résolution de l\'image en largeur',
'exif-yresolution' => 'Résolution de l\'image en hauteur',
'exif-jpeginterchangeformat' => 'Position du SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Taille en octet des données JPEG',
'exif-transferfunction' => 'Fonction de transfert',
'exif-datetime' => 'Date et heure de changement du fichier',
'exif-imagedescription' => 'Titre de l\'image',
'exif-make' => 'Fabricant de l\'appareil',
'exif-model' => 'Modèle de l\'appareil',
'exif-software' => 'Logiciel utilisé',
'exif-artist' => 'Auteur',
'exif-copyright' => 'Détenteur du copyright',
'exif-exifversion' => 'Version exif',
'exif-makernote' => 'Notes du fabricant',
'exif-relatedsoundfile' => 'Fichier audio lié',
'exif-flash' => 'Flash',
'exif-whitebalance' => 'Balance des blancs',
'exif-contrast' => 'Contraste',
'exif-saturation' => 'Saturation',
'exif-compression-1' => 'Sans compression',
'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Inversée horizontalement',
'exif-orientation-3' => 'Tournée de 180°',
'exif-orientation-4' => 'Inversée verticalement',
'exif-orientation-5' => 'Tournée de 90° à gauche et inversée verticalement',
'exif-orientation-6' => 'Tournée de 90° à droite',
'exif-orientation-7' => 'Tournée de 90° à droite et inversée verticalement',
'exif-orientation-8' => 'Tournée de 90° à gauche',
'exif-componentsconfiguration-0' => 'n\'existe pas',


// exifgps:

);

class LanguageFr extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListFr ;
		return $wgBookstoreListFr ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesFr;
		return $wgNamespaceNamesFr;
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesFr, $wgSitename;

		foreach ( $wgNamespaceNamesFr as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( $wgSitename == 'Wikipédia' ) {
			if( 0 == strcasecmp( 'Wikipedia', $text ) ) return 4;
			if( 0 == strcasecmp( 'Discussion_Wikipedia', $text ) ) return 5;
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsFr;
		return $wgQuickbarSettingsFr;
	}

	function getSkinNames() {
		global $wgSkinNamesFr;
		return $wgSkinNamesFr;
	}

	function date( $ts, $adj = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ' ' .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  ' ' . substr( $ts, 0, 4 );
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . ' à ' . $this->time( $ts, $adj );
	}

	var $digitTransTable = array(
		',' => "\xc2\xa0", // @bug 2749
		'.' => ','
	);

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		return $wgTranslateNumerals ? strtr($number, $this->digitTransTable ) : $number;
	}

	function getMessage( $key ) {
		global $wgAllMessagesFr, $wgAllMessagesEn;
		if( isset( $wgAllMessagesFr[$key] ) ) {
			return $wgAllMessagesFr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

}

?>
