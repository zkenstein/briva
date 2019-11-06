<?php

namespace Payments;

use \Payments\Curl;

class Briva
{
	static $id_key;

	static $secret_key;

	static $url;

	static $institution_code;

	static $briva_no;

	static $token;

	public static function information()
	{
		return 'BRI Virtual Account Bridging';
	}

	public static function request_token()
	{
		$data = [
			"client_id"=>Briva::$id_key,
			"client_secret"=>Briva::$secret_key
		];
		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$result =  Curl::post(http_build_query($data),$headers,Briva::$url . "/oauth/client_credential/accesstoken?grant_type=client_credentials");
		Briva::$token = json_decode($result)->access_token;
		return $result;
	}

	public static function payload($path, $verb, $token, $timestamp, $body)
	{
		$array = [
			'path' => $path,
			'verb' => $verb,
			'token' => $token,
			'timestamp' => $timestamp,
			'body' => $body
		];
		$retval = urldecode(http_build_query($array));
		$retval = hash_hmac('sha256', $retval, Briva::$secret_key, true);
		$retval = base64_encode($retval);
		return $retval;
	}

	public static function create($custCode, $custName, $amount, $expiredDate)
	{
		$path = "/v1/briva";
		$verb = "POST";
		$token = "Bearer ".Briva::$token;
		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$body = json_encode([
			"institutionCode" => Briva::$institution_code,
			"brivaNo" => Briva::$briva_no,
			"custCode" => $custCode,
			"nama" => $custName,
			"amount" => $amount,
			"keterangan" => "",
			"expiredDate" => $expiredDate
		]);
		$signature = self::payload($path, $verb, $token, $timestamp, $body);
		$headers = array();
		$headers[] = 'BRI-Signature: '.$signature;
		$headers[] = 'BRI-Timestamp: '.$timestamp;
		$headers[] = 'Authorization: '.$token;
		$headers[] = 'Content-Type: application/json';
		return Curl::post($body,$headers,Briva::$url.$path);
	}

	public static function update($custCode,$custName,$amount,$expiredDate)
	{
		$path = "/v1/briva";
		$verb = "PUT";
		$token = "Bearer ".Briva::$token;
		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$body = json_encode([
			"institutionCode" => Briva::$institution_code,
			"brivaNo" => Briva::$briva_no,
			"custCode" => $custCode,
			"nama" => $custName,
			"amount" => $amount,
			"keterangan" => "",
			"expiredDate" => $expiredDate
		]);
		$signature = self::payload($path, $verb, $token, $timestamp, $body);
		$headers = array();
		$headers[] = 'BRI-Signature: '.$signature;
		$headers[] = 'BRI-Timestamp: '.$timestamp;
		$headers[] = 'Authorization: '.$token;
		$headers[] = 'Content-Type: application/json';
		return Curl::put($body,$headers,Briva::$url.$path);
	}

	public static function delete($custCode)
	{
		$path = "/v1/briva";
		$verb = "DELETE";
		$token = "Bearer ".Briva::$token;
		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$body = http_build_query([
			"institutionCode" => Briva::$institution_code,
			"brivaNo" => Briva::$briva_no,
			"custCode" => $custCode,
		]);
		$signature = self::payload($path, $verb, $token, $timestamp, $body);
		$headers = array();
		$headers[] = 'BRI-Signature: '.$signature;
		$headers[] = 'BRI-Timestamp: '.$timestamp;
		$headers[] = 'Authorization: '.$token;
		$headers[] = 'Content-Type: text/plain';
		return Curl::delete($body,$headers, Briva::$url.$path);
	}

	public static function get_status($custCode)
	{
		$path = "/v1/briva/status/".Briva::$institution_code."/".Briva::$briva_no."/".$custCode;
		$verb = "GET";
		$token = "Bearer ".Briva::$token;
		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$body = "";
		$signature = self::payload($path, $verb, $token, $timestamp, $body);
		$headers = array();
		$headers[] = 'BRI-Signature: '.$signature;
		$headers[] = 'BRI-Timestamp: '.$timestamp;
		$headers[] = 'Authorization: '.$token;
		return Curl::get($headers,Briva::$url.$path);
	}

	public static function update_status($custCode, $custName, $statusBayar)
	{
		$path = "/v1/briva/status";
		$verb = "PUT";
		$token = "Bearer ".Briva::$token;
		$timestamp = gmdate("Y-m-d\TH:i:s.000\Z");
		$body = json_encode([
			"institutionCode" => Briva::$institution_code,
			"brivaNo" => Briva::$briva_no,
			"custCode" => $custCode,
			"nama" => $custName,
			"statusBayar" => "Y"
		]);
		$signature = self::payload($path, $verb, $token, $timestamp, $body);
		$headers = array();
		$headers[] = 'BRI-Signature: '.$signature;
		$headers[] = 'BRI-Timestamp: '.$timestamp;
		$headers[] = 'Authorization: '.$token;
		$headers[] = 'Content-Type: application/json';
		return Curl::put($body,$headers,Briva::$url.$path);
	}
}