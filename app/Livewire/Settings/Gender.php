<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

class Gender extends Component
{
    use Toast;

    #[Validate('nullable|in:male,female')]
    public $gender;

    #[Validate('nullable|in:male,female')]
    public $genderPrev;

    public function mount(): void
    {
        $user = Auth::user();

        $this->gender = $user->gender;

        $this->genderPrev = $user->gender;
    }

    public function updateGender(): void
    {
        $user = Auth::user();

        $user->update(['gender' => $this->gender]);

        $this->success(__('The gender field was updated successfully!'), position: 'toast-bottom');
    }

    public function resetGender(): void
    {
        if ($this->genderPrev === $this->gender) {
            $this->gender = null;

            $this->genderPrev = null;
        } else {
            $this->genderPrev = $this->gender;
        }
    }

    public function render()
    {
        return view('livewire.settings.gender');
    }
}
