<script setup>
import PostItem from "@/Components/app/PostItem.vue";
import PostModal from "@/Components/app/PostModal.vue";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import AttachmentPreviewModal from "@/Components/app/AttachmentPreviewModal.vue";

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

</script>

<template>
    <div class="space-y-4 mb-3 flex-1 overflow-auto">
        <PostItem v-for="post of posts" :key="post.id" :post="post"
                  @editClick="openEditModal"
                  @attachmentClick="openAttachmentPreviewModal"
        />
    </div>

    <PostModal :post="editPost" v-model="showEditModal" @hide="onModalHide"/>
    <AttachmentPreviewModal
        :attachments="previewAttachmentsPost.post?.attachments || []"
        v-model:index="previewAttachmentsPost.index"
        v-model="showAttachmentsModal"
    />
</template>

<style scoped>

</style>
