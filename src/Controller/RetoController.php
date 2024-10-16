<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Entity\Usuario;
use App\Entity\Usucurso;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CursoRepository;
use App\Repository\UsuarioRepository;
use App\Repository\UsucursoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



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

    #[Route('/cursos', name: 'app_curso', methods: ['GET'])]
    public function getCursos(CursoRepository $cursoRepository): Response
    {
        $cursos = $cursoRepository->findAll();

        if (!$cursos) {
            return $this->json(['message' => 'No courses found'], Response::HTTP_NOT_FOUND);
        }

        $cursosArray = [];
        foreach ($cursos as $curso) {
            $cursosArray[] = [
                'id' => $curso->getId(),
                'nombre' => $curso->getNombre(),
            ];
        }

        return $this->json($cursosArray);
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


    #[Route('/addNota', name: 'add_nota', methods: ['POST'])]
    public function addNota(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $notaData = json_decode($data, true);
        $usuario = $entityManager->getRepository(Usuario::class)->find($notaData['usuario_id']);
        $curso = $entityManager->getRepository(Curso::class)->find($notaData['curso_id']);
        if (!$usuario || !$curso) {
            return $this->json(['status' => 'Usuario or Curso not found!'], 404);
        }
        if (!isset($notaData['nota'])) {
            return $this->json(['status' => 'Nota not provided!'], 400);
        }
        $usucurso = new Usucurso();
        $usucurso->setIdUsuario($usuario);
        $usucurso->setIdCurso($curso);
        $usucurso->setNota($notaData['nota']);
        $entityManager->persist($usucurso);
        $entityManager->flush();
        return $this->json(['status' => 'Nota added!'], 201);
    }

    #[Route('/deleteNota/{id}', name: 'delete_nota', methods: ['DELETE'])]
    public function deleteNota(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $usucurso = $entityManager->getRepository(Usucurso::class)->find($id);
        if (!$usucurso) {
            return $this->json(['status' => 'Nota not found!'], 404);
        }
        $entityManager->remove($usucurso);
        $entityManager->flush();
        return $this->json(['status' => 'Nota deleted!'], 200);
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


    
    #[Route('/addUsuario', name: 'add_usuario', methods: ['POST'])]
    public function addUsuario(Request $request, UsuarioRepository $usr, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
    
        if (!isset($data['username'], $data['password'], $data['admin'])) {
            return $this->json(['status' => 'Invalid data!'], 400);
        }
    
        $usuario = new Usuario();
        $usuario->setUsername($data['username']);
        $usuario->setPassword($passwordHasher->hashPassword($usuario, $data['password']));
        $usuario->setAdmin((bool)$data['admin']);
        if (isset($data['foto'])) {
            $usuario->setFoto($data['foto']);
        }
    
        $usr->add($usuario);
    
        return $this->json(['status' => 'Usuario created!'], 201);
    }

    #[Route('/getUsuario/{id}', name: 'get_usuario', methods: ['GET'])]
    public function getUsuario(int $id, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $usuario = $entityManager->getRepository(Usuario::class)->find($id);
        if (!$usuario) {
            return $this->json(['status' => 'Usuario not found!'], 404);
        }
        $data = $serializer->serialize($usuario, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/getcursonota/{id}', name: 'get_curso_nota', methods: ['GET'])]
    public function getcursonota(int $id, UsucursoRepository $usucursoRepository): JsonResponse
    {
        $usucursos = $usucursoRepository->findBy(['id_usuario' => $id]);

        if (!$usucursos) {
            return $this->json(['message' => 'No courses found for the given student ID'], Response::HTTP_NOT_FOUND);
        }

        $cursosNotas = [];
        foreach ($usucursos as $usucurso) {
            $cursosNotas[] = [
                'curso' => $usucurso->getIdCurso()->getNombre(),
                'nota' => $usucurso->getNota(),
            ];
        }

        return $this->json($cursosNotas);
    }
}
