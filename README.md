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
* Filtros, paginación y ordenamientos son todos por frontend, con Javascript. No se hacen por backend.

Posibles mejoras que se podrían hacer
* Filtros, ordenamiento y paginación por backend, ya que si tenemos muchos elementos, las operaciones en frontend se ponen lentas, y las llamadas a index también se hacen muy pesadas.
* Añadir recaptcha o hcaptcha a los formularios visibles al público (login, registro, recuperar contraseña, etc.)
* Afinar detalles de estilo (ej. checkbox personalizados, espaciado)
* Hacer que vistas por defecto de Breeze se apeguen más a los componentes PrimeVue (ej. en /profile se usa modal que viene con Breeze, pero en el resto de la aplicación se usa Dialog de PrimeVue)
* Mejorar uso de nombres de columnas (ej. que todos usen "title" en vez de que unos usen "title" y otros "name")
* Utilizar caché en otras partes (en este momento solo se usa en hasRole de modelo User)
* Hacer más unit tests
* Tests end-to-end con Selenium

## Algunos comandos y links útiles

Si uno quiere ejecutar el ambiente local con Sail pero no tiene instalado PHP ni composer
''''
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
''''

Para obtener App Password de Google y poder enviar correos con cuenta Gmail (llenar en .env)
`https://myaccount.google.com/apppasswords`
''''
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME='ACA_CORREO_GMAIL'
MAIL_PASSWORD='ACA_APP_PASSWORD'
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS='ACA_CORREO_GMAIL'
MAIL_FROM_NAME="${APP_NAME}"
''''

Para obtener personal access token de GitHub (llenar en .env, en key GITHUB_PERSONAL_ACCESS_TOKEN)
`https://github.com/settings/tokens?type=beta`

Borrar todo en BD, ejecutar migraciones de 0 y ejecutar seeds por defecto
''''
sail php artisan migrate:fresh
sail php artisan db:seed
''''

Ejecutar seeds adicionales
`sail php artisan db:seed --class=TestUserSeeder`

Ejecutar sólo 1 test
`vendor/bin/sail phpunit --filter AdminCRUDTest`

Crear usuario laravel, BD con nombre laravel y dar acceso, en versiones nuevas de PostgreSQL
''''
su - postgres
psql
CREATE USER laravel WITH PASSWORD 'ACA_PASSWORD';
create database laravel;
grant all privileges on database laravel to laravel;
\c laravel
GRANT CREATE ON SCHEMA public TO laravel;
''''

