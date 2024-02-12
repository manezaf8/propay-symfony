<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonController extends AbstractController
{
    /**
     * @Route("/Person", name="User-Person", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $item = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $this->renderForm('Person/index.html.twig', [
            'title' => "Persons",
            'list' => $item,

        ]);
    }

    /**
     * @Route("/dashboard/Person/add/{id?}", name="Save Person Persons", methods={"GET","POST"})
     */
    public function savePerson(Request $request, $id): Response
    {
        $instance = new Person();

        if ($id) {
            $instance = $this->getDoctrine()->getRepository(Person::class)->find($id);
        }

        $form = $this->createForm(PersonType::class, $instance);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($instance);
            $entityManager->flush();
            $this->addFlash('success', 'was successfully');
            //new accounts only

            $form = $this->createForm(PersonType::class, $instance);
            $id = $instance->getId();
        }

        return $this->renderForm('person/add/index.html.twig', [
            'form' => $form,
            'id' => $id,

        ]);
    }

    /**
     * 
     * @Route("/Person/delete/{id}", name="Delete Person Person", methods={"GET","POST"})
     */
    public function delete(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $item = $this->getDoctrine()->getRepository(Person::class)->find($id);
        if ($item) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
            $this->addFlash('success', "Item deleted successfully");
        } else {
            $this->addFlash('danger', "Failed to delete item");
        }
        return $this->redirect("/dashboard/profile/Persons");
    }
}
