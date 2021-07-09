<template>
    <img :src="friend.profile_photo_url" :class="imageClass" />
</template>

<script>
    export default {
        props: {
            friend: {},
            name: {
                type: String,
                required: true,
                validator(value) {
                    return value != '';
                }
            },
            imageClass: {
                type: String,
                default: "w-16",
                required: false
            }
        }
    }
</script>