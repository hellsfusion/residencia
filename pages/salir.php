<?php 
include __DIR__.'/../php/funciones.php';

switch ($_SESSION['admin']) {
 case '0':
  logout("loginApartamento");
  break;
 default:
  logout();
  break;
}
?>
