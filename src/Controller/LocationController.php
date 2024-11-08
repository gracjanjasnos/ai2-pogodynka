<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


#[Route('/location')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'app_location_index', methods: ['GET'])]
    public function index(LocationRepository $locationRepository): Response
    {
        return $this->render('location/index.html.twig', [
            'locations' => $locationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_location_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $location = new Location();
    $form = $this->createForm(LocationType::class, $location, [
        'validation_groups' => ['create'],
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($location);
        $entityManager->flush();

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('location/new.html.twig', [
        'location' => $location,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_location_show', methods: ['GET'])]
    public function show(Location $location): Response
    {
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_location_delete', ['id' => $location->getId()]))
            ->setMethod('POST')
            ->getForm();

        return $this->render('location/show.html.twig', [
            'location' => $location,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
    
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_location_delete', ['id' => $location->getId()]))
            ->setMethod('POST')
            ->add('submit', SubmitType::class, [
                'label' => 'Delete',
                'attr' => ['class' => 'btn btn-danger mt-3']
            ])
            ->getForm();
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
            'delete_form' => $deleteForm->createView(),
        ]);
    }
    
    

    #[Route('/{id}', name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $location->getId(), $request->request->get('_token'))) {
            $entityManager->remove($location);
            $entityManager->flush();
    
            $this->addFlash('success', 'Location deleted successfully.');
        }
    
        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
