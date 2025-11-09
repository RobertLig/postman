<div>
    <x-chat :chatMessages="$chatMessages" :selectedUser="$selectedUser" :auth-user-avatar="$authUserAvatar" 
        :selected-user-avatar="$selectedUserAvatar" timezone="{{ $timezone }}" :subtitle="$subtitle"
        senderAnnouncementID="{{ $senderAnnouncementID }}" courierAnnouncementID="{{ $courierAnnouncementID }}" :presenceIndicator="$presenceIndicator" /> {{-- :indicator="$presenceIndicator" --}}
</div>
