<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import { onMounted, ref } from "vue";
import {usePage} from "@inertiajs/vue3";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";
import axiosClient from "@/axiosClient.js";

const page = usePage();

const allPosts = ref({
    data: page.props.posts.data,
    next: page.props.posts.links.next
})

defineProps({
    posts: {
        type: Array
    }
});

const authUser = usePage().props.auth.user;
const showEditModal = ref(false);
const showAttachmentsModal = ref(false);
const editPost = ref({});
const previewAttachmentsPost = ref({});
const loadMoreIntersectRef = ref(null);

function openEditModal(post){
    editPost.value = post;
    showEditModal.value = true;
}

function openAttachmentPreviewModal(post, index) {
    // debugger;
    previewAttachmentsPost.value = {
        post,
        index
    };
    showAttachmentsModal.value = true;
}

function onModalHide() {
    editPost.value = {
        id: null,
        body: '',
        user: authUser
    };
}

function loadMore() {
    if (!allPosts.value.next) {
        return;
    }

    axiosClient.get(allPosts.value.next)
        .then(response => {
            allPosts.value.data = [...allPosts.value.data, ...response.data.data];
            allPosts.value.next = response.data.links.next;
        })
        .catch(error => {
            console.error(error);
        });
}

onMounted(() => {
   const observer = new IntersectionObserver(
       (entries) => entries.forEach(entry => entry.isIntersecting && loadMore()), {
       rootMargin: '-250px 0px 0px 0px',
   });

   observer.observe(loadMoreIntersectRef.value);
});

</script>

<template>
    <div class="space-y-4 flex-1 overflow-auto">
        <PostItem v-for="post of allPosts.data" :key="post.id" :post="post"
                  @editClick="openEditModal"
                  @attachmentClick="openAttachmentPreviewModal"
        />
        <div ref="loadMoreIntersectRef"></div>

        <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
        <AttachmentPreviewModal
            :attachments="previewAttachmentsPost.post?.attachments || []"
            v-model:index="previewAttachmentsPost.index"
            v-model="showAttachmentsModal"
        />
    </div>
</template>

<style scoped>

</style>
