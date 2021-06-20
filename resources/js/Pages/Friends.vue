
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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 mb-4">
                    <friends-list v-if="friends.length">
                        <FriendsListItem
                            v-for="(friend, key) in friends"
                            :friend="friend"
                            unfriendurl="friends/{{ friend.id }}/delete"
                            :key="key" />
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

    export default {
        components: {
            AppLayout,
            FriendsList,
            FriendsListItem
        },

        data() {
            return {

            };
        },

        props: {
            friends: {
                type: [Array, Number],
                required: true
            }
        },

        mounted() {
            //
        }
    }
</script>
