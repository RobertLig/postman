<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = __("PLEASE READ THIS TERMS OF SERVICE AGREEMENT CAREFULLY, AS IT CONTAINS IMPORTANT INFORMATION REGARDING YOUR LEGAL RIGHTS AND REMEDIES.");
    }
}; ?>

<div>
    <x-header title="{{ __('Terms of use') }}" separator /> 

    <div> 
        <div class="text-center text-lg">{{ __('TERMS OF SERVICES') }}</div>
        <p class="mt-5">{{ __('PLEASE READ THIS TERMS OF SERVICE AGREEMENT CAREFULLY, AS IT CONTAINS IMPORTANT INFORMATION REGARDING YOUR LEGAL RIGHTS AND REMEDIES.') }}</p>

        <p class="mt-5">{{ __('Last Revised: 2025-12-03 15:34:24') }}</p>

        <h2 class="mt-5 font-medium">{{ __('1. OVERVIEW') }}</h2>

        <p class="mt-3">{{ __('This Terms of Service Agreement ("Agreement") is entered into by and between Postman, registered address Kościuszki 79a, Poland ("Company") and you, and is made effective as of the date of your use of this website http://www.postman.chat ("Site") or the date of electronic acceptance.') }}</p>

        <p class="mt-3">{{ __('This Agreement sets forth the general terms and conditions of your use of the http://www.postman.chat as well as the products and/or services purchased or accessed through this Site (the "Services").Whether you are simply browsing or using this Site or purchase Services, your use of this Site and your electronic acceptance of this Agreement signifies that you have read, understand, acknowledge and agree to be bound by this Agreement our Privacy policy. The terms "we", "us" or "our" shall refer to Company. The terms "you", "your", "User" or "customer" shall refer to any individual or entity who accepts this Agreement, uses our Site, has access or uses the Services. Nothing in this Agreement shall be deemed to confer any third-party rights or benefits.') }}</p>
        
        <p class="mt-3">{{ __('Company may, in its sole and absolute discretion, change or modify this Agreement, and any policies or agreements which are incorporated herein, at any time, and such changes or modifications shall be effective immediately upon posting to this Site. Your use of this Site or the Services after such changes or modifications have been made shall constitute your acceptance of this Agreement as last revised.') }}</p>

        <p class="mt-3">{{ __('IF YOU DO NOT AGREE TO BE BOUND BY THIS AGREEMENT AS LAST REVISED, DO NOT USE (OR CONTINUE TO USE) THIS SITE OR THE SERVICES.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('2. ELIGIBILITY') }}</h2>

        <p class="mt-3">{{ __('This Site and the Services are available only to Users who can form legally binding contracts under applicable law. By using this Site or the Services, you represent and warrant that you are (i) at least eighteen (18) years of age, (ii) otherwise recognized as being able to form legally binding contracts under applicable law, and (iii) are not a person barred from purchasing or receiving the Services found under the laws of the Poland or other applicable jurisdiction.') }}</p>

        <p class="mt-3">{{ __('If you are entering into this Agreement on behalf of a company or any corporate entity, you represent and warrant that you have the legal authority to bind such corporate entity to the terms and conditions contained in this Agreement, in which case the terms "you", "your", "User" or "customer" shall refer to such corporate entity. If, after your electronic acceptance of this Agreement, Company finds that you do not have the legal authority to bind such corporate entity, you will be personally responsible for the obligations contained in this Agreement.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('3. RULES OF USER CONDUCT') }}</h2>

        <p class="mt-3">{{ __('By using this Site You acknowledge and agree that:') }}</p>

        <ul class="list-disc mt-3 ms-10">
            <li>{{ __('Your use of this Site, including any content you submit, will comply with this Agreement and all applicable local, state, national and international laws, rules and regulations.') }}</li>
        </ul>

        <p class="mt-5">{{ __('You will not use this Site in a manner that:') }}</p>

        <ul class="list-disc mt-3 ms-10">
            <li>{{ __('Is illegal, or promotes or encourages illegal activity;') }}</li>
            <li>{{ __('Promotes, encourages or engages in child pornography or the exploitation of children;') }}</li>
            <li>{{ __('Promotes, encourages or engages in terrorism, violence against people, animals, or property;') }}</li>
            <li>{{ __('Promotes, encourages or engages in any spam or other unsolicited bulk email, or computer or network hacking or cracking;') }}</li>
            <li>{{ __('Infringes on the intellectual property rights of another User or any other person or entity;') }}</li>
            <li>{{ __('Violates the privacy or publicity rights of another User or any other person or entity, or breaches any duty of confidentiality that you owe to another User or any other person or entity;') }}</li>
            <li>{{ __('Interferes with the operation of this Site;') }}</li>
            <li>{{ __('Contains or installs any viruses, worms, bugs, Trojan horses, Cryptocurrency Miners or other code, files or programs designed to, or capable of, using many resources, disrupting, damaging, or limiting the functionality of any software or hardware.') }}</li>
        </ul>

        <p class="mt-5">{{ __('You will not:') }}</p>

        <ul class="list-disc mt-3 ms-10">
            <li>{{ __('copy or distribute in any medium any part of this Site, except where expressly authorized by Company,') }}</li>
            <li>{{ __('copy or duplicate this Terms of Services agreement, which was created with the help of the Terms and Conditions Generator from TermsHub,') }}</li>
            <li>{{ __('modify or alter any part of this Site or any of its related technologies,') }}</li>
            <li>{{ __('access Companies Content (as defined below) or User Content through any technology or means other than through this Site itself.') }}</li>
        </ul>

        <h2 class="mt-5 font-medium">{{ __('4. INTELLECTUAL PROPERTY') }}</h2>

        <p class="mt-3">{{ __('In addition to the general rules above, the provisions in this Section apply specifically to your use of Companies Content posted to Site. Companies Content on this Site, including without limitation the text, software, scripts, source code, API, graphics, photos, sounds, music, videos and interactive features and the trademarks, service marks and logos contained therein ("Companies Content"), are owned by or licensed to Postman in perpetuity, and are subject to copyright, trademark, and/or patent protection.') }}</p>

        <p class="mt-3">{{ __('Companies Content is provided to you "as is", "as available" and "with all faults" for your information and personal, non-commercial use only and may not be downloaded, copied, reproduced, distributed, transmitted, broadcast, displayed, sold, licensed, or otherwise exploited for any purposes whatsoever without the express prior written consent of Company. No right or license under any copyright, trademark, patent, or other proprietary right or license is granted by this Agreement.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('5. YOUR USE OF USER CONTENT') }}</h2>

        <p class="mt-3">{{ __('Some of the features of this Site may allow Users to view, post, publish, share, or manage (a) ideas, opinions, recommendations, or advice ("User Submissions"), or (b) literary, artistic, musical, or other content, including but not limited to photos and videos (together with User Submissions, "User Content"). By posting or publishing User Content to this Site, you represent and warrant to Company that (i) you have all necessary rights to distribute User Content via this Site or via the Services, either because you are the author of the User Content and have the right to distribute the same, or because you have the appropriate distribution rights, licenses, consents, and/or permissions to use, in writing, from the copyright or other owner of the User Content, and (ii) the User Content does not violate the rights of any third party.') }}</p>

        <p class="mt-3">{{ __('You agree not to circumvent, disable or otherwise interfere with the security-related features of this Site (including without limitation those features that prevent or restrict use or copying of any Companies Content or User Content) or enforce limitations on the use of this Site, the Companies Content or the User Content therein.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('6. COMPANIES USE OF USER CONTENT') }}</h2>

        <p class="mt-3">{{ __('The provisions in this Section apply specifically to Companies use of User Content posted to Site.') }}</p>

        <p class="mt-3">{{ __('You shall be solely responsible for any and all of your User Content or User Content that is submitted by you, and the consequences of, and requirements for, distributing it.') }}</p>

        <p class="mt-3">{{ __('With Respect to User Submissions, you acknowledge and agree that:') }}</p>

        <ul class="list-disc mt-3 ms-10">
            <li>{{ __('Your User Submissions are entirely voluntary.') }}</li>
            <li>{{ __('Your User Submissions do not establish a confidential relationship or obligate Company to treat your User Submissions as confidential or secret.') }}</li>
            <li>{{ __('Company has no obligation, either express or implied, to develop or use your User Submissions, and no compensation is due to you or to anyone else for any intentional or unintentional use of your User Submissions.') }}</li>
        </ul>

        <p class="mt-3">{{ __('Company shall own exclusive rights (including all intellectual property and other proprietary rights) to any User Submissions posted to this Site, and shall be entitled to the unrestricted use and dissemination of any User Submissions posted to this Site for any purpose, commercial or otherwise, without acknowledgment or compensation to you or to anyone else.') }}</p>
  
        <p class="mt-3">{{ __('With Respect to User Content, by posting or publishing User Content to this Site, you authorize Company to use the intellectual property and other proprietary rights in and to your User Content to enable inclusion and use of the User Content in the manner contemplated by this Site and this Agreement.') }}</p>

        <p class="mt-3">{{ __('You hereby grant Company a worldwide, non-exclusive, royalty-free, sublicensable, and transferable license to use, reproduce, distribute, prepare derivative works of, combine with other works, display, and perform your User Content in connection with this Site, including without limitation for promoting and redistributing all or part of this Site in any media formats and through any media channels without restrictions of any kind and without payment or other consideration of any kind, or permission or notification, to you or any third party. You also hereby grant each User of this Site a non-exclusive license to access your User Content through this Site, and to use, reproduce, distribute, prepare derivative works of, combine with other works, display, and perform your User Content as permitted through the functionality of this Site and under this Agreement.') }}</p>

        <p class="mt-3">{{ __('The above licenses granted by you in your User Content terminate within a commercially reasonable time after you remove or delete your User Content from this Site. You understand and agree, however, that Company may retain (but not distribute, display, or perform) server copies of your User Content that have been removed or deleted. The above licenses granted by you in your User Content are perpetual and irrevocable.') }}</p>

        <p class="mt-3">{{ __('Company generally does not pre-screen User Content but reserves the right (but undertakes no duty) to do so and decide whether any item of User Content is appropriate and/or complies with this Agreement. Company may remove any item of User Content if it violating this Agreement, at any time and without prior notice.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('7. DISCLAIMER OF REPRESENTATIONS AND WARRANTIES') }}</h2>

        <p class="mt-3">{{ __('YOU SPECIFICALLY ACKNOWLEDGE AND AGREE THAT YOUR USE OF THIS SITE SHALL BE AT YOUR OWN RISK AND THAT THIS SITE ARE PROVIDED "AS IS", "AS AVAILABLE" AND "WITH ALL FAULTS". COMPANY, ITS OFFICERS, DIRECTORS, EMPLOYEES, AGENTS, DISCLAIM ALL WARRANTIES, STATUTORY, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, ANY IMPLIED WARRANTIES OF TITLE, MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. COMPANY, ITS OFFICERS, DIRECTORS, EMPLOYEES, AND AGENTS MAKE NO REPRESENTATIONS OR WARRANTIES ABOUT (I) THE ACCURACY, COMPLETENESS, OR CONTENT OF THIS SITE, (II) THE ACCURACY, COMPLETENESS, OR CONTENT OF ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, AND/OR (III) THE SERVICES FOUND AT THIS SITE OR ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, AND COMPANY ASSUMES NO LIABILITY OR RESPONSIBILITY FOR THE SAME.') }}</p>

        <p class="mt-3">{{ __('IN ADDITION, YOU SPECIFICALLY ACKNOWLEDGE AND AGREE THAT NO ORAL OR WRITTEN INFORMATION OR ADVICE PROVIDED BY COMPANY, ITS OFFICERS, DIRECTORS, EMPLOYEES, OR AGENTS, AND THIRD-PARTY SERVICE PROVIDERS WILL (I) CONSTITUTE LEGAL OR FINANCIAL ADVICE OR (II) CREATE A WARRANTY OF ANY KIND WITH RESPECT TO THIS SITE OR THE SERVICES FOUND AT THIS SITE, AND USERS SHOULD NOT RELY ON ANY SUCH INFORMATION OR ADVICE.') }}</p>

        <p class="mt-3">{{ __('THE FOREGOING DISCLAIMER OF REPRESENTATIONS AND WARRANTIES SHALL APPLY TO THE FULLEST EXTENT PERMITTED BY LAW, and shall survive any termination or expiration of this Agreement or your use of this Site or the Services found at this Site.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('8. LIMITATION OF LIABILITY') }}</h2>

        <p class="mt-3">{{ __('IN NO EVENT SHALL COMPANY, ITS OFFICERS, DIRECTORS, EMPLOYEES, AGENTS, AND ALL THIRD PARTY SERVICE PROVIDERS, BE LIABLE TO YOU OR ANY OTHER PERSON OR ENTITY FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, PUNITIVE, OR CONSEQUENTIAL DAMAGES WHATSOEVER, INCLUDING ANY DAMAGES THAT MAY RESULT FROM (I) THE ACCURACY, COMPLETENESS, OR CONTENT OF THIS SITE, (II) THE ACCURACY, COMPLETENESS, OR CONTENT OF ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, (III) THE SERVICES FOUND AT THIS SITE OR ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, (IV) PERSONAL INJURY OR PROPERTY DAMAGE OF ANY NATURE WHATSOEVER, (V) THIRD-PARTY CONDUCT OF ANY NATURE WHATSOEVER, (VI) ANY INTERRUPTION OR CESSATION OF SERVICES TO OR FROM THIS SITE OR ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, (VII) ANY VIRUSES, WORMS, BUGS, TROJAN HORSES, OR THE LIKE, WHICH MAY BE TRANSMITTED TO OR FROM THIS SITE OR ANY SITES LINKED (THROUGH HYPERLINKS, BANNER ADVERTISING OR OTHERWISE) TO THIS SITE, (VIII) ANY USER CONTENT OR CONTENT THAT IS DEFAMATORY, HARASSING, ABUSIVE, HARMFUL TO MINORS OR ANY PROTECTED CLASS, PORNOGRAPHIC, "X-RATED", OBSCENE OR OTHERWISE OBJECTIONABLE, AND/OR (IX) ANY LOSS OR DAMAGE OF ANY KIND INCURRED AS A RESULT OF YOUR USE OF THIS SITE OR THE SERVICES FOUND AT THIS SITE, WHETHER BASED ON WARRANTY, CONTRACT, TORT, OR ANY OTHER LEGAL OR EQUITABLE THEORY, AND WHETHER OR NOT COMPANY IS ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.') }}</p>

        <p class="mt-3">{{ __('IN ADDITION, You SPECIFICALLY ACKNOWLEDGE AND agree that any cause of action arising out of or related to this Site or the Services found at this Site must be commenced within one (1) year after the cause of action accrues, otherwise such cause of action shall be permanently barred.') }}</p>

        <p class="mt-3">{{ __('THE FOREGOING LIMITATION OF LIABILITY SHALL APPLY TO THE FULLEST EXTENT PERMITTED BY LAW, AND shall survive any termination or expiration of this Agreement or your use of this Site or the Services found at this Site.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('9. INDEMNITY') }}</h2>

        <p class="mt-3">{{ __('You agree to protect, defend, indemnify and hold harmless Company and its officers, directors, employees, agents from and against any and all claims, demands, costs, expenses, losses, liabilities and damages of every kind and nature (including, without limitation, reasonable attorneys’ fees) imposed upon or incurred by Company directly or indirectly arising from (i) your use of and access to this Site; (ii) your violation of any provision of this Agreement or the policies or agreements which are incorporated herein; and/or (iii) your violation of any third-party right, including without limitation any intellectual property or other proprietary right. The indemnification obligations under this section shall survive any termination or expiration of this Agreement or your use of this Site or the Services found at this Site.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('10. DATA TRANSFER') }}</h2>

        <p class="mt-3">{{ __('If you are visiting this Site from a country other than the country in which our servers are located, your communications with us may result in the transfer of information across international boundaries. By visiting this Site and communicating electronically with us, you consent to such transfers.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('11. AVAILABILITY OF WEBSITE') }}</h2>

        <p class="mt-3">{{ __('Subject to the terms and conditions of this Agreement and our policies, we shall use commercially reasonable efforts to attempt to provide this Site on 24/7 basis. You acknowledge and agree that from time to time this Site may be inaccessible for any reason including, but not limited to, periodic maintenance, repairs or replacements that we undertake from time to time, or other causes beyond our control including, but not limited to, interruption or failure of telecommunication or digital transmission links or other failures.') }}</p>

        <p class="mt-3">{{ __('You acknowledge and agree that we have no control over the availability of this Site on a continuous or uninterrupted basis, and that we assume no liability to you or any other party with regard thereto.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('12. DISCONTINUED SERVICES') }}</h2>

        <p class="mt-3">{{ __('Company reserves the right to cease offering or providing any of the Services at any time, for any or no reason, and without prior notice. Although Company makes great effort to maximize the lifespan of all its Services, there are times when a Service we offer will be discontinued. If that is the case, that product or service will no longer be supported by Company. In such case, Company will either offer a comparable Service for you to migrate to or a refund. Company will not be liable to you or any third party for any modification, suspension, or discontinuance of any of the Services we may offer or facilitate access to.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('13. NO THIRD-PARTY BENEFICIARIES') }}</h2>

        <p class="mt-3">{{ __('Nothing in this Agreement shall be deemed to confer any third-party rights or benefits.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('14. COMPLIANCE WITH LOCAL LAWS') }}</h2>

        <p class="mt-3">{{ __('Company makes no representation or warranty that the content available on this Site are appropriate in every country or jurisdiction, and access to this Site from countries or jurisdictions where its content is illegal is prohibited. Users who choose to access this Site are responsible for compliance with all local laws, rules and regulations.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('15. GOVERNING LAW') }}</h2>

        <p class="mt-3">{{ __('This Agreement and any dispute or claim arising out of or in connection with it or its subject matter or formation shall be governed by and construed in accordance with the laws of Poland, śląsk, to the exclusion of conflict of law rules.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('16. DISPUTE RESOLUTION') }}</h2>

        <p class="mt-3">{{ __('The courts of Poland, śląsk shall have exclusive jurisdiction to settle any dispute or claim that arises out of or in connection with this agreement or its subject matter or formation.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('17. TITLES AND HEADINGS') }}</h2>

        <p class="mt-3">{{ __('The titles and headings of this Agreement are for convenience and ease of reference only and shall not be utilized in any way to construe or interpret the agreement of the parties as otherwise set forth herein.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('18. SEVERABILITY') }}</h2>

        <p class="mt-3">{{ __('Each covenant and agreement in this Agreement shall be construed for all purposes to be a separate and independent covenant or agreement. If a court of competent jurisdiction holds any provision (or portion of a provision) of this Agreement to be illegal, invalid, or otherwise unenforceable, the remaining provisions (or portions of provisions) of this Agreement shall not be affected thereby and shall be found to be valid and enforceable to the fullest extent permitted by law.') }}</p>

        <h2 class="mt-5 font-medium">{{ __('19. CONTACT INFORMATION') }}<h2>

        <p class="mt-3">{{ __('If you have any questions about this Agreement, please contact us by email or regular mail at the following address:') }}</p>

        <p class="mt-3">{{ __('Postman') }}</p>

        <p class="mt-3">{{ __('Kościuszki 79a') }}</p>

        <p class="mt-3">{{ __('42-582 Rogożnik') }}</p>

        <p class="mt-3">{{ __('Poland') }}</p>

        <p class="mt-3">{{ __('robertligeza2@gmail.com') }}</p>

        {{-- __('termsofservice.termsOfService') --}}

        {{-- __("The owner of the website is not liable for any damages resulting from its use.") --}}
    </div>
</div>
