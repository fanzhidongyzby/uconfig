<?php
	function replace($path, $key, $old, $new) {
		if(!$key || strpos($new, '\'')) {
			return false;
		}

		$config = file($path);
		$key = '\''.$key.'\'';
		
		$new_config = '';

		foreach($config as $line => $content){
				if(!$content) {
					$new_config .= $content;
					continue;
				}
				
				$pos = strpos($content, $key);
				if ($pos === false) {
					$new_config .= $content;
					continue;
				}
				$head = substr($content, 0, $pos + strlen($key));
				$tail = substr($content, $pos + strlen($key));
				$pos = strpos($tail, $old);
				if ($pos === false) {
					$new_config .= $content;
					continue;
				}

				$tail = substr_replace($tail, $new, $pos, strlen($old));
				$content = $head.$tail;
				$new_config .= $content;
		}
		$file = fopen($path, 'w') or die('Unable to open file!');
		fwrite($file, $new_config);
		fclose($file);
		return true;
	}

	$key = $_POST['key'];
	$old = $_POST['old'];
	$new = $_POST['new'];
	$ok = replace("messages.js", $key, $old, $new);
	if ($ok) {
		//$ok = replace("app.js.map", $key, $old, $new);
		$ok = replace("/usr/lib/ambari-server/web/javascripts/app.js", $key, $old, $new);
	}
	return $ok;

?>
