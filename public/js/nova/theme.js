document.documentElement.classList.remove("light");
localStorage.setItem("novaTheme", "dark");

let iframeBtnCreated = false;
let useIframeBtn;
let previousPage;
let trixContentField;
let trixToolbar;
let iframeButton;
let iframeForm;
let buttonCancel;
let buttonAdd;
let inputLink;
let inputName;

const addIframeToTrix = () => {
    trixToolbar = document.querySelectorAll("trix-toolbar")[1];

    if (!trixToolbar) return;
    const toolbarId = trixToolbar.id;

    trixContentField = document.querySelector(
        `.trix-content[toolbar='${toolbarId}']`
    );

    iframeButton = createIframeButton();
    createIframeForm();

    trixToolbar
        .querySelector("span")
        .appendChild(iframeButton);
    trixToolbar.closest(".rounded-lg").appendChild(iframeForm);

    iframeButton.addEventListener("click", toggleIframeForm);
    iframeForm.addEventListener("submit", addIframe);
    window.addEventListener("keydown", escPressListener);
};

const toggleIframeForm = function () {
    iframeForm.classList.toggle("hidden");
};

const escPressListener = function (e) {
    if (e.key == "Escape") {
        hideIframeForm();
    }
};

const hideIframeForm = function () {
    iframeForm.classList.add("hidden");
};

const addIframe = function (event) {
    event.preventDefault();
    const form = this.closest("form");
    if (
        form.elements["iframe_src"].value &&
        form.elements["iframe_name"].value
    ) {
        const breakElement = document.createElement("br");
        const iframeStringElement = document.createTextNode(
            `<iframe src="${form.elements["iframe_src"].value}" title="${form.elements["iframe_name"].value}"></iframe>`
        );

        if (trixContentField.querySelector(":scope > div")) {
            trixContentField
                .querySelector(":scope > div")
                .appendChild(breakElement);
            trixContentField
                .querySelector(":scope > div")
                .appendChild(iframeStringElement);
        } else {
            const contentDiv = document.createElement("div");

            contentDiv.appendChild(iframeStringElement);
            trixContentField.appendChild(contentDiv);
        }

        setTimeout(function () {
            focusTrixContentEnd(trixContentField.querySelector(":scope > div"));
        }, 100);

        inputLink.value = "";
        inputName.value = "";
        hideIframeForm();
    }
};

const focusTrixContentEnd = function (element) {
    element.focus();
    let range = document.createRange();
    let selection = window.getSelection();

    // Select the last child node of the element
    range.selectNodeContents(element);
    // Collapse the range to the end point
    range.collapse(false);
    // Remove any existing selections
    selection.removeAllRanges();
    // Add the range to the selection
    selection.addRange(range);
};

const createIframeButton = () => {
    btn = document.createElement("button");
    btn.classList.add(
        "trix-button",
        "trix-button--icon",
        "trix-button--icon-iframe"
    );
    btn.value = "Attach Iframe";
    btn.title = "Attach Iframe";
    btn.id = "button-iframe";
    btn.type = "button";
    btn.setAttribute("tabindex", "-1");

    return btn;
};

