<div x-data="{
    model: {
        default: '',
    },
    field: null,
    dynamicPricing: false
 }"
 x-modelable="model"
 class="flex-column gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800"
 {{ $attributes }}>
    <h2 x-text="field?.name?.en" class="font-medium text-base text-gray-700 dark"></h2>

    <div>
        <label>
            Default Value
        </label>
        <input
            class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
            x-model="model.value.default" />
    </div>
    <template x-show="!dynamicPricing">
        <div>
            <label>
                Price
            </label>
            <div class="flex">
                <select
                    class="w-[20%] flex min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 mr-4"
                    x-model="model.value.prefix">
                    <option value="+">
                        +
                    </option>
                    <option value="-">
                        -
                    </option>
                </select>
                <input
                    type="text"
                    class="w-[60%] py-2.5 px-3 border text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                    x-model="model.value.price" />
            </div>
        </div>
    </template>
    <div>
        <label class="required">
            Required
        </label>
        <select
            x-model="model.required"
            class="custom-select flex w-full min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400">
            <option value="0">
                No
            </option>
            <option value="1">
                Yes
            </option>
        </select>
    </div>
    <div>
        <label class="min">
            Min
        </label>
        <input
            class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
            type="text"
            x-model="model.min" />
    </div>
    <div>
        <label class="max">
            Max
        </label>
        <input
            type="text"
            class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
            x-model="model.max" />
    </div>
</div>
