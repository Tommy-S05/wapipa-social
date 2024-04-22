<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

defineProps({
    posts: {
        type: Array
    }
});

const authUser = usePage().props.auth.user;
const showEditModal = ref(false);
const editPost = ref({});

function openEditModal(post){
    editPost.value = post;
    showEditModal.value = true;
}

function onModalHide() {
    editPost.value = {
        id: null,
        body: '',
        user: authUser
    };
}

</script>

<template>
    <div class="space-y-4 mb-3 flex-1 overflow-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    </div>

    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
</template>

<style scoped>

</style>
