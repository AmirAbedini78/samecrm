// Language Switcher JavaScript
$(document).ready(function() {
    // Handle language change
    $('.change_lang').click(function(e) {
        e.preventDefault();
        var lang = $(this).attr('value');
        if (lang) {
            // Store language in session
            $.ajax({
                url: '/change-language/' + lang,
                type: 'GET',
                success: function(response) {
                    // Reload the page to apply the new language
                    window.location.reload();
                },
                error: function() {
                    // Fallback: reload with language parameter
                    window.location.href = window.location.pathname + '?lang=' + lang;
                }
            });
        }
    });
    
    // Handle language change for login page
    if (window.location.pathname === '/login' || window.location.pathname === '/register') {
        $('.change_lang').click(function(e) {
            e.preventDefault();
            var lang = $(this).attr('value');
            if (lang) {
                window.location.href = window.location.pathname + '?lang=' + lang;
            }
        });
    }
});
