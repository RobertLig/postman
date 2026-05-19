<div>
    <footer class="footer sm:footer-horizontal bg-base-200 text-base-content p-10">
        <aside>
            <x-app-brand />
        </aside>
        <nav>
            <h6 class="footer-title">{{ __('Company') }}</h6>
            <a class="link link-hover" href="{{ route('about') }}">{{ __('About us') }}</a>
            <a class="link link-hover" href="{{ route('faq') }}">{{ __('FAQ') }}</a>
            <a class="link link-hover" href="{{ route('contact') }}">{{ __('Contact') }}</a>
        </nav>
        <nav>
            <h6 class="footer-title">{{ __('Legal') }}</h6>
            <a class="link link-hover" href="{{ route('terms-of-use') }}">{{ __('Terms of use') }}</a>
            <a class="link link-hover" href="{{ route('privacy-policy') }}">{{ __('Privacy policy') }}</a>
            <a class="link link-hover" href="{{ route('cookie-policy') }}">{{ __('Cookie policy') }}</a>

            <button class="btn btn-ghost btn-sm"
                onclick="
                fetch('/cookie-consent/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN':
                            document.querySelector('meta[name=csrf-token]').content
                    }
                }).then(() => location.reload())
            ">
                Cookie settings
            </button>
        </nav>
    </footer>
    <footer class="footer sm:footer-horizontal footer-center bg-base-200 text-base-content p-4">
        <aside class="leading-7">
            <p>{{ __('Copyright') }} © {{ date('Y') }} - {{ __('All right reserved by') }} Postman Industries Ltd
            </p>
        </aside>
    </footer>
</div>
