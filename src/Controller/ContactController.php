<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Category;
use App\Form\ContactType;
use App\Services\CategoriesServices;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\WeekType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// https://symfony.com/doc/current/reference/forms/types.html
class ContactController extends AbstractController
{
    public function __construct(CategoriesServices $categoriesServices)
    {
        $categoriesServices->updateSession();
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, CategoryRepository $repoCat, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request); // permet d'analyser la requete et d'insèrer les informations reçues via le formulaire dans la variable $contact

        $session = $request->getSession(); // on récupère la session

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTimeImmutable()); // création de createdAt

            // dd($contact); // affichage du formulaire $contact

            // on sauvegarde les données
            $em->persist($contact);
            $em->flush();

            // on vide le formulaire pour un nouvel affichage
            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);

            // set flash messages
            $session->getFlashBag()->add("message", "Message envoyé avec succès");
            $session->set('status', "success");
        } else if ($form->isSubmitted() && !$form->isValid()) {
            $session->getFlashBag()->add("message", "Message non envoyé. Merci de corriger les erreurs");
            $session->set('status', "danger");
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'contact' => $form->createView()
        ]);
    }
}
