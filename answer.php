<?php

function findAnswer($translatedToken)
{
    $replacements = array(
        "0" => "9",
        "1" => "8",
        "2" => "7",
        "3" => "6",
        "4" => "5",
        "5" => "4",
        "6" => "3",
        "7" => "2",
        "8" => "1",
        "9" => "0",
        "a" => "z",
        "b" => "y",
        "c" => "x",
        "d" => "w",
        "e" => "v",
        "f" => "u",
        "g" => "t",
        "h" => "s",
        "i" => "r",
        "j" => "q",
        "k" => "p",
        "l" => "o",
        "m" => "n",
        "n" => "m",
        "o" => "l",
        "p" => "k",
        "q" => "j",
        "r" => "i",
        "s" => "h",
        "t" => "g",
        "u" => "f",
        "v" => "e",
        "w" => "d",
        "x" => "c",
        "y" => "b",
        "z" => "a"
    );
    return strtr($translatedToken, $replacements);    
}

function crawlerPage()
{
    $cookie = "cookie.txt";
    $tagretUrl = "http://applicant-test.us-east-1.elasticbeanstalk.com/";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_URL, $tagretUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    
    $firstPage = curl_exec($ch);
    
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($firstPage);
    $xpath = new DOMXPath($dom);

    $nodeToken = $xpath->query("//input[@id='token']")->item(0);
    $token = $nodeToken->getAttribute("value");
   
    $translatedToken = findAnswer($token);
    
    $arrHeaders = [
        "Referer: http://applicant-test.us-east-1.elasticbeanstalk.com/",
        "Content-Type: application/x-www-form-urlencoded",
    ];

    $postData = "token=" . urlencode($translatedToken);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeaders);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    
    $pageAnswer = curl_exec($ch);   

    $dom = new DOMDocument();
    $dom->loadHTML($pageAnswer);
    $xpath = new DOMXPath($dom);
    
    $nodeAnswer = $xpath->query("//span[@id='answer']")->item(0);
    $answer = $nodeAnswer->nodeValue;

    curl_close($ch);
    
    return $answer;
}

echo "The answer is: " . crawlerPage() . "\n";