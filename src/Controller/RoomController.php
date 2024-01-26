<?php

namespace App\Controller;

use App\Entity\{Room, User};
use App\Repository\RoomRepository;
use App\Service\JWTGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/room', name: 'room_')]
class RoomController extends AbstractController {

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository)
    : Response {

        return $this->render('room/index.html.twig', [
            'rooms' => $roomRepository->findAll(),
        ]);
    }

    #[Route('/{publicId}', name: 'join', methods: ['GET'])]
    public function join(
        #[CurrentUser] User $user,
        Room                $room,
        JWTGenerator        $jWTGenerator
    )
    : Response {

        $jwt = $jWTGenerator->generate($room->getPublicId(), $user->getUserIdentifier(), $room->belongsToUser($user));

        return $this->render('room/join.html.twig', [
            'controller_name' => 'RoomController',
            'room'            => $room,
            'jwt'             => $jwt,
        ]);
    }
}
