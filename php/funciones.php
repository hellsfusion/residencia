<?php
session_start();
include __DIR__ . '/conn.php';

// fecha inicio sistema
$fechaInicioSistema = "2025-08-01";

// variables globales
$Base = 'https://residenciasflorida.page.gd/';
$menu = [
 ['nombre' => 'Inicio', 'url' => 'dashboard', 'icon' => 'fas fa-home'],
 ['nombre' => 'Calendario', 'url' => 'calendario', 'icon' => 'far fa-calendar-alt'],
 ['nombre' => 'Apartamentos', 'url' => 'aptos', 'icon' => 'far fa-building'],
 ['nombre' => 'Cuotas Extra', 'url' => 'cuotasExtra', 'icon' => 'fas fa-file-invoice'],
 ['nombre' => 'Ingresos', 'url' => 'ingresos', 'icon' => 'fas fa-file-invoice-dollar'],
 ['nombre' => 'Gastos', 'url' => 'gastos', 'icon' => 'fas fa-donate'],
 ['nombre' => 'Recibos', 'url' => 'recibos', 'icon' => 'fas fa-file-invoice-dollar'],
 ['nombre' => 'Recibos de Pago', 'url' => 'recibos_ingresos', 'icon' => 'fas fa-file-invoice-dollar'],
 ['nombre' => 'Recibos de Gastos', 'url' => 'recibos_gastos', 'icon' => 'fas fa-file-invoice-dollar'],
 ['nombre' => 'Reportes de Pago', 'url' => 'reportes_pago', 'icon' => 'fas fa-file-invoice-dollar'],
 // ['nombre' => 'Conserjeria', 'url' => 'conserjeria', 'icon' => 'fas fa-broom'],
 ['nombre' => 'Documentos Subidos', 'url' => 'documentos', 'icon' => 'far fa-file-word'],
 ['nombre' => 'Documentos Generados', 'url' => 'documentos_generados_plantillas', 'icon' => 'far fa-file-word'],
 ['nombre' => 'Contraseñas', 'url' => 'claves', 'icon' => 'fas fa-unlock-alt'],


 // ['nombre' => 'Reportes', 'url' => 'reportes', 'icon' => 'far fa-file-pdf'],
 ['nombre' => 'Maestros [interno]', 'url' => 'maestros', 'icon' => 'fas fa-users-cog'],
 ['nombre' => 'Salir', 'url' => 'salir', 'icon' => 'fas fa-sign-out-alt text-danger'],
];

// menu apto
if ($_SESSION['admin'] == 0) {
 $menu = [
  ['nombre' => 'Inicio', 'url' => 'apartamento', 'icon' => 'fas fa-home'],
  ['nombre' => 'Datos Propietario / Beneficiario', 'url' => 'datos_propietario', 'icon' => 'fas fa-user'],
  ['nombre' => 'Cuotas Extra', 'url' => 'cuotas_apartamento', 'icon' => 'fas fa-file-invoice-dollar'],
  ['nombre' => 'Recibos de Pago', 'url' => 'recibos_apartamento', 'icon' => 'fas fa-file-invoice-dollar'],
  ['nombre' => 'Cambiar Contraseña', 'url' => 'cambiar_contrasena', 'icon' => 'fas fa-key'],
  ['nombre' => 'Salir', 'url' => 'salir', 'icon' => 'fas fa-sign-out-alt text-danger'],
 ];
}

// 22 06 2026
$arrayVariablesPlantillas = [
 '[nombre_condominio]'    => '[nombre_condominio]',
 '[direccion_condominio]' => '[direccion_condominio]',
 // '[ciudad_estado]'        => '[ciudad_estado]',

 '[fecha]'                => '[fecha]',

 '[nombre_propietario]'   => '[nombre_propietario]',
 '[cedula_propietario]'   => '[cedula_propietario]',
 '[apto]'                 => '[apto]',

 '[nombre_usuario]'       => '[nombre_usuario]',
 '[cargo_usuario]'        => '[cargo_usuario]',
 '[firma_usuario]'        => '[firma_usuario]',
];

