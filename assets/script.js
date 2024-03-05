jQuery(document).ready(function ($) {
    if ($(".btdev_form").length) {
        const btDevGetFieldAction = (field) => {
            let action = "click";
            if (
                ["payment", "radio", "checkbox"].indexOf(field["type"]) !== -1
            ) {
            } else if (["select"].indexOf(field["type"]) !== -1) {
                action = "change";
            } else if (["text", "textarea"].indexOf(field["type"]) !== -1) {
                action = "blur";
            }

            return action;
        };

        const btDevCalculateNewPrice = (total, price) => {
            let returnVal = total;

            if (price.type !== undefined) {
                if (price.type === "relative") {
                    returnVal += price.value;
                } else {
                    returnVal = price.value;
                }
            }

            return returnVal;
        };

        const btDevRecalculateEntryTotal = (entry) => {
            let total = window.btdevForm.basePrice * 1;
            const index = $(entry).attr("attr-index");
            let fieldPrices = JSON.parse(window.btdevForm.priceChanges);

            let entryElement = $(entry);
            for (var key in fieldPrices) {
                let inputSelector = ".input_named_" + key;

                if (
                    ["radio", "checkbox", "payment"].indexOf(
                        fieldPrices[key]["type"]
                    ) !== -1
                ) {
                    if (fieldPrices[key]["options"] !== undefined) {
                        for (var innerOption in fieldPrices[key]["options"]) {
                            // innerOption
                            const isSelected = $(
                                inputSelector + '[value="' + innerOption + '"]'
                            ).is(":checked");
                            if (isSelected) {
                                newTotal = btDevCalculateNewPrice(
                                    total,
                                    fieldPrices[key]["options"][innerOption]
                                );
                                total = newTotal;
                            }
                        }
                    }
                } else if (
                    ["select"].indexOf(fieldPrices[key]["type"]) !== -1
                ) {
                    let input = entryElement.find(inputSelector);

                    let valuesThatModify = [];
                    if (fieldPrices[key]["options"] !== undefined) {
                        for (var innerOption in fieldPrices[key]["options"]) {
                            valuesThatModify.push(innerOption);
                        }
                    }

                    if (
                        valuesThatModify.indexOf(input.val()) !== -1 &&
                        fieldPrices[key]["options"][input.val()] !== undefined
                    ) {
                        newTotal = btDevCalculateNewPrice(
                            total,
                            fieldPrices[key]["options"][input.val()]
                        );
                        total = newTotal;
                    }
                } else if (
                    ["text", "textarea"].indexOf(fieldPrices[key]["type"]) !==
                    -1
                ) {
                    let input = entryElement.find(inputSelector);
                    if (input.val() !== "" && fieldPrices[key]["price"]) {
                        newTotal = btDevCalculateNewPrice(
                            total,
                            fieldPrices[key]["price"]
                        );
                        total = newTotal;
                    }
                }
            }

            entryElement
                .find(
                    ".btdev_inscriere_total_text .btdev_inscriere_total_amount"
                )
                .html(total / 100);

            return total;
        };

        const btDevRecalculateGrandTotal = () => {
            let grandTotal = 0;

            $(".btdev_data_wrapper").each((index, element) => {
                const rowTotal = btDevRecalculateEntryTotal(element);
                grandTotal += rowTotal;
            });

            $("#grand_total_wrapper .btdev_inscriere_total_amount").html(
                grandTotal / 100
            );
        };

        const btDevShowFields = () => {
            $(
                '.btdev_form input[type="radio"]:visible:checked, .btdev_form input[type="checkbox"]:visible:checked'
            ).trigger("click");
            $(".btdev_form select:visible").trigger("change");
            $(
                '.btdev_form input[type="text"]:visible, .btdev_form input[type="email"]:visible, .btdev_form input[type="date"]:visible, .btdev_form textarea:visible'
            ).trigger("blur");
        };

        if (window.btdevForm !== undefined) {
            // Action logic inputs
            if (
                window.btdevForm.hideLogic !== undefined &&
                window.btdevForm.hideLogic !== "false"
            ) {
                try {
                    let fieldLogics = JSON.parse(window.btdevForm.hideLogic);
                    for (var key in fieldLogics) {
                        if (fieldLogics[key]["actions"].length > 0) {
                            let elementSelector = '[name="' + key + '"]';
                            fieldLogics[key]["actions"].forEach((value) => {
                                let valueSelector = elementSelector;
                                if (value.value !== "") {
                                    if (
                                        [
                                            "payment",
                                            "radio",
                                            "checkbox",
                                        ].indexOf(fieldLogics[key]["type"]) !==
                                        -1
                                    ) {
                                        valueSelector +=
                                            '[value="' + value.value + '"]';
                                    }
                                }

                                let action = btDevGetFieldAction(
                                    fieldLogics[key]
                                );
                                if (value.data.action !== undefined) {
                                    action = value.data.action;
                                }

                                $("body").on(
                                    action,
                                    valueSelector,
                                    function (e) {
                                        if ($(this).is(":visible")) {
                                            for (var actionEl in value.data
                                                .events) {
                                                value.data.events[
                                                    actionEl
                                                ].forEach((fieldInAction) => {
                                                    let fieldTmp = $(
                                                        '.btdev_form [name="' +
                                                            fieldInAction +
                                                            '"]'
                                                    );
                                                    let fieldTmpGroup = $(
                                                        '.btdev_form [name="' +
                                                            fieldInAction +
                                                            '"]'
                                                    ).closest(".form-group");
                                                    if (
                                                        fieldTmpGroup.length < 1
                                                    ) {
                                                        fieldTmpGroup = $(
                                                            ".btdev_form #wrapper_" +
                                                                fieldInAction +
                                                                ".form-group"
                                                        );
                                                    }

                                                    if (
                                                        fieldTmpGroup.length > 0
                                                    ) {
                                                        if (
                                                            actionEl === "hide"
                                                        ) {
                                                            if (
                                                                $(
                                                                    fieldTmpGroup
                                                                ).hasClass(
                                                                    "required_element"
                                                                )
                                                            ) {
                                                                $(
                                                                    fieldTmp
                                                                ).attr(
                                                                    "required",
                                                                    false
                                                                );
                                                            }
                                                            fieldTmpGroup.addClass(
                                                                "hidden_element"
                                                            );
                                                        } else if (
                                                            actionEl === "show"
                                                        ) {
                                                            if (
                                                                $(
                                                                    fieldTmpGroup
                                                                ).hasClass(
                                                                    "required_element"
                                                                )
                                                            ) {
                                                                $(
                                                                    fieldTmp
                                                                ).attr(
                                                                    "required",
                                                                    true
                                                                );
                                                            }
                                                            fieldTmpGroup.removeClass(
                                                                "hidden_element"
                                                            );
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    }
                                );
                            });
                        }
                    }
                    btDevShowFields();
                } catch (e) {
                    return console.error(e);
                }
            }

            // Price logic inputs
            if (
                window.btdevForm.priceChanges !== undefined &&
                window.btdevForm.priceChanges !== "false"
            ) {
                try {
                    $("body").on(
                        "click",
                        '.btdev_form input[type="radio"], .btdev_form input[type="checkbox"]',
                        function (e) {
                            if ($(this).is(":visible")) {
                                btDevRecalculateGrandTotal();
                            }
                        }
                    );
                    $("body").on("change", ".btdev_form select", function (e) {
                        if ($(this).is(":visible")) {
                            btDevRecalculateGrandTotal();
                        }
                    });
                    $("body").on(
                        "blur",
                        '.btdev_form input[type="text"], .btdev_form input[type="email"], .btdev_form input[type="date"], .btdev_form textarea',
                        function (e) {
                            if ($(this).is(":visible")) {
                                btDevRecalculateGrandTotal();
                            }
                        }
                    );
                    btDevRecalculateGrandTotal();
                } catch (e) {
                    return console.error(e);
                }
            }

            // Form add new participant
            $("body").on(
                "click",
                ".btdev_form .form_add_new_data",
                function (e) {
                    e.preventDefault();
                    let newIndex = Math.floor(Math.random() * 100);
                    if (
                        $('.btdev_data_wrapper[attr-index="' + newIndex + '"]')
                            .length > 0
                    ) {
                        newIndex = Math.floor(Math.random() * 100);
                    }
                    let replaced_html = window.btdevForm.repeaterData;
                    replaced_html = replaced_html.replaceAll("%s", newIndex);
                    window.btdevForm.count++;
                    $("#repeater_data").append(replaced_html);

                    btDevRecalculateGrandTotal();
                }
            );

            // Form remove new participant
            $("body").on(
                "click",
                ".btdev_form .form_remove_data",
                function (e) {
                    e.preventDefault();
                    if (window.btdevForm.count === 1) {
                        alert(
                            btdev_inscriere_ajax["translation"][
                                "you_must_have_at_least_one_entry"
                            ]
                        );
                    } else {
                        if (
                            confirm(
                                btdev_inscriere_ajax["translation"][
                                    "are_you_sure_you_want_to_delete"
                                ]
                            )
                        ) {
                            $(this).closest(".btdev_data_wrapper").remove();
                            window.btdevForm.count--;
                            btDevRecalculateGrandTotal();
                        }
                    }
                }
            );

            // Form participant change name
            $("body").on(
                "blur",
                ".btdev_form .btdev_data_wrapper .firstname_entry, .btdev_form .btdev_data_wrapper .lastname_entry",
                function (e) {
                    let nameDiv = $(this)
                        .closest(".btdev_data_wrapper")
                        .find(".name_entry");
                    let firstnameInput = $(this)
                        .closest(".btdev_data_wrapper")
                        .find(".firstname_entry");
                    let lastInput = $(this)
                        .closest(".btdev_data_wrapper")
                        .find(".lastname_entry");

                    let name =
                        $(firstnameInput).val() + " " + $(lastInput).val();
                    if (name === " ") {
                        name =
                            btdev_inscriere_ajax["translation"]["participant"];
                    }

                    $(nameDiv).html(name);
                }
            );

            // Disable Others
            if (
                $('.btdev_form .disable_other input[type="radio"]').length > 0
            ) {
                // Action on click
                $("body").on(
                    "click",
                    '.disable_other input[type="radio"]',
                    function (e) {
                        let parent_wrapper = $(this).closest(".disable_other");
                        let change_input = parent_wrapper.attr("attr-change");
                        let participant_id = $(this)
                            .closest(".btdev_data_wrapper")
                            .attr("attr-index");
                        let value = $(this).val();

                        // Reset disable in other wrapper
                        $(
                            "#wrapper_" +
                                change_input +
                                "_" +
                                participant_id +
                                ' input[type="radio"]:not(.always_disabled)'
                        ).attr("disabled", false);

                        if (value !== "nu_particip") {
                            // Disable the pair of current selected
                            $(
                                "#wrapper_" +
                                    change_input +
                                    "_" +
                                    participant_id +
                                    ' input[type="radio"][value="' +
                                    value +
                                    '"]:not(.always_disabled)'
                            ).attr("disabled", true);
                        }
                    }
                );

                // Action on load
                let items = $('.disable_other input[type="radio"]');
                if (items.length > 0) {
                    items.each((item) => {
                        if ($(items[item]).is(":checked")) {
                            $(items[item]).trigger("click");
                        }
                    });
                }
            }
        }
    }

    if ($(".table_wrapper").length) {
        function reloadActiveTable() {
            if (
                window.datatableBTDEV.table !== undefined &&
                window.datatableBTDEV.table !== undefined
            ) {
                window.datatableBTDEV.table.draw();
            }
        }

        $("body").on("change", "#filter_payment_status", function () {
            reloadActiveTable();
        });

        $("body").on("click", ".btdev_action_link", function () {
            let element = $(this);
            let elementData = element.attr("attr-data");
            let elementDataJs = element.attr("attr-dataJs");
            if (element.attr("attr-disabled") !== "true") {
                try {
                    if (elementData !== "" && elementDataJs !== "") {
                        elementData = $.parseJSON(elementData);
                        elementDataJs = $.parseJSON(elementDataJs);

                        let doAction = false;
                        if (
                            elementDataJs.confirm !== undefined &&
                            elementDataJs.confirm.enabled !== undefined &&
                            elementDataJs.confirm.enabled === true
                        ) {
                            let text =
                                btdev_inscriere_ajax.translation[
                                    "please_confirm_the_action"
                                ];
                            if (
                                elementDataJs.confirm !== undefined &&
                                elementDataJs.confirm.text !== undefined &&
                                elementDataJs.confirm.text !== ""
                            ) {
                                text = elementDataJs.confirm.text;
                            }

                            if (confirm(text)) doAction = true;
                        } else {
                            doAction = true;
                        }

                        if (doAction) {
                            element.attr("attr-disabled", "true");
                            jQuery
                                .post(
                                    btdev_inscriere_ajax.ajax_url,
                                    elementData,
                                    function (response) {
                                        if (response.status === true) {
                                            reloadActiveTable();
                                            window[
                                                "btdevNotification"
                                            ].showNotification({
                                                text: response.message,
                                            });
                                        } else {
                                            console.log(response.message);
                                            window[
                                                "btdevNotification"
                                            ].showNotification({
                                                text: response.message,
                                                type: "error",
                                            });
                                        }
                                    }
                                )
                                .fail(function (e) {
                                    let text =
                                        e.status +
                                        ": " +
                                        (e.statusText || "error");

                                    console.log(text);
                                    window[
                                        "btdevNotification"
                                    ].showNotification({
                                        text: text,
                                        type: "error",
                                    });
                                })
                                .always(function () {
                                    element.removeAttr("attr-disabled");
                                });
                        }
                    }
                } catch (error) {
                    console.error(error);
                }
            }
            /*
            window.datatableBTDEV.formType
            window.datatableBTDEV.tableType
            */
        });
    }

    class BtdevNotification {
        elementId = "#btdev_popup_notifications";
        wrapperNotifications = null;
        defaultSettings = {
            type: "success",
            text: null,
            closeAfter: 3000,
            autoClose: true,
            showClose: true,
        };

        constructor() {
            let element = $(this.elementId);
            let thisClass = this;
            if (element.length > 0) {
                this.wrapperNotifications = element;
                $("body").on(
                    "click",
                    "#btdev_popup_notifications > div .close",
                    function (ev) {
                        thisClass.dismissNotification($(ev.target).parent());
                    }
                );
            }
        }

        dismissNotification(element) {
            $(element)
                .stop(true, true)
                .animate(
                    {
                        opacity: 0,
                        height: 0,
                    },
                    1000,
                    function () {
                        $(element).remove();
                    }
                );
        }

        prepareHtml(settings) {
            return (
                `<div class="popup_notification_wrapper ` +
                settings.type +
                `">
                <div class="text">` +
                settings.text +
                `</div>` +
                (settings.showClose ? `<div class="close">x</div>` : ``)
            );
        }

        showNotification(settings = null) {
            if (this.wrapperNotifications != null) {
                let thisClass = this;
                let mergedSettings = Object.assign(
                    {},
                    this.defaultSettings,
                    settings
                );
                this.wrapperNotifications.append(
                    this.prepareHtml(mergedSettings)
                );
                if (mergedSettings.autoClose) {
                    let newElement = $(this.wrapperNotifications)
                        .children()
                        .last();
                    setTimeout(() => {
                        thisClass.dismissNotification(newElement);
                    }, mergedSettings.closeAfter);
                }
            } else {
                console.log("Notification element not found");
            }
        }
    }
    window["btdevNotification"] = new BtdevNotification();
});
