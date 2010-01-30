<?php

	$_base = dirname(__FILE__).'/';
	// Load dependancies
	require_once($_base.'pur/src/pur.inc.php');
	require_once($_base.'pop_cache/src/pop_cache.inc.php');
	require_once($_base.'pop_loader/src/PopLoader.php');
	
	if(isset($pop['base'])) $_appBase = $pop['base'];
	else if(isset($pop['pop_loader']['base'])) $_appBase = $pop['pop_loader']['base'];
	else if(isset($pop['pop_config']['base'])) $_appBase = $pop['pop_config']['base'];
	else $_appBase = $_base.'../';
	
	// Provided configuration
	if(!isset($pop)) $pop = array();

	// Framework configuration
	$pop = PurArray::merge(array(
		'pop_cache'=>array(
			'stores'=>array(
				'PopCacheFile'=>array(
					'base'=>$_appBase,
				)
			)
		),
		'pop_loader'=>array(
			'base'=>$_appBase,
			'repositories'=>$_base.'/.',
		),
		'pop_config'=>array(
			'base'=>$_appBase,
			'modes'=>array('local')
		),
		'pop_error'=>array(
		),
		'pop_environment'=>array(
		)
	),$pop);

	// Bootstraping
	// Pop Compat is loaded before Pop Config which need json functions
	// Pop Compat is loader after PopLoader so we don't require it if we don't need it
	$_popCache = new PopCache($pop['pop_cache']);
	$_popLoader = new PopLoader($pop['pop_loader'],$_popCache);
	$_popCompat = version_compare(phpversion(),'5.2','<')?new PopCompat():null;
	$_popConfig = new PopConfig($pop,$_popCache);
	$_popEnvironment = new PopEnvironment($pop['pop_environment'],$_popLoader);
	$_popCache->configure($_popConfig->pop_cache);
	
	$pop = new Padrino(
		$_popCache,
		$_popLoader,
		$_popConfig,
		$_popEnvironment,
		'pop_error',
		'pop_log',
		version_compare(phpversion(),'5.2','<')?'pop_compat':null
	);
	
	// Clear context
	unset($_base);
	unset($_popCache);
	unset($_popLoader);
	unset($_popCompat);
	unset($_popConfig);
	unset($_popEnvironment);
	
	if(isset($_SERVER['argv'])&&in_array('console',$_SERVER['argv'])){
		if($_SERVER['argv'][1]=='console'){
			$pop->pop_install_console->init();
		}else{
			$pop->pop_install_console->exec();
		}
	}else{
		// Include mode
		// Available in current context and as a returned value
		return $pop;
	}

?>