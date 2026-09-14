<?php
session_start();
const DB_HOST='localhost'; const DB_NAME='nesere_db'; const DB_USER='root'; const DB_PASS='';
function db(){ static $pdo; if(!$pdo){$pdo=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);} return $pdo; }
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function logged(){return isset($_SESSION['user']);}
function admin(){return logged() && $_SESSION['user']['role']==='admin';}
function customer(){return logged() && $_SESSION['user']['role']==='customer';}
function require_login(){if(!logged()){header('Location: login.php');exit;}}
function require_admin(){if(!admin()){header('Location: ../login.php');exit;}}
function csrf(){if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf'];}
function check_csrf(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Invalid request.');}}
function flash($type,$msg){$_SESSION['flash']=[$type,$msg];}
function show_flash(){if(isset($_SESSION['flash'])){$f=$_SESSION['flash'];unset($_SESSION['flash']);echo '<div class="alert alert-'.e($f[0]).'">'.e($f[1]).'</div>';}}
?>
