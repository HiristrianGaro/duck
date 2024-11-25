<?php
// FILE: Duck/backend/registerTest.php

include "../common/connection.php";
include_once "../common/funzioni.php";

// Function to simulate POST request
function simulatePostRequest($postData) {
    $_POST = $postData;
    ob_start();
    include 'register.php';
    $output = ob_get_clean();
    return $output;
}

// Test cases
$testCases = [
    [
        'description' => 'Empty Username',
        'postData' => [
            'RegisterUsername' => '',
            'RegisterName' => 'TestName',
            'RegisterSurname' => 'TestSurname',
            'RegisterEmail' => 'test@example.com',
            'RegisterPassword' => 'password123',
            'ConfirmRegisterPassword' => 'password123'
        ],
        'expectedOutput' => 'Inserire un username'
    ],
    [
        'description' => 'Empty Name',
        'postData' => [
            'RegisterUsername' => 'TestUser',
            'RegisterName' => '',
            'RegisterSurname' => 'TestSurname',
            'RegisterEmail' => 'test@example.com',
            'RegisterPassword' => 'password123',
            'ConfirmRegisterPassword' => 'password123'
        ],
        'expectedOutput' => 'Inserire un nome'
    ],
    // Add more test cases as needed
];

// Run tests
foreach ($testCases as $testCase) {
    $output = simulatePostRequest($testCase['postData']);
    if (strpos($output, $testCase['expectedOutput']) !== false) {
        echo "Test '{$testCase['description']}' passed.\n";
    } else {
        echo "Test '{$testCase['description']}' failed. Expected '{$testCase['expectedOutput']}', got '$output'.\n";
    }
}
?>