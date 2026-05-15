# Portal de Clientes ISP - FG OptiNet

**Estudiante:** Yober Luis Torres Cabezas

## Descripción del Proyecto
Sistema web desarrollado con HTML, PHP y MySQL para la gestión de clientes de un Proveedor de Servicios de Internet. El proyecto implementa un flujo completo de autenticación de usuarios (registro, login, logout) y un panel de administración de perfil ("Mi Cuenta"), priorizando prácticas de seguridad y un diseño responsivo.

## Tecnologías Utilizadas
* **Backend:** PHP.
* **Base de Datos:** MySQL.
* **Frontend:** HTML5 y Bootstrap 5.

## Características de Seguridad Implementadas
Este proyecto cumple estrictamente con las normativas de seguridad web para la gestión de usuarios:
1. **Protección de Contraseñas:** Uso de `password_hash()`para el registro y `password_verify()` para el inicio de sesión y cambio de clave.
2. **Prevención de Inyección SQL:** Implementación nativa de sentencias preparadas (`prepare()` y `bind_param()`) en todas las consultas a la base de datos.
3. **Sanitización y Prevención:** * Limpieza de espacios en blanco (`trim()`).
   * Filtrado de correos electrónicos (`filter_var` con `FILTER_SANITIZE_EMAIL`).
   * Escape de caracteres especiales en la salida de datos (`htmlspecialchars` con `ENT_QUOTES` y `UTF-8`).
4. **Control de Sesiones:** Validación rigurosa de `$_SESSION` para restringir el acceso a páginas privadas (`inicio.php`, `mi_cuenta.php`).
5. **Validación de Duplicidad:** Comprobación en el servidor para evitar el registro de cédulas o correos electrónicos repetidos.

## Estructura de Archivos
* `database.sql`: Script de creación de la base de datos (`ACD1B_TORRES`) y la tabla `usuarios`.
* `conexion.php`: Archivo centralizado para la conexión a la base de datos usando MySQLi.
* `registro.php`: Interfaz y lógica para la creación de nuevas cuentas de cliente.
* `index.php`: Formulario de acceso (Login) y validación de credenciales.
* `inicio.php`: Dashboard principal del usuario con la visualización de los planes de internet y la información corporativa.
* `mi_cuenta.php`: Módulo privado que permite al usuario actualizar sus datos básicos y cambiar su contraseña de forma segura.
* `logout.php`: Script para la destrucción segura de la sesión.
* Imágenes: Fotografías estáticas utilizadas para ilustrar los planes en el inicio.

## Instrucciones de Instalación (Local)
1. Iniciar los módulos de Apache y MySQL en el panel de control de XAMPP.
2. Descargar los archivos.
3. Crear una carpeta llamada ACD1B.TORRES e ingresar los archivos en la carpeta.
4. Copiar la carpeta del proyecto dentro del directorio `C:\xampp\htdocs\`.
5. Abrir phpMyAdmin (`http://localhost/phpmyadmin/`), seleccionar la pestaña SQL y ejecutar el código del archivo `database.sql`.
6. Acceder al proyecto desde el navegador web ingresando a: `http://localhost/ACD1B.TORRES/index.php`
7. Credenciales de usuario ya creado: luistorres.20199@gmail.com - Lobeluis2025@
