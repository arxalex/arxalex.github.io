<?php

namespace framework\routing;

class Router
{
    private const MODULES_NAMESPACE = "controllers\\";

    public static function resolveRoute(string $uri_string, string $systemPath)
    {
        $routeRegistrer = new RouteRegister("$systemPath/routes.json");
        $class = $routeRegistrer->getClassByRoute($uri_string);

        if ($class == null) {
            http_response_code(404);
            exit;
        } else {
            $params = json_decode(file_get_contents("php://input"), true);

            $className = self::MODULES_NAMESPACE . $class['className'];
            $classObject = new $className();
            $classObject->systemPath = $systemPath;

            $classObject->render($params);
        }
    }
}
