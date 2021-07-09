<template>
    <li class="p-4 border rounded flex justify-content-start align-items-start">
        <ProfilePhoto :friend="friend.user_id === $page.props.user.id? friend.user: friend.by_user" :name="getName()" imageClass="w-48" />

        <div class="p-2">
            <div class="text-xs">Name and surname</div>
            <strong class="mb-4">{{ getName() }} {{ getSurname() }}</strong>

            <div class="text-xs">Email</div>
            <strong class="mb-4">{{ getEmail() }}</strong>

            <div @click="submitUnfriend()" class="mt-4 bg-red-500 rounded border text-white text-center p-1 cursor-pointer">Unfriend</div>
        </div>
    </li>
</template>

<script>
import ProfilePhoto from './ProfilePhoto'

export default {
    props: {
        friend: {
            type: [Array, Object],
            required: true
        },

        unfriendurl: {
            type: String,
            required: true
        }
    },
    
    components: {
        ProfilePhoto
    },
    
    computed: {
        profile_photo() {
            return this.friend.user && this.friend.user.profile_photo_path?
                    '/storage/' + this.friend.user.profile_photo_path:
                    'https://ui-avatars.com/api/?name=' + this.getName() + '&color=7F9CF5&background=EBF4FF';
        }
    },

    methods: {
        getName() {
            return this.friend.by_user? this.friend.by_user.name: this.friend.user.name
        },

        getSurname() {
            return this.friend.by_user? this.friend.by_user.surname: this.friend.user.surname
        },

        getEmail() {
            return this.friend.by_user? this.friend.by_user.email: this.friend.user.email
        },

        submitUnfriend() {
            if ( !confirm( 'Are you sure you want to unfriend the ' + this.getName() + ' ' + this.getSurname() + '?')) {
                return false
            }

            this.$inertia.post(
                route('friends.delete', {
                friend: this.friend.id
            }), {
                _token: document.querySelector('meta[name=csrf-token]').content,
                _method: 'DELETE'
            }, {
                preserveState: false
            });
        }
    }
}
</script>
