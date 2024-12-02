<?php

use Core\Debug\Debugger;
use Core\Router\Router;

if (!function_exists('d')) {
    function dd(): void
    {
        Debugger::dd(...func_get_args());
    }
}

if (!function_exists('route')) {
    /**
     * @param string $name
     * @param mixed[] $params
     * @return string
     */
    function route(string $name, $params = []): string
    {
        return Router::getInstance()->getRoutePathByName($name, $params);
    }

    if (!function_exists('view')) {
      function view(string $view, array $data = [])
      {
          extract($data); // Extrai as variáveis do array $data para o escopo da view

          // Adicionando o subdiretório 'home' na construção do caminho
          $viewPath = __DIR__ . '/../app/views/home/' . $view . '.phtml';

          if (file_exists($viewPath)) {
              require_once $viewPath;
          } else {
              throw new Exception("View {$view} not found in /home.");
          }
      }
  }


}
