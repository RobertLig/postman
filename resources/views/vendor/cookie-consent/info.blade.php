@foreach ($cookies->getCategories() as $category) 
    <h3 class="mt-5 font-medium">{{ $category->title }}</h3>

    @php
        $headers = [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'cookie', 'label' => __('cookieConsent::cookies.cookie'), 'class' => 'w-20'],
            ['key' => 'purpose', 'label' => __('cookieConsent::cookies.purpose')],
            ['key' => 'duration', 'label' => __('cookieConsent::cookies.duration'), 'class' => 'w-64', 'sortable' => false],
        ];

        $data = collect();

        foreach ($category->getCookies() as $key => $cookie) {
            //dd($cookie->description);
            $data->push(['id' => $key+1, 'cookie' => $cookie->name, 'purpose' => $cookie->description, 'duration' => \Carbon\CarbonInterval::minutes($cookie->duration)->cascade()]);
        }
    @endphp

    <x-card shadow>
        <x-table :headers="$headers" :rows="$data" />
    </x-card>
@endforeach


{{-- original --}}
{{-- @foreach($cookies->getCategories() as $category)
<h3>{{ $category->title }}</h3>
<table>
    <thead>
        <th>@lang('cookieConsent::cookies.cookie')</th>
        <th>@lang('cookieConsent::cookies.purpose')</th>
        <th>@lang('cookieConsent::cookies.duration')</th>
    </thead>
    <tbody>
    @foreach($category->getCookies() as $cookie)
        <tr>
            <td>{{ $cookie->name }}</td>
            <td>{{ $cookie->description }}</td>
            <td>{{ \Carbon\CarbonInterval::minutes($cookie->duration)->cascade() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endforeach --}}
