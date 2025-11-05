const fs = require('fs');
const path = require('path');

// Read the city.html file
const htmlContent = fs.readFileSync(path.join(__dirname, 'city.html'), 'utf8');

// Extract cities using regex
const cityRegex = /<li[^>]*>([^<]+)<\/li>/g;
const cities = [];
let match;

while ((match = cityRegex.exec(htmlContent)) !== null) {
    const cityText = match[1].trim();
    // Skip the "Select Customer City" option
    if (cityText && !cityText.includes('--Select Customer City--')) {
        cities.push(cityText);
    }
}

// Remove duplicates
const uniqueCities = [...new Set(cities)];

// Create CSV
const csvContent = uniqueCities.map(city => `"${city.replace(/"/g, '""')}"`).join('\n');
fs.writeFileSync(path.join(__dirname, 'cities.csv'), csvContent, 'utf8');

// Create JSON array for PHP use
const jsonContent = JSON.stringify(uniqueCities, null, 2);
fs.writeFileSync(path.join(__dirname, 'cities.json'), jsonContent, 'utf8');

console.log(`Extracted ${uniqueCities.length} unique cities`);
console.log('Created cities.csv and cities.json');

