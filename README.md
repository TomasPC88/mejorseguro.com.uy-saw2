<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)
- [Appoly](https://www.appoly.co.uk)
- [OP.GG](https://op.gg)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
<br/>

<img src="public/assets/admin/img/sitios_logo/imago/sitios_c_512.png" height="100">

## SitiosAW2
> Gestor base con Laravel 6.2

### Gu??a de desarrollo

#### Creaci??n de secciones

Para poder crear secciones de forma autm??tica que se proyecten en el panel de administraci??n
de AW2 cuenta con un comando de artisan para el incio del proceso.
```bash
php artisan create:section <!-nombre_seccion->
```

Luego de ser ejecutado el comando el sistema espera la entrada de los atributos del modelo
de datos de la secci??n a crear, de acuerdo con el tipado de las migraciones de Laravel. (V??ase debajo)

```bash
.:::Liste los atributos de la seccion a generar, con el formato "nombre=tipo_de_dato", separados por coma:::. 
Tipos: bigIncrements,bigInteger,binary,boolean,char,date,double,float,increments,integer,longText,mediumInteger,dateTime,decimal,mediumText,smallInteger,tinyInteger,string,text,time:
 > name=string,descripcion=text,destacado=boolean  
```
En el proceso de generaci??n el sistema pide entrada de interrogantes que facilitan la customizaci??n del resultado.
Al final del proceso, se preguntar?? por la ejecuci??n de la migraci??n a DB.

#### Roles y permisos para las secciones

##### Rese??a
Luego de ser creada la secci??n por v??a consola de artisan, se generan y se guardan en DB los permisos con su rol 
asociado a la secci??n ya guardada. Para la administraci??n de los permisos y roles del sistema, es declarado el 
usuario administrativo de soporte con Rol <b>super-admin</b> el cual puede asignar los roles a los usuarios administrativos
que podr??an encargarse de una o varias secciones del panel.
#####Orden de permisos
Ahora, por cada secci??n son creados los siguientes permisos
* <b>listar</b> Permiso para acceder a la vista del listado de elementos de la secci??n
* <b>nuevo</b> Permiso para acceder a la vista de creaci??n de un elemento
* <b>editar</b> Permiso para acceder a la vista de creaci??n de un elemento
* <b>crear</b> Permiso para crear un elemento       
* <b>actualizar</b> Permiso para actualizar un elemento
* <b>borrar:</b> Permiso para eliminar un elemento
* <b>rol-<!-seccion></b> Rol por defecto creado que contiene todos los permisos anteriores

Los roles pueden ser creados con la combinaci??n de permisos deseados y ser asignado al usuario para de esta manera
definir el nivel de acceso.

>En caso de no generarse la secci??n con el comando de artisan es necesario incluir en DB los permisos + rol manualmente o con el comando de artisan:
```bash
php artisan create:permissions <!-nombre_seccion->
```
><small><b>Recuerde siempre limpiar la cach?? de rutas para que laravel detecte la nueva secci??n creada</b></small>

