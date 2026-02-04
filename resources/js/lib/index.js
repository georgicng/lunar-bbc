export const mapToId = (col, key = "id") => {
    return col.reduce(
        (acc, val) => ({
            ...acc,
            [val[key]]: val,
        }),
        {},
    );
};

export const reference = () => {
    let text = "";
    let possible =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (let i = 0; i < 10; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
};
