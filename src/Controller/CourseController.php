<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\CreateCourseFormType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController{
    private CourseRepository $courseRepository;
    private EntityManagerInterface $em;
    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $em) {
        $this->courseRepository = $courseRepository;
        $this->em = $em;
    }
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, PaginatorInterface $paginator): Response{
        $queryBuilder = $this->courseRepository->createQueryBuilder('c')
            ->orderBy('c.title', 'ASC');

        $limit = $request->query->getInt('limit', 10);  

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            $limit, /* limit per page */
        );


        return $this->render('course/list.html.twig', [
            'pagination' => $pagination,
            'limit' => $limit,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/course/new', name:'app_new_course')]
    public function newCourse(Request $request): Response{
        $course = new Course();
        $form = $this->createForm(CreateCourseFormType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('image')->getData();
            
            // TODO: Create a seperated service to handle the creation request
            if ($file) {
                $newFilename = uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('courses_images_uploads_directory'),
                    $newFilename
                );

                $course->setImagePath('/images/courses/' . $newFilename);
                $course->setCreator($this->getUser());

                $this->em->persist($course);
                $this->em->flush();
                return $this->redirectToRoute('app_home');
            }
        }
        return $this->render('course/new.html.twig', [
            'user' => $this->getUser(),
            'errors' => $form->getErrors(),
            'form' => $form
        ]);
    }

    #[Route('/fav', name:'app_course_favs')]
    public function showFavourites(Request $request): Response{
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        return $this->render("course/fav.courses.html.twig", [
            'user' => $user,
            'courses' => $user->getFavCourses(),
        ]
        );
    }

    #[Route('/fav/add/{id}', name:'app_add_fav_course')]
    public function addFavourite(Request $request, int $id): Response{
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $course = $this->courseRepository->find($id);
        // TODO: handle the exception if course is not found
        if ($course) {
            // throw $this->createNotFoundException('The course does not exist');
            $user->addFavCourse($course);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_course_favs');
    }

    #[Route('/fav/remove/{id}', name:'app_remove_fav_course')]
    public function removeFavourite(Request $request, int $id): Response{
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $course = $this->courseRepository->find($id);
        // TODO: handle the exception if course is not found
        if ($course) {
            // throw $this->createNotFoundException('The course does not exist');
            $user->removeFavCourse($course);
            $this->em->persist($user);
            $this->em->flush();
        }

        return $this->redirectToRoute('app_course_favs');
    }
}
