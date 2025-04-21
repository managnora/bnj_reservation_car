<?php

namespace App\Infrastructure\Controller\API;

use App\Application\Mapper\ReservationMapper;
use App\Application\Request\ReservationRequest;
use App\Application\UseCase\CreateReservation;
use App\Application\UseCase\ListReservations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/reservations', name: 'api_reservation_')]
class ReservationController extends AbstractController
{
    /**
     * @param ReservationMapper $reservationMapper
     * @param ValidatorInterface $validator
     */
    public function __construct(
        private readonly ReservationMapper $reservationMapper,
        private readonly ValidatorInterface $validator
    ) {}

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        CreateReservation $createReservation
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        try {
            $reservationRequest = new ReservationRequest(
                carId: $data['carId'],
                userEmail: $data['userEmail'],
                startTime: new \DateTime($data['startTime']),
                endTime: new \DateTime($data['endTime'])
            );

            $violations = $this->validator->validate($reservationRequest);

            if (count($violations) > 0) {
                $errors = [];
                foreach ($violations as $violation) {
                    $errors[$violation->getPropertyPath()] = $violation->getMessage();
                }
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $reservation = $createReservation->execute($reservationRequest);
            $response = $this->reservationMapper->toResponse($reservation);

            return new JsonResponse($response->toArray(), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('', methods: ['GET'])]
    public function list(ListReservations $listReservations): JsonResponse
    {
        $reservations = $listReservations->execute();
        $response = array_map(
            fn($reservation) => $this->reservationMapper->toResponse($reservation)->toArray(),
            $reservations
        );
        return new JsonResponse($response, Response::HTTP_OK);
    }
}
