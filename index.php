<?php

require __DIR__ . '/vendor/autoload.php';

use Payments\Briva;

// echo Briva::information();

// set id key
Briva::$id_key = "";

// set secret key
Briva::$secret_key = "";

// set institution code
Briva::$institution_code = "";

// set briva no
Briva::$briva_no = "";

// set url
Briva::$url = "https://sandbox.partner.api.bri.co.id";

// header('Content-Type:: application/json');
echo "<pre>";

echo Briva::request_token();

echo "<br><br>-------------create--------------<br>";
echo Briva::create("58000001", "Manok", 1000, Date("Y-m-d 00:00:00", strtotime("+1 Month")));
echo "<br><br>-------------update--------------<br>";
echo Briva::update("58000001", "Manok", 1500, Date("Y-m-d 00:00:00", strtotime("+1 Month")));
echo "<br><br>-------------get status--------------<br>";
echo Briva::get_status("58000001");
echo "<br><br>-------------update status--------------<br>";
echo Briva::update_status("58000001", "Manok", "Y");
echo "<br><br>-------------get status--------------<br>";
echo Briva::get_status("58000001");
echo "<br><br>-------------delete--------------<br>";
echo Briva::delete("58000001");