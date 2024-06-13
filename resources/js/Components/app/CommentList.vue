<script setup>

import TextareaInput from "@/Components/TextareaInput.vue";
import DangerButton from "@/Components/DangerButton.vue";
import IndigoButton from "@/Components/app/IndigoButton.vue";
import {
    ChatBubbleLeftRightIcon,
    ChatBubbleOvalLeftEllipsisIcon,
    HandThumbDownIcon,
    HandThumbUpIcon
} from "@heroicons/vue/24/outline/index.js";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import ReadMoreReadLess from "@/Components/app/ReadMoreReadLess.vue";
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import axiosClient from "@/axiosClient.js";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";

const props = defineProps({
    post: Object,
    data: Object,
    parentComment: {
        type: [Object, null],
        default: null
    }
});

const authUser = usePage().props.auth.user;

const newCommentText = ref('');
const editingComment = ref({});

function createComment() {
    axiosClient.post(route('comment.create', props.post.id), {
        comment: newCommentText.value,
        parent_id: props.parentComment?.id || null
    }).then(({data}) => {
        newCommentText.value = '';
        // props.post.comments = [data.comment, ...props.post.comments];
        // props.post.comments_count = data.comments_count;
        props.data.comments.unshift(data.comment);

        if (props.parentComment) {
            props.parentComment.comments_count++;
        }

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
    axiosClient.put(route('comment.update', editingComment.value.id), {
        comment: editingComment.value.comment
    }).then(({data}) => {
        props.data.comments = props.data.comments.map(c => c.id === data.comment.id ? data.comment : c);
        editingComment.value = {};
    }).catch((error) => {
        console.log(error);
    });
}

function deleteComment(comment) {
    if (window.confirm('Are you sure you want to delete this comment?')) {
        axiosClient.delete(route('comment.delete', comment.id))
            .then(() => {
                // props.post.comments_count = data.comments_count;
                const commentIndex = props.data.comments.findIndex(c => c.id === comment.id);
                props.data.comments.splice(commentIndex, 1);
                // props.data.comments = props.data.comments.filter(c => c.id !== comment.id);

                if (props.parentComment) {
                    props.parentComment.comments_count--;
                }

                props.post.comments_count--;

            })
            .catch((error) => {
                console.log(error);
            });
    }
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

</script>

<template>
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
        <div v-for="comment of data.comments" :key="comment.id">
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
                        <small class="text-xs text-gray-400">{{ comment.updated_at }}</small>
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
                <Disclosure v-slot="{ open }">
                    <!--Reactions & Comment Buttons-->
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

                        <DisclosureButton
                            class="flex items-center text-xs text-indigo-500 px-1 py-0.5 hover:bg-indigo-100 rounded-lg">
                            <ChatBubbleOvalLeftEllipsisIcon class="w-3 h-3 mr-1"/>
                            <span class="mr-2">{{ comment.comments_count }}</span>
                            Comments
                        </DisclosureButton>
                    </div>

                    <!--Comments Section-->
                    <DisclosurePanel class="mt-3">
                        <CommentList
                            :post="post"
                            :data="{comments: comment.comments}"
                            :parent-comment="comment"
                        />
                    </DisclosurePanel>
                </Disclosure>

            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
