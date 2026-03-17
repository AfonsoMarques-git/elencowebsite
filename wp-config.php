<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'festb2_elencoDB' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'Zg3Vu>zp$;aM9pv*k!P`>kRNK:li(x%7,:)n-m_LX+%4Sn)xH9ZnM_sUt%34Q;qt' );
define( 'SECURE_AUTH_KEY',  'PC01CWkHLu8agii&w?[IO6 +*ppm3bdxc9&)+:|llA.O[g-XF&COD2(m3QVEd9#S' );
define( 'LOGGED_IN_KEY',    ' >I*?eu$#u*  t`F,!kkB43;H1^EeW8cs6_7blhwM>y!=wZ3yxBToGpCVUt,_CZ.' );
define( 'NONCE_KEY',        '}JH>Pg1LoZ:*|kU5QIuN?f>}g8N;+YS8E{w:yY9J,TDk)fu7H?-Q06@L!748<4P.' );
define( 'AUTH_SALT',        's}l>aWPMoh=Pkd$HWd?W[8~D2kf91}ZJZ@is[!!<6QBXFo@b#nltwte+le9Cqrd]' );
define( 'SECURE_AUTH_SALT', ']5ieAW(+J>[uVf;k|yd3o)E* 8$9 l!8;xPxv.;) @ADK5Q0myhB Zk#G%[xQCq$' );
define( 'LOGGED_IN_SALT',   '-/3``JIKG.JL,0:Ud~:XC7gI&p.|)d8*fa~,PW87Y#Q&Hb>gP{waSd:<L[bZ&,xT' );
define( 'NONCE_SALT',       '7nAwp:|fs7 )s9o1iwTE&[Bg}9ptED+Ms;v.FJ}_<-RIO#kCEeg6^A|U>Em#w2)3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
