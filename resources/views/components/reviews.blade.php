<div class="mt-30">

    <h2 class="text-3xl font-bold text-center">
        {{ __('What people are saying') }}
    </h2>

    <p class="text-center text-base-content/70 mt-3 max-w-2xl mx-auto">
        {{ __('Trusted by senders and travelers using our platform.') }}
    </p>

    <div x-data="{
        open: false,
        testimonial: null
    }">
        <div class="grid gap-6 mt-12 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($testimonials as $testimonial)
                <div class="card bg-base-200 shadow-sm border border-base-300 cursor-pointer hover:shadow-md transition"
                    @click="
                        testimonial = {
                            name: @js($testimonial->name),
                            role: @js($testimonial->role),
                            content: @js($testimonial->content),
                            rating: @js($testimonial->rating),
                            published_at: @js($testimonial->published_at?->format('F Y')),
                            initials: @js($testimonial->initials()),
                        };

                        open = true;
                    ">

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

        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

            {{-- Modal --}}
            <div x-transition
                class="relative w-full max-w-2xl rounded-2xl bg-base-100 shadow-2xl border border-base-300 p-6">

                <template x-if="testimonial">

                    <div class="space-y-6">

                        {{-- Header --}}
                        <div class="flex items-center gap-4">

                            <x-avatar placeholder="AA" x-bind:placeholder="testimonial.initials" class="!w-14" />

                            <div>

                                <div class="font-bold text-lg" x-text="testimonial.name"></div>

                                <div class="text-sm text-base-content/60" x-text="testimonial.role"></div>

                            </div>

                        </div>

                        {{-- Rating --}}
                        <div class="flex gap-1">

                            <template x-for="i in 5" :key="i">

                                <x-icon name="s-star" class="w-5 h-5 text-warning" x-show="i <= testimonial.rating" />

                            </template>

                        </div>

                        {{-- Content --}}
                        <p class="leading-relaxed text-base-content/80" x-text="testimonial.content"></p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4">

                            <span class="text-sm text-base-content/50" x-text="testimonial.published_at"></span>

                            <x-button label="{{ __('Close') }}" @click="open = false" />

                        </div>

                    </div>

                </template>

            </div>

        </div>

    </div>

    {{-- Trustpilot card preserved --}}
    <div class="mt-12 max-w-md mx-auto">

        <x-external-review-card />

    </div>

</div>
