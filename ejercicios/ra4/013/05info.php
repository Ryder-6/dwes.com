<?php

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/jwt/include_jwt.php');
require_once('include.php');

$usuario = comprobarJWT();

$fichero = filter_input(INPUT_POST, 'fichero', FILTER_SANITIZE_SPECIAL_CHARS);
