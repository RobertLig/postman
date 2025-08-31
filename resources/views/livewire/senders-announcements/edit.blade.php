<?php

use Livewire\Volt\Component;
use App\Models\SenderAnnouncement;

new class extends Component {
    public SenderAnnouncement $senderannouncement;

    public function mount(SenderAnnouncement $senderannouncement) //received from route parameter
    {
        //dd($senderannouncement); //works!
        $this->senderannouncement = $senderannouncement;
    }

    public function update()
    {
        $this->authorize('update', $this->senderannouncement);
    }
}; ?>

<div>
    Edit sender announcement
</div>
