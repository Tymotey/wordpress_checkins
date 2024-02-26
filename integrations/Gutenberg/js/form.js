wp.blocks.registerBlockType("btdev-inscriere/form", {
    title: "Add Entry Form",
    icon: "smiley",
    category: "btdev",
    attributes: {
        formName: { type: "string" },
    },

    edit: function (props) {
        function updateFormName(event) {
            props.setAttributes({ formName: event.target.value });
        }

        return React.createElement(
            "div",
            null,
            React.createElement("h3", null, "BTDEV Inscrieri - Form"),
            React.createElement("input", {
                type: "text",
                value: props.attributes.formName,
                onChange: updateFormName,
            })
        );
    },
    save: function (props) {
        var shortcode =
            '[bbdev_inscrieri_form form="' + props.attributes.formName + '"]';

        return wp.element.createElement(
            "div",
            {},
            wp.element.createElement("RawHTML", {}, shortcode)
        );
    },
});
