<?php

/**
 * Script para generar capturas de pantalla automáticas para el README.md
 * 
 * Para usar:
 * 1. Asegúrate de que el servidor esté ejecutándose (php artisan serve)
 * 2. Ejecuta: php capture-screenshots.php
 */

// URL base de la aplicación
$baseUrl = 'http://localhost:8000';

// Directorio para guardar las capturas
$screenshotsDir = __DIR__ . '/docs/screenshots';

// Asegúrate de que el directorio exista
if (!is_dir($screenshotsDir)) {
    mkdir($screenshotsDir, 0755, true);
}

// Configuración de capturas
$screenshots = [
    [
        'name' => 'dashboard.png',
        'url' => $baseUrl . '/admin/dashboard',
        'description' => 'Panel de Control'
    ],
    [
        'name' => 'dynamic-qrcodes.png',
        'url' => $baseUrl . '/admin/dynamic-q-r-codes',
        'description' => 'Lista de QR Dinámicos'
    ],
    [
        'name' => 'static-qrcodes.png',
        'url' => $baseUrl . '/admin/static-q-r-codes',
        'description' => 'Lista de QR Estáticos'
    ],
    [
        'name' => 'dynamic-qr-create.png',
        'url' => $baseUrl . '/admin/dynamic-q-r-codes/create',
        'description' => 'Creación de QR Dinámico'
    ],
    [
        'name' => 'static-qr-create.png',
        'url' => $baseUrl . '/admin/static-q-r-codes/create',
        'description' => 'Creación de QR Estático'
    ],
    [
        'name' => 'qr-customization.png',
        'url' => $baseUrl . '/admin/dynamic-q-r-codes/create#advanced',
        'description' => 'Personalización Avanzada'
    ],
    [
        'name' => 'users.png',
        'url' => $baseUrl . '/admin/users',
        'description' => 'Gestión de Usuarios'
    ]
];

// Verifica si Chrome está disponible
$chromeAvailable = isChromiumAvailable();

if (!$chromeAvailable) {
    echo "⚠️ No se encontró Chrome/Chromium en el sistema. Las capturas de pantalla no se pueden generar automáticamente.\n";
    echo "Sigue estas instrucciones para generar capturas manualmente:\n\n";
    
    foreach ($screenshots as $screenshot) {
        echo "1. Abre esta URL en tu navegador: {$screenshot['url']}\n";
        echo "2. Toma una captura de pantalla manualmente\n";
        echo "3. Guárdala como: {$screenshotsDir}/{$screenshot['name']}\n";
        echo "4. Esta captura representa: {$screenshot['description']}\n\n";
    }
    
    exit;
}

echo "📷 Iniciando captura de pantallas...\n";

// Primero realizar login para obtener una sesión
performLogin($baseUrl);

// Tomar las capturas de pantalla
foreach ($screenshots as $index => $screenshot) {
    echo "Capturando {$screenshot['name']}... ";
    
    $fullPath = $screenshotsDir . '/' . $screenshot['name'];
    if (captureScreenshot($screenshot['url'], $fullPath)) {
        echo "✅ Guardada en {$fullPath}\n";
    } else {
        echo "❌ Error al capturar\n";
    }
    
    // Pequeña pausa para no sobrecargar el servidor
    if ($index < count($screenshots) - 1) {
        sleep(1);
    }
}

echo "\n✨ ¡Listo! Las capturas están en el directorio {$screenshotsDir}\n";
echo "Puedes usarlas en tu README.md con la sintaxis:\n";
echo "![Descripción](docs/screenshots/nombre-archivo.png)\n";

/**
 * Verifica si Chrome o Chromium está disponible en el sistema
 */
function isChromiumAvailable() {
    // Verificar en sistemas Linux/Mac
    exec('which google-chrome chrome chromium chromium-browser', $output, $returnCode);
    
    // En Windows sería diferente, pero este ejemplo es simple
    return $returnCode === 0 && !empty($output);
}

/**
 * Realiza el login para obtener una sesión
 */
function performLogin($baseUrl) {
    // Este sería un ejemplo simplificado - en la práctica se necesitaría usar cURL o Guzzle
    // para manejar correctamente cookies, tokens CSRF, etc.
    echo "Iniciando sesión en el sistema... (simulado)\n";
    // En una implementación real, aquí haríamos una solicitud POST para iniciar sesión
}

/**
 * Captura una pantalla usando Chrome headless
 */
function captureScreenshot($url, $outputPath) {
    // Comando para Chrome headless
    $command = sprintf(
        'google-chrome --headless --disable-gpu --screenshot="%s" --window-size=1280,800 "%s" 2>/dev/null',
        $outputPath,
        $url
    );
    
    // Ejecutar el comando
    exec($command, $output, $returnCode);
    
    return $returnCode === 0;
}