<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {
    ChatBubbleLeftRightIcon,
    HandThumbDownIcon,
    HandThumbUpIcon
} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";
import CommentList from "@/Components/app/CommentList.vue";

const props = defineProps({
    post: Object
})

const authUser = usePage().props.auth.user;

const emit = defineEmits(['editClick', 'attachmentClick'])

function openEditModalEmit() {
    emit('editClick', props.post)
}

function deletePost() {
    router.delete(route('post.destroy', props.post.id), {
        preserveScroll: true,
        onBefore: () => confirm('Are you sure you want to delete this post?'),
    });

    // if (window.confirm('Are you sure you want to delete this post?')) {
    //     router.delete(route('post.destroy', props.post.id))
    // }
}

function openAttachment(index) {
    emit('attachmentClick', props.post, index);
}

function sendReaction(reaction) {
    axiosClient.post(route('post.reaction', props.post.id), {
        reaction: reaction
    }).then(({data}) => {
        props.post.reaction_type = data.reaction_type;
        props.post.reactions_count = data.reactions_count;
    }).catch((error) => {
        console.log(error);
    });
}

</script>

<template>
    <div class="bg-white border rounded p-4 shadow">
        <header class="flex items-center justify-between mb-3">
            <PostUserHeader :post="post"/>
            <EditDeleteDropdown v-if="post.user.id === authUser.id" :user="post.user" @edit="openEditModalEmit"
                                @delete="deletePost"/>
        </header>

        <!--Body Section-->
        <div class="mb-3">
            <ReadMoreReadLess :content="post.body"/>
        </div>

        <!--Attachments Section-->
        <div
            class="grid gap-3 mb-3"
            :class="[
                post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
            ]"
        >
            <PostAttachments
                :attachments="post.attachments"
                @attachmentClick="openAttachment"
            />
        </div>

        <!--Footer Section-->
        <Disclosure v-slot="{ open }">
            <!--Reactions & Comment Buttons-->
            <div class="flex gap-2">
                <button
                    @click="sendReaction('like')"
                    class="flex flex-1 gap-1 items-center justify-center rounded-lg py-2 px-4 text-gray-800"
                    :class="[
                        post.reaction_type === 'like' ? 'bg-sky-100 hover:bg-sky-200' : 'bg-gray-100 hover:bg-gray-200'
                    ]"
                >
                    <HandThumbUpIcon class="w-6 h-6"/>
                    <span class="mr-2">
                        {{ post.reactions_count.like }}
                        <!--{{post.reactions.like_count}}-->
                    </span>
                    Like
                </button>
                <button
                    @click="sendReaction('dislike')"
                    class="flex flex-1 gap-1 items-center justify-center rounded-lg py-2 px-4 text-gray-800"
                    :class="[
                        post.reaction_type === 'dislike' ? 'bg-sky-100 hover:bg-sky-200' : 'bg-gray-100 hover:bg-gray-200'
                    ]"
                >
                    <HandThumbDownIcon class="w-6 h-6"/>
                    <span class="mr-2">
                        {{ post.reactions_count.dislike }}
                    </span>
                    Dislike
                </button>

                <DisclosureButton
                    class="flex flex-1 gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg py-2 px-4 text-gray-800"
                >
                    <ChatBubbleLeftRightIcon class="w-6 h-6"/>
                    <span class="mr-2">{{ post.comments_count }}</span>
                    Comments
                </DisclosureButton>
            </div>

            <!--Comments Section-->
            <DisclosurePanel class="comment-list mt-3 max-h-96 overflow-auto">
                <CommentList
                    :post="post"
                    :data="{comments: post.comments}"
                />
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

