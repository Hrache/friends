<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Friends
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                The list of friends you made until now.
            </p>
        </template>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-if="pending && pending.length || requested && requested.length">
                <div class="py-1 flex justify-end">
                    <span class="px-2 bg-red-700 text-white text-sm font-bold cursor-pointer" @click="togglePendings()">
                        <span v-show="pendings">Hide</span> <span v-show="!pendings">Show</span> pending requests
                    </span>
                </div>

                <div v-show="pendings" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 mb-4">
                    <section v-if="pending && pending.length">
                        <Pending
                            v-for="(request, key) in pending" :key="key"
                            :request="request"
                            :confirmurl="route('friends.confirm')"
                            :rejecturl="route('friends.reject')"
                        />
                    </section>

                    <section v-if="requested && requested.length">
                        <Requested
                            v-for="(request, key) in requested" :key="key"
                            :request="request"
                            :cancelurl="route('friends.cancel', {
                                'cancel': request.id
                            })"
                        />
                    </section>
                </div>
            </div>
            <div class="text-gray-400 text-2xl font-bold max-w-7xl mx-auto sm:px-6 lg:px-8" v-else>
                No pending requests
            </div>
        </div>

        <section class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <FriendSearch :searchurl="route('friends.search')" />
            </div>
        </section>

        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 mb-4">
                    <friends-list v-if="friends.length">
                        <FriendsListItem
                            v-for="(friend, key) in friends"
                            :friend="friend"
                            unfriendurl="friends/{{ friend.id }}/delete"
                            :key="key"
                        />
                    </friends-list>

                    <div v-else class="sm:p-2 text-2xl lg:p-4 md:p-4">
                        You have no friends yet
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import FriendsList from './Chips/FriendsList'
    import FriendsListItem from './Chips/FriendsListItem'
    import Pending from './Chips/Pending'
    import Requested from './Chips/Requested'
    import FriendSearch from "./Chips/FriendSearch";

    export default {
        components: {
            AppLayout,
            FriendsList,
            FriendsListItem,
            Pending,
            Requested,
            FriendSearch
        },

        data() {
            return {
                pendings: true
            };
        },

        props: {
            friends: {
                type: [Object, Array],
                required: false
            },
            pending: {
                type: [Object, Array],
                required: false
            },
            requested: {
                type: [Object, Array],
                required: false
            },
        },

        methods: {
            togglePendings: function() {
                this.pendings = !this.pendings
            }
        },

        mounted() {
            //
        }
    }
</script>
