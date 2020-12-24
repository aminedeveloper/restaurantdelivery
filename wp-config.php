<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'restaurantdelivery' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'y1k.0=ywGiA_Qw)N4US s!&%y{GvNSwu9LP/#OdpYO?|PwMMP>^qMlF~g~yki`x@' );
define( 'SECURE_AUTH_KEY',  'DFc%IRYU8pp}S3<KcOM*a&_Ko;pyAea7!GBPD)^sHLp_ANc]]))8DxHb+c+h^Xs;' );
define( 'LOGGED_IN_KEY',    '@1O42bBeUgNM@c~iHj#N8F,:[$7VALsgqOUbdko`_BP(1ybF-Na!or+c4%I/^FR4' );
define( 'NONCE_KEY',        'w/fijqrsC1IPZU>{aNZx{!zy-%:*Z`L|+7:9sORhM_{N -g-Ho))OI<;lgz{:+$N' );
define( 'AUTH_SALT',        'pnOA-DNQRE1|!68p V7N;+,_n5h/2I:C&2yAH^&P9*~yZ0##R[QaLk$7xB31m=V0' );
define( 'SECURE_AUTH_SALT', 'xRjT%X;R&?Eaden#s>NK<&3E@m :iD~StW<ijnYQMcl6Lt`)pGi&|Y:<(ZUVs&wF' );
define( 'LOGGED_IN_SALT',   ',y+vSum<;$.>-QK-{v?=Kv.(1Z~#^A>ZcNt^9{?4SL2!Q4ZhRpktR/gKD)&2vimo' );
define( 'NONCE_SALT',       'k)B]LedQS}Ca +B~jNQ)&IVzSb2X~- E;(#%`|J3b-vDP|<z,^^gX*M9=u_PTbXL' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
