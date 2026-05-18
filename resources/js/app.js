import "./bootstrap";

import Sortable from "sortablejs";

window.Sortable = Sortable;

/* document.addEventListener("alpine:init", () => {
    Alpine.data("cookieConsent", () => ({
        open: !document.cookie.includes("cookie_consent="),

        analytics: false,

        googleAds: false,

        save() {
            const consent = {
                analytics: this.analytics,
                google_ads: this.googleAds,
            };

            document.cookie =
                "cookie_consent=" +
                encodeURIComponent(JSON.stringify(consent)) +
                "; path=/; max-age=" +
                60 * 60 * 24 * 365;

            console.log("tata");

            window.location.reload();
        },

        essentialsOnly() {
            const consent = {
                analytics: false,
                google_ads: false,
            };

            document.cookie =
                "cookie_consent=" +
                encodeURIComponent(JSON.stringify(consent)) +
                "; path=/; max-age=" +
                60 * 60 * 24 * 365;

            window.location.reload();
        },
    }));
}); */
