<?php

// Define the key and plaintext
$key = "0123456789abcdef";
$plaintext = "Hello, World!";

// Generate the DES cipher
$cipher = openssl_encrypt($plaintext, "DES-ECB", $key, OPENSSL_RAW_DATA);

// Print the cipher
echo base64_encode($cipher);

?>