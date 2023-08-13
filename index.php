<?php
/* Mostrar errores */
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', "C:/xampp/htdocs/api/php_error_log");
/*Encabezada de las solicitudes*/
/*CORS*/
header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
/*--- Requerimientos Clases o librerías*/
require_once "models/MySqlConnect.php";
/***--- Agregar todos los modelos*/
require_once  "models/PlanesModel.php";
require_once  "models/ActividadesGrupalesModel.php";
require_once  "models/RutinasModel.php";
require_once "models/ServiciosModel.php";
require_once "models/RutinaEjerciciosModel.php";
require_once "models/EjerciciosImagenesModel.php";
require_once "models/EjerciciosModel.php";
require_once "models/UsuarioModel.php";
require_once "models/ActividadesReservasModel.php";
require_once "models/HistorialRutinasModel.php";
/***--- Agregar todos los controladores*/
require_once "controllers/PlanesController.php";
require_once "controllers/ActividadesGrupalesController.php";
require_once "controllers/RutinasController.php";
require_once "controllers/ServiciosController.php";
require_once "controllers/RutinaEjerciciosController.php";
require_once "controllers/EjercicioImagenesController.php";
require_once "controllers/EjercicioController.php";
require_once "controllers/UsuarioController.php";
require_once "controllers/ActividadesReservasController.php";
require_once "controllers/HistorialRutinasController.php";
//Enrutador
//RoutesController.php
require_once "controllers/RoutesController.php";
$index = new RoutesController();
$index->index();
