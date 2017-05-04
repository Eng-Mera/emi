var HTR = {};

HTR.init = function () {

    $.views.settings.delimiters("@%", "%@");
    $.fn.dataTable.ext.errMode = 'none';

    //User Management
    HTR.userTable('#users-datatable', '#user-action-template');
    HTR.roleTable('#role-datatable', '#role-action-template');
    HTR.permissionTable('#permission-datatable', '#permission-action-template');

    //Restaurant Management
    HTR.restaurantTable('#restaurant-datatable', '#restaurant-action-template');

    HTR.menuItemTable('#menuitem-datatable', '#menuitem-action-template');

    HTR.reservationPolicy('#reservation-policy-datatable', '#reservation-policy-action-template');

    HTR.openingDaysTable('#opening-days-datatable', '#opening-days-action-template');

    HTR.rateReview('#rate-review-datatable', '#rate-review-action-template');

    HTR.reportTable('#report-datatable', '#report-action-template');
    HTR.claimTable('#claim-datatable', '#claim-action-template');

    HTR.branchTable('#branch-datatable', '#branch-action-template');
    HTR.facilityTable('#facility-datatable', '#facility-action-template');

    //Category
    HTR.category('#category-datatable', '#category-action-template');

    //Movies
    HTR.movie('#movie-datatable', '#movie-action-template');

    //Movies
    HTR.adminReviews('#admin-review-datatable', '#admin-review-action-template');

    //City
    HTR.city('#city-datatable', '#city-action-template');

    //Reservation
    HTR.reservation('#reservation-datatable', '#reservation-action-template');

    //Job Title
    HTR.jobTitle('#job-title-datatable', '#job-title-action-template');

    //Job Vcancies
    HTR.jobVacancy('#job-vacancy-datatable', '#job-vacancy-action-template');

    HTR.dishCategory('#dishcategory-datatable', '#dishcategory-action-template');

    HTR.jobApplier('#job-applier-datatable', 'job-applier-action-template');

    HTR.handleUploadedImage();

    HTR.multiSelect();

    HTR.resumable();

    HTR.removeImage();

    HTR.tabsPrevNext('#create-restaurant-tabs', '#create-container', '#create-content');

    HTR.showReservationField();

    HTR.fixDuplicates();

    HTR.handleTabContentErrors();

    HTR.fontAwesome();

};

HTR.fixDuplicates = function () {


    var dtable = $('.dataTable').dataTable().api();

    $(".dataTables_filter input")
        .unbind() // Unbind previous default bindings
        .bind("input", function (e) { // Bind our desired behavior

            // If the length is 3 or more characters, or the user pressed ENTER, search

            if (this.value.length >= 3) {
                // Call the API search function
                dtable.search(this.value).draw();
            }

            // Ensure we clear the search if they backspace far enough
            if (this.value == "") {
                dtable.search("").draw();
            }

            return;
        });

    $(".dataTables_filter input").on('keyup', function (e) {
        if (e.keyCode == 13) {
            console.log(dtable.search(this.value).draw());
        }
    });
};

HTR.showReservationField = function () {

    if (!$('#temp-price-field').length || !$('#reservable_online').length) {
        return false;
    }

    $('#reservable_online').change(function () {

        $('#temp-price-field').toggleClass('hide');
    });

};

HTR.handleTabContentErrors = function () {

    if (!$('.alert-danger').length) return false;

    $('.alert-danger').each(function (i, e) {

        var id = $(this).parents('.tab-pane').attr('id');

        if (!id) return false;

        id = 'a[href=#' + id + ']';

        var content = '<i class="fa fa-warning" aria-hidden"true"=""></i> ' + $(id).text();

        $(id).css('color', '#dd4b39');

        $(id).html(content);

        if (i == 0) {
            $(id).tab('show');
            $('html, body').animate({
                scrollTop: $(this).offset().top
            }, 2000);
        }
    });
};

