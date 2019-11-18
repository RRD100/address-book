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
});