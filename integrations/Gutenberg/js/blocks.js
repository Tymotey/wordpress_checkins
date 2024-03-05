wp.blocks.registerBlockType("btdev-inscriere/form", {
    title: "Entry Form Add",
    icon: "smiley",
    category: "btdev-inscrieri",
    attributes: {
        formName: { type: "string" },
    },

    edit: function (props) {
        function updateFormName(event) {
            props.setAttributes({ formName: event.target.value });
        }

        let forms = [];
        for (const [key, value] of Object.entries(btdev_inscriere_ajax.forms)) {
            forms.push([key, value.title]);
        }

        return React.createElement(
            "div",
            null,
            React.createElement("h4", null, "BTDEV Inscrieri - Form"),
            React.createElement("span", null, "Select a form: "),
            React.createElement(
                "select",
                {
                    value: props.attributes.formName,
                    onChange: updateFormName,
                },
                ...forms.map((val) => {
                    return React.createElement(
                        "option",
                        { value: val[0] },
                        val[1]
                    );
                })
            )
        );
    },
    save: function (props) {
        let shortcode = "";
        if (props.attributes.formName && props.attributes.formName !== "") {
            shortcode =
                '[bbdev_inscrieri_form form="' +
                props.attributes.formName +
                '"]';
        }

        return wp.element.createElement(
            "div",
            {
                className:
                    props.attributes.className &&
                    props.attributes.className !== ""
                        ? props.attributes.className
                        : "",
            },
            shortcode
        );
    },
});

wp.blocks.registerBlockType("btdev-inscriere/list", {
    title: "Lists",
    icon: "smiley",
    category: "btdev-inscrieri",
    attributes: {
        formName: { type: "string" },
    },

    edit: function (props) {
        function updateFormName(event) {
            props.setAttributes({ formName: event.target.value });
        }
        function updateTableType(event) {
            props.setAttributes({ tableType: event.target.value });
        }

        let forms = [];
        for (const [key, value] of Object.entries(btdev_inscriere_ajax.forms)) {
            forms.push([key, value.title]);
        }

        let tableTypes = [];
        for (const [key, value] of Object.entries(
            btdev_inscriere_ajax.tables
        )) {
            tableTypes.push([key, value]);
        }

        return React.createElement(
            "div",
            null,
            React.createElement("h4", null, "BTDEV Inscrieri - Liste"),
            React.createElement(
                "select",
                {
                    value: props.attributes.formName,
                    onChange: updateFormName,
                },
                ...forms.map((val) => {
                    return React.createElement(
                        "option",
                        { value: val[0] },
                        val[1]
                    );
                })
            ),
            React.createElement(
                "select",
                {
                    value: props.attributes.tableType,
                    onChange: updateTableType,
                },
                ...tableTypes.map((val) => {
                    return React.createElement(
                        "option",
                        { value: val[0] },
                        val[1]
                    );
                })
            )
        );
    },
    save: function (props) {
        let shortcode = "";
        if (props.attributes.formName && props.attributes.formName !== "") {
            shortcode =
                '[bbdev_inscrieri_list_entries form="' +
                props.attributes.formName +
                '" type="' +
                props.attributes.tableType +
                '"]';
        }

        return wp.element.createElement(
            "div",
            {
                className:
                    props.attributes.className &&
                    props.attributes.className !== ""
                        ? props.attributes.className
                        : "",
            },
            shortcode
        );
    },
});

wp.blocks.registerBlockType("btdev-inscriere/summary", {
    title: "Summary",
    icon: "smiley",
    category: "btdev-inscrieri",
    attributes: {
        formName: { type: "string" },
    },

    edit: function (props) {
        function updateFormName(event) {
            props.setAttributes({ formName: event.target.value });
        }

        let forms = [];
        for (const [key, value] of Object.entries(btdev_inscriere_ajax.forms)) {
            forms.push([key, value.title]);
        }

        return React.createElement(
            "div",
            null,
            React.createElement("h4", null, "BTDEV Inscrieri - Liste"),
            React.createElement(
                "select",
                {
                    value: props.attributes.formName,
                    onChange: updateFormName,
                },
                ...forms.map((val) => {
                    return React.createElement(
                        "option",
                        { value: val[0] },
                        val[1]
                    );
                })
            )
        );
    },
    save: function (props) {
        let shortcode = "";
        if (props.attributes.formName && props.attributes.formName !== "") {
            shortcode =
                '[bbdev_inscrieri_entry_summary form="' +
                props.attributes.formName +
                '"]';
        }

        return wp.element.createElement(
            "div",
            {
                className:
                    props.attributes.className &&
                    props.attributes.className !== ""
                        ? props.attributes.className
                        : "",
            },
            shortcode
        );
    },
});
