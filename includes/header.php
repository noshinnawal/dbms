<?php
/**
 * Global Header
 * Centralizes neomorphic design tokens, fonts, and Tailwind CDN
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo isset($pageTitle) ? $pageTitle . " - EduSoft SMS" : "EduSoft SMS"; ?></title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS (via CDN as per prototype) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-bright": "#f5faff",
                        "on-primary": "#ffffff",
                        outline: "#707979",
                        "tertiary-container": "#ffefea",
                        "on-secondary-container": "#7a4342",
                        "surface-container-highest": "#dbe4eb",
                        primary: "#396568",
                        "on-primary-fixed": "#002022",
                        background: "#f5faff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container": "#e6eff6",
                        "on-tertiary-fixed": "#2a1710",
                        "primary-container": "#ccfbfe",
                        "tertiary-fixed": "#ffdbcf",
                        error: "#ba1a1a",
                        "on-tertiary-fixed-variant": "#594139",
                        "on-background": "#141d22",
                        "secondary-fixed": "#ffdad8",
                        "secondary-container": "#feb4b1",
                        "on-primary-fixed-variant": "#1f4d50",
                        "secondary-fixed-dim": "#feb4b1",
                        "on-secondary-fixed": "#360d0e",
                        "surface-container-low": "#ecf5fc",
                        "inverse-surface": "#293237",
                        "on-primary-container": "#497578",
                        "outline-variant": "#c0c8c8",
                        "surface-tint": "#396568",
                        secondary: "#884e4d",
                        surface: "#f5faff",
                        "on-secondary": "#ffffff",
                        "surface-container-high": "#e0e9f0",
                        "on-tertiary-container": "#84685e",
                        "primary-fixed-dim": "#a1cfd2",
                        "on-tertiary": "#ffffff",
                        "inverse-primary": "#a1cfd2",
                        tertiary: "#73584f",
                        "surface-dim": "#d2dbe2",
                        "primary-fixed": "#bcebee",
                        "on-secondary-fixed-variant": "#6c3737",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#e1bfb4",
                        "on-surface-variant": "#404849",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",
                        "on-surface": "#141d22",
                        "inverse-on-surface": "#e9f2f9",
                        "surface-variant": "#dbe4eb"
                    },
                    fontFamily: {
                        body: ["Lexend", "sans-serif"],
                        headline: ["Lexend", "sans-serif"]
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #e6eff6; /* surface-container */
            font-family: 'Lexend', sans-serif;
        }
        
        /* Neomorphic Utility Classes */
        .neo-raised {
            shadow-[16px_16px_32px_#dbe4eb,-16px_-16px_32px_#ffffff];
        }
        .neo-sunken {
            shadow-[inset_6px_6px_12px_#dbe4eb,inset_-6px_-6px_12px_#ffffff];
        }
    </style>
</head>
<body class="min-h-screen antialiased">
