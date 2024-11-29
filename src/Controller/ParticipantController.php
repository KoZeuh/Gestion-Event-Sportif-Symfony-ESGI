<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participant;

use App\Form\ParticipantType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/event/{id}/participants/new', name: 'participant_add', requirements: ['id' => '\d+'])]
    public function addParticipant(Event $event, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$event) {
            throw $this->createNotFoundException('Événement introuvable.');
        }
    
        $participant = new Participant();
        $participant->setEvent($event);

        $form = $this->createForm(ParticipantType::class, $participant, ['event' => $event]);
              
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $existingParticipant = $entityManager->getRepository(Participant::class)->findOneBy([
                'event' => $event,
                'email' => $participant->getEmail(),
            ]);
    
            if ($existingParticipant) {
                $this->addFlash('danger', 'Ce participant est déjà inscrit à cet événement.');
    
                return $this->redirectToRoute('participant_add', ['id' => $event->getId()]);
            }
    
            $entityManager->persist($participant);
            $entityManager->flush();
    
            $this->addFlash('success', 'Participant ajouté avec succès.');
    
            return $this->redirectToRoute('event_view', ['id' => $event->getId()]);
        }
    
        return $this->render('participant/new.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }    
}
