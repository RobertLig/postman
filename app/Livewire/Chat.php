<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SenderAnnouncement;
use App\Models\Courier;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use App\Events\MessageSenderAnnouncement;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Url;
use App\Broadcasting\SenderAnnouncementChannel;
use Illuminate\Support\Facades\Log;

#[Title('Chat')]
class Chat extends Component
{
    public User $selectedUser;

    //public SenderAnnouncement $senderannouncement;

    #[Validate('nullable|string|max:200')]
    public $newMessage;

    public $chatMessages;

    public $senderAnnouncementID;

    public $courierAnnouncementID;

    public $selectedUserAvatar;

    public $authUserAvatar;

    public $timezone;

    #[Url] 
    public $senderannouncement; //for url query string parameter

    #[Url] 
    public $courier; //for url query string parameter

    public $subtitle;

    public $presenceIndicator; //public bool $presenceIndicator = false

                                     //for both SenderAnnouncement and CourierAnnouncement (would be more readable to make saparate livewire components for both)
    public function mount(User $user, $announcement=null) //, SenderAnnouncement $senderannouncement route model binding doesn't work for SenderAnnouncement. why? | $selectedUser from parent or route model binding  | ,
    {
        $this->selectedUser = $user;

        //$this->senderannouncement = $senderannouncement; //test

        //for Url
        if($this->senderannouncement)
        {
           //dd($this->senderannouncement); 

           $announcement = SenderAnnouncement::findOrFail($this->senderannouncement);
        }

        //for Url
        if($this->courier) 
        {
           //dd($this->courierannouncement); 

           $announcement = Courier::findOrFail($this->courier); 
        }

        if($announcement) 
        {
            if(array_key_exists('library', $announcement->getAttributes())) 
            {
                $this->senderAnnouncementID = $announcement->id; //it is a SenderAnnouncement

                //new SenderAnnouncementChannel($announcement); //broadcasting authorization endpoint. doesn't work
            }
            else 
            {
                $this->courierAnnouncementID = $announcement->id; //it is a CourierAnnouncement

                //dd($this->courierAnnouncementID);

                //Log::info('courierAnnouncementID: {courierAnnouncementID}', ['courierAnnouncementID' => $this->courierAnnouncementID]);
            } 

            $this->subtitle = 'You can agree on the details of the ad.'; 
        } 
        else
        {
            $this->subtitle = "Talk as much as your heart desires.";
        }

        $this->readAllAnnouncementMessages(); 

        $this->setMessages(); 

        //get avatars of both users
        if($this->selectedUser->avatar)
        {
            $this->selectedUserAvatar = Storage::url('avatars/'.$this->selectedUser->avatar);
        }

        if(Auth::user()->avatar)
        {
            $this->authUserAvatar = Storage::url('avatars/'.Auth::user()->avatar);
        }

        //set user timezone in db
        $ipInfo = Http::get('http://ip-api.com/json/' . request()->ip());

        $this->timezone = $ipInfo->json()['timezone'] ?? 'Europe/London'; //'Europe/Warsaw'
    }

    public function readAllAnnouncementMessages()
    {
        Message::where('sender_id', $this->selectedUser->id)
            ->where('recipient_id', Auth::user()->id)
            ->where('sender_announcement_id', $this->senderAnnouncementID)
            ->where('courier_announcement_id', $this->courierAnnouncementID) 
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    public function setMessages()
    {
        $this->chatMessages = Message::query()
            ->where(function(Builder $query) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('recipient_id', $this->selectedUser->id)
                    ->where('sender_announcement_id', $this->senderAnnouncementID)
                    ->where('courier_announcement_id', $this->courierAnnouncementID);
            })
            ->orWhere(function(Builder $query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('recipient_id', Auth::user()->id)
                    ->where('sender_announcement_id', $this->senderAnnouncementID)
                    ->where('courier_announcement_id', $this->courierAnnouncementID)
                    ->where('is_deleted', 0)
                    ->whereNotIn('sender_id', Auth::user()->blocked ?: collect()); // ?: collect()
            })
            ->get();
    }

