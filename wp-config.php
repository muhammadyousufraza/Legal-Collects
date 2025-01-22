<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '>skF1c-e+ALTc?4-mrn}}%9]L]V=3^QY,0`^EL^Q!Ldx404Qh~T$>A.ekeI}v?oc' );
define( 'SECURE_AUTH_KEY',   '*$#bSXP7S5#O1^-6hHDE{gZ)E<0q.iS=M2=%s@oxT>nB|tqeA-loztbrjCp/5His' );
define( 'LOGGED_IN_KEY',     '; N>KO2:cJZxI&kEP_^)X5=7/s^,yY^R5k,$jGKKvQr%:QIU%)I~/YrsAEEce|Vu' );
define( 'NONCE_KEY',         'jOO u.bPlDc48p8(`JUyxLz4Uo>PR>{d W*0TU?X}b2qX(LT9:KuL*49-h}}: R=' );
define( 'AUTH_SALT',         'RfQx.vSk#-[<]H.pc#IJK>#},l[u>*nFXX|~,Vo|h*?;rrKQClPQL9J8Loe=XNb#' );
define( 'SECURE_AUTH_SALT',  'G.k/A0.6|$nl3?(neP_I/bPVaNhj5Xh.cPZ`1xc]V}Aod#L{K.-oxbKZhiA{UfW[' );
define( 'LOGGED_IN_SALT',    '*$MY/nq9/]/2v/,h,}Kw6$mwW[Fc$TDFtr=XTJbRdoiE)[})vN&dN`saS*jjR*D7' );
define( 'NONCE_SALT',        'SKrz.b-od>;~FTF5Z&9e)G?,xR}sML&W$}SVJU9}~N<h]~~c0/?<>~q#7Bfhf|o*' );
define( 'WP_CACHE_KEY_SALT', 'J%A4^zz|moT8.k+pG{ Y8iei>/jgjR(kBz7x1mk3Z;cZ#t5%921Mp,/M/yPVJe6r' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
