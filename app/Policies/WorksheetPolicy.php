<?php

namespace App\Policies;

use App\Models\Advert;
use App\Models\AdvertFavourite;
use App\Models\User;
use App\Models\Worksheet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorksheetPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Worksheet $worksheet = null)
    {
        return ($user->isAdmin() || ($user->id == $worksheet->user_id));
    }

    public function changeState(User $user, Worksheet $worksheet)
    {
        return $user->isAdmin();
    }


    public function delete(User $user, Worksheet $worksheet = null)
    {
        return ($user->isAdmin() || ($user->id == $worksheet->user_id));
    }

    // public function like(User $user = null, Worksheet $worksheet = null)
    // {
    //     if ($worksheet && $worksheet->isAccepted()) {
    //         $conditions = [
    //             'advert_id' => $worksheet->id,
    //             'user_id' => $user ? $user->id : null
    //         ];

    //         if (empty($user)) {
    //             $conditions['user_ip'] = client_ip();
    //         }
    //         return AdvertFavourite::where($conditions)->count() == 0;
    //     }

    //     return false;
    // }

    // public function unlike(User $user = null, Worksheet $worksheet = null)
    // {
    //     $conditions = [
    //         'Advert_id' => $worksheet->id,
    //         'user_id' => $user ? $user->id : null
    //     ];

    //     if (empty($user)) {
    //         $conditions['user_ip'] = client_ip();
    //     }

    //     return AdvertFavourite::where($conditions)->count();
    // }

    // public function deleteFavourite(User $user = null, Worksheet $worksheet = null)
    // {
    //     return $user->favouriteAdverts->find($worksheet->id);
    // }

    // public function deleteRecent(User $user = null, Worksheet $worksheet = null)
    // {
    //     return $user->recentAdverts->find($worksheet->id);
    // }
}