HTR.fontAwesome = function () {
    if ($('.icp-dd').length) {

        $('.icp-dd').iconpicker({
            placement: 'topLeft',
            selected: 'test'
        });

        $('.icp-dd').on('iconpickerSelected', function (e) {
            $('.select-icon').text('fa ' + e.iconpickerValue);
            $('.facility-icon-hidden').val('fa ' + e.iconpickerValue);
            $('.facility-icon').attr('class', 'facility-icon fa ' + e.iconpickerValue);
        });
    }
};

HTR.tabsPrevNext = function (allContainer, tabContainer, contentContainer) {

    $('.btnNext').click(function () {
        $('.nav-tabs > .active').next('li').find('a').trigger('click');
    });

    $('.btnPrevious').click(function () {
        $('.nav-tabs > .active').prev('li').find('a').trigger('click');
    });

};

HTR.multiSelect = function () {

    $('select[multiple=multiple]').length && $('select[multiple=multiple]').each(function () {

        $(this).multiSelect({
            selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search ...'>",
            selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search ...'>",
            afterInit: function (ms) {
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    });

    $('body').on('click', '.delete-action', function (e) {

        if (confirm('Are you sure ?')) {
            e.preventDefault();
            $(this).parent().next().submit();
        }

        return false;
    });

    $('body').on('click', '.accept-reservation', function (e) {

        if (confirm('Are you sure to Accept Reservation ?')) {
            e.preventDefault();
            $(this).parent().next().submit();
        }

        return false;
    });

    $('body').on('click', '.arrived-reservation', function (e) {

        if (confirm('Are you sure Client has been arrived ?')) {
            e.preventDefault();
            $(this).parent().next().submit();
        }

        return false;
    });


    $('body').on('click', '.reject-reservation', function (e) {

        if (confirm('Are you sure Reject Reservation ?')) {
            e.preventDefault();
            $(this).parent().next().submit();
        }

        return false;
    });


};

HTR.userTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": {
            "url": "/admin/user/",
            // "type": "POST",
            "data": function (d) {
                d.role_filter = $("#role-filter").val();

                return d;
            }
        },
        "processing": true,
        "serverSide": true,
        'lengthChange': false,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {

                    rolesTxt = '';

                    if (data.roles) {
                        rolesTxt = '<br/>';
                        for (var role in data.roles) {
                            rolesTxt += '<span class="label label-success" style="background-color: ' + data.roles[role].color + ' !important;">' + data.roles[role].display_name + '</span> ';
                        }
                    }

                    return '<a href="/admin/user/' + data.username + '/edit">' + data.name + '</a>' + rolesTxt;
                }
            },
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'dob', name: 'dob'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return actionTemplate.render(data);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });

    $('#role-filter').change(function () {
        table.ajax.reload();
    });

};

HTR.restaurantTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var restaurantTemplate = $.templates('#restaurant-grid-details');

    var table = $(listId).DataTable({

        "ajax": {
            "url": "/admin/restaurant/"
        },
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return restaurantTemplate.render(data);
                }
            },
            {data: 'slug', name: 'slug'},
            {data: 'email', name: 'email'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    // console.log(data.owner);
                    if (!(data.owner == null)) {
                        return '<a href="/admin/user/' + data.owner.username + '/edit">' + data.owner.name + '</a>';
                    }
                    else {
                        return 'Not Set';
                    }
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return actionTemplate.render(data);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });

};

HTR.reportTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": {
            "url": "/admin/report/",
            "data": function (d) {
                d.type_filter = $("#type-filter").val();
                return d;
            }
        },
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'report_type', name: 'report_type'},
            {data: 'report_subject', name: 'report_subject'},
            {data: 'reported_id', name: 'reported_id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/user/' + data.user.username + '/edit">' + data.user.name + '</a>';
                }
            },
            {data: 'details', name: 'details'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return actionTemplate.render(data);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
    $('#type-filter').change(function () {
        table.ajax.reload();
    });

};

HTR.roleTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": {
            "url": "/admin/role/",
        },
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/role/' + data.name + '/edit">' + data.display_name + '</a>';
                }
            },
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return actionTemplate.render(data);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });

};