function remplazarVariablesPlantilla($contenido, $idUsuario = null, $idEdificio = null, $idApto = null)
{
 global $conn;
 global $arrayVariablesPlantillas;
 // valores de usuario
 if ($idUsuario !== null) {
  $queryUsuario = mysqli_query($conn, "SELECT * FROM users WHERE id = '$idUsuario'");
  $rowUsuario = mysqli_fetch_assoc($queryUsuario);
  $arrayVariablesPlantillas["[nombre_usuario]"] = $rowUsuario["name"];
  $arrayVariablesPlantillas["[cargo_usuario]"] = $rowUsuario["cargo"];
  $arrayVariablesPlantillas["[firma_usuario]"] = $rowUsuario["firma"];
 }

 // fecha actual
 $fecha = date("d/m/Y");
 $arrayVariablesPlantillas["[fecha]"] = $fecha;

 // valores edificio
 if ($idEdificio !== null) {
  $queryUsuario = mysqli_query($conn, "SELECT * FROM edificios WHERE id = '$idEdificio'");
  $rowUsuario = mysqli_fetch_assoc($queryUsuario);
  $arrayVariablesPlantillas["[nombre_condominio]"] = $rowUsuario["nombre"].', '. $rowUsuario["conjunto"];
  $arrayVariablesPlantillas["[direccion_condominio]"] = $rowUsuario["direccion"];
 }

 // valores propietario
 if ($idApto !== null) {
  $queryUsuario = mysqli_query($conn, "SELECT * FROM apartamentos WHERE id = '$idApto'");
  $rowUsuario = mysqli_fetch_assoc($queryUsuario);
  $arrayVariablesPlantillas["[nombre_propietario]"] = $rowUsuario["propietario"];
  $arrayVariablesPlantillas["[cedula_propietario]"] = 'XXXXX';
  $arrayVariablesPlantillas["[apto]"] = $rowUsuario["apartamento"];  
 }

 foreach ($arrayVariablesPlantillas as $key => $value) {
  $arrayVariablesPlantillas[$key] = strtoupper($value);
 }




 // remplazar valores
 foreach ($arrayVariablesPlantillas as $variable => $valor) {
  $contenido = str_replace($variable, $valor, $contenido);
 }
 return $contenido;
}



$salt = substr(md5("ad$%RQFSDGwqe&*U"), 0, 10);
$defaultPass = md5("1234" . $salt);
// var_dump($defaultPass);

// meses
// $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$meses = array(1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre');

// ---------------------------
// función login
// ---------------------------
function login($user, $pass)
{
 global $conn;

 $sql = "SELECT * FROM users WHERE username = '$user' AND pass = '$pass'";
 $result = mysqli_query($conn, $sql);
 if (mysqli_num_rows($result) > 0) {
  // crear sesion
  session_start();
  $row = mysqli_fetch_assoc($result);
  $_SESSION['id'] = $row['id'];
  $_SESSION['username'] = $row['username'];
  $_SESSION['name'] = $row['name'];
  $_SESSION['pass'] = $row['pass'];
  $_SESSION['admin'] = 1;
  // redirect to dashboard
  echo "Paso";
  header("Location: dashboard");
 } else {
  echo "no Paso";
  header("Location: index");
 }
 exit();
}

// ---------------------------
// función login apartamento
// ---------------------------
function loginApartamento($idEdificio, $pass, $idApartamento)
{
 global $conn;
 global $salt;
 $pass = md5($pass . $salt);
 $sql = "SELECT * FROM apartamentos WHERE idEdificio = '$idEdificio' AND pass = '$pass' AND id = '$idApartamento'";
 $result = mysqli_query($conn, $sql);
 if (mysqli_num_rows($result) > 0) {
  // crear sesion
  session_start();
  $row = mysqli_fetch_assoc($result);
  $_SESSION['id'] = $row['id'];
  $_SESSION['username'] = $row['username'];
  $_SESSION['name'] = $row['name'];
  $_SESSION['pass'] = $row['pass'];
  $_SESSION['admin'] = 0;
  // redirect to dashboard
  echo "Paso";
  header("Location: apartamento");
 } else {
  echo "no Paso";
  header("Location: loginApartamento");
 }
 exit();
}

// ---------------------------
// función logout
// ---------------------------
function logout($ruta = "login")
{
 session_start();
 session_destroy();
 header("Location: $ruta");
 exit();
}

// ---------------------------
// función verificar si el usuario está logueado
// ---------------------------
function isLoggedIn()
{

 if (isset($_SESSION['id'])) {
  return true;
 } else {
  return false;
 }
}

