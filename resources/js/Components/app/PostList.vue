<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {ref} from "vue";

defineProps({
    posts: {
        type: Array
    }
});

const showEditModal = ref(false);
const editPost = ref({});

function openEditModal(post){
    editPost.value = post;
    showEditModal.value = true;
}

const post1 = {
    user: {
        id: 1,
        name: 'John Smith',
        avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
    },
    group: null,
    attachments: [
        {
            id: 1,
            name: 'test.png',
            url: 'https://picsum.photos/1000',
            mime: 'image/png'
        },
        {
            id: 2,
            name: 'test2.png',
            url: 'https://picsum.photos/1000',
            mime: 'image/png'
        },
        {
            id: 2,
            name: 'MyDocument.docx',
            url: 'url',
            mime: ''
        }
    ],
    body: `<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus cumque eius, est et molestias pariatur qui tempora unde. Atque cupiditate deleniti impedit laborum maxime velit. Cum in nihil quis quo.</p>

    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus cumque eius, est et molestias pariatur qui tempora unde. Atque cupiditate deleniti impedit laborum maxime velit. Cum in nihil quis quo.</p>`,
    created_at: '2023-11-19 15:12'
}

const post2 = {
    user: {
        id: 2,
        name: 'John Does',
        avatar: 'https://randomuser.me/api/portraits/men/79.jpg'
    },
    group: {
        id: 1,
        name: 'Laravel Developers'
    },
    body: `<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus cumque eius, est et molestias pariatur qui tempora unde. Atque cupiditate deleniti impedit laborum maxime velit. Cum in nihil quis quo.</p>

    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus cumque eius, est et molestias pariatur qui tempora unde. Atque cupiditate deleniti impedit laborum maxime velit. Cum in nihil quis quo.</p>`,
    created_at: '2023-11-19 15:12'
}
</script>

<template>
    <div class="space-y-4 mb-3 flex-1 overflow-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post" @editClick="openEditModal"/>
    </div>

    <PostModal :post="editPost" v-model="showEditModal"/>
</template>

<style scoped>

</style>
