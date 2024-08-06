## prueba-n-vzuniga-ago2024

Desarrollado en Laravel 11, basado en Breeze con Vue+Inertia y PHPUnit

Bibliotecas utilizadas
Backend:
* GuzzlePHP


Información del proyecto
* No se usa bibliotecas para manejar roles ni permisos (autorización). Se usa una tabla "roles" muy simple y para autorización se usan Policies y middleware en rutas.
* Envío de correos y tareas que usan API de GitHub utilizan colas, por lo que se deben correr para que funcionen (queue:work)
* Validación mediante Requests personalizadas, en app/Http/Requests
* Rutas POST de autenticación tienen throttle
* Acceso a API de Github utiliza personal access token (en .env, GITHUB_PERSONAL_ACCESS_TOKEN) para evitar problemas de throttle
* 

Otros
* Fuente definida en css, se saca de Google Fonts
