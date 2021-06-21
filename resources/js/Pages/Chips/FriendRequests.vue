<template>
    <section v-for="(frequest, key) in requests" class="p-4" :key="key">

        <form :action="confirmurl" method="post" @submit.prevent="rejectConfirm()">
            <input type="hidden" :value="_token" name="_token" />
            <input type="hidden" value="put" name="_method" />
            <input type="hidden" :value="frequest.user_id" name="uac" />
            <input type="submit" value="Confirm" class="button p-2 bg-red-500 text-white text-bold" />
            <input type="submit" value="Reject" class="button p-2 bg-red-500 text-white text-bold" @mousedown="action='reject';" />
        </form>
    </section>
</template>

<script>
export default {
    name: "FriendRequests",

    props: {
        requests: {
            type: [Object, Array],
            required: false
        },
        confirmurl: {
            type: String,
            required: true
        },
        rejecturl: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            _token: document.querySelector('meta[name=csrf-token]').content,
            action: 'confirm'
        }
    },

    method: {
        rejectConfirm: function() {
            if (this.action === 'confirm') {

            }
        }
    }
}
</script>
