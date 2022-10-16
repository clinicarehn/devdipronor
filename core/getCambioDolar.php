<?php
	$peticionAjax = true;
	require_once "configGenerales.php";
	require_once "mainModel.php";
	
	$insMainModel = new mainModel();
	
	$fecha = date("Y-m-d");
	$data = htmlentities(file_get_contents("https://www.bancopromerica.com/banca-de-empresas/banca-internacional/mesa-de-cambio/"));
	echo $data;
	if (preg_match('|<h2 style="margin: 12px 0 0 0;">(.*?)</h2>|is' , $data , $cap )){
		echo "UF ".$cap[1];
	}else{
		echo 'nada';
	}

	// $profile_data = htmlentities(file_get_contents("https://www.bancopromerica.com/banca-de-empresas/banca-internacional/mesa-de-cambio/"));
	
	// $sep1 = explode('string(15899)', $profile_data,2); 
	// //$set2 = explode('tipoCambioCompra = parseFloat(tipoCambioCompra).toFixed(4); document.getElementById("tipoCambioCompra").textContent = tipoCambioCompra; var tipoCambioVenta = 24.7931; tipoCambioVenta = parseFloat(tipoCambioVenta).toFixed(4); document.getElementById("tipoCambioVenta").textContent = tipoCambioVenta; </script> ', $sep1[1]); 
	// $rest = substr("tipoCambioCompra=", 10, 12); 
	// echo '<hr>'.$rest;
	// //echo $profile_data;
	