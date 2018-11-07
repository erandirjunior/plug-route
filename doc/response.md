# Working with response

## Manipulating response
```php
$route->put('/people/{id}', function ($request, $response) {
    $response->setStatus(404); // Default 200
    $reponse->setContentType(''application/json'); // Default 'application/json'
    echo $response->responseAsJson(['nome' => 'Erandir']);
});
```