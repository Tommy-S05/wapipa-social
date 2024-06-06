<script setup>
import {ArrowDownTrayIcon, DocumentIcon} from '@heroicons/vue/24/outline'
import {isImage} from "@/helpers.js";

defineProps({
    attachments: Array,
})

const emit = defineEmits(['attachmentClick'])
</script>

<template>
    <template v-for="(attachment, index) of attachments.slice(0, 4)">
        <div
            @click="$emit('attachmentClick', index)"
            class="relative group bg-blue-100 aspect-square flex flex-col items-center justify-center text-gray-500 cursor-pointer"
        >
            <!--Download-->
            <a
                @click.stop
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

            <div v-else
                 class="flex flex-col items-center justify-center"
            >
                <DocumentIcon class="w-10 h-10 mb-3"/>
                <small class="text-center">
                    {{ attachment.name }}
                </small>
            </div>

            <div v-if="index === 3 && attachments.length > 4"
                 class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white text-center flex items-center justify-center text-2xl">
                +{{ attachments.length - 3 }} more
            </div>
        </div>
    </template>
</template>
