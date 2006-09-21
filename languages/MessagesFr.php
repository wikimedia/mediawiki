<?php
/** French (Français)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */
$quickbarSettings = array(
	'Aucune', 'Gauche', 'Droite', 'Flottante à gauche'
);

$skinNames = array(
	'standard'  => 'Standard',
	'nostalgia' => 'Nostalgie',
);

$bookstoreList = array(
	'Amazon.fr'    => 'http://www.amazon.fr/exec/obidos/ISBN=$1',
	'alapage.fr'   => 'http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&',
	'fnac.com'     => 'http://www3.fnac.com/advanced/book.do?isbn=$1',
	'chapitre.com' => 'http://www.chapitre.com/frame_rec.asp?isbn=$1',
);

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Special',
	NS_MAIN           => '',
	NS_TALK           => 'Discuter',
	NS_USER           => 'Utilisateur',
	NS_USER_TALK      => 'Discussion_Utilisateur',
	NS_PROJECT        => '$1',
	NS_PROJECT_TALK   => 'Discussion_$1',
	NS_IMAGE          => 'Image',
	NS_IMAGE_TALK     => 'Discussion_Image',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
	NS_TEMPLATE       => 'Modèle',
	NS_TEMPLATE_TALK  => 'Discussion_Modèle',
	NS_HELP           => 'Aide',
	NS_HELP_TALK      => 'Discussion_Aide',
	NS_CATEGORY       => 'Catégorie',
	NS_CATEGORY_TALK  => 'Discussion_Catégorie'
);
$linkTrail = '/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sDu';

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy both' => 'F j, Y à H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y à H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd both' => 'Y F j à H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(

# User preference Toggles

'tog-underline' => 'Liens soulignés',
'tog-highlightbroken' => 'Liens vers les sujets non existants en rouge',
'tog-justify' => 'Paragraphes justifiés',
'tog-hideminor' => 'Cacher les <i>Modifications récentes</i> mineures',
'tog-extendwatchlist' => 'Liste de suivi améliorée',
'tog-usenewrc' => 'Modifications récentes améliorées<br /> (certains navigateurs seulement)',
'tog-numberheadings' => 'Numérotation automatique des titres',
'tog-showtoolbar' => 'Montrer la barre de menu d\'édition',
'tog-editondblclick' => 'Double-cliquer pour modifier une page (nécessite JavaScript)',
'tog-editsection'	=> 'Modifier une section via les liens [modifier]',
'tog-editsectiononrightclick'	=> 'Éditer une section en cliquant à droite<br /> sur le titre de la section',
'tog-showtoc'	=> 'Afficher la table des matières<br /> (pour les articles ayant plus de 3 sections)',
'tog-rememberpassword' => 'Se souvenir de mon mot de passe (cookie)',
'tog-editwidth' => 'La fenêtre d\'édition s\'affiche en pleine largeur',
'tog-watchcreations' => 'Ajouter les pages que vous créez à votre liste de suivi',
'tog-watchdefault' => 'Ajouter les pages que vous modifiez à votre liste de suivi',
'tog-minordefault' => 'Mes modifications sont considérées<br /> comme mineures par défaut',
'tog-previewonfirst' => 'Montrer la prévisualisation lors de la première édition',
'tog-nocache' => 'Désactiver le cache des pages',
'tog-enotifwatchlistpages' 	=> 'Avertissez-moi par courriel en cas de modification de la page',
'tog-enotifusertalkpages' 	=> 'Avertissez-moi par courriel en cas de modification de ma page de discussion',
'tog-enotifminoredits' 		=> 'Avertissez-moi par courriel même en cas de modification mineure',
'tog-enotifrevealaddr' 		=> 'Affichez mon adresse électronique dans les courriels d\'avertissement',
'tog-shownumberswatching' 	=> 'Afficher le nombre d\'utilisateurs qui suivent cette page',
'tog-fancysig' => 'Signature brute (sans lien automatique)',
'tog-externaleditor' => 'Utiliser un éditeur externe par défaut',
'tog-externaldiff' => 'Utiliser un comparateur externe par défaut',
'tog-showjumplinks' => 'Activer les liens d\'accessibilité rapide.',
'tog-uselivepreview' => 'Utiliser l\'aperçu rapide (JavaScript) (Expérimental)',
'tog-autopatrol' => 'Marquer mes éditions comme patrouillées',
'tog-forceeditsummary' => 'M\'avertir lorsque je n\'ai pas completé le contenu de la boîte de commentaires',
'tog-watchlisthideown' => 'Cacher mes propres modifications dans la liste de suivi',
'tog-watchlisthidebots' => 'Cacher dans la liste de suivi les modifications faites par les bots',

'underline-always' => 'Toujours',
'underline-never' => 'Jamais',
'underline-default' => 'Par défaut',

'skinpreview' => '(Prévisualisation)',

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

'categories'	=> '{{PLURAL:$1|Catégorie|Catégories}}',
'category_header' => 'Articles dans la catégorie « $1 ».',
'subcategories'	=> 'Sous-catégories',

'mainpage'      => 'Accueil',
'mainpagetext'	=> '<big>\'\'\'MediaWiki a été installé avec succès.\'\'\'</big>',
'mainpagedocfooter' => 'Consultez le [http://meta.wikipedia.org/wiki/Aide:Contenu Guide de l\'utilisateur] pour plus d\'informations sur l\'utilisation de ce logiciel.',
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
'sitesupport'	=> 'Faire un don',
'sitesupport-url' => 'Project:D',
'faqpage'       => '{{ns:4}}:FAQ',
'edithelp'      => 'Aide',
'newwindow'	=> '(s\'ouvre dans une nouvelle fenêtre)',
'edithelppage'  => '{{ns:help}}:Comment modifier une page',
'cancel'        => 'Annuler',
'qbfind'        => 'Rechercher',
'qbbrowse'      => 'Défiler',
'qbedit'        => 'Modifier',
'qbpageoptions' => 'Page d\'option',
'qbpageinfo'    => 'Page d\'information',
'qbmyoptions'   => 'Mes options',
'qbspecialpages'=> 'Pages spéciales',
'moredotdotdot'	=> 'Et plus...',
'mypage'        => 'Ma page',
'mytalk'        => 'Ma page de discussion',
'anontalk'	=> 'Discussion avec cette adresse IP',

# Metadata in edit box

'metadata' => '<b>Metadata</b>',

'currentevents' => 'Actualités',
'currentevents-url' => 'Actualités',
'disclaimers'	=> 'Avertissements',
'disclaimerpage' => '{{ns:4}}:Avertissements généraux',
'privacy' => 'Politique de confidentialité',
'privacypage' => 'Project:Confidentialité',
'errorpagetitle' => 'Erreur',
'returnto'      => 'Revenir à la page $1.',
'tagline'       => 'Un article de {{SITENAME}}.',
'search'        => 'Rechercher',
'searchbutton'  => 'Rechercher',
'go'            => 'Consulter',
'history'	=> 'Historique de la page',
'history_short' => 'Historique',
'updatedmarker' => 'modifié depuis ma dernière visite',
'printableversion' => 'Version imprimable',
'permalink'     => 'Lien permanent',
'print' => 'Imprimer',
'edit' => 'Modifier',
'editthispage'  => 'Modifier cette page',
'delete'	=> 'Supprimer',
'deletethispage' => 'Supprimer cette page',
'undelete_short' => 'Restaurer $1 modifications',
'protect' => 'Protéger',
'protectthispage' => 'Protéger cette page',
'unprotect' => 'Déprotéger',
'unprotectthispage' => 'Déprotéger cette page',
'newpage'       => 'Nouvelle page',
'talkpage'      => 'Page de discussion',
'specialpage'	=> 'Page spéciale',
'personaltools'	=> 'Outils personnels',
'postcomment'	=> 'Ajouter un commentaire',
'articlepage'	=> 'Voir l\'article',
'views' => 'Affichages',
'toolbox'	=> 'Boîte à outils',
'userpage'      => 'Page utilisateur',
'projectpage' => 'Page méta',
'imagepage'     => 'Page image',
'viewtalkpage'  => 'Page de discussion',
'otherlanguages' => 'Autres langues',
'redirectedfrom' => '(Redirigé depuis $1)',
'redirectpagesub' => 'Page de redirection',
'lastmodified'  => 'Dernière modification de cette page le $2, $1.',
'viewcount'     => 'Cette page a été consultée $1 fois.',
'copyright'	=> 'Contenu disponible sous $1.',
'protectedpage' => 'Page protégée',

'badaccess' => 'Erreur de permission',

'versionrequired' => 'Version $1 de MediaWiki nécessaire',
'versionrequiredtext' => 'La version $1 de MediaWiki est nécessaire pour utiliser cette page. Consultez [[Special:Version]]',

'nbytes'        => '$1 octets',
'ncategories'	=> '$1 catégories',
'nrevisions'	=> '$1 révisions',
'retrievedfrom' => 'Récupérée de « $1 »',
'youhavenewmessages' => 'Vous avez $1 ($2).',
'newmessageslink' => 'des nouveaux messages',
'newmessagesdifflink' => 'diff vers l\'avant-dernière révision',
'editsection'	=> 'modifier',
'editold'	=> 'modifier',
'editsectionhint' => 'Éditer la section : $1',
'toc'		=> 'Sommaire',
'showtoc'	=> 'afficher',
'hidetoc'	=> 'masquer',
'thisisdeleted' => 'Désirez-vous afficher ou restaurer $1 ?',
'viewdeleted' => 'Voir $1 ?',
'restorelink'	=> '$1 modification(s) effacée(s)',
'feedlinks'	=> 'Flux',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-user' => 'Page utilisateur',
'nstab-media' => 'Média',
'nstab-special' => 'Spécial',
'nstab-project' => 'À propos',
'nstab-image' => 'Image',
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
'databaseerror' => 'Erreur base de données',
'dberrortext'	=> 'Erreur de syntaxe dans la base de données. La dernière requête traitée par la base de données était :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction « <tt>$2</tt> ».
MySQL a renvoyé l\'erreur « <tt>$3: $4</tt> ».',
'dberrortextcl' => 'Une requête à la base de donnée comporte une erreur de syntaxe. La dernière requête envoyée était:
« $1 »
effectuée par la fonction « $2 »
MySQL a retourné l\'erreur « $3 : $4 ».',
'noconnect'	=> 'Désolé! Suite à des problèmes techniques, il est impossible de se connecter à la base de données pour le moment. <br /> $1',
'nodb'		=> 'Sélection impossible de la base de données $1',
'cachederror'	=> 'Ceci est une copie de la page demandée et peut ne pas être à jour',
'laggedslavemode' => 'Attention : la page n\'intègre peut être pas les dernières éditions',
'readonly'	=> 'Mises à jour bloquées sur la base de données',
'enterlockreason' => 'Indiquez la raison du blocage, ainsi qu\'une estimation de la durée de blocage',
'readonlytext'	=> 'Les ajouts et mises à jour sur la base de donnée {{SITENAME}} sont actuellement bloqués, probablement pour permettre la maintenance de la base, après quoi, tout rentrera dans l\'ordre. Voici la raison pour laquelle l\'administrateur a bloqué la base :
<p>$1',
'missingarticle' => 'La base de données n\'a pas pu trouver le texte d\'une page existante, dont le titre est « $1 ».
Ce n\'est pas une erreur de la base de données, mais plus probablement un bogue du wiki.
Veuillez rapporter cette erreur à un administrateur, en lui indiquant l\'adresse de la page fautive.',
'readonly_lag' => 'La base de donnée a été automatiquement bloquée pendant que les serveurs secondaires rattrapent leur retard avec le serveur principal',
'internalerror' => 'Erreur interne',
'filecopyerror' => 'Impossible de copier « $1 » vers « $2 ».',
'filedeleteerror' => 'Impossible de supprimer « $1 ».',
'filenotfound'	=> 'Le fichier « $1 » introuvable.',
'unexpected' => 'Valeur inattendue : « $1 » = « $2 ».',
'formerror'	=> 'Erreur: Impossible de soumettre le formulaire',
'badarticleerror' => 'Cette action ne peut pas être effectuée sur cette page.',
'cannotdelete'	=> 'Impossible de supprimer la page ou l\'image indiquée.',
'badtitle'	=> 'Mauvais titre',
'badtitletext'	=> 'Le titre de la page demandée est invalide, vide ou le lien interlangue est invalide',
'perfdisabled' => 'Désolé! Cette fonctionnalité est temporairement désactivée
car elle ralentit la base de données à un point tel que plus personne
ne peut utiliser le wiki.',
'perfdisabledsub' => 'Ceci est une copie de sauvegarde de $1:',
'perfcached' => 'Ceci est une version en cache et n\'est peut-être pas à jour.',
'perfcachedts' => 'Ceci est une version en cache, et fut mis à jour la dernière fois le $1.',
'wrong_wfQuery_params' => 'Paramètres incorrects sur la commande wfQuery()<br />
Fonction : $1<br />
Requête : $2',
'viewsource'	=> 'Voir le texte source',
'viewsourcefor' => 'pour $1',
'protectedtext'	=> 'Cette page a été protégée pour empêcher sa modification. Vous pouvez consulter [[{{ns:4}}:Page protégée]] pour voir les différentes raisons possibles. Vous pouvez toutefois voir et copier son code source.',
'protectedinterface' => 'Cette page fournit du texte d\'interface pour le logiciel, et est protégée pour éviter les abus.',
'editinginterface' => '\'\'\'Attention :\'\'\' Vous éditez une page utilisée pour fournir le texte de l\'interface du logiciel. Les modifications affecteront l\'apparence de l\'interface pour les autres utilisateurs.',
'sqlhidden' => '(requête SQL cachée)',

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
'remembermypassword' => 'Se souvenir de mon mot de passe (cookie)',
'yourdomainname'       => 'Votre domaine',
'externaldberror'	=> 'Une erreur externe de la base de donnée d\'authentification s\'est produite et vous n\'êtes pas autorisé à mettre à jour votre compte externe.',
'loginproblem'       => '<b>Problème d\'identification.</b><br />Essayez à nouveau !',
'alreadyloggedin'    => '<strong>\'\'\'Utilisateur $1, vous êtes déjà identifié!\'\'\'</strong><br />',

'login'         => 'Identification',
'loginprompt'	=> 'Vous devez activer les cookies pour vous connecter à {{SITENAME}}.',
'userlogin'     => 'Créer un compte ou se connecter',
'logout'        => 'Déconnexion',
'userlogout'    => 'Déconnexion',
'notloggedin'	=> 'Non connecté',
'nologin'	=> 'Vous n\'avez pas de compte ? $1.',
'nologinlink'	=> 'Créer un compte',
'createaccount' => 'Créer un compte',
'gotaccount'	=> 'Vous avez déjà un compte ? $1.',
'gotaccountlink'	=> 'Identifiez-vous',
'createaccountmail'	=> 'par courriel',
'badretype'     => 'Les deux mots de passe que vous avez saisis ne sont pas identiques.',
'userexists'    => 'Le nom d\'utilisateur que vous avez saisi est déjà utilisé. Veuillez en choisir un autre.',
'youremail'     => 'Mon adresse électronique',
'username'	=> 'Nom d\'utilisateur :',
'uid'		=> 'ID utilisateur :',
'yourrealname'	=> 'Votre nom réel*',
'yourlanguage' 	=> 'Langue de l\'interface',
'yourvariant'	=> 'Variante',
'yournick'      => 'Pseudonyme :',
'badsig'	=> 'Signature brute incorrecte, vérifiez vos balises HTML.',
'email'		=> 'Courriel',
'prefs-help-email-enotif' => 'Cette adresse est aussi utilisée pour vous envoyer des notifications par courriel si vous avez activé les options correspondantes.',
'prefs-help-realname' => '* Nom réel (facultatif): si vous le spécifiez, il sera utilisé pour l\'attribution de vos contributions.',
'loginerror'    => 'Problème d\'identification',
'prefs-help-email' => '* Adresse de courriel (facultatif): permet de vous contacter depuis le site sans dévoiler votre identité.',
'nocookiesnew'	=> 'Le compte utilisateur a été créé, mais vous n\'êtes pas connecté. {{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactives. Merci de les activer et de vous reconnecter.',
'nocookieslogin' => '{{SITENAME}} utilise des cookies pour la connexion mais vous avez les cookies désactivés. Merci de les activer et de vous reconnecter.',
'noname'        => 'Vous n\'avez pas saisi de nom d\'utilisateur.',
'loginsuccesstitle' => 'Identification réussie.',
'loginsuccess'  => 'Vous êtes actuellement connecté sur {{SITENAME}} en tant que « $1 ».',
'nosuchuser'    => 'L\'utilisateur « $1 » n\'existe pas.
Vérifiez que vous avez bien orthographié le nom, ou utilisez le formulaire ci-dessous pour créer un nouveau compte utilisateur.',
'nosuchusershort' => 'Il n\'y a pas de contributeur avec le nom « $1 ». Vérifiez l\'orthographe.',
'wrongpassword' => 'Le mot de passe est incorrect. Essayez à nouveau.',
'wrongpasswordempty'=> 'Vous n\'avez pas entré de mot de passe. Essayez à nouveau.',
'mailmypassword' => 'Envoyez-moi un nouveau mot de passe',
'passwordremindertitle' => 'Votre nouveau mot de passe sur {{SITENAME}}',
'passwordremindertext' => 'Quelqu\'un (probablement vous) ayant l\'adresse IP $1 a demandé à ce qu\'un nouveau mot de passe vous soit envoyé pour {{SITENAME}} ($4)
Le mot de passe de l\'utilisateur « $2 » est à présent « $3 ».
Nous vous conseillons de vous connecter et de modifier ce mot de passe dès que possible. Si vous n\'êtes pas l\'auteur de cette demande, ou si vous vous souvenez à présent de votre ancien mot de passe et que vous ne souhaitez plus en changer, vous pouvez ignorer ce message et continuer à utiliser votre ancien mot de passe.',
'noemail'  => 'Aucune adresse électronique n\'a été enregistrée pour l\'utilisateur « $1 ».',
'passwordsent' => 'Un nouveau mot de passe a été envoyé à l\'adresse électronique de l\'utilisateur « $1 ».',
'mailerror'	=> 'Erreur en envoyant le courriel : $1',
'acct_creation_throttle_hit' => 'Désolé, vous avez déjà créé $1 comptes. Vous ne pouvez pas en créer de nouveaux.',
'emailauthenticated'        => 'Votre adresse de courriel a été authentifiée le $1..',
'emailnotauthenticated'     => 'Votre adresse de courriel n\'est <strong>pas encore authentifiée</strong>. Aucun courriel ne sera envoyé pour aucune des fonction suivantes.',
'noemailprefs'              => '<strong>Veuillez fournir une adresse de courriel pour le bon fonctionnement de ces fonctionnalités.</strong>',
'emailconfirmlink' => 'Confirmez votre adresse de courriel',
'invalidemailaddress'	=> 'Cette adresse de courriel ne peut pas être acceptée parce qu\'elle semble avoir un format invalide. Veuillez entrer une adresse valide ou vider ce champ.',
'accountcreated' => 'Compte créé.',
'accountcreatedtext' => 'Le compte utilisateur pour $1 a été créé.',

# Edit page toolbar
'bold_sample'   => 'Texte gras',
'bold_tip'      => 'Texte gras',
'italic_sample' => 'Texte italique',
'italic_tip'    => 'Texte italique',
'link_sample'   => 'Lien titre',
'link_tip'      => 'Lien interne',
'extlink_sample'  => 'http://www.example.com lien titre',
'extlink_tip'     => 'Lien externe (n\'oubliez pas le préfixe http://)',
'headline_sample' => 'Texte de sous-titre',
'headline_tip'  => 'Sous-titre niveau 2',
'math_sample'   => 'Entrez votre formule ici',
'math_tip'      => 'Formule mathématique (LaTeX)',
'nowiki_sample' => 'Entrez le texte non formaté ici',
'nowiki_tip'    => 'Ignorer la syntaxe wiki',
'image_sample'  => 'Exemple.jpg',
'image_tip'     => 'Image insérée',
'media_sample'  => 'Exemple.ogg',
'media_tip'     => 'Lien fichier média',
'sig_tip'       => 'Votre signature avec la date',
'hr_tip'        => 'Lien horizontal (ne pas en abuser)',

# Edit pages
#
'summary'      => 'Résumé&nbsp;',
'subject'	   => 'Sujet/titre',
'minoredit'    => 'Modification mineure.',
'watchthis'    => 'Suivre cet article',
'savearticle'  => 'Sauvegarder',
'preview'      => 'Prévisualiser',
'showpreview'  => 'Prévisualisation',
'showlivepreview'=> 'Prévisualisation rapide',
'showdiff'	=> 'Changements en cours',
'anoneditwarning' => '\'\'\'Attention :\'\'\' Vous n\'êtes pas identifié. Votre adresse IP sera enregistrée dans l\'historique de cette page.',
'missingsummary' => '\'\'\'Rappel :\'\'\' Vous n\'avez pas fourni de commentaire de modification. Si vous cliquez une nouvelle fois sur le bouton « Sauvegarder », votre modification sera enregistrée sans commentaire.',
'missingcommenttext' => 'Merci d\'insérer un commentaire ci-dessous.',
'blockedtitle' => 'Utilisateur bloqué',
'blockedtext'  => 'Votre compte utilisateur ou votre adresse IP ont été bloqués par $1 pour la raison suivante : $2. Vous pouvez contacter $1 ou un des autres [[{{ns:4}}:Administrateur|administrateurs]] pour en discuter. Veuillez noter que vous ne pouvez utiliser la fonction de courriel si vous n\'avez pas enregistré une adresse de courriel valide dans vos [[Special:Preferences|préférences]]. Votre adresse IP est $3. Merci d\'inclure cette adresse dans toutes vos requêtes.',
'whitelistedittitle' => 'Login requis pour rédiger',
'whitelistedittext' => 'Vous devez être $1 pour avoir la permission de rédiger',
'whitelistreadtitle' => 'Compte requis pour lire',
'whitelistreadtext' => 'Vous devez être [[Special:Userlogin|connecté]] pour avoir la permission de lire les articles',
'whitelistacctitle' => 'Vous n\'êtes pas autorisé à créer un compte',
'whitelistacctext' => 'Pour avoir la permission de créer un compte sur ce Wiki vous devez être [[Special:Userlogin|connecté]] et avoir les permissions appropriées', 
'confirmedittitle' => 'Validation par courriel requise pour modifier les pages',
'confirmedittext' => 'Vous devez valider votre adresse de courriel avant de modifier une page. Veuillez entrer et valider votre adresse de courriel grâce à [[Special:Preferences|user preferences]].',
'loginreqtitle'	=> 'Identification nécessaire',
'loginreqlink' => 'connecter',
'loginreqpagetext'	=> 'Vous devez vous $1 pour voir les autres pages.',
'accmailtitle' => 'Mot de passe envoyé.',
'accmailtext' => 'Le mot de passe de « $1 » a été envoyé à l\'adresse $2.',
'newarticle'   => '(Nouveau)',
'newarticletext' => 'Vous avez suivi un lien vers une page qui n\'existe pas encore. Pour créer cette page, entrez votre texte dans la boîte ci-dessous (vous pouvez consulter [[Project:Aide|la page d\'aide]] pour plus d\'information). Si vous êtes arrivé ici par erreur, cliquez sur le bouton \'\'\'retour\'\'\' de votre navigateur.',
'anontalkpagetext' => '---- \'\'Vous êtes sur la page de discussion d\'un utilisateur anonyme qui n\'a pas encore créé un compte ou qui ne l\'utilise pas. Pour cette raison, nous devons utiliser l\'adresse IP numérique pour l\'identifier. Une adresse de ce type peut être partagée entre plusieurs utilisateurs. Si vous êtes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont été adressés, vous pouvez [[Special:Userlogin|créer un compte ou vous connecter]] afin d\'éviter toute confusion future avec d\'autres contributeurs anonymes.\'\'',
'noarticletext' => 'Il n\'y a pour l\'instant aucun texte sur cette page, vous pouvez [[{{ns:special}}:Search/{{PAGENAME}}|faire une recherche pour le titre de cette page]] ou [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} modifier cette page]',
'clearyourcache'    => '\'\'\'Note :\'\'\' Après avoir sauvegardé, vous devez forcer le rechargement de la page pour voir les changements : \'\'\'Mozilla / Konqueror / Firefox\'\'\' : \'\'ctrl-shift-r\'\', \'\'\'IE\'\'\' : \'\'ctrl-f5\'\', \'\'\'Safari\'\'\' : \'\'cmd-shift-r\'\'; \'\'\'Konqueror\'\'\' : \'\'f5\'\'.',
'usercssjsyoucanpreview' => "'''Astuce :''' utilisez le bouton '''Prévisualisation''' pour tester votre nouvelle feuille css/js avant de l'enregistrer.",
'usercsspreview' => "'''Rappelez-vous que vous êtes en train de prévisualiser votre propre feuille css et qu'elle n'a pas encore été enregistrée !'''",
'userjspreview' => "'''Rappelez-vous que vous êtes en train de visualiser ou de tester votre code javascript et qu'il n'a pas encore été enregistré !'''",
'updated'      => '(Mis à jour)',
'note'         => '<strong>Note :</strong>',
'previewnote'  => 'Attention, ce texte n\'est qu\'une prévisualisation et n\'a pas encore été sauvegardé !',
'session_fail_preview' => '<strong>Désolé ! Nous ne pouvons enregistrer votre modification à cause d\'une perte d\'informations concernant votre session. Veuillez réessayer. Si cela échoue à nouveau, veuillez vous déconnecter, puis vous reconnecter.</strong>',
'previewconflict' => 'La prévisualisation montre le texte de cette page tel qu\'il apparaîtra une fois sauvegardé.',
'importing' => 'Import de $1',
'editing'         => 'modification de $1',
'editingsection'  => 'modification de $1 (section)',
'editingcomment'  => 'modification de $1 (commentaire)',
'editconflict' => 'Conflit de modification : $1',
'explainconflict' => '<b>Cette page a été sauvegardée après que vous avez commencé à la modifier.
La zone d\'édition supérieure contient le texte tel qu\'il est enregistré actuellement dans la base de données. Vos modifications apparaissent dans la zone d\'édition inférieure. Vous allez devoir apporter vos modifications au texte existant. Seul le texte de la zone supérieure sera sauvegardé.<br />',
'yourtext'     => 'Votre texte',
'storedversion' => 'Version enregistrée',
'nonunicodebrowser' => "<strong>Attention : Votre navigateur ne supporte pas l'unicode. Une solution temporaire a été trouvée pour vous permettre d'éditer en tout sûreté un article : les caractères non-ASCII apparaîtront dans votre boîte de modification en tant que code hexadécimal.</strong>",
'editingold'   => '<strong>Attention : vous êtes en train de modifier une version obsolète de cette page. Si vous sauvegardez, toutes les modifications effectuées depuis cette version seront perdues.</strong>',
'yourdiff'  => 'Différences',
'copyrightwarning' => 'Toutes les contributions à {{SITENAME}} sont considérées comme publiées sous les termes de la $2 (voir $1 pour plus de détails), . Si vous ne désirez pas que vos écrits soient édités et distribués à volonté, merci de ne pas les soumettre ici. Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l\'avez copié d\'une source provenant du domaine public, ou d\'une ressource libre.<strong>N\'UTILISEZ PAS DE TRAVAUX SOUS COPYRIGHT SANS AUTORISATION EXPRESSE !</strong>',
'copyrightwarning2' => 'Toutes les contributions à {{SITENAME}} peuvent être éditées, modifiées, ou supprimées par d\'autres utilisateurs. Si vous ne désirez pas que vos écrits soient édités, merci de ne pas les soumettre ici. Vous nous promettez aussi que vous avez écrit ceci vous-même, ou que vous l\'avez copié d\'une source provenant du domaine public, ou d\'une ressource libre. (voir $1 pour plus de détails).
<strong>N\'UTILISEZ PAS DE TRAVAUX SOUS COPYRIGHT SANS AUTORISATION EXPRESSE !</strong>',
'longpagewarning' => "'''AVERTISSEMENT : cette page a une longueur de $1 ko;
quelques navigateurs gèrent mal les pages approchant ou dépassant 32 ko lors de leur rédaction.
Peut-être devriez-vous diviser la page en sections plus petites.'''",
'longpageerror' => "<strong>ERREUR : Le texte que vous avez soumis fait $1 octets, ce qui dépasse la limite fixée à $2 octets. La sauvegarde ne peut avoir lieu.</strong>",
'readonlywarning' => '\'\'\'AVERTISSEMENT : cette page est protégée pour maintenance,
vous ne pourrez donc pas sauvegarder vos modifications maintenant. Vous pouvez copier le texte dans un fichier et le sauver pour plus tard.\'\'\'',
'protectedpagewarning' => '\'\'\'AVERTISSEMENT : cette page est protégée.
Seuls les utilisateurs ayant le statut d\'administrateur peuvent la modifier. Soyez certain que
vous suivez les [[Project:Page_protégée|directives concernant les pages protégées]].\'\'\'',
'semiprotectedpagewarning' => "'''Note:''' Cette page a été protégée de telle façon que seuls les contributeurs enregistrés puissent la modifier.",
'templatesused'	=> 'Modèles utilisés sur cette page:',
'edittools' => '<!-- Tout texte entré ici sera affiché sous les boîtes de modification ou d\'import. -->',
'nocreatetitle' => 'Création de page limitée',
'nocreatetext' => 'Ce site a restreint la possibilité de créer de nouvelles pages. Vous pouvez retourner en arrière et éditer une page existante ou [[Special:Userlogin|vous connecter ou créer un compte]].',
'cantcreateaccounttitle' => 'Vous ne pouvez pas créer un compte.',
'cantcreateaccounttext' => 'La création de compte depuis cette adresse IP (<b>$1</b>) a été bloquée. Ceci est probablement du à du vandalisme répété depuis votre école ou votre fournisseur d\'accès à internet.',

# History pages #

'revhistory'   => 'Versions précédentes',
'nohistory'    => 'Il n\'existe pas d\'historique pour cette page.',
'revnotfound'  => 'Version introuvable',
'revnotfoundtext' => 'La version précédente de cette page n\'a pas pu être retrouvée. Vérifiez l\'URL que vous avez utilisée pour accéder à cette page.',
'loadhist'     => 'Chargement de l\'historique de la page',
'currentrev'   => 'Version actuelle',
'revisionasof' => 'Version du $1',
'previousrevision' => '← Version précédente',
'nextrevision' => 'Version suivante →',
'currentrevisionlink'   => 'voir la version courante',
'cur'    => 'actu',
'next'   => 'suiv',
'last'   => 'dern',
'histlegend' => 'Légende : (actu) = différence avec la version actuelle ,
(dern) = différence avec la version précédente, M = modification mineure',
'deletedrev' => '[supprimé]',
'histfirst' => 'Premières contributions',
'histlast' => 'Dernières contributions',
'rev-deleted-comment' => '(commentaire supprimé)',
'rev-deleted-user' => '(nom d\'utilisateur supprimé)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Cette version de la page a été retirée des archives publiques. Il peut y avoir des détails dans le [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} journal des effacements]. </div>',
'rev-deleted-text-view' => '<div class="mw-warning plainlinks"> Cette version de la page a été retirée des archives publiques. En tant qu\'administrateur de ce site, vous pouvez la visualiser ; il peut y avoir des détails dans le [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} journal des suppressions]. </div>',

#'rev-delundel' => 'del/undel',
'rev-delundel' => 'montrer/cacher',

# Revision deletion
#

'revisiondelete' => 'Supprimer/Restaurer des versions',
'revdelete-selected' => 'Version sélectionnée de [[:$1]]:',
'revdelete-text' => 'Des versions supprimées apparaîtront encore dans l\'historique de l\'article, mais leur contenu textuel sera innaccessible au public.

D\'autres administrateurs sur ce wiki pourront toujours accèder au contenu cacher et le restaurer à nouveau à travers cette même interface, à moins qu\'une restriction supplémentaire ne soit mise en place par les opérateurs du site.',
'revdelete-legend' => 'Mettre en place des restrictions de version :',
'revdelete-hide-text' => 'Cacher le texte de la version',
'revdelete-hide-comment' => 'Cacher le commentaire de modification',
'revdelete-hide-user' => 'Cacher le nom du compte utilisateur/de l\'IP.',
'revdelete-hide-restricted' => 'Appliquer ces restrictions aux administrateurs ainsi qu\'aux autres utilisateurs',
'revdelete-log' => 'Commentaire pour le journal :',
'revdelete-submit' => 'Appliquer à la version sélectionnée',
'revdelete-logentry' => 'la visibilité des versions a été modifiée pour [[$1]]',

#  Diffs
#
'difference' => '(Différences entre les versions)',
'loadingrev' => 'chargement de l\'ancienne version pour comparaison',
'lineno'  => 'Ligne $1:',
'editcurrent' => 'Modifier la version actuelle de cette page',
'selectnewerversionfordiff' => 'Choisir une version plus récente pour comparaison',
'selectolderversionfordiff' => 'Choisir une version plus ancienne pour comparaison',
'compareselectedversions' => 'Comparer les versions sélectionnées',

# Search results
#
'searchresults' => 'Résultat de la recherche',
'searchresulttext' => 'Pour plus d\'informations sur la recherche dans {{SITENAME}}, voir [[Aide:Recherche|Chercher dans {{SITENAME}}]].',
'searchsubtitle' => 'Pour la requête « [[:$1]] »',
'searchsubtitleinvalid' => 'Pour la requête « $1 »',
'badquery'  => 'Requête mal formulée',
'badquerytext' => 'Nous n\'avons pas pu traiter votre requête. Vous avez probablement recherché un mot d\'une longueur inférieure à trois lettres, ce qui n\'est pas encore possible. Vous avez aussi pu faire une erreur de syntaxe, telle que « poisson et et écailles ». Veuillez essayer une autre requête.',
'matchtotals' => 'La requête « $1 » correspond à $2 titre(s) d\'article et au texte de $3 article(s).',
'noexactmatch' => 'Aucune page avec ce titre n\'existe. Voulez-vous [[:$1|créer cet article]] ?',
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
'searchdisabled' => 'La recherche sur {{SITENAME]] a été désactivée. En attendant la réactivation, vous pouvez effectuer une recherche par le biais de Google. Attention, leur indexation du contenu {{SITENAME]] peut ne pas être à jour.',

# Preferences page
#
'preferences'       => 'Préférences',
'prefsnologin'      => 'Non connecté',
'prefsnologintext'  => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier vos préférences d\'utilisateur.',
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
'math_notexvc'		=> 'L\'exécutable « texvc » est introuvable. Lisez math/README pour le configurer.',
'prefs-personal'    => 'Informations personnelles',
'prefs-rc'          => 'Modifications récentes',
'prefs-misc'        => 'Préférences diverses',
'saveprefs'         => 'Enregistrer les préférences',
'resetprefs'        => 'Rétablir les préférences',
'oldpassword'       => 'Ancien mot de passe',
'newpassword'       => 'Nouveau mot de passe',
'retypenew'         => 'Confirmer le nouveau mot de passe',
'textboxsize'       => 'Fenêtre d\'édition',
'rows'              => 'Rangées',
'columns'           => 'Colonnes',
'searchresultshead' => 'Affichage des résultats de recherche',
'resultsperpage'    => 'Nombre de réponses par page',
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
'allowemail'		=> 'Autoriser l\'envoi de courriel d\'autres utilisateurs',
'defaultns'         => 'Par défaut, rechercher dans ces espaces :',
'default'		=> 'défaut',
'files'			=> 'Fichiers',

# User rights

'userrights-lookup-user' => 'Gérer les goupes d\'utilisateur',
'userrights-user-editname' => 'Entrer un nom d\'utilisateur :',
'editusergroup' => 'Modification des groupes utilisateurs',

'userrights-editusergroup' => 'Éditer les groupes de l\'utilisateur',
'saveusergroups' => 'Sauvegarder les groupes utilisateur',
'userrights-groupsmember' => 'Membre de :',
'userrights-groupsavailable' => 'Groupes disponibles :',
'userrights-groupshelp' => 'Choisissez les groupes desquels vous voulez retirer ou rajouter l\'utilsateur. Les groupes non sélectionnés ne seront pas modifiés. Vous pouvez désélectionner un groupe avec CTRL + clic gauche.',

# Groups
'group'                   => 'Groupe :',
'group-sysop'             => 'Administrateurs',
'group-bureaucrat'        => 'Bureaucrates',
'group-all'               => '(tous)',

'group-sysop-member'      => 'Administrateur',
'group-bureaucrat-member' => 'Bureaucrate',

'grouppage-sysop' => 'Projet:Administrateurs',
'grouppage-bureaucrat' => 'Projet:Bureaucrates',

# Recent changes
#
'changes'	=> 'modifications',
'recentchanges' => 'Modifications récentes',
'recentchangestext' => "Suivez sur cette page les dernières modifications de {{SITENAME}}.",
'rcnote'  => 'Voici les <strong>$1</strong> dernières modifications effectuées au cours des <strong>$2</strong> derniers jours, en date du $3',
'rcnotefrom'	=> 'Voici les modifications effectuées depuis le <strong>$2</strong> (<b>$1</b> au maximum).',
'rclistfrom'	=> 'Afficher les nouvelles modifications depuis le $1.',
'rcshowhideminor' => '$1 modifications mineures',
'rcshowhidebots' => '$1 robots',
'rcshowhideliu' => '$1 utilisateurs enregistrés',
'rcshowhideanons' => '$1 utilisateurs anonymes',
'rcshowhidemine' => '$1 mes contributions',
'rclinks'	=> 'Afficher les $1 dernières modifications effectuées au cours des $2 derniers jours; $3 modifications mineures.',
'hide'            => 'Masquer',
'show'            => 'Afficher',
'number_of_watching_users_pageview' 	=> '[$1 utilisateur/s suivant]',
'rc_categories'	=> 'Limite des catégories (separation avec « | »)',
'rc_categories_any'	=> 'Toutes',

# Upload
#
'upload'       => 'Copier sur le serveur',
'uploadbtn'    => 'Copier un fichier',
'reupload'     => 'Copier à nouveau',
'reuploaddesc' => 'Retour au formulaire.',
'uploadnologin' => 'Non connecté(e)',
'uploadnologintext' => 'Vous devez être [[Special:Userlogin|connecté]] pour copier des fichiers sur le serveur.',
'upload_directory_read_only' => 'Le serveur Web ne peut écrire dans le dossier cible ($1).',
'uploaderror'  => 'Erreur',
'uploadtext'   => 'Utilisez ce formulaire pour copier des fichiers, pour voir ou rechercher des images précédemment copiées consultez la [[Special:Imagelist|liste de fichiers copiés]], les copies et suppressions sont aussi enregistrées dans le [[Special:Log/upload|journal des copies]].

Pour inclure une image dans une page, utilisez un lien de la forme
\'\'\'<nowiki>[[{{ns:6}}:fichier.jpg]]</nowiki>\'\'\',
\'\'\'<nowiki>[[{{ns:6}}:fichier.png|texte alternatif]]</nowiki>\'\'\' or
\'\'\'<nowiki>[[{{ns:-2}}:fichier.ogg]]</nowiki>\'\'\' pour lier directement vers le fichier.',
'uploadlog'  => 'Journal d\'upload',
'uploadlogpage' => 'Journal d\'upload',
'uploadlogpagetext' => 'Voici la liste des derniers fichiers copiés sur le serveur.',
'filename'	=> 'Nom du fichier',
'filedesc'	=> 'Description',
'fileuploadsummary' => 'Description :',
'filestatus'	=> 'Statut du copyright',
'copyrightpage' => '{{ns:4}}:Copyright',
'copyrightpagename' => 'licence {{SITENAME}}',
'uploadedfiles' => 'Fichiers copiés',
'ignorewarning'        => 'Ignorer l\'avertissement et sauvegarder le fichier.',
'ignorewarnings'	=> 'Ignorer tous les avertissements',
'minlength'	=> 'Les noms des images doivent comporter au moins trois lettres.',
'illegalfilename'	=> 'Le nom de fichier « $1 » contient des caractères interdits dans les titres de pages. Merci de le renommer et de le copier à nouveau.',
'badfilename' => 'L\'image a été renommée « $1 ».',
'badfiletype' => '« .$1 » n\'est pas un format accepté pour les fichiers images.',
'largefile'  => 'La taille maximale conseillée pour les images est de $1 ko ($2).',
'largefileserver' => 'Ce fichier possède une taille supérieure à celle autorisée par la configuration du serveur.',
'emptyfile'		=> 'Le fichier que vous avez copié semble être vide. Ceci peut-être du à une erreur dans le nom du fichier. Veuillez vérifiez que vous désirez vraiment copier ce fichier.',
'fileexists' => 'Un fichier avec ce nom existe déjà. Merci de vérifier $1. Êtes-vous certain de vouloir modifier ce fichier ?',
'fileexists-forbidden' => 'Un fichier avec ce nom existe déjà ; merci de retourner en arrière et de copier le fichier sous un nouveau nom. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichier portant ce nom existe déjà dans le répertoire de fichiers partagés ; merci de retourner en arrière et de copier le fichier sous un nouveau nom. [[Image:$1|thumb|center|$1]]',
'successfulupload' => 'Copie réussie',
'fileuploaded' => 'Le fichier « $1 » a été copié sur le serveur.
Suivez ce lien ($2) pour accéder à la page de description, et donner des informations sur le fichier, par exemple son origine, sa date de création, son auteur, ou tout autre renseignement en votre possession.',
'uploadwarning' => 'Attention !',
'savefile'  => 'Sauvegarder le fichier',
'uploadedimage' => '« [[$1]] » copié sur le serveur',
'uploaddisabled' => 'Désolé, l\'envoi de fichier est désactivé.',
'uploaddisabledtext' => 'La copie de fichiers est désactivée sur ce wiki.',
'uploadscripted' => 'Ce fichier contient du code HTML ou un script qui pourrait être interprété de façon incorrecte par un navigateur Internet.',
'uploadcorrupt' => 'Ce fichier est corrompu, a une taille nulle ou possède une extension invalide.
Veuillez vérifer le fichier.',
'uploadvirus' => 'Ce fichier contient un virus ! Pour plus de détails, consultez : $1',
'sourcefilename' => 'Nom du fichier à envoyer',
'destfilename' => 'Nom sous lequel le fichier sera enregistré',
'filewasdeleted' => 'Un fichier avec ce nom a déjà été copié, puis supprimé. Vous devriez vérifier le $1 avant de procéder à une nouvelle copie.',

'license' => 'Licence',
'nolicense' => 'Aucune licence sélectionnée',


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
'linkstoimage' => 'Les pages ci-dessous contiennent cette image :',
'nolinkstoimage' => 'Aucune page ne contient cette image.',
'sharedupload' => 'Ce fichier est partagé et peut-être utilisé par d\'autres projets.',
'shareduploadwiki' => 'Veuillez consulter le $1 pour plus d\'informations.',
'shareduploadwiki-linktext' => 'Page de description du fichier',
'noimage'       => 'Aucun fichier possèdant ce nom n\'existe, vous pouvez $1.',
'noimage-linktext'       => 'le copier',
'uploadnewversion-linktext' => 'Copier une nouvelle version de ce fichier',

# Mime search
#
'mimesearch' => 'Recherche par type MIME',
'mimetype' => 'Type MIME :',
'download' => 'Téléchargement',

# Unwatchedpages
#
'unwatchedpages' => 'Pages non suivies',

# List redirects
'listredirects' => 'Liste des redirections',

# Unused templates
'unusedtemplates' => 'Modèles non utilisés',
'unusedtemplatestext' => 'Cette page liste toutes les pages de l\'espace de noms « Modèle » qui ne sont inclus dans aucune autre page. N\'oubliez pas de vérifier s\'il n\'y a pas d\'autre liens vers les modèles avant de les supprimer.',
'unusedtemplateswlh' => 'autres liens',

# Random redirect
'randomredirect' => 'Redirect aléatoire',

# Statistics

'statistics' => 'Statistiques',
'sitestats'  => 'Statistiques de {{SITENAME}}',
'userstats'  => 'Statistiques utilisateur',
'sitestatstext' => "La base de données contient actuellement <b>$1</b> pages.

Ce chiffre inclut les pages « discussion », les pages relatives à {{SITENAME}}, les pages minimales (\"ébauches\"),  les pages de redirection, ainsi que d'autres pages qui ne peuvent sans doute pas être considérées comme des articles.
Si l'on exclut ces pages, il reste <b>$2</b> pages qui sont probablement de véritables articles.<p>

'''$8''' fichiers ont été téléchargés.

<b>$3</b> pages ont été consultées et <b>$4</b> pages modifiées.

Cela représente une moyenne de <b>$5</b> modifications par page et de <b>$6</b> consultations pour une modification.</p>

<p>Il y a '''$7''' articles dans [http://meta.wikimedia.org/wiki/Help:Job_queue la file de tâche].</p>",
'userstatstext' => "Il y a <b>$1</b> utilisateurs enregistrés. Parmi ceux-ci, '''$2''' (ou '''$4%''') ont le statut d\'administrateur (voir $3).",

'disambiguations'	=> 'Pages d\'homonymie',
'disambiguationspage'	=> 'Modèle:Homonymie',
'disambiguationstext'	=> 'Les pages suivantes lient vers une <i>page d\'homonymie</i>. Elles devraient plutôt lier vers une page pertinente.<br /> Une page est traitée comme une page d\'homonymie si elle est liée depuis $1.<br /> Les liens depuis d\'autres espaces de noms <i>ne sont pas</i> listés ici.',

'doubleredirects'	=> 'Doubles redirections.',
'doubleredirectstext'	=> 'Chaque case contient des liens vers la première et la seconde redirection, ainsi que la première ligne de texte de la seconde page, ce qui fournit habituellement la « vraie » page cible, vers laquelle la première redirection devrait rediriger.',

'brokenredirects'	=> 'Redirections cassées',
'brokenredirectstext'	=> 'Ces redirections mènent vers des pages n\'existant pas :',

# Miscellaneous special pages
#
'lonelypages'   => 'Pages orphelines',
'uncategorizedpages' => 'Pages sans catégorie',
'uncategorizedcategories' => 'Catégories sans catégories',
'unusedcategories' => 'Catégories non utilisées',
'unusedimages'  => 'Images orphelines',
'popularpages'  => 'Pages les plus consultées',
'nviews'        => '$1 consultations',
'wantedcategories' => 'Catégories les plus demandées',
'wantedpages'   => 'Pages les plus demandées',
'mostlinked'	=> 'Plus de liens vers les pages',
'mostlinkedcategories' => 'Plus de liens vers les catégories',
'mostcategories' => 'Articles avec le plus de catégories',
'mostimages'	=> 'Plus de liens vers des images',
'mostrevisions' => 'Articles avec le plus de révisions',
'nlinks'        => '$1 références',
'allpages'      => 'Toutes les pages',
'prefixindex'   => 'Index des préfixes',
'randompage'    => 'Une page au hasard',
'shortpages'    => 'Pages courtes',
'longpages'     => 'Pages longues',
'deadendpages'  => 'Pages en impasse',
'listusers'     => 'Liste des participants',
'specialpages'  => 'Pages spéciales',
'spheading'     => 'Pages spéciales',
'restrictedpheading'	=> 'Pages spécials d\'accès restreint',
'recentchangeslinked' => 'Suivi des liens',
'rclsub'        => '(des pages liées à « $1 »)',
'newpages'      => 'Nouvelles pages',
'ancientpages'	=> 'Articles les moins récemment modifiés',
'move'		=> 'Renommer',
'movethispage'  => 'Renommer la page',
'unusedimagestext' => '<p>N\'oubliez pas que d\'autres sites, peuvent contenir un lien direct vers cette image, et que celle-ci peut être placée dans cette liste alors qu\'elle est en réalité utilisée.</p>',
'unusedcategoriestext' => 'Les pages des catégories suivantes existent bien qu\'aucun article ou catégorie ne les utilise.',

'booksources'   => 'Ouvrages de référence',
'categoriespagetext' => 'Les catégories suivantes existent dans le wiki.',
'data'	=> 'Données',
'userrights' => 'Gestion des droits utilisateurs',
'groups' => 'Groupes utilisateurs',

'booksourcetext' => 'Voici une liste de liens vers d\'autres sites qui vendent des livres neufs et d\'occasion et sur lesquels vous trouverez peut-être des informations sur les ouvrages que vous cherchez. {{SITENAME}} n\'étant liée à aucune de ces sociétés, elle n\'a aucunement l\'intention d\'en faire la promotion.',
'alphaindexline' => '$1 à $2',
'log'		=> 'Journaux',
'alllogstext'	=> 'Affichage combiné des journaux de copie, suppression, protection, blocage, et administrateur. Vous pouvez restreindre la vue en selectionnant un type de journal, un nom d\'utilisateur ou la page concernée.',
'logempty' => 'Aucun élement pertinent dans le journal.',

# Special:Allpages

'nextpage'          	=> 'Page suivante ($1)',
'allpagesfrom'		=> 'Afficher les pages à partir de :',
'allarticles'		=> 'Tous les articles',
'allinnamespace' 	=> 'Toutes les pages (espace de nom $1)',
'allnotinnamespace'	=> 'Toutes les pages (n\’étant pas dans l\'espace de nom $1)',
'allpagesnext'		=> 'Suivant',
'allpagesprev' 		=> 'Précédent',
'allpagessubmit' 	=> 'Valider',
'allpagesprefix'	=> 'Afficher les pages commençant par le préfixe :',

# Email this user
#
'mailnologin' => 'Pas d\'adresse',
'mailnologintext' => 'Vous devez être [[Special:Userlogin|connecté]]
et avoir indiqué une adresse électronique valide dans vos [[Special:Preferences|préférences]]
pour avoir la permission d\'envoyer un message à un autre utilisateur.',
'emailuser'  => 'Envoyer un message à cet utilisateur',
'emailpage'  => 'Envoyer un courriel à l\'utilisateur',
'emailpagetext' => 'Si cet utilisateur a indiqué une adresse électronique valide dans ses préférences, le formulaire ci-dessous lui enverra un message.
L\'adresse électronique que vous avez indiquée dans vos préférences apparaîtra dans le champ « Expéditeur » de votre message afin que le destinataire puisse vous répondre.',
'usermailererror' => 'Erreur dans le sujet du courriel :',
'defemailsubject' => 'courriel envoyé depuis {{SITENAME}}',
'noemailtitle' => 'Pas d\'adresse électronique',
'noemailtext' => 'Cet utilisateur n\'a pas spécifié d\'adresse de courriel valide ou a choisi de ne pas recevoir de courriel des autres utilisateurs.',
'emailfrom'  => 'Expéditeur',
'emailto'  => 'Destinataire',
'emailsubject' => 'Objet',
'emailsend'  => 'Envoyer',
'emailsent'  => 'Message envoyé',
'emailsenttext' => 'Votre message a été envoyé.',

# Watchlist
#
'watchlist'	=> 'Liste de suivi',
'nowatchlist'	=> 'Votre liste de suivi ne contient aucun article.',
'watchlistcount' 	=> '\'\'\'Vous avez $1 pages dans votre liste de suivi, en incluant les pages de discussion\'\'\'',
'clearwatchlist' 	=> 'Nettoyer votre liste de suivi',
'watchlistcleartext' => 'Êtes-vous sûr de vouloir les retirer ?',
'watchlistclearbutton' => 'Nettoyer la liste de suivi',
'watchlistcleardone' => 'Votre liste de suivi a été nettoyée. $1 pages en ont été retirées.',
'watchnologin'	=> 'Non connecté',
'watchnologintext' => 'Vous devez être [[Special:Userlogin|connecté]] pour modifier votre liste.',
'addedwatch'	=> 'Ajouté à la liste de suivi',
'addedwatchtext' => 'La page « $1 » a été ajoutée à votre [[Special:Watchlist|liste de suivi]].
Les prochaines modifications de cette page et de la page de discussion associée seront répertoriées ici, et la page apparaîtra \'\'\'en gras\'\'\' dans la [[Special:Recentchanges|liste des modifications récentes]] pour être repérée plus facilement.

Pour supprimer cette page de votre liste de suivi, cliquez sur « ne plus suivre » dans le cadre de navigation.',
'removedwatch'	=> 'Retirée de la liste de suivi',
'removedwatchtext' => 'La page « $1 » a été retirée de votre liste de suivi.',
'watch'		=> 'Suivre',
'watchthispage'	=> 'Suivre cette page',
'unwatch'	=> 'Ne plus suivre',
'unwatchthispage' => 'Ne plus suivre',
'notanarticle'	=> 'Pas un article',
'watchnochange' => "Aucune des pages que vous suivez n'a été modifiée pendant la période affichée",
'watchdetails' => 'Vous suivez $1 pages, sans compter les pages de discussion.
* [[Special:Watchlist/edit|Voir et éditer la liste de suivi]]
* [[Special:Watchlist/clear|Retirer toutes les pages de ma liste de suivi]]',
'wlheader-enotif' 		=> '* La notification par courriel est activée.',
'wlheader-showupdated'   => '* Les pages qui ont été modifiées depuis votre dernière visite sont montrées en \'\'\'gras\'\'\'',
'watchmethod-recent' => 'vérification des modifications récentes des pages suivies',
'watchmethod-list' => 'vérification des pages suivies pour des modifications récentes',
'removechecked' => 'Retirer de la liste de suivi les pages sélectionnées',
'watchlistcontains' => 'Votre liste de suivi contient $1 pages',
'watcheditlist' => 'Ceci est votre liste de suivi par ordre alphabétique. Sélectionnez les pages que vous souhaitez retirer de la liste et cliquez le bouton « retirer de la liste de suivi » en bas de l\'écran. (retirer un article retire aussi la page de discussion associée, et vice-versa)',
'removingchecked' => 'Les articles sélectionnés sont retirés de votre liste de suivi...',
'couldntremove' => 'Impossible de retirer l\'article « $1 »...',
'iteminvalidname' => 'Problème avec l\'article « $1 » : le nom est invalide...',
'wlnote' => 'Ci-dessous se trouvent les $1 dernières modifications depuis les <b>$2</b> dernières heures.',
'wlshowlast' => 'Montrer les dernières $1 heures $2 jours $3',
'wlsaved' => 'La liste de suivi n\'est remise à jour qu\'une fois par heure pour alléger la charge sur le serveur.',
'wlhideshowown'   	=> '$1 mes modifications',
'wlhideshowbots'   	=> '$1 les contributions de bots',

'enotif_mailer' 		=> '{{SITENAME}} Notificateur par courriel',
'enotif_reset'			=> 'Marque toutes les pages comme visitées',
'enotif_newpagetext'=> 'Ceci est une nouvelle page.',
'changed'			=> 'modifiée',
'created'			=> 'créée',
'enotif_subject' 	=> '{{SITENAME}} la page $PAGETITLE a été $CHANGEDORCREATED par $PAGEEDITOR',
'enotif_lastvisited' => 'Consultez $1 pour tous les changements depuis votre dernière visite.',
'enotif_body' => 'Cher $WATCHINGUSERNAME,

la page de {{SITENAME}} $PAGETITLE a été $CHANGEDORCREATED le $PAGEEDITDATE par $PAGEEDITOR, voyez $PAGETITLE_URL pour la version actuelle.

$NEWPAGE

Résumé de l\'éditeur : $PAGESUMMARY $PAGEMINOREDIT

Contactez l\'éditeur :
courriel : $PAGEEDITOR_EMAIL
wiki : $PAGEEDITOR_WIKI

Il n\'y aura pas de nouvelles notifications en cas d\'autres modifications à moins que vous ne visitiez cette page. Vous pouvez aussi remettre à zéro le notificateur pour toutes les pages de votre liste de suivi.

             Votre {{SITENAME}} système de notification

--
Pour modifier les paramètres de votre liste de suivi, visitez
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Retour et assistance :
{{SERVER}}{{localurl:Help:Contents}}',

# Delete/protect/revert
#
'deletepage'	=> 'Supprimer une page',
'confirm'	=> 'Confirmer',
'excontent'	=> 'contenant « $1 »',
'excontentauthor' => 'le contenu était : « $1 » (et le seul contributeur était « $2 »)',
'exbeforeblank' => 'Avant son blanchiment, cette page contenait : $1',
'exblank'	=> 'page vide',
'confirmdelete' => 'Confirmer la suppression',
'deletesub'	=> '(Suppression de « $1 »)',
'historywarning' => 'Attention : La page que vous êtes sur le point de supprimer a un historique :',
'confirmdeletetext' => 'Vous êtes sur le point de supprimer définitivement de la base de données une page ou une image, ainsi que toutes ses versions antérieures. Veuillez confirmer que c\'est bien là ce que vous voulez faire, que vous en comprenez les conséquences et que vous faites cela en accord avec les [[Project:Policy|règles internes]].',
'actioncomplete' => 'Action effectuée',
'deletedtext'	=> '« $1 » a été supprimé.
Voir $2 pour une liste des suppressions récentes.',
'deletedarticle' => 'a effacé « $1 »',
'dellogpage'	=> 'Journal des effacements',
'dellogpagetext' => 'Voici la liste des suppressions récentes.
L\'heure indiquée est celle du serveur (UTC).',
'deletionlog'	=> 'journal des effacements',
'reverted'	=> 'Rétablissement de la version précédente',
'deletecomment' => 'Motif de la suppression',
'imagereverted' => 'La version précédente a été rétablie.',
'rollback'	=> 'révoquer modifications',
'rollback_short' => 'Révoquer',
'rollbacklink'	=> 'révoquer',
'rollbackfailed' => 'La révocation a échoué',
'cantrollback'	=> 'Impossible de révoquer : il n\'y a qu\'un seul auteur à avoir modifié cet article',
'alreadyrolled'	=> "Impossible de révoquer la dernière modification de l’article « [[$1]] » effectuée par [[User:$2|$2]] ([[User talk:$2|Discussion]]) ; quelqu’un d’autre a déjà modifié ou révoqué l’article. La dernière modification a été effectuée par [[User:$3|$3]] ([[User talk:$3|Discussion]]).",
'editcomment' => "Le résumé de la modification était: <i>« $1 »</i>.",
'revertpage'	=> "Modifications de [[Special:Contributions/$2|$2]] ([[User_talk:$2|Discussion]]) révertées; retour à l'ancienne version de [[User:$1|$1]]",
'sessionfailure' => 'Il semble qu\'il y ait eu un problème avec votre session d\'identification; cette action a été annulée par précaution contre le piratage de session. Merci de cliquer sur « retour » et de recharger la page d\'où vous venez, et de réessayer.',
'protectlogpage' => 'Historique des protections',
'protectlogtext' => 'Voir les [[Project:Page protégée|directives]] pour plus d\'information.',
'protectedarticle' => 'a protégé « $1 »',
'unprotectedarticle' => 'a déprotégé « $1 »',
'protectsub' => "(Protéger « $1 »)",
'confirmprotect' => 'Confirmer la protection',
'confirmprotecttext' => 'Voulez-vous réellement protéger cette page ?',
'protectmoveonly' => 'Protéger uniquement les déplacements',
'protectcomment' => 'Raison de la protection',
'unprotectsub' => '(Déprotéger « $1 »)',
'confirmunprotecttext' => 'Voulez-vous réellement déprotéger cette page ?',
'confirmunprotect' => 'Raison de la déprotection',
'unprotectcomment' => 'Raison du débloquage',
'protect-unchain' => 'Débloquer les permissions de déplacement',
'protect-text' => 'Vous pouvez consulter et modifier le niveau de protection de la page <strong>$1</strong>.
Veuillez vous assurez que vous suivez les [[Project:Protected page|règles internes]].',
'protect-viewtext' => 'Votre compte ne possède pas les permissions nécessaires pour changer les niveaux de protection de page. La configuration actuelle pour la page <strong>$1</strong> est la suivante :',
'protect-default' => '(défaut)',
'protect-level-autoconfirmed' => 'Bloquer les utilisateurs non enregistrés',
'protect-level-sysop' => 'Uniquement les administrateurs',

# restrictions (nouns)
'restriction-edit' => 'Modifier',
'restriction-move' => 'Déplacer',

# Special:Undelete
#
'undelete'	=> 'Voir la page supprimée',
'undeletepage'	=> 'Voir et restaurer la page supprimée',
'undeletepagetext' => 'Ces pages ont été supprimées et se trouvent dans l\'archive, elles sont toujours dans la base de donnée et peuvent être restaurées.
L\'archive peut être effacée périodiquement.',
'undeleteextrahelp' => 'Pour restaurer toutes les versions de cette page, laissez vierges toutes les cases à cocher, puis cliquez sur \'\'\'\'\'Procéder à la restauration\'\'\'\'\'.<br />Pour procéder à une restauration sélective, cochez les cases correspondant aux versions qui sont à restaurer, puis cliquez sur \'\'\'\'\'Procéder à la restauration\'\'\'\'\'.<br />En cliquant sur le bouton \'\'\'\'\'Réinitialiser\'\'\'\'\', la boîte de résumé et les cases cochées seront remises à zéro.',
'undeletearticle' => 'Restaurer les articles supprimés',
'undeleterevisions' => '$1 révisions archivées',
'undeletehistory' => 'Si vous restaurez la page, toutes les révisions seront restaurées dans l\'historique.

Si une nouvelle page avec le même nom a été créée depuis la suppression, les révisions restaurées apparaîtront dans l\'historique antérieur et la version courante ne sera pas automatiquement remplacée.',
'undeletehistorynoadmin' => 'Cet article a été supprimé. Le motif de la suppression est indiqué dans le résumé ci-dessous, avec les détails des utilisateurs qui l\'ont édité avant sa suppression. Le contenu de ces versions n\'est disponible qu\'aux administrateurs.',
'undeleterevision' => '$1 versions archivées',
'undeletebtn'	=> 'Procéder à la restauration !',
'undeletereset' => 'Réinitialiser',
'undeletecomment' => 'Commentaire :',
'undeletedarticle' => 'a restauré « [[$1]] »',
'undeletedrevisions' => '$1 versions ont été restaurées',

# Namespace form on various pages
'namespace' => 'Espace de nom :',
'invert' => 'Inverser la sélection',

# Contributions
#
'contributions'	=> 'Contributions',
'mycontris'	=> 'Mes contributions',
'contribsub'	=> 'Pour $1',
'nocontribs'	=> 'Aucune modification correspondant à ces critères n\'a été trouvée.',
'ucnote'	=> 'Voici les <b>$1</b> dernières modifications effectuées par cet utilisateur au cours des <b>$2</b> derniers jours.',
'uclinks'	=> 'Afficher les $1 dernières modifications; afficher les $2 derniers jours.',
'uctop'		=> ' (dernière)',
'newbies'       => 'Nouveaux contributeurs',

'sp-contributions-newest' => 'Dernières contributions',
'sp-contributions-oldest' => 'Premières contributions',
'sp-contributions-newer'  => '$1 précédents',
'sp-contributions-older'  => '$1 suivants',
'sp-contributions-newbies-sub' => 'Contributions des nouveaux utilisateurs',


# What links here

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

Une telle mesure ne doit être prise que pour empêcher le vandalisme et en accord avec les [[{{ns:project}}:Policy|règles internes]].
Donnez ci-dessous une raison précise (par exemple en indiquant les pages qui ont été vandalisées).',
'ipaddress'	=> 'Adresse IP',
'ipadressorusername' => 'Adresse IP ou nom d\'utilisateur',
'ipbexpiry' => 'Durée du blocage',
'ipbreason'	=> 'Motif du blocage',
'ipbanononly'   => 'Bloquer uniquement les utilisateurs anonymes',
'ipbcreateaccount' => 'Empêcher la création de compte',
'ipbsubmit'	=> 'Bloquer cet utilisateur',
'ipbother'	=> 'Autre durée',
'ipboptions'		=> '2 heures:2 hours,1 jour:1 day,3 jours:3 days,1 semaine:1 week,2 semaines:2 weeks,1 mois:1 month,3 mois:3 months,6 mois:6 months,1 an:1 year,Permanent:infinite',
'ipbotheroption'	=> 'autre',
'badipaddress'	=> 'L\'adresse IP n\'est pas correcte.',
'blockipsuccesssub' => 'Blocage réussi',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1|$1]] a été bloqué.<br />Vous pouvez consulter sur cette [[Special:Ipblocklist|page]] la liste des adresses IP bloquées.',
'unblockip'	=> "Débloquer un utilisateur",
'unblockiptext' => 'Utilisez le formulaire ci-dessous pour rétablir l\'accès en écriture
d\'une adresse IP précédemment bloquée.',
'ipusubmit'	=> 'Débloquer cette adresse',
'ipblocklist'	=> 'Liste des blocages',
'blocklistline' => '$1, $2 a bloqué $3 ($4)',
'infiniteblock' => 'permanent',
'expiringblock' => 'expire le $1',
'anononlyblock' => 'uniquement anonyme',
'createaccountblock' => 'la création de compte est bloquée',
'ipblocklistempty'=> 'La liste de blocage est vide.',
'blocklink'	=> 'bloquer',
'unblocklink'	=> 'débloquer',
'contribslink'	=> 'contributions',
'autoblocker'	=> 'Vous avez été bloqué automatiquement parce que votre adresse IP a été récemment utilisée par « $1 ». La raison fournie pour le blocage de $1 est : « $2 ».',
'blocklogpage'	=> 'Journal des blocages',
'blocklogentry'	=> 'blocage de [[$1]] avec un temps d\'expiration de $2',
'blocklogtext'	=> 'Ceci est la trace des blocages et déblocages des utilisateurs. Les adresses IP automatiquement bloquées ne sont pas listées. Consultez la [[Special:Ipblocklist|liste des utilisateurs bloqués]] pour voir qui est actuellement effectivement bloqué.',
'unblocklogentry'	=> 'déblocage de « $1 »',
'ipb_expiry_invalid' => 'temps d\'expiration invalide.',
'ipb_already_blocked' => '"$1" est déjà bloqué',
'ip_range_invalid' => 'Bloc IP incorrect.',
'proxyblocker' => 'Bloqueur de proxy',
'proxyblockreason' => 'Votre ip a été bloquée car il s\'agit d\'un proxy ouvert. Merci de contacter votre fournisseur d\'accès internet ou votre support technique et de l\'informer de ce problème de sécurité.',
'proxyblocksuccess' => 'Terminé.',
'sorbsreason'   => 'Votre adresse IP est listée en tant que proxy ouvert [http://www.sorbs.net SORBS] DNSBL.',
'sorbs_create_account_reason' => 'Votre adresse IP est listée en tant que proxy ouvert [http://www.sorbs.net SORBS] DNSBL. Vous ne pouvez créer un compte',

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
'makesysoptitle'	=> 'Donne les droits d\'administrateur.',
'makesysoptext'		=> 'Ce formulaire est utilisé par les bureaucrates pour donner les droits d\'administrateur.
Tapez le nom de l\'utilisateur dans la boite et pressez le bouton pour lui donner les droits.',
'makesysopname'		=> 'Nom de l\'utilisateur:',
'makesysopsubmit'	=> 'Donner les droits d\'administrateur à cet utilisateur',
'makesysopok'		=> '<b>L\'utilisateur « $1 » est désormais administrateur.</b>',
'makesysopfail'		=> '<b>L\'utilisateur « $1 » ne peut être converti en adminitrateur. (Avez vous entré le nom correctement?)</b>',
'setbureaucratflag' => 'Ajouter le statut de Bureaucrate',
'rightslog'		=> 'Journal des droits',
'rightslogtext'		=> 'Ceci est un journal des modifications de statut d\'utilisateur..',
'rightslogentry'	=> 'Droits de l\'utilisateur « $1 » de $2 à $3',
'rights'		=> 'Droits:',
'set_user_rights'	=> 'Appliquer les droits à l\'utilisateur',
'user_rights_set'	=> '<b>Les droits de l\'utilisateur « $1 » sont mis à jour</b>',
'set_rights_fail'	=> '<b>Les droits de l\'utilisateur « $1 » n\'ont pas pu être mis à jour. (Avez vous entré le nom correctement?)</b>',
'makesysop'         => 'Donner les droits d\'administrateur à un utilisateur',
'already_sysop'     => 'Cet utilisateur est déjà un administrateur',
'already_bureaucrat' => 'Cet utilisateur est déjà un bureaucrate',
'rightsnone' 		=> '(aucun)',

# Move page
#
'movepage'  => 'Renommer une page',
'movepagetext' => 'Utilisez le formulaire ci-dessous pour renommer une page, en déplaçant tout son historique vers le nouveau nom.
L\'ancien titre deviendra une page de redirection vers le nouveau titre. Les liens vers le titre de l\'ancienne page ne seront pas changés ; veuillez vérifier si cet déplacement n\'a pas créé de double redirect. Vous devez vous assurez que les liens continuent de pointer vers leur destination supposée.

Une page ne sera pas déplacée si il y a déjà une page au nouveau titre, à moins que la page soit vide, ou une redirection, et qu\'elle n\'ait pas d\'historique. Ce qui veut dire que vous pouvez renommer une page vers sa position d\'origine si vous avez commis une erreur, et que vous ne pouvez effacer une page déjà existante par ce procédé.',
'movepagetalktext' => 'La page de discussion associée, si présente, sera automatiquement renommée avec \'\'\'sauf si:\'\'\'
*Vous renommez une page vers un autre espace,
*Une page de discussion existe déjà avec le nouveau nom, ou
*Vous avez désélectionné le bouton ci-dessous.

Dans ce cas, vous devrez renommer ou fusionner la page manuellement si vous le désirez.',

'movearticle'	=> 'Renommer l\'article',
'movenologin'	=> 'Non connecté',
'movenologintext' => 'Pour avoir la permission de renommer un article, vous devez être [[Special:Userlogin|connecté]] en tant qu\'utilisateur enregistré.',
'newtitle'	=> 'Nouveau titre',
'movepagebtn'	=> 'Renommer l\'article',
'pagemovedsub' => 'Renommage réussi',
'pagemovedtext' => 'L\'article « [[$1]] » a été renommé en « [[$2]] ».',
'articleexists' => 'Il existe déjà un article portant ce titre, ou le titre que vous avez choisi n\'est pas valide. Veuillez en choisir un autre.',
'talkexists'	=> 'La page elle-même a été déplacée avec succès, mais la page de discussion n\'a pas pu être déplacée car il en existait déjà une sous le nouveau nom. Veuillez les fusionner manuellement.',
'movedto'  => 'renommé en',
'movetalk'  => 'Renommer aussi la page « discussion », s\'il y a lieu.',
'talkpagemoved' => 'La page discussion correspondante a également été déplacée.',
'talkpagenotmoved' => 'La page discussion correspondante n\'a <strong>pas</strong> été déplacée.',
'1movedto2' => 'a déplacé $1 vers $2',
'1movedto2_redir' => 'a déplacé $1 vers $2 (redirection)',
'movelogpage' => 'Journal des renommages',
'movelogpagetext' => 'Ci-dessous apparaît la liste des pages renommées.',
'movereason' => 'Raison du renommage',
'revertmove'	=> 'révocation',
'delete_and_move' => 'Supprimer et renommer',
'delete_and_move_text'	=>
'==Suppression requise==

L\'article de destination "[[$1]]" existe déjà. Voulez-vous le supprimer pour permettre le renommage ?',
'delete_and_move_confirm' => 'Oui, supprimer la page',
'delete_and_move_reason' => 'Page supprimée pour permettre le renommage',
'selfmove' => 'La page source et de destination sont identiques ; il est impossible de renommer une page en elle-même',
'immobile_namespace' => 'Le titre de destination est d\'un type spécial ; il est impossible de renommer des pages vers cet espace de nom',


# Export page
'export'	=> 'Exporter des pages',
'exporttext'	=> 'Vous pouvez exporter en XML le texte et l\'historique d\'une page ou d\'un ensemble de pages; le résultat peut alors être importé dans un autre wiki fonctionnant avec le logiciel MediaWiki.

Pour exporter des pages, entrez leurs titres dans la boîte de texte ci-dessous, un titre par ligne, et sélectionnez si vous désirez ou non la version actuelle avec toutes les anciennes versions, avec la page d\'historique, ou simplement la page actuelle avec des informations sur la dernière modification.

Dans ce dernier cas, vous pouvez aussi utiliser un lien, comme [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]] pour la page {{Mediawiki:mainpage}}.',

'exportcuronly'	=> 'Exporter uniquement la version courante sans l\'historique complet',
'exportnohistory' => "----
'''Note :''' l'exportation de l'historique complet des pages à travers ce formulaire a été désactive pour des raisons de performance.",

# Namespace 8 related

'allmessages'	=> 'Liste des messages système',
'allmessagesname' => 'Nom du champ',
'allmessagesdefault' => 'Message par défaut',
'allmessagescurrent' => 'Message actuel',
'allmessagestext'	=> 'Ceci est la liste de tous les messages disponibles dans l\'espace MediaWiki',
'allmessagesnotsupportedUI' => 'Special:AllMessages n\'accepte pas la langue de votre interface (<b>$1</b>) sur ce site.',
'allmessagesnotsupportedDB' => '\'\'\'Special:Allmessages\'\'\' n\'est pas disponible car \'\'\'$wgUseDatabaseMessages\'\'\' est désactivé.',
'allmessagesfilter' => 'Filtre d\'expression régulière :',
'allmessagesmodified' => 'N\'afficher que les modifications',

# Thumbnails

'thumbnail-more'	=> 'Agrandir',
'missingimage'		=> '<b>Image manquante</b><br /><i>$1</i>',
'filemissing'		=> 'Fichier manquant',
'thumbnail_error'   => 'Erreur lors de la création de la miniature : $1',

# Special:Import

'import'	=> 'Importer des pages',
'importinterwiki' => 'Import inter-wiki',
'importtext'	=> 'Veuillez exporter le fichier depuis le wiki d\'origine en utilisant l\'outil Special:Export, le sauvegarder sur votre disque dur et le copier ici.',
'importfailed'	=> 'Échec de l\'import : $1',
'importhistoryconflict' => 'Il y a un conflit dans l\'historique des versions (cette page à peut être déjà été importée avant)',
'importnotext'	=> 'Vide ou sans texte',
'importsuccess'	=> 'L\'import a réussi !',
'importhistoryconflict' => 'Il existe un conflit de versions d\'historique (cette page a peut-être déjà été importée)',
'importnosources' => 'Aucune source inter-wiki n\'a été définie et la copie directe d\'historique est désactivée.',
'importnofile' => 'Aucun fichier d\'import n\'a été copié.',
'importuploaderror' => 'La copie du fichier d\'import a échouée ; la taille du fichier est peut-être supérieure à celle autorisée.',

# Keyboard access keys for power users

# tooltip help for some actions, most are in Monobook.js

'tooltip-search' => 'Rechercher dans ce wiki',
'tooltip-minoredit' => 'Marquer cette modification comme mineur [alt-i]',
'tooltip-save' => 'Sauvegarder vos modifications [alt-s]',
'tooltip-preview' => 'Prévisualiser vos changements, veuillez utiliser cette fonction avant de sauvegarder ! [alt-p]',
'tooltip-diff' => 'Voir les modifications que vous avez apportées au texte. [alt-v]',
'tooltip-compareselectedversions' => 'Voir les différences entre les deux versions séléctionnées de cette page. [alt-v]',
'tooltip-watch' => 'Rajouter cette page à votre liste de suivi [alt-w]',

# stylesheets

'Common.css' => '/** Le CSS placé ici sera appliqué à toutes les apparences. */',
'Monobook.css' => '/* Le CSS placé ici affectera les utilisateurs du skin Monobook */',

# Metadata

'nodublincore' => 'Les métadonnées « Dublin Core RDF » sont désactivées sur ce serveur.',
'nocreativecommons' => 'Les données méta « Creative Commons RDF » sont désactivées sur ce serveur.',
'notacceptable' => 'Ce serveur wiki ne peut pas fournir les données dans un format que votre client est capable de lire.',

# Attribution

'anonymous'	=> 'Utilisateur(s) anonyme(s) de {{SITENAME}}',
'siteuser'	=> 'Utilisateur $1 de {{SITENAME}}',
'lastmodifiedby' => 'Cette page a été modifiée pour la dernière fois le $1 par $2',
'and'	=> 'et',
'othercontribs' => 'Basé sur le travail de $1.',
'others' => 'autres',
'siteusers'	=> 'Utilisateur(s) $1',
'creditspage' => 'Page de crédits',
'nocredits' => 'Il n\'y a pas d\'informations de crédits disponible pour cette page.',

# Spam protection

'spamprotectiontitle' => 'Filtre de protection contre le spam',
'spamprotectiontext' => 'La page que vous avez tenté de sauvegarder a été bloquée par notre filtre anti-spam. Ceci est probablement causé par un lien vers un site externe',
'spamprotectionmatch' => 'Le texte suivant a déclenché le détecteur de spam: $1',
'subcategorycount' => 'Il y a {{PLURAL:$1|une sous-catégorie|a $1 sous-catégories}} dans cette catégorie.',
'categoryarticlecount' => 'Il y a {{PLURAL:$1|un article|$1 articles}} dans cette catégorie.',
'spambot_username' => 'Nettoyage de spam MediaWiki',
'spam_reverting' => 'Retour à la dernière version ne contenant pas de lien vers $1',
'spam_blanking' => 'Blanchissement de toutes les versions contenant un lien vers $1',

# Info page
'infosubtitle' => 'Information pour la page',
'numedits' => 'Nombre de modifications (article) : $1',
'numtalkedits' => 'Nombre de modifications (page de discussion ) : $1',
'numwatchers' => 'Nombre de contributeurs ayant la page dans leur liste de suivi : $1',
'numauthors' => 'Nombre d\'auteurs distincts (article) : $1',
'numtalkauthors' => 'Nombre d\'auteurs distincts (page de discussion ) : $1',

# Math options

'mw_math_png' => 'Toujours produire une image PNG',
'mw_math_simple' => 'HTML si très simple, autrement PNG',
'mw_math_html' => 'HTML si possible, autrement PNG',
'mw_math_source' => 'Laisser le code TeX original',
'mw_math_modern' => 'Pour les navigateurs modernes',
'mw_math_mathml' => 'MathML',

# Patrolling

'markaspatrolleddiff' => 'Marquer comme vérifiée',
'markaspatrolledtext' => 'Marquer cet article comme vérifié',
'markedaspatrolled' => 'Marqué comme vérifié',
'markedaspatrolledtext' => 'La version sélectionnée a été marquée comme vérifiée.',
'rcpatroldisabled'      => 'La fonction de patrouille des modifications récentes n\'est pas activée.',
'rcpatroldisabledtext' => 'La fonctionnalité de surveillance des modifications récentes n\'est pas activée.',
'markedaspatrollederror'  => 'Impossibilté de marquer en tant que révision patrouillée',
'markedaspatrollederrortext' => 'Vous devez spécifier une révision à marquer comme patrouillée.',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => '/* infobulles et touches d\'accès */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Ma page utilisateur\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'La page utilisateur de l\\\'IP avec laquelle vous éditez\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Ma page de discussion\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussion à propos des modifications depuis cette adresse IP\');
ta[\'pt-preferences\'] = new Array(\'\',\'Mes préférences\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'liste des pages dont vous suivez les modifications.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Liste de mes contributions\');
ta[\'pt-login\'] = new Array(\'o\',\'Vous êtes invité à vous identifier, mais ce n\\\'est pas obligatoire.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Vous êtes invité à vous identifier, mais ce n\\\'est pas obligatoire.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Se déconnecter\');
ta[\'ca-talk\'] = new Array(\'t\',\'Discussion à propos de l\\\'article\');
ta[\'ca-edit\'] = new Array(\'e\',\'Vous pouvez éditer cette page. Merci d\\\'utiliser le bouton de prévisualisation avant de sauvegarder.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Ajouter un commentaire à cette discussion.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Cette page est protégée. Vous pouvez voir sa source.\');
ta[\'ca-history\'] = new Array(\'h\',\'Anciennes versions de cette page.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Protéger cette page\');
ta[\'ca-delete\'] = new Array(\'d\',\'Supprimer cette page\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Restaurer les modifications effectuées sur cette page avant sa suppression\');
ta[\'ca-move\'] = new Array(\'m\',\'Renommer cette page\');
ta[\'ca-watch\'] = new Array(\'w\',\'Ajouter cette page à votre liste de suivi\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Retirer cette page de votre liste de suivi\');
ta[\'search\'] = new Array(\'f\',\'Chercher dans ce wiki\');
ta[\'p-logo\'] = new Array(\'\',\'Page principale\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Visitez la page principle\');
ta[\'n-portal\'] = new Array(\'\',\'À propos de ce projet, ce que vous pouvez faire, où trouver les choses\');
ta[\'n-currentevents\'] = new Array(\'\',\'Trouver des informations sur les évènements actuels\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Liste des changements récents sur le wiki.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Charger une page aléatoire\');
ta[\'n-help\'] = new Array(\'\',\'Aide\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Aidez-nous\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Liste de tous les wikis qui lient vers cette page\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Changements récents dans les pages qui lient vers cette page\');
ta[\'feed-rss\'] = new Array(\'\',\'Flux RSS pour cette page\');
ta[\'feed-atom\'] = new Array(\'\',\'Flux Atom for this page\');
ta[\'t-contributions\'] = new Array(\'\',\'Voir la liste de contributions de cet utilisateur\');
ta[\'t-emailuser\'] = new Array(\'\',\'Envoyer un courriel à cet utilisateur\');
ta[\'t-upload\'] = new Array(\'u\',\'Télécharger une image ou des fichiers\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Liste de toutes les pages spéciales\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Voir l\\\'article\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Voir la page utilisateur\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Voir la page du média\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Ceci est une page spéciale, vous ne pouvez l\\\'éditer.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Voir la page du projet\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Voir la page de l\\\'image\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Voir le message système\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Voir le modèle\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Voir la page d\\\'aide\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Voir la page de la catégorie\');',

# image deletion
'deletedrevision' => 'L\'ancienne version $1 a été supprimée.',

# browsing diffs
'previousdiff' => '← Différence précédente',
'nextdiff' => 'Différence suivante →',
'imagemaxsize' => 'Limiter l\'image sur la page de description de l\'image à :',
'thumbsize'	=> 'Taille de la miniature :',
'showbigimage' => 'Télécharger une version en haute résolution ($1x$2, $3 KB)',

'newimages' => 'Galerie des nouveaux fichiers',
'noimages'  => 'Rien à voir.',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',

# variants for Serbian language

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'Utilisateur :',
'speciallogtitlelabel' => 'Titre :',

'passwordtooshort' => 'Votre mot de passe est trop court. Il doit contenir au moins $1 caractères.',

# Media Warning
'mediawarning' => '\'\'\'Attention\'\'\': Ce fichier peut contenir du code malveillant, votre système pouvant être mis en danger par son exécution.
<hr />',

'fileinfo' => '$1KB, type MIME : <code>$2</code>',

# Metadata

# external editor support
'edit-externally' => 'Modifier ce fichier en utilisant un application externe',
'edit-externally-help' => 'Voir [http://meta.wikimedia.org/wiki/Help:External_editors les instructions] pour plus d\'informations.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'toutes',
'imagelistall' => 'toutes',
'watchlistall1' => 'tout',
'watchlistall2' => 'tout',
'namespacesall' => 'tous',

# E-mail address confirmation

'confirmemail' => 'Confirmer adresse de courriel',
'confirmemail_text' => 'Ce wiki nécessite la vérification de votre adresse de courriel avant de pouvoir utiliser toute fonction de messagerie. Utilisez le bouton ci dessous pour envoyer un email de confirmation à votre adresse. Le courriel contiendra un lien contenant un code, chargez ce lien dans votre navigateur pour valider votre adresse.',
'confirmemail_send' => 'Envoyer un code de confirmation',
'confirmemail_sent' => 'Email de confirmation envoyé',
'confirmemail_sendfailed' => 'Impossible d\'envoyer l\'email de confirmation. Vérifiez votre adresse.',
'confirmemail_invalid' => 'Code de confirmation incorrect. Le code a peut être expiré',
'confirmemail_needlogin' => 'Vous devez vous $1 pour confirmer votre adresse de courriel.',
'confirmemail_success' => 'Votre adresse de courriel est confirmée. Vous pouvez maintenant vous connecter et profiter du wiki.',
'confirmemail_loggedin' => 'Votre adresse est maintenant confirmée',
'confirmemail_error' => 'Un problème est survenu en voulant enregistrer votre confirmation',
'confirmemail_subject' => 'Confirmation d\'adresse de courriel pour {{SITENAME}}',
'confirmemail_body' => 'Quelqu\'un, probablement vous avec l\'adresse IP $1, a enregistré un compte « $2 » avec cette adresse de courriel sur le site {{SITENAME}}.

Pour confirmer que ce compte vous appartient vraiment et activer les fonctions de messagerie sur {{SITENAME}}, veuillez suivre le lien ci dessous dans votre navigateur :

$3

Si il ne s\'agit pas de vous, n\'ouvrez pas le lien. Ce code de confirmation expirera le $4.',

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'Essayez la correspondance exacte',
'searchfulltext' => 'Recherche en texte intégral',
'createarticle' => 'Créer l\'article',

# Scary transclusion
'scarytranscludedisabled' => '[La transclusion interwiki est désactivée]',
'scarytranscludefailed' => '[La récupération de modèle a échoué pour $1 ; désolé]',
'scarytranscludetoolong' => '[L\'URL est trop longue ; désolé]',

# Trackbacks

# delete conflict

'deletedwhileediting' => 'Attention : cette page a été supprimée après que vous ayez commencé à l\'éditer.',
'confirmrecreate' => 'L\'utilisateur [[User:$1|$1]] ([[User talk:$1|talk]]) a supprimé cette page après que vous ayez commencé à l\'éditer avec la raison : \'\'$2\'\'
Veuillez confirmer que vous désirez vraiment re-créer cette page.',
'recreate' => 'Recréer',

# HTML dump
'redirectingto' => 'Redirection vers [[$1]]...',

# action=purge
'confirm_purge' => 'Voulez-vous rafraîchir cette page ?\n\n$1',
'confirm_purge_button' => 'Confirmer',

'youhavenewmessagesmulti' => 'Vous avez de nouveaux messages sur $1',
'searchcontaining' => 'Chercher les articles contenant \'\'$1\'\'.',
'searchnamed' => 'Chercher les articles nommés \'\'$1\'\'.',
'articletitles' => 'Articles commençant par \'\'$1\'\'',
'hideresults' => 'Cacher les résultats',

# DISPLAYTITLE
'displaytitle' => '(Lier vers cette page comme [[$1]])',

# Separator for categories in page lists
# Please don't localise this

# Metadata
'metadata' => 'Métadonnées',
'metadata-help' => 'Ce fichier contient des informations additionnelles, certainement ajoutée par l\'appareil photographique ou le numériseur utilisé pour le créer ou le digitaliser. Si l\'état du fichier a été altéré, certains détails peuvent ne pas refléter totalement l\'image modifiée.',
'metadata-expand' => 'Montrer les détails',
'metadata-collapse' => 'Cacher les détails',
'metadata-fields' => 'Les champs de métadonnées d\'EXIF listés dans ce message seront inclus dans la page de description de l\'image quand la table de métadonnées sera réduite. Les autres champs seront cachés par défaut
* constructeur
* modèle
* Date et heure
* temps d\'exposition
* Nombre F
* longueur de la focale',

# EXIF tags
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
'exif-whitebalance' => 'Balance des blancs',
'exif-contrast' => 'Contraste',
'exif-compression-1' => 'Sans compression',
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


?>
