export const mapToId = (col, key = "id") => {
    return col.reduce(
        (acc, val) => ({
            ...acc,
            [val[key]]: val,
        }),
        {},
    );
};
