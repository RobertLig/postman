<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CourierPresence extends Component
{
     use WithPagination;

    public Collection $couriers;

    public array $ids;

    public array $userPresentOnCourierAnnouncement;

    public function mount() 
    {
        $this->couriers = Auth::user()->couriers; 

        $this->ids = [];

        //dd($this->couriers);
    }

    public function getListeners()
    {
        //create dynamic channels for each Courier
        foreach($this->couriers as $courier)
        {
            $array["echo-presence:courier.{$courier->id},here"] = 'here'; 
            $array["echo-presence:courier.{$courier->id},joining"] = 'joining'; 
            $array["echo-presence:courier.{$courier->id},leaving"] = 'leaving'; 
        }

        return $array;
    }

    //#[On('echo-presence:chatroom,here')]
    public function here($users)
    {
        //Log::info('All presentUsers message box: {users}', ['users' => $users]);

        foreach($users as $user)
        {
            if($user['id'] != Auth::user()->id)
            {
                $this->ids[] = $user['id'];

                $this->userPresentOnCourierAnnouncement[ $user['id'] ] = $user['courierAnnouncementID']; //theoretically user can't be at two different announcements at the same time
            }
        } 
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        //Log::info('Joining presentUsers message box: {user}', ['user' => $user]);

        $this->ids[] = $user['id']; //may be added two times: once after here() and second time here (problem?)

        $this->userPresentOnCourierAnnouncement[ $user['id'] ] = $user['courierAnnouncementID']; 
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        //Log::info('Leaving presentUsers message box: {user}', ['user' => $user]);

        unset( $this->userPresentOnCourierAnnouncement[ $user['id'] ] );

        $key = array_search($user['id'], $this->ids);

        unset($this->ids[$key]); 
    } 

    public function render()
    {
        $paginatedUsers = User::query()
            ->whereIn('id', $this->ids)
            ->paginate(10, pageName:'courier-presence-page'); //10

        $itemsTransformed = $paginatedUsers
            ->getCollection()
            ->map(function($item) {
                return [
                    $this->userPresentOnCourierAnnouncement[$item->id], $item, //$item->id
                ];
            }); 

        $presentUsersTransformedAndPaginated = new LengthAwarePaginator(
            $itemsTransformed,
            $paginatedUsers->total(),
            $paginatedUsers->perPage(),
            $paginatedUsers->currentPage(), 
            /* [
                'path' => url()->current(), //may be needed
                //'query' => [
                    //'page' => $paginatedUsers->currentPage()
                //] 
            ] */
        );

        return view('livewire.courier-presence', compact('presentUsersTransformedAndPaginated') );
    }
}
