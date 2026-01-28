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
        <table class="table-auto">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col" class="px-6 py-4">Option Value</th>
                    <th scope="col" class="px-6 py-4" x-show="!dynamicPricing">Price</th>
                    <th scope="col" class="px-6 py-4"></th>
                </tr>
            </thead>
            <tbody x-sort="alert($item + ' - ' + $position)">
                <template x-for="(option, index) in model.value">
                    <tr x-sort:item="option.id">
                        <th scope="row"> <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i> </th>
                        <td class="px-6 py-4" x-text="option.name">
                        </td>
                        <td v-show="!dynamicPricing" class="px-6 py-4">
                            <div class="flex">
                                <select
                                    class="flex w-1/2 min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                    x-model="model.value[index].prefix">
                                    <option value="+">
                                        +
                                    </option>
                                    <option value="-">
                                        -
                                    </option>
                                </select>
                                <input
                                    type="text"
                                    class="w-1/2 py-2.5 px-3 border text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    x-model="model.value[index].price" />
                            </div>
                        </td>
                        <td class="px-6 py-4"><button type="button" x-on:click="remove(model.option_id, option.id)"><span class="icon-delete text-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 hover:rounded-md"></span></button></td>
                    </tr>
                </template>

                <template x-if="!model.value.length">
                    <tr>
                        <td colspan="3">Please add an option item to begin</td>
                    </tr>
                </template>
            </tbody>
            <template x-if="hasUnassignedOptions(model.option_id)">
                <tfoot>
                    <tr>
                        <th scope="row" colspan="2">
                            <select
                                x-model="assignOption"
                                class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                label="required">
                                <option value="">Select an option</option>
                                <template x-for="item in getUnassignedOptions(model.option_id)">
                                    <option x-bind:value="item.id" x-text="item.name">
                                    </option>
                                </template>
                            </select>
                        </th>
                        <td class="px-6 py-4">
                            <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addOption(model.option_id, assignOption)">Add</button>
                            <template x-if="getUnassignedOptions(model.option_id).length > 1">
                                <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addAll(model.option_id)">Add all</button>
                            </template>
                        </td>
                    </tr>
                </tfoot>
            </template>
        </table>
    </div>

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
