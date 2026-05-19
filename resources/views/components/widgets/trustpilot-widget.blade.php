<div class="card bg-base-200 shadow-xl border border-base-300">

    <div class="card-body items-center text-center gap-4">

        <div class="flex items-center gap-1">

            @for ($i = 0; $i < 5; $i++)
                <x-icon name="s-star" class="w-5 h-5 text-warning" />
            @endfor

        </div>

        <div>

            <h2 class="card-title justify-center text-2xl">
                {{ __('Trusted by our users') }}
            </h2>

            <p class="text-base-content/70 mt-2 max-w-sm">
                {{ __('Read verified customer reviews on Trustpilot.') }}
            </p>

        </div>

        <a href="https://www.trustpilot.com/review/postman.chat" target="_blank" rel="noopener noreferrer"
            class="btn btn-info btn-wide">

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                <path d="M12 2l2.9 6.1 6.7.6-5 4.4 1.5 6.5L12 16.9 5.9 19.6l1.5-6.5-5-4.4 6.7-.6L12 2z" />
            </svg>

            {{ __('View reviews on Trustpilot') }}

        </a>

    </div>
</div>
