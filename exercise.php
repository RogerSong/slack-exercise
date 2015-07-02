<?php

include 'variables.php';
$validate = array("token" => $token);

// Curling slack for users.list
$ch_users = curl_init("https://slack.com/api/users.list");
curl_setopt($ch_users, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch_users, CURLOPT_POSTFIELDS, $validate);
curl_setopt($ch_users, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch_users);
curl_close($ch_users);

// Decoding JSON data to PHP friendly array
$json = json_decode($result);

// Extracting just names from array
foreach($json->members as $members) {
    if ($members->deleted == false)
    {
    	$userList[] = $members->name;
    }
}

// Generating number and exercise for payload

$amount = rand(10,30);
$userLength = count($userList) - 1;
$exerciseLength = count($exercise) - 1;

$payloadText = "NEXT EXERCISE: @" . $userList[rand(0,$userLength)] . " must do " . $amount . " " . $exercise[rand(0,$exerciseLength)];
echo $payloadText, "<br />";

// Packaging data for delivery to Slack
$data = array(
    "token"			=>	$token,
    "channel"       =>  $channel,
    "username"		=>	$username,
    "text"          =>  $payloadText,
    "icon_emoji"    =>  $icon
);

// Incoming webhook to Slack with payload
$ch = curl_init("https://slack.com/api/chat.postMessage");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);
echo "Server message: " . $result;

?>