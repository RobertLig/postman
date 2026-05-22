<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Age extends Component
{
    use Toast;

    #[Validate([
        'age' => 'nullable|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90',
    ])]
    public ?string $age = null;

    public ?string $originalAge = null;

    public array $ageOptions = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->age = $user->age;
        $this->originalAge = $user->age;

        $this->ageOptions = [
            ['id' => '< 20', 'name' => __('< 20')],
            ['id' => '20 to 29', 'name' => __('20 to 29')],
            ['id' => '30 to 39', 'name' => __('30 to 39')],
            ['id' => '40 to 49', 'name' => __('40 to 49')],
            ['id' => '50 to 59', 'name' => __('50 to 59')],
            ['id' => '60 to 69', 'name' => __('60 to 69')],
            ['id' => '70 to 79', 'name' => __('70 to 79')],
            ['id' => '80 to 89', 'name' => __('80 to 89')],
            ['id' => '> 90', 'name' => __('> 90')],
        ];
    }

    public function updateAge(): void
    {
        $this->validate();

        $user = Auth::user();

        if ($this->age === $user->age) {
            $this->info(__('No changes detected.'));
            return;
        }

        $user->update([
            'age' => $this->age,
        ]);

        $this->originalAge = $this->age;

        $this->success(
            __('The age field has been updated successfully!'),
            position: 'toast-bottom'
        );
    }

    public function clearAge(): void
    {
        $this->age = null;
    }

    public function resetAge(): void
    {
        $this->age = $this->originalAge;
    }

    public function render()
    {
        return view('livewire.settings.age');
    }
}
