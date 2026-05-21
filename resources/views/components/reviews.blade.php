<div class="mt-30" x-data="{
    open: false,

    testimonial: null,

    scroll(direction) {

        const container = this.$refs.container;

        const card = container.querySelector('[data-slide]');

        if (!card) {
            return;
        }

        const gap = 24; // gap-6
        const amount = card.offsetWidth + gap;

        container.scrollBy({
            left: direction * amount,
            behavior: 'smooth',
        });
    },
}">

    {{-- Heading --}}
    <h2 class="text-3xl font-bold text-center">
        {{ __('What people are saying') }}
    </h2>

    <p class="text-center text-base-content/70 mt-3 max-w-2xl mx-auto">
        {{ __('Trusted by senders and travelers using our platform.') }}
    </p>

    {{-- Carousel --}}
    <div class="relative mt-12">

        {{-- Left arrow --}}
        <button type="button"
            class="
                absolute left-0 top-1/2 z-30
                hidden md:flex
                items-center justify-center
                w-10 h-10 rounded-full
                bg-base-100 border border-base-300 shadow
                -translate-y-1/2
            "
            @click="scroll(-1)">
            <x-icon name="o-chevron-left" class="w-5 h-5" />
        </button>

        {{-- Right arrow --}}
        <button type="button"
            class="
                absolute right-0 top-1/2 z-30
                hidden md:flex
                items-center justify-center
                w-10 h-10 rounded-full
                bg-base-100 border border-base-300 shadow
                -translate-y-1/2
            "
            @click="scroll(1)">
            <x-icon name="o-chevron-right" class="w-5 h-5" />
        </button>

        {{-- Slides --}}
        <div x-ref="container"
            class="
                flex gap-6
                overflow-x-auto
                scroll-smooth
                snap-x snap-mandatory
                px-4 md:px-12
                pb-2
                scrollbar-hide
            ">

            @foreach ($testimonials as $testimonial)
                <div data-slide
                    class="
                        snap-start
                        shrink-0
                        w-full
                        sm:w-[calc(50%-12px)]
                        lg:w-[calc(33.333%-16px)]
                    "
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

                    <div
                        class="
                            card bg-base-200 shadow-sm border border-base-300
                            cursor-pointer hover:shadow-md transition
                            h-full
                        ">

                        <div class="card-body gap-5 h-full">

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
                            <div class="flex items-center justify-between pt-2 mt-auto">

                                <span class="text-xs text-base-content/50">
                                    {{ $testimonial->published_at?->format('M Y') }}
                                </span>

                                <x-button label="{{ __('Read more') }}" class="btn-ghost btn-sm" />

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    {{-- Modal --}}
    <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>

        {{-- Modal content --}}
        <div x-transition
            class="
                relative
                w-full max-w-2xl
                rounded-2xl
                bg-base-100
                shadow-2xl
                border border-base-300
                p-6
            ">

            <template x-if="testimonial">

                <div class="space-y-6">

                    {{-- Header --}}
                    <div class="flex items-center gap-4">

                        <x-avatar :image="null" x-bind:placeholder="testimonial.initials" class="!w-14" />
                        {{-- placeholder="AA" x-bind:placeholder --}}

                        <div>

                            <div class="font-bold text-lg" x-text="testimonial.name"></div>

                            <div class="text-sm text-base-content/60" x-text="testimonial.role"></div>

                        </div>

                    </div>

                    {{-- Rating --}}
                    <div class="flex gap-1">

                        <template x-for="i in 5" :key="i">

                            <svg x-show="i <= testimonial.rating" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" class="w-5 h-5 text-warning">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.95-.69l1.07-3.292z" />
                            </svg>

                            <svg x-show="i > testimonial.rating" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-5 h-5 text-warning">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.94a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557L3.04 10.385a.562.562 0 01.32-.988l5.518-.442a.563.563 0 00.476-.345l2.125-5.11z" />
                            </svg>

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

    {{-- Trustpilot card --}}
    <div class="mt-12 max-w-md mx-auto">

        <x-external-review-card />

    </div>

</div>
