<?php

namespace App\Livewire\SendersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\WithMediaSync;
use Livewire\WithPagination;
use Illuminate\Support\Facades\App;


#[Title('Senders` announcements')]
class ShowAnnouncements extends Component
{
    use WithMediaSync, WithPagination;

    //public SenderAnnouncement $senderAnnouncement;

    public $language;

    public function mount() //SenderAnnouncement $senderAnnouncement
    {
        //$this->senderAnnouncement = $senderAnnouncement;

        $this->language = Language::where('code', App::currentLocale())->first();
    }

    public function delete($id)
    {
        //dd($id);

        $senderannouncement = SenderAnnouncement::find($id);
 
        $this->authorize('delete', $senderannouncement); 

        //delete files of the announcement
        if($senderannouncement->library->count())
        {
            foreach($senderannouncement->library as $image)
            {
                Storage::disk('senders-announcements')->delete($image['path']);
            }
        }
 
        $senderannouncement->delete();
    }

    public function render()
    {
        $senderAnnouncements = SenderAnnouncement::paginate(10); //SenderAnnouncement::all()

        return view('livewire.senders-announcements.show-announcements', compact('senderAnnouncements'));
    }
}
