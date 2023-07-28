jQuery(document).ready(function($) {
    var searchInput = $('#glossary-search-input');
    var searchClearBtn = $('<button class="glossary-search-clear">x</button>');
    var noTermsMessage = $('<p class="no-terms-message">No glossary terms found.</p>');

    // Append clear button to the search container
    $('.glossary-search').append(searchClearBtn);

    // Clear search input when clear button is clicked
    searchClearBtn.on('click', function() {
        searchInput.val('');
        searchInput.trigger('keyup');
    });

    // Toggle clear button visibility based on search input value
    searchInput.on('keyup', function() {
        var value = $(this).val().toLowerCase();
        var visibleChildTerms = $('.glossary-terms-list li').filter(function() {
            return $(this).text().toLowerCase().indexOf(value) > -1;
        });

        // Show/hide child terms based on search input
        $('.glossary-terms-list li').hide();
        visibleChildTerms.show();

        // Check if any child terms are visible
        var hasVisibleChildTerms = visibleChildTerms.length > 0;

        // Show/hide main alphabet heading
        $('.gloss-letter').each(function() {
            var sublist = $(this).next('.glossary-terms-sublist');
            var hasVisibleTerms = sublist.find('li:visible').length > 0;

            if (!hasVisibleTerms) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        // Show/hide 'No glossary terms found' message
        if (!hasVisibleChildTerms && !noTermsMessage.is(':visible')) {
            $('.glossary-search').after(noTermsMessage);
        } else if (hasVisibleChildTerms && noTermsMessage.is(':visible')) {
            noTermsMessage.remove();
        }

        // Toggle clear button visibility based on search input value
        if (value.length > 0) {
            searchClearBtn.show();
        } else {
            searchClearBtn.hide();
        }
    });
});
