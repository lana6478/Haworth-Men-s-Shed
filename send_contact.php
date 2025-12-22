<?php
/**
 * This script handles the contact form submission.
 * It creates a folder called 'messages' and saves each entry as a text file.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect and clean data to prevent basic scripts from running
    $name = htmlspecialchars($_POST['visitor_name']);
    $email = htmlspecialchars($_POST['visitor_email']);
    $message = htmlspecialchars($_POST['visitor_message']);

    // 2. Setup the folder
    $folderName = 'messages';

    // Check if folder exists, if not, create it
    if (!is_dir($folderName)) {
        // 0777 gives the server permission to read/write the folder
        mkdir($folderName, 0777, true);
    }

    // 3. Create a unique filename for this specific message
    // Format: Name_Date_Time.txt (e.g., John-Doe_2025-12-22_143005.txt)
    $safeName = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    $filename = $safeName . "_" . date("Y-m-d_H-i-s") . ".txt";
    $fullPath = $folderName . '/' . $filename;

    // 4. Create the content for the file
    $fileContent = "NEW MESSAGE FROM WEBSITE\n";
    $fileContent .= "=========================\n\n";
    $fileContent .= "Date: " . date("l, jS F Y, H:i:s") . "\n";
    $fileContent .= "From: " . $name . "\n";
    $fileContent .= "Email: " . $email . "\n\n";
    $fileContent .= "Message Content:\n";
    $fileContent .= "----------------\n";
    $fileContent .= $message . "\n";

    // 5. Write the file to the folder
    if (file_put_contents($fullPath, $fileContent)) {
        // Success Message (Styled to match the site)
        echo "<!DOCTYPE html><html lang='en'><head><link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap' rel='stylesheet'><style>body{font-family:Inter, sans-serif; background:#f3f4f6; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; text-align:center;} .box{background:white; padding:50px; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.1); max-width:400px;} h1{color:#1f2937;} p{color:#6b7280; margin-bottom:30px;} .btn{background:#f59e0b; color:white; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold;}</style></head><body>";
        echo "<div class='box'><h1>Message Sent!</h1><p>Thank you, $name. We have saved your message in the $folderName folder.</p><a href='index.html' class='btn'>Back to Home</a></div>";
        echo "</body></html>";
    } else {
        echo "Error: The server could not save the message. Please check folder permissions.";
    }
} else {
    // If someone tries to access this file directly, send them back to home
    header("Location: index.html");
    exit();
}
?>
