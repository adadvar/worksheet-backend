<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worksheet;
use App\Models\WorksheetFavourite;
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
        // return ($user->isAdmin() || ($user->id == $worksheet->user_id));
        return $user->isAdmin();
    }

    public function changeState(User $user, Worksheet $worksheet)
    {
        return $user->isAdmin();
    }


    public function delete(User $user, Worksheet $worksheet = null)
    {
        // return ($user->isAdmin() || ($user->id == $worksheet->user_id));
        return $user->isAdmin();
    }

    public function like(User $user = null, Worksheet $worksheet = null)
    {
        // if ($worksheet && $worksheet->isAccepted()) {
        if ($worksheet) {
            $conditions = [
                'worksheet_id' => $worksheet->id,
                'user_id' => $user ? $user->id : null
            ];

            if (empty($user)) {
                $conditions['user_ip'] = client_ip();
            }
            return WorksheetFavourite::where($conditions)->count() == 0;
        }

        // return $this->deny('شما مجاز به این کار نیستید');
        return false;
    }

    public function unlike(User $user = null, Worksheet $worksheet = null)
    {
        $conditions = [
            'Worksheet_id' => $worksheet->id,
            'user_id' => $user ? $user->id : null
        ];

        if (empty($user)) {
            $conditions['user_ip'] = client_ip();
        }

        return WorksheetFavourite::where($conditions)->count();
    }

    // public function deleteFavourite(User $user = null, Worksheet $worksheet = null)
    // {
    //     return $user->favouriteWorksheets->find($worksheet->id);
    // }

    // public function deleteRecent(User $user = null, Worksheet $worksheet = null)
    // {
    //     return $user->recentWorksheets->find($worksheet->id);
    // }
}
