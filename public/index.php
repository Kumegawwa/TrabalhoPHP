<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/csrf.php';

spl_autoload_register(function ($class_name) {
    $controller_file = __DIR__ . '/../app/controllers/' . $class_name . '.php';
    $model_file = __DIR__ . '/../app/models/' . $class_name . '.php';

    if (file_exists($controller_file)) {
        require_once $controller_file;
    } elseif (file_exists($model_file)) {
        require_once $model_file;
    }
});

// Define uma constante para a URL base do projeto
define('BASE_URL', '/TrabalhoPHP'); // Ajuste se o nome da sua pasta for diferente

// Roteamento básico
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME']; // Ex: /TrabalhoPHP/public/index.php

// Tenta determinar a rota de forma mais robusta
$base_path_for_route_extraction = dirname($script_name); // Ex: /TrabalhoPHP/public
if ($base_path_for_route_extraction === '/' || $base_path_for_route_extraction === '\\') {
    $base_path_for_route_extraction = '';
}

// Remove o caminho base do script da URI para obter a rota "limpa"
if (strpos($request_uri, $base_path_for_route_extraction) === 0) {
    $route = substr($request_uri, strlen($base_path_for_route_extraction));
} else {
    $route = $request_uri; // Fallback, menos provável com .htaccess correto
}


// Remove query string da rota, se houver
$route = strtok($route, '?');

// Garante que a rota comece com / e remove a barra final se não for a raiz
$route = '/' . trim($route, '/');

// Se após o trim a rota for vazia (ex: acessou /TrabalhoPHP/public/), considera como raiz '/'
if ($route === '/' && ($base_path_for_route_extraction === BASE_URL.'/public' || $base_path_for_route_extraction === BASE_URL )) {
     // Se você acessou http://localhost/TrabalhoPHP/ (e .htaccess na raiz aponta para public)
     // ou http://localhost/TrabalhoPHP/public/
     // a rota será '/'
} else if (empty(trim($route, '/'))) { // se a rota ficou vazia após o trim, é a raiz
    $route = '/';
}


// Definição das rotas (MANTENHA COMO ANTES)
$routes = [
    // Rotas GET
    'GET' => [
        '/' => ['SiteController', 'home'],
        '/home' => ['SiteController', 'home'],
        '/login' => ['AuthController', 'login'],
        '/logout' => ['AuthController', 'logout'],
        '/cursos' => ['CursoController', 'index'],
        '/cursos/create' => ['CursoController', 'create'],
        // Ajuste para aceitar ID como parâmetro numérico
        '/cursos/show/{id:[0-9]+}' => ['CursoController', 'show'],
        '/cursos/edit/{id:[0-9]+}' => ['CursoController', 'edit'], // Adicionar esta rota para editar
        '/materiais/create/{curso_id:[0-9]+}' => ['MaterialController', 'create'],
        '/materiais/edit/{id:[0-9]+}' => ['MaterialController', 'edit'], // Adicionar esta rota
        '/sobre' => ['SiteController', 'sobre'],
        '/lista-cursos' => ['SiteController', 'listaCursosPublicos'],
    ],
    // Rotas POST
    'POST' => [
        '/login' => ['AuthController', 'processLogin'],
        '/cursos/store' => ['CursoController', 'store'],
        '/cursos/update/{id:[0-9]+}' => ['CursoController', 'update'], // Adicionar esta rota
        '/cursos/delete/{id:[0-9]+}' => ['CursoController', 'delete'], // Adicionar esta rota
        '/materiais/store' => ['MaterialController', 'store'],
        '/materiais/update/{id:[0-9]+}' => ['MaterialController', 'update'], // Adicionar esta rota
        '/materiais/delete/{id:[0-9]+}' => ['MaterialController', 'delete'], // Adicionar esta rota
    ]
];

$method = $_SERVER['REQUEST_METHOD'];
$controllerName = null;
$actionName = null;
$params = [];

if (isset($routes[$method])) {
    foreach ($routes[$method] as $routePattern => $handler) {
        // Lógica para rotas com parâmetros (ajustada para placeholder com regex opcional)
        $pattern = preg_replace_callback('/\{([a-zA-Z0-9_]+)(:[^\}]+)?\}/', function($matches) {
            // Se houver uma regex customizada (ex: {id:[0-9]+}), usa ela. Senão, usa [a-zA-Z0-9_]+.
            return '(' . (isset($matches[2]) ? substr($matches[2], 1) : '[a-zA-Z0-9_]+') . ')';
        }, $routePattern);

        if (preg_match('#^' . $pattern . '$#', $route, $matches)) {
            array_shift($matches);
            $params = $matches;
            $controllerName = $handler[0];
            $actionName = $handler[1];
            break;
        }
    }
}


if ($controllerName && $actionName) {
    if (class_exists($controllerName)) {
        $controllerInstance = new $controllerName();
        if (method_exists($controllerInstance, $actionName)) {
            $protectedRoutes = [
                // A home após login (se for diferente da pública)
                // 'SiteController@home', (se home for sempre pública, remover daqui)
                'CursoController@create',
                'CursoController@store',
                'CursoController@edit',
                'CursoController@update',
                'CursoController@delete',
                'MaterialController@create',
                'MaterialController@store',
                'MaterialController@edit',
                'MaterialController@update',
                'MaterialController@delete',
                'AuthController@logout'
            ];
            // A página /home pode ser acessível publicamente ou apenas após login.
            // Se /home é a mesma que /, e / é pública, então /home não precisa estar em $protectedRoutes.
            // Se /home é uma dashboard após login, então adicione 'SiteController@home'.
            // No meu exemplo, / e /home apontam para o mesmo lugar e não estão em $protectedRoutes.
            // Adicione proteção se necessário.

            $currentHandler = $controllerName . '@' . $actionName;

            if (in_array($currentHandler, $protectedRoutes) && !isset($_SESSION['usuario_id'])) {
                $_SESSION['redirect_message'] = 'Você precisa estar logado para acessar esta página.';
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
            
            if ($method === 'POST' && function_exists('verifyCsrfToken')) {
                if (!verifyCsrfToken()) {
                    // Você pode redirecionar com uma mensagem de erro ou apenas 'die'.
                    $_SESSION['error_message'] = 'Falha na validação CSRF. Tente novamente.';
                    // Redireciona para a página anterior ou para uma página de erro
                    $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/';
                    header('Location: ' . $referer);
                    exit;
                }
            }

            call_user_func_array([$controllerInstance, $actionName], $params);
        } else {
            http_response_code(404);
            echo "Erro 404: Método {$actionName} não encontrado no controller {$controllerName} para a rota '{$route}'.";
        }
    } else {
        http_response_code(404);
        echo "Erro 404: Controller {$controllerName} não encontrado para a rota '{$route}'.";
    }
} else {
    http_response_code(404);
    echo "Erro 404: Rota não encontrada para {$method} {$route}.";
}