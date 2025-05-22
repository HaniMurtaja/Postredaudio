export default {
    getNewResourceDisplayName(resourceName, resourceId) {
        return Nova.request().get(
            `/nova-vendor/holy-motors/inline-relationships/${resourceName}/${resourceId}`
        );
    },
};
