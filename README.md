### introduce

<p>The kitten Router is a Lightweight and easy to use library.It is based on a mature symfony/routing component</p>
<p>It has the following features:</p>

* Write routing with similar laravel writing, coding friendly and intuitive.
* No constraint controller and middleware，You can define it the way you want it.
* Do not process HTTP requests, only responsible for routing filtering, So you can choose the framework very freely, such as zend-diactoros、symfony/http-foundation.

### Installation
<p>Install via composer</p>

> composer require kittenphp/router

Usage
------
#### Quick Start
```php
<?php
require __DIR__.'/vendor/autoload.php';

use kitten\component\router\RouteCollector;
use kitten\component\router\RouteTracker;

$url=$_SERVER['REQUEST_URI'];
//$url='/news';
$router= new RouteCollector();
$router->get('/','HomeController@index');
$router->get('/news','NewsController@index');
$tracker=new RouteTracker($router->getRouteNodes());
$result=$tracker->search($url,'GET');
if (is_null($result)){
    //No matching routes found
    echo '404 Not Found';
}else{
    $controller=$result->getRouteNode()->getCallable();
    echo $controller; //Output: 'NewsController@index'
}
```

#### Defining routes
```php
$router= new RouteCollector();
$router->get($uri, $callback);
$router->post($uri, $callback);
$router->put($uri, $callback);
$router->patch($uri, $callback);
$router->delete($uri, $callback);
$router->options($uri, $callback);
```
<p>Sometimes you may need to register a route that responds to multiple HTTP verbs. You may do so using the match method.</p>

`$router->match(['GET','POST'],'/page','PageController@index');`

<p>$callback can be of any type, such as string,arrays,closures and so on.</p>

```php
$router->get('/',function (){
   return 'hello world'; 
});
```
The following is a complete code example that sets $callback to closures:

```php
$url='/';
$router= new RouteCollector();

$router->get('/',function (){
    return 'hello world!';
});

$tracker=new RouteTracker($router->getRouteNodes());
$result=$tracker->search($url,'GET');
if (is_null($result)){
    //No matching routes found
    echo '404 Not Found';
}else{
    $controller=$result->getRouteNode()->getCallable();
    echo $controller();    //Screen display： 'hello world!'
}
```

#### Regular Expression 
<p>To match a path by using a regular expression</p>

`$router->get('/article/{id}','ArticleController@get')->where(['id'=>'[0-9]+']);`

<p>You can match multiple snippets in your code:</p>

`$router->get('/article/{year}/{month}','ArticleController@get')->where(['year'=>'[0-9]{4}','month'=>'[0-9]{2}']);`

<p>The RouteResult class can get the value of the parameter matching the path</p>

```php
$url='/article/2010/01';
$router= new RouteCollector();
$router->get('/article/{year}/{month}','ArticleController@get')->where(['year'=>'[0-9]{4}','month'=>'[0-9]{2}']);

$tracker=new RouteTracker($router->getRouteNodes());
$result=$tracker->search($url,'GET');
$args=$result->getCallParameters();
print_r($args);  //print 'Array ( [year] => 2010 [month] => 01 )'
```

#### Route Groups
<p>By routing Groups to share some of the attributes, such as url, middleware and so on.</p>

```php
$router->group('/article',function (RouteCollector $router){    
    $router->get('/','ArticleController@index');           //Matching path：/article/
    $router->get('/add','ArticleController@add');          //Matching path：/article/add
    $router->get('/remove','ArticleController@remove');    //Matching path：/article/remove
});
```

<p>Routing Groups can be nested at multiple levels</p>

```php
$router->group('/article',function (RouteCollector $router){
   $router->group('/business',function (RouteCollector $router){
      $router->get('/add','ArticleController@add'); //Matching path：/article/business/add
   });
});
```

#### Middleware
<p>Kitten Router does not limit how you implement middleware processing,You can easily apply it in own framework,For example:StackPHP,PSR-7...</p>

```php
$router->group('/admin',function (RouteCollector $router){
    $router->get('/','Admin@index');
})->middleware('Auth');
```
<p>Get middleware for matching routes</p>

```php
$tracker=new RouteTracker($router->getRouteNodes());
$result=$tracker->search($url,'GET');
$m= $result->getRouteNode()->getMiddleware();
print_r($m);      //Print out: Array ( [0] => Auth )
```

<p>Middleware can also be defined in routing nodes</p>

`$router->get('/admin','Admin@index')->middleware('Auth');`

<p>You can define multiple middleware at the same time, only need to pass an array as a parameter</p>

```php
$router->get('/admin','Admin@index')->middleware(['MiddleA','MiddleB','MiddleC']);
```

#### Generate a URL
<p>To generate a URL path by the name of the route</p>

```php
$router= new RouteCollector();
$router->get('/article/{year}/{month}','ArticleController@get')->where(['year'=>'[0-9]{4}','month'=>'[0-9]{2}'])->setName('ReadArticle');

$tracker=new RouteTracker($router->getRouteNodes());
$url= $tracker->generateUrl('ReadArticle',['year'=>'2020','month'=>'08']);
echo $url;  //print out: '/article/2020/08'
```