$(function () {
    "use strict";

    // Original code from license.js
    $(document).ready(function () {
        let form = $("form.form-lock-small");
        form.parsley();
        $(".btn-unlock", form).on("click", function (e) {
            e.preventDefault();
            if (!form.parsley().validate()) return;
            let security = $("#nf_lock", form).val();
            let encryption = new Encryption();
            let formData = getFormData("ajax_unlock", security, form);
            formData["lock-password"] = encryption.encrypt(
                formData["lock-password"],
                security
            );
            callAjaxLoading(
                formData,
                (res) => {
                    if (res.success) {
                        showSuccessMessage(
                            () => {
                                window.location.href = $("#urlredirect").val();
                            },
                            res.data.msg,
                            "Mở khóa thành công"
                        );
                    } else {
                        showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
                    }
                },
                (msg) => {
                    showErrorMessage(msg);
                }
            );
        });
    });

    // License manager code
    const nonce = $('#nonce-license').val();
    const tableBody = $('#license-table tbody');
    const form = $('#license-form');
    const formTitle = $('#license-form-title');
    const licenseIdField = $('#license_id');
    const pluginIdField = $('#plugin_id');
    const versionDetailIdField = $('#version_detail_id');
    const customerIdField = $('#customer_id');

    if (typeof initSelect2Customer === "function") {
        initSelect2Customer('customer_id', form);
    }

    function sendRequest(action, data = {}) {
        const postData = {
            action: 'cdw_handle_license_action',
            nonce: nonce,
            ...data,
            license_action: action,
        };
        return $.post(objAdmin.ajax_url, postData);
    }

    function loadVersionDetails(pluginId, selectedVersionDetailId = null) {
        versionDetailIdField.empty().append('<option value="">Chọn phiên bản</option>');
        if (!pluginId) {
            return;
        }

        sendRequest('get_version_details_by_plugin', { plugin_id: pluginId }).done(function (response) {
            if (response.success) {
                response.data.forEach(function (versionDetail) {
                    const option = `<option value="${versionDetail.id}">${versionDetail.version} (${versionDetail.date})</option>`;
                    versionDetailIdField.append(option);
                });
                if (selectedVersionDetailId) {
                    versionDetailIdField.val(selectedVersionDetailId).trigger('change');
                }
            } else {
                showErrorMessage(response.data.msg || 'Không thể tải chi tiết phiên bản.', 'Lỗi');
            }
        }).fail(function () {
            showErrorMessage('Lỗi AJAX khi tải chi tiết phiên bản.', 'Lỗi');
        });
    }

    function loadLicenses() {
        tableBody.html('<tr><td colspan="10">Đang tải...</td></tr>');

        sendRequest('get_licenses').done(function (response) {
            if (response.success) {
                tableBody.empty();
                response.data.forEach(function (license) {
                    const row = `
                        <tr data-id="${license.id}">
                            <td>${license.title}</td>
                            <td>${license.key}</td>
                            <td>${license.customer_name}</td>
                            <td>${license.plugin_name}</td>
                            <td>${license.type === 'free' ? 'Miễn phí' : 'Trả phí'}</td>
                            <td>${license.starts_at}</td>
                            <td>${license.expires_at}</td>
                            <td class="status">${license.status === 'active' ? 'Kích hoạt' : 'Vô hiệu hóa'}</td>
                            <td>${license.version}</td>
                            <td class="actions">
                                ${license.status === 'active'
                            ? `<button class="btn btn-sm btn-warning deactivate-license">Vô hiệu hóa</button>`
                            : `<button class="btn btn-sm btn-success activate-license">Kích hoạt</button>`}
                                <button class="btn btn-sm btn-primary edit-license">Sửa</button>
                                <button class="btn btn-sm btn-info renew-license">Gia hạn</button>
                                <button class="btn btn-sm btn-danger delete-license">Xóa</button>
                                <button class="btn btn-sm btn-secondary view-license">Xem</button>
                            </td>
                        </tr>`;
                    tableBody.append(row);
                });
            } else {
                const msg = response.data.msg || 'Không thể tải danh sách giấy phép.';
                showErrorMessage(msg, 'Lỗi');
                tableBody.html(`<tr><td colspan="10">${msg}</td></tr>`);
            }
        }).fail(function () {
            const msg = 'Đã xảy ra lỗi khi tải giấy phép.';
            showErrorMessage(msg, 'Lỗi');
            tableBody.html(`<tr><td colspan="10">${msg}</td></tr>`);
        });
    }

    function resetForm() {
        form[0].reset();
        licenseIdField.val('');
        formTitle.text('Thêm mới Giấy phép');
        $('#starts_at').val(new Date().toISOString().split('T')[0]);
        pluginIdField.val('').trigger('change');
        versionDetailIdField.empty().append('<option value="">Chọn phiên bản</option>');
        customerIdField.val(null).trigger('change');
    }

    tableBody.on('click', '.view-license', function () {
        const row = $(this).closest('tr');
        const licenseKey = row.find('td:nth-child(2)').text();

        sendRequest('get_licenses').done(function (response) {
            if (response.success) {
                const license = response.data.find(l => l.key === licenseKey);
                if (license) {
                    $('#modal-license-key').text(license.key);
                    $('#modal-plugin-code').text(license.plugin_code);
                    $('#view-details-modal').modal('show');
                }
            } else {
                showErrorMessage(response.data.msg || 'Không thể lấy chi tiết giấy phép.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi lấy chi tiết giấy phép.', 'Lỗi');
        });
    });

    tableBody.on('click', '.delete-license', function () {
        if (!confirm('Bạn có chắc chắn muốn xóa?')) return;
        const row = $(this).closest('tr');
        const licenseId = row.data('id');
        sendRequest('delete', { license_id: licenseId }).done(function (response) {
            if (response.success) {
                row.remove();
                showSuccessMessage(null, 'Xóa giấy phép thành công.');
            } else {
                showErrorMessage(response.data.msg || 'Không thể xóa giấy phép.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi xóa giấy phép.', 'Lỗi');
        });
    });

    tableBody.on('click', '.activate-license, .deactivate-license', function () {
        const button = $(this);
        const row = button.closest('tr');
        const licenseId = row.data('id');
        const action = button.hasClass('activate-license') ? 'activate' : 'deactivate';

        sendRequest(action, { license_id: licenseId }).done(function (response) {
            if (response.success) {
                loadLicenses();
            } else {
                showErrorMessage(response.data.msg || 'Không thể thay đổi trạng thái giấy phép.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi thay đổi trạng thái giấy phép.', 'Lỗi');
        });
    });

    tableBody.on('click', '.renew-license', function () {
        const row = $(this).closest('tr');
        const licenseId = row.data('id');
        sendRequest('renew', { license_id: licenseId }).done(function (response) {
            if (response.success) {
                loadLicenses();
            } else {
                showErrorMessage(response.data.msg || 'Không thể gia hạn giấy phép.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi gia hạn giấy phép.', 'Lỗi');
        });
    });

    tableBody.on('click', '.edit-license', function () {
        const licenseId = $(this).closest('tr').data('id');
        sendRequest('get_details', { license_id: licenseId }).done(function (response) {
            if (response.success) {
                const license = response.data;
                formTitle.text('Sửa Giấy phép');
                licenseIdField.val(license.id);
                $('#license_title').val(license.title);
                $('#plugin_id').val(license.plugin_id);
                $('#license_type').val(license.type);
                $('#starts_at').val(license.starts_at);
                $('#status').val(license.status);
                $('#duration').val(license.duration);
                $('#version').val(license.version_id);
                if (license.customer_id) {
                    customerIdField.val(license.customer_id).trigger('change');
                } else {
                    customerIdField.val(null).trigger('change');
                }
                if (license.plugin_id) {
                    loadVersionDetails(license.plugin_id, license.version_id);
                }
            } else {
                showErrorMessage(response.data.msg || 'Không thể lấy chi tiết giấy phép để sửa.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi lấy chi tiết giấy phép.', 'Lỗi');
        });
    });

    // Event listener for module selection change
    pluginIdField.on('change', function () {
        const selectedPluginId = $(this).val();
        loadVersionDetails(selectedPluginId);
    });

    form.on('submit', function (e) {
        e.preventDefault();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Đang lưu...');

        const formData = $(this).serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});

        sendRequest('save', formData).done(function (response) {
            if (response.success) {
                resetForm();
                loadLicenses();
                showSuccessMessage(null, 'Lưu giấy phép thành công.');
            } else {
                showErrorMessage(response.data.msg || 'Không thể lưu giấy phép.', 'Lỗi');
            }
        }).fail(function() {
            showErrorMessage('Lỗi AJAX khi lưu giấy phép.', 'Lỗi');
        }).always(function () {
            submitButton.prop('disabled', false).text('Lưu Giấy phép');
        });
    });

    $('#clear-form').on('click', function () {
        resetForm();
    });

    if ($('#license-table').length) {
        loadLicenses();
    }

    // If a module is pre-selected (e.g., on page load for edit form), load its versions
    if (pluginIdField.val()) {
        loadVersionDetails(pluginIdField.val());
    }
});
