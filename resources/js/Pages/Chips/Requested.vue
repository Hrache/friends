<template>
    <section class="flex w-full gap-2">
        <ProfilePhoto :friend="request.user_id === $page.props.user.id? request.user: request.by_user" :name="getName()" />

        <div class="p-1 py-4">
            <div>{{ getName() }} {{ getSurname() }}</div>
            <button type="button" class="button px-2 text-sm bg-green-500 text-white text-bold" @click.once="cancel()">Cancel</button>
        </div>
    </section>
</template>

<script>
import ProfilePhoto from './ProfilePhoto'

export default {
    name: "Pending",

    props: {
        request: {
            type: [Object, Array],
            required: false
        },
        cancelurl: {
            type: String,
            required: true
        }
    },
    
    components: {
        ProfilePhoto
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

        cancel: function() {
            this.$inertia.post(
                this.cancelurl, {
                    _token: this._token,
                    _method: 'DELETE',
                    frid: this.request.id
                }
            );
        }
    }
}
</script>
