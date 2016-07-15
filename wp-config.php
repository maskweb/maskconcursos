<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'u368997073_conc');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'u368997073_anath');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'WX5wog2ZSI');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'mysql.hostinger.es');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'Q;CHFp/%9E{vmF0Wm;`hHV=zDC(B5gItQK^%mtgJ6OfXp^HomP7{~3jt {T`Tl$Q');
define('SECURE_AUTH_KEY', '<0hVgIInDE>7Y`RvFqx9T@+!D4A}dwi{=D_$Vi?|1S ,y4KPx/@FGK%`xR=V?)5;');
define('LOGGED_IN_KEY', '^qWHG&P&#G5%<>:,AD.x$!F^It`U_q`}xei{8lK4<lf?I,lpR!|`3Q*-HDZ?m+A`');
define('NONCE_KEY', 'PArc =A=^{lyTTu@98w!j6vJe*Wy;P6{R.D0/YK`K8(<DFjHA}{&{T&197Az#cwC');
define('AUTH_SALT', 'b3Ml16b1gd,.,*-X-$`:MHP9%Jiii@_vX77;WDC/4/b&%v3D>S$QRjrt7i}wFxba');
define('SECURE_AUTH_SALT', ')&*9GMg b9-P[SuBK^NKEf21z&2fXQ.=`q)=<`W}|)7?uAatM6d?8>)UXffUCT6}');
define('LOGGED_IN_SALT', 'rlR)JaZ`h^?We<fB8e4n[MIX@,SnLtB56@<:zoC[W>PSV:*T!Pr/;JSP&Pj4fUu!');
define('NONCE_SALT', '/j(qZ<UN]/LKn-X$FRM5NNo:|Xroi]Efmx%1p+C/Cwt]vUgQ-u7E+TEW+Jo$AI+]');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', 'es_ES');
