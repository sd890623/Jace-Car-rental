<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ecalypse');

/** MySQL database username */
define('DB_USER', 'gribo');

/** MySQL database password */
define('DB_PASSWORD', 'klavesnice');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'wu:B-y&_:_H#;-;JPOrGIeysGARXWJ+^f-$BGY5Aw]0eq?a/Ls^GY@N_F%HVO}0b');
define('SECURE_AUTH_KEY',  'g[g%K*-aRuJ9Q<pcKi!F.q YjS-7awn[G2H42k~[_iosh5u3*b#mfU:TZw24NH_v');
define('LOGGED_IN_KEY',    'iS+!jdSNNr6I@5ZQe&<9Hyll33i/sli pv-<J/|Ij$X<$gyY&&Q&=|v@3dhFGrxs');
define('NONCE_KEY',        'A5[lO{H=T{ Yf;e;|nc21FYQ}J`8:$a}Wnj(.!$ko-@dg1Qk1rRnYT@ZFH.Nrn4!');
define('AUTH_SALT',        '7hHkN!7kw>@Gi_Fn%{(ly%98V)A=%x(c{y&:~(, /I:6bR)L<djr-B`;[j {m|5/');
define('SECURE_AUTH_SALT', 'BPOXmT+r/Jq,_v|/Hwpvaso<a|:od4eiAx7W-:-J%m9SqeRvF|-_N]#?IL7{kXly');
define('LOGGED_IN_SALT',   'ofj?1nDf}u8.|lJZ,x$<DKUI1Mcr9^E9dV6/(O?;:m#J4][+5h6/-9],Hd}J-K|!');
define('NONCE_SALT',       '!|]B-LdT6|wLJlJ<;J:+WFgmk-(=,RmLq|ZSxPm.k~n,%yy$88th>YmJgKkx(8fA');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
