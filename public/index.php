<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/csrf.php';

spl_autoload_register(function ($class_name) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class_name . '.php',
        __DIR__ . '/../app/models/' . $class_name . '.php'
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

define('BASE_URL', '/TrabalhoPHP');

$request_uri = $_SERVER['REQUEST_URI'];
$base_path = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
$route = '/' . trim(str_replace($base_path, '', strtok($request_uri, '?')), '/');

$routes = [
    'GET' => [
        '/' => ['SiteController', 'home'],
        '/home' => ['SiteController', 'home'],
        '/sobre' => ['SiteController', 'sobre'],
        '/lista-cursos' => ['SiteController', 'listaCursosPublicos'],
        '/login' => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],
        '/register' => ['UsuarioController', 'create'],
        '/recuperar-senha' => ['AuthController', 'showRecoverForm'],
        '/dashboard' => ['DashboardController', 'index'],
        '/cursos' => ['CursoController', 'index'],
        '/cursos/create' => ['CursoController', 'create'],
        '/cursos/show/{id:[0-9]+}' => ['CursoController', 'show'],
        '/cursos/edit/{id:[0-9]+}' => ['CursoController', 'edit'],
        '/materiais/create/{curso_id:[0-9]+}' => ['MaterialController', 'create'],
        '/materiais/edit/{id:[0-9]+}' => ['MaterialController', 'edit'],
    ],
    'POST' => [
        '/login' => ['AuthController', 'processLogin'],
        '/register' => ['UsuarioController', 'store'],
        '/recuperar-senha' => ['AuthController', 'processRecovery'],
        '/cursos/store' => ['CursoController', 'store'],
        '/cursos/join' => ['CursoController', 'joinByCode'],
        '/cursos/update/{id:[0-9]+}' => ['CursoController', 'update'],
        '/cursos/delete/{id:[0-9]+}' => ['CursoController', 'delete'],
        '/cursos/leave/{id:[0-9]+}' => ['CursoController', 'leave'],
        '/cursos/{curso_id:[0-9]+}/remove-aluno/{aluno_id:[0-9]+}' => ['CursoController', 'removeAluno'],
        '/materiais/store' => ['MaterialController', 'store'],
        '/materiais/update/{id:[0-9]+}' => ['MaterialController', 'update'],
        '/materiais/delete/{id:[0-9]+}' => ['MaterialController', 'delete'],
    ]
];

$method = $_SERVER['REQUEST_METHOD'];
$controllerName = null;
$actionName = null;
$params = [];

if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePattern => $handler) {
        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+):([^\}]+)\}/', function($m) { return '(?P<'.$m[1].'>'.$m[2].')'; }, $routePattern);
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';

        if (preg_match($pattern, $route, $matches)) {
            $controllerName = $handler[0];
            $actionName = $handler[1];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[] = $value;
                }
            }
            break;
        }
    }
}

if ($controllerName && $actionName) {
    if ($method === 'POST' && !verifyCsrfToken()) {
        $_SESSION['error_message'] = 'Falha de segurança (CSRF). Por favor, tente novamente.';
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . '/'));
        exit;
    }

    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName($pdo);
        if (method_exists($controllerInstance, $actionName)) {
            call_user_func_array([$controllerInstance, $actionName], $params);
        } else {
            http_response_code(404);
            echo "Erro 404: O método '{$actionName}' não foi encontrado no controller '{$controllerName}'.";
        }
    } else {
        http_response_code(404);
        echo "Erro 404: O controller '{$controllerName}' não foi encontrado.";
    }
} else {
    http_response_code(404);
    echo "Erro 404: Página não encontrada.";
}
