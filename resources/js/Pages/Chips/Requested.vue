<template>
    <section class="pt-1">
        <header class="py-1">
            <span class="p-1">{{ getName() }}</span> <span class="p-1">{{ getSurname() }}</span>
        </header>
        <button type="button" class="button px-2 text-sm bg-green-500 text-white text-bold" @click.once="cancel()">Cancel</button>
    </section>
</template>

<script>
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
