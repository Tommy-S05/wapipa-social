<script setup>
import {computed} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {XMarkIcon, DocumentIcon, ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/24/solid';
import {isImage} from "@/helpers.js";
import {ArrowDownTrayIcon} from "@heroicons/vue/20/solid/index.js";

const props = defineProps({
    attachments: {
        type: Array,
        required: true
    },
    index: {
        type: Number
    },
    modelValue: {
        type: Boolean,
    }
});

const show = computed({
    get: () => props.modelValue,
    set: (newValue) => emit('update:modelValue', newValue)
});

const currentIndex = computed({
    get: () => props.index,
    set: (newValue) => emit('update:index', newValue)
});

const attachment = computed(() => {
    return props.attachments[currentIndex.value];
});

const emit = defineEmits(['update:modelValue', 'update:index', 'hide'])

function closeModal() {
    show.value = false;
    emit('hide');
}

function prev() {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = props.attachments.length - 1;
    }
}

function next() {
    if (currentIndex.value < props.attachments.length - 1){
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
}

</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">

            <Dialog as="div" @close="closeModal" class="relative z-40">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div class="fixed inset-0 bg-black/25"/>
                </TransitionChild>

                <div class="fixed inset-0 overflow-y-auto">
                    <div
                        class="h-full w-full"
                    >
                        <TransitionChild
                            as="template"
                            class="w-full h-full"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full transform overflow-hidden bg-slate-800 text-left align-middle shadow-xl transition-all"
                            >
                                <button
                                    @click="closeModal"
                                    class="absolute right-3 top-3 z-30 w-12 h-12 rounded-full hover:bg-black/50 transition flex items-center justify-center"
                                >
                                    <XMarkIcon class="w-6 h-6 text-gray-100"/>
                                </button>

                                <div class="relative group h-full">
                                    <!--Download-->
                                    <div class="absolute top-3 w-full flex items-center justify-center">
                                        <a
                                            :href="route('post.download', attachment.id)"
                                            class="w-8 h-8 z-10 flex items-center justify-center text-gray-100 bg-gray-600 hover:bg-gray-700 rounded cursor-pointer opacity-0 group-hover:opacity-100 transition-all"
                                        >
                                            <ArrowDownTrayIcon class="w-4 h-4"/>
                                        </a>
                                    </div>

                                    <div
                                        @click="prev"
                                        class="absolute flex items-center w-16 h-full opacity-0 group-hover:opacity-100 text-gray-100 cursor-pointer left-0 bg-black/5">
                                        <ChevronLeftIcon class="w-16"/>
                                    </div>

                                    <div
                                        @click="next"
                                        class="absolute flex items-center w-16 h-full opacity-0 group-hover:opacity-100 text-gray-100 cursor-pointer right-0 bg-black/5">
                                        <ChevronRightIcon class="w-16"/>
                                    </div>

                                    <div class="flex items-center justify-center w-full h-full">
                                        <img v-if="isImage(attachment)"
                                             :src="attachment.url"
                                             alt=""
                                             class="object-contain max-w-full max-h-full"
                                        />

                                        <div v-else
                                             class="text-white flex flex-col items-center justify-center p-32"
                                        >
                                            <DocumentIcon class="w-10 h-10 mb-3"/>
                                            <small class="text-center">
                                                {{ attachment.name }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>
