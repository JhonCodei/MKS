<?php
ob_start();

ini_set("display_errors", 1);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 30000);

error_reporting(E_ALL);
date_default_timezone_set('America/Lima');

session_start();

# Database Config
define('DB_DSN', 'mysql');              # Data Source Name.
define('DB_HOST', '127.0.0.1');         # Host  Database.
define('DB_PORT', '3306');         					# Host  PUERTO.
define('DB_USER', 'root');              # User Database.
#define('DB_NAME', 'mks_unidos_db');    # Name Database.
#define('DB_PASS', 'syst3m2090');       # Password Database.

define('DB_NAME', 'mks_restore_13');    # Name Database.
define('DB_PASS', 'lenovo19');       			# Password Database.

# Correo Config

define('EMAIL_USER', 'emailserverti@gmail.com');      //Usuario [Email_envio]..
define('EMAIL_PASSWORD', 'emailserverti2017');       //Password [Email_envio].
define('EMAIL_SMTP', 'smtp.gmail.com');                   //SMTP Del proveedor.
define('EMAIL_PORT', 465);                                      //Puerto del servidor Email.
define('EMAIL_MEDIO', 'ssl');                                    //Seguridad Medio Servidor.

# URL CONTEND

require_once 'helpers/complements/Urls.php';

define( 'APP_LOCAL', server_url());  //Si es necesario habilitar esta opcion para desarrollo local.
define( 'APP_URL', web_index());   // URL Base de la web(dominio)[por lo general se usa este define cuando ya este alojado.].
define( 'APP_INDEX', web_index()); // URL index o redireccionamiento [opcional su habilitacion].
define( 'TITLE', title_());
// define( 'APP_COPY', 'Copyright &copy; '  .  date('Y', time()) . ' MksUnidos Software. ' ); // Copyright del sistema.
define( 'APP_COPY', 'Copyright &copy; 2018 MksUnidos Software. ' ); // Copyright del sistema.
define( 'APP_ICON', icon_web()); //Icono de la web.

#  APP_URLs

define('APP_VIEWS', 'app/views/');  //Direccion de las vistas
define('APP_CONTROLLERS', 'app/controllers/');  //Direccion de los controladores
define('APP_MODELS', 'app/models/');  //Direccion de los modelos

define('APP_PUBLIC', web_path());  //Direccion de los modelos

# Required

require_once 'database.php'; //Conexion a la base de datos.

isset($_SESSION['user_id']) ? require_once 'helpers/complements/Users.php' : NULL;  // User Sessions..

## LOAD FUNCTION

require_once 'helpers/libraries/Email/PHPMailerAutoload.php';
require_once 'helpers/libraries/ExcelAPI/PHPExcel.php';
require_once 'helpers/libraries/CSV/parsecsv.lib.php';

require_once 'helpers/complements/func_helpers.php';
#require_once 'helpers/complements/Mailer.php';
require_once 'helpers/complements/Encrypt.php';
require_once 'helpers/complements/PDOException.php';
require_once 'helpers/complements/fn_sql.php';
#require_once 'helpers/complements/Menu.php';
require_once 'helpers/complements/Session.php';
ob_end_flush();