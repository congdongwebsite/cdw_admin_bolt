$(function () {
  "use strict";
  $(document).ready(function () {
    if ($(".client-email").length > 0) email.initialize();
  });
});

function actionEmailFormatter(data, type, row, meta) {
  let buttons = '';
  if (row.urlEmail) {
    buttons += '<a class="btn-detail-index btn btn-sm btn-primary" target="_blank" title="Đăng nhập Email" href="' + row.urlEmail + '">Đăng nhập</a>';
  }
  if (row.inet_email_id) {
    buttons += ` <div class="btn-group" role="group">
                   <button type="button" class="btn btn-sm btn-info btn-configure-email-inet" data-id="${row.id}" data-inet-id="${row.inet_email_id}">Cấu hình</button>
                   <button type="button" class="btn btn-sm btn-warning btn-gen-dkim-email-inet" data-id="${row.id}" data-inet-id="${row.inet_email_id}">Tạo DKIM</button>
                   <button type="button" class="btn btn-sm btn-primary btn-change-email-plan" data-id="${row.id}" data-inet-id="${row.inet_email_id}">Đổi gói</button>
                 </div>`;
  } else {
    buttons += ` <button type="button" class="btn btn-sm btn-success btn-register-email-inet" data-id="${row.id}">Đăng ký</button>`;
  }
  return buttons;
}
var email = (function (self, base) {
  base.action = "ajax_get-client-email";
  base.tableID = "#tb-data";
  base.context = $(".client-email");
  base.buttons = [
    ...base.buttons,
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Mua Email</pan>',
      className: "btn-danger",
      action: function (e, dt, node, config) {
        self.byEmail();
      },
    },
    {
      text: '<i class="fa fa-shopping-cart" aria-hidden="true"></i> <span>Gia Hạn</pan>',
      className: "btn-warning",
      action: function (e, dt, node, config) {
        self.addToCart();
      },
    },
  ];
  self.byEmail = (e) => {
    window.location.href = $("#url-choose").val();
  };
  self.addToCart = (e) => {
    let data = base.getSelectedRows();
    if (data.length == 0) {
      Swal.fire({
        title: "Vui lòng chọn email muốn gia hạn!",
        text: "Vui lòng chọn email!",
        icon: "warning",
      });
      return;
    }
    ids = [];

    data.map((row, index) => {
      ids = [...ids, row.id];
    });

    Swal.fire({
      title: "Gia hạn email",
      text: "Bạn có chắc muốn gia hạn cho các email vừa chọn?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Có",
      cancelButtonText: "Không",
    }).then((res) => {
      if (res.value) {
        callAjaxLoading(
          {
            ids: ids,
            action: "ajax_add-email-client-cart",
            security: $("#index-nonce").val(),
          },
          (res) => {
            if (res.success) {
              $(".top-navbar-cart").trigger("update-cart");
              Swal.fire({
                title: "Thêm vào giỏ hàng thành công",
                text: "Bạn muốn mở giỏ hàng?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Có",
                cancelButtonText: "Không",
              }).then((res2) => {
                if (res2.value) {
                  window.location.href = res.data.cart_url;
                }
              });
            } else {
              showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
            }
          },
          (msg) => {
            showErrorMessage(msg);
          }
        );
      }
    });
  };
  base.column = [
    { data: "status", title: "Trạng thái" },
    { data: "email-type_label", title: "Gói" },
    { data: "price", title: "Giá" },
    { data: "url_admin", title: "URL Admin" },
    { data: "url_client", title: "URL Client" },
    { data: "user", title: "Tài khoản" },
    { data: "pass", title: "Mật khẩu" },
    { data: "action", title: "Hành động" },
  ];
  base.columnDefs = [
    {
      targets: 2,
      render: numberFormatterAmountVND,
    },
    {
      targets: 7,
      render: actionEmailFormatter,
      responsivePriority: 1,
    },
  ];
  let i = 1;
  base.addEvent = () => {
    $('#btn-confirm-change-email-plan').on('click', function () {
      var modal = $('#modal-change-email-plan');
      var service_id = $('#change-email-plan-customer-email-id').val();
      var new_plan_id = $('#new-email-plan').val();

      if (!new_plan_id) {
        showErrorMessage('Vui lòng chọn một gói email mới.', 'Lỗi');
        return;
      }

      Swal.fire({
        title: 'Xác nhận đổi gói Email?',
        text: 'Bạn có chắc chắn muốn đổi gói email này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Đổi gói',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          callAjaxLoading(
            {
              action: 'ajax_client_add_email_change_to_cart',
              security: $('#ajax-client-email-nonce').val(),
              customer_email_id: service_id,
              new_plan_id: new_plan_id
            },
            (res) => {
              modal.modal('hide');
              if (res.success) {
                if (res.data.title && res.data.title === 'Liên hệ') {
                  Swal.fire({
                    title: res.data.title,
                    text: res.data.msg,
                    icon: 'info',
                    confirmButtonText: 'Đóng'
                  });
                } else {
                  Swal.fire({
                    title: 'Thành công!',
                    text: res.data.msg,
                    icon: 'success',
                    confirmButtonText: 'Đến giỏ hàng'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = res.data.cart_url;
                    }
                  });
                }
              } else {
                showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
              }
            },
            (msg) => {
              modal.modal('hide');
              showErrorMessage(msg);
            }
          );
        }
      });
    });

    $(".btn-reload").on("click", (e) => {
      e.preventDefault();

      data = {
        from_date: $("#from-date", base.context).val(),
        until_date: $("#until-date", base.context).val(),
        from_expiry_date: $("#from-expiry-date", base.context).val(),
        until_expiry_date: $("#until-expiry-date", base.context).val(),
        domain_status: $("#domain-status", base.context).val(),
      };
      base.ajaxData = {
        ...base.ajaxData,
        ...data,
      };
    });

    base.table.on("click", ".btn-change-email-plan", function (e) {
      e.preventDefault();
      var customer_email_id = $(this).data("id");
      var inet_email_id = $(this).data("inet-id");

      $('#modal-change-email-plan').modal('show');
      $('#change-email-plan-customer-email-id').val(customer_email_id);
      $('#change-email-plan-inet-email-id').val(inet_email_id);

      // Fetch current email plan details
      callAjax(
        {
          action: 'ajax_get_email_detail_inet',
          security: $('#ajax-client-email-nonce').val(), // Correct nonce for client
          inet_email_id: inet_email_id,
          customer_email_id: customer_email_id,
        },
        (res) => {
          if (res.success) {
            var data = res.data;
            $('#current-plan-name').text(data.plan);
            $('#current-plan-domain').text(data.domain);
            $('#current-plan-expiry-date').text(data.expiry_date);

            // Calculate remaining days
            var expiryDate = moment(data.expiry_date, "DD/MM/YYYY");
            var today = moment();
            var remainingDays = expiryDate.diff(today, 'days');
            $('#current-plan-remaining-days').text(remainingDays > 0 ? remainingDays : 0);

          } else {
            showErrorMessage(res.data.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });

    function ajax_get_email_records_inet(inet_email_id, customer_email_id) {
      var tbody = $('#tb-email-records').find('tbody');
      tbody.html('<tr><td colspan="4" class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></td></tr>');
      $('#btn-step2-next').prop('disabled', true);

      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_client_get_email_records_inet',
          inet_email_id: inet_email_id,
          customer_email_id: customer_email_id, // Pass customer_email_id
          security: $('#nonce').val()
        },
        dataType: 'json',
        success: function (res) {
          tbody.empty();
          if (res.success) {
            res.data.records.forEach(function (record) {
              var status_html = record.verified
                ? '<span class="text-success">Đã xác thực</span>'
                : '<span class="text-danger">Chưa xác thực</span>';
              tbody.append(`<tr><td>${record.type}</td><td>${record.name}</td><td>${record.value}</td><td>${status_html}</td></tr>`);
            });

            if (res.data.all_verified) {
              $('#btn-step2-next').prop('disabled', false);
            } else {
              $('#btn-step2-next').prop('disabled', true);
            }

            // Dynamic text for btn-check-records-top
            if (res.data.is_verified) {
              $('#btn-check-records-top').text('Kiểm tra lại bản ghi');
            } else {
              $('#btn-check-records-top').text('Kiểm tra bản ghi');
            }

          } else {
            tbody.html('<tr><td colspan="4" class="text-center text-danger">' + res.data.msg + '</td></tr>');
          }
        },
        error: function () {
          tbody.html('<tr><td colspan="4" class="text-center text-danger">Lỗi khi tải bản ghi DNS.</td></td></tr>');
        }
      });
    }

    base.table.on("click", ".btn-register-email-inet", function (e) {
      e.preventDefault();
      var customer_email_id = $(this).data("id");

      $('#modal-configure-email').modal('show');
      $('#register-email-customer-email-id').val(customer_email_id);
      $('#register-email-inet-email-id').val('');

      $('#form-step1-register-email')[0].reset();
      $('#domain-check-message').empty();
      $('#btn-activate-domain').prop('disabled', true);
      $('#tb-email-records tbody').empty();

      $('#register-email-tabs a[href="#step1-domain"]').removeClass('disabled').tab('show');
      $('#register-email-tabs a[href="#step2-records"]').addClass('disabled');
      $('#register-email-tabs a[href="#step3-finish"]').addClass('disabled');
    });


    // Add click handler for the Step 2 tab to refresh records
    $('#register-email-tabs a[href="#step2-records"]').on('click', function (e) {
      e.preventDefault();
      if (!$(this).hasClass('disabled')) {
        var inet_email_id = $('#register-email-inet-email-id').val();
        var customer_email_id = $('#register-email-customer-email-id').val();
        if (inet_email_id && customer_email_id) {
          ajax_get_email_records_inet(inet_email_id, customer_email_id);
        }
        $(this).tab('show');
      }
    });

    base.table.on('click', '.btn-configure-email-inet', function (e) {
      e.preventDefault();
      var inet_email_id = $(this).data('inet-id');
      var customer_email_id = $(this).data('id');

      $('#modal-configure-email').modal('show');

      $('#register-email-inet-email-id').val(inet_email_id);
      $('#register-email-customer-email-id').val(customer_email_id);

      // Clear previous state
      $('#form-step1-register-email')[0].reset();
      $('#domain-check-message').empty();
      $('#tb-email-records tbody').empty();
      $('#admin-password-section').hide();

      showLoading($.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_client_get_email_detail_inet',
          inet_email_id: inet_email_id,
          customer_email_id: customer_email_id,
          security: $('#nonce').val()
        },
        dataType: 'json',
        success: function (res) {
          if (res.success) {
            if (res.data.is_verified) {
              // Already verified, jump to Step 3
              var package_info = $('#email-package-info');
              var admin_info = $('#email-admin-info');
              var data = res.data;

              $('.quota', package_info).text(data.quota);
              $('.accounts', package_info).text(data.accounts);
              $('.groups', package_info).text(data.groups);
              $('.status', package_info).text(data.status);
              $('.expiry-date', package_info).text(data.expiry_date);
              $('.plan-name', package_info).text(data.plan);
              $('.domain', package_info).text(data.domain);
              $('.created-date', package_info).text(data.created_date);

              $('.admin-url', admin_info).attr('href', data.admin_url).text(data.admin_url);
              $('.admin-email', admin_info).text(data.admin_email);
              $('.client-url', admin_info).attr('href', data.client_url).text(data.client_url);

              $('#register-email-tabs a[href="#step3-finish"]').removeClass('disabled').tab('show');
              $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled');
              $('#register-email-tabs a[href="#step1-domain"]').addClass('disabled');
            } else {
              // Not verified, go to Step 2
              $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled').tab('show');
              $('#register-email-tabs a[href="#step1-domain"]').addClass('disabled');
              $('#register-email-tabs a[href="#step3-finish"]').addClass('disabled');
              ajax_get_email_records_inet(inet_email_id, customer_email_id);
            }
          } else {
            showErrorMessage(res.data.msg || 'Lỗi khi lấy chi tiết email.');
            $('#modal-configure-email').modal('hide');
          }
        },
        error: function () {
          showErrorMessage('Lỗi khi tải dữ liệu. Vui lòng thử lại.');
          $('#modal-configure-email').modal('hide');
        },
        complete: function () {
          hideLoading();
        }
      }));
    });

    base.table.on("click", ".btn-gen-dkim-email-inet", function (e) {
      e.preventDefault();
      var inet_email_id = $(this).data("inet-id");
      var customer_email_id = $(this).data("id");
      Swal.fire({
        title: 'Bạn có chắc chắn muốn tạo DKIM cho gói email này?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Tạo DKIM',
        cancelButtonText: 'Hủy'
      }).then((result) => {
        if (result.value) {
          callAjaxLoading(
            {
              action: "ajax_gen_dkim_email_inet",
              security: $("#nonce").val(),
              inet_email_id: inet_email_id,
              customer_email_id: customer_email_id,
            },
            (res) => {
              if (res.success) {
                showSuccessMessage(() => {
                  base.loadData();
                }, res.data.msg);
              } else {
                showErrorMessage(res.data.msg);
              }
            },
            (msg) => {
              showErrorMessage(msg);
            }
          );
        }
      });
    });

    $('#btn-check-domain-step1').on('click', function () {
      var domain_name = $('#register-email-domain').val();
      var customer_email_id = $('#register-email-customer-email-id').val();

      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_client_check_email_domain_available_inet',
          domain: domain_name,
          customer_email_id: customer_email_id,
          security: $('#nonce').val()
        },
        dataType: 'json',
        beforeSend: function () { showLoading(); },
        success: function (res) {
          var messageDiv = $('#domain-check-message');
          if (res.success) {
            messageDiv.html('<span class="text-success">Tên miền có thể sử dụng.</span>');
            $('#btn-activate-domain').prop('disabled', false);
          } else {
            messageDiv.html(`<span class="text-danger">${res.data.msg}</span>`);
            $('#btn-activate-domain').prop('disabled', true);
          }
        },
        complete: function () { hideLoading(); }
      });
    });

    $('#btn-activate-domain').on('click', function () {
      var domain_name = $('#register-email-domain').val();
      var customer_email_id = $('#register-email-customer-email-id').val();

      $.ajax({
        url: objAdmin.ajax_url,
        type: 'POST',
        data: {
          action: 'ajax_client_create_email_package_inet',
          domain: domain_name,
          customer_email_id: customer_email_id,
          security: $('#nonce').val()
        },
        dataType: 'json',
        beforeSend: function () { showLoading(); },
        success: function (res) {
          if (res.success) {
            $('#register-email-inet-email-id').val(res.data.id);
            $('#register-email-tabs a[href="#step2-records"]').removeClass('disabled').tab('show');
            ajax_get_email_records_inet(res.data.id, customer_email_id);
          } else {
            $('#domain-check-message').html(`<span class="text-danger">${res.data.msg}</span>`);
          }
        },
        error: function (jqXHR) {
          showErrorMessage(jqXHR.responseJSON?.data?.msg || 'Lỗi khi tạo gói email.');
        },
        complete: function () { hideLoading(); }
      });
    });

    $('#btn-check-records-top').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      if (inet_email_id) {
        var customer_email_id = $('#register-email-customer-email-id').val(); // Get customer_email_id
        ajax_get_email_records_inet(inet_email_id, customer_email_id);
      } else {
        showErrorMessage('Không tìm thấy ID gói email để tải lại bản ghi.');
      }
    });

    $('#btn-gen-dkim-modal').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      var customer_email_id = $('#register-email-customer-email-id').val();
      if (inet_email_id) {
        Swal.fire({
          title: 'Bạn có chắc chắn muốn tạo DKIM cho gói email này?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Tạo DKIM',
          cancelButtonText: 'Hủy'
        }).then((result) => {
          if (result.value) {
            callAjaxLoading(
              {
                action: "ajax_gen_dkim_email_inet",
                security: $("#nonce").val(),
                inet_email_id: inet_email_id,
                customer_email_id: customer_email_id,
              },
              (res) => {
                if (res.success) {
                  showSuccessMessage(() => {
                    ajax_get_email_records_inet(inet_email_id, customer_email_id);
                  }, res.data.msg);
                } else {
                  showErrorMessage(res.data.msg);
                }
              },
              (msg) => {
                showErrorMessage(msg);
              }
            );
          }
        });
      } else {
        showErrorMessage('Không tìm thấy ID gói email để tạo DKIM.');
      }
    });

    $('#btn-step2-next').on('click', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      var customer_email_id = $('#register-email-customer-email-id').val();
      if (inet_email_id) {
        $.ajax({
          url: objAdmin.ajax_url,
          type: 'POST',
          data: {
            action: 'ajax_client_get_email_detail_inet',
            inet_email_id: inet_email_id,
            customer_email_id: customer_email_id,
            security: $('#nonce').val()
          },
          dataType: 'json',
          beforeSend: function () { showLoading(); },
          success: function (clean_data) {
            var package_info = $('#email-package-info');
            var admin_info = $('#email-admin-info');
            var data = clean_data.data;

            $('.plan-name', package_info).text(data.plan);
            $('.domain', package_info).text(data.domain);
            $('.quota', package_info).text(data.quota);
            $('.accounts', package_info).text(data.accounts);
            $('.groups', package_info).text(data.groups);
            $('.status', package_info).text(data.status);
            $('.created-date', package_info).text(data.created_date);
            $('.expiry-date', package_info).text(data.expiry_date);

            $('.admin-url', admin_info).attr('href', data.admin_url).text(data.admin_url);
            $('.admin-email', admin_info).text(data.admin_email);
            $('.client-url', admin_info).attr('href', data.client_url).text(data.client_url);

            $('#register-email-tabs a[href="#step3-finish"]').removeClass('disabled').tab('show');
            base.loadData();
          },
          error: function (jqXHR) {
            var errorMsg = "Lỗi không xác định.";
            if (jqXHR.responseJSON && jqXHR.responseJSON.data && jqXHR.responseJSON.data.msg) {
              errorMsg = jqXHR.responseJSON.data.msg;
            }
            showErrorMessage(errorMsg);
          },
          complete: function () { hideLoading(); }
        });
      }
    });

    // Handler for generating password
    $('#modal-configure-email').on('click', '.btn-generate-email-password', function () {
      var inet_email_id = $('#register-email-inet-email-id').val();
      if (!inet_email_id) {
        showErrorMessage('Không tìm thấy ID email của iNET.');
        return;
      }

      callAjaxLoading(
        {
          action: 'ajax_client_reset_email_password_inet',
          security: $('#nonce').val(),
          inet_email_id: inet_email_id,
        },
        (res) => {
          if (res.success) {
            $('#email-admin-info .admin-password-display').val(res.data.newPassword);
            $('#email-admin-info .btn-toggle-password-visibility i').removeClass('fa-eye').addClass('fa-eye-slash');
            $('#admin-password-section').css('display', 'block');

          } else {
            showErrorMessage(res.msg, "Có lỗi xảy ra!");
          }
        },
        (msg) => {
          showErrorMessage(msg);
        }
      );
    });

    // Handler for toggling password visibility
    $('#modal-configure-email').on('click', '.btn-toggle-password-visibility', function () {
      var passwordField = $(this).closest('.input-group').find('.admin-password-display');
      var icon = $(this).find('i');

      if (passwordField.attr('type') === 'password') {
        passwordField.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        passwordField.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });

    // Handler for copying password
    $('#modal-configure-email').on('click', '.btn-copy-password', function () {
      var passwordField = $(this).closest('.input-group').find('.admin-password-display');
      var password = passwordField.val();

      if (password) {
        navigator.clipboard.writeText(password).then(() => {
          var copyButton = $(this);
          var originalIcon = copyButton.html();
          copyButton.html('<i class="fa fa-check"></i>');
          setTimeout(function () {
            copyButton.html(originalIcon);
          }, 2000);
        }).catch(err => {
          showErrorMessage('Không thể sao chép mật khẩu.');
        });
      }
    });

  };
  base.order = [[0, "asc"]];

  self.checkToggle = (e) => {
    base.checkToggle(e);
  };
  self.initialize = () => {
    base.initialize("email");
    initSelect2DomainStatus("domain-status", base.context);
    initdatepickerlink("from-date", "until-date", base.context);
    initdatepickerlink("from-expiry-date", "until-expiry-date", base.context);
    initSelect2Email("new-email-plan", '#modal-change-email-plan');
  };

  return self;
})({}, baseReport({}));