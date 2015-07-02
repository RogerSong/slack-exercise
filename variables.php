<?php

// Paste your Slack API token in here
// You can generate one from: https://api.slack.com/web#authentication
$token = "";

// Channel to post the exercise
// Don't forget to prefix the channel with a hashtag
$channel = "#random";

// Username of the bot
$username = "ExerciseBot";

// User icon of the bot
$icon = ":muscle:";

// List of exercises
$exercise = array(
	"sit ups",
	"push ups",
	"squats",
	"jumping jacks"
);

// Generates a random number between $min_amount and $max_amount
$min_amount = 10;
$max_amount = 25;

?>