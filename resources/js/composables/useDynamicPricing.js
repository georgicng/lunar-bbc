import { computed } from "vue";

export const useDynamicPricing = (rules, customisations) => {
    const mapToId = (col, key = "id") => {
        return col.reduce(
            (acc, val) => ({
                ...acc,
                [val[key]]: val,
            }),
            {},
        );
    };
    const optionMap = computed(() => mapToId(customisations, "attribute_id"));
    const evalRule = (rule, domain) => {
        const { logic, conditions, result } = rule;
        const outcome = conditions.reduce((acc, condition) => {
            const { field, operator, value } = condition;
            const domainValue = domain[field];
            const isArrayCheck = Array.isArray(value);
            let check;
            switch (operator) {
                case "exist":
                    check = !!domainValue;
                    break;
                case "empty":
                    check = !domainValue;
                    break;
                case "=":
                case "in":
                    check = isArrayCheck
                        ? value.includes(String(domainValue))
                        : domainValue == value;
                    break;
                case "!=":
                case "not in":
                    check = isArrayCheck
                        ? !value.includes(String(domainValue))
                        : domainValue != value;
                    break;
                case "regex":
                    check = domainValue.match(value);
                    break;
                case "include":
                    check = domainValue.every((_value) =>
                        value.includes(_value),
                    );
                    break;
                case "exclude":
                    check = domainValue.every(
                        (_value) => !value.includes(_value),
                    );
                    break;
                case "count":
                    check = domainValue.length == value;
                    break;
                default:
                    check = false;
            }
            if (acc == null) {
                return check;
            }
            return logic === "and" ? acc && check : acc || check;
        }, null);
        return outcome ? parseFloat(result) : 0;
    };

    const getOptionIncrement = (key, value) => {
        if (!value) {
            return 0;
        }
        if (!Array.isArray(value)) {
            value = [value];
        }
        const option = optionMap.value[key];
        return value.reduce((acc, _value) => {
            if (!Array.isArray(option.attribute_data)) {
                const { prefix, price } = option.attribute_data;
                return acc + (prefix === "+" ? price : -price);
            }
            const selection = option.attribute_data.find(
                (data) => data.id === _value,
            );
            if (!selection) {
                return acc;
            }
            const { prefix, price } = selection;
            return acc + +(prefix === "+" ? price : -price);
        }, 0);
    };

    const getMarkup = (model) => {
        return Object.keys(model).reduce(
            (acc, key) => (acc += getOptionIncrement(key, model[key])),
            0,
        );
    };

    const getDynamicMarkup = (model) => {
        return rules.reduce(
            (acc, rule) => (acc += evalRule(rule, model)),
            0,
        );
    };

    return {
        getMarkup,
        getDynamicMarkup,
    };
};
