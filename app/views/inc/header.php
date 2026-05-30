<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] . ' - ' . SITENAME : SITENAME; ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-fixed": "#002113",
                        "inverse-surface": "#213145",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#d3e4fe",
                        "on-tertiary-container": "#3fd298",
                        "on-primary-container": "#a8b8ff",
                        "inverse-on-surface": "#eaf1ff",
                        "primary": "#00288e",
                        "on-secondary": "#ffffff",
                        "surface-container-high": "#dce9ff",
                        "on-surface-variant": "#444653",
                        "surface": "#f8f9ff",
                        "on-background": "#0b1c30",
                        "primary-fixed": "#dde1ff",
                        "inverse-primary": "#b8c4ff",
                        "outline-variant": "#c4c5d5",
                        "surface-variant": "#d3e4fe",
                        "on-primary": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "surface-dim": "#cbdbf5",
                        "surface-container": "#e5eeff",
                        "primary-container": "#1e40af",
                        "error": "#ba1a1a",
                        "secondary": "#a93349",
                        "surface-tint": "#3755c3",
                        "secondary-fixed-dim": "#ffb2b9",
                        "primary-fixed-dim": "#b8c4ff",
                        "tertiary-fixed-dim": "#4edea3",
                        "secondary-container": "#fe7488",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#0b1c30",
                        "on-secondary-fixed-variant": "#891933",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-primary-fixed": "#001453",
                        "on-secondary-container": "#730425",
                        "outline": "#757684",
                        "on-secondary-fixed": "#400010",
                        "surface-container-low": "#eff4ff",
                        "tertiary-container": "#00563a",
                        "tertiary": "#003d27",
                        "on-error-container": "#93000a",
                        "on-primary-fixed-variant": "#173bab",
                        "secondary-fixed": "#ffdadc",
                        "tertiary-fixed": "#6ffbbe",
                        "surface-bright": "#f8f9ff",
                        "error-container": "#ffdad6",
                        "background": "#f8f9ff"
                    },
                    "borderRadius": {
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "lexend": ["Lexend"],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: "Lexend", sans-serif;
        }
        .graph-paper-bg {
            background-color: #f8f9ff;
            background-image: linear-gradient(#e5eeff 1px, transparent 1px),
            linear-gradient(90deg, #e5eeff 1px, transparent 1px);
            background-size: 32px 32px;
        }
    </style>
</head>
<body class="bg-gray-50">
