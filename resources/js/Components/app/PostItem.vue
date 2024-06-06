<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {
    ChatBubbleLeftRightIcon,
    ChatBubbleOvalLeftEllipsisIcon,
    HandThumbDownIcon,
    HandThumbUpIcon
} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router, usePage} from "@inertiajs/vue3";
import axiosClient from "@/axiosClient.js";
import TextareaInput from "@/Components/TextareaInput.vue";
import IndigoButton from "@/Components/app/IndigoButton.vue";
import {ref} from "vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";

const props = defineProps({
    post: Object
})

const authUser = usePage().props.auth.user;

const newCommentText = ref('');
const editingComment = ref({});

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

function sendCommentReaction(comment, reaction) {
    axiosClient.post(route('comment.reaction', comment.id), {
        reaction: reaction
    }).then(({data}) => {
        comment.reaction_type = data.reaction_type;
        comment.reactions_count = data.reactions_count;
    }).catch((error) => {
        console.log(error);
    });
}

function createComment() {
    axiosClient.post(route('post.comment.create', props.post.id), {
        comment: newCommentText.value
    }).then(({data}) => {
        newCommentText.value = '';
        // props.post.comments = [data.comment, ...props.post.comments];
        // props.post.comments_count = data.comments_count;
        props.post.comments.unshift(data.comment);
        props.post.comments_count++;
    }).catch((error) => {
        console.log(error);
    });
}

function startCommentEdit(comment) {
    editingComment.value = {
        id: comment.id,
        // Replace <br />, <br >, <br> and <br/> with new line
        comment: comment.comment.replace(/<br\s*\/?>/gi, '\n')
    };
}

function updateComment() {
    axiosClient.put(route('post.comment.update', editingComment.value.id), {
        comment: editingComment.value.comment
    }).then(({data}) => {
        props.post.comments = props.post.comments.map(c => c.id === data.comment.id ? data.comment : c);
        editingComment.value = {};
    }).catch((error) => {
        console.log(error);
    });
}

function deleteComment(comment) {
    if (window.confirm('Are you sure you want to delete this comment?')) {
        axiosClient.delete(route('post.comment.delete', comment.id))
            .then(() => {
                // props.post.comments_count = data.comments_count;
                props.post.comments = props.post.comments.filter(c => c.id !== comment.id);
                props.post.comments_count--;
            })
            .catch((error) => {
                console.log(error);
            });
    }
}

</script>

<template>
    <div class="bg-white border rounded p-4 shadow">
        <header class="flex items-center justify-between mb-3">
            <PostUserHeader :post="post"/>
            <EditDeleteDropdown v-if="post.user.id === authUser.id" :user="post.user" @edit="openEditModalEmit" @delete="deletePost"/>
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
                    Comment
                </DisclosureButton>
            </div>

            <!--Comments Section-->
            <DisclosurePanel class="mt-3">
                <!--Create comment section-->
                <div class="flex gap-2 mb-3">
                    <a href="javascript:void(0)">
                        <img
                            :src="authUser.avatar_url || '/img/default_avatar.webp'"
                            class="w-10 rounded-full border border-2 hover:border-blue-500 transition-all"
                        />
                    </a>
                    <div class="flex flex-1">
                        <TextareaInput
                            v-model="newCommentText"
                            :rows="1"
                            placeholder="Write a comment..."
                            auto-resize
                            class="w-full max-h-40 resize-none rounded-r-none"
                        />
                        <IndigoButton
                            @click="createComment"
                            class="w-28 rounded-l-none max-w-28"
                        >
                            Comment
                        </IndigoButton>
                    </div>
                </div>

                <!--Comments-->
                <div class="space-y-4">
                    <div v-for="comment of post.comments" :key="comment.id">
                        <!--Comment Header Section-->
                        <div class="flex justify-between gap-2">
                            <div class="flex gap-2">
                                <a href="javascript:void(0)">
                                    <img
                                        :src="comment?.user?.avatar_url || '/img/default_avatar.webp'"
                                        class="w-10 rounded-full border border-2 hover:border-blue-500 transition-all"
                                    />
                                </a>

                                <div>
                                    <h4 class="font-bold">
                                        <a href="javascript:void(0)"
                                           class="hover:underline"
                                        >
                                            {{ comment?.user?.name }}
                                        </a>
                                    </h4>
                                    <small class="text-xs text-gray-400">{{comment.updated_at}}</small>
                                </div>
                            </div>

                            <EditDeleteDropdown
                                :user="comment.user"
                                @edit="startCommentEdit(comment)"
                                @delete="deleteComment(comment)"
                            />
                        </div>

                        <!--Comment Body Section-->
                        <div class="pl-12">
                            <!--Edit Comment Section-->
                            <div v-if="editingComment?.id === comment.id">
                                <TextareaInput
                                    v-model="editingComment.comment"
                                    :rows="1"
                                    placeholder="Write a comment..."
                                    auto-resize
                                    class="w-full max-h-40 resize-none"
                                />

                                <div class="flex justify-end">
                                    <DangerButton
                                        @click="editingComment = {}"
                                        class="rounded-r-none"
                                    >
                                        Cancel
                                    </DangerButton>
                                    <IndigoButton
                                        @click="updateComment"
                                        class="w-28 max-w-28 rounded-l-none"
                                    >
                                        Update
                                    </IndigoButton>
                                </div>

                                <!--                            <div class="flex gap-2 justify-end">-->
                                <!--                                <button class="rounded-r-none text-indigo-500" @click="editingComment = {}">-->
                                <!--                                    cancel-->
                                <!--                                </button>-->
                                <!--                                <IndigoButton-->
                                <!--                                    @click="createComment"-->
                                <!--                                    class="w-28 max-w-28"-->
                                <!--                                >-->
                                <!--                                    update-->
                                <!--                                </IndigoButton>-->
                                <!--                            </div>-->
                            </div>

                            <!--Comment Content Section-->
                            <ReadMoreReadLess
                                v-else
                                :content="comment?.comment"
                                content-class="text-sm flex flex-1"
                            />

                            <!--Comment Reaction Section-->
                            <div class="flex gap-2 mt-1">
                                <button class="flex items-center text-xs text-indigo-500 px-1 py-0.5 rounded-lg"
                                        :class="[
                                            comment.reaction_type === 'like' ? 'bg-indigo-50 hover:bg-indigo-100' : 'hover:bg-indigo-50'
                                        ]"
                                        @click="sendCommentReaction(comment, 'like')"
                                >
                                    <HandThumbUpIcon class="w-3 h-3 mr-1"/>
                                    <span class="mr-2">{{ comment.reactions_count.like }}</span>
                                    Like
                                </button>
                                <button class="flex items-center text-xs text-indigo-500 px-1 py-0.5 rounded-lg"
                                        :class="[
                                            comment.reaction_type === 'dislike' ? 'bg-indigo-50 hover:bg-indigo-100' : 'hover:bg-indigo-50'
                                        ]"
                                        @click="sendCommentReaction(comment, 'dislike')"
                                >
                                    <HandThumbDownIcon class="w-3 h-3 mr-1"/>
                                    <span class="mr-2">{{ comment.reactions_count.dislike }}</span>
                                    Dislike
                                </button>
                                <button class="flex items-center text-xs text-indigo-500 px-1 py-0.5 hover:bg-indigo-100 rounded-lg">
                                    <ChatBubbleOvalLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

