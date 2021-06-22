<template>
    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg w-full p-4">
        <h3 class="text-center text-bold text-4xl mb-2">Search friends</h3>

        <div class="grid grid-cols-12 sm:grid-rows-2 md:grid-rows-1 lg:grid-rows-1">
            <input type="text" v-model="searchQuery" class="col-span-8" @keyup.enter="search()" />
            <button @click="search()" class="col-span-2 bg-blue-500 text-white">Search</button>
            <button @click="clean()" class="col-span-2 bg-red-500 text-white">Clean</button>
        </div>

        <div class="p-4" v-if="friends && friends.length">
            <div class="px-1 pt-1" v-for="(friend, key) in friends" :key="key">
                <a :href="friendlink" class="pt-1">{{ friend.name }}</a> <span class="pt-1">{{ friend.surname }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "FriendSearch",

    props: ['searchurl'],

    data() {
        return {
            searchQuery: "",
            friends: ""
        }
    },

    methods: {
        getName: function(friend) {
            return friend.user_id === this.$page.props.user.id? friend.user.name: friend.by_user.name
        },

        getSurname: function(friend) {
            return friend.user_id === this.$page.props.user.id? friend.user.surname: friend.by_user.surname
        },

        clean: function() {
            this.searchQuery = '';
            this.friends = null;
        },

        search() {
            if ( !this.searchQuery) {
                window.confirm( 'Please enter what to search')
                return false;
            }

            let self = this;

            axios({
                method: 'post',
                url: this.searchurl,
                data: {
                    _token: document.querySelector('meta[name=csrf-token]').content,
                    toSearch: this.searchQuery
                }
            })
            .then(function (response) {
                self.friends = response.data.people
            });
        }
    }
}
</script>
