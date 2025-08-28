<?php

/**
 * Sanitize user input to prevent XSS and other attacks.
 *
 * @param string $data The input data to sanitize.
 * @return string The sanitized data.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Convert a given amount to a specified currency.
 * Note: This is a placeholder function. A real-world implementation
 * would involve a more robust system, possibly an API.
 *
 * @param float $amount The amount to convert.
 * @param string $target_currency The currency to convert to (e.g., 'USD', 'EUR').
 * @return float The converted amount.
 */
function convertCurrency($amount, $target_currency) {
    // This is a placeholder. In a real application, you would
    // use a currency conversion API or a comprehensive database.
    $exchange_rates = [
        'USD' => 1.0,
        'EUR' => 0.93,
        'GBP' => 0.79,
        // Add more exchange rates as needed
    ];

    if (isset($exchange_rates[$target_currency])) {
        return $amount * $exchange_rates[$target_currency];
    }

    // Default to returning the original amount if the currency is not found
    return $amount;
}
?>