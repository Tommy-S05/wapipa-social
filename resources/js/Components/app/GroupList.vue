<script setup>

import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import GroupListItems from "@/Components/app/GroupListItems.vue";

</script>

<template>
    <!--    <div class="p-3 bg-white rounded border h-[300px] lg:h-full overflow-hidden flex flex-col">-->
    <div class="p-3 bg-white rounded border lg:h-full overflow-hidden flex flex-col">
        <div class="block lg:hidden">
            <Disclosure v-slot="{ open }" :defaultOpen=true>
                <DisclosureButton class="text-left w-full" :class="{ 'mb-4': open }">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">My Groups</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="w-6 h-6 transition-all"
                             :class="open && 'rotate-90 transform'">
                            <path fill-rule="evenodd"
                                  d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                </DisclosureButton>
                <DisclosurePanel>
                    <GroupListItems/>
                </DisclosurePanel>
            </Disclosure>
        </div>
        <div class="hidden lg:flex lg:flex-col overflow-hidden">
            <h2 class="text-xl font-bold mb-4">My Groups</h2>
            <GroupListItems/>
        </div>
        <!--        <div class="block lg:hidden">-->
        <!--            <Disclosure v-slot="{ open }">-->
        <!--                <DisclosureButton class="text-left">-->
        <!--                    <h2 class="text-xl font-bold mb-4">My Groups</h2>-->
        <!--                </DisclosureButton>-->
        <!--                <DisclosurePanel>-->
        <!--                    <TextInput-->
        <!--                        :model-value="searchKeyword"-->
        <!--                        placeholder="Type to search"-->
        <!--                        class="w-full"-->
        <!--                    />-->
        <!--                    <div class="mt-3 flex-1 overflow-auto">-->
        <!--                        <div v-if="false" class="text-gray-400 text-center p-3">-->
        <!--                            You are not joined to any groups-->
        <!--                        </div>-->
        <!--                        <div v-else>-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Laravel Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                            <GroupItem-->
        <!--                                image="https://picsum.photos/100"-->
        <!--                                title="Vue.js Developers"-->
        <!--                                description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                            />-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </DisclosurePanel>-->
        <!--            </Disclosure>-->
        <!--        </div>-->
        <!--        <div class="hidden lg:block">-->
        <!--            <h2 class="text-xl font-bold mb-4">My Groups</h2>-->
        <!--            <TextInput-->
        <!--                :model-value="searchKeyword"-->
        <!--                placeholder="Type to search"-->
        <!--                class="w-full"-->
        <!--            />-->
        <!--            <div class="mt-3 flex-1 overflow-auto">-->
        <!--                <div v-if="false" class="text-gray-400 text-center p-3">-->
        <!--                    You are not joined to any groups-->
        <!--                </div>-->
        <!--                <div v-else>-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Laravel Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                    <GroupItem-->
        <!--                        image="https://picsum.photos/100"-->
        <!--                        title="Vue.js Developers"-->
        <!--                        description="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor."-->
        <!--                    />-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </div>
</template>

<style scoped>

</style>
