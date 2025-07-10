<?php
//this component is not used in application. It doesn't work with toast
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\EmailVerificationRequest; 
use Mary\Traits\Toast;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class VerifyEmail //extends Component 
{
    use Toast;
    public function __invoke(EmailVerificationRequest $request)
    {
        $request->fulfill();

        //return redirect(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/'));

        $this->success(
            __('Verified successfully!'), 
            position: 'toast-bottom',
            redirectTo: LaravelLocalization::localizeUrl('/') 
        );
    }

    /*public function render()
    {
        return <<<'HTML' 
        <div>
            {{-- Your Blade template goes here... --}}
        </div>
        HTML;
    }*/
}
