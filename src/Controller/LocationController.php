<?php

namespace App\Controller;

use App\Entity\Location;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location')]
final class LocationController extends AbstractController
{
    #[Route(name: 'app_location_index', methods: ['GET'])]
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
        $form = $this->createForm(LocationType::class, $location, ['validation_groups' => ['create']]);
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

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'app_location_show', methods: ['GET'])]
    public function show(#[MapEntity(id: 'id')] Location $location): Response
    {
        return $this->render('location/show.html.twig', [
            'location' => $location,
        ]);
    }

    #[Route('/{id}/edit', requirements: ['id' => '\d+'], name: 'app_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(id: 'id')] Location $location, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationType::class, $location, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_location_confirm_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function confirmDelete(#[MapEntity(id: 'id')] Location $location): Response
    {
        return $this->render('location/delete.html.twig', ['location' => $location]);
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], name: 'app_location_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(id: 'id')] Location $location, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$location->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }
        if (!$location->getMeasurements()->isEmpty()) {
            $this->addFlash('warning', 'Remove the measurements of this location first.');
            return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
        }
        $entityManager->remove($location);
        $entityManager->flush();
        $this->addFlash('success', 'Location deleted successfully.');

        return $this->redirectToRoute('app_location_index', [], Response::HTTP_SEE_OTHER);
    }
}
