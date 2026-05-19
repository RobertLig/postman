<div x-data='{
    open: {{ request()->cookie('consent_answered') ? 'false' : 'true' }},

    analytics: false,

    googleAds: false,

    async save() {

        await fetch("/cookie-consent", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",

                "X-CSRF-TOKEN": document
                    .querySelector("meta[name=csrf-token]")
                    .content
            },

            body: JSON.stringify({
                analytics: this.analytics,
                google_ads: this.googleAds,
            })
        });

        window.location.reload();
    },

    async essentialsOnly() {

        await fetch("/cookie-consent", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",

                "X-CSRF-TOKEN": document
                    .querySelector("meta[name=csrf-token]")
                    .content
            },

            body: JSON.stringify({
                analytics: false,
                google_ads: false,
            })
        });

        window.location.reload();
    },
}'
    x-show="open" x-transition class="fixed bottom-5 right-5 z-50 w-full max-w-md">

    <div class="card bg-base-100 shadow-2xl border border-base-300">

        <div class="card-body">

            <h2 class="card-title">
                Cookies
            </h2>

            <p class="text-sm opacity-80">
                We use cookies to improve your experience.
            </p>

            <div class="form-control">

                <label class="label cursor-pointer">

                    <span class="label-text">
                        Analytics
                    </span>

                    <input type="checkbox" class="toggle toggle-info" x-model="analytics">
                </label>

                <label class="label cursor-pointer">

                    <span class="label-text">
                        Google Ads
                    </span>

                    <input type="checkbox" class="toggle toggle-info" x-model="googleAds">
                </label>

            </div>

            <div class="card-actions justify-end mt-4">

                <button type="button" class="btn btn-ghost" @click.prevent="essentialsOnly()">

                    Essentials only
                </button>

                <button type="button" class="btn btn-info" @click.prevent="save()">

                    Save settings
                </button>

            </div>

        </div>
    </div>
</div>
