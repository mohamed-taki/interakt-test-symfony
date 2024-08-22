<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\User;
use App\Form\CreateCourseFormType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController{
    private CourseRepository $courseRepository;
    private EntityManagerInterface $em;
    private User|null $user;
    private PaginatorInterface $paginator;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $em, PaginatorInterface $paginator, Security $security) {
        $this->courseRepository = $courseRepository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->user = $security->getUser() ?? null;
    }

    
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response{
        $courses = $this->courseRepository->findAll();

        $limit = $request->query->getInt('limit', 10);  
        $pagination = $this->paginator->paginate(
            $courses, 
            $request->query->getInt('page', 1),
            $limit,
        );

        return $this->render('course/list.html.twig', [
            'pagination' => $pagination,
            'limit' => $limit,
            'user' => $this->user,
        ]);
    }

    #[Route('/course/new', name:'app_new_course')]
    public function newCourse(Request $request): Response{
        $course = new Course();
        $form = $this->createForm(CreateCourseFormType::class, $course);
        $form->handleRequest($request);

        // the CreateCourseFormType::class Already validates all the required fields for us
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('image')->getData();

            // set a unique filename
            $newFilename = uniqid() . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('courses_images_uploads_full_directory'),
                $newFilename
            );

            // upload image to the dynamic images directory
            $course->setImagePath('/' . $this->getParameter('courses_images_uploads_directory') . $newFilename);

            // set the new course's creator as the current user
            $course->setCreator($this->user);

            $this->em->persist($course);
            $this->em->flush();

            return $this->redirectToRoute('app_home');
        }
        return $this->render('course/new.html.twig', [
            'user' => $this->user,
            'form' => $form
        ]);
    }

    #[Route('/fav', name:'app_course_favs')]
    public function showFavourites(): Response{
        return $this->render("course/fav.courses.html.twig", [
            'user' => $this->user,
        ]
        );
    }

    // no need for slugger here
    #[Route('/fav/add/{id}', name:'app_add_fav_course')]
    public function addFavouriteCourse(int $id): Response{
        $course = $this->courseRepository->find($id);

        if ($course) {
            // throw $this->createNotFoundException('The course does not exist');
            $this->user->addFavCourse($course);
            $this->em->persist($this->user);
            $this->em->flush();
        }else{
            // redirect to not found page
            return $this->redirectToRoute('app_not_found');
        }

        return $this->redirectToRoute('app_course_favs');
    }

    // no need for slugger here
    #[Route('/fav/remove/{id}', name:'app_remove_fav_course')]
    public function removeFavouriteCourse(int $id): Response{
        $course = $this->courseRepository->find($id);

        if ($course) {
            $this->user->removeFavCourse($course);
            $this->em->persist($this->user);
            $this->em->flush();
        }else{
            // redirect to not found page
            return $this->redirectToRoute('app_not_found');
        }

        return $this->redirectToRoute('app_course_favs');
    }
}
