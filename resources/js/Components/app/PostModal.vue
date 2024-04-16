<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import TextareaInput from "@/Components/TextareaInput.vue";
import {XMarkIcon} from '@heroicons/vue/24/solid';
import {useForm} from "@inertiajs/vue3";
import PostUserHeader from "@/Components/app/PostUserHeader.vue";

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    modelValue: {
        type: Boolean,
    }
});

const show = computed({
    get: () => props.modelValue,
    set: (newValue) => emit('update:modelValue', newValue)
});

const emit = defineEmits(['update:modelValue'])

function closeModal() {
    show.value = false
}

const updatePostForm = useForm({
    id: null,
    body: ''
})

const submit = () => {
    updatePostForm.put(route('post.update', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
        }
    })
}

watch(() => props.post, () => {
    updatePostForm.id = props.post.id;
    updatePostForm.body = props.post.body;
});

</script>

<template>
    <teleport to="body">
        <TransitionRoot appear :show="show" as="template">

            <Dialog as="div" @close="closeModal" class="relative z-10">
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
                        class="flex min-h-full items-center justify-center p-4 text-center"
                    >
                        <TransitionChild
                            as="template"
                            enter="duration-300 ease-out"
                            enter-from="opacity-0 scale-95"
                            enter-to="opacity-100 scale-100"
                            leave="duration-200 ease-in"
                            leave-from="opacity-100 scale-100"
                            leave-to="opacity-0 scale-95"
                        >
                            <DialogPanel
                                class="w-full max-w-md transform overflow-hidden rounded-md bg-white text-left align-middle shadow-xl transition-all"
                            >
                                <DialogTitle
                                    as="h3"
                                    class="flex items-center justify-between py-3 px-4 font-medium text-gray-900 bg-gray-100"
                                >
                                    Update Post
                                    <button
                                        @click="closeModal"
                                        class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center"
                                    >
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>
                                <div class="p-4">
                                    <PostUserHeader :post="post" :show-time="false" class="mb-4"/>
                                    <TextareaInput
                                        v-model="updatePostForm.body"
                                        class="mb-3 w-full"
                                        :auto-resize="true"
                                    />
                                </div>

                                <div class="py-3 px-4">
                                    <button
                                        type="button"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
                                        @click="submit"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </teleport>
</template>