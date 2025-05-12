<?php
header('Content-type: text/plain');

// Database connection with XAMPP default credentials
//atsk_4c361d19d2efa6b58886e5a9b0ae63cfade92b28aabb8a1d1806e536cf11c6e31b79b531
$db = new PDO('mysql:host=localhost;dbname=farmers_ussd', 'root', '');

try {
    // Get USSD parameters from Africa's Talking
    $sessionId   = $_POST["sessionId"] ?? '';
    $serviceCode = $_POST["serviceCode"] ?? '*384*07887#';
    $phoneNumber = $_POST["phoneNumber"] ?? '+250790780353';
    $text        = $_POST["text"] ?? '';

    // Validate required parameters
    if (empty($sessionId) || empty($serviceCode) || empty($phoneNumber)) {
        throw new Exception("Missing required parameters");
    }

    // Explode text to get user input steps
    $textArray = explode("*", $text);
    $level = count($textArray);

    // Main menu
    if ($text == "" || $text == "0") {
        $response = "CON Welcome to Farmer's Assistant\n";
        $response .= "1. Crop Calendar\n";
        $response .= "2. Weather Updates\n";
        $response .= "3. Market Prices\n";
        $response .= "4. Farming Tips\n";
        $response .= "5. Request Help\n";
        $response .= "6. Exit";
    }
    // Level 1 - Main menu options
    else if ($level == 1) {
        switch ($text) {
            case "1": // Crop Calendar
                $response = "CON Select crop:\n";
                $response .= "1. Maize\n";
                $response .= "2. Beans\n";
                $response .= "3. Rice\n";
                $response .= "4. Back to main menu";
                break;

            case "2": // Weather Updates
                $response = "CON Weather Updates:\n";
                $response .= "1. Today's forecast\n";
                $response .= "2. Weekly forecast\n";
                $response .= "3. Back to main menu";
                break;

            case "3": // Market Prices
                $response = "CON Select crop for price:\n";
                $response .= "1. Maize\n";
                $response .= "2. Beans\n";
                $response .= "3. Rice\n";
                $response .= "4. Back to main menu";
                break;

            case "4": // Farming Tips
                $response = "CON Select crop for tips:\n";
                $response .= "1. Maize\n";
                $response .= "2. Beans\n";
                $response .= "3. Rice\n";
                $response .= "4. Back to main menu";
                break;

            case "5": // Request Help
                $response = "CON Select issue type:\n";
                $response .= "1. Pest Control\n";
                $response .= "2. Disease Management\n";
                $response .= "3. Soil Issues\n";
                $response .= "4. Other\n";
                $response .= "5. Back to main menu";
                break;

            case "6": // Exit
                $response = "END Thank you for using Farmer's Assistant!";
                break;

            default:
                $response = "END Invalid option. Please try again.";
        }
    }
    // Level 2 - Submenu options
    else if ($level == 2) {
        $mainOption = $textArray[0];
        $subOption = $textArray[1];

        switch ($mainOption) {
            case "1": // Crop Calendar
                $calendar = [
                    "1" => ["name" => "Maize", "planting" => "March-April", "harvesting" => "August-September"],
                    "2" => ["name" => "Beans", "planting" => "February-March", "harvesting" => "May-June"],
                    "3" => ["name" => "Rice", "planting" => "November-December", "harvesting" => "March-April"]
                ];
                
                if ($subOption == "4") {
                    $response = "CON Welcome to Farmer's Assistant\n";
                    $response .= "1. Crop Calendar\n";
                    $response .= "2. Weather Updates\n";
                    $response .= "3. Market Prices\n";
                    $response .= "4. Farming Tips\n";
                    $response .= "5. Request Help\n";
                    $response .= "6. Exit";
                } else if (isset($calendar[$subOption])) {
                    $crop = $calendar[$subOption];
                    $response = "END {$crop['name']} Calendar:\n";
                    $response .= "Planting: {$crop['planting']}\n";
                    $response .= "Harvesting: {$crop['harvesting']}";
                } else {
                    $response = "END Invalid option. Please try again.";
                }
                break;

            case "2": // Weather Updates
                if ($subOption == "3") {
                    $response = "CON Welcome to Farmer's Assistant\n";
                    $response .= "1. Crop Calendar\n";
                    $response .= "2. Weather Updates\n";
                    $response .= "3. Market Prices\n";
                    $response .= "4. Farming Tips\n";
                    $response .= "5. Request Help\n";
                    $response .= "6. Exit";
                } else if ($subOption == "1") {
                    $response = "END Today's Weather:\n";
                    $response .= "Temperature: 25°C\n";
                    $response .= "Conditions: Partly Cloudy\n";
                    $response .= "Rainfall: 20% chance";
                } else if ($subOption == "2") {
                    $response = "END Weekly Forecast:\n";
                    $response .= "Mon: Sunny, 26°C\n";
                    $response .= "Tue: Cloudy, 24°C\n";
                    $response .= "Wed: Rain, 22°C\n";
                    $response .= "Thu: Partly Cloudy, 25°C\n";
                    $response .= "Fri: Sunny, 27°C";
                } else {
                    $response = "END Invalid option. Please try again.";
                }
                break;

            case "3": // Market Prices
                $prices = [
                    "1" => ["name" => "Maize", "price" => "500 RWF/kg"],
                    "2" => ["name" => "Beans", "price" => "800 RWF/kg"],
                    "3" => ["name" => "Rice", "price" => "1200 RWF/kg"]
                ];
                
                if ($subOption == "4") {
                    $response = "CON Welcome to Farmer's Assistant\n";
                    $response .= "1. Crop Calendar\n";
                    $response .= "2. Weather Updates\n";
                    $response .= "3. Market Prices\n";
                    $response .= "4. Farming Tips\n";
                    $response .= "5. Request Help\n";
                    $response .= "6. Exit";
                } else if (isset($prices[$subOption])) {
                    $crop = $prices[$subOption];
                    $response = "END Current price of {$crop['name']}:\n";
                    $response .= "{$crop['price']}";
                } else {
                    $response = "END Invalid option. Please try again.";
                }
                break;

            case "4": // Farming Tips
                $tips = [
                    "1" => ["name" => "Maize", "tips" => "Plant in rows 75cm apart. Water regularly during dry spells."],
                    "2" => ["name" => "Beans", "tips" => "Plant in well-drained soil. Support with stakes as they grow."],
                    "3" => ["name" => "Rice", "tips" => "Requires flooded fields. Control weeds regularly."]
                ];
                
                if ($subOption == "4") {
                    $response = "CON Welcome to Farmer's Assistant\n";
                    $response .= "1. Crop Calendar\n";
                    $response .= "2. Weather Updates\n";
                    $response .= "3. Market Prices\n";
                    $response .= "4. Farming Tips\n";
                    $response .= "5. Request Help\n";
                    $response .= "6. Exit";
                } else if (isset($tips[$subOption])) {
                    $crop = $tips[$subOption];
                    $response = "END {$crop['name']} Farming Tips:\n";
                    $response .= "{$crop['tips']}";
                } else {
                    $response = "END Invalid option. Please try again.";
                }
                break;

            case "5": // Request Help
                if ($subOption == "5") {
                    $response = "CON Welcome to Farmer's Assistant\n";
                    $response .= "1. Crop Calendar\n";
                    $response .= "2. Weather Updates\n";
                    $response .= "3. Market Prices\n";
                    $response .= "4. Farming Tips\n";
                    $response .= "5. Request Help\n";
                    $response .= "6. Exit";
                } else {
                    // Store the help request in database
                    $issueTypes = [
                        "1" => "Pest Control",
                        "2" => "Disease Management",
                        "3" => "Soil Issues",
                        "4" => "Other"
                    ];
                    
                    $issueType = $issueTypes[$subOption] ?? "Unknown";
                    
                    // Here you would typically save to database
                    // $stmt = $db->prepare("INSERT INTO help_requests (phone, issue_type) VALUES (?, ?)");
                    // $stmt->execute([$phoneNumber, $issueType]);
                    
                    $response = "END Your help request has been received.\n";
                    $response .= "Issue: {$issueType}\n";
                    $response .= "An agriculture officer will contact you soon.";
                }
                break;

            default:
                $response = "END Invalid option. Please try again.";
        }
    }
    // Invalid level
    else {
        $response = "END Invalid option. Please try again.";
    }

    // Output the response
    echo $response;

} catch (Exception $e) {
    // Handle any errors
    echo "END An error occurred. Please try again later.";
    // Log the error for debugging
    error_log("USSD Error: " . $e->getMessage());
}
?>