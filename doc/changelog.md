# Changelog

### 4.6
* Fixed documentation
* Removed unnecessary code
* Improvements in return type

### 4.5
* Refactored all PlugRoute library interface
* Imported all classes from PlugHttp
* Added new documentation
* Saved old documentation in a new folder
* Applied tests in all the library
* Removed routes from xml file
* Removed routes from json file

### v4.4
* Fixed parameters types

### v4.3
* Update dependencies

### v4.2
* Ending support to PHP < 7.4
* Sending dynamic Request classes

### v4.1
* Now It's possible get parameter without use Request class
* Refactored RouteAnalyzer
* Deleted DynamicRoute and SimpleRoute classes
* Created RouteFactory class
* Refactored RouteManager, PlugRoute and MatchHelper classes
* Refactored tests

### v4.0
* Refactored Callback class
* Refactored PlugRouteMiddleware interface
* Fix name methods

### v3.9
* Fix bug in dynamic route without regex
* Created class Route to storage a route
* Created class RouteStorage to storage all routes
* Refactored DynamicRoute, RouteManager, RouteContainer and Callback classes

### v3.8
* Update dependency version

### v3.7
* Added sending injection values
* Refactory in DynamicRoute class
* Replaced interface Router with abstract class RouterAnalyzer
* Replaced class name PlugHelper with MatchHelper
* Removed unnecessary method

### v3.6
* Added xml route
* Improvements on error messages

### v3.5
* Added new example
* Update dependencies
* Fix bug on clear middlewares
* Fix bug on json routes

### v3.4
* Added method do load routes from json
* Fixed bug when parameter has value equal 0
* Added new examples
* Rename key name route group
* Added new tests
* Update documentation

### v3.3
* Added redirect route
* Rename Route class to RouteContainer
* Created DynamicRoute class to handler dynamic routes
* Created SimpleRoute class to handler simple routes
* Created RouteManager to manager routes
* Created Router interface to standardize route handlers
* Added parameters on PlugRoute constructor
* Created addNamedRoute method to get routes with name defined
* Added return on exceptions throwers
* Rename method setRouteNamed to setRouteNamed in Request class
* Rename method getErrorRoute to getNotFound on PlugRoute class
* Rename method error to notFound on PlugRoute class
* Rename method getErrorRoute to getErrorRouteNotFound on RouteContainer class
* Request class extends Request from PlugHttp lib
* Response class extends Response from PlugHttp lib
* Created RequestCreator class to create Request class

### v3.2
* Improvements in the route group
* Added redirect route
* Improvement when obtaining data of the formdata type
* Added parameter on construct

### v3.1
* Added namespace method
* Improvements in the route flow
* Added new method to set route error
* Now, you can return values
* More tests

### v3.0
* Added options method
* Added match method
* Fixed bugs when used groups within groups

### v2.9
* Added dependency injection
* Fixed bug dynamic route
* Fixed bugs on constructor parameter