// function funcionMaster($filtro, $campoFiltrar, $campoImprimir, $tabla, $config = null)
// {
// 	global $conn;
// 	try {
// 		// $config resive un arreglo con la configuracion que quieran usar
// 		// tu puedes poner lo que quieras hacer, la meta la pones tu :v
// 		if ($config && !empty($config)) { // valores para configurar
// 			$arrayAccept = [ // funciones a utilizar
// 				"like",  // deja filtrar valores con un like teniendo en cuenta que solo va a devolver un valor
// 				"order", // para indicar el orden de la consulta for example: order => "id asc"
// 				"returnConsult", // en el caso de que no se ejecute la consulta devuelve la consulta para validación
// 				"notResult" // para personalizar el mensaje en el caso de que este vacio
// 			];
// 			$arrayConfig = [];
// 			foreach ($config as $key => $value) {
// 				if (!in_array($key, $arrayAccept)) { // si no se envia un valor que no este configurado lo devuelve para que sepa que no esta configurado
// 					return "Invalid configuration parameter $key"; // en ingles pa que sepa que ta mal
// 				} else {
// 					$arrayConfig[$key] = $value;
// 				}
// 			}
// 			$order = ($arrayConfig["order"] && !empty($arrayConfig["order"]) ? $arrayConfig["order"] : "");
// 			$condicion = ($arrayConfig["like"] && ($arrayConfig["like"] == true) ? "$campoFiltrar like '%$filtro%'" : "$campoFiltrar = '$filtro'");
// 			$return = ($arrayConfig["returnConsult"] && ($arrayConfig["returnConsult"] == true) ? 1 : 0);
// 		} else { // valores por defecto
// 			$arrayConfig = null;
// 			$order = "";
// 			$condicion = "$campoFiltrar = '$filtro'";
// 			$return = 0;
// 		}
// 		$query = mysqli_query($conn, "SELECT $campoImprimir FROM $tabla where $condicion " . (!empty($order) ? "order by $order" : "") . " limit 1");
// 		if ($query) {
// 			$row = mysqli_fetch_assoc($query);
// 			if (!empty(mysqli_num_rows($query))) {
// 				return $row[$campoImprimir];
// 			} else {
// 				if (!empty($return)) {
// 					return "SELECT $campoImprimir FROM $tabla where $condicion " . (!empty($order) ? "order by $order" : "") . " limit 1";
// 				} else {
// 					// return ($arrayConfig["notResult"] ? $arrayConfig["notResult"] : "not result");
// 					return ($arrayConfig["notResult"] ? $arrayConfig["notResult"] : ""); // jaja sorry
// 				}
// 			}
// 		}
// 	} catch (\Throwable $th) {
// 		return "";
// 	}
// }
function funcionMaster($filtro, $campoFiltrar, $campoImprimir, $tabla, $config = null)
{
 global $conn;
 // $config resive un arreglo con la configuracion que quieran usar
 // tu puedes poner lo que quieras hacer, la meta la pones tu :v
 if ($config && !empty($config)) { // valores para configurar
  $arrayAccept = [ // funciones a utilizar
   "like",  // deja filtrar valores con un like teniendo en cuenta que solo va a devolver un valor
   "order", // para indicar el orden de la consulta for example: order => "id asc"
   "returnConsult", // en el caso de que no se ejecute la consulta devuelve la consulta para validación
   "notResult" // para personalizar el mensaje en el caso de que este vacio
  ];
  $arrayConfig = [];
  foreach ($config as $key => $value) {
   if (!in_array($key, $arrayAccept)) { // si no se envia un valor que no este configurado lo devuelve para que sepa que no esta configurado
    return "Invalid configuration parameter $key"; // en ingles pa que sepa que ta mal
   } else {
    $arrayConfig[$key] = $value;
   }
  }
  $order = ($arrayConfig["order"] && !empty($arrayConfig["order"]) ? $arrayConfig["order"] : "");
  $condicion = ($arrayConfig["like"] && ($arrayConfig["like"] == true) ? "$campoFiltrar like '%$filtro%'" : "$campoFiltrar = '$filtro'");
  $return = ($arrayConfig["returnConsult"] && ($arrayConfig["returnConsult"] == true) ? 1 : 0);
 } else { // valores por defecto
  $order = "";
  $condicion = "$campoFiltrar = '$filtro'";
  $return = 0;
 }


 $queryCheckTable = mysqli_query($conn, "SHOW TABLES LIKE '$tabla'");
 if (mysqli_num_rows($queryCheckTable) > 0 || $tabla == "INFORMATION_SCHEMA.COLUMNS") {
  // Si la tabla existe, realiza la consulta principal
  // var_dump("SELECT $campoImprimir FROM $tabla WHERE $condicion " . (!empty($order) ? "ORDER BY $order" : "") . " LIMIT 1");
  $query = mysqli_query($conn, "SELECT $campoImprimir FROM $tabla WHERE $condicion " . (!empty($order) ? "ORDER BY $order" : "") . " LIMIT 1") or die(mysqli_error($conn));
  if ($query) {
   $row = mysqli_fetch_array($query);
   if (!empty(mysqli_num_rows($query))) {
    return $row[$campoImprimir];
   } else {
    if (!empty($return)) {
     return 0;
    } else {
     return 0;
    }
   }
  }
 } else {
  // Si la tabla no existe, retorna 0
  return 0;
 }
}

