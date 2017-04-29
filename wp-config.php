<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clés secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C’est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d’installation. Vous n’avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'la petite boite');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clés uniques d’authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n’importe quel moment, afin d’invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'iYUU3OGx_^7~[np=-`rlrT8e4}rXEsky 1~E%Xt@c.UT%ONuGoF<^-z7Sl>!z>Q-');
define('SECURE_AUTH_KEY',  'Q;.Q&2*Suw<5=Wh%oreP^HRokckXb0v{@zh;G1:@nmN<|1YU~P!`Gpr&`zJfN9mX');
define('LOGGED_IN_KEY',    'If-OML0wd95sI3|K/?VAo=f&S6t4kH41o]MX#8*7lm#uUyUU^F6*E]mEF86ZiBR`');
define('NONCE_KEY',        'SbS^OfZ>b^yY,L/?#*F[J]m;;%h.](G|DB@[%P-;Hy*2^tvK (MP5?l6$a4$SLcl');
define('AUTH_SALT',        '2[{KLb:B;*aH|Rt8wK]],#~tFOF;J^0 qa[quj<i,-^V`q713H*f!9>5@2cLoib4');
define('SECURE_AUTH_SALT', '0(Sk]6Q9EB(gaW<lx3{iF7[#)%U`$4`aHXyGgn4OmgAJt],GbBX@3XKt6KE/a:<1');
define('LOGGED_IN_SALT',   'vQkBEfWIqE#r%qI6=,_>Z:]X6Eqq[WUm%=/3iA.)x=)Cs?7wc_52C d1sDzm;D_7');
define('NONCE_SALT',       'Rp;Op50eV qfAP xt,i|S$WS*^AQ]u?Q$App8PtgY@:Aj66rkHxy<qK4(x2O3&~w');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N’utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés !
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l’affichage des
 * notifications d’erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d’extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d’information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* C’est tout, ne touchez pas à ce qui suit ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');