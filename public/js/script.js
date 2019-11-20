$(document).ready(function() {
    // store tabs variables
    var tabs = document.querySelectorAll('ul.nav-tabs > li');

    for (var i = 0; i < tabs.length; i++) {
        tabs[i].addEventListener('click', switchTab);
    }

    function switchTab(event) {
        event.preventDefault();

        document.querySelector('ul.nav-tabs li.active').classList.remove('active');
        document.querySelector('.tab-pane.active').classList.remove('active');

        var clickedTab = event.currentTarget;
        var anchor = event.target;
        var activePaneID = anchor.getAttribute('href');

        clickedTab.classList.add('active');
        document.querySelector(activePaneID).classList.add('active');
    }

    function fetch_data_users(page = 1) {
        $.ajax({
            url: "/fetch_data_users?page=" + page,
            success: function(data) {
                $('.results').html(data);
            }
        });
    }

    function search_data(page = 1, user_search = '') {
        window.global_user_search = user_search;

        $.ajax({
            url: "/search_data?page=" + page + "&user_search=" + user_search,
            success: function(data) {
                $('.results').html(data);
            }
        });
    }

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetch_data_users(page);
    });

    $('#search').click(function() {
        var user_search = $('#user_search').val();

        if (user_search != '') {
            search_data(1, user_search);

            if (hasTabs == 1) {
                document.querySelector('ul.nav-tabs li.active').classList.remove('active');
                document.querySelector('.tab-pane.active').classList.remove('active');

                $('#tab1').addClass('active');
                $('#tab-1').addClass('active');
            }
        } else {
            alert('Search Field can\'t be empty!');
        }
    });

    $('input#user_search').keyup(function(event) {
        var hasTabs = $(this).attr('hasTabs');

        if (hasTabs == 1) {
            document.querySelector('ul.nav-tabs li.active').classList.remove('active');
            document.querySelector('.tab-pane.active').classList.remove('active');

            $('#tab1').addClass('active');
            $('#tab-1').addClass('active');
        }

        search_data(1, $(this).val());
    });

    $('#refresh').click(function() {
        $('#user_search').val('');
        search_data(1);
    });

    var wrapper_emails = $(".alternative_emails"); //Input fields wrapper for emails
    var add_button_emails = $(".add_fields_emails"); //Add button class or ID for emails
    var add_button_emails_edit = $(".add_fields_emails_edit"); //Add button class or ID for emails on Edit view
    var wrapper_phones = $(".alternative_phones"); //Input fields wrapper for phones
    var add_button_phones = $(".add_fields_phones"); //Add button class or ID for phones
    var add_button_phones_edit = $(".add_fields_phones_edit"); //Add button class or ID for phones on Edit view

    //When user click on add for emails
    $(add_button_emails).click(function(e) {
        e.preventDefault();

        //add input field
        $(wrapper_emails).append('<div class="form-group row"><label for="alternative_email" class="col-md-4 col-form-label text-md-right">Alternative E-Mail Address</label><div class="col-md-6"><input id="alternative_email" type="phone" class="form-control" name="contacts[alternative_email][]" value=""></div><a href="javascript:void(0);" class="remove_field btn btn-primary">Remove</a></div>');
    });

    //When user click on add input for phones
    $(add_button_phones).click(function(e) {
        e.preventDefault();

        //add input field
        $(wrapper_phones).append('<div class="form-group row"><label for="alternative_phone" class="col-md-4 col-form-label text-md-right">Alternative Phone Number</label><div class="col-md-6"><input id="alternative_phone" type="phone" class="form-control" name="contacts[alternative_phone][]" value=""></div><a href="javascript:void(0);" class="remove_field btn btn-primary">Remove</a></div>');
    });

    //when user click on remove button
    $(wrapper_emails).on("click", ".remove_field", function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //remove input field
    });

    //when user click on remove button
    $(wrapper_phones).on("click", ".remove_field", function(e) {
        e.preventDefault();
        $(this).parent('div').remove(); //remove input field
    });

    //When user click on add input for emails
    $(add_button_emails_edit).click(function(e) {
        e.preventDefault();

        //add input field
        $('.' + $(this).attr('parentID')).append('<div><input id="new" type="email" class="form-control ' + $(this).attr('classID') + '" name="contacts[alternative_emails][]"><a href="javascript:void(0);" class="remove_field btn btn-primary">Remove</a><br><br></div>').on("click", ".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //remove input field
        });
    });

    //When user click on add input for phones
    $(add_button_phones_edit).click(function(e) {
        e.preventDefault();

        //add input field
        $('.' + $(this).attr('parentID')).append('<div><input id="new" type="phone" class="form-control ' + $(this).attr('classID') + '" name="contacts[alternative_phone][]"><a href="javascript:void(0);" class="remove_field btn btn-primary">Remove</a><br><br></div>').on("click", ".remove_field", function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //remove input field
        });
    });
});