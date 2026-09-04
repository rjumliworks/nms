<!DOCTYPE html>
<html lang="en" data-theme="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title inertia>NMS - Fishing Management System</title>
    <meta name="description"
        content="NMS Fishing Management System for managing fishing operations, vessel expenses, carrier expenses, sales, cash advances, and other business transactions.">
    <meta name="keywords"
        content="NMS, fishing, fishing operations, fishing management, vessel management, fishing expenses, sales, cash advance">
    <meta name="author" content="NMS"> <!-- Open Graph -->
    <meta property="og:title" content="NMS - Fishing Management System">
    <meta property="og:description"
        content="A centralized management system for NMS fishing operations, expenses, sales, cash advances, and vessel-related transactions.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ URL::asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url('/') }}"> <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="NMS - Fishing Management System">
    <meta name="twitter:description" content="Management system for NMS fishing operations and business transactions.">
    <meta name="twitter:image" content="{{ URL::asset('images/logo.png') }}"> <!-- Favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('images/favicon.ico') }}"> <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/1.8.349/pdf.min.js"></script> <!-- Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    @vite(['resources/js/app.js']) @inertiaHead
</head>

<body class="font-sans antialiased"> @inertia </body>

</html>
