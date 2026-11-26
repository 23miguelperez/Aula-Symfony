<?php

namespace App\Tests;

use App\Entity\Tarea;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TareaAccesoTest extends WebTestCase
{
    private KernelBrowser $client;
    private int $anaId;
    private int $luisId;
    private int $tareaId;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $em = $this->em();

        // Base de datos limpia en cada test: primero tareas, luego usuarios
        $em->createQuery('DELETE FROM App\Entity\Tarea')->execute();
        $em->createQuery('DELETE FROM App\Entity\User')->execute();

        $ana = $this->crearUsuario('ana@example.com');
        $luis = $this->crearUsuario('luis@example.com');

        $tarea = new Tarea();
        $tarea->setTitulo('Tarea de Ana');
        $tarea->setCompletada(false);
        $tarea->setPropietario($ana);
        $em->persist($tarea);
        $em->flush();

        $this->anaId = $ana->getId();
        $this->luisId = $luis->getId();
        $this->tareaId = $tarea->getId();
    }

    public function testAnonimoEsRedirigidoAlLogin(): void
    {
        foreach (['/tareas', '/tareas/nueva', "/tareas/{$this->tareaId}", "/tareas/{$this->tareaId}/editar"] as $url) {
            $this->client->request('GET', $url);

            $this->assertResponseStatusCodeSame(302, "Sin sesión, $url debería redirigir");
            $this->assertStringEndsWith('/login', $this->client->getResponse()->headers->get('Location'));
        }
    }

    public function testPropietarioPuedeVerYEditarSuTarea(): void
    {
        $this->loginComo($this->anaId);

        $this->client->request('GET', "/tareas/{$this->tareaId}");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Tarea de Ana');

        $this->client->request('GET', "/tareas/{$this->tareaId}/editar");
        $this->assertResponseIsSuccessful();
    }

    public function testPropietarioVeSuTareaEnElListado(): void
    {
        $this->loginComo($this->anaId);

        $this->client->request('GET', '/tareas');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('body', 'Tarea de Ana');
    }

    public function testOtroUsuarioNoVeLaTareaAjenaEnSuListado(): void
    {
        $this->loginComo($this->luisId);

        $this->client->request('GET', '/tareas');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextNotContains('body', 'Tarea de Ana');
    }

    public function testOtroUsuarioNoPuedeVerNiEditarTareaAjena(): void
    {
        $this->loginComo($this->luisId);

        $this->client->request('GET', "/tareas/{$this->tareaId}");
        $this->assertResponseStatusCodeSame(403);

        $this->client->request('GET', "/tareas/{$this->tareaId}/editar");
        $this->assertResponseStatusCodeSame(403);
    }

    public function testOtroUsuarioNoPuedeEliminarTareaAjena(): void
    {
        $this->loginComo($this->luisId);

        $this->client->request('POST', "/tareas/{$this->tareaId}/eliminar", ['_token' => 'token-falso']);

        $this->assertResponseStatusCodeSame(403);
        $this->assertNotNull(
            $this->em()->find(Tarea::class, $this->tareaId),
            'La tarea de Ana no debe haberse borrado'
        );
    }

    private function em(): EntityManagerInterface
    {
        return static::getContainer()->get(EntityManagerInterface::class);
    }

    private function crearUsuario(string $email): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword('no-se-usa-en-los-tests');

        $this->em()->persist($user);
        $this->em()->flush();

        return $user;
    }

    private function loginComo(int $userId): void
    {
        $this->client->loginUser($this->em()->find(User::class, $userId));
    }
}