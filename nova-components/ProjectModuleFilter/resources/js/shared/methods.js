export default {
    capitalizeArrayElements: function (array) {
        return array
            .filter((module) => module.status)
            .map((module) =>
                module.name
                    .split(" ")
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(" ")
            )
            .join(" - ");
    },
};
