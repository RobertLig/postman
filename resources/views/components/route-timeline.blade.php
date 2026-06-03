{{-- resources/views/components/route-timeline.blade.php --}}

@props(['from', 'fromDate', 'to', 'toDate'])

<div class="space-y-4">

    <div class="flex items-start gap-3">
        <div class="mt-1 text-primary">
            <x-icon name="o-map-pin" class="w-5 h-5 text-secondary" />
        </div>

        <div class="flex-1 min-w-0">
            <div class="font-semibold truncate">
                {{ $from }}
            </div>

            <div class="text-sm text-base-content/70">
                {{ $fromDate?->locale(app()->getLocale())->translatedFormat('D, d F Y, H:i') }}
            </div>
        </div>
    </div>

    <div class="border-l-2 border-base-300 h-6 ml-2"></div>

    <div class="flex items-start gap-3">
        <div class="mt-1 text-success">
            <x-icon name="o-flag" class="w-5 h-5 text-success" />
        </div>

        <div class="flex-1 min-w-0">
            <div class="font-semibold truncate">
                {{ $to }}
            </div>

            <div class="text-sm text-base-content/70">
                {{ $toDate?->locale(app()->getLocale())->translatedFormat('D, d F Y, H:i') }}
            </div>
        </div>
    </div>

</div>
