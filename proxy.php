<?php

// Define the target URL
$targetUrl = 'http://127.0.0.1:11100/capture';

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Forward request headers
$headers = getallheaders();

// Forward request body
$body = file_get_contents('php://input');

// Create a new cURL resource
$ch = curl_init();

// Set the cURL options
curl_setopt($ch, CURLOPT_URL, $targetUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

// Execute the request and get the response
$response = curl_exec($ch);

// Get the response headers
$responseHeaders = curl_getinfo($ch);

// Close cURL resource
curl_close($ch);

// Forward response headers to the client
foreach ($responseHeaders as $name => $value) {
    if (strpos($name, 'Content-Length') === false && strpos($name, 'Transfer-Encoding') === false) {
        header("$name: $value");
    }
}

// Return the response to the client
echo $response;

