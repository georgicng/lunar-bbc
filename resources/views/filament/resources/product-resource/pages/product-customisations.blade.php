<x-filament-panels::page>
    <!-- Tabs: Vertical -->
    <!-- An Alpine.js and Tailwind CSS component by https://pinemix.com -->
    <!-- Alpine.js focus plugin is required, for more info http://pinemix.com/docs/getting-started -->
    <div
        x-data="{
            fields: $wire.fields,
            model: $wire.model,
            active: $wire.model[0]?.attribute_id,
            dynamicPricing: $wire.dynamicPricing,
            assignOption: null,
            hasUnassignedOptions(fieldId) {
                return this.getUnassignedOptions(fieldId)?.length > 0;
            },
            getUnassignedOptions(fieldId) {
                if (!this.fields[fieldId]?.configuration?.lookups) {
                    return [];
                }
                const assignedOptionIds = this.model.find(f => f.attribute_id === fieldId)?.attribute_data.map(o => o.id) || [];
                return this.fields[fieldId].configuration.lookups.filter(f => !assignedOptionIds.includes(f.value)).map(o => ({
                    id: o.value,
                    name: o.label,
                }));
            },
            addOption(fieldId, optionId) {
                if (!optionId) {
                    return;
                }
                const field = this.model.find(f => f.attribute_id === fieldId);
                const option = this.fields[fieldId].configuration.lookups.find(o => o.value === optionId);
                if (field && option) {
                    field.attribute_data.push({
                        id: option.value,
                        name: option.label,
                        prefix: '+',
                        price: '0',
                    });
                    this.assignOption = null;
                }
            },
            addAll(fieldId) {
                const field = this.model.find(f => f.attribute_id === fieldId);
                const unassignedOptions = this.getUnassignedOptions(fieldId);
                if (field) {
                    unassignedOptions.forEach(option => {
                        field.attribute_data.push({
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
                    field.attribute_data = field.attribute_data.filter(o => o.id !== optionId);
                });
            },
            removeAll(fieldId) {
                const field = this.model.find(f => f.id === fieldId);
                if (field) {
                    field.attribute_data = [];
                }
            },
            rules: $wire.rules,
            get counterOperators() {
                return ['count'];
            },
            get linearOperators() {
                return ['=', '!='];
            },
            get selectionOperators() {
                return ['includes', 'excludes', 'in', 'not in'];
            },
            get touchedOperators() {
                return ['empty', 'exist'];
            },
            get textGroup() {
                return ['Lunar\\FieldTypes\\Text', 'textarea'];
            },
            get selectGroup() {
                return ['Lunar\\FieldTypes\\Dropdown', 'multiselect', 'checkbox'];
            },
            get operators() {
                return {
                    'Lunar\\FieldTypes\\Text': ['=', '!=', 'exist', 'empty', 'regex'],
                    textarea: ['exist', 'empty', 'regex'],
                    boolean: ['exist', 'empty'],
                    'Lunar\\FieldTypes\\Dropdown': ['=', '!=', 'exist', 'empty', 'in', 'not in'],
                    multiselect: ['includes', 'excludes', 'count'],
                    checkbox: ['includes', 'excludes', 'count'],
                }
            },
            generateId() {
                return 'xxxxxxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    var r = (Math.random() * 16) | 0,
                        v = c == 'x' ? r : (r & 0x3) | 0x8;
                    return v.toString(16);
                });
            },
            addRuleCondition(index) {
                this.rules[index]['conditions'].push({
                    id: this.generateId(),
                    field: '',
                    operator: '',
                    value: ''
                });
            },
            deleteRuleCondition(index, id) {
                this.rules[index]['conditions'] = this.rules[index]['conditions'].filter(item => item.id !== id);
            },
            setRuleLogic(index, value) {
                this.rules[index]['logic'] = value;
            },
            addRule() {
                this.rules.push({
                    id: this.generateId(),
                    name: '',
                    logic: 'and',
                    conditions: [],
                    result: null
                });
            },
            duplicateRule(index) {
                const element = this.rules[index]
                const append = JSON.parse(JSON.stringify(element));
                this.rules.splice(index, 0, append);
            },
            deleteRule(index) {
                this.rules.splice(index, 1);
            },
        }"
        x-init="$watch('rules', (value, oldValue) => console.log(value, oldValue))">
        <!--Toggle Pricing -->
        <div>
            <label>
                Use Dynamic Pricing </label>
            <label for="useDynamicPricing" class="group relative block h-8 w-14 rounded-full bg-gray-300 transition-colors [-webkit-tap-highlight-color:transparent] has-checked:bg-green-500">
                <input type="checkbox" id="useDynamicPricing" x-model="dynamicPricing" class="peer sr-only">

                <span class="absolute inset-y-0 start-0 m-1 grid size-6 place-content-center rounded-full bg-white text-gray-700 transition-[inset-inline-start] peer-checked:start-6 peer-checked:*:first:hidden *:last:hidden peer-checked:*:last:block">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path>
                    </svg>
                </span>
            </label>
        </div>
        <!-- End Toggle Pricing -->

        <!--Tabs: Vertical -->
        <div class="flex gap-4">
            <!-- Nav Tabs -->
            <div class="border-r border-gray-200">
                <div role="tablist" class="-mr-px flex flex-col gap-1">
                    <template x-for="(field, index) in model" :key="field.attribute_id">
                        <button
                            x-text="fields[field.attribute_id].name?.en"
                            x-on:click="active = field.attribute_id"
                            type="button"
                            role="tab"
                            x-bind:aria-selected="active !==  field.attribute_id"
                            class="border-r-2 px-4 py-2 text-left text-sm font-medium  transition-colors"
                            x-bind:class="{ 'border-blue-600 text-blue-600  hover:text-blue-700': active ===  field.attribute_id, 'border-transparent text-gray-600 hover:text-gray-700': active !==  field.attribute_id }">
                        </button>
                    </template>
                </div>
            </div>
            <!-- END Nav Tabs -->

            <!-- Tab Content -->
            <div role="tabpanel" class="flex-1">
                <template x-for="(field, index) in model">
                    <div
                        x-show="active === field.attribute_id"
                        tab="tabpanel"
                        tabindex="0"
                        class="text-gray-700">
                        <template x-if="['Lunar\\FieldTypes\\Text'].includes(fields[field.attribute_id].type)">
                            <div class="flex-column gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                                <h2 x-text="fields[field.attribute_id].name?.en" class="font-medium text-base text-gray-700 dark"></h2>

                                <div>
                                    <label>
                                        Default Value
                                    </label>
                                    <input
                                        class="w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                        x-model="field.attribute_data.default" />
                                </div>
                                <div x-show="! dynamicPricing">
                                    <label>
                                        Price
                                    </label>
                                    <div class="flex">
                                        <select
                                            class="w-[20%] flex min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 mr-4"
                                            x-model="field.attribute_data.prefix">
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
                                            x-model="field.attribute_data.price" />
                                    </div>
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
                        <template x-if="['Lunar\\FieldTypes\\Dropdown'].includes(fields[field.attribute_id].type)">
                            <div class="flex-column gap-2.5 justify-between px-4 py-6 border-b border-slate-300 dark:border-gray-800">
                                <h2 x-text="fields[field.attribute_id].name?.en" class="font-medium text-base text-gray-700 dark"></h2>

                                <div>
                                    <table class="table-auto">
                                        <thead>
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col" class="px-6 py-4">Option Value</th>
                                                <th scope="col" class="px-6 py-4" x-show="! dynamicPricing">Price</th>
                                                <th scope="col" class="px-6 py-4"></th>
                                            </tr>
                                        </thead>
                                        <tbody x-sort="alert($item + ' - ' + $position)">
                                            <template x-for="(option, index) in field.attribute_data">
                                                <tr x-sort:item="option.id">
                                                    <th scope="row"> <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i> </th>
                                                    <td class="px-6 py-4" x-text="option.name">
                                                    </td>
                                                    <td class="px-6 py-4" x-show="! dynamicPricing">
                                                        <div class="flex">
                                                            <select
                                                                class="flex w-1/2 min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                                x-model="field.attribute_data[index].prefix">
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
                                                                x-model="field.attribute_data[index].price" />
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4"><button type="button" x-on:click="remove(field.attribute_id, option.id)"><span class="icon-delete text-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 hover:rounded-md"></span></button></td>
                                                </tr>
                                            </template>

                                            <template x-if="!field.attribute_data.length">
                                                <tr>
                                                    <td colspan="3">Please add an option item to begin</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <template x-if="hasUnassignedOptions(field.attribute_id)">
                                            <tfoot>
                                                <tr>
                                                    <th scope="row" colspan="2">
                                                        <select
                                                            x-model="assignOption"
                                                            class="custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                                                            label="required">
                                                            <option value="">Select an option</option>
                                                            <template x-for="item in getUnassignedOptions(field.attribute_id)">
                                                                <option x-bind:value="item.id" x-text="item.name">
                                                                </option>
                                                            </template>
                                                        </select>
                                                    </th>
                                                    <td class="px-6 py-4">
                                                        <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addOption(field.attribute_id, assignOption)">Add</button>
                                                        <template x-if="getUnassignedOptions(field.attribute_id).length > 1">
                                                            <button class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" type="button" x-on:click="addAll(field.attribute_id)">Add all</button>
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


        <template x-if="dynamicPricing">
            <!-- Panel: Rules -->
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <!-- Header -->
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Rules
                    </h3>
                    <div class="flex gap-x-2.5 items-center">
                        <button
                            type="button"
                            class="secondary-button"
                            x-on:click="addRule">Add Ruleset</button>
                    </div>
                </div>
                <!-- End Header-->

                <!-- Body -->
                <div class="border-t border-gray-100 p-4 sm:p-6 dark:border-gray-800">
                    <!-- ====== Rules -->
                    <div x-data="{ openItem: 0 }" class="space-y-2">

                        <template x-for="(rule, index) in rules" :key="rule.id">
                            <!-- Accordion:  Rule -->
                            <details class="group [&amp;_summary::-webkit-details-marker]:hidden">
                                <summary class="flex cursor-pointer items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-4 py-3 font-medium text-gray-900 hover:bg-gray-50">
                                    <span x-text="rule.name"></span>

                                    <svg x-on:click="deleteRule(index)" width="5" class="size-5 shrink-0 transition-transform duration-300 group-open:-rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </summary>

                                <div class="p-4">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <div class="text-base text-gray-800 font-semibold">Conditions</div>
                                            <div>
                                                <button
                                                    type="button"
                                                    class="px-3 py-2 rounded bg-gray-600 mr-2"
                                                    x-on:click="addRuleCondition(index)">Add Condition</button>
                                                <button
                                                    type="button"
                                                    class="px-3 py-2 rounded bg-gray-600 mr-2"
                                                    x-on:click="duplicateRule(index)">Duplicate Ruleset</button>
                                            </div>
                                        </div>
                                        <template x-if="rule.conditions && rule.conditions.length">
                                            <!-- Rule -->
                                            <div class="flex flex-col flex-auto p-5 mb-px">
                                                <div class="inline-flex rounded-md shadow-sm" role="group">
                                                    <button
                                                        type="button"
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
                                                        x-bind:class="{ 'bg-gray bg-gray-100' : rule.logic == 'and', 'bg-white' : rule.logic != 'and' }"
                                                        x-on:click="setRuleLogic(index, 'and')">
                                                        And
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 border border-gray-200 rounded-e-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white"
                                                        x-bind:class="{ 'bg-gray bg-gray-100' : rule.logic == 'or', 'bg-white' : rule.logic != 'or' }"
                                                        x-on:click="setRuleLogic(index, 'or')">
                                                        Or
                                                    </button>
                                                </div>
                                                <!-- Conditions -->
                                                <div class="rounded box-shadow mb-4">
                                                    <template x-for="(condition, _index) in rule.conditions" :key="condition.id">
                                                        <div class="flex p-3 gap-4 justify-between mt-4">
                                                            <div class="flex gap-4 flex-1 max-sm:flex-wrap max-sm:flex-1">
                                                                <select
                                                                    x-model="rules[index].conditions[_index].field"
                                                                    class="custom-select flex w-1/3 min:w-1/3 h-10 py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                                                    <option value="">
                                                                        Select field
                                                                    </option>
                                                                    <template x-for="field in model" x-bind:key="field.attribute_id">
                                                                        <option x-bind:value="field.attribute_id" x-text="fields[field.attribute_id].name.en">
                                                                        </option>
                                                                    </template>
                                                                </select>

                                                                <select
                                                                    x-model="rules[index].conditions[_index].operator"
                                                                    class="custom-select inline-flex gap-x-1 justify-between items-center h-10 w-full max-w-[196px] py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400 max-sm:flex-auto max-sm:max-w-full">
                                                                    <option value="">
                                                                        Select operator
                                                                    </option>
                                                                    <template x-for="item in operators[fields[condition?.field]?.type]" :key="item">
                                                                        <option x-bind:value="item" x-text="item">
                                                                        </option>
                                                                    </template>
                                                                </select>

                                                                <div>
                                                                    <template x-if="linearOperators.includes(condition.operator) && textGroup.includes(fields[condition.field]?.type)">
                                                                        <input
                                                                            type="text"
                                                                            x-model="rules[index].conditions[_index].value"
                                                                            placeholder="input"
                                                                            class="flex w-[289px] min:w-1/3 h-10 py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800" />
                                                                    </template>
                                                                    <template x-if="counterOperators.includes(condition.operator) && selectGroup.includes(fields[condition.field].type)">
                                                                        <input
                                                                            type="number"
                                                                            x-model="rules[index].conditions[_index].value"
                                                                            placeholder="input"
                                                                            class="flex w-[289px] min:w-1/3 h-10 py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800" />
                                                                    </template>
                                                                    <template x-if="linearOperators.includes(condition.operator) && selectGroup.includes(fields[condition.field].type)">
                                                                        <select
                                                                            x-model="rules[index].conditions[_index].value"
                                                                            class="custom-select inline-flex gap-x-1 justify-between items-center h-10 w-[196px] max-w-[196px] py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400">
                                                                            <option value="">
                                                                                Select value
                                                                            </option>
                                                                            <template x-for="item in model.find(item => item.attribute_id == condition.field).attribute_data" :key="item.id">
                                                                                <option x-bind:value="item.id" x-text="item.name">
                                                                                </option>
                                                                            </template>
                                                                        </select>
                                                                    </template>
                                                                    <template x-if="selectionOperators.includes(condition.operator) && selectGroup.includes(fields[condition.field].type)">
                                                                        <select
                                                                            x-model="rules[index].conditions[_index].value"
                                                                            class="custom-select inline-flex gap-x-1 justify-between items-center w-[196px] max-w-[196px] py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400"
                                                                            multiple
                                                                            size="5">
                                                                            <template x-for="item in model.find(item => item.attribute_id == condition.field).attribute_data" :key="item.id">
                                                                                <option x-bind:value="item.id" x-text="item.name">
                                                                                </option>
                                                                            </template>
                                                                        </select>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                class="max-h-9 max-w-9 text-2xl p-1.5 rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                                                x-on:click="deleteRuleCondition(index, condition.id)">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                                <!-- End Conditions -->
                                                <div class="mb-4">
                                                    <label
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray bg-gray-100 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">Price</label>
                                                    <input
                                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                        label="Value"
                                                        type="text"
                                                        x-model="rules[index].result"
                                                        placeholder="result" />
                                                </div>
                                                <div>
                                                    <label
                                                        class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray bg-gray-100 border border-gray-200 rounded-s-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">Name</label>
                                                    <input
                                                        class="flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                                        type="text"
                                                        x-model="rules[index].name"
                                                        placeholder="Add rule name" />
                                                </div>
                                            </div>
                                            <!-- End Rules -->
                                        </template>

                                        <template x-if="!rule.conditions">
                                            <div>
                                                Add a rule to begin
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </details>
                            <!-- End Accordion -->
                        </template>
                    </div>
                    <!-- ====== Rule End -->
                </div>
                <!-- End Body -->
            </div>
            <!-- END Panel -->
        </template>

        <button x-on:click="$wire.saveCustom()" class="relative border-black bg-white px-5 py-3 font-semibold text-black after:absolute after:inset-x-0 after:bottom-0 after:h-1 after:bg-black hover:text-white hover:after:h-full focus:ring-2 focus:ring-yellow-300 focus:outline-0" href="#">
            <span class="relative z-10"> Submit </span>
        </button>

    </div>
</x-filament-panels::page>
