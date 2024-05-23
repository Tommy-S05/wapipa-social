<script setup>
import {computed, ref, watch} from 'vue'
import {
    TransitionRoot,
    TransitionChild,
    Dialog,
    DialogPanel,
    DialogTitle,
} from '@headlessui/vue'
import {XMarkIcon, PaperClipIcon, BookmarkIcon, DocumentIcon, ArrowUturnLeftIcon} from '@heroicons/vue/24/solid';
import {useForm, usePage} from "@inertiajs/vue3";
import PostUserHeader from "@/Components/app/PostUserHeader.vue";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import {isImage} from "@/helpers.js";

const props = defineProps({
    post: {
        type: Object,
        required: true
    },
    modelValue: {
        type: Boolean,
    }
});

const attachmentExtensions = usePage().props.attachmentsExtensions;

const editor = ClassicEditor;
const editorConfig = {
    toolbar: {
        items: [
            'undo',
            // 'redo',
            '|',
            'heading',
            '|',
            'bold',
            'italic',
            '|',
            'link',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'outdent',
            'indent',
            '|',
            'blockQuote'
        ]
    }
};

/**
 * {
 *     file: File,
 *     url: String
 * }
 * @type {Ref<UnwrapRef<*[]>>}
 */
const attachmentFiles = ref([]);
const attachmentErrors = ref([]);
const formErrors = ref({});

const show = computed({
    get: () => props.modelValue,
    set: (newValue) => emit('update:modelValue', newValue)
});

const computedAttachments = computed(() => {
    return [...attachmentFiles.value, ...(props.post.attachments || [])];
})

const showExtensionsText = computed(() => {
   for (const myFile of attachmentFiles.value) {
       const extension = myFile.file.name.split('.').pop().toLowerCase();
       if (!attachmentExtensions.includes(extension)) {
           return true;
       }
   }

    return false;
});

const emit = defineEmits(['update:modelValue', 'hide'])

function closeModal() {
    show.value = false;
    emit('hide');
    resetModal();
}

function resetModal() {
    postForm.reset();
    attachmentFiles.value = [];
    formErrors.value = {};
    attachmentErrors.value = [];
    props.post?.attachments?.forEach(file => file.deleted = false)
}

const postForm = useForm({
    id: null,
    body: '',
    attachments: [],
    deleted_files_ids: [],
    _method: 'POST'
})

const submit = () => {
    postForm.attachments = attachmentFiles.value.map((myFile) => myFile.file);
    if (postForm.id) {
        postForm._method = 'PUT';
        postForm.post(route('post.update', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: (errors) => {
                processErrors(errors);
            }
        });
    } else {
        postForm.post(route('post.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
            },
            onError: (errors) => {
                processErrors(errors);
            }
        });
    }
}

function processErrors(errors) {
    formErrors.value = errors;
    for (const error in errors) {
        if (error.includes('.')) {
            const [key, index] = error.split('.');
            attachmentErrors.value[index] = errors[error];
        }
    }
}

async function onAttachmentChoose(event) {
    for (const file of event.target.files) {
        const myFile = {
            file,
            url: await readFile(file)
        }
        attachmentFiles.value.push(myFile)
    }

    event.target.value = null;
}

async function readFile(file) {
    return new Promise((res, rej) => {
        if (isImage(file)) {
            const reader = new FileReader();
            reader.onload = () => {
                res(reader.result);
            }
            reader.onerror = rej;

            reader.readAsDataURL(file);
        } else {
            res(null);
        }
    })

}

function removeFile(myFile, index) {
    if (myFile.file) {
        attachmentFiles.value = attachmentFiles.value.filter((file) => file !== myFile);
        attachmentErrors.value.splice(index, 1);
    } else {
        postForm.deleted_files_ids.push(myFile.id);
        myFile.deleted = true;
    }
}

function undoDelete(myFile) {
    myFile.deleted = false;
    postForm.deleted_files_ids = postForm.deleted_files_ids.filter((id) => myFile.id !== id);
}

