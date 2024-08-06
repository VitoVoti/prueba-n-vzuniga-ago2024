## prueba-n-vzuniga-ago2024

Desarrollado en Laravel 11, basado en Breeze con Vue+Inertia, Tailwind CSS y PHPUnit

Bibliotecas extras utilizadas
Backend:
* GuzzlePHP
* laravel-lang

Frontend:
* PrimeVue
* PrimeIcons
* @leeoniya/ufuzzy


Información del proyecto
* No se usa bibliotecas para manejar roles ni permisos (autorización). Se usa una tabla "roles" muy simple y para autorización se usan Policies y middleware en rutas.
* Envío de correos y tareas que usan API de GitHub utilizan colas, por lo que se deben correr para que funcionen (queue:work)
* Varios métodos típicos en controladores (como create de ArticleController) no están porque esto se maneja en modales en la misma vista de index
* Validaciones se hacen mediante Requests personalizadas, en app/Http/Requests
* Rutas POST de autenticación tienen throttle
* Acceso a API de Github utiliza personal access token (en .env, GITHUB_PERSONAL_ACCESS_TOKEN) para evitar problemas de throttle
* En vistas se mantienen varios componentes de Vue que vienen con Breeze. Parte de los layouts y headers también son de Breeze.
* Fuente se saca de Google Fonts, se define en archivo css
* No se utiliza archivos css ni scss para estilos, solo Tailwind. Ciertas customizaciones están en la configuración de Tailwind (tailwind.config.js) y en la configuración de PrimeVue (en app.js)
* Filtros y ordenamientos son todos por frontend, con Javascript. No se hacen filtrados ni ordenamientos por backend.

Otros

