<?php

namespace App\Services;

use App\Repositories\ReservationRepository;
use Exception;

class ReservationService {
    protected $reservationRepo;

    public function __construct(ReservationRepository $reservationRepo) {
        $this->reservationRepo = $reservationRepo;
    }

    public function createReservation($userId, $siteId, $start, $end) {
        if ($this->reservationRepo->getUserActiveReservations($userId)) {
            throw new Exception("User already has an active reservation.");
        }

        if ($start <= now()) {
            throw new Exception("Reservations must be made for future dates.");
        }

        if ($this->reservationRepo->hasOverlappingReservations($userId, $start, $end)) {
            throw new Exception("User has overlapping reservations.");
        }

        return $this->reservationRepo->create([
            'user_id' => $userId,
            'site_id' => $siteId,
            'start_date' => $start,
            'end_date' => $end,
        ]);
    }
}
