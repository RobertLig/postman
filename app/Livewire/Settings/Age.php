<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class Age extends Component
{
    use Toast;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $age;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $agePrev;

    public function mount(): void
    {
        $user = Auth::user();

        $this->age = $user->age;

        $this->agePrev = $user->age;
    }

    public function updateAge(): void
    {
        $user = Auth::user();

        $user->update(['age' => $this->age]);

        $this->success(__('The age field has been updated successfully!'), position: 'toast-bottom');
    }

    public function resetAge(): void
    {
        if ($this->agePrev === $this->age) {
            $this->age = null;

            $this->agePrev = null;
        } else {
            $this->agePrev = $this->age;
        }
    }

    public function render()
    {
        return view('livewire.settings.age');
    }
}
