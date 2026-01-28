<tr
x-data="{
    model: {
        default: '',
    },
    option: null,
    dynamicPricing: false
 }"
 x-modelable="model"
 x-sort:item="option?.id">
    <th scope="row"> <i class="icon-drag text-[20px] transition-all group-hover:text-gray-700"></i> </th>
    <td class="px-6 py-4" x-text="option?.name">
    </td>
    <td v-show="!dynamicPricing" class="px-6 py-4">
        <div class="flex">
            <select
                class="flex w-1/2 min-h-10 py-2.5 px-3.5 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-2.5 text-6 text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400"
                x-model="model.prefix">
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
                x-model="model.price" />
        </div>
    </td>
    <td class="px-6 py-4"><button type="button" x-on:click="$dispatch('remove', { id: model.option_id, option: option.id}"><span class="icon-delete text-xl cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 hover:rounded-md"></span></button></td>
</tr>
