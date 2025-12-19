/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     type="object",
 *     required={"id","title","priority","status"},
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Finish assignment"),
 *     @OA\Property(property="description", type="string", example="Complete Swagger docs"),
 *     @OA\Property(property="priority", type="string", enum={"Low","Normal","High"}, example="High"),
 *     @OA\Property(property="status", type="string", enum={"pending","in-progress","done"}, example="pending"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2025-01-10"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */