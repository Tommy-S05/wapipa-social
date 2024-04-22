<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue'
import {PencilIcon, TrashIcon, EllipsisVerticalIcon, ArrowDownTrayIcon} from '@heroicons/vue/20/solid'
import {HandThumbUpIcon, ChatBubbleLeftRightIcon} from '@heroicons/vue/24/outline'
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import {router} from "@inertiajs/vue3";
import {isImage} from "@/helpers.js";
import {DocumentIcon} from "@heroicons/vue/24/solid/index.js";

const props = defineProps({
    post: Object
})

const emit = defineEmits(['editClick'])

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

</script>

<template>
    <div class="bg-white border rounded p-4 shadow">
        <header class="flex items-center justify-between mb-3">
            <PostUserHeader :post="post"/>
            <Menu as="div" class="relative inline-block text-left z-20">
                <div>
                    <MenuButton
                        class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center"
                    >
                        <EllipsisVerticalIcon
                            class="w-5 h-5"
                            aria-hidden="true"
                        />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
                    >
                        <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                                <button
                                    @click="openEditModalEmit"
                                    :class="[
                                        active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                    ]"
                                >
                                    <PencilIcon
                                        class="mr-2 h-5 w-5"
                                        aria-hidden="true"
                                    />
                                    Edit
                                </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <button
                                    @click="deletePost"
                                    :class="[
                                        active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                    ]"
                                >
                                    <TrashIcon
                                        class="mr-2 h-5 w-5"
                                        aria-hidden="true"
                                    />
                                    Delete
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </header>

        <div class="mb-3">
            <Disclosure v-slot="{ open }">
                <div class="ck-content-output" v-if="!open" v-html="post.body.substring(0, 200)"/>
                <template v-if="post.body.length > 200">
                    <DisclosurePanel>
                        <div class="ck-content-output" v-html="post.body"/>
                    </DisclosurePanel>
                    <div class="flex justify-end">
                        <DisclosureButton class="text-blue-500 hover:underline">
                            {{ open ? 'Read Less' : 'Read More' }}
                        </DisclosureButton>
                    </div>
                </template>
            </Disclosure>
        </div>
        <div
            class="grid gap-3 mb-3"
            :class="[
                post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
            ]"
        >
            <template v-for="(attachment, index) of post.attachments.slice(0, 4)">
                <div
                    class="relative group bg-blue-100 aspect-square flex flex-col items-center justify-center text-gray-500"
                >
                    <!--Download-->
                    <a
                        :href="route('post.download', attachment.id)"
                        class="w-8 h-8 z-10 flex items-center justify-center text-gray-100 bg-gray-700 hover:bg-gray-800 rounded absolute right-2 top-2 cursor-pointer opacity-0 group-hover:opacity-100 transition-all"
                    >
                        <ArrowDownTrayIcon class="w-4 h-4"/>
                    </a>

                    <img v-if="isImage(attachment)"
                         :src="attachment.url"
                         alt=""
                         class="aspect-square object-cover"
                    />

                    <template v-else>
                        <DocumentIcon class="w-10 h-10 mb-3"/>
                        <small>
                            {{ attachment.name }}
                        </small>
                    </template>

                    <div v-if="index === 3 && post.attachments.length > 4"
                         class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white text-center flex items-center justify-center text-2xl">
                        +{{ post.attachments.length - 3 }} more
                    </div>
                </div>
            </template>
        </div>
        <div class="flex gap-2">
            <button
                class="flex flex-1 gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg py-2 px-4 text-gray-800"
            >
                <HandThumbUpIcon class="w-6 h-6 mr-2"/>
                Like
            </button>
            <button
                class="flex flex-1 gap-1 items-center justify-center bg-gray-100 hover:bg-gray-200 rounded-lg py-2 px-4 text-gray-800"
            >
                <ChatBubbleLeftRightIcon class="w-6 h-6 mr-2"/>
                Comment
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
