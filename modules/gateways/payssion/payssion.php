<?php
/*
Plugin Name: PAYSSION for WHMCS
Description: PAYSSION payment gateway plugin for WHMCS
Version: 1.0
Compatible with: WHMCS 5.3.14, 6.0.2
Author: PAYSSION
Author URI: https://www.payssion.com
Release date: 11/11/2015
*/

class Payssion extends Controller {
	public static $_api_key = 'you api key';
	public static $_secret_key = 'you secret key';
	
	public static function getPMId($class_name) {
		$index = strpos($class_name, '_');
		return strtolower(substr($class_name, $index + 1));
	}
	
	public static function getConfig($pm_name) {
		return 	$configarray = array(
		"FriendlyName" => array(
			"Type" => "System",
			"Value" => $pm_name
		));
	}
	
	public static function getLink(&$params, $pm_id) {
		$address = $params['clientdetails']['address1'].' '.$params['clientdetails']['address2'];
		$url = '';
		if (true/*$params['payssion_testmode']*/) {
			$url = 'https://www.payssion.com/payment/create.html';
		} else {
			$url = 'http://sandbox.payssion.com/payment/create.html';
		}
		
		$api_key = self::$_api_key;//$params['payssion_api_key'];
		$secret_key = self::$_secret_key;//$params['payssion_secret_key'];
		$source = 'whmcs';
		
		// Invoice Parameters
		$track_id = $params['invoiceid'];
		$description = $params["description"];
		$amount = $params['amount'];
		$currency = $params['currency'];
		
		// Client Parameters
		$payer_name = $params['clientdetails']['firstname'] . ' ' . $params['clientdetails']['lastname'];
		$payer_email = $params['clientdetails']['email'];
		
		$module_name = $params['paymentmethod'];
		$system_url = $params['systemurl'];
		$notify_url = $system_url . '/modules/gateways/callback/payssion.php';
		
		$arr = array($api_key, $pm_id, $amount, $currency,
				$track_id, '', $secret_key);
		$api_sig = implode('|', $arr);
		
		$code =
		$code = '<form method="post" action="' . $url . '">
		<input type="hidden" name="source" value="'.$source.'" />
		<input type="hidden" name="api_key" value="'.$api_key.'" />
		<input type="hidden" name="api_sig" value="'.$api_sig.'" />
		<input type="hidden" name="pm_id" value="'.$pm_id.'" />
		<input type="hidden" name="payer_name" value="'.$payer_name.'" />
		<input type="hidden" name="payer_email" value="'.$payer_email.'" />
		<input type="hidden" name="track_id" value="'.$track_id.'" />
		<input type="hidden" name="description" value="'.$description.'" />
		<input type="hidden" name="amount" value="'.$amount.'" />
		<input type="hidden" name="currency" value="'$currency.'" />
		<input type="hidden" name="notify_url" value="'.$notify_url.'" />
		<input type="hidden" name="success_url" value="'.$params['returnurl'].'" />
		<input type="hidden" name="redirect_url" value="'.$params['returnurl'].'" />
		<input type="submit" value="Pay Now" />
		</form>';
		return $code;
	}
}

function payssion_config() {
	$configarray = array(
		"FriendlyName" => array(
			"Type" => "System",
			"Value" => "PAYSSION"
		),
		"payssion_api_key" => array(
			"FriendlyName" => "API Key",
			"Type" => "text",
			"Size" => "32",
			"Description" => "You can find your API Key in your APP setting"
		),
		"payssion_secret_key" => array(
			"FriendlyName" => "Secret Key",
			"Type" => "text",
			"Size" => "32",
			"Description" => "You can find your Secret Key in your APP setting"
		)/*,
	    "payssion_testmode" => array(
	    		"FriendlyName" => "TestMode", 
	    		"Type" => "yesno", 
	    		"Description" => "Please select YES if you are using the sandbox environment")*/
	);
	return $configarray;
}
function okpay_link($params) {
	$address = $params['clientdetails']['address1'].' '.$params['clientdetails']['address2'];
	$url = '';
	if (true/*$params['payssion_testmode']*/) {
		$url = 'https://www.payssion.com/payment/create.html';
	} else {
		$url = 'http://sandbox.payssion.com/payment/create.html';
	}
	
	$api_key = $params['payssion_api_key'];
	$secret_key = $params['payssion_secret_key'];
	$source = 'whmcs';
	$pm_id = 'payssion';
	
	// Invoice Parameters
	$track_id = $params['invoiceid'];
	$description = $params["description"];
	$amount = $params['amount'];
	$currency = $params['currency'];
	
	// Client Parameters
	$payer_name = $params['clientdetails']['firstname'] . ' ' . $params['clientdetails']['lastname'];
	$payer_email = $params['clientdetails']['email'];
	
	$module_name = $params['paymentmethod'];
	$system_url = $params['systemurl'];
	$notify_url = $system_url . '/modules/gateways/callback/' . $module_name . '.php';
	
	$arr = array($api_key, $pm_id, $amount, $currency,
			$track_id, '', $secret_key);
	$api_sig = implode('|', $arr);
	
	$code = 
	$code = '<form method="post" action="' . $url . '">
				<input type="hidden" name="source" value="'.$source.'" />
				<input type="hidden" name="api_key" value="'.$api_key.'" />
				<input type="hidden" name="api_sig" value="'.$api_sig.'" />
				<input type="hidden" name="pm_id" value="'.$pm_id.'" />
				<input type="hidden" name="payer_name" value="'.$payer_name.'" />
				<input type="hidden" name="payer_email" value="'.$payer_email.'" />
				<input type="hidden" name="track_id" value="'.$track_id.'" />
				<input type="hidden" name="description" value="'.$description.'" />
				<input type="hidden" name="amount" value="'.$amount.'" />
				<input type="hidden" name="currency" value="'$currency.'" />
				<input type="hidden" name="notify_url" value="'.$notify_url.'" />
				<input type="hidden" name="success_url" value="'.$params['returnurl'].'" />
				<input type="hidden" name="redirect_url" value="'.$params['returnurl'].'" />
				<input type="submit" value="Pay Now" />
			</form>';
	return $code;
}
?>