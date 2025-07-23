<?php
require_once 'tests/bootstrap.php';
require_once 'lib/SuiteCRM/Utility/StringValidator.php';

echo "Running StringValidator tests..." . PHP_EOL;

// Test startsWith
$testString = 'foobarbaz';
$tests_passed = 0;
$tests_total = 0;

// Test 1: startsWith with 'foo'
$tests_total++;
if (SuiteCRM\Utility\StringValidator::startsWith($testString, 'foo')) {
    echo "PASS: startsWith test 1" . PHP_EOL;
    $tests_passed++;
} else {
    echo "FAIL: startsWith test 1" . PHP_EOL;
}

// Test 2: startsWith with 'bar' (should be false)
$tests_total++;
if (!SuiteCRM\Utility\StringValidator::startsWith($testString, 'bar')) {
    echo "PASS: startsWith test 2" . PHP_EOL;
    $tests_passed++;
} else {
    echo "FAIL: startsWith test 2" . PHP_EOL;
}

// Test 3: endsWith with 'baz'
$tests_total++;
if (SuiteCRM\Utility\StringValidator::endsWith($testString, 'baz')) {
    echo "PASS: endsWith test 1" . PHP_EOL;
    $tests_passed++;
} else {
    echo "FAIL: endsWith test 1" . PHP_EOL;
}

// Test 4: endsWith with 'bar' (should be false)
$tests_total++;
if (!SuiteCRM\Utility\StringValidator::endsWith($testString, 'bar')) {
    echo "PASS: endsWith test 2" . PHP_EOL;
    $tests_passed++;
} else {
    echo "FAIL: endsWith test 2" . PHP_EOL;
}

echo "Tests completed: $tests_passed/$tests_total passed" . PHP_EOL; 