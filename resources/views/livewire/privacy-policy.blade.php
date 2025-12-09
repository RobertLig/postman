<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Privacy policy')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Privacy policy') }}" separator />

    <div class=" font-semibold">{{ __('Postman Privacy Policy.') }}</div>

    <p class="">{{ __('Last updated: ') }}2025-12-09 13:40:21</p>

    <p class="mt-5">{{ __('This Privacy Policy describes how Postman (the "Site", "we", "us", or "our") collects, uses, and discloses your personal information when you visit, use our services, or otherwise communicate with us (collectively, the "Services"). For purposes of this Privacy Policy, "you" and "your" means you as the user of the Services, website visitor, or another individual whose information we have collected pursuant to this Privacy Policy.') }}</p>

    <p class="mt-5">{{ __('Please read this Privacy Policy carefully. By using and accessing any of the Services, you agree to the collection, use, and disclosure of your information as described in this Privacy Policy. If you do not agree to this Privacy Policy, please do not use or access any of the Services.') }}</p>

    <p class="mt-5 font-semibold">{{ __('Changes to This Privacy Policy') }}</p>

    <p class="mt-5">{{ __('We may update this Privacy Policy from time to time, including to reflect changes to our practices or for other operational, legal, or regulatory reasons. We will post the revised Privacy Policy on the Site, update the "Last updated" date and take any other steps required by applicable law.') }}</p>

    <p class="mt-5">{{ __('In addition to the specific uses set out below, we may use information we collect about you to communicate with you, provide the Services, comply with any applicable legal obligations, enforce any applicable terms of service, and to protect or defend the Services, our rights, and the rights of our users or others') }}</p>

    <p class="mt-5 font-semibold">{{ __('What Personal Information We Collect') }}</p>

    <p class="mt-5">{{ __('The types of personal information we obtain about you depends on how you interact with our Site and use our Services. When we use the term "personal information", we are referring to information that identifies, relates to, describes or can be associated with you. The following sections describe the categories and specific types of personal information we collect.') }}</p>

    <p class="mt-5 font-semibold">{{ __('Information We Collect Directly from You') }}</p>

    <p class="mt-5">{{ __('Information that you directly submit to us through our Services may include:') }}</p>

    <ul class="list-disc mt-3 ms-10">
        <li>{{ __('Your name and surname.') }}</li>
        <li>{{ __('Your email address.') }}</li>
        <li>{{ __('Your password.') }}</li>
        <li>{{ __('Optionally, your age.') }}</li>
        <li>{{ __('Optionally, your gender.') }}</li>
        <li>{{ __('Optionally, your photo.') }}</li>
    </ul>

    <p class="mt-5">{{ __('Some features of the Services may require you to directly provide us with certain information about yourself. You may elect not to provide this information, but doing so may prevent you from using or accessing these features.') }}</p>

    <p class="mt-5 font-semibold">{{ __('Information We Collect through Cookies') }}</p>

    <p class="mt-5">{{ __('We also automatically collect certain information about your interaction with the Services ("Usage Data"). To do this, we may use cookies. Usage Data may include information about how you access and use our Site and your account, including device information, browser information, information about your network connection, your IP address and other information regarding your interaction with the Services.') }}</p>

    <p class="mt-5">{{ __('We don\'t collect information about you from Third Parties') }}</p>

    <p class="mt-5 font-semibold">{{ __('How We Use Your Personal Information') }}</p>

    <ul class="list-disc mt-3 ms-10">
        <li>{{ __('Providing Products and Services. We use your personal information to provide you with the Services in order to perform our contract with you, to send notifications to you related to you account, to create, maintain and otherwise manage your account and to enable you topost reviews.') }}</li>
        <li>{{ __('Security and Fraud Prevention. We use your personal information to detect, investigate or take action regarding possible fraudulent, illegal or malicious activity. If you choose to use the Services and register an account, you are responsible for keeping your account credentials safe. We highly recommend that you do not share your username, password, or other access details with anyone else. If you believe your account has been compromised, please contact us immediately.') }}</li>
        <li>{{ __('Communicating with you. We use your personal information to provide you with customer support and improve our Services. This is in our legitimate interests in order to be responsive to you, to provide effective services to you, and to maintain our business relationship with you.') }}</li>
    </ul>

    <p class="mt-5 font-semibold">{{ __('Cookies') }}</p>

    <p class="mt-5">{{ __('Like many websites, we use Cookies on our Site. For specific information about the Cookies that we use visit ') }}<a href="{{ route('cookie-policy') }}" class="link ">{{ __('cookie policy page') }}</a>.</p>

    <p class="mt-5">{{ __('We use Cookies to power and improve our Site and our Services (including to remember your actions and preferences), to run analytics and better understand user interaction with the Services (in our legitimate interests to administer, improve and optimize the Services).') }}</p>

    <p class="mt-5">{{ __('Most browsers automatically accept Cookies by default, but you can choose to set your browser to remove or reject Cookies through your browser controls. Please keep in mind that removing or blocking Cookies can negatively impact your user experience and may cause some of the Services, including certain features and general functionality, to work incorrectly or no longer be available.') }}</p>

    <p class="mt-5 font-semibold">{{ __('How We Disclose Personal Information') }}</p>

    <ul class="list-disc mt-3 ms-10">
        <li>{{ __('When you direct, request us or otherwise consent to our disclosure of certain information to third parties.') }}</li>
    </ul>

    <p class="mt-5">{{ __('We do not use or disclose sensitive personal information for the purposes of inferring characteristics about you.') }}</p>

    <p class="mt-5">{{ __('The Services may enable you to post product reviews and other user-generated content. If you choose to submit user generated content to any public area of the Services, this content will be public and accessible by anyone.') }}</p>

    <p class="mt-5">{{ __('We do not control who will have access to the information that you choose to make available to others, and cannot ensure that parties who have access to such information will respect your privacy or keep it secure. We are not responsible for the privacy or security of any information that you make publicly available, or for the accuracy, use or misuse of any information that you disclose or receive from third parties') }}</p>

    <p class="mt-5">{{ __('Our Site doesn\'t provide links to websites or other online platforms operated by third parties') }}</p>

    <p class="mt-5 font-semibold">{{ __('Children\'s Data') }}</p>

    <p class="mt-5">{{ __('The Services are not intended to be used by children, and we do not knowingly collect any personal information about children. If you are the parent or guardian of a child who has provided us with their personal information, you may contact us using the ' ) }} <a href="{{ route('contact') }}" class="link ">{{ __('contact') }}</a> {{ __('page to request that it be deleted.') }}</p>

    <p class="mt-5">{{ __('As of the Effective Date of this Privacy Policy, we do not have actual knowledge that we "share" or "sell" (as those terms are defined in applicable law) personal information of individuals under 16 years of age.') }}</p>

    <p class="mt-5 font-semibold">{{ __('Security and Retention of Your Information') }}</p>

    <p class="mt-5">{{ __('Please be aware that no security measures are perfect or impenetrable, and we cannot guarantee "perfect security." In addition, any information you send to us may not be secure while in transit. We recommend that you do not use unsecure channels to communicate sensitive or confidential information to us.') }}</p>
</div>
