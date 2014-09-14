(function ($) {
/* USAGE: 
 * $('MyTabSelector').disableTab(0);        // Disables the first tab
 * $('MyTabSelector').disableTab(1, true);  // Disables & hides the second tab
 * $('MyTabSelector').enableTab(1);         // Enables & shows the second tab
 * 
 * For the hide option to work, you need to define the following css
 *   li.ui-state-default.ui-state-hidden[role=tab]:not(.ui-tabs-active) {
 *     display: none;
 *   }
 */
    $.fn.disableTab = function (tabIndex, hide) {
 
        // Get the array of disabled tabs, if any
        var disabledTabs = this.tabs("option", "disabled");
 
        if ($.isArray(disabledTabs)) {
            var pos = $.inArray(tabIndex, disabledTabs);
 
            if (pos < 0) {
                disabledTabs.push(tabIndex);
            }
        }
        else {
            disabledTabs = [tabIndex];
        }
 
        this.tabs("option", "disabled", disabledTabs);
 
        if (hide === true) {
            $(this).find('li:eq(' + tabIndex + ')').addClass('ui-state-hidden');
        }
 
        // Enable chaining
        return this;
    };
 
    $.fn.enableTab = function (tabIndex) {
 

        // Remove the ui-state-hidden class if it exists
        $(this).find('li:eq(' + tabIndex + ')').removeClass('ui-state-hidden');
 
        // Use the built-in enable function
        this.tabs("enable", tabIndex);
 
        // Enable chaining
        return this;


    };
 
})(jQuery);

$('.form-trend').ajaxForm({
    type: 'POST',
    delegation: true, // for live response
    dataType: 'json',
    beforeSubmit: function() {
        $.fancybox.showLoading();
    },
    success: function(response) {

        if (response.trend == '1') {
            $follow_place_holder = $('.count_follow_' + response.entry);
            var follow_count = parseInt($follow_place_holder.html());
            if (response.action == '-1') {
                $('.btn-follow-' + response.entry).text('Follow');
                $follow_place_holder.text(eval(follow_count - 1));
            } else {
                $('.btn-follow-' + response.entry).text('Following');
                $follow_place_holder.text(eval(follow_count + 1));
            }
        }

        if (response.trend == '2') {
            if (response.action == '-1') {
                $('.btn-favorite-' + response.entry).text('Add Favorite');
            } else {
                $('.btn-favorite-' + response.entry).text('Favorite');
            }
        }
        if (response.trend == '3') {
            $star_place_holder = $('.count_star_' + response.entry);
            star_count = parseInt($star_place_holder.html());
            if (response.action == '-1') {
                $star_place_holder.text(eval(star_count - 1));
            } else {
                $star_place_holder.text(eval(star_count + 1));
            }
        }
        if(response.reload === true) {
            window.location.reload();
        }
    },
    complete: function() {
        $.fancybox.hideLoading();
    }
});