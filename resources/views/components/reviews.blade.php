<div class="mt-30">

    <h2 class="text-3xl font-bold text-center">
        {{ __('What people are saying') }}
    </h2>

    <p class="text-center text-base-content/70 mt-3 max-w-2xl mx-auto">
        {{ __('Trusted by senders and travelers using our platform.') }}
    </p>

    <div class="grid gap-6 mt-12 sm:grid-cols-2 lg:grid-cols-3">

        @foreach ($testimonials as $testimonial)
            <div class="card bg-base-200 shadow-sm border border-base-300">

                <div class="card-body gap-5">

                    {{-- Header --}}
                    <div class="flex items-center gap-4">

                        <x-avatar :image="null" :placeholder="$testimonial->initials()" class="!w-12" />

                        <div>

                            <div class="font-semibold">
                                {{ $testimonial->name }}
                            </div>

                            @if ($testimonial->role)
                                <div class="text-sm text-base-content/60">
                                    {{ $testimonial->role }}
                                </div>
                            @endif

                        </div>

                    </div>

                    {{-- Rating --}}
                    <div class="flex gap-0.5">

                        @for ($i = 1; $i <= 5; $i++)
                            <x-icon name="{{ $i <= $testimonial->rating ? 's-star' : 'o-star' }}"
                                class="w-4 h-4 text-warning" />
                        @endfor

                    </div>

                    {{-- Content --}}
                    <p class="text-base-content/80 leading-relaxed">
                        {{ Str::limit($testimonial->content, 140) }}
                    </p>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-2">

                        <span class="text-xs text-base-content/50">
                            {{ $testimonial->published_at?->format('M Y') }}
                        </span>

                        <x-button label="{{ __('Read more') }}" class="btn-ghost btn-sm" />

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    {{-- Trustpilot card preserved --}}
    <div class="mt-12 max-w-md mx-auto">

        <x-external-review-card />

    </div>

</div>