HTR.menuItemTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var table = $(listId).DataTable({
        "ajax": '/admin/restaurant/' + slug + '/menu-item',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '/menu-item/' + data.slug + '/edit">' + data.name + '</a>';
                }
            },

            {data: 'slug', name: 'slug'},
            {data: 'price', name: 'price'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {

                    return data.popular_dish ? 'Yes' : 'No';
                }
            },
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'slug': data.slug,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.reservationPolicy = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var table = $(listId).DataTable({
        "ajax": '/admin/restaurant/' + slug + '/reservation-policy',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '/reservation-policy/' + data.id + '/edit">' + data.name + '</a>';
                }
            },
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return data.status ? 'Enabled' : 'Disabled';
                }
            },
            {data: 'amount', name: 'amount'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.facilityTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/facility/',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'icon', name: 'icon'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.branchTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');
    // console.log(slug);
    var table = $(listId).DataTable({
        "ajax": '/admin/restaurant/' + slug + '/branch',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '">' + slug + '</a>';
                }
            },
            {data: 'address', name: 'address'},
            {data: 'slug', name: 'slug'},
            {data: 'latitude', name: 'latitude'},
            {data: 'longitude', name: 'longitude'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'slug': data.slug,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.claimTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": {
            "url": "/admin/claim/",
        },
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/user/' + data.user.username + '/edit">' + data.user.name + '</a>';
                }
            },
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.openingDaysTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var table = $(listId).DataTable({

        "ajax": '/admin/restaurant/' + slug + '/opening-days',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '/opening-days/' + data.id + '/edit">' + data.day_name + '</a>';
                }
            },
            {data: 'from', name: 'from'},
            {data: 'to', name: 'to'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });


};

HTR.rateReview = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var table = $(listId).DataTable({

        "ajax": '/admin/restaurant/' + slug + '/rates',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '/rates/' + data.id + '">' + data.title + '</a>';
                }
            },
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return data.description;
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });


};

HTR.permissionTable = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/permission',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/permission/' + data.name + '/edit">' + data.display_name + '</a>';
                }
            },
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return actionTemplate.render(data);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });

};

HTR.resumable = function () {

    if (!$('#restaurant-slug').length) {
        return;
    }

    var restaurant_slug = $('#restaurant-slug').data('slug');

    var r = new Resumable({
        target: '/gallery-uploader',
        testChunks: true,
        query: {
            'restaurant_slug': restaurant_slug
        }
    });

    r.assignBrowse(document.getElementById('add-file-btn'));

    $('#start-upload-btn').click(function () {
        r.upload();
    });

    $('#pause-upload-btn').click(function () {
        if (r.files.length > 0) {
            if (r.isUploading()) {
                return r.pause();
            }
            return r.upload();
        }
    });

    var progressBar = new ProgressBar($('#upload-progress'));

    r.on('fileAdded', function (file, event) {
        progressBar.fileAdded();
    });

    r.on('fileSuccess', function (file, message) {
        progressBar.finish();
    });

    r.on('progress', function () {
        progressBar.uploading(r.progress() * 100);
        $('#pause-upload-btn').find('.glyphicon').removeClass('glyphicon-play').addClass('glyphicon-pause');
    });

    r.on('pause', function () {
        $('#pause-upload-btn').find('.glyphicon').removeClass('glyphicon-pause').addClass('glyphicon-play');
    });

    function ProgressBar(ele) {
        this.thisEle = $(ele);

        this.fileAdded = function () {
            (this.thisEle).removeClass('hide').find('.progress-bar').css('width', '0%');
        },

            this.uploading = function (progress) {
                (this.thisEle).find('.progress-bar').attr('style', "width:" + progress + '%');
            },

            this.finish = function () {
                (this.thisEle).addClass('hide').find('.progress-bar').css('width', '0%');
            }
    }
};

