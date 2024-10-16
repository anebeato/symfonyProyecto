<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RetoController extends AbstractController
{
    #[Route('/reto', name: 'app_reto')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RetoController.php',
        ]);
    }

    #[Route('/cursos', name: 'app_reto')]
    public function getCursos(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $cursos = $entityManager->getRepository(Curso::class)->findAll();
        $data = $serializer->serialize($cursos, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/addCursos', name: 'add_cursos', methods: ['POST'])]
    public function addCursos(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $curso = $serializer->deserialize($data, Curso::class, 'json');
        $entityManager->persist($curso);
        $entityManager->flush();
        return $this->json(['status' => 'Curso added!'], 201);
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $credentials = $serializer->deserialize($data, 'array', 'json');
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['username' => $credentials['username']]);
        if (!$usuario || !password_verify($credentials['password'], $usuario->getPassword())) {
            return $this->json(['status' => 'Invalid credentials!'], 401);
        }
        return $this->json(['status' => 'Login successful!']);
    }

    #[Route('/addAlumno', name: 'add_alumno', methods: ['POST'])]
    public function addAlumno(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $usuario = $serializer->deserialize($data, Usuario::class, 'json');
        $usuario->setPassword(password_hash($usuario->getPassword(), PASSWORD_BCRYPT));
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $this->json(['status' => 'Alumno added!'], 201);
    }

    #[Route('/addNota', name: 'add_nota', methods: ['POST'])]
    public function addNota(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $notaData = $serializer->deserialize($data, 'array', 'json');
        $usuario = $entityManager->getRepository(Usuario::class)->find($notaData['usuario_id']);
        $curso = $entityManager->getRepository(Curso::class)->find($notaData['curso_id']);
        if (!$usuario || !$curso) {
            return $this->json(['status' => 'Usuario or Curso not found!'], 404);
        }
        $usuario->addIdCursoUsuario($curso);
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $this->json(['status' => 'Nota added!'], 201);
    }

    #[Route('/getAlumno/{id}', name: 'get_alumno', methods: ['GET'])]
    public function getAlumno(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $usuario = $entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['status' => 'Usuario not found!'], 404);
        }
        $data = $serializer->serialize($usuario, 'json');
        return new JsonResponse($data, 200, [], true);
    }
}
