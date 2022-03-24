window.onload = function () {
    let modal = document.getElementById("koala-wechat-x-hidden-modal")
    let overlay = document.getElementById("koala-wechat-x-hidden-modal-overlay")
    if (modal && overlay) {
        document.body.appendChild(modal);
        document.body.appendChild(overlay);
    }
    let active_btn_arr = document.getElementsByClassName("koala-wechat-x-modal-active-btn")

    for (let index = 0; index < active_btn_arr.length; index++) {
        active_btn_arr[index].onclick = function () {
            let data_target_model_id = active_btn_arr[index].getAttribute("data-target")
            koala_wechat_x_modal_active(data_target_model_id)
        }
    }

    let inactive_btn_arr = document.getElementsByClassName("koala-wechat-x-modal-overlay")

    for (let index = 0; index < inactive_btn_arr.length; index++) {
        inactive_btn_arr[index].onclick = function () {
            let data_target_model_id = inactive_btn_arr[index].getAttribute("data-target")
            koala_wechat_x_modal_inactive(data_target_model_id)
        }
    }
    if (document.getElementsByClassName("koala-wechat-x-code-input-input").length)
        document.getElementsByClassName("koala-wechat-x-code-input-input")[0].oninput = function (e) {
            let that = this
            let code_length = parseInt(that.getAttribute("code-length"))
            if (that.value.length === code_length) {
                that.blur();
                console.log("放弃焦点")
                let post_id = that.getAttribute("post-id")
                //$("#post-" + id + " .entry-content").html(data.data.content);
                let xmlHttp = createXMLHttpRequest()
                let url = "/wp-admin/admin-ajax.php?action=post";
                xmlHttp.open("POST", url, true);
                xmlHttp.onreadystatechange = function (res) {
                    if (xmlHttp.readyState === 4 && this.status === 200) {
                        let res = JSON.parse(this.responseText)
                        console.log(res)
                        if (res.success) {
                            setTimeout(function () {
                                document.getElementsByClassName("koala-wechat-x-status-wrong")[0].style.display = "none";
                                document.getElementsByClassName("koala-wechat-x-status-right")[0].style.display = "block";
                                setTimeout(function () {
                                    koala_wechat_x_modal_inactive("koala-wechat-x-hidden-modal")
                                    window.location.reload()
                                }, 1000);
                            }, 100)
                        } else {
                            setTimeout(function () {
                                document.getElementsByClassName("koala-wechat-x-status-right")[0].style.display = "none"
                                document.getElementsByClassName("koala-wechat-x-status-wrong")[0].style.display = "block"
                                that.value = ""
                                that.focus();
                            }, 100)
                        }
                    }
                };
                xmlHttp.setRequestHeader("Content-Type",
                    "application/x-www-form-urlencoded;");
                let res = xmlHttp.send('id=' + post_id + '&verifycode=' + that.value);
            }
            if (this.value.length > code_length) {
                this.blur();
                return false
            }
        }

    function createXMLHttpRequest() {
        var xmlHttp;
        if (window.XMLHttpRequest) {
            xmlHttp = new XMLHttpRequest();
            if (xmlHttp.overrideMimeType)
                xmlHttp.overrideMimeType('text/xml');
        } else if (window.ActiveXObject) {
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                }
            }
        }
        return xmlHttp;
    }

    function koala_wechat_x_modal_active(id) {
        koala_wechat_x_modal_show_or_hidden(id)
        document.getElementsByClassName("koala-wechat-x-code-input-input")[0].focus()
    }

    function koala_wechat_x_modal_inactive(id) {
        koala_wechat_x_modal_show_or_hidden(id)
    }

    function koala_wechat_x_modal_show_or_hidden(id) {
        let overlay = document.getElementById(id + "-overlay");
        overlay.style.display = (overlay.style.display === "none") ? "block" : "none";

        let modal = document.getElementById(id);
        modal.style.display = (modal.style.display === "none") ? "block" : "none";
    }
}