<?php

namespace App\Controllers;

use Liman\Toolkit\Shell\Command;

class PingController
{
	public function ping()
	{
		$ip = request("ip");


		$result = Command::run("ping -c 2 -W 2 {:ip}", ["ip" => $ip]);

		if ($result != "") {
			preg_match("/(\d{1,3})% packet loss/m", $result, $matches);
			$result = ["ip" => $ip, "result" => $matches[1] != "100"];
		} else
			$result = ["ip" => $ip, "result" => false];


		return respond($result, 200);
	}


	public function addIp()
	{
		$ip = request("ip");
		$name = request("name");

		$ips = json_decode(file_get_contents(IPS_JSON));
		if ($ips == null)
			$ips = [];

		$id = $ips[count($ips) - 1]  == null ? 0 : intval($ips[count($ips) - 1]->id) + 1;

		array_push($ips, ["id" => $id, "name" => $name, "ip" => $ip]);


		$this->updateJson($ips);
	}


	public function deleteIp()
	{
		$id = request("id");


		$ips = json_decode(file_get_contents(IPS_JSON));
		if ($ips == [] || $ips == null)
			return respond("ips.json corrupted", 403);


		$index = array_search($id, array_column($ips, 'id'));
		array_splice($ips, $index, 1);


		$this->updateJson($ips);

		return respond(true, 200);
	}


	public function updateIp()
	{
		$id = request("id");
		$ip = request("ip");
		$name = request("name");

		$ips = json_decode(file_get_contents(IPS_JSON));
		if ($ips == [] || $ips == null)
			return respond("ips.json corrupted", 403);


		$index = array_search($id, array_column($ips, 'id'));
		$ips[$index] = ["id" => intval($id), "name" => $name, "ip" => $ip];


		$this->updateJson($ips);
		return respond(true, 200);
	}


	public function getSavedIps()
	{
		$contents = file_get_contents(IPS_JSON);
		if ($contents == "")
			$contents = "[]";

		return respond(json_decode($contents), 200);
	}


	public function getSavedIpsTable()
	{
		$contents = file_get_contents(IPS_JSON);
		if ($contents == "")
			$contents = "[]";


		return view('table', [
			"value" => json_decode($contents),
			"title" => ["Durum", "İsim", "IP Adresi", "*hidden*"],
			"display" => ["status", "name", "ip", "id:id"]
		], 200);
	}


	private function updateJson($ips)
	{
		$file = fopen(IPS_JSON, "w");
		fwrite($file, json_encode($ips));
		fclose($file);
	}
}
