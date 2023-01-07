<?php

function crawlerPage()
{
    $tagretUrl = "http://applicant-test.us-east-1.elasticbeanstalk.com/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tagretUrl);
    
    $page = curl_exec($ch);

    print_r($page);
    die;
}