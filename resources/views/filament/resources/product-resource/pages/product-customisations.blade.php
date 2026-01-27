<x-filament-panels::page>
    <!-- Tabs: Vertical -->
    <!-- An Alpine.js and Tailwind CSS component by https://pinemix.com -->
    <!-- Alpine.js focus plugin is required, for more info http://pinemix.com/docs/getting-started -->
    <div
        x-data="{
            fields: $wire.fields,
            model: $wire.model,
            active: $wire.model[0]?.option_id,
            dynamicPricing: $wire.pricingType === 'dynamic',
            assignOption: null,
            hasUnassignedOptions(fieldId) {
                return this.getUnassignedOptions(fieldId)?.length > 0;
            },
            getUnassignedOptions(fieldId) {
                if (!this.fields[fieldId]?.configuration?.lookups) {
                    return [];
                }
                const assignedOptionIds = this.model.find(f => f.option_id === fieldId)?.value.map(o => o.id) || [];
                return this.fields[fieldId].configuration.lookups.filter(f => !assignedOptionIds.includes(f.value)).map(o => ({
                    id: o.value,
                    name: o.label,
                }));
            },
            addOption(fieldId, optionId) {
                if (!optionId) {
                    return;
                }
                const field = this.model.find(f => f.option_id === fieldId);
                const option = this.fields[fieldId].configuration.lookups.find(o => o.value === optionId);
                if (field && option) {
                    field.value.push({
                        id: option.value,
                        name: option.label,
                        prefix: '+',
                        price: '0',
                    });
                    this.assignOption = null;
                }
            },
            addAll(fieldId) {
                const field = this.model.find(f => f.option_id === fieldId);
                const unassignedOptions = this.getUnassignedOptions(fieldId);
                if (field) {
                    unassignedOptions.forEach(option => {
                        field.value.push({
                            ...option,
                            prefix: '+',
                            price: '0',
                        });
                    });
                    this.assignOption = null;
                }
            },
            remove(optionId) {
                this.model.forEach(field => {
                    field.value = field.value.filter(o => o.id !== optionId);
                });
            },
            removeAll(fieldId) {
                const field = this.model.find(f => f.id === fieldId);
                if (field) {
                    field.value = [];
                }
            },
        }"
        class="flex gap-4">
        <div class="border-r border-gray-200">
            <div role="tablist" class="-mr-px flex flex-col gap-1">
                <template x-for="(field, index) in model" :key="field.option_id">
                    <button
                        x-text="fields[field.option_id].name?.en"
                        x-on:click="active = field.option_id"
                        type="button"
                        role="tab"
                        x-bind:aria-selected="active !==  field.option_id"
                        class="border-r-2 px-4 py-2 text-left text-sm font-medium  transition-colors"
                        x-bind:class="{ 'border-blue-600 text-blue-600  hover:text-blue-700': active ===  field.option_id, 'border-transparent text-gray-600 hover:text-gray-700': active !==  field.option_id }">
                    </button>
                </template>
            </div>
        </div>
        <!-- END Nav Tabs -->

        <!-- Tab Content -->
        <div role="tabpanel" class="flex-1">
            <template x-for="(field, index) in model">
                <div
                    x-show="active === field.option_id"
                    tab="tabpanel"
                    tabindex="0"
                    class="text-gray-700">
                    <template x-if="['Lunar\\FieldTypes\\Text'].includes(fields[field.option_id].type)">
                        <div class="flex-column gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                            <h2 x-text="fields[field.option_id].name?.en" class="font-medium text-base text-gray-700 dark"></h2>

                            <div>
                                <label>
                                    Default Value
                                </label>
                                <input
                                    class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    x-model="field.value.default" />
                            </div>
                            <template x-show="!dynamicPricing">
                                <div>
                                    <label>
                                        Price
                                    </label>
                                    <div class="flex">
                                        <select
                                            class="w-[20%] flex min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 mr-4"
                                            x-model="field.value.prefix">
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
                                            x-model="field.value.price" />
                                    </div>
                                </div>
                            </template>
                            <div>
                                <label class="required">
                                    Required
                                </label>
                                <select
                                    x-model="field.required"
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
                                    x-model="field.min" />
                            </div>
                            <div>
                                <label class="max">
                                    Max
                                </label>
                                <input
                                    type="text"
                                    class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    x-model="field.max" />
                            </div>
                        </div>
                    </template>
                    <template x-if="['Lunar\\FieldTypes\\Dropdown'].includes(fields[field.option_id].type)">
                        <div class="flex-column gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                            <h2 x-text="fields[field.option_id].name?.en" class="font-medium text-base text-gray-700 dark"></h2>

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
                                        <template x-for="(option, index) in field.value">
                                            <tr x-sort:item="option.id">
                                                <th scope="row"> <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i> </th>
                                                <td class="px-6 py-4" x-text="option.name">
                                                </td>
                                                <td v-show="!dynamicPricing" class="px-6 py-4">
                                                    <div class="flex">
                                                        <select
                                                            class="flex w-1/2 min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                            x-model="field.value[index].prefix">
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
                                                            x-model="field.value[index].price" />
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4"><button type="button" @click="remove(field.option_id, option.id)"><span class="icon-delete text-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 hover:rounded-md"></span></button></td>
                                            </tr>
                                        </template>

                                        <template x-if="!field.value.length">
                                            <tr>
                                                <td colspan="3">Please add an option item to begin</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <template x-if="hasUnassignedOptions(field.option_id)">
                                        <tfoot>
                                            <tr>
                                                <th scope="row" colspan="2">
                                                    <select
                                                        x-model="assignOption"
                                                        class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                        label="required">
                                                        <option value="">Select an option</option>
                                                        <template x-for="item in getUnassignedOptions(field.option_id)">
                                                            <option x-bind:value="item.id" x-text="item.name">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </th>
                                                <td class="px-6 py-4">
                                                    <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addOption(field.option_id, assignOption)">Add</button>
                                                    <template x-if="getUnassignedOptions(field.option_id).length > 1">
                                                        <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addAll(field.option_id)">Add all</button>
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
                                    x-model="field.required"
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
                                    x-model="field.min" />
                            </div>
                            <div>
                                <label class="max">
                                    Max
                                </label>
                                <input
                                    type="text"
                                    class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    x-model="field.max" />
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <!-- END Tab Content -->
    </div>
    <!-- END Tabs: Vertical -->
</x-filament-panels::page>
