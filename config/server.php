<?php
const server = "localhost";
const db = "prestamos";
const user = "root";
const pass = "";

//conexion a la base de datos
const sgdb = "mysql:host=".server.";dbname=".db;
//echo sgdb;

//para encriptacion de contraseñas y registros en la bd
const method = "AES-256-CBC";
const secret_key = '$prestamos2024';
const secret_iv = '201627';
?>