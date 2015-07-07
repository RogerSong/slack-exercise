<?php

// This php page should only be accessed via ajax. All other requests including direct access should be denied.
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ) {
    
    // Gathering data from user's variables file
    include 'variables.php';
    $data = array(
        "token"         =>  $token,
        "channel"       =>  $channel,
        "username"      =>  $username,
        "icon_emoji"    =>  $icon
    );

    // Checking to see if each variable has been set by the user
    foreach($data as $key => $value) {
        if (empty($value)) {
            echo $key . " is empty. Check variables file.";
            die();
        }
    }

    // Curling slack for list of users
    $ch = curl_init("https://slack.com/api/users.list?token=" . $token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    // Decoding JSON data to PHP friendly array
    $json = json_decode($result);

    // Checking for authentication success
    if (isset($json->error)) {
        echo "Authentication Failed. Please check your user token.";
        die();
    }

    // Extracting just names of active users from array
    foreach($json->members as $members) {
        if ($members->deleted == false)
            $users[] = $members->name;
    }

    // Generating number and exercise for payload
    $randAmount = rand($min_amount, $max_amount);
    $randUser = $users[rand(0,count($users) - 1)];
    $randExercise = $exercise[rand(0,count($exercise) - 1)];
    $payloadText = "NEXT EXERCISE: @" . $randUser . " must do " . $randAmount . " " . $randExercise;
    $data["text"] = $payloadText;

    // Passing data and payload text to Slack's post message method
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, count($data));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    // Echoing exercise back to HTML page
    echo $payloadText;

} else {
    die();
}

?>