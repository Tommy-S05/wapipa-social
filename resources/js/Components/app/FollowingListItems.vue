<script setup>

import FollowingItem from "@/Components/app/FollowingItem.vue";
import TextInput from "@/Components/TextInput.vue";
import {ref} from "vue";

const searchKeyword = ref('');
</script>

<template>
    <TextInput
        :model-value="searchKeyword"
        placeholder="Type to search"
        class="w-full"
    />
    <div class="mt-3 h-[300px] lg:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-400 text-center p-3">
            You don't have followers yet.
        </div>
        <div v-else>
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="John Doe"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
            <FollowingItem
                image="https://picsum.photos/100"
                title="Elon Musk"
                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."
            />
        </div>
    </div>
</template>

<style scoped>

</style>
