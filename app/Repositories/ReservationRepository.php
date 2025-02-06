<?php

namespace App\Repositories;

use App\Models\Reservation;

class ReservationRepository {
    public function create(array $data) {
        return Reservation::create($data);
    }

    public function getUserActiveReservations($userId) {
        return Reservation::where('user_id', $userId)
            ->where('end_date', '>=', now())
            ->exists();
    }

    public function hasOverlappingReservations($userId, $start, $end) {
        return Reservation::where('user_id', $userId)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end]);
            })
            ->exists();
    }
}
