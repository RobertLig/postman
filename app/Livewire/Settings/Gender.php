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
    public ?string $gender = null;

    public ?string $originalGender = null;

    public array $genderOptions = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->gender = $user->gender;
        $this->originalGender = $user->gender;

        $this->genderOptions = [
            ['id' => 'male', 'name' => __('Male')],
            ['id' => 'female', 'name' => __('Female')],
        ];
    }

    public function updateGender(): void
    {
        $this->validate();

        $user = Auth::user();

        if ($this->gender === $user->gender) {
            $this->info(__('No changes detected.'));
            return;
        }

        $user->update([
            'gender' => $this->gender,
        ]);

        $this->originalGender = $this->gender;

        $this->success(__('The gender field was updated successfully!'));
    }

    public function clearGender(): void
    {
        $this->gender = null;
    }

    public function resetGender(): void
    {
        $this->gender = $this->originalGender;
    }

    public function render()
    {
        return view('livewire.settings.gender');
    }
}
