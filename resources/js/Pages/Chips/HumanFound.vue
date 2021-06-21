<template>
    <li class="p-4">
        <span v-if="$page.props.user" :class="'p-2 cursor-pointer ' + friendedClass + ' text-white text-center rounded'" @click.once="friendRequest()">Friend</span>
        <span class="ml-4">{{ human.name }} {{ human.surname }}</span>
    </li>
</template>

<script>
import axios from 'axios'

export default {
    name: "HumanFound",

    props: {
        'human': {
            type: Object,
            required: true
        },
        'friendurl': {
            type: String,
            required: true
        },
        'friendcheckurl': {
            type: String,
            required: true
        }
    },

    data() {
        return {
            friendedClass: 'bg-green-700'
        }
    },

    methods: {
        friendRequest()
        {
            let self = this;

            axios({
                method: 'post',
                url: self.friendurl,
                data: {
                    '_token': document.querySelector('meta[name=csrf-token]').content,
                    'uac': self.human.id
                }
            })
            .then(function (response) {
                if ( response.data.success ) {
                    self.friendedClass = 'bg-red-700'
                }
                else {
                    alert("Failed to friend the user.")
                }
            });
        }
    }
}
</script>