const createIframeForm = () => {
    iframeForm = document.createElement("form");
    buttonCancel = document.createElement("button");
    buttonAdd = document.createElement("input");
    inputLink = document.createElement("input");
    inputName = document.createElement("input");
    const formTitle = document.createElement("p");
    const linkInputLabel = document.createElement("label");
    const nameInputLabel = document.createElement("label");

    iframeForm.classList.add(
        "iframe-form",
        "bg-white",
        "dark:bg-gray-800",
        "rounded-lg",
        "shadow",
        "divide-y",
        "divide-gray-100",
        "dark:divide-gray-700",
        "hidden"
    );
    formTitle.innerHTML = "Add an Iframe";
    formTitle.classList.add(
        "form-title",
        "font-normal",
        "text-xl",
        "md:text-xl",
        "mb-3"
    );
    linkInputLabel.innerHTML = "Link:";
    nameInputLabel.innerHTML = "Name:";
    linkInputLabel.setAttribute("for", "iframe_src");
    nameInputLabel.setAttribute("for", "iframe_name");
    inputLink.id = "iframe_src";
    inputLink.type = "text";
    inputLink.name = "iframe_src";
    inputLink.setAttribute("required", true);
    inputLink.placeholder = "Link";
    inputName.id = "iframe_name";
    inputName.type = "text";
    inputName.name = "iframe_name";
    inputName.setAttribute("required", true);
    inputName.placeholder = "Name";
    inputLink.classList.add(
        "form-control",
        "form-input",
        "form-input-bordered"
    );
    inputName.classList.add(
        "form-control",
        "form-input",
        "form-input-bordered"
    );
    buttonAdd.type = "submit";
    buttonAdd.value = "Add";
    buttonAdd.classList.add("iframe-btn", "btn-submit");
    buttonCancel.innerHTML = "Cancel";
    buttonCancel.type = "button";
    buttonCancel.classList.add("iframe-btn", "btn-cancel");

    iframeForm.appendChild(formTitle);
    iframeForm.appendChild(linkInputLabel);
    iframeForm.appendChild(inputLink);
    iframeForm.appendChild(nameInputLabel);
    iframeForm.appendChild(inputName);
    iframeForm.appendChild(buttonCancel);
    iframeForm.appendChild(buttonAdd);

    buttonCancel.addEventListener("click", hideIframeForm);

    return iframeForm;
};

const clearListeners = () => {
    if (buttonCancel) {
        buttonCancel.removeEventListener("click", hideIframeForm);
    }
    if (iframeButton) {
        iframeButton.removeEventListener("click", toggleIframeForm);
    }
    if (iframeForm) {
        iframeForm.removeEventListener("submit", addIframe);
    }
    window.removeEventListener("keydown", escPressListener);
};

window.onload = function () {
    let path = window.location.pathname;
    useIframeBtn =
        path.includes("resources/stories/") ||
        path.includes("resources/projects/");

    if (useIframeBtn && !iframeBtnCreated) {
        iframeBtnCreated = true;
        setTimeout(addIframeToTrix, 1000);
    }
};

window.navigation.addEventListener("navigate", () => {
    let path = window.location.pathname;
    useIframeBtn =
        path.includes("resources/stories/") ||
        path.includes("resources/projects/");

    if (useIframeBtn) {
        if (previousPage !== path && !iframeBtnCreated) {
            iframeBtnCreated = true;
            clearListeners();
            setTimeout(addIframeToTrix, 2000);
        }
    } else {
        iframeBtnCreated = false;
        clearListeners();
    }

    previousPage = path;
});

const ajaxSuccessCallback = function (event) {
    if (event.detail.method.toUpperCase() === "POST") {
        let path = window.location.pathname;
        useIframeBtn =
            path.includes("resources/stories/") ||
            path.includes("resources/projects/");

        if (useIframeBtn) {
            iframeBtnCreated = true;
            clearListeners();
            setTimeout(addIframeToTrix, 2000);
        }
    }
};

(function () {
    // Save the original XMLHttpRequest object
    const originalXHR = window.XMLHttpRequest;

    // Create a new constructor function
    function newXHR() {
        const realXHR = new originalXHR();
        let method;

        // Override the open method to capture the HTTP method
        const originalOpen = realXHR.open;
        realXHR.open = function (methodArg, url) {
            method = methodArg;
            originalOpen.apply(realXHR, arguments);
        };

        // Override the send method to add the event listener
        const originalSend = realXHR.send;
        realXHR.send = function () {
            realXHR.addEventListener(
                "readystatechange",
                function () {
                    if (realXHR.readyState === 4) {
                        if (realXHR.status >= 200 && realXHR.status < 300) {
                            const event = new CustomEvent("ajaxSuccess", {
                                detail: {
                                    xhr: realXHR,
                                    url: realXHR.responseURL,
                                    method: method,
                                },
                            });
                            document.dispatchEvent(event);
                        }
                    }
                },
                false
            );

            originalSend.apply(realXHR, arguments);
        };

        return realXHR;
    }

    // Override the global XMLHttpRequest object
    window.XMLHttpRequest = newXHR;

    document.addEventListener("ajaxSuccess", ajaxSuccessCallback);
})();
