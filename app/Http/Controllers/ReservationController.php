<?php

namespace App\Http\Controllers;

use App\Services\ReservationService;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      title="Reservation API",
 *      version="1.0.0",
 *      description="API para gestionar reservas de usuarios en sitios."
 * )
 *
 * @OA\Tag(
 *     name="Reservations",
 *     description="Endpoints para gestionar reservas"
 * )
 */
class ReservationController extends Controller {
    protected $reservationService;

    public function __construct(ReservationService $reservationService) {
        $this->reservationService = $reservationService;
    }

    /**
     * @OA\Post(
     *     path="/api/reservations",
     *     tags={"Reservations"},
     *     summary="Crear una nueva reserva",
     *     description="Permite a un usuario hacer una reserva en un sitio",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","site_id","start_date","end_date"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="site_id", type="integer", example=2),
     *             @OA\Property(property="start_date", type="string", format="date-time", example="2025-02-10 14:00:00"),
     *             @OA\Property(property="end_date", type="string", format="date-time", example="2025-02-12 14:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reserva creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reservation created"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="site_id", type="integer", example=2),
     *                 @OA\Property(property="start_date", type="string", format="date-time", example="2025-02-10 14:00:00"),
     *                 @OA\Property(property="end_date", type="string", format="date-time", example="2025-02-12 14:00:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errores de validación",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="error", type="string", example="User already has an active reservation.")
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="error", type="string", example="Reservations must be made for future dates.")
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="error", type="string", example="User has overlapping reservations.")
     *                 )
     *             }
     *         )
     *     )
     * )
     */

    /**
     * Crea una nueva reserva en el sistema.
     *
     * @param Request $request Datos de la reserva
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        try {

            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'site_id' => 'required|exists:sites,id',
                'start_date' => 'required|date|after:now',
                'end_date' => 'required|date|after:start_date',
            ]);

            $reservation = $this->reservationService->createReservation(
                $validatedData['user_id'],
                $validatedData['site_id'],
                $validatedData['start_date'],
                $validatedData['end_date']
            );

            $reservation->load('user', 'site');

            return response()->json([
                'message' => 'Reservation created',
                'data' => [
                    'id' => $reservation->id,
                    'user_name' => $reservation->user->name,
                    'site_name' => $reservation->site->name,
                    'start_date' => $reservation->start_date,
                    'end_date' => $reservation->end_date
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar y devolver errores de validación
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error processing request', 'error' => $e->getMessage()], 400);
        }
    }
}