function selectMaster($whereselect, $campoValue, $campoTexto, $tabla, $selected = null)
{
 global $conn;

 $arraytexto = explode(",", $campoTexto);
 $arrayvalue = explode(",", $campoValue);

 $query = mysqli_query($conn, "SELECT * FROM $tabla " . $whereselect);
 $nrowl = mysqli_num_rows($query);
 $textoprint = "";
 $valueprint = "";
 $text = "";
 while ($row = mysqli_fetch_assoc($query)) {
  for ($i = 0; $i < count($arraytexto); $i++) {
   $textoprint .= $row[$arraytexto[$i]] . ' - ';
  }
  $textoprint = trim($textoprint, ' - ');

  for ($k = 0; $k < count($arrayvalue); $k++) {
   $valueprint .= $row[$arrayvalue[$k]] . ' - ';
  }
  $valueprint = trim($valueprint, ' - ');


  $text .= "<option value='$valueprint' " . ($selected == $valueprint ? "selected" : "") . ">$textoprint</option>";
  $textoprint = "";
  $valueprint = "";
 }

 return  utf8_encode($text);
}

// function getDolarBCV()
// {
// 	try {
// 		$url = 'https://www.bcv.org.ve/';

// 		// Configurar cURL
// 		$ch = curl_init();

// 		curl_setopt_array($ch, [
// 			CURLOPT_URL => $url,
// 			CURLOPT_RETURNTRANSFER => true,
// 			CURLOPT_FOLLOWLOCATION => true,
// 			CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; PHP Script)',
// 			CURLOPT_SSL_VERIFYPEER => false,
// 			CURLOPT_SSL_VERIFYHOST => false,
// 			CURLOPT_TIMEOUT => 30,
// 			CURLOPT_CONNECTTIMEOUT => 10,
// 			CURLOPT_FAILONERROR => true
// 		]);

// 		// Realizar la petición
// 		$html = curl_exec($ch);
// 		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// 		$error = curl_error($ch);

// 		curl_close($ch);

// 		// Verificar si la petición fue exitosa
// 		if ($html === false || $httpCode !== 200) {
// 			error_log("Error cURL: " . $error . " | HTTP Code: " . $httpCode);
// 			return 0;
// 		}

// 		// Primer método: expresión regular para el formato actualizado
// 		$regex = '/<div id="dolar"[^>]*>[\s\S]*?<strong>\s*([\d,]+)\s*<\/strong>/';
// 		preg_match($regex, $html, $match);

// 		if ($match && count($match) >= 2) {
// 			$value = floatval(str_replace(',', '.', trim($match[1])));
// 			if ($value > 0) {
// 				return $value;
// 			}
// 		}

// 		// Segundo método: buscar elemento con clase específica
// 		$classRegex = '/<div class="views-field views-field-field-tasa-compra"[^>]*>[\s\S]*?<span class="field-content">\s*([\d,]+)\s*<\/span>/i';
// 		preg_match_all($classRegex, $html, $classMatches, PREG_SET_ORDER);

// 		if ($classMatches && count($classMatches) >= 2) {
// 			$secondValue = floatval(str_replace(',', '.', trim($classMatches[1][1])));
// 			if ($secondValue > 0) {
// 				return $secondValue;
// 			}
// 		}

// 		// Tercer método alternativo: buscar en el contenido JSON que pueda tener la página
// 		$jsonRegex = '/USD\s*[\/\-\:]\s*[\$\s]*([\d,]+)/i';
// 		preg_match($jsonRegex, $html, $jsonMatch);

// 		if ($jsonMatch && count($jsonMatch) >= 2) {
// 			$jsonValue = floatval(str_replace(',', '.', trim($jsonMatch[1])));
// 			if ($jsonValue > 0) {
// 				return $jsonValue;
// 			}
// 		}

