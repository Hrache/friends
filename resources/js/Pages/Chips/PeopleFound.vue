<template>
    <ol v-if="people.length">
        <Human :human="human" :friendurl="route('friends.store')" v-for="(human, key) in people" :key="key" />
    </ol>
    <h3 v-else>
        No people have been found "{{ searchQuery }}"
    </h3>
</template>

<script>
import Human from './HumanFound'

export default {
    components: {
        Human
    },

    props: ["people", "searchQuery"]
}
</script>
