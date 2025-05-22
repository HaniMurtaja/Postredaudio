import IndexField from "./fields/IndexField";
import DetailField from "./fields/DetailField";
import FormField from "./fields/FormField";

Nova.booting((app, store) => {
    app.component("index-inline-relationships", IndexField);
    app.component("detail-inline-relationships", DetailField);
    app.component("form-inline-relationships", FormField);
});
