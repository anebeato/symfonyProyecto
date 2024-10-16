<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CursoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;


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

    #[Route('/cursos', name: 'get_cursos', methods: ['GET'])]
    public function getCursos(CursoRepository $cursoRepository, SerializerInterface $serializer): JsonResponse
    {
        $cursos = $cursoRepository->findAll();

        $data = $serializer->serialize($cursos, 'json', [
            'groups' => 'curso:read',
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['curso']
        ]);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/addCurso', name: 'add_curso', methods: ['POST'])]
    public function addCurso(Request $request, CursoRepository $cursoRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $curso = new Curso();
        $curso->setNombre($data['nombre']);


        $cursoRepository->add($curso); 


        return $this->json(['status' => 'Curso created!'], Response::HTTP_CREATED);
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
