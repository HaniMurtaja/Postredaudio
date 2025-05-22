let mix = require("laravel-mix");
let path = require("path");

require("./nova.mix");

mix.setPublicPath("dist")
    .js("resources/js/field.js", "js")
    .vue({ version: 3 })
    .css("resources/css/field.css", "css")
    .nova("holy-motors/inline-relationships")
    .webpackConfig({
        resolve: {
            alias: {
                "@/storage": path.resolve(
                    __dirname,
                    "../../nova/resources/js/storage"
                ),
                "@": path.resolve(__dirname, "../../nova/resources/js/"),
            },
            modules: [path.resolve(__dirname, "../../nova/node_modules/")],
            symlinks: false,
        },
    });
