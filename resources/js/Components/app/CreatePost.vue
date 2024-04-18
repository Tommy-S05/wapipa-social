<script setup>

import {ref} from "vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import PostModal from "@/Components/app/PostModal.vue";

const authUser = usePage().props.auth.user;

const showModal = ref(false);
const newPost = ref({
    id: null,
    body: '',
    user: authUser
});

function showCreatePostModal(){
    showModal.value = true;
}

// const submit = () => {
//     newPostForm.post(route('post.store'), {
//         onSuccess: () => {
//             newPostForm.reset();
//         }
//     })
// }
</script>

<template>
    <div class="p-4 bg-white rounded-lg border mb-3">
        <div
            @click="showCreatePostModal"
            class="py-2 px-3 text-gray-500 border border-2 rounded mb-3 cursor-pointer border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 hover:border-indigo-500 dark:hover:border-indigo-600 hover:ring-indigo-500 dark:hover:ring-indigo-600 rounded-md shadow-sm"
        >
            Click here to create new post
        </div>

<!--        <TextareaInput-->
<!--            @click="showCreatePostModal"-->
<!--            class="mb-3 w-full"-->
<!--            placeholder="Click here to create new post"-->
<!--            :auto-resize="true"-->
<!--            :rows="1"-->
<!--        />-->

<!--        <div class="flex justify-between">-->
<!--            <button-->
<!--                type="button"-->
<!--                class="relative rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"-->
<!--            >-->
<!--                Attach Files-->
<!--                <input-->
<!--                    type="file"-->
<!--                    class="absolute left-0 top-0 right-0 bottom-0 opacity-0"-->
<!--                />-->
<!--            </button>-->
<!--            <button-->
<!--                @click="submit"-->
<!--                type="submit"-->
<!--                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"-->
<!--            >-->
<!--                Submit-->
<!--            </button>-->
<!--        </div>-->

        <PostModal :post="newPost" v-model="showModal"/>
    </div>
</template>

<style scoped>

</style>
