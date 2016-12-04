## Storage Project

### Dependencias del proyecto

1) Apache con PHP >= 5.6 + [Todas las dependencias de laravel 4.2 (Composer es la principal)]
2) Un esquema en mysql (Ajustar la información de conexión en app/config/database.php)
3) Redis

### Setup

En el directorio raíz ejecutar

$ composer install

Luego, también en el directorio raíz

$ php artisan migrate --seed

Luego para ejecutar las pruebas Unitarias:

$ phpunit --verbose

Finalmente en apache necesitamos configurar el directorio que exponemos,  para que apunte al directorio public dentro del directorio raíz.

Para establecer comunicación segura con la aplicación es necesario configurar HTTPs dentro de apache. [Apache + HTTPs](https://httpd.apache.org/docs/2.4/ssl/ssl_howto.html)


### Cómo consumir el servicio

Exponemos un servicio tipo REST con dos operaciones store (POST) y get (GET) que respectivamente guardan/regresan un valor que guardamos cifrado por medio de nuestro servicio.

Para poder consumir el servicio es necesario tener un usuario. En el seeder del proyecto creamos dos que tienen las siguientes credenciales:

first@user.com, firtuser_password
second@user.com, second_password

Podemos probar con curl de la siguiente manera

 $ curl --user first@user.com:first_password  -d "source_data=03218000011835971" https://localhost:18000/api/v1/storage/store

 Dicho comando regresa una respuesta tipo json como la siguiente

 {"success":true,"identifier":"5075042ff9840a7c465cbfdf18c1a677ba157c53"}

 Ahora para consumir el dato que almacenamos en la base es necesario guardar el atributo identifier que obtuvimos en la respuesta de la petición anterior. Para leer el valor podemos probar con curl de la siguiente manera:

 curl --user first@user.com:first_password https://localhost:18000/api/v1/storage/get/5075042ff9840a7c465cbfdf18c1a677ba157c53

Dicho comando regresa el siguiente resultado:

{"success":true,"data":"03218000011835971"}

## Consideraciones de diseño

### Storage

Con el fin de abstraer la funcionalidad que vamos a proveer independiente mente de cómo la vayamos a consumir, creamos la siguiente clase:

app/services/StorageService.php

Esta es una clase utilitaria en la que realizamos operaciones para guardar y leer información en REDIS. Por medio de esta clase podemos almacenar cuaquier valor u objeto que sea serializable.
Guardamos la informacion de manera cifrada por medio de AES, que soporta directamente el framework. Es importante que si se ejecuta la aplicación de forma distribuida todas las instancias tengan la misma llave definida en el archivo app/config/app.php.

El identificador lo generamos a partir de email del usuario que consume el servicio al aplicarle la función hash SHA1. 

El tiempo de expiración de los objetos que almacenamos en redis se encuentra en el archivo app/config/storage.php

Incluimos este servicio dentro del IoC Container como un singleton por medio de la clase app/providers/StorageServiceProvider.php y registramos esta clase en app/config/app.php en el arreglo $providers.

Luego crearmos un interfaz tipo Facade en app/facades/Storage.php para consumir los métodos que definimos sobre el servicio StorageService por medio de métodos estáticos. Esta parte es muy importante para poder hacer Mockup objects en las pruebas unitarias y para mejorar el rendimiento de la aplicación en general, pues generamos un singleton stateless para compartir entre todos los hilos que atiendan peticiones dentro de una misma instancia/servidor.

Finalmente las pruebas unitarias de este servicio se encuentran en app/tests/StorageTest.php. Los parámetros de configuración del servicio para ejecutar pruebas se encuentran en app/config/testing/storage.php

### Exposición

Dado que no conseguí exponer la funcionalidad del servicio por medio de websockets (es algo engorroso en laravel 4.2, aunque ya viene out-of-the-box en laravel 5.3), cree una servicio REST para poder interactuar con él por medio del protocolo HTTPs. Dicho servicio se encuentra en app/controller/api/v1/storage/ApiStorageController.php, que básicamente es un controlador que funciona como mediador entre un cliente HTTP y nuestro servicio Storage.

Las pruebas para este controlador se encuentran en el archivo app/tests/ApiStorageControllerTest.php. Las llamadas al servicio se realizan mendiante la Facade Storage.
