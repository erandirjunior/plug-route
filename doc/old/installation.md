# Installing the PlugRoute library

## Requirements
* PHP 7.1 >=
* Composer 

## Install
```bash
composer require erandir/plug-route:v4.4
```

> The PlugRoute use PlugHttp library to handler request, response, cookie, session and others values.

> If you want to use this library without virtualhost or the embedded php server, add the **.htaccess** file that is in the example folder, at the root of the project. Modify line 49 setting the folder path.

> If you want to run the examples, clone this project and run this command:
```bash
docker-compose up
```

* [Defining Routes](defining-routes.md)
* [Request](request.md)
* [Response](https://github.com/erandirjunior/plug-http/blob/master/doc/response.md)