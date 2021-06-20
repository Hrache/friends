<template>
    <li class="p-4 border rounded">
        <h4>{{ friend.id }}</h4>

        <strong>{{ friend.by_user? friend.by_user.name: friend.user.name }} {{ friend.by_user? friend.by_user.surname: friend.user.surname }}</strong>
        <div class="text-xs mb-2">Name and surname</div>

        <strong>{{ friend.by_user? friend.by_user.email: friend.user.email }}</strong>
        <div class="text-xs mb-2">Email</div>

        <div @click="submitUnfriend()" class="button w-1/4 bg-red-500 rounded border text-white text-center p-1 cursor-pointer">Unfriend</div>
    </li>
</template>

<script>
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

    methods: {
        submitUnfriend() {
            this.$inertia.post(route('friends.delete', {
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
