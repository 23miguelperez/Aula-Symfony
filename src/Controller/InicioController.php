<?php

// Dirección de la carpeta src/Controller/InicioController.php
namespace App\Controller;

# Importa las clases necesarias para el controlador
    // AbstractController: Clase base para controladores en Symfony
    // Response: Clase que representa una respuesta HTTP
    // Route: Atributo para definir rutas en Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Atributo que conecta la URL '/' con el método index() de este controlador
class InicioController extends AbstractController
{
    #[Route('/', name: 'app_inicio')]
    public function index(): Response
    {
        return new Response('Hola Symfony');
    }

    #[Route('/saludo/{nombre}', name: 'app_saludo')]
    public function saludo(string $nombre): Response
    {
        $nombreSeguro = htmlspecialchars($nombre, ENT_QUOTES);
        return new Response("Hola, $nombreSeguro!");
    }
}

// Explicación de la ruta y el método index():
// La ruta '/' es la URL raíz de la aplicación. Cuando un usuario accede a esta URL, Symfony invoca el método index() del controlador InicioController. Este método devuelve una respuesta HTTP con el contenido 'Hola Symfony', que se muestra en el navegador del usuario.
// Nota: El uso de atributos (como #[Route]) es una característica moderna de PHP y Symfony, que permite definir rutas directamente en los métodos del controlador, haciendo el código más limpio y fácil de mantener.
