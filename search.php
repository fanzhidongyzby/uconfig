<?php
	class kv {
		
	}
	function search($keyword) {
		$kvs = array();
		$config = file('./messages.js');
		foreach($config as $line => $content){
			$kv = explode(':', $content);
			$len = count($kv);
			if ($len == 2) {
				$key = trim($kv[0]);
				$value = trim($kv[1]);
				if(strlen($key) > 2 && strlen($value) > 3 &&
					$key[0] == '\'' && $key[strlen($key) - 1] == '\'' &&
					$value[0] == '\'' && $value[strlen($value) - 2] == '\'' && 
					$value[strlen($value) - 1] == ',') {
					$key = substr($key, 1, strlen($key) - 2);
					$value = substr($value , 1, strlen($value) - 3);
					if (!(strpos($value, $keyword) === false)) {
						$kv = new kv;
						$kv->key = $key;
						$kv->value = $value;
						array_push($kvs, $kv);
					}
				}
			}
		}
		return json_encode($kvs);
	}
	//$data = file_get_contents("php://input");
	//print_r($data);
	//print_r($_POST);
	//print_r($_POST['keyword']);
	//print_r($_POST['keyword2']);
	
	echo search($_POST['keyword']);
	//echo search('运行');
?>
