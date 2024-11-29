<?php

namespace App\Controller;

use App\Entity\Event;

use App\Service\DistanceCalculator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private DistanceCalculator $distanceCalculator)
    {
    }

    #[Route('/', name: 'event_list')]
    public function listEvents(): Response
    {
        $events = $this->entityManager->getRepository(Event::class)->findAll();

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/{id}', name: 'event_view', requirements: ['id' => '\d+'])]
    public function viewEvent(Event $event): Response
    {
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }

        return $this->render('event/view.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/event/{id}/calculate-distance/{userLat}/{userLon}', name: 'event_calculate_distance', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function calculateDistanceToEvent(
        int $id,
        float $userLat,
        float $userLon
    ): JsonResponse {
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event) {
            return new JsonResponse(['error' => 'Cet évènement n\'existe pas !'], 404);
        }
    
        $eventLat = $event->getLatitude();
        $eventLon = $event->getLongitude();
    
        $distance = $this->distanceCalculator->calculateDistance($userLat, $userLon, $eventLat, $eventLon);
    
        return new JsonResponse([
            'distance_km' => round($distance, 2),
        ]);
    }
    
}
