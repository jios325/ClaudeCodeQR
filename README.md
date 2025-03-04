# QR Code Generator

Una aplicación web completa para la generación y gestión de códigos QR estáticos y dinámicos, desarrollada con Laravel 12 y Filament.

## Características Principales

- **Panel de administración** con estadísticas en tiempo real
- **Códigos QR dinámicos** que pueden actualizarse después de generados
- **Códigos QR estáticos** con diversos tipos de contenido
- **Personalización avanzada** con logos, colores, gradientes y estilos
- **Seguimiento de escaneos** con analíticas completas
- **Gestión de usuarios** con roles de administrador y superadministrador

## Tipos de Códigos QR Soportados

### Códigos QR Dinámicos
Los códigos QR dinámicos utilizan un identificador único que redirige a la URL deseada. La URL de destino puede ser modificada en cualquier momento sin necesidad de crear un nuevo código QR.

### Códigos QR Estáticos
Los códigos QR estáticos codifican directamente su contenido y soportan múltiples formatos:
- Texto plano
- URLs
- Email (con asunto pre-definido)
- Teléfono
- SMS
- WhatsApp
- Ubicación geográfica
- WiFi
- Y muchos más...

## Opciones de Personalización

- **Colores**: Personaliza los colores del código y del fondo
- **Gradientes**: Aplica gradientes de color a tus códigos QR
- **Logos**: Añade tu logotipo en el centro del código QR
- **Estilos**: Elige entre diferentes estilos (cuadrado, redondeado, puntos)
- **Precisión**: Configura el nivel de corrección de errores
- **Tamaño**: Selecciona el tamaño de salida del código QR

## Requisitos del Sistema

- PHP 8.2 o superior
- Composer
- MySQL 5.7+ o SQLite
- Node.js y NPM (para los assets)
- Extensiones PHP: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, Fileinfo

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tu-usuario/qrcode-generator.git
   cd qrcode-generator
   ```

2. Instala las dependencias:
   ```bash
   composer install
   npm install && npm run build
   ```

3. Configura el entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Actualiza tu archivo `.env` con la información de tu base de datos:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=qrgenerator
   DB_USERNAME=root
   DB_PASSWORD=tu_contraseña
   ```

5. Ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

6. Crea un superadministrador:
   ```bash
   php artisan qr:create-admin
   ```

7. (Opcional) Carga datos de ejemplo:
   ```bash
   php artisan qr:seed-examples
   ```

8. Inicia el servidor:
   ```bash
   php artisan serve
   ```

9. Accede a la aplicación en tu navegador:
   ```
   http://localhost:8000
   ```

## Credenciales por defecto

Después de ejecutar el comando para crear el administrador, podrás acceder con:

- **Email**: admin@example.com
- **Password**: qrcode123

## Funcionamiento del Sistema

### Códigos QR Dinámicos

Cuando se crea un código QR dinámico, el sistema:
1. Genera un identificador único
2. Crea una URL especial con ese identificador
3. El código QR contiene esa URL especial, no la URL de destino
4. Cuando alguien escanea el código, el sistema redirige a la URL de destino
5. La URL de destino puede cambiarse en cualquier momento

### Sistema de Escaneo

Cada vez que se escanea un código QR:
1. Se registra la fecha y hora del escaneo
2. Se almacena información como la dirección IP y el agente de usuario
3. Se incrementa el contador de escaneos para ese código
4. Se muestran estadísticas en tiempo real en el panel de administración

## Estructura de Directorios

- `app/` - Lógica de la aplicación
- `app/Models/` - Modelos Eloquent
- `app/Filament/` - Recursos, páginas y widgets de Filament
- `app/Http/Controllers/` - Controladores
- `database/migrations/` - Migraciones de la base de datos
- `public/` - Archivos accesibles públicamente
- `resources/` - Vistas y assets
- `routes/` - Definiciones de rutas
- `storage/` - Archivos generados por la aplicación

## Tecnologías Utilizadas

- **Laravel 12**: Framework de PHP para el backend
- **Filament**: Panel de administración moderno
- **MySQL/SQLite**: Base de datos
- **Tailwind CSS**: Framework CSS para el diseño
- **Alpine.js**: Para interactividad en el frontend

## Licencia

Este proyecto está licenciado bajo [MIT License](LICENSE).

---

Desarrollado con ❤️ usando [Laravel](https://laravel.com) y [Filament](https://filamentphp.com)