    public function save()
    {
        $this->validate();

        if(!$this->newMessage) {return;}

        $message = Message::create([
            'sender_announcement_id' => $this->senderAnnouncementID,
            'courier_announcement_id' => $this->courierAnnouncementID, 
            'sender_id' => Auth::user()->id,
            'recipient_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);

        $this->chatMessages->push($message);

        $this->newMessage = null; 

        broadcast(new MessageSent($message))->toOthers();
    }

    public function updatedNewMessage($property)
    {
        //Log::info('courierAnnouncementID updatedNewMessage: {courierAnnouncementID}', ['courierAnnouncementID' => $this->courierAnnouncementID]);

        $this->dispatch("userTyping", userID: Auth::user()->id, 
                                             userName: Auth::user()->name, 
                                             selectedUserID: $this->selectedUser->id, 
                                             senderAnnouncementID: $this->senderAnnouncementID,
                                             courierAnnouncementID: $this->courierAnnouncementID);
    }

    public function getListeners()
    {
        $loginID = Auth::user()->id;

        //$senderAnnouncementID = (int) $this->senderAnnouncementID;

        $array["echo-private:chat.{$loginID},MessageSent"] = 'newChatMessageNotification';
        $array["echo-private:chat.{$loginID},MessageDeleted"] = 'newMessageDeletedNotification';

        if($this->senderAnnouncementID)
        {
            $senderAnnouncementID = (int) $this->senderAnnouncementID;

            $array["echo-presence:chatroomSender.{$senderAnnouncementID},here"] = 'here';
            $array["echo-presence:chatroomSender.{$senderAnnouncementID},joining"] = 'joining';
            $array["echo-presence:chatroomSender.{$senderAnnouncementID},leaving"] = 'leaving';
        }
        elseif($this->courierAnnouncementID)
        {
            $courierAnnouncementID = (int) $this->courierAnnouncementID;

            $array["echo-presence:chatroomCourier.{$courierAnnouncementID},here"] = 'here';
            $array["echo-presence:chatroomCourier.{$courierAnnouncementID},joining"] = 'joining';
            $array["echo-presence:chatroomCourier.{$courierAnnouncementID},leaving"] = 'leaving';
        }
        else
        {
            $array["echo-presence:chatroom,here"] = 'here';
            $array["echo-presence:chatroom,joining"] = 'joining';
            $array["echo-presence:chatroom,leaving"] = 'leaving';
        }


        return $array;

        /* return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification',
            "echo-presence:chatroom.{$senderAnnouncementID},UserEnterAnnouncement" => 'newUsersNotification', //? //"echo-presence:senderannouncement.{sender_announcement_id},UserEnterAnnouncement"
            "echo-presence:chatroom.{$senderAnnouncementID},here" => 'here',
            "echo-presence:chatroom.{$senderAnnouncementID},joining" => 'joining',
            "echo-presence:chatroom.{$senderAnnouncementID},leaving" => 'leaving', 
            "echo-presence:chatroom.{$senderAnnouncementID},error" => 'showError',
        ]; */
    }

    public function newChatMessageNotification($message)
    {
        if($message['sender_announcement_id'] == $this->senderAnnouncementID && 
           $message['courier_announcement_id'] == $this->courierAnnouncementID )
        { 
            $messageModel = Message::find($message['id']);

            $this->chatMessages->push($messageModel);
        }
    }

    /* public function messageSenderAnnouncementHandler($event) //doesn't work
    {
        $messageModel = Message::find($event['id']);

        $this->chatMessages->push($messageModel);
    } */

    public function newMessageDeletedNotification($message)
    {
        if($message['sender_announcement_id'] == $this->senderAnnouncementID && 
           $message['courier_announcement_id'] == $this->courierAnnouncementID )
        { 
            $this->setMessages();
        }
    }

    public function deleteMessage($id)
    {
        $message = Message::find($id);
 
        $this->authorize('delete', $message); 

        $message->delete();

        $this->setMessages();

        broadcast(new MessageDeleted($message))->toOthers();

        //dd('message deleted test');
    }

    public function deleteSomebodyMessage($id)
    {
        $message = Message::find($id);
 
        $this->authorize('deleteSomebodyMessage', $message); 

        $message->is_deleted = 1;
        $message->save();

        $this->setMessages();

        //Log::info('deleteSomebodyMessage');

        //dd('message deleted test');
    }

    public function blockUser(User $user)
    {
        $this->authorize('block', $user);

        if($user->id != $this->selectedUser->id)
        {
            abort(403);
        }

        $authUser = Auth::user();

        $blocked = $authUser->blocked ?: collect();
        $blocked->push($user->id);
        $authUser->blocked = $blocked;

        //$authUser->blocked->push($user->id);
        $authUser->save(); 

        //update for setMessages query builder
        /* $this->selectedUser->blocked_by->push(Auth::user()->id);
        $this->selectedUser->save(); */

        //Log::info('block User: {user}', ['user' => $user]);

        $this->setMessages();
    }

    public function newUsersNotification()
    {
        //dd("I am on show announcement page");
    }

    //#[On('echo-presence:chatroom,here')]
    public function here($users) //for event dispatcher
    {
        /* foreach($users as $user)
        {
            $user = User::find($user['id']);

            $this->presentUsers->push($user);
        } */

        //dd($users);
        //if(isset($users['senderAnnouncementID']) && $users['senderAnnouncementID'] == $this->senderAnnouncementID) //never executes
        //{
            //Log::info('All users: {users}', ['users' => $users]);

            foreach($users as $user) //don't show me a user if he is not in the chatroom
            {
                if($user['id'] == $this->selectedUser->id)
                {
                    $this->presenceIndicator = 1; //1
                }
            }
        //}
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user) //for event recipient
    {
        //dd($user);
        //if(isset($user['senderAnnouncementID']) && $user['senderAnnouncementID'] == $this->senderAnnouncementID) //not needed
        //{
            //Log::info('Joining: {user}', ['user' => $user]);

            if($user['id'] == $this->selectedUser->id)
            {
                $this->presenceIndicator = 1; //1
            }
        //}

        /* $user = User::find($user['id']);

        $this->presentUsers->push($user); */
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user) //for event recipient
    {
        //dd($user);

        //if(isset($user['senderAnnouncementID']) && $user['senderAnnouncementID'] == $this->senderAnnouncementID) //not needed
        //{
            //Log::info('Leaving: {user}', ['user' => $user]);

            if($user['id'] == $this->selectedUser->id)
            {
                $this->presenceIndicator = 0; //1
            }
        //}
    
        $this->setUserLeftMessagesAsRead($user); //mark as read current messages received at the moment of speaking

        /* $userModel = User::find($user['id']);

        $this->presentUsers = $this->presentUsers->filter(function ($value, int $key) use ($userModel) {
            return $value->id != $userModel->id;
        }); */
    } 

    public function showError($error)
    {
        //Log::info('Error: {error}', ['error' => $error]);
    }
                                             //array
    public function setUserLeftMessagesAsRead($user) 
    {
        Message::where('sender_id', Auth::user()->id)
            ->where('recipient_id', $user['id']) //$this->selectedUser->id
            ->where('sender_announcement_id', $this->senderAnnouncementID)
            ->where('courier_announcement_id', $this->courierAnnouncementID) 
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
