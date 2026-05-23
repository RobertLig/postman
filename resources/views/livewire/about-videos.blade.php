<div class="grid gap-6 md:grid-cols-2">

    @foreach ($videos as $id)
        <div x-data="{ playing: false }"
            class="relative w-full overflow-hidden rounded-lg bg-black cursor-pointer aspect-video focus:outline-none focus:ring-2 focus:ring-info"
            @click="playing = true" @keydown.enter="playing = true" tabindex="0">

            <template x-if="!playing">

                <div class="w-full h-full bg-cover bg-center"
                    style="background-image: url('https://img.youtube.com/vi/{{ $id }}/hqdefault.jpg')">

                    <div class="absolute inset-0 bg-black/30"></div>

                    <div class="absolute inset-0 flex items-center justify-center">

                        <button type="button"
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-600 text-white shadow-lg transition hover:bg-red-700 hover:scale-105"
                            aria-label="{{ __('Play video') }}">
                            ▶
                        </button>

                    </div>

                </div>

            </template>

            <template x-if="playing">

                <iframe class="absolute inset-0 w-full h-full"
                    src="https://www.youtube.com/embed/{{ $id }}?autoplay=1"
                    title="{{ __('How the platform works') }}" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen loading="lazy"></iframe>

            </template>

        </div>
    @endforeach

</div>
