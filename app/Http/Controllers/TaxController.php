<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaxController extends Controller
{
	public function __construct()
	{
		// 岩手県盛岡市
		$this->firstPrefCode = "3";
		$this->firstCityCode = "03201";
		// 東京都港区
		$this->secondPrefCode = "13";
		$this->secondCityCode = "13103";
	}

	public function index()
	{
		$taxes['iwate'] = $this->getTax($this->firstPrefCode,$this->firstCityCode);
		$taxes['tokyo'] = $this->getTax($this->secondPrefCode,$this->secondCityCode);
		return response()->json($taxes);
	}

	public function getTax($pref_cd, $city_cd)
	{
		$api_key = "UPX5SZobrRouNAHKJksmpsixcgtDbcQfPXCo2UV9";
		$headers = [
			"Content-Type: application/json",
			"X-API-KEY: " . $api_key,
		];
		$url = "https://opendata.resas-portal.go.jp/api/v1/municipality/taxes/perYear?prefCode=".$pref_cd."&cityCode=".$city_cd;
		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$res = curl_exec($curl);
		curl_close($curl);
		$res_array = json_decode($res, true);
		$tmp = $res_array['result']['data'];
		$recent_num = count($res_array['result']['data'])-1;
		$result['year'] = $res_array['result']['data'][$recent_num]['year'];
		$result['value'] = $res_array['result']['data'][$recent_num]['value'];
		return $result;
	}
}