HTR.handleUploadedImage = function () {

    $('input[type=file]').on('change', function () {

        var elem = this;
        var i = 0;

        for (i; i < this.files.length; i++) {

            var theFile = this.files[i];

            var FR = new FileReader();

            FR.onload = function (e) {

                var img = '<img src="' + e.target.result + '" alt="" class="img-circle" width="100"/>';
                var newHidden = $(elem).parent().find('[data-uploaded-field=1]:first').clone();

                $(newHidden).val(e.target.result).removeAttr('disabled');

                $(elem).parents('.form-group').find('br:first').after(img);
                $(elem).parents('.form-group').find('br:first').after(newHidden);
            };

            FR.readAsDataURL(theFile);
        }
    });
};

HTR.removeImage = function () {

    $('.delete-image').click(function (e) {

        if (!confirm('Are you sure?')) {
            return
        }

        e.preventDefault();

        var inputName = $(this).data('input-name');

        var id = $(this).data('id');

        if ($(inputName).length && id) {

            var val = $(inputName).val();

            if (val) {
                val += ',' + id;
            } else {
                val = id;
            }

            $(inputName).val(val);
        }

        $(this).parents('.img-container').remove();
    });

};

HTR.category = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/category',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/category/' + data.id + '/edit">' + data.category_name + '</a>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.movie = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/movie',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/movie/' + data.id + '/edit">' + data.name + '</a>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.adminReviews = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);
    var table = $(listId).DataTable({
        "ajax": '/admin/admin-review',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/admin-review/' + data.id + '/edit">' + data.restaurant_name + '</a>';
                }
            },
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.city = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/city',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/city/' + data.id + '/edit">' + data.city_name + '</a>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.reservation = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/reservation',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + data.restaurant.slug + '/edit">' + data.restaurant.name + '</a>';
                }
            },
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    if (!(data.user == null)) {
                        return '<a href="/admin/user/' + data.user.username + '/edit">' + data.user.name + '</a>';
                    }
                    else {
                        return 'Not Set';
                    }
                }
            },
            {data: 'status', name: 'status'},
            {data: 'number_of_people', name: 'number_of_people'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {

                    return data.date + ' ' + data.time;
                }
            },
            {data: 'total', name: 'total'},
            {data: 'note', name: 'note'},

            {data: 'created_at', name: 'created_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                        'user': data.user_id,
                        'restaurant': data.restaurant_id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.jobTitle = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/job-title',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/job-title/' + data.id + '/edit">' + data.job_title + '</a>';
                }
            },
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.jobApplier = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    // var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var jobId = $(listId).data('job-id');

    var table = $(listId).DataTable({
        "ajax": '/admin/restaurant/' + slug + '/applied-users/' + jobId,
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/user/' + data.username + '">' + data.name + '</a>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'}
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.jobVacancy = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var slug = $(listId).data('slug');

    var table = $(listId).DataTable({
        "ajax": '/admin/restaurant/' + slug + '/job-vacancy',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/restaurant/' + slug + '/job-vacancy/' + data.id + '/edit">' + data.job_title.job_title + '</a>';
                }
            },
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return data.status == 1 ? 'Enabled' : 'Disabled';
                }
            },
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id,
                        'restaurant_slug': slug
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

HTR.dishCategory = function (listId, gridId) {

    if (!$(listId).length) {
        return false;
    }

    var actionTemplate = $.templates(gridId);

    var table = $(listId).DataTable({
        "ajax": '/admin/dish-category',
        "processing": true,
        "serverSide": true,
        "order": [[0, "desc"]],
        columns: [
            {data: 'id', name: 'id'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    return '<a href="/admin/dish-category/' + data.id + '/edit">' + data.category_name + '</a>';
                }
            },
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
                data: null,
                defaultContent: '',
                render: function (data, type, full, meta) {
                    var d = {
                        'id': data.id
                    };
                    return actionTemplate.render(d);
                }
            }
        ]
    }).on('draw', function () {
        var tableBody = $(table.table().body());
        tableBody.unhighlight();
        tableBody.highlight(table.search());
    });
};

$(document).ready(function () {
    HTR.init();
});