<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema (
 *     title="GroupMenu",
 *     type="object",
 *     required={"id","name", "icon"},
 *     @OA\Property(property="id", type="number", example="1"),
 *     @OA\Property(property="name", type="string", example="Admin"),
 *     @OA\Property(property="icon", type="string", example="fas fa-user"),
 *     @OA\Property(property="created_at", type="string", example="2024-03-27 01:42:21"),
 *     @OA\Property(
 *         property="option_menus",
 *         ref="#/components/schemas/OptionMenu"
 *     ),
 * )
 */
class GroupMenu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'icon',
        'created_at',

    ];
    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function optionMenus()
    {
        return $this->hasMany(Optionmenu::class, 'groupmenu_id');
    }

    public static function getFilteredGroupMenus($userTypeId)
    {
        return self::with(['optionMenus' => function ($query) use ($userTypeId) {
            $query->whereHas('accesses', function ($query) use ($userTypeId) {
                $query->where('typeuser_id', $userTypeId);
            });
        }])
            ->get()
            ->map(function ($groupMenu) use ($userTypeId) {
                // Filtrar optionMenus según el acceso del usuario
                $groupMenu->option_menus = $groupMenu->optionMenus->filter(function ($optionMenu) use ($userTypeId) {
                    return $optionMenu->accesses->contains('typeuser_id', $userTypeId);
                })->values();
                // Eliminar 'accesses' de los optionMenus filtrados
                $groupMenu->option_menus->each(function ($optionMenu) {
                    unset($optionMenu->accesses);
                });
                // Ocultar el atributo 'optionMenus' original
                unset($groupMenu->optionMenus);
                return $groupMenu;
            });
    }
    public static function getFilteredGroupMenusSuperior($userTypeId)
    {
        return self::with(['optionMenus' => function ($query) use ($userTypeId) {
            $query->whereHas('accesses', function ($query) use ($userTypeId) {
                $query->where('typeuser_id', $userTypeId);
            });
        }])
            ->whereIn('id', [1, 2, 3,5])->get()
            ->map(function ($groupMenu) use ($userTypeId) {
                // Filtrar optionMenus según el acceso del usuario
                $groupMenu->option_menus = $groupMenu->optionMenus->filter(function ($optionMenu) use ($userTypeId) {
                    return $optionMenu->accesses->contains('typeuser_id', $userTypeId);
                })->values();
                // Eliminar 'accesses' de los optionMenus filtrados
                $groupMenu->option_menus->each(function ($optionMenu) {
                    unset($optionMenu->accesses);
                });
                // Ocultar el atributo 'optionMenus' original
                unset($groupMenu->optionMenus);
                return $groupMenu;
            });
    }

}
