<template>
    <section class="flex w-full gap-2">
        <ProfilePhoto :friend="request.user_id === $page.props.user.id? request.user: request.by_user" :name="getName()" />

        <div class="p-1 py-4">
            {{ getName() }} {{ getSurname() }}
            <div>
                <button type="button" class="button px-2 text-sm bg-red-500 text-white text-bold" @click.once="confirm()">Approve</button>
                <button type="button" class="button px-2 text-sm bg-green-500 text-white text-bold" @click.once="reject()">Reject</button>
            </div>
        </div>
    </section>
</template>

<script>
import ProfilePhoto from './ProfilePhoto'

export default {
    name: "PendingRequests",

    components: {
        ProfilePhoto
    },

    props: {
        request: {
            type: [Object, Array],
            required: false
        },
        rejecturl: {
            type: String,
            required: true
        },
        confirmurl: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            _token: document.querySelector('meta[name=csrf-token]').content
        }
    },

    methods: {
        getName: function() {
            return this.request.user_id === this.$page.props.user.id? this.request.user.name: this.request.by_user.name
        },

        getSurname: function() {
            return this.request.user_id === this.$page.props.user.id? this.request.user.surname: this.request.by_user.surname
        },

        reject: function() {
            this.$inertia.post(
                this.rejecturl, {
                    _token: this._token,
                    _method: 'PUT',
                    frid: this.request.id
                }
            );
        },

        confirm: function() {
            this.$inertia.post(
                this.confirmurl, {
                    _token: this._token,
                    _method: 'PUT',
                    frid: this.request.id
                }
            );
        }
    }
}
</script>
