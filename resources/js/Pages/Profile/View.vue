<script setup>

import {Head, useForm, usePage} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Pages/Profile/Partials/TabItem.vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {XMarkIcon, CheckCircleIcon} from '@heroicons/vue/24/solid'
import {CameraIcon} from '@heroicons/vue/24/outline'
import {computed, ref} from "vue";


const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
        type: Object
    },
    errors: Object
});

const imagesForm = useForm({
    cover: null,
    avatar: null
})

const authUser = usePage().props.auth.user

const isMyProfile = computed(() => authUser && authUser.id === props.user.id)
const coverImageSrc = ref('');
const showNotification = ref(false)

function onCoverChange(event) {
    imagesForm.cover = event.target.files[0];
    if (imagesForm.cover) {
        const reader = new FileReader();
        reader.onload = () => {
            coverImageSrc.value = reader.result;
        }
        reader.readAsDataURL(imagesForm.cover);
    }
}

function cancelCoverImage() {
    imagesForm.cover = null;
    coverImageSrc.value = null;
}

function submitCoverImage() {
    imagesForm.post(route('profile.updateImage'), {
        onSuccess: () => {
            showNotification.value = true;
            cancelCoverImage();
            setTimeout(() => {
                showNotification.value = false;
            }, 5000);
        },
        onError: () => {

        }
    })
}

</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div
                v-show="showNotification && status === 'cover-image-update'"
                class="my-2 py-2 px-3 font-medium text-sm bg-emerald-500 text-white"
            >
                Your cover image has been updated
            </div>
            <div
                v-if="errors.cover"
                class="my-2 py-2 px-3 font-medium text-sm bg-red-400 text-white"
            >
                {{ errors.cover }}
            </div>
            <div class="group relative bg-white">
                <!--                <img src="https://www.prodraw.net/fb_cover/images/fb_cover_65.jpg"-->
                <img :src="coverImageSrc || user.cover_url || '/img/default_cover.webp'"
                     alt="Cover image"
                     class="h-[200px] w-full object-cover"
                />
                <div class="absolute top-2 right-2">
                    <button v-if="!coverImageSrc"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100"
                    >
                        <CameraIcon class="w-3 h-3 mr-2"/>
                        Update Cover Image
                        <input type="file"
                               class="absolute left-0 top-0 bottom-0 right-0 cursor-pointer opacity-0"
                               @change="onCoverChange"
                        />
                    </button>
                    <div v-else class="flex space-x-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                        <button
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center"
                            @click="cancelCoverImage"
                        >
                            <XMarkIcon class="w-3 h-3 mr-2"/>
                            Cancel
                        </button>
                        <button
                            class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center"
                            @click="submitCoverImage"
                        >
                            <CheckCircleIcon class="w-3 h-3 mr-2"/>
                            Submit
                        </button>
                    </div>
                </div>
                <div class="flex">
                    <img src="https://cdn.iconscout.com/icon/free/png-256/free-avatar-370-456322.png?f=webp"
                         alt=""
                         class="ml-12 w-32 h-32 -mt-16"
                    />
                    <div class="flex justify-between items-center flex-1 p-4">
                        <h2 class="font-bold text-lg">
                            {{ user.name }}
                        </h2>
                        <PrimaryButton v-if="isMyProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"/>
                            </svg>
                            Edit Profile
                        </PrimaryButton>
                    </div>
                </div>
            </div>
            <div class="border-t">
                <TabGroup>
                    <TabList class="flex bg-white">
                        <Tab
                            v-if="isMyProfile"
                            as="template"
                            key="about"
                            v-slot="{ selected }"
                        >
                            <TabItem :selected="selected" text="About"/>
                        </Tab>
                        <Tab
                            as="template"
                            key="posts"
                            v-slot="{ selected }"
                        >
                            <TabItem :selected="selected" text="Posts"/>
                        </Tab>
                        <Tab
                            as="template"
                            key="followers"
                            v-slot="{ selected }"
                        >
                            <TabItem :selected="selected" text="Followers"/>
                        </Tab>
                        <Tab
                            as="template"
                            key="following"
                            v-slot="{ selected }"
                        >
                            <TabItem :selected="selected" text="Following"/>
                        </Tab>
                        <Tab
                            as="template"
                            key="photos"
                            v-slot="{ selected }"
                        >
                            <TabItem :selected="selected" text="Photos"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel
                            v-if="isMyProfile"
                            key="about"
                        >
                            <Edit :must-verify-email="mustVerifyEmail"
                                  :status="status"
                            />
                        </TabPanel>
                        <TabPanel
                            key="posts"
                            class="bg-white p-3 shadow"
                        >
                            Posts Content
                        </TabPanel>
                        <TabPanel
                            key="followers"
                            class="bg-white p-3 shadow"
                        >
                            Followers Content
                        </TabPanel>
                        <TabPanel
                            key="following"
                            class="bg-white p-3 shadow"
                        >
                            Following Content
                        </TabPanel>
                        <TabPanel
                            key="photos"
                            class="bg-white p-3 shadow"
                        >
                            Photos Content
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<!--<template>-->
<!--    <div>-->
<!--        &lt;!&ndash;        Cover&ndash;&gt;-->
<!--        <div>-->

<!--        </div>-->
<!--        &lt;!&ndash;        Avatar&ndash;&gt;-->
<!--        <div>-->
<!--            Avatar-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->


<style scoped>

</style>