// 		error_log("No se pudo encontrar el valor del dólar en el HTML");
// 		return 0;
// 	} catch (Exception $error) {
// 		error_log("Exception en getDolarBCV: " . $error->getMessage());
// 		return 0;
// 	}
// }

// Ejemplo de uso:
// $dolar = getDolarBCV();
// echo "Dólar BCV: " . ($dolar > 0 ? $dolar : "No disponible");


function getDolarBCV()
{
 try {
  $url = 'https://www.bcv.org.ve/';

  // Configurar cURL
  $ch = curl_init();

  curl_setopt_array($ch, [
   CURLOPT_URL => $url,
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_FOLLOWLOCATION => true,
   // Es vital usar un User-Agent real para evitar bloqueos del servidor (403 Forbidden)
   CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
   CURLOPT_SSL_VERIFYPEER => false,
   CURLOPT_SSL_VERIFYHOST => false,
   CURLOPT_TIMEOUT => 30,
   CURLOPT_CONNECTTIMEOUT => 10,
   CURLOPT_FAILONERROR => true
  ]);

  // Realizar la petición
  $html = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $error = curl_error($ch);

  curl_close($ch);

  // Verificar si la petición fue exitosa
  if ($html === false || $httpCode !== 200) {
   error_log("Error cURL: " . $error . " | HTTP Code: " . $httpCode);
   return 0;
  }

  // --- MÉTODO 1: Busca el contenedor #dolar y el <strong class="strong-tb"> ---
  $regex = '/<div\s+id="dolar"[\s\S]*?<strong\s+class="strong-tb"[^>]*>\s*([\d,]+)\s*<\/strong>/i';
  if (preg_match($regex, $html, $match)) {
   if (isset($match[1])) {
    $value = floatval(str_replace(',', '.', trim($match[1])));
    if ($value > 0) {
     return $value;
    }
   }
  }

  // --- MÉTODO 2 (RESPALDO): Por si cambia pero mantiene la clase 'centrado' ---
  $backupRegex = '/<div\s+id="dolar"[\s\S]*?class="[^"]*centrado[^"]*"[^>]*>\s*<strong[^>]*>\s*([\d,]+)\s*<\/strong>/i';
  if (preg_match($backupRegex, $html, $backupMatch)) {
   if (isset($backupMatch[1])) {
    $backupValue = floatval(str_replace(',', '.', trim($backupMatch[1])));
    if ($backupValue > 0) {
     return $backupValue;
    }
   }
  }

  error_log("No se pudo encontrar el valor del dólar en el HTML del BCV con las nuevas estructuras.");
  return 0;
 } catch (Exception $error) {
  error_log("Exception en getDolarBCV: " . $error->getMessage());
  return 0;
 }
}

function numero_letra($valor, $moneda)
{
 $desc_moneda = $moneda; //moneda
 $sep = "con"; //separador ej con, coma, y
 $desc_decimal = "/100"; //decimales ej centavos, centimos

 $arr = explode(".", $valor);
 $entero = $arr[0];
 if (isset($arr[1])) {
  $decimos = strlen($arr[1]) == 1 ? $arr[1] . '0' : $arr[1];
 }

 $fmt = new \NumberFormatter('es', \NumberFormatter::SPELLOUT);
 if (is_array($arr)) {
  $num_word = ($arr[0] >= 1000000) ? "{$fmt->format($entero)} de $desc_moneda" : "{$fmt->format($entero)} $desc_moneda";
  if (isset($decimos) && $decimos > 0) {
   $num_word .= " $sep  {$fmt->format($decimos)} $desc_decimal";
  }
 }

 return $num_word;
}

function generarQR($data)
{
 $url = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($data);
 $qrImage = file_get_contents($url);
 if ($qrImage !== false) {
  return 'data:image/png;base64,' . base64_encode($qrImage);
 } else {
  return null; // Manejar error si no se pudo generar el QR
 }
}

function calcularMesesEntreFechas($fechaInicio, $fechaFin)
{
 // Creamos los objetos DateTime a partir de las fechas
 $inicio = new DateTime($fechaInicio);
 $fin = new DateTime($fechaFin);

 // Calculamos la diferencia
 $diferencia = $inicio->diff($fin);

 // Convertimos los años transcurridos a meses y sumamos los meses restantes
 $totalMeses = ($diferencia->y * 12) + $diferencia->m;

 $totalMeses = $totalMeses + 1;

 return $totalMeses;
}
