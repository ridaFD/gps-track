<?php

// Read the city.html file
$htmlContent = file_get_contents(__DIR__ . '/city.html');

// Extract cities using regex
preg_match_all('/<li[^>]*>([^<]+)<\/li>/', $htmlContent, $matches);

$cities = [];
foreach ($matches[1] as $cityText) {
    $cityText = trim($cityText);
    // Skip the "Select Customer City" option
    if (!empty($cityText) && !str_contains($cityText, '--Select Customer City--')) {
        $cities[] = $cityText;
    }
}

// Remove duplicates
$cities = array_unique($cities);
sort($cities);

// Create CSV
$csvContent = '';
foreach ($cities as $city) {
    $csvContent .= '"' . str_replace('"', '""', $city) . '"' . "\n";
}
file_put_contents(__DIR__ . '/cities.csv', $csvContent);

// Create JSON array for PHP use
$jsonContent = json_encode($cities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents(__DIR__ . '/cities.json', $jsonContent);

echo "Extracted " . count($cities) . " unique cities\n";
echo "Created cities.csv and cities.json\n";

