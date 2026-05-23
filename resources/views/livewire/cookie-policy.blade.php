<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Cookie policy')] class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = __('Cookie policy');
    }
}; ?>

<div class="space-y-10">
    <x-header title="{{ __('Cookie policy') }}" separator />

    <div class="prose max-w-none">

        @if (app()->getLocale() === 'pl')
            <h2>Czym są pliki cookies?</h2>

            <p>
                Pliki cookies to niewielkie pliki tekstowe zapisywane na urządzeniu użytkownika
                podczas korzystania z naszej strony internetowej. Pomagają one zapewnić
                prawidłowe działanie serwisu, poprawić komfort użytkowania oraz analizować ruch
                na stronie.
            </p>

            <h2>Jakich cookies używamy?</h2>

            <h3>1. Niezbędne cookies</h3>

            <p>
                Te pliki cookies są konieczne do prawidłowego działania strony internetowej.
                Obejmują między innymi zapisanie informacji o zgodzie na cookies oraz
                utrzymanie podstawowych funkcji aplikacji.
            </p>

            <h3>2. Cookies analityczne</h3>

            <p>
                Za zgodą użytkownika używamy cookies analitycznych do zbierania anonimowych
                informacji o sposobie korzystania ze strony. Pomaga nam to ulepszać działanie
                aplikacji i doświadczenie użytkowników.
            </p>

            <h3>3. Cookies reklamowe Google Ads</h3>

            <p>
                Za zgodą użytkownika możemy używać cookies Google Ads do wyświetlania bardziej
                dopasowanych reklam oraz mierzenia skuteczności kampanii reklamowych.
            </p>

            <h2>Zarządzanie cookies</h2>

            <p>
                Użytkownik może w każdej chwili zmienić swoje ustawienia cookies lub wycofać
                zgodę poprzez ustawienia przeglądarki albo ponowne otwarcie banera cookies,
                jeśli jest dostępny.
            </p>

            <h2>Kontakt</h2>

            <p>
                Jeśli masz pytania dotyczące polityki cookies, skontaktuj się z nami za pomocą
                formularza kontaktowego dostępnego w serwisie.
            </p>
        @else
            <h2>What are cookies?</h2>

            <p>
                Cookies are small text files stored on a user's device while using our website.
                They help ensure the website functions properly, improve user experience, and
                allow us to analyze website traffic.
            </p>

            <h2>What cookies do we use?</h2>

            <h3>1. Essential cookies</h3>

            <p>
                These cookies are necessary for the proper functioning of the website.
                They include storing cookie consent preferences and maintaining the core
                functionality of the application.
            </p>

            <h3>2. Analytics cookies</h3>

            <p>
                With the user's consent, we use analytics cookies to collect anonymous
                information about how visitors use the website. This helps us improve the
                application and user experience.
            </p>

            <h3>3. Google Ads cookies</h3>

            <p>
                With the user's consent, we may use Google Ads cookies to display more relevant
                advertisements and measure the effectiveness of advertising campaigns.
            </p>

            <h2>Managing cookies</h2>

            <p>
                Users can change their cookie preferences or withdraw consent at any time
                through their browser settings or by reopening the cookie consent banner,
                if available.
            </p>

            <h2>Contact</h2>

            <p>
                If you have any questions regarding this cookie policy, please contact us
                through the contact form available on the website.
            </p>
        @endif

    </div>
</div>
