<?php

	$dirBase = dirname(__FILE__).'/';
	// Load dependancies
	require_once($dirBase.'pur/src/pur.inc.php');
	require_once($dirBase.'padrino/src/Padrino.php');
	// Create default configuration
	// Merge with provided configuration if any
	$pop = PurArray::merge(array(
		'base'=>$dirBase,
		'repositories'=>array(
			$dirBase,
		),
		'pop_error'=>array('stdout'=>true),
		'load_on_startup'=>array('pop_error','pop_loader'),
	),isset($pop)&&is_array($pop)?$pop:array());
	// Add pop_compat on startup if PHP version lower than 5.2
	if(version_compare(phpversion(),'5.2','<')){
		$pop['load_on_startup'][] = 'pop_compat';
	}
	// Clear unused variables and leave padrino in memory
	unset($dirBase);
	// Initialize pop
	$pop = new Padrino($pop);
	//if(__FILE__==$_SERVER["PWD"].'/'.$_SERVER["PHP_SELF"]){
	//if($_SERVER["PHP_SELF"]=='pop.php'){
	if(count($_SERVER['argv'])>1){
		// console mode
		$pop->pop_install->{!empty($_SERVER['argv'][1])?$_SERVER['argv'][1]:'console'}();
	}else{
		// Include mode
		// Available in current context and as a returned value
		return $pop;
	}

?>