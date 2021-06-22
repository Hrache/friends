<template>
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div v-if="canLogin" class="fixed top-0 right-0 px-6 py-4 sm:block">
            <inertia-link v-if="$page.props.user" :href="route('dashboard')" class="text-sm text-gray-700 underline">
                Dashboard
            </inertia-link>

            <template v-else>
                <inertia-link :href="route('login')" class="text-sm text-gray-700 underline">
                    Log in
                </inertia-link>

                <inertia-link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 underline">
                    Register
                </inertia-link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full">
            <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg w-full p-4">
                <h3 class="text-center text-bold text-4xl mb-2">People finder</h3>

                <div class="grid grid-cols-12 rounded">
                    <button @click="search()" class="bg-green-500 text-white col-span-2">Search</button>
                    <input type="text" v-model="searchQuery" class="col-span-10" @keyup.enter="search()" />
                </div>

                <div class="p-4" v-show="results && results.length">
                    <PeopleFound :people="results" :search-query="searchQuery" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>

<script>
import axios from 'axios'
import PeopleFound from "./Chips/PeopleFound"

export default {
    components: {
        PeopleFound
    },

    props: {
        canLogin: Boolean,
        canRegister: Boolean
    },

    data() {
        return {
            searchQuery: "",
            results: {}
        }
    },

    methods: {
        search() {
            this.results = {}

            if ( !this.searchQuery) {
                window.confirm( 'Please enter what to search')
                return false;
            }

            let self = this;

            axios({
                method: 'post',
                url: route('search.people'),
                data: {
                    '_token': document.querySelector('meta[name=csrf-token]').content,
                    'toSearch': this.searchQuery
                }
            })
            .then(function (response) {
                self.results = response.data.people
            });
        }
    }
}
</script>
