<script setup>
import {
    useField,
    configure,
    defineRule,
    Field,
    Form,
    FieldArray,
    ErrorMessage,
} from "vee-validate";
import Multiselect from "vue-multiselect";
import { watch, computed } from "vue";

const props = defineProps({
    options: { type: Array, required: true },
    modelValue: { type: [String, Number, Array], required: true },
    label: { type: String, default: "" },
    name: { type: String, default: "" },
    multiple: { type: Boolean, default: false },
    placeholder: { type: String },
    rules: { type: String },
});
const emit = defineEmits(["update:modelValue"]);
const { value } = useField(props.name, props.rules, {
    label: props.label,
    initialValue: props.modelValue,
});
watch(value, (newValue) => {
    emit("update:modelValue", newValue);
});
const hint = computed(() => {
    if (!props.multiple || !props.rules) {
        return "";
    }
    const rulesArray = props.rules.split("|");
    const hintRule = rulesArray.find((rule) => rule.startsWith("length:"));
    if (hintRule) {
        const values = hintRule.split(":")[1];
        const [min, max] = values.split(",");
        if (min && max) {
            return `Please select between ${min} and ${max} options.`;
        }
        if (min) {
            return `Please select at least ${min} options.`;
        }
        if (max) {
            return `Please select up to ${max} options.`;
        }
    }
    return "";
});
const select = (option) => {
    if (!props.multiple) {
        value.value = option.id;
        return;
    }
    value.value = value.value.includes(option.id)
        ? value.value.filter((i) => i !== option.id)
        : [...value.value, option.id];
};
const isActive = (option) => {
    if (!props.multiple) {
        return value.value == option.id;
    }
    return value.value.includes(option.id);
};
</script>

<template>
    <div
        class="cr-size-weight flex flex-col max-[380px]:flex-col max-[380px]:justify-start max-[380px]:items-start"
    >
        <h5
            class="mb-2 font-Poppins text-[16px] leading-[1.556] text-[#2b2b2d] font-medium max-[1199px]:min-w-[100px] max-[1199px]:text-[14px]"
        >
            {{ label }}
        </h5>
        <div class="cr-kg max-[380px]:pt-[10px] relative group">
            <ul class="w-full p-[0] m-[0] flex flex-wrap">
                <li
                    v-for="option in options"
                    :key="option.id"
                    class="transition-all duration-[0.3s] ease-in-out m-[2px] py-[5px] px-[10px] font-Poppins text-[14px] leading-[1] bg-[#fff] text-[#777] border-[1px] border-solid border-[#e9e9e9] rounded-[5px] cursor-pointer max-[1199px]:mr-[5px]"
                    :class="isActive(option) ? 'bg-blue-200' : ''"
                    :role="multiple ? 'checkbox' : 'radio'"
                    @click="select(option)"
                    v-text="option.name"
                ></li>
            </ul>
            <p
                v-if="hint"
                class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 w-max px-2 py-1 text-sm text-white bg-gray-700 rounded shadow-lg opacity-0 group-hover:opacity-100"
            >
                {{ hint }}
            </p>
        </div>
        <Field
            v-if="!multiple"
            type="hidden"
            :name="name"
            :value="value"
        ></Field>
        <template v-else>
            <input
                v-for="item in value"
                :name="name"
                type="hidden"
                :key="item"
                :value="item"
            />
        </template>
    </div>
</template>
