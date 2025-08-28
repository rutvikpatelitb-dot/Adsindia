<?php
/**
 * Simple Hello World PHP script for Adsindia project
 */

// Display a greeting message
echo "Hello, PHP World!\n";

// Show current date and time
echo "Current date and time: " . date('Y-m-d H:i:s') . "\n";

// Basic variable example
$projectName = "Adsindia";
$language = "PHP";

echo "Welcome to $projectName - powered by $language!\n";

// Simple function example
function greetUser($name = "Developer") {
    return "Hello, $name! Ready to code some PHP?";
}

echo greetUser() . "\n";
echo greetUser("Adsindia Team") . "\n";
?>