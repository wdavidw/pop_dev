<?php

	$_base = dirname(__FILE__).'/';
	// Load dependancies
	require_once($_base.'pur/src/pur.inc.php');
	require_once($_base.'pop_loader/src/PopLoader.php');
	
	if(isset($pop['pop_loader']['base'])) $_appBase = $pop['pop_loader']['base'];
	else if(isset($pop['pop_config']['base'])) $_appBase = $pop['pop_config']['base'];
	
	// Provided configuration
	if(!isset($pop)) $pop = array();
	
	// Framework configuration
	$pop = PurArray::merge(array(
		'pop_loader'=>array(
			'base'=>$_appBase,
			'repositories'=>$_base.'/.'
		),
		'pop_config'=>array(
			'base'=>$_appBase
		),
		'pop_error'=>array(
			'stdout'=>true
		),
		'pop_environment'=>array(
		)
	),$pop);

	// Bootstraping
	$_popLoader = new PopLoader($pop['pop_loader']);
	$_popConfig = new PopConfig($pop);
	$_popEnvironment = new PopEnvironment($pop['pop_environment'],$_popLoader);
	$pop = new Padrino(
		$_popLoader,
		$_popConfig,
		$_popEnvironment,
		'pop_error',
		version_compare(phpversion(),'5.2','<')?'pop_compat':null);
	
	// Clear context
	unset($_base);
	unset($_popLoader);
	unset($_popConfig);
	unset($_popEnvironment);
	
	//if(__FILE__==$_SERVER["PWD"].'/'.$_SERVER["PHP_SELF"]){
	//if($_SERVER["PHP_SELF"]=='pop.php'){
	if(isset($_SERVER['argv'])&&in_array('console',$_SERVER['argv'])){
		// console mode
		$pop->pop_install->console();
	}else{
		// Include mode
		// Available in current context and as a returned value
		return $pop;
	}

?>