watch(() => props.post, () => {
    postForm.id = props.post.id || null;
    postForm.body = props.post.body || '';
});

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
                                    {{ post.id ? 'Update Post' : 'Create Post' }}
                                    <button
                                        @click="closeModal"
                                        class="w-8 h-8 rounded-full hover:bg-black/5 transition flex items-center justify-center"
                                    >
                                        <XMarkIcon class="w-4 h-4"/>
                                    </button>
                                </DialogTitle>
                                <div class="p-4">
                                    <PostUserHeader :post="post" :show-time="false" class="mb-4"/>
                                    <ckeditor
                                        :editor="editor"
                                        v-model="postForm.body"
                                        :config="editorConfig"
                                    />

                                    <div v-if="showExtensionsText"
                                        class="border-l-4 border-amber-500 py-2 px-3 bg-amber-100 mt-3 text-gray-800">
                                        Files must be one of the following extensions: <br/>
                                        <strong>{{ attachmentExtensions.join(', ') }}.</strong>
                                    </div>

                                    <div v-if="formErrors.attachments"
                                         class="border-l-4 border-red-500 py-2 px-3 bg-red-100 mt-3 text-gray-800"
                                    >
                                        {{formErrors.attachments}}
                                    </div>

                                    <div
                                        class="grid gap-3 my-3"
                                        :class="[
                                            computedAttachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
                                        ]"
                                    >
                                        <div v-for="(myFile, index) of computedAttachments">
                                            <div
                                                class="relative group bg-blue-100 aspect-square flex flex-col items-center justify-center text-gray-500 border-2"
                                                :class="attachmentErrors[index] ? 'border-red-500' : ''"
                                            >

                                                <!--Delete/Undo bottom-->
                                                <button
                                                    @click="myFile.deleted ? undoDelete(myFile) : removeFile(myFile, index)"
                                                    class="w-7 h-7 flex items-center justify-center text-white bg-gray-700 hover:bg-gray-800 rounded-full absolute right-2 top-2 cursor-pointer opacity-0 group-hover:opacity-100 transition-all z-10"
                                                >
                                                    <ArrowUturnLeftIcon v-if="myFile.deleted" class="w-4 h-4"/>

                                                    <XMarkIcon v-else class="w-4 h-4"/>
                                                </button>

                                                <!--For images files-->
                                                <img v-if="isImage(myFile.file || myFile)"
                                                     :src="myFile.url"
                                                     alt=""
                                                     class="aspect-square object-cover"
                                                     :class="myFile.deleted ? 'opacity-50' : ''"
                                                />

                                                <!--For other types of files-->
                                                <div v-else
                                                     class="flex flex-col items-center justify-center px-3"
                                                     :class="myFile.deleted ? 'opacity-50' : ''"
                                                >
                                                    <DocumentIcon class="w-10 h-10 mb-3"/>
                                                    <small class="text-center">
                                                        {{ (myFile.file || myFile).name }}
                                                    </small>
                                                </div>

                                                <!--For attachments deleted-->
                                                <div v-if="myFile.deleted"
                                                     class="absolute left-0 right-0 bottom-0 py-2 px-3 bg-black text-white text-sm z-10"
                                                >
                                                    To be deleted
                                                </div>
                                            </div>

                                            <small class="text-red-500">
                                                {{attachmentErrors[index]}}
                                            </small>
                                        </div>
                                    </div>

                                </div>

                                <div class="flex gap-2 py-3 px-4">
                                    <label
                                        type="button"
                                        class="relative flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full cursor-pointer"
                                    >
                                        <PaperClipIcon class="w-4 h-4 mr-2"/>
                                        Attach Files
                                        <input
                                            type="file"
                                            multiple
                                            @change="onAttachmentChoose"
                                            class="absolute left-0 top-0 right-0 bottom-0 cursor-pointer opacity-0 hidden"
                                        />
                                    </label>
                                    <button
                                        type="button"
                                        class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
                                        @click="submit"
                                    >
                                        <BookmarkIcon class="w-4 h-4 mr-2"/>
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
