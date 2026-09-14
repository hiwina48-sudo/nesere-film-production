<?php

if (!isset($_SESSION["lang"])) {
    $_SESSION["lang"] = "en";
}

$lang = $_SESSION["lang"];

$translations = [

    "en" => [

        "home" => "Home",
        "about" => "About Us",
        "services" => "Services",
        "packages" => "Packages",
        "portfolio" => "Portfolio",
        "contact" => "Contact",
        "login" => "Login",
        "register" => "Register",
        "logout" => "Logout",
        "dashboard" => "Dashboard",

        "hero_title" => "We turn stories into cinematic experiences.",
        "hero_text" => "Film production, commercials, social media, events, graphics, web and digital campaigns.",
        "request_service" => "Request a Service",
        "get_quote" => "Get a Quote",

        "our_services" => "Our Services",
        "welcome" => "Welcome",
        "new_request" => "New Request",
        "project_title" => "Project Title",
        "location" => "Location",
        "budget" => "Estimated Budget",
        "description" => "Project Description",
        "submit_request" => "Submit Request",

        "english" => "English",
        "amharic" => "አማርኛ"
    ],

    "am" => [

        "home" => "መነሻ",
        "about" => "ስለ እኛ",
        "services" => "አገልግሎቶች",
        "packages" => "ፓኬጆች",
        "portfolio" => "ሥራዎቻችን",
        "contact" => "ያግኙን",
        "login" => "ግባ",
        "register" => "ተመዝገብ",
        "logout" => "ውጣ",
        "dashboard" => "ዳሽቦርድ",

        "hero_title" => "ታሪኮችን ወደ ሲኒማዊ ተሞክሮ እንቀይራለን።",
        "hero_text" => "የፊልም ፕሮዳክሽን፣ ማስታወቂያ፣ ሶሻል ሚዲያ፣ የዝግጅት ሽፋን፣ ግራፊክስ፣ ድረ-ገጽ እና ዲጂታል ካምፔይኖች።",
        "request_service" => "አገልግሎት ይጠይቁ",
        "get_quote" => "ዋጋ ይጠይቁ",

        "our_services" => "የእኛ አገልግሎቶች",
        "welcome" => "እንኳን ደህና መጡ",
        "new_request" => "አዲስ ጥያቄ",
        "project_title" => "የፕሮጀክት ርዕስ",
        "location" => "ቦታ",
        "budget" => "የተገመተ በጀት",
        "description" => "የፕሮጀክት መግለጫ",
        "submit_request" => "ጥያቄውን ያስገቡ",

        "english" => "English",
        "amharic" => "አማርኛ"
    ]
];

function t($key) {

    global $translations, $lang;

    return $translations[$lang][$key]
        ?? $translations["en"][$key]
        ?? $key;
}
?>