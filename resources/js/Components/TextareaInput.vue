<script setup>
import {onMounted, ref, watch} from 'vue';

const model = defineModel({
    type: String,
    required: false,
});

const props = defineProps({
    placeholder: {
        type: String
    },
    rows: {
        type: Number
    },
    autoResize: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue']);

// const autoResizeTextarea = ref(null);
const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});


function onInputChange($event) {
    emit('update:modelValue', $event.target.value)
}

function adjustHeight() {
    if (props.autoResize) {
        input.value.style.height = 'auto';
        input.value.style.height = (input.value.scrollHeight + 2) + 'px';
    }
}

watch(model, () => {
    setTimeout(() => {
        adjustHeight();
    }, 1);
});

onMounted(() => {
    adjustHeight();
})
</script>

<template>
    <textarea
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
        v-model="model"
        @input="onInputChange"
        ref="input"
        :placeholder="placeholder"
        :rows="rows"
    />
</template>
