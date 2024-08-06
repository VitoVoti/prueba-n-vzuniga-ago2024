## 1. Nombre los requerimientos y pasos a seguir para crear un nuevo proyecto Laravel.

Se debe tener un computador con un sistema operativo Windows, Linux o MacOS, en una cuenta con permisos de administrador. Mientras más cercano el sistema sea a un ambiente Unix, mejor. Por ello se recomienda utilizar WSL si se trabaja con Windows.

Como dependencias principales, se necesita PHP (8.1 o superior para las versiones recientes de Laravel), Composer y Node.js
Además, varias extensiones de PHP deben estar instaladas (revisar `https://laravel.com/docs/11.x/deployment#server-requirements`).

Según otras herramientas a usar (ej. Base de datos MySQL/PostgreSQL, Redis, Mailpit) será necesario instalarlas también.
Una manera de simplificar la instalación en un ambiente local es Laravel Sail, la cual es una serie de imágenes Docker y scripts que permiten montar un ambiente de desarrollo sin tener que instalar nada más allá de Docker (y WSL en caso de Windows).

Otra manera es con Laravel Herd, una alternativa "freemium" (varias herramientas adicionales requieren la versión de pago).

Finalmente está Laravel Homestead, que utiliza máquinas virtuales. Hoy en día Sail es una alternativa mejor, al utilizar containers en vez de máquinas virtuales, pero vale mencionarla porque sigue teniendo soporte.

Luego, para generar el proyecto en sí tenemos las siguientes opciones:

1. Si se tiene Docker instalado y se desea usar Sail, se tiene el script automatizado
`curl -s "https://laravel.build/nombre_de_app_a_usar" | bash`
Se pueden añadir servicios adicionales inmediatamente al docker-compose que genera Sail
`curl -s "https://laravel.build/nombre_de_app_a_usar?with=mysql,redis" | bash`

2. Si se tiene PHP y Composer instalado, script de composer 
`composer create-project laravel/laravel nombre_de_app_a_usar`

3. Si se quiere usar Herd, descargar en el sitio web
`https://herd.laravel.com/`

4. Si se quiere usar Homestead, teniendo instalado Vagrant y un software de máquinas virtuales como VirtualBox, se debe descargar Homestead con git y ejecutar el script de instalación
`git clone https://github.com/laravel/homestead.git ~/Homestead`

Detalles para cada opción se pueden ver en la documentación de Laravel.

## 2. ¿A qué se refiere Route-Model-Binding en Laravel? Proporcione el código de un ejemplo.

Es una característica que se incluye en el framework Laravel, que está atada a las interacciones entre rutas, modelos, requests y controladores.
En resumen, varios métodos en Laravel permiten que en forma "mágica", uno pueda utilizar un objeto de un modelo a cuando normalmente uno tiene sólo un Id, ahorrando trabajo de obtener el elemento por ese Id.

### Implícito:
Este proceso funciona en Rutas y Controladores, y saca del tipo de dato que uno defina en los parámetros para hacer el proceso correctamente.

Ejemplo 1
Si llamo a una ruta como articles/edit/5
Aquí se toma el "hint" en los parámetros, que dice que recibimos un Article, y busca inmediatamente el Article con Id 5.
Así, nos ahorramos definir $article y el código necesario para retornar 404 si no se encuentra.
````

Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');

...

public function edit(Article $article)
    {

        return Inertia::render('Articles/Edit', ['article' => $article]);
    }
````

Este RMB se puede personalizar, por ejemplo, si queremos usar slug en vez de id. Revisar la documentación de Laravel para más detalles.

### Explícito:
El Route-Model-Binding explícito funciona según el nombre del modelo (ej. para Articles la ruta debe tener el parámetro {article}), y la personalización es limitada.
Una herramienta más poderasa es hacer RMB explícito, definiendo "closures" en RouteServiceProvider. Allí podemos escoger un identificador y obtener el objeto como queramos, así como manejar los errores, etc.

Ejemplo 2
Si los articles tienen 2 slugs (por ejemplo, uno en inglés y otro en español), y queremos sacar el último que corresponda, podemos crear una función que maneje los 2 casos, e inmediatamente nos de un Article para usarlo en el controlador
````
...

public function boot() {
    parent::boot();
    Route::bind('slug_en_or_es', function($slug) { 
        $posibles_elementos = Article::where('slug_spanish', $slug)
                                    ->orWhere('slug_english', $slug)
                                    ->orderBy('updated_at', 'desc')
                                    ->firstOrFail();
    });
}

...

Route::get('/articles/{slug_en_or_es}/edit', [ArticleController::class, 'edit'])->name('articles.edit');


````

## 3. ¿Cuáles son los pasos por seguir para publicar el proyecto en el repositorio existente suponiendo que ya tiene el proyecto listo?

Se puede trabajar Git sin "remotes" o con varios "remotes".
Por ejemplo, si uno tiene un proyecto sin remote, o con el remote "origin" ya configurado en una cuenta GitHub/GitLab/etc. personal, y uno desea subirlo a otro remote, se pueden seguir estos pasos:

Ej. Queremos llamar al remote "algunaempresa" y nos dieron la URL HTTPS https://gitlab.com/algunaempresa/prueba-n-vzuniga-ago2024.git

git remote add algunaempresa https://gitlab.com/algunaempresa/prueba-n-vzuniga-ago2024.git
git push --set-upstream gitlab --all
git push --set-upstream gitlab --tags

El lío que uno podría tener es acerca de los permisos del repositorio. Si se usa GitHub/GitLab/etc., el administrador de tal repositorio remoto (ej. algunaempresa) debe añadir a uno como colaborador del repo (si es que uno ya tiene una cuenta) o de lleno ingresarlo a la organización.

# 4. Señala que mejoras se pueden hacer:

1. Leve: métodos no usan nombres estándar de Laravel (getUser en vez de show, updateUser en vez de update)
2. Medio: se usa parámetro $id directamente, teniendo que sacar el elemento manualmente y manejar los errores. Repetivivo y propenso a error. Se podría aprovechar Route-Model-Binding.
3. DB::table('users')->find($id); no retorna instancia de modelo User, sino un objeto stdClass, por lo que se pierde la gracia de los métodos adicionales de User.
4. No hay manejo de errores si $id es inválido, o no es número entero.
5. Request en updateUser no tiene ninguna validación, ni usando un Request personalizado ni usando $request->validate()
6. Asignación de atributos una a una, sin usar mass assignment. Si se usara un request validado, y si se definen los campos $fillable, se podría hacer mass assignment (ej. ->fill()) en forma segura y ahorrando código.