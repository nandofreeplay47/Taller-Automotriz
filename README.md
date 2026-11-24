# Mecánica Rafael - Sistema Web y Panel Administrativo

Aplicación web Full-Stack para la gestión de citas y solicitudes de un taller automotriz.

## Tecnologías utilizadas
- **Frontend:** HTML5, CSS, JavaScript (Fetch API)
- **Backend:** PHP 8 (Sesiones, consultas preparadas `mysqli`, encriptación `bcrypt`)
- **Base de datos:** MySQL
- **Servidor:** Apache2 en Ubuntu Linux

## Funcionalidades
- Landing page responsiva con formulario de reserva de citas conectado a MySQL.
- Sistema de autenticación segura (`login.php`) con contraseñas hasheadas.
- Panel administrativo (`admin.php`) con operaciones CRUD completas (Listar, Editar y Eliminar solicitudes).

## Instalación local
1. Importa el archivo `database.sql` en tu servidor MySQL.
2. Copia `config.example.php` como `config.php` y configura tus credenciales.
