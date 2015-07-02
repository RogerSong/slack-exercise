<?php

include 'variables.php';
$validate = array("token" => $token);

// Curling slack for list of users
$ch = curl_init("https://slack.com/api/users.list");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch ,CURLOPT_POST, count($validate));
curl_setopt($ch, CURLOPT_POSTFIELDS, $validate);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

// Decoding JSON data to PHP friendly array
$json = json_decode($result);

// Extracting just names of active users from array
foreach($json->members as $members) {
    if ($members->deleted == false)
    {
        $users[] = $members->name;
    }
}

// Generating number and exercise for payload
$amount = rand($min_amount, $max_amount);
$randUser = $users[rand(0,count($users) - 1)];
$randExercise = $exercise[rand(0,count($exercise) - 1)];
$payloadText = "NEXT EXERCISE: @" . $randUser . " must do " . $amount . " " . $randExercise;

// Packaging data for delivery to Slack
$data = array(
    "token"         =>  $token,
    "channel"       =>  $channel,
    "username"      =>  $username,
    "text"          =>  $payloadText,
    "icon_emoji"    =>  $icon
);

// Incoming webhook to Slack with payload
$ch = curl_init("https://slack.com/api/chat.postMessage");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($data));
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

// Echoing exercise just for validation
echo $payloadText;

?>