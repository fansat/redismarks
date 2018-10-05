<?php
/*
 *访问频率限制
 *10秒之内不能超过6次访问
 */
$redis = new Redis();
$maxLimit = 5;
$redis_key = 'request_limit';
$redis->connect('127.0.0.1', 63791, 1);  
$data =$redis->get($redis_key); 
if($data){
	list($count,$time)= explode('_',$data); 
	if($time+10 > time() && $maxLimit < $count){ 
		die('limit firewill work'); 
	}else{
		if($time+10 < time() || ($maxLimit < $count)){
			$count =1;
			$value = '1_'.time();
		}else{
			$value = ++$count.'_'.$time;
			echo $value.PHP_EOL;
		}		
		$redis->set($redis_key, $value, Array('ex'=>10)); 
		die('it ok step one:'.$count); 
	}
}else{
	$value = '1_'.time(); 	
	$redis->set($redis_key, $value, Array('ex'=>10)); 
	die('it ok step two:1'); 
}
?>