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
    const moduleIdField = $('#module_id');
    const versionDetailIdField = $('#version_detail_id');

    function sendRequest(action, data = {}) {
        const postData = {
            action: 'cdw_handle_license_action',
            nonce: nonce,
            ...data,
            license_action: action,
        };
        return $.post(objAdmin.ajax_url, postData);
    }

    function loadVersionDetails(moduleId, selectedVersionDetailId = null) {
        versionDetailIdField.empty().append('<option value="">Chọn phiên bản</option>');
        if (!moduleId) {
            return;
        }

        sendRequest('get_version_details_by_module', { module_id: moduleId }).done(function(response) {
            if (response.success) {
                response.data.forEach(function(versionDetail) {
                    const option = `<option value="${versionDetail.id}">${versionDetail.version} (${versionDetail.date})</option>`;
                    versionDetailIdField.append(option);
                });
                if (selectedVersionDetailId) {
                    versionDetailIdField.val(selectedVersionDetailId);
                }
            } else {
                console.error('Error loading version details:', response.data.msg);
            }
        }).fail(function() {
            console.error('AJAX error loading version details.');
        });
    }

    function loadLicenses() {
        tableBody.html('<tr><td colspan="8">Đang tải...</td></tr>');

        sendRequest('get_licenses').done(function(response) {
            if (response.success) {
                tableBody.empty();
                response.data.forEach(function(license) {
                    const row = `
                        <tr data-id="${license.id}">
                            <td>${license.title}</td>
                            <td>${license.key}</td>
                            <td>${license.plugin_name}</td>
                            <td>${license.module_name}</td>
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
                tableBody.html('<tr><td colspan="8">Không thể tải danh sách giấy phép.</td></tr>');
            }
        }).fail(function() {
            tableBody.html('<tr><td colspan="8">Đã xảy ra lỗi.</td></tr>');
        });
    }

    function resetForm() {
        form[0].reset();
        licenseIdField.val('');
        formTitle.text('Thêm mới Giấy phép');
        $('#starts_at').val(new Date().toISOString().split('T')[0]);
        moduleIdField.val(''); // Clear module selection
        versionDetailIdField.empty().append('<option value="">Chọn phiên bản</option>'); // Clear version details
    }

    tableBody.on('click', '.view-license', function() {
        const row = $(this).closest('tr');
        const licenseKey = row.find('td:nth-child(2)').text();
        
        sendRequest('get_licenses').done(function(response) {
            if (response.success) {
                const license = response.data.find(l => l.key === licenseKey);
                if (license) {
                    $('#modal-license-key').text(license.key);
                    $('#modal-plugin-id').text(license.plugin_id);
                    $('#view-details-modal').modal('show');
                }
            }
        });
    });
    
    tableBody.on('click', '.delete-license', function() {
        if (!confirm('Bạn có chắc chắn muốn xóa?')) return;
        const row = $(this).closest('tr');
        const licenseId = row.data('id');
        sendRequest('delete', { license_id: licenseId }).done(function(response) {
            if (response.success) {
                row.remove();
            }
        });
    });

    tableBody.on('click', '.activate-license, .deactivate-license', function() {
        const button = $(this);
        const row = button.closest('tr');
        const licenseId = row.data('id');
        const action = button.hasClass('activate-license') ? 'activate' : 'deactivate';
        
        sendRequest(action, { license_id: licenseId }).done(function(response) {
            if (response.success) {
                loadLicenses();
            }
        });
    });

    tableBody.on('click', '.renew-license', function() {
        const row = $(this).closest('tr');
        const licenseId = row.data('id');
        sendRequest('renew', { license_id: licenseId }).done(function(response) {
            if (response.success) {
                loadLicenses();
            }
        });
    });

    tableBody.on('click', '.edit-license', function() {
        const licenseId = $(this).closest('tr').data('id');
        sendRequest('get_details', { license_id: licenseId }).done(function(response) {
            if (response.success) {
                const license = response.data;
                formTitle.text('Sửa Giấy phép');
                licenseIdField.val(license.id);
                $('#license_title').val(license.title);
                $('#plugin_id').val(license.plugin_id);
                $('#license_type').val(license.type);
                $('#starts_at').val(license.starts_at);
                $('#status').val(license.status);
                $('#duration').val('1 year'); 
                $('#version').val(license.version);

                // Set module and load version details
                if (license.module_id) {
                    moduleIdField.val(license.module_id);
                    loadVersionDetails(license.module_id, license.version);
                }
            }
        });
    });

    // Event listener for module selection change
    moduleIdField.on('change', function() {
        const selectedModuleId = $(this).val();
        loadVersionDetails(selectedModuleId);
    });

    form.on('submit', function(e) {
        e.preventDefault();
        const submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Đang lưu...');

        const formData = $(this).serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});
        
        sendRequest('save', formData).done(function(response) {
            if (response.success) {
                resetForm();
                loadLicenses();
            }
        }).always(function() {
            submitButton.prop('disabled', false).text('Lưu Giấy phép');
        });
    });

    $('#clear-form').on('click', function() {
        resetForm();
    });

    if ($('#license-table').length) {
        loadLicenses();
    }

    // If a module is pre-selected (e.g., on page load for edit form), load its versions
    if (moduleIdField.val()) {
        loadVersionDetails(moduleIdField.val());
    }
